<?php

namespace App\Services;

use Amp\Parallel\Worker\Internal\Job;
use App\BillOfQuantity;
use App\JobProject;
use App\JobProjectTask;
use App\NewProject;
use App\NewProjectTask;
use Illuminate\Http\Request;
use Rubix\ML\Classifiers\KNearestNeighbors;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Transformers\OneHotEncoder;
use Rubix\ML\Transformers\NumericStringConverter;

class AiBoqService
{
    private $request;

    public function generateBoq(Request $request)
    {
        $this->request = $request;
        $project_id = $request->project_id;

        if($project_id){
            $project = NewProject::find($project_id);
            if($project){
                return $this->getBoqByProject(
                    $project->project_type,$project->location, $project->total_contract,
                    $project->total_contract,
                );
            }
        }
    }

    public function getBoqByProject($type, $location, $budget)
    {
        $type = 'G+1';
        $projects = NewProject::where('project_type',$type)->get();

        if($projects->count() < 30){
            $projects = NewProject::all();
        }

        $projectData = [];

        // Prepare project features
        foreach ($projects as $project) {
            $jobProject = JobProject::where('project_id', $project->id)->first();
            if($jobProject){

                $projectData[] = [
                    'id' => $jobProject->id,
                    'type' => $project->project_type,
                    'location' => $project->location,
                    'budget' => $jobProject->total_budget,
                ];
            }
        }

        if(empty($projectData)){
            return $this->generateBoqFromArea();
        }

        // Encode features as numeric
        $typeMap = []; $locationMap = []; $typeCounter = 0; $locationCounter = 0;
        foreach ($projectData as &$proj) {
            if (!isset($typeMap[$proj['type']])) $typeMap[$proj['type']] = $typeCounter++;
            if (!isset($locationMap[$proj['location']])) $locationMap[$proj['location']] = $locationCounter++;
            $proj['type_num'] = $typeMap[$proj['type']];
            $proj['location_num'] = $locationMap[$proj['location']];
        }
        unset($proj);

        $newSample = [
            'type_num' => $typeMap[$type] ?? $typeCounter,
            'location_num' => $locationMap[$location] ?? $locationCounter,
            'budget' => $budget,
        ];

        // Compute Euclidean distance
        foreach ($projectData as &$proj) {
            $proj['distance'] = sqrt(
                pow($proj['type_num'] - $newSample['type_num'], 2) +
                pow($proj['location_num'] - $newSample['location_num'], 2) +
                pow($proj['budget'] - $newSample['budget'], 2)
            );
        }
        unset($proj);

        // Get top 2 nearest projects
        usort($projectData, fn($a, $b) => $a['distance'] <=> $b['distance']);
        $nearestProjects = array_slice($projectData, 0, 2);

        // Aggregate BOQ
        $aggregated = [];
        foreach ($nearestProjects as $proj) {
            $jobProject = JobProject::where('project_id', $proj['id'])->first();
            if (!$jobProject) continue;
            foreach ($jobProject->tasks as $task) {
                foreach ($task->items as $item) {
                    $key = $task->task_name . '_' . $item->item_description;
                    if (!isset($aggregated[$key])) {
                        $aggregated[$key] = [
                            'task' => $task->task_name,
                            'description' => $item->item_description,
                            'unit' => $item->unit,
                            'qty' => 0,
                            'rate' => 0,
                            'count' => 0
                        ];
                    }
                    $aggregated[$key]['qty'] += $item->qty;
                    $aggregated[$key]['rate'] += $item->rate;
                    $aggregated[$key]['count'] += 1;
                }
            }
        }

        // Average quantities and rates
        $boqForm = [];
        $totalOriginal = 0;
        foreach ($aggregated as $data) {
            $avgQty = $data['qty'] / $data['count'];
            $avgRate = $data['rate'] / $data['count'];
            $totalOriginal += $avgQty * $avgRate;
            $boqForm[] = [
                'task' => $data['task'],
                'description' => $data['description'],
                'unit' => $data['unit'],
                'qty' => $avgQty,
                'rate' => $avgRate,
                'total' => $avgQty * $avgRate,
            ];
        }

        // Scale quantities so total matches given budget
        $scaleFactor = $budget / $totalOriginal;
        foreach ($boqForm as &$item) {
            $item['qty'] = round($item['qty'] * $scaleFactor, 2);
            $item['total'] = round($item['qty'] * $item['rate'], 2);
        }
        unset($item);

        $boq_budget = array_sum(array_column($boqForm,'total'));
        if($budget != $boq_budget){
            if($budget > $boq_budget){
                $boqForm[0]['rate'] += $budget - $boq_budget;
                $boqForm[0]['total'] += $budget - $boq_budget;
            }else{
                $boqForm[0]['rate'] -= $budget - $boq_budget;
                $boqForm[0]['total'] -= $budget - $boq_budget;
            }
        }

        $boqData = $this->formatBoqForm($boqForm,$budget);


        // dd($budget,array_sum(array_column($boqForm,'total')),$boqData);
        return $boqData;
    }

    private function formatBoqForm(&$boqForm,$budget){
        $boqData = [];

        $change_task = [
            'preliminaries' => 'Preliminaries & Mobilization',
            'PRELIMINARIES AND MOBILIZATION WORKS' => 'Preliminaries & Mobilization',
            'mobilization' => 'Preliminaries & Mobilization',
            'preliminariesandmobilization' => 'Preliminaries & Mobilization',
            'siteworks' => 'Site Preparation & Earth Work',
            'earthwork' => 'Site Preparation & Earth Work',
            'excavationbackfilling' => 'Site Preparation & Earth Work',
            'antitermiteworks' => 'Anti-termite Works',
            'substructure' => 'Sub Structure',
            'superstructure' => 'Super Structure',
            'blockworks' => 'Block Works',
            'waterproofingworks' => 'Water Proofing Works',
            'plasteringworks' => 'Plastering Works',
            'gates' => 'Gates and Car Sheds',
            'Plumbing & Sanitary Work' => 'Plumbing & Sanitary Work',
            'Electrical Work' => 'Electrical Work',
            'Miscellaneous Work' => 'Miscellaneous Work',
        ];

        foreach($boqForm as $item){
            // 1. Remove Arabic characters
            $task = preg_replace('/[\x{0600}-\x{06FF}]+/u', '', $item['task']);

            // 2. Remove all spaces
            $task = str_replace(' ', '', $task);

            // 2. Remove non-letters (like spaces, dashes, commas, etc.)
            $task = preg_replace('/[^A-Za-z]/', '', $task);

            // 3. Convert to lowercase
            $task = strtolower($task);

            if(isset($change_task[$task])){
                $item['task'] = $change_task[$task];
            }



            if(!isset($boqData[$item['task']])){
                $boqData[$item['task']] = [];
            }
            if($item['rate'] <= 0){
                continue;
            }

            $rate =  $item['qty'] > 2 ? $item['rate'] : $item['total'];

            $description = $item['description'];

            // Remove Arabic characters
            $description = preg_replace('/[\x{0600}-\x{06FF}]+/u', '', $description);

            // Remove extra spaces and trim
            $description = preg_replace('/\s+/u', ' ', $description);

            // Trim spaces at start and end
            $description = trim($description);

            // Extract numeric values (handles commas and decimals)
            preg_match_all('/\d[\d,\.]*/', $description, $matches);

            // Example: Replace numbers with a placeholder (like X)
            $new_desc = preg_replace('/\d[\d,\.]*/', $rate, $description);

            $boqData[$item['task']][] = [
                'description' =>   $new_desc,
                'unit' => $item['unit'],
                'qty' => $item['qty'] > 2 ? $item['qty'] : 1,
                'rate' => $rate,
                'total' => $item['total'],
            ];
        }

        return $boqData;

    }

    public function generateBoqFromArea(){
        $project_id = $this->request->project_id;
        $work_type = $this->request->work_type;
        $total_area = $this->request->total_area;
        $total_area_unit = $this->request->total_area_unit;
        $construction_area = $this->request->construction_area;
        $construction_area_unit = $this->request->construction_area_unit;

        $new_project = NewProject::find($project_id);

        $project_tasks = NewProjectTask::with('items')->orderBy('position')->get();

        dd($project_tasks);
    }
}

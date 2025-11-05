<?php

namespace App\Http\Controllers\backend;

use App\EngineerReport;
use App\EngineerReportDetails;
use App\EngineerReportImage;
use App\GnattChart;
use App\GnattChartItem;
use App\Http\Controllers\Controller;
use App\JobProject;
use App\JobProjectTask;
use App\NewProject;
use App\ProjectDocument;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Random\Engine;

class EngineerReportController extends Controller
{

    public function index(Request $request){
        $role = auth()->user()->role;
        if($role && $role->slug == 'administration'){
            $reports = EngineerReport::with(['new_project', 'task', 'item'])->orderBy('date', 'desc')->paginate(20);
        }else{
            $reports = EngineerReport::with(['new_project', 'task', 'item'])
                ->where('engineer_id', auth()->user()->employee_id)
                ->orderBy('date', 'desc')
                ->get();
        }

        $projects = JobProject::with(['new_project', 'tasks'])->get();

        return view('backend.engineer_report.index', compact('reports', 'projects'));
    }

    public function store(Request $request){
        // dd($request->all());
        $request->validate([
            'date' => 'required|date_format:d/m/Y',
            'project_id' => 'required|integer',
            'task_id' => 'required|integer',
            'item_id' => 'required|integer',
            'work_details.*' => 'nullable|string',
            'progress.*' => 'nullable|numeric',
            'captured_image.*' => 'nullable|string',
            // 'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image'   => 'required|array',
            'image.*' => 'nullable|image|max:2048',
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y',
        ]);

        // return $request->all();
        // dd($request->all());

        $project = JobProject::find($request->project_id);

        try {
            // Step 1: Store engineer report
            $report = EngineerReport::create([
                'job_project_id' => $project->id,
                'new_project_id' => $project->project_id,
                'engineer_id' => auth()->user()->employee_id,
                'task_id' => $request->task_id,
                'item_id' => $request->item_id,
                'date' => date('Y-m-d'),
                'total_progress' => array_sum($request->progress) / count($request->progress),
                'start_date' => change_date_format($request->start_date),
                'end_date' => change_date_format($request->end_date),
                'company_id' => $project->company_id ?? 0,
            ])->load(['details', 'new_project', 'documents']);

            // Step 2: Loop through details (assuming all fields are arrays of equal length)
            foreach ($request->work_details as $index => $work_details) {
                $detail = EngineerReportDetails::create([
                    'engineer_report_id' => $report->id,
                    'task_id' => $request->task_id,
                    'item_id' => $request->item_id,
                    'job_project_id' => $request->project_id,
                    'work_details' => $request->work_details[$index],
                    'progress' => $request->progress[$index],
                ]);
            }

            // Step 3: Handle uploaded images (file)
            if ($request->hasFile('image')){
                foreach ($request->file('image') as $file) {
                    $name = $file->getClientOriginalName();
                    $name = pathinfo($name, PATHINFO_FILENAME);
                    $ext = $file->getClientOriginalExtension();
                    $project_doc_name = $name . time() . '.' . $ext;
                    $file->storeAs('public/upload/project-document', $project_doc_name);

                    ProjectDocument::create([
                        'project_id' =>  $project->project_id,
                        'file_name' => $project_doc_name,
                        'ext' => $ext,
                        'job_project_id' => $project->id,
                        'engineer_report_id' => $report->id,
                    ]);
                }
            }

            // Step 4: Handle base64 captured images
            if ($request->has('captured_image')) {
                foreach ($request->captured_image as $key => $base64Image) {
                    if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                        $ext = strtolower($type[1]);
                        $image = base64_decode(substr($base64Image, strpos($base64Image, ',') + 1));
                        $name = time() . $key . '.' . $ext;
                        if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'gif'])) {
                            Storage::put("public/upload/project-document/{$name}", $image);
                        }
                    }

                    ProjectDocument::create([
                        'project_id' =>  $project->project_id,
                        'file_name' => $name,
                        'ext' => $ext,
                        'job_project_id' => $project->id,
                        'engineer_report_id' => $report->id,
                    ]);
                }
            }

            DB::commit();

            return back()->with(['alert-type' => 'success', 'message' => 'Report submitted successfully.']);

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return back()->with(['alert-type' => 'error', 'message' => 'Failed to submit report.']);
        }
    }

    public function show(EngineerReport $report){
        $report->load(['details', 'new_project', 'documents']);

        return view('backend.engineer_report.show', compact('report'));

    }

    public function edit(EngineerReport $report){
        $report->load(['details', 'new_project']);
        $projects = JobProject::with(['new_project', 'tasks'])->get();
        return view('backend.engineer_report.edit', compact('report', 'projects'));
    }

    public function update(Request $request, EngineerReport $report){

        $request->validate([
            'date' => 'required|date_format:d/m/Y',
            'project_id' => 'required|integer',
            'task_id' => 'required|integer',
            'item_id' => 'required|integer',
            'work_details.*' => 'nullable|string',
            'start_date' => 'required|date_format:d/m/Y',
            'end_date' => 'required|date_format:d/m/Y',
        ]);

        $project = JobProject::find($request->project_id);
        DB::beginTransaction();
        try {
            // Update engineer report
            $report->update([
                'job_project_id' => $project->id,
                'new_project_id' => $project->project_id,
                'engineer_id' => auth()->user()->employee_id,
                'task_id' => $request->task_id,
                'item_id' => $request->item_id,
                'date' => change_date_format($request->date),
                'total_progress' => array_sum($request->progress) / count($request->progress),
                'start_date' => change_date_format($request->start_date),
                'end_date' => change_date_format($request->end_date),
            ]);

            $report->details()->delete(); // Clear existing details

            // Step 2: Loop through details (assuming all fields are arrays of equal length)
            foreach ($request->work_details as $index => $work_details) {
                $detail = EngineerReportDetails::create([
                    'engineer_report_id' => $report->id,
                    'task_id' => $request->task_id,
                    'item_id' => $request->item_id,
                    'job_project_id' => $request->project_id,
                    'work_details' => $request->work_details[$index],
                    'progress' => $request->progress[$index],
                ]);
            }

            // Step 3: Handle uploaded images (file)
            foreach ($request->image as $imageGroup) {
                foreach ($imageGroup as $file) {
                    $name = $file->getClientOriginalName();
                    $name = pathinfo($name, PATHINFO_FILENAME);
                    $ext = $file->getClientOriginalExtension();
                    $project_doc_name = $name . time() . '.' . $ext;
                    $file->storeAs('public/upload/project-document', $project_doc_name);

                    ProjectDocument::create([
                        'project_id' => $report->new_project_id,
                        'file_name' => $project_doc_name,
                        'ext' => $ext,
                        'job_project_id' => $report->job_project_id,
                        'engineer_report_id' => $report->id,
                    ]);
                }
            }

            // Step 4: Handle base64 captured images
            if ($request->has('captured_image')) {
                foreach ($request->captured_image as $key => $base64Image) {
                    if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                        $ext = strtolower($type[1]);
                        $image = base64_decode(substr($base64Image, strpos($base64Image, ',') + 1));
                        $name = time() . $key . '.' . $ext;
                        if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'gif'])) {
                            Storage::put("public/upload/project-document/{$name}", $image);
                        }
                    }

                    ProjectDocument::create([
                        'project_id' =>  $project->project_id,
                        'file_name' => $name,
                        'ext' => $ext,
                        'job_project_id' => $project->id,
                        'engineer_report_id' => $report->id,
                    ]);
                }
            }

            DB::commit();

            return back()->with(['alert-type' => 'success', 'message' => 'Report updated successfully.']);

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return back()->with(['alert-type' => 'error', 'message' => 'Failed to update report.']);
        }
    }

    public function destroy(EngineerReport $report){
        try {
            $report->delete();
            return back()->with(['alert-type' => 'success', 'message' => 'Report deleted successfully.']);
        } catch (\Exception $e) {
            return back()->with(['alert-type' => 'error', 'message' => 'Failed to delete report.']);
        }
    }



    public function approve(Request $request, EngineerReport $report){

        $gantt_chart = GnattChart::where('job_project_id', $report->job_project_id)->first();
        $start_date1 = Carbon::parse($report->start_date);
        $start_date2 = Carbon::parse($report->start_date);

        $end_date1 = Carbon::parse($report->end_date);
        $end_date2 = Carbon::parse($report->end_date);

        $maxStartDate = $start_date1->max($start_date2);
        $maxEndDate = $end_date1->max($end_date2);
        $diff =  $maxStartDate->diff($maxEndDate);
        $days = $diff->days;

        if (!$gantt_chart) {
            $gantt_chart = GnattChart::create([
                'name' => $report->new_project->name,
                'job_project_id' => $report->job_project_id,
                'project_id' => $report->new_project_id,
                'start_date' => $report->start_date,
                'end_date' => $report->end_date,
                'quotation_id' => $report->lpo_projects_id,
                'progress' => $report->total_progress,
                'status' => 1,
                'company_id' => $report->company_id,
            ]);
        }else{
            // Update the gantt chart with the maximum start and end dates
            $gantt_chart->update([
                'start_date' => $maxStartDate->toDateString(),
                'end_date' => $maxEndDate->toDateString(),
                'progress' => $gantt_chart->progress + $report->total_progress,
            ]);
        }

        if ($gantt_chart) {

            $item = GnattChartItem::where('gnatt_chart_id', $gantt_chart->id)
                ->where('project_task_id', $report->task_id)
                ->first();

            $job_project_task = JobProjectTask::find($report->task_id);

            if (!$item) {
                GnattChartItem::create([
                    'name' => $job_project_task->task_name,
                    'gnatt_chart_id' => $gantt_chart->id,
                    'project_task_id' => $report->task_id,
                    'progress' => $report->total_progress,
                    'assign_by' => optional($report->engineer)->full_name,
                    'color' => '#ff5349',
                    'start_date' => $maxStartDate->toDateString(),
                    'end_date' => $maxEndDate->toDateString(),
                    'progress' => $report->total_progress,
                    'total_day' => $days
                ]);
            }else{
                GnattChartItem::updated([
                    'progress' => $item->progress + $report->total_progress,
                    'end_date' => $maxEndDate->toDateString(),
                    'progress' => $item->progress + $report->total_progress,
                    'days' => $days,
                    'color' => '#ff5349',
                ]);
            }
        }

        $job_project = JobProject::find($report->job_project_id);
        $report->update(['status' => 'approve']);

        return back()->with(['alert-type' => 'success', 'message' => 'The report has been approved successfully']);
    }
}

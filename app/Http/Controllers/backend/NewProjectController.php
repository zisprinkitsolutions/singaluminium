<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Imports\ProspectImporter;
use App\JobProject;
use App\LpoProject;
use App\Models\Payroll\EmployeeTemp;
use Illuminate\Http\Request;
use App\NewProject;
use App\PartyInfo;
use App\ProjectDocument;
use App\Subsidiary;
use App\VatRate;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class NewProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function dateFormat($date)
    {
        $old_date = explode('/', $date);

        $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }

    public function index(Request $request)
    {
        Gate::authorize('Project');
        $search = $request->search;
        $company_id = $request->company_id ? $request->company_id : null;
        $projects = NewProject::orderByRaw("CAST(REGEXP_SUBSTR(project_no, '[0-9]+$') AS UNSIGNED)")
            ->when($search, function($q) use ($search){
                $q->where(function($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('plot', 'like', '%' . $search . '%')
                        ->orWhere('project_no', 'like', '%' . $search . '%');
                });
            })
            ->when($company_id == 'seabridge' , function($q) use ($company_id){
                $q->where('company_id', null);
            })
            ->when( $company_id && $company_id != 'seabridge'  , function($q) use ($company_id){
                $q->where('company_id', $company_id);
            });
            // ->paginate(25);
        $cal_projects = (clone $projects);
        $projects = $projects->latest()->paginate(25);

        $data = [];
        $data['total_ps_budget'] = $cal_projects->sum('ps_budget');
        $data['total_estimation'] = $cal_projects->sum('estimation');
        $data['total_variation'] = $cal_projects->sum('variation');
        $data['total_contract'] = $cal_projects->sum('total_contract');
        $data['total_vat'] = $cal_projects->sum('vat');
        $data['total_contract_value'] = $cal_projects->sum('contract_value');

        // dd($total_ps_budget);

        $pInfos = PartyInfo::where('pi_type', 'Customer')->get();
        $subsidiarys = Subsidiary::get();

        return view('backend.new-project.index', compact('projects', 'pInfos','search', 'subsidiarys','company_id', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     return $request;
    //     $request->validate([
    //         'name'=>'required',
    //         'project_no'=>'required',
    //         'project_type'=>'required',
    //         'project_code'=>'required',
    //         'party_id'=>'required',
    //         'location'=>'required',
    //     ]);

    //     $project = new NewProject;
    //     $project->name = $request->name;
    //     $project->project_no = $request->project_no;
    //     $project->project_type = $request->project_type;
    //     $project->project_code = $request->project_code;
    //     $project->party_id = $request->party_id;
    //     $project->mobile_no = $request->mobile_no;
    //     $project->location = $request->location;
    //     $project->consulting_agent = $request->consulting_agent;
    //     $project->start_date = $request->start_date?$this->dateFormat($request->start_date):null;
    //     $project->end_date = $request->end_date?$this->dateFormat($request->end_date):null;
    //     $project->details = $request->details;
    //     $project->total_amount = $request->contact_amount;
    //     $project->project_status = $request->project_status;
    //     $project->handover_on = $request->handover_on?$this->dateFormat($request->handover_on):null;
    //     $project->save();


    //     $notification = [
    //         'message'=>'Add Success',
    //         'alert-type'=>'success'
    //     ];
    //     if($request->type==1){
    //         return $project;
    //     }else{
    //         return back()->with($notification);
    //     }
    // }

    public function store(Request $request)
    {
        Gate::authorize('ProjectManagement_Create');

        $request->validate([
            'projects' => 'required|array|min:1',
            'projects.*.name' => 'required|string|max:255',
            'projects.*.project_no' => 'required|string|max:255',
            'projects.*.project_type' => 'required|string|max:255',
            // 'projects.*.company_id' => 'required',
            'projects.*.party_id' => 'required|integer',
            'projects.*.location' => 'required|string|max:255',
            // Add more validation rules as needed
        ]);

        $anyUpdated = false;
        $anyCreated = false;

        foreach ($request->projects as $projData) {
            if (isset($projData['id'])) {
                $project = NewProject::find($projData['id']);
                if (!$project) {
                    continue; // or handle missing project case
                }
                $anyUpdated = true;
            } else {
                $project = new NewProject();
                $anyCreated = true;
            }

            // Assign fields (same as before)...
            $project->name            = $projData['name'];
            $project->project_no      = $projData['project_no'];
            $project->company_id      = $projData['company_id'] ?? null;
            $project->plot            = $projData['plot'];
            $project->project_type    = $projData['project_type'];
            $project->project_code    = $projData['project_no'];
            $project->party_id        = $projData['party_id'];
            $project->mobile_no       = $projData['mobile_no'] ?? null;
            $project->location        = $projData['location'];
            $project->details         = $projData['details'] ?? null;
            $project->handover_on     = isset($projData['handover_on']) ?  $this->dateFormat($projData['handover_on']) : null;
            $project->engineer        = $projData['engineer'] ?? null;
            $project->short_name      = $projData['short_name'] ?? null;
            $project->consultant      = $projData['consultant'] ?? null;
            $project->contract_value  = $projData['contract_value'] ?? null;
            $project->vat             = $projData['vat'] ?? null;
            $project->variation       = $projData['variation'] ?? null;
            $project->total_contract  = $projData['total_contract'] ?? null;
            $project->estimation      = $projData['estimation'] ?? null;
            $project->ps_budget       = $projData['ps_budget'] ?? null;
            $project->status          = $projData['status'] ?? null;
            $project->date            = isset($projData['date']) ?  $this->dateFormat($projData['date']): null;
            $project->insurance       = $projData['insurance'] ?? null;
            $project->contract        = $projData['contract'] ?? null;
            $project->contract_period = $projData['contract_period'] ?? null;
            $project->area            = $projData['area'] ?? null;
            $project->file_no         = $projData['file_no'] ?? null;
            $project->start_date      = isset($projData['start_date']) ?  $this->dateFormat($projData['start_date']) : null;
            $project->end_date        = isset($projData['deadline']) ?  $this->dateFormat($projData['deadline']) : null;

            $project->save();
        }

        // Determine message
        if ($anyUpdated && !$anyCreated) {
            $message = 'Projects updated successfully!';
        } elseif ($anyCreated && !$anyUpdated) {
            $message = 'Projects created successfully!';
        } else {
            $message = 'Projects created and updated successfully!';
        }

        return redirect()->back()->with('success', $message);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // dump($id);
        $projects = NewProject::all();
        $project_info = NewProject::find($id);
        $gantt_chart = $project_info->gantt_charts()->with('items')->orderBy('id', 'desc')->first();
        $pInfos = PartyInfo::where('pi_type', 'Customer')->get();
        $boq = $project_info->boqs()->orderBy('id','desc')->first();

        $boq_id = $boq ? $boq->id : null;

        // dd($boq_id);

        $quotation = LpoProject::where('project_id', $boq_id)->orderBy('id','desc')->first();
        // dd($quotation);
        $quotation_id = $quotation ? $quotation->id : null;
        $documents = ProjectDocument::where('project_id', $project_info->id)->get();
        return view('backend.new-project.view', compact('projects', 'project_info', 'pInfos','gantt_chart','documents'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Gate::authorize('ProjectManagement_Edit');

        $projects = NewProject::all();
        $project = NewProject::find($id);
        $pInfos = PartyInfo::where('pi_type', 'Customer')->get();
        $subsidiarys = Subsidiary::get();
        return view('backend.new-project.edit', compact('projects', 'project', 'pInfos','subsidiarys'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Gate::authorize('ProjectManagement_Edit');


        $request->validate([
            'name'=>'required',
            'project_no'=>'required',
            'project_type'=>'required',
            // 'company_id'=>'required',
            'project_code'=>'required',
            'party_id'=>'required',
            'location'=>'required',
        ]);

        $project = NewProject::find($id);
        $project->name = $request->name;
        $project->company_id      = $request->company_id;
        $project->project_no = $request->project_no;
        $project->project_type = $request->project_type;
        $project->project_code = $request->project_code;
        $project->party_id = $request->party_id;
        $project->mobile_no = $request->mobile_no;
        $project->location = $request->location;
        $project->consulting_agent = $request->consulting_agent;
        $project->start_date = $request->start_date?$this->dateFormat($request->start_date):null;
        $project->end_date = $request->end_date?$this->dateFormat($request->end_date):null;
        $project->details = $request->details;
        $project->total_amount = $request->contact_amount;
        $project->project_status = $request->project_status;
        $project->handover_on = $request->handover_on?$this->dateFormat($request->handover_on):null;

        $project->save();

        $notification = [
            'message'=>'update Success',
            'alert-type'=>'success'
        ];
        return redirect()->route('new-project.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function project_document_view(Request $request){
        $project = JobProject::find($request->id);
        $documents  = ProjectDocument::where('job_project_id', $project->id)->get();
        return view('backend.new-project.document-view', compact('documents', 'project'));
    }

    public function project_document_store(Request $request){
        Gate::authorize('ProjectManagement_Create');

        if($request->hasFile('voucher_scan')){
            $files = $request->file('voucher_scan');
            foreach($files as $file){
                $name = $file->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext = $file->getClientOriginalExtension();
                $project_doc_name = $name . time() . '.' . $ext;
                $file->storeAs('public/upload/project-document', $project_doc_name);
                ProjectDocument::create([
                    'project_id' =>  $request->project_id,
                    'file_name' => $project_doc_name,
                    'ext' => $ext,
                    'name' => $name,
                    'job_project_id' => $request->job_project_id,
                ]);
            }
            $notification = [
                'message'=>'Add Success',
                'alert-type'=>'success'
            ];
            return back()->with($notification);
        }else{
            $notification = [
                'message'=>'Somthing Wrong',
                'alert-type'=>'warningt'
            ];
            return back()->with($notification);
        }
    }

    public function ganttChart(NewProject $project){
        $project = $project->gantt_charts()->orderBy('id', 'desc')->first();
        if(!$project){
            return back()->with(['alert-type' => 'error', 'message' => 'The Gantt Chart does not exists']);
        }
        $customers = PartyInfo::all();
        $vats = VatRate::orderBy('value')->get();
        $employees = EmployeeTemp::orderBy('full_name')->get();
        return view('backend.job-project.track', compact('project', 'customers', 'vats', 'employees'));
    }

    public function ganttChartPdf(NewProject $project){
        $project = $project->gantt_charts()->orderBy('id', 'desc')->first();
    }

     public function prospect_excel_import(Request $request){
           $request->session()->put('token', $request->token);
        $request->session()->put('project_id', $request->boq_project_name);

        $import = new ProspectImporter();
        Excel::import($import, $request->excel_file);

     $message = '✅ <strong>The BOQ has been imported successfully.</strong>';
    $skippedMessages = $import->getSkippedRows();

    if (!empty($skippedMessages)) {
        $formattedMessages = "<div style='text-align: left; margin-top: 10px;'>";
        $formattedMessages .= "<p>⚠️ <strong>However, some rows were skipped:</strong></p>";
        $formattedMessages .= "<ul style='padding-left: 20px;'>";
        foreach ($skippedMessages as $msg) {
            $formattedMessages .= "<li>🔸 " . e($msg) . "</li>";
        }
        $formattedMessages .= "</ul></div>";

        return back()->with([
            'alert-type' => 'success',
            'message_import' => $message . $formattedMessages
        ]);
    }

    return back()->with([
        'alert-type' => 'success',
        'message_import' => $message
    ]);

    }
}

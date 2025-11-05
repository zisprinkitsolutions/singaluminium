<?php

namespace App\Http\Controllers\backend;

use App\GnattChart;
use App\GnattChartItem;
use App\Http\Controllers\Controller;
use App\JobProject;
use App\LpoProject;
use App\Models\Payroll\Employee;
use App\Models\Payroll\EmployeeTemp;
use App\PartyInfo;
use App\ProjectDocument;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GnattChartController extends Controller
{
    public function index(){
        Gate::authorize('Gantt_Chart');

        $user = auth()->user();
        $role = auth()->user()->role;

        $emplyeee = null;

        if($user->employee_id){
            $employee = Employee::find($user->employee_id);
        }

        if($role && $role->name == 'ENGINEERING'){
            $gnatts = GnattChart::with(['items' => function($q) use($employee) {
                $q->where('assign_by',$employee->full_name);
        }])->whereHas('items',function($q) use($employee){
            $q->where('assign_by', $employee->full_name);
        })->orderBy('id','desc')->paginate(30);
        }else{
            $gnatts = GnattChart::orderBy('id','desc')->paginate(30);
        }

        $parties = PartyInfo::where('pi_type', 'customer')->get();
        return view('backend.gnatt-chart.index',compact('gnatts', 'parties'));
    }

    public function create(){
        Gate::authorize('ProjectManagement_Create');
        $employees= EmployeeTemp::orderBy('full_name')->get();
        $parties = PartyInfo::with(['quotations.tasks','quotations.jobProjects.tasks'])->where('pi_type', 'customer')->orderBy('pi_name')->get();
        return view('backend.gnatt-chart.create',compact('parties','employees'));
    }
    public function ajaxGanttChart(Request $request){
        Gate::authorize('ProjectManagement_Create');
        $employees = EmployeeTemp::orderBy('full_name')->get();
        $project = JobProject::with('quotation')->where('id', $request->project_id)->first();
        $quotation = $project->quotation;
        $quotation->load(['tasks','jobProjects.tasks']);
        $chart = GnattChart::where('job_project_id', $request->project_id)->first();
        $chart_have = false;
        if($chart){
           $chart_have = true;
        }
        // $chart = GnattChart::where('job_project_id', $project->id)->first();
        // $chart->load('items');
        // // return view('backend.gnatt-chart.show',compact('chart'));
        // $view = view('backend.gnatt-chart._ajax_gantt_chart', compact('employees', 'chart'))->render();

        $view = view('backend.gnatt-chart._ajax_gantt_chart', compact('employees', 'project' ,'chart_have' ,'project'))->render();

        return response([
            'view' => $view,
            'project' => $project,
            'quotation' => $quotation,
            'employees' => $employees,
            'chart_have' => $chart_have
        ]);
    }
    public function ajaxGanttChartView(Request $request){
        $chart = GnattChart::where('job_project_id', $request->project_id)->first();
        $chart->load('items');
        return response([
            'status' => 'success',
            'chart' => $chart
        ]);
    }

    public function store(Request $request){

        Gate::authorize('ProjectManagement_Create');


        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);


        try {
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $quotation = LpoProject::find($request->quotation_id);

            if($quotation->boq){
                $project_id = $quotation->boq->project_id ?? null;
            }

            $gantt = GnattChart::create([
                'name' => $request->name,
                'party_id' => $request->customer_id,
                'quotation_id' => $request->quotation_id,
                'job_project_id' => $request->project_id,
                'end_date' => $request->end_date1 ? $this->changeDate($request->end_date1) : '',
                'start_date' => $request->start_date1 ? $this->changeDate($request->start_date1): '',
                'project_id' => $project_id,
            ]);

            $task_names = $request->input('task_name');

            foreach($task_names as $key => $name){
                $end_date = $this->changeDate($request->end_date[$key]);
                $start_date = $this->changeDate($request->start_date[$key]);

                $start = new DateTime($start_date);
                $end = new DateTime($end_date);

                $diff = $start->diff($end);
                $days = $diff->days;
                $task_id = $request->project_task_id[$key] ?? null;
                GnattChartItem::create([
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'total_day' => $days,
                    'color' => $request->color[$key],
                    'priority' => $request->priority[$key],
                    'assign_by' => $request->assign_to[$key],
                    'gnatt_chart_id' => $gantt->id,
                    'name' => $name,
                    'project_task_id' => $task_id,
                ]);
            }

            if($request->hasFile('voucher_file')){
                $files = $request->file('voucher_file');
                $this->uploadFile($files, $gantt);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'The GnattChart has been save successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
      }    }

    public function edit( $id){
        Gate::authorize('ProjectManagement_Edit');
        $chart = GnattChart::where('job_project_id' , $id)->first();

        $employees= EmployeeTemp::orderBy('full_name')->get();
        $parties = PartyInfo::with(['quotations.tasks','quotations.jobProjects.tasks'])->whereHas('quotations')->orderBy('pi_name')->get();
        return view('backend.gnatt-chart.edit',compact('parties', 'chart', 'employees'));
    }

    public function update(Request $request, GnattChart $chart){
        Gate::authorize('ProjectManagement_Edit');
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);
        try {
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $quotation = LpoProject::find($request->quotation_id);

            if($quotation->boq){
                $project_id = $quotation->boq->project_id ?? null;
            }

            $chart->update([
                'name' => $request->name,
                'party_id' => $request->customer_id,
                'quotation_id' => $request->quotation_id,
                'job_project_id' => $request->project_id,
                'end_date' => $request->end_date1 ? $this->changeDate($request->end_date1) : '',
                'start_date' => $request->start_date1 ? $this->changeDate($request->start_date1): '',
                'project_id' => $project_id,
            ]);

            $chart->items->each->delete();

            $task_names = $request->input('task_name');

            foreach($task_names as $key => $name){
                $end_date = $this->changeDate($request->end_date[$key]);
                $start_date = $this->changeDate($request->start_date[$key]);

                $start = new DateTime($start_date);
                $end = new DateTime($end_date);

                $diff = $start->diff($end);
                $days = $diff->days;
                $task_id = $request->project_task_id[$key] ?? null;
                GnattChartItem::create([
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'total_day' => $days,
                    'color' => $request->color[$key],
                    'priority' => $request->priority[$key],
                    'assign_by' => $request->assign_to[$key],
                    'gnatt_chart_id' => $chart->id,
                    'project_task_id' => $task_id,
                    'name' => $name,
                ]);
            }

            if($request->hasFile('voucher_file')){
                $files = $request->file('voucher_file');
                $this->uploadFile($files, $chart);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'The GnattChart has been updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
      }
    }

    private function uploadFile($files,$chart){
        if($chart->job_project_id){
            foreach($files as $file){
                $name = $file->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext = $file->getClientOriginalExtension();
                $project_doc_name = $name . time() . '.' . $ext;
                $file->storeAs('public/upload/project-document', $project_doc_name);
                ProjectDocument::create([
                    'project_id' =>  $chart->project_id,
                    'file_name' => $project_doc_name,
                    'ext' => $ext,
                    'name' => $name,
                    'job_project_id' => $chart->job_project_id,
                ]);
            }
            return true;
        }else{
            return false;
        }
    }

    public function destroy($id)
    {
        Gate::authorize('ProjectManagement_Delete');
        $chart = GnattChart::where('job_project_id', $id)->first();

        $chart->load('items');
        $this->deleteChartItems($chart);
        $chart->delete();

        if (request()->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'The GanttChart has been deleted successfully'
            ]);
        }

        return back()->with(['alert-type' => 'success', 'message' => 'The GanttChart has been deleted successfully']);
    }
    public function getChartStatus($projectId)
    {
        $chart = GnattChart::where('job_project_id', $projectId)->first();

        return response()->json([
            'exists' => (bool)$chart,
            'approved' => $chart ? ($chart->status == 1) : false
        ]);
    }

    public function approve($id)
    {
        Gate::authorize('ProjectManagement_Approve');
        $chart = GnattChart::where('job_project_id', $id)->first();

        $chart->update(['status' => 1]);

        if (request()->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'The Gantt has been approved successfully'
            ]);
        }

        return redirect()->back()->with(['alert-type' => 'success', 'message' => 'The Gantt has been approved successfully']);
    }


    private function deleteChartItems($chart){
        foreach($chart->items as $item){
            $item->delete();
        }
    }

    public function itemDestroy(GnattChartItem $item){
        $item->delete();
        return back()->with(['alert-type' => 'success', 'message' => 'The item has been deleted successfully']);
    }

    private function changeDate($date){
        $date_array = explode('/', $date);
        $date_string = implode('-', $date_array);
        return date('Y-m-d', strtotime($date_string));
    }

    public function show(GnattChart $chart){
        $chart->load('items');
        // $documents = ProjectDocument::where('project_id', $chart->quotation_id)->get();
        // return view('backend.gnatt-chart.modal-view', compact('chart','documents'));
        return view('backend.gnatt-chart.show',compact('chart'));
    }

    public function report(GnattChart $chart){
        $chart->load('items');
        $documents = ProjectDocument::where('project_id', $chart->project_id)->get();

        return view('backend.gnatt-chart.report', compact('chart','documents'));
    }


}

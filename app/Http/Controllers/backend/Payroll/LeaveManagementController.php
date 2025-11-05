<?php

namespace App\Http\Controllers\backend\Payroll;

use App\Http\Controllers\Controller;
use App\Mapping;
use App\Models\Payroll\SalaryType;
use App\Models\AccountHead;
use App\Models\Payroll\Employee;
use App\Models\Payroll\EmployeeSalary;
use App\Models\Payroll\GradeWiseLiveList;
use App\Models\Payroll\LeaveDocument;
use App\Models\Payroll\LeaveInformation;
use App\Models\Payroll\Nationality;
use App\Models\Payroll\SalaryStructure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;

class LeaveManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $startDate = date('Y')."-01-01";
        $now = Carbon::now()->format('y-m-d');

        //Gate::authorize('app.mapping.index');
        $leave_info = LeaveInformation::orderBy('id', 'desc')->get();
        $employees = Employee::all();
        // dd($salaryStructure['1']['id']);
        return view('backend.payroll.leaveManagement.index', compact('leave_info', 'employees'));
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
    public function store(Request $request)
    {
        // dd($request);
        $employee_leave = new LeaveInformation();
        $employee_leave->employee_id = $request->employee_id;
        $employee_leave->leave_type = $request->leave_type;
        $employee_leave->from_date = $request->from_date;
        $employee_leave->to_date = $request->to_date;
        $employee_leave->days_leave = $request->days;
        $employee_leave->leave_reason = $request->leave_reason;
        $employee_leave->save();
        if($request->file('files')){
            foreach($request->file('files') as $file){
                $name= $file->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext= $file->getClientOriginalExtension();
                $studentImageName= $name.time().'.'.$ext;

                $file->storeAs( 'public/upload/employee-leave', $studentImageName);

                LeaveDocument::create([
                    'name'              => $name,
                    'filename'          => $studentImageName,
                    'leave_info_id'     => $employee_leave->id,
                    'extension'         => $ext
                ]);
            // $ext= $request->document2->getClientOriginalExtension();
            // $document_two= 'b'.time().$request->pi_name.'.'.$ext;
            // $emirates_id_upload = $request->file('document2')->storeAs( 'public/upload/service-provider', $document_two);
            // $draftCost->document2= $document_two;
            // $draftCost->extension2= $ext;
            }
        }
        $notification= array(
            'message'       => 'Employee Leave created successfully!',
            'alert-type'    => 'success'
        );
        return back()->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $nationality_info = Nationality::find($id);
        $nationalities = Nationality::orderBy('id', 'desc')->get();
        return view('backend.payroll.nationality.edit', compact('nationality_info', 'nationalities'));
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
        // $request->validate([
        //     'name' => 'required',
        // ]);
        $employee_leave = LeaveInformation::find($id);
        $employee_leave->employee_id = $request->employee_id;
        $employee_leave->leave_type = $request->leave_type;
        $employee_leave->from_date = $request->from_date;
        $employee_leave->to_date = $request->to_date;
        $employee_leave->days_leave = $request->days;
        $employee_leave->leave_reason = $request->leave_reason;
        $employee_leave->save();
        if($request->file('files')){
            foreach($request->file('files') as $file){
                $name= $file->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext= $file->getClientOriginalExtension();
                $studentImageName= $name.time().'.'.$ext;

                $file->storeAs( 'public/upload/employee-leave', $studentImageName);

                LeaveDocument::create([
                    'name'              => $name,
                    'filename'          => $studentImageName,
                    'leave_info_id'     => $employee_leave->id,
                    'extension'         => $ext
                ]);
            // $ext= $request->document2->getClientOriginalExtension();
            // $document_two= 'b'.time().$request->pi_name.'.'.$ext;
            // $emirates_id_upload = $request->file('document2')->storeAs( 'public/upload/service-provider', $document_two);
            // $draftCost->document2= $document_two;
            // $draftCost->extension2= $ext;
            }
        }

        $notification= array(
            'message'       => 'Update successfully!',
            'alert-type'    => 'success'
        );
        return redirect('leave-management')->with($notification);
    }

    public function employeeInfo(Request $request)
    {
        $startDate = "01-01-".date('Y');
        $now = Carbon::now()->format('d-m-y');
        // return 1;
        $emp_name = Employee::find($request->emp);
        $entitled = GradeWiseLiveList::find($emp_name->grade);
        $takeInformation = LeaveInformation::where('employee_id',$request->emp)->whereYear('from_date', date('Y'))->get();
        // dd($takeInformation);
        // $entitled = LeaveInformation::where('employee_id',$emp_name->id)where('created_at');
        if($emp_name->count()>0)
        {
            if ($request->ajax()) {
                return Response()->json([
                    'page' => $emp_name,
                    'entitled' => view('backend.payroll.leaveManagement.leaveInfo', ['entitled' => $entitled, 'i' => 1])->render(),
                    'takeInformation' => view('backend.payroll.leaveManagement.takeInfo', ['takeInformation' => $takeInformation, 'i' => 1])->render(),
                    'newLeave' => view('backend.payroll.leaveManagement.newLeave', ['newLeave' => $entitled, 'i' => 1])->render(),

                ]);
            }
        }
    }

    public function employee_leave_edit_modal(Request $request){
        $employees = Employee::all();
        $leave = LeaveInformation::find($request->id);
        $others= LeaveDocument::where('leave_info_id', $request->id)->get();
        $emp_name = Employee::find($leave->employee_id);
        $entitled = GradeWiseLiveList::find($emp_name->grade);
        return view('backend.payroll.leaveManagement.new-edit1-form', compact('leave', 'employees','others'));
    }


    public function employeeLeaveDocumentDelete(Request $request)
    {
        $employeeDocument = LeaveDocument::find($request->id);
        $employee_id = $employeeDocument->leave_info_id;
        $employeeDocument->delete();
        $others = LeaveDocument::where('leave_info_id', $employee_id)->get();
        // $notification = array(
        //     'message'       => 'Employee Salary Deleted successfully!',
        //     'alert-type'    => 'success'
        // );
        return Response()->json([
            'page' => view('backend.payroll.leaveManagement.ajaxImage', ['others' => $others, 'i' => 1])->render(),

        ]);

    }

    public function employee_view_leave_modal(Request $request){
        $employees = Employee::all();
        $leave = LeaveInformation::find($request->id);
        $others= LeaveDocument::where('leave_info_id', $request->id)->get();
        return view('backend.payroll.leaveManagement.view', compact('leave', 'employees','others'));
    }
}

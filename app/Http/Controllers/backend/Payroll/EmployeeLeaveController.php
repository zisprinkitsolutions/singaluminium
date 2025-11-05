<?php

namespace App\Http\Controllers\backend\Payroll;


use App\Http\Controllers\Controller;
use App\Models\Payroll\Employee;
use App\Models\Payroll\EmployeeLeave;
use App\Models\Payroll\EmployeeLeaveDocument;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EmployeeLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Gate::authorize('app.attendance.index');
        $sort_search = null;
        $employee_leaves = EmployeeLeave::orderBy('id', 'asc');
        if($request->search){
            $sort_search = $request->search;
            $employee_leaves = $employee_leaves->where('employee_name', 'like', '%'.$sort_search.'%');
        }
        $employee_leaves = $employee_leaves->paginate(15);
        return view('backend.employee-leave.index', compact('employee_leaves', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Gate::authorize('app.attendance.index');
        $employees = Employee::all();
        return view('backend.employee-leave.create', compact('employees'));
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
        //Gate::authorize('app.attendance.index');
        $old_date1 = explode('/', $request->from_date);

        $new_data1 = $old_date1[0].'-'.$old_date1[1].'-'.$old_date1[2];
        $new_date1 = date('Y-m-d', strtotime($new_data1));
        $from_date = \DateTime::createFromFormat("Y-m-d", $new_date1);

        $old_date12 = explode('/', $request->to_date);
        $new_data12 = $old_date12[0].'-'.$old_date12[1].'-'.$old_date12[2];
        $new_date12 = date('Y-m-d', strtotime($new_data12));
        $to_date = \DateTime::createFromFormat("Y-m-d", $new_date12);

        $employee_leave = new EmployeeLeave;
        $employee_info = Employee::find($request->employee_name);
        $employee_leave->employee_name = $employee_info->salutation ." ". $employee_info->first_name ." ". $employee_info->middle_name ." ". $employee_info->last_name;
        $employee_leave->employee_id = $request->employee_id;
        $employee_leave->from_date = $from_date;
        $employee_leave->to_date = $to_date;
        $employee_leave->days_leave = $request->days_leave;
        $employee_leave->leave_reason = $request->leave_reason;
        $employee_leave->save();
        if($request->hasFile('files')){
            foreach($request->file('files') as $file){
                $name= $file->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext= $file->getClientOriginalExtension();
                $studentImageName= $name.time().'.'.$ext;

                $file->storeAs( 'public/upload/employee-leave', $studentImageName);

                EmployeeLeaveDocument::create([
                    'name'              => $name,
                    'filename'          => $studentImageName,
                    'employee_id'          => $employee_leave->id,
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
        // return redirect('employee-leave')->with($notification);
    }
    public function teacher_store(Request $request)
    {

        // dd($request);
        //Gate::authorize('app.attendance.index');
        $old_date1 = explode('/', $request->from_date);

        $new_data1 = $old_date1[0].'-'.$old_date1[1].'-'.$old_date1[2];
        $new_date1 = date('Y-m-d', strtotime($new_data1));
        $from_date = \DateTime::createFromFormat("Y-m-d", $new_date1);

        $old_date12 = explode('/', $request->to_date);
        $new_data12 = $old_date12[0].'-'.$old_date12[1].'-'.$old_date12[2];
        $new_date12 = date('Y-m-d', strtotime($new_data12));
        $to_date = \DateTime::createFromFormat("Y-m-d", $new_date12);

        $employee_leave = new EmployeeLeave;
        $employee_info = Employee::find($request->employee_name);
        $employee_leave->employee_name = $employee_info->salutation ." ". $employee_info->first_name ." ". $employee_info->middle_name ." ". $employee_info->last_name;
        $employee_leave->employee_id = $request->employee_id;
        $employee_leave->from_date = $from_date;
        $employee_leave->to_date = $to_date;
        $employee_leave->days_leave = $request->days_leave;
        $employee_leave->leave_reason = $request->leave_reason;
        $employee_leave->save();
        if($request->hasFile('files')){
            foreach($request->file('files') as $file){
                $name= $file->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext= $file->getClientOriginalExtension();
                $studentImageName= $name.time().'.'.$ext;

                $file->storeAs( 'public/upload/employee-leave', $studentImageName);

                EmployeeLeaveDocument::create([
                    'name'              => $name,
                    'filename'          => $studentImageName,
                    'employee_id'          => $employee_leave->id,
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
        // return redirect('employee-leave')->with($notification);
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
        //Gate::authorize('app.attendance.index');
        $employees = Employee::all();
        $leave = EmployeeLeave::find($id);
        return view('backend.employee-leave.edit', compact('leave', 'employees'));
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
        //Gate::authorize('app.attendance.index');
        $leave = EmployeeLeave::find($id);
        $ext= $request->scan_copy->getClientOriginalExtension();
        $employeeImageName= $request->file('scan_copy')->getClientOriginalName();
        $request->file('scan_copy')->storeAs('public/upload/employee-scan-copy', $employeeImageName);
        $leave->scan_copy = $employeeImageName;
        $leave->save();
        $notification= array(
            'message'       => 'Scan copy upload successfully!',
            'alert-type'    => 'success'
        );
        return back()->with($notification);
        // return redirect('employee-leave')->with($notification);
    }

    public function leaveUpdate(Request $request, $id)
    {
        //Gate::authorize('app.attendance.index');
        $old_date1 = explode('/', $request->from_date);

        $new_data1 = $old_date1[0].'-'.$old_date1[1].'-'.$old_date1[2];
        $new_date1 = date('Y-m-d', strtotime($new_data1));
        $from_date = \DateTime::createFromFormat("Y-m-d", $new_date1);

        $old_date12 = explode('/', $request->to_date);
        $new_data12 = $old_date12[0].'-'.$old_date12[1].'-'.$old_date12[2];
        $new_date12 = date('Y-m-d', strtotime($new_data12));
        $to_date = \DateTime::createFromFormat("Y-m-d", $new_date12);

        $employee_leave = EmployeeLeave::find($id);
        $employee_leave->employee_name = $request->employee_name;
        $employee_leave->employee_id = $request->employee_id;
        $employee_leave->from_date = $from_date;
        $employee_leave->to_date = $to_date;
        $employee_leave->days_leave = $request->days_leave;
        $employee_leave->leave_reason = $request->leave_reason;
        $employee_leave->save();

        if($request->file('files')){
            foreach($request->file('files') as $file){
                $name= $file->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext= $file->getClientOriginalExtension();
                $studentImageName= $name.time().'.'.$ext;

                $file->storeAs( 'public/upload/employee-leave', $studentImageName);

                EmployeeLeaveDocument::create([
                    'name'              => $name,
                    'filename'          => $studentImageName,
                    'employee_id'          => $employee_leave->id,
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
            'message'       => 'Employee leave update successfully!',
            'alert-type'    => 'success'
        );
        return back()->with($notification);
        // return redirect('employee-leave')->with($notification);
    }
    public function teacherupdate(Request $request, $id)
    {
        //Gate::authorize('app.attendance.index');
        $old_date1 = explode('/', $request->from_date);

        $new_data1 = $old_date1[0].'-'.$old_date1[1].'-'.$old_date1[2];
        $new_date1 = date('Y-m-d', strtotime($new_data1));
        $from_date = \DateTime::createFromFormat("Y-m-d", $new_date1);

        $old_date12 = explode('/', $request->to_date);
        $new_data12 = $old_date12[0].'-'.$old_date12[1].'-'.$old_date12[2];
        $new_date12 = date('Y-m-d', strtotime($new_data12));
        $to_date = \DateTime::createFromFormat("Y-m-d", $new_date12);

        $employee_leave = EmployeeLeave::find($id);
        $employee_leave->employee_name = $request->employee_name;
        $employee_leave->employee_id = $request->employee_id;
        $employee_leave->from_date = $from_date;
        $employee_leave->to_date = $to_date;
        $employee_leave->days_leave = $request->days_leave;
        $employee_leave->leave_reason = $request->leave_reason;
        $employee_leave->save();

        if($request->file('files')){
            foreach($request->file('files') as $file){
                $name= $file->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext= $file->getClientOriginalExtension();
                $studentImageName= $name.time().'.'.$ext;

                $file->storeAs( 'public/upload/employee-leave', $studentImageName);

                EmployeeLeaveDocument::create([
                    'name'              => $name,
                    'filename'          => $studentImageName,
                    'employee_id'          => $employee_leave->id,
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
            'message'       => 'Employee leave update successfully!',
            'alert-type'    => 'success'
        );
        return back()->with($notification);
        // return redirect('employee-leave')->with($notification);
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
    public function employee_leave_print($id){
        $school_name = Setting::where('config_name', 'school_name')->first();
        $school_address = Setting::where('config_name', 'school_address')->first();
        $leave = EmployeeLeave::find($id);
        return view('backend.employee-leave.leave-print', compact('leave', 'school_name', 'school_address'));
    }
    // work by mominul
    public function new_employee_leave(Request $request){
        Gate::authorize('employee_attendance');
        $sort_search = null;
        $employees = Employee::where('division','!=',6)->get();


        if($request->search){
            $sort_search = $request->search;
            $employee_leaves = EmployeeLeave::orderBy('id', 'asc')
            ->leftjoin('employees', 'employees.id','=','employee_leaves.employee_id')

            ->select('employee_leaves.*')
            ->where('employees.division','!=',6)
            ->where('employee_leaves.employee_name', 'like', '%'.$sort_search.'%');

        }
        else
        {
            $employee_leaves = EmployeeLeave::orderBy('id', 'asc')
            ->leftjoin('employees', 'employees.id','=','employee_leaves.employee_id')

            ->select('employee_leaves.*')
            ->where('employees.division','!=',6);

        }
        $employee_leaves = $employee_leaves->paginate(15);
        return view('backend.employee-leave.new-index', compact('employee_leaves', 'sort_search', 'employees'));
    }
    public function new_teacher_leave(Request $request){
        //Gate::authorize('app.attendance.index');
        $sort_search = null;
        // dd(1);
        $employees = Employee::where('division', 6)->get();

        // dd($employee_leaves);
        if($request->search){
            $sort_search = $request->search;
            $employee_leaves = EmployeeLeave::orderBy('id', 'asc')
            ->leftjoin('employees', 'employees.id','=','employee_leaves.employee_id')

            ->select('employee_leaves.*')
            ->where('employees.division',6)
            ->where('employee_leaves.employee_name', 'like', '%'.$sort_search.'%')
            ->get();
        }
        else{
            $employee_leaves = EmployeeLeave::orderBy('id', 'asc')
            ->leftjoin('employees', 'employees.id','=','employee_leaves.employee_id')

            ->select('employee_leaves.*')
            ->where('employees.division',6)
            ->get();
        }
        // $employee_leaves = $employee_leaves->get();
        return view('backend.teacher-leave.new-index', compact('employee_leaves', 'sort_search', 'employees'));
    }
    public function employee_leave_print_modal(Request $request){
        // dd(1);
        $leave = EmployeeLeave::find($request->id);
        return view('backend.employee-leave.new-leave-print', compact('leave'));
    }
    public function teacher_leave_print_modal(Request $request){
        $leave = EmployeeLeave::find( $request->id);
        //  return($leave->employee);
        return view('backend.employee-leave.teacher-new-leave-print', compact('leave'));
    }
    public function employee_leave_upload_scan_copy_modal(Request $request){
        $employees = Employee::all();
        $leave = EmployeeLeave::find($request->id);
        return view('backend.employee-leave.new-edit-form', compact('leave', 'employees'));
    }

    function downloadFile($file_name){
        $path = "storage/upload/student-parent/".$file_name;
        return response()->download($path);

    }

    public function employee_view_leave_modal(Request $request){
        $employees = Employee::all();
        $leave = EmployeeLeave::find($request->id);
        $others= EmployeeLeaveDocument::where('employee_id', $request->id)->get();
        return view('backend.employee-leave.view', compact('leave', 'employees','others'));
    }
    public function teacher_view_leave_modal(Request $request){
        $employees = Employee::all();
        $leave = EmployeeLeave::find($request->id);
        $others= EmployeeLeaveDocument::where('employee_id', $request->id)->get();
        return view('backend.employee-leave.view-teacher', compact('leave', 'employees','others'));
    }

    public function employee_leave_edit_modal(Request $request){
        $employees = Employee::all();
        $leave = EmployeeLeave::find($request->id);
        $others= EmployeeLeaveDocument::where('employee_id', $request->id)->get();
        return view('backend.employee-leave.new-edit1-form', compact('leave', 'employees','others'));
    }
    public function teacher_leave_edit_modal(Request $request){
        $employees = Employee::where('role', 5)->get( );
        $leave = EmployeeLeave::find($request->id);
        $others= EmployeeLeaveDocument::where('employee_id', $request->id)->get();
        return view('backend.teacher-leave.new-edit1-form', compact('leave', 'employees','others'));
    }

    public function employeeLeaveDocumentDelete(Request $request)
    {
        $employeeDocument = EmployeeLeaveDocument::find($request->id);
        $employee_id = $employeeDocument->employee_id;
        $employeeDocument->delete();
        $others = EmployeeLeaveDocument::where('employee_id', $employee_id)->get();
        // $notification = array(
        //     'message'       => 'Employee Salary Deleted successfully!',
        //     'alert-type'    => 'success'
        // );
        return Response()->json([
            'page' => view('backend.employee-leave.ajaxImage', ['others' => $others, 'i' => 1])->render(),

        ]);

    }
}

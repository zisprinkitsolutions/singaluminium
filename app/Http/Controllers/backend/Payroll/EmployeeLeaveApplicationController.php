<?php

namespace App\Http\Controllers\backend\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll\Employee;
use App\Models\Payroll\EmployeeLeaveApplication;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeeLeaveApplicationController extends Controller
{


    private function change_date_format($date)
    {
        $date_array = explode('/', $date);
        $date_string = implode('-', $date_array);
        $date = date('Y-m-d', strtotime($date_string));
        return $date;
    }

    private function generateUniqueLeaveNo()
    {
        $latestLeave = EmployeeLeaveApplication::orderBy('id', 'desc')->first();

        if ($latestLeave && Str::startsWith($latestLeave->leave_no, 'LEAVE')) {
            $number = intval(substr($latestLeave->leave_no, 5)) + 1;
        } else {
            $number = 1000;
        }

        return 'LEAVE' . $number;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        $currentDate = new DateTime();
        $firstDayOfYear = $currentDate->modify('first day of January')->format('Y-m-d');
        $lastDayOfYear = $currentDate->modify('last day of December')->format('Y-m-d');
        $form =  $request->form ? $this->change_date_format($request->form) :$firstDayOfYear;
        $to = $request->to ? $this->change_date_format($request->to) : $lastDayOfYear;

        $search = $request->search;
        $type = $request->type ? $request->type : 'Annual';

        // Fetch leave policies with filtering
        $employee_aplication = EmployeeLeaveApplication::query();

        if($form) {
            $employee_aplication->where('created_at', '>=',$form);
        }
        if($to) {
            $employee_aplication->where('created_at', '<=',$to);
        }

        if($search) {
            $employee_aplication->where('leave_no', $search);
        }

        $employee_aplication_approve = EmployeeLeaveApplication::query();

        if ($form) {
            $employee_aplication_approve->where('created_at', '>=',$form);
        }

        if ($to) {
            $employee_aplication_approve->where('created_at', '<=',$to);
        }

        if ($search) {
            $employee_aplication_approve->where('leave_no', $search);
        }

        if (Auth::user()->hasPermission('Employee_Leave')){
            $employee_aplication_approve = $employee_aplication_approve->where('status', 1)->orderBy('id', 'desc')->get();
            $employee_aplication = $employee_aplication->whereIn('status', [0,2])->orderBy('id', 'desc')->get();
        }else{
            $employee_aplication_approve = $employee_aplication_approve->where('status', 1)->where('employee_id',  Auth::user()->employee_id)->orderBy('id', 'desc')->get();
            $employee_aplication = $employee_aplication->whereIn('status', [0,2])->where('employee_id',  Auth::user()->employee_id)->orderBy('id', 'desc')->get();
        }

        $employee_eligabel_leave = Auth::user();
        $max_date = null;
        $minimum_date = null;

        if ($employee_eligabel_leave->emp) {
            $currentDate = Carbon::now();
            $emp_policy = policy_helper($employee_eligabel_leave->emp->emp_id, date('Y-m-d'));
            $anual_leave_no = $emp_policy->number_of_yearly_vacation ?? 0;
            $employee_last_visit = $employee_eligabel_leave->emp->last_visite ? $employee_eligabel_leave->emp->last_visite : $employee_eligabel_leave->emp->joining_date;
            $employee_last_visit = Carbon::parse($employee_last_visit);
            $monthly_leave = $anual_leave_no / 12;
            $month_after_visit = $employee_last_visit->diffInMonths($currentDate);
            $eligable_leave = floor($month_after_visit * $monthly_leave);
            if($eligable_leave > 0){
                $minimum_date = $currentDate->toDateString(); // Today
                $max_date = $currentDate->addDays($eligable_leave-1)->toDateString();
            }

        }

        // return ([ $month_after_visit,$eligable_leave]);

        return view('backend.payroll.employee-leave-application.index', compact('employee_aplication_approve','max_date','minimum_date','employee_aplication','search','form','to','type'));
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
        $request->validate([
            'emp_id' => 'required|integer',

        ]);

        // Convert dates to the correct format
        $start_date = $request->start_date ? $this->change_date_format($request->start_date) :'';
        $end_date = $request->end_date ? $this->change_date_format($request->end_date) :'';

       // return $request;

        // Generate unique leave number
        $leaveNo = $this->generateUniqueLeaveNo();

        if ($request->file('file')) {
            $name = $request->file('file')->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $request->file('file')->getClientOriginalExtension();
            $file = 'leave-document' . time() . '.' . $ext;

            $request->file('file')->storeAs('public/upload/leave-documents', $file);
        }
        // Create new leave policy
        $leave = new EmployeeLeaveApplication([
            'employee_id' => $request->input('emp_id'),
            'leave_no' => $leaveNo,

            'start_date' => $start_date,
            'end_date' => $end_date,

            'leave_day' => $request->input('leave_day'),
            'description' => $request->input('description'),
            'status' => 0,
            'file' => $file ?? null,
        ]);

        $leave->save();
        $notification= array(
            'message'       => 'Added successfully!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payroll\EmployeeLeaveApplication  $employeeLeaveApplication
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeLeaveApplication $employeeLeaveApplication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payroll\EmployeeLeaveApplication  $employeeLeaveApplication
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $leave = EmployeeLeaveApplication::find($id);

        return view('backend.payroll.employee-leave-application.edit-modal', compact('leave'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payroll\EmployeeLeaveApplication  $employeeLeaveApplication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        $request->validate([
            'emp_id' => 'required|integer',

        ]);

        // Convert dates to the correct format
        $start_date = $request->input('start_date') ? $this->change_date_format($request->input('start_date')) :'';
        $end_date = $request->input('end_date') ? $this->change_date_format($request->input('end_date')) :'';

        $leave = EmployeeLeaveApplication::findOrFail($id);

        // Handle file upload if a new file is provided
        if ($request->file('file')) {
            $name = $request->file('file')->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $request->file('file')->getClientOriginalExtension();
            $file = 'leave-document' . time() . '.' . $ext;
            $request->file('file')->storeAs('public/upload/leave-documents', $file);

            if ($leave->file) {
                Storage::delete('public/upload/leave-documents/' . $leave->file);
            }
            $leave->file = $file;
        }

        $leave->employee_id = $request->input('emp_id');
        $leave->leave_no = $leave->leave_no;

        // $leave->start_date = $start_date;
        // $leave->end_date = $end_date;

        $leave->leave_day = $request->input('leave_day');
        $leave->description = $request->input('description');
        $leave->status = 0;

        $leave->save();

        $notification = array(
            'message' => 'Updated successfully!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function approve($id)
    {
        // Gate::authorize('approver');
        $leave = EmployeeLeaveApplication::findOrFail($id);

        $employee_eligabel_leave = Employee::where('id', $leave->employee_id)->first();

        if ($employee_eligabel_leave) {
            $currentDate = Carbon::now();
            $emp_policy = policy_helper($employee_eligabel_leave->emp_id, date('Y-m-d'));
            $anual_leave_no = $emp_policy->number_of_yearly_vacation ?? 0;
            $employee_last_visit = $employee_eligabel_leave->last_visite ? $employee_eligabel_leave->last_visite : $employee_eligabel_leave->joining_date;
            $employee_last_visit = Carbon::parse($employee_last_visit);
            $monthly_leave = $anual_leave_no / 12;
            $month_after_visit = $employee_last_visit->diffInMonths($currentDate);
            $eligable_leave = floor($month_after_visit * $monthly_leave);

            if($eligable_leave >= $leave->leave_day){

                if($leave && $leave->status == 0){

                   $leave->status = 1;
                   $leave->save();
                   $employee = Employee::where('id', $leave->employee_id)->first();
                   $employee->last_visite = $leave->end_date;
                   $employee->save();
                     $notification = array(
                         'message' => 'Approve successfully!',
                         'alert-type' => 'success'
                     );
                }else{

                 $notification = array(
                     'message' => 'Already approved',
                     'alert-type' => 'warning'
                 );
                }
            }else{
                $notification = array(
                    'message' => 'No Available Leave.',
                    'alert-type' => 'error'
                );
            }

        }else
        {
            $notification = array(
                'message' => 'No Employee Found.',
                'alert-type' => 'error'
            );
        }


       return redirect()->back()->with($notification);
    }

    public function reject($id)

    {
        Gate::authorize('employee_leave_approval');

        $leave = EmployeeLeaveApplication::findOrFail($id);

       if($leave && $leave->status == 0){

          $leave->status = 2;
          $leave->save();

            $notification = array(
                'message' => 'Reject successfully!',
                'alert-type' => 'success'
            );
       }else{

        $notification = array(
            'message' => 'Already Reject',
            'alert-type' => 'warning'
        );
       }
       return redirect()->back()->with($notification);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payroll\EmployeeLeaveApplication  $employeeLeaveApplication
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $leave = EmployeeLeaveApplication::findOrFail($id);

       if($leave && $leave->status == 0){
          $leave->delete();
            $notification = array(
                'message' => 'Delete successfully!',
                'alert-type' => 'success'
            );
       }else{
        $notification = array(
            'message' => 'This can not be delete!',
            'alert-type' => 'warning'
        );
       }


        return redirect()->back()->with($notification);
    }
}

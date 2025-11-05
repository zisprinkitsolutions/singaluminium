<?php

namespace App\Http\Controllers\backend\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll\Employee;
use App\Models\Payroll\EmployeeAttendance ;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployeeAttendenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $request;
        $from = $request->input('from');
        $to = $request->input('to');

        $search = $request->search ? $request->search : null;
        $fromDate = $from ? Carbon::createFromFormat('d/m/Y', $from)->format('Y-m-d') : null;
        $toDate = $to ? Carbon::createFromFormat('d/m/Y', $to)->format('Y-m-d') : null;
        $employees= Employee::get();
        $date = $request->date;
        if($request->has('date')){
            $attendances = EmployeeAttendance::where('date', $request->date)->get();
            // dd($attendances);
            return view('backend.attendance.employee.new-index', compact('attendances', 'employees', 'date','fromDate','toDate', 'search'));
        }
        return view('backend.attendance.employee.new-index', compact('employees', 'date','date','fromDate','toDate', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('app.attendance.index');
        $employees= Employee::all();
        return view('backend.attendance.employee.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // //Gate::authorize('app.mapping.index');
        $request->validate([
            'date'          => 'required|date',
            'status'        => 'required',
        ]);

        $isExist= EmployeeAttendance::where('date', $request->date)->get();
        if($isExist->count() > 0){
            $notification= array(
                'message'       => 'Already have these attandance!',
                'alert-type'    => 'warning'
            );
            return back()->with($notification);
        }

        foreach ($request->status as $key => $attendance) {
            EmployeeAttendance::create([
                'employee_id'        => $key,
                'status'            => $attendance,
                'date'              => $request->date
            ]);
        }
        $notification= array(
            'message'       => 'Attandance Create Successfull!',
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
        //
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
        //
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
    public function new_employee_attendance(Request $request){

        $job_type = $request->job_type ? $request->job_type : null;
        $search = $request->search ? $request->search : null;

        Gate::authorize('app.attendance.index');
        $employees= Employee::where('job_status',1)->when($search , function ($query) use($search) {
           $query->where('full_name',$search);
        })->when($job_type , function ($query) use($job_type) {
            $query->where('job_type',$job_type);
         })->orderBy('id', 'asc')->get();

        return view('backend.attendance.employee.new-index', compact('employees', 'search', ' job_type'));
    }
    public function employee_attendance_print(Request $request){
        $employees= Employee::all();
        $date = $request->date;
        if($request->has('date')){
            $attendances = EmployeeAttendance::where('date', $request->date)->get();
            return view('backend.attendance.employee.attendance-print', compact('attendances', 'employees', 'date'));
        }
        return view('backend.attendance.employee.attendance-print', compact('employees', 'date'));
    }

    public function find_employee(Request $request){
        $employees= Employee::where('');

    }
}

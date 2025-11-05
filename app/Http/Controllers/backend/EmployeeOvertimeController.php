<?php

namespace App\Http\Controllers\backend;

use App\EmployeeOvertime;
use App\Http\Controllers\Controller;
use App\JobProject;
use App\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployeeOvertimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function dateFormat($date){
        $old_date = explode('/', $date);

        $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }
    public function index(Request $request)
    {
        // dd($request);
        $new_date = null;
        $date = null;
        $month = null;
        $attendances = null;
        if ($request->date != '') {
            $date = $this->dateFormat($request->date);
        }
        if ($request->new_date != '') {
            $new_date = $this->dateFormat($request->new_date);
            $attendances = EmployeeOvertime::whereDate('date', '=', Carbon::parse($new_date)->format('Y-m-d'))->whereIn('employee_id', function ($query) {
                $query->select('id')
                    ->from('employees')
                    ->where('division', 6);})->get();
        }
        if($request->month){
            $month = $request->month;
        }
        $employees= Employee::where('division',6)->get();
        $projects = JobProject::where('is_invoice', '>', 0)->get();
        return view('backend.attendance.overtime.index', compact('employees', 'new_date', 'date', 'attendances', 'month', 'projects'));
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
            'date'        => 'required',
        ]);
        $date = $this->dateFormat($request->date);
        $attendances= EmployeeOvertime::whereDate('date', $date)->get();
        if(count($attendances)>0){
            $notification= array(
                'message'       => 'Already take overtime this date!',
                'alert-type'    => 'warning'
            );
            return back()->with($notification);
        }
        $hours = $request->hours;
        $project_id = $request->project_id;
        $project_task_id = $request->project_task_id;
        foreach ($request->employee_id as $key => $employee_id) {
            if($hours[$key]){
                $attendances = new EmployeeOvertime;
                $attendances->employee_id  = $employee_id;
                $attendances->date         = $date;
                $attendances->hours        = $hours[$key];
                $attendances->project_id   = $project_id[$key];
                $attendances->project_task_id = $project_task_id[$key];
                $attendances->save();
            }
        }
        $notification= array(
            'message'       => 'Oveertime Created Successfully!',
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
    public function employee_overtime_print(Request $request){
        // dd(1);
        $employees= Employee::where('division', 6)->get();
        $date = $request->date;
        if($request->has('date')){
            $attendances = EmployeeOvertime::where('date', $request->date)->get();
            return view('backend.attendance.overtime.attendance-print', compact('attendances', 'employees', 'date'));
        }
        return view('backend.attendance.overtime.attendance-print', compact('employees', 'date'));
    }
    public function employee_overtime_edit(Request $request){
        // dd($request->date);
        $new_date = null;
        $attendances = null;
        $date = $request->date;
        $attendances = EmployeeOvertime::whereDate('date', '=', Carbon::parse($date)->format('Y-m-d'))->whereIn('employee_id', function ($query) {
            $query->select('id')
                ->from('employees')
                ->where('division', 6);
            })->get();
        $employees= Employee::where('division',6)->get();
        $projects = JobProject::where('is_invoice', '>', 0)->get();
        return view('backend.attendance.overtime.edit', compact('employees',  'date', 'attendances', 'projects'));
    }
    public function employee_overtime_update(Request $request){
        $employee_ids = $request->employee_id;
        $hours = $request->hours;
        $project_id = $request->project_id;
        $employee_overtime_id = $request->employee_overtime_id;
        foreach($employee_ids as $key => $employee){
            $exit_hours = EmployeeOvertime::find($employee_overtime_id[$key]);
            if($exit_hours){
                if($hours[$key]){
                    $exit_hours->hours = $hours[$key];
                    $exit_hours->project_id = $project_id[$key];
                    $exit_hours->save();
                }else{
                    $exit_hours->delete();
                }
            }else{
                if($hours[$key]){
                    $attendances = new EmployeeOvertime;
                    $attendances->employee_id  = $employee;
                    $attendances->date         = $request->date;
                    $attendances->hours        = $hours[$key];
                    $attendances->project_id   = $project_id[$key];
                    $attendances->save();
                }
            }
        }
        $notification= array(
            'message'       => 'Oveertime Update Successfully!',
            'alert-type'    => 'success'
        );
        return back()->with($notification);
    }
}

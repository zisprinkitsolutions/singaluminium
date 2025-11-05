<?php

namespace App\Http\Controllers\backend\Payroll;

use App\Http\Controllers\Controller;
use App\JobProject;
use App\Models\Payroll\Employee;
use App\Models\Payroll\EmployeeAttendance;
use App\Models\Payroll\EmployeePolicy;
use App\Models\Payroll\JobType;
use App\Models\Payroll\JobTypeInfo;
use App\NewProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;

class EmployeeAttendence extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

       Gate::authorize('employee_attendance');
        $date=[];
        $job_types = JobType::get();

        if($request->has('date')){
            $attendances = EmployeeAttendance::where('date', $request->date)->get();
            return view('backend.payroll.attendance.employee.new-index', compact('attendances','job_types'));
        }

        return view('backend.payroll.attendance.employee.new-index',compact(('job_types')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       Gate::authorize('employee_attendance');
        $employees= Employee::all();
        return view('backend.payroll.attendance.employee.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

        // Gate::authorize('employee_attendance');
        // Helper function to add two time strings in H:i:s format
        function addWorkingHours($time1, $time2)
        {
            $time1 = $time1 ?: '00:00:00';
            $time2 = $time2 ?: '00:00:00';

            list($hours1, $minutes1, $seconds1) = explode(':', $time1);
            list($hours2, $minutes2, $seconds2) = explode(':', $time2);

            $totalSeconds = ($hours1 * 3600 + $minutes1 * 60 + $seconds1) + ($hours2 * 3600 + $minutes2 * 60 + $seconds2);

            $hours = floor($totalSeconds / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);
            $seconds = $totalSeconds % 60;

            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        try {
            // Validate and format the date
            $dateInput = $request->date;
            $dateArray = explode('/', $dateInput);
            if (count($dateArray) !== 3 || !checkdate($dateArray[1], $dateArray[0], $dateArray[2])) {
                return response()->json(['message' => 'Invalid date format. Please use DD/MM/YYYY.', 'type' => 'error'], 400);
            }
            $formattedDate = "{$dateArray[2]}-{$dateArray[1]}-{$dateArray[0]}";
            $date = DateTime::createFromFormat("Y-m-d", $formattedDate);
            if (!$date) {
                return response()->json(['message' => 'Date conversion error.', 'type' => 'error'], 500);
            }

            // Fetch employee and validate existence
            $employee = Employee::find($request->emp_id);
            if (!$employee) {
                return response()->json(['message' => 'Employee not found.', 'type' => 'error'], 404);
            }

            // Fetch employee policy`
            $date = date('Y-m-d');
            $emp_policy = policy_helper($employee->emp_id,$date);

            if (!$emp_policy) {
                return response()->json(['message' => 'Employee policy not found.', 'type' => 'error'], 404);
            }

            // Set timezone
            $timeZone =  date_default_timezone_get(); //$emp_policy->time_zone ?? 'Asia/Dubai';
            $timezone = new DateTimeZone($timeZone);

            // Initialize attendance record or check if exists
            $attendance = EmployeeAttendance::firstOrNew(
                ['date' => $date, 'employee_id' => $request->emp_id]
            );

            // Process attendance based on type
            switch ($request->type) {
                case 'morning_in':
                    if ($attendance->exists) {
                        return response()->json(['message' => 'Attendance already added!', 'type' => 'warning']);
                    }

                    $officeInDateTime = new DateTime('now', $timezone);
                    $formattedOfficeIn = $officeInDateTime->format('h:i:s A');
                    $referenceInDateTime = DateTime::createFromFormat('H:i', $emp_policy->m_ref_in_time, $timezone);
                    $lateTime = ($referenceInDateTime && $officeInDateTime > $referenceInDateTime)
                        ? $referenceInDateTime->diff($officeInDateTime)->format('%H:%I:%S')
                        : '';

                    $attendance->fill([
                        'status' => 1,
                        'in_time' => $formattedOfficeIn,
                        'total_late_time' => $lateTime,
                        'reference_in_time' => $referenceInDateTime->format('h:i:s A'),
                        'reference_out_time' => DateTime::createFromFormat('H:i', $emp_policy->m_ref_out_time, $timezone)->format('h:i:s A'),
                        'e_reference_in_time' => DateTime::createFromFormat('H:i', $emp_policy->e_ref_out_time, $timezone)->format('h:i:s A'),
                        'e_reference_out_time' => DateTime::createFromFormat('H:i', $emp_policy->e_ref_out_time, $timezone)->format('h:i:s A'),
                        'project_id' => $request->project_id,
                        ])->save();

                    return response()->json(['message' => 'Attendance successfully added!', 'type' => 'success']);

                case 'morning_out':
                    if (!$attendance->exists || $attendance->out_time) {
                        return response()->json(['message' => 'Attendance already updated or not found!', 'type' => 'warning']);
                    }

                    $officeOutDateTime = new DateTime('now', $timezone);
                    $inDateTime = DateTime::createFromFormat('H:i:s A', $attendance->in_time, $timezone);
                    $referenceOutDateTime = DateTime::createFromFormat('H:i:s A', $attendance->reference_out_time, $timezone);

                    $formattedOfficeOut = $officeOutDateTime->format('h:i:s A');
                    $overtime = ($referenceOutDateTime && $officeOutDateTime > $referenceOutDateTime)
                        ? $referenceOutDateTime->diff($officeOutDateTime)->format('%H:%I:%S')
                        : '';
                    $totalHours = ($inDateTime && $officeOutDateTime > $inDateTime)
                        ? $inDateTime->diff($officeOutDateTime)->format('%H:%I:%S')
                        : '';

                    $attendance->fill([
                        'out_time' => $formattedOfficeOut,
                        'total_overtime' => addWorkingHours($attendance->total_overtime, $overtime),
                        'total_working_hours' => addWorkingHours($attendance->total_working_hours, $totalHours)
                    ])->save();

                    return response()->json(['message' => 'Attendance updated successfully!', 'type' => 'success']);

                case 'evening_in':
                    if ($attendance->evening_in) {
                        return response()->json(['message' => 'Attendance already updated or not found!', 'type' => 'warning']);
                    }

                    $officeInDateTime = new DateTime('now', $timezone);
                    $e_referenceInTime = DateTime::createFromFormat('H:i:s A', $attendance->e_reference_in_time, $timezone);
                    $lateTime = ($e_referenceInTime && $officeInDateTime > $e_referenceInTime)
                        ? $e_referenceInTime->diff($officeInDateTime)->format('%H:%I:%S')
                        : '';

                    $attendance->fill([
                        'status' => 1,
                        'evening_in' => $officeInDateTime->format('h:i:s A'),
                        'total_late_time' => addWorkingHours($attendance->total_late_time, $lateTime)
                    ])->save();

                    return response()->json(['message' => 'Attendance updated successfully!', 'type' => 'success']);

                case 'evening_out':
                    if (!$attendance->exists || $attendance->evening_out) {
                        return response()->json(['message' => 'Attendance already updated or not found!', 'type' => 'warning']);
                    }

                    $officeOutDateTime = new DateTime('now', $timezone);
                    $inDateTime = DateTime::createFromFormat('H:i:s A', $attendance->evening_in, $timezone);
                    $referenceOutDateTime = DateTime::createFromFormat('H:i:s A', $attendance->e_reference_out_time, $timezone);

                    $formattedOfficeOut = $officeOutDateTime->format('h:i:s A');
                    $overtime = ($referenceOutDateTime && $officeOutDateTime > $referenceOutDateTime)
                        ? $referenceOutDateTime->diff($officeOutDateTime)->format('%H:%I:%S')
                        : '';
                    $totalHours = ($inDateTime && $officeOutDateTime > $inDateTime)
                        ? $inDateTime->diff($officeOutDateTime)->format('%H:%I:%S')
                        : '';

                    $attendance->fill([
                        'evening_out' => $formattedOfficeOut,
                        'total_overtime' => addWorkingHours($attendance->total_overtime, $overtime),
                        'total_working_hours' => addWorkingHours($attendance->total_working_hours, $totalHours)
                    ])->save();

                    return response()->json(['message' => 'Attendance updated successfully!', 'type' => 'success']);

                default:
                    return response()->json(['message' => 'Invalid attendance type.', 'type' => 'error'], 400);
            }

        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error occurred: ' . $e->getMessage(),], 500);
        }
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

    public function new_employee_attendance(Request $request)
    {
        $projects = JobProject::orderBy('project_name')->get();

        if (Auth::user()->hasPermission('Attendance')){

            $job_type = $request->job_type ?? null;

            $search = $request->filled('search') ? trim($request->search) : null;
            $search_array = explode(' ', $search);
            $search1 = 'not_exisist_1234';
            $search2 = 'not_exisist_1234';

            if(count($search_array) == 2){
                $search2 = $search_array[0];
                $search1 = $search_array[0] . '  ' . $search_array[1];
            }

            if(count($search_array) > 2){
                $search2 = $search_array[0] . ' ' . $search_array[1];
                $search1 = $search_array[0] . ' ' . $search_array[1] . '  ' . $search_array[2];
            }

            if(count($search_array) > 3){
                $search2 = $search1 = $search_array[0] . ' ' . $search_array[1] . ' ' . $search_array[2];
                $search1 = $search_array[0] . ' ' . $search_array[1] . '  ' . $search_array[2];
            }

            $job_types = JobTypeInfo::get();

            $date_input = $request->date ?? date('d/m/Y');

            $new_date = \DateTime::createFromFormat('d/m/Y', $date_input);
            $date = $new_date ? $new_date->format('Y-m-d') : now()->format('Y-m-d');

            $employees = Employee::where('status', 1)->orderBy('code')
                ->when($search, function ($query) use ($search,$search1,$search2) {
                    $query->where(function ($q) use ($search,$search1,$search2) {
                        $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search1}%")
                        ->orWhere('full_name', 'like', "%{$search2}%")
                        ->orWhere('contact_number', 'like', "%{$search}%")
                        ->orWhere('parmanent_address', 'like', "%{$search}%"); // Fixed typo
                    });
                })
                ->when($job_type, function ($query) use ($job_type) {
                    $query->where('job_type', $job_type);
                })
                ->orderBy('id', 'asc')
                ->get();


            return view('backend.payroll.attendance.employee.new-index', compact('employees', 'search', 'job_type', 'job_types', 'date','projects'));

        }else{
            $job_type = $request->job_type ?? null;
            $search = $request->search ?? null;

            $job_types = JobTypeInfo::get();

            $date_input = $request->date ?? date('d/m/Y');

            $new_date = \DateTime::createFromFormat('d/m/Y', $date_input);
            $date = $new_date ? $new_date->format('Y-m-d') : now()->format('Y-m-d');

            $employees = Employee::where('job_status', 1)
                ->where('id', Auth::user()->employee_id)
                ->orderBy('id', 'asc')
                ->get();

            return view('backend.payroll.attendance.employee.new-index', compact('employees', 'search', 'job_type', 'job_types', 'date' , 'projects'));
        }
    }

    public function employee_monthly_attendance(Request $request)
    {
        if ( Auth::user()->hasPermission('employee_attendance')){

            $job_type = $request->job_type ?? null;
            $search = $request->filled('search') ? trim($request->search) : null;
            $search_array = explode(' ', $search);
            $search1 = null;
            $search2 = null;

            if(count($search_array) == 2){
                $search2 = $search_array[0];
                $search1 = $search_array[0] . '  ' . $search_array[1];
            }

            if(count($search_array) > 2){
                $search2 = $search_array[0] . ' ' . $search_array[1];
                $search1 = $search_array[0] . ' ' . $search_array[1] . '  ' . $search_array[2];
            }

            if(count($search_array) > 3){
                $search2 = $search1 = $search_array[0] . ' ' . $search_array[1] . ' ' . $search_array[2];
                $search1 = $search_array[0] . ' ' . $search_array[1] . '  ' . $search_array[2];
            }

            $job_types = JobTypeInfo::get();

            $date_input = $request->date ?? date('d/m/Y');

            $new_date = \DateTime::createFromFormat('d/m/Y', $date_input);
            $date_d = $new_date ? $new_date->format('Y-m-d') : now()->format('Y-m-d');
            $date = $request->date ? Carbon::parse($date_d) : Carbon::now();
            $request_date = date('Y-m' , strtotime( $date));

            $employee = Employee::where('status', 1)->orderBy('code')
                ->when($search, function ($query) use ($search,$search1,$search2) {
                    $query->where(function ($q) use ($search,$search1,$search2) {
                        $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search1}%")
                        ->orWhere('full_name', 'like', "%{$search2}%")
                        ->orWhere('contact_number', 'like', "%{$search}%")
                        ->orWhere('parmanent_address', 'like', "%{$search}%"); // Fixed typo
                    });
                })
                ->when($job_type, function ($query) use ($job_type) {
                    $query->where('job_type', $job_type);
                })
                ->orderBy('id', 'asc')
                ->paginate(10);

                $numDays = $date->daysInMonth;

                $daysArray = [];

                for ($day = 1; $day <= $numDays; $day++) {
                    $currentDay = Carbon::createFromDate($date->year, $date->month, $day);

                    $dayData = [
                        'day_number' => $currentDay->day,
                        'date' => $currentDay->format('Y-m-d')

                    ];

                    $daysArray[] = $dayData;
                }

            return view('backend.payroll.attendance.employee.monthly-attendance', compact('employee', 'search', 'job_type', 'job_types', 'date','daysArray','request_date'));

        }
        return redirect()->back();
    }


    public function employee_attendance_show(Request $request){

        $employee_info = $request->emp_id;
        $date = $request->date;
        $employee = Employee::find($employee_info);
        $attendanceData = null;
        $name = $employee->full_name . ' ' . '(' . $employee->emp_id . ')';
        if ($employee) {
            $attendanceData = EmployeeAttendance::where('employee_id', $employee->id)
                ->whereYear('date', date('Y', strtotime($date)))
                ->whereMonth('date', date('m', strtotime($date)))
                ->orderBy('date', 'desc')
                ->get();
        }
        return view('backend.payroll.attendance.employee.attendance-show', compact('attendanceData', 'date','name'));
    }

    public function employee_attendance_print(Request $request){
        // dd(1);
        $employees= Employee::where('division','!=', 6)->get();
        $date = $request->date;
        if($request->has('date')){
            $attendances = EmployeeAttendance::where('date', $request->date)->get();
            return view('backend.payroll.attendance.employee.attendance-print', compact('attendances', 'employees', 'date'));
        }
        return view('backend.payroll.attendance.employee.attendance-print', compact('employees', 'date'));
    }

    public function new_employee_attendance_edit(Request $request){
       Gate::authorize('employee_attendance');
        $employees= Employee::where('role', '!=', 5)->get();
        $date = $request->date;
        if($request->new_date){
            $old_date1 = explode('/', $request->new_date);

            $new_data1 = $old_date1[0].'-'.$old_date1[1].'-'.$old_date1[2];
            $new_date1 = date('Y-m-d', strtotime($new_data1));
            $new_date = \DateTime::createFromFormat("Y-m-d", $new_date1);

           // Convert the provided date string to a Carbon instance
            $date1 = Carbon::parse($new_date)->format('Y-m-d');
             if($new_date){
            // $new_date = $request->new_date;
            $attendances = EmployeeAttendance::whereDate('date', '=', $date1)->whereIn('employee_id', function ($query) {
                $query->select('id')
                    ->from('employees')
                    ->where('division','!=', 6);})->get();
            // return($attendances);
            return view('backend.payroll.attendance.employee.new-edit', compact('attendances', 'employees', 'date','date1', 'new_date'));
           }

        }
        return view('backend.payroll.attendance.employee.new-index', compact('employees', 'date'));
    }



    public function new_employee_attendance_update(Request $request){
       Gate::authorize('employee_attendance');
        //   dd( $request->all());
        foreach ($request->status as $key => $attendance) {
            // dd($attendance);
           $update_attendence = EmployeeAttendance::where('date', $request->new_date)->where('employee_id', $key)->first();
        //    dd($update_attendence);
           $update_attendence->status =  $attendance;
           $update_attendence->save();
        }
        $notification= array(
            'message'       => 'Update Successfull!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

}

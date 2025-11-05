<?php

namespace App\Http\Controllers\backend\Payroll;

use App\Models\Payroll\EmployeeAttendance;
use App\Http\Controllers\Controller;
use App\JobProject;
use App\Models\Payroll\Employee;
use App\Models\Payroll\SalaryProcess;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class ProjectReportController extends Controller
{
    private function timeToSeconds($time) {
        [$h, $m, $s] = explode(':', $time);
        return ($h * 3600) + ($m * 60) + $s;
    }

    public function report(Request $request){

        $employees = Employee::orderBy('full_name')->get();
        $selected_project = $request->project_name;
        $selected_employee = $request->employee_id;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $project_ids = JobProject::where('project_name', 'like', '%' . $selected_project . '%')->pluck('id')->toArray();

        $date = Carbon::now();
        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');

        $monthYear = $year .'-'.$month;

        $attendances = EmployeeAttendance::where('status',1)->where('project_id','>',0)->whereMonth('date', $month)->whereYear('date', $year)
                ->when($project_ids, function($q) use($project_ids){
                    $q->whereIn('project_id', $project_ids);
                })->when($selected_employee, function($q) use($selected_employee){
                    $q->where('employee_id', $selected_employee);
                })->get();

        $data = [];

        $totalDaysInMonth = Carbon::parse($monthYear)->daysInMonth;
        $currentDayOfMonth = $date->format('Y-m') == $monthYear ? $date->day : $totalDaysInMonth;

        foreach ($attendances as $attendance) {

            $employee = Employee::find($attendance->employee_id);
            if (!$employee) continue;

            $project_id = $attendance->project_id;
            $project_name = optional($attendance->project)->project_name ?? 'Unknown';

            $salary_process = SalaryProcess::where('employee_id', $employee->id)
                ->where('advance_amount', '>', 0)
                ->get();

            $already_payment = SalaryProcess::where('employee_id', $employee->id)
                ->where('status', 1)
                ->where('month', $month)
                ->where('year', $year)
                ->get();

            $salary_date = new DateTime("$year-$month-01");
            $last_visite = $employee->last_visite ?? $employee->joining_date ?? null;

            $text = 0;

            if ($last_visite) {
                $last = new DateTime($last_visite);
                $interval = $salary_date->diff($last);
                $text = ($interval->y * 12) + $interval->m;
            }

            $basic_salary = 0;
            $check_attendance = EmployeeAttendance::check_attendance($employee->id, $month, $year, $basic_salary,null,null, $project_id);

            $overtime_amount = isset($check_attendance['overtime_amount']) ? str_replace(',', '', $check_attendance['overtime_amount']) : 0;
            $late_amount = isset($check_attendance['late_amount']) ? str_replace(',', '', $check_attendance['late_amount']) : 0;
            $total_absen_penalty = $check_attendance['total_absen_penalty'] ?? 0;
            $basic_salary = $check_attendance['basic_salary'] ?? 0;
            $total_absen_penalty = isset($check_attendance['total_absen_penalty']) ? $check_attendance['total_absen_penalty'] : 0;

            $basic_salary_current_day = $basic_salary;
            if ($currentDayOfMonth < $totalDaysInMonth) {
                $basic_salary_current_day = ($basic_salary / $totalDaysInMonth) * $currentDayOfMonth;
            }

            $total_amount = ($overtime_amount - $late_amount - $total_absen_penalty) + $basic_salary_current_day;
            $time = $check_attendance['total_working_hours'] ?? '00:00:00';
            $total_working_seconds = $this->timeToSeconds($time);

            // Initialize if not exists
            if (!isset($data[$project_id])) {
                $data[$project_id] = [
                    'project_name' => $project_name,
                    'total_employees' => 0,
                    'total_working_hours' => $total_working_seconds,
                    'total_overtime' => 0,
                    'total_late_time' => 0,
                    'total_absen' => 0,
                    'overtime_amount' => 0,
                    'late_amount' => 0,
                    'basic_salary_current_day' => 0,
                    'total_cost' => 0,
                    'total_absen_penalty' => 0,
                ];
            }
            // Increment totals
            $data[$project_id]['total_employees'] += 1;
            $data[$project_id]['total_working_hours'] += $total_working_seconds;
            $data[$project_id]['total_overtime'] += $this->timeToSeconds($check_attendance['total_overtime'] ?? 0);
            $data[$project_id]['total_late_time'] += $this->timeToSeconds($check_attendance['total_late_time'] ?? 0);
            $data[$project_id]['total_absen'] += $check_attendance['total_absen'] ?? 0;
            $data[$project_id]['overtime_amount'] += $overtime_amount;
            $data[$project_id]['late_amount'] += $late_amount;
            $data[$project_id]['basic_salary_current_day'] += $basic_salary_current_day;
            $data[$project_id]['total_cost'] += $total_amount;
            $data[$project_id]['total_absen_penalty'] += isset( $total_absen_penalty) ? $total_absen_penalty : 0;
        }

        return view('backend.payroll.attendance.employee.project-report',compact('data','selected_project', 'selected_employee', 'month', 'year', 'employees'));
    }

    public function reportDetails($id, Request $request){

        $project = JobProject::find($id);

        if(!$project){
            return back();
        }

        $new_project = $project->new_project;

        $date = Carbon::now();
        $year = $request->year ?? date('Y');
        $month = $request->month ?? date('m');
        $employee_id = $request->employee_id;
        $monthYear = $year .'-'.$month;

        $attendances = EmployeeAttendance::where('project_id',$id)->whereMonth('date', $month)->whereYear('date', $year)
            ->when($employee_id, function($q) use($employee_id){
                $q->where('employee_id', $employee_id);
            })->get();

        $data = [];

        $totalDaysInMonth = Carbon::parse($monthYear)->daysInMonth;
        $currentDayOfMonth = $date->format('Y-m') == $monthYear ? $date->day : $totalDaysInMonth;

        foreach ($attendances as $attendance) {
            $employee = Employee::find($attendance->employee_id);
            if (!$employee) continue;

            $employee_id = $employee->id;
            $employee_name = $employee->full_name;

            $salary_process = SalaryProcess::where('employee_id', $employee->id)
                ->where('advance_amount', '>', 0)
                ->get();

            $already_payment = SalaryProcess::where('employee_id', $employee->id)
                ->where('status', 1)
                ->where('month', $month)
                ->where('year', $year)
                ->get();

            $salary_date = new DateTime("$year-$month-01");
            $last_visite = $employee->last_visite ?? $employee->joining_date ?? null;

            $text = 0;

            if ($last_visite) {
                $last = new DateTime($last_visite);
                $interval = $salary_date->diff($last);
                $text = ($interval->y * 12) + $interval->m;
            }

            $basic_salary = 0;
            $check_attendance = EmployeeAttendance::check_attendance($employee->id, $month, $year, $basic_salary, null, null, $project->id);
            $overtime_amount = isset($check_attendance['overtime_amount']) ? str_replace(',', '', $check_attendance['overtime_amount']) : 0;
            $late_amount = isset($check_attendance['late_amount']) ? str_replace(',', '', $check_attendance['late_amount']) : 0;
            $total_absen_penalty = $check_attendance['total_absen_penalty'] ?? 0;
            $basic_salary = $check_attendance['basic_salary'] ?? 0;
            $total_absen_penalty = isset($check_attendance['total_absen_penalty']) ? $check_attendance['total_absen_penalty'] : 0;
            $basic_salary_current_day = $basic_salary;

            if ($currentDayOfMonth < $totalDaysInMonth) {
                $basic_salary_current_day = ($basic_salary / $totalDaysInMonth) * $currentDayOfMonth;
            }

            $total_amount = ($overtime_amount - $late_amount - $total_absen_penalty) + $basic_salary_current_day;
            $time = $check_attendance['total_working_hours'] ?? '00:00:00';
            $total_working_seconds = $this->timeToSeconds($time);

            // Initialize if not exists
            if (!isset($data[$employee_id])) {
                $data[$employee_id] = [
                    'employee_name' => $employee->full_name,
                    'total_days' => 1,
                    'total_working_hours' => $total_working_seconds,
                    'total_overtime' => 0,
                    'total_late_time' => 0,
                    'total_absen' => 0,
                    'overtime_amount' => 0,
                    'late_amount' => 0,
                    'basic_salary_current_day' => 0,
                    'total_cost' => 0,
                    'total_absen_penalty' => 0,
                ];
            }
            // Increment totals
            $data[$employee_id]['total_days'] += 1;
            $data[$employee_id]['total_working_hours'] += $total_working_seconds;
            $data[$employee_id]['total_overtime'] += $this->timeToSeconds($check_attendance['total_overtime'] ?? 0);
            $data[$employee_id]['total_late_time'] += $this->timeToSeconds($check_attendance['total_late_time'] ?? 0);
            $data[$employee_id]['total_absen'] += $check_attendance['total_absen'] ?? 0;
            $data[$employee_id]['overtime_amount'] += $overtime_amount;
            $data[$employee_id]['late_amount'] += $late_amount;
            $data[$employee_id]['basic_salary_current_day'] += $basic_salary_current_day;
            $data[$employee_id]['total_cost'] += $total_amount;
            $data[$employee_id]['total_absen_penalty'] += isset( $total_absen_penalty) ? $total_absen_penalty : 0;
        }

        return view('backend.payroll.attendance.employee.project-report-details',compact('data','new_project'));
    }
}

<?php

namespace App\Models\Payroll;

use App\JobProject;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payroll\Employee;
use DateTime;
use DateTimeZone;

class EmployeeAttendance extends Model
{
    protected $guarded= [];


    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function project(){
        return $this->belongsTo(JobProject::class,'project_id');
    }


    public static function attendance($emp_id, $date)
    {
        $employee = Employee::find($emp_id);
        $result = [  // Initialize at the top so it always has a value
            'status' => '4',
            'out_time' => '' ,
            'evening_out' => '',
            'in_time' => '',
            'evening_in' => '',
            'late_time' => '',
            'overtime' => '',
            'total_hours' => ''
        ];

        if ($employee) {
            $attendance = EmployeeAttendance::whereDate('date', $date)
                ->where('employee_id', $emp_id)
                ->first();

            if ($attendance) {
                $result['status'] = $attendance->status ?? '4';
                $result['in_time'] = $attendance->in_time ?? '';
                $result['out_time'] = $attendance->out_time ?? '';
                $result['evening_in'] = $attendance->evening_in ?? '';
                $result['evening_out'] = $attendance->evening_out ?? '';
                $result['late_time'] = $attendance->total_late_time ?? '';
                $result['overtime'] = $attendance->total_overtime ?? '';
                $result['total_hours'] = $attendance->total_working_hours ?? '';
                $result['project_id'] = $attendance->project_id;
            }
        }

        return $result; // Ensure this return statement is here
    }




    public static function check_attendance($employee_id, $month, $year, $basic, $from_date = null, $to_date = null, $project_id = null)
    {
        $year = (int) $year;
        $month = (int) $month;
        $employee = Employee::find($employee_id);
        // Retrieve the policy for the specified month and year, or the latest if not found

            $date = date('Y-m-d');
            $emp_policy = policy_helper($employee->emp_id,$date);
            $basic = $emp_policy->basic_salary ?? 0;

        // Get late grace time from policy in minutes
        $lateGraceTimeInMinutes = $emp_policy ? intval($emp_policy->late_grace_time) : 0;

        // Retrieve all attendance records for the specified month and year
        $attendanceData = self::where('employee_id', $employee_id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->when($from_date || $to_date, function($query) use($from_date, $to_date){
                if($from_date && $to_date){
                    $query->whereBetween('date', [$from_date, $to_date]);
                }elseif($to_date && !$from_date){
                    $query->where('date',  $to_date);
                }elseif($from_date && !$to_date){
                    $query->where('date', $to_date);
                }
            })->when($project_id, function($query) use ($project_id){
                $query->where('project_id', $project_id);
            })
            ->get();

        // Initialize totals
        $totalLateTime = 0;
        $totalOvertime = 0;
        $totalWorkingHours = 0;
        $total_absen = 0;

        $lateDays = 0;
        if(count($attendanceData) >0){
            foreach ($attendanceData as $attendance) {
                if($attendance->status == 0){
                    $total_absen +=1;
                }

                // Convert each time field to seconds
                $lateTimeInSeconds = self::timeToSeconds($attendance->total_late_time);
                $overtimeInSeconds = self::timeToSeconds($attendance->total_overtime);
                $workingHoursInSeconds = self::timeToSeconds($attendance->total_working_hours);

                // Convert late grace time to seconds for comparison
                $lateGraceTimeInSeconds = $lateGraceTimeInMinutes * 60;

                // Check if the late time exceeds the grace period
                if ($lateTimeInSeconds > $lateGraceTimeInSeconds) {
                    $lateDays++;
                }

                // Accumulate totals
                $totalLateTime += $lateTimeInSeconds;
                $totalOvertime += $overtimeInSeconds;
                $totalWorkingHours += $workingHoursInSeconds;
            }

            // Calculate Overtime Amount
            $overtime_amount = 0;
            $policy_over_time = $emp_policy->apply_over_time ?? '';
            $minimum_hours_for_overtime =  $emp_policy->min_hours_for_overtime ?? 0;
            $min_hours_for_overtime = $minimum_hours_for_overtime * 3600;

            if ($policy_over_time == 'Yes' && $totalOvertime >= $min_hours_for_overtime) {
                $daily_rate = $basic / 30;
                $total_over_time_hours = $totalOvertime / 3600;
                $overtime_amount = $total_over_time_hours * ($emp_policy->overtime_rate / 100) * $daily_rate;
            }

            // Calculate Late Amount
            $minimum_hours_for_late =  $emp_policy->minimum_hours_for_late ?? 0;

            $minimum_hours_for_late = $minimum_hours_for_late * 3600;
            $late_amount = 0;
            $late_rate = $emp_policy->salary_loss ?? 0;
            $daily_rate = $basic / 30;
            $late_type = $emp_policy->late_type ?? '';
            $minimum_day = $emp_policy->minimum_day_for_late ?? 0;

            if ($late_type == 'day' && $minimum_day > 0 && $minimum_day <= $lateDays) {
                $incriment_rate =  $lateDays/$minimum_day;
                $late_amount = $incriment_rate * $daily_rate * $late_rate;
            } elseif ($late_type == 'hours'&& $minimum_hours_for_late > 0 && $minimum_hours_for_late <= $totalLateTime) {
                $incriment_rate = $totalLateTime/$minimum_hours_for_late;
                $late_amount = $incriment_rate * $daily_rate * $late_rate / 100;
            }
             $total_absen_penalty = $daily_rate*$total_absen;

            // Convert totals back to HH:MM:SS format and return
            return [
                'emp_policy_id' => $emp_policy->id ?? 0,
                'total_late_time' => self::secondsToTime($totalLateTime) ?? 0,
                'total_overtime' => self::secondsToTime($totalOvertime) ?? 0,
                'total_working_hours' => self::secondsToTime($totalWorkingHours) ?? 0,
                'overtime_amount' =>$overtime_amount,
                'late_amount' => $late_amount,
                'total_absen' => $total_absen,
                'total_absen_penalty' => $total_absen_penalty,
                'basic_salary' => $basic,
                // 'minimum_hours_for_late' =>  $totalLateTime
            ];
        }
        return 0;

    }


    private static function timeToSeconds($time)
    {
        list($hours, $minutes, $seconds) = sscanf($time, '%d:%d:%d');
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }

    private static function secondsToTime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }




}

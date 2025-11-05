<?php

namespace App;

use App\Models\Payroll\ExtraSalaryComponentHistory;
use App\Models\Payroll\GradeWiseSalaryComponentHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class JobProjectTask extends Model
{
    protected $guarded=[];

    public function project(){
        return $this->belongsTo(JobProject::class);
    }
    public function vat(){
        return $this->belongsTo(VatRate::class);
    }

    public function expenses(){
        return $this->hasMany(PurchaseExpenseItem::class,'task_id');
    }
    public function task_expenses(){
        return $this->hasMany(BillDistribute::class,'task_id');
    }
    public function project_task_base_expense($project_id, $task_id){
        // employee attendance
        $employee_attendances = EmployeeAttendance::with('employee')->where('project_id', $project_id)->where('project_task_id', $task_id)->get();
        // return count($employee_attendances);
        $total_amount = 0;
        $basic_salary = 0;
        $over_time_amount = 0;
        foreach($employee_attendances as $key => $one_day){
            $per_day_salary = 0;
            $per_day_extra_salary = 0;
            $days_counts = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($one_day->date)),date('Y', strtotime($one_day->date)));

            $date = GradeWiseSalaryComponentHistory::where('grade_id',$one_day->employee->grade)->whereMonth('date','<=',$one_day->date)->whereYear('date',$one_day->date)->orderBy('id','DESC')->first();
            if(!$date){
                $date = GradeWiseSalaryComponentHistory::where('grade_id',$one_day->employee->grade)->whereYear('date','<',$one_day->date)->orderBy('id','DESC')->first();
            }
            if($date){
                $per_month_salary = GradeWiseSalaryComponentHistory::where('grade_id',$one_day->employee->grade)->where('date',$date->date)->get();
                $per_day_salary = $per_month_salary->sum('value')/$days_counts;
            }
            $date_extra = ExtraSalaryComponentHistory::where('employee_id',$one_day->employee_id)->whereMonth('date','<=',$one_day->date)->whereYear('date',$one_day->date)->orderBy('id','DESC')->first();
            if(!$date_extra){
                $date_extra = ExtraSalaryComponentHistory::where('employee_id',$one_day->employee_id)->whereYear('date','<',$one_day->date)->orderBy('id','DESC')->first();
            }
            if ($date_extra != null){
                $extra_salary_amount = ExtraSalaryComponentHistory::where('employee_id',$one_day->employee_id)->where('date',$one_day->date)->sum('value');
                $per_day_extra_salary = $extra_salary_amount/$days_counts;;
            }
            $total_amount += $per_day_salary+$per_day_extra_salary;
        }
        // employee overtime
        $employee_overtime = EmployeeOvertime::with('employee')->where('project_id', $project_id)->where('project_task_id', $task_id)->get();
        // return count($employee_overtime);
        foreach($employee_overtime as $key => $one_day){
            $days_counts = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($one_day->date)),date('Y', strtotime($one_day->date)));
            $date = GradeWiseSalaryComponentHistory::where('grade_id',$one_day->employee->grade)->whereMonth('date','<=',$one_day->date)->whereYear('date',$one_day->date)->orderBy('id','DESC')->first();
            if(!$date){
                $date = GradeWiseSalaryComponentHistory::where('grade_id',$one_day->employee->grade)->whereYear('date','<',$one_day->date)->orderBy('id','DESC')->first();
            }
            if($date){
                $basic_salary = GradeWiseSalaryComponentHistory::where('grade_id',$one_day->employee->grade)->where('date',$date->date)->where('salary_component_id',1)->get();
                $per_hours_salary = ($basic_salary->sum('value')/$days_counts)/10;
                $over_time_amount += $one_day->hours*$per_hours_salary;
            }
        }
        return $total_amount+$over_time_amount;
    }

    public function items(){
        return $this->hasMany(JobProjectTaskItem::class, 'task_id');
    }

    public function getEstimatedProgressAttribute()
    {

        if (!$this->start_date || !$this->end_date) {
            return 100;
        }

        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        $now = Carbon::now();

        if ($now->lt($start)) {
            return 0;
        }

        if ($now->gt($end)) {
            return 100;
        }

        $totalDuration = $start->diffInSeconds($end);

        if ($totalDuration === 0) {
            return 100; // or 0, depending on your logic
        }

        $elapsed = $start->diffInSeconds($now);
        $progress = ($elapsed / $totalDuration) * 100;

        return round($progress, 2);
    }
}

<?php

namespace App\Models\Payroll;

use App\EmployeeLeave;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payroll\Department;
use App\Models\Payroll\Division;
use App\User;
use App\Models\FundAllocation;
use Carbon\Carbon;
use Facade\FlareClient\Flare;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Week;

class Employee extends Model
{
    protected $guarded = ['id'];

    public function leave(){
        return $this->hasMany(EmployeeLeave::class, 'employee_id');
    }

    public function code()
    {
        return $this->belongsTo(Country::class, 'country_code');
    }
    public function dvision()
    {
        return $this->belongsTo(Division::class, 'division');
    }
    public function dpt()
    {
        return $this->belongsTo(Department::class, 'department');
    }
    public function job_type_info()
    {
        return $this->belongsTo(JobTypeInfo::class, 'job_type');
    }
    public function items()
    {
        return $this->belongsTo(SalaryType::class, 'employee_wage_type');
    }

    public function div_name()
    {
        return $this->belongsTo(Division::class, 'division');
    }

    public function gradeNeed()
    {
        return $this->belongsTo(Grade::class, 'grade');
    }

    public function gradeWise($id)
    {
        return GradeWiseSalaryComponent::where('grade_id',$id)->get();
    }

    // public function companies()
    // {
    //     return $this->belongsTo(GroupCompanies::class, 'company');
    // }
    // public function banks()
    // {
    //     return $this->belongsTo(BankBranch::class, 'country_code');
    // }

    public function in()
    {
        $in = $this->hasMany(TimeTrack::class, 'employee_id')->orderby('id','DESC')->first();
        if ($in) {
            return $in->out == NULL?true:false;
        }
        return false;
    }

    //extra salary component
    public function extraSalaryComponent()
    {
        $extra = $this->hasMany(ExtraSalaryComponent::class, 'employee_id')->orderby('id','DESC')->get();
        // dd($in->out == NULL);
        return $extra;
    }

    public function extraCom($id)
    {
        return $this->hasMany(ExtraSalaryComponent::class,'employee_id')->where('salary_component_id',$id)->first();
    }

    public function user()
    {
        return $this->hasOne(User::class,'employee_id');
    }
    public function getNameAttribute(){
        return $this->salutation . ' ' . $this->first_name .' '.$this->middle_name . ' ' .$this->last_name;
    }

    function present($emp_id , $date){
        $attendance = EmployeeAttendance::where('employee_id',$emp_id )->whereDate('date', $date)->first();
        if($attendance){
            $late = $attendance->total_late_time != '' ? 1 : '';
            return  ['present'=> $attendance->status , 'resone'=>$attendance->total_late_time , 'late'=>$late];

        }else{
            return   ['present'=> '', 'resone'=>'' ,'late' => ''];

        }
    }
    function in_out($emp_id , $date){
        $attendance = EmployeeAttendance::where('employee_id', $emp_id)->whereDate('date', $date)->first();
        if ($attendance) {
            return [
                'in' => $attendance->in_time ? $attendance->in_time : '',
                'out' => $attendance->out_time ? $attendance->out_time : '',
                'evening_in' => $attendance->evening_in ? $attendance->evening_in : '',
                'evening_out' => $attendance->evening_out ? $attendance->evening_out : '',
                'late' => $attendance->total_late_time ? $attendance->total_late_time : '',
                'over_time' => $attendance->total_overtime ? $attendance->total_overtime : '',
                'working_hours' => $attendance->total_working_hours ? $attendance->total_working_hours : '',



            ];
        } else {
            return [
                'in' => '',
                'out' => '',
                'evening_in' => '',
                'evening_out' => '',
                'late' => '',
                'over_time' =>'',
                'working_hours' => '',


            ];
        }
    }

    function day_present($date) {
        $attendanceCounts = DB::table('employee_attendances')
        ->selectRaw('
                COUNT(CASE WHEN status = "1" THEN 1 END) as present_count,
                COUNT(CASE WHEN status = "3" THEN 1 END) as weekend,
                COUNT(CASE WHEN status = "2" THEN 1 END) as leave_count,
                COUNT(CASE WHEN status = "0" THEN 1 END) as absent_count,
                COUNT(CASE WHEN status = "4" THEN 1 END) as holy_day
            ')
         ->whereDate('date', $date)
        ->first();
        $total_day_present = $attendanceCounts->present_count ;

        $total_user =  Employee::where('job_status',1)->count();
        $total_present_percentage = $total_user > 0 ? ($total_day_present / $total_user) * 100 : 0;

        return [
            'total_present_percentage1' => $total_present_percentage,
            'attendance_p' => $attendanceCounts->present_count
        ];
    }
    function emp_month_present($emp_id ,$date) {
        $month = date('m',strtotime( $date));
        $year = date('Y',strtotime( $date));

        $attendanceCounts = DB::table('employee_attendances')->where('employee_id',$emp_id )->whereMonth('date', $month)->whereYear('date', $year)
        ->selectRaw('
                COUNT(CASE WHEN status = "1" THEN 1 END) as present_count,
                COUNT(CASE WHEN status = "3" THEN 1 END) as weekend,
                COUNT(CASE WHEN status = "2" THEN 1 END) as leave_count,
                COUNT(CASE WHEN status = "0" THEN 1 END) as absent_count,
                COUNT(CASE WHEN status = "4" THEN 1 END) as holy_day,
                COUNT(CASE WHEN total_late_time IS NOT NULL THEN 1 END) as late_count


            ')
            ->first();
        $total_day = $attendanceCounts->present_count + $attendanceCounts->absent_count + $attendanceCounts->leave_count + $attendanceCounts->holy_day;
        $total_day_present =  $total_day  - $attendanceCounts->absent_count ;

        $total_present_percentage = $total_day > 0 ? ($total_day_present / $total_day) * 100 : 0;

        return [
            'total_present_percentage' => $total_present_percentage,
            'attendance_p' => $attendanceCounts->present_count,
            'attendance_a' => $attendanceCounts->absent_count,
            'holiday' => $attendanceCounts->holy_day,
            'weekend' => $attendanceCounts->weekend,
            'leave' => $attendanceCounts->leave_count,

            'attendance_l' => $attendanceCounts->late_count
        ];
    }

    function weekend($emp_id , $date){
        $attendance = WeekendHoliday::where('emp_id',$emp_id )->whereDate('date', $date)->first();

        if($attendance){
            $weekend = $attendance->date != '' ? 1 : '';
            return  ['weekend'=> $weekend , 'day'=>$attendance->weekend , 'month'=>$attendance->month];

        }else{

            $start_of_week = Carbon::parse($date)->startOfWeek(Carbon::SATURDAY)->format('Y-m-d');
            $end_of_week = Carbon::parse($date)->endOfWeek(Carbon::FRIDAY)->format('Y-m-d');
            $check = WeekendHoliday::where('emp_id',$emp_id )->whereBetween('date', [$start_of_week, $end_of_week])->first();
            if (!$check) {
                $employee = Employee::find($emp_id);

                if (!$employee) {
                    return [
                        'weekend' => '',
                        'day' => '',
                        'month' => '',
                    ];
                }
                $defaultDays = json_decode($employee->default_weekend, true);
                if (!is_array($defaultDays)) {
                    $defaultDays = [];
                }
                $carbonDate = Carbon::parse($date);
                $today = $carbonDate->format('l');
                $month = $carbonDate->format('F');
                $isWeekend = in_array($today, $defaultDays);

                return [
                    'weekend' => $isWeekend ? 1 : '',
                    'day' => $isWeekend ? $today : '',
                    'month' => $isWeekend ? $month : '',
                ];
            } else {
                return [
                    'weekend' => '',
                    'day' => '',
                    'month' => '',
                ];
            }
        }
    }

    public function payment_account(){
        return $this->hasOne(FundAllocation::class, 'paid_by');
    }

    public function subordinates()
    {
        return $this->belongsToMany(Employee::class, 'reporting_authorities', 'parent_id', 'child_id')
                    ->withPivot('work_date');
    }

    public function recursiveSubordinates()
    {
        return $this->subordinates()->with('recursiveSubordinates');
    }

    public function getTodaySubordinates($employeeId)
    {
        $date = Carbon::today()->toDateString();

        $employee = Employee::findOrFail($employeeId);

        $todaySubordinates = $employee->subordinates()
            ->wherePivot('work_date', $date)
            ->get();

        return $todaySubordinates;
    }

}

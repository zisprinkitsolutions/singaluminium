<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Payroll\Department;
use App\Models\Payroll\Division;
class Employee extends Model
{
    protected $guarded = ['id'];
    public function last_assign_project($id){
        return EmployeeAttendance::where('employee_id', $id)->orderBy('id', 'desc')->first();
    }
    public function last_working_project($id){
        return JobProject::where('id',$id)->first();
    }
    public function last_working_task($employee_id, $project_id,$task_id){
        return EmployeeEngageTask::where('employee_id', $employee_id)->where('project_id', $project_id)->where('task_id', $task_id)->orderBy('id', 'desc')->first();
    }
    public function type_of_employee(){
        return $this->belongsTo(TypeOfEmployee::class);
    }
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
}

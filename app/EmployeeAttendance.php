<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
    protected $guarded= ['id'];

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function project(){
        return $this->belongsTo(JobProject::class, 'project_id');
    }
}

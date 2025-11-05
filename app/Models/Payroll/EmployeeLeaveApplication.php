<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveApplication extends Model
{
    protected $guarded = [];


    public function emp(){
        return $this->belongsTo(Employee::class , 'employee_id');
    }

}

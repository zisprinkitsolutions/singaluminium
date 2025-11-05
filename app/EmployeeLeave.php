<?php

namespace App;

use App\Models\Payroll\Employee;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function employeeName(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    
}

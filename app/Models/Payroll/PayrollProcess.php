<?php

namespace App\Models\Payroll;
use App\Models\Payroll\Employee;

use Illuminate\Database\Eloquent\Model;

class PayrollProcess extends Model
{
    public function employeeName(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}

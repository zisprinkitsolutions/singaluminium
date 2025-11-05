<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;
use App\Models\Payroll\Employee;

class EmployeeSalary extends Model
{
    public function items()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    protected $guarded = [];
}

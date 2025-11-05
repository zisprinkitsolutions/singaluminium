<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;
use App\Models\Payroll\Employee;

class SalaryStructure extends Model
{
    protected $fillable = ['employee_id'];

    public function employeeName(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}

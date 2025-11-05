<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;

class EmployeeTemp extends Model
{
    public function items()
    {
        return $this->belongsTo(SalaryType::class, 'employee_wage_type');
    }

    public function dpt()
    {
        return $this->belongsTo(Department::class, 'department');
    }
    public function dvision()
    {
        return $this->belongsTo(Division::class, 'division');
    }

    public function div()
    {
        return $this->belongsTo(Division::class, 'division');
    }

    public function code()
    {
        return $this->belongsTo(Country::class, 'country_code');
    }

    public function gradeNeed()
    {
        return $this->belongsTo(Grade::class, 'grade');
    }

    public function gradeWise($id)
    {
        return GradeWiseSalaryComponent::where('grade_id',$id)->get();
    }

    protected $guarded = [];
}

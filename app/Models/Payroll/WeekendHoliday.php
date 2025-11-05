<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;

class WeekendHoliday extends Model
{
    public function emp(){
        return $this->belongsTo(Employee::class , 'emp_id');
    }
}

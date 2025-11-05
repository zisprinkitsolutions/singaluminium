<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;

class TimeTrack extends Model
{
    public function emp()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    protected $guarded = [];
}

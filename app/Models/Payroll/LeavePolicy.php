<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;

class LeavePolicy extends Model
{
    protected $guarded = [];
    public function emp(){
        return $this->belongsTo(Employee::class , 'emp_id');
    }

}

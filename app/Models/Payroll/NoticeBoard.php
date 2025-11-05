<?php

namespace App\Models\Payroll;

use App\Models\Payroll\Employee;
use Illuminate\Database\Eloquent\Model;

class NoticeBoard extends Model
{
    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}

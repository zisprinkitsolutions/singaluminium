<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LpoBillDetail extends Model
{
    public function unit(){
        return $this->belongsTO(Unit::class,'unit_id');
    }
    public function task()
    {
        return $this->belongsTo(JobProjectTask::class,'task_id');
    }

    public function subTask()
    {
        return $this->belongsTo(JobProjectTaskItem::class,'sub_task_id');
    }
}

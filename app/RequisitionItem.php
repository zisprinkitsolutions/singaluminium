<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisitionItem extends Model
{
     public function unit(){
        return $this->belongsTO(Unit::class,'unit_id');
    }

        public function task()
    {
        return $this->belongsTo(JobProjectTask::class,'job_project_task_id');
    }

    public function subTask()
    {
        return $this->belongsTo(JobProjectTaskItem::class,'job_project_task_item_id');
    }
}

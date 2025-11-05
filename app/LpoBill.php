<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LpoBill extends Model
{
    public function party()
    {
        return $this->belongsTo(PartyInfo::class,'party_id');
    }

    public function items()
    {
        return $this->hasMany(LpoBillDetail::class,'lpo_bill_id');
    }

    public function job_project(){
        return $this->belongsTo(JobProject::class, 'job_project_id');
    }

    public function project()
    {
        return $this->belongsTo(JobProject::class,'project_id');
    }

    public function task()
    {
        return $this->belongsTo(JobProjectTask::class,'task_id');
    }

    public function subTask()
    {
        return $this->belongsTo(JobProjectTaskItem::class,'task_item_id');
    }
}

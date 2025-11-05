<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    public function party(){
        return $this->belongsTo(PartyInfo::class, 'party_id');
    }

    public function items(){
        return $this->hasMany(RequisitionItem::class, 'requisition_id');
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

    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoqSample extends Model
{
    protected $guarded = [];

    public function task(){
        return $this->belongsTo(NewProjectTask::class, 'task_id');
    }

    public function item(){
        return $this->belongsTo(NewProjectTaskItem::class, 'item_id');
    }

    public function boq_unit(){
        return $this->belongsTo(BoqSampleUnit::class, 'unit');
    }
}

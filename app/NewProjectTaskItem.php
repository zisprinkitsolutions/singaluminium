<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewProjectTaskItem extends Model
{
    public function task(){
        return $this->belongsTo(NewProjectTask::class,'task_id');
    }
}

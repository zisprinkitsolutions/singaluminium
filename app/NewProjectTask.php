<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewProjectTask extends Model
{
    public function items(){
        return $this->hasMany(NewProjectTaskItem::class, 'task_id');
    }

    public function sub_tasks(){
        return $this->hasMany(NewProjectSubTask::class, 'task_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewProjectSubTask extends Model
{
    public function items(){
        return $this->hasMany(NewProjectTaskItem::class, 'sub_task_id');
    }
}

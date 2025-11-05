<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoqTaskName extends Model
{
    protected $fillable = ['token', 'item_task'];
    public function items(){
        return $this->hasMany(BoqItemDetail::class, 'task_id');
    }
}

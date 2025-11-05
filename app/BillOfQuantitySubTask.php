<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillOfQuantitySubTask extends Model
{
    public function items(){
        return $this->hasMany(BillOfQuantityItem::class, 'sub_task_id');
    }
}

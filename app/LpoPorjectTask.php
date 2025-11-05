<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LpoPorjectTask extends Model
{
    protected $guarded=[];

    public function project(){
        return $this->belongsTo(LpoProject::class);
    }

    public function vat(){
        return $this->belongsTo(VatRate::class);
    }

    public function items(){
        return $this->hasMany(LpoProjectTaskItem::class, 'task_id');
    }
}

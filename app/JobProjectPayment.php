<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobProjectPayment extends Model
{
    protected $guarded=[];

    public function project(){
        return $this->belongsTo(JobProject::class,'job_project_id');
    }
    public function party(){
        return $this->belongsTo(PartyInfo::class,'party_info_id');
    }
}

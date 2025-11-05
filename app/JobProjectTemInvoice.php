<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobProjectTemInvoice extends Model
{
    protected $guarded = [];

    public function tasks(){
        return $this->hasMany(JobProjectTemInvoiceTask::class,'invoice_id');
    }

    public function party(){
        return $this->belongsTo(PartyInfo::class,'customer_id');
    }

    public function project(){
        return $this->belongsTo(JobProject::class,'job_project_id');
    }
    public function new_project(){
        return $this->belongsTo(NewProject::class, 'project_id');
    }

}

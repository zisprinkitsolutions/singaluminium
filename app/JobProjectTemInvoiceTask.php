<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobProjectTemInvoiceTask extends Model
{
    protected $guarded = [];

    public function vat(){
        return $this->belongsTo(VatRate::class,'vat_id');
    }

    public function project_task(){
        return $this->belongsTo(JobProjectTask::class,'task_id');
    }
}

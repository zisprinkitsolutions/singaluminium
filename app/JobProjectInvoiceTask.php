<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobProjectInvoiceTask extends Model
{
    protected $guarded = [];

    public function invoices()
    {
        // return $this->belongsToMany(JobProjectInvoice::class,'job_project_invoice_task','task_id','invoice_id');
    }

    public function vat(){
        return $this->belongsTo(VatRate::class,'vat_id');
    }
    public function unit_name()
    {
        return $this->belongsTo(Unit::class,'unit_id');
    }
}

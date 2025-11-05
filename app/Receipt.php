<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    public function party()
    {
        return $this->belongsTo(PartyInfo::class,'party_id');
    }
    public function subsidiary(){
        return $this->belongsTo(Subsidiary::class,'company_id');
    }
    public function items()
    {
        return $this->hasMany(ReceiptSale::class,'payment_id');
    }

    public function job_project()
    {
        return $this->belongsTo(JobProject::class,'job_project_id');
    }
public function getProspectAttribute()
{
    // Get the first (and only) item if items exist
    $firstItem = $this->items->first();

    if (!$firstItem) {
        return null;
    }

    $invoice = optional($firstItem->invoice);
    $project = optional($invoice->project);
    $quotation = optional($project->quotation);
    $boq = optional($quotation->boq);
    $newProject = optional($boq->project);

    // Return single NewProject model or null
    return $newProject ?: null;
}


    public function bank_name(){
        return $this->belongsTo(AccountSubHead::class, 'bank_id');
    }
}

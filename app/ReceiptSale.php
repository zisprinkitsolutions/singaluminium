<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ReceiptSale extends Model
{
        protected $guarded = [];

    public function sale()
    {
        return $this->belongsTo(Sale::class,'sale_id');
    }

    public function invoice()
    {
        return $this->belongsTo(JobProjectInvoice::class,'sale_id');
    }
    public function payment(){
        return $this->belongsTo(Receipt::class,'payment_id');
    }

    public function project(){
        return $this->belongsTo(JobProject::class,'site_project');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\JobProject;

class JobProjectInvoice extends Model
{
    protected $guarded = [];

    public function tasks(){
        return $this->hasMany(JobProjectInvoiceTask::class,'invoice_id');
    }
    public function subsidiary(){
        return $this->belongsTo(Subsidiary::class,'compnay_id');
    }
    public function party(){
        return $this->belongsTo(PartyInfo::class,'customer_id');
    }

     public function project()
    {
        return $this->belongsTo(JobProject::class, 'job_project_id', 'id');
    }
    public function getProspectAttribute()
    {
        return optional(optional(optional(optional($this->project)->quotation)->boq)->project);
    }
    public function receipts(){
        return $this->hasMany(ReceiptSale::class,'sale_id');
    }

    public function tempReceipt()
    {
        return $this->hasOne(TempReceiptVoucherDetail::class,'sale_id');
    }

    public function tem_receipt_amount()
    {
        return $this->hasMany(TempReceiptVoucherDetail::class,'sale_id')->sum('Total_amount');
    }
    public function new_project(){
        return $this->belongsTo(NewProject::class, 'project_id');
    }

    public function documents(){
        return $this->hasMany(SaleVoucher::class,'invoice_id');
    }
    public function items(){
        return $this->hasMany(JobProjectInvoiceTask::class,'invoice_id');
    }

    public function receipt_lists(){
        return $this->belongsToMany(Receipt::class,'receipt_sales', 'sale_id', 'payment_id')->withPivot('amount');
    }
    public function company(){
        return $this->belongsTo(Subsidiary::class, 'compnay_id');
    }
}

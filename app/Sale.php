<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = [];
    public function party()
    {
        return $this->belongsTo(PartyInfo::class,'party_id');
    }
    public function subsidiary(){
        return $this->belongsTo(Subsidiary::class,'compnay_id');
    }
    public function items()
    {
        return $this->hasMany(SaleItem::class,'sale_id');
    }
    public function project(){
        return $this->belongsTo(JobProject::class, 'site_project');
    }

    public function documents(){
        return $this->hasMany(SaleVoucher::class,'temp_invoice_id');
    }

    public function receipts(){
        return $this->belongsToMany(TempReceiptVoucher::class,'temp_receipt_voucher_details','sale_id', 'payment_id');
    }
    public function company(){
        return $this->belongsTo(Subsidiary::class, 'compnay_id');
    }
}

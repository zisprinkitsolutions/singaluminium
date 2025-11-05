<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentInvoice extends Model
{
    public function purchase()
    {
        return $this->belongsTo(PurchaseExpense::class,'sale_id');
    }
    public function party(){
        return $this->belongsTo(PartyInfo::class, 'party_id');
    }
    public function payment(){
        return $this->belongsTo(Payment::class,'payment_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];
    public function party()
    {
        return $this->belongsTo(PartyInfo::class,'party_id');
    }

    public function items()
    {
        return $this->hasMany(PaymentInvoice::class,'payment_id');
    }
    public function bank_name(){
        return $this->belongsTo(AccountSubHead::class, 'bank_id');
    }

    public function payment_account(){
        return $this->belongsTo(Employee::class, 'paid_by', 'id');
    }
}

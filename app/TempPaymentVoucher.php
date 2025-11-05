<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempPaymentVoucher extends Model
{
    public function party()
    {
        return $this->belongsTo(PartyInfo::class,'party_id');
    }

    public function items()
    {
        return $this->hasMany(PaymentInvoice::class,'payment_id');
    }

    public function temp_payment_voucher_details(){
        return $this->hasMany(TempPaymentVoucherDetail::class,'payment_id');
    }
    public function bank_name(){
        return $this->belongsTo(AccountSubHead::class, 'bank_id');
    }
}

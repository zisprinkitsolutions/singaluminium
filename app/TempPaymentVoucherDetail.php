<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempPaymentVoucherDetail extends Model
{
    public function purchase()
    {
        return $this->belongsTo(PurchaseExpense::class,'sale_id');
    }
}

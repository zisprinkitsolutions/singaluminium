<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempReceiptVoucherDetail extends Model
{
    public function sale()
    {
        return $this->belongsTo(Sale::class,'sale_id');
    }

    public function invoice()
    {
        return $this->belongsTo(JobProjectInvoice::class,'sale_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempReceiptVoucher extends Model
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
        return $this->hasMany(TempReceiptVoucherDetail::class,'payment_id');
    }

    public function job_project()
    {
        return $this->belongsTo(JobProject::class,'job_project_id');
    }
    public function bank_name(){
        return $this->belongsTo(AccountSubHead::class, 'bank_id');
    }
}

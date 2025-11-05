<?php

namespace App\Models;


use App\JournalRecord;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterAccount extends Model
{
    use  SoftDeletes;
    public function accType()
    {
        return $this->belongsTo('App\Models\MstACType', 'mst_ac_type');

    }

    public function records()
    {
        return $this->hasMany(JournalRecord::class,'master_account_id');
    }

    public function masterDR()
    {
       return JournalRecord::where('master_account_id',$this->id)->where('transaction_type','DR')->sum('total_amount');
    }

    public function masterCR()
    {
       return JournalRecord::where('master_account_id',$this->id)->where('transaction_type','CR')->sum('total_amount');
    }


}

<?php

namespace App;

use App\Models\AccountHead;
use Illuminate\Database\Eloquent\Model;

class FundCollection extends Model
{
    public function account_head(){
        return $this->belongsTo(AccountHead::class, 'account_head_id');
    }
    public function party(){
        return $this->belongsTo(PartyInfo::class, 'party_id');
    }
    
    public function documents(){
        return $this->hasMany(FundDocument::class, 'fund_id');
    }
    
    public function receipts(){
        return $this->hasMany(ReceiptSale::class,'sale_id');
    }
}

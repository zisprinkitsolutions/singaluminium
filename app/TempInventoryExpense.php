<?php

namespace App;
use App\Models\AccountHead;
use Illuminate\Database\Eloquent\Model;

class TempInventoryExpense extends Model
{
    public function account_head(){
        return $this->belongsTo(AccountHead::class, 'account_head_id');
    }
    public function sub_account_head(){
        return $this->belongsTo(AccountSubHead::class, 'sub_account_id');
    }
    public function items(){
        return $this->hasMany(TempProjectExpense::class, 'inventory_expense_id');
    }
}

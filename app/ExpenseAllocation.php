<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\AccountHead;
class ExpenseAllocation extends Model
{
    public function account_head(){
        return $this->belongsTo(AccountHead::class, 'account_head_id');
    }
    public function items(){
        return $this->hasMany(ExpenseAllocationItem::class, 'expense_allocation_id');
    }
}

<?php

namespace App;

use App\Models\AccountHead;
use Illuminate\Database\Eloquent\Model;

class PurchaseExpenseItemTemp extends Model
{
    public function head()
    {
        return $this->belongsTo(AccountHead::class,'head_id');
    }
    public function head_sub()
    {
        return $this->belongsTo(AccountSubHead::class,'sub_head_id');
    }

    public function purchase()
    {
        return $this->belongsTo(PurchaseExpense::class,'purchase_expense_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class,'unit_id');
    }

    public function task()
    {
        return $this->belongsTo(JobProjectTask::class,'task_id');
    }
    public function cogs_head($id, $head_id, $sub_head_id){
        if($head_id){
            return TempCogsAssign::where('purchase_expense_id', $id)->where('account_head_id', $head_id)->first();
        }else{
            return TempCogsAssign::where('purchase_expense_id', $id)->where('sub_head_id', $sub_head_id)->first();
        }
        
    }
    public function subsidiary_head($id, $head_id, $sub_head_id){
        if($head_id){
            return SubsidiaryStore::where('purchase_id', $id)->where('account_head_id', $head_id)->first();
        }else{
            return SubsidiaryStore::where('purchase_id', $id)->where('sub_head_id', $sub_head_id)->first();
        }
        
    }
}

<?php

namespace App;

use App\Models\AccountHead;
use Illuminate\Database\Eloquent\Model;

class PurchaseExpenseItem extends Model
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
    public function project_head($account_id, $id){
        $data = ProjectExpense::where('account_head_id', $account_id)->where('purchase_expense_id', $id)->get();
        return $data;
    }
    public function project_sub_head($account_id, $id){
        $data = ProjectExpense::where('sub_head_id', $account_id)->where('purchase_expense_id', $id)->get();
        return $data;
    }

}

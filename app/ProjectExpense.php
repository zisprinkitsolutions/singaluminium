<?php

namespace App;
use App\Models\AccountHead;
use Illuminate\Database\Eloquent\Model;

class ProjectExpense extends Model
{
    public function project(){
        return $this->belongsTo(JobProject::class, 'project_id');
    }
    public function expense(){
        return $this->belongsTo(PurchaseExpense::class, 'purchase_expense_id');
    }

    public function account_head(){
        return $this->belongsTo(AccountHead::class,'account_head_id');
    }

    public function account_subhead(){
        return $this->belongsTo(AccountSubHead::class, 'sub_head_id');
    }
    public function project_task(){
        return $this->belongsTo(JobProjectTask::class, 'task_id');
    }
    public function project_task_item(){
        return $this->belongsTo(JobProjectTaskItem::class, 'task_item_id');
    }
    public function cogs_account(){
        return $this->belongsTo(AccountHead::class, 'cogs_account_id');
    }
}

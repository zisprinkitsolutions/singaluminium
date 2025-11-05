<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseExpenseTemp extends Model
{
    public function party()
    {
        return $this->belongsTo(PartyInfo::class,'party_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseExpenseItemTemp::class,'purchase_expense_id');

    }

    public function job_project()
    {
        return $this->belongsTo(JobProject::class,'job_project_id');
    }
    public function bill_distribute(){
        return $this->hasMany(BillDistribute::class, 'bill_id');
    }
    public function documents(){
        return $this->hasMany(TempPurchaseExpenseDocument::class, 'expense_id');
    }
}

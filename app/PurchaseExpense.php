<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseExpense extends Model
{
    public function party()
    {
        return $this->belongsTo(PartyInfo::class,'party_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseExpenseItem::class,'purchase_expense_id');

    }

    public function job_project()
    {
        return $this->belongsTo(JobProject::class,'job_project_id');
    }
  public function getProspectAttribute()
    {
        return optional(optional(optional(optional($this->job_project)->quotation)->boq)->project);
    }
    public function tempPayment()
    {
        return $this->hasOne(TempPaymentVoucherDetail::class,'sale_id');
    }

    public function tem_paid_amount()
    {
        return $this->hasMany(TempPaymentVoucherDetail::class,'sale_id')->sum('Total_amount');
    }

    public function payment_invoice(){
        return $this->hasMany(PaymentInvoice::class, 'sale_id');
    }

    public function bill_distribute(){
        return $this->hasMany(BillDistribute::class, 'bill_id');
    }
    public function documents(){
        return $this->hasMany(PurchaseExpenseDocument::class, 'expense_id');
    }
    public function project_expense(){
        return $this->hasMany(ProjectExpense::class, 'purchase_expense_id');
    }

}

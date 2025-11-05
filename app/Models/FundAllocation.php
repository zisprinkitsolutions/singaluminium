<?php

namespace App\Models;

use App\Models\Payroll\Employee;
use App\PayMode;
use Illuminate\Database\Eloquent\Model;

class FundAllocation extends Model
{
    public function fromAccount()
    {
        return $this->belongsTo(PayMode::class,'account_id_from');
    }
    public function toAccount()
    {
        return $this->belongsTo(PayMode::class,'account_id_to');
    }

    public function documents(){
        return $this->hasMany(FundAllocationDocument::class, 'allocation_id');
    }
    public function payment_account(){
        return $this->belongsTo(Employee::class, 'paid_by');
    }
}

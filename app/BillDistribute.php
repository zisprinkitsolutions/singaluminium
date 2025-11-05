<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillDistribute extends Model
{
    public function job_project()
    {
        return $this->belongsTo(JobProject::class,'project_id');
    }
    public function bill_info(){
        return $this->belongsTo(PurchaseExpense::class, 'bill_id');
    }
}

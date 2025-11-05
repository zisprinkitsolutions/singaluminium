<?php

namespace App;

use App\Models\AccountHead;
use Illuminate\Database\Eloquent\Model;

class NewProject extends Model
{
     protected $fillable = [
        'name',
        'project_no',
        'project_type',
        'project_code',
        'party_id',
        'mobile_no',
        'location',
        'consultant',
        'start_date',
        'end_date',
        'details',
        'handover_on',
        'project_status',
        'total_amount',
        'plot',
        'engineer',
        'short_name',
        'contract_value',
        'vat',
        'variation',
        'total_contract',
        'estimation',
        'ps_budget',
        'status',
        'date',
        'insurance',
        'contract',
        'contract_period',
        'area',
        'file_no',
        'deadline',
    ];
    public function party(){
        return $this->belongsTo(PartyInfo::class, 'party_id');
    }
    public function company(){
        return $this->belongsTo(Subsidiary::class, 'company_id');
    }
    public function invoices()
    {
        return $this->hasMany(JobProjectInvoice::class,'job_project_id');
    }

    public function tasks(){
        return $this->hasMany(NewProjectTask::class, 'project_id');
    }

    public function purchase_expense(){
        return $this->hasMany(ProjectExpense::class, 'project_id');
    }

    public function temp_receipt()
    {
        $invoices = JobProjectInvoice::where('job_project_id',$this->id)->has('tempReceipt')->get();
        $sum=0;
        foreach($invoices as $inv)
        {
            $sum+=$inv->tem_receipt_amount();
        }
        return $sum;
    }

    public function boqs(){
        return $this->hasMany(BillOfQuantity::class,'project_id');
    }

    public function work_order(){
        return $this->hasOne(JobProject::class,'project_id');
    }

    public function gantt_charts(){
        return $this->hasMany(GnattChart::class,'project_id');
    }
}

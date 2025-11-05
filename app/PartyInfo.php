<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class PartyInfo extends Model
{
    use  SoftDeletes;
    protected $guarded=[];

    public function journals()
    {
        return $this->hasMany(Journal::class,'party_info_id');
    }

    public function quotations(){
        return $this->hasMany(LpoProject::class,'customer_id');
    }

    public function project(){
        return $this->hasMany(NewProject::class, 'party_id');
    }

    public function jobProjects(){
        return $this->hasMany(JobProject::class, 'customer_id');
    }

    public function customerName(){
        return $this->hasMany(JournalRecord::class, 'party_info_id')->where('transaction_type', 'DR');
    }

    public function tempJournal(){
        $all= $this->hasMany(JournalTemp::class, 'project_id')->get();
        return $all->count();
    }

    public function journalCount(){
        $all= $this->hasMany(Journal::class, 'project_id')->get();
        return $all->count();
    }

    public static function opening($party,$date)
    {

        $cr=JournalRecord::where('party_info_id', $party->id)->where('journal_date','<', $date)->where('account_head_id','5')->where('transaction_type','CR')->sum('amount');
        $dr=JournalRecord::where('party_info_id', $party->id)->where('journal_date','<', $date)->where('account_head_id','5')->where('transaction_type','DR')->sum('amount');
        $cr2=JournalRecord::where('party_info_id', $party->id)->where('journal_date','<', $date)->where('account_head_id','3')->where('transaction_type','CR')->sum('amount');
        $dr2=JournalRecord::where('party_info_id', $party->id)->where('journal_date','<', $date)->where('account_head_id','3')->where('transaction_type','DR')->sum('amount');
        $cr3=JournalRecord::where('party_info_id', $party->id)->where('journal_date','<', $date)->where('account_head_id','853')->where('transaction_type','CR')->sum('amount');
        $dr3=JournalRecord::where('party_info_id', $party->id)->where('journal_date','<', $date)->where('account_head_id','853')->where('transaction_type','DR')->sum('amount');
        $amount = $dr-$cr2+$dr2-$cr3+$dr3 - $cr;
        $tdr=$dr+$dr2+$dr3;
        $tcr=$cr2+$cr3+$cr;
        $balance=$dr-$cr2+$dr2-$cr3+$dr3-$cr;
        return $balance;

    }

    public function journal_record_payable($party_id,$date, $from, $to){
        if($date){
            $records=JournalRecord::where('journal_date', $date)->whereIn('master_account_id',[2])->whereNotIn('account_head_id',[19])->where('party_info_id',$party_id)->select('journal_id')->distinct()->get();
        }elseif($from && $to){
            $records=JournalRecord::whereBetween('journal_date', [$from, $to])->whereIn('master_account_id',[2])->whereNotIn('account_head_id',[19])->where('party_info_id',$party_id)->select('journal_id')->distinct()->get();
        }else{
            $records=JournalRecord::whereIn('master_account_id',[2])->whereNotIn('account_head_id',[19])->where('party_info_id',$party_id)->select('journal_id')->distinct()->get();
        }
        return $records;
    }
    public function journal_record_receivable($party_id, $date, $from, $to){
        if($date){
            $records=JournalRecord::where('journal_date', $date)->whereIn('master_account_id',[1])->whereIn('account_head_id',[3])->where('party_info_id',$party_id)->select('journal_id')->distinct()->get();
        }elseif($from && $to){
            $records=JournalRecord::whereBetween('journal_date', [$from, $to])->whereIn('master_account_id',[1])->whereIn('account_head_id',[3])->where('party_info_id',$party_id)->select('journal_id')->distinct()->get();
        }else{
            $records=JournalRecord::whereIn('master_account_id',[1])->whereIn('account_head_id',[3])->where('party_info_id',$party_id)->select('journal_id')->distinct()->get();
        }
        return $records;
    }


    public function due()
    {
        return $this->hasMany(JournalRecord::class,'party_info_id')->where('account_head_id',5)->where('transaction_type','CR')->sum('amount')-$this->hasMany(JournalRecord::class,'party_info_id')->where('account_head_id',5)->where('transaction_type','DR')->sum('amount');
    }

    public function projects(){
        return $this->hasMany(NewProject::class,'party_id');
    }

    public function boqs(){
        return $this->hasMany(BillOfQuantity::class, 'party_id');
    }

    public function effects()
    {
        $check = DB::select("
        SELECT id AS id FROM job_project_invoices WHERE customer_id = $this->id
        UNION
        SELECT id AS id FROM receipts WHERE party_id = $this->id
        UNION
        SELECT id AS id FROM purchase_expense_temps WHERE party_id =  $this->id
        UNION
        SELECT id AS id FROM purchase_expenses WHERE party_id =  $this->id
        UNION
        SELECT id AS id FROM sales WHERE party_id =  $this->id
        UNION
        SELECT id AS id FROM payments WHERE party_id =  $this->id
        UNION
        SELECT id AS id FROM temp_payment_vouchers WHERE party_id =  $this->id
        UNION
        SELECT id AS id FROM temp_receipt_vouchers WHERE party_id =  $this->id

        UNION
        SELECT id AS id FROM journals WHERE party_info_id =  $this->id
        UNION
        SELECT id AS id FROM journal_temps WHERE party_info_id =  $this->id
        ;
    ");

    return $check;

    }
}

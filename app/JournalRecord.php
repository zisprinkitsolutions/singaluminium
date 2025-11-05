<?php

namespace App;
use App\Models\AccountHead;
use App\Models\CostCenter;
use App\Models\MasterAccount;
use Auth;
use Illuminate\Database\Eloquent\Model;

class JournalRecord extends Model
{
    protected $guarded = [];

    public function journal(){
        return $this->belongsTo(Journal::class);
    }
    public function ac_sub_head(){
        return $this->belongsTo(AccountSubHead::class,'sub_account_head_id');
    }
    public function ac_head(){
        return $this->belongsTo(AccountHead::class,'account_head_id');
    }

    public function master_ac(){
        return $this->belongsTo(MasterAccount::class,'master_account_id');
    }

    public function openingBalanceLadgerDR($id,$date)
    {
        // dd($this);
        return JournalRecord::where('journal_date','<',$date)->where('account_head_id',$id)->where('transaction_type','DR')->sum('amount');
        // $sum=0;
        // $records=JournalRecord::where('account_head_id',$id)->where('journal_date','<',$date)->distinct()->get('journal_id');
        // foreach($records as $r)
        // {
        //     $sum=$sum+JournalRecord::where('journal_id',$r->journal_id)->where('account_head_id','!=',$id)->where('transaction_type','CR')->sum('amount');
        // }
        // return $sum;
    }


    public function openingBalanceLadgerCR($id,$date)
    {
        return JournalRecord::where('journal_date','<',$date)->where('account_head_id',$id)->where('transaction_type','CR')->sum('amount');
        // $sum=0;
        // $records=JournalRecord::where('account_head_id',$id)->where('journal_date','<',$date)->distinct()->get('journal_id');
        // foreach($records as $r)
        // {
        //     // DD(JournalRecord::where('journal_id',$r->journal_id)->where('account_head_id','!=',$id)->where('transaction_type','CR')->get());
        //     $sum=$sum+JournalRecord::where('journal_id',$r->journal_id)->where('account_head_id','!=',$id)->where('transaction_type','DR')->sum('amount');
        // }
        // // dd($sum);
        // return $sum;
    }


    public function balanceCD($id)
    {

       return JournalRecord::where('account_head_id', $id)->where('journal_id', '!=',0)->where('transaction_type', 'DR')->sum('amount') - JournalRecord::where('account_head_id', $id)->where('journal_id', '!=',0)->where('transaction_type', 'CR')->sum('amount');
    }


    public function masterbalanceCD($id)
    {
       $records = JournalRecord::where('master_account_id', $id)->where('journal_id', '!=',0)->get();
       $dr=0;
       $cr=0;
       foreach($records as $r)
       {
        $reverse= $r->transaction_type== "DR" ? "CR" : "DR";
        foreach ($r_count=JournalRecord::where('journal_id',$r->journal_id)->where('transaction_type', $reverse)->get() as $ledger_record)
        {
            if($r_count->count()>1)
            {
                if($r->transaction_type=='DR')
                {
                    $dr=$dr+ $ledger_record->amount;
                }
                else
                {
                    $cr=$cr+ $ledger_record->amount;
                }
            }
            else
            {
                if($r->transaction_type=='DR')
                {
                    $dr=$dr+ $r->amount;
                }
                else
                {
                    $cr=$cr+ $r->amount;
                }
            }
        }
       }
       return $dr-$cr;
    }



    public function accCD($id)
    {
       $records = JournalRecord::where('account_head_id', $id)->where('journal_id', '!=',0)->get();
       $dr=JournalRecord::where('account_head_id', $id)->where('journal_id', '!=',0)->where('transaction_type','DR')->sum('amount');
       $cr=JournalRecord::where('account_head_id', $id)->where('journal_id', '!=',0)->where('transaction_type','CR')->sum('amount');

       return $dr-$cr;
    }

    public function party()
    {
        return $this->belongsTo(PartyInfo::class,'party_info_id');
    }



    public function inventoryBalance()
    {
        $inventoryCredit=JournalRecord::where('master_account_id',$this->master_account_id)->where('transaction_type','CR')->sum('amount');
        $inventoryDebit=JournalRecord::where('master_account_id',$this->master_account_id)->where('transaction_type','DR')->sum('amount');
        return $inventoryDebit-$inventoryCredit;

    }


    public function headBalance($id)
    {
        $dr = JournalRecord::where('account_head_id', $id)->where('transaction_type','DR')->sum('amount');
        $cr = JournalRecord::where('account_head_id', $id)->where('transaction_type','CR')->sum('amount');
        return $dr-$cr;


    }


    public function headOpeningBalance($id,$date)
    {

        $dr = JournalRecord::where('account_head_id', $id)->where('transaction_type','DR')->where('journal_date','<',$date)->sum('amount');
        $cr = JournalRecord::where('account_head_id', $id)->where('transaction_type','CR')->where('journal_date','<',$date)->sum('amount');

        return $dr-$cr;


    }

    public function inventoryOpeningBalance($date)
    {
        $inventoryCredit=JournalRecord::where('master_account_id',$this->master_account_id)->where('transaction_type','CR')->where('journal_date','<',$date)->sum('amount');
        $inventoryDebit=JournalRecord::where('master_account_id',$this->master_account_id)->where('transaction_type','DR')->where('journal_date','<',$date)->sum('amount');
        return $inventoryDebit-$inventoryCredit;

    }

    public function headClosingBalance($id,$date)
    {
        $dr = JournalRecord::where('account_head_id', $id)->where('transaction_type','DR')->where('journal_date','<=',$date)->sum('amount');
        $cr = JournalRecord::where('account_head_id', $id)->where('transaction_type','CR')->where('journal_date','<=',$date)->sum('amount');
        return $dr-$cr;


    }

    public function inventoryClosingBalance($date)
    {
        $inventoryCredit=JournalRecord::where('master_account_id',$this->master_account_id)->where('transaction_type','CR')->where('journal_date','<=',$date)->sum('amount');
        $inventoryDebit=JournalRecord::where('master_account_id',$this->master_account_id)->where('transaction_type','DR')->where('journal_date','<=',$date)->sum('amount');
        // dd($inventoryCredit-$inventoryDebit);
        return $inventoryDebit-$inventoryCredit;

    }

    public function headTransection($id,$date,$date2,$type)
    {

        return JournalRecord::where('account_head_id', $id)->where('transaction_type',$type)->where('journal_date','>=',$date)->where('journal_date','<=',$date2)->sum('amount');

    }


    public function inventoryTransection($date,$date2,$type)
    {
        return JournalRecord::where('master_account_id',$this->master_account_id)->where('transaction_type',$type)->where('journal_date','>=',$date)->where('journal_date','<=',$date2)->sum('amount');
    }



    public static function openingProfit($date)
    {
        $dr=JournalRecord::whereIn('account_type_id',[4,3])->where('transaction_type','DR')
            ->when($date, function($q) use($date){
                $q->where('journal_date','<',$date);
            })->sum('amount');
        $cr=JournalRecord::whereIn('account_type_id',[4,3])->where('transaction_type','CR')
            ->when($date, function($q) use($date){
                $q->where('journal_date','<',$date);
            })->sum('amount');

        return $dr-$cr;

    }

    public function openingProfitRoi($invoice_ids, $purchase_ids){
        $dr = JournalRecord::join('journals as j', 'journal_records.journal_id', '=', 'j.id')
            ->where(function ($query) use ($invoice_ids, $purchase_ids) {
                $query->whereIn('j.invoice_id', $invoice_ids)
                    ->orWhereIn('j.purchase_expense_id', $purchase_ids);
            })->whereIn('journal_records.account_type_id', [4, 3])
            ->where('transaction_type','DR')->sum('amount');
    }


    public static function openingProfitBalanceSheet($date)
    {
        $dr=JournalRecord::whereIn('account_type_id',[1,6,2])->where('transaction_type','DR')->where('journal_date','<',$date)->sum('amount');
        $cr=JournalRecord::whereIn('account_type_id',[1,6,2])->where('transaction_type','CR')->where('journal_date','<',$date)->sum('amount');
        return $dr-$cr;

    }

    public function headDrCrTransaction($date,$date2, $project_id = null)
    {
        $dr=JournalRecord::where('account_head_id', $this->account_head_id)->where('transaction_type','DR')
        ->when($date && $date2, function($q) use($date, $date2){
            $q->whereBetween('journal_date',[$date,$date2]);
        })->when($project_id, function($q) use($project_id){
            $q->where('project_id', $project_id);
        })->sum('amount');
        $cr=JournalRecord::where('account_head_id', $this->account_head_id)->where('transaction_type','CR')
        ->when($date && $date2, function($q) use($date, $date2){
            $q->whereBetween('journal_date',[$date,$date2]);
        })->when($project_id, function($q) use($project_id){
            $q->where('project_id', $project_id);
        })->sum('amount');

        return $dr-$cr;
    }

    public function inventoryDrCrTransection($date,$date2,$project_id = null)
    {
        if($project_id){
            $dr = JournalRecord::where('master_account_id',$this->master_account_id)->where('transaction_type','DR')->where('project_id',$project_id)->sum('amount');
            $cr = JournalRecord::where('master_account_id',$this->master_account_id)->where('transaction_type','CR')->where('project_id',$project_id)->sum('amount');
            return $dr-$cr;
        }
        $dr= JournalRecord::where('master_account_id',$this->master_account_id)->where('transaction_type','DR')->whereBetween('journal_date',[$date,$date2])->sum('amount');
        $cr= JournalRecord::where('master_account_id',$this->master_account_id)->where('transaction_type','CR')->whereBetween('journal_date',[$date,$date2])->sum('amount');
        return $dr-$cr;
    }

    static public function corporate_tax_details($from_date, $to_date,$accountType, $type){
        $details = JournalRecord::whereBetween('journal_records.journal_date', [$from_date, $to_date])
            ->where('journal_records.office_id', Auth::user()->office_id)
            ->join('master_accounts', 'master_accounts.id', '=', 'journal_records.master_account_id')
            ->where('master_accounts.mst_definition', $accountType)
            ->selectRaw('
                journal_records.account_head_id,
                SUM(CASE WHEN journal_records.transaction_type = "DR" THEN journal_records.total_amount ELSE 0 END) as total_dr,
                SUM(CASE WHEN journal_records.transaction_type = "CR" THEN journal_records.total_amount ELSE 0 END) as total_cr
            ')
            ->groupBy('journal_records.account_head_id')
            ->get();
        $details = $details->map(function ($item) use ($type) {
            if ($type === 'CR') {
                $item->net_amount = $item->total_cr - $item->total_dr; // CR - DR
            } else { // Default to DR
                $item->net_amount = $item->total_dr - $item->total_cr; // DR - CR
            }
            return $item;
        });
        return $details;
    }
    static public function balance_sheet_details($from_date, $to_date,$id, $type){
        $details = JournalRecord::whereBetween('journal_records.journal_date', [$from_date, $to_date])
            ->where('journal_records.office_id', Auth::user()->office_id)
            ->join('master_accounts', 'master_accounts.id', '=', 'journal_records.master_account_id')
            ->where('master_accounts.id', $id)
            ->selectRaw('
                journal_records.account_head_id,
                SUM(CASE WHEN journal_records.transaction_type = "DR" THEN journal_records.total_amount ELSE 0 END) as total_dr,
                SUM(CASE WHEN journal_records.transaction_type = "CR" THEN journal_records.total_amount ELSE 0 END) as total_cr
            ')
            ->groupBy('journal_records.account_head_id')
            ->get();
        $details = $details->map(function ($item) use ($type) {
            if ($type === 'CR') {
                $item->net_amount = $item->total_cr - $item->total_dr; // CR - DR
            } else { // Default to DR
                $item->net_amount = $item->total_dr - $item->total_cr; // DR - CR
            }
            return $item;
        });
        return $details;
    }
    static public function balance_fwd($id, $year, $month, $to, $from){
        if($year || $month || $to || $from){
            $records=JournalRecord::whereIn('account_head_id', [3,5,30,1759])->where('party_info_id', $id);
            if($month){
                $records=$records->whereMonth('journal_date','<',$month);
            }
            if($year){
                $records=$records->whereYear('journal_date','<',$year);
            }
            if($from && $to){
                $records=$records->where('journal_date','<',$from);

            }elseif($to){
                $records=$records->where('journal_date','<',$to);
            }elseif($from){
                $records=$records->where('journal_date','<',$from);
            }
            $records = $records->get();
            $balance_fwd_dr = $records->where('transaction_type', 'DR')->sum('total_amount');
            $balance_fwd_cr = $records->where('transaction_type', 'CR')->sum('total_amount');
            return [$balance_fwd_dr,$balance_fwd_cr];
        }else{
            return [0,0];
        }
    }
}

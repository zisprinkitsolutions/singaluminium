<?php

namespace App\Http\Controllers\backend;

use App\DebitCreditVoucher;
use App\Http\Controllers\Controller;
use App\Journal;
use App\JournalRecord;
use App\Models\AccountHead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpeningBalanceController extends Controller
{
    public function opening_cash_asset()
    {
        $items=AccountHead::where('master_account_id',1)->whereNotIn('id',[3])->get();
        return view('backend.opening-balance.opening-cash-asset',compact('items'));
    }

    public function opening_cash_asset_store(Request $request)
    {


        $total_dr=0;
        $total_cr=0;
        // **************************************************
        $sub_invoice = Carbon::now()->format('Ymd');
        $latest_journal_no = Journal::withTrashed()->whereDate('created_at', Carbon::today())->where('journal_no', 'LIKE', "%{$sub_invoice}%")->orderBy('id','desc')->first();
        if ($latest_journal_no) {
            $journal_no = substr($latest_journal_no->journal_no,0,-1);
            $journal_code = $journal_no + 1;
            $journal_no = $journal_code . "J";
        } else {
            $journal_no = Carbon::now()->format('Ymd') . '001' . "J";
        }
        // return $journal_no;
        $journal= new Journal();
        $journal->project_id        = 1;
        $journal->journal_no        = $journal_no;
        $journal->transection_type        = 'Openning Balance Entry';
        $journal->transaction_type        = 'Openning Balance Entry';

        $journal->date              = $request->date;
        $journal->pay_mode          = 'NonCash';
        $journal->invoice_no        = 'opening-0';
        $journal->cost_center_id    = 1;
        $journal->party_info_id     = 1;
        $journal->account_head_id   = 123;
        $journal->amount            =  $request->total_amount;
        $journal->tax_rate          = 0;
        $journal->vat_amount        = 0;
        $journal->total_amount      =  $journal->amount;
        $journal->narration         = 'Opening Cash Assets By-'. $journal->pay_mode;
        $journal->created_by        = Auth::id();
        $journal->authorized_by=Auth::id();
        $journal->approved_by=Auth::id();
        $journal->authorized        = 1;
        $journal->approved        = 1;
        $journal->voucher_type      = 'default.jpg';
        $journal->opening_balance_entry      = 1;
        $journal->save();
        // **************************************************
        $multi_head=$request->input('group-a');

        foreach($multi_head as $each_head){
            $dr_acc_head= AccountHead::find($each_head['item_name']);
            $jl_record= new JournalRecord();
            $jl_record->journal_id     = $journal->id;
            $jl_record->project_details_id  = 1;
            $jl_record->cost_center_id      = 1;
            $jl_record->party_info_id       = 1;
            $jl_record->journal_no          = $journal_no;
            $jl_record->account_head_id     = $dr_acc_head->id;
            $jl_record->master_account_id   = $dr_acc_head->master_account_id;
            $jl_record->account_head        = $dr_acc_head->fld_ac_head;
            $jl_record->amount              = $each_head['total_price'];
            $jl_record->total_amount        = $jl_record->amount ;
            $jl_record->vat_rate_id         = 1;
            $jl_record->transaction_type    = 'DR';
            $jl_record->journal_date        =  $journal->date;
            $jl_record->account_type_id = $dr_acc_head->account_type_id;
            $jl_record->is_main_head        = 1;
            $jl_record->opening_balance_entry      = 1;
            $jl_record->save();
            $total_dr=$total_dr+$each_head['total_price'];
        }

        if($total_dr>0)
        {
            $adjustment= AccountHead::find(25);
            $jl_record= new JournalRecord();
            $jl_record->journal_id     = $journal->id;
            $jl_record->project_details_id  = 1;
            $jl_record->cost_center_id      = 1;
            $jl_record->party_info_id       = 1;
            $jl_record->journal_no          = $journal_no;
            $jl_record->account_head_id     = $adjustment->id;
            $jl_record->master_account_id   = $adjustment->master_account_id;
            $jl_record->account_head        = $adjustment->fld_ac_head;
            $jl_record->amount              = $total_dr;
            $jl_record->total_amount        = $jl_record->amount ;
            $jl_record->vat_rate_id         = 1;
            $jl_record->transaction_type    = 'CR';
            $jl_record->journal_date        =  $journal->date;
            $jl_record->account_type_id = $adjustment->account_type_id;
            $jl_record->is_main_head        = 1;
            $jl_record->opening_balance_entry      = 1;
            $jl_record->save();
        }
        $dr_cr_voucher= new DebitCreditVoucher();
        $dr_cr_voucher->journal_id      = $journal->id;
        $dr_cr_voucher->project_id      =  $journal->project_id;
        $dr_cr_voucher->cost_center_id  = 1;
        $dr_cr_voucher->party_info_id   =  $journal->party_info_id;
        $dr_cr_voucher->account_head_id = 0;
        $dr_cr_voucher->pay_mode        = $journal->pay_mode;
        $dr_cr_voucher->amount          = $journal->total_amount;
        $dr_cr_voucher->narration       = $journal->narration;
        $dr_cr_voucher->type            =  'Opening Asset Entry';
        $dr_cr_voucher->date            = $journal->date;
        $dr_cr_voucher->save();

        return back()->with('success',"Successfully Added");
    }


    public function opening_others()
    {
        $items=AccountHead::whereIn('account_type_id',[3])->orWhereIn('id',[834,833,476])->get();
        return view('backend.opening-balance.opening-others',compact('items'));
    }


    public function opening_others_store(Request $request)
    {


        // dd($request->all());
        $total_dr=0;
        $total_cr=0;
        // **************************************************
        $sub_invoice = Carbon::now()->format('Ymd');
        $latest_journal_no = Journal::withTrashed()->whereDate('created_at', Carbon::today())->where('journal_no', 'LIKE', "%{$sub_invoice}%")->orderBy('id','desc')->first();
        if ($latest_journal_no) {
            $journal_no = substr($latest_journal_no->journal_no,0,-1);
            $journal_code = $journal_no + 1;
            $journal_no = $journal_code . "J";
        } else {
            $journal_no = Carbon::now()->format('Ymd') . '001' . "J";
        }
        // return $journal_no;
        $journal= new Journal();
        $journal->project_id        = 1;
        $journal->transection_type        = 'Openning Balance Entry';
        $journal->transaction_type        = 'Openning Balance Entry';

        $journal->journal_no        = $journal_no;
        $journal->date              = $request->date;


        $journal->pay_mode          = 'NonCash';
        $journal->invoice_no        = 'opening-0';
        $journal->cost_center_id    = 1;
        $journal->party_info_id     = 1;
        $journal->account_head_id   = 123;
        $journal->amount            =  $request->total_amount;
        $journal->tax_rate          = 0;
        $journal->vat_amount        = 0;
        $journal->total_amount      =  $journal->amount;
        $journal->narration         = 'Opening Entry By-'. $journal->pay_mode;
        $journal->created_by        = Auth::id();
        $journal->authorized_by=Auth::id();
        $journal->approved_by=Auth::id();
        $journal->authorized        = 1;
        $journal->approved        = 1;
        $journal->voucher_type      = 'default.jpg';
        $journal->opening_balance_entry      = 1;
        $journal->save();
        // **************************************************
        $multi_head=$request->input('group-a');

        foreach($multi_head as $each_head){
            $dr_acc_head= AccountHead::find($each_head['item_name']);
            $jl_record= new JournalRecord();
            $jl_record->journal_id     = $journal->id;
            $jl_record->project_details_id  = 1;
            $jl_record->cost_center_id      = 1;
            $jl_record->party_info_id       = 1;
            $jl_record->journal_no          = $journal_no;
            $jl_record->account_head_id     = $dr_acc_head->id;
            $jl_record->master_account_id   = $dr_acc_head->master_account_id;
            $jl_record->account_head        = $dr_acc_head->fld_ac_head;
            $jl_record->amount              = $each_head['total_price'];
            $jl_record->total_amount        = $jl_record->amount ;
            $jl_record->vat_rate_id         = 1;
            $jl_record->transaction_type    =  'CR';
            $jl_record->journal_date        =  $journal->date;
            $jl_record->Note        =  $each_head['note'];
            $jl_record->account_type_id = $dr_acc_head->account_type_id;
            $jl_record->is_main_head        = 1;
            $jl_record->opening_balance_entry      = 1;
            $jl_record->save();

                $total_cr=$total_cr+$each_head['total_price'];

        }

        if($total_dr!=$total_cr)
        {
            $adjustment= AccountHead::find(25);
            $jl_record= new JournalRecord();
            $jl_record->journal_id     = $journal->id;
            $jl_record->project_details_id  = 1;
            $jl_record->cost_center_id      = 1;
            $jl_record->party_info_id       = 1;
            $jl_record->journal_no          = $journal_no;
            $jl_record->account_head_id     = $adjustment->id;
            $jl_record->master_account_id   = $adjustment->master_account_id;
            $jl_record->account_head        = $adjustment->fld_ac_head;
            $jl_record->amount              = $total_dr>$total_cr ? ($total_dr-$total_cr):($total_cr-$total_dr);
            $jl_record->total_amount        = $jl_record->amount ;
            $jl_record->vat_rate_id         = 1;
            $jl_record->transaction_type    = $total_dr>$total_cr ? 'CR':'DR';
            $jl_record->journal_date        =  $journal->date;
            $jl_record->account_type_id = $adjustment->account_type_id;
            $jl_record->is_main_head        = 1;
            $jl_record->opening_balance_entry      = 1;
            $jl_record->save();
        }
        $journal->amount            =  $total_dr>$total_cr? $total_dr:$total_cr;
        $journal->total_amount      =  $journal->amount;
        $journal->save();
        $dr_cr_voucher= new DebitCreditVoucher();
        $dr_cr_voucher->journal_id      = $journal->id;
        $dr_cr_voucher->project_id      =  $journal->project_id;
        $dr_cr_voucher->cost_center_id  = 1;
        $dr_cr_voucher->party_info_id   =  $journal->party_info_id;
        $dr_cr_voucher->account_head_id = 0;
        $dr_cr_voucher->pay_mode        = $journal->pay_mode;
        $dr_cr_voucher->amount          = $journal->total_amount;
        $dr_cr_voucher->narration       = $journal->narration;
        $dr_cr_voucher->type            =  'Opening Asset/Liability Entry';
        $dr_cr_voucher->date            = $journal->date;
        $dr_cr_voucher->save();

        return back()->with('success',"Successfully Added");
    }
}

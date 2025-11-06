<?php

namespace App\Http\Controllers\backend;


use App\Http\Controllers\Controller;
use App\Journal;
use App\JournalRecord;
use App\Models\AccountHead;
use App\Models\FundAllocation;
use App\Models\FundAllocationDocument;
use App\PayMode;
use App\AccountSubHead;
use App\PaymentAccount;
use App\Models\Payroll\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class FundAllocationController extends Controller
{
    private function available_pay_amount($pay_mode){
        $pay_mode_check = PayMode::find($pay_mode);
        $balance = 0;
        if($pay_mode_check->title =='Cash'){
            $cash_cr = JournalRecord::where('account_head_id',1)->whereYear('created_at',date('Y'))->where('transaction_type','CR')->get()->sum('total_amount');
            $cash_dr = JournalRecord::where('account_head_id',1)->whereYear('created_at',date('Y'))->where('transaction_type','DR')->get()->sum('total_amount');
            $balance = $cash_dr - $cash_cr;
        }
        if($pay_mode_check->title =='Card' || $pay_mode_check->title =='Bank' || $pay_mode_check->title =='Cheque'){
            $bank_cr = JournalRecord::where('account_head_id',2)->whereYear('created_at',date('Y'))->where('transaction_type','CR')->get()->sum('total_amount');
            $bank_dr = JournalRecord::where('account_head_id',2)->whereYear('created_at',date('Y'))->where('transaction_type','DR')->get()->sum('total_amount');
            $balance = $bank_dr - $bank_cr;
        }if($pay_mode_check->title =='Petty Cash'){
            $cash_cr = JournalRecord::where('account_head_id',93)->whereYear('created_at',date('Y'))->where('transaction_type','CR')->get()->sum('total_amount');
            $cash_dr = JournalRecord::where('account_head_id',93)->whereYear('created_at',date('Y'))->where('transaction_type','DR')->get()->sum('total_amount');
            $balance = $cash_dr - $cash_cr;
        }
        return $balance;
    }
    public function index(Request $request)
    {
        Gate::authorize('Fund_Allocation');
        // $payment_accounts = PaymentAccount::all();
        $modes = PayMode::whereIn('id', [1,7,6 ])->get();
        $allocations=FundAllocation::where('approved',false)->orderBy('date','DESC');

        if($request->from_account_search){
            $allocations = $allocations->where('account_id_from', $request->from_account_search);
        }
        if($request->to_account_search){
            // dd($request->search);
            $allocations = $allocations->where('account_id_to', $request->to_account_search);
        }
        if($request->date_search){
            $allocations = $allocations->whereDate('date', $this->dateFormat($request->date_search));
        }
        if($request->date_from_search ){
            $allocations = $allocations->whereDate('date', '>=',$this->dateFormat($request->date_from_search));
        }
        if($request->date_to_search ){
            $allocations = $allocations->whereDate('date', '<=',$this->dateFormat($request->date_to_search));
        }
        $allocations =$allocations->get();
        $employee = Employee::orderBy('full_name')->whereNotIn('division', [4])->get();
        return view('backend.fund-allocation.index',compact('modes','allocations', 'employee'));
    }

    private function dateFormat($date){
        $old_date = explode('/', $date);

        $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }

    public function store(Request $request)
    {
        Gate::authorize('Accounting_Create');
        $request->validate(
            [
                'date'              =>  'required',
                'from_account'        => 'required',
                'to_account'        => 'required',
                'amount'        => 'required',
            ],
            [
                'date.required'         => 'Date is required',
                'from_account.required'   => 'From Account is required',
                'to_account.required'   => 'To Account is required',
                'amount.required'   => 'Amount is required',
            ]
        );
        if($request->to_account==6 && ! $request->paid_by){
            $request->validate([
                'paid_by' => 'required',
            ],[
                'paid_by.required' => 'Payment Account is required',
            ]);
        }
        $r_amount = $request->amount+$request->transaction_cost;
        if($this->available_pay_amount($request->from_account) >= $r_amount){
            $fund = new FundAllocation();
            $fund->account_id_from = $request->from_account;
            $fund->account_id_to = $request->to_account;
            $fund->date =$this->dateFormat($request->date);
            $fund->transaction_number = $request->transaction_number;
            $fund->note = $request->note;
            $fund->amount = $request->amount;
            $fund->paid_by = $request->paid_by;
            $fund->transaction_cost = $request->transaction_cost?$request->transaction_cost:0.00;
            $fund->save();

            if($request->hasFile('voucher_scan')){
                $files = $request->file('voucher_scan');
                foreach($files as $file){
                    $document = new FundAllocationDocument();
                    $ext= $file->getClientOriginalExtension();
                    $name = hexdec(uniqid()).time().'.'.$ext;
                    $file->storeAs('public/upload/fund-allocation', $name);
                    $document->allocation_id = $fund->id;
                    $document->file_name = $name;
                    $document->ext = $ext;
                    $document->save();
                }
            }
            return view('backend.fund-allocation.preview', compact('fund'));
        }else{
            return 2;
        }
    }

    public function show($id)
    {
        $fund=FundAllocation::find($id);
        // dd($fund->documents);
        return view('backend.fund-allocation.preview', compact('fund'));

    }
    public function edit($id){
        Gate::authorize('Accounting_Edit');
        $fund=FundAllocation::find($id);
        $modes = PayMode::whereIn('id', [1,4,5])->get();
        return view('backend.fund-allocation.edit', compact('fund','modes'));
    }
    public function update(Request $request , $id){
        Gate::authorize('Accounting_Edit');

        $r_amount = $request->amount+$request->transaction_cost;
        if($this->available_pay_amount($request->from_account)>$r_amount){
            $fund=FundAllocation::find($id);
            $fund->account_id_from = $request->from_account;
            $fund->account_id_to = $request->to_account;
            $fund->date =$this->dateFormat($request->date);
            $fund->transaction_number = $request->transaction_number;
            $fund->note = $request->note;
            $fund->amount = $request->amount;
            $fund->transaction_cost = $request->transaction_cost;
            $fund->save();

            if($request->hasFile('voucher_scan')){
                $files = $request->file('voucher_scan');
                foreach($files as $file){
                    $document = new FundAllocationDocument();
                    $ext= $file->getClientOriginalExtension();
                    $name = hexdec(uniqid()).time().'.'.$ext;
                    $file->storeAs('public/upload/fund-allocation', $name);
                    $document->allocation_id = $fund->id;
                    $document->file_name = $name;
                    $document->ext = $ext;
                    $document->save();
                }
            }
            $notification = array(
                'message'       => 'Allocation Successfully!',
                'alert-type'    => 'success'
            );
        }else{
            $notification = array(
                'message'       => 'Allocation Update Something Wrong !',
                'alert-type'    => 'error'
            );
        }
        return back()->with($notification);

    }
    private function journal_no(){
        $sub_invoice = Carbon::now()->format('Ymd');
        // return $sub_invoice;
        $latest_journal_no = Journal::withTrashed()->whereDate('created_at', Carbon::today())->where('journal_no', 'LIKE', "%{$sub_invoice}%")->latest('id')->first();
        // return $latest_journal_no;
        if ($latest_journal_no) {
            $journal_no = substr($latest_journal_no->journal_no, 0, -1);
            $journal_code = $journal_no + 1;
            $journal_no = $journal_code . "J";
        } else {
            $journal_no = Carbon::now()->format('Ymd') . '001' . "J";
        }

        return $journal_no;
    }
    
    public function fund_allocation_delete($id)
    {
        $fund = FundAllocation::where('id', $id)->first();
        if ($fund) {
            if ($fund->approved) {
                $journal = Journal::where('fund_allocation_id', $id)->first();
                if ($journal) {
                    JournalRecord::where('journal_id', $journal->id)->forcedelete();
                    $journal->forcedelete();
                } else {
                    $journal = Journal::where('invoice_id', $id)->where('transection_type', 'RECEIPT VOUCHER')->where('voucher_type', 'Receipt Voucher')->first();
                    if ($journal) {
                        JournalRecord::where('journal_id', $journal->id)->forcedelete();
                        $journal->forcedelete();
                    }
                }
            }
             $fund->forcedelete();

                    $notification = array(
                        'message'       => 'Fund Allocation Deleted',
                        'alert-type'    => 'success'
                    );
                    return back()->with($notification);
        }
        else
        {
            $notification = array(
                        'message'       => 'Fund Allocation Not Found',
                        'alert-type'    => 'warning'
                    );
                    return back()->with($notification);
        }
    }


    public function fund_allocation_approval($id)
    {
        Gate::authorize('Accounting_Approve');

        $fund=FundAllocation::find($id);
        if($fund)
        {
            $r_amount = $fund->amount+$fund->transaction_cost;
            if($this->available_pay_amount($fund->account_id_from)>=$r_amount){
                if($fund->approved==false)
                {
                    $journal = new Journal();
                    $journal->project_id        = 1;
                    $journal->transection_type  = 'Fund Allocation';
                    $journal->transaction_type  = 'DEBIT';
                    $journal->journal_no        = $this->journal_no();
                    $journal->date              = $fund->date;
                    $journal->voucher_type      = 'Fund Allocation';
                    $journal->fund_allocation_id        = $fund->id;
                    $journal->pay_mode          =  $fund->fromAccount->title ;
                    $journal->invoice_no        = 0;
                    $journal->cost_center_id    = 1;
                    $journal->party_info_id     = 0;
                    $journal->account_head_id   = 123;
                    $journal->amount            = $fund->amount+$fund->transaction_cost;
                    $journal->tax_rate          = 0;
                    $journal->vat_amount        = 0;
                    $journal->total_amount      =  $fund->amount+$fund->transaction_cost;
                    $journal->narration         = $fund->note?$fund->note:'n/a';
                    $journal->created_by        = Auth::id();
                    $journal->authorized_by     = Auth::id();
                    $journal->approved_by       = Auth::id();
                    $journal->save();

                    $from=$fund->account_id_from==1?1:($fund->account_id_from==7?2:($fund->account_id_from==6?93:2));
                    $to=$fund->account_id_to==1?1:($fund->account_id_to==7?2:($fund->account_id_to==6?93:2));

                    $from_head = AccountHead::find($from);
                    $jl_record = new JournalRecord();
                    $jl_record->journal_id          = $journal->id;
                    $jl_record->project_details_id  = 1;
                    $jl_record->cost_center_id      = 1;
                    $jl_record->party_info_id       = $journal->party_info_id;
                    $jl_record->journal_no          = $journal->journal_no;
                    $jl_record->account_head_id     = $from_head->id;
                    $jl_record->master_account_id   = $from_head->master_account_id;
                    $jl_record->account_head        = $from_head->fld_ac_head;
                    $jl_record->amount              = $journal->total_amount;
                    $jl_record->total_amount        = $journal->total_amount;
                    $jl_record->vat_rate_id         = 0;
                    $jl_record->transaction_type    = 'CR';
                    $jl_record->journal_date        = $journal->date;
                    $jl_record->account_type_id     = $from_head->account_type_id;
                    $jl_record->is_main_head        = 0;
                    $jl_record->save();
                    $sub_head = AccountSubHead::where('employee_id', $fund->paid_by)->first();
                    $to_head = AccountHead::find($to);
                    $jl_record = new JournalRecord();
                    $jl_record->journal_id          = $journal->id;
                    $jl_record->project_details_id  = 1;
                    $jl_record->cost_center_id      = 1;
                    $jl_record->party_info_id       = $journal->party_info_id;
                    $jl_record->journal_no          = $journal->journal_no;
                    $jl_record->account_head_id     = $to_head->id;
                    $jl_record->master_account_id   = $to_head->master_account_id;
                    $jl_record->account_head        = $to_head->fld_ac_head;
                    $jl_record->amount              = $fund->amount;
                    $jl_record->total_amount        = $fund->amount;
                    $jl_record->vat_rate_id         = 0;
                    $jl_record->transaction_type    = 'DR';
                    $jl_record->journal_date        = $journal->date;
                    $jl_record->account_type_id     = $to_head->account_type_id;
                    $jl_record->sub_account_head_id = $sub_head?$sub_head->id:null;
                    $jl_record->is_main_head        = 0;
                    $jl_record->save();
                    if($fund->transaction_cost>0)
                    {
                        $transaction_fee = AccountHead::find(92);
                        $jl_record = new JournalRecord();
                        $jl_record->journal_id          = $journal->id;
                        $jl_record->project_details_id  = 1;
                        $jl_record->cost_center_id      = 1;
                        $jl_record->party_info_id       = $journal->party_info_id;
                        $jl_record->journal_no          = $journal->journal_no;
                        $jl_record->account_head_id     = $transaction_fee->id;
                        $jl_record->master_account_id   = $transaction_fee->master_account_id;
                        $jl_record->account_head        = $transaction_fee->fld_ac_head;
                        $jl_record->amount              = $fund->transaction_cost;
                        $jl_record->total_amount        = $fund->transaction_cost;
                        $jl_record->vat_rate_id         = 0;
                        $jl_record->transaction_type    = 'DR';
                        $jl_record->journal_date        = $journal->date;
                        $jl_record->account_type_id     = $transaction_fee->account_type_id;
                        $jl_record->is_main_head        = 0;
                        $jl_record->save();
                    }
                    $fund->approved=true;
                    $fund->save();

                    $notification = array(
                        'message'       => 'Fund Allocation Approved',
                        'alert-type'    => 'success'
                    );
                    return back()->with($notification);

                }
            }
        else
        {
            $notification = array(
                'message'       => 'Amount Something Wrong !',
                'alert-type'    => 'warning'
            );
            return back()->with($notification);
        }

        }
        else
        {
            $notification = array(
                'message'       => 'Already Approve',
                'alert-type'    => 'warning'
            );
            return back()->with($notification);
        }
    }

    public function fund_allocation_approve(Request $request)
    {
        Gate::authorize('Accounting_Approve');
        $modes = PayMode::whereIn('id', [1,7,6 ])->get();
        $allocations=FundAllocation::where('approved',true)->orderBy('date','DESC');

        if($request->from_account_search){
            $allocations = $allocations->where('account_id_from', $request->from_account_search);
        }
        if($request->to_account_search){
            // dd($request->search);
            $allocations = $allocations->where('account_id_to', $request->to_account_search);
        }
        if($request->date_search){
            $allocations = $allocations->whereDate('date', $this->dateFormat($request->date_search));
        }
        if($request->date_from_search ){
            $allocations = $allocations->whereDate('date', '>=',$this->dateFormat($request->date_from_search));
        }
        if($request->date_to_search ){
            $allocations = $allocations->whereDate('date', '<=',$this->dateFormat($request->date_to_search));
        }
        $allocations =$allocations->paginate(25);
        return view('backend.fund-allocation.approved',compact('modes','allocations'));
    }

    public function allocation_print($id)
    {
        $fund=FundAllocation::find($id);
        return view('backend.fund-allocation.preview', compact('fund'));
    }
    public function fund_allocation_modal(Request $request){
        $fund=FundAllocation::find($request->id);
        return view('backend.fund-allocation.preview2', compact('fund'));
    }
}

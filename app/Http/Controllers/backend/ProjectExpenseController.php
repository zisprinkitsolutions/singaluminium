<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\JobProject;
use App\Journal;
use App\JournalRecordsTemp;
use App\JournalTemp;
use App\Models\AccountHead;
use App\Models\CostCenter;
use App\PartyInfo;
use App\Payment;
use App\PaymentInvoice;
use App\PayMode;
use App\PayTerm;
use App\ProjectDetail;
use App\PurchaseExpense;
use App\PurchaseExpenseItem;
use App\Receipt;
use App\ReceiptSale;
use App\Sale;
use App\SaleItem;
use App\TxnType;
use App\VatRate;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\JournalRecord;
use App\Models\InvoiceNumber;
use App\PurchaseExpenseItemTemp;
use App\PurchaseExpenseTemp;
use App\BillDistribute;
use App\AccountSubHead;
use App\Models\MasterAccount;
use App\TempPurchaseExpenseDocument;
use App\PurchaseExpenseDocument;
use App\NewProject;
use App\TempPE;
use App\TempProjectExpense;
use App\ProjectExpense;
use App\Unit;
use App\Stock;
use App\BillOfQuantityTask;
use DB;
use Illuminate\Support\Facades\Storage;


class purchaseExpenseController extends Controller
{

    private function dateFormat($date)
    {
        $old_date = explode('/', $date);

        $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }

    private function journal_no()
    {
        $sub_invoice = Carbon::now()->format('Ymd');
        // return $sub_invoice;
        $latest_journal_no = Journal::withTrashed()->whereDate('created_at', Carbon::today())->where('journal_no', 'LIKE', "%{$sub_invoice}%")->orderBy('id','DESC')->first();
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
    private function purchase_expense_no()
    {
        $sub_invoice = Carbon::now()->format('y');
        // return $sub_invoice;
        $let_purch_exp = InvoiceNumber::where('purchase_no', 'LIKE', "%{$sub_invoice}%")->first();
        if ($let_purch_exp) {
            $purch_no = preg_replace('/^P/', '', $let_purch_exp->purchase_no);
            $purch_code = $purch_no + 1;
            $purch_no="P" . $purch_code;
        } else {
            $purch_no = "P" .Carbon::now()->format('y') . '0001';
        }
        return $purch_no;
    }

    private function temp_purchase_expense_no()
    {
        $sub_invoice = 'P'.Carbon::now()->format('y');
        // return $sub_invoice;
        $let_purch_exp = InvoiceNumber::where('purchase_no', 'LIKE', "%{$sub_invoice}%")->first();
        if ($let_purch_exp) {
            $purch_no = preg_replace('/^'.$sub_invoice.'/', '', $let_purch_exp->purchase_no);
            $purch_code = $purch_no + 1;
            if($purch_code<10)
            {
                $purch_no=$sub_invoice.'000'.$purch_code;
            }
            elseif($purch_code<100)
            {
                $purch_no=$sub_invoice.'00'.$purch_code;
            }
            elseif($purch_code<1000)
            {
                $purch_no=$sub_invoice.'0'.$purch_code;
            }
            else
            {
                $purch_no=$sub_invoice.$purch_code;

            }
        } else {
            $purch_no = $sub_invoice . '0001';
        }
        return $purch_no;
    }

    public function purchase_expense(Request $records)
    {
        $projects = ProjectDetail::all();
        $modes = PayMode::whereNotIn('id',[5])->get();
        $terms = PayTerm::all();
        $sub_invoice = Carbon::now()->format('Ymd');
        $cCenters = CostCenter::all();
        $txnTypes = TxnType::all();
        $pInfos = PartyInfo::where('pi_type','Supplier')->get();
        $vats = VatRate::get();
        $purchase_expense_no = $this->purchase_expense_no();
        $project_lists = NewProject::all();
        $account_heads = AccountHead::where('account_type_id',4)->get();
        $special_heads = AccountHead::where('master_account_id', 3)->get();
        $account_sub_heads = AccountSubHead::whereIn('office_id', [0, Auth::user()->office_id])->get();
        $master_details = MasterAccount::where('account_type_id', 4)->get();
        $special_master_details = MasterAccount::where('id', 3)->get();
        $units = Unit::all();
        TempPE::whereDate('created_at','<', today())->delete();
        return view('backend.purchase-expense.purchase-expense', compact('project_lists', 'projects', 'purchase_expense_no', 'modes', 'terms', 'cCenters', 'txnTypes',  'vats', 'pInfos', 'account_heads', 'account_sub_heads', 'master_details', 'units', 'special_heads', 'special_master_details'));
    }




    public function expensepost(Request $request)
    {
        // dd($request->all());
        $request->validate(
            [
                'date'              =>  'required',
                'party_info'        => 'required',
                'pay_mode'          => 'required',
            ],
            [
                'date.required'         => 'Date is required',
                'party_info.required'   => 'Party Info is required',
                'pay_mode.required'     => 'Pay Mode is required',
            ]
        );
        //Update date formate
        $update_date_format = $this->dateFormat($request->date);

        //purchase expense entry
        $purch_no = $this->temp_purchase_expense_no();
        $purch_ex = new PurchaseExpenseTemp();
        $purch_ex->date = $update_date_format;
        // $purch_ex->job_project_id = $request->project_id;
        $purch_ex->pay_mode =  $request->pay_mode;
        $purch_ex->purchase_no = $purch_no;
        $purch_ex->invoice_no = $request->invoice_no;
        $purch_ex->project_id = $request->project;
        $purch_ex->invoice_type = $request->invoice_type;
        $purch_ex->head_id = 0;
        $purch_ex->total_amount = $request->total_amount;
        $purch_ex->vat = $request->total_vat;
        $purch_ex->amount = $request->taxable_amount;
        $purch_ex->party_id =  $request->party_info;
        $purch_ex->attention = $request->attention;
        $purch_ex->paid_by = $request->paid_by;
        $purch_ex->narration = $request->narration?$request->narration:'N/A';
        $purch_ex->gst_subtotal = 0;
        $purch_ex->created_by = Auth::id();
        $purch_ex->paid_amount = $request->pay_mode == 'Credit' ?  0 : $purch_ex->total_amount;
        $purch_ex->due_amount = $request->pay_mode == 'Credit' ?  $purch_ex->total_amount : 0;

        if ($request->pay_mode == 'Cheque') {
            $purch_ex->issuing_bank = $request->issuing_bank;
            $purch_ex->bank_branch = $request->bank_branch;
            $purch_ex->cheque_no =  $request->cheque_no;
            $purch_ex->deposit_date = $this->dateFormat($request->deposit_date);
        }
        $purch_ex->authorized = true;
        $purch_ex->authorized_by = Auth::id();
        $purch_ex->save();
        //end purchase expense entry
        $purchase_number = InvoiceNumber::find(1);
        $purchase_number->purchase_no = $purch_ex->purchase_no;
        $purchase_number->save();
        //records entry
        $project_expense = TempPE::where('token', $request->_token)->get();
        foreach($project_expense as $project_e){
            $check_sub_head = substr($project_e->account_head_id,0,3);
            $proj_exp = new TempProjectExpense;
            if($check_sub_head == 'Sub'){
                $proj_exp->sub_head_id = substr($project_e->account_head_id,3);
            }else{
                $proj_exp->account_head_id = $project_e->account_head_id;
            }
            $proj_exp->purchase_expense_id = $purch_ex->id;
            $proj_exp->project_id = $project_e->project_id;
            $proj_exp->task_id = $project_e->task_id;
            $proj_exp->task_item_id = $project_e->task_item_id;
            $proj_exp->amount = $project_e->amount;
            $proj_exp->qty = $project_e->qty;
            $proj_exp->save();
            $project_e->delete();
        }
        if($request->hasFile('voucher_scan')){
            $files = $request->file('voucher_scan');
            foreach($files as $file){
                $document = new TempPurchaseExpenseDocument();
                $ext= $file->getClientOriginalExtension();
                $name = hexdec(uniqid()).time().'.'.$ext;
                $file->storeAs('public/upload/purchase-expense', $name);
                $document->expense_id = $purch_ex->id;
                $document->file_name = $name;
                $document->ext = $ext;
                $document->save();
            }
        }
        $multi_head = $request->input('group-a');

        foreach ($multi_head as $each_head) {
            $check_sub_head = substr($each_head['head_id'],0,3);
            if($each_head['head_id'] || $each_head['multi_acc_head']){
                $purc_exp_itm = new PurchaseExpenseItemTemp();
                if($check_sub_head == 'Sub'){
                    $purc_exp_itm->sub_head_id = substr($each_head['head_id'],3);
                }else{
                    $purc_exp_itm->head_id = $each_head['head_id'];
                }
                $purc_exp_itm->item_description = $each_head['multi_acc_head'];
                $purc_exp_itm->amount = $each_head['amount'];
                $purc_exp_itm->vat = $each_head['vat_amount'];
                $purc_exp_itm->total_amount = $each_head['sub_gross_amount'];
                $purc_exp_itm->qty = $each_head['qty'];
                $purc_exp_itm->unit_id = $each_head['unit_id'];
                $purc_exp_itm->type = $each_head['type'];
                $purc_exp_itm->party_id = $request->party_info;
                $purc_exp_itm->purchase_expense_id = $purch_ex->id;
                $purc_exp_itm->gst_subtotal = 0;
                $purc_exp_itm->save();
            }
        }
        //end records entry
        $purchase_exp = $purch_ex;
        $items=PurchaseExpenseItemTemp::where('purchase_expense_id',$purch_ex->id)->get();

        $new=0;
        return view('backend.purchase-expense.approve-preview', compact('purchase_exp'));
        // return view('backend.purchase-expense.authorize-preview', compact('purchase_exp'));
    }

    public function purchase_expense_list()
    {
        $expenses = PurchaseExpense::orderBy('date', 'desc')->paginate(40);
        $i = 0;
        $parties = PartyInfo::get();
        return view('backend.purchase-expense.list', compact('expenses', 'i', 'parties'));
    }


    public function payment_voucher2_list()
    {
        $payments = Payment::orderBy('id', 'desc')->paginate(40);
        $i = 0;
        return view('backend.purchase-expense.payments', compact('payments', 'i'));
    }


    public function purch_exp_modal(Request $request)
    {
        $purchase_exp = PurchaseExpense::find($request->id);
        return view('backend.purchase-expense.preview', compact('purchase_exp'));
    }
    public function auth_purch_exp_modal(Request $request)
    {
        $purchase_exp = PurchaseExpenseTemp::find($request->id);
        $items=PurchaseExpenseItemTemp::where('purchase_expense_id',$purchase_exp->id)->get();
        return view('backend.purchase-expense.authorize-preview', compact('purchase_exp','items'));
    }

    public function approve_purch_exp_modal(Request $request)
    {
        $purchase_exp = PurchaseExpenseTemp::find($request->id);
        return view('backend.purchase-expense.approve-preview', compact('purchase_exp'));
    }


    public function payment_modal(Request $request)
    {
        $payment = Payment::find($request->id);
        return view('backend.purchase-expense.payment-preview', compact('payment'));
    }

    public function payment_voucher2()
    {
        $expenses = PurchaseExpense::where('due_amount', '>', 0)->get();
        $parties = PartyInfo::where('pi_type','Supplier')->get();
        $i = 0;
        $modes = PayMode::whereNotIn('id', [2,3,5])->get();

        return view('backend.purchase-expense.payment-voucher', compact('expenses', 'i', 'parties', 'modes'));
    }

    public function partyInfoInvoice2(Request $request)
    {
        $info = PartyInfo::where('id', $request->value)->first();
        $expenses = PurchaseExpense::where('due_amount', '>', 0)->where('party_id', $info->id)->get();
        $due_amount = $expenses->sum('due_amount');
        if ($request->ajax()) {
            return Response()->json([
                'page' => view('backend.purchase-expense.receipt-invoice', ['expenses' => $expenses, 'i' => 1])->render(),
                'info' => $info,
                'due_amount' => $due_amount

            ]);
        }
    }

    public function findinvoiceRec(Request $request)
    {
        $purchase = PurchaseExpense::find($request->value);
        return $purchase;
    }

    public function invoice_no_validation(Request $request)
    {
        // return 1;
        // return $request->all();
        if($request->party==null){
            return Response()->json([
                'error' => 'Please Select Party First!',
            ]);
        }
        $check = PurchaseExpense::where('party_id', $request->party)->where('invoice_no', $request->inv)->first();
        if ($check) {
            return Response()->json([
                'warning' => $request->inv . ' Invoice No Already Exist!',
            ]);
        }
        else
        {
            $check = PurchaseExpenseTemp::where('party_id', $request->party)->where('invoice_no', $request->inv)->first();
        if ($check) {
            return Response()->json([
                'warning' => $request->inv . ' Invoice No Already Exist Temporarily!',
            ]);
        }

        }
    }



    public function receipt_post(Request $request)
    {
        // return $request->all();
        $update_date_format = $this->dateFormat($request->date);
        $sub_invoice = Carbon::now()->format('Ymd');
        $let_purch_exp = Payment::whereDate('created_at', Carbon::today())->where('payment_no', 'LIKE', "%{$sub_invoice}%")->latest('id')->first();
        if ($let_purch_exp) {
            $purch_no = $let_purch_exp->payment_no + 1;
        } else {
            $purch_no = Carbon::now()->format('Ymd') . '001';
        }

        if ($request->pay_mode == "Cheque") {
            $deposit_date = $this->dateFormat($request->deposit_date);
            $payment = new Payment();
            $payment->date = $update_date_format;
            $payment->pay_mode =  $request->pay_mode;
            $payment->payment_no = $purch_no;
            $payment->head_id = 0;
            $payment->total_amount = $request->pay_amount;
            $payment->vat = 0;
            $payment->party_id =  $request->party_info;
            $payment->narration = $request->narration;
            $payment->paid_amount = 0;
            $payment->due_amount = 0;
            $payment->issuing_bank = $request->issuing_bank;
            $payment->branch = $request->bank_branch;
            $payment->cheque_no = $request->cheque_no;
            $payment->deposit_date = $deposit_date;
            $payment->status = 'Pending';
            $payment->save();
        } else {
            $multi_head = $request->input('group-a');
            $total_vat = 0;
            $total_amount_withvat = 0;
            $total_amount = 0;
            $cost_center_id = 0;
            if ($request->cost_center_name != null) {
                $cost_center_id = $request->cost_center_name;
            }
            $latest_journal_no = Journal::withTrashed()->whereDate('created_at', Carbon::today())->where('journal_no', 'LIKE', "%{$sub_invoice}%")->latest('id')->first();
            if ($latest_journal_no) {
                $journal_no = substr($latest_journal_no->journal_no, 0, -1);
                $journal_code = $journal_no + 1;
                $journal_no = $journal_code . "J";
            } else {
                $journal_no = Carbon::now()->format('Ymd') . '001' . "J";
            }

            $payment = new Payment();
            $payment->date = $update_date_format;
            $payment->pay_mode =  $request->pay_mode;
            $payment->payment_no = $purch_no;
            $payment->head_id = 0;
            $payment->total_amount = $request->pay_amount;
            $payment->vat = 0;
            $payment->party_id =  $request->party_info;
            $payment->narration = $request->narration;
            $payment->paid_amount = 0;
            $payment->due_amount = 0;
            $payment->status = 'Realised';

            $payment->save();

            $journal = new Journal();
            $journal->project_id        = 1;
            $journal->transection_type        = 'PAYMENT VOUCHER';
            $journal->transaction_type        = 'CREDIT';
            $journal->payment_id        = $payment->id;
            $journal->journal_no        = $journal_no;
            $journal->date              = $update_date_format;
            $journal->pay_mode          = $request->pay_mode;
            $journal->voucher_type          = 'CREDIT';
            $journal->invoice_no        = 0;
            $journal->cost_center_id    = $cost_center_id;
            $journal->party_info_id     = $request->party_info;
            $journal->account_head_id   = 123;
            $journal->amount            = $request->pay_amount;
            $journal->tax_rate          = 0;
            $journal->vat_amount        = 0;
            $journal->total_amount      = $request->pay_amount;
            $journal->narration         = $request->narration;
            $journal->created_by        = Auth::id();
            $journal->authorized_by = Auth::id();
            $journal->approved_by    = Auth::id();
            $journal->save();

            $income_head = AccountHead::find(5);
            $jl_record = new JournalRecord();
            $jl_record->journal_id     = $journal->id;
            $jl_record->project_details_id  = 1;
            $jl_record->cost_center_id      = $cost_center_id;
            $jl_record->party_info_id       = $request->party_info;
            $jl_record->journal_no          = $journal_no;
            $jl_record->account_head_id     = $income_head->id;
            $jl_record->master_account_id   = $income_head->master_account_id;
            $jl_record->account_head        = $income_head->fld_ac_head;
            $jl_record->amount              = $request->pay_amount;
            $jl_record->total_amount        = $request->pay_amount;
            $jl_record->vat_rate_id         = 0;
            $jl_record->transaction_type    = 'DR';
            $jl_record->journal_date        = $update_date_format;
            $jl_record->account_type_id = $income_head->account_type_id;
            $jl_record->is_main_head        = 0;
            $jl_record->save();

            if ($request->pay_mode == 'Cash') {
                $dd = 1;
            } else {
                $dd = 2;
            }
            $pay_head = AccountHead::find($dd);
            $jl_record = new JournalRecord();
            $jl_record->journal_id     = $journal->id;
            $jl_record->project_details_id  = 1;
            $jl_record->cost_center_id      = $cost_center_id;
            $jl_record->party_info_id       = $request->party_info;
            $jl_record->journal_no          = $journal_no;
            $jl_record->account_head_id     = $pay_head->id;
            $jl_record->master_account_id   = $pay_head->master_account_id;
            $jl_record->account_head        = $pay_head->fld_ac_head;
            $jl_record->amount              = $request->pay_amount;
            $jl_record->total_amount        = $request->pay_amount;
            $jl_record->vat_rate_id         = 0;
            $jl_record->transaction_type    = 'CR';
            $jl_record->journal_date        = $update_date_format;
            $jl_record->account_type_id = $pay_head->account_type_id;
            $jl_record->is_main_head        = 0;
            $jl_record->save();
            $type = '';
            $all_invoicess = '';
        }

        $purchase = PurchaseExpense::where('due_amount', '>', 0)->where('party_id', $request->party_info)->orderBy('date', 'asc')->first();
        $advance = 0;
        if ($purchase) {
            $pay_amount = $request->pay_amount;
            while ($pay_amount > 0) {
                if ($pay_amount < $purchase->due_amount) {
                    $amount = $pay_amount;
                    $purchase->due_amount = $purchase->due_amount - $pay_amount;
                    $purchase->paid_amount = $purchase->paid_amount + $pay_amount;
                    $pay_amount = 0;
                } else {
                    $amount = $purchase->due_amount;
                    $purchase->paid_amount = $purchase->paid_amount + $purchase->due_amount;
                    $pay_amount = $pay_amount - $purchase->due_amount;
                    $purchase->due_amount = 0;
                }
                $purchase->save();
                $purc_exp_itm = new PaymentInvoice();
                $purc_exp_itm->sale_id = $purchase->id;
                $purc_exp_itm->payment_id = $payment->id;
                $purc_exp_itm->Total_amount = $amount;
                $purc_exp_itm->vat = 0;
                $purc_exp_itm->amount = $amount;
                $purc_exp_itm->party_id = $request->party_info;
                $purc_exp_itm->save();
                $purchase = PurchaseExpense::where('due_amount', '>', 0)->where('party_id', $request->party_info)->orderBy('date', 'asc')->first();
                if (!$purchase) {
                    $advance = $pay_amount;
                    $pay_amount = 0;
                }
            }
        }

        $payment->due_amount = PurchaseExpense::where('due_amount', '>', 0)->where('party_id', $request->party_info)->sum('due_amount');
        $payment->advance = $advance;
        $payment->save();

        return view('backend.purchase-expense.payment-preview', compact('payment'));
    }


    public function receipt_voucher2()
    {
        $sales = Sale::where('due_amount', '>', 0)->get();
        $parties = PartyInfo::get();
        $i = 0;
        $modes = PayMode::whereIn('id', [1, 3])->get();

        return view('backend.sale.receipt-voucher', compact('sales', 'i', 'parties', 'modes'));
    }




    public function findsaleRec(Request $request)
    {
        $sale = Sale::find($request->value);
        return $sale;
    }
    public function purchase_expense_invoice()
    {
        $parties = PartyInfo::get();
        $invoicess = PurchaseExpenseItem::leftjoin('purchase_expenses', 'purchase_expense_items.purchase_expense_id', '=', 'purchase_expenses.id')
            ->orderBy('purchase_expenses.date', 'DESC')
            ->select('purchase_expense_items.*')
            ->paginate(30);
        return view('backend.purchase-expense.invoice', compact('invoicess', 'parties'));
    }

    public function find_invoice(Request $request)
    {
        if ($request->id != null && $request->invoice_no != null) {
            $invoicess = PurchaseExpenseItem::leftjoin('purchase_expenses', 'purchase_expense_items.purchase_expense_id', '=', 'purchase_expenses.id')
                ->where('purchase_expense_items.invoice_no', 'like', "%{$request->invoice_no}%")
                ->where('purchase_expenses.party_id', $request->id)
                ->orderBy('purchase_expenses.date', 'DESC')
                ->select('purchase_expense_items.*')
                ->get();
        } elseif ($request->id != null) {
            $invoicess = PurchaseExpenseItem::leftjoin('purchase_expenses', 'purchase_expense_items.purchase_expense_id', '=', 'purchase_expenses.id')
                ->where('purchase_expenses.party_id', $request->id)
                ->orderBy('purchase_expenses.date', 'DESC')
                ->select('purchase_expense_items.*')
                ->get();
        } elseif ($request->invoice_no != null) {
            $invoicess = PurchaseExpenseItem::leftjoin('purchase_expenses', 'purchase_expense_items.purchase_expense_id', '=', 'purchase_expenses.id')
                ->where('purchase_expense_items.invoice_no', 'like', "%{$request->invoice_no}%")
                ->orderBy('purchase_expenses.date', 'DESC')
                ->select('purchase_expense_items.*')
                ->get();
        } else {
            $invoicess = PurchaseExpenseItem::leftjoin('purchase_expenses', 'purchase_expense_items.purchase_expense_id', '=', 'purchase_expenses.id')
                ->orderBy('purchase_expenses.date', 'DESC')
                ->select('purchase_expense_items.*')
                ->get();
        }
        return view('backend.purchase-expense.ajax-invoice', compact('invoicess'));
    }

    public function find_invoice_date(Request $request)
    {
        $date = $this->dateFormat($request->date);

        $invoicess = PurchaseExpenseItem::leftjoin('purchase_expenses', 'purchase_expense_items.purchase_expense_id', '=', 'purchase_expenses.id')
            ->where('purchase_expenses.date', $date)
            ->orderBy('purchase_expenses.date', 'DESC')
            ->select('purchase_expense_items.*')
            ->get();
        return view('backend.purchase-expense.ajax-invoice', compact('invoicess'));
    }

    public function search_purch(Request $request)
    {
        $expenses = PurchaseExpense::where('purchase_no', 'like', "%{$request->value}%")->orWhere('invoice_no', 'like', "%{$request->value}%")->orderBy('date', 'desc')->get();
        if ($request->party != '') {
            $expenses = $expenses->where('party_id', $request->party);
        }
        if ($request->date != '') {
            $date = $this->dateFormat($request->date);
            $expenses = $expenses->where('date', $date);
        }
        return view('backend.purchase-expense.search-purch', compact('expenses'));
    }

    public function purchase_authorize()
    {
        $parties = PartyInfo::get();
        $i = 0;
        $expenses = PurchaseExpenseTemp::where('authorized', false)->orderBy('id', 'DESC')->get();
        return view('backend.purchase-expense.authorize', compact('expenses', 'parties', 'i'));
    }

    public function purchase_authorization($id)
    {
        $purch = PurchaseExpenseTemp::find($id);
        $purch->authorized = true;
        $purch->authorized_by = Auth::id();
        $purch->save();
        $notification = array(
            'message'       => 'Authorized Successfully!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function purchase_approve()
    {
        $parties = PartyInfo::get();
        $i = 0;
        $expenses = PurchaseExpenseTemp::where('authorized', true)->orderBy('id', 'DESC')->get();
        return view('backend.purchase-expense.approve', compact('expenses', 'parties', 'i'));
    }


    public function purchase_approval($id)
    {
        $purch = PurchaseExpenseTemp::find($id);
        $purch_ex = new PurchaseExpense();
        $purch_ex->date = $purch->date;
        $purch_ex->job_project_id = $purch->job_project_id;
        $purch_ex->pay_mode =  $purch->pay_mode;
        $purch_ex->purchase_no =$purch->purchase_no;
        $purch_ex->invoice_no = $purch->invoice_no;
        $purch_ex->project_id = $purch->project_id;
        $purch_ex->invoice_type = $purch->invoice_type;
        $purch_ex->head_id = $purch->head_id;
        $purch_ex->total_amount = $purch->total_amount;
        $purch_ex->vat = $purch->vat;
        $purch_ex->amount = $purch->amount;
        $purch_ex->party_id =  $purch->party_id;
        $purch_ex->narration = $purch->narration;
        $purch_ex->gst_subtotal = $purch->gst_subtotal;
        $purch_ex->created_by = $purch->created_by;
        $purch_ex->paid_amount = $purch->paid_amount;
        $purch_ex->due_amount = $purch->due_amount;
        $purch_ex->authorized_by = $purch->authorized_by;
        $purch_ex->voucher_scan =  $purch->voucher_scan;
        $purch_ex->voucher_scan2 = $purch->voucher_scan2;
        $purch_ex->attention = $purch->attention;
        $purch_ex->paid_by = $purch->paid_by;
        $purch_ex->approved_by    = Auth::id();
        $purch_ex->save();

        foreach($purch->documents as $file){
            $document = new PurchaseExpenseDocument();
            $document->expense_id = $purch_ex->id;
            $document->file_name = $file->file_name;
            $document->ext = $file->ext;
            $document->save();
        }


        $journal_no = $this->journal_no();
        $journal = new Journal();
        $journal->project_id        = $purch_ex->project_id;
        $journal->purchase_expense_id        = $purch_ex->id;
        $journal->transection_type = 'Purchase/Expense Entry';
        $journal->transaction_type = 'Increase';
        $journal->journal_no        = $journal_no;
        $journal->date              = $purch_ex->date;
        $journal->pay_mode          = $purch_ex->pay_mode;
        $journal->cost_center_id    = 0;
        $journal->party_info_id     = $purch_ex->party_id;
        $journal->account_head_id   = 123;
        $journal->voucher_type      = 'CREDIT';
        $journal->amount            = $purch_ex->total_amount;
        $journal->tax_rate          = 0;
        $journal->vat_amount        = $purch_ex->vat;
        $journal->total_amount      = $purch_ex->amount;
        $journal->gst_subtotal = 0;
        $journal->narration         =  $purch_ex->narration;
        $journal->approved_by = $purch_ex->approved_by;
        $journal->authorized_by         = $purch_ex->authorized_by;
        $journal->created_by = $purch_ex->created_by;
        $journal->voucher_scan  = $purch_ex->voucher_scan;
        $journal->voucher_scan2  = $purch_ex->voucher_scan2;
        if($purch->pay_mode == 'Cheque'){
            $purch_ex->issuing_bank = $purch->issuing_bank;
            $purch_ex->bank_branch = $purch->bank_branch;
            $purch_ex->cheque_no =  $purch->cheque_no;
            $purch_ex->deposit_date = $purch->deposit_date;
        }
        $journal->save();

        $purchase_expense_acount = 0;
        foreach ($purch->items as $item) {
            $purc_exp_itm = new PurchaseExpenseItem();
            $purc_exp_itm->head_id = $item->head_id;
            $purc_exp_itm->sub_head_id = $item->sub_head_id;
            $purc_exp_itm->item_description = $item->item_description;
            $purc_exp_itm->qty = $item->qty;
            $purc_exp_itm->unit_id = $item->unit_id;
            $purc_exp_itm->rate = $item->rate;
            $purc_exp_itm->type = $item->type;
            $purc_exp_itm->amount = $item->amount;
            $purc_exp_itm->vat = $item->vat;
            $purc_exp_itm->total_amount = $item->total_amount;
            $purc_exp_itm->party_id = $item->party_id;
            $purc_exp_itm->purchase_expense_id = $purch_ex->id;
            $purc_exp_itm->gst_subtotal = $item->gst_subtotal;
            $purc_exp_itm->task_id = $item->task_id;
            $purc_exp_itm->save();

            $sub_head = AccountSubHead::find($purc_exp_itm->sub_head_id);
            if($sub_head){
                $ac_head = AccountHead::find($sub_head->account_head_id);
            }else{
                $ac_head = AccountHead::find($purc_exp_itm->head_id);
            }
            // dd($sub_head);
            if($ac_head && $purc_exp_itm->type == 'Raw Material'){
                $jl_record = new JournalRecord();
                $jl_record->journal_id          = $journal->id;
                $jl_record->project_details_id  = $journal->project_id;
                $jl_record->cost_center_id      = $journal->cost_center_id;
                $jl_record->party_info_id       = $journal->party_info_id;
                $jl_record->journal_no          = $journal->journal_no;
                $jl_record->sub_account_head_id = $sub_head?$sub_head->id:null;
                $jl_record->account_head_id     = $ac_head->id;
                $jl_record->master_account_id   = $ac_head->master_account_id;
                $jl_record->account_head        = $ac_head->fld_ac_head;
                $jl_record->amount              = $purc_exp_itm->amount;
                $jl_record->total_amount        = $purc_exp_itm->amount;
                $jl_record->vat_rate_id         = 0;
                $jl_record->invoice_no          = 0;
                $jl_record->transaction_type    = 'DR';
                $jl_record->journal_date        = $journal->date;
                $jl_record->is_main_head        = 1;
                $jl_record->account_type_id     = $ac_head->account_type_id;
                $jl_record->save();
            }else{
                $purchase_expense_acount += $purc_exp_itm->amount;
            }
            if($purc_exp_itm->type == 'Raw Material'){
                $item_stock = Stock::where('account_head_id', $purc_exp_itm->head_id)->where('sub_account_head_id', $purc_exp_itm->sub_head_id)->first();
                if(!$item_stock){
                    $item_stock = Stock::where('account_head_id', $purc_exp_itm->head_id)->first();
                }
                if(!$item_stock){
                    $item_stock = new Stock;
                }
                $item_stock->account_head_id = $ac_head->id;
                $item_stock->sub_account_head_id = $purc_exp_itm->sub_head_id;
                $item_stock->amount_in += $purc_exp_itm->amount;
                $item_stock->stock_in += $purc_exp_itm->qty;
                $item_stock->save();
            }
        }
        $bill_distribute = BillDistribute::where('bill_id', $purch->id)->get();
        foreach($bill_distribute as $bill_dist){
            $bill_dist->bill_id = $purch_ex->id;
            $bill_dist->save();
        }
        if($purchase_expense_acount>0){
            $ac_head = AccountHead::find(28);
            $jl_record = new JournalRecord();
            $jl_record->journal_id     = $journal->id;
            $jl_record->project_details_id  = $journal->project_id;
            $jl_record->cost_center_id      = $journal->cost_center_id;
            $jl_record->party_info_id       =  $journal->party_info_id;
            $jl_record->journal_no          =  $journal->journal_no;
            $jl_record->account_head_id     = $ac_head->id;
            $jl_record->master_account_id   = $ac_head->master_account_id;
            $jl_record->account_head        = $ac_head->fld_ac_head;
            $jl_record->amount              = $purchase_expense_acount;
            $jl_record->total_amount        = $purchase_expense_acount;
            $jl_record->vat_rate_id         = 0;
            $jl_record->invoice_no        = 0;
            $jl_record->transaction_type    = 'DR';
            $jl_record->journal_date        =  $journal->date;
            $jl_record->is_main_head        = 1;
            $jl_record->account_type_id = $ac_head->account_type_id;
            $jl_record->save();
        }
        //vat journal
        if ($purch_ex->vat > 0) {
            $vat_ac_head = AccountHead::find(18); // vat account head
            $jl_record = new JournalRecord();
            $jl_record->journal_id     = $journal->id;
            $jl_record->project_details_id  = $journal->project_id;
            $jl_record->cost_center_id      = $journal->cost_center_id;
            $jl_record->party_info_id       = $journal->party_info_id;
            $jl_record->journal_no          =  $journal->journal_no;
            $jl_record->account_head_id     = $vat_ac_head->id;
            $jl_record->master_account_id   = $vat_ac_head->master_account_id;
            $jl_record->account_head        = $vat_ac_head->fld_ac_head;
            $jl_record->amount              = $purch_ex->vat;
            $jl_record->invoice_no              = 'N/A';
            $jl_record->total_amount        = 0;
            $jl_record->vat_rate_id         = 0;
            $jl_record->transaction_type    = 'DR';
            $jl_record->journal_date        = $journal->date;
            $jl_record->account_type_id = $vat_ac_head->account_type_id;
            $jl_record->is_main_head        = 0;
            $jl_record->save();
        }
        //Paymode journal
        if($purch_ex->due_amount>0){
            $ac_head = AccountHead::find(5); // accounts payable
            $jl_record = new JournalRecord();
            $jl_record->journal_id     = $journal->id;
            $jl_record->project_details_id  = $journal->project_id;
            $jl_record->cost_center_id      = $journal->cost_center_id;
            $jl_record->party_info_id       = $journal->party_info_id;
            $jl_record->journal_no          =  $journal->journal_no;
            $jl_record->account_head_id     = $ac_head->id;
            $jl_record->master_account_id   = $ac_head->master_account_id;
            $jl_record->account_head        = $ac_head->fld_ac_head;
            $jl_record->amount              = $purch_ex->due_amount;
            $jl_record->total_amount        = $purch_ex->due_amount;
            $jl_record->vat_rate_id         = 0;
            $jl_record->transaction_type    = 'CR';
            $jl_record->journal_date        = $journal->date;
            $jl_record->invoice_no              = 'N/A';
            $jl_record->account_type_id = $ac_head->account_type_id;
            $jl_record->is_main_head        = 0;
            $jl_record->save();
        }
        //end paymode journal
        if ($purch_ex->pay_mode == 'Cash' || $purch_ex->pay_mode == 'Petty Cash' || $purch_ex->pay_mode == 'Card' || $purch_ex->pay_mode == 'Bank' || $purch_ex->pay_mode == 'Cheque') {
            $sub_invoice = 'PV'.Carbon::now()->format('y');
            $let_purch_exp = InvoiceNumber::where('payment_no', 'LIKE', "%{$sub_invoice}%")->first();
            if ($let_purch_exp) {
                $purch_code =preg_replace('/^'.$sub_invoice.'/', '', $let_purch_exp->payment_no);
                $purch_code = $purch_code + 1;
                if($purch_code<10)
                {
                    $payment_no=$sub_invoice.'000'.$purch_code;
                }
                elseif($purch_code<100)
                {
                    $payment_no=$sub_invoice.'00'.$purch_code;
                }
                elseif($purch_code<1000)
                {
                    $payment_no=$sub_invoice.'0'.$purch_code;
                }
                else
                {
                    $payment_no=$sub_invoice.$purch_code;

                }
            } else {
                $payment_no = $sub_invoice . '0001';
            }
            $payment = new Payment();
            $payment->date = $purch_ex->date;
            $payment->pay_mode =  $purch_ex->pay_mode;
            $payment->payment_no = $payment_no;
            $payment->head_id = 0;
            $payment->total_amount = $purch_ex->total_amount;
            $payment->vat = 0;
            $payment->party_id = $purch_ex->party_id;
            $payment->narration = $purch_ex->narration??'n/a';
            $payment->paid_amount = 0;
            $payment->due_amount = 0;
            if($purch_ex->pay_mode == 'Cheque'){
                $payment->issuing_bank = $purch_ex->issuing_bank;
                $payment->branch = $purch_ex->bank_branch;
                $payment->deposit_date = $purch_ex->deposit_date;
                $payment->cheque_no = $purch_ex->cheque_no;
                $payment->status = 'Pending';
            }else{
                $payment->status = 'Realised';
            }
            $payment->save();

            $payment_invoice = InvoiceNumber::first();
            $payment_invoice->payment_no = $payment->payment_no;
            $payment_invoice->save();

            $purc_exp_itm = new PaymentInvoice();
            $purc_exp_itm->sale_id = $purch_ex->id;
            $purc_exp_itm->payment_id = $payment->id;
            $purc_exp_itm->total_amount = $payment->total_amount;
            $purc_exp_itm->vat = 0;
            $purc_exp_itm->amount = $payment->total_amount;
            $purc_exp_itm->party_id = $payment->party_id;
            $purc_exp_itm->save();
            if($purch_ex->pay_mode != 'Cheque'){
                if($purch_ex->pay_mode == 'Petty Cash'){
                    $pay_head = 93;
                }elseif($purch_ex->pay_mode == 'Card' || $purch_ex->pay_mode == 'Bank'){
                    $pay_head = 2;
                }elseif($purch_ex->pay_mode == 'VISA Card'){
                    $pay_head = 153;
                }else{
                    $pay_head = 1;
                }
                $ac_head = AccountHead::find($pay_head);
                $jl_record = new JournalRecord();
                $jl_record->journal_id          = $journal->id;
                $jl_record->project_details_id  = $journal->project_id;
                $jl_record->cost_center_id      = $journal->cost_center_id;
                $jl_record->party_info_id       = $journal->party_info_id;
                $jl_record->journal_no          = $journal->journal_no;
                $jl_record->account_head_id     = $ac_head->id;
                $jl_record->master_account_id   = $ac_head->master_account_id;
                $jl_record->account_head        = $ac_head->fld_ac_head;
                $jl_record->amount              = $payment->total_amount;
                $jl_record->total_amount        = $payment->total_amount;
                $jl_record->vat_rate_id         = 0;
                $jl_record->transaction_type    = 'CR';
                $jl_record->journal_date        = $journal->date;
                $jl_record->invoice_no          = 'N/A';
                $jl_record->account_type_id     = $ac_head->account_type_id;

                $jl_record->is_main_head        = 0;
                $jl_record->save();
            } else {
                $ac_head = AccountHead::find(5); // accounts payable
                $jl_record = new JournalRecord();
                $jl_record->journal_id          = $journal->id;
                $jl_record->project_details_id  = $journal->project_id;
                $jl_record->cost_center_id      = $journal->cost_center_id;
                $jl_record->party_info_id       = $journal->party_info_id;
                $jl_record->journal_no          =  $journal->journal_no;
                $jl_record->account_head_id     = $ac_head->id;
                $jl_record->master_account_id   = $ac_head->master_account_id;
                $jl_record->account_head        = $ac_head->fld_ac_head;
                $jl_record->amount              = $payment->total_amount;
                $jl_record->total_amount        = $payment->total_amount;
                $jl_record->vat_rate_id         = 0;
                $jl_record->transaction_type    = 'CR';
                $jl_record->journal_date        = $journal->date;
                $jl_record->invoice_no              = 'N/A';
                $jl_record->account_type_id = $ac_head->account_type_id;
                $jl_record->is_main_head        = 0;
                $jl_record->save();
            }

        }

        $project_expense = TempProjectExpense::where('purchase_expense_id', $purch->id)->get();
        foreach($project_expense as $project_e){
            $proj_exp = new ProjectExpense;
            if($project_e->sub_head_id){
                $sub_head = AccountSubHead::find($project_e->sub_head_id);
                $ac_head = AccountHead::find($sub_head->account_head_id);
                $proj_exp->sub_head_id = $project_e->sub_head_id;
                $proj_exp->account_head_id = $ac_head->id;
            }else{
                $proj_exp->sub_head_id = $project_e->sub_head_id;
                $proj_exp->account_head_id = $project_e->account_head_id;
            }
            $proj_exp->purchase_expense_id = $purch_ex->id;
            $proj_exp->project_id = $project_e->project_id;
            $proj_exp->task_id = $project_e->task_id;
            $proj_exp->task_item_id = $project_e->task_item_id;
            $proj_exp->amount = $project_e->amount;
            if($purch_ex->pay_mode == 'Credit'){
                $proj_exp->paid_amount = $project_e->amount;
            }else{
                $proj_exp->due_amount = $project_e->amount;
            }
            $proj_exp->qty = $project_e->qty;
            $proj_exp->save();
            $project_e->delete();
            $bill_of_qty = BillOfQuantityTask::find($proj_exp->task_id);
            if($bill_of_qty){
                $bill_of_qty->expense += $proj_exp->amount;
                if($purch_ex->pay_mode == 'Credit'){
                    $bill_of_qty->payable += $proj_exp->amount;
                }else{
                    $bill_of_qty->payment += $proj_exp->amount;
                }
                $bill_of_qty->save();
            }
            $item_stock = Stock::where('account_head_id', $proj_exp->account_head_id)->where('sub_account_head_id', $proj_exp->sub_head_id)->first();
            if(!$item_stock){
                $item_stock = Stock::where('account_head_id', $proj_exp->account_head_id)->first();
            }
            if($item_stock){
                $item_stock->amount_out += $proj_exp->amount;
                $item_stock->stock_out += $proj_exp->qty;
                $item_stock->save();
            }
            $exit_purchasse_item = PurchaseExpenseItem::where('purchase_expense_id', $purch_ex->id)->where('head_id', $proj_exp->account_head_id)->first();
            $exit_purchasse_item->out_qty += $proj_exp->qty;
            $exit_purchasse_item->out_amount += $proj_exp->amount;
            $exit_purchasse_item->save();
        }
        $purch->items->each->delete();
        $purch->delete();

        // project expense
        if($purch_ex->project_expense->sum('amount')>0){
            $project_expense_amount = 0;
            $journal_no = $this->journal_no();
            $journal = new Journal();
            $journal->project_id        = $purch_ex->project_id;
            $journal->purchase_expense_id = $purch_ex->id;
            $journal->transection_type  = 'Project Expense Entry';
            $journal->transaction_type  = 'Increase';
            $journal->journal_no        = $journal_no;
            $journal->date              = $purch_ex->date;
            $journal->pay_mode          = $purch_ex->pay_mode;
            $journal->cost_center_id    = 0;
            $journal->party_info_id     = $purch_ex->party_id;
            $journal->account_head_id   = 123;
            $journal->voucher_type      = 'CREDIT';
            $journal->amount            = $purch_ex->project_expense->sum('amount');
            $journal->tax_rate          = 0;
            $journal->vat_amount        = 0;
            $journal->total_amount      = $purch_ex->project_expense->sum('amount');
            $journal->gst_subtotal      = 0;
            $journal->narration         = $purch_ex->narration;
            $journal->approved_by       = $purch_ex->approved_by;
            $journal->authorized_by     = $purch_ex->authorized_by;
            $journal->created_by        = $purch_ex->created_by;
            $journal->save();
            foreach($purch_ex->project_expense as $pro_expense){
                $project_expense_head = AccountHead::find($pro_expense->account_head_id);
                $jl_record = new JournalRecord();
                $jl_record->journal_id          = $journal->id;
                $jl_record->project_details_id  = $journal->project_id;
                $jl_record->cost_center_id      = $journal->cost_center_id;
                $jl_record->party_info_id       = $journal->party_info_id;
                $jl_record->journal_no          = $journal->journal_no;
                $jl_record->sub_account_head_id = $pro_expense->sub_head_id;
                $jl_record->account_head_id     = $project_expense_head->id;
                $jl_record->master_account_id   = $project_expense_head->master_account_id;
                $jl_record->account_head        = $project_expense_head->fld_ac_head;
                $jl_record->amount              = $pro_expense->amount;
                $jl_record->total_amount        = $pro_expense->amount;
                $jl_record->vat_rate_id         = 0;
                $jl_record->invoice_no          = 0;
                $jl_record->transaction_type    = 'CR';
                $jl_record->journal_date        = $journal->date;
                $jl_record->is_main_head        = 1;
                $jl_record->account_type_id     = $project_expense_head->account_type_id;
                $jl_record->project_id          = $pro_expense->project_id;
                $jl_record->save();
                
                $ac_head = AccountHead::find(34);
                $jl_record = new JournalRecord();
                $jl_record->journal_id          = $journal->id;
                $jl_record->project_details_id  = $journal->project_id;
                $jl_record->cost_center_id      = $journal->cost_center_id;
                $jl_record->party_info_id       = $journal->party_info_id;
                $jl_record->journal_no          = $journal->journal_no;
                $jl_record->sub_account_head_id = $project_expense_head->sub_head_id;
                $jl_record->account_head_id     = $ac_head->id;
                $jl_record->master_account_id   = $ac_head->master_account_id;
                $jl_record->account_head        = $ac_head->fld_ac_head;
                $jl_record->amount              = $pro_expense->amount;
                $jl_record->total_amount        = $pro_expense->amount;
                $jl_record->vat_rate_id         = 0;
                $jl_record->invoice_no          = 0;
                $jl_record->transaction_type    = 'DR';
                $jl_record->journal_date        = $journal->date;
                $jl_record->is_main_head        = 1;
                $jl_record->account_type_id     = $ac_head->account_type_id;
                $jl_record->project_id          = $pro_expense->project_id;
                $jl_record->save();
            }
        }
        $notification = array(
            'message'       => 'Approve Successfully!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function payment_declined($id)
    {
        $payment=Payment::find($id);
        $payment->status="Declined";
        $payment->save();
        $notification = array(
            'message'       => 'Declined!',
            'alert-type'    => 'warning'
        );
        return redirect()->back()->with($notification);
    }

    public function payment_realised($id)
    {
        $payment=Payment::find($id);
        if($payment->status=='Realised')
        {
            $notification = array(
                'message'       => 'Already Realised!',
                'alert-type'    => 'warning'
            );
            return redirect()->back()->with($notification);
        }
        $payment->status="Realised";
        $payment->save();
        $journal_no = $this->journal_no();
        $journal = new Journal();
        $journal->project_id        = 1;
        $journal->transection_type        = 'PAYMENT VOUCHER';
        $journal->transaction_type        = 'CREDIT';
        $journal->payment_id        = $payment->id;
        $journal->journal_no        = $journal_no;
        $journal->date              = $payment->date;
        $journal->pay_mode          = 'Cash';
        $journal->voucher_type          = 'Payment Voucher';
        $journal->invoice_no        = 0;
        $journal->cost_center_id    = 0;
        $journal->party_info_id     = $payment->party_id;
        $journal->account_head_id   = 123;
        $journal->amount            = $payment->total_amount;
        $journal->tax_rate          = 0;
        $journal->vat_amount        = 0;
        $journal->total_amount      = $payment->total_amount;
        $journal->narration         = $payment->narration;
        $journal->created_by        = Auth::id();
        $journal->authorized_by = Auth::id();
        $journal->approved_by    = Auth::id();
        $journal->save();

        $payment_head = AccountHead::find(5);
        $jl_record = new JournalRecord();
        $jl_record->journal_id     = $journal->id;
        $jl_record->project_details_id  = $journal->project_id;
        $jl_record->cost_center_id      = $journal->cost_center_id ;
        $jl_record->party_info_id       = $journal->party_info_id  ;
        $jl_record->journal_no          = $journal_no;
        $jl_record->account_head_id     = $payment_head->id;
        $jl_record->master_account_id   = $payment_head->master_account_id;
        $jl_record->account_head        = $payment_head->fld_ac_head;
        $jl_record->amount              = $journal->amount;
        $jl_record->total_amount        = $journal->amount;
        $jl_record->vat_rate_id         = 0;
        $jl_record->transaction_type    = 'DR';
        $jl_record->journal_date        = $journal->date;
        $jl_record->account_type_id = $payment_head->account_type_id;
        $jl_record->is_main_head        = 0;
        $jl_record->save();

        $pay_head = AccountHead::find(2);
        $jl_record = new JournalRecord();
        $jl_record->journal_id     = $journal->id;
        $jl_record->project_details_id  = $journal->project_id;
        $jl_record->cost_center_id      = $journal->cost_center_id ;
        $jl_record->party_info_id       = $journal->party_info_id  ;
        $jl_record->journal_no          = $journal_no;
        $jl_record->account_head_id     = $pay_head->id;
        $jl_record->master_account_id   = $pay_head->master_account_id;
        $jl_record->account_head        = $pay_head->fld_ac_head;
        $jl_record->amount              = $journal->amount;
        $jl_record->total_amount        = $journal->amount;
        $jl_record->vat_rate_id         = 0;
        $jl_record->transaction_type    = 'CR';
        $jl_record->journal_date        = $journal->date;
        $jl_record->account_type_id = $pay_head->account_type_id;
        $jl_record->is_main_head        = 0;
        $jl_record->save();

        $notification = array(
            'message'       => 'Realised!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);

    }

    public function payment_deposit(Request $request, $id)
    {
        $payment=Payment::find($id);
        // dd($request->all());
        $payment->deposit_date=$this->dateFormat($request->deposit_date);
        $payment->save();
        $notification = array(
            'message'       => 'Success!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function purchase_expense_edit($purchase)
    {
        $projects = ProjectDetail::all();
        $modes = PayMode::whereNotIn('id', [])->get();
        $terms = PayTerm::all();
        $sub_invoice = Carbon::now()->format('Ymd');
        $cCenters = CostCenter::all();
        $txnTypes = TxnType::all();

        $pInfos = PartyInfo::all();
        $parties = PartyInfo::get();
        $vats = VatRate::get();
        $orders = JobProject::latest()->paginate(20);
        $purchase=PurchaseExpenseTemp::find($purchase);
        $invoices = JobProject::latest()->paginate(20);
        // dd($purchase);
        $balance = 0;
        if($purchase->pay_mode=='Cash'){
            $cash_cr = JournalRecord::where('account_head_id',1)->whereYear('created_at',date('Y'))->where('transaction_type','CR')->get()->sum('total_amount');
            $cash_dr = JournalRecord::where('account_head_id',1)->whereYear('created_at',date('Y'))->where('transaction_type','DR')->get()->sum('total_amount');
            $balance = $cash_dr - $cash_cr;
        }
        if($purchase->pay_mode=='Card' || $purchase->pay_mode=='Cheque'){
            $bank_cr = JournalRecord::where('account_head_id',2)->whereYear('created_at',date('Y'))->where('transaction_type','CR')->get()->sum('total_amount');
            $bank_dr = JournalRecord::where('account_head_id',2)->whereYear('created_at',date('Y'))->where('transaction_type','DR')->get()->sum('total_amount');
            $balance = $bank_dr - $bank_cr;
        }
        return view('backend.purchase-expense.purchase-expense-edit', compact('purchase','orders', 'parties', 'projects', 'modes', 'terms', 'cCenters', 'txnTypes',  'vats', 'pInfos', 'invoices', 'balance'));
    }


    public function expense_edit_post(Request $request, $id)
    {

        $request->validate(
            [
                'date'              =>  'required',
                'party_info'        => 'required',
                'pay_mode'          => 'required',
                'narration'         => 'required'
            ],
            [
                'date.required'         => 'Date is required',
                'party_info.required'   => 'Party Info is required',
                'pay_mode.required'     => 'Pay Mode is required',
                'narration.required'    => 'Narration is required',
            ]
        );





        //Update date formate
        $update_date_format = $this->dateFormat($request->date);
        //purchase expense entry
        $purch_ex = PurchaseExpenseTemp::find($id);

        $voucher_file_name=$purch_ex->voucher_scan;
        if($request->hasFile('voucher_scan')){
            $voucher_scan= $request->file('voucher_scan');
            $name= $voucher_scan->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext= $voucher_scan->getClientOriginalExtension();
            $voucher_file_name= $name.time().'.'.$ext;

            if (($purch_ex->voucher_scan)) {
                $currentFilePath = 'public/upload/documents/' . $purch_ex->voucher_scan;
                Storage::delete($currentFilePath);
            }

            $voucher_scan->storeAs( 'public/upload/documents', $voucher_file_name);
        }

        $voucher_file_name2=$purch_ex->voucher_scan2;
        if($request->hasFile('voucher_scan2')){
            $voucher_scan2= $request->file('voucher_scan2');
            $name= $voucher_scan2->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext= $voucher_scan2->getClientOriginalExtension();
            $voucher_file_name2= $name.time().'.'.$ext;
            if (($purch_ex->voucher_scan2)) {
                $currentFilePath = 'public/upload/documents2/' . $purch_ex->voucher_scan2;
                Storage::delete($currentFilePath);
            }
            $voucher_scan2->storeAs( 'public/upload/documents2', $voucher_file_name2);
        }


        $purch_ex->date = $update_date_format;
        // $purch_ex->job_project_id = $request->project_id;
        $purch_ex->pay_mode =  $request->pay_mode;
        $purch_ex->invoice_no = $request->invoice_no;
        $purch_ex->project_id = $request->project;
        $purch_ex->invoice_type = $request->invoice_type;
        $purch_ex->head_id = 0;
        $purch_ex->total_amount = $request->total_amount;
        $purch_ex->vat = $request->total_vat;
        $purch_ex->amount = $request->taxable_amount;
        $purch_ex->party_id =  $request->party_info;
        $purch_ex->narration = $request->narration;
        $purch_ex->gst_subtotal = 0;
        if ($request->pay_mode == 'Cheque') {
            $purch_ex->issuing_bank = $request->issuing_bank;
            $purch_ex->bank_branch = $request->bank_branch;
            $purch_ex->cheque_no =  $request->cheque_no;
            $purch_ex->deposit_date = $this->dateFormat($request->deposit_date);
        }
        $purch_ex->edited_by = Auth::id();

        $purch_ex->voucher_scan =  $voucher_file_name;
        $purch_ex->voucher_scan2 = $voucher_file_name2;
        $purch_ex->paid_amount = $request->pay_mode == 'Credit' ?  0 : $purch_ex->total_amount;
        $purch_ex->due_amount = $request->pay_mode == 'Credit' ?  $purch_ex->total_amount : 0;
        $purch_ex->save();
        //end purchase expense entry

        //records entry

        $purch_ex->items->each->delete();
        $multi_head = $request->input('group-a');
        foreach ($multi_head as $each_head) {
            //purchase record
            $purc_exp_itm = new PurchaseExpenseItemTemp();
            $purc_exp_itm->item_description = $each_head['multi_acc_head'];
            $purc_exp_itm->amount = $each_head['amount'];
            $purc_exp_itm->vat = $each_head['vat_amount'];
            $purc_exp_itm->total_amount = $each_head['sub_gross_amount'];
            // $purc_exp_itm->task_id = $each_head['task'];
            $purc_exp_itm->party_id = $request->party_info;
            $purc_exp_itm->purchase_expense_id = $purch_ex->id;
            $purc_exp_itm->gst_subtotal = 0;
            $purc_exp_itm->save();
            //end purchase record
        }
        //end records entry
        // sleep(5);
        BillDistribute::where('bill_id', $purch_ex->id)->delete();
        $project_ids = $request->project_id;
        foreach($project_ids as $key => $id){
            if($request->task[$key] && $request->task_amount[$key]){
                $bill_dist = new BillDistribute;
                $bill_dist->project_id = $id;
                $bill_dist->bill_id = $purch_ex->id;
                $bill_dist->task_id = $request->task[$key];
                $bill_dist->amount = $request->task_amount[$key];
                $bill_dist->save();
            }
        }

        $purchase_exp = $purch_ex;
        $items=PurchaseExpenseItemTemp::where('purchase_expense_id',$purch_ex->id)->get();
        $new=0;
        // return $purchase_exp->items;
        return view('backend.purchase-expense.authorize-preview', compact('purchase_exp','new','items'));
    }

    public function purchase_delete($id)
    {
        $purch=PurchaseExpenseTemp::find($id);
        $purch->items->each->delete();
        BillDistribute::where('bill_id', $purch->id)->delete();
        TempProjectExpense::where('purchase_expense_id', $purch->id)->delete();
        $purch->delete();
        $notification = array(
            'message'       => 'Deleted Successfully!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function payable()
    {
        $suppliers=DB::table('party_infos')
        ->where('party_infos.pi_type','Supplier')
        ->join('purchase_expenses', 'party_infos.id', '=', 'purchase_expenses.party_id')
        ->select('party_infos.id','party_infos.pi_code','party_infos.pi_name',
        DB::raw('SUM(CASE WHEN purchase_expenses.party_id =party_infos.id THEN purchase_expenses.due_amount ELSE 0 END ) as due_amount')
        )
        ->groupBy('party_infos.id','party_infos.pi_code','party_infos.pi_name')
        ->orderByDesc('due_amount')
        ->paginate(40);
        return view('backend.purchase-expense.payable',compact('suppliers'));
    }

    public function search_supplier_due(Request $request)
    {


        $suppliers=DB::table('party_infos')
        ->where('party_infos.pi_type','Supplier')
        ->where('party_infos.id','=',$request->party)
        ->join('purchase_expenses', 'party_infos.id', '=', 'purchase_expenses.party_id')
        ->select('party_infos.id','party_infos.pi_code','party_infos.pi_name',
        DB::raw('SUM(CASE WHEN purchase_expenses.party_id =party_infos.id THEN purchase_expenses.due_amount ELSE 0 END ) as due_amount')
        )
        ->groupBy('party_infos.id','party_infos.pi_code','party_infos.pi_name')
        ->orderByDesc('due_amount')
        ->get();


        return view('backend.purchase-expense.payable-table',compact('suppliers'));

    }

    public function payable_view(Request $request)
    {

        $info = PartyInfo::where('id', $request->id)->first();
        $expenses = PurchaseExpense::where('due_amount', '>', 0)->where('party_id', $info->id)->get();
        $due_amount = $expenses->sum('due_amount');
        return view('backend.purchase-expense.payable-view', compact('expenses','info'));
    }
    public function available_pay_amount(Request $request){
        $balance = 0;
        if($request->pay_mode=='Cash'){
            $cash_cr = JournalRecord::where('account_head_id',1)->whereYear('created_at',date('Y'))->where('transaction_type','CR')->get()->sum('total_amount');
            $cash_dr = JournalRecord::where('account_head_id',1)->whereYear('created_at',date('Y'))->where('transaction_type','DR')->get()->sum('total_amount');
            $balance = $cash_dr - $cash_cr;
        }
        if($request->pay_mode=='Bank'){
            $bank_cr = JournalRecord::where('account_head_id',2)->whereYear('created_at',date('Y'))->where('transaction_type','CR')->get()->sum('total_amount');
            $bank_dr = JournalRecord::where('account_head_id',2)->whereYear('created_at',date('Y'))->where('transaction_type','DR')->get()->sum('total_amount');
            $balance = $bank_dr - $bank_cr;
        }
        if($request->pay_mode=='Card'){
            $bank_cr = JournalRecord::where('account_head_id',3)->whereYear('created_at',date('Y'))->where('transaction_type','CR')->get()->sum('total_amount');
            $bank_dr = JournalRecord::where('account_head_id',3)->whereYear('created_at',date('Y'))->where('transaction_type','DR')->get()->sum('total_amount');
            $balance = $bank_dr - $bank_cr;
        }
        if($request->pay_mode=='Petty Cash'){
            $bank_cr = JournalRecord::where('account_head_id',93)->whereYear('created_at',date('Y'))->where('transaction_type','CR')->get()->sum('total_amount');
            $bank_dr = JournalRecord::where('account_head_id',93)->whereYear('created_at',date('Y'))->where('transaction_type','DR')->get()->sum('total_amount');
            $balance = $bank_dr - $bank_cr;
        }
        return $balance;
    }
    public function available_balance_add(Request $request){
        // dd($request->all());
        $sub_invoice = Carbon::now()->format('Ymd');
        $latest_journal_no = Journal::withTrashed()->whereDate('created_at', Carbon::today())->where('journal_no', 'LIKE', "%{$sub_invoice}%")->latest('id')->first();
        if ($latest_journal_no) {
            $journal_no = substr($latest_journal_no->journal_no, 0, -1);
            $journal_code = $journal_no + 1;
            $journal_no = $journal_code . "J";
        } else {
            $journal_no = Carbon::now()->format('Ymd') . '001' . "J";
        }
        if($request->payment_type=='Borrow' && $request->party_id2){
            $party_info = PartyInfo::find($request->party_id2);
            $party_info->balance += $request->b_amount;
            $party_info->save();
        }
        $journal = new Journal();
        $journal->project_id        = 1;
        $journal->transection_type        = 'RECEIPT VOUCHER';
        $journal->transaction_type        = 'DEBIT';
        $journal->journal_no        = $journal_no;
        $journal->date              = date('Y-m-d');
        $journal->voucher_type      = 'Receipt Voucher';
        $journal->receipt_id        = 0;

        $journal->pay_mode          = $request->pay_mode=="Cash"?"Card":"Cash";
        $journal->invoice_no        = 0;
        $journal->cost_center_id    = 0;
        $journal->party_info_id     = $request->party_id2?$request->party_id2:0;
        $journal->account_head_id   = 123;
        $journal->amount            = $request->b_amount;
        $journal->tax_rate          = 0;
        $journal->vat_amount        = 0;
        $journal->total_amount      = $request->b_amount;
        $journal->narration         = 'Balance Adjust';
        $journal->created_by        = Auth::id();
        $journal->authorized_by     = Auth::id();
        $journal->approved_by       = Auth::id();
        $journal->save();


        $dd = $request->pay_mode == 'Cash'?1:2;

        $pay_head = AccountHead::find($dd);
        $jl_record = new JournalRecord();
        $jl_record->journal_id     = $journal->id;
        $jl_record->project_details_id  = 1;
        $jl_record->cost_center_id      = 0;
        $jl_record->party_info_id       = $journal->party_info_id;
        $jl_record->journal_no          = $journal->journal_no;
        $jl_record->account_head_id     = $pay_head->id;
        $jl_record->master_account_id   = $pay_head->master_account_id;
        $jl_record->account_head        = $pay_head->fld_ac_head;
        $jl_record->amount              = $journal->total_amount;
        $jl_record->total_amount        = $journal->total_amount;
        $jl_record->vat_rate_id         = 0;
        $jl_record->transaction_type    = 'DR';
        $jl_record->journal_date        = $journal->date;
        $jl_record->account_type_id     = $pay_head->account_type_id;
        $jl_record->is_main_head        = 0;
        $jl_record->save();


        if($request->payment_type=='Borrow'){
            $head = 30;
        }else{
            $head = $request->pay_mode == 'Cash'?2:1;
        }
        $income_head = AccountHead::find($head);
        $jl_record = new JournalRecord();
        $jl_record->journal_id          = $journal->id;
        $jl_record->project_details_id  = 1;
        $jl_record->cost_center_id      = 0;
        $jl_record->party_info_id       = $journal->party_info_id;
        $jl_record->journal_no          = $journal_no;
        $jl_record->account_head_id     = $income_head->id;
        $jl_record->master_account_id   = $income_head->master_account_id;
        $jl_record->account_head        = $income_head->fld_ac_head;
        $jl_record->amount              = $journal->total_amount;
        $jl_record->total_amount        = $journal->total_amount;
        $jl_record->vat_rate_id         = 0;
        $jl_record->transaction_type    = 'CR';
        $jl_record->journal_date        = $journal->date;
        $jl_record->account_type_id     = $income_head->account_type_id;
        $jl_record->is_main_head        = 0;
        $jl_record->save();
        return $request->b_amount;
    }
    public function project_expense(Request $request){
        $project_lists = NewProject::all();
        $amount = $request->amount;
        $qty = $request->qty;
        $account_head = $request->head_id;
        $temp_pe = TempPE::where('token', $request->_token)->where('account_head_id', $request->head_id)->get();
        // dd($temp_pe);
        return view('backend.purchase-expense.project-expense', compact('project_lists', 'amount', 'account_head', 'temp_pe', 'qty'));
    }
    public function project_expense_store(Request $request){
        TempPE::where('token', $request->_token)->where('account_head_id', $request->accout_head_id)->forceDelete();
        $project_ids = $request->project_id;
        if($project_ids){
            foreach($project_ids as $key => $id){
                if($id && $request->task_amount[$key]){
                    $pe = new TempPE;
                    $pe->token = $request->_token;
                    $pe->project_id = $id;
                    $pe->amount = $request->task_amount[$key];
                    $pe->qty = $request->task_qty[$key];
                    $pe->task_id = $request->task_id[$key];
                    $pe->task_item_id = $request->task_item_id[$key];
                    $pe->account_head_id = $request->accout_head_id;
                    $pe->save();
                }
            }
        }
    }
    public function check_project_expense(Request $request){
        $token = $request->_token;
        $multi_head = $request->input('group-a');
        return view('backend.purchase-expense.check-project-expense', compact('token', 'multi_head'));
    }
}

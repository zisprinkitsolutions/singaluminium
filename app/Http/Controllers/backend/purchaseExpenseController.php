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
use App\Models\StockTransection;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
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
use App\InventoryExpense;
use App\Unit;
use App\Stock;
use App\BillOfQuantityTask;
use App\JobProjectTask;
use App\TempInventoryExpense;
use App\TempCogsAssign;
use App\Models\StockTransfer;
use App\Models\Payroll\Employee;
use App\TempPaymentVoucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Excel;
use App\ExpenseImport;
use App\Imports\ExpenseExcelImport;
use App\LpoBill;
use App\Requisition;
use App\Subsidiary;
use App\TempSubsidiary;
use App\SubsidiaryStore;
use App\SubsidiaryExpense;
use App\RequisitionItem;
use Illuminate\Contracts\Queue\Job;
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

    private function company_journal($company_id, $record_id, $head_id, $sub_head_id){
        $record_info = JournalRecord::find($record_id);

        $journal_no = $this->journal_no();
        $journal = new Journal();
        $journal->project_id        = 1;
        $journal->transection_type  = 'Purchase/Expense Entry';
        $journal->transaction_type  = 'Increase';
        $journal->journal_no        = $journal_no;
        $journal->date              = $record_info->journal_date;
        $journal->pay_mode          = 'Credit';
        $journal->cost_center_id    = 0;
        $journal->party_info_id     = $record_info->party_info_id;
        $journal->account_head_id   = 123;
        $journal->voucher_type      = 'CREDIT';
        $journal->amount            = $record_info->total_amount;
        $journal->tax_rate          = 0;
        $journal->vat_amount        = 0;
        $journal->total_amount      = $record_info->amount;
        $journal->gst_subtotal      = 0;
        $journal->narration         = 'n/a';
        $journal->approved_by       = Auth::user()->id;
        $journal->authorized_by     = Auth::user()->id;
        $journal->created_by        = Auth::user()->id;
        $journal->save();

        $dr_head = AccountHead::find($head_id);
        $jl_record = new JournalRecord();
        $jl_record->journal_id          = $journal->id;
        $jl_record->project_details_id  = $journal->project_id;
        $jl_record->cost_center_id      = $journal->cost_center_id;
        $jl_record->party_info_id       = $journal->party_info_id;
        $jl_record->journal_no          = $journal->journal_no;
        $jl_record->sub_account_head_id = $sub_head_id;
        $jl_record->account_head_id     = $dr_head->id;
        $jl_record->master_account_id   = $dr_head->master_account_id;
        $jl_record->account_head        = $dr_head->fld_ac_head;
        $jl_record->amount              = $record_info->amount;
        $jl_record->total_amount        = $record_info->total_amount;
        $jl_record->vat_rate_id         = 0;
        $jl_record->invoice_no          = 0;
        $jl_record->transaction_type    = 'DR';
        $jl_record->journal_date        = $journal->date;
        $jl_record->is_main_head        = 1;
        $jl_record->account_type_id     = $dr_head->account_type_id;
        $jl_record->compnay_id          = $company_id;
        $jl_record->save();

        $cr_head = AccountHead::find(1769);
        $jl_record = new JournalRecord();
        $jl_record->journal_id          = $journal->id;
        $jl_record->project_details_id  = $journal->project_id;
        $jl_record->cost_center_id      = $journal->cost_center_id;
        $jl_record->party_info_id       = $journal->party_info_id;
        $jl_record->journal_no          = $journal->journal_no;
        $jl_record->account_head_id     = $cr_head->id;
        $jl_record->master_account_id   = $cr_head->master_account_id;
        $jl_record->account_head        = $cr_head->fld_ac_head;
        $jl_record->amount              = $record_info->amount;
        $jl_record->total_amount        = $record_info->total_amount;
        $jl_record->vat_rate_id         = 0;
        $jl_record->invoice_no          = 0;
        $jl_record->transaction_type    = 'CR';
        $jl_record->journal_date        = $journal->date;
        $jl_record->is_main_head        = 1;
        $jl_record->account_type_id     = $cr_head->account_type_id;
        $jl_record->compnay_id          = $company_id;
        $jl_record->save();
        return $journal;
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

    public function purchase_expense(Request $request)
    {
        $type = $request->type;

        Gate::authorize('Expense');
        $from = $request->form_date ? $this->dateFormat($request->form_date) : ($request->to_date ? $this->dateFormat($request->to_date) : date('Y-m-d'));
        $to = $request->to_date ? $this->dateFormat($request->to_date) :  null;
        $projects = ProjectDetail::all();
        $modes = PayMode::whereNotIn('id',[5])->get();
        $terms = PayTerm::all();
        $sub_invoice = Carbon::now()->format('Ymd');
        $cCenters = CostCenter::all();
        $txnTypes = TxnType::all();
        $pInfos = PartyInfo::where('pi_type','Supplier')->get();
        $vats = VatRate::get();
        $purchase_expense_no = $this->purchase_expense_no();
        $project_lists = JobProject::all();
        $account_heads = AccountHead::whereIn('account_type_id',[1,3])->whereNotIn('master_account_id', [1])->get();
        $special_heads = AccountHead::where('fld_definition', 'Cost of Sales / Goods Sold')->get();
        $account_sub_heads = AccountSubHead::whereIn('office_id', [0, Auth::user()->office_id])->get();
        $master_details = MasterAccount::where('account_type_id', 4)->get();
        $special_master_details = MasterAccount::where('id', 3)->get();
        $units = Unit::all();

        TempPE::whereDate('created_at','<', today())->delete();

        $products = AccountHead::whereHas('stock') // only heads that have stock
        ->with(['sub_heads' => function ($q) {
            $q->whereHas('sub_stock'); // only sub_heads that have stock
        }])
        ->get();

        $expenses = PurchaseExpense::orderBy('date', 'desc');
                            // ->paginate(40);
        $cal_expenses_total_amount = (clone $expenses)->get()->sum('total_amount');
        $cal_expenses_paid_amount = (clone $expenses)->get()->sum('paid_amount');
        $cal_expenses_due_amount = (clone $expenses)->get()->sum('due_amount');
        $expenses = $expenses->paginate(40);

        $temp_expenses = PurchaseExpenseTemp::where('authorized', true)->orderBy('id', 'DESC');
                            // ->paginate(40);
        $cal_temp_expenses_total_amount = (clone $temp_expenses)->get()->sum('total_amount');
        $cal_temp_expenses_paid_amount = (clone $temp_expenses)->get()->sum('paid_amount');
        $cal_temp_expenses_due_amount = (clone $temp_expenses)->get()->sum('due_amount');
        $temp_expenses = $temp_expenses->paginate(40);

        $data = [];
        $data['total_amount'] = $cal_expenses_total_amount + $cal_temp_expenses_total_amount;
        $data['paid_amount'] = $cal_expenses_paid_amount + $cal_temp_expenses_paid_amount;
        $data['due_amount'] = $cal_expenses_due_amount + $cal_temp_expenses_due_amount;

        $lpo = LpoBill::with('items','party')->find($request->lpo);

        if(!$lpo){
            $lpo = null;
        }

        return view('backend.purchase-expense.purchase-expense', compact('project_lists', 'projects', 'purchase_expense_no', 'modes', 'terms', 'cCenters', 'txnTypes',  'vats', 'pInfos', 'account_heads', 'account_sub_heads', 'master_details', 'units', 'special_heads', 'special_master_details', 'products', 'from', 'to', 'expenses', 'temp_expenses' ,'lpo','type', 'data'));
    }



    public function expensepost(Request $request)
    {
        Gate::authorize('Expense_Create');

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
        $purchase_number = InvoiceNumber::first();
        $purchase_number->purchase_no = $purch_ex->purchase_no;
        $purchase_number->save();
        //cogs records entry
        $project_expense = TempPE::where('token', $request->_token)->get();
        foreach($project_expense as $project_e){
            $check_sub_head = substr($project_e->account_head_id,0,3);
            $proj_exp = new TempCogsAssign;
            if($check_sub_head == 'Sub'){
                $sub_id = substr($project_e->account_head_id,3);
                $sub_acc = AccountSubHead::find($sub_id);
                $proj_exp->sub_head_id = $sub_id;
                $proj_exp->account_head_id = $sub_acc->account_head_id;
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
        $subsidiary_store = TempSubsidiary::where('token', $request->_token)->get();
        foreach($subsidiary_store as $project_s){
            $check_sub_head = substr($project_s->account_head_id,0,3);
            $proj_exp = new SubsidiaryStore;
            if($check_sub_head == 'Sub'){
                $sub_id = substr($project_s->account_head_id,3);
                $sub_acc = AccountSubHead::find($sub_id);
                $proj_exp->sub_head_id = $sub_id;
                $proj_exp->account_head_id = $sub_acc->account_head_id;
            }else{
                $proj_exp->account_head_id = $project_s->account_head_id;
            }
            $proj_exp->purchase_id = $purch_ex->id;
            $proj_exp->company_id = $project_s->company_id;
            $proj_exp->amount = $project_s->amount;
            $proj_exp->qty = $project_s->qty;
            $proj_exp->save();
            $project_s->delete();
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
                $purc_exp_itm->qty = isset($each_head['qty']) ? $each_head['qty'] : 1;
                $purc_exp_itm->unit_id = isset($each_head['unit_id']) ? $each_head['unit_id'] : null;
                $purc_exp_itm->type = $each_head['type'];
                $purc_exp_itm->party_id = $request->party_info;
                $purc_exp_itm->purchase_expense_id = $purch_ex->id;
                $purc_exp_itm->gst_subtotal = 0;
                $purc_exp_itm->save();
            }
        }
        //end records entry
        $purchase_exp = $purch_ex;
        $expenses = PurchaseExpense::orderBy('date', 'desc')->paginate(150);
        $expenses_temp = PurchaseExpenseTemp::orderBy('date', 'desc')->get();
        $purchase_exp_items = PurchaseExpenseItemTemp::where('purchase_expense_id', $purchase_exp->id)->get();

        return response()->json([
            'preview' =>  view('backend.purchase-expense.approve-preview', compact('purchase_exp', 'purchase_exp_items'))->render(),
            'expense_list' => view('backend.purchase-expense.search-purch', compact('expenses', 'expenses_temp'))->render(),
        ]);
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
        $modes = PayMode::whereNotIn('id', [2,3,5])->get();
        $employee = Employee::whereHas('payment_account')->orderBy('full_name')->get();
        $purchase_exp = PurchaseExpense::find($request->id);
        $bank_name = AccountSubHead::where('account_head_id', 2)->get();
        return view('backend.purchase-expense.preview', compact('purchase_exp', 'modes', 'employee', 'bank_name'));
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
        $purchase_exp_items = PurchaseExpenseItemTemp::where('purchase_expense_id', $purchase_exp->id)->get();
        return view('backend.purchase-expense.approve-preview', compact('purchase_exp', 'purchase_exp_items'));
    }


    public function payment_modal(Request $request)
    {
        $payment = Payment::find($request->id);
        return view('backend.purchase-expense.payment-preview', compact('payment'));
    }

    public function payment_voucher2()
    {
        Gate::authorize('Payment');
        $expenses = PurchaseExpense::where('due_amount', '>', 0)->get();
        $parties = PartyInfo::where('pi_type','Supplier')->get();
        $i = 0;
        $modes = PayMode::whereNotIn('id', [2,3,5])->get();
        $employee = Employee::whereHas('payment_account')->orderBy('full_name')->get();
        $temp_payments = TempPaymentVoucher::where('is_authorize', 1);
                                // ->get();
        $temp_payment_total_amount = (clone $temp_payments)->get()->sum('total_amount');
        $temp_payments = $temp_payments->get();

        $payments = Payment::orderBy('id', 'desc');
                        // ->paginate(40);
        $payment_total_amount = (clone $payments)->get()->sum('total_amount');
        $payments = $payments->paginate(40);

        $data = [];
        $data['total_amount'] = $temp_payment_total_amount + $payment_total_amount;

        $bank_name = AccountSubHead::where('account_head_id', 2)->get();
        return view('backend.purchase-expense.payment-voucher', compact('expenses', 'i', 'parties', 'modes', 'employee', 'temp_payments', 'payments', 'bank_name', 'data'));
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
        }elseif ($request->id != null) {
            $invoicess = PurchaseExpenseItem::leftjoin('purchase_expenses', 'purchase_expense_items.purchase_expense_id', '=', 'purchase_expenses.id')
                ->where('purchase_expenses.party_id', $request->id)
                ->orderBy('purchase_expenses.date', 'DESC')
                ->select('purchase_expense_items.*')
                ->get();
        }elseif ($request->invoice_no != null) {
            $invoicess = PurchaseExpenseItem::leftjoin('purchase_expenses', 'purchase_expense_items.purchase_expense_id', '=', 'purchase_expenses.id')
                ->where('purchase_expense_items.invoice_no', 'like', "%{$request->invoice_no}%")
                ->orderBy('purchase_expenses.date', 'DESC')
                ->select('purchase_expense_items.*')
                ->get();
        }else {
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
        $expenses_temp = PurchaseExpenseTemp::where('purchase_no', 'like', "%{$request->value}%")->orWhere('invoice_no', 'like', "%{$request->value}%")->orderBy('date', 'desc')->get();
        if ($request->party != '') {
            $expenses = $expenses->where('party_id', $request->party);
            $expenses_temp = $expenses_temp->where('party_id', $request->party);
        }
        if ($request->date != '') {
            $date = $this->dateFormat($request->date);
            $expenses = $expenses->where('date', $date);
            $expenses_temp = $expenses_temp->where('date', $date);
        }
        return view('backend.purchase-expense.search-purch', compact('expenses', 'expenses_temp'));
    }

    public function purchase_authorize()
    {
        Gate::authorize('Expense_Authorize');

        $parties = PartyInfo::get();
        $i = 0;
        $expenses = PurchaseExpenseTemp::where('authorized', false)->orderBy('id', 'DESC')->get();
        return view('backend.purchase-expense.authorize', compact('expenses', 'parties', 'i'));
    }

    public function purchase_authorization($id)
    {
        Gate::authorize('Expense_Authorize');

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
        Gate::authorize('Expense_Approve');

        $parties = PartyInfo::get();
        $i = 0;
        $expenses = PurchaseExpenseTemp::where('authorized', true)->orderBy('id', 'DESC')->get();
        return view('backend.purchase-expense.approve', compact('expenses', 'parties', 'i'));
    }


    public function purchase_approval($id)
    {
        Gate::authorize('Expense_Approve');

        $purch = PurchaseExpenseTemp::find($id);
        $purch_ex = new PurchaseExpense();
        $purch_ex->date = $purch->date;
        $purch_ex->lpo_bill_id = $purch->lpo_bill_id;
        $purch_ex->requisition_id = $purch->requisition_id;
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
        $purchase_exp = LpoBill::find($purch->lpo_bill_id);
        if($purchase_exp){
            $purchase_exp->status = 'Expense Created';
            $purchase_exp->save();
            $requisition = Requisition::where('id', $purchase_exp->requisition_id)->first();
            if ($requisition) {
                $requisition->status = 'Expense Created';
                $requisition->save();
            }
        }
        foreach($purch->documents as $file){
            $document = new PurchaseExpenseDocument();
            $document->expense_id = $purch_ex->id;
            $document->file_name = $file->file_name;
            $document->ext = $file->ext;
            $document->save();
        }

        // cogs project expense $purch
        $cogs_items = TempCogsAssign::where('purchase_expense_id', $purch->id)->get();
        foreach($cogs_items as $cogs_itm){
            $base_amount = $cogs_itm->amount;;
            $vat = $base_amount * 0.05;
            $total = $base_amount + $vat;

            $proj_exp = new ProjectExpense;
            $proj_exp->purchase_expense_id = $purch_ex->id;
            $proj_exp->inventory_expense_id = 0;
            $proj_exp->sub_head_id = $cogs_itm->sub_head_id;
            $proj_exp->account_head_id = $cogs_itm->account_head_id;
            $proj_exp->project_id = $cogs_itm->project_id;
            $proj_exp->task_id = $cogs_itm->task_id;
            $proj_exp->task_item_id = $cogs_itm->task_item_id;
            $proj_exp->amount = $cogs_itm->amount;
            $proj_exp->vat = $vat;
            $proj_exp->total_amount = $total;
            $proj_exp->due_amount =  $total;
            $proj_exp->qty = $cogs_itm->qty;
            $proj_exp->save();
            $cogs_itm->forceDelete();

            $job_project_task = JobProjectTask::where('job_project_id', $cogs_itm->project_id)->where('id',$cogs_itm->task_id)->first();
            if($job_project_task){
                $job_project_task->expense += $total;
                $job_project_task->payable += $total;
                $job_project_task->save();
            }
        }
        // subsidiary expense store
        $subsidiary_expense = SubsidiaryStore::where('purchase_id', $purch->id)->get();
        foreach($subsidiary_expense as $subsidiary_exp){
            $new_sub = new SubsidiaryExpense();
            $new_sub->purchase_id = $subsidiary_exp->purchase_id;
            $new_sub->company_id = $subsidiary_exp->company_id;
            $new_sub->account_head_id = $subsidiary_exp->account_head_id;
            $new_sub->sub_head_id = $subsidiary_exp->sub_head_id;
            $new_sub->amount = $subsidiary_exp->amount;
            $new_sub->qty = $subsidiary_exp->qty;
            $new_sub->save();
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
            if($ac_head){
                if($sub_head){
                    $project_expens = ProjectExpense::where('purchase_expense_id', $purch_ex->id)->where('sub_head_id', $sub_head->id)->get();
                }else{
                    $project_expens = ProjectExpense::where('purchase_expense_id', $purch_ex->id)->where('account_head_id',$ac_head->id)->get();
                }
                if(count($project_expens)>0){
                    $avbl_amount = $purc_exp_itm->amount;
                    foreach($project_expens as $project_expen){
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
                        $jl_record->amount              = $project_expen->amount;
                        $jl_record->total_amount        = $project_expen->amount;
                        $jl_record->vat_rate_id         = 0;
                        $jl_record->invoice_no          = 0;
                        $jl_record->transaction_type    = 'DR';
                        $jl_record->journal_date        = $journal->date;
                        $jl_record->is_main_head        = 1;
                        $jl_record->account_type_id     = $ac_head->account_type_id;
                        // $jl_record->compnay_id          = $project_expen->project?$project_expen->project->compnay_id:null;
                        $jl_record->save();
                        $avbl_amount = $avbl_amount-$project_expen->amount;
                        $company_id = $project_expen->project?$project_expen->project->compnay_id:null;
                        if($company_id){
                            $company_sub_head = AccountSubHead::where('company_id', $company_id)->first();
                            $jl_record->sub_account_head_id = $company_sub_head->id;
                            $jl_record->save();
                            $this->company_journal($company_id, $jl_record->id, $ac_head->id, $purc_exp_itm->sub_head_id);
                        }
                    }
                    if($avbl_amount>0){
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
                        $jl_record->amount              = $avbl_amount;
                        $jl_record->total_amount        = $avbl_amount;
                        $jl_record->vat_rate_id         = 0;
                        $jl_record->invoice_no          = 0;
                        $jl_record->transaction_type    = 'DR';
                        $jl_record->journal_date        = $journal->date;
                        $jl_record->is_main_head        = 1;
                        $jl_record->account_type_id     = $ac_head->account_type_id;
                        $jl_record->save();
                        $avbl_amount = 0;
                    }
                }else{
                    if($ac_head->master_account_id != 3){
                        if($sub_head){
                            $project_subsidiary = SubsidiaryStore::where('purchase_id', $purch->id)->where('sub_head_id', $sub_head->id)->get();
                        }else{
                            $project_subsidiary = SubsidiaryStore::where('purchase_id', $purch->id)->where('account_head_id',$ac_head->id)->get();
                        }
                        if(count($project_subsidiary)>0){
                            $av_bl = $purc_exp_itm->amount;
                            foreach($project_subsidiary as $project_s){
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
                                $jl_record->amount              = $project_s->amount;
                                $jl_record->total_amount        = $project_s->amount;
                                $jl_record->vat_rate_id         = 0;
                                $jl_record->invoice_no          = 0;
                                $jl_record->transaction_type    = 'DR';
                                $jl_record->journal_date        = $journal->date;
                                $jl_record->is_main_head        = 1;
                                $jl_record->account_type_id     = $ac_head->account_type_id;
                                // $jl_record->compnay_id          = $project_s->company_id;
                                $jl_record->save();
                                $av_bl = $av_bl-$project_s->amount;

                                $company_id = $project_s->company_id;
                                if($company_id){
                                    $company_sub_head = AccountSubHead::where('company_id', $company_id)->first();
                                    $jl_record->sub_account_head_id = $company_sub_head->id;
                                    $jl_record->save();
                                    $this->company_journal($company_id, $jl_record->id, $ac_head->id, $purc_exp_itm->sub_head_id);
                                }
                            }
                            if($av_bl>0){
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
                                $jl_record->amount              = $av_bl;
                                $jl_record->total_amount        = $av_bl;
                                $jl_record->vat_rate_id         = 0;
                                $jl_record->invoice_no          = 0;
                                $jl_record->transaction_type    = 'DR';
                                $jl_record->journal_date        = $journal->date;
                                $jl_record->is_main_head        = 1;
                                $jl_record->account_type_id     = $ac_head->account_type_id;
                                $jl_record->save();
                                $av_bl = 0;
                            }
                        }else{
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
                        }
                    }else{
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
                    }
                }
            }else{
                $purchase_expense_acount += $purc_exp_itm->amount;
            }
            if($ac_head->master_account_id == 3){
                $stock_qty = $purc_exp_itm->qty;
                if($purch_ex->requisition_id){
                    $requisition_item = RequisitionItem::where('requisition_id', $purch_ex->requisition_id)->where('item_description',$sub_head?$sub_head->name:null)->first();
                    if($requisition_item){
                        $stock_qty = $stock_qty-$requisition_item->qty;
                    }
                }
                $stock_transection = new StockTransection;
                $stock_transection->product_id = $ac_head->id;
                $stock_transection->sub_head_id = $sub_head?$sub_head->id:null;
                $stock_transection->transection_id = $purch_ex->id;
                $stock_transection->date = $purch_ex->date;
                $stock_transection->transection_type = 'Purchase';
                $stock_transection->stock_effect = 1;
                $stock_transection->transection_code = 'p';
                $stock_transection->quantity = $stock_qty;
                $stock_transection->unit_price =  $purc_exp_itm->amount/$purc_exp_itm->qty;
                $stock_transection->cost_sale_price = $purc_exp_itm->total_amount;
                $stock_transection->remaining_stock = $stock_qty;
                $stock_transection->consumed_quantity = 0;
                $stock_transection->unit = $item->unit_id ? Unit::find($item->unit_id)->name : '';
                $stock_transection->save();
            }
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
        // if ($purch_ex->pay_mode == 'Cash' || $purch_ex->pay_mode == 'Petty Cash' || $purch_ex->pay_mode == 'Card' || $purch_ex->pay_mode == 'Bank' || $purch_ex->pay_mode == 'Cheque') {
        //     $sub_invoice = 'PV'.Carbon::now()->format('y');
        //     $let_purch_exp = InvoiceNumber::where('payment_no', 'LIKE', "%{$sub_invoice}%")->first();
        //     if ($let_purch_exp) {
        //         $purch_code =preg_replace('/^'.$sub_invoice.'/', '', $let_purch_exp->payment_no);
        //         $purch_code = $purch_code + 1;
        //         if($purch_code<10)
        //         {
        //             $payment_no=$sub_invoice.'000'.$purch_code;
        //         }
        //         elseif($purch_code<100)
        //         {
        //             $payment_no=$sub_invoice.'00'.$purch_code;
        //         }
        //         elseif($purch_code<1000)
        //         {
        //             $payment_no=$sub_invoice.'0'.$purch_code;
        //         }
        //         else
        //         {
        //             $payment_no=$sub_invoice.$purch_code;

        //         }
        //     } else {
        //         $payment_no = $sub_invoice . '0001';
        //     }
        //     $payment = new Payment();
        //     $payment->date = $purch_ex->date;
        //     $payment->pay_mode =  $purch_ex->pay_mode;
        //     $payment->payment_no = $payment_no;
        //     $payment->head_id = 0;
        //     $payment->total_amount = $purch_ex->total_amount;
        //     $payment->vat = 0;
        //     $payment->party_id = $purch_ex->party_id;
        //     $payment->narration = $purch_ex->narration??'n/a';
        //     $payment->paid_amount = 0;
        //     $payment->due_amount = 0;
        //     if($purch_ex->pay_mode == 'Cheque'){
        //         $payment->issuing_bank = $purch_ex->issuing_bank;
        //         $payment->branch = $purch_ex->bank_branch;
        //         $payment->deposit_date = $purch_ex->deposit_date;
        //         $payment->cheque_no = $purch_ex->cheque_no;
        //         $payment->status = 'Pending';
        //     }else{
        //         $payment->status = 'Realised';
        //     }
        //     $payment->save();

        //     $payment_invoice = InvoiceNumber::first();
        //     $payment_invoice->payment_no = $payment->payment_no;
        //     $payment_invoice->save();

        //     $purc_exp_itm = new PaymentInvoice();
        //     $purc_exp_itm->sale_id = $purch_ex->id;
        //     $purc_exp_itm->payment_id = $payment->id;
        //     $purc_exp_itm->total_amount = $payment->total_amount;
        //     $purc_exp_itm->vat = 0;
        //     $purc_exp_itm->amount = $payment->total_amount;
        //     $purc_exp_itm->party_id = $payment->party_id;
        //     $purc_exp_itm->save();
        //     if($purch_ex->pay_mode != 'Cheque'){
        //         if($purch_ex->pay_mode == 'Petty Cash'){
        //             $pay_head = 93;
        //         }elseif($purch_ex->pay_mode == 'Card' || $purch_ex->pay_mode == 'Bank'){
        //             $pay_head = 2;
        //         }elseif($purch_ex->pay_mode == 'VISA Card'){
        //             $pay_head = 153;
        //         }else{
        //             $pay_head = 1;
        //         }
        //         $ac_head = AccountHead::find($pay_head);
        //         $jl_record = new JournalRecord();
        //         $jl_record->journal_id          = $journal->id;
        //         $jl_record->project_details_id  = $journal->project_id;
        //         $jl_record->cost_center_id      = $journal->cost_center_id;
        //         $jl_record->party_info_id       = $journal->party_info_id;
        //         $jl_record->journal_no          = $journal->journal_no;
        //         $jl_record->account_head_id     = $ac_head->id;
        //         $jl_record->master_account_id   = $ac_head->master_account_id;
        //         $jl_record->account_head        = $ac_head->fld_ac_head;
        //         $jl_record->amount              = $payment->total_amount;
        //         $jl_record->total_amount        = $payment->total_amount;
        //         $jl_record->vat_rate_id         = 0;
        //         $jl_record->transaction_type    = 'CR';
        //         $jl_record->journal_date        = $journal->date;
        //         $jl_record->invoice_no          = 'N/A';
        //         $jl_record->account_type_id     = $ac_head->account_type_id;

        //         $jl_record->is_main_head        = 0;
        //         $jl_record->save();
        //     } else {
        //         $ac_head = AccountHead::find(5); // accounts payable
        //         $jl_record = new JournalRecord();
        //         $jl_record->journal_id          = $journal->id;
        //         $jl_record->project_details_id  = $journal->project_id;
        //         $jl_record->cost_center_id      = $journal->cost_center_id;
        //         $jl_record->party_info_id       = $journal->party_info_id;
        //         $jl_record->journal_no          =  $journal->journal_no;
        //         $jl_record->account_head_id     = $ac_head->id;
        //         $jl_record->master_account_id   = $ac_head->master_account_id;
        //         $jl_record->account_head        = $ac_head->fld_ac_head;
        //         $jl_record->amount              = $payment->total_amount;
        //         $jl_record->total_amount        = $payment->total_amount;
        //         $jl_record->vat_rate_id         = 0;
        //         $jl_record->transaction_type    = 'CR';
        //         $jl_record->journal_date        = $journal->date;
        //         $jl_record->invoice_no              = 'N/A';
        //         $jl_record->account_type_id = $ac_head->account_type_id;
        //         $jl_record->is_main_head        = 0;
        //         $jl_record->save();
        //     }

        // }
        SubsidiaryStore::where('purchase_id', $purch->id)->delete();
        $purch->items->each->delete();
        $purch->delete();

        $modes = PayMode::whereNotIn('id', [2,3,5])->get();
        $employee = Employee::whereHas('payment_account')->orderBy('full_name')->get();
        $purchase_exp = $purch_ex;
        $expenses = PurchaseExpense::orderBy('date', 'desc')->paginate(150);
        $expenses_temp = PurchaseExpenseTemp::orderBy('date', 'desc')->get();
        $bank_name = AccountSubHead::where('account_head_id', 2)->get();

        return response()->json([
            'preview' =>  view('backend.purchase-expense.preview', compact('purchase_exp', 'modes', 'employee', 'bank_name'))->render(),
            'expense_list' => view('backend.purchase-expense.search-purch', compact('expenses', 'expenses_temp'))->render(),
        ]);
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

    public function purchase_expense_edit(Request $request)
    {
        Gate::authorize('Expense_Edit');

        $projects = ProjectDetail::all();
        $modes = PayMode::whereNotIn('id', [])->get();
        $terms = PayTerm::all();
        $sub_invoice = Carbon::now()->format('Ymd');
        $cCenters = CostCenter::all();
        $txnTypes = TxnType::all();

        $pInfos = PartyInfo::where('pi_type', 'Supplier')->get();
        $parties = PartyInfo::get();
        $vats = VatRate::get();
        $orders = JobProject::latest()->paginate(20);
        $purchase=PurchaseExpenseTemp::find($request->id);
        $invoices = JobProject::latest()->paginate(20);
        $account_heads = AccountHead::whereIn('account_type_id',[1,4])->where('fld_definition', '!=' ,'Cost of Sales / Goods Sold')->whereNotIn('master_account_id', [1])->get();
        $special_heads = AccountHead::where('fld_definition', 'Cost of Sales / Goods Sold')->get();
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
        return view('backend.purchase-expense.purchase-expense-edit', compact('purchase','orders', 'parties', 'projects', 'modes', 'terms', 'cCenters', 'txnTypes',  'vats', 'pInfos', 'invoices', 'balance', 'account_heads', 'special_heads'));
    }


    public function expense_edit_post(Request $request, $id)
    {
        Gate::authorize('Expense_Edit');

        $request->validate([
                'date'              =>  'required',
                'party_info'        => 'required',
                'narration'         => 'required'
            ],
            [
                'date.required'         => 'Date is required',
                'party_info.required'   => 'Party Info is required',
                'narration.required'    => 'Narration is required',
            ]
        );

        //Update date formate
        $update_date_format = $this->dateFormat($request->date);
        //purchase expense entry
        $purch_ex = PurchaseExpenseTemp::find($id);
        $purch_ex->date = $update_date_format;
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
        $purch_ex->paid_amount = $request->pay_mode == 'Credit' ?  0 : $purch_ex->total_amount;
        $purch_ex->due_amount = $request->pay_mode == 'Credit' ?  $purch_ex->total_amount : 0;
        $purch_ex->save();
        //end purchase expense entry
        $purch_ex->items->each->delete();

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
                $purc_exp_itm->qty = isset($each_head['qty'])?$each_head['qty']:1;
                $purc_exp_itm->unit_id = isset($each_head['unit_id'])?$each_head['unit_id']:1;
                $purc_exp_itm->party_id = $request->party_info;
                $purc_exp_itm->purchase_expense_id = $purch_ex->id;
                $purc_exp_itm->gst_subtotal = 0;
                $purc_exp_itm->save();
            }
        }
        $purchase_exp = $purch_ex;
        $expenses = PurchaseExpense::orderBy('date', 'desc')->paginate(150);
        $expenses_temp = PurchaseExpenseTemp::orderBy('date', 'desc')->get();
        $purchase_exp_items = PurchaseExpenseItemTemp::where('purchase_expense_id', $purchase_exp->id)->get();
        return response()->json([
            'preview' =>  view('backend.purchase-expense.approve-preview', compact('purchase_exp', 'purchase_exp_items'))->render(),
            'expense_list' => view('backend.purchase-expense.search-purch', compact('expenses', 'expenses_temp'))->render(),
        ]);
    }

    public function purchase_delete($id)
    {
        $purch=PurchaseExpenseTemp::find($id);
        $purch->items->each->delete();
        BillDistribute::where('bill_id', $purch->id)->delete();
        TempCogsAssign::where('purchase_expense_id', $purch->id)->delete();
        SubsidiaryStore::where('purchase_id', $purch->id)->delete();
        $purch->delete();
        $notification = array(
            'message'       => 'Deleted Successfully!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function payable(Request $request)
    {
        if($request->date_to){
            $date_to = $this->dateFormat($request->date_to);
        }else{
            $date_to = date('Y-m-d');
        }
        if($request->date_from){
            $date_from = $this->dateFormat($request->date_from);
        }else{
            $date_from = date('Y-m-d');
        }
        Gate::authorize('Payable');
        $suppliers=DB::table('party_infos')
        ->where('party_infos.pi_type','Supplier')
        ->join('purchase_expenses', 'party_infos.id', '=', 'purchase_expenses.party_id')
        ->select('party_infos.id','party_infos.pi_code','party_infos.pi_name',
        DB::raw('SUM(CASE WHEN purchase_expenses.party_id =party_infos.id THEN purchase_expenses.due_amount ELSE 0 END ) as due_amount')
        )
        ->groupBy('party_infos.id','party_infos.pi_code','party_infos.pi_name')
        ->orderByDesc('due_amount');
        // ->paginate(40);
        $cal_due_amount =  (clone $suppliers)->get()->sum('due_amount');
        $suppliers = $suppliers->paginate(40);

        $data = [];
        $data['due_amount'] = $cal_due_amount;

        return view('backend.purchase-expense.payable',compact('suppliers', 'data'));
    }
    public function home_payable(Request $request)
    {
        $date_from = $request->date_from ? $this->dateFormat($request->date_from) : null;
        $date_to   = $request->date_to ? $this->dateFormat($request->date_to) : null;
        $month     = $request->month ?? null; // YYYY-MM from input type="month"
        $year      = $request->year ?? null;  // optional if separate dropdown

        Gate::authorize('Payable');

        $query = PurchaseExpense::select(
            'party_id',
            DB::raw('SUM(due_amount) as due_amount')
        )
            // Date range filter
            ->when($date_from && $date_to, function ($query) use ($date_from, $date_to) {
                $query->whereBetween('date', [$date_from, $date_to]);
            })
            ->when($date_from && !$date_to, function ($query) use ($date_from) {
                $query->whereDate('date', $date_from);
            })
            ->when(!$date_from && $date_to, function ($query) use ($date_to) {
                $query->whereDate('date', $date_to);
            })

            // Month filter
            ->when($month, function ($query) use ($month) {
                $query->whereMonth('date', '=', date('m', strtotime($month)))
                    ->whereYear('date', '=', date('Y', strtotime($month)));
            })

            // Year filter
            ->when($year, function ($query) use ($year) {
                $query->whereYear('date', '=', $year);
            })

            ->where('due_amount', '>', 0)
            ->groupBy('party_id')
            ->orderByDesc('due_amount');

        // Paginate results with filter params
        $suppliers = $query->paginate(20)->appends(request()->except('page'));

        return view('backend.purchase-expense.payable1', compact('suppliers', 'date_from', 'date_to', 'month', 'year'));
    }


    public function home_expense(Request $request)
    {
        $date_from = $request->date_from ? $this->dateFormat($request->date_from) : null;
        $date_to   = $request->date_to ? $this->dateFormat($request->date_to) : null;
        $month     = $request->month ?? null; // YYYY-MM from input type="month"
        $year      = $request->year ?? null;  // from input type="number"

        Gate::authorize('Payable');

        $query = PurchaseExpense::query()
            // Date range filter
            ->when($date_from && $date_to, function ($query) use ($date_from, $date_to) {
                $query->whereBetween('date', [$date_from, $date_to]);
            })
            ->when($date_from && !$date_to, function ($query) use ($date_from) {
                $query->whereDate('date', $date_from);
            })
            ->when(!$date_from && $date_to, function ($query) use ($date_to) {
                $query->whereDate('date', $date_to);
            })

            // Month filter
            ->when($month, function ($query) use ($month) {
                $query->whereMonth('date', '=', date('m', strtotime($month)))
                    ->whereYear('date', '=', date('Y', strtotime($month)));
            })

            // Year filter
            ->when($year, function ($query) use ($year) {
                $query->whereYear('date', '=', $year);
            });

        // Grand total
        $grand_total_amount = (clone $query)->sum('total_amount');

        // Paginate with filters preserved
        $expenses = $query->paginate(20)->appends(request()->except('page'));

        return view('backend.purchase-expense.home_expense', compact(
            'expenses',
            'grand_total_amount',
            'date_from',
            'date_to',
            'month',
            'year'
        ));
    }

   public function geteExpense(Request $request)
    {
        $party_id = $request->party_id;

        $expenses = PurchaseExpense::where('party_id', $party_id)->where('due_amount', '>', 0)->get();

        return response()->json($expenses);
    }
     public function geteExpenseView(Request $request)
    {
        $id = $request->id;
        $expense = PurchaseExpense::where('id', $id)->with('items')->first();
        return response()->json($expense);
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
    public function inventory_project_expense(Request $request){
        $project_lists = NewProject::all();
        $amount = $request->amount;
        $qty = $request->qty;
        $account_head = $request->head_id;
        $projects = JobProject::all();
        $check_sub_head = substr($request->head_id,0,3);
        if($check_sub_head == 'Sub'){
            $sub_head_id = substr($request->head_id,3);
            $assign_qty = TempProjectExpense::where('sub_head_id', $sub_head_id)->sum('qty');
        }else{
            $assign_qty = TempProjectExpense::where('account_head_id', $account_head)->where('sub_head_id', null)->sum('qty');
        }
        // dd($request->head_id);
        return view('backend.inventory.inventory-project-expense', compact('project_lists', 'amount', 'account_head', 'qty', 'projects', 'assign_qty'));
    }

    public function inventory_project_expense_store(Request $request){

        $project_ids = $request->project_id;
        $check_sub_head = substr($request->accout_head_id,0,3);

        if($check_sub_head == 'Sub'){
            $sub_head_id = substr($request->accout_head_id,3);
            $sub_head_info = AccountSubHead::find($sub_head_id);
            $account_head_id = $sub_head_info->account_head_id;
        }else{
            $account_head_id = $request->accout_head_id;
            $sub_head_id = null;
        }

        if($sub_head_id){
            $stock = StockTransection::where('product_id', $account_head_id)->where('sub_head_id', $sub_head_id)->where('transection_type','Purchase')->where('stock_effect',1)->where('remaining_stock', '>', 0)->latest()->first();
            $stock_all = StockTransection::where('product_id', $account_head_id)->where('sub_head_id', $sub_head_id)->where('transection_type','Purchase')->where('stock_effect',1)->where('remaining_stock', '>', 0)->get();
        }else{
            $stock = StockTransection::where('product_id', $account_head_id)->where('transection_type','Purchase')->where('stock_effect',1)->where('remaining_stock', '>', 0)->latest()->first();
            $stock_all = StockTransection::where('product_id', $account_head_id)->where('transection_type','Purchase')->where('stock_effect',1)->where('remaining_stock', '>', 0)->get();
        }


        $inventory = new TempInventoryExpense;
        $inventory->date = $this->dateFormat($request->date);
        $inventory->account_head_id = $account_head_id;
        $inventory->sub_account_id = $sub_head_id;
        $inventory->unit = $stock->unit??null;
        $inventory->save();

        if($project_ids){
            foreach($project_ids as $key => $id){

                $total_price = $stock_all->sum('cost_sale_price');
                $total_qty = $stock_all->sum('quantity');
                $avarage_amount = $total_price / $total_qty;

                if($id && $request->task_qty[$key]){
                    $proj_exp = new TempProjectExpense;
                    $proj_exp->inventory_expense_id = $inventory->id;
                    $proj_exp->sub_head_id = $inventory->sub_account_id;
                    $proj_exp->account_head_id = $inventory->account_head_id;
                    $proj_exp->project_id = $id;

                    $proj_exp->qty = $request->task_qty[$key];
                    $proj_exp->task_id = $request->task_id[$key];
                    // $proj_exp->task_item_id = $request->task_item_id[$key];
                    $proj_exp->unit = $stock->unit ?? null;
                    $proj_exp->avarage_rate = $avarage_amount;
                    $proj_exp->amount = $avarage_amount * $proj_exp->qty;
                    $proj_exp->save();
                }
            }
        }
        $temp = true;
        return view('backend.inventory.inventory-show', compact('inventory', 'temp'));
    }

    public function check_project_expense(Request $request){
        $token = $request->_token;
        $multi_head = $request->input('group-a');
        $purchase_id = $request->purchase_id;
        // dd($multi_head);
        return view('backend.purchase-expense.check-project-expense', compact('token', 'multi_head', 'purchase_id'));
    }
    public function check_project_expense_edit(Request $request){
        $token = $request->_token;
        $multi_head = $request->input('group-a');
        $purchase_id = $request->purchase_id;
        // dd($multi_head);
        return view('backend.purchase-expense.check-project-expense-edit', compact('token', 'multi_head', 'purchase_id'));
    }
    public function account_inventory(Request $request){
        $from = $request->form_date ? $this->dateFormat($request->form_date) : ($request->to_date ? $this->dateFormat($request->to_date) : date('Y-m-d'));
        $to = $request->to_date ? $this->dateFormat($request->to_date) :  null;
        $products = StockTransection::join('account_heads', 'account_heads.id', '=', 'stock_transections.product_id')
        ->select('account_heads.id','account_heads.fld_ac_head as product_name')->distinct()->get();
        // dd($products);
        return view('backend.inventory.stock-report', compact('products', 'from', 'to'));
    }
    public function pendding_inventory(Request $request){
        $inventory = TempInventoryExpense::orderBy('date', 'desc')->get();
        // dd($inventory);
        return view('backend.inventory.pendding-inventory', compact('inventory'));
    }
    public function approval_inventory(Request $request){
        $inventory = InventoryExpense::orderBy('date', 'desc')->get();
        return view('backend.inventory.approval-inventory', compact('inventory'));
    }
    public function temp_inventory_show(Request $request){
        $inventory = TempInventoryExpense::find($request->id);
        $temp = true;
        return view('backend.inventory.inventory-show', compact('inventory', 'temp'));
    }
    public function inventory_show(Request $request){
        $inventory = InventoryExpense::find($request->id);
        $temp = false;
        return view('backend.inventory.inventory-show', compact('inventory', 'temp'));
    }
    public function inventory_expense_edit(Request $request){
        $inventory = TempInventoryExpense::find($request->id);
        if($inventory->sub_account_id){
            $qty_in = StockTransection::where('stock_effect', '1')->where('sub_head_id', $inventory->sub_account_id)->sum('quantity');
            $qty_out = StockTransection::where('stock_effect', '-1')->where('sub_head_id', $inventory->sub_account_id)->sum('quantity');
        }else{
            $qty_in = StockTransection::where('stock_effect', '1')->where('product_id', $inventory->account_head_id)->where('sub_head_id', null)->sum('quantity');
            $qty_out = StockTransection::where('stock_effect', '-1')->where('product_id', $inventory->account_head_id)->where('sub_head_id', null)->sum('quantity');
        }
        $assign_qty = TempProjectExpense::whereNotIn('inventory_expense_id', [$inventory->id])->sum('qty');
        $project_lists = NewProject::all();
        $amount = $request->amount;
        $qty = $qty_in-$qty_out;
        $account_head = $inventory->sub_head_id?$inventory->sub_head_id:$inventory->account_head_id;
        $projects = JobProject::all();
        return view('backend.inventory.inventory-edit', compact('project_lists', 'amount', 'account_head', 'qty', 'projects','inventory', 'assign_qty'));
    }
    public function inventory_expense_update(Request $request){
        $project_ids = $request->project_id;
        $check_sub_head = substr($request->accout_head_id,0,3);
        if($check_sub_head == 'Sub'){
            $sub_head_id = substr($request->accout_head_id,3);
            $sub_head_info = AccountSubHead::find($sub_head_id);
            $account_head_id = $sub_head_info->account_head_id;
        }else{
            $account_head_id = $request->accout_head_id;
            $sub_head_id = null;
        }
        $inventory = TempInventoryExpense::find($request->inventory_id);
        $inventory->date = $this->dateFormat($request->date);
        $inventory->save();
        $inventory->items->each->forceDelete();
        if($project_ids){
            foreach($project_ids as $key => $id){
                if($id && $request->task_qty[$key]){
                    $proj_exp = new TempProjectExpense;
                    $proj_exp->inventory_expense_id = $inventory->id;
                    $proj_exp->sub_head_id = $inventory->sub_account_id;
                    $proj_exp->account_head_id = $inventory->account_head_id;
                    $proj_exp->project_id = $id;
                    $proj_exp->amount = 0.00;
                    $proj_exp->qty = $request->task_qty[$key];
                    $proj_exp->task_id = $request->task_id[$key];
                    // $proj_exp->task_item_id = $request->task_item_id[$key];
                    $proj_exp->save();
                }
            }
        }
         $notification = array(
            'message'       => 'Update Successfully!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }
    public function inventory_expense_delete($id){
        $inventory = TempInventoryExpense::find($id);
        foreach ($inventory->items as $item) {
            $item->forceDelete();
        }
        $inventory->forceDelete();
        $notification = array(
            'message'       => 'Deleted Successfully!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function inventory_expense_approve($id){

        $inventory_temp = TempInventoryExpense::find($id);
        $inventory = new InventoryExpense;
        $inventory->date = $inventory_temp->date;
        $inventory->account_head_id = $inventory_temp->account_head_id;
        $inventory->sub_account_id = $inventory_temp->sub_account_id;
        $inventory->save();

        foreach($inventory_temp->items as $key => $inventory_item){

            if($inventory->sub_account_id){
                $findStock = StockTransection::where('sub_head_id', $inventory->sub_account_id )->where('product_id', $inventory->account_head_id)->where('stock_effect',1)->where('remaining_stock','>',0)->where('transection_type', 'Purchase')->orderBy('id', 'asc')->get();
            }else{
                $findStock = StockTransection::where('product_id', $inventory->account_head_id)->where('sub_head_id', null)->where('stock_effect',1)->where('remaining_stock','>',0)->orderBy('id', 'asc')->where('transection_type', 'Purchase')->get();
            }

            $cogs_amount=0;

            $prdct = AccountHead::find($inventory->account_head_id);

            if($prdct){
                if($findStock){

                    $remaining = $inventory_item->qty;

                    foreach($findStock as $stock){
                        if($remaining  <= 0){
                            break;
                        }

                        $purchase = PurchaseExpense::find($stock->transection_id);

                        $cogs_amount = $cogs_amount + ($remaining*$stock->unit_price);
                        $min = min($remaining, $stock->remaining_stock);
                        $base_amount = $min * $stock->unit_price;
                        $vat = $base_amount * 0.05;
                        $total = $base_amount + $vat;

                        $proj_exp = new ProjectExpense;
                        $proj_exp->inventory_expense_id = $inventory->id;
                        $proj_exp->purchase_expense_id = $purchase->id;
                        $proj_exp->sub_head_id = $inventory_item->sub_head_id;
                        $proj_exp->account_head_id = $inventory_item->account_head_id;
                        $proj_exp->project_id = $inventory_item->project_id;
                        $proj_exp->amount =  $base_amount;
                        $proj_exp->vat = $vat;
                        $proj_exp->total_amount = $total;
                        $proj_exp->qty = $min;
                        $proj_exp->task_id = $inventory_item->task_id;
                        $proj_exp->task_item_id = $inventory_item->task_item_id;
                        $proj_exp->paid_amount = min($total, $purchase->paid_amount);
                        $proj_exp->due_amount = $total - $purchase->paid_amount;
                        $proj_exp->save();

                        if($proj_exp->task_id){
                            $task = JobProjectTask::find($proj_exp->task_id);
                            $task->expense = $task->expense +  $total;
                            $task->payable = $task->expense - $task->payment;
                            $task->save();
                        }

                        if($remaining < $stock->remaining_stock){
                            $stock->remaining_stock = $stock->remaining_stock - $remaining;
                            $stock->consumed_quantity = $remaining + $stock->consumed_quantity;
                            $transferStock = new StockTransfer();
                            $transferStock->transection_id = $inventory->id;
                            $transferStock->product_id =$proj_exp->account_head_id;
                            $transferStock->sub_account_head_id =$proj_exp->sub_head_id;
                            $transferStock->type = 'sales';
                            $transferStock->quantity =   $remaining;
                            $transferStock->stock_transection_id = $stock->id;
                            $transferStock->save();
                            $remaining = 0;
                            $stock->save();
                        }else{
                            $remaining = $remaining - $stock->remaining_stock;
                            $stock->remaining_stock = $stock->remaining_stock - $remaining;
                            $stock->consumed_quantity = $remaining + $stock->consumed_quantity;
                            $transferStock = new StockTransfer();
                            $transferStock->transection_id = $inventory->id;
                            $transferStock->product_id =$proj_exp->account_head_id;
                            $transferStock->sub_account_head_id =$proj_exp->sub_head_id;
                            $transferStock->type = 'sales';
                            $transferStock->quantity =   $remaining;
                            $transferStock->stock_transection_id = $stock->id;
                            $transferStock->save();
                            $stock->save();
                        }
                        $stock = new StockTransection();
                        $stock->product_id = $proj_exp->account_head_id;
                        $stock->sub_head_id = $proj_exp->sub_head_id;
                        $stock->transection_id = $inventory->id;
                        $stock->date = $inventory->date;
                        $stock->transection_type = 'sales';
                        $stock->stock_effect = '-1';
                        $stock->transection_code = 'S';
                        $stock->quantity = $proj_exp->qty;
                        $stock->unit_price = $proj_exp->amount/$proj_exp->qty;
                        $stock->cost_sale_price = $proj_exp->amount;
                        $stock->remaining_stock = 0;
                        $stock->consumed_quantity = 0;
                        $stock->save();
                    }
                }
            }

            $inventory_item->forceDelete();
        }

        $inventory_temp->forceDelete();
        // project expense
        if($inventory->items->sum('amount')>0){

            $journal_no = $this->journal_no();
            $journal = new Journal();
            $journal->project_id        = 0;
            $journal->transection_type  = 'Project Expense Entry';
            $journal->transaction_type  = 'Increase';
            $journal->journal_no        = $journal_no;
            $journal->date              = $inventory->date;
            $journal->pay_mode          = 'Credit';
            $journal->cost_center_id    = 0;
            $journal->party_info_id     = 0;
            $journal->account_head_id   = 123;
            $journal->voucher_type      = 'CREDIT';
            $journal->amount            = $inventory->items->sum('amount');
            $journal->tax_rate          = 0;
            $journal->vat_amount        = 0;
            $journal->total_amount      = $inventory->items->sum('amount');
            $journal->gst_subtotal      = 0;
            $journal->narration         = 'n/a';
            $journal->approved_by       = Auth::user()->id;
            $journal->authorized_by     = Auth::user()->id;
            $journal->created_by        = Auth::user()->id;
            $journal->save();
            // cr journal record
            $ac_head = AccountHead::find($inventory->account_head_id);
            $jl_record = new JournalRecord();
            $jl_record->journal_id          = $journal->id;
            $jl_record->project_details_id  = $journal->project_id;
            $jl_record->cost_center_id      = $journal->cost_center_id;
            $jl_record->party_info_id       = $journal->party_info_id;
            $jl_record->journal_no          = $journal->journal_no;
            $jl_record->account_head_id     = $ac_head->id;
            $jl_record->sub_account_head_id = $inventory->sub_account_id;
            $jl_record->master_account_id   = $ac_head->master_account_id;
            $jl_record->account_head        = $ac_head->fld_ac_head;
            $jl_record->amount              = $inventory->items->sum('amount');
            $jl_record->total_amount        = $inventory->items->sum('amount');
            $jl_record->vat_rate_id         = 0;
            $jl_record->invoice_no          = 0;
            $jl_record->transaction_type    = 'CR';
            $jl_record->journal_date        = $journal->date;
            $jl_record->is_main_head        = 1;
            $jl_record->account_type_id     = $ac_head->account_type_id;
            $jl_record->save();

            $construction_amount = 0;
            foreach($inventory->items as $pro_expense){
                $company_id = $pro_expense->project?$pro_expense->project->compnay_id:null;
                if($company_id){
                    $project_expense_head = AccountHead::find($pro_expense->account_head_id);
                    $company_sub_head = AccountSubHead::where('company_id', $company_id)->first();
                    $jl_record = new JournalRecord();
                    $jl_record->journal_id          = $journal->id;
                    $jl_record->project_details_id  = $journal->project_id;
                    $jl_record->cost_center_id      = $journal->cost_center_id;
                    $jl_record->party_info_id       = $journal->party_info_id;
                    $jl_record->journal_no          = $journal->journal_no;
                    $jl_record->sub_account_head_id = $company_sub_head?$company_sub_head->id:null;
                    $jl_record->account_head_id     = $project_expense_head->id;
                    $jl_record->master_account_id   = $project_expense_head->master_account_id;
                    $jl_record->account_head        = $project_expense_head->fld_ac_head;
                    $jl_record->amount              = $pro_expense->amount;
                    $jl_record->total_amount        = $pro_expense->amount;
                    $jl_record->vat_rate_id         = 0;
                    $jl_record->invoice_no          = 0;
                    $jl_record->transaction_type    = 'DR';
                    $jl_record->journal_date        = $journal->date;
                    $jl_record->is_main_head        = 1;
                    $jl_record->account_type_id     = $project_expense_head->account_type_id;
                    $jl_record->project_id          = $pro_expense->project_id;
                    $jl_record->save();

                    // company accounting
                    $journal_no = $this->journal_no();
                    $com_acc_journal = new Journal();
                    $com_acc_journal->project_id        = 0;
                    $com_acc_journal->transection_type  = 'Project Expense Entry';
                    $com_acc_journal->transaction_type  = 'Increase';
                    $com_acc_journal->journal_no        = $journal_no;
                    $com_acc_journal->date              = $inventory->date;
                    $com_acc_journal->pay_mode          = 'Credit';
                    $com_acc_journal->cost_center_id    = 0;
                    $com_acc_journal->party_info_id     = 0;
                    $com_acc_journal->account_head_id   = 123;
                    $com_acc_journal->voucher_type      = 'CREDIT';
                    $com_acc_journal->amount            = $pro_expense->amount;
                    $com_acc_journal->tax_rate          = 0;
                    $com_acc_journal->vat_amount        = 0;
                    $com_acc_journal->total_amount      = $pro_expense->amount;
                    $com_acc_journal->gst_subtotal      = 0;
                    $com_acc_journal->narration         = 'n/a';
                    $com_acc_journal->approved_by       = Auth::user()->id;
                    $com_acc_journal->authorized_by     = Auth::user()->id;
                    $com_acc_journal->created_by        = Auth::user()->id;
                    $com_acc_journal->save();

                    $com_dr_ac_head = AccountHead::find($inventory->account_head_id);
                    $acc_jl_record = new JournalRecord();
                    $acc_jl_record->journal_id          = $com_acc_journal->id;
                    $acc_jl_record->project_details_id  = $com_acc_journal->project_id;
                    $acc_jl_record->cost_center_id      = $com_acc_journal->cost_center_id;
                    $acc_jl_record->party_info_id       = $com_acc_journal->party_info_id;
                    $acc_jl_record->journal_no          = $com_acc_journal->journal_no;
                    $acc_jl_record->account_head_id     = $com_dr_ac_head->id;
                    $acc_jl_record->sub_account_head_id = $inventory->sub_account_id;
                    $acc_jl_record->master_account_id   = $com_dr_ac_head->master_account_id;
                    $acc_jl_record->account_head        = $com_dr_ac_head->fld_ac_head;
                    $acc_jl_record->amount              = $pro_expense->amount;
                    $acc_jl_record->total_amount        = $pro_expense->amount;
                    $acc_jl_record->vat_rate_id         = 0;
                    $acc_jl_record->invoice_no          = 0;
                    $acc_jl_record->transaction_type    = 'DR';
                    $acc_jl_record->journal_date        = $com_acc_journal->date;
                    $acc_jl_record->is_main_head        = 1;
                    $acc_jl_record->account_type_id     = $com_dr_ac_head->account_type_id;
                    $acc_jl_record->compnay_id          = $company_id;
                    $acc_jl_record->save();

                    $com_cr_ac_head = AccountHead::find(1769);
                    $acc_jl_record = new JournalRecord();
                    $acc_jl_record->journal_id          = $com_acc_journal->id;
                    $acc_jl_record->project_details_id  = $com_acc_journal->project_id;
                    $acc_jl_record->cost_center_id      = $com_acc_journal->cost_center_id;
                    $acc_jl_record->party_info_id       = $com_acc_journal->party_info_id;
                    $acc_jl_record->journal_no          = $com_acc_journal->journal_no;
                    $acc_jl_record->account_head_id     = $com_cr_ac_head->id;
                    $acc_jl_record->master_account_id   = $com_cr_ac_head->master_account_id;
                    $acc_jl_record->account_head        = $com_cr_ac_head->fld_ac_head;
                    $acc_jl_record->amount              = $pro_expense->amount;
                    $acc_jl_record->total_amount        = $pro_expense->amount;
                    $acc_jl_record->vat_rate_id         = 0;
                    $acc_jl_record->invoice_no          = 0;
                    $acc_jl_record->transaction_type    = 'CR';
                    $acc_jl_record->journal_date        = $com_acc_journal->date;
                    $acc_jl_record->is_main_head        = 1;
                    $acc_jl_record->account_type_id     = $com_cr_ac_head->account_type_id;
                    $acc_jl_record->compnay_id          = $company_id;
                    $acc_jl_record->save();
                    // opsite company accounting
                    $journal_no = $this->journal_no();
                    $com_acc_journal = new Journal();
                    $com_acc_journal->project_id        = 0;
                    $com_acc_journal->transection_type  = 'Project Expense Entry';
                    $com_acc_journal->transaction_type  = 'Increase';
                    $com_acc_journal->journal_no        = $journal_no;
                    $com_acc_journal->date              = $inventory->date;
                    $com_acc_journal->pay_mode          = 'Credit';
                    $com_acc_journal->cost_center_id    = 0;
                    $com_acc_journal->party_info_id     = 0;
                    $com_acc_journal->account_head_id   = 123;
                    $com_acc_journal->voucher_type      = 'CREDIT';
                    $com_acc_journal->amount            = $pro_expense->amount;
                    $com_acc_journal->tax_rate          = 0;
                    $com_acc_journal->vat_amount        = 0;
                    $com_acc_journal->total_amount      = $pro_expense->amount;
                    $com_acc_journal->gst_subtotal      = 0;
                    $com_acc_journal->narration         = 'n/a';
                    $com_acc_journal->approved_by       = Auth::user()->id;
                    $com_acc_journal->authorized_by     = Auth::user()->id;
                    $com_acc_journal->created_by        = Auth::user()->id;
                    $com_acc_journal->save();

                    $com_cr_ac_head = AccountHead::find($inventory->account_head_id);
                    $acc_jl_record = new JournalRecord();
                    $acc_jl_record->journal_id          = $com_acc_journal->id;
                    $acc_jl_record->project_details_id  = $com_acc_journal->project_id;
                    $acc_jl_record->cost_center_id      = $com_acc_journal->cost_center_id;
                    $acc_jl_record->party_info_id       = $com_acc_journal->party_info_id;
                    $acc_jl_record->journal_no          = $com_acc_journal->journal_no;
                    $acc_jl_record->account_head_id     = $com_cr_ac_head->id;
                    $acc_jl_record->sub_account_head_id = $inventory->sub_account_id;
                    $acc_jl_record->master_account_id   = $com_cr_ac_head->master_account_id;
                    $acc_jl_record->account_head        = $com_cr_ac_head->fld_ac_head;
                    $acc_jl_record->amount              = $pro_expense->amount;
                    $acc_jl_record->total_amount        = $pro_expense->amount;
                    $acc_jl_record->vat_rate_id         = 0;
                    $acc_jl_record->invoice_no          = 0;
                    $acc_jl_record->transaction_type    = 'CR';
                    $acc_jl_record->journal_date        = $com_acc_journal->date;
                    $acc_jl_record->is_main_head        = 1;
                    $acc_jl_record->account_type_id     = $com_cr_ac_head->account_type_id;
                    $acc_jl_record->compnay_id          = $company_id;
                    $acc_jl_record->save();

                    $com_dr_ac_head = AccountHead::find(1668);
                    $acc_jl_record = new JournalRecord();
                    $acc_jl_record->journal_id          = $com_acc_journal->id;
                    $acc_jl_record->project_details_id  = $com_acc_journal->project_id;
                    $acc_jl_record->cost_center_id      = $com_acc_journal->cost_center_id;
                    $acc_jl_record->party_info_id       = $com_acc_journal->party_info_id;
                    $acc_jl_record->journal_no          = $com_acc_journal->journal_no;
                    $acc_jl_record->account_head_id     = $com_dr_ac_head->id;
                    $acc_jl_record->master_account_id   = $com_dr_ac_head->master_account_id;
                    $acc_jl_record->account_head        = $com_dr_ac_head->fld_ac_head;
                    $acc_jl_record->amount              = $pro_expense->amount;
                    $acc_jl_record->total_amount        = $pro_expense->amount;
                    $acc_jl_record->vat_rate_id         = 0;
                    $acc_jl_record->invoice_no          = 0;
                    $acc_jl_record->transaction_type    = 'DR';
                    $acc_jl_record->journal_date        = $com_acc_journal->date;
                    $acc_jl_record->is_main_head        = 1;
                    $acc_jl_record->account_type_id     = $com_dr_ac_head->account_type_id;
                    $acc_jl_record->compnay_id          = $company_id;
                    $acc_jl_record->save();

                }else{
                    $construction_amount += $pro_expense->amount;
                }
            }
            if($construction_amount>0){
                $ac_head = AccountHead::find(1668);
                $jl_record = new JournalRecord();
                $jl_record->journal_id          = $journal->id;
                $jl_record->project_details_id  = $journal->project_id;
                $jl_record->cost_center_id      = $journal->cost_center_id;
                $jl_record->party_info_id       = $journal->party_info_id;
                $jl_record->journal_no          = $journal->journal_no;
                $jl_record->account_head_id     = $ac_head->id;
                $jl_record->master_account_id   = $ac_head->master_account_id;
                $jl_record->account_head        = $ac_head->fld_ac_head;
                $jl_record->amount              = $construction_amount;
                $jl_record->total_amount        = $construction_amount;
                $jl_record->vat_rate_id         = 0;
                $jl_record->invoice_no          = 0;
                $jl_record->transaction_type    = 'DR';
                $jl_record->journal_date        = $journal->date;
                $jl_record->is_main_head        = 1;
                $jl_record->account_type_id     = $ac_head->account_type_id;
                $jl_record->save();
            }

        }
        $notification = array(
            'message'       => 'Approve Successfully!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function cogs_project_expense(Request $request){
        $amount = $request->amount;
        $qty = $request->qty;
        $account_head = $request->head_id;
        $temp_pe = TempPE::where('token', $request->_token)->where('account_head_id', $request->head_id)->get();
        $projects = JobProject::all();
        return view('backend.purchase-expense.project-expense', compact('amount', 'account_head', 'temp_pe', 'qty', 'projects'));
    }

    public function cogs_project_expense_store(Request $request){
        TempPE::where('token', $request->_token)->where('account_head_id', $request->accout_head_id)->forceDelete();
        $project_ids = $request->project_id;
        if($project_ids){
            foreach($project_ids as $key => $id){
                if($id && $request->task_qty[$key]){
                    $pe = new TempPE;
                    $pe->token = $request->_token;
                    $pe->project_id = $id;
                    $pe->amount = $request->task_amount[$key];
                    $pe->qty = $request->task_qty[$key];
                    $pe->task_id = $request->task_id[$key];
                    // $pe->task_item_id = $request->task_item_id[$key];
                    $pe->account_head_id = $request->accout_head_id;
                    $pe->save();
                }
            }
        }
    }
    public function cogs_project_expense_edit(Request $request){
        $amount = $request->amount;
        $qty = $request->qty;
        $purchase_id = $request->purchase_id;
        $account_head = $request->head_id;
        $check_sub_head = substr($request->head_id,0,3);
        $sub_id = null;
        $account_head_id = null;
        if($check_sub_head == 'Sub'){
            $sub_id = substr($request->head_id,3);
        }else{
            $account_head_id = $request->head_id;
        }
        if($account_head_id){
            $temp_pe = TempCogsAssign::where('purchase_expense_id', $request->purchase_id)->where('account_head_id', $account_head_id)->where('sub_head_id', null)->get();
        }else{
            $temp_pe = TempCogsAssign::where('purchase_expense_id', $request->purchase_id)->where('sub_head_id', $sub_id)->get();
        }
        $projects = JobProject::all();
        return view('backend.purchase-expense.project-expense-edit', compact('amount', 'account_head', 'temp_pe', 'qty', 'projects', 'purchase_id'));
    }
    public function cogs_project_expense_update(Request $request){
        $check_sub_head = substr($request->accout_head_id,0,3);
        $sub_id = null;
        $account_head_id = null;
        if($check_sub_head == 'Sub'){
            $sub_id = substr($request->accout_head_id,3);
            $account_head_id = AccountSubHead::find($sub_id)->account_head_id;
        }else{
            $account_head_id = $request->accout_head_id;
        }
        if($account_head_id){
            TempCogsAssign::where('purchase_expense_id', $request->purchase_id)->where('sub_head_id', $sub_id)->forceDelete();

        }else{
            TempCogsAssign::where('purchase_expense_id', $request->purchase_id)->where('account_head_id', $account_head_id)->where('sub_head_id', null)->forceDelete();
        }
        $project_ids = $request->project_id;
        if($project_ids){
            foreach($project_ids as $key => $id){
                if($id && $request->task_qty[$key]){
                    $pe = new TempCogsAssign;
                    $pe->purchase_expense_id = $request->purchase_id;
                    $pe->project_id = $id;
                    $pe->amount = $request->task_amount[$key];
                    $pe->qty = $request->task_qty[$key];
                    $pe->task_id = $request->task_id[$key];
                    // $pe->task_item_id = $request->task_item_id[$key];
                    $pe->account_head_id = $account_head_id;
                    $pe->sub_head_id = $sub_id;
                    $pe->save();
                }
            }
        }
    }

    public function temp_cogs_clear(Request $request){
        TempPE::where('token', $request->_token)->forceDelete();
        return true;
    }

    public function expense_create_model_content(Request $request){
        Gate::authorize('Expense_Create');
        $from = $request->form_date ? $this->dateFormat($request->form_date) : ($request->to_date ? $this->dateFormat($request->to_date) : date('Y-m-d'));
        $to = $request->to_date ? $this->dateFormat($request->to_date) :  null;
        $projects = ProjectDetail::all();
        $modes = PayMode::whereNotIn('id',[5])->get();
        $sub_invoice = Carbon::now()->format('Ymd');
        $pInfos = PartyInfo::where('pi_type','Supplier')->get();
        $vats = VatRate::get();
        $purchase_expense_no = $this->purchase_expense_no();
        $account_heads = AccountHead::whereIn('account_type_id',[1,4])->where('fld_definition', '!=' ,'Cost of Sales / Goods Sold')->whereNotIn('master_account_id', [1])->get();
        $special_heads = AccountHead::where('fld_definition', 'Cost of Sales / Goods Sold')->get();
        $account_sub_heads = AccountSubHead::whereIn('office_id', [0, Auth::user()->office_id])->get();
        $master_details = MasterAccount::where('account_type_id', 4)->get();
        $special_master_details = MasterAccount::where('id', 3)->get();
        $units = Unit::all();
        TempPE::whereDate('created_at','<', today())->delete();
        $products = AccountHead::whereHas('stock') // only heads that have stock
        ->with(['sub_heads' => function ($q) {
            $q->whereHas('sub_stock'); // only sub_heads that have stock
        }])
        ->get();

        $expenses = PurchaseExpense::orderBy('date', 'desc')->paginate(40);
        $temp_expenses = PurchaseExpenseTemp::where('authorized', true)->orderBy('id', 'DESC')->paginate(40);
        return view('backend.purchase-expense.create', compact('projects', 'purchase_expense_no', 'modes',  'vats', 'pInfos', 'account_heads', 'account_sub_heads', 'master_details', 'units', 'special_heads', 'special_master_details', 'products', 'from', 'to', 'expenses', 'temp_expenses'));
    }

    public function inventory_create_model_content(Request $request){
        $products = AccountHead::whereHas('stock')
        ->with(['sub_heads' => function ($q) {
            $q->whereHas('sub_stock');
        }])
        ->get();
        $from = $request->form_date ? $this->dateFormat($request->form_date) : ($request->to_date ? $this->dateFormat($request->to_date) : date('Y-m-d'));
        $to = $request->to_date ? $this->dateFormat($request->to_date) :  null;
        return view('backend.inventory.stock-report', compact('products', 'from', 'to'));
    }

    public function expense_excel_import(Request $request){
        ExpenseImport::get()->each->forceDelete();
        $request->session()->put('token', $request->token);
        // $request->session()->put('project_id', $request->expense_project_name);
        Excel::import(new ExpenseExcelImport, $request->excel_file);
        return redirect()->route('check-excel-import');
    }

    public function check_excel_import(Request $request){
        $records = ExpenseImport::where('token', Session::get('token'))->get();
        $party = PartyInfo::where('pi_type', 'Supplier')->get();
        $account_heads = AccountHead::whereIn('account_type_id',[1,3])->whereNotIn('master_account_id', [1])->get();
        $special_heads = AccountHead::where('fld_definition', 'Cost of Sales / Goods Sold')->get();
        $projects = JobProject::all();
        return view('backend.purchase-expense.check-excel-import', compact('records', 'party', 'account_heads', 'special_heads', 'projects'));
    }
    public function delete_excel_truck_entry(Request $request){
        $record = ExpenseImport::find($request->id);
        $record->delete();
        return true;
    }

    public function final_excel_import(Request $request){
        DB::beginTransaction();
        $records = $request->date;
        try{
            foreach($records as $key => $record){
                $date = $this->dateFormat($record);
                $narration = $request->narration[$key];
                $project_id = $request->project_id[$key];
                $bill_no = $request->bill_no[$key];
                $description = $request->description[$key];
                $account_head = $request->account_head[$key];
                $amount = $request->amount[$key];
                $sub_head_id = null;
                $check_sub_head = substr($account_head,0,3);
                if($check_sub_head == 'Sub'){
                    $sub_head_id = substr($account_head,3);
                    $sub_head_info = AccountSubHead::find($sub_head_id);
                    $account_head_id = $sub_head_info->account_head_id;
                }else{
                    $account_head_id = $account_head;
                }
                // dd($date,$account_head_id,$account_head_id,$amount,$project_id);
                if($date && $account_head_id && $account_head_id && $amount && $project_id){
                    $onboard_project = JobProject::find($project_id);
                    $purch_ex                   = new PurchaseExpense();
                    $purch_ex->date             = $date;
                    $purch_ex->job_project_id   = $project_id;
                    $purch_ex->pay_mode         = 'Bank';
                    $purch_ex->purchase_no      = $this->temp_purchase_expense_no();
                    $purch_ex->invoice_no       = $request->vr[$key];
                    $purch_ex->project_id       = 0;
                    $purch_ex->invoice_type     = 'Tax Invoice';
                    $purch_ex->head_id          = 0;
                    $purch_ex->total_amount     = $amount;
                    $purch_ex->vat              = 0.00;
                    $purch_ex->amount           = $amount;
                    $purch_ex->party_id         = 896;
                    $purch_ex->narration        = $narration;
                    $purch_ex->head_details     = $bill_no;
                    $purch_ex->gst_subtotal     = 0.00;
                    $purch_ex->paid_amount      = $amount;
                    $purch_ex->due_amount       = 0.00;
                    $purch_ex->created_by       = Auth::id();
                    $purch_ex->authorized_by    = Auth::id();
                    $purch_ex->approved_by      = Auth::id();
                    $purch_ex->save();

                    $purchase_number                = InvoiceNumber::find(1);
                    $purchase_number->purchase_no   = $purch_ex->purchase_no;
                    $purchase_number->save();

                    $journal                        = new Journal();
                    $journal->project_id            = $purch_ex->project_id;
                    $journal->purchase_expense_id   = $purch_ex->id;
                    $journal->transection_type      = 'Purchase/Expense Entry';
                    $journal->transaction_type      = 'Increase';
                    $journal->journal_no            = $this->journal_no();
                    $journal->date                  = $purch_ex->date;
                    $journal->pay_mode              = $purch_ex->pay_mode;
                    $journal->cost_center_id        = 0;
                    $journal->party_info_id         = $purch_ex->party_id;
                    $journal->account_head_id       = 123;
                    $journal->voucher_type          = 'CREDIT';
                    $journal->amount                = $purch_ex->total_amount;
                    $journal->tax_rate              = 0;
                    $journal->vat_amount            = $purch_ex->vat;
                    $journal->total_amount          = $purch_ex->amount;
                    $journal->gst_subtotal          = 0;
                    $journal->narration             = $purch_ex->narration;
                    $journal->approved_by           = $purch_ex->approved_by;
                    $journal->authorized_by         = $purch_ex->authorized_by;
                    $journal->created_by            = $purch_ex->created_by;
                    $journal->save();

                    $sub_head = AccountSubHead::find($sub_head_id);
                    $ac_head = AccountHead::find($account_head_id);

                    $jl_record                      = new JournalRecord();
                    $jl_record->journal_id          = $journal->id;
                    $jl_record->project_details_id  = $journal->project_id;
                    $jl_record->cost_center_id      = $journal->cost_center_id;
                    $jl_record->party_info_id       = $journal->party_info_id;
                    $jl_record->journal_no          = $journal->journal_no;
                    $jl_record->sub_account_head_id = $sub_head?$sub_head->id:null;
                    $jl_record->account_head_id     = $ac_head->id;
                    $jl_record->master_account_id   = $ac_head->master_account_id;
                    $jl_record->account_head        = $ac_head->fld_ac_head;
                    $jl_record->amount              = $purch_ex->total_amount;
                    $jl_record->total_amount        = $purch_ex->total_amount;
                    $jl_record->vat_rate_id         = 0;
                    $jl_record->invoice_no          = 0;
                    $jl_record->transaction_type    = 'DR';
                    $jl_record->journal_date        = $journal->date;
                    $jl_record->is_main_head        = 1;
                    $jl_record->account_type_id     = $ac_head->account_type_id;
                    $jl_record->compnay_id          = $onboard_project->compnay_id??null;
                    $jl_record->save();

                    // purchase expense item
                    $purc_exp_itm = new PurchaseExpenseItem();
                    $purc_exp_itm->head_id              = $ac_head->id;
                    $purc_exp_itm->sub_head_id          = $sub_head?$sub_head->id:null;
                    $purc_exp_itm->item_description     = $request->bill_no[$key];
                    $purc_exp_itm->qty                  = 1;
                    $purc_exp_itm->unit_id              = $sub_head?$sub_head->unit_id:null;
                    $purc_exp_itm->rate                 = $purch_ex->total_amount;
                    $purc_exp_itm->amount               = $purch_ex->total_amount;
                    $purc_exp_itm->vat                  = 0.00;
                    $purc_exp_itm->total_amount         = $purch_ex->total_amount;
                    $purc_exp_itm->party_id             = $purch_ex->party_id;
                    $purc_exp_itm->purchase_expense_id  = $purch_ex->id;
                    $purc_exp_itm->gst_subtotal         = 0.00;
                    $purc_exp_itm->save();

                    $ac_head_dr = AccountHead::find(2);
                    $jl_record                      = new JournalRecord();
                    $jl_record->journal_id          = $journal->id;
                    $jl_record->project_details_id  = $journal->project_id;
                    $jl_record->cost_center_id      = $journal->cost_center_id;
                    $jl_record->party_info_id       = $journal->party_info_id;
                    $jl_record->journal_no          = $journal->journal_no;
                    $jl_record->account_head_id     = $ac_head_dr->id;
                    $jl_record->master_account_id   = $ac_head_dr->master_account_id;
                    $jl_record->account_head        = $ac_head_dr->fld_ac_head;
                    $jl_record->amount              = $purch_ex->total_amount;
                    $jl_record->total_amount        = $purch_ex->total_amount;
                    $jl_record->vat_rate_id         = 0;
                    $jl_record->invoice_no          = 0;
                    $jl_record->transaction_type    = 'CR';
                    $jl_record->journal_date        = $journal->date;
                    $jl_record->is_main_head        = 1;
                    $jl_record->account_type_id     = $ac_head_dr->account_type_id;
                    $jl_record->compnay_id          = $onboard_project->compnay_id??null;
                    $jl_record->save();

                    if($sub_head && $ac_head->id == 1758){
                        $journal                        = new Journal();
                        $journal->project_id            = $purch_ex->project_id;
                        $journal->purchase_expense_id   = $purch_ex->id;
                        $journal->transection_type      = 'Inventory';
                        $journal->transaction_type      = 'Increase';
                        $journal->journal_no            = $this->journal_no();
                        $journal->date                  = $purch_ex->date;
                        $journal->pay_mode              = $purch_ex->pay_mode;
                        $journal->cost_center_id        = 0;
                        $journal->party_info_id         = $purch_ex->party_id;
                        $journal->account_head_id       = 123;
                        $journal->voucher_type          = 'CREDIT';
                        $journal->amount                = $purch_ex->total_amount;
                        $journal->tax_rate              = 0;
                        $journal->vat_amount            = $purch_ex->vat;
                        $journal->total_amount          = $purch_ex->amount;
                        $journal->gst_subtotal          = 0;
                        $journal->narration             = $purch_ex->narration;
                        $journal->approved_by           = $purch_ex->approved_by;
                        $journal->authorized_by         = $purch_ex->authorized_by;
                        $journal->created_by            = $purch_ex->created_by;
                        $journal->save();

                        $sub_head = AccountSubHead::find($sub_head_id);
                        $ac_head = AccountHead::find($account_head_id);

                        $jl_record                      = new JournalRecord();
                        $jl_record->journal_id          = $journal->id;
                        $jl_record->project_details_id  = $journal->project_id;
                        $jl_record->cost_center_id      = $journal->cost_center_id;
                        $jl_record->party_info_id       = $journal->party_info_id;
                        $jl_record->journal_no          = $journal->journal_no;
                        $jl_record->sub_account_head_id = $sub_head?$sub_head->id:null;
                        $jl_record->account_head_id     = $ac_head->id;
                        $jl_record->master_account_id   = $ac_head->master_account_id;
                        $jl_record->account_head        = $ac_head->fld_ac_head;
                        $jl_record->amount              = $purch_ex->total_amount;
                        $jl_record->total_amount        = $purch_ex->total_amount;
                        $jl_record->vat_rate_id         = 0;
                        $jl_record->invoice_no          = 0;
                        $jl_record->transaction_type    = 'CR';
                        $jl_record->journal_date        = $journal->date;
                        $jl_record->is_main_head        = 1;
                        $jl_record->account_type_id     = $ac_head->account_type_id;
                        $jl_record->compnay_id          = $onboard_project->compnay_id??null;
                        $jl_record->save();

                        $ac_head_dr = AccountHead::find(1668);
                        $jl_record                      = new JournalRecord();
                        $jl_record->journal_id          = $journal->id;
                        $jl_record->project_details_id  = $journal->project_id;
                        $jl_record->cost_center_id      = $journal->cost_center_id;
                        $jl_record->party_info_id       = $journal->party_info_id;
                        $jl_record->journal_no          = $journal->journal_no;
                        $jl_record->account_head_id     = $ac_head_dr->id;
                        $jl_record->master_account_id   = $ac_head_dr->master_account_id;
                        $jl_record->account_head        = $ac_head_dr->fld_ac_head;
                        $jl_record->amount              = $purch_ex->total_amount;
                        $jl_record->total_amount        = $purch_ex->total_amount;
                        $jl_record->vat_rate_id         = 0;
                        $jl_record->invoice_no          = 0;
                        $jl_record->transaction_type    = 'DR';
                        $jl_record->journal_date        = $journal->date;
                        $jl_record->is_main_head        = 1;
                        $jl_record->account_type_id     = $ac_head_dr->account_type_id;
                        $jl_record->compnay_id          = $onboard_project->compnay_id??null;
                        $jl_record->save();
                    }
                    // payment voucher
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
                    $payment                = new Payment();
                    $payment->date          = $date;
                    $payment->paid_by       = Auth::id();
                    $payment->pay_mode      = 'Bank';
                    $payment->payment_no    = $payment_no;
                    $payment->head_id       = 0;
                    $payment->total_amount  = $amount;
                    $payment->vat           = 0;
                    $payment->party_id      = $purch_ex->party_id;
                    $payment->narration     = $purch_ex->narration;
                    $payment->paid_amount   = 0;
                    $payment->due_amount    = 0;
                    $payment->status        = 'Realised';
                    $payment->save();

                    $purc_exp_itm               = new PaymentInvoice();
                    $purc_exp_itm->sale_id      = $purch_ex->id;
                    $purc_exp_itm->payment_id   = $payment->id;
                    $purc_exp_itm->total_amount = $payment->total_amount;
                    $purc_exp_itm->vat          = 0;
                    $purc_exp_itm->amount       = $payment->total_amount;
                    $purc_exp_itm->party_id     = $payment->party_id;
                    $purc_exp_itm->save();

                    $payment_invoice = InvoiceNumber::first();
                    $payment_invoice->payment_no = $payment->payment_no;
                    $payment_invoice->save();

                }
                $check_expense_excel = ExpenseImport::find($request->temp_id[$key]);
                if($check_expense_excel){
                    $check_expense_excel->forceDelete();
                }
            }
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        $notification = array(
            'message'       => 'Approve successfull!',
            'alert-type'    => 'success'
        );
        return back()->with($notification);
    }
    public function subsidiary_create(Request $request){
        $amount = $request->amount;
        $qty = $request->qty;
        $account_head = $request->head_id;
        $temp_pe = TempSubsidiary::where('token', $request->_token)->where('account_head_id', $request->head_id)->get();
        $projects = Subsidiary::all();
        return view('backend.purchase-expense.sebsidiary-create', compact('amount', 'account_head', 'temp_pe', 'qty', 'projects'));
    }
    public function subsidiary_store(Request $request){
        TempSubsidiary::where('token', $request->_token)->where('account_head_id', $request->accout_head_id)->forceDelete();
        $project_ids = $request->company_id;
        if($project_ids){
            foreach($project_ids as $key => $id){
                if($id && $request->subsidiary_qty[$key]){
                    $pe = new TempSubsidiary;
                    $pe->token = $request->_token;
                    $pe->company_id = $id;
                    $pe->amount = $request->subsidiary_amount[$key];
                    $pe->qty = $request->subsidiary_qty[$key];
                    $pe->account_head_id = $request->accout_head_id;
                    $pe->save();
                }
            }
        }
    }
    public function subsidiary_edit(Request $request){
        $purchase_id = $request->purchase_id;
        $amount = $request->amount;
        $qty = $request->qty;
        $account_head = $request->head_id;
        $check_sub_head = substr($request->head_id,0,3);
        $sub_id = null;
        $account_head_id = null;
        if($check_sub_head == 'Sub'){
            $sub_id = substr($request->head_id,3);
        }else{
            $account_head_id = $request->head_id;
        }
        if($account_head_id){
            $temp_pe = SubsidiaryStore::where('purchase_id', $request->purchase_id)->where('account_head_id', $account_head_id)->where('sub_head_id', null)->get();
        }else{
            $temp_pe = SubsidiaryStore::where('purchase_id', $request->purchase_id)->where('sub_head_id', $sub_id)->get();
        }
        $projects = Subsidiary::all();
        return view('backend.purchase-expense.subsidiary-edit', compact('amount', 'account_head', 'temp_pe', 'qty', 'projects', 'purchase_id'));
    }
    public function subsidiary_update(Request $request){
        $check_sub_head = substr($request->accout_head_id,0,3);
        $sub_id = null;
        $account_head_id = null;
        if($check_sub_head == 'Sub'){
            $sub_id = substr($request->accout_head_id,3);
            $account_head_id = AccountSubHead::find($sub_id)->account_head_id;
        }else{
            $account_head_id = $request->accout_head_id;
        }
        if($account_head_id){
            SubsidiaryStore::where('purchase_id', $request->purchase_id)->where('sub_head_id', $sub_id)->forceDelete();
        }else{
            SubsidiaryStore::where('purchase_id', $request->purchase_id)->where('account_head_id', $account_head_id)->where('sub_head_id', null)->forceDelete();
        }
        // dd($sub_id);
        $project_ids = $request->company_id;
        if($project_ids){
            foreach($project_ids as $key => $id){
                if($id && $request->subsidiary_qty[$key]){
                    $pe = new SubsidiaryStore;
                    $pe->sub_head_id        = $sub_id;
                    $pe->account_head_id    = $account_head_id;
                    $pe->purchase_id        = $request->purchase_id;
                    $pe->company_id         = $id;
                    $pe->qty                = $request->subsidiary_qty[$key];
                    $pe->amount             = $request->subsidiary_amount[$key];
                    $pe->account_head_id    = $account_head_id;
                    $pe->save();
                }
            }
        }
    }
}

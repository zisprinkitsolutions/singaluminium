<?php

namespace App\Http\Controllers\backend;

use App\DebitCreditVoucher;
use App\Http\Controllers\Controller;
use App\JobProject;
use App\JobProjectInvoice;
use App\Journal;
use App\JournalRecord;
use App\JournalRecordsTemp;
use App\JournalTemp;
use App\Models\AccountHead;
use App\Models\CostCenter;
use App\Models\FundAllocation;
use App\PartyInfo;
use App\Payment;
use App\PayMode;
use App\PayTerm;
use App\ProjectDetail;
use App\PurchaseExpense;
use App\PurchaseExpenseItem;
use App\Receipt;
use App\TxnType;
use App\VatRate;
use App\NewProject;
use App\AccountSubHead;
use App\TempPartyPaymentAmount;
use App\PartyPaymentAmount;
use App\JournalEntryDocument;
use App\LpoProject;
use App\Models\InvoiceNumber;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate ;

class JournalEntryController extends Controller
{

    private function dateFormat($date)
    {
        $old_date = explode('/', $date);

        $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }
    private function purchase_expense_no()
    {
        $sub_invoice = Carbon::now()->format('y');
        // return $sub_invoice;
        $let_purch_exp = InvoiceNumber::where('purchase_no', 'LIKE', "%{$sub_invoice}%")->first();
        if ($let_purch_exp) {
            $purch_no = preg_replace('/^P/', '', $let_purch_exp->purchase_no);
            $purch_code = $purch_no + 1;
            $purch_no = "P" . $purch_code;
        } else {
            $purch_no = "P" . Carbon::now()->format('y') . '0001';
        }
        return $purch_no;
    }
    public function invoice_no()
    {
        $sub_invoice = 'TI' . Carbon::now()->format('y');
        $invoice = InvoiceNumber::where('invoice_no', 'LIKE', "%{$sub_invoice}%")->first();
        if ($invoice) {
            $number = preg_replace('/^' . $sub_invoice . '/', '', $invoice->invoice_no);
            $number++;
            if ($number < 10) {
                $invoice_no = $sub_invoice . '000' . $number;
            } elseif ($number < 100) {
                $invoice_no = $sub_invoice . '00' . $number;
            } elseif ($number < 1000) {
                $invoice_no = $sub_invoice . '0' . $number;
            } else {
                $invoice_no = $sub_invoice . $number;
            }
        } else {
            $invoice_no  = $sub_invoice . '0001';
        }
        return $invoice_no;
    }
    public function partyInfoInvoice2(Request $request)
    {
        $info = PartyInfo::where('id', $request->value)->first();

        $projects = JobProject::where('customer_id', $request->value)->orderBy('id','desc')->get();

        return response()->json(['info' => $info, 'projects' => $projects]);
    }

    public function new_journal(Request $request)
    {
        Gate::authorize('View');
        $mVoucherType = $request->mVoucherType;
        $voucherType = $request->voucherType;
        $journals = Journal::orderBy('id', 'desc');
        if ($request->text) {
            $journals = $journals->where('journal_no', 'like', '%' . $request->text . '%');
        }
        if ($request->date) {
            $journals = $journals->where('date', $request->date);
        }
        if ($request->voucherType) {
            $journals = $journals->where('voucher_type', $voucherType);
        }
        if ($request->from) {
            $from = $request->from;
            if ($request->to) {
                $to = $request->to;
            } else {
                $to = Carbon::now();
            }
            $journals = $journals->whereBetween('date', [$from, $to]);
        }
        $cal_total_amount = (clone $journals)->get()->sum('amount');
        $journals = $journals->paginate(300);
        $data['amount'] = $cal_total_amount;
        // select temp journal
        $journals_temp = JournalTemp::orderBy('id', 'desc');
        if ($request->text) {
            $journals_temp = $journals_temp->where('journal_no', 'like', '%' . $request->text . '%');
        }
        if ($request->date) {
            $journals_temp = $journals_temp->where('date', $request->date);
        }
        if ($request->voucherType) {
            $journals_temp = $journals_temp->where('voucher_type', $voucherType);
        }
        if ($request->from) {
            $from = $request->from;
            if ($request->to) {
                $to = $request->to;
            } else {
                $to = Carbon::now();
            }
            $journals_temp = $journals_temp->whereBetween('date', [$from, $to]);
        }
        $journals_temp = $journals_temp->get();

        // return view('backend.journal.new-journal', compact('journals', 'journals_temp', 'data'));
        return view('backend.journal.new-journal-check', compact('journals', 'journals_temp', 'data'));
    }

    public function voucher_preview_modal(Request $request)
    {
        // dd($request->all());
        if ($request->v_type == 'main') {
            $journal = Journal::find($request->id);
            $journal_record = JournalRecord::where('journal_id', $request->id)->get();
            // dd($journal_record);
        } elseif ($request->v_type == 'temp') {
            $journal = JournalTemp::find($request->id);
        } elseif ($request->v_type == 'invoice') {
            $is_show = 'yes';
            $sale = JobProjectInvoice::find($request->id);
            return view('backend.sale.preview', compact('sale', 'is_show'));
        } elseif ($request->v_type == 'receipt') {

            $recept = Receipt::find($request->id);
            return view('backend.sale.receipt-preview', compact('recept'));
        } elseif ($request->v_type == 'payment') {
            $payment = Payment::find($request->id);
            return view('backend.purchase-expense.payment-preview', compact('payment'));
        } elseif ($request->v_type == 'purchase') {

            $purchase_exp = PurchaseExpense::find($request->id);
            return view('backend.purchase-expense.preview', compact('purchase_exp'));
        } elseif ($request->v_type == 'fund_allocation') {
            $fund = FundAllocation::find($request->id);
            return view('backend.fund-allocation.preview', compact('fund'));
        } elseif ($request->v_type == 'project') {
            $is_show = 'yes';
            $project = JobProject::find($request->id);
            return view('backend.job-project.view', compact('project', 'is_show'));
        }
        return view('backend.journal.new-preview', compact('journal'));
    }

    public function new_journal_creation(Request $records)
    {
        Gate::authorize('Accounting_Create');
        TempPartyPaymentAmount::whereDate('created_at', '<', today())->forceDelete();
        $projects = ProjectDetail::get();
        $modes = PayMode::all();
        $terms = PayTerm::all();
        $sub_invoice = Carbon::now()->format('Ymd');
        $cCenters = CostCenter::all();
        $txnTypes = TxnType::all();
        $acHeads = AccountHead::all();
        $pInfos = PartyInfo::whereIn('office_id', [0, Auth::user()->office_id])->get();
        $vats = VatRate::orderBy('id', 'desc')->get();
        $latest_journal_no = Journal::withTrashed()->whereDate('created_at', Carbon::today())->where('journal_no', 'LIKE', "%{$sub_invoice}%")->where('office_id', Auth::user()->office_id)->orderBy('id', 'desc')->first();
        if ($latest_journal_no) {
            $journal_no = substr($latest_journal_no->journal_no, 0, -1);
            $journal_code = $journal_no + 1;
            $journal_no = $journal_code . "J";
        } else {
            $journal_no = Carbon::now()->format('Ymd') . '001' . "J";
        }
        return view('backend.journal.new-journal-creation', compact('projects', 'journal_no', 'modes', 'terms', 'cCenters', 'txnTypes', 'acHeads', 'vats', 'pInfos'));
    }

    public function transection_heads(Request $request)
    {
        if ($request->value == 'Purchase/Expense Entry') {
            $acHeads = AccountHead::where('account_type_id', '1')->where('fld_definition', 'Sell of Asset')
                ->orWhere(function ($query) {
                    $query->where('account_type_id', '4');
                })
                ->get();
        } elseif ($request->value == 'Sales Entry') {
            $acHeads = AccountHead::where('account_type_id', '3')->get();
        } elseif ($request->value == 'Payment Voucher') {
            $acHeads = AccountHead::where('account_type_id', '2')->get();
        } elseif ($request->value == 'Receipt Voucher') {
            $acHeads = AccountHead::where('account_type_id', '1')->where('fld_definition', '!=', 'Sell of Asset')->where('pay_mode_id', null)->get();
        }
        return view('backend.journal.transection-head', compact('acHeads'));
    }


    public function journalEntryPost(Request $request)
    {
        Gate::authorize('Accounting_Create');
        // dd($request->all());
        $request->validate(
            [
                'date'              =>  'required',
                'narration'         => 'required'
            ],
            [
                'date.required'         => 'Date is required',
                'narration.required'    => 'Narration is required',
            ]
        );
        $date_format = $this->dateFormat($request->date);
        $multi_head = $request->input('group-a');
        $sub_invoice = Carbon::now()->format('Ymd');
        $latest_journal_no = Journal::withTrashed()->whereDate('created_at', Carbon::today())->where('journal_no', 'LIKE', "%{$sub_invoice}%")->orderBy('id', 'desc')->first();
        // return $latest_journal_no;
        if ($latest_journal_no) {
            $journal_no = substr($latest_journal_no->journal_no, 0, -1);
            $journal_code = $journal_no + 1;
            $journal_no = $journal_code . "J";
        } else {
            $journal_no = Carbon::now()->format('Ymd') . '001' . "J";
        }
        $journal = new JournalTemp();
        $journal->office_id        = Auth::user()->office_id;
        $journal->project_id        = $request->project;
        $journal->transection_type  = 'N/A';
        $journal->transaction_type  = 'N/A';
        $journal->journal_no        = $journal_no;
        $journal->date              = $date_format;
        $journal->pay_mode          = $request->pay_mode ? $request->pay_mode : 'N/A';
        $journal->cost_center_id    = 0;
        $journal->party_info_id     = 0;
        $journal->account_head_id   = 123;
        $journal->amount            = $request->total_debit;
        $journal->tax_rate          = 0;
        $journal->vat_amount        = $request->gst_total;
        $journal->total_amount      = $request->total_debit;
        $journal->without_gst       = $request->vat_subtotal;
        $journal->narration         = $request->narration;
        $journal->created_by        = Auth::id();
        $journal->save();
        $type = '';
        if ($request->hasFile('voucher_scan')) {
            $files = $request->file('voucher_scan');
            foreach ($files as $file) {
                $document = new JournalEntryDocument();
                $ext = $file->getClientOriginalExtension();
                $name = time() . '.' . $ext;
                $file->storeAs('public/upload/journal-entry-expense', $name);
                $document->journal_no = $journal->journal_no;
                $document->file_name = $name;
                $document->ext = $ext;
                $document->save();
            }
        }
        $party_amount = TempPartyPaymentAmount::where('token', $request->_token)->get();
        foreach ($party_amount as $party_ant) {
            $p_amount = new PartyPaymentAmount;
            $p_amount->journal_no = $journal->journal_no;
            $p_amount->party_id = $party_ant->party_id;
            $p_amount->amount = $party_ant->amount;
            $p_amount->account_head_id = $party_ant->account_head_id;
            $p_amount->sub_head_id = $party_ant->sub_head_id;
            $p_amount->save();
            $party_ant->forceDelete();
        }
        foreach ($multi_head as $each_head) {
            if ($each_head['debit_amount'] || $each_head['credit_amount']) {
                $check_sub_head = substr($each_head['multi_acc_head'], 0, 3);
                $sub_head = null;
                if ($check_sub_head == 'Sub') {
                    $sub_head = substr($each_head['multi_acc_head'], 3);
                    $sub_ac_head = AccountSubHead::find($sub_head);
                    $ac_head = AccountHead::find($sub_ac_head->account_head_id);
                } else {
                    $ac_head = AccountHead::find($each_head['multi_acc_head']);
                }
                $jl_record = new JournalRecordsTemp();
                $jl_record->journal_temp_id     = $journal->id;
                $jl_record->office_id           = $journal->office_id;
                $jl_record->project_details_id  = $request->project;
                $jl_record->cost_center_id      = 1;
                $jl_record->party_info_id       = $request->party_info;
                $jl_record->journal_no          = $journal_no;
                $jl_record->description         = $each_head['description'];
                $jl_record->account_head_id     = $ac_head->id;
                $jl_record->sub_account_head_id = $sub_head;
                $jl_record->master_account_id   = $ac_head->master_account_id;
                $jl_record->account_head        = $ac_head->fld_ac_head;
                $jl_record->amount              = $each_head['debit_amount'] ? $each_head['debit_amount'] : $each_head['credit_amount'];
                $jl_record->total_amount        = $jl_record->amount;
                $jl_record->gst_subtotal        = 0.00;
                $jl_record->gst_amount          = 0.00;
                $jl_record->vat_rate_id         = 0;
                $jl_record->transaction_type    = $each_head['debit_amount'] ? 'DR' : 'CR';
                $jl_record->journal_date        = $date_format;
                $jl_record->is_main_head        = 1;
                $jl_record->account_type_id     = $ac_head->account_type_id;
                $jl_record->save();
            }
        }

        //Debit Voucher Or Credit Voucher
        $voucher_type = "DR";
        if (($request->pay_mode == 'Cash' || $request->pay_mode == 'Card')  && ($type == 'DR')) {
            // if it is expense or asset
            $voucher_type = 'DR';
        } elseif (($request->pay_mode == 'Cash' || $request->pay_mode == 'Card')  && ($type == 'CR')) {
            // if it is income, liability or equity
            $voucher_type = 'CR';
        } elseif ($request->pay_mode == 'Credit') {
            $voucher_type            = 'JOURNAL';
        }

        $journal->voucher_type          = $voucher_type;
        $journal->save();


        return redirect()->route('journal-success', $journal->id)->with('success', "Successfully Added");
    }

    public function journal_success($id)
    {
        // return $id;
        $journal = JournalTemp::find($id);

        return view('backend.journal.journal-success', compact('journal'));
    }

    public function journal_edit($id)
    {
        // return $id;
        $journalF = JournalTemp::find($id);
        // return $journal->records->where('is_main_head',1);
        $projects = ProjectDetail::get();
        $modes = PayMode::all();
        $terms = PayTerm::all();
        $sub_invoice = Carbon::now()->format('Ymd');
        $cCenters = CostCenter::all();
        $txnTypes = TxnType::all();
        $acHeads = AccountHead::all();
        $pInfos = PartyInfo::whereIn('office_id', [0, Auth::user()->office_id])->get();
        $vats = VatRate::orderBy('id', 'desc')->get();
        if ($journalF->transection_type == 'Purchase/Expense Entry') {
            $acHeads = AccountHead::where('account_type_id', '1')->where('fld_definition', 'Sell of Asset')
                ->orWhere(function ($query) {
                    $query->where('account_type_id', '4');
                })
                ->get();
        } elseif ($journalF->transection_type == 'Sales Entry') {
            $acHeads = AccountHead::where('account_type_id', '3')->get();
        } elseif ($journalF->transection_type == 'Payment Voucher') {
            $acHeads = AccountHead::where('account_type_id', '2')->get();
        } elseif ($journalF->transection_type == 'Receipt Voucher') {
            $acHeads = AccountHead::where('account_type_id', '1')->where('fld_definition', '!=', 'Sell of Asset')->where('pay_mode_id', null)->get();
        }


        return view('backend.journal.journal-edit', compact('acHeads', 'projects', 'journalF', 'modes', 'terms', 'cCenters', 'txnTypes', 'acHeads', 'vats', 'pInfos'));
    }


    public function journalEntryEditPost(Request $request, $journal)
    {
        Gate::authorize('Accounting_Edit');
        // dd($request->all());
        $request->validate(
            [
                'date'              =>  'required',
                'narration'         => 'required'
            ],
            [
                'date.required'         => 'Date is required',
                'narration.required'    => 'Narration is required',
            ]
        );
        $date_format = $this->dateFormat($request->date);

        $multi_head = $request->input('group-a');
        // dd($multi_head);


        // voucher scan upload
        if ($request->hasFile('voucher_scan')) {
            $voucher_scan = $request->file('voucher_scan');
            $name = $voucher_scan->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $voucher_scan->getClientOriginalExtension();
            $voucher_file_name = $name . time() . '.' . $ext;
            $voucher_scan->storeAs('public/upload/documents', $voucher_file_name);
        }


        if ($request->hasFile('voucher_scan2')) {
            $voucher_scan2 = $request->file('voucher_scan2');
            $name = $voucher_scan2->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $voucher_scan2->getClientOriginalExtension();
            $voucher_file_name2 = $name . time() . '.' . $ext;
            $voucher_scan2->storeAs('public/upload/documents2', $voucher_file_name2);
        }

        $cost_center_id = 0;
        if ($request->cost_center_name != null) {
            $cost_center_id = $request->cost_center_name;
        }

        if ($request->transection_type == 'Purchase/Expense Entry' || $request->transection_type == 'Expense Entry' || $request->transection_type == 'Payment Voucher') {
            if ($request->transaction_type == 'Increase') {
                $transection_head_type = 'DR';
            } else {
                $transection_head_type = 'CR';
            }
        } elseif ($request->transection_type == 'Sales Entry' || $request->transection_type == 'Receipt Voucher') {
            if ($request->transaction_type == 'Increase') {
                $transection_head_type = 'CR';
            } else {
                $transection_head_type = 'DR';
            }
        } else {
            if ($request->transaction_type == 'Increase') {
                $transection_head_type = 'DR';
            } else {
                $transection_head_type = 'CR';
            }
        }

        $journal = JournalTemp::find($journal);
        $journal->project_id        = $request->project;
        $journal->transection_type  = 'N/A';
        $journal->transaction_type  = $request->transaction_type;
        $journal->date              = $date_format;
        $journal->pay_mode          = $request->pay_mode ? $request->pay_mode : 'N/A';
        $journal->cost_center_id    = $cost_center_id;
        $journal->party_info_id     = 0;
        $journal->account_head_id   = 123;
        $journal->amount            = $request->total_debit;
        $journal->tax_rate          = 0;
        $journal->vat_amount        = 0.00;
        $journal->total_amount      = 0.00;
        $journal->without_gst       = 0.00;
        $journal->narration         = $request->narration;
        $journal->created_by        = Auth::id();
        if ($request->hasFile('voucher_scan')) {
            $journal->voucher_scan  = $voucher_file_name;
        }
        if ($request->hasFile('voucher_scan2')) {
            $journal->voucher_scan2  = $voucher_file_name2;
        }
        $journal->save();
        $type = '';
        $journal->records()->forceDelete();
        foreach ($multi_head as $each_head) {
            if ($each_head['debit_amount'] || $each_head['credit_amount']) {
                $check_sub_head = substr($each_head['multi_acc_head'], 0, 3);
                $sub_head = null;
                if ($check_sub_head == 'Sub') {
                    $sub_head = substr($each_head['multi_acc_head'], 3);
                    $sub_ac_head = AccountSubHead::find($sub_head);
                    $ac_head = AccountHead::find($sub_ac_head->account_head_id);
                } else {
                    $ac_head = AccountHead::find($each_head['multi_acc_head']);
                }
                $jl_record = new JournalRecordsTemp();
                $jl_record->journal_temp_id     = $journal->id;
                $jl_record->office_id           = $journal->office_id;
                $jl_record->project_details_id  = $request->project;
                $jl_record->cost_center_id      = $cost_center_id;
                $jl_record->party_info_id       = $request->party_info;
                $jl_record->journal_no          = $journal->journal_no;
                $jl_record->description         = $each_head['description'];
                $jl_record->account_head_id     = $ac_head->id;
                $jl_record->sub_account_head_id = $sub_head;
                $jl_record->master_account_id   = $ac_head->master_account_id;
                $jl_record->account_head        = $ac_head->fld_ac_head;
                $jl_record->amount              = $each_head['debit_amount'] ? $each_head['debit_amount'] : $each_head['credit_amount'];
                $jl_record->total_amount        = $jl_record->amount;
                $jl_record->gst_subtotal        = 0.00;
                $jl_record->gst_amount          = 0.00;
                $jl_record->vat_rate_id         = 0;
                $jl_record->transaction_type    = $each_head['debit_amount'] ? 'DR' : 'CR';
                $jl_record->journal_date        = $date_format;
                $jl_record->is_main_head        = 1;
                $jl_record->account_type_id     = $ac_head->account_type_id;
                $jl_record->save();
            }
        }

        // vat entry to journal
        // if($request->gst_total>0){
        //     if($request->transection_type=='Purchase/Expense Entry' || $request->transection_type=='Expense Entry')
        //         {
        //             $vat_ac_head= AccountHead::find(18); // vat account head

        //         }
        //         else
        //         {
        //             $vat_ac_head= AccountHead::find(17); // vat account head

        //         }            $jl_record= new JournalRecordsTemp();
        //     $jl_record->journal_temp_id     = $journal->id;
        //     $jl_record->project_details_id  = $request->project;
        //     $jl_record->cost_center_id      = $cost_center_id;
        //     $jl_record->party_info_id       = $request->party_info;
        //     $jl_record->journal_no          = $journal->journal_no;
        //     $jl_record->account_head_id     = $vat_ac_head->id;
        //     $jl_record->master_account_id   = $vat_ac_head->master_account_id;
        //     $jl_record->account_head        = $vat_ac_head->fld_ac_head;
        //     $jl_record->amount              = $request->gst_total;
        //     $jl_record->total_amount        = $request->gst_total;
        //     $jl_record->gst_subtotal        =  0;

        //     $jl_record->vat_rate_id         = 0;
        //     $jl_record->transaction_type    =  $transection_head_type;
        //     $jl_record->journal_date        = $date_format;
        //     $jl_record->account_type_id = $vat_ac_head->account_type_id;

        //     $jl_record->is_main_head        = 0;
        //     $jl_record->save();
        // }

        // // Opposit entry of journal
        // if($request->pay_mode=='Cash' || $request->pay_mode=='Card'){
        //     if($request->pay_mode=='Cash'){
        //         $ac_head= AccountHead::find(1);
        //     }else{
        //         $ac_head= AccountHead::find(2);
        //     }
        //     $opposit_type= $transection_head_type=='DR' ? 'CR' : 'DR';
        //     $jl_record= new JournalRecordsTemp();
        //     $jl_record->journal_temp_id     = $journal->id;
        //     $jl_record->project_details_id  = $request->project;
        //     $jl_record->cost_center_id      = $cost_center_id;
        //     $jl_record->party_info_id       = $request->party_info;
        //     $jl_record->journal_no          = $journal->journal_no;
        //     $jl_record->account_head_id     = $ac_head->id;
        //     $jl_record->master_account_id   = $ac_head->master_account_id;
        //     $jl_record->account_head        = $ac_head->fld_ac_head;
        //     $jl_record->amount              =  $request->total_amount;
        //     $jl_record->total_amount        =  $request->total_amount;
        //     $jl_record->gst_subtotal        =  0;

        //     $jl_record->vat_rate_id         = 0;
        //     $jl_record->transaction_type    = $opposit_type;
        //     $jl_record->journal_date        = $date_format;
        //     $jl_record->account_type_id = $ac_head->account_type_id;
        //     $jl_record->is_main_head        = 0;
        //     $jl_record->save();

        // }elseif($request->pay_mode=='Credit'){
        //     if($request->transection_type=='Purchase/Expense Entry' || $request->transection_type=='Expense Entry'){
        //         $ac_head= AccountHead::find(28); // accounts payable
        //     }else{
        //         $ac_head= AccountHead::find(3); // accounts payable
        //     }
        //     $opposit_type= $transection_head_type=='DR' ? 'CR' : 'DR';

        //     $jl_record= new JournalRecordsTemp();
        //     $jl_record->journal_temp_id     = $journal->id;
        //     $jl_record->project_details_id  = $request->project;
        //     $jl_record->cost_center_id      = $cost_center_id;
        //     $jl_record->party_info_id       = $request->party_info;
        //     $jl_record->journal_no          = $journal->journal_no;
        //     $jl_record->account_head_id     = $ac_head->id;
        //     $jl_record->master_account_id   = $ac_head->master_account_id;
        //     $jl_record->account_head        = $ac_head->fld_ac_head;
        //     $jl_record->amount              =  $request->total_amount;
        //     $jl_record->total_amount        =  $request->total_amount;
        //     $jl_record->gst_subtotal        =  0;

        //     $jl_record->vat_rate_id         = 0;
        //     $jl_record->transaction_type    = $opposit_type;
        //     $jl_record->journal_date        = $date_format;
        //     $jl_record->account_type_id = $ac_head->account_type_id;

        //     $jl_record->is_main_head        = 0;
        //     $jl_record->save();

        // }elseif($request->pay_mode == 'NonCash'){
        //     // Non cash credit
        //     $opposit_noncash= $transection_head_type=='DR' ? 'CR' : 'DR';

        //     $ac_head_2= AccountHead::find($request->acc_head_2);

        //     $jl_record= new JournalRecordsTemp();
        //     $jl_record->journal_temp_id     = $journal->id;
        //     $jl_record->project_details_id  = $request->project;
        //     $jl_record->cost_center_id      = $cost_center_id;
        //     $jl_record->party_info_id       = $request->party_info;
        //     $jl_record->journal_no          = $journal->journal_no;
        //     $jl_record->account_head_id     = $request->acc_head_2;
        //     $jl_record->master_account_id   = $ac_head_2->master_account_id;
        //     $jl_record->account_head        = $ac_head_2->fld_ac_head;
        //     $jl_record->amount              =  $request->total_amount;
        //     $jl_record->total_amount        =  $request->total_amount;
        //     $jl_record->gst_subtotal        =  0;

        //     $jl_record->vat_rate_id         = 0;
        //     $jl_record->transaction_type    = $opposit_noncash;
        //     $jl_record->journal_date        = $date_format;
        //     $jl_record->account_type_id = $ac_head_2->account_type_id;

        //     $jl_record->is_main_head        = 0;
        //     $jl_record->save();
        // }


        //Debit Voucher Or Credit Voucher
        $voucher_type = "DR";
        if (($request->pay_mode == 'Cash' || $request->pay_mode == 'Card')  && ($type == 'DR')) {
            // if it is expense or asset
            $voucher_type = 'DR';
        } elseif (($request->pay_mode == 'Cash' || $request->pay_mode == 'Card')  && ($type == 'CR')) {
            // if it is income, liability or equity
            $voucher_type = 'CR';
        } elseif ($request->pay_mode == 'Credit') {
            $voucher_type            = 'JOURNAL';
        }

        $journal->voucher_type          = $voucher_type;
        $journal->save();


        return redirect()->route('journal-success', $journal->id)->with('success', "Successfully Added");
    }

    public function partyInfoInvoice3(Request $request)
    {
        $info = PartyInfo::where('pi_code', $request->value)->whereIn('office_id', [0, Auth::user()->office_id])->first();
        return $info;
    }
    public function journal_authorization_section(Request $request)
    {
        Gate::authorize('Accounting_Authorize');
        //Gate::authorize('app.journal_authorize');
        $journals = JournalTemp::where('authorized', 0)->orderBy('id', 'DESC')->where('office_id', Auth::user()->office_id)->get();
        return view('backend.journal.new-journal-authorize', compact('journals'));
    }
    public function journal_authorize_show_modal(Request $request)
    {
        $journal = JournalTemp::find($request->id);
        if (!$journal) {
            return back()->with('error', "Not Found");
        }
        return view('backend.journal.new-journa-authorize-view', compact('journal'));
    }

    public function tem_journal_view_pdf($id)
    {
        $journal = JournalTemp::find($id);
        $pdf = Pdf::loadView('backend.journal.new-journal-preview-pdf', compact('journal'));
        return $pdf->download('journal-' . $journal->journal_no . '.pdf');
        // return view('backend.journal.new-preview', compact('journal'));
    }
    public function journalDelete($journal)
    {
        $journal = JournalTemp::find($journal);
        if (!$journal) {
            return back()->with('error', "Not Found");
        }
        $journal->records()->delete();
        $journal->forceDelete();
        return back()->with('success', 'Deleted Successfully');
        // return redirect()->route('journalEntry')->with('success','Deleted Successfully');
    }
    
      public function journal_delete($id)
    {
        $journal = Journal::findOrFail($id);

        // Check if journal is linked with any source transaction
        if (!$journal->is_deletable)  {
            return back()->with(['alert-type' => 'warning', 'message' => 'This journal cannot be deleted because it is linked to another transaction.']);
        }

        JournalRecord::where('journal_id',$journal->id)->forceDelete();
        $journal->forceDelete();

        // Proceed with delete

        return back()->with(['alert-type' => 'success', 'message' => 'The journal has been deleted']);
    }
    public function journalMakeAuthorize($journal)
    {
        $journal = JournalTemp::find($journal);
        if (!$journal) {
            return back()->with('error', "Not Found");
        }
        $journal->authorized = true;
        $journal->authorized_by = Auth::id();
        $journal->save();
        return back()->with('success', 'Successfully Authorized');
        // return redirect()->route('journalAuthorize')->with('success','Successfully Authorized');
    }

    public function journal_approval_section(Request $request)
    {
        Gate::authorize('Accounting_Approve');
        $journals = JournalTemp::where('authorized', 1)->latest()->where('office_id', Auth::user()->office_id)->get();
        return view('backend.journal.new-journal-approval', compact('journals'));
    }
    public function journal_approval_show_modal(Request $request)
    {
        $journal = JournalTemp::find($request->id);
        if (!$journal) {
            return back()->with('error', "Not Found");
        }
        return view('backend.journal.new-journa-approval-view', compact('journal'));
    }


    public function journalMakeApprove($journal)
    {
        $journal = JournalTemp::find($journal);
        // dd($journal->records);
        if (!$journal) {
            return back()->with('error', "Not Found");
        }
        $journal_no = $journal->journal_no;
        $ApproveJournal = new Journal();
        $ApproveJournal->office_id = Auth::user()->office_id;
        $ApproveJournal->transection_type  = $journal->transection_type;
        $ApproveJournal->transaction_type  = $journal->transaction_type;
        $ApproveJournal->without_gst  = $journal->without_gst;
        $ApproveJournal->project_id = $journal->project_id;
        $ApproveJournal->journal_no = $journal_no;
        $ApproveJournal->date = $journal->date;
        $ApproveJournal->invoice_no = $journal->invoice_no;
        $ApproveJournal->cost_center_id = $journal->cost_center_id;
        $ApproveJournal->party_info_id = $journal->party_info_id ?? 0;
        $ApproveJournal->account_head_id = $journal->account_head_id;
        $ApproveJournal->created_by = $journal->created_by;
        $ApproveJournal->editedby_id = $journal->editedby_id;
        $ApproveJournal->authorized = $journal->authorized;
        $ApproveJournal->approved = true;
        $ApproveJournal->pay_mode = $journal->pay_mode ?? 'n/a';
        $ApproveJournal->amount = $journal->amount;
        $ApproveJournal->tax_rate = $journal->tax_rate;
        $ApproveJournal->vat_amount = $journal->vat_amount;
        $ApproveJournal->total_amount = $journal->total_amount;
        $ApproveJournal->narration = $journal->narration;
        $ApproveJournal->voucher_scan = $journal->voucher_scan;
        $ApproveJournal->purchase_id = $journal->purchase_id;
        $ApproveJournal->invoice_id = $journal->invoice_id;
        $ApproveJournal->receipt_id = $journal->receipt_id;
        $ApproveJournal->payment_id = $journal->payment_id;
        $ApproveJournal->payment_id = $journal->payment_id;
        $ApproveJournal->purchase_expense_id = $journal->purchase_expense_id;
        $ApproveJournal->donation_id = $journal->donation_id;
        $ApproveJournal->applicatio_fee_id = $journal->applicatio_fee_id;
        $ApproveJournal->gst_subtotal = $journal->gst_subtotal;
        $ApproveJournal->approved_by = Auth::id();
        $ApproveJournal->authorized_by          = $journal->authorized_by;
        $ApproveJournal->editedby_id          = $journal->editedby_id;
        $ApproveJournal->created_by = $journal->created_by;
        $ApproveJournal->voucher_type = 'JOURNAL';
        $ApproveJournal->save();

        foreach ($journal->records as $item) {
            if ($item->sub_account_head_id) {
                $amount_record = PartyPaymentAmount::where('journal_no', $ApproveJournal->journal_no)->where('account_head_id', $item->account_head_id)->where('sub_head_id', $item->sub_account_head_id)->get();
            } else {
                $amount_record = PartyPaymentAmount::where('journal_no', $ApproveJournal->journal_no)->where('account_head_id', $item->account_head_id)->get();
            }
            if (count($amount_record) > 0) {
                $amount = $item->total_amount;
                foreach ($amount_record as $p_amount) {
                    $appJournalRec = new JournalRecord();
                    $appJournalRec->journal_id          = $ApproveJournal->id;
                    $appJournalRec->office_id           = $ApproveJournal->office_id;
                    $appJournalRec->project_details_id  = $item->project_details_id;
                    $appJournalRec->cost_center_id      = $item->cost_center_id;
                    $appJournalRec->party_info_id       = $p_amount->party_id;
                    $appJournalRec->gst_amount          = $item->gst_amount;
                    $appJournalRec->journal_no          = $ApproveJournal->journal_no;
                    $appJournalRec->account_head_id     = $item->account_head_id;
                    $appJournalRec->sub_account_head_id = $item->sub_account_head_id;
                    $appJournalRec->master_account_id   = $item->master_account_id;
                    $appJournalRec->account_head        = $item->account_head;
                    $appJournalRec->invoice_no          = $item->invoice_no;
                    $appJournalRec->amount              = $p_amount->amount;
                    $appJournalRec->total_amount        = $p_amount->amount;
                    $appJournalRec->transaction_type    = $item->transaction_type;
                    $appJournalRec->journal_date        = $item->journal_date;
                    $appJournalRec->vat_rate_id         = $item->vat_rate_id;
                    $appJournalRec->is_main_head        = $item->is_main_head;
                    $appJournalRec->account_type_id     = $item->account_type_id;
                    $appJournalRec->gst_subtotal        = $item->gst_subtotal;
                    $appJournalRec->save();

                    if ($appJournalRec->transaction_type == 'DR') {

                        $invoice = new JobProjectInvoice();
                        $invoice->invoice_no =  $this->invoice_no();
                        $invoice->job_project_id = 0;
                        $invoice->customer_id = $p_amount->party_id;
                        $invoice->budget = $p_amount->amount;
                        $invoice->discount = 0;
                        $invoice->total_due_amount_percentage = 100;
                        $invoice->total_budget = $p_amount->amount;
                        $invoice->due_amount = $p_amount->amount;
                        $invoice->vat = 0;
                        $invoice->paid_amount = 0;
                        $invoice->invoice_type = 'Tax Invoice';
                        $invoice->date = date('Y-m-d');
                        $invoice->created_by = Auth::id();
                        $invoice->updated_by = Auth::id();
                        $invoice->authorized_by = Auth::id();
                        $invoice->approved_by = auth()->id();
                        $invoice->pay_mode = 'Credit';
                        $invoice->save();

                        $invoice_number = InvoiceNumber::first();
                        $invoice_number->invoice_no = $invoice->invoice_no;
                        $invoice_number->save();
                    } else {
                        $purch_ex = new PurchaseExpense();
                        $purch_ex->date = date('Y-m-d');
                        $purch_ex->job_project_id = 0;
                        $purch_ex->pay_mode =  'Credit';
                        $purch_ex->purchase_no = $this->purchase_expense_no();;
                        $purch_ex->invoice_no = 'n/a';
                        $purch_ex->project_id = $ApproveJournal->project_id;
                        $purch_ex->head_id = $p_amount->account_head_id;
                        $purch_ex->invoice_type = 'n/a';
                        $purch_ex->total_amount = $p_amount->amount;
                        $purch_ex->vat = 0;
                        $purch_ex->amount = $p_amount->amount;
                        $purch_ex->party_id =  $p_amount->party_id;
                        $purch_ex->narration = $ApproveJournal->narration;
                        $purch_ex->gst_subtotal = 0.00;
                        $purch_ex->created_by = Auth::id();
                        $purch_ex->paid_amount = 0.00;
                        $purch_ex->due_amount = $p_amount->amount;
                        $purch_ex->authorized_by = Auth::id();
                        $purch_ex->paid_by = Auth::id();
                        $purch_ex->approved_by    = Auth::id();
                        $purch_ex->save();

                        $invoice = InvoiceNumber::first();
                        $invoice->purchase_no = $purch_ex->purchase_no;
                        $invoice->save();
                    }
                    $amount -= $p_amount->amount;
                }
                if ($amount > 0) {
                    $appJournalRec = new JournalRecord();
                    $appJournalRec->journal_id          = $ApproveJournal->id;
                    $appJournalRec->office_id           = $ApproveJournal->office_id;
                    $appJournalRec->project_details_id  = $item->project_details_id;
                    $appJournalRec->cost_center_id      = $item->cost_center_id;
                    $appJournalRec->party_info_id       = $item->party_info_id ?? 0;
                    $appJournalRec->gst_amount          = 0.00;
                    $appJournalRec->journal_no          = $ApproveJournal->journal_no;
                    $appJournalRec->account_head_id     = $item->account_head_id;
                    $appJournalRec->sub_account_head_id = $item->sub_account_head_id;
                    $appJournalRec->master_account_id   = $item->master_account_id;
                    $appJournalRec->account_head        = $item->account_head;
                    $appJournalRec->invoice_no          = $item->invoice_no;
                    $appJournalRec->amount              = $amount;
                    $appJournalRec->total_amount        = $amount;
                    $appJournalRec->transaction_type    = $item->transaction_type;
                    $appJournalRec->journal_date        = $item->journal_date;
                    $appJournalRec->vat_rate_id         = $item->vat_rate_id;
                    $appJournalRec->is_main_head        = $item->is_main_head;
                    $appJournalRec->account_type_id     = $item->account_type_id;
                    $appJournalRec->gst_subtotal        = $item->gst_subtotal;
                    $appJournalRec->save();
                }
            } else {
                $appJournalRec = new JournalRecord();
                $appJournalRec->journal_id          = $ApproveJournal->id;
                $appJournalRec->office_id           = $ApproveJournal->office_id;
                $appJournalRec->project_details_id  = $item->project_details_id;
                $appJournalRec->cost_center_id      = $item->cost_center_id;
                $appJournalRec->party_info_id       = $item->party_info_id ?? 0;
                $appJournalRec->gst_amount          = $item->gst_amount;
                $appJournalRec->journal_no          = $ApproveJournal->journal_no;
                $appJournalRec->account_head_id     = $item->account_head_id;
                $appJournalRec->sub_account_head_id = $item->sub_account_head_id;
                $appJournalRec->master_account_id   = $item->master_account_id;
                $appJournalRec->account_head        = $item->account_head;
                $appJournalRec->invoice_no          = $item->invoice_no;
                $appJournalRec->amount              = $item->amount;
                $appJournalRec->total_amount        = $item->total_amount;
                $appJournalRec->transaction_type    = $item->transaction_type;
                $appJournalRec->journal_date        = $item->journal_date;
                $appJournalRec->vat_rate_id         = $item->vat_rate_id;
                $appJournalRec->is_main_head        = $item->is_main_head;
                $appJournalRec->account_type_id     = $item->account_type_id;
                $appJournalRec->gst_subtotal        = $item->gst_subtotal;
                $appJournalRec->save();
            }
        }
        $journal->records()->forceDelete();
        $journal->forceDelete();


        return back()->with('success', 'Successfully Approved');
    }
    public function journal_view_pdf($id)
    {
        $journal = Journal::find($id);
        $pdf = PDF::loadView('backend.journal.new-journal-preview-pdf', compact('journal'));
        return $pdf->download('journal-' . $journal->journal_no . '.pdf');
        // return view('backend.journal.new-preview', compact('journal'));
    }

    public function account_head_type_check(Request $request){
        $check_sub_head = substr($request->head_id,0,3);
        $journal_no = $request->journal_no;
        $sub_head = null;
        if ($check_sub_head == 'Sub') {
            $sub_head = substr($request->head_id, 3);
            $sub_ac_head = AccountSubHead::find($sub_head);
            $ac_head = AccountHead::find($sub_ac_head->account_head_id);
        } else {
            $ac_head = AccountHead::find($request->head_id);
        }
        // dd($request->debit_amount);
        if ($ac_head->account_type_id == 1 && $ac_head->fld_definition == 'Current/Operating Asset') {
            $debit_amount = $request->debit_amount;
            $credit_amount = $request->credit_amount;
            $parties = PartyInfo::where('pi_type', 'Customer')->get();
            return view('backend.journal.party-amount-add', compact('parties', 'credit_amount', 'debit_amount','ac_head', 'sub_head', 'journal_no'));
        }elseif($ac_head->account_type_id==2 && $ac_head->fld_definition=='Current Liability'){
            $debit_amount = $request->debit_amount;
            $credit_amount = $request->credit_amount;
            $parties = PartyInfo::where('pi_type', 'Supplier')->get();
            return view('backend.journal.party-amount-add', compact('parties', 'credit_amount', 'debit_amount','ac_head', 'sub_head', 'journal_no'));

        }else{
            return 0;
        }
    }
    public function party_amount_store(Request $request)
    {
        TempPartyPaymentAmount::where('token', $request->_token)->where('account_head_id', $request->accout_head_id)->forceDelete();
        $party_ids = $request->party_id;
        if ($party_ids) {
            foreach ($party_ids as $key => $id) {
                if ($id && $request->pay_amount[$key]) {
                    $pe = new TempPartyPaymentAmount;
                    $pe->token = $request->_token;
                    $pe->party_id = $id;
                    $pe->amount = $request->pay_amount[$key];
                    $pe->account_head_id = $request->accout_head_id;
                    $pe->sub_head_id = $request->sub_head_id;
                    $pe->save();
                }
            }
        }
    }
}

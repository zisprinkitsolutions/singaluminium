<?php

namespace App\Http\Controllers\backend;

use App\BillOfQuantity;
use App\BillOfQuantityTask;
use App\Http\Controllers\Controller;
use App\JobProject;
use App\JobProjectInvoice;
use App\JobProjectInvoiceTask;
use App\JobProjectTask;
use App\JobProjectTemInvoice;
use App\Journal;
use App\JournalRecord;
use App\JournalRecordsTemp;
use App\JournalTemp;
use App\LpoProject;
use App\Models\AccountHead;
use App\Models\CostCenter;
use App\Models\InvoiceNumber;
use App\NewProject;
use App\PartyInfo;
use App\Payment;
use App\PaymentInvoice;
use App\PayMode;
use App\PayTerm;
use App\ProjectDetail;
use App\Receipt;
use App\ReceiptSale;
use App\Sale;
use App\SaleItem;
use App\SaleVoucher;
use App\TempReceiptVoucher;
use App\TxnType;
use App\Unit;
use App\VatRate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Facades\Storage;
use PDO;

use function GuzzleHttp\Promise\all;

class SaleController extends Controller
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
    private function sale_no()
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
    private function p_sale_no()
    {
        $sub_invoice = 'PI' . Carbon::now()->format('y');
        $invoice = InvoiceNumber::where('proforma_invoice_no', 'LIKE', "%{$sub_invoice}%")->first();
        if ($invoice) {
            $number = preg_replace('/^' . $sub_invoice . '/', '', $invoice->proforma_invoice_no);
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
    private function direct_sale_no()
    {
        $sub_invoice = 'D' . Carbon::now()->format('y');
        // return $sub_invoice;
        $let_sale = InvoiceNumber::where('invoice_no_s_d', 'LIKE', "%{$sub_invoice}%")->first();
        if ($let_sale) {
            $let_sale_no = preg_replace('/^' . $sub_invoice . '/', '', $let_sale->invoice_no_s_d);
            $sale_no = $let_sale_no + 1;
            if ($sale_no < 10) {
                $sale_no = $sub_invoice . '000' . $sale_no;
            } elseif ($sale_no < 100) {
                $sale_no = $sub_invoice . '00' . $sale_no;
            } elseif ($sale_no < 1000) {
                $sale_no = $sub_invoice . '0' . $sale_no;
            } else {
                $sale_no = $sub_invoice . $sale_no;
            }
        } else {
            $sale_no = $sub_invoice . '0001';
        }

        return $sale_no;
    }

    private function payment_no()
    {
        $sub_invoice = 'RV' . Carbon::now()->format('y');
        $let_purch_exp = InvoiceNumber::where('receipt_invoice_number', 'LIKE', "%{$sub_invoice}%")->first();
        if ($let_purch_exp) {
            $receipt_no = preg_replace('/^' . $sub_invoice . '/', '', $let_purch_exp->receipt_invoice_number);
            $receipt_no++;
            if ($receipt_no < 10) {
                $receipt_no = $sub_invoice . '000' . $receipt_no;
            } elseif ($receipt_no < 100) {
                $receipt_no = $sub_invoice . '00' . $receipt_no;
            } elseif ($receipt_no < 1000) {
                $receipt_no = $sub_invoice . '0' . $receipt_no;
            } else {
                $receipt_no = $sub_invoice . $receipt_no;
            }
        } else {
            $receipt_no = $sub_invoice . '0001';
        }
        return $receipt_no;
    }


    public function saleIssue(Request $records)
    {
        $projects = ProjectDetail::all();
        $modes = PayMode::whereNotIn('id', [6])->get();
        $terms = PayTerm::all();
        $sub_invoice = Carbon::now()->format('Ymd');
        $cCenters = CostCenter::all();
        $txnTypes = TxnType::all();
        // $acHeads = AccountHead::where('id',476)->get();
        $acHeads = AccountHead::where('account_type_id', '1')->where('fld_definition', 'Sell of Asset')
            ->get();
        $parties = PartyInfo::where('pi_type', 'Customer')->get();
        $pInfos  = PartyInfo::where('pi_type', 'Customer')->get();
        $vats = VatRate::orderBy('id', 'desc')->get();
        $sale_no = $this->sale_no();

        return view('backend.sale.sale', compact('sale_no', 'modes', 'terms', 'pInfos', 'cCenters', 'txnTypes', 'acHeads', 'vats', 'parties', 'projects'));
    }

    public function proforma_edit($id)
    {
        $projects = ProjectDetail::all();
        $modes = PayMode::whereNotIn('id', [6])->get();
        $terms = PayTerm::all();
        $sub_invoice = Carbon::now()->format('Ymd');
        $cCenters = CostCenter::all();
        $txnTypes = TxnType::all();
        // $acHeads = AccountHead::where('id',476)->get();
        $acHeads = AccountHead::where('account_type_id', '1')->where('fld_definition', 'Sell of Asset')->get();
        $parties = PartyInfo::where('pi_type', 'Customer')->get();
        $pInfos  = PartyInfo::where('pi_type', 'Customer')->get();
        $vats = VatRate::orderBy('id', 'desc')->get();
        $sales = Sale::find($id);
        $units = Unit::orderBy('name')->get();
        return view('backend.sale.proforma-invoice-edit', compact('projects', 'sales', 'modes', 'terms', 'pInfos', 'cCenters', 'txnTypes', 'acHeads', 'vats', 'parties', 'units'));
    }

    public function saleIssuepost(Request $request)
    {
        $request->validate(
            [
                'date'              =>  'required',
                'party_info'        => 'required',
                // 'narration'         => 'required'
            ],
            [
                'date.required'         => 'Date is required',
                'party_info.required'   => 'Party Info is required',
                // 'narration.required'    => 'Narration is required',
            ]
        );

        //Update date formate
        $update_date_format = $this->dateFormat($request->date);

        //purchase expense entry

        $party_info = PartyInfo::find($request->party_info);
        $paid_amount = 0;
        $advance_paid_amount = 0;
        $due_amount = 0;
        if ($request->pay_mode == 'Advance') {
            if ($party_info->balance > $request->total_amount) {
                $advance_paid_amount = $request->total_amount;
                $paid_amount = $request->total_amount;

                $party_info->balance = $party_info->balance - $request->total_amount;
                $party_info->save();
            } elseif ($party_info->balance == $request->total_amount) {
                $advance_paid_amount = $request->total_amount;
                $paid_amount = $request->total_amount;

                $party_info->balance = $party_info->balance - $request->total_amount;
                $party_info->save();
            } else {
                $advance_paid_amount = $party_info->balance;
                $due_amount = $request->total_amount - $party_info->balance;
                $paid_amount = $request->total_amount - $due_amount;

                $party_info->balance = 0.00;
                $party_info->save();
            }
        } elseif ($request->pay_mode == 'Credit') {
            $paid_amount = 0;
            $due_amount = $request->total_amount;
        } else {
            $paid_amount = 0;
            $due_amount = $request->total_amount;
        }
        $sale = new Sale();
        $sale->date = $update_date_format;
        $sale->pay_mode =  $request->pay_mode;
        if ($request->invoice_type == 'Direct Invoice') {
            $sale_no = $this->direct_sale_no();
            $sale->invoice_no_s_d = $sale_no;
        } elseif ($request->invoice_type == 'Tax Invoice') {
            $sale_no = $this->sale_no();
            $sale->invoice_no = $sale_no;
        } else {
            $sale_no = $this->p_sale_no();
            $sale->proforma_invoice_no = $sale_no;
        }
        $sale->site_project = $request->job_project_id;
        $sale->project_id = 0;
        $sale->invoice_type = $request->invoice_type;
        $sale->head_id = 0;
        $sale->retention_invoice = $request->retention_invoice ?? 0;
        $sale->total_amount = $request->total_amount;
        $sale->vat = $request->total_vat;
        $sale->amount = $request->taxable_amount;
        $sale->party_id =  $request->party_info;
        $sale->do_no = $request->do_no;
        $sale->lpo_no =  $request->lpo_no;
        $sale->quotation_no =  $request->quotation_no;
        $sale->attention =  $request->attention;
        $sale->narration = 0;
        $sale->created_by = Auth::id();
        $sale->gst_subtotal = 0;
        $sale->advance_paid_amount = $advance_paid_amount;
        $sale->paid_amount = $paid_amount;
        $sale->due_amount = $due_amount;

        if ($request->pay_mode == 'Cheque') {
            $sale->issuing_bank = $request->issuing_bank;
            $sale->branch = $request->bank_branch;
            $sale->cheque_no =  $request->cheque_no;
            $sale->deposit_date = $this->dateFormat($request->deposit_date);
        }

        $sale->authorized = true;
        $sale->save();

        if ($request->hasFile('voucher_scan')) {
            foreach ($request->file('voucher_scan') as $file) {
                $this->fileUpload($file, $sale->id);
            }
        }

        if ($request->invoice_type == 'Direct Invoice') {
            $sales_invoice = InvoiceNumber::first();
            $sales_invoice->invoice_no_s_d = $sale->invoice_no;
            $sales_invoice->save();
        } elseif ($request->invoice_type == 'Tax Invoice') {
            $sales_invoice = InvoiceNumber::first();
            $sales_invoice->invoice_no = $sale->invoice_no;
            $sales_invoice->save();
        } else {
            $sales_invoice = InvoiceNumber::first();
            $sales_invoice->proforma_invoice_no = $sale->proforma_invoice_no;
            $sales_invoice->save();
        }
        //end purchase expense entry
        $multi_head = $request->input('group-a');
        $t_cogs = 0;
        foreach ($multi_head as $each_head) {
            //purchase record
            $purc_exp_itm = new SaleItem();
            $purc_exp_itm->task_id = $each_head['task_id'] ?? null;
            $purc_exp_itm->item_description = $each_head['multi_acc_head'];
            $purc_exp_itm->qty = $each_head['qty'];
            $purc_exp_itm->unit_id = $each_head['unit'];
            $purc_exp_itm->rate = $each_head['rate'];
            $purc_exp_itm->amount = $each_head['amount'];
            $purc_exp_itm->vat = $each_head['vat_amount'];
            $purc_exp_itm->total_amount = $purc_exp_itm->amount + $purc_exp_itm->vat;
            $purc_exp_itm->party_id = $request->party_info;
            $purc_exp_itm->sale_id = $sale->id;
            $purc_exp_itm->gst_subtotal = 0;
            $purc_exp_itm->save();
            //end purchase record
        }
        //end records entry
        $sale = $sale;
        $projects = ProjectDetail::all();
        $modes = PayMode::get();
        $terms = PayTerm::all();
        $sub_invoice = Carbon::now()->format('Ymd');
        $cCenters = CostCenter::all();
        $txnTypes = TxnType::all();
        // $acHeads = AccountHead::where('id',476)->get();
        $acHeads = AccountHead::where('account_type_id', '1')->where('fld_definition', 'Sell of Asset')
            ->get();
        $parties = PartyInfo::where('pi_type', 'Customer')->get();
        $pInfos  = PartyInfo::where('pi_type', 'Customer')->get();
        $vats = VatRate::orderBy('id', 'desc')->get();
        $sales = Sale::where('authorized', true)->orderBy('date', 'DESC')->paginate(20);

        return response()->json([
            'preview' =>  view('backend.sale.authorize-preview', compact('sale', 'pInfos', 'vats', 'sale_no', 'projects', 'modes'))->render(),
            'approve_list' => view('backend.sale._ajax_approve_list', compact('sales'))->render(),
        ]);
    }

    public function sale_list()
    {
        $units = Unit::orderBy('name')->get();
        $modes = PayMode::whereNotIn('id', [6])->get();
        $parties = PartyInfo::where('pi_type', 'Customer')->get();
        $pInfos  = PartyInfo::where('pi_type', 'Customer')->get();
        $sales = JobProjectInvoice::where('invoice_type', 'Tax Invoice')->orderBy('id', 'DESC')->paginate(20);
        $i = 0;
        $pending_sales = Sale::where('authorized', true)->orderBy('date', 'DESC')->paginate(20);
        return view('backend.sale.all-invoice-list', compact('sales', 'i', 'parties', 'pending_sales', 'pInfos', 'modes', 'units'));
    }

    public function sale_list_proforma()
    {
        $parties = PartyInfo::get();
        $sales = JobProjectInvoice::where('invoice_type', 'Proforma Invoice')->orderBy('date', 'DESC')->paginate(20);
        $i = 0;
        return view('backend.sale.list-proforma', compact('sales', 'i', 'parties'));
    }
    public function sale_list_direct()
    {
        $parties = PartyInfo::get();
        $sales = JobProjectInvoice::where('invoice_type', 'Direct Invoice')->orderBy('id', 'DESC')->paginate(20);
        $i = 0;
        return view('backend.sale.list-direct', compact('sales', 'i', 'parties'));
    }
    public function authorize_sale_modal(Request $request)
    {
        $projects = ProjectDetail::all();
        $modes = PayMode::get();
        $terms = PayTerm::all();
        $sub_invoice = Carbon::now()->format('Ymd');
        $cCenters = CostCenter::all();
        $txnTypes = TxnType::all();
        // $acHeads = AccountHead::where('id',476)->get();
        $acHeads = AccountHead::where('account_type_id', '1')->where('fld_definition', 'Sell of Asset')
            ->get();
        $parties = PartyInfo::all();
        $pInfos  = PartyInfo::all();
        $vats = VatRate::orderBy('id', 'desc')->get();
        $sale = Sale::find($request->id);
        $sale_no = $sale->invoice_no;
        return view('backend.sale.authorize-preview', compact('sale', 'pInfos', 'vats', 'sale_no', 'projects', 'modes'));
    }

    public function approve_sale_modal(Request $request)
    {
        $projects = ProjectDetail::all();
        $modes = PayMode::get();
        $terms = PayTerm::all();
        $sub_invoice = Carbon::now()->format('Ymd');
        $cCenters = CostCenter::all();
        $txnTypes = TxnType::all();
        // $acHeads = AccountHead::where('id',476)->get();
        $acHeads = AccountHead::where('account_type_id', '1')->where('fld_definition', 'Sell of Asset')->get();
        $parties = PartyInfo::all();
        $pInfos  = PartyInfo::all();
        $vats = VatRate::orderBy('id', 'desc')->get();
        $sale = Sale::find($request->id);
        $sale_no = $sale->invoice_no;
        return view('backend.sale.authorize-preview', compact('sale', 'pInfos', 'vats', 'sale_no', 'projects', 'modes'));
    }

    public function sale_modal(Request $request)
    {
        $sale = JobProjectInvoice::with('documents')->find($request->id);
        $modes = PayMode::whereIn('id', [1, 5, 7, 4])->get();
        if ($sale->invoice_from == 'project') {
            $invoice = $sale;
            $standard = VatRate::where('name', 'Standard')->first();
            $notes = JobProjectInvoice::where('id', '<=', $sale->id)->where('job_project_id', $sale->job_project_id)->orderBy('id', 'asc')->get();
            // return view('backend.sale.approve-invoice-view', compact('invoice', 'standard', 'notes'));
            return view('backend.job-project-invoice.approve-invoice-view', compact('invoice', 'standard', 'notes'));
        } else {
            return view('backend.sale.preview', compact('sale', 'modes'));
        }
    }



    public function sale_print($id)
    {
        $sale = JobProjectInvoice::find($id);
        if ($sale->invoice_from == 'project') {
            $invoice = $sale;
            $standard = VatRate::where('name', 'Standard')->first();
            $notes = JobProjectInvoice::where('id', '<', $sale->id)->where('job_project_id', $sale->job_project_id)->orderBy('id', 'asc')->get();
            return view('backend.sale.approve-invoice-view', compact('invoice', 'standard', 'notes'));
        } else {
            return view('backend.sale.sale_print', compact('sale'));
        }
    }

    public function auth_sale_print($id)
    {
        $projects = ProjectDetail::all();
        $modes = PayMode::get();
        $terms = PayTerm::all();
        $sub_invoice = Carbon::now()->format('Ymd');
        $cCenters = CostCenter::all();
        $txnTypes = TxnType::all();
        // $acHeads = AccountHead::where('id',476)->get();
        $acHeads = AccountHead::where('account_type_id', '1')->where('fld_definition', 'Sell of Asset')->get();
        $parties = PartyInfo::all();
        $pInfos  = PartyInfo::all();
        $vats = VatRate::orderBy('id', 'desc')->get();
        $sale = Sale::find($id);
        $sale_no = $sale->invoice_no;
        return view('backend.sale.auth_sale_print', compact('sale', 'pInfos', 'vats', 'sale_no', 'projects', 'modes'));
    }





    public function search_sale(Request $request)
    {
        $sales = Sale::where('invoice_type', 'like', "%{$request->invoice_type}%")->where('invoice_no', 'like', "%{$request->value}%")->where('authorized', $request->type == 'authorize' ? 0 : 1)->get();
        // return $sales;
        if ($request->party != '') {
            $sales = $sales->where('party_id', $request->party);
        }
        if ($request->date != '') {
            $date = $this->dateFormat($request->date);
            $sales = $sales->where('date', $date);
        }
        return view('backend.sale.search-sale', compact('sales'));
    }

    public function search_sale_inv(Request $request)
    {
        $sales = JobProjectInvoice::where('invoice_from', 'Sales')->where('invoice_type', $request->type)->where('invoice_no', 'like', "%{$request->value}%")->get();
        if ($request->party != '') {
            $sales = $sales->where('customer_id', $request->party);
        }
        if ($request->date != '') {
            $date = $this->dateFormat($request->date);
            $sales = $sales->where('date', $date);
        }
        return view('backend.sale.search-sale-inv', compact('sales'));
    }

    public function receipt_voucher2()
    {
        $sales = Sale::where('due_amount', '>', 0)->get();
        $parties = PartyInfo::where('pi_type', 'Customer')->get();
        $i = 0;
        $modes = PayMode::whereNotIn('id', [2])->get();
        $invoices = JobProject::where('is_invoice', 1)->latest()->paginate(20);

        return view('backend.sale.receipt-voucher', compact('sales', 'i', 'parties', 'modes', 'invoices'));
    }
    public function receipt_voucher3()
    {
        $sales = Sale::where('due_amount', '>', 0)->get();
        $parties = PartyInfo::where('pi_type', 'Customer')->get();
        $i = 0;
        $modes = PayMode::whereNotIn('id', [2, 3, 6])->get();
        $invoices = JobProjectInvoice::where('due_amount', '>', 0)->whereIn('invoice_type', ['Tax Invoice', 'Proforma Invoice'])->get();

        return view('backend.sale.receipt-voucher2', compact('sales', 'i', 'parties', 'modes', 'invoices'));
    }
    public function partyInfosale2(Request $request)
    {
        $date = $request->date ? $this->dateFormat($request->date) : date('d/m/Y');
        $info = PartyInfo::where('id', $request->party_id)->first();
        $invoices = JobProjectInvoice::where('date', '<=', $date)->where('due_amount', '>', 0)->where('customer_id', $info->id)->get();
        $due = $invoices->where('due_amount', '>', 0)->sum('due_amount');
        if ($request->ajax()) {
            return Response()->json([
                'page' => view('backend.sale.receipt-invoice', ['invoices' => $invoices, 'i' => 1])->render(),
                'info' => $info,
                'due' =>  $invoices->where('due_amount', '>', 0)->sum('due_amount')
            ]);
        }
    }

    public function partyInfodueInvoices(Request $request)
    {
        // return $request->all();
        // return 1;
        $info = PartyInfo::where('id', $request->value)->first();
        $invoices = JobProjectInvoice::where('due_amount', '>', 0)->whereIn('invoice_type', ['Tax Invoice', 'Proforma Invoice'])->where('customer_id', $info->id)->get();
        $due = '';
        // return $invoices->sum('due_amount');

        if ($request->ajax()) {
            return Response()->json([
                'page' => view('backend.sale.due-invoice', ['invoices' => $invoices, 'i' => 1])->render(),
                'info' => $info,
                'due' =>  ''
            ]);
        }
    }

    public function findInvoiceforReceipt(Request $request)
    {
        $invoice = JobProjectInvoice::find($request->value);
        $info = PartyInfo::where('id', $invoice->customer_id)->first();
        $due = $invoice->due_amount;

        if ($request->ajax()) {
            return Response()->json([
                'info' => $info,
                'due' =>  $invoice->due_amount
            ]);
        }
    }

    public function payment_post(Request $request)
    {
        // return $request->all();
        $update_date_format = $this->dateFormat($request->date);
        $sub_invoice = Carbon::now()->format('Ymd');
        $job_project = JobProject::find($request->project_id);
        $let_purch_exp = Receipt::whereDate('created_at', Carbon::today())->where('receipt_no', 'LIKE', "%{$sub_invoice}%")->latest('id')->first();
        if ($let_purch_exp) {
            $purch_no = $let_purch_exp->receipt_no + 1;
        } else {
            $purch_no = Carbon::now()->format('Ymd') . '001';
        }

        if ($request->pay_mode == "Cheque") {
            $deposit_date = $this->dateFormat($request->deposit_date);
            $payment = new Receipt();
            $payment->job_project_id = $request->project_id;
            $payment->date = $update_date_format;
            $payment->pay_mode =  $request->pay_mode;
            $payment->receipt_no = $purch_no;
            $payment->head_id = 0;
            $payment->total_amount = $request->pay_amount;
            $payment->vat = 0;
            $payment->party_id =  $request->party_info;
            $payment->narration = $request->narration;
            $payment->issuing_bank = $request->issuing_bank;
            $payment->branch = $request->bank_branch;
            $payment->cheque_no = $request->cheque_no;
            $payment->deposit_date = $deposit_date;
            $payment->status = 'Pending';
            $payment->paid_amount = $request->pay_amount;
            $payment->due_amount = $request->due_amount - $request->pay_amount;
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
            $payment = new Receipt();
            $payment->date = $update_date_format;
            $payment->pay_mode =  $request->pay_mode;
            $payment->receipt_no = $purch_no;
            $payment->head_id = 0;
            $payment->total_amount = $request->pay_amount;
            $payment->vat = 0;
            $payment->party_id =  $request->party_info;
            $payment->narration = $request->narration;
            $payment->status = 'Realised';
            $payment->paid_amount = $request->pay_amount;
            $payment->due_amount = $request->due_amount - $request->pay_amount;
            $payment->save();

            $journal = new Journal();
            $journal->project_id        = 1;
            $journal->transection_type        = 'RECEIPT VOUCHER';
            $journal->transaction_type        = 'DEBIT';
            $journal->journal_no        = $journal_no;
            $journal->date              = $payment->date;
            $journal->voucher_type              = 'Receipt Voucher';
            $journal->receipt_id          = $payment->id;

            $journal->pay_mode          = $payment->pay_mode;
            $journal->invoice_no        = 0;
            $journal->cost_center_id    = $cost_center_id;
            $journal->party_info_id     = $payment->party_id;
            $journal->account_head_id   = 123;
            $journal->amount            = $payment->total_amount;
            $journal->tax_rate          = 0;
            $journal->vat_amount        = 0;
            $journal->total_amount      = $payment->total_amount;
            $journal->narration         =  $payment->narration;
            $journal->created_by        = Auth::id();
            $journal->authorized_by = Auth::id();
            $journal->approved_by    = Auth::id();
            $journal->save();


            $income_head = AccountHead::find(3);
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
            $jl_record->transaction_type    = 'CR';
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
            $jl_record->transaction_type    = 'DR';
            $jl_record->journal_date        = $update_date_format;
            $jl_record->account_type_id = $pay_head->account_type_id;
            $jl_record->is_main_head        = 0;
            $jl_record->save();
        }


        $sale = JobProjectInvoice::where('due_amount', '>', 0)->where('customer_id', $request->party_info)->orderBy('date', 'asc')->first();
        if ($sale) {
            $pay_amount = $request->pay_amount;
            while ($pay_amount > 0) {
                if ($pay_amount < $sale->due_amount) {
                    $amount = $pay_amount;
                    $sale->due_amount = $sale->due_amount - $pay_amount;
                    $sale->paid_amount = $sale->paid_amount + $pay_amount;
                    $pay_amount = 0;
                } else {
                    $amount = $sale->due_amount;
                    $sale->paid_amount = $sale->paid_amount + $sale->due_amount;
                    $pay_amount = $pay_amount - $sale->due_amount;
                    $sale->due_amount = 0;
                }
                $sale->save();
                $purc_exp_itm = new ReceiptSale();
                $purc_exp_itm->sale_id = $sale->id;
                $purc_exp_itm->payment_id = $payment->id;
                $purc_exp_itm->Total_amount = $amount;
                $purc_exp_itm->vat = 0;
                $purc_exp_itm->amount = $amount;
                $purc_exp_itm->party_id = $request->party_info;
                $purc_exp_itm->save();
                $sale = JobProjectInvoice::where('due_amount', '>', 0)->where('customer_id', $request->party_info)->orderBy('date', 'asc')->first();
                if (!$sale) {
                    $advance = $pay_amount;
                    $pay_amount = 0;
                }
            }
        }


        $recept = $payment;
        return view('backend.sale.receipt-preview', compact('recept'));
    }

    public function receipt_voucher_list_show()
    {
        $sales = Sale::where('due_amount', '>', 0)->get();
        $receipt_list = Receipt::orderBy('id', 'desc')->paginate(40);
        $parties = PartyInfo::get();
        $i = 0;
        $modes = PayMode::all();
        $temp_receipt_list = TempReceiptVoucher::orderBy('id', 'desc')->paginate(40);
        return view('backend.sale.receipt-list', compact('sales', 'i', 'parties', 'modes', 'receipt_list', 'temp_receipt_list'));
    }

    public function receipt_list_modal(Request $request)
    {
        $recept = Receipt::find($request->id);
        return view('backend.sale.receipt-preview', compact('recept'));
    }


    public function search_receipt(Request $request)
    {

        $receipt_list = Receipt::where('receipt_no', 'like', "%{$request->value}%")->get();
        $temp_receipt_list = TempReceiptVoucher::where('receipt_no', 'like', "%{$request->value}%")->get();
        if ($request->party != '') {
            $receipt_list = $receipt_list->where('party_id', $request->party);
            $temp_receipt_list =  $temp_receipt_list->where('party_id', $request->party);
        }
        if ($request->date != '') {
            $date = $this->dateFormat($request->date);
            $receipt_list = $receipt_list->where('date', $date);
            $temp_receipt_list = $temp_receipt_list->where('date', $date);
        }
        if ($request->mode != '') {
            $receipt_list = $receipt_list->where('pay_mode', $request->mode);
            $temp_receipt_list = $temp_receipt_list->where('pay_mode', $request->mode);
        }
        $i = 0;
        return view('backend.sale.search-receipt', compact('receipt_list', 'temp_receipt_list', 'i'));
    }


    public function find_job_project(Request $request)
    {
        $proj = JobProject::find($request->value);
        $party = PartyInfo::find($proj->customer_id);
        $due = $proj->invoicess->where('due_amount', '>', 0)->sum('due_amount');
        if ($request->ajax()) {
            return Response()->json([
                'page' => view('backend.sale.search-sale', ['proj' => $proj, 'i' => 1])->render(),
                'proj' => $proj,
                'party' => $party,
                'due' => $due
            ]);
        }
    }



    public function receipt_declined($id)
    {
        $payment = Receipt::find($id);
        $payment->status = "Declined";
        $payment->save();
        $notification = array(
            'message'       => 'Declined!',
            'alert-type'    => 'warning'
        );
        return redirect()->back()->with($notification);
    }

    public function receipt_realised($id)
    {
        $receipt = Receipt::find($id);
        if ($receipt->status == 'Realised') {
            $notification = array(
                'message'       => 'Already Realised!',
                'alert-type'    => 'warning'
            );
            return redirect()->back()->with($notification);
        }
        $receipt->status = "Realised";
        $receipt->save();
        $journal_no = $this->journal_no();
        $journal = new Journal();
        $journal->project_id        = 1;
        $journal->transection_type        = 'PAYMENT VOUCHER';
        $journal->transaction_type        = 'CREDIT';
        $journal->payment_id        = $receipt->id;
        $journal->journal_no        = $journal_no;
        $journal->date              = $receipt->date;
        $journal->pay_mode          = 'Cash';
        $journal->voucher_type          = 'Payment Voucher';
        $journal->invoice_no        = 0;
        $journal->cost_center_id    = 0;
        $journal->party_info_id     = $receipt->party_id;
        $journal->account_head_id   = 123;
        $journal->amount            = $receipt->total_amount;
        $journal->tax_rate          = 0;
        $journal->vat_amount        = 0;
        $journal->total_amount      = $receipt->total_amount;
        $journal->narration         = $receipt->narration;
        $journal->created_by        = Auth::id();
        $journal->authorized_by = Auth::id();
        $journal->approved_by    = Auth::id();
        $journal->save();

        $receipt_head = AccountHead::find(3);
        $jl_record = new JournalRecord();
        $jl_record->journal_id     = $journal->id;
        $jl_record->project_details_id  = $journal->project_id;
        $jl_record->cost_center_id      = $journal->cost_center_id;
        $jl_record->party_info_id       = $journal->party_info_id;
        $jl_record->journal_no          = $journal_no;
        $jl_record->account_head_id     = $receipt_head->id;
        $jl_record->master_account_id   = $receipt_head->master_account_id;
        $jl_record->account_head        = $receipt_head->fld_ac_head;
        $jl_record->amount              = $journal->amount;
        $jl_record->total_amount        = $journal->amount;
        $jl_record->vat_rate_id         = 0;
        $jl_record->transaction_type    = 'CR';
        $jl_record->journal_date        = $journal->date;
        $jl_record->account_type_id = $receipt_head->account_type_id;
        $jl_record->is_main_head        = 0;
        $jl_record->save();


        $pay_head = AccountHead::find(2);
        $jl_record = new JournalRecord();
        $jl_record->journal_id     = $journal->id;
        $jl_record->project_details_id  = $journal->project_id;
        $jl_record->cost_center_id      = $journal->cost_center_id;
        $jl_record->party_info_id       = $journal->party_info_id;
        $jl_record->journal_no          = $journal_no;
        $jl_record->account_head_id     = $pay_head->id;
        $jl_record->master_account_id   = $pay_head->master_account_id;
        $jl_record->account_head        = $pay_head->fld_ac_head;
        $jl_record->amount              = $journal->amount;
        $jl_record->total_amount        = $journal->amount;
        $jl_record->vat_rate_id         = 0;
        $jl_record->transaction_type    = 'DR';
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

    public function receipt_deposit(Request $request, $id)
    {
        $receipt = Receipt::find($id);
        // dd($request->all());
        $receipt->deposit_date = $this->dateFormat($request->deposit_date);
        $receipt->save();
        $notification = array(
            'message'       => 'Success!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }


    public function sale_authorize()
    {
        $parties = PartyInfo::get();
        $i = 0;
        $sales = Sale::where('authorized', false)->orderBy('id', 'DESC')->get();
        return view('backend.sale.authorize', compact('sales', 'parties', 'i'));
    }



    public function sale_authorization($id)
    {
        $purch = Sale::find($id);
        $purch->authorized = true;
        $purch->authorized_by = Auth::id();
        $purch->save();
        $notification = array(
            'message'       => 'Authorized Successfully!',
            'alert-type'    => 'success'
        );
        return redirect('sales/sale-approve')->with($notification);
    }

    public function sale_approve()
    {
        $parties = PartyInfo::get();
        $i = 0;
        $sales = Sale::where('authorized', true)->orderBy('date', 'DESC')->paginate(20);
        return view('backend.sale.approve', compact('sales', 'parties', 'i'));
    }

    private function normalInvoice($sale)
    {
        $invoice = new JobProjectInvoice();
        $invoice->invoice_no =  $this->sale_no();
        $invoice->proforma_invoice_no =  $sale->proforma_invoice_no;
        $invoice->invoice_no_s_d =  $sale->invoice_no_s_d;
        $invoice->invoice_from = 'Sales';
        $invoice->customer_id =  $sale->party_id;
        $invoice->budget = $sale->amount;
        $invoice->vat = $sale->vat;
        $invoice->total_budget = $sale->total_amount;
        $invoice->date = $sale->date;
        $invoice->due_amount = $sale->due_amount;
        $invoice->paid_amount =  $sale->paid_amount;
        $invoice->advance_paid_amount =  $sale->advance_paid_amount;
        $invoice->narration = $sale->narration;
        $invoice->invoice_type = 'Tax Invoice'; //$sale->invoice_type;
        $invoice->approved_by    = Auth::id();
        $invoice->do_no = $sale->do_no;
        $invoice->lpo_no =  $sale->lpo_no;
        $invoice->quotation_no =  $sale->quotation_no;
        $invoice->quotation_no =  $sale->quotation_no;
        $invoice->voucher_scan =  $sale->voucher_scan;
        $invoice->voucher_scan2 =  $sale->voucher_scan2;
        $invoice->attention =  $sale->attention;
        $invoice->pay_mode =  $sale->pay_mode;
        $invoice->job_project_id =  $sale->site_project;
        $invoice->retention_invoice =  $sale->retention_invoice;
        $invoice->save();



        $sales_invoice = InvoiceNumber::first();
        $sales_invoice->invoice_no = $invoice->invoice_no;
        $sales_invoice->save();

        foreach ($sale->documents as $document) {
            $document->update(['invoice_id' => $invoice->id]);
        }
        foreach ($sale->items as $item) {
            $purc_exp_itm = new JobProjectInvoiceTask();
            $purc_exp_itm->task_name = $item->item_description;
            $purc_exp_itm->task_id = $item->task_id;
            $purc_exp_itm->qty = $item->qty;
            $purc_exp_itm->unit = $item->unit_id;
            $purc_exp_itm->rate = $item->rate;
            $purc_exp_itm->budget = $item->amount;
            $purc_exp_itm->vat_id = $item->amount;
            $purc_exp_itm->total_budget = $item->total_amount;
            $purc_exp_itm->vat_id =  $purc_exp_itm->budget < $purc_exp_itm->total_budget ? 1 : 3;
            $purc_exp_itm->item_description = $item->item_description;
            $purc_exp_itm->invoice_id = $invoice->id;
            $purc_exp_itm->paid_amount = $sale->paid_amount > 0 ? $item->amount : 0;
            $purc_exp_itm->due_amount = $sale->paid_amount > 0 ? 0 : $item->amount;
            $purc_exp_itm->save();

            //update task
            $task = JobProjectTask::find($item->task_id);
            if ($task) {
                $job_project = JobProject::find($task->job_project_id);
                $job_project->paid_amount += $item->amount;
                $job_project->due_amount  -= $item->amount;
                $job_project->save();

                $task->revenue += $item->amount;
                $task->receipt += $sale->paid_amount > 0 ? $item->amount : 0;
                $task->receivable += $sale->paid_amount > 0 ? 0 : $item->amount;
                $task->save();
            }
        }
        if ($invoice->invoice_type == 'Tax Invoice') {
            $journal_no = $this->journal_no();
            $journal = new Journal();
            $journal->project_id        = 1;
            $journal->invoice_id        = $invoice->id;
            $journal->transection_type = 'Sale';
            $journal->transaction_type = 'Increase';
            $journal->journal_no        = $journal_no;
            $journal->date              =  $invoice->date;
            $journal->pay_mode          = 'CREDIT';
            $journal->cost_center_id    = 0;
            $journal->party_info_id     = $invoice->customer_id;
            $journal->account_head_id   = 123;
            $journal->voucher_type   = 'CREDIT';

            $journal->amount            = $invoice->total_budget;
            $journal->tax_rate          = 0;
            $journal->vat_amount        = $invoice->total_budget - $invoice->budget;
            $journal->total_amount      = $invoice->budget;
            $journal->gst_subtotal = 0;
            $journal->narration         =  $invoice->narration;
            $journal->approved_by = $invoice->approved_by;
            $journal->save();

            //journal record
            $ac_head = AccountHead::find(7);
            $jl_record = new JournalRecord();
            $jl_record->journal_id          = $journal->id;
            $jl_record->project_details_id  = $journal->project_id;
            $jl_record->cost_center_id      = $journal->cost_center_id;
            $jl_record->party_info_id       =  $journal->party_info_id;
            $jl_record->journal_no          =  $journal->journal_no;
            $jl_record->account_head_id     = $ac_head->id;
            $jl_record->master_account_id   = $ac_head->master_account_id;
            $jl_record->account_head        = $ac_head->fld_ac_head;
            $jl_record->amount              = $invoice->budget;
            $jl_record->total_amount        = $invoice->budget;
            $jl_record->vat_rate_id         = 0;
            $jl_record->invoice_no          = 0;
            $jl_record->transaction_type    = 'CR';
            $jl_record->journal_date        =  $journal->date;
            $jl_record->is_main_head        = 1;
            $jl_record->account_type_id     = $ac_head->account_type_id;
            $jl_record->project_id          = $invoice->job_project_id;
            $jl_record->save();
            //end journal record

            //vat journal
            if ($journal->vat_amount > 0) {
                $vat_ac_head = AccountHead::find(17); // vat account head
                $jl_record = new JournalRecord();
                $jl_record->journal_id     = $journal->id;
                $jl_record->project_details_id  = $journal->project_id;
                $jl_record->cost_center_id      = $journal->cost_center_id;
                $jl_record->party_info_id       = $journal->party_info_id;
                $jl_record->journal_no          =  $journal->journal_no;
                $jl_record->account_head_id     = $vat_ac_head->id;
                $jl_record->master_account_id   = $vat_ac_head->master_account_id;
                $jl_record->account_head        = $vat_ac_head->fld_ac_head;
                $jl_record->amount              = $journal->vat_amount;
                $jl_record->invoice_no              = 'N/A';
                $jl_record->total_amount        = $journal->vat_amount;
                $jl_record->vat_rate_id         = 0;
                $jl_record->transaction_type    = 'CR';
                $jl_record->journal_date        = $journal->date;
                $jl_record->account_type_id = $vat_ac_head->account_type_id;
                $jl_record->project_id          = $invoice->job_project_id;
                $jl_record->is_main_head        = 0;
                $jl_record->save();
            }
            //end vat journal

            //Paymode journal
            if ($invoice->due_amount > 0) {
                $ac_head = AccountHead::find(3); // accounts Receivable
                $jl_record = new JournalRecord();
                $jl_record->journal_id     = $journal->id;
                $jl_record->project_details_id  = $journal->project_id;
                $jl_record->cost_center_id      = $journal->cost_center_id;
                $jl_record->party_info_id       = $journal->party_info_id;
                $jl_record->journal_no          =  $journal->journal_no;
                $jl_record->account_head_id     = $ac_head->id;
                $jl_record->master_account_id   = $ac_head->master_account_id;
                $jl_record->account_head        = $ac_head->fld_ac_head;
                $jl_record->amount              = $invoice->due_amount;
                $jl_record->total_amount        = $invoice->due_amount;
                $jl_record->vat_rate_id         = 0;
                $jl_record->transaction_type    = 'DR';
                $jl_record->journal_date        = $journal->date;
                $jl_record->invoice_no              = 'N/A';
                $jl_record->account_type_id = $ac_head->account_type_id;
                $jl_record->project_id          = $invoice->job_project_id;

                $jl_record->is_main_head        = 0;
                $jl_record->save();
            }
            //end paymode journal

            //payment voucher
        }

        $jProject = JobProject::find($invoice->job_project_id);
        if ($jProject) {
            $retentionAmount =  (10/100)*$invoice->budget;
            $jProject->retention_amount = $jProject->retention_amount + $retentionAmount;
            $jProject->save();

            $ac_head = AccountHead::find(1759);
            $jl_record = new JournalRecord();
            $jl_record->journal_id          = $journal->id;
            $jl_record->project_details_id  = $journal->project_id;
            $jl_record->cost_center_id      = $journal->cost_center_id;
            $jl_record->party_info_id       = $journal->party_info_id;
            $jl_record->journal_no          = $journal->journal_no;
            $jl_record->account_head_id     = $ac_head->id;
            $jl_record->master_account_id   = $ac_head->master_account_id;
            $jl_record->account_head        = $ac_head->fld_ac_head;
            $jl_record->amount              = $retentionAmount;
            $jl_record->total_amount        = $retentionAmount;
            $jl_record->vat_rate_id         = 0;
            $jl_record->transaction_type    = 'DR';
            $jl_record->journal_date        = $journal->date;
            $jl_record->invoice_no          = 'N/A';
            $jl_record->account_type_id     = $ac_head->account_type_id;
            $jl_record->is_main_head        = 0;
            $jl_record->project_id          = $invoice->job_project_id;
            $jl_record->save();
        }
        //end payment voucher

        $sale->items->each->delete();
        $sale->delete();

        return $invoice;
    }

    private function retentionInvoice($sale)
    {
        $invoice = new JobProjectInvoice();
        $invoice->invoice_no =  $this->sale_no();
        $invoice->proforma_invoice_no =  $sale->proforma_invoice_no;
        $invoice->invoice_no_s_d =  $sale->invoice_no_s_d;
        $invoice->invoice_from = 'Sales';
        $invoice->customer_id =  $sale->party_id;
        $invoice->budget = $sale->amount;
        $invoice->vat = $sale->vat;
        $invoice->total_budget = $sale->total_amount;
        $invoice->date = $sale->date;
        $invoice->due_amount = $sale->due_amount;
        $invoice->paid_amount =  $sale->paid_amount;
        $invoice->advance_paid_amount =  $sale->advance_paid_amount;
        $invoice->narration = $sale->narration;
        $invoice->invoice_type = 'Tax Invoice'; //$sale->invoice_type;
        $invoice->approved_by    = Auth::id();
        $invoice->do_no = $sale->do_no;
        $invoice->lpo_no =  $sale->lpo_no;
        $invoice->quotation_no =  $sale->quotation_no;
        $invoice->quotation_no =  $sale->quotation_no;
        $invoice->voucher_scan =  $sale->voucher_scan;
        $invoice->voucher_scan2 =  $sale->voucher_scan2;
        $invoice->attention =  $sale->attention;
        $invoice->pay_mode =  $sale->pay_mode;
        $invoice->job_project_id =  $sale->site_project;
        $invoice->retention_invoice =  $sale->retention_invoice;
        $invoice->save();
        // dd($invoice);

         $sales_invoice = InvoiceNumber::first();
        $sales_invoice->invoice_no = $invoice->invoice_no;
        $sales_invoice->save();

         foreach ($sale->items as $item) {
            $purc_exp_itm = new JobProjectInvoiceTask();
            $purc_exp_itm->task_name = $item->item_description;
            $purc_exp_itm->task_id = $item->task_id;
            $purc_exp_itm->qty = $item->qty;
            $purc_exp_itm->unit = $item->unit_id;
            $purc_exp_itm->rate = $item->rate;
            $purc_exp_itm->budget = $item->amount;
            $purc_exp_itm->vat_id = $item->amount;
            $purc_exp_itm->total_budget = $item->total_amount;
            $purc_exp_itm->vat_id =  $purc_exp_itm->budget < $purc_exp_itm->total_budget ? 1 : 3;
            $purc_exp_itm->item_description = $item->item_description;
            $purc_exp_itm->invoice_id = $invoice->id;
            $purc_exp_itm->paid_amount = $sale->paid_amount > 0 ? $item->amount : 0;
            $purc_exp_itm->due_amount = $sale->paid_amount > 0 ? 0 : $item->amount;
            $purc_exp_itm->save();
         }

             $jProject = JobProject::find($invoice->job_project_id);
        if ($jProject) {

            $jProject->retention_amount = $jProject->retention_amount - $invoice->budget;
            $jProject->save();

         $journal_no = $this->journal_no();
            $journal = new Journal();
            $journal->project_id        = 1;
            $journal->invoice_id        = $invoice->id;
            $journal->transection_type = 'Sale';
            $journal->transaction_type = 'Increase';
            $journal->journal_no        = $journal_no;
            $journal->date              =  $invoice->date;
            $journal->pay_mode          = 'CREDIT';
            $journal->cost_center_id    = 0;
            $journal->party_info_id     = $invoice->customer_id;
            $journal->account_head_id   = 123;
            $journal->voucher_type   = 'CREDIT';
            $journal->amount            = $invoice->total_budget;
            $journal->tax_rate          = 0;
            $journal->vat_amount        = $invoice->total_budget - $invoice->budget;
            $journal->total_amount      = $invoice->budget;
            $journal->gst_subtotal = 0;
            $journal->narration         =  $invoice->narration;
            $journal->approved_by = $invoice->approved_by;
            $journal->save();
            $retentionAmount =  $invoice->budget;
            $ac_head = AccountHead::find(3);
            $jl_record = new JournalRecord();
            $jl_record->journal_id          = $journal->id;
            $jl_record->project_details_id  = $journal->project_id;
            $jl_record->cost_center_id      = $journal->cost_center_id;
            $jl_record->party_info_id       = $journal->party_info_id;
            $jl_record->journal_no          = $journal->journal_no;
            $jl_record->account_head_id     = $ac_head->id;
            $jl_record->master_account_id   = $ac_head->master_account_id;
            $jl_record->account_head        = $ac_head->fld_ac_head;
            $jl_record->amount              = $invoice->due_amount;
            $jl_record->total_amount        = $invoice->due_amount;
            $jl_record->vat_rate_id         = 0;
            $jl_record->transaction_type    = 'DR';
            $jl_record->journal_date        = $journal->date;
            $jl_record->invoice_no          = 'N/A';
            $jl_record->account_type_id     = $ac_head->account_type_id;
            $jl_record->is_main_head        = 0;
            $jl_record->project_id          = $invoice->job_project_id;
            $jl_record->save();
             $ac_head = AccountHead::find(1759);
            $jl_record = new JournalRecord();
            $jl_record->journal_id          = $journal->id;
            $jl_record->project_details_id  = $journal->project_id;
            $jl_record->cost_center_id      = $journal->cost_center_id;
            $jl_record->party_info_id       = $journal->party_info_id;
            $jl_record->journal_no          = $journal->journal_no;
            $jl_record->account_head_id     = $ac_head->id;
            $jl_record->master_account_id   = $ac_head->master_account_id;
            $jl_record->account_head        = $ac_head->fld_ac_head;
            $jl_record->amount              = $retentionAmount;
            $jl_record->total_amount        = $retentionAmount;
            $jl_record->vat_rate_id         = 0;
            $jl_record->transaction_type    = 'CR';
            $jl_record->journal_date        = $journal->date;
            $jl_record->invoice_no          = 'N/A';
            $jl_record->account_type_id     = $ac_head->account_type_id;
            $jl_record->is_main_head        = 0;
            $jl_record->project_id          = $invoice->job_project_id;
            $jl_record->save();

              if ($journal->vat_amount > 0) {
                $vat_ac_head = AccountHead::find(17); // vat account head
                $jl_record = new JournalRecord();
                $jl_record->journal_id     = $journal->id;
                $jl_record->project_details_id  = $journal->project_id;
                $jl_record->cost_center_id      = $journal->cost_center_id;
                $jl_record->party_info_id       = $journal->party_info_id;
                $jl_record->journal_no          =  $journal->journal_no;
                $jl_record->account_head_id     = $vat_ac_head->id;
                $jl_record->master_account_id   = $vat_ac_head->master_account_id;
                $jl_record->account_head        = $vat_ac_head->fld_ac_head;
                $jl_record->amount              = $journal->vat_amount;
                $jl_record->invoice_no              = 'N/A';
                $jl_record->total_amount        = $journal->vat_amount;
                $jl_record->vat_rate_id         = 0;
                $jl_record->transaction_type    = 'CR';
                $jl_record->journal_date        = $journal->date;
                $jl_record->account_type_id = $vat_ac_head->account_type_id;
                $jl_record->project_id          = $invoice->job_project_id;
                $jl_record->is_main_head        = 0;
                $jl_record->save();
            }





             $ac_head = AccountHead::find(1760);
            $jl_record = new JournalRecord();
            $jl_record->journal_id          = $journal->id;
            $jl_record->project_details_id  = $journal->project_id;
            $jl_record->cost_center_id      = $journal->cost_center_id;
            $jl_record->party_info_id       = $journal->party_info_id;
            $jl_record->journal_no          = $journal->journal_no;
            $jl_record->account_head_id     = $ac_head->id;
            $jl_record->master_account_id   = $ac_head->master_account_id;
            $jl_record->account_head        = $ac_head->fld_ac_head;
            $jl_record->amount              = $retentionAmount;
            $jl_record->total_amount        = $retentionAmount;
            $jl_record->vat_rate_id         = 0;
            $jl_record->transaction_type    = 'DR';
            $jl_record->journal_date        = $journal->date;
            $jl_record->invoice_no          = 'N/A';
            $jl_record->account_type_id     = $ac_head->account_type_id;
            $jl_record->is_main_head        = 0;
            $jl_record->project_id          = $invoice->job_project_id;
            $jl_record->save();

             $ac_head = AccountHead::find(7);
            $jl_record = new JournalRecord();
            $jl_record->journal_id          = $journal->id;
            $jl_record->project_details_id  = $journal->project_id;
            $jl_record->cost_center_id      = $journal->cost_center_id;
            $jl_record->party_info_id       = $journal->party_info_id;
            $jl_record->journal_no          = $journal->journal_no;
            $jl_record->account_head_id     = $ac_head->id;
            $jl_record->master_account_id   = $ac_head->master_account_id;
            $jl_record->account_head        = $ac_head->fld_ac_head;
            $jl_record->amount              = $retentionAmount;
            $jl_record->total_amount        = $retentionAmount;
            $jl_record->vat_rate_id         = 0;
            $jl_record->transaction_type    = 'CR';
            $jl_record->journal_date        = $journal->date;
            $jl_record->invoice_no          = 'N/A';
            $jl_record->account_type_id     = $ac_head->account_type_id;
            $jl_record->is_main_head        = 0;
            $jl_record->project_id          = $invoice->job_project_id;
            $jl_record->save();
        }


         $sale->items->each->delete();
        $sale->delete();

        return $invoice;


    }

    public function sale_approval(Request $request,$id)
    {

        $sale = Sale::find($id);


        if ($sale->retention_invoice) {
           $invoice =  $this->retentionInvoice($sale);
        } else {
            $invoice = $this->normalInvoice($sale);
        }

        if($request->ajax()){
            return response()->json([
                'id' => $invoice->id,
                'party_id' => $sale->party_id,
                'advance' => $sale->party->balance,
                'due_amount' => $sale->due_amount,
            ]);
        }

         $notification = array(
            'message'       => 'Invoice Approved Successfully!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function sale_delete($id)
    {
        $sale = Sale::find($id);
        $party_info = PartyInfo::find($sale->party_id);
        $party_info->balance +=  $sale->advance_paid_amount;
        $party_info->save();
        $sale->items->each->delete();
        $sale->delete();
        $notification = array(
            'message'       => 'Deleted Successfully!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    private function fileUpload($file, $invoice_id)
    {
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $ext = $file->getClientOriginalExtension();
        $voucher_file_name = $name . time() . '.' . $ext;

        // Store file
        $file->storeAs('public/upload/sale', $voucher_file_name);

        // Optional: Save to database
        SaleVoucher::create([
            'temp_invoice_id' => $invoice_id,
            'file_path' => 'upload/sale/' . $voucher_file_name,
        ]);
    }

    public function saleIssueEdit(Request $request)
    {

        $request->validate(
            [
                'date'              =>  'required',
                'party_info'        => 'required',
                // 'narration'         => 'required'
            ],
            [
                'date.required'         => 'Date is required',
                'party_info.required'   => 'Party Info is required',
                'pay_mode.required'     => 'Pay Mode is required',
                // 'narration.required'    => 'Narration is required',
            ]
        );
        // return($request);

        $sale = Sale::find($request->id);
        $party_info = PartyInfo::find($request->party_info);
        $party_info->balance += $sale->advance_paid_amount;
        $party_info->save();
        $paid_amount = 0;
        $advance_paid_amount = 0;
        $due_amount = 0;
        if ($request->pay_mode == 'Advance') {
            if ($party_info->balance > $request->total_amount) {
                $advance_paid_amount = $request->total_amount;
                $paid_amount = $request->total_amount;

                $party_info->balance = $party_info->balance - $request->total_amount;
                $party_info->save();
            } elseif ($party_info->balance == $request->total_amount) {
                $advance_paid_amount = $request->total_amount;
                $paid_amount = $request->total_amount;

                $party_info->balance = $party_info->balance - $request->total_amount;
                $party_info->save();
            } else {
                $advance_paid_amount = $request->total_amount - $party_info->balance;
                $due_amount = $request->total_amount - $party_info->balance;
                $paid_amount = $request->total_amount - $due_amount;

                $party_info->balance = 0.00;
                $party_info->save();
            }
        } elseif ($request->pay_mode == 'Credit') {
            $paid_amount = 0;
            $due_amount = $request->total_amount;
        } else {
            $paid_amount = 0;
            $due_amount = $request->total_amount;
        }
        if ($request->invoice_type == 'Direct Invoice') {
            if ($sale->invoice_no_s_d == '') {
                $sale_no = $this->direct_sale_no();
                $sale->invoice_no_s_d = $sale_no;
                $sales_invoice = InvoiceNumber::first();
                $sales_invoice->invoice_no_s_d = $sale_no;
                $sales_invoice->save();
            }
        } elseif ($request->invoice_type == 'Tax Invoice') {
            if ($sale->invoice_no == '') {
                $sale_no = $this->sale_no();
                $sale->invoice_no = $sale_no;
                $sales_invoice = InvoiceNumber::first();
                $sales_invoice->invoice_no = $sale_no;
                $sales_invoice->save();
            }
        } else {
            if ($sale->proforma_invoice_no == '') {
                $sale_no = $this->p_sale_no();
                $sale->proforma_invoice_no = $sale_no;
                $sales_invoice = InvoiceNumber::first();
                $sales_invoice->proforma_invoice_no = $sale_no;
                $sales_invoice->save();
            }
        }
        $update_date_format = $this->dateFormat($request->date);
        $sale->date = $update_date_format;
        $sale->pay_mode =  $request->pay_mode;
        $sale->project_id = $request->project;
        $sale->invoice_type = $request->invoice_type;

        $sale->head_id = 0;
        $sale->total_amount = $request->total_amount;
        $sale->vat = $request->total_vat;
        $sale->amount = $request->taxable_amount;
        $sale->party_id =  $request->party_info;
        $sale->attention =  $request->attention;
        $sale->narration = 0;
        $sale->edited_by = Auth::id();
        $sale->gst_subtotal = 0;
        $sale->advance_paid_amount = $advance_paid_amount;
        $sale->paid_amount = $paid_amount;
        $sale->due_amount = $due_amount;

        if ($request->pay_mode == 'Cheque') {
            $sale->issuing_bank = $request->issuing_bank;
            $sale->branch = $request->bank_branch;
            $sale->cheque_no =  $request->cheque_no;
            $sale->deposit_date = $this->dateFormat($request->deposit_date);
        }
        $sale->do_no = $request->do_no;
        $sale->lpo_no =  $request->lpo_no;
        $sale->quotation_no =  $request->quotation_no;
        $sale->site_project =  $request->job_project_id;

        if ($request->hasFile('voucher_scan')) {
            foreach ($request->file('voucher_scan') as $file) {
                $this->fileUpload($file, $sale->id);
            }
        }

        $sale->save();

        foreach ($sale->items as $item) {
            $item->delete();
        }
        //end purchase expense entry
        $multi_head = $request->input('group-a');
        $t_cogs = 0;
        foreach ($multi_head as $each_head) {
            //purchase record
            $sale_item = new SaleItem();
            $sale_item->item_description = $each_head['multi_acc_head'];
            $sale_item->qty = $each_head['qty'];
            $sale_item->unit_id = $each_head['unit'];
            $sale_item->rate = $each_head['rate'];
            $sale_item->amount = $each_head['amount'];
            $sale_item->vat = $each_head['vat_amount'];
            $sale_item->total_amount = $sale_item->amount + $sale_item->vat;
            $sale_item->party_id = $request->party_info;
            $sale_item->sale_id = $sale->id;
            $sale_item->gst_subtotal = 0;
            $sale_item->save();

            //end purchase record
        }
        //end records entry
        $sale = Sale::with('documents')->find($sale->id);
        $projects = ProjectDetail::all();
        $modes = PayMode::get();
        $terms = PayTerm::all();
        $sub_invoice = Carbon::now()->format('Ymd');
        $cCenters = CostCenter::all();
        $txnTypes = TxnType::all();
        // $acHeads = AccountHead::where('id',476)->get();
        $acHeads = AccountHead::where('account_type_id', '1')->where('fld_definition', 'Sell of Asset')
            ->get();
        $parties = PartyInfo::where('pi_type', 'Customer')->get();
        $pInfos  = PartyInfo::where('pi_type', 'Customer')->get();
        $vats = VatRate::orderBy('id', 'desc')->get();
        $sale_no = $sale->invoice_no;
        $sales = Sale::where('authorized', true)->orderBy('date', 'DESC')->paginate(20);

        return response()->json([
            'preview' =>  view('backend.sale.authorize-preview', compact('sale', 'pInfos', 'vats', 'sale_no', 'projects', 'modes'))->render(),
            'approve_list' => view('backend.sale._ajax_approve_list', compact('sales'))->render(),
        ]);
    }

    public function receivable(Request $request)
    {
        $party_id = $request->party_search;

        $suppliers = PartyInfo::where('pi_type', 'Customer')->get();
        $invoices = JobProjectInvoice::orderBy('id', 'desc')->when($party_id, function($q) use($party_id){
            $q->where('customer_id', $party_id);
        })->paginate(80);

        $data = [
            'project' => [],
            'unlinked' => [] // unlinked invoices
        ];

        $invoices->each(function ($invoice) use (&$data) {
            if ($invoice->job_project_id) {
                $projectId = $invoice->job_project_id;
                $project_name = null;

                $project = JobProject::find($projectId);
                if ($project) {
                    $new_project = $project->new_project;
                    $project_name = $new_project->name ?? $project->project_name;
                    $contract_amount = $new_project->total_amount ?? $project->total_budget;
                    $retention_amount = $project->retention_amount ?? 00;
                }

                if (!isset($data['project'][$projectId])) {
                    $data['project'][$projectId] = [
                        'total_invoice' => 0,
                        'total_amount' => 0,
                        'paid_amount' => 0,
                        'due_amount' => 0,
                        'project_name' => $project_name,
                        'party_name' => optional($invoice->party)->pi_name,
                        'contract_amount' => $contract_amount,
                        'retention_amount' =>  $retention_amount,
                        'plot_no' => $new_project ? $new_project->plot : ' ',
                        'location' => $new_project ? $new_project->location : ' ',
                    ];
                }

                $data['project'][$projectId]['total_invoice'] += 1;
                $data['project'][$projectId]['total_amount'] += $invoice->total_budget;
                $data['project'][$projectId]['paid_amount'] += $invoice->paid_amount;
                $data['project'][$projectId]['due_amount'] += $invoice->due_amount;
            } else {
                // Collect each unlinked invoice separately
                $data['unlinked'][] = [
                    'total_invoice' => 1,
                    'total_amount' => $invoice->total_budget,
                    'paid_amount' => $invoice->paid_amount,
                    'due_amount' => $invoice->due_amount,
                    'project_name' => '---',
                    'party_name' => optional($invoice->party)->pi_name,
                ];
            }
        });

        return view('backend.sale.receivable', compact('data', 'suppliers', 'invoices'));
    }


    public function search_customer_due(Request $request)
    {
        $suppliers = DB::table('party_infos')
            ->where('party_infos.pi_type', 'Customer')
            ->where('party_infos.id', '=', $request->party)
            ->join('job_project_invoices', 'party_infos.id', '=', 'job_project_invoices.customer_id')
            ->select(
                'party_infos.id',
                'party_infos.pi_code',
                'party_infos.pi_name',
                DB::raw('SUM(CASE WHEN job_project_invoices.customer_id =party_infos.id  THEN job_project_invoices.due_amount ELSE 0 END ) as due_amount')
            )
            ->groupBy('party_infos.id', 'party_infos.pi_code', 'party_infos.pi_name')
            ->orderByDesc('due_amount')
            ->get();
        return view('backend.sale.receivable-table', compact('suppliers'));
    }


    public function receivable_view(Request $request)
    {
        $id = $request->id;
        $type = $request->type;

        if ($type == 'project') {
            $invoices = JobProjectInvoice::where('job_project_id', $id)->get();
        } else {
            $invoices = JobProjectInvoice::where('id', $id)->get();
        }

        return view('backend.sale.receivable-view', compact('invoices'));
    }
    public function all_invoice_list()
    {
        $parties = PartyInfo::get();
        $sales = JobProjectInvoice::orderBy('id', 'desc')->paginate(20);
        $i = 0;
        return view('backend.sale.all-invoice-list', compact('sales', 'i', 'parties'));
    }
    public function search_all_invoice_list(Request $request)
    {
        $sales = JobProjectInvoice::orderBy('id', 'desc');
        $pending_sales = Sale::orderBy('id', 'desc');
        if ($request->value) {
            $sales = $sales->where('invoice_no', 'like', '%' . $request->value . '%');
            $pending_sales = $pending_sales->where('invoice_no', 'like', '%' . $request->value . '%');
        }
        if ($request->party) {
            $sales = $sales->where('customer_id', $request->party);
            $pending_sales = $pending_sales->where('party_id', $request->party);
        }
        if ($request->date) {
            $date = $this->dateFormat($request->date);
            $sales = $sales->where('date', $date);
            $pending_sales = $pending_sales->where('date', $date);
        }
        $sales = $sales->get();
        $pending_sales = $pending_sales->get();
        return view('backend.sale.search-all-invoice', compact('sales', 'pending_sales'));
    }

    public function transection()
    {
        $parties = PartyInfo::get();
        $transections = DB::select("
        SELECT id AS id, customer_id AS party_id, date AS date, invoice_no AS transection_no, invoice_type AS invoice_type, total_budget AS amount, 'Invoice' AS data_from FROM job_project_invoices WHERE invoice_type != 'Direct Invoice' AND 'id' < 0
        UNION
        SELECT id AS id, party_id AS party_id, date AS date, receipt_no AS transection_no, 'Receipt' AS invoice_type,total_amount AS amount ,'Receipt' AS data_from FROM receipts WHERE 'id' < 0
        ;
    ");

        $i = 0;
        return view('backend.sale.transections', compact('transections', 'i', 'parties'));
    }


    public function search_all_transection_list(Request $request)
    {
        // return $request->all();
        $parties = PartyInfo::get();
        if ($request->party) {
            $transections = DB::select("
            SELECT id AS id, customer_id AS party_id, date AS date, invoice_no AS transection_no, invoice_type AS invoice_type, total_budget AS amount, 'Invoice' AS data_from FROM job_project_invoices WHERE customer_id = $request->party AND invoice_type != 'Direct Invoice'
            UNION
            SELECT id AS id, party_id AS party_id, date AS date, receipt_no AS transection_no, 'Receipt' AS invoice_type,total_amount AS amount ,'Receipt' AS data_from FROM receipts WHERE party_id = $request->party
            ;
        ");
        } else {
            $transections = DB::select("
            SELECT id AS id, customer_id AS party_id, date AS date, invoice_no AS transection_no, invoice_type AS invoice_type, total_budget AS amount, 'Invoice' AS data_from FROM job_project_invoices WHERE invoice_type != 'Direct Invoice'
            UNION
            SELECT id AS id, party_id AS party_id, date AS date, receipt_no AS transection_no, 'Receipt' AS invoice_type,total_amount AS amount ,'Receipt' AS data_from FROM receipts
            ;
        ");
        }

        $i = 0;
        return view('backend.sale.transections_items', compact('transections', 'i', 'parties'));
    }

    public function voucherDelete($id)
    {
        $document = SaleVoucher::findOrFail($id);

        if (Storage::exists('public/' . $document->file_path)) {
            Storage::delete('public/' . $document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Voucher deleted successfully.');
    }

    public function retentionForm(Request $request){
        $id = $request->id;

        $project = JobProject::find($id);

        if($project){
            return view('backend.sale.retention-form', compact( 'project'));
        }

    }
}

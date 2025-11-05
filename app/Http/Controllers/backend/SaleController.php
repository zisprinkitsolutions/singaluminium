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
use App\AccountSubHead;
use App\Imports\ReceiptImport;
use App\LpoBill;
use App\Subsidiary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use PDO;
use App\TempReceiptVoucherDetail;
use function GuzzleHttp\Promise\all;
use App\Imports\SalesImport;
use Excel;
use Illuminate\Support\Facades\DB;

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
    public function generateInvoiceNo($company_id = null)
    {
        $yearPrefix = 'TI' . Carbon::now()->format('y');

        $query = JobProjectInvoice::whereNotNull('invoice_no')
            ->where('invoice_no', 'like', $yearPrefix . '%');

        if ($company_id) {
            $query->where('compnay_id', $company_id);
        } else {
            $query->whereNull('compnay_id');
        }

        $latestInvoice = $query->orderByDesc('id')->first();

        if ($latestInvoice) {
            // Extract the numeric part after the year prefix
            $number = (int) preg_replace('/^' . $yearPrefix . '/', '', $latestInvoice->invoice_no);
            $number++;
        } else {
            $number = 1;
        }

        // Final invoice number padded to 4 digits
        $invoice_no = $yearPrefix . str_pad($number, 4, '0', STR_PAD_LEFT);

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
    public function generateProformaInvoiceNo($company_id = null)
    {
        // Prefix with PI + last two digits of the year (e.g., PI25 for 2025)
        $yearPrefix = 'PI' . Carbon::now()->format('y');

        // Query the latest proforma invoice for the current year and company
        $query = Sale::whereNotNull('proforma_invoice_no')
            ->where('proforma_invoice_no', 'like', $yearPrefix . '%');

        if ($company_id) {
            $query->where('compnay_id', $company_id);
        } else {
            $query->whereNull('compnay_id');
        }

        $latestInvoice = $query->orderByDesc('id')->first();

        if ($latestInvoice) {
            // Extract the numeric part after the year prefix
            $number = (int) preg_replace('/^' . $yearPrefix . '/', '', $latestInvoice->proforma_invoice_no);
            $number++;
        } else {
            $number = 1;
        }

        // Final invoice number padded to 4 digits
        $invoice_no = $yearPrefix . str_pad($number, 4, '0', STR_PAD_LEFT);

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
        Gate::authorize('Revenue_Edit');
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

        $project = JobProject::find($sales->site_project);
        $contract = $project->budget;
        $retention = $project->invoices->sum('retention_amount');
        $inv_amount = $project->invoices->sum('budget');
        $remaining = $contract - $retention - $inv_amount;
        $remaining_vat = $remaining * (5 / 100);
        $total_remaining = $remaining_vat + $remaining;

        return view('backend.sale.proforma-invoice-edit', compact('total_remaining', 'remaining_vat', 'remaining', 'inv_amount', 'retention', 'contract', 'projects', 'sales', 'modes', 'terms', 'pInfos', 'cCenters', 'txnTypes', 'acHeads', 'vats', 'parties', 'units'));
    }


    public function create_form()
    {
        Gate::authorize('Invoice');
        $units = Unit::orderBy('name')->get();
        $companies = Subsidiary::orderBy('id', 'desc')->get();
        $parties = PartyInfo::where('pi_type', 'Customer')->get();
        $sales = JobProjectInvoice::where('invoice_type', 'Tax Invoice')->where('compnay_id', null)->orderBy('id', 'DESC');
        // ->paginate(20);
        $cal_sales_total_budget = (clone $sales)->get()->sum('total_budget');
        $cal_sales_paid_amount = (clone $sales)->get()->sum('paid_amount');
        $cal_sales_due_amount = (clone $sales)->get()->sum('due_amount');
        $sales = $sales->paginate(20);
        $i = 0;
        $pending_sales = Sale::where('authorized', true)->where('compnay_id', null)->orderBy('date', 'DESC');
        // ->paginate(20);
        $cal_psales_total_amount = (clone $pending_sales)->get()->sum('total_amount');
        $cal_psales_paid_amount = (clone $pending_sales)->get()->sum('paid_amount');
        $cal_psales_due_amount = (clone $pending_sales)->get()->sum('due_amount');
        $pending_sales = $pending_sales->paginate(20);

        $data['total_amount'] = $cal_sales_total_budget + $cal_psales_total_amount;
        $data['paid_amount'] = $cal_sales_paid_amount + $cal_psales_paid_amount;
        $data['due_amount'] = $cal_sales_due_amount + $cal_psales_due_amount;

        $bank_name = AccountSubHead::where('account_head_id', 2)->get();

        // **************************

        $pInfos  = PartyInfo::where('pi_type', 'Customer')->get();
        $modes = PayMode::whereNotIn('id', [2, 3, 6])->get();

        return view('backend.sale.create-form', compact('pInfos', 'modes'));
    }

    public function saleIssuepost(Request $request)
    {
        Gate::authorize('Revenue_Create');
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

        $responseData = DB::transaction(function () use ($request) {

            // Update date format
            $update_date_format = $this->dateFormat($request->date);

            // company id
            $compnay_id = optional(JobProject::find($request->job_project_id))->compnay_id;

            // amounts
            $advance_paid_amount = 0;
            $paid_amount = 0;
            $due_amount = ($request->total_amount ?? 0) - ($request->retention_transferred ?? 0);

            // Prepare sale data
            $saleData = [
                'date' => $update_date_format,
                'pay_mode' => $request->pay_mode,
                'site_project' => $request->job_project_id,
                'compnay_id' => $compnay_id,
                'project_id' => 0,
                'invoice_type' => $request->invoice_type,
                'head_id' => 0,
                'retention_invoice' => $request->retention_invoice ?? 0,
                'total_amount' => $request->total_amount,
                'retention_amount' => $request->retention_transferred ?? 0,
                'vat' => $request->total_vat,
                'amount' => $request->taxable_amount,
                'party_id' => $request->party_info,
                'do_no' => $request->do_no,
                'lpo_no' => $request->lpo_no,
                'quotation_no' => $request->quotation_no,
                'attention' => $request->attention,
                'narration' => 0,
                'created_by' => Auth::id(),
                'gst_subtotal' => 0,
                'advance_paid_amount' => $advance_paid_amount,
                'paid_amount' => $paid_amount,
                'due_amount' => $due_amount,
                'authorized' => true,
            ];

            // Generate invoice number
            if ($request->invoice_type == 'Direct Invoice') {
                $saleData['invoice_no_s_d'] = $this->direct_sale_no();
                $sale_no = $saleData['invoice_no_s_d'];
            } elseif ($request->invoice_type == 'Tax Invoice') {
                $saleData['invoice_no'] = $this->sale_no();
                $sale_no = $saleData['invoice_no'];
            } else {
                $saleData['proforma_invoice_no'] = $this->generateProformaInvoiceNo($compnay_id);
                $sale_no = $saleData['proforma_invoice_no'];
            }

            // Cheque details
            if ($request->pay_mode == 'Cheque') {
                $saleData['issuing_bank'] = $request->issuing_bank;
                $saleData['branch'] = $request->bank_branch;
                $saleData['cheque_no'] = $request->cheque_no;
                $saleData['deposit_date'] = $this->dateFormat($request->deposit_date);
            }

            // Save Sale
            $sale = Sale::create($saleData);

            // File upload
            if ($request->hasFile('voucher_scan')) {
                foreach ($request->file('voucher_scan') as $file) {
                    $this->fileUpload($file, $sale->id);
                }
            }

            // Update InvoiceNumber (only one query)
            $sales_invoice = InvoiceNumber::first();
            if ($request->invoice_type == 'Direct Invoice') {
                $sales_invoice->invoice_no_s_d = $sale->invoice_no_s_d;
            } elseif ($request->invoice_type == 'Tax Invoice') {
                $sales_invoice->invoice_no = $sale->invoice_no;
            } else {
                $sales_invoice->proforma_invoice_no = $sale->proforma_invoice_no;
            }
            $sales_invoice->save();

            // Handle SaleItems in bulk insert
            $multi_head = $request->input('group-a', []);
            if (!empty($multi_head)) {
                $items = [];
                foreach ($multi_head as $each_head) {
                    $amount = $each_head['amount'] ?? 0;
                    $vat = $each_head['vat_amount'] ?? 0;

                    $items[] = [
                        'task_id' => $each_head['task_id'] ?? null,
                        'item_description' => $each_head['multi_acc_head'] ?? '',
                        'qty' => 1,
                        'unit_id' => '-',
                        'rate' => $amount,
                        'amount' => $amount,
                        'vat' => $vat,
                        'total_amount' => $amount + $vat,
                        'party_id' => $request->party_info,
                        'sale_id' => $sale->id,
                        'gst_subtotal' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                SaleItem::insert($items);
            }

            // Load data for preview
            $projects = ProjectDetail::all();
            $modes = PayMode::all();
            $pInfos = PartyInfo::where('pi_type', 'Customer')->get();
            $vats = VatRate::orderBy('id', 'desc')->get();
            $sales = Sale::where('authorized', true)->orderBy('date', 'DESC')->paginate(20);

            // Collect response
            return [
                'preview' => view('backend.sale.authorize-preview', compact('sale', 'pInfos', 'vats', 'sale_no', 'projects', 'modes'))->render(),
                'approve_list' => view('backend.sale._ajax_approve_list', compact('sales'))->render(),
            ];
        });

        // Return response outside transaction
        return response()->json($responseData);
    }

    public function sale_list(Request $request)
    {
        Gate::authorize('Invoice');
        $units = Unit::orderBy('name')->get();
        $companies = Subsidiary::orderBy('id', 'desc')->get();
        $modes = PayMode::whereNotIn('id', [2, 3, 6])->get();
        $parties = PartyInfo::where('pi_type', 'Customer')->get();
        $pInfos  = PartyInfo::where('pi_type', 'Customer')->get();
        $sales = JobProjectInvoice::where('invoice_type', 'Tax Invoice')->where('compnay_id', null)->orderBy('id', 'DESC');
        // ->paginate(20);
        $cal_sales_total_budget = (clone $sales)->get()->sum('total_budget');
        $cal_sales_paid_amount = (clone $sales)->get()->sum('paid_amount');
        $cal_sales_due_amount = (clone $sales)->get()->sum('due_amount');
        $sales = $sales->paginate(20);
        $i = 0;
        $pending_sales = Sale::where('authorized', true)->where('compnay_id', null)->orderBy('date', 'DESC');
        // ->paginate(20);
        $cal_psales_total_amount = (clone $pending_sales)->get()->sum('total_amount');
        $cal_psales_paid_amount = (clone $pending_sales)->get()->sum('paid_amount');
        $cal_psales_due_amount = (clone $pending_sales)->get()->sum('due_amount');
        $pending_sales = $pending_sales->paginate(20);

        $data['total_amount'] = $cal_sales_total_budget + $cal_psales_total_amount;
        $data['paid_amount'] = $cal_sales_paid_amount + $cal_psales_paid_amount;
        $data['due_amount'] = $cal_sales_due_amount + $cal_psales_due_amount;

        $bank_name = AccountSubHead::where('account_head_id', 2)->get();
        return view('backend.sale.all-invoice-list', compact('sales', 'i', 'parties', 'pending_sales', 'pInfos', 'modes', 'units', 'bank_name', 'companies', 'data'));
    }

    public function sale_list_ajax(Request $request)
    {
        $date_from = $request->date_from ? $this->dateFormat($request->date_from) : null;
        $date_to   = $request->date_to ? $this->dateFormat($request->date_to) : null;
        $month     = $request->month ?? null; // YYYY-MM from <input type="month">
        $year      = $request->year ?? null;  // from <input type="number">

        Gate::authorize('Invoice');

        $units = Unit::orderBy('name')->get();
        $companies = Subsidiary::orderBy('id', 'desc')->get();
        $modes = PayMode::whereNotIn('id', [2, 3, 6])->get();
        $parties = PartyInfo::where('pi_type', 'Customer')->get();
        $pInfos  = PartyInfo::where('pi_type', 'Customer')->get();

        $sales = JobProjectInvoice::query()
            ->where('invoice_type', 'Tax Invoice')

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

            ->orderBy('id', 'DESC');

        $grandTotal = (clone $sales);
        $paginatedSales = $sales->paginate(20)->appends(request()->except('page'));

        $i = 0;
        $bank_name = AccountSubHead::where('account_head_id', 2)->get();

        return view('backend.sale._ajax_all-invoice-list', compact(
            'paginatedSales',
            'grandTotal',
            'i',
            'parties',
            'pInfos',
            'modes',
            'units',
            'bank_name',
            'companies',
            'date_from',
            'date_to',
            'month',
            'year'
        ));
    }

    public function retention_list_ajax(Request $request)
    {
        $date_from = $request->date_from ? $this->dateFormat($request->date_from) : null;
        $date_to   = $request->date_to ? $this->dateFormat($request->date_to) : null;
        $month     = $request->month ?? null; // expects format "YYYY-MM"
        $year      = $request->year ?? null;
        $retention = $request->retention ?? null;

        Gate::authorize('Invoice');

        $sales = JobProjectInvoice::query()
            ->where('invoice_type', 'Tax Invoice')
            // Date range filter
            ->when($date_from && $date_to, function ($query) use ($date_from, $date_to) {
                $query->whereDate('date', '>=', $date_from)
                    ->whereDate('date', '<=', $date_to);
            })
            ->when($date_from && !$date_to, function ($query) use ($date_from) {
                $query->whereDate('date', $date_from);
            })
            ->when(!$date_from && $date_to, function ($query) use ($date_to) {
                $query->whereDate('date', $date_to);
            })
            // Month filter if dates are blank
            ->when(!$date_from && !$date_to && $month, function ($query) use ($month) {
                [$year, $mon] = explode('-', $month); // month input format "YYYY-MM"
                $query->whereYear('date', $year)
                    ->whereMonth('date', $mon);
            })
            // Year filter if dates and month are blank
            ->when(!$date_from && !$date_to && !$month && $year, function ($query) use ($year) {
                $query->whereYear('date', $year);
            })
            ->when($retention , function ($query) use ($retention) {
              $query->where('retention_amount', '<', 0);
            })
            ->orderBy('id', 'ASC');

            $grandTotal = (clone $sales);
            $paginatedSales = $sales->paginate(20)->appends(request()->except('page'));

            return view('backend.sale._ajax_all-retention-list', compact('paginatedSales', 'grandTotal', 'date_from', 'date_to' , 'month', 'year'));
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
            $sale = Sale::with('project')->find($request->id);
            $sale_no = $sale->invoice_no;
            return view('backend.sale.authorize-preview', compact('sale', 'pInfos', 'vats', 'sale_no', 'projects', 'modes'));
        }

        public function sale_modal(Request $request)
        {
            $sale = JobProjectInvoice::with('documents')->find($request->id);
            // dd($sale->invoice_from);
            $modes = PayMode::whereIn('id', [1, 5, 7, 4])->get();
            $bank_name = AccountSubHead::where('account_head_id', 2)->get();
            if ($sale->invoice_from == 'project') {
                $invoice = $sale;
                $standard = VatRate::where('name', 'Standard')->first();
                $notes = JobProjectInvoice::where('id', '<=', $sale->id)->where('job_project_id', $sale->job_project_id)->orderBy('id', 'asc')->get();
                // return view('backend.sale.approve-invoice-view', compact('invoice', 'standard', 'notes'));
                return view('backend.job-project-invoice.approve-invoice-view', compact('invoice', 'standard', 'notes'));
            } else {
                return view('backend.sale.preview', compact('sale', 'modes', 'bank_name'));
            }
        }

        public function home_sale_view(Request $request)
        {
            $sale = JobProjectInvoice::with(['tasks', 'project', 'party'])->find($request->id);

            return response()->json($sale);
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
                $imageUrl = asset('img/laterhead.jpg');
                if ($sale->subsidiary && $sale->subsidiary->image) {
                    $imageUrl = asset('storage/' . $sale->subsidiary->image);
                }
                return view('backend.sale.sale_print', compact('sale', 'imageUrl'));
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
            $imageUrl = asset('img/laterhead.jpg');
            if ($sale->subsidiary && $sale->subsidiary->image) {
                $imageUrl = asset('storage/' . $sale->subsidiary->image);
            }
            return view('backend.sale.auth_sale_print', compact('sale', 'pInfos', 'vats', 'sale_no', 'projects', 'modes', 'imageUrl'));
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
            $projects = JobProject::where('customer_id', $info->id)->get();
            if ($request->ajax()) {
                return Response()->json([
                    'page' => view('backend.sale.receipt-invoice', ['invoices' => $invoices, 'i' => 1])->render(),
                    'info' => $info,
                    'projects' => $projects,
                    'due' =>  $invoices->where('due_amount', '>', 0)->sum('due_amount')
                ]);
            }
        }

        public function projectReceipt(Request $request)
        {
            $date = $request->date ? $this->dateFormat($request->date) : date('d/m/Y');
            $project = JobProject::where('id', $request->project)->first();
            $invoices = JobProjectInvoice::where('date', '<=', $date)->where('due_amount', '>', 0)->where('job_project_id', $project->id)->get();
            $due = $invoices->where('due_amount', '>', 0)->sum('due_amount');
            if ($request->ajax()) {
                return Response()->json([
                    'page' => view('backend.sale.receipt-invoice', ['invoices' => $invoices, 'i' => 1])->render(),
                    'due' =>  $invoices->where('due_amount', '>', 0)->sum('due_amount'),
                    'project' => $project

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
            Gate::authorize('Receipt_Voucher');
            $sales = Sale::where('due_amount', '>', 0)->get();

            $receipt_list = Receipt::select('*')->selectRaw('(SELECT SUM(total_amount) FROM receipts WHERE company_id IS NULL) as grand_total')->orderBy('id', 'desc')->where('company_id', null);
            // ->paginate(40);
            $cal_receipt_list_total_amount = (clone $receipt_list)->get()->sum('total_amount');
            $receipt_list = $receipt_list->paginate(40);

            $parties = PartyInfo::get();
            $i = 0;
            $modes = PayMode::whereNotIn('id', [2, 3, 6])->get();
            $temp_receipt_list = TempReceiptVoucher::orderBy('id', 'desc')->where('company_id', null);
            // ->paginate(40);
            $cal_temp_receipt_list_total_amount = (clone $temp_receipt_list)->get()->sum('total_amount');
            $temp_receipt_list = $temp_receipt_list->paginate(20);

            $data['total_amount'] = $cal_receipt_list_total_amount + $cal_temp_receipt_list_total_amount;

            $bank_name = AccountSubHead::where('account_head_id', 2)->get();
            $companies = Subsidiary::orderBy('id', 'desc')->get();
            $projects = JobProject::get();
            return view('backend.sale.receipt-list', compact('sales', 'i', 'parties', 'modes', 'receipt_list', 'temp_receipt_list', 'bank_name', 'companies', 'projects', 'data'));
        }

        public function receipt_list_modal(Request $request)
        {
            $recept = Receipt::find($request->id);
            $modes = PayMode::whereIn('id', [1, 7])->get();
            $imageUrl = asset('img/laterhead.jpg');
            if ($recept->subsidiary && $recept->subsidiary->image) {
                $imageUrl = asset('storage/' . $recept->subsidiary->image);
            }
            return view('backend.sale.receipt-preview', compact('recept', 'modes', 'imageUrl'));
        }


        public function search_receipt(Request $request)
        {
            // Base queries
            $receipt_list = Receipt::orderBy('id', 'desc')
                ->where('receipt_no', 'like', "%{$request->value}%");

            $temp_receipt_list = TempReceiptVoucher::where('receipt_no', 'like', "%{$request->value}%");

            // Filter by party
            if (!empty($request->party)) {
                $receipt_list->where('party_id', $request->party);
                $temp_receipt_list->where('party_id', $request->party);
            }

            // Filter by date
            if (!empty($request->date)) {
                $date = $this->dateFormat($request->date);
                $receipt_list->where('date', $date);
                $temp_receipt_list->where('date', $date);
            }

            // Filter by mode
            if (!empty($request->mode)) {
                $receipt_list->where('pay_mode', $request->mode);
                $temp_receipt_list->where('pay_mode', $request->mode);
            }

            // ✅ Filter by company_id
            if ($request->company_id != '') {
                if ($request->company_id == 0) {
                    // Show receipts where company_id is null
                    $receipt_list->whereNull('company_id');
                    $temp_receipt_list->whereNull('company_id');
                } else {
                    // Filter by given company_id
                    $receipt_list->where('company_id', $request->company_id);
                    $temp_receipt_list->where('company_id', $request->company_id);
                }
            }

            // Final result
            $receipt_list = $receipt_list->get();
            $temp_receipt_list = $temp_receipt_list->get();
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

        return DB::transaction(function () use ($sale) {
                // dd( $this->generateInvoiceNo($sale->compnay_id));
                // 1️⃣ Create JobProjectInvoice using mass assignment
                $invoice = new JobProjectInvoice();
                $invoice->fill([
                    'invoice_no'          => $this->generateInvoiceNo($sale->compnay_id),
                    'proforma_invoice_no' => $sale->proforma_invoice_no,
                    'invoice_no_s_d'      => $sale->invoice_no_s_d,
                    'invoice_from'        => 'Sales',
                    'customer_id'         => $sale->party_id,
                    'compnay_id'          => $sale->compnay_id,
                    'budget'              => $sale->amount,
                    'vat'                 => $sale->vat,
                    'total_budget'        => $sale->total_amount,
                    'date'                => $sale->date,
                    'due_amount'          => $sale->due_amount,
                    'paid_amount'         => $sale->paid_amount,
                    'retention_amount'    => $sale->retention_amount,
                    'advance_paid_amount' => $sale->advance_paid_amount,
                    'narration'           => $sale->narration,
                    'invoice_type'        => 'Tax Invoice',
                    'approved_by'         => Auth::id(),
                    'do_no'               => $sale->do_no,
                    'lpo_no'              => $sale->lpo_no,
                    'quotation_no'        => $sale->quotation_no,
                    'voucher_scan'        => $sale->voucher_scan,
                    'voucher_scan2'       => $sale->voucher_scan2,
                    'attention'           => $sale->attention,
                    'pay_mode'            => $sale->pay_mode,
                    'job_project_id'      => $sale->site_project,
                    'retention_invoice'   => $sale->retention_invoice,
                ]);
                $invoice->save();


                // 2️⃣ Update all documents in bulk
                $sale->documents()->update(['invoice_id' => $invoice->id]);

                // 3️⃣ Prepare JobProjectInvoiceTask bulk insert
                $tasksData = [];
                foreach ($sale->items as $item) {
                    $amount = $item->amount;
                    $total  = $item->total_amount;
                    $tasksData[] = [
                        'task_name'        => $item->item_description,
                        'task_id'          => $item->task_id,
                        'qty'              => $item->qty,
                        'unit'             => $item->unit_id,
                        'rate'             => $item->rate,
                        'budget'           => $amount,
                        'total_budget'     => $total,
                        'vat_id'           => $amount < $total ? 1 : 3,
                        'item_description' => $item->item_description,
                        'invoice_id'       => $invoice->id,
                        'paid_amount'      => $sale->paid_amount > 0 ? $amount : 0,
                        'due_amount'       => $sale->paid_amount > 0 ? 0 : $amount,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ];
                }
                JobProjectInvoiceTask::insert($tasksData);

                // 4️⃣ Update JobProjectTask & JobProject in batch
                $taskIds = $sale->items->pluck('task_id')->toArray();
                $tasks = JobProjectTask::whereIn('id', $taskIds)->with('jobProject')->get()->keyBy('id');

                foreach ($sale->items as $item) {
                    $task = $tasks[$item->task_id] ?? null;
                    if ($task) {
                        $jobProject = $task->jobProject;
                        if ($jobProject) {
                            $jobProject->increment('paid_amount', $item->amount);
                            $jobProject->decrement('due_amount', $item->amount);
                        }
                        $task->increment('revenue', $item->amount);
                        $task->increment('receipt', $sale->paid_amount > 0 ? $item->amount : 0);
                        $task->increment('receivable', $sale->paid_amount > 0 ? 0 : $item->amount);
                    }
                }

                // 5️⃣ Create Journal & JournalRecords
                if ($invoice->invoice_type == 'Tax Invoice') {

                    $journalNo = $this->journal_no();
                    $journal = Journal::create([
                        'project_id'        => 1,
                        'compnay_id'        => $sale->compnay_id,
                        'invoice_id'        => $invoice->id,
                        'transection_type'  => 'Sale',
                        'transaction_type'  => 'Increase',
                        'journal_no'        => $journalNo,
                        'date'              => $invoice->date,
                        'pay_mode'          => 'CREDIT',
                        'cost_center_id'    => 0,
                        'party_info_id'     => $invoice->customer_id,
                        'account_head_id'   => 123,
                        'voucher_type'      => 'CREDIT',
                        'amount'            => $invoice->total_budget,
                        'tax_rate'          => 0,
                        'vat_amount'        => $invoice->vat,
                        'total_amount'      => $invoice->budget,
                        'gst_subtotal'      => 0,
                        'narration'         => $invoice->narration,
                        'approved_by'       => $invoice->approved_by,
                    ]);

                    $journalRecords = [];

                    // Main Account Head
                    $acHead = AccountHead::find(7);
                    $journalRecords[] = [
                        'journal_id'           => $journal->id,
                        'compnay_id'           => $sale->compnay_id,
                        'project_details_id'   => $journal->project_id,
                        'cost_center_id'       => $journal->cost_center_id,
                        'party_info_id'        => $journal->party_info_id,
                        'journal_no'           => $journal->journal_no,
                        'account_head_id'      => $acHead->id,
                        'master_account_id'    => $acHead->master_account_id,
                        'account_head'         => $acHead->fld_ac_head,
                        'amount'               => $invoice->budget + $invoice->retention_amount,
                        'total_amount'         => $invoice->budget + $invoice->retention_amount,
                        'vat_rate_id'          => 0,
                        'invoice_no'           => 0,
                        'transaction_type'     => 'CR',
                        'journal_date'         => $journal->date,
                        'is_main_head'         => 1,
                        'account_type_id'      => $acHead->account_type_id,
                        'job_project_id'       => $invoice->job_project_id,
                    ];

                    // VAT
                    if ($invoice->vat > 0) {
                        $vatAcHead = AccountHead::find(17);
                        $journalRecords[] = [
                            'journal_id'           => $journal->id,
                            'compnay_id'           => $sale->compnay_id,
                            'project_details_id'   => $journal->project_id,
                            'cost_center_id'       => $journal->cost_center_id,
                            'party_info_id'        => $journal->party_info_id,
                            'journal_no'           => $journal->journal_no,
                            'account_head_id'      => $vatAcHead->id,
                            'master_account_id'    => $vatAcHead->master_account_id,
                            'account_head'         => $vatAcHead->fld_ac_head,
                            'amount'               => $invoice->vat,
                            'total_amount'         => $invoice->vat,
                            'vat_rate_id'          => 0,
                            'invoice_no'           => 'N/A',
                            'transaction_type'     => 'CR',
                            'journal_date'         => $journal->date,
                            'account_type_id'      => $vatAcHead->account_type_id,
                            'job_project_id'       => $invoice->job_project_id,
                            'is_main_head'         => 0,
                        ];
                    }

                    // Paymode / Accounts Receivable
                    if ($invoice->due_amount > 0) {
                        $acHead = AccountHead::find(3);
                        $journalRecords[] = [
                            'journal_id'           => $journal->id,
                            'compnay_id'           => $sale->compnay_id,
                            'project_details_id'   => $journal->project_id,
                            'cost_center_id'       => $journal->cost_center_id,
                            'party_info_id'        => $journal->party_info_id,
                            'journal_no'           => $journal->journal_no,
                            'account_head_id'      => $acHead->id,
                            'master_account_id'    => $acHead->master_account_id,
                            'account_head'         => $acHead->fld_ac_head,
                            'amount'               => $invoice->due_amount,
                            'total_amount'         => $invoice->due_amount,
                            'vat_rate_id'          => 0,
                            'invoice_no'           => 'N/A',
                            'transaction_type'     => 'DR',
                            'journal_date'         => $journal->date,
                            'account_type_id'      => $acHead->account_type_id,
                            'job_project_id'       => $invoice->job_project_id,
                            'is_main_head'         => 0,
                        ];
                    }

                    // Retention
                    $jProject = JobProject::find($invoice->job_project_id);
                    if ($jProject && $invoice->retention_amount > 0) {
                        $jProject->increment('retention_amount', $invoice->retention_amount);
                        $retAcHead = AccountHead::find(1759);
                        $journalRecords[] = [
                            'journal_id'           => $journal->id,
                            'compnay_id'           => $sale->compnay_id,
                            'project_details_id'   => $journal->project_id,
                            'cost_center_id'       => $journal->cost_center_id,
                            'party_info_id'        => $journal->party_info_id,
                            'journal_no'           => $journal->journal_no,
                            'account_head_id'      => $retAcHead->id,
                            'master_account_id'    => $retAcHead->master_account_id,
                            'account_head'         => $retAcHead->fld_ac_head,
                            'amount'               => $invoice->retention_amount,
                            'total_amount'         => $invoice->retention_amount,
                            'vat_rate_id'          => 0,
                            'invoice_no'           => 'N/A',
                            'transaction_type'     => 'DR',
                            'journal_date'         => $journal->date,
                            'account_type_id'      => $retAcHead->account_type_id,
                            'job_project_id'       => $invoice->job_project_id,
                            'is_main_head'         => 0,
                        ];
                    }

                    JournalRecord::insert($journalRecords);
                }

                // 6️⃣ Cleanup Sale
                $sale->items()->delete();
                $sale->delete();

                return $invoice->id;
            });
        }

        private function retentionInvoice($sale)
        {
        return DB::transaction(function () use ($sale) {
                // 1️⃣ Create Invoice
                $invoice = JobProjectInvoice::create([
                    'invoice_no'          => $this->generateInvoiceNo($sale->compnay_id),
                    'proforma_invoice_no' => $sale->proforma_invoice_no,
                    'invoice_no_s_d'      => $sale->invoice_no_s_d,
                    'invoice_from'        => 'Sales',
                    'customer_id'         => $sale->party_id,
                    'compnay_id'          => $sale->compnay_id,
                    'budget'              => $sale->amount,
                    'vat'                 => $sale->vat,
                    'total_budget'        => $sale->total_amount,
                    'date'                => $sale->date,
                    'due_amount'          => $sale->due_amount,
                    'paid_amount'         => $sale->paid_amount,
                    'advance_paid_amount' => $sale->advance_paid_amount,
                    'narration'           => $sale->narration,
                    'invoice_type'        => 'Tax Invoice',
                    'approved_by'         => Auth::id(),
                    'do_no'               => $sale->do_no,
                    'lpo_no'              => $sale->lpo_no,
                    'quotation_no'        => $sale->quotation_no,
                    'voucher_scan'        => $sale->voucher_scan,
                    'voucher_scan2'       => $sale->voucher_scan2,
                    'attention'           => $sale->attention,
                    'pay_mode'            => $sale->pay_mode,
                    'job_project_id'      => $sale->site_project,
                    'retention_invoice'   => $sale->retention_invoice,
                    'retention_amount'    => (-1) * $sale->amount,
                ]);

                // 2️⃣ Prepare Invoice Tasks for bulk insert
                $tasksData = [];
                foreach ($sale->items as $item) {
                    $amount = $item->amount;
                    $total  = $item->total_amount;
                    $tasksData[] = [
                        'task_name'        => $item->item_description,
                        'task_id'          => $item->task_id,
                        'qty'              => $item->qty,
                        'unit'             => $item->unit_id,
                        'rate'             => $item->rate,
                        'budget'           => $amount,
                        'total_budget'     => $total,
                        'vat_id'           => $amount < $total ? 1 : 3,
                        'item_description' => $item->item_description,
                        'invoice_id'       => $invoice->id,
                        'paid_amount'      => $sale->paid_amount > 0 ? $amount : 0,
                        'due_amount'       => $sale->paid_amount > 0 ? 0 : $amount,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ];
                }
                JobProjectInvoiceTask::insert($tasksData);

                // 3️⃣ Update JobProject retention and tasks
                $jProject = JobProject::find($invoice->job_project_id);
                if ($jProject) {
                    $jProject->decrement('retention_amount', $invoice->budget);
                }

                $taskIds = $sale->items->pluck('task_id');
                $tasks = JobProjectTask::whereIn('id', $taskIds)->with('jobProject')->get()->keyBy('id');

                foreach ($sale->items as $item) {
                    $task = $tasks[$item->task_id] ?? null;
                    if ($task) {
                        $jobProject = $task->jobProject;
                        if ($jobProject) {
                            $jobProject->increment('paid_amount', $item->amount);
                            $jobProject->decrement('due_amount', $item->amount);
                        }
                        $task->increment('revenue', $item->amount);
                        $task->increment('receipt', $sale->paid_amount > 0 ? $item->amount : 0);
                        $task->increment('receivable', $sale->paid_amount > 0 ? 0 : $item->amount);
                    }
                }

                // 4️⃣ Journal & JournalRecords
                $journalNo = $this->journal_no();
                $journal = Journal::create([
                    'project_id'        => 1,
                    'compnay_id'        => $sale->compnay_id,
                    'invoice_id'        => $invoice->id,
                    'transection_type'  => 'Sale',
                    'transaction_type'  => 'Increase',
                    'journal_no'        => $journalNo,
                    'date'              => $invoice->date,
                    'pay_mode'          => 'CREDIT',
                    'cost_center_id'    => 0,
                    'party_info_id'     => $invoice->customer_id,
                    'account_head_id'   => 123,
                    'voucher_type'      => 'CREDIT',
                    'amount'            => $invoice->total_budget,
                    'tax_rate'          => 0,
                    'vat_amount'        => $invoice->total_budget - $invoice->budget,
                    'total_amount'      => $invoice->budget,
                    'gst_subtotal'      => 0,
                    'narration'         => $invoice->narration,
                    'approved_by'       => $invoice->approved_by,
                ]);

                // 5️⃣ Pre-fetch account heads to avoid repeated find()
                $accountHeads = AccountHead::whereIn('id', [3, 17, 1759])->get()->keyBy('id');

                $journalRecords = [];

                // Accounts Receivable
                $acHead = $accountHeads[3];
                $journalRecords[] = [
                    'journal_id'        => $journal->id,
                    'compnay_id'        => $sale->compnay_id,
                    'project_details_id' => $journal->project_id,
                    'cost_center_id'    => $journal->cost_center_id,
                    'party_info_id'     => $journal->party_info_id,
                    'journal_no'        => $journal->journal_no,
                    'account_head_id'   => $acHead->id,
                    'master_account_id' => $acHead->master_account_id,
                    'account_head'      => $acHead->fld_ac_head,
                    'amount'            => $invoice->due_amount,
                    'total_amount'      => $invoice->due_amount,
                    'vat_rate_id'       => 0,
                    'transaction_type'  => 'DR',
                    'journal_date'      => $journal->date,
                    'invoice_no'        => 'N/A',
                    'account_type_id'   => $acHead->account_type_id,
                    'is_main_head'      => 0,
                    'job_project_id'    => $invoice->job_project_id,
                ];

                // Retention
                $retAcHead = $accountHeads[1759];
                $journalRecords[] = [
                    'journal_id'        => $journal->id,
                    'compnay_id'        => $sale->compnay_id,
                    'project_details_id' => $journal->project_id,
                    'cost_center_id'    => $journal->cost_center_id,
                    'party_info_id'     => $journal->party_info_id,
                    'journal_no'        => $journal->journal_no,
                    'account_head_id'   => $retAcHead->id,
                    'master_account_id' => $retAcHead->master_account_id,
                    'account_head'      => $retAcHead->fld_ac_head,
                    'amount'            => $invoice->budget,
                    'total_amount'      => $invoice->budget,
                    'vat_rate_id'       => 0,
                    'transaction_type'  => 'CR',
                    'journal_date'      => $journal->date,
                    'invoice_no'        => 'N/A',
                    'account_type_id'   => $retAcHead->account_type_id,
                    'is_main_head'      => 0,
                    'job_project_id'    => $invoice->job_project_id,
                ];

                // VAT
                if ($journal->vat_amount > 0) {
                    $vatAcHead = $accountHeads[17];
                    $journalRecords[] = [
                        'journal_id'        => $journal->id,
                        'compnay_id'        => $sale->compnay_id,
                        'project_details_id' => $journal->project_id,
                        'cost_center_id'    => $journal->cost_center_id,
                        'party_info_id'     => $journal->party_info_id,
                        'journal_no'        => $journal->journal_no,
                        'account_head_id'   => $vatAcHead->id,
                        'master_account_id' => $vatAcHead->master_account_id,
                        'account_head'      => $vatAcHead->fld_ac_head,
                        'amount'            => $journal->vat_amount,
                        'total_amount'      => $journal->vat_amount,
                        'vat_rate_id'       => 0,
                        'transaction_type'  => 'CR',
                        'journal_date'      => $journal->date,
                        'invoice_no'        => 'N/A',
                        'account_type_id'   => $vatAcHead->account_type_id,
                        'is_main_head'      => 0,
                        'job_project_id'    => $invoice->job_project_id,
                    ];
                }

                JournalRecord::insert($journalRecords);

                // 6️⃣ Cleanup Sale
                $sale->items()->delete();
                $sale->delete();

                return $invoice->id;
            });
        }

        public function sale_approval(Request $request, $id)
        {
            Gate::authorize('Revenue_Approve');

            $sale = Sale::find($id);

            if ($sale->retention_invoice) {
                $invoice_id =  $this->retentionInvoice($sale);
            } else {
                $invoice_id = $this->normalInvoice($sale);
            }
        $invoice = JobProjectInvoice::find($invoice_id);

            if ($request->ajax()) {
                return response()->json([
                    'id' => $invoice->id,
                    'party_id' => $invoice->customer_id,
                    'advance' => $invoice->party->balance,
                    'due_amount' => $invoice->due_amount,
                ]);
            }

            $notification = array(
                'message'       => 'Invoice Approved Successfully!',
                'alert-type'    => 'success'
            );
            return redirect()->back()->with($notification);
        }

        public function invoice_delete($id)
        {
            // Fetch the invoice
            $invoice = JobProjectInvoice::find($id);
            if (!$invoice) {
                return ['error' => 'Invoice not found.'];
            }

            // Delete related journal records
            $journal = Journal::where('invoice_id', $invoice->id)->first();
            if ($journal) {
                JournalRecord::where('journal_id', $journal->id)->forceDelete();
                $journal->forceDelete();
            }

            // Delete related payment records
            $receipt_sales = ReceiptSale::where('sale_id', $invoice->id)->get();
            foreach ($receipt_sales as $rec_sale) {
                $receipt = Receipt::where('id', $rec_sale->payment_id)->first();
                if ($receipt) {
                    $journal = Journal::where('receipt_id', $receipt->id)->first();
                    if ($journal) {
                        JournalRecord::where('journal_id', $journal->id)->forceDelete();
                        $journal->forceDelete();
                    }
                    $receipt_items = ReceiptSale::where('payment_id', $receipt->id)->get();
                    foreach ($receipt_items as $r_item) {
                        $rec_invoice = JobProjectInvoice::find($r_item->sale_id);
                        if ($rec_invoice) {
                            $rec_invoice->due_amount = $rec_invoice->due_amount + $r_item->amount;
                            $rec_invoice->paid_amount = $rec_invoice->paid_amount - $r_item->amount;
                            $rec_invoice->save();
                        }
                        $r_item->forceDelete();
                    }
                    $receipt->forceDelete();
                }
            }

            $pending_receipt = TempReceiptVoucherDetail::where('sale_id', $invoice->id)->get();
            foreach ($pending_receipt as $p_sale) {
                $p_item = TempReceiptVoucher::where('id', $p_sale->payment_id)->first();

                if ($p_item) {
                    $rec_invoice = JobProjectInvoice::find($p_item->sale_id);
                    if ($rec_invoice) {
                        $rec_invoice->due_amount = $rec_invoice->due_amount + $p_item->amount;
                        $rec_invoice->paid_amount = $rec_invoice->paid_amount - $p_item->amount;
                        $rec_invoice->save();
                    }
                    $p_item->forceDelete();
                }
                $p_sale->forceDelete();
            }

            // Delete sale items
            JobProjectInvoiceTask::where('invoice_id', $invoice->id)->forceDelete();

            $jProject = JobProject::find($invoice->job_project_id);
            if ($jProject) {
                if ($invoice->retention_invoice) {
                    $jProject->retention_amount = $jProject->retention_amount + $invoice->budget;
                } else {
                    $jProject->retention_amount = $jProject->retention_amount - $invoice->retention_amount;
                }
                $jProject->save();
            }
            // Delete the invoice
            $invoice->delete();

            $notification = array(
                'message'       => 'Deleted Succesfully',
                'alert-type'    => 'success'
            );
            return redirect()->back()->with($notification);
        }

        public function receipt_delete($id)
        {
            $receipt = Receipt::where('id', $id)->first();
            if ($receipt) {
                $journal = Journal::where('receipt_id', $receipt->id)->first();
                if ($journal) {
                    JournalRecord::where('journal_id', $journal->id)->forceDelete();
                    $journal->forceDelete();
                }
                $receipt_items = ReceiptSale::where('payment_id', $receipt->id)->get();
                foreach ($receipt_items as $r_item) {
                    $rec_invoice = JobProjectInvoice::find($r_item->sale_id);
                    if ($rec_invoice) {
                        $rec_invoice->due_amount = $rec_invoice->due_amount + $r_item->amount;
                        $rec_invoice->paid_amount = $rec_invoice->paid_amount - $r_item->amount;
                        $rec_invoice->save();
                    }
                    $r_item->forceDelete();
                }
                $receipt->forceDelete();
            }

            $notification = array(
                'message'       => 'Deleted Succesfully',
                'alert-type'    => 'success'
            );
            return redirect()->back()->with($notification);
        }

        public function sale_delete($id)
        {
            Gate::authorize('Revenue_Delete');
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
            $paid_amount = 0;
            $due_amount = $request->total_amount - $request->retention_transferred ?? 0;;
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
                    // $sale_no = $this->p_sale_no();
                    $sale->proforma_invoice_no =  $sale->proforma_invoice_no;
                    // $sales_invoice = InvoiceNumber::first();
                    // $sales_invoice->proforma_invoice_no = $sale_no;
                    // $sales_invoice->save();
                }
            }
            $update_date_format = $this->dateFormat($request->date);
            $sale->date = $update_date_format;
            $sale->pay_mode =  $request->pay_mode;
            $sale->project_id = $request->project;
            $sale->invoice_type = $request->invoice_type;

            $sale->head_id = 0;
            $sale->total_amount = $request->total_amount;
            $sale->retention_amount = $request->retention_transferred;
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
                $sale_item->qty = 1;
                $sale_item->unit_id = '-';
                $sale_item->rate = $each_head['amount'];
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
            Gate::authorize('Revenue_Analysis');
            $party_id = $request->party_search;

            $suppliers = PartyInfo::where('pi_type', 'Customer')->get();
            $invoices = JobProjectInvoice::orderBy('id', 'desc')->when($party_id, function ($q) use ($party_id) {
                $q->where('customer_id', $party_id);
            })->paginate(80);

            $data = [
                'project' => [],
                'unlinked' => []
            ];

            $invoices->each(function ($invoice) use (&$data) {
                if ($invoice->job_project_id) {
                    $projectId = $invoice->job_project_id;
                    $project_name = null;

                    $project = JobProject::find($projectId);
                    if ($project) {
                        $new_project = $project->new_project;

                        $project_name = $new_project->name ?? $project->project_name;
                        $advance_amount =  $project->advance_amount ?? 0;
                        $contract_amount = $new_project->total_amount ?? $project->total_budget;
                        $retention_amount = $project->retention_amount ?? 00;
                    }

                    if (!isset($data['project'][$projectId])) {
                        $data['project'][$projectId] = [
                            'total_invoice' => 0,
                            'total_amount' => 0,
                            'paid_amount' => 0,
                            'due_amount' => 0,
                            'advance_amount' => $advance_amount ?? 0,
                            'project_name' => $project_name,
                            'party_name' => optional($project->party)->pi_name,
                            'contract_amount' => $contract_amount,
                            'retention_amount' =>  $retention_amount,
                            'plot_no' => $new_project ? $new_project->plot : ' ',
                            'location' => $new_project ? $new_project->location : ' ',
                        ];
                    }
                    $paid_amount = $invoice->receipt_lists->sum('pivot.amount');
                    $data['project'][$projectId]['total_invoice'] += 1;
                    $data['project'][$projectId]['total_amount'] += $invoice->total_budget;
                    $data['project'][$projectId]['paid_amount'] += $invoice->paid_amount;
                    $data['project'][$projectId]['due_amount'] += $invoice->total_budget - $paid_amount;
                } else {
                    // Collect each unlinked invoice separately
                    $data['unlinked'][$invoice->id] = [
                        'total_invoice' => 1,
                        'total_amount' => $invoice->total_budget,
                        'paid_amount' => $invoice->paid_amount,
                        'due_amount' => $invoice->due_amount,
                        'project_name' => '---',
                        'party_name' => optional($invoice->party)->pi_name,
                    ];
                }
            });
            $modes = PayMode::whereNotIn('id', [2, 3, 6])->get();
            $bank_name = AccountSubHead::where('account_head_id', 2)->get();

            return view('backend.sale.receivable', compact('data', 'invoices', 'suppliers', 'modes', 'bank_name'));
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

            $invoices = JobProjectInvoice::where('customer_id', $id)->get();
            // dd($invoices);
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
                $sales->where('invoice_no', 'like', '%' . $request->value . '%');
                $pending_sales->where('invoice_no', 'like', '%' . $request->value . '%');
            }

            if ($request->party) {
                $sales->where('customer_id', $request->party);
                $pending_sales->where('party_id', $request->party);
            }

            if ($request->date) {
                $date = $this->dateFormat($request->date);
                $sales->where('date', $date);
                $pending_sales->where('date', $date);
            }

            // 🔍 Filter by company ID
            if ($request->has('compnay_id')) {
                if ($request->compnay_id == 0) {
                    $sales->whereNull('compnay_id');
                    $pending_sales->whereNull('compnay_id');
                } else {
                    $sales->where('compnay_id', $request->compnay_id);
                    $pending_sales->where('compnay_id', $request->compnay_id);
                }
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

        public function retentionForm(Request $request)
        {
            $id = $request->id;

            $project = JobProject::find($id);
            $new_project = $project ? $project->new_project : null;

            if ($project) {
                return view('backend.sale.retention-form', compact('project', 'new_project'));
            }
        }

        public function payment_change(Request $request, $id)
        {
            $receipt = Receipt::find($id);
            $pre_mode = $receipt->pay_mode == 'Cash' ? 1 : 2;
            $receipt->pay_mode =  $request->payment_mode;
            $receipt->save();

            $journal = Journal::where('receipt_id', $receipt->id)->first();
            if ($journal) {
                $journal->pay_mode = $receipt->pay_mode;
                $journal->save();
                $dd = $receipt->pay_mode == 'Cash' ? 1 : 2;
                $pay_head = AccountHead::find($dd);
                $pay_record = JournalRecord::where('journal_id', $journal->id)->where('account_head_id', $pre_mode)->first();
                $pay_record->account_head_id = $dd;
                $pay_record->account_head_id     = $pay_head->id;
                $pay_record->master_account_id   = $pay_head->master_account_id;
                $pay_record->account_head        = $pay_head->fld_ac_head;
                $pay_record->account_type_id     = $pay_head->account_type_id;
                $pay_record->save();
            }

            $notification = array(
                'message'       => 'Payment Mode Updated',
                'alert-type'    => 'success'
            );
            return redirect()->back()->with($notification);
        }





        public function invoice_excel_import(Request $request)
        {
            $request->validate([
                'excel_file' => 'required|mimes:xlsx,xls'
            ]);

            $import = new SalesImport;
            Excel::import($import, $request->file('excel_file'));

            $message = '✅ <strong>The Invoices has been imported successfully.</strong>';
            $skippedMessages = $import->getSkippedRows();
            if (!empty($skippedMessages)) {
                $formattedMessages = "<div style='text-align: left; margin-top: 10px;'>";
                $formattedMessages .= "<p>⚠️ <strong>However, some rows were skipped:</strong></p>";
                $formattedMessages .= "<ul style='padding-left: 20px;'>";
                foreach ($skippedMessages as $msg) {
                    $formattedMessages .= "<li>🔸 " . e($msg) . "</li>";
                }
                $formattedMessages .= "</ul></div>";

                return back()->with([
                    'alert-type' => 'success',
                    'message_import' => $message . $formattedMessages
                ]);
            }

            return back()->with([
                'alert-type' => 'success',
                'message_import' => $message
            ]);
        }

        public function invoice_excel_export()
        {
            $invoicess = JobProjectInvoice::get();
            return view('test.neweReport', compact('invoicess'));
        }


        public function receipt_excel_export()
        {
            $receipts = Receipt::get();
            return view('test.receipts', compact('receipts'));
        }


        public function receipt_excel_import(Request $request)
        {
            $request->validate([
                'excel_file' => 'required|mimes:xlsx,xls'
            ]);

            $import = new ReceiptImport();
            Excel::import($import, $request->file('excel_file'));

            $message = '✅ <strong>The Receipt has been imported successfully.</strong>';
            $skippedMessages = $import->getSkippedRows();

            if (!empty($skippedMessages)) {
                $formattedMessages = "<div style='text-align: left; margin-top: 10px;'>";
                $formattedMessages .= "<p>⚠️ <strong>However, some rows were skipped:</strong></p>";
                $formattedMessages .= "<ul style='padding-left: 20px;'>";
                foreach ($skippedMessages as $msg) {
                    $formattedMessages .= "<li>🔸 " . e($msg) . "</li>";
                }
                $formattedMessages .= "</ul></div>";

                return back()->with([
                    'alert-type' => 'success',
                    'message_import' => $message . $formattedMessages
                ]);
            }

            return back()->with([
                'alert-type' => 'success',
                'message_import' => $message
            ]);
        }

        public function recieved_data(Request $request)
        {
            $date_from = $request->date_from ? $this->dateFormat($request->date_from) : null;
            $date_to   = $request->date_to ? $this->dateFormat($request->date_to) : null;
            $month     = $request->month; // YYYY-MM from <input type="month">
            $year      = $request->year;  // from <input type="number">

            $receipt_list = Receipt::query()
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

                ->with('job_project')
                ->orderBy('date', 'asc')
                ->paginate(20)
                ->appends(request()->except('page')); // keep filters on pagination

            return view('backend.sale.received-data', compact('receipt_list', 'date_from', 'date_to', 'month', 'year'));
        }


        // home receivable
        public function home_receivable(Request $request)
        {
            $date_from = $request->date_from ? $this->dateFormat($request->date_from) : null;
            $date_to   = $request->date_to ? $this->dateFormat($request->date_to) : null;
            $month     = $request->month ?? null; // expecting YYYY-MM format (from <input type="month">)
            $year      = $request->year ?? null;  // optional if you want separate year filter

            $receivables = JobProjectInvoice::select(
                    'customer_id',
                    DB::raw('SUM(due_amount) as total_due'),
                    DB::raw('SUM(budget) as total_budget'),
                    DB::raw('SUM(vat) as total_vat'),
                    DB::raw('SUM(paid_amount) as total_paid'),
                    DB::raw('SUM(total_budget - retention_amount) as total_total_budget'),
                    DB::raw('MAX(job_project_id) as job_project_id')
                )
                    ->with(['party', 'project.quotation.boq.project'])
                    ->where('due_amount', '>', 0)

                    // Date Range filter
                    ->when($date_from && $date_to, function ($query) use ($date_from, $date_to) {
                        $query->whereBetween('date', [$date_from, $date_to]);
                    })

                    // Month filter
                    ->when($month, function ($query) use ($month) {
                        $query->whereMonth('date', '=', date('m', strtotime($month)))
                            ->whereYear('date', '=', date('Y', strtotime($month)));
                    })

                    // Year filter (if separate dropdown for year)
                    ->when($year, function ($query) use ($year) {
                        $query->whereYear('date', '=', $year);
                    })

                    ->groupBy('customer_id')
                    ->orderByDesc('total_due')
                    ->paginate(20)->appends(request()->except('page'));

                    $grandTotals = JobProjectInvoice::select(
                        DB::raw('SUM(job_project_invoices.due_amount) as grand_total_due'),
                        DB::raw('SUM(job_project_invoices.budget) as grand_total_budget'),
                        DB::raw('SUM(job_project_invoices.vat) as grand_total_vat'),
                        DB::raw('SUM(job_project_invoices.paid_amount) as grand_total_paid'),
                        DB::raw('SUM(job_project_invoices.total_budget - job_project_invoices.retention_amount) as grand_total_total_budget'),
                        DB::raw('SUM(new_projects.total_contract - job_project_invoices.vat - job_project_invoices.budget) as grand_total_acc_rec')
                    )
                        ->join('job_projects', 'job_projects.id', '=', 'job_project_invoices.job_project_id')
                        ->join('lpo_projects', 'lpo_projects.id', '=', 'job_projects.lpo_projects_id')
                        ->join('bill_of_quantities', 'bill_of_quantities.project_id', '=', 'lpo_projects.id')
                        ->join('new_projects', 'new_projects.id', '=', 'bill_of_quantities.project_id')
                        ->where('job_project_invoices.due_amount', '>', 0)

                        // Apply same filters here
                        ->when($date_from && $date_to, function ($query) use ($date_from, $date_to) {
                            $query->whereBetween('job_project_invoices.created_at', [$date_from, $date_to]);
                        })
                        ->when($month, function ($query) use ($month) {
                            $query->whereMonth('job_project_invoices.date', '=', date('m', strtotime($month)))
                                ->whereYear('job_project_invoices.date', '=', date('Y', strtotime($month)));
                        })
                        ->when($year, function ($query) use ($year) {
                            $query->whereYear('job_project_invoices.date', '=', $year);
                        })

                        ->first();

            //joman
            return view('backend.sale.receivable-data', compact('receivables', 'grandTotals', 'date_from','date_to', 'month', 'year'));
        }

        // InvoiceController.php
        public function getInvoices(Request $request)
        {
            $customer_id = $request->customer_id;

            $invoices = JobProjectInvoice::with('project')
                ->where('customer_id', $customer_id)->where('due_amount', '>', 0)
                ->get();

            return response()->json($invoices);
        }

        public function project_limit(Request $request)
        {
            $project = JobProject::find($request->project);
            $contract = $project->budget;
            $retention = $project->invoices->sum('retention_amount');
            $inv_amount = $project->invoices->sum('budget');
            $remaining = $contract - $retention - $inv_amount;
            $remaining_vat = $remaining * (5 / 100);
            $total_remaining = $remaining_vat + $remaining;

            return [
                'contract'        => round($contract, 2),
                'retention'       => round($retention, 2),
                'inv_amount'      => round($inv_amount, 2),
                'remaining'       => round($remaining, 2),
                'remaining_vat'   => round($remaining_vat, 2),
                'total_remaining' => round($total_remaining, 2),
            ];
        }
    }

<?php

namespace App\Http\Controllers\backend;

use App\BillOfQuantityTask;
use App\Http\Controllers\Controller;
use App\JobProject;
use App\JobProjectInvoice;
use App\JobProjectTask;
use App\Journal;
use App\JournalRecord;
use App\Models\AccountHead;
use App\Models\InvoiceNumber;
use App\PartyInfo;
use App\PayMode;
use App\Receipt;
use App\ReceiptSale;
use App\TempReceiptVoucher;
use App\TempReceiptVoucherDetail;
use App\AccountSubHead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;


class TempReceiptVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function dateFormat($date)
    {
        $old_date = explode('/', $date);

        $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }
    private function receive()
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
            $receipt_no = $sub_invoice . '250001';
        }
        return $receipt_no;
    }
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sale = JobProjectInvoice::where('id', $request->invoice_no)->first();
// return $sale;
        $update_date_format = $this->dateFormat($request->date);
        $job_project = JobProject::find($request->project_id);
        $prefix = 'RV' . Carbon::now()->format('y');

        // Step 2: Determine company-wise prefix or fallback
        $company_id = $sale->compnay_id ?? null;
        $company_key = $company_id ?? 'other'; // use 'other' if company_id is null

        // Step 3: Fetch last used number for this company
        $lastInvoice = TempReceiptVoucher::where('receipt_no', 'LIKE', "{$prefix}%" )
            ->where('company_id', $company_id)
            ->orderByDesc('id')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) str_replace($prefix, '', $lastInvoice->receipt_no);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        // Step 5: Pad the number
        $receipt_no = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $voucher_file_name = '';
        $ext = '';
        if ($request->hasFile('voucher_file')) {
            $voucher_scan = $request->file('voucher_file');
            $name = $voucher_scan->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $voucher_scan->getClientOriginalExtension();
            $voucher_file_name = $name . time() . '.' . $ext;
            $voucher_scan->storeAs('public/upload/documents', $voucher_file_name);
        }
        if ($request->pay_mode == "Cheque") {
            $deposit_date = $this->dateFormat($request->deposit_date);
            $payment = new TempReceiptVoucher();
            $payment->job_project_id = $request->project_id;
            $payment->company_id = $sale ? $sale->compnay_id : '';
            $payment->date = $update_date_format;
            $payment->pay_mode =  $request->pay_mode;
            $payment->bank_id =  $request->bank_id;
            $payment->receipt_no = $receipt_no;
            $payment->head_id = 0;
            $payment->total_amount = $request->pay_amount;
            $payment->vat = 0;
            $payment->party_id =  $request->party_info ?? $sale->customer_id;
            $payment->narration = $request->narration;
            $payment->issuing_bank = $request->issuing_bank;
            $payment->branch = $request->bank_branch;
            $payment->cheque_no = $request->cheque_no;
            $payment->deposit_date = $deposit_date;
            $payment->status = 'Pending';
            $payment->paid_amount = $request->pay_amount;
            $payment->due_amount = $request->due_amount - $request->pay_amount;
            $payment->is_direct = 0;
            $payment->save();
        } else {
            $total_vat = 0;
            $total_amount_withvat = 0;
            $total_amount = 0;
            $cost_center_id = 0;
            if ($request->cost_center_name != null) {
                $cost_center_id = $request->cost_center_name;
            }
            $payment = new TempReceiptVoucher();
            $payment->date = $update_date_format;
            $payment->company_id = $sale ? $sale->compnay_id : '';
            $payment->pay_mode =  $request->pay_mode;
            $payment->bank_id =  $request->bank_id;
            $payment->receipt_no = $receipt_no;
            $payment->head_id = 0;
            $payment->total_amount = $request->pay_amount;
            $payment->vat = 0;
            $payment->party_id =  $request->party_info ?? $sale->customer_id;
            $payment->narration = $request->narration;
            $payment->status = 'Realised';
            $payment->paid_amount = $request->pay_amount;
            $payment->due_amount = $request->due_amount - $request->pay_amount;
            $payment->is_direct = 0;
            $payment->save();
        }
        // $let_purch_exp = InvoiceNumber::first();
        // if (!$let_purch_exp) {
        //     $let_purch_exp = new InvoiceNumber();
        // }

        // $let_purch_exp->receipt_invoice_number = $receipt_no;
        // $let_purch_exp->save();

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
                $purc_exp_itm = new TempReceiptVoucherDetail();
                $purc_exp_itm->sale_id = $sale->id;
                $purc_exp_itm->company_id = $sale ? $sale->compnay_id : '';
                $purc_exp_itm->payment_id = $payment->id;
                $purc_exp_itm->Total_amount = $amount;
                $purc_exp_itm->vat = 0;
                $purc_exp_itm->amount = $amount;
                $purc_exp_itm->party_id = $request->party_info ?? $sale->customer_id;
                $purc_exp_itm->save();
                $sale = JobProjectInvoice::where('due_amount', '>', 0)->where('customer_id', $request->party_info)->orderBy('date', 'asc')->first();
                if (!$sale) {
                    $advance = $pay_amount;
                    $pay_amount = 0;
                }
            }
        }

        $recept = $payment;
        return view('backend.receipt-voucher.receipt-preview', compact('recept'));
    }


    public function store_invoice_receipt(Request $request)
    {
        Gate::authorize('Revenue_Create');
        if ($request->pay_mode == 'Advance') {
            $party = PartyInfo::find($request->party_info);
            if ($party->balance < $request->pay_amount) {
                return 1;
            }
        }

        // dd($request->all());
        $update_date_format = $this->dateFormat($request->date);
        $job_project = JobProject::find($request->project_id);
        $sale = JobProjectInvoice::where('due_amount', '>', 0)->where('date', '<=', $update_date_format)->where('customer_id',  $request->party_info)->orderBy('date', 'asc')->first();

         $prefix = 'RV' . Carbon::now()->format('y');

        // Step 2: Determine company-wise prefix or fallback
        $company_id = $sale->compnay_id ?? null;
        $company_key = $company_id ?? 'other'; // use 'other' if company_id is null

        // Step 3: Fetch last used number for this company
        $lastInvoice = TempReceiptVoucher::where('receipt_no', 'LIKE', "{$prefix}%" )
            ->where('company_id', $company_id)
            ->orderByDesc('id')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) str_replace($prefix, '', $lastInvoice->receipt_no);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        // Step 5: Pad the number
        $receipt_no = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $voucher_file_name = '';
        $ext = '';
        if ($request->hasFile('voucher_file')) {
            $voucher_scan = $request->file('voucher_file');
            $name = $voucher_scan->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $voucher_scan->getClientOriginalExtension();
            $voucher_file_name = $name . time() . '.' . $ext;
            $voucher_scan->storeAs('public/upload/documents', $voucher_file_name);
        }
        // dd($request->records);
        // dd($this->receive());
        if ($request->voucher_type == 'due') {

            $payment = new TempReceiptVoucher();
            $payment->company_id = $sale ? $sale->compnay_id : '';
            $payment->date = $update_date_format;
            $payment->pay_mode =  $request->pay_mode;
            $payment->bank_id =  $request->bank_id;
            $payment->receipt_no = $receipt_no;
            $payment->head_id = 0;
            $payment->total_amount = $request->pay_amount;
            $payment->vat = 0;
            $payment->party_id = $request->party_info;
            $payment->narration = $request->narration;
            $payment->job_project_id = $request->project;
            if ($request->pay_mode == 'Cheque') {
                $deposit_date = $this->dateFormat($request->deposit_date);
                $payment->issuing_bank = $request->issuing_bank;
                $payment->branch = $request->bank_branch;
                $payment->cheque_no = $request->cheque_no;
                $payment->deposit_date = $deposit_date;
                $payment->status = 'Pending';
            } else {
                $payment->status = 'Realised';
            }
            $payment->paid_amount = $request->pay_amount;
            $payment->due_amount = $request->due_amount - $request->pay_amount;
            $payment->is_direct = 0;
            $payment->voucher_file = $voucher_file_name;
            $payment->extension = $ext;
            $payment->type = 'due';
            $payment->save();

            // $invoice_number = InvoiceNumber::first();
            // $invoice_number->receipt_invoice_number = $payment->receipt_no;
            // $invoice_number->save();

            $receipt_lists = $request->records;
            $pay_amount = $request->pay_amount;
            $inv_discount = $request->input('inv_discount');
            if ($receipt_lists) {
                foreach ($receipt_lists as $key => $receipt_l) {
                    if ($pay_amount > 0 || $inv_discount[$key]) {
                        $sale = JobProjectInvoice::find($receipt_l);
                        if ($pay_amount + $inv_discount[$key] < $sale->due_amount) {
                            $paid_amount = $pay_amount + $inv_discount[$key];
                            $amount = $pay_amount + $inv_discount[$key];
                            $sale->due_amount = $sale->due_amount - ($pay_amount + $inv_discount[$key]);
                            $sale->paid_amount = $sale->paid_amount + $pay_amount + $inv_discount[$key];
                            $pay_amount = 0;
                        } else {
                            $paid_amount = $sale->due_amount;
                            $amount = $sale->due_amount;
                            $sale->paid_amount = $sale->paid_amount + $sale->due_amount;
                            $pay_amount = $pay_amount - $sale->due_amount + $inv_discount[$key];
                            $sale->due_amount = 0;
                        }
                        $sale->save();

                        $purc_exp_itm = new TempReceiptVoucherDetail();
                        $purc_exp_itm->sale_id = $sale->id;
                        $purc_exp_itm->company_id = $sale ? $sale->compnay_id : '';
                        $purc_exp_itm->payment_id = $payment->id;
                        $purc_exp_itm->Total_amount = $paid_amount;
                        $purc_exp_itm->vat = 0;
                        $purc_exp_itm->amount = $sale->paid_amount;
                        $purc_exp_itm->party_id = $paid_amount;
                        $purc_exp_itm->save();
                    }
                }
            }

            if ($sale) {
                while ($pay_amount > 0) {
                    if ($pay_amount < $sale->due_amount) {
                        $paid_amount = $pay_amount;
                        $amount = $pay_amount;
                        $sale->due_amount = $sale->due_amount - $pay_amount;
                        $sale->paid_amount = $sale->paid_amount + $pay_amount;
                        $pay_amount = 0;
                    } else {
                        $paid_amount = $sale->due_amount;
                        $amount = $sale->due_amount;
                        $sale->paid_amount = $sale->paid_amount + $sale->due_amount;
                        $pay_amount = $pay_amount - $sale->due_amount;
                        $sale->due_amount = 0;
                    }
                    $sale->save();

                    $purc_exp_itm = new TempReceiptVoucherDetail();
                    $purc_exp_itm->sale_id = $sale->id;
                    $purc_exp_itm->payment_id = $payment->id;
                    $purc_exp_itm->company_id = $sale ? $sale->compnay_id : '';
                    $purc_exp_itm->Total_amount =  $paid_amount;
                    $purc_exp_itm->vat = 0;
                    $purc_exp_itm->amount =  $paid_amount;
                    $purc_exp_itm->party_id = $request->party_info;
                    $purc_exp_itm->save();
                    $sale = JobProjectInvoice::where('due_amount', '>', 0)->where('date', '<=', $update_date_format)->where('customer_id',  $request->party_info)->orderBy('date', 'asc')->first();
                    if (!$sale) {
                        $advance = $pay_amount;
                        $pay_amount = 0;
                    }
                }
            }
        } else {
            $payment = new TempReceiptVoucher();
            $payment->job_project_id = $request->project;
            $payment->company_id = $sale ? $sale->compnay_id : '';
            $payment->date = $update_date_format;
            $payment->bank_id =  $request->bank_id;
            $payment->pay_mode =  $request->pay_mode;
            $payment->receipt_no = $receipt_no;;
            $payment->head_id = 0;
            $payment->total_amount = $request->pay_amount;
            $payment->vat = 0;
            $payment->party_id =  $request->party_info;
            $payment->narration = $request->narration;
            if ($request->pay_mode == 'Cheque') {
                $deposit_date = $this->dateFormat($request->deposit_date);
                $payment->issuing_bank = $request->issuing_bank;
                $payment->branch = $request->bank_branch;
                $payment->cheque_no = $request->cheque_no;
                $payment->deposit_date = $deposit_date;
                $payment->status = 'Pending';
            } else {
                $payment->status = 'Realised';
            }
            $payment->paid_amount = $request->pay_amount;
            $payment->due_amount = 0.00;
            $payment->is_direct = 0;
            $payment->voucher_file = $voucher_file_name;
            $payment->extension = $ext;
            $payment->type = 'advance';
            $payment->save();
            // $let_purch_exp = InvoiceNumber::first();
            // $let_purch_exp->receipt_invoice_number = $payment->receipt_no;
            // $let_purch_exp->save();
        }

        if ($payment->pay_mode == 'Advance') {
            $party = PartyInfo::find($payment->party_id);
            $party->balance = $party->balance - $request->pay_amount;
            $party->save();
        }

        $recept = $payment;
        $temp_receipt_list = TempReceiptVoucher::orderBy('id', 'desc')->paginate(40);

         $recept = TempReceiptVoucher::find($payment->id);
         $imageUrl = asset('img/laterhead.jpg');
        if ($recept->subsidiary && $recept->subsidiary->image) {
                $imageUrl = asset('storage/' . $recept->subsidiary->image);
        }


        return response()->json([
            'preview' => view('backend.receipt-voucher.receipt-preview', compact('recept','imageUrl'))->render(),
            'list' => view('backend.receipt-voucher._ajax_list', compact('temp_receipt_list'))->render(),
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function temp_receipt_voucher_authorize(Request $recept)
    {
        $receipt_list = TempReceiptVoucher::where('is_authorize', 0)->orderBy('id', 'desc')->paginate(40);
        $parties = PartyInfo::get();
        $modes = PayMode::all();
        // dd($receipt_list);
        return view('backend.receipt-voucher.authorize-receipt-list', compact('parties', 'modes', 'receipt_list'));
    }

    public function temp_receipt_voucher_approve(Request $recept)
    {
        $receipt_list = TempReceiptVoucher::orderBy('id', 'desc')->paginate(40);
        $parties = PartyInfo::get();
        $modes = PayMode::all();

        // dd($receipt_list);
        return view('backend.receipt-voucher.approve-receipt-list', compact('parties', 'modes', 'receipt_list'));
    }

    public function search_receipt(Request $request)
    {
        $receipt_list = TempReceiptVoucher::where('receipt_no', 'like', "%{$request->value}%")->get();
        if ($request->party != '') {
            $receipt_list = $receipt_list->where('party_id', $request->party);
        }
        if ($request->date != '') {
            $date = $this->dateFormat($request->date);
            $receipt_list = $receipt_list->where('date', $date);
        }
        if ($request->mode != '') {
            $receipt_list = $receipt_list->where('pay_mode', $request->mode);
        }
        return view('backend.receipt-voucher.search-receipt-voucher', compact('receipt_list'));
    }
    public function temp_receipt_voucher_preview(Request $request)
    {
        $recept = TempReceiptVoucher::find($request->id);
         $imageUrl = asset('img/laterhead.jpg');
        if ($recept->subsidiary && $recept->subsidiary->image) {
                $imageUrl = asset('storage/' . $recept->subsidiary->image);
        }

        return view('backend.receipt-voucher.receipt-preview', compact('recept','imageUrl'));
    }
    public function receipt_voucher_authorize($id)
    {
        $receipt = TempReceiptVoucher::find($id);
        $receipt->is_authorize = 1;
        $receipt->save();
        $notification = array(
            'message'       => 'Authorize successfull!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function receipt_voucher_approve($id)
    {
        $receipt = TempReceiptVoucher::find($id);
        $receipt_details = TempReceiptVoucherDetail::where('payment_id', $id)->get();

        $update_date_format = $receipt->date;
        $sub_invoice = Carbon::now()->format('Ymd');
        $job_project = JobProject::find($receipt->project_id);


        $cost_center_id = 0;
        if ($receipt->cost_center_name != null) {
            $cost_center_id = $receipt->cost_center_name;
        }
        $latest_journal_no = Journal::withTrashed()->whereDate('created_at', Carbon::today())->where('journal_no', 'LIKE', "%{$sub_invoice}%")->orderBy('id', 'desc')->first();
        if ($latest_journal_no) {
            $journal_no = substr($latest_journal_no->journal_no, 0, -1);
            $journal_code = $journal_no + 1;
            $journal_no = $journal_code . "J";
        } else {
            $journal_no = Carbon::now()->format('Ymd') . '001' . "J";
        }
        if ($receipt->type == 'advance') {
            // if pay_mode == "Cheque"
            // dd($receipt);
            if($receipt->job_project_id)
            {
                $job_project=JobProject::find($receipt->job_project_id);
                $job_project->advance_amount = $job_project->advance_amount + $receipt->total_amount;
                $job_project->save();
            }
           {
             $party = PartyInfo::find($receipt->party_id);
            $party->balance += $receipt->total_amount;
            $party->save();
           }

            if ($receipt->pay_mode == "Cheque") {
                $payment = new Receipt();
                $payment->date = $update_date_format;
                $payment->company_id = $receipt->company_id;
                $payment->pay_mode =  $receipt->pay_mode;
                $payment->bank_id =  $receipt->bank_id;
                $payment->receipt_no = $receipt->receipt_no;
                $payment->head_id = 0;
                $payment->total_amount = $receipt->total_amount;
                $payment->vat = 0;
                $payment->party_id =  $receipt->party_id;
                $payment->narration = $receipt->narration;
                $payment->paid_amount = $receipt->paid_amount;
                $payment->due_amount = $receipt->due_amount;
                $payment->issuing_bank = $receipt->issuing_bank;
                $payment->branch = $receipt->branch;
                $payment->cheque_no = $receipt->cheque_no;
                $payment->deposit_date = $receipt->deposit_date;
                $payment->name = $receipt->name;
                $payment->type = $receipt->type;
                $payment->job_project_id = $receipt->job_project_id;
                // $payment->voucher_file = $receipt->voucher_file;
                // $payment->extension= $receipt->extension;
                $payment->status = 'Pending';
                $payment->save();
            } else {
                $payment = new Receipt();
                $payment->date = $update_date_format;
                $payment->company_id = $receipt->company_id;
                $payment->pay_mode =  $receipt->pay_mode;
                $payment->bank_id =  $receipt->bank_id;
                $payment->receipt_no = $receipt->receipt_no;
                $payment->head_id = 0;
                $payment->total_amount = $receipt->total_amount;
                $payment->vat = 0;
                $payment->party_id =  $receipt->party_id;
                $payment->narration = $receipt->narration;
                $payment->status = 'Realised';
                $payment->paid_amount = $receipt->paid_amount;
                $payment->due_amount = $receipt->due_amount;
                $payment->name = $receipt->name;
                $payment->type = $receipt->type;
                // $payment->voucher_file = $receipt->voucher_file;
                // $payment->extension= $receipt->extension;
                $payment->job_project_id = $receipt->job_project_id;
                $payment->save();

                $journal = new Journal();
                $journal->project_id        = 1;
                $journal->compnay_id = $receipt->company_id;
                $journal->job_project_id = $receipt->job_project_id;
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

                $income_head = AccountHead::find(30); // advance account head
                $jl_record = new JournalRecord();
                $jl_record->journal_id          = $journal->id;
                $jl_record->job_project_id          = $journal->job_project_id;
                $jl_record->compnay_id = $receipt->company_id;
                $jl_record->project_details_id  = 1;
                $jl_record->cost_center_id      = $cost_center_id;
                $jl_record->party_info_id       = $payment->party_id;
                $jl_record->journal_no          = $journal_no;
                $jl_record->account_head_id     = $income_head->id;
                $jl_record->master_account_id   = $income_head->master_account_id;
                $jl_record->account_head        = $income_head->fld_ac_head;
                $jl_record->amount              = $receipt->total_amount;
                $jl_record->total_amount        = $receipt->total_amount;
                $jl_record->vat_rate_id         = 0;
                $jl_record->transaction_type    = 'CR';
                $jl_record->journal_date        = $update_date_format;
                $jl_record->account_type_id     = $income_head->account_type_id;
                $jl_record->is_main_head        = 0;
                $jl_record->save();

                $dd = $receipt->pay_mode == 'Cash' ? 1 : 2;
                $pay_head = AccountHead::find($dd);
                $jl_record = new JournalRecord();
                $jl_record->journal_id          = $journal->id;
                $jl_record->job_project_id          = $journal->job_project_id;
                $jl_record->compnay_id = $receipt->company_id;
                $jl_record->project_details_id  = 1;
                $jl_record->cost_center_id      = $cost_center_id;
                $jl_record->party_info_id       = $payment->party_id;
                $jl_record->journal_no          = $journal_no;
                $jl_record->account_head_id     = $pay_head->id;
                $jl_record->master_account_id   = $pay_head->master_account_id;
                $jl_record->account_head        = $pay_head->fld_ac_head;
                $jl_record->amount              = $receipt->total_amount;
                $jl_record->total_amount        = $receipt->total_amount;
                $jl_record->vat_rate_id         = 0;
                $jl_record->transaction_type    = 'DR';
                $jl_record->journal_date        = $update_date_format;
                $jl_record->account_type_id = $pay_head->account_type_id;
                $jl_record->is_main_head        = 0;
                $jl_record->sub_account_head_id = $payment->bank_id;
                $jl_record->save();
            }
        } else {
            if ($receipt->pay_mode == "Cheque") {
                $payment = new Receipt();
                $payment->date = $update_date_format;

                $payment->company_id = $receipt->company_id;
                $payment->pay_mode =  $receipt->pay_mode;
                $payment->bank_id =  $receipt->bank_id;
                $payment->receipt_no = $receipt->receipt_no;
                $payment->head_id = 0;
                $payment->total_amount = $receipt->total_amount;
                $payment->vat = 0;
                $payment->party_id =  $receipt->party_id;
                $payment->narration = $receipt->narration;
                $payment->paid_amount = $receipt->paid_amount;
                $payment->due_amount = $receipt->due_amount;
                $payment->issuing_bank = $receipt->issuing_bank;
                $payment->branch = $receipt->branch;
                $payment->cheque_no = $receipt->cheque_no;
                $payment->deposit_date = $receipt->deposit_date;
                $payment->name = $receipt->name;
                $payment->voucher_file = $receipt->voucher_file;
                $payment->extension = $receipt->extension;
                $payment->type = $receipt->type;
                $payment->job_project_id = $receipt->job_project_id;
                $payment->status = 'Pending';
                $payment->save();
                foreach ($receipt_details as $key => $details) {
                    $purc_exp_itm = new ReceiptSale();
                    $purc_exp_itm->sale_id = $details->sale_id;
                    $purc_exp_itm->company_id = $receipt->company_id;
                    $purc_exp_itm->payment_id = $payment->id;
                    $purc_exp_itm->Total_amount = $details->Total_amount;
                    $purc_exp_itm->vat = 0;
                    $purc_exp_itm->amount = $details->amount;
                    $purc_exp_itm->party_id = $details->party_id;
                    $purc_exp_itm->save();
                    $details->delete();
                }
            } else {
                $payment = new Receipt();
                $payment->date = $update_date_format;
                $payment->company_id = $receipt->company_id;
                $payment->pay_mode =  $receipt->pay_mode;
                $payment->bank_id =  $receipt->bank_id;
                $payment->receipt_no = $receipt->receipt_no;
                $payment->head_id = 0;
                $payment->total_amount = $receipt->total_amount;
                $payment->vat = 0;
                $payment->party_id =  $receipt->party_id;
                $payment->narration = $receipt->narration;
                $payment->status = 'Realised';
                $payment->paid_amount = $receipt->paid_amount;
                $payment->due_amount = $receipt->due_amount;
                $payment->name = $receipt->name;
                $payment->voucher_file = $receipt->voucher_file;
                $payment->extension = $receipt->extension;
                $payment->type = $receipt->type;
                $payment->job_project_id = $receipt->job_project_id;
                $payment->save();
                 $journal = new Journal();
                $journal->project_id        = 1;
                $journal->compnay_id = $receipt->company_id;
                $journal->job_project_id = $receipt->job_project_id;
                $journal->transection_type  = 'RECEIPT VOUCHER';
                $journal->transaction_type  = 'DEBIT';
                $journal->journal_no        = $journal_no;
                $journal->date              = $payment->date;
                $journal->voucher_type      = 'Receipt Voucher';
                $journal->receipt_id        = $payment->id;
                $journal->pay_mode          = $payment->pay_mode;
                $journal->invoice_no        = 0;
                $journal->cost_center_id    = $cost_center_id;
                $journal->party_info_id     = $payment->party_id;
                $journal->account_head_id   = 123;
                $journal->amount            = $payment->total_amount;
                $journal->tax_rate          = 0;
                $journal->vat_amount        = 0;
                $journal->total_amount      = $payment->total_amount;
                $journal->narration         = $payment->narration;
                $journal->created_by        = Auth::id();
                $journal->authorized_by     = Auth::id();
                $journal->approved_by       = Auth::id();
                $journal->save();
                foreach ($receipt_details as $key => $details) {
                    $purc_exp_itm = new ReceiptSale();
                    $purc_exp_itm->sale_id = $details->sale_id;
                    $purc_exp_itm->company_id = $receipt->company_id;
                    $purc_exp_itm->payment_id = $payment->id;
                    $purc_exp_itm->Total_amount = $details->Total_amount;
                    $purc_exp_itm->vat = 0;
                    $purc_exp_itm->amount = $details->amount;
                    $purc_exp_itm->party_id = $details->party_id;
                    $purc_exp_itm->save();
                    $details->delete();
                    $invoice = JobProjectInvoice::find($purc_exp_itm->sale_id);
                    $head = 3;
                    $income_head = AccountHead::find($head);
                    $jl_record = new JournalRecord();
                    $jl_record->journal_id          = $journal->id;
                    $jl_record->compnay_id = $receipt->company_id;
                    $jl_record->job_project_id          = $invoice->job_project_id;
                    $jl_record->project_details_id  = 1;
                    $jl_record->cost_center_id      = $cost_center_id;
                    $jl_record->party_info_id       = $payment->party_id;
                    $jl_record->journal_no          = $journal_no;
                    $jl_record->account_head_id     = $income_head->id;
                    $jl_record->master_account_id   = $income_head->master_account_id;
                    $jl_record->account_head        = $income_head->fld_ac_head;
                    $jl_record->amount              =  $purc_exp_itm->amount;
                    $jl_record->total_amount        =  $purc_exp_itm->amount;
                    $jl_record->vat_rate_id         = 0;
                    $jl_record->transaction_type    = 'CR';
                    $jl_record->journal_date        = $update_date_format;
                    $jl_record->account_type_id     = $income_head->account_type_id;
                    $jl_record->is_main_head        = 0;
                    $jl_record->save();
                }





                if ($receipt->pay_mode == 'Cash') {
                    $dd = 1;
                } elseif ($receipt->pay_mode == 'Advance') {
                    $dd = 30;
                     if($receipt->job_project_id)
                        {
                            $job_project=JobProject::find($receipt->job_project_id);
                            $job_project->advance_amount = $job_project->advance_amount - $receipt->total_amount;
                            $job_project->save();
                        }

                } else {
                    $dd = 2;
                }
                $pay_head = AccountHead::find($dd);
                $jl_record = new JournalRecord();
                $jl_record->job_project_id          = $journal->job_project_id;
                $jl_record->journal_id          = $journal->id;
                $jl_record->compnay_id = $receipt->company_id;
                $jl_record->project_details_id  = 1;
                $jl_record->cost_center_id      = $cost_center_id;
                $jl_record->party_info_id       = $payment->party_id;
                $jl_record->journal_no          = $journal_no;
                $jl_record->account_head_id     = $pay_head->id;
                $jl_record->master_account_id   = $pay_head->master_account_id;
                $jl_record->account_head        = $pay_head->fld_ac_head;
                $jl_record->amount              = $receipt->total_amount;
                $jl_record->total_amount        = $receipt->total_amount;
                $jl_record->vat_rate_id         = 0;
                $jl_record->transaction_type    = 'DR';
                $jl_record->journal_date        = $update_date_format;
                $jl_record->account_type_id     = $pay_head->account_type_id;
                $jl_record->is_main_head        = 0;
                $jl_record->sub_account_head_id = $payment->bank_id;
                $jl_record->save();
            }
        }
        $receipt->delete();
        $notification = array(
            'message'       => 'Approve successfull!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }
    public function receipt_voucher_edit($id)
    {
        $receipt = TempReceiptVoucher::find($id);
        $parties = PartyInfo::where('id', $receipt->party_id)->get();
        $modes = PayMode::whereNotIn('id', [2, 3, 6])->get();
        $receipt_details = TempReceiptVoucherDetail::where('payment_id', $id)->pluck('sale_id')->toArray();
        $sale_ids = [];
        $invoices = JobProjectInvoice::whereIn('id', $receipt_details)->where('customer_id', $receipt->party_id)->get();
        $invoices2 = JobProjectInvoice::where('customer_id', $receipt->party_id)->where('due_amount', '>', 0)->whereNotIn('id', $receipt_details)->get();
        // dd($invoices2->sum('due_amount'));
        $due = 0;
        $bank_name = AccountSubHead::where('account_head_id', 2)->get();
        return view('backend.receipt-voucher.temp-receipt-voucher-edit', compact('parties', 'modes', 'receipt', 'invoices', 'due', 'invoices2', 'bank_name'));
    }

    public function temp_receipt_voucher_update(Request $request)
    {
        if ($request->pay_mode == 'Advance') {
            $party = PartyInfo::find($request->party_info);
            if ($party->balance < $request->pay_amount) {
                return 1;
            }
        }
        $payment = TempReceiptVoucher::find($request->receipt_voucher_id);
        if ($payment->pay_mode == 'Advance') {
            $party = PartyInfo::find($payment->party_id);
            $party->balance = $party->balance + $payment->total_amount;
            $party->save();
        }
        $payment->bank_id = $request->bank_id;
        $update_date_format = $this->dateFormat($request->date);
        $voucher_file_name = $payment->voucher_file;
        $ext = $payment->extension;
        if ($request->hasFile('voucher_file')) {
            if (Storage::exists('public/upload/documents/' . $payment->voucher_file)) {
                Storage::delete('public/upload/documents/' . $payment->voucher_file);
            }
            $voucher_scan = $request->file('voucher_file');
            $name = $voucher_scan->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $voucher_scan->getClientOriginalExtension();
            $voucher_file_name = $name . time() . '.' . $ext;
            $voucher_scan->storeAs('public/upload/documents', $voucher_file_name);
        }
        $payment->voucher_file = $voucher_file_name;
        $payment->extension = $ext;

        if ($request->pay_mode == "Cheque") {
            $deposit_date = $this->dateFormat($request->deposit_date);
            $payment->date = $update_date_format;
            $payment->pay_mode =  $request->pay_mode;
            $payment->total_amount = $request->pay_amount;
            $payment->narration = $request->narration;
            $payment->issuing_bank = $request->issuing_bank;
            $payment->branch = $request->bank_branch;
            $payment->cheque_no = $request->cheque_no;
            $payment->deposit_date = $deposit_date;
            $payment->paid_amount = $request->pay_amount;
            $payment->due_amount = $request->due_amount - $request->pay_amount;
            $payment->save();
        } else {
            $payment->date = $update_date_format;
            $payment->pay_mode =  $request->pay_mode;
            $payment->total_amount = $request->pay_amount;
            $payment->narration = $request->narration;
            $payment->paid_amount = $request->pay_amount;
            $payment->due_amount = $request->due_amount - $request->pay_amount;
            $payment->save();
        }
        if ($request->voucher_type == 'due') {
            $receipt_voucher_details = TempReceiptVoucherDetail::where('payment_id', $payment->id)->get();
            foreach ($receipt_voucher_details as $key => $details) {
                $sale = JobProjectInvoice::find($details->sale_id);
                $sale->due_amount = $sale->due_amount + $details->Total_amount;
                $sale->paid_amount = $sale->paid_amount - $details->Total_amount;
                $sale->save();
                $details->delete();
            }

            $receipt_lists = $request->records;
            $pay_amount = $request->pay_amount;
            $inv_discount = $request->input('inv_discount');
            if ($receipt_lists) {
                foreach ($receipt_lists as $key => $receipt_l) {
                    if ($pay_amount > 0 || $inv_discount[$key]) {
                        $sale = JobProjectInvoice::find($receipt_l);
                        if ($pay_amount + $inv_discount[$key] < $sale->due_amount) {
                            $paid_amount = $pay_amount + $inv_discount[$key];
                            $amount = $pay_amount + $inv_discount[$key];
                            $sale->due_amount = $sale->due_amount - ($pay_amount + $inv_discount[$key]);
                            $sale->paid_amount = $sale->paid_amount + $pay_amount + $inv_discount[$key];
                            $pay_amount = 0;
                        } else {
                            $paid_amount = $sale->due_amount;
                            $amount = $sale->due_amount;
                            $sale->paid_amount = $sale->paid_amount + $sale->due_amount;
                            $pay_amount = $pay_amount - $sale->due_amount + $inv_discount[$key];
                            $sale->due_amount = 0;
                        }
                        $sale->save();

                        $purc_exp_itm = new TempReceiptVoucherDetail();
                        $purc_exp_itm->sale_id = $sale->id;
                        $purc_exp_itm->payment_id = $payment->id;
                        $purc_exp_itm->Total_amount = $paid_amount;
                        $purc_exp_itm->vat = 0;
                        $purc_exp_itm->amount = $sale->paid_amount;
                        $purc_exp_itm->party_id = $paid_amount;
                        $purc_exp_itm->save();
                    }
                }
            }

            $sale = JobProjectInvoice::where('due_amount', '>', 0)->where('date', '<=', $update_date_format)->where('customer_id',  $request->party_info)->orderBy('date', 'asc')->first();
            if ($sale) {
                while ($pay_amount > 0) {
                    if ($pay_amount < $sale->due_amount) {
                        $paid_amount = $pay_amount;
                        $amount = $pay_amount;
                        $sale->due_amount = $sale->due_amount - $pay_amount;
                        $sale->paid_amount = $sale->paid_amount + $pay_amount;
                        $pay_amount = 0;
                    } else {
                        $paid_amount = $sale->due_amount;
                        $amount = $sale->due_amount;
                        $sale->paid_amount = $sale->paid_amount + $sale->due_amount;
                        $pay_amount = $pay_amount - $sale->due_amount;
                        $sale->due_amount = 0;
                    }
                    $sale->save();

                    $purc_exp_itm = new TempReceiptVoucherDetail();
                    $purc_exp_itm->sale_id = $sale->id;
                    $purc_exp_itm->payment_id = $payment->id;
                    $purc_exp_itm->Total_amount =  $paid_amount;
                    $purc_exp_itm->vat = 0;
                    $purc_exp_itm->amount =  $paid_amount;
                    $purc_exp_itm->party_id = $request->party_info;
                    $purc_exp_itm->save();
                    $sale = JobProjectInvoice::where('due_amount', '>', 0)->where('date', '<=', $update_date_format)->where('customer_id',  $request->party_info)->orderBy('date', 'asc')->first();
                    if (!$sale) {
                        $advance = $pay_amount;
                        $pay_amount = 0;
                    }
                }
            }
        }
        if ($payment->pay_mode == 'Advance') {
            $party = PartyInfo::find($payment->party_id);
            $party->balance = $party->balance - $request->pay_amount;
            $party->save();
        }

        $recept = $payment;
        $temp_receipt_list = TempReceiptVoucher::orderBy('id', 'desc')->paginate(40);

        return response()->json([
            'preview' => view('backend.receipt-voucher.receipt-preview', compact('recept'))->render(),
            'list' => view('backend.receipt-voucher._ajax_list', compact('temp_receipt_list'))->render(),
        ]);
    }

    public function temp_receipt_delete($id)
    {
        $receipt = TempReceiptVoucher::find($id);
        if ($receipt->pay_mode == 'Advance') {
            $party = PartyInfo::find($receipt->party_id);
            $party->balance = $party->balance + $receipt->total_amount;
            $party->save();
        }
        foreach ($receipt->items as $itm) {
            $inv = JobProjectInvoice::find($itm->sale_id);
            $inv->due_amount = $inv->due_amount + $itm->amount;
            $inv->paid_amount = $inv->paid_amount - $itm->amount;
            $inv->save();
            $itm->delete();
        }
        $receipt->delete();
        $notification = array(
            'message'       => 'Deleted successfull!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function direct_receipt()
    {
        $modes = PayMode::whereNotIn('id', [2, 5])->get();
        return view('backend.receipt-voucher.direct-receipt', compact('modes'));
    }


    public function direct_receipt_post(Request $request)
    {
        $update_date_format = $this->dateFormat($request->date);
        $job_project = JobProject::find($request->project_id);
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



        $total_vat = 0;
        $total_amount_withvat = 0;
        $total_amount = 0;
        $cost_center_id = 0;
        if ($request->cost_center_name != null) {
            $cost_center_id = $request->cost_center_name;
        }
        if ($request->pay_mode == 'Cheque') {
            $deposit_date = $this->dateFormat($request->deposit_date);
            $payment = new TempReceiptVoucher();
            $payment->date = $update_date_format;
            $payment->pay_mode =  $request->pay_mode;
            $payment->bank_id =  $request->bank_id;
            $payment->receipt_no = $receipt_no;
            $payment->head_id = 0;
            $payment->total_amount = $request->pay_amount;
            $payment->vat = 0;
            $payment->party_id =  56;
            $payment->narration = $request->narration;
            $payment->issuing_bank = $request->issuing_bank;
            $payment->branch = $request->bank_branch;
            $payment->cheque_no = $request->cheque_no;
            $payment->deposit_date = $deposit_date;
            $payment->status = 'Pending';
            $payment->paid_amount = $request->pay_amount;
            $payment->due_amount = $request->due_amount - $request->pay_amount;
            $payment->is_direct = 1;
            $payment->name = $request->cus_name;
            $payment->type = 'Direct Receipt';
            $payment->is_authorize = 1;

            $payment->save();
        } else {
            $payment = new TempReceiptVoucher();
            $payment->date = $update_date_format;
            $payment->pay_mode =  $request->pay_mode;
            $payment->bank_id =  $request->bank_id;
            $payment->receipt_no = $receipt_no;
            $payment->head_id = 0;
            $payment->total_amount = $request->pay_amount;
            $payment->vat = 0;
            $payment->party_id =  56;
            $payment->narration = $request->narration;
            $payment->status = 'Realised';
            $payment->paid_amount = $request->pay_amount;
            $payment->is_direct = 1;
            $payment->name = $request->cus_name;
            $payment->type = 'Direct Receipt';
            $payment->due_amount = 0;
            $payment->is_authorize = 1;

            $payment->save();
        }
        $let_purch_exp = InvoiceNumber::first();
        $let_purch_exp->receipt_invoice_number = $payment->receipt_no;
        $let_purch_exp->save();
        $recept = $payment;
        return view('backend.receipt-voucher.receipt-preview', compact('recept'));
    }
}

<?php

namespace App\Imports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use App\ExpenseImport;
use App\JobProject;
use App\Journal;
use App\JournalRecord;
use App\AccountSubHead;
use App\Payment;
use App\PaymentInvoice;
use App\PurchaseExpenseItem;
use App\Models\AccountHead;
use App\NewProject;
use App\PurchaseExpense;
use App\Models\InvoiceNumber;
use Carbon\Carbon as CarbonCarbon;
use DateTime;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
class ExpenseExcelImport implements ToModel, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private function journal_no()
    {
        $sub_invoice = CarbonCarbon::now()->format('Ymd');
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
    public function chunkSize(): int
    {
        return 1000;
    }
    public function startRow(): int
    {
        return 2;
    }
    public function model(array $row)
    {
        $token = Session::get('token');
        if($row[0]=='Project Code' || $row[0]=='PROJECT CODE'|| $row[0]=='CODE'|| $row[0]=='Code' || $row[1]=='Project Name' || $row[2]=='Date'){
            return;
        }else{
            $date = null;
            // dd($row[2]);
            if($row[2]){
                if(gettype($row[2]) == 'integer' || gettype($row[2]) == 'double'){
                    $excel_date = $row[2]; //here is that value 41621 or 41631
                    $unix_date = ($excel_date - 25569) * 86400;
                    // dd($unix_date);
                    $excel_date = 25569 + ($unix_date / 86400);
                    $unix_date = ($excel_date - 25569) * 86400;
                    $date = gmdate("Y-m-d", $unix_date);
                }else{
                    $date1 = DateTime::createFromFormat('d-m-y', $row[2]);
                    if ($date1 === false) {
                        $date1 = DateTime::createFromFormat('d/m/Y', $row[2]);
                    }
                    if ($date1 !== false) {
                        $date = $date1->format('Y-m-d');
                    } else {
                        $old_date = explode('/', $row[2]);
                        $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
                        $new_date = date('Y-m-d', strtotime($new_data));
                        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
                        $date = $new_date->format('Y-m-d');
                    }
                }
            }
            $narration = $row[3]?$row[3]:'n/a';
            $project_info = NewProject::where('project_no', $row[0])->first();
            if(!$project_info){
                $project_info = NewProject::whereRaw('LOWER(?) LIKE CONCAT("%", LOWER(name), "%")', [trim($row[1])])->first();
            }
            $onboard_project = JobProject::where('project_id', $project_info->id??null)->first();
            if(!$onboard_project){
                $onboard_project = JobProject::whereRaw('LOWER(?) LIKE CONCAT("%", LOWER(project_name), "%")', [trim($row[1])])->first();
            }
            // dd($onboard_project);
            $account_head_id = null;
            $sub_head_id = null;
            if($row[8]){
                $sub_head_info = AccountSubHead::where('name',$row[8])->first();
                if($sub_head_info){
                    $sub_head_id = $sub_head_info->id;
                    $account_head_id = $sub_head_info->account_head_id;
                }else{
                    $account_head_info = AccountHead::where('fld_ac_head', $row[8])->first();
                    $account_head_id = $account_head_info->id??null;
                }
            }else{
                $account_head_info = AccountHead::find(1767);
                $account_head_id = $account_head_info->id;
            }
            $amount = null;
            if(is_numeric($row[7])) {
                $amount = $row[7];
            }
            $invoice_no = $row[4];
            $bill_no = $row[5]?$row[5]:'n/a';
            $description = $row[6]?$row[6]:'n/a';
            // dd($onboard_project);
            if($date && $account_head_id && $onboard_project && $amount){
                $purch_ex                   = new PurchaseExpense();
                $purch_ex->date             = $date;
                $purch_ex->job_project_id   = $project_info->id??null;
                $purch_ex->pay_mode         = 'Bank';
                $purch_ex->purchase_no      = $this->temp_purchase_expense_no();
                $purch_ex->invoice_no       = $invoice_no;
                $purch_ex->project_id       = 0;
                $purch_ex->invoice_type     = 'Tax Invoice';
                $purch_ex->head_id          = 0;
                $purch_ex->total_amount     = $amount;
                $purch_ex->vat              = 0.00;
                $purch_ex->amount           = $amount;
                $purch_ex->party_id         = 112;
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
                $purc_exp_itm->item_description     = $bill_no;
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

            }else{
                return new ExpenseImport([
                    'token'             => $token,
                    'project_code'      => $row[0]?$row[0]:null,
                    'project_name'      => $row[1]?$row[1]:null,
                    'date'              => date('Y-m-d', strtotime($date)), //$row[2]
                    'party_id'          => $row[3]?$row[3]:null,
                    'vr'                => $row[4]?$row[4]:null,
                    'bill_no'           => $row[5]?$row[5]:null,
                    'description'       => $row[6]?$row[6]:null,
                    'amount'            => $row[7]?$row[7]:null,
                    'account_head'      => $row[8]?$row[8]:null,
                ]);
            }
        }
        return;
    }
}

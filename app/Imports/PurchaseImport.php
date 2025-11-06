<?php

namespace App\Imports;

use App\AccountSubHead;
use App\JobProject;
use App\Journal;
use App\JournalRecord;
use App\Models\AccountHead;
use App\Models\InvoiceNumber;
use App\PartyInfo;
use App\Payment;
use App\PaymentInvoice;
use App\PurchaseExpense;
use App\PurchaseExpenseItem;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class PurchaseImport implements ToCollection, WithHeadingRow
{
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

    private function temp_purchase_expense_no()
    {
        $sub_invoice = 'P' . Carbon::now()->format('y');
        // return $sub_invoice;
        $let_purch_exp = InvoiceNumber::where('purchase_no', 'LIKE', "%{$sub_invoice}%")->first();
        if ($let_purch_exp) {
            $purch_no = preg_replace('/^' . $sub_invoice . '/', '', $let_purch_exp->purchase_no);
            $purch_code = $purch_no + 1;
            if ($purch_code < 10) {
                $purch_no = $sub_invoice . '000' . $purch_code;
            } elseif ($purch_code < 100) {
                $purch_no = $sub_invoice . '00' . $purch_code;
            } elseif ($purch_code < 1000) {
                $purch_no = $sub_invoice . '0' . $purch_code;
            } else {
                $purch_no = $sub_invoice . $purch_code;
            }
        } else {
            $purch_no = $sub_invoice . '0001';
        }
        return $purch_no;
    }
    private $rowNumber = 1;
    private $skippedRows = [];

    public function collection(\Illuminate\Support\Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // +2 because header is row 1
            $mandatoryFields = [
                'date',
                // 'invoice',
                'debit',
                'credit',
                'amount',
                'total',
                'supplier_name',
                // 'project',
                // 'project_no'
            ];

            $missingFields = [];
            foreach ($mandatoryFields as $field) {
                if (!isset($row[$field]) || trim($row[$field]) === '') {
                    // dd($field);
                    $missingFields[] = $field;
                }
            }

            if (!empty($missingFields)) {
                $message = "Skipping Invoice : {$row['invoice']}, Row: {$rowNum}, Missing Fields: " . implode(', ', $missingFields);
                $this->skippedRows[] = $message;
            }
        }

        // 2️⃣ Stop import if any mandatory field missing
        if (!empty($this->skippedRows)) {
        } else {
            // 3️⃣ Start DB transaction
            DB::beginTransaction();
            try {
                foreach ($rows as $row) {
                    $this->processRow($row); // your previous processRow logic
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }


    private function processRow($row)
    {
        $party = PartyInfo::whereRaw('LOWER(?) LIKE CONCAT("%", LOWER(pi_name), "%")', [trim($row['supplier_name'])])->where('pi_type', 'Supplier')->first();
        // dd($party);
        if (!$party) {
            $latest = PartyInfo::withTrashed()->orderBy('id', 'DESC')->first();
            if ($latest) {
                $pi_code = preg_replace('/^PI-/', '', $latest->pi_code);
                ++$pi_code;
            } else {
                $pi_code = 1;
            }
            if ($pi_code < 10) {
                $cc = "PI-000" . $pi_code;
            } elseif ($pi_code < 100) {
                $cc = "PI-00" . $pi_code;
            } elseif ($pi_code < 1000) {
                $cc = "PI-0" . $pi_code;
            } else {
                $cc = "PI-" . $pi_code;
            }
            $party = new PartyInfo;
            $party->fill([
                'pi_code' => $cc,
                'pi_name' => $row['supplier_name'],
                'pi_type' => 'Supplier',
                'trn_no' => $row['trn']
            ]);
            $party->save();
        }
        $project = JobProject::where('project_no', $row['project_no'])->first();

        if (!$project && $row['project_no']) {

            $message = "Skipping Project :  (Project not found: {$row['project_no']})";
            if (!in_array($message, $this->skippedRows)) {
                $this->skippedRows[] = $message;
            }
            return null;
        }

        $cinv = PurchaseExpense::where('invoice_no', $row['invoice'])->first();
        if (!$cinv || !$row['invoice']) {
            //  if ($project) {
            if (1 == 1) {
                $numericFields = [
                    'net_amount' => $row['amount'] ?? 0,
                    'vat' => $row['vat'] ?? 0,
                    'total_gross_amount' => $row['total'] ?? 0,
                    'retention_amount' =>  0,
                ];

                foreach ($numericFields as $field => $value) {
                    if (!is_numeric($value)) {
                        $message = "Skipping Invoice : {$row['invoice']} (Invalid {$field} = {$value})";
                        if (!in_array($message, $this->skippedRows)) {
                            $this->skippedRows[] = $message;
                        }
                        return null; // ❌ Skip this row
                    }
                }
                if (gettype($row['date']) == 'integer' || gettype($row['date']) == 'double') {
                    $excel_date = $row['date']; //here is that value 41621 or 41631
                    $unix_date = ($excel_date - 25569) * 86400;
                    // dd($unix_date);
                    $excel_date = 25569 + ($unix_date / 86400);
                    $unix_date = ($excel_date - 25569) * 86400;
                    $date1 = gmdate("Y-m-d", $unix_date);
                } else {
                    $date = DateTime::createFromFormat('d/m/Y', $row['date']);;
                    $date1 = $date->format('Y-m-d');
                }
                if (1 == 1) {
                    $dr_sub_Account = null;
                    $dr_Account =       AccountHead::whereRaw('LOWER(?) LIKE CONCAT("%", LOWER(fld_ac_head), "%")', [trim($row['debit'])])->first();
                    if (!$dr_Account) {
                        $dr_sub_Account = AccountSubHead::whereRaw('LOWER(?) LIKE CONCAT("%", LOWER(name), "%")', [trim($row['debit'])])->first();
                        if ($dr_sub_Account) {
                            $dr_Account = AccountHead::find($dr_sub_Account->account_head_id);
                        }
                    }
                    $cr_sub_Account = null;
                    $cr_Account =       AccountHead::whereRaw('LOWER(?) LIKE CONCAT("%", LOWER(fld_ac_head), "%")', [trim($row['credit'])])->first();
                    if (!$cr_Account) {
                        $cr_sub_Account = AccountSubHead::whereRaw('LOWER(?) LIKE CONCAT("%", LOWER(name), "%")', [trim($row['credit'])])->first();
                        if ($cr_sub_Account) {
                            $dr_Account = AccountHead::find($cr_sub_Account->account_head_id);
                        }
                    }

                    if (!$dr_Account) {
                        $message = "Skipping Invoice : {$row['invoice']} (Debit account not found: {$row['debit']})";
                        if (!in_array($message, $this->skippedRows)) {
                            $this->skippedRows[] = $message;
                        }
                        return null;
                    }

                    if (!$cr_Account) {
                        $message = "Skipping Invoice : {$row['invoice']} (Credit account not found: {$row['credit']})";
                        if (!in_array($message, $this->skippedRows)) {
                            $this->skippedRows[] = $message;
                        }
                        return null;
                    }

                    // ***********
                    $purch_ex                   = new PurchaseExpense();
                    $purch_ex->date             = $date1;
                    $purch_ex->job_project_id   = $project ? $project->id : null;
                    $purch_ex->pay_mode         = $cr_Account->id == 5 ? 'Credit' : $row['credit'];
                    $purch_ex->purchase_no      = $this->temp_purchase_expense_no();
                    $purch_ex->invoice_no       = $row['invoice'];
                    $purch_ex->project_id       = 0;
                    $purch_ex->invoice_type     = 'Tax Invoice';
                    $purch_ex->head_id          = 0;
                    $purch_ex->total_amount     = $row['total'];
                    $purch_ex->vat              = $row['vat'];
                    $purch_ex->amount           =  $row['amount'];
                    $purch_ex->party_id         = $party->id;
                    $purch_ex->narration        = 'Excel Import';
                    $purch_ex->head_details     = $row['invoice'];
                    $purch_ex->gst_subtotal     = 0.00;
                    $purch_ex->paid_amount      = $cr_Account->id != 5 ? $row['total'] : 0;
                    $purch_ex->due_amount       = $purch_ex->total_amount - $purch_ex->paid_amount;
                    $purch_ex->created_by       = Auth::id();
                    $purch_ex->authorized_by    = Auth::id();
                    $purch_ex->approved_by      = Auth::id();
                    $purch_ex->save();

                    $purchase_number                = InvoiceNumber::find(1);
                    $purchase_number->purchase_no   = $purch_ex->purchase_no;
                    $purchase_number->save();



                    $purc_exp_itm = new PurchaseExpenseItem();
                    $purc_exp_itm->head_id              = $dr_Account->id;
                    $purc_exp_itm->sub_head_id          = null;
                    $purc_exp_itm->item_description     = $row['debit'];
                    $purc_exp_itm->qty                  = 1;
                    $purc_exp_itm->unit_id              = null;
                    $purc_exp_itm->rate                 = $purch_ex->amount;
                    $purc_exp_itm->amount               = $purch_ex->amount;
                    $purc_exp_itm->vat                  = $purch_ex->vat;
                    $purc_exp_itm->total_amount         = $purch_ex->total_amount;
                    $purc_exp_itm->party_id             = $purch_ex->party_id;
                    $purc_exp_itm->purchase_expense_id  = $purch_ex->id;
                    $purc_exp_itm->gst_subtotal         = 0.00;
                    $purc_exp_itm->save();


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


                    //journal record
                    $jl_record                      = new JournalRecord();
                    $jl_record->journal_id          = $journal->id;
                    $jl_record->project_details_id  = $journal->project_id;
                    $jl_record->cost_center_id      = $journal->cost_center_id;
                    $jl_record->party_info_id       = $journal->party_info_id;
                    $jl_record->journal_no          = $journal->journal_no;
                    $jl_record->sub_account_head_id = $dr_sub_Account ? $dr_sub_Account->id : null;
                    $jl_record->account_head_id     = $dr_Account->id;
                    $jl_record->master_account_id   = $dr_Account->master_account_id;
                    $jl_record->account_head        = $dr_Account->fld_ac_head;
                    $jl_record->amount              = $purch_ex->amount;
                    $jl_record->total_amount        = $purch_ex->amount;
                    $jl_record->vat_rate_id         = 0;
                    $jl_record->invoice_no          = 0;
                    $jl_record->transaction_type    = 'DR';
                    $jl_record->journal_date        = $journal->date;
                    $jl_record->is_main_head        = 1;
                    $jl_record->account_type_id     = $dr_Account->account_type_id;
                    $jl_record->compnay_id          = $onboard_project->compnay_id ?? null;
                    $jl_record->save();
                    //end journal record

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
                        $jl_record->amount              =  $purch_ex->vat;
                        $jl_record->invoice_no              = 'N/A';
                        $jl_record->total_amount        =  $purch_ex->vat;
                        $jl_record->vat_rate_id         = 0;
                        $jl_record->transaction_type    = 'DR';
                        $jl_record->journal_date        = $journal->date;
                        $jl_record->account_type_id = $vat_ac_head->account_type_id;
                        $jl_record->compnay_id         =  $project ? $project->compnay_id : null;
                        $jl_record->is_main_head        = 0;
                        $jl_record->save();
                    }
                    //end vat journal

                    //Paymode journal

                    $jl_record = new JournalRecord();
                    $jl_record->journal_id     = $journal->id;
                    $jl_record->project_details_id  = $journal->project_id;
                    $jl_record->cost_center_id      = $journal->cost_center_id;
                    $jl_record->party_info_id       = $journal->party_info_id;
                    $jl_record->journal_no          =  $journal->journal_no;
                    $jl_record->account_head_id     = $cr_Account->id;
                    $jl_record->master_account_id   = $cr_Account->master_account_id;
                    $jl_record->account_head        = $cr_Account->fld_ac_head;
                    $jl_record->amount              = $purch_ex->total_amount;
                    $jl_record->total_amount        = $purch_ex->total_amount;
                    $jl_record->vat_rate_id         = 0;
                    $jl_record->transaction_type    = 'CR';
                    $jl_record->journal_date        = $journal->date;
                    $jl_record->invoice_no              = 'N/A';
                    $jl_record->account_type_id = $cr_Account->account_type_id;
                    $jl_record->compnay_id         = $project ? $project->compnay_id : null;
                    $jl_record->is_main_head        = 0;
                    $jl_record->save();

                    // dd($invoice);
                    if ($purch_ex->paid_amount > 0) {
                        $sub_invoice = 'PV' . Carbon::now()->format('y');
                        $let_purch_exp = InvoiceNumber::where('payment_no', 'LIKE', "%{$sub_invoice}%")->first();
                        if ($let_purch_exp) {
                            $purch_code = preg_replace('/^' . $sub_invoice . '/', '', $let_purch_exp->payment_no);
                            $purch_code = $purch_code + 1;
                            if ($purch_code < 10) {
                                $payment_no = $sub_invoice . '000' . $purch_code;
                            } elseif ($purch_code < 100) {
                                $payment_no = $sub_invoice . '00' . $purch_code;
                            } elseif ($purch_code < 1000) {
                                $payment_no = $sub_invoice . '0' . $purch_code;
                            } else {
                                $payment_no = $sub_invoice . $purch_code;
                            }
                        } else {
                            $payment_no = $sub_invoice . '0001';
                        }
                        $payment                = new Payment();
                        $payment->date          = $journal->date;
                        $payment->paid_by       = Auth::id();
                        $payment->pay_mode      = $purch_ex->pay_mode;
                        $payment->payment_no    = $payment_no;
                        $payment->head_id       = 0;
                        $payment->total_amount  = $purch_ex->total_amount;
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
                } else {
                    $message = "Skipping Invoice : (Amount Exceed) {$row['invoice']}, INV: {$row['invoice']}";
                    if (!in_array($message, $this->skippedRows)) {
                        $this->skippedRows[] = $message;
                    }
                }
            } else {
                $message = "Skipping Invoice : {$row['invoice']}, INV: {$row['invoice']}";
                if (!in_array($message, $this->skippedRows)) {
                    $this->skippedRows[] = $message;
                }
            }
        } else {
            $message = "Skipping Invoice : {$row['invoice']}, INV: {$row['invoice']} Already exist";
            if (!in_array($message, $this->skippedRows)) {
                $this->skippedRows[] = $message;
            }
        }
    }



    public function getSkippedRows(): array
    {
        return $this->skippedRows;
    }
}

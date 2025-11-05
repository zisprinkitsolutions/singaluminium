<?php

namespace App\Imports;

use App\JobProject;
use App\JobProjectInvoice;
use App\JobProjectInvoiceTask;
use App\Journal;
use App\JournalRecord;
use App\Models\AccountHead;
use App\NewProject;
use App\PartyInfo;
use App\Receipt;
use App\ReceiptSale;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiptImport implements ToCollection, WithHeadingRow
{
    private $receipt_no = 'start';
    private $rowNumber = 1;
    private $skippedRows = [];

    /**
     * Convert Excel/CSV values to safe numeric
     */
    private function toNumber($value)
    {
        // Remove commas, spaces, currency symbols etc.
        return (float) str_replace([',', ' ', 'AED', '৳', '$'], '', $value ?? 0);
    }

    public function collection(\Illuminate\Support\Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // +2 because header is row 1
            $mandatoryFields = [
                'date',
                'receipt_no',
                'amount',
                'pay_mode',
                'total_amount',
            ];

            $missingFields = [];
            foreach ($mandatoryFields as $field) {
                if (!isset($row[$field]) || trim($row[$field]) === '') {
                    $missingFields[] = $field;
                }
            }

            if (!empty($missingFields)) {
                $message = "Skipping Invoice : {$row['project']}, Row: {$rowNum}, Missing Fields: " . implode(', ', $missingFields);
                $this->skippedRows[] = $message;
            }
        }

        // Stop import if any mandatory field missing
        if (!empty($this->skippedRows)) {
            return;
        }

        // Start DB transaction
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
                $this->processRow($row);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function processRow($row)
    {
        $sub_invoice = Carbon::now()->format('Ymd');

        $latest_journal_no = Journal::withTrashed()
            ->whereDate('created_at', Carbon::today())
            ->where('journal_no', 'LIKE', "%{$sub_invoice}%")
            ->orderBy('id', 'desc')
            ->first();

        if ($latest_journal_no) {
            $journal_no = substr($latest_journal_no->journal_no, 0, -1);
            $journal_code = $journal_no + 1;
            $journal_no = $journal_code . "J";
        } else {
            $journal_no = Carbon::now()->format('Ymd') . '001' . "J";
        }

        // Sanitize numbers
        $amount = $this->toNumber($row['amount']);
        $totalAmount = $this->toNumber($row['total_amount']);

        if ($this->receipt_no == $row['receipt_no']) {
            // Same receipt - add new line
            $rec = Receipt::where('receipt_no', $row['receipt_no'])->first();
            $invoice = JobProjectInvoice::where('invoice_no', $row['invoice_no'])->first();

            if ($invoice) {

                  if($invoice->due_amount<$amount)
                {
                    $this->skippedRows[] = " Skipping Receipt : " . $row['receipt_no'] . ', Invocie: ' . $row['invoice_no'] . ' Amount Exceed';
                    return;
                }
                ReceiptSale::create([
                    'sale_id' => $invoice->id,
                    'company_id' => $rec->company_id ?? null,
                    'payment_id' => $rec->id,
                    'Total_amount' => $amount,
                    'vat' => 0,
                    'amount' => $amount,
                    'party_id' => $rec->id,
                ]);
                $invoice->due_amount = $invoice->due_amount - $amount;
                $invoice->paid_amount = $invoice->paid_amount + $amount;
                $invoice->save();
            } else {
                $message = " Skipping  Receipt : " . $row['receipt_no'] . ', Invoice: ' . $row['invoice_no'] . ' not found';
                if (!in_array($message, $this->skippedRows)) {
                    $this->skippedRows[] = $message;
                }
            }
            return;
        }

        // New receipt
        $this->receipt_no = $row['receipt_no'];

        // Date handling
        if (is_numeric($row['date'])) {
            $excel_date = $row['date'];
            $unix_date = ($excel_date - 25569) * 86400;
            $excel_date = 25569 + ($unix_date / 86400);
            $unix_date = ($excel_date - 25569) * 86400;
            $date1 = gmdate("Y-m-d", $unix_date);
        } else {
            $date = DateTime::createFromFormat('d/m/Y', $row['date']);
            $date1 = $date ? $date->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        }

        $checkReceipt = Receipt::where('receipt_no', $row['receipt_no'])->first();
        if (!$checkReceipt) {
            if ($row['invoice_no']) {
                // Linked with invoice
                $invoice = JobProjectInvoice::where('invoice_no', $row['invoice_no'])->first();
                if (!$invoice) {
                    $this->skippedRows[] = " Skipping Receipt : " . $row['receipt_no'] . ', Invocie: ' . $row['invoice_no'] . ' not found';
                    return;
                }

                if($invoice->due_amount<$amount)
                {
                    $this->skippedRows[] = " Skipping Receipt : " . $row['receipt_no'] . ', Invocie: ' . $row['invoice_no'] . ' Amount Exceed';
                    return;
                }



                $party = PartyInfo::where('id', $invoice->customer_id)->where('pi_type', 'Customer')->first();
                $project = JobProject::where('id', $invoice->job_project_id)->first();

                $payment = new Receipt();
                $payment->date = $date1;
                $payment->company_id = $project ? $project->compnay_id : null;
                $payment->pay_mode = $row['pay_mode'];
                $payment->receipt_no = $row['receipt_no'];
                $payment->head_id = 0;
                $payment->total_amount = $totalAmount;
                $payment->vat = 0;
                $payment->party_id = $party->id;
                $payment->narration = $row['narration'];
                $payment->status = 'Realised';
                $payment->paid_amount = $totalAmount;
                $payment->due_amount = 0;
                $payment->type = 'due';
                $payment->save();

                $invoice->due_amount -= $amount;
                $invoice->paid_amount += $amount;
                $invoice->save();

                ReceiptSale::create([
                    'sale_id' => $invoice->id,
                    'company_id' => $payment->company_id ?? null,
                    'payment_id' => $payment->id,
                    'Total_amount' => $amount,
                    'vat' => 0,
                    'amount' => $amount,
                    'party_id' => $payment->party_id,
                ]);

                // Journal entry
                $journal = new Journal();
                $journal->project_id = 1;
                $journal->transection_type = 'RECEIPT VOUCHER';
                $journal->transaction_type = 'DEBIT';
                $journal->journal_no = $journal_no;
                $journal->date = $payment->date;
                $journal->voucher_type = 'Receipt Voucher';
                $journal->receipt_id = $payment->id;
                $journal->pay_mode = $payment->pay_mode;
                $journal->invoice_no = 0;
                $journal->cost_center_id = 0;
                $journal->party_info_id = $payment->party_id;
                $journal->account_head_id = 123;
                $journal->amount = $totalAmount;
                $journal->tax_rate = 0;
                $journal->vat_amount = 0;
                $journal->total_amount = $totalAmount;
                $journal->narration = $payment->narration;
                $journal->created_by = Auth::id();
                $journal->authorized_by = Auth::id();
                $journal->approved_by = Auth::id();
                $journal->save();

                // Credit record (income)
                $income_head = AccountHead::find(3);
                $jl_record = new JournalRecord();
                $jl_record->journal_id = $journal->id;
                $jl_record->compnay_id = $payment->company_id;
                $jl_record->job_project_id = $project ? $project->id : null;
                $jl_record->project_details_id = 1;
                $jl_record->cost_center_id = 0;
                $jl_record->party_info_id = $payment->party_id;
                $jl_record->journal_no = $journal_no;
                $jl_record->account_head_id = $income_head->id;
                $jl_record->master_account_id = $income_head->master_account_id;
                $jl_record->account_head = $income_head->fld_ac_head;
                $jl_record->amount = $totalAmount;
                $jl_record->total_amount = $totalAmount;
                $jl_record->vat_rate_id = 0;
                $jl_record->transaction_type = 'CR';
                $jl_record->journal_date = $payment->date;
                $jl_record->account_type_id = $income_head->account_type_id;
                $jl_record->is_main_head = 0;
                $jl_record->save();

                // Debit record (Cash/Bank/Advance)
                if (strcasecmp($payment->pay_mode, 'Cash') === 0) {
                    $dd = 1;
                } elseif (strcasecmp($payment->pay_mode, 'Advance') === 0) {
                    $dd = 30;
                } else {
                    $dd = 2;
                }

                $pay_head = AccountHead::find($dd);
                $jl_record = new JournalRecord();
                $jl_record->journal_id = $journal->id;
                $jl_record->project_details_id = 1;
                $jl_record->cost_center_id = 0;
                $jl_record->party_info_id = $payment->party_id;
                $jl_record->journal_no = $journal_no;
                $jl_record->account_head_id = $pay_head->id;
                $jl_record->master_account_id = $pay_head->master_account_id;
                $jl_record->account_head = $pay_head->fld_ac_head;
                $jl_record->amount = $totalAmount;
                $jl_record->total_amount = $totalAmount;
                $jl_record->vat_rate_id = 0;
                $jl_record->transaction_type = 'DR';
                $jl_record->journal_date = $payment->date;
                $jl_record->account_type_id = $pay_head->account_type_id;
                $jl_record->is_main_head = 0;
                $jl_record->sub_account_head_id = $payment->bank_id;
                $jl_record->compnay_id = $payment->company_id;
                $jl_record->job_project_id = $project ? $project->id : null;
                $jl_record->save();
            } else {
                // No invoice number -> Advance receipt
                $party = PartyInfo::where('pi_name', $row['party_name'])
                    ->where('pi_type', 'Customer')
                    ->first();

                if (!$party) {
                    $this->skippedRows[] = " Skipping Receipt : " . $row['receipt_no'] . ', Invoice: ' . $row['invoice_no'] . ', Party: ' . $row['party_name'] . ' not found';
                    return;
                }

                $payment = new Receipt();
                $payment->date = $date1;
                $payment->company_id = null;
                $payment->pay_mode = $row['pay_mode'];
                $payment->bank_id = $row['bank_id'];
                $payment->receipt_no = $row['receipt_no'];
                $payment->head_id = 0;
                $payment->total_amount = $totalAmount;
                $payment->vat = 0;
                $payment->party_id = $party->id;
                $payment->narration = $row['narration'];
                $payment->status = 'Realised';
                $payment->paid_amount = $totalAmount;
                $payment->due_amount = 0;
                $payment->type = 'advance';
                $payment->save();

                $party->balance += $totalAmount;
                $party->save();

                // Journal
                $journal = new Journal();
                $journal->project_id = 1;
                $journal->transection_type = 'RECEIPT VOUCHER';
                $journal->transaction_type = 'DEBIT';
                $journal->journal_no = $journal_no;
                $journal->date = $payment->date;
                $journal->voucher_type = 'Receipt Voucher';
                $journal->receipt_id = $payment->id;
                $journal->pay_mode = $payment->pay_mode;
                $journal->invoice_no = 0;
                $journal->cost_center_id = 0;
                $journal->party_info_id = $payment->party_id;
                $journal->account_head_id = 123;
                $journal->amount = $totalAmount;
                $journal->tax_rate = 0;
                $journal->vat_amount = 0;
                $journal->total_amount = $totalAmount;
                $journal->narration = $payment->narration;
                $journal->created_by = Auth::id();
                $journal->authorized_by = Auth::id();
                $journal->approved_by = Auth::id();
                $journal->save();

                // Credit record (advance)
                $income_head = AccountHead::find(30); // advance account head
                $jl_record = new JournalRecord();
                $jl_record->journal_id = $journal->id;
                $jl_record->project_details_id = 1;
                $jl_record->cost_center_id = 0;
                $jl_record->party_info_id = $payment->party_id;
                $jl_record->journal_no = $journal_no;
                $jl_record->account_head_id = $income_head->id;
                $jl_record->master_account_id = $income_head->master_account_id;
                $jl_record->account_head = $income_head->fld_ac_head;
                $jl_record->amount = $totalAmount;
                $jl_record->total_amount = $totalAmount;
                $jl_record->vat_rate_id = 0;
                $jl_record->transaction_type = 'CR';
                $jl_record->journal_date = $date1;
                $jl_record->account_type_id = $income_head->account_type_id;
                $jl_record->is_main_head = 0;
                $jl_record->compnay_id = $payment->company_id;
                $jl_record->save();

                // Debit record (cash/bank)
                $dd = strcasecmp($payment->pay_mode, 'Cash') === 0 ? 1 : 2;
                $pay_head = AccountHead::find($dd);

                $jl_record = new JournalRecord();
                $jl_record->journal_id = $journal->id;
                $jl_record->project_details_id = 1;
                $jl_record->cost_center_id = 0;
                $jl_record->party_info_id = $payment->party_id;
                $jl_record->journal_no = $journal_no;
                $jl_record->account_head_id = $pay_head->id;
                $jl_record->master_account_id = $pay_head->master_account_id;
                $jl_record->account_head = $pay_head->fld_ac_head;
                $jl_record->amount = $totalAmount;
                $jl_record->total_amount = $totalAmount;
                $jl_record->vat_rate_id = 0;
                $jl_record->transaction_type = 'DR';
                $jl_record->journal_date = $date1;
                $jl_record->account_type_id = $pay_head->account_type_id;
                $jl_record->is_main_head = 0;
                $jl_record->sub_account_head_id = $payment->bank_id;
                $jl_record->compnay_id = $payment->company_id;
                $jl_record->save();
            }
        }
        else
        {
            $this->skippedRows[] = " Skipping Receipt : " . $row['receipt_no'] . ' Already Exist';
                    return;
        }
    }

    public function getSkippedRows(): array
    {
        return $this->skippedRows;
    }
}

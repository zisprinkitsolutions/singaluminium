<?php

namespace App;

use App\Models\AccountHead;
use App\Models\CostCenter;
use App\Payment;
use App\Models\FundAllocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Journal extends Model
{
    protected $guarded = [];
    use  SoftDeletes;
    public function records()
    {
        return $this->hasMany(JournalRecord::class);
    }
    public function project()
    {
        return $this->belongsTo(ProjectDetail::class, 'project_id');
    }

    public function purchaseExp()
    {
        return $this->belongsTo(PurchaseExpense::class, 'purchase_expense_id');
    }
    public function jobProject()
    {
        return $this->belongsTo(JobProject::class, 'job_project_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class, 'receipt_id');
    }


    public function costCenter()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    public function profitCenter()
    {
        return $this->belongsTo(ProfitCenter::class, 'profit_center_id');
    }

    public function PartyInfo()
    {
        return $this->belongsTo(PartyInfo::class, 'party_info_id');
    }

    public function accHead()
    {
        return $this->belongsTo(AccountHead::class, 'account_head_id');
    }

    public function taxRate()
    {
        return $this->belongsTo(VatRate::class, 'tax_rate');
    }

    public function creditPartyInfo()
    {
        return $this->belongsTo(PartyInfo::class, 'credit_party_info');
    }

    public function dateJournal($date, $partyInfo)
    {
        $count = 0;
        $journals = Journal::whereDate('date', $date)->where('party_info_id', $partyInfo->id)->get();
        foreach ($journals as $j) {
            $count = $count + $j->records->count();
        }

        return $count;
    }

    // work by mominul
    public function voucher_type()
    {
        return $this->hasOne(DebitCreditVoucher::class, 'journal_id');
    }

    public function party()
    {
        return $this->belongsTo(PartyInfo::class, 'party_info_id');
    }
    public function invoice()
    {
        return $this->belongsTo(JobProjectInvoice::class, 'invoice_id');
    }
    public function receipt_voucher()
    {
        return $this->belongsTo(ReceiptVoucher::class, 'receipt_id');
    }
    public function charity()
    {
        return $this->belongsTo(CharityConllection::class, 'cahrity_id');
    }


    public static function student_opening_balance($student, $from_date)
    {
        $party = PartyInfo::where('student_id', $student)->first();

        $cr = JournalRecord::where('party_info_id', $party->id)->where('journal_date', '<', $from_date)->where('account_head_id', '26')->where('transaction_type', 'CR')->sum('amount');
        $dr = JournalRecord::where('party_info_id', $party->id)->where('journal_date', '<', $from_date)->where('account_head_id', '26')->where('transaction_type', 'DR')->sum('amount');
        $cr2 = JournalRecord::where('party_info_id', $party->id)->where('journal_date', '<', $from_date)->where('account_head_id', '27')->where('transaction_type', 'CR')->sum('amount');
        $dr2 = JournalRecord::where('party_info_id', $party->id)->where('journal_date', '<', $from_date)->where('account_head_id', '27')->where('transaction_type', 'DR')->sum('amount');
        $cr3 = JournalRecord::where('party_info_id', $party->id)->where('journal_date', '<', $from_date)->where('account_head_id', '853')->where('transaction_type', 'CR')->sum('amount');

        $dr3 = JournalRecord::where('party_info_id', $party->id)->where('journal_date', '<', $from_date)->where('account_head_id', '853')->where('transaction_type', 'DR')->sum('amount');
        $amount = $dr - $cr2 + $dr2 - $cr3 + $dr3 - $cr;
        $balance = $cr + $dr - $cr2 + $dr2 - $cr3 + $dr3;
        return $balance;
    }

    public function purchaseAmnt()
    {
        $amounts = DB::table('journal_records')
            ->leftJoin('account_heads', 'account_heads.id', '=', 'journal_records.account_head_id')
            ->where('account_heads.account_type_id', 1)
            ->where('account_heads.fld_definition', 'Sell of Asset')
            ->where('journal_records.journal_date', date('Y-m-d'))
            ->where('journal_records.journal_id', $this->id)
            ->where('journal_records.transaction_type', 'DR')
            ->sum('journal_records.total_amount');
        return $amounts;
    }

    public function journal_description($journal_id)
    {
        $journal = Journal::find($journal_id);
        if (!$journal) {
            return ['name' => 'Journal Not Found', 'type' => 'unknown', 'id' => null, 'valid' => false];
        }

        $invoice = JobProjectInvoice::find($journal->invoice_id);
        if ($invoice) {
            if ($invoice->retention_invoice == 1) {
                return ['name' => 'Retention Release Invoice Amount' . $invoice->budget . ',' . $invoice->invoice_no, 'type' => 'invoice', 'id' => $invoice->id, 'valid' => true];
            } else {
                return ['name' => 'By Invoice ' . $invoice->invoice_no, 'type' => 'invoice', 'id' => $invoice->id, 'valid' => true];
            }
        }

        $receipt = Receipt::find($journal->receipt_id);
        if ($receipt) {
            return ['name' => 'By Receipt ' . $receipt->receipt_no, 'type' => 'receipt', 'id' => $receipt->id, 'valid' => false];
        }

        $payment = Payment::find($journal->payment_id);
        if ($payment) {
            return ['name' => 'By Payment ' . $payment->payment_no, 'type' => 'payment', 'id' => $payment->id, 'valid' => false];
        }

        $purchase = PurchaseExpense::find($journal->purchase_expense_id);
        if ($purchase) {
            return ['name' => 'By Purchase ' . $purchase->purchase_no, 'type' => 'purchase', 'id' => $purchase->id, 'valid' => true];
        }

        $fund_allocation = FundAllocation::find($journal->fund_allocation_id);
        if ($fund_allocation) {
            return ['name' => 'By Fund Allocation: ' . $fund_allocation->fromAccount->title . ' To ' . $fund_allocation->toAccount->title, 'type' => 'fund_allocation', 'id' => $fund_allocation->id, 'valid' => true];
        }

        // $fund_add = FundAdd::find($journal->fund_add_id);
        // if ($fund_add) {
        //     return ['name' => 'By Fund Add ', 'valid' => true];
        // }

        return ['name' => 'By Journal ' . $journal->journal_no, 'type' => 'journal', 'id' => $journal->id, 'valid' => true];
    }

    public function party_journal_description($journal_id)
    {
        $journal = Journal::find($journal_id);

        if (!$journal) {
            return ['name' => 'Journal Not Found', 'type' => 'unknown', 'id' => null, 'valid' => false, 'tasks' => null];
        }



        // $job_project=JobProject::find($journal->job_project_id);
        // if($job_project)
        // {
        //     return ['name' => 'By Project ' . $job_project->project_code, 'type' => 'project', 'id' => $job_project->id, 'valid' => true,'tasks'=>null];

        // }

        $invoice = JobProjectInvoice::find($journal->invoice_id);
        if ($invoice) {
            $text = '';
            foreach ($invoice->items($invoice->invoice_no) as $task) {
                $text = $text . ' ' . $task->task_name;
            }
            if ($invoice->retention_invoice == 1) {
                return ['name' => 'Account Receivable, Retention Release Invoice Amount - ' . $invoice->budget . ', ' . $invoice->invoice_no,  'type' => 'invoice', 'id' => $invoice->id, 'valid' => true, 'tasks' => $text];
            } else {
                return ['name' => 'Account Receivable, By Invoice ' . $invoice->invoice_no, 'type' => 'invoice', 'id' => $invoice->id, 'valid' => true, 'tasks' => $text];
            }
        }

        $receipt = Receipt::find($journal->receipt_id);
        if ($receipt) {
            if ($receipt->type == 'due') {
                $invoice_list = [];
                $receipt_inv = ReceiptSale::where('payment_id', $receipt->id)->get();
                foreach ($receipt_inv as $inv) {
                    $invoice = JobProjectInvoice::find($inv->sale_id);
                    if ($invoice) {
                        $invoice_list[] = $invoice->invoice_no;
                    }
                }
                $invoice_numbers = implode(', ', $invoice_list);
                if ($receipt->pay_mode == 'Advance') {
                    return [
                        'name' => 'Advance Adjustment ' . $receipt->receipt_no . ', Towards ' . $invoice_numbers,
                        'type' => 'invoice',
                        'id' => $receipt->id,
                        'valid' => true,
                        'tasks' => null,
                    ];
                } else {
                    return [
                        'name' => 'By Received ' . $receipt->receipt_no . ', Towards ' . $invoice_numbers,
                        'type' => 'invoice',
                        'id' => $receipt->id,
                        'valid' => true,
                        'tasks' => null,
                    ];
                }
            } else {
                return ['name' => 'By Received ' . $receipt->receipt_no . ', Towards Advance', 'type' => 'receipt', 'id' => $receipt->id, 'valid' => false, 'tasks' => null];
            }
        }

        $payment = Payment::find($journal->payment_id);
        if ($payment) {
            return ['name' => 'By Payment ' . $payment->payment_no, 'type' => 'payment', 'id' => $payment->id, 'valid' => false, 'tasks' => null];
        }

        $purchase = PurchaseExpense::find($journal->purchase_expense_id);
        if ($purchase) {
            $text = '';
            foreach ($purchase->items as $task) {
                $text = $text . ' ' . $task->item_description;
            }
            return ['name' => 'By Purchase ' . $purchase->purchase_no, 'type' => 'purchase', 'id' => $purchase->id, 'valid' => true, 'tasks' => $text];
        }

        $fund_allocation = FundAllocation::find($journal->fund_allocation_id);
        if ($fund_allocation) {
            return ['name' => 'By Fund Allocation: ' . $fund_allocation->fromAccount->title . ' To ' . $fund_allocation->toAccount->title, 'type' => 'fund_allocation', 'id' => $fund_allocation->id, 'valid' => true, 'tasks' => null];
        }

        // $fund_add = FundAdd::find($journal->fund_add_id);
        // if ($fund_add) {
        //     return ['name' => 'By Fund Add ', 'valid' => true,'tasks'=>null];
        // }

        return ['name' => 'By Journal ' . $journal->journal_no, 'type' => 'journal', 'id' => $journal->id, 'valid' => true, 'tasks' => null];
    }
    public function documents()
    {
        return $this->hasMany(JournalEntryDocument::class, 'journal_no', 'journal_no');
    }
    
    public function getIsDeletableAttribute()
    {
        return !(
            $this->invoice_id ||
            $this->receipt_id ||
            $this->payment_id ||
            $this->purchase_id ||
            $this->fund_allocation_id ||
            $this->purchase_expense_id ||
            $this->purchase_return_id ||
            $this->cahrity_id ||
            $this->donation_id ||
            $this->applicatio_fee_id

        );
    }
}

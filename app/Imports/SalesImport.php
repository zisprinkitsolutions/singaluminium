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
use App\JobProjectTask;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DateTime;
use Illuminate\Support\Facades\DB;

class SalesImport implements ToCollection, WithHeadingRow
{

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

    private $rowNumber = 1;
    private $skippedRows = [];

    public function collection(\Illuminate\Support\Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // +2 because header is row 1
            $mandatoryFields = [
                'date',
                'invoice_no',
                'retention_invoice',
                'project_no',
                'description',
                'net_amount',
                'vat',
                'total_gross_amount',
                'trn',
            ];

            $missingFields = [];
            foreach ($mandatoryFields as $field) {
                if (!isset($row[$field]) || trim($row[$field]) === '') {
                    // dd($field);
                    $missingFields[] = $field;
                }
            }

            if (!empty($missingFields)) {
                $message = "Skipping Invoice : {$row['project']}, Row: {$rowNum}, Missing Fields: " . implode(', ', $missingFields);
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
        $party = PartyInfo::whereRaw('LOWER(?) LIKE CONCAT("%", LOWER(pi_name), "%")', [trim($row['party_name'])])->where('pi_type', 'Customer')->first();
        // dd($party);
        if(!$party){
            $latest = PartyInfo::withTrashed()->orderBy('id','DESC')->first();
            if ($latest) {
                $pi_code=preg_replace('/^PI-/', '', $latest->pi_code );
                ++$pi_code;
            } else {
                $pi_code = 1;
            }
            if($pi_code<10){
                $cc="PI-000".$pi_code;
            }elseif($pi_code<100){
                $cc="PI-00".$pi_code;
            }elseif($pi_code<1000){
                $cc="PI-0".$pi_code;
            }else{
                $cc="PI-".$pi_code;
            }
            $party=new PartyInfo;
            $party->fill([
                'pi_code'=>$cc,
                'pi_name'=>$row['party_name'],
                'pi_type'=>'Customer'
            ]);
            $party->save();
        }
        $project = JobProject::where('project_no', $row['project_no'])->first();
        if(!$project){
            if (gettype($row['date']) == 'integer' || gettype($row['date']) == 'double') {
                $excel_date = $row['date']; //here is that value 41621 or 41631
                $unix_date = ($excel_date - 25569) * 86400;
                $excel_date = 25569 + ($unix_date / 86400);
                $unix_date = ($excel_date - 25569) * 86400;
                $date1 = gmdate("Y-m-d", $unix_date);
            } else {
                $date = DateTime::createFromFormat('d/m/Y', $row['date']);;
                $date1 = $date->format('Y-m-d');
            }
            $project = new JobProject;
            $project->fill([
                'project_name'=>$row['project'],
                'project_no'=>$row['project_no'],
                'customer_id'=>$party->id,
                'date'=>$date1,
                'budget'=>$row['net_amount'],
                'total_budget'=>$row['net_amount'],
            ]);
            $project->save();
            $project_item = new JobProjectTask;
            $project_item->job_project_id=$project->id;
            $project_item->task_name=$row['project'];
            $project_item->qty=1;
            $project_item->sqm=1;
            $project_item->rate=$row['net_amount'];
            $project_item->total=$row['net_amount'];
            $project_item->save();
        }
        $cinv=JobProjectInvoice::where('invoice_no',$row['invoice_no'])->first();
        if(!$cinv)
        {
            if ($project) {
                $numericFields = [
                    'net_amount' => $row['net_amount'] ?? 0,
                    'vat' => $row['vat'] ?? 0,
                    'total_gross_amount' => $row['total_gross_amount'] ?? 0,
                    'retention_amount' => $row['retention_amount'] ?? 0,
                ];

                foreach ($numericFields as $field => $value) {
                    if (!is_numeric($value)) {
                        $message = "Skipping Invoice : {$row['project']} (Invalid {$field} = {$value})";
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

                // $limit =  (strtolower($row['retention_invoice']) === 'yes') ? $project->invoices->sum('retention_amount') : ( $project->budget - $project->invoices->sum('retention_amount')-$project->invoices->sum('budget'));
                // $limitCheck = (strtolower($row['retention_invoice']) === 'yes') ? $row['net_amount']:($row['net_amount']+$row['retention_amount']);
                // if ($limit >= $limitCheck) {
                if(1==1){
                    $invoice = JobProjectInvoice::create([
                        'date'               => $date1,
                        'invoice_no'         => $row['invoice_no'],
                        'retention_invoice'  => (strtolower($row['retention_invoice']) === 'yes') ? 1 : 0,
                        'job_project_id'            => $project->id,
                        'invoice_from'      => 'Sales',
                        'customer_id'         => $party->id,
                        'compnay_id' =>  $project->compnay_id,
                        'budget' => $row['net_amount'],
                        'vat' => $row['vat'],
                        'total_budget' => $row['total_gross_amount'],
                        'retention_amount'   => (strtolower($row['retention_invoice']) === 'yes') ? ($row['net_amount'] * (-1)) : $row['retention_amount'],
                        'due_amount' => $row['net_amount'] + $row['vat'],
                        'paid_amount' => 0,
                        'advance_paid_amount' => 0,
                        'invoice_type' => 'Tax Invoice',
                        'pay_mode' => 'Credit',
                        'narration' => 'N/A',
                        'trn'=>$row['trn'],
                    ]);

                    JobProjectInvoiceTask::create([
                        'task_name' =>  $row['description'],
                        'item_description' =>  $row['description'],
                        'qty' => 1,
                        'unit' => 'N/A',
                        'rate' => $row['net_amount'],
                        'budget' => $row['net_amount'],
                        'total_budget' => $row['total_gross_amount'] - $row['retention_amount'],
                        'vat_id' => $row['total_gross_amount'] - $row['retention_amount'] > $row['net_amount'] ? 1 : 3,
                        'invoice_id' => $invoice->id,
                        'paid_amount' => 0,
                        'due_amount' => $row['total_gross_amount']
                    ]);

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
                    $journal->vat_amount        =  $invoice->vat;
                    $journal->total_amount      = $invoice->budget;
                    $journal->gst_subtotal = 0;
                    $journal->narration         =  $invoice->narration;
                    $journal->compnay_id         =  $project->compnay_id;
                    $journal->approved_by = $invoice->approved_by;
                    $journal->save();


                    //journal record
                    $head = (strtolower($row['retention_invoice']) === 'yes') ? 1759 : 7;
                    $ac_head = AccountHead::find($head);
                    $jl_record = new JournalRecord();
                    $jl_record->journal_id          = $journal->id;
                    $jl_record->project_details_id  = $journal->project_id;
                    $jl_record->cost_center_id      = $journal->cost_center_id;
                    $jl_record->party_info_id       =  $journal->party_info_id;
                    $jl_record->journal_no          =  $journal->journal_no;
                    $jl_record->account_head_id     = $ac_head->id;
                    $jl_record->master_account_id   = $ac_head->master_account_id;
                    $jl_record->account_head        = $ac_head->fld_ac_head;
                    $jl_record->amount              = $invoice->budget + ((strtolower($row['retention_invoice']) === 'yes') ? 0 : $invoice->retention_amount);
                    $jl_record->total_amount        = $jl_record->amount;
                    $jl_record->vat_rate_id         = 0;
                    $jl_record->invoice_no          = 0;
                    $jl_record->transaction_type    = 'CR';
                    $jl_record->journal_date        =  $journal->date;
                    $jl_record->is_main_head        = 1;
                    $jl_record->account_type_id     = $ac_head->account_type_id;
                    $jl_record->job_project_id          = $invoice->job_project_id;
                    $jl_record->compnay_id         =  $project->compnay_id;
                    $jl_record->save();
                    //end journal record

                    //vat journal
                    if ($invoice->vat > 0) {
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
                        $jl_record->amount              =  $invoice->vat;
                        $jl_record->invoice_no              = 'N/A';
                        $jl_record->total_amount        =  $invoice->vat;
                        $jl_record->vat_rate_id         = 0;
                        $jl_record->transaction_type    = 'CR';
                        $jl_record->journal_date        = $journal->date;
                        $jl_record->account_type_id = $vat_ac_head->account_type_id;
                        $jl_record->job_project_id          = $invoice->job_project_id;
                        $jl_record->compnay_id         =  $project->compnay_id;
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
                        $jl_record->job_project_id          = $invoice->job_project_id;
                        $jl_record->compnay_id         =  $project->compnay_id;

                        $jl_record->is_main_head        = 0;
                        $jl_record->save();
                    }
                    // dd($invoice);


                    if ((strtolower($row['retention_invoice']) === 'yes')) {
                        $project->retention_amount = $project->retention_amount - $invoice->budget;
                        $project->save();
                    } else {
                        $project->retention_amount = $project->retention_amount +  $row['retention_amount'];
                        $project->save();

                        if ($row['retention_amount'] > 0) {
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
                            $jl_record->amount              = $row['retention_amount'];
                            $jl_record->total_amount        = $row['retention_amount'];
                            $jl_record->vat_rate_id         = 0;
                            $jl_record->transaction_type    = 'DR';
                            $jl_record->journal_date        = $journal->date;
                            $jl_record->invoice_no          = 'N/A';
                            $jl_record->account_type_id     = $ac_head->account_type_id;
                            $jl_record->is_main_head        = 0;
                            $jl_record->job_project_id          = $invoice->job_project_id;
                            $jl_record->compnay_id         =  $project->compnay_id;
                            $jl_record->save();
                        }
                    }
                }
                else
                {
                    $message = "Skipping Invoice : (Amount Exceed) {$row['project']}, INV: {$row['invoice_no']}";
                if (!in_array($message, $this->skippedRows)) {
                    $this->skippedRows[] = $message;
                }
                }
            } else {
                $message = "Skipping Invoice : {$row['project']}, INV: {$row['invoice_no']}";
                if (!in_array($message, $this->skippedRows)) {
                    $this->skippedRows[] = $message;
                }
            }
        } else {
            $message = "Skipping Invoice : {$row['project']}, INV: {$row['invoice_no']} Already exist";
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

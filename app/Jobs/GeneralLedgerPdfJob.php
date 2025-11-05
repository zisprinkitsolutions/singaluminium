<?php

namespace App\Jobs;

use App\AccountSubHead;
use App\Models\AccountHead;
use App\Notifications\DownloadCompleteNotification;
use App\Office;
use App\Subsidiary;
use App\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Faker\Provider\ar_JO\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use setasign\Fpdi\Fpdi;

class GeneralLedgerPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $from;
    public $to;
    public $search;
    public $year;
    public $month;
    public $search_query;
    public $logo;
    public $user_id;
    public $company_id;
    public $image;
    public $column_name;

    public function __construct($from, $to, $year, $month, $search, $search_query, $logo, $user_id, $company_id, $image,$column_name)
    {
        $this->from = $from;
        $this->to = $to;
        $this->year = $year;
        $this->month = $month;
        $this->search = $search;
        $this->search_query = $search_query;
        $this->logo = $logo;
        $this->user_id = $user_id;
        $this->company_id = $company_id;
        $this->image = $image;
        $this->column_name = $column_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $logo = $this->logo;
        $from = $this->from;
        $to = $this->to;
        $year = $this->year;
        $month = $this->month;
        $search = $this->search;
        $search_query = $this->search_query;
        $account_head = AccountHead::find($search);
        $column_name = $this->column_name;
        $company_id = $this->company_id;
        $image = $this->image;

        $company = Subsidiary::find($company_id);

        if($company){
            $company_name = $company->company_name;
        }else{
            $company_name = 'SINGH ALUMINIUM AND STEEL';
        }

        if($column_name == 'sub_account_head_id'){
            $this->sub_account_print($company_name);
        }else{

            $chunkIndex = 1;
            try {
                $directory = storage_path('app/public/pdf');

                if (!is_dir($directory)) {
                    mkdir($directory, 0777, true);
                }

                $chunkSize = 600;

                $items = DB::table('journal_records as jr')
                ->selectRaw('
                    jr.id,
                    jr.amount,
                    jr.transaction_type,
                    jr.journal_date,
                    ah.fld_ac_head,
                    jr.journal_id,
                    ash.name as sub_head,
                    CASE
                        WHEN jpi.invoice_no IS NOT NULL THEN CONCAT("By ", jpi.date, " ", jpi.invoice_no)
                        WHEN pe.purchase_no IS NOT NULL THEN CONCAT("By Purchase", " ", pe.purchase_no)
                        WHEN p.payment_no IS NOT NULL THEN CONCAT("By payment", " ", p.payment_no)
                        WHEN r.receipt_no IS NOT NULL THEN CONCAT("By receipt_no", " ", r.receipt_no)
                        WHEN j.journal_no IS NOT NULL THEN CONCAT("By journal", " ", j.journal_no)
                        ELSE "NO Narration Available"
                    END AS narration,
                    CASE
                        WHEN pe.purchase_no IS NOT NULL THEN CONCAT("Invoice no: ", pe.invoice_no,",  ", pi.pi_name)
                        ELSE pi.pi_name
                    END AS reference
                ')
                ->join('account_heads as ah', 'ah.id', '=', 'jr.account_head_id')
                ->leftJoin('account_sub_heads as ash', 'ash.id', '=','jr.sub_account_head_id')
                ->join('party_infos as pi', 'pi.id', '=', 'jr.party_info_id')
                ->leftJoin('journals as j', 'j.id', '=', 'jr.journal_id')
                ->leftJoin('purchase_expenses as pe', 'pe.id', '=', 'j.purchase_expense_id')
                ->leftJoin('payments as p', 'p.id', '=', 'j.payment_id')
                ->leftJoin('receipts as r', 'r.id', '=', 'j.receipt_id')
                ->leftJoin('job_project_invoices as jpi', 'jpi.id', '=', 'j.invoice_id')
                ->where('jr.account_head_id', '=', $search)
                ->where('jr.compnay_id', $company_id)
                ->when($from & $to, function($query) use($from, $to) {
                    $query->whereBetween('jr.journal_date', [$from, $to]);
                })
                ->when($from && !$to, fn($query) => $query->whereDate('journal_date', $from))
                ->when(!$from && $to, fn($query) => $query->whereDate('journal_date', $to))
                ->when($year, fn($query) => $query->whereYear('journal_date',$year))
                ->when($month, fn($query) => $query->whereMonth('journal_date', $month))
                ->where(function ($query) use ($search_query) {
                    $query->whereNull($search_query)
                        ->orWhere(function ($query) use ($search_query) {
                            $query->orWhere('jpi.invoice_no', 'like', '%' . $search_query . '%')
                                ->orWhere('r.receipt_no', 'like', '%' . $search_query . '%')
                                ->orWhere('p.payment_no', 'like', '%' . $search_query . '%')
                                ->orWhere('pe.purchase_no', 'like', '%' . $search_query . '%')
                                ->orWhere('j.journal_no', 'like', '%' . $search_query . '%');
                        });
                })
                ->orderBy('journal_date', 'ASC')
                ->chunk($chunkSize, function ($items) use (&$chunkIndex, $directory,$chunkSize,$account_head,$from, $to, $year, $month, $logo, $image,$company_name) {

                    $records = [];
                    foreach($items as $item){
                        $year = date('Y', strtotime($item->journal_date));
                        $month = date('F', strtotime($item->journal_date));
                        $key = $month.'('.$year.')';

                        $data = [
                            'date' => date('d/m/Y', strtotime($item->journal_date)),
                            'narration' => $item->narration,
                            'reference' => $item->reference,
                            'dr_amount' => $item->transaction_type === 'DR' ? $item->amount : 0.00,
                            'cr_amount' => $item->transaction_type === 'CR' ? $item->amount : 0.00,
                        ];

                        if (!isset( $records[$key])) {
                            $records[$key]['items'] = [];
                            $records[$key]['month_total_dr'] = 0;
                            $records[$key]['month_total_cr'] = 0;
                        }

                        $records[$key]['items'][] = $data;
                        $records[$key]['month_total_dr'] += $data['dr_amount'];
                        $records[$key]['month_total_cr'] += $data['cr_amount'];
                    }

                    $pdfContent = view('backend.accounts-report.pdf.extended-general-ledger', compact('records', 'from', 'to','logo','year', 'account_head','company_name','image'))->render();
                    $pdf = Pdf::loadHTML($pdfContent);
                    $pdfPath = "{$directory}/leadger_temp_{$chunkIndex}.pdf";
                    $pdf->save($pdfPath);
                    $chunkIndex += 1;
                });

                $this->mergePDFs($directory,$chunkIndex);

            }catch(Exception $e) {
                Log::error('Error generating PDF: ' . $e->getMessage());
                dd($e->getMessage());
            }
        }
    }

    private function sub_account_print($company_name){
        $logo = $this->logo;
        $from = $this->dateFormat($this->from);
        $to = $this->dateFormat($this->to);
        $year = $this->year;
        $month = $this->month;
        $search = $this->search;
        $search_query = $this->search_query;
        $account_head = AccountSubHead::find($search);
        $company_id = $this->company_id;
        $image = $this->image;
        $chunkIndex = 1;

        try {
            $directory = storage_path('app/public/pdf');

            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            $chunkSize = 600;
            $item = DB::table('journal_records as jr')
            ->selectRaw('
                jr.id,
                jr.amount,
                jr.transaction_type,
                jr.journal_date,
                jr.journal_id,
                ash.name as fld_ac_head ,
                CASE
                    WHEN jpi.invoice_no IS NOT NULL THEN CONCAT("By ", jpi.invoice_type, " ", jpi.invoice_no)
                    WHEN pe.purchase_no IS NOT NULL THEN CONCAT("By Purchase", " ", pe.purchase_no)
                    WHEN p.payment_no IS NOT NULL THEN CONCAT("By payment", " ", p.payment_no)
                    WHEN r.receipt_no IS NOT NULL THEN CONCAT("By receipt_no", " ", r.receipt_no)
                    WHEN j.journal_no IS NOT NULL THEN CONCAT("By journal", " ", j.journal_no)
                    ELSE "NO Narration Available"
                END AS narration,
                CASE
                    WHEN pe.purchase_no IS NOT NULL THEN CONCAT("Invoice no: ", pe.invoice_no,",  ", pi.pi_name)
                    ELSE pi.pi_name
                END AS reference
            ')

            ->Join('account_sub_heads as ash', 'ash.id', '=','jr.sub_account_head_id')
            ->join('party_infos as pi', 'pi.id', '=', 'jr.party_info_id')
            ->leftJoin('journals as j', 'j.id', '=', 'jr.journal_id')
            ->leftJoin('purchase_expenses as pe', 'pe.id', '=', 'j.purchase_expense_id')
            ->leftJoin('payments as p', 'p.id', '=', 'j.payment_id')
            ->leftJoin('receipts as r', 'r.id', '=', 'j.receipt_id')
            ->leftJoin('job_project_invoices as jpi', 'jpi.id', '=', 'j.invoice_id')
            ->where('jr.sub_account_head_id', '=', $search)
            ->where('jr.compnay_id', $company_id)
            ->when($from & $to, function($query) use($from, $to) {
                $query->whereBetween('jr.journal_date', [$from, $to]);
            })
            ->when($from && !$to, fn($query) => $query->whereDate('journal_date', $from))
            ->when(!$from && $to, fn($query) => $query->whereDate('journal_date', $to))
            ->when($year, fn($query) => $query->whereYear('journal_date',$year))
            ->when($month, fn($query) => $query->whereMonth('journal_date', $month))
            ->where(function ($query) use ($search_query) {
                $query->whereNull($search_query)
                    ->orWhere(function ($query) use ($search_query) {
                        $query->orWhere('jpi.invoice_no', 'like', '%' . $search_query . '%')
                            ->orWhere('r.receipt_no', 'like', '%' . $search_query . '%')
                            ->orWhere('p.payment_no', 'like', '%' . $search_query . '%')
                            ->orWhere('pe.purchase_no', 'like', '%' . $search_query . '%')
                            ->orWhere('j.journal_no', 'like', '%' . $search_query . '%');
                    });
            })
            ->orderBy('journal_date', 'ASC')
            ->chunk($chunkSize, function ($items) use (&$chunkIndex, $directory,$chunkSize,$account_head,$from, $to, $year, $month, $logo, $image,$company_id,$company_name) {
                $records = [];
                foreach($items as $item){
                    $year = date('Y', strtotime($item->journal_date));
                    $month = date('F', strtotime($item->journal_date));
                    $key = $month.'('.$year.')';

                    $data = [
                        'date' => date('d/m/Y', strtotime($item->journal_date)),
                        'narration' => $item->narration,
                        'reference' => $item->reference,
                        'dr_amount' => $item->transaction_type === 'DR' ? $item->amount : 0.00,
                        'cr_amount' => $item->transaction_type === 'CR' ? $item->amount : 0.00,
                    ];

                    if (!isset( $records[$key])) {
                        $records[$key]['items'] = [];
                        $records[$key]['month_total_dr'] = 0;
                        $records[$key]['month_total_cr'] = 0;
                    }

                    $records[$key]['items'][] = $data;
                    $records[$key]['month_total_dr'] += $data['dr_amount'];
                    $records[$key]['month_total_cr'] += $data['cr_amount'];
                }

                $pdfContent = view('backend.accounts-report.pdf.extended-general-ledger', compact('records', 'from', 'to','logo','year', 'account_head','image','company_name'))->render();
                $pdf = Pdf::loadHTML($pdfContent);
                $pdfPath = "{$directory}/leadger_temp_{$chunkIndex}.pdf";
                $pdf->save($pdfPath);
                $chunkIndex += 1;
            });

            $this->mergePDFs($directory,$chunkIndex);
        }catch(Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());
            // dd($e->getMessage());
        }

    }

    private function mergePDFs($directory, $chunkIndex)
    {
        $pdfMerger = new Fpdi();

        // Step 1: Get PDF files ordered by chunk index
        $pdfFiles = [];
        for ($i = 1; $i < $chunkIndex; $i++) {
            $filePath = "{$directory}/leadger_temp_{$i}.pdf";
            if (file_exists($filePath)) {
                $pdfFiles[] = $filePath;
            } else {
                Log::warning("File not found: {$filePath}");
            }
        }

        if (!empty($pdfFiles)) {
            Log::error('No PDF files found to merge.');

            // Step 2: Calculate total number of pages
            $totalPages = 0;
            foreach ($pdfFiles as $file) {
                $totalPages += (new Fpdi())->setSourceFile($file);
            }

            $currentPage = 1;
            foreach ($pdfFiles as $file) {
                $pageCount = $pdfMerger->setSourceFile($file);

                for ($page = 1; $page <= $pageCount; $page++) {
                    $templateId = $pdfMerger->importPage($page);
                    $pdfMerger->AddPage();
                    $pdfMerger->useTemplate($templateId);

                    // Add page number at the bottom
                    $pdfMerger->SetFont('Arial', '', 10);
                    $pdfMerger->SetTextColor(0, 0, 0);
                    $pdfMerger->SetXY( -30, -0);
                    $pdfMerger->Cell(0, 10, "Page {$currentPage} of {$totalPages}", 0, 0, 'C');

                    $currentPage++;
                }
            }

            // Step 3: Save the merged file
            $file_name = "/ledger-" . rand(1, 1000) . '-' . date('d-m-Y') . '.pdf';
            $mergedPdfPath = "{$directory}{$file_name}";
            $pdfMerger->Output($mergedPdfPath, 'F');

            // Notify the user
            $user = User::find($this->user_id);
            $path = "pdf" . $file_name;
            $user->notify(new DownloadCompleteNotification("Your requested general ledger pdf file is ready to download.", $path));

            // Step 4: Clean up temporary files
            try {
                foreach ($pdfFiles as $file) {
                    unlink($file);
                }
            } catch (Exception $e) {
                Log::error('Error deleting temporary files: ' . $e->getMessage());
            }

            // Step 5: Update job status in cache
            Cache::put('job_status_' . $this->user_id, 'download-file', now()->addMinutes(10));
        }
    }

    private function dateFormat($date)
    {
        $old_date = explode('/', $date);

        $new_data = $old_date[0].'-'.$old_date[1].'-'.$old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }

}

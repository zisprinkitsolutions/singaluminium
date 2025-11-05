<?php

namespace App\Jobs;

use App\Exports\MissingInvoiceExport;
use App\JobProjectInvoice;
use App\MissingInvoice;
use App\Notifications\DownloadCompleteNotification;
use App\Office;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;
use Exception;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use setasign\Fpdi\Fpdi;

class MissingInvoiceNumberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $data;
    public $user_id;
    public $logo;
    public function __construct($data,$user_id,$logo)
    {
        $this->data = $data;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $from = $this->data['from'] ;
        $to = $this->data['to'];
        $year = $this->data['year'];
        $month = $this->data['month'];
        $search_query = $this->data['search_query'];
        $office = Office::find($this->data['office_id']);
       $file_type = $this->data['file_type'];

        DB::table('missing_invoices')->truncate();
        $chunkIndex = 1;
        try {
            DB::table('job_project_invoices as jpi');
            $invoice = DB::table('job_project_invoices as jpi')
                ->select(
                    'jpi.date',
                    'jpi.invoice_no',
                )
                ->join('party_infos as pi', 'pi.id', '=', 'jpi.customer_id')
                ->when($from && $to, function ($query) use ($from, $to) {
                    $query->whereBetween('jpi.date', [$from, $to]);
                })
                ->when($to && !$from, function ($query) use ($to) {
                    $query->where('jpi.date', $to);
                })
                ->when($from && !$to, function ($query) use ($from) {
                    $query->where('jpi.date', $from);
                })
                ->when($search_query, function ($query) use ($search_query) {
                    $query->where('jpi.invoice_no', 'LIKE', '%' . $search_query . '%');
                })->when($year, fn($query) => $query->whereYear('jpi.date', $year))
                ->when($month, fn($query) => $query->whereMonth('jpi.date', $month))
                ->where('jpi.office_id', $office->id)
                ->orderBy('jpi.date', 'asc')
                ->chunk(1000, function ($invoices) use(&$chunkIndex) {
                    $invoice_group = [];
                    foreach($invoices as $invoice) {
                        $prefix = '';
                        $number = '';
                        $suffix = '';
                        $parts = str_split($invoice->invoice_no);
                        foreach ($parts as $char) {
                            if (ctype_alpha($char) && $number === '') {
                                $prefix .= $char;
                            } elseif (ctype_digit($char)) {
                                $number .= $char;
                            } elseif (ctype_alpha($char) && $number !== '') {
                                $suffix .= $char;
                            }
                        }

                        $data = [
                            'date' => $invoice->date,
                            'invoice_no' => $invoice->invoice_no,
                            'prefix' => $prefix,
                            'suffix' => $suffix,
                            'number' => $number ? (int)$number : null,
                            'original_number' => $number,
                        ];

                        $data_prefix = $data['prefix'];

                        if($data_prefix == 'W' || $data_prefix =='WW'){
                            $data_number = $data['original_number'];
                            if(strlen($data_number) > 5){
                                $new_number = substr($data_number,$data_prefix=='W'?4:5);
                                $data['number'] = (int) $new_number;
                            }
                        }

                        $invoice_group[] = $data;
                    }

                    usort( $invoice_group, function ($a, $b) {
                        return $a['number'] <=> $b['number'];
                    });

                    foreach($invoice_group as $key => $invoice){
                        $n = count($invoice_group);
                        if ($key < $n - 1) {
                            $next_invoice = $invoice_group[$key + 1];

                            if (!$next_invoice['prefix'] && !$next_invoice['suffix']) {
                                $second_prefix = 'number';
                            }else{
                                $second_prefix = $next_invoice['prefix'] ?: $next_invoice['suffix'];
                            }

                            if (!$invoice['prefix'] && !$invoice['suffix']) {
                                $first_prefix  = 'number';
                            } else {
                                $first_prefix = $invoice['prefix'] ?: $invoice['suffix'];
                            }
                            if (($first_prefix == $second_prefix || ($invoice['number'] + 1) != $next_invoice['number'])) {
                                $missing_count = abs($next_invoice['number'] - $invoice['number']);
                                for ($i = $invoice['number'] + 1; $i < $next_invoice['number']; $i++) {
                                    $missing_date = $invoice['date'] === $next_invoice['date']
                                    ? date('d/m/Y', strtotime($invoice['date']))
                                    : date('d/m/Y', strtotime($invoice['date'])) . ' - ' . date('d/m/Y', strtotime($next_invoice['date']));

                                    $missing = $invoice['prefix'].$i.$invoice['suffix'];
                                    $origin_missing = $invoice['prefix'].($invoice['original_number'] + 1).$invoice['suffix'];

                                    $exists = JobProjectInvoice::where('invoice_no', $missing)->exists() ||
                                        MissingInvoice::where('previus_invoice', $missing)->orWhere('invoice_number',$origin_missing)->exists();

                                    if(!$exists && $i != $next_invoice['number']){
                                        $exists = JobProjectInvoice::select('invoice_no')->where('invoice_no','like',  ($invoice['number'] + 1). '%')->orderBy('invoice_no','desc')->first();
                                    }

                                    if($missing_count < 30){
                                        if (!$exists){
                                            MissingInvoice::create([
                                                'invoice_number' =>  $invoice['prefix'].($invoice['original_number'] + 1).$invoice['suffix'],
                                                'previus_invoice' =>  $missing,
                                                'next_invoice' =>  $i,
                                                'date' => date('Y-m-d'),
                                                'missing_date' =>  $missing_date,
                                                'page_number' => $chunkIndex,
                                            ]);
                                        }

                                    }
                                }
                            }
                        }
                    }
                });

        }catch(Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());
                dd($e->getMessage());
        }

        if ($file_type == 'excel') {
            $this->makeExcel();
        } else {
            $this->makePdf();
        }
    }

    public function makeExcel(){
        $name = 'missing-invoice-'.rand(1,100).date('d-m-Y').'.'.'xlsx';
        $directory = '/excel/'. $name;
        $missing_invoices = [];
        DB::table('missing_invoices')->where('date', date('Y-m-d'))
        ->orderBy('created_at', 'asc')
        ->chunk(1000, function($invoices) use(&$missing_invoices){
            foreach($invoices as $invoice){
                $missing_invoices[]  = $invoice;
            }
        });

        if(count($missing_invoices) <= 0){
            return;
        }

        Excel::store(new MissingInvoiceExport($missing_invoices), $directory,'public');
        $user = User::find($this->user_id);
        $user->notify(new DownloadCompleteNotification('Your requested missing invoice excel is ready to download',$directory));
        Cache::put('job_status_' . $this->user_id, 'download-file', now()->addMinutes(10));
    }

    public function makePdf(){
        $from = $this->data['from'] ;
        $to = $this->data['to'];
        $year = $this->data['year'];
        $month = $this->data['month'];
        $logo = $this->logo;
        $office_logo = $this->data['office_logo'];
        $office = Office::find($this->data['office_id']);

        $directory = storage_path('app/public/pdf');
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $chunkIndex = 1;
        DB::table('missing_invoices')->where('date', date('Y-m-d'))
        ->orderBy('created_at', 'asc')
        ->chunk(100, function($missing_invoices) use(&$chunkIndex, $directory,$year,$month,$from,$to,$logo, $office, $office_logo){
            $pdfContent = view('backend.accounts-report.pdf.missing_invoice_pdf', compact('missing_invoices','year','month','from','to','logo','office_logo', 'office'))->render();
            $pdf = Pdf::loadHTML($pdfContent);
            $pdfPath = "{$directory}/missing_invoice-report-".$chunkIndex.'.'."pdf";
            $pdf->save($pdfPath);
            $chunkIndex += 1;
        });

        $this->mergePDFs($directory, $chunkIndex);
    }

    private function mergePDFs($directory, $chunkIndex)
    {
        $pdfMerger = new Fpdi();

        // Step 1: Get PDF files ordered by chunk index
        $pdfFiles = [];
        for ($i = 1; $i <= $chunkIndex; $i++) {
            $filePath = "{$directory}/missing_invoice-report-{$i}.pdf";
            if (file_exists($filePath)) {
                $pdfFiles[] = $filePath;
            } else {
                Log::warning("File not found: {$filePath}");
            }
        }

        if (empty($pdfFiles)) {
            Log::error('No PDF files found to merge.');
            return;
        }

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
        $file_name = "/missing-invoice-" . rand(1, 1000) . '-' . date('d-m-Y') . '.pdf';
        $mergedPdfPath = "{$directory}{$file_name}";
        $pdfMerger->Output($mergedPdfPath, 'F');

        // Notify the user
        $user = User::find($this->user_id);
        $path = "pdf" . $file_name;
        $user->notify(new DownloadCompleteNotification("Your minssing invoice pdf file is ready to download.", $path));

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

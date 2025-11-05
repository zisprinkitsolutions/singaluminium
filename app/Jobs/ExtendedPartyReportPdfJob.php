<?php

namespace App\Jobs;

use App\JournalRecord;
use App\Notifications\DownloadCompleteNotification;
use App\Office;
use App\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use setasign\Fpdi\Fpdi;

class ExtendedPartyReportPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $logo;
    public $month;
    public $year;
    public $from;
    public $to;
    public $partyId;
    public $partyType;
    public $user_id;
    public $company_id;
    public $image;

    public function __construct($month, $year, $from, $to, $partyId, $partyType, $logo, $user_id, $company_id,$image)
    {
        $this->logo = $logo;
        $this->month = $month;
        $this->year = $year;
        $this->from = $from;
        $this->to = $to;
        $this->partyId = $partyId;
        $this->partyType = $partyType;
        $this->user_id = $user_id;
        $this->company_id = $company_id;
        $this->image = $image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $from = $this->from;
        $to = $this->to;
        $month = $this->month;
        $year = $this->year;
        $party_id = $this->partyId;
        $party_type = $this->partyType;
        $company_id = $this->company_id;
        $image = $this->image;

        try {
            $directory = storage_path('app/public/pdf');
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            $logo = $this->logo;

            $chunkSize = 100;
            $index = 1;
            $parties = DB::table('party_infos as pi')
            ->join('journal_records as jr', 'jr.party_info_id', '=', 'pi.id')
            ->select(
                'pi.id',
                'pi.pi_name',
                'pi.pi_code',
                'pi.pi_type',
                DB::raw("SUM(CASE WHEN jr.transaction_type = 'DR' THEN jr.amount ELSE 0 END) AS dr_amount"),
                DB::raw("SUM(CASE WHEN jr.transaction_type = 'CR' THEN jr.amount ELSE 0 END) AS cr_amount")
            )
            ->whereIn('account_head_id', [3, 5])
            ->where('jr.compnay_id', $company_id)
            ->when($to && $from, function ($query) use ($to, $from) {
                return $query->whereBetween('jr.journal_date', [$from, $to]);
            })
            ->when($to && !$from, function ($query) use ($to) {
                return $query->where('jr.journal_date', $to);
            })
            ->when($from && !$to, function ($query) use ($from) {
                return $query->where('jr.journal_date', $from);
            })
            ->when($year, function ($query) use ($year) {
                return $query->whereYear('jr.journal_date', $year);
            })
            ->when($month, function ($query) use ($month) {
                return $query->whereMonth('jr.journal_date', $month);
            })
            ->when($party_id, function ($query) use ($party_id) {
                return $query->where('pi.id', $party_id);
            })
            ->when($party_type && $party_type != 'all', function ($query) use ($party_type) {
                return $query->where('pi.pi_type', $party_type);
            })
            ->groupBy('pi.id', 'pi.pi_name', 'pi.pi_code', 'pi.pi_type')
            ->orderBy('pi.pi_name', 'ASC')
            ->chunk($chunkSize, function ($parties) use ($from, &$index, $to, $month, $year,$directory, $logo, $company_id, $image) {
                $pdfContent = view('backend.accounts-report.pdf.party_report', compact('parties','year','month','from','to','logo','index', 'company_id','image'))->render();
                $pdf = Pdf::loadHTML($pdfContent);
                $pdfPath = "{$directory}/temp_party_report-".$index.'.'."pdf";
                $pdf->save($pdfPath);
                $index += 1;
            });

            $this->mergePDFs($directory);
        }catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    private function mergePDFs($directory)
    {
        $pdfMerger = new Fpdi();

        // Get all PDF files in the directory
        $pdfFiles = glob("{$directory}/temp_party_report*.pdf");

        $totalPages = 0;

        // First loop: Count total pages
        foreach ($pdfFiles as $file) {
            $totalPages += $pdfMerger->setSourceFile($file);
        }

        // Reset FPDI instance for merging with page numbers
        $pdfMerger = new Fpdi();

        $currentPage = 1;

        // Second loop: Add pages and add page numbers
        foreach ($pdfFiles as $file) {
            $pageCount = $pdfMerger->setSourceFile($file);

            for ($page = 1; $page <= $pageCount; $page++) {
                $templateId = $pdfMerger->importPage($page);
                $pdfMerger->AddPage();
                $pdfMerger->useTemplate($templateId);

                // Add page number
                $pdfMerger->SetFont('Helvetica', '', 10); // Set font for the page number
                $pdfMerger->SetTextColor(0, 0, 0); // Set text color (black)

                // Set position for the page number (bottom-right corner)
                $pageWidth = $pdfMerger->GetPageWidth();
                $pageHeight = $pdfMerger->GetPageHeight();
                $pdfMerger->SetXY($pageWidth - 40, $pageHeight - 35);

                // Add page number text
                $pdfMerger->Cell(0, 10, "Page {$currentPage} of {$totalPages}", 0, 0, 'R');
                $currentPage++;
            }
        }

        // Save merged PDF
        $file_name = "/party_report-" . rand(1, 100) . '-' . date('d-m-Y') . '.pdf';
        $mergedPdfPath = "{$directory}" . $file_name;
        $pdfMerger->Output($mergedPdfPath, 'F');

        // Notify user
        $user = User::find($this->user_id);
        $user->notify(new DownloadCompleteNotification("Your requested party ledger pdf file is ready to download." , '/pdf' . $file_name));

        // Remove temporary files
        foreach ($pdfFiles as $file) {
            unlink($file);
        }

        Cache::put('job_status_' . $this->user_id, 'download-file', now()->addMinutes(10));
    }

}

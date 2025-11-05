<?php

namespace App\Jobs;

use App\Imports\ExtendPartyLedger;
use App\JournalRecord;
use App\User;
use App\Notifications\DownloadCompleteNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class ExportPartyLedgerReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $filePath;
    public $month;
    public $year;
    public $from;
    public $to;
    public $partyId;
    public $partyType;
    public $user_id;
    public $company_id;

    public function __construct($filePath, $month, $year, $from, $to, $partyId, $partyType, $user_id, $company_id)
    {
        $this->filePath = $filePath;
        $this->month = $month;
        $this->year = $year;
        $this->from = $from;
        $this->to = $to;
        $this->partyId = $partyId;
        $this->partyType = $partyType;
        $this->user_id = $user_id;
        $this->company_id = $company_id;
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

        $parties = DB::table('party_infos as pi')
            ->select(
                'pi.id',
                'pi.pi_name',
                'pi.pi_code',
                'pi.pi_type',
                DB::raw("SUM(CASE WHEN jr.transaction_type = 'DR' THEN jr.amount ELSE 0 END) AS dr_amount"),
                DB::raw("SUM(CASE WHEN jr.transaction_type = 'CR' THEN jr.amount ELSE 0 END) AS cr_amount")
            )
            ->join('journal_records as jr', 'jr.party_info_id', '=', 'pi.id')
            ->where('jr.compnay_id',$company_id)
            ->whereIn('jr.account_head_id', [3,5])
            ->when($from & $to, function($query) use($from, $to) {
                $query->whereBetween('jr.journal_date', [$from, $to]);
            })
            ->when($party_type && $party_type != 'all', fn($query) => $query->where('pi.pi_type', $party_type))

            ->when($from && !$to, fn($query) => $query->whereDate('journal_date', $from))
            ->when(!$from && $to, fn($query) => $query->whereDate('journal_date', $to))
            ->when($year, fn($query) => $query->whereYear('journal_date',$year))
            ->when($month, fn($query) => $query->whereMonth('journal_date', $month))
            ->when($party_id, fn($query) => $query->where('pi.id', $party_id))
            ->groupBy('pi.id', 'pi.pi_name', 'pi.pi_code', 'pi.pi_type')
            ->get()
            ->map(function($party) use ($to,$from,$month,$year,$company_id){

                $records=JournalRecord::whereIn('account_type_id',[1,2])->where('company_id', $company_id)
                ->whereNotIn('account_head_id',[19])->where('party_info_id',$party->id);
                if($month){
                    $records=$records->whereMonth('journal_date',$month);
                }
                if($year){
                    $records=$records->whereYear('journal_date',$year);
                }
                if($to && $from){
                    $records=$records->whereBetween('journal_date',[$from,$to]);

                }elseif($to){
                    $records=$records->whereBetween('journal_date',[$to,$to]);
                }elseif($from){
                    $records=$records->whereBetween('journal_date',[$from,$from]);
                }

                $records = $records->orderBy('journal_date','ASC')->select('journal_id','journal_date')->distinct()->get();
                return[
                    'pi_name' => $party->pi_name,
                    'pi_type' => $party->pi_type,
                    'pi_code' => $party->pi_code,
                    'dr_amount' => $party->dr_amount,
                    'cr_amount' => $party->cr_amount,
                    'balance' => number_format(abs($party->dr_amount - $party->cr_amount),2,'.',''),
                    'remark' => $party->dr_amount > $party->cr_amount ? 'Receivable' : 'Payable',
                    'items' => $records,
                ];
            });

        Excel::store(new ExtendPartyLedger($parties), $this->filePath, 'public');
        $user = User::find($this->user_id);
        $user->notify(new DownloadCompleteNotification('Your requested party ledger Excel file is ready to download',$this->filePath));
        Cache::put('job_status_' . $this->user_id, 'download-file', now()->addMinutes(10));
    }
}

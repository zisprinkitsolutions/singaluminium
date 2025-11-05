<?php

namespace App\Http\Controllers\backend;

use App\Exports\ExtendedGenerelLedger;
use App\Exports\GeneralLedger;
use App\Exports\SaleReportExcel;
use App\Exports\SaleReportExtendedExcel;
use App\Http\Controllers\Controller;
use App\Imports\ExtendPartyLedger;
use App\Imports\PartyLedgerImport;
use App\JournalRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Imports\TrialBalanceImport;
use App\Jobs\ExportPartyLedgerReportJob;
use App\Jobs\ExtendedPartyReportPdfJob;
use App\Jobs\GeneralLedgerPdfJob;
use App\Jobs\MissingInvoiceNumberJob;
use App\Models\AccountHead;
use App\Office;
use App\Subsidiary;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AccountReportPdfController extends Controller
{
    private function dateFormat($date)
    {
        $old_date = explode('/', $date);

        $new_data = $old_date[0].'-'.$old_date[1].'-'.$old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }

    public function generalLedgerPdf(Request $request){
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $from = $request->from ? $this->dateFormat($request->from):null;
        $to = $request->to ? $this->dateFormat($request->to):null;
        $year = $request->year;
        $search = $request->search;
        $records = $this->generalLedgerPdfData($request);
        $image = $this->convertPdfImage("img/laterhead.jpg");
        $company = Subsidiary::find($request->company_id);

        if($company){
            $company_name = $company->company_name;
            $image = $this->convertPdfImage("storage/{$company->image}");
        }else{
            $company_name = 'SINGH ALUMINIUM AND STEEL';
        }


        $logo = $this->convertPdfImage();

        $pdf = Pdf::loadView('backend.accounts-report.pdf.general-ledger', compact('records', 'from', 'to','logo','year','company_name','image', 'logo'));
        return $pdf->stream($company_name.'-general-ledger-'.time().'.'.'pdf');
    }

    public function generalLedgerExcel(Request $request){
        $records = $this->generalLedgerPdfData($request);
        $office = Office::find($request->office_id);
        return Excel::download(new GeneralLedger($records),$office->name."-general-ledger".'-'.date('d-m-Y').'.'.'xlsx');
    }

    private function generalLedgerPdfData($request){
        $from = $request->from ? $this->dateFormat($request->from) : null;
        $to = $request->to ? $this->dateFormat($request->to) : null;
        $search_year = $request->year;
        $search_month = $request->search_month;
        $search = $request->search;
        $company_id = $request->company_id > 0 ? $request->company_id : null;
        $search_query = $request->search_query;
        if($from && $to){
            $search_year = null;
        }

        $sql = "
            SELECT
                ah.fld_ac_head,
                ah.id,
                SUM(CASE
                    WHEN jr.transaction_type = 'DR' THEN amount
                    ELSE 0
                END) AS dr_amount,

                SUM(CASE
                    WHEN jr.transaction_type = 'CR' THEN amount
                    ELSE 0
                END) AS cr_amount
            FROM journal_records AS jr
            JOIN account_heads AS ah ON ah.id = jr.account_head_id
            WHERE 1 = 1
        ";
        if($company_id){
            $sql .= " AND jr.compnay_id = {$company_id}";
        }else{
            $sql .= " AND jr.compnay_id IS  NULL";
        }

        if($from && $to){
            $sql .= " AND jr.journal_date BETWEEN '{$from}' AND '{$to}'";
        }elseif($to){
            $sql .= " AND jr.journal_date = '{$to}'";
        }elseif($from){
            $sql .= " AND jr.journal_date = '{$from}'";
        }

        if($search_year && (!$from  && !$to)){
            $sql .= " AND Year(jr.journal_date) = {$search_year}";
        }

        if($search_month){
            $sql .= " AND Year(jr.journal_date) = {$search_month}";
        }

        if($search){
            $sql .= " AND jr.account_head_id = {$search}";
        }

        $sql .= "
            GROUP BY ah.fld_ac_head, ah.id
            ORDER BY ah.fld_ac_head
        ";
        $records = DB::select($sql);

        return $records;
    }

    public function extendedGeneralLedgerPdf(Request $request){
        $company_id = $request->company_id ?? null;
        $from = $request->from;
        $to = $request->to;
        $year = $request->year;
        $month = $request->month;
        $search = $request->head_id ? $request->head_id: $request->search;
        $search_query = $request->search_query;
        $logo = $this->convertPdfImage();
        $user_id = auth()->id();
        $image = $this->convertPdfImage("img/laterhead.jpg");

        if($company_id){
            $company = Subsidiary::find($company_id);
            $image = $this->convertPdfImage("storage/{$company->image}");
        }

        $column_name = $request->column_name ?? null;

        GeneralLedgerPdfJob::dispatch($from, $to, $year,$month, $search, $search_query,$logo, $user_id, $company_id, $image,$column_name);
    }

    public function extendedGeneralLedgerExcel(Request $request){

        $records = $this->extendedGeneralLedgerPdfData($request);
        $year = $request->year;
        $search = $request->head_id ? $request->head_id: $request->search;
        $selected_account_head = AccountHead::find($search);
        return Excel::download(new ExtendedGenerelLedger($records,$year,$selected_account_head),$selected_account_head->fld_ac_head .'-' .date('d-m-Y').'.'.'xlsx');
    }

    public function extendedGeneralLedgerPdfData(Request $request){
        $from = $request->from ? $this->dateFormat($request->from):null;
        $to = $request->to ? $this->dateFormat($request->to):null;
        $year = $request->year;
        $month = $request->month;
        $search = $request->head_id ? $request->head_id: $request->search;
        $search_query = $request->search_query;
        $company_id = $request->company_id ?? null;
        $order_by = 'journal_date-ASC';
        list($column, $direction) = explode('-',$order_by);

        $month_year_group = [];

        $items = DB::table('journal_records as jr')
            ->selectRaw('
                jr.id,
                jr.amount,
                jr.transaction_type,
                jr.journal_date,
                ah.fld_ac_head,
                jr.journal_id,
                CASE
                    WHEN jpi.invoice_no IS NOT NULL THEN CONCAT("By ", jpi.date, " ", jpi.invoice_no)
                    WHEN pe.purchase_no IS NOT NULL THEN CONCAT("By Purchase", " ", pe.purchase_no)
                    WHEN p.payment_no IS NOT NULL THEN CONCAT("By payment", " ", p.payment_no)
                    WHEN r.receipt_no IS NOT NULL THEN CONCAT("By receipt_no", " ", r.receipt_no)
                    WHEN j.journal_no IS NOT NULL THEN CONCAT("By journal", " ", j.journal_no)
                    ELSE "NO Narration Available"
                END AS narration,
                CASE
                    WHEN pe.purchase_no IS NOT NULL THEN CONCAT("Invoice no: ", pe.invoice_no," ", pi.pi_name)
                    ELSE pi.pi_name
                END AS reference
            ')
            ->join('account_heads as ah', 'ah.id', '=', 'jr.account_head_id')
            ->join('party_infos as pi', 'pi.id', '=', 'jr.party_info_id')
            ->leftJoin('journals as j', 'j.id', '=', 'jr.journal_id')
            ->leftJoin('purchase_expenses as pe', 'pe.id', '=', 'j.purchase_expense_id')
            ->leftJoin('payments as p', 'p.id', '=', 'j.payment_id')
            ->leftJoin('receipts as r', 'r.id', '=', 'j.receipt_id')
            ->leftJoin('job_project_invoices as jpi', 'jpi.id', '=', 'j.invoice_id')
            ->where('jr.compnay_id', $company_id)
            ->where('jr.account_head_id', '=', $search)
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
            ->orderBy($column, $direction)
            ->get();

        $items->map(function ($item) use (&$month_year_group) {
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

            if (!isset($month_year_group[$key])) {
                $month_year_group[$key]['items'] = [];
                $month_year_group[$key]['month_total_dr'] = 0;
                $month_year_group[$key]['month_total_cr'] = 0;
            }

            $month_year_group[$key]['items'][] = $data;
            $month_year_group[$key]['month_total_dr'] += $data['dr_amount'];
            $month_year_group[$key]['month_total_cr'] += $data['cr_amount'];
        });

        return $month_year_group;
    }

    public function convertPdfImage($url = 'img/zikash-logo.png'){
        if(!file_exists(public_path($url))){
            return null;
        }
        $path = public_path($url);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return  'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    public function trialBalanceExcel(Request $request){
        $date = $request->from;
        $date1 = $request->to;
        $company_id = $request->company_id ?? 0;
        $company = Subsidiary::find($request->company_id);
        $data = $this->trialBalanceData($date,$date1,$company_id);

        $company_name = $company ? $company->company_name : 'SINGH ALUMINIUM AND STEEL';
        return Excel::download(new TrialBalanceImport($data), "{$company_name}-trial-balance-".date('d').'.'.'xlsx');
    }

    public function trialBalancePdf(Request $request){
        $date = $request->from;
        $date1 = $request->to;
        $company_id  = $request->company_id ?? 0;
        $master_accounts  = $this->trialBalanceData($date,$date1,$company_id);
        $logo = $this->convertPdfImage();
        $image = $this->convertPdfImage("img/laterhead.jpg");
        $company = Subsidiary::find($company_id);
        if($company_id){
            $image = $this->convertPdfImage('storage/'.$company->image);
        }
        $company_name = $company ? $company->company_name : 'SINGH ALUMINIUM AND STEEL';
        $pdf = Pdf::loadView('backend.accounts-report.pdf.trial-balance', compact('master_accounts','logo', 'date', 'date1','image', 'company_name'));

        return $pdf->stream('trial_balance'.time().'.'.'pdf');
    }

    private function trialBalanceData($date,$date1,$company_id){
        $master_accounts = DB::table('master_accounts')->get()
        ->map(function ($masterAccount) use ($date,$date1,$company_id) {
                $sql = "
                    SELECT
                        ah.id,  -- ah is AccountHead
                        ah.fld_ac_head,           -- AccountHead name

                        SUM(CASE
                                WHEN jr.journal_date BETWEEN :from_date1 AND :to_date1 AND jr.transaction_type = 'DR'
                                THEN jr.amount
                                ELSE 0
                            END) AS total_dr_amount,
                        SUM(CASE
                                WHEN jr.journal_date BETWEEN :from_date2 AND :to_date2 AND jr.transaction_type = 'CR'
                                THEN jr.amount
                                ELSE 0
                            END) AS total_cr_amount

                    FROM
                        account_heads AS ah  -- AccountHead table is the base
                    JOIN
                        journal_records AS jr ON ah.id = jr.account_head_id
                    WHERE ah.master_account_id  = :master_account_id
                    AND jr.compnay_id <=> :company_id
                    GROUP BY
                        ah.id, ah.fld_ac_head;
                ";

                $account_heads = DB::select($sql, [
                    'from_date1' => $date,
                    'to_date1' => $date1,
                    'from_date2' => $date,
                    'to_date2' => $date1,
                    'master_account_id' => $masterAccount->id,
                    'company_id' => $company_id,
                ]);

                dd($account_heads);

            return [
                'code' => $masterAccount->mst_ac_code,
                'name' => $masterAccount->mst_ac_head,
                'account_heads' => $account_heads,
            ];
        });

        return $master_accounts;
    }

    public function party_report_pdf(Request $request){
        $company_id = $request->company_id;
        $month = $request->month;
        $year = $request->year;
        $from = $request->from;
        $to = $request->to;
        $party_id = $request->party_id;
        $party_type = $request->party_type;
        $logo = $this->convertPdfImage();
        $image = $this->convertPdfImage("img/laterhead.jpg");
        $company = Subsidiary::find($company_id);
        if($company_id){
            $image = $this->convertPdfImage('storage/'.$company->image);
        }
        $company_name = $company ? $company->company_name : 'SINGH ALUMINIUM AND STEEL';
        $user_id  = auth()->id();
        ExtendedPartyReportPdfJob::dispatch($month, $year, $from, $to, $party_id,$party_type, $logo, $user_id,$company_id, $image);
    }

    public function party_report_pdf_sql($month, $year, $from, $to, $party_id, $party_type, $company_id){
        if($month && !$year){
            $year = date('Y');
        }

        $sql = "
            SELECT
                pi.id,
                pi.pi_name,
                pi.pi_code,
                pi.pi_type,
                SUM(CASE WHEN jr.transaction_type = 'DR' THEN jr.amount ELSE 0 END) AS dr_amount,
                SUM(CASE WHEN jr.transaction_type = 'CR' THEN jr.amount ELSE 0 END) AS cr_amount

            FROM party_infos AS pi
            JOIN journal_records AS jr ON jr.party_info_id = pi.id
            WHERE account_head_id IN (3, 5)
            AND jr.compnay_id = {$company_id}
        ";
        if($to && $from){
            $sql .= " AND jr.journal_date BETWEEN {$from} AND {$to}";
        }elseif($to){
            $sql .= " AND jr.journal_date = {$to}";
        }elseif($from){
            $sql .= " AND jr.journal_date = {$from}";
        }

        if($year){
            $sql .= " AND year(jr.journal_date) = {$year}";
        }

        if($month){
            $sql .= " AND month(jr.journal_date) = {$month}";
        }

        if($party_id){
            $sql .= " AND pi.id = {$party_id}";
        }

        if($party_type && $party_type != 'all'){
            $sql .= " AND pi.pi_type = '{$party_type}'";
        }

        $sql .= "
            GROUP BY pi.id, pi.pi_name, pi.pi_code, pi.pi_type
            ORDER BY pi.pi_name ASC
        ";

        $parties = DB::select($sql);
        return $parties;
    }

    public function party_report_extend_pdf(Request $request){

        $month = $request->month;
        $year = $request->year;
        $from = $request->from? $this->dateFormat($request->from):null;
        $to = $request->to ? $this->dateFormat($request->to):null;
        $party_id = $request->party_id;
        $party_type = $request->party_type;
        $company_id = $request->company_id;
        $parties = $this->party_report_extend_pdf_sql($month, $year, $from, $to, $party_id, $party_type, $company_id);
        $logo = $this->convertPdfImage();
        $image = $this->convertPdfImage("img/laterhead.jpg");
        $company = Subsidiary::find($company_id);
        if($company_id){
            $image = $this->convertPdfImage('storage/'.$company->image);
        }
        $company_name = $company ? $company->company_name : 'SINGH ALUMINIUM AND STEEL';
        $pdf = Pdf::loadView('backend.accounts-report.pdf.party_report_extend', compact('parties','year','month','from','to','logo', 'company_name','image'));
        return $pdf->stream($company_name.'-party-report-'.time().'.'.'pdf');
    }

    private function party_report_extend_pdf_sql($month, $year, $from, $to, $party_id, $party_type,$office_id){
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
            ->whereIn('jr.account_head_id', [3,5])
            ->where('jr.office_id', $office_id)
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
            ->map(function($party) use ($to,$from,$month,$year,$office_id){
                $records=JournalRecord::whereIn('account_type_id',[1,2])->where('office_id', $office_id)
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

        return $parties;
    }

    public function party_report_excel(Request $request){
        $month = $request->month;
        $year = $request->year;
        $from = $request->from;
        $to = $request->to;
        $party_id = $request->party_id;
        $party_type = $request->party_type;
        $company_id = $request->company_id;
        $company = Subsidiary::find($company_id);
        $name = $company ? $company->company_name : 'SINGH ALUMINIUM AND STEEL';

        $parties = $this->party_report_pdf_sql($month, $year, $from, $to, $party_id, $party_type,$company_id);

        return Excel::download(new PartyLedgerImport($parties), "{$name}-party-ledger-".date('d-m-Y').'.'.'xlsx');
    }

    public function party_report_extend_excel(Request $request){
        $month = $request->month;
        $year = $request->year;
        $from = $request->from;
        $to = $request->to;
        $party_id = $request->party_id;
        $party_type = $request->party_type;
        // $parties = $this->party_report_extend_pdf_sql($month, $year, $from, $to, $party_id, $party_type);
        $name = 'extended-party-report-'.rand(1,100).date('d-m-Y').'.'.'xlsx';
        $file_path = '/party-report/'. $name;
        $user_id = auth()->id();
        $company_id = $request->company_id;

        ExportPartyLedgerReportJob::dispatch($file_path,$month, $year, $from, $to, $party_id,$party_type, $user_id, $company_id);

        // Excel::store(new ExtendPartyLedger($parties), $file_path, 'public');
        // return Excel::download(new ExtendPartyLedger($parties), 'extended-party-ledger-'.date('d-m-Y').'.'.'xlsx');
    }

    public function sale_report_pdf(Request $request){
        $from = $request->from ? $this->dateFormat($request->from) : null;
        $to = $request->to? $this->dateFormat($request->to) : null;
        $year = $request->year;
        $month = $request->month;

        $search_query = $request->search_query;

        $office = Office::find($request->office_id);

        $records = $this->sale_report_data($from, $to, $year, $month, $search_query, $office->id);
        $office_logo = $this->convertPdfImage("storage/upload/logo/{$office->logo}");
        $logo = $this->convertPdfImage();
        $pdf = Pdf::loadView('backend.accounts-report.pdf.sale_report', compact('records','year','month','from','to','logo', 'office','office_logo'));
        return $pdf->stream('sale-report'.time().'.'.'pdf');
    }

    public function sale_report_data($from, $to, $year, $month, $search_query,$office_id){

        $taxInvoices = DB::table('job_project_invoices as jpi')
            ->selectRaw('YEAR(jpi.date) AS year, MONTH(jpi.date) AS month')
            ->selectRaw('SUM(jpi.total_budget) AS total_amount')
            ->selectRaw('SUM(jpi.paid_amount) AS paid_amount')
            ->selectRaw('SUM(jpi.due_amount) AS due_amount')
            // ->when($office_id, fn($q) => $q->where('office_id', $office_id))
            ->when($year, fn($query) => $query->whereYear('date',$year))
            ->when($from && $to, fn($query) => $query->whereBetween('date', [$from, $to]))
            ->when($from && !$to, fn($query) => $query->whereDate('date', $from))
            ->when(!$from && $to, fn($query) => $query->whereDate('date', $to))
            ->when($month  && !$search_query, fn($query) => $query->whereMonth('jpi.date', $month))
            ->when($search_query, fn($query) => $query->where('invoice_no', 'like', '%'.$search_query.'%'))
            ->groupByRaw('YEAR(jpi.date), MONTH(jpi.date)')
            ->orderByRaw('YEAR(jpi.date) DESC, MONTH(jpi.date) ASC')
        ->get();

        return $taxInvoices;
    }

    public function sale_report_extend_pdf(Request $request){
        $from = $request->from ? $this->dateFormat($request->from) : null;
        $to = $request->to? $this->dateFormat($request->to) : null;
        $year = $request->year;
        $month = $request->month;
        $search_query = $request->search_query;
        $office = Office::find($request->office_id);
        $office_logo = $this->convertPdfImage("storage/upload/logo/{$office->logo}");
        $records = $this->sale_report_data($from, $to, $year, $month, $search_query,$office->id);
        $invoices = $this->sale_report_extend_data($records, $from, $to, $year, $month, $search_query, $office->id);

        $logo = $this->convertPdfImage();
        $pdf = Pdf::loadView('backend.accounts-report.pdf.sale_report_extend', compact('invoices','year','month','from','to','logo','office','office_logo'));
        return $pdf->stream('sale-report-extended'.time().'.'.'pdf');
    }

    public function sale_report_extend_data($records, $from, $to, $year, $month, $search_query,$office_id){
       $invoices = $records->map(function($item) use($from, $to, $search_query,$office_id) {
            $sql ="
                SELECT
                    jpi.date,
                    jpi.id,
                    jpi.invoice_no,
                    jpi.date,
                    pi.pi_name,
                    jpi.total_budget as total_amount,
                    jpi.paid_amount,
                    jpi.due_amount
                FROM job_project_invoices as jpi
                JOIN party_infos AS pi ON pi.pi_code = jpi.customer_id
                WHERE YEAR(jpi.date) = {$item->year}
                AND MONTH(jpi.date) = {$item->month}
            ";

            if ($to && $from) {
                $sql .= " AND jpi.date BETWEEN '{$from}' AND '{$to}'";
            } elseif ($to) {
                $sql .= " AND jpi.date = '{$to}'";
            } elseif ($from) {
                $sql .= " AND jpi.date = '{$from}'";
            }

            if ($search_query) {
                $sql .= " AND jpi.invoice_no LIKE '%{$search_query}%'";
            }

            $sql .= "
                ORDER BY jpi.date ASC
            ";
            $items = DB::select($sql);
            return [
                'year' => $item->year,
                'month_number' => $item->month,
                'month' => Carbon::createFromDate(null, $item->month)->format('F'),
                'total_amount' => $item->total_amount,
                'due_amount' => $item->due_amount,
                'paid_amount' => $item->paid_amount,
                'items' => $items
            ];
        });

        return $invoices;
    }

    public function sale_report_excel(Request $request){
        $from = $request->from ? $this->dateFormat($request->from) : null;
        $to = $request->to? $this->dateFormat($request->to) : null;
        $year = $request->year;
        $month = $request->month;
        $search_query = $request->search_query;
        $office = Office::find($request->office_id);
        $records = $this->sale_report_data($from, $to, $year, $month, $search_query,$office->id);

        return Excel::download(new SaleReportExcel($records), "{$office->name}-sale-report-".date('d-m-Y').'.'.'xlsx');
    }

    public function sale_report_extend_excel(Request $request){
        $from = $request->from ? $this->dateFormat($request->from) : null;
        $to = $request->to? $this->dateFormat($request->to) : null;
        $year = $request->year;
        $month = $request->month;
        $search_query = $request->search_query;
        $office = Office::find($request->office_id);
        $records = $this->sale_report_data($from, $to, $year, $month, $search_query,$office->id);
        $invoices = $this->sale_report_extend_data($records, $from, $to, $year, $month, $search_query, $office->id);

        return Excel::download(new SaleReportExtendedExcel($invoices), $office->name.'-extended-sale-report-'.date('d-m-Y').'.'.'xlsx');
    }

    public function downloadFile(Request $request){
        $notification_id = $request->notification_id;

        $path = $request->path;
        $path = '/public/'.$path;


        if (!Storage::exists($path)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        DB::table('notifications')
            ->where('id', $notification_id)
            ->update(['read_at' => now()]);

        return Storage::download($path);
    }

    public function checkDownloadNotification(){

        $status = Cache::get('job_status_' . auth()->id());

        if($status){
            Cache::forget('job_status_' . auth()->id());
            return view('backend.accounts-report.pdf.notifications');
        }

    }

    public function missing_invoice_number_pdf(Request $request){
        $data = $request->all();
        $office = Office::find(auth()->user()->office_id);
        $user_id = auth()->id();
        $logo = $this->convertPdfImage();
        $data['office_logo'] =  $this->convertPdfImage("storage/upload/logo/{$office->logo}");
        $data['office_id'] = $office->id;
        MissingInvoiceNumberJob::dispatch($data, $user_id,$logo);
    }
}

<?php

namespace App\Http\Controllers\backend;

use App\AccountSubHead;
use App\Exports\ExtendedReceivableExport;
use App\Exports\ReceivableExport;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\JournalRecord;
use App\Models\AccountHead;
use App\Models\FundAllocation;
use App\Models\StockTransection;
use App\PartyInfo;
use App\Payment;
use App\PaymentInvoice;
use App\PurchaseExpense;
use App\Receipt;
use App\ReceiptSale;
use App\FundAdd;
use App\JobProject;
use App\MissingInvoice;
use App\Office;
use App\PurchaseExpenseItem;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\JobProjectInvoice;
use App\NewProject;
use App\Models\Payroll\Employee;
use App\Subsidiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use Illuminate\Support\Facades\Gate;

class AccountsReportController extends Controller
{

    private function dateFormat($date)
    {
        $old_date = explode('/', $date);

        $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }

    public function new_general_ledger(Request $request)
    {

        Gate::authorize('Accounting_Reports');

        $from = $request->from ? $this->dateFormat($request->from) : null;
        $to = $request->to ? $this->dateFormat($request->to) : null;
        $search_year = $request->year ?? null;
        $search_month = $request->month;
        $search = $request->search;
        $search_query = $request->search_query;
        $order_by = null;
        $is_all = $request->all;
        $office = $request->office;
        $selected_company = new Subsidiary();
        $company_id = $request->company_id;



        if ($company_id) {
            $selected_company = Subsidiary::find($company_id);
        }

        if ($is_all) {
            $search_year = null;
        }

        if ($from && $to) {
            $search_year = null;
        }

        $office_id = $request->office_id;

        if (!$office_id) {
            $office_id = auth()->user()->office_id;
        }

        $selected_office = Office::find($office_id);

        $records = DB::table('journal_records as jr')
            ->select(
                'ah.fld_ac_head',
                'ah.id',
                DB::raw("SUM(CASE WHEN jr.transaction_type = 'DR' THEN amount ELSE 0 END) AS dr_amount"),
                DB::raw("SUM(CASE WHEN jr.transaction_type = 'CR' THEN amount ELSE 0 END) AS cr_amount")
            )
            ->join('account_heads as ah', 'ah.id', '=', 'jr.account_head_id')
            ->groupBy('ah.fld_ac_head', 'ah.id')
            ->orderBy('ah.fld_ac_head')
            ->when($company_id, function ($query) use ($company_id) {
                if ($company_id > 0) {
                    $query->where('jr.compnay_id', $company_id);
                } else {
                    $query->whereNull('compnay_id');
                }
            })
            ->where('jr.compnay_id', $company_id)
            ->when($from && $to && !$search_query, fn($query) => $query->whereBetween('journal_date', [$from, $to]))
            ->when($from && !$to  && !$search_query, fn($query) => $query->whereDate('journal_date', $from))
            ->when(!$from && $to  && !$search_query, fn($query) => $query->whereDate('journal_date', $to))
            ->when($search_year && (!$from && !$to)  && !$search_query, fn($query) => $query->whereYear('journal_date', $search_year))
            ->when($search, fn($query) => $query->where('ah.id', $search))
            ->when($search_month, fn($query) => $query->whereMonth('journal_date', $search_month))
            ->get()
            ->map(function ($head) use ($from, $to, $search_year, $search_month, $company_id) {
                $sql = "
                        SELECT
                            ash.name,
                            ash.id,
                            SUM(CASE
                                WHEN jr.transaction_type = 'DR' THEN amount
                                ELSE 0
                            END) AS dr_amount,

                            SUM(CASE
                                WHEN jr.transaction_type = 'CR' THEN amount
                                ELSE 0
                            END) AS cr_amount
                        FROM journal_records AS jr
                        JOIN account_sub_heads AS ash ON ash.id = jr.sub_account_head_id
                        WHERE ash.account_head_id = {$head->id}
                    ";
                if ($company_id > 0) {
                    $sql .= " AND jr.compnay_id = {$company_id}";
                }
                if ($company_id <= 0) {
                    $sql .= " AND jr.compnay_id IS NULL";
                }
                if ($from && $to) {
                    $sql .= " AND jr.journal_date BETWEEN '{$from}' AND '{$to}'";
                } elseif ($to) {
                    $sql .= " AND jr.journal_date = '{$to}'";
                } elseif ($from) {
                    $sql .= " AND jr.journal_date = '{$from}'";
                }

                if ($search_year && (!$from  && !$to)) {
                    $sql .= " AND Year(jr.journal_date) = {$search_year}";
                }

                if ($search_month) {
                    $sql .= " AND Month(jr.journal_date) = {$search_month}";
                }

                $sql .= "
                        GROUP BY ash.name, ash.id
                        ORDER BY ash.name
                    ";
                $items = DB::select($sql);

                return [
                    'items' => $items,
                    'fld_ac_head' => $head->fld_ac_head,
                    'id' => $head->id,
                    'dr_amount' => $head->dr_amount,
                    'cr_amount' => $head->cr_amount,
                ];
            });


        $selected_account_head = AccountHead::find($search);
        $offices = Office::orderBy('name')->get();
        $account_heads = AccountHead::orderBy('fld_ac_head', 'asc')->get();
        $companies = Subsidiary::orderBy('id', 'desc')->get();


        return view('backend.accounts-report.general-ledger.new-index', compact('records', 'from', 'to', 'search', 'search_month', 'search_year', 'order_by', 'account_heads', 'search_query', 'selected_account_head', 'offices', 'selected_office', 'companies', 'selected_company'));
    }

    public function general_ledger_yearly_details(Request $request, $id)
    {
        $column = $request->column;
        if ($column == 'sub_account_head_id') {
            $records =  $this->sub_head_details_sql($request, $id);
        } else {
            $records =  $this->head_details_sql($request, $id);
        }
        return view('backend.accounts-report.general-ledger.yearly-details', compact('records', 'column', 'id'));
    }

    private function head_details_sql(Request $request, $head_id)
    {
        $from = $request->from ? $this->dateFormat($request->from) : null;
        $to = $request->to ? $this->dateFormat($request->to) : null;
        if ($request->from_date) {
            $from = $request->from_date;
            $to = $request->to_date;
        }

        $search_year = $request->year ?? null;
        $search_month = $request->search_month ?? $request->month;
        $search = $head_id;
        $search_query = $request->search_query;

        $company_id = $request->company_id;

        $records = JournalRecord::select(
            DB::raw("YEAR(journal_records.journal_date) as year"),
            'journal_records.account_head_id',
            'account_heads.fld_ac_head',
            'account_heads.fld_ac_code',
            DB::raw("SUM(CASE WHEN transaction_type = 'DR' THEN amount ELSE 0 END) as total_dr_amount"),
            DB::raw("SUM(CASE WHEN transaction_type = 'CR' THEN amount ELSE 0 END) as total_cr_amount")
        )
            ->leftJoin(
                'account_heads',
                'account_heads.id',
                'journal_records.account_head_id'
            )
            ->groupBy(
                DB::raw("YEAR(journal_records.journal_date)"),
                'journal_records.account_head_id',
                'account_heads.fld_ac_head',
                'account_heads.fld_ac_code'
            )
            ->where('journal_records.account_head_id', $search)
            ->where('journal_records.compnay_id', $company_id)
            ->when(!$from && !$to && !$search_year, function ($query) {
                return $query->whereRaw("journal_records.journal_date >= DATE_SUB(CURRENT_DATE, INTERVAL 14 YEAR)");
            })
            ->when($from && $to && !$search_query, fn($query) => $query->whereBetween('journal_date', [$from, $to]))
            ->when($from && !$to  && !$search_query, fn($query) => $query->whereDate('journal_date', $from))
            ->when(!$from && $to  && !$search_query, fn($query) => $query->whereDate('journal_date', $to))
            ->when($search_year && (!$from && !$to)  && !$search_query, fn($query) => $query->whereYear('journal_date', $search_year))
            ->when($search_query, function ($query) use ($search_query) {
                $query->whereHas('journal', function ($q) use ($search_query) {
                    $q->where(function ($qInner) use ($search_query) {
                        $qInner->whereHas('invoice', fn($sub) => $sub->where('invoice_no', 'LIKE', '%' . $search_query . '%'))
                            ->orWhereHas('receipt', fn($sub) => $sub->where('receipt_no', 'LIKE', '%' . $search_query . '%'))
                            ->orWhereHas('payment', fn($sub) => $sub->where('payment_no', 'LIKE', '%' . $search_query . '%'))
                            ->orWhereHas('purchaseExp', fn($sub) => $sub->where('purchase_no', 'LIKE', '%' . $search_query . '%'))
                            ->orWhere('journal_no', 'LIKE', '%' . $search_query . '%');
                    });
                });
            })
            ->orderBy(DB::raw("YEAR(journal_records.journal_date)"), 'ASC')
            ->paginate(10);

        $mappedCollection = $records->getCollection()->map(function ($head) use ($from, $to, $search_query, $search_month, $search_year, $company_id) {

            $monthly_records = JournalRecord::where('account_head_id', $head->account_head_id)
                ->selectRaw("
                    DATE_FORMAT(journal_date, '%M') as month,
                    DATE_FORMAT(journal_date, '%m') as month_number,
                    SUM(CASE WHEN transaction_type = 'DR' THEN amount ELSE 0 END) as total_dr_amount,
                    SUM(CASE WHEN transaction_type = 'CR' THEN amount ELSE 0 END) as total_cr_amount
                ")
                ->whereYear('journal_date', $head->year)
                ->where('journal_records.compnay_id', $company_id)
                ->when($from && $to  && !$search_query, fn($query) => $query->whereBetween('journal_date', [$from, $to]))
                ->when($from && !$to  && !$search_query, fn($query) => $query->whereDate('journal_date', $from))
                ->when(!$from && $to  && !$search_query, fn($query) => $query->whereDate('journal_date', $to))
                ->when($search_year && (!$from && !$to)  && !$search_query, fn($query) => $query->whereYear('journal_date', $search_year))
                ->when($search_month  && !$search_query, fn($query) => $query->whereMonth('journal_date', $search_month))
                ->when($search_query, function ($query) use ($search_query) {
                    $query->whereHas('journal', function ($q) use ($search_query) {
                        $q->where(function ($qInner) use ($search_query) {
                            $qInner->whereHas('invoice', fn($sub) => $sub->where('invoice_no', 'LIKE', '%' . $search_query . '%'))
                                ->orWhereHas('receipt', fn($sub) => $sub->where('receipt_no', 'LIKE', '%' . $search_query . '%'))
                                ->orWhereHas('payment', fn($sub) => $sub->where('payment_no', 'LIKE', '%' . $search_query . '%'))
                                ->orWhereHas('purchaseExp', fn($sub) => $sub->where('purchase_no', 'LIKE', '%' . $search_query . '%'))
                                ->orWhere('journal_no', 'LIKE', '%' . $search_query . '%');
                        });
                    });
                })

                ->groupBy(DB::raw("DATE_FORMAT(journal_date, '%M'), DATE_FORMAT(journal_date, '%m')"))
                ->orderByRaw("DATE_FORMAT(journal_date, '%m') ASC")
                ->get();

            return [
                'months'  => $monthly_records,
                'year' => $head->year,
                'head_id' => $head->account_head_id,
                'fld_ac_code' => $head->fld_ac_code,
                'fld_ac_head' => $head->fld_ac_head,
                'total_dr_amount' => $head->total_dr_amount,
                'total_cr_amount' => $head->total_cr_amount,
                'balance' => $head->total_cr_amount > $head->total_dr_amount ? $head->total_cr_amount - $head->total_dr_amount :  $head->total_dr_amount - $head->total_cr_amount,
            ];
        });

        $records =  $records->setCollection($mappedCollection);

        return $records;
    }

    private function sub_head_details_sql(Request $request, $head_id)
    {
        $from = $request->from ? $this->dateFormat($request->from) : null;
        $to = $request->to ? $this->dateFormat($request->to) : null;
        if ($request->from_date) {
            $from = $request->from_date;
            $to = $request->to_date;
        }

        $search_year = $request->year ?? null;
        $search_month = $request->search_month;
        $search = $head_id;
        $search_query = $request->search_query;
        $company_id = $request->company_id ?? null;

        $records = JournalRecord::select(
            DB::raw("YEAR(journal_records.journal_date) as year"),
            'journal_records.sub_account_head_id as account_head_id',
            'account_sub_heads.name as fld_ac_head',
            DB::raw("SUM(CASE WHEN transaction_type = 'DR' THEN amount ELSE 0 END) as total_dr_amount"),
            DB::raw("SUM(CASE WHEN transaction_type = 'CR' THEN amount ELSE 0 END) as total_cr_amount")
        )
            ->leftJoin(
                'account_sub_heads',
                'account_sub_heads.id',
                'journal_records.sub_account_head_id'
            )
            ->groupBy(
                DB::raw("YEAR(journal_records.journal_date)"),
                'journal_records.sub_account_head_id',
                'account_sub_heads.name',
            )->where('journal_records.compnay_id', $company_id)
            ->where('journal_records.sub_account_head_id', $search)
            ->when(!$from && !$to && !$search_year, function ($query) {
                return $query->whereRaw("journal_records.journal_date >= DATE_SUB(CURRENT_DATE, INTERVAL 6 YEAR)");
            })

            ->when($from && $to, fn($query) => $query->whereBetween('journal_date', [$from, $to]))
            ->when($from && !$to, fn($query) => $query->whereDate('journal_date', $from))

            ->when(!$from && $to  && !$search_query, fn($query) => $query->whereDate('journal_records.journal_date', $to))
            ->when($search_year && (!$from && !$to)  && !$search_query, fn($query) => $query->whereYear('journal_date', $search_year))
            ->when($search_query, function ($query) use ($search_query) {
                $query->whereHas('journal', function ($q) use ($search_query) {
                    $q->where(function ($qInner) use ($search_query) {
                        $qInner->whereHas('invoice', fn($sub) => $sub->where('invoice_no', 'LIKE', '%' . $search_query . '%'))
                            ->orWhereHas('receipt', fn($sub) => $sub->where('receipt_no', 'LIKE', '%' . $search_query . '%'))
                            ->orWhereHas('payment', fn($sub) => $sub->where('payment_no', 'LIKE', '%' . $search_query . '%'))
                            ->orWhereHas('purchaseExp', fn($sub) => $sub->where('purchase_no', 'LIKE', '%' . $search_query . '%'))
                            ->orWhere('journal_no', 'LIKE', '%' . $search_query . '%');
                    });
                });
            })
            ->orderBy(DB::raw("YEAR(journal_records.journal_date)"), 'ASC')
            ->paginate(10);

        $mappedCollection = $records->getCollection()->map(function ($head) use ($from, $to, $search_query, $search_month, $search_year, $company_id) {

            $monthly_records = JournalRecord::where('sub_account_head_id', $head->account_head_id)
                ->selectRaw("
                    DATE_FORMAT(journal_date, '%M') as month,
                    DATE_FORMAT(journal_date, '%m') as month_number,
                    SUM(CASE WHEN transaction_type = 'DR' THEN amount ELSE 0 END) as total_dr_amount,
                    SUM(CASE WHEN transaction_type = 'CR' THEN amount ELSE 0 END) as total_cr_amount
                ")
                ->whereYear('journal_date', $head->year)
                ->where('journal_records.company_id', $company_id)
                ->when($from && $to  && !$search_query, fn($query) => $query->whereBetween('journal_date', [$from, $to]))
                ->when($from && !$to  && !$search_query, fn($query) => $query->whereDate('journal_date', $from))
                ->when(!$from && $to  && !$search_query, fn($query) => $query->whereDate('journal_date', $to))
                ->when($search_year && (!$from && !$to)  && !$search_query, fn($query) => $query->whereYear('journal_date', $search_year))
                ->when($search_month  && !$search_query, fn($query) => $query->whereMonth('journal_date', $search_month))
                ->when($search_query, function ($query) use ($search_query) {
                    $query->whereHas('journal', function ($q) use ($search_query) {
                        $q->where(function ($qInner) use ($search_query) {
                            $qInner->whereHas('invoice', fn($sub) => $sub->where('invoice_no', 'LIKE', '%' . $search_query . '%'))
                                ->orWhereHas('receipt', fn($sub) => $sub->where('receipt_no', 'LIKE', '%' . $search_query . '%'))
                                ->orWhereHas('payment', fn($sub) => $sub->where('payment_no', 'LIKE', '%' . $search_query . '%'))
                                ->orWhereHas('purchaseExp', fn($sub) => $sub->where('purchase_no', 'LIKE', '%' . $search_query . '%'))
                                ->orWhere('journal_no', 'LIKE', '%' . $search_query . '%');
                        });
                    });
                })

                ->groupBy(DB::raw("DATE_FORMAT(journal_date, '%M'), DATE_FORMAT(journal_date, '%m')"))
                ->orderByRaw("DATE_FORMAT(journal_date, '%m') ASC")
                ->get();

            return [
                'months'  => $monthly_records,
                'year' => $head->year,
                'head_id' => $head->account_head_id,
                'fld_ac_code' => isset($head->fld_ac_code) ? $head->fld_ac_code : null,
                'fld_ac_head' => $head->fld_ac_head,
                'total_dr_amount' => $head->total_dr_amount,
                'total_cr_amount' => $head->total_cr_amount,
                'balance' => $head->total_cr_amount > $head->total_dr_amount ? $head->total_cr_amount - $head->total_dr_amount :  $head->total_dr_amount - $head->total_cr_amount,
            ];
        });

        $records =  $records->setCollection($mappedCollection);
        return $records;
    }

    public function general_ledger_details(Request $request, $head_id)
    {
        $column = $request->column;
        if ($column == 'sub_account_head_id') {
            $account_head = AccountSubHead::find($head_id);
            $table = 'account_sub_heads';
            $head_name = 'name';
        } else {
            $account_head = AccountHead::find($head_id);
            $table = 'account_heads';
            $head_name = 'fld_ac_head';
        }

        $from = $request->from_date ? $this->dateFormat($request->from_date) : null;
        $to = $request->to_date ? $this->dateFormat($request->to_date) : null;
        $search_year = $request->year;
        $month_number = $request->month_number;
        $search = $request->search ?? $account_head->id;
        $search_query = $request->search_query;
        $order_by = $request->order_by;
        $company_id = $request->company_id;

        if ($company_id == 0) {
            $company_id = null;
        }

        if ($order_by) {
            list($column, $direction) = explode('-', $order_by);
        }

        $sql = "
            SELECT
                jr.id,
                jr.amount,
                jr.transaction_type,
                jr.journal_date,
                ah.`{$head_name}` AS fld_ac_head,
                jr.journal_id,
            CASE
                WHEN jpi.invoice_no IS NOT NULL THEN CONCAT('By Invoice', ' ', jpi.invoice_no)
                WHEN pe.purchase_no IS NOT NULL THEN CONCAT('By Purchase', ' ', pe.purchase_no)
                WHEN p.payment_no IS NOT NULL THEN CONCAT('By payment', ' ', p.payment_no)
                WHEN r.receipt_no IS NOT NULL THEN CONCAT('By receipt_no', ' ', r.receipt_no)
                WHEN j.journal_no IS NOT NULL THEN CONCAT('By journal', ' ', j.journal_no)
                ELSE 'NO Narration Available'
            END AS naration,

            CASE
                WHEN pe.purchase_no IS NOT NULL THEN
                    CONCAT('Invoice No: ', pe.invoice_no, pi.pi_name)
                WHEN fa.id IS NOT NULL THEN
                    CONCAT('Fund allocation: ', fa.note)
                ELSE
                    pi.pi_name

            END AS reference

            FROM journal_records as jr

            JOIN `{$table}` as ah on ah.id = jr.`{$column}`
            LEFT JOIN party_infos as pi ON pi.id = jr.party_info_id
            LEFT JOIN journals as j ON j.id = jr.journal_id
            LEFT JOIN purchase_expenses as pe ON pe.id = j.purchase_expense_id
            LEFT JOIN payments as p ON p.id  = j.payment_id
            LEFT JOIN receipts as r ON r.id = j.receipt_id
            LEFT JOIN job_project_invoices as jpi ON jpi.id = j.invoice_id
            LEFT JOIN fund_allocations as fa ON fa.id = j.fund_allocation_id

            WHERE jr.`{$column}` = {$head_id}

            AND MONTH(jr.journal_date) = {$month_number}

            AND (
                (:from_date IS NOT NULL AND :to_date IS NOT NULL AND jr.journal_date BETWEEN :from_date3 AND :to_date3)
                OR (:from_date1 IS NOT NULL AND :to_date1 IS NULL AND jr.journal_date = :from_date4)
                OR (:from_date2 IS NULL AND :to_date2 IS NOT NULL AND jr.journal_date = :to_date4)
                OR (:from_date5 IS NULL AND :to_date5 IS NULL)
            )
            AND (:year1 IS NULL OR YEAR(jr.journal_date) = :year2)
            AND (
                :search_query IS  NULL OR (
                    jpi.invoice_no LIKE CONCAT('%', :search_query1, '%') OR
                    r.receipt_no LIKE CONCAT('%', :search_query2, '%') OR
                    p.payment_no LIKE CONCAT('%', :search_query3, '%') OR
                    pe.purchase_no LIKE CONCAT('%', :search_query4, '%') OR
                    j.journal_no LIKE CONCAT('%', :search_query5, '%')
                )
            )

            AND jr.compnay_id <=> :company_id


            ORDER BY jr.journal_date ASC
        ";

        $items = DB::select($sql, [
            'from_date' => $from,
            'to_date' => $to,
            'from_date1' => $from,
            'to_date1' => $to,
            'from_date2' => $from,
            'to_date2' => $to,
            'from_date3' => $from,
            'to_date3' => $to,
            'from_date4' => $from,
            'to_date4' => $to,
            'from_date5' => $from,
            'to_date5' => $to,
            'year1' => $search_year,
            'year2' => $search_year,
            'search_query' => $search_query,
            'search_query1' => $search_query,
            'search_query2' => $search_query,
            'search_query3' => $search_query,
            'search_query4' => $search_query,
            'search_query5' => $search_query,
            'company_id' => $company_id,
        ]);
        // dd($items);
        return view('backend.accounts-report.general-ledger.new-general-ledger-details', compact('items'));
    }

    public function party_report(Request $request)
    {

        $company_id = $request->company_id ? (array) $request->company_id : [0, null];
        $ids = implode(',', $company_id);
        $selected_company = Subsidiary::whereIn('id', $company_id)->first();
        if (!$selected_company) {
            $selected_company = new Subsidiary();
        }
        $month = $request->month;
        $year = $request->year;
        $from = $request->from ? $this->dateFormat($request->from) : null;
        $to = $request->to ? $this->dateFormat($request->to) : null;
        $party_id = $request->party_id;
        $party_type = $request->party_type;
        $projects = JobProject::where(function ($query) use ($company_id) {
        $ids = array_filter($company_id, fn($id) => $id !== null && $id != 0); // valid IDs
        if ($ids) {
            $query->whereIn('compnay_id', $ids);
        }
        if (in_array(0, $company_id)) {
            $query->orWhere('compnay_id', 0);
        }
        if (in_array(null, $company_id, true)) {
            $query->orWhereNull('compnay_id');
        }
        })->get();
        $project_id = $request->project_id ? $request->project_id : null;
        $project = JobProject::find($request->project_id);


        if ($month && !$year) {
            $year = date('Y');
        }

        if ($party_type === 'all') {
            $from = null;
            $to = null;
            $party_id = null;
        }

        $sql = "
            SELECT
                pi.id,
                pi.pi_name,
                pi.pi_code,
                pi.pi_type,
                SUM(CASE
                    WHEN jr.transaction_type = 'DR' AND jr.account_head_id IN (3, 5,30,1759)
                    THEN jr.amount ELSE 0
                END) AS dr_amount,
                SUM(CASE
                    WHEN jr.transaction_type = 'CR' AND jr.account_head_id IN (3,5,30,1759)
                    THEN jr.amount ELSE 0
                END) AS cr_amount
            FROM party_infos AS pi
            JOIN journal_records AS jr ON jr.party_info_id = pi.id
            WHERE 1 = 1
        ";

        if ( $request->company_id) {
            $sql .=  " AND jr.compnay_id in ($ids)";
        } else {
            $sql .=  " AND jr.compnay_id IS NULL";
        }

        // Date filters
        if ($to && $from) {
            $sql .= " AND jr.journal_date BETWEEN '{$from}' AND '{$to}'";
        } elseif ($to) {
            $sql .= " AND jr.journal_date = '{$to}'";
        } elseif ($from) {
            $sql .= " AND jr.journal_date = '{$from}'";
        }

        // Year filter
        if ($year) {
            $sql .= " AND YEAR(jr.journal_date) = {$year}";
        }
        if ($project) {
            $sql .= " AND jr.job_project_id = {$project->id}";
        }

        // Month filter
        if ($month) {
            $sql .= " AND MONTH(jr.journal_date) = {$month}";
        }

        // Party type
        if ($party_type && $party_type != 'all') {
            $sql .= " AND pi.pi_type = '{$party_type}'";
        }

        // Specific party
        if ($party_id) {
            $sql .= " AND pi.id = {$party_id}";
        }

        $sql .= "
            GROUP BY pi.id, pi.pi_name, pi.pi_code, pi.pi_type
            ORDER BY pi.pi_name ASC
        ";

        $parties = DB::select($sql);

        // dd($parties);
        $all_parties = PartyInfo::orderBy('pi_name')->get();

        $companies = Subsidiary::orderBy('id', 'desc')->get();

        return view('backend.accounts-report.party-report', compact('project_id', 'projects', 'project', 'parties', 'to', 'from', 'all_parties', 'year', 'month', 'party_id', 'party_type', 'selected_company', 'companies'));
    }

    public function party_report_details(Request $request, PartyInfo $party, $searched_project = null)
    {
        $from = $request->from;
        $to = $request->to;
        $year = $request->year;
        $month = $request->month;
        $search_query = $request->search_query;
        $company_id = $request->company_id;
        $records = JournalRecord::where('compnay_id', $company_id)->whereIn('account_type_id', [1, 2])->whereNotIn('account_head_id', [19])->where('party_info_id', $party->id);
        if ($month) {
            $records = $records->whereMonth('journal_date', $month);
        }
        if ($searched_project) {
            $records = $records->where('job_project_id', $searched_project);
        }
        if ($year) {
            $records = $records->whereYear('journal_date', $year);
        }
        if ($to && $from) {
            $records = $records->whereBetween('journal_date', [$from, $to]);
        } elseif ($to) {
            $records = $records->whereBetween('journal_date', [$to, $to]);
        } elseif ($from) {
            $records = $records->whereBetween('journal_date', [$from, $from]);
        }

        $records = $records->orderBy('journal_date', 'ASC')->select('journal_id', 'journal_date')->distinct()->get();
        // dd($records);
        $provious_balance = JournalRecord::whereIn('account_head_id', [3, 5, 30, 1759])
            ->where('party_info_id', $party->id)
            ->when($searched_project, function ($query) use ($searched_project) {
                $query->where('job_project_id', $searched_project);
            })
            ->where('journal_date', '<', $records->first()->journal_date)
            ->get();
        // dd($provious_balance);
        $balance_fwd_dr = $provious_balance->where('transaction_type', 'DR')->sum('total_amount');
        $balance_fwd_cr = $provious_balance->where('transaction_type', 'CR')->sum('total_amount');
        // dd($balance);
        if ($party->pi_type == 'Customer') {
            return view('backend.accounts-report.party-report-details2', compact('records', 'balance_fwd_dr', 'balance_fwd_cr', 'party'));
        } else {
            return view('backend.accounts-report.party-report-details', compact('records', 'balance_fwd_dr', 'balance_fwd_cr', 'party'));
        }
    }

    public function new_trial_balance(Request $request)
    {

        $currentFirstDate = date('Y') . '-' . '01' . '-' . '01';
        $currentDate = date('Y-m-d');

         $date1 = $request->to ? $this->dateFormat($request->to) : $currentDate;
        $date = $request->from ? $this->dateFormat($request->from) : Carbon::parse($date1)->startOfYear()->toDateString();

        if ($request->date && !$request->date1) {
            $date1 =  $this->dateFormat($request->date);
            $date = $date1;
        }

        $company_id = $request->company_id?$request->company_id:null;
        $selected_company = Subsidiary::find($company_id);
        if (!$company_id) {
            $selected_company = new Subsidiary();
        }

        $master_accounts = DB::table('master_accounts')->orderBy('mst_ac_head')->get()
            ->map(function ($masterAccount) use ($date, $date1, $company_id) {
                $account_heads = DB::table('account_heads as ah')
                    ->select(
                        'ah.id',
                        'ah.fld_ac_head',
                        DB::raw("
                    SUM(CASE
                            WHEN jr.journal_date BETWEEN '{$date}' AND '{$date1}' AND jr.transaction_type = 'DR'
                            THEN jr.amount
                            ELSE 0
                        END) AS total_dr_amount
                "),

                        DB::raw("
                    SUM(CASE
                            WHEN jr.journal_date BETWEEN '{$date}' AND '{$date1}' AND jr.transaction_type = 'CR'
                            THEN jr.amount
                            ELSE 0
                        END) AS total_cr_amount
                "),

                        DB::raw("
                    SUM(CASE
                            WHEN jr.journal_date < '{$date}' AND jr.transaction_type = 'DR'
                            THEN jr.amount
                            ELSE 0
                        END) AS opening_dr_amount
                "),

                        DB::raw("
                    SUM(CASE
                            WHEN jr.journal_date < '{$date}' AND jr.transaction_type = 'CR'
                            THEN jr.amount
                            ELSE 0
                        END) AS opening_cr_amount
                "),

                        DB::raw("
                    SUM(CASE
                            WHEN jr.journal_date <= '{$date1}' AND jr.transaction_type = 'DR'
                            THEN jr.amount
                            ELSE 0
                        END) AS closing_dr_amount
                "),

                        DB::raw("
                    SUM(CASE
                            WHEN jr.journal_date <= '{$date1}' AND jr.transaction_type = 'CR'
                            THEN jr.amount
                            ELSE 0
                        END) AS closing_cr_amount
                ")
                    )
                    ->join('journal_records as jr', 'jr.account_head_id', '=', 'ah.id')
                    ->groupBy('ah.id', 'ah.fld_ac_head')
                    ->orderBy('ah.fld_ac_head', 'ASC')
                    ->where('ah.master_account_id', $masterAccount->id)
                    ->where('jr.compnay_id', $company_id)
                    ->get()

                    ->map(function ($head) use ($date, $date1, $company_id) {
                        $sub_heads = DB::table('account_sub_heads as ash')
                            ->select(
                                'ash.id',
                                'ash.name',

                                DB::raw("
                            SUM(CASE
                                    WHEN jr.journal_date BETWEEN '{$date}' AND '{$date1}' AND jr.transaction_type = 'DR'
                                    THEN jr.amount
                                    ELSE 0
                                END) AS total_dr_amount
                        "),

                                DB::raw("
                            SUM(CASE
                                    WHEN jr.journal_date BETWEEN '{$date}' AND '{$date1}' AND jr.transaction_type = 'CR'
                                    THEN jr.amount
                                    ELSE 0
                                END) AS total_cr_amount
                        "),

                                DB::raw("
                            SUM(CASE
                                    WHEN jr.journal_date < '{$date}' AND jr.transaction_type = 'DR'
                                    THEN jr.amount
                                    ELSE 0
                                END) AS opening_dr_amount
                        "),

                                DB::raw("
                            SUM(CASE
                                    WHEN jr.journal_date < '{$date}' AND jr.transaction_type = 'CR'
                                    THEN jr.amount
                                    ELSE 0
                                END) AS opening_cr_amount
                        "),

                                DB::raw("
                            SUM(CASE
                                    WHEN jr.journal_date <= '{$date1}' AND jr.transaction_type = 'DR'
                                    THEN jr.amount
                                    ELSE 0
                                END) AS closing_dr_amount
                        "),

                                DB::raw("
                            SUM(CASE
                                    WHEN jr.journal_date <= '{$date1}' AND jr.transaction_type = 'CR'
                                    THEN jr.amount
                                    ELSE 0
                                END) AS closing_cr_amount
                        ")
                            )

                            ->where('ash.account_head_id', $head->id)
                            ->join('journal_records as jr', 'jr.sub_account_head_id', '=', 'ash.id')
                            ->where('jr.compnay_id', $company_id)
                            ->groupBy('ash.id', 'ash.name')
                            ->orderBy('ash.name', 'ASC')
                            ->get();

                        return [
                            'sub_head' => $sub_heads,
                            'fld_ac_head' => $head->fld_ac_head,
                            'head_id' => $head->id,
                            'total_dr_amount' => $head->total_dr_amount,
                            'total_cr_amount' => $head->total_cr_amount,
                            'opening_dr_amount' =>  $head->opening_dr_amount - $head->opening_cr_amount,
                            'opening_cr_amount' =>  $head->opening_cr_amount - $head->opening_dr_amount,
                            'closing_dr_amount' => $head->closing_dr_amount - $head->closing_cr_amount,
                            'closing_cr_amount' => $head->closing_cr_amount - $head->closing_dr_amount,
                        ];
                    });

                return [
                    'master_account_id' => $masterAccount->id,
                    'code' => $masterAccount->mst_ac_code,
                    'name' => $masterAccount->mst_ac_head,
                    'account_heads' => $account_heads,
                ];
            });

        $companies = Subsidiary::orderBy('id', 'desc')->get();

        return view('backend.accounts-report.new-trial-balance', compact('date', 'date1', 'master_accounts', 'companies', 'selected_company'));
    }

    public function income_statement_pre(Request $request)
    {

        $office_id = $request->office_id;

        if (!$office_id) {
            $office_id  = auth()->user()->office_id;
        }

        $offices = Office::orderBy('name')->get();

        if ($request->date != null && $request->date2 != null) {
            $date = $this->dateFormat($request->date);
            $date2 = $this->dateFormat($request->date2);
            $masters = JournalRecord::whereIn('account_type_id', [4, 3])->distinct()
                ->when($office_id, fn($query) => $query->where('office_id', $office_id))
                ->get('account_head_id');
            $groupMasters = JournalRecord::whereIn('account_type_id', [])->distinct()
                ->when($office_id, fn($query) => $query->where('office_id', $office_id))
                ->get('account_head_id');

            return view('backend.accounts-report.income-statement-range', compact('masters', 'groupMasters', 'date', 'date2', 'office_id', 'offices'));
        } elseif ($request->date != null && $request->date2 == null) {
            $date = $this->dateFormat($request->date);
            $date2 = $this->dateFormat($request->date);
            $masters = JournalRecord::whereIn('account_type_id', [4, 3])->distinct()
                ->when($office_id, fn($query) => $query->where('office_id', $office_id))
                ->get('account_head_id');
            $groupMasters = JournalRecord::whereIn('account_type_id', [])->distinct()
                ->when($office_id, fn($query) => $query->where('office_id', $office_id))
                ->get('master_account_id');
            return view('backend.accounts-report.income-statement-range', compact('masters', 'groupMasters', 'date', 'date2', 'offices', 'office_id'));
        } elseif ($request->date == null && $request->date2 != null) {
            $date = $this->dateFormat($request->date2);
            $date2 = $this->dateFormat($request->date2);
            $masters = JournalRecord::whereIn('account_type_id', [4, 3])->distinct()
                ->when($office_id, fn($query) => $query->where('office_id', $office_id))
                ->get('account_head_id');
            $groupMasters = JournalRecord::whereIn('account_type_id', [])->distinct()
                ->when($office_id, fn($query) => $query->where('office_id', $office_id))
                ->get('master_account_id');
            return view('backend.accounts-report.income-statement-range', compact('masters', 'groupMasters', 'date', 'date2', 'office_id', 'offices'));
        } else {
            $masters = JournalRecord::whereIn('account_type_id', [4, 3])->distinct()
                ->when($office_id, fn($query) => $query->where('office_id', $office_id))->get('account_head_id');
            $groupMasters = JournalRecord::whereIn('account_type_id', [])->distinct()
                ->when($office_id, fn($query) => $query->where('office_id', $office_id))
                ->get('master_account_id');
            return view('backend.accounts-report.income-statement', compact('masters', 'groupMasters', 'offices', 'office_id'));
        }
    }

    public function income_statement(Request $request)
    {
        $to = $request->to ? $this->dateFormat($request->to) : date('Y-m-d');
        $from = $request->from ? $this->dateFormat($request->from) : Carbon::parse($to)->startOfYear()->toDateString();
        $company_id = $request->company_id?$request->company_id:null;
        // Revenue
        $revenues = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('journal_records.compnay_id', $company_id)
            ->where('account_heads.account_type_id', 3)
            ->whereBetween('journal_records.journal_date', [$from, $to])
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) -
                     SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) AS balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();

            // dd($revenues);

        $revenue_balance = $revenues->sum('balance');

        // Inventory
        $inventory = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('journal_records.compnay_id', $company_id)
            ->where('account_heads.fld_ms_ac_head', 'like', '%INVENTORY%')
            ->select(DB::raw("
            SUM(CASE WHEN journal_records.transaction_type = 'DR' AND journal_records.journal_date < '$from' THEN journal_records.amount ELSE 0 END) -
            SUM(CASE WHEN journal_records.transaction_type = 'CR' AND journal_records.journal_date < '$from' THEN journal_records.amount ELSE 0 END) AS beginningBalance,

            SUM(CASE WHEN journal_records.transaction_type = 'DR' AND journal_records.journal_date > '$from' AND journal_records.journal_date <= '$to' THEN journal_records.amount ELSE 0 END) AS purchaseAmount,

            SUM(CASE WHEN journal_records.transaction_type = 'DR' AND journal_records.journal_date <= '$to' THEN journal_records.amount ELSE 0 END) -
            SUM(CASE WHEN journal_records.transaction_type = 'CR' AND journal_records.journal_date <= '$to' THEN journal_records.amount ELSE 0 END) AS endBalance
        "))
            ->first();

        // Cost of Goods Sold
        $cog_s = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('journal_records.compnay_id', $company_id)
            ->where('account_heads.fld_definition', 'like', '%Cost of Sales / Goods Sold%')
            ->whereBetween('journal_records.journal_date', [$from, $to])
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) -
                     SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) AS balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();

        $total_cogs = ($inventory->beginningBalance ?? 0) - ($inventory->endBalance ?? 0) + $cog_s->sum('balance');
        $gross_profit = $revenue_balance - $total_cogs;

        // Overhead
        $overHeads = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('journal_records.compnay_id', $company_id)
            ->where('account_heads.account_type_id', 4)
            ->where('account_heads.fld_definition', 'not like', '%Cost of Sales / Goods Sold%')
            ->where('account_heads.fld_definition', 'not like', '%Administrative Expense%')
            ->where('account_heads.fld_definition', 'not like', '%Depreciation and Amortization%')
            ->whereBetween('journal_records.journal_date', [$from, $to])
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) -
                        SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) AS balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();

        // Admin Expenses
        $administrative_exp = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('journal_records.compnay_id', $company_id)
            ->where('account_heads.fld_definition', 'like', '%Administrative Expense%')
            ->whereBetween('journal_records.journal_date', [$from, $to])
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) -
                        SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) AS balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();

        $total_op_expense = $overHeads->sum('balance') + $administrative_exp->sum('balance');
        $net_profit_loss = $gross_profit - $total_op_expense;

        // Depreciation
        $depreciation = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('journal_records.compnay_id', $company_id)
            ->where(function ($query) {
                $query->where('account_heads.fld_ms_ac_head', 'like', '%Depreciation and Amortization%')
                    ->orWhere('account_heads.fld_ms_ac_head', 'like', '%Accumulated Depreciation & Amortization%');
            })
            ->whereBetween('journal_records.journal_date', [$from, $to])
            ->select(DB::raw("
                SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) -
                SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) AS amount
            "))
            ->first();

        $final_profit = $net_profit_loss - ($depreciation->amount ?? 0);
        $companies = Subsidiary::get();
        return view('backend.accounting-reports.income-statement.index', compact(
            'from',
            'to',
            'revenues',
            'revenue_balance',
            'inventory',
            'total_cogs',
            'cog_s',
            'gross_profit',
            'overHeads',
            'administrative_exp',
            'total_op_expense',
            'net_profit_loss',
            'depreciation',
            'final_profit',
            'companies',
            'company_id',
        ));
    }


    public function daily_report(Request $request)
    {
        $date = date('Y-m-d');
        $from = null;
        $to = null;
        if ($request->date) {
            $date = $this->dateformat($request->date);

            $sales = JournalRecord::whereIn('account_type_id', [3])->where('journal_date', $date)->select('journal_id')->distinct()->get();
            // $purchases=JournalRecord::whereIn('account_head_id',[851])->where('journal_date',$date)->select('journal_id')->distinct()->get();
            $purchases = DB::table('journal_records')
                ->leftJoin('account_heads', 'account_heads.id', '=', 'journal_records.account_head_id')
                ->where('account_heads.account_type_id', 1)
                ->where('account_heads.fld_definition', 'Sell of Asset')
                ->where('journal_records.journal_date', $date)
                ->select('journal_records.journal_id')
                ->distinct()
                ->get();

            $receiveds = JournalRecord::whereIn('account_head_id', [3])->where('transaction_type', 'CR')->where('journal_date', $date)->select('journal_id')->distinct()->get();
            $payments = JournalRecord::whereIn('account_head_id', [5])->where('transaction_type', 'DR')->where('journal_date', $date)->select('journal_id')->distinct()->get();
            $expensess = JournalRecord::whereNotIn('account_type_id', [4])->where('master_account_id', 4)->where('transaction_type', 'DR')->where('journal_date', $date)->select('journal_id')->distinct()->get();

            $cash_balance = (JournalRecord::whereIn('account_head_id', [1])->where('journal_date', $date)->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [1])->where('journal_date', $date)->where('transaction_type', 'CR')->sum('total_amount'));
            $bank_balance = (JournalRecord::whereIn('account_head_id', [2])->where('journal_date', $date)->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [2])->where('journal_date', $date)->where('transaction_type', 'CR')->sum('total_amount'));
            $receivable_balance = (JournalRecord::whereIn('account_head_id', [3])->where('journal_date', $date)->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [27])->where('journal_date', $date)->where('transaction_type', 'CR')->sum('total_amount'));
            $payable_balance = (JournalRecord::whereIn('account_head_id', [5])->where('journal_date', $date)->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [26])->where('journal_date', $date)->where('transaction_type', 'CR')->sum('total_amount'));
        } elseif ($request->from) {
            $from = $this->dateformat($request->from);
            $to = $this->dateformat($request->to);
            $sales = JournalRecord::whereIn('account_type_id', [3])->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->select('journal_id')->distinct()->get();
            // $purchases=JournalRecord::whereIn('account_head_id',[851])->where('journal_date','>=',$from)->where('journal_date','<=',$to)->select('journal_id')->distinct()->get();
            $purchases = DB::table('journal_records')
                ->leftJoin('account_heads', 'account_heads.id', '=', 'journal_records.account_head_id')
                ->where('account_heads.account_type_id', 1)
                ->where('account_heads.fld_definition', 'Sell of Asset')
                ->where('journal_records.journal_date', '>=', $from)
                ->where('journal_records.journal_date', '<=', $to)
                ->select('journal_records.journal_id')
                ->distinct()
                ->get();

            $receiveds = JournalRecord::whereIn('account_head_id', [3])->where('transaction_type', 'CR')->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->select('journal_id')->distinct()->get();
            $payments = JournalRecord::whereIn('account_head_id', [5])->where('transaction_type', 'DR')->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->select('journal_id')->distinct()->get();
            $expensess = JournalRecord::whereNotIn('account_type_id', [4])->where('master_account_id', 4)->where('transaction_type', 'DR')->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->select('journal_id')->distinct()->get();

            $cash_balance = (JournalRecord::whereIn('account_head_id', [1])->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [1])->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->where('transaction_type', 'CR')->sum('total_amount'));
            $bank_balance = (JournalRecord::whereIn('account_head_id', [2])->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [2])->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->where('transaction_type', 'CR')->sum('total_amount'));
            $receivable_balance = (JournalRecord::whereIn('account_head_id', [3])->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [27])->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->where('transaction_type', 'CR')->sum('total_amount'));
            $payable_balance = (JournalRecord::whereIn('account_head_id', [5])->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [26])->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->where('transaction_type', 'CR')->sum('total_amount'));
        } else {
            $sales = JournalRecord::whereIn('account_type_id', [3])->where('journal_date', date('Y-m-d'))->select('journal_id')->distinct()->get();
            // $purchases=JournalRecord::whereIn('account_head_id',[851])->where('journal_date',date('Y-m-d'))->select('journal_id')->distinct()->get();
            $purchases = DB::table('journal_records')
                ->leftJoin('account_heads', 'account_heads.id', '=', 'journal_records.account_head_id')
                ->where('account_heads.account_type_id', 1)
                ->where('account_heads.fld_definition', 'Sell of Asset')
                ->where('journal_records.journal_date', date('Y-m-d'))
                ->select('journal_records.journal_id')
                ->distinct()
                ->get();
            $receiveds = JournalRecord::whereIn('account_head_id', [3])->where('transaction_type', 'CR')->where('journal_date', date('Y-m-d'))->select('journal_id')->distinct()->get();
            $payments = JournalRecord::whereIn('account_head_id', [5])->where('transaction_type', 'DR')->where('journal_date', date('Y-m-d'))->select('journal_id')->distinct()->get();
            $expensess = JournalRecord::whereNotIn('account_type_id', [4])->where('master_account_id', 4)->where('transaction_type', 'DR')->where('journal_date', date('Y-m-d'))->select('journal_id')->distinct()->get();
            $cash_balance = (JournalRecord::whereIn('account_head_id', [1])->where('journal_date', date('Y-m-d'))->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [1])->where('journal_date', date('Y-m-d'))->where('transaction_type', 'CR')->sum('total_amount'));
            $bank_balance = (JournalRecord::whereIn('account_head_id', [2])->where('journal_date', date('Y-m-d'))->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [2])->where('journal_date', date('Y-m-d'))->where('transaction_type', 'CR')->sum('total_amount'));
            $receivable_balance = (JournalRecord::whereIn('account_head_id', [3])->where('journal_date', date('Y-m-d'))->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [27])->where('journal_date', date('Y-m-d'))->where('transaction_type', 'CR')->sum('total_amount'));
            $payable_balance = (JournalRecord::whereIn('account_head_id', [5])->where('journal_date', date('Y-m-d'))->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [26])->where('journal_date', date('Y-m-d'))->where('transaction_type', 'CR')->sum('total_amount'));
            $date = date('Y-m-d');
        }
        return view('backend.accounts-report.daily-report', compact('from', 'to', 'expensess', 'sales', 'purchases', 'receiveds', 'date', 'payments', 'cash_balance', 'bank_balance', 'receivable_balance', 'payable_balance'));
    }



    public function daily_summary_copy(Request $request)
    {
        $date = date('Y-m-d');
        $from = null;
        $to = null;
        if ($request->date) {
            $date = $this->dateformat($request->date);

            $sales = JournalRecord::whereIn('account_head_id', [3])->where('transaction_type', 'CR')->where('journal_date', $date)->select('journal_id')->distinct()->get();
            $payable = JournalRecord::whereIn('account_head_id', [5])->where('transaction_type', 'DR')->where('journal_date', $date)->select('journal_id')->distinct()->get();
            $income = JournalRecord::whereIn('account_head_id', [7])->where('transaction_type', 'CR')->where('journal_date', $date)->select('journal_id')->distinct()->get();

            $purchases = JournalRecord::whereIn('account_head_id', [8])->where('transaction_type', 'DR')->where('journal_date', $date)->select('journal_id')->distinct()->get();


            $receiveds = JournalRecord::whereIn('account_head_id', [3])->where('transaction_type', 'CR')->where('journal_date', $date)->select('journal_id')->distinct()->get();
            $payments = JournalRecord::whereIn('account_head_id', [5])->where('transaction_type', 'DR')->where('journal_date', $date)->select('journal_id')->distinct()->get();
            $expensess = JournalRecord::whereNotIn('account_type_id', [4])->where('master_account_id', 4)->where('transaction_type', 'DR')->where('journal_date', $date)->select('journal_id')->distinct()->get();

            $cash_balance = (JournalRecord::whereIn('account_head_id', [1])->where('journal_date', $date)->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [1])->where('journal_date', $date)->where('transaction_type', 'CR')->sum('total_amount'));
            $bank_balance = (JournalRecord::whereIn('account_head_id', [2])->where('journal_date', $date)->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [2])->where('journal_date', $date)->where('transaction_type', 'CR')->sum('total_amount'));
            $receivable_balance = (JournalRecord::whereIn('account_head_id', [3])->where('journal_date', $date)->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [3])->where('journal_date', $date)->where('transaction_type', 'CR')->sum('total_amount'));
            $payable_balance = (JournalRecord::whereIn('account_head_id', [5])->where('journal_date', $date)->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [5])->where('journal_date', $date)->where('transaction_type', 'CR')->sum('total_amount'));
        } elseif ($request->from) {
            $from = $this->dateformat($request->from);
            $to = $this->dateformat($request->to);
            $sales = JournalRecord::whereIn('account_head_id', [3])->where('transaction_type', 'CR')->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->select('journal_id')->distinct()->get();
            $payable = JournalRecord::whereIn('account_head_id', [5])->where('transaction_type', 'DR')->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->select('journal_id')->distinct()->get();
            $income = JournalRecord::whereIn('account_head_id', [7])->where('transaction_type', 'CR')->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->select('journal_id')->distinct()->get();
            $purchases = JournalRecord::whereIn('account_head_id', [8])->where('transaction_type', 'DR')->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->select('journal_id')->distinct()->get();

            // // $purchases=JournalRecord::whereIn('account_head_id',[851])->where('journal_date','>=',$from)->where('journal_date','<=',$to)->select('journal_id')->distinct()->get();
            // $purchases=DB::table('journal_records')
            // ->leftJoin('account_heads','account_heads.id','=','journal_records.account_head_id')
            // ->where('account_heads.account_type_id',1)
            // ->where('account_heads.fld_definition','Sell of Asset')
            // ->where('journal_records.journal_date','>=',$from)
            // ->where('journal_records.journal_date','<=',$to)
            // ->select('journal_records.journal_id')
            // ->distinct()
            // ->get();

            $receiveds = JournalRecord::whereIn('account_head_id', [3])->where('transaction_type', 'CR')->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->select('journal_id')->distinct()->get();
            $payments = JournalRecord::whereIn('account_head_id', [5])->where('transaction_type', 'DR')->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->select('journal_id')->distinct()->get();
            $expensess = JournalRecord::whereNotIn('account_type_id', [4])->where('master_account_id', 4)->where('transaction_type', 'DR')->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->select('journal_id')->distinct()->get();

            $cash_balance = (JournalRecord::whereIn('account_head_id', [1])->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [1])->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->where('transaction_type', 'CR')->sum('total_amount'));
            $bank_balance = (JournalRecord::whereIn('account_head_id', [2])->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [2])->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->where('transaction_type', 'CR')->sum('total_amount'));
            $receivable_balance = (JournalRecord::whereIn('account_head_id', [3])->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [3])->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->where('transaction_type', 'CR')->sum('total_amount'));
            $payable_balance = (JournalRecord::whereIn('account_head_id', [5])->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [5])->where('journal_date', '>=', $from)->where('journal_date', '<=', $to)->where('transaction_type', 'CR')->sum('total_amount'));
        } else {
            $sales = JournalRecord::whereIn('account_head_id', [3])->where('transaction_type', 'CR')->where('journal_date', date('Y-m-d'))->select('journal_id')->distinct()->get();
            $payable = JournalRecord::whereIn('account_head_id', [5])->where('transaction_type', 'DR')->where('journal_date', date('Y-m-d'))->select('journal_id')->distinct()->get();
            $income = JournalRecord::whereIn('account_head_id', [7])->where('transaction_type', 'CR')->where('journal_date', date('Y-m-d'))->select('journal_id')->distinct()->get();

            $purchases = JournalRecord::whereIn('account_head_id', [8])->where('transaction_type', 'DR')->where('journal_date', date('Y-m-d'))->select('journal_id')->distinct()->get();

            $receiveds = JournalRecord::whereIn('account_head_id', [3])->where('transaction_type', 'CR')->where('journal_date', date('Y-m-d'))->select('journal_id')->distinct()->get();
            $payments = JournalRecord::whereIn('account_head_id', [5])->where('transaction_type', 'DR')->where('journal_date', date('Y-m-d'))->select('journal_id')->distinct()->get();
            $expensess = JournalRecord::whereNotIn('account_type_id', [4])->where('master_account_id', 4)->where('transaction_type', 'DR')->where('journal_date', date('Y-m-d'))->select('journal_id')->distinct()->get();
            $cash_balance = (JournalRecord::whereIn('account_head_id', [1])->where('journal_date', date('Y-m-d'))->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [1])->where('journal_date', date('Y-m-d'))->where('transaction_type', 'CR')->sum('total_amount'));
            $bank_balance = (JournalRecord::whereIn('account_head_id', [2])->where('journal_date', date('Y-m-d'))->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [2])->where('journal_date', date('Y-m-d'))->where('transaction_type', 'CR')->sum('total_amount'));
            $receivable_balance = (JournalRecord::whereIn('account_head_id', [3])->where('journal_date', date('Y-m-d'))->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [3])->where('journal_date', date('Y-m-d'))->where('transaction_type', 'CR')->sum('total_amount'));
            $payable_balance = (JournalRecord::whereIn('account_head_id', [5])->where('journal_date', date('Y-m-d'))->where('transaction_type', 'DR')->sum('total_amount')) - (JournalRecord::whereIn('account_head_id', [5])->where('journal_date', date('Y-m-d'))->where('transaction_type', 'CR')->sum('total_amount'));
            $date = date('Y-m-d');
        }
        return view('backend.accounts-report.daily-summary', compact('from', 'to', 'expensess', 'sales', 'purchases', 'receiveds', 'date', 'payments', 'cash_balance', 'bank_balance', 'receivable_balance', 'payable_balance', 'payable', 'income'));
    }
    public function daily_summary(Request $request)
    {
        Gate::authorize('Daily_Summary');
        if ($request->date) {
            $date = $this->dateFormat($request->date);
        } else {
            $date = date('Y-m-d');
        }
        // dd($date);
        $from = null;
        $to = null;
        if ($request->from && $request->to) {
            $from = $this->dateFormat($request->from);
            $to = $this->dateFormat($request->to);
            // $opening_balance_receive_cash = Receipt::where('pay_mode', 'like', '%'.'Cash'.'%')->where('date', '<', $from)->sum('total_amount');

            $opening_balance_receive_cash = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')->where('receipts.pay_mode', 'like', '%' . 'Cash' . '%')->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->where('job_project_invoices.date', '<', $from)->select('receipt_sales.*', 'receipts.total_amount')->sum('receipts.total_amount');
            // dd($opening_balance_receive_cash);
            $dr_cash = JournalRecord::where('account_head_id', 1)->where('transaction_type', 'DR')->where('journal_date', '<', $from)->sum('amount');
            $cr_cash = JournalRecord::where('account_head_id', 1)->where('transaction_type', 'CR')->where('journal_date', '<', $from)->sum('amount');
            $opening_balance_cash = $dr_cash - $cr_cash;
            // dd($opening_balance_cash);

            $dr_bank = JournalRecord::where('account_head_id', 2)->where('transaction_type', 'DR')->where('journal_date', '<', $from)->sum('amount');
            $cr_bank = JournalRecord::where('account_head_id', 2)->where('transaction_type', 'CR')->where('journal_date', '<', $from)->sum('amount');
            $opening_balance_bank = $dr_bank - $cr_bank;

            $dr_petty_cash = JournalRecord::where('account_head_id', 93)->where('transaction_type', 'DR')->where('journal_date', '<', $from)->sum('amount');
            $cr_petty_cash = JournalRecord::where('account_head_id', 93)->where('transaction_type', 'CR')->where('journal_date', '<', $from)->sum('amount');
            $opening_balance_pettycash = $dr_petty_cash - $cr_petty_cash;

            $opening_balance_payment_cash = Payment::where('pay_mode', 'Cash')->whereBetween('date', [$from, $to])->sum('total_amount');
            $opening_balance_receive_bank = Receipt::whereIn('receipts.pay_mode', ['Bank', 'Card'])->whereBetween('date', [$from, $to])->sum('total_amount');

            $opening_balance_payment_bank = Payment::where('pay_mode', 'Bank')->whereBetween('date', [$from, $to])->sum('total_amount');
            $fund_allocation = FundAllocation::where('approved', 1)->whereBetween('date', [$from, $to])->select('id', 'date', 'amount', 'account_id_to', 'account_id_from', 'transaction_cost')->get();
            $previous_fund_allocation = FundAllocation::where('approved', 1)->whereBetween('date', [$from, $to])->select('id', 'date', 'amount', 'account_id_to', 'account_id_from', 'transaction_cost')->get();
            // dd($fund_allocation);

            $today_cash_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')->where('receipts.pay_mode', 'like', '%' . 'Cash' . '%')->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->whereBetween('job_project_invoices.date', [$from, $to])->select('receipt_sales.*', 'receipts.total_amount')->get();
            // dd($today_cash_sale_receipts);

            $previous_cash_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')->where('receipts.pay_mode', 'like', '%' . 'Cash' . '%')->whereBetween('receipts.date', [$from, $to])->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->where('job_project_invoices.date', '<', $from)->select('receipt_sales.*', 'receipts.total_amount')->get();
            // dd($previous_cash_sale_receipts);
            $today_cash_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->where('payments.pay_mode', 'Cash')->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->whereBetween('purchase_expenses.date', [$from, $to])->select('payment_invoices.*')->get();
            // dd($today_cash_bill_payments);

            $previous_cash_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->where('payments.pay_mode', 'Cash')->whereBetween('payments.date', [$from, $to])->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', '<', $from)->select('payment_invoices.*')->get();
            // dd($previous_cash_bill_payments);
            $today_advance_cash_received = Receipt::where('type', 'advance')->whereBetween('date', [$from, $to])->where('pay_mode', 'Cash')->get();
            $today_advance_cash_payment = Payment::where('type', 'advance')->whereBetween('date', [$from, $to])->where('pay_mode', 'Cash')->get();

            $today_bank_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')->whereIn('receipts.pay_mode', ['Bank', 'Card'])->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->whereBetween('job_project_invoices.date', [$from, $to])->select('receipt_sales.*', 'receipts.total_amount')->get();

            $previous_bank_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')->whereIn('receipts.pay_mode', ['Bank', 'Card'])->whereBetween('receipts.date', [$from, $to])->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->where('job_project_invoices.date', '<', $from)->select('receipt_sales.*', 'receipts.total_amount')->get();

            $today_bank_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->whereIn('payments.pay_mode', ['Bank', 'Card'])->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->whereBetween('purchase_expenses.date', [$from, $to])->select('payment_invoices.*')->get();

            $previous_bank_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->whereIn('payments.pay_mode', ['Bank', 'Card'])->whereBetween('payments.date', [$from, $to])->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', '<', $from)->select('payment_invoices.*')->get();

            $today_advance_bank_received = Receipt::where('type', 'advance')->whereBetween('date', [$from, $to])->whereIn('pay_mode', ['Bank', 'Card'])->get();
            $today_advance_bank_payment = Payment::where('type', 'advance')->whereBetween('date', [$from, $to])->whereIn('pay_mode', ['Bank', 'Card'])->get();
            $today_advance_visa_card_payment = Payment::where('type', 'advance')->whereBetween('date', [$from, $to])->whereIn('pay_mode', ['Bank', 'Card'])->get();

            $previous_account_receivable = JobProjectInvoice::where('date', '<', $from)->where('due_amount', '>', 0)->get();
            $today_account_receivable = JobProjectInvoice::whereBetween('date', [$from, $to])->where('due_amount', '>', 0)->get();;

            $previous_account_payable = PurchaseExpense::where('date', '<', $from)->where('due_amount', '>', 0)->get();
            $today_account_payable = PurchaseExpense::whereBetween('date', [$from, $to])->where('due_amount', '>', 0)->get();
            $today_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->whereBetween('payments.date', [$from, $to])->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->whereBetween('purchase_expenses.date', [$from, $to])->select('payment_invoices.*', 'payments.date', 'payments.type', 'payments.pay_mode', 'payments.total_amount')->get();
            $previous_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->whereBetween('payments.date', [$from, $to])->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', '<', $from)->select('payment_invoices.*', 'payments.date', 'payments.type', 'payments.pay_mode', 'payments.total_amount')->get();
        } else {
            $dr_cash = JournalRecord::where('account_head_id', 1)->where('transaction_type', 'DR')->where('journal_date', '<', $date)->sum('amount');
            $cr_cash = JournalRecord::where('account_head_id', 1)->where('transaction_type', 'CR')->where('journal_date', '<', $date)->sum('amount');
            $opening_balance_cash = $dr_cash - $cr_cash;
            $dr_bank = JournalRecord::where('account_head_id', 2)->where('transaction_type', 'DR')->where('journal_date', '<', $date)->sum('amount');
            $cr_bank = JournalRecord::where('account_head_id', 2)->where('transaction_type', 'CR')->where('journal_date', '<', $date)->sum('amount');
            $opening_balance_bank = $dr_bank - $cr_bank;
            $dr_petty_cash = JournalRecord::where('account_head_id', 93)->where('transaction_type', 'DR')->where('journal_date', '<', $date)->sum('amount');
            $cr_petty_cash = JournalRecord::where('account_head_id', 93)->where('transaction_type', 'CR')->where('journal_date', '<', $date)->sum('amount');
            $opening_balance_pettycash = $dr_petty_cash - $cr_petty_cash;

            $opening_balance_receive_cash = Receipt::where('pay_mode', 'like', '%' . 'Cash' . '%')->where('date', '<', $date)->sum('total_amount');
            $opening_balance_payment_cash = Payment::where('pay_mode', 'Cash')->where('date', '<', $date)->sum('total_amount');
            $opening_balance_receive_bank = Receipt::whereIn('receipts.pay_mode', ['Bank', 'Card'])->where('date', '<', $date)->sum('total_amount');
            $opening_balance_payment_bank = Payment::where('pay_mode', 'Bank')->where('date', '<', $date)->sum('total_amount');
            $fund_allocation = FundAllocation::where('approved', 1)->where('date', $date)->select('id', 'date', 'amount', 'account_id_to', 'account_id_from', 'transaction_cost')->get();
            $previous_fund_allocation = FundAllocation::where('approved', 1)->where('date', '<', $date)->select('id', 'date', 'amount', 'account_id_to', 'account_id_from', 'transaction_cost')->get();
            // dd($fund_allocation);
            $today_cash_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')->where('receipts.pay_mode', 'like', '%' . 'Cash' . '%')->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->where('job_project_invoices.date', $date)->select('receipt_sales.*', 'receipts.total_amount')->get();
            // dd($today_cash_sale_receipts->sum('total_amount'));
            $previous_cash_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')->where('receipts.pay_mode', 'like', '%' . 'Cash' . '%')->where('receipts.date', $date)->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->where('job_project_invoices.date', '<', $date)->select('receipt_sales.*', 'receipts.total_amount')->get();
            // dd($previous_cash_sale_receipts);
            $today_cash_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->where('payments.pay_mode', 'Cash')->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', $date)->select('payment_invoices.*')->get();
            // dd($today_cash_bill_payments);
            $previous_cash_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->where('payments.pay_mode', 'Cash')->where('payments.date', $date)->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', '<', $date)->select('payment_invoices.*')->get();
            // dd($previous_cash_bill_payments);
            $today_advance_cash_received = Receipt::where('type', 'advance')->where('date', $date)->where('pay_mode', 'Cash')->get();
            $today_advance_cash_payment = Payment::where('type', 'advance')->where('date', $date)->where('pay_mode', 'Cash')->get();

            $today_bank_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')->whereIn('receipts.pay_mode', ['Bank', 'Card'])->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->where('job_project_invoices.date', $date)->select('receipt_sales.*', 'receipts.total_amount')->get();
            $previous_bank_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')->whereIn('receipts.pay_mode', ['Bank', 'Card'])->where('receipts.date', $date)->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->where('job_project_invoices.date', '<', $date)->select('receipt_sales.*', 'receipts.total_amount')->get();

            $today_bank_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->whereIn('payments.pay_mode', ['Bank', 'Card'])->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', $date)->select('payment_invoices.*')->get();

            $previous_bank_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->whereIn('payments.pay_mode', ['Bank', 'Card'])->where('payments.date', $date)->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', '<', $date)->select('payment_invoices.*')->get();

            $today_advance_bank_received = Receipt::where('type', 'advance')->where('date', $date)->whereIn('pay_mode', ['Bank', 'Card'])->get();
            $today_advance_bank_payment = Payment::where('type', 'advance')->where('date', $date)->whereIn('pay_mode', ['Bank', 'Card'])->get();
            $today_advance_visa_card_payment = Payment::where('type', 'advance')->where('date', $date)->whereIn('pay_mode', ['Bank', 'Card'])->sum('total_amount');

            $previous_account_receivable = JobProjectInvoice::where('date', '<', $date)->where('due_amount', '>', 0)->get();

            $today_account_receivable = JobProjectInvoice::where('date', $date)->where('due_amount', '>', 0)->get();

            $previous_account_payable = PurchaseExpense::where('date', '<', $date)->where('due_amount', '>', 0)->get();
            $today_account_payable = PurchaseExpense::where('date', $date)->where('due_amount', '>', 0)->get();
            // dd($previous_account_payable);

            $today_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->where('payments.date', $date)->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', $date)->select('payment_invoices.*', 'payments.date', 'payments.type', 'payments.pay_mode', 'payments.total_amount')->get();
            $previous_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->where('payments.date', $date)->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', '<', $date)->select('payment_invoices.*', 'payments.date', 'payments.type', 'payments.pay_mode', 'payments.total_amount')->get();
        }

        return view('backend.accounts-report.daily-summary', compact('from', 'to', 'date', 'today_cash_sale_receipts', 'previous_cash_sale_receipts', 'today_cash_bill_payments', 'previous_cash_bill_payments', 'today_advance_cash_received', 'today_advance_cash_payment', 'today_bank_sale_receipts', 'previous_bank_sale_receipts', 'today_bank_bill_payments', 'previous_bank_bill_payments', 'today_advance_bank_received', 'today_advance_bank_payment', 'opening_balance_receive_cash', 'opening_balance_payment_cash', 'opening_balance_receive_bank', 'opening_balance_payment_bank', 'previous_account_receivable', 'today_account_receivable', 'previous_account_payable', 'today_account_payable', 'fund_allocation', 'today_payments', 'previous_payments', 'previous_fund_allocation', 'opening_balance_bank', 'opening_balance_cash', 'opening_balance_pettycash'));
    }

    // cash summery details
    public function cash_today_sale_received(Request $request)
    {
        // dd($request->date);
        $from = $request->from;
        $to = $request->to;
        $date = $request->date ? $request->date : date('Y-m-d');
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $today_cash_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')->where('receipts.pay_mode', 'like', '%' . 'Cash' . '%')->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->whereBetween('job_project_invoices.date', [$from, $to])->select('receipt_sales.*')->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $today_cash_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')->where('receipts.pay_mode', 'like', '%' . 'Cash' . '%')->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->where('job_project_invoices.date', $date)->select('receipt_sales.*')->get();
        }
        $receipt_list = $today_cash_sale_receipts;
        $cash = true;
        return view('backend.accounts-report.receipt-report', compact('receipt_list', 'cash'));
    }
    public function cash_previous_receivable_receive(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $previous_cash_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')->where('receipts.pay_mode', 'like', '%' . 'Cash' . '%')->whereBetween('receipts.date', [$from, $to])->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->where('job_project_invoices.date', '<', $from)->select('receipt_sales.*')->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $previous_cash_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')->where('receipts.pay_mode', 'like', '%' . 'Cash' . '%')->where('receipts.date', $date)->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->where('job_project_invoices.date', '<', $date)->select('receipt_sales.*')->get();
        }
        // dd($previous_cash_sale_receipts);
        $receipt_list = $previous_cash_sale_receipts;
        $cash = true;
        return view('backend.accounts-report.receipt-report', compact('receipt_list', 'cash'));
    }
    public function cash_advance_receive(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $today_advance_cash_received = Receipt::where('type', 'advance')->whereBetween('date', [$from, $to])->where('pay_mode', 'Cash')->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $today_advance_cash_received = Receipt::where('type', 'advance')->where('date', $date)->where('pay_mode', 'Cash')->get();
        }
        $receipt_list = $today_advance_cash_received;
        return view('backend.accounts-report.receipt-report2', compact('receipt_list'));
    }
    public function cash_today_payment_expense(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $today_cash_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->where('payments.pay_mode', 'Cash')->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->whereBetween('purchase_expenses.date', [$from, $to])->select('payment_invoices.*')->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $today_cash_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->where('payments.pay_mode', 'Cash')->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', $date)->select('payment_invoices.*')->get();
        }
        $payments = $today_cash_bill_payments;
        return view('backend.accounts-report.payment-report', compact('payments'));
    }
    public function cash_previous_payable_payment(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $previous_cash_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->where('payments.pay_mode', 'Cash')->whereBetween('payments.date', [$from, $to])->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', '<', $from)->select('payment_invoices.*')->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $previous_cash_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->where('payments.pay_mode', 'Cash')->where('payments.date', $date)->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', '<', $date)->select('payment_invoices.*')->get();
        }
        $payments = $previous_cash_bill_payments;
        return view('backend.accounts-report.payment-report', compact('payments'));
    }
    public function today_payment_expense(Request $request, $pay_mode)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $today_cash_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->where('payments.pay_mode', $pay_mode)->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->whereBetween('purchase_expenses.date', [$from, $to])->select('payment_invoices.*')->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $today_cash_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->where('payments.pay_mode', $pay_mode)->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', $date)->select('payment_invoices.*')->get();
        }
        $payments = $today_cash_bill_payments;
        return view('backend.accounts-report.payment-report', compact('payments'));
    }
    public function previous_payment_expense(Request $request, $pay_mode)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $previous_cash_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->where('payments.pay_mode', $pay_mode)->whereBetween('payments.date', [$from, $to])->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', '<', $from)->select('payment_invoices.*')->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $previous_cash_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->where('payments.pay_mode', $pay_mode)->where('payments.date', $date)->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', '<', $date)->select('payment_invoices.*')->get();
        }
        $payments = $previous_cash_bill_payments;
        return view('backend.accounts-report.payment-report', compact('payments'));
    }
    public function cash_advance_payment(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $today_advance_cash_payment = Payment::where('type', 'advance')->whereBetween('date', [$from, $to])->where('pay_mode', 'Cash')->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $today_advance_cash_payment = Payment::where('type', 'advance')->where('date', $date)->where('pay_mode', 'Cash')->get();
        }
        $payments = $today_advance_cash_payment;
        return view('backend.accounts-report.payment-report2', compact('payments'));
    }
    // bank summery details
    public function bank_today_sale_received(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $today_bank_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')->whereIn('receipts.pay_mode', ['Card', 'Bank'])->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->whereBetween('job_project_invoices.date', [$from, $to])->select('receipt_sales.*')->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $today_bank_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')->whereIn('receipts.pay_mode', ['Card', 'Bank'])->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->where('job_project_invoices.date', $date)->select('receipt_sales.*')->get();
        }
        $receipt_list = $today_bank_sale_receipts;
        $cash = false;
        return view('backend.accounts-report.receipt-report', compact('receipt_list', 'cash'));
    }
    public function bank_previous_receivable_receive(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $previous_bank_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')->whereIn('receipts.pay_mode', ['Card', 'Bank'])->whereBetween('receipts.date', [$from, $to])->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->where('job_project_invoices.date', '<', $from)->select('receipt_sales.*')->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $previous_bank_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')->whereIn('receipts.pay_mode', ['Card', 'Bank'])->where('receipts.date', $date)->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->where('job_project_invoices.date', '<', $date)->select('receipt_sales.*')->get();
        }
        $receipt_list = $previous_bank_sale_receipts;
        $cash = false;
        return view('backend.accounts-report.receipt-report', compact('receipt_list', 'cash'));
    }
    public function bank_advance_receive(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $today_advance_bank_received = Receipt::where('type', 'advance')->whereBetween('date', [$from, $to])->whereIn('pay_mode', ['Card', 'Bank'])->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $today_advance_bank_received = Receipt::where('type', 'advance')->where('date', $date)->whereIn('pay_mode', ['Card', 'Bank'])->get();
        }
        $receipt_list = $today_advance_bank_received;
        return view('backend.accounts-report.receipt-report2', compact('receipt_list'));
    }
    public function bank_today_payment_expense(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $today_bank_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->whereIn('payments.pay_mode', ['Card', 'Bank'])->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->whereBetween('purchase_expenses.date', [$from, $to])->select('payment_invoices.*')->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $today_bank_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->whereIn('payments.pay_mode', ['Card', 'Bank'])->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', $date)->select('payment_invoices.*')->get();
        }
        $payments = $today_bank_bill_payments;
        return view('backend.accounts-report.payment-report', compact('payments'));
    }
    public function bank_previous_payable_payment(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $previous_bank_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->whereIn('payments.pay_mode', ['Card', 'Bank'])->whereBetween('payments.date', [$from, $to])->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', '<', $from)->select('payment_invoices.*')->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $previous_bank_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->whereIn('payments.pay_mode', ['Card', 'Bank'])->where('payments.date', $date)->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', '<', $date)->select('payment_invoices.*')->get();
        }
        $payments = $previous_bank_bill_payments;
        // dd($payments);
        return view('backend.accounts-report.payment-report', compact('payments'));
    }
    public function bank_advance_payment(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $today_advance_bank_payment = Payment::where('type', 'advance')->whereBetween('date', [$from, $to])->whereIn('pay_mode', ['Card', 'Bank'])->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $today_advance_bank_payment = Payment::where('type', 'advance')->where('date', $date)->whereIn('pay_mode', ['Card', 'Bank'])->get();
        }
        $payments = $today_advance_bank_payment;
        return view('backend.accounts-report.payment-report2', compact('payments'));
    }
    // till day receivable and payable
    public function previous_account_receivable(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $previous_account_receivable = Invoice::where('date', '<', $from)->where('due_amount', '>', 0)->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $previous_account_receivable = Invoice::where('date', '<', $date)->where('due_amount', '>', 0)->get();
        }
        return view('backend.accounts-report.receipt-report3', compact('previous_account_receivable'));
    }
    public function today_account_receivable(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $previous_account_receivable = Invoice::whereBetween('date', [$from, $to])->where('due_amount', '>', 0)->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $previous_account_receivable = Invoice::where('date', $date)->where('due_amount', '>', 0)->get();
        }
        return view('backend.accounts-report.receipt-report3', compact('previous_account_receivable'));
    }
    public function previous_account_payable(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $previous_account_payable = PurchaseExpense::where('date', '<', $from)->where('due_amount', '>', 0)->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $previous_account_payable = PurchaseExpense::where('date', '<', $date)->where('due_amount', '>', 0)->get();
        }

        return view('backend.accounts-report.payment-report3', compact('previous_account_payable'));
    }
    public function today_account_payable(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
            $previous_account_payable = PurchaseExpense::whereBetween('date', [$from, $to])->where('due_amount', '>', 0)->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $previous_account_payable = PurchaseExpense::where('date', $date)->where('due_amount', '>', 0)->get();
        }
        return view('backend.accounts-report.payment-report3', compact('previous_account_payable'));
    }

    public function fund_transfer(Request $request, $from, $to)
    {
        if ($request->from && $request->to) {
            $date_from = $request->from;
            $date_to = $request->to;
            $fund_allocations = FundAllocation::whereBetween('date', [$date_from, $date_to])->where('account_id_from', $from)->where('account_id_to', $to)->get();
        } else {
            $date = $request->date ? $request->date : date('Y-m-d');
            $fund_allocations = FundAllocation::where('date', $date)->where('account_id_from', $from)->where('account_id_to', $to)->get();
        }
        return view('backend.accounts-report.fund-transfer', compact('fund_allocations'));
    }

    public function daily_summar_copy(Request $request)
    {
        $date = date('Y-m-d');
        $from = null;
        $to = null;
        $office_id = Auth::user()->office_id;
        $opening_balance_receive_cash = Receipt::where('office_id', $office_id)->where('pay_mode', 'like', '%' . 'Cash' . '%')->where('date', '<', date('Y-m-d'))->sum('total_amount');
        $opening_balance_payment_cash = Payment::where('office_id', $office_id)->where('pay_mode', 'Cash')->where('date', '<', date('Y-m-d'))->sum('total_amount');
        $opening_balance_receive_bank = Receipt::where('office_id', $office_id)->where('pay_mode', 'like', '%' . 'Bank' . '%')->where('date', '<', date('Y-m-d'))->sum('total_amount');
        $opening_balance_payment_bank = Payment::where('office_id', $office_id)->where('pay_mode', 'Bank')->where('date', '<', date('Y-m-d'))->sum('total_amount');
        $fund_allocation = FundAllocation::where('office_id', $office_id)->where('approved', 1)->where('date', date('Y-m-d'))->select('amount', 'account_id_to', 'account_id_from', 'transaction_cost')->get();
        $previous_fund_allocation = FundAllocation::where('office_id', $office_id)->where('approved', 1)->where('date', '<', date('Y-m-d'))
            ->select('amount', 'account_id_to', 'account_id_from')->get();
        // dd($fund_allocation);
        $today_cash_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')
            ->where('receipts.pay_mode', 'like', '%' . 'Cash' . '%')->where('receipt_sales.office_id', $office_id)->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')
            ->where('job_project_invoices.date', date('Y-m-d'))->select('receipt_sales.*', 'receipts.total_amount')->sum('receipts.total_amount');
        // dd($today_cash_sale_receipts);
        $previous_cash_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')
            ->where('receipts.pay_mode', 'like', '%' . 'Cash' . '%')->where('receipts.date', date('Y-m-d'))
            ->where('receipt_sales.office_id', $office_id)
            ->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')
            ->where('job_project_invoices.date', '<', date('Y-m-d'))
            ->select('receipt_sales.*', 'receipts.cash_amount')->sum('receipts.total_amount');

        $today_cash_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')
            ->where('payments.pay_mode', 'Cash')->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')
            ->where('payment_invoices.office_id', $office_id)
            ->where('purchase_expenses.date', date('Y-m-d'))->select('payment_invoices.*')->sum('payment_invoices.total_amount');
        // dd($today_cash_bill_payments);
        $previous_cash_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')
            ->where('payments.pay_mode', 'Cash')->where('payments.date', date('Y-m-d'))
            ->where('payment_invoices.office_id', $office_id)
            ->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', '<', date('Y-m-d'))
            ->select('payment_invoices.*')->sum('payment_invoices.total_amount');
        // dd($previous_cash_bill_payments);
        $today_advance_cash_received = Receipt::where('office_id', $office_id)->where('type', 'advance')->where('date', date('Y-m-d'))->where('pay_mode', 'Cash')->sum('total_amount');
        $today_advance_cash_payment = Payment::where('office_id', $office_id)->where('pay_mode', 'Advance')->where('date', date('Y-m-d'))->where('pay_mode', 'Cash')->sum('total_amount');

        $today_bank_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')
            ->where('receipts.pay_mode', 'like', '%' . 'Bank' . '%')->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')
            ->where('receipt_sales.office_id', $office_id)
            ->where('job_project_invoices.date', date('Y-m-d'))->select('receipt_sales.*', 'receipts.total_amount')->sum('receipts.total_amount');

        $previous_bank_sale_receipts = ReceiptSale::join('receipts', 'receipts.id', '=', 'receipt_sales.payment_id')
            ->where('receipts.pay_mode', 'like', '%' . 'Bank' . '%')->where('receipts.date', date('Y-m-d'))
            ->where('receipt_sales.office_id', $office_id)
            ->join('job_project_invoices', 'job_project_invoices.id', '=', 'receipt_sales.sale_id')->where('job_project_invoices.date', '<', date('Y-m-d'))
            ->select('receipt_sales.*', 'receipts.total_amount')->sum('receipts.total_amount');

        $today_bank_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')
            ->where('payments.pay_mode', 'Bank')->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')
            ->where('payment_invoices.office_id', $office_id)
            ->where('purchase_expenses.date', date('Y-m-d'))->select('payment_invoices.*')->sum('payment_invoices.total_amount');

        $previous_bank_bill_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')
            ->where('payment_invoices.office_id', $office_id)
            ->where('payments.pay_mode', 'Bank')->where('payments.date', date('Y-m-d'))
            ->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', '<', date('Y-m-d'))
            ->select('payment_invoices.*')->sum('payment_invoices.total_amount');

        $today_advance_bank_received = Receipt::where('office_id', $office_id)->where('type', 'advance')->where('date', date('Y-m-d'))->where('pay_mode', 'Bank')->sum('total_amount');
        $today_advance_bank_payment = Payment::where('office_id', $office_id)->where('pay_mode', 'Advance')->where('date', date('Y-m-d'))->where('pay_mode', 'Bank')->sum('total_amount');
        $today_advance_visa_card_payment = Payment::where('office_id', $office_id)->where('pay_mode', 'Advance')->where('date', date('Y-m-d'))
            ->where('pay_mode', 'Bank')->sum('total_amount');

        $previous_account_receivable = Invoice::where('office_id', $office_id)->where('date', '<', date('Y-m-d'))->where('due_amount', '>', 0)->sum('due_amount');
        $today_account_receivable = Invoice::where('office_id', $office_id)->where('date', date('Y-m-d'))->where('due_amount', '>', 0)->sum('due_amount');


        $previous_account_payable = PurchaseExpense::where('office_id', $office_id)->where('date', '<', date('Y-m-d'))->where('due_amount', '>', 0)->sum('due_amount');
        $today_account_payable = PurchaseExpense::where('office_id', $office_id)->where('date', date('Y-m-d'))->where('due_amount', '>', 0)->sum('due_amount');
        // dd($previous_account_payable);

        $today_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->where('payment_invoices.office_id', $office_id)->where('payments.date', date('Y-m-d'))->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', date('Y-m-d'))->select('payment_invoices.*', 'payments.date', 'payments.pay_mode', 'payments.total_amount')->get();
        // dd($today_payments);
        $previous_payments = PaymentInvoice::join('payments', 'payments.id', '=', 'payment_invoices.payment_id')->where('payment_invoices.office_id', $office_id)->where('payments.date', date('Y-m-d'))->join('purchase_expenses', 'purchase_expenses.id', '=', 'payment_invoices.sale_id')->where('purchase_expenses.date', '<', date('Y-m-d'))->select('payment_invoices.*', 'payments.date',  'payments.pay_mode', 'payments.total_amount')->get();
        // dd($previous_payments);
        $other_receipt = FundAdd::where('office_id', $office_id)->where('date', date('Y-m-d'))->get();
        return view('backend.accounts-report.daily-summary1', compact(
            'from',
            'to',
            'date',
            'today_cash_sale_receipts',
            'previous_cash_sale_receipts',
            'today_cash_bill_payments',
            'previous_cash_bill_payments',
            'today_advance_cash_received',
            'today_advance_cash_payment',
            'today_bank_sale_receipts',
            'previous_bank_sale_receipts',
            'today_bank_bill_payments',
            'previous_bank_bill_payments',
            'today_advance_bank_received',
            'today_advance_bank_payment',
            'opening_balance_receive_cash',
            'opening_balance_payment_cash',
            'opening_balance_receive_bank',
            'opening_balance_payment_bank',
            'previous_account_receivable',
            'today_account_receivable',
            'previous_account_payable',
            'today_account_payable',
            'fund_allocation',
            'today_payments',
            'previous_payments',
            'previous_fund_allocation',
            'other_receipt'
        ));
    }

    public function balance_sheet_pre(Request $request)
    {
        $office_id = $request->office_id;
        if (!$office_id) {
            $office_id = auth()->user()->office_id;
        }
        $offices = Office::orderBy('name')->get();
        if ($request->date && $request->date2) {
            $from_date = $this->dateFormat($request->date);
            $to_date = $this->dateFormat($request->date2);
        } else {
            $from_date = optional(JournalRecord::where('office_id', Auth::user()->office_id)->orderBy('journal_date', 'asc')->first())->journal_date;
            $to_date = optional(JournalRecord::where('office_id', Auth::user()->office_id)->orderBy('journal_date', 'desc')->first())->journal_date;
        }
        $assets_results = JournalRecord::whereBetween('journal_records.journal_date', [$from_date, $to_date])
            ->where('journal_records.office_id', Auth::user()->office_id)
            ->join('master_accounts', 'master_accounts.id', '=', 'journal_records.master_account_id')
            ->whereIn('master_accounts.mst_ac_type', ['ASSET'])
            ->selectRaw('
            master_accounts.id as master_accounts_id,
            master_accounts.mst_ac_head as master_accounts_name,
            SUM(CASE WHEN journal_records.transaction_type = "DR" THEN journal_records.amount ELSE 0 END) as total_dr,
            SUM(CASE WHEN journal_records.transaction_type = "CR" THEN journal_records.amount ELSE 0 END) as total_cr,
            (SUM(CASE WHEN journal_records.transaction_type = "DR" THEN journal_records.amount ELSE 0 END) -
            SUM(CASE WHEN journal_records.transaction_type = "CR" THEN journal_records.amount ELSE 0 END)) as net_amount
        ')
            ->groupBy('master_accounts.id', 'master_accounts.mst_ac_head')
            ->get();
        $liability_results = JournalRecord::whereBetween('journal_records.journal_date', [$from_date, $to_date])
            ->where('journal_records.office_id', Auth::user()->office_id)
            ->join('master_accounts', 'master_accounts.id', '=', 'journal_records.master_account_id')
            ->whereIn('master_accounts.mst_ac_type', ['LIABILITY'])
            ->selectRaw('
            master_accounts.id as master_accounts_id,
            master_accounts.mst_ac_head as master_accounts_name,
            SUM(CASE WHEN journal_records.transaction_type = "CR" THEN journal_records.amount ELSE 0 END) as total_cr,
            SUM(CASE WHEN journal_records.transaction_type = "DR" THEN journal_records.amount ELSE 0 END) as total_dr,
            (SUM(CASE WHEN journal_records.transaction_type = "CR" THEN journal_records.amount ELSE 0 END) -
            SUM(CASE WHEN journal_records.transaction_type = "DR" THEN journal_records.amount ELSE 0 END)) as net_amount
        ')
            ->groupBy('master_accounts.id', 'master_accounts.mst_ac_head')
            ->get();
        return view('backend.accounts-report.balance-sheet', compact('office_id', 'offices', 'assets_results', 'from_date', 'to_date', 'liability_results'));
    }


    public function balance_sheet(Request $request)
    {
        $date = $request->from ? $this->dateFormat($request->from) :  date('Y-m-d');
        $from = date('Y-01-01', strtotime($date));
        $to = $date;
        $company_id = $request->company_id?$request->company_id:null;
        // dd($company_id);


        // Current Asset
        $current_assets = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('journal_records.compnay_id', $company_id)
            ->where('account_heads.account_type_id', 1)
            ->where('account_heads.fld_definition', 'like', '%Current/Operating Asset%')
            ->where('journal_records.journal_date', '<=', $to)
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) -
                        SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) AS balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();

        $current_asset_balance = $current_assets->sum('balance');

        // Fixed Asset
        $fixed_assets = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('journal_records.compnay_id', $company_id)
            ->where('account_heads.account_type_id', 1)
            ->where('account_heads.fld_definition', 'like', '%Fixed Asset%')
            ->where('journal_records.journal_date', '<=', $to)
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) -
                        SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) AS balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();

        $fixed_asset_balance = $fixed_assets->sum('balance');

        // Other Asset
        $other_assets = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('journal_records.compnay_id', $company_id)
            ->where('account_heads.account_type_id', 1)
            ->where(function ($query) {
                $query->where('account_heads.fld_definition', 'not like', '%Fixed Asset%')
                    ->where('account_heads.fld_definition', 'not like', '%Current/Operating Asset%');
            })
            ->where('journal_records.journal_date', '<=', $to)
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) -
                        SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) AS balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();

        $other_asset_balance = $other_assets->sum('balance');
        $total_asset = $current_asset_balance + $fixed_asset_balance + $other_asset_balance;

        // Current Liability
        $current_liability = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('journal_records.compnay_id', $company_id)
            ->where('account_heads.account_type_id', 2)
            ->where('account_heads.fld_definition', 'like', '%Current Liability%')
            ->where('journal_records.journal_date', '<=', $to)
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) -
                        SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) AS balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();

        $total_current_liability = $current_liability->sum('balance');

        // Non-Current Liability
        $non_current_liability = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('journal_records.compnay_id', $company_id)
            ->where('account_heads.account_type_id', 2)
            ->where('account_heads.fld_definition', 'like', '%Non-Current Liabilities%')
            ->where('journal_records.journal_date', '<=', $to)
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) -
                        SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) AS balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();

        $total_non_current_liability = $non_current_liability->sum('balance');

        // Owners Equity
        $owners_equity = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('journal_records.compnay_id', $company_id)
            ->where('account_heads.account_type_id', 2)
            ->where('account_heads.fld_definition', 'like', '%Share Capital%')
            ->where('journal_records.journal_date', '<=', $to)
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) -
                        SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) AS balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();

        $total_owners_equity = $owners_equity->sum('balance');

        // Other Liabilities
        $other_liabilities = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('journal_records.compnay_id', $company_id)
            ->where('account_heads.account_type_id', 2)
            ->where(function ($query) {
                $query->where('account_heads.fld_definition', 'not like', '%Non-Current Liabilities%')
                    ->where('account_heads.fld_definition', 'not like', '%Share Capital%')
                    ->where('account_heads.fld_definition', 'not like', '%Current Liability%');
            })
            ->where('journal_records.journal_date', '<=', $to)
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) -
                        SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) AS balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();

        $other_liability_balance = $other_liabilities->sum('balance');

        // Retained Earnings
        $retained = DB::table('journal_records')
            ->where('journal_records.compnay_id', $company_id)
            ->whereIn('account_type_id', [1, 2])
            ->where('journal_records.journal_date', '<', $from)
            ->select(DB::raw("
                SUM(CASE WHEN journal_records.transaction_type = 'DR' AND journal_records.account_type_id = 1 THEN journal_records.amount ELSE 0 END) -
                SUM(CASE WHEN journal_records.transaction_type = 'CR' AND journal_records.account_type_id = 1 THEN journal_records.amount ELSE 0 END) AS preAsset,

                SUM(CASE WHEN journal_records.transaction_type = 'CR' AND journal_records.account_type_id = 2 THEN journal_records.amount ELSE 0 END) -
                SUM(CASE WHEN journal_records.transaction_type = 'DR' AND journal_records.account_type_id = 2 THEN journal_records.amount ELSE 0 END) AS preLiability
            "))
            ->first();

        $retained_earning = $retained->preAsset - $retained->preLiability;
        $total_liability = $total_current_liability + $total_non_current_liability + $total_owners_equity + $other_liability_balance + $retained_earning;
        $current_profit = $total_asset - $total_liability;
        $companies = Subsidiary::get();
        return view('backend.accounting-reports.balance-sheet.index', compact(
            'from',
            'to',
            'current_assets',
            'current_asset_balance',
            'fixed_assets',
            'fixed_asset_balance',
            'other_assets',
            'other_asset_balance',
            'total_asset',
            'current_liability',
            'total_current_liability',
            'non_current_liability',
            'total_non_current_liability',
            'owners_equity',
            'total_owners_equity',
            'other_liabilities',
            'other_liability_balance',
            'total_liability',
            'retained_earning',
            'current_profit',
            'company_id',
            'companies'
        ));
    }

    public function head_ledger_show(Request $request)
    {
        $from_date = $request->from_date ?? date('Y-m-d');
        $to_date = $request->to_date ?? date('Y-m-d');
        $head_id = $request->id;
        $column = $request->column;
        $company_id = $request->company_id;
        if ($column == 'account_head_id') {
            $acc_head = AccountHead::find($head_id);
            $records = $this->head_details_sql($request,  $head_id);
        } else {
            $acc_head = AccountSubHead::find($head_id);
            $records = $this->sub_head_details_sql($request, $head_id);
            $head_id = $acc_head->account_head_id;
        }

        return view('backend.accounts-report.head-ledger-show', compact('acc_head', 'records', 'from_date', 'to_date', 'column', 'company_id', 'head_id'));
    }

    public function headDetailsSearch(Request $request)
    {

        $head_id = $request->head_id;

        $column = $request->column;
        if ($column == 'account_head_id') {
            $acc_head = AccountHead::find($head_id);
            $records = $this->head_details_sql($request,  $head_id);
        } else {
            $acc_head = AccountSubHead::find($head_id);
            $records = $this->sub_head_details_sql($request, $head_id);
        }
        return view('backend.accounts-report.head-ledger-show-search', compact('records'));
    }


    public function sale_reports(Request $request)
    {

        $from = $request->from ? $this->dateFormat($request->from) : null;
        $to = $request->to ? $this->dateFormat($request->to) : null;
        $year = $request->year;
        $month = $request->month;
        $search_query = $request->search_query;
        $order_by = $request->order_by ?? 'invoice_no-ASC';
        $office_id = $request->office_id;

        if (!$office_id) {
            $office_id = auth()->user()->office_id;
        }

        $selected_office = Office::find($office_id);
        $offices = Office::orderBy('name')->get();

        if ($order_by) {
            list($column, $direction) = explode('-', $order_by);
        }
        $taxInvoices = DB::table('job_project_invoices as jpi')
            ->selectRaw('YEAR(jpi.date) AS year, MONTH(jpi.date) AS month')
            ->selectRaw('SUM(jpi.total_budget) AS total_amount')
            ->selectRaw('SUM(jpi.paid_amount) AS paid_amount')
            ->selectRaw('SUM(jpi.due_amount) AS due_amount')
            ->when($year, fn($query) => $query->whereYear('date', $year))
            ->when($from && $to, fn($query) => $query->whereBetween('date', [$from, $to]))
            ->when($from && !$to, fn($query) => $query->whereDate('date', $from))
            ->when(!$from && $to, fn($query) => $query->whereDate('date', $to))
            ->when($month  && !$search_query, fn($query) => $query->whereMonth('jpi.date', $month))
            ->when($search_query, fn($query) => $query->where('invoice_no', 'like', '%' . $search_query . '%'))
            ->groupByRaw('YEAR(jpi.date), MONTH(jpi.date)')
            ->orderByRaw('YEAR(jpi.date) DESC, MONTH(jpi.date) ASC')
            ->paginate(100);

        $records = $taxInvoices->getCollection()->map(function ($item) use ($from, $to, $search_query, $office_id) {
            $sql = "
                SELECT
                    jpi.date,
                    jpi.id,
                    jpi.invoice_no,
                    pi.pi_name,
                    jpi.total_budget as total_amount,
                    jpi.paid_amount,
                    jpi.due_amount
                FROM job_project_invoices as jpi
                JOIN party_infos as pi ON pi.id = jpi.customer_id
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

        $taxInvoices = new \Illuminate\Pagination\LengthAwarePaginator(
            $records,
            $taxInvoices->total(),
            $taxInvoices->perPage(),
            $taxInvoices->currentPage(),
            ['path' => \Request::url(), 'query' => \Request::query()] // Preserve the URL and query parameters
        );

        return view('backend.accounts-report.sale.sale-reports', compact('from', 'to',  'taxInvoices', 'search_query', 'year', 'order_by', 'month', 'offices', 'selected_office'));
    }

    public function petty_cash_report(Request $request)
    {
        Gate::authorize('Petty_Cash_Report');
        $office_id = 1;

        if (!$office_id) {
            $office_id = auth()->user()->office_id;
        }

        $offices = Office::orderBy('name')->get();
        // $transections = DB::select("
        // SELECT id AS id, customer_id AS party_id, date AS date, invoice_no AS transection_no, invoice_type AS invoice_type, total_budget AS amount, 'Invoice' AS data_from FROM invoices WHERE invoice_type != 'Direct Invoice' AND 'id' < 0
        // UNION
        // SELECT id AS id, party_id AS party_id, date AS date, receipt_no AS transection_no, 'Receipt' AS invoice_type,total_amount AS amount ,'Receipt' AS data_from FROM receipts WHERE 'id' < 0
        // ;
        // ");

        // $fundAllocations = DB::table('fund_allocations')->where('approved', 1)
        //     ->when('office_id', fn($query) => $query->where('office_id', $office_id))
        //     ->leftJoin('employees', 'fund_allocations.paid_by', '=', 'employees.id')
        //     ->select('id', 'amount', 'date', 'account_id_from', 'transaction_number', 'paid_by', 'employees.full_name', DB::raw('"fund_allocations" as source'), 'created_at');
        $fundAllocations = DB::table('fund_allocations')
        ->where('approved', 1)
        ->when($office_id, fn($query) => $query->where('office_id', $office_id))
        ->leftJoin('employees', 'fund_allocations.paid_by', '=', 'employees.id')
        ->select(
            'fund_allocations.id',
            'fund_allocations.amount',
            'fund_allocations.date',
            'fund_allocations.account_id_from',
            'fund_allocations.transaction_number',
            'fund_allocations.paid_by',
            'employees.full_name',
            DB::raw('NULL as pi_name'), // ✅ dummy column for union compatibility
            DB::raw('"fund_allocations" as source'),
            'fund_allocations.created_at'
        );

        // $purchaseExpenses = DB::table('purchase_expenses')->where('pay_mode', 'Petty Cash')
        //     ->select('id', 'total_amount', 'date', 'pay_mode', 'purchase_no', DB::raw('"purchase_expenses" as source'), 'created_at');

        // dd($fundAllocations);

        // $payment_voucher = DB::table('payments')->where('pay_mode', 'Petty Cash')
        // ->when('office_id', fn($query) => $query->where('office_id', $office_id))
        // ->leftJoin('employees', 'payments.paid_by', '=', 'employees.id')
        // ->select('id', 'total_amount', 'date', 'pay_mode', 'payment_no', 'paid_by', 'employees.full_name', DB::raw('"payments" as source'), 'created_at');
        $payment_voucher = DB::table('payments')
        ->where('pay_mode', 'Petty Cash')
        ->when($office_id, fn($query) => $query->where('payments.office_id', $office_id))
        ->leftJoin('employees', 'payments.paid_by', '=', 'employees.id')
        ->leftJoin('party_infos', 'payments.party_id', '=', 'party_infos.id')
        ->select(
            'payments.id',
            'payments.total_amount',
            'payments.date',
            'payments.pay_mode',
            'payments.payment_no',
            'payments.paid_by',
            'employees.full_name',
            'party_infos.pi_name',
            DB::raw('"payments" as source'),
            'payments.created_at'
        );

        // dd($payment_voucher);

        if ($request->date && $request->date2) {
            $fundAllocations = $fundAllocations->whereBetween('date', [$this->dateFormat($request->date), $this->dateFormat($request->date2)]);
            // $purchaseExpenses = $purchaseExpenses->whereBetween('date', [$this->dateFormat($request->date), $this->dateFormat($request->date2)]);
            $payment_voucher = $payment_voucher->whereBetween('date', [$this->dateFormat($request->date), $this->dateFormat($request->date2)]);
        }
        if ($request->paid_by) {
            $fundAllocations = $fundAllocations->where('paid_by', $request->paid_by);
            $payment_voucher = $payment_voucher->where('paid_by', $request->paid_by);
        }
        $petty_cashs = $fundAllocations->union($payment_voucher)
            ->orderBy('created_at', 'asc')
            ->get();
        // dd($petty_cashs);
        $employee = Employee::orderBy('full_name')->whereNotIn('division', [4])->get();
        return view('backend.accounts-report.petty-cash-report', compact('petty_cashs', 'offices', 'office_id', 'employee'));
    }
    public function bank_account_report(Request $request)
    {
        $office_id = 1;

        if (!$office_id) {
            $office_id = auth()->user()->office_id;
        }

        $offices = Office::orderBy('name')->get();
        $bank_account = JournalRecord::where('account_head_id', 2);
        if ($request->sub_account_head) {
            $bank_account = $bank_account->where('sub_account_head_id', $request->sub_account_head);
        }
        if ($request->date && $request->date2) {
            $bank_account = $bank_account->whereBetween('journal_date', [$this->dateFormat($request->date), $this->dateFormat($request->date2)]);
        }
        $bank_account = $bank_account->get();
        $banks = AccountSubHead::where('account_head_id',2)->get();
        return view('backend.accounts-report.bank-account-report', compact('bank_account', 'offices', 'office_id', 'banks'));
    }
    public function purchase_reports(Request $request)
    {
        $from = null;
        $to = null;
        $date = null;
        $office_id = $request->office_id;

        if (!$office_id) {
            $office_id = auth()->user()->office_id;
        }

        $offices = Office::orderBy('name')->get();

        if ($request->from) {
            $from = $this->dateFormat($request->from);
            if ($request->to) {
                $to = $this->dateFormat($request->to);
            } else {
                $to = date('Y-m-d');
            }
        }
        if ($request->date) {
            $date = $this->dateFormat($request->date);
        }
        $purchases = PurchaseExpense::OrderBy('id', 'desc')->where('office_id', $office_id);
        if ($from && $to) {
            $purchases = $purchases->whereBetween('date', [$from, $to]);
        } elseif ($date) {
            $purchases = $purchases->where('date', $date);
        } else {
            $purchases = $purchases->where('date', date('Y-m-d'));
        }
        $purchases = $purchases->paginate(25);
        return view('backend.accounts-report.purchase-reports', compact('from', 'to', 'date', 'purchases', 'offices', 'office_id'));
    }
    public function receivable_reports(Request $request)
    {
        $from = null;
        $to = null;
        $date = null;
        $party_info = null;
        if ($request->from) {
            $from = $this->dateFormat($request->from);
            if ($request->to) {
                $to = $this->dateFormat($request->to);
            } else {
                $to = date('Y-m-d');
            }
        }
        if ($request->date) {
            $date = $this->dateFormat($request->date);
        }
        if ($request->party_id) {
            $party_info = $request->party_id;
        }
        $partys = PartyInfo::whereIn('office_id', [0, Auth::user()->office_id])->get();
        return view('backend.accounts-report.receivable-reports', compact('partys', 'from', 'to', 'date', 'party_info'));
    }
    public function payable_reports(Request $request)
    {
        $from = null;
        $to = null;
        $date = null;
        $party_info = null;
        if ($request->from) {
            $from = $this->dateFormat($request->from);
            if ($request->to) {
                $to = $this->dateFormat($request->to);
            } else {
                $to = date('Y-m-d');
            }
        }
        if ($request->date) {
            $date = $this->dateFormat($request->date);
        }
        if ($request->party_id) {
            $party_info = $request->party_id;
        }
        $partys = PartyInfo::whereIn('office_id', [0, Auth::user()->office_id])->get();
        return view('backend.accounts-report.payable-reports', compact('partys', 'from', 'to', 'date', 'party_info'));
    }


    public function accountsReceivable(Request $request, $type)
    {
        if ($type == 'receivable') {
            Gate::authorize('Account_Receivable');
            $table = "job_project_invoices";
            $party_column = 'customer_id';
            $amount_column = 'total_budget';
            $number_column = 'invoice_no';
            $parties = PartyInfo::where('pi_type', 'customer')->get();
        } else {
            Gate::authorize('Account_Payable');

            $table = "purchase_expenses";
            $party_column = 'party_id';
            $amount_column = 'total_amount';
            $number_column = 'purchase_no';
            $parties = PartyInfo::where('pi_type', 'Supplier')->get();
        }

        $current_month = date('Y-m-d');
        $last_3_month = date('Y-m-d', strtotime('-3 months'));
        $last_6_month = date('Y-m-d', strtotime('-6 months'));
        $last_12_month = date('Y-m-d', strtotime('-12 months'));
        $search = $request->search;
        $search_query = $request->search_query;
        $from_date = $request->from_date ? $this->dateFormat($request->from_date) : null;
        $to_date = $request->to_date ? $this->dateFormat($request->to_date) : null;
        $order_by = $request->order_by ?? 'due_amount-DESC';

        $office_id = 1;
        if (!$office_id) {
            $office_id = auth()->user()->office_id;
        }

        $selected_office = Office::find($office_id);
        $offices = Office::orderBy('name')->get();

        if ($order_by) {
            list($column, $direction) = explode('-', $order_by);
        }

        $base_sql = "
            SELECT
                pi.pi_name,
                pi.id,
                sum(t.due_amount) as due_amount
            FROM
            party_infos as pi
            JOIN {$table} as t ON t.{$party_column} = pi.id

            WHERE
                t.due_amount > 0
            AND t.office_id = {$office_id}
        ";

        $bindings = [];

        if ($search_query) {
            $base_sql .= " AND t.{$number_column} = :search_query";
            $bindings['search_query'] = $search_query;
        }

        if ($search) {
            $base_sql .= " AND pi.id = :search";
            $bindings['search'] = $search;
        }


        if ($from_date && $to_date) {
            $base_sql .= " AND t.date BETWEEN :from_date AND :to_date";
            $bindings['from_date'] = $from_date;
            $bindings['to_date'] = $to_date;
        } elseif ($from_date) {
            $base_sql .= " AND t.date = :from_date";
            $bindings['from_date'] = $from_date;
        } elseif ($to_date) {
            $base_sql .= " AND jpi.date = :to_date";
            $bindings['to_date'] = $to_date;
        }
        $three_month_sql = $base_sql . "
            AND t.date BETWEEN :start_month AND :end_month
            GROUP BY pi.id, pi.pi_name
            ORDER BY pi.pi_name ASC
        ";

        $three_month_bindings = array_merge($bindings, [
            'start_month' => $last_3_month,
            'end_month' => $current_month
        ]);
        $three_month_data = DB::select($three_month_sql, $three_month_bindings);

        $six_month_sql = $base_sql . "
            AND t.date BETWEEN :start_month AND :end_month
            GROUP BY pi.id, pi.pi_name
            ORDER BY pi.pi_name ASC
        ";
        $six_month_bindings = array_merge($bindings, [
            'start_month' => $last_6_month,
            'end_month' => $last_3_month
        ]);
        $six_month_data = DB::select($six_month_sql, $six_month_bindings);

        $twelve_month_sql = $base_sql . "
            AND t.date BETWEEN :start_month AND :end_month
            GROUP BY pi.id, pi.pi_name
            ORDER BY pi.pi_name ASC
        ";
        $twelve_month_bindings = array_merge($bindings, [
            'start_month' => $last_12_month,
            'end_month' => $last_6_month
        ]);
        $twelve_month_data = DB::select($twelve_month_sql, $twelve_month_bindings);

        $old_data_sql = $base_sql . "
            AND t.date <= :end_month
            GROUP BY pi.id, pi.pi_name
            ORDER BY pi.pi_name ASC
        ";
        $old_data_bindings = array_merge($bindings, [
            'end_month' => $last_12_month
        ]);
        $old_month_data = DB::select($old_data_sql, $old_data_bindings);

        if ($request->ajax()) {
            return view('backend.ajax.receivable', compact(
                'three_month_data',
                'six_month_data',
                'twelve_month_data',
                'old_month_data',
                'search',
                'search_query',
                'parties',
                'from_date',
                'to_date',
                'order_by',
                'type',
                'offices',
                'selected_office',
            ));
        }

        return view('backend.accounts-report.accounts-receivable', compact(
            'three_month_data',
            'six_month_data',
            'twelve_month_data',
            'old_month_data',
            'search',
            'search_query',
            'parties',
            'from_date',
            'to_date',
            'order_by',
            'type',
            'offices',
            'selected_office',
        ));
    }

    public function accountsReceivableDetails(Request $request, $type, PartyInfo $party)
    {
        if ($type == 'receivable') {
            $table = "job_project_invoices";
            $party_column = 'customer_id';
            $amount_column = 'total_budget';
            $number_column = 'invoice_no';
        } else {
            $table = "purchase_expenses";
            $party_column = 'party_id';
            $amount_column = 'total_amount';
            $number_column = 'purchase_no';
        }

        $current_month = date('Y-m-d');
        $last_3_month = date('Y-m-d', strtotime('-3 months'));
        $last_6_month = date('Y-m-d', strtotime('-6 months'));
        $last_12_month = date('Y-m-d', strtotime('-12 months'));
        $type = $request->type;
        $search_query = $request->search_query;
        $from_date = $request->from_date ? $this->dateFormat($request->from_date) : null;
        $to_date = $request->to_date ? $this->dateFormat($request->to_date) : null;
        $office_id = $request->office_id;
        $sql = "
            SELECT
                t.{$amount_column} as total_budget,  -- Dynamic table 1210 1181 line
                t.due_amount,
                t.paid_amount,
               t.{$number_column} as invoice_no, -- invoice_no / purchase_no
                t.date,
                pi.pi_name  -- Party info
            FROM
                {$table} AS t
            LEFT JOIN
                party_infos AS pi ON pi.id = t.{$party_column}
            WHERE
                t.{$party_column} = :party_id
                AND t.due_amount > 0
                AND t.office_id = {$office_id}
        ";

        $binding = ['party_id' => $party->id];

        if ($type === 'three_month') {
            $sql .= " AND t.date BETWEEN :start_month AND :end_month";
            $binding['start_month'] = $last_3_month;
            $binding['end_month'] = $current_month;
        } elseif ($type === 'six_month') {
            $sql .= " AND t.date BETWEEN :start_month AND :end_month";
            $binding['start_month'] = $last_6_month;
            $binding['end_month'] = $last_3_month;
        } elseif ($type == 'twelve_month') {
            $sql .= " AND  t.date BETWEEN :start_month AND :end_month";
            $binding['start_month'] = $last_12_month;
            $binding['end_month'] = $last_6_month;
        } else {
            $sql .= " AND  t.date  <=  :end_month";
            $binding['end_month'] = $last_12_month;
        }

        if ($search_query) {
            $sql .= " AND  t.invoice_no = :search_query";
            $binding['search_query'] = $search_query;
        }

        if ($to_date && $from_date) {
            $sql .= " AND jbi.date BETWEEN :from_date AND :to_date";
            $binding['from_date'] = $from_date;
            $binding['to_date'] = $to_date;
        } elseif ($to_date) {
            $sql .= " AND t.date = :to_date";
            $binding['to_date'] = $to_date;
        } elseif ($from_date) {
            $sql .= " AND t.date = :from_date";
            $binding['from_date'] = $from_date;
        }

        $data = DB::select($sql, $binding);

        return view('backend.accounts-report.accounts-receivable-details', compact('data'));
    }

    public function accountsReceivablePrint(Request $request)
    {
        $office_id = $request->office_id;
        $current_month = date('Y-m-d');
        $last_3_month = date('Y-m-d', strtotime('-3 months'));
        $last_6_month = date('Y-m-d', strtotime('-6 months'));
        $last_12_month = date('Y-m-d', strtotime('-12 months'));
        $direction = 'DESC';
        $base_sql = "
            SELECT
                jpi.id,
                jpi.total_budget, -- jpi represents job_project_invoices
                jpi.due_amount,
                jpi.paid_amount,
                jpi.invoice_no,
                jpi.date,
                jpi.customer_id,
                pi.pi_name -- pi represents party_info
            FROM
                job_project_invoices AS jpi
            LEFT JOIN
                party_infos AS pi ON pi.id = jpi.customer_id
            WHERE
                jpi.due_amount > 0
        ";
        $bindings = [];

        $three_month_sql = $base_sql . "
            AND jpi.date BETWEEN :start_month AND :end_month
            ORDER BY pi.pi_name ASC, date ASC, due_amount DESC
        ";

        $three_month_bindings = array_merge($bindings, [
            'start_month' => $last_3_month,
            'end_month' => $current_month
        ]);
        $three_month_data = DB::select($three_month_sql, $three_month_bindings);

        $six_month_sql = $base_sql . "
            AND jpi.date BETWEEN :start_month AND :end_month
            ORDER BY pi.pi_name ASC, date ASC, due_amount DESC
        ";
        $six_month_bindings = array_merge($bindings, [
            'start_month' => $last_6_month,
            'end_month' => $last_3_month
        ]);
        $six_month_data = DB::select($six_month_sql, $six_month_bindings);

        $twelve_month_sql = $base_sql . "
            AND jpi.date BETWEEN :start_month AND :end_month
            ORDER BY pi.pi_name ASC, date ASC, due_amount DESC
        ";
        $twelve_month_bindings = array_merge($bindings, [
            'start_month' => $last_12_month,
            'end_month' => $last_6_month
        ]);
        $twelve_month_data = DB::select($twelve_month_sql, $twelve_month_bindings);

        $old_data_sql = $base_sql . "
            AND jpi.date <= :end_month
            ORDER BY pi.pi_name ASC, date ASC, due_amount DESC
        ";
        $old_data_bindings = array_merge($bindings, [
            'end_month' => $last_12_month
        ]);

        $old_month_data = DB::select($old_data_sql, $old_data_bindings);

        return view('check-print');

        return view('backend.accounts-report.accounts-receivable-print', compact(
            'three_month_data',
            'six_month_data',
            'twelve_month_data',
            'old_month_data',
        ));
    }

    public function accountsReceivablePdf(Request $request, $type)
    {

        $office = Office::find(1);
        if ($type == 'receivable') {
            $table2 = "job_project_invoices";
            $party_column = 'customer_id';
            $party_relation_column = 'pi_code';
            $pdf_title = " Account Receivable Report " . date('d/m/Y');
        } else {
            $table2 = "purchase_expenses";
            $party_column = 'party_id';
            $party_relation_column = 'id';
            $pdf_title = " Account Payable Report " . date('d/m/Y');
        }

        [$three_month_data, $six_month_data, $twelve_month_data, $old_month_data] = $this->pdfSql($table = 'party_infos', $table2, $party_column, $party_relation_column, $office->id);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $path = public_path('img/zikash-logo.png');
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64Image = 'data:image/' . $ext . ';base64,' . base64_encode($data);

        if (file_exists(public_path('storage/upload/logo/' . $office->logo))) {
            $path = public_path('storage/upload/logo/' . $office->logo);
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $office_logo = 'data:image/' . $ext . ';base64,' . base64_encode($data);
        } else {
            $office_logo = null;
        }

        $html = view('backend.accounts-report.receivable.pdf', [
            'three_month_data' => $three_month_data,
            'six_month_data'   => $six_month_data,
            'twelve_month_data' => $twelve_month_data,
            'old_month_data'    => $old_month_data,
            'logo'             => $base64Image,
            'pdf_title'         => $pdf_title,
            'type'              => $type,
            'office_logo'  => $office_logo,
            'office'       => $office,
        ])->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("receivable-" . date('d-m-Y'), array("Attachment" => 0));
    }

    public function accountsReceivableExtendedPdf(Request $request, $type)
    {
        if ($type == 'receivable') {
            $pdf_title = " Account Receivable Report " . date('d/m/Y');
            $table2 = "job_project_invoices";
            $party_column = 'customer_id';
            $party_relation_column = 'pi_code';
            $amount_column = 'total_budget';
            $number_column = 'invoice_no';
        } else {
            $pdf_title = " Account Payable Report " . date('d/m/Y');
            $table2 = "purchase_expenses";
            $party_column = 'party_id';
            $party_relation_column = 'id';
            $amount_column = 'total_amount';
            $number_column = 'purchase_no';
        }

        $office = Office::find(1);


        [$three_month_data, $six_month_data, $twelve_month_data, $old_month_data] = $this->extendedpdfSql($table = 'party_infos', $table2, $party_column,  $party_relation_column, $amount_column, $number_column, $office->id);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $path = public_path('img/zikash-logo.png');
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64Image = 'data:image/' . $ext . ';base64,' . base64_encode($data);

        if (file_exists(public_path('storage/upload/logo/' . $office->logo))) {
            $path = public_path('storage/upload/logo/' . $office->logo);
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $office_logo = 'data:image/' . $ext . ';base64,' . base64_encode($data);
        } else {
            $office_logo = null;
        }

        $html = view('backend.accounts-report.receivable.extended-pdf', [
            'three_month_data' => $three_month_data,
            'six_month_data'   => $six_month_data,
            'twelve_month_data' => $twelve_month_data,
            'old_month_data'    => $old_month_data,
            'image'             => $base64Image,
            'number_column'     => $number_column,
            'pdf_title'         => $pdf_title,
            'type'              => $type,
            'office'            => $office,
            'office_logo'       => $office_logo,
        ])->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($type . '-report-' . date('d-m-Y'), array("Attachment" => 0));
    }

    public function accountsReceivableExcel(Request $request, $type)
    {
        if ($type == 'receivable') {
            $table2 = "job_project_invoices";
            $party_column = 'customer_id';
            $party_relation_column = 'pi_code';
        } else {
            $table2 = "purchase_expenses";
            $party_column = 'party_id';
            $party_relation_column = 'id';
        }
        $office_id = $request->office_id;
        [$three_month, $six_month, $twelve_month, $old] = $this->pdfSql($table = 'party_infos', $table2, $party_column, $party_relation_column, $office_id);

        $data = [
            'three_month' => $three_month,
            'six_month'  => $six_month,
            'twelve_month' => $twelve_month,
            'old' => $old,
        ];

        return Excel::download(new ReceivableExport($data), $type . '-' . date('d-m-y') . '.' . 'xlsx');
    }

    public function accountsReceivableExtendedExcel(Request $request, $type)
    {
        if ($type == 'receivable') {
            $table2 = "job_project_invoices";
            $party_column = 'customer_id';
            $party_relation_column = 'pi_code';
            $amount_column = 'total_amount';
            $number_column = 'invoice_no';
        } else {
            $table2 = "purchase_expenses";
            $party_column = 'party_id';
            $party_relation_column = 'id';
            $amount_column = 'total_amount';
            $number_column = 'purchase_no';
        }
        $office_id = $request->office_id;
        [$three_month, $six_month, $twelve_month, $old] = $this->extendedpdfSql($table = 'party_infos', $table2, $party_column, $party_relation_column, $amount_column, $number_column, $office_id);
        $data = [
            'three_month' => $three_month,
            'six_month'  => $six_month,
            'twelve_month' => $twelve_month,
            'old' => $old,
            'column' =>  $number_column,
        ];

        return Excel::download(new ExtendedReceivableExport($data), $type . '-extended-' . date('d-m-Y') . '.' . 'xlsx');
    }

    private function extendedpdfSql($table1, $table2, $party_column, $party_relation_column, $amount_column, $number_column, $office_id)
    {
        $current_month = date('Y-m-d');
        $last_3_month = date('Y-m-d', strtotime('-3 months'));
        $last_6_month = date('Y-m-d', strtotime('-6 months'));
        $last_12_month = date('Y-m-d', strtotime('-12 months'));

        $three_month_data = DB::table($table1 . ' as pi')
            ->join($table2 . ' as t', 't.' . $party_column, '=', 'pi.' . $party_relation_column)
            ->select('pi.pi_name', 'pi.id', DB::raw('sum(t.due_amount) as due_amount'))
            ->where('t.due_amount', '>', 0)
            // ->where('t.office_id', $office_id)
            ->whereBetween('t.date', [$last_3_month, $current_month])
            ->groupBy('pi.id', 'pi.pi_name')
            ->orderBy('pi.pi_name')
            ->get()
            ->map(function ($data) use ($table1, $table2, $last_3_month, $current_month, $party_column, $party_relation_column, $amount_column, $number_column, $office_id) {
                $sql = "
                    SELECT
                        t.id,
                        t.{$amount_column} total_budget, -- t represents daynamic table
                        t.due_amount,
                        t.paid_amount,
                        t.{$number_column} as invoice_no, -- invoice_no / purchase_no
                        t.date,
                        pi.pi_name -- pi represents party_info
                    FROM
                        {$table2} AS t
                    LEFT JOIN
                        {$table1} AS pi ON pi.{$party_relation_column} = t.{$party_column}
                    WHERE
                        t.due_amount > 0
                        -- AND t.office_id = {$office_id}
                        AND pi.id = {$data->id}
                        AND t.date BETWEEN :start_month AND :end_month
                    ORDER BY t.date
                ";

                $items = DB::select($sql, ['start_month' => $last_3_month, 'end_month' => $current_month]);

                return [
                    'items' => $items,
                    'id' => $data->id,
                    'pi_name' => $data->pi_name,
                    'due_amount' => $data->due_amount,
                ];
            });



        $six_month_data = DB::table($table1 . ' as pi')
            ->join($table2 . ' as t', 't.' . $party_column, '=', 'pi.id')
            ->select('pi.pi_name', 'pi.id', DB::raw('sum(t.due_amount) as due_amount'))
            ->where('t.due_amount', '>', 0)
            // ->where('t.office_id', $office_id)
            ->whereBetween('t.date', [$last_6_month, $last_3_month])
            ->groupBy('pi.id', 'pi.pi_name')
            ->orderBy('pi.pi_name', 'asc')
            ->get()
            ->map(function ($data) use ($table1, $table2, $last_3_month, $last_6_month, $party_column, $amount_column, $number_column, $office_id) {
                $sql = "
                    SELECT
                        t.id,
                        t.{$amount_column} as total_budget, -- t represents daynamic table
                        t.due_amount,
                        t.paid_amount,
                        t.{$number_column} as invoice_no, -- invoice_no / purchase_no
                        t.date,
                        pi.pi_name -- pi represents party_info
                    FROM
                        {$table2} AS t
                    LEFT JOIN
                        {$table1} AS pi ON pi.id = t.{$party_column}
                    WHERE
                        t.due_amount > 0
                        -- AND t.office_id = {$office_id}
                        AND t.{$party_column} = {$data->id}
                        AND t.date BETWEEN :start_date AND :end_date
                    ORDER BY t.date
                ";


                $items = DB::select($sql, ['start_date' => $last_6_month, 'end_date' => $last_3_month]);

                return [
                    'items' => $items,
                    'id' => $data->id,
                    'pi_name' => $data->pi_name,
                    'due_amount' => $data->due_amount,
                ];
            });

        $twelve_month_data = DB::table($table1 . ' as pi')
            ->join($table2 . ' as t', 't.' . $party_column, '=', 'pi.id')
            ->select('pi.pi_name', 'pi.id', DB::raw('sum(t.due_amount) as due_amount'))
            ->where('t.due_amount', '>', 0)
            // ->where('t.office_id', $office_id)
            ->whereBetween('t.date', [$last_12_month, $last_6_month])
            ->groupBy('pi.id', 'pi.pi_name')
            ->orderBy('pi.pi_name', 'asc')
            ->get()
            ->map(function ($data) use ($table1, $table2, $last_12_month, $last_6_month, $party_column, $amount_column, $number_column, $office_id) {
                $sql = "
                    SELECT
                        t.id,
                        t.{$amount_column} total_budget, -- t represents daynamic table
                        t.due_amount,
                        t.paid_amount,
                         t.{$number_column} as invoice_no, -- invoice_no / purchase_no
                        t.date,
                        pi.pi_name -- pi represents party_info
                    FROM
                        {$table2} AS t
                    LEFT JOIN
                        {$table1} AS pi ON pi.id = t.{$party_column}
                    WHERE
                        t.due_amount > 0
                        -- AND t.office_id = {$office_id}
                        AND t.{$party_column} = {$data->id}
                        AND t.date BETWEEN :start_date AND :end_date
                    ORDER BY t.date
                ";

                $items = DB::select($sql, ['start_date' => $last_12_month, 'end_date' => $last_6_month]);

                return [
                    'items' => $items,
                    'id' => $data->id,
                    'pi_name' => $data->pi_name,
                    'due_amount' => $data->due_amount,
                ];
            });

        $old_month_data = DB::table($table1 . ' as pi')
            ->join($table2 . ' as t', 't.' . $party_column, '=', 'pi.id')
            ->select('pi.pi_name', 'pi.id', DB::raw('sum(t.due_amount) as due_amount'))
            ->where('t.due_amount', '>', 0)
            ->where('t.date', '<=', $last_12_month)
            // ->where('t.office_id', $office_id)
            ->groupBy('pi.id', 'pi.pi_name')
            ->orderBy('pi.pi_name', 'asc')
            ->get()
            ->map(function ($data) use ($table1, $table2, $last_12_month, $party_column, $amount_column, $number_column, $office_id) {
                $sql = "
                    SELECT
                        t.id,
                        t.{$amount_column} as total_budget, -- t represents daynamic table
                        t.due_amount,
                        t.paid_amount,
                        t.{$number_column} as invoice_no, -- invoice_no / purchase_no,
                        t.date,
                        pi.pi_name -- pi represents party_info
                    FROM
                        {$table2} AS t
                    LEFT JOIN
                        {$table1} AS pi ON pi.id = t.{$party_column}
                    WHERE
                        t.due_amount > 0
                        -- AND t.office_id = {$office_id}
                        AND t.{$party_column} = {$data->id}
                        AND t.date <= :end_date
                    ORDER BY t.date
                ";
                $items = DB::select($sql, ['end_date' => $last_12_month]);

                return [
                    'items' => $items,
                    'id' => $data->id,
                    'pi_name' => $data->pi_name,
                    'due_amount' => $data->due_amount,
                ];
            });

        return [$three_month_data, $six_month_data, $twelve_month_data, $old_month_data];
    }


    private function pdfSql($table1, $table2, $party_column, $party_relation_column, $office_id)
    {
        $current_month = date('Y-m-d');
        $last_3_month = date('Y-m-d', strtotime('-3 months'));
        $last_6_month = date('Y-m-d', strtotime('-6 months'));
        $last_12_month = date('Y-m-d', strtotime('-12 months'));

        $base_sql = "
            SELECT
                pi.pi_name,
                pi.id,
                sum(t.due_amount) as due_amount
            FROM
            {$table1} as pi
            JOIN {$table2} as t ON t.{$party_column} = pi.{$party_relation_column}

            WHERE
                t.due_amount > 0
            -- AND t.office_id = {$office_id}
        ";

        $bindings = [];

        $three_month_sql = $base_sql . "
            AND t.date BETWEEN :start_month AND :end_month
            GROUP BY pi.id, pi.pi_name
            ORDER BY pi.pi_name
        ";

        $three_month_bindings = array_merge($bindings, [
            'start_month' => $last_3_month,
            'end_month' => $current_month
        ]);
        $three_month_data = DB::select($three_month_sql, $three_month_bindings);

        $six_month_sql = $base_sql . "
            AND t.date BETWEEN :start_month AND :end_month
            GROUP BY pi.id, pi.pi_name
            ORDER BY pi.pi_name ASC
        ";
        $six_month_bindings = array_merge($bindings, [
            'start_month' => $last_6_month,
            'end_month' => $last_3_month
        ]);
        $six_month_data = DB::select($six_month_sql, $six_month_bindings);

        $twelve_month_sql = $base_sql . "
            AND t.date BETWEEN :start_month AND :end_month
            GROUP BY pi.id, pi.pi_name
            ORDER BY pi.pi_name ASC
        ";
        $twelve_month_bindings = array_merge($bindings, [
            'start_month' => $last_12_month,
            'end_month' => $last_6_month
        ]);
        $twelve_month_data = DB::select($twelve_month_sql, $twelve_month_bindings);

        $old_data_sql = $base_sql . "
            AND t.date <= :end_month
            GROUP BY pi.id, pi.pi_name
            ORDER BY pi.pi_name ASC
        ";
        $old_data_bindings = array_merge($bindings, [
            'end_month' => $last_12_month
        ]);
        $old_month_data = DB::select($old_data_sql, $old_data_bindings);

        return [$three_month_data, $six_month_data, $twelve_month_data, $old_month_data];
    }
    public function missing_invoice_number(Request $request)
    {
        $from = $request->from ? $this->dateFormat($request->from) : null;
        $to = $request->to ? $this->dateFormat($request->to) : null;
        $year = $request->year;
        $month = $request->month;
        $search_query = $request->search_query;

        $invoice_group = [];

        $taxInvoices = DB::table('invoices as jpi')
            ->select(
                'jpi.date',
                'jpi.id',
                'jpi.invoice_no',
                'jpi.invoice_type',
                'pi.pi_name',
                'jpi.total_budget',
                'jpi.paid_amount',
                'jpi.due_amount'
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
            })->when($month, fn($query) => $query->whereMonth('jpi.date', $month))
            ->when($search_query, function ($query) use ($search_query) {
                $query->where('jpi.invoice_no', 'LIKE', '%' . $search_query . '%');
            })->when($year, fn($query) => $query->whereYear('jpi.date', $year))
            ->where('jpi.office_id', auth()->user()->office_id)
            ->orderBy('jpi.date', 'asc')
            ->paginate(1000)
            ->appends([
                'search_query' => $search_query,
                'from' => $from,
                'to' => $to,
                'month' => $month,
                'year' => $year,
            ]);

        $current_page = $taxInvoices->currentPage();

        DB::table('missing_invoices')->where('page_number', $current_page)->truncate();


        $invoice_group = $taxInvoices->getCollection()->map(function ($invoice) {

            $prefix = '';
            $number = '';

            $parts = str_split($invoice->invoice_no);
            foreach ($parts as $char) {
                if (ctype_alpha($char) && $number === '') {
                    $prefix .= $char;
                } elseif (ctype_digit($char)) {
                    $number .= $char;
                }
            }

            $data = [
                'date' => $invoice->date,
                'invoice_no' => $invoice->invoice_no,
                'id' => $invoice->id,
                'invoice_type' => $invoice->invoice_type,
                'pi_name' => $invoice->pi_name,
                'total_budget' => $invoice->total_budget,
                'paid_amount' => $invoice->paid_amount,
                'due_amount' => $invoice->due_amount,
                'prefix' => $prefix,
                'number' => $number ? (int)$number : null,
                'missing_invoices' => [],
            ];

            return $data;
        })->toArray();

        usort($invoice_group, function ($a, $b) {
            return $a['number'] <=> $b['number'];
        });

        foreach ($invoice_group as $key => $invoice) {
            $n = count($invoice_group);
            if ($key < $n - 1) {
                $next_invoice = $invoice_group[$key + 1];
                $second_prefix = $next_invoice['prefix'];
                $first_prefix = $invoice['prefix'];

                if (($first_prefix == $second_prefix || ($invoice['number'] + 1) != $next_invoice['number'])) {
                    $missing_count = abs($next_invoice['number'] - $invoice['number']);
                    for ($i = $invoice['number'] + 1; $i < $next_invoice['number']; $i++) {

                        $missing = $invoice['prefix'] . $i;
                        $exists = Invoice::where('invoice_no', $missing)->exists() ||
                            MissingInvoice::where('previus_invoice', $missing)->exists();

                        if (!$exists && $i != $next_invoice['number']) {
                            $exists = Invoice::select('invoice_no')->where('invoice_no', 'like', ($invoice['number'] + 1) . '%')->orderBy('invoice_no', 'desc')->first();
                        }

                        if ($missing_count < 30) {

                            if (!$exists) {
                                MissingInvoice::create([
                                    'invoice_number' =>  $invoice['prefix'] . ($invoice['number'] + 1),
                                    'previus_invoice' =>  $missing,
                                    'next_invoice' =>  $missing,
                                    'date' => date('Y-m-d'),
                                    'missing_date' =>  $missing,
                                    'page_number' => $current_page,
                                ]);

                                $invoice_group[$key]['missing_invoices'][] = $invoice['prefix'] . ($invoice['number'] + 1);
                            }
                        }
                    }
                }
            }
        }

        return view('backend.accounts-report.sale.missing_invoice', compact('search_query', 'taxInvoices', 'invoice_group', 'month', 'year', 'from', 'to'));
    }

    public function tax_reports(Request $request)
    {
        if ($request->from_date && $request->to_date) {
            $from_date = $this->dateFormat($request->from_date);
            $to_date = $this->dateFormat($request->to_date);
        } else {
            $from_date = date('Y-01-01');
            $to_date = date('Y-m-d');
        }
        $profitLosse_accountTypes = [
            ['account_type' => 'Sales Turnover', 'title' => 'Operating Revenue', 'type' => 'CR'],
            ['account_type' => 'Cost of Sales / Goods Sold', 'title' => 'Expenditure in Deriving Operating Revenue', 'type' => 'DR'],
            ['account_type' => 'Salaries, Benefits and Wages', 'title' => 'Salaries, Wages and Related Charges', 'type' => 'DR'],
            ['account_type' => 'Administrative Expense', 'title' => 'Non-Operating Expense', 'type' => 'DR'],
            ['account_type' => 'Depreciation and Amortization', 'title' => 'Depreciation and Amortisation', 'type' => 'DR'],
            ['account_type' => 'Fines and Penalties', 'title' => 'Fines and Penalties', 'type' => 'DR'],
            ['account_type' => 'Donations', 'title' => 'Donations', 'type' => 'DR'],
            ['account_type' => 'Food & Entertainment', 'title' => 'Client Entertainment Expenses', 'type' => 'DR'],
            ['account_type' => 'Other Administrative Expense', 'title' => 'Other Expneses', 'type' => 'DR'],
            ['account_type' => 'Other Income', 'title' => 'Non-Operating Revenue', 'type' => 'CR'],
            ['account_type' => 'Dividends received', 'title' => 'Dividends Received', 'type' => 'CR'],
            ['account_type' => 'Other non-operating revenue', 'title' => 'Other Non-operating Revenue', 'type' => 'CR'],
            ['account_type' => 'Interest Income', 'title' => 'Interest Income', 'type' => 'CR'],
            ['account_type' => 'Interest Expenditure', 'title' => 'Interest Expenditure', 'type' => 'CR'],
            ['account_type' => 'Gains disposal of assets', 'title' => 'Gains Disposal of Assets', 'type' => 'DR'],
            ['account_type' => 'Losses Disposal of Assets', 'title' => 'Losses Disposal of Assets', 'type' => 'CR'],
            ['account_type' => 'Foreign Exchange Gains', 'title' => 'Foreign Exchange Gains', 'type' => 'DR'],
            ['account_type' => 'Foreign Exchange Losses', 'title' => 'Foreign Exchange Losses', 'type' => 'CR'],
        ];
        $profitLosse_results = JournalRecord::whereBetween('journal_records.journal_date', [$from_date, $to_date])
            ->where('journal_records.office_id', Auth::user()->office_id)
            ->join('master_accounts', 'master_accounts.id', '=', 'journal_records.master_account_id')
            ->whereIn('master_accounts.mst_definition', array_column($profitLosse_accountTypes, 'account_type'))
            ->selectRaw('
            master_accounts.mst_definition as account_type,
            SUM(CASE WHEN journal_records.transaction_type = "DR" THEN journal_records.amount ELSE 0 END) as total_dr,
            SUM(CASE WHEN journal_records.transaction_type = "CR" THEN journal_records.amount ELSE 0 END) as total_cr,
            (SUM(CASE WHEN journal_records.transaction_type = "DR" THEN journal_records.amount ELSE 0 END) -
            SUM(CASE WHEN journal_records.transaction_type = "CR" THEN journal_records.amount ELSE 0 END)) as net_amount
        ')
            ->groupBy('master_accounts.mst_definition')
            ->get();

        $profitLosse_results = collect($profitLosse_accountTypes)->map(function ($account) use ($profitLosse_results) {
            $result = $profitLosse_results->firstWhere('account_type', $account['account_type']);
            if ($account['type'] === 'CR') {
                $net_amount = ($result ? $result->total_cr : 0.00) - ($result ? $result->total_dr : 0.00);
            } else { // Default to DR
                $net_amount = ($result ? $result->total_dr : 0.00) - ($result ? $result->total_cr : 0.00);
            }

            return [
                'title' => $account['title'], // Add the title field
                'account_type' => $account['account_type'],
                'type' => $account['type'],
                'total_dr' => $result ? $result->total_dr : 0.00,
                'total_cr' => $result ? $result->total_cr : 0.00,
                'net_amount' => $net_amount // Use the calculated net_amount
            ];
        });
        // other comprehensive
        $conprehensive_accountTypes = [
            ['account_type' => 'account_type', 'title' => 'Income that will not be reclassified to the income statement', 'type' => 'CR'],
            ['account_type' => 'account_type', 'title' => 'Losses that will not be reclassified to the income statement', 'type' => 'DR'],
            ['account_type' => 'account_type', 'title' => 'Income that may be reclassified to the income statement', 'type' => 'CR'],
            ['account_type' => 'account_type', 'title' => 'Losses that may be reclassified to the income statement', 'type' => 'DR'],
            ['account_type' => 'account_type', 'title' => 'Other income reported in other comprehensive income for the year, net of tax', 'type' => 'CR'],
            ['account_type' => 'account_type', 'title' => 'Other losses reported in other comprehensive income for the year, net of tax ', 'type' => 'DR'],
        ];

        $comprehensive_results = JournalRecord::whereBetween('journal_records.journal_date', [$from_date, $to_date])
            ->where('journal_records.office_id', Auth::user()->office_id)
            ->join('master_accounts', 'master_accounts.id', '=', 'journal_records.master_account_id')
            ->whereIn('master_accounts.mst_definition', array_column($conprehensive_accountTypes, 'account_type'))
            ->selectRaw('
            master_accounts.mst_definition as account_type,
            SUM(CASE WHEN journal_records.transaction_type = "DR" THEN journal_records.amount ELSE 0 END) as total_dr,
            SUM(CASE WHEN journal_records.transaction_type = "CR" THEN journal_records.amount ELSE 0 END) as total_cr,
            (SUM(CASE WHEN journal_records.transaction_type = "DR" THEN journal_records.amount ELSE 0 END) -
            SUM(CASE WHEN journal_records.transaction_type = "CR" THEN journal_records.amount ELSE 0 END)) as net_amount
        ')
            ->groupBy('master_accounts.mst_definition')
            ->get();

        $comprehensive_results = collect($conprehensive_accountTypes)->map(function ($account) use ($comprehensive_results) {
            $result = $comprehensive_results->firstWhere('account_type', $account['account_type']);

            // Calculate net_amount based on the account type
            if ($account['type'] === 'CR') {
                $net_amount = ($result ? $result->total_cr : 0.00) - ($result ? $result->total_dr : 0.00);
            } else { // Default to DR
                $net_amount = ($result ? $result->total_dr : 0.00) - ($result ? $result->total_cr : 0.00);
            }

            return [
                'title' => $account['title'], // Add the title field
                'account_type' => $account['account_type'],
                'type' => $account['type'],
                'total_dr' => $result ? $result->total_dr : 0.00,
                'total_cr' => $result ? $result->total_cr : 0.00,
                'net_amount' => $net_amount // Use the calculated net_amount
            ];
        });
        // financial position
        $accountTypes = [
            ['account_type' => 'Current/Operating Asset', 'title' => 'Total Current Assets', 'type' => 'DR'],
            ['account_type' => 'Property, Plant and Equipment', 'title' => 'Property, Plant and Equipment', 'type' => 'CR'],
            ['account_type' => 'Intangible Assets', 'title' => 'Intangible Assets', 'type' => 'DR'],
            ['account_type' => 'Financial Assets', 'title' => 'Financial Assets', 'type' => 'DR'],
            ['account_type' => 'Other Non-Current Assets', 'title' => 'Other Non-Current Assets', 'type' => 'DR'],
            ['account_type' => 'Current Liability', 'title' => 'Total Current Liabilities', 'type' => 'CR'],
            ['account_type' => 'Non-Current Liabilities', 'title' => 'Total Non-Current Liabilities', 'type' => 'CR'],
            ['account_type' => 'Share Capital', 'title' => 'Total Share Capital', 'type' => 'DR'],
            ['account_type' => 'Retained Earnings', 'title' => 'Retained Earnings', 'type' => 'CR'],
            ['account_type' => 'Other Equity', 'title' => 'Other Equity', 'type' => 'DR'],
        ];

        $financial_results = JournalRecord::whereBetween('journal_records.journal_date', [$from_date, $to_date])
            ->where('journal_records.office_id', Auth::user()->office_id)
            ->join('master_accounts', 'master_accounts.id', '=', 'journal_records.master_account_id')
            ->whereIn('master_accounts.mst_definition', array_column($accountTypes, 'account_type'))
            ->selectRaw('
            master_accounts.mst_definition as account_type,
            SUM(CASE WHEN journal_records.transaction_type = "DR" THEN journal_records.amount ELSE 0 END) as total_dr,
            SUM(CASE WHEN journal_records.transaction_type = "CR" THEN journal_records.amount ELSE 0 END) as total_cr,
            (SUM(CASE WHEN journal_records.transaction_type = "DR" THEN journal_records.amount ELSE 0 END) -
            SUM(CASE WHEN journal_records.transaction_type = "CR" THEN journal_records.amount ELSE 0 END)) as net_amount
        ')
            ->groupBy('master_accounts.mst_definition')
            ->get();

        $financial_results = collect($accountTypes)->map(function ($account) use ($financial_results) {
            $result = $financial_results->firstWhere('account_type', $account['account_type']);

            // Calculate net_amount based on the account type
            if ($account['type'] === 'CR') {
                $net_amount = ($result ? $result->total_cr : 0.00) - ($result ? $result->total_dr : 0.00);
            } else { // Default to DR
                $net_amount = ($result ? $result->total_dr : 0.00) - ($result ? $result->total_cr : 0.00);
            }

            return [
                'title' => $account['title'], // Add the title field
                'account_type' => $account['account_type'],
                'type' => $account['type'],
                'total_dr' => $result ? $result->total_dr : 0.00,
                'total_cr' => $result ? $result->total_cr : 0.00,
                'net_amount' => $net_amount // Use the calculated net_amount
            ];
        });

        return view('backend.accounts-report.tax-reports', compact('from_date', 'to_date', 'profitLosse_results', 'financial_results', 'comprehensive_results'));
    }
    public function sub_head_details(Request $request)
    {
        if ($request->from_date && $request->to_date) {
            $from_date = $this->dateFormat($request->from_date);
            $to_date = $this->dateFormat($request->to_date);
        } else {
            $from_date = date('Y-01-01');
            $to_date = date('Y-m-d');
        }
        $type = $request->type; // Assuming $request->type contains 'DR' or 'CR'
        $accountType = $request->account_type;
        $details = JournalRecord::whereBetween('journal_records.journal_date', [$from_date, $to_date])
            ->where('journal_records.office_id', Auth::user()->office_id)
            ->join('master_accounts', 'master_accounts.id', '=', 'journal_records.master_account_id')
            ->where('master_accounts.mst_definition', $accountType)
            ->selectRaw('
                journal_records.account_head_id,
                SUM(CASE WHEN journal_records.transaction_type = "DR" THEN journal_records.total_amount ELSE 0 END) as total_dr,
                SUM(CASE WHEN journal_records.transaction_type = "CR" THEN journal_records.total_amount ELSE 0 END) as total_cr
            ')
            ->groupBy('journal_records.account_head_id')
            ->get();

        $details = $details->map(function ($item) use ($type) {
            if ($type === 'CR') {
                $item->net_amount = $item->total_cr - $item->total_dr; // CR - DR
            } else { // Default to DR
                $item->net_amount = $item->total_dr - $item->total_cr; // DR - CR
            }
            return $item;
        });
        // dd($request->account_type);
        return view('backend.accounts-report.sub-head-details', compact('details', 'type'));
    }

    public function tax_sub_head_details(Request $request)
    {
        $head_id = $request->account_id;
        $column = 'account_head_id';
        $account_head = AccountHead::find($head_id);
        $table = 'account_heads';
        $head_name = 'fld_ac_head';
        $from = $request->from_date ? $this->dateFormat($request->from_date) : date('Y-01-01');
        $to = $request->to_date ? $this->dateFormat($request->to_date) : date('Y-m-d');
        $month_number = $request->month_number;
        $search = $request->search ?? $account_head->id;
        $office_id = Auth::user()->office_id;

        $sql = "
            SELECT
                jr.id,
                jr.amount,
                jr.transaction_type,
                jr.journal_date,
                ah.`{$head_name}` AS fld_ac_head,
                jr.journal_id,
            CASE
                WHEN jpi.invoice_no IS NOT NULL THEN CONCAT('By Invoice', ' ', jpi.invoice_no)
                WHEN pe.purchase_no IS NOT NULL THEN CONCAT('By Purchase', ' ', pe.purchase_no)
                WHEN p.payment_no IS NOT NULL THEN CONCAT('By payment', ' ', p.payment_no)
                WHEN r.receipt_no IS NOT NULL THEN CONCAT('By receipt_no', ' ', r.receipt_no)
                WHEN j.journal_no IS NOT NULL THEN CONCAT('By journal', ' ', j.journal_no)
                ELSE 'NO Narration Available'
            END AS naration,

            CASE
                WHEN pe.purchase_no IS NOT NULL THEN
                    CONCAT('Invoice No: ', pe.invoice_no, pi.pi_name)
                ELSE
                    pi.pi_name

            END AS reference

            FROM journal_records as jr

            JOIN `{$table}` as ah on ah.id = jr.`{$column}`
            JOIN party_infos as pi ON pi.id = jr.party_info_id
            LEFT JOIN journals as j ON j.id = jr.journal_id
            LEFT JOIN purchase_expenses as pe ON pe.id = j.purchase_expense_id
            LEFT JOIN payments as p ON p.id  = j.payment_id
            LEFT JOIN receipts as r ON r.id = j.receipt_id
            LEFT JOIN invoices as jpi ON jpi.id = j.invoice_id

            WHERE jr.`{$column}` = {$head_id}
            AND jr.office_id = {$office_id}

            AND (
                (:from_date IS NOT NULL AND :to_date IS NOT NULL AND jr.journal_date BETWEEN :from_date3 AND :to_date3)
                OR (:from_date1 IS NOT NULL AND :to_date1 IS NULL AND jr.journal_date = :from_date4)
                OR (:from_date2 IS NULL AND :to_date2 IS NOT NULL AND jr.journal_date = :to_date4)
                OR (:from_date5 IS NULL AND :to_date5 IS NULL)
            )
            ORDER BY jr.journal_date ASC
        ";

        $items = DB::select($sql, [
            'from_date' => $from,
            'to_date' => $to,
            'from_date1' => $from,
            'to_date1' => $to,
            'from_date2' => $from,
            'to_date2' => $to,
            'from_date3' => $from,
            'to_date3' => $to,
            'from_date4' => $from,
            'to_date4' => $to,
            'from_date5' => $from,
            'to_date5' => $to,
        ]);

        return view('backend.accounts-report.tax-sub-head', compact('items', 'account_head'));
    }
    public function conporate_tax_details(Request $request)
    {
        // dd($request->all());
        if ($request->from_date && $request->to_date) {
            $from_date = $request->from_date;
            $to_date = $request->to_date;
        } else {
            $from_date = date('Y-01-01');
            $to_date = date('Y-m-d');
        }
        $profitLosse_accountTypes = [
            ['account_type' => 'Sales Turnover', 'title' => 'Operating Revenue', 'type' => 'CR'],
            ['account_type' => 'Cost of Sales / Goods Sold', 'title' => 'Expenditure in Deriving Operating Revenue', 'type' => 'DR'],
            ['account_type' => 'Administrative Expense', 'title' => 'Non-Operating Expense', 'type' => 'DR'],
            ['account_type' => 'Salaries, Benefits and Wages', 'title' => 'Salaries, Wages and Related Charges', 'type' => 'DR'],
            ['account_type' => 'Depreciation and Amortization', 'title' => 'Depreciation and Amortisation', 'type' => 'DR'],
            ['account_type' => 'Fines and Penalties', 'title' => 'Fines and Penalties', 'type' => 'DR'],
            ['account_type' => 'Donations', 'title' => 'Donations', 'type' => 'DR'],
            ['account_type' => 'Food & Entertainment', 'title' => 'Entertainment Expenses', 'type' => 'DR'],
            ['account_type' => 'Other Administrative Expense', 'title' => 'Other Expneses', 'type' => 'DR'],
            ['account_type' => 'Other Income', 'title' => 'Non-Operating Revenue', 'type' => 'CR'],
            ['account_type' => 'Dividends received', 'title' => 'Dividends Received', 'type' => 'CR'],
            ['account_type' => 'Other non-operating revenue', 'title' => 'Other Non-operating Revenue', 'type' => 'CR'],
            ['account_type' => 'Interest Income', 'title' => 'Interest Income', 'type' => 'CR'],
            ['account_type' => 'Interest Expenditure', 'title' => 'Interest Expense', 'type' => 'CR'],
            ['account_type' => 'Gains disposal of assets', 'title' => 'Gains Disposal of Assets', 'type' => 'DR'],
            ['account_type' => 'Losses Disposal of Assets', 'title' => 'Losses Disposal of Assets', 'type' => 'CR'],
            ['account_type' => 'Foreign Exchange Gains', 'title' => 'Foreign Exchange Gains', 'type' => 'DR'],
            ['account_type' => 'Foreign Exchange Losses', 'title' => 'Foreign Exchange Losses', 'type' => 'CR'],
        ];
        $profitLosse_results = JournalRecord::whereBetween('journal_records.journal_date', [$from_date, $to_date])
            ->where('journal_records.office_id', Auth::user()->office_id)
            ->join('master_accounts', 'master_accounts.id', '=', 'journal_records.master_account_id')
            ->whereIn('master_accounts.mst_definition', array_column($profitLosse_accountTypes, 'account_type'))
            ->selectRaw('
            master_accounts.mst_definition as account_type,
            SUM(CASE WHEN journal_records.transaction_type = "DR" THEN journal_records.amount ELSE 0 END) as total_dr,
            SUM(CASE WHEN journal_records.transaction_type = "CR" THEN journal_records.amount ELSE 0 END) as total_cr,
            (SUM(CASE WHEN journal_records.transaction_type = "DR" THEN journal_records.amount ELSE 0 END) -
            SUM(CASE WHEN journal_records.transaction_type = "CR" THEN journal_records.amount ELSE 0 END)) as net_amount
        ')
            ->groupBy('master_accounts.mst_definition')
            ->get();
        // dd($profitLosse_results[0]->corporate_tax_details(null, null, null, null));
        $profitLosse_results = collect($profitLosse_accountTypes)->map(function ($account) use ($profitLosse_results) {
            $result = $profitLosse_results->firstWhere('account_type', $account['account_type']);
            if ($account['type'] === 'CR') {
                $net_amount = ($result ? $result->total_cr : 0.00) - ($result ? $result->total_dr : 0.00);
            } else { // Default to DR
                $net_amount = ($result ? $result->total_dr : 0.00) - ($result ? $result->total_cr : 0.00);
            }

            return [
                'title' => $account['title'], // Add the title field
                'account_type' => $account['account_type'],
                'type' => $account['type'],
                'total_dr' => $result ? $result->total_dr : 0.00,
                'total_cr' => $result ? $result->total_cr : 0.00,
                'net_amount' => $net_amount // Use the calculated net_amount
            ];
        });

        $accountTypes = [
            ['account_type' => 'Current/Operating Asset', 'title' => 'Total Current Assets', 'type' => 'DR'],
            ['account_type' => 'Property, Plant and Equipment', 'title' => 'Property, Plant and Equipment', 'type' => 'CR'],
            ['account_type' => 'Intangible Assets', 'title' => 'Intangible Assets', 'type' => 'DR'],
            ['account_type' => 'Financial Assets', 'title' => 'Financial Assets', 'type' => 'DR'],
            ['account_type' => 'Other Non-Current Assets', 'title' => 'Other Non-Current Assets', 'type' => 'DR'],
            ['account_type' => 'Current Liability', 'title' => 'Total Current Liabilities', 'type' => 'CR'],
            ['account_type' => 'Non-Current Liabilities', 'title' => 'Total Non-Current Liabilities', 'type' => 'CR'],
            ['account_type' => 'Share Capital', 'title' => 'Total Share Capital', 'type' => 'DR'],
            ['account_type' => 'Retained Earnings', 'title' => 'Retained Earnings', 'type' => 'CR'],
            ['account_type' => 'Other Equity', 'title' => 'Other Equity', 'type' => 'DR'],
        ];

        $financial_results = JournalRecord::whereBetween('journal_records.journal_date', [$from_date, $to_date])
            ->where('journal_records.office_id', Auth::user()->office_id)
            ->join('master_accounts', 'master_accounts.id', '=', 'journal_records.master_account_id')
            ->whereIn('master_accounts.mst_definition', array_column($accountTypes, 'account_type'))
            ->selectRaw('
            master_accounts.mst_definition as account_type,
            SUM(CASE WHEN journal_records.transaction_type = "DR" THEN journal_records.amount ELSE 0 END) as total_dr,
            SUM(CASE WHEN journal_records.transaction_type = "CR" THEN journal_records.amount ELSE 0 END) as total_cr,
            (SUM(CASE WHEN journal_records.transaction_type = "DR" THEN journal_records.amount ELSE 0 END) -
            SUM(CASE WHEN journal_records.transaction_type = "CR" THEN journal_records.amount ELSE 0 END)) as net_amount
        ')
            ->groupBy('master_accounts.mst_definition')
            ->get();

        $financial_results = collect($accountTypes)->map(function ($account) use ($financial_results) {
            $result = $financial_results->firstWhere('account_type', $account['account_type']);

            // Calculate net_amount based on the account type
            if ($account['type'] === 'CR') {
                $net_amount = ($result ? $result->total_cr : 0.00) - ($result ? $result->total_dr : 0.00);
            } else { // Default to DR
                $net_amount = ($result ? $result->total_dr : 0.00) - ($result ? $result->total_cr : 0.00);
            }

            return [
                'title' => $account['title'], // Add the title field
                'account_type' => $account['account_type'],
                'type' => $account['type'],
                'total_dr' => $result ? $result->total_dr : 0.00,
                'total_cr' => $result ? $result->total_cr : 0.00,
                'net_amount' => $net_amount // Use the calculated net_amount
            ];
        });
        return view('backend.accounts-report.corporate-tax-details', compact('from_date', 'to_date', 'profitLosse_results', 'financial_results'));
    }
    public function input_vat_report(Request $request)
    {
        // Gate::authorize('')
        if ($request->date) {
            $date = $this->dateFormat($request->date);
        } else {
            $date = date('Y-m-d');
        }
        $from = null;
        $to = null;
        if ($request->from && $request->to) {
            $from = $this->dateFormat($request->from);
            $to = $this->dateFormat($request->to);
            $input_vats = PurchaseExpense::whereBetween('date', [$from, $to])->get();
        } else {
            $input_vats = PurchaseExpense::where('date', $date)->get();
        }
        return view('backend.accounts-report.input-vat', compact('date', 'from', 'to', 'input_vats'));
    }
    public function output_vat_report(Request $request)
    {
        if ($request->date) {
            $date = $this->dateFormat($request->date);
        } else {
            $date = date('Y-m-d');
        }
        $from = null;
        $to = null;
        if ($request->from && $request->to) {
            $from = $this->dateFormat($request->from);
            $to = $this->dateFormat($request->to);
            $output_vats = JobProjectInvoice::whereBetween('date', [$from, $to])->get();
        } else {
            $output_vats = JobProjectInvoice::where('date', $date)->get();
        }
        return view('backend.accounts-report.output-vat', compact('date', 'from', 'to', 'output_vats'));
    }
    public function stock_report(Request $request)
    {
        Gate::authorize('Stock_Report');
        $from = $request->form_date ? $this->dateFormat($request->form_date) : ($request->to_date ? $this->dateFormat($request->to_date) : date('Y-m-d'));
        $to = $request->to_date ? $this->dateFormat($request->to_date) :  null;
        $products = StockTransection::join('account_heads', 'account_heads.id', '=', 'stock_transections.product_id')
            ->select('account_heads.id', 'account_heads.fld_ac_head as product_name')->distinct()->get();
        // dd($products);
        return view('backend.accounts-report.stockReport', compact('products', 'from', 'to'));
    }
    public function head_expense_detail(Request $request)
    {
        $special_head = null;
        $head_record = null;
        $special_sub_head = AccountSubHead::find($request->id);
        $head_record = PurchaseExpenseItem::where('sub_head_id', $request->id)->get();
        if (!$special_sub_head && count($head_record) == 0) {
            $special_head = AccountHead::find($request->id);
            $head_record = PurchaseExpenseItem::where('head_id', $request->id)->get();
        }
        return view('backend.accounts-report.head-expense-detais', compact('special_head', 'special_sub_head', 'head_record'));
    }
    public function head_project_expense(Request $request)
    {
        $special_head = null;
        $head_record = null;
        $special_sub_head = AccountSubHead::find($request->id);
        $head_record = PurchaseExpenseItem::where('sub_head_id', $request->id)->whereColumn('out_qty', '!=', 'qty')->get();
        if (!$special_sub_head && count($head_record) == 0) {
            $special_head = AccountHead::find($request->id);
            $head_record = PurchaseExpenseItem::where('head_id', $request->id)->get();
        }
        return view('backend.project-expense.project-expense', compact('special_head', 'special_sub_head', 'head_record'));
    }
    public function project_expense_adjust(Request $request)
    {
        $expense = PurchaseExpenseItem::find($request->id);
        $project_lists = NewProject::all();
        return view('backend.project-expense.project-expense-adjust', compact('expense', 'project_lists'));
    }

    public function company_oth(Request $request)
    {
                $companyIds = $request->company ? (array) $request->company : [0, null];

                // Filter valid IDs (non-null, non-zero)
                $validIds = array_filter($companyIds, fn($id) => $id !== null && $id != 0);

                $projects = JobProject::when($request->party, function ($query, $party) {
                        $query->where('customer_id', $party);
                    })
                    ->where(function ($query) use ($validIds, $companyIds) {
                        if ($validIds) {
                            $query->whereIn('compnay_id', $validIds);
                        }
                        if (in_array(0, $companyIds)) {
                            $query->orWhere('compnay_id', 0);
                        }
                        if (in_array(null, $companyIds, true)) {
                            $query->orWhereNull('compnay_id');
                        }
                    })
                    ->get();
        return view('backend.accounts-report.company-project', compact( 'projects'));
    }


}

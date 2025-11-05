<?php

namespace App\Http\Controllers\backend\report;

use App\Http\Controllers\Controller;
use App\Subsidiary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{

    private function dateFormat($date)
    {
        $old_date = explode('/', $date);

        $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }

    public function income_statement(Request $request)
    {
        $to = $request->to ? $this->dateFormat($request->to) : date('Y-m-d');
        $from = $request->from ? $this->dateFormat($request->from) : Carbon::parse($to)->startOfYear()->toDateString();
        $company_id = $request->company_id ? $request->company_id : null;

        $baseQuery = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('journal_records.compnay_id', $company_id);

        // Revenue
        $revenues =  (clone $baseQuery)
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
        $inventory =  (clone $baseQuery)
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
        $cog_s =  (clone $baseQuery)
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
        $overHeads =  (clone $baseQuery)
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
        $administrative_exp =  (clone $baseQuery)
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
        $depreciation =  (clone $baseQuery)
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

    public function balance_sheet(Request $request)
    {
        $date = $request->from ? $this->dateFormat($request->from) :  date('Y-m-d');
        $from = date('Y-01-01', strtotime($date));
        $to = $date;
        $company_id = $request->company_id ? $request->company_id : null;
        // dd($company_id);

        // Common reusable balance formula
        $debitMinusCredit = "SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) -
                     SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END)";

        $creditMinusDebit = "SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) -
                     SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END)";

        // Base query
        $baseQuery = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('journal_records.compnay_id', $company_id)
            ->where('journal_records.journal_date', '<=', $to);


        // Current Asset
        $current_assets = (clone $baseQuery)
            ->where('account_heads.account_type_id', 1)
            ->where('account_heads.fld_definition', 'like', '%Current/Operating Asset%')
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("$debitMinusCredit as balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();

        $current_asset_balance = $current_assets->sum('balance');

        // Fixed Asset
        $fixed_assets = (clone $baseQuery)
            ->where('account_heads.account_type_id', 1)
            ->where('account_heads.fld_definition', 'like', '%Fixed Asset%')
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("$debitMinusCredit as balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();

        $fixed_asset_balance = $fixed_assets->sum('balance');

        // Other Asset
        $other_assets = (clone $baseQuery)
            ->where('account_heads.account_type_id', 1)
            ->where(function ($q) {
                $q->where('account_heads.fld_definition', 'not like', '%Fixed Asset%')
                    ->where('account_heads.fld_definition', 'not like', '%Current/Operating Asset%');
            })
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("$debitMinusCredit as balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();

        $other_asset_balance = $other_assets->sum('balance');
        $total_asset = $current_asset_balance + $fixed_asset_balance + $other_asset_balance;

        // Current Liability
        $current_liability = (clone $baseQuery)
            ->where('account_heads.account_type_id', 2)
            ->where('account_heads.fld_definition', 'like', '%Current Liability%')
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("$creditMinusDebit as balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();

        $total_current_liability = $current_liability->sum('balance');

        // Non-Current Liability
        $non_current_liability = (clone $baseQuery)
            ->where('account_heads.account_type_id', 2)
            ->where('account_heads.fld_definition', 'like', '%Non-Current Liabilities%')
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("$creditMinusDebit as balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();

        $total_non_current_liability = $non_current_liability->sum('balance');

        // Owners Equity
        $owners_equity = (clone $baseQuery)
            ->where('account_heads.account_type_id', 2)
            ->where('account_heads.fld_definition', 'like', '%Share Capital%')
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("$creditMinusDebit as balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();
        $total_owners_equity = $owners_equity->sum('balance');

        // Other Liabilities
        $other_liabilities = (clone $baseQuery)
            ->where('account_heads.account_type_id', 2)
            ->where(function ($q) {
                $q->where('account_heads.fld_definition', 'not like', '%Non-Current Liabilities%')
                    ->where('account_heads.fld_definition', 'not like', '%Share Capital%')
                    ->where('account_heads.fld_definition', 'not like', '%Current Liability%');
            })
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("$creditMinusDebit as balance")
            )
            ->groupBy('account_heads.id', 'account_heads.fld_ac_head')
            ->get();

        $other_liability_balance = $other_liabilities->sum('balance');

        // Retained Earnings
        $retained = DB::table('journal_records')
            ->where('journal_records.compnay_id', $company_id)
            ->whereIn('account_type_id', [1, 2])
            ->where('journal_records.journal_date', '<', $from)
            ->selectRaw("
                SUM(CASE WHEN transaction_type = 'DR' AND account_type_id = 1 THEN amount ELSE 0 END) -
                SUM(CASE WHEN transaction_type = 'CR' AND account_type_id = 1 THEN amount ELSE 0 END) AS preAsset,
                SUM(CASE WHEN transaction_type = 'CR' AND account_type_id = 2 THEN amount ELSE 0 END) -
                SUM(CASE WHEN transaction_type = 'DR' AND account_type_id = 2 THEN amount ELSE 0 END) AS preLiability
            ")->first();

        $retained_earning = ($retained->preAsset ?? 0) - ($retained->preLiability ?? 0);
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

    public function cash_flow_statement(Request $request)
    {

        $year = $request->year ? $request->year :  date('Y');
        $company_id = $request->company_id ? $request->company_id : null;
        $companies = Subsidiary::get();

        $baseQuery = DB::table('journal_records')
            ->where('journal_records.compnay_id', $company_id);

        // Revenue
        $revenues = (clone $baseQuery)
            ->whereIn('account_type_id', [3, 4])
            ->whereYear('journal_records.journal_date', $year)
            ->selectRaw("
            SUM(CASE WHEN transaction_type = 'CR' AND account_type_id = 3 THEN amount ELSE 0 END) -
            SUM(CASE WHEN transaction_type = 'DR' AND account_type_id = 3 THEN amount ELSE 0 END) AS incomes,
            SUM(CASE WHEN transaction_type = 'DR' AND account_type_id = 4 THEN amount ELSE 0 END) -
            SUM(CASE WHEN transaction_type = 'CR' AND account_type_id = 4 THEN amount ELSE 0 END) AS expenses
        ")
            ->first();

        $depreciation = (clone $baseQuery)
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('account_heads.fld_definition', 'Depreciation and Amortization')
            ->whereYear('journal_records.journal_date', $year)
            ->selectRaw("
                SUM(CASE WHEN transaction_type = 'CR' THEN amount ELSE 0 END) AS credit_amount,
                SUM(CASE WHEN transaction_type = 'DR' THEN amount ELSE 0 END) AS debit_amount
            ")
            ->first();

        $asset_decrease = (clone $baseQuery)
    ->selectRaw("
        -- Inventory (cumulative)
        SUM(CASE WHEN transaction_type = 'DR'
                 AND master_account_id = 3
                 AND YEAR(journal_records.journal_date) <= ?
            THEN amount ELSE 0 END) -
        SUM(CASE WHEN transaction_type = 'CR'
                 AND master_account_id = 3
                 AND YEAR(journal_records.journal_date) <= ?
            THEN amount ELSE 0 END) AS inventory_this_year,

        SUM(CASE WHEN transaction_type = 'DR'
                 AND master_account_id = 3
                 AND YEAR(journal_records.journal_date) <= ?
            THEN amount ELSE 0 END) -
        SUM(CASE WHEN transaction_type = 'CR'
                 AND master_account_id = 3
                 AND YEAR(journal_records.journal_date) <= ?
            THEN amount ELSE 0 END) AS inventory_prev_year,

        -- Receivable (cumulative)
        SUM(CASE WHEN transaction_type = 'DR'
                 AND account_head_id = 3
                 AND YEAR(journal_records.journal_date) <= ?
            THEN amount ELSE 0 END) -
        SUM(CASE WHEN transaction_type = 'CR'
                 AND account_head_id = 3
                 AND YEAR(journal_records.journal_date) <= ?
            THEN amount ELSE 0 END) AS receivable_this_year,

        SUM(CASE WHEN transaction_type = 'DR'
                 AND account_head_id = 3
                 AND YEAR(journal_records.journal_date) <= ?
            THEN amount ELSE 0 END) -
        SUM(CASE WHEN transaction_type = 'CR'
                 AND account_head_id = 3
                 AND YEAR(journal_records.journal_date) <= ?
            THEN amount ELSE 0 END) AS receivable_prev_year,

        -- Payable (cumulative) 🔴 (double-check account_head_id for payables!)
        SUM(CASE WHEN transaction_type = 'CR'
                 AND account_head_id = 5
                 AND YEAR(journal_records.journal_date) <= ?
            THEN amount ELSE 0 END) -
        SUM(CASE WHEN transaction_type = 'DR'
                 AND account_head_id = 5
                 AND YEAR(journal_records.journal_date) <= ?
            THEN amount ELSE 0 END) AS payable_this_year,

        SUM(CASE WHEN transaction_type = 'CR'
                 AND account_head_id = 5
                 AND YEAR(journal_records.journal_date) <= ?
            THEN amount ELSE 0 END) -
        SUM(CASE WHEN transaction_type = 'DR'
                 AND account_head_id = 5
                 AND YEAR(journal_records.journal_date) <= ?
            THEN amount ELSE 0 END) AS payable_prev_year
    ", [
        $year, $year, $year - 1, $year - 1,   // inventory
        $year, $year, $year - 1, $year - 1,   // receivable
        $year, $year, $year - 1, $year - 1    // payable
    ])
    ->first();


        $profit = $revenues ? ($revenues->incomes - $revenues->expenses) : 0;

        $inventory_decrease = $asset_decrease->inventory_prev_year -   $asset_decrease->inventory_this_year;
        $receivable_decrease = $asset_decrease->receivable_prev_year -   $asset_decrease->receivable_this_year;
        $payable_increase = $asset_decrease->payable_prev_year -   $asset_decrease->payable_this_year;
        $tax_paid = 0;
        $net_cash_from_operation = $profit+$depreciation->debit_amount+$inventory_decrease+$receivable_decrease+$payable_increase;
        $net_cash_from_operation_act = $net_cash_from_operation + $tax_paid;



        return view('backend.accounting-reports.cash-flow-statement.index', compact('net_cash_from_operation_act','tax_paid','net_cash_from_operation','payable_increase','receivable_decrease','inventory_decrease','depreciation', 'company_id', 'companies', 'year', 'profit'));
    }
}

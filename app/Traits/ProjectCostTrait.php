<?php

namespace App\Traits;

use App\ProjectExpense;
use Illuminate\Support\Facades\DB;

trait ProjectCostTrait{
    public function LabourCost($project_id, $month = null, $year = null, $employee_id = null, $from_date = null, $to_date = null){
        $total_cost = 0;
        $data = 0;
        return ['data' => $data, 'total_cost' => number_format($total_cost,2)];
    }

    public function matarialCost($project_id, $from_date = null, $to_date = null)
    {
        $dateFilter = $this->buildDateFilter($from_date, $to_date);

        $project_expenses =  ProjectExpense::where('project_id', $project_id)
            ->where('inventory_expense_id', '>', 0)
            ->whereHas('expense', $dateFilter)
            ->with(['expense' => $dateFilter])
            ->get();
        return ['data' => $project_expenses, 'total_cost' => number_format($project_expenses->sum('total_amount'), 2)];
    }

    public function administrativeCost($project_id, $from_date = null, $to_date = null)
    {
        $dateFilter = $this->buildDateFilter($from_date, $to_date);

        $project_expenses = ProjectExpense::where('project_id', $project_id)
            ->where('inventory_expense_id', 0)
            ->whereHas('expense', $dateFilter)
            ->with(['expense' => $dateFilter])
            ->get();

        return ['data' => $project_expenses, 'total_cost' => number_format($project_expenses->sum('total_amount'), 2)];
    }

    /**
     * Build date filter closure for query.
     */
    private function buildDateFilter($from_date, $to_date)
    {
        return function ($query) use ($from_date, $to_date) {
            if ($from_date && $to_date) {
                $query->whereBetween('date', [$from_date, $to_date]);
            } elseif ($from_date) {
                $query->where('date', $from_date);
            } elseif ($to_date) {
                $query->where('date', $to_date);
            }
        };
    }

    public function incomeStateMent($project_id){
        $from = date('Y-01-01');
        $to =  date('Y-m-d');
        // Revenue
        $revenues = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('account_heads.account_type_id', 3)
            ->whereBetween('journal_records.journal_date', [$from, $to])
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) -
                        SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) AS balance")
            )->where('journal_records.project_id', $project_id)
            ->groupBy('account_heads.id','account_heads.fld_ac_head')
            ->get();

        $revenue_balance = $revenues->sum('balance');

        // COGS: Inventory
        $inventory = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('journal_records.project_id', $project_id)
            ->where('account_heads.fld_ms_ac_head', 'like', '%INVENTORY%')
            ->select(DB::raw("
                SUM(CASE WHEN journal_records.transaction_type = 'DR' AND journal_records.journal_date < '$from' THEN journal_records.amount ELSE 0 END) -
                SUM(CASE WHEN journal_records.transaction_type = 'CR' AND journal_records.journal_date < '$from' THEN journal_records.amount ELSE 0 END) AS beginningBalance,

                SUM(CASE WHEN journal_records.transaction_type = 'DR' AND journal_records.journal_date > '$from' AND journal_records.journal_date <= '$to' THEN journal_records.amount ELSE 0 END) AS purchaseAmount,

                SUM(CASE WHEN journal_records.transaction_type = 'DR' AND journal_records.journal_date <= '$to' THEN journal_records.amount ELSE 0 END) -
                SUM(CASE WHEN journal_records.transaction_type = 'CR' AND journal_records.journal_date <= '$to' THEN journal_records.amount ELSE 0 END) AS endBalance
            "))
            ->first();

        // COGS: Cost of Goods Sold
        $cog_s = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('account_heads.fld_definition', 'like', '%Cost of Sales / Goods Sold%')
            ->whereBetween('journal_records.journal_date', [$from, $to])
            ->where('journal_records.project_id', $project_id)
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) -
                        SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) AS balance")
            )
            ->groupBy('account_heads.id','account_heads.fld_ac_head')
            ->get();

        $total_cogs = ($inventory->beginningBalance ?? 0) - ($inventory->endBalance ?? 0) + $cog_s->sum('balance');

        // Gross Profit
        $gross_profit = $revenue_balance - $total_cogs;

        // Overhead
        $overHeads = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('account_heads.account_type_id', 4)
            ->where('account_heads.fld_definition', 'not like', '%Cost of Sales / Goods Sold%')
            ->where('account_heads.fld_definition', 'not like', '%Administrative Expense%')
            ->where('account_heads.fld_definition', 'not like', '%Depreciation and Amortization%')
            ->where('journal_records.project_id', $project_id)
            ->whereBetween('journal_records.journal_date', [$from, $to])
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) -
                        SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) AS balance")
            )
            ->groupBy('account_heads.id','account_heads.fld_ac_head')
            ->get();

        // Administrative Expenses
        $administrative_exp = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where('account_heads.fld_definition', 'like', '%Administrative Expense%')
            ->whereBetween('journal_records.journal_date', [$from, $to])
            ->select(
                'account_heads.id',
                'account_heads.fld_ac_head',
                DB::raw("SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) -
                        SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) AS balance")
            )
            ->where('journal_records.project_id', $project_id)
            ->groupBy('account_heads.id','account_heads.fld_ac_head')
            ->get();

        $total_op_expense = $overHeads->sum('balance') + $administrative_exp->sum('balance');

        // Net Profit Before Depreciation
        $net_profit_loss = $gross_profit - $total_op_expense;

        // Depreciation
        $depreciation = DB::table('journal_records')
            ->leftJoin('account_heads', 'journal_records.account_head_id', '=', 'account_heads.id')
            ->where(function ($query) {
                $query->where('account_heads.fld_ms_ac_head', 'like', '%Depreciation and Amortization%')
                    ->orWhere('account_heads.fld_ms_ac_head', 'like', '%Accumulated Depreciation & Amortization%');
            })
            ->where('journal_records.project_id', $project_id)
            ->whereBetween('journal_records.journal_date', [$from, $to])
            ->select(DB::raw("
                SUM(CASE WHEN journal_records.transaction_type = 'DR' THEN journal_records.amount ELSE 0 END) -
                SUM(CASE WHEN journal_records.transaction_type = 'CR' THEN journal_records.amount ELSE 0 END) AS amount
            "))
            ->first();

        $final_profit = $net_profit_loss - ($depreciation->amount ?? 0);

        return [
            'from' => $from,
            'to' => $to,
            'revenues' => $revenues,
            'revenue_balance' => $revenue_balance,
            'inventory' => $inventory,
            'total_cogs' => $total_cogs,
            'cog_s' => $cog_s,
            'gross_profit' => $gross_profit,
            'overHeads' => $overHeads,
            'administrative_exp' => $administrative_exp,
            'total_op_expense' => $total_op_expense,
            'net_profit_loss' => $net_profit_loss,
            'depreciation' => $depreciation,
            'final_profit' => $final_profit,
        ];
    }
}

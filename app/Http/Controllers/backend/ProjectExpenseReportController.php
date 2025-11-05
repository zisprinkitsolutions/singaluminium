<?php

namespace App\Http\Controllers\backend;

use App\BillOfQuantityTask;
use App\Http\Controllers\Controller;
use App\JobProject;
use App\NewProject;
use App\ProjectExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProjectExpenseReportController extends Controller
{
    public function expenseReport(Request $request)
    {
        // dd($request->project_id);
        $project_id = $request->project_id;

        $sql = "
            SELECT
                p.id,
                p.avarage_complete AS progress,
                np.project_name,
                np.address,
                SUM(pe.amount) AS amount,
                SUM(pe.paid_amount) AS paid_amount,
                SUM(pe.due_amount) AS due_amount,
                SUM(pe.vat) as vat,
                SUM(pe.total_amount) as total_amount,
                SUM(pe.qty) AS total_qty
            FROM
                job_projects AS p
            JOIN
                project_expenses pe ON pe.project_id = p.id
            JOIN
                job_projects np ON np.id = p.project_id
        ";

        $params = [];

        if ($project_id) {
            $sql .= " WHERE p.id = :project_id";
            $params['project_id'] = $project_id;
        }

        $sql .= "
            GROUP BY
                p.id, np.project_name, np.address, p.avarage_complete
            ORDER BY
                np.project_name ASC
        ";

        // Convert to collection
        $allExpenses = collect(DB::select($sql, $params));

        // Pagination
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 40;
        $pagedData = $allExpenses->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $expenses = new LengthAwarePaginator(
            $pagedData,
            $allExpenses->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        $projects = JobProject::all();

        return view('backend.project-expense.index', compact('expenses','projects','project_id'));
    }

    public function expenseReportDetails(JobProject $project){
        $project_expenses = ProjectExpense::where('project_id', $project->id)->get();
        return view('backend.project-expense.details', compact('project_expenses', 'project'));
    }

    public function boqCompare(JobProject $project){
        $project_expenses = [];

        foreach($project->tasks as $task){
            $boq_task = BillOfQuantityTask::find($task->boq_task_id);
            if($boq_task){
                $estimated_expense = max($task->estimated_expense,$boq_task->estimated_expense);
            }else{
                $estimated_expense = $task->estimated_expense;
            }
            $project_expenses[] = [
                'task_name' => $task->task_name,
                'estimated_expense' => number_format ($estimated_expense, 2),
                'actual_expense' => number_format($task->expense, 2),
                'paid' => number_format($task->payment, 2),
                'payable' => number_format($task->payable, 2),
            ];
        }

        return view('backend.project-expense.boq_compare',compact('project','project_expenses'));
    }
}

<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\JobProject;
use App\JobProjectInvoice;
use App\Models\Payroll\Employee;
use App\Models\Payroll\EmployeeAttendance;
use App\Models\Payroll\SalaryProcess;
use App\ProjectExpense;
use App\Subsidiary;
use App\PartyInfo;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectCostAnalysisController extends Controller
{

    public function costAnalysis(Request $request){
        $company_id = $request->company_id;
        $to_date = $request->to_date ? change_date_format($request->to_date) : '';
        $from_date = $request->from_date ? change_date_format($request->from_date) : '';
        $project_id = $request->project_id;

        $projects = JobProject::orderBy('project_name','DESC')
        ->when($company_id,function($query) use($company_id){
            if($company_id){
                if($company_id > 0){
                    $query->whereNull('compnay_id');
                }else{
                    $query->where('compnay_id', $company_id);
                }
            }
        })->when($project_id,function($query) use($project_id){
            $query->where('id', $project_id);
        })->paginate(20);

        $all_projects = JobProject::orderBy('project_name')
        ->when($company_id,function($query) use($company_id){
            if($company_id){
                if($company_id > 0){
                    $query->whereNull('compnay_id');
                }else{
                    $query->where('compnay_id', $company_id);
                }
            }
        })->get();


        $dateFilter = function ($query) use ($from_date, $to_date) {
            if ($from_date && $to_date) {
                $query->whereBetween('date', [$from_date, $to_date]);
            } elseif ($from_date) {
                $query->where('date', $from_date);
            } elseif ($to_date) {
                $query->where('date', $to_date);
            }
        };

        $project_costs = [];

        $total_cost = [
            'matarial_cost' => 0,
            'administrative_cost' => 0,
            'invoice_amount' => 0,
            'receipt' => 0,
            'receivable_amount' => 0,
            'accrued_amount' => 0,
            'total_labour_cost' => 0,
            'total_contarct_value' => 0
        ];

        foreach($projects as $project){
            $administray_expense = ProjectExpense::where('project_id', $project->id)
                ->whereHas('expense', $dateFilter)
                ->with(['expense' => $dateFilter])
                ->where('inventory_expense_id', 0)
                ->get();

            $project_expenses = ProjectExpense::where('project_id', $project->id)
                ->whereHas('expense', $dateFilter)
                ->with(['expense' => $dateFilter])
                ->where('inventory_expense_id', '>', 0)
                ->get();

            // Invoices with date filtering
            $invoices = JobProjectInvoice::where('job_project_id', $project->id)
                ->when($from_date && $to_date, function ($q) use ($from_date, $to_date) {
                    $q->whereBetween('date', [$from_date, $to_date]);
                })
                ->when($from_date && !$to_date, function ($q) use ($from_date) {
                    $q->where('date', $from_date);
                })
                ->when(!$from_date && $to_date, function ($q) use ($to_date) {
                    $q->where('date', $to_date);
                })->get();

            // Laber Cost

            $months = DB::table('employee_attendances')
            ->selectRaw("YEAR(date) as year, MONTH(date) as month")
            ->when($from_date || $to_date, function ($query) use ($from_date, $to_date) {
                if($from_date && $to_date){
                    $query->whereBetween('date', [$from_date, $to_date]);
                }elseif($to_date && !$from_date){
                    $query->where('date',  $to_date);
                }elseif($from_date && !$to_date){
                    $query->where('date', $to_date);
                }
            })
            ->groupByRaw("YEAR(date), MONTH(date)")
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

            $labour_cost = 0;

            foreach($months as $month){
                $year = $month->year;
                $month = $month->month;
                $monthYear = $year .'-'.$month;

                if($from_date && $to_date){
                    $date = Carbon::parse($to_date);
                }elseif($from_date && !$to_date){
                    $date = Carbon::parse($from_date);
                }elseif($to_date && !$from_date){
                    $date = Carbon::parse($to_date);
                }else{
                    $date = Carbon::now();
                }


                $totalDaysInMonth = Carbon::parse($monthYear)->daysInMonth;
                $currentDayOfMonth = $date->format('Y-m') == $monthYear ? $date->day : $totalDaysInMonth;

                $attendances = EmployeeAttendance::where('project_id',$project->id)->whereMonth('date', $month)->whereYear('date', $year)
                ->when($from_date || $to_date, function ($query) use ($from_date, $to_date) {
                    if($from_date && $to_date){
                        $query->whereBetween('date', [$from_date, $to_date]);
                    }elseif($to_date && !$from_date){
                        $query->where('date',  $to_date);
                    }elseif($from_date && !$to_date){
                        $query->where('date', $to_date);
                    }
                })->get();

                foreach ($attendances as $attendance) {
                    $employee = Employee::find($attendance->employee_id);
                    if (!$employee) continue;

                    $employee_id = $employee->id;
                    $employee_name = $employee->full_name;

                    $salary_process = SalaryProcess::where('employee_id', $employee->id)
                        ->where('advance_amount', '>', 0)
                        ->get();

                    $already_payment = SalaryProcess::where('employee_id', $employee->id)
                        ->where('status', 1)
                        ->where('month', $month)
                        ->where('year', $year)
                        ->get();

                    $salary_date = new DateTime("$year-$month-01");
                    $last_visite = $employee->last_visite ?? $employee->joining_date ?? null;

                    if ($last_visite) {
                        $last = new DateTime($last_visite);
                        $interval = $salary_date->diff($last);
                        $text = ($interval->y * 12) + $interval->m;
                    }

                    $basic_salary = 0;
                    $check_attendance = EmployeeAttendance::check_attendance($employee->id, $month, $year, $basic_salary, $from_date, $to_date, $project->id);

                    $overtime_amount = isset($check_attendance['overtime_amount']) ? str_replace(',', '', $check_attendance['overtime_amount']) : 0;
                    $late_amount = isset($check_attendance['late_amount']) ? str_replace(',', '', $check_attendance['late_amount']) : 0;
                    $total_absen_penalty = $check_attendance['total_absen_penalty'] ?? 0;
                    $basic_salary = $check_attendance['basic_salary'] ?? 0;
                    $total_absen_penalty = isset($check_attendance['total_absen_penalty']) ? $check_attendance['total_absen_penalty'] : 0;
                    $basic_salary_current_day = $basic_salary;

                    if ($currentDayOfMonth < $totalDaysInMonth) {
                        $basic_salary_current_day = ($basic_salary / $totalDaysInMonth) * $currentDayOfMonth;
                    }

                    $total_amount = ($overtime_amount - $late_amount - $total_absen_penalty) + $basic_salary_current_day;

                    $labour_cost += $total_amount;
                }
            }

            $contract_value = $project->new_project ? $project->new_project->contract_value : 0;
            $invoice_amount = $invoices->sum('total_budget') - $invoices->sum('retention_amount');
            $receivable = $invoices->sum('due_amount');
            $receipt = $invoices->sum('paid_amount');
            $accrued_receivable = $contract_value - $invoice_amount;
            $matarial_cost = $project_expenses->sum('total_amount');
            $administrative_cost = $administray_expense->sum('total_amount');


            $project_costs[] = [
                'project_id' => $project->id,
                'advance_amount' => $project->advance_amount,
                'project_name' => $project->project_name,
                'project_no' =>  optional($project->new_project)->project_no,
                'project_plot' => optional($project->new_project)->plot,
                'material_expense' => number_format( $matarial_cost, 2),
                'administry_cost' => number_format($administrative_cost, 2),
                'total_invoice' => number_format(  $invoice_amount, 2),
                'labour_cost' => number_format($labour_cost,2),
                'receivable' => number_format( $receivable,2),
                'receipt' => number_format($receipt,2),
                'contract_value' => number_format( $contract_value,2),
                'accrued_receivable' => number_format( $accrued_receivable,2),
                'total_receivable' => number_format($receivable + $accrued_receivable, 2),
            ];

            $total_cost['matarial_cost'] +=  $matarial_cost;
            $total_cost['administrative_cost'] +=  $administrative_cost;
            $total_cost['invoice_amount'] +=  $invoice_amount;
            $total_cost['receipt'] +=  $receipt;
            $total_cost['receivable_amount'] +=  $receivable;
            $total_cost['accrued_amount'] +=  $accrued_receivable;
            $total_cost['total_labour_cost'] +=  $labour_cost;
            $total_cost['total_contarct_value'] +=  $contract_value;
        }

        $companies = Subsidiary::orderBy('company_name')->get();
        $all_parties = PartyInfo::where('pi_type', 'Customer')->orderBy('pi_name')->get();

        return view('backend.job-project.cost-analysis.cost',compact('projects', 'project_id', 'to_date', 'from_date', 'project_id','all_projects','project_costs', 'total_cost', 'companies', 'company_id', 'all_parties'));
    }

    public function metarilaCost(JobProject $project, Request $request){

        $cost = $request->cost;
        $from_date = $request->from_date;
        $to_date = $request->to_date;


        $dateFilter = function ($query) use ($from_date, $to_date) {
            if ($from_date && $to_date) {
                $query->whereBetween('date', [$from_date, $to_date]);
            } elseif ($from_date) {
                $query->where('date', $from_date);
            } elseif ($to_date) {
                $query->where('date', $to_date);
            }
        };

        if($cost == 'administrative'){
            $project_expenses = ProjectExpense::where('project_id', $project->id)
                ->where('inventory_expense_id','<=', 0)
                ->whereHas('expense', $dateFilter)
                ->with(['expense' => $dateFilter])
                ->get();
        }elseIf($cost == 'all'){
           $project_expenses = ProjectExpense::where('project_id', $project->id)
                ->whereHas('expense', $dateFilter)
                ->with(['expense' => $dateFilter])
                ->get();
        }elseif($cost == 'payable'){
            $project_expenses = ProjectExpense::where('project_id', $project->id)
                ->where('due_amount', '>', 0)
                ->whereHas('expense', $dateFilter)
                ->with(['expense' => $dateFilter])
                ->get();
        }elseif($cost == 'payment'){
            $project_expenses = ProjectExpense::where('project_id', $project->id)
                ->where('paid_amount', '>', 0)
                ->whereHas('expense', $dateFilter)
                ->with(['expense' => $dateFilter])
                ->get();
        }
        else{
            $project_expenses = ProjectExpense::where('project_id', $project->id)
                ->where('inventory_expense_id', '>', 0)
                ->whereHas('expense', $dateFilter)
                ->with(['expense' => $dateFilter])
                ->get();
        }


        return view('backend.job-project.cost-analysis.metarial-cost',compact('project', 'to_date', 'from_date','project_expenses'));
    }

    public function invoices(JobProject $project, Request $request){
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $type = $request->type;

        $invoices = JobProjectInvoice::where('job_project_id', $project->id)
            ->when($from_date && $to_date, function ($q) use ($from_date, $to_date) {
                $q->whereBetween('date', [$from_date, $to_date]);
            })
            ->when($from_date && !$to_date, function ($q) use ($from_date) {
                $q->where('date', $from_date);
            })
            ->when(!$from_date && $to_date, function ($q) use ($to_date) {
                $q->where('date', $to_date);
            })
            ->when($type, function($query) use ($type){
                if($type == 'paid'){
                    $query->where('paid_amount','>', 0);
                }elseif($type == 'due'){
                    $query->where('due_amount','>', 0);
                }
            })->get();

        return view('backend.job-project.cost-analysis.invoices', compact('to_date', 'from_date','project', 'invoices','type'));
    }

    public function receipt(JobProject $project, Request $request){

        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $type = $request->type;

        $invoices = JobProjectInvoice::with('receipt_lists')->where('job_project_id', $project->id)
            ->when($from_date && $to_date, function ($q) use ($from_date, $to_date) {
                $q->whereBetween('date', [$from_date, $to_date]);
            })
            ->when($from_date && !$to_date, function ($q) use ($from_date) {
                $q->where('date', $from_date);
            })
            ->when(!$from_date && $to_date, function ($q) use ($to_date) {
                $q->where('date', $to_date);
            })->get();


         return view('backend.job-project.cost-analysis.receipts', compact('to_date', 'from_date','project', 'invoices','type'));

    }

    public function labourCost(JobProject $project, Request $request){
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        if(!$project){
            return back();
        }

        $new_project = $project->new_project;

        $months = DB::table('employee_attendances')
            ->selectRaw("YEAR(date) as year, MONTH(date) as month")
            ->when($from_date || $to_date, function ($query) use ($from_date, $to_date) {
                if($from_date && $to_date){
                    $query->whereBetween('date', [$from_date, $to_date]);
                }elseif($to_date && !$from_date){
                    $query->where('date',  $to_date);
                }elseif($from_date && !$to_date){
                    $query->where('date', $to_date);
                }
            })
            ->groupByRaw("YEAR(date), MONTH(date)")
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        $data = [];
        $total_cost = 0;

        foreach($months as $month){
            $year = $month->year;
            $month = $month->month;
            $monthYear = $year .'-'.$month;
            if($from_date && $to_date){
                    $date = Carbon::parse($to_date);
            }elseif($from_date && !$to_date){
                $date = Carbon::parse($from_date);
            }elseif($to_date && !$from_date){
                $date = Carbon::parse($to_date);
            }else{
                $date = Carbon::now();
            }

            $totalDaysInMonth = Carbon::parse($monthYear)->daysInMonth;
            $currentDayOfMonth = $date->format('Y-m') == $monthYear ? $date->day : $totalDaysInMonth;

            $attendances = EmployeeAttendance::where('project_id',$project->id)->whereMonth('date', $month)->whereYear('date', $year)
            ->when($from_date || $to_date, function ($query) use ($from_date, $to_date) {
                if($from_date && $to_date){
                    $query->whereBetween('date', [$from_date, $to_date]);
                }elseif($to_date && !$from_date){
                    $query->where('date',  $to_date);
                }elseif($from_date && !$to_date){
                    $query->where('date', $to_date);
                }
            })->get();

            foreach ($attendances as $attendance) {
                $employee = Employee::find($attendance->employee_id);
                if (!$employee) continue;

                $employee_id = $employee->id;
                $employee_name = $employee->full_name;

                $salary_process = SalaryProcess::where('employee_id', $employee->id)
                    ->where('advance_amount', '>', 0)
                    ->get();

                $already_payment = SalaryProcess::where('employee_id', $employee->id)
                    ->where('status', 1)
                    ->where('month', $month)
                    ->where('year', $year)
                    ->get();

                $salary_date = new DateTime("$year-$month-01");
                $last_visite = $employee->last_visite ?? $employee->joining_date ?? null;

                if ($last_visite) {
                    $last = new DateTime($last_visite);
                    $interval = $salary_date->diff($last);
                    $text = ($interval->y * 12) + $interval->m;
                }

                $basic_salary = 0;

                $check_attendance = EmployeeAttendance::check_attendance($employee->id, $month, $year, $basic_salary, $from_date, $to_date, $project->id);

                $overtime_amount = isset($check_attendance['overtime_amount']) ? str_replace(',', '', $check_attendance['overtime_amount']) : 0;
                $late_amount = isset($check_attendance['late_amount']) ? str_replace(',', '', $check_attendance['late_amount']) : 0;
                $total_absen_penalty = $check_attendance['total_absen_penalty'] ?? 0;
                $basic_salary = $check_attendance['basic_salary'] ?? 0;
                $total_absen_penalty = isset($check_attendance['total_absen_penalty']) ? $check_attendance['total_absen_penalty'] : 0;
                $basic_salary_current_day = $basic_salary;

                if ($currentDayOfMonth < $totalDaysInMonth) {
                    $basic_salary_current_day = ($basic_salary / $totalDaysInMonth) * $currentDayOfMonth;
                }

                $total_amount = ($overtime_amount - ($late_amount + $total_absen_penalty)) + $basic_salary_current_day;
                $time = $check_attendance['total_working_hours'] ?? '00:00:00';
                $total_working_seconds = $this->timeToSeconds($time);

                // Initialize if not exists
                if (!isset($data[$employee_id])) {
                    $data[$employee_id] = [
                        'employee_name' => $employee->full_name,
                        'total_days' => 1,
                        'total_working_hours' => 0,
                        'total_overtime' => 0,
                        'total_late_time' => 0,
                        'total_absen' => 0,
                        'overtime_amount' => 0,
                        'late_amount' => 0,
                        'basic_salary_current_day' => 0,
                        'total_cost' => 0,
                        'total_absen_penalty' => 0,
                    ];
                }
                // Increment totals
                $data[$employee_id]['total_days'] += 1;
                $data[$employee_id]['total_working_hours'] += $total_working_seconds;
                $data[$employee_id]['total_overtime'] += $this->timeToSeconds($check_attendance['total_overtime'] ?? 0);
                $data[$employee_id]['total_late_time'] += $this->timeToSeconds($check_attendance['total_late_time'] ?? 0);
                $data[$employee_id]['total_absen'] += $check_attendance['total_absen'] ?? 0;
                $data[$employee_id]['overtime_amount'] += $overtime_amount;
                $data[$employee_id]['late_amount'] += $late_amount;
                $data[$employee_id]['basic_salary_current_day'] += $basic_salary_current_day;
                $data[$employee_id]['total_cost'] += $total_amount;
                $data[$employee_id]['total_absen_penalty'] += isset( $total_absen_penalty) ? $total_absen_penalty : 0;
                $total_cost += $total_amount;
            }
        }

        return view('backend.job-project.cost-analysis.labour-cost',compact('total_cost','to_date', 'from_date', 'project', 'data'));
    }

    public function labourCostDetails(){
        //
    }

    private function timeToSeconds($time) {
        [$h, $m, $s] = explode(':', $time);
        return ($h * 3600) + ($m * 60) + $s;
    }
    public function search_project_report2(Request $request){
        $company_id = $request->company_id ? (array) $request->company_id : [0, null];
        $ids = implode(',', $company_id);
        $selected_company = Subsidiary::whereIn('id', $company_id)->first();
        if (!$selected_company) {
            $selected_company = new Subsidiary();
        }
        $month = $request->month;
        $year = $request->year;
        $from = $request->from ? $this->dateFormat($request->from) : null;
        $to = $request->to ? $this->dateFormat($request->to) : date('Y-m-d');
        $projects = JobProject::query();

        if ($request->company_id_search) {
            $projects->where('compnay_id', $request->company_id_search);
        }
        if($request->plot_no){
            $projects->whereHas('new_project', function($q) use ($request){
                $q->where('plot', 'like', '%' . $request->plot_no . '%');
            });
        }
        if($request->location){
            $projects->whereHas('new_project', function($q) use ($request){
                $q->where('location', 'like', '%' . $request->location . '%');
            });
        }
        if ($request->project_id) {
            $projects->where('id', $request->project_id);
        }
        if($request->party_id){
            $projects->where('customer_id', $request->party_id);
        }
        if($from && $to){
            $projects->whereBetween('start_date', [$from, $to]);
        }
        $projects = $projects->paginate(50);

        $all_parties = PartyInfo::orderBy('pi_name')->get();

        $companies = Subsidiary::orderBy('id', 'desc')->get();
        $all_projects = JobProject::orderBy('project_name')
        ->when($company_id,function($query) use($company_id){
            if($company_id){
                if($company_id > 0){
                    $query->whereNull('compnay_id');
                }else{
                    $query->where('compnay_id', $company_id);
                }
            }
        })->get();
        return view('backend.job-project.cost-analysis.project-report', compact( 'projects', 'to', 'from', 'all_parties', 'year', 'month',  'selected_company', 'companies', 'all_projects'));
    }
    public function search_project_report(Request $request){
        $company_id = $request->company_id_search ? (array) $request->company_id_search : [0, null];
        $month = $request->month;
        $year = $request->year;
        $from = $request->from ? $this->dateFormat($request->from) : null;
        $to = $request->to ? $this->dateFormat($request->to) : date('Y-m-d');
        $party = $request->party_id;
        $searched_project = null;

        $projects = JobProject::query();
        if ($request->company_id_search) {
            $projects->where('compnay_id', $request->company_id_search);
        }
        if ($request->plot_no) {
            $projects->whereHas('new_project', function($q) use ($request){
                $q->where('plot', 'like', '%' . $request->plot_no . '%');
            });
        }
        if ($request->location) {
            $projects->whereHas('new_project', function($q) use ($request){
                $q->where('location', 'like', '%' . $request->location . '%');
            });
        }
        if ($request->project_id) {
            $projects->where('id', $request->project_id);
        }
        if ($request->party_id) {
            $projects->where('customer_id', $request->party_id);
        }
        if ($from && $to) {
            $projects->whereBetween('start_date', [$from, $to]);
        }

        $projects = $projects->with(['journalRecords' => function($q) use ($company_id) {
            $q->whereIn('account_type_id', [1, 2])
            ->whereNotIn('account_head_id', [19]);

            $q->orderBy('journal_date', 'ASC')
            ->select('journal_id', 'journal_date', 'job_project_id')
            ->distinct();
        }])->get();

        $all_parties = PartyInfo::orderBy('pi_name')->get();

        $companies = Subsidiary::orderBy('id', 'desc')->get();
        $all_projects = JobProject::orderBy('project_name')
        ->when($company_id,function($query) use($company_id){
            if($company_id){
                if($company_id > 0){
                    $query->whereNull('compnay_id');
                }else{
                    $query->where('compnay_id', $company_id);
                }
            }
        })->get();
        return view('backend.job-project.cost-analysis.project-report', compact( 'projects', 'to', 'from', 'all_parties', 'year', 'month', 'companies', 'all_projects'));
    }
}

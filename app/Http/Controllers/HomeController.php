<?php

namespace App\Http\Controllers;

use App\Fifo;
use App\FifoInvoice;
use App\Invoice;
use App\InvoiceAmount;
use App\Employee;
use App\InvoiceItem;
use App\ItemList;
use App\JobProject;
use App\JobProjectInvoice;
use App\JobProjectInvoiceTask;
use App\JobProjectTask;
use App\JobProjectTemInvoice;
use App\JobProjectTemInvoiceTask;
use App\Journal;
use App\JournalRecord;
use App\LpoPorjectTask;
use App\LpoProject;
use App\Models\BankDetail;
use App\Models\CostCenter;
use App\Models\MasterAccount;
use App\PartyInfo;
use App\Permission;
use App\Role;
use App\ProfitCenter;
use App\ProjectDetail;
use App\Purchase;
use App\PurchaseDetail;
use App\Setting;
use App\Stock;
use App\Models\AccountHead;
use App\Payment;
use App\PurchaseExpense;
use App\Receipt;
use App\ReceiptSale;
use App\StockTransection;
use App\TempReceiptVoucher;
use App\TempReceiptVoucherDetail;
use App\AccountSubHead;
use App\BillOfQuantity;
use App\EngineerReport;
use App\LpoBill;
use Carbon\Carbon;
use App\NewProject;
use App\PaymentInvoice;
use App\PurchaseExpenseItem;
use App\PurchaseExpenseItemTemp;
use App\PurchaseExpenseTemp;
use App\Requisition;
use App\TempPaymentVoucher;
use App\TempPaymentVoucherDetail;
use App\User;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private function dateFormat($date)
    {
        $old_date = explode('/', $date);

        $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $date_to = null;
        $date_from = null;
        // if($request->date_to){
        //     $date_to = $this->dateFormat($request->date_to);
        // }else{
        //     $date_to = date('Y-m-d');
        // }
        // if($request->date_from){
        //     $date_from = $this->dateFormat($request->date_from);
        // }else{
        //     $date_from = date('Y-m-d');
        // }
        // $date=date('Y-m-d');
        $sales = JobProjectInvoice::orderBy('id', 'desc')->get();
        $expenses = PurchaseExpense::orderBy('date', 'desc')->get();
        $payments = Payment::orderBy('date', 'desc')->get();
        $receipt_list = Receipt::orderBy('date', 'desc')->get();


        $receivable = JobProjectInvoice::select(
            DB::raw('SUM((budget - (paid_amount/(budget+vat-discount_amount) * (budget-discount_amount)))) as net_receivable'),
            DB::raw('SUM((vat - (paid_amount/(budget+vat-discount_amount) * vat))) as vat_receivable')
        )->first();
        $payble_cr = JournalRecord::where('account_head_id', 5)->whereYear('created_at', date('Y'))->where('transaction_type', 'CR')->get()->sum('total_amount');
        $payble_dr = JournalRecord::where('account_head_id', 5)->whereYear('created_at', date('Y'))->where('transaction_type', 'DR')->get()->sum('total_amount');
        // $payble = $payble_cr - $payble_dr;
        $payble = PurchaseExpense::where('due_amount', '>', 0)->sum('due_amount');
        $cash_cr = JournalRecord::where('account_head_id', 1)->where('transaction_type', 'CR')->get()->sum('total_amount');
        $cash_dr = JournalRecord::where('account_head_id', 1)->where('transaction_type', 'DR')->get()->sum('total_amount');
        $cash = $cash_dr - $cash_cr;
        // $m_sales = JobProjectInvoice::orderBy('id', 'desc')->whereMonth('date',date('m'))->whereYear('date',date('Y'))->sum('total_budget');
        $income_cr = JournalRecord::where('account_type_id', 3)->where('transaction_type', 'CR')->get()->sum('total_amount');
        $income_dr = JournalRecord::where('account_type_id', 3)->where('transaction_type', 'DR')->get()->sum('total_amount');
        $m_sales = $sales->where('retention_invoice', false)->sum('budget') + $sales->where('retention_invoice', false)->sum('retention_amount');

        $projects = JobProject::orderBy('project_name')->get();
        $running_projects = JobProject::orderBy('id', 'desc')
            ->where(function ($q) {
                $q->where('avarage_complete', '<', 100)
                    ->orWhereNull('avarage_complete');
            })->get();
        $total_project_value = $projects->sum('budget');
        $total_projects = $projects->count();

        $total_running_project_value = $running_projects->sum('budget');
        $total_running_projects = $running_projects->count();

        $ongoing_projects = NewProject::where('status', 'in progress')->get();
        $completed_projects = NewProject::where('status', 'completed')->get();

        $accrued_receivable = DB::table('job_projects as jp')
        ->leftJoin(DB::raw('(SELECT job_project_id, SUM(budget) as invoice_sum
                            FROM job_project_invoices
                            GROUP BY job_project_id) as jpi'),
                'jp.id', '=', 'jpi.job_project_id')
        ->selectRaw('
            SUM(GREATEST(jp.budget - COALESCE(jpi.invoice_sum,0), 0)) as grand_accrued_receivable
        ')
        ->first()
        ->grand_accrued_receivable;



        $daily_reports = EngineerReport::where('engineer_id', auth()->user()->employee_id)->orderBy('id', 'desc')->limit(10)->get();

        // $daily_reports = EngineerReport::where('engineer_id', auth()->user()->employee_id)->orderBy('id','desc')->limit(10)->get();
        $daily_reports = [];


        return view('home', compact(
            'expenses',
            'payments',
            'receipt_list',
            'sales',
            'receivable',
            'payble',
            'cash',
            'm_sales',

            'projects',
            'daily_reports',
            'daily_reports',
            'accrued_receivable',
            'ongoing_projects',
            'completed_projects',
            'date_to',
            'date_from',
            'total_running_projects',
            'total_running_project_value',
            'total_project_value',
            'total_projects'

        ));
    }

    public function SearchAjax(Request $request, $id)
    {

        if ($id == "masterAcc") {
            $masterDetails = MasterAccount::where('mst_ac_code', 'like', "%{$request->q}%")
                ->orWhere('mst_ac_head', 'like', "%{$request->q}%")
                ->orWhere('mst_definition', 'like', "%{$request->q}%")
                ->orWhere('mst_ac_type', 'like', "%{$request->q}%")
                ->orWhere('vat_type', 'like', "%{$request->q}%")
                ->latest()
                ->take(40)
                ->get();
            $i = 1;

            if ($request->ajax()) {
                return Response()->json(['page' => view('backend.ajax.masterAccTbody', ['masterDetails' => $masterDetails, 'i' => $i])->render()]);
            }
        }

        if ($id == "costCenter") {
            $costCenters = CostCenter::where('cc_code', 'like', "%{$request->q}%")
                ->orWhere('cc_name', 'like', "%{$request->q}%")
                ->latest()
                ->take(40)
                ->get();
            $i = 1;
            if ($request->ajax()) {
                return Response()->json(['page' => view('backend.ajax.costCenterTbody', ['costCenters' => $costCenters, 'i' => $i])->render()]);
            }
        }

        if ($id == "projectDetails") {
            $projDetails = ProjectDetail::where('proj_no', 'like', "%{$request->q}%")
                ->orWhere('proj_name', 'like', "%{$request->q}%")
                ->orWhere('cont_no', 'like', "%{$request->q}%")
                ->latest()
                ->take(40)
                ->get();
            $i = 1;

            if ($request->ajax()) {
                return Response()->json(['page' => view('backend.ajax.projectDetailsTbody', ['projDetails' => $projDetails, 'i' => $i])->render()]);
            }
        }

        if ($id == "bankDetails") {
            $bankDetails = BankDetail::where('bank_code', 'like', "%{$request->q}%")
                ->orWhere('bank_name', 'like', "%{$request->q}%")
                ->orWhere('ac_no', 'like', "%{$request->q}%")
                ->latest()
                ->take(40)
                ->get();
            $i = 1;
            if ($request->ajax()) {
                return Response()->json(['page' => view('backend.ajax.bankDetailsTbody', ['bankDetails' => $bankDetails, 'i' => $i])->render()]);
            }
        }


        if ($id == "profitCenter") {
            $profitDetails = ProfitCenter::where('pc_code', 'like', "%{$request->q}%")
                ->orWhere('pc_name', 'like', "%{$request->q}%")
                ->latest()
                ->take(40)
                ->get();
            $i = 1;
            if ($request->ajax()) {
                return Response()->json(['page' => view('backend.ajax.profitCenterTbody', ['profitDetails' => $profitDetails, 'i' => $i])->render()]);
            }
        }


        if ($id == "partyCenter") {
            $partyInfos = PartyInfo::where('pi_code', 'like', "%{$request->q}%")
                ->orWhere('pi_name', 'like', "%{$request->q}%")
                ->orWhere('trn_no', 'like', "%{$request->q}%")
                ->latest()
                ->take(40)
                ->get();
            $i = 1;
            if ($request->ajax()) {
                return Response()->json(['page' => view('backend.ajax.partyInfoTbody', ['partyInfos' => $partyInfos, 'i' => $i])->render()]);
            }
        }
    }

    public function emp_party()
    {
        $employees = Employee::get();
        foreach ($employees as $emp) {
            $check = PartyInfo::where('emp_id', $emp->id)->first();
            if (!$check) {
                $latest = PartyInfo::withTrashed()->orderBy('id', 'DESC')->first();

                if ($latest) {
                    $pi_code = preg_replace('/^PI-/', '', $latest->pi_code);
                    ++$pi_code;
                } else {
                    $pi_code = 1;
                }
                if ($pi_code < 10) {
                    $c_code = "PI-000" . $pi_code;
                } elseif ($pi_code < 100) {
                    $c_code = "PI-00" . $pi_code;
                } elseif ($pi_code < 1000) {
                    $c_code = "PI-0" . $pi_code;
                } else {
                    $c_code = "PI-" . $pi_code;
                }

                $draftCost = new PartyInfo();
                $draftCost->pi_code = $c_code;
                $draftCost->pi_name = $emp->first_name;
                $draftCost->emp_id  = $emp->id;
                $draftCost->pi_type = 'Employee';

                $draftCost->email = $emp->email;
                $sv = $draftCost->save();
            }
        }
    }


    public function p_number_correction()
    {
        $temps = PurchaseExpenseTemp::get();
        foreach ($temps as $temp) {
            $temp->purchase_no = 'P-' . $temp->purchase_no;
            $temp->save();
        }
        $mains = PurchaseExpense::get();
        foreach ($mains as $main) {
            $main->purchase_no = 'P-' . $main->purchase_no;
            $main->save();
        }
        dd('Alhamdulillah');
    }


    public function payment_number_correction()
    {
        $temps = TempPaymentVoucher::get();
        foreach ($temps as $temp) {
            $temp->payment_no = 'PV-' . $temp->payment_no;
            $temp->save();
        }

        $mains = Payment::get();
        foreach ($mains as $main) {
            $main->payment_no = 'PV-' . $main->payment_no;
            $main->save();
        }
        dd('Alhamdulillah');
    }


    public function delete_demo()
    {
        $lpos = LpoProject::whereIn('id', [4, 8, 9, 10, 19, 20, 21, 3, 1])->get();
        foreach ($lpos as $lpo) {
            LpoPorjectTask::where('lpo_project_id', $lpo->id)->delete();
            $workOrders = JobProject::where('lpo_projects_id', $lpo->id)->get();
            foreach ($workOrders as $itm) {
                JobProjectTask::where('job_project_id', $itm->id)->delete();
                $tems = JobProjectTemInvoice::where('job_project_id', $itm->id)->get();
                foreach ($tems as $tem) {
                    JobProjectTemInvoiceTask::where('invoice_id', $tem->id)->delete();
                    $tem->delete();
                }
                $invoicess = JobProjectInvoice::where('job_project_id', $itm->id)->get();
                foreach ($invoicess as $inv) {
                    JobProjectInvoiceTask::where('invoice_id', $inv->id)->delete();
                    $receipts = ReceiptSale::where('sale_id', $inv->id)->get();
                    foreach ($receipts as $receipt) {
                        $rec = Receipt::find($receipt->payment_id);
                        $journal = Journal::where('receipt_id', $rec ? $rec->id : '')->first();
                        if ($journal) {
                            JournalRecord::where('journal_id', $journal->id)->delete();
                            $journal->delete();
                        }
                        if ($rec) {
                            $rec->delete();
                        }
                        $receipt->delete();
                    }
                    $temps = TempReceiptVoucherDetail::where('sale_id', $inv->id)->get();
                    foreach ($temps as $temp) {
                        $receipt = TempReceiptVoucher::find($temp->payment_id);
                        $receipt->delete();
                        $temp->delete();
                    }

                    $journal = Journal::where('invoice_id', $inv->id)->first();
                    if ($journal) {
                        JournalRecord::where('journal_id', $journal->id)->delete();
                        $journal->delete();
                    }
                    $inv->delete();
                }

                $itm->delete();
            }
            $lpo->delete();
        }
        dd('Alhamdulillah');
    }

    public function requirement()
    {
        return view('program-view.requirement-list');
    }
    public function moduls_list()
    {
        return view('program-view.moduls');
    }

    public function sub_head_create()
    {
        $employee = Employee::orderBy('full_name')->whereNotIn('division', [4])->get();
        // dd($employee);
        foreach ($employee as $empl) {
            $sub_head = new AccountSubHead;
            $sub_head->office_id = 1;
            $sub_head->account_head_id = 93;
            $sub_head->name = $empl->full_name;
            $sub_head->employee_id = $empl->id;
            $sub_head->save();
        }
        return back();
    }



    // runing projects filter option
    public function getProjectsData(Request $request)
    {
        $columns = [
            'id',
            'company_name',
            'project_name',
            'project_code',
            'customer',
            'total_budget',
            'start_date',
            'end_date',
            'avarage_complete'
        ];

        // Base query
        $query = JobProject::query()
            ->with(['company', 'party', 'new_project', 'quotation'])
            ->where(function ($q) {
                $q->where('avarage_complete', '<', 100)
                    ->orWhereNull('avarage_complete');
            });

        // ===== Date Range Filter =====
        if (!empty($request->date_from) && !empty($request->date_to)) {
            try {
                $dateFrom = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_from)->format('Y-m-d');
                $dateTo   = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_to)->format('Y-m-d');

                $query->whereBetween('start_date', [$dateFrom, $dateTo]);
            } catch (\Exception $e) {
                // Invalid date format — skip filter
            }
        }

        $totalData = $query->count();

        // ===== Search Filtering =====
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];

            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                    ->orWhere('project_code', 'like', "%{$search}%")
                    ->orWhereHas('company', function ($q2) use ($search) {
                        $q2->where('company_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('party', function ($q2) use ($search) {
                        $q2->where('pi_name', 'like', "%{$search}%");
                    });
            });
        }

        $totalFiltered = $query->count();

        // ===== Ordering =====
        $orderColumnIndex = $request->order[0]['column'] ?? 0;
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $orderDir = $request->order[0]['dir'] ?? 'asc';
        $query->orderBy($orderColumn, $orderDir);

        // ===== Pagination =====
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $projects = $query->offset($start)->limit($length)->get();

        // ===== Data Formatting =====
        $data = [];
        foreach ($projects as $project) {
            $nestedData = [];
            $nestedData['id'] = $project->id;
            $nestedData['company_name'] = $project->company->company_name ?? 'SBBC';
            $nestedData['project_name'] = $project->project_id
                ? ($project->new_project ? $project->new_project->name : '')
                : $project->project_name;
            $nestedData['project_code'] = $project->project_code;
            $nestedData['customer'] = $project->party->pi_name ?? '';
            $nestedData['total_budget'] = number_format($project->total_budget, 2);

            $nestedData['start_date'] = $project->start_date
                ? \Carbon\Carbon::parse($project->start_date)->format('d/m/Y')
                : '...';
            $nestedData['end_date'] = $project->end_date
                ? \Carbon\Carbon::parse($project->end_date)->format('d/m/Y')
                : '...';

            $nestedData['avarage_complete'] = $project->avarage_complete
                ? number_format($project->avarage_complete, 2) . '%'
                : '0%';

            $nestedData['action'] = '
                <button
                    class="project-btn btn-primary view-project"
                    style="border: none; border-radius: 5px;"
                    data-id="' . $project->id . '"
                    data-url="' . route('projects.show', $project->id) . '"
                    data-quotation="' . ($project->quotation->project_code ?? '') . '"
                    data-invoice="' . $project->is_invoice . '"
                    title="View">
                    👁
                </button>';

            $data[] = $nestedData;
        }

        // ===== JSON Response =====
        $json_data = [
            "draw" => intval($request->draw),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        ];

        return response()->json($json_data);
    }




    public function dashboardAjax(Request $request)
    {
        $dateFrom = null;
        $dateTo = null;
        if (!empty($request->date_from) && !empty($request->date_to)) {

            $dateFrom = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_from)->format('Y-m-d');
            $dateTo   = \Carbon\Carbon::createFromFormat('d/m/Y', $request->date_to)->format('Y-m-d');
        }
        $projects = JobProject::when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('start_date', [$dateFrom, $dateTo]);
        })->paginate(20);

        $totals = DB::table('job_projects as jp')
            ->leftJoin(DB::raw('(SELECT job_project_id, SUM(budget) as invoice_sum
                                FROM job_project_invoices
                                GROUP BY job_project_id) as jpi'),
                    'jp.id', '=', 'jpi.job_project_id')
            ->selectRaw('
                SUM(jp.budget) as grand_total_budget,
                SUM(GREATEST(jp.budget - COALESCE(jpi.invoice_sum,0), 0)) as grand_accrued_receivable,
                SUM(COALESCE(jpi.invoice_sum,0)) as grand_total_received
            ')
            ->first();



        return view('backend.ajax.accrued_receivable', [
            'ongoing_projects' => $projects,
            'totals' => $totals,
        ]);
    }


    // permission wise dashboard data show
    public function user_dashboard_data(Request $request)
    {
        $dateFrom = $request->date_from
            ? Carbon::createFromFormat('d/m/Y', $request->date_from)->startOfDay()
            : now()->subDays(30);

        $dateTo = $request->date_to
            ? Carbon::createFromFormat('d/m/Y', $request->date_to)->endOfDay()
            : now();

        $user = Auth::user();

        // Determine if the user is administration
        $isAdmin = $user->role && $user->role->slug === 'administration';

        // User filter only applies to non-admins
        $userFilter = function ($q) use ($user, $isAdmin) {
            if (!$isAdmin) {
                $q->where('created_by', $user->id)
                ->orWhere('approved_by', $user->id)
                ->orWhere('edited_by', $user->id);
            }
        };

        $queries = [];

        $models = [
            ['model' => BillOfQuantity::class, 'ref' => 'boq_no', 'status' => 'status', 'reason' => 'reject_reason'],
            ['model' => LpoProject::class, 'ref' => 'project_code', 'status' => 'has_work_order', 'reason' => null],
            ['model' => Requisition::class, 'ref' => 'requisition_no', 'status' => 'status', 'reason' => 'reject_reason'],
            ['model' => LpoBill::class, 'ref' => 'lpo_bill_no', 'status' => 'status', 'reason' => null],
            ['model' => PurchaseExpense::class, 'ref' => 'purchase_no', 'status' => null, 'reason' => null],
            ['model' => Payment::class, 'ref' => 'payment_no', 'status' => 'status', 'reason' => 'reject_reason'],
            ['model' => JobProjectInvoice::class, 'ref' => 'invoice_no', 'status' => 'status', 'reason' => null],
            ['model' => JobProject::class, 'ref' => 'project_code', 'status' => 'status', 'reason' => null],
            ['model' => Receipt::class, 'ref' => 'receipt_no', 'status' => 'status', 'reason' => null],
            ['model' => TempPaymentVoucher::class, 'ref' => 'payment_no', 'status' => null, 'reason' => null],
            ['model' => TempReceiptVoucher::class, 'ref' => 'receipt_no', 'status' => null, 'reason' => null],
        ];

        // Build queries
        foreach ($models as $m) {
            $statusCol = $m['status'] ?? 'NULL';
            $reasonCol = $m['reason'] ?? 'NULL';
            $sourceName = Str::title(Str::plural(Str::snake(class_basename($m['model']), ' ')));

            $queries[] = $m['model']::selectRaw(
                "'{$sourceName}' as source,
                id,
                ".$m['ref']." as ref_no,
                {$statusCol} as status,
                {$reasonCol} as reject_reason,
                created_at, created_by, approved_by, edited_by"
            )
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->when(!$isAdmin, $userFilter);
        }

        // Union all queries
        $finalQuery = array_shift($queries);
        foreach ($queries as $query) {
            $finalQuery->unionAll($query);
        }

        // Get all data
        $allData = DB::table(DB::raw("({$finalQuery->toSql()}) as combined"))
            ->mergeBindings($finalQuery->getQuery())
            ->orderBy('created_at', 'desc')
            ->get();

        // Collect all unique user IDs
        $userIds = $allData->pluck('created_by')
            ->merge($allData->pluck('approved_by'))
            ->merge($allData->pluck('edited_by'))
            ->filter()
            ->unique();

        // Fetch user info with role and division
        $users = User::with(['role', 'emp.dvision'])
            ->whereIn('id', $userIds)
            ->get()
            ->keyBy('id');

        $mapUserInfo = function ($userId) use ($users) {
            if (!$userId || !isset($users[$userId])) return null;
            $user = $users[$userId];
            return [
                'id'            => $user->id,
                'name'          => $user->name,
                'role'          => $user->role ? $user->role->name : null,
                'division_name' => $user->emp && $user->emp->dvision ? $user->emp->dvision->name : null,
                'emp_id'        => $user->emp ? $user->emp->emp_id : null,
            ];
        };

        // Map user info into all data
        $allData = $allData->map(function ($item) use ($mapUserInfo) {
            $item->created_by_info  = $mapUserInfo($item->created_by);
            $item->approved_by_info = $mapUserInfo($item->approved_by);
            $item->edited_by_info   = $mapUserInfo($item->edited_by);
            return $item;
        });

        // Grouped collections for executive/approver/edited
        $createdByData  = $allData->whereNotNull('created_by')->groupBy('created_by')->map(function ($items, $userId) use ($mapUserInfo) {
            return [
                'user'  => $mapUserInfo($userId),
                'items' => $items
            ];
        });

        $approvedByData = $allData->whereNotNull('approved_by')->groupBy('approved_by')->map(function ($items, $userId) use ($mapUserInfo) {
            return [
                'user'  => $mapUserInfo($userId),
                'items' => $items
            ];
        });

        $editedByData   = $allData->whereNotNull('edited_by')->groupBy('edited_by')->map(function ($items, $userId) use ($mapUserInfo) {
            return [
                'user'  => $mapUserInfo($userId),
                'items' => $items
            ];
        });

        // Return data for DataTables
        return [
            'data'             => $allData,
            'created_by_data'  => $createdByData,
            'approved_by_data' => $approvedByData,
            'edited_by_data'   => $editedByData,
        ];
    }
    
    
    public function party_correction()
    {
        // dd('i am here to blast');
        DB::transaction(function () {
            // Step 1: Find all duplicate (pi_name, pi_type) combinations
            $duplicates = DB::table('party_infos')
                ->select('pi_name', 'pi_type', DB::raw('COUNT(*) as total'))
                ->groupBy('pi_name', 'pi_type')
                ->having('total', '>', 1)
                ->get();

            // Step 2: Process each duplicate group
            foreach ($duplicates as $d) {
                // Find all duplicates for this combination
                $parties = PartyInfo::where('pi_name', $d->pi_name)
                    ->where('pi_type', $d->pi_type)
                    ->orderBy('id', 'asc') // keep the oldest (smallest ID)
                    ->get();

                if ($parties->count() < 2) {
                    continue;
                }

                // First one to keep
                $keeper = $parties->first();

                // All others to delete
                $duplicateIds = $parties->pluck('id')->skip(1)->toArray();
                

                if (!empty($duplicateIds)) {
                    // Update related receipts in one query
                    Receipt::whereIn('party_id', $duplicateIds)->update(['party_id' => $keeper->id]);
                    ReceiptSale::whereIn('party_id',$duplicateIds)->update(['party_id' => $keeper->id]);

                    TempReceiptVoucher::whereIn('party_id', $duplicateIds)->update(['party_id' => $keeper->id]);
                    TempReceiptVoucherDetail::whereIn('party_id',$duplicateIds)->update(['party_id' => $keeper->id]);
                    PurchaseExpenseTemp::whereIn('party_id',$duplicateIds)->update(['party_id' => $keeper->id]);
                    PurchaseExpenseItemTemp::whereIn('party_id',$duplicateIds)->update(['party_id' => $keeper->id]);
                    PurchaseExpense::whereIn('party_id',$duplicateIds)->update(['party_id' => $keeper->id]);
                    PurchaseExpenseItem::whereIn('party_id',$duplicateIds)->update(['party_id' => $keeper->id]);
                    Payment::whereIn('party_id',$duplicateIds)->update(['party_id' => $keeper->id]);
                    PaymentInvoice::whereIn('party_id',$duplicateIds)->update(['party_id' => $keeper->id]);
                    TempPaymentVoucher::whereIn('party_id',$duplicateIds)->update(['party_id' => $keeper->id]);
                    TempPaymentVoucherDetail::whereIn('party_id',$duplicateIds)->update(['party_id' => $keeper->id]);
                    Journal::whereIn('party_info_id',$duplicateIds)->update(['party_info_id' => $keeper->id]);
                    JournalRecord::whereIn('party_info_id',$duplicateIds)->update(['party_info_id' => $keeper->id]);
                    JobProject::whereIn('customer_id',$duplicateIds)->update(['customer_id' => $keeper->id]);
                    JobProject::whereIn('customer_id',$duplicateIds)->update(['customer_id' => $keeper->id]);
                    JobProjectInvoice::whereIn('customer_id',$duplicateIds)->update(['customer_id' => $keeper->id]);
                    JobProjectTemInvoice::whereIn('customer_id',$duplicateIds)->update(['customer_id' => $keeper->id]);
                    LpoBill::whereIn('party_id',$duplicateIds)->update(['party_id' => $keeper->id]);
                    // Delete duplicates
                    PartyInfo::whereIn('id', $duplicateIds)->delete();
                }
            }
        });
    }





}

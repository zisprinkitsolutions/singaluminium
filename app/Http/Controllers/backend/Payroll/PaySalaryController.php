<?php

namespace App\Http\Controllers\backend\Payroll;

use App\DebitCreditVoucher;
use App\Http\Controllers\Controller;
use App\Journal;
use App\JournalRecord;
use App\Mapping;
use App\Models\Payroll\PaySalary;
use App\Models\Payroll\PaymentInformation;
use App\Models\Payroll\Employee;
use App\Models\AccountHead;
use App\Models\Payroll\Grade;
use App\PartyInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;

class PaySalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Gate::authorize('app.mapping.index');
        $paySalarys = PaySalary::query();
        if ($request->has('date')) {
            $month = date('F',strtotime($request->date));
            $year = date('Y',strtotime($request->date));
            $paySalarys->where('month', $month)->where('year', $year);
        }else{
            $date = Carbon::now();
            $month = $date->format('F');
            $year = $date->format('Y');
            $paySalarys->where('month', $month)->where('year', $year);
        }
        $grades = Grade::orderBy('id')->get();
        $employees = Employee::orderBy('id')->get();
        $paySalarys = $paySalarys->get();
        return view('backend.payroll.pay_salary.index', compact('paySalarys', 'grades', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // dd($request->date);
        // return 1;
        //Gate::authorize('app.mapping.index');
        $month = null;
        $year = null;
        $payroll_lists = PaySalary::where('due','!=',0)->orderBy('id', 'asc');
        if($request->has('date')){
            $month = date('F',strtotime($request->date));
            $year = date('Y',strtotime($request->date));
            $payroll_lists  = $payroll_lists->where('month', $month)->where('year', $year);
            dd($payroll_lists);

        }
        $payroll_lists = $payroll_lists->get();
        // return view('backend.payroll..pay_salary.pay-modal' ,compact('payroll_lists','month'));

        return Response()->json([
            'page' => view('backend.payroll..pay_salary.pay-modal', ['payroll_lists' => $payroll_lists,
                                                                     'month' => $month])->render(),

        ]);
        // return view('backend.payroll.pay_salary.pay-modal', compact('payroll_lists','month',));
    }

    public function payRequest(Request $request)
    {
        // dd($request->has('date'));
        $month = null;
        $year = null;
        $payroll_lists = PaySalary::where('due','!=',0)->orderBy('id', 'asc');
        if($request->has('date')){
            $month = date('F',strtotime($request->date));
            $year = date('Y',strtotime($request->date));
            $payroll_lists  = $payroll_lists->where('month', $month)->where('year', $year);
            // dd($payroll_lists);

        }
        $payroll_lists = $payroll_lists->get();


        return Response()->json([
            'page' => view('backend.payroll..pay_salary.pay-modal', ['payroll_lists' => $payroll_lists,
                                                                     'month' => $month])->render(),

        ]);
        // return view('backend.payroll.pay_salary.pay-modal', compact('payroll_lists','month',));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if ($request->has($request->employee_id['id'])) {
        //     dd($request);
        // }else{
        //     dd($request->employee_id['id']);
        // }

        $month = $request->month;
        $year = $request->year;

        // $request->validate([
        //     'id' => 'required',
        // ]);
        // dd());
        if (isset($request->employee_id['id'])) {
            foreach ($request->employee_id['id'] as $key => $value) {
                $id = $request->employee_id['id'][$key];
                $month = $request->employee_id['month'][$key];
                $year = $request->employee_id['year'][$key];
                $salary_info = PaySalary::where('employee_id', $id)->where('month', $month)->where('year', $year)->first();
                $pi=PaymentInformation::create([
                    'employee_id' => $request->employee_id['id'][$key],
                    'pay_salary_id' => $salary_info->id,
                    'paid' => $request->employee_id['pay_salary'][$key],
                    'was_due' => $salary_info->due,
                    'month' => $request->employee_id['month'][$key],
                    'year' => $request->employee_id['year'][$key],
                ]);

                PaySalary::find($salary_info->id)->update([
                    'paid' => $salary_info->paid + $request->employee_id['pay_salary'][$key],
                    'due' => $salary_info->due - $request->employee_id['pay_salary'][$key],
                ]);


                // *****************JournalEntry*******************
                $sub_invoice = Carbon::now()->format('Ymd');
                        $latest_journal_no = Journal::withTrashed()->whereDate('created_at', Carbon::today())->where('journal_no', 'LIKE', "%{$sub_invoice}%")->orderBy('id','DESC')->first();
                        if ($latest_journal_no)
                        {
                            $journal_no = substr($latest_journal_no->journal_no,0,-1);
                            $journal_code = $journal_no + 1;
                            $journal_no = $journal_code . "J";
                        }
                        else
                        {
                            $journal_no = Carbon::now()->format('Ymd') . '001' . "J";
                        }

                          $journal_main= AccountHead::find(5); // Payable  Account

                          $party=PartyInfo::where('emp_id', $id)->first();
                          $employee_inf=Employee::find($id);

                          $journal= new Journal();
                          $journal->project_id        = 1;
                          $journal->journal_no        = $journal_no;
                          $journal->date              = Carbon::now()->toDateString();
                          $journal->invoice_no        = 'N/A';
                          $journal->source            = 'Salary Pay';
                          $journal->pay_mode            = "Cash";
                          $journal->pay_salary_id            =$pi->id;

                          $journal->cost_center_id    = 0;
                          $journal->profit_center_id    = 1;
                          $journal->party_info_id     = $party->id;
                          $journal->account_head_id   = $journal_main->id;
                          $journal->amount            = $request->employee_id['pay_salary'][$key];
                          $journal->tax_rate          = 0;
                          $journal->vat_amount        = 0;
                          $journal->voucher_type      = "CR";
                          $journal->total_amount      = $request->employee_id['pay_salary'][$key];
                          $journal->narration         = "Salary  Pay";
                          $journal->authorized      = 1;
                          $journal->approved      = 1;

                          $journal->approved_by         = Auth::id();
                          $journal->created_by         = Auth::id();
                          $journal->authorized_by         = Auth::id();
                          $journal->save();



                        $jl_record= new JournalRecord();
                        $jl_record->journal_id     = $journal->id;
                        $jl_record->project_details_id  = 0;
                        $jl_record->cost_center_id    = 0;
                        $jl_record->profit_center_id    = 1;
                        $jl_record->party_info_id       = $party->id;
                        $jl_record->journal_no          = $journal_no;
                        $jl_record->account_head_id     = $journal_main->id;
                        $jl_record->master_account_id   = $journal_main->master_account_id;
                        $jl_record->account_head        = $journal_main->fld_ac_head;
                        $jl_record->amount              = $request->employee_id['pay_salary'][$key];
                        $jl_record->total_amount              =$request->employee_id['pay_salary'][$key];
                        $jl_record->is_main_head              = 1;
                        $jl_record->transaction_type    = 'DR';
                        $jl_record->account_type_id        = 0;
                        $jl_record->journal_date        = Carbon::now()->toDateString();
                        $jl_record->save();



                        if($employee_inf->payment_method=='bank')
                        {
                            $journal_main= AccountHead::find(2);
                        }
                        else{
                            $journal_main= AccountHead::find(1);

                        }


                        $jl_record= new JournalRecord();
                        $jl_record->journal_id     = $journal->id;
                        $jl_record->project_details_id  = 0;
                        $jl_record->cost_center_id    = 0;
                        $jl_record->profit_center_id    = 1;
                        $jl_record->party_info_id       = $party->id;
                        $jl_record->journal_no          = $journal_no;
                        $jl_record->account_head_id     = $journal_main->id;
                        $jl_record->master_account_id   = $journal_main->master_account_id;
                        $jl_record->account_head        = $journal_main->fld_ac_head;
                        $jl_record->amount              =   $request->employee_id['pay_salary'][$key];
                        $jl_record->total_amount              =  $request->employee_id['pay_salary'][$key];
                        $jl_record->is_main_head              = 1;
                        $jl_record->transaction_type    = 'CR';
                        $jl_record->account_type_id        = 0;
                        $jl_record->journal_date        = Carbon::now()->toDateString();
                        $jl_record->save();






                //*************Journalentry end************************
            }
            $notification= array(
                'message'       => 'Employee Salary Added successfully!',
                'alert-type'    => 'success'
            );
        }else{
            $notification= array(
                'message'       => 'Please checked atleast one item!',
                'alert-type'    => 'warning'
            );
        }



     return redirect('pay-salary')->with($notification);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //printDocument
    }

    public function printDocument()
    {

        //Gate::authorize('app.mapping.index');
        // $paySalarys = PaySalary::orderBy('id', 'desc')->get();
        // $employees = Employee::all();
        // dd($facitities);
        return view('backend.payroll.pay_salary.print');
    }


}

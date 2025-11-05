<?php

namespace App\Http\Controllers\backend\Payroll;

use App\DebitCreditVoucher;
use App\Http\Controllers\Controller;
use App\Journal;
use App\JournalRecord;
use App\Mapping;
use App\Models\AccountHead;
use App\Models\Payroll\EmployeeSalary;
use App\Models\Payroll\Employee;
use App\Models\Payroll\SalaryStructure;
use App\Models\Payroll\ComponentType;
use App\Models\Payroll\DeductionEntry;
use App\Models\Payroll\DeductionProcess;
use App\Models\Payroll\EMILoan;
use App\Models\Payroll\EmployeeAttendance;
use App\Models\Payroll\ExtraSalaryComponentHistory;
use App\Models\Payroll\GradeWiseSalaryComponentHistory;
use App\Models\Payroll\Installment;
use App\Models\Payroll\InstallmentProcess;
use App\Models\Payroll\LoanPayment;
use App\Models\Payroll\OvertimeLatesSalaryProcess;
use App\Models\Payroll\PaySalary;
use App\Models\Payroll\SalaryApprovalDocument;
use App\Models\Payroll\SalaryComponent;
use App\Models\Payroll\SalaryProcess;
use App\Models\Payroll\SalaryType;
use App\PartyInfo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;

class SalaryprocessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employee_all = Employee::orderBy('full_name')->get();

        $date = Carbon::now();
        $monthYear = $request->month ?? $date->format('Y-m');
        $employee_id = $request->employee_id;

        $monthName = Carbon::parse($monthYear)->format('F');
        $month = Carbon::parse($monthYear)->format('m');
        $year = Carbon::parse($monthYear)->format('Y');

        $month_year = $year . '-' . Carbon::parse($monthYear)->format('m');

        $totalDaysInMonth = Carbon::parse($monthYear)->daysInMonth;
        $currentDayOfMonth = $date->format('Y-m') == $monthYear ? $date->day : $totalDaysInMonth;

        if (Auth::user()->hasPermission('basic_info') ||
            Auth::user()->hasPermission('employee_attendance') ||
            Auth::user()->hasPermission('payroll_process') ||
            Auth::user()->hasPermission('player') ||
            Auth::user()->hasPermission('salary_procedure')) {
            $employees = Employee::orderBy('first_name')->get();
        }else{
            $employees = Employee::where('status', 1)->where('id', Auth::user()->employee_id)->get();
        }

        if($employee_id){
            $employees = Employee::where('id', $employee_id)->get();
        }

        $salarys = [];

        foreach ($employees as $item) {
            $salary_month = $month;
            $salary_year = $year;
            $text = "N/A";

            $salary_process = SalaryProcess::where('employee_id', $item->id)->where('advance_amount', '>', 0)->get();
            $already_payment = SalaryProcess::where('employee_id', $item->id)->where('status',1)->where('month', $month)->where('year',$year)->get();

            if($salary_month && $salary_year) {
                $salary_date = new DateTime("$salary_year-$salary_month-01");

                if ($item) {
                    $last_visite = !empty($item->last_visite) ? new DateTime($item->last_visite) :
                                  (!empty($item->joining_date) ? new DateTime($item->joining_date) : null);
                    $v_type = !empty($item->last_visite) ? 'l' : 'j';

                    if ($last_visite) {
                        $interval = $salary_date->diff($last_visite);
                        $months_difference = ($interval->y * 12) + $interval->m;
                        $text = $months_difference;
                    }
                }
            }

            $basic_salary = 0;
            $check_attendance = EmployeeAttendance::check_attendance($item->id, $month, $year, $basic_salary);
            $overtime_amount = isset($check_attendance['overtime_amount']) ? str_replace(',', '', $check_attendance['overtime_amount']) : 0;
            $late_amount = isset($check_attendance['late_amount']) ? str_replace(',', '', $check_attendance['late_amount']) : 0;
            $total_absen_penalty = isset($check_attendance['total_absen_penalty']) ? $check_attendance['total_absen_penalty'] : 0;
            $basic_salary = isset($check_attendance['basic_salary']) ? $check_attendance['basic_salary'] : 0;

            // Calculate the prorated amount if the month is not complete
            $basic_salary_current_day = $basic_salary;
            if ($currentDayOfMonth < $totalDaysInMonth) {
                    $basic_salary_current_day = ($basic_salary / $totalDaysInMonth) * $currentDayOfMonth;
            }
            //    return([ $basic_salary,$currentDayOfMonth , $totalDaysInMonth]);
            $total_amount = ($overtime_amount - $late_amount - $total_absen_penalty) + $basic_salary_current_day;
            $salarys[] = [
                'code' => $item->code,
                'employee_id' => $item->id,
                'employee_name' => $item->full_name,
                'emp_id' => $item->emp_id,
                'total_late_time' => isset($check_attendance['total_late_time']) ? $check_attendance['total_late_time'] : 0,
                'total_overtime' => isset($check_attendance['total_overtime']) ? $check_attendance['total_overtime'] : 0,
                'total_working_hours' => isset($check_attendance['total_working_hours']) ? $check_attendance['total_working_hours'] : 0,
                'overtime_amount' => isset($overtime_amount) ? $overtime_amount : 0,
                'late_amount' => isset($late_amount) ? $late_amount : 0,
                'total_absen_penalty' => isset($total_absen_penalty) ? $total_absen_penalty : 0,
                'total_absen' => isset($check_attendance['total_absen']) ? $check_attendance['total_absen'] : 0,
                'basic_salary' => $basic_salary,
                'basic_salary_current_day' => $basic_salary_current_day,

                'month_number' => $text,
                'amount' => $total_amount,
                'month' => $monthName,
                'year' => $year,
                'advance' => $salary_process->sum('advance_amount'),
                'paid_salary' => $already_payment->sum('amount'),
                // 'minimum_hours_for_late' =>  isset($check_attendance['minimum_hours_for_late']) ? $check_attendance['minimum_hours_for_late'] : 0,
            ];
        }

        return view('backend.payroll.salary_process.index', compact('salarys', 'month_year','employees', 'employee_id','employee_all'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //Gate::authorize('app.mapping.index');
        list($year, $month) = explode('-', $request->month_year);
        $selectedEmpIds = $request->input('employee_id', []); // array of selected checkboxes
        $amounts = $request->input('amount', []); // keyed by emp_id
        $basics = $request->input('basic', []);
        $overtimes = $request->input('overtime', []);
        $lates = $request->input('late', []);
        $penalties = $request->input('absen_penalty', []);
        $currents = $request->input('basic_salary_current_day', []);
        $advances = $request->input('advance_amount', []);
        $totals = $request->input('totals', []);
        $reduce_advances = $request->input('reduce_advances', []);

        $check = SalaryProcess::where('month', $month)->where('year', $year)->where('status', 0)->first();

        if($check){
            $notification= array(
                'message'       => 'Salary Sheet Create successfully!',
                'alert-type'    => 'success'
            );

            return back()->with($notification);
        }

        foreach ($selectedEmpIds as $empId) {;
            SalaryProcess::create([
                'employee_id' => $empId,
                'basic' => str_replace(',', '', $basics[$empId] ?? 0),
                'overtime' => str_replace(',', '', $overtimes[$empId] ?? 0),
                'late' => str_replace(',', '', $lates[$empId] ?? 0),
                'absen_penalty' => str_replace(',', '', $penalties[$empId] ?? 0),
                'basic_salary_current_day' => str_replace(',', '', $currents[$empId] ?? 0),
                'advance_amount' => str_replace(',', '', $advances[$empId] ?? 0),
                'amount' => str_replace(',', '', $amounts[$empId] ?? 0),
                'month' => $month,
                'year' => $year,
                'total'=> str_replace(',', '', $totals[$empId] ?? 0),
                'status' => 0,
                'reduce_advance' => str_replace(',', '', $reduce_advances[$empId] ?? 0),
            ]);
        }

        $notification= array(
            'message'       => 'Salary Sheet Create successfully!',
            'alert-type'    => 'success'
        );

        return back()->with($notification);
    }

    public function authorizeList(Request $request){
        $monthYear = $request->month ?? date('Y-m');
        $employee_id = $request->employee_id;
        list($year, $month) = explode('-', $monthYear);
        $employees = Employee::where('status',1)->orderBy('full_name')->get();
        $salary_process = SalaryProcess::where('status', 0)
            ->when($month && $year, function($q) use ($month, $year){
                $q->where('month', $month)->where('year', $year);
            })
            ->when($employee_id, function($q) use($employee_id){
                $q->where('employee_id', $employee_id);
            })->get();

        return view('backend.payroll.salary_process.authorize_list', compact('salary_process', 'employees', 'monthYear'));
    }

    public function destroy($id){
        $salary_proces = SalaryProcess::find($id);
        if($salary_proces->status == 0){
            $salary_proces->delete();
            return back()->with(['alert-type' => 'success', 'message' => 'The items has been deleted successfully']);
        }

        return back()->with(['alert-type' => 'warning', 'message' => 'Unable to delete this item']);
    }

    public function approve($id){
        $salary_proces = SalaryProcess::find($id);
        $reduce_advance = $salary_proces->reduce_advance;

        if($salary_proces->status == 0){
            if($reduce_advance > 0){
                $advances = SalaryProcess::where('status',1)->where('employee_id', $salary_proces->employee_id)->where('advance_amount', '>', 0)->get();
                foreach ($advances as $process) {
                    if ($reduce_advance <= 0) {
                        break;
                    }

                    $currentAdvance = $process->advance_amount;

                    // Case 1: enough to reduce full row
                    if ( $reduce_advance >= $currentAdvance) {
                         $reduce_advance -= $currentAdvance;
                        $process->advance_amount = 0;
                    } else {
                        // Case 2: partially reduce
                        $process->advance_amount -=  $reduce_advance;
                         $reduce_advance = 0;
                    }

                    $process->save(); // Update DB
                }
            }
            $salary_proces->update([
                'status' => 1,
            ]);
        }
        return back()->with(['alert-type' => 'success', 'message' => 'The items has been updated successfully']);
    }

    public function procesAction(Request $request){
        $action = $request->action;
        $employee_ids = $request->employee_id;
        if($action == 'delete'){
            foreach($employee_ids as $id){
                $this->destroy($id);
            }
            return back()->with(['alert-type' => 'success', 'message' => 'The items has been deleted successfully']);
        }else{
            foreach($employee_ids as $id){
                $this->approve($id);
            }

            return back()->with(['alert-type' => 'success', 'message' => 'The items has been updated successfully']);
        }
    }

    public function ApproveList(Request $request){
        $monthYear = $request->month ?? date('Y-m');
        $employee_id = $request->employee_id;
        list($year, $month) = explode('-', $monthYear);
        $employees = Employee::orderBy('full_name')->get();
        $salary_process = SalaryProcess::where('status', 1)
            ->when($month && $year, function($q) use ($month, $year){
                $q->where('month', $month)->where('year', $year);
            })
            ->when($employee_id, function($q) use($employee_id){
                $q->where('employee_id', $employee_id);
            })->get();

        return view('backend.payroll.salary_process.approve_list', compact('salary_process', 'employees', 'monthYear'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


        $month_year = SalaryProcess::where('employee_id',$id)->where('status', 1)->first();
        $monthNumber = date('F', strtotime($month_year->month));
        $installmentMonth = $month_year->year . '-' . $monthNumber;
        $installments = Installment::whereHas('loan', function ($query) use ($id) {
            $query->where('emp_id', $id)->where('status', 'active');
        })
        ->where('installment_month', $installmentMonth)
        ->where('status', 'pending')
        ->get();

        // return $installments;
        $components = SalaryComponent::orderBy('id', 'desc')->get();
        $deductions = DeductionEntry::where('employee_id', $id)->where('due','!=', 0)->orderBy('id', 'desc')->get();
        // dd($deductions);
        $component_types = ComponentType::orderBy('id', 'desc')->get();
        $employee = Employee::find($id);
        // dd($wages_type);
        $salaryStructure = SalaryStructure::all()->toArray();

        return Response()->json([
            'page' => view('backend.payroll.salary_process.edit-modal', ['components' => $components,
                                                                     'component_types' => $component_types,
                                                                     'employee' => $employee,
                                                                     'installments' => $installments,
                                                                     'deductions' => $deductions])->render(),

        ]);
        // return view('backend.payroll.salary_process.edit', compact('components', 'component_types', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $process =  SalaryProcess::where('employee_id',$id)->where('status',1)->first();
        // dd($process);
        $grade_id = $process->grade_id;
        $month = $process->month;
        $year = $process->year;
        if (isset($request->records['head'])) {
            SalaryProcess::where('employee_id',$id)->where('status',1)->delete();

        foreach ($request->records['head'] as $key => $value) {


            SalaryProcess::create([
                    'employee_id' => $id,
                    'grade_id' => $grade_id,
                    'salary_component_id' => $request->records['head'][$key],
                    'month' => $month,
                    'year' => $year,
                    'amount' => $request->records['amount'][$key],
                ]);
        }
        $notification= array(
            'message'       => 'Update successfully!',
            'alert-type'    => 'success'
        );
    }else{
        $notification= array(
            'message'       => 'Please Select Atleast One Item!',
            'alert-type'    => 'warning'
        );
    }
        if (isset($request->deduct['head'])) {
            DeductionProcess::where('employee_id',$id)->where('status',1)->delete();

            foreach ($request->deduct['head'] as $key => $value) {

                DeductionProcess::create([
                        'employee_id' => $id,
                        'deduction_component_id' => $request->deduct['head'][$key],
                        'month' => $month,
                        'year' => $year,
                        'amount' => $request->deduct['amount'][$key],
                    ]);
            }
        }


        return redirect('salary-process')->with($notification);
    }

    public function crearteSalary(Request $request)
    {
        // dd($request);
        //Gate::authorize('app.mapping.index');

        $date = Carbon::now();
        $monthName = $request->month;
        $year = $request->year;
        $month_year = $year.'-'.$monthName;

        $check=SalaryProcess::where('month', $monthName)->where('year', $year)->get();

        $monthNumber = date_parse($request->month);
        $month = $monthNumber['month'];

        // dd($month);
        // $salary_month=$year.'-'.$monthName.'-'.'01';

        $employees = Employee::get();

        //return $employees;
        $check_attendance1 = [];


        if(count($check) == 0) {

            $doubleCheck = SalaryProcess::where('status', 1)->get();

            if(count($doubleCheck) == 0){
                $employees = Employee::get();

                //return $employees;
                 foreach ($employees as $item){


                    $installments = Installment::whereHas('loan', function ($query) use ($item) {
                        $query->where('emp_id', $item->id)->where('status', 'active');
                    })
                    ->where('installment_month', $month_year)
                    ->where('status', 'pending')
                    ->get();
                    if (isset($installments)) {
                        InstallmentProcess::where('employee_id',$item->id)->where('status',1)->delete();

                        foreach ($installments as $key => $installment) {

                            InstallmentProcess::create([
                                'employee_id' => $item->id,
                                'installment_id' => $installment->id,
                                'month' => $monthName,
                                'year' => $year,
                                'amount' => $installment->installment_amount,
                            ]);
                        }
                        }


                    $date = GradeWiseSalaryComponentHistory::where('grade_id',$item->grade)->whereMonth('date','<=',$month)->whereYear('date',$year)->orderBy('id','DESC')->first();
                    if(!$date)
                    {
                        $date = GradeWiseSalaryComponentHistory::where('grade_id',$item->grade)->whereYear('date','<',$year)->orderBy('id','DESC')->first();
                    }
                    // dd($date);
                   if($date)
                   {


                    $basic_slary =  $item->basic_salary ?? 0;
                    $check_attendance = EmployeeAttendance::check_attendance($item->id, $month, $year, $basic_slary);
                    if($check_attendance){
                        if ($check_attendance['overtime_amount'] !== null && $check_attendance['late_amount'] !== null) {
                            $total_amount = $check_attendance['overtime_amount'] - ($check_attendance['late_amount'] + ($check_attendance['total_absen_penalty'] ?? 0));
                        } else {
                            $totla_amount = 0;
                        }
                        $check_attendance['overtime_amount'] = str_replace(',', '', $check_attendance['overtime_amount']);
                        $check_attendance['late_amount'] = str_replace(',', '', $check_attendance['late_amount']);

                        OvertimeLatesSalaryProcess::create([
                            'employee_id' => $item->id,
                            'grade_id' => $item->grade,
                            'emp_policy_id' => $check_attendance['emp_policy_id'],
                            'total_late_time' => $check_attendance['total_late_time'],
                            'total_overtime' => $check_attendance['total_overtime'],
                            'total_working_hours' => $check_attendance['total_working_hours'],
                            'overtime_amount' => $check_attendance['overtime_amount'],
                            'late_amount' => $check_attendance['late_amount'],
                            'total_absen_penalty' => $check_attendance['total_absen_penalty'],
                            'total_absen' => $check_attendance['total_absen'],
                            'amount' => $total_amount,
                            'month' => $monthName,
                            'year' => $year,
                        ]);

                    }


                    $gradeWises = GradeWiseSalaryComponentHistory::where('grade_id',$item->grade)->where('date',$date->date)->get();
                    foreach($gradeWises as $salary){
                        SalaryProcess::create([
                            'employee_id' => $item->id,
                            'grade_id' => 0,
                            'salary_component_id' => 1,
                            'amount' => $item->basic_salary,
                            'month' =>$monthName,
                            'year' => $year,
                        ]);
                    }

                    $date = ExtraSalaryComponentHistory::where('employee_id',$item->id)->whereMonth('date','<=',$month)->whereYear('date',$year)->orderBy('id','DESC')->first();
                    if(!$date)
                    {
                        $date = ExtraSalaryComponentHistory::where('employee_id',$item->id)->whereYear('date','<',$year)->orderBy('id','DESC')->first();
                    }
                    // $date = ExtraSalaryComponentHistory::where('employee_id',$item->id)->whereMonth('date','<=',$month)->whereYear('date','>=',$year)->whereMonth('date','<=',$month)->whereYear('date','<=',$year)->orderBy('date', 'desc')->first();

                    if ($date != null) {

                        $extra = ExtraSalaryComponentHistory::where('employee_id',$item->id)->where('date',$date->date)->get();
                        // dd($extra);
                        foreach($extra as $salary){
                            SalaryProcess::create([
                                'employee_id' => $item->id,
                                'grade_id' => $item->grade,
                                'salary_component_id' => $salary->salary_component_id,
                                'amount' => $salary->value,
                                'month' =>$monthName,
                                'year' => $year,
                            ]);
                        }
                    }
                   }


                }
                $notification= array(
                    'message'       => 'Create succesfully',
                    'alert-type'    => 'success'
                );
            }else{
                $notification= array(
                    'message'       => 'Last month salary processing!',
                    'alert-type'    => 'warning'
                );
            }

        }else{
            $notification= array(
                'message'       => 'This months salary sheet already created!',
                'alert-type'    => 'error'
            );
        };


        return redirect('salary-process')->with($notification);

    }

    public function confirm(Request $request) {



        $check=SalaryProcess::where('status', 1)->get();

        if(count($check) != 0) {

            if($request->has('file')){
                $info =  SalaryProcess::where('status', 1)->first();
                $name= $request->file->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext= $request->file->getClientOriginalExtension();
                $file_name= 'salary_confirm'.time().'.'.$ext;

                $request->file->storeAs( 'public/upload/approval_document', $file_name);

                SalaryApprovalDocument::create([
                    'month' => $info->month,
                    'year' => $info->year,
                    'document' => $file_name,
                    'extension' => $ext
                ]);

            }else{
                $notification= array(
                    'message'       => 'Please upload your approval document!',
                    'alert-type'    => 'warning'
                );
                return redirect('pay-salary')->with($notification);
            }

            $employees = SalaryProcess::where('status', 1)->distinct()->get('employee_id');

              // *****************************************

            // dd($employees);
            foreach ($employees as $item){

               $total = SalaryProcess::where('employee_id', $item->employee_id)->where('status', 1)->sum('amount');
               $deduct = DeductionProcess::where('employee_id', $item->employee_id)->where('status', 1)->sum('amount');
            //    $installment_amount = InstallmentProcess::where('employee_id', $item->employee_id)->where('status', 1)->sum('amount');
               $overtime_late_salary_process = OvertimeLatesSalaryProcess::where('employee_id', $item->employee_id)->where('status', 1)->sum('amount');

                $info =  SalaryProcess::where('employee_id', $item->employee_id)->where('status', 1)->first();


                $deduction = DeductionProcess::where('employee_id', $item->employee_id)->where('status', 1)->get();
                foreach ($deduction as $value) {
                    $data = DeductionEntry::find($value->deduction_component_id);
                    $data->due = $data->due - $value->amount;
                    $data->save();
                }

                DeductionProcess::where('employee_id', $item->employee_id)->where('status', 1)->update([
                    'status' => 0,
                ]);

                $installment_process = InstallmentProcess::where('employee_id', $item->employee_id)->where('status', 1)->get();
                $total_principal=0;
                $total_interest=0;

                foreach ($installment_process as $installment_proces) {
                    $data = Installment::find($installment_proces->installment_id);
                    $data->paid_amount = $installment_proces->amount;
                    $data->status = 'complete';
                    $data->save();

                    $paid = $data->principal_amount;

                    if ($data->loan) {
                        $data->loan->update([
                            'remaining_principal_amount' => $data->loan->remaining_principal_amount - $paid,
                            'total_interest' => $data->loan->total_interest + $data->interest_amount,
                        ]);
                    }

                    $total_principal=$total_principal+$data->principal_amount;
                    $total_interest=$total_interest+$data->interest_amount;

                    $payment = new LoanPayment();
                    $payment->payment_no = LoanPayment::generateLoanNumber();
                    $payment->loan_id = $data->loan_id;
                    $payment->installment_id = $data->id;
                    $payment->payment_amount = $data->paid_amount;
                    $payment->type ='paid';
                    $payment->payment_type = 'auto';
                    $payment->payment_date = date('Y-m-d');
                    $payment->save();

                }

                InstallmentProcess::where('employee_id', $item->employee_id)->where('status', 1)->update([
                    'status' => 0,
                ]);
                OvertimeLatesSalaryProcess::where('employee_id', $item->employee_id)->where('status', 1)->update([
                    'status' => 0,
                ]);

                    // dd($info);
                $sp=PaySalary::create([
                    'employee_id' => $item->employee_id,
                    'grade_id' => $info->grade_id,
                    'month' =>$info->month,
                    'year' => $info->year,
                    'payable' => $total-$deduct   + $overtime_late_salary_process, // - $installment_amount
                    'paid' => 0,
                    'due' => $total - $deduct  + $overtime_late_salary_process, // - $installment_amount
                ]);
                SalaryProcess::where('employee_id', $item->employee_id)->where('status', 1)->update([
                    'status' => 0,
                ]);
                    // ***************************Journal records


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

                $journal_main= AccountHead::find(5); // Payable Account

                $party=PartyInfo::where('emp_id',$item->employee_id)->first();


                $journal= new Journal();
                $journal->project_id        = 1;
                $journal->journal_no        = $journal_no;
                $journal->date              = Carbon::now()->toDateString();
                $journal->invoice_no        = 'N/A';
                $journal->salary_proces_id        = $sp->id;

                $journal->source            = 'Application Fee';
                $journal->pay_mode            = "Cash";
                $journal->cost_center_id    = 0;
                $journal->profit_center_id    = 1;
                $journal->party_info_id     = $party->id;
                $journal->account_head_id   = $journal_main->id;
                $journal->amount            = $total;
                $journal->tax_rate          = 0;
                $journal->vat_amount        = 0;
                $journal->voucher_type      = "DR";
                $journal->total_amount      = $total;
                $journal->narration         = "Salary Process";
                $journal->authorized      = 1;
                $journal->approved      = 1;

                $journal->approved_by         = Auth::id();
                $journal->created_by         = Auth::id();
                $journal->authorized_by         = Auth::id();
                $journal->save();



                    $journal_main= AccountHead::find(5); // Payable Account
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
                    $jl_record->amount              =  $total-$deduct; //  - $installment_amount
                    $jl_record->total_amount              = $total-$deduct ; // - $installment_amount
                    $jl_record->is_main_head              = 1;
                    $jl_record->transaction_type    = 'CR';
                    $jl_record->account_type_id        = 0;
                    $jl_record->journal_date        = Carbon::now()->toDateString();
                    $jl_record->save();

                    $journal_main= AccountHead::find(184); // Salary Account
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
                    $jl_record->amount              =  $total;
                    $jl_record->total_amount              = $total;
                    $jl_record->is_main_head              = 1;
                    $jl_record->transaction_type    = 'DR';
                    $jl_record->account_type_id        = 0;
                    $jl_record->journal_date        = Carbon::now()->toDateString();
                    $jl_record->save();

                    if($deduct>0)
                    {
                        $journal_main= AccountHead::find(183); // Receivable Account
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
                        $jl_record->amount              =  $deduct;
                        $jl_record->total_amount              = $deduct;
                        $jl_record->is_main_head              = 1;
                        $jl_record->transaction_type    = 'CR';
                        $jl_record->account_type_id        = 0;
                        $jl_record->journal_date        = Carbon::now()->toDateString();
                        $jl_record->save();
                    }

                    // if($total_principal>0)
                    // {
                    //     $journal_main= AccountHead::find(182); // Receivable Account
                    //     $jl_record= new JournalRecord();
                    //     $jl_record->journal_id     = $journal->id;
                    //     $jl_record->project_details_id  = 0;
                    //     $jl_record->cost_center_id    = 0;
                    //     $jl_record->profit_center_id    = 1;
                    //     $jl_record->party_info_id       = $party->id;
                    //     $jl_record->journal_no          = $journal_no;
                    //     $jl_record->account_head_id     = $journal_main->id;
                    //     $jl_record->master_account_id   = $journal_main->master_account_id;
                    //     $jl_record->account_head        = $journal_main->fld_ac_head;
                    //     $jl_record->amount              =  $total_principal;
                    //     $jl_record->total_amount              = $total_principal;
                    //     $jl_record->is_main_head              = 1;
                    //     $jl_record->transaction_type    = 'CR';
                    //     $jl_record->account_type_id        = 0;
                    //     $jl_record->journal_date        = Carbon::now()->toDateString();
                    //     $jl_record->save();
                    // }

                    // if($total_interest>0)
                    // {
                    //     $journal_main= AccountHead::find(184); // Receivable Account
                    //     $jl_record= new JournalRecord();
                    //     $jl_record->journal_id     = $journal->id;
                    //     $jl_record->project_details_id  = 0;
                    //     $jl_record->cost_center_id    = 0;
                    //     $jl_record->profit_center_id    = 1;
                    //     $jl_record->party_info_id       = $party->id;
                    //     $jl_record->journal_no          = $journal_no;
                    //     $jl_record->account_head_id     = $journal_main->id;
                    //     $jl_record->master_account_id   = $journal_main->master_account_id;
                    //     $jl_record->account_head        = $journal_main->fld_ac_head;
                    //     $jl_record->amount              =  $total_interest;
                    //     $jl_record->total_amount              = $total_interest;
                    //     $jl_record->is_main_head              = 1;
                    //     $jl_record->transaction_type    = 'CR';
                    //     $jl_record->account_type_id        = 0;
                    //     $jl_record->journal_date        = Carbon::now()->toDateString();
                    //     $jl_record->save();
                    // }

            }
            $notification= array(
                'message'       => 'Salary Sheet Create successfully!',
                'alert-type'    => 'success'
            );

        }else{
            $notification= array(
                'message'       => 'There have nothing to confirm!',
                'alert-type'    => 'warning'
            );
        };


        return redirect('pay-salary')->with($notification);
    }

    // employee salary info show table

    public function employee_salary_show(Request $request)
    {
        $date = Carbon::now();
        $monthYear = $request->month ?? $date->format('Y-m');

        $monthName = Carbon::parse($monthYear)->format('F');
        $month = Carbon::parse($monthYear)->format('m');
        $year = Carbon::parse($monthYear)->format('Y');

        $month_year = $year . '-' . Carbon::parse($monthYear)->format('m');

        $totalDaysInMonth = Carbon::parse($monthYear)->daysInMonth;
        $currentDayOfMonth = $date->format('Y-m') == $monthYear ? $date->day : $totalDaysInMonth;

        if (Auth::user()->hasPermission('Salary') ||
            Auth::user()->hasPermission('Attendance') ) {
            $employees = Employee::where('job_status', 1)->orderBy('first_name')->get();
        }else{
            $employees = Employee::where('job_status', 1)->where('id', Auth::user()->employee_id)->get();
        }

        $salarys = [];

        foreach ($employees as $item) {
            $salary_month = $month;
            $salary_year = $year;
            $text = "N/A";

            if ($salary_month && $salary_year) {
                $salary_date = new DateTime("$salary_year-$salary_month-01");

                if ($item) {
                    $last_visite = !empty($item->last_visite) ? new DateTime($item->last_visite) :
                                  (!empty($item->joining_date) ? new DateTime($item->joining_date) : null);
                    $v_type = !empty($item->last_visite) ? 'l' : 'j';

                    if ($last_visite) {
                        $interval = $salary_date->diff($last_visite);
                        $months_difference = ($interval->y * 12) + $interval->m;
                        $text = $months_difference;
                    }
                }
            }

            $basic_salary = 0;
            $check_attendance = EmployeeAttendance::check_attendance($item->id, $month, $year, $basic_salary);
            $overtime_amount = isset($check_attendance['overtime_amount']) ? str_replace(',', '', $check_attendance['overtime_amount']) : 0;
            $late_amount = isset($check_attendance['late_amount']) ? str_replace(',', '', $check_attendance['late_amount']) : 0;
            $total_absen_penalty = isset($check_attendance['total_absen_penalty']) ? $check_attendance['total_absen_penalty'] : 0;
            $basic_salary = isset($check_attendance['basic_salary']) ? $check_attendance['basic_salary'] : 0;
            // Calculate the prorated amount if the month is not complete
            $basic_salary_current_day = $basic_salary;
            if ($currentDayOfMonth < $totalDaysInMonth) {
                    $basic_salary_current_day = ($basic_salary / $totalDaysInMonth) * $currentDayOfMonth;
            }
            //    return([ $basic_salary,$currentDayOfMonth , $totalDaysInMonth]);
            $total_amount = ($overtime_amount - $late_amount - $total_absen_penalty) + $basic_salary_current_day;
            $salarys[] = [
                'code' => $item->code,
                'employee_id' => $item->id,
                'employee_name' => $item->full_name,
                'emp_id' => $item->emp_id,
                'total_late_time' => isset($check_attendance['total_late_time']) ? $check_attendance['total_late_time'] : 0,
                'total_overtime' => isset($check_attendance['total_overtime']) ? $check_attendance['total_overtime'] : 0,
                'total_working_hours' => isset($check_attendance['total_working_hours']) ? $check_attendance['total_working_hours'] : 0,
                'overtime_amount' => isset($overtime_amount) ? $overtime_amount : 0,
                'late_amount' => isset($late_amount) ? $late_amount : 0,
                'total_absen_penalty' => isset($total_absen_penalty) ? $total_absen_penalty : 0,
                'total_absen' => isset($check_attendance['total_absen']) ? $check_attendance['total_absen'] : 0,
                'basic_salary' => $basic_salary,
                'basic_salary_current_day' => $basic_salary_current_day,

                'month_number' => $text,
                'amount' => $total_amount,
                'month' => $monthName,
                'year' => $year,
                // 'minimum_hours_for_late' =>  isset($check_attendance['minimum_hours_for_late']) ? $check_attendance['minimum_hours_for_late'] : 0,
            ];
        }
    //    return $salarys;
        return view('backend.payroll.salary_process.show-salary-sheet', compact('salarys', 'month_year'));
    }
    public function pay_salary_sheet(Request $request)
    {
        $date = Carbon::now();
        $monthYear = $request->month ?? $date->format('Y-m');

        $monthName = Carbon::parse($monthYear)->format('F');
        $month = Carbon::parse($monthYear)->format('m');
        $year = Carbon::parse($monthYear)->format('Y');

        $month_year = $year . '-' . Carbon::parse($monthYear)->format('m');

        $totalDaysInMonth = Carbon::parse($monthYear)->daysInMonth;
        $currentDayOfMonth = $date->format('Y-m') == $monthYear ? $date->day : $totalDaysInMonth;

        if ($request->type == 'all') {
            $employees = Employee::get();
        }else {
            $employees = Employee::where('emp_id', $request->id)->get();
        }

        $salarys = [];

        foreach ($employees as $item) {
            $salary_month = $month;
            $salary_year = $year;
            $text = "N/A";

            if ($salary_month && $salary_year) {
                $salary_date = new DateTime("$salary_year-$salary_month-01");

                if ($item) {
                    $last_visite = !empty($item->last_visite) ? new DateTime($item->last_visite) :
                                  (!empty($item->joining_date) ? new DateTime($item->joining_date) : null);
                    $v_type = !empty($item->last_visite) ? 'l' : 'j';

                    if ($last_visite) {
                        $interval = $salary_date->diff($last_visite);
                        $months_difference = ($interval->y * 12) + $interval->m;
                        $text = "$months_difference months After " . ($v_type == 'j' ? "Joining Date." : "Last Visit Date.");
                    }
                }
            }

            $basic_salary =  0;
            $check_attendance = EmployeeAttendance::check_attendance($item->id, $month, $year, $basic_salary);
            $overtime_amount = isset($check_attendance['overtime_amount']) ? str_replace(',', '', $check_attendance['overtime_amount']) : 0;
            $late_amount = isset($check_attendance['late_amount']) ? str_replace(',', '', $check_attendance['late_amount']) : 0;
            $total_absen_penalty = isset($check_attendance['total_absen_penalty']) ? $check_attendance['total_absen_penalty'] : 0;
            $basic_salary = isset($check_attendance['basic_salary']) ? $check_attendance['basic_salary'] : 0;


            // Calculate the prorated amount if the month is not complete
            $basic_salary_current_day = $basic_salary;
            if ($currentDayOfMonth < $totalDaysInMonth) {
                 $basic_salary_current_day = ($basic_salary / $totalDaysInMonth) * $currentDayOfMonth;
            }
        //    return([ $basic_salary,$currentDayOfMonth , $totalDaysInMonth]);
           $total_amount = ($overtime_amount - $late_amount - $total_absen_penalty) + $basic_salary_current_day;

            $salarys[] = [
                'code' => $item->code,
                'employee_id' => $item->id,
                'employee_name' => $item->full_name,
                'emp_id' => $item->emp_id,
                'total_late_time' => isset($check_attendance['total_late_time']) ? $check_attendance['total_late_time'] : 0,
                'total_overtime' => isset($check_attendance['total_overtime']) ? $check_attendance['total_overtime'] : 0,
                'total_working_hours' => isset($check_attendance['total_working_hours']) ? $check_attendance['total_working_hours'] : 0,
                'overtime_amount' => isset($overtime_amount) ? $overtime_amount : 0,
                'late_amount' => isset($late_amount) ? $late_amount : 0,
                'total_absen_penalty' => isset($total_absen_penalty) ? $total_absen_penalty : 0,
                'total_absen' => isset($check_attendance['total_absen']) ? $check_attendance['total_absen'] : 0,
                'basic_salary' => $basic_salary,
                'basic_salary_current_day' => $basic_salary_current_day,

                'month_number' => $text,
                'amount' => $total_amount,
                'month' => $monthName,
                'year' => $year,
            ];
        }
        return Response()->json([
            'page' => view('backend.payroll.pdf.payslip-show', [
                'datas' => $salarys,
                'currentDayOfMonth' =>$currentDayOfMonth,
            ])->render(),

        ]);
    }

    public function downloadPayslip(Request $request){
        $date = Carbon::now();
        $all = $request->all;
        $month = $request->month;
        $year = $request->year;
        $id = $request->id;

        $employee = Employee::where('id', $id)->first();

        if (!$employee) {
            return abort(404, 'Employee not found');
        }

        $salarys = [];

        if (!$employee) {
            return []; // or appropriate fallback
        }

        // Get the current year
        $currentYear = $year ?? $date->format('Y');

        $loopMonths = [];

        if ($all) {
            // Generate all 12 months of current year
            for ($i = 1; $i <= 12; $i++) {
                $loopMonths[] = Carbon::createFromDate($currentYear, $i, 1)->format('Y-m');
            }
        } elseif ($month) {
            $loopMonths[] = Carbon::createFromDate($currentYear, $month, 1)->format('Y-m');
        }

        foreach ($loopMonths as $monthYear) {
            $monthName = Carbon::parse($monthYear)->format('F');
            $monthNum = Carbon::parse($monthYear)->format('m');
            $year = Carbon::parse($monthYear)->format('Y');

            $totalDaysInMonth = Carbon::parse($monthYear)->daysInMonth;
            $currentDayOfMonth = $date->format('Y-m') == $monthYear ? $date->day : $totalDaysInMonth;

            $salary_date = new DateTime("$year-$monthNum-01");
            $text = "N/A";

            // Determine which date to compare
            $last_visite = !empty($employee->last_visite) ? new DateTime($employee->last_visite) :
                        (!empty($employee->joining_date) ? new DateTime($employee->joining_date) : null);

            $v_type = !empty($employee->last_visite) ? 'l' : 'j';

            if ($last_visite) {
                $interval = $salary_date->diff($last_visite);
                $months_difference = ($interval->y * 12) + $interval->m;
                $text = "$months_difference months After " . ($v_type == 'j' ? "Joining Date." : "Last Visit Date.");
            }

            // Get attendance
            $basic_salary = 0;
            $check_attendance = EmployeeAttendance::check_attendance($employee->id, $monthNum, $year, $basic_salary);

            $overtime_amount = isset($check_attendance['overtime_amount']) ? str_replace(',', '', $check_attendance['overtime_amount']) : 0;
            $late_amount = isset($check_attendance['late_amount']) ? str_replace(',', '', $check_attendance['late_amount']) : 0;
            $total_absen_penalty = $check_attendance['total_absen_penalty'] ?? 0;
            $basic_salary = $check_attendance['basic_salary'] ?? 0;

            // Prorate
            $basic_salary_current_day = $basic_salary;
            if ($currentDayOfMonth < $totalDaysInMonth) {
                $basic_salary_current_day = ($basic_salary / $totalDaysInMonth) * $currentDayOfMonth;
            }

            $total_amount = ($overtime_amount - $late_amount - $total_absen_penalty) + $basic_salary_current_day;

            $salarys[$monthYear] = [
                'code' => $employee->code,
                'employee_id' => $employee->id,
                'employee_name' => $employee->full_name,
                'emp_id' => $employee->emp_id,
                'total_late_time' => $check_attendance['total_late_time'] ?? 0,
                'total_overtime' => $check_attendance['total_overtime'] ?? 0,
                'total_working_hours' => $check_attendance['total_working_hours'] ?? 0,
                'overtime_amount' => $overtime_amount,
                'late_amount' => $late_amount,
                'total_absen_penalty' => $total_absen_penalty,
                'total_absen' => $check_attendance['total_absen'] ?? 0,
                'basic_salary' => $basic_salary,
                'basic_salary_current_day' => $basic_salary_current_day,
                'amount' => $total_amount,
                'month' => $monthName,
                'year' => $year,
                'currentDayOfMonth' => $currentDayOfMonth,
            ];
        }

        $name = $employee->full_name;

        $pdf = Pdf::loadView('backend.payroll.pdf.download-payslip', compact('salarys','name', 'year','text'));

        return response()->make($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="payslip_' . $employee->full_name . '.pdf"',
        ]);
    }
}

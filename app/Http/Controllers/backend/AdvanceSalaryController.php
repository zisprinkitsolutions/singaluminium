<?php

namespace App\Http\Controllers\backend;

use App\AdvanceSalary;
use App\DebitCreditVoucher;
use App\Models\Payroll\Employee;
use App\Http\Controllers\Controller;
use App\Journal;
use App\JournalRecord;
use App\Models\AccountHead;
use App\Models\Payroll\DeductionEntry;
use App\PartyInfo;
use App\Payment;
use App\PayMode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvanceSalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function dateFormat($date)
    {
        $old_date = explode('/', $date);

        $new_data = $old_date[0].'-'.$old_date[1].'-'.$old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }
    public function index()
    {
        $advance_salaries = AdvanceSalary::orderBy('id', 'desc')->paginate(20);
        $employees = Employee::orderBy('id', 'desc')->where('division', 6)->get();
        $pay_modes = PayMode::whereNotIn('id',[2,4])->get();
        return view('backend.advance-salary.index', compact('advance_salaries', 'employees', 'pay_modes'));
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
    public function store(Request $request) {
        $request->validate([
            'employee_id' => 'required',
            'date' => 'required',
            'pay_mode' => 'required',
            'amount' => 'required',
            'description' => 'required',
        ]);
        if ($request->file('file')) {
            $name= $request->file('file')->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext= $request->file('file')->getClientOriginalExtension();
            $file_name= $request->description.time().'.'.$ext;
            $request->file('file')->storeAs( 'public/upload/advance-salary-document', $file_name);
            $request->file('file')->storeAs( 'public/upload/deduction-document', $file_name);
        }else{
            $file_name = '';
            $ext = '';
        }
        $new_date = $this->dateFormat($request->date);
        $advance_salary = new AdvanceSalary;
        $advance_salary->employee_id = $request->employee_id;
        $advance_salary->description = $request->description;
        $advance_salary->paymode = $request->pay_mode;
        $advance_salary->amount = $request->amount;
        $advance_salary->due = $request->amount;
        $advance_salary->extension = $ext;
        $advance_salary->date = $new_date;
        $advance_salary->document = $file_name;
        $advance_salary->save();
        // deduction entry
        DeductionEntry::create([
            'employee_id' => $request->employee_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'due' => $request->amount,
            'date' => $new_date,
            'deduction_type' => 1,
            'document' => $file_name,
        ]);
        // *****************************************
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

        if($request->pay_mode == 'Cash'){
            $cr_acd= AccountHead::find(1); // Cash Account
        }else{
            $cr_acd= AccountHead::find(2); // Bank Account
        }

        $party=PartyInfo::where('emp_id',$request->employee_id)->first();

        $journal= new Journal();
        $journal->project_id        = 1;
        $journal->journal_no        = $journal_no;
        $journal->date              = Carbon::now()->toDateString();
        $journal->invoice_no        = 'N/A';
        $journal->source            = 'Advance Salary';
        $journal->pay_mode            = $request->pay_mode;
        $journal->cost_center_id    = 0;
        $journal->profit_center_id    = 1;
        $journal->party_info_id     = $party->id;
        $journal->account_head_id   = $cr_acd->id;
        $journal->amount            = $request->amount;
        $journal->tax_rate          = 0;
        $journal->vat_amount        = 0;
        $journal->voucher_type      = "DR";
        $journal->total_amount      = $request->amount;
        $journal->narration         = "Advance Salary for-". $request->description;
        $journal->authorized      = 1;
        $journal->approved      = 1;

        $journal->approved_by         = Auth::id();
        $journal->created_by         = Auth::id();
        $journal->authorized_by         = Auth::id();
        $journal->save();

        $jl_record= new JournalRecord();
        $jl_record->journal_id     = $journal->id;
        $jl_record->project_details_id  = 0;
        $jl_record->cost_center_id      = 0;
        $jl_record->profit_center_id    = 1;
        $jl_record->party_info_id       = $party->id;
        $jl_record->journal_no          = $journal_no;
        $jl_record->account_head_id     = $cr_acd->id;
        $jl_record->master_account_id   = $cr_acd->master_account_id;
        $jl_record->account_head        = $cr_acd->fld_ac_head;
        $jl_record->amount              = $request->amount;
        $jl_record->total_amount        = $request->amount;
        $jl_record->is_main_head        = 1;
        $jl_record->transaction_type    = 'CR';
        $jl_record->account_type_id     = 0;
        $jl_record->journal_date        = Carbon::now()->toDateString();
        $jl_record->save();

        $advance_paid_account= AccountHead::find(3); // Receiable Account

        $jl_record= new JournalRecord();
        $jl_record->journal_id     = $journal->id;
        $jl_record->project_details_id  = 0;
        $jl_record->cost_center_id      = 0;
        $jl_record->profit_center_id    = 1;
        $jl_record->party_info_id       = $party->id;
        $jl_record->journal_no          = $journal_no;
        $jl_record->account_head_id     = $advance_paid_account->id;
        $jl_record->master_account_id   = $advance_paid_account->master_account_id;
        $jl_record->account_head        = $advance_paid_account->fld_ac_head;
        $jl_record->amount              = $request->amount;
        $jl_record->total_amount        = $request->amount;
        $jl_record->is_main_head        = 1;
        $jl_record->transaction_type    = 'DR';
        $jl_record->account_type_id     = 0;
        $jl_record->journal_date        = Carbon::now()->toDateString();
        $jl_record->save();

        $dr_cr_voucher= new DebitCreditVoucher();
        $dr_cr_voucher->journal_id      = $journal->id;
        $dr_cr_voucher->project_id      = $journal->project_id;
        $dr_cr_voucher->cost_center_id  = 0;
        $dr_cr_voucher->party_info_id   = $journal->party_info_id;
        $dr_cr_voucher->account_head_id = 67;
        $dr_cr_voucher->pay_mode        = "Cash";
        $dr_cr_voucher->amount          = $journal->total_amount;
        $dr_cr_voucher->narration       = $journal->narration;
        $dr_cr_voucher->type            = "DR";
        $dr_cr_voucher->date            = $journal->date;
        $dr_cr_voucher->save();

                //////////////////////////////////////////
        $notification= array(
            'message'       => 'Added successfully!',
            'alert-type'    => 'success'
        );
        return back()->with($notification);
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

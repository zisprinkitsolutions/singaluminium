<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\JobProject;
use App\PartyInfo;
use App\VatRate;
use App\JobProjectInvoice;
use App\JobProjectTask;
use App\JobProjectTemInvoice;
use App\Journal;
use App\JournalRecord;
use App\Models\AccountHead;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class JobProjectInvoiceController extends Controller
{

    private function journal_no()
    {
        $sub_invoice = Carbon::now()->format('Ymd');
        // return $sub_invoice;
        $latest_journal_no = Journal::withTrashed()->whereDate('created_at', Carbon::today())->where('journal_no', 'LIKE', "%{$sub_invoice}%")->latest('id')->first();
        // return $latest_journal_no;
        if ($latest_journal_no) {
            $journal_no = substr($latest_journal_no->journal_no, 0, -1);
            $journal_code = $journal_no + 1;
            $journal_no = $journal_code . "J";
        } else {
            $journal_no = Carbon::now()->format('Ymd') . '001' . "J";
        }

        return $journal_no;
    }

    public function index()
    {
        $tem_invoices = JobProjectTemInvoice::with('project', 'party')->whereNull('authorize_by')->latest()->paginate(20);

        return view('backend.job-project-invoice.index', compact('tem_invoices'));
    }

    public function checkInvoiceNo(Request $request)
    {
        $sub_invoice = Carbon::now()->format('Ymd');
        $let_invoice_exp = JobProjectTemInvoice::whereDate('created_at', Carbon::today())->where('invoice_no', 'LIKE', "%{$sub_invoice}%")->latest('id')->first();
        if ($let_invoice_exp) {
            $tem_invoice_no = preg_replace('/^INV-/', '', $let_invoice_exp->invoice_no);
            $invoice_no = $tem_invoice_no + 1;
            $invoice_no = 'INV-'.$invoice_no ;
        }else{
            $invoice_no = 'INV-'.Carbon::now()->format('Ymd') .'001';
        }
        return $invoice_no;
    }

    public function projectInvoiceCreate($id)
    {
        $customers = Partyinfo::all();
        $vats = VatRate::all();
        $job_project = JobProject::with(['tasks' => function ($q) {
            $q->where('is_invoice', 0);
        }])->find($id);

        if ($job_project->is_invoice == 1 || $job_project->due_amount == 0) {
            return redirect()->route('project.invoice.index')->with(['alert-type' => 'warning', 'message' => "Invoice Already Exist"]);
        }elseif($job_project->tasks->count() == $job_project->tasks->sum('is_invoice')){
            return redirect()->route('project.invoice.index')->with(['alert-type' => 'warning', 'message' => "Invoice Already Exist"]);
        }else{
            return view('backend.job-project-invoice.create', compact('job_project', 'customers', 'vats'));
        }
    }

    public function store(Request $request)
    {

        $request->validate([
            'invoice_no' => 'required|unique:job_project_tem_invoices,invoice_no',
            'job_project_id' => 'required',
            'customer_id' => 'required',
        ]);

        $date_array = explode('/', $request->date);
        $date_string = implode('-', $date_array);
        $date_time = date('Y-m-d', strtotime($date_string));
        $date = \DateTime::createFromFormat('Y-m-d', $date_time);

        $tem_invoice_data = $request->only('job_project_id', 'customer_id', 'invoice_no','invoice_type','discount_amount','tax_invoice');

        $tem_invoice_data['budget'] = $request->total;
        $tem_invoice_data['total_budget'] = $request->invoice_amount;
        $tem_invoice_data['due_amount'] = $request->total_due_amount;
        $tem_invoice_data['vat'] = $request->vat;
        $tem_invoice_data['date'] = date('Y/m/d');
        $tem_invoice_data['discount'] = $request->total_discount;
        $tem_invoice_data['created_by'] = auth()->id();

        $tem_invoice = JobProjectTemInvoice::create($tem_invoice_data);

        if($request->invoice_type == 'task_base'){
            $task_ids = $request->invoice_tasks;
            $due_amount = $request->due_amount;
            $discount = $request->discount;
            $unit = $request->unit;
            $rate = $request->rate;
            $qty = $request->qty;
            $amount = $request->amount;
            $advance_amount = $request->advance_amount;
            $description = $request->description;
            $task_name = $request->task_name;
            $invoice_amount = $request->invoice_amount;

            foreach($task_ids as $i => $task_id) {
                $task = JobProjectTask::find($task_id);
                if($invoice_amount > $task->due_amount){
                    $task_invoice_amount = $task->due_amount;
                    $invoice_amount -= $task->due_amount;
                }else{
                    $task_invoice_amount = $task->due_amount - $invoice_amount;
                    $invoice_amount = 0;
                }
                $tem_invoice->tasks()->create([
                    'task_name' => $task_name[$i],
                    'description' => $description[$i],
                    'due_amount' => $due_amount[$i],
                    'discount' => $discount[$i],
                    'advance_amount' => $advance_amount[$i],
                    'qty' => $qty[$i],
                    'unit' => $unit[$i],
                    'rate' => $rate[$i],
                    'invoice_amount' => $task_invoice_amount,
                    'amount' => $amount[$i],
                    'task_id' => $task->id,
                ]);
                $task->update([
                    'is_invoice' => 1,
                ]);
            }
        }

        $tem_invoice->project->update([
            'invoice_type' => $request->invoice_type,
        ]);

        return redirect()->route('project.invoice.index')->with(['alert-type' => 'success', 'message' => 'The invoic has been created successfully']);
    }

    public function show(JobProjectTemInvoice $tem_invoice)
    {
        $standard = VatRate::where('name','Standard')->first();
        return view('backend.job-project-invoice.view', compact('tem_invoice','standard'));
    }

    public function edit(JobProjectTemInvoice $tem_invoice)
    {
        $customers = PartyInfo::all();
        $vats = VatRate::all();
        $job_project = JobProject::with(['tasks' => function($q){
            $q->where('is_invoice',0);
        }])->find($tem_invoice->job_project_id);
        return view('backend.job-project-invoice.edit', compact('tem_invoice', 'customers', 'vats', 'job_project'));
    }

    public function update(Request $request, JobProjectTemInvoice $tem_invoice)
    {
        $request->validate([
            'invoice_no' => 'required|unique:job_project_tem_invoices,invoice_no',
            'job_project_id' => 'required',
            'customer_id' => 'required',
        ]);

        $date_array = explode('/', $request->date);
        $date_string = implode('-', $date_array);
        $date_time = date('Y-m-d', strtotime($date_string));
        $date = \DateTime::createFromFormat('Y-m-d', $date_time);

        $tem_invoice_data = $request->only('job_project_id', 'customer_id', 'invoice_no','invoice_type','discount_amount','tax_invoice');

        $tem_invoice_data['budget'] = $request->total;
        $tem_invoice_data['total_budget'] = $request->invoice_amount;
        $tem_invoice_data['due_amount'] = $request->total_due_amount;
        $tem_invoice_data['vat'] = $request->vat;
        $tem_invoice_data['date'] = date('Y/m/d');
        $tem_invoice_data['discount'] = $request->total_discount;
        $tem_invoice_data['updated_by'] = auth()->id();

        $tem_invoice->update($tem_invoice_data);
        $tem_invoice->tasks->each->delete();

        $task_ids = $request->invoice_tasks;
        $due_amount = $request->due_amount;
        $discount = $request->discount;
        $unit = $request->unit;
        $rate = $request->rate;
        $qty = $request->qty;
        $amount = $request->amount;
        $advance_amount = $request->advance_amount;
        $description = $request->description;
        $task_name = $request->task_name;

        if($request->invoice_type == 'task_base'){

            $invoice_amount = $request->invoice_amount;
            foreach($task_ids as $i => $task_id) {
                $task = JobProjectTask::find($task_id);
                if($invoice_amount > $task->due_amount){
                    $task_invoice_amount = $task->due_amount;
                    $invoice_amount -= $task->due_amount;
                }else{
                    $task_invoice_amount = $task->due_amount - $invoice_amount;
                    $invoice_amount = 0;
                }
                $tem_invoice->tasks()->create([
                    'task_name' => $task_name[$i],
                    'description' => $description[$i],
                    'due_amount' => $due_amount[$i],
                    'discount' => $discount[$i],
                    'advance_amount' => $advance_amount[$i],
                    'qty' => $qty[$i],
                    'unit' => $unit[$i],
                    'rate' => $rate[$i],
                    'invoice_amount' => $task_invoice_amount,
                    'amount' => $amount[$i],
                    'task_id' => $task->id,
                ]);
                $task->update([
                    'is_invoice' => 1,
                ]);
            }
        }
        return back()->with(['alert-type' => 'success','message' => 'The invoice has been updated Successfully']);
    }

    public function makeAutorizeInvoice(JobProjectTemInvoice $tem_invoice){
        $tem_invoice->update(['authorize_by' => auth()->id()]);
        return back()->with(['alert-type' => 'success', 'message' => 'Successfully invoice authorized']);
    }

    public function authorizeInvoice(){
        $tem_invoices = JobProjectTemInvoice::whereNotNull('authorize_by')->whereNull('approved_by')->latest()->paginate(20);
        return view('backend.job-project-invoice.authorize',compact('tem_invoices'));
    }

    public function makeApprovedInvoice(JobProjectTemInvoice $tem_invoice){
        $sub_invoice = Carbon::now()->format('Ymd');
        $let_invoice_exp = JobProjectInvoice::whereDate('created_at', Carbon::today())->where('invoice_no', 'LIKE', "%{$sub_invoice}%")->latest('id')->first();
        if ($let_invoice_exp) {
            $tem_invoice_no = preg_replace('/^INV-/', '', $let_invoice_exp->invoice_no);
            $invoice_no = $tem_invoice_no + 1;
        } else {
            $invoice_no = Carbon::now()->format('Ymd') . '001';
        }
        $invoice = new JobProjectInvoice();
        $invoice->invoice_no = 'INV-'.$invoice_no;
        $invoice->job_project_id = $tem_invoice->job_project_id;
        $invoice->customer_id = $tem_invoice->customer_id;
        $invoice->budget = $tem_invoice->budget;
        $invoice->discount =$tem_invoice->discount ? $tem_invoice->discount : 0;
        $invoice->total_budget = $tem_invoice->total_budget;
        $invoice->due_amount = $tem_invoice->due_amount;
        $invoice->vat = $tem_invoice->vat;
        $invoice->paid_amount = $tem_invoice->project->paid_amount;
        $invoice->date = date('Y/m/d');

        $invoice->created_by = $tem_invoice->created_by;
        $invoice->updated_by = $tem_invoice->updated_by;

        $invoice->authorized_by = $tem_invoice->authorize_by;
        $invoice->approved_by = auth()->id();
        $invoice->invoice_from = 'project';

        $invoice->save();

        if($tem_invoice->invoice_type == 'task_base'){
            foreach($tem_invoice->tasks as $task){
                $invoice->tasks()->create([
                    'task_id' => $task->task_id,
                    'task_name' => $task->task_name,
                    'invoice_id' => $invoice->id,
                    'paid_amount' => $task->paid_amount,
                    'description' => $task->description,
                    'due_amount' => $task->due_amount,
                    'discount' =>$task->discount ? $task->discount : 0,
                    'advance_amount' => $task->advance_amount,
                    'qty' => $task->qty,
                    'unit' => $task->unit,
                    'rate' => $task->rate,
                    'invoice_amount' => $task->invoice_amount,
                    'amount' => $task->amount,
                ]);

                $project_task = JobProjectTask::find($task->task_id);
                if($task->due_amount <= $project_task->due_amount){
                    $project_task->is_invoice = 1;
                }else{
                    $project_task->is_invoice = 0;
                }
                $project_task->save();
            }
        }

        $project = $tem_invoice->project;
        $project->paid_amount = $project->paid_amount + $tem_invoice->due_amount;
        $project->due_amount = $project->due_amount - $tem_invoice->due_amount;
        if ($project->due_amount <= 0) {
            $project->is_invoice = 1;
        }else{
            $project->is_invoice = 0;
        }

        $project->save();

        foreach($tem_invoice->tasks as $task){
            $task->delete();
        }
        // // ********************************************************************************
        // uninvoiced journal adjustment

        // $journal_no = $this->journal_no();
        // $journal = new Journal();
        // $journal->project_id        = 1;
        // $journal->job_project_id        = $project->id;
        // $journal->transection_type = 'Project Invoice';
        // $journal->transaction_type = 'Increase';
        // $journal->journal_no        = $journal_no;
        // $journal->date              = $tem_invoice->date;
        // $journal->pay_mode          = 'Credit';
        // $journal->cost_center_id    = 0;
        // $journal->party_info_id     = $tem_invoice->customer_id;
        // $journal->account_head_id   = 123;
        // $journal->voucher_type   = 'DEBIT';
        // $journal->amount            = $tem_invoice->total_budget;
        // $journal->tax_rate          = 0;
        // $journal->vat_amount        = $tem_invoice->vat;
        // $journal->total_amount      = $tem_invoice->budget;
        // $journal->gst_subtotal = 0;
        // $journal->narration         =  'Project Invoice';
        // $journal->approved_by = Auth::id();
        // $journal->authorized_by         = Auth::id();
        // $journal->created_by = Auth::id();
        // $journal->save();

        // journal record Receivable entry
        // $ac_head = AccountHead::find(3);
        // $jl_record = new JournalRecord();
        // $jl_record->journal_id     = $journal->id;
        // $jl_record->project_details_id  = $journal->project_id;
        // $jl_record->cost_center_id      = $journal->cost_center_id;
        // $jl_record->party_info_id       =  $journal->party_info_id;
        // $jl_record->journal_no          =  $journal->journal_no;
        // $jl_record->account_head_id     = $ac_head->id;
        // $jl_record->master_account_id   = $ac_head->master_account_id;
        // $jl_record->account_head        = $ac_head->fld_ac_head;
        // $jl_record->amount              = $journal->amount;
        // $jl_record->total_amount        = $journal->amount;
        // $jl_record->vat_rate_id         = 0;
        // $jl_record->invoice_no        = 0;
        // $jl_record->transaction_type    = 'DR';
        // $jl_record->journal_date        =  $journal->date;
        // $jl_record->is_main_head        = 1;
        // $jl_record->account_type_id = $ac_head->account_type_id;
        // $jl_record->save();
        // end journal record Receivable entry

        // journal record  Revenue entry
        // $ac_head = AccountHead::find(7);
        // $jl_record = new JournalRecord();
        // $jl_record->journal_id     = $journal->id;
        // $jl_record->project_details_id  = $journal->project_id;
        // $jl_record->cost_center_id      = $journal->cost_center_id;
        // $jl_record->party_info_id       =  $journal->party_info_id;
        // $jl_record->journal_no          =  $journal->journal_no;
        // $jl_record->account_head_id     = $ac_head->id;
        // $jl_record->master_account_id   = $ac_head->master_account_id;
        // $jl_record->account_head        = $ac_head->fld_ac_head;
        // $jl_record->amount              = $journal->total_amount;
        // $jl_record->total_amount        = $journal->total_amount;
        // $jl_record->vat_rate_id         = 0;
        // $jl_record->invoice_no        = 0;
        // $jl_record->transaction_type    = 'CR';
        // $jl_record->journal_date        =  $journal->date;
        // $jl_record->is_main_head        = 1;
        // $jl_record->account_type_id = $ac_head->account_type_id;
        // $jl_record->save();
        // end journal record Revenue entry


        // journal record Vat entry
        // if($journal->vat_amount>0)
        // {
        //     $ac_head = AccountHead::find(17);
        //     $jl_record = new JournalRecord();
        //     $jl_record->journal_id     = $journal->id;
        //     $jl_record->project_details_id  = $journal->project_id;
        //     $jl_record->cost_center_id      = $journal->cost_center_id;
        //     $jl_record->party_info_id       =  $journal->party_info_id;
        //     $jl_record->journal_no          =  $journal->journal_no;
        //     $jl_record->account_head_id     = $ac_head->id;
        //     $jl_record->master_account_id   = $ac_head->master_account_id;
        //     $jl_record->account_head        = $ac_head->fld_ac_head;
        //     $jl_record->amount              = $journal->vat_amount;
        //     $jl_record->total_amount        = $journal->vat_amount;
        //     $jl_record->vat_rate_id         = 0;
        //     $jl_record->invoice_no        = 0;
        //     $jl_record->transaction_type    = 'CR';
        //     $jl_record->journal_date        =  $journal->date;
        //     $jl_record->is_main_head        = 1;
        //     $jl_record->account_type_id = $ac_head->account_type_id;
        //     $jl_record->save();
        // }
        // end journal record vat entry



        // *****************************************************************************
        // uninvoiced journal adjustment
        // $journal_no = $this->journal_no();
        // $journal = new Journal();
        // $journal->project_id        = 1;
        // $journal->job_project_id        = $project->id;
        // $journal->transection_type = 'Uninvoiced account adjustment';
        // $journal->transaction_type = 'Increase';
        // $journal->journal_no        = $journal_no;
        // $journal->date              = $tem_invoice->date;
        // $journal->pay_mode          = 'Credit';
        // $journal->cost_center_id    = 0;
        // $journal->party_info_id     = $tem_invoice->customer_id;
        // $journal->account_head_id   = 123;
        // $journal->voucher_type   = 'DEBIT';
        // $journal->amount            = $diff;
        // $journal->tax_rate          = 0;
        // $journal->vat_amount        = 0;
        // $journal->total_amount      = $diff;
        // $journal->gst_subtotal = 0;
        // $journal->narration         =  'Project Invoice';
        // $journal->approved_by = Auth::id();
        // $journal->authorized_by         = Auth::id();
        // $journal->created_by = Auth::id();
        // $journal->save();



        // // journal record Receivable entry
        // $ac_head = AccountHead::find(19);
        // $jl_record = new JournalRecord();
        // $jl_record->journal_id     = $journal->id;
        // $jl_record->project_details_id  = $journal->project_id;
        // $jl_record->cost_center_id      = $journal->cost_center_id;
        // $jl_record->party_info_id       =  $journal->party_info_id;
        // $jl_record->journal_no          =  $journal->journal_no;
        // $jl_record->account_head_id     = $ac_head->id;
        // $jl_record->master_account_id   = $ac_head->master_account_id;
        // $jl_record->account_head        = $ac_head->fld_ac_head;
        // $jl_record->amount              = $journal->total_amount;
        // $jl_record->total_amount        = $journal->total_amount;
        // $jl_record->vat_rate_id         = 0;
        // $jl_record->invoice_no        = 0;
        // $jl_record->transaction_type    = 'CR';
        // $jl_record->journal_date        =  $journal->date;
        // $jl_record->is_main_head        = 1;
        // $jl_record->account_type_id = $ac_head->account_type_id;
        // $jl_record->save();
        // end journal record Receivable entry

        // journal record ninvoiced Revenue entry
        // $ac_head = AccountHead::find(20);
        // $jl_record = new JournalRecord();
        // $jl_record->journal_id     = $journal->id;
        // $jl_record->project_details_id  = $journal->project_id;
        // $jl_record->cost_center_id      = $journal->cost_center_id;
        // $jl_record->party_info_id       =  $journal->party_info_id;
        // $jl_record->journal_no          =  $journal->journal_no;
        // $jl_record->account_head_id     = $ac_head->id;
        // $jl_record->master_account_id   = $ac_head->master_account_id;
        // $jl_record->account_head        = $ac_head->fld_ac_head;
        // $jl_record->amount              = $journal->total_amount;
        // $jl_record->total_amount        = $journal->total_amount;
        // $jl_record->vat_rate_id         = 0;
        // $jl_record->invoice_no        = 0;
        // $jl_record->transaction_type    = 'DR';
        // $jl_record->journal_date        =  $journal->date;
        // $jl_record->is_main_head        = 1;
        // $jl_record->account_type_id = $ac_head->account_type_id;
        // $jl_record->save();
        // end journal record Revenue entry

        if($tem_invoice->invoice_type == 'task_base'){
            $tem_invoice->project->update(['due_amount' => $tem_invoice->project->due_amount + $tem_invoice->due_amount]);
        }

        $tem_invoice->delete();

        return back()->with(['alert-type' => 'success', 'message' => 'Invoice has beed approved']);
    }

    public function approvedInvoice(Request $request){

        $search = $request->search;
        if($request->filter_date){
            $date_array =  explode('/',$request->filter_date);
            $date = $date_array[2].'-'.$date_array[1].'-'.$date_array[0];
            $invoices = JobProjectInvoice::where('invoice_from','project')->where('date',$date)->where('invoice_no','like','%'.$search . '%')->latest()->paginate(20);
        }else if($search){
            $invoices = JobProjectInvoice::where('invoice_from','project')->where('invoice_no','like','%'.$search . '%')->latest()->paginate(20);
        }else{
            $invoices = JobProjectInvoice::with('tasks')->where('invoice_from','project')->latest()->paginate(20);
        }
        return view('backend.job-project-invoice.approve-invoice',compact('invoices'));
    }

    public function approvedInvoiceView(JobProjectInvoice $invoice){
        $standard = VatRate::where('name','Standard')->first();
        return view('backend.job-project-invoice.approve-invoice-view',compact('invoice','standard'));
    }

    public function destroy(JobProjectTemInvoice $tem_invoice){
        if($tem_invoice->invoice_type == 'task_base'){
            foreach($tem_invoice->tasks as $invoice_task){
                $task = JobProjectTask::find($invoice_task->task_id);
                $task->update(['is_invoice'=> 0]);
                $invoice_task->delete();
            }

            $invoice_item = $tem_invoice->project->tasks->sum('is_invoice');

            if($invoice_item == 0){
                $tem_invoice->project->update([
                    'invoice_type' => Null,
                ]);
            }
        }
        $tem_invoice->delete();
        return back()->with(['alert-type' => 'success', 'message' => 'The invoice has been deleted successfully']);
    }

}

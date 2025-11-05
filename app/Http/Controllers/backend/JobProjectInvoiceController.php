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
use App\Models\InvoiceNumber;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $tem_invoices = JobProjectTemInvoice::whereNotNull('authorize_by')->whereNull('approved_by')->latest()->paginate(20);
        return view('backend.job-project-invoice.authorize',compact('tem_invoices'));
    }

    public function checkInvoiceNo(Request $request)
    {
        $sub_invoice = 'TI'.Carbon::now()->format('y');
        $invoice = InvoiceNumber::where('invoice_no', 'LIKE', "%{$sub_invoice}%")->first();
        if ($invoice) {
            $number = preg_replace('/^'.$sub_invoice.'/', '', $invoice->invoice_no);
            $number++;
            if($number<10)
            {
                $invoice_no=$sub_invoice.'000'.$number;
            }
            elseif($number<100)
            {
                $invoice_no=$sub_invoice.'00'.$number;
            }
            elseif($number<1000)
            {
                $invoice_no=$sub_invoice.'0'.$number;
            }
            else
            {
                $invoice_no=$sub_invoice.$number;

            }
        } else {
            $invoice_no  = $sub_invoice . '0001';
        }
        return $invoice_no;
    }

    public function invoice_no($type)
    {
        if($type == 'Tax Invoice'){
            $sub_invoice = 'TI'.Carbon::now()->format('y');
            $invoice = InvoiceNumber::where('invoice_no', 'LIKE', "%{$sub_invoice}%")->first();

        }else{

            $sub_invoice = 'PI'.Carbon::now()->format('y');
            $invoice = InvoiceNumber::where('proforma_invoice_no', 'LIKE', "%{$sub_invoice}%")->first();

        }
        if ($invoice) {
            if($type == 'Tax Invoice'){
                $number = preg_replace('/^'.$sub_invoice.'/', '', $invoice->invoice_no);
            }else{
                $number = preg_replace('/^'.$sub_invoice.'/', '', $invoice->proforma_invoice_no);
            }
            $number++;
            if($number<10)
            {
                $invoice_no=$sub_invoice.'000'.$number;
            }
            elseif($number<100)
            {
                $invoice_no=$sub_invoice.'00'.$number;
            }
            elseif($number<1000)
            {
                $invoice_no=$sub_invoice.'0'.$number;
            }
            else
            {
                $invoice_no=$sub_invoice.$number;

            }
        } else {
            $invoice_no  = $sub_invoice . '0001';
        }
        return $invoice_no;
    }
    public function projectInvoiceCreate($id)
    {
        $vats = VatRate::all();
        $job_project = JobProject::with(['tasks' => function ($q) {
            $q->where('is_invoice', 0);
        }])->find($id);
        $customers = Partyinfo::where('id',$job_project->customer_id)->get();
        

        if ($job_project->is_invoice == 1 || $job_project->due_amount == 0 || ($job_project->tem_invoices->sum('due_amount') > 1 && $job_project->invoice_type == 'amount_base')) {
            return redirect()->route('project.invoice.index')->with(['alert-type' => 'warning', 'message' => "Invoice Already Exist"]);
        }elseif($job_project->tasks->count() == $job_project->tasks->sum('is_invoice')){
            return redirect()->route('project.invoice.index')->with(['alert-type' => 'warning', 'message' => "Invoice Already Exist"]);
        }else{
            return view('backend.job-project-invoice.create', compact('job_project', 'customers', 'vats'));
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            // 'invoice_no' => 'required',
            'job_project_id' => 'required',
            'customer_id' => 'required',
        ]);

        $date_array = explode('/', $request->date);
        $date_string = implode('-', $date_array);
        $date_time = date('Y-m-d', strtotime($date_string));
        $date = \DateTime::createFromFormat('Y-m-d', $date_time);

        $tem_invoice_data = $request->only('customer_id','invoice_type','discount_amount','tax_invoice','attention');

        $tem_invoice_data['budget'] = $request->invoice_amount - $request->vat;
        if($request->tax_invoice == 'Tax Invoice'){
            $tem_invoice_data['invoice_no'] = $this->invoice_no( $request->tax_invoice);
        }else{
            $tem_invoice_data['proforma_invoice_no'] = $this->invoice_no( $request->tax_invoice);
        }

        $tem_invoice_data['total_budget'] = $request->invoice_amount;
        $tem_invoice_data['due_amount'] = $request->invoice_amount;
        $tem_invoice_data['vat'] = $request->vat;
        $tem_invoice_data['date'] = date('Y/m/d');
        $tem_invoice_data['discount'] = $request->total_discount;
        $tem_invoice_data['total_due_amount_percentage'] = $request->total_due_amount_percentage;
        $tem_invoice_data['created_by'] = auth()->id();
        $tem_invoice_data['site_delivery'] = $request->site_delivery;
        $tem_invoice_data['top_note'] = $request->top_note;
        $tem_invoice_data['with_note'] = $request->with_note?true:false;
        $tem_invoice_data['authorize_by'] = auth()->id();
        $tem_invoice_data['mobile_no'] = $request->mobile_no;
        $tem_invoice_data['project_id'] = $request->project_id;
        $tem_invoice_data['job_project_id'] = $request->job_project_id;

        $voucher_file_name = '';
        $ext = '';
        if($request->hasFile('voucher_file')){
            $voucher_scan = $request->file('voucher_file');
            $name = $voucher_scan->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $voucher_scan->getClientOriginalExtension();
            $voucher_file_name = $name.time(). '.'. $ext;
            $voucher_scan->storeAs('public/upload/documents',$voucher_file_name);
        }
        $tem_invoice_data['voucher_file'] = $voucher_file_name;
        $tem_invoice_data['extension'] = $ext;
        $tem_invoice = JobProjectTemInvoice::create($tem_invoice_data);

        $invoice = InvoiceNumber::first();
        if($request->tax_invoice == 'Tax Invoice'){
            $invoice->invoice_no = $tem_invoice->invoice_no;
        }else{
            $invoice->proforma_invoice_no = $tem_invoice->proforma_invoice_no;
        }
        $invoice->save();

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
        $notes= JobProjectInvoice::where('job_project_id',$tem_invoice->job_project_id)->orderBy('id','asc')->get();
        return view('backend.job-project-invoice.view', compact('tem_invoice','standard','notes'));
    }

    public function temp_invoice_print($id)
    {
        $tem_invoice=JobProjectTemInvoice::find($id);
        $standard = VatRate::where('name','Standard')->first();
        $notes= JobProjectInvoice::where('job_project_id',$tem_invoice->job_project_id)->orderBy('id','asc')->get();

        return view('backend.job-project-invoice.temp-print', compact('tem_invoice','standard','notes'));
    }

    public function invoice_print($id)
    {
        $invoice=JobProjectInvoice::find($id);
        $standard = VatRate::where('name','Standard')->first();
        $notes= JobProjectInvoice::where('id','<=',$invoice->id)->where('job_project_id',$invoice->job_project_id)->orderBy('id','asc')->get();
       // return($notes);
        return view('backend.job-project-invoice.approve-invoice-print',compact('invoice','standard','notes'));
   }

    public function edit(JobProjectTemInvoice $tem_invoice)
    {
        $vats = VatRate::all();
        $job_project = JobProject::with(['tasks' => function($q){
            $q->where('is_invoice',0);
        }])->find($tem_invoice->job_project_id);
        // dd($job_project);
        $customers = PartyInfo::where('id',$job_project->customer_id)->get();

        return view('backend.job-project-invoice.edit', compact('tem_invoice', 'customers', 'vats', 'job_project'));
    }

    public function update(Request $request, JobProjectTemInvoice $tem_invoice)
    {
        $request->validate([
            // 'invoice_no' => 'required|unique:job_project_tem_invoices,invoice_no,'.$tem_invoice->id,
            'job_project_id' => 'required',
            'customer_id' => 'required',
        ]);

        $date_array = explode('/', $request->date);
        $date_string = implode('-', $date_array);
        $date_time = date('Y-m-d', strtotime($date_string));
        $date = \DateTime::createFromFormat('Y-m-d', $date_time);

        $tem_invoice_data = $request->only('job_project_id', 'customer_id', 'invoice_no','invoice_type','discount_amount','tax_invoice','attention');
        if($request->tax_invoice == 'Tax Invoice'){
            if($tem_invoice->invoice_no ==''){
                $invoice = InvoiceNumber::first();
                $no =$this->invoice_no( $request->tax_invoice);
                $tem_invoice_data['invoice_no'] =  $no;
                $invoice->invoice_no =  $no;
                $invoice->save();

            }
        }else{
            if($tem_invoice->proforma_invoice_no ==''){

                $invoice = InvoiceNumber::first();
                $no =$this->invoice_no( $request->tax_invoice);
                $tem_invoice_data['proforma_invoice_no'] = $no  ;
                $invoice->proforma_invoice_no =  $no;
                $invoice->save();
            }
        }
        $tem_invoice_data['budget'] = $request->invoice_amount - $request->vat;;
        $tem_invoice_data['total_budget'] = $request->invoice_amount;
        $tem_invoice_data['due_amount'] = $request->invoice_amount;
        $tem_invoice_data['vat'] = $request->vat;
        $tem_invoice_data['date'] = date('Y/m/d');
        $tem_invoice_data['discount'] = $request->total_discount;
        $tem_invoice_data['total_due_amount_percentage'] = $request->total_due_amount_percentage;
        $tem_invoice_data['top_note'] = $request->top_note;
        $tem_invoice_data['with_note'] = $request->with_note?true:false;
        $tem_invoice_data['updated_by'] = auth()->id();
        $tem_invoice_data['mobile_no'] = $request->mobile_no;

        $voucher_file_name = $tem_invoice->voucher_file;
        $ext = $tem_invoice->extension;
        if($request->hasFile('voucher_file')){
            if(Storage::exists('public/upload/documents/'. $tem_invoice->voucher_file)){
                Storage::delete('public/upload/documents/'. $tem_invoice->voucher_file);

            }
            $voucher_scan = $request->file('voucher_file');
            $name = $voucher_scan->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $voucher_scan->getClientOriginalExtension();
            $voucher_file_name = $name.time(). '.' . $ext;
            $voucher_scan->storeAs('public/upload/documents', $voucher_file_name);

        }
        $tem_invoice_data['voucher_file'] = $voucher_file_name;
        $tem_invoice_data['extension'] = $ext;
        $tem_invoice->update($tem_invoice_data);

        foreach($tem_invoice->tasks as $task){
            $pro_task = JobProjectTask::find($task->task_id);
            $pro_task->update(['is_invoice' => 0]);
            $task->delete();
        }

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
        return redirect('project/job/project/author/invoice')->with(['alert-type' => 'success','message' => 'The invoice has been updated Successfully']);
    }

    public function makeAutorizeInvoice(JobProjectTemInvoice $tem_invoice){
        $tem_invoice->update(['authorize_by' => auth()->id()]);
        return redirect('project/job/project/author/invoice')->with(['alert-type' => 'success', 'message' => 'Successfully invoice authorized']);
    }

    public function authorizeInvoice(){
        $tem_invoices = JobProjectTemInvoice::whereNotNull('authorize_by')->whereNull('approved_by')->latest()->paginate(20);
        return view('backend.job-project-invoice.authorize',compact('tem_invoices'));
    }

    public function makeApprovedInvoice(JobProjectTemInvoice $tem_invoice){
        // dd($tem_invoice);
        $invoice = new JobProjectInvoice();
        $invoice->invoice_no =  $tem_invoice->invoice_no;
        $invoice->proforma_invoice_no =  $tem_invoice->proforma_invoice_no;

        $invoice->job_project_id = $tem_invoice->job_project_id;
        $invoice->customer_id = $tem_invoice->customer_id;
        $invoice->budget = $tem_invoice->budget;
        $invoice->discount =$tem_invoice->discount ? $tem_invoice->discount : 0;
        $invoice->total_due_amount_percentage =$tem_invoice->total_due_amount_percentage ? $tem_invoice->total_due_amount_percentage : 0;
        $invoice->total_budget = $tem_invoice->total_budget;
        $invoice->due_amount = $tem_invoice->due_amount;
        $invoice->vat = $tem_invoice->vat;
        $invoice->paid_amount = 0;
        $invoice->invoice_type = $tem_invoice->tax_invoice;
        $invoice->date = $tem_invoice->date;
        $invoice->top_note = $tem_invoice->top_note;
        $invoice->with_note = $tem_invoice->with_note;
        $invoice->created_by = $tem_invoice->created_by;
        $invoice->updated_by = $tem_invoice->updated_by;
        $invoice->authorized_by = $tem_invoice->authorize_by;
        $invoice->attention = $tem_invoice->attention;

        $invoice->approved_by = auth()->id();
        $invoice->invoice_from = 'project';
        $invoice->site_delivery = $tem_invoice->site_delivery;
        $invoice->voucher_file = $tem_invoice->voucher_file;
        $invoice->extension = $tem_invoice->extension;
        $invoice->mobile_no = $tem_invoice->mobile_no;
        $invoice->project_id = $tem_invoice->project_id;
        $invoice->pay_mode = 'Credit';
        // dd($invoice);
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
        $project->paid_amount = $project->paid_amount + $tem_invoice->budget;
        $project->due_amount = $project->due_amount - $tem_invoice->budget;
        $project->paid_amount_percentage = $project->paid_amount_percentage + $tem_invoice->total_due_amount_percentage;


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
        if($invoice->invoice_type == 'Tax Invoice'){
            $journal_no = $this->journal_no();
            $journal = new Journal();
            $journal->project_id        = 1;
            $journal->job_project_id    = $project->id;
            $journal->invoice_id        = $invoice->id;
            $journal->transection_type  = 'Project Invoice';
            $journal->transaction_type  = 'Increase';
            $journal->journal_no        = $journal_no;
            $journal->date              = $invoice->date;
            $journal->pay_mode          = $invoice->pay_mode;
            $journal->cost_center_id    = 0;
            $journal->party_info_id     = $invoice->customer_id;
            $journal->account_head_id   = 123;
            $journal->voucher_type      = 'DEBIT';
            $journal->amount            = $invoice->total_budget;
            $journal->tax_rate          = 0;
            $journal->vat_amount        = $invoice->vat;
            $journal->total_amount      = $invoice->budget;
            $journal->gst_subtotal      = 0;
            $journal->narration         = 'Project Invoice';
            $journal->approved_by       = Auth::id();
            $journal->authorized_by     = Auth::id();
            $journal->created_by        = Auth::id();
            $journal->save();

            // journal record Receivable entry
            $ac_head = AccountHead::find(3);
            $jl_record = new JournalRecord();
            $jl_record->journal_id     = $journal->id;
            $jl_record->project_details_id  = $journal->project_id;
            $jl_record->cost_center_id      = $journal->cost_center_id;
            $jl_record->party_info_id       =  $journal->party_info_id;
            $jl_record->journal_no          =  $journal->journal_no;
            $jl_record->account_head_id     = $ac_head->id;
            $jl_record->master_account_id   = $ac_head->master_account_id;
            $jl_record->account_head        = $ac_head->fld_ac_head;
            $jl_record->amount              = $journal->amount;
            $jl_record->total_amount        = $journal->amount;
            $jl_record->vat_rate_id         = 0;
            $jl_record->invoice_no        = 0;
            $jl_record->transaction_type    = 'DR';
            $jl_record->journal_date        =  $journal->date;
            $jl_record->is_main_head        = 1;
            $jl_record->account_type_id = $ac_head->account_type_id;
            $jl_record->save();
            // end journal record Receivable entry

            // journal record  Revenue entry
            $ac_head = AccountHead::find(7);
            $jl_record = new JournalRecord();
            $jl_record->journal_id     = $journal->id;
            $jl_record->project_details_id  = $journal->project_id;
            $jl_record->cost_center_id      = $journal->cost_center_id;
            $jl_record->party_info_id       =  $journal->party_info_id;
            $jl_record->journal_no          =  $journal->journal_no;
            $jl_record->account_head_id     = $ac_head->id;
            $jl_record->master_account_id   = $ac_head->master_account_id;
            $jl_record->account_head        = $ac_head->fld_ac_head;
            $jl_record->amount              = $journal->total_amount;
            $jl_record->total_amount        = $journal->total_amount;
            $jl_record->vat_rate_id         = 0;
            $jl_record->invoice_no        = 0;
            $jl_record->transaction_type    = 'CR';
            $jl_record->journal_date        =  $journal->date;
            $jl_record->is_main_head        = 1;
            $jl_record->account_type_id = $ac_head->account_type_id;
            $jl_record->save();
            // end journal record Revenue entry


            // journal record Vat entry
            if($journal->vat_amount>0)
            {
                $ac_head = AccountHead::find(17);
                $jl_record = new JournalRecord();
                $jl_record->journal_id     = $journal->id;
                $jl_record->project_details_id  = $journal->project_id;
                $jl_record->cost_center_id      = $journal->cost_center_id;
                $jl_record->party_info_id       =  $journal->party_info_id;
                $jl_record->journal_no          =  $journal->journal_no;
                $jl_record->account_head_id     = $ac_head->id;
                $jl_record->master_account_id   = $ac_head->master_account_id;
                $jl_record->account_head        = $ac_head->fld_ac_head;
                $jl_record->amount              = $journal->vat_amount;
                $jl_record->total_amount        = $journal->vat_amount;
                $jl_record->vat_rate_id         = 0;
                $jl_record->invoice_no        = 0;
                $jl_record->transaction_type    = 'CR';
                $jl_record->journal_date        =  $journal->date;
                $jl_record->is_main_head        = 1;
                $jl_record->account_type_id = $ac_head->account_type_id;
                $jl_record->save();
            }
            
            // uninvoiced journal adjustment
            $journal_no = $this->journal_no();
            $journal = new Journal();
            $journal->project_id        = 1;
            $journal->job_project_id        = $project->id;
            $journal->transection_type = 'Uninvoiced account adjustment';
            $journal->transaction_type = 'Increase';
            $journal->journal_no        = $journal_no;
            $journal->date              = $tem_invoice->date;
            $journal->pay_mode          = 'Credit';
            $journal->cost_center_id    = 0;
            $journal->party_info_id     = $tem_invoice->customer_id;
            $journal->account_head_id   = 123;
            $journal->voucher_type   = 'DEBIT';
            $journal->amount            = $tem_invoice->budget;
            $journal->tax_rate          = 0;
            $journal->vat_amount        = 0;
            $journal->total_amount      = $tem_invoice->budget;
            $journal->gst_subtotal = 0;
            $journal->narration         =  'Project Invoice';
            $journal->approved_by = Auth::id();
            $journal->authorized_by         = Auth::id();
            $journal->created_by = Auth::id();
            $journal->save();

            // // journal record Receivable entry
            $ac_head = AccountHead::find(19);
            $jl_record = new JournalRecord();
            $jl_record->journal_id     = $journal->id;
            $jl_record->project_details_id  = $journal->project_id;
            $jl_record->cost_center_id      = $journal->cost_center_id;
            $jl_record->party_info_id       =  $journal->party_info_id;
            $jl_record->journal_no          =  $journal->journal_no;
            $jl_record->account_head_id     = $ac_head->id;
            $jl_record->master_account_id   = $ac_head->master_account_id;
            $jl_record->account_head        = $ac_head->fld_ac_head;
            $jl_record->amount              = $journal->total_amount;
            $jl_record->total_amount        = $journal->total_amount;
            $jl_record->vat_rate_id         = 0;
            $jl_record->invoice_no        = 0;
            $jl_record->transaction_type    = 'CR';
            $jl_record->journal_date        =  $journal->date;
            $jl_record->is_main_head        = 1;
            $jl_record->account_type_id = $ac_head->account_type_id;
            $jl_record->save();
            // end journal record Receivable entry

            // journal record ninvoiced Revenue entry
            $ac_head = AccountHead::find(20);
            $jl_record = new JournalRecord();
            $jl_record->journal_id     = $journal->id;
            $jl_record->project_details_id  = $journal->project_id;
            $jl_record->cost_center_id      = $journal->cost_center_id;
            $jl_record->party_info_id       =  $journal->party_info_id;
            $jl_record->journal_no          =  $journal->journal_no;
            $jl_record->account_head_id     = $ac_head->id;
            $jl_record->master_account_id   = $ac_head->master_account_id;
            $jl_record->account_head        = $ac_head->fld_ac_head;
            $jl_record->amount              = $journal->total_amount;
            $jl_record->total_amount        = $journal->total_amount;
            $jl_record->vat_rate_id         = 0;
            $jl_record->invoice_no        = 0;
            $jl_record->transaction_type    = 'DR';
            $jl_record->journal_date        =  $journal->date;
            $jl_record->is_main_head        = 1;
            $jl_record->account_type_id = $ac_head->account_type_id;
            $jl_record->save();
            // end journal record Revenue entry
        }
        $tem_invoice->delete();

        return redirect('project/job/project/approved/invoice')->with(['alert-type' => 'success', 'message' => 'Invoice has beed approved']);
    }

    public function approvedInvoice(Request $request){

        $search = $request->search;
        if($request->filter_date){
            $date_array =  explode('/',$request->filter_date);
            $date = $date_array[2].'-'.$date_array[1].'-'.$date_array[0];
            $invoices = JobProjectInvoice::where('invoice_from','project')->where('date',$date)->where('invoice_no','like','%'.$search . '%')->orderBy('id','DESC')->paginate(20);
        }else if($search){
            $invoices = JobProjectInvoice::where('invoice_from','project')->where('invoice_no','like','%'.$search . '%')->orderBy('created_at','DESC')->paginate(20);
        }else{
            $invoices = JobProjectInvoice::with('tasks')->where('invoice_from','project')->orderBy('created_at','DESC')->paginate(20);
        }
        return view('backend.job-project-invoice.approve-invoice',compact('invoices'));
    }

    public function approvedInvoiceView(JobProjectInvoice $invoice){
        $standard = VatRate::where('name','Standard')->first();
        $notes= JobProjectInvoice::where('id','<=',$invoice->id)->where('job_project_id',$invoice->job_project_id)->orderBy('id','asc')->get();

        return view('backend.job-project-invoice.approve-invoice-view',compact('invoice','standard','notes'));
    }

    public function destroy(JobProjectTemInvoice $tem_invoice){
        if($tem_invoice->invoice_type == 'task_base'){
            foreach($tem_invoice->tasks as $invoice_task){
                $task = JobProjectTask::find($invoice_task->task_id);
                $task->update(['is_invoice'=> 0]);
                $invoice_task->delete();
            }
        }
        $tem_invoice->delete();
        return back()->with(['alert-type' => 'success', 'message' => 'The invoice has been deleted successfully']);
    }



    public function convert_tax_invoice(JobprojectInvoice $invoice){
        $invoice->date=date('Y-m-d');
        if($invoice->invoice_no ==''){
            $invoice_no = InvoiceNumber::first();
            $no =$this->invoice_no('Tax Invoice');
            $invoice->invoice_no = $no;
            $invoice_no->invoice_no =  $no;
            $invoice_no->save();
        }
        $invoice->save();
        $journal_no = $this->journal_no();
        $journal = new Journal();
        $journal->project_id        = 1;
        $journal->invoice_id        = $invoice->id;
        $journal->transection_type = 'Sale';
        $journal->transaction_type = 'Increase';
        $journal->journal_no        = $journal_no;
        $journal->date              =  $invoice->date;
        $journal->pay_mode          = 'CREDIT';
        $journal->cost_center_id    = 0;
        $journal->party_info_id     = $invoice->customer_id;
        $journal->account_head_id   = 123;
        $journal->voucher_type   = 'CREDIT';

        $journal->amount            = $invoice->total_budget;
        $journal->tax_rate          = 0;
        $journal->vat_amount        = $invoice->total_budget - $invoice->budget;
        $journal->total_amount      = $invoice->budget;
        $journal->gst_subtotal = 0;
        $journal->narration         =  $invoice->narration ? $invoice->narration : 'Project Invoice';
        $journal->approved_by = $invoice->approved_by;
        $journal->save();

        //journal record
        $ac_head = AccountHead::find(7);
        $jl_record = new JournalRecord();
        $jl_record->journal_id     = $journal->id;
        $jl_record->project_details_id  = $journal->project_id;
        $jl_record->cost_center_id      = $journal->cost_center_id;
        $jl_record->party_info_id       =  $journal->party_info_id;
        $jl_record->journal_no          =  $journal->journal_no;
        $jl_record->account_head_id     = $ac_head->id;
        $jl_record->master_account_id   = $ac_head->master_account_id;
        $jl_record->account_head        = $ac_head->fld_ac_head;
        $jl_record->amount              = $invoice->budget;
        $jl_record->total_amount        = $invoice->budget;
        $jl_record->vat_rate_id         = 0;
        $jl_record->invoice_no        = 0;
        $jl_record->transaction_type    = 'CR';
        $jl_record->journal_date        =  $journal->date;
        $jl_record->is_main_head        = 1;
        $jl_record->account_type_id = $ac_head->account_type_id;
        $jl_record->save();
        //end journal record

        //vat journal
        if ($journal->vat_amount > 0) {
            $vat_ac_head = AccountHead::find(17); // vat account head
            $jl_record = new JournalRecord();
            $jl_record->journal_id     = $journal->id;
            $jl_record->project_details_id  = $journal->project_id;
            $jl_record->cost_center_id      = $journal->cost_center_id;
            $jl_record->party_info_id       = $journal->party_info_id;
            $jl_record->journal_no          =  $journal->journal_no;
            $jl_record->account_head_id     = $vat_ac_head->id;
            $jl_record->master_account_id   = $vat_ac_head->master_account_id;
            $jl_record->account_head        = $vat_ac_head->fld_ac_head;
            $jl_record->amount              = $journal->vat_amount;
            $jl_record->invoice_no              = 'N/A';
            $jl_record->total_amount        = $journal->vat_amount;
            $jl_record->vat_rate_id         = 0;
            $jl_record->transaction_type    = 'CR';
            $jl_record->journal_date        = $journal->date;
            $jl_record->account_type_id = $vat_ac_head->account_type_id;
            $jl_record->is_main_head        = 0;
            $jl_record->save();
        }
        //end vat journal

        //Paymode journal
        $ac_head = AccountHead::find(3); // accounts Receivable
        $jl_record = new JournalRecord();
        $jl_record->journal_id     = $journal->id;
        $jl_record->project_details_id  = $journal->project_id;
        $jl_record->cost_center_id      = $journal->cost_center_id;
        $jl_record->party_info_id       = $journal->party_info_id;
        $jl_record->journal_no          =  $journal->journal_no;
        $jl_record->account_head_id     = $ac_head->id;
        $jl_record->master_account_id   = $ac_head->master_account_id;
        $jl_record->account_head        = $ac_head->fld_ac_head;
        $jl_record->amount              = $journal->amount;
        $jl_record->total_amount        = $journal->amount;
        $jl_record->vat_rate_id         = 0;
        $jl_record->transaction_type    = 'DR';
        $jl_record->journal_date        = $journal->date;
        $jl_record->invoice_no              = 'N/A';
        $jl_record->account_type_id = $ac_head->account_type_id;

        $jl_record->is_main_head        = 0;
        $jl_record->save();
        //end paymode journal

        $invoice->invoice_type = 'Tax Invoice';
        $invoice->save();

        return back()->with(['alert-type' => 'success','message' => 'Successfully Converted into tax invoice']);
    }

}

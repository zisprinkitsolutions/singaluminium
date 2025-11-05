<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobProjectPaymentStoreRequest;
use App\Http\Requests\JobProjectStoreRequest;
use App\Http\Requests\PartyInfoStoreRequest;
use App\JobProject;
use App\JobProjectPayment;
use App\JobProjectTask;
use App\Journal;
use App\JournalRecord;
use App\LpoProject;
use App\Models\AccountHead;
use App\PartyInfo;
use App\VatRate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class JobProjectController extends Controller
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
        $projects = JobProject::latest()->paginate(20);

        return view('backend.job-project.index',compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function workStationCreate(LpoProject $lpo_project)
    {
        $customers = PartyInfo::all();
        $vats = VatRate::orderBy('value')->get();
        return view('backend.job-project.create',compact('lpo_project','customers','vats'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JobProjectStoreRequest $request)
    {

        $project_data = $request->only('project_name','project_description','customer_id');
        $date_array = explode('/',$request->start_date);
        $date_string = implode('-',$date_array);
        $date_time = date('Y-m-d',strtotime($date_string));
        $date = \DateTime::createFromFormat('Y-m-d',$date_time);
        $project_data['start_date'] = $date;

        $end_date_array = explode('/',$request->end_date);
        $end_date_string = implode('-',$end_date_array);
        $end_date_time = date('Y-m-d',strtotime($end_date_string));
        $end_date = \DateTime::createFromFormat('Y-m-d',$end_date_time);
        $project_data['end_date'] = $end_date;

        $project = JobProject::create($project_data);

        $project_tasks = $request->task_name;
        $task_description = $request->description;
        $task_budget = $request->budget;
        $task_vat = $request->vat;
        $task_total_budget = $request->total_budget;

        for($i=0;$i<count($project_tasks);$i++){
            $task_data = [
                'job_project_id' => $project->id,
                'task_name' => $project_tasks[$i],
                'description' => $task_description[$i],
                'budget' => $task_budget[$i],
                'vat_id' => $task_vat[$i],
                'total_budget' =>$task_total_budget[$i],
            ];

            JobProjectTask::create($task_data);
        }

        $project->update([
            'project_code' => 'P' . Carbon::now()->format('Ymd') . $project->id,
            'budget' => $project->tasks->sum('budget'),
            'total_budget' => $project->tasks->sum('total_budget'),
            'vat' => $project->tasks->sum('total_budget') - $project->tasks->sum('budget'),
        ]);
        //uninvoiced journal
        $journal_no = $this->journal_no();
        $journal = new Journal();
        $journal->project_id        = 1;
        $journal->job_project_id        = $project->id;
        $journal->transection_type = 'UnInvoiced Journal Entry';
        $journal->transaction_type = 'Increase';
        $journal->journal_no        = $journal_no;
        $journal->date              = $project->start_date;
        $journal->pay_mode          = 'Uninvoiced';
        $journal->cost_center_id    = 0;
        $journal->party_info_id     = $project->customer_id;
        $journal->account_head_id   = 123;
        $journal->voucher_type   = 'DEBIT';
        $journal->amount            = $project->total_budget;
        $journal->tax_rate          = 0;
        $journal->vat_amount        = $project->vat;
        $journal->total_amount      = $project->budget;
        $journal->gst_subtotal = 0;
        $journal->narration         =  'Project Invoice';
        $journal->approved_by = Auth::id();
        $journal->authorized_by         = Auth::id();
        $journal->created_by = Auth::id();
        $journal->save();



        //journal record Receivable entry
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
        $jl_record->amount              = $journal->amount;
        $jl_record->total_amount        = $journal->amount;
        $jl_record->vat_rate_id         = 0;
        $jl_record->invoice_no        = 0;
        $jl_record->transaction_type    = 'DR';
        $jl_record->journal_date        =  $journal->date;
        $jl_record->is_main_head        = 1;
        $jl_record->account_type_id = $ac_head->account_type_id;
        $jl_record->save();
        //end journal record Receivable entry

        //journal record Revenue entry
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
        //end journal record Revenue entry


        //end uninviced
        return redirect()->route('projects.index')->with([
            'alert-type' => 'success',
            'message' =>"Project has been created successfully",
        ]);
    }

    public function addCustomer(PartyInfoStoreRequest $request){
        $latest = PartyInfo::withTrashed()->orderBy('id','DESC')->first();

        if ($latest) {
            $pi_code=preg_replace('/^PI-/', '', $latest->pi_code );
            ++$pi_code;
        } else {
            $pi_code = 1;
        }
        if($pi_code<10)
        {
            $c_code="PI-000".$pi_code;
        }
        elseif($pi_code<100)
        {
            $c_code="PI-00".$pi_code;
        }
        elseif($pi_code<1000)
        {
            $c_code="PI-0".$pi_code;
        }
        else
        {
            $c_code="PI-".$pi_code;
        }
        $data = $request->all();
        $data['pi_code'] = $c_code;

        return PartyInfo::create($data);
    }

    public function show(JobProject $project)
    {
        return view('backend.job-project.view',compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JobProject  $jobProject
     * @return \Illuminate\Http\Response
     */
    public function edit(JobProject $project)
    {
        $customers = PartyInfo::all();
        $vats = VatRate::orderBy('value')->get();
        return view('backend.job-project.edit',compact('project','customers','vats'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JobProject  $jobProject
     * @return \Illuminate\Http\Response
     */
    public function update(JobProjectStoreRequest $request, JobProject $project)
    {
        $project_data = $request->only('project_name','project_description','customer_id');
        $project_data['budget'] = $request->sum_budget;
        $project_data['vat'] = $request->total_vat;
        $project_data['total_budget'] = $request->sum_total_budget;
        $date_array = explode('/',$request->start_date);
        $date_string = implode('-',$date_array);
        $date_time = date('Y-m-d',strtotime($date_string));
        $date = \DateTime::createFromFormat('Y-m-d',$date_time);
        $project_data['start_date'] = $date;

        $end_date_array = explode('/',$request->end_date);
        $end_date_string = implode('-',$end_date_array);
        $end_date_time = date('Y-m-d',strtotime($end_date_string));
        $end_date = \DateTime::createFromFormat('Y-m-d',$end_date_time);
        $project_data['end_date'] = $end_date;

        $project->update($project_data);

        $project_tasks = $request->task_name;
        $task_description = $request->description;
        $task_budget = $request->budget;
        $task_vat = $request->vat;
        $task_total_budget = $request->total_budget;

        foreach($project->tasks as $task){
            $task->delete();
        }

        for($i=0;$i<count($project_tasks);$i++){
            $task_data = [
                'job_project_id' => $project->id,
                'task_name' => $project_tasks[$i],
                'description' => $task_description[$i],
                'budget' => $task_budget[$i],
                'vat_id' => $task_vat[$i],
                'total_budget' =>$task_total_budget[$i],
            ];

            JobProjectTask::create($task_data);
        }


        return redirect()->route('projects.index')->with([
            'alert-type' => 'success',
            'message' =>"Project has been updated successfully",
        ]);
    }


    public function projectDetails(JobProject $job_project){
        $due = $job_project->tasks->sum('budget') - $job_project->payments->sum('payment_amount');
        return ['payment' => $job_project->payments, 'party' => $job_project->party,'total_budget' => $job_project->tasks->sum('budget'),'due' => $due];
    }



    public function projectInvoiceCreate(JobProject $job_project){
        $job_project->update([
            'is_invoice' => 1,
            'due_amount' => $job_project->total_budget,
        ]);


        $journal_no = $this->journal_no();
        $journal = new Journal();
        $journal->project_id        = 1;
        $journal->job_project_id        = $job_project->id;
        $journal->transection_type = 'Job Entry';
        $journal->transaction_type = 'Increase';
        $journal->journal_no        = $journal_no;
        $journal->date              = $job_project->start_date;
        $journal->pay_mode          = 'Credit';
        $journal->cost_center_id    = 0;
        $journal->party_info_id     = $job_project->customer_id;
        $journal->account_head_id   = 123;
        $journal->voucher_type   = 'CREDIT';
        $journal->amount            = $job_project->total_budget;
        $journal->tax_rate          = 0;
        $journal->vat_amount        = $job_project->vat;
        $journal->total_amount      = $job_project->budget;
        $journal->gst_subtotal = 0;
        $journal->narration         =  'Project Invoice';
        $journal->approved_by = Auth::id();
        $journal->authorized_by         = Auth::id();
        $journal->created_by = Auth::id();
        $journal->save();


        //journal record Receivable entry
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
        //end journal record Receivable entry

        //journal record Revenue entry
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
        //end journal record Revenue entry

        if($journal->vat_amount>0)
         {
            //journal record vat entry
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
            //end journal record vat entry
         }



        return redirect()->route('project.invoice.index')->with(['alert-type' => 'success','message' => 'Invoice has been created uccessfully ']);

    }

    public function projectInvoice(){
        $invoices = JobProject::where('is_invoice',1)->latest()->paginate(20);
        return view('backend.job-project.invoices',compact('invoices'));
    }


    public function getVat(){
        return VatRate::orderBy('value')->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JobProject  $jobProject
     * @return \Illuminate\Http\Response
     */
    public function destroy(JobProject $jobProject)
    {
        //
    }
}

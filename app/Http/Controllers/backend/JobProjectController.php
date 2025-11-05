<?php

namespace App\Http\Controllers\backend;

use App\BillOfQuantityItem;
use App\BillOfQuantityTask;
use App\GnattChart;
use App\GnattChartItem;
use App\Http\Controllers\Controller;
use App\Http\Requests\JobProjectStoreRequest;
use App\Http\Requests\PartyInfoStoreRequest;
use App\Invoice;
use App\JobDocumentUpload;
use App\JobProject;
use App\JobProjectInvoice;
use App\JobProjectInvoiceTask;
use App\JobProjectTask;
use App\JobProjectTaskItem;
use App\Journal;
use App\JournalRecord;
use App\LpoPorjectTask;
use App\Models\AccountHead;
use App\PartyInfo;
use App\LpoProject;
use App\Models\InvoiceNumber;
use App\Models\Payroll\Employee;
use App\Models\Payroll\EmployeeTemp;
use App\Receipt;
use App\VatRate;
use App\PayMode;
use App\ReceiptSale;
use App\PurchaseExpense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\NewProject;
use App\NewProjectTask;
use App\Payment;
use App\PaymentInvoice;
use App\ProjectDocument;
use App\Sale;
use App\Subsidiary;
use App\Traits\ProjectCostTrait;
use Illuminate\Support\Str;

class JobProjectController extends Controller
{
    use ProjectCostTrait;

    private function dateFormat($date)
    {
        $old_date = explode('/', $date);

        $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }
    private function payment_no()
    {
        $sub_invoice = 'RV' . Carbon::now()->format('y');
        $let_purch_exp = InvoiceNumber::where('receipt_invoice_number', 'LIKE', "%{$sub_invoice}%")->first();
        if ($let_purch_exp) {
            $receipt_no = preg_replace('/^' . $sub_invoice . '/', '', $let_purch_exp->receipt_invoice_number);
            $receipt_no++;
            if ($receipt_no < 10) {
                $receipt_no = $sub_invoice . '000' . $receipt_no;
            } elseif ($receipt_no < 100) {
                $receipt_no = $sub_invoice . '00' . $receipt_no;
            } elseif ($receipt_no < 1000) {
                $receipt_no = $sub_invoice . '0' . $receipt_no;
            } else {
                $receipt_no = $sub_invoice . $receipt_no;
            }
        } else {
            $receipt_no = $sub_invoice . '0001';
        }
        return $receipt_no;
    }

    private function invoice_no()
    {
        $sub_invoice = 'INV' . Carbon::now()->format('y');
        $invoice = InvoiceNumber::where('invoice_no', 'LIKE', "%{$sub_invoice}%")->first();
        if ($invoice) {
            $number = preg_replace('/^' . $sub_invoice . '/', '', $invoice->invoice_no);
            $number++;
            if ($number < 10) {
                $invoice_no = $sub_invoice . '000' . $number;
            } elseif ($number < 100) {
                $invoice_no = $sub_invoice . '00' . $number;
            } elseif ($number < 1000) {
                $invoice_no = $sub_invoice . '0' . $number;
            } else {
                $invoice_no = $sub_invoice . $number;
            }
        } else {
            $invoice_no  = $sub_invoice . '0001';
        }
        return $invoice_no;
    }
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
    public function index(Request $request)
    {
        $company_id = $request->company_id === 'seabridge'
            ? null
            : $request->company_id;

        $subsidiarys = Subsidiary::all();
        $active_btn = 'all';

        // Start query
        $query = JobProject::where('compnay_id', $company_id)
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = trim($request->search);

                $q->where(function ($subQ) use ($search) {
                    $subQ->where('project_name', 'LIKE', "%{$search}%")
                        ->orWhereHas('new_project', function ($newQ) use ($search) {
                            $newQ->where('project_no','LIKE', "%{$search}%"); // exact match in new_projects table
                        });
                });
            });

        $ongoingFilter = function ($q) {
            $q->where('avarage_complete', '<', 100)
                ->orWhereNull('avarage_complete');
        };

        // Clone for calculations
        $cal_projects = clone $query;

        $ongoingProjects = (clone $cal_projects)->where($ongoingFilter);
        $total_ongoing_project = $ongoingProjects->count();
        $total_vat             = $ongoingProjects->sum('vat');
        $total_budget          = $ongoingProjects->sum('budget');
        $total_contact_amount  = $ongoingProjects->sum('total_budget');
        // Paginate results
        $projects = $query->orderBy('id', 'desc')->paginate(20);

        return view('backend.job-project.index', compact('projects', 'active_btn', 'subsidiarys', 'company_id', 'total_ongoing_project', 'total_vat', 'total_budget', 'total_contact_amount'));
    }

    public function home_project(Request $request)
    {
        $company_id = $request->company_id;
        $subsidiarys = Subsidiary::get();
        $active_btn = 'all';

        $projects = JobProject::query();

        // Apply company_id logic
        if ($company_id === 'seabridge') {
            $projects->whereNull('compnay_id');
        } elseif (!empty($company_id) && $company_id != 'seabridge') {
            $projects->where('compnay_id', $company_id);
        }

        // Apply filtering based on search or invoice status
        if ($request->has('search')) {
            $query = $request->search;
            $projects->where(function ($q) use ($query) {
                $q->where('project_name', 'like', '%' . $query . '%')
                    ->orWhere('project_code', 'like', '%' . $query . '%');
            });
            $active_btn = 'all';
        } elseif ($request->invoice_item === 'invoice') {
            $projects->where('is_invoice', '>', 0);
            $active_btn = 'invoice-item';
        } else {
            $projects->where('is_invoice', 0);
            $active_btn = 'uninvoice-item';
        }

        // Clone query before pagination for calculations
        $cal_projects = (clone $projects);

        // Paginate results
        $projects = $projects->latest()->paginate(20);

        // Calculate totals where avarage_complete < 100 or avarage_complete is null
        $total_ongoing_project = $cal_projects->where(function ($q) {
            $q->where('avarage_complete', '<', 100)
                ->orWhereNull('avarage_complete');
        })->count();


        $total_contact_amount = $cal_projects->where(function ($q) {
            $q->where('avarage_complete', '<', 100)
                ->orWhereNull('avarage_complete');
        })->sum('total_budget');

        $total_budget = $cal_projects->sum('total_budget');
        return view('backend.job-project.home-project-view', compact('projects', 'active_btn', 'subsidiarys', 'company_id', 'total_ongoing_project', 'total_budget', 'total_contact_amount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function workStationCreate(LpoProject $lpo_project)
    {
        // dd($lpo_project);
        $paymodes = PayMode::whereIn('id', [1, 3, 4])->get();
        $customers = PartyInfo::where('pi_type', 'Customer')->get();
        $vats = VatRate::orderBy('value', 'desc')->get();
        $selected_project_id = $lpo_project->boq->project_id;
        $new_projects = NewProject::all();
        $tasks = NewProjectTask::with('items')->orderBy('name')->get();
        $subsidiarys = Subsidiary::get();
        return view('backend.job-project.create', compact('lpo_project', 'customers', 'vats', 'paymodes', 'new_projects', 'selected_project_id', 'tasks', 'subsidiarys'));
    }

    public function ajaxCreate()
    {
        $lpo_projects = LpoProject::latest()->get();
        $customers = PartyInfo::all();
        $vats = VatRate::orderBy('value')->get();
        return view('backend.job-project.ajax-create', compact('customers', 'vats', 'lpo_projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'party_info' => 'required',
            'project_name' => 'required',
            'project_no' => 'required',
        ]);
        $sub_invoice = 'WO' . Carbon::now()->format('y');
        $tem_project_code = JobProject::where('project_code', 'LIKE', "%{$sub_invoice}%")->orderBy('id', 'DESC')->first();
        if ($tem_project_code) {
            $cc =  preg_replace('/^' . $sub_invoice . '/', '', $tem_project_code->project_code) + 1;
            if ($cc < 10) {
                $cc = $sub_invoice . '000' . $cc;
            } elseif ($cc < 100) {
                $cc = $sub_invoice . '00' . $cc;
            } elseif ($cc < 1000) {
                $cc = $sub_invoice . '0' . $cc;
            } else {
                $cc = $sub_invoice . $cc;
            }
        } else {
            $cc = $sub_invoice . '0001';
        }
        // dd($request->all());
        
        $project = new JobProject;
        $project->fill([
            'date'=>$this->dateFormat($request->date),
            'customer_id'=>$request->party_info,
            'project_name'=>$request->project_name,
            'project_code'=>$cc,
            'project_no'=>$request->project_no,
            'lpo_projects_id'=>$request->lpo_projects_id,
            'address'=>$request->address,
            'project_description'=>$request->project_description,
            'project_term'=>$request->project_term,
            'site_delivery'=>$request->site_delivery,
            'attention'=>$request->attention,
            'lpo_no'=>$request->lpo_no,
            'do_no'=>$request->do_no,
            'mobile_no'=>$request->mobile_no,
            'start_date'=>$request->start_date?$this->dateFormat($request->start_date):null,
            'end_date'=>$request->end_date?$this->dateFormat($request->end_date):null,
            'budget'=>$request->taxable_amount,
            'vat'=>$request->total_vat,
            'total_budget'=>$request->total_amount,
        ]);
        // dd($project);
        $project->save();
        $multi_head = $request->input('group-a');
        $items=[];
        foreach ($multi_head as $key => $each_item) {
            $items[]=[
                'job_project_id'=>$project->id,
                'task_name'=>$each_item['description'],
                'qty'=>$each_item['qty'],
                'sqm'=>$each_item['sqm'],
                'rate'=>$each_item['amount'],
                'total'=>$each_item['sub_gross_amount'],
            ];
        }
        JobProjectTask::insert($items);
        if($request->lpo_projects_id){
            return redirect()->route('lpo-projects.index')->with([
                'alert-type' => 'success',
                'message' => "Work Order Create successfully",
            ]);
        }else{
            return response()->json([
                'preview' =>  view('backend.job-project.view', compact('project'))->render(),
            ]);
        }
    }


    public function addCustomer(PartyInfoStoreRequest $request)
    {
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
        $data = $request->all();
        $data['pi_code'] = $c_code;

        return PartyInfo::create($data);
    }

    public function show(JobProject $project)
    {
        return view('backend.job-project.view', compact('project'));
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
        return view('backend.job-project.edit', compact('project', 'customers', 'vats'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JobProject  $jobProject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required',
            'party_info' => 'required',
            'project_name' => 'required',
            'project_no' => 'required',
        ]);

        $project = JobProject::find($id);
        $project->fill([
            'date'=>$this->dateFormat($request->date),
            'customer_id'=>$request->party_info,
            'project_name'=>$request->project_name,
            'project_no'=>$request->project_no,
            'address'=>$request->address,
            'start_date'=>$request->start_date?$this->dateFormat($request->start_date):null,
            'end_date'=>$request->end_date?$this->dateFormat($request->end_date):null,
            'budget'=>$request->taxable_amount,
            'vat'=>$request->total_vat,
            'total_budget'=>$request->total_amount,
        ]);
        $project->save();
        JobProjectTask::where('job_project_id', $project->id)->delete();
        $multi_head = $request->input('group-a');
        $items=[];
        foreach ($multi_head as $key => $each_item) {
            $items[]=[
                'job_project_id'=>$project->id,
                'task_name'=>$each_item['description'],
                'qty'=>$each_item['qty'],
                'sqm'=>$each_item['sqm'],
                'rate'=>$each_item['amount'],
                'total'=>$each_item['sub_gross_amount'],
            ];
        }
        JobProjectTask::insert($items);

        return redirect()->route('projects.index')->with([
            'alert-type' => 'success',
            'message' => "Project has been updated successfully",
        ]);
    }


    public function projectDetails(JobProject $job_project)
    {
        $due = $job_project->tasks->sum('budget') - $job_project->payments->sum('payment_amount');
        return ['payment' => $job_project->payments, 'party' => $job_project->party, 'total_budget' => $job_project->tasks->sum('budget'), 'due' => $due];
    }



    public function projectInvoiceCreate(JobProject $job_project)
    {
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

        return redirect()->route('project.invoice.index')->with(['alert-type' => 'success', 'message' => 'Invoice has been created uccessfully ']);
    }

    public function projectInvoice()
    {
        $invoices = JobProject::where('is_invoice', 1)->latest()->paginate(20);
        return view('backend.job-project.invoices', compact('invoices'));
    }


    public function getVat()
    {
        return VatRate::orderBy('value', 'desc')->get();
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

    public function tracking($id)
    {
        $employees = EmployeeTemp::OrderBy('full_name')->get();
        $customers = PartyInfo::all();
        $project = GnattChart::where('job_project_id', $id)->first();
        $vats = VatRate::orderBy('value')->get();
        return view('backend.job-project.track', compact('project', 'customers', 'vats', 'employees'));
    }

    public function project_update(Request $request)
    {
        // ✅ Validate request
        $validated = $request->validate([
            'project_id' => 'required|exists:job_projects,id',
            'status' => 'required|in:Planned,In Progress,Completed,Hold On,Handover',
        ]);

        // ✅ Find project
        $project = JobProject::findOrFail($validated['project_id']);

        // ✅ Update project status
        $project->status = $validated['status'];

        if ($validated['status'] === 'Completed') {
            $project->avarage_complete = 100;
        }

        if ($validated['status'] === 'Handover') {
            $project->avarage_complete = 100;
            $project->handover_date = now();
        }

        $project->save();

        return redirect()->back()->with([
            'alert-type' => 'success',
            'message' => "Project has been updated successfully",
        ]);
    }


    public function traking_store(Request $request)
    {
        $chart = GnattChart::find($request->project_id);

        $taskIds = $request->task_id;
        $completed = $request->completed;

        foreach ($taskIds as $index => $taskId) {
            $task = GnattChartItem::find($taskId);
            $task->start_date = $this->dateFormat($request->start_time[$index]);
            $task->end_date = $this->dateFormat($request->end_time[$index]);
            $task->progress = $completed[$index];
            $task->save();
        }

        if ($chart->job_project_id) {
            $projectId = $chart->job_project_id;
            $averageCompletion = array_sum($completed) / count($completed);

            $project = JobProject::find($projectId);
            $project->avarage_complete = $averageCompletion;
            $project->save();
        }

        foreach ($chart->items as $item) {
            $task = JobProjectTask::find($item->project_task_id);
            if ($task) {
                $task->start_date = $item->start_date;
                $task->end_date = $item->end_date;
                $task->completed = $item->progress;
                $task->save();
            }
        }

        if ($request->hasFile('voucher_file')) {
            $files = $request->file('voucher_file');
            $this->uploadFile($files, $chart);
        }
        if ($request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => "Gantt chart has been track successfully",
                'project_id' => $chart->id,
            ]);
        }

        return redirect()->route('gnatt.chart.index')->with([
            'alert-type' => 'success',
            'message' => "Project has been updated successfully",
        ]);
    }

    private function uploadFile($files, $chart)
    {
        if ($chart->job_project_id) {
            foreach ($files as $file) {
                $name = $file->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext = $file->getClientOriginalExtension();
                $project_doc_name = $name . time() . '.' . $ext;
                $file->storeAs('public/upload/project-document', $project_doc_name);
                ProjectDocument::create([
                    'project_id' =>  $chart->project_id,
                    'file_name' => $project_doc_name,
                    'ext' => $ext,
                    // 'name' => $name,
                    'job_project_id' => $chart->job_project_id,
                ]);
            }
            return true;
        } else {
            return false;
        }
    }

    public function job_document_view(Request $request)
    {
        $project = JobProject::find($request->project_id);
        $documents = ProjectDocument::where('job_project_id', $project->id)->get();
        return view('backend.job-project.job-document-view', compact('project', 'documents'));
    }

    public function job_document_update(Request $request)
    {
        if ($request->file('files')) {
            foreach ($request->file('files') as $file) {
                $name = $file->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext = $file->getClientOriginalExtension();
                $project_doc_name = $name . time() . '.' . $ext;
                $file->storeAs('public/upload/project-document', $project_doc_name);
                ProjectDocument::create([
                    'project_id' =>  $request->project_id,
                    'file_name' => $project_doc_name,
                    'ext' => $ext,
                    'name' => $name,
                    'job_project_id' => $request->job_project_id,
                ]);
            }
        }

        $notification = array(
            'message'       => 'Document saved successfully!',
            'alert-type'    => 'success'
        );

        return back()->with($notification);
    }
    public function delete_job_document(Request $request)
    {
        $record = ProjectDocument::find($request->id);
        $record->delete();
        return true;
    }

    // public function find_project_task(Request $request)
    // {
    //     // return $request->all();
    //     $tasks = JobProjectTask::where('job_project_id', $request->project)->get();
    //     return view('backend.job-project.find-job-task', compact('tasks'));
    // }

    public function projectReport(Request $request)
    {
        $customers = PartyInfo::whereHas('projects')->get();
        $projects = NewProject::orderBy('name')->get();
        $project_id = $request->project_id;
        $date_from = $request->date_from ? $this->dateFormat($request->date_from) : null;
        $date_to = $request->date_to ? $this->dateFormat($request->date_to) : null;
        // $lpo_project = LpoProject::pluck('project_name')->unique()->values();
        // foreach($lpo_project as $item){
        //     if($item){
        //         $new_project = new NewProject;
        //         $new_project->name = $item;
        //         $new_project->save();
        //         LpoProject::where('project_name', $item)->update(['project_id'=> $new_project->id]);
        //         JobProject::where('project_name', $item)->update(['project_id'=> $new_project->id]);
        //         JobProjectInvoice::where('project_name', $item)->update(['project_id'=> $new_project->id]);
        //     }
        // }
        if ($project_id) {
            $project = JobProject::where('lpo_projects_id', $project_id);
            $customer = PartyInfo::with(['projects' => function ($e) use ($project_id) {
                $e->where('lpo_projects_id', $project_id);
            }])->find($request->customer_id);

            $projects = JobProject::join('new_projects', 'new_projects.id', '=', 'job_projects.project_id')->where('customer_id', $request->customer_id)
                ->get()->unique('project_id')->values();
            // dd($projects);
        } else {
            $customer = PartyInfo::with(['projects' => function ($e) {
                $e->orderBy('project_name');
                $e->latest()->take(10)->get();
            }])->find($request->customer_id);
            $projects = JobProject::join('new_projects', 'new_projects.id', '=', 'job_projects.project_id')->where('customer_id', $request->customer_id)
                ->get()->unique('project_id')->values();
        }

        $project_map = [];

        // dd($projects->where('project_id',2));
        if ($customer && $customer->projects) {
            foreach ($customer->projects as $item) {
                if (array_key_exists($item->lpo_projects_id, $project_map)) {
                    $project_map[$item->lpo_projects_id]['invoices'][] = $item;
                } else {
                    $project_map[$item->lpo_projects_id]['details'] = $item;
                    $project_map[$item->lpo_projects_id]['invoices'][] = $item;
                }
            }
        }
        // dd($customer);

        return view('backend.job-project.report', compact('project_map', 'customer', 'customers', 'projects', 'project_id', 'date_to', 'date_from'));
    }
    public function projectReport2(Request $request)
    {
        $customers = PartyInfo::whereHas('projects')->get();
        $projects = JobProject::orderBy('project_name')->get();
        $project_id = $request->project_id;

        if ($project_id) {
            $project = JobProject::where('lpo_projects_id', $project_id);
            $customer = PartyInfo::with(['projects' => function ($e) use ($project_id) {
                $e->where('lpo_projects_id', $project_id);
            }])->find($request->customer_id);

            $projects = JobProject::where('customer_id', $request->customer_id)->get()->unique('lpo_projects_id');
        } else {
            $customer = PartyInfo::with(['projects' => function ($e) {
                $e->orderBy('project_name');
                $e->latest()->take(10)->get();
            }])->find($request->customer_id);
            $projects = JobProject::where('customer_id', $request->customer_id)->get()->unique('lpo_projects_id');
        }
        $project_map = [];
        // dd($projects);
        if ($customer && $customer->projects) {
            foreach ($customer->projects as $item) {
                if (array_key_exists($item->lpo_projects_id, $project_map)) {
                    $project_map[$item->lpo_projects_id]['invoices'][] = $item;
                } else {
                    $project_map[$item->lpo_projects_id]['details'] = $item;
                    $project_map[$item->lpo_projects_id]['invoices'][] = $item;
                }
            }
        }

        return view('backend.job-project.report2', compact('project_map', 'customer', 'customers', 'projects', 'project_id'));
    }
    public function customerProject($id)
    {
        $list = JobProject::join('new_projects', 'new_projects.id', '=', 'job_projects.project_id')->where('customer_id', $id)
            ->get()->unique('project_id')->values();
        return $list;
    }


    public function roiReport(Request $request, $project_id)
    {

        $project_id = $request->project_id;

        $project = JobProject::with('tasks.items')->find($request->project_id);

        if (!$project) {
            return response()->json(['error' => "The project doesn't have any work order!"], 401);
        }

        if (!$project || $project->tasks->count() <= 0) {
            return response()->json(['error' => 'The project task is missing'], 401);
        }

        $project_start_date = $project->start_date;
        $project_end_date = $project->end_date;
        $working_progress = $project->tasks->sum('completed') / $project->tasks->count();

        foreach ($project->tasks as $task) {
            if (is_null($project_start_date) || $task->start_date < $project_start_date) {
                if ($task->start_date) {
                    $project_start_date = $task->start_date;
                }
            }
            if (is_null($project_end_date) || $task->end_date > $project_end_date) {
                $project_end_date = $task->end_date;
            }
        }

        $estimate_progress = null;
        $start = Carbon::parse($project_start_date);
        $end = Carbon::parse($project_end_date);
        $now = Carbon::now();

        if ($now->lt($start)) {
            $progress = 0;
        } elseif ($now->gt($end)) {
            $progress = 100;
        } else {
            $totalDuration = $start->diffInSeconds($end);
            $elapsed = $start->diffInSeconds($now);
            $progress = ($elapsed / $totalDuration) * 100;
        }

        $estimate_progress = round($progress, 2);

        $this_proj = JobProject::find($request->project_id);
        $invoice_id = $project->invoices->pluck('id')->all();

        $receipt_sales = ReceiptSale::whereIn('sale_id', $invoice_id)->get();

        $receiveds = [
            'received' => [],
            'total' => 0
        ];

        foreach ($receipt_sales as $sale) {
            $receipt = Receipt::find($sale->payment_id);
            $invoice = JobProjectInvoice::find($sale->sale_id);

            // Make sure both are found
            if (!$receipt || !$invoice) continue;

            $receiveds['received'][] = [
                'receipt_no' => $receipt->receipt_no,
                'date' => date('d/m/Y', strtotime($receipt->date)),
                'invoice_no' => $invoice->invoice_no,
                'amount' => (float) $receipt->total_amount,
            ];

            $receiveds['total'] += (float) $receipt->total_amount;
        }

        // $receivables = $project->invoices()
        // ->whereHas('tasks', function ($q) {
        //     $q->whereNotNull('task_id')
        //     ->where('due_amount', '>', 0);
        // })
        // ->with(['tasks' => function ($q) {
        //     $q->whereNotNull('task_id')
        //     ->where('due_amount', '>', 0);
        // }])
        // ->get();

        $receivables = $project->invoices()->where('due_amount', '>', 0)->get();

        $payments = [];

        foreach ($project->purchase_expense as $expense) {
            $purchase_expense = PurchaseExpense::find($expense->purchase_expense_id);

            if (array_key_exists($purchase_expense->purchase_no, $payments)) {
                $payments[$purchase_expense->purchase_no]['amount'] += $expense->amount;
                $payments[$purchase_expense->purchase_no]['due_amount'] += $expense->due_amount;
                $payments[$purchase_expense->purchase_no]['paid_amount'] += $expense->paid_amount;
            } else {
                $payments[$purchase_expense->purchase_no] = [
                    'party_name' => $purchase_expense->party ? $purchase_expense->party->pi_name : 'N/A',
                    'invoice_no' => $purchase_expense->invoice_no,
                    'date' => date('d/m/Y', strtotime($purchase_expense->date)),
                    'amount' => $expense->total_amount,
                    'due_amount' => $expense->due_amount,
                    'paid_amount' => $expense->paid_amount,
                ];
            }
        }

        $office_id = auth()->user()->office_id;

        $masters = JournalRecord::where('project_id', $this_proj->id)
            ->where(function ($q) {
                $q->where(function ($q1) {
                    $q1->where('account_type_id', 3)
                        ->where('transaction_type', 'CR');
                })
                    ->orWhere(function ($q2) {
                        $q2->where('account_type_id', 4)
                            ->where('transaction_type', 'DR');
                    });
            })
            ->select('account_head_id')
            ->distinct()
            ->get();

        $administrative_cost = $this->administrativeCost($project->id)['total_cost'];
        $matarial_cost = $this->matarialCost($project->id)['total_cost'];
        $labour_cost = $this->LabourCost($project->id)['total_cost'];

        $income_statements = $this->incomeStateMent($project->id);

        if ($request->print) {
            return view('backend.job-project.roi-report-print', compact(
                'project',
                'receiveds',
                'receivables',
                'working_progress',
                'project_start_date',
                'project_end_date',
                'estimate_progress',
                'payments',
                'administrative_cost',
                'matarial_cost',
                'labour_cost',
                'income_statements',
            ));
        }

        return view('backend.job-project.roi-report', compact(
            'project',
            'receiveds',
            'receivables',
            'payments',
            'working_progress',
            'project_start_date',
            'project_end_date',
            'estimate_progress',
            'administrative_cost',
            'matarial_cost',
            'labour_cost',
            'income_statements'
        ));
    }


    public function roiReportChart($id)
    {
        $project = JobProject::find($id);
        $data = [
            'labels' => [],
            'value' => [
                'label_1' => 'Estimated Expense',
                'label_2' => 'Expense',
                'label_3' => 'Payment',
                'label_4' => 'Payable',
                'label_5' => 'Estimated Revenue',
                'label_6' => 'Revenue',
                'label_7' => 'Receipt',
                'label_8' => 'Receivable',

                'data_1' => [],
                'data_2' => [],
                'data_3' => [],
                'data_4' => [],
                'data_5' => [],
                'data_6' => [],
                'data_7' => [],
                'data_8' => [],
            ],
        ];

        foreach ($project->tasks as $task) {
            $fullName = $task->task_name;
            $shortName = Str::limit($fullName, 10);

            $data['labels'][] = [
                'full' => $fullName,
                'short' => $shortName,
            ];

            $data['value']['data_1'][] = $task->estimated_expense;
            $data['value']['data_2'][] = $task->expense;
            $data['value']['data_3'][] = $task->payment;
            $data['value']['data_4'][] = $task->payable;
            $data['value']['data_5'][] = $task->contact_amount;
            $data['value']['data_6'][] = $task->revenue;
            $data['value']['data_7'][] = $task->receipt;
            $data['value']['data_8'][] = $task->receivable;
        }

        return response()->json($data);
    }



    public function job_project_print($id)
    {
        $project = JobProject::find($id);
        return view('backend.job-project.print', compact('project'));
    }
    public function partyProjects(PartyInfo $party, Request $request)
    {
        $party_type = $request->party_type;
        if ($party_type == "Supplier") {
            $purchases = PurchaseExpense::join('bill_distributes')->with('bill_distribute.job_project')->where('party_id', $party->id)->get();
            return response()->json($purchases);
        }

        return response()->json($party->projects);
    }

    public function find_project_task(Request $request)
    {
        $tasks = JobProjectTask::where('job_project_id', $request->project)->get();
        return view('backend.job-project.find-job-task', compact('tasks'));
    }

    public function find_project_task_item(Request $request)
    {
        $tasks = JobProjectTaskItem::where('task_id', $request->task_id)->get();
        return view('backend.job-project.find-job-task-item', compact('tasks'));
    }

    public function adjustProject()
    {
        $sales = JobProjectInvoice::all();

        foreach ($sales as $sale) {
            $party_id = $sale->customer_id;
            $job_project = JobProject::where('customer_id', $party_id)->first();
            $job_project->retention_amount = $job_project->retention_amount + $sale->retention_amount;
            $job_project->save();
        }
    }
    public function new_project_create(Request $request){
        $party = PartyInfo::where('pi_type', 'Customer')->get();
        return view('backend.job-project.project-create', compact('party'));
    }
    public function new_project_edit(Request $request){
        $project=JobProject::find($request->id);
        $party = PartyInfo::where('pi_type', 'Customer')->get();
        return view('backend.job-project.project-edit', compact('party', 'project'));
    }
}

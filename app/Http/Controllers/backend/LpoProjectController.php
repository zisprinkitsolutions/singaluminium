<?php

namespace App\Http\Controllers\backend;

use App\BillOfQuantity;
use App\BillOfQuantityTask;
use App\Http\Controllers\Controller;
use App\Http\Requests\JobProjectStoreRequest;
use App\LpoPorjectTask;
use App\LpoProject;
use App\LpoProjectTaskItem;
use App\PartyInfo;
use App\VatRate;
use App\NewProject;
use App\NewProjectTask;
use App\Subsidiary;
use App\JobProject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class LpoProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Gate::authorize('Quotation');
        $active_btn = 'new';
        $quotation = $request->quotation;
        $search = $request->search;
        $company_id = $request->company_id;

        $projects = LpoProject::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('project_name', 'like', '%' . $search . '%')
                        ->orWhere('project_code', 'like', '%' . $search . '%')
                        ->orWhereHas('boq.project', function ($subQ) use ($search) {
                            $subQ->where('project_no', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when($company_id == 'seabridge', function ($q) {
                $q->whereNull('company_id');
            })
            ->when($company_id && $company_id != 'seabridge', function ($q) use ($company_id) {
                $q->where('company_id', $company_id);
            })
            ->orderBy('id', 'desc');
        // ->paginate(20);
        $cal_projects = (clone $projects);
        $projects = $projects->paginate(20);

        $data = [];
        $data['total_budget'] = $cal_projects->sum('total_budget');

        $subsidiarys = Subsidiary::get();


        return view('backend.lpo-project.index', compact('projects', 'active_btn', 'search', 'company_id', 'subsidiarys', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('Quotation');
        $project = new LpoProject();
        $new_projects = NewProject::all();
        $sub_invoice = 'QTO' . Carbon::now()->format('Ymd');
        $tem_project_code = LpoProject::where('project_code', 'LIKE', "%{$sub_invoice}%")->orderBy('id', 'DESC')->first();
        if ($tem_project_code) {
            $cc = preg_replace('/^' . $sub_invoice . '/', '', $tem_project_code->project_code);
            $cc++;
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

        $tasks = NewProjectTask::with('items')->orderBy('name')->get();
        $customers = PartyInfo::where('pi_type', 'Customer')->get();
        $vats = VatRate::orderBy('value', 'desc')->get();

        return view('backend.lpo-project.create', compact('customers', 'project', 'vats', 'cc', 'new_projects', 'tasks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JobProjectStoreRequest $request)
    {
        // dd($request->all());
        Gate::authorize('ProjectManagement_Create');
        $boq = BillOfQuantity::find($request->project_id);
        // dd($boq);
        $project_data = $request->only('project_id', 'project_description', 'customer_id', 'project_term', 'attention', 'mobile_no');

        $sub_invoice = 'QTO' . Carbon::now()->format('y');

        $tem_project_code = LpoProject::where('project_code', 'LIKE', "%{$sub_invoice}%")->orderBy('id', 'DESC')->first();
        if ($tem_project_code) {
            $cc = preg_replace('/^' . $sub_invoice . '/', '', $tem_project_code->project_code) + 1;

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
            $cc = 'QTO' . Carbon::now()->format('y') . '0001';
        }
        if ($request->start_date) {
            $date_array = explode('/', $request->start_date);
            $date_string = implode('-', $date_array);
            $date_time = date('Y-m-d', strtotime($date_string));
            $date = \DateTime::createFromFormat('Y-m-d', $date_time);
            $project_data['start_date'] = $date;
        }
        if ($request->start_date) {
            $end_date_array = explode('/', $request->end_date);
            $end_date_string = implode('-', $end_date_array);
            $end_date_time = date('Y-m-d', strtotime($end_date_string));
            $end_date = \DateTime::createFromFormat('Y-m-d', $end_date_time);
            $project_data['end_date'] = $end_date;
        }
        $voucher_file_name = '';
        $ext = '';
        if ($request->hasFile('voucher_file')) {
            $voucher_scan = $request->file('voucher_file');
            $name = $voucher_scan->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $voucher_scan->getClientOriginalExtension();
            $voucher_file_name = $name . time() . '.' . $ext;
            $voucher_scan->storeAs('public/upload/documents', $voucher_file_name);
        }
        $project_data['voucher_file'] = $voucher_file_name;
        $project_data['extension'] = $ext;
        $project_data['budget'] = $request->taxable_amount;
        $project_data['total_budget'] = $request->total_amount;
        $project_data['discount'] = $request->discount;
        $project_data['vat'] = $request->total_vat;
        $project_data['project_code'] = $cc;
        $project_data['site_delivery'] = $request->site_delivery;
        $project_data['company_id'] = $boq->compnay_id;
        $project = LpoProject::create($project_data);

        $multi_head = $request->input('group-a');
        $items=[];
        foreach ($multi_head as $key => $each_item) {
            $items[]=[
                'lpo_id'=>$project->id,
                'item_description'=>$each_item['description'],
                'qty'=>$each_item['qty'],
                'sqm'=>$each_item['sqm'],
                'rate'=>$each_item['amount'],
                'total'=>$each_item['sub_gross_amount'],
            ];
        }
        LpoProjectTaskItem::insert($items);
        return back()->with([
            'alert-type' => 'success',
            'message' => "Project has been created successfully",
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Lpoproject  $lpoproject
     * @return \Illuminate\Http\Response
     */
    public function show(LpoProject $lpo_project)
    {
        return view('backend.lpo-project.view', compact('lpo_project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lpoproject  $lpoproject
     * @return \Illuminate\Http\Response
     */
    public function edit(LpoProject $lpo_project)
    {
        Gate::authorize('ProjectManagement_Edit');

        $customers = PartyInfo::where('pi_type', 'Customer')->get();
        $tasks = NewProjectTask::with('items')->orderBy('name')->get();
        $new_projects = JobProject::all();
        return view('backend.lpo-project.edit', compact('lpo_project', 'customers', 'new_projects', 'tasks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lpoproject  $lpoproject
     * @return \Illuminate\Http\Response
     */
    public function update(JobProjectStoreRequest $request, LpoProject $lpo_project)
    {
        Gate::authorize('ProjectManagement_Edit');


        $project_data = $request->only('project_id', 'project_description', 'customer_id', 'project_term', 'attention', 'mobile_no');

        if ($request->start_date) {
            $date_array = explode('/', $request->start_date);
            $date_string = implode('-', $date_array);
            $date_time = date('Y-m-d', strtotime($date_string));
            $date = \DateTime::createFromFormat('Y-m-d', $date_time);
            $project_data['start_date'] = $date;
        } else {
            $project_data['start_date'] = Null;
        }
        if ($request->start_date) {
            $end_date_array = explode('/', $request->end_date);
            $end_date_string = implode('-', $end_date_array);
            $end_date_time = date('Y-m-d', strtotime($end_date_string));
            $end_date = \DateTime::createFromFormat('Y-m-d', $end_date_time);
            $project_data['end_date'] = $end_date;
        } else {
            $project_data['end_date'] = Null;
        }

        $project_data['budget'] = $request->taxable_amount;
        $project_data['total_budget'] = $request->total_amount;
        $project_data['discount'] = $request->discount;
        $project_data['site_delivery'] = $request->site_delivery;
        $project_data['project_id'] = $request->project_id;

        $voucher_file_name = $lpo_project->voucher_file;
        $ext = $lpo_project->extension;

        if ($request->hasFile('voucher_file')) {
            if (Storage::exists('public/upload/documents/' . $lpo_project->voucher_file)) {
                Storage::delete('public/upload/documents/' . $lpo_project->voucher_file);
            }
            $voucher_scan = $request->file('voucher_file');
            $name = $voucher_scan->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $voucher_scan->getClientOriginalExtension();
            $voucher_file_name = $name . time() . '.' . $ext;
            $voucher_scan->storeAs('public/upload/documents', $voucher_file_name);
        }

        $project_data['voucher_file'] = $voucher_file_name;
        $project_data['extension'] = $ext;
        $lpo_project->update($project_data);

        LpoProjectTaskItem::where('lpo_id',$lpo_project->id)->delete();
        $multi_head = $request->input('group-a');
        $items=[];
        foreach ($multi_head as $key => $each_item) {
            $items[]=[
                'lpo_id'=>$lpo_project->id,
                'item_description'=>$each_item['description'],
                'qty'=>$each_item['qty'],
                'sqm'=>$each_item['sqm'],
                'rate'=>$each_item['amount'],
                'total'=>$each_item['sub_gross_amount'],
            ];
        }
        LpoProjectTaskItem::insert($items);

        return redirect()->route('lpo-projects.index')->with([
            'alert-type' => 'success',
            'message' => "Project has been updated successfully",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lpoproject  $lpoproject
     * @return \Illuminate\Http\Response
     */

    public function getLpoProject($id)
    {
        return LpoProject::with(['party', 'tasks' => function ($q) {
            $q->with('vat');
        }])->find($id);
    }

    public function destroy(Lpoproject $lpoproject)
    {
        //
    }

    public function lpo_print($id)
    {
        $lpo_project = LpoProject::find($id);
        return view('backend.lpo-project.print', compact('lpo_project'));
    }

    public function partyQuotations(PartyInfo $party)
    {
        $party->load(['quotations.tasks', 'quotations.jobProjects.tasks']);
        return response()->json(['quotations' => $party->quotations]);
    }

    public function quotationProjects(LpoProject $quotation)
    {
        $quotation->load(['tasks', 'jobProjects.tasks']);
        return response(['quotation' => $quotation, 'projects' => $quotation->jobProjects()->with('tasks')->orderBy('id', 'desc')->get()]);
        // $quotation->load([
        //     'jobProjects' => function($q){
        //         $q->with('tasks')->orderBy('id', 'desc');
        //     }
        // ]);
        // return response(['quotation' => $quotation, 'projects' => $quotation->jobProjects]);
    }
}

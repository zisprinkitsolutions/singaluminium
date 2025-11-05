<?php

namespace App\Http\Controllers\backend;

use App\BillOfQuantity;
use App\BillOfQuantityItem;
use App\BillOfQuantityTask;
use App\BoqSample;
use App\Http\Controllers\Controller;
use App\JobProject;
use App\NewProject;
use App\NewProjectTask;
use App\PartyInfo;
use App\Unit;
use Excel;
use App\BoqTaskName;
use App\BoqItemDetail;
use App\BoqSampleUnit;
use App\Imports\BOQExcelImport;
use App\NewProjectTaskItem;
use App\Services\AiBoqService;
use App\Subsidiary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use function PHPSTORM_META\type;

class BillOfQuantityController extends Controller
{

    protected $aiBoqService;

    public function __construct(AiBoqService $aiBoqService){
        $this->aiBoqService = $aiBoqService;
    }

    public function index(Request $request){
        Gate::authorize('Bill_OF_Quantity');
        $party_id = $request->party_id;
        $search = $request->search;
        $company_id = $request->company_id ? $request->company_id : null;

        $units = Unit::orderBy('name')->get();
        $boqs = BillOfQuantity::orderBy('id', 'desc')
            ->when($search, function ($q) use ($search) {
                $q->where('boq_no', 'like', '%' . $search . '%')
                ->orWhereHas('project', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                    ->orwhere('project_no', 'like', '%' . $search . '%');
                });
            })
            ->when($party_id, function ($q) use ($party_id) {
                $q->where('party_id', $party_id);
            })
            ->with('tasks');
        $cal_total = (clone $boqs)->get()->sum(function ($boq) {
            return $boq->tasks->sum('contact_amount');
        });

        $boqs = $boqs->latest()->paginate(25);

        $project_lists = NewProject::doesntHave('boqs')->orderBy('id','desc')->get();

        $subsidiarys = Subsidiary::get();
        $parties = PartyInfo::orderBy('pi_name')->get();

        return view('backend.boq.index', compact('boqs', 'units','parties', 'search','party_id', 'project_lists','subsidiarys','company_id', 'cal_total'));
    }

    public function create(Request $request){
        Gate::authorize('ProjectManagement_Create');
        $project_id = $request->project_id;
        $party_id = null;
        $new_project = JobProject::find($project_id);

        if($new_project){
            $party_id = $new_project->party_id;
        }

        $parties = PartyInfo::where('pi_type','Customer')->orderBy('pi_name', 'desc')->get();
        $tasks = NewProjectTask::with('sub_tasks.items')->get();

        $boqs = $this->aiBoqService->generateBOQ($request);
        // dd($boqs);
        if(count($boqs ?? []) > 0){
            $tasks = [];
        }


        return view('backend.boq.create',compact('parties', 'tasks', 'new_project', 'boqs', 'party_id'));
    }

    public function store(Request $request){
        Gate::authorize('ProjectManagement_Create');

        $boq = new BillOfQuantity();
        $boq->boq_no = $this->boq_no();
        $boq->party_id = $request->party_id;
        $boq->date = $this->changeDate($request->date);
        $boq->amount = $request->taxable_amount;
        $boq->vat = $request->total_vat;
        $boq->total_amount = $request->total_amount;
        if($request->status == 'final'){
            $boq->status = 1;
        }else{
            $boq->status = 0;
        }
        $boq->save();
        $multi_head = $request->input('group-a');
        $items=[];
        foreach ($multi_head as $key => $each_item) {
            $items[]=[
                'bill_id'=>$boq->id,
                'item_description'=>$each_item['description'],
                'qty'=>$each_item['qty'],
                'sqm'=>$each_item['sqm'],
                'rate'=>$each_item['amount'],
                'total'=>$each_item['sub_gross_amount'],
                'sub_task_id'=>0,
            ];
        }
        BillOfQuantityItem::insert($items);
        return redirect()->route('boq.index')->with(['alert-type' => 'success', 'message' => 'The BOQ has been created succcessfully']);
    }

    public function edit(BillOfQuantity $boq){
        Gate::authorize('ProjectManagement_Edit');
        $boq->load('project', 'tasks.sub_tasks.items');
        $tasks = NewProjectTask::with('sub_tasks.items')->orderBy('name')->get();
        $parties = PartyInfo::where('pi_type','Customer')->orderBy('pi_name', 'desc')->get();
        return view('backend.boq.edit',compact('parties','boq','tasks'));
    }

    public function update(Request $request, BillOfQuantity $boq){

        Gate::authorize('ProjectManagement_Edit');
        $boq->party_id = $request->party_id;
        $boq->date = $this->changeDate($request->date);
        $boq->amount = $request->taxable_amount;
        $boq->vat = $request->total_vat;
        $boq->total_amount = $request->total_amount;
        $boq->save();
        BillOfQuantityItem::where('bill_id',$boq->id)->delete();
        $multi_head = $request->input('group-a');
        $items=[];
        foreach ($multi_head as $key => $each_item) {
            $items[]=[
                'bill_id'=>$boq->id,
                'item_description'=>$each_item['description'],
                'qty'=>$each_item['qty'],
                'sqm'=>$each_item['sqm'],
                'rate'=>$each_item['amount'],
                'total'=>$each_item['sub_gross_amount'],
                'sub_task_id'=>0,
            ];
        }
        BillOfQuantityItem::insert($items);

        return redirect()->route('boq.index')->with(['alert-type' => 'success', 'message' => 'The BOQ has been updated succcessfully']);
    }

    public function boqApprove(BillOfQuantity $boq){
        Gate::authorize('ProjectManagement_Approve');
        $boq->update(['status' => 2]);
        return redirect()->route('boq.index')->with(['alert-type' => 'success', 'message' => 'The BOQ has been updated succcessfully']);
    }

    public function boqPrint(BillOfQuantity $boq){
        return view('backend.boq.print', compact('boq'));
    }


    public function show(BillOfQuantity $boq){
        $boq->load('tasks.items');
        return view('backend.boq.show',compact('boq'));
    }

    public function getBoq(JobProject $project){
        $project->load('tasks.items');
        return response()->json($project);
    }

    public function perSqftItem($item){
        $boq_sample_unit = BoqSampleUnit::find($item->unit);

        if($boq_sample_unit){
            $converted_sqft = $boq_sample_unit->conversion_rate_to_sqft * $item->area;
            return $item->qty / $converted_sqft;
        }
        return 1;
    }

    public function getBoqItems(Request $request){
        $house_type = $request->house_type;
        $work_type = $request->work_type;
        $square_feet = $request->square_feet;
        $total_floor = $request->total_floor;
        $budget = $request->budget;
        $total_sqft = $square_feet * $total_floor;

        $results = [];

        $total_cost = 0;

        $high_boq_items = BoqSample::where('priority','high')->where('house_type', $house_type)->where('work_type', $work_type)->get();

        foreach($high_boq_items as $item){
            $task_item = NewProjectTaskItem::find($item->item_id);

            if($item->area && $item->unit){
                $per_sqft_items = $this->perSqftItem($item);
                $total_qty = $per_sqft_items * $total_sqft;
            }else{
                $total_qty = 1;
            }

            $amount = $total_qty * $task_item->rate * $item->cost_factor;

            $results[$task_item->task->name][] = [
                'item_description' => $task_item->item_description,
                'unit' => $task_item->unit,
                'rate' => $task_item->rate * $item->cost_factor,
                'qty' => $total_qty,
                'amount' => $amount,
            ];

            $total_cost += $amount;
        }


        if($total_cost > $budget){
            return response()->json([
                'message' => "Your Budget is so low atleast " . number_format($total_cost + 1,2) ." is required",
                'page' => view('backend.boq._boq_generate_list', compact('results'))->render(),
                'need_amount' => $total_cost,
                'type' => 'error',
            ]);
        }

        $medium_boq_items = BoqSample::where('priority','medium')->where('house_type', $house_type)->where('work_type', $work_type)->get();

        $medium_boq = [];

        foreach($medium_boq_items as $item){
            $task_item = NewProjectTaskItem::find($item->item_id);

            if($item->area && $item->unit){
                $per_sqft_items = $this->perSqftItem($item);
                $total_qty = $per_sqft_items * $total_sqft;
            }else{
                $total_qty = 1;
            }

            $amount = $total_qty * $task_item->rate * $item->cost_factor;

            $medium_boq[$task_item->task->name][] = [
                'item_description' => $task_item->item_description,
                'unit' => $task_item->unit,
                'rate' => $task_item->rate * $item->cost_factor,
                'qty' => $total_qty,
                'amount' => $amount,
            ];

            $total_cost += $amount;
        }

        if($budget < $total_cost){
            $need_reduce = $total_cost - $budget;
            $reduce_percentage = ($need_reduce / $total_cost) * 100;

            if($reduce_percentage < 30){
                $this->rearange($medium_boq,$budget,$total_cost);
            }else{
                return response()->json([
                    'message' => "Add more " . number_format($need_reduce / 2,2) . " to " . number_format($need_reduce,2) . " for standard work.",
                    'page' => view('backend.boq._boq_generate_list', compact('results'))->render(),
                    'type' => 'info',
                    'need_amount' => $need_reduce,
                ]);
            }
        }


        foreach($medium_boq as $key => $boq){
            foreach($boq as $item){
                $results[$key][] = [
                    'item_description' => $item['item_description'],
                    'unit' => $item['unit'],
                    'rate' => $item['rate'],
                    'qty' => $item['qty'],
                    'amount' => $item['amount'],
                ];
            }
        }

        $low_boq_items = BoqSample::where('priority','low')->where('house_type', $house_type)->where('work_type', $work_type)->get();

        $low_boq = [];

        foreach($low_boq_items as $item){
            $task_item = NewProjectTaskItem::find($item->item_id);

            if($item->area && $item->unit){
                $per_sqft_items = $this->perSqftItem($item);
                $total_qty = $per_sqft_items * $total_sqft;
            }else{
                $total_qty = 1;
            }

            $amount = $total_qty * $task_item->rate * $item->cost_factor;

            $low_boq[$task_item->task->name][] = [
                'item_description' => $task_item->item_description,
                'unit' => $task_item->unit,
                'rate' => $task_item->rate * $item->cost_factor,
                'qty' => $total_qty,
                'amount' => $amount,
            ];

            $total_cost += $amount;
        }

        if($budget < $total_cost){
            $need_reduce = $total_cost - $budget;
            $reduce_percentage = ($need_reduce / $total_cost) * 100;

            if($reduce_percentage < 30){
                $this->rearange($low_boq,$budget,$total_cost);
            }else{
                return response()->json([
                    'message' =>  "Add more " . number_format($need_reduce / 2,2) . " to " . number_format($need_reduce,2) . " for deluxe work.",
                    'page' => view('backend.boq._boq_generate_list', compact('results'))->render(),
                    'type' => 'info',
                    'need_amount' => $need_reduce,
                ]);
            }
        }


        foreach($low_boq as $key => $boq){
            foreach($boq as $item){
                $results[$key][] = [
                    'item_description' => $item['item_description'],
                    'unit' => $item['unit'],
                    'rate' => $item['rate'],
                    'qty' => $item['qty'],
                    'amount' => $item['amount'],
                ];
            }
        }

        $optional_boq_items = BoqSample::where('priority','optional')->where('house_type', $house_type)->where('work_type', $work_type)->get();

        $optional_boq = [];

        foreach($optional_boq_items as $item){
            $task_item = NewProjectTaskItem::find($item->item_id);

            if($item->area && $item->unit){
                $per_sqft_items = $this->perSqftItem($item);
                $total_qty = $per_sqft_items * $total_sqft;
            }else{
                $total_qty = 1;
            }

            $amount = $total_qty * $task_item->rate * $item->cost_factor;

            $low_boq[$task_item->task->name][] = [
                'item_description' => $task_item->item_description,
                'unit' => $task_item->unit,
                'rate' => $task_item->rate * $item->cost_factor,
                'qty' => $total_qty,
                'amount' => $amount,
            ];

            $total_cost += $amount;
        }

        if($budget < $total_cost){
            $need_reduce = $total_cost - $budget;
            $reduce_percentage = ($need_reduce / $total_cost) * 100;

            if($reduce_percentage < 30){
                $this->rearange($low_boq,$budget,$total_cost);
            }else{
                return response()->json([
                    'message' =>  "Add more " . number_format($need_reduce / 2,2) . " to " . number_format($need_reduce,2) . " for premium work.",
                    'page' => view('backend.boq._boq_generate_list', compact('results'))->render(),
                    'type' => 'info',
                    'need_amount' => $need_reduce,
                ]);
            }
        }

        foreach($low_boq as $key => $boq){
            foreach($boq as $item){
                $results[$key][] = [
                    'item_description' => $item['item_description'],
                    'unit' => $item['unit'],
                    'rate' => $item['rate'],
                    'qty' => $item['qty'],
                    'amount' => $item['amount'],
                ];
            }
        }

        return response()->json([
            'message' => "The boq generate successfully",
            'page' => view('backend.boq._boq_generate_list', compact('results'))->render(),
            'type' => 'success',
        ]);
    }

    private function rearange(&$boq, $budget, $total_cost){
        $need_reduce = $total_cost - $budget;

        if($need_reduce <= 0) return;

        foreach($boq as $task_name => &$items){
            foreach($items as &$item){
                $reduce_amount = ($item['amount'] / $total_cost) * $need_reduce;
                $item['amount'] -= $reduce_amount;
                $item['qty'] = $item['amount'] / $item['rate'];
            }
        }
    }

    public function boqSample(Request $request){
        $sample_units = BoqSampleUnit::get();
        $tasks = NewProjectTask::get();
        $boq_samples = BoqSample::orderBy('task_id')->paginate(40);
        // dd($boq_samples);
        return view('backend.new-project.sample-list', compact('boq_samples', 'tasks', 'sample_units'));
    }

    public function storeBoqSample(Request $request){

        $project_tasks = $request->project_task;
        $items = $request->project_item;
        $house_type = $request->house_type;
        $cost_factor = $request->cost_factor;
        $priority = $request->priority;
        $work_type = $request->work_type;
        $area = $request->area;
        $unit = $request->unit;
        $qty = $request->qty;

        foreach($project_tasks as $key => $task){

            $old_data = BoqSample::where('task_id', $task)->where('item_id', $items[$key])
                ->where('house_type', $house_type[$key])->where('work_type', $work_type[$key])->first();

            if($old_data){
                $old_data->update([
                    'task_id' => $task,
                    'item_id' => $items[$key],
                    'house_type' => $house_type[$key],
                    'cost_factor' => $cost_factor[$key],
                    'priority' => $priority[$key],
                    'area' =>$area[$key],
                    'unit' => $unit[$key],
                    'qty' => $qty[$key],
                    'work_type' => $work_type[$key],
                ]);
            }else{
                BoqSample::create([
                    'task_id' => $task,
                    'item_id' => $items[$key],
                    'house_type' => $house_type[$key],
                    'cost_factor' => $cost_factor[$key],
                    'priority' => $priority[$key],
                    'area' =>$area[$key],
                    'unit' => $unit[$key],
                    'qty' => $qty[$key],
                    'work_type' => $work_type[$key],
                ]);
            }
        }

        return back()->with(['alert-type'=> 'success', 'message' => 'This BOQ Factor has been created successfully.']);
    }

    public function getOldBoqSample(Request $request){

        $task_id = $request->project_task;
        $item_id = $request->project_item;
        $house_type = $request->house_type;
        $work_type = $request->work_type;

        $boq_sample = BoqSample::where([
            ['task_id', $task_id],
            ['item_id', $item_id],
            ['house_type', $house_type],
            ['work_type', $work_type],
        ])->first();

        if($boq_sample){
            return response()->json($boq_sample);
        }
    }

    public function destroy(BillOfQuantity $boq){

        Gate::authorize('ProjectManagement_Delete');

        if($boq->stauts == 2){
            return back()->with(['alert-type'=> 'warning', 'message' => 'This BOQ can not be delete this.']);
        }
        BillOfQuantityItem::where('bill_id', $boq->id)->delete();
        $boq->delete();
        return back()->with(['alert-type'=> 'success', 'message' => 'The BOQ has been deleted successfully.']);
    }

    public function boq_no(){
        $sub_invoice = 'BOQ';
        // return $sub_invoice;
        $boq = BillOfQuantity::orderBy('id', 'desc')->first();
        if ($boq) {
            $boq_no  = preg_replace('/^'.$sub_invoice.'/', '', $boq->boq_no);
            $boq_code = $boq_no  + 1;
            if($boq_code<10)
            {
               $boq_no =$sub_invoice.'000'.$boq_code;
            }
            elseif($boq_code<100)
            {
                $boq_no =$sub_invoice.'00'.$boq_code;
            }
            elseif($boq_code<1000)
            {
                $boq_no =$sub_invoice.'0'.$boq_code;
            }
            else
            {
                $boq_no =$sub_invoice.$boq_code;
            }
        }else {
            $boq_no  = $sub_invoice . '0001';
        }
        return $boq_no ;
    }

    public function partyProject(PartyInfo $party){
        $projects = JobProject::where('customer_id', $party->id)->get();
        return response()->json(['projects' => $projects]);
    }

    public function partyBoq(PartyInfo $party){
        $boqs = BillOfQuantity::where('party_id', $party->id)->where('status', 2)->get();
        return response()->json(['boqs' => $boqs]);
    }
    public function project_item_get(Request $request){
        $items=BillOfQuantityItem::where('bill_id', $request->id)->get();
        if($request->ajax()){
            return Response()->json([
                'page'=>view('backend.lpo-project.boq-item', ['items'=>$items])->render(),
            ]);
        }
    }
    public function projectTask(JobProject $project){
       $project->load('tasks.items');
        return response()->json(['tasks' => $project->tasks]);
    }

    private function changeDate($date){
        $date_array = explode('/', $date);
        $date_string = implode('-', $date_array);
        return date('Y-m-d', strtotime($date_string));
    }
    public function boq_excel_import(Request $request)
    {
        $request->session()->put('token', $request->token);
        $request->session()->put('project_id', $request->boq_project_name);

        $import = new BOQExcelImport();
        Excel::import($import, $request->excel_file);

        $message = '✅ <strong>The BOQ has been imported successfully.</strong>';
        $skippedMessages = $import->getSkippedRows();

        if (!empty($skippedMessages)) {
            $formattedMessages = "<div style='text-align: left; margin-top: 10px;'>";
            $formattedMessages .= "<p>⚠️ <strong>However, some rows were skipped:</strong></p>";
            $formattedMessages .= "<ul style='padding-left: 20px;'>";
            foreach ($skippedMessages as $msg) {
                $formattedMessages .= "<li>🔸 " . e($msg) . "</li>";
            }
            $formattedMessages .= "</ul></div>";

            return back()->with([
                'alert-type' => 'success',
                'message_import' => $message . $formattedMessages
            ]);
        }

        return back()->with([
            'alert-type' => 'success',
            'message_import' => $message
        ]);
    }

    public function boq_check_excel_import(Request $request){
        $records = BoqTaskName::where('token', Session::get('token'))->get();
        $project_info = NewProject::find(Session::get('project_id'));
        $parties = PartyInfo::find($project_info->party_id);
        return view('backend.boq.check-excel',compact('parties', 'records', 'project_info'));
    }
}


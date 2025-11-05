<?php

namespace App\Http\Controllers\Backend;

use App\BillOfQuantityTask;
use App\Http\Controllers\Controller;
use App\JobProjectTask;
use App\NewProject;
use App\NewProjectSubTask;
use App\NewProjectTask;
use App\NewProjectTaskItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NewProjectTaskController extends Controller
{
     private function dateFormat($date)
    {
        $old_date = explode('/', $date);

        $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }


    public function index(Request $request){
        Gate::authorize('Project_Task');
        $search = $request->search;
        $tasks = NewProjectTask::with('sub_tasks') // still eager load for view
        ->when($search, function($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('sub_tasks', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
        })
        ->with('items');
        // ->paginate(20);
        $cal_total_amount = (clone $tasks)->get()->sum(function($task){
            return $task->items->sum('total');
        });
        $cal_total_rate = (clone $tasks)->get()->sum(function($task){
            return $task->items->sum('rate');
        });

        $tasks = $tasks->paginate(20);

        $data = [];
        $data['cal_total_amount'] = $cal_total_amount;
        $data['cal_total_rate'] = $cal_total_rate;


        return view('backend.new-project.tasks.index', compact('tasks','search', 'data'));
    }

    public function create(){
        Gate::authorize('ProjectManagement_Create');

        $projects = NewProject::all();
        return view('backend.new-project.tasks.create',compact('projects'));
    }

    public function store(Request $request){
        Gate::authorize('ProjectManagement_Create');

        $request->validate([
            'task_name' =>  'required|max:255',
        ]);

        $task = new NewProjectTask();
        $task->name = $request->task_name;
        $task->total_amount = $request->subtotal;
        $task->save();

        $sub_tasks = $request->input('subtask_name');

        foreach($sub_tasks as $key => $name){
            $sub_task = new NewProjectSubTask();
            $sub_task->name = $name;
            $sub_task->task_id = $task->id;
            $sub_task->save();

            $item_description = $request->item_description[$key];

            foreach($item_description as $sub_key => $description){
                $item = new NewProjectTaskItem();
                $item->task_id = $task->id;
                $item->sub_task_id = $sub_task->id;
                $item->item_description = $description;
                $item->unit = $request->unit[$key][$sub_key];
                $item->qty = $request->qty[$key][$sub_key];
                $item->rate = $request->rate[$key][$sub_key];
                $item->total = $request->amount[$key][$sub_key];
                $item->save();
            }
        }

        return redirect()->route('project.tasks.index')->with(['alert-type' => 'success', 'message' => 'The task has been created successfully']);
    }

    public function edit(Request $request, NewProjectTask $task){
        Gate::authorize('ProjectManagement_Edit');

        $projects = NewProject::all();
        return view('backend.new-project.tasks.edit',compact('projects', 'task'));
    }

    public function update(Request $request, NewPRojectTask $task){

        Gate::authorize('ProjectManagement_Edit');

        $request->validate([
            'task_name' =>  'required|max:255',
        ]);

        $task->name = $request->task_name;
        $task->total_amount = $request->subtotal;
        $task->save();

        $task->items->each->delete();
        $task->sub_tasks->each->delete();

        $sub_tasks = $request->input('subtask_name');

        foreach($sub_tasks as $key => $name){
            $sub_task = new NewProjectSubTask();
            $sub_task->name = $name;
            $sub_task->task_id = $task->id;
            $sub_task->save();

            $item_description = $request->item_description[$key];

            foreach($item_description as $sub_key => $description){
                $item = new NewProjectTaskItem();
                $item->task_id = $task->id;
                $item->sub_task_id = $sub_task->id;
                $item->item_description = $description;
                $item->unit = $request->unit[$key][$sub_key];
                $item->qty = $request->qty[$key][$sub_key];
                $item->rate = $request->rate[$key][$sub_key];
                $item->total = $request->amount[$key][$sub_key];
                $item->save();
            }
        }

        return redirect()->route('project.tasks.index')->with(['alert-type' => 'success', 'message' => 'The task has been created successfully']);
    }

    public function destroy(NewProjectTask $task){
        Gate::authorize('ProjectManagement_Delete');

        $exist = BillOfQuantityTask::where('project_task_id', $task->id);
        // if($exist){
        //     return back()->with(['alert-type' => 'error', 'message' => 'This task is associated with another module and cannot be deleted']);
        // }

        $task->delete();

        return back()->with(['alert-type' => 'success', 'message' => 'This task has been deleted successfully']);

    }
}

<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\WorkType;
use Illuminate\Http\Request;

class WorkTypeController extends Controller
{
    public function index(){
        $work_types = WorkType::all();
        return view('backend.work-type.index', compact('work_types'));
    }

    public function store(Request $request)
    {
        $work_type_id = $request->house_type_id;
        $work_type = $request->house_type;

        // Check if the house type already exists
        $work = WorkType::where('name', $work_type)
            ->when($work_type_id, function ($q) use ($work_type_id) {
                $q->where('id', '!=', $work_type_id);
            })
            ->first();

        if ($work) { // <-- should check $work, not $work_type
            return back()->with([
                'alert-type' => 'warning',
                'message' => 'The house type already exists!',
            ]);
        }

        // Create or update
        if ($work_type_id) {
            $work = WorkType::find($work_type_id);
        } else {
            $work = new WorkType();
        }

        $work->name = $work_type;
        $work->save();

        return back()->with([
            'alert-type' => 'success',
            'message' => 'Work type saved successfully!',
        ]);
    }
}

<?php

namespace App\Http\Controllers\backend\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll\Employee;
use App\Models\Payroll\ReportingAuthority;
use Illuminate\Http\Request;

class ReportingAuthorityController extends Controller
{
    public function index(Request $request){
        $selected_employee_id = $request->selected_employee;

        $date = $request->date ? change_date_format($request->date) : date('Y-m-d');

        // 1. Get top-level employees (those who are not child_id on the given date)
        // $topEmployees = Employee::whereNotIn('id', function ($query) use ($date) {
        //     $query->select('child_id')
        //         ->from('reporting_authorities')
        //         ->whereDate('work_date', $date);
        // })->whereIn('id', function($query) use($date){
        //     $query->select('parent_id')
        //     ->from('reporting_authorities')
        //     ->whereDate('work_date', $date);
        // })
        // ->with(['recursiveSubordinates' => function ($query) use ($date) {
        //     $query->wherePivot('work_date', $date);
        // }])->get();

        $query = Employee::with(['recursiveSubordinates' => function ($q) use ($date) {
            $q->wherePivot('work_date', $date);
        }]);

        if ($selected_employee_id) {
            $query->where('id', $selected_employee_id);
        } else {
            $query->whereNotIn('id', function ($subquery) use ($date) {
                $subquery->select('child_id')
                    ->from('reporting_authorities')
                    ->whereDate('work_date', $date);
            })
            ->whereIn('id', function ($subquery) use ($date) {
                $subquery->select('parent_id')
                    ->from('reporting_authorities')
                    ->whereDate('work_date', $date);
            });
        }

        // Use pagination
        $topEmployees = $query->paginate(10);


        // 2. Get employees from division 1,2,3 who are not parent_id or child_id on the given date
        $top_free_employees = Employee::whereIn('division', [1, 2, 3])->orderBy('full_name')->get();

        // 3. Get employees  or child_id on the given date
        $employees = Employee::whereNotIn('id', function($query) USE ($date){
            $query->select('child_id')->from('reporting_authorities')
                ->whereDate('work_date', $date);
            })
            ->orderBy('full_name')->get();

        // all employee

        $all_employees = Employee::orderBy('full_name')->get();

        return view('backend.payroll.reporting-authority.index',compact(
            'selected_employee_id', 'all_employees',
            'topEmployees','top_free_employees','employees','date'
        ));
    }

    public function store(Request $request){
        $request->validate([
            'parent_id' => 'required',
            'work_date' => 'required',
        ]);

        $date = change_date_format($request->work_date);

        $inputs = $request->input('group-a');

        foreach($inputs as $input){
            ReportingAuthority::create([
                'child_id' => $input['child_id'],
                'parent_id' => $request->parent_id,
                'work_date' => $date,
            ]);
        }

        return back()->with(['alert-type' => 'success', 'message' => 'The employee reproting has been created successfully']);
    }

    public function edit($id, Request $request){
        $employee_id = $id;
        $date = $request->date ?? date('Y-m-d');


        $exist_parent = ReportingAuthority::where('parent_id', $employee_id)->whereDate('work_date', $date)->first();

        if(!$exist_parent){
            $child_exist =  ReportingAuthority::where('child_id', $employee_id)->whereDate('work_date', $date)->first();
            if($child_exist){
                 $exist_parent =  ReportingAuthority::where('parent_id', $child_exist->parent_id)->whereDate('work_date', $date)->first();
            }else{
                $child_exist =  ReportingAuthority::where('child_id', $employee_id)->latest()->first();
                $exist_parent =  ReportingAuthority::where('parent_id', $child_exist->parent_id)->latest()->first();
            }
        }

        $top_employee = Employee::find($exist_parent->parent_id);

        // 3. Get employees parent_id or child_id on the given date
        $employees = Employee::orderBy('full_name')->get();
        return view('backend.payroll.reporting-authority.edit',compact('top_employee','employees','date'));
    }

    public function update(Request $request,$id){
        $request->validate([
            'parent_id' => 'required',
            'work_date' => 'required',
        ]);

        $date = change_date_format($request->work_date);

        $this->deleteRecursive($request->parent_id, $date);

        $inputs = $request->input('group-a');

        foreach($inputs as $input){
            ReportingAuthority::create([
                'child_id' => $input['child_id'],
                'parent_id' => $request->parent_id,
                'work_date' => $date,
            ]);
        }

        return back()->with(['alert-type' => 'success', 'message' => 'The report has been update successfully']);
    }

    public function deleteRecursive($parent_id, $date)
    {
        // Get direct children for this parent and date
        $childs = ReportingAuthority::where('parent_id', $parent_id)
            ->where('work_date', $date)
            ->get();

        foreach ($childs as $child) {
            // Recursive call to delete this child's children first
            $this->deleteRecursive($child->child_id, $date);
        }

        // Now delete all links where this employee is parent
        ReportingAuthority::where('parent_id', $parent_id)
            ->where('work_date', $date)
            ->delete();

        ReportingAuthority::where('child_id', $parent_id)
            ->where('work_date', $date)
            ->delete();
    }

    public function destroy($id, Request $request){
        $date = $request->date;
        $child_id = $id;
        $this->deleteRecursive($child_id, $date);
    }
}

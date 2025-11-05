<?php

namespace App\Http\Controllers\backend\Payroll;

use App\Http\Controllers\Controller;
use App\Mapping;
use App\Models\Payroll\SalaryType;
use App\Models\AccountHead;
use App\Models\Payroll\Employee;
use App\Models\Payroll\EmployeeHistory;
use App\Models\Payroll\EmployeeSalary;
use App\Models\Payroll\GradeWiseLiveList;
use App\Models\Payroll\LeaveDocument;
use App\Models\Payroll\LeaveInformation;
use App\Models\Payroll\Nationality;
use App\Models\Payroll\SalaryStructure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;

class EmployeeHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        // dd('hi');
        // $startDate = date('Y')."-01-01";
        // $now = Carbon::now()->format('y-m-d');
        
        //Gate::authorize('app.mapping.index');
        $leave_info = LeaveInformation::orderBy('id', 'desc')->get();
        $employees = Employee::orderBy('id', 'desc');
        if($request->search){
            $employees = $employees->where(function ($query) use ($request) {
                if ($request->search) {
                    $searchTerms = explode(' ', $request->search);
                    foreach ($searchTerms as $term) {
                        $term = trim($term); // Remove leading/trailing spaces from each term
                        if (!empty($term)) { // Skip empty terms
                            $query->orWhere(function ($subquery) use ($term) {
                                $subquery->where('first_name', 'like', '%' . $term . '%');
                                        //  ->orWhere('first_name', 'like', '%' . $term . '%')
                                        //  ->orWhere('middle_name', 'like', '%' . $term . '%')
                                        //  ->orWhere('last_name', 'like', '%' . $term . '%');
                            });
                            $query->orWhere('emp_id', 'like', '%' . $term . '%')
                                ->orWhere('contact_number', 'like', '%' . $term . '%')
                                ->orWhere('local_contact_number', 'like', '%' . $term . '%');
                        }
                    }
                }
            });
        }
        $employees = $employees->paginate(15)->withQueryString();
        // dd($salaryStructure['1']['id']);
        return view('backend.payroll.employee_history.index', compact('leave_info', 'employees'));
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
    public function store(Request $request)
    {
        // dd($request);
        if ($request->file('file')) {

                $name= $request->file('file')->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext= $request->file('file')->getClientOriginalExtension();
                $file_name= $request->history.time().'.'.$ext;
                
                $request->file('file')->storeAs( 'public/upload/employee_history', $file_name);
            }

        $employee_history = new EmployeeHistory();
        $employee_history->emp_id = $request->employee_id;
        $employee_history->history = $request->history;
        $employee_history->date = date('Y-m-d');
        $employee_history->remark = $request->remark;
        $employee_history->approved_by = $request->approved_by;
        $employee_history->extension = $ext;
        $employee_history->document = $file_name;
        $employee_history->save();

        
        $notification= array(
            'message'       => 'Created successfully!',
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
        $histories= EmployeeHistory::where('emp_id', $id)->get();
        $employee = Employee::find($id);

        return Response()->json([
            'page' => view('backend.payroll.employee_history.view-modal', ['histories' => $histories,
                                                                     'employee' => $employee])->render()
                                                                     

        ]);

        // return view('backend.payroll.employee_history.view-modal', compact('histories','employee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $histories= EmployeeHistory::find($id);
        $employee = Employee::get();

        return Response()->json([
            'page' => view('backend.payroll.employee_history.edit-modal', ['history' => $histories,
                                                                            'employees' => $employee])->render()
        ]);
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
        // $request->validate([
        //     'name' => 'required',
        // ]);
        if($request->file('file')){
                $path = public_path('storage/upload/employee_history/').$request->document;
                if (file_exists($path)) {
                    unlink($path);
                }

                $name= $request->file('file')->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext= $request->file('file')->getClientOriginalExtension();
                $file_name= $request->history.time().'.'.$ext;
                
                $request->file('file')->storeAs( 'public/upload/employee_history', $file_name);
        }

        $employee_history = EmployeeHistory::find($id);
        $employee_history->emp_id = $request->employee_id;
        $employee_history->history = $request->history;
        $employee_history->remark = $request->remark;
        $employee_history->approved_by = $request->approved_by;
        if($request->file('file')){
            $employee_history->document = $file_name;
            $employee_history->extension = $ext;
        }
        $employee_history->save();

        $notification= array(
            'message'       => 'Update successfully!',
            'alert-type'    => 'success'
        );
        return redirect('employee-history')->with($notification);
    }

    public function employeeInfo(Request $request)
    {
        $startDate = "01-01-".date('Y');
        $now = Carbon::now()->format('d-m-y');
        // return 1;
        $emp_name = Employee::find($request->emp);
        $entitled = GradeWiseLiveList::find($emp_name->grade);
        $takeInformation = LeaveInformation::where('employee_id',$request->emp)->whereYear('from_date', date('Y'))->get();
        // dd($takeInformation);
        // $entitled = LeaveInformation::where('employee_id',$emp_name->id)where('created_at');
        if($emp_name->count()>0)
        {
            if ($request->ajax()) {
                return Response()->json([
                    'page' => $emp_name,
                    'entitled' => view('backend.payroll.leaveManagement.leaveInfo', ['entitled' => $entitled, 'i' => 1])->render(),
                    'takeInformation' => view('backend.payroll.leaveManagement.takeInfo', ['takeInformation' => $takeInformation, 'i' => 1])->render(),
                    'newLeave' => view('backend.payroll.leaveManagement.newLeave', ['newLeave' => $entitled, 'i' => 1])->render(),

                ]);
            }
        }
    }

    public function employee_history_view(Request $request){
        $histories= EmployeeHistory::where('emp_id', $request->id)->get();
        $employee = Employee::find($request->id);
        return view('backend.payroll.employee_history.view-modal', compact('histories','employee'));
    }

    // public function historyInfo(Request $request)
    // {
    //     $emp_info = Employee::where('emp_id', 'like', '%' . $request->id . '%')->orWhere('name', 'like', '%' . $request->id . '%')->get();
 
    //     if($emp_info->count()>0)
    //     {
    //         if ($request->ajax()) {
    //             return Response()->json([
    //                 'page' => view('backend.payroll.employee_history.ajaxEmpList', ['empList' => $emp_info, 'i' => 1])->render(),

    //             ]);
    //         }
    //     }
    // }
}

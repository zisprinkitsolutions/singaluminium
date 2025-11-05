<?php

namespace App\Http\Controllers\backend\Payroll;

use App\Http\Controllers\Controller;
use App\Mapping;
use App\Models\Payroll\EmployeeSalary;
use App\Models\Payroll\SalaryStructure;
use App\Models\AccountHead;
use App\Models\Payroll\ComponentType;
use App\Models\Payroll\Employee;
use App\Models\Payroll\ExtraSalaryComponent;
use App\Models\Payroll\ExtraSalaryComponentHistory;
use App\Models\Payroll\Grade;
use App\Models\Payroll\SalaryComponent;
use App\Models\Payroll\SalaryStructureStory;
use App\Models\Payroll\SalaryType;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;

class EmployeeSalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //Gate::authorize('app.mapping.index');
        // $employeeSalarys = EmployeeSalary::orderBy('id', 'desc')->get();
        $employees = Employee::all();
        $wages_type = SalaryType::all();
        $salaryStructure = SalaryStructure::all()->toArray();


        // dd($salaryStructure['1']['id']);
        return view('backend.payroll.extra_salary_component.index', compact( 'employees', 'salaryStructure','wages_type'));
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
    // public function store(Request $request)
    // {
    //     // dd($request);
    //     $request->validate([
    //         'basic' => 'required',
    //         'employee_id' => 'required|unique:employee_salaries',
    //     ]);
    //     $emp_info = Employee::find($request->employee_id);
    //     // dd($emp_info);
    //     $info = EmployeeSalary::create([
    //         'employee_id' => $request->employee_id,
    //         'basic' => $request->basic,
    //         'house_rent' => $request->house_rent,
    //         'transportation' => $request->transportation,
    //         'bonus' => $request->bonus,
    //         'telephone_bill' => $request->telephone_bill,
    //         'ta' => $request->ta,
    //         'da' => $request->da,
    //         'medical_expenses' => $request->medical_expenses,
    //         'vacation_bonus' => $request->vacation_bonus,
    //         'tax_reduction' => $request->tax_reduction,
    //         'providant_fund' => $request->providant_fund,
    //         'gratuity' => $request->gratuity,
    //         'others' => $request->others,
    //         'total' => $request->total
    //     ]);

    //     SalaryStructureStory::create([
    //         'employee_id' => $info->employee_id,
    //         'basic' => $info->basic,
    //         'house_rent' => $info->house_rent,
    //         'transportation' => $info->transportation,
    //         'bonus' => $info->bonus,
    //         'telephone_bill' => $info->telephone_bill,
    //         'ta' => $info->ta,
    //         'da' => $info->da,
    //         'medical_expenses' => $info->medical_expenses,
    //         'vacation_bonus' => $info->vacation_bonus,
    //         'tax_reduction' => $info->tax_reduction,
    //         'providant_fund' => $info->providant_fund,
    //         'gratuity' => $info->gratuity,
    //         'others' => $info->others,
    //         'form' => $emp_info->joining_date,
    //         'total' => $info->total
    //     ]);

    //     $notification= array(
    //         'message'       => 'Employee Salary Added successfully!',
    //         'alert-type'    => 'success'
    //     );
    //     return redirect('employee-salary')->with($notification);
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $extra = ExtraSalaryComponent::find($id);
        $employee = Employee::find($id);
        $grade = Grade::find($employee->grade);
        // dd($grade);
        $components = SalaryComponent::orderBy('id', 'desc')->get();
        $component_types = ComponentType::orderBy('id', 'desc')->get();

        return Response()->json([
            'page' => view('backend.payroll.extra_salary_component.edit-modal', ['components' => $components,
                                                                            'component_types' => $component_types,
                                                                            'employee' => $employee,
                                                                            'grade' => $grade,
                                                                            'extra' => $extra])->render(),

        ]);
        // return view('backend.payroll.extra_salary_component.edit', compact('employee','components','component_types','grade','extra'));
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

        $old_date = explode('/', $request->date);

        $new_data = $old_date[0].'-'.$old_date[1].'-'.$old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);

        $employee = Employee::find($id);

        if (!$employee) {
            $notification= array(
                'message'       => 'There have no Employee',
                'alert-type'    => 'danger'
            );
            return redirect('employee-salary')->with($notification);
        }

        ExtraSalaryComponent::where('employee_id',$employee->id)->delete();

        //if update one employees in same month more than one than previous salary component will delete
        ExtraSalaryComponentHistory::where('employee_id',$employee->id)->whereMonth('date',$new_date)->whereYear('date',$new_date)->delete();


        foreach ($request->records['head'] as $key => $value) {

            ExtraSalaryComponent::create([
                    'type_id' => $request->records['type'][$key],
                    'employee_id' => $request->employee_id,
                    'date' => $new_date,
                    'salary_component_id' => $request->records['head'][$key],
                    'value' => $request->records['amount'][$key],
            ]);

            ExtraSalaryComponentHistory::create([
                'type_id' => $request->records['type'][$key],
                'employee_id' => $request->employee_id,
                'date' => $new_date,
                'salary_component_id' => $request->records['head'][$key],
                'value' => $request->records['amount'][$key],
            ]);
    }
        // dd($request->all());
        // $request->validate([
        //     'head' => 'required',
        // ]);

        $notification= array(
            'message'       => 'Update successfully!',
            'alert-type'    => 'success'
        );
        return redirect('employee-salary')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employeeSalary = EmployeeSalary::find($id);
        $employeeSalary->delete();
        $notification = array(
            'message'       => 'Employee Salary Deleted successfully!',
            'alert-type'    => 'success'
        );
        return redirect('employee-salary')->with($notification);
    }
}

<?php

namespace App\Http\Controllers\backend\Payroll;

use App\Http\Controllers\Controller;
use App\Mapping;
use App\Models\Payroll\SalaryStructure;
use App\Models\AccountHead;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;

use App\Models\Payroll\EmployeeSalary;
use App\Models\Payroll\PaySalary;
// use Illuminate\Support\Carbon;
use Carbon\Carbon;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
     
    }

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
        
    }

    public function crearteSalary()
    {
        //Gate::authorize('app.mapping.index');
        $date = Carbon::now();
        $monthName = $date->subMonth()->format('F');
        $year = $date->format('Y');

        $check=PaySalary::where('month', $monthName)->get();
        // dd($monthName);


        if(count($check) == 0)
        {
            $employees = EmployeeSalary::orderBy('id', 'desc')->get();

            foreach ($employees as $item){
                PaySalary::create([
                    'employee_id' => $item->employee_id,
                    'month' =>$monthName,
                    'year' => $year,
                    'payable' => $item->total,
                    'paid' => 0,
                    'due' => $item->total
                ]);
            }

            $notification= array(
                'message'       => 'Salary Sheet Create successfully!',
                'alert-type'    => 'success'
            );
            
        }else{
            $notification= array(
                'message'       => 'This months salary sheet already created!',
                'alert-type'    => 'success'
            );
        };

        
        return redirect('pay-salary')->with($notification);
        
    }

    public function percentCount(Request $request)
    {
        // return $request;

        $info = SalaryStructure::where('type', '%')->get();
        // foreach ($info as $key => $value) {

        // }
        return ['infos'=>$info,'value'=>$request->value];
    }
}

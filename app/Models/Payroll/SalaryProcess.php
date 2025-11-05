<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use App\Models\Payroll\Employee;

class SalaryProcess extends Model
{
    public function employee(){
        return $this->belongsTo(Employee::class,'employee_id');
    }

    public function items()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function salaryComponent()
    {
        return $this->belongsTo(SalaryComponent::class, 'salary_component_id');
    }

    public function components($id, $month, $year){

        return SalaryProcess::where('employee_id',$id)->where('month',$month)->where('year',$year)->get();
    }
    public function overtime_late($id){

        return OvertimeLatesSalaryProcess::where('employee_id',$id)->where('status', 1)->first();
    }
    public function totalSalary($id){
           $deduct = DeductionProcess::where('employee_id',$id)->where('status', 1)->sum('amount');
           $installment_amount = InstallmentProcess::where('employee_id',$id)->where('status', 1)->sum('amount');

           $earns = SalaryProcess::where('employee_id',$id)->where('status', 1)->sum('amount');
        return $result =  $earns - $deduct - $installment_amount;
    }

    public function deductComponents($id, $month, $year) {

        // $return = DeductionProcess::where('employee_id',$id)->where('month',$month)->where('year',$year)->get();

        return DeductionProcess::where('employee_id',$id)->where('month',$month)->where('year',$year)->get();
    }

    public function check($employee)
    {
        $status = DeductionEntry::where('employee_id',$employee)->where('due','!=', 0)->orderBy('id','DESC')->first();

        if($status) {

            // dd($status->value);
            return true;
        }
        return null;
    }

    public function deductProcessCheck($employee)
    {
        $status = DeductionProcess::where('employee_id',$employee)->where('status', 1)->orderBy('id','DESC')->first();

        if($status) {

            // dd($status->value);
            return true;
        }
        return null;
    }
    public function installmentProcess($employee)
    {
        $status = InstallmentProcess::where('employee_id',$employee)->where('status', 1)->orderBy('id','DESC')->first();

        if($status) {

            // dd($status->value);
            return true;
        }
        return null;
    }
    public function isntallmentProcessCheck($employee, $month, $year)
    {
        $monthNumber = date('m', strtotime($month));
        $installmentMonth = $year . '-' . $monthNumber;
        $installments = Installment::whereHas('loan', function ($query) use ($employee) {
            $query->where('emp_id', $employee)->where('status', 'active');
        })
        ->where('installment_month', $installmentMonth)
        ->where('status', 'pending')
        ->get();

        if (count($installments) > 0) {
            return true;

        }

        return null;
    }


    protected $guarded = [];
}

<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;
use App\Models\Payroll\Employee;

class PaySalary extends Model {

    public function items2() {

        return $this->belongsTo(Employee::class, 'employee_id');
    }

    //relation with salary process table
    public function process() {

        return $this->belongsTo(SalaryProcess::class, 'salary_process_id');
    }

    //relation with salary process table
    public function emp() {

        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function payInfo($id, $month, $year){

        // dd($id);
        $data = PaymentInformation::where('pay_salary_id',$id)->where('month',$month)->where('year',$year)->first();
        $date = $data ? $data->created_at :'';
        return $date ;
    }

    public function components($id, $month, $year){

        return SalaryProcess::where('employee_id',$id)->where('month',$month)->where('year',$year)->get();
    }

    public function deductComponents($id, $month, $year) {

        return DeductionProcess::where('employee_id',$id)->where('month',$month)->where('year',$year)->get();
    }
    public function installment_process($id, $month, $year) {

        return InstallmentProcess::where('employee_id',$id)->where('month',$month)->where('year',$year)->get();
    }

    public function overtime_late_salary_process($id, $month, $year) {

        return OvertimeLatesSalaryProcess::where('employee_id',$id)->where('month',$month)->where('year',$year)->get();
    }

    protected $guarded = [];
}

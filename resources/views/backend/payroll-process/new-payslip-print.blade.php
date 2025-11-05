@extends('layouts.backend.app-print')
@section('content')
   
<style>
    @media print{
        .printPage{
            margin-top: -700px;
        }
        html, body {
            height:100%; 
            overflow: hidden;
        }
    }
</style>
<div class="content-body">
    <!-- app invoice View Page -->
    <section class="invoice-view-wrapper">
        <div class="row">
            <!-- invoice view page -->
            <div class="col-xl-12 col-md-12 col-12">
                <div class="cardStyleChange invoice-print-area">
                    <div class="card-body">
                        <div class="text-white text-center">
                            <h5 class="bg-light ">Payslip</h5>
                            <h4>Payslip for the period of {{$salary_payment->month}} {{$salary_payment->year}}</h4>
                        </div>
                        <div class="row my-2">
                            <div class="col-lg-4 col-md-12">
                                <span class="invoice-number mr-50">Payslip No:</span>
                                <span>000756</span>
                            </div>
                            <div class="col-lg-8 col-md-12">
                                <div class="d-flex align-items-center justify-content-lg-end flex-wrap">
                                    <div>
                                        <small class="text-muted">Issue Date:</small>
                                        <span id="issueDate">08/10/2019</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <!-- invoice address and contact -->
                        <div class="row invoice-info m-1">
                            <div class="table-responsive">
                                <table class="table mb-0 table-sm table-borderless">
                                    <tbody>
                                        <tr>
                                            <td>Staff ID: </td>
                                            <td>123456</td>
                                            <td>Name:</td>
                                            <td>{{$payroll_process_info->employeeName->fname}} {{$payroll_process_info->employeeName->mname}}</td>
                                        </tr>
                                        <tr>
                                            <td>Depertment:</td>
                                            <td>Academic</td>
                                            <td>Designation:</td>
                                            <td>Teacher</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <!-- product details table-->
                    <div class="table-responsive p-2">
                        <table class="table table-borderless mb-0 table-sm">
                            <tbody>
                                <tr>
                                    <td>Payment Method</td>
                                    <td class="text-primary text-right font-weight-bold">{{$salary_payment->payoff_mehtod}}</td>
                                </tr>
                                <tr>
                                    <td>Basic Salary</td>
                                    <td class="text-primary text-right font-weight-bold">TK. {{$salary_payment->employee_salary}}</td>
                                </tr>
                                <tr>
                                    <td>Gross Salary</td>
                                    <td class="text-primary text-right font-weight-bold">TK. 00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- invoice subtotal -->
                    <div class="card-body pt-0 mx-25">
                        <hr>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-12 d-flex justify-content-end mt-75">
                                <div class="invoice-subtotal">
                                    <div class="invoice-calc d-flex justify-content-between">
                                        <span class="invoice-title">Total Salary: </span>
                                        <span class="invoice-value">AED. {{$salary_payment->employee_salary}}</span>
                                    </div>
                                    <div class="invoice-calc d-flex justify-content-between">
                                        <span class="invoice-title">Paid to date: </span>
                                        <span class="invoice-value">AED. {{$salary_payment->employee_salary_payoff}}</span>
                                    </div>
                                    <div class="invoice-calc d-flex justify-content-between border-top mt-1">
                                        <span class="invoice-title">Due: </span>
                                        <span class="invoice-value">AED. {{$salary_payment->employee_salary - $salary_payment->employee_salary_payoff}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
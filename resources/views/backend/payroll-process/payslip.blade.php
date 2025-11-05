@extends('layouts.backend.app')
@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/backend/')}}/app-assets/vendors/css/extensions/toastr.css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/backend/')}}/app-assets/css/plugins/extensions/toastr.css">
@endpush
@section('content')

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- app invoice View Page -->
                <section class="invoice-view-wrapper">
                    <div class="row">
                        <!-- invoice view page -->
                        <div class="col-xl-9 col-md-8 col-12">
                            <div class="card invoice-print-area">
                                <div class="card-body">
                                    <!-- header section -->
                                    <!-- logo and title -->
                                    <div class="row my-sm-2">
                                        <div class="col-sm-6 col-12 text-center text-sm-left order-2 order-sm-1">
                                            <h6 class="text-primary">Bangladesh English Private School</h6>
                                            <span>145 Motijheel C/A, Dhaka - 1000</span>
                                            <span>Help Line Number (Tel): 02-48317513, 02-48317519</span>
                                            <span>Email Address: vnsc.edu@gmail.com, vnsc_bd@yahoo.com</span>
                                        </div>
                                        <div class="col-sm-6 col-12 text-center text-sm-right order-1 order-sm-2 d-sm-flex justify-content-end mb-1 mb-sm-0">
                                            <img src="../../../app-assets/images/pages/pixinvent-logo.png" alt="logo" height="46" width="164">
                                        </div>
                                    </div>
                                    <div class="text-white text-center">
                                        <h5 class="bg-light ">Payslip</h5>
                                        <h4>Payslip for the period of {{$salary_payment->month}} {{$salary_payment->year}}</h4>
                                    </div>
                                    <div class="row  my-2">
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
                                    <div class="row invoice-info">
                                        <div class="table-responsive">
                                            <table class="table table-borderless">
                                                <tbody>
                                                    <tr style="outline: none;">
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
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
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
                        <!-- invoice action  -->
                        <div class="col-xl-3 col-md-4 col-12">
                            <div class="card invoice-action-wrapper shadow-none border">
                                <div class="card-body">                                    
                                    <div class="invoice-action-btn">
                                        <button class="btn btn-light-primary btn-block invoice-print" onclick="window.print()">
                                            <span>print</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
    <!-- END: Content-->

@endsection

@push('js')
<script src="{{asset('assets/backend/')}}/app-assets/vendors/js/extensions/toastr.min.js"></script>
<!-- END: Page Vendor JS-->
<script>
@if(Session::has('message'))
    var type = "{{ Session::get('alert-type', 'info') }}";
    console.log(type);
    toastr.options =
        {
            "closeButton" : true,
            "tapToDismiss": false,
        };
    switch(type){
        case 'info':
            toastr.info("{{ Session::get('message') }}","Info");
            break;

        case 'warning':
            toastr.warning("{{ Session::get('message') }}","Warning");
            break;

        case 'success':
            toastr.success("{{ Session::get('message') }}", "Success");
            break;

        case 'error':
            toastr.error("{{ Session::get('message') }}", "Error");
            break;
    }
@endif
</script>
<script>
    var today = new Date();
    var date = today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getFullYear();
    document.getElementById("issueDate").innerHTML = date;
    console.log(date);
</script>
@endpush
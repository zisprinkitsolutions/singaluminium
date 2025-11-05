

@extends('layouts.backend.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@section('content')
@include('layouts.backend.partial.style')
<style>
    .changeColStyle span{
        min-width: 16%;
    }
    .changeColStyle .select2-container--default .select2-selection--single .select2-selection__arrow b{
        display: none;
    }
    .journaCreation{
        background: #1214161c;
    }
    .transaction_type{
        padding-right:5px;
        padding-left:5px;
        padding-bottom:5px;
    }
    @media only screen and (max-width: 1500px) {
        .custome-project span{
            max-width: 140px;
        }
    }

    thead {
        background: #34465b;
        color: #fff !important;
    }
    th{
        color: #fff !important;
        font-size: 11px !important;
        height: 25px !important;
        text-align: center !important;
    }
    td
    {
        font-size: 12px !important;
        height: 25px !important;
        text-align: center !important;
    }

    .table-sm th, .table-sm td {
        padding: 0rem;
    }
    tr{
        cursor: pointer;
    }
    .dropdown-filter-content div {
        color: #727e8c !important;
    }
    
    input,
    select {
        height: 30px !important;
    }
    @media print{
        .nav.nav-tabs ~ .tab-content{
            border-left: 1px solid #fff;
            border-right: 1px solid #fff;
            border-bottom: 1px solid #fff;
            padding-left: 0;
        }
    }
</style>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.report._header',['activeMenu' => 'new_daily_report'])
            <div class="tab-content bg-white">
                <div class="tab-pane active p-2">
                    <div class="content-body">
                        <section id="widgets-Statistics">
                            <div class="d-flex">
                                <div><h5>Daily Summary</h5></div>
                                <div class="ml-auto  print-hideen">
                                    <div class="d-flex-align-items-center gap-2">
                                        <button type="button" class="btn mExcelButton formButton mr-1" title="Export" onclick="exportTableToCSV('Daily Summary-{{ date('d M Y') }}.csv')">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{asset('assets/backend/app-assets/icon/excel-icon.png')}}" width="25">
                                                </div>
                                                <div><span>Excel</span></div>
                                            </div>
                                        </button>
                                        <a href="#" class="btn btn_create mPrint formButton" title="Print"
                                            onclick="window.print()">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{ asset('assets/backend/app-assets/icon/print-icon.png') }}" width="25">
                                                </div>
                                                <div><span>Print</span></div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-1" style="margin-left: 5px !important">
                                <div class="col-md-12">
                                    <form action="" method="GET" class="d-flex row">


                                        <div class="row form-group col-md-3">
                                            <input type="text" class="form-control inputFieldHeight datepicker" placeholder="From Date/Single Date" name="date" autocomplete="off">
                                        </div>
                                        <div class="row form-group col-md-3">
                                            <input type="text" class="form-control inputFieldHeight datepicker" placeholder="To Date/Single Date" name="date2" autocomplete="off">
                                        </div>

                                        <div class="col-md-3">
                                            <button type="submit" class="btn mSearchingBotton mb-2 formButton inputFieldHeight" title="Search" >
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img src="{{asset('assets/backend/app-assets/icon/searching-icon.png')}}" width="25">
                                                    </div>
                                                    <div><span>Search</span></div>
                                                </div>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body pt-0 pb-0">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-bordered table-sm 2filter-table text-center" >
                                            <tr style="background:#34465b;color:white;">
                                                <td colspan="4">Cash Sale</td>
                                            </tr>
                                            <tr class="text-dark bg-white">
                                                <td style="width: 16%">Date</td>
                                                <td style="width: 24%">Invoice No</td>
                                                <td style="width: 40%">Party Name</td>
                                                <td style="width: 20%">Amount</td>
                                            </tr>
                                            @foreach ($today_cash_sale as $item)
                                            <tr class="sale_view" id="{{$item->id}}">
                                                <td>{{date('d/m/Y',strtotime($item->date))}}</td>
                                                <td>{{$item->invoice_no}}</td>
                                                <td>{{$item->partyInfo($item->customer_name)->pi_name}}</td>
                                                <td>{{number_format($item->paid_amount, 2)}}</td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="3" class="text-right pr-1">Total Amount</td>
                                                <td>{{number_format($today_cash_sale->sum('paid_amount'),2,'.','')}}</td>
                                            </tr>
                                        </table>
                                        <table class="table table-bordered table-sm 2filter-table text-center" >
                                            <tr style="background:#34465b;color:white;">
                                                <td colspan="4">Credit Sale</td>
                                            </tr>
                                            <tr class="text-dark bg-white">
                                                <td style="width: 16%">Date</td>
                                                <td style="width: 24%">Invoice No</td>
                                                <td style="width: 40%">Party Name</td>
                                                <td style="width: 20%">Amount</td>
                                            </tr>
                                            @foreach ($today_credit_sale as $item)
                                                <tr class="sale_view" id="{{$item->id}}">
                                                    <td>{{date('d/m/Y',strtotime($item->date))}}</td>
                                                    <td>{{$item->invoice_no}}</td>
                                                    <td>{{$item->partyInfo($item->customer_name)->pi_name}}</td>
                                                    <td>{{number_format($item->due_amount, 2)}}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="3" class="text-right pr-1">Total Amount</td>
                                                <td>{{number_format($today_credit_sale->sum('due_amount'),2,'.','')}}</td>
                                            </tr>
                                        </table>
                                        <table class="table table-bordered table-sm 2filter-table text-center" >
                                            <tr style="background:#34465b;color:white;">
                                                <td colspan="4">Cash Recipt</td>
                                            </tr>
                                                <tr class="text-dark bg-white">
                                                    <td style="width: 16%">Date</td>
                                                    <td style="width: 24%">Invoice No</td>
                                                    <td style="width: 40%">Party Name</td>
                                                    <td style="width: 20%">Amount</td>
                                                </tr>
                                                <tbody id="purch-body">
                                                @foreach ($previous_receipt as $item)
                                                <tr class="receipt_exp_view"  id="{{$item->payment_id}}">
                                                    <td>{{date('d/m/Y',strtotime($item->receipt->date))}}</td>
                                                    <td>{{$item->receipt->receipt_no}}</td>
                                                    <td>{{$item->partyInfo->pi_name}}</td>
                                                    <td>{{number_format($item->Total_amount, 2)}}</td>
                                                </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="3" class="text-right pr-1">Total Amount</td>
                                                    <td>{{number_format($previous_receipt->sum('Total_amount'),2,'.','')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-bordered table-sm 2filter-table text-center" >
                                            <tr style="background:#34465b;color:white;">
                                                <td colspan="4">Cash Expense</td>
                                            </tr>
                                                <tr class="text-dark bg-white">
                                                    <td style="width: 16%">Date</td>
                                                    <td style="width: 24%">Invoice No</td>
                                                    <td style="width: 40%">Party Name</td>
                                                    <td style="width: 20%">Amount</td>
                                                </tr>
                                                <tbody id="purch-body">
                                                @foreach ($today_cash_expense as $item)
                                                    <tr class="purchase_view" id="{{$item->id}}">
                                                        <td>{{date('d/m/Y',strtotime($item->date))}}</td>
                                                        <td>{{$item->exp_code}}</td>
                                                        <td>{{$item->party->pi_name}}</td>
                                                        <td>{{number_format($item->total_amount	, 2)}}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="3" class="text-right pr-1">Total Amount</td>
                                                    <td>{{number_format($today_cash_expense->sum('total_amount'),2,'.','')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class="table table-bordered table-sm 2filter-table text-center" >
                                            <tr style="background:#34465b;color:white;">
                                                <td colspan="4">Credit Expense</td>
                                            </tr>
                                                <tr class="text-dark bg-white">
                                                    <td style="width: 16%">Date</td>
                                                    <td style="width: 24%">Invoice No</td>
                                                    <td style="width: 40%">Party Name</td>
                                                    <td style="width: 20%">Amount</td>
                                                </tr>
                                                <tbody id="purch-body">
                                                @foreach ($today_credit_expense as $item)
                                                    <tr class="expense_view" id="{{$item->id}}" data-url="{{ route('expenses.edit', $item->id) }}">
                                                        <td>{{date('d/m/Y',strtotime($item->date))}}</td>
                                                        <td>{{$item->exp_code}}</td>
                                                        <td>{{$item->party->pi_name}}</td>
                                                        <td>{{number_format($item->total_amount	, 2)}}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="3" class="text-right pr-1">Total Amount</td>
                                                    <td>{{number_format($today_credit_expense->sum('total_amount'),2,'.','')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        
                                        <table class="table table-bordered table-sm 2filter-table text-center" >
                                            <tr style="background:#34465b;color:white;">
                                                <td colspan="4">Cash Payment</td>
                                            </tr>
                                                <tr class="text-dark bg-white">
                                                    <td style="width: 16%">Date</td>
                                                    <td style="width: 24%">Invoice No</td>
                                                    <td style="width: 40%">Party Name</td>
                                                    <td style="width: 20%">Amount</td>
                                                </tr>
                                                <tbody id="purch-body">
                                                @foreach ($previous_payment as $item)
                                                <tr r class="payment_exp_view" id="{{$item->payment->id}}">
                                                    <td>{{date('d/m/Y',strtotime($item->payment->date))}}</td>
                                                    <td>{{$item->payment->payment_no}}</td>
                                                    <td>{{$item->party?$item->party->pi_name:''}}</td>
                                                    <td>{{number_format($item->total_amount, 2)}}</td>
                                                </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="3" class="text-right pr-1">Total Amount</td>
                                                    <td>{{number_format($previous_payment->sum('total_amount'),2,'.','')}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-bordered table-sm 2filter-table text-center" >
                                            <tr style="background:#34465b;color:white;">
                                                <td colspan="3">Overall Cash Receipt</td>
                                                <td>{{number_format($today_receipt->sum('Total_amount')+$previous_receipt->sum('Total_amount'),2,'.','')}}</td>
                                            </tr>
                                            <tr style="background:#34465b;color:white;">
                                                <td colspan="3">Overall Cash Payment</td>
                                                <td>{{number_format($today_payment->sum('total_amount')+$previous_payment->sum('total_amount'),2,'.','')}}</td>
                                            </tr> 
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div id="voucherPreviewShow">

        </div>
      </div>
    </div>
</div>
@endsection
@push('js')
    <script>
        
        $(document).on("click", ".sale_view", function(e) {
            e.preventDefault();
            var id= $(this).attr('id');
            $.ajax({
                url: "{{route('sale-modal')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response){
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                }
            });
        });
        $(document).on("click", ".expense_view", function(e) {
            e.preventDefault();
            var that = $(this);
            var urls = that.attr("data-url");

            $.ajax({
                url: urls,
                type: 'GET',
                cache: false,
                success: function(response) {
                    $("#voucherPreviewShow").empty().append(response);
                    $("#voucherPreviewModal").modal('show');
                },
                error: function(xhr) {
                    console.log('Something went wrong: ' + xhr.statusText);
                }
            });
        });
        $(document).on("click", ".receipt_exp_view", function(e) {
            e.preventDefault();
            var id= $(this).attr('id');
            $.ajax({
                url: "{{URL('receipt-list-modal')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response){
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                    $(".datepicker").datepicker({
                    dateFormat: "dd/mm/yy"
                });
                }
            });
        });
    </script>
@endpush
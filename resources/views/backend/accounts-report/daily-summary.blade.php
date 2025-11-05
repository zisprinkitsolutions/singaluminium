@extends('layouts.backend.app')
@php
    $company_name= \App\Setting::where('config_name', 'company_name')->first();
    $company_address= \App\Setting::where('config_name', 'company_address')->first();
    $address2= \App\Setting::where('config_name', 'address2')->first();
    $company_tele= \App\Setting::where('config_name', 'company_tele')->first();
    $company_email= \App\Setting::where('config_name', 'company_email')->first();
    $trn_no= \App\Setting::where('config_name', 'trn_no')->first();
    $i=1;
@endphp
@push('css')
@include('layouts.backend.partial.style')
<style>
    .pluseMinuseIcon.collapsed::before{
        content: "\f067";;
        cursor: pointer;
        border: 1px solid rgb(123, 123, 123);
    }
    .pluseMinuseIcon::before {
        font-family: 'FontAwesome';
        content: "\f068";
        cursor: pointer;
        border: 1px solid rgb(123, 123, 123);
    }

    .rowStyle{
        cursor: pointer;
        border-left: dotted;
        padding: 3px;
        margin-bottom: 2px;
    }
    .findMasterAcc{
        cursor: pointer;
    }
    /* ==========My Code========== */
    .bg-secondary {
        background-color: #34465b !important;
        color:white  !important;
        padding: 2px 5px 2px 5px !important;
    }
    a.bg-secondary:hover, a.bg-secondary:focus,
    button.bg-secondary:hover,
    button.bg-secondary:focus {
        background-color: #475f7b30 !important;
        color:black!important;
    }
    tr:nth-child(even) {
        background-color: #c8d6e357;
    }
    a.text-dark:hover, a.text-dark:focus {
        color: #ffffff !important;
    }
    .btn-outline-secondary {
        border-radius: 40px;
        padding: 0.2px 9px 0.2px 9px !important;
    }
    thead{
        background: transparent !important;
        color: #ffffff;
    }
    .page-header h2,
    .page-header h4{
        color: #333333 !important;
        font-weight: 400;
    }
    .page-header h2{
        letter-spacing: 0.5px;
    }
    #summery-table .table-heading-title{
        color: #333333 !important;
        font-weight: 500 !important;
        letter-spacing: 0.3 !important;
        font-size: 16px !important;
        line-height: 25px !important;
        padding-left:10px !important;
        text-transform: capitalize !important;
        background: transparent !important;
    }
    #summery-table{
        border-collapse: collapse;
        border:1px solid #999999;
    }
    #summery-table th,
    #summery-table td{
        text-align: center;
        border: 1px solid #999999;
        color: #333333 !important;
        font-weight: 400;
        font-size:14px !important;
    }
    .accordion .pluseMinuseIcon.collapsed::before{
        content: "\f067";;
        cursor: pointer;
        border: 1px solid rgb(123, 123, 123);
    }
    .accordion .pluseMinuseIcon::before {
        font-family: 'FontAwesome';
        content: "\f068";
        cursor: pointer;
        border: 1px solid rgb(123, 123, 123);
    }
    @media print{
        .thermal-print2{
            display: none !important;
        }
        .thermal-table-add{
            width: 75px !important;
            font-size: 10px;
            max-width: 75px !important;
        }
        .dropdown-filter-dropdown{
            display: none;
        }
        .nav.nav-tabs ~ .tab-content {
            border-left: 1px solid #fff !important;
            border-right: 1px solid #fff !important;
            border-bottom: 1px solid #fff !important;
            padding-left: 0;
        }
        .thermal-header-print2 {
            width: 175px !important;
            min-width: 300px !important;
            text-align: center !important;
            align-content: center !important;
        }
        .print-header-tityle-style-thermal{
            font-size:13px;
            text-align: center !important;
        }
        .print-address-style-thermal{
            font-size:9px;
            text-align: center !important;
        }
        @page {
            margin: 0px !important;
            padding: 0px !important;
        }
        .collapse.multi-collapse {
            display: table-row !important; /* or block depending on layout */
            visibility: visible !important;
            height: auto !important;
        }

        .pluseMinuseIcon {
            display: none !important; /* or inline-block if needed */
        }
        .cardStyleChange{
            display: none !important;
        }
        .text-white{
            color: #333333 !important;
        }
        td{
            color: #333333 !important;
        }
        li{
            color: #333333 !important;
        }
        .col-md-6,
        .col-sm-6,
        .col-6 {
            width: 100% !important;
            max-width: 100% !important;
            flex: 0 0 100% !important;
        }

        .row {
            display: flex !important;
            flex-wrap: wrap !important;
        }
    }
</style>
@endpush
@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.report._header', [ 'activeMenu' => 'daily-summary'])
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active">
                    <section id="widgets-Statistics ">

                        <div class="cardStyleChange">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div>
                                        {{-- <a href="#" class="btn btn_create mPrint formButton" title="Print" onclick="window.print()">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{asset('assets/backend/app-assets/icon/print-icon.png')}}" width="25">
                                                </div>
                                                <div><span>Print</span></div>
                                            </div>
                                        </a> --}}
                                    </div>
                                </div>
                                <div class="row ml-1">
                                    <div class="col-md-3 pl-0">
                                        <form action="" method="GET" class="d-flex row">
                                            <div class="row form-group col-md-8 ml-1" style="padding-left:7px;">
                                                <input type="text"
                                                    class="inputFieldHeight form-control datepicker" name="date"
                                                    placeholder="Select Date" required autocomplete="off">
                                            </div>
                                            <div class="col-md-4 mr-0 pl-0">
                                                <button type="submit" class="btn mSearchingBotton mb-2 ml-1 formButton" style="padding: 3px 5px;"
                                                    title="Search">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                width="25">
                                                        </div>
                                                        <div><span>Search</span></div>
                                                    </div>
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-4 ">
                                        <form action="" method="GET" class="d-flex row">
                                            <div class="row form-group col-md-4 mr-0 pr-0">
                                                <input type="text"
                                                    class="inputFieldHeight form-control datepicker" name="from"
                                                    placeholder="From Date" required autocomplete="off">
                                            </div>
                                            <div class="row form-group col-md-4 ml-1 p-0 mr-0 pr-0">
                                                <input type="text"
                                                    class="inputFieldHeight form-control datepicker" name="to"
                                                    placeholder="To Date" required autocomplete="off">
                                            </div>
                                            <div class="col-md-2 pl-0">
                                                <button type="submit" class="btn mSearchingBotton mb-2 ml-1 formButton"style="padding: 3px 5px;"
                                                    title="Search">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                width="25">
                                                        </div>
                                                        <div><span>Search</span></div>
                                                    </div>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-3 col-padding-right pl-0">
                                        <a href="#" onclick="window.print()"style="padding: 5px 5px;"
                                            class="btn btn-icon btn-secondary"><i class="bx bx-printer"></i>
                                            Print</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>

                            <div class="card-body pt-0 pb-0 daily-summery ">
                                @include('layouts.backend.partial.modal-header-info')
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2 class="text-center daily-summery-print-style ">Daily Summary</h2>
                                        <h2 class="text-center col-6">
                                            <h4 class="text-center daily-summery-print-style ">
                                                @if ($from && $to)
                                                    Date:From {{date('d/m/Y', strtotime($from))}} To {{date('d/m/Y', strtotime($to))}}
                                                @else
                                                    Date: {{date('d/m/Y', strtotime($date))}}
                                                @endif
                                            </h4>
                                        </h2>
                                    </div>
                                </div>
                                @php
                                    $cash_other_cost = $fund_allocation->where('account_id_from', 1)->sum('transaction_cost');
                                        $bank_other_cost = $fund_allocation->where('account_id_from', 7)->sum('transaction_cost');
                                        $petty_cash_other_cost = $fund_allocation->where('account_id_from', 6)->sum('transaction_cost');

                                        $transfer_to_cash_from_bank = $fund_allocation->where('account_id_to', 1)->where('account_id_from', 7)->sum('amount');
                                        $transfer_to_cash_from_petty_cash = $fund_allocation->where('account_id_to', 1)->where('account_id_from',
                                        6)->sum('amount');
                                        $transfer_from_cash_to_bank = $fund_allocation->where('account_id_from', 1)->where('account_id_to', 7)->sum('amount');
                                        $transfer_from_cash_to_petty_cash = $fund_allocation->where('account_id_from', 1)->where('account_id_to',
                                        6)->sum('amount');

                                        $transfer_to_bank_from_petty_cash = $fund_allocation->where('account_id_to', 7)->where('account_id_from',
                                        6)->sum('amount');
                                        $transfer_from_bank_to_petty_cash = $fund_allocation->where('account_id_from', 7)->where('account_id_to',
                                        6)->sum('amount');
                                        $transfer_to_bank_from_visa_card = $fund_allocation->where('account_id_from', 0)->where('account_id_to',
                                        0)->sum('amount');
                                        $transfer_from_bank_to_visa_card = $fund_allocation->where('account_id_from', 0)->where('account_id_to',
                                        0)->sum('amount');

                                        $today_payment_visa_card = $today_payments->where('pay_mode', 'VISA Card')->where('type', 'due')->sum('total_amount');
                                        $previous_payment_visa_card = $previous_payments->where('pay_mode', 'VISA Card')->where('type',
                                        'due')->sum('total_amount');
                                        $today_payment_petty_cash = $today_payments->where('pay_mode', 'Petty Cash')->where('type', 'due')->sum('total_amount');
                                        $previous_payment_petty_cash = $previous_payments->where('pay_mode', 'Petty Cash')->where('type',
                                        'due')->sum('total_amount');

                                        $opening_balance_receipt_visa_card = $previous_fund_allocation->where('account_id_to', 0)->sum('amount');
                                        $opening_balance_payment_visa_card = $previous_fund_allocation->where('account_id_from', 0)->sum('amount');
                                        $opening_balance_receipt_petty_cash = $previous_fund_allocation->where('account_id_to', 6)->sum('amount');
                                        $opening_balance_payment_petty_cash = $previous_fund_allocation->where('account_id_from', 6)->sum('amount');
                                        $opening_balance_receipt_cash_fund = $previous_fund_allocation->where('account_id_to', 1)->sum('amount');
                                        $opening_balance_payment_cash_fund = $previous_fund_allocation->where('account_id_from', 1)->sum('amount');
                                        $opening_balance_receipt_bank_fund = $previous_fund_allocation->where('account_id_to', 6)->sum('amount');
                                        $opening_balance_payment_bank_fund = $previous_fund_allocation->where('account_id_from', 6)->sum('amount');
                                @endphp
                                {{-- chash --}}
                                <div class="row mt-1">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-hover ">
                                            <tr>
                                                <th colspan="5" class="text-left bg-secondary text-white pl-1">Today's Cash Summary </th>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1 d-flex">Opening Balance </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($opening_balance_cash,2)}}</td>
                                            </tr>
                                            {{-- Today's Cash Sales Received --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_today_cash_sale_receipts" aria-controls="collapse_today_cash_sale_receipts" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Today's Cash Sales Received</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($today_cash_sale_receipts->sum('total_amount'),2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_today_cash_sale_receipts" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Invoice No</td>
                                                            <td>Receipt No</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($today_cash_sale_receipts as $sale_receipt)
                                                            <tr>
                                                                <td>{{date('d/m/Y', strtotime($sale_receipt->payment->date))}}</td>
                                                                <td class="sale_view" id="{{$sale_receipt->invoice->id}}">{{$sale_receipt->invoice->invoice_no?$sale_receipt->invoice->invoice_no:$sale_receipt->invoice->proforma_invoice_no}}</td>
                                                                <td class="receipt_exp_view" id="{{$sale_receipt->payment->id}}">{{$sale_receipt->payment->receipt_no}}</td>
                                                                <td class="text-right pr-1">{{number_format($sale_receipt->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Today's Cash Payment/Expense --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_today_cash_payment_expense" aria-controls="collapse_today_cash_payment_expense" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;"> Today's Cash Payment/Expense</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($today_cash_bill_payments->sum('amount'),2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_today_cash_payment_expense" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Expense No</td>
                                                            <td>Payment No</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($today_cash_bill_payments as $cash_bill_payment)
                                                            <tr>
                                                                <td>{{date('d/m/Y', strtotime($cash_bill_payment->payment->date))}}</td>
                                                                <td class="purch_exp_view" id="{{ $cash_bill_payment->purchase->id }}">{{$cash_bill_payment->purchase->purchase_no}}</td>
                                                                <td class="payment_view" id="{{$cash_bill_payment->payment->id}}">{{$cash_bill_payment->payment->payment_no}}</td>
                                                                <td class="text-right pr-1">{{number_format($cash_bill_payment->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Previous Accounts Receivable Received --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_previous_cash_sale_receipts" aria-controls="collapse_previous_cash_sale_receipts" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Previous Accounts Receivable Received</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($previous_cash_sale_receipts->sum('amount'),2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_previous_cash_sale_receipts" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Invoice No</td>
                                                            <td>Receipt No</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($previous_cash_sale_receipts as $sale_receipt)
                                                            <tr>
                                                                <td>{{date('d/m/Y', strtotime($sale_receipt->payment->date))}}</td>
                                                                <td class="sale_view" id="{{$sale_receipt->invoice->id}}">{{$sale_receipt->invoice->invoice_no?$sale_receipt->invoice->invoice_no:$sale_receipt->invoice->proforma_invoice_no}}</td>
                                                                <td class="receipt_exp_view" id="{{$sale_receipt->payment->id}}">{{$sale_receipt->payment->receipt_no}}</td>
                                                                <td class="text-right pr-1">{{number_format($sale_receipt->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Previous Account Payable Paymen --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_previous_cash_bill_payments" aria-controls="collapse_previous_cash_bill_payments" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;"> Previous Account Payable Payment</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($previous_cash_bill_payments->sum('amount'),2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_previous_cash_bill_payments" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Expense No</td>
                                                            <td>Payment No</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($previous_cash_bill_payments as $cash_bill_payment)
                                                            <tr>
                                                                <td>{{date('d/m/Y', strtotime($cash_bill_payment->payment->date))}}</td>
                                                                <td class="purch_exp_view" id="{{ $cash_bill_payment->purchase->id }}">{{$cash_bill_payment->purchase->purchase_no}}</td>
                                                                <td class="payment_view" id="{{$cash_bill_payment->payment->id}}">{{$cash_bill_payment->payment->payment_no}}</td>
                                                                <td class="text-right pr-1">{{number_format($cash_bill_payment->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Advance Received --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_today_advance_cash_received" aria-controls="collapse_today_advance_cash_received" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Advance Received</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($today_advance_cash_received->sum('total_amount'),2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_today_advance_cash_received" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Receipt No</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($today_advance_cash_received as $advance_receipt)
                                                            <tr>
                                                                <td>{{date('d/m/Y', strtotime($advance_receipt->date))}}</td>
                                                                <td class="receipt_exp_view" id="{{$advance_receipt->id}}">{{$advance_receipt->receipt_no}}</td>
                                                                <td class="text-right pr-1">{{number_format($advance_receipt->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- advance payment --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_today_advance_cash_payment" aria-controls="collapse_today_advance_cash_payment" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;"> Advance Payment</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($today_advance_cash_payment->sum('amount'),2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_today_advance_cash_payment" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Payment No</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($today_advance_cash_payment as $cash_bill_payment)
                                                            <tr>
                                                                <td>{{date('d/m/Y', strtotime($cash_bill_payment->date))}}</td>
                                                                <td class="payment_view" id="{{$cash_bill_payment->id}}">{{$cash_bill_payment->payment_no}}</td>
                                                                <td class="text-right pr-1">{{number_format($cash_bill_payment->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Other Cash Received --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_other_cash_received" aria-controls="collapse_other_cash_received" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Other Cash Received</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">0.00</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_other_cash_received" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Receipt No</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- other Cash Payment --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_cash_other_cost" aria-controls="collapse_cash_other_cost" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;"> Other Cash Payment</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($cash_other_cost,2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_cash_other_cost" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Note</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($fund_allocation->where('account_id_from', 1) as $item)
                                                            <tr class="allocation-show" id="{{ $item->id }}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->note}}</td>
                                                                <td>{{number_format($item->transaction_cost,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Transferred to Cash from Bank --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_transfer_to_cash_from_bank" aria-controls="collapse_transfer_to_cash_from_bank" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Transferred to Cash from Bank</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($transfer_to_cash_from_bank,2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_transfer_to_cash_from_bank" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>To Account</td>
                                                            <td>From Account</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($fund_allocation->where('account_id_to', 1)->where('account_id_from', 7) as $item)
                                                            <tr class="allocation-show" id="{{ $item->id }}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->fromAccount->title}}</td>
                                                                <td>{{$item->toAccount->title}}</td>
                                                                <td>{{number_format($item->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Transferred to Cash from Petty Cash --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_transfer_to_cash_from_petty_cash" aria-controls="collapse_transfer_to_cash_from_petty_cash" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Transferred to Cash from Petty Cash</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($transfer_to_cash_from_petty_cash,2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_transfer_to_cash_from_petty_cash" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>To Account</td>
                                                            <td>From Account</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($fund_allocation->where('account_id_to', 1)->where('account_id_from', 6) as $item)
                                                            <tr class="allocation-show" id="{{ $item->id }}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->fromAccount->title}}</td>
                                                                <td>{{$item->toAccount->title}}</td>
                                                                <td>{{number_format($item->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Transferred from Cash to Bank --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_transfer_from_cash_to_bank" aria-controls="collapse_transfer_from_cash_to_bank" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Transferred from Cash to Bank</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($transfer_from_cash_to_bank,2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_transfer_from_cash_to_bank" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>To Account</td>
                                                            <td>From Account</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($fund_allocation->where('account_id_to', 1)->where('account_id_from', 6) as $item)
                                                            <tr class="allocation-show" id="{{ $item->id }}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->fromAccount->title}}</td>
                                                                <td>{{$item->toAccount->title}}</td>
                                                                <td>{{number_format($item->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Transferred from Cash to Petty Cash --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_transfer_from_cash_to_petty_cash" aria-controls="collapse_transfer_from_cash_to_petty_cash" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Transferred from Cash to Petty Cash</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($transfer_from_cash_to_petty_cash,2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_transfer_from_cash_to_petty_cash" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>To Account</td>
                                                            <td>From Account</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($fund_allocation->where('account_id_to', 6)->where('account_id_from', 1) as $item)
                                                            <tr class="allocation-show" id="{{ $item->id }}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->fromAccount->title}}</td>
                                                                <td>{{$item->toAccount->title}}</td>
                                                                <td>{{number_format($item->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right pr-1"><strong>Today's Cash Total</strong></td>
                                                <td style="width: 25% !important;" class="text-right pr-1">
                                                    {{number_format(($opening_balance_cash+$today_cash_sale_receipts->sum('total_amount')+$previous_cash_sale_receipts->sum('total_amount')+$today_advance_cash_received->sum('total_amount')+$transfer_to_cash_from_bank+$transfer_to_cash_from_petty_cash)
                                                        - ($cash_other_cost+$today_cash_bill_payments->sum('total_amount')+$previous_cash_bill_payments->sum('total_amount')+$today_advance_cash_payment->sum('total_amount')+$transfer_from_cash_to_bank+$transfer_from_cash_to_petty_cash),2)}}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                {{-- bank --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-hover ">
                                            <tr>
                                                <th colspan="5" class="text-left bg-secondary text-white pl-1">Today's Bank Summary</th>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1 d-flex">Opening Balance</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($opening_balance_bank ,2)}}</td>
                                            </tr>
                                            {{-- Today's Bank Sales Received --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_today_bank_sale_receipts" aria-controls="collapse_today_bank_sale_receipts" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Today's Bank Sales Received</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($today_bank_sale_receipts->sum('amount'),2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_today_bank_sale_receipts" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Invoice No</td>
                                                            <td>Receipt No</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($today_bank_sale_receipts as $sale_receipt)
                                                            <tr>
                                                                <td>{{date('d/m/Y', strtotime($sale_receipt->payment->date))}}</td>
                                                                <td class="sale_view" id="{{$sale_receipt->invoice->id}}">{{$sale_receipt->invoice->invoice_no?$sale_receipt->invoice->invoice_no:$sale_receipt->invoice->proforma_invoice_no}}</td>
                                                                <td class="receipt_exp_view" id="{{$sale_receipt->payment->id}}">{{$sale_receipt->payment->receipt_no}}</td>
                                                                <td class="text-right pr-1">{{number_format($sale_receipt->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Today's Bank Payment/Expense --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_today_bank_payment_expense" aria-controls="collapse_today_bank_payment_expense" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;"> Today's Bank Payment/Expense</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($today_bank_bill_payments->sum('amount'),2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_today_bank_payment_expense" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Expense No</td>
                                                            <td>Payment No</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($today_bank_bill_payments as $cash_bill_payment)
                                                            <tr>
                                                                <td>{{date('d/m/Y', strtotime($cash_bill_payment->payment->date))}}</td>
                                                                <td class="purch_exp_view" id="{{ $item->id }}">{{$cash_bill_payment->purchase->purchase_no}}</td>
                                                                <td class="payment_view" id="{{$cash_bill_payment->payment->id}}">{{$cash_bill_payment->payment->payment_no}}</td>
                                                                <td class="text-right pr-1">{{number_format($cash_bill_payment->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Previous Accounts Receivable Received --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_previous_bank_sale_receipts" aria-controls="collapse_previous_bank_sale_receipts" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Previous Accounts Receivable Received</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($previous_bank_sale_receipts->sum('amount'),2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_previous_bank_sale_receipts" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Invoice No</td>
                                                            <td>Receipt No</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($previous_bank_sale_receipts as $sale_receipt)
                                                            <tr>
                                                                <td>{{date('d/m/Y', strtotime($sale_receipt->payment->date))}}</td>
                                                                <td class="sale_view" id="{{$sale_receipt->invoice->id}}">{{$sale_receipt->invoice->invoice_no?$sale_receipt->invoice->invoice_no:$sale_receipt->invoice->proforma_invoice_no}}</td>
                                                                <td class="receipt_exp_view" id="{{$sale_receipt->payment->id}}">{{$sale_receipt->payment->receipt_no}}</td>
                                                                <td class="text-right pr-1">{{number_format($sale_receipt->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Previous Account Payable Paymen --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_previous_bank_bill_payments" aria-controls="collapse_previous_bank_bill_payments" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;"> Previous Account Payable Payment</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($previous_bank_bill_payments->sum('amount'),2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_previous_bank_bill_payments" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Expense No</td>
                                                            <td>Payment No</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($previous_bank_bill_payments as $cash_bill_payment)
                                                            <tr>
                                                                <td>{{date('d/m/Y', strtotime($cash_bill_payment->payment->date))}}</td>
                                                                <td class="purch_exp_view" id="{{ $cash_bill_payment->id }}">{{$cash_bill_payment->purchase->purchase_no}}</td>
                                                                <td class="payment_view" id="{{$cash_bill_payment->payment->id}}">{{$cash_bill_payment->payment->payment_no}}</td>
                                                                <td class="text-right pr-1">{{number_format($cash_bill_payment->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Advance Received --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_today_advance_bank_received" aria-controls="collapse_today_advance_bank_received" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Advance Received</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($today_advance_bank_received->sum('total_amount'),2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_today_advance_bank_received" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Receipt No</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($today_advance_bank_received as $advance_receipt)
                                                            <tr>
                                                                <td>{{date('d/m/Y', strtotime($advance_receipt->date))}}</td>
                                                                <td class="receipt_exp_view" id="{{$advance_receipt->id}}">{{$advance_receipt->receipt_no}}</td>
                                                                <td class="text-right pr-1">{{number_format($advance_receipt->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- advance payment --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_today_advance_bank_payment" aria-controls="collapse_today_advance_bank_payment" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;"> Advance Payment</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($today_advance_bank_payment->sum('amount'),2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_today_advance_bank_payment" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Payment No</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($today_advance_bank_payment as $cash_bill_payment)
                                                            <tr>
                                                                <td>{{date('d/m/Y', strtotime($cash_bill_payment->date))}}</td>
                                                                <td class="payment_view" id="{{$cash_bill_payment->id}}">{{$cash_bill_payment->payment_no}}</td>
                                                                <td class="text-right pr-1">{{number_format($cash_bill_payment->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Other Bank Received --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_other_bank_received" aria-controls="collapse_other_bank_received" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Other Bank Received</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">0.00</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_other_bank_received" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Receipt No</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- other Bank Payment --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_bank_other_cost" aria-controls="collapse_bank_other_cost" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;"> Other Bank Payment</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($bank_other_cost,2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_bank_other_cost" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Note</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($fund_allocation->where('account_id_from', 7) as $item)
                                                            <tr class="allocation-show" id="{{ $item->id }}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->note}}</td>
                                                                <td>{{number_format($item->transaction_cost,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Transferred to Bank from Bank --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_transfer_from_cash_to_bank" aria-controls="collapse_transfer_from_cash_to_bank" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Transferred to Bank from Bank</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($transfer_from_cash_to_bank,2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_transfer_from_cash_to_bank" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>To Account</td>
                                                            <td>From Account</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($fund_allocation->where('account_id_to', 1)->where('account_id_from', 6) as $item)
                                                            <tr class="allocation-show" id="{{ $item->id }}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->fromAccount->title}}</td>
                                                                <td>{{$item->toAccount->title}}</td>
                                                                <td>{{number_format($item->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Transferred to Bank from Petty Bank --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_transfer_to_bank_from_petty_cash" aria-controls="collapse_transfer_to_bank_from_petty_cash" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Transferred to Bank from Petty Cash</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($transfer_to_bank_from_petty_cash,2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_transfer_to_bank_from_petty_cash" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>To Account</td>
                                                            <td>From Account</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($fund_allocation->where('account_id_to', 6)->where('account_id_from', 6) as $item)
                                                            <tr class="allocation-show" id="{{ $item->id }}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->fromAccount->title}}</td>
                                                                <td>{{$item->toAccount->title}}</td>
                                                                <td>{{number_format($item->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Transferred from Bank to Bank --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_transfer_to_cash_from_bank1" aria-controls="collapse_transfer_to_cash_from_bank1" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Transferred from Bank to Cash</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($transfer_to_cash_from_bank,2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_transfer_to_cash_from_bank1" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>To Account</td>
                                                            <td>From Account</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($fund_allocation->where('account_id_to', 1)->where('account_id_from', 6) as $item)
                                                            <tr class="allocation-show" id="{{ $item->id }}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->fromAccount->title}}</td>
                                                                <td>{{$item->toAccount->title}}</td>
                                                                <td>{{number_format($item->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Transferred from Bank to Petty Cash --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_transfer_from_bank_to_petty_cash0" aria-controls="collapse_transfer_from_bank_to_petty_cash0" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Transferred from Bank to Petty Cash</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($transfer_from_bank_to_petty_cash,2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_transfer_from_bank_to_petty_cash0" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>To Account</td>
                                                            <td>From Account</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($fund_allocation->where('account_id_to', 6)->where('account_id_from', 7) as $item)
                                                            <tr class="allocation-show" id="{{ $item->id }}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->fromAccount->title}}</td>
                                                                <td>{{$item->toAccount->title}}</td>
                                                                <td>{{number_format($item->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right pr-1"><strong>Today's Bank Total</strong></td>
                                                <td style="width: 25% !important;" class="text-right pr-1">
                                                    {{number_format(
                                                        ($opening_balance_bank+$today_bank_sale_receipts->sum('total_amount')+$previous_bank_sale_receipts->sum('total_amount')+$today_advance_bank_received->sum('total_amount')+$transfer_from_cash_to_bank+$transfer_to_bank_from_petty_cash+$transfer_to_bank_from_visa_card)
                                                        -
                                                        ($bank_other_cost+$today_bank_bill_payments->sum('total_amount')+$previous_bank_bill_payments->sum('total_amount')+$today_advance_bank_payment->sum('total_amount')+$transfer_to_cash_from_bank+$transfer_from_bank_to_petty_cash+$transfer_from_bank_to_visa_card)
                                                    ,2)}}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                {{-- card --}}
                                <div class="row d-none">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-hover ">
                                            <tr>
                                                <th colspan="5" class="text-left bg-secondary text-white pl-1"> Today's VISA Card Summary</th>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1 d-flex">Opening Balance</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($opening_balance_visa_card = $opening_balance_receipt_visa_card-$opening_balance_payment_visa_card,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="today-payment-expense/VISA Card">
                                                <td class="text-left pl-1 d-flex">Today's VISA Card Payment/Expense</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($today_payment_visa_card,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="previous-payment-expense/VISA Card">
                                                <td class="text-left pl-1 d-flex">Previous VISA Card Payment/Expense</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($previous_payment_visa_card,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="fund-transfer/6/4">
                                                <td class="text-left pl-1 d-flex">Transferred from VISA Card to Bank</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($transfer_to_bank_from_visa_card,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="fund-transfer/6/4">
                                                <td class="text-left pl-1 d-flex">Transferred to VISA Card from Bank</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($transfer_from_bank_to_visa_card,2)}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-right pr-1"><strong>Today's VISA Card Total</strong></td>
                                                <td style="width: 25% !important;" class="text-right pr-1">
                                                    {{number_format(($opening_balance_visa_card+$transfer_from_bank_to_visa_card)
                                                        - ($today_payment_visa_card+$previous_payment_visa_card+$transfer_to_bank_from_visa_card),2)}}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                {{-- petty cash --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-hover ">
                                            <tr>
                                                <th colspan="5" class="text-left bg-secondary text-white pl-1">Today's Petty Cash Summary</th>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1 d-flex">Opening Balance</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($opening_balance_petty_cash = $opening_balance_pettycash,2)}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_today_payment_petty_cash" aria-controls="collapse_today_payment_petty_cash" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Today's Petty Cash Payment/Expense</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($today_payment_petty_cash,2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_today_payment_petty_cash" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Expense No</td>
                                                            <td>Payment No</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($today_payments->where('pay_mode', 'Petty Cash')->where('type', 'due') as $petty_cash_bill_payment)
                                                            <tr>
                                                                <td>{{date('d/m/Y', strtotime($petty_cash_bill_payment->payment->date))}}</td>
                                                                <td class="purch_exp_view" id="{{ $item->id }}">{{$petty_cash_bill_payment->purchase->purchase_no}}</td>
                                                                <td class="payment_view" id="{{$petty_cash_bill_payment->payment->id}}">{{$petty_cash_bill_payment->payment->payment_no}}</td>
                                                                <td class="text-right pr-1">{{number_format($petty_cash_bill_payment->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_previous_payment_petty_cash" aria-controls="collapse_previous_payment_petty_cash" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Previous Petty Cash Payment/Expense</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($previous_payment_petty_cash,2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_previous_payment_petty_cash" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Expense No</td>
                                                            <td>Payment No</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($previous_payments->where('pay_mode', 'Petty Cash')->where('type', 'due') as $petty_cash_bill_payment)
                                                            <tr>
                                                                <td>{{date('d/m/Y', strtotime($petty_cash_bill_payment->payment->date))}}</td>
                                                                <td class="purch_exp_view" id="{{ $item->id }}">{{$petty_cash_bill_payment->purchase->purchase_no}}</td>
                                                                <td class="payment_view" id="{{$petty_cash_bill_payment->payment->id}}">{{$petty_cash_bill_payment->payment->payment_no}}</td>
                                                                <td class="text-right pr-1">{{number_format($petty_cash_bill_payment->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_transfer_to_cash_from_petty_cash3" aria-controls="collapse_transfer_to_cash_from_petty_cash3" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Transferred from Petty Cash to Cash</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($transfer_to_cash_from_petty_cash,2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_transfer_to_cash_from_petty_cash3" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>To Account</td>
                                                            <td>From Account</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($fund_allocation->where('account_id_to', 1)->where('account_id_from', 6) as $item)
                                                            <tr class="allocation-show" id="{{ $item->id }}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->fromAccount->title}}</td>
                                                                <td>{{$item->toAccount->title}}</td>
                                                                <td>{{number_format($item->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_transfer_to_bank_from_petty_cash11" aria-controls="collapse_transfer_to_bank_from_petty_cash11" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Transferred from Petty Cash to Bank</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($transfer_to_bank_from_petty_cash,2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_transfer_to_bank_from_petty_cash11" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>To Account</td>
                                                            <td>From Account</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($fund_allocation->where('account_id_to', 7)->where('account_id_from', 6) as $item)
                                                            <tr class="allocation-show" id="{{ $item->id }}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->fromAccount->title}}</td>
                                                                <td>{{$item->toAccount->title}}</td>
                                                                <td>{{number_format($item->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_transfer_from_bank_to_petty_cash10" aria-controls="collapse_transfer_from_bank_to_petty_cash10" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Transferred to Petty Cash from Bank</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($transfer_from_bank_to_petty_cash,2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_transfer_from_bank_to_petty_cash10" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>To Account</td>
                                                            <td>From Account</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($fund_allocation->where('account_id_to', 6)->where('account_id_from', 7) as $item)
                                                            <tr class="allocation-show" id="{{ $item->id }}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->fromAccount->title}}</td>
                                                                <td>{{$item->toAccount->title}}</td>
                                                                <td>{{number_format($item->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_transfer_from_cash_to_petty_cash120" aria-controls="collapse_transfer_from_cash_to_petty_cash120" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Transferred to Petty Cash from Cash</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($transfer_from_cash_to_petty_cash,2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_transfer_from_cash_to_petty_cash120" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>To Account</td>
                                                            <td>From Account</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($fund_allocation->where('account_id_to', 6)->where('account_id_from', 1) as $item)
                                                            <tr class="allocation-show" id="{{ $item->id }}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->fromAccount->title}}</td>
                                                                <td>{{$item->toAccount->title}}</td>
                                                                <td>{{number_format($item->amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_petty_cash_other_cost" aria-controls="collapse_petty_cash_other_cost" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;"> Other Cash Payment</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($petty_cash_other_cost,2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_petty_cash_other_cost" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Note</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($fund_allocation->where('account_id_from', 6) as $item)
                                                            <tr class="allocation-show" id="{{ $item->id }}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->note}}</td>
                                                                <td>{{number_format($item->transaction_cost,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right pr-1"><strong>Today's Petty Cash Total</strong></td>
                                                <td style="width: 25% !important;" class="text-right pr-1">
                                                    {{number_format(($opening_balance_petty_cash+$transfer_from_bank_to_petty_cash+$transfer_from_cash_to_petty_cash)
                                                        - ($petty_cash_other_cost+$today_payment_petty_cash+$previous_payment_petty_cash+$transfer_to_bank_from_petty_cash+$transfer_to_cash_from_petty_cash),2)}}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                {{-- Till Date Credit (Receivable) Details --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-hover ">
                                            <tr>
                                                <th colspan="5" class="text-left bg-secondary text-white pl-1">Till Date Credit (Receivable) Details</th>
                                            </tr>
                                            <tr class="daily-summery" data="previous-account-receivable">
                                                <td class="text-left pl-1 d-flex"> </td>
                                                <td class="text-right pr-1" style="width: 25% !important;">{{number_format($previous_account_receivable->sum('due_amount'),2)}}</td>
                                            </tr>
                                            {{--  --}}
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_previous_account_receivable" aria-controls="collapse_previous_account_receivable" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Previous Accounts Receivable</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($previous_account_receivable->sum('due_amount'),2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_previous_account_receivable" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Invoice No</td>
                                                            <td>Total Amount</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($previous_account_receivable as $item)
                                                            <tr class="sale_view" id="{{$item->id}}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->invoice_no?$item->invoice_no:$item->proforma_invoice_no}}</td>
                                                                <td>{{number_format($item->total_amount,2)}}</td>
                                                                <td class="text-right pr-1">{{number_format($item->due_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_today_account_receivable" aria-controls="collapse_today_account_receivable" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Today's Accounts Receivable</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($today_account_receivable->sum('due_amount'),2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_today_account_receivable" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Invoice No</td>
                                                            <td>Total Amount</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($today_account_receivable as $item)
                                                            <tr class="sale_view" id="{{$item->id}}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->invoice_no?$item->invoice_no:$item->proforma_invoice_no}}</td>
                                                                <td>{{number_format($item->total_amount,2)}}</td>
                                                                <td class="text-right pr-1">{{number_format($item->due_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right pr-1"> <strong>Till Date Receivable Total </strong> </td>
                                                <td class="text-right pr-1" style="width: 25% !important;">{{number_format($previous_account_receivable->sum('due_amount')+$today_account_receivable->sum('due_amount'),2)}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                {{-- Till Date Credit (Payable) Details --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-hover ">
                                            <tr>
                                                <th colspan="5" class="text-left bg-secondary text-white pl-1">Till Date Credit (Payable) Details</th>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_previous_account_payable" aria-controls="collapse_previous_account_payable" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Previous Accounts Payable</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($previous_account_payable->sum('due_amount'),2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_previous_account_payable" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Expense No</td>
                                                            <td>Total Amount</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($previous_account_payable as $item)
                                                            <tr class="purch_exp_view" id="{{ $item->id }}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->purchase_no}}</td>
                                                                <td>{{number_format($item->total_amount,2)}}</td>
                                                                <td class="text-right pr-1">{{number_format($item->due_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1 d-flex">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_today_account_payable" aria-controls="collapse_today_account_payable" aria-expanded="false">

                                                    </div>
                                                    <li class="btn" style="padding: 0; margin-left: 5px !important; color: #333333 !important;">Today's Accounts Payable</li>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($today_account_payable->sum('due_amount'),2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_today_account_payable" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr>
                                                            <td>Date</td>
                                                            <td>Expense No</td>
                                                            <td>Total Amount</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($today_account_payable as $item)
                                                            <tr class="purch_exp_view" id="{{ $item->id }}">
                                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                <td>{{$item->purchase_no}}</td>
                                                                <td>{{number_format($item->total_amount,2)}}</td>
                                                                <td class="text-right pr-1">{{number_format($item->due_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-right pr-1"> <strong>Till Date Payable Total </strong> </td>
                                                <td class="text-right pr-1" style="width: 25% !important;">{{number_format($previous_account_payable->sum('due_amount')+$today_account_payable->sum('due_amount'),2)}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>


    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherPreviewShow">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="project-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px;">
              <h5 class="modal-title" id="exampleModalLabel"> View Project </h5>
              <div class="d-flex align-items-center">
                <button type="button" class="print-page project-btn bg-dark" style="margin:0 5px;">
                    <span aria-hidden="true">  <i class="bx bx-printer text-white"></i> </span>
                </button>
                <button type="button" class="project-btn bg-dark text-white" data-dismiss="modal" aria-label="Close" style="margin:0 5px;">
                    <span aria-hidden="true">&times;</span>
                </button>
              </div>

            </div>
            <div class="modal-body" style="padding: 5px 15px;">

            </div>
          </div>
        </div>
    </div>
    <input type="hidden" id="date" value="{{$date}}">
    <input type="hidden" id="from" value="{{$from}}">
    <input type="hidden" id="to" value="{{$to}}">
@endsection

@push('js')
<script>
    window.onafterprint = function() {
        // Initialize your date picker code here
        location.reload();
        $(".datepicker").datepicker({
            dateFormat: "dd/mm/yy"
        });
    };
    $(document).on("click", ".daily-summery", function(e) {
        e.preventDefault();
        var url= $(this).attr('data');
        var date = $("#date").val();
        var from = $("#from").val();
        var to = $("#to").val();
		$.ajax({
			url: url,
			type: "post",
			cache: false,
			data:{
				_token:'{{ csrf_token() }}',
                date:date,
                from:from,
                to:to
			},
			success: function(response){
                document.getElementById("voucherPreviewShow").innerHTML = response;
                $('#voucherPreviewModal').modal('show')
			}
		});
	});
    $(document).on("click", ".sale_view", function(e) {
        e.preventDefault();
        var id= $(this).attr('id');
		$.ajax({
			url: "{{URL('sale-modal')}}",
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
    $(document).on('click','.view-approve-invoice',function(e){
        e.preventDefault();
        let url = $(this).attr('data-url');
        $.get(url,function(res){
            $('.modal-body').html(res);
            $('.modal-title').html('View Project');
            $('#project-modal').modal('show');
        })
    });
    $(document).on("click", ".purch_exp_view", function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        $.ajax({
            url: "{{ URL('purch-exp-modal') }}",
            type: "post",
            cache: false,
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
            },
            success: function(response) {
                document.getElementById("voucherPreviewShow").innerHTML = response;
                $('#voucherPreviewModal').modal('show')
            }
        });
    });
    $(document).on("click", ".payment_view", function(e) {
        e.preventDefault();
        var id= $(this).attr('id');
		$.ajax({
			url: "{{URL('payment-modal')}}",
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
    $(document).on("click", ".allocation-show", function(e) {
        e.preventDefault();
        var id= $(this).attr('id');
		$.ajax({
			url: "{{route('fund-allocation-modal')}}",
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
    $(document).on('click', '.thermal_print_button', function(){
        $('.thermal-print').addClass('thermal-print2');
        $('.thermal-table').addClass('thermal-table-add');
        $('.print-header-title').addClass('print-header-title-style-thermal');
        $('.print-address').addClass('print-address-style-thermal');
        $('.print-header-title').addClass('thermal-header-print2');
        $('.print-address').addClass('thermal-header-print2');
        $('.thermal-header-print').addClass('thermal-header-print2');
        window.print();
    });
    $(document).on('click', '.daily-summery-print', function(){
        $('.print-header-title').addClass('print-header-title-style');
        $('.print-address').addClass('print-address-style');
    });

</script>
@endpush

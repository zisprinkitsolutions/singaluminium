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
        color: #333333;
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
                                    <div class="col-md-2 pl-0">
                                        <form action="" method="GET" class="d-flex row">
                                            <div class="row form-group col-md-8" style="padding-left:7px;">
                                                <input type="text"
                                                    class="inputFieldHeight form-control datepicker" name="date"
                                                    placeholder="Select Date" required autocomplete="off">
                                            </div>
                                            <div class="col-md-4 mr-0 pl-0">
                                                <button type="submit" class="btn mSearchingBotton mb-2 formButton"
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

                                    <div class="col-md-3 ">
                                        <form action="" method="GET" class="d-flex row">
                                            <div class="row form-group col-md-4 mr-0 pr-0">
                                                <input type="text"
                                                    class="inputFieldHeight form-control datepicker" name="from"
                                                    placeholder="From Date" required autocomplete="off">
                                            </div>
                                            <div class="row form-group col-md-4 mr-0 pr-0">
                                                <input type="text"
                                                    class="inputFieldHeight form-control datepicker" name="to"
                                                    placeholder="To Date" required autocomplete="off">
                                            </div>
                                            <div class="col-md-2 pl-0">
                                                <button type="submit" class="btn mSearchingBotton mb-2 formButton"
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
                                        <a href="#"onclick="media_print('print-table')"
                                            class="btn btn-icon btn-secondary"><i class="bx bx-printer"></i>
                                            Print</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="print-table">

                            <span class="print-header-footer ">
                                @include('layouts.backend.partial.modal-header-info')
                            </span>
                            <div class="card-body pt-0 pb-0 daily-summery ">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2 class="text-center daily-summery-print-style ">Daily Summery</h2>
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
                                    $bank_other_cost = $fund_allocation->where('account_id_from', 3)->sum('transaction_cost');
                                    $petty_cash_other_cost = $fund_allocation->where('account_id_from', 6)->sum('transaction_cost');

                                    $transfer_to_cash_from_bank = $fund_allocation->where('account_id_to', 1)->where('account_id_from', 3)->sum('amount');
                                    $transfer_to_cash_from_petty_cash = $fund_allocation->where('account_id_to', 1)->where('account_id_from', 6)->sum('amount');
                                    $transfer_from_cash_to_bank = $fund_allocation->where('account_id_from', 1)->where('account_id_to', 3)->sum('amount');
                                    $transfer_from_cash_to_petty_cash = $fund_allocation->where('account_id_from', 1)->where('account_id_to', 6)->sum('amount');

                                    $transfer_to_bank_from_petty_cash = $fund_allocation->where('account_id_to', 3)->where('account_id_from', 6)->sum('amount');
                                    $transfer_from_bank_to_petty_cash = $fund_allocation->where('account_id_from', 3)->where('account_id_to', 6)->sum('amount');
                                    $transfer_to_bank_from_visa_card = $fund_allocation->where('account_id_from', 0)->where('account_id_to', 0)->sum('amount');
                                    $transfer_from_bank_to_visa_card = $fund_allocation->where('account_id_from', 0)->where('account_id_to', 0)->sum('amount');

                                    $today_payment_visa_card = $today_payments->where('pay_mode', 'VISA Card')->where('type', 'due')->sum('total_amount');
                                    $previous_payment_visa_card = $previous_payments->where('pay_mode', 'VISA Card')->where('type', 'due')->sum('total_amount');
                                    $today_payment_petty_cash = $today_payments->where('pay_mode', 'Petty Cash')->where('type', 'due')->sum('total_amount');
                                    $previous_payment_petty_cash = $previous_payments->where('pay_mode', 'Petty Cash')->where('type', 'due')->sum('total_amount');

                                    $opening_balance_receipt_visa_card = $previous_fund_allocation->where('account_id_to', 0)->sum('amount');
                                    $opening_balance_payment_visa_card = $previous_fund_allocation->where('account_id_from', 0)->sum('amount');
                                    $opening_balance_receipt_petty_cash = $previous_fund_allocation->where('account_id_to', 6)->sum('amount');
                                    $opening_balance_payment_petty_cash = $previous_fund_allocation->where('account_id_from', 6)->sum('amount');
                                    $opening_balance_receipt_cash_fund = $previous_fund_allocation->where('account_id_to', 1)->sum('amount');
                                    $opening_balance_payment_cash_fund = $previous_fund_allocation->where('account_id_from', 1)->sum('amount');
                                    $opening_balance_receipt_bank_fund = $previous_fund_allocation->where('account_id_to', 3)->sum('amount');
                                    $opening_balance_payment_bank_fund = $previous_fund_allocation->where('account_id_from', 3)->sum('amount');
                                @endphp
                                {{-- chash --}}
                                <div class="row mt-1">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-hover ">
                                            <tr>
                                                <th colspan="5" class="text-left bg-secondary text-white pl-1">Today's Cash Summery </th>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1">Opening Balance </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($opening_balance_cash,2)}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1">
                                                    <div class="pluseMinuseIcon collapsed" data-toggle="collapse" href="#collapse_opening_balance_cash" aria-controls="collapse_opening_balance_cash" aria-expanded="false">
                                                        <li class="btn" style="padding-right: 5px !important;">Today's Cash Sales Received</li>
                                                    </div>
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($today_cash_sale_receipts->sum('total_amount'),2)}}</td>
                                            </tr>
                                            <tr style="background: #d8d5d575 !important;" id="collapse_opening_balance_cash" class="collapse multi-collapse">
                                                <td colspan="2" style="width: 100%">
                                                    <table class="table text-center">
                                                        <tr class="">
                                                            <td>Date</td>
                                                            <td>Invoice No</td>
                                                            <td>Receipt No</td>
                                                            <td class="text-right pr-1">Amount</td>
                                                        </tr>
                                                        @foreach ($today_cash_sale_receipts as $sale_receipt)
                                                            <tr class="">
                                                                <td>{{date('d/m/Y', strtotime($sale_receipt->payment->date))}}</td>
                                                                <td>{{$sale_receipt->invoice->invoice_no}}</td>
                                                                <td>{{$sale_receipt->payment->receipt_no}}</td>
                                                                <td class="text-right pr-1">{{$sale_receipt->amount}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr class="daily-summery" data="cash-today-payment-expense">
                                                <td class="text-left pl-1">Today's Cash Payment/Expense</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($today_cash_bill_payments,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="cash-previous-receivable-receive">
                                                <td class="text-left pl-1">Previous Accounts Receivable Received</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($previous_cash_sale_receipts,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="cash-previous-payable-payment">
                                                <td class="text-left pl-1">Previous Account Payable Payment</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($previous_cash_bill_payments,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="cash-advance-receive">
                                                <td class="text-left pl-1">Advance Received </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($today_advance_cash_received,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="cash-advance-payment">
                                                <td class="text-left pl-1">Advance Payment </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($today_advance_cash_payment,2)}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1">Other Cash Received </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">0.00</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1">Other Cash Payment</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($cash_other_cost,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="fund-transfer/3/1">
                                                <td class="text-left pl-1">Transferred to Cash from Bank</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($transfer_to_cash_from_bank,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="fund-transfer/6/1">
                                                <td class="text-left pl-1">Transferred to Cash from Petty Cash</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($transfer_to_cash_from_petty_cash,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="fund-transfer/1/3">
                                                <td class="text-left pl-1">Transferred from Cash to Bank</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($transfer_from_cash_to_bank,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="fund-transfer/1/6">
                                                <td class="text-left pl-1">Transferred from Cash to Petty Cash</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($transfer_from_cash_to_petty_cash,2)}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-right pr-1"><strong>Today's Cash Total</strong></td>
                                                <td style="width: 25% !important;" class="text-right pr-1">
                                                    {{number_format(($opening_balance_cash+$today_cash_sale_receipts->sum('total_amount')+$previous_cash_sale_receipts+$today_advance_cash_received+$transfer_to_cash_from_bank+$transfer_to_cash_from_petty_cash)
                                                        - ($cash_other_cost+$today_cash_bill_payments+$previous_cash_bill_payments+$today_advance_cash_payment+$transfer_from_cash_to_bank+$transfer_from_cash_to_petty_cash),2)}}
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
                                                <th colspan="5" class="text-left bg-secondary text-white pl-1">Today's Bank Summery</th>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1">Opening Balance</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($opening_balance_bank ,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="bank-today-sale-received">
                                                <td class="text-left pl-1">Today's Bank Sales Received</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($today_bank_sale_receipts,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="bank-today-payment-expense">
                                                <td class="text-left pl-1">Today's Bank Payment/Expense</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($today_bank_bill_payments,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="bank-previous-receivable-receive">
                                                <td class="text-left pl-1">Previous Account Receivable Received </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($previous_bank_sale_receipts,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="bank-previous-payable-payment">
                                                <td class="text-left pl-1">Previous Account Payable Payment</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($previous_bank_bill_payments,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="bank-advance-receive">
                                                <td class="text-left pl-1">Advance Received</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($today_advance_bank_received,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="bank-advance-payment">
                                                <td class="text-left pl-1">Advance Payment</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($today_advance_bank_payment,2)}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1">Other Bank Received</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">0.00</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1">Other Bank Payment</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($bank_other_cost,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="fund-transfer/1/3">
                                                <td class="text-left pl-1">Transferred to Bank from Cash</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($transfer_from_cash_to_bank,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="fund-transfer/6/3">
                                                <td class="text-left pl-1">Transferred to Bank from Petty Cash</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($transfer_to_bank_from_petty_cash,2)}}</td>
                                            </tr>
                                            {{-- <tr class="daily-summery" data="fund-transfer/5/4">
                                                <td class="text-left pl-1">Transferred to Bank from VISA Card</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($transfer_to_bank_from_visa_card,2)}}</td>
                                            </tr> --}}
                                            <tr class="daily-summery" data="fund-transfer/3/1">
                                                <td class="text-left pl-1">Transferred from Bank to Cash
                                                </td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($transfer_to_cash_from_bank,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="fund-transfer/3/6">
                                                <td class="text-left pl-1">Transferred from Bank to Petty Cash</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($transfer_from_bank_to_petty_cash,2)}}</td>
                                            </tr>
                                            {{-- <tr class="daily-summery" data="fund-transfer/4/6">
                                                <td class="text-left pl-1">Transferred from Bank to VISA Card</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($transfer_from_bank_to_visa_card,2)}}</td>
                                            </tr> --}}
                                            <tr>
                                                <td class="text-right pr-1"><strong>Today's Bank Total</strong></td>
                                                <td style="width: 25% !important;" class="text-right pr-1">
                                                    {{number_format(
                                                        ($opening_balance_bank+$today_bank_sale_receipts+$previous_bank_sale_receipts+$today_advance_bank_received+$transfer_from_cash_to_bank+$transfer_to_bank_from_petty_cash+$transfer_to_bank_from_visa_card)
                                                        -
                                                        ($bank_other_cost+$today_bank_bill_payments+$previous_bank_bill_payments+$today_advance_bank_payment+$transfer_to_cash_from_bank+$transfer_from_bank_to_petty_cash+$transfer_from_bank_to_visa_card)
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
                                                <th colspan="5" class="text-left bg-secondary text-white pl-1"> Today's VISA Card Summery</th>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1">Opening Balance</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($opening_balance_visa_card = $opening_balance_receipt_visa_card-$opening_balance_payment_visa_card,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="today-payment-expense/VISA Card">
                                                <td class="text-left pl-1">Today's VISA Card Payment/Expense</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($today_payment_visa_card,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="previous-payment-expense/VISA Card">
                                                <td class="text-left pl-1">Previouse VISA Card Payment/Expense</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($previous_payment_visa_card,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="fund-transfer/6/4">
                                                <td class="text-left pl-1">Transferred from VISA Card to Bank</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($transfer_to_bank_from_visa_card,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="fund-transfer/6/4">
                                                <td class="text-left pl-1">Transferred to VISA Card from Bank</td>
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
                                                <th colspan="5" class="text-left bg-secondary text-white pl-1">Today's Petty Cash Summery</th>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1">Opening Balance</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($opening_balance_petty_cash = $opening_balance_pettycash,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="today-payment-expense/Petty Cash">
                                                <td class="text-left pl-1">Today's Petty Cash Payment/Expense</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($today_payment_petty_cash,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="previous-payment-expense/Petty Cash">
                                                <td class="text-left pl-1">Previouse Petty Cash Payment/Expense</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($previous_payment_petty_cash,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="fund-transfer/6/1">
                                                <td class="text-left pl-1">Transferred from Petty Cash to Cash</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($transfer_to_cash_from_petty_cash,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="fund-transfer/6/3">
                                                <td class="text-left pl-1">Transferred from Petty Cash to Bank</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($transfer_to_bank_from_petty_cash,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="fund-transfer/3/6">
                                                <td class="text-left pl-1">Transferred to Petty Cash from Bank</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($transfer_from_bank_to_petty_cash,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="fund-transfer/1/6">
                                                <td class="text-left pl-1">Transferred to Petty Cash from Cash</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">{{number_format($transfer_from_cash_to_petty_cash,2)}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-left pl-1">Other Petty Cash Payment</td>
                                                <td style="width: 25% !important;" class="text-right pr-1">(-) {{number_format($petty_cash_other_cost,2)}}</td>
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
                                                <td class="text-left pl-1"> Previouse Accounts Receivable</td>
                                                <td class="text-right pr-1" style="width: 25% !important;">{{number_format($previous_account_receivable,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="today-account-receivable">
                                                <td class="text-left pl-1"> Today's Accounts Receivable</td>
                                                <td class="text-right pr-1" style="width: 25% !important;">{{number_format($today_account_receivable,2)}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-right pr-1"> <strong>Till Date Receivable Total </strong> </td>
                                                <td class="text-right pr-1" style="width: 25% !important;">{{number_format($previous_account_receivable+$today_account_receivable,2)}}</td>
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
                                            <tr class="daily-summery" data="previous-account-payable">
                                                <td class="text-left pl-1"> Previouse Accounts Payable </td>
                                                <td class="text-right pr-1" style="width: 25% !important;">{{number_format($previous_account_payable,2)}}</td>
                                            </tr>
                                            <tr class="daily-summery" data="today-account-payable">
                                                <td class="text-left pl-1"> Today's Accounts Payable
                                                </td>
                                                <td class="text-right pr-1" style="width: 25% !important;">{{number_format($today_account_payable,2)}}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-right pr-1"> <strong>Till Date Payable Total </strong> </td>
                                                <td class="text-right pr-1" style="width: 25% !important;">{{number_format($previous_account_payable+$today_account_payable,2)}}</td>
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

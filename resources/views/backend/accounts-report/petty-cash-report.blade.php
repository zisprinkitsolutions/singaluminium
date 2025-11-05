

@extends('layouts.backend.app')
@section('content')
@include('layouts.backend.partial.style')
<style>
    .tabPadding{
        padding: 5px;
    }
    .padding-right{
        padding-right: 10px;
    }
    td{
        font-size: 12px !important;
    }

    th{
        font-size: 14px !important;
    }
    @media(min-width:1300px){
        .padding-right{
            padding-right: 0px !important;
        }
    }
    @media print{

        .row{
            display: flex;
        }
        .col-md-6{
            max-width: 50% !important;
        }
    }
    .card-body {
        flex: 1 1 auto;
        min-height: 1px;
        padding: 0rem !important;
    }
</style>

<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.report._header',['activeMenu' => 'petty-cash'])
            <div class="tab-content bg-white">
                <div class="tab-pane active p-2">
                    <div class="content-body">

                        <section id="widgets-Statistics">
                            <div class="row mt-1" style="margin-left: 5px !important">
                                <div class="col-md-9">
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
                                <div class="col-md-3 text-right">
                                    <a href="#" class="btn btn_create mPrint formButton" title="Print" onclick="window.print()">
                                        <div class="d-flex">
                                            <div class="formSaveIcon">
                                                <img src="{{ asset('assets/backend/app-assets/icon/print-icon.png') }}"
                                                    width="25">
                                            </div>
                                            <div><span>Print</span></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body pt-0 pb-0">
                                <table class="table table-bordered table-sm ">
                                    <thead class="thead">
                                        <tr>
                                            <th style="width: 13%">Date</th>
                                            <th style="width: 13%">Bill/Transaction</th>
                                            <th>Paid To/ Receive From</th>
                                            <th style="width: 13%" class="text-right pr-1">Cash In</th>
                                            <th style="width: 13%" class="text-right pr-1">Cash Out</th>
                                            <th style="width: 13%" class="text-right pr-1">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody id="purch-body">
                                        @php
                                            $t_balance=0;
                                            $cash_in = 0;
                                            $cash_out = 0;
                                        @endphp
                                        @foreach ($petty_cashs as $item)
                                            @php
                                                $cash_in = null;
                                                $cash_out = null;
                                                if($item->source=='fund_allocations'){
                                                    $pay_mode = App\PayMode::find($item->account_id_from);
                                                    $pay_name = $pay_mode->title;
                                                    if($item->account_id_from == 5){
                                                        $cash_out = $item->amount;
                                                    }else{
                                                        $cash_in = $item->amount;
                                                    }
                                                }else {
                                                    $pay_name = $item->account_id_from;
                                                    $cash_out = $item->amount;
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                <td>{{$item->transaction_number}}</td>
                                                <td>{{$pay_name}}</td>
                                                <td class="text-right pr-1">
                                                    {{ $cash_in ? number_format($balance_in = $cash_in, 2) : ($balance_in = null) }}
                                                </td>
                                                <td class="text-right pr-1">
                                                    {{ $cash_out ? number_format($balance_out = $cash_out, 2) : ($balance_out = null) }}
                                                </td>
                                                <td class="text-right pr-1">
                                                    {{ number_format($t_balance = ($t_balance + ($balance_in ?? 0)) - ($balance_out ?? 0), 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        {{-- <tr>
                                            <td colspan="4" class="text-left pl-2">Cash In</td>
                                            <td>{{number_format($cash_in,2)}}</td>
                                            <td>{{number_format($cash_out,2)}}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-left pl-2">Cash Out</td>
                                            <td>{{number_format($cash_out,2)}}</td>
                                            <td></td>
                                            <td></td>
                                        </tr> --}}
                                        {{-- <tr>
                                            <td colspan="6" class="text-left pl-2">Opening Balance</td>
                                            <td>{{number_format($opening_balance->where('type', 'Cash In')->sum('total_amount')-$opening_balance->where('type', 'Cash Out')->sum('total_amount'),2)}}</td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<style>
    #customers {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #customers td,
    #customers th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #customers tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #customers tr:hover {
        background-color: #ddd;
    }

    #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #04AA6D;
        color: white;
        text-transform: uppercase;

    }

    .graph-7 {
        background: url(../img/graphs/graph-7.jpg) no-repeat;
    }

    .graph-image img {
        display: none;
    }

    @media screen {
        div.divFooter {
            display: none;
        }
    }

    @media print {
        div.divFooter {
            position: fixed;
            bottom: 0;
        }
    }

    th {
        text-transform: uppercase;
    }
</style>
<style>
    .print-layout {
        display: none;
    }

    @media print {
        .print-layout {
            display: block;
            overflow: hidden;
        }
    }
</style>
<section class="print-layout">
    @include('layouts.backend.partial.modal-header-info')
    <div class="card-body pt-0 pb-0">
        <table class="table table-bordered table-sm ">
            <thead class="thead">
                <tr>
                    <th style="width: 13%">Date</th>
                    <th style="width: 13%">Bill/Transection</th>
                    <th>Paid To/ Receive From</th>
                    <th style="width: 13%">Cash In</th>
                    <th style="width: 13%">Cash Out</th>
                    <th style="width: 13%">Balance</th>
                </tr>
            </thead>
            <tbody id="purch-body">
                @php
                    $t_balance=0;
                    $cash_in = 0;
                    $cash_out = 0;
                @endphp
                @foreach ($petty_cashs as $item)
                    @php
                        $cash_in = null;
                        $cash_out = null;
                        if($item->source=='fund_allocations'){
                            $pay_mode = App\PayMode::find($item->account_id_from);
                            $pay_name = $pay_mode->title;
                            if($item->account_id_from == 5){
                                $cash_out = $item->amount;
                            }else{
                                $cash_in = $item->amount;
                            }
                        }else {
                            $pay_name = $item->account_id_from;
                            $cash_out = $item->amount;
                        }
                    @endphp
                    <tr>
                        <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                        <td>{{$item->transaction_number}}</td>
                        <td>{{$pay_name}}</td>
                        <td>{{$cash_in?number_format($balance_in=$cash_in,2):$balance_in=null}}</td>
                        <td>{{$cash_out?number_format($balance_out=$cash_out,2):$balance_out=null}}</td>
                        <td>
                            {{number_format($t_balance=($t_balance+$balance_in)-$balance_out,2) }}
                        </td>
                    </tr>
                @endforeach
                {{-- <tr>
                    <td colspan="4" class="text-left pl-2">Cash In</td>
                    <td>{{number_format($cash_in,2)}}</td>
                    <td>{{number_format($cash_out,2)}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5" class="text-left pl-2">Cash Out</td>
                    <td>{{number_format($cash_out,2)}}</td>
                    <td></td>
                    <td></td>
                </tr> --}}
                {{-- <tr>
                    <td colspan="6" class="text-left pl-2">Opening Balance</td>
                    <td>{{number_format($opening_balance->where('type', 'Cash In')->sum('total_amount')-$opening_balance->where('type', 'Cash Out')->sum('total_amount'),2)}}</td>
                </tr> --}}
            </tbody>
        </table>
    </div>
    @include('layouts.backend.partial.modal-footer-info')
</section>

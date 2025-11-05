
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

    .card-body {
    flex: 1 1 auto;
    min-height: 1px;
    padding: 0rem !important;
}
tr:nth-child(even) {background-color: #f2f2f2;}

</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.report._header', [
                'activeMenu' => 'account_report',
            ])
            <div class="tab-content bg-white">
                <div class="tab-pane active p-1">
                    <div class="content-body">
                        <div class="d-flex justify-content-between align-items-center">
                            @include('clientReport.report._accounting_report_subheader', [
                                'activeMenu' => 'income_statement',
                            ])
                            <div class="d-flex-align-items-center gap-2">
                                <button type="button" class="btn mExcelButton formButton mr-1" title="Export" onclick="exportTableToCSV('income-statement-{{ date('d M Y') }}.csv')">
                                    <div class="d-flex">
                                        <div class="formSaveIcon">
                                            <img src="{{asset('assets/backend/app-assets/icon/excel-icon.png')}}" width="25">
                                        </div>
                                        <div><span>Export</span></div>
                                    </div>
                                </button>
                                <a href="#" class="btn btn_create mPrint formButton" title="Print"
                                    onclick="window.print()">
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
                        <section id="widgets-Statistics ">
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
                                <table  class="table table-sm table-hover">
                                    <tr>
                                        <th colspan="4" class="text-center">
                                            <h4>Income Statement</h4>
                                            <h6>{{date('d F Y',strtotime($date))}} - {{date('d F Y',strtotime($date2)) }}</h4>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>A/C Head</th>
                                        <th></th>
                                        <th class="text-right">Debit</th>
                                        <th class="text-right">Credit</th>
                                    </tr>

                                    <tr>
                                        <td> Opening Balance</td>
                                        <td>{{App\JournalRecord::openingProfit($date,$office_id)<0?'Profit':'Loss'}}</td>
                                        <td class="text-right">{{$currency->symbole}} {{$total_debit=App\JournalRecord::openingProfit($date,$office_id)>0?App\JournalRecord::openingProfit($date):'0.00'}}</td>
                                        <td class="text-right">{{$currency->symbole}} {{$total_credit=App\JournalRecord::openingProfit($date, $office_id)<0?App\JournalRecord::openingProfit($date)*(-1):'0.00'}}</td>
                                    </tr>
                                    @foreach ($masters as $unique_master_ac)
                                    <tr class="head-ledger" id="{{$unique_master_ac->ac_head->id??''}}">
                                                <td>{{$unique_master_ac->ac_head->fld_ac_head??''}} </td>
                                                <td></td>
                                                <td class="text-right">{{$currency->symbole}} {{$debit_balance=$unique_master_ac->headDrCrTransaction($date,$date2, $office_id) > 0 ?$unique_master_ac->headDrCrTransaction($date,$date2,$office_id):0}}</td>
                                                <td class="text-right">{{$currency->symbole}} {{$credit_balance=$unique_master_ac->headDrCrTransaction($date,$date2, $office_id) < 0 ? ($unique_master_ac->headDrCrTransaction($date,$date2, $office_id)*(-1)):0}}</td>
                                            </tr>
                                            @php
                                                $total_debit=$total_debit+$debit_balance;
                                                $total_credit=$total_credit+$credit_balance;
                                            @endphp
                                    @endforeach

                                    @foreach ($groupMasters as $unique_master_ac)
                                    <tr  class="master-head-ledger" id="{{$unique_master_ac->master_ac->id}}">
                                                <td>{{$unique_master_ac->master_ac->mst_ac_head}} </td>
                                                <td></td>
                                                <td class="text-right">{{$currency->symbole}} {{$debit_balance=$unique_master_ac->inventoryDrCrTransection($date,$date2,$office_id) > 0 ?$unique_master_ac->inventoryDrCrTransection($date,$date2, $office_id):0}}</td>
                                                <td class="text-right">{{$currency->symbole}} {{$credit_balance=$unique_master_ac->inventoryDrCrTransection($date,$date2,$office_id) < 0 ? ($unique_master_ac->inventoryDrCrTransection($date,$date2, $office_id)*(-1)):0}}</td>
                                            </tr>
                                            @php
                                                $total_debit=$total_debit+$debit_balance;
                                                $total_credit=$total_credit+$credit_balance;
                                            @endphp
                                    @endforeach


                                    @php
                                    $asset_expense= App\JournalRecord::where('office_id', Auth::user()->office_id)->whereIn('master_account_id',[180])->get();
                                    $asset_expense_cr=$asset_expense->where('transaction_type','CR')->sum('total_amount');
                                    $asset_expense_dr=$asset_expense->where('transaction_type','DR')->sum('total_amount');
                                    $profit_loss=$asset_expense_dr-$asset_expense_cr;

                                    @endphp

                                    @if ($asset_expense->count()>0)
                                    <tr>
                                                <td>{{App\Models\MasterAccount::where('id',180)->first()->mst_ac_head}} </td>
                                                <td></td>
                                                <td class="text-right">{{$debit_balance= $profit_loss>0?$profit_loss:0}}</td>
                                                <td class="text-right">{{$credit_balance= $profit_loss<0?($profit_loss*(-1)):0}}</td>
                                            </tr>
                                            @php
                                                $total_debit=$total_debit+$debit_balance;
                                                $total_credit=$total_credit+$credit_balance;
                                            @endphp
                                    @endif

                                    <tr>
                                        <th>{{$total_credit>$total_debit? 'Profit':'Loss'}}</th>
                                        <th></th>
                                        <th class="text-right"> {{$total_credit>$total_debit?$currency->symbole. (number_format($total_credit-$total_debit,2)):'0'}}</th>
                                        <th class="text-right"> {{$total_credit<$total_debit?$currency->symbole. (number_format($total_debit-$total_credit,2)):''}}</th>

                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right">{{$currency->symbole}} {{$total_debit>$total_credit? number_format($total_debit,2):number_format($total_credit,2)}} </th>
                                        <th class="text-right">{{$currency->symbole}} {{$total_debit>$total_credit? number_format($total_debit,2):number_format($total_credit,2)}}</th>
                                    </tr>
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
        <table  class="table table-sm table-hover">
            <tr>
                <th colspan="4" class="text-center">
                    <h4>Income Statement</h4>
                    <h6>{{date('d F Y',strtotime($date))}} - {{date('d F Y',strtotime($date2)) }}</h4>
                </th>
            </tr>
            <tr>
                <th>A/C Head</th>
                <th></th>
                <th class="text-right">Debit</th>
                <th class="text-right">Credit</th>
            </tr>

            <tr>
                <td> Opening Balance</td>
                <td>{{App\JournalRecord::openingProfit($date)<0?'Profit':'Loss'}}</td>
                <td class="text-right">{{$currency->symbole}} {{$total_debit=App\JournalRecord::openingProfit($date)>0?App\JournalRecord::openingProfit($date):'0.00'}}</td>
                <td class="text-right">{{$currency->symbole}} {{$total_credit=App\JournalRecord::openingProfit($date)<0?App\JournalRecord::openingProfit($date)*(-1):'0.00'}}</td>
            </tr>
            @foreach ($masters as $unique_master_ac)
            <tr class="head-ledger" id="{{$unique_master_ac->ac_head->id??''}}">
                        <td>{{$unique_master_ac->ac_head->fld_ac_head??''}} </td>
                        <td></td>
                        <td class="text-right">{{$currency->symbole}} {{$debit_balance=$unique_master_ac->headDrCrTransaction($date,$date2) > 0 ?$unique_master_ac->headDrCrTransaction($date,$date2):0}}</td>
                        <td class="text-right">{{$currency->symbole}} {{$credit_balance=$unique_master_ac->headDrCrTransaction($date,$date2) < 0 ? ($unique_master_ac->headDrCrTransaction($date,$date2)*(-1)):0}}</td>
                    </tr>
                    @php
                        $total_debit=$total_debit+$debit_balance;
                        $total_credit=$total_credit+$credit_balance;
                    @endphp
            @endforeach

            @foreach ($groupMasters as $unique_master_ac)
            <tr  class="master-head-ledger" id="{{$unique_master_ac->master_ac->id}}">
                        <td>{{$unique_master_ac->master_ac->mst_ac_head}} </td>
                        <td></td>
                        <td class="text-right">{{$currency->symbole}} {{$debit_balance=$unique_master_ac->inventoryDrCrTransection($date,$date2) > 0 ?$unique_master_ac->inventoryDrCrTransection($date,$date2):0}}</td>
                        <td class="text-right">{{$currency->symbole}} {{$credit_balance=$unique_master_ac->inventoryDrCrTransection($date,$date2) < 0 ? ($unique_master_ac->inventoryDrCrTransection($date,$date2)*(-1)):0}}</td>
                    </tr>
                    @php
                        $total_debit=$total_debit+$debit_balance;
                        $total_credit=$total_credit+$credit_balance;
                    @endphp
            @endforeach


            @php
            $asset_expense= App\JournalRecord::where('office_id', Auth::user()->office_id)->whereIn('master_account_id',[180])->get();
            $asset_expense_cr=$asset_expense->where('transaction_type','CR')->sum('total_amount');
            $asset_expense_dr=$asset_expense->where('transaction_type','DR')->sum('total_amount');
            $profit_loss=$asset_expense_dr-$asset_expense_cr;

            @endphp

            @if ($asset_expense->count()>0)
            <tr>
                        <td>{{App\Models\MasterAccount::where('id',180)->first()->mst_ac_head}} </td>
                        <td></td>
                        <td class="text-right">{{$debit_balance= $profit_loss>0?$profit_loss:0}}</td>
                        <td class="text-right">{{$credit_balance= $profit_loss<0?($profit_loss*(-1)):0}}</td>
                    </tr>
                    @php
                        $total_debit=$total_debit+$debit_balance;
                        $total_credit=$total_credit+$credit_balance;
                    @endphp
            @endif

            <tr>
                <th>{{$total_credit>$total_debit? 'Profit':'Loss'}}</th>
                <th></th>
                <th class="text-right"> {{$total_credit>$total_debit?$currency->symbole. (number_format($total_credit-$total_debit,2)):'0'}}</th>
                <th class="text-right"> {{$total_credit<$total_debit?$currency->symbole. (number_format($total_debit-$total_credit,2)):''}}</th>

            </tr>
            <tr>
                <th></th>
                <th></th>
                <th class="text-right">{{$currency->symbole}} {{$total_debit>$total_credit? number_format($total_debit,2):number_format($total_credit,2)}} </th>
                <th class="text-right">{{$currency->symbole}} {{$total_debit>$total_credit? number_format($total_debit,2):number_format($total_credit,2)}}</th>
            </tr>
        </table>
    </div>
    @include('layouts.backend.partial.modal-footer-info')
</section>

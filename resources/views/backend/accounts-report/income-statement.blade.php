

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
                        </div>
                        <section id="widgets-Statistics ">
                            <div class="row mt-1" style="margin-left: 5px !important">
                                <div class="col-md-12 pl-0">
                                    <form action="" method="GET" >
                                        <div class="d-flex">
                                            <div class="form-group" style="width:15%;">
                                                <input type="text" class="inputFieldHeight form-control datepicker" name="from" placeholder="From"   id="from" autocomplete="off">
                                            </div>
                                            <div class="form-group" style="width:15%;padding-left:8px;">
                                                <input type="text" class="inputFieldHeight form-control datepicker" name="to"
                                                placeholder="To"  id="to" autocomplete="off">
                                            </div>
                                            <div class="form-group" style="width:15%;padding-left:8px;">
                                                <button type="submit" class="btn mSearchingBotton inputFieldHeight formButton" title="Search" >
                                                    <div class="d-flex" style="padding: 0 10px;">
                                                        <div class="formSaveIcon">
                                                            <img src="{{asset('assets/backend/app-assets/icon/searching-icon.png')}}" width="20">
                                                        </div>
                                                        <div><span>Search</span></div>
                                                    </div>
                                                </button>
                                            </div>

                                            <div class="d-flex justify-content-end" style="width:70%">
                                                <a href="#" class="btn mExcelButton formButton mr-1 mb-2" title="Export" onclick="exportTableToCSV('income-statement-{{ date('d M Y') }}.csv')">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{asset('assets/backend/app-assets/icon/excel-icon.png')}}" width="25">
                                                        </div>
                                                        <div><span>Excel</span></div>
                                                    </div>
                                                </a>
                                                <a href="#" class="btn btn_create mPrint formButton mb-2" title="Print" onclick="window.print()">
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
                                    </form>
                                </div>
                            </div>
                            <div class="card-body pt-0 pb-0" id="print_section">
                                @include('layouts.backend.partial.modal-header-info')
                                <table  class="table table-sm table-hover">
                                    <tr>
                                        <th colspan="4" class="text-center">
                                            <h4>Income Statement</h4>
                                            <h6>January {{ date('Y') }} - December {{ date('Y') }}</h4>
                                        </th>
                                    </tr>
                                    <tr style="background:#34465b;color:white;">
                                        <th class="pl-2">A/C Head</th>
                                        <th></th>
                                        <th class="text-right">Debit <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                        <th class="text-right pr-2">Credit <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                    </tr>
                                    @php
                                        $total_debit=0;
                                        $total_credit=0;
                                    @endphp
                                    @foreach ($masters as $unique_master_ac)
                                    <tr class="head-ledger" id="{{$unique_master_ac->ac_head->id??''}}">
                                                <td class="pl-2">{{$unique_master_ac->ac_head->fld_ac_head??''}} </td>
                                                <td></td>
                                                <td class="text-right">  {{number_format(($debit_balance=$unique_master_ac->balanceCD($unique_master_ac->account_head_id, $office_id) > 0 ?$unique_master_ac->balanceCD($unique_master_ac->account_head_id, $office_id):0),2,'.','')}}</td>
                                                <td class="text-right pr-2">  {{number_format(($credit_balance=$unique_master_ac->balanceCD($unique_master_ac->account_head_id, $office_id) < 0 ? ($unique_master_ac->balanceCD($unique_master_ac->account_head_id, $office_id)*(-1)):0),2,'.','')}}</td>
                                            </tr>
                                            @php
                                                $total_debit=$total_debit+$debit_balance;
                                                $total_credit=$total_credit+$credit_balance;
                                            @endphp
                                    @endforeach

                                    @foreach ($groupMasters as $unique_master_ac)
                                    <tr class="head-ledger" id="{{$unique_master_ac->ac_head->id??''}}">
                                                <td class="pl-2">{{$unique_master_ac->ac_head->fld_ac_head??''}}  </td>
                                                <td></td>
                                                 <td class="text-right">  {{number_format(($debit_balance=$unique_master_ac->balanceCD($unique_master_ac->account_head_id, $office_id) > 0 ?$unique_master_ac->balanceCD($unique_master_ac->account_head_id, $office_id):0),2,'.','')}}</td>
                                                <td class="text-right pr-2">  {{number_format(($credit_balance=$unique_master_ac->balanceCD($unique_master_ac->account_head_id, $office_id) < 0 ? ($unique_master_ac->balanceCD($unique_master_ac->account_head_id, $office_id)*(-1)):0),2,'.','')}}</td>
                                            </tr>
                                            </tr>
                                            @php
                                                $total_debit=$total_debit+$debit_balance;
                                                $total_credit=$total_credit+$credit_balance;
                                            @endphp
                                    @endforeach


                                    @php
                                    $asset_expense= App\JournalRecord::whereIn('master_account_id',[180])->get();
                                    $asset_expense_cr=$asset_expense->where('transaction_type','CR')->sum('total_amount');
                                    $asset_expense_dr=$asset_expense->where('transaction_type','DR')->sum('total_amount');
                                    $profit_loss=$asset_expense_dr-$asset_expense_cr;
                                    @endphp

                                    @if ($asset_expense->count()>0)
                                    <tr>
                                        <td class="pl-2">{{App\Models\MasterAccount::where('id',180)->first()->mst_ac_head}} </td>
                                        <td></td>
                                        <td class="text-right">{{number_format(($debit_balance= $profit_loss>0?$profit_loss:0),2,'.','')}}</td>
                                        <td class="text-right pr-2">{{number_format(($credit_balance= $profit_loss<0?($profit_loss*(-1)):0),2,'.','')}}</td>
                                    </tr>
                                    @php
                                        $total_debit=$total_debit+$debit_balance;
                                        $total_credit=$total_credit+$credit_balance;
                                    @endphp
                                    @endif

                                    <tr>
                                        <th class="pl-2">{{$total_credit>$total_debit? 'Profit':'Loss'}}</th>
                                        <th></th>
                                        <th class="text-right "> {{$total_credit>$total_debit? $currency->symbole. (number_format($total_credit-$total_debit,2,'.','')):'0.00'}}</th>
                                        <th class="text-right pr-2"> {{$total_credit<$total_debit? $currency->symbole. (number_format($total_debit-$total_credit,2,'.','')):''}}</th>

                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right"> {{$total_debit>$total_credit? number_format($total_debit,2):number_format($total_credit,2,'.','')}} </th>
                                        <th class="text-right pr-2"> {{$total_debit>$total_credit? number_format($total_debit,2):number_format($total_credit,2,'.','')}}</th>
                                    </tr>
                                </table>
                                @include('layouts.backend.partial.modal-footer-info')

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
<section class="print-layout ">
    @include('layouts.backend.partial.modal-header-info')
    <div class="card-body pt-0 pb-0">
          <table  class="table table-sm table-hover">
                                    <tr>
                                        <th colspan="4" class="text-center">
                                            <h4>Income Statement</h4>
                                            <h6>January {{ date('Y') }} - December {{ date('Y') }}</h4>
                                        </th>
                                    </tr>
                                    <tr style="background:#34465b;color:white;">
                                        <th class="pl-2">A/C Head</th>
                                        <th></th>
                                        <th class="text-right">Debit <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                        <th class="text-right pr-2">Credit <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                    </tr>
                                    @php
                                        $total_debit=0;
                                        $total_credit=0;
                                    @endphp
                                    @foreach ($masters as $unique_master_ac)
                                    <tr class="head-ledger" id="{{$unique_master_ac->ac_head->id??''}}">
                                                <td class="pl-2">{{$unique_master_ac->ac_head->fld_ac_head??''}}  </td>
                                                <td></td>
                                                <td class="text-right">  {{number_format(($debit_balance=$unique_master_ac->balanceCD($unique_master_ac->account_head_id) > 0 ?$unique_master_ac->balanceCD($unique_master_ac->account_head_id):0),2,'.','')}}</td>
                                                <td class="text-right pr-2">  {{number_format(($credit_balance=$unique_master_ac->balanceCD($unique_master_ac->account_head_id) < 0 ? ($unique_master_ac->balanceCD($unique_master_ac->account_head_id)*(-1)):0),2,'.','')}}</td>
                                            </tr>
                                            @php
                                                $total_debit=$total_debit+$debit_balance;
                                                $total_credit=$total_credit+$credit_balance;
                                            @endphp
                                    @endforeach

                                    @foreach ($groupMasters as $unique_master_ac)
                                    <tr class="head-ledger" id="{{$unique_master_ac->ac_head->id??''}}">
                                                <td class="pl-2">{{$unique_master_ac->ac_head->fld_ac_head??''}}  </td>
                                                <td></td>
                                                 <td class="text-right">  {{number_format(($debit_balance=$unique_master_ac->balanceCD($unique_master_ac->account_head_id) > 0 ?$unique_master_ac->balanceCD($unique_master_ac->account_head_id):0),2,'.','')}}</td>
                                                <td class="text-right pr-2">  {{number_format(($credit_balance=$unique_master_ac->balanceCD($unique_master_ac->account_head_id) < 0 ? ($unique_master_ac->balanceCD($unique_master_ac->account_head_id)*(-1)):0),2,'.','')}}</td>
                                            </tr>
                                            </tr>
                                            @php
                                                $total_debit=$total_debit+$debit_balance;
                                                $total_credit=$total_credit+$credit_balance;
                                            @endphp
                                    @endforeach


                                    @php
                                    $asset_expense= App\JournalRecord::whereIn('master_account_id',[180])->get();
                                    $asset_expense_cr=$asset_expense->where('transaction_type','CR')->sum('total_amount');
                                    $asset_expense_dr=$asset_expense->where('transaction_type','DR')->sum('total_amount');
                                    $profit_loss=$asset_expense_dr-$asset_expense_cr;

                                    @endphp

                                    @if ($asset_expense->count()>0)
                                    <tr>
                                                <td class="pl-2">{{App\Models\MasterAccount::where('id',180)->first()->mst_ac_head}} </td>
                                                <td></td>
                                                <td class="text-right ">{{number_format(($debit_balance= $profit_loss>0?$profit_loss:0),2,'.','')}}</td>
                                                <td class="text-right pr-2">{{number_format(($credit_balance= $profit_loss<0?($profit_loss*(-1)):0),2,'.','')}}</td>
                                            </tr>
                                            @php
                                                $total_debit=$total_debit+$debit_balance;
                                                $total_credit=$total_credit+$credit_balance;
                                            @endphp
                                    @endif

                                    <tr>
                                        <th class="pl-2">{{$total_credit>$total_debit? 'Profit':'Loss'}}</th>
                                        <th></th>
                                        <th class="text-right "> {{$total_credit>$total_debit? $currency->symbole. (number_format($total_credit-$total_debit,2,'.','')):'0.00'}}</th>
                                        <th class="text-right pr-2"> {{$total_credit<$total_debit? $currency->symbole. (number_format($total_debit-$total_credit,2,'.','')):''}}</th>

                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right"> {{$total_debit>$total_credit? number_format($total_debit,2):number_format($total_credit,2,'.','')}} </th>
                                        <th class="text-right pr-2"> {{$total_debit>$total_credit? number_format($total_debit,2):number_format($total_credit,2,'.','')}}</th>
                                    </tr>
                                </table>
    </div>
    @include('layouts.backend.partial.modal-footer-info')
</section>


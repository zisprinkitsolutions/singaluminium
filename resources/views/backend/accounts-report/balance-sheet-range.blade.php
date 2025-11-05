

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
            @include('clientReport.report._header',['activeMenu' => 'financial_reports'])
            <div class="tab-content bg-white">
                <div class="tab-pane active p-2">
                    <div class="content-body">
                        <div class="d-flex justify-content-between align-items-center">
                            @include('clientReport.report._financial_report_subheader', [
                                'activeMenu' => 'balance_sheet',
                            ])
                            <div class="d-flex-align-items-center gap-2">
                                <button type="button" class="btn mExcelButton formButton mr-1" title="Export" onclick="exportTableToCSV('balance-sheet-{{ date('d M Y') }}.csv')">
                                    <div class="d-flex">
                                        <div class="formSaveIcon">
                                            <img src="{{asset('assets/backend/app-assets/icon/excel-icon.png')}}" width="25">
                                        </div>
                                        <div><span>Export To CSV</span></div>
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

                        <section id="widgets-Statistics">
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
                                    <div class="col-md-12">
                                        <table  class="table table-sm table-hover">
                                            <tr>
                                                <th colspan="4" class="text-center">
                                                    <h5>Balance Sheet</h5>
                                                    <h6>{{ date(' d F Y', strtotime($date)) }} - {{ date(' d F Y', strtotime($date2)) }}</h6>
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
                                    @php
                                        $total_debit=$total_debit = App\JournalRecord::openingProfitBalanceSheet($date, $office_id)>0? App\JournalRecord::openingProfitBalanceSheet($date, $office_id):0;
                                        $total_credit=App\JournalRecord::openingProfitBalanceSheet($date, $office_id)<0? App\JournalRecord::openingProfitBalanceSheet($date, $office_id)*(-1):0;
                                        $total_debit_adj=0;
                                        $total_credit_adj=0;
                                    @endphp
                                    <div class="col-md-6">
                                            <table  class="table table-sm table-hover">
                                                @if($total_debit>0)
                                                <tr>
                                                    <th>Opening <small>(Profit)</small></th>
                                                    <th class="text-right"> {{$total_debit}}</th>
                                                </tr>
                                                @endif
                                            <tr>
                                                <th>Asset</th>
                                                <th class="text-right">Amount ({{$currency->symbole}})</th>
                                            </tr>

                                            @foreach (App\JournalRecord::whereIn('account_type_id',[1])->where('office_id',$office_id)->distinct()->get('account_head_id') as $unique_master_ac)
                                                    <tr class="head-ledger" id="{{$unique_master_ac->ac_head->id}}">
                                                        <td>{{$unique_master_ac->ac_head->fld_ac_head}} </td>
                                                        <td class="text-right"> {{$debit_balance=$unique_master_ac->headDrCrTransaction($date,$date2,$office_id) }}</td>
                                                    </tr>
                                                    @php
                                                        $total_debit=$total_debit+$debit_balance;
                                                    @endphp
                                            @endforeach

                                              @foreach (App\JournalRecord::whereIn('account_type_id',[6])->where('office_id',$office_id)->whereNotIn('account_head_id',[421,460])->distinct()->get('account_head_id') as $unique_master_ac)
                                                 @if($unique_master_ac->ac_head->id!=457)
                                                    <tr class="head-ledger" id="{{$unique_master_ac->ac_head->id}}">
                                                        <td>{{$unique_master_ac->ac_head->fld_ac_head}} </td>
                                                        <td class="text-right"> {{$debit_balance=$unique_master_ac->headDrCrTransaction($date,$date2,$office_id)}}</td>
                                                    </tr>
                                                     @php
                                                        $total_debit=$total_debit+$debit_balance;
                                                    @endphp
                                                 @endif
                                            @endforeach

                                        </table>

                                    </div>


                                    <div class="col-md-6">
                                            <table  class="table table-sm table-hover">
                                                @if($total_credit>0)
                                                <tr>
                                                    <th>Opening <small>(Loss)</small></th>
                                                    <th class="text-right"> {{$total_credit}}</th>
                                                </tr>
                                                @endif

                                            <tr>
                                                <th>Liability & Owner's Equity</th>
                                                <th class="text-right">Amount ({{$currency->symbole}})</th>
                                            </tr>

                                            @foreach (App\JournalRecord::whereIn('account_type_id',[2])->where('office_id',$office_id)->distinct()->get('account_head_id') as $unique_master_ac)

                                                  <tr class="head-ledger" id="{{$unique_master_ac->ac_head->id}}">
                                                        <td>{{$unique_master_ac->ac_head->fld_ac_head}}</td>
                                                        <td class="text-right"> {{$credit_balance=($unique_master_ac->headDrCrTransaction($date,$date2,$office_id)*(-1))}}</td>
                                                    </tr>

                                                 @php
                                                    $credit_balance=$credit_balance==null? 0:$credit_balance;
                                                $total_credit=$total_credit+ $credit_balance;
                                               @endphp
                                            @endforeach

                                              @foreach (App\JournalRecord::whereIn('account_type_id',[6])->where('office_id',$office_id)->whereNotIn('account_head_id',[14])->distinct()->get('account_head_id') as $unique_master_ac)

                                                  <tr class="head-ledger" id="{{$unique_master_ac->ac_head->id}}">
                                                        <td>{{$unique_master_ac->ac_head->fld_ac_head}}</td>
                                                        <td class="text-right"> {{$credit_balance=($unique_master_ac->headDrCrTransaction($date,$date2,$office_id)*(-1))}}</td>
                                                    </tr>

                                                 @php
                                                    $credit_balance=$credit_balance==null? 0:$credit_balance;
                                                $total_credit=$total_credit+ $credit_balance;
                                               @endphp
                                            @endforeach


                                        </table>

                                    </div>


                                    <div class="col-md-6">
                                            <table  class="table table-sm table-hover">
                                               

                                        </table>

                                    </div>


                                    <div class="col-md-6">
                                        <table  class="table table-sm table-hover">
                                            @php
                                                $profit=$total_debit-$total_credit;
                                                $total_credit_adj = ($profit+$total_credit);
                                            @endphp
                                            <tr>
                                                <td>
                                                    {{$profit>=0?'Profit':'Loss'}}
                                                </td>
                                                <td class="text-right">
                                                    {{number_format($profit<0?$profit*-1:$profit,2,'.','')}}
                                                </td>
                                            </tr>

                                        </table>

                                    </div>


                                       <div class="col-md-6">
                                            <table  class="table table-sm table-hover">

                                              <tr>
                                                   <th></th>
                                                    <th class="text-right"> {{$total_debit}}</th>
                                                </tr>
                                        </table>

                                    </div>

                                       <div class="col-md-6">
                                            <table  class="table table-sm table-hover">


                                            <tr>
                                                <th></th>
                                                <th class="text-right"> {{$total_credit_adj}}</th>
                                            </tr>

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
@endsection


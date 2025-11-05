

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
    @media print{
        
        .print-hideen{
            display: none !important;
        }
        .nav.nav-tabs ~ .tab-content{
            border: #fff;
        }
        td, th, .text-color{
            color: black !important;
        }
        html, body {
            width: 100%;
            height: auto;
            overflow: visible;
        }
    }
</style>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.report._header',['activeMenu' => 'account_report'])
            <div class="tab-content bg-white">
                <div class="tab-pane active p-2">
                    <div class="content-body">
                        <div class="d-flex justify-content-between align-items-center  print-hideen">
                            @include('clientReport.report._accounting_report_subheader', ['activeMenu' => 'balance_sheet'])
                            
                        </div>
                        <section id="widgets-Statistics">
                            <div class="row mt-1  print-hideen" style="margin-left: 5px !important">
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
                            <div class="card-body pt-0 pb-0">
                                @include('layouts.backend.partial.modal-header-info')
                                <div class="row">
                                    <div class="col-md-12">
                                        <table  class="table table-sm table-hover">
                                            <tr>
                                                <th colspan="4" class="text-center">
                                                    <h5 class="text-color">Balance Sheet</h5>
                                                    <h6 class="text-color"> {{ $from_date?date('M Y', strtotime($from_date)):date('M Y') }} - {{ $to_date?date('M Y', strtotime($to_date)):date('M Y') }}</h6>
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table  class="table table-sm table-hover">
                                            <tr style="background:#34465b;color:white;">
                                                <th>Asset</th>
                                                <th class="text-right">Amount <small>({{$currency->symbole}})</small></th>
                                            </tr>
                                            @foreach ($assets_results as $item)
                                                <tr class="head-details" style="background: white !important;">
                                                    <td style="font-size: 13px;">
                                                        <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                            <i class='bx bx-plus d-none'></i>
                                                            <i class='bx bx-minus'> </i>
                                                            {{$item->master_accounts_name}}
                                                        </div>
                                                    </td>
                                                    <td class="text-right pr-1 {{$item->net_amount<0?'text-danger':''}}">{{$item->net_amount<0?'(':''}}{{number_format($item->net_amount<0?$item->net_amount*-1:$item->net_amount,2)}}{{$item->net_amount<0?')':''}}</td>
                                                </tr>
                                                <tr class="subhead">
                                                    <td colspan="2" style="padding: 0;">
                                                        <div class=" ml-2">
                                                            <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                                @foreach (App\JournalRecord::balance_sheet_details($from_date, $to_date, $item->master_accounts_id, 'DR') as $detail)
                                                                    <tr class="tax-sub-head-details" id="{{$detail->account_head_id}}">
                                                                        <td class="td-border">{{$detail->ac_head->fld_ac_head??''}}</td>
                                                                        <td class="text-right pr-1 {{$detail->net_amount<0?'text-danger':''}}" style="width: 200px !important;">{{$detail->net_amount<0?'(':''}}{{number_format($detail->net_amount<0?$detail->net_amount*-1:$detail->net_amount,2)}}{{$detail->net_amount<0?')':''}}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table  class="table table-sm table-hover">
                                            <tr style="background:#34465b;color:white;">
                                                <th>Liability & Owner's Equity</th>
                                                <th class="text-right">Amount <small>({{$currency->symbole}})</small></th>
                                            </tr>
                                            @foreach ($liability_results as $item)
                                                <tr class="head-details" style="background: white !important;">
                                                    <td style="font-size: 13px;">
                                                        <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                            <i class='bx bx-plus d-none'></i>
                                                            <i class='bx bx-minus'> </i>
                                                            {{$item->master_accounts_name}}
                                                        </div>
                                                    </td>
                                                    <td class="text-right pr-1">{{number_format($item->net_amount<0?$item->net_amount*-1:$item->net_amount,2)}}</td>
                                                </tr>
                                                <tr class="subhead">
                                                    <td colspan="2" style="padding: 0;">
                                                        <div class=" ml-2">
                                                            <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                                @foreach (App\JournalRecord::balance_sheet_details($from_date, $to_date, $item->master_accounts_id, 'DR') as $detail)
                                                                    <tr class="tax-sub-head-details" id="{{$detail->account_head_id}}">
                                                                        <td class="td-border">{{$detail->ac_head->fld_ac_head??''}}</td>
                                                                        <td class="text-right pr-1" style="width: 200px !important;">{{number_format($detail->net_amount<0?$detail->net_amount*-1:$detail->net_amount,2)}}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
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
                                                $profit = $assets_results->sum('net_amount') - $liability_results->sum('net_amount');
                                            @endphp
                                            <tr>
                                                <td>
                                                    {{$profit>=0?'PROFIT':'LOSS'}}
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
                                                <th class="text-right"> {{number_format($assets_results->sum('net_amount')<0?$assets_results->sum('net_amount')*-1:$assets_results->sum('net_amount'),2,'.','')}}</th>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table  class="table table-sm table-hover">
                                            @php
                                                $net_profit = $liability_results->sum('net_amount')+$profit;
                                            @endphp
                                            <tr>
                                                <th></th>
                                                <th class="text-right"> {{number_format($net_profit<0?($net_profit*-1):$net_profit,2,'.','')}}</th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @include('layouts.backend.partial.modal-footer-info')
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        $(document).on('click', '.head-details', function () {
            var $this = $(this);
            var $subheadRow = $this.next('tr.subhead');
            $this.find('.bx').toggleClass('d-none');
            $this.find('td').toggleClass('active-bg');
            $subheadRow.toggleClass('d-none');
        });
        window.onbeforeprint = function() {
            document.querySelectorAll('.empty-section').forEach(el => el.style.display = 'none');
        };
    </script>
@endpush

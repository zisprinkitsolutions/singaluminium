

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
        body {-webkit-print-color-adjust: exact;}
        .row{
            display: flex;
        }
        .col-md-6{
            max-width: 50% !important;
        }
        .print-hideen{
            display: none !important;
        }
        .nav.nav-tabs ~ .tab-content{
            border: #fff;
        }
        .bx{
            display: none !important;
        }
        .table-bg{
            background: #e3e3e3 !important;
            print-color-adjust: exact; 
        }
        .td-border{
            border-left: 1px solid #fff !important
        }
    }
    .card-body {
        flex: 1 1 auto;
        min-height: 1px;
        padding: 0rem !important;
    }
</style>

<div class="app-content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.report._header',['activeMenu' => 'tax-report'])
            <div class="tab-content bg-white">
                <div class="tab-pane active p-2">
                    <div class="content-body">

                        <section id="widgets-Statistics" class="col-md-8">
                            <div class="row mt-1 print-hideen" style="margin-left: 5px !important">
                                <div class="col-md-10">
                                    <form action="" method="GET" class="d-flex row">

                                        <div class="row form-group col-md-5 pr-2">
                                            <input type="text" class="form-control inputFieldHeight datepicker" placeholder="From Date" required name="from_date" autocomplete="off">
                                        </div>
                                        <div class="row form-group col-md-5">
                                            <input type="text" class="form-control inputFieldHeight datepicker" placeholder="To Date" required name="to_date" autocomplete="off">
                                        </div>

                                        <div class="col-md-2">
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
                                <div class="col-md-2 text-right">
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
                                @include('layouts.backend.partial.modal-header-info')

                                <table class="table table-bordered table-sm ">
                                    <thead class="thead">
                                        <tr class="text-center">
                                            <th colspan="2">
                                                <h4>Corporate Tax Report</h4>
                                                <h6>Company Name: {{App\Office::find(Auth::user()->office_id)->name}}</h6>
                                                <p>Date: {{date('d/m/Y', strtotime($from_date)) .'-'. date('d/m/Y', strtotime($to_date))}}</p>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="pl-1">Statement of Profit/Loss</th>
                                            <th class="text-right pr-1">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="purch-body">
                                        <tr class="head-details" data-target=".operating_revenue" data-column="account_head_id">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Operating Revenue
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($operating_revenue->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($operating_revenue as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details" data-target="expenditure">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Expenditure in Deriving Operating Revenue
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($expenditure->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($expenditure as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Non-Operating Expense
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($non_operating_expense->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($non_operating_expense as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Salaries, Wages and Related Charges
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($salary_wages->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($salary_wages as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Depreciation and Amortisation
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($depreciation->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($depreciation as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Fines and Penalties
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($fines_penalties->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($fines_penalties as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Donations
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($donations->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($donations as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Entertainment Expenses
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($entertainment_expense->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($entertainment_expense as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Non-Operating Revenue
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($non_operating_revenue->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($non_operating_revenue as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Dividends Received
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($dividends_received->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($dividends_received as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Other Non-operating Revenue
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($other_non_operating_revenue->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($other_non_operating_revenue as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Interest Income
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($interest_income->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($interest_income as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Interest Expense
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($interest_expenditure->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($interest_expenditure as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Gains Disposal of Assets
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($gains_disposal_assets->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($gains_disposal_assets as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Losses Disposal of Assets
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($losses_disposal_assets->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($losses_disposal_assets as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Foreign Exchange Gains
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($foreign_exchange_gains->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($foreign_exchange_gains as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td>
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Foreign Exchange Losses
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($foreign_exchange_losses->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($foreign_exchange_losses as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Other Expneses
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($other_expense->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($other_expense as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <th class="pl-1">Statement of Financial Position</th>
                                            <th class="text-right pr-1">Amount</th>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Total Current Assets
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($current_asset->sum('total_dr_amount')-$current_asset->sum('total_cr_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($current_asset as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Property, Plant and Equipment
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($property_plant->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($property_plant as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Intangible Assets
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($intangible_assets->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($intangible_assets as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Financial Assets
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($financial_assets->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($financial_assets as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Other Non-Current Assets
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($other_current_assets->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($other_current_assets as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Total Current Liabilities
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($current_liabilities->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($current_liabilities as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Total Non-Current Liabilities
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($non_current_liabilities->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($non_current_liabilities as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Share Capital
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($share_capital->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($share_capital as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Retained Earnings
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($retained_earnings->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($retained_earnings as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="head-details">
                                            <td style="font-size: 13px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Other Equity
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($other_equity->sum('total_amount'),2)}}</td>
                                        </tr>
                                        <tr class="subhead d-none">
                                            <td colspan="2" style="padding: 0;">
                                                <div class=" ml-2">
                                                    <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                        @foreach ($other_equity as $item)
                                                            <tr class="">
                                                                <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                                                                <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->total_amount,2)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
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

@push('js')
    <script>
        $(document).on('click', '.head-details', function () {
            $(this).find('.bx').toggleClass('d-none');
            $(this).find('td').toggleClass('active-bg');
            $(this).next('tr').toggleClass('d-none');
        });
    </script>
@endpush
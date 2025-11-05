@extends('layouts.backend.app')
@section('content')
    @include('layouts.backend.partial.style')
    <style>
        .tabPadding {
            padding: 5px;
        }

        .padding-right {
            padding-right: 10px;
        }

        td {
            font-size: 12px !important;
            color: #000 !important;
        }

        th {
            font-size: 14px !important;
            color: #000 !important;
        }

        @media(min-width:1300px) {
            .padding-right {
                padding-right: 0px !important;
            }
        }

        .card-body {
            flex: 1 1 auto;
            min-height: 1px;
            padding: 0rem !important;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }


        h4,h6{
            color: #000 !important
        }

        .negetive {
            color: red !important;
        }
        .final_profit{
            color:rgb(33, 175, 33) !important;
        }

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
                            <section id="widgets-Statistics " style="max-width: 1080px;">
                                <div class="row mt-1" style="margin-left: 5px !important" >
                                    <div class="col-md-12 pl-0">
                                        <form action="" method="GET" style="margin-bottom:0px">
                                            <div class="d-flex">
                                                <div class="form-group" style="width:30%;">
                                                    <input type="text" class="inputFieldHeight form-control datepicker"
                                                        name="from" placeholder="From" id="from" autocomplete="off"
                                                        >
                                                </div>
                                                <div class="form-group" style="width:30%;padding-left:8px; margin-right: 10px;">
                                                    <input type="text" class="inputFieldHeight form-control datepicker"
                                                        name="to" placeholder="To" id="to" autocomplete="off"
                                                        >
                                                </div>
                                                <div class="d-none">
                                                <select name="company_id" class="inputFieldHeight form-control common-select2" style="margin-left: 10px;">
                                                    <option value="">Select Subsidiary..</option>
                                                    <option value="0" {{ $company_id == 0 ? 'selected' : '' }}>SINGH ALUMINIUM AND STEEL</option>
                                                    @foreach($companies as $company)
                                                    <option value="{{ $company->id }}" {{ $company_id == $company->id ? 'selected' : '' }}>
                                                        {{ $company->company_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                </div>
                                                <div class="form-group" style="width:15%;padding-left:8px;">
                                                    <button type="submit"
                                                        class="btn mSearchingBotton inputFieldHeight formButton"
                                                        title="Search">
                                                        <div class="d-flex" style="padding: 0 10px;">
                                                            <div class="formSaveIcon">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                    width="20">
                                                            </div>
                                                            <div><span>Search</span></div>
                                                        </div>
                                                    </button>
                                                </div>

                                                <div class="d-flex justify-content-end" style="width:70%">

                                                    <a href="#" class="btn btn_create mPrint formButton mb-2"
                                                        title="Print" onclick="window.print()">
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
                                    <div class="col-md-12">
                                        <div class="card-body pt-0 pb-0" id="print_section">
                                            <table class="table table-sm table-hover">
                                                <tr>
                                                    <th colspan="4" class="text-center">
                                                        <h4>Income Statement</h4>
                                                        <h6> {{ date('d/m/Y', strtotime($from)) }} -
                                                            {{ date('d/m/Y', strtotime($to)) }}</h4>
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <th colspan='3'>Operating Revenue</th>
                                                </tr>
                                                @foreach ($revenues as $rev)
                                                    <tr>
                                                        <td style="width: 80px !important"></td>
                                                        <td>{{ $rev->fld_ac_head }}</td>
                                                        <td
                                                            class="text-right pr-2 {{ $rev->balance < 0 ? 'negetive' : '' }}">
                                                            {{ $rev->balance < 0 ? '(' . number_format(abs($rev->balance), 2) . ')' : number_format($rev->balance, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th colspan="2" class="text-center">TOTAL OPERATING REVENUE</th>
                                                    <th
                                                        class="text-right pr-2 {{ $revenue_balance < 0 ? 'negetive' : '' }}">
                                                        {{ $revenue_balance < 0
                                                            ? '(' . number_format(abs($revenue_balance), 2) . ')'
                                                            : number_format($revenue_balance, 2) }}
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <th colspan='3'>OPERATING EXPENSES </th>
                                                </tr>

                                                <tr>
                                                    <th style="width: 80px !important"></th>
                                                    <th>Cost Of Good Sold</th>
                                                    <th class="text-right pr-2">
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td style="width: 80px !important"></td>
                                                    <td>BEGINNING INVENTORY</td>
                                                    <td
                                                        class="text-right pr-2 {{ $inventory->beginningBalance < 0 ? 'negetive' : '' }}">
                                                        {{ $inventory->beginningBalance < 0
                                                            ? '(' . number_format(abs($inventory->beginningBalance), 2) . ')'
                                                            : number_format($inventory->beginningBalance, 2) }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="width: 80px !important"></td>
                                                    <td>PURCHASE INVENTORY</td>
                                                    <td
                                                        class="text-right pr-2 {{ $inventory->purchaseAmount < 0 ? 'negetive' : '' }}">
                                                        {{ $inventory->purchaseAmount < 0
                                                            ? '(' . number_format(abs($inventory->purchaseAmount), 2) . ')'
                                                            : number_format($inventory->purchaseAmount, 2) }}
                                                    </td>
                                                </tr>

                                                @foreach ($cog_s as $cog)
                                                    <tr>
                                                        <td style="width: 80px !important"></td>
                                                        <td>{{ $cog->fld_ac_head }}</td>
                                                        <td
                                                            class="text-right pr-2 {{ $cog->balance < 0 ? 'negetive' : '' }}">
                                                            {{ $cog->balance < 0 ? '(' . number_format(abs($cog->balance), 2) . ')' : number_format($cog->balance, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                <tr>
                                                    <td style="width: 80px !important"></td>
                                                    <td>END INVENTORY</td>
                                                    <td
                                                        class="text-right pr-2 {{ $inventory->endBalance < 0 ? 'negetive' : '' }}">
                                                        {{ $inventory->endBalance > 0
                                                            ? '(' . number_format($inventory->endBalance, 2) . ')'
                                                            : number_format(abs($inventory->endBalance), 2) }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th colspan="2" class="text-center">TOTAL COST OF GOODS SOLD</th>
                                                    <th class="text-right pr-2 {{ $total_cogs < 0 ? 'negetive' : '' }}">
                                                        {{ $total_cogs < 0
                                                            ? '(' . number_format(abs($total_cogs), 2) . ')'
                                                            : number_format($total_cogs, 2) }}
                                                    </th>
                                                </tr>



                                                <tr>
                                                    <th colspan="2" class="text-center">GROSS PROFIT</th>
                                                    <th class="text-right pr-2 {{ $gross_profit < 0 ? 'negetive' : '' }}">
                                                        {{ $gross_profit < 0
                                                            ? '(' . number_format(abs($gross_profit), 2) . ')'
                                                            : number_format($gross_profit, 2) }}
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <th colspan='3'>OVERHEAD</th>
                                                </tr>

                                                @foreach ($overHeads as $overHead)
                                                    <tr>
                                                        <td style="width: 80px !important"></td>
                                                        <td>{{ $overHead->fld_ac_head }}</td>
                                                        <td
                                                            class="text-right pr-2 {{ $overHead->balance < 0 ? 'negetive' : '' }}">
                                                            {{ $overHead->balance < 0 ? '(' . number_format(abs($overHead->balance), 2) . ')' : number_format($overHead->balance, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                <tr>
                                                    <th colspan='3'>ADMINISTRATIVE EXPENSES</th>
                                                </tr>

                                                @foreach ($administrative_exp as $exp)
                                                    <tr>
                                                        <td style="width: 80px !important"></td>
                                                        <td>{{ $exp->fld_ac_head }}</td>
                                                        <td
                                                            class="text-right pr-2 {{ $exp->balance < 0 ? 'negetive' : '' }}">
                                                            {{ $exp->balance < 0 ? '(' . number_format(abs($exp->balance), 2) . ')' : number_format($exp->balance, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach



                                                <tr>
                                                    <th colspan="2" class="text-center">TOTAL EXPENSES</th>
                                                    <th
                                                        class="text-right pr-2 {{ $total_op_expense < 0 ? 'negetive' : '' }}">
                                                        {{ $total_op_expense < 0
                                                            ? '(' . number_format(abs($total_op_expense), 2) . ')'
                                                            : number_format($total_op_expense, 2) }}
                                                    </th>
                                                </tr>



                                                <tr>
                                                    <th colspan="2" class="text-center"> {{ $net_profit_loss < 0 ? 'NET LOSS' : 'NET PROFIT'}}</th>
                                                    <th
                                                        class="text-right pr-2 {{ $net_profit_loss < 0 ? 'negetive' : '' }}">
                                                        {{ $net_profit_loss < 0
                                                            ? '(' . number_format(abs($net_profit_loss), 2) . ')'
                                                            : number_format($net_profit_loss, 2) }}
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <th colspan="2" class="text-center">DEPRECIATION</th>
                                                    <th
                                                        class="text-right pr-2 {{ $depreciation->amount < 0 ? 'negetive' : '' }}">
                                                        {{ $depreciation->amount < 0
                                                            ? '(' . number_format(abs($depreciation->amount), 2) . ')'
                                                            : number_format($depreciation->amount, 2) }}
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <th colspan="2" class="text-center">{{$final_profit < 0 ? 'Loss':'PROFIT'}} </th>
                                                    <th class="text-right pr-2 {{ $final_profit < 0 ? 'negetive' : '' }}">
                                                        {{ $final_profit < 0
                                                            ? '(' . number_format(abs($final_profit), 2) . ')'
                                                            : number_format($final_profit, 2) }}
                                                    </th>
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
    <div class="card-body pt-0 pb-0" id="print_section" style="display: flex; justify-content: center;">
        <table class="table table-sm table-hover" style="width: 900px;">
            <tr>
                <th colspan="4" class="text-center">
                    <h4>Income Statement</h4>
                    <h6> {{ date('d/m/Y', strtotime($from)) }} -
                        {{ date('d/m/Y', strtotime($to)) }}</h4>
                </th>
            </tr>

            <tr>
                <th colspan='3'>Operating Revenue</th>
            </tr>
            @foreach ($revenues as $rev)
                <tr>
                    <td style="width: 80px !important"></td>
                    <td>{{ $rev->fld_ac_head }}</td>
                    <td class="text-right pr-2 {{ $rev->balance < 0 ? 'negetive' : '' }}">
                        {{ $rev->balance < 0 ? '(' . number_format(abs($rev->balance), 2) . ')' : number_format($rev->balance, 2) }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <th colspan="2" class="text-center">TOTAL OPERATING REVENUE</th>
                <th class="text-right pr-2 {{ $revenue_balance < 0 ? 'negetive' : '' }}">
                    {{ $revenue_balance < 0
                        ? '(' . number_format(abs($revenue_balance), 2) . ')'
                        : number_format($revenue_balance, 2) }}
                </th>
            </tr>

            <tr>
                <th colspan='3'>OPERATING EXPENSES </th>
            </tr>

            <tr>
                <th style="width: 80px !important"></th>
                <th>Cost Of Good Sold</th>
                <th class="text-right pr-2">
                </th>
            </tr>
            <tr>
                <td style="width: 80px !important"></td>
                <td>BEGINNING INVENTORY</td>
                <td class="text-right pr-2 {{ $inventory->beginningBalance < 0 ? 'negetive' : '' }}">
                    {{ $inventory->beginningBalance < 0
                        ? '(' . number_format(abs($inventory->beginningBalance), 2) . ')'
                        : number_format($inventory->beginningBalance, 2) }}
                </td>
            </tr>

            <tr>
                <td style="width: 80px !important"></td>
                <td>PURCHASE INVENTORY</td>
                <td class="text-right pr-2 {{ $inventory->purchaseAmount < 0 ? 'negetive' : '' }}">
                    {{ $inventory->purchaseAmount < 0
                        ? '(' . number_format(abs($inventory->purchaseAmount), 2) . ')'
                        : number_format($inventory->purchaseAmount, 2) }}
                </td>
            </tr>

            @foreach ($cog_s as $cog)
                <tr>
                    <td style="width: 80px !important"></td>
                    <td>{{ $cog->fld_ac_head }}</td>
                    <td class="text-right pr-2 {{ $cog->balance < 0 ? 'negetive' : '' }}">
                        {{ $cog->balance < 0 ? '(' . number_format(abs($cog->balance), 2) . ')' : number_format($cog->balance, 2) }}
                    </td>
                </tr>
            @endforeach

            <tr>
                <td style="width: 80px !important"></td>
                <td>END INVENTORY</td>
                <td class="text-right pr-2 {{ $inventory->endBalance < 0 ? 'negetive' : '' }}">
                    {{ $inventory->endBalance > 0
                        ? '(' . number_format($inventory->endBalance, 2) . ')'
                        : number_format(abs($inventory->endBalance), 2) }}
                </td>
            </tr>

            <tr>
                <th colspan="2" class="text-center">TOTAL COST OF GOODS SOLD</th>
                <th class="text-right pr-2 {{ $total_cogs < 0 ? 'negetive' : '' }}">
                    {{ $total_cogs < 0
                        ? '(' . number_format(abs($total_cogs), 2) . ')'
                        : number_format($total_cogs, 2) }}
                </th>
            </tr>



            <tr>
                <th colspan="2" class="text-center">GROSS PROFIT</th>
                <th class="text-right pr-2 {{ $gross_profit < 0 ? 'negetive' : '' }}">
                    {{ $gross_profit < 0
                        ? '(' . number_format(abs($gross_profit), 2) . ')'
                        : number_format($gross_profit, 2) }}
                </th>
            </tr>

            <tr>
                <th colspan='3'>OVERHEAD</th>
            </tr>

            @foreach ($overHeads as $overHead)
                <tr>
                    <td style="width: 80px !important"></td>
                    <td>{{ $overHead->fld_ac_head }}</td>
                    <td class="text-right pr-2 {{ $overHead->balance < 0 ? 'negetive' : '' }}">
                        {{ $overHead->balance < 0 ? '(' . number_format(abs($overHead->balance), 2) . ')' : number_format($overHead->balance, 2) }}
                    </td>
                </tr>
            @endforeach

            <tr>
                <th colspan='3'>ADMINISTRATIVE EXPENSES</th>
            </tr>

            @foreach ($administrative_exp as $exp)
                <tr>
                    <td style="width: 80px !important"></td>
                    <td>{{ $exp->fld_ac_head }}</td>
                    <td class="text-right pr-2 {{ $exp->balance < 0 ? 'negetive' : '' }}">
                        {{ $exp->balance < 0 ? '(' . number_format(abs($exp->balance), 2) . ')' : number_format($exp->balance, 2) }}
                    </td>
                </tr>
            @endforeach



            <tr>
                <th colspan="2" class="text-center">TOTAL EXPENSES</th>
                <th class="text-right pr-2 {{ $total_op_expense < 0 ? 'negetive' : '' }}">
                    {{ $total_op_expense < 0
                        ? '(' . number_format(abs($total_op_expense), 2) . ')'
                        : number_format($total_op_expense, 2) }}
                </th>
            </tr>



            <tr>
                <th colspan="2" class="text-center {{ $final_profit < 0 ? 'negetive' : 'final_profit' }}"> {{$net_profit_loss < 0 ? 'NET LOSS' : 'NET PROFIT'}} </th>
                <th class="text-right pr-2 {{ $net_profit_loss < 0 ? 'negetive' : 'final_profit' }}">
                    {{ $net_profit_loss < 0
                        ? '(' . number_format(abs($net_profit_loss), 2) . ')'
                        : number_format($net_profit_loss, 2) }}
                </th>
            </tr>

            <tr>
                <th colspan="2" class="text-center">DEPRECIATION</th>
                <th class="text-right pr-2 {{ $depreciation->amount < 0 ? 'negetive' : 'final_profit' }}">
                    {{ $depreciation->amount < 0
                        ? '(' . number_format(abs($depreciation->amount), 2) . ')'
                        : number_format($depreciation->amount, 2) }}
                </th>
            </tr>

            <tr>
                <th colspan="2" class="text-center {{ $final_profit < 0 ? 'negetive' : 'final_profit' }}"> {{$net_profit_loss < 0 ? 'NET LOSS' : 'NET PROFIT'}} </th>
                <th class="text-right pr-2 {{ $final_profit < 0 ? 'negetive' : 'final_profit' }}">
                    {{ $final_profit < 0
                        ? '(' . number_format(abs($final_profit), 2) . ')'
                        : number_format($final_profit, 2) }}
                </th>
            </tr>
        </table>
    </div>
    @include('layouts.backend.partial.modal-footer-info')
</section>

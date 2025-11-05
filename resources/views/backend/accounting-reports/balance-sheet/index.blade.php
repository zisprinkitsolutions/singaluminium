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

        .negetive {
            color: red !important
        }
        h4,h6{
            color: #000 !important
        }

    </style><style>
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
            .print-hideen{
                display: none !important;
            }
            .nav.nav-tabs ~ .tab-content{
                border: #fff !important;
            }
        }
    </style>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.report._header', [
                    'activeMenu' => 'account_report',
                ])
                <div class="tab-content bg-white">
                    <div class="tab-pane active p-1">
                        <div class="content-body">
                            <div class="d-flex justify-content-between align-items-center print-hideen">
                                @include('clientReport.report._accounting_report_subheader', [
                                    'activeMenu' => 'balance_sheet',
                                ])
                            </div>
                            <section id="widgets-Statistics " style="max-width: 1080px;">
                                <div class="row mt-1" style="margin-left: 5px !important" >
                                    <div class="col-md-12 pl-0 print-hideen">
                                        <form action="" method="GET" style="margin-bottom:0px">
                                            <div class="d-flex">
                                                <div class="form-group" style="width:20%;">
                                                    <input type="text" class="inputFieldHeight form-control datepicker"
                                                        name="from" placeholder="Date" id="from" autocomplete="off"
                                                        >
                                                </div>
                                                <div class="form-group d-none" style="width:15%;padding-left:8px; margin-right: 10px;">
                                                    <input type="text" class="inputFieldHeight form-control datepicker"
                                                        name="to" placeholder="To" id="to" autocomplete="off"
                                                        >
                                                </div>
                                                 <div class="form-group d-none" style="margin-left: 10px;">
                                                    <select name="company_id" class="inputFieldHeight form-control common-select2" >
                                                        <option value="">Select Subsidiary..</option>
                                                        <option value="0" {{ $company_id==0 ? 'selected' : '' }}>SINGH ALUMINIUM AND STEEL</option>
                                                        @foreach($companies as $company)
                                                        <option value="{{ $company->id }}" {{ $company_id==$company->id ? 'selected' : '' }}>
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
                                                        <h4>Balance Sheet</h4>
                                                        <h6>
                                                            {{ date('d/m/Y', strtotime($to)) }}</h4>
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <th colspan='3'>Current Asset</th>
                                                </tr>
                                                @foreach ($current_assets as $c_asset)
                                                    <tr>
                                                        <td style="width: 80px !important"></td>
                                                        <td>{{ $c_asset->fld_ac_head }}</td>
                                                        <td
                                                            class="text-right pr-2 {{ $c_asset->balance < 0 ? 'negetive' : '' }}">
                                                            {{ $c_asset->balance < 0 ? '(' . number_format(abs($c_asset->balance), 2) . ')' : number_format($c_asset->balance, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th colspan="2" class="text-center">TOTAL CURRENT ASSET</th>
                                                    <th
                                                        class="text-right pr-2 {{ $current_asset_balance < 0 ? 'negetive' : '' }}">
                                                        {{ $current_asset_balance < 0
                                                            ? '(' . number_format(abs($current_asset_balance), 2) . ')'
                                                            : number_format($current_asset_balance, 2) }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th colspan='3'>Fixed Asset</th>
                                                </tr>
                                                 @foreach ($fixed_assets as $f_asset)
                                                    <tr>
                                                        <td style="width: 80px !important"></td>
                                                        <td>{{ $f_asset->fld_ac_head }}</td>
                                                        <td
                                                            class="text-right pr-2 {{ $f_asset->balance < 0 ? 'negetive' : '' }}">
                                                            {{ $f_asset->balance < 0 ? '(' . number_format(abs($f_asset->balance), 2) . ')' : number_format($f_asset->balance, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th colspan="2" class="text-center">TOTAL FIXED ASSET </th>
                                                    <th
                                                        class="text-right pr-2 {{ $fixed_asset_balance < 0 ? 'negetive' : '' }}">
                                                        {{ $fixed_asset_balance < 0
                                                            ? '(' . number_format(abs($fixed_asset_balance), 2) . ')'
                                                            : number_format($fixed_asset_balance, 2) }}
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <th colspan='3'>Other Asset</th>
                                                </tr>
                                                 @foreach ($other_assets as $o_asset)
                                                    <tr>
                                                        <td style="width: 80px !important"></td>
                                                        <td>{{ $o_asset->fld_ac_head }}</td>
                                                        <td
                                                            class="text-right pr-2 {{ $o_asset->balance < 0 ? 'negetive' : '' }}">
                                                            {{ $o_asset->balance < 0 ? '(' . number_format(abs($o_asset->balance), 2) . ')' : number_format($o_asset->balance, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th colspan="2" class="text-center">TOTAL OTHER ASSET </th>
                                                    <th
                                                        class="text-right pr-2 {{ $other_asset_balance < 0 ? 'negetive' : '' }}">
                                                        {{ $other_asset_balance < 0
                                                            ? '(' . number_format(abs($other_asset_balance), 2) . ')'
                                                            : number_format($other_asset_balance, 2) }}
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <th colspan="2" class="text-center">TOTAL ASSET </th>
                                                    <th
                                                        class="text-right pr-2 {{ $total_asset < 0 ? 'negetive' : '' }}">
                                                        {{ $total_asset < 0
                                                            ? '(' . number_format(abs($total_asset), 2) . ')'
                                                            : number_format($total_asset, 2) }}
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <th colspan='3'>Current Liability</th>
                                                </tr>
                                                 @foreach ($current_liability as $c_liability)
                                                    <tr>
                                                        <td style="width: 80px !important"></td>
                                                        <td>{{ $c_liability->fld_ac_head }}</td>
                                                        <td
                                                            class="text-right pr-2 {{ $c_liability->balance < 0 ? 'negetive' : '' }}">
                                                            {{ $c_liability->balance < 0 ? '(' . number_format(abs($c_liability->balance), 2) . ')' : number_format($c_liability->balance, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th colspan="2" class="text-center">TOTAL Total Current Liability </th>
                                                    <th
                                                        class="text-right pr-2 {{ $total_current_liability < 0 ? 'negetive' : '' }}">
                                                        {{ $total_current_liability < 0
                                                            ? '(' . number_format(abs($total_current_liability), 2) . ')'
                                                            : number_format($total_current_liability, 2) }}
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <th colspan='3'>Non-Current Liability</th>
                                                </tr>
                                                 @foreach ($non_current_liability as $o_equity)
                                                    <tr>
                                                        <td style="width: 80px !important"></td>
                                                        <td>{{ $o_equity->fld_ac_head }}</td>
                                                        <td
                                                            class="text-right pr-2 {{ $o_equity->balance < 0 ? 'negetive' : '' }}">
                                                            {{ $o_equity->balance < 0 ? '(' . number_format(abs($o_equity->balance), 2) . ')' : number_format($o_equity->balance, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th colspan="2" class="text-center">TOTAL Current Liability </th>
                                                    <th
                                                        class="text-right pr-2 {{ $total_non_current_liability < 0 ? 'negetive' : '' }}">
                                                        {{ $total_non_current_liability < 0
                                                            ? '(' . number_format(abs($total_non_current_liability), 2) . ')'
                                                            : number_format($total_non_current_liability, 2) }}
                                                    </th>
                                                </tr>


                                                <tr>
                                                    <th colspan='3'>OWNER'S EQUITY</th>
                                                </tr>
                                                 @foreach ($owners_equity as $o_equity)
                                                    <tr>
                                                        <td style="width: 80px !important"></td>
                                                        <td>{{ $o_equity->fld_ac_head }}</td>
                                                        <td
                                                            class="text-right pr-2 {{ $o_equity->balance < 0 ? 'negetive' : '' }}">
                                                            {{ $o_equity->balance < 0 ? '(' . number_format(abs($o_equity->balance), 2) . ')' : number_format($o_equity->balance, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th colspan="2" class="text-center">TOTAL OWNER'S EQUITY </th>
                                                    <th
                                                        class="text-right pr-2 {{ $total_owners_equity < 0 ? 'negetive' : '' }}">
                                                        {{ $total_owners_equity < 0
                                                            ? '(' . number_format(abs($total_owners_equity), 2) . ')'
                                                            : number_format($total_owners_equity, 2) }}
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <th colspan='3'>Other Liability</th>
                                                </tr>
                                                 @foreach ($other_liabilities as $o_liability)
                                                    <tr>
                                                        <td style="width: 80px !important"></td>
                                                        <td>{{ $o_liability->fld_ac_head }}</td>
                                                        <td
                                                            class="text-right pr-2 {{ $o_liability->balance < 0 ? 'negetive' : '' }}">
                                                            {{ $o_liability->balance < 0 ? '(' . number_format(abs($o_liability->balance), 2) . ')' : number_format($o_liability->balance, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th colspan="2" class="text-center">TOTAL Other Liability </th>
                                                    <th
                                                        class="text-right pr-2 {{ $other_liability_balance < 0 ? 'negetive' : '' }}">
                                                        {{ $other_liability_balance < 0
                                                            ? '(' . number_format(abs($other_liability_balance), 2) . ')'
                                                            : number_format($other_liability_balance, 2) }}
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <th colspan="2" class="text-center">Retained Earning <small>({{ date('d/m/Y', strtotime($from)) }})</small> </th>
                                                    <th
                                                        class="text-right pr-2 {{ $retained_earning < 0 ? 'negetive' : '' }}">
                                                        {{ $retained_earning < 0
                                                            ? '(' . number_format(abs($retained_earning), 2) . ')'
                                                            : number_format($retained_earning, 2) }}
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <th colspan="2" class="text-center">{{$current_profit < 0 ? 'Loss':'Profit'}} </th>
                                                    <th
                                                        class="text-right pr-2 {{ $current_profit < 0 ? 'negetive' : '' }}">
                                                        {{ $current_profit < 0
                                                            ? '(' . number_format(abs($current_profit), 2) . ')'
                                                            : number_format($current_profit, 2) }}
                                                    </th>
                                                </tr>

                                                @php
                                                    $tl=$current_profit+$total_liability;
                                                @endphp

                                                <tr>
                                                    <th colspan="2" class="text-center">Total Liability </th>
                                                    <th
                                                        class="text-right pr-2 {{ $tl < 0 ? 'negetive' : '' }}">
                                                        {{ $tl < 0
                                                            ? '(' . number_format(abs($tl), 2) . ')'
                                                            : number_format($tl, 2) }}
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

<section class="print-layout ">
    @include('layouts.backend.partial.modal-header-info')
    <div class="card-body pt-0 pb-0" id="print_section" style="display: flex; justify-content: center;">

    </div>
    @include('layouts.backend.partial.modal-footer-info')
</section>

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

        h4,
        h6 {
            color: #000 !important
        }
    </style>
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

            .print-hideen {
                display: none !important;
            }

            .nav.nav-tabs~.tab-content {
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
                                    'activeMenu' => 'cash-flow-statement',
                                ])
                            </div>
                            <section id="widgets-Statistics " style="max-width: 1080px;">
                                <div class="row mt-1" style="margin-left: 5px !important">
                                    <div class="col-md-12 pl-0 print-hideen">
                                        <form action="" method="GET" style="margin-bottom:0px">
                                            <div class="d-flex">
                                                <div class="form-group" style="width:20%;">
                                                    <select id="yearSelect" class="form-control inputFieldHeight"
                                                        name="year">
                                                        <option value="">-- Select Year --</option>
                                                    </select>

                                                </div>

                                                <div class="form-group" style="margin-left: 10px;">
                                                    <select name="company_id"
                                                        class="inputFieldHeight form-control common-select2">
                                                        <option value="">Select Subsidiary..</option>
                                                        <option value="0" {{ $company_id == 0 ? 'selected' : '' }}>SEA
                                                            BRIDGE BUILDING CONT. LLC</option>
                                                        @foreach ($companies as $company)
                                                            <option value="{{ $company->id }}"
                                                                {{ $company_id == $company->id ? 'selected' : '' }}>
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
                                                        <h4>Cash Flow Statement</h4>
                                                        <h6>{{ $year }}</h6>
                                                    </th>
                                                </tr>
                                            {{-- Start CASH FLOW FROM OPERATION --}}
                                                <tr>
                                                    <th colspan="2" class="text-left">CASH FLOW FROM OPERATION </th>
                                                    <th
                                                        class="text-right pr-2 ">
                                                        AMOUNT
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td style="width: 80px !important"></td>
                                                    <td>NET {{ $profit < 0 ? 'LOSS' : 'PROFIT' }}</td>
                                                    <td class="text-right pr-2 {{ $profit < 0 ? 'negetive' : '' }}">
                                                        {{ $profit < 0 ? '(' . number_format(abs($profit), 2) . ')' : number_format($profit, 2) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th colspan='3' class="pl-3">ADJUSTMENT FOR NON CASH ITEMS </th>
                                                </tr>
                                                 <tr>
                                                    <td style="width: 80px !important"></td>
                                                    <td>DEPRECIATION</td>
                                                    <td class="text-right pr-2 ">
                                                        {{  number_format(abs($depreciation->debit_amount), 2) }}
                                                    </td>
                                                </tr>

                                                 <tr>
                                                    <th colspan='3' class="pl-3">DECREASE IN CURRENT ASSETS </th>
                                                </tr>
                                                 <tr>
                                                    <td style="width: 80px !important"></td>
                                                    <td>INVENTORIES</td>
                                                    <td class="text-right pr-2 {{ $inventory_decrease < 0 ? 'negetive' : '' }}">
                                                        {{ $inventory_decrease < 0 ? '(' . number_format(abs($inventory_decrease), 2) . ')' : number_format($inventory_decrease, 2) }}
                                                    </td>
                                                </tr>

                                                 <tr>
                                                    <td style="width: 80px !important"></td>
                                                    <td>ACCOUNTS RECEIVABLES </td>
                                                    <td class="text-right pr-2 {{ $receivable_decrease < 0 ? 'negetive' : '' }}">
                                                        {{ $receivable_decrease < 0 ? '(' . number_format(abs($receivable_decrease), 2) . ')' : number_format($receivable_decrease, 2) }}
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <th colspan='3' class="pl-3">INCREASE IN CURRENT LIABILITIES </th>
                                                </tr>
                                                <tr>
                                                    <td style="width: 80px !important"></td>
                                                    <td>ACCOUNTS PAYABLES </td>
                                                    <td class="text-right pr-2 {{ $payable_increase < 0 ? 'negetive' : '' }}">
                                                        {{ $payable_increase < 0 ? '(' . number_format(abs($payable_increase), 2) . ')' : number_format($payable_increase, 2) }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th colspan="2" class="text-right">NET CASH GENERATED FROM OPERATIONS </th>
                                                    <th
                                                        class="text-right pr-2 {{ $net_cash_from_operation < 0 ? 'negetive' : '' }}">
                                                        {{ $net_cash_from_operation < 0
                                                            ? '(' . number_format(abs($net_cash_from_operation), 2) . ')'
                                                            : number_format($net_cash_from_operation, 2) }}
                                                    </th>
                                                </tr>
                                                 <tr>
                                                    <td style="width: 80px !important"></td>
                                                    <td>LESS: INCOME TAX PAID </td>
                                                    <td class="text-right pr-2 {{ $tax_paid < 0 ? 'negetive' : '' }}">
                                                        {{ $tax_paid < 0 ? '(' . number_format(abs($tax_paid), 2) . ')' : number_format($tax_paid, 2) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th colspan="2" class="text-right">NET CASH FROM OPERATING ACTIVITIES </th>
                                                    <th
                                                        class="text-right pr-2 {{ $net_cash_from_operation_act < 0 ? 'negetive' : '' }}">
                                                        {{ $net_cash_from_operation_act < 0
                                                            ? '(' . number_format(abs($net_cash_from_operation_act), 2) . ')'
                                                            : number_format($net_cash_from_operation_act, 2) }}
                                                    </th>
                                                </tr>
                                            {{-- End CASH FLOW FROM OPERATION--}}

                                            {{-- Start CASH FLOWS FROM INVESTING ACTIVITIES --}}
                                                <tr>
                                                    <th colspan="2" class="text-left">CASH FLOW FROM OPERATION </th>
                                                    <th
                                                        class="text-right pr-2 ">

                                                    </th>
                                                </tr>
                                            {{-- Start CASH FLOWS FROM INVESTING ACTIVITIES --}}

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

@push('js')
    <script>
        (function populateYears(startYear = 2000) {
            const select = document.getElementById('yearSelect');
            const currentYear = new Date().getFullYear();
            for (let y = currentYear; y >= startYear; y--) {
                const opt = document.createElement('option');
                opt.value = y;
                opt.text = y;
                // opt.selected = (y === currentYear); // uncomment to default-select current year
                select.appendChild(opt);
            }
        })();
    </script>
@endpush

<section class="print-layout ">
    @include('layouts.backend.partial.modal-header-info')
    <div class="card-body pt-0 pb-0" id="print_section" style="display: flex; justify-content: center;">

    </div>
    @include('layouts.backend.partial.modal-footer-info')
</section>

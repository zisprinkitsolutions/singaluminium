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
        table{
            border-collapse: collapse;
        }
        th,td{
            color: #313131;
        }
        @media(min-width:1300px) {
            .padding-right {
                padding-right: 0px !important;
            }
        }
        table tr:nth-child(odd) {
            background-color: #f9f9f9 !important;
        }

        table tr:nth-child(even) {
            background-color: #c8d6e357 !important;
        }

        .loading-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        td .sort-indicator desc {
            font-size: 12px;
            margin-left: 8px;
            color: #888;
            opacity: 0.5;
        }

        td .sort-indicator.asc::after {
            content: "▲";
        }

        td .sort-indicator.desc::after {
            content: "▼";
        }

        td .sort-indicator desc {
            opacity: 1;
            color: #007bff;
        }

        .circle {
            width: 30px;
            height: 30px;
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
        .toggle_month{
            cursor: pointer;
        }

        th,td{
            font-weight: 13px !important;
            font-weight: 500;
        }


        @media print {
            #print_section{
                padding: 20px !important;
                margin: 10px !important;
            }
            th,td{
                color: black !important;
                font-weight: 13px;
            }
            th{
                font-size: 13px;
            }
            .text-white{
                color: black !important;
            }
        }
        .toggle_month{
            padding: 7px 5px;
        }
        .bg-change, .toggle_month:hover{
            background-color: #e2e2e2;
        }
        .bg-party-column, .data_toggle td:hover{
            background-color: #e6f7ff;
        }

    </style>
    @php
        $grand_total_value = 0;
        $grand_total_pcs = 0;
    @endphp
    <div class="app-content content print-hideen">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.report._header', [
                    'activeMenu' => $type,
                ])
                <div class="tab-content bg-white">
                    <div class="tab-pane active p-2">
                        <div class="content-body">
                            <section id="widgets-Statistics">
                                <div class="cardStyleChange">
                                    <div class="card-body mt-1">
                                        <form action="" method="GET">
                                            <div class="d-flex">
                                                {{-- @if(auth()->user()->role_id == 1)
                                                <div class="form-group" style="width:15%;">
                                                    <label for=""> Office </label>
                                                    <select name="office_id" id="office_id"
                                                    class="form-control common-select2">
                                                        <option value="">Select </option>
                                                        @foreach ($offices as $office)
                                                            <option value="{{ $office->id }}" {{$office->id == $selected_office->id ? 'selected' : ' '}}>
                                                                {{ $office->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @else --}}
                                                <input type="hidden" name="office_id" value="">
                                                {{-- @endif --}}
                                                <div style="width:15%">
                                                    <label for="search"> Search </label>
                                                    <input type="text" name="search_query" id="search_query" value="{{old('search_query', $search_query)}}" class="form-control inputFieldHeight" placeholder="Search by invoice_no" autocomplete="off">
                                                </div>

                                                <div class="form-grup" style="width:20%;margin-left:8px;">
                                                    <label for="">
                                                        Party
                                                    </label>
                                                    <select name="search"
                                                    class="form-control common-select2">
                                                        <option value="">Select </option>
                                                        @foreach ($parties as $party)
                                                            <option value="{{ $party->id }}" {{$party->id == $search ? 'selected' : ' '}}>
                                                                {{ $party->pi_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div style="width:10%;margin-left:8px;">
                                                    <div class="form-group">
                                                        <label for=""> From Date </label>
                                                        <input type="text" id="from_date" name="from_date" class="datepicker form-control inputFieldHeight" value="{{$from_date ? date('d/m/Y',strtotime($from_date)) : ''}}" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div style="width:10%;margin-left:8px;">
                                                    <div class="form-group">
                                                        <label for=""> To Date </label>
                                                        <input type="text" id="to_date" name="to_date" class="datepicker form-control inputFieldHeight" value="{{$from_date ? date('d/m/Y',strtotime($to_date)) : ''}}" autocomplete="off">
                                                    </div>
                                                </div>

                                                <button type="submit"
                                                    class="btn mSearchingBotton mb-2 mt-2 formButton" title="Search" style="margin-left:8px;">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                width="25">
                                                        </div>
                                                    </div>
                                                </button>

                                                <div class="d-flex justify-content-end" style="margin-left: 8px; width:40%">


                                                    {{-- <button type="button" class="btn mExcelButton formButton mr-1"
                                                        title="Export"
                                                        onclick="exportTableToCSV('general-ledger-{{ date('d M Y') }}.csv')">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/excel-icon.png') }}"
                                                                    width="25">
                                                            </div>
                                                            <div><span>Excel </span></div>
                                                        </div>
                                                    </button> --}}

                                                    <a href="#" class="btn btn_create mPrint formButton mt-2 mb-2" title="Print" style="margin-left: 4px;"
                                                        onclick="media_print('print_section')">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/print-icon.png') }}"
                                                                    width="25">
                                                            </div>
                                                        </div>
                                                    </a>

                                                    <a href="{{route('accounts.receivable.pdf',['type' => $type])}}" target="blank"
                                                        style="margin-left: 4px;"
                                                         class="btn btn_create mPrint mt-2 mb-2 formButton" title="Print/PDF">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <i class='bx bxs-file-pdf'></i>
                                                            </div>
                                                        </div>
                                                    </a>

                                                    <a href="{{route('accounts.receivable.extended.pdf',['type' => $type])}}" target="blank"
                                                         class="btn btn_create mPrint formButton mt-2 mb-2"
                                                        title="Print/Pdf Extended" style="margin-left:4px;">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <i class='bx bxs-file-pdf'></i>
                                                            </div>
                                                        </div>
                                                    </a>

                                                    <a href="{{route('accounts.receivable.excel',['type' => $type])}}" target="blank"
                                                        class="btn btn_create mPrint formButton mt-2 mb-2"
                                                       title="Excel" style="margin-left:4px;">
                                                       <div class="d-flex">
                                                           <div class="formSaveIcon">
                                                            <img src="{{asset('assets/backend/app-assets/icon/excel-icon.png')}}" width="25">
                                                           </div>
                                                       </div>
                                                   </a>

                                                   <a href="{{route('accounts.receivable.extended.excel',['type' => $type])}}" target="blank"
                                                            class="btn btn_create mPrint formButton mt-2 mb-2"
                                                        title="Extended Excel" style="margin-left:4px;">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{asset('assets/backend/app-assets/icon/excel-icon.png')}}" width="25">
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="card-body" id="print_section">
                                        @include('layouts.backend.partial.modal-header-info')

                                        <div class="text-center invoice-view-wrapper" style="padding:10px;">
                                            @if($from_date && $to_date)
                                             <h5> Account {{$type}} report from {{date('d/m/Y',strtotime($from_date))}} to {{date('d/m/Y', strtotime($to_date))}}</h5>
                                            @elseif ($from_date)
                                            <h5>  Account {{$type}} report  {{date('d/m/Y',strtotime($from_date))}}</h5>
                                            @elseif($to_date)
                                            <h5>  Account {{$type}} report  {{date('d/m/Y',strtotime($to_date))}}</h5>
                                            @else
                                                <h5>  Account {{$type}} report </h5>
                                            @endif
                                        </div>

                                        @if (count($three_month_data)  > 0)
                                            <div class="d-flex justify-content-between align-items-center bg-change toggle_month" data-target=".three_month">
                                                <div class="d-flex align-items-center">
                                                    <i class='bx bx-chevron-up d-none' style="font-size: 25px;font-weight:200;"></i>
                                                    <i class='bx bx-chevron-down' style="font-size:25px; font-weight:200;"> </i>
                                                    <h5 class="text-center" style="margin-bottom: 0; color:#313131;font-weight:400;font-size:15px;"> <= 3 Months </h5>
                                                </div>
                                                @php
                                                    $three_month_data = collect($three_month_data);
                                                    $sum = $three_month_data->sum('due_amount');
                                                @endphp
                                                <h5 style="margin-bottom: 0; color:#313131;font-weight:400; font-size:15px;">  Total: {{number_format($sum,2)}} </h5>
                                            </div>

                                            <div class="three_month child-table">
                                                <table class="table table-sm parent-table">
                                                    <tbody>
                                                        @foreach ($three_month_data as $item)
                                                            <tr class="data_toggle" data-fetch="1" data-type="three_month"
                                                                data-target=".three_month_data_details_{{$item->id}}" style="">
                                                                <td style="padding:8px 30px; text-align: left;font-weight:400; ">
                                                                    <div class="d-flex align-items-center" style="text-transform: uppercase !important; font-size:13px;">
                                                                        <i class='bx bx-chevron-up d-none' style="font-size: 25px;font-weight:200;"></i>
                                                                        <i class='bx bx-chevron-down' style="font-size:25px; font-weight:200;"> </i>
                                                                        {{$item->pi_name}}
                                                                    </div>

                                                                </td>
                                                                <td class="text-right" style="padding: 8px; text-align: center; font-size:14px; font-weight:400; "> {{number_format($item->due_amount,2)}} </td>
                                                            </tr>

                                                            <tr class="three_month_data_details_{{$item->id}} child-table" style="display: none">
                                                                <td colspan="2" style="padding-left:55px;">
                                                                    <div class="loading-container">
                                                                        <div class="circle"></div>
                                                                    </div>

                                                                    <table class="table table-sm print-layout">
                                                                        <thead>
                                                                            <tr class="sort-toggler">
                                                                                <td data-column="0" data-sort="desc" style="font-size: 13px;"> Date <i class="sort-indicator"></i></td>
                                                                                <td data-column="1" data-sort="desc" style="font-size: 13px;"> Invoice No <i class="sort-indicator"></i></td>
                                                                                <td data-column="2" data-sort="desc" class="text-center" style="font-size: 13px;">Agieng Period <i class="sort-indicator"></i></td>
                                                                                <td data-column="3" data-sort="desc" style="font-size: 13px;text-align:right;"> Total <i class="sort-indicator"></i></td>
                                                                                <td data-column="4" data-sort="desc" style="font-size: 13px;text-align:right;"> Paid  <i class="sort-indicator"></i></td>
                                                                                <td data-column="5" data-sort="desc" style="font-size: 13px;text-align:right;"> Due <i class="sort-indicator desc"></i></td>
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody class="three_month_details_item_{{$item->id}} child-table">

                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif

                                        @if(count($six_month_data) > 0)
                                            <div class="d-flex justify-content-between align-items-center mt-1 toggle_month" data-target=".six_month">
                                                <div class="d-flex align-items-center">
                                                    <i class='bx bx-chevron-up' style="font-size: 25px;font-weight:200;"></i>
                                                    <i class='bx bx-chevron-down d-none' style="font-size:25px; font-weight:200;"> </i>
                                                    <h5 class="text-center" style="margin-bottom: 0; color:#313131;font-weight:400;font-size:15px;"> 3 Months <,  <= 6 Months </h5>
                                                </div>

                                                @php
                                                    $six_month_data = collect($six_month_data);
                                                    $sum = $six_month_data->sum('due_amount');
                                                @endphp

                                                <h5 style="margin-bottom: 0; color:#313131;font-weight:400;font-size:15px;">  Total: {{number_format($sum,2)}} </h5>
                                            </div>

                                            <div class="six_month child-table" style="display: none">
                                                <table class="table table-sm">
                                                    <tbody>
                                                        @foreach ($six_month_data as $item)
                                                            <tr class="data_toggle" data-fetch="1" data-type="six_month"
                                                                data-target=".six_month_data_details_{{$item->id}}">
                                                                <td style="padding:8px 30px; text-align: left;font-weight:400; ">
                                                                    <div class="d-flex align-items-center" style="text-transform: uppercase !important; font-size:13px;">
                                                                        <i class='bx bx-chevron-up d-none' style="font-size: 25px;font-weight:200;"></i>
                                                                        <i class='bx bx-chevron-down' style="font-size:25px; font-weight:200;"> </i>
                                                                        {{$item->pi_name}}
                                                                    </div>

                                                                </td>
                                                                <td class="text-right" style="padding: 8px; text-align: center; font-size:14px; font-weight:400; "> {{$item->due_amount}} </td>
                                                            </tr>

                                                            <tr class="six_month_data_details_{{$item->id}} child-table" style="display: none">
                                                                <td colspan="2" style="padding-left: 55px;">
                                                                    <div class="loading-container">
                                                                        <div class="circle"></div>
                                                                    </div>

                                                                    <table class="table table-sm print-layout">
                                                                        <thead>
                                                                            <tr class="sort-toggler">
                                                                                <td data-column="0" data-sort="desc" style="font-size: 13px;"> Date <i class="sort-indicator"></i></td>
                                                                                <td data-column="1" data-sort="desc" style="font-size: 13px;"> Invoice No <i class="sort-indicator"></i></td>
                                                                                <td data-column="2" data-sort="desc" class="text-center" style="font-size:13px;">Agieng Period <i class="sort-indicator"></i></td>
                                                                                <td data-column="3" data-sort="desc" style="font-size: 13px;text-align:right;"> Total <i class="sort-indicator"></i></td>
                                                                                <td data-column="4" data-sort="desc" style="font-size: 13px;text-align:right;"> Paid  <i class="sort-indicator"></i></td>
                                                                                <td data-column="5" data-sort="desc" style="font-size: 13px;text-align:right;"> Due <i class="sort-indicator desc"></i></td>
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody class="six_month_details_item_{{$item->id}} child-table">

                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif


                                        @if(count($twelve_month_data) > 0)
                                            <div class="d-flex justify-content-between align-items-center mt-1 toggle_month" data-target=".twelve_month">
                                                <div class="d-flex align-items-center">
                                                    <i class='bx bx-chevron-up' style="font-size: 25px;font-weight:200;"></i>
                                                    <i class='bx bx-chevron-down d-none' style="font-size:25px; font-weight:200;"> </i>
                                                    <h5 class="text-center" style="margin-bottom: 0; font-size:15px; color:#313131;font-weight:400;">  6 Months  >, <= 12 Months </h5>
                                                </div>

                                                @php
                                                $data = collect($twelve_month_data);
                                                $sum = $data->sum('due_amount');
                                                @endphp
                                                 <h5 style="margin-bottom: 0; color:#313131;font-weight:400;font-size:15px;"> Total:{{number_format($sum,2)}} </h5>
                                            </div>
                                            <div class="twelve_month child-table" style="display: none">
                                                <table class="table table-sm parent-table">

                                                    <tbody>
                                                        @foreach ($twelve_month_data as $item)
                                                            <tr class="data_toggle" data-fetch="1" data-type="twelve_month"
                                                                data-target=".twelve_month_data_details_{{$item->id}}">
                                                                <td style="padding:8px 30px; text-align: left;font-weight:400; ">
                                                                    <div class="d-flex align-items-center" style="text-transform: uppercase !important; font-size:13px;">
                                                                        <i class='bx bx-chevron-up d-none' style="font-size: 25px;font-weight:200;"></i>
                                                                        <i class='bx bx-chevron-down' style="font-size:25px; font-weight:200;"> </i>
                                                                        {{$item->pi_name}}
                                                                    </div>

                                                                </td>
                                                                <td class="text-right" style="padding: 8px; text-align: center; font-size:14px; font-weight:400; "> {{number_format($item->due_amount,2)}} </td>
                                                            </tr>

                                                            <tr class="twelve_month_data_details_{{$item->id}} child-table" style="display: none">
                                                                <td colspan="2" style="padding-left:55px;">
                                                                    <div class="loading-container">
                                                                        <div class="circle"></div>
                                                                    </div>

                                                                    <table class="table table-sm">
                                                                        <thead>
                                                                            <tr class="sort-toggler">
                                                                                <td data-column="0" data-sort="desc" style="font-size: 13px;"> Date <i class="sort-indicator"></i></td>
                                                                                <td data-column="1" data-sort="desc" style="font-size: 13px;"> Invoice No <i class="sort-indicator"></i></td>
                                                                                <td data-column="2" data-sort="desc" class="text-center" style="font-size:13px;">Agieng Period <i class="sort-indicator"></i></td>
                                                                                <td data-column="3" data-sort="desc" style="font-size: 13px;text-align:right;"> Total <i class="sort-indicator"></i></td>
                                                                                <td data-column="4" data-sort="desc" style="font-size: 13px;text-align:right;"> Paid  <i class="sort-indicator"></i></td>
                                                                                <td data-column="5" data-sort="desc" style="font-size: 13px;text-align:right;"> Due <i class="sort-indicator desc"></i></td>
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody class="twelve_month_details_item_{{$item->id}} child-table">

                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif

                                        @if(count($old_month_data) > 0)
                                            <div class="d-flex justify-content-between align-items-center mt-1 toggle_month" data-target=".old_month">
                                                <div class="d-flex align-items-center">
                                                    <i class='bx bx-chevron-up' style="font-size: 25px;font-weight:200;"></i>
                                                    <i class='bx bx-chevron-down d-none' style="font-size:25px; font-weight:200;"> </i>
                                                    <h5 class="text-center" style="margin-bottom: 0; font-size:15px; color:#313131;font-weight:400;"> 1 Year + </h5>
                                                </div>

                                                @php
                                                $six_month_data = collect($old_month_data);
                                                $sum = $six_month_data->sum('due_amount');
                                                @endphp
                                                <h5 style="margin-bottom: 0; color:#313131;font-weight:400; font-size:15px;">  Total: {{number_format($sum,2)}} </h5>
                                            </div>

                                            <div class="old_month child-table" style="display: none">
                                                <table class="table table-sm parent-table">

                                                    <tbody>
                                                        @foreach ($old_month_data as $item)
                                                            <tr class="data_toggle" data-fetch="1" data-type="old_month"
                                                                data-target=".old_month_data_details_{{$item->id}}">
                                                                <td style="padding:8px 30px; text-align: left;font-weight:400; ">
                                                                    <div class="d-flex align-items-center" style="text-transform: uppercase !important; font-size:13px;">
                                                                        <i class='bx bx-chevron-up d-none' style="font-size: 25px;font-weight:200;"></i>
                                                                        <i class='bx bx-chevron-down' style="font-size:25px; font-weight:200;"> </i>
                                                                        {{$item->pi_name}}
                                                                    </div>

                                                                </td>
                                                                <td class="text-right" style="padding: 8px; text-align: center; font-size:14px; font-weight:400; "> {{number_format($item->due_amount,2)}} </td>
                                                            </tr>

                                                            <tr class="old_month_data_details_{{$item->id}} child-table" style="display: none">
                                                                <td colspan="2" style="padding-left:55px;">
                                                                    <div class="loading-container">
                                                                        <div class="circle"></div>
                                                                    </div>

                                                                    <table class="table table-sm print-layout">
                                                                        <thead>
                                                                            <tr class="sort-toggler">
                                                                                <td data-column="0" data-sort="desc" style="font-size: 13px;"> Date <i class="sort-indicator"></i></td>
                                                                                <td data-column="1" data-sort="desc" style="font-size: 13px;"> Invoice No <i class="sort-indicator"></i></td>
                                                                                <td data-column="2" data-sort="desc" class="text-center" style="font-size:13px;">Agieng Period <i class="sort-indicator"></i></td>
                                                                                <td data-column="3" data-sort="desc" style="font-size: 13px;text-align:right;"> Total <i class="sort-indicator"></i></td>
                                                                                <td data-column="4" data-sort="desc" style="font-size: 13px;text-align:right;"> Paid  <i class="sort-indicator"></i></td>
                                                                                <td data-column="5" data-sort="desc" style="font-size: 13px;text-align:right;"> Due <i class="sort-indicator desc"></i></td>
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody class="old_month_details_item_{{$item->id}} child-table">

                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif

                                       @include('layouts.backend.partial.modal-footer-info')
                                    </div>
                                </div>
                            </section>
                        </div>
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
@endsection

@push('js')
    <script>

        function fetchDetail(type, party_id){
            var report_type = "{{ $type }}";
            var url = "{{ route('accounts.receivable.details', ['report_type' => ':type', 'party' => ':party_id']) }}";
            url = url.replace(':party_id', party_id);
            url = url.replace(':type', report_type);
            var search_query = $('#search_query').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var order_by  = $('#order_by').val();
            var office_id = 1;
            $.ajax({
                type:'GET',
                url:url,
                data:{
                    'type': type,
                    'search_query': search_query,
                    'from_date': from_date,
                    'to_date': to_date,
                    'order_by': order_by,
                    'office_id': office_id,
                },

                success:function(res){
                    $(`.${type}_details_item_${party_id}`).html(res);
                    $(this).data('fetch', 0);
                },
                error:function(error){
                    toastr.error('The ivoice has been delete, Or server Error',404);
                },
                complete: function() {
                    $('.loading-container').hide();
                }
            })
        };

        $(document).on('click', '.data_toggle', function() {
            // $('.child-table').hide();
            $(this).find('.bx').toggleClass('d-none');
            $(this).find('td').toggleClass('bg-party-column');
            $(this).closest('.child-table').show();
            var target = $(this).data('target');
            var targetDetails =  $(target).find('.child-table');
            if (targetDetails.is(':visible')) {
                targetDetails.hide();
            } else {
                targetDetails.show();
            }

            $(target).find('.loading-container').show();
            var type = $(this).data('type');
            var fetchData = $(this).data('fetch');
            var party_id = target.match(/\d+/)[0];

            if (fetchData == 1) {
                $(target).toggle();
                fetchDetail(type,party_id);
            } else {
                $(this).data('fetch', 0);
                $(target).toggle();
                $('.loading-container').hide();
            }
        });

        $(document).on('click', '.toggle_month', function() {
            $('.child-table').hide();
            var target = $(this).data('target');
            $(this).find('.bx').toggleClass('d-none');
            $(this).toggleClass('bg-change');
            $(target).toggle();
        });

        $(document).on('click', '.sort-toggler td', function () {
            const $header = $(this);
            const table = $header.closest('table');
            const tbody = table.find('tbody');
            const columnIndex = $header.data('column');
            let sortOrder = $header.data('sort');

            sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
            $header.data('sort', sortOrder);
            table.find('.sort-indicator').removeClass('asc desc');
            $header.find('.sort-indicator').addClass(sortOrder);
            const rows = tbody.find('tr').toArray();

            rows.sort((a, b) => {
                const aText = $(a).find('td').eq(columnIndex).text().trim();
                const bText = $(b).find('td').eq(columnIndex).text().trim();

                const aNum = parseFloat(aText.replace(/[^0-9.-]+/g, ""));
                const bNum = parseFloat(bText.replace(/[^0-9.-]+/g, ""));

                if (!isNaN(aNum) && !isNaN(bNum)) {
                    return sortOrder === 'asc' ? aNum - bNum : bNum - aNum;
                }

                return sortOrder === 'asc'
                    ? aText.localeCompare(bText)
                    : bText.localeCompare(aText);
            });

            tbody.append(rows);
        });

    </script>
@endpush



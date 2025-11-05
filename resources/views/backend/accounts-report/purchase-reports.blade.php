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

        @media(min-width:1300px) {
            .padding-right {
                padding-right: 0px !important;
            }
        }
        thead {
            background: #34465b;
            color: #fff !important;
        }

        th {
            font-size: 15px !important;
        }

        a {
            color: #ffffff;
        }
        th{
            color: #fff !important;
            font-size: 11px !important;
        }
        td{
            font-size: 10px !important;
        }
        .latter-head{
            display: none;
        }
        .header-info{
            top: 0 !important;
        }
        @media print{
            .latter-head{
                display:block;
            }
            /* .header-info{
                top: 0 !important;
                position: fixed;
            } */
            .print-content{
                top: 100 !important;
                position: fixed;
            }
            .nav.nav-tabs ~ .tab-content{
                border: #ffffff !important;
            }
            .padding-top{
                padding-top: 80px !important;
            }
            .print-hideen{
                display: none !important;
            }
        }
    </style>
    @php
        $grand_total_value = 0;
        $grand_total_pcs = 0;
    @endphp
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.report._header',['activeMenu' => 'account_report'])
                <div class="tab-content bg-white">
                    <div class="tab-pane active p-2">
                        <div class="content-body">
                            <section id="widgets-Statistics">
                                <div class="d-flex justify-content-between align-items-center print-hideen">
                                    @include('clientReport.report._accounting_report_subheader', ['activeMenu' => 'purchase_reports'])
                                </div>

                                <div class="cardStyleChange">
                                    <div class="latter-head header-info">
                                        @include('layouts.backend.partial.modal-header-info')
                                    </div>
                                    <div class="card-body mt-1 print-hideen">
                                        <div class="row" style=" padding: 0 10px;">

                                            <div class="col-md-9 padding-right" style="padding-left: 0px;">
                                                <form action="" method="GET">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <input type="text" class="inputFieldHeight form-control datepicker" name="date" placeholder="Single Date" id="date" autocomplete="off">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" class="inputFieldHeight form-control datepicker" name="from" placeholder="From" id="from" autocomplete="off">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" class="inputFieldHeight form-control datepicker" name="to" placeholder="To" id="to" autocomplete="off">
                                                        </div>
                                                        <div class="col-md-2 text-left">
                                                            <button type="submit"
                                                                class="btn mSearchingBotton mb-2 formButton" title="Search">
                                                                <div class="d-flex">
                                                                    <div class="formSaveIcon">
                                                                        <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                            width="25">
                                                                    </div>
                                                                    <div><span>Search</span></div>
                                                                </div>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                            <div class="col-md-3 text-right col-right-padding">
                                                <button type="button" class="btn mExcelButton formButton mr-1"
                                                    title="Export"
                                                    onclick="exportTableToCSV('general-ledger-{{ date('d M Y') }}.csv')">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{ asset('assets/backend/app-assets/icon/excel-icon.png') }}"
                                                                width="25">
                                                        </div>
                                                        <div><span>Excel</span></div>
                                                    </div>
                                                </button>
                                                <a href="#" class="btn btn_create mPrint formButton" title="Print"
                                                    onclick=" handlePrintClick('Purchase_Reports')">
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
                                    </div>
                                    <div class="card-body p-0 p" style="padding: 0 !important;" id="Purchase_Reports">

                                        <h4 class="text-center padding-top">Purchase Reports</h4>
                                        @if ($date)
                                            <h6 class="text-center">{{date('d M Y', strtotime($date))}}</h6>
                                        @elseif($from && $to)
                                        <h6 class="text-center">{{date('d M Y', strtotime($from)).' To '.date('d M Y', strtotime($to))}}</h6>
                                        @endif
                                        <div style="height: 500px; overflow-y:auto;">
                                            <table class="table mb-0 table-sm table-bordered">
                                                <thead class="thead" style="position: sticky; top:-2px; z-index:99;">
                                                    <tr class="text-center" style="height: 40px;">
                                                        <th>Date</th>
                                                        {{-- <th style="min-width: 100px">Project</th> --}}
                                                        <th style="width: 15%">Bill No</th>
                                                        <th style="width: 15%">Invoice No</th>
                                                        <th style="width: 34%">Party Name</th>
                                                        <th style="width: 12%">Amount <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                    $total = 0;
                                                    $vat = 0;
                                                    $qty = 0;
                                                    @endphp
                                                    @foreach ($purchases as $item)
                                                    <tr class="purch_exp_view" id="{{ $item->id }}" style="text-align:center;">
                                                        <td>{{ date('d/m/Y', strtotime($item->date)) }}</td>
                                                        {{-- <td>{{$item->job_project? $item->job_project->project_name:''}}</td> --}}
                                                        <td>{{ $item->purchase_no }}</td>
                                                        <td>{{ $item->invoice_no }}</td>
                                                        <td>{{ $item->party->pi_name }}</td>
                                                        <td>{{number_format($item->total_amount,2) }}</td>
                                                    </tr>
                                                    @endforeach
                                                    <tr class="text-center">
                                                        <td>Total Amount</td>
                                                        <td colspan="3"></td>
                                                        <td>{{number_format($purchases->sum('total_amount'),2)}}</td>
                                                    </tr>
                                                </tbody>
                                                </table>
                                        </div>
                                        {{$purchases->links()}}
                                    </div>
                                    <div class="latter-head">
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

@endsection

@push('js')
    <script>
        $(document).on('change', '#date', function(e){
            $("#from").val('');
            $("#to").val('');
        });
        $(document).on('change', '#from', function(e){
            $("#date").val('');
        });
        $(document).on('change', '#to', function(e){
            $("#date").val('');
        });
    </script>
@endpush

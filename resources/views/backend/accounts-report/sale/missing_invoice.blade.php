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
        a {
            color: #ffffff;
        }
        th{
            font-size: 14px !important;
            color: #fff !important;
        }
        td{
            font-size: 13px !important;
        }
        .latter-head{
            display: none;
        }
        .header-info{
            top: 0 !important;
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

        @media print{
            .latter-head{
                display:inline;
            }
            .header-info{
                top: 0 !important;
                position: fixed;
            }
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
        .unregular{
            background:#b48e06;
            color:#fff3cd;
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
                @include('clientReport.report._header',['activeMenu' => 'financial_reports'])
                <div class="tab-content bg-white">
                    <div class="tab-pane active p-2">
                        <div class="content-body">
                            <section id="widgets-Statistics">
                                <div class="d-flex justify-content-between align-items-center print-hideen">
                                    @include('clientReport.report._financial_report_subheader', [ 'activeMenu' => 'missing'])
                                </div>

                                <div class="cardStyleChange">

                                    <div class="card-body mt-1 print-hideen">
                                        <form action="" method="GET">
                                            <div class="d-flex">
                                                <div class="form-group" style="width:15%;">
                                                    <label for="search"> Search </label>
                                                    <input type="text" name="search_query" value="{{$search_query}}" class="form-control inputFieldHeight" placeholder="Search by invoice no">
                                                </div>

                                                <div class="from-group" style="width:10%; margin-left:8px;">
                                                    <label for=""> Month </label>
                                                    <select name="month" class="form-control inputFieldHeight" id="month">
                                                        <option value=""> Select </option>
                                                            @foreach (range(1, 12) as $m)
                                                            <option value="{{ $m}}" {{$month == $m ? 'selected' : ' '}}>
                                                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="from-group" style="width:10%; margin-left:8px;">
                                                    <label for=""> Year </label>
                                                    <select name="year" class="form-control inputFieldHeight" id="year">
                                                        <option value="">Select</option>
                                                        @foreach (range(date('Y'), date('Y') - 10) as $y)
                                                            <option value="{{ $y }}" {{$year == $y ? 'selected' : ' '}}>{{ $y }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                                <div class="from-group" style="width:10%; margin-left:8px;">
                                                    <div class="form-group">
                                                        <label for=""> From Date </label>
                                                        <input type="text" class="datepicker form-control inputFieldHeight" name="from" placeholder="from" value="{{$from ? date('d/m/Y', strtotime($from)) : null}}" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="from-group" style="width:10%; margin-left:8px;">
                                                    <div class="form-group">
                                                        <label for=""> To Date </label>
                                                        <input type="text" class="datepicker form-control inputFieldHeight" name="to" placeholder="to" value="{{$to ? date('d/m/Y', strtotime($to)) : null}}" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-end" style="margin-left:8px;">
                                                    <button type="submit"
                                                        class="btn mSearchingBotton mb-2 mt-2 formButton" title="Search">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                    width="25">
                                                            </div>
                                                        </div>
                                                    </button>

                                                    <a href="{{route('missing-invoice-number')}}" type="submit" style="margin-left: 4px;"
                                                        class="btn mt-2 btn-primary mb-2 formButton" title="Search">
                                                        <div class="d-flex">
                                                            Default
                                                        </div>
                                                    </a>

                                                    <a href="#" class="btn btn_create mPrint formButton mt-2 mb-2" title="Print"
                                                        onclick="media_print('print_section')" style="margin-left:6px;">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/print-icon.png') }}"
                                                                    width="25">
                                                            </div>
                                                        </div>
                                                    </a>

                                                    <button data-url="{{route('missing-invoice-number-pdf')}}" type="button" style="margin-left: 4px;"
                                                        class="btn mt-2 mPrint mb-2 formButton extend-download" title="PDF / Print" data-query="{{ json_encode([
                                                            'month' => $month,
                                                            'year' => $year,
                                                            'from' => $from,
                                                            'to' => $to,
                                                            'search_query' => $search_query,
                                                            'file_type' => 'pdf',
                                                        ]) }}">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <i class='bx bxs-file-pdf'></i>
                                                            </div>
                                                        </div>
                                                    </button>

                                                    <button data-url="{{route('missing-invoice-number-pdf')}}" type="button" style="margin-left: 4px;"
                                                        class="btn mt-2 mPrint mb-2 formButton extend-download" title="Excel" data-query="{{ json_encode([
                                                            'month' => $month,
                                                            'year' => $year,
                                                            'from' => $from,
                                                            'to' => $to,
                                                            'search_query' => $search_query,
                                                            'file_type' => 'excel',
                                                        ]) }}">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/excel-icon.png') }}"
                                                                    width="25">
                                                            </div>
                                                        </div>
                                                    </button>

                                                    {{-- <button data-url="{{route('sale-report-extend-pdf')}}" data-type="extend" type="button" style="margin-left: 4px;"
                                                        class="btn mt-2 mPrint mb-2 formButton download-pdf" title="Extended Pdf / Print">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <i class='bx bxs-file-pdf'></i>
                                                            </div>
                                                        </div>
                                                    </button>

                                                    <button data-url="{{route('sale-report-excel')}}" type="button" style="margin-left: 4px;"
                                                        class="btn mt-2 mPrint mb-2 formButton download-pdf" title="Excel">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/excel-icon.png') }}"
                                                                    width="25">
                                                            </div>
                                                        </div>
                                                    </button>

                                                    <button data-url="{{route('sale-report-extend-excel')}}" data-type="extend-excel" type="button" style="margin-left: 4px;"
                                                        class="btn mt-2 mPrint mb-2 formButton download-pdf" title="Extended Excel">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/excel-icon.png') }}"
                                                                    width="25">
                                                            </div>
                                                        </div>
                                                    </button> --}}
                                                </div>
                                            </div>
                                        </form>


                                    <div class="card-body" style="padding: 0 !important;" id="print_section">
                                        <div class="latter-head header-info">
                                            @include('layouts.backend.partial.modal-header-info')
                                        </div>

                                        <h4 class="text-center padding-top">  Missing invoice report </h4>

                                        @if($from && $to)
                                        <h6 class="text-center">{{date('d M Y', strtotime($from)).' To '.date('d M Y', strtotime($to))}}</h6>
                                        @elseif ($from)
                                            <h6 class="text-center">{{date('d M Y', strtotime($from))}}</h6>
                                        @endif
                                        <table class="table mb-0 table-sm">
                                            <thead  class="thead">
                                                <tr>
                                                    <th style="width:7%; text-align:center;"> SL No </th>
                                                    <th style="width: 12%">Date</th>
                                                    <th style="width: 15%">Invoice No</th>
                                                    <th style="width: 33%">Party Name</th>
                                                    <th style="width: 20%">Amount <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                                    <th style="width: 13%">Type</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ($invoice_group as $key => $invoice)

                                                    <tr class="sale_view" id="{{ $invoice['id'] }}">
                                                        <td class="text-center ">{{ ($taxInvoices->currentPage() - 1) * $taxInvoices->perPage() + ($key + 1) }}</td>
                                                        <td class="">{{ date('d/m/Y', strtotime($invoice['date'])) }}</td>
                                                        <td class="">{{ $invoice['invoice_no'] }}</td>
                                                        <td class="">{{ $invoice['pi_name'] }}</td>
                                                        <td class="">{{ $invoice['total_budget'] }}</td>
                                                        <td class="">{{ $invoice['invoice_type'] }}</td>
                                                    </tr>

                                                    @foreach ($invoice['missing_invoices'] as $subKey => $missing)
                                                        <tr>
                                                            <td class="bg-danger text-white text-center">
                                                                {{ ($taxInvoices->currentPage() - 1) * $taxInvoices->perPage() + ($key + 1) }}.{{ $subKey + 1 }}
                                                            </td>
                                                            <td class="bg-danger text-white text-center" colspan="5"> {{ $missing }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <div class="my-1">
                                            {{$taxInvoices->links()}}
                                        </div>

                                        <div class="latter-head">
                                            @include('layouts.backend.partial.modal-footer-info')
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

    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
    $(document).on('click', '.extend-download', function(){
        const queryData = JSON.parse($(this).attr('data-query'));
        var url = $(this).data('url');
        var confirmation = confirm("The file is too large to render. We will notify you once the process is complete?");
        if (!confirmation) {
            return;
        }
        $.ajax({
            url: url,
            type: 'GET',
            data: queryData,
            success: function (response) {
                checkNotification();
            },
            error: function (xhr, status, error) {
                alert('An error occurred while processing your request.');
            }
        });
    });
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

        $(document).on("click", ".sale_view", function(e) {
            e.preventDefault();
            var id= $(this).attr('id');
            $.ajax({
                url: "{{URL('sale-modal')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response){
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                }
            });
        });
    </script>
@endpush

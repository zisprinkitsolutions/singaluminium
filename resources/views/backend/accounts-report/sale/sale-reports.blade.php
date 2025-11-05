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
        }
        a {
            color: #ffffff;
        }
        th{
            font-size: 12px !important;
            color: #fff !important;
        }
        td{
            font-size: 12px !important;
        }
        td.amount-column,th.amount-column{
            text-align: right;
        }
        .latter-head{
            display: none;
        }
        .header-info{
            top: 0 !important;
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
        .data-toggler.active, .data-toggler:hover{
            background-color:rgb(223, 211, 211);
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
                                    @include('clientReport.report._accounting_report_subheader', ['activeMenu' => 'sale_reports'])
                                </div>

                                <div class="cardStyleChange">
                                    <div class="card-body mt-1 print-hideen">
                                        <form action="" method="GET">
                                            <div class="d-flex">
                                                <input type="hidden" name="office_id" id="office_id" value="{{$selected_office->id}}">
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

                                                <button type="submit" class="btn mb-2 mt-2 formButton ml-1 btn-primary" title="Search">
                                                    Search
                                                </button>

                                                <a href="{{route('sale-reports')}}" type="submit" style="margin-left: 4px;"
                                                    class="btn mt-2 btn-primary mb-2 formButton" title="Default">
                                                    <div class="d-flex">
                                                        Default
                                                    </div>
                                                </a>

                                                <div class="d-flex justify-content-end" style="width: 40%;">
                                                    <a href="#" class="btn btn_create mPrint formButton mt-2 mb-2" title="Print"
                                                        onclick="media_print('print_section')" style="margin-left:6px;">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/print-icon.png') }}"
                                                                    width="25">
                                                            </div>
                                                        </div>
                                                    </a>

                                                    <button data-url="{{route('sale-report-pdf')}}" type="button" style="margin-left: 4px;"
                                                        class="btn mt-2 mPrint mb-2 formButton download-pdf" title="PDF / Print">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <i class='bx bxs-file-pdf'></i>
                                                            </div>
                                                        </div>
                                                    </button>

                                                    <button data-url="{{route('sale-report-extend-pdf')}}" data-type="extend" type="button" style="margin-left: 4px;"
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
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="card-body" style="padding: 0 !important;" id="print_section">
                                        <div class="latter-head header-info">
                                            @include('layouts.backend.partial.modal-header-info')
                                        </div>

                                        <h4 class="text-center padding-top">{{$selected_office->name}} Sale Reports </h4>

                                        @if($from && $to)
                                        <h6 class="text-center">{{date('d M Y', strtotime($from)).' To '.date('d M Y', strtotime($to))}}</h6>
                                        @elseif ($from)
                                            <h6 class="text-center">{{date('d M Y', strtotime($from))}}</h6>
                                        @endif

                                        <table class="table mb-0 table-sm sale">
                                            <thead class="thead">
                                                <tr class="header">
                                                    <th style="width:70%;">Month</th>
                                                    <th style="width:10%;" class="amount-column">Total</th>
                                                    <th style="width:10%;" class="amount-column">Paid</th>
                                                    <th style="width:10%;" class="amount-column">Due <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $displayedYears = [];
                                                @endphp

                                                @foreach ($taxInvoices as $key => $month_data)
                                                    @if (!in_array($month_data['year'], $displayedYears))
                                                        <!-- Display Year Header -->
                                                        <tr class="year-{{$month_data['year']}}">
                                                            <td colspan="4" class="text-center" style="background-color:#e2e2e2;font-size:14px !important;">
                                                                <div class="d-flex justify-content-center align-items-center">
                                                                    {{$month_data['year']}}
                                                                    {{-- <button class="extra-download-button" style="border:none;background:transparent;" title="PDF / Print"
                                                                            data-url="{{route('sale-report-pdf')}}"
                                                                            data-query='{"year":"{{$month_data['year']}}","month":"","from":"{{$from}}","to":"{{$to}}"}'>
                                                                        <i class="bx bxs-file-pdf"></i>
                                                                    </button>

                                                                    <button class="extra-download-button" style="border:none;background:transparent;" title="Extend PDF / Print"
                                                                            data-url="{{route('sale-report-extend-pdf')}}"
                                                                            data-query='{"year":"{{$month_data['year']}}","month":"","from":"{{$from}}","to":"{{$to}}"}'>
                                                                        <i class="bx bxs-file-pdf"></i>
                                                                    </button>

                                                                    <button class="extra-print-btn" style="border:none;background:transparent;" title="Extend PDF / Print"
                                                                            data-table="sale" data-body="year-{{$month_data['year']}}">
                                                                            <i class='bx bx-printer'></i>
                                                                    </button> --}}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $displayedYears[] = $month_data['year'];
                                                        @endphp
                                                    @endif

                                                    <!-- Monthly Invoice Row -->
                                                    <tr class="data-toggler year-{{$month_data['year']}}" data-target=".monthly-invoice-{{$month_data['year']}}-{{$month_data['month_number']}}">
                                                        <td>
                                                            <div class="d-flex align-items-center" style="font-size: 14px;">
                                                                <i class='bx bx-plus' style="font-size: 18px;color:#313131;font-weight:500;"></i>
                                                                <i class='bx bx-minus d-none' style="font-size:18px;"> </i>
                                                                {{$month_data['month']}}
                                                            </div>
                                                        </td>
                                                        <td class="amount-column">{{number_format($month_data['total_amount'],2)}}</td>
                                                        <td class="amount-column">{{number_format($month_data['paid_amount'],2)}}</td>
                                                        <td class="amount-column">{{number_format($month_data['due_amount'],2)}}</td>
                                                    </tr>

                                                    <tr class="year-{{$month_data['year']}} monthly-invoice-{{$month_data['year']}}-{{$month_data['month_number']}}" style="display: none;">
                                                        <td colspan="4">
                                                            <div class="loading-container">
                                                                <div class="circle"></div>
                                                            </div>
                                                            <table class="table table-sm">
                                                                <thead class="thead-light">
                                                                    <tr style="background-color: #f8f9fa;" class="sort-toggler year-{{$month_data['year']}}">
                                                                        <td style="width:8%; text-align:center;">SL No</td>
                                                                        <td data-column="1" data-sort="asc" style="width:12%"> Date <i class="sort-indicator asc"></i></td>
                                                                        <td data-column='2' data-short="desc" style="width:15px;"> Invoice No <i class="sort-indicator"></i>  </td>
                                                                        <td data-column="3" data-sort="desc" style="width:35%;">Party Name <i class="sort-indicator"></i> </td>
                                                                        <td data-column="4" data-sort="desc" style="width:10%;" class="amount-column">Total  <i class="sort-indicator"></i>  </td>
                                                                        <td data-column="5" data-sort="desc" style="width:10%;" class="amount-column">Paid<i class="sort-indicator"></i>  </td>
                                                                        <td data-column="6" data-sort="desc" style="width:10%;" class="amount-column">
                                                                            Due
                                                                            <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small>
                                                                            <i class="sort-indicator"></i>
                                                                        </td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($month_data['items'] as $index => $item)
                                                                        <tr class="sale_view" id="{{$item->id}}">
                                                                            <td style="text-align:center;">{{$index + 1}}</td>
                                                                            <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                                            <td>{{$item->invoice_no}}</td>
                                                                            <td>{{$item->pi_name}}</td>
                                                                            <td class="amount-column">{{number_format($item->total_amount,2)}}</td>
                                                                            <td class="amount-column">{{number_format($item->paid_amount,2)}}</td>
                                                                            <td class="amount-column">{{number_format($item->due_amount,2)}}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
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
        $(document).on('click', '.download-pdf', function(){
            var url = $(this).data('url');
            var type = $(this).data('type');
            var year = $('#year');
            var month = $('#month');

            if (type === 'extend' && !year.val()) {
                year[0].focus();
                toastr.warning('Year field is required', 'Warning');
                return;
            }

            if (type == 'extend' && !month.val()) {
                month[0].focus();
                toastr.warning('Month field is required', 'Warning');
                return;
            }

            if(type == 'extend-excel' && !year.val()){
                year[0].focus();
                toastr.warning('Year field is required', 'Warning');
                return;
            }

            const form = $('form');
            form.attr('method', 'GET');
            form.attr('target', ' ');
            var params = new URLSearchParams(new FormData(form[0]));
            var newUrl = url + (url.includes('?') ? '&' : '?') + params.toString();
            form.prop('action', url);
            form.submit();
            form.removeAttr('target', ' ');
            form.prop('action', ' ');
        });

        $(document).on('click', '.data-toggler', function() {
            $(this).find('.bx').toggleClass('d-none');
            var target = $(this).data('target');
            $(target).toggle();
            $(this).toggleClass('active');
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

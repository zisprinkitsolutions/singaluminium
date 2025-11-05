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

        .print-header-footer {
            display: none;
        }

        td {
            text-align: center !important;
        }

        th {
            text-align: center !important;
        }

        .trFontSize {
            font-size: 11px !important;
        }

        @media(min-width:1200px) {
            .padding-right {
                padding-right: 0px !important;
            }
        }

        @media print {
            @page {
                max-width: 10px;

            }

            .print-header-footer {
                display: block !important;
            }

            body {
                margin: 0px;
                padding: 0px;
            }

            .bg-secondary {
                background-color: #475F7B !important;
                color: #000 !important;
            }

            #print-table {
                margin-right: 10px;
                margin-left: 10px
            }
        }

        tr.parrent-tr:nth-child(odd) {
            background-color: #e32323 !important;
        }

        tr.parrent-tr:nth-child(even) {
            background-color: #05250b !important;
        }

        .toggle_month {
            padding: 7px 5px;
        }

        .bg-change,
        .toggle_month:hover {
            background-color: #e2e2e2;
        }

        .bg-party-column,
        .data_toggle td:hover {
            background-color: #e6f7ff;
        }

        .loading-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        td .sort-indicator desc {
            font-size: 13px;
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
    </style>
    <div class="app-content content print-hideen">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.report._header', [
                    'activeMenu' => 'account_report',
                ])
                <div class="tab-content bg-white">
                    <div class="tab-pane active">
                        <div class="content-body pt-1">
                            <section id="widgets-Statistics">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="pl-2">
                                            @include('clientReport.report._accounting_report_subheader', [
                                                'activeMenu' => 'party_report',
                                            ])
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-right" style="padding-right:31px">
                                         <button type="button" data-url="{{ route('party_report_pdf') }}"
                                                class="btn mb-2 btn-secondary formButton inputFieldHeight extend-download"
                                                title="Pdf/Print" style="margin-left: 5px !important;"
                                                data-query="{{ json_encode([
                                                    'party_type' => $party_type,
                                                    'month' => $month,
                                                    'year' => $year,
                                                    'from' => $from,
                                                    'to' => $to,
                                                    'party_id' => $party_id,
                                                    'company_id' => $selected_company->id ?? 0,
                                                ]) }}">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon" style="width: 25px">
                                                        <i class='bx bxs-file-pdf text-white'></i>
                                                    </div>
                                                </div>
                                            </button>

                                            <a href="#" class="btn btn_create mPrint formButton mb-2 inputFieldHeight"
                                                title="Print" style="margin-left: 5px !important;"
                                                onclick="media_print('print_section')">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img src="{{ asset('assets/backend/app-assets/icon/print-icon.png') }}"
                                                            width="25">
                                                    </div>
                                                </div>
                                            </a>

                                            <button type="button"
                                                class="download-pdf btn mSearchingBotton mb-2 formButton inputFieldHeight"
                                                title="Excel" style="margin-left: 5px !important;"
                                                data-url="{{ route('party_report_excel') }}">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img src="{{ asset('assets/backend/app-assets/icon/excel-icon.png') }}"
                                                            width="25">
                                                    </div>
                                                </div>
                                            </button>


                                            <button type="button" data-url="{{ route('party_report_extend_excel') }}"
                                                style="margin-left: 5px !important;"
                                                class="btn btn-info mb-2 formButton inputFieldHeight extend-download text-right"
                                                title="Extend Excel"
                                                data-query="{{ json_encode([
                                                    'party_type' => $party_type,
                                                    'month' => $month,
                                                    'year' => $year,
                                                    'from' => $from,
                                                    'to' => $to,
                                                    'party_id' => $party_id,
                                                    'company_id' => $selected_company->id ?? 0,
                                                ]) }}">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img src="{{ asset('assets/backend/app-assets/icon/excel-icon.png') }}"
                                                            width="25">
                                                    </div>
                                                </div>
                                            </button>

                                    </div>
                                </div>

                                <div class="cardStyleChange">
                                    <div class="px-1 py-0 mt-1">
                                        <div class="d-flex">
                                            <div class="col-md-12 p-0">
                                                <form action="" method="GET">
                                                    <div class="d-flex">
                                                        <div class="form-group d-none" style="margin-right: 8px; width:20%" >
                                                            <select name="company_id" id="company_id_search"
                                                                class="common-select2 inputFieldHeight w-100">
                                                                <option value="">Select Company...</option>
                                                                <option value="0" selected>SINGH ALUMINIUM AND STEEL</option>
                                                                @foreach ($companies as $company)
                                                                    <option value="{{ $company->id }}"
                                                                        {{ $company->id == $selected_company->id ? 'selected' : '' }}>
                                                                        {{ $company->company_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group" style="margin-right: 8px; width:20%">
                                                            <select name="party_id" id="party_id"
                                                                class="common-select2 w-100">
                                                                <option value="">Select...</option>
                                                                @foreach ($all_parties as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        {{ $party_id == $item->id ? 'selected' : '' }}>
                                                                        {{ $item->pi_name }}
                                                                        <small>({{ $item->pi_type }})</small></option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group" style="margin-right: 8px; width:20%">
                                                            <select name="project_id" id="project_id"
                                                                class="common-select2 w-100">
                                                                <option value="">Select...</option>
                                                                @foreach ($projects as $proj)
                                                                    <option value="{{ $proj->id }}"
                                                                        {{ $project_id == $proj->id ? 'selected' : '' }}> {{ $proj->project_name }} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <input type="hidden" name="searched_project" value="{{$project_id}}" id="">

                                                        <div class="form-group mr-0" style="margin-left: 8px; width:8%">
                                                            <select name="year" id="year"
                                                                class="form-control common-select2 w-100">
                                                                <option value="">Year </option>
                                                                @for ($i = 0; $i <= 10; $i++)
                                                                    @php
                                                                        $y = date('Y') - $i;
                                                                    @endphp
                                                                    <option value="{{ $y }}"
                                                                        {{ $year == $y ? 'selected' : '' }}>
                                                                        {{ $y }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>

                                                        <div class="form-group mr-0" style="margin-left: 8px; width:10%">
                                                            <select name="month" id="month"
                                                                class="form-control common-select2 w-100">
                                                                <option value="">Month</option>
                                                                @for ($i = 1; $i <= 12; $i++)
                                                                    @php
                                                                        $monthName = date('F', mktime(0, 0, 0, $i, 1));
                                                                    @endphp
                                                                    <option value="{{ $i }}"
                                                                        {{ $i == $month ? 'selected' : '' }}>
                                                                        {{ $monthName }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>

                                                        <div class="form-group mr-0" style="margin-left: 8px;width:8%">
                                                            <input type="text"
                                                                class="form-control inputFieldHeight datepicker w-100"
                                                                placeholder="From Date" name="from" autocomplete="off"
                                                                value="{{ old('from', $from ? date('d/m/Y', strtotime($from)) : null) }}">
                                                        </div>

                                                        <div class="form-group mr-0" style="margin-left: 8px;width:8%">
                                                            <input type="text"
                                                                class="form-control inputFieldHeight datepicker w-100"
                                                                placeholder="To Date" name="to" autocomplete="off"
                                                                value="{{ old('to', $to ? date('d/m/Y', strtotime($to)) : null) }}">
                                                        </div>

                                                        <input type="hidden" name="party_type"
                                                            value="{{ $party_type }}" id="party_type">

                                                        <button type="submit"
                                                            class="btn mb-2 formButton btn-primary inputFieldHeight"
                                                            title="Search"
                                                            style="padding: 0 20px !important; margin-left: 5px !important;">
                                                            Search
                                                        </button>

                                                        @if ($party_id)
                                                            {{-- <button type="button" class="download-pdf btn btn-info mb-2 formButton inputFieldHeight"
                                                                title="Extended Pdf/Print" data-type="extend" data-url="{{route('party_report_extend_pdf')}}">
                                                                <div class="d-flex">
                                                                    <div class="formSaveIcon">
                                                                        <i class='bx bxs-file-pdf text-white'></i>
                                                                    </div>
                                                                </div>
                                                            </button> --}}
                                                        @endif

                                                        <button type="button"
                                                            class="download-pdf btn {{ $party_type == 'all' ? 'btn-success' : 'btn-primary' }} mb-2 formButton inputFieldHeight"
                                                            title="All Party"
                                                            style="padding:3px 30px !important;margin-left: 5px !important;"
                                                            data-url="{{ route('party-report') }}" data-party="all">
                                                            All
                                                        </button>

                                                        <button type="button"
                                                            class="download-pdf btn btn-primary {{ $party_type == 'Supplier' ? 'btn-success' : 'btn-primary' }}  mb-2 formButton inputFieldHeight"
                                                            title="Excel"
                                                            style="padding:3px 10px !important; margin-left: 5px !important;"
                                                            data-url="{{ route('party-report') }}" data-party="Supplier">
                                                            Supplier
                                                        </button>

                                                        <button type="button"
                                                            class="download-pdf btn btn-primary mb-2 formButton inputFieldHeight {{ $party_type == 'Customer' ? 'btn-success' : 'btn-primary' }}"
                                                            title="Customer"
                                                            style="padding:3px 10px !important; margin-left: 5px !important;"
                                                            data-url="{{ route('party-report') }}" data-party="Customer">
                                                            Customer
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if (count($parties) > 0)
                                    <div class="text-center">
                                        <div class="text-center invoice-view-wrapper" style="padding:10px;">
                                            @if ($from && $to)
                                                <h5> Party ledger report from {{ date('d/m/Y', strtotime($from)) }} to
                                                    {{ date('d/m/Y', strtotime($to)) }}</h5>
                                            @elseif ($from)
                                                <h5> Party ledger report {{ date('d/m/Y', strtotime($from)) }}</h5>
                                            @elseif($to)
                                                <h5> Party ledger report {{ date('d/m/Y', strtotime($to)) }}</h5>
                                            @elseif($year || $month)
                                                Party Ledger report {{ date('F'), strtotime($month) }} {{ $year }}
                                            @else
                                                <h5> Party ledger report </h5>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="px-1" id="print_section">
                                                @include('layouts.backend.partial.modal-header-info')

                                    @if ($project)
                                    <div class="row">
                                        <div class="col-12">
                                            <h3 class="text-center">{{$project->new_project->plot }} : {{$project->project_name}}</h3>
                                            @if ($project->start_date)
                                            <h4 class="text-center">Start Date: {{$project->start_date}}</h4>

                                            @endif
                                        </div>
                                    </div>

                                    @endif

                                        @if (count($parties) > 0)
                                            <div class="customer-ledger">
                                                <table class="table table-sm">
                                                    <thead style="background:#475F7B; color:#fff;">
                                                        <tr>
                                                            <th class="text-white"
                                                                style="font-size: 13px; text-align:left !important; padding:0 10px; width:55%;">
                                                                Name </th>
                                                            <th class="text-white" style="font-size:13px; width:10%;">
                                                                Code </th>
                                                            <th class="text-white"
                                                                style="font-size:13px; width:10%;text-align:right !important">
                                                                Type </th>
                                                            <th class="text-white"
                                                                style="font-size:13px; width:10%;text-align:right !important;">
                                                                Remark </th>
                                                            <th class="text-white"
                                                                style="font-size:13px; width:15%;text-align:right !important;">
                                                                Balance <small> ({{ $currency->symbole }}) </small> </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($parties as $party)
                                                            <tr class="data-toggler"
                                                                data-target=".party-details-{{ $party->id }}"
                                                                data-url="{{ route('party-report-detail', [$party->id, $project_id]) }}"
                                                                data-fetch="1">
                                                                <td
                                                                    style="text-align:left !important; padding:5 10px; font-size:12px;">
                                                                    <div class="d-flex align-items-center">
                                                                        <i class='bx bx-plus'
                                                                            style="font-size: 16px;"></i>
                                                                        <i class='bx bx-minus d-none'
                                                                            style="font-size:16px;"> </i>
                                                                        <h5 class="text-left"
                                                                            style="margin-bottom: 0; font-size:13px;">
                                                                            {{ $party->pi_name }} </h5>
                                                                    </div>
                                                                </td>
                                                                <td style="text-align:center; font-size:13px;">
                                                                    {{ $party->pi_code }} </td>
                                                                <td
                                                                    style="text-align:center; font-size:13px; text-align:right !important;">
                                                                    {{ $party->pi_type }} </td>
                                                                @if ($party->dr_amount > $party->cr_amount)
                                                                    <td
                                                                        style="font-size:13px;  text-align:right !important;">
                                                                        Receivable </td>
                                                                @elseif($party->cr_amount > $party->dr_amount)
                                                                    <td
                                                                        style="font-size:13px;  text-align:right !important;">
                                                                        Payable </td>
                                                                @else
                                                                    <td> </td>
                                                                @endif
                                                                {{-- <td style="text-align:right !important; font-size:13px;"> {{$party->dr_amount}} </td>-->
                                                        <td style="text-align:right !important; font-size:13px;"> {{$party->cr_amount}} </td>--> --}}
                                                                @php
                                                                    $amount_fwd = App\JournalRecord::balance_fwd(
                                                                        $party->id,
                                                                        $year,
                                                                        $month,
                                                                        $to,
                                                                        $from,
                                                                    );
                                                                @endphp
                                                                <td
                                                                    style="text-align:right !important; font-size:13px; text-align:right !important;">
                                                                    {{ $party->dr_amount + $amount_fwd[0] > $party->cr_amount + $amount_fwd[1] ? 'DR' : 'CR' }}
                                                                    {{ number_format(abs($party->cr_amount + $amount_fwd[1] - ($party->dr_amount + $amount_fwd[0])), 2) }}

                                                                </td>
                                                            </tr>

                                                            <tr class="party-details-{{ $party->id }}"
                                                                style="display: none">
                                                                <td colspan="5" style="padding-left:20px;">
                                                                    <div class="loading-container">
                                                                        <div class="circle"></div>
                                                                    </div>
                                                                    <table
                                                                        class="table table-sm party_details_item_{{ $party->id }}">

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
                                @endif
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
@endpush

@push('js')
    <script>
        document.querySelectorAll('tbody tr').forEach((row, index) => {
            row.style.backgroundColor = index % 2 === 0 ? '#e3e3e3' : '#ffffff';
        });

        $(document).on('click', '.download-pdf', function() {
            var url = $(this).data('url');
            var type = $(this).data('type');
            var year = $('#year');

            // if (type === 'extend' && !year.val()) {
            //     year[0].focus();
            //     toastr.warning('Year field is required', 'Warning');
            //     return;
            // }

            var party_type = $(this).data('party');
            const form = $('form');
            form.attr('method', 'GET');

            if (party_type) {
                $('#party_type').val(party_type);
                form.removeAttr('target');
            } else {
                form.prop('target', ' ');
            }
            var formData = new URLSearchParams(new FormData(form[0]));
            var newUrl = url + (url.includes('?') ? '&' : '?') + formData.toString();

            form.prop('action', newUrl);
            form.submit();
            form.removeAttr('target');
            form.prop('action', '');
        });

        $(document).on('click', '.extend-download', function() {
            const queryData = JSON.parse($(this).attr('data-query'));
            var url = $(this).data('url');
            var confirmation = confirm(
                "The file is too large to render. We will notify you once the process is complete?");
            if (!confirmation) {
                return;
            }
            $.ajax({
                url: url,
                type: 'GET',
                data: queryData,
                success: function(response) {
                    checkNotification()
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while processing your request.');
                }
            });
        });

        function fetchDetail(url, party_id) {
            var month = "{{ $month }}";
            var from = "{{ $from }}"
            var to = "{{ $to }}";
            var year = "{{ $year }}";
            var company_id = "{{ $selected_company->id }}";

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    year: year,
                    month: month,
                    from: from,
                    to: to,
                    company_id: company_id,
                },

                success: function(res) {
                    $(`.party_details_item_${party_id}`).html(res);
                },
                error: function(error) {
                    toastr.error('Something rong to fetching error', 404);
                },
                complete: function() {
                    $('.loading-container').hide();
                }
            })
        };

        $(document).on('click', '.data-toggler', function() {
            $(this).find('.bx').toggleClass('d-none');
            $(this).find('td').toggleClass('bg-party-column');
            var target = $(this).data('target');
            var url = $(this).data('url');
            $(target).find('.loading-container').show();
            var fetchData = $(this).data('fetch');
            var party_id = target.match(/\d+/)[0];

            if (fetchData == 1) {
                $(target).toggle();
                fetchDetail(url, party_id);
            } else {
                $(this).data('fetch', 0);
                $(target).toggle();
                $('.loading-container').hide();
            }
        });

        $(document).on("click", ".journalDetails", function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            var v_type = $(this).attr('v-type');
            $.ajax({
                url: "{{ URL('voucher-preview-modal') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    v_type: v_type,
                },
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                }
            });
        });
    </script>
    <script>

        $(document).on("change", "#company_id_search, #party_id", function(e) {
                var company = $('#company_id_search').val();
                var party = $('#party_id').val();
                $.ajax({
                    url: "{{ URL('fetch-company-oth') }}",
                    type: "post",
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        company: company,
                        party:party
                    },
                    success: function(response) {
                        $('#project_id').empty().append(response);
                    }
                });
            });



        // details view
        $(document).on("click", ".show-details", function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            var v_type = $(this).data('type');
            $.ajax({
                url: "{{ URL('voucher-preview-modal') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    v_type: v_type,
                },
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                }
            });
        });
    </script>
@endpush

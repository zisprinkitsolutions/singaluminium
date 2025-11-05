@extends('layouts.backend.app')
@push('css')
    @include('layouts.backend.partial.style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    <style>
        .table td {
            padding: 0px;
        }

        .table th:nth-child(2),
        .table td:nth-child(2) {
            width: 10% !important;
            /* Set desired width */
            max-width: 10%;
            /* Optional to prevent overflow */
            white-space: nowrap;
            /* Prevent text wrap */
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #input-container .form-control {
            border: none;
        }

        #input-container .form-control:focus {
            border: 1px solid #4CB648;
        }


        .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-size: 12px !important;
        }

        .select2-container--default .select2-selection--single {
            height: 35px !important;
            width: 100%;
        }


        input.form-control {
            height: 35px !important;
        }

        th {
            color: #fff !important;
            vertical-align: top !important;
        }

        .table .thead-light th {
            color: #F2F4F4;
            background-color: #34465b;
            border-color: #DFE3E7;
        }

        tr:nth-child(even) {
            background-color: #c8d6e357;
        }

        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
        }

        thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background-color: #34465b !important;
            color: white;
            white-space: nowrap;
            padding: 10px 6px !important;
        }

        td {
            vertical-align: middle;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            text-align: left;
        }

        .select2-container--default .select2-results>.select2-results__options {
            text-align: left;
        }
        .f-600{
            font-weight: 600;
        }
    </style>
@endpush
@section('content')
    <div class="app-content content {{--print-hideen--}}">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.project._header')
                <div class="tab-content bg-white">
                    <div id="journaCreation" class="tab-pane active">
                        <section class="p-1" id="widgets-Statistics">
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 ">
                                <!-- Left side form -->
                                <form class="d-flex {{--flex-wrap--}} align-items-center print-hideen mt-1">
                                    {{-- <div class="print-hideen mb-1" style="width: 350px; margin-right: 5px;">
                                        <select name="company_id" class="common-select2 form-control form-control-sm w-100">
                                            <option value="0">SINGH ALUMINIUM AND STEEL</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}"
                                                    {{ $company_id == $company->id ? 'selected' : '' }}>
                                                    {{ $company->company_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    <div class="print-hideen mb-1" style="width: 400px; margin-right: 5px;">
                                        <select name="project_id" class="common-select2 form-control form-control-sm w-100">
                                            <option value="">Select Project...</option>
                                            @foreach ($all_projects as $project)
                                                <option value="{{ $project->id }}"
                                                    {{ $project->id == $project_id ? 'selected' : '' }}>
                                                    {{ $project->project_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <input type="text" name="from_date"
                                        class="datepicker form-control form-control-sm inputFieldHeight print-hideen mb-1"
                                        value="{{ $from_date ? date('d/m/Y', strtotime($from_date)) : '' }}"
                                        placeholder="From Date" autocomplete="off" style="width:100px; margin-right: 5px;">

                                    <input type="text" name="to_date"
                                        class="datepicker form-control form-control-sm inputFieldHeight print-hideen mb-1"
                                        value="{{ $to_date ? date('d/m/Y', strtotime($to_date)) : '' }}"
                                        placeholder="To Date" autocomplete="off" style="width:100px; margin-right: 5px;">

                                    <button type="submit" class="btn btn-primary btn-sm inputFieldHeight print-hideen mb-1"
                                        style="padding:3px 15px !important;">
                                        Search
                                    </button>
                                </form>
                                <!-- Right side buttons -->
                                <div class="d-flex flex-wrap justify-content-end mt-1 mt-md-0">
                                    <button class="btn btn-primary btn-sm inputFieldHeight print-hideen"
                                        style="padding:3px 8px !important; margin-right: 5px;" data-toggle="modal" data-target="#partyInfo">
                                        Project Ledger
                                    </button>
                                    {{-- <button class="btn btn-info btn-sm inputFieldHeight"
                                        style="padding:3px 8px !important; width: 80px;" onclick="window.print()">
                                        Print
                                    </button>
                                    <button onclick="exportToExcel();" class="btn btn-success btn-sm inputFieldHeight"
                                        style="padding:3px 8px !important; width: 100px;">
                                        Excel Export
                                    </button> --}}
                                    <!-- Right Side (Export/Import) -->
                                    <div class="dropdown print-hideen ">
                                        <button class="btn btn-info inputFieldHeight formButton dropdown-toggle"
                                            type="button" id="exportDropdown" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false"
                                            style="padding:4px 15px !important;">
                                            Export / Import
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="exportDropdown">
                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="exportToExcel('financial-analysis')">Excel Export</a>
                                            <a class="dropdown-item" href="javascript:void(0);"
                                                onclick="window.print()">Print</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive my-1">
                                <table class="table table-sm" id="financial-analysis">
                                    <thead style="background-color:#34465b !important;">
                                        <tr class="text-center">
                                            <th class="f-600 text-center" style="width:70px;"> Project No </th>
                                            <th class="f-600 text-left" style="width:30% !important;"> Project</th>
                                            <th class="f-600 text-center"> Plot No </th>
                                            <th class="f-600 text-right">
                                                <div class="d-flex flex-column">
                                                    <span class="f-600 text-right"> Contract Value </span>
                                                    <span class="f-600 total total_contact_value">
                                                        {{ number_format($total_cost['total_contarct_value'], 2) }} </span>
                                                </div>
                                            </th>

                                            <th>
                                                <div class="d-flex flex-column">
                                                    <span class="f-600 text-right"> Material Cost </span>
                                                    <span class="f-600 total matarial_cost text-right">
                                                        {{ number_format($total_cost['matarial_cost'], 2) }} </span>
                                                </div>
                                            </th>
                                            <th class="text-right">
                                                <div class="d-flex flex-column">
                                                    <span class="f-600 text-right"> Labour Cost </span>
                                                    <span
                                                        class="f-600 total labour_cost">{{ number_format($total_cost['total_labour_cost'], 2) }}
                                                    </span>
                                                </div>
                                            </th>

                                            <th class="text-right">
                                                <div class="d-flex flex-column">
                                                    <span class="f-600 text-right"> Administrative Cost </span>
                                                    <span class="f-600 total administrative_cost">
                                                        {{ number_format($total_cost['administrative_cost'], 2) }} </span>
                                                </div>
                                            </th>

                                            <th class="text-right">
                                                <div class="d-flex flex-column">
                                                    <span class="f-600 text-right"> Invoice Issued </span>
                                                    <span class="f-600 total total_invoice">
                                                        {{ number_format($total_cost['invoice_amount']), 2 }} </span>
                                                </div>
                                            </th>

                                            <th class="text-right">
                                                <div class="d-flex flex-column">
                                                    <span class="f-600 text-right"> Payment Received </span>
                                                    <span class="f-600 total receipt">
                                                        {{ number_format($total_cost['receipt'], 2) }}
                                                    </span>
                                                </div>
                                            </th>
                                            <th class="text-right">
                                                <div class="d-flex flex-column">
                                                    <span class="f-600 text-right"> Advance amount </span>
                                                    <span class="f-600 total total_contact_value">
                                                        {{ number_format($projects['advance_amount'], 2) }} </span>
                                                </div>
                                            </th>

                                            <th class="text-right">
                                                <div class="d-flex flex-column">
                                                    <span class="f-600 text-right"> Accrued Receivable </span>
                                                    <span class="f-600 total total_invoice">
                                                        {{ number_format($total_cost['accrued_amount']), 2 }} </span>
                                                </div>
                                            </th>

                                            <th class="text-right">
                                                <div class="d-flex flex-column">
                                                    <span class="f-600 text-right">Receivable</span>
                                                    <span class="f-600 total receivable_amount">
                                                        {{ number_format($total_cost['receivable_amount'], 2) }} </span>
                                                </div>
                                            </th>

                                            <th class="text-right">
                                                <div class="d-flex flex-column">
                                                    <span class="f-600 text-right"> Total Receivable</span>
                                                    <span class="f-600 total receivable_amount">
                                                        {{ number_format($total_cost['receivable_amount'] + $total_cost['accrued_amount'], 2) }}
                                                    </span>
                                                </div>
                                            </th>

                                            <th class="text-center"> Action </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($project_costs as $cost)
                                            <tr class="text-center">
                                                <td class="text-center">
                                                    {{ $cost['project_no'] }}
                                                </td>

                                                <td class="text-left" title="{{ $cost['project_name'] }}">
                                                    {{-- {{ \Illuminate\Support\Str::limit($cost['project_name'], 30) }} --}}
                                                    {{ \Illuminate\Support\Str::words($cost['project_name'], 2, '...') }}
                                                </td>

                                                <td class="text-center">
                                                    {{ $cost['project_plot'] }}
                                                </td>

                                                <td class="text-center">
                                                   {{$cost['contract_value']}}
                                                </td>

                                                <td class="view-details text-right" data-url="{{route('project.metarial.cost',['project' => $cost['project_id']])}}">
                                                   {{$cost['material_expense']}}
                                                </td>

                                                <td class="view-details text-right" data-url="{{route('project.labour.cost',['project' => $cost['project_id']])}}">
                                                   {{$cost['labour_cost']}}
                                                </td>

                                                <td class="view-details text-right" data-url="{{route('project.metarial.cost',['project' => $cost['project_id'], 'cost' => 'administrative'])}}">
                                                   {{$cost['administry_cost']}}
                                                </td>

                                                <td class="view-details text-right" data-url="{{route('project.invoice',['project' => $cost['project_id'], 'type' => 'all'])}}">
                                                   {{$cost['total_invoice']}}
                                                </td>

                                               <td class="view-details text-right" data-url="{{route('project.receipt',['project' => $cost['project_id'], 'type' => 'receipt'])}}">
                                                   {{$cost['receipt']}}
                                                </td>

                                                <td  class="view-details text-right" data-url="{{route('project.receipt',['project' => $cost['project_id'], 'type' => 'accrued_receivable'])}}">
                                                   {{$cost['accrued_receivable']}}
                                                </td>

                                                <td  class="view-details text-right" data-url="{{route('project.receipt',['project' => $cost['project_id'], 'type' => 'receivable'])}}">
                                                   {{$cost['receivable']}}
                                                </td>

                                                <td  class="view-details text-right" data-url="{{route('project.receipt',['project' => $cost['project_id'], 'type' => 'total_receivable'])}}">
                                                   {{$cost['total_receivable']}}
                                                </td>

                                                <td class="text-center">
                                                    <a data-url="{{route('new.project.roy.report',['project_id' => $cost['project_id'], 'print' => false])}}">

                                                    {{ $cost['project_plot'] }}
                                                </td>

                                                <td class="text-center">
                                                    {{ $cost['contract_value'] }}
                                                </td>


                                                <td class="view-details text-right"
                                                    data-url="{{ route('project.metarial.cost', ['project' => $cost['project_id']]) }}">
                                                    {{ $cost['material_expense'] }}
                                                </td>

                                                <td class="view-details text-right"
                                                    data-url="{{ route('project.labour.cost', ['project' => $cost['project_id']]) }}">
                                                    {{ $cost['labour_cost'] }}
                                                </td>

                                                <td class="view-details text-right"
                                                    data-url="{{ route('project.metarial.cost', ['project' => $cost['project_id'], 'cost' => 'administrative']) }}">
                                                    {{ $cost['administry_cost'] }}
                                                </td>

                                                <td class="view-details text-right"
                                                    data-url="{{ route('project.invoice', ['project' => $cost['project_id'], 'type' => 'all']) }}">
                                                    {{ $cost['total_invoice'] }}
                                                </td>

                                                <td class="view-details text-right"
                                                    data-url="{{ route('project.receipt', ['project' => $cost['project_id'], 'type' => 'receipt']) }}">
                                                    {{ $cost['receipt'] }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $cost['advance_amount'] }}
                                                </td>


                                                <td class="view-details text-right"
                                                    data-url="{{ route('project.invoice', ['project' => $cost['project_id'], 'type' => 'accrued_receivable']) }}">
                                                    {{ $cost['accrued_receivable'] }}
                                                </td>

                                                <td class="view-details text-right"
                                                    data-url="{{ route('project.invoice', ['project' => $cost['project_id'], 'type' => 'receivable']) }}">
                                                    {{ $cost['receivable'] }}
                                                </td>

                                                <td class="view-details text-right"
                                                    data-url="{{ route('project.invoice', ['project' => $cost['project_id'], 'type' => 'total_receivable']) }}">
                                                    {{ $cost['total_receivable'] }}
                                                </td>

                                                <td class="text-center">
                                                    <a data-url="{{ route('new.project.roy.report', ['project_id' => $project->id, 'print' => false]) }}"
                                                        class="btn p-0 roi-report" title="ROI Report">
                                                        <img src="{{ asset('/icon/due-report-icon.png') }}"
                                                            style="height: 20px; width: 20px;" alt="ROI Report">
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {!! $projects->links() !!}
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Roi Report --}}
    <div class="modal fade bd-example-modal-lg" id="RoiModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="ROIModalDetails">

                </div>
            </div>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade bd-example-modal-lg" id="project_expense_model1" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="project_expense_model_content1">

                </div>
            </div>
        </div>
    </div>

    {{-- modal --}}

    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherPreviewShow">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="partyInfo" tabindex="-1" data-keyboard="false" data-backdrop="static"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog {{--modal-xl--}} modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white ">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-family: Cambria; font-size: 1.5rem;"></h5>
                    <button type="button" class="btn btn-danger btn-sm ml-auto" data-dismiss="modal" aria-label="Close"
                        style="padding: 3px 10px; border-radius: 5px;">&times;</button>
                </div>

                <div class="modal-body px-3 py-2">
                    <form action="{{ route('search-project-report') }}" method="get">
                        @csrf
                        <div class="row mb-1">
                            {{-- First 3 selects in one row --}}
                            <div class="col-md-4 p-0 mb-2" style="padding-right: 5px !important;">
                                <select name="company_id" id="company_id_search"
                                    class="form-control common-select2 w-100">
                                    <option value="">Select Company...</option>
                                    <option value="0" selected>SINGH ALUMINIUM AND STEEL</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 p-0 mb-2" style="padding-right: 5px !important;">
                                <select name="party_id" id="party_id" class="form-control common-select2 w-100">
                                    <option value="">Select Party...</option>
                                    @foreach ($all_parties as $item)
                                        <option value="{{ $item->id }}">{{ $item->pi_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 p-0 mb-2" style="padding-right: 5px !important;">
                                <select name="project_id" id="project_id" class="form-control common-select2 w-100">
                                    <option value="">Select Project...</option>
                                    @foreach ($all_projects as $proj)
                                        <option value="{{ $proj->id }}">{{ $proj->project_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row ">
                            {{-- Next 5 inputs in second row --}}
                            <div class="col-md-2 p-0 mb-2" style="padding-right: 5px !important;">
                                <select name="year" id="year" class="form-control common-select2 w-100">
                                    <option value="">Year</option>
                                    @for ($i = 0; $i <= 10; $i++)
                                        @php $y = date('Y') - $i; @endphp
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2 p-0 mb-2" style="padding-right: 5px !important;">
                                <input type="text" class="form-control inputFieldHeight w-100" placeholder="Plot No"
                                    name="plot_no" autocomplete="off">
                            </div>
                            <div class="col-md-2 p-0 mb-2" style="padding-right: 5px !important;">
                                <input type="text" class="form-control inputFieldHeight w-100" placeholder="Location"
                                    name="location" autocomplete="off">
                            </div>
                            <div class="col-md-3 p-0 mb-2" style="padding-right: 5px !important;">
                                <input type="text" class="form-control inputFieldHeight datepicker w-100"
                                    placeholder="From Date" name="from" autocomplete="off">
                            </div>
                            <div class="col-md-3 p-0 mb-2" style="padding-right: 5px !important;">
                                <input type="text" class="form-control inputFieldHeight datepicker w-100"
                                    placeholder="To Date" name="to" autocomplete="off">
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-12 d-flex justify-content-center" style="padding-right: 5px !important;">
                                <a class="btn btn-success" style="padding: 5px 12px; margin-right: 5px;" title="All Project"
                                    href="{{ route('search-project-report') }}">All Project</a>
                                <button type="submit" class="btn btn-primary" title="Search">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('icon/search-icon.png') }}" width="20" class="" style="margin-right: 5px;"
                                            alt="Search">
                                        <span>Search</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $('.common-select2').select2({
            width: '100%'
        })

        $(document).on('click', '.view-details, .roi-report', function() {
            var url = $(this).data('url');
            var from_date = '{{ $from_date }}';
            var to_date = '{{ $to_date }}';
            var className = 'details';
            if ($(this).hasClass('roi-report')) {
                className = 'roi';
            } else {
                var className = 'details';
            }

            $.ajax({
                type: 'get',
                url: url,
                data: {
                    from_date: from_date,
                    to_date: to_date,
                },
                success: function(res) {
                    if (className == 'roi') {
                        $('#ROIModalDetails').html(res);
                        $('#RoiModal').modal('show');
                    } else {
                        $('#project_expense_model_content1').html(res);
                        $('#project_expense_model1').modal('show');
                    }

                },
                error: function(xhr) {
                    let message = xhr.responseJSON?.message || xhr.responseJSON?.error;
                    toastr.error(message, 'Error');
                }
            })
        });

        $(document).on("click", ".purch_exp_view", function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ URL('purch-exp-modal') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                },
                error: function(xhr) {
                    let message = xhr.responseJSON?.message || xhr.responseJSON?.error;
                    toastr.error(message, 'Error');
                }
            });
        });

        $(document).on("click", ".receipt_exp_view", function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ URL('receipt-list-modal') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                    $(".datepicker").datepicker({
                        dateFormat: "dd/mm/yy"
                    });
                }
            });
        });


        $(document).on("click", ".sale_view", function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ URL('sale-modal') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                },
                error: function(xhr) {
                    let message = xhr.responseJSON?.message || xhr.responseJSON?.error;
                    toastr.error(message, 'Error');
                }
            });
        });

        $(document).on('click', '.details', function() {
            var url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'get',
                success: function(res) {
                    document.getElementById("voucherPreviewShow").innerHTML = res;
                    $('#voucherPreviewModal').modal('show');
                },
                error: function(xhr) {
                    let message = xhr.responseJSON?.message || xhr.responseJSON?.error;
                    toastr.error(message, 'Error');
                }
            });
        });

        $(document).on('mouseenter', '.roi', function() {
            $('.roi-formula').removeClass('d-none');
        });

        $(document).on('mouseleave', '.roi', function() {
            $('.roi-formula').addClass('d-none');
        });

        $(document).on('hidden.bs.modal', '.modal', function() {
            if ($('.modal:visible').length) {
                $('body').addClass('modal-open');
            }
        });
    </script>
@endpush

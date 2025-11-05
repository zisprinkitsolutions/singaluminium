@extends('layouts.backend.app')
@push('css')
    @include('layouts.backend.partial.style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    <style>
        .ledger-table th,
        .ledger-table td {
            font-size: 12px;
            padding: 6px;
            vertical-align: middle;
        }

        .ledger-table thead th {
            /* background: #394c62db; */
            background: #3c4754c2;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .ledger-table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .project-card {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .05);
        }

        .project-card-header {
            /* background: #0076ff; */
            background: #19314c;
            color: #fff;
            padding: 8px 12px;
            font-weight: bold;
            border-radius: 6px 6px 0 0;
        }

        .project-card-body {
            padding: 10px 15px;
            background: #fdfdfd;
        }


        .select2-container--default .select2-selection--single .select2-selection__rendered {
            text-align: left;
        }

        .select2-container--default .select2-results>.select2-results__options {
            text-align: left;

        tr:not(.no-hover):hover {
        background: #f5f7fa !important;
        }
    </style>
@endpush
@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.project._header')
                <div class="tab-content bg-white">
                    <div id="journaCreation" class="tab-pane active">
                        <section class="p-1" id="widgets-Statistics">
                            <div class="d-flex justify-content-between align-items-center print-hideen mb-2">
                                <!-- Left side back button -->
                                <div class="col-md-3 text-left">
                                    <a href="{{ url()->previous() }}" class="btn btn-secondary inputFieldHeight"
                                        style="padding:3px 8px !important; width:80px;">
                                        Back
                                    </a>
                                </div>
                                <!-- Right side buttons -->
                                <div class="col-md-9 text-right">
                                    {{-- <button class="btn btn-success inputFieldHeight"
                                        style="padding:3px 8px !important; width:80px; "
                                        onclick="window.print()">
                                        Print/PDF
                                    </button> --}}
                                    <div class="d-flex align-items-center justify-content-end">
                                        <button class="btn btn-primary inputFieldHeight" style="padding:3px 8px !important; margin-right: 5px"
                                            data-toggle="modal" data-target="#partyInfo">
                                            Project Ledger
                                        </button>
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
                                                    onclick="exportToExcel('customer-ledger')">Excel Export</a>
                                                <a class="dropdown-item" href="javascript:void(0);"
                                                    onclick="window.print()">Print</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" my-1">
                                @include('layouts.backend.partial.modal-header-info')

                                <h3 class="text-center mb-3">Project Ledger</h3>
                                @if (count($projects) > 0)
                                    <div class="customer-ledger" id="customer-ledger">
                                    @foreach ($projects as $project)
                                        <div class="project-card">
                                            <div class="project-card-header d-flex justify-content-between align-items-center">
                                                <span>{{ $project->project_name }}</span>
                                                <span class="badge badge-light"><b>Project No: </b>
                                                    {{ $project->new_project->project_no }}</span>
                                                <span class="badge badge-light"><b>Plot: </b>
                                                    {{ $project->new_project->plot }}</span>
                                                <span class="badge badge-light"><b>Location: </b>
                                                    {{ $project->new_project->location }}</span>
                                                <span class="badge badge-light"><b>Start Date: </b>
                                                    {{ $project->start_date ? date('d/m/Y', strtotime($project->start_date)) : '-' }}</span>
                                            </div>
                                            <div class="project-card-body">
                                                {{-- <div class="row mb-2 text-left">
                                                    <div class="col-md-4"><b>Plot:</b> {{ $project->new_project->plot }}
                                                    </div>
                                                    <div class="col-md-4"><b>Location:</b>
                                                        {{ $project->new_project->location }}</div>
                                                    <div class="col-md-4"><b>Start Date:</b>
                                                        {{ $project->start_date ? date('d/m/Y', strtotime($project->start_date)) : '-' }}
                                                    </div>
                                                </div> --}}
                                                <div class="row  text-left">
                                                    <div class="col-md-4"><b>Contract Amount <i><small>(Incl. VAT)</small></i>:</b>
                                                        {{ number_format($project->new_project->total_contract, 2) }}</div>
                                                    <div class="col-md-4"><b>Handover Amount <i><small>(Incl. VAT)</small></i>:</b>
                                                        {{ number_format($project->new_project->total_contract*0.9, 2) }}</div>
                                                    <div class="col-md-4"><b>Retention Amount <i><small>(Incl. VAT)</small></i>:</b>
                                                        {{ number_format($project->retention_amount, 2) }}</div>
                                                </div>
                                            </div>
                                            <table class="table table-sm" style="border: 1px solid">
                                                <thead>
                                                    <tr style="display:none;">
                                                        <td>{{ $project->project_name }}</td>
                                                        <td class="badge badge-light"><b>Project No: </b>
                                                            {{ $project->new_project->project_no }}</td>
                                                        <td class="badge badge-light"><b>Plot: </b>
                                                            {{ $project->new_project->plot }}</td>
                                                        <td class="badge badge-light"><b>Location: </b>
                                                            {{ $project->new_project->location }}</td>
                                                        <td class="badge badge-light"><b>Start Date: </b>
                                                            {{ $project->start_date ? date('d/m/Y', strtotime($project->start_date)) : '-' }}</td>
                                                    </tr>
                                                    <tr style="display:none;">
                                                        <td><b>Contract Amount <i><small>(Incl. VAT)</small></i>:</b>
                                                            {{ number_format($project->new_project->total_contract, 2) }}</td>
                                                        <td><b>Handover Amount <i><small>(Incl. VAT)</small></i>:</b>
                                                            {{ number_format($project->new_project->total_contract*0.9, 2) }}</td>
                                                        <td><b>Retention Amount <i><small>(Incl. VAT)</small></i>:</b>
                                                            {{ number_format($project->retention_amount, 2) }}</td>
                                                    </tr>
                                                    <tr class="parrent-tr">
                                                        <td style="font-size: 12px;font-weight:800; padding-left:20px; background-color:#e3e3e3;width:10%;"> Date </td>
                                                        <td class="text-left" style="font-size: 12px; font-weight:800; background-color:#e3e3e3;"> Description </td>
                                                        <td style="font-size: 12px; font-weight:800; background-color:#e3e3e3;">Paymode</td>
                                                        <td style="font-size: 12px; font-weight:800; text-align:right !important;background-color:#e3e3e3; width:10%;">Debit </td>
                                                        <td style="font-size: 12px; font-weight:800; text-align:right !important;background-color:#e3e3e3; width:10%;">Credit </td>
                                                        <td style="font-size: 12px; font-weight:800; text-align:right !important;background-color:#e3e3e3;">Running Payment Balance </td>
                                                        <td style="font-size: 12px; font-weight:800; text-align:right !important;background-color:#e3e3e3;">Retention Receivable </td>
                                                        <td style="font-size: 12px; font-weight:800; text-align:right !important;background-color:#e3e3e3; width:10%;">Balance <small>{{$currency->symbole}}</small> </td>
                                                    </tr>
                                                </thead>
                                                @php
                                                    $balance_dr = 0.00;
                                                    $balance_cr = 0.00;
                                                    $runnign_balance = 0;
                                                    $retention_balance = 0;
                                                    $balance=0;
                                                @endphp
                                                {{-- <tr class="text-center trFontSize" style="cursor: pointer;">
                                                    <td></td>
                                                    <td class="text-left"><strong>Balance Brought FWD</strong></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="text-align:right !important;"> {{$balance_dr>$balance_cr?'DR ':'CR '}} {{number_format(abs($balance_dr-$balance_cr),2)}} </td>
                                                </tr> --}}
                                                @foreach ($project->journalRecords as $record)
                                                    @php
                                                        $journal=App\Journal::find($record->journal_id);
                                                        $isPayment=true;
                                                    @endphp

                                                    @if(!$journal->receipt_id && !$journal->payment_id)
                                                        <tr class="text-center trFontSize " v-type="main" style="cursor: pointer;" id="{{ $journal->journal_id }}">
                                                            <td>{{date('d/m/Y', strtotime($journal->date))}}</td>
                                                            <td class="text-left">{{$journal->party_journal_description($journal->id)['name']}}</td>
                                                            <td style="text-transform:uppercase;">{{$journal->pay_mode}} </td>
                                                            @if($journal->invoice_id)
                                                                <td style="text-align:right !important; ">{{number_format($b=$journal->records()->whereIn('account_head_id',[3,1759])->where('transaction_type','DR')->sum('amount')-$journal->records()->whereIn('account_head_id',[3,1759])->where('transaction_type','CR')->sum('amount'),2)}}</td>
                                                                <td style="text-align:right !important; ">0.00</td>
                                                                @php
                                                                    $balance_dr +=  $b;
                                                                @endphp
                                                            @else($journal->purchase_expense_id)
                                                                <td style="text-align:right !important; ">0.00</td>
                                                                <td style="text-align:right !important; ">{{number_format($c=$journal->records()->whereNotIn('account_head_id',[407,1760])->where('transaction_type','DR')->sum('amount'),2)}}</td>
                                                                @php $balance_dr -=  $c; @endphp
                                                            @endif

                                                            @php
                                                                $rr=App\JournalRecord::where('journal_id', $journal->id)->where('account_head_id', 1759)->first();
                                                                $runnign_balance += $journal->records()->whereIn('account_head_id',[3])->where('transaction_type','DR')->sum('amount')-$journal->records()->whereIn('account_head_id',[3])->where('transaction_type','CR')->sum('amount');

                                                                $retention_balance += $journal->records()->whereIn('account_head_id',[1759])->where('transaction_type','DR')->sum('amount')- $journal->records()->whereIn('account_head_id',[1759])->where('transaction_type','CR')->sum('amount')
                                                            @endphp
                                                            <td style="text-align:right !important;"> {{number_format($runnign_balance,2)}} </td>
                                                            <td style="text-align:right !important;">{{number_format($retention_balance,2)}}</td>

                                                            <td style="text-align:right !important;"> {{$balance_dr>$balance_cr?'DR ':'CR '}} {{number_format(abs(($balance_dr)-$balance_cr),2)}} </td>

                                                        </tr>
                                                    @endif

                                                    @php
                                                        $payment=$journal->records()->whereIn('account_head_id',[1,2,30,32,93,153])->get();
                                                        $cr_amount = $payment->where('transaction_type','DR')->sum('amount');
                                                        $dr_amount =$payment->where('transaction_type','CR')->sum('amount');
                                                        $amount=  $dr_amount-$cr_amount;
                                                        if ($journal->pay_mode != 'Advance') {
                                                            $balance_cr += $cr_amount;
                                                        }
                                                        if($journal->pay_mode == 'Advance'){
                                                            $balance_dr += $dr_amount;
                                                        }
                                                    @endphp
                                                    @if($payment->count())
                                                        <tr class="text-center trFontSize " v-type="main" style="cursor: pointer;" id="{{ $journal->journal_id }}">
                                                            <td>{{date('d/m/Y', strtotime($journal->date))}}</td>
                                                            <td class="text-left">{{$journal->party_journal_description($journal->id)['name']}}</td>
                                                            <td style="text-transform: uppercase !important;">{{$journal->pay_mode == 'Advance'?'-':$journal->pay_mode}}</td>
                                                            @if ($journal->pay_mode == 'Advance')
                                                                <td style="text-align:right !important; ">{{number_format($journal->total_amount,2)}}</td>
                                                                <td style="text-align:right !important; ">0</td>
                                                            @else
                                                                <td style="text-align:right !important; "> {{number_format($amount>0?$amount:0,2)}}</td>
                                                                @if ($journal->receipt?$journal->receipt->type == 'advance':'')
                                                                    <td style="text-align:right !important; ">{{number_format($journal->total_amount,2)}}</td>
                                                                @else
                                                                    <td style="text-align:right !important; ">{{number_format($amount<0?($amount*(-1)):0,2)}}</td>
                                                                @endif
                                                            @endif
                                                            @php
                                                                $rr=App\JournalRecord::where('journal_id', $journal->id)->where('account_head_id', 1759)->first();
                                                                $runnign_balance += $journal->records()->whereIn('account_head_id',[3])->where('transaction_type','DR')->sum('amount')-$journal->records()->whereIn('account_head_id',[3])->where('transaction_type','CR')->sum('amount');

                                                                $retention_balance += $journal->records()->whereIn('account_head_id',[1759])->where('transaction_type','DR')->sum('amount')- $journal->records()->whereIn('account_head_id',[1759])->where('transaction_type','CR')->sum('amount')
                                                            @endphp
                                                            <td style="text-align:right !important;"> {{number_format($runnign_balance,2)}} </td>
                                                            <td style="text-align:right !important;">{{number_format($retention_balance,2)}}</td>

                                                            <td style="text-align:right !important; "> {{$balance_dr>$balance_cr?'DR ':'CR '}} {{number_format(abs($balance_dr-$balance_cr),2)}} </td>

                                                        </tr>
                                                    @endif
                                                @endforeach
                                                <tr class=" no-hover">
                                                    <th class="text-right" colspan="8" style="font-size: 12px;">RUNNING PAYMENT RECEIVABLE: {{number_format($runnign_balance,2)}}</th>
                                                </tr>
                                                <tr class=" no-hover">
                                                    <th class="text-right" colspan="8" style="font-size: 12px;">{{$balance_dr>=$balance_cr?'GROSS RECEIVABLE INCLUDING RETENTION: ':'PAYABLE: '}} {{number_format(abs(($balance_dr)-$balance_cr),2)}}</th>
                                                </tr>
                                            </table>
                                        </div>
                                    @endforeach

                                </div>
                                @else
                                    <p class="text-center text-muted">No projects found.</p>
                                @endif


                                @include('layouts.backend.partial.modal-footer-info')
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
                <div class="modal-header" style="padding: 10px 15px; background: #364a60;">
                    <h5 class="modal-title" style="font-family: Cambria; font-size: 1.8rem; color: #fff;">
                        Search Project Ledger
                    </h5>
                    <button type="button" class="btn btn-danger btn-sm ml-2" data-dismiss="modal" aria-label="Close"
                        style="border-radius:5px; padding: 5px 10px;">&times;</button>
                </div>
                <div class="modal-body py-2 px-3" style="padding: 20px;">
                    <form action="{{ route('search-project-report') }}" method="get">
                        @csrf
                        <div class="row mt-2">
                            <!-- First 3 selects in one line -->
                            <div class="col-md-4 p-0 mb-2" style="padding-right: 5px !important;">
                                <select name="company_id" class="form-control common-select2 w-100">
                                    <option value="">Select Company...</option>
                                    <option value="0" selected>SINGH ALUMINIUM AND STEEL</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 p-0 mb-2" style="padding-right: 5px !important;">
                                <select name="party_id" class="form-control common-select2 w-100">
                                    <option value="">Select Party...</option>
                                    @foreach ($all_parties as $item)
                                        <option value="{{ $item->id }}">{{ $item->pi_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 p-0 mb-2" style="padding-right: 5px !important;">
                                <select name="project_id" class="form-control common-select2 w-100">
                                    <option value="">Select Project...</option>
                                    @foreach ($all_projects as $proj)
                                        <option value="{{ $proj->id }}">{{ $proj->project_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row ">
                            <!-- Next 5 inputs/selects in one line -->
                            <div class="col-md-2 p-0 mb-2" style="padding-right: 5px !important;">
                                <select name="year" class="form-control common-select2 w-100">
                                    <option value="">Year</option>
                                    @for ($i = 0; $i <= 10; $i++)
                                        @php $y = date('Y') - $i; @endphp
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2 p-0 mb-2" style="padding-right: 5px !important;">
                                <input type="text" name="plot_no" class="form-control w-100" placeholder="Plot No">
                            </div>
                            <div class="col-md-2 p-0 mb-2" style="padding-right: 5px !important;">
                                <input type="text" name="location" class="form-control w-100" placeholder="Location">
                            </div>
                            <div class="col-md-3 p-0 mb-2" style="padding-right: 5px !important;">
                                <input type="text" name="from" class="form-control datepicker w-100"
                                    placeholder="From Date">
                            </div>
                            <div class="col-md-3 p-0 mb-2" style="padding-right: 5px !important;">
                                <input type="text" name="to" class="form-control datepicker w-100"
                                    placeholder="To Date">
                            </div>
                        </div>

                        {{-- <div class="row  justify-content-end">
                            <div class="col-auto">
                                <a href="{{ route('search-project-report') }}" class="btn btn-success mb-2"
                                    style="padding: 5px 12px;">All Project</a>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary mb-2" style="padding: 5px 12px;">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('icon/search-icon.png') }}" alt="" width="20"
                                            class="" >
                                        <span>Search</span>
                                    </div>
                                </button>
                            </div>
                        </div> --}}
                        <div class="row justify-content-end">
                            <div class="col-12 d-flex justify-content-end" style="padding-right: 5px !important;">
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

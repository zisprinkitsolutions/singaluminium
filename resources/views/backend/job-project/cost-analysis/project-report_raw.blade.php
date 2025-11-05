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
    @media print{
        .nav.nav-tabs ~ .tab-content{
            border-left: 1px solid #fff;
            border-right: 1px solid #fff;
            border-bottom: 1px solid #fff;
            padding-left: 0;
        }
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
                        <div class="d-flex justify-content-between align-items-center print-hideen">
                            <form class="col-md-9 d-flex align-items-center mr-1 print-hideen"></form>
                            <div class="col-md-3 text-right print-hideen">
                                <button class="btn btn-success inputFieldHeight print-hideen" style="padding:3px 8px !important; width:80px; margin-right:5%;" onclick="window.print()"> Print/PDF </button>
                                <button class="btn btn-info inputFieldHeight print-hideen" style="padding:3px 8px !important;" data-toggle="modal" data-target="#partyInfo"> Project Ledger </button>
                            </div>
                        </div>

                        <div class=" my-1">
                            @include('layouts.backend.partial.modal-header-info')
                            <h3 class="text-center">Project Ledger</h3>
                            @if (count($projects) > 0)
                                <div class="customer-ledger">
                                    @foreach ($projects as $project)
                                        <table class="table table-sm mb-0 pb-0" style="border: 1px solid; background: #80808042 !important; color:black !important; " style="">
                                            <thead>
                                                <tr>
                                                    <td style="width: 150px;" class="text-left pl-1">Project Name</td>
                                                    <td class="text-left pl-1" colspan="3">:{{$project->project_name}}</td>
                                                    <td class="text-right">Project Starting</td>
                                                    <td class="text-left pl-1" style="width: 140px;">:{{$project->start_date?date('d/m/Y', strtotime($project->start_date)):''}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px;" class="text-left pl-1">Project No</td>
                                                    <td class="text-left pl-1">:{{$project->new_project->project_no}}</td>
                                                    <td style="width: 150px;" class="text-left pl-1">Plot No</td>
                                                    <td class="text-left pl-1">:{{$project->new_project->plot}}</td>
                                                    <td class="text-right">Location</td>
                                                    <td class="text-left pl-1" style="width: 140px;">:{{$project->new_project->location}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px;" class="text-left pl-1">Contract Amount</td>
                                                    <td class="text-left pl-1">:{{number_format($project->total_budget,2)}}</td>
                                                    <td style="width: 150px;" class="text-left pl-1">Handover Amount</td>
                                                    <td class="text-left pl-1">:{{number_format($project->paid_amount,2)}}</td>
                                                    <td class="text-right">Retention Amount</td>
                                                    <td class="text-left pl-1" style="width: 140px;">:{{number_format($project->retention_amount,2)}}</td>
                                                </tr>
                                            </thead>
                                        </table>
                                        <table class="table table-sm" style="border: 1px solid">
                                            <thead>
                                                <tr class="parrent-tr">
                                                    <td style="font-size: 10px;font-weight:800; padding-left:20px; background-color:#e3e3e3;width:10%;"> Date </td>
                                                    <td class="text-left" style="font-size: 10px; font-weight:800; background-color:#e3e3e3;"> Description </td>
                                                    <td style="font-size: 10px; font-weight:800; background-color:#e3e3e3;">Paymode</td>
                                                    <td style="font-size: 10px; font-weight:800; text-align:right !important;background-color:#e3e3e3; width:10%;">Debit </td>
                                                    <td style="font-size: 10px; font-weight:800; text-align:right !important;background-color:#e3e3e3; width:10%;">Credit </td>
                                                    <td style="font-size: 10px; font-weight:800; text-align:right !important;background-color:#e3e3e3;">Running Payment Balance </td>
                                                    <td style="font-size: 10px; font-weight:800; text-align:right !important;background-color:#e3e3e3;">Retention Receivable </td>
                                                    <td style="font-size: 10px; font-weight:800; text-align:right !important;background-color:#e3e3e3; width:10%;">Balance <small>{{$currency->symbole}}</small> </td>
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
                                        </table>
                                    @endforeach

                                </div>
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
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px;background:#364a60;">
                <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;"></h5>
                <div class="d-flex align-items-center">
                    <button type="button" class="project-btn bg-danger text-white" data-dismiss="modal" aria-label="Close" style="padding: 5px 10px;border:none; border-radius:5px" data-bs-toggle="tooltip" data-bs-placement="right" title="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{-- @include('alerts.alerts') --}}
                </div>
            </div>
            <div class="modal-body" style="padding: 15px 15px;">
                <section id="widgets-Statistics" class="mr-1 ml-1 mb-1">
                    <div class="row">
                        <div class="col-12 party-info-form">
                            <form action="{{ route('search-project-report') }}" method="get">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="company_id" id="company_id_search" class="common-select2 inputFieldHeight w-100">
                                            <option value="">Select Company...</option>
                                            <option value="0" selected>SINGH ALUMINIUM AND STEEL</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <select name="party_id" id="party_id" class="common-select2 w-100">
                                            <option value="">Select Party...</option>
                                            @foreach ($all_parties as $item)
                                                <option value="{{ $item->id }}">{{ $item->pi_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class=" col-md-3">
                                        <select name="project_id" id="project_id" class="common-select2 w-100">
                                            <option value="">Select Project...</option>
                                            @foreach ($all_projects as $proj)
                                                <option value="{{ $proj->id }}" >{{ $proj->project_name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="year" id="year" class="form-control common-select2 w-100">
                                            <option value="">Year </option>
                                            @for ($i = 0; $i <= 10; $i++)
                                                @php $y = date('Y') - $i; @endphp
                                                <option value="{{ $y }}"> {{ $y }} </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="mt-1 col-md-3">
                                        <input type="text" class="form-control inputFieldHeight w-100" placeholder="Plot No" name="plot_no" autocomplete="off">
                                    </div>
                                    <div class="mt-1 col-md-3">
                                        <input type="text" class="form-control inputFieldHeight w-100" placeholder="Location" name="location" autocomplete="off">
                                    </div>
                                    <div class="mt-1 col-md-3">
                                        <input type="text" class="form-control inputFieldHeight datepicker w-100" placeholder="From Date" name="from" autocomplete="off">
                                    </div>

                                    <div class="mt-1 col-md-3">
                                        <input type="text" class="form-control inputFieldHeight datepicker w-100" placeholder="To Date" name="to" autocomplete="off" >
                                    </div>
                                    <div class="col-md-12 mt-1 d-flex justify-content-end changeColStyle">
                                        <a class="btn mb-2 btn-success formButton inputFieldHeight mr-1" style="padding: 5px 10px !important;" title="All Project" width="25" href="{{route('search-project-report')}}">All Project</a>
                                        <button type="submit" class="btn btn-primary formButton mb-2" title="Search">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img  src="{{asset('icon/search-icon.png')}}" alt="" srcset="" class="img-fluid" width="20">
                                                </div>
                                                <div><span> Search</span></div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
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
                error: function (xhr) {
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
                },
                error: function (xhr) {
                    let message = xhr.responseJSON?.message || xhr.responseJSON?.error;
                    toastr.error(message, 'Error');
                }
            });
        });

        $(document).on('click', '.details', function(){
            var url = $(this).data('url');
            $.ajax({
                url:url,
                type:'get',
                success:function(res){
                    document.getElementById("voucherPreviewShow").innerHTML = res;
                    $('#voucherPreviewModal').modal('show');
                },
                error: function (xhr) {
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

        $(document).on('hidden.bs.modal', '.modal', function () {
            if ($('.modal:visible').length) {
                $('body').addClass('modal-open');
            }
        });
</script>
@endpush

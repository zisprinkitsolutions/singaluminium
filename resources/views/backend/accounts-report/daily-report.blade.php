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
                display: none ;
            }

            td{
        text-align: center !important;
    }
    th{
        text-align: center !important;
    }
        @media(min-width:1300px) {
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
                padding: 0px !important;
            }
            .bg-secondary {
                background-color: #34465b !important;
                border-radius: 0px !important;
                color: white !important;
                padding: 0px !important;
            }
            table {
                padding-right: 10px;
                padding-left: 10px
            }
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
                            <section id="widgets-Statistics ">
                                <div class="pl-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        @include('clientReport.report._accounting_report_subheader', [
                                            'activeMenu' => 'daily_summary',
                                        ])
                                    </div>
                                </div>
                                <div class="cardStyleChange">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div>
                                                {{-- <a href="#" class="btn btn_create mPrint formButton" title="Print" onclick="window.print()">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{asset('assets/backend/app-assets/icon/print-icon.png')}}" width="25">
                                                        </div>
                                                        <div><span>Print</span></div>
                                                    </div>
                                                </a> --}}
                                            </div>
                                        </div>
                                        <div class="row ml-1">
                                            <div class="col-md-3">
                                                <form action="" method="GET" class="d-flex row">
                                                    <div class="row form-group col-md-8" style="padding-left:7px;">
                                                        <input type="text" class="inputFieldHeight form-control datepicker"
                                                            name="date"
                                                            placeholder="Select Date"
                                                             required autocomplete="off">
                                                    </div>
                                                    <div class="col-md-4 mr-0">
                                                        <button type="submit" class="btn mSearchingBotton mb-2 formButton"
                                                            title="Search">
                                                            <div class="d-flex">
                                                                <div class="formSaveIcon">
                                                                    <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                        width="25">
                                                                </div>
                                                                <div><span>Search</span></div>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>


                                            <div class="col-md-6">
                                                <form action="" method="GET" class="d-flex row">
                                                    <div class="row form-group col-md-4 mr-0">
                                                        <input type="text" class="inputFieldHeight form-control datepicker"
                                                            name="from"
                                                            placeholder="From Date"
                                                             required autocomplete="off">
                                                    </div>
                                                    <div class="row form-group col-md-4 mr-0">
                                                        <input type="text" class="inputFieldHeight form-control datepicker"
                                                            name="to"  placeholder="To Date"
                                                             required autocomplete="off">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="submit" class="btn mSearchingBotton mb-2 formButton"
                                                            title="Search">
                                                            <div class="d-flex">
                                                                <div class="formSaveIcon">
                                                                    <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                        width="25">
                                                                </div>
                                                                <div><span>Search</span></div>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="col-md-3 text-right col-padding-right">
                                               <a href="#"onclick="var e=document.getElementById('print-table').innerHTML,t=document.body.innerHTML;document.body.innerHTML=e,window.print(),document.body.innerHTML=t;" class="btn btn-icon btn-secondary"><i class="bx bx-printer"></i> Print</a>
                                                <button type="button" class="btn mExcelButton formButton"
                                                    title="Export"
                                                    onclick="exportTableToCSV('daily-balance-{{ $date }}.csv')">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{ asset('assets/backend/app-assets/icon/excel-icon.png') }}"
                                                                width="25">
                                                        </div>
                                                        <div><span>Excel</span></div>

                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 <div id="print-table">
                                    <span class="print-header-footer " >
                                        @include('layouts.backend.partial.modal-header-info')
                                    </span>

                                    <div class="card-body pt-0 pb-0">
                                        <table class="table table-sm table-hover ">
                                            <tr>
                                                <th colspan="7" class="text-center">
                                                    <h2>Daily Summary</h2>
                                                    <h4> {{$from!=null?date('d F Y', strtotime($from)). '-'.date('d F Y', strtotime($to)):($date!=null?date('d F Y', strtotime($date)):'')}}</h4>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th colspan="7" class="text-left bg-secondary text-white">Running Collection</th>
                                            </tr>
                                            <tr>
                                                <th>Date</th>
                                                <th>Journal No</th>
                                                <th>Acc. Head</th>
                                                <th>Party/Cus./Supplier</th>
                                                <th>Payment</th>
                                                <th>Amount</th>
                                            </tr>
                                            @foreach ($sales as $sale)
                                                @php
                                                    $journal = App\Journal::find($sale->journal_id);
                                                @endphp
                                                <tr class="trFontSize journalDetails" v-type="main" style="cursor: pointer;"
                                                    id="{{ $journal->id }}">
                                                    <td>{{ $journal->date }}</td>
                                                    <td>{{ $journal->journal_no }}</td>
                                                    <td>Sale</td>
                                                    <td>{{ $journal->party->pi_name }}</td>
                                                    <td>{{ $journal->records()->where('transaction_type', 'DR')->first()->ac_head->fld_ac_head }}
                                                    </td>
                                                    <td>{{ $journal->records()->where('transaction_type', 'DR')->first()->total_amount }}
                                                    </td>
                                                    <td>{{ $journal->transaction_type == 'DR' ? '$' . $journal->total_amount : '' }}
                                                    </td>
                                                    <td>{{ $journal->transaction_type == 'CR' ? '$' . $journal->total_amount : '' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th colspan="7" class="text-left bg-secondary text-white">Previous Due Collection</th>
                                            </tr>
                                            <tr>
                                                <th>Date</th>
                                                <th>Journal No</th>
                                                <th>Acc. Head</th>
                                                <th>Party/Cus./Supplier</th>
                                                <th>Payment</th>
                                                <th>Amount</th>
                                            </tr>
                                            @foreach ($receiveds as $rev)
                                                @php
                                                    $journal = App\Journal::find($rev->journal_id);
                                                @endphp
                                                <tr class="trFontSize journalDetails" v-type="main" style="cursor: pointer;"
                                                    id="{{ $journal->id }}">
                                                    <td>{{ $journal->date }}</td>
                                                    <td>{{ $journal->journal_no }}</td>
                                                    <td>{{ $journal->records()->where('transaction_type', 'CR')->first()->ac_head->fld_ac_head }}
                                                    </td>
                                                    <td>{{ $journal->party->pi_name }}</td>
                                                    <td>{{ $journal->records()->where('transaction_type', 'DR')->first()->ac_head->fld_ac_head }}
                                                    </td>
                                                    <td>{{ $journal->records()->where('transaction_type', 'DR')->first()->total_amount }}
                                                    </td>
                                                    <td>{{ $journal->transaction_type == 'DR' ? '$' . $journal->total_amount : '' }}
                                                    </td>
                                                    <td>{{ $journal->transaction_type == 'CR' ? '$' . $journal->total_amount : '' }}
                                                    </td>
                                                </tr>
                                            @endforeach


                                            <tr>
                                                <th colspan="7" class="text-left bg-secondary text-white">Purchase</th>
                                            </tr>

                                            <tr>
                                                <th>Date</th>
                                                <th>Journal No</th>
                                                <th>Acc. Head</th>
                                                <th>Party/Cus./Supplier</th>
                                                <th>Payment</th>
                                                <th>Amount</th>
                                            </tr>
                                            @foreach ($purchases as $purchase)
                                                @php
                                                    $journal = App\Journal::find($purchase->journal_id);
                                                @endphp
                                                <tr class="trFontSize journalDetails" v-type="main" style="cursor: pointer;"
                                                    id="{{ $journal->id }}">
                                                    <td>{{ $journal->date }}</td>
                                                    <td>{{ $journal->journal_no }}</td>
                                                    <td>Purchase </td>
                                                    <td>{{ $journal->party->pi_name }}</td>
                                                    <td>{{ $journal->records()->where('transaction_type', 'CR')->first()->ac_head->fld_ac_head }}
                                                    </td>
                                                    @php

                                                    @endphp
                                                    <td>{{ $journal->purchaseAmnt() }}
                                                    </td>

                                                </tr>
                                            @endforeach

                                            <tr>
                                                <th colspan="7" class="text-left bg-secondary text-white">Previous Balance Due Payment</th>
                                            </tr>

                                            <tr>
                                                <th>Date</th>
                                                <th>Journal No</th>
                                                <th>Acc. Head</th>
                                                <th>Party/Cus./Supplier</th>
                                                <th>Payment</th>
                                                <th>Amount</th>
                                            </tr>
                                            @foreach ($payments as $payment)
                                                @php
                                                    $journal = App\Journal::find($payment->journal_id);
                                                @endphp
                                                <tr class="trFontSize journalDetails" v-type="main" style="cursor: pointer;"
                                                    id="{{ $journal->id }}">
                                                    <td>{{ $journal->date }}</td>
                                                    <td>{{ $journal->journal_no }}</td>
                                                    <td>{{ $journal->records()->where('transaction_type', 'DR')->first()->ac_head->fld_ac_head }}
                                                    </td>
                                                    <td>{{ $journal->party->pi_name }}</td>
                                                    <td>{{ $journal->records()->where('transaction_type', 'CR')->first()->ac_head->fld_ac_head }}
                                                    </td>
                                                    <td>{{ $journal->records()->where('transaction_type', 'CR')->first()->total_amount }}
                                                    </td>

                                                </tr>
                                            @endforeach



                                            <tr>
                                                <th colspan="7" class="text-left bg-secondary text-white">Expense</th>
                                            </tr>

                                            <tr>
                                                <th>Date</th>
                                                <th>Journal No</th>
                                                <th>Acc. Head</th>
                                                <th>Party/Cus./Supplier</th>
                                                <th>Payment</th>
                                                <th>Amount</th>
                                            </tr>
                                            @foreach ($expensess as $expense)
                                                @php
                                                    $journal = App\Journal::find($expense->journal_id);
                                                @endphp
                                                <tr class="trFontSize journalDetails" v-type="main" style="cursor: pointer;"
                                                    id="{{ $journal->id }}">
                                                    <td>{{ $journal->date }}</td>
                                                    <td>{{ $journal->journal_no }}</td>
                                                    <td>{{ $journal->records()->where('transaction_type', 'DR')->first()->ac_head->fld_ac_head }}
                                                    </td>
                                                    <td>{{ $journal->party->pi_name }}</td>
                                                    <td>{{ $journal->records()->where('transaction_type', 'CR')->first()->ac_head->fld_ac_head }}
                                                    </td>
                                                    <td>{{ $journal->records()->where('transaction_type', 'DR')->where('account_head_id', '!=', 851)->sum('total_amount') }}
                                                    </td>

                                                </tr>
                                            @endforeach

                                        </table>
                                    </div>

                                    <div class="card-body d-flex align-items-center">
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <th class="text-center bg-secondary text-white" colspan="2">Balance</th>
                                            </tr>
                                            <tr>
                                                <td>Cash</td>
                                                <td>{{ $cash_balance }}</td>
                                            </tr>
                                            <tr>
                                                <td>Bank</td>
                                                <td>{{ $bank_balance }}</td>
                                            </tr>
                                            <tr>
                                                <td>Payable</td>
                                                <td>{{ $payable_balance*(-1) }}</td>
                                            </tr>
                                            <tr>
                                                <td>Receivable</td>
                                                <td>{{ $receivable_balance }}</td>
                                            </tr>

                                        </table>


                                    </div>
                                    <div class="divFooter mb-1 ml-1 print-header-footer " >
                                        Business Software Solutions by
                                        <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png')}}" alt="" width="150"></span>
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
@endpush

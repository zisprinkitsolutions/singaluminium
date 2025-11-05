
@extends('layouts.backend.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@section('content')
    @include('layouts.backend.partial.style')
    
<style>
    .tabPadding{
        padding: 5px;
    }
    .padding-right{
        padding-right: 10px;
    }
    td{
        font-size: 12px !important;
    }

    th{
        font-size: 14px !important;
    }
    @media(min-width:1300px){
        .padding-right{
            padding-right: 0px !important;
        }
    }

    .card-body {
        flex: 1 1 auto;
        min-height: 1px;
        padding: 0rem !important;
    }
</style>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <div class="print-hideen">
                    @include('clientReport.report._header',['activeMenu' => 'petty-cash'])
                </div>
                <div class="tab-content journaCreation">
                    <div id="journaCreation" class="tab-pane bg-white active">
                        <section id="widgets-Statistics">

                            <div class="row ">

                                <div class="col-md-12 px-2">
                                    <div class="print-layout">
                                        @include('layouts.backend.partial.modal-header-info')
                                        <h4 class="text-center">Petty Cash Reports</h4>
                                    </div>
                                    <div class="cardStyleChange" style="width: 100%">
                                        <div class="card-body bg-white">
                                            <form action="">
                                                <div class="row mt-1 print-hideen">
                                                    <div class="col-2">
                                                        <input type="text" name="date_from" id="date_from"
                                                            class="form-control inputFieldHeight datepicker by_date_search"
                                                            placeholder="Search by Date From">
                                                    </div>
                                                    <div class="col-2">
                                                        <input type="text" name="date_to" id="date_to"
                                                            class="form-control inputFieldHeight datepicker by_date_search"
                                                            placeholder="Search by Date To">
                                                    </div>
                                                    <div class="col-sm-1 text-right d-flex justify-content-end">
                                                        <button type="submit" class="btn btn-dark formButton " id="submitButton">
                                                            <div class="d-flex">
                                                                <div class="formSaveIcon">
                                                                    <img  src="{{asset('assets/backend/app-assets/icon/search-icon.png')}}" alt="" srcset=""  width="25">
                                                                </div>
                                                                <div><span>Search</span></div>
                                                            </div>
                                                        </button>
                                                    </div>
                                                    <div class="col-7 d-flex justify-content-end">
                                                        <a href="#" class="btn btn_create mPrint formButton float-right" title="Print" onclick="window.print()">
                                                            <div class="d-flex">
                                                                <div class="formSaveIcon">
                                                                    <img src="{{ asset('assets/backend/app-assets/icon/print-icon.png') }}" width="25">
                                                                </div>
                                                                <div><span>Print</span></div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </form>

                                            <table class="table table-bordered table-sm ">
                                                <thead class="thead">
                                                    <tr>
                                                        <th style="width: 13%">Date</th>
                                                        <th style="width: 13%">Bill/Transection</th>
                                                        <th>Paid To/ Receive From</th>
                                                        <th style="width: 13%">Cash In</th>
                                                        <th style="width: 13%">Cash Out</th>
                                                        <th style="width: 13%">Balance</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="purch-body">
                                                    @php
                                                        $t_balance=0;
                                                        $cash_in = 0;
                                                        $cash_out = 0;
                                                    @endphp
                                                    @foreach ($petty_cashs as $item)
                                                        @php
                                                            $cash_in = null;
                                                            $cash_out = null;
                                                            if($item->source=='fund_allocations'){
                                                                $pay_mode = App\PayMode::find($item->account_id_from);
                                                                $pay_name = $pay_mode->title;
                                                                if($item->account_id_from == 5){
                                                                    $cash_out = $item->amount;
                                                                }else{
                                                                    $cash_in = $item->amount;
                                                                }
                                                            }else {
                                                                $pay_name = $item->account_id_from;
                                                                $cash_out = $item->amount;
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                            <td>{{$item->transaction_number}}</td>
                                                            <td>{{$pay_name}}</td>
                                                            <td>{{$cash_in?number_format($balance_in=$cash_in,2,'.',''):$balance_in=null}}</td>
                                                            <td>{{$cash_out?number_format($balance_out=$cash_out,2,'.',''):$balance_out=null}}</td>
                                                            <td>
                                                                {{number_format($t_balance=($t_balance+$balance_in)-$balance_out,2,'.','') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    {{-- <tr>
                                                        <td colspan="4" class="text-left pl-2">Cash In</td>
                                                        <td>{{number_format($cash_in,2,'.','')}}</td>
                                                        <td>{{number_format($cash_out,2,'.','')}}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5" class="text-left pl-2">Cash Out</td>
                                                        <td>{{number_format($cash_out,2,'.','')}}</td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr> --}}
                                                    {{-- <tr>
                                                        <td colspan="6" class="text-left pl-2">Opening Balance</td>
                                                        <td>{{number_format($opening_balance->where('type', 'Cash In')->sum('total_amount')-$opening_balance->where('type', 'Cash Out')->sum('total_amount'),2)}}</td>
                                                    </tr> --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
    {{-- modal --}}
    <!-- END: Content-->
    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherPreviewShow">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="voucherDetailsPrintModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherDetailsPrint">

                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/js/scripts/forms/select/form-select2.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/js/scripts/forms/form-repeater.js"></script>
    {{-- js work by mominul start --}}

    <script>

    </script>
@endpush

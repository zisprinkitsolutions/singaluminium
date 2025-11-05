@extends('layouts.backend.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@php
$company_name= \App\Setting::where('config_name', 'company_name')->first();
$company_address= \App\Setting::where('config_name', 'company_address')->first();
$address2= \App\Setting::where('config_name', 'address2')->first();
$company_tele= \App\Setting::where('config_name', 'company_tele')->first();
$company_email= \App\Setting::where('config_name', 'company_email')->first();
$trn_no= \App\Setting::where('config_name', 'trn_no')->first();
$i=1;
@endphp
@section('content')
<style>
    @media print{
        .thermal-print2{
            display: none !important;
        }
        .thermal-table{
            width: 75px;
            font-size: 10px;
            max-width: 75px;
        }
        .dropdown-filter-dropdown{
            display: none;
        }
        .nav.nav-tabs ~ .tab-content {
            border-left: 1px solid #fff !important;
            border-right: 1px solid #fff !important;
            border-bottom: 1px solid #fff !important;
            padding-left: 0;
        }
        .centered {
            text-align: center;
            align-content: center;
        }

        @page {
            margin: 0px !important;
            padding: 0px !important;
        }
    }
</style>
    @include('layouts.backend.partial.style')

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">

            @include('clientReport.report._header', [ 'activeMenu' => 'stock_report'])
                <div class="tab-content journaCreation">
                    <div id="journaCreation" class="tab-pane bg-white active">
                        <section id="widgets-Statistics row">
                            <div class="col-md-12">
                                <div class="cardStyleChange">
                                    <div class="card-body bg-white">
                                            <div class="row mt-1">
                                                <h5 class="pl-1 pb-1 thermal-print"> Stock Summary Report </h5>
                                                <div class="col-md-10  print-hideen">
                                                    <form method="get">
                                                        <div class="row">
                                                            <div class="col-3">
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                        class="form-control from-date inputFieldHeight datepicker"
                                                                        autocomplete="off" value=""
                                                                        name="form_date"placeholder=" Filter by Date: {{ $from ? date('d/m/Y', strtotime($from)) : 'dd/mm/yyyy' }}">
                                                                </div>
                                                            </div>

                                                            <div class="col-3 to-date-toggle "
                                                            @if (!$to) style="display: none" @endif>
                                                                <div class="form-group ">
                                                                    <div class="row">
                                                                        <div class="col-2">
                                                                            <label class="form-lebel" style="margin-top:10px"
                                                                                for="">To </label>
                                                                        </div>
                                                                        <div class="col-10">
                                                                            <input id="elementToToggle"
                                                                                type="text"class="form-control inputFieldHeight to-date datepicker "
                                                                                name="to_date" value="" autocomplete="off"
                                                                                placeholder="{{ $to ? date('d/m/Y', strtotime($to)) : 'dd/mm/yyyy' }} ">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-2 p-0">
                                                                <button type="submit"
                                                                    class="btn  formButton mSearchingBotton"title="Searching"
                                                                    style="padding: 6px 10px; background:#1A233A !important">
                                                                    <div class="d-flex">
                                                                        <div class="formSaveIcon">
                                                                            <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                                alt=""srcset="" width="20">
                                                                        </div>
                                                                    </div>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-md-2 float-right d-flex flex-row-reverse  print-hideen">
                                                    <a class=" action-btn uninvoice-item float-right botton-style print-window ml-1 coursur-pointer" style="cursor: pointer;"  onclick="media_print('print-table')" title="Print this table record !">
                                                        <img src="{{ asset('assets/backend/app-assets/icon/print-icon.png') }}"srcset=""class="img-fluid" width="25">
                                                    </a>

                                                </div>
                                                <div class="col-12">
                                                    <table class="table table-bordered table-sm table-striped 2filter-table thermal-table" id="print-table" style="width: 100% ">
                                                        <thead>
                                                            <tr class=" text-center">
                                                                <td colspan="12" id="header_info_td">
                                                                    @include('layouts.backend.partial.modal-header-info')
                                                                    <span class="invoice-view-wrapper text-center">Stock Summary Reports</span> <br>
                                                                    <span class="invoice-view-wrapper text-center">{{$from ? date('d/m/Y', strtotime($from)):''}} {{$to ? ' To '.date('d/m/Y', strtotime($to)):''}}</span>
                                                                </td>
                                                            </tr>
                                                        </thead>
                                                        <thead class="thead">
                                                            <tr class="trFontSize text-center">
                                                                <th>Account Head </th>
                                                                <th>Opening Stock </th>
                                                                <th>Qty In </th>
                                                                <th>Qty Out </th>
                                                                {{-- <th>Sale Return</th>
                                                                <th>Purchase Return</th> --}}
                                                                <th>Closing Stock</th>
                                                                {{-- <th style="width: 10%;text-align: right !important;padding-right: 5px;" title="Value Of Closing Stock" class="thermal-print"> Stock Value
                                                                    <small> ( {{ $currency->currency_nane }}) </small>
                                                                </th> --}}
                                                            </tr>
                                                        </thead>
                                                        <tbody id="purch-body">
                                                            @foreach ($products as $key => $item)
                                                            @php
                                                                $values = $item->openning_product_office( $from, $item->id);
                                                                $quantity = $item->quantity_in( $from, $to, $item->id);
                                                                $quantity_out = $item->quantity_out( $from, $to, $item->id);
                                                                $closing_qunatity = $values['opening'] + $quantity['quantity_in'] - $quantity_out;
                                                                $return_product = $item->return_product( $from, $to, $item->id);
                                                            @endphp
                                                                <tr class="account_head_allocation text-center trFontSize" id="">
                                                                    <td>{{ $item->product_name }}</td>
                                                                    <td>{{ floatval($values['opening'])}}</td>
                                                                    <td>{{ floatval($quantity['quantity_in'])}}</td>
                                                                    <td>{{ floatval($quantity_out) }}</td>
                                                                    {{-- <td>0</td>
                                                                    <td>0</td> --}}
                                                                    <td>{{ floatval($closing_qunatity)}}</td>
                                                                    {{-- <td style="width: 15%; text-align: right !important; padding-right: 5px;" class="thermal-print">{{ number_format($item->last_purchase_price->amount/$item->last_purchase_price->qty*$closing_qunatity , 2, '.', '') }}</td> --}}
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
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

@endsection
@push('js')
    <script>
        window.onafterprint = function() {
            location.reload();
        };
        $(document).on('click', '.thermal_print_button', function(){
            $('.thermal-print').addClass('thermal-print2')
            window.print();
        })
        $(document).ready(function() {
            $(document).on('change', '.from-date', function() {
                var date = $(this).val();
                $('.to-date').val(date);
                $('.to-date-toggle').show();
            });
            $(document).on('change', '.to-date', function() {
                var to_date = $(this).val();
                var from_date = $('.from-date').val();

                var date1Milliseconds = Date.parse(from_date.replace(/(\d{2})\/(\d{2})\/(\d{4})/,
                    '$2/$1/$3'));
                var date2Milliseconds = Date.parse(to_date.replace(/(\d{2})\/(\d{2})\/(\d{4})/,
                '$2/$1/$3'));
                var date1 = new Date(date1Milliseconds);
                var date2 = new Date(date2Milliseconds);
                if (date1 > date2) {
                    toastr.warning('Please select greater than from date');
                    $(this).val(from_date);
                }

            })
        })
        function BtnProjectItem(r){
            var current_tr = $(r).closest('tr');
            var head_id = current_tr.find('.expense_head').val();
            var amount = current_tr.find('.amount').val();
            var qty = current_tr.find('.qty').val();
            if(!head_id){
                toastr.warning('Please select account head');
            }else if(amount == 0){
                toastr.warning('Please give expense amount');
            }else if(!qty){
                toastr.warning('Please give QTY');
            }else{
                $.ajax({
                    url: "{{ route('project-expense') }}",
                    type: "post",
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        head_id: head_id,
                        amount: amount,
                        qty: qty,
                    },
                    success: function(response) {
                        $('#project_expense_model_content').empty().append(response);
                        $('#project_expense_model').modal('show');
                    }
                });

            }
        }
    </script>
@endpush

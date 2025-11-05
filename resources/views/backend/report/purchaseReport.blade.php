@extends('layouts.backend.app')
@php
$company_name= \App\Setting::where('config_name', 'company_name')->first();
$company_address= \App\Setting::where('config_name', 'company_address')->first();
$company_tele= \App\Setting::where('config_name', 'company_tele')->first();
$company_email= \App\Setting::where('config_name', 'company_email')->first();
$currency= \App\Setting::where('config_name', 'currency')->first();

@endphp
@section('title', 'Sales Report')
@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    <style>
        td{
            text-align: right !important;
        }
        th{
            /* text-transform: uppercase; */
            font-size: 11px !important;
        }
    </style>
@endpush

@section('content')
@php

@endphp
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <!-- Widgets Statistics start -->
                <section id="widgets-Statistics">
                    <div class="row">
                        <div class="col-md-6">
                            <h4> Purchase Report: 
                                @if ($date)
                                    {{date('d/m/Y')}}
                                @endif
                                @if ($from && $to)
                                    {{'To'. date('d/m/Y').' From '. date('d-m-Y')}}
                                @endif
                                </h4>
                        </div>
                        <div class="col-md-2 text-right  col-left-padding">
                            <form action="#" method="GET">
                                {{-- @csrf --}}
                                <div class="row form-group  col-left-padding">
                                    <input type="text" class="form-control col-9 " name="date" placeholder="Select Date" id="date" required>
                                    <button class="bx bx-search col-3 btn-warning btn-block" type="submit"></button>
                                </div>
                            </form>

                        </div>
                        <div class="col-md-4  col-left-padding">
                            <form action="#" method="GET">
                                {{-- @csrf --}}
                                <div class="row form-group">
                                    <div class="col-5 col-right-padding">
                                        <input type="text" class="form-control" name="from" placeholder="From" id="from" required>

                                    </div>
                                    <div class="col-5  col-left-padding col-right-padding">
                                        <input type="text" class="form-control" name="to" placeholder="To" id="to" required>
                                    </div>
                                    <button class="bx bx-search col-2 btn-warning btn-block" type="submit"></button>
                                </div>
                            </form>
                        </div>
                        {{-- <div class="col-md-2">
                            <a href="{{ route('stockReportWithP') }}" class="btn btn-info">With Purchase</a>
                        </div> --}}
                    </div>



                    <div class="row pt-2 d-flex justify-content-end">
                        <div class="col-6">
                            {{-- <div style="width: 40%">
                                <select name="filter" id="filter" class="form-control">
                                    <option value="">Filter...</option>
                                    @foreach (App\PayMode::get() as $mode )
                                    <option value="{{ $mode->title }}">{{ $mode->title }}</option>

                                    @endforeach
                                </select>

                            </div> --}}
                        </div>
                        <div class="table-responsive pt-1">
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <th>Purchase No</th>
                                    <th>Date</th>
                                    <th>Payment Mode</th>
                                    <th>Taxable Sales Amount</th>
                                    <th>Vat Amount</th>
                                    <th>Total Amount</th>
                                </tr>
                                <tbody class="invoice-tbody">
                                    @php
                                        $grand_total_taxable=0;
                                        $grand_total_vat=0;
                                        $grand_total_amount=0;
                                    @endphp
                               @foreach($purchases as $inv)

                               <tr>
                                <td><a href="{{ route('purchaseView', $inv) }}">{{ $inv->purchase_no }}</a></td>
                               <td>{{ date('d/m/Y', strtotime($inv->date)) }}</td>
                               <td>{{ $inv->pay_mode }}</td>
                               <td>{{$txable=number_format((float)( App\PurchaseItem::where('purchase_id',$inv->id)->sum('price')), 2,'.','')    }}</td>
                                <td>{{$vat=number_format((float)(  App\PurchaseItem::where('purchase_id',$inv->id)->sum('vat')), 2,'.','')   }}</td>
                               <td>{{$total=number_format((float)(App\PurchaseItem::where('purchase_id',$inv->id)->sum('total_price')), 2,'.','')   }}</td>
                               </tr>
                               @php
                                        $grand_total_taxable=$grand_total_taxable+$txable;
                                        $grand_total_vat=$grand_total_vat+$vat;
                                        $grand_total_amount=$grand_total_amount+$total;
                                    @endphp
                                @endforeach
                                <tr>
                                    <td colspan="3" style="text-center">Grand Total</td>
                                    <td>{{ number_format((float)$grand_total_taxable,'2','.','')}}</td>
                                    <td>{{ number_format((float)$grand_total_vat,'2','.','')}}</td>
                                    <td>{{ number_format((float)$grand_total_amount,'2','.','')}}</td>

                                </tr>
                                </tbody>


                            </table>
                        </div>
                    </div>

                </section>
                <!-- Widgets Statistics End -->



            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    {{-- <script src="{{ asset('assets/backend/app-assets/vendors/js/jquery/jquery.min.js') }}"></script> --}}
    <script>
      $(document).ready(function() {
    $('#filter').change(function() {

        if ($(this).val() != '') {
            var date = $('#hidden_date').val();

            var from = $('#hidden_date_from').val();

            var to = $('#hidden_date_to').val();

            var value = $(this).val();

            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('filterInvoiceWiseSaleReport') }}",
                method: "POST",
                data: {
                    value: value,
                    date:date,
                    from:from,
                    to:to,
                    _token: _token,
                },
                success: function(response) {
                    $(".invoice-tbody").empty().append(response.page);
                }
            })
        }
    });

});
    </script>


@endpush

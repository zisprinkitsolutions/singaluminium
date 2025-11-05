@extends('layouts.pdf.app')
@php
$company_name= \App\Setting::where('config_name', 'company_name')->first();
$company_address= \App\Setting::where('config_name', 'company_address')->first();
$company_tele= \App\Setting::where('config_name', 'company_tele')->first();
$company_email= \App\Setting::where('config_name', 'company_email')->first();
@endphp
@push('css')
<style>
    p{
        color: black;
    }
</style>

@endpush
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <section id="widgets-Statistics">
                    <div class="row">

                        <div class="col-12 text-center pt-3">
                            <h1>SALES RETURN</h1>
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-4">
                                    <p><strong>Sale Return NO:</strong></p>
                                </div>
                                <div class="col-8 text-left">
                                    <p>{{ $invoice->sale_return_no }} </p>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-6 text-right">
                                    <p><strong>Date:</strong></p>
                                </div>
                                <div class="col-6">
                                    <p>{{ $invoice->date }} </p>
                                </div>
                            </div>

                        </div>

                    </div>




                    <div class="row">
                        <table id="customers">
                            <tr>
                                <th>ITEM NO</th>
                                <th>PRODUCT NAME</th>
                                <th>UNIT</th>
                                <th>UNIT PRICE</th>
                                <th>QUANTITY</th>
                                <th>TOTAL AMOUNT <small>(AED)</small></th>

                                {{-- <th>COST PRICE</th> --}}
                            </tr>

                            @foreach (App\SaleReturnItem::where('sale_return_no', $invoice->sale_return_no)->get() as $item)
                            {{-- {{ dd($item) }} --}}
                            <tr>
                                <td>{{ $item->barcode }}</td>
                                <td>{{ $item->item->item_name }}</td>
                                <td>{{ $item->unit }}</td>
                                <td>{{ $item->cost_price/$item->quantity }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{number_format((float)($item->cost_price),'2','.','') }}</td>

                            </tr>

                            @endforeach



                        </table>
                    </div>



                    <div class="row pt-5 mt-5">

                        <div class="col-6">
                            <div class="row">
                                <div class="col-12">
                                    <h4>RECEIVED BY</h4>
                                </div>

                                <div class="col-12 pt-5">
                                    <h4>SIGNATURE</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="row">
                                <div class="col-12 text-right">
                                    <h4>For {{ $company_name->config_value }}</h4>
                                </div>

                                <div class="col-12 pt-5 text-right">
                                    <h4>AUTHOROZED SIGNATORY</h4>
                                </div>
                            </div>
                        </div>

                    </div>


                </section>
            </div>
        </div>
    </div>
@endsection

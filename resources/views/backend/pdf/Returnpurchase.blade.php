@extends('layouts.pdf.app')
@php
$company_name= \App\Setting::where('config_name', 'company_name')->first();
$company_address= \App\Setting::where('config_name', 'company_address')->first();
$company_tele= \App\Setting::where('config_name', 'company_tele')->first();
$company_email= \App\Setting::where('config_name', 'company_email')->first();
@endphp
@push('css')
<style>
    p {
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
                        <h1>Purchase Return</h1>
                    </div>
                </div>

                <div class="row pt-4">
                    <div class="col-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 mb-1">
                                        <span><strong style="color: #000">CUSTOMER NAME :  </strong> {{ $invoice->supplier_id }}</span>

                                    </div>
                                   
                                    <div class="col-12">
                                        <p><strong>Purchase Return NO: </strong> {{ $invoice->purchase_return_no }}</p>
                                    </div>
                                    <div class="col-12">
                                        <p><strong>Purchase  NO: </strong> {{ $invoice->purchase_no }}</p>
                                    </div>
                                  
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <p> <strong>SHIP ADDRESS: </strong>  {{ $invoice->address == null? "NA":$invoice->address }}</p>
                                    </div>
                                
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <p> <strong>TRN: </strong> {{ $invoice->trn }} </p>
                                    </div>
                                 
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <p> <strong>CONTACT NO:  </strong> {{ $invoice->challan_number }} </p>
                                    </div>
                                  
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="col-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <p> <strong>PAYMODE: </strong> {{ $invoice->paymode }} </p>
                                    </div>
                                
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <p> <strong>DATE: </strong> {{ $invoice->date }}</p>
                                    </div>
                                
                                </div>
                            </div>

                        </div>
                    </div>

                </div>


                <div class="row">
                    <table class="table table-sm table-bordered" id="myTable">
                        <thead>
                            <tr>
                                
                                <th>Style ID</th>
                                <th scope="col">Item Name</th>
                             
                                <th scope="col">Qty</th>
                                <th scope="col">Vat Rate</th>

                                <th scope="col">Purchase Rate</th>
                                <th scope="col">Vat Amount</th>
                             
                               <th scope="col">Amount</th>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="all-data-area">
                            @php  
                            $total_vat = 0; 
                            $total_amount = 0; 

                            @endphp
                            @foreach ($items as $key => $item)
                            <tr>

                               
                                <td>{{$item->itemName->style_name}}</td>
                                <td>{{$item->itemName->item_name}}</td>
                    
                                <td>{{$item->return_qty}}</td>
                                <td>{{$item->vat_rate}}</td>
                                <td>{{$item->purchase_rate}}</td>
                                @php 
                                $total = $item->purchase_rate * $item->return_qty;
                                 $vat_amount = ($total * $item->vat_rate) / 100;
                                 $total_vat = $total_vat + $vat_amount; 
                                 $total_amount = $total + $total_amount;


                                @endphp

                                <td>{{$vat_amount}}</td>

                                <td>{{$total + $vat_amount}}</td>
                            </tr>
                            @endforeach
                            <tr class="border-top">
                                        <td colspan="5"  class="text-right">Amount (AED): </td>
                                        <td colspan="2">
                                            @php
                                                $amount = $total_amount;
                                                $AM = $amount + $total_vat;
                                            @endphp
                                                {{ number_format((float)$amount, 2, '.', '')}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right">VAT:</td>
                                        <td colspan="2">
                                            {{ number_format((float)$total_vat, 2, '.', '')}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right">Net Amount (AED):</td>
                                        <td colspan="2">
                                            {{ number_format((float)$AM, 2, '.', '')}}
                                        </td>
                                  </tr>

                        </tbody>



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
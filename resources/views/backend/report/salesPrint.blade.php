@extends('layouts.pdf.appInvoice')
@php
$company_name= \App\Setting::where('config_name', 'company_name')->first();
$company_address= \App\Setting::where('config_name', 'company_address')->first();
$company_tele= \App\Setting::where('config_name', 'company_tele')->first();
$company_email= \App\Setting::where('config_name', 'company_email')->first();
@endphp
@push('css')
<style>
    td{
        text-align: center !important;
    }

    th, td {
    border: 1px solid #000 !important;
    text-align: center !important;
}

.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #000;
}
p{
    color: black !important;
}

</style>
@endpush
@php
    $i=1;
@endphp
@section('content')
    <div class="container ">
        <div class="row">
            <div class="col-md-12">
                <section  id="widgets-Statistics" >
                    <div class="row">

                        <div class="col-12 text-center pt-3">
                            <h1>Sales Report</h1>
                            <span>{{ $searchDate }} {{ isset($searchDateto)? '-'.$searchDateto:"" }}</span>
                        </div>
                    </div>

                    <div class="row pt-2">
                        <table   class="table table-sm ">
                            <tr>

                                    <tr>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Payment Mode</th>
                                        <th>Taxable Sales Amount</th>
                                        <th>Vat Amount</th>
                                        <th>Total Amount</th>
                                    </tr>

                            </tr>
                            <tbody class="invoice-tbody">
                                @php
                                    $grand_total_taxable=0;
                                    $grand_total_vat=0;
                                    $grand_total_amount=0;
                                @endphp
                           @foreach($invoicess as $inv)

                           <tr>
                            <td>{{ $inv->invoice_no }}</td>
                           <td>{{ $inv->date }}</td>
                           <td>{{ $inv->pay_mode }}</td>
                           <td>{{$txable=number_format((float)( $inv->taxable_price), 2,'.','')    }}</td>
                                <td>{{$vat=number_format((float)(  $inv->vat_amount), 2,'.','')   }}</td>
                               <td>{{$total=number_format((float)($inv->price), 2,'.','')   }}</td>
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

                    {{-- <div class="row d-flex justify-content-end pt-4">
                        <div class="col-4">
                            <div class="row d-flex justify-content-end">
                                    <div class="col-9 text-right">
                                        <strong>TAXBLE Amount:</strong>
                                    </div>
                                    <div class="col-3">
                                        {{ $invoice->taxbleSup($invoice->invoice_no) }}
                                    </div>
                                    <div class="col-9 text-right">
                                        <strong>VAT Amount:</strong>
                                    </div>
                                    <div class="col-3">
                                        {{ $invoice->vat($invoice->invoice_no) }}
                                    </div>

                                    <div class="col-9 text-right">
                                        <strong>Total Amount:</strong>
                                    </div>
                                    <div class="col-3">
                                        {{ $invoice->grossTotal($invoice->invoice_no) }}
                                    </div>

                            </div>

                        </div>
                    </div> --}}

                    <div class="row pt-5 mt-5">

                        <div class="col-6">
                            <div class="row">
                                {{-- <div class="col-12">
                                    <h4>RECEIVED BY</h4>
                                </div> --}}

                                <div class="col-12 pt-5">
                                    <p>Supplier Signature</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="row">
                                {{-- <div class="col-12 text-right">
                                    <h4>For {{ $company_name->config_value }}</h4>
                                </div> --}}

                                <div class="col-12 pt-5 text-right">
                                    <p>Authorised Signature</p>
                                    <span>Name: {{ Auth::user()->name }}</span>
                                        <br>
                                    <span class="text-left">User ID: {{ Auth::id() }}</span>
                                </div>
                            </div>
                        </div>

                    </div>


                </section>
            </div>
        </div>
    </div>

@endsection

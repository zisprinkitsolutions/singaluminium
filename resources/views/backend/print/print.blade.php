@extends('backend.print.master')
@section('header')
    @include('backend.print.header')
@endsection
<style>
    .summernote p{
        line-height: 17px;
    }
    .authority-info th{
            color: black !important;
            font-weight: bolder !important;
            border: 1px solid #0867d2 !important;
            text-align: center !important;
        }
        .authority-info td{
            color: black !important;
            border: 1px solid #0867d2 !important;
            text-align: center !important;

        }
</style>
@php
        $company_name= \App\Setting::where('config_name', 'company_name')->first();

@endphp
@section('body')
<div class="row">
    <div class="col-sm-12">
        <h4 class=" text-center" style="margin:0;padding:0;line-height:29px;"> QUOTATION </h4>

        <div class="customer-info">
            <div class="row ml-1 mr-1 ">
                <div class="col-2 customer-static-content">
                    <strong>SUPPLIER/CONTRACTOR</strong> <br>
                    M/S: <br>
                    Address: <br>
                    Contact Person: <br>
                    Contact No: <br>
                    Email: <br>
                    TRN:
                </div>
                <div class="col-6 customer-dynamic-content">
                    <br>
                        {{ $lpo->party->pi_name ? $lpo->party->pi_name : '...' }} <br>
                        {{ $lpo->party->address ? $lpo->party->address : '...' }} <br>
                        {{ $lpo->party->con_person ? $lpo->party->con_person : '...'}}<br>
                        @if ($lpo->party->phone_no==$lpo->party->con_no && $lpo->party->con_no!='.' &&  $lpo->party->con_no!='')
                        {{$lpo->party->phone_no}} hh
                        @elseif($lpo->party->con_no && $lpo->party->phone_no && $lpo->party->con_no!='.' && $lpo->party->phone_no!='.')
                        {{$lpo->party->con_no.', '. $lpo->party->phone_no}}
                        @else
                        {{$lpo->party->con_no && $lpo->party->con_no!='.'? $lpo->party->con_no:($lpo->party->phone_no?$lpo->party->phone_no:'...')}}

                        @endif
                        <br>
                        {{ $lpo->party->con_no ? $lpo->party->email :'...'}} <br>
                        {{ $lpo->party->con_no ? $lpo->party->trn_no :'...'}} <br>

                </div>
                <div class="col-4 customer-dynamic-content text-right pt-1">
                    <span>
                        NO.: {{$lpo->lpo_bill_no}}<br>
                    </span>
                    <span>
                        Date: {{date('d/m/Y')}} <br>
                    </span>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row" style="padding: 15px;">
    <div class="col-sm-12">
        <table class="table table-sm table-bordered lpo-table authority-info">
            <tbody class="user-table-body">
                <tr class="border-top">
                    <th rowspan="2" style="border: 1px solid #0867d2 !important;">No </th>
                    <th rowspan="2" style="border: 1px solid #0867d2 !important;">Description </th>
                    <th rowspan="2" style="border: 1px solid #0867d2 !important;">Qty</th>
                    <th colspan="2" style="border: 1px solid #0867d2 !important;">Rate </th>
                    <th colspan="2" style="border: 1px solid #0867d2 !important;width:150px">Amount </th>
                </tr>
                <tr>
                    <td style="border: 1px solid #0867d2 !important;">Dhs </td>
                    <td style="border: 1px solid #0867d2 !important;">Fils </td>
                    <td style="border: 1px solid #0867d2 !important;">Dhs </td>
                    <td style="border: 1px solid #0867d2 !important;">Fils </td>
                </tr>
                  @foreach ($items as $key => $item)
                    @php
                        $rate_whole = floor($item->rate);
                        $rate_fraction = number_format($item->rate - $rate_whole, 2);
                        $total_whole = floor($item->amount);
                        $total_fraction = number_format($item->amount - $total_whole, 2);
                    @endphp
                  <tr>
                    <td style="border: 1px solid #0867d2 !important;">{{$key+1}}</td>
                    <td style="border: 1px solid #0867d2 !important;">{{$item->item_description}}</td>
                    <td style="border: 1px solid #0867d2 !important;">{{floatval($item->qty)}}</td>
                    <td style="border: 1px solid #0867d2 !important;">{{$rate_whole}}</td>
                    <td style="border: 1px solid #0867d2 !important;">{{$rate_fraction*100}}</td>
                    <td style="border: 1px solid #0867d2 !important;">{{$total_whole}}</td>
                    <td style="border: 1px solid #0867d2 !important;">{{$total_fraction*100}}</td>
                 </tr>

                  @endforeach
                  @php
                      $amount_whole = floor($lpo->amount);
                      $amount_fraction = number_format($lpo->amount - $amount_whole, 2);
                      $vat_whole = floor($lpo->vat);
                      $vat_fraction = number_format($lpo->vat - $vat_whole, 2);
                  @endphp
                  <tr>
                    <td style="border: 1px solid #0867d2 !important;  text-align:right !important" colspan="5" class="text-right pr-1">Total </td>
                    <td style="border: 1px solid #0867d2 !important;">{{$amount_whole}}</td>
                    <td style="border: 1px solid #0867d2 !important;">{{$amount_fraction*100}}</td>
                  </tr>
                  <tr>
                    <td style="border: 1px solid #0867d2 !important; text-align:right !important" colspan="5" class="text-right pr-1">VAT@5% </td>
                    <td style="border: 1px solid #0867d2 !important;">{{$vat_whole}}</td>
                    <td style="border: 1px solid #0867d2 !important;">{{$vat_fraction*100}}</td>
                  </tr>
                  <tr>
                    <td style="border: 1px solid #0867d2 !important;" colspan="5" class="text-center pr-1">
                        @php
                            $total_whole = floor($lpo->total_amount);
                            $total_fraction = number_format($lpo->total_amount - $total_whole, 2);
                            $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                            $amount_in_word = $f->format($total_whole);
                            $amount_in_word2 = $f->format($total_fraction);
                        @endphp
                        <div class="d-flex w-100">
                            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                                <span style="width:150px !important; color:#0867d2;font-size:15px;font-weight:bold; line-height:23px !important;">
                                    Total Dhs
                                </span>
                                <div class="w-100" style="border-bottom:1px dashed #0867d2;">
                                    <p style="margin:0 !important;padding:0!important;color:#0867d2;font-size:15px;font-weight:500 !important; padding-left:30px;text-transform: uppercase">
                                        {{ $amount_in_word }}
                                        @if ($total_fraction > 0)
                                            {{ '& ' . substr($amount_in_word2, 10) }}
                                        @endif {{ $currency->symbole }}
                                    </p>
                                </div>

                            </div>
                        </div>
                    </td>
                    {{-- <td style="border: 1px solid #0867d2 !important;" colspan="5" class="text-right pr-1">Total Dhs <span class="pl-2">المجموع درهم</span></td> --}}
                    <td style="border: 1px solid #0867d2 !important;">{{$total_whole}}</td>
                    <td style="border: 1px solid #0867d2 !important;">{{$total_fraction*100}}</td>
                  </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-12">
        <div class="row d-flex justify-content-center mt-4">
            <div class="col-md-12">
                <table class="table table-sm table-bordered authority-info">
                    <tr>
                        <th></th>
                        <th>Checked By</th>
                        <th>Prepared By</th>
                        <th>Approved By</th>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{$lpo->checked_by}}</td>
                        <td>{{$lpo->prepared_by}}</td>
                        <td>{{$lpo->approved_by}}</td>

                    </tr>
                    <tr>
                        <th>Signature</th>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                    <tr>
                        <th>Date</th>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                </table>

            </div>

            <div class="col-md-12">
                <h5 style="color:#0867d2;">Pay Terms:</h5>
                <p>{{$lpo->narration}}</p>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')
@include('backend.print.footer-with-address')
<div class="img receipt-bg invoice-view-wrapper footer-margin">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid" style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;" alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>
@endsection

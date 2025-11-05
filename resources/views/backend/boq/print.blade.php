@extends('layouts.print_app')

@push('css')
    <style>
        .data-table table,
        .data-table .table td,
        .data-table .table th{
            border-collapse: collapse;
            border: 1px solid #ddd;
            color: #313131;
            padding:3px 6px;
        }

        .left-side p,
        .right-side p{
            color: #313131;
            font-weight: 500;
        }
        .fa-bold{
            font-weight: bold;
        }
    </style>
@endpush
@section('content')
<div style="padding:10px 25px;">
    <div class="d-flex justify-content-between align-items-center" style="padding: 10px;">
        <div class="left-side">
            <p> <span class="fa-bold"> Company Name: </span> {{optional($boq->party)->pi_name}} </p>
            <p> <span class="fa-bold"> Address: </span>  {{optional($boq->project)->location}} </p>
            <p> <span class="fa-bold"> Mobile no: </span>  {{optional($boq->project)->mobile_no}} </p>
        </div>

        <div class="right-side text-right">
            <p> <span class="fa-bold"> Date : </span> {{date('d/m/Y', strtotime($boq->date))}} </p>
            <p> <span class="fa-bold"> BOQ No : </span> {{$boq->boq_no}} </p>
            <p> <span class="fa-bold"> Total Amount : </span> {{number_format($boq->total_amount,2)}} </p>
        </div>
    </div>
    <h4 class="text-center">Bill of Quantity</h4>
    <div class="data-table mt-2" style="padding: 10px;">
        <table class="table table-sm">
            <thead style="background: #ddd !important">
                <tr>
                    <th class="" style="color:#444;font-weight:600; width:3%">SL </th>
                    <th class="text-left" style="color:#444;font-weight:600 ;width: 40%"> Description  </th>
                    <th class="text-right" style="color:#444;font-weight:600;"> QTY </th>
                    <th class="text-right" style="color:#444;font-weight:600;"> SQM </th>
                    <th class="text-right" style="color:#444;font-weight:600;"> Rate </th>
                    <th class="text-right" style="color:#444;font-weight:600;"> Total </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($boq->items as $key => $task)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $task->item_description }}</td>
                        <td class="text-right">{{$task->qty}}</td>
                        <td class="text-right" style="padding: 5px 15px !important;"> {{number_format($task->sqm,2)}} </td>
                        <td class="text-right" style="padding: 5px 15px !important;"> {{number_format($task->rate,2)}} </td>
                        <td class="text-right" style="padding: 5px 15px !important;"> {{number_format($task->total,2)}} </td>
                    <tr>
                @endforeach


                <tr>
                    <td colspan="5" class="text-dark text-right" style="border-right:1px solid #ddd;font-size:14px;font-weight:500;">
                        Amount ({{ $currency->symbole }})
                    </td>
                    <td class="text-right text-dark" style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                        {{ number_format($boq->amount,2) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="text-dark text-right" style="border-right:1px solid #ddd;font-size:14px;font-weight:500;">
                        VAT ({{ $currency->symbole }})
                    </td>
                    <td class="text-right text-dark" style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                        {{ number_format($boq->vat,2) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="text-dark text-right" style="border-right:1px solid #ddd;font-size:14px;font-weight:500;">
                        Total Amount ({{ $currency->symbole }})
                    </td>
                    <td class="text-right text-dark" style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                        {{ number_format($boq->total_amount,2) }}
                    </td>
                </tr>
                <tr>
                    @php
                        $whole = floor($boq->total_amount);
                        $fraction = number_format($boq->total_amount  - $whole, 2);
                        $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                        $amount_in_word = $f->format($whole);
                        $amount_in_word2 = $f->format($fraction);
                    @endphp
                    <td colspan="6" class="text-center text-dark text-capitalize"
                        style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                        In Words: {{ $amount_in_word }} Dirhams
                        @if ($fraction > 0)
                            {{ '& ' . substr($amount_in_word2, 10) }}
                        @else
                        Zero
                        @endif Fils
                    </td>
                </tr>

                {{-- <tr>
                    <td colspan="8" class="text-center text-dark" style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                        Note: 5% VAT will be added to the total amount.(TRN) {{$trn_no}}
                    </td>
                </tr> --}}

            </tbody>
        </table>
    </div>
</div>
@endsection

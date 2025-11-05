@extends('layouts.print_app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <h4 class=" text-center" style="margin:0;padding:0;line-height:40px;color: #1d1d1d !important;"> <strong>{{$invoice->invoice_type}}</strong> </h4>
        <p class="text-center mb-2" style="color: #1d1d1d !important;">
            Invoice No: @if($invoice->invoice_type == 'Tax Invoice')
            {{$invoice->invoice_no}}
            @else
            {{$invoice->proforma_invoice_no}}
            @endif,
            Date: {{date('d/m/Y', strtotime($invoice->date))}},
            @if ($invoice->invoice_type!='Proforma Invoice')
            VAT TRN: {{'('.$trn_no.')'}}, @endif
            Running No:{{$running_no}}
        </p>
        <div class="customer-info">
            <div class="row ml-1 mr-1">
                <div class="d-flex col-md-12 col-12" style="background: #706f6f33 !important; border-top: 1px solid #1d1d1d !important;border-left: 1px solid #1d1d1d !important;border-right: 1px solid #1d1d1d !important;">
                    <div class="text-left" style="padding: 5px 0 !important;">
                        To, <br>
                    </div>
                </div>
                <div class="row col-12 mb-1 pt-1" style="border: 1px solid #1d1d1d !important; margin:0;">
                    <div class="col-8 p-0">
                        <div class="d-flex">
                            <div class="text-left" style="width:16%;">
                                M/S
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                                : {{$invoice->party->pi_name}}
                            </p>
    
                        </div>
                        <div class="d-flex">
                            <div class="text-left" style="width:16%;">
                                Address
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                                : {{$invoice->party->address}}
                            </p>
                        </div>
                    </div>
                    <div class="col-4 p-0">
                        <div class="d-flex">
                            <div class="text-left" style="width:30%;">
                                Attention
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                                : {{ $invoice->attention ? $invoice->attention : '.'}}
                            </p>

                        </div>
                        <div class="d-flex">
                            <div class="text-left" style="width:30%;">
                                Contact No
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                                : @if ($invoice->party->phone_no==$invoice->party->con_no && $invoice->party->con_no!='.' && $invoice->party->con_no!='')
                                {{$invoice->party->phone_no}}
                                @elseif($invoice->party->con_no && $invoice->party->phone_no && $invoice->party->con_no!='.' && $invoice->party->phone_no!='.')
                                {{$invoice->party->con_no.', '. $invoice->party->phone_no}}
                                @else
                                {{$invoice->party->con_no && $invoice->party->con_no!='.'? $invoice->party->con_no:($invoice->party->phone_no?$invoice->party->phone_no:'')}}

                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row col-12 mb-1 pt-1" style="border: 1px solid #1d1d1d !important; margin:0;">
                    <div class="col-8 p-0">
                        <div class="d-flex">
                            <div class="text-left" style="width:16%;">
                                Project Name
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                                : {{ $invoice->project_id?$invoice->new_project->name:$invoice->project->project_name }}
                            </p>
    
                        </div>
                        <div class="d-flex">
                            <div class="text-left" style="width:16%;">
                                Quotation No
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                                : {{ $invoice->project->quotation->project_code }}
                            </p>
                        </div>
                    </div>
                    <div class="col-4 p-0">
                        <div class="d-flex">
                            <div class="text-left" style="width:30%;">
                                D.O No
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                                : {{ $invoice->project ? $invoice->project->do_no : '' }}
                            </p>
                        </div>
                        <div class="d-flex">
                            <div class="text-left" style="width:30%;">
                                LPO NO
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                                : {{$invoice->project?$invoice->project->lpo_no:''}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="row">
        <div class="col-12 text-center">
            <h6 style="margin-top: 15px; margin-bottom: 0px; color: black;">
                {{$invoice->top_note}}
            </h6>
        </div>
    </div>
    <div class="row" style="padding: 15px;">
        <div class="col-sm-12">
            <table class="table-sm table-bordered border-botton  w-100" style="color: black; ">
                <thead style="background-color: #706f6f33 !important;color: black;">
                    <tr >
                        <th class="text-center" style=" text-transform: uppercase; color: black !important;width:50px;">Serial</th>
                        <th class="text-center" style=" text-transform: uppercase; color: black !important;"> Description</th>
                        <th class="text-center" style=" text-transform: uppercase; color: black !important;width:70px"> Unit</th>
                        <th class="text-center" style=" text-transform: uppercase; color: black !important;width:70px"> Qty</th>
                        <th class="text-center" style=" text-transform: uppercase; color: black !important;width:70px"> Rate</th>
                        @if($invoice->project->discount>0)
                        <th class="text-center" style=" text-transform: uppercase; color: black !important;width:70px"> Discount </th>
                        @endif

                        <th class="text-center"  style=" text-transform: uppercase; color: black !important;width:130px"> Amount <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                    </tr>
                </thead>
                @php
                    $cc = 0;
                @endphp
                <tbody class="user-table-body">
                    @if ($invoice->project->invoice_type == 'amount_base')
                        @foreach ($invoice->project->tasks as $key => $task)
                            <tr>
                                <td class="text-center" style="padding-right: 7px; vertical-align: top !important;">{{ ++$key }}</td>
                                <td class="text-left">{{ $task->task_name }} <br>
                                        <pre class="text-left border-0">{{$task->description}}</pre>
                                </td>

                                <td  class="text-center" style="vertical-align: top !important"> {{$task->unit}} </td>
                                <td  class="text-center" style="vertical-align: top !important"> {{ floatval($task->qty) }} </td>
                                <td class="text-center" style="vertical-align: top !important"> {{number_format($task->rate,2)}} </td>
                                @if($invoice->project->discount>0)
                                <td class="text-center" style="vertical-align: top !important"> {{number_format($task->discount,2)}} </td>
                                @endif
                                <td class="text-center" style="vertical-align: top !important">{{number_format($task->amount, 2)}}</td>
                            </tr>
                        @endforeach

                    @else
                        @foreach ($invoice->tasks as $key => $item)
                            <tr>
                                <td  class="text-center" style="vertical-align: top !important">{{ ++$key }}</td>
                                <td class="text-left">{{ $item->task_name }} <br>
                                        <pre class="text-left border-0">{{$item->description}}</pre>
                                </td>

                                <td  class="text-center" style="vertical-align: top !important"> {{ $item->unit }} </td>
                                <td  class="text-center" style="vertical-align: top !important"> {{ floatval($item->qty) }} </td>
                                <td class="text-center" style="vertical-align: top !important"> {{ number_format($item->rate,2) }} </td>
                                @if($invoice->project->discount>0)
                                <td class="text-center" style="vertical-align: top !important"> {{number_format($task->discount,2)}} </td>
                                @endif
                                <td class="text-center" style="vertical-align: top !important">{{ number_format($item->amount,2) }}</td>
                            </tr>
                        @endforeach
                    @endif


                    {{-- ////////////////////////// --}}
                    <tr>
                        <td colspan="{{$invoice->project->discount>0?3:2}}" rowspan="5" class="pl-1" style="font-size:16px">
                            @if ($invoice->with_note)
                            <p> Note: </p>
                                    @foreach ($notes as $key => $data)

                                        <p>
                                            {{$invoice->top_note}} Invoice No :  {{ $data->invoice_no?$data->invoice_no:$data->proforma_invoice_no }}
                                                AED {{ $data->total_budget }} /-
                                            (@foreach ($data->receipts as $receipt)
                                                {{ $receipt->payment->pay_mode == 'Cheque' ? $receipt->payment->cheque_no : $receipt->payment->pay_mode }}
                                                <{{ $receipt->Total_amount }}>
                                            @endforeach )
                                        </p>

                                @endforeach
                                <p>
                                    Total works of amount:
                                {{ number_format(($invoice->project->total_budget + ($standard_vat_rate / 100) * $invoice->project->total_budget),2,) }}
                                (Including VAT)
                                </p>
                                @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-left pr-1"> Total Amount</td>
                        <td style="text-align:center !important">{{number_format($invoice->project->total_budget,2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-left pr-1"> Invoiced Amount</td>
                        <td style="text-align:center !important">{{number_format($invoice->budget,2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-left pr-1"> VAT {{'@'.$standard_vat_rate}}% </td>
                        <td style="text-align:center !important">{{number_format($invoice->vat,2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-left pr-1" >Invoiced Total Amount <small> ({{$currency->symbole}}) </small> </td>
                        <td style="text-align:center !important">{{number_format($invoice->total_budget,2)}}</td>
                    </tr>
                    {{-- ////////////////// --}}


                    <tr>
                        @php

                            $whole = floor($invoice->total_budget);
                            $fraction = number_format($invoice->total_budget - $whole, 2);
                            $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                            $amount_in_word = $f->format($whole);
                            $amount_in_word2 = $f->format((int)($fraction*100));
                        @endphp
                        <td colspan="{{$invoice->project->discount>0?7:6}}" class="text-center pr-1 text-dark text-uppercase"
                            style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                            In Words: {{ $amount_in_word }} Dirhams
                            @if ($fraction > 0)
                                {{ '& ' . $amount_in_word2 }}
                            @else
                                No
                            @endif Fils
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
@endsection
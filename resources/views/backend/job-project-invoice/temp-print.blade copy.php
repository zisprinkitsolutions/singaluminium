@extends('layouts.print_app')
@section('content')
    <div class="row">
        <div class="col-sm-12">

            <div class="customer-info">
                <div class="row ml-1 mr-1"style="border: 2px solid #bdbdbd;">
                    <div class="col-12">
                        <div class="d-flex">
                            <div class="text-left" style="border-right:1px solid #999; width:16%;">
                                To, <br>
                            </div>
                        </div>


                        <div class="d-flex">
                            <div class="text-left" style="border-right:1px solid #999; width:16%;">
                                M/S:
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;width:84%;">
                                {{$tem_invoice->party->pi_name}}
                            </p>

                        </div>
                        <div class="d-flex">
                            <div class="text-left" style="border-right:1px solid #999; width:16%;">
                                Address:
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;width:84%;">
                                {{$tem_invoice->party->address}}
                            </p>
                        </div>
                        <div class="d-flex">
                            <div class="text-left" style="border-right:1px solid #999; width:16%;">
                                Attention:
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px; width:84%;">
                                {{ $tem_invoice->attention ? $tem_invoice->attention : '.'}}
                            </p>

                        </div>
                        <div class="d-flex">
                            <div class="text-left" style="border-right:1px solid #999; width:16%;">
                                Contact No:
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;width:84%;">
                                @if ($tem_invoice->party->phone_no==$tem_invoice->party->con_no && $tem_invoice->party->con_no!='.' && $tem_invoice->party->con_no!='')
                                {{$tem_invoice->party->phone_no}}
                                @elseif($tem_invoice->party->con_no && $tem_invoice->party->phone_no && $tem_invoice->party->con_no!='.' && $tem_invoice->party->phone_no!='.')
                                {{$tem_invoice->party->con_no.', '. $tem_invoice->party->phone_no}}
                                @else
                                {{$tem_invoice->party->con_no && $tem_invoice->party->con_no!='.'? $tem_invoice->party->con_no:($tem_invoice->party->phone_no?$tem_invoice->party->phone_no:'')}}

                                @endif
                            </p>

                        </div>
                        <div class="d-flex">
                            <div class="text-left" style="border-right:1px solid #999; width:16%;">
                                Customer TRN:
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px; width:84%">
                                {{$tem_invoice->party->trn_no}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <h5 class="ml-1 mr-1" style="background: #818181a1;  margin-top: 5px;margin-bottom:0px; color:black;"> {{ $tem_invoice->tax_invoice}}</h5>
                @if ($tem_invoice->tax_invoice!='Proforma Invoice')
                    <p style="color: #818181a1;margin-bottom:5px !important; color:black;">{{'('.$trn_no.')'}}</p>
                @endif
            </div>

            <div class="company-info">
                <div class="row ml-1 mr-1 " style="border: 2px solid #bdbdbd;">

                    <div class="col-8">

                        <div class="d-flex">
                            <div class="text-left" style="border-right:1px solid #999; width:24%; padding-top:10px;">
                                {{ $tem_invoice->tax_invoice}} 
                            </div>
                            <p style="padding-left: 15px; padding-top:10px; margin-bottom:0px; line-height:16px; width:76%">
                                @if($tem_invoice->tax_invoice == 'Tax Invoice')
                                <span class="text-danger">{{$tem_invoice->invoice_no}}</span> <br>
                                @else
                                <span class="text-danger">{{$tem_invoice->proforma_invoice_no}}</span> <br>
                                @endif
                            </p>
                        </div>

                        <div class="d-flex">
                            <div class="text-left" style="border-right:1px solid #999; width:24%;">
                                D.O No:
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px; width:76%">
                                {{ $tem_invoice->project ? $tem_invoice->project->do_no : '' }}
                            </p>
                        </div>

                        <div class="d-flex">
                            <div class="text-left" style="border-right:1px solid #999; width:24%;">
                                Quotation No:
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px; width:76%">
                                {{ $tem_invoice->project->quotation->project_code }}
                            </p>
                        </div>

                        <div class="d-flex">
                            <div class="text-left" style="border-right:1px solid #999; width:24%;">
                                Project Name:
                            </div>

                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px; width:76%">
                                {{ $tem_invoice->project_id?$tem_invoice->new_project->name:$tem_invoice->project->project_name }}
                            </p>
                        </div>
                    </div>

                    <div class="col-sm-4 customer-dynamic-content">
                        <span>
                            Date: {{date('d/m/Y',strtotime($tem_invoice->date))}} <br><br>
                        </span>
                        <span>
                            LPO NO:{{$tem_invoice->project?$tem_invoice->project->lpo_no:''}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center">
            <h6 style="margin-top: 15px; margin-bottom: 0px; color: red;">

                {{$tem_invoice->top_note}}

            </h6>
        </div>
    </div>
    <div class="row" style="padding: 15px;">
        <div class="col-sm-12">
            <table class="table-sm table-bordered border-botton  w-100" style="color: black;">
                <thead style="background-color: #706f6f33 !important;color: black;">
                    <tr >
                        <th class="text-center" style="color: black !important;width:50px;">S.no</th>
                        <th class="text-center" style="color: black !important;"> Description</th>
                        <th class="text-center" style="color: black !important;width:70px"> Unit</th>
                        <th class="text-center" style="color: black !important;width:70px"> Qty</th>
                        <th class="text-center" style="color: black !important;width:70px"> Rate</th>
                        @if ($tem_invoice->project->discount>0)
                        <th class="text-center" style="color: black !important;width:70px"> Discount</th>
                        @endif
                        <th class="text-center"  style="color: black !important;width:130px"> Amount <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                    </tr>
                </thead>
                @php
                    $cc=0;
                @endphp
                <tbody class="user-table-body">
                    @if ($tem_invoice->project->invoice_type == 'amount_base')
                    @foreach ($tem_invoice->project->tasks as $key => $task)

                        <tr>
                            <td class="text-center" style="padding-right: 7px; vertical-align: top !important;">{{ ++$key }}</td>
                            <td class="text-left">{{$task->task_name}} <br>
                                <pre class=" text-left border-0">{{$task->description}}</pre>
                            </td>

                            <td class="text-center" style="vertical-align: top !important"> {{$task->unit}} </td>
                            <td class="text-center" style="vertical-align: top !important"> {{floatval($task->qty)}} </td>
                            <td class="text-center" style="vertical-align: top !important"> {{number_format($task->rate,2)}} </td>
                            @if ($tem_invoice->project->discount>0)
                            <td class="text-center" style="vertical-align: top !important">{{number_format($task->discount, 2)}}</td>
                            @endif
                            <td class="text-center" style="vertical-align: top !important">{{number_format($task->amount, 2)}}</td>
                            {{-- @if($key == 1)
                            <td style=";text-align:right !important">{{$tem_invoice->due_amount }}</td>
                            @else
                            <td></td>
                            @endif --}}

                        </tr>

                        @endforeach

                    @else
                        @foreach ($tem_invoice->tasks as $key => $item)
                        <tr>
                            <td class="text-center">{{++$key}}</td>
                            <td class="text-left">{{$item->task_name }} <br>
                            <pre class="text-left border-0">{{$item->description}}</pre>
                            </td>
                            <td class="text-center"> {{$item->unit}} </td>
                            <td class="text-center"> {{floatval($item->qty)}} </td>
                            <td class="text-center" > {{number_format($item->rate,2)}} </td>
                            @if ($tem_invoice->project->discount>0)
                            <td class="text-center" style="vertical-align: top !important">{{number_format($task->discount, 2)}}</td>
                            @endif
                            <td class="text-center" style="vertical-align: top !important">{{number_format($task->amount, 2)}}</td>
                        </tr>
                        @endforeach
                    @endif

                    <tr>
                        <td colspan="{{$tem_invoice->project->discount>0 ? 3 : 2}}" rowspan="5">
                                @if ($tem_invoice->with_note)
                                <p>Note</p>
                                <p> Total works of amount:
                                {{ number_format($tem_invoice->project->total_budget + ($standard_vat_rate / 100) * $tem_invoice->project->total_budget,2) }}
                                (Including VAT)</p>

                                @php
                                $cc=0
                            @endphp
                            @foreach ($notes as $key => $data)
                            {{$data->top_note}} Invoice No : {{ $data->invoice_no?$data->invoice_no:$data->proforma_invoice_no }} AED {{ number_format($data->total_budget,2) }} /-
                            (@foreach ($data->receipts as $receipt)
                                        {{ $receipt->payment->pay_mode == 'Cheque' ? $receipt->payment->cheque_no : $receipt->payment->pay_mode }}
                                        <{{ $receipt->Total_amount }}>
                                    @endforeach )

                            @endforeach
                            <p> {{$tem_invoice->top_note}} Invoice No :
                        {{$tem_invoice->invoice_no ? $tem_invoice->invoice_no : $tem_invoice->proforma_invoice_no  }} AED {{ $tem_invoice->total_budget }} /- ()</p>

                                @endif



                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-left pr-1"> Total Amount</td>
                        <td style="text-align:center !important">{{number_format($tem_invoice->project->total_budget,2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-left pr-1"> Invoiced Amount</td>
                        <td style="text-align:center !important">{{number_format($tem_invoice->budget,2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-left pr-1"> VAT {{'@'.$standard_vat_rate}}% </td>
                        <td style="text-align:center !important">{{number_format($tem_invoice->vat,2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-left pr-1" >Invoiced Total Amount ({{$currency->symbole}}) </td>
                        <td style="text-align:center !important">{{number_format($tem_invoice->total_budget,2)}}</td>
                    </tr>


                    <tr>
                        @php

                            $whole = floor($tem_invoice->total_budget);
                            $fraction = number_format($tem_invoice->total_budget - $whole, 2);
                            $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                            $amount_in_word = $f->format($whole);
                            $amount_in_word2 = $f->format((int)($fraction*100));
                        @endphp
                        <td colspan="{{$tem_invoice->project->discount>0 ? 7 : 6}}" class="text-center pr-1 text-dark text-uppercase"
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
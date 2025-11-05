@extends('backend.print.master')
@section('header')
    @include('backend.print.header')
@endsection
<style>
    .summernote p{
        line-height: 17px;
    }
</style>

@php
    $company_name= \App\Setting::where('config_name', 'company_name')->first();
@endphp

@section('body')
<div class="row">
    <div class="col-sm-12">
        <h4 class=" text-center" style="margin:0;padding:0;line-height:40px;"> QUOTATION </h4>

        <div class="customer-info">
            <div class="row ml-1 mr-1 border-gray">
                <div class="col-8">
                    <div class="d-flex">
                        <div class="text-left" style="border-right:1px solid #999; width:25%;">
                            To, <br>
                        </div>
                    </div>


                    <div class="d-flex">
                        <div class="text-left" style="border-right:1px solid #999; width:25%;">
                            M/S:
                        </div>
                        <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                            {{ $lpo_project->party->pi_name ? $lpo_project->party->pi_name : '.' }}
                        </p>

                    </div>
                    <div class="d-flex">
                        <div class="text-left" style="border-right:1px solid #999; width:25%;">
                            Address:
                        </div>
                        <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                            {{ $lpo_project->party->address ? $lpo_project->party->address : '.' }}
                        </p>
                    </div>
                    <div class="d-flex">
                        <div class="text-left" style="border-right:1px solid #999; width:25%;">
                            Attention:
                        </div>
                        <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                            {{ $lpo_project->attention ? $lpo_project->attention : '.'}}
                        </p>

                    </div>
                    <div class="d-flex">
                        <div class="text-left" style="border-right:1px solid #999; width:25%;">
                            Contact No:
                        </div>
                        <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                            {{ $lpo_project->mobile_no ? $lpo_project->mobile_no : '.'}}
                        </p>

                    </div>
                    <div class="d-flex">
                        <div class="text-left" style="border-right:1px solid #999; width:25%;">
                            Subject:
                        </div>
                        <p style="padding-left: 15px; margin-bottom:0px; line-height:16px; width:75%">
                            {{ $lpo_project->project_description ? $lpo_project->project_description  : '.' }}
                        </p>
                    </div>
                    <div class="d-flex">
                        <div class="text-left" style="border-right:1px solid #999; min-width:25% !important;">
                            Project Name:
                        </div>
                        <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                            {{ $lpo_project->project_id?$lpo_project->new_project->name:$lpo_project->project_name }}
                        </p>
                    </div>
                </div>

                <div class="col-4 customer-dynamic-content text-right pt-1">
                    <span>
                        Quotation NO: {{$lpo_project->project_code}}<br>
                    </span>
                    <span>
                        Date: {{date('d/m/Y')}} <br>
                    </span>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="px-1 mt-1">
    <p style="font-size:15px; line-hight:0 !important;font-weight:500;color:black;margin:5px 20px 0 0;"> <span
            style="font-weight:400"> Dear sir : </span> </p>
    <p style="font-size:15px; line-hight:0 !important;font-weight:500;color:black;margin:5px 20px 0 0;">
        As per our discussion with you, we are submitting the following details and
        price for Below Mentioned works for your Kind Approval.
    </p>
</div>
<div class="row" style="padding: 15px;">
    <div class="col-sm-12">
        <table class="table table-sm table-bordered border-botton proview-table" style="color: black; ">
            <thead style="background: #E6BC99 !important;color: black;">
                <tr>
                    <th class="" style="color:#444;font-weight:600; width:3%"> S.no </th>
                    <th class="text-center" style="color:#444;font-weight:600 ;width: 60%"> Description of work </th>
                    <th class="text-center" style="color:#444;font-weight:600;"> Unit </th>
                    <th class="text-center" style="color:#444;font-weight:600;"> Qty </th>
                    <th class="text-center" style="color:#444;font-weight:600;"> Rate ({{ $currency->symbole }}) </th>
                    <th class="text-center" style="color:#444;font-weight:600;"> Amount ({{ $currency->symbole }}) </th>
                </tr>
            </thead>
            @php
                $cc = 0;
            @endphp
            <tbody class="user-table-body">
                @foreach ($lpo_project->tasks as $key => $task)
                <tr class="text-center">
                    <td class="text-right">{{ $key + 1 }}</td>
                    <td class="text-left">
                        {{ $task->task_name }} <br>
<pre class="text-left border-0">
{{$task->description}}
</pre>
                    </td>
                    <td class="text-center">
                        {{ $task->unit }}
                    </td>
                    <td class="text-center">
                        {{ floatval($task->qty)}}
                    </td>
                    <td class="text-center">
                        {{ number_format($task->rate,2) }}
                    </td>
                    <td class="text-center">
                        {{ number_format($task->amount + $task->discount,2)}}
                    </td>
                </tr>
            @endforeach
            @if($lpo_project->discount)
            <tr>
                <td colspan="5" class=" text-right"
                    >
                    Total
                </td>
                <td class="text-center "
                    >
                    {{ number_format($lpo_project->budget,2) }}
                </td>
            </tr>
            @if($lpo_project->discount >0)
            <tr>
                <td colspan="5" class=" text-right"
                    >
                    Discount
                </td>
                <td class="text-center "
                    >
                    {{ number_format($lpo_project->discount,2) }}
                </td>
            </tr>
            @endif
            @endif
            <tr>
                <td colspan="5" class=" text-right"
                    >
                    Total Amount ({{ $currency->symbole }})
                </td>
                <td class="text-center "
                    >
                    {{ number_format($lpo_project->total_budget,2) }}
                </td>
            </tr>

            <tr>
                @php

                    $whole = floor($lpo_project->total_budget);
                    $fraction = number_format($lpo_project->total_budget - $whole, 2);
                    $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                    $amount_in_word = $f->format($whole);
                    $amount_in_word2 = $f->format($fraction*100);
                @endphp
                <td colspan="7" class="text-center  text-capitalize"
                    >
                    In Words: {{ $amount_in_word }} Dirhams
                    @if ($fraction > 0)
                        {{ '& ' . $amount_in_word2 }}
                    @else
                    No
                    @endif Fils
                </td>
            </tr>

            <tr>
                <td colspan="7" class="text-center "
                    >
                    Note: 5% VAT will be added to the total amount.(TRN) {{$trn_no}}
                </td>
            </tr>

            </tbody>
        </table>
    </div>
    <div class="col-md-12">
        <div class="invoice-view-wrapper px-1 mb-1">
            <div class="summernote mb-1">
                 {!!$lpo_project->project_term!!}

            </div>
            <!--<p style="margin-bottom: 2px !important;font-size:16px; margin-bottom:5px "><Strong>Working Period</Strong></p>-->
            <!--<p style="font-size:16px; ">As per the Discussion</p>-->
            <!--<p style="font-size:16px; ">We hope that our quotation will meet your kind approval and waiting for your valuable LPO</p>-->


            <p style="font-size:15px; line-hight:0 !important;font-weight:500;color:#000;margin:5px 20px 0 0;"> <span
                    style="font-weight:400"> {{ $company_name->config_value}} </span> </p>
            <p style="font-size:16px; line-hight:0 !important;font-weight:500;color:#000;margin:5px 20px 0 0;"> Jani Barua (MD)
            </p>
            <p style="font-size:16px; line-hight:0 !important;font-weight:500;color:#000;margin:5px 20px 0 0;"> 050 628 7964
            </p>
            <br>
            <p style="font-size:16px; line-hight:0 !important;font-weight:500;color:#000;margin:5px 20px 0 0;"> Office:</p>
            <p style="font-size:16px; line-hight:0 !important;font-weight:500;color:#000;margin:5px 20px 0 0;">
                0565831841 </p>

        </div>
    </div>
</div>
@endsection
@section('footer')
@include('backend.print.footer-with-address')

@endsection

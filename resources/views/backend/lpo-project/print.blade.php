
@extends('layouts.print_app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <h4 class=" text-center" style="margin:0;padding:0;line-height:40px;color: #1d1d1d !important;"> <strong>QUOTATION</strong> </h4>
        <p class="text-center mb-2" style="color: #1d1d1d !important;"> Quotation No: {{$lpo_project->project_code}}, Date: {{date('d/m/Y')}}</p>
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
                                : {{ $lpo_project->party->pi_name ? $lpo_project->party->pi_name : '.' }}
                            </p>

                        </div>
                        <div class="d-flex">
                            <div class="text-left" style="width:16%;">
                                Address
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                                : {{ $lpo_project->party->address ? $lpo_project->party->address : '.' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-4 p-0">
                        <div class="d-flex">
                            <div class="text-left" style="width:30%;">
                                Attention
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                                : {{ $lpo_project->attention ? $lpo_project->attention : '.'}}
                            </p>

                        </div>
                        <div class="d-flex">
                            <div class="text-left" style="width:30%;">
                                Contact No
                            </div>
                            <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                                : {{ $lpo_project->mobile_no ? $lpo_project->mobile_no : '.'}}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-12 customer-dynamic-content text-right pt-1" style="border: 1px solid #1d1d1d !important">
                    <div class="d-flex">
                        <div class="text-left" style="width:11%;">
                            Project Name
                        </div>
                        <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                            :  {{ optional(optional($lpo_project->boq)->project)->name ?? 'N/A'  }}
                        </p>
                    </div>
                    <div class="d-flex">
                        <div class="text-left" style="width:11%;">
                            Subject
                        </div>
                        <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                            : {{ $lpo_project->project_description ? $lpo_project->project_description  : '.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="px-1 mt-1">
    <p style="font-size:15px; line-hight:0 !important;font-weight:500;color:black;margin:5px 20px 0 0;"> <span style="font-weight:400"> Dear Sir : </span> </p>
    <p style="font-size:15px; line-hight:0 !important;font-weight:500;color:black;margin:5px 20px 0 0;">
        As per our discussion with you, we are submitting the following details and
        price for Below Mentioned works for your Kind Approval.
    </p>
</div>
<div class="row" style="padding: 15px;">
    <div class="col-sm-12">
        <table class="table table-sm table-bordered border-botton proview-table" style="color: black; ">
            <thead style="background: #706f6f33 !important;color: black;">
                <tr class="">
                    <th class="" style="color:#000; text-transform: uppercase; background: #706f6f33 !important; width:3%"> Serial </th>
                    <th class="text-left" style="color:#000; text-transform: uppercase; background: #706f6f33 !important; width: 20%"> Description </th>
                    <th class="text-right" style="color:#000; text-transform: uppercase; background: #706f6f33 !important;"> QTY </th>
                    <th class="text-right" style="color:#000; text-transform: uppercase; background: #706f6f33 !important;"> SQM </th>
                    <th class="text-right" style="color:#000; text-transform: uppercase; background: #706f6f33 !important;"> Rate ({{ $currency->symbole }}) </th>
                    <th class="text-right" style="color:#000; text-transform: uppercase; background: #706f6f33 !important; width: 200px;"> ToAmount ({{ $currency->symbole }}) </th>
                </tr>
            </thead>
            @php
                $cc = 0;
            @endphp
            <tbody class="user-table-body">
                @foreach ($lpo_project->items as $key => $task)
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
                    <td colspan="5" class=" text-right"> Amount ({{ $currency->symbole }})</td>
                    <td class="text-right">{{ number_format($lpo_project->budget,2) }}</td>
                </tr>
                <tr>
                    <td colspan="5" class=" text-right"> VAT 5% ({{ $currency->symbole }})</td>
                    <td class="text-right">{{ number_format($lpo_project->vat,2) }}</td>
                </tr>
                <tr>
                    <td colspan="5" class=" text-right"> Total Amount ({{ $currency->symbole }})</td>
                    <td class="text-right">{{ number_format($lpo_project->total_budget,2) }}</td>
                </tr>

                <tr>
                    @php
                        $whole = floor($lpo_project->total_budget);
                        $fraction = number_format($lpo_project->total_budget - $whole, 2);
                        $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                        $amount_in_word = $f->format($whole);
                        $amount_in_word2 = $f->format($fraction*100);
                    @endphp
                    <td colspan="6" class="text-center  text-capitalize">
                        In Words: {{ $amount_in_word }} Dirhams
                        @if ($fraction > 0)
                            {{ '& ' . $amount_in_word2 }}
                        @else
                        Zero
                        @endif Fils
                    </td>
                </tr>

                {{-- <tr>
                    <td colspan="7" class="text-center ">
                        Note: 5% VAT will be added to the total amount.(TRN) {{$trn_no}}
                    </td>
                </tr> --}}

            </tbody>
        </table>
    </div>
    <div class="col-md-12">
        <div class="invoice-view-wrapper px-1 mb-1">
            <div class="summernote mb-1" style="color: #1d1d1d !important;">
                {!!$lpo_project->project_term!!}
            </div>
        </div>
    </div>
    <div class="mt-5 pl-2">
        <div class="divFooter text-center " style="color: #1d1d1d !important;">
            {{$company_name}}
        </div>
    </div>
</div>
@endsection

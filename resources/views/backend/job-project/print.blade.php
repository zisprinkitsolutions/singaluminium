@extends('layouts.print_app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <h4 class="text-dark text-center" style="margin:0;padding:0;line-height:40px">RECEIVED WORK ORDER</h4>

        <div class="customer-info">
            <div class="row ml-1 mr-1 border-gray" style="border:1px solid black">
                <div class="col-8">
                    <div class="d-flex">
                        <div class="text-left text-dark" style="width:25%;">
                            To, <br>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="text-lef text-dark" style="width:25%;">
                            M/S
                        </div>
                        <p style="padding-left: 15px; margin-bottom:0px; line-height:16px; color:black;">
                            : {{ $project->party->pi_name ? $project->party->pi_name : '.' }}
                        </p>
                    </div>
                    <div class="d-flex">
                        <div class="text-left text-dark" style="width:25%;">
                            Address
                        </div>
                        <p style="padding-left: 15px; margin-bottom:0px; line-height:16px; color:black;">
                            : {{ $project->party->address ? $project->party->address : '.' }}
                        </p>
                    </div>
                    <div class="d-flex">
                        <div class="text-left text-dark" style="width:25%;">
                            Contact No
                        </div>
                        <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                            : {{ $project->mobile_no ? $project->mobile_no : '.'}}
                        </p>

                    </div>
                    <div class="d-flex">
                        <div class="text-left text-dark" style="min-width:25% !important; color:black;">
                            Project Name
                        </div>
                        <p style="padding-left: 15px; margin-bottom:0px; line-height:16px; color:black;">
                            : {{ $project->project_id?$project->new_project->name:$project->project_name}}
                        </p>
                    </div>
                </div>

                <div class="col-4 customer-dynamic-content text-right">
                    <span class="text-dark">
                        Date: {{date('d/m/Y', strtotime($project->date))}} <br>
                    </span>
                    <span class="text-dark">
                        Total Amount: {{number_format($project->total_budget,2)}} <br>
                    </span>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="p-1">
    <p style="font-size:15px; line-hight:0 !important;font-weight:500;color:black;margin:5px 20px 0 0;"> <span
            style="font-weight:400"> Dear Sir : </span> </p>
    <p style="font-size:15px; line-hight:0 !important;font-weight:500;color:black;margin:5px 20px 0 0;">
        As per our discussion with you, we are submitting the following details and
        price for Below Mentioned works for your Kind Approval.
    </p>
</div>
<div class="row" style="padding: 15px;">
    <div class="col-sm-12">
        <table class="table table-sm table-bordered border-botton proview-table" style="color: black; ">
            <thead style="color: black;">
                <tr>
                    <th class="" style="text-transform: uppercase; background: #706f6f33; color:#444;font-weight:600; width:3%">SL </th>
                    <th class="text-left" style="text-transform: uppercase; background: #706f6f33; color:#444;font-weight:600 ;width: 40%">Description </th>
                    <th class="text-right" style="text-transform: uppercase; background: #706f6f33; color:#444;font-weight:600;"> Qty </th>
                    <th class="text-right" style="text-transform: uppercase; background: #706f6f33; color:#444;font-weight:600;"> SQM </th>
                    <th class="text-right" style="text-transform: uppercase; background: #706f6f33; color:#444;font-weight:600;"> Rate </th>
                    <th class="text-right" style="text-transform: uppercase; background: #706f6f33; color:#444;font-weight:600;"> Amount  </th>
                </tr>
            </thead>
            @php
                $cc = 0;
            @endphp
            <tbody class="user-table-body">
                @foreach ($project->tasks as $key => $task)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $task->task_name }}</td>
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
                        {{ number_format($project->budget,2) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="text-dark text-right" style="border-right:1px solid #ddd;font-size:14px;font-weight:500;">
                        VAT ({{ $currency->symbole }})
                    </td>
                    <td class="text-right text-dark" style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                        {{ number_format($project->vat,2) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="text-dark text-right" style="border-right:1px solid #ddd;font-size:14px;font-weight:500;">
                        Total Amount ({{ $currency->symbole }})
                    </td>
                    <td class="text-right text-dark" style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                        {{ number_format($project->total_budget,2) }}
                    </td>
                </tr>
                <tr>
                    @php
                        $whole = floor($project->total_budget);
                        $fraction = number_format($project->total_budget  - $whole, 2);
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
            </tbody>
        </table>
    </div>
    <div class="col-md-12">
        <div class="invoice-view-wrapper px-1 mb-1">
            <div class="summernote" style="color: black;">
                {!!$project->project_term!!}

            </div>
        </div>
    </div>
</div>
@endsection

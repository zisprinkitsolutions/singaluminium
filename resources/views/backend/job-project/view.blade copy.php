<style>
    .row{
        display: flex;
    }
    .col-md-1{
        max-width: 8.33% !important;
    }
    .col-md-2{
        max-width: 16.66% !important;
    }
    .col-md-3{
        max-width: 25% !important;
    }
    .col-md-8{
        max-width: 66.66% !important;
    }
    .col-md-10{
        max-width: 83.33% !important;
    }
    .col-md-11{
        max-width: 91.66% !important;
    }
    .customer-static-content{
        background: #ada8a81c;
    }
    .customer-dynamic-content{
        background: #cdc3c317;
    }
    .customer-content{
        border: 1px solid black !important;
    }
    .proview-table tr td, .proview-table tr th{
        border: 1px solid black !important;
    }
    .customer-dynamic-content2{
        background: #fff !important;
    }
    @media print and (color) {
        .proview-table {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .divFoote {
            display: block !important;
        }
    }
    @media print{
        #widgets-Statistics{
            padding: 2px !important;
        }
        .row{
            display: flex;
        }

        .customer-dynamic-content2{
            background: #fff !important;
        }
        .proview-table tr td, table tr th{
            border: 1px solid black !important;
        }
        .customer-content{
            border: 1px solid black !important;
        }
    }
    .print-table .table tr th,
    .print-table .table tbody tr td {
        vertical-align: top !important;
        text-align: left;
        padding: 5px 15px;
        color: #000 !important;
        border-bottom: 1px solid black !important;
        border-left: 1px solid black !important;
        border-right: 1px solid black !important;
        border-collapse: separate;
        border-top: 1px solid black !important;
        font-size: 13px !important;
    }

    .table-content {
        margin: 0;
        padding: 0;
        line-height: 27px;
    }

    .summernote,.summernote p{
        line-height: 23px !important;
        margin: 0;
    }

    @media print {
        body {
            margin: 100px;
            padding: 0px !important;
            background-color: #fff !important;
            color: #000 !important;
            font-size: 13px !important;
        }

        p {
            color: #000 !important;
            font-weight: 400 !important;
            font-size: 13px !important;
        }

        .data-table .table {
            padding-right: 10px;
            padding-left: 10px;
            border-collapse: separate;
            border-right: 1px solid black;
            border-top: 1px solid black;
            font-size: 13px !important;
        }

        .data-table .table tr th,
        .data-table .table tbody tr td {
            vertical-align: top !important;
            text-align: left;
            padding: 5px 15px;
            color: #000 !important;
            border-bottom: 1px solid black !important;
            border-left: 1px solid black !important;
            border-right: 1px solid black !important;
            border-collapse: separate;
            border-top: 1px solid black !important;
            font-size: 13px !important;
        }

        .print-hide {
            display: none !important;
        }

        label,
        table th {
            font-weight: 400 !important;
        }

    }

</style>
<div class="receipt-voucher-hearder invoice-view-wrapper" style="margin: 50px 20px; border-radius: 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>
@php
    $trn_no = \App\Setting::where('config_name', 'trn_no')->first()->config_value;
    $company_name= \App\Setting::where('config_name', 'company_name')->first()->config_value;
@endphp
<section id="widgets-Statistics " >
    <h4 class="text-dark text-center" style="margin:0;padding:0;line-height:29px;"> Work Order </h4>
    <div class="row">
        <div class="col-md-12">
            <div class="customer-info">
                <div class="row ml-1 mr-1" style="border:1px solid #bdbdbd">
                    <div class="col-md-2 customer-static-content" style="padding-left:3px !important">
                        TO, <br>
                        M/S: <br>
                        Address: <br>
                        Attention: <br>
                        Contact No: <br>
                        Subject: <br>
                        Site/Delivey: <br>
                    </div>

                    <div class="col-md-6 customer-dynamic-content">
                        <br>
                        {{ $project->party->pi_name ? $project->party->pi_name : '...' }} <br>
                        {{ $project->party->address ? $project->party->address : '...' }} <br>
                        {{ $project->party->con_person ? $project->party->con_person : '...'}}<br>
                        {{ $project->party->con_no ? $project->party->con_no :'...'}} <br>
                        {{ $project->project_name }} <br>
                        {{ $project->site_delivery }} <br>

                    </div>
                    <div class="col-md-4 customer-dynamic-content text-right pt-1">
                        <span>
                            L.P.O NO: {{$project->project_code}}<br>
                        </span>
                        <span>
                            Date: {{date('d/m/Y')}} <br>
                        </span>

                    </div>
                </div>
            </div>
            <div class="px-1">
                <p style="font-size:15px; line-hight:0 !important;font-weight:500;color:#444;margin:5px 20px 0 0;"> <span
                        style="font-weight:400"> Dear sir : </span> </p>
                <p style="font-size:15px; line-hight:0 !important;font-weight:400;color:#444;margin:5px 20px 0 0;">
                    As per our discussion with you, we are submitting the following details and
                    price for Below Mentioned works for your Kind Approval.
                </p>
            </div>
            <div class="my-1 px-1 print-table table-responsive">
                <table class="table table-sm">
                    <thead style="background: #ddd !important">
                        <tr>
                            <th class="" style="color:#444;font-weight:600; width:3%"> S.no </th>
                            <th class="text-center" style="color:#444;font-weight:600 ;width: 60%"> Description of work </th>
                            <th class="text-center" style="color:#444;font-weight:600;"> Unit </th>
                            <th class="text-center" style="color:#444;font-weight:600;"> Qty </th>
                            <th class="text-center" style="color:#444;font-weight:600;"> Rate ({{ $currency->symbole }}) </th>
                            <th class="text-center" style="color:#444;font-weight:600;"> Amount ({{ $currency->symbole }}) </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($project->tasks as $key => $task)
                            <tr>
                                <td class="text-right">{{ $key + 1 }}</td>
                                <td>
                                    {{ $task->task_name }} <br>
<pre class="text-left border-0">
{{$task->description}}
</pre>
                                </td>
                                <td class="text-center">
                                    {{ $task->unit }}
                                </td>
                                <td class="text-center">
                                    {{ $task->qty }}
                                </td>
                                <td class="text-center">
                                    {{ $task->rate }}
                                </td>
                                <td class="text-center">
                                    {{ $task->amount + $task->discount }}
                                </td>
                            </tr>
                        @endforeach
                        @if($project->discount || $project->advance_amount)
                        <tr>
                            <td colspan="5" class="text-dark text-right"
                                style="border-right:1px solid #ddd;font-size:14px;font-weight:500;">
                                Total
                            </td>
                            <td class="text-center text-dark"
                                style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                                {{ $project->budget }}
                            </td>
                        </tr>
                        @endif
                        @if($project->discount)
                        <tr>
                            <td colspan="5" class="text-dark text-right"
                                style="border-right:1px solid #ddd;font-size:14px;font-weight:500;">
                                Discount
                            </td>
                            <td class="text-center text-dark"
                                style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                                {{ $project->discount }}
                            </td>
                        </tr>
                        @endif

                        @if($project->advance_amount)
                        <tr>
                            <td colspan="5" class="text-dark text-right"
                                style="border-right:1px solid #ddd;font-size:14px;font-weight:500;">
                                Advance
                            </td>
                            <td class="text-center text-dark"
                                style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                                {{ $project->advance_amount }}
                            </td>
                        </tr>
                        @endif

                        <tr>
                            <td colspan="5" class="text-dark text-right"
                                style="border-right:1px solid #ddd;font-size:14px;font-weight:500;">
                                Total Amount ({{ $currency->symbole }})
                            </td>
                            <td class="text-center text-dark"
                                style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                                {{ $project->total_budget-$project->advance_amount }}
                            </td>
                        </tr>

                        <tr>
                            @php

                                $whole = floor($project->total_budget-$project->advance_amount);
                                $fraction = number_format($project->total_budget-$project->advance_amount  - $whole, 2);
                                $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                $amount_in_word = $f->format($whole);
                                $amount_in_word2 = $f->format($fraction);
                            @endphp
                            <td colspan="7" class="text-center text-dark text-capitalize"
                                style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                                In Words: {{ $amount_in_word }} Dirhams
                                @if ($fraction > 0)
                                    {{ '& ' . substr($amount_in_word2, 10) }}
                                @else
                                No
                                @endif Fils
                            </td>
                        </tr>

                        <tr>
                            <td colspan="7" class="text-center text-dark"
                                style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                                Note: 5% vat will be added to the total amount.(TRN) {{$trn_no}}
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <div class="invoice-view-wrapper px-1">
                <div class="summernote">
                     {!!$project->project_term!!}

                </div>


                <p style="font-size:13px; line-hight:0 !important;font-weight:500;color:#444;margin:5px 20px 0 0;"> <span
                        style="font-weight:400"> BIN HINDI FABRICATION METAL AND IRON WORKS LLC </span> </p>
                <p style="font-size:13px; line-hight:0 !important;font-weight:400;color:#444;margin:0px 20px 0 0;"> Jani Barua (MD)
                </p>
                <p style="font-size:13px; line-hight:0 !important;font-weight:400;color:#444;margin:0px 20px 0 0;"> 050 628 7964
                </p>
                <p style="font-size:13px; line-hight:0 !important;font-weight:400;color:#444;margin:5px 20px 0 0;"> Office:</p>
                <p style="font-size:13px; line-hight:0 !important;font-weight:400;color:#444;margin:0px 20px 0 0;"> Utpal Barua:
                    056 583 1841 </p>

            </div>

            <div class="divFoote mb-1 ml-1 d-none ">
                <p class="text-center" style="text-align: center !important">
                    تليفون : ٠٦٧٤٨۰۲۲۳، ص.ب : ۸۲۱٦، منطقة الصناعية الجديدة، عجمان - ا.ع.م <br>
                    Tel: 06 7480223, P.O. Box: 8216, New Industrial Area, Ajman - U.A.E. <br> Email:
                    binhindifabrication@yahoo.com
                </p>

            </div>

        </div>
    </div>
</section>
<div class="divFooter mb-1 ml-1 invoice-view-wrapper student_profle-print footer-margin">
    Business Software Solutions by
    <span style="color: #0005" class="spanStyle"><img class="img-fluid"
            src="{{ asset('img/zikash-logo.png')}}" alt="" width="70"></span>
</div>
<div class="img receipt-bg invoice-view-wrapper">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid" style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;" alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>




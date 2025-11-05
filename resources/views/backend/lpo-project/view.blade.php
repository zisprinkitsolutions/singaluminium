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
            margin: 0px;
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
            padding: 5px 15px !important;
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
    pre {
            white-space: pre-wrap;
        }
</style>
<div class="receipt-voucher-hearder invoice-view-wrapper" style="margin: 50px 20px; border-radius: 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>

@php
    $trn_no = \App\Setting::where('config_name', 'trn_no')->first()->config_value;
    $company_name= \App\Setting::where('config_name', 'company_name')->first()->config_value;
@endphp
<section id="widgets-Statistics">

    <div class="row">
        <div class="col-md-12">
            <div class="customer-info">
                <div class="row ml-1 mr-1 " style="border:1px solid #bdbdbd">
                    <div class="col-md-2 customer-static-content" style="padding-left: 3px !important">
                        M/S: <br>
                        Address: <br>
                        Project Name: <br>
                        Subject: <br>
                    </div>

                    <div class="col-md-6 customer-dynamic-content">
                        <strong>{{ $lpo_project->party->pi_name ? $lpo_project->party->pi_name : '.' }}</strong> <br>
                        {{ $lpo_project->party->address ? $lpo_project->party->address : '.' }} <br>
                        {{ optional(optional($lpo_project->boq)->project)->name ?? 'N/A'  }} <br>
                        {{ $lpo_project->project_description }} <br>
                    </div>
                    <div class="col-md-4 customer-dynamic-content text-right">
                        <span>
                            Site Location: {{ $lpo_project->site_delivery ? $lpo_project->site_delivery : '.'}}<br>
                        </span>
                        <span>
                            Contact No: {{ $lpo_project->mobile_no ? $lpo_project->mobile_no : '.'}}<br>
                        </span>
                        <span>
                            Quotation NO: {{$lpo_project->project_code}}<br>
                        </span>
                        <span>
                            Date: {{date('d/m/Y')}} <br>
                        </span>
                    </div>
                </div>
            </div>
            <div class="px-1">
                <p style="font-size:15px; line-hight:0 !important;font-weight:500;color:#444;margin:5px 20px 0 0;"> <span
                        style="font-weight:400"> Dear Sir : </span> </p>
                <p style="font-size:15px; line-hight:0 !important;font-weight:400;color:#444;margin:5px 20px 0 0;">
                    As per our discussion with you, we are submitting the following details and
                    price for Below Mentioned works for your Kind Approval.
                </p>
            </div>
            <div class="my-1 px-1 print-table table-responsive data-table">
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
                            <td colspan="5" class="text-dark text-right" style="border-right:1px solid #ddd;font-size:14px;font-weight:500;">
                                Amount ({{ $currency->symbole }})
                            </td>
                            <td class="text-right text-dark" style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                                {{ number_format($lpo_project->budget,2) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-dark text-right" style="border-right:1px solid #ddd;font-size:14px;font-weight:500;">
                                VAT ({{ $currency->symbole }})
                            </td>
                            <td class="text-right text-dark" style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                                {{ number_format($lpo_project->vat,2) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-dark text-right" style="border-right:1px solid #ddd;font-size:14px;font-weight:500;">
                                Total Amount ({{ $currency->symbole }})
                            </td>
                            <td class="text-right text-dark" style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                                {{ number_format($lpo_project->total_budget,2) }}
                            </td>
                        </tr>
                        <tr>
                            @php
                                $whole = floor($lpo_project->total_budget);
                                $fraction = number_format($lpo_project->total_budget  - $whole, 2);
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

                <div class="container mt-1 ">
                    @if($lpo_project->voucher_file)
                    <h5 class="mb-2 fw-bold text-center">Supporting Documents  </h5>
                    @php
                        $imageExtensions = ['png', 'jpeg', 'gif', 'jpg'];
                        $pdfExtensions = ['pdf'];
                    @endphp


                    @if(in_array($lpo_project->extension, $pdfExtensions))

                    {{-- <div class="row justify-content-center">
                        <a href="{{ Storage::url('upload/documents/' . $recept->voucher_file) }}">
                        <div class="col-auto">
                            <img src="{{asset('icon/pdf-download-icon-2.png')}}" alt="Image" class="img-fluid">
                        </div>
                        </a>
                    </div> --}}

                    <div class="row justify-content-center">
                    <a href="{{ Storage::url('upload/documents/' . $lpo_project->voucher_file) }}" target="_blank">View
                        <img src="{{asset('icon/pdf-download-icon-2.png')}}" alt="Image" class="img-fluid" style="height: 50px"></a>
                    {{-- <iframe src="{{ Storage::url('upload/documents/' . $lpo_project->voucher_file) }}" width="100%" height="600px" frameborder="0"></iframe> --}}
                    </div>
                    @else
                    <div class="row justify-content-center">
                        <div class="col-auto">
                            <a href="{{ asset('storage/upload/documents/' . $lpo_project->voucher_file) }}" target="_blank">                            <img src="{{ asset('storage/upload/documents/' . $lpo_project->voucher_file) }}" alt="Image" class="img-fluid" >
                            </a>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-center mb-2">
                <a href="" class="btn btn-secondary custom-action-btn print-lpo" target="_blank"  title="Print Now">
                    <i class="bx bx-printer text-white"></i> Print
                </a>

                {{-- <button type="button" class="print-page project-btn bg-success" title="Print" style="margin-right: 0.2rem !important;">
                    <span aria-hidden="true">  <i class="bx bx-printer text-white" style="padding-top:4px;"></i> </span>
                </button> --}}
                @if(Auth::user()->hasPermission('ProjectManagement_Authorize'))
                <a href="" class="btn bg-info text-white custom-action-btn work-station-create" title="Genarate Work Order" style="margin-right: 0.2rem !important;">
                <img src="{{asset('icon/generate.png')}}" class="img-fluid" style="height: 25px" alt=""> Work Order
                </a>
                @endif
            </div>

            <div class="divFooter  ml-1 invoice-view-wrapper student_profle-print footer-margin">
                Business Software Solutions by
                <span style="color: #0005" class="spanStyle"><img class="img-fluid"
                        src="{{ asset('img/zikash-logo.png')}}" alt="" width="70"></span>
            </div>
        </div>
    </div>
</section>
<div class="img receipt-bg invoice-view-wrapper">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid" style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;" alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>





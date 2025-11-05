<style>
    .row {
        display: flex;
    }

    td,
    th {
        text-align: left !important;
    }

    .col-md-1 {
        max-width: 8.33% !important;
    }

    .col-md-2 {
        max-width: 16.66% !important;
    }

    .col-md-3 {
        max-width: 25% !important;
    }

    .col-md-8 {
        max-width: 66.66% !important;
    }

    .col-md-10 {
        max-width: 83.33% !important;
    }

    .col-md-11 {
        max-width: 91.66% !important;
    }

    .customer-static-content {
        background: #ada8a81c;
    }

    .customer-dynamic-content {
        background: #706f6f33;
    }

    .customer-content {
        border: 1px solid black !important;
    }

    .proview-table tr td,
    .proview-table tr th {
        border: 1px solid black !important;
    }

    .customer-dynamic-content2 {
        background: #f6f5f5 !important;
    }
    .header-print {
            display: none;
        }
    @media print and (color) {
        .proview-table {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    @media print {
        #widgets-Statistics {
            padding: 2px !important;
        }
        .header-print {
            display: block;
        }
        .divFoote {
            display: block !important;
        }

        .row {
            display: flex;
        }

        .col-md-1 {
            max-width: 8.33% !important;
        }

        .col-md-2 {
            max-width: 16.66% !important;
        }

        .col-md-3 {
            max-width: 25% !important;
        }

        .col-md-8 {
            max-width: 66.66% !important;
        }

        .col-md-10 {
            max-width: 83.33% !important;
        }

        .col-md-11 {
            max-width: 91.66% !important;
        }

        /* .customer-static-content{
            background: #ada8a81c;
        }
        .customer-dynamic-content{
            background: #706f6f33;
        } */
        .customer-dynamic-content2 {
            background: #f6f5f5 !important;
        }

        .proview-table tr td,
        table tr th {
            border: 1px solid black !important;
        }

        .customer-content {
            border: 1px solid black !important;
        }
    }
</style>
<div class="modal-header  print-hideen mb-1" style="padding: 5px 15px;background:#364a60;">
    <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:#fff;padding-left: 12px;"> Invoice </h5>
    <div class="d-flex align-items-center">
        @if($invoice->invoice_type == "Proforma Invoice")
            <div class="" style="padding-right: 3px;"><a href="{{route('convert-to-tax-invoice',$invoice->id)}}" class="btn btn-icon btn-info project-btn" title="Convert into tax invoice"><i class='bx bx-transfer-alt'></i></a></div>
        @endif
      <button type="button" class="project-btn bg-success " style="margin-right: 0.2rem !important;">
          <span aria-hidden="true">  <a href="{{route('invoice-print',$invoice->id)}}" target="_blank"><i class="bx bx-printer text-white"></i></a></span>
      </button>
      <button type="button" class="project-btn bg-danger text-white" data-dismiss="modal" aria-label="Close" style="margin-right: 1.1rem !important;">
          <span aria-hidden="true">&times;</span>
      </button>
    </div>
</div>

@php
    $trn_no = \App\Setting::where('config_name', 'trn_no')->first();
    $company_name = \App\Setting::where('config_name', 'company_name')->first();
    $company_name = \App\Setting::where('config_name', 'company_name')->first();
    $company_address = \App\Setting::where('config_name', 'company_address')->first();
    $company_tele = \App\Setting::where('config_name', 'company_tele')->first();
    $company_email = \App\Setting::where('config_name', 'company_email')->first();
    $trn_no = \App\Setting::where('config_name', 'trn_no')->first();

@endphp
<section>
    <div class="receipt-voucher-hearder invoice-view-wrapper mb-1" style=" border: 1px solid; margin: 70px 20px; border-radius: 20px;">
        @include('layouts.backend.partial.modal-header-info')
        <div class="d-flex justify-content-between px-2">
            <div>
                <span>Tel: {{$company_tele->config_value}}</span><br>
                <span>P.O BOX: 8216</span><br>
                <span>{{$company_address->config_value}}</span><br>
                <span>Email: {{$company_email->config_value}}</span><br>
            </div>
            <div style="text-align: right;">
                <span> {{$company_tele->config_value}}: الهاتف</span><br>
                <span>صندوق بريد: 8216</span><br>
                <span>{{$company_address->config_value}}</span><br>
                <span>{{$company_email->config_value}}:  ريد إلكتروني </span><br>
            </div>
        </div>
    </div>
</section>

<section id="widgets-Statistics">
    <div class="row">
        <div class="col-md-12">

                <div class="customer-info">
                    <div class="row ml-1 mr-1 "style="border: 2px solid #bdbdbd;">
                        <div class="col-md-2 customer-static-content">
                            M/S: <br>
                            Address: <br>
                            Attention: <br>
                            Contact No: <br>
                            Customer TRN: <br>
                        </div>
                        <div class="col-md-10 customer-dynamic-content">
                            {{ $invoice->party->pi_name }} <br>
                            {{ $invoice->party->address }} <br>
                            {{$invoice->attention}} <br>
                            {{$invoice->mobile_no}}


                            <br>
                            {{ $invoice->party->trn_no }} <br>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <h5 class="ml-1 mr-1" style="background: #706f6f33; margin-top: 5px;margin-bottom:5px;">
                        {{ $invoice->invoice_type }}</h5>
                    @if ($invoice->invoice_type != 'Proforma Invoice')
                        <p style="color: balck;margin-bottom:5px !important;">{{ '(' . $trn_no->config_value . ')' }}</p>
                    @endif
                </div>
                <div class="company-info">
                    <div class="row ml-1 mr-1"style="border: 2px solid #bdbdbd;">
                        <div class="col-md-2 customer-static-content">
                            {{ $invoice->invoice_type }} : <br>
                            D.o No: <br>
                            Quotation No: <br>
                            Project Name: <br>
                        </div>
                        <div class="col-md-7 customer-dynamic-content2">
                            @if($invoice->invoice_type == 'Tax Invoice')
                            <span>{{$invoice->invoice_no}}</span> <span style="font-size: 12px;">{{$invoice->proforma_invoice_no?' (Ref: '.$invoice->proforma_invoice_no.')':''}}</span> <br>
                            @else
                            <span>{{$invoice->proforma_invoice_no}}</span> <br>
                            @endif
                            {{ $invoice->project ? $invoice->project->do_no : '' }}<br>
                            {{ $invoice->project->quotation->project_code }} <br>
                            {{ $invoice->project_id?$invoice->new_project->name:$invoice->project->project_name }}<br>
                        </div>
                        <div class="col-md-3 customer-dynamic-content2 pl-0">
                            <span>
                                Date: {{ date('d/m/Y', strtotime($invoice->date)) }} <br><br>
                            </span>
                            <span>
                                LPO NO:{{ $invoice->project ? $invoice->project->lpo_no : '' }}
                            </span>
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
            <div class="col-md-12">
                <table class="table table-sm table-bordered border-botton " style="color: black; ">
                    <thead style="background: #706f6f33 !important;color: black;">
                        <tr>
                            <th class="text-center" style="color: black !important; width:5%;">S.no</th>
                            <th class="text-center" style="color: black !important; width:50%;"> Description</th>
                            <th class="text-center" style="color: black !important; width:10%;"> Unit</th>
                            <th class="text-center" style="color: black !important; width:5%;"> Qty</th>
                            <th class="text-center" style="color: black !important; width:10%;"> Rate</th>
                            @if ($invoice->project->discount>0)
                            <th class="text-center" style="color: black !important; width:10%;"> Discount </th>
                            @endif
                            <th class="text-center" style="color: black !important; width:12%;"> Amount </th>
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
<pre class="text-left border-0 pb-0 mb-0">
{{$task->description}}
</pre>
                                    </td>

                                    <td class="text-center" style="vertical-align: top !important"> {{$task->unit}} </td>
                                    <td class="text-center" style="vertical-align: top !important"> {{floatval($task->qty)}} </td>
                                    <td class="text-center" style="vertical-align: top !important"> {{number_format($task->rate,2)}} </td>
                                    @if ($invoice->project->discount>0)
                                    <td class="text-center" style="vertical-align: top !important"> {{number_format($task->discount,2)}}</td>
                                    @endif
                                    <td class="text-center" style="vertical-align: top !important">{{number_format($task->amount, 2)}}</td>

                                </tr>
                            @endforeach

                        @else
                            @foreach ($invoice->tasks as $key => $item)
                                <tr>
                                    <td class="text-center" style="vertical-align: top !important">{{ ++$key }}</td>
                                    <td class="text-left">{{ $item->task_name }} <br>
                                            <pre class="text-left border-0">{{$item->description}}</pre>
                                        </td>
                                    <td class="text-center" style="vertical-align: top !important"> {{ $item->unit }} </td>
                                    <td class="text-center" style="vertical-align: top !important"> {{ floatval($item->qty) }} </td>
                                    <td class="text-center" style="vertical-align: top !important"> {{ number_format($item->rate,2) }} </td>
                                    @if ($invoice->project->discount>0)
                                    <td class="text-center" style="vertical-align: top !important"> {{number_format($task->discount,2)}}</td>
                                    @endif
                                    <td class="text-center" style="vertical-align: top !important">{{number_format($task->amount, 2)}}</td>
                                </tr>
                            @endforeach
                        @endif

                        @if ($invoice->with_note)
                        <tr>
                            <td></td>
                            <td >Note</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @if ($invoice->project->discount>0)
                            <td></td>
                            @endif
                        </tr>
                        <tr>
                            <td></td>
                            <td >Total works of amount:
                                {{number_format(( $invoice->project->total_budget + ($standard_vat_rate / 100) * $invoice->project->total_budget ),2)}}
                                (Including VAT)</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                @if ($invoice->project->discount>0)
                                <td></td>
                                @endif
                        </tr>

                    @foreach ($notes as $key => $data)
                        <tr>
                            <td></td>
                            <td>  {{$data->top_note}} Invoice No :  {{ $data->invoice_no?$data->invoice_no:$data->proforma_invoice_no }} AED
                                {{ $data->invoice_no }} AED {{ $data->total_budget }} /- (@foreach ($data->receipts as $receipt)
                                    {{ $receipt->payment->pay_mode == 'Cheque' ? $receipt->payment->cheque_no : $receipt->payment->pay_mode }}

                                            <{{ number_format($receipt->Total_amount,2) }}>

                                @endforeach )
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @if ($invoice->project->discount>0)
                            <td></td>
                            @endif
                        </tr>
                    @endforeach
                    @endif
                        <tr>
                            <td colspan="{{$invoice->project->discount>0 ? 6 : 5}}" class="text-right pr-1" style="background: #ada8a81c"> Total Amount</td>
                            <td style="background: #ada8a81c;text-align:center !important">{{number_format($invoice->project->total_budget,2)}}</td>
                        </tr>
                        <tr>

                            <td colspan="{{$invoice->project->discount>0 ? 6 : 5}}" class="text-right pr-1" style="background: #ada8a81c"> Invoiced Amount</td>
                            <td style="background: #ada8a81c;text-align:center !important">{{number_format($invoice->budget,2) }}</td>
                        </tr>
                        <tr>

                            <td colspan="{{$invoice->project->discount>0 ? 6 : 5}}" class="text-right pr-1" style="background: #ada8a81c"> VAT </td>
                            <td style="background: #ada8a81c;text-align:center !important">{{number_format($invoice->vat,2)}}</td>
                        </tr>
                        <tr>
                            <td colspan="{{$invoice->project->discount>0 ? 6 : 5}}" class="text-right pr-1" style="background: #ada8a81c">Invoiced Total Amount <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small> </td>
                            <td style="background: #ada8a81c;text-align:center !important"> {{number_format($invoice->total_budget,2)}} </td>
                        </tr>



                        <tr>
                            @php

                                $whole = floor($invoice->total_budget);
                                $fraction = number_format($invoice->total_budget - $whole, 2);
                                $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                $amount_in_word = $f->format($whole);
                                $amount_in_word2 = $f->format((int)($fraction*100));
                            @endphp
                            <td colspan="{{$invoice->project->discount>0 ? 7 : 6}}" class="text-center text-dark text-capitalize"
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

                <div class="container mt-1 ">
                    <h5 class="mb-2 fw-bold text-center">Support Documents </h5>
                    @if($invoice->voucher_file)
                    @php
                        $imageExtensions = ['png', 'jpeg', 'gif', 'jpg'];
                        $pdfExtensions = ['pdf'];
                    @endphp


                    @if(in_array($invoice->extension, $pdfExtensions))

                    {{-- <div class="row justify-content-center">
                        <a href="{{ Storage::url('upload/documents/' . $recept->voucher_file) }}">
                        <div class="col-auto">
                            <img src="{{asset('icon/pdf-download-icon-2.png')}}" alt="Image" class="img-fluid">
                        </div>
                        </a>
                    </div> --}}

                    <div class="row justify-content-center">
                    <a href="{{ Storage::url('upload/documents/' . $invoice->voucher_file) }}" target="_blank">View
                        <img src="{{asset('icon/pdf-download-icon-2.png')}}" alt="Image" class="img-fluid" style="height: 50px"></a>
                    {{-- <iframe src="{{ Storage::url('upload/documents/' . $invoice->voucher_file) }}" width="100%" height="600px" frameborder="0"></iframe> --}}
                    </div>
                    @else
                    <div class="row justify-content-center">
                        <div class="col-auto">
                           <a href="{{ asset('storage/upload/documents/' . $invoice->voucher_file) }}" target="_blank"> <img src="{{ asset('storage/upload/documents/' . $invoice->voucher_file) }}" alt="Image" class="img-fluid" ></a>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between aligin-items-center mt-3" style="padding: 10px;">
            <div class="w-100">
                Receiver's Sign -------------------------------------
                <span>
                    علامة المتلقي
                </span>
            </div>
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100 ">
                <span style=" color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;"
                    class="pl-2">
                    For <br> {{ $company_name->config_value }}
                </span>
            </div>
        </div>
        <div class="divFoote  d-none " style="background: #f6f5f5 ; padding-top:10px ; padding-bottom:10px">
            <p class="text-center" style="text-align: center !important">
                تليفون : ٠٦٧٤٨۰۲۲۳، ص.ب : ۸۲۱٦، منطقة الصناعية الجديدة، عجمان - ا.ع.م <br>
                Tel: 06 7480223, P.O. Box: 8216, New Industrial Area, Ajman - U.A.E. <br> Email:
                binhindifabrication@yahoo.com</p>
        </div>
        <div class="divFooter mb-1 ml-1 invoice-view-wrapper  footer-margin">
            Business Software Solutions by
            <span style="color: #0005" class="spanStyle"><img class="img-fluid"
                    src="{{ asset('img/zikash-logo.png')}}" alt="" width="70"></span>
        </div>
</section>
<div class="img receipt-bg invoice-view-wrapper">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid" style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;" alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>

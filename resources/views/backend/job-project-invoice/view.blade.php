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
        background: #706f6f33;
    }
    .header-print {
            display: none;
        }
    .customer-content{
        border: 1px solid black !important;
    }
    .proview-table tr td, .proview-table tr th{
        border: 1px solid black !important;
    }
    .customer-dynamic-content2{
        background: #f6f5f5 !important;
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
        .header-print {
            display: block;
        }
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
        /* .customer-static-content{
            background: #ada8a81c;
        }
        .customer-dynamic-content{
            background: #706f6f33;
        } */
        .customer-dynamic-content2{
            background: #f6f5f5  !important;

        }
        .proview-table tr td, table tr th{
            border: 1px solid black !important;
        }
        .customer-content{
            border: 1px solid black !important;
        }
    }
    @media print {
            pre {
                border: none !important;  /* Remove border when printing */
            }
        }
</style>
<div class="modal-header  print-hideen mb-1" style="padding: 5px 15px;background:#364a60;">
    <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:#fff;padding-left: 12px;"> Invoice </h5>
    <div class="d-flex align-items-center">
      <button type="button" class="project-btn bg-success " style="margin-right: 0.2rem !important;">
          <span aria-hidden="true">  <a href="{{route('temp-invoice-print',$tem_invoice->id)}}" target="_blank"><i class="bx bx-printer text-white"></i></a> </span>
      </button>
      {{-- <div class="" style="padding-right: 3px;margin-top: 6px;"><a href="{{route('temp-invoice-print',$tem_invoice->id)}}" target="_blank" class="btn btn-icon btn-success" title="Print"><i class="bx bx-printer"></i></a></div> --}}

      <button type="button" class="project-btn bg-danger text-white" data-dismiss="modal" aria-label="Close" style="margin-right: 1.1rem !important;">
          <span aria-hidden="true">&times;</span>
      </button>
    </div>

  </div>
@php
    $trn_no= \App\Setting::where('config_name', 'trn_no')->first();
    $company_name= \App\Setting::where('config_name', 'company_name')->first();
    $company_name = \App\Setting::where('config_name', 'company_name')->first();
    $company_address = \App\Setting::where('config_name', 'company_address')->first();
    $company_tele = \App\Setting::where('config_name', 'company_tele')->first();
    $company_email = \App\Setting::where('config_name', 'company_email')->first();
    $trn_no = \App\Setting::where('config_name', 'trn_no')->first();
@endphp

<section id="widgets-Statistics">
    <div class="row">
        <div class="col-sm-12">
            <h4 class=" text-center" style="margin:0;padding:0;line-height:40px;color: #1d1d1d !important;"> <strong>{{$tem_invoice->tax_invoice}}</strong> </h4>
            <p class="text-center mb-2" style="color: #1d1d1d !important;">
                Invoice No: @if($tem_invoice->tax_invoice == 'Tax Invoice')
                {{$tem_invoice->invoice_no}}
                @else
                {{$tem_invoice->proforma_invoice_no}}
                @endif,
                Date: {{date('d/m/Y', strtotime($tem_invoice->date))}},@if ($tem_invoice->tax_invoice!='Proforma Invoice')
                VAT TRN: {{'('.$trn_no->config_value.')'}} @endif, Running No: {{$running_no}}
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
                                    : {{$tem_invoice->party->pi_name}}
                                </p>
        
                            </div>
                            <div class="d-flex">
                                <div class="text-left" style="width:16%;">
                                    Address
                                </div>
                                <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                                    : {{$tem_invoice->party->address}}
                                </p>
                            </div>
                        </div>
                        <div class="col-4 p-0">
                            <div class="d-flex">
                                <div class="text-left" style="width:30%;">
                                    Attention
                                </div>
                                <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                                    : {{ $tem_invoice->attention ? $tem_invoice->attention : '.'}}
                                </p>
    
                            </div>
                            <div class="d-flex">
                                <div class="text-left" style="width:30%;">
                                    Contact No
                                </div>
                                <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                                    : @if ($tem_invoice->party->phone_no==$tem_invoice->party->con_no && $tem_invoice->party->con_no!='.' && $tem_invoice->party->con_no!='')
                                    {{$tem_invoice->party->phone_no}}
                                    @elseif($tem_invoice->party->con_no && $tem_invoice->party->phone_no && $tem_invoice->party->con_no!='.' && $tem_invoice->party->phone_no!='.')
                                    {{$tem_invoice->party->con_no.', '. $tem_invoice->party->phone_no}}
                                    @else
                                    {{$tem_invoice->party->con_no && $tem_invoice->party->con_no!='.'? $tem_invoice->party->con_no:($tem_invoice->party->phone_no?$tem_invoice->party->phone_no:'')}}
    
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
                                    : {{ $tem_invoice->project_id?$tem_invoice->new_project->name:$tem_invoice->project->project_name }}
                                </p>
        
                            </div>
                            <div class="d-flex">
                                <div class="text-left" style="width:16%;">
                                    Quotation No
                                </div>
                                <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                                    : {{ $tem_invoice->project->quotation->project_code }}
                                </p>
                            </div>
                        </div>
                        <div class="col-4 p-0">
                            <div class="d-flex">
                                <div class="text-left" style="width:30%;">
                                    D.O No
                                </div>
                                <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                                    : {{ $tem_invoice->project ? $tem_invoice->project->do_no : '' }}
                                </p>
                            </div>
                            <div class="d-flex">
                                <div class="text-left" style="width:30%;">
                                    LPO NO
                                </div>
                                <p style="padding-left: 15px; margin-bottom:0px; line-height:16px;">
                                    : {{$tem_invoice->project?$tem_invoice->project->lpo_no:''}}
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
            <h6 style="margin-top: 15px; margin-bottom: 0px;">

                    {{$tem_invoice->top_note}}

            </h6>
        </div>
    </div>
    <div class="row" style="padding: 15px;">
        <div class="col-md-12">
            <table class="table table-sm table-bordered border-botton " style="color: black; ">
                <thead style="background: #919090be !important;color: black;">
                    <tr >
                        <th class="text-center" style="color: black !important;">Serial</th>
                        <th class="text-center" style="color: black !important;"> Description</th>
                        <th class="text-center" style="color: black !important;"> Unit</th>
                        <th class="text-center" style="color: black !important;"> Qty</th>
                        <th class="text-center" style="color: black !important;"> Rate</th>
                        @if($tem_invoice->project->discount>0)
                            <th class="text-center" style="color: black !important;"> Discount </th>
                        @endif
                        <th class="text-center" style="color: black !important;"> Amount <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                    </tr>
                </thead>
                @php
                    $cc=0;
                @endphp
                <tbody class="user-table-body">
                    @if ($tem_invoice->project->invoice_type == 'amount_base')
                    @foreach ($tem_invoice->project->tasks as $key => $task)

                        <tr>
                            <td class="text-center" style="vertical-align: top !important">{{++$key}}</td>
                            <td class="text-left">{{$task->task_name}} <br>
                                <pre class="text-left border-0 mb-0 pb-0">{{$task->description}}</pre>
                            </td>

                            <td class="text-center" style="vertical-align: top !important"> {{$task->unit}} </td>
                            <td class="text-center" style="vertical-align: top !important"> {{floatval($task->qty)}} </td>
                            <td class="text-center" style="vertical-align: top !important"> {{number_format($task->rate,2)}} </td>
                            @if($tem_invoice->project->discount> 0)
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
                            <td class="text-center"> {{number_format($item->rate,2)}} </td>
                            <td class="text-center">{{number_format($item->due_amount,2) }}</td>
                        </tr>
                        @endforeach
                    @endif

                    @if ($tem_invoice->with_note)
                    <tr>
                        <td></td>
                        <td>Note</td>

                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @if($tem_invoice->project->discount>0)
                        <td></td>
                        @endif


                    </tr>
                    <tr>
                        <td></td>
                        <td>Total works of amount:
                            {{ number_format($tem_invoice->project->total_budget + ($standard_vat_rate / 100) * $tem_invoice->project->total_budget,2,'.','') }}
                            (Including VAT)</td>

                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @if($tem_invoice->project->discount>0)
                        <td></td>
                        @endif


                    </tr>

                @php
                    $cc=0
                @endphp
                @foreach ($notes as $key => $data)
                    <tr>
                        <td></td>
                        <td>  {{$data->top_note}} Invoice No :
                            {{ $data->invoice_no?$data->invoice_no:$data->proforma_invoice_no }} AED {{ number_format($data->total_budget,2) }} /- (@foreach ($data->receipts as $receipt)
                                {{ $receipt->payment->pay_mode == 'Cheque' ? $receipt->payment->cheque_no : $receipt->payment->pay_mode }}

                                        <{{ number_format($receipt->Total_amount,2) }}>

                            @endforeach )</td>

                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @if($tem_invoice->project->discount>0)
                        <td></td>
                        @endif

                    </tr>
                @endforeach


                <tr>
                    <td></td>
                    <td>  {{$tem_invoice->top_note}} Invoice No :
                        {{ $tem_invoice->invoice_no?$tem_invoice->invoice_no:$tem_invoice->proforma_invoice_no }} AED {{ number_format($tem_invoice->total_budget,2) }} /- ()</td>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    @if($tem_invoice->project->discount>0)
                    <td></td>
                    @endif

                </tr>
                @endif
                    <tr>
                        <td colspan="{{$tem_invoice->project->discount>0 ? 5 : 4}}"></td>
                        <td class="text-right pr-1" style="background: #ada8a81c"> Total Amount</td>
                        <td style="background: #ada8a81c;text-align:center !important">{{number_format($tem_invoice->project->total_budget,2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="{{$tem_invoice->project->discount>0 ? 5 : 4}}"></td>
                        <td class="text-right pr-1" style="background: #ada8a81c"> Invoiced Amount</td>
                        <td style="background: #ada8a81c;text-align:center !important">{{number_format($tem_invoice->budget,2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="{{$tem_invoice->project->discount>0 ? 5 : 4}}"></td>
                        <td class="text-right pr-1" style="background: #ada8a81c"> VAT </td>
                        <td style="background: #ada8a81c;text-align:center !important">{{number_format($tem_invoice->vat,2)}}</td>
                    </tr>
                    <tr>
                        <td colspan="{{$tem_invoice->project->discount>0 ? 5 : 4}}"></td>
                        <td class="text-right pr-1" style="background: #ada8a81c">Invoiced Total Amount </td>
                        <td style="background: #ada8a81c;text-align:center !important">{{number_format($tem_invoice->total_budget,2)}}</td>
                    </tr>



                    <tr>
                        @php

                            $whole = floor($tem_invoice->total_budget);
                            $fraction = number_format($tem_invoice->total_budget - $whole, 2);
                            $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                            $amount_in_word = $f->format($whole);
                            $amount_in_word2 = $f->format((int)($fraction*100));
                        @endphp
                        <td colspan="{{$tem_invoice->project->discount>0 ? 7 : 6}}" class="text-center text-dark text-capitalize"
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
                @if($tem_invoice->voucher_file)
                @php
                    $imageExtensions = ['png', 'jpeg', 'gif', 'jpg'];
                    $pdfExtensions = ['pdf'];
                @endphp


                @if(in_array($tem_invoice->extension, $pdfExtensions))

                {{-- <div class="row justify-content-center">
                    <a href="{{ Storage::url('upload/documents/' . $recept->voucher_file) }}">
                    <div class="col-auto">
                        <img src="{{asset('icon/pdf-download-icon-2.png')}}" alt="Image" class="img-fluid">
                    </div>
                    </a>
                </div> --}}

                <div class="row justify-content-center">
                <a href="{{ Storage::url('upload/documents/' . $tem_invoice->voucher_file) }}" target="_blank">View
                    <img src="{{asset('icon/pdf-download-icon-2.png')}}" alt="Image" class="img-fluid" style="height: 50px"></a>
                {{-- <iframe src="{{ Storage::url('upload/documents/' . $tem_invoice->voucher_file) }}" width="100%" height="600px" frameborder="0"></iframe> --}}
                </div>
                @else
                <div class="row justify-content-center">
                    <div class="col-auto">
                       <a href="{{ asset('storage/upload/documents/' . $tem_invoice->voucher_file) }}" target="_blank"> <img src="{{ asset('storage/upload/documents/' . $tem_invoice->voucher_file) }}" alt="Image" class="img-fluid" ></a>
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between aligin-items-center mt-3" style="padding: 10px;">
        <div class="w-100">
            Receiver's Sign ----------------------------------
            <span>
                علامة المتلقي
            </span>
        </div>
        <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
            <span style=" color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;" class="">
                For <br> {{$company_name->config_value}}
            </span>
        </div>
    </div>
    <div class="mb-2 d-flex print-hideen justify-content-center align-item-center w-100">
        @if ($tem_invoice->authorize_by)
        <a href="{{ route('project.invoice.approve.change',$tem_invoice->id) }}" class="project-btn btn-light-green" onclick="return confirm('About to approve invoice. Please, Confirm?')"> Approve </a>
        @else
        <a href="{{ route('project.invoice.autorize.change',$tem_invoice->id) }}" class="project-btn btn-light-green" onclick="return confirm('About to authorize invoice. Please, Confirm?')"> Authorize </a>
        @endif
    </div>
    <div class="divFoote mb-1 ml-1 d-none ">
        <p class="text-center" style="text-align: center !important">
            تليفون : ٠٦٧٤٨۰۲۲۳، ص.ب : ۸۲۱٦، منطقة الصناعية الجديدة، عجمان - ا.ع.م <br>
            Tel: 06 7480223, P.O. Box: 8216, New Industrial Area, Ajman - U.A.E. <br> Email:
            binhindifabrication@yahoo.com
        </p>

    </div>

    <div class="divFooter mb-1 ml-1 invoice-view-wrapper footer-margin">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid"
                src="{{ asset('img/zikash-logo.png')}}" alt="" width="70"></span>
    </div>
</section>

<div class="img receipt-bg invoice-view-wrapper">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid" style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;" alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>

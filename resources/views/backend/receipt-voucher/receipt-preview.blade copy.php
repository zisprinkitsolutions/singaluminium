<style>
    html,
    body {
        height: 100%;
    }

    thead {
        background: #34465b;
        color: #fff !important;
        height: 30px;
    }
    .receipt-bg{
        display: none;
    }
    @media print{
        .receipt-bg{
            display: block;
        }
    }
</style>
@php
    $company_name= \App\Setting::where('config_name', 'company_name')->first();
    $company_address= \App\Setting::where('config_name', 'company_address')->first();
    $company_tele= \App\Setting::where('config_name', 'company_tele')->first();
    $company_email= \App\Setting::where('config_name', 'company_email')->first();
    $trn_no= \App\Setting::where('config_name', 'trn_no')->first();
    $whole = floor($recept->total_amount);
    $fraction = number_format($recept->total_amount - $whole, 2);
    $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
    $amount_in_word = $f->format($whole);
    $amount_in_word2 = $f->format($fraction);
@endphp
<section class="print-hideen border-bottom">
    <div class="d-flex flex-row-reverse">
        <div class="py-1 pr-1">
            <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a>
        </div>
        <div class="py-1 pr-1"><a href="#" onclick="window.print();" class="btn btn-icon btn-secondary"><i class="bx bx-printer"></i></a></div>

        <div class="py-1 mr-1">
            <a href="{{ route('receipt-voucher-delete', $recept) }}" class="btn btn-sm btn-icon btn-danger">Delete</a>
        </div>
        @if ($recept->status == 0)
            <div class="py-1 mr-1">
                <a href="{{ route('receipt-voucher-authorize', $recept) }}" class="btn btn-sm btn-icon btn-success">Authorize</a>
            </div>
        @else
            <div class="py-1 mr-1">
                <a href="{{ route('receipt-voucher-approve', $recept) }}" class="btn btn-sm btn-icon btn-success">Approve</a>
            </div>
        @endif
        @if ($recept->is_direct!=1)
        <div class="py-1 mr-1">
            <a href="{{ route('receipt-voucher-edit', $recept) }}" class="btn btn-sm btn-icon btn-success">Edit</a>
        </div>
        @endif

        <div class="py-1 pr-1 w-100 pl-2">
            <h4>Receipt</h4>
        </div>
    </div>
</section>
<section>
    <div class="receipt-voucher-hearder invoice-view-wrapper" style=" border: 1px solid; margin: 0px 20px; border-radius: 20px;">
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
    <div class="payment-voucher px-2 pt-1 pb-4" style=" border: 1px solid; margin: 20px; border-radius: 20px;">
        <div class="d-flex justify-content-between">
            <div style="display: flex; padding-bottom: 50px; padding-top: 25px;" >
                <div style="width: 66% !important;">
                    Dhs. <span style="padding-left: 100px;">درهم</span>
                    <div style="border: 1px solid #111; padding: 20px 100px; width: 34% !important;">
                        <strong>{{$whole}}</strong>
                    </div>
                </div>
                <div style="width: 34% !important;">
                    Fils <span style="padding-left: 50px;">فلس</span>
                    <div style="border: 1px solid #111; padding: 20px 50px; width: 34% !important;">
                        <strong>{{$fraction}}</strong>
                    </div>
                </div>
                <div>
                </div>
            </div>
            <div class="text-center invoice-view-wrapper student_profle-print" style="padding-right: 50px;">
                <h1 style="color: #313131; border-bottom: 1px solid;">سند القبض</h1>
                <h3 style="color: #313131">RECEIPT VOUCHER</h3>
            </div>
            <div style="padding-top: 25px;">
                <div class="col-12 text-right">
                    <strong>No.: {{$recept->receipt_no}}</strong><br><br>
                    <strong>Date :</strong> {{ date('d/m/Y', strtotime($recept->date)) }}
                </div>
            </div>
        </div>


        <div class="d-flex w-100">
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span style="width:300px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                   Project:
                </span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                        <span style="  text-transform: uppercase !important;">
                            @foreach ($recept->items as $r)
                        {{$r->invoice->project?$r->invoice->project->project_name:''}}
                      @endforeach
                        </span>
                    </p>
                </div>

            </div>
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span style="width:70px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;" class="pl-2">
                    Invoice
                </span>
                <div class="w-100" style="border-bottom:1px dashed #111;margin-left: 10px;">
                    <p style="margin:0 !important; padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                      @foreach ($recept->items as $r)
                        {{$r->invoice->invoice_no}}
                      @endforeach

                    </p>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-between aligin-items-center mb-1">
            <span style="width:260px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                Received from Mr./Ms.</span>
            <div class="w-100" style="border-bottom:1px dashed #111;">
                <p style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                    {{ $recept->party->pi_name }}
                </p>
            </div>
            <span style="width:200px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                وردت من السيد / السيدة
            </span>
        </div>


        <div class="d-flex w-100">
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span style="width:300px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                   Project:
                </span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                        <span style="  text-transform: uppercase !important;">
                            @foreach ($recept->items as $r)
                        {{$r->invoice->project?$r->invoice->project->project_name:''}}
                      @endforeach
                        </span>
                    </p>
                </div>

            </div>
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span style="width:70px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;" class="pl-2">
                    Invoice
                </span>
                <div class="w-100" style="border-bottom:1px dashed #111;margin-left: 10px;">
                    <p style="margin:0 !important; padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                      @foreach ($recept->items as $r)
                        {{$r->invoice->invoice_no}}
                      @endforeach

                    </p>
                </div>

            </div>
        </div>

        <div class="d-flex w-100">
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span style="width:140px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    The Sum of Dhs
                </span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px;text-transform: uppercase">
                        {{ $amount_in_word }}
                        @if ($fraction > 0)
                            {{ '& ' . substr($amount_in_word2, 10) }}
                        @endif {{ $currency->symbole }}
                    </p>
                </div>
                <span style="width:80px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    المبلغ درهم
                </span>
            </div>
        </div>



        <div class="d-flex w-100">
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span style="width:300px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    By Cash / Cheque No
                </span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                        <span style="  text-transform: uppercase !important;">
                            {{ $recept->pay_mode }}
                        </span>
                    </p>
                </div>
                <span style="width:180px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    نقدا / رقم الشيك
                </span>
            </div>
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span style="width:70px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;" class="pl-2">
                    Date
                </span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                       {{ date('d/m/Y', strtotime($recept->date)) }}
                    </p>
                </div>
                <span style="width:50px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    تاريخ
                </span>
            </div>
        </div>

        <div class="d-flex justify-content-between aligin-items-center mb-1">
            <span style="width:50px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                Bank
            </span>
            <div class="w-100" style="border-bottom:1px dashed #111;">
                <p style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                    <span style="  text-transform: uppercase !important;">
                        {{$recept->issuing_bank? $recept->issuing_bank:''}}
                        {{$recept->branch? ', '.$recept->branch:''}}
                        {{$recept->cheque_no? ', '.$recept->cheque_no:''}}
                        {{$recept->deposit_date? ', '.date('d/m/Y',strtotime($recept->deposit_date)):''}}
                    </span>
                </p>
            </div>
            <span style="width:35px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                بنك
            </span>
        </div>

        <div class="d-flex justify-content-between aligin-items-center mb-1">
            <span
                style="width:50px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                Being</span>
            <div class="w-100" style="border-bottom:1px dashed #111;">
                <p style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                    <span style="  text-transform: uppercase !important;">

                    </span>
                </p>
            </div>
            <span style="width:35px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                كون
            </span>
        </div>

        <div class="d-flex justify-content-between aligin-items-center mt-3">
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span style="width:230px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    Receiver's Sign
                </span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                        <span style="  text-transform: uppercase !important;">

                        </span>
                    </p>
                </div>
                <span style="width:150px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    علامة المتلقي
                </span>
            </div>
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span style="width:100px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;" class="pl-2">
                    Signature
                </span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">

                    </p>
                </div>
                <span style="width:50px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    إمضاء
                </span>
            </div>
        </div>


    </div>


    <div class="divFooter mb-1 ml-1 invoice-view-wrapper">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid"
                src="{{ asset('img/zikash-logo.png')}}" alt="" width="70"></span>
    </div>
</section>
<div class="img receipt-bg">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid" style="position: fixed; top: 420px; left: 200px; opacity: 0.3; width: 650px !important; height: 250px;" alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>

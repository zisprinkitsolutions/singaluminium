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

    .receipt-bg {
        display: none;
    }

    @media print {
        .receipt-bg {
            display: block;
        }
    }
</style>
@php
    $company_name = \App\Setting::where('config_name', 'company_name')->first();
    $company_address = \App\Setting::where('config_name', 'company_address')->first();
    $company_tele = \App\Setting::where('config_name', 'company_tele')->first();
    $company_email = \App\Setting::where('config_name', 'company_email')->first();
    $trn_no = \App\Setting::where('config_name', 'trn_no')->first();
    $whole = floor($recept->total_amount);
    $fraction = number_format($recept->total_amount - $whole, 2);
    $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
    $amount_in_word = $f->format($whole);
    $amount_in_word2 = $f->format((int) ($fraction * 100));
@endphp
<section class="print-hideen border-bottom" style="padding: 5px 15px;background:#364a60;">
    <div class="d-flex flex-row-reverse" style="padding-right: 10px;">
        <div class="" style="margin-top: 6px;">
            <a href="#" class="close btn-icon btn btn-danger cancle-modal" title="Close"
                data-url={{ route('receipt-voucher-approve', $recept) }}><span aria-hidden="true"><i
                        class='bx bx-x'></i></span></a>
        </div>
        <div class="" style="padding-right: 3px;margin-top: 6px;"><a href="#" onclick="window.print();"
                class="btn btn-icon btn-success" title="Print"><i class="bx bx-printer"></i></a></div>

        <div class="" style="padding-right: 3px;margin-top: 6px;">
            <a href="{{ route('receipt-voucher-delete', $recept) }}" class="btn btn-icon btn-danger"
                onclick="event.preventDefault(); deleteAlert(this, 'About to delete receipt voucher. Please, confirm?');"
                title="Delete"><i class="bx bx-trash"></i></a>
        </div>

        <div class="" style="padding-right: 3px;margin-top: 6px;">
            <a href="{{ route('receipt-voucher-edit', $recept) }}" class="btn btn-icon btn-primary edit-btn"
                title="Edit"><i class="bx bx-edit"></i></a>
        </div>

        <div class="" style="padding-right: 3px;margin-top: 6px;">
            <a href="{{ route('receipt-voucher-approve', $recept) }}" class="btn btn-icon btn-success"
                onclick="event.preventDefault(); deleteAlert(this, 'About to approve voucher. Please, confirm?', 'approve');"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Approve"><i class="bx bx-check"></i></a>
        </div>

        <div class="w-100">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white; text-align:left;">Receipt Voucher </h4>
        </div>
    </div>
</section>
<section>
    <div class="receipt-voucher-hearder invoice-view-wrapper"
        style=" border: 1px solid; margin: 50px 20px; border-radius: 20px;">
        <style>
            @media {
                .border1 {
                    color: red !important;
                }

                .border2 {
                    color: green !important;
                }
            }
        </style>
        @php
            $company_name = App\Setting::where('config_name', 'company_name')->first();
            $company_name_arabic = App\Setting::where('config_name', 'company_name_arabic')->first();
            $company_email = App\Setting::where('config_name', 'company_email')->first();
            $company_tele = App\Setting::where('config_name', 'company_tele')->first();
            $company_fax = App\Setting::where('config_name', 'company_fax')->first();
            $company_mobile = App\Setting::where('config_name', 'company_mobile')->first();
        @endphp
        <section class="invoice-view-wrapper">
            <div style="width:100%;">
                <img src="{{ isset($imageUrl) ? $imageUrl : asset('default.jpg') }}" alt=""
                    style="height:130px !important; width:100%;">
            </div>
            {{-- <div class="row">
                    <div class="col-md-5 col-xl-5 col-5">
                        <h4 class="text-left pl-1" style="font-size: 20px !important; color: #ff0000c0 !important;">
                            <strong>{{$company_name->config_value}}</strong>
                        </h4>
                        <h5 class="text-left pl-1">
                            Tel: {{$company_tele->config_value}} <br>
                            Fax: {{$company_fax->config_value}} <br>
                            Mob: {{$company_mobile->config_value}} <br>
                        </h5>
                    </div>
                    <div class="col-xl-2 col-md-2 col-2 text-center p-0 m-0">
                        <div class="pl-2">
                            <img src="{{ asset('img/laterhead.jpg') }}" alt="" style="height: 100px !important">
                        </div>
                        <p class=" text-center p-0 m-0" style="color: #ff0000c0 !important;">Email:{{$company_email->config_value}}
                        </p>
                    </div>
                    <div class="col-md-5 col-xl-5 col-5">
                        <h4 class="text-right pr-1" style="color: #ff0000c0 !important;">{{$company_name_arabic->config_value}}</h4>
                        <h5 class="text-right pr-1">
                            هاتف: {{$company_tele->config_value}} <br>
                            فاكس: {{$company_fax->config_value}} <br>
                            الغوغاء: {{$company_mobile->config_value}} <br>
                        </h5>
                    </div>
                </div> --}}
            <p style="border-top: 3px solid #ff0000c0 !important"
                style="padding-bootm: 0px !important; margin-bootm:0px !important;"></p>
            <p style="border-top: 3px solid #008000b6 !important"></p>
        </section>
        <div class="d-flex justify-content-between px-2">
            <div>
                <span>Tel: {{ $company_tele->config_value }}</span><br>
                <span>P.O BOX: 8216</span><br>
                <span>{{ $company_address->config_value }}</span><br>
                <span>Email: {{ $company_email->config_value }}</span><br>
            </div>
            <div style="text-align: right;">
                <span> {{ $company_tele->config_value }}: الهاتف</span><br>
                <span>صندوق بريد: 8216</span><br>
                <span>{{ $company_address->config_value }}</span><br>
                <span>{{ $company_email->config_value }}: ريد إلكتروني </span><br>
            </div>
        </div>
    </div>
</section>
<section id="widgets-Statistics">
    <div class="payment-voucher px-2 pt-1 pb-4" style=" border: 1px solid; margin: 20px; border-radius: 20px;">
        <div class="row  mb-2">
            <div class="col-4">
                <table class="receipt-price">
                    <tr>
                        <td class="td-bottom-border" style="width: 60% !important;padding-bottom:0px !important">
                            <div class="d-flex justify-content-between w-100">
                                <div>Dhs.</div>
                                <div>درهم</div>
                            </div>
                        </td>
                        <td class="td-bottom-border" style="width: 40% !important;padding-bottom:0px !important">
                            <div class="d-flex justify-content-between w-100">
                                <div>Fils</div>
                                <div>فلس</div>
                            </div>
                        </td>
                    </tr>
                    <tr class="tr-border">
                        <td class="td-top-border td-right-border">{{ $whole }}</td>
                        <td class="td-top-border">{{ $fraction * 100 }}</td>
                    </tr>

                </table>
            </div>
            <div class="col-4 text-center ">
                <div class="invoice-view-wrapper">
                    <h1 style="color: #313131; border-bottom: 1px solid;">سند القبض</h1>
                    <h3 style="color: #313131">RECEIPT VOUCHER</h3>
                </div>

            </div>
            <div class="col-4 d-flex align-items-center justify-content-end">
                <div class="row">
                    <div class="col-8 text-right ">
                        <strong> No.:</strong>
                    </div>
                    <div class="col-4 col-left-padding">
                        <strong> {{ $recept->receipt_no }}</strong>
                    </div>
                    <div class="col-8 text-right ">
                        <strong> Date</strong>
                    </div>
                    <div class="col-4 col-left-padding">
                        <strong> {{ date('d/m/Y', strtotime($recept->date)) }}</strong>
                    </div>
                </div>
                <br><br>
            </div>
        </div>


        <div class="d-flex w-100">
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span
                    style="width:100px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    Project:
                </span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p
                        style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                        <span style="  text-transform: uppercase !important;">

                                 @foreach ($recept->items as $r)
                                    {{ $r->invoice ? ($r->invoice->project ? $r->invoice->project->project_name : '') : '' }}
                                @endforeach

                        </span>
                    </p>
                </div>

            </div>
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-50">
                    <span style="width:100px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                        Plot No:
                    </span>
                    <div class="w-100" style="border-bottom:1px dashed #111;">
                        <p
                            style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                            <span style="  text-transform: uppercase !important;">

                                @foreach ($recept->items as $r)
                                {{ $r->invoice ? ($r->invoice->project ? $r->invoice->project->prospect->plot : '') : '' }}
                                @endforeach

                            </span>
                        </p>
                    </div>

                </div>
                <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                    <span
                        style="width:70px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;"
                        class="pl-2">
                        Invoice:
                    </span>
                    <div class="w-100" style="border-bottom:1px dashed #111;margin-left: 15px;">
                        <p
                            style="margin:0 !important; padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                            @foreach ($recept->items as $r)
                                {{ $r->invoice ? ($r->invoice->invoice_no ? $r->invoice->invoice_no : $r->invoice->proforma_invoice_no) : '' }}
                            @endforeach
                        </p>
                    </div>
                </div>
        </div>

        <div class="d-flex justify-content-between aligin-items-center mb-1">
            <span
                style="width:260px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                Received from Mr./Ms.</span>
            <div class="w-100" style="border-bottom:1px dashed #111;">
                <p
                    style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                    {{ $recept->name == null ? $recept->party->pi_name : $recept->name }}
                </p>
            </div>
            <span
                style="width:250px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                وردت من السيد / السيدة
            </span>
        </div>


        <div class="d-flex w-100">
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span
                    style="width:170px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    The Sum of Dhs:
                </span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p
                        style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px;text-transform: uppercase">
                        {{ $amount_in_word }} Dirhams
                        @if ($fraction > 0)
                            {{ '& ' . $amount_in_word2 }}
                        @else
                            No
                        @endif Fils
                    </p>
                </div>
                <span
                    style="width:120px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    المبلغ درهم
                </span>
            </div>
        </div>



        <div class="d-flex w-100">
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span
                    style="width:320px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    By Cash / Cheque No:
                </span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p
                        style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                        <span style="  text-transform: uppercase !important;">
                            {{ $recept->pay_mode }} {{ $recept->bank_name ? ',' . $recept->bank_name->name : '' }}
                        </span>
                    </p>
                </div>
                <span
                    style="width:210px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    نقدا / رقم الشيك
                </span>
            </div>
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span
                    style="width:70px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;"
                    class="pl-2">
                    Date:
                </span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p
                        style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                        @if ($recept->pay_mode == 'Cheque')
                            {{ $recept->deposit_date ? date('d/m/Y', strtotime($recept->deposit_date)) : '' }}
                        @endif
                    </p>
                </div>
                <span
                    style="width:50px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    تاريخ
                </span>
            </div>
        </div>

        <div class="d-flex justify-content-between aligin-items-center mb-1">
            <span
                style="width:50px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                Bank:
            </span>
            <div class="w-100" style="border-bottom:1px dashed #111;">
                <p
                    style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                    <span style="">
                        @if ($recept->pay_mode == 'Cheque')
                            {{ $recept->issuing_bank ? $recept->issuing_bank : '' }}
                            {{ $recept->branch ? ', ' . $recept->branch : '' }}
                            {{ $recept->cheque_no ? ', ' . $recept->cheque_no : '' }}
                        @endif
                    </span>
                </p>
            </div>
            <span
                style="width:35px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                بنك
            </span>
        </div>

        <div class="d-flex justify-content-between aligin-items-center mb-1">
            <span
                style="width:100px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                Narration</span>
            <div class="w-100" style="border-bottom:1px dashed #111;">
                <p
                    style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                    <span style="">
                        {{ $recept->narration }}
                    </span>
                </p>
            </div>
            <span
                style="width:35px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                كون
            </span>
        </div>

        <div class="d-flex justify-content-between aligin-items-center mt-3">
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span
                    style="width:230px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    Receiver's Sign
                </span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p
                        style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                        <span style="  text-transform: uppercase !important;">

                        </span>
                    </p>
                </div>
                <span
                    style="width:150px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    علامة المتلقي
                </span>
            </div>
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span
                    style="width:100px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;"
                    class="pl-2">
                    Signature
                </span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p
                        style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">

                    </p>
                </div>
                <span
                    style="width:50px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    إمضاء
                </span>
            </div>
        </div>


    </div>

    <div class="container mt-1 ">
        @if ($recept->voucher_file)
            @php
                $imageExtensions = ['png', 'jpeg', 'gif', 'jpg'];
                $pdfExtensions = ['pdf'];
            @endphp


            @if (in_array($recept->extension, $pdfExtensions))
                {{-- <div class="row justify-content-center">
            <a href="{{ Storage::url('upload/documents/' . $recept->voucher_file) }}">
            <div class="col-auto">
                <img src="{{asset('icon/pdf-download-icon-2.png')}}" alt="Image" class="img-fluid">
            </div>
            </a>
        </div> --}}

                <div class="row justify-content-center">
                    <a href="{{ Storage::url('upload/documents/' . $recept->voucher_file) }}" target="_blank">View
                        <img src="{{ asset('icon/pdf-download-icon-2.png') }}" alt="Image" class="img-fluid"
                            style="height: 50px"></a>
                    {{-- <iframe src="{{ Storage::url('upload/documents/' . $recept->voucher_file) }}" width="100%" height="600px" frameborder="0"></iframe> --}}
                </div>
            @else
                <div class="row justify-content-center">
                    <div class="col-auto">
                        <a href="{{ asset('storage/upload/documents/' . $recept->voucher_file) }}" target="_blank">
                            <img src="{{ asset('storage/upload/documents/' . $recept->voucher_file) }}"
                                alt="Image" class="img-fluid"></a>
                    </div>
                </div>
            @endif
        @endif
    </div>


    <div class="divFooter mb-1 ml-1 invoice-view-wrapper">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid"
                src="{{ asset('img/zikash-logo.png') }}" alt="" width="70"></span>
    </div>
</section>
<div class="img receipt-bg">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid"
        style="position: fixed; top: 420px; left: 200px; opacity: 0.3; width: 650px !important; height: 250px;"
        alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>

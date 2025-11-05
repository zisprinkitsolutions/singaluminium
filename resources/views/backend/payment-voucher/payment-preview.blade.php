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
    $whole = floor($payment->total_amount);
    $fraction = number_format($payment->total_amount - $whole, 2);
    $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
    $amount_in_word = $f->format($whole);
    $amount_in_word2 = $f->format($fraction * 100);
@endphp
<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse" style="padding-top: 5px;padding-right: 8px;">
        <div class="pr-1" style="margin-top: 5px;">
            <a href="#" class="close btn-icon btn btn-danger " data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true"><i class='bx bx-x' style="padding-bottom: 3px;"></i></span></a>
        </div>
        <div class="mr-1 w-100 pl-2 text-left" style="margin-top: 5px;">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">Payment</h4>
        </div>
    </div>
</section>
<section>
    <div class="receipt-voucher-hearder invoice-view-wrapper" style=" border: 1px solid; margin: 50px 20px; border-radius: 20px;">
        @include('layouts.backend.partial.modal-header-info')
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
                    <h3 style="color: #313131">PAYMENT VOUCHER</h3>
                </div>

            </div>
            <div class="col-4 d-flex align-items-center justify-content-end">
                <div class="row">
                    <div class="col-6 text-right ">
                        <strong> No.:</strong>
                    </div>
                    <div class="col-6 col-left-padding">
                        <strong>{{ $payment->payment_no }}</strong>
                    </div>
                    <div class="col-6 text-right ">
                        <strong> Date:</strong>
                    </div>
                    <div class="col-6 col-left-padding">
                        <strong> {{ date('d/m/Y', strtotime($payment->date)) }}</strong>
                    </div>
                </div>
                <br><br>
            </div>
        </div>

        <div class="d-flex justify-content-between aligin-items-center mb-1">
            <span
                style="width:160px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                Paid to Mr./Ms.</span>
            <div class="w-100" style="border-bottom:1px dashed #111;">
                <p
                    style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                    {{ $payment->party->pi_name }}
                </p>
            </div>
            <span
                style="width:215px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                وردت من السيد / السيدة
            </span>
        </div>

        <div class="d-flex w-100">
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span
                    style="width:155px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    The Sum of Dhs:
                </span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p
                        style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px; text-transform: uppercase;">
                        {{ $amount_in_word }} Dirhams @if ($fraction > 0)
                            {{ '& ' . $amount_in_word2 . ' Fils' }}
                        @endif
                    </p>
                </div>
                <span
                    style="width:95px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    المبلغ درهم
                </span>
            </div>
        </div>

        <div class="d-flex w-100">
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span
                    style="width:305px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    By Cash / Cheque:
                </span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p
                        style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                        <span style="  text-transform: uppercase !important;">
                            {{ $payment->pay_mode }} {{ $payment->bank_name ? ',' . $payment->bank_name->name : '' }}
                        </span>
                    </p>
                </div>
                <span
                    style="width:190px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
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
                        @if ($payment->pay_mode == 'Cheque')
                            {{ $payment->deposit_date ? date('d/m/Y', strtotime($payment->deposit_date)) : '' }}
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
                    <span style=" ">
                        @if ($payment->pay_mode == 'Cheque')
                            {{ $payment->issuing_bank ? $payment->issuing_bank : '' }}
                            {{ $payment->branch ? ', ' . $payment->branch : '' }}
                            {{ $payment->cheque_no ? ', ' . $payment->cheque_no : '' }}
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
                Narration:</span>
            <div class="w-100" style="border-bottom:1px dashed #111;">
                <p
                    style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                    <span style="  ">
                        {{ $payment->narration }}
                    </span>
                </p>
            </div>
            <span
                style="width:35px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                كون
            </span>
        </div>

        <div class="d-flex justify-content-between aligin-items-center mt-1">
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
        @if ($payment->voucher_file)
            <h5 class="mb-2 fw-bold text-center">Support Documents </h5>
            @php
                $imageExtensions = ['png', 'jpeg', 'gif', 'jpg'];
                $pdfExtensions = ['pdf'];
            @endphp


            @if (in_array($payment->extension, $pdfExtensions))
                {{-- <div class="row justify-content-center">
            <a href="{{ Storage::url('upload/documents/' . $recept->voucher_file) }}">
            <div class="col-auto">
                <img src="{{asset('icon/pdf-download-icon-2.png')}}" alt="Image" class="img-fluid">
            </div>
            </a>
        </div> --}}

                <div class="row justify-content-center">
                    <a href="{{ Storage::url('upload/documents/' . $payment->voucher_file) }}" target="_blank">View
                        <img src="{{ asset('icon/pdf-download-icon-2.png') }}" alt="Image" class="img-fluid"
                            style="height: 50px"></a>
                    {{-- <iframe src="{{ Storage::url('upload/documents/' . $payment->voucher_file) }}" width="100%" height="600px" frameborder="0"></iframe> --}}
                </div>
            @else
                <div class="row justify-content-center">
                    <div class="col-auto">
                        <a href="{{ asset('storage/upload/documents/' . $payment->voucher_file) }}" target="_blank">
                            <img src="{{ asset('storage/upload/documents/' . $payment->voucher_file) }}"
                                alt="Image" class="img-fluid"></a>
                    </div>
                </div>
            @endif
        @endif
    </div>

    {{-- ______ Action Buttons ______ --}}
    {{-- <div class="d-flex flex-row-reverse mb-2" style="padding-top: 5px;padding-right: 8px;"> --}}
    <div class="d-flex justify-content-center align-items-center flex-row-reverse mt-2 mb-2 "
        style="padding-top: 5px;padding-right: 8px;">
        <div class="print-hideen" >
            <a href="#" onclick="window.print();"
                class="btn btn-icon btn-secondary custom-action-btn" data-bs-toggle="tooltip" data-bs-placement="bottom"
                title="Print Now"><i class="bx bx-printer"></i> Print
            </a>
            </div>
        <div class="print-hideen" style="">
            <a href="{{ route('temp-payment-voucher-delete', $payment->id) }}"
                class="btn btn-danger custom-action-btn"
                onclick="event.preventDefault(); deleteAlert(this, 'About to delete invoice. Please, confirm?');" title="Delete Now">
                <i class="bx bx-trash"></i> Delete
            </a>
        </div>
        @if ($payment->is_authorize == 0)
            <div class="print-hideen" style="">
                <a href="{{ route('payment-voucher-authorize', $payment) }}"
                    class="btn btn-success custom-action-btn" onclick="return confirm('Authorize! Confirm?')"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Authorize" title="Approve Now">
                    <i class="bx bx-check"></i> Approve
                </a>
            </div>
        @else
            <div class="print-hideen" style="">
                <a href="{{ route('payment-voucher-approve', $payment) }}" class="btn btn-success custom-action-btn"
                    onclick="event.preventDefault(); deleteAlert(this, 'About to approve. Please, confirm?', 'approve');"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Approve" title="Approve Now">
                    <i class="bx bx-check"></i> Approve
                </a>
            </div>
        @endif
        <div class="print-hideen" style="">
            <a href="#" class="btn btn-primary custom-action-btn payment-edit" id="{{ $payment->id }}"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" title="Edit Now">
                <i class="bx bx-edit"></i> Edit
            </a>
        </div>
    </div>
    {{-- ______ End: Action Buttons --}}

    <div class="divFooter mb-1 ml-1 footer-margin invoice-view-wrapper">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid"
                src="{{ asset('img/zikash-logo.png') }}" alt="" width="150"></span>
    </div>
</section>
<div class="img receipt-bg">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid"
        style="position: fixed; top: 450px; left: 220px; opacity: 0.3; width: 600px !important; height: 200px;"
        alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>

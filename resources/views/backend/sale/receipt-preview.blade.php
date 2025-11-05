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
    <div class="d-flex flex-row-reverse">
        <div class="pr-1" style="margin-top: 5px;">
            <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"
                title="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a>
        </div>
        <div class="w-100">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">Receipt</h4>
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
                        <td class="td-top-border td-right-border">{{ number_format($whole) }}</td>
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
                        <strong> Date:</strong>
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
                style="width:280px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                Received from Mr./Ms.</span>
            <div class="w-100" style="border-bottom:1px dashed #111;">
                <p
                    style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                    <td> {{ $recept->name == null ? $recept->party->pi_name : $recept->name }}</td>

                </p>
            </div>
            <span
                style="width:230px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
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
                        {{ $recept->deposit_date ? date('d/m/Y', strtotime($recept->deposit_date)) : '' }}
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
                        {{ $recept->issuing_bank ? $recept->issuing_bank : '' }}
                        {{ $recept->branch ? ', ' . $recept->branch : '' }}
                        {{ $recept->cheque_no ? ', ' . $recept->cheque_no : '' }}

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


        <div class="mb-1 d-none" id="payment-part">
            <form action="{{ route('payment-change', $recept) }}" method="POST">
                @csrf
                <div class="row ">

                    <div class="col-md-3">
                        <select name="payment_mode" class="form-control" id="payment-mode">
                            <option value="">Change Payment Mode...</option>
                            @foreach ($modes as $mode)
                                <option value="{{ $mode->title }}">{{ $mode->title }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-info">Save</button>
                    </div>
                </div>
            </form>
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

<div class="d-flex flex-row-reverse justify-content-center align-items-center py-2">
    <div class="" style="">
        <a href="#" onclick="window.print();" class="btn btn-icon btn-secondary custom-action-btn">
            <i class="bx bx-printer" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Print Now"></i> Print
        </a>
    </div>

    <div class="" style="">
        <a href="{{ route('receipt-delete', $recept) }}" class="btn btn-icon custom-action-btn btn-danger" title="Delete"
            onclick="event.preventDefault(); deleteAlert(this, 'About to delete receipt voucher. Please, confirm?');">
            <i class="bx bx-trash"></i> Delete
        </a>
    </div>

    @if ($recept->status == 'Realised')
        <!--<div class="" style="margin-top: 5px;padding-right: 3px;"><a href="#" disabled class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Realised"><i class="fa fa-external-link" style="font-size:17px"></i></a></div>-->
    @else
        <div class="" style=""><a href="{{ route('receipt-realised', $recept) }}"
                class="btn btn-icon custom-action-btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom"
                title="Realised"><i class="fa fa-external-link" style=""></i> Realised</a></div>
        <div class="" style="white-space: nowrap;"><a href="#"
                class="btn btn-icon custom-action-btn btn-info " data-toggle="modal" data-target="#notSubmit"
                title="Not Submited"><i class="fa fa-ban" style=""></i> Not Submited</a></div>
        <div class="" style=""><a href="{{ route('receipt-declined', $recept) }}"
                class="btn btn-icon custom-action-btn {{ $recept->status == 'Declined' ? 'btn-warning' : 'btn-danger' }}"
                title="Declined" style="{{ $recept->status == 'Declined' ? 'pointer-events: none' : '' }}"><i
                    class="fa fa-hourglass-start" style=""></i> Declined</a></div>
    @endif

    <div class="" style="">
        <a href="#" class="btn btn-icon custom-action-btn {{--btn-danger--}} btn-primary change-payment" onclick="togglePayment()"
            title="Change Payment Mode">
            Change Payment
        </a>
    </div>
</div>

<div class="img receipt-bg">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid"
        style="position: fixed; top: 420px; left: 200px; opacity: 0.3; width: 650px !important; height: 250px;"
        alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>


<div class="modal fade" id="notSubmit" tabindex="-1" role="dialog" aria-labelledby="notSubmitLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Next Deposit Date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ route('nex-deposit-receipt', $recept) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-9">
                            <input type="text" name="deposit_date" class="form-control datepicker"
                                placeholder="Next Deposit Date" required>
                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn btn-sm btn-info">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

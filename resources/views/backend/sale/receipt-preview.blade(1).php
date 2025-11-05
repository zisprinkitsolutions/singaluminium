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
</style>
@php
    $whole = floor($recept->total_amount);
    $fraction = number_format($recept->total_amount - $whole, 2);
    $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
    $amount_in_word = $f->format($whole);
    $amount_in_word2 = $f->format($fraction);
@endphp
<section class="print-hideen border-bottom">
    <div class="d-flex flex-row-reverse">
        <div class="py-1 pr-1">

            <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true"><i class='bx bx-x'></i></span></a>
        </div>
        <div class="py-1 pr-1"><a href="#" onclick="window.print();" class="btn btn-icon btn-secondary"><i
                    class="bx bx-printer"></i></a></div>

        @if ($recept->status == 'Realised')
            <div class="py-1 mr-1"><a href="#" disabled class="btn btn-sm btn-icon btn-success">Realised</a></div>
        @else
            <div class="py-1 mr-1"><a href="{{ route('receipt-realised', $recept) }}"
                    class="btn btn-sm btn-icon btn-success">Realised</a></div>
            <div class="py-1 mr-1" style="white-space: nowrap;"><a href="#"
                    class="btn btn-sm btn-icon btn-warning " data-toggle="modal" data-target="#notSubmit">Not
                    Submited</a></div>
            <div class="py-1 mr-1"><a href="{{ route('receipt-declined', $recept) }}"
                    class="btn btn-sm btn-icon {{ $recept->status == 'Declined' ? 'btn-secondary' : 'btn-danger' }}"
                    style="{{ $recept->status == 'Declined' ? 'pointer-events: none' : '' }}">Declined</a></div>
        @endif

        <div class="py-1 pr-1 w-100 pl-2">
            <h4>Receipt</h4>
        </div>
    </div>
</section>
@include('layouts.backend.partial.modal-header-info')
<section id="widgets-Statistics">
    <div class="row mx-1">
        <div class="col-12 text-right">
            <strong>Date :</strong> {{ date('d/m/Y', strtotime($recept->date)) }}
        </div>
    </div>
    <div class="text-center invoice-view-wrapper student_profle-print">
        <h2 style="color: #313131">Receipt</h2>
    </div>
    <div class="payment-voucher px-2 pt-1 pb-4">
        <div class="d-flex justify-content-between aligin-items-center mb-1">
            <span
                style="width:140px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                Paid To:</span>
            <div class="w-100" style="border-bottom:1px dashed #111;">
                <p
                    style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                    {{ $recept->party->pi_name }} </p>
            </div>
        </div>



        <div class="d-flex w-100">
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span
                    style="width:140px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">Payment
                    No:</span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p
                        style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                        {{ $recept->receipt_no }} </p>
                </div>
            </div>
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span
                    style="width:100px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    Project :</span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p
                        style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                        {{ $recept->job_project ? $recept->job_project->project_name : '' }}</p>
                </div>
            </div>
        </div>



        <div class="d-flex justify-content-between aligin-items-center mb-1">
            <span
                style="width:140px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                Sum of Amount:</span>
            <div class="w-100" style="border-bottom:1px dashed #111;">
                <p
                    style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                    <span style="  text-transform: uppercase !important;">{{ $amount_in_word }}@if ($fraction > 0)
                            {{ '& ' . substr($amount_in_word2, 10) }}
                        @endif {{ $currency->symbole }} </span> </p>
            </div>
        </div>

        <div class="d-flex w-100">
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span
                    style="width:140px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    Amount :</span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p
                        style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                        <span style="  text-transform: uppercase !important;">{{number_format( $recept->total_amount ,2)}}
                            {{ $currency->symbole }} </span> </p>
                </div>
            </div>
            {{-- <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span
                    style="width:50px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    Due :</span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p
                        style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                        {{ $recept->due_amount }} </p>
                </div>
            </div> --}}
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span
                    style="width:200px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                    Payment Mode :</span>
                <div class="w-100" style="border-bottom:1px dashed #111;">
                    <p
                        style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                        {{ $recept->pay_mode }} </p>
                </div>
            </div>
        </div>



        @if ($recept->pay_mode == 'Cheque')
            <div class="d-flex w-100">
                <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                    <span
                        style="width:140px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                        Issuing Bank :</span>
                    <div class="w-100" style="border-bottom:1px dashed #111;">
                        <p
                            style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                            {{ $recept->issuing_bank }} </p>
                    </div>
                </div>
                <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                    <span
                        style="width:100px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                        Branch :</span>
                    <div class="w-100" style="border-bottom:1px dashed #111;">
                        <p
                            style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                            {{ $recept->branch }} </p>
                    </div>
                </div>
            </div>

            <div class="d-flex w-100">
                <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                    <span
                        style="width:130px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                        Cheque No:</span>
                    <div class="w-100" style="border-bottom:1px dashed #111;">
                        <p
                            style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                            {{ $recept->cheque_no }} </p>
                    </div>
                </div>

                <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                    <span
                        style="width:150px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">
                        Deposit Date:</span>
                    <div class="w-100" style="border-bottom:1px dashed #111;">
                        <p
                            style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">
                            {{ date('d/m/Y', strtotime($recept->deposit_date)) }} </p>
                    </div>
                </div>
            </div>
        @endif


        @foreach ($recept->items as $item)
        @endforeach



    </div>

    <div class="invoice-view-wrapper">
        <div class="row " style="margin-top:170px">
            <div class="col-6 text-center">
                <span
                    style=" color:#313131;font-size:15px;font-weight:bold; line-height:23px !important; border-top:2px solid black">
                    Authorized Signature </span>

            </div>
            <div class="col-6 text-center">
                <span
                    style=" color:#313131;font-size:15px;font-weight:bold; line-height:23px !important; border-top:2px solid black">
                    Customer Signature</span>

            </div>
        </div>
    </div>

    <div class="divFooter mb-1 ml-1 invoice-view-wrapper">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid"
                src="{{ asset('img/zikash-logo.png')}}" alt="" width="70"></span>
    </div>
</section>

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
                            <input type="text" name="deposit_date" class="form-control datepicker" placeholder="Next Deposit Date" required>
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

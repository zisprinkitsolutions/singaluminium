

<style>
    html, body {
        height:100%;
        overflow: hidden;
    }
    thead {
    background: #8d8888;
    color: #fff !important;
    height: 30px;
}
</style>
<section class="print-hideen border-bottom">
    <div class="d-flex flex-row-reverse">
        <div class="py-1 pr-1">
            <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a>
        </div>
        <div class="py-1 pr-1"><a href="#" onclick="window.print();" class="btn btn-icon btn-secondary"><i class="bx bx-printer"></i></a></div>
    </div>
</section>
@include('layouts.backend.partial.modal-header-info')
<section id="widgets-Statistics">
    <div class="text-center invoice-view-wrapper student_profle-print">
        <h4 style="color: #313131">Receipt</h4>
    </div>

    <div class="receipt-voucher px-2 pt-1 pb-4">
        <div class="d-flex justify-content-between aligin-items-center mb-1">
            <span style="width:130px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;"> Receipt Form:</span>
            <div class="w-100"  style="border-bottom:1px dashed #111; padding-left:20px;">
                <p style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">  {{ $recept->party->pi_name}}   </p>
            </div>
        </div>

        <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
            <span style="width:110px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;"> Receipt No:</span>
            <div class="w-100"  style="border-bottom:1px dashed #111; padding-left:20px;">
                <p style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">   {{ $recept->receipt_no}}  </p>
            </div>
        </div>

        <div class="d-flex justify-content-between aligin-items-center mb-1">
            <span style="width:160px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;"> Sum of Ammount:</span>
            <div class="w-100"  style="border-bottom:1px dashed #111; padding-left:20px;">
                <p style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">  AED {{ $recept->total_amount}}    </p>
            </div>
        </div>
        <div class="d-flex w-100">
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span style="width:170px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;"> Payment Mode :</span>
                <div class="w-100"  style="border-bottom:1px dashed #111; padding-left:20px;">
                    <p style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">   {{ $recept->pay_mode}}    </p>
                </div>
            </div>
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span style="width:50px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;"> Date:</span>
                <div class="w-100"  style="border-bottom:1px dashed #111; padding-left:20px;">
                    <p style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">  {{ date('d/m/Y',strtotime($recept->date))}}  </p>
                </div>
            </div>
        </div>
        @foreach ($recept->items as $item)
        <div class="d-flex w-100">
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span style="width:150px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;"> Invoice No :</span>
                <div class="w-100"  style="border-bottom:1px dashed #111; padding-left:20px;">
                    <p style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px">  {{$item->sale?$item->sale->invoice_no:''}}  </p>
                </div>
            </div>
            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                <span style="width:80px !important; color:#313131;font-size:15px;font-weight:bold; line-height:23px !important;">Amount :</span>
                <div class="w-100"  style="border-bottom:1px dashed #111; padding-left:20px;">
                    <p style="margin:0 !important;padding:0!important;color:#313131;font-size:15px;font-weight:500 !important; padding-left:30px"> AED {{$item->Total_amount}}  </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>


    <div class="divFooter mb-1 ml-1 invoice-view-wrapper student_profle-print">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png')}}" alt="" width="150"></span>
    </div>
</section>


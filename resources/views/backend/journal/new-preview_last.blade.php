

<style>


    thead {
    background: #34465b;
    color: #fff !important;
    height: 30px;
}
</style>
<section class="print-hideen border-bottom" style="padding: 5px 15px;background: #34465b;">
    <div class="d-flex flex-row-reverse" style="padding: 0 8px;">
        <div class="" style="margin-top: 6px;"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x' style="padding-bottom: 6px;"></i></span></a></div>
        <div class="" style="padding-right: 3px;margin-top: 6px;"><a href="#" id="{{$journal->id}}" onclick="window.print();" class="btn btn-icon btn-success" title="Print"><i class='bx bx-printer'></i></a></div>
        <div class="" style="padding-right: 3px;margin-top: 6px;"><a href="{{route("journal-view-pdf", $journal->id)}}" class="btn btn-icon btn-primary" title="PDF"><i class='bx bxs-file-pdf'></i></a></div>
        {{-- <div class=""><a href="#" onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
        <div class="w-100">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">Journal</h4>
        </div>
    </div>
</section>
<section class="mt-4">
    @include('layouts.backend.partial.modal-header-info')
</section>
<section id="widgets-Statistics">

    <div class="row pt-2">
        <div class="col-md-12">
            <div class="">
                <div class="mx-2 mb-2">
                    <div class="row">
                            <div class="col-3">
                                <strong>Journal No:</strong>  {{ $journal->journal_no}}
                            </div>

                            <div class="col-2">
                                <strong>Date:</strong> {{ date('d/m/Y',strtotime($journal->date))}}
                            </div>

                            <div class="col-2">
                                <strong>Payment Mode:</strong> {{ $journal->pay_mode}}
                            </div>
                            <div class="col-3">
                                <strong>Party Name:</strong> {{ $journal->PartyInfo?$journal->PartyInfo->pi_name:''}}
                            </div>

                            <div class="col-2">
                                <strong>Amount:</strong> @if(!empty($currency->symbole)){{$currency->symbole}}@endif {{ number_format($journal->amount,2)}}
                            </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="border-botton">
                <div class="mx-2">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered border-botton">
                            <thead class="thead">
                                <tr class="text-center">
                                    {{-- <th>Date</th> --}}
                                    <th style="color:#fff" class="text-left pl-1">HEAD</th>
                                    <th style="color:#fff">Invoice No</th>
                                    <th style="color:#fff" class="text-right pr-1">Debit</th>
                                    <th style="color:#fff" class="text-right pr-1">Credit</th>
                                </tr>
                            </thead>

                            <tbody class="user-table-body">
                                    @foreach ($journal->records()->orderBy('transaction_type','DESC')->get() as $record)
                                    <tr class="text-center trFontSize">
                                        <td  class="text-left pl-1">{{$record->ac_sub_head?$record->ac_sub_head->name:$record->account_head }}</td>
                                        <td >{{$record->invoice_no }}</td>

                                        <td class="text-right pr-1">{{ $retVal = ($record->transaction_type=='DR')  ? $currency->symbole .' '.number_format($record->amount,2) : ''  }}</td>
                                        <td class="text-right pr-1">{{ $retVal = ($record->transaction_type=='CR') ?  $currency->symbole.' ' .number_format($record->amount,2) : ''  }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="border-bottom">
                                        <td colspan="4" class="text-center"> ( {{$journal->narration}} ) </td>

                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row pt-4">
        <div class="col-12 text-center">
            <h3>Supporting Document</h3>

        </div>
        @if (($journal->voucher_scan != '') && ($journal->voucher_scan2 != '') )
        <div class="col-6 text-center">
            <img src="{{asset('storage/upload/documents')}}/{{$journal->voucher_scan}}" class="img-fluid" style="height: 490px" alt="">
        </div>
        <div class="col-6 text-center">
            <img src="{{asset('storage/upload/documents2')}}/{{$journal->voucher_scan2}}" class="img-fluid" style="height: 490px" alt="">
        </div>
        @elseif(($journal->voucher_scan != '') && ($journal->voucher_scan2 == ''))
        <div class="col-12 text-center">
            <img src="{{asset('storage/upload/documents')}}/{{$journal->voucher_scan}}" class="img-fluid" style="height: 490px" alt="">

        </div>
        @elseif(($journal->voucher_scan == '') && ($journal->voucher_scan2 != ''))
        <div class="col-12 text-center">
            <img src="{{asset('storage/upload/documents2')}}/{{$journal->voucher_scan2}}" class="img-fluid" style="height: 490px" alt="">

        </div>
        @endif

    <div class="divFooter mb-1 ml-1 pl-2">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png')}}" alt="" width="70"></span>
    </div>
</section>


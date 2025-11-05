

<style>
    html, body {
        height:100%;
    }
    thead {
    background: #34465b;
    color: #fff !important;
    height: 30px;
}
@media print{
        .table tr th,
        .table tr td{
            color: #000000 !important;
            font-weight:500 !important;
        }
    }
</style>
<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse">

        <div class="pr-1" style="padding-top: 8px;padding-right: 22px !important;"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
        <div class="pr-1" style="padding: 8px;padding-right: 0.2rem !important;"><a href="#" onclick="window.print();" class="btn btn-icon btn-primary"><i class="bx bx-printer"></i></a></div>

        <div class="pr-1" style="padding-top: 8px;padding-right: 0.2rem !important;"><a href="{{route('purchase-expense.edit',$purchase_exp)}}" class="btn btn-sm btn-icon btn-success" style="padding: 6px 8px;margin-right: -8px;"><i class="bx bx-edit"></i></a></div>
        <div class="pr-1" style="padding-top: 9px;padding-right: 0.2rem !important;"><a href="{{route('purchase-expense.delete',$purchase_exp)}}" class="btn btn-sm btn-icon btn-danger" style="padding: 6px 8px;"><i class="bx bx-trash"></i></a></div>


        <div class="pr-1 w-100 pl-2" style="margin-top: 2px;">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">Expense</h4>
        </div>
        {{-- <div class="py-1 pr-1"><a href="#" onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
    </div>
</section>
<div class="receipt-voucher-hearder invoice-view-wrapper" style="margin: 50px 20px; border-radius: 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>
<section id="widgets-Statistics">

    <div class="row">
        <div class="col-md-12 text-center invoice-view-wrapper student_profle-print my-1">
            <h2>Expense - Bill</h2>
        </div>
        <div class="col-md-12">
            <div class="">
                <div class="mx-2 mb-2 pt-2">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-12">
                                    <strong>Payee:</strong> {{ $purchase_exp->party->pi_name}}
                                </div>
                                <div class="col-3">
                                    <strong>Address:</strong> {{ $purchase_exp->party->address}}
                                </div>

                                <div class="col-3">
                                    <strong>Attention:</strong> {{ $purchase_exp->attention}}
                                </div>
                                <div class="col-3">
                                    <strong>Contact No:</strong> {{ $purchase_exp->party->con_no}}
                                </div>

                                <div class="col-3">
                                    <strong>Bill No:</strong>  {{ $purchase_exp->purchase_no}}
                                </div>


                                <div class="col-3">
                                    <strong>Date:</strong> {{ date('d/m/Y',strtotime($purchase_exp->date))}}
                                </div>

                                <div class="col-3">
                                    <strong>Payment Mode:</strong> {{ $purchase_exp->pay_mode}}
                                </div>

                                <div class="col-3">
                                    <strong>Invoice No:</strong> {{ $purchase_exp->invoice_no}}
                                </div>


                                <div class="col-3">
                                    <strong>Amount:</strong> @if(!empty($currency->symbole)){{$currency->symbole}}@endif {{$purchase_exp->total_amount}}
                                </div>
                                @if ($purchase_exp->pay_mode=='Cheque')
                                <div class="col-3">
                                    <strong>Issuing Bank:</strong> {{$purchase_exp->issuing_bank}}
                                </div>
                                <div class="col-3">
                                    <strong>Branch:</strong> {{$purchase_exp->bank_branch}}
                                </div>
                                <div class="col-3">
                                    <strong>Cheque No:</strong> {{$purchase_exp->cheque_no}}
                                </div>
                                <div class="col-3">
                                    <strong>Deposit Date:</strong> {{date('d/m/Y', strtotime($purchase_exp->deposit_date))}}
                                </div>

                                @endif
                            </div>
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
                                <tr >
                                    <th>Account Head</th>
                                    <th>Item Description</th>
                                    <th class="text-right">Amount <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                    <th class="text-right">Vat <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                    <th class="text-right">Total Amount <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                </tr>
                            </thead>

                            <tbody class="user-table-body">
                                  @foreach ($items as $item)
                                  <tr>
                                    @if ($item->head_sub)
                                    <td class="pl-1 text-center"><pre class="text-center border-0">{{$item->head_sub->name??''}}</pre></td>
                                    @else
                                    <td class="pl-1 text-center"><pre class="text-center border-0">{{$item->head->fld_ac_head??''}}</pre></td>
                                    @endif
                                    <td class="pl-1 text-center"><pre class="text-center border-0">{{$item->item_description}}</pre></td>
                                    <td class="text-right">{{$item->amount}}</td>
                                    <td class="text-right">{{$item->vat}}</td>
                                    <td class="text-right">{{$item->total_amount}}</td>
                                 </tr>

                                  @endforeach
                                  <tr>
                                    <td colspan="3" rowspan="3"><strong>Narration: {{$purchase_exp->narration}}</strong></td>
                                    <td>Total Amount</td>
                                    <td  class="text-right">{{$purchase_exp->amount}}</td>
                                  </tr>
                                  <tr>

                                    <td>VAT</td>
                                    <td  class="text-right">{{$purchase_exp->vat}}</td>
                                  </tr>
                                  <tr>

                                    <td>Total Amount</small></td>
                                    <td  class="text-right">{{$purchase_exp->total_amount}}</td>
                                  </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12  text-center">
            <h3 class="text-center">Supporting Document</h3>
        </div>
        <div class="row p-2" id="documents">
            @if($purchase_exp->documents->count() > 0)
                @foreach ($purchase_exp->documents as $document)
                <div class="col-md-2 text-center py-1 px-4 print-hideen document-file" id="document-{{$document->id}}">
                    <button class="remove-document py-1 d-none" id={{$document->id}}>
                        <i class="bx bx-trash text-danger"></i>
                    </button>
                    @if ($document->ext=='pdf')
                    <a href="{{ asset('storage/upload/purchase-expense/' . $document->file_name) }}" target="blank">
                        <img src="{{asset('icon/pdf-download-icon-2.png')}}" class="img-fluid" style="width:100%;" alt="{{$document->ext}}">
                    </a>
                    @else
                    <a href="{{ asset('storage/upload/purchase-expense/' . $document->file_name) }}" target="blank">
                        <img src="{{ asset('storage/upload/purchase-expense/' . $document->file_name) }}" class="img-fluid" style="width:100%;" alt="{{$document->ext}}">
                    </a>
                    @endif
                </div>
                @endforeach
            @endif
        </div>
        @if (isset($new))
        @else
        <div class="col-md-12 text-center mb-1">
            <a href="{{route('purchase-authorize',$purchase_exp)}}" class="btn btn-info btn-sm" onclick="return confirm('about to authorize purchase. Please, Confirm?')"> Authorize </a>

        </div>
        @endif

    </div>




    <div class="divFooter mb-1 ml-1 footer-margin invoice-view-wrapper">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png')}}" alt="" width="150"></span>
    </div>
</section>

<div class="img receipt-bg invoice-view-wrapper footer-margin">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid" style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;" alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>

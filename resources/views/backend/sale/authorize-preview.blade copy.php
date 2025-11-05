
<section class="print-hideen border-bottom">
    <div class="d-flex flex-row-reverse">
        <div class="py-1 pr-1"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
                <div class="py-1 pr-1"><a href="#" onclick="window.print();" class="btn btn-icon btn-secondary"><i class="bx bx-printer"></i></a></div>
                <div class="py-1 pr-1"><a href="{{route('sale.delete',$sale)}}" class="btn btn-sm btn-icon btn-danger">Delete</a></div>

                <div class="py-1 pr-1 w-100 pl-2">
                    <h4> {{ $sale->invoice_type=="Tax Invoice"? 'Tax Invoice':'Proforma Invoice'}}</h4>
                </div>
            </div>
</section>
@include('layouts.backend.partial.modal-header-info')
<section id="widgets-Statistics">
    <div class="row">
        <div class="col-md-12">
            <div class=" print-content text-center">
                <h2> {{ $sale->invoice_type=="Tax Invoice"? 'Tax Invoice':'Proforma Invoice'}}</h2>
            </div>
            <div class="">
                <div class="mx-2 mb-2">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-12">
                                    <strong>Party Name:</strong> {{ $sale->party->pi_name}}
                                </div>
                                <div class="col-3">
                                    <strong>Address:</strong> {{ $sale->party->address}}
                                </div>
                                <div class="col-3">
                                    <strong>Attention:</strong> {{ $sale->party->con_person}}
                                </div>
                                <div class="col-3">
                                    <strong>Contact No:</strong> {{ $sale->party->con_no}}
                                </div>
                                <div class="col-3">
                                    <strong>Date:</strong> {{ date('d/m/Y',strtotime($sale->date))}}
                                </div>
                                <div class="col-3">
                                    <strong>Payment Mode:</strong> {{ $sale->pay_mode}}
                                </div>
                                <div class="col-3">
                                    <strong>Invoice No:</strong>{{ $sale->invoice_no}}
                                </div>
                                <div class="col-3">
                                    <strong>Amount:</strong> @if(!empty($currency->symbole)){{$currency->symbole}}@endif {{ $sale->total_amount}}
                                </div>
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
                                    {{-- <th>Date</th> --}}
                                    <th>Item Description</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Rate</th>
                                    <th>Amount <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                </tr>
                            </thead>

                            <tbody class="user-table-body">
                                  @foreach ($sale->items as $item)
                                 <tr>
                                    <td>{{$item->item_description}}</td>
                                    <td>{{$item->qty}}</td>
                                    <td>{{$item->unit->name}}</td>
                                    <td>{{$item->rate}}</td>
                                    <td>{{$item->amount}}</td>
                                 </tr>

                                  @endforeach
                                  <tr>
                                    <td colspan="3"></td>
                                    <td>Total Amount</td>
                                    <td>{{$sale->amount}}</td>
                                  </tr>
                                  @if ($sale->invoice_type=="Tax Invoice")
                                  <tr>
                                    <td colspan="3"></td>
                                    <td>Vat <small>({{$standard_vat_rate}}%)</small></td>
                                    <td>{{$sale->vat}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="3"></td>
                                    <td>Total Amount</small></td>
                                    <td>{{$sale->total_amount}}</td>
                                  </tr>
                                  @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if (isset($new))
        @else
        <div class="col-md-12 text-center">
            <a href="{{route('sale-authorize',$sale)}}" class="btn btn-info btn-sm" onclick="return confirm('about to authorize purchase. Please, Confirm?')"> Authorize </a>

        </div>
        @endif
    </div>



    <div class="divFooter mb-1 ml-1">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png')}}" alt="" width="150"></span>
    </div>
</section>


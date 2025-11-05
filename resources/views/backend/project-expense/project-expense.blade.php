<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse">

        <div class="pr-1" style="padding-top: 7px;padding-right: 24px !important;"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
        <div class="pr-1 w-100 pl-2">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">{{$special_sub_head?$special_sub_head->name:$special_head->fld_ac_head}} Expense Allocation</h4>
        </div>
        {{-- <div class="py-1 pr-1"><a href="#" onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
    </div>
</section>
<div class="receipt-voucher-hearder invoice-view-wrapper" style="margin: 50px 20px; border-radius: 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>
<table class="table table-sm">
    <thead>
        <tr>
            <th>Date</th>
            <th>Purchase No</th>
            <th>Total QTY</th>
            <th>Total Amount</th>
            <th>Expense QTY</th>
            <th>Expense Amount</th>
            <th>Available QTY</th>
            <th>Available Amount</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($head_record as $item)
            <tr id="{{$item->id}}">
                <td>{{date('d/m/Y', strtotime($item->purchase->date))}}</td>
                <td>{{$item->purchase->purchase_no}}</td>
                <td>{{$item->qty}}</td>
                <td>{{number_format($item->amount,2)}}</td>
                <td>{{$item->out_qty}}</td>
                <td>{{number_format($item->out_amount,2)}}</td>
                <td>{{$item->qty-$item->out_qty}}</td>
                <td>{{number_format($item->amount-$item->out_amount,2)}}</td>
                <td>
                    @if ($item->qty != $item->out_qty)
                        <button style="padding: 5px;" type="button" class="btn btn-sm btn-success" title="Project Expense Add" onclick="BtnProjectItem(this)">
                            <i class="bx bx-plus" style="color: white;"></i>
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
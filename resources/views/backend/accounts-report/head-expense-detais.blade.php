<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse">

        <div class="pr-1" style="padding-top: 7px;padding-right: 24px !important;"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
        <div class="pr-1" style="padding-top: 5px;padding-right: 3px !important;"><a href="#" onclick="window.print();" class="btn btn-icon btn-success"><i class="bx bx-printer"></i></a></div>
        <div class="pr-1 w-100 pl-2">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">{{$special_sub_head?$special_sub_head->name:$special_head->fld_ac_head}}</h4>
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
            <th>Supplier</th>
            <th>Total QTY</th>
            <th>Total Amount</th>
            <th>Project</th>
            <th>Expense QTY</th>
            <th>Expense Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($head_record as $item)
            <tr>
                <td>{{date('d/m/Y', strtotime($item->purchase->date))}}</td>
                <td>{{$item->purchase->purchase_no}}</td>
                <td>{{$item->purchase->party->pi_name}}</td>
                <td>{{$item->qty}}</td>
                <td>{{number_format($item->amount,2)}}</td>
                <td>
                    @if ($item->sub_head_id)
                        @foreach ($item->project_sub_head($special_sub_head->id,$item->purchase->id) as $project_head_expense)
                            {{$project_head_expense->project->name}} <br>
                        @endforeach
                    @else
                        @foreach ($item->project_head($special_head->id,$item->purchase->id) as $project_head_expense)
                            {{$project_head_expense->project->name}} <br>
                        @endforeach
                    @endif
                </td>
                <td>{{$item->out_qty}}</td>
                <td>{{number_format($item->out_amount,2)}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
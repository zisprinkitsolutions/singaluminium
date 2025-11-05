{{-- <div class="modal-header" style="padding: 5px 18px;background:#364a60;">
    <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">Project Expense Assign</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div> --}}
<div class="card-body bg-white">
        @include('backend.inventory.sub-head', ['activeMenu' => 'pendding-inventory'])
        <div class="row">
            <div class="col-12 p-0">
                <table class="table table-bordered table-sm table-striped 2filter-table thermal-table" id="print-table"  style="width: 850px !important;">

                    <thead class="thead">
                        <tr class="trFontSize text-center">
                            <th>Date</th>
                            <th>Account Head </th>
                            <th>Total QTY</th>
                            <th> Unit </th>
                        </tr>
                    </thead>
                    <tbody id="purch-body">
                        @foreach ($inventory as $key => $inv)
                            <tr class="temp-inventory-view text-center trFontSize" id="{{$inv->id}}">
                                <td>{{date('d/m/Y', strtotime($inv->date))}}</td>
                                <td>{{$inv->sub_account_head?$inv->sub_account_head->name:$inv->account_head->fld_ac_head}}</td>
                                <td>{{$inv->items->sum('qty')}}</td>
                                <td> {{optional($inv->sub_account_head)->unit ? optional($inv->sub_account_head)->unit->name :'-'}} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
</div>

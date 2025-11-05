
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
        
        <div class="pr-1 w-100 pl-2" style="margin-top: 2px;">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">Expense Allocation Edit</h4>
        </div>
    </div>
</section>
<form action="{{route('expense-allocation.update', $allocation->id)}}" method="POST" class="m-2 col-md-6">
    @csrf
    @method('PATCH')
    <input type="hidden" value="{{$expense->head_id}}" name="accout_head_id">
    <input type="hidden" value="{{$expense->sub_head_id}}" name="sub_head_id">
    <input type="hidden" value="{{$expense->amount - $expense->out_amount}}" name="max_amount" id="max_amount">
    <input type="hidden" value="{{$expense->qty - $expense->out_qty}}" name="max_qty" id="max_qty">
    <input type="hidden" value="{{$expense->id}}" name="purchase_expense_id" id="purchase_expense_id">
    <div class="row">
        <div class="col-md-6">
            <input type="text" required value="{{ date('d/m/Y', strtotime($allocation->date)) }}" class="form-control inputFieldHeight datepicker" name="date" placeholder="dd/mm/yyyy">
        </div>
    </div>
    <table class="table table-sm ">
        <thead>
            <tr>
                <th>Project</th>
                <th style="width: 50px;">QTY</th>
                <th style="width: 100px;">Amount</th>
                <th class="NoPrint" style="width: 20px; padding: 2px;">
                    <button type="button" class="btn btn-sm btn-success task_addBtn"style="border: 1px solid green; color: #fff; border-radius: 10px;padding: 5px;" onclick="task_BtnAdd()">
                        <i class="bx bx-plus" style="color: white;margin-top: -5px;"></i>
                    </button>
                </th>
            </tr>
        </thead>
        <tbody id="task_TBody">
            @foreach ($allocation->items as $_item)
                <tr class="text-center invoice_row">
                    <td>
                        <select name="project_id[]" id="project_id" class="w-100 project_id inputFieldHeight2" >
                            <option value="">Select...</option>
                            @foreach ($project_lists as $item)
                                <option value="{{ $item->id }}" {{$_item->project_id==$item->id?'selected':''}}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <div>
                            <input type="number" step="any" name="task_qty[]" required step="any" placeholder="QTY" class="text-center form-control inputFieldHeight2 task_qty"style="width: 100%;height:36px;" value="{{$_item->qty}}">
                        </div>
                    </td>
                    <td>
                        <div class="d-flex justy-content-between align-items-center">
                            <input type="number" readonly step="any" name="task_amount[]" step="any" placeholder="Amount" class="text-center form-control inputFieldHeight2 task_amount"style="width: 100%;height:36px;" value="{{$_item->amount}}">
                        </div>
                    </td>
                    <td class="NoPrint text-center">
                        <button style="padding: 5px; margin: 4px;" type="button" class="btn btn-sm btn-danger" onclick="BtnDel(this)"><i class="bx bx-trash" style="color: white;margin-top: -5px;"></i></button>
                    </td>
                </tr>
            @endforeach
            <tr id="task_TRow" class="text-center invoice_row">
                <td>
                    <select name="project_id[]" id="project_id" class="w-100 project_id inputFieldHeight2" >
                        <option value="">Select...</option>
                        @foreach ($project_lists as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <div>
                        <input type="number" step="any" name="task_qty[]" required step="any" placeholder="QTY" class="text-center form-control inputFieldHeight2 task_qty"style="width: 100%;height:36px;">
                    </div>
                </td>
                <td>
                    <div class="d-flex justy-content-between align-items-center">
                        <input type="number" readonly step="any" name="task_amount[]" step="any" placeholder="Amount" class="text-center form-control inputFieldHeight2 task_amount"style="width: 100%;height:36px;">
                    </div>
                </td>
                <td class="NoPrint text-center">
                    <button style="padding: 5px; margin: 4px;" type="button" class="btn btn-sm btn-danger" onclick="BtnDel(this)"><i class="bx bx-trash" style="color: white;margin-top: -5px;"></i></button>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td class="text-center" style="color: black">TOTAL AMOUNT <small class="text-danger">(Max amount allow: {{$expense->amount - $expense->out_amount}} And QTY: {{$expense->qty - $expense->out_qty}}  )</small></td>
                <td>
                    <input type="number" step="any" readonly id="task_total_qty" class="text-center inputFieldHeight2 form-control inputFieldHeight task_total_qty" name="task_total_amount" value="{{$allocation->items->sum('qty')}}" placeholder="QTY" >
                </td>
                <td>
                    <input type="number" step="any" readonly id="task_total_amount" class="text-center inputFieldHeight2 form-control inputFieldHeight task_total_amount" name="task_total_amount" value="{{$allocation->items->sum('amount')}}" placeholder="TOTAL " >
                </td>
            </tr>
        </tbody>
    </table>
    <div class="text-right">
        <button class="btn btn-success" type="submit">Update</button>
    </div>
</form>
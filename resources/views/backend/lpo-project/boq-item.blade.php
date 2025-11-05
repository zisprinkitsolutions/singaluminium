@foreach ($items as $key => $item)
    <tr class="text-center invoice_row" id="TRow">
        <td>
            <div class="d-flex justy-content-between align-items-center" >
                <input type="text" name="group-a[{{$key}}][description]" value="{{$item->item_description}}"  placeholder="Item Description" class="form-control inputFieldHeight description" required>
            </div>
        </td>
        <td>
            <input type="number" step="any" name="group-a[{{$key}}][qty]" step="any" value="{{$item->qty}}" placeholder="QTY" class="text-center form-control inputFieldHeight qty"style="width: 100%;" required>
        </td>
        <td>
            <input type="number" step="any" name="group-a[{{$key}}][sqm]" step="any" placeholder="SQM" value="{{$item->sqm}}" class="text-center form-control inputFieldHeight sqm"style="width: 100%;" required>
        </td>
        <td>
            <input type="number" step="any" name="group-a[{{$key}}][amount]" step="any" required placeholder="Rate" value="{{$item->rate}}" class="text-center form-control inputFieldHeight amount"style="width: 100%;">
        </td>

        <td>
            <input type="number" step="any" name="group-a[{{$key}}][sub_gross_amount]" required value="{{$item->total}}" class="text-center form-control sub_gross_amount inputFieldHeight" placeholder="Total Amount" style="width: 100%;" readonly>
        </td>
        <td class="NoPrint add_button text-center d-flex" style="margin-top: 5px;">
            <button type="button" class="bg-danger custom-btn" onclick="BtnDel(this)">
                <i class="bx bx-trash"></i>
            </button>
        </td>
    </tr>
@endforeach
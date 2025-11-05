<form action="{{route('subsidiary-store')}}" method="POST" id="subsidiary_store">
    @csrf
    <input type="hidden" value="{{$account_head}}" name="accout_head_id">
    <input type="hidden" value="{{$amount}}" name="max_amount" id="subsidiary_max_amount">
    <input type="hidden" value="{{$qty}}" name="max_qty" id="subsidiary_max_qty">
    <table class="table  table-sm " style="width: 50% !important">
        <thead>
            <tr>
                <th style="width: 25%">Company Name</th>
                <th style="width: 50px;">QTY</th>
                <th style="width: 100px;">Amount</th>
                <th class="NoPrint" style="width: 20px; padding: 2px;">
                    <button type="button" class="btn btn-sm btn-success task_addBtn"style="border: 1px solid green; color: #fff; border-radius: 10px;padding: 5px;" onclick="task_BtnAdd('#task_TRow', '#task_TBody','group-a')">
                        <i class="bx bx-plus" style="color: white;margin-top: -5px;"></i>
                    </button>
                </th>
            </tr>
        </thead>

        <tbody id="task_TBody">
            @foreach ($temp_pe as $temp)
                <tr class="text-center invoice_row">
                    <td class="td-width">
                        <select name="company_id[]" class="w-100 company_id inputFieldHeight2 common-select2" required>
                            <option value="">Select...</option>
                            @foreach ($projects as $item)
                                <option value="{{ $item->id }}" {{$item->id==$temp->company_id?'selected':''}}>{{ $item->company_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <div>
                            <input type="number" step="any" name="subsidiary_qty[]" required value="{{$temp->qty}}" step="any" placeholder="QTY" class="text-center form-control inputFieldHeight2 subsidiary_qty"style="width: 100%;height:36px;">
                        </div>
                    </td>
                    <td>
                        <div>
                            <input type="number" step="any" name="subsidiary_amount[]" required value="{{$temp->amount}}" step="any" placeholder="Amount" class="text-center form-control inputFieldHeight2 subsidiary_amount"style="width: 100%;height:36px;" >
                        </div>
                    </td>
                    <td class="NoPrint text-center">
                        <button style="padding: 5px; margin: 4px;" type="button" class="btn btn-sm btn-danger" onclick="subsidiary_Btn_Del(this)"><i class="bx bx-trash" style="color: white;margin-top: -5px;"></i></button>
                    </td>
                </tr>
            @endforeach
            <tr id="task_TRow" class="invoice_row d-none">
                <td class="td-width">
                    <select name="company_id[]" required class="form-control company_id account-head" disabled >
                        <option value="">Select....</option>
                        @foreach ($projects as $item)
                            <option value="{{ $item->id }}">{{ $item->company_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <div>
                        <input type="number" step="any" name="subsidiary_qty[]" required step="any" placeholder="QTY" class="text-center form-control inputFieldHeight2 subsidiary_qty"style="width: 100%;height:36px;" disabled>
                    </div>
                </td>
                <td>
                    <div class="d-flex justy-content-between align-items-center">
                        <input type="number" step="any" name="subsidiary_amount[]" step="any" placeholder="Amount" class="text-center form-control inputFieldHeight2 subsidiary_amount"style="width: 100%;height:36px;" disabled>
                    </div>
                </td>
                <td class="NoPrint text-center">
                    <button style="padding: 5px; margin: 4px;" type="button" class="btn btn-sm btn-danger" onclick="subsidiary_Btn_Del(this)"><i class="bx bx-trash" style="color: white;margin-top: -5px;"></i></button>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td class="text-right pr-1 " style="color: black"><small class="text-danger">(Max amount allow: {{$amount}} And QTY: {{$qty}}  )</small>TOTAL:</td>
                <td>
                    <input type="number" step="any" readonly id="subsidiary_total_qty" class="text-center inputFieldHeight2 form-control inputFieldHeight subsidiary_total_qty" name="subsidiary_total_qty" value="{{$temp_pe->sum('qty')}}" placeholder="QTY" >
                </td>
                <td>
                    <input type="number" step="any" readonly id="subsidiary_total_amount" class="text-center inputFieldHeight2 form-control inputFieldHeight subsidiary_total_amount" name="subsidiary_total_amount" value="{{$temp_pe->sum('amount')}}" placeholder="TOTAL " >
                </td>
            </tr>
        </tbody>
    </table>
    <div class="text-left">
        <button class="btn btn-success" type="submit">Save</button>
    </div>
</form>

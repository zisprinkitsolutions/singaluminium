<form action="{{route('inventory-project-expense-store')}}" method="POST" id="project_expense_store">
    @csrf
    <div style="width:12%;margin-bottom:5px;">
        <label for="">Date</label>
        <input type="text" value="{{ Carbon\Carbon::now()->format('d/m/Y') }}" class="form-control inputFieldHeight datepicker" name="date" placeholder="dd-mm-yyyy">
    </div>
    <input type="hidden" value="{{$account_head}}" name="accout_head_id">
    <input type="hidden" value="{{$qty-$assign_qty}}" name="max_qty" id="max_qty">
    <table class="table table-sm" style="">
        <thead>
            <tr>
                <th style="width: 20%">Project</th>
                <th style="width: 20%">Task</th>
                {{-- <th style="width: 20%">Task Item</th> --}}
                <th style="width: 10%;">QTY</th>
                <th class="NoPrint" style="width: 10%; padding: 2px;">
                    <button type="button" class="btn btn-sm btn-success task_addBtn"style="border: 1px solid green; color: #fff; border-radius: 10px;padding: 5px;" onclick="task_BtnAdd('#task_TRow', '#task_TBody','group-a')">
                        <i class="bx bx-plus" style="color: white;margin-top: -5px;"></i>
                    </button>
                </th>
            </tr>
        </thead>
        <tbody id="task_TBody">
            <tr id="task_TRow" class="text-center invoice_row d-none">
                <td class="td-width">
                    <select name="project_id[]" id="project_id" class="w-100 project_id inputFieldHeight2" disabled required>
                        <option value="">Select...</option>
                        @foreach ($projects as $item)
                            <option value="{{ $item->id }}">{{ $item->project_id?$item->new_project->name:$item->project_name }}-{{$item->project_code}}</option>
                        @endforeach
                    </select>
                </td>
                <td class="td-width">
                    <select name="task_id[]" class="form-control task_id" disabled >
                        <option value="">Select....</option>
                    </select>
                </td>
                {{-- <td class="td-width">
                    <select name="task_item_id[]" class="form-control task_item" disabled >
                        <option value="">Select....</option>
                    </select>
                </td> --}}
                <td>
                    <div>
                        <input type="number" step="any" name="task_qty[]" required step="any" placeholder="QTY" class="text-center form-control inputFieldHeight2 task_qty"style="width: 100%;height:36px;" disabled>
                    </div>
                </td>
                <td class="NoPrint text-center">
                    <button style="padding: 5px; margin: 4px;" type="button" class="btn btn-sm btn-danger" onclick="BtnDel(this)"><i class="bx bx-trash" style="color: white;margin-top: -5px;"></i></button>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td class="text-right pr-1 " colspan="2" style="color: black"><small class="text-danger">(Available QTY: {{$qty}} {{$assign_qty>0?' But Already Assigned QTY '. $assign_qty:''}}) </small>TOTAL QTY: </td>
                <td>
                    <input type="number" step="any" readonly id="task_total_qty" class="text-center inputFieldHeight2 form-control inputFieldHeight task_total_qty" name="task_total_qty" value="" placeholder="QTY" >
                </td>
            </tr>
        </tbody>
    </table>
    <div class="text-left">
        <button class="btn btn-success" type="submit">Save</button>
    </div>
</form>
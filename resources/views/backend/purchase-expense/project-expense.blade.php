<form action="{{route('cogs-project-expense-store')}}" method="POST" id="cogs_project_expense_store">
    @csrf
    <input type="hidden" value="{{$account_head}}" name="accout_head_id">
    <input type="hidden" value="{{$amount}}" name="max_amount" id="cogs_max_amount">
    <input type="hidden" value="{{$qty}}" name="max_qty" id="cogs_max_qty">
    <table class="table  table-sm ">
        <thead>
            <tr>
                <th style="width: 25%">Project</th>
                <th style="width: 25%">Task</th>
                {{-- <th style="width: 25%">Task Item</th> --}}
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
                        <select name="project_id[]" class="w-100 project_id inputFieldHeight2 common-select2" required>
                            <option value="">Select...</option>
                            @foreach ($projects as $item)
                                <option value="{{ $item->id }}" {{$item->id==$temp->project_id?'selected':''}}>{{ $item->project_id?$item->new_project->name:$item->project_name }}-{{$item->project_code}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="td-width">
                        <select name="task_id[]" class="w-100 task_id inputFieldHeight2 common-select2">
                            <option value="">Select...</option>
                            @foreach (App\JobProjectTask::where('job_project_id', $temp->project_id)->get() as $item)
                                <option value="{{ $item->id }}" {{$item->id==$temp->task_id?'selected':''}}>{{ $item->task_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    {{-- <td class="td-width">
                        <select name="task_item_id[]" class="w-100 task_item inputFieldHeight2 common-select2">
                            <option value="">Select...</option>
                            @foreach (App\JobProjectTaskItem::where('task_id', $temp->task_id)->get() as $item)
                                <option value="{{ $item->id }}" {{$item->id==$temp->task_item_id?'selected':''}}>{{ $item->item_description }}</option>
                            @endforeach
                        </select>
                    </td> --}}
                    <td>
                        <div>
                            <input type="number" step="any" name="task_qty[]" required value="{{$temp->qty}}" step="any" placeholder="QTY" class="text-center form-control inputFieldHeight2 cogs_task_qty"style="width: 100%;height:36px;">
                        </div>
                    </td>
                    <td>
                        <div>
                            <input type="number" step="any" name="task_amount[]" required value="{{$temp->amount}}" step="any" placeholder="Amount" class="text-center form-control inputFieldHeight2 cogs_task_amount"style="width: 100%;height:36px;" >
                        </div>
                    </td>
                    <td class="NoPrint text-center">
                        <button style="padding: 5px; margin: 4px;" type="button" class="btn btn-sm btn-danger" onclick="COGS_Btn_Del(this)"><i class="bx bx-trash" style="color: white;margin-top: -5px;"></i></button>
                    </td>
                </tr>
            @endforeach
            <tr id="task_TRow" class="invoice_row d-none">
                <td class="td-width">
                    <select name="project_id[]" required class="form-control project_id account-head" disabled >
                        <option value="">Select....</option>
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
                        <input type="number" step="any" name="task_qty[]" required step="any" placeholder="QTY" class="text-center form-control inputFieldHeight2 cogs_task_qty"style="width: 100%;height:36px;" disabled>
                    </div>
                </td>
                <td>
                    <div class="d-flex justy-content-between align-items-center">
                        <input type="number" step="any" name="task_amount[]" step="any" placeholder="Amount" class="text-center form-control inputFieldHeight2 cogs_task_amount"style="width: 100%;height:36px;" disabled>
                    </div>
                </td>
                <td class="NoPrint text-center">
                    <button style="padding: 5px; margin: 4px;" type="button" class="btn btn-sm btn-danger" onclick="COGS_Btn_Del(this)"><i class="bx bx-trash" style="color: white;margin-top: -5px;"></i></button>
                </td>
            </tr>
        </tbody>
        <tbody>
            <tr>
                <td class="text-right pr-1 " colspan="2" style="color: black"><small class="text-danger">(Max amount allow: {{$amount}} And QTY: {{$qty}}  )</small>TOTAL:</td>
                <td>
                    <input type="number" step="any" readonly id="cogs_total_qty" class="text-center inputFieldHeight2 form-control inputFieldHeight cogs_total_qty" name="task_total_amount" value="{{$temp_pe->sum('qty')}}" placeholder="QTY" >
                </td>
                <td>
                    <input type="number" step="any" readonly id="cogs_total_amount" class="text-center inputFieldHeight2 form-control inputFieldHeight cogs_total_amount" name="task_total_amount" value="{{$temp_pe->sum('amount')}}" placeholder="TOTAL " >
                </td>
            </tr>
        </tbody>
    </table>
    <div class="text-right">
        <button class="btn btn-success" type="submit">Save</button>
    </div>
</form>

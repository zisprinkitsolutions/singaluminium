<section class="print-hideen border-bottom">
    <div class="d-flex">
        <h6 class="mt-1 ml-1 mr-auto">Account Head: {{$ac_head->fld_ac_head}}</h6>

        <div class="mIconStyleChange">
            <a href="#" class="btn btn-sm btn-info d-none hide-unhide" id="add-product">Back
                <span class="text-center" style="font-size: 18px;color:#92a0b1;"></span></a>
                <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">
                    <i class='bx bx-x'></i>
                </span>
            </a>
        </div>
    </div>
</section>

<section id="widgets-Statistics" class="mr-1 ml-1 mb-2 accountHeadStyle HeadStyle">
    <div class="col-12 mt-2 m-0 p-0" id="project_expense_model_content">
        <form action="{{route('party-amount-store')}}" method="POST" id="account_expense_store">
            @csrf
            <input type="hidden" value="{{$journal_no}}" name="journal_no">
            <input type="hidden" value="{{$ac_head->id}}" name="accout_head_id">
            <input type="hidden" value="{{$sub_head}}" name="sub_head_id">
            <input type="hidden" value="{{$credit_amount>0?$credit_amount:$debit_amount}}" name="max_amount" id="max_amount">
            <table class="table table-sm ">
                <thead>
                    <tr>
                        <th>Party Name</th>
                        <th style="width: 100px;">Amount</th>
                        <th class="NoPrint" style="width: 20px; padding: 2px;">
                            <button type="button" class="btn btn-sm btn-success task_addBtn"style="border: 1px solid green; color: #fff; border-radius: 10px;padding: 5px;" onclick="task_BtnAdd()">
                                <i class="bx bx-plus" style="color: white;margin-top: -5px;"></i>
                            </button>
                        </th>
                    </tr>
                </thead>
                <tbody id="task_TBody">
                    {{-- @foreach ($temp_pe as $temp)
                        <tr class="text-center invoice_row">
                            <td>
                                <select name="project_id[]" id="project_id" class="w-100 project_id inputFieldHeight2" required>
                                    <option value="">Select...</option>
                                    @foreach ($project_lists as $item)
                                        <option value="{{ $item->id }}" {{$item->id==$temp->project_id?'selected':''}}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <div>
                                    <input type="number" step="any" name="task_qty[]" required value="{{$temp->qty}}" step="any" placeholder="QTY" class="text-center form-control inputFieldHeight2 task_qty"style="width: 100%;height:36px;">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <input type="number" step="any" name="task_amount[]" readonly required value="{{$temp->amount}}" step="any" placeholder="Amount" class="text-center form-control inputFieldHeight2 task_amount"style="width: 100%;height:36px;" >
                                </div>
                            </td>
                            <td class="NoPrint text-center">
                                <button style="padding: 5px; margin: 4px;" type="button" class="btn btn-sm btn-danger" onclick="BtnDel(this)"><i class="bx bx-trash" style="color: white;margin-top: -5px;"></i></button>
                            </td>
                        </tr>
                    @endforeach --}}
                    <tr id="task_TRow" class="text-center invoice_row">
                        <td>
                            <select name="party_id[]" id="party_id" class="w-100 party_id inputFieldHeight2 form-control" >
                                <option value="">Select...</option>
                                @foreach ($parties as $item)
                                    <option value="{{ $item->id }}">{{ $item->pi_name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <div class="d-flex justy-content-between align-items-center">
                                <input type="number" step="any" name="pay_amount[]" step="any" placeholder="Amount" class="text-center form-control inputFieldHeight2 pay_amount"style="width: 100%;height:36px;">
                            </div>
                        </td>
                        <td class="NoPrint text-center">
                            <button style="padding: 5px; margin: 4px;" type="button" class="btn btn-sm btn-danger" onclick="BtnDel(this)"><i class="bx bx-trash" style="color: white;margin-top: -5px;"></i></button>
                        </td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td class="text-center" style="color: black">TOTAL AMOUNT <small class="text-danger">(Max amount allow: {{$credit_amount>0?$credit_amount:$debit_amount}})</small></td>
                        <td>
                            <input type="number" step="any" readonly id="pay_amount_total_amount" class="text-center inputFieldHeight2 form-control inputFieldHeight pay_amount_total_amount" name="pay_amount_total_amount" value="" placeholder="TOTAL " >
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="text-right">
                <button class="btn btn-success" type="submit">Save</button>
            </div>
        </form>
    </div>
</section>
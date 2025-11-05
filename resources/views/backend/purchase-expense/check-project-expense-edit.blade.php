@php
    $e_amount= 0;
    $p_amount= 0;
@endphp
@foreach ($multi_head as $each_head)
    @php
        $check_sub_head = substr($each_head['head_id'],0,3);
        $account_sub_head = null;
        $account_head = null;
        if ($check_sub_head == 'Sub') {
            $account_sub_head = App\AccountSubHead::find(substr($each_head['head_id'],3));
            $account_head = App\Models\AccountHead::find($account_sub_head->account_head_id);
        } else {
            $account_head = App\Models\AccountHead::find($each_head['head_id']);
        }
        if($account_sub_head){
            $collection = App\TempCogsAssign::where('purchase_expense_id', $purchase_id)->where('sub_head_id', $account_sub_head->id)->get();
            
        }else{
            $collection = App\TempCogsAssign::where('purchase_expense_id', $purchase_id)->where('account_head_id', $account_head->id)->where('sub_head_id', null)->get();
        }
        $p_amount += $collection->sum('amount');
    @endphp
    @if ($account_head->fld_definition == 'Cost of Sales / Goods Sold')
        @php
            $e_amount += $each_head['amount'];
        @endphp
        <table class="table table-sm mb-0 table-bordered mb-1">
            <tr>
                <td colspan="2" class="text-left pl-1" style="font-size: 15px !important;">{{$account_sub_head?$account_sub_head->name:$account_head->fld_ac_head}}</td>
            </tr>
            <tr style="background: #383737ce !important;">
                <th class="text-left pl-1" style="line-height: 2;">Project Name</th>
                <th class="text-left pl-1" style="line-height: 2;">Project Item</th>
                <th class="text-left pl-1" style="line-height: 2;">Item Details</th>
                <th class="text-center" style="line-height: 2;">QTY</th>
                <th class="text-center" style="line-height: 2;">Amount</th>
            </tr>
            @if (count($collection)>0)
                @foreach ($collection as $px)
                    @php
                        $project_new = App\NewProject::find($px->project->project_id);
                    @endphp
                    <tr>
                        <td class="text-left pl-1">{{$project_new?$project_new->name:$px->project_name}}</td>
                        <td class="text-left pl-1">{{$px->project_task->task_name??''}}</td>
                        <td class="text-left pl-1">{{$px->project_task_item->item_description??''}}</td>
                        <td class="text-center">{{$px->qty}}</td>
                        <td class="text-center">{{number_format($px->amount,2)}}</td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <td colspan="4" class="text-right pr-1">Total Amount:</td>
                <td class="text-center">{{number_format($collection->sum('amount'),2)}}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right pr-1">Expense Amount:</td>
                <td class="text-center">{{number_format($each_head['amount'],2)}}</td>
            </tr>
            @if ($collection->sum('amount') != $each_head['amount'])
                <tr>
                    <td colspan="5" class="text-danger text-center">Please assign the amount to the project.</td>
                </tr>
            @endif
        </table>
    @endif
@endforeach
@if (number_format($e_amount, 2, '.', ',') == number_format($p_amount, 2, '.', ','))
    <div class="mb-1 text-right">
        <small class="text-danger">If you sure expense are al right please click confirm button !</small>
        <button type="button" class="btn btn-success" onclick="purchase_expense_edit(document.getElementById('editFormSubmit'))">Confirm</button>
    </div>
@endif

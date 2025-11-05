<td colspan="2" style="padding: 0;">
    <div class=" ml-2">
        <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
            @foreach ($details as $item)
                <tr class="tax-sub-head-details" id="{{$item->account_head_id}}">
                    <td class="td-border">{{$item->ac_head->fld_ac_head??''}}</td>
                    <td class="text-right pr-1" style="width: 143px !important;">{{number_format($item->net_amount<0?$item->net_amount*-1:$item->net_amount,2)}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</td>
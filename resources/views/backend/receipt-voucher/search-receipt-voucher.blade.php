@foreach ($receipt_list as $key => $item)
<tr class="receipt_exp_view"  id="{{$item->id}}">
    <td>{{$key+1}}</td>
    <td>{{date('d/m/Y',strtotime($item->date))}}</td>

    <td>{{$item->receipt_no}}</td>
    <td>{{$item->party->pi_name}}</td>
    <td>{{$item->narration}}</td>
    <td >{{number_format($item->total_amount,2)}}</td>
    <td>{{$item->pay_mode}}</td>
</tr>

@endforeach

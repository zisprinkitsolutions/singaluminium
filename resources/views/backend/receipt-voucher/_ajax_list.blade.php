@foreach ($temp_receipt_list as $key => $item)
    <tr class="receipt_view" id="{{$item->id}}">
        <td>{{$key+1}}</td>
        <td>{{date('d/m/Y',strtotime($item->date))}}</td>
        <td>{{$item->receipt_no}}</td>
        <td> {{ $item->name==null?  $item->party->pi_name : $item->name}}</td>
        <td>{{$item->narration}}</td>
        <td >{{number_format($item->total_amount,2)}} </td>
        <td style="min-width: fit-content"> Awaiting Approve </td>
        <td >{{$item->pay_mode}}</td>
    </tr>
    @endforeach


@foreach ($sales as $item)
<tr class="sale_view"  id="{{$item->id}}">
    <td>{{date('d/m/Y',strtotime($item->date))}}</td>
    <td>{{$item->invoice_no}}</td>
    <td>{{$item->party->pi_name}}</td>
    <td>{{number_format($item->total_budget,2)}}</td>
</tr>

@endforeach


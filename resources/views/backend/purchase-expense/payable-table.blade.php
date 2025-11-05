@foreach ($suppliers as $party)
<tr class="payable-view" id="{{ $party->id }}"
    style="text-align:center;">
    <td>{{ $party->pi_code}}</td>
    <td>{{$party->pi_name}}</td>
    <td>{{number_format($party->due_amount,2)}}</td>


</tr>
@endforeach

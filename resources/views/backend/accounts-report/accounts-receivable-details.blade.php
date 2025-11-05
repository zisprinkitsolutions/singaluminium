@foreach ($data as $key => $item)
@php
    $date = \Carbon\Carbon::parse($item->date);
    $now = \Carbon\Carbon::now()->toDateString();
    $diff = $date->diffInDays($now);
@endphp
<tr class="invoice_show">
    <td style="font-size: 13px; font-weight:400;"> {{date('d/m/Y', strtotime($item->date))}} </td>
    <td style="font-size: 13px; font-weight:400;"> {{$item->invoice_no}} </td>
    <td class="text-center" style="font-size: 13px; font-weight:400;"> {{$diff}} {{$diff>1?'Days':'Day'}}</td>
    <td style="font-size: 13px; font-weight:400;text-align:right;"> {{number_format($item->total_budget,2)}} </td>
    <td style="font-size: 13px; font-weight:400;text-align:right;"> {{number_format($item->paid_amount,2)}} </td>
    <td style="font-size: 13px; font-weight:400;text-align:right;"> {{number_format($item->due_amount,2)}} </td>
</tr>

@endforeach

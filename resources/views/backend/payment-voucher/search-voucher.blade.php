@php
    $i = 0;
@endphp
@foreach ($temp_payments as $key => $item)
    <tr class="temp-payment-voucher" id="{{$item->id}}">
        <td>{{++$i}}</td>
        <td>{{date('d/m/Y',strtotime($item->date))}}</td>
        <td>{{$item->payment_no}}</td>
        <td style="text-align: left !important;" title="{{optional($item->party)->pi_name}}">
            {{\Illuminate\Support\Str::limit(optional($item->party)->pi_name,30)}}
        </td>
        <td >{{number_format($item->total_amount,2)}} </td>
        <td style="min-width: fit-content">  <span class="bg-warning text-white" style="padding: 2px 3px;"> Awaiting Approve </span>  </td>
        <td >{{$item->pay_mode}}</td>
    </tr>
@endforeach
@foreach ($payments as $item)
    <tr class="payment-voucher"  id="{{$item->id}}">
        <td>{{++$i}}</td>
        <td>{{date('d/m/Y',strtotime($item->date))}}</td>
        <td>{{$item->payment_no}}</td>
        <td style="text-align: left !important;" title="{{optional($item->party)->pi_name}}">
            {{\Illuminate\Support\Str::limit(optional($item->party)->pi_name,30)}}
        </td>
        <td >{{number_format($item->total_amount,2)}}</td>
        <td style="min-width: fit-content"> <span class="bg-success text-white" style="padding: 2px 3px;"> Approved </span> </td>
        <td >{{$item->pay_mode}}</td>
    </tr>
@endforeach
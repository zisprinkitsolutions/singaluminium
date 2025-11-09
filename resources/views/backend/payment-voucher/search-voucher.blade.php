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
        <td class="text-right">{{number_format($item->total_amount,2)}} </td>
        <td style="min-width: fit-content">  <span class="bg-warning text-white" style="padding: 2px 3px;"> Awaiting Approve </span>  </td>
        <td class="text-center">{{$item->pay_mode}}</td>
        @if ($item->bank_id)
            <td class="text-center">{{ optional($item->bank_name)->name }}</td>
        @else
            <td class="text-center">{{ optional($item->payment_account)->full_name }}</td>
        @endif
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
        <td class="text-right">{{number_format($item->total_amount,2)}}</td>
        <td style="min-width: fit-content"> <span class="bg-success text-white" style="padding: 2px 3px;"> Approved </span> </td>
        <td class="text-center">{{$item->pay_mode}}</td>
        @if ($item->bank_id)
            <td class="text-center">{{ optional($item->bank_name)->name }}</td>
        @else
            <td class="text-center">{{ optional($item->payment_account)->full_name }}</td>
        @endif
    </tr>
@endforeach
<tr style="background: #394c62 !important;">
    <td colspan="4" class="text-right " style=" color: #fff !important; background: #394c62 !important;">Total</td>
    <td class="text-right" style=" color: #fff !important; background: #394c62 !important;">{{ number_format($temp_payments->sum('total_amount')+$payments->sum('total_amount'), 2) }}</td>
    <td colspan="3" style="background: #394c62 !important;"></td>
</tr>
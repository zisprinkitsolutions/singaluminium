@php
$balance=0;
@endphp
@foreach ($transections as $item)
    <tr class="{{ $item->invoice_type == 'Receipt' ? 'receipt_exp_view' : 'sale_view' }}" id="{{ $item->id }}">
        <td>{{ date('d/m/Y', strtotime($item->date)) }}</td>
        <td>{{ $item->transection_no }}</td>
        <td>{{ App\PartyInfo::find($item->party_id)->pi_name }}</td>
        <td>{{ number_format($item->amount, 2) }}</td>
        <td>{{ $item->invoice_type }}</td>
        @php
            if ($item->invoice_type == 'Receipt') {
                $balance -= $item->amount;
            } else {
                $balance += $item->amount;
            }
        @endphp
        <td>{{ $balance }}</td>
    </tr>
@endforeach
<tr>
    <td colspan="5" class="text-right"><strong>Total Balance</strong></td>
    <td><strong>{{$balance}}</strong></td>
</tr>

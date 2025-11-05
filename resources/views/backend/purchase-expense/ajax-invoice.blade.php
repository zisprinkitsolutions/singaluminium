@foreach ($invoicess as $item)
    <tr class="purch_exp_view" id="{{ $item->purchase->id }}" style="text-align:center;">
        <td>{{ date('d/m/Y', strtotime($item->purchase->date)) }}</td>
        <td>{{ $item->purchase->purchase_no }}</td>
        <td>{{ $item->purchase->party->pi_name }}</td>
        <td>{{ $item->invoice_no }}</td>
        <td>{{ $item->Total_amount }}</td>
    </tr>
@endforeach

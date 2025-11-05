<thead>
    <tr class="sort-toggler">
        <td data-column="0" data-sort="desc" style="width:10%;font-size:13px;text-transform:capitalize;padding-left:35px; border-bottom:1px solid #dddddd;"> Date <i class="sort-indicator desc"></i></td>
        <td data-column="1" data-sort="desc" style="width:25%;font-size:13px;text-transform:capitalize; border-bottom:1px solid #dddddd;"> Narration <i class="sort-indicator"></i></td>
        <td data-column="2" data-sort="desc" style="width:30%;font-size:13px;text-transform:capitalize !important; border-bottom:1px solid #dddddd;"> Ref. No.<i class="sort-indicator"></i> </td>
        <td data-column="3" data-sort="desc" class="text-right" style="width:10%;font-size:13px;text-transform:capitalize;text-align:right; border-bottom:1px solid #dddddd;"> Debit<i class="sort-indicator"></i> </td>
        <td data-column="4" data-sort="desc" class="text-right" style="width;10%;font-size:13px;text-transform:capitalize;text-align:right; border-bottom:1px solid #dddddd;"> Credit <i class="sort-indicator"></i> </td>
        <td data-column="5" data-sort="desc" class="text-right" style="width:15%;font-size:13px;text-transform:capitalize;text-align:right; border-bottom:1px solid #dddddd;"> Balance C/D <i class="sort-indicator"></i> </td>
    </tr>
</thead>

@php
    $total_dr_amount = 0;
    $total_cr_amount = 0;
@endphp

<tbody>
@foreach ($items as $item)
    @php
        if ($item->journal_id) {
            $journal = \App\Journal::find($item->journal_id);
            $detail_record =  $journal->journal_description($journal->id);
        }
    @endphp

    <tr class="trFontSize journalDetails short-date" v-type="main" style="cursor: pointer; padding-left: 50px !important;" id="{{ $journal->id}}">
            <td style="font-size: 13px;padding-left:35px;">{{ \Carbon\Carbon::parse($item->journal_date)->format('d/m/Y') }}</td>
            <td style="font-size: 12px !important;"> {{ $item->fld_ac_head}} </td>
        @if(isset($detail_record))
            <td style="font-size: 13px; padding-left: 25px !important;" class="show-details" data-type=" {{ isset($detail_record['type']) ? $detail_record['type'] : 'type' }}" id="{{ isset($detail_record['id']) ? $detail_record['id'] : 'id' }}">
                {{ isset($detail_record['name']) ? $detail_record['name'] : 'N/A' }}
            </td>
        @else
            <td>

            </td>
        @endif
        @php
            $dr_amount = $item->transaction_type == 'DR' ? $item->amount: 0;
            $cr_amount = $item->transaction_type == 'CR' ? $item->amount: 0;
            $total_dr_amount += $dr_amount;
            $total_cr_amount += $cr_amount;
            $balance = abs($total_dr_amount - $total_cr_amount);
        @endphp
            <td class="text-right" style="font-size: 13px;">
                {{ number_format(($item->transaction_type == 'DR' ? $item->amount: 0),2)}}
            </td>
            <td class="text-right" style="font-size: 13px;">
                {{ number_format(($item->transaction_type == 'CR' ? $item->amount : 0),2)}}
            </td>
            <td style="font-size: 13px;" class="text-right"> {{number_format($balance,2)}} </td>
    </tr>
@endforeach
</tbody>
<tr>
    <td colspan="3" class="text-right" style="font-size:13px;"> Balance C/D </td>
    <td class="text-right" style="font-size:13px;">  {{number_format(($total_dr_amount > $total_cr_amount ? 0.00 : $total_cr_amount - $total_dr_amount), 2) }} </td>
    <td class="text-right" style="font-size:13px;">  {{number_format(($total_cr_amount > $total_dr_amount ? 0.00 : $total_dr_amount - $total_cr_amount), 2) }} </td>
    <td></td>
</tr>

<tr>
    <td colspan="3" class="text-right" style="font-size:13px;"> Total </td>
    <td class="text-right" style="font-size:13px;">  {{number_format(($total_dr_amount > $total_cr_amount ? $total_dr_amount: $total_cr_amount), 2) }} </td>
    <td class="text-right" style="font-size:13px;">  {{number_format(($total_cr_amount > $total_dr_amount ? $total_cr_amount: $total_dr_amount), 2) }} </td>
    <td></td>
</tr>


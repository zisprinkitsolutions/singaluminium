<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse">

        <div class="pr-1" style="padding-top: 8px;padding-right: 22px !important;"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>

        <div class="pr-1 w-100 pl-2">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">{{$account_head->fld_ac_head}} DETAILS</h4>
        </div>
    </div>
</section>
<table class="table table-sm ">
    <thead>
        <tr class="thead">
            <th data-column="0" data-sort="desc" style="width:10%;font-size:13px;text-transform:capitalize;padding-left:35px; border-bottom:1px solid #dddddd;"> Date </th>
            <th data-column="1" data-sort="desc" style="width:25%;font-size:13px;text-transform:capitalize; border-bottom:1px solid #dddddd;"> Narration  </th>
            <th data-column="2" data-sort="desc" style="width:30%;font-size:13px;text-transform:capitalize !important; border-bottom:1px solid #dddddd;"> Ref. No.  </th>
            <th data-column="3" data-sort="desc" class="text-right" style="width:10%;font-size:13px;text-transform:capitalize;text-align:right; border-bottom:1px solid #dddddd;"> Debit</th>
            <th data-column="4" data-sort="desc" class="text-right" style="width;10%;font-size:13px;text-transform:capitalize;text-align:right; border-bottom:1px solid #dddddd;"> Credit  </th>
            <th data-column="5" data-sort="desc" class="text-right pr-1" style="width:15%;font-size:13px;text-transform:capitalize;text-align:right; border-bottom:1px solid #dddddd;"> Balance C/D  </th>
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
    
    <tr class="trFontSize journalDetails short-date" v-type="main"
        style="cursor: pointer;" id="{{ $journal->id}}">
            <td style="font-size: 13px;padding-left:35px;">{{ \Carbon\Carbon::parse($item->journal_date)->format('d/m/Y') }}</td>
        @if(isset($detail_record))
            <td style="font-size: 13px;" class="show-details" data-type=" {{ isset($detail_record['type']) ? $detail_record['type'] : 'type' }}" id="{{ isset($detail_record['id']) ? $detail_record['id'] : 'id' }}">
                {{ isset($detail_record['name']) ? $detail_record['name'] : 'N/A' }}
            </td>
        @else
            <td>
    
            </td>
        @endif
            <td style="font-size: 12px !important;"> {{ $item->reference}} </td>
        @php
            $dr_amount = $item->transaction_type == 'DR' ? $item->amount: 0;
            $cr_amount = $item->transaction_type == 'CR' ? $item->amount: 0;
            $total_dr_amount += $dr_amount;
            $total_cr_amount += $cr_amount;
            $balance = abs($dr_amount - $cr_amount);
        @endphp
            <td class="text-right" style="font-size: 13px;">
                {{ number_format(($item->transaction_type == 'DR' ? $item->amount: 0),2,'.','')}}
            </td>
            <td class="text-right" style="font-size: 13px;">
                 {{ number_format(($item->transaction_type == 'CR' ? $item->amount : 0),2,'.','')}}
            </td>
            <td style="font-size: 13px;" class="text-right pr-1"> {{number_format($balance,2,'.','')}} </td>
    </tr>
    @endforeach
    </tbody>
    <tr>
        <td colspan="3" class="text-right" style="font-size:13px;"> Balance C/D </td>
        <td class="text-right" style="font-size:13px;">  {{number_format(($total_dr_amount > $total_cr_amount ? 0.00 : $total_cr_amount - $total_dr_amount), 2, '.' , '') }} </td>
        <td class="text-right" style="font-size:13px;">  {{number_format(($total_cr_amount > $total_dr_amount ? 0.00 : $total_dr_amount - $total_cr_amount), 2, '.', '') }} </td>
        <td></td>
    </tr>
    
    <tr>
        <td colspan="3" class="text-right" style="font-size:13px;"> Total </td>
        <td class="text-right" style="font-size:13px;">  {{number_format(($total_dr_amount > $total_cr_amount ? $total_dr_amount: $total_cr_amount), 2, '.' , '') }} </td>
        <td class="text-right" style="font-size:13px;">  {{number_format(($total_cr_amount > $total_dr_amount ? $total_cr_amount: $total_dr_amount), 2, '.', '') }} </td>
        <td></td>
    </tr>
    
    
</table>
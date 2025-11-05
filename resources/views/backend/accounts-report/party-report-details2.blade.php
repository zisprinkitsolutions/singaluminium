<thead>
    <tr class="parrent-tr">
        <td style="font-size: 10px;font-weight:800; padding-left:20px; background-color:#e3e3e3;width:10%;"> Date </td>
        <td class="text-left" style="font-size: 10px; font-weight:800; background-color:#e3e3e3;"> Description </td>
        <td style="font-size: 10px; font-weight:800;  background-color:#e3e3e3;"> Paymode</td>
        <td style="font-size: 10px; font-weight:800;  text-align:right !important;background-color:#e3e3e3; width:10%;">Debit </td>
        <td style="font-size: 10px; font-weight:800;  text-align:right !important;background-color:#e3e3e3; width:10%;">Credit </td>
        <td style="font-size: 10px; font-weight:800;  text-align:right !important;background-color:#e3e3e3;">Running Payment Balance </td>
        <td style="font-size: 10px; font-weight:800;  text-align:right !important;background-color:#e3e3e3;">Retention Receivable </td>
        <td style="font-size: 10px; font-weight:800;  text-align:right !important;background-color:#e3e3e3; width:10%;">Balance <small>{{$currency->symbole}}</small> </td>
    </tr>
</thead>

<tbody>
    @php
        $balance_dr =  $balance_fwd_dr;
        $balance_cr = $balance_fwd_cr;
        $runnign_balance = 0;
        $retention_balance = 0;
        $balance=0;
    @endphp
    <tr class="text-center trFontSize" style="cursor: pointer;">
        <td></td>
        <td class="text-left"><strong>Balance Brought FWD</strong></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align:right !important;"> {{$balance_dr>$balance_cr?'DR ':'CR '}} {{number_format(abs($balance_dr-$balance_cr),2)}} </td>
    </tr>
    @foreach ($records as $record)
        @php
            $journal=App\Journal::find($record->journal_id);
            $isPayment=true;
        @endphp

        @if(!$journal->receipt_id && !$journal->payment_id)
            <tr class="text-center trFontSize " v-type="main" style="cursor: pointer;" id="{{ $journal->journal_id }}">
                <td>{{date('d/m/Y', strtotime($journal->date))}}</td>
                <td class="text-left">{{$journal->party_journal_description($journal->id)['name']}}</td>
                <td style="text-transform:uppercase;">{{$journal->pay_mode}} </td>
                @if($journal->invoice_id)
                    <td style="text-align:right !important; ">{{number_format($b=$journal->records()->whereIn('account_head_id',[3,1759])->where('transaction_type','DR')->sum('amount')-$journal->records()->whereIn('account_head_id',[3,1759])->where('transaction_type','CR')->sum('amount'),2)}}</td>
                    <td style="text-align:right !important; ">0.00</td>
                    @php
                        $balance_dr +=  $b;
                    @endphp
                @else($journal->purchase_expense_id)
                    <td style="text-align:right !important; ">0.00</td>
                    <td style="text-align:right !important; ">{{number_format($c=$journal->records()->whereNotIn('account_head_id',[407,1760])->where('transaction_type','DR')->sum('amount'),2)}}</td>
                    @php $balance_dr -=  $c; @endphp
                @endif

                @php
                    $rr=App\JournalRecord::where('journal_id', $journal->id)->where('account_head_id', 1759)->first();
                    $runnign_balance += $journal->records()->whereIn('account_head_id',[3])->where('transaction_type','DR')->sum('amount')-$journal->records()->whereIn('account_head_id',[3])->where('transaction_type','CR')->sum('amount');

                    $retention_balance += $journal->records()->whereIn('account_head_id',[1759])->where('transaction_type','DR')->sum('amount')- $journal->records()->whereIn('account_head_id',[1759])->where('transaction_type','CR')->sum('amount')
                @endphp
                <td style="text-align:right !important;"> {{number_format($runnign_balance,2)}} </td>
                <td style="text-align:right !important;">{{number_format($retention_balance,2)}}</td>

                <td style="text-align:right !important;"> {{$balance_dr>$balance_cr?'DR ':'CR '}} {{number_format(abs(($balance_dr)-$balance_cr),2)}} </td>

            </tr>
        @endif

        @php
            $payment=$journal->records()->whereIn('account_head_id',[1,2,30,32,93,153])->get();
            $cr_amount = $payment->where('transaction_type','DR')->sum('amount');
            $dr_amount =$payment->where('transaction_type','CR')->sum('amount');
            $amount=  $dr_amount-$cr_amount;
            if ($journal->pay_mode != 'Advance') {
                $balance_cr += $cr_amount;
            }
            if($journal->pay_mode == 'Advance'){
                $balance_dr += $dr_amount;
            }
        @endphp
        @if($payment->count())
            <tr class="text-center trFontSize " v-type="main" style="cursor: pointer;" id="{{ $journal->journal_id }}">
                <td>{{date('d/m/Y', strtotime($journal->date))}}</td>
                <td class="text-left">{{$journal->party_journal_description($journal->id)['name']}}</td>
                <td style="text-transform: uppercase !important;">{{$journal->pay_mode == 'Advance'?'-':$journal->pay_mode}}</td>
                @if ($journal->pay_mode == 'Advance')
                    <td style="text-align:right !important; ">{{number_format($journal->total_amount,2)}}</td>
                    <td style="text-align:right !important; ">0</td>
                @else
                    <td style="text-align:right !important; "> {{number_format($amount>0?$amount:0,2)}}</td>
                    @if ($journal->receipt?$journal->receipt->type == 'advance':'')
                        <td style="text-align:right !important; ">{{number_format($journal->total_amount,2)}}</td>
                    @else
                        <td style="text-align:right !important; ">{{number_format($amount<0?($amount*(-1)):0,2)}}</td>
                    @endif
                @endif
                @php
                    $rr=App\JournalRecord::where('journal_id', $journal->id)->where('account_head_id', 1759)->first();
                    $runnign_balance += $journal->records()->whereIn('account_head_id',[3])->where('transaction_type','DR')->sum('amount')-$journal->records()->whereIn('account_head_id',[3])->where('transaction_type','CR')->sum('amount');

                    $retention_balance += $journal->records()->whereIn('account_head_id',[1759])->where('transaction_type','DR')->sum('amount')- $journal->records()->whereIn('account_head_id',[1759])->where('transaction_type','CR')->sum('amount')
                @endphp
                <td style="text-align:right !important;"> {{number_format($runnign_balance,2)}} </td>
                <td style="text-align:right !important;">{{number_format($retention_balance,2)}}</td>

                <td style="text-align:right !important; "> {{$balance_dr>$balance_cr?'DR ':'CR '}} {{number_format(abs($balance_dr-$balance_cr),2)}} </td>

            </tr>
        @endif
    @endforeach
    <tr class="">
        <th class="text-right" colspan="8" style="font-size: 12px;">RUNNING PAYMENT RECEIVABLE: {{number_format($runnign_balance,2)}}</th>
    </tr>
    <tr class="">
        <th class="text-right" colspan="8" style="font-size: 12px;">{{$balance_dr>$balance_cr?'GROSS RECEIVABLE INCLUDING RETENTION: ':'GROSS PAYABLE: '}} {{number_format(abs(($balance_dr)-$balance_cr),2)}}</th>
    </tr>
</tbody>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Party Report PDF </title>
    <style>
        @page {
           margin: 100px 25px;
           size: A4;
       }
       body {
           font-family: Arial, sans-serif;
           margin: 0;
           background-color: #ffffff;
       }
       .header {
           position: fixed;
           top: -100px;
           left: 0px;
           right: 0px;
           height: 50px;
           text-align: center;
           line-height: 35px;
       }
       h1, .company-details{
            margin-bottom: 0 !important;
       }
       .company-details{
            line-height: 5px;
       }

       .footer {
           position: fixed;
           bottom: -60px;
           left: 0px;
           right: 0px;
           height: 50px;
           font-size: 20px !important;

           text-align: center;
       }

       .content {
           margin-top: 0px;
           padding: 0 20px;
       }

       h5,h4{
           font-weight: 400;
           margin-bottom: 10px;
           color: #292929;
       }
       h4{
           font-size: 22px;
           text-align: center;
           font-weight: 500;
       }

       h5{
           font-size: 17px;
           font-weight: 400;
       }

       .currency{
           font-size: 14px;
           text-align: center;
           color: #292929;
           font-weight: 500;
       }

       table{
           border-collapse: collapse;
           border: 1px solid #ddd;
       }
       th, td{
           border: 1px solid #ddd;
           text-align: center;
           padding: 4px 10px;
           border-collapse: collapse;
       }
       td{
            font-weight: 400;
            font-size: 14px;
            color: #474343;
            text-align: center;
       }
       th{
           font-size: 16px;
           color: #202020;
           font-weight: 400;
       }
       table{
           width:100%;
       }
       .footer {
           position: fixed;
           bottom: -60px;
           left: 0px;
           right: 0px;
           height: 50px;
           font-size: 20px !important;
           text-align: center;
       }
       .page-number{
            text-align: right;
            padding-right: 25px;
        }

       .page-number:after { content: "Page " counter(page)};

   </style>
</head>
<body>
    @include('layouts.backend.partial.pdf-header')

   <div class="footer" style="font-size: 13px;">
    <p class="page-number" style="font-size:13px;"></p>
       <div style="text-align: left; line-height: 1.5;">
           <span style="font-size: 13px; vertical-align: middle; margin-right: 10px;">
               System software power by Zicash
           </span>
           <img src="{{ $logo }}" alt="zisprink" width="60" style="vertical-align: middle;">
       </div>
   </div>

   <div class="content">
        <div class="text-center invoice-view-wrapper">
            @if($from && $to)
            <h5 style="text-transform: uppercase"> {{$parties[0]['pi_name']}} Ledger Report from {{date('d/m/Y',strtotime($from))}} to {{date('d/m/Y', strtotime($to))}}</h5>
            @elseif ($from)
            <h5 style="text-transform: uppercase"> {{$parties[0]['pi_name']}} Ledger Report  {{date('d/m/Y',strtotime($from))}}</h5>
            @elseif($to)
            <h5 style="text-transform: uppercase"> {{$parties[0]['pi_name']}} Ledger Report  {{date('d/m/Y',strtotime($to))}}</h5>
            @elseif($year || $month)
              {{$parties[0]['pi_name']}} Ledger Report {{$month ? date('F',strtotime($month)) : ' '}} {{$year}}
            @else
                <h5 style="text-transform: uppercase"> {{$parties[0]['pi_name']}} Ledger Report </h5>
            @endif
        </div>

        <div class="" style="margin-top:10px;">
            <table>
                <thead>
                    <tr>
                        <th style="background-color: #DCDCDC; font-size:14px; text-align:left; padding-left:6px; width:52%;" colspan="2"> Name  </th>
                        <th style="background-color: #DCDCDC; font-size:14px; text-align:center;width:10%"> Code </th>
                        <th style="background-color: #DCDCDC; font-size:14px; text-align:right; padding-left:6px;width:10%"> Type </th>
                        <th style="background-color: #DCDCDC; font-size:14px; text-align:right; padding-left:6px; width:10%;"> Remark </th>
                        {{-- <th style="background-color: #DCDCDC; font-size:14px; text-align:right;width:10%"> Debit </th>
                        <th style="background-color: #DCDCDC; font-size:14px; text-align:right; width:10%;"> Credit</th> --}}
                        <th style="background-color: #DCDCDC; font-size:14px; text-align:right; width:18%;"> Balance(<small style="font-size: 10px;">{{$currency->symbole}}</small>) </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_dr_amount = 0;
                        $total_cr_amount = 0;
                    @endphp
                    @foreach ($parties as $party)
                    <tr>
                        <td style="padding: 8px; font-size:12px; text-align: left; color:#313131; width:55%;" colspan="2">{{ $party['pi_name']}}</td>
                        <td style="padding: 8px; font-size:13px; text-align: center; color:#313131;">{{ $party['pi_code'] }}</td>
                        <td style="padding: 8px; font-size:13px; text-align: right; color:#313131;">{{ $party['pi_type'] }}</td>
                        <td style="padding: 8px; font-size:13px; text-align: right; color:#313131;">{{ $party['remark']}}</td>
                        {{-- <td style="padding: 8px; font-size:13px; text-align: right; color:#313131;">{{ $party['dr_amount'] }}</td>
                        <td style="padding: 8px; font-size:13px; text-align: right; color:#313131;">{{ $party['cr_amount'] }}</td> --}}
                        <td style="padding: 8px; font-size:13px; text-align: right; color:#313131;">{{ $party['balance'] }}</td>
                    </tr>
                    @php
                        $total_dr_amount += $party['dr_amount'];
                        $total_cr_amount += $party['cr_amount'];
                    @endphp

                    <tr>
                        <td style="font-size: 13px; text-align:left !important;background-color:#bbdee2;width:10%;"> Date </td>
                        <td style="font-size: 13px;  text-align:left !important;background-color:#bbdee2; width:25%;"> Narration </td>
                        <td style="font-size: 13px;  text-align:left !important;background-color:#bbdee2; width:30%;"> Reference</td>
                        <td style="font-size: 13px;  text-align:right !important;background-color:#bbdee2; width:10%;"> Debit  </td>
                        <td style="font-size: 13px;  text-align:right !important;background-color:#bbdee2; width:10%;">  Credit </td>
                        <td style="font-size: 13px;  text-align:right !important;background-color:#bbdee2; width:15%;"> Balance </td>
                    </tr>

                    @php
                        $balance_dr =  0.00;
                        $balance_cr = 0.00;
                        $balance=0;
                    @endphp

                    @foreach ($party['items'] as $item)
                    @php
                        $journal=App\Journal::find($item->journal_id);
                        $isPayment=true;
                        if(!$journal->receipt_id && !$journal->payment_id){
                            $isPayment=false;
                        }
                    @endphp

                    @php
                        $cr_amount = $item->transaction_type == 'CR' ? $item->amount : 0.00;
                        $dr_amount = $item->transaction_type == 'DR' ? $item->amount : 0.00;
                        $balance_dr += $dr_amount;
                        $balance_cr += $cr_amount;
                    @endphp
                    <tr>
                        <td style="text-align:left; font-size:13px;"> {{date('d/m/Y',strtotime($journal->date))}} </td>
                        <td style="text-align:left !important; font-size:13px;"> {{$journal->party_journal_description($journal->id)['name'] }} </td>
                        <td style="text-align:left !important; font-size:13px;text-transform:uppercase;font-size:12px"> {{$journal->party_journal_description($journal->id)['tasks']}} </td>
                        @if($journal->invoice_id)
                        <td style="text-align:right !important; font-size:13px;"> {{$b=$journal->records()->where('account_head_id','!=',31)->where('transaction_type','DR')->sum('amount')}} </td>
                        <td style="text-align:right !important; font-size:13px;">0.00</td>
                        @php
                            $balance_dr +=  $b;
                        @endphp
                        @else
                        <td style="text-align:right !important; font-size:13px;">0.00</td>
                        <td style="text-align:right !important; font-size:13px;">{{$c=$journal->records()->where('account_head_id','!=',407)->where('transaction_type','DR')->sum('amount')}}</td>
                        @php
                            $balance_dr -=  $c;
                        @endphp
                        @endif

                        <td style="text-align:right !important; font-size:13px;"> {{$balance_dr>$balance_cr? 'DR ':'CR '}} {{number_format(abs($balance_dr-$balance_cr),2,'.','')}} </td>
                    </tr>
                    @endforeach

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

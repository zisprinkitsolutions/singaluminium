<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> General ledger PDF Report</title>
    <style>
        @page {
           margin: 100px 25px 120px 25px;
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
           bottom: -80px;
           left: 0px;
           right: 0px;
           height: 50px;
           font-size: 20px !important;
           text-align: center;
       }
   </style>
</head>
<body>
    @include('layouts.backend.partial.pdf-header')

   <div class="footer" style="font-size: 13px;">
        <div style="text-align: left; line-height: 1.5;">
            <span style="font-size: 13px; vertical-align: middle; margin-right: 10px;">
                System software power by Zicash
            </span>
            <img src="{{ $logo }}" alt="zisprink" width="60" style="vertical-align: middle;">
        </div>
   </div>

   <div class="content">
        @if($index = 1)
        <div class="text-center invoice-view-wrapper">
            @if($from && $to)
            <h5> Party ledger report from {{date('d/m/Y',strtotime($from))}} to {{date('d/m/Y', strtotime($to))}}</h5>
            @elseif ($from)
            <h5>  Party ledger report  {{date('d/m/Y',strtotime($from))}}</h5>
            @elseif($to)
            <h5>  Party ledger report  {{date('d/m/Y',strtotime($to))}}</h5>
            @elseif($year || $month)
                 Party Ledger report {{$month ? date('F',strtotime($month)) : ' '}} {{$year}}
            @else
                <h5>  Party ledger report </h5>
            @endif
        </div>
        @endif
        <div class="" style="margin-top:10px;">
             <table>
                <thead>
                    <tr>
                        <th style="background-color: #DCDCDC; font-size:14px; text-align:left; padding-left:6px;"> Name  </th>
                        <th style="background-color: #DCDCDC; font-size:14px; text-align:center;width:10%"> Code </th>
                        <th style="background-color: #DCDCDC; font-size:14px; text-align:center; padding-left:6px;width:10%"> Type </th>

                        <th style="background-color: #DCDCDC; font-size:14px; text-align:right;width:10%"> Debit </th>
                        <th style="background-color: #DCDCDC; font-size:14px; text-align:right; width:10%;"> Credit</th>
                        <th style="background-color: #DCDCDC; font-size:14px; text-align:right; padding-left:6px; width:15%;"> Balance(<small style="font-size: 11px;">{{$currency->symbole}}</small>) </th>
                        <th style="background-color: #DCDCDC; font-size:14px; text-align:cebter; padding-left:6px; width:10%;"> Remark </th>
                    </tr>
                </thead>
               <tbody>
                    @php
                        $total_dr_amount = 0;
                        $total_cr_amount = 0;
                    @endphp
                    @foreach ($parties as $party)
                    <tr>
                        <td style="padding: 8px; font-size:12px; text-align: left; color:#313131;">{{ $party->pi_name}}</td>
                        <td style="padding: 8px; font-size:13px; text-align: center; color:#313131;">{{ $party->pi_code }}</td>
                        <td style="padding: 8px; font-size:13px; text-align: center; color:#313131;">{{ $party->pi_type }}</td>
                        <td style="padding: 8px; font-size:13px; text-align: right; color:#313131;">{{ $party->dr_amount }}</td>
                        <td style="padding: 8px; font-size:13px; text-align: right; color:#313131;">{{ $party->cr_amount }}</td>
                        <td style="padding: 8px; font-size:13px; text-align: right; color:#313131;">{{ number_format(abs($party->dr_amount - $party->cr_amount), 2,'.', '') }}</td>
                        @if($party->dr_amount > $party->cr_amount)
                        <td style="padding: 8px; font-size:13px; text-align: center; color:#313131;"> Receivable  </td>
                        @elseif($party->cr_amount > $party->dr_amount)
                        <td style="padding: 8px; font-size:13px; text-align: center; color:#313131;"> Payable  </td>
                        @else
                        <td> </td>
                        @endif
                    </tr>
                    @php
                        $total_dr_amount += $party->dr_amount;
                        $total_cr_amount += $party->cr_amount;
                    @endphp
                    @endforeach
                    {{-- <tr>
                        <td colspan="3" style="padding: 8px; font-size:14px; text-align: center; color:#313131;"> Total </td>
                        <td style="padding: 8px; font-size:14px; text-align: right; color:#313131;">{{ $total_dr_amount }}</td>
                        <td style="padding: 8px; font-size:14px; text-align: right; color:#313131;">{{ $total_cr_amount }}</td>
                        <td style="padding: 8px; font-size:14px; text-align: right; color:#313131;">{{ number_format(abs($total_dr_amount - $total_cr_amount), 2,'.','') }}</td>
                        <td style="padding: 8px; font-size:14px; text-align: center; color:#313131;">{{ $party->dr_amount > $party->cr_amount ? 'Receivable' : 'Payable'}}</td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Trial Balance PDF Report</title>
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
           font-weight: 500;
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
            font-size: 13px;
            color: #474343;
            text-align: center;
       }
       th{
           font-size: 14px;
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
    <p class="page-number" style="font-size:12px;"></p>
        <div style="text-align: left; line-height: 1.5;">
            <span style="font-size: 13px; vertical-align: middle; margin-right: 10px;">
                System software power by ziKash
            </span>
            <img src="{{ $logo }}" alt="zisprink" width="60" style="vertical-align: middle;">
        </div>
   </div>

   <div class="content">
        <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin: 10px 0;">
            <thead>
                <tr>
                    <th colspan="3" style="text-align: center; font-size: 16px; font-weight: bold; padding: 10px; border: 1px solid #000;">
                        Trial Balance
                        <small>{{ date('d/m/Y', strtotime($date)) }} - {{ date('d/m/Y', strtotime($date1)) }}</small>
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid #000; padding: 5px; text-align: left;">A/C Head</th>
                    <th style="border: 1px solid #000; padding: 5px; text-align: right;">Debit <small style="font-size:11px;">({{$currency->symbole}})</small> </th>
                    <th style="border: 1px solid #000; padding: 5px; text-align: right;">Credit <small style="font-size:11px;">({{$currency->symbole}})</small></th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_dr_amount = 0;
                    $total_cr_amount = 0;
                @endphp

                @foreach ($master_accounts as $key => $master_account)
                    @php
                        $dr_amount = array_sum(array_column($master_account['account_heads'], 'total_dr_amount'));
                        $cr_amount = array_sum(array_column($master_account['account_heads'], 'total_cr_amount'));
                        $total_dr_amount += $dr_amount;
                        $total_cr_amount += $cr_amount;
                        $balance_ma = $dr_amount - $cr_amount;
                    @endphp

                    <!-- Master Account Row -->
                    <tr>
                        <td style="border: 1px solid #000; padding: 5px; text-align:left; background-color: #f2f2f2; font-weight: bold;">
                            {{ strtoupper($master_account['name']) }}
                        </td>
                        <td style="border: 1px solid #000; padding: 5px; background-color: #f2f2f2; font-weight: bold; text-align:right">
                            {{ number_format($dr_amount,2) }}
                        </td>
                        <td style="border: 1px solid #000; padding: 5px; background-color: #f2f2f2; font-weight: bold; text-align:right;">
                            {{ number_format($cr_amount,2) }}
                        </td>
                    </tr>

                    <!-- Sub Accounts Rows -->
                    @foreach ($master_account['account_heads'] as $account_head)
                        @php
                            $balance = $account_head->total_dr_amount - $account_head->total_cr_amount;
                        @endphp
                        <tr>
                            <td style="border: 1px solid #000; padding: 5px 20px; font-size:12px !important; text-align:left;">{{ $account_head->fld_ac_head }}</td>
                            <td style="border: 1px solid #000; padding: 5px; text-align: right;">
                                {{ number_format($account_head->total_dr_amount, 2) }}
                            </td>
                            <td style="border: 1px solid #000; padding: 5px; text-align: right;">
                                {{ number_format($account_head->total_cr_amount, 2) }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach

                <!-- Grand Total -->
                <tr>
                    <td style="border: 1px solid #000; padding: 5px; font-weight: bold;">Grand Total</td>
                    <td style="border: 1px solid #000; padding: 5px; text-align: right; font-weight: bold;">
                        {{ number_format($total_dr_amount, 2) }}
                    </td>
                    <td style="border: 1px solid #000; padding: 5px; text-align: right; font-weight: bold;">
                        {{ number_format($total_cr_amount, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</head>
</html>

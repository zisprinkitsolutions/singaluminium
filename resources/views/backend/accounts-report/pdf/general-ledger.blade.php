<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> General ledger PDF Report</title>
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
    @include('layouts.backend.partial.pdf-header', [
        'name' => $company_name,
        'image' => $image,
    ])

   <div class="footer" style="font-size: 13px;">
    <p class="page-number" style="font-size:12px;"></p>
        <div style="text-align: left; line-height: 1.5;">
            <span style="font-size: 13px; vertical-align: middle; margin-right: 10px;">
                System software power by ziCash
            </span>
            @if($logo)
                <img src="{{ $logo }}" alt="zisprink" width="60" style="vertical-align: middle;">
            @endif

        </div>
   </div>

   <div class="content">
    <div style="text-align: center">
        <h4 class="text-center" style="color: #313131; font-size:18px; font-weight:500; text-transform:uppercase">
          {{$company_name}} (General Ledger)
        </h4>
        @if ($year)
        <p style="color:#313131; font-weight:400;"> Year:{{$year}} </p>
        @endif
        @if ($to && $from)
        <p style="color:#313131; font-weight:400;"> From {{date('d/m/Y', strtotime($from))}} To {{date('d/m/Y', strtotime($to))}} </p>
        @elseif ($from)
        <p style="color:#313131">  Date {{date('d/m/Y', strtotime($from))}} </>
        @elseif ($from)
        <p style="color:#313131"> Date {{date('d/m/Y', strtotime($from))}} </>
        @endif
    </div>

       <div class="mt-1">
        <table>
            <thead>
                <tr>
                    <th style="background-color: #DCDCDC; font-size:16px; text-align:left; padding-left:4px;"> Account Head </th>
                    <th style="background-color: #DCDCDC; font-size:16px; text-align:right;"> Debit(<small style="font-size: 11px;">{{$currency->symbole}}</small>) </th>
                    <th style="background-color: #DCDCDC; font-size:16px; text-align:right;"> Credit(<small style="font-size: 11px;">{{$currency->symbole}}</small>) </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_dr_amount = 0;
                    $total_cr_amount = 0;
                @endphp
                @foreach ($records as $record)
                <tr>
                    <td style="padding: 4px; font-size:13px; text-align: left; color:#313131;">{{ $record->fld_ac_head}}</td>
                    <td style="padding: 4px; font-size:14px; text-align: right; color:#313131;">{{ number_format($record->dr_amount,2,'.','') }}</td>
                    <td style="padding: 4px; font-size:14px; text-align: right; color:#313131;">{{ number_format($record->cr_amount,2,'.','') }}</td>
                </tr>
                @php
                    $total_dr_amount += $record->dr_amount;
                    $total_cr_amount += $record->cr_amount;
                @endphp
                @endforeach
                <tr>
                    <td style="padding: 4px; font-size:14px; text-align: right; color:#313131;"> Balance C/D  </td>
                    <td style="padding: 4px; font-size:14px; text-align: right; color:#313131;">  {{number_format(($total_dr_amount > $total_cr_amount ? 0.00 : $total_cr_amount - $total_dr_amount), 2, '.' , '') }} </td>
                    <td style="padding: 4px; font-size:14px; text-align: right; color:#313131;">  {{number_format(($total_dr_amount > $total_cr_amount ? $total_dr_amount - $total_cr_amount: 0.00), 2, '.' , '') }} </td>
                </tr>

                <tr>
                    <td style="padding: 4px; font-size:14px; text-align: right; background-color: #DCDCDC; color:#313131;"> Total </td>
                    <td style="padding: 4px; font-size:14px; text-align: right; background-color: #DCDCDC; color:#313131;"> {{number_format(($total_dr_amount), 2, '.' , '') }} </td>
                    <td style="padding: 4px; font-size:14px; text-align: right; background-color: #DCDCDC; color:#313131;"> {{number_format(($total_cr_amount), 2, '.' , '') }} </td>
                </tr>

            </tbody>
        </table>
    </div>
</body>
</html>

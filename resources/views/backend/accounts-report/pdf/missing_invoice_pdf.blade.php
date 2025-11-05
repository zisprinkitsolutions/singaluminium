<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> General ledger PDF Report</title>
    <style>
        @page {
           margin: 100px 25px 50px 25px;
           size: A4;
       }
       body {
            font-family: sans-serif;
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
           font-weight: 500;
           margin-bottom: 16px;
           color: #474343;
           font-size: 22px;
           font-family: sans-serif;
       }
       h4{
           text-align: center;
       }

       h5{
           font-size: 17px;
           font-weight: 500;
       }

       .currency{
           font-size: 14px;
           text-align: center;
           color: #474343;
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
            text-transform:capitalize;
            border-collapse: collapse;
            line-height: 28px;
            color: #474343;
            font-size:13px;
       }
       td{
            font-weight: 400;
            text-align: center;
       }
       th{
           font-size: 14px;
           font-weight: 400;
       }
       table{
           width:100%;
       }
       .head-details td{
            color: #474343;
            font-weight: 500;
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
        <div style="text-align: left; line-height: 1.5;">
            <span style="font-size: 13px; vertical-align: middle; margin-right: 10px;">
                System software power by Zicash
            </span>
            <img src="{{ $logo }}" alt="zisprink" width="60" style="vertical-align: middle;">
        </div>
    </div>

   <div class="content">
        <div style="text-align: center">
            <p style="background-color: #f2f2f2; font-size:18px; font-weight:400; padding:6px;" colspan="5">
                Missing Invoice
                @if ($to && $from)
                    <p style="color:#313131; font-weight:400;"> From {{date('d/m/Y', strtotime($from))}} To {{date('d/m/Y', strtotime($to))}} </p>
                @elseif ($from)
                    <p style="color:#313131; font-weight:400">  Date {{date('d/m/Y', strtotime($from))}} </p>
                @elseif ($from)
                    <p style="color:#313131; font-weight:400"> Date {{date('d/m/Y', strtotime($from))}} </p>
                @elseif ($year and $month)
                    <p style="color:#313131; font-weight:400"> {{DateTime::createFromFormat('!m', $month)->format('F')}}({{$year}}) </p>
                @elseif ($year && $month)
                        <p style="color:#313131; font-weight:400"> {{$year}} </p>
                @elseif (!$year && $month)
                    <p style="color:#313131; font-weight:400"> {{DateTime::createFromFormat('!m', $month)->format('F')}} </p>
                @elseif ($year && !$month)
                    <p style="color:#313131; font-weight:400"> {{$year}} </p>
                @endif
            </p>
        <div>
       <div class="mt-1">
        <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin: 10px 0; border: 1px solid #000;">
            <thead>
                <tr style="background-color: #f2f2f2; border-bottom: 1px solid #000;">
                    <th style="padding:4px; text-align: left;font-size:15px;"> Missing Date </th>
                    <th style="padding:4px; text-align: right; font-size:15px;"> Probably Missing Invoice </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($missing_invoices as $item)
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding:4px;text-align:left;">{{$item->missing_date}}</td>
                    <td style="padding:4px;text-align:right;">{{$item->invoice_number}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>

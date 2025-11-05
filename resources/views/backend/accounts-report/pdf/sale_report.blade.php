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
           text-align: right;
           padding: 4px 10px;
           border-collapse: collapse;
       }
       td{
            font-weight: 400;
            font-size: 14px;
            color: #474343;
            text-align: right;
       }
       th{
           font-size: 15px;
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
                System software power by Zicash
            </span>
            <img src="{{ $logo }}" alt="zisprink" width="60" style="vertical-align: middle;">
        </div>
   </div>

   <div class="content">
        <div class="text-center invoice-view-wrapper">
            @if($from && $to)
            <h5> Sale report from {{date('d/m/Y',strtotime($from))}} to {{date('d/m/Y', strtotime($to))}}</h5>
            @elseif ($from)
            <h5>  Sale report  {{date('d/m/Y',strtotime($from))}}</h5>
            @elseif($to)
            <h5> Sale report  {{date('d/m/Y',strtotime($to))}}</h5>
            @elseif($year || $month)
             Sale report {{$month ? date('F',strtotime($month)) : ' '}} {{$year}}
            @else
                <h5>  Sale report </h5>
            @endif
        </div>

        <div class="" style="margin-top:10px;">
            <table class="table mb-0 table-sm">
                <thead class="thead">
                    <tr>
                        <th style="width:45%; text-align:left;">Month</th>
                        <th style="width:15%;" class="amount-column">Total</th>
                        <th style="width:15%;" class="amount-column">Paid</th>
                        <th style="width:15%;" class="amount-column">Due <small style="font-size:12px;">(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $displayedYears = [];
                    @endphp

                    @foreach ($records as $key => $month)
                        @if (!in_array($month->year, $displayedYears))
                            <!-- Display Year Header -->
                            <tr>
                                <td colspan="4" class="text-center" style="background-color:#e2e2e2;font-size:14px !important; text-align:center">{{$month->year}}</td>
                            </tr>
                            @php
                                $displayedYears[] = $month->year;
                            @endphp
                        @endif

                        <!-- Monthly Invoice Row -->
                        <tr class="data-toggler">
                            <td>
                                <div class="d-flex align-items-center" style="font-size: 14px; text-align:left">
                                    {{ \Carbon\Carbon::createFromFormat('m', $month->month)->format('F') }}
                                </div>
                            </td>
                            <td class="amount-column">{{$month->total_amount}}</td>
                            <td class="amount-column">{{$month->paid_amount}}</td>
                            <td class="amount-column">{{$month->due_amount}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

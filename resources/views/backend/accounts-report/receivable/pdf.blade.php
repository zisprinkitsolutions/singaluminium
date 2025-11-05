
<html>
    <head>
        <title>{{$pdf_title}} </title>
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
                font-size: 18px;
                text-align: center;
            }

            h5{
                font-size: 15px;
            }

            .currency{
                font-size: 12px;
                text-align: center;
                color: #333333;
            }

            table{
                border-collapse: collapse;
                border: 1px solid #ddd;
            }
            th, td{
                font-weight: 400;
                border: 1px solid #ddd;
                text-align: left;
                padding: 4px 10px;
                color: #333333;
                font-size: 12px;
                text-transform: capitalize;
                border-collapse: collapse;
            }
            th{
                font-size: 13px;
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
                <span style="font-size: 12px; vertical-align: middle; margin-right: 10px;">
                    Zikash Make Business Easy
                </span>
                <img src="{{ $logo }}" alt="zisprink" width="70" style="vertical-align: middle;">
            </div>
        </div>

        <div class="content">
            <h4> {{$pdf_title}} </h4>
            <p class="currency">All amounts are represented in AED (United Arab Emirates Dirham) </p>

            @if (count($three_month_data) > 0)
            <div class="d-flex justify-content-between align-items-center">
                @php
                    $three_month_data = collect($three_month_data);
                    $sum = $three_month_data->sum('due_amount');
                @endphp
                <h5 class="text-center"> Past 3 months {{$type}} amount:{{number_format($sum,2,'.','')}} </h5>
            </div>

            <div class="mt-1 three_month">
                <table class="table table-sm parent-table">
                    <thead>
                        <tr>
                            <th style="background-color: #f5f5f5"> Party Name </th>
                            <th style="text-align: right;background-color:#f5f5f5"> {{$type}} Amount </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($three_month_data as $item)
                            <tr class="data_toggle" data-fetch="1">
                                <td style="text-align:left;padding:0 10px;width:70%"> {{$item->pi_name}} </td>
                                <td style="width:30%;text-align:right"> {{$item->due_amount}} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if(count($six_month_data) > 0)
                @php
                    $six_month_data = collect($six_month_data);
                    $sum = $six_month_data->sum('due_amount');
                @endphp
                <h5 class="text-center">  {{ucfirst($type)}} amount between 3 and 6 months:{{number_format($sum,2,'.','')}} </h5>

                <div class="six_month">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th style="background-color: #f5f5f5"> Party Name </th>
                                <th style="text-align: right;background-color:#f5f5f5"> {{$type}} Amount </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($six_month_data as $item)
                            <tr class="data_toggle" data-fetch="1">
                                <td style="text-align:left;padding:0 10px;width:70%; text-transform:uppercase; font-size:10px;font-weight:normal;"> {{$item->pi_name}} </td>
                                <td style="text-align:right;width:30%"> {{$item->due_amount}} </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        @endif


        @if(count($twelve_month_data) > 0)
            @php
                $six_month_data = collect($twelve_month_data);
                $sum = $six_month_data->sum('due_amount');
            @endphp
             <h5 class="text-center">{{ucfirst($type)}} amount between 6 and 12 months:{{number_format($sum,2,'.','')}} </h5>
            <div class="twelve_month mt-1">
                <table class="table table-sm parent-table">
                    <thead>
                        <tr>
                            <th style="background-color: #f5f5f5"> Party Name </th>
                            <th style="text-align: right;background-color:#f5f5f5"> {{$type}} Amount </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($six_month_data as $item)
                        <tr class="data_toggle" data-fetch="1">
                            <td style="text-align:left;padding:0 10px;width:70%"> {{$item->pi_name}} </td>
                            <td style="text-align: right;width:30%"> {{$item->due_amount}} </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @if(count($old_month_data) > 0)
            @php
            $six_month_data = collect($old_month_data);
                $sum = $six_month_data->sum('due_amount');
            @endphp
            <h5 class="text-center"> One year plus {{$type}} amount:{{number_format($sum,2,'.','')}} </h5>
            <div class="old_month mt-1">
                <table class="table table-sm parent-table">
                    <thead>
                        <tr>
                            <th style="background-color: #f5f5f5"> Party Name </th>
                            <th style="text-align: right;background-color:#f5f5f5"> {{$type}} Amount </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($six_month_data as $item)
                        <tr class="data_toggle" data-fetch="1">
                            <td style="text-align:left;padding:0 10px;width:70%"> {{$item->pi_name}} </td>
                            <td style="text-align: center;width:30%;text-align:right"> {{$item->due_amount}} </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        </div>
    </body>
</html>


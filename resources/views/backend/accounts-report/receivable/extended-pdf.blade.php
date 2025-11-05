<html>
    <title>{{$pdf_title}} </title>
    <head>
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
                font-size: 17px;
            }

            .currency{
                font-size: 13px;
                text-align: center;
                color: #292929;
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
                border-collapse: collapse;
            }
            td{
                font-size: 13px;
                text-align: center;
                color: #444;
            }
            th{
                font-size: 14px;
                font-weight: 400;
                color: #444;
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
            .three_month th{
                text-align: center;

                font-weight: 500;
            }

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
                <img src="{{ $image }}" alt="zisprink" width="70" style="vertical-align: middle;">
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
            </div>

            <div class="mt-1">
                <table class="table table-sm parent-table">
                    <thead>
                        <tr>
                            <th colspan="6" style="background-color: #e3e6ea; text-align:center; font-weight:400; font-size:16px; padding: 10px;">
                                Past 3 Months {{ ucfirst($type) }} Amount: {{ number_format($sum, 2, '.', '') }}
                            </th>
                        </tr>
                        <tr style="background-color: #f8f9fa; font-weight: 400; text-transform: uppercase;">
                            <th style="width: 12%; text-align:left; padding: 8px;font-size:12px;">Date</th>
                            <th style="width: 20%; text-align: center;font-size:12px;">Aging Period</th>
                            <th style="width: 20%; text-align: center;font-size:12px;">{{ $number_column }}</th>
                            <th style="width: 15%; text-align: center;font-size:12px;">Total</th>
                            <th style="width: 10%; text-align: center;font-size:12px;">Paid</th>
                            <th style="width: 10%; text-align:right;font-size:12px;">Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($three_month_data as $data)
                        <tr style="background-color: #f1f3f5; font-weight: 400;">
                            <td colspan="3" style="text-align:left; padding: 8px;text-transform:uppercase;font-size:12px;">{{ $data['pi_name'] }}</td>
                            <td colspan="3" style="text-align:right; padding: 8px;">{{ number_format($data['due_amount'],2,'.','') }}</td>
                        </tr>
                        @foreach ($data['items'] as $item)
                        @php
                            $givenDate = \Carbon\Carbon::parse($item->date);
                            $diffForHumans = $givenDate->diffForHumans();
                        @endphp
                        <tr class="invoice_show" style="background-color: #ffffff;">
                            <td style="text-align:right; padding: 8px;">{{ date('d/m/Y', strtotime($item->date)) }}</td>
                            <td class="text-center" style="padding: 8px;">{{ $diffForHumans }}</td>
                            <td style="text-align: center; padding: 8px;">{{ $item->invoice_no }}</td>
                            <td style="text-align: center; padding: 8px;">{{ number_format($item->total_budget,2,'.','') }}</td>
                            <td style="text-align: center; padding: 8px;">{{ number_format($item->paid_amount,2,'.','') }}</td>
                            <td style="text-align:right; padding: 8px;">{{number_format($item->due_amount,2,'.','') }}</td>
                        </tr>
                        @endforeach
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
            <div class="six_month">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th colspan="6" style="background-color: #e3e6ea; text-align:center; font-weight:400; font-size:16px; padding: 10px;">
                                {{ucfirst($type)}} between 3 and 6 months:{{number_format($sum,2,'.','')}}
                            </th>
                        </tr>
                        <tr style="background-color: #f8f9fa; font-weight: 400; text-transform: uppercase;">
                            <th style="width: 12%; text-align:left; padding: 8px;font-size:12px;">Date</th>
                            <th style="width: 20%; text-align: center;font-size:12px;">Aging Period</th>
                            <th style="width: 20%; text-align: center;font-size:12px;">{{ $number_column }}</th>
                            <th style="width: 15%; text-align: center;font-size:12px;">Total</th>
                            <th style="width: 10%; text-align: center;font-size:12px;">Paid</th>
                            <th style="width: 10%; text-align:right;font-size:12px;">Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($six_month_data as $data)
                        <tr style="background-color: #f1f3f5; font-weight: 400;">
                            <td colspan="3" style="text-align:left; padding: 8px;text-transform:uppercase;font-size:12px;">{{ $data['pi_name'] }}</td>
                            <td colspan="3" style="text-align:right; padding: 8px;">{{ number_format($data['due_amount'],2,'.','') }}</td>
                        </tr>
                        @foreach ($data['items'] as $item)
                        @php
                            $givenDate = \Carbon\Carbon::parse($item->date);
                            $diffForHumans = $givenDate->diffForHumans();
                        @endphp
                        <tr class="invoice_show" style="background-color: #ffffff;">
                            <td style="text-align:right; padding: 8px;">{{ date('d/m/Y', strtotime($item->date)) }}</td>
                            <td class="text-center" style="padding: 8px;">{{ $diffForHumans }}</td>
                            <td style="text-align: center; padding: 8px;">{{ $item->invoice_no }}</td>
                            <td style="text-align: center; padding: 8px;">{{ number_format($item->total_budget,2,'.','') }}</td>
                            <td style="text-align: center; padding: 8px;">{{ number_format($item->paid_amount,2,'.','') }}</td>
                            <td style="text-align:right; padding: 8px;">{{ number_format($item->due_amount,2,'.','') }}</td>
                        </tr>
                        @endforeach
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
            <h5 class="text-center">{{ucfirst($type)}}  6 and 12 months:{{number_format($sum,2,'.','')}} </h5>
            <div class="twelve_month mt-1">
                <table class="table table-sm parent-table">
                    <thead>
                        <tr>
                            <th colspan="6" style="background-color: #e3e6ea; text-align:center; font-weight:400; font-size:16px; padding: 10px;">
                                {{ucfirst($type)}} between 6 and 12 months:{{number_format($sum,2,'.','')}}
                            </th>
                        </tr>
                        <tr style="background-color: #f8f9fa; font-weight: 400; text-transform: uppercase;">
                            <th style="width: 12%; text-align:left; padding: 8px;font-size:12px;">Date</th>
                            <th style="width: 20%; text-align: center;font-size:12px;">Aging Period</th>
                            <th style="width: 20%; text-align: center;font-size:12px;">{{ $number_column }}</th>
                            <th style="width: 15%; text-align: center;font-size:12px;">Total</th>
                            <th style="width: 10%; text-align: center;font-size:12px;">Paid</th>
                            <th style="width: 10%; text-align:right;font-size:12px;">Due</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($twelve_month_data as $data)
                        <tr style="background-color: #f1f3f5; font-weight: 400;">
                            <td colspan="3" style="text-align:left; padding: 8px;text-transform:uppercase;font-size:12px;">{{ $data['pi_name'] }}</td>
                            <td colspan="3" style="text-align:right; padding: 8px;">{{ number_format($data['due_amount'],2,'.','') }}</td>
                        </tr>
                        @foreach ($data['items'] as $item)
                        @php
                            $givenDate = \Carbon\Carbon::parse($item->date);
                            $diffForHumans = $givenDate->diffForHumans();
                        @endphp
                        <tr class="invoice_show" style="background-color: #ffffff;">
                            <td style="text-align:right; padding: 8px;">{{ date('d/m/Y', strtotime($item->date)) }}</td>
                            <td class="text-center" style="padding: 8px;">{{ $diffForHumans }}</td>
                            <td style="text-align: center; padding: 8px;">{{ $item->invoice_no }}</td>
                            <td style="text-align: center; padding: 8px;">{{ number_format($item->total_budget,2,'.','') }}</td>
                            <td style="text-align: center; padding: 8px;">{{ number_format($item->paid_amount,2,'.','') }}</td>
                            <td style="text-align:right; padding: 8px;">{{ number_format($item->due_amount,2,'.','') }}</td>
                        </tr>
                        @endforeach
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

            <div class="old_month mt-1">
                <table class="table table-sm parent-table">
                    <thead>
                        <tr>
                            <th colspan="6" style="background-color: #e3e6ea; text-align:center; font-weight:400; font-size:16px; padding: 10px;">
                                One year plus {{ucfirst($type)}} amount
                            </th>
                        </tr>

                        <tr style="background-color: #f8f9fa; font-weight: 400; text-transform: uppercase;">
                            <th style="width: 12%; text-align:left; padding: 8px;font-size:12px;">Date</th>
                            <th style="width: 20%; text-align: center;font-size:12px;">Aging Period</th>
                            <th style="width: 20%; text-align: center;font-size:12px;">{{ $number_column }}</th>
                            <th style="width: 15%; text-align: center;font-size:12px;">Total</th>
                            <th style="width: 10%; text-align: center;font-size:12px;">Paid</th>
                            <th style="width: 10%; text-align:right;font-size:12px;">Due</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($old_month_data as $data)
                        <tr style="background-color: #f1f3f5; font-weight: 400;">
                            <td colspan="3" style="text-align:left; padding: 8px;text-transform:uppercase;font-size:12px;">{{ $data['pi_name'] }}</td>
                            <td colspan="3" style="text-align:right; padding: 8px;">{{ number_format($data['due_amount'],2,'.','') }}</td>
                        </tr>

                        @foreach ($data['items'] as $item)
                        @php
                            $givenDate = \Carbon\Carbon::parse($item->date);
                            $diffForHumans = $givenDate->diffForHumans();
                        @endphp
                        <tr class="invoice_show" style="background-color: #ffffff;">
                            <td style="text-align:right; padding: 8px;">{{ date('d/m/Y', strtotime($item->date)) }}</td>
                            <td class="text-center" style="padding: 8px;">{{ $diffForHumans }}</td>
                            <td style="text-align: center; padding: 8px;">{{ $item->invoice_no }}</td>
                            <td style="text-align: center; padding: 8px;">{{ number_format($item->total_budget,2,'.','') }}</td>
                            <td style="text-align: center; padding: 8px;">{{ number_format($item->paid_amount,2,'.','') }}</td>
                            <td style="text-align:right; padding: 8px;">{{ number_format($item->due_amount,2,'.','') }}</td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        </div>

    </body>
</html>


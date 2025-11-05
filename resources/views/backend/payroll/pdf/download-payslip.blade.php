<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>PAYSLIP</title>
        <meta name="author" content="USER" />
        <link rel="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" href="">

        <style type="text/css">

            @page {
                size: A4;
                margin: 1cm;
            }

            .print {
                display: none;
            }

            @media print {
                div.fix-break-print-page {
                    page-break-inside: avoid;
                }

                .print {
                    display: block;
                }
            }

            .print:last-child {
                page-break-after: auto;
            }
            body{
                font-family: Arial, Helvetica, sans-serif;
            }
            .a,
            a {
                color: #aca8a8;
                font-family: Cambria, serif;
                font-style: normal;
                font-weight: normal;
                text-decoration: none;
                font-size: 10pt;
            }
            table {
                border-collapse: collapse;
                width: 80%;
            }

            td{
            /* border: 1px dotted #dddddd; */
                text-align: left;
                padding: 8px;
                font-size:14px;
            }
            th{
                border: 1px dotted #070707;
                background: rgb(198, 192, 192);
                font-size: 17px;
            }
            .row {
                display: flex;
                text-align: center;
                justify-content: space-around;
            }
            .column {
                width: 80%;
                padding: 10px;
            }
            .column2 {
                width: 70%;
                padding: 10px;
            }
            hr{
                width: 40%;
            }
            .center {
                margin-left: auto;
                margin-right: auto;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

        </style>
    </head>

    <body style="">
        <div>
            <div class="header">
                <h2 style="text-align:left">PAY SUMMARY </h2>
                <table style="border: .2px dotted #447264;width:410px ! important;">
                    <tr>
                        <td style="border: .2px dotted black;width:35%"> Employee name </td>
                        <td style="border: .2px dotted black;width:65%">{{$name}}</td>
                    </tr>

                    <tr>
                        <td style="border: .2px dotted black;width:35%"> Year </td>
                        <td style="border: .2px dotted black;width:65%">{{$year}} </td>
                    </tr>
                </table>
            </div>
            @php
                $total_netpay = 0;
            @endphp
            @foreach($salarys as $index => $data)
            @php
                $netpay = 0;
                $extra_amount = $data['overtime_amount'] - $data['late_amount'] - $data['total_absen_penalty'];
                $netpay = $data['basic_salary_current_day'] + $extra_amount;
                $total_netpay += $netpay;
            @endphp

            <div>
                <div class="d-flex justify-content-between align-items-center">
                    <h2 style="border-bottom: 2px solid #26b2d4; padding-bottom:10px;">
                        Pay slip for the month of {{ $data['month'] }}, {{ $data['year'] }}
                    </h2>
                    <div>
                        <p>NET PAY : AED {{number_format($netpay, 2) }} /- </p>
                    </div>
                </div>

                <div>
                    <div class="row">
                        <div class="column" style="margin-right:20px;margin-top:20px">
                            <table class="center w-100" style="border: 1px dotted black;">
                                <thead>
                                    <tr>
                                        <th scope="col" style="text-align:left;"> Salary</th>
                                        <th scope="col" style="text-align:right;">Amount (AED)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="height: 35px;">
                                        <td style="border: 1px dotted black;width:67%;text-align:left;">Basic Salary</td>
                                        <td style="border: 1px dotted black;width:33%;text-align:right">{{ number_format($data['basic_salary'], 2) }}</td>
                                    </tr>

                                </tbody>
                            </table>


                            <table class="center w-100" style="border: 1px dotted black; margin-top:20px">
                                <thead>
                                    <tr>
                                        <th scope="col" style="text-align:left;"> Earnings/Penalty</th>
                                        <th scope="col">Hours/Days</th>
                                        <th scope="col" style="text-align:right;">Amount (AED)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="height: 35px;">
                                        <td style="border: 1px dotted black;width:50%; text-align:left;">Basic Salary</td>
                                        <td style="border: 1px dotted black;width:25%; text-align:center;">{{$data['currentDayOfMonth']}} day</td>
                                        <td style="border: 1px dotted black;width:25%; text-align:right">{{ number_format($data['basic_salary_current_day'],2) ?? 0 }}</td>
                                    </tr>
                                    <tr style="height: 35px;">
                                        <td style="border: 1px dotted black;width:50%;text-align:left">Total Working Hours (TWH)</td>
                                        <td style="border: 1px dotted black;width:25%;text-align:center;">{{ $data['total_working_hours'] ?? 0 }}</td>
                                        <td style="border: 1px dotted black;width:25%;text-align:right">-</td>
                                    </tr>
                                    <tr style="height: 35px;">
                                        <td style="border: 1px dotted black;width:50%;text-align:left">Total Over Time Hours (TOH)</td>
                                        <td style="border: 1px dotted black;width:25%;text-align:center;">{{ $data['total_overtime'] ?? 0 }}</td>
                                        <td style="border: 1px dotted black;width:25%;text-align:right">{{ number_format($data['overtime_amount'] ?? 0, 2) }}</td>
                                    </tr>
                                    <tr style="height: 35px;">
                                        <td style="border: 1px dotted black;width:50%;text-align:left">Total Late Hours (TLH)</td>
                                        <td style="border: 1px dotted black;width:25%;text-align:center">{{ $data['total_late_time'] ?? 0 }}</td>
                                        <td style="border: 1px dotted black;width:25%;text-align:right">{{ number_format($data['late_amount'] ?? 0, 2) }}</td>
                                    </tr>


                                    <tr style="height: 35px;">
                                        <td style="border: 1px dotted black;width:50%;text-align:left;">Total Absence Day (TAD)</td>
                                        <td style="border: 1px dotted black;width:25%;text-align:center;">{{$data['total_absen'] ?? 0 }}</td>
                                        <td style="border: 1px dotted black;width:25%;text-align:right;">{{ number_format($data['total_absen_penalty'] ?? 0, 2) }}</td>
                                    </tr>


                                    <!-- Grand total row -->
                                    {{-- <tr style="height: 35px;">
                                        <td style="border: 1px dotted black;width:50%;text-align: end; font-weight:bold">GRAND TOTAL</td>
                                        <td style="border: 1px dotted black;width:25%">-</td>
                                        <td style="border: 1px dotted black;width:25%;font-weight:bold">{{ number_format($extra_amount, 2) }}</td>
                                    </tr> --}}
                                </tbody>
                            </table>


                            <table class="center w-100" style="border: 1px dotted black; margin-top:20px">
                                <tr style="height: 35px;">
                                    <td style="border: 1px dotted black;width:67%"><span style="font-weight:bold">NET PAY</span> <span style="font-size: 12px"> (( Gross Earnings – Deductions) + Reimbursements )</span> </td>
                                    <td style="border: 1px dotted black;width:33%;font-weight:bold">{{number_format($netpay,2)}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div style="text-align:left">
                <p style=""><span style="font-weight: bold;">Total Net Payable AED {{number_format($total_netpay,2)}} /-</span> only (Arab Emirates Dirhams {{ucwords((new NumberFormatter('en_IN', NumberFormatter::SPELLOUT))->format($netpay,2))}} Only)</p>
            </div>

            <div style="width:100%; margin-top:10px; ">
                <div class="row">
                    <div class="col-6">
                        <p class="s1" style="margin-right: .8in;text-indent: 0pt;text-align:right; color:gray;"><i>*** {{ $text}}</i> </p>

                    </div>
                    <div class="col-6">
                        <p class="s12" style="margin-right: .8in;text-indent: 0pt;text-align:right; color:gray;"><i>*** This is a system generated Payslip. No signature required</i> </p>

                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

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
            }
            th{
                border: 1px dotted #070707;
                background: rgb(198, 192, 192);
                font-size: 20px;
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
            @foreach($datas as $data)
                @php

                    $netpay = 0;
                    $extra_amount = $data['overtime_amount'] - $data['late_amount'] - $data['total_absen_penalty'];

                    $netpay = $data['basic_salary_current_day'] + $extra_amount;
                @endphp

                <div style="page-break-before: always; margin-top:100px">
                    <div style="margin-left:70px">

                    <h2 style="border-bottom: 2px solid #26b2d4; padding-bottom:10px; width:620px">
                        Pay slip for the month of {{ $data['month'] }}, {{ $data['year'] }}

                    </h2>

                        <h2 style="text-align: left">PAY SUMMARY </h2>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <table style="border: .2px dotted #447264;width:410px ! important;margin-left:30px">
                                <tr>
                                    <td style="border: .2px dotted black;width:35%">Employee name</td>
                                    <td style="border: .2px dotted black;width:65%">{{$data['employee_name']}}</td>
                                </tr>

                                <tr>
                                    <td style="border: .2px dotted black;width:35%">Pay Period</td>
                                    <td style="border: .2px dotted black;width:65%">{{$data['month']}}, {{$data['year']}} ({{$currentDayOfMonth}} day)</td>
                                </tr>
                                {{-- <tr>
                                    <td style="border: .2px dotted black;width:35%">Pay Date</td>
                                    <td style="border: .2px dotted black;width:65%">{{$data->payInfo($data->id, $data->month, $data->year)->format('d/m/Y')}}</td>
                                </tr> --}}
                            </table>
                        </div>
                        <div class="col-4" style="text-align: center; margin-right:60px">
                            <p>NET PAY</p>
                            <h3>AED {{number_format($netpay, 2) }} /-</h3>
                        </div>

                    </div>
                    <h2 style="border-bottom: 2px solid #26b2d4; margin-bottom:-5px; width:620px;margin-left:70px"></h2>
                    <div>
                        <div class="row">
                            <div class="column" style="margin-right:20px;margin-top:20px">
                                <table class="center w-100" style="border: 1px dotted black;">
                                    <thead>
                                    <tr>
                                        <th scope="col"> Salary</th>
                                        <th scope="col">Amount (AED)</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="height: 35px;">
                                            <td style="border: 1px dotted black;width:67%">Basic Salary</td>
                                            <td style="border: 1px dotted black;width:33%;">{{ number_format($data['basic_salary'], 2) }}</td>
                                        </tr>

                                    </tbody>
                                </table>



                                {{-- <table class="center w-100" style="border: 1px dotted black; margin-top:20px">
                                    <thead>
                                    <tr>
                                        <th scope="col"> Over Time , Late &  Absence  </th>
                                        <th scope="col">Amount (AED)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="height: 35px;">
                                            <td style="border: 1px dotted black;width:67%">
                                                ({{ $data['total_working_hours'] ?? 0 }}) <span title="Total Working Hours">TWH</span>,
                                                ({{ $data['total_overtime'] ?? 0 }}) <span title="Total Over Time Hours">TOH</span>,
                                                ({{ $data['total_late_time'] ?? 0 }}) <span title="Total Late Hours">TLH</span>,
                                                ({{ number_format($data['overtime_amount'] ?? 0, 2) }}) <span title="Total Over Time Amount">TOA</span>,
                                                ({{ number_format($data['late_amount'] ?? 0, 2) }}) <span title="Total Late Penalty">TLP</span>,
                                                ({{ number_format($data['total_absen'] ?? 0, 0) }}) <span title="Total Absence">TA</span>,
                                                ({{ number_format($data['total_absen_penalty'] ?? 0, 2) }}) <span title="Total Absence Penalty">TAP</span>
                                            </td>

                                            <td style="border: 1px dotted black;width:33%">
                                                {{number_format($extra_amount,2) }}
                                            </td>
                                        </tr>

                                        <tr style="height: 35px;">
                                            <td style="border: 1px dotted black;width:67%;text-align: end; font-weight:bold">TOTAL  </td>
                                            <td style="border: 1px dotted black;width:33%;font-weight:bold">{{number_format($extra_amount,2) }}</td>
                                        </tr>
                                    </tbody>
                                </table> --}}
                                <table class="center w-100" style="border: 1px dotted black; margin-top:20px">
                                    <thead>
                                        <tr>
                                            <th scope="col"> Earnings/Penalty</th>
                                            <th scope="col">Hours/Days</th>
                                            <th scope="col">Amount (AED)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="height: 35px;">
                                            <td style="border: 1px dotted black;width:50%">Basic Salary</td>
                                            <td style="border: 1px dotted black;width:25%">{{$currentDayOfMonth}} day</td>
                                            <td style="border: 1px dotted black;width:25%">{{ number_format($data['basic_salary_current_day'],2) ?? 0 }}</td>
                                        </tr>
                                        <tr style="height: 35px;">
                                            <td style="border: 1px dotted black;width:50%">Total Working Hours (TWH)</td>
                                            <td style="border: 1px dotted black;width:25%">{{ $data['total_working_hours'] ?? 0 }}</td>
                                            <td style="border: 1px dotted black;width:25%">-</td>
                                        </tr>
                                        <tr style="height: 35px;">
                                            <td style="border: 1px dotted black;width:50%">Total Over Time Hours (TOH)</td>
                                            <td style="border: 1px dotted black;width:25%">{{ $data['total_overtime'] ?? 0 }}</td>
                                            <td style="border: 1px dotted black;width:25%">{{ number_format($data['overtime_amount'] ?? 0, 2) }}</td>
                                        </tr>
                                        <tr style="height: 35px;">
                                            <td style="border: 1px dotted black;width:50%">Total Late Hours (TLH)</td>
                                            <td style="border: 1px dotted black;width:25%">{{ $data['total_late_time'] ?? 0 }}</td>
                                            <td style="border: 1px dotted black;width:25%">{{ number_format($data['late_amount'] ?? 0, 2) }}</td>
                                        </tr>


                                        <tr style="height: 35px;">
                                            <td style="border: 1px dotted black;width:50%">Total Absence Day (TAD)</td>
                                            <td style="border: 1px dotted black;width:25%">{{$data['total_absen'] ?? 0 }}</td>
                                            <td style="border: 1px dotted black;width:25%">{{ number_format($data['total_absen_penalty'] ?? 0, 2) }}</td>
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
                    <div style="text-align:right;width: 80.5%;">
                        <p style=""><span style="font-weight: bold;">Total Net Payable AED {{$netpay}} /-</span> only (Arab Emirates Dirhams {{ucwords((new NumberFormatter('en_IN', NumberFormatter::SPELLOUT))->format($netpay,2))}} Only)</p>
                    </div>

                </div>
                <div style="width:100%; margin-top:10px; ">
                    <div class="row">
                        <div class="col-6">
                            <p class="s1" style="margin-right: .8in;text-indent: 0pt;text-align:right; color:gray;"><i>*** {{ $data['month_number']}}</i> </p>

                        </div>
                        <div class="col-6">
                            <p class="s12" style="margin-right: .8in;text-indent: 0pt;text-align:right; color:gray;"><i>*** This is a system generated Payslip. No signature required</i> </p>

                        </div>
                    </div>
                </div>
            @endforeach
    </body>

</html>

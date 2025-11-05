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
          /* border: 1px solid #dddddd; */
          text-align: left;
          padding: 8px;
        }
        th{
            border: 1px solid #070707; 
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

<body style=""onload="window.print();">
       
        @foreach($datas as $data)
            @php
            // use App\Models\Payroll\SalaryProcess;
                $i = 0;
                $j = 0;
                $earning_sum = 0;
                $deduction_sum = 0;
                $netpay = 0;

                $month = date("m", strtotime($data->month));
                $last = cal_days_in_month(CAL_GREGORIAN, $month, $data->year);
                // dd($datas->components);
                // $component = SalaryProcess::where('employee_id', $data->employee_id)->first();
            @endphp
            <div style="page-break-before: always;">
                <div style="width:100%; text-align:end; margin-top:50px">
                    <img  style="padding-right:70px" width="168"  height="100" src="{{asset('/')}}zisprink.jpg">
                </div>
                <div style="text-align: center;">
                    <h2>Payslip</h2>
                    <p>{{$data->emp->companies->company_name}} <br> {{$data->emp->companies->address}}</p>
                    {{-- <p>Gateway Avenue</p> --}}
                </div><br>
                <div class="row">
                    <div class="column">
                        <table class="center">
                            <tr>
                                <td>Employee name</td>
                                <td>: {{$data->emp->first_name.' '.$data->emp->last_name}}</td>
                                <td style="margin-left: 5%">Date of Joining</td>
                                <td>: {{ date('d/m/Y', strtotime($data->emp->joining_date))}}</td>
                            </tr>
                            <tr>
                                <td >Designation</td>
                                <td >: {{$data->emp->designation}}</td>
                                <td>Salary Period</td>
                                <td style="margin-left: 5%">: 01/{{$month}}/{{$data->year}} - {{$last}}/{{$month}}/{{$data->year}}</td>
                            </tr>
                            <tr>
                                <td>Department</td>
                                <td>: {{$data->emp->dpt->name}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div>
                    <div class="row">
                        <div class="column" style="margin-right:20px">
                            <table class="center w-100" style="border: 1px solid black;">
                                <thead>
                                <tr>
                                    <th scope="col">Salary Component</th>
                                    <th scope="col">Amount</th>
                                    
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($check = $data->components($data->employee_id, $data->month, $data->year) as $key => $item)
                                        
                                        <tr style="height: 35px;">
                                            <td style="border-right: 1px solid black;width:67%">{{$item->salaryComponent->name}}</td>
                                            <td style="border-right: 1px solid black;width:33%;text-align: end;">{{$item->amount}}</td>
                                        </tr>

                                        @if ($key == count($check)-1)
                                            <tr style="height: 35px; ">
                                                <td style="border-right: 1px solid black;text-align:right;width:67%"></td>
                                                <td style="border-right: 1px solid black;border-top: 1px solid black;width:33%">
                                                    <div style='float: left; text-align: left'>Total</div>
                                                    <div style='float: right; text-align: right'>{{$check->sum('amount')}}</div>
                                                </td>
                                            </tr>
                                        @endif

                                    @endforeach
                                    
                                    @foreach ($data->deductComponents($data->employee_id, $data->month, $data->year) as $item)
                                        <tr style="height: 35px;">
                                            <td style="border-right: 1px solid black;width:67%">(Deduction) - {{$item->deductComponent->description}}</td>
                                            <td style="border-right: 1px solid black;width:33%;text-align: end;">{{$item->amount}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <table>
                                <tbody>
                                    <tr>
                                        @php
                                            $netpay = $data->payable - $deduction_sum;
                                        @endphp
                                        <td style="width:67%"></td>
                                        <td style="width:33%">
                                            <div style='float: left; text-align: right'> Net Salary</div>
                                            <div style='float: right; text-align: right'>{{$netpay}}</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div style="text-align:right;width: 89.5%;">
                    <p>{{ucwords((new NumberFormatter('en_IN', NumberFormatter::SPELLOUT))->format($netpay))}} {{$data->emp->currency}}</p>
                </div>
                <div style="text-align:center;">
                    <p>Your salary transfar to  Bank: {{ $data->items2->bank_name}}, Account: {{ $data->items2->account_number}}, IBAN: {{ $data->items2->ibal_number}}, Date: {{$data->payInfo($data->id, $data->month, $data->year)->format('d/m/Y')}}</p>
                </div>
                {{-- <div style="text-align: center;">This is System generated payslip</div> --}}
            </div>
            <div style="position: fixed; bottom:70px; left:0px; width:100%;">
                <p class="s12" style="margin-left: .8in;text-indent: 0pt;text-align:left">*** System generated document.
                    Signature not required.</p>
            </div>
            <div style="position: fixed; bottom:0px; left:0px; width:100%; text-align:center; margin-top:50px">
                {{-- <p style="width:100%;text-indent: 0pt;text-align: center;"><br /> <hr></p> --}}
                <p style="border-top: 1px solid rgb(12, 11, 11);padding-top: 4pt;t;padding: 10px 30px;line-height: 12pt;text-align: justify;">
                    <a
                        href="mailto:info@zinith-audit.com" class="a" target="_blank">RAS AL KHAIMAH, U.A.E.
                    </a><a href="mailto:info@zinith-audit.com" target="_blank"> <a  style="corlor:rgb(16, 128, 255)" href="info@zisprink.com">: info@beps.com</a> <a href="">www.beps.com</a>
                </p>
                {{-- <p style="border-top: 1px solid rgb(12, 11, 11);padding-top: 4pt;t;padding: 10px 77px;line-height: 12pt;text-align: center;"><a
                        href="mailto:info@zinith-audit.com" class="a" target="_blank">ziSprink IT Solutions FZ LLC B04-421; Business Center 03; RAKEZ Business Zone-FZ, RAK, UAE
                        Phone: +971 7 2075887 Mobile: +971 54 2968058 Email
                    </a><a href="mailto:info@zinith-audit.com" target="_blank"> <a  style="corlor:rgb(16, 128, 255)" href="info@zisprink.com">: info@zisprink.com</a> www.zisprink.com
                </p> --}}
            </div>
        @endforeach
        <div class="img">
            <img style="position: fixed; top:300px; left:170px;opacity:0.2"width="500" height="350"src="{{asset('/')}}zisprink.jpg" class="img-fluid">
        </div>
</body>
                    

</html>

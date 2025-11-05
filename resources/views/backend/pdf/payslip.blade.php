@php

    use Carbon\Carbon;
                    use App\HolidayRecode;
@endphp
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

    <body style="" onload="window.print();">
            @foreach($datas as $data)

                    @php

                    $employee=$data->emp;
                    $total_salary = App\Models\Payroll\GradeWiseSalaryComponent::where('grade_id',$employee->grade)->get();
                    $basic_salary = $total_salary->where('salary_component_id',1);
                    $date = Carbon::parse($data->month)->format('Y-m');

                    $this_month_days = Carbon::now()->month($date)->daysInMonth;
                    $per_hours_salary = ($basic_salary->sum('value')/$this_month_days)/10;
                    $over_time = App\EmployeeOvertime::where('employee_id', $employee->id)->whereMonth('date', Carbon::parse($data->month)->format('m'))->whereYear('date', Carbon::parse($data->month)->format('Y'))->get()->sum('hours');
                    $absent_count = App\EmployeeAttendance::where('employee_id', $employee->id)->whereMonth('date', Carbon::parse($data->month)->format('m'))->whereYear('date', Carbon::parse($data->month)->format('Y'))->where('status',0)->get();
                    $deduction_processes=App\Models\Payroll\DeductionProcess::where('employee_id', $employee->id)->where('month',$data->month)->where('year', Carbon::parse($data->month)->format('Y'))->get();
                    @endphp
                <section id="basic-vertical-layouts" style="page-break-before: always; margin-top:100px">
                    <div class="row match-height">
                        <div class="col-md-12 col-12">
                            <div class="cardStyleChange">
                                <div class="card-body">
                                    <div style="text-align: center;">
                                        <h2>LABOUR CARD</h2>
                                    </div>
                                    <table class="table table-sm table-borderless">

                                        <tr>
                                            <td >Name: {{$data->emp->full_name}}</td>
                                            {{-- <td class="text-left" style="border-bottom: 2px dotted;" colspan="2">{{$data->emp->full_name}}</td> --}}
                                            <td></td>
                                            <td>Profession: {{$data->emp->dvision->name}}</td>
                                            {{-- <td class="text-left" style="border-bottom: 2px dotted;" colspan="2">{{$data->emp->dvision->name}}</td> --}}
                                        </tr>
                                        <tr>
                                            <td>Month: {{$data->month}}</td>
                                            {{-- <td class="text-left" style="border-bottom: 2px dotted;" colspan="2">{{$data->month}}</td> --}}
                                            <td></td>
                                            <td>Year: {{$data->year}}</td>
                                            {{-- <td class="text-left" style="border-bottom: 2px dotted;" colspan="2">{{$data->year}}</td> --}}
                                        </tr>
                                        <tr>
                                            <td>Salary Per Month: {{$total_salary->sum('value')}}</td>
                                            {{-- <td class="text-left" style="border-bottom: 2px dotted;">{{$total_salary->sum('value')}}</td> --}}
                                            <td>Per Day: {{number_format($basic_salary->sum('value')/$this_month_days,2)}}</td>
                                            {{-- <td class="text-left" style="border-bottom: 2px dotted;">{{number_format($basic_salary->sum('value')/$this_month_days,2)}}</td> --}}
                                            <td>Per Hours: {{number_format($per_hours_salary,2)}}</td>
                                            {{-- <td class="text-left" style="border-bottom: 2px dotted;">{{number_format($per_hours_salary,2)}}</td> --}}
                                        </tr>
                                    </table>

                                    <div class="daily-attendance-report">
                                        @php
                                         $datee=$date.'-01';
                                            $today = Carbon::createFromFormat('Y-m-d', $datee);
                                            $dates = [];
                                            $holyArray=null;

                                            $holydays=HolidayRecode::whereYear('date',date('Y', strtotime($date.'-1')))->whereMonth('date',date('m', strtotime($date.'-1')))->get();
                                            foreach($holydays as $holy)
                                            {
                                                $holyArray=$holyArray.date('d',strtotime($holy->date)).',';
                                            }
                                            $weekend=null;

                                            $array=explode(',', $holyArray);
                                            for ($i = 1; $i < $today->daysInMonth + 1; ++$i) {
                                                if (!in_array($i, $array)) {
                                                    $dates[] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i);
                                                }
                                            }
                                            $extra_td = 32 - count($dates);

                                        @endphp
                                        <table class="table table-sm table-bordered">
                                            <tr style="height: 30px;">
                                                <td style="min-width: 150px !important;" class="text-center"></td>
                                                @foreach ($dates as $key => $item)
                                                    @if ($key < 16)
                                                        <td class="separate-color text-center" style="min-width: 50px !important;">{{ date('d', strtotime($item)) }}</td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Morning</td>
                                                @foreach ($dates as $key => $item)
                                                    @if ($key < 16)
                                                        @if ($a = App\EmployeeAttendance::where('date', date('Y-m-d', strtotime($item)))->where('employee_id', $employee->id)->first())
                                                            @if ($a->morning==1)
                                                                <td class="text-center">P</td>
                                                            @else
                                                                <td class="text-center">A</td>
                                                            @endif
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Afternoon</td>
                                                @foreach ($dates as $key => $item)
                                                    @if ($key < 16)
                                                        @if ($a = App\EmployeeAttendance::where('date', date('Y-m-d', strtotime($item)))->where('employee_id', $employee->id)->first())
                                                            @if ($a->afternoon==1)
                                                                <td class="text-center">P</td>
                                                            @else
                                                                <td class="text-center">A</td>
                                                            @endif
                                                        @else
                                                            <td ></td>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td>Overtime</td>
                                                @foreach ($dates as $key => $item)
                                                    @if ($key < 16)
                                                        @if ($a = App\EmployeeOvertime::where('date', date('Y-m-d', strtotime($item)))->where('employee_id', $employee->id)->first())
                                                            <td class="text-right">{{$a->hours}}</td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </tr>
                                            {{-- 17 to up --}}
                                            <tr style="height: 30px;">
                                                <td style="min-width: 150px !important;" class="text-center"></td>
                                                @foreach ($dates as $key => $item)
                                                    @if ($key >= 16)
                                                        <td class="separate-color text-center">{{ date('d', strtotime($item)) }}</td>
                                                    @endif
                                                @endforeach
                                                @for ($i = 0; $i < $extra_td; $i++)
                                                    <td></td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                <td>Morning</td>
                                                @foreach ($dates as $key => $item)
                                                    @if ($key >= 16)
                                                        @if ($a = App\EmployeeAttendance::where('date', date('Y-m-d', strtotime($item)))->where('employee_id', $employee->id)->first())
                                                            @if ($a->morning==1)
                                                                <td class="text-center">P</td>
                                                            @else
                                                                <td class="text-center">A</td>
                                                            @endif
                                                        @else
                                                            <td></td>
                                                        @endif
                                                    @endif
                                                @endforeach
                                                @for ($i = 0; $i < $extra_td; $i++)
                                                <td></td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                <td>Afternoon</td>
                                                @foreach ($dates as $key => $item)
                                                    @if ($key >= 16)
                                                        @if ($a = App\EmployeeAttendance::where('date', date('Y-m-d', strtotime($item)))->where('employee_id', $employee->id)->first())
                                                            @if ($a->afternoon==1)
                                                                <td class="text-center">P</td>
                                                            @else
                                                                <td class="text-center">A</td>
                                                            @endif
                                                        @else
                                                            <td ></td>
                                                        @endif
                                                    @endif
                                                @endforeach
                                                @for ($i = 0; $i < $extra_td; $i++)
                                                <td></td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                <td>Overtime</td>
                                                @foreach ($dates as $key => $item)
                                                    @if ($key >= 16)
                                                        @if ($a = App\EmployeeOvertime::where('date', date('Y-m-d', strtotime($item)))->where('employee_id', $employee->id)->first())
                                                            <td class="text-right">{{$a->hours}}</td>
                                                        @else
                                                            <td ></td>
                                                        @endif
                                                    @endif
                                                @endforeach
                                                @for ($i = 0; $i < $extra_td; $i++)
                                                <td></td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                <td rowspan="7" colspan="4" class="text-center">
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <hr>
                                                    Authorize Signature
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <hr>
                                                    Receiver's Signature
                                                </td>
                                                <td colspan="9">Details</td>
                                                <td colspan="4">Amount</td>

                                            </tr>
                                            <tr>
                                                <td colspan="9">Basic Salary</td>
                                                <td colspan="4">{{$basic_salary->sum('value')}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="9">Overtime</td>
                                                <td colspan="4">{{number_format($over_time*$per_hours_salary,2)}}</td>
                                            </tr>
                                            <tr>
                                                {{-- <td rowspan="3" colspan="4">Authorize Signatory</td> --}}
                                                <td colspan="9">Total</td>
                                                <td colspan="4">{{number_format($total_salary->sum('value')+($over_time*$per_hours_salary),2)}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="9">Less Absent</td>
                                                <td colspan="4">{{number_format(($basic_salary->sum('value')/$this_month_days)*count($absent_count),2)}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="9">Less Advance Paid</td>
                                                <td colspan="4">{{number_format($deduction_processes->where('deduction_type', 1)->sum('amount'),2)}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="9">Balance Amount Payable</td>
                                                <td colspan="4">{{number_format(($total_salary->sum('value')+($over_time*$per_hours_salary))-($basic_salary->sum('value')/$this_month_days)*count($absent_count)-$deduction_processes->sum('amount'),2)}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endforeach
    </body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap-grid.min.css" />
    <title>Management Report {{$months->month}}, {{$months->year}}</title>
    <style>
        /* h3{
            text-align: center;
        } */
        table{
          border-collapse: collapse;
          width: 100%;
          border: 1px solid black;
        }
        tr,th {
            border: 1px solid black;
        }
        .bod-none{
            border-bottom: 1px solid rgba(0, 0, 0, 0);
        }
		/* @page { size: landscape; } */
    </style>
</head>
@php
// $company_name= \App\Setting::where('config_name', 'company_name')->first();
// $company_address= \App\Setting::where('config_name', 'company_address')->first();
// $company_tele= \App\Setting::where('config_name', 'company_tele')->first();
// $company_email= \App\Setting::where('config_name', 'company_email')->first();
// $trn_no= \App\Setting::where('config_name', 'trn_no')->first();
// $company_logo= \App\Setting::where('config_name', 'company_logo')->first();
// $company_trn= \App\Setting::where('config_name', 'trn_no')->first();
$i = 1;
$l = 0;


use Carbon\Carbon;
@endphp
<body style="margin: 40px" onload="window.print();">

    <h2 style="text-align: center; line-height: 0; margin-top:100px">SALARY DETAILS</h2>
    <p style="text-align: center; line-height: 0">Salary Period :{{$months->month}}, {{$months->year}}</p>
    <p style="text-align: center; line-height: 0">Processing Date :{{Carbon::now()->format("d/m/Y")}}</p>
    <br><br>
    <table class="table table-small">
        <tr>
            <th>Sl No</th>
            <th>EMP ID</th>
            <th>Name of Employee</th>
            <th>Component</th>
            <th>Amount</th>
            <th colspan="2">Net Salary</th>
        </tr>
        @foreach($datas as $data)
            @php
                $earning_sum = 0;
                $deduction_sum = 0;
                $j = 0;
                $k = 0;
                $deduct = $data->deductComponents($data->employee_id, $months->month, $months->year);
            @endphp
            @if ($l+$i > 26)
                <tr style="page-break-after: always;">
                </tr>
                <h2 style="text-align: center; line-height: 0">SALARY PROCESS REPORT</h2>
                <p style="text-align: center; line-height: 0">{{$months->month}}, {{$months->year}}</p>
                <p style="text-align: center; line-height: 0">Report Date :{{Carbon::now()->format("Y-m-d")}}</p>
                <br><br>
                <table class="table table-small">
                    <tr>
                        <th>Sl No</th>
                        <th>Name of Employee</th>
                        <th>Component</th>
                        <th>Amount</th>
                        <th colspan="2">Total</th>
                    </tr>

                    @php
                        $l = 0;
                    @endphp
            @endif

            @foreach($comps = $data->components($data->employee_id, $months->month, $months->year) as $key => $comp)
                <tr >
                    @if ($key == 0)
                        <th rowspan="{{count($comps) + count($deduct)}}">{{$i}}</th>
                        <th rowspan="{{count($comps) + count($deduct)}}">{{$comp->items->emp_id}}</th>
                        <th rowspan="{{count($comps) + count($deduct)}}">{{$comp->items->first_name.' '.$comp->items->last_name}}</th>
                    @endif
                    @if ($key != count($comps)-1)
                        <td class="bod-none" style="border-right: 1px solid black; ">{{$comp->salaryComponent->name}}</td>
                        <td class="bod-none" style="border-right: 1px solid black;text-align: end;">{{$comp->amount}}</td>
                    @else
                        <td class="bod-none" style="border-right: 1px solid black; border-bottom: 1px solid black">{{$comp->salaryComponent->name}}</td>
                        <td class="bod-none" style="border-right: 1px solid black;text-align: end; border-bottom: 1px solid black">{{$comp->amount}}</td>
                    @endif
                    @if ($key == 0)
                        <th rowspan="{{count($comps) + count($deduct)}}">{{$comps->sum('amount') - $deduct->sum('amount')}}</th>
                    @endif
                </tr>
            @endforeach

            @foreach($deduct as $key => $comp)
                <tr >
                    @if ($key != count($deduct)-1)
                        <td class="bod-none" style="border-right: 1px solid black; ">(-) {{$comp->deductComponent->description}}</td>
                        <td class="bod-none" style="border-right: 1px solid black;text-align: end;">{{$comp->amount}}</td>
                    @else
                        <td class="bod-none" style="border-right: 1px solid black; border-bottom: 1px solid black">(-) {{$comp->deductComponent->description}}</td>
                        <td class="bod-none" style="border-right: 1px solid black;text-align: end; border-bottom: 1px solid black">{{$comp->amount}}</td>
                    @endif
                </tr>
            @endforeach

            @php
                // $l++;
                $i++;
            @endphp
        @endforeach
    </table>
    <p style="margin-top: 90px; border-top:1px solid black;width:100px; text-align:center">Signature</p>
</body>

</html>

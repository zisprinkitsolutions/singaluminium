@extends('layouts.print_app')
@section('content')
<style>
    tr td{
        font-size: 10 px !important;
        padding: 0 !important;
        border: 1px solid black !important;
        color: black;

    }

    .bg-light{
        background-color: #6e72752e !important;
    }
    .yellow-color{
        background: #ffff00 !important;
    }
    .black-color{
        height: 20px;
        background-color: #151414 !important;
    }
    @media print{
        tr td{
            font-size: 10 px !important;
            padding: 0 !important;
            border: 1px solid black !important;
            color: black;

        }

        .bg-light{
            background-color: #6e72752e !important;
            -webkit-print-color-adjust: exact;
        }
        .yellow-color{
            background-color: #ffff00 !important;
            -webkit-print-color-adjust: exact;
        }
        .black-color:nth-child(1){
            background: #1e1d1d !important;
            -webkit-print-color-adjust: exact;
        }
        .date_period:nth-child(1){
            background:#f1640d7b !important;
            -webkit-print-color-adjust: exact;
        }
        @page {
            size: A4 landscape;
            width: 276mm;
        }
    }
</style>
@php
    use Carbon\Carbon;
    use App\Models\Payroll\HolidayRecode;
    use  App\Models\Payroll\EmployeeAttendance;


@endphp

<div class="daily-attendance-report">

    @php
        // $today = Carbon::now()->startOfMonth();
        $today = Carbon::createFromFormat('Y-m', $date);;
        $dates = [];
        $holyArray=null;

        $holydays=HolidayRecode::whereYear('date',date('Y', strtotime($date.'-1')))->whereMonth('date',date('m', strtotime($date.'-1')))->get();
        foreach($holydays as $holy)
        {
            $holyArray=$holyArray.date('d',strtotime($holy->date)).',';
        }
        $weekend=null;
        for ($i = 1; $i < $today->daysInMonth + 1; ++$i) {
            if (!in_array($i, [$holyArray])) {
                if(Carbon::createFromDate($today->year, $today->month, $i)->isSaturday() || Carbon::createFromDate($today->year, $today->month, $i)->isSunday())
                {
                    $holyArray=$holyArray.$i.',';

                }
            }
        }

            $array=explode(',', $holyArray);
            for ($i = 1; $i < $today->daysInMonth + 1; ++$i) {
                if (!in_array($i, $array)) {
                    $dates[] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i);
                }
            }
    @endphp
    <table class="table table-sm  table-bordered">
        <tr >
            <td colspan="{{2+count($dates)/2}}" style="background-color: #A9D18E;"><h1 class="text-center" style="margin-bottom:0; vertical-align:middle;font-size:21px"> Employee Attendance Sheet</h1></td>
            <td colspan="{{2+count($dates)/2}}">
              <h1  style="margin-bottom:0; vertical-align:middle;font-size:20px;text-align:center">{{date('d-F-Y', strtotime($today->firstOfMonth()))}} From {{date('d-F-Y', strtotime($today->lastOfMonth()))}} </h1>
            </td>
        </tr>
        <tr style="height: 70px;">
            <td style="background:#F8CBAD;">Rank</td>
            <td style="min-width: 150px !important;background:#F8CBAD;" class="text-center">Name</td>
            @foreach ($dates as $item)
                <td class="separate-color text-center">{{ date('D', strtotime($item)) }} <br>{{ date('d', strtotime($item)) }}</td>
            @endforeach
        </tr>
        @foreach ($employees as  $key => $employee)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$employee->first_name}} {{$employee->middle_name}} {{$employee->last_name}}</td>
                @foreach ($dates as $item)
                    @if ($a = EmployeeAttendance::where('date', date('Y-m-d', strtotime($item)))->where('employee_id', $employee->id)->first())
                        @if ($a->status==1)
                            <td class="text-center bg-success">P</td>
                        @else
                            <td  class="text-center bg-danger">A</td>
                        @endif
                    @else
                        <td style="background-color: #efefef"></td>
                    @endif
                @endforeach
            </tr>
        @endforeach
        <tr>
            <td colspan="2" class="text-center text-danger" >Attendace</td>
            @foreach ($dates as $item)
            @php
                    $present=EmployeeAttendance::where('date', date('Y-m-d', strtotime($item)))
                                    ->leftjoin('employees','employees.id','=','employee_attendances.employee_id')
                                    ->where('employees.division','!=', 6)
                                    ->where('employee_attendances.status',1)
                                    ->select('employee_attendances.*')
                                    ->get();
            @endphp
                <td class=" text-center" style="background-color: #C5E0B4">
                    {{count( $present)}}
                </td>
            @endforeach
        </tr>
        <tr>
            <td colspan="2" class="text-center text-danger">Attendace %</td>
            @foreach ($dates as $item)
                <td class="bg-light text-center">
                    @php
                        $f = 0;
                        $entries=EmployeeAttendance::where('date', date('Y-m-d', strtotime($item)))
                                    ->leftjoin('employees','employees.id','=','employee_attendances.employee_id')
                                    ->where('employees.division', '!=', 6)
                                    ->select('employee_attendances.*')
                                    ->get();
                        $present=EmployeeAttendance::where('date', date('Y-m-d', strtotime($item)))
                                    ->leftjoin('employees','employees.id','=','employee_attendances.employee_id')
                                    ->where('employees.division', '!=', 6)
                                    ->where('employee_attendances.status',1)
                                    ->select('employee_attendances.*')
                                    ->get();

                        $t =$entries->count();
                        $p =$present->count();
                        if($t>0){
                            $f = $p/$t;
                        }
                    @endphp
                    @if ($t>0)
                        {{number_format(($f)*100,1)}} %
                    @else
                        0%
                    @endif
                </td>
            @endforeach
        </tr>
    </table>
</div>
<div class="print-section-header" style="position: fixed; bottom: 0;" style="display: none">
    @include('layouts.backend.partial.modal-footer-info')
</div>
@endsection

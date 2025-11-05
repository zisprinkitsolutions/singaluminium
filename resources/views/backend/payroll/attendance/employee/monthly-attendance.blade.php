@extends('layouts.backend.app')
@php
use Carbon\Carbon;
use App\Models\Payroll\HolidayRecode;
use App\Models\Payroll\EmployeeAttendance;
@endphp
@push('css')
<!-- summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@endpush

@section('content')
@include('layouts.backend.partial.style')
<style>
    .table td {
        vertical-align: middle;
        border-bottom: 1px solid #DFE3E7;
        border-top: none;
        font-size: 12px;
    }

    .table {
        width: 98%;
        margin-bottom: 1rem;
        color: #727E8C;
        margin: 10px;
    }
</style>
<style>
    .table .thead-light th {
        color: #F2F4F4;
        background-color: #34465b;
        border-color: #DFE3E7;
    }

    tr:nth-child(even) {
        background-color: #c8d6e357;
    }

    .mSearchingBotton:hover {
        background: #686a6c !important;
        color: #ffd
    }

    .mSearchingBotton {
        background: #71777d !important;
        color: #ffd !important;
    }

    .btn:hover {
        color: #727E8C;
        text-decoration: none;
    }

    .inputFieldHeight {
        height: 32px;
    }

    .office_in_out {
        background: #de2014;
        padding: 3px;
        border-radius: 10%;
        color: #fff;
        font-size: 10px;
    }

    .office_in_out_active {
        background: #009013;
        padding: 3px;
        border-radius: 10%;
        color: #fff;
        font-size: 10px;
    }
    .office_in_out_active_absen {
        background: #901600;
        padding: 3px;
        border-radius: 10%;
        color: #fff;
        font-size: 10px;
    }
    .emp_this_month_atten {
        cursor: pointer;
    }

    .select2-container--default .select2-selection--single {
        min-height: 32px !important;
    }
</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('backend.payroll.tab-sub-tab._basic_info_header', ['activeMenu' => 'attendance'])
            <div class="tab-content bg-white">
                <div id="studentProfileList" class="tab-pane active pt-1" style="max-width:100%">
                    @include('backend.payroll.tab-sub-tab.attendance_submenu',['activeMenu' => 'monthly'])

                    <div class="cardStyleChange">
                        <div class="card-body  pb-0" style="margin-top: 13px; margin-left: 10px;">
                            @if(session('msg'))
                            <div class="col-md-12">
                                <div class="alert alert-warning ">
                                    {!! session('msg') !!}
                                </div>
                            </div>
                            @endif
                            <section id="basic-vertical-layouts">
                                <div class="row pr-1">
                                    <div class="col-md-12">
                                        <div class="row" style="margin-left:10px;">
                                            <div class="col-md-4">
                                                <form action="" method="GET" class="d-flex row">
                                                    <div class="row form-group col-8 col-lg-11" style="padding-left:7px;">
                                                        <input type="text" class="inputFieldHeight form-control "
                                                            name="search" value="{{$search}}"
                                                            placeholder="SEARCH BY NAME" required autocomplete="off">
                                                    </div>
                                                    <div class="col-4 col-lg-1 mr-0">
                                                        <button type="submit" style="padding: 4px 10px;"
                                                            class="btn mSearchingBotton mb-2 formButton" title="Search">
                                                            <div class="d-flex">
                                                                <div class="formSaveIcon">
                                                                    <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                        width="25">
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="col-md-7 ">
                                                <form action="" method="GET" class="d-flex row">
                                                    {{-- <div class="col-md-4">
                                                        <select name="job_type" id="job_type"
                                                            class="form-control common-select2 job_type"
                                                            style="width: 100% !important">
                                                            <option value="">SELECT JOB TYPE</option>
                                                            @foreach ($job_types as $job_type_)
                                                            <option value="{{$job_type_->id}}" {{$job_type==$job_type_->
                                                                id}}>{{$job_type_->type}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div> --}}
                                                    <div class="row form-group col-8 col-lg-4" style="padding-left:20px;">
                                                        <input type="text"
                                                            class="inputFieldHeight form-control attendance_date date-value"
                                                            name="date" value="{{ date('d/m/Y', strtotime($date)) }}"
                                                            placeholder="DD/MM/YYYY" required autocomplete="off">

                                                    </div>
                                                    <div class="col-4 col-lg-2">
                                                        <button type="submit" style="padding: 4px 10px;"
                                                            class="btn mSearchingBotton mb-2 formButton" title="Search">
                                                            <div class="d-flex">
                                                                <div class="formSaveIcon">
                                                                    <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                        width="25">
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="table-contain">
                                    <table class="myTable" style="width: 100%; font-size:10px">
                                        <thead>
                                            <tr>
                                                <th>Employee Name</th>
                                                <th colspan="31" style="font-size: 10px; text-align:center;text-transform:uppercase">
                                                    EMPLOYEE ATTENDANCE of {{ date('M-Y', strtotime($request_date . '-01')) }}
                                                </th>
                                                <th class="th-td-color-2" colspan="7">Summary</th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                @foreach ($daysArray as $day)
                                                    <th class="th-td-color-2 text-center" data-date="{{ $day['date'] }}" title="{{ $day['date'] }}">
                                                        {{ $day['day_number'] }}
                                                    </th>
                                                @endforeach
                                                @for ($i = 0; $i < 31 - count($daysArray); $i++)
                                                    <th></th>
                                                @endfor
                                                <th class="th-td-color-2 text-center" title="Present">PR</th>
                                                <th class="th-td-color-2 text-center" title="Absent">AB</th>
                                                <th class="th-td-color-2 text-center" title="Late">LA</th>
                                                <th class="th-td-color-2 text-center" title="Weekend">WE</th>
                                                <th class="th-td-color-2 text-center" title="Holiday">HO</th>
                                                <th class="th-td-color-2 text-center" title="Leave">LE</th>
                                                <th class="th-td-color-2 text-center">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($employee as $data)
                                                <tr class="text-center">
                                                    <td class="emp_show text-dark text-left" data-id="{{ $data->id }}" title="{{ $data->first_name . ' ' . $data->last_name }}" style="cursor:pointer">
                                                        {{ $data->full_name . ' (' . $data->code . ')' }}
                                                    </td>
                                                    @foreach ($daysArray as $day)
                                                        @php
                                                            $present = $data->present($data->id, date('Y-m-d', strtotime($request_date . '-' . $day['day_number'])));
                                                            $in_out = $data->in_out($data->id, date('Y-m-d', strtotime($request_date . '-' . $day['day_number'])));
                                                            $present_status_map = [
                                                                '1' => ['bg-success text-white', 'P'],
                                                                '0' => ['bg-danger text-white', 'A'],
                                                                '2' => ['bg-info text-white', 'LE'],
                                                                '3' => ['bg-secondary text-white', 'W'],
                                                                '4' => ['bg-primary text-white', 'H']
                                                            ];
                                                            $style = $present_status_map[$present['present']][0] ?? 'text-secondary';
                                                            $present_status = $present_status_map[$present['present']][1] ?? 'N';
                                                            if ($present['late'] == '1') {
                                                                $style = 'bg-warning text-white';
                                                                $present_status = 'L';
                                                            }
                                                            $title_show = "Morning In Time: {$in_out['in']}, Morning Out Time: {$in_out['out']}, Evening In Time: {$in_out['evening_in']}, Evening Out Time: {$in_out['evening_out']}, Late Time: {$in_out['late']}, Over Time: {$in_out['over_time']}, Working Hours: {$in_out['working_hours']}";
                                                        @endphp
                                                        <td class="{{ $style }} show-attendance" title="{{ $title_show }}">{{ $present_status }}</td>
                                                    @endforeach
                                                    @for ($i = 0; $i < 31 - count($daysArray); $i++)
                                                        <td></td>
                                                    @endfor
                                                    @php
                                                        $summary = $data->emp_month_present($data->id, date('Y-m-d', strtotime($request_date . '-01')));
                                                    @endphp
                                                    <td class="th-td-color-2 text-white bg-success">{{ $summary['attendance_p'] - $summary['attendance_l'] }}</td>
                                                    <td class="th-td-color-2 text-white bg-danger">{{ $summary['attendance_a'] }}</td>
                                                    <td class="th-td-color-2 text-white bg-warning">{{ $summary['attendance_l'] }}</td>
                                                    <td class="th-td-color-2 text-white bg-secondary">{{ $summary['weekend'] }}</td>
                                                    <td class="th-td-color-2 text-white bg-primary">{{ $summary['holiday'] }}</td>
                                                    <td class="th-td-color-2 text-white bg-info">{{ $summary['leave'] }}</td>
                                                    <td class="th-td-color-2 text-white bg-success">{{ number_format($summary['total_present_percentage'], 2, '.', '') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="40" class="text-center">No employee data found.</td>
                                                </tr>
                                            @endforelse

                                            @if (count($employee) > 0)
                                                <tr class="th-td-color-3 text-center">
                                                    <td class="text-left font-weight-bold">Attendance</td>
                                                    @foreach ($daysArray as $day)
                                                        @php $dp = $data->day_present(date('Y-m-d', strtotime($request_date . '-' . $day['day_number']))); @endphp
                                                        <td>{{ $dp['attendance_p'] }}</td>
                                                    @endforeach
                                                    @for ($i = 0; $i < 31 - count($daysArray); $i++)
                                                        <td></td>
                                                    @endfor
                                                    <td colspan="7"></td>
                                                </tr>
                                                <tr class="th-td-color-3">
                                                    <td>Attendance %</td>
                                                    @foreach ($daysArray as $day)
                                                        @php $dp = $data->day_present(date('Y-m-d', strtotime($request_date . '-' . $day['day_number']))); @endphp
                                                        <td>{{ number_format($dp['total_present_percentage1'], 2, '.', '') }}</td>
                                                    @endforeach
                                                    @for ($i = 0; $i < 31 - count($daysArray); $i++)
                                                        <td></td>
                                                    @endfor
                                                    <td colspan="7"></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-2">
                                    {{$employee->links()}}
                                </div>
                            </section>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
</div>

@endsection
@push('js')
<script>

    $(".attendance_date").datepicker({
        dateFormat: 'dd/mm/yy',
    });

    $(document).ready(function() {

    })

</script>
@endpush

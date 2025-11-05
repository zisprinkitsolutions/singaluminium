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
    table {
        table-layout: fixed;
        width: 100%;
    }
    .select2-container--default .select2-selection--single {
        min-height: 32px !important;
    }
    .select2-selection__rendered{
        font-size:13px !important;
    }
    .select2-container {
        text-align: left;
        width: 240px !important;
        font-size:13px;
    }
</style>

<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('backend.payroll.tab-sub-tab._basic_info_header', ['activeMenu' => 'attendance'])
            <div class="tab-content bg-white">
                <div id="studentProfileList" class="tab-pane active pt-1" style="max-width:100%">
                    @include('backend.payroll.tab-sub-tab.attendance_submenu',['activeMenu' => 'project'])

                    <div class="cardStyleChange">
                        <div class="card-body  pb-0" style="margin-top: 13px;">

                            <section id="basic-vertical-layouts">
                                <form class="d-flex" style="margin-left:10px;">
                                    <input type="text" class="form-control inputFieldHeight" name="project_name" placeholder="Search By Project name"
                                        style="width:240px; margin-right:15px;" value="{{$selected_project}}">

                                    <select name="employee_id" id="" class="form-control common-select2" style="margin-right:15px; width:240px;">
                                        <option value=""> Select. Employee </option>
                                        @foreach ($employees as $employee)
                                            <option value="{{$employee->id}}" {{$selected_employee == $employee->id ? 'selected' : ''}}> {{$employee->full_name}} </option>
                                        @endforeach
                                    </select>

                                    <select name="month" class="form-control inputFieldHeight"  style="margin:0 15px; width:100px;">
                                        <option value=""> Select. Month </option>
                                        @for($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                            </option>
                                        @endfor
                                    </select>

                                    <select name="year" class="form-control inputFieldHeight" style="width:100px; margin-right:10px;">
                                        <option value=""> Select. Year </option>
                                        @for($y = date('Y'); $y >= 2020; $y--)
                                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>

                                    <button class="btn btn-primary inputFieldHeight" style="padding:3px 10px !inportant;" type="submit"> Search </button>
                                </form>

                                <div class="table-responsive mt-1">
                                    <table class="table table-bordered table-sm text-center">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width:180px; text-align:left;"> Project Name </th>
                                                <th> Employees </th>
                                                <th> Working Hours </th>
                                                <th> Overtime </th>
                                                <th> Late Time </th>
                                                <th> Absent </th>
                                                <th> Current Salary </th>
                                                <th> Overtime Amount </th>
                                                <th> Late Penalty </th>
                                                <th> Absent Penalty </th>
                                                <th> Total Cost </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data as $key => $project)
                                                <tr class="details" data-url="{{route('project.working.report.details',$key)}}">
                                                    <td class="text-left" data-title="{{$project['project_name'] ?? 'N/A'}}">
                                                       {{ \Illuminate\Support\Str::limit($project['project_name'] ?? 'N/A', $limit = 30, $end = '...')}}</td>
                                                    <td>{{ $project['total_employees'] ?? 0 }}</td>
                                                    <td>{{ secondsTotime($project['total_working_hours']) ?? '00:00:00' }}</td>
                                                    <td>{{ secondsTotime($project['total_overtime']) ?? '00:00:00' }}</td>
                                                    <td>{{ secondsTotime($project['total_late_time']) ?? '00:00:00' }}</td>
                                                    <td>{{ $project['total_absen'] ?? 0 }}</td>
                                                    <td>{{number_format($project['basic_salary_current_day'],2)}}</td>
                                                    <td>{{ number_format($project['overtime_amount'] ?? 0, 2) }}</td>
                                                    <td>{{ number_format($project['late_amount'] ?? 0, 2) }}</td>
                                                    <td>{{ number_format($project['total_absen_penalty'] ?? 0, 2) }}</td>
                                                    <td><strong>{{ number_format($project['total_cost'] ?? 0, 2) }}</strong></td>
                                                </tr>
                                            @empty

                                            <tr>
                                                <td colspan="10">No data found for the selected month.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" style="width: 100%;" id="employee-modal-show" tabindex="-1" role="dialog"
aria-labelledby="employee-modal-show" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="padding-right: 0px !important;">
    <div class="modal-content ">
        <div class="" id="modal-details">
        </div>
    </div>
</div>
{{-- ****************  modal end ************************ --}}
</div>

@endsection
@push('js')
<script>
    $(document).on('click', '.details', function(){
        var url = $(this).data('url');
        var employee_id = '{{$selected_employee}}';
        var month = '{{$month}}';
        var year = '{{$year}}';
        $.ajax({
            url:url,
            type:'get',
            data:{
                employee_id:employee_id,
                month:month,
                year:year,
            },
            success:function(res){
                $('#modal-details').html(res)
                $('#employee-modal-show').modal('show');
            }
        });
    });

</script>
@endpush

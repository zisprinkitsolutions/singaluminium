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
        width: 100% !important;
        max-width: 100% !important;
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
                    @include('backend.payroll.tab-sub-tab.attendance_submenu',['activeMenu' => 'attendance'])

                    <div class="cardStyleChange">
                        <div class="card-body  pb-0" style="margin-top: 13px;">
                            @if(session('msg'))
                            <div class="col-md-12">
                                <div class="alert alert-warning ">
                                    {!! session('msg') !!}
                                </div>
                            </div>
                            @endif
                            <section id="basic-vertical-layouts">
                                <div class="row" style="margin-left:1px;">
                                    <div class="col-12 col-md-4">
                                        <form action="" method="GET" class="d-flex">

                                            <div class="form-group" style="width:100%; max-width:300px;">
                                                <input type="text" class="inputFieldHeight form-control "
                                                    name="search" value="{{$search}}"
                                                    placeholder="SEARCH BY NAME" required autocomplete="off">
                                            </div>

                                            <div class="mr-0">
                                                <button type="submit" style="padding: 4px 10px;"
                                                    class="btn mSearchingBotton mb-2 formButton ml-1" title="Search">
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

                                    <div class="col-12 col-md-4">
                                        <form action="" method="GET" class="d-flex">
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

                                            <div class="form-group" style="padding-left:20px;">
                                                <input type="text"
                                                    class="inputFieldHeight form-control attendance_date date-value"
                                                    name="date" value="{{ date('d/m/Y', strtotime($date)) }}"
                                                    placeholder="DD/MM/YYYY" required autocomplete="off">
                                            </div>

                                            <div class="">
                                                <button type="submit" style="padding: 4px 10px;"
                                                    class="btn mSearchingBotton mb-2 formButton ml-1" title="Search">
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

                                <div class="table-contain table-responsive" style="padding-left: 6px;">
                                    <table class="table table-bordered table-sm employee_change" id="2filter-table">
                                        <thead class="thead-light">

                                            <tr class="text-center" style="font-size: 10px !important;">
                                                {{-- <th rowspan="2" style="padding: 4px;">SI No</th> --}}
                                                <th rowspan="2" style="padding: 4px; width:80px;">Date</th>
                                                {{-- <th rowspan="2" style="padding: 4px;">Day</th> --}}

                                                <th rowspan="2" style="padding: 4px; width: 200px;text-align: left;">Name</th>
                                                <th rowspan="2" style="padding: 4px;width:70px;">Code</th>
                                                <th rowspan="2" style="padding:4px;width:200px;text-align:left;"> Project </th>
                                                <th rowspan="2" style="padding: 4px;width:110px;">Department</th>

                                                {{-- <th rowspan="2" style="padding: 4px;">Absent</th> --}}
                                                <th rowspan="2" style="padding: 4px;width:90px;">Present</th>

                                                <!-- Morning Column with In and Out sub-columns -->
                                                <th colspan="2" style="padding: 4px; width:160px;">Morning</th>

                                                <!-- Evening Column with In and Out sub-columns -->
                                                <th colspan="2" style="padding: 4px; width:160px;">Evening</th>

                                                <th rowspan="2" style="padding: 4px; width:90px;">Total Hour</th>
                                                <th rowspan="2" style="padding: 4px; width:90px;">Overtime</th>
                                                <th rowspan="2" style="padding: 4px; width:90px;">Late</th>
                                                {{-- <th rowspan="2" style="padding: 4px;">Remarks</th> --}}
                                            </tr>

                                            <tr class="text-center" style="height: 40px;">
                                                <!-- Sub-columns for Morning and Evening -->
                                                <th style="width: 10%; padding: 4px; width:80px;">In</th>
                                                <th style="width: 10%; padding: 4px; width:80px;">Out</th>
                                                <th style="width: 10%; padding: 4px; width:80px;">In</th>
                                                <th style="width: 10%; padding: 4px; width:80px;">Out</th>
                                            </tr>

                                        </thead>


                                        <tbody class="t-body">
                                            <!-- Example data -->
                                            {{-- @php
                                            $checkd = [];
                                            @endphp --}}

                                            @foreach ( $employees as $index => $employee)
                                            @php

                                            $emp_id = $employee->id;
                                            $attendanceData = App\Models\Payroll\EmployeeAttendance::attendance($emp_id,$date);
                                            $check = check_holiday_helper( $emp_id ,$date);

                                            $check_time = policy_helper($employee->emp_id,$date);
                                            $working_project = App\Models\Payroll\EmployeeAttendance::where('employee_id', $emp_id)->whereNotNull('project_id')->first();
                                            if($check_time){
                                                $m_ref_in = $check_time->m_ref_in_time;
                                                $e_ref_in = $check_time->e_ref_in_time;

                                                $time_zone = $check_time->time_zone ? $check_time->time_zone : 'Asia/Dubai';
                                                $max_attendance_time = (int)$check_time->maximum_time_for_attendace;

                                                date_default_timezone_set($time_zone);

                                                // Create DateTime objects for m_ref_in and e_ref_in
                                                $m_ref_in_datetime = new DateTime($m_ref_in);
                                                $e_ref_in_datetime = new DateTime($e_ref_in);

                                                // Add max_attendance_time (in minutes) to both m_ref_in and e_ref_in times
                                                $m_ref_in_datetime->add(new DateInterval("PT{$max_attendance_time}M"));
                                                $e_ref_in_datetime->add(new DateInterval("PT{$max_attendance_time}M"));

                                                // Get the current time
                                                $current_time = new DateTime();

                                                // Compare the adjusted times with the current time
                                                $m_is_within_max_attendance_time = $m_ref_in_datetime >= $current_time;
                                                $e_is_within_max_attendance_time = $e_ref_in_datetime >= $current_time;

                                                // Set the result
                                                $m_is_time_over = $m_is_within_max_attendance_time ? true : false;
                                                $e_is_time_over = $e_is_within_max_attendance_time ? true : false;
                                                // $checkd[]=  $check_time;
                                            }
                                            @endphp
                                            @if($check && $check_time)

                                            <tr class="text-center" style="font-size: 10px !important;">
                                                {{-- <td>{{$index+1}}</td> --}}
                                                <td>{{ date('d/m/Y', strtotime($date)) }}</td>
                                                {{-- <td>{{ date('D', strtotime($date)) }}</td> --}}

                                                <td class="emp_this_month_atten text-left" data-emp_id="{{$employee->id}}" data-date="{{$date}}" title="Current Month Attendance of {{$employee->full_name}}">{{$employee->full_name}}</td>
                                                <td>{{$employee->code}}</td>

                                                <td style="width:200px">
                                                    <select name="project_id" id="" class="project_id common-select2 form-control w-100 text-left">
                                                        <option value=""> Select </option>
                                                        @foreach ($projects as $project)
                                                            <option value="{{ $project->id }}"
                                                                @if(isset($attendanceData['project_id']) && $project->id == $attendanceData['project_id']) selected
                                                                @elseif(($working_project && isset($attendanceData['project_id'])) && ($working_project->project_id == $attendanceData['project_id']) )
                                                                    selected
                                                                @endif>
                                                                {{ $project->project_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td>{{$employee->dvision?$employee->dvision->name:''}}</td>
                                                {{-- <td>
                                                    @if($attendanceData['status'] == 1)
                                                    YES
                                                    @elseif($attendanceData['status'] == 0)
                                                    Absen
                                                    @elseif($attendanceData['status'] == 2)
                                                    Leave
                                                    @elseif($attendanceData['status'] == 3)
                                                    Weekend
                                                    @else
                                                    NO
                                                    @endif
                                                </td> --}}

                                                <td> {{$attendanceData['status'] == 1 ? 'YES':'NO'}} </td>


                                                <!-- morning attendance botton -->
                                                <td>
                                                    @if($attendanceData['in_time'] != '' )

                                                    <div class="form-check form-check-inline">
                                                        <span style="margin-left: 10px"
                                                            class="form-check-input   @if($attendanceData['status'] == 1 || $attendanceData['status'] == 3)  office_in_out_active @else office_in_out_active_absen  @endif office_in_out   ">
                                                            @if($attendanceData['status'] == 1)
                                                               {{ $attendanceData['in_time'] ?? '00:00:00' }}
                                                            @elseif($attendanceData['status'] == 0)
                                                            Absen
                                                            @elseif($attendanceData['status'] == 2)
                                                            Leave
                                                            @elseif($attendanceData['status'] == 3)
                                                            Weekend
                                                            @else
                                                            NO
                                                            @endif

                                                        </span>
                                                    </div>
                                                    @elseif($m_is_time_over)

                                                    <div class="form-check form-check-inline" style="cursor: pointer">
                                                        <span data-type="morning_in" data-emp_id="{{ $employee->id }}" title="Task Attendance button"
                                                            data-in_time="{{ $employee->job_type_info ? $employee->job_type_info->in_time : '' }}"
                                                            style="margin-left: 10px" id="in_time_{{ $employee->id }}"
                                                            class="form-check-input office_in_out action-btn current-time">
                                                            {{ $attendanceData['in_time'] ?? '00:00:00' }}
                                                        </span>
                                                    </div>
                                                    @else
                                                        Late
                                                    @endif
                                                </td>

                                                <!-- morning leave attendance button -->
                                                <td>
                                                    @if($attendanceData['out_time'] != "" )
                                                    <div class="form-check form-check-inline">
                                                        <span style="margin-left: 10px"
                                                            class="form-check-input @if($attendanceData['status'] == 1 || $attendanceData['status'] == 3)  office_in_out_active @else office_in_out_active_absen  @endif ">
                                                            @if($attendanceData['status'] == 1)
                                                                {{ $attendanceData['out_time'] ?? '00:00:00' }}
                                                            @elseif($attendanceData['status'] == 0)
                                                            Absen
                                                            @elseif($attendanceData['status'] == 2)
                                                            Leave
                                                            @elseif($attendanceData['status'] == 3)
                                                            Weekend
                                                            @else
                                                            NO
                                                            @endif
                                                        </span>
                                                    </div>
                                                    @elseif(($m_is_time_over || $attendanceData['status'] == 1) and $attendanceData['in_time'])

                                                    <div class="form-check form-check-inline" style="cursor: pointer">
                                                        <span data-type="morning_out" data-emp_id="{{ $employee->id }}" title="Morning Leave Button"
                                                            data-out_time="{{ $employee->job_type_info ? $employee->job_type_info->out_time : '' }}"
                                                            class="form-check-input office_in_out  action-btn current-time" id="out_time_{{ $employee->id }}">
                                                                 {{ $attendanceData['out_time'] ?? '00:00:00' }}
                                                        </span>
                                                    </div>

                                                    @elseif(!$attendanceData['in_time'])
                                                        Inactive

                                                    @endif
                                                </td>

                                                  <!-- evening  attendance in botton -->
                                                  <td>
                                                    @if($attendanceData['evening_in'] != "" )

                                                    <div class="form-check form-check-inline">
                                                        <span style="margin-left: 10px"
                                                            title="{{$attendanceData['status'] == 1 ? 'Attendance Complete' :'Attendance Status'}}"
                                                            class="form-check-input   @if($attendanceData['status'] == 1 || $attendanceData['status'] == 3)  office_in_out_active @else office_in_out_active_absen  @endif   office_in_out   ">
                                                            @if($attendanceData['status'] == 1)
                                                                {{ $attendanceData['evening_in'] ?? '00:00:00' }}

                                                            @elseif($attendanceData['status'] == 0)
                                                            Absen
                                                            @elseif($attendanceData['status'] == 2)
                                                            Leave
                                                            @elseif($attendanceData['status'] == 3)
                                                            Weekend
                                                            @else
                                                            NO
                                                            @endif
                                                        </span>
                                                    </div>
                                                    @elseif($e_is_time_over && $attendanceData['in_time'] && $attendanceData['out_time'])

                                                    <div class="form-check form-check-inline" style="cursor: pointer">
                                                        <span data-type="evening_in" data-emp_id="{{ $employee->id }}"
                                                            data-in_time="{{ $employee->job_type_info ? $employee->job_type_info->in_time : '' }}"
                                                            style="margin-left: 10px" id="in_time_{{ $employee->id }}"
                                                            class="form-check-input office_in_out action-btn current-time">
                                                                {{ $attendanceData['evening_in'] ?? '00:00:00' }}
                                                        </span>
                                                    </div>
                                                    @elseif(!$attendanceData['in_time'] || !$attendanceData['out_time'])
                                                        Inactive
                                                    @else
                                                        Late
                                                    @endif
                                                </td>

                                                <!-- evening  attendance out button  -->
                                                <td>
                                                    @if($attendanceData['evening_out'] != "" )

                                                    <div class="form-check form-check-inline">
                                                        <span style="margin-left: 10px"
                                                            class="form-check-input @if($attendanceData['status'] == 1 || $attendanceData['status'] == 3)  office_in_out_active @else office_in_out_active_absen  @endif ">
                                                            @if($attendanceData['status'] == 1)
                                                             {{ $attendanceData['evening_out'] ?? '00:00:00' }}
                                                            @elseif($attendanceData['status'] == 0)
                                                            Absen
                                                            @elseif($attendanceData['status'] == 2)
                                                            Leave
                                                            @elseif($attendanceData['status'] == 3)
                                                            Weekend
                                                            @else
                                                            NO
                                                            @endif
                                                        </span>
                                                    </div>
                                                    @elseif(($e_is_time_over || $attendanceData['status'] == 1) and $attendanceData['evening_in'])
                                                    <div class="form-check form-check-inline" style="cursor: pointer">
                                                        <span data-type="evening_out" data-emp_id="{{ $employee->id }}" id="out_time_{{ $employee->id }}"
                                                            data-out_time="{{ $employee->job_type_info ? $employee->job_type_info->out_time : '' }}"
                                                            class="form-check-input office_in_out current-time action-btn">
                                                                {{ $attendanceData['evening_out'] ?? '00:00:00' }}
                                                        </span>
                                                    </div>
                                                </td>
                                                @elseif(!$attendanceData['evening_in'])
                                                    Inactive
                                                @endif
                                                <!-- Display Total Hours Worked -->

                                                <!-- Display Total Hours Worked -->


                                                <td>
                                                    @if(!empty($attendanceData['total_hours']))
                                                    {{$attendanceData['total_hours']}} @else 00:00:00 @endif
                                                </td>

                                                <!-- Display Overtime -->
                                                <td>
                                                    @if( !empty($attendanceData['overtime']))
                                                    {{$attendanceData['overtime']}} @else 00:00:00 @endif
                                                </td>
                                                <!-- Display Late Time -->

                                                <td>
                                                    @if(!empty($attendanceData['late_time'] ))
                                                    {{$attendanceData['late_time'] }} @else 00:00:00 @endif

                                                </td>
                                                {{-- <td></td> --}}
                                            </tr>
                                            @else

                                            <tr class="text-center">
                                                {{-- <td>{{$index+1}}</td> --}}
                                                <td>{{ date('d/m/Y', strtotime($date)) }}</td>
                                                {{-- <td>{{ date('D', strtotime($date)) }}</td> --}}

                                                <td class="emp_this_month_atten text-left" data-emp_id="{{$employee->id}}" data-date="{{$date}}" title="Current Month Attendance of {{$employee->full_name}}">{{$employee->full_name}}</td>

                                                <td>{{$employee->code}}</td>

                                                <td style="width:200px">
                                                    <select name="project_id" id="" class="project_id common-select2 form-control text-left">
                                                        <option value=""> Select </option>
                                                        @foreach ($projects as $project)
                                                            <option value="{{$project->id}}"> {{$project->project_name}} </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td>{{$employee->dvision?$employee->dvision->name:''}}</td>

                                                {{-- <td> WEEKEND </td> --}}
                                                <td> WEEKEND  </td>
                                                <td>00:00:00</td>
                                                <!-- morning attendance button -->
                                                <td>00:00:00 </td>
                                                  <!-- evening  attendance out button  -->
                                                  <td> 00:00:00 </td>
                                                <td>00:00:00</td>
                                                <!-- Display Overtime -->
                                                <td>00:00:00</td>
                                                <!-- Display Late Time -->
                                                <td> 00:00:00</td>
                                                <td> 00:00:00</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                        {{-- @dd($checkd) --}}
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
</div>

<div class="modal fade" style="width: 100%;" id="employee-modal-show" tabindex="-1" role="dialog"
aria-labelledby="employee-modal-show" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="padding-right: 0px !important;">
    <div class="modal-content ">
        <div class="" id="show-modal">


        </div>
    </div>
</div>
{{-- **************** Employees edit modal end ************************ --}}
</div>

@endsection
@push('js')
<script>

    $(".attendance_date").datepicker({
        dateFormat: 'dd/mm/yy',
        maxDate:  {{date('d/m/Y')}}
    });

    $(document).ready(function() {
        $('body').addClass('no-loader'); // Add class to disable loader
        function updateClock() {
        let now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes().toString().padStart(2, '0');
        let seconds = now.getSeconds().toString().padStart(2, '0');

        let ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        let currentTime = `${hours}:${minutes}:${seconds} ${ampm}`;

        $(".current-time").each(function() {
            $(this).text(currentTime);
        });
    }
    setInterval(updateClock, 1000);
    updateClock();

        $(document).on('click', '.btn_create', function(e){
            $('#newEmployeeAttendance').modal('show')
        })
        // emp_this_month_atten
        $(document).on('click', '.emp_this_month_atten', function(e) {
            e.preventDefault();

            var emp_id = $(this).data('emp_id');
            var date = $(this).data('date');

            $.ajax({
                url: '{{ route("employee-wise-attendance-show") }}',
                type: 'POST',
                data: {
                    emp_id: emp_id,
                    date: date,
                    _token: '{{ csrf_token() }}', // Use @csrf in Blade if needed
                },
                success: function(response) {
                    // Assuming response contains HTML for modal content
                    console.log(response);

                    $("#show-modal").empty().html(response);
                    $("#employee-modal-show").modal('show');

                },
                error: function(xhr, status, error) {
                    // Handle AJAX errors
                    toastr.error('An error occurred. Please try again later.');
                    console.error("Error:", error);
                }
            });
        });



        $(document).on('click', '.action-btn', function(e) {
            e.preventDefault();
            var project_id = $(this).closest('tr').find('.project_id').val();
            var $this = $(this);

            var emp_id = $(this).data('emp_id');
            var type = $(this).data('type');
            var date = $('.date-value').val();
            var _token = '{{csrf_token()}}';

            // Get today's date in DD/MM/YYYY format
            var today = new Date();
            var day = String(today.getDate()).padStart(2, '0');
            var month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            var year = today.getFullYear();
            var todayFormatted = `${day}/${month}/${year}`;

            if (!project_id) {
                toastr.warning('The project is required!.');
                return;
            }

            var alert_type = '';
           if (type == 'morning_in' || type == 'evening_in') {
                alert_type = "entered";
                title = "Mark Entry";
            } else if (type == 'morning_out' || type == 'evening_out') {
                alert_type = "leave";
                title = "Mark Exit";
            }

            Swal.fire({
                title: title,
                text: `Are you sure this employee has ${alert_type} the office?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: `Yes, ${type.replace('_', ' ')}`,
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#2ecc71',
                cancelButtonColor: '#3085d6',
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{route("employee-attendance.store")}}',
                        type: 'post',
                        data: {
                            emp_id: emp_id,
                            type: type,
                            date: date,
                            project_id:project_id,
                            _token: _token,
                        },

                        success: function(response) {
                            if (response) {

                                $this.addClass('office_in_out_active');
                                $this.removeClass('current-time');

                                if (response.type === 'error') {
                                    toastr.error(response.message);
                                } else if (response.type === 'success') {
                                    toastr.success(response.message);
                                } else if (response.type === 'warning') {
                                    toastr.warning(response.message);
                                }
                            }
                        }
                    });
                }
            });
        });
    })

    $(document).on('mouseenter', '.commont-select2', function(){
        $(this).select2();
    });

</script>
@endpush

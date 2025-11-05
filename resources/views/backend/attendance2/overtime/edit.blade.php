
@extends('layouts.backend.app')
@section('content')
@include('backend.tab-file.style')
@php
    use Carbon\Carbon;
    use App\HolidayRecode;

@endphp
<style>
    .table .thead-light th {
        color: #F2F4F4;
        background-color: #34465b;
        border-color: #DFE3E7;
    }
    tr:nth-child(even) {
        background-color: #c8d6e357;
    }
</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <div class="nav nav-tabs master-tab-section" id="nav-tab" role="tablist">
                <a href="{{route('new-employee-attendance')}}" class="nav-item nav-link " role="tab" aria-controls="nav-contact" aria-selected="false" id="parentProfileTab">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/employee-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
                    </div>
                    <div>Employees Attendance</div>
                </a>
                <a href="{{route("new-employee-leave")}}" class="nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/leave-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
                    </div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Employees Leave&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                </a>
                <a href="{{route("employee-overtime.index")}}" class="nav-item nav-link active" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/overtime.png')}}" alt="" srcset="" class="img-fluid" width="50">
                    </div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp; Employees Overtime &nbsp;&nbsp;&nbsp;&nbsp;</div>
                </a>
            </div>
            <div class="tab-content bg-white">
                <div id="employeeAttendance" class="tab-pane active">
                    <div class="content-body">
                        <section id="basic-vertical-layouts">
                            <div class="row match-height">
                                <div class="col-md-12 col-12">
                                    <div class="cardStyleChange">
                                        <div class="d-flex card-header">
                                            <h4 class="flex-grow-1">Update Employee Overtime</h4>
                                        </div>
                                        <div class="cord-body m-2">
                                            <form class="form form-vertical" action="{{route('employee-overtime-update')}}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control datepicker @error('date') error @enderror" placeholder="dd/mm/yyyy" value="{{ date('d/m/Y', strtotime($date))}}" required disabled>
                                                                <input type="hidden" value="{{$date}}" name="date">
                                                                <small class="text-danger" id="holiday_message"></small>
                                                                @error('date')
                                                                <span class="error">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div style="width: 750px;">
                                                                <table class="table mb-0 table-sm table-hover">
                                                                    <thead  class="thead-light">
                                                                        <tr class="text-center" style="height: 40px;">
                                                                            <th>Name</th>
                                                                            <th>Project Name</th>
                                                                            <th>Overtime <small class="text-green">(Hours Base)</small></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($employees as $employee)
                                                                        <tr class="text-center trFontSize">
                                                                            <td>{{ $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name }}</td>
                                                                            @php
                                                                                $overtime = App\EmployeeOvertime::where('employee_id', $employee->id)->where('date', $date)->first();
                                                                            @endphp
                                                                            <td>
                                                                                <select style="max-width: 200px !important;" name="project_id[]" id="" class="common-select2 project_name" required style="width: 100% !important;">
                                                                                    <option value="">Select Project</option>
                                                                                    @foreach ($projects as $project)
                                                                                        <option value="{{$project->id}}" {{$overtime && $project->id==$overtime->project_id?'selected':''}}>{{$project->project_name}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input type="number" step="any" name="hours[]" value="{{$overtime?$overtime->hours:''}}">
                                                                                <input type="hidden" name="employee_id[]" value="{{$employee->id}}">
                                                                                <input type="hidden" name="employee_overtime_id[]" value="{{$overtime?$overtime->id:''}}">
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="row pt-1" style="width: 796px;">
                                                                <div class="col-12 d-flex justify-content-end">
                                                                    <button type="submit" class="btn btn-primary mr-1">Update</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function() {
        check_date();
       // Page Script
        function check_date(){
            var date = $('#check_new_date').val();
        var _token = '{{csrf_token()}}';
        $.ajax({
            url:  '{{route("search-holiday-recode")}}',
            type: 'post',
            data: {
                date: date,
                _token: _token,
            },
            success:function(response){
                if(response){
                    $("#holiday_message").html(response.reason);
                    $("#check_new_date").val("");
                }else{
                    $("#holiday_message").html("");
                }
            }
        });
        }


       $('.present-all').click(function (event) {
               $('.present-status').prop('checked', true);
       });

       $('.absent-all').click(function (event) {
               $('.absent-status').prop('checked', true);
       });

    });
    $(document).on('change', '#check_new_date', function(e){
        var date = $(this).val();
        var _token = '{{csrf_token()}}';
        $.ajax({
            url:  '{{route("search-holiday-recode")}}',
            type: 'post',
            data: {
                date: date,
                _token: _token,
            },
            success:function(response){
                if(response){
                    $("#holiday_message").html(response.reason);
                    $("#check_new_date").val("");
                }else{
                    $("#holiday_message").html("");
                }
            }
        });
    })
</script>
@endpush

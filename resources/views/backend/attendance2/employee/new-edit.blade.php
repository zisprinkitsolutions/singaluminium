
@extends('layouts.backend.app')
@section('content')
@include('backend.tab-file.style')
@php
    use Carbon\Carbon;
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
                {{-- <a href="{{route("students-attendance")}}" class="nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/student-icon.png')}}" alt="" srcset="" class="img-fluid" width="55">
                    </div>
                    <div>Students Attendance</div>
                </a>
                <a href="{{route("new-student-leave")}}" class="nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/document-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
                    </div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Students Leave&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                </a> --}}
                <a href="{{route('new-employee-attendance')}}" class="nav-item nav-link active" role="tab" aria-controls="nav-contact" aria-selected="false" id="parentProfileTab">
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
                <a href="{{route("employee-overtime.index")}}" class="nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/overtime.png')}}" alt="" srcset="" class="img-fluid" width="50">
                    </div>
                    <div>&nbsp;&nbsp;&nbsp;&nbsp; Employees Overtime &nbsp;&nbsp;&nbsp;&nbsp;</div>
                </a>
            </div>
            <div class="tab-content bg-white">
                <div id="employeeAttendance" class="tab-pane active">
                    @if(session('msg'))
                    <div class="col-md-12">
                        <div class="alert alert-warning ">
                            {!! session('msg') !!}
                        </div>
                    </div>
                    @endif
                    <div class="content-body">
                        <section id="basic-vertical-layouts">
                            <div class="row match-height">
                                <div class="col-md-12 col-12">
                                    <div class="cardStyleChange">
                                        <div class="card-body">
                                            <div class="form-body">
                                                @if (isset($attendances))
                                                    <div class="cardStyleChange m-1">
                                                        <h4 class="">Employee Attendance Edit</h4>
                                                        <form class="form form-vertical" action="{{route('new-employee-attendance-update', ['new_date'=>$date1])}}" method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    {{-- @method('PUT') --}}
                                                            <div class="form-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <select name="duty_shift" id="duty_shift" class="form-control" required>
                                                                            <option value="">Select Option</option>
                                                                            <option value="Morning">Morning</option>
                                                                            <option value="Afternoon">Afternoon</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <p class="text-right">
                                                                            <button type="button" class="btn btn-success present-all ">Present All</button>
                                                                            <button type="button" class="btn btn-success absent-all">Absent All</button>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">


                                                                    <div class="col-md-12">

                                                                        <table class="table mb-0 table-sm table-hover">
                                                                            <thead class="thead-light">
                                                                                <tr class="text-center" style="height: 40px;">
                                                                                    <th>Name</th>
                                                                                    <th style="max-width: 50px !important;">Project Name</th>
                                                                                    <th>Tasks Name</th>
                                                                                    <th>Status</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($attendances as $employee)

                                                                                <tr class="text-center">
                                                                                    <input type="hidden" name="employee_id[]" value="{{$employee->employee_id}}">
                                                                                    <td>{{ $employee->employee->first_name.' '.$employee->employee->middle_name.' '.$employee->employee->last_name }}</td>
                                                                                    <td>
                                                                                        <select style="max-width: 200px !important;" name="project_id[]" id="" class="common-select2 project_name" required style="width: 100% !important;">
                                                                                            <option value="">Select Project</option>
                                                                                            @foreach ($projects as $project)
                                                                                                <option value="{{$project->id}}" {{$project->id==$employee->project_id?'selected':''}}>{{$project->project_name}}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </td>
                                                                                    <td class="taks_name">
                                                                                        <select name="project_task_id[]" class="form-control" style="max-width: 200px !important;">
                                                                                            <option value="">Select Task</option>
                                                                                            @foreach ($employee->project->tasks as $key => $item)
                                                                                                <option value="{{$item->id}}" {{$item->id == $employee->project_task_id?'selected':''}}>{{$item->task_name}}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </td>
                                                                                    <td>
                                                                                        <ul class="list-unstyled mb-0">
                                                                                            <li class="d-inline-block mr-2">
                                                                                                <fieldset>
                                                                                                    <div class="radio">
                                                                                                        <input type="radio" class="present-status" name="status[{{$employee->employee_id}}]" id="present-{{$employee->id}}" {{($employee->morning || $employee->afternoon) == 1?'checked':''}}  value="1" >
                                                                                                        <label for="present-{{$employee->id}}">Present</label>
                                                                                                    </div>
                                                                                                </fieldset>
                                                                                            </li>
                                                                                            <li class="d-inline-block mr-2">
                                                                                                <fieldset>
                                                                                                    <div class="radio">
                                                                                                        <input type="radio" class="absent-status" name="status[{{$employee->employee_id}}]" id="absent-{{$employee->id}}" {{($employee->morning || $employee->afternoon) == 0?'checked':''}} value="0">
                                                                                                        <label for="absent-{{$employee->id}}">Absent</label>
                                                                                                    </div>
                                                                                                </fieldset>
                                                                                            </li>

                                                                                        </ul>
                                                                                    </td>
                                                                                </tr>
                                                                                @endforeach

                                                                            </tbody>
                                                                        </table>

                                                                        <div class="row pt-1">
                                                                            <div class="col-12 d-flex justify-content-end">
                                                                                {{-- <input type="hidden" name="class_id" value="{{$inputs['class_name']}}">
                                                                                <input type="hidden" name="date" value="{{$inputs['date']}}">
                                                                                <input type="hidden" name="section_id" value="{{$inputs['section']->id}}"> --}}
                                                                                <button type="submit" class="btn btn-primary mr-1">
                                                                                    <div class="d-flex">
                                                                                        <div class="formSaveIcon">
                                                                                            <img src="{{asset('assets/backend/app-assets/icon/save-icon.png')}}" alt="" srcset="" width="20">
                                                                                        </div>
                                                                                        <div><span> Update</span></div>
                                                                                    </div>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        @if ($date)
                            <div class="daily-attendance-report">
                                <div class=" d-flex justify-content-end">
                                    <a href="{{route('employee-attendance-print', ['date'=>$date])}}" class="btn btn-primary mr-1 mb-1" target="blank">Print</a>
                                </div>
                                @php
                                    $employees= App\Employee::get();
                                    // $today = Carbon::now()->startOfMonth();
                                    $today = Carbon::createFromFormat('Y-m', $date);
                                    $dates = [];
                                    for($i=1; $i < $today->daysInMonth + 1; ++$i) {
                                        $dates[] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i);
                                    }
                                @endphp
                                <table class="table table-sm table-responsive table-bordered">
                                    <tr style="background:yellow;">
                                        <td colspan="{{2+count($dates)}}"><h1 class="text-center" style="margin-bottom:0;">Daily Attendance Sheet</h1></td>
                                    </tr>
                                    <tr style="height: 20px; background-color: black;">
                                        <td colspan="{{2+count($dates)}}"></td>
                                    </tr>
                                    <tr style="height: 50px; background:#f1640d7b" class="text-center">
                                        <td colspan="{{2+count($dates)}}">
                                            <p class="text-center">Start Date Period
                                                <br> {{date('d-F-Y', strtotime($today->firstOfMonth()))}} From {{date('d-F-Y', strtotime($today->lastOfMonth()))}}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr style="height: 20px; background-color: black;">
                                        <td colspan="{{2+count($dates)}}"></td>
                                    </tr>
                                    <tr style="height: 70px;">
                                        <td style="background:yellow;">Rank</td>
                                        <td style="min-width: 150px !important;background:yellow;" class="text-center">Name</td>
                                        @foreach ($dates as $item)
                                            <td class="separate-color">Day {{ date('d', strtotime($item)) }}</td>
                                        @endforeach
                                    </tr>
                                    @foreach ($employees as  $key => $employee)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{$employee->fname}}</td>
                                            @foreach ($dates as $item)
                                                @if ($a = App\EmployeeAttendance::where('date', date('Y-m-d', strtotime($item)))->where('employee_id', $employee->id)->first())
                                                    @if ($a->status==1)
                                                        <td class="text-center">&#10003;</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @else
                                                    <td class="bg-light"></td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="2" class="text-center" style="background:yellow;">Attendace</td>
                                        @foreach ($dates as $item)
                                            <td class="bg-light text-center">
                                                {{count(App\EmployeeAttendance::where('date', date('Y-m-d', strtotime($item)))->where('status',1)->get())}}
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center" style="background:yellow;">Attendace %</td>
                                        @foreach ($dates as $item)
                                            <td class="bg-light text-center">
                                                @php
                                                $f = 0;
                                                    $a = App\EmployeeAttendance::where('date', date('Y-m-d', strtotime($item)))->where('status',1)->get();
                                                    if(count($a)>0){
                                                        $f = count($a)/count($employees);
                                                    }
                                                @endphp
                                                @if (count($a)>0)
                                                    {{number_format(($f)*100,1)}}
                                                @else
                                                    0
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="newEmployeeAttendance" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <section class="print-hideen border-bottom">
            <div class="d-flex flex-row-reverse">
                <div class="mIconStyleChange"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
                {{-- <div class="mIconStyleChange"><a href="#" class="btn btn-icon btn-success"><i class="bx bx-edit"></i></a></div>
                <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-secondary"><i class='bx bx-printer'></i></a></div>
                <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-primary"><i class='bx bxs-file-pdf'></i></a></div>
                <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
            </div>
        </section>
        @include('backend.tab-file.modal-header-info')
        <section id="basic-vertical-layouts">
            <div class="row match-height">
                <div class="col-md-12 col-12">
                    <div class="cardStyleChange">
                        <div class="card-body">
                            <form class="form form-vertical" action="{{route('employee-attendance.store')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="date" id="date" class="form-control @error('date') error @enderror" name="date" value="{{ isset($inputs) ? $inputs['date'] : old('date')}}" required>
                                                @error('date')
                                                <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-right">
                                                <button type="button" class="btn btn-success present-all ">Present All</button>
                                                <button type="button" class="btn btn-success absent-all">Absent All</button>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table mb-0 table-sm table-hover">
                                                    <thead  class="thead-light">
                                                        <tr class="text-center" style="height: 40px;">
                                                            <th>Name</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($employees as $employee)
                                                        <tr class="trFontSize">
                                                            <td>{{ $employee->first_name.' '.$employee->middle_name.' '.$employee->last_name }}</td>
                                                            <td>
                                                                <ul class="list-unstyled mb-0">
                                                                    <li class="d-inline-block">
                                                                        <fieldset>
                                                                            <div class="radio">
                                                                                <input type="radio" class="present-status" name="status[{{$employee->id}}]" id="present-{{$employee->id}}" checked value="1" >
                                                                                <label for="present-{{$employee->id}}">Present</label>
                                                                            </div>
                                                                        </fieldset>
                                                                    </li>
                                                                    <li class="d-inline-block">
                                                                        <fieldset>
                                                                            <div class="radio">
                                                                                <input type="radio" class="absent-status" name="status[{{$employee->id}}]" id="absent-{{$employee->id}}" value="0">
                                                                                <label for="absent-{{$employee->id}}">Absent</label>
                                                                            </div>
                                                                        </fieldset>
                                                                    </li>
                                                                </ul>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-primary mr-1">Save Attendance</button>
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
        @include('backend.tab-file.modal-footer-info')
      </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function() {
       // Page Script

       
       $('.present-all').click(function (event) {
               $('.present-status').prop('checked', true);
       });

       $('.absent-all').click(function (event) {
               $('.absent-status').prop('checked', true);
       });

    });
    $(document).on('change', '.project_name', function(e){
        var project_id = $(this).val();
        var taks_name =  $(this).closest("tr").find(".taks_name");
        var employee_id =  $(this).closest("tr").find(".employee_id").val();
        var _token = '{{csrf_token()}}';
        $.ajax({
            url:  '{{route("search-project-task-list")}}',
            type: 'post',
            data: {
                project_id: project_id,
                employee_id: employee_id,
                _token: _token,
            },
            success:function(response){
                taks_name.html(response);
            }
        });
    })
</script>
@endpush

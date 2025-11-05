
@extends('layouts.backend.app')
@section('content')
@include('backend.tab-file.style')

<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <div class="nav nav-tabs master-tab-section" id="nav-tab" role="tablist">
                <a href="{{route("students-attendance")}}" class="nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false">
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
                </a>
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
                        <form class="form form-vertical" method="get" enctype="multipart/form-data">
                            <section id="basic-vertical-layouts">
                                <div class="row match-height">
                                    <div class="col-md-12 col-12">
                                        <div class="cardStyleChange">
                                            <div class="d-flex card-header">
                                                <h4 class="flex-grow-1">Search Employee Attendance</h4>
                                                <button type="button" class="btn btn-primary btn_create formButton" title="Add" data-toggle="modal" data-target="#newEmployeeAttendance">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" width="25">
                                                        </div>
                                                        <div><span>Take Attendance</span></div>
                                                    </div>
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-6 col-12">
                                                            <label for="date">Date</label>
                                                                <input type="date" id="date" class="inputFieldHeight form-control @error('date') error @enderror" name="date" value="{{ isset($inputs) ? $inputs['date'] : old('date')}}" required>
                                                                @error('date')
                                                                <span class="error">{{ $message }}</span>
                                                                @enderror
                                                        </div>
                                                        <div class="col-12 col-md-6 d-flex justify-content-end">
                                                            {{-- <button type="submit" class="btn btn-primary mr-1">Search</button> --}}
                                                            <button type="submit" class="btn btn-primary formButton mSearchingBotton mt-1 mb-1" title="Searching" >
                                                            <div class="d-flex">
                                                                <div class="formSaveIcon">
                                                                    <img src="{{asset('assets/backend/app-assets/icon/searching-icon.png')}}" alt="" srcset="" width="20">
                                                                </div>
                                                                <div><span> Search</span></div>
                                                            </div>
                                                        </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                        
                                </div>
                                @if (isset($attendances))
                                    <div class="cardStyleChange m-2">
                                        <h4 class="">Employee Attendance List</h4>
                                        <div class="table-responsive"">
                                            <table class="table mb-0 table-sm table-hover">
                                                <thead  class="thead-light">
                                                    <tr style="height: 50px;">
                                                        <th>Name</th>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($attendances as $attendance)
                                                    <tr class="border-bottom trFontSize">
                                                        <td>{{ $attendance->employee->fname.' '.$attendance->employee->mname }}</td>
                                                        <td> {{$attendance->date}} </td>
                                                        <td>
                                                            @if ($attendance->status==1)
                                                            <div class="badge badge-success mr-1">Present</div>
                                                            @else
                                                            <div class="badge badge-danger mr-1">Absent</div>  
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </section>
                        </form>
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
                                                        <tr style="height: 50px;">
                                                            <th>Name</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($employees as $employee)
                                                        <tr class="trFontSize">
                                                            <td>{{ $employee->fname.' '.$employee->mname.' '.$employee->family_name }}</td>
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

       $('#st-class').change(function(){
           var class_id= $(this).val();
           var csrf_token= '{{ csrf_token()}}';
           $.ajax({
           url:  '{{route("get_sections")}}',
           dataType: 'json',
           type: 'post',
           data: {class_id: class_id, _token: csrf_token },
           success:function(response){
               
               var optionHtml= '<option> Select Section </option>';

               response.forEach(function(element, index) { 
                   console.log(element);
                   optionHtml += "<option value='"+element.id +"'> "+ element.name+"</option>";
                });
                $('#st-section').html(optionHtml);
                console.log(optionHtml);
               
                   
           }
           });
       });


       $('.present-all').click(function (event) {
               $('.present-status').prop('checked', true);
       });

       $('.absent-all').click(function (event) {
               $('.absent-status').prop('checked', true);
       });

    });
</script>
@endpush
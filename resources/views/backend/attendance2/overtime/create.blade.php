@extends('layouts.backend.app')

@push('css')

@endpush

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        {{-- <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="breadcrumbs-top">
                    <h5 class="content-header-title float-left pr-1 mb-0">Student Attendance</h5>
                    <div class="breadcrumb-wrapper d-none d-sm-block">
                        <ol class="breadcrumb p-0 mb-0 pl-1">
                            <li class="breadcrumb-item"><a href="index.html"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">Attendance</a>
                            </li>
                            <li class="breadcrumb-item active"><a href="#">Student Attendance</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row">
            <div class="col-md-12">
              @if ($errors->any())
                  <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif
            </div>
            @if(session('msg'))
            <div class="col-md-12">
                <div class="alert alert-warning ">
                    {!! session('msg') !!}
                </div>
            </div>
            @endif
          </div>
        <div class="content-body">


                <!-- Basic Vertical form layout section start -->
                <section id="basic-vertical-layouts">
                    <div class="row match-height">
                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-header">

                                    <h4 class="card-title">Employee Attendance</h4>
                                    {{-- <a href="{{ route('attendance.index') }}" class="btn btn-info">Search Attendance</a> --}}
                                </div>
                                <div class="card-body">
                                    <form class="form form-vertical"
                                            action="{{route('employee-attendance.store')}}" method="POST" enctype="multipart/form-data">
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

                                                    <!-- table bordered -->
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($employees as $employee)

                                                                <tr>
                                                                <td>{{ $employee->fname.' '.$employee->mname.' '.$employee->family_name }}</td>
                                                                <td>
                                                                    <ul class="list-unstyled mb-0">
                                                                        <li class="d-inline-block mr-2 mb-1">
                                                                            <fieldset>
                                                                                <div class="radio">
                                                                                    <input type="radio" class="present-status" name="status[{{$employee->id}}]" id="present-{{$employee->id}}" checked value="1" >
                                                                                    <label for="present-{{$employee->id}}">Present</label>
                                                                                </div>
                                                                            </fieldset>
                                                                        </li>
                                                                        <li class="d-inline-block mr-2 mb-1">
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
                                                            {{-- <input type="hidden" name="class_id" value="{{$inputs['class_name']}}">
                                                            <input type="hidden" name="date" value="{{$inputs['date']}}">
                                                            <input type="hidden" name="section_id" value="{{$inputs['section']->id}}"> --}}
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

                    <!-- Bordered table start -->

                    @if (isset($attendances) && count($attendances)>0)
                        <div class="row" id="table-bordered">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Update Student Attendance</h4>

                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('update_st_attend')}}" method="POST">
                                            @csrf
                                        <p class="text-right">
                                            <button type="button" class="btn btn-success present-all ">Present All</button>
                                            <button type="button" class="btn btn-success absent-all">Absent All</button>
                                        </p>

                                        <!-- table bordered -->
                                        <div class="table-responsive">
                                            <table class="table table-bordered mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($attendances as $attendance)

                                                    <tr>
                                                    <td>{{ $attendance->student->fname.' '.$attendance->student->mname }}</td>
                                                    <td>
                                                        <ul class="list-unstyled mb-0">
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="radio">
                                                                        <input type="radio" class="present-status" name="status[{{$attendance->id}}]" id="present-{{$attendance->id}}" value="1" {{$attendance->status==1 ? 'checked': ''}} >
                                                                        <label for="present-{{$attendance->id}}">Present</label>
                                                                    </div>
                                                                </fieldset>
                                                            </li>
                                                            <li class="d-inline-block mr-2 mb-1">
                                                                <fieldset>
                                                                    <div class="radio">
                                                                        <input type="radio" class="absent-status" name="status[{{$attendance->id}}]" id="absent-{{$attendance->id}}" value="0" {{$attendance->status==0 ? 'checked' : ''}}>
                                                                        <label for="absent-{{$attendance->id}}">Absent</label>
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
                                                <input type="hidden" name="class_id" value="{{$inputs['class_name']}}">
                                                <input type="hidden" name="date" value="{{$inputs['date']}}">
                                                <input type="hidden" name="section_id" value="{{$inputs['section']->id}}">
                                                <button type="submit" class="btn btn-primary mr-1">Update</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(isset($students) && count($students)>0)
                        <div class="row" id="table-bordered">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Save Student Attendance</h4>

                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('attendance.store')}}" method="POST">
                                            @csrf
                                            <p class="text-right">
                                                <button type="button" class="btn btn-success present-all ">Present All</button>
                                                <button type="button" class="btn btn-success absent-all">Absent All</button>
                                            </p>

                                            <!-- table bordered -->
                                            <div class="table-responsive">
                                                <table class="table table-bordered mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($students as $employee)

                                                        <tr>
                                                        <td>{{ $employee->fname.' '.$employee->mname }}</td>
                                                        <td>
                                                            <ul class="list-unstyled mb-0">
                                                                <li class="d-inline-block mr-2 mb-1">
                                                                    <fieldset>
                                                                        <div class="radio">
                                                                            <input type="radio" class="present-status" name="status[{{$employee->id}}]" id="present-{{$employee->id}}" checked value="1" >
                                                                            <label for="present-{{$employee->id}}">Present</label>
                                                                        </div>
                                                                    </fieldset>
                                                                </li>
                                                                <li class="d-inline-block mr-2 mb-1">
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
                                                    <input type="hidden" name="class_id" value="{{$inputs['class_name']}}">
                                                    <input type="hidden" name="date" value="{{$inputs['date']}}">
                                                    <input type="hidden" name="section_id" value="{{$inputs['section']->id}}">
                                                    <button type="submit" class="btn btn-primary mr-1">Save Attendance</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif


                    <!-- Bordered table end -->
                </section>
                <!-- Basic Vertical form layout section end -->


        </div>
    </div>
</div>
@endsection

@push('js')
    {{-- <script src="{{ asset('assets/backend/app-assets/vendors/js/jquery/jquery.min.js') }}"></script> --}}
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

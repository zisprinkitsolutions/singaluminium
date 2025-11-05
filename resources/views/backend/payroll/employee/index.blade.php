




@extends('layouts.backend.app')
@push('css')
<!-- summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>

@endpush
@php

    use App\Models\Payroll\Employee;
    use App\Models\Payroll\EmployeeAttendance;
    use App\Models\Payroll\EmployeePolicy;
    use Carbon\Carbon;


@endphp
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

.tab-content1 {
    padding: 15px;
    border-radius: 5px;
}
.nav-tabs1 .nav-link {
    color: #000;
}
.nav-tabs1 .nav-link.active {
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
}
.nav.nav-tabs1 ~ .tab-content1 {

    border: 1px solid #dfe3e700;
}
.tab-content1 {
    background: #00000000 !important;

}
.nav.nav-tabs1 .nav-item .nav-link.active, .nav.nav-pills .nav-item .nav-link.active {
    box-shadow: 0 2px 4px 0 rgb(0 61 177 / 0%);
}
.nav-tabs1 .nav-link.active {
    background: #475f7b !important;
    color: #ececec !important;

}
.nav-tabs1 .nav-link, .nav-pills .nav-link {
    background-color: #f3f3f3;
    color: #100f0f !important;
}
.nav.nav-tabs1 {
    border-bottom: 1px solid #82868c;
}

.tab-pane{display: none;}
</style>
<style>
    .table .thead-light th {
        color:#F2F4F4 ;
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

    @media (min-width: 992px) {

        .table-small-divice{
            display: none;
        }

        #employee_table_data{
            display:block !important;
        }

        #add-employee{
            display: block !important;
        }
    }
</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
              @include('backend.payroll.tab-sub-tab._basic_info_header', ['activeMenu' => 'employee-profile'])
            <div class="tab-content bg-white">
                <div class="tab-pane active pt-1" style="max-width:100%">
                    {{-- @include('backend.payroll.tab-sub-tab._baisc_info_submenu', ['activeMenu' => 'profile']) --}}

                            <div class="cardStyleChange">
                                <div class="card-body pb-0" style="margin-top: 13px; margin-left: 10px;">
                                    <!-- table bordered -->
                                    <div class="row">
                                        <div class="col-md-3" style="display:none;" id="add-employee">
                                            @if(Auth::user('HR_Create'))
                                            <button type="button" class="btn btn-primary btn_create formButton employee_modal_open"
                                            data-modal="#employee-modal" title="Add" data-toggle="modal" data-target="#studentProfileAdd">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" width="25">
                                                    </div>
                                                    <div><span>Add New  </span></div>
                                                </div>
                                            </button>
                                            @endif
                                        </div>
                                        <div class="col-md-9 col-left-padding">
                                            <div class="row ml-1">
                                                <div class="col-md-4">
                                                    <form action="" method="GET" class="d-flex row">
                                                        <div class="row form-group col-8" style="padding-left:7px;">
                                                            <input type="text"
                                                                class="inputFieldHeight form-control " name="search" value="{{$search}}"
                                                                placeholder="search by name" required autocomplete="off">
                                                        </div>
                                                        <div class="col-4 mr-0">
                                                            <button type="submit" title="search" class="btn mSearchingBotton formButton"
                                                                title="Search">
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

                                               <div class="col-md-8">
                                                    <form action="" method="GET" class="">
                                                        <div class="row">
                                                            <div class="row form-group col-6 col-md-3 mr-0">
                                                                <input type="text"
                                                                    class="inputFieldHeight form-control datepicker"
                                                                    name="from" @if($fromDate != null) value="{{date('d/m/Y', strtotime($fromDate))}}"@endif
                                                                        placeholder="From Date"  autocomplete="off">
                                                            </div>
                                                            <div class="row form-group col-6 col-md-3 mr-0">
                                                                <input type="text"
                                                                    class="inputFieldHeight form-control datepicker" name="to"
                                                                    @if($toDate != null)value="{{date('d/m/Y', strtotime($toDate))}}" @endif
                                                                    placeholder="To Date"  autocomplete="off">
                                                            </div>
                                                            <div class="col-8 col-md-4">
                                                                <select name="division" id="division"
                                                                    class="form-control common-select errorr-abcd"
                                                                    style="width: 100% !important; " >
                                                                    <option value="">Select Department</option>
                                                                    @foreach ($divisions as $division)
                                                                    <option value="{{$division->id}}" {{$division_search == $division->id ? 'selected' :''}}>{{$division->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-4 col-md-2">
                                                                <button type="submit" title="search" class="btn mSearchingBotton  formButton"
                                                                    title="Search">
                                                                    <div class="d-flex">
                                                                        <div class="formSaveIcon">
                                                                            <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                                width="25">
                                                                        </div>
                                                                    </div>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-small-divice">
                                    <div class="table-responsive" id="employee_table_data" style="min-height: 300px">
                                        <table class="table  table-sm table-hover" style="max-width: 100%">
                                            <thead  class="thead-light">
                                                <tr class="text-center" style="height: 40px;">
                                                    {{-- <th>NO</th> --}}
                                                    <th class="text-left" style="min-width:15px;">Name</th>
                                                    <th style="min-width:90px; max-width:100px;">DEPARTMENT</th>
                                                    <th style="min-width:80px; max-width:90px;">JOIND ON</th>
                                                    {{-- <th>NEXT VISIT</th> --}}
                                                    <th style="min-width:90px; max-width:100px;">Salary</th>
                                                    <th style="min-width:80px; max-width:100px;">STATUS</th>
                                                    {{-- <th>Action</th> --}}
                                                </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach ($employees as $key => $data)
                                                    @php
                                                    $employee = Employee::where('emp_id', $data->emp_id)->first();
                                                    if ($employee) {
                                                        // Get the most recent EmployeePolicy based on the effective date
                                                        $date = date('Y-m-d');
                                                        $emp_policy = policy_helper($data->emp_id,$date);

                                                        $last_visit = $employee->last_visite ? $employee->last_visite : $employee->joinning_date;
                                                        $remaining_vacation = $employee->remaining_vacation ?  $employee->remaining_vacation  : '';
                                                        // Calculate the next visit based on policy type
                                                        if ($emp_policy && $emp_policy->vacation_type == 'Fixed Period') {
                                                            $last = $last_visit ? Carbon::parse($last_visit) : Carbon::parse($employee->joining_date);
                                                            $period = $emp_policy->minimum_vacation_period;
                                                            $next_visit = $last->copy()->addYears($period);
                                                        }
                                                    }

                                                    @endphp
                                                        <tr class="text-center ">
                                                            {{-- <td>{{ $key+1 }}</td> --}}

                                                            <td class="text-left"> {{ $data->salutation.' '.$data->first_name.' '.$data->middle_name.' '.$data->last_name }}</td>
                                                            <td> {{$data->div ? $data->div->name:$data->division}} </td>
                                                            <td> {{date('d/m/Y', strtotime($data->joining_date))}} </td>
                                                            <td> {{$data->basic_salary}} </td>
                                                            <td class="text-dark" data-approv="{{ $data->status }}">
                                                                {{ $data->status == 1 ? 'Approved' : ($data->status == 0 ? 'Waiting Approval' : 'Waiting Approval') }}
                                                            </td>
                                                            </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                            @if ($employees)
                                                <div class="mt-1">
                                                    {{$employees->links()}}
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive" id="employee_table_data" style="min-height: 300px; display:none;">
                                    <table class="table  table-sm table-hover" style="max-width: 100%">
                                        <thead  class="thead-light">
                                            <tr class="text-center" style="height: 40px;">
                                                {{-- <th>NO</th> --}}
                                                <th> Code </th>
                                                <th class="text-left"> Name</th>
                                                <th>DEPARTMENT</th>
                                                {{-- <th>EID</th> --}}
                                                <th>JOINING DATE</th>
                                                <th>PASSPORT</th>
                                                {{-- <th>VISA</th> --}}

                                                {{-- <th>LAST COUNTRY VISIT</th> --}}
                                                {{-- <th>NEXT VISIT</th> --}}
                                                <th> Salary <br> {{ number_format($cal_total_salary, 2) }}</th>
                                                <th class="text-left">STATUS</th>
                                                <th>BUTTON</th>

                                                {{-- <th>Action</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>

                                        @foreach ($employees as $key => $data)
                                            @php
                                            $employee = Employee::where('emp_id', $data->emp_id)->first();
                                            if ($employee) {
                                                // Get the most recent EmployeePolicy based on the effective date
                                                $date = date('Y-m-d');
                                                $emp_policy = policy_helper($data->emp_id,$date);

                                                $last_visit = $employee->last_visite ? $employee->last_visite : $employee->joinning_date;
                                                $remaining_vacation = $employee->remaining_vacation ?  $employee->remaining_vacation  : '';
                                                // Calculate the next visit based on policy type
                                                if ($emp_policy && $emp_policy->vacation_type == 'Fixed Period') {
                                                    $last = $last_visit ? Carbon::parse($last_visit) : Carbon::parse($employee->joining_date);
                                                    $period = $emp_policy->minimum_vacation_period;
                                                    $next_visit = $last->copy()->addYears($period);
                                                }
                                            }

                                            @endphp
                                            <tr class="text-center ">
                                                <td> {{ $data->code}}</td>
                                                <td class="employee text-left" style="width: 20%" data-id="{{ route('employees.show',$data) }}"id="{{$data->id}}">
                                                    {{ $data->full_name }}
                                                </td>
                                                <td>{{$data->div ? $data->div->name:$data->division}}</td>
                                                {{-- <td>{{$data->emirates_id}}</td> --}}

                                                <td>{{$data->joining_date ? date('d/m/Y', strtotime($data->joining_date)) : ''}}</td>
                                                <td> {{ $data->passport_number}}</td>
                                                {{-- <td> {{ $data->visa_number}}</td> --}}
                                                {{-- <td>{{isset($last_visit) ? date('d/m/Y', strtotime($last_visit))  :'N/A'}} </td> --}}
                                                {{-- <td>{{isset($next_visit) ? date('d/m/Y', strtotime($next_visit)) :'N/A'}} </td> --}}
                                                <td> {{$data->basic_salary}} </td>
                                                <td class="text-dark text-left"
                                                    data-approv="{{ $data->status }}">{{ $data->status == 1 ? 'Approved' : 'Awaiting Approval' }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding-top: 2px; padding-bottom: 2px; font-size: 12px; padding-left: 10px; padding-right:14px">
                                                                Actions
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                @if($data->status != 1)
                                                                @if(Auth::user()->hasPermission('HR_Approve'))
                                                                <a class="dropdown-item studentProfileEditemployee " href="{{ route('employees-approve',$data->id) }}"
                                                                    onclick="event.preventDefault(); deleteAlert(this, 'Are Youe Sure To approve It ?','approve')"
                                                                    title="Approve">Approve</a>
                                                                    @endif
                                                                @endif
                                                               @if(Auth::user()->hasPermission('HR_Edit'))
                                                                <a class="dropdown-item studentViewProfile employee-edit"
                                                                    data-id="{{ route('employees.edit',$data->id) }}"id="{{$data->id}}">Edit</a>
                                                               @endif
                                                                <a class="dropdown-item studentProfileEditemployee employee" data-modal="#employee-modal"
                                                                    data-id="{{ route('employees.show',$data) }}"id="{{$data->id}}">View</a>

                                                                {{-- <a class="dropdown-item studentProfileEditemployee employee-salary-certificate"
                                                                data-id="{{ route('employees.salray-cetificate',$data) }}"id="{{$data->id}}">Print Salary Certificate</a> --}}
                                                               @if(Auth::user()->hasPermission('HRPAYROLl_Delete'))
                                                                <a class="dropdown-item studentProfileEditemployee "href="{{route('employees-delete',$data->id)}}"
                                                                    onclick="event.preventDefault(); deleteAlert(this, 'Are youe sure to delete it ?')"
                                                                    title="delete">Delete</a>
                                                                @endif
                                                                {{-- {{-- <a class="dropdown-item studentProfilePrint" id="{{$data->id}}" href="#">Print</a> --}}

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if ($employees)
                                    <div class="mt-1">
                                        {{$employees->links()}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="printArea" class="d-none">

            </div>
        </div>
    </div>
</div>


@include('backend.payroll.employee.modal')



@endsection
    @push('js')

    @include('backend.payroll.employee.ajax')        <!-- summernote css/js -->
    <script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/form-repeater.js"></script>
    {{-- parent --}}
    <script>
        $(document).on('change', '.job_status', function(){
            var job_status = $(this).val();

            if(job_status == 2){
                $('.job_status_div').show();
                $('.leave_date').prop('required', true);
                $('.leave_date_span').show();
            }else{
                $('.job_status_div').hide();
                $('.leave_date').prop('required', false);
                $('.leave_date_span').hide();
            }
        });

        $(document).on('click', '.custome-tabs .nav-link', function (e) {
            e.preventDefault();

            // remove active class only from this tab group
            const tabContainer = $(this).closest('.custome-tabs');
            tabContainer.find('.nav-link').removeClass('active');

            // remove active class from all tab-panes
            $('.custom-tab .tab-pane').removeClass('active show').hide();

            // add active to clicked tab
            $(this).addClass('active');

            // show corresponding tab pane
            const target = $(this).attr('href');
            $(target).addClass('active show').fadeIn(); // or .show()
        });
       function generateBarcode(data) {
            // Create the barcode on a temporary canvas
            JsBarcode("#barcodeCanvas", data, {
                format: "CODE128",
                displayValue: true,
                fontSize: 18,
                lineColor:'#072f64',
                width: 2,
                height: 70
            });

            // Convert the canvas content to a base64 image
            var barcodeImage = document.getElementById('barcodeCanvas').toDataURL("image/png");

            // Set the base64 image as the src of the <img> tag
            document.getElementById('barcodeImage').src = barcodeImage;
        }

        $(document).on('mouseenter', '.datepicker', function () {
            if (!$(this).hasClass('hasDatepicker')) {
                $(this).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-1000:+1000",
                    dateFormat: "dd/mm/yy",
                });
            }
        });

        $(document).ready(function(){
            $('.common-select').select2();

        });
        @if (count($errors) > 0)
            $('#parentProfileAdd').modal('show');
        @endif
        function printFunction(){
            window.print();
        }

        function fatherEmirateImgChange() {
            fatherEmirateImgPreview.src = URL.createObjectURL(event.target.files[0]);
        }
        function passportImgChange() {
            passportImgPreview.src = URL.createObjectURL(event.target.files[0]);
        }
        function qualificationImgChange() {
            qualificationImgPreview.src = URL.createObjectURL(event.target.files[0]);
        }
        function motherEmirateImgChange() {
            motherEmirateImgPreview.src = URL.createObjectURL(event.target.files[0]);
        }
        $(document).on("change", "#edit_fatherEmirateImgChange", function(){
            edit_fatherEmirateImgPreview.src = URL.createObjectURL(event.target.files[0]);
        });
        $(document).on("change", "#edit_motherEmirateImgChange", function(){
            edit_motherEmirateImgPreview.src = URL.createObjectURL(event.target.files[0]);
        });
        $(document).on("change", "#edit_passportImg", function(){
            edit_passportImgPreview.src = URL.createObjectURL(event.target.files[0]);
        });
        $(document).on("change", "#edit_quali_image", function(){
            edit_qualiImgPreview.src = URL.createObjectURL(event.target.files[0]);
        });
        $(document).on("click", ".parentViewProfile", function(e) {
            e.preventDefault();
            var id= $(this).attr('id');
            $.ajax({
                url: "{{URL('employee-view-profile-modal')}}",
                type: "get",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response){
                    document.getElementById("profileViewDetails").innerHTML = response.page;
                    $('#parentViewProfileModal').modal('show')
                }
            });
        });
        var i = 0;
        // add line
        $(document).on("click", '.add-line', function(event) {
            ++i;
            $(".from-body").append('<div class="row mt-1 start"><div class="col-sm-4 changeColStyle"><input type="text" class="form-control inputFieldHeight" name="group_a['+ i +'][post_name]" ></div><div class="col-sm-4 changeColStyle"><input type="file" class="form-control inputFieldHeight" name="group_a['+ i +'][post_quali_image]"></div><div class="col-sm-2 d-flex changeColStyle justify-content-end"><button type="button" class="btn btn-danger formButton mDeleteIcon remove-row" title="Delete"><div class="d-flex align-items-right"><div class="formSaveIcon"><img  src="{{asset('assets/backend/app-assets/icon/delete-icon.png')}}" alt="" srcset=""  width="15"></div><div><span>Delete</span></div></div></button></div></div>');
        });
        //remove line
        $(document).on("click", '.remove-row', function(event) {
            // alert('I am there');
            $(this).parents(".start").remove();
        });

        //Delete others
        $(document).on("click", '.invoice-item-delete', function(event) {
                event.preventDefault();
                // alert(1);
                var that = $(this);
                var urls = that.attr("data_target");
                var _token = $('input[name="_token"]').val();
                // alert(invoice_no);
                $.ajax({
                    url: urls,
                    method: "GET",
                    _token: _token,

                    success: function(response) {
                        // alert("hukka");
                        console.log(response);
                        $(".data").empty().append(response.page);

                    },
                    error: function() {
                        //   alert('no');
                    }
                });
        });

        $(document).on("click", ".parentProfileEdit", function(e) {
            e.preventDefault();
            var url= $(this).attr('href');
            // alert(url);
            $.ajax({
                url: url,
                type: "get",
                data:{
                    _token:'{{ csrf_token() }}',
                },
                success: function(response){
                    document.getElementById("profileEditDetails").innerHTML = response.page;
                    $('#parentEditProfileModal').modal('show');
                    $(".datepicker").datepicker({ dateFormat: "dd/mm/yy" });
                }
            });
        });

        $(document).on("keyup", ".search", function(e) {
            // e.preventDefault();
            var val = $(this).val();
            // if (condition) {

            // }
            $.ajax({
                url: "{{URL('employee-search')}}",
                type: "get",
                data:{
                    _token:'{{ csrf_token() }}',
                    id:val,
                },
                success: function(response){
                    $('.emp_list').empty().append(response.page);
                }
            });
        });


        $(document).on("keyup", "#present_address", function(e) {
                    var value = $(this).val();
                    $("#parmanent_address").val(value);
                });

        $(document).on("keyup", "#em_present_address", function(e) {
                var value = $(this).val();
                $("#em_parmanent_address").val(value);
            });

        $(document).on("keyup", "#r_present_address", function(e) {
                var value = $(this).val();
                $("#r_parmanent_address").val(value);
            });
        $(document).on("keyup", "#name", function(e) {
                var value = $(this).val();
                document.getElementById("name_show").innerHTML= "Name :" +value;
            });
        $(document).on("click", ".parentProfilePrint", function(e) {
            e.preventDefault();
            var id= $(this).attr('id');
            $.ajax({
                url: "{{URL('parent-profile-print')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response) {
                    document.getElementById("profilePrintDetails").innerHTML = response;
                    $('#parentPrintProfileModal').modal('show');
                    setTimeout(printFunction, 500);
                },
                error: function() {
                    alert('Problem Found');
                }
            });
        });
    </script>
        <script>
            // Use the plugin once the DOM has been loaded.
            $(function() {
                // Apply the plugin

                $('#2filter-table').excelTableFilter();

            });

            // show inser modal for insert data
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });




        </script>

            <!-- summernote css/js -->
            <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
            <script type="text/javascript">
            $(document).ready(function() {
                $('.summernote').summernote({
                    height: 100
                });

                // $('#summernote2').summernote({
                //     height: 100
                // });
            });
                // $('#summernote').summernote({
                //     height: 200
                // });
            </script>

<script>

    // get policy data

    function convertToTimeInputFormat(timeString) {
        let [time, period] = timeString.split(' ');
        let [hours, minutes] = time.split(':');

        if (period === "PM" && hours !== "12") {
            hours = parseInt(hours) + 12;
        } else if (period === "AM" && hours === "12") {
            hours = "00";
        }
        return `${String(hours).padStart(2, '0')}:${minutes}`;
    }

    $(document).on('change', '.policy_type,.policy_typeu', function(e) {
        e.preventDefault();
        let value = $(this).val();
        if (value === 'Default') {
            let emp_id = $('.employee_id').val();
            let url = '{{route('custom-policy')}}';
            $.ajax({
                url: url,
                method: 'get',
                data: { emp_id: emp_id },
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {

                    $('[name="air_ticket_eligibility"]').val(response.air_ticket_eligibility).change();
                    $('[name="description"]').val(response.description);
                    $('[name="cash_redeem"]').val(response.cash_redeem);
                    $('[name="vacation_type"]').val(response.vacation_type);
                    $('[name="vacation_paid_or_unpaid"]').val(response.vacation_paid_or_unpaid).change();
                    $('[name="minimum_day_for_ticket_price"]').val(response.minimum_day_for_ticket_price);
                    $('[name="ticket_price_percentage"]').val(response.ticket_price_percentage);
                    $('[name="late_type"]').val(response.late_type).change();
                    $('[name="minimum_day_for_late"]').val(response.minimum_day_for_late);
                    $('[name="minimum_hours_for_late"]').val(response.minimum_hours_for_late);
                    $('[name="salary_loss"]').val(response.salary_loss);

                    $('[name="overtime_rate"]').val(response.overtime_rate);
                    $('[name="min_hours_for_overtime"]').val(response.min_hours_for_overtime);
                    $('[name="late_grace_time"]').val(response.late_grace_time);
                    $('[name="number_of_yearly_vacation"]').val(response.number_of_yearly_vacation);
                    $('[name="maximum_time_for_attendace"]').val(response.maximum_time_for_attendace);

                    // Time fields with conversion
                    $('[name="m_ref_in_time"]').val(convertToTimeInputFormat(response.m_ref_in_time));
                    $('[name="m_ref_out_time"]').val(convertToTimeInputFormat(response.m_ref_out_time));
                    $('[name="e_ref_in_time"]').val(convertToTimeInputFormat(response.e_ref_in_time));
                    $('[name="e_ref_out_time"]').val(convertToTimeInputFormat(response.e_ref_out_time));

                    $('[name="time_zone"]').val(response.time_zone);
                    $('[name="apply_over_time"]').val(response.apply_over_time);

                    let effectDate = response.effect_date;
                    if (effectDate) {
                        let [year, month, day] = effectDate.split('-');
                        effectDate = `${day}/${month}/${year}`;
                    }

                    // $('[name="effect_date"]').val(effectDate).prop('readonly', true);
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                    toaster.error('Error: ' + error);
                }
            });
        } else {
            $('[name="air_ticket_eligibility"]').val('').prop('readonly', false).change();
            $('[name="description"]').val('').prop('readonly', false);
            $('[name="cash_redeem"]').val('').prop('readonly', false);
            $('[name="vacation_type"]').val('').prop('readonly', false);
            $('[name="vacation_paid_or_unpaid"]').val('').prop('readonly', false).change();
            $('[name="minimum_day_for_ticket_price"]').val('').prop('readonly', false);
            $('[name="ticket_price_percentage"]').val('').prop('readonly', false);
            $('[name="late_type"]').val('').prop('readonly', false).change();
            $('[name="minimum_day_for_late"]').val('').prop('readonly', false);
            $('[name="minimum_hours_for_late"]').val('').prop('readonly', false);
            $('[name="salary_loss"]').val('').prop('readonly', false);

            $('[name="overtime_rate"]').val('');
            $('[name="min_hours_for_overtime"]').val('').prop('readonly', false);
            $('[name="late_grace_time"]').val('').prop('readonly', false);
            $('[name="m_ref_in_time"]').val('').prop('readonly', false);
            $('[name="m_ref_out_time"]').val('').prop('readonly', false);
            $('[name="e_ref_in_time"]').val('').prop('readonly', false);
            $('[name="e_ref_out_time"]').val('').prop('readonly', false);
            $('[name="time_zone"]').val('').prop('readonly', false);
            $('[name="number_of_yearly_vacation"]').val('').prop('readonly', false);
            $('[name="maximum_time_for_attendace"]').val('').prop('readonly', false);
            $('[name="apply_over_time"]').val('').prop('readonly', false);
            $('[name="effect_date"]').val('').prop('readonly', false);
            $('[name="overtime_rate"]').val('').prop('readonly', false);
        }
    });
    // form ajax save employee information

    $(document).on('submit', '.employee_send_form',function(e) {
        $('#first_name').removeClass('is-invalid');
        $('#last_name').removeClass('is-invalid');
        $('#nationality').removeClass('is-invalid');
        $('#dob').removeClass('is-invalid');

        e.preventDefault();

        let input_focus = $(this).data('focus');
        let tab_btn_id = $(this).data('id');
        let url = $(this).attr('action');
        let form_data = new FormData(this);

        $.ajax({
            url: url,
            method: 'POST',
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                $(tab_btn_id).click();
                setTimeout(function() {
                    $(input_focus).focus();
                }, 200);
                $('.employee_id').val(response.id);
                $("#employee_table_data").load(location.href + " #employee_table_data");
                if(response.page){
                    $('#other_document_replace').empty().append(response.page);
                }
                toastr.success(response.message);
            },

            error: function(xhr, status, error) {
                var error = xhr.responseJSON.error;
                toastr.error(error, xhr.status);

                $('#first_name').addClass('is-invalid');
                $('#last_name').addClass('is-invalid');
                $('#nationality').addClass('is-invalid');
                $('#dob').addClass('is-invalid');
            }
        });
    });

    //****************** AJAX email-valadition off   ***************************
    $(document).on("change", ".errorr-abcd1fgfg", function(e) {
        e.preventDefault();

        var value = $(this).val();
        var emailInput = $("#email1ffff");
        //alert(value)

        if (value == 3 || value == 4 || value == 5) {
            emailInput.prop("required", false);
            emailInput.removeClass("ajax-error");
            $(".error-disable").prop("disabled", false)
            $(".email_error").text("").css("color", "green").show();

        }else {
            if(emailInput.val()!='')
            {
                emailInput.prop("required", true);
            emailInput.addClass("ajax-error");
            reverse('email1ffff');
            }

        }
    });

    $(document).on('click','.other-document-delete', function(e){
        var id = $(this).attr('id');
        var _token = $('input[name="_token"]').val();
        $.ajax({
            method: "post",
            url: "{{route('other-document-delete')}}",
            data: {
                id:id,
                _token: _token,
            },
            success: function (response) {
                if(response ==1){
                    $('#tr'+id).remove();
                    toastr.success("Document deleted", "Success",{ timeOut: 500 });
                }
            }
        });
    })

    $(document).on('change', '#profile_picture', function(e){
        var file = e.target.files[0];
        if (file) {
            var fileType = file['type'];
            var validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if ($.inArray(fileType, validImageTypes) < 0) {
                toastr.warning('Only image files are allowed!');
                $('#profile_picture').val('');
                $('.profile-picture-box').hide();
            } else {
                $('.profile-picture-box').show();
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.preview').attr('src', e.target.result).show();
                    $(".bigPreviewImg").attr("src", e.target.result);
                }
                reader.readAsDataURL(file);
            }
        }

    })

    $(document).on('mouseenter', '.preview', function () {
        $(this).siblings('.bigPreview').fadeIn(200);
    });

    $(document).on('mouseleave', '.preview', function () {
        $(this).siblings('.bigPreview').fadeOut(200);
    });

    //************************** AJAX VALIDATION ***********************************

    function getFocusableInputs() {
        return $('input, select, textarea').filter(':visible:enabled');
    }

    function moveToNextTab() {
        const $currentTab = $('.nav-tabs .nav-link.active');
        const $nextTab = $currentTab.closest('li').next().find('.nav-link');

        if ($nextTab.length > 0) {
            $nextTab.tab('show');

            // Focus the first input in the next tab
            setTimeout(() => {
                const nextTabPane = $($nextTab.attr('href'));
                const firstInput = nextTabPane.find('input, select, textarea').filter(':visible:enabled').first();
                if (firstInput.length) {
                    firstInput.focus();
                    if (firstInput.is('select')) {
                        firstInput.click(); // open dropdown if it's a select
                    }
                }
            }, 150);
        }
    }

    $(document).on('focus', 'select', function () {
        // A tiny delay helps the browser finish the focus event before opening
        const sel = this;
        setTimeout(() => sel.click(), 0);
    });

    $(document).on('keydown', 'input, select, textarea', function (e) {
        const key = e.which;
        const inputs = getFocusableInputs();
        const currentIndex = inputs.index(this);
        const isLastInput = currentIndex === inputs.length - 1;
        const $this = $(this);
        const isSelect    = $this.is('select');

        const keysHandled = [13, 37, 38, 39, 40];

        if (!keysHandled.includes(key)) return;

        if(
            (!isSelect && keysHandled.includes(key)) ||
            (isSelect && [37, 38, 39, 40].includes(key))
        ){
            e.preventDefault();
        } else {
            return;
        }

        if (key === 13) {
            if (isLastInput) {
                // Submit the form if last input and Enter
                $this.closest('form')[0].requestSubmit();
            } else {
                // If it's a select, open it
                if ($this.is('select')) {
                    $this.focus().click();
                    return;
                }
                inputs.eq(currentIndex + 1).focus();
            }
        }

        if (key === 39 || key === 40) {
            if (isLastInput) {
                // Focus the Next button
                $('.keyboard-control-btn').focus();
            } else {
                inputs.eq(currentIndex + 1).focus();
            }
        }

        if (key === 37 || key === 38) {
            if (currentIndex > 0) {
                inputs.eq(currentIndex - 1).focus();
            }
        }
    });

    $(document).on('click keydown', '.keyboard-control-btn', function(e) {
        var tab = $(this).data('tab');
        var focus = $(this).data('focus');
        var focus1 = $(this).data('focus1');
        if ( e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
            setTimeout(function() {
                $(focus).focus();
            }, 200);
        }else if(e.type === 'click'){
            $(tab).click();
        }

        setTimeout(function() {
            $(focus1).focus();
        }, 200);
    })

    $(document).on('keydown', function(e) {
        const activeTab = $('.tab-pane.active.show');
        const form = activeTab.find('form')[0];

        // Ctrl + S → Save (submit current tab form)
        if (e.ctrlKey && e.key.toLowerCase() === 's') {
            e.preventDefault();

            if (form) {
                form.requestSubmit(); // safe and modern
            }
        }

         // Alt + → → Go to next tab
        else if (e.altKey && e.key === 'ArrowRight') {
            e.preventDefault();

            const nextTabId = activeTab.data('next');
            const focusSelector = activeTab.data('focus1');

            if (nextTabId) {
                $(nextTabId).click();
                setTimeout(() => {
                    $(focusSelector).focus();
                }, 200);
            }
        }

        // ✅ Alt + ← → Go to previous tab
        else if (e.altKey && e.key === 'ArrowLeft') {
            e.preventDefault();

            const prevTabId = activeTab.data('prev');
            const focusSelector = activeTab.data('focus');

            if (prevTabId) {
                $(prevTabId).click();
                setTimeout(() => {
                    $(focusSelector).focus();
                }, 200);
            }
        }
    });

    $(document).on('click', '.add-row', function(){
        const table = $(this).closest('table');
        const tableBody = table.find('.documentTableBody');

        const newRow = `<tr>
            <td>
                <input type="file" class="form-control inputFieldHeight file-input" name="document[]" required>
            </td>

            <td>
                <select name="document_type[]" id="document_type" required class="form-control inputFieldHeight">
                    <option value=""> Select </option>
                    <option value="id_card"> ID Card </option>
                    <option value="visa"> Visa Image </option>
                    <option value="other"> Other </option>
                </select>
            </td>


            <td>
                <img src="" class="preview" alt="">
            </td>
            <td class="text-right">
                <button type="button" class="delete-button"
                        title="Delete"
                        style="padding: 2px 6px; font-size: 14px; background-color: #dc3545; color: white; border: none; border-radius: 3px;">
                    <i class="bx bx-trash"></i>
                </button>
            </td>
        </tr>
        `;

        tableBody.append(newRow);
    });

    $(document).on('click','.delete-button',function(){
        const row = $(this).closest('tr');
        row.remove();
    });

    $(document).on('change', '.file-input', function (e) {
        const file = e.target.files[0];
        const previewCell = $(this).closest('tr').find('.preview');

        if (!file) return;

        const fileType = file.type;
        const fileName = file.name;
        const extension = fileName.split('.').pop().toLowerCase();

        if (fileType.startsWith('image/')) {
            // It's an image file
            const reader = new FileReader();
            reader.onload = function (e) {
                previewCell.replaceWith(`<img src="${e.target.result}" class="preview" alt="Preview" style="max-height: 50px;">`);
            };
            reader.readAsDataURL(file);
        } else {
            previewCell.replaceWith(`<span class="preview" style="font-size: 14px;">.${extension}</span>`);
        }
    });

    $(document).on('click', '#payroll-button', function () {
        var month = $('#payslip_month').val();
        var year = $('#payslip_year').val();
        var employee_id = $('#paysip_user_id').val();

        $.ajax({
            url: "{{ route('get.payroll') }}",
            method: 'GET',
            data: {
                month: month,
                year: year,
                employee_id: employee_id
            },
            beforeSend: function () {
                $('#payroll-button').prop('disabled', true).text('Searching...');
            },
            success: function (response) {
                $('#payroll-table-body').html(response.page);
            },
            complete: function () {
                $('#payroll-button').prop('disabled', false).text('Search');
            },
            error: function () {
                alert('Something went wrong!');
            }
        });
    });

    $(document).on('change', '.employee_code', function () {
        let code = $(this).val();
        let id = $(this).data('employee');

        if (code.length > 0) {
            $.ajax({
                url: '{{ route("employee.check-code") }}',
                type: 'GET',
                data: { code: code, id: id },
                success: function (response) {
                    if (response.exists) {
                        $('.code_message').text('Code Used');
                        $('.employee_code').addClass('is-invalid');
                        $('.save-btn').prop('disabled',true);
                    } else {
                        $('.code_message').text('');
                        $('.employee_code').removeClass('is-invalid');
                        $('.save-btn').prop('disabled',false);
                    }
                }
            });
        } else {
            $('.save-btn').prop('disabled',false);
            $('.code_message').text('');
            $('.employee_code').removeClass('is-invalid');
        }
    });

    // office in out validation

    $(document).on('change', '.morning_in_time, .morning_out_time, .evening_in_time, .evening_out_time', function () {
        office_in_out_validation(this);
    });

    function office_in_out_validation(node){
        var row = $(node).closest('.row');
        var morning_enter_time = row.find('.morning_in_time').val();
        var morning_out_time = row.find('.morning_out_time').val();
        var evening_enter_time = row.find('.evening_in_time').val();
        var evening_out_time = row.find('.evening_out_time').val();
        var error = '';

        $('.morning_in_time').removeClass('is-invalid');
        $('.morning_out_time').removeClass('is-invalid');
        $('.evening_in_time').removeClass('is-invalid');
        $('.evening_out_time').removeClass('is-invalid');

        if(morning_enter_time > morning_out_time){
            error = 'Morning In time must be earlier than Out time.'
            $('.morning_in_time').addClass('is-invalid');
            $('.morning_out_time').val('').addClass('is-invalid');
        }

        if(evening_enter_time > evening_out_time){
            error = 'Evening In time must be earlier than Out time.'
            $('.evening_in_time').addClass('is-invalid');
            $('.evening_out_time').val('').addClass('is-invalid');
        }

        if (error) {
            toastr.warning(error);
        }
    }
</script>
@endpush

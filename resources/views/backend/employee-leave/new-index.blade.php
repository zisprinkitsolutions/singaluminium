<style>
    .commonSelect2Style span{
        width: 100% !important;
    }
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b{
        display: none;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b{
        display: none;
    }
</style>
@extends('layouts.backend.app')
@section('content')
@include('backend.tab-file.style')
<style>
    .bg-secondary {
        background-color: #34465b !important;
        border-radius: 40px;
        color:white  !important;
        padding: 2px 5px 2px 5px !important;
    }
    a.bg-secondary:hover, a.bg-secondary:focus,
    button.bg-secondary:hover,
    button.bg-secondary:focus {
        background-color: #475f7b30 !important;
        color:black!important;
    }
    tr:nth-child(even) {
        background-color: #c8d6e357;
    }
    a.text-dark:hover, a.text-dark:focus {
        color: #ffffff !important;
    }
    .btn-outline-secondary {
        border-radius: 40px;
        padding: 0.2px 9px 0.2px 9px !important;
    }
    .table .thead-light th {
        color:#F2F4F4 ;
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
                <a href="{{route("new-employee-leave")}}" class="nav-item nav-link active" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
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

            <div class="tab-content bg-white px-4 py-2 active">
                <div class="tab-pane active">
                    <div class="d-flex align-items-center justify-content-between" id="nav-tab" role="tablist">
                        {{-- <div class="d-flex align-items-center gap-2 pl-2">
                            <a href="{{route('new-employee-attendance')}}" class="btn btn-outline-secondary nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false" id="parentProfileTab" style="margin-right:15px;">
                                <div>Employees Attendance</div>
                            </a>
                            <a href="{{route("new-employee-leave")}}" class="btn bg-secondary nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
                                <div>Employees Leave</div>
                            </a>
                        </div>

                        <button type="button" class="btn btn-primary btn_create formButton mr-2" title="Add" data-toggle="modal" data-target="#mNewEmployeeLeaveAdd">
                            <div class="d-flex">
                                <div><span>New Leave</span></div>
                            </div>
                        </button> --}}
                    </div>

                    <div class="tab-content bg-white">
                        <div class="tab-pane active">
                            <div class="content-body">
                                <div class="row" id="table-bordered" style="width:975px !important">
                                    <div class="col-8">
                                        <div class="cardStyleChange">
                                            <div class="card-body mx-0 px-0 py-1" style="padding: 0.5rem 0!important;">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <form action="" method="GET">
                                                            <input type="text" id="search" name="search" class="form-control inputFieldHeight " @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="Search Employee Name">
                                                        </form>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 text-right">
                                        <button type="button" class="btn btn-primary btn_create formButton mt-2" title="Add" data-toggle="modal" data-target="#mNewEmployeeLeaveAdd">
                                            <div class="d-flex">
                                                <div><span>New Leave</span></div>
                                            </div>
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <div class="table-responsive" style="min-height: 200px;">
                                            <table class="table mb-0 table-sm table-hover" style="width:975px !important">
                                                <thead  class="thead-light">
                                                    <tr class="text-center" style="height: 40px;">
                                                        <th>ID</th>
                                                        <th>Employee Name</th>
                                                        <th>From date</th>
                                                        <th>To date</th>
                                                        <th>Days leave</th>
                                                        <th>Leave Reason</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($employee_leaves as $employee_leave)
                                                        <tr class="text-center border-bottom trFontSize">
                                                            <td>{{$employee_leave->employee->emp_id}}</td>
                                                            <td>{{$employee_leave->employee->first_name}}</td>
                                                            <td>{{date('d/m/Y',strtotime($employee_leave->from_date))}}</td>
                                                            <td>{{date('d/m/Y',strtotime($employee_leave->to_date))}}</td>
                                                            <td>{{$employee_leave->days_leave}} days</td>
                                                            <td>{{$employee_leave->leave_reason}}</td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <div class="dropdown">
                                                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding-top: 2px; padding-bottom: 2px; font-size: 12px; padding-left: 10px;">
                                                                            Actions
                                                                        </button>
                                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                            <a class="dropdown-item employeeLeaveEdit" id="{{$employee_leave->id}}" href="#">Edit</a>
                                                                            <a class="dropdown-item leaveView" href="#"  id="{{$employee_leave->id}}">View</a>
                                                                            {{-- @if ($employee_leave->scan_copy == null)
                                                                            <a href="#" id="{{$employee_leave->id}}" class="dropdown-item employeeLeaveUploadScanCopy">Upload Scan Copy</a>
                                                                            @else
                                                                            <a href="#" id="{{$employee_leave->id}}" class="dropdown-item employeeLeaveDownloadScanCopy" >Download Scan Copy</a>
                                                                            <a href="{{ asset('storage/upload/employee-scan-copy/'.$employee_leave->scan_copy)}}" class="dropdown-item" target="_blank">Download Scan Copy</a>
                                                                            @endif
                                                                            <a href="#" id="{{$employee_leave->id}}" class="dropdown-item employeeLeavePrint">Print</a>--}}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            {{ $employee_leaves->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="mNewEmployeeLeaveAdd" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="width: 60%;">
      <div class="modal-content">
            <section class="print-hideen border-bottom" style="padding: 10px;background-color: #34465b;">
                <div class="row">
                    <div class="col-6 pl-2">
                        <h5 style="font-family:Cambria;font-size: 2.3rem;color:#fff;"><b>Employee Leave</b> </h5>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-row-reverse">
                            <div class="mIconStyleChange"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
                        </div>
                    </div>
                </div>
            </section>
        {{-- @include('backend.tab-file.modal-header-info') --}}
        @include('backend.employee-leave.new-create-modal')
        @include('backend.tab-file.modal-footer-info')
      </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="employeeLeavePrintModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div id="employeeLeavePrintShow">

        </div>
      </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="employeeLeaveditModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="width: 60%;">
      <div class="modal-content">
        <div id="employeeLeaveEditModalShow">

        </div>
      </div>
    </div>
</div>
{{-- view pop-up  --}}
<div class="modal fade bd-example-modal-lg" id="leaveViewModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document"  style="width: 60%;">
      <div class="modal-content">
        <div id="leaveViewDetails">

        </div>
      </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="employeeLeavePrintModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div id="employeeLeavePrintShow">

        </div>
      </div>
    </div>
</div>
@endsection
@push('js')
<script>
    function printFunction(){
        window.print();
    }
    $(document).on("click", ".employeeLeavePrint", function(e) {
        e.preventDefault();
        var id= $(this).attr('id');
        $.ajax({
            url: "{{URL('employee-leave-print-modal')}}",
            type: "post",
            cache: false,
            data:{
                _token:'{{ csrf_token() }}',
                id:id,
            },
            success: function(response){
                document.getElementById("employeeLeavePrintShow").innerHTML = response;
                $('#employeeLeavePrintModal').modal('show');
                setTimeout(printFunction, 500);
            }
        });
    });
//  edit pop-up
    $(document).on("click", ".employeeLeaveEdit", function(e) {
        e.preventDefault();
        var id= $(this).attr('id');
		$.ajax({
			url: "{{URL('employee-leave-edit-modal')}}",
			type: "post",
			cache: false,
			data:{
				_token:'{{ csrf_token() }}',
                id:id,
			},
			success: function(response){
                document.getElementById("employeeLeaveEditModalShow").innerHTML = response;
                $('#employeeLeaveditModal').modal('show')
                $(".datepicker").datepicker({ dateFormat: "dd/mm/yy" });
			}
		});
	});


    //view modal po-up
    $(document).on("click", ".leaveView", function(e) {
            e.preventDefault();
            var id= $(this).attr('id');
            $.ajax({
                url: "{{URL('employee-view-leave-modal')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response){
                    document.getElementById("leaveViewDetails").innerHTML = response;
                    $('#leaveViewModal').modal('show')
                }
            });
    });


    $(document).on("click", ".employeeLeaveUploadScanCopy", function(e) {
        e.preventDefault();
        var id= $(this).attr('id');
        $.ajax({
            url: "{{URL('employee-leave-upload-scan-copy-modal')}}",
            type: "post",
            cache: false,
            data:{
                _token:'{{ csrf_token() }}',
                id:id,
            },
            success: function(response){
                document.getElementById("employeeLeavePrintShow").innerHTML = response;
                $('#employeeLeavePrintModal').modal('show')
            }
        });
    });
</script>
<script>
    // employee fetch and set
    function employee(){
        var employee_id = document.getElementById("select_id").value;
        document.getElementById("employee_id").value = employee_id;
    }
</script>

<script>
    $(document).on("change", ".todate_calculation1", function(e) {
        var dateStr11 = $(".form_date1").val();
        var dateStr21 = $(".form_date2").val();

        // Parse the date strings into Date objects
        var parts11 = dateStr11.split('/');
        var parts21 = dateStr21.split('/');
        var date11 = new Date(parts11[2], parts11[1] - 1, parts11[0]);
        var date21 = new Date(parts21[2], parts21[1] - 1, parts21[0]);

        // Calculate the time difference
        var timeDiff1 = date21 - date11;

        // Convert the time difference to days
        var daysDiff1 = Math.floor(timeDiff1 / (1000 * 60 * 60 * 24));
        console.log(daysDiff1);
        // Display the result
        $(".days_leave1").val(daysDiff1+1);
    });
</script>


<script>
    $(document).on("change", ".todate_calculation11", function(e) {
        var dateStr11 = $(".form_date11").val();
        var dateStr21 = $(".form_date22").val();

        // Parse the date strings into Date objects
        var parts11 = dateStr11.split('/');
        var parts21 = dateStr21.split('/');
        var date11 = new Date(parts11[2], parts11[1] - 1, parts11[0]);
        var date21 = new Date(parts21[2], parts21[1] - 1, parts21[0]);

        // Calculate the time difference
        var timeDiff1 = date21 - date11;

        // Convert the time difference to days
        var daysDiff1 = Math.floor(timeDiff1 / (1000 * 60 * 60 * 24));
        console.log(daysDiff1);
        // Display the result
        $(".days_leave11").val(daysDiff1+1);
    });
</script>
@endpush

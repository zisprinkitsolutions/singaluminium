
@extends('layouts.backend.app')

@push('css')
<!-- summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@endpush

@section('content')
@include('layouts.backend.partial.style')
<style>
    .table .thead-light th {
        color:#F2F4F4 ;
        background-color: #34465b;
        border-color: #DFE3E7;
    }

    tr:nth-child(even) {
        background-color: #c8d6e357;
    }

    .tab-content1 {
        padding: 15px;
        border-radius: 5px;
    }

    .nav-tabs1 .nav-link {
        color: #000;
    }

    .nav-tabs1 .nav-link.active{
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
    }

    .nav.nav-tabs1 ~ .tab-content1 {
        border: 1px solid #dfe3e700;
    }

    .tab-content1 {
        background: #00000000 !important;
    }

    .nav.nav-tabs1 .nav-item .nav-link.active, .nav.nav-pills .nav-item .nav-link.active{
        box-shadow: 0 2px 4px 0 rgb(0 61 177 / 0%);
    }

    .table-responsive .nav .nav-link{
        background-color: #55595e;
        color: #fff;
    }

    .table-responsive .nav .nav-link.active{
        color: #313131;
    }

    .nav.nav-tabs1 {
        border-bottom: 1px solid #82868c;
    }

    .modal .modal-content .modal-header .close{
        padding: 7px;
        border-radius: 4px;
    }

    .nav li{
        padding: 0 !important;
        background: transparent;
        margin-right:15px !important;
    }

    .nav-link{
        margin-bottom: 10px !important;
    }
    .modal-header .close{
        display: flex;
        align-items: center;
        justify-content: center;
        background: red !important;
        color: white;
    }

    .modal-header .close span{
        font-size: 22px !important;
    }
</style>

<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('backend.payroll.tab-sub-tab._basic_info_header',['activeMenu' => 'leave_application'])
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active">
                    <div class="tab-content bg-white">
                        <div id="studentProfileList" class="tab-pane active px-2 pt-1" style="max-width: 100%;">
                            {{-- @include('backend.payroll.tab-sub-tab._policies_submenu',['activeMenu' => 'leave-policy']) --}}

                            <div class="row mt-1" id="table-bordered">
                                <div class="col-12">
                                    <div class="cardStyleChange">
                                        <div class="">
                                            <div class="row pr-1">
                                                <div class="col-md-12">
                                                    <div class="row" style="margin-left:3px;">
                                                        <div class="col-2" style="padding: 0;">
                                                            <button type="button" class="btn btn-primary btn_create formButton employee_modal_open"
                                                            data-modal="#employee-modal" title="Add" data-toggle="modal" data-target="#studentProfileAdd">
                                                                <div class="d-flex">
                                                                    <div class="formSaveIcon">
                                                                        <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" width="25">
                                                                    </div>
                                                                    <div><span> Create  </span></div>
                                                                </div>
                                                            </button>
                                                        </div>

                                                        <div class="col-3">
                                                            <form action="" method="GET"  class="d-flex row">
                                                                <div class="row form-group col-10 col-lg-11" style="padding-left:7px;">
                                                                    <input type="text"
                                                                        class="inputFieldHeight form-control " name="search" value="{{$search}}"
                                                                        placeholder="SEARCH BY LEAVE NO"  autocomplete="off">
                                                                </div>

                                                                <div class="col-2 col-lg-1 mr-1">
                                                                    <button type="submit"style="padding: 5px 0px;" class="btn mSearchingBotton inputFieldHeight mb-2 formButton"
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

                                                        <div class="col-7">
                                                            <form action=""  id="form-date-range" method="GET" class="d-flex row">
                                                                <input type="hidden" name="type" value="{{$type}}" class="type" id="">

                                                                <div class=" form-group col-md-3" style="padding-left:7px;">
                                                                    <input type="text"
                                                                    class="inputFieldHeight form-control datepicker date-value"
                                                                    name="form"
                                                                    value="{{ date('d/m/Y', strtotime($form)) }}"
                                                                    placeholder="DD/MM/YYYY"
                                                                    autocomplete="off">
                                                                </div>

                                                                <div class=" form-group col-md-3" style="padding-left:7px;">
                                                                    <input type="text"
                                                                    class="inputFieldHeight form-control datepicker date-value"
                                                                    name="to"
                                                                    value="{{ date('d/m/Y', strtotime($to)) }}"
                                                                    placeholder="DD/MM/YYYY"

                                                                    autocomplete="off">

                                                                </div>

                                                                <div class="col-2">
                                                                    <button type="submit" style="padding: 5px 0px;" class="btn mSearchingBotton inputFieldHeight mb-2 formButton"
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
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="table-responsive">
                                                    <!-- Tabs for Annual Leave and Other Leave -->
                                                    <ul class="nav nav-tabs nav-tabs1" id="leaveTab" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="unapprove-list-tab" data-toggle="tab" href="#unapprove-list" role="tab" aria-controls="unapprove-list" aria-selected="true"> Unapprove List </a>
                                                        </li>

                                                        <li class="nav-item">
                                                            <a class="nav-link" id="approve-list-tab" data-toggle="tab" href="#approve-list" role="tab" aria-controls="approve-list" aria-selected="false"> Approve List </a>
                                                        </li>
                                                    </ul>

                                                    <div class="tab-content tab-content1" id="leaveTabContent">
                                                        <!-- Annual Leave Table -->
                                                        <div class="tab-pane fade show active " id="unapprove-list" role="tabpanel" aria-labelledby="unapprove-list-tab">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-sm text-center" id="unapprove-list-table">
                                                                    <thead style="background: #475f7b">
                                                                        <tr>
                                                                            <th class="text-white">SI No</th>
                                                                            <th class="text-white text-left">Employee</th>
                                                                            <th class="text-white">Leave NO</th>
                                                                            <th class="text-white">From</th>
                                                                            <th class="text-white">To</th>
                                                                            <th class="text-white">Total Days</th>
                                                                            <th class="text-white">Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($employee_aplication as $data)
                                                                            <tr class="employee-edit" style="cursor:pointer" data-id="{{ route('employee-leave-application.edit',$data->id) }}"id="{{$data->id}}">
                                                                                <td>{{ $loop->iteration }}</td>
                                                                                <td class="text-left">{{ $data->emp ? $data->emp->full_name:'' }}</td>
                                                                                <td>{{ $data->leave_no }}</td>
                                                                                <td>{{ date('d/m/Y', strtotime($data->start_date ))}}</td>
                                                                                <td>{{ date('d/m/Y', strtotime($data->end_date ))}}</td>
                                                                                <td class="text-center">{{ $data->leave_day }}</td>
                                                                                <td>{{ $data->status == 0 ? ($data->status == 2 ?  'Rejected' : 'Unapproved') : ($data->status == 2 ?  'Rejected' : 'Unapproved') }}</td>

                                                                                <!-- Other relevant fields -->
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <!-- Other Leave Table -->
                                                        <div class="tab-pane fade " id="approve-list" role="tabpanel" aria-labelledby="approve-list-tab">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped table-sm text-center" id="approve-list-table">
                                                                    <thead style="background: #475f7b">
                                                                        <tr>
                                                                            <th class="text-white">SI No</th>
                                                                            <th class="text-white text-left">Employee</th>
                                                                            <th class="text-white">Leave NO</th>
                                                                            <th class="text-white">Leave Date From</th>
                                                                            <th class="text-white">Leave Date To</th>
                                                                            <th class="text-white">Leave Day Numbers</th>
                                                                            <th class="text-white">Status</th>

                                                                            <!-- Other relevant fields -->
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($employee_aplication_approve as $data)
                                                                            <tr class="employee-edit " style="cursor:pointer" data-id="{{ route('employee-leave-application.edit',$data->id) }}"id="{{$data->id}}">
                                                                                <td>{{ $loop->iteration }}</td>
                                                                                <td class="text-left">{{ $data->emp ? $data->emp->full_name:'' }}</td>
                                                                                <td>{{ $data->leave_no }}</td>
                                                                                <td>{{ date('d/m/Y', strtotime($data->start_date ))}}</td>
                                                                                <td>{{ date('d/m/Y', strtotime($data->end_data ))}}</td>
                                                                                <td>{{ $data->leave_day }}</td>
                                                                                <td>{{ $data->status == 1 ? 'Approved' : 'Rejected' }}</td>

                                                                                <!-- Other relevant fields -->
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
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
                </div>
            </div>
        </div>
    </div>
</div>

@include('backend.payroll.employee-leave-application.modal')

@endsection

@push('js')


<script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/form-repeater.js"></script>
@include('backend.payroll.employee-leave-application.ajax')

{{-- parent --}}
<script>
    @if (count($errors) > 0)
        $('#parentProfileAdd').modal('show');
    @endif

    function printFunction(){
        window.print();
    }

    $(document).ready(function () {
        $('.datepicker_cus').each(function () {
            const minDate = $(this).data('min-date');
            const maxDate = $(this).data('max-date');

            if (!minDate || !maxDate) {
                // Disable the input field if minDate or maxDate is missing
                $(this).prop('disabled', true);
            } else {
                // Initialize the datepicker with minDate and maxDate
                $(this).datepicker({
                    dateFormat: 'dd/mm/yy', // Customize as needed
                    minDate: new Date(minDate), // Convert to Date object
                    maxDate: new Date(maxDate) // Convert to Date object
                });
            }
        });
    });

</script>
    <script>
        $(function() {
            $('#2filter-table').excelTableFilter();

        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @endpush

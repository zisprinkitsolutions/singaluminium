
@extends('layouts.backend.app')
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
        background: #475f7b !important
    }
    .nav-tabs1 .nav-link, .nav-pills .nav-link {
        background-color: #82868c;
        color: #ffffff !important;
    }
    .nav.nav-tabs1 {
        border-bottom: 1px solid #82868c;
    }
    .modal .modal-content .modal-header .close {
        padding: 7px;

        border-radius: 4px;
    }
    .pay-button {

        padding: 7px 20px;
        background: #2a8a8e;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;

    }
    .pay-button:hover {

        padding: 7px 20px;
        background: #2a8a8e;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;

        }
    .pay-button-all {
        padding: 3px 5px;
        background: #2a8a8e;
        color: #fff;
        border-radius: 5px;
        cursor: pointer;
    }

    .small-device th,
    .small-device tr{
        font-size: 10px !important;
        text-align: center;
    }
</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('backend.payroll.tab-sub-tab._basic_info_header',['activeMenu' => 'salary-info'])
            <div class="tab-content bg-white">
                <div id="studentProfileList" class="tab-pane active px-2 pt-1" style="max-width: 100%;">
                    @include('backend.payroll.tab-sub-tab._salary_submenu',['activeMenu' => 'salary'])
                    <div class="cardStyleChange">
                        <div class="row">
                            {{-- <div class="col-md-2">
                                <form action="" method="GET"  class="d-flex row">
                                    <div class="row form-group col-md-11" style="padding-left:7px;">
                                        <input type="text"
                                            class="inputFieldHeight form-control " name="search" value="{{$search}}"
                                            placeholder="SEARCH BY LEAVE NO"  autocomplete="off">
                                    </div>
                                    <div class="col-md-1 mr-1">
                                        <button type="submit"style="padding: 5px 0px;" class="btn mSearchingBotton mb-2 formButton"
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
                            </div> --}}

                            <div class="col-9 col-lg-10">
                                <form action="" id="form-date-range" method="GET" class="d-flex row">
                                    <div class="form-group col-md-3" style="padding-left:7px;">
                                        <input type="month"
                                        class="inputFieldHeight form-control  date-value" id="monthInput"
                                        name="month"
                                        value="{{ $month_year }}"
                                        placeholder="DD/MM/YYYY"
                                        autocomplete="off">
                                    </div>

                                    <div class="col-md-2">
                                        <button type="submit" style="padding: 3px 10px;" class="btn mSearchingBotton mb-2 formButton"
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

                        <div class="table-responsive small-device d-lg-none" style="margin-left: -8px;">
                            <table class="table table-bordered table-sm employee_change" id="2filter-table">
                                <thead class="thead-light">
                                    <th class="text-left">Name</th>
                                    <th>Basic Salary</th>
                                    <th>Over Time</th>
                                    <th>Late Time</th>
                                    <th>Absence</th>
                                    <th>Total Salary</th>
                                </thead>
                                <tbody>
                                    @foreach ($salarys as $data)
                                    </tr>
                                        <td class="employee" data-modal="#employee-modal" data-id="{{ route('salary-process.edit', $data['employee_id']) }}" style="width: 20%">
                                            {{ $data['employee_name'] }}
                                        </td>

                                        <td>{{ number_format($data['basic_salary'], 2) }}</td>
                                        <td>({{ number_format($data['overtime_amount'], 2) }}) {{ $data['total_overtime'] }} H</td>
                                        <td>({{ number_format($data['late_amount'], 2) }}) {{ $data['total_late_time'] }} H</td>
                                        <td>({{ number_format($data['total_absen_penalty'], 2) }}) {{ number_format($data['total_absen'], 0) }} D</td>
                                        <td>{{ number_format($data['amount'], 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive d-none d-lg-block" style="margin-left: -8px;">
                            <table class="table table-bordered table-sm employee_change" id="2filter-table">
                                <thead class="thead-light">
                                    <tr class="text-center" style="height: 40px;">
                                        <th class="text-left">
                                          Name
                                        </th>
                                        <th> Basic </th>
                                        <th> Over Time </th>
                                        <th> Late Time </th>
                                        <th> Absence </th>
                                        <th> Current Salary </th>
                                        <th> Total Salary </th>
                                        <th style="min-withd:170px;"> Action <span class="pay-button-all" data-type="all" id="0">All Slip</span></th>
                                    </tr>
                                </thead>
                                <tbody class="t-body">
                                    @foreach ($salarys as $data)
                                    <tr class="text-center" style="border-bottom: 1px solid #dfe3e7">

                                        <td class="employee text-left" data-modal="#employee-modal" data-id="{{ route('salary-process.edit', $data['employee_id']) }}" style="width: 20%">
                                             {{ $data['employee_name'] }}
                                        </td>
                                        <td>{{ number_format($data['basic_salary'], 2) }}</td>
                                        <td>({{ number_format($data['overtime_amount'], 2) }}) {{ $data['total_overtime'] }} H</td>
                                        <td>({{ number_format($data['late_amount'], 2) }}) {{ $data['total_late_time'] }} H</td>
                                        <td>({{ number_format($data['total_absen_penalty'], 2) }}) {{ number_format($data['total_absen'], 0) }} D</td>

                                        <td>{{ number_format($data['basic_salary_current_day'], 2) }}</td>

                                        <td>{{ number_format($data['amount'], 2) }}</td>
                                        <td>
                                            <button class="btn  btn-sm me-1 pay-button" id="{{$data['emp_id']}}" data-type="single">View Slip</button>
                                            {{-- <button class="btn btn-success btn-sm pay-button" id="{{$data['emp_id']}}" data-type="single">Pay Slip</button> --}}
                                        </td>

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
@endsection

@push('js')

<script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/form-repeater.js"></script>
<script>
    $(document).ready(function() {
        var currentMonth = new Date().toISOString().slice(0, 7);
        $('#monthInput').attr('max', currentMonth);
    });

    $(document).on("click", ".pay-button,.pay-button-all", function(e) {
    e.preventDefault();
    var id = $(this).attr('id');
    var type = $(this).data('type');
    var month = $('#monthInput').val();

    $.ajax({
        url: "{{ route('pay-salary-sheet') }}",
        method: 'post',
        data: {id: id, type: type,month:month},
        success: function(res) {
            var print_content = res.page;

            // Create an iframe for printing
            var iframe = document.createElement('iframe');
            iframe.style.position = "absolute";
            iframe.style.width = "0px";
            iframe.style.height = "0px";
            iframe.style.border = "none";

            document.body.appendChild(iframe);

            // Write content to the iframe
            var doc = iframe.contentWindow || iframe.contentDocument;
            doc.document.open();
            doc.document.write(print_content);
            doc.document.close();

            // Add a delay to ensure content is fully loaded before printing
            setTimeout(function() {
                doc.focus();
                doc.print();

                // Remove the iframe after printing
                document.body.removeChild(iframe);
            }, 500); // Adjust delay as needed
        },
        error: function(err) {
            let error = err.responseJSON;
            $.each(error.errors, function(index, value) {
                toastr.error(value);
            });
        }
    });
});

</script>
@endpush

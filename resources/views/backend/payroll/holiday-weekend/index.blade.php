
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
    .modal .modal-content .modal-header .close{
        padding: 7px;
        border-radius: 4px;
    }
</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('backend.payroll.tab-sub-tab._basic_info_header', ['activeMenu' => 'attendance'])
            <div class="tab-content bg-white">
                <div id="studentProfileList" class="tab-pane active pt-1" style="max-width:100%">
                    @include('backend.payroll.tab-sub-tab.attendance_submenu',['activeMenu' => 'holiday-weekend'])
                    <div class="row mt-1" id="table-bordered">
                        <div class="col-12">
                            <div class="cardStyleChange">
                                <div class="">
                                    <div class="row pr-1">
                                        <div class="col-md-12">
                                            <div class="row" style="margin-left: 30px;">
                                                <div class="col-md-4">
                                                    <form action="" method="GET"  class="d-flex row">
                                                        <div class="row form-group col-8 col-lg-10" style="padding-left:7px;">
                                                            <input type="text"
                                                                class="inputFieldHeight form-control " name="search" value="{{$search}}"
                                                                placeholder="SEARCH BY EMPLOYEE NAME"  autocomplete="off">
                                                        </div>
                                                        <div class="col-4 col-lg-2 mr-1">
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
                                                </div>
                                                <div class="col-md-4">
                                                    <form action=""  id="form-date-range" method="GET" class="d-flex row">
                                                        <div class="form-group col-8 col-lg-10" style="padding-left:7px;">
                                                            <input type="text"
                                                            class="inputFieldHeight form-control datepicker date-value"
                                                            name="form"
                                                            value="@if($form){{ date('d/m/Y', strtotime($form)) }} @endif"
                                                            placeholder="DD/MM/YYYY"

                                                            autocomplete="off">

                                                        </div>

                                                        <div class="col-4 col-lg-2">
                                                            <button type="submit" style="padding: 5px 0px;" class="btn mSearchingBotton mb-2 formButton"
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

                                                <div class="col-12 col-lg-4">
                                                    <button type="button" class="btn btn-primary btn_create formButton float-right employee_modal_open"
                                                        data-modal="#employee-modal" title="Add" data-toggle="modal" data-target="#studentProfileAdd">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" width="25">
                                                            </div>
                                                            <div><span>Add New  </span></div>
                                                        </div>
                                                    </button>
                                                    <button type="button" class="btn btn-info btn_create formButton float-right employee_modal_open mr-1"
                                                    data-modal="#employee-default-weekend-modal" title="Add" data-toggle="modal" data-target="#studentProfileAdd">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" width="25">
                                                            </div>
                                                            <div><span>Add Default  </span></div>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive" style="padding-left:5px;">
                                            <div class="tab-content tab-content1" id="leaveTabContent">
                                                <!-- Annual Leave Table -->
                                                    <div class="table-responsive">
                                                        <table class=" table myTable table-bordered table-sm" style="width: 100%; font-size:10px">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 10%">Employee Name</th>
                                                                    <th colspan="31" style="font-size: 10px; text-align:center;text-transform:uppercase">  EMPLOYEE WEEKEND of  {{date('M-Y',strtotime($request_date.'-01'))}}</th>
                                                                </tr>
                                                                <tr>
                                                                    <th></th>
                                                                    @foreach ($daysArray as  $day)
                                                                    <th class="th-td-color-2"  data-date="{{$day['date']}}" title="{{$day['date']}}">{{$day['day_number']}}</th>
                                                                    @endforeach
                                                                    @php
                                                                        $numDays = count($daysArray);
                                                                        $extraColumns =  31 - $numDays;

                                                                    @endphp
                                                                    @if ($extraColumns > 0)
                                                                        @for ($i = 0; $i < $extraColumns; $i++)
                                                                            <th></th>
                                                                        @endfor
                                                                    @endif
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($employees as  $data)
                                                                    <tr class="text-center">

                                                                        <td class="emp_show text-dark text-left" data-id="{{$data->id}}" title="{{ $data->first_name.' '.$data->last_name }}" style="cursor:pointer">{{ $data->full_name.' ('.$data->code.')'}}</td>
                                                                        @foreach ($daysArray as  $day)
                                                                        @php
                                                                            $current_date = date('Y-m-d', strtotime($request_date . '-' . $day['day_number']));
                                                                            $present = $data->weekend($data->id, $current_date);

                                                                            $day_of_week = date('l', strtotime($current_date));

                                                                            if ($present['weekend'] == '1') {
                                                                                $style = 'bg-info text-white';
                                                                                $present_status = 'WEEKEND';
                                                                                $title_show = date('d-m-Y', strtotime($current_date));
                                                                            } //elseif ($day_of_week == 'Friday') {
                                                                            //     $style = 'bg-warning text-dark';
                                                                            //     $present_status = 'HALF DAY';
                                                                            //     $title_show = 'HALF DAY ' . date('d-m-Y', strtotime($current_date));
                                                                            // }
                                                                            else {
                                                                                $style = 'text-secondary';
                                                                                $present_status = 'WD';
                                                                                $title_show = '(WORKING DAY) ' . date('d-m-Y', strtotime($current_date));
                                                                            }
                                                                        @endphp

                                                                        <td class="{{$style}} show-attendance" title="{{$title_show}}">{{$present_status }}</td>
                                                                        @endforeach
                                                                        @php
                                                                            $numDays = count($daysArray);
                                                                            $extraColumns =  31 - $numDays;
                                                                            $emp_day_present = $data->emp_month_present($data->id , date('Y-m-d',strtotime($request_date.'-01')));

                                                                       @endphp
                                                                        @if ($extraColumns > 0)
                                                                            @for ($i = 0; $i < $extraColumns; $i++)
                                                                            <td></td>
                                                                            @endfor
                                                                        @endif
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
@include('backend.payroll.holiday-weekend.modal')
@endsection

@push('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-multidatespicker/1.6.6/jquery-ui.multidatespicker.min.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/form-repeater.js"></script>
@include('backend.payroll.holiday-weekend.ajax')


 <script>

    $(document).ready(function() {
        BtnAdd('#TRow', '#TBody');

        var today = new Date();
        var dayOfWeek = today.getDay();
        var daysToNextSaturday = (6 - dayOfWeek + 7) % 7;
        var daysToNextThursday = (4 - dayOfWeek + 7 + daysToNextSaturday) % 7 + daysToNextSaturday;

        $(".weekend_date").datepicker({
            dateFormat: 'dd/mm/yy',
            minDate: daysToNextSaturday,
            maxDate: daysToNextThursday
        });
    });

    function BtnAdd(trow, tbody) {

        var today = new Date();
        var dayOfWeek = today.getDay();
        var daysToNextSaturday = (6 - dayOfWeek + 7) % 7;
        var daysToNextThursday = (4 - dayOfWeek + 7 + daysToNextSaturday) % 7 + daysToNextSaturday;

        var $trow = $(trow);
        var $tbody = $(tbody);
        var newRow = $trow.clone().removeClass("d-none").removeAttr('id');
        newRow.appendTo(tbody);
        newRow.find("input, select, textarea").prop('disabled', false);
        newRow.find("input, select, textarea").prop('required', true);

        newRow.find("select").select2();
        newRow.find(".weekend-date").multiDatesPicker({
                dateFormat: 'dd/mm/yy'
            });

    }
    function BtnAddALL() {
    var $tbody = $("#TBody");

    // Remove all rows that don't have disabled fields
    $tbody.find('tr').each(function() {
        if ($(this).find('input:enabled, select:enabled, textarea:enabled').length > 0) {
            $(this).remove();  // Remove row if it contains any enabled input/select/textarea
        }
    });

    // Loop through each employee and add a row for each
    @foreach ($employees as $employee)
        var today = new Date();
        var dayOfWeek = today.getDay();
        var daysToNextSaturday = (6 - dayOfWeek + 7) % 7;
        var daysToNextThursday = (4 - dayOfWeek + 7 + daysToNextSaturday) % 7 + daysToNextSaturday;

        // Clone the row
        var $trow = $("#TRow");
        var newRow = $trow.clone().removeClass("d-none").removeAttr('id');

        // Clear the previous row's non-disabled input/select fields
        newRow.find("input:enabled, select:enabled, textarea:enabled").closest('tr').remove();
        // Enable the input/select fields
        newRow.find("input, select, textarea").prop('disabled', false).prop('required', true);

        // Set the employee's select value
        newRow.find("select").val("{{ $employee->id }}");

        // Re-initialize select2 and datepicker for the new row
        newRow.find("select").select2();
        newRow.find(".weekend-date").multiDatesPicker({
                dateFormat: 'dd/mm/yy'
            });

        // Append the new row to the table
        newRow.appendTo($tbody);
    @endforeach
}

    function BtnDel1(v) {
        $(v).closest('tr').remove();
        total_amount();

    }
    // Event handler for arrow key navigation
    $(document).on('keydown', 'td', function (e) {
        var $this = $(this);
        var index = $this.index();
        var $tr = $this.closest('tr');
        switch (e.which) {
            case 37: // Left arrow key
                if (index > 0) {
                    e.preventDefault();
                    highlightAndFocus($tr.find('td:eq(' + (index - 1) + ')').find(':input'));
                }
                break;
            case 38: // Up arrow key
                e.preventDefault();
                var $prevRow = $tr.prev('tr');
                if ($prevRow.length > 0) {
                    highlightAndFocus($prevRow.find('td:eq(' + index + ')').find(':input'));
                }
                break;
            case 39: // Right arrow key
                e.preventDefault();
                if (index < $tr.find('td').length - 1) {
                    highlightAndFocus($tr.find('td:eq(' + (index + 1) + ')').find(':input'));
                }
                break;
            case 40: // Down arrow key
                e.preventDefault();
                var $nextRow = $tr.next('tr');
                if ($nextRow.length > 0) {
                    highlightAndFocus($nextRow.find('td:eq(' + index + ')').find(':input'));
                }
                break;
        }
     });

     $(document).on('change', 'select', function () {
        var $this = $(this);
        var $td = $this.closest('td');

        var index = $td.index();
        var $tr = $this.closest('tr');
        var $element = $tr.find('td:eq(' + (index + 1) + ')').find(':input');
        $(':focus').removeClass('highlight');
        if ($element.hasClass('select2-hidden-accessible')) {
            $element.addClass('highlight').select2('open');
        } else {
            $element.addClass('highlight');
        }
    });

    function highlightAndFocus($element) {
        $(':focus').removeClass('highlight');
        if ($element.hasClass('select2-hidden-accessible')) {
            $element.siblings('.select2').find('.select2-selection').addClass('highlight').focus();
        } else {
            $element.addClass('highlight').focus();
        }
     }
</script>

    @endpush

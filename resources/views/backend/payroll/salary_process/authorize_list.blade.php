
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
                    <div class="row">
                        <div class="col-12 col-md-6">
                            @include('backend.payroll.tab-sub-tab._salary_submenu',['activeMenu' => 'authorize'])
                        </div>
                        <div class="col-12 col-md-6">
                            <form action="" id="form-date-range" method="GET" class="d-flex row">
                                <div class="form-group col-5 col-md-6" style="padding-left:7px;">
                                    <select name="employee_id" id="employee_id" class="form-control inputFieldHeight common-select2">
                                        <option value=""> Select </option>
                                        @foreach ($employees as $item)
                                            <option value="{{$item->id}}"> {{$item->code . ' ' . $item->full_name . ' ' . $item->contact_number}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-4 col-md-4" style="padding-left:7px;">
                                    <input type="month"
                                    class="inputFieldHeight form-control  date-value" id="monthInput"
                                    name="month"
                                    value="{{ $monthYear }}"
                                    placeholder="DD/MM/YYYY"
                                    autocomplete="off">
                                </div>

                                <div class="col-3 col-md-2">
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

                    <div class="cardStyleChange">
                        <form action="{{route('salary-process.action.all')}}" method="POST" class="mb-2">
                            @csrf

                            <div class="table-responsive" style="margin-left: -8px;">
                                <table class="table table-bordered table-sm employee_change" id="2filter-table">
                                    <thead class="thead-light">
                                        <tr class="text-center" style="height: 40px;">
                                            <th class="">
                                                <div class="d-flex align-items-center ml-2">
                                                    <input type="checkbox" class="form-check-input" id="checkAll">
                                                    <label for="checkAll" class="text-white" style="margin-top: 10px;"> Name </label>
                                                </div>
                                            </th>
                                            <th> Basic </th>
                                            <th> Over Time </th>
                                            <th> Late Time </th>
                                            <th> Absence </th>
                                            <th> Current Salary </th>
                                            <th> Total </th>
                                            <th style="width:120px;"> Pay Salary </th>
                                            <th> Advance  </th>
                                            <th> Action </th>
                                        </tr>
                                    </thead>
                                    <tbody class="t-body">
                                        @foreach ($salary_process as $salary)
                                        <tr class="text-center" style="border-bottom: 1px solid #dfe3e7">

                                            <td class="employee text-left" style="width: 20%">
                                                <input class="checkItem" type="checkbox" name="employee_id[{{$salary->id}}]" id="{{$salary->id}}" value="{{$salary->id}}">
                                                <label for="{{$salary->id}}"> {{optional($salary->employee)->full_name}} </label>
                                            </td>
                                            <td>
                                                {{$salary->basic}}
                                            </td>

                                            <td>
                                                {{$salary->overtime}}
                                            </td>

                                            <td>
                                                {{$salary->late}}
                                            </td>

                                            <td>
                                                {{$salary->absen_penalty}}
                                            </td>

                                            <td>
                                                {{$salary->advance_amount}}
                                            </td>

                                            <td>
                                                {{$salary->total}}
                                            </td>

                                            <td> {{$salary->amount}} </td>

                                            <td>
                                                {{$salary->advance_amount}}
                                            </td>

                                            <td>
                                                <a  href="{{ route('salary.procres.approve', [$salary->id]) }}"
                                                     onclick="event.preventDefault(); deleteAlert(this, 'Are youe sure to approve it?','approve');"
                                                    style="border: none; margin:0 5px;" title="Approve">
                                                        <i class='bx bx-message-square-check'></i>
                                                </a>

                                                <a  href="{{ route('employee.salary.procres.destroy', [$salary->id]) }}"
                                                     onclick="event.preventDefault(); deleteAlert(this, 'Are youe sure to delete it?'); "
                                                    style="border: none; margin:0 5px;color:rgb(143, 17, 17);" title="Delete">
                                                        <i class="bx bx-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if ($salary_process->count() > 0)
                                <button type="submit" name="action" value="approve" class="btn btn-success"
                                    onclick="event.preventDefault(); deleteAlert(this, 'Are youe sure to approve selected items?','approve'); click()"
                                    >Approve</button>
                                <button type="submit" name="action" value="delete" class="btn btn-danger"
                                    onclick="event.preventDefault(); deleteAlert(this, 'Are youe sure to delete selected items?'); click()"
                                >Delete</button>
                            @endif

                        </form>
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
    // Toggle all checkboxes
    $('#checkAll').on('change', function () {
        $('.checkItem').prop('checked', $(this).prop('checked'));
    });

    // Uncheck 'checkAll' if one checkbox is unchecked
    $(document).on('change', '.checkItem', function () {
        $('#checkAll').prop('checked', $('.checkItem:checked').length === $('.checkItem').length);
    });
 });
</script>
@endpush


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
                            @include('backend.payroll.tab-sub-tab._salary_submenu',['activeMenu' => 'salary-process'])
                        </div>
                        <div class="col-12 col-md-6">
                            <form action="" id="form-date-range" method="GET" class="d-flex row">
                                <div class="form-group col-5 col-md-6" style="padding-left:7px;">
                                    <select name="employee_id" id="employee_id" class="form-control inputFieldHeight common-select2">
                                        <option value=""> Select </option>
                                        @foreach ($employee_all as $item)
                                            <option value="{{$item->id}}"> {{$item->code . ' ' . $item->full_name . ' ' . $item->contact_number}} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-4 col-md-4" style="padding-left:7px;">
                                    <input type="month"
                                    class="inputFieldHeight form-control  date-value" id="monthInput"
                                    name="month"
                                    value="{{ $month_year }}"
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
                        <form action="{{route('salary-process.store')}}" method="POST" class="mb-2">
                            @csrf
                            <input type="hidden" value="{{$month_year}}" name="month_year">
                            <div class="table-responsive d-none d-lg-block" style="margin-left: -8px;">
                                <table class="table table-bordered table-sm employee_change" id="2filter-table">
                                    <thead class="thead-light">
                                        <tr class="text-center" style="height: 40px;">
                                            <th class="min-width:150px">
                                                <div class="d-flex align-items-center ml-2">
                                                    <input type="checkbox" class="form-check-input" id="checkAll">
                                                    <label for="checkAll" class="text-white" style="margin-top: 10px;"> Name </label>
                                                </div>
                                            </th>
                                            <th> Basic </th>
                                            <th style="min-width: 70px;"> Over Time </th>
                                            <th style="min-width: 70px;"> Late Time </th>
                                            <th> Absence </th>
                                            <th style="min-width: 100px;"> Current Salary </th>
                                            <th> Total </th>
                                            @if(array_sum(array_column($salarys, 'paid_salary')) > 0)
                                                <th style="min-width: 90px;text-align:right;">Paid</th>
                                            @endif
                                            <th style="width:120px; text-align:right"> Pay Salary </th>
                                            <th class="text-right" style="min-width: 90px;"> Advance  </th>
                                             @if(array_sum(array_column($salarys, 'advance')) > 0)
                                                <th style="min-width: 140px;text-align:right;">Reduece Advance </th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="t-body">
                                        @foreach ($salarys as $data)
                                        <tr class="text-center" style="border-bottom: 1px solid #dfe3e7">

                                            <td class="employee text-left" data-modal="#employee-modal" data-id="{{ route('salary-process.edit', $data['employee_id']) }}" style="width:140px;">
                                                <input class="checkItem" type="checkbox" name="employee_id[{{$data['employee_id']}}]" id="{{$data['employee_id']}}" value="{{$data['employee_id']}}">
                                                <label for="{{$data['employee_id']}}"> {{ $data['employee_name'] }} </label>
                                            </td>
                                            <td>
                                                <input class="form-control text-center" type="text" name="basic[{{ $data['employee_id'] }}]" readonly value={{ number_format($data['basic_salary'], 2,'.','') }}>
                                            </td>

                                            <td>
                                                <input class="form-control text-center" type="text" name="overtime[{{ $data['employee_id'] }}]" readonly value={{ number_format($data['overtime_amount'], 2,'.','') }}>
                                            </td>

                                            <td>
                                                <input class="form-control text-center" type="text" name="late[{{ $data['employee_id'] }}]" readonly value={{ number_format($data['late_amount'], 2,'.','') }}>
                                            </td>

                                            <td>
                                                <input class="form-control text-center" type="text" name="absen_penalty[{{ $data['employee_id'] }}]" readonly value={{ number_format($data['total_absen_penalty'], 2,'.','') }}>
                                            </td>

                                            <td>
                                               <input class="form-control text-center" type="text" name="basic_salary_current_day[{{ $data['employee_id'] }}]" readonly value={{ number_format($data['basic_salary_current_day'], 2,'.','') }}>
                                            </td>

                                            <td>
                                                <input class="form-control text-center" type="text" name="totals[{{ $data['employee_id'] }}]" readonly value={{ number_format($data['amount'], 2,'.','') }}>
                                            </td>
                                           @if(array_sum(array_column($salarys, 'paid_salary')) > 0)
                                           <td>
                                                <input type="text" class="form-control text-right" name="paid_salary[{{ $data['employee_id'] }}]"
                                                    value="{{ number_format($data['paid_salary'] ?? 0, 2) }}" readonly>
                                            </td>
                                            @endif

                                            <td>
                                                <input type="text" class="form-control text-right" name="amount[{{ $data['employee_id'] }}]"
                                                    value="{{ number_format($data['amount'] ?? 0, 2) }}">
                                            </td>

                                            <td>
                                                <input type="text" class="form-control text-right advance_amount" name="advance_amount[{{ $data['employee_id'] }}]" placeholder="{{ $data['advance'] > 0 ? $data['advance'] : 'Advance Salary' }}" min="1">
                                            </td>
                                            @if(array_sum(array_column($salarys, 'advance')) > 0)
                                            <td>
                                                <input type="text" class="form-control text-right reduce_advance" name="reduce_advances[{{ $data['employee_id'] }}]" title="Reduce Advance" placeholder="Reduce Advance" min="1" value="{{number_format($data['advance'],2,'.','')}}" max="{{number_format($data['advance'],2,'.','')}}">
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <button type="submit" class="btn btn-primary" style="margin-left: -8px;"> Save </button>
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

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
</style>

<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('backend.payroll.tab-sub-tab._basic_info_header',['activeMenu' => 'grade-wise-salary-components'])
            <div class="tab-content bg-white">
                <div id="studentProfileList" class="tab-pane active px-2 pt-1" style="max-width:100%;">
                    {{-- @include('backend.payroll.tab-sub-tab._salary_procedures_submenu',['activeMenu' => 'division']) --}}
                    <div class="row" id="table-bordered">
                        <div class="col-12">
                            <div class="cardStyleChange">
                                <div class="">
                                    <div class="d-flex mt-1 justify-content-between align-items-center">
                                        <button type="button" class="btn btn-primary employee_modal_open btn_create formButton float-right" data-modal="#employee-modal" title="Add" data-toggle="#employee-modal" data-target="#studentProfileAdd">
                                            <div class="d-flex align-items-center">
                                                <div class="formSaveIcon">
                                                    <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" width="24">
                                                </div>
                                                <div class=""><span> Add new </span></div>
                                            </div>
                                        </button>
                                    </div>

                                    <div class="table-responsive mt-2">
                                        <form id="myform" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="table-responsive " style="height:75vh;max-height:75vh; overflow:auto;max-width: 876px;">

                                                <table class="table table-bordered table-sm employee_change  " id="2filter-table">
                                                    <thead  class="thead-light">
                                                        <tr class="text-center" style="height: 40px;">
                                                            <th>Department</th>
                                                            <th class="pl-2" style="width: 10%"> Action </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="t-body">
                                                        @foreach ($items as $key => $data)
                                                            <tr class="text-center" style="border-bottom: 1px solid #dfe3e7">
                                                                <td class="employee"
                                                                    data-modal="#employee-modal"
                                                                        data-id="{{ route('division.edit',$data) }}"style="width: 60%">{{ $data->name }}
                                                                </td>

                                                                <td>
                                                                    <div class="btn-group">
                                                                        <div class="dropdown">
                                                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-top:2px; padding-bottom: 2px; font-size: 12px; padding-left: 10px;">
                                                                                Actions
                                                                            </button>
                                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                                <a class="dropdown-item studentdocument employee" data-modal="#employee-modal"  data-id="{{ route('division.edit',$data) }}" id=""> Edit </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </form>
                                    </div>

                                </div>
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
@include('backend.payroll.division.modal')
@endsection
@push('js')
<script>
    @if (count($errors) > 0)
        $('#parentProfileAdd').modal('show');
    @endif
    function printFunction(){
        window.print();
    }

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
@include('backend.payroll.division.ajax')
@endpush

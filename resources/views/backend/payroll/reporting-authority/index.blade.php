@extends('layouts.backend.app')

@push('css')
<!-- summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@endpush

@section('content')
@include('layouts.backend.partial.style')
<style>
    .table td {
        vertical-align: middle;
        border-bottom: 1px solid #DFE3E7;
        border-top: none;
        font-size: 12px;
    }
</style>
<style>
    .table .thead-light th {
        color: #F2F4F4;
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

    .inputFieldHeight {
        height: 32px;
    }

    .select2-container--default .select2-selection--single {
        min-height: 32px !important;
    }

    .select2-selection__rendered{
        font-size:13px !important;
    }

    .tree ul {
        list-style-type: none;
        padding-left: 20px;
        position: relative;
    }

    .tree ul::before {
        content: '';
        position: absolute;
        top: 0;
        left: 8px;
        border-left: 2px solid #ccc;
        bottom: 0;
    }

    .tree li {
        position: relative;
        padding: 8px 0 8px 20px;
    }

    .tree li::before {
        content: '';
        position: absolute;
        top: 16px;
        left: 0;
        width: 15px;
        border-top: 2px solid #ccc;
    }

    .tree-node {
        padding: 6px 10px;
        background-color: #f9f9f9;
        border-radius: 6px;
        border: 1px solid #ddd;
        display: inline-block;
        min-width: 120px;
        box-shadow: 1px 1px 3px rgba(0,0,0,0.05);
        transition: background 0.3s;
    }

    .tree-node:hover {
        background-color: #eef6ff;
    }

    .employee-name {
        font-weight: 500;
        font-size: 14px;
    }

    .tree-node .btn.btn-icon{
        display: none;
    }

    .tree-node:hover .btn.btn-icon{
        display: inline-block;
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
            @include('backend.payroll.tab-sub-tab._basic_info_header', ['activeMenu' => 'reporting-authority'])
            <div class="tab-content bg-white">
                <div id="studentProfileList" class="tab-pane active pt-1" style="max-width:100%">
                    {{-- @include('backend.payroll.tab-sub-tab.attendance_submenu',['activeMenu' => 'project']) --}}

                    <div class="cardStyleChange">
                        <div class="card-body  pb-0" style="margin-top: 13px;">

                            <section id="basic-vertical-layouts">
                                <div class="d-flex justify-content-between align-items-center" style="margin: 0 10px;">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                                        Add New
                                    </button>

                                    <form class="d-flex" style="margin-left:10px;">

                                       <select name="selected_employee" class="common-select2 form-control" style="width: 300px;">
                                            <option value=""> Search by employee ... </option>

                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}"
                                                        data-code="{{ $employee->code }}"
                                                        {{ $selected_employee_id == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->full_name }} ({{ $employee->code }})
                                                </option>
                                            @endforeach
                                       </select>

                                        <input type="text" name="date" id="date-search" class="form-control inputFieldHeight datepicker" value="{{date('d/m/Y',strtotime($date))}}" style="width:100px;margin:0 10px;">

                                        <button class="btn btn-primary inputFieldHeight" style="padding:3px 15px !important;" type="submit"> Search </button>

                                    </form>
                                </div>


                                <div class="tree mt-1">
                                    <ul class="tree-root">
                                        @foreach ($topEmployees as $employee)
                                            @include('backend.payroll.reporting-authority.employee-tree',[
                                                'employee' => $employee,
                                                'depth' => 0
                                            ])
                                        @endforeach
                                    </ul>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding:5px 10px;background:#364a60;">
                <h5 class="modal-title" id="exampleModalLabel"
                    style="font-family:Cambria;font-size: 2rem;color:white;margin-left: 10px;"> Reporting Authority </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" style="width:100%;max-width:700px;padding:15px;">
                <form action="{{ route('reporting.authority.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-5 form-group">
                            <label for=""> Parent Employee </label>
                            <select name="parent_id" class="form-control common-select2">
                                <option value=""> Select... </option>
                                @foreach ($top_free_employees as $employee)
                                    <option value="{{$employee->id}}">
                                        {{$employee->full_name . ' (' .$employee->code.')' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-5 form-group">
                            <label for=""> Date </label>
                            <input type="text" class="datepicker form-control inputFieldHeight" name="work_date" value="{{date('d/m/Y')}}">
                        </div>
                    </div>

                    <table class="table table-bordered table-sm">
                        <thead style="background:#34465b;color:#fff;">
                            <tr>
                                <th style="width: 70%;color:#fff; text-align:left !important; padding:4px;"> Employee </th>
                                <th style="width: 15%;color:#fff; text-align:center !important; padding:4px;"> Code </th>
                                <th class="NoPrint" style="padding: 2px; text-align:center !important;"> <button type="button"
                                        class="btn btn-sm btn-success addBtn"style="border: 1px solid green;
                                    color: #fff; border-radius: 10px;padding: 5px;"
                                        onclick="BtnAdd(this)"><i class="bx bx-plus" style="color: white;"></i></button>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="sale-item">
                            <tr class="text-center invoice_row">
                                <td class="text-left">
                                    <select name="group-a[0][child_id]" class="child_id form-control text-left common-select2" required>

                                        <option value=""> Select... </option>

                                        @foreach ($employees as $employee)
                                            <option value="{{$employee->id}}" data-code="{{$employee->code}}"> {{$employee->full_name . ' (' .$employee->code.')' }} </option>
                                        @endforeach

                                    </select>
                                </td>

                                <td>
                                    <input type="text" class="form-control inputFieldHeight code">
                                </td>

                                <td class="NoPrint"><button style="padding: 5px; margin: 4px;"
                                        type="button"
                                        class="btn btn-sm btn-danger"onclick="BtnDel(this)"><i class="bx bx-trash" style="color: white;"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <button class="btn btn-sm btn-primary"> Save </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" style="width: 100%;" id="employee-modal-show" tabindex="-1" role="dialog"
    aria-labelledby="employee-modal-show" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="padding-right: 0px !important;">
        <div class="modal-content">
            <div class="" id="modal-details">

            </div>
        </div>
    </div>
</div>

{{-- ****************  modal end ************************ --}}


@endsection
@push('js')
<script>
    $(document).on('click', '.edit-btn', function(){
        var url = $(this).data('url');
        var date = '{{$date}}';
        $.ajax({
            url:url,
            type:'get',
            data:{
                date:date,
            },
            success:function(res){
                $('#modal-details').html(res)
                $('#employee-modal-show').modal('show');
                $('.common-select2').select2();
            },
            error:function(error){
                toastr.error(error.responseJSON.error,'404');
            }
        });
    });

    var employees =  @json($employees);
    var index = 0;
    function BtnAdd(node) {
        index += 1;
        var table = $(node).closest('table');
        var tbody = table.find('tbody');
        var trLength = tbody.find('tr').length;

        index = trLength + 1;

        // Build <option> elements from employees
        var options = "<option value=''> Select... </option>";
        employees.forEach(employee => {
            options += `<option value="${employee.id}" data-code="${employee.code}">
                ${employee.full_name} (${employee.code})
            </option>`;
        });

        // Create a new row
        var newRow = `
            <tr>
                <td class="text-left">
                    <select name="group-a[${index}][child_id]" class="child_id form-control text-left common-select2" required>
                        ${options}
                    </select>
                </td>

                <td>
                    <input type="text" class="form-control inputFieldHeight code">
                </td>

                <td class="NoPrint text-center">
                    <button style="padding: 5px; margin: 4px;" type="button"
                        class="btn btn-sm btn-danger" onclick="BtnDel(this)">
                        <i class="bx bx-trash" style="color: white;"></i>
                    </button>
                </td>
            </tr>
        `;

        // Append new row to tbody
        tbody.append(newRow);

        // Reinitialize Select2 if used
        $('.common-select2').select2();
    }

    $(document).on('change', '.child_id', function(){
        var code = $(this).find('option:selected').data('code');
        $(this).closest('tr').find('.code').val(code);
    });

    function BtnDel(node){
        Swal.fire(alertDesign('Do you want to remove this employee', 'delete'))
            .then((result) => {
                var url = $(node).data('url');
                var tr = $(node).closest('tr');
                var top_employee_id = $('#edit-top-employee').val();
                var date = $('#edit-date').data('date');
                // Remove row from DOM
                tr.remove();
                // Make AJAX request
                $.ajax({
                    url: url,
                    type: 'DELETE', // 'delete' is okay too, but conventionally use uppercase
                    data: {
                        _token: '{{ csrf_token() }}', // This must be echoed correctly in Blade
                        top_employee_id: top_employee_id,
                        date:date,
                    },
                    success: function (res) {
                        Swal.fire(alertDesign('The employee has been remove successfully', 'success'))
                    },

                    error: function (xhr) {
                        console.error(xhr);
                    }
                });
            });
    }
</script>
@endpush

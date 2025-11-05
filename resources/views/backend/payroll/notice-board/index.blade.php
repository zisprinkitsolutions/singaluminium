@extends('layouts.backend.app')
@section('content')

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
            @include('backend.payroll.tab-sub-tab._basic_info_header',['activeMenu' => 'notice_borad'])
            <div class="tab-content bg-white">
                <div id="studentProfileList" class="tab-pane active px-2">
                    <div class="row" id="table-bordered">
                        <div class="col-12">
                            <div class="cardStyleChange">
                                <div class="">
                                    <button type="button" class="btn btn-primary employee_modal_open btn_create formButton float-right mb-1 mt-1" style="padding:5px" data-modal="#employee-modal" title="Add" data-toggle="#employee-modal" data-target="#studentProfileAdd">
                                        <div class="d-flex align-items-center">
                                            <div class="formSaveIcon">
                                                <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" width="24">
                                            </div>
                                            <div class=""><span> Add new </span></div>
                                        </div>
                                    </button>
                                    <div class="table-responsive mt-2" >
                                            <div class="table-responsive " style="height:75vh;max-height:75vh; overflow:auto;">

                                                <table class="table table-bordered table-sm employee_change  " id="2filter-table">
                                                    <thead  class="thead-light">
                                                        <tr style="height: 40px;">
                                                            <th>Notice</th>
                                                            <th>Document</th>
                                                            <th>Employee</th>
                                                            {{-- <th class="pl-2" style="width: 10%"> Action </th> --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody class="t-body">
                                                        @foreach ($notices as $key => $data)
                                                            <tr style="border-bottom: 1px solid #dfe3e7">
                                                                <td class="pl-1">{!! $data->notice !!}</td>
                                                                <td>
                                                                    <a href="{{ asset('storage/upload/notice-board/'.$data->document) }}" target="_blank" style="color:#000;">
                                                                        <img src="{{ asset('storage/upload/notice-board/'.$data->document) }}" class="img-fluid rounded-circle" style="width:150px; height:150px; object-fit: cover;" alt="Notice Docs">
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    {{$data->employee?$data->employee->full_name:'All Employees'}}
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
            </div>
            <div id="printArea" class="d-none">

            </div>
        </div>
    </div>
</div>
<div>
    <div class="modal fade bd-example-modal-lg" id="employee-modal" style="width: 90%; left: 5%;" tabindex="-1" role="dialog"
    aria-labelledby="employee-modal" aria-hidden="true">

        <div class="modal-dialog-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="p-0" style="font-family:Cambria;font-size: 2rem;"><b>Add New Notice</b></h5>
                    <button type="button" class="close " data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <form action="{{route('notice-board.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <label for="">Noice</label>
                                <textarea name="notice"  class="form-control description" rows="10" id="summernote" placeholder="Description" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="col-form-label">Employee</label>
                                        <select name="employee_id" id="" class="form-controll common-select2">
                                            <option value="">Select Name</option>
                                            @foreach ($employees as $item)
                                                <option value="{{$item->id}}">{{$item->full_name}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="col-form-label">Document</label>
                                <input type="file" class="form-control" name="file" value="" >
                                </div>
                                <div class="col-md-2 text-right mt-2">
                                    <button type="submit" class="btn btn-primary mt-1">Submit</button>
                                </div>
                            </div>

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
{{-- parent --}}
<script>
    // Use the plugin once the DOM has been loaded.

    $(function() {
        // Apply the plugin

        $('#2filter-table').excelTableFilter();

    });
    $(document).on('click', '.close', function(e){
        e.preventDefault();
       // alert(5)
       location.reload();
    })

    // show inser modal for insert data
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

</script>



    @include('backend.payroll.notice-board.ajax')


@endpush

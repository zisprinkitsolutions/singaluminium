
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
    .modal.show .modal-dialog {
        width: 70%;
    }
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
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.hrPayroll._payroll_process_header',['activeMenu' => 'advance-salary'])
            <div class="tab-content bg-white">
                <div id="studentProfileList" class="tab-pane active px-2">
                    <div class="row" id="table-bordered">
                        <div class="col-12">
                            <div class="cardStyleChange">
                                <div>
                                    <div class="d-flex align-items-center justify-content-end mt-1">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" width="25">
                                                </div>
                                                <div><span> Add new </span></div>
                                            </div>
                                        </button>
                                    </div>


                                    <div class="table-responsive mt-2">
                                        <div class="table-responsive " style="height:75vh;max-height:75vh; overflow:auto;">
                                            <table class="table table-bordered table-sm employee_change " id="2filter-table">
                                                <thead  class="thead-light">
                                                    <tr class="text-center" style="height: 40px;">
                                                        <th style="width: 10%"> EMP ID </th>
                                                        <th style="width: 18%"> Name </th>
                                                        <th style="width: 13%"> Department</th>
                                                        <th style="width: 18%"> Description </th>
                                                        <th style="width: 18%"> Amount </th>
                                                        <th style="width: 13%"> Date </th>
                                                        <th style="width: 10%"> Document </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($advance_salaries as $item)
                                                        <tr class="text-center" style="font-size: 12px;">
                                                            <td>{{$item->employee->emp_id}}</td>
                                                            <td>{{$item->employee->first_name}}</td>
                                                            <td>{{$item->employee->dvision->name}}</td>
                                                            <td>{{$item->description}}</td>
                                                            <td>{{$item->amount}}</td>
                                                            <td>{{date('d/m/Y', strtotime($item->date))}}</td>
                                                            <td>
                                                                @if ($item->extension == 'pdf')
                                                                    <a href="{{ asset('storage/upload/advance-salary-document/'.$item->document)}}" target="blank">
                                                                        <img src="{{asset('assets/backend/app-assets/icon/pdf-download-icon-2.png')}}" style="height:30px; margin-right:10px; margin-top:-1px padding-top:0px" class="img-fluid float-right" alt="" >
                                                                    </a>
                                                                @else
                                                                    <a href="{{ asset('storage/upload/advance-salary-document/'.$item->document)}}" target="blank">
                                                                        <img src="{{ asset('storage/upload/advance-salary-document/'.$item->document)}}" style="height:30px; margin-right:10px; margin-top:-1px padding-top:0px" class="img-fluid float-right" alt="" >
                                                                    </a>
                                                                @endif
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
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content m-2">
        <section class="print-hideen border-bottom" style="background-color:#34465b">
            <div class="d-flex pr-2 pl-1" style="padding-right: 2rem !important;">
                <h4 class="mr-auto pl-2" style="font-family:Cambria;font-size: 2rem;color:#fff;padding:5px;">Advance Salary</h4>
                <div class="mIconStyleChange"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
            </div>
        </section>
        <form action="{{route('advance-salary.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row m-2">
                <div class="col-md-3 commonSelect2Style">
                    <label for="">Employee Name</label>
                    <select name="employee_id" id="" class="form-control common-select2" required>
                        <option value="">Select...</option>
                        @foreach ($employees as $item)
                            <option value="{{$item->id}}">{{$item->first_name.' '. $item->last_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 commonSelect2Style">
                    <label for="">Date</label>
                    <input type="text" class="inputFieldHeight form-control datepicker" name="date" required placeholder="dd/mm/yyyy">
                </div>
                <div class="col-md-3 commonSelect2Style">
                    <label for="">Pay Mode</label>
                    <select name="pay_mode" class="inputFieldHeight form-control" id="" required>
                        <option value="">Select...</option>
                        @foreach ($pay_modes as $item)
                                <option value="{{$item->title}}">{{$item->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 commonSelect2Style">
                    <label for="">Amount</label>
                    <input type="number" class="inputFieldHeight form-control" name="amount" required placeholder="Amount">
                </div>
                <div class="col-md-9 commonSelect2Style">
                    <label for="">Descrition</label>
                    <input type="text" class="inputFieldHeight form-control" name="description" required placeholder="Description">
                </div>
                <div class="col-md-3 commonSelect2Style">
                    <label for="">Document</label>
                    <input type="file" class="inputFieldHeight form-control" name="file">
                </div>
            </div>
            <button class="btn btn-primary ml-3 mb-2" type="submit">Save</button>
        </form>
      </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/form-repeater.js"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
{{-- parent --}}
@endpush

@extends('layouts.backend.app')
@section('content')
@php
    use Carbon\CarbonPeriod;
@endphp
@include('backend.tab-file.style')
<style>
    .table td{
        border-bottom: none;
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
            <div class="nav nav-tabs master-tab-section" id="nav-tab" role="tablist">
                <a href="{{route("new-salary-structure")}}" class="nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/salary-icon.png')}}" class="img-fluid" width="50">
                    </div>
                    <div>Salary Structure</div>
                </a>
                <a href="{{route("new-payroll-process")}}" class="nav-item nav-link active" role="tab" aria-controls="nav-contact" aria-selected="false">
                    <div class="master-icon text-cente">
                        <img src="{{asset('assets/backend/app-assets/icon/payroll-icon.png')}}" class="img-fluid" width="50">
                    </div>
                    <div>Payroll Search</div>
                </a>
            </div>
            <div class="tab-content bg-white">
                <div class="tab-pane active"  style="min-height: 300px">
                    <div class="content-body">
                        <div class="d-flex pl-2 pt-1 pr-2">
                            <h4  class="flex-grow-1">Payroll Search</h4>
                            <div>
                                <button type="button" class="btn btn-primary btn_create formButton" title="Add" data-toggle="modal" data-target="#newPayrollGenerateAdd">
                                    <div class="d-flex">
                                        <div class="formSaveIcon">
                                            <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" width="25">
                                        </div>
                                        <div><span>Payroll Gerenate</span></div>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <form class="form form-vertical" action="" method="get" enctype="multipart/form-data">
                            <div class="cardStyleChange">
                                <div class="card-body">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="class-name">Month</label>
                                                    <select name="month" id="" class="inputFieldHeight form-control" required>
                                                        @foreach(CarbonPeriod::create(now()->startOfMonth(), '1 month', now()->addMonths(11)->startOfMonth()) as $date)
                                                            <option value="{{ $date->format('F') }}" {{$date->format('F') == $month ? "selected":""}}>
                                                                {{ $date->format('F') }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('month')
                                                        <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="class-name">Year</label>
                                                    <select name="year" class="inputFieldHeight form-control" id="dropdownYear" required>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 d-flex justify-content-end">
                                                <button type="submit" class="btn mt-2 mb-2 formButton mSearchingBotton" title="Search">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{asset('assets/backend/app-assets/icon/searching-icon.png')}}" width="25">
                                                        </div>
                                                        <div><span>Search</span></div>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive pl-2 pr-2">
                            <table class="table mb-0 table-sm table-hover">
                                <thead  class="thead-light">
                                    <tr style="height: 50px;">
                                        <th>Employee Name</th>
                                        <th>Month</th>
                                        <th>Year</th>
                                        <th>Status</th>
                                        <th class="text-right pr-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payroll_lists as $list)                        
                                    <tr class="border-bottom trFontSize" >
                                        <td>{{ $list->employeeName->fname }} {{ $list->employeeName->mname }} </td>
                                        <td>{{ $list->month }} </td>
                                        <td>{{ $list->year }} </td>
                                        <td>{{ $list->status == 1 ? "Paid":"Unpaid" }} </td>
                                        <td class="text-right pr-2">
                                            @if ($list->status == 0)
                                                <a href="#" class="btn paymentPayrollProcessModal" title="Edit" id="{{$list->id}}" style="padding-top: 1px; padding-bottom: 1px; height: 30px; width: 30px;">
                                                    <img src="{{asset("assets/backend/app-assets/icon/payment-icon.png")}}" style=" height: 30px; width: 30px;">
                                                </a>
                                            @else
                                                <a href="#" class="btn showPayrollProcesseModal" title="View" id="{{$list->id}}" style="padding-top: 1px; padding-bottom: 1px; height: 30px; width: 30px;">
                                                    <img src="{{asset("assets/backend/app-assets/icon/view-icon.png")}}" style=" height: 30px; width: 30px;">
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{$payroll_lists->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- modal --}}
    <div class="modal fade bd-example-modal-lg modal-for-idCard" id="paymentPayrollProcessEditModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-for-idCard-body" role="document">
          <div class="modal-content">
            <div id="paymentPayrollProcessEdit">
              
            </div>
          </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg modal-for-idCard" id="paymentPayrollProcessPrint" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-for-idCard-body" role="document">
          <div class="modal-content">
            <div id="paymentPayrollPrint">
              
            </div>
          </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="newPayrollGenerateAdd" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <section class="print-hideen border-bottom">
                <div class="d-flex flex-row-reverse">
                    <div class="mIconStyleChange"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
                    {{-- <div class="mIconStyleChange"><a href="#" class="btn btn-icon btn-success"><i class="bx bx-edit"></i></a></div>
                    <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-secondary"><i class='bx bx-printer'></i></a></div>
                    <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-primary"><i class='bx bxs-file-pdf'></i></a></div>
                    <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
                </div>
            </section>
            @include('backend.tab-file.modal-header-info')
            @include('backend.payroll-process.new-payroll-generate')
            @include('backend.tab-file.modal-footer-info')
          </div>
        </div>
    </div>
@endsection
@push('js')
<script>
    function printFunction(){
        window.print();
    }
    var i, currentYear, startYear, endYear, newOption, dropdownYear;
        dropdownYear = document.getElementById("dropdownYear");
        currentYear = (new Date()).getFullYear();
        startYear = currentYear - 4;
        endYear = currentYear + 3;
        for (i=startYear;i<=endYear;i++) {
        newOption = document.createElement("option");
        newOption.value = i;
        newOption.label = i;
            if (i == currentYear) {
                newOption.selected = true;
            }
            dropdownYear.appendChild(newOption);
        }
$(document).on("click", ".paymentPayrollProcessModal", function(e) { 
    e.preventDefault();
    var id= $(this).attr('id');
    $.ajax({
        url: "{{URL('payment-payroll-process-modal')}}",
        type: "post",
        cache: false,
        data:{
            _token:'{{ csrf_token() }}',
            id:id,
        },
        success: function(response){				
            document.getElementById("paymentPayrollProcessEdit").innerHTML = response;
            $('#paymentPayrollProcessEditModal').modal('show');
            $('.common-select2').select2();
        }
    });
});
$(document).on("click", ".showPayrollProcesseModal", function(e) { 
    e.preventDefault();
    var id= $(this).attr('id');
    $.ajax({
        url: "{{URL('show-payment-payroll-modal')}}",
        type: "post",
        cache: false,
        data:{
            _token:'{{ csrf_token() }}',
            id:id,
        },
        success: function(response){				
            document.getElementById("paymentPayrollProcessEdit").innerHTML = response;
            $('#paymentPayrollProcessEditModal').modal('show');
            $('.common-select2').select2();
        }
    });
});
$(document).on("click", ".printPayrollProcesseModal", function(e) { 
    e.preventDefault();
    var id= $(this).attr('id');
    $.ajax({
        url: "{{URL('new-payslip-print')}}",
        type: "post",
        cache: false,
        data:{
            _token:'{{ csrf_token() }}',
            id:id,
        },
        success: function(response){				
            document.getElementById("paymentPayrollPrint").innerHTML = response;
            $('#paymentPayrollProcessPrint').modal('show');
            setTimeout(printFunction, 500);
        }
    });
});
</script>
<script type='text/javascript'>
    $(document).ready(function(){
      // Check or Uncheck All checkboxes
      $("#checkall").change(function(){
        var checked = $(this).is(':checked');
        if(checked){
          $(".checkbox").each(function(){
            $(this).prop("checked",true);
          });
        }else{
          $(".checkbox").each(function(){
            $(this).prop("checked",false);
          });
        }
      });    
     // Changing state of CheckAll checkbox 
     $(".checkbox").click(function(){    
       if($(".checkbox").length == $(".checkbox:checked").length) {
         $("#checkall").prop("checked", true);
       } else {
         $("#checkall").prop("checked", false);
       }
   
     });
   });
</script>
@endpush

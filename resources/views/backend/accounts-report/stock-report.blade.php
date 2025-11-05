@extends('layouts.backend.app')
@php
    $company_name= \App\Setting::where('config_name', 'company_name')->first();
    $company_address= \App\Setting::where('config_name', 'company_address')->first();
    $address2= \App\Setting::where('config_name', 'address2')->first();
    $company_tele= \App\Setting::where('config_name', 'company_tele')->first();
    $company_email= \App\Setting::where('config_name', 'company_email')->first();
    $trn_no= \App\Setting::where('config_name', 'trn_no')->first();
    $i=1;
@endphp
@push('css')
@include('layouts.backend.partial.style')
<style>
    .changeColStyle span{
        min-width: 16%;
    }
    .changeColStyle .select2-container--default .select2-selection--single .select2-selection__arrow b{
        display: none;
    }
    .journaCreation{
        background: #1214161c;
    }
    .transaction_type{
        padding-right:5px;
        padding-left:5px;
        padding-bottom:5px;
    }
    @media only screen and (max-width: 1500px) {
        .custome-project span{
            max-width: 140px;
        }
    }

    thead {
        background: #34465b;
        color: #fff !important;
    }
    th{
        color: #fff !important;
        font-size: 11px !important;
        height: 25px !important;
        text-align: center !important;
    }
    td
    {
        font-size: 12px !important;
        height: 25px !important;
        text-align: center !important;
    }

    .table-sm th, .table-sm td {
        padding: 0rem;
    }

    tr:nth-child(even) {
        background-color: #c8d6e357;
    }
    tr{
        cursor: pointer;
    }
</style>
@endpush
@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.report._header', [ 'activeMenu' => 'stock_report'])
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active">
                    <section id="widgets-Statistics ">
                        <div class="cardStyleChange print-menu">
                            <div class="card-body">
                                <div class="row ml-2">
                                    <div class="col-md-3 pl-0">
                                        <form action="" method="GET" class="d-flex row">
                                            <div class="row form-group col-md-8" style="padding-left:7px;">
                                                <input type="text"
                                                    class="inputFieldHeight form-control datepicker" name="date"
                                                    placeholder="Select Date" required autocomplete="off">
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn mSearchingBotton mb-2 formButton"
                                                    title="Search">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                width="25">
                                                        </div>
                                                        <div><span>Search</span></div>
                                                    </div>
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-6 ">
                                        <form action="" method="GET" class="d-flex row">
                                            <div class="row form-group col-md-4">
                                                <input type="text"
                                                    class="inputFieldHeight form-control datepicker" name="from"
                                                    placeholder="From Date" required autocomplete="off">
                                            </div>
                                            <div class="row form-group col-md-4 ml-1">
                                                <input type="text"
                                                    class="inputFieldHeight form-control datepicker" name="to"
                                                    placeholder="To Date" required autocomplete="off">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn mSearchingBotton mb-2 formButton"
                                                    title="Search">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                width="25">
                                                        </div>
                                                        <div><span>Search</span></div>
                                                    </div>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-3 col-padding-right pl-0">
                                        {{-- <a href="#" onclick="media_print('stock_report')" class="btn btn-icon btn-secondary"><i class="bx bx-printer"></i>Print</a> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="card-body pt-0 pb-0 daily-summery" id="stock_report">
                                @include('layouts.backend.partial.modal-header-info')
                                <h2 class="text-center">Stock Report</h2>
                                <h4 class="text-center">
                                    @if ($from && $to)
                                        Date:From {{date('d/m/Y', strtotime($from))}} To {{date('d/m/Y', strtotime($to))}}
                                    @else
                                        Date: {{date('d/m/Y', strtotime($date))}}
                                    @endif
                                </h4>
                                <table class="table table-bordered text-center">
                                    <thead class="thead">
                                        <tr >
                                            <th style="text-align: center !important;">Account Head</th>
                                            <th style="text-align: center !important;">Unit</th>
                                            <th style="text-align: center !important;">Total QTY In</th>
                                            <th style="text-align: center !important;">Total QTY Out</th>
                                            <th style="text-align: center !important;">Available QTY</th>
                                            <th style="text-align: center !important;">Amount</th>
                                            {{-- <th style="text-align: center !important;">Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody id="purch-body">
                                        @foreach ($special_heads as $item)
                                            <tr class="head_details"  id="{{$item->id}}">
                                                @php
                                                    $last_purchase = $item->purchase_item()->latest()->first();
                                                @endphp
                                                <td style="text-align: center !important; padding: 8px !important; margin: 8px !important;">{{$item->fld_ac_head}}</td>
                                                <td style="text-align: center !important; padding: 8px !important; margin: 8px !important;">{{$last_purchase->unit->name??''}}</td>
                                                <td style="text-align: center !important; padding: 8px !important; margin: 8px !important;">{{$item->purchase_item->sum('qty')}}</td>
                                                <td style="text-align: center !important; padding: 8px !important; margin: 8px !important;">{{$item->purchase_item->sum('out_qty')}}</td>
                                                <td style="text-align: center !important; padding: 8px !important; margin: 8px !important;">{{$current_qty = $item->purchase_item->sum('qty') - $item->purchase_item->sum('out_qty')}}</td>
                                                <td style="text-align: center !important; padding: 8px !important; margin: 8px !important;">{{number_format($item->purchase_item->sum('amount') - $item->purchase_item->sum('out_amount'),2)}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>


    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherPreviewShow">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="project-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px;">
              <h5 class="modal-title" id="exampleModalLabel"> View Project </h5>
              <div class="d-flex align-items-center">
                <button type="button" class="print-page project-btn bg-dark" style="margin:0 5px;">
                    <span aria-hidden="true">  <i class="bx bx-printer text-white"></i> </span>
                </button>
                <button type="button" class="project-btn bg-dark text-white" data-dismiss="modal" aria-label="Close" style="margin:0 5px;">
                    <span aria-hidden="true">&times;</span>
                </button>
              </div>

            </div>
            <div class="modal-body" style="padding: 5px 15px;">

            </div>
          </div>
        </div>
    </div>
        <div class="modal fade" id="project_expense_model" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <section class="print-hideen border-bottom">
                    <div class="d-flex">
                        <h4 class="mt-1 ml-1 mr-auto">Project Expense Assign</h4>
    
                        <div class="mIconStyleChange">
                            <a href="#" class="btn btn-sm btn-info d-none hide-unhide" id="add-product">Back
                                <span class="text-center" style="font-size: 18px;color:#92a0b1;"></span></a>
                                <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">
                                    <i class='bx bx-x'></i>
                                </span>
                            </a>
                        </div>
                    </div>
                </section>
    
                <section id="widgets-Statistics" class="mr-1 ml-1 mb-2 accountHeadStyle HeadStyle">
                    <div class="col-12 mt-2 m-0 p-0" id="project_expense_model_content">
                        
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).on("click", ".head_details", function(e) {
        e.preventDefault();
        var id= $(this).attr('id');
        console.log(id);
		$.ajax({
			url: "{{route('head-expense-detail')}}",
			type: "post",
			cache: false,
			data:{
				_token:'{{ csrf_token() }}',
                id:id,
			},
			success: function(response){
                document.getElementById("voucherPreviewShow").innerHTML = response;
                $('#voucherPreviewModal').modal('show')
			}
		});
	});
    $(document).on("click", ".head_expense", function(e) {
        e.preventDefault();
        var id= $(this).attr('id');
        console.log(id);
		$.ajax({
			url: "{{route('head-project-expense')}}",
			type: "post",
			cache: false,
			data:{
				_token:'{{ csrf_token() }}',
                id:id,
			},
			success: function(response){
                document.getElementById("voucherPreviewShow").innerHTML = response;
                $('#voucherPreviewModal').modal('show')
			}
		});
	});
    function BtnProjectItem(r){
        var current_tr = $(r).closest('tr');
        var id = current_tr.attr('id');
        console.log(id);
        $.ajax({
            url: "{{ route('project-expense-adjust') }}",
            type: "post",
            cache: false,
            data: {
                _token: '{{ csrf_token() }}',
                id: id
            },
            success: function(response) {
                $('#project_expense_model_content').empty().append(response);
                $('#project_expense_model').modal('show');
            }
        });
    }
    $(document).on('submit', '#project_expense_store', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var data = new FormData(this);
        var task_total_amount = $('#task_total_amount').val();
        var max_amount = $('#max_amount').val();
        var max_qty = $('#max_qty').val();
        var task_total_qty = $('#task_total_qty').val();
        if(Number(task_total_amount)>Number(max_amount)){
            toastr.warning('Please check your amount');
        }else if(Number(task_total_qty)>Number(max_qty)){
            toastr.warning('Please check your QTY');
        }
        else{
            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    $("#project_expense_model").modal('hide');
                    toastr.success('Add Success');
                }
            });
        }
    });
    
    $(document).on('keyup', '.task_qty', function(e){
        var qty = $(this).val();
        var current_tr = $(this).closest('tr');
        var max_amount = $('#max_amount').val();
        var max_qty = $('#max_qty').val();
        var rate = Number(max_amount)/Number(max_qty);
        console.log(rate, max_amount, max_qty);
        current_tr.find('.task_amount').val(rate*qty);
        project_total_qty();
        project_total_amount()
    });
    
    function project_total_amount(){
        total = 0;
        $('.task_amount').each(function() {
            var this_amount = $(this).val();
            this_amount = (this_amount === '') ? 0 : this_amount;
            var this_amount = parseFloat(this_amount);
            total = total + this_amount;
        });
        $(".task_total_amount").val((total.toFixed(2)));
    }
    
    function project_total_qty(){
        total = 0;
        $('.task_qty').each(function() {
            var this_qty = $(this).val();
            this_qty = (this_qty === '') ? 0 : this_qty;
            var this_qty = parseFloat(this_qty);
            total = total + this_qty;
        });
        $(".task_total_qty").val((total.toFixed(2)));
    }
    function task_BtnAdd() {
        /* Add Button */
        var newRow = $("#task_TRow").clone();
        newRow.removeClass("d-none");
        newRow.find("input, select,textarea").val('').attr('name', function(index, name) {
            return name.replace(/\[\d+\]/, '[' + ($('#task_TBody tr').length) + ']');
        });
        newRow.find("th").first().html($('#task_TBody tr').length + 1);
        newRow.appendTo("#task_TBody");
        newRow.find(".common-select2").select2();
    }
</script>
@endpush

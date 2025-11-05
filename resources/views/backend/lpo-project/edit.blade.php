@extends('layouts.backend.app')
@push('css')
@include('layouts.backend.partial.style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
<style>
    body{
		    counter-reset: Serial;
	}
    .project-btn{
        border: none;
        color: #fff;
        font-size: 15px;
        font-weight: 500px;
        padding:3px 10px;
        border-radius: 5px;
    }
    .add_items{
        background: #4CB648;
    }
    .delete_items{
        background: #EA5455;
        padding:3px 3px 2px 3px;
        font-size: 13px;
    }

    #input-container .form-control{
        border: none;
        height: 30px !important;
    }
    #input-container .form-control:focus{
        border: 1px solid #4CB648;
    }
    .tasks-title, .budget-title{
        font-size: 16px;
        color: #313131;
        font-weight: 500;
        text-transform: capitalize;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-size: 16px !important;
    }

    .select2-container--default .select2-selection--single {
        height: 35px !important;
    }
    .add-customer{
        background: #4A47A3;
        padding:2px 4px !important;
        margin:0 !important;
    }
    .save-btn{
        background: #406343;
    }
    input.form-control{
        height: 35px !important;
    }

    .sub-btn{
        border:1px solid #475F7B !important;
        background-color: #fff !important;
        /* border-radius: 15px !important; */
        border-radius: 8px !important;
        color: #475F7B !important;
        padding: 3px 6px 3px 6px !important;
    },
    .action-btn{
        background-color: #5F6F94;
        height: 35px;
    }
    .sub-btn:hover,
    .sub-btn.active{
        background-color: #34465b  !important;
        color:white  !important;
    }
    .sub-btn.active:hover{
        background-color: #c8d6e357  !important;
        color:black  !important;
    }
    .form-control,
    .project-btn{
        height: 30px;
    }
    .date_type:focus,
    .date_type:active{
        border: border 1px solid #313131;
    }
    .note-editor p{
        line-height: 23px !important;
        margin: 0;
    }
    thead{
        background: #34465b;
        color: #fff !important;
    }
    tr th{
        color: #fff !important;
    }
</style>
@endpush

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.project._header')
            <div class="tab-content bg-white">
                <div id="journaCreation" class="tab-pane active">

                    <section class="p-1" id="widgets-Statistics">
                        <div class="row">
                            <div class="col-7 d-flex justify-content-between">
                                <div class="d-flex">
                                    @if(Auth::user()->hasPermission('ProjectManagement_Create'))
                                    <a href="{{ route('lpo-projects.create') }}" class="project-btn sub-btn">&nbsp;&nbsp;&nbsp; Create &nbsp;&nbsp;&nbsp;</a>
                                    <a href="#" class="project-btn sub-btn active ml-1">&nbsp;&nbsp;&nbsp; Edit &nbsp;&nbsp;&nbsp;</a>
                                    @endif
                                    <form action="{{ route('lpo-projects.index') }}" method="get" class="ml-1">
                                        <input type="hidden" value="new" name="quotation">

                                        <button type="submit" class="project-btn sub-btn bg-dark">&nbsp;&nbsp; View All &nbsp;&nbsp;</button>
                                    </form>

                                    {{-- <form action="{{ route('lpo-projects.index') }}" method="get" class="ml-1">
                                        <input type="hidden" value="old" name="quotation">
                                        <button type="submit" class="project-btn sub-btn bg-dark  {{ $active_btn == 'old' ? 'active' : '' }}"> Quatation <small>(WO)</small>   </button>
                                    </form> --}}
                                </div>
                            </div>
                            <div class="col-5 d-flex justify-content-end" >
                            </div>
                        </div>
                        <form class="repeater mt-1" action="{{ route('lpo-projects.update',$lpo_project->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Customer Name </label>
                                        <div class="d-flex">
                                            <select name="customer_id" required id="customer_id"
                                                    class="form-control customer_id @error('customer_id') is-invalid @enderror">
                                                <option value="">Select Customer</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                            {{ old('customer_id', $lpo_project->customer_id) == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->pi_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('customer_id')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for=""> Project Name </label>
                                        <select name="project_id" id="project_id" required class="form-control project_id common-select2">
                                            <option value="{{$lpo_project->project_id}}"> {{optional(optional($lpo_project->boq)->project)->name}} </option>
                                        </select>
                                        @error('project_name')
                                            <p class="text-danger"> {{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Site/Delivery</label>
                                        <input type="text" name="site_delivery"
                                               value="{{ $lpo_project->site_delivery }}" autocomplete="off"
                                               class="form-control @error('site_delivery') is_invalid @enderror"
                                               placeholder="Site delivery...">
                                        @error('site_delivery')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 d-none">
                                    <div class="form-group">
                                        <label for="">Attention</label>
                                        <input type="text" name="attention" id="attention"
                                               value="{{ $lpo_project->attention }}" autocomplete="off"
                                               class="form-control @error('attention') is_invalid @enderror"
                                               placeholder="Attention...">
                                        @error('attention')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Mobile Number</label>
                                        <input type="text" name="mobile_no" id="mobile_no"
                                               value="{{ $lpo_project->mobile_no }}" autocomplete="off"
                                               class="form-control @error('mobile_no') is_invalid @enderror"
                                               placeholder="Mobile number..." required>
                                        @error('mobile_no')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Estimated Start Date</label>
                                        <input type="text" name="start_date"
                                               value="{{ $lpo_project->start_date ? date('d/m/Y', strtotime($lpo_project->start_date)) : '' }}"
                                               class="date form-control @error('start_date') is-invalid @enderror" autocomplete="off">
                                        @error('start_date')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Estimated End Date</label>
                                        <input type="text" name="end_date"
                                               value="{{ $lpo_project->end_date ? date('d/m/Y', strtotime($lpo_project->end_date)) : '' }}"
                                               class="date form-control @error('end_date') is-invalid @enderror" autocomplete="off">
                                        @error('end_date')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for=""> Subject </label>
                                        <textarea name="project_description" cols="30" rows="2" placeholder="Description max 200 characters" maxlength="100"
                                                  class="form-control @error('project_description') is-invalid @enderror" required>{{ old('project_description', $lpo_project->project_description) }}</textarea>
                                        @error('project_description')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <table class="table  table-sm ">
                                <thead>
                                    <tr>
                                        <th >Description</th>
                                        <th style="width: 10%">QTY</th>
                                        <th style="width: 10%">SQM</th>
                                        <th style="width: 10%">Rate</th>
                                        <th style="width: 15%">Total Amount</th>
                                        <th class="NoPrint" style="width: 20px;padding: 2px;">
                                            <button type="button" class="btn btn-sm btn-success addBtn"style="border: 1px solid green; color: #fff; border-radius: 10px;padding: 5px;" onclick="BtnAdd('#TRow', '#TBody','group-a')">
                                                <i class="bx bx-plus" style="color: white;margin-top: -5px;"></i>
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="TBody">
                                    @foreach ($lpo_project->items as $key => $item)
                                        <tr class="text-center invoice_row">
                                            <td>
                                                <div class="d-flex justy-content-between align-items-center" >
                                                    <input type="text" name="group-a[{{$key}}][description]" value="{{$item->item_description}}"  placeholder="Item Description" class="form-control inputFieldHeight description" required>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" step="any" name="group-a[{{$key}}][qty]" step="any" value="{{$item->qty}}" placeholder="QTY" class="text-center form-control inputFieldHeight qty"style="width: 100%;" required>
                                            </td>
                                            <td>
                                                <input type="number" step="any" name="group-a[{{$key}}][sqm]" step="any" placeholder="SQM" value="{{$item->sqm}}" class="text-center form-control inputFieldHeight sqm"style="width: 100%;" required>
                                            </td>
                                            <td>
                                                <input type="number" step="any" name="group-a[{{$key}}][amount]" step="any" required placeholder="Rate" value="{{$item->rate}}" class="text-center form-control inputFieldHeight amount"style="width: 100%;">
                                            </td>

                                            <td>
                                                <input type="number" step="any" name="group-a[{{$key}}][sub_gross_amount]" required value="{{$item->total}}" class="text-center form-control sub_gross_amount inputFieldHeight" placeholder="Total Amount" style="width: 100%;" readonly>
                                            </td>
                                            <td class="NoPrint add_button text-center d-flex" style="margin-top: 5px;">
                                                <button type="button" class="bg-danger custom-btn" onclick="BtnDelItem(this)">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr id="TRow" class="text-center invoice_row d-none">
                                        <td>
                                            <div class="d-flex justy-content-between align-items-center" >
                                                <input type="text" name="group-a[{{count($lpo_project->tasks)+1}}][description]" disabled  placeholder="Item Description" class="form-control inputFieldHeight description" required>
                                            </div>
                                        </td>

                                        <td>
                                            <input type="number" step="any" name="group-a[{{count($lpo_project->tasks)+1}}][qty]" step="any" placeholder="QTY" class="text-center form-control inputFieldHeight qty"style="width: 100%;" disabled required>
                                        </td>
                                        <td>
                                            <input type="number" step="any" name="group-a[{{count($lpo_project->tasks)+1}}][sqm]" step="any" placeholder="SQM" class="text-center form-control inputFieldHeight sqm"style="width: 100%;" disabled required>
                                        </td>
                                        <td>
                                            <input type="number" step="any" name="group-a[{{count($lpo_project->tasks)+1}}][amount]" step="any" required placeholder="Rate" disabled class="text-center form-control inputFieldHeight amount"style="width: 100%;">
                                        </td>

                                        <td>
                                            <input type="number" step="any" name="group-a[{{count($lpo_project->tasks)+1}}][sub_gross_amount]" required disabled class="text-center form-control sub_gross_amount inputFieldHeight" placeholder="Total Amount" style="width: 100%;" readonly>
                                        </td>
                                        <td class="NoPrint add_button text-center d-flex" style="margin-top: 5px;">
                                            <button type="button" class="bg-danger custom-btn" onclick="BtnDelItem(this)">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <td class="text-right pr-1" colspan="4" style="color: black">AMOUNT</td>
                                        <td><input type="number" step="any" readonly
                                                id="taxable_amount"
                                                class="text-center form-control inputFieldHeight2 @error('taxable_amount') error @enderror inputFieldHeight taxable_amount"
                                                name="taxable_amount" value="{{$lpo_project->budget}}"
                                                placeholder="AMOUNT" readonly required>
                                            @error('taxable_amount')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </td>
                                    </tr>

                                    <tr class="text-center">
                                        <td class="text-right pr-1" colspan="4" style="color: black">VAT 5%</td>
                                        <td><input type="number" step="any" readonly
                                                id="total_vat"
                                                class="text-center inputFieldHeight2 form-control @error('total_vat') error @enderror inputFieldHeight total_vat"
                                                name="total_vat" value="{{$lpo_project->vat}}"
                                                placeholder="@if (!empty($currency->vat_name)) {{ $currency->vat_name }} @endif SUBTOTAL"
                                                readonly required>
                                            @error('total_vat')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-right pr-1" colspan="4" style="color: black">TOTAL AMOUNT</td>
                                        <td><input type="number" step="any" readonly
                                                id="total_amount"
                                                class="text-center inputFieldHeight2 form-control @error('total_amount') error @enderror inputFieldHeight total_amount"
                                                name="total_amount" value="{{$lpo_project->total_budget}}"
                                                placeholder="TOTAL " readonly required>
                                            @error('total_amount')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="">
                                <div class="form-group mt-1">
                                    <label for="">Terms & Conditions</label>
                                    <textarea name="project_term" cols="30" rows="6" placeholder="Terms & Conditions max 200 characters"
                                              class="form-control summernote @error('project_term') is-invalid @enderror">{{ $lpo_project->project_term }}</textarea>
                                    @error('project_term')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row d-flex align-items-center">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for=""> Voucher File Upload  </label>
                                        <input
                                            class="form-control  @error('voucher_file') is-invalid @enderror" type="file" name="voucher_file" style="padding: 0px !important; border:none" accept="application/pdf,image/png,image/jpeg,application/msword" >
                                        @error('voucher_file')
                                            <p class="text-danger"> {{ $message }}</p>
                                        @enderror
                                    </div>

                                </div>
                                <div class="col-12">
                                    @if(Auth::user()->hasPermission('ProjectManagement_Edit'))
                                    <div class="d-flex justify-content-center mt-1">
                                        <button type="submit" class="project-btn save-btn"> Save </button>
                                    </div>
                                    @endif
                                </div>
                             </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add-customer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header" style="padding: 5px 15px;">
            <h5 class="modal-title" id="exampleModalLabel"> Create Party </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" style="padding: 5px 15px;">
            <div class="d-flex">
                <div class="form-group w-50">
                    <label for=""> Party Name </label>
                    <input type="text" name="pi_name" class="form-control" required>
                    <p class="error-pi_name text-danger"> </p>
                </div>
                <div class="form-group w-50 ml-1">
                    <label for=""> Contact Person </label>
                    <input type="text" name="con_person" class="form-control" required>
                    <p class="error-con_person text-danger"></p>
                </div>
            </div>
            <div class="d-flex">
                <div class="form-group w-50">
                    <label for=""> Party Type </label>
                    <select name="pi_type" class="form-control">
                        <option selected> Customer </option>

                    </select>
                    <p class="error-pi_type text-danger"></p>
                </div>
                <div class="form-group w-50 ml-1">
                    <label for=""> Mobile Phone Number </label>
                    <input type="text" name="phone_no" class="form-control" required>
                    <p class="error-phone_no text-danger"></p>
                </div>
            </div>
            <div class="d-flex">
                <div class="form-group w-50">
                    <label for=""> TRN No </label>
                    <input type="text" name="trn_no" class="form-control" required>
                    <p class="error-trn_no text-danger"></p>
                </div>
                <div class="form-group w-50 ml-1">
                    <label for=""> Phone Number </label>
                    <input type="text" name="con_no" class="form-control" required>
                    <p class="error-con_no text-danger"></p>
                </div>
            </div>

            <div class="d-flex">
                <div class="form-group w-50">
                    <label for=""> Address </label>
                    <input type="text" name="address" class="form-control" required>
                    <p class="error-address text-danger"></p>
                </div>
                <div class="form-group w-50 ml-1">
                    <label for=""> Email </label>
                    <input type="text" name="email" class="form-control" required>
                    <p class="error-email text-danger"></p>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="padding: 5px 15px;">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary create-party"> Create </button>
        </div>
    </div>
</div>
</div>
<div class="modal fade" id="add-project" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px;">
                <h5 class="modal-title" id="exampleModalLabel"> New Project </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 5px 15px;">
                <div class="d-flex">
                    <div class="form-group w-100">
                        <label for=""> Project Name </label>
                        <input type="text" name="name" class="form-control" required>
                        <p class="error-pi_name text-danger"> </p>
                    </div>
                    <button type="button" class="btn btn-primary create-project mt-2 mb-3 ml-1"> Submit </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        BtnAdd('#TRow', '#TBody', 'group-a');
        function BtnAdd(trow, tbody, inputs) {
            var $trow = $(trow);
            var $tbody = $(tbody);
            var newRow = $trow.clone().removeClass("d-none").removeAttr('id');
            newRow.find("input, select, textarea").prop('disabled', false);
            newRow.find("select").addClass('commom-select3');
            newRow.find(".date-").addClass('datepicker');

            var $rows = $tbody.children('tr').not($trow);
            var lastIndex = 0;
            var lastRow = $rows.last();
            // Dynamic input handling
            var lastInputName = lastRow.find("input[name^='" + inputs + "']").attr('name');
            var lastSelectName = lastRow.find("select[name^='" + inputs + "']").attr('name');
            var lastTextName = lastRow.find("textarea[name^='" + inputs + "']").attr('name');
            var lastName = lastInputName || lastSelectName || lastTextName;

            var match = lastName && lastName.match(/\[(\d+)\]/);
            if (match) {
                lastIndex = parseInt(match[1], 10);
            }

            var newIndex = lastIndex + 1;

            newRow.find("input, select, textarea").attr('name', function(index, name) {
                return name.replace(/\[\d+\]/, '[' + newIndex + ']');
            });
            
            newRow.find('.description').val('');
            newRow.find('.qty').val('');
            newRow.find('.sqm').val('');
            newRow.find('.rate').val('');
            newRow.find('.amount').val('');
            newRow.find('.sub_gross_amount').val('');

            newRow.appendTo(tbody);

            // Initialize select2 on the newly added row
            newRow.find(".commom-select3").each(function() {
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
                $(this).select2();
            });
        }
        function BtnDelItem(v) {
            $(v).parent().parent().remove();
            $("#TBody").find("tr").each(function(index) {
                $(this).find("th").first().html(index);
            });
            total();
        }
        $(document).on('keyup', '.sqm, .amount', function(e){
            tr = $(this).closest('tr');
            rate = tr.find('.amount').val();
            sqm = tr.find('.sqm').val();
            amount = Number(sqm)*Number(rate);
            sub_gross_amount = tr.find('.sub_gross_amount').val(amount.toFixed(2));
            total();
        });
        function total() {
            var sum = 0;
            $('.sub_gross_amount').each(function() {
                var this_amount = $(this).val();
                this_amount = (this_amount === '') ? 0 : this_amount;
                var this_amount = parseFloat(this_amount);
                sum = sum + this_amount;
            });
            var taxable = sum.toFixed(2);
            var vat = taxable*0.05;
            var total = (vat * 1) + (taxable * 1)
            $(".taxable_amount").val(taxable);
            $(".total_vat").val(vat.toFixed(2));
            $(".total_amount").val((total.toFixed(2)));
        };
        $(document).ready(function() {
            $('.summernote').summernote();
            console.log($('.summernote').val());
            $('.date').datepicker({dateFormat:'dd/mm/yy'})
            $('.customer_id').select2();
            $(".add_items").click(function () {
                addInput();
            });
        });

        $(document).on('change', '#customer_id', function(){
            var party_id = $(this).val();
            var url = "{{route('get.party.boq',':party_id')}}";
            url = url.replace(':party_id', party_id);

            $.ajax({
                url:url,
                type:'GET',
                success:function(res){
                    $('#project_id').html('<option value=""> Select </option>');
                    res.boqs.forEach(function(boq){
                        let boqJson = JSON.stringify(boq).replace(/"/g, '&quot;');
                        $('#project_id').append(`<option value="${boq.id}" data-boq="${boqJson}"> ${boq.project.name} </option>`);
                    });
                }
            });
        });

        let tasks = @json($tasks);
        let taskIndex = '{{$key + 1}}';

        $('#addTask').on('click', function () {
            const itemIndex = 0;
            var project = $('#project_id').val();
            if(!project){
                toastr.warning('Project are missing. First select project');
                return;
            }

            let taskOptions = '<option value=""> Select </option>';
                tasks.forEach(function (task) {
                taskOptions += `<option value="${task.id}" data-items='${JSON.stringify(task.items)}'>${task.name}</option>`;
            });

            const taskRow = `
                <tr class="task-row" data-task-index="${taskIndex}">
                <td style="width:15%;">
                    <div class="d-flex">
                        <select name="task_id[${taskIndex}]" class="form-control task_id inputFieldHeight" required>
                            ${taskOptions}
                        </select>
                        <input type="text" name="task_name[${taskIndex}]" class="form-control d-none task-name" placeholder="Description">
                        <button type="button" class="task-toggler bg-info text-white" style="border: 1px solid #ddd;" title="Add Item"> <i class='bx bx-refresh'></i> </button>
                    </div>
                </td>
                <td colspan="8" style="width:85%;">
                    <table class="table table-sm mb-0" style="width:100%;">
                        <tbody class="item-body">
                            <tr>
                                <td style="width:25%"><input type="text" name="item_description[${taskIndex}][${itemIndex}]" class="form-control" placeholder="Description"></td>
                                <td style="width:10%"><input type="text" name="unit[${taskIndex}][${itemIndex}]" class="form-control text-center" placeholder="Unit" required></td>
                                <td style="width:10%"><input type="number" name="qty[${taskIndex}][${itemIndex}]" class="form-control qty text-right" placeholder="Qty"></td>
                                <td style="width:10%"><input type="text" name="rate[${taskIndex}][${itemIndex}]" class="form-control rate text-right" placeholder="Rate"></td>
                                <td style="width:10%"><input type="text" name="amount[${taskIndex}][${itemIndex}]" class="form-control total text-right" placeholder="Amount" readonly required></td>
                                <td style="width:10%"><input type="text" name="expense[${taskIndex}][${itemIndex}]" class="form-control expense text-right" placeholder="Expense" required></td>
                                <td style="width:5%">
                                    <div class="d-flex">
                                        <button type="button" class="addItemBtn bg-info text-white" style="border: 1px solid #ddd;" title="Add Item">+</button>
                                        <button type="button" class="removeItemBtn bg-danger text-white" style="border: 1px solid #ddd;" title="Remove Item">X</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width:5%;">
                    <button type="button" class="removeTaskBtn bg-danger text-white" style="border: 1px solid #ddd;" title="Remove Task" >X</button>
                </td>
                </tr>
            `;

            $('#boq-body').append(taskRow);
            taskIndex++;
        });

        function addItem(item = null, taskRow = null){
            const $taskRow = taskRow  || (this).closest('.task-row');
            const taskIndex = $taskRow.data('task-index');
            const $itemBody = $taskRow.find('.item-body');
            const itemIndex = $itemBody.children('tr').length;

            const itemRow = `
            <tr>
                <td style="width:25%"><input type="text" name="item_description[${taskIndex}][${itemIndex}]" value="${item.item_description}" class="form-control" placeholder="Description"></td>
                <td style="width:10%"><input type="text" name="unit[${taskIndex}][${itemIndex}]" class="form-control text-center" placeholder="Unit" value="" required></td>
                <td style="width:10%"><input type="number" name="qty[${taskIndex}][${itemIndex}]" class="form-control text-right qty" placeholder="Qty" value="" required></td>
                <td style="width:10%"><input type="text" name="rate[${taskIndex}][${itemIndex}]" class="form-control text-right rate" placeholder="Rate" value="" required></td>
                <td style="width:10%"><input type="text" name="amount[${taskIndex}][${itemIndex}]" class="form-control text-right total" placeholder="Amount" readonly value="" readonly></td>
                <td style="width:10%"><input type="text" name="expense[${taskIndex}][${itemIndex}]" class="form-control expense text-right" placeholder="Expense" required value=""></td>
                <td style="width:5%">
                    <div class="d-flex">
                        <button type="button" class="addItemBtn bg-info text-white" style="border: 1px solid #ddd;" title="Add Item">+</button>
                        <button type="button" class="removeItemBtn bg-danger text-white" style="border: 1px solid #ddd;" title="Remove Item">X</button>
                    </div>

                </td>
            </tr>
            `;

            $itemBody.append(itemRow);
        }

        $(document).on('change', '.task_id', function () {
            const $taskRow = $(this).closest('.task-row');
            const $itemBody = $taskRow.find('.item-body');
            $itemBody.empty(); // Clear old items

            const selectedOption = $(this).find('option:selected');
            const itemsJson = selectedOption.data('items');

            if (Array.isArray(itemsJson)) {
                itemsJson.forEach(function (item, index) {
                    addItem(item, $taskRow);
                });
            }
        });


        // Remove task
        $(document).on('click', '.removeTaskBtn', function () {
            $(this).closest('.task-row').remove();
            calculateTotal();
        });

        $(document).on('click', '.removeItemBtn', function () {
            $(this).closest('tr').remove();
            calculateTotal();
        });

        // Add item
        $(document).on('click', '.addItemBtn', function () {
            const $taskRow = $(this).closest('.task-row');
            const taskIndex = $taskRow.data('task-index');
            const $itemBody = $taskRow.find('.item-body');
            const itemIndex = $itemBody.children('tr').length;

            const itemRow = `
            <tr>
                <td style="width:25%"><input type="text" name="item_description[${taskIndex}][${itemIndex}]" class="form-control" placeholder="Description" required></td>
                <td style="width:10%"><input type="text" name="unit[${taskIndex}][${itemIndex}]" class="form-control  text-center" placeholder="Unit" required></td>
                <td style="width:10%"><input type="number" name="qty[${taskIndex}][${itemIndex}]" class="form-control qty text-right" placeholder="Qty" required></td>
                <td style="width:10%"><input type="text" name="rate[${taskIndex}][${itemIndex}]" class="form-control rate text-right" placeholder="Rate" required></td>
                <td style="width:10%"><input type="text" name="amount[${taskIndex}][${itemIndex}]" class="form-control total text-right" placeholder="Amount" required></td>
                <td style="width:10%"><input type="text" name="expense[${taskIndex}][${itemIndex}]" class="form-control expense text-right" placeholder="Expense" required readonly></td>
                <td style="width:5%">
                    <div class="d-flex">
                        <button type="button" class="addItemBtn bg-info text-white" style="border: 1px solid #ddd;" title="Add Item">+</button>
                        <button type="button" class="removeItemBtn bg-danger text-white" style="border: 1px solid #ddd;" title="Remove Item">X</button>
                    </div>

                </td>
            </tr>
            `;

            $itemBody.append(itemRow);
        });


        function calculateTotal(){
            var subtotal = 0;
            var total_expense = 0;
            $('.qty').each(function(){
                var qty = parseFloat($(this).val());
                var rate = parseFloat($(this).closest('tr').find('.rate').val());
                var total = qty * rate;
                subtotal += total;
                $(this).closest('tr').find('.total').val(total.toFixed(2));
            })

            $('.subtotal').val(parseFloat(subtotal).toFixed(2))
            let discount = parseFloat($('.discount').val());
            if(discount > 0){
                $('.total_amount').val(parseFloat(subtotal - discount).toFixed(2))
                $('.total_amount').attr('min', 1)
                $('.total_amount').attr('max' , parseFloat(subtotal - discount).toFixed(2))
            }else{
                $('.total_amount').val(parseFloat(subtotal).toFixed(2))
                $('.total_amount').attr('min', 1)
                $('.total_amount').attr('max' , parseFloat(subtotal).toFixed(2))
            }
        }

        $(document).on('keyup', '.qty, .rate', function () {
            calculateTotal();
        });

        $(document).on("keyup", ".discount", function(event) {
            calculateTotal();
        });


        $(document).on('click', '.task-toggler', function () {
            var td = $(this).closest('td');
            var task_id = td.find('.task_id');
            var task_name = td.find('.task-name');

            if(task_id.hasClass('d-none')) {
                task_id.removeClass('d-none').prop('disabled', false).prop('required', true);
                task_name.addClass('d-none').prop('disabled', true).prop('required', false);
            } else {
                task_id.addClass('d-none').prop('disabled', true).prop('required', false);
                task_name.removeClass('d-none').prop('disabled', false).prop('required', true);
            }
        });
    </script>
@endpush

@extends('layouts.backend.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@section('content')
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
        height: 15px !important;
        text-align: center !important;
    }
    td
    {
        font-size: 12px !important;
        height: 25px !important;
    }

    .table-sm th, .table-sm td {
        padding: 0rem;
    }

    .card-body {
        flex: 1 1 auto;
        min-height: 1px;
        padding-top: 0rem;
        padding-bottom: 0rem;

        padding-left: 1.7rem;
        padding-right: 1.7rem;

    }
    .select2-results__option{
        /* background: #da7d7d4b !important; */
        padding: 0 5px !important;
    }

    .file-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px;
        border: 1px solid #ddd;
        margin-bottom: 5px;
    }

    .delete-btn {
        background: #ff4444;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        cursor: pointer;
    }

    .delete-btn:hover {
        background: #cc0000;
    }
</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.accounting._header', ['activeMenu' =>'jouranal-creation'])
            <div class="tab-content bg-white">
                <div id="journalList" class="tab-pane active p-2">
                    <input type="hidden" name="standard_vat_rate" value="{{$standard_vat_rate}}"  id="standard_vat_rate">
                    <section id="widgets-Statistics">
                        @isset($journalF)
                        <form action="{{ route('journalEntryEditPost', $journalF) }}" method="POST" enctype="multipart/form-data">
                        @else
                            <form action="{{ route('journalEntryPost') }}" method="POST" enctype="multipart/form-data" id="form_submit">
                            @endisset
                            @csrf
                            <div class="col-md-12">
                                <div class="row my-1">
                                    <div class="cardStyleChange " style="width: 100%">
                                        <div class="card-body bg-white " style="padding: 0px !important;">
                                            <table class="table table-bordered table-sm mb-0">
                                                <thead>
                                                    <tr >
                                                        <th style="width: 25%">A/C Head</th>
                                                        <th>Description</th>
                                                        <th style="width: 15%">Debit Amount</th>
                                                        <th style="width: 15%">Credit Amount</th>
                                                        <th style="width: 10%" class="NoPrint"> <button type="button" class="btn btn-sm btn-success "style="border: 1px solid #fff;
                                                            color: #fff; border-radius: 10px;padding: 5px; margin: 4px;" onclick="BtnAdd('#TRow', '#TBody','group-a')"><i class="bx bx-plus" style="color: white;margin-top: -5px;"></i></button>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="TBody">
                                                    <tr id="TRow" class="d-none">
                                                        <td>
                                                             <select name="group-a[0][multi_acc_head]" disabled required  class="account_head_change text-center job_group_id form-control multi-acc-head input-due-payment inputFieldHeight2" style="width: 100%;    height: 36PX;">
                                                                <option value="">Select...</option>
                                                                @foreach ($acHeads as $item)
                                                                    <option value="{{$item->id}}">{{$item->fld_ac_head}}</option>
                                                                    @foreach ($item->sub_heads as $sub_head)
                                                                        <option value="Sub{{$sub_head->id}}" class="sub-head"> &nbsp;&nbsp;&nbsp; |---{{$sub_head->name}}</option>
                                                                    @endforeach
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="text" disabled name="group-a[0][description]"  class="text-center form-control inputFieldHeight2 "style="width: 100%;height:36px;"></td>
                                                        <td><input type="number" disabled name="group-a[0][debit_amount]" step="any"  class="text-center form-control inputFieldHeight2 feild_amount debit_amount" style="width: 100%;height:36px;"></td>
                                                        <td><input type="number" disabled name="group-a[0][credit_amount]" step="any"  class="text-center form-control inputFieldHeight2 feild_amount credit_amount" style="width: 100%;height:36px;"></td>
                                                        <td class="NoPrint text-center"><button style="padding: 5px; margin: 4px;" type="button" class="btn btn-sm btn-danger" onclick="BtnDel(this)"><i class="bx bx-trash" style="color: white;margin-top: -5px;"></i></button></td>
                                                    </tr>
                                                </tbody>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="1"></td>
                                                        <td class="text-center" style="color: black">TOTAL</td>
                                                        <td>
                                                            <input type="text" readonly id="total_debit" value="0.00" class="text-center form-control inputFieldHeight2 @error('total_debit') error @enderror inputFieldHeight total"
                                                             name="total_debit"  placeholder="TOTAL" readonly required>
                                                            @error('total_debit')
                                                            <span class="error">{{ $message }}</span>
                                                            @enderror
                                                        </td>
                                                            <td>
                                                                <input type="text" readonly id="total_credit" value="0.00" class="text-center form-control inputFieldHeight2 @error('total_credit') error @enderror inputFieldHeight total"
                                                                name="total_credit"  placeholder="TOTAL" readonly required>
                                                               @error('total_credit')
                                                               <span class="error">{{ $message }}</span>
                                                               @enderror
                                                            </td>
                                                    </tr>
                                                    <tr class="d-none">
                                                        <td colspan="2"></td>
                                                        <td class="text-center" style="color: black">@if(!empty($currency->vat_name)){{$currency->vat_name}} @endif SUBTOTAL</td>
                                                        <td><input type="text" readonly id="vat_subtotal" class="text-center form-control  @error('vat_subtotal') error @enderror inputFieldHeight2 gst_subtotal"
                                                             name="vat_subtotal"  value="0.00" placeholder="@if(!empty($currency->vat_name)){{$currency->vat_name}} @endif SUBTOTAL" readonly required>
                                                            @error('vat_subtotal')
                                                            <span class="error">{{ $message }}</span>
                                                            @enderror</td>
                                                    </tr>
                                                    <tr class="d-none">
                                                        <td colspan="2"></td>
                                                        <td class="text-center" style="color: black">@if(!empty($currency->vat_name)){{$currency->vat_name}} @endif TOTAL</td>
                                                        <td><input type="text" readonly id="gst_total" class="text-center form-control @error('gst_total') error @enderror inputFieldHeight2 gst_total"
                                                             name="gst_total" value="0.00" placeholder="@if(!empty($currency->vat_name)){{$currency->vat_name}} @endif TOTAL " readonly required>
                                                            @error('gst_total')
                                                            <span class="error">{{ $message }}</span>
                                                            @enderror</td>
                                                    </tr>
                                                    <tr class="d-none">
                                                        <td colspan="2"></td>
                                                        <td class="text-center " style="color: black">@if(!empty($currency->vat_name)){{$currency->vat_name}} @endif FREE TOTAL</td>
                                                        <td><input type="text" readonly id="gst_free_total" class="text-center form-control @error('free_amount') error @enderror inputFieldHeight2 gst_free_total"
                                                             name="free_amount"  value="0.00" placeholder="@if(!empty($currency->vat_name)){{$currency->vat_name}} @endif TOTAL " readonly required>
                                                            @error('free_amount')
                                                            <span class="error">{{ $message }}</span>
                                                            @enderror</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="cardStyleChange">
                                <div class="card-body bg-white">
                                    <div class="row">
                                        <div class="col-md-2 changeColStyle" id="printarea">
                                            <label for="">Date</label>
                                            <input type="text" value="{{ Carbon\Carbon::now()->format('d/m/Y') }}" class="form-control inputFieldHeight datepicker" name="date"  placeholder="dd/mm/yyyy" >
                                            @error('date')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="">Narration</label>
                                            <input type="text" class="form-control inputFieldHeight" name="narration"
                                                id="narration" placeholder="Narration"
                                                value="{{ isset($journalF) ? $journalF->narration : '' }}"
                                                required>
                                        </div>

                                        <div class="col-sm-4 form-group">
                                            <label for="">Voucher Scan/File</label>
                                            <input type="file" class="form-control inputFieldHeight" name="voucher_scan[]" accept="image/*,application/pdf" multiple id="fileInput">
                                        </div>
                                        <div class="col-md-12" id="fileList">
                                            <div class="col-md-12"></div>
                                        </div>
                                        <div class="col-md-12 text-right d-flex justify-content-center align-items-center mt-2" >
                                            <button type="submit" class="btn btn-primary formButton" title="Add">
                                                <div class="d-flex align-items-center">
                                                    <div class="formSaveIcon">
                                                        <img  src="{{asset('assets/backend/app-assets/icon/save-icon.png')}}" alt=""  srcset=""  height="15">
                                                    </div>
                                                    <div><span>Save</span></div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- modal --}}
    <!-- END: Content-->
    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div id="voucherPreviewShow">

            </div>
          </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="voucherDetailsPrintModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div id="voucherDetailsPrint">

            </div>
          </div>
        </div>
    </div>
    <div class="modal fade" id="party_amount_add_model" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div id="party_amount_add">

                </div>

            </div>
        </div>
    </div>
@endsection
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/select/form-select2.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/form-repeater.js"></script>
{{-- js work by mominul start --}}
<script>

    $('#transection_type').change(function() {
        if ($(this).val() != '') {
            var value = $(this).val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('transection-heads') }}",
                method: "POST",
                data: {
                    value: value,
                    _token: _token,
                },
                success: function(response) {
                    $(".transection-heads").empty().append(response);
                }
            })
        }
    });
    $(document).on('keyup', '.feild_amount', function(){
        var total_debit = 0;
        $('.debit_amount').each(function(){
            total_debit += Number($(this).val());
        })
        var total_credit = 0;
        $('.credit_amount').each(function(){
            total_credit += Number($(this).val());
        })
        $('#total_debit').val(total_debit.toFixed(2));
        $('#total_credit').val(total_credit.toFixed(2));
    })
    $(document).on("keyup", ".feild_amount", function(e) {
        var index = $(this).closest('tr').index();
        var qty1 = parseFloat($(".debit_amount").eq(index).val());
        var qty2 = parseFloat($(".credit_amount").eq(index).val());
        var qty3 = parseFloat($(".fluat").eq(index).val());
            if(!isNaN(qty1))
            {
            $(this).closest('tr').find('.credit_amount').prop('readonly', true);
            $(this).closest('tr').find('.fluat').prop('readonly', true);
            }
            else if(!isNaN(qty2))
            {
            $(this).closest('tr').find('.debit_amount').prop('readonly', true);
            $(this).closest('tr').find('.fluat').prop('readonly', true);
            }
            else if(!isNaN(qty3))
            {
            $(this).closest('tr').find('.debit_amount').prop('readonly', true);
            $(this).closest('tr').find('.credit_amount').prop('readonly', true);
            }
            else
            {
            $(this).closest('tr').find('.debit_amount').prop('readonly', false);
            $(this).closest('tr').find('.credit_amount').prop('readonly', false);
            $(this).closest('tr').find('.fluat').prop('readonly', false);
            }
    });
    $(document).on('change', '.feild_amount', function(e){
        e.preventDefault();
        var current_tr = $(this).closest('tr');
        var head_id = $(current_tr).find('.account_head_change').val();
        var debit_amount = $(current_tr).find('.debit_amount').val();
        var credit_amount = $(current_tr).find('.credit_amount').val();
        var _token = $('input[name="_token"]').val();
        if(Number(credit_amount)>0 || Number(debit_amount)>0){
            $.ajax({
                url: "{{ route('account-head-type-check') }}",
                method: "POST",
                data: {
                    head_id:head_id,
                    debit_amount: debit_amount,
                    credit_amount: credit_amount,
                    _token: _token,
                },
                success: function(response) {
                    if(response != 0){
                        document.getElementById("party_amount_add").innerHTML = response;
                        $('#party_amount_add_model').modal('show');
                    }
                }
            })
        }
    })
    $('#form_submit').submit(function(e){
        e.preventDefault();
        var total_debit = $('#total_debit').val();
        var total_credit = $('#total_credit').val();
        if(Number(total_debit) != Number(total_credit)){
            toastr.warning('Debit and Credit amount not equal');
            return;
        }else{
            $('#form_submit')[0].submit();
        }
    })
    function task_BtnAdd() {
        var newRow = $("#task_TRow").clone();
        newRow.removeClass("d-none");
        newRow.find("input, select,textarea").val('').attr('name', function(index, name) {
            return name.replace(/\[\d+\]/, '[' + ($('#task_TBody tr').length) + ']');
        });
        newRow.find("th").first().html($('#task_TBody tr').length + 1);
        newRow.appendTo("#task_TBody");
        newRow.find(".common-select2").select2();
    }
    $(document).on('keyup', '.pay_amount', function(e){
        total = 0;
        $('.pay_amount').each(function() {
            var this_amount = $(this).val();
            this_amount = (this_amount === '') ? 0 : this_amount;
            var this_amount = parseFloat(this_amount);
            total = total + this_amount;
        });
        $(".pay_amount_total_amount").val((total.toFixed(2)));
    })
    $(document).on('submit', '#account_expense_store', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var data = new FormData(this);
        var task_total_amount = $('#pay_amount_total_amount').val();
        var max_amount = $('#max_amount').val();
        var task_total_qty = $('#task_total_qty').val();
        if(Number(task_total_amount) != Number(max_amount)){
            toastr.warning('Please check your amount');
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
                    $("#party_amount_add_model").modal('hide');
                    toastr.success('Add Success');
                }
            });
        }
    });
</script>
{{-- js work by mominul end --}}

<script>
    $(document).ready(function() {
        $('#pay_mode').change(function() {
            var value = $(this).val();
            if (value=="NonCash") {
                $('.non-cash-account-head').show();
                // $("#acc_head_2").focus();
                $('.common-select2').select2();

            } else {
                $('.non-cash-account-head').hide();
                $("#ac_code").focus();
            }
        });

        $('#party_info').change(function() {
            if ($(this).val() != '') {
                var value = $(this).val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('partyInfoInvoice2') }}",
                    method: "POST",
                    data: {
                        value: value,
                        _token: _token,
                    },
                    success: function(response) {
                        console.log(response);
                        $("#trn_no").val(response.trn_no);
                        $("#pi_code").val(response.pi_code);
                        $("#invoice_no").focus();

                    }
                })
            }
        });


        $(document).on("keyup", "#pi_code", function(e) {
            // alert(1);
            var value = $(this).val();
            var _token = $('input[name="_token"]').val();
            if ($(this).val() != '') {
            $.ajax({
                url: "{{ route('partyInfoInvoice3') }}",
                method: "POST",
                data: {
                    value: value,
                    _token: _token,
                },
                success: function(response) {
                    console.log(response);
                    var qty = 1;
                    if (response != '') {
                        $("div.search-item-pi select").val(response.id);
                        $('.common-select2').select2();
                        $("#trn_no").val(response.trn_no);
                        $("#invoice_no").focus();
                    }
                }
            })
        }
        });

    });

    BtnAdd('#TRow', '#TBody','group-a');
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

        newRow.appendTo(tbody);

        // Initialize select2 on the newly added row
        newRow.find(".commom-select3").each(function() {
            if ($(this).data('select2')) {
                $(this).select2('destroy');
            }
            $(this).select2();
        });
    }

    function BtnDel(v) {
        /* Delete Button */
        $(v).parent().parent().remove();
        sum_all_amount();

        $("#TBody").find("tr").each(function(index) {
            $(this).find("th").first().html(index);
        });

    }

    $(document).on("keyup", ".multi-tax-rate", function(e) {
        console.log(6)
        var amount1 = $(this).val();
        var standard_vat_rate=$('#standard_vat_rate').val();
        var vat_cal=standard_vat_rate*1;

        var gst1 = ((100+vat_cal)/vat_cal)*amount1;
        var value1 = gst1.toFixed(2)
        var selectedValue1 = $(this).closest("tr").find(".amount_without_vat").val(value1);
        gstotal();
        total();
    });

    $(document).on("keyup", ".amount_without_vat", function(e) {
        var amount = $(this).val();
        var standard_vat_rate=$('#standard_vat_rate').val();
        var gst = (amount*standard_vat_rate)/(100+(standard_vat_rate*1));
        var value = gst.toFixed(2)
        var selectedValue = $(this).closest("tr").find(".multi-tax-rate").val(value);
        gstotal();
        total();

    });

    $(document).on("keyup", ".amount_withvat", function(e) {
        var amount=$(this).val();
        $(this).closest("tr").find(".amount_without_vat").val(amount);
        var standard_vat_rate=$('#standard_vat_rate').val();
        var gst = (amount*standard_vat_rate)/(100+(standard_vat_rate*1));
        var value = gst.toFixed(2)
        var selectedValue = $(this).closest("tr").find(".multi-tax-rate").val(value);
        gstotal();
        total();
        sum_all_amount();
    });


    function sum_all_amount(){
        var sum=0;
        $('.amount_withvat').each(function() {
            var this_amount= $(this).val();
            this_amount = (this_amount === '') ? 0 : this_amount;
            this_amount= parseInt(this_amount);
            //
            sum = sum+this_amount;
        });
        console.log(sum);
        $('#total_amount').val(sum);
    }

    function gstotal() {
        var sum=0;
        $('.multi-tax-rate').each(function() {
            var this_amount1= $(this).val();
            //console.log(this_amount)
            this_amount1 = (this_amount1 === '') ? 0 : this_amount1;
            var this_amount1 = parseFloat(this_amount1);
            //
            sum = sum+this_amount1;
        });
        var result1 = sum.toFixed(2)
        // console.log(sum);
        $(".gst_total").val(result1);
    };

    function total() {
        var sum1=0;
        $('.amount_without_vat').each(function() {
            var this_amount12= $(this).val();
            //console.log(this_amount)
            this_amount12 = (this_amount12 === '') ? 0 : this_amount12;
            var this_amount12 = parseFloat(this_amount12);
            //
            sum1 = sum1+this_amount12;
        });
        var result12 = sum1.toFixed(2)
        // console.log(sum1);
        $("#vat_subtotal").val(result12);
    };

    let selectedFiles = [];

    document.getElementById('fileInput').addEventListener('change', function(e) {
        // Add new files to our array
        selectedFiles = selectedFiles.concat(Array.from(e.target.files));
        updateFileListDisplay();
        updateFileInput();
    });

    function updateFileListDisplay() {
        const fileList = document.getElementById('fileList');
        fileList.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            fileItem.innerHTML = `
                <span>${file.name}</span>
                <button class="delete-btn" data-index="${index}">×</button>
            `;
            fileList.appendChild(fileItem);
        });

        // Add delete functionality
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                selectedFiles.splice(index, 1);
                updateFileListDisplay();
                updateFileInput(); // Update the actual file input
            });
        });
    }

    function updateFileInput() {
        const fileInput = document.getElementById('fileInput');
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });
        fileInput.files = dataTransfer.files;
    }
</script>

@endpush

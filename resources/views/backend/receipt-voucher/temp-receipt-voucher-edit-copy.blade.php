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
</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.receipt._header',['activeMenu' => 'receipt_voucher'])
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active">
                    <div class="py-1 px-2">
                        @include('backend.receipt-voucher._subhead_receipt_voucher', [
                            'activeMenu' => 'create',
                        ])
                    </div>
                    <section id="widgets-Statistics">

                            <form action="{{ route('temp-receipt-voucher-update') }}" method="POST" enctype="multipart/form-data" >
                            @csrf
                            <input type="hidden" name="receipt_voucher_id" value="{{$receipt->id}}">
                            <div class="cardStyleChange bg-white">
                                <div class="card-body pb-1">
                                    <div class="row mx-1 mt-1 d-flex">

                                        <div class="col-md-4 changeColStyle search-item-pi">
                                            <label for="">Party Name</label>
                                            <select name="party_info" id="party_info" class="common-select2 party-info" style="width: 100% !important" data-target="" required disabled>
                                                <option value="">Select...</option>
                                                @foreach ($parties as $item)
                                                    <option value="{{ $item->id }}" {{ $receipt->party_id == $item->id ? 'selected' : '' }}> {{ $item->pi_name }}</option>
                                                @endforeach
                                            </select>
                                         </div>

                                        <div class="col-md-2 changeColStyle">
                                            <label for="">Payment Mode</label>
                                            <select name="pay_mode" id="pay_mode" class="form-control inputFieldHeight" required>
                                                @foreach ($modes as $item)
                                                    <option value="{{ $item->title }}" {{$receipt->pay_mode == $item->title ? 'selected' : ''}}> {{ $item->title }} </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2 changeColStyle" id="printarea">
                                            <label for="">Date</label>
                                            <input type="text" value="{{date('d/m/Y', strtotime($receipt->date))}}" class="form-control inputFieldHeight datepicker" name="date"  placeholder="dd-mm-yyyy" >

                                        </div>

                                        <div class="col-md-2 changeColStyle" id="printarea">
                                            <label for="">Due Amount</label>
                                            <input type="number" step="any" class="form-control inputFieldHeight" name="due_amount" id="due_amount" placeholder="Due Amount" value="{{$due}}" readonly>
                                        </div>

                                        <div class="col-md-2 changeColStyle" id="printarea">
                                            <label for="">Pay Amount</label>
                                            <input type="number" step="any" class="form-control inputFieldHeight" name="pay_amount" id="pay_amount" placeholder="Pay Amount" value="{{$receipt->total_amount}}" required>
                                        </div>

                                        <div class="col-md-12 cheque-content" style="display: {{$receipt->pay_mode=='Cheque'?'':'none'}}">
                                            <div class="row">
                                                <div class="col-md-5 changeColStyle"><label for="">Issuing Bank</label>
                                                    <input type="text" autocomplete="off" name="issuing_bank"
                                                        id="issuing_bank" class="form-control inputFieldHeight"
                                                        placeholder="Issuing Bank" value="{{$receipt->issuing_bank}}" {{$receipt->pay_mode=='Cheque'?'required':''}}>
                                                    @error('issuing_bank')
                                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-3 changeColStyle">
                                                    <label for="">Branch</label>
                                                    <input type="text" autocomplete="off" name="bank_branch"
                                                        id="bank_branch" class="form-control inputFieldHeight"
                                                        placeholder="Branch" value="{{$receipt->branch}}" {{$receipt->pay_mode=='Cheque'?'required':''}}>
                                                    @error('bank_branch')
                                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-2 changeColStyle">
                                                    <label for="">Cheque No</label>
                                                    <input type="text"  autocomplete="off"
                                                        class="form-control inputFieldHeight" name="cheque_no"
                                                        placeholder="Cheque Number" id="cheque_no" value="{{$receipt->cheque_no}}" {{$receipt->pay_mode=='Cheque'?'required':''}}>
                                                    @error('cheque_no')
                                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-2 changeColStyle">
                                                    <label for="">Deposit Date</label>
                                                    <input type="text" autocomplete="off"
                                                        class="form-control inputFieldHeight datepicker deposit_date"
                                                        name="deposit_date" placeholder="dd-mm-yyyy" value="{{date('d/m/Y',strtotime($receipt->deposit_date))}}" {{$receipt->pay_mode=='Cheque'?'required':''}}>
                                                    @error('deposit_date')
                                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 changeColStyle" id="printarea">
                                            <label for="">Narration</label>
                                            <input type="text" class="form-control inputFieldHeight" name="narration" placeholder="Narration" value="{{ $receipt->narration }}" required>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="cardStyleChange" style="width: 100%">
                                                    <div class="card-body bg-white" id="table-part">
                                                        <table class="table table-bordered table-sm " >
                                                            <thead>
                                                                <tr >
                                                                    <th style="width: 5%">#</th>
                                                                    <th style="width: 28%">Invoice</th>
                                                                    <th style="width: 28%">Total Amount <small>( @if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                                                    <th style="width: 28%">Due Amount <small>( @if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                                                </tr>
                                                            </thead>
                                                            @foreach ($new_invoices as $key => $inv)
                                                            @php
                                                                $exit_invoice_amount = App\TempReceiptVoucherDetail::where('sale_id', $inv->id)->where('payment_id', $receipt->id)->first();
                                                                $due_amount = $inv->due_amount;
                                                                if($exit_invoice_amount){
                                                                    $due_amount += $exit_invoice_amount->Total_amount;
                                                                }
                                                            @endphp
                                                            <tr id="TRow" >
                                                                <td>{{$key+1}}</td>
                                                                <td>
                                                                    {{$inv->invoice_no}}
                                                                </td>
                                                                <td>
                                                                    {{$inv->total_budget}}
                                                                </td>
                                                                <td>
                                                                    {{$due_amount}}
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            <tr>
                                                                <td colspan="2"></td>
                                                                <td class="text-center" style="color: black">Total Due</td>
                                                                <td>{{$due}}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-12 text-right d-flex justify-content-end mb-1">
                                            <button type="submit" class="btn btn-primary formButton mr-1">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img  src="{{asset('assets/backend/app-assets/icon/save-icon.png')}}" alt="" srcset=""  width="25">
                                                    </div>
                                                    <div><span>Update</span></div>
                                                </div>
                                            </button>
                                            <a href="{{route("receipt-voucher2")}}" class="btn btn-warning">New</a>

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
@endsection
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/select/form-select2.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/form-repeater.js"></script>
{{-- js work by mominul start --}}

{{-- js work by mominul end --}}

<script>

function refreshPage(){
            window.location.reload();
        }
    $(document).ready(function() {

        // $('.btn_create').click(function(){
        $(document).on("click", ".btn_create", function(e){
            e.preventDefault();
            // alert('Alhamdulillah');
            setTimeout(function() {
                $('.multi-acc-head').select2();
                $('.multi-tax-rate').select2();
            }, 1000);
        });
        // on select multiple account head

        $(document).on('change', '.purchase_no', function(){
            var value = $(this).val();
            var amount_obj= $(this).closest('#TRow').find('.due_amount');
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('findsaleRec') }}",
                method: "POST",
                data: {
                    value: value,
                    _token: _token,
                },
                success: function(response) {
                    console.log(response);
                    alert(response.due_amount);
                    $(amount_obj).val(response.due_amount);
                    sum_all_amount();
                }
            })
        });

        $('#pay_mode').change(function() {
                if ($(this).val() == 'Cheque') {
                    $(".deposit_date").attr('required',true);
                    $("#bank_branch").attr('required',true);;
                    $("#issuing_bank").attr('required',true);
                    $("#cheque_no").attr('required',true);
                    $('.cheque-content').show();

                } else {
                    $(".deposit_date").removeAttr('required');
                    $("#bank_branch").removeAttr('required');;
                    $("#issuing_bank").removeAttr('required');
                    $("#cheque_no").removeAttr('required');
                    $('.cheque-content').hide();
                }

            });



        $('#party_info').change(function() {
            if ($(this).val() != '') {
                var value = $(this).val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('partyInfosale2R') }}",
                    method: "POST",
                    data: {
                        value: value,
                        _token: _token,
                    },
                    success: function(response) {
                        console.log(response);
                        $("#trn_no").val(response.info.trn_no);
                        $("#pi_code").val(response.info.pi_code);
                        $("#due_amount").val(response.due);

                        $("#pay_amount").attr({
                                        "max" : response.due,        // substitute your own
                                        "min" :  1         // values (or variables) here
                                    });
                        $("#table-part").empty().append(response.page);
                    }
                })
            }
        });

        $('#project_id').change(function() {
            if ($(this).val() != '') {
                var value = $(this).val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('find-job-project') }}",
                    method: "POST",
                    data: {
                        value: value,
                        _token: _token,
                    },
                    success: function(response) {
                        console.log(response);
                        $("div.search-item-pi select").val(response.party.id);
                        $("#trn_no").val(response.party.trn_no);
                        $("#pi_code").val(response.party.pi_code);
                        $("#due_amount").val(response.due);
                        $("#table-part").empty().append(response.page);

                        $("#pay_amount").attr({
                                        "max" : response.due,        // substitute your own
                                        "min" :  1         // values (or variables) here
                                    });
                        $('.common-select2').select2();
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




        $(document).on("keypress", "#pi_code", function(e) {
            var key = e.which;
            var value = $(this).val();
            if (e.which == 13) {


                $("#party_info").focus();
                e.preventDefault();
                return false;
            }

        });





        $(document).on("keypress", "#amount", function(e) {
            var key = e.which;
            var value = $(this).val();
                if (e.which == 13) {
                $("#tax_rate").focus();
                e.preventDefault();
                return false;
            }
        });




    });

    function BtnAdd() {
    /* Add Button */
    var newRow = $("#TRow").clone();
    newRow.removeClass("d-none");

    newRow.find("input, select").val('').attr('name', function(index, name) {
        return name.replace(/\[\d+\]/, '[' + ($('#TBody tr').length) + ']');
    });

    newRow.find("th").first().html($('#TBody tr').length+1 );
    newRow.appendTo("#TBody");
    newRow.find(".common-select2").select2();
 }

 function BtnDel(v) {
    /* Delete Button */
    $(v).parent().parent().remove();
    sum_all_amount();

    $("#TBody").find("tr").each(function(index) {
        $(this).find("th").first().html(index);
    });

 }
$(document).on("keyup", ".amount_withvat", function(e) {
    sum_all_amount()
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


        $("#formSubmit").submit(function(e) {
            e.preventDefault(); // avoid executing the actual submit of the form.

            var form = $(this);
            var url = form.attr('action');
            var data = new FormData(this);

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    if (response.warning) {
                        toastr.warning("{{ Session::get('message') }}", response.warning);
                    } else if (response.status) {
                        // Handle validation errors
                        for (var i = 0; i < Object.keys(response.status).length; i++) {
                            var key = i + ".invoice";
                            if (response.status.hasOwnProperty(key)) {
                                var errorMessages = response.status[key];
                                for (var j = 0; j < errorMessages.length; j++) {
                                    toastr.warning(errorMessages[j]);
                                }
                            }
                        }
                    } else {
                        $("#submitButton").prop("disabled", true)
                        $(".deleteBtn").prop("disabled", true)
                        $(".addBtn").prop("disabled", true)
                        document.getElementById("voucherPreviewShow").innerHTML = response;
                        $('#voucherPreviewModal').modal('show');
                        $("#newButton").removeClass("d-none")
                        $("#submitButton").addClass("d-none")
                    }

                },
                error: function(err) {
                    let error = err.responseJSON;
                    $.each(error.errors, function(index, value) {
                    toastr.error(value, "Error");
                    });
                }
            });
        });

</script>

@endpush

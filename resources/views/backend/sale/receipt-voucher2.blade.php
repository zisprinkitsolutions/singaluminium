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
            @include('clientReport.sales._header', ['activeMenu' => 'receipt_voucher'])
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="py-1 px-2">
                                @include('backend.receipt-voucher._subhead_receipt_voucher', [
                                    'activeMenu' => 'create',
                                ])
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            {{-- <div class="py-1 pr-2">
                                <a href="{{route('direct-receipt')}}" class="btn btn-info btn-sm" >Direct Receipt</a>
                            </div> --}}
                        </div>
                    </div>

                    <section id="widgets-Statistics">
                        <form action="{{ route('temp-receipt-voucher-post-inv') }}" method="POST" id="formSubmit" enctype="multipart/form-data" >
                            @csrf
                            <div class="cardStyleChange bg-white">
                                <div class="card-body pb-1">
                                    <div class="row mx-1 mt-1 d-flex justify-content-center">
                                        <div class="col-md-2 changeColStyle  mb-0 pb-0">
                                            <label for="">Type</label>
                                            <select name="voucher_type" id="voucher_type" class="common-select2 voucher_type" style="width: 100% !important" required>
                                                <option value="due" selected>Due Payment</option>
                                                <option value="advance">Advance Payment</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 changeColStyle  mb-0 pb-0 search-item-pi">
                                            <div class="form-group">
                                                <label for="">Party Name </label>
                                                <select name="party_info" id="party_info" class="common-select2 party-info" style="width: 100% !important" data-target="" required>
                                                    <option value="">Select...</option>
                                                    @foreach ($parties as $item)
                                                        <option value="{{ $item->id }}">{{ $item->pi_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('party_info')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                                <small id="available_balance" class="text-danger"></small>
                                            </div>
                                         </div>

                                        <div class="col-md-2 changeColStyle  mb-0 pb-0">
                                            <div class="form-group">
                                                <label for="">Party Code</label>
                                                <input type="text" name="pi_code" id="pi_code" class="form-control pi_code inputFieldHeight" required placeholder="Party Code">
                                                @error('party_info')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                           </div>
                                        </div>

                                        <div class="col-md-2 changeColStyle  mb-0 pb-0 d-none">
                                            <div class="form-group">
                                                <label for="">@if(!empty($currency->licence_name)){{$currency->licence_name}}@endif</label>
                                                <input type="text" class="form-control inputFieldHeight trn_no"
                                                value="{{ isset($journalF) ? $journalF->partyInfo->trn_no : '' }}"
                                                name="trn_no" id="trn_no" class="form-control" readonly>
                                                @error('trn_no')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-3 changeColStyle  mb-0 pb-0">
                                            <div class="form-group">
                                                <label for="">Payment Mode</label>
                                                <select name="pay_mode" id="pay_mode" class="form-control inputFieldHeight pay_mode" required>
                                                    <option value="">Select...</option>

                                                    @foreach ($modes as $item)
                                                        <option value="{{ $item->title }}"
                                                            {{ isset($journalF) ? ($journalF->txn_mode == $item->title ? 'selected' : '') : '' }}>
                                                            {{ $item->title }} </option>
                                                    @endforeach

                                                </select>
                                                @error('pay_mode')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-2 changeColStyle  mb-0 pb-0" id="printarea">
                                            <div class="form-group">
                                                <label for="">Date</label>
                                                <input type="text" value="{{date('d/m/Y')}}" class="form-control inputFieldHeight date datepicker" name="date"  placeholder="dd-mm-yyyy" id="date">
                                                @error('date')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                @enderror

                                            </div>
                                        </div>

                                        <div class="col-md-12 cheque-content" style="display: none">
                                            <div class="row">
                                                <div class="col-md-3 changeColStyle  mb-0 pb-0">
                                                    <div class="form-group">
                                                        <label for="">Issuing Bank</label>

                                                        <input type="text" autocomplete="off" name="issuing_bank"
                                                            id="issuing_bank" class="form-control inputFieldHeight issuing_bank"
                                                            placeholder="Issuing Bank">
                                                        @error('issuing_bank')
                                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                                            </div>
                                                        @enderror

                                                    </div>
                                                </div>

                                                <div class="col-md-3 changeColStyle  mb-0 pb-0">
                                                    <div class="form-group">
                                                        <label for="">Branch</label>

                                                        <input type="text" autocomplete="off" name="bank_branch"
                                                            id="bank_branch" class="form-control inputFieldHeight bank_branch"
                                                            placeholder="Branch">
                                                        @error('bank_branch')
                                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                                            </div>
                                                        @enderror

                                                    </div>
                                                </div>

                                                <div class="col-md-3 changeColStyle  mb-0 pb-0">
                                                    <div class="form-group">
                                                        <label for="">Cheque No</label>

                                                        <input type="text" value="" autocomplete="off"
                                                            class="form-control inputFieldHeight cheque_no" name="cheque_no"
                                                            placeholder="Cheque Number" id="cheque_no">
                                                        @error('cheque_no')
                                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                                            </div>
                                                        @enderror

                                                    </div>
                                                </div>

                                                <div class="col-md-3 changeColStyle  mb-0 pb-0">
                                                    <div class="form-group">
                                                        <label for="">Deposit Date</label>

                                                        <input type="text" value="" autocomplete="off"
                                                            class="form-control inputFieldHeight datepicker deposit_date"
                                                            name="deposit_date" placeholder="dd-mm-yyyy">
                                                        @error('deposit_date')
                                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                                            </div>
                                                        @enderror

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="cardStyleChange" style="width: 100%">
                                                    <div class="card-body bg-white" class="table-part">


                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-2 changeColStyle" id="printarea">
                                            <label for="">Voucher File</label>
                                            <input class="form-control inputFieldHeight  @error('voucher_file') is-invalid @enderror" type="file" name="voucher_file" style="height: 32px !important" accept="application/pdf,image/png,image/jpeg,application/msword" >
                                        </div>
                                        <div class="col-md-4 changeColStyle narration_div" id="">
                                            <div class="form-group">
                                                <label for="">Narration</label>

                                                <input type="text" class="form-control inputFieldHeight" name="narration"
                                                id="narration" placeholder="Narration"
                                                value="{{ isset($journalF) ? $journalF->narration : '' }}"
                                                required>
                                            </div>
                                        </div>

                                        <div class="col-md-2 changeColStyle due_amount_div" id="">
                                            <div class="form-group">
                                                <label for="">Due Amount</label>
                                                <input type="number" step="any" class="form-control inputFieldHeight" name="due_amount"
                                                id="due_amount" placeholder="Due Amount"
                                                value=""
                                                readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-2 changeColStyle d-none" id="printarea">
                                            <div class="form-group">
                                                <label for=""> Discount Amount</label>
                                                <input type="nmber" step="any" class="form-control inputFieldHeight" name="discount_amount" readonly id="discount_amount" placeholder="Pay Amount">
                                            </div>
                                        </div>

                                        <div class="col-md-2 changeColStyle" id="printarea">
                                            <div class="form-group">
                                                <label for="">Pay Amount</label>

                                                <input type="number" step="any" class="form-control inputFieldHeight" name="pay_amount"
                                                id="pay_amount" placeholder="Pay Amount"
                                                value=""
                                                required>

                                            </div>
                                        </div>



                                        <div class="col-md-2 text-right d-flex justify-content-end mb-1" style="padding-right: 5px;">
                                            <button type="submit" class="btn btn-primary formButton mt-2" id="submitButton">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img  src="{{asset('assets/backend/app-assets/icon/save-icon.png')}}" alt="" srcset=""  width="25">
                                                    </div>
                                                    <div><span>Save</span></div>
                                                </div>
                                            </button>
                                            <a href="{{route("receipt-voucher3")}}" class="btn btn-warning  d-none mt-2" id="newButton">New</a>

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
    $(document).on('change', '#voucher_type', function(e){
        $("#table-part").empty();
        $("#due_amount").val('');
        $('#discount_amount').val('');
        $('#pay_amount').val('');
        var type = $(this).val();
        if(type == 'advance'){
            $(".due_amount_div").addClass('d-none');
            $(".narration_div").removeClass('col-md-4');
            $(".narration_div").addClass('col-md-6');
        }else{
            $(".due_amount_div").removeClass('d-none');
            $(".narration_div").removeClass('col-md-6');
            $(".narration_div").addClass('col-md-4');
        }
    })
    function refreshPage(){
        window.location.reload();
    }
    $(document).ready(function() {
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
        $(document).on('change', '#party_info, #date, #pi_code', function(e){

            var voucher_type = $('#voucher_type').val();
            var date = $('#date').val();
            var party_id = $('#party_info').val();
            if (party_id != '') {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('partyInfosale2R') }}",
                    method: "POST",
                    data: {
                        date:date,
                        party_id: party_id,
                        _token: _token,
                    },
                    success: function(response) {
                        var due=response.due.toFixed(2);
                        $("#trn_no").val(response.info.trn_no);
                        $("#pi_code").val(response.info.pi_code);
                        $("#available_balance").html('Available Balance '+ response.info.balance);
                        if(voucher_type=='due'){
                            $("#due_amount").val(due);
                            $('#due_amount').data('due',due);
                            $("#pay_amount").attr({
                                            "max" : due,        // substitute your own
                                            "min" :  1         // values (or variables) here
                                        });
                            $("#table-part").empty().append(response.page);
                        }
                    }
                })
            }
        });

        $(document).on("keyup", "#pi_code", function(e) {
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

        $(document).on("keypress", "#amount", function(e) {
            var key = e.which;
            var value = $(this).val();
                if (e.which == 13) {
                $("#tax_rate").focus();
                e.preventDefault();
                return false;
            }
        });

        $(document).on("click", ".checkbox-record", function(e) {
            var val=$(this).closest('tr').find('.inv_discount').val();
            if(val>0)
            {
                e.preventDefault()
            }
        });
        function discount(){
            var total_discount =  0;
            var total_due = parseFloat($('#due_amount').data('due')) || 0;

            $('.inv_discount').each(function(){
                var discount = parseFloat($(this).val()) || 0;
                var inv_due = parseFloat($(this).closest('tr').find('.inv_due').data('due'));

                if(discount > 0){
                    $(this).closest('tr').find('.checkbox-record').prop('checked',true);
                }else{
                    $('#discount_amount').val(total_discount);
                }

                if(discount <= inv_due){
                    inv_due -= discount;
                    total_discount += discount;
                    total_due -= discount;

                    $('#discount_amount').val(total_discount.toFixed(2));
                    $('#due_amount').val(total_due.toFixed(2));
                    $('#pay_amount').prop('max',total_due.toFixed(2));
                    $(this).closest('tr').find('.inv_due').text(inv_due.toFixed(2));
                    $('.total_due').text(total_due.toFixed(2));
                }else{
                    if(discount > 0){
                        toastr.error('Discount amount can not greater than due amount', inv_due);
                        $(this).addClass('is-invalid');
                    }
                }
            })
        }
        $(document).on('keyup', '.inv_discount', function(){
            discount();
        })
        $(document).on('click', '.btn-select-all', function(event){
            if (this.checked) {
                // Iterate each checkbox
                $(':checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $(':checkbox').each(function () {
                    this.checked = false;
                });
            }
        });

    });

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
                }else if(response==1){
                    toastr.warning('Please Check Your Advance Balance');
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

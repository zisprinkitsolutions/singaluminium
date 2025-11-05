@extends('layouts.backend.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@section('content')
    @include('layouts.backend.partial.style')
    <style>
        .changeColStyle span {
            min-width: 16%;
        }

        .changeColStyle .select2-container--default .select2-selection--single .select2-selection__arrow b {
            display: none;
        }

        .journaCreation {
            background: #1214161c;
        }

        .transaction_type {
            padding-right: 5px;
            padding-left: 5px;
            padding-bottom: 5px;
        }

        @media only screen and (max-width: 1500px) {
            .custome-project span {
                max-width: 140px;
            }
        }

        thead {
            background: #34465b;
            color: #fff !important;
        }

        th {
            color: #fff !important;
            font-size: 11px !important;
            height: 25px !important;
            text-align: center !important;
        }

        td {
            font-size: 12px !important;
            height: 25px !important;
            text-align: center !important;
        }

        .table-sm th,
        .table-sm td {
            padding: 0rem;
        }
    </style>
    <div class="app-content content print-hideen">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.purchase._header', ['activeMenu' => 'payment_voucher'])
                <div class="tab-content journaCreation">
                    <div id="journaCreation" class="tab-pane bg-white active">
                        <div class="py-1 px-1">
                            @include('backend.payment-voucher.sub-head', [
                                'activeMenu' => 'create',
                            ])
                        </div>
                        <section id="widgets-Statistics">

                            <form action="{{ route('temp-payment-voucher-store') }}" method="POST" id="formSubmit"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="cardStyleChange bg-white">
                                    <div class="card-body pb-1">
                                        <div class="row">
                                            <div class="col-md-3 form-group">
                                                <label for="">Payee</label>
                                                <select name="party_info" id="party_info"
                                                    class="common-select2 party-info" style="width: 100% !important"
                                                    data-target="" required>
                                                    <option value="">Select...</option>
                                                    @foreach ($parties as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ isset($journalF) ? ($journalF->party_info_id == $item->id ? 'selected' : '') : '' }}>
                                                            {{ $item->pi_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('party_info')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <label for="">Party Code</label>
                                                <input type="text" name="pi_code" id="pi_code"
                                                    class="form-control inputFieldHeight" required
                                                    placeholder="Party Code">
                                                @error('party_info')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-md-2 form-group">
                                                <label for="">TRN</label>
                                                <input type="text" class="form-control inputFieldHeight"
                                                    value="{{ isset($journalF) ? $journalF->partyInfo->trn_no : '' }}"
                                                    name="trn_no" id="trn_no" class="form-control" readonly>
                                                @error('trn_no')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-md-2 form-group">
                                                <label for="">Payment Mode</label>
                                                <select name="pay_mode" id="pay_mode"  class="form-control inputFieldHeight" required>
                                                    <option value="">Select...</option>
                                                    @foreach ($modes as $item)
                                                        <option value="{{ $item->title }}">{{ $item->title }} </option>
                                                    @endforeach
                                                </select>
                                                @error('pay_mode')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-3 form-group">
                                                <label for="">Paid By</label>
                                                <select name="paid_by" id="paid_by" class="form-control inputFieldHeight common-select2" disabled>
                                                    <option value="">Select...</option>
                                                    @foreach ($employee as $item)
                                                        <option value="{{ $item->id }}" >{{ $item->full_name }} </option>
                                                    @endforeach
                                                </select>
                                                @error('paid_by')
                                                    <div class="btn btn-sm btn-danger">{{ $message }} </div>
                                                @enderror
                                            </div>

                                            <div class="col-md-12 cheque-content" style="display: none">
                                                <div class="row">
                                                    <div class="col-md-3 form-group">

                                                        <label for="">Issuing Bank</label>

                                                        <input type="text" autocomplete="off" name="issuing_bank"
                                                            id="issuing_bank" class="form-control inputFieldHeight"
                                                            placeholder="Issuing Bank">
                                                        @error('issuing_bank')
                                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                                            </div>
                                                        @enderror

                                                    </div>

                                                    <div class="col-md-3 form-group">

                                                        <label for="">Branch</label>

                                                        <input type="text" autocomplete="off" name="bank_branch"
                                                            id="bank_branch" class="form-control inputFieldHeight"
                                                            placeholder="Branch">
                                                        @error('bank_branch')
                                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                                            </div>
                                                        @enderror

                                                    </div>

                                                    <div class="col-md-3 form-group">

                                                        <label for="">Cheque No</label>

                                                        <input type="text" value="" autocomplete="off"
                                                            class="form-control inputFieldHeight" name="cheque_no"
                                                            placeholder="Cheque Number" id="cheque_no">
                                                        @error('cheque_no')
                                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                                            </div>
                                                        @enderror

                                                    </div>

                                                    <div class="col-md-3 form-group">

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
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="cardStyleChange" style="width: 100%">
                                            <div class="card-body bg-white" id="table-part">


                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="cardStyleChange ">
                                    <div class="card-body bg-white payment-exist">
                                        <div class="row">
                                            <div class="col-sm-3 form-group">
                                                <label for="">Date</label>
                                                <input type="text" value="{{ date('d/m/Y') }}" class="form-control inputFieldHeight datepicker"
                                                    name="date" id="narration" placeholder="dd-mm-yyyy"
                                                     required>
                                                        @error('date')
                                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                                            </div>
                                                        @enderror
                                            </div>
                                            <!-- <div class="col-sm-3  form-group">
                                                <div class="row align-items-center">
                                                    <label for="">Date</label>
                                                        <input type="text" value="{{ date('d/m/Y') }}"
                                                            class="form-control inputFieldHeight datepicker" name="date"
                                                            placeholder="dd-mm-yyyy">
                                                        @error('date')
                                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div> -->
                                            <div class="col-sm-4 form-group">
                                                <label for="">Narration</label>
                                                <input type="text" class="form-control inputFieldHeight"
                                                    name="narration" id="narration" placeholder="Narration"
                                                    value="{{ isset($journalF) ? $journalF->narration : '' }}" required>
                                            </div>

                                            <div class="col-sm-2 form-group">
                                                <label for="">Pay Amount</label>
                                                <input type="number" step="any" class="form-control inputFieldHeight"
                                                    name="pay_amount" id="pay_amount" placeholder="Pay Amount"
                                                    value="" required>
                                            </div>

                                            <div class="col-sm-2 form-group">
                                                <label for="">Voucher File</label>
                                                <input class="form-control  @error('voucher_file') is-invalid @enderror" type="file" name="voucher_file"
                                                 style="padding: 0px !important;border:none" accept="application/pdf,image/png,image/jpeg,application/msword" >
                                            </div>
                                            <div class="col-sm-1 text-right d-flex justify-content-end mt-2 mb-1" style="padding-right: 9px;">
                                                <button type="submit" class="btn btn-primary formButton "
                                                    id="submitButton">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}"
                                                                alt="" srcset="" width="25">
                                                        </div>
                                                        <div><span>Save</span></div>
                                                    </div>
                                                </button>

                                                <a href="{{route("payment-voucher2")}}" class="btn btn-warning  d-none" id="newButton">New</a>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body bg-white payment-not-exist text-center text-danger">
                                        Doesn't Have Any Dues!
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
    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherPreviewShow">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="voucherDetailsPrintModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
    <script src="{{ asset('assets/backend') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/js/scripts/forms/select/form-select2.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/js/scripts/forms/form-repeater.js"></script>
    {{-- js work by mominul start --}}

    {{-- js work by mominul end --}}

    <script>
        function refreshPage() {
            window.location.reload();
        }
        $(document).ready(function() {


            $(document).on("click", ".btn_create", function(e) {
                e.preventDefault();
                setTimeout(function() {
                    $('.multi-acc-head').select2();
                    $('.multi-tax-rate').select2();
                }, 1000);
            });

            $(document).on('change', '.purchase_no', function() {
                var value = $(this).val();
                var amount_obj = $(this).closest('#TRow').find('.due_amount');
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
                        url: "{{ route('partyInfoInvoice2R') }}",
                        method: "POST",
                        data: {
                            value: value,
                            _token: _token,
                        },
                        success: function(response) {
                            console.log(response);
                            $("#trn_no").val(response.info.trn_no);
                            $("#pi_code").val(response.info.pi_code);
                            if(response.due_amount>0)
                            {
                                $('.payment-exist').show();
                                $('.payment-not-exist').hide();
                                $("#table-part").empty().append(response.page);
                                $("#pay_amount").attr({
                                        "max" : response.due_amount,        // substitute your own
                                        "min" :  1         // values (or variables) here
                                    });
                            }
                            else
                            {
                                $('.payment-exist').hide();
                                $('.payment-not-exist').show();
                                $("#table-part").empty();

                            }
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

            newRow.find("th").first().html($('#TBody tr').length + 1);
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

        function sum_all_amount() {
            var sum = 0;
            $('.amount_withvat').each(function() {
                var this_amount = $(this).val();
                this_amount = (this_amount === '') ? 0 : this_amount;
                this_amount = parseInt(this_amount);
                //
                sum = sum + this_amount;
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
        $(document).on('change', '#pay_mode', function(e){
            var to_account = $(this).val();
            if (to_account == 'Petty Cash') {
                $('#paid_by').attr('required', true);
                $('#paid_by').attr('disabled', false);
            } else {
                $('#paid_by').val(null).trigger('change');
                $('#paid_by').attr('required', false);
                $('#paid_by').attr('disabled', true);
            }
        })
    </script>
@endpush

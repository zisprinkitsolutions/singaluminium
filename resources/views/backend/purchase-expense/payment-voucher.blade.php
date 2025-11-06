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
            font-size: 13px !important;
            height: 25px !important;
            text-align: center ;
        }

        td {
            font-size: 15px !important;
           background: #fff;
        }

        .table-sm th,
        .table-sm td {
            padding: 5px;
        }

        .card-body {
            flex: 1 1 auto;
            min-height: 1px;
            padding: 0rem !important;
        }

        .card {
            margin-bottom: 0rem;
            box-shadow: none;
        }
        .select2-results__option{
            /* background: #da7d7d4b !important; */
            padding: 0 5px !important;
        }
        .change-body{
            display: none !important;
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
        .text-white{
            color: #fff !important;
        }
        /* -------- select -------- */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            text-align: left;
        }
        .select2-results__option[aria-selected] {
            text-align: left;
        }
</style>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.purchase._header', ['activeMenu' => 'payment_voucher'])
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active pb-1">
                    <section id="widgets-Statistics" style="padding-left: 8px;">
                        @include('layouts.backend.partial.modal-header-info')

                            <div class="col-md-12 pt-2">
                                <div class="cardStyleChange" >
                                    <div class="card-body bg-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center " style="width: 80% !important;">
                                                @if(Auth::user()->hasPermission('Expense_Create'))
                                                <button class="btn btn-primary inputFieldHeight print-hideen" style="padding:3px 8px !important;" data-toggle="modal" data-target="#voucher_create_model"> Issue Payment </button>
                                                @endif
                                                <div style="padding-left:10px;">
                                                    <input type="text" name="search" id="search" class="form-control inputFieldHeight print-hideen" placeholder="Search by Expense No">
                                                </div>

                                                <div class="print-hideen" style="padding-left:10px;width: 40% !important;">
                                                    <select name="party_search" id="party_search" class="common-select2 inputFieldHeight w-100 ">
                                                        <option value="">Select...</option>
                                                        @foreach ($parties as $party)
                                                            <option value="{{ $party->id }}">{{ $party->pi_name }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div style="padding-left:10px;">
                                                    <input type="text" name="date_search" id="date_search" class="form-control inputFieldHeight datepicker print-hideen" placeholder="Search by Date">
                                                </div>
                                                <div class="print-hideen" style="padding-left:10px; width:15%;">
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
                                            </div>

                                            {{-- <div class="d-flex">
                                                <button class="btn btn-info inputFieldHeight" style="padding:3px 8px !important; width:80px; margin-right:5%;" onclick="window.print()"> Print  </button>
                                                <button onclick="exportToExcel();" class="btn btn-success inputFieldHeight" style="padding:3px 8px !important;"> Excel Export </button>
                                            </div> --}}

                                            <!-- Right Side (Export/Import) -->
                                            <div class="dropdown print-hideen">
                                                <button class="btn btn-info inputFieldHeight formButton dropdown-toggle"
                                                    type="button" id="exportDropdown" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false"
                                                    style="padding:4px 15px !important;">
                                                    Export / Import
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="exportDropdown">
                                                    <a class="dropdown-item" href="javascript:void(0);"
                                                        onclick="exportToExcel('payment')">Excel Export</a>
                                                    <a class="dropdown-item" href="javascript:void(0);"
                                                        onclick="window.print()">Print</a>
                                                </div>
                                            </div>
                                        </div><br>

                                        <h5 class="invoice-view-wrapper"> Expense List  </h5>
                                        <table class="table table-bordered table-sm" id="payment">
                                            <thead class="thead">
                                                <tr >
                                                    <th style="min-width: fit-content;">SL No</th>
                                                    <th style="min-width: fit-content;">Date</th>
                                                    <th style="min-width: fit-content;">Payment No</th>
                                                    <th style="min-width: fit-content; text-align:left !important;">Party / Owner Name</th>
                                                    <th style="min-width: fit-content;" class="text-right">Amount <br> {{ number_format($data['total_amount'], 2) }}</th>
                                                    <th style="min-width: fit-content;" class="text-left"> Remarks </th>
                                                    <th style="min-width: fit-content;" class="text-center">Mode</th>
                                                    <th style="min-width: fit-content;" class="text-center">Paid By</th>
                                                </tr>
                                            </thead>
                                            <tbody id="payment-body">
                                                @php
                                                    $i = 0;
                                                @endphp
                                                @foreach ($temp_payments as $key => $item)
                                                    <tr class="temp-payment-voucher" id="{{$item->id}}">
                                                        <td class="text-center">{{++$i}}</td>
                                                        <td class="text-center">{{date('d/m/Y',strtotime($item->date))}}</td>
                                                        <td class="text-center">{{$item->payment_no}}</td>
                                                        <td style="text-align: left !important;" title="{{optional($item->party)->pi_name}}">
                                                            {{\Illuminate\Support\Str::limit(optional($item->party)->pi_name,30)}}
                                                        </td>
                                                        <td class="text-right">{{number_format($item->total_amount,2)}} </td>
                                                        <td style="min-width: fit-content" class="text-left">  <span class="bg-warning text-white" style="padding: 2px 3px;"> Awaiting Approve </span>  </td>
                                                        <td class="text-center">{{$item->pay_mode}}</td>
                                                        <td class="text-center">{{ optional($item->payment_account)->full_name }}</td>
                                                    </tr>
                                                @endforeach
                                                @foreach ($payments as $item)
                                                    <tr class="payment-voucher"  id="{{$item->id}}">
                                                        <td class="text-center">{{++$i}}</td>
                                                        <td class="text-center">{{date('d/m/Y',strtotime($item->date))}}</td>
                                                        <td class="text-center">{{$item->payment_no}}</td>
                                                        <td style="text-align: left !important;" title="{{optional($item->party)->pi_name}}">
                                                            {{\Illuminate\Support\Str::limit(optional($item->party)->pi_name,30)}}
                                                        </td>
                                                        <td class="text-right">{{number_format($item->total_amount,2)}}</td>
                                                        <td class="text-left" style="min-width: fit-content"> <span class="bg-success text-white" style="padding: 2px 3px;"> Approved </span> </td>
                                                        <td class="text-center">{{$item->pay_mode}}</td>
                                                        <td class="text-center">{{ optional($item->payment_account)->full_name }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div>{{$payments->links()}}</div>
                                    </div>
                                </div>
                            </div>
                        @include('layouts.backend.partial.modal-footer-info')
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- modal --}}
    <div class="modal fade bd-example-modal-lg" id="voucher_create_model" tabindex="-1" role="dialog"  aria-labelledby="voucher_create_modelLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                @include('backend.purchase-expense.payment-create')
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherPreviewShow">

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
        $('#search').keyup(function() {
            if ($(this).val() != '') {
                var value = $(this).val();
                var party = $('#party_search').val();
                var date = $('#date_search').val();
                var pay_mode = $('#pay_mode').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-payment-voucher') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date:date,
                        pay_mode:pay_mode,
                        _token: _token,
                    },
                    success: function(response) {
                        $('#payment-body').html(response);
                    }
                })
            }
        });

        $('#party_search, #date_search, #pay_mode').change(function() {
            if ($(this).val() != '') {
                var party = $('#party_search').val();
                var value = $('#search').val();
                var date = $('#date_search').val();
                var pay_mode = $('#pay_mode').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-payment-voucher') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date:date,
                        pay_mode:pay_mode,
                        _token: _token,
                    },
                    success: function(response) {
                        console.log(response);
                        $('#payment-body').html(response);
                    }
                })
            }
        });

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
                        $('#voucher_create_model').modal('hide');
                        $("#submitButton").prop("disabled", true)
                        $(".deleteBtn").prop("disabled", true)
                        $(".addBtn").prop("disabled", true)
                        document.getElementById("voucherPreviewShow").innerHTML = response.preview;
                        $('#voucherPreviewModal').modal('show');
                        $("#newButton").removeClass("d-none")
                        $('#payment-body').html(response.payment_list);
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
                $('.paid_by').attr('required', true);
                $('.paid_by').attr('disabled', false);
            } else {
                $('.paid_by').val(null).trigger('change');
                $('.paid_by').attr('required', false);
                $('.paid_by').attr('disabled', true);
            }
        })
        $(document).on("click", ".payment-voucher", function(e) {
            e.preventDefault();
            var id= $(this).attr('id');
            $.ajax({
                url: "{{route('payment-modal')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response){
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                    $(".datepicker").datepicker({
                    dateFormat: "dd/mm/yy"
                });
                }
            });
        });
        $(document).on("click", ".temp-payment-voucher", function(e) {
            e.preventDefault();
            var id= $(this).attr('id');
            $.ajax({
                url: "{{route('temp-payment-voucher-preview')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response){
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                    $(".datepicker").datepicker({
                    dateFormat: "dd/mm/yy"
                });
                }
            });
        });
        $(document).on("click", ".payment-edit", function(e) {
            e.preventDefault();
            var id= $(this).attr('id');
            $.ajax({
                url: "{{route('temp-payment-voucher-edit')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response){
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                    $(".datepicker").datepicker({
                        dateFormat: "dd/mm/yy"
                    });
                }
            });
        });
        $(document).on('change', '#pay_mode', function(e){
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
            var pay_mode = $(this).val();
            if(pay_mode == 'Bank'){
                $('.bank_name').show();
                $("#bank_id").attr('required',true);
            }else{
                $('#bank_id').val(null).trigger('change');
                $('.bank_name').hide();
                $("#bank_id").removeAttr('required');
            }
        })
    </script>
@endpush

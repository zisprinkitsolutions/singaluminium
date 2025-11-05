
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

        tr:nth-child(even) {
            background-color: #c8d6e357;
        }

        tr {
            cursor: pointer;
        }
    </style>
    <div class="app-content content print-hideen">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.purchase._header', ['activeMenu' => 'list'])
                <div class="tab-content journaCreation">
                    <div id="journaCreation" class="tab-pane bg-white active">

                        <section id="widgets-Statistics">

                            <div class="row ">

                                <div class="col-md-12 px-2">

                                    <div class="cardStyleChange" style="width: 850px !important;">
                                        <div class="card-body bg-white">
                                            <div class="row">
                                                <div class="col-4">
                                                    <input type="text" name="search" id="search"
                                                        class="form-control inputFieldHeight"
                                                        placeholder="Search by Bill No, Invoice No">
                                                </div>
                                                <div class="col-4">
                                                    <select name="party_search" id="party_search"
                                                        class="common-select2 inputFieldHeight" style="width: 100%;">
                                                        <option value="">Select Payee...</option>
                                                        @foreach ($parties as $party)
                                                            <option value="{{ $party->id }}">{{ $party->pi_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-4">
                                                    <input type="text" name="date_search" id="date_search"
                                                        class="form-control inputFieldHeight datepicker"
                                                        placeholder="Search by Date">
                                                </div>
                                            </div><br>

                                            <table class="table table-bordered table-sm ">
                                                <thead class="thead">
                                                    <tr>
                                                        <th style="width: 12%">Date</th>
                                                        <th style="width: 12%">Bill No</th>
                                                        <th style="width: 12%">Invoice No</th>
                                                        <th >Payee</th>
                                                        <th style="width: 12%">Amount <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                                        {{-- <th >Status</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody id="purch-body">
                                                    @foreach ($expenses as $item)
                                                        <tr class="purch_exp_view" id="{{ $item->id }}"
                                                            style="text-align:center;">
                                                            <td>{{ date('d/m/Y', strtotime($item->date)) }}</td>
                                                            <td>{{ $item->purchase_no }}</td>
                                                            <td>{{ $item->invoice_no }}</td>
                                                            <td>{{ $item->party->pi_name }}</td>
                                                            <td>{{number_format($item->total_amount,2) }}</td>
                                                            {{-- @if ($item->paid_amount==0)
                                                                <td class="text-danger">Full Due</td>
                                                            @elseif($item->due_amount==0)
                                                                <td class="text-success">Full Paid</td>
                                                            @else
                                                                <td class="text-danger">Full Due</td>
                                                            @endif --}}
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                            {!! $expenses->links()!!}
                                        </div>
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

    <script>
        $(document).on("click", ".purch_exp_view", function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ URL('purch-exp-modal') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                }
            });
        });


        $('#search').keyup(function() {
            if ($(this).val() != '') {
                var value = $(this).val();
                var party = $('#party_search').val();
                var date = $('#date_search').val();

                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-purchase-expense') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date:date,
                        _token: _token,
                    },
                    success: function(response) {

                        $("#purch-body").empty().append(response);
                    }
                })
            }
        });

        $('#party_search').change(function() {
            if ($(this).val() != '') {
                var party = $(this).val();
                var value = $('#search').val();
                var date = $('#date_search').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-purchase-expense') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date:date,
                        _token: _token,
                    },
                    success: function(response) {

                        $("#purch-body").empty().append(response);
                    }
                })
            }
        });

        $('#date_search').change(function() {
            if ($(this).val() != '') {
                var date = $(this).val();
                var value = $('#search').val();
                var party = $('#party_search').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-purchase-expense') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date:date,
                        _token: _token,
                    },
                    success: function(response) {

                        $("#purch-body").empty().append(response);
                    }
                })
            }
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
    })
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
    $(document).on('mouseenter', '.datepicker', function(){
        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "-1000:+1000",
            dateFormat: "dd/mm/yy",
        });
    });
    
    $(document).on('submit', '#formSubmit', function(e){
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

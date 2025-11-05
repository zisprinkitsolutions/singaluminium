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
            text-align: center;
            padding: 4 9px;
        }

        td {
            font-size: 12px !important;
            background: #fff;
        }

        .table-sm th,
        .table-sm td {
            padding: 0rem;
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
                <input type="hidden" name="standard_vat_rate" value="{{ $standard_vat_rate }}" id="standard_vat_rate">

                @include('clientReport.purchase._header', ['activeMenu' => 'lpo_bill'])
                <div class="tab-content journaCreation active">
                    <div id="journaCreation" class="tab-pane bg-white active">
                        {{-- <div class="py-1 px-1">
                            @include('clientReport.lpo-bill._subhead_lpo_bill', [
                                'activeMenu' => 'create',
                            ])
                        </div> --}}
                        <section id="widgets-Statistics">

                            <form action="{{ route('lpo-to-expense') }}" method="POST" enctype="multipart/form-data" class="text-left pt-1">
                                <input type="hidden" value="{{ $lpo_bill->id }}" name="lpo_bill_id">
                                <input type="hidden" value="{{ $lpo_bill->requisition_id }}" name="requisition_id">
                                @csrf

                                <div class="cardStyleChange bg-white">
                                    <div class="card-body ">
                                        <div class="row mx-1">
                                            <div class="col-md-2 col-left-padding">

                                                <label for="">Date</label>
                                                <input type="text" value="{{ date('d/m/Y', strtotime(date('Y-m-d'))) }}"
                                                    class="form-control inputFieldHeight datepicker" name="date"
                                                    placeholder="dd-mm-yyyy">
                                                @error('date')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4 col-left-padding">
                                                <label for="">Payee</label>
                                                <div class="row align-items-center">
                                                    <div class="col-10 customer-select">
                                                        <select name="party_info" id="party_info"
                                                            class="common-select2 party-info customer"
                                                            style="width: 100% !important" data-target="" required>
                                                            <option value="">Select...</option>
                                                            @foreach ($pInfos as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ $lpo_bill->party_id == $item->id ? 'selected' : '' }}>
                                                                    {{ $item->pi_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('party_info')
                                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-2 col-left-padding d-flex align-items-center">
                                                        <a href="#" data-toggle="modal"
                                                            data-target="#customerModal"><img
                                                                src="{{ asset('assets/backend/app-assets/icon/add-icon.png') }}"
                                                                alt="" srcset="" class="img-fluid"
                                                                style="height:29px"></a>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-3 col-left-padding">
                                                <label for="">Project</label>
                                                <div class="row align-items-center">
                                                    <div class="col-12 customer-select">
                                                        <select name="project_id" id="project_id" class="common-select2"
                                                            style="width: 100% !important" data-target="">
                                                            <option value="">Select...</option>
                                                            @foreach ($projects as $item)
                                                                <option value="{{ $item->id }}" {{ $item->id == $lpo_bill->project_id ? 'selected' : '' }}>
                                                                    {{ $item->project_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('project_id')
                                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-left-padding col-right-padding">
                                                <label for="">Invoice No</label>
                                                <input type="text" class="form-control inputFieldHeight" name="invoice_no" id="invoice_no" placeholder="Invoice No" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 col-right-padding col-left-padding"
                                    style="margin-top:25px !important">
                                    <div class="row mx-1">
                                        <div class="cardStyleChange" style="width: 100%">
                                            <div class="card-body bg-white">
                                                <table class="table  table-sm ">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 10%">Account Head</th>
                                                            <th style="width: 10%">Description</th>
                                                            <th style="width: 10%;">Project Task</th>
                                                            <th style="width: 10%;">Sub Task</th>
                                                            <th>Quantity</th>
                                                            <th style="width: 8%"> Unit </th>
                                                            <th style="width: 8%">Rate</th>
                                                            <th>Amount</th>
                                                            <th class="vat-exist" style="width: 8%">Vat Rate</th>
                                                            <th class="vat-exist" style="width: 5%">Vat</th>
                                                            <th>Total Amount</th>
                                                            <th class="NoPrint" style="width: 5%;padding: 2px;"> <button
                                                                    type="button"
                                                                    class="btn btn-sm btn-success addBtn"style="border: 1px solid green;
                                                                                    color: #fff; border-radius: 10px;padding: 5px;"
                                                                    onclick="BtnAdd()"><i class="bx bx-plus"
                                                                        style="color: white;margin-top: -5px;"></i></button>
                                                            </th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="TBody">
                                                        @foreach ($items as $key => $item)
                                                            <tr id="TRow" class="text-center invoice_row">
                                                                {{-- ******************************** --}}
                                                                <td>
                                                                    <select name="group-a[{{ $key }}][head_id]"
                                                                        required
                                                                        class="form-control expense_head account-head">
                                                                        <option value="">Select....</option>
                                                                        @foreach ($heads as $head)
                                                                            <option value="{{ $head->id }}" {{$head->name==$item->item_description? 'selected':''}}
                                                                                class="head">{{ $head->name }}
                                                                            </option>
                                                                        @endforeach

                                                                    </select>
                                                                </td>
                                                                {{-- ************************* --}}
                                                                <td>
                                                                    <input type="text"
                                                                        name="group-a[{{ $key }}][multi_acc_head]"
                                                                        value="{{ $item->item_description }}" required
                                                                        placeholder="Item Description"
                                                                        class="text-center form-control inputFieldHeight"
                                                                        style="width: 100%;height:36px;">
                                                                </td>
                                                                <td style="padding: 0">
                                                                    <select name="group-a[{{ $key }}][task_id]" class="task_id form-control col-left-padding col-right-padding">
                                                                        <option value="">Select....</option>
                                                                        @foreach (App\JobProjectTask::where('job_project_id', $lpo_bill->project_id)->get() as $p_task)
                                                                            <option value="{{ $p_task->id }}" {{ $p_task->id == $item->task_id ? 'selected' : '' }}> {{ $p_task->task_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td style="padding: 0">
                                                                    <select name="group-a[{{ $key }}][sub_task_id]" class="sub_task_id form-control col-left-padding col-right-padding">
                                                                        <option value="">Select....</option>
                                                                        @foreach (App\JobProjectTaskItem::where('task_id', $item->task_id)->get() as $itm)
                                                                            <option value="{{ $itm->id }}" {{ $itm->id == $item->sub_task_id ? 'selected' : '' }}> {{ $itm->item_description }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>

                                                                    <input type="number" value="{{ $item->qty }}"
                                                                        name="group-a[{{ $key }}][quantity]"
                                                                        step="any" required placeholder="Quantity"
                                                                        class="text-center form-control inputFieldHeight quantity"style="width: 100%;height:36px;">

                                                                </td>
                                                                <td>
                                                                    <select type="number" step="any"
                                                                        name="group-a[{{ $key }}][unit]"
                                                                        class="text-center form-control quantity inputFieldHeight"style="width: 100%;height:36px;">

                                                                        <option value="">Select...</option>

                                                                        @foreach ($units as $unit)
                                                                            <option value="{{ $unit->id }}"
                                                                                {{ $item->unit_id == $unit->id ? 'selected' : '' }}>
                                                                                {{ $unit->name }} </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>

                                                                <td>

                                                                    <input type="number" value="{{ $item->rate }}"
                                                                        name="group-a[{{ $key }}][rate]"
                                                                        step="any" required placeholder="Rate"
                                                                        class="text-center form-control inputFieldHeight rate"style="width: 100%;height:36px;">

                                                                </td>

                                                                <td>

                                                                    <input type="number" value="{{ $item->amount }}"
                                                                        name="group-a[{{ $key }}][amount]"
                                                                        step="any" required placeholder="Amount"
                                                                        class="text-center form-control inputFieldHeight amount"style="width: 100%;height:36px;">

                                                                </td>

                                                                <td class="vat-exist">
                                                                    <select name="group-a[{{ $key }}][vat_rate]"
                                                                        required
                                                                        class="inputFieldHeight vat_rate form-control "
                                                                        style="width: 100%;HEIGHT: 36PX;text-align:center;">
                                                                        <option value=""> -- Choice Option --
                                                                        </option>
                                                                        @foreach ($vats as $vat)
                                                                            <option value="{{ $vat->value }}"
                                                                                {{ $item->vat > 0 && $vat->value == 5 ? 'selected' : '' }}>
                                                                                {{ $vat->name . ' (' . $vat->value . ')' }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>

                                                                </td>

                                                                <td class="vat-exist"><input type="number"
                                                                        value="{{ $item->vat }}" step="any"
                                                                        class="text-center form-control vat_amount inputFieldHeight"
                                                                        required placeholder="Vat Amount"
                                                                        name="group-a[{{ $key }}][vat_amount]"
                                                                        readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="number"
                                                                        value="{{ $item->total_amount }}" step="any"
                                                                        name="group-a[{{ $key }}][sub_gross_amount]"
                                                                        required
                                                                        class="text-center form-control sub_gross_amount inputFieldHeight"
                                                                        placeholder="Amount"
                                                                        style="width: 100%;height:36px;" readonly>
                                                                </td>
                                                                </td>
                                                                <td class="NoPrint text-center"><button
                                                                        style="padding: 5px; margin: 4px;" type="button"
                                                                        class="btn btn-sm btn-danger"onclick="BtnDel(this)"><i
                                                                            class="bx bx-trash"
                                                                            style="color: white;margin-top: -5px;"></i></button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="8"></td>
                                                            <td colspan="2" class="text-right pr-1" style="color: black">TOTAL</td>
                                                            <td><input type="number" step="any" readonly
                                                                    id="taxable_amount"
                                                                    class="text-center form-control inputFieldHeight2 @error('taxable_amount') error @enderror inputFieldHeight taxable_amount"
                                                                    name="taxable_amount" value="{{ $lpo_bill->amount }}"
                                                                    placeholder="Amount" readonly required>
                                                                @error('taxable_amount')
                                                                    <span class="error">{{ $message }}</span>
                                                                @enderror
                                                            </td>
                                                        </tr>
                                                        <tr class="text-center">
                                                            <td colspan="8"></td>
                                                            <td colspan="2" class="text-right pr-1" style="color: black">VAT</td>
                                                            <td><input type="number" step="any" readonly
                                                                    id="total_vat"
                                                                    class="text-center inputFieldHeight2 form-control @error('total_vat') error @enderror inputFieldHeight total_vat"
                                                                    name="total_vat" value="{{ $lpo_bill->vat }}"
                                                                    placeholder="@if (!empty($currency->vat_name)) {{ $currency->vat_name }} @endif SUBTOTAL"
                                                                    readonly required>
                                                                @error('total_vat')
                                                                    <span class="error">{{ $message }}</span>
                                                                @enderror
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8"></td>
                                                            <td colspan="2" class="text-right pr-1" style="color: black">TOTAL AMOUNT</td>
                                                            <td><input type="number" step="any" readonly
                                                                    id="total_amount"
                                                                    class="text-center inputFieldHeight2 form-control @error('total_amount') error @enderror inputFieldHeight total_amount"
                                                                    name="total_amount"
                                                                    value="{{ $lpo_bill->total_amount }}"
                                                                    placeholder="TOTAL " readonly required>
                                                                @error('total_amount')
                                                                    <span class="error">{{ $message }}</span>
                                                                @enderror
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="cardStyleChange">
                                    <div class="card-body bg-white">
                                        <div class="row px-1">

                                            <div class="col-sm-11 col-right-padding ">
                                                <div class="row">
                                                    <div class="col-sm-3 col-right-padding  form-group">
                                                        <label for="">Voucher File</label>
                                                        <input type="file" class="form-control inputFieldHeight" name="voucher_scan[]" accept="image/*,application/pdf" multiple id="fileInput">
                                                    </div>
                                                    <div class="col-sm-3 col-right-padding  form-group d-none">
                                                        <label for="">Checked By</label>
                                                        <input type="text" class="form-control inputFieldHeight"
                                                            name="checked_by" id="checked_by" placeholder="Checked By"
                                                            value="">
                                                    </div>


                                                    <div class="col-sm-3 col-right-padding  form-grou d-none">
                                                        <label for="">Prepared By</label>
                                                        <input type="text" class="form-control inputFieldHeight"
                                                            name="prepared_by" id="prepared_by" placeholder="Prepared By"
                                                            value="">
                                                    </div>

                                                    <div class="col-sm-3  form-group d-none">
                                                        <label for="">Approved By</label>
                                                        <input type="text" class="form-control inputFieldHeight"
                                                            name="approved_by" id="approved_by" placeholder="Approved By"
                                                            value="">
                                                    </div>

                                                    {{-- <div class="col-sm-3  form-group">
                                                        <label for="">Pay Terms</label>
                                                        <input type="text" class="form-control inputFieldHeight"
                                                            name="pay_terms" id="pay_terms" placeholder="Pay Terms"
                                                            value="" >
                                                    </div> --}}
                                                </div>
                                            </div>

                                            <div class="col-sm-1 text-right d-flex justify-content-end mb-1"
                                                style="margin-top:20px;">
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
                                                <a href="{{ route('lpo-bill-create') }}" class="btn btn-warning  d-none"
                                                    id="newButton">New</a>
                                            </div>
                                            <div class="col-md-6" id="fileList">
                                                <div class="col-md-6"></div>
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
        function refreshPage() {
            window.location.reload();
        }
    </script>
    {{-- js work by mominul end --}}

    <script>
        $(document).ready(function() {


            // $('.btn_create').click(function(){
            $(document).on("click", ".btn_create", function(e) {
                e.preventDefault();
                // alert('Alhamdulillah');
                setTimeout(function() {
                    $('.multi-acc-head').select2();
                    $('.multi-tax-rate').select2();
                }, 1000);
            });


            $(document).on("change", "#project_id", function(e) {
                var project = $(this).val();
                $.ajax({
                    url: "{{ URL('find-project-task') }}",
                    type: "post",
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        project: project,
                    },
                    success: function(response) {
                        $('#task_id').empty().append(response);
                        $("#sub_task_id").empty();
                    }
                });
            });


            $(document).on("change", "#task_id", function(e) {
                var task_id = $(this).val();
                if (task_id) {
                    $.ajax({
                        url: "{{ route('find-project-task-item') }}",
                        type: "post",
                        cache: false,
                        data: {
                            _token: '{{ csrf_token() }}',
                            task_id: task_id,
                        },
                        success: function(response) {
                            $("#sub_task_id").empty().append(response);
                        }
                    });
                }
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

            $("#date").focus();


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
                            $("#party_contact").val(response.con_no);
                            $("#party_address").val(response.address);
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
                                $("#party_contact").val(response.con_no);
                                $("#party_address").val(response.address);

                                $("#invoice_no").focus();
                            }
                        }
                    })
                }
            });


            $(document).on("keyup", ".amount", function(e) {
                var amount = $(this).val();
                var invoice_type = $('#invoice_type').val();
                var quantity = $(this).closest("tr").find(".quantity").val();
                var rate = amount / quantity;

                var vat_amount = 0;
                var vat_rate = $(this).closest("tr").find(".vat_rate").val();
                vat_amount = (vat_rate / 100) * amount;
                gross_amount = (amount * 1) + vat_amount;
                gross_amount = gross_amount * 1;

                $(this).closest("tr").find(".rate").val(rate.toFixed(2));
                $(this).closest("tr").find(".vat_amount").val(vat_amount.toFixed(2));
                $(this).closest("tr").find(".sub_gross_amount").val(gross_amount.toFixed(2));
                total();

            });


            $(document).on("keyup", ".quantity", function(e) {
                var quantity = $(this).val();
                var invoice_type = $('#invoice_type').val();
                var rate = $(this).closest("tr").find(".rate").val();
                var amount = rate * quantity;

                var vat_amount = 0;
                var vat_rate = $(this).closest("tr").find(".vat_rate").val();
                vat_amount = (vat_rate / 100) * amount;
                gross_amount = (amount * 1) + vat_amount;
                gross_amount = gross_amount * 1;

                $(this).closest("tr").find(".amount").val(amount.toFixed(2));
                $(this).closest("tr").find(".vat_amount").val(vat_amount.toFixed(2));
                $(this).closest("tr").find(".sub_gross_amount").val(gross_amount.toFixed(2));
                total();

            });



            $(document).on("keyup", ".rate", function(e) {
                var rate = $(this).val();
                var invoice_type = $('#invoice_type').val();
                var quantity = $(this).closest("tr").find(".quantity").val();
                var amount = rate * quantity;

                var vat_amount = 0;
                var vat_rate = $(this).closest("tr").find(".vat_rate").val();
                vat_amount = (vat_rate / 100) * amount;
                gross_amount = (amount * 1) + vat_amount;
                gross_amount = gross_amount * 1;

                $(this).closest("tr").find(".amount").val(amount.toFixed(2));
                $(this).closest("tr").find(".vat_amount").val(vat_amount.toFixed(2));
                $(this).closest("tr").find(".sub_gross_amount").val(gross_amount.toFixed(2));
                total();

            });




            $(document).on("change", ".vat_rate", function(e) {
                var amount = $(this).closest("tr").find(".amount").val();
                var invoice_type = $('#invoice_type').val();
                var vat_amount = 0;
                var vat_rate = $(this).val();
                vat_amount = (vat_rate / 100) * amount;
                amount = (amount * 1) + vat_amount;
                $(this).closest("tr").find(".vat_amount").val(vat_amount.toFixed(2));
                $(this).closest("tr").find(".sub_gross_amount").val(amount.toFixed(2));

                total();
            });


            function total() {
                var sum = 0;
                var total_vat = 0;
                $('.amount').each(function() {
                    var this_amount = $(this).val();
                    this_amount = (this_amount === '') ? 0 : this_amount;
                    var this_amount = parseFloat(this_amount);
                    sum = sum + this_amount;
                });
                $('.vat_amount').each(function() {
                    var this_amount = $(this).val();
                    this_amount = (this_amount === '') ? 0 : this_amount;
                    var this_amount = parseFloat(this_amount);
                    //
                    total_vat = total_vat + this_amount;
                });
                var taxable = sum.toFixed(2)
                var vat = total_vat.toFixed(2)
                var total = (vat * 1) + (taxable * 1)
                $(".taxable_amount").val(taxable);
                $(".total_vat").val(vat);
                $(".total_amount").val((total.toFixed(2)));
            };

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

            $("#TBody").find("tr").each(function(index) {
                $(this).find("th").first().html(index);
            });
        }
        let selectedFiles = [];
        $(document).on('change', '#fileInput', function(e) {
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

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

        th {
            color: #475F7B;
            border-top: 1px solid #DFE3E7;
            font-size: 11px !important;
            text-align: center;
        }

        td {
            font-size: 12px !important;
            background:#fff;
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
    </style>
    <div class="app-content content print-hideen">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <input type="hidden" name="standard_vat_rate" value="{{$standard_vat_rate}}"  id="standard_vat_rate">

                @include('clientReport.sales._header', ['activeMenu' => 'invoice'])
                <div class="tab-content journaCreation active">
                    <div id="journaCreation" class="tab-pane bg-white active">
                        <div class="py-1 px-1">
                            @include('clientReport.sales._subhead_sale', [
                                'activeMenu' => 'create',
                            ])
                        </div>
                        <section id="widgets-Statistics">
                            <form action="{{ route('saleIssuepost') }}" method="POST" id="formSubmit"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="cardStyleChange bg-white">
                                    <div class="card-body ">

                                        <div class="row mx-1 pt-1">
                                            <div style="width:12%;margin-right:5px;">
                                                <label for="">Date</label>

                                                <input type="text"
                                                    value="{{ Carbon\Carbon::now()->format('d/m/Y') }}"
                                                    class="form-control inputFieldHeight datepicker" name="date"
                                                    placeholder="dd/mm/yyyy">
                                                @error('date')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>


                                            <div class="search-item-pi" style="width:25%;">
                                                <label for=""> Owner Party Name </label>

                                                <div class="row align-items-center">
                                                    <div class="col-10 customer-select">
                                                        <select name="party_info" id="party_info"
                                                            class="common-select2 party-info customer"
                                                            style="width: 100% !important" data-target="" required>
                                                            <option value="">Select...</option>
                                                            @foreach ($pInfos as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ isset($journalF) ? ($journalF->party_info_id == $item->id ? 'selected' : '') : '' }}>
                                                                    {{ $item->pi_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <small id="available_balance" class="text-danger"></small>
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

                                            <div style="width:20%;margin-left:5px;">
                                                <label for=""> Project </label>

                                                <select name="job_project_id" id="job_project_id" class="form-control common-select2">
                                                    <option value=""> Select... </option>
                                                </select>

                                                @error('job_project_id')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div class="" style="width:15%;margin-left:5px;">

                                                <label for="">Attention</label>

                                                <input type="text" name="attention" id="attention"
                                                    class="form-control inputFieldHeight"
                                                    placeholder="Attention">
                                                @error('attention')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div class="d-none" style="width:10%;margin-left:5px;">

                                                <label for="">Payment Mode</label>


                                                <select name="pay_mode" id="pay_mode"
                                                    class="form-control inputFieldHeight">
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



                                            <div class="d-none" style="width:10%;margin-left:5px;">

                                                <label for="">Invoice Type</label>


                                                <select name="invoice_type" id="invoice_type" class="form-control inputFieldHeight" required>
                                                    <option value="Tax Invoice">Tax Invoice</option>
                                                    <option value="Proforma Invoice" selected>Proforma Invoice</option>
                                                    <option value="Direct Invoice">Direct Invoice</option>
                                                </select>
                                                @error('invoice_type')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror

                                            </div>



                                            <div style="width:10%;margin-left:5px;" class="d-none">

                                                    <label for="">D.o No</label>

                                                    <input type="text" name="do_no" id="do_no"
                                                    class="form-control inputFieldHeight"
                                                    placeholder="D.O No">
                                                    @error('do_no')
                                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                                        </div>
                                                    @enderror
                                            </div>

                                            <div style="width:10%;margin-left:5px;" class="d-none">

                                                <label for="">LPO No</label>


                                                <input type="text" name="lpo_no" id="lpo_no"
                                                class="form-control inputFieldHeight"
                                                placeholder="LPO No">
                                                @error('lpo_no')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div style="width:13%;margin-left:5px;">

                                                <label for="">Quotation No</label>


                                                <input type="text" name="quotation_no" id="quotation_no"
                                                class="form-control inputFieldHeight"
                                                placeholder="Quotation No">
                                                @error('quotation_no')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror

                                            </div>

                                            <div class="col-md-12 cheque-content" style="display: none">
                                                <div class="row">
                                                    <div class="col-md-5 changeColStyle">

                                                        <label for="">Issuing Bank</label>

                                                        <input type="text" autocomplete="off" name="issuing_bank"
                                                            id="issuing_bank" class="form-control inputFieldHeight"
                                                            placeholder="Issuing Bank">
                                                        @error('issuing_bank')
                                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                                            </div>
                                                        @enderror

                                                    </div>

                                                    <div class="col-md-3 changeColStyle">

                                                        <label for="">Branch</label>

                                                        <input type="text" autocomplete="off" name="bank_branch"
                                                            id="bank_branch" class="form-control inputFieldHeight"
                                                            placeholder="Branch">
                                                        @error('bank_branch')
                                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                                            </div>
                                                        @enderror

                                                    </div>

                                                    <div class="col-md-2 changeColStyle">

                                                        <label for="">Cheque No</label>

                                                        <input type="text" value="" autocomplete="off"
                                                            class="form-control inputFieldHeight" name="cheque_no"
                                                            placeholder="Cheque Number" id="cheque_no">
                                                        @error('cheque_no')
                                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                                            </div>
                                                        @enderror

                                                    </div>

                                                    <div class="col-md-2 changeColStyle">

                                                        <label for="">Deposit Date</label>

                                                        <input type="text" value="" autocomplete="off"
                                                            class="form-control inputFieldHeight datepicker deposit_date"
                                                            name="deposit_date" placeholder="dd/mm/yyyy">
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
                                <div class="col-md-12 col-right-padding col-left-padding"
                                    style="margin-top:25px !important">
                                    <div class="row mx-1">
                                        <div class="cardStyleChange" style="width: 100%">
                                            <div class="card-body bg-white">
                                                <table class="table table-bordered table-sm ">
                                                    <thead style="background:#34465b;color:#fff;">
                                                        <tr>
                                                            <th style="width: 25%;color:#fff; text-align:left !important; padding:4px;">Description</th>
                                                            {{-- <th style="width: 10%;color:#fff">QTY</th>

                                                            <th style="width: 10%;color:#fff">Unit</th>
                                                            <th style="width: 15%;color:#fff">Rate</th> --}}
                                                            <th style="width: 10%;color:#fff">Amount</th>
                                                            <th style="width: 10%;color:#fff"> VAT (%) </th>
                                                            <th style="width: 10%;color:#fff"> Tatal VAT   </th>
                                                            <th style="width: 15%;color:#fff"> Sub Total  </th>

                                                            <th class="NoPrint" style="width: 1%;padding: 2px;"> <button type="button"
                                                                    class="btn btn-sm btn-success addBtn"style="border: 1px solid green;
                                                                color: #fff; border-radius: 10px;padding: 5px;"
                                                                    onclick="BtnAdd()"><i class="bx bx-plus" style="color: white;margin-top: -5px;"></i></button>
                                                            </th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="TBody">
                                                        <tr id="TRow" class="text-center invoice_row">
                                                            <td>
                                                                <div
                                                                    class="d-flex justy-content-between align-items-center">
                                                                    <input type="text" name="group-a[0][multi_acc_head]"  placeholder="Item Description" class="form-control inputFieldHeight2" style="height: 36px" required>
                                                                </div>
                                                            </td>

                                                            <td>
                                                                <div
                                                                    class="d-flex justy-content-between align-items-center d-none">
                                                                    <input type="text" name="group-a[0][qty]"
                                                                        step="any" required value="1"
                                                                        class="text-center form-control inputFieldHeight2 qty"style="width: 100%;height:36px;">
                                                                </div>
                                                            </td>

                                                            <td>
                                                                <input name="group-a[0][unit]" type="text" required class="text-center inputFieldHeight2 unit form-control d-none"style="width: 100%;height:36px;" value="1">
                                                            </td>

                                                            <td><input type="number" step="any"
                                                                    class="text-center form-control rate inputFieldHeight2" required
                                                                    name="group-a[0][rate]">
                                                            </td>

                                                            <td>
                                                                <input type="number" step="any"
                                                                    name="group-a[0][amount]" required
                                                                    class="text-center form-control amount inputFieldHeight2 d-none"
                                                                    style="width: 100%;height:36px;" readonly>
                                                            </td>

                                                            <td>
                                                                <select
                                                                    class="text-center form-control vat_rate inputFieldHeight2" required
                                                                    name="group-a[0][vat_rate]">

                                                                    <option value=""> Select... </option>
                                                                    <option value="5"> Standard (5) </option>
                                                                    <option value="0"> 0 Rated (0) </option>
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <input type="number" step="any"
                                                                    name="group-a[0][vat_amount]" required
                                                                    class="text-center form-control vat_amount inputFieldHeight2"
                                                                    style="width: 100%;height:36px;" readonly>
                                                            </td>

                                                            <td>
                                                                <input type="number" step="any"
                                                                    name="group-a[0][sub_total]" required
                                                                    class="text-center form-control sub_total inputFieldHeight2"
                                                                    style="width: 100%;height:36px;" readonly>
                                                            </td>

                                                            <td class="NoPrint"><button style="padding: 5px; margin: 4px;"
                                                                    type="button"
                                                                    class="btn btn-sm btn-danger"onclick="BtnDel(this)"><i class="bx bx-trash" style="color: white;margin-top: -5px;"></i></button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="7" class="text-right" style="color: black"> Taxable Amount </td>
                                                            <td><input type="number" step="any" readonly id="taxable_amount"
                                                                    class="text-center form-control inputFieldHeight2 @error('taxable_amount') error @enderror inputFieldHeight taxable_amount"
                                                                    name="taxable_amount" value=""
                                                                    placeholder="Amount" readonly required>
                                                                @error('taxable_amount')
                                                                    <span class="error">{{ $message }}</span>
                                                                @enderror
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                           <td colspan="7" class="text-right" style="color: black"> Total VAT</td>
                                                            <td><input type="number" step="any" readonly id="total_vat"
                                                                    class="text-center inputFieldHeight2 form-control @error('total_vat') error @enderror inputFieldHeight total_vat"
                                                                    name="total_vat" value=""
                                                                    placeholder="@if (!empty($currency->vat_name)) {{ $currency->vat_name }} @endif SUBTOTAL"
                                                                    readonly required>
                                                                @error('total_vat')
                                                                    <span class="error">{{ $message }}</span>
                                                                @enderror
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="7" class="text-right" style="color: black">TOTAL AMOUNT</td>
                                                            <td><input type="number" step="any" readonly id="total_amount"
                                                                    class="text-center inputFieldHeight2 form-control @error('total_amount') error @enderror inputFieldHeight total_amount"
                                                                    name="total_amount" value=""
                                                                    placeholder="TOTAL "
                                                                    readonly required>
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
                                            {{-- <div class="col-sm-6 form-group">
                                                <label for="">Narration</label>
                                                <input type="text" class="form-control inputFieldHeight"
                                                    name="narration" id="narration" placeholder="Narration"
                                                    value="{{ isset($journalF) ? $journalF->narration : '' }}" required>
                                            </div> --}}

                                            <div class="col-sm-3 form-group">
                                                <label for="">File Upload</label>
                                                <input type="file" class="form-control inputFieldHeight" id="voucher_scan"
                                                    name="voucher_scan[]" accept="image/*" multiple>
                                            </div>

                                            <div class="col-sm-3 form-group">

                                            </div>
                                            <div class="col-sm-6 text-right d-flex justify-content-end mt-2 mb-1">
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
                                                <a class="btn btn-warning  d-none" onClick="refreshPage()"
                                                    id="newButton">New</a>

                                            </div>

                                        </div>
                                        <div id="preview-images" class="mt-2 ml-1 d-flex flex-wrap"></div>
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
         function BtnAdd() {
            /* Add Button */
            var newRow = $("#TBody tr:first").clone();
            newRow.find('textarea').prop('readonly', false);
            newRow.removeClass("d-none");
            newRow.find("input, select,textarea").val('').attr('name', function(index, name) {
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
            total()
        }

            // $('.btn_create').click(function(){
            $(document).on("click", ".btn_create", function(e) {
                e.preventDefault();
                // alert('Alhamdulillah');
                setTimeout(function() {
                    $('.multi-acc-head').select2();
                    $('.multi-tax-rate').select2();
                }, 1000);
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

                        }
                        $('.show-edit-form').hide();
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
            $('#project').change(function() {
                console.log($(this).val());
                if ($(this).val() != '') {
                    var value = $(this).val();
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('findProject') }}",
                        method: "POST",
                        data: {
                            value: value,
                            _token: _token,
                        },
                        success: function(response) {
                            $("#owner").val(response.owner_name);
                            $("#location").val(response.address);
                            $("#address").val(response.address);
                            $("#mobile").val(response.cont_no);
                        }
                    })
                }
            });

            $(document).on('change', '#invoice_type', function(){
                var invoice_type = $(this).val();
                var selectElement = document.getElementById("pay_mode");
                var options = selectElement.options;
                for (var i = 0; i < options.length; i++) {
                    var option = options[i];
                    if (option.value=='Advance' && invoice_type !='Tax Invoice') {
                        option.setAttribute('disabled','disabled');
                    }else{
                        option.removeAttribute('disabled','disabled');
                    }
                }
            })

            $('#party_info').change(function() {
                var selectElement = document.getElementById("pay_mode");
                var options = selectElement.options;
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
                            var party = response.info;
                            var projects = response.projects;

                            $("#trn_no").val(party.trn_no);
                            $("#pi_code").val(party.pi_code);
                            $("#party_contact").val(party.con_no);
                            $("#party_address").val(party.address);
                            $("#attention").val(party.con_person);
                            $("#available_balance").html('Available Balance '+ party.balance);
                            $("#invoice_no").focus();
                            for (var i = 0; i < options.length; i++) {
                                var option = options[i];
                                if (option.value=='Advance' && Number(party.balance)==0) {
                                    option.setAttribute('disabled','disabled');
                                }else{
                                    option.removeAttribute('disabled','disabled');
                                }
                            }

                            $('#job_project_id').empty();
                            $('#job_project_id').append('<option value=""> Select </option>')

                            $.each(projects, function(index, project) {
                                $('#job_project_id').append(`<option value="${project.id}">${project.project_name} (${project.project_code}</option>`);
                            });
                        }
                    })
                }
            });

            $(document).on('change', '#job_project_id', function () {
                var project_id = $(this).val();
                var url = "{{route('get.boq', ':project_id')}}";
                url = url.replace(':project_id', project_id);

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function (data) {
                        $('#TBody').empty(); // clear previous rows

                        let rowIndex = 0;

                        data.tasks.forEach(task => {
                            task.items.forEach(item => {
                                const row = `
                                    <tr id="TRow class="text-center invoice_row">
                                        <td>
                                            <div class="d-flex justy-content-between align-items-center">
                                                <textarea readonly name="group-a[${rowIndex}][multi_acc_head]" placeholder="Item Description" cols="30" rows="1" class="form-control" style="height: 36px" required>${item.item_description || ''}</textarea>
                                            </div>
                                        </td>
                                         <input type="hidden" name="group-a[${rowIndex}][task_id]" value="${item.task_id || ''}">
                                        <td>
                                            <div class="d-flex justy-content-between align-items-center">
                                                <input type="text" name="group-a[${rowIndex}][qty]" step="any" required class="text-center form-control inputFieldHeight2 qty" style="width: 100%; height:36px;" value="${1}">
                                            </div>
                                        </td>

                                        <td>
                                            <input name="group-a[${rowIndex}][unit]" type="text" required class="text-center inputFieldHeight2 unit form-control" style="width: 100%; height:36px;" value="${item.unit || ''}">
                                        </td>

                                        <td>
                                            <input type="number" step="any" class="text-center form-control rate inputFieldHeight2" required name="group-a[${rowIndex}][rate]" value="${item.rate || ''}">
                                        </td>


                                        <td>
                                            <input type="number" step="any" name="group-a[${rowIndex}][amount]" required class="text-center form-control amount inputFieldHeight2" style="width: 100%; height:36px;" value="${item.rate || ''}" readonly>
                                        </td>

                                        <td>
                                            <select
                                                class="text-center form-control vat_rate inputFieldHeight2" required
                                                name="group-a[${rowIndex}][vat_rate]">

                                                <option value=""> Select... </option>
                                                <option value="5"> Standard (5) </option>
                                                <option value="0"> 0 Rated (0) </option>
                                            </select>
                                        </td>

                                        <td>
                                            <input type="number" step="any" name="group-a[${rowIndex}][vat_amount]" required class="text-center form-control vat_amount inputFieldHeight2" style="width: 100%; height:36px;" value="${0.00}" readonly>
                                        </td>

                                        <td>
                                            <input type="number" step="any" name="group-a[${rowIndex}][sub_total]" required class="text-center form-control sub_total inputFieldHeight2" style="width: 100%; height:36px;" value="${item.rate || ''}" readonly>
                                        </td>

                                        <td class="NoPrint">
                                            <button style="padding: 5px; margin: 4px;" type="button" class="btn btn-sm btn-danger" onclick="BtnDel(this)">
                                                <i class="bx bx-trash" style="color: white; margin-top: -5px;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                $('#TBody').append(row);
                                rowIndex++;

                                total();
                            });
                        });
                    }
                });
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





        $(document).on("change", "#party", function(e) {
            e.preventDefault();
            $('.date').val('')
            var id = $(this).val();
            var invoice_no = $('#invoice_no').val();
            $.ajax({
                url: "{{ URL('find-invoice') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    invoice_no: invoice_no,
                },
                success: function(response) {
                    $('#table-body').empty().append(response);
                }
            });
        });


        $(document).on("keyup", "#invoice_no", function(e) {
            var inv = $(this).val();
            var party= $('#party_info').val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('invoice_no_validation') }}",
                method: "POST",
                data: {
                    inv: inv,
                    party:party,
                    _token: _token,
                },
                success: function(response) {
                    if(response.warning)
                    {

                        toastr.warning(response.warning);
                    }

                }
            })
        });



    //     $(document).on("keyup", ".qty", function(e) {
    //         var qty = $(this).val();
    //         var rate =  $(this).closest("tr").find(".rate").val();
    //         var amount = qty*rate;
    //        $(this).closest("tr").find(".amount").val(amount);
    //        total();
    //   });

    //   $(document).on("keyup", ".rate", function(e) {
    //         var rate = $(this).val();
    //         var qty =  $(this).closest("tr").find(".qty").val();
    //         var amount = qty*rate;
    //        $(this).closest("tr").find(".amount").val(amount);
    //        total();
    //   });

    function calculateAmount($row){
        var rate = parseFloat($row.find(".rate").val()) || 0;
        var qty = parseFloat($row.find(".qty").val()) || 0;
        var vatRate = parseFloat($row.find('.vat_rate').val()) || 0;

        var amount = qty * rate;
        $row.find(".amount").val(amount.toFixed(2));

        var vatAmount = (amount * vatRate) / 100;
        totalAmount = vatAmount + amount;
        $row.find(".vat_amount").val(vatAmount.toFixed(2));
        $row.find('.sub_total').val(totalAmount.toFixed(2));
        total();
    }

    $(document).on('keyup', '.rate , .qty', function(){
        var $row = $(this).closest("tr");
        calculateAmount($row);
    });

    $(document).on('change', '.vat_rate', function() {
        var $row = $(this).closest("tr");
        calculateAmount($row);
    });

      $(document).on("change", "#invoice_type", function(e) {
        total()
      });

      function total() {
            var sum=0;
            var vat = 0;
            $('.amount').each(function() {
                var this_amount= $(this).val();
                this_amount = (this_amount === '') ? 0 : this_amount;
                var this_amount = parseFloat(this_amount);
                vat += parseFloat($(this).closest("tr").find('.vat_amount').val()) || 0;
                sum = sum+this_amount;
            });
            var result = sum.toFixed(2)
            var standard_vat_rate=$('#standard_vat_rate').val();
            var invoice_type=$('#invoice_type').val();
            // if(invoice_type!='Direct Invoice' )
            // {
            //     var vat=(standard_vat_rate/100)*result;
            // }
            // else
            // {
            //     var vat=0;
            // }
            // alert(vat);
            var total_amount =  sum + vat;
            $(".taxable_amount").val(result);
            $(".total_vat").val(vat.toFixed(2));
            $(".total_amount").val(total_amount.toFixed(2));
      };

    $(document).on('submit', '.voucher-img-form', function (e) {
        e.preventDefault(); // stop form submission

        if (!confirm('Are you sure you want to delete this file?')) return;

        let wrapper = $(this).closest('.voucher-img-wrapper');
        let url = $(this).attr('action');

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _method: 'DELETE', // simulate DELETE request
                _token: '{{ csrf_token() }}'
            },
            success: function (res) {
                wrapper.remove();
               toastr.success(res.message || 'File deleted successfully.');
            },
            error: function () {
                alert('Failed to delete the file.');
            }
        });
    });

    $('#voucher_scan').on('change', function () {
            let files = this.files;
            $('#preview-images').html('');

            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        $('#preview-images').append(`
                            <div style="margin:5px;">
                                <img src="${e.target.result}" width="100" height="100" style="object-fit:cover; border:1px solid #ddd;"/>
                            </div>
                        `);
                    }
                    reader.readAsDataURL(file);
                }
            });
        });

    </script>
@endpush

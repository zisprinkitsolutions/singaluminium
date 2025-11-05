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

        .card-body {
            flex: 1 1 auto;
            min-height: 1px;
            padding: 0rem !important;
        }

        .card {
            margin-bottom: 0rem;
            box-shadow: none;
        }
        select option{
            text-align: left !important;
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
                        <div class="py-1 px-1">
                            {{-- @include('clientReport.lpo-bill._subhead_lpo_bill', [
                                'activeMenu' => 'create',
                            ]) --}}
                        </div>
                        <section id="widgets-Statistics">

                            <form action="{{ route('lpo-bill-store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="cardStyleChange bg-white text-left">
                                    <div class="card-body">
                                        <input type="hidden" name="requisition_id"  value="{{$requisition->id}}" id="">
                                        <div class="row mx-1">
                                            <div class="col-md-1 col-right-padding col-left-padding">
                                                <label for="">Date</label>
                                                <input type="text" value="{{ Carbon\Carbon::now()->format('d/m/Y') }}"
                                                    class="form-control inputFieldHeight datepicker" name="date"
                                                    placeholder="dd-mm-yyyy">
                                                @error('date')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-md-3">
                                                <label for="">Payee</label>
                                                <div class="row align-items-center">
                                                    <div class="col-10 customer-select">
                                                        <select name="party_info" id="party_info"
                                                            class="common-select2 party-info customer"
                                                            style="width: 100% !important" data-target="" required>
                                                            <option value="">Select...</option>
                                                            @foreach ($pInfos as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ $requisition->party_id == $item->id ? 'selected' : ' ' }}>
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

                                            
                                            <div class="col-md-1">
                                                <label for="">Plot No</label>
                                                <input type="text" id="plot_no" class="form-control inputFieldHeight" placeholder="Enter plot no" value="{{$requisition->project?$requisition->project->new_project->plot:''}}">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">Project</label>
                                                <div class="row align-items-center">
                                                    <div class="col-12 customer-select">
                                                        <select name="project_id" id="project_id" class="common-select2" style="width: 100% !important" data-target="">
                                                            <option value="">Select...</option>
                                                            @foreach ($projects as $item)
                                                                <option value="{{ $item->id }}" data-no="{{ optional($item->new_project)->plot }}" {{ $item->id == $requisition->project_id ? 'selected' : '' }}>
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

                                            <div class="col-md-4">
                                                <label for="">Pay Terms</label>
                                                <input type="text" class="form-control inputFieldHeight" name="narration" id="narration" placeholder="Pay Terms" required>
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
                                                            <th style="width: 15%">Description</th>
                                                            <th style="color:#fff !important;width: 15%;">Project Task</th>
                                                            <th style="color:#fff !important;width: 15%;">Sub Task</th>
                                                            <th>Quantity</th>
                                                            <th style="width: 5%">Unit</th>
                                                            <th>Rate</th>
                                                            <th>Amount</th>
                                                            <th class="vat-exist" style="width: 8%">Vat Rate</th>
                                                            <th class="vat-exist" style="width: 8%">Vat Amount</th>
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
                                                        @foreach ($requisition->items as $key => $item)
                                                            <tr class="invoice_row">
                                                                <td>
                                                                    <div
                                                                        class="d-flex justy-content-between">
                                                                        <input type="text"
                                                                            name="group-a[{{ $key }}][multi_acc_head]"
                                                                            value="{{ $item->item_description }}" required
                                                                            placeholder="Item Description"
                                                                            class="form-control "
                                                                            style="width: 100%;height:36px;"
                                                                            list="acc_head_list">
                                                                        <datalist id="acc_head_list">
                                                                            @foreach ($heads as $head)
                                                                                <option value="{{ $head->name }}">
                                                                            @endforeach
                                                                        </datalist>
                                                                    </div>
                                                                </td>
                                                                <td style="padding: 0">
                                                                    <select name="group-a[{{ $key }}][task_id]" class="task_id form-control col-left-padding col-right-padding">
                                                                        <option value="">Select....</option>
                                                                        @foreach (App\JobProjectTask::where('job_project_id', $requisition->project_id)->get() as $p_task)
                                                                            <option value="{{ $p_task->id }}" {{ $p_task->id == $item->job_project_task_id ? 'selected' : '' }}> {{ $p_task->task_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td style="padding: 0">
                                                                    <select name="group-a[{{ $key }}][sub_task_id]" class="sub_task_id form-control col-left-padding col-right-padding">
                                                                        <option value="">Select....</option>
                                                                        @foreach (App\JobProjectTaskItem::where('task_id', $item->job_project_task_id)->get() as $itm)
                                                                            <option value="{{ $itm->id }}" {{ $itm->id == $item->job_project_task_item_id ? 'selected' : '' }}> {{ $itm->item_description }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <div
                                                                        class="d-flex justy-content-between align-items-center">
                                                                        <input type="number" value="{{ $item->qty }}"
                                                                            name="group-a[{{ $key }}][quantity]"
                                                                            step="any" required placeholder="Quantity"
                                                                            class="text-center form-control  quantity" style="width: 100%;height:36px;">
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <select type="number" step="any"
                                                                        name="group-a[{{ $key }}][unit]"
                                                                        class="text-center form-control quantity" style="width: 100%;height:36px;">

                                                                        <option value="">Select...</option>

                                                                        @foreach ($units as $unit)
                                                                            <option value="{{ $unit->id }}"
                                                                                {{ $unit->id == $item->unit_id ? 'selected' : '' }}>
                                                                                {{ $unit->name }} </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>

                                                                <td>
                                                                    <div
                                                                        class="d-flex justy-content-between align-items-center">
                                                                        <input type="number" value="{{ $item->rate }}"
                                                                            name="group-a[{{ $key }}][rate]"
                                                                            step="any" required placeholder="Rate" min=".1"
                                                                            class="text-center form-control  rate"style="width: 100%;height:36px;">
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <div
                                                                        class="d-flex justy-content-between align-items-center">
                                                                        <input type="number" value="{{ $item->amount }}"
                                                                            name="group-a[{{ $key }}][amount]"
                                                                            step="any" required placeholder="Amount" min=".1"
                                                                            class="text-center form-control  amount"style="width: 100%;height:36px;">
                                                                    </div>
                                                                </td>

                                                                <td class="vat-exist">
                                                                    <select name="group-a[{{ $key }}][vat_rate]"
                                                                        required class=" vat_rate form-control "
                                                                        style="width: 100%;HEIGHT: 36PX;text-align:center;">
                                                                        <option value=""> -- Choice Option --
                                                                        </option>
                                                                        @foreach ($vats as $vat)
                                                                            <option value="{{ $vat->value }}"
                                                                                {{ $vat->value == 5 ? 'selected' : '' }}>
                                                                                {{ $vat->name . ' (' . $vat->value . ')' }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>

                                                                </td>

                                                                <td class="vat-exist"><input type="number"
                                                                        value="{{ $item->vat }}" step="any"
                                                                        class="text-center form-control vat_amount "
                                                                        required placeholder="Vat Amount"
                                                                        name="group-a[{{ $key }}][vat_amount]"
                                                                        readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="number"
                                                                        value="{{ $item->total_amount }}" step="any"
                                                                        name="group-a[{{ $key }}][sub_gross_amount]"
                                                                        required
                                                                        class="text-center form-control sub_gross_amount "
                                                                        placeholder="Amount"
                                                                        style="width: 100%;height:36px;" readonly>
                                                                </td>
                                                                </td>
                                                                <td class="NoPrint text-center d-flex" style="padding: 0">
                                                                    <button type="button" class="addItemBtn bg-info text-white" style="border: 1px solid #ddd; padding: 7px;" title="Add Item">+</button>
                                                                    <button type="button" class="removeItemBtn bg-danger text-white" style="border: 1px solid #ddd; padding: 7px;" title="Remove Item" onclick="BtnDel(this)">X</button>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                        <tr id="TRow" class="text-center invoice_row">
                                                            <td>
                                                                <div
                                                                    class="d-flex justy-content-between">
                                                                    <input type="text"
                                                                        name="group-a[{{ count($requisition->items) }}][multi_acc_head]"
                                                                        step="any" required
                                                                        placeholder="Item Description"
                                                                        class="text-center form-control "style="width: 100%;height:36px;" list="acc_head_list">
                                                                </div>
                                                            </td>
                                                            <td style="padding: 0">
                                                                <select name="group-a[{{ count($requisition->items) }}][task_id]" class="task_id form-control col-left-padding col-right-padding">
                                                                    <option value="">Select....</option>
                                                                    @foreach (App\JobProjectTask::where('job_project_id', $requisition->project_id)->get() as $p_task)
                                                                        <option value="{{ $p_task->id }}" > {{ $p_task->task_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td style="padding: 0">
                                                                <select name="group-a[{{ count($requisition->items) }}][sub_task_id]" class="sub_task_id form-control col-left-padding col-right-padding">
                                                                    <option value="">Select....</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <div
                                                                    class="d-flex justy-content-between">
                                                                    <input type="number" step="any"
                                                                        name="group-a[{{ count($requisition->items) }}][quantity]"
                                                                        step="any" required placeholder="Quantity"
                                                                        class="text-center form-control  quantity"style="width: 100%;height:36px;">
                                                                </div>
                                                            </td>

                                                            <td>
                                                                <select type="number" step="any"
                                                                    name="group-a[{{ count($requisition->items) }}][unit]"
                                                                    class="text-center form-control quantity"style="width: 100%;height:36px;">

                                                                    <option value="">Select...</option>

                                                                    @foreach ($units as $unit)
                                                                        <option value="{{ $unit->id }}">
                                                                            {{ $unit->name }} </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <div
                                                                    class="d-flex justy-content-between align-items-center">
                                                                    <input type="number" step="any"
                                                                        name="group-a[{{ count($requisition->items) }}][rate]"
                                                                        step="any" required placeholder="Rate"
                                                                        class="text-center form-control rate"style="width: 100%;height:36px;" min=".1">
                                                                </div>
                                                            </td>

                                                            <td>
                                                                <div
                                                                    class="d-flex justy-content-between align-items-center">
                                                                    <input type="number" step="any"
                                                                        name="group-a[{{ count($requisition->items) }}][amount]"
                                                                        step="any" required placeholder="Amount"
                                                                        class="text-center form-control  amount"style="width: 100%;height:36px;" min=".1">
                                                                </div>
                                                            </td>


                                                            <td class="vat-exist">
                                                                <select
                                                                    name="group-a[{{ count($requisition->items) }}][vat_rate]"
                                                                    required class=" vat_rate form-control "
                                                                    style="width: 100%;HEIGHT: 36PX;text-align:center;">
                                                                    <option value=""> -- Choice Option --
                                                                    </option>
                                                                    @foreach ($vats as $vat)
                                                                        <option value="{{ $vat->value }}">
                                                                            {{ $vat->name . ' (' . $vat->value . ')' }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>

                                                            </td>

                                                            <td class="vat-exist"><input type="number" step="any"
                                                                    class="text-center form-control vat_amount " required
                                                                    placeholder="Vat Amount"
                                                                    name="group-a[{{ count($requisition->items) }}][vat_amount]"
                                                                    readonly>
                                                            </td>
                                                            <td>
                                                                <input type="number" step="any"
                                                                    name="group-a[{{ count($requisition->items) }}][sub_gross_amount]"
                                                                    required
                                                                    class="text-center form-control sub_gross_amount "
                                                                    placeholder="Amount" style="width: 100%;height:36px;"
                                                                    readonly>
                                                            </td>
                                                            <td class="NoPrint text-center d-flex" style="padding: 0">
                                                                <button type="button" class="addItemBtn bg-info text-white" style="border: 1px solid #ddd; padding: 7px;" title="Add Item">+</button>
                                                                <button type="button" class="removeItemBtn bg-danger text-white" style="border: 1px solid #ddd; padding: 7px;" title="Remove Item" onclick="BtnDel(this)">X</button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="8"></td>
                                                            <td class="text-center" style="color: black">TAXABLE</td>
                                                            <td><input type="number" step="any" readonly
                                                                    id="taxable_amount"
                                                                    class="text-center form-control  @error('taxable_amount') error @enderror inputFieldHeight taxable_amount"
                                                                    name="taxable_amount"
                                                                    value="{{ $requisition->amount }}"
                                                                    placeholder="Amount" readonly required>
                                                                @error('taxable_amount')
                                                                    <span class="error">{{ $message }}</span>
                                                                @enderror
                                                            </td>
                                                        </tr>
                                                        <tr class="text-center">
                                                            <td colspan="8"></td>
                                                            <td class="text-center" style="color: black">VAT</td>
                                                            <td><input type="number" step="any" readonly
                                                                    id="total_vat"
                                                                    class="text-center  form-control @error('total_vat') error @enderror inputFieldHeight total_vat"
                                                                    name="total_vat"
                                                                    value="{{ $requisition->vat_amount }}"
                                                                    placeholder="@if (!empty($currency->vat_name)) {{ $currency->vat_name }} @endif SUBTOTAL"
                                                                    readonly required>
                                                                @error('total_vat')
                                                                    <span class="error">{{ $message }}</span>
                                                                @enderror
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="8"></td>
                                                            <td class="text-center" style="color: black">TOTAL AMOUNT</td>
                                                            <td><input type="number" step="any" readonly
                                                                    id="total_amount"
                                                                    class="text-center  form-control @error('total_amount') error @enderror inputFieldHeight total_amount"
                                                                    name="total_amount"
                                                                    value="{{ $requisition->total_amount }}"
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
                                                <div class="row text-left">
                                                    <div class="col-sm-3 col-right-padding  form-group">
                                                        <label for="">Voucher File</label>
                                                        <input type="file" class="form-control inputFieldHeight"
                                                            name="voucher_file" id="voucher_file">
                                                    </div>
                                                    <div class="col-sm-3 col-right-padding  form-group">
                                                        <label for="">Checked By</label>
                                                        <input type="text" class="form-control inputFieldHeight"
                                                            name="checked_by" id="checked_by" placeholder="Checked By"
                                                            value="">
                                                    </div>


                                                    <div class="col-sm-3 col-right-padding  form-group">
                                                        <label for="">Prepared By</label>
                                                        <input type="text" class="form-control inputFieldHeight"
                                                            name="prepared_by" id="prepared_by" placeholder="Prepared By"
                                                            value="">
                                                    </div>

                                                    <div class="col-sm-3  form-group">
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

                                            <div class="col-sm-1 text-right d-flex justify-content-end mt-2 mb-1">
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
                            $("#attention").val(response.con_person);
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
        }
        $(document).on("change", "#project_id", function () {
            let $plotInput = $("#plot_no");
            let plot = $(this).find("option:selected").data("no"); // data-no ব্যবহার করো
            $plotInput.val(plot ? plot : "");
        });
        $(document).on("change", "#plot_no", function () {
            let $projectSelect = $("#project_id");
            let plotVal = $(this).val().trim().toLowerCase();
            let found = false;

            $projectSelect.find("option").each(function () {
                let optPlot = $(this).data("no"); // data-no check
                if (optPlot && optPlot.toString().toLowerCase() === plotVal) {
                    $projectSelect.val($(this).val()).trigger("change");
                    found = true;
                    return false; // break loop
                }
            });

            if (!found) {
                $projectSelect.val("").trigger("change");
            }
        });
        $(document).on("change", ".task_id", function(e) {
            var task_id = $(this).val();
            current_tr = $(this).closest("tr");
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
                        current_tr.find(".sub_task_id").empty().append(response);
                    }
                });
            }
        });
        $(document).on("click", ".addItemBtn", function () {
            let $row   = $(this).closest("tr");
            let task_id = $row.find('.task_id').val();
            let sub_task_id = $row.find('.sub_task_id').val();
            let $clone = $row.clone();

            // get values from clone
            console.log("Task:", task_id, "SubTask:", sub_task_id);

            // find the highest index
            let lastIndex = 0;
            $("#tBody tr").each(function () {
                $(this).find("input, select, textarea").each(function () {
                    let name = $(this).attr("name");
                    if (name) {
                        let match = name.match(/group-a\[(\d+)\]/);
                        if (match) {
                            lastIndex = Math.max(lastIndex, parseInt(match[1]));
                        }
                    }
                });
            });
            let newIndex = lastIndex + 1;

            // update name attributes for cloned row
            $clone.find("input, select, textarea").each(function () {
                let name = $(this).attr("name");
                if (name) {
                    $(this).attr("name", name.replace(/group-a\[\d+\]/, "group-a[" + newIndex + "]"));
                }
            });
            $clone.find("input, select, textarea").val("");
            $clone.find('.task_id').val(task_id);
            $clone.find('.sub_task_id').val(sub_task_id);
            // insert new row
            $row.after($clone);
        });
    </script>
@endpush

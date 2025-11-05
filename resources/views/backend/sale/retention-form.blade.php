
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

    th{
        color: #fff !important;
        font-size: 11px !important;
        height: 25px !important;
        text-align: center;
    }

    td {
        font-size: 12px !important;
        background: #fff;
        padding: 3px 6px !important
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

<div class="modal-header" style="padding:5px 10px;background:#364a60;">
    <h5 class="modal-title" id="exampleModalLabel"
        style="font-family:Cambria;font-size: 2rem;color:white;">Tax Invoice</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<form action="{{ route('saleIssuepost') }}" method="POST" id="formSubmit" enctype="multipart/form-data">
    @csrf
    <div class="cardStyleChange bg-white">
        <div class="card-body ">
            <input type="hidden" name="retention_invoice" value="1">
            <input type="hidden" name="invoice_type" value="Proforma Invoice">
            <div class="row mx-1 mt-2">
                <div class="col-md-2 changeColStyle  col-right-padding d-none">
                    <div class="row d-flex align-items-center">
                        <div class="col-3">
                            <label for="project">Branch</label>
                        </div>

                        <div class="col-9">
                            <select name="project" class="common-select2 w-100" id="project"
                                required>
                                <option value="0"> 0 </option>
                            </select>
                            @error('project')
                                <div class="btn btn-sm btn-danger">{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>



                <div class="" style="width:12%;margin-right:5px;">

                    <label for="">Date</label>

                    <input type="text"
                        value="  {{date('d/m/Y')}}"
                        class="form-control inputFieldHeight datepicker"
                        name="date" placeholder="dd-mm-yyyy">
                    @error('date')
                        <div class="btn btn-sm btn-danger">{{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="search-item-pi" style="width:30%;">

                    <label for=""> Owner Party Name</label>

                    <div class="row align-items-center">
                        <div class="col-10 customer-select">
                            <select name="party_info" id="party_info"
                                class="common-select2 party-info form-control inputFieldHeight customer"
                                style="width: 100% !important" data-target="" required>
                                <option value="{{$project->customer_id}}"> {{optional($project->party)->pi_name}}</option>

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

                <div class="" style="width:25%;margin-left:5px;">

                    <label for="">Site/Project</label>

                    <select name="job_project_id" id="job_project_id" class="form-control common-select2 inputFieldHeight job_project_id">
                        <option value="{{$project->id}}"> {{$project->project_name}} </option>
                    </select>

                </div>

                <div class="" style="width:15%; margin-left:5px;">

                    <label for="">Attention</label>

                    <input type="text" name="attention" id="attention"
                        class="form-control inputFieldHeight"
                        placeholder="Attention" value="">
                    @error('attention')
                        <div class="btn btn-sm btn-danger">{{ $message }}
                        </div>
                    @enderror

                </div>






                <div class="" style="width:13%;margin-left:5px;">

                            <label for="">Quotation No</label>


                            <input type="text" name="quotation_no" id="quotation_no"
                            class="form-control inputFieldHeight"
                            placeholder="Quotation No" value="">
                            @error('quotation_no')
                                <div class="btn btn-sm btn-danger">{{ $message }}
                                </div>
                            @enderror

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
                        <thead>
                            <tr class="text-center">
                                <th style="width:25%; text-align:left;">Description</th>
                                <th class="d-none" style="width: 10%">QTY</th>
                                <th class="d-none" style="width: 10%">Unit</th>
                                <th class="d-none" style="width: 12%">Rate</th>
                                <th style="width: 12%">Amount</th>
                                <th style="width: 10%;color:#fff"> VAT (%) </th>
                                <th style="width: 8%;color:#fff"> VAT   </th>
                                <th style="width: 15%;color:#fff"> Sub Total  </th>
                            </tr>
                        </thead>
                        <tbody id="editTBody" class="sale-item">
                            <tr id="TRow" class="text-center invoice_row">
                                <td>
                                    <div
                                        class="d-flex justy-content-between align-items-center">
                                        <input type="text"
                                            name="group-a[{{0}}][multi_acc_head]" step="any" value="{{'Retention Amount'}}"
                                            required placeholder="Item Description" readonly
                                            class="text-left form-control inputFieldHeight2"style="width: 100%;height:36px;">
                                    </div>
                                </td>

                                <td class="d-none">
                                    <div
                                        class="d-flex justy-content-between align-items-center">
                                        <input type="number" name="group-a[{{0}}][qty]" value="{{1}}" readonly
                                            step="any" required
                                            class="text-center form-control inputFieldHeight2 qty"style="width: 100%;height:36px;">
                                    </div>

                                </td>


                                <td class="d-none">
                                    <select name="group-a[{{0}}][unit]" type="text"required
                                        class="text-center inputFieldHeight2 unit form-control "
                                        style="width: 100%;    HEIGHT: 36PX;">

                                        <option value="--">  -- </option>

                                    </select>



                                </td>
                                <td class="d-none"><input type="number" step="any"
                                        class="text-center form-control rate inputFieldHeight2" required
                                        name="group-a[{{0}}][rate]" value="{{$project->retention_amount}}">
                                </td>
                                <td>
                                    <input type="number" step="any"
                                        name="group-a[{{0}}][amount]" required value="{{$project->retention_amount}}"
                                        class="text-center form-control amount inputFieldHeight2"
                                        style="width: 100%;height:36px;" >
                                </td>


                                <td>
                                    <select
                                        class="text-center form-control vat_rate inputFieldHeight2" required
                                        name="group-a[{{0}}][vat_rate]">
                                        <option value="5"> 5 Rated </option>
                                    </select>
                                </td>

                                @php
                                    $amount = $project->retention_amount;
                                    $vat_amount = (5 * $amount) / 100;
                                    $total_amount = $amount + $vat_amount;
                                @endphp

                                <td>
                                    <input type="number" step="any"
                                        name="group-a[{{0}}][vat_amount]"  value="{{number_format($vat_amount,2,'.','')}}"
                                        class="text-center form-control vat_amount inputFieldHeight2"
                                        style="width: 100%;height:36px;" readonly>
                                </td>

                                <td>
                                    <input type="number" step="any"
                                        name="group-a[{{0}}][sub_total]" required value="{{number_format($total_amount,2,'.','')}}"
                                        class="text-center form-control sub_total inputFieldHeight2"
                                        style="width: 100%;height:36px;" readonly>
                                </td>
                            </tr>

                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-right" style="color: black">TAXABLE</td>
                                <td><input type="number" step="any" readonly
                                        id="taxable_amount"
                                        class="text-center form-control inputFieldHeight2 @error('taxable_amount') error @enderror inputFieldHeight taxable_amount"
                                        name="taxable_amount" value="{{$project->retention_amount}}"
                                        placeholder="Amount" readonly required>
                                    @error('taxable_amount')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right" style="color: black">VAT</td>
                                <td><input type="number" step="any" readonly
                                        id="total_vat"
                                        class="text-center inputFieldHeight2 form-control @error('total_vat') error @enderror inputFieldHeight total_vat"
                                        name="total_vat" value="{{number_format($vat_amount,2,'.','')}}"
                                        placeholder="VAT "
                                        readonly required>
                                    @error('total_vat')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right" style="color: black">TOTAL AMOUNT</td>
                                <td><input type="number" step="any" readonly
                                        id="total_amount"
                                        class="text-center inputFieldHeight2 form-control @error('total_amount') error @enderror inputFieldHeight total_amount"
                                        name="total_amount" value="{{number_format($total_amount,2,'.','')}}"
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
    <div class="cardStyleChange mb-2">
        <div class="card-body bg-white">
            <div class="row px-1">
                {{-- <div class="col-sm-6 form-group">
                    <label for="">Narration</label>
                    <input type="text" class="form-control inputFieldHeight"
                        name="narration" id="narration" placeholder="Narration"
                        value="{{ isset($sales) ? $sales->narration : '' }}" required>
                </div> --}}

                <div class="col-sm-4 form-group">
                    <label for="">File Upload</label>
                    <input type="file" class="form-control inputFieldHeight file_upload" id="voucher_scan2"
                        name="voucher_scan[]" accept="image/*" multiple>

                    <ul class="fileList">

                    </ul>
                </div>



                <div class="col-sm-6 d-flex mb-1">
                     <button type="submit" class="btn btn-primary formButton inputFieldHeight"
                            style="margin-top:18px;padding:5px 10px !important;" id="submitButton">
                        <div class="d-flex">
                            <div class="formSaveIcon">
                                <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}"
                                    alt="" srcset="" width="25">
                            </div>
                            <div><span>Save</span></div>
                        </div>
                    </button>

                </div>
            </div>

        </div>
    </div>
</form>




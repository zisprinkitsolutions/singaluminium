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
    <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">Tax Invoice
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<form action="{{ route('saleIssuepost.edit') }}" method="POST" id="editFormSubmit" enctype="multipart/form-data">
    @csrf
    <div class="cardStyleChange bg-white">
        <div class="card-body ">

            <div class="row mx-1 mt-2">
                <div class="col-md-2 changeColStyle  col-right-padding d-none">
                    <div class="row d-flex align-items-center">
                        <div class="col-3">
                            <label for="project">Branch</label>
                        </div>
                        <input type="hidden" value="{{ $sales->id }}" name="id">
                        <div class="col-9">
                            <select name="project" class="common-select2 w-100" id="project" required>
                                @foreach ($projects as $item)
                                    <option value="{{ $item->id }}"
                                        {{ isset($sales->project_id) ? ($sales->project_id == $item->id ? 'selected' : '') : '' }}>
                                        {{ $item->proj_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project')
                                <div class="btn btn-sm btn-danger">{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-md-3 changeColStyle d-none">
                    <div class="row aling-items-center">
                        <div class="col-4">
                            <label for="">Party Code</label>
                        </div>
                        <div class="col-8">
                            <input type="text" name="pi_code" id="pi_code" value="{{ $sales->party->pi_code }}"
                                class="form-control inputFieldHeight" required placeholder="Party Code">
                            @error('party_info')
                                <div class="btn btn-sm btn-danger">{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="" style="width:12%;margin-right:5px;">

                    <label for="">Date</label>

                    <input type="text" value="  {{ date('d/m/Y', strtotime($sales->date)) }}"
                        class="form-control inputFieldHeight datepicker" name="date" placeholder="dd-mm-yyyy">
                    @error('date')
                        <div class="btn btn-sm btn-danger">{{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="search-item-pi" style="width:30%;">

                    <label for=""> Owner Party Name</label>

                    <div class="row align-items-center">
                        <div class="col-10 customer-select">
                            <select name="party_info" id="party_info" class="common-select2 party-info customer"
                                style="width: 100% !important" data-target="" required>
                                <option value="">Select...</option>
                                @foreach ($pInfos as $item)
                                    <option value="{{ $item->id }}"
                                        {{ isset($sales->party_id) ? ($sales->party_id == $item->id ? 'selected' : '') : '' }}>
                                        {{ $item->pi_name }}</option>
                                @endforeach
                            </select>
                            <small id="available_balance" class="text-danger"></small>
                        </div>
                        <div class="col-2 col-left-padding d-flex align-items-center">
                            <a href="#" data-toggle="modal" data-target="#customerModal"><img
                                    src="{{ asset('assets/backend/app-assets/icon/add-icon.png') }}" alt=""
                                    srcset="" class="img-fluid" style="height:29px"></a>

                        </div>

                    </div>
                </div>

                <div class="" style="width:25%;margin-left:5px;">

                    <label for="">Site/Project</label>

                    <select name="job_project_id" id="job_project_id"
                        class="form-control common-select2 inputFieldHeight job_project_id" required>
                        <option value=""> Select...</option>
                        <option value="{{ $sales->site_project }}" selected>
                            {{ optional($sales->project)->project_name }} </option>
                    </select>

                </div>

                <div class="" style="width:15%; margin-left:5px;">

                    <label for="">Attention</label>

                    <input type="text" name="attention" id="attention" class="form-control inputFieldHeight"
                        placeholder="Attention" value="{{ $sales->attention }}">
                    @error('attention')
                        <div class="btn btn-sm btn-danger">{{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="d-none" style="width:10%;margin-left:5px;">

                    <label for="">Payment Mode</label>

                    <select name="pay_mode" id="pay_mode" class="form-control inputFieldHeight">
                        <option value="">Select...</option>

                        @foreach ($modes as $item)
                            <option value="{{ $item->title }}"
                                {{ isset($sales) ? ($sales->pay_mode == $item->title ? 'selected' : '') : '' }}>
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
                        <option value="Tax Invoice"
                            {{ isset($sales) ? ($sales->invoice_type == 'Tax Invoice' ? 'selected' : '') : '' }}>Tax
                            Invoice</option>
                        <option value="Proforma Invoice"
                            {{ isset($sales) ? ($sales->invoice_type == 'Proforma Invoice' ? 'selected' : '') : '' }}>
                            Proforma Invoice</option>
                        <option value="Direct Invoice">Direct Invoice</option>
                    </select>
                    @error('invoice_type')
                        <div class="btn btn-sm btn-danger">{{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="d-none" style="width:10%;margin-left:5px;">

                    <label for="">D.o No</label>

                    <input type="text" name="do_no" id="do_no" class="form-control inputFieldHeight"
                        placeholder="D.O No" value="{{ $sales->do_no }}">
                    @error('do_no')
                        <div class="btn btn-sm btn-danger">{{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="d-none" style="width:10%;margin-left:5px;">

                    <label for="">LPO No</label>


                    <input type="text" name="lpo_no" id="lpo_no" class="form-control inputFieldHeight"
                        placeholder="LPO No" value="{{ $sales->lpo_no }}">
                    @error('lpo_no')
                        <div class="btn btn-sm btn-danger">{{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="" style="width:13%;margin-left:5px;">

                    <label for="">Quotation No</label>


                    <input type="text" name="quotation_no" id="quotation_no"
                        class="form-control inputFieldHeight" placeholder="Quotation No"
                        value="{{ $sales->quotation_no }}">
                    @error('quotation_no')
                        <div class="btn btn-sm btn-danger">{{ $message }}
                        </div>
                    @enderror

                </div>



                <div class="col-md-12 cheque-content" style="display: {{ $sales->pay_mode == 'Cheque' ? '' : 'none' }}">
                    <div class="row">
                        <div class="col-md-5 changeColStyle">

                            <label for="">Issuing Bank</label>

                            <input type="text" autocomplete="off" name="issuing_bank" id="issuing_bank"
                                class="form-control inputFieldHeight" placeholder="Issuing Bank"
                                value="{{ $sales->issuing_bank }}" {{ $sales->pay_mode == 'Cheque' ? 'required' : '' }}>
                            @error('issuing_bank')
                                <div class="btn btn-sm btn-danger">{{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="" style="width:10%;margin-left:5px;">

                            <label for="">Branch</label>

                            <input type="text" autocomplete="off" name="bank_branch" id="bank_branch"
                                class="form-control inputFieldHeight" placeholder="Branch"
                                value="{{ $sales->branch }}" {{ $sales->pay_mode == 'Cheque' ? 'required' : '' }}>
                            @error('bank_branch')
                                <div class="btn btn-sm btn-danger">{{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="" style="width:10%;margin-left:5px;">

                            <label for="">Cheque No</label>

                            <input type="text" autocomplete="off" class="form-control inputFieldHeight"
                                name="cheque_no" placeholder="Cheque Number" id="cheque_no"
                                value="{{ $sales->cheque_no }}" {{ $sales->pay_mode == 'Cheque' ? 'required' : '' }}>
                            @error('cheque_no')
                                <div class="btn btn-sm btn-danger">{{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="" style="width:10%;margin-left:5px;">

                            <label for="">Deposit Date</label>

                            <input type="text" autocomplete="off"
                                class="form-control inputFieldHeight datepicker deposit_date" name="deposit_date"
                                placeholder="dd/mm/yyyy" value="{{ date('d/m/Y', strtotime($sales->deposit_date)) }}"
                                {{ $sales->pay_mode == 'Cheque' ? 'required' : '' }}>
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
    <div class="col-md-12 col-right-padding col-left-padding" style="margin-top:25px !important">
        <div class="row mx-1">
            <div class="cardStyleChange" style="width: 100%">
                <div class="card-body bg-white">
                    <table class="table table-bordered table-sm ">
                        <thead>
                            <tr class="text-center">
                                <th style="width:25%; text-align:left;">Description</th>
                                <th style="width: 12%">Amount</th>
                                <th style="width: 10%;color:#fff"> VAT (%) </th>
                                <th style="width: 8%;color:#fff"> VAT </th>
                                <th style="width: 15%;color:#fff"> Sub Total </th>

                                <th class="NoPrint" style="width: 1%;padding: 2px;"> <button type="button"
                                        class="btn btn-sm btn-success addBtn"style="border: 1px solid green;
                                    color: #fff; border-radius: 10px;padding: 5px;" onclick="addEditRow()"><i
                                            class="bx bx-plus" style="color: white;margin-top: -5px;"></i></button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="editTBody" class="sale-item">
                            @foreach ($sales->items as $key => $item)
                                <tr id="TRow" class="text-center invoice_row">
                                    <td>
                                        <div class="d-flex justy-content-between align-items-center">
                                            <textarea type="text" name="group-a[{{ $key }}][multi_acc_head]" placeholder="Item Description"
                                                class="form-control" style="height: 36px" required>{{ $item->item_description }} </textarea>

                                        </div>
                                    </td>



                                    <td>
                                        <input type="number" step="any"
                                            name="group-a[{{ $key }}][amount]" required
                                            value="{{ $item->amount }}"
                                            class="text-center form-control amount inputFieldHeight2"
                                            style="width: 100%;height:36px;" required>
                                    </td>


                                    <td>
                                        <select class="text-center form-control vat_rate inputFieldHeight2" required
                                            name="group-a[{{ $key }}][vat_rate]">

                                            <option value=""> Select... </option>
                                            <option value="5" {{ $item->vat > 0 ? 'selected' : '' }}> Standard (5)
                                            </option>
                                            <option value="0" {{ $item->vat <= 0 ? 'selected' : '' }}> 0 Rated (0)
                                            </option>
                                        </select>
                                    </td>

                                    <td>
                                        <input type="number" step="any"
                                            name="group-a[{{ $key }}][vat_amount]"
                                            value="{{ $item->vat }}"
                                            class="text-center form-control vat_amount inputFieldHeight2"
                                            style="width: 100%;height:36px;" readonly>
                                    </td>

                                    <td>
                                        <input type="number" step="any"
                                            name="group-a[{{ $key }}][sub_total]" required
                                            value="{{ $item->total_amount }}"
                                            class="text-center form-control sub_total inputFieldHeight2"
                                            style="width: 100%;height:36px;" readonly>
                                    </td>
                                    <td class="NoPrint"><button style="padding: 5px; margin: 4px;" type="button"
                                            class="btn btn-sm btn-danger"onclick="BtnDel(this)"><i class="bx bx-trash"
                                                style="color: white;margin-top: -5px;"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tbody>
                            <tr class="text-center d-none">
                                <td>
                                    <div class="d-flex justy-content-between align-items-center">
                                        <input type="text" name="retention" placeholder="Item Description"
                                            class="form-control" readonly value="Transferred to Retention Account">
                                    </div>
                                </td>
                                <td colspan="3">
                                    <input type="number" step="any" name="rentention_sub_total" required
                                        class="text-center form-control rentention_sub_total" style="width: 100%;"
                                        readonly>
                                </td>
                                <td>
                                    <input type="number" step="any" name="retention_amount" required value="{{$sales->retention_amount}}"
                                        class="text-center form-control retention_amount"
                                        style="width: 100%;height:36px;" readonly>
                                </td>






                                {{-- <td class="NoPrint"><button style="padding: 5px; margin: 4px;"
                                                        type="button"
                                                        class="btn btn-sm btn-danger"onclick="BtnDel(this)"><i class="bx bx-trash" style="color: white;margin-top: -5px;"></i></button>
                                                </td> --}}
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="4" class="text-right" style="color: black">TAXABLE <br><span class="d-none"><small><small> <input type="number" step="any" name="" value="{{$remaining}}"  style="border: none;background: transparent;    text-align: right;" class="taxable-amount" readonly id="">Remaining</small></small></span></td>
                                <td><input type="number" step="any" readonly id="taxable_amount"
                                        class="text-center form-control inputFieldHeight2 @error('taxable_amount') error @enderror inputFieldHeight taxable_amount"
                                        name="taxable_amount" value="{{ $sales->amount }}" placeholder="Amount"
                                        readonly required>
                                    @error('taxable_amount')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right" style="color: black">VAT <br><span class="d-none"><small><small> <input type="number" step="any" name="" value="{{$remaining_vat}}"  style="border: none;background: transparent;    text-align: right;" class="remaining-vat" readonly id="">Remaining</small></small></span></td>
                                <td><input type="number" step="any" readonly id="total_vat"
                                        class="text-center inputFieldHeight2 form-control @error('total_vat') error @enderror inputFieldHeight total_vat"
                                        name="total_vat" value="{{ $sales->vat }}"
                                        placeholder="@if (!empty($currency->vat_name)) {{ $currency->vat_name }} @endif SUBTOTAL"
                                        readonly required>
                                    @error('total_vat')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right" style="color: black"> RETENTION AMOUNT</td>
                                <td><input type="number" step="any"  id="retention_transferred"
                                        class="text-center form-control @error('retention_transferred') error @enderror inputFieldHeight retention_transferred"
                                        name="retention_transferred" value="{{$sales->retention_amount}}" placeholder="TOTAL VAT"
                                        required>
                                    @error('retention_transferred')
                                        <span class="error">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right" style="color: black">TOTAL AMOUNT <br><span class="d-none"><small><small> <input type="number" step="any" name="" value="{{$total_remaining}}"  style="border: none;background: transparent;    text-align: right;" class="total-remaining" readonly id="">Remaining</small></small></span></td>
                                <td><input type="number" step="any" readonly id="total_amount"
                                        class="text-center inputFieldHeight2 form-control @error('total_amount') error @enderror inputFieldHeight total_amount"
                                        name="total_amount" value="{{ $sales->total_amount }}" placeholder="TOTAL "
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
                                <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}" alt=""
                                    srcset="" width="25">
                            </div>
                            <div><span>Save</span></div>
                        </div>
                    </button>

                </div>
            </div>

        </div>
    </div>
</form>

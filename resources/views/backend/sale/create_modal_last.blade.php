<div class="modal fade" id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding:5px 10px;background:#364a60;">
                <h5 class="modal-title" id="exampleModalLabel"
                    style="font-family:Cambria;font-size: 2rem;color:white;margin-left: 10px;"> Tax Invoice </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" style="padding: 0;">
                <form action="{{ route('saleIssuepost') }}" method="POST" id="formSubmit"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="cardStyleChange bg-white">
                        <div class="card-body">

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


                                <div class="search-item-pi" style="width:30%;">
                                    <label for=""> Party / Owner  Name </label>

                                    <div class="row align-items-center">
                                        <div class="col-10 customer-select">
                                            <select name="party_info" id="party_id"
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

                                <div style="width:27%;margin-left:5px;">
                                    <label for=""> Project </label>

                                    <select name="job_project_id" id="job_project_id" class="form-control common-select2 job_project_id" required>
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

                                <div class="d-none" style="">

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


                                <div class="d-none" style="">

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



                                <div style="" class="d-none">

                                        <label for="">D.o No</label>

                                        <input type="text" name="do_no" id="do_no"
                                        class="form-control inputFieldHeight"
                                        placeholder="D.O No">
                                        @error('do_no')
                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                            </div>
                                        @enderror
                                </div>

                                <div style="" class="d-none">

                                    <label for="">LPO No</label>

                                    <input type="text" name="lpo_no" id="lpo_no"
                                    class="form-control inputFieldHeight"
                                    placeholder="LPO No">
                                    @error('lpo_no')
                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div style="width:12%;margin-left:5px;">

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
                        style="margin-top:10px !important">
                        <div class="row mx-1">
                            <div class="cardStyleChange" style="width: 100%">
                                <div class="card-body bg-white">
                                    <table class="table table-bordered table-sm ">
                                        <thead style="background:#34465b;color:#fff;">
                                            <tr>
                                                <th style="width: 40%;color:#fff; text-align:left !important; padding:4px;">Description</th>
                                                {{-- <th style="width: 10%;color:#fff">QTY</th>

                                                <th style="width: 10%;color:#fff">Unit</th>
                                                <th style="width: 15%;color:#fff">Rate</th> --}}
                                                <th style="width: 15%;color:#fff">Amount</th>
                                                <th style="width: 15%;color:#fff"> VAT (%) </th>
                                                <th style="width: 10%;color:#fff"> VAT   </th>
                                                <th style="width: 15%;color:#fff"> Sub Total  </th>

                                                <th class="NoPrint" style="width: 1%;padding: 2px;"> <button type="button"
                                                        class="btn btn-sm btn-success addBtn"style="border: 1px solid green;
                                                    color: #fff; border-radius: 10px;padding: 5px;"
                                                        onclick="BtnAdd()"><i class="bx bx-plus" style="color: white;margin-top: -5px;"></i></button>
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody id="TBody" class="sale-item">
                                            <tr id="TRow" class="text-center invoice_row">
                                                <td>
                                                    <div
                                                        class="d-flex justy-content-between align-items-center">
                                                        <textarea type="text" name="group-a[0][multi_acc_head]"  placeholder="Item Description" class="form-control" style="height: 36px" required> </textarea>
                                                    </div>
                                                </td>

                                                <td>
                                                    <input type="number" step="any"
                                                        name="group-a[0][amount]" required
                                                        class="text-center form-control amount"
                                                        style="width: 100%;height:36px;">
                                                </td>

                                                <td>
                                                    <select
                                                        class=" form-control vat_rate" required
                                                        name="group-a[0][vat_rate]">

                                                        <option value=""> Select... </option>
                                                        <option value="5"> Standard (5) </option>
                                                        <option value="0"> 0 Rated (0) </option>
                                                    </select>
                                                </td>

                                                <td>
                                                    <input type="number" step="any"
                                                        name="group-a[0][vat_amount]" required
                                                        class="text-center form-control vat_amount"
                                                        style="width: 100%;height:36px;" readonly>
                                                </td>

                                                <td>
                                                    <input type="number" step="any"
                                                        name="group-a[0][sub_total]" required
                                                        class="text-center form-control sub_total"
                                                        style="width: 100%;height:36px;" readonly>
                                                </td>

                                                <td class="NoPrint"><button style="padding: 5px; margin: 4px;"
                                                        type="button"
                                                        class="btn btn-sm btn-danger"onclick="BtnDel(this)"><i class="bx bx-trash" style="color: white;margin-top: -5px;"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>

                                        <tbody>
                                            <tr class="text-center d-none">
                                                <td>
                                                    <div
                                                        class="d-flex justy-content-between align-items-center">
                                                        <input type="text" name="retention"  placeholder="Item Description" class="form-control" readonly value="Transferred to Retention Account">
                                                    </div>
                                                </td>

                                                <td colspan="3">
                                                    <input type="number" step="any"
                                                        name="rentention_sub_total" required
                                                        class="text-center form-control rentention_sub_total"
                                                        style="width: 100%;" readonly>
                                                </td>

                                                <td>
                                                    <input type="number" step="any"
                                                        name="retention_amount" required
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
                                                <td colspan="4" class="text-right" style="color: black"> TAXABLE AMOUNT </td>
                                                <td><input type="number" step="any" readonly id="taxable_amount"
                                                        class="text-center form-control @error('taxable_amount') error @enderror  taxable_amount"
                                                        name="taxable_amount" value=""
                                                        placeholder="TAXABLE AMOUNT " readonly required>
                                                    @error('taxable_amount')
                                                        <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-right" style="color: black"> TOTAL VAT</td>
                                                <td><input type="number" step="any" readonly id="total_vat"
                                                        class="text-center form-control @error('total_vat') error @enderror  total_vat"
                                                        name="total_vat" value=""
                                                        placeholder="TOTAL VAT"
                                                        readonly required>
                                                    @error('total_vat')
                                                        <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                            </tr>
                                             <tr>
                                                <td colspan="4" class="text-right" style="color: black"> RETENTION AMOUNT</td>
                                                <td><input type="number" step="any" value="0.00"  id="retention_transferred"
                                                        class="text-center form-control @error('retention_transferred') error @enderror  retention_transferred"
                                                        name="retention_transferred"
                                                        placeholder="TOTAL VAT"
                                                         required>
                                                    @error('retention_transferred')
                                                        <span class="error">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-right" style="color: black">TOTAL AMOUNT</td>
                                                <td><input type="number" step="any" readonly id="total_amount"
                                                        class="text-center form-control @error('total_amount') error @enderror  total_amount"
                                                        name="total_amount" value=""
                                                        placeholder="TOTAL AMOUNT"
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

                                <div class="col-sm-4 form-group">
                                    <label for="">File Upload</label>
                                    <input type="file" class="form-control inputFieldHeight file_upload" id="voucher_scan"
                                        name="voucher_scan[]" accept="image/*" multiple>

                                    <ul class="fileList">

                                    </ul>
                                </div>

                                <div class="col-sm-6 d-flex">
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

                                    <a class="btn btn-warning  d-none" onClick="refreshPage()"
                                        id="newButton">New</a>

                                </div>

                                <div class="col-sm-3 form-group">

                                </div>
                            </div>
                            <div id="preview-images" class="mt-2 ml-1 d-flex flex-wrap"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

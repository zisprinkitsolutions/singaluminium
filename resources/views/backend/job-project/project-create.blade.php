<div class="modal-content">
    <div class="modal-header" style="padding: 5px 18px;background:#364a60;">
        <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">New Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form action="{{ route('projects.store') }}" method="POST" id="formSubmit" enctype="multipart/form-data" class="text-left">
        @csrf
        <div class="cardStyleChange bg-white">
            <div class="card-body ">

                <div class="row mx-1 pt-1">
                    
                    <div class="col-md-1 pl-0">
                        <label for="">Date</label>

                        <input type="text" value="{{ Carbon\Carbon::now()->format('d/m/Y') }}" class="form-control inputFieldHeight datepicker" name="date" placeholder="dd-mm-yyyy" required>
                        @error('date')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                        @enderror

                    </div>
                    <div class="search-item-pi col-md-3 pr-0">
                        <div class="row align-items-center">
                            <div class="col-10 pl-0">
                                <label for="">Payee</label>

                                <select name="party_info" id="party_info" class="common-select2 party-info customer" style="width: 100% !important" required>
                                    <option value="">Select...</option>
                                    @foreach ($party as $item)
                                        <option value="{{ $item->id }}" > {{ $item->pi_name }}</option>
                                    @endforeach
                                </select>
                                @error('party_info')
                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-2 col-left-padding d-flex align-items-center pl-0" style="margin-top:15px;">
                                <a href="#" data-toggle="modal"
                                    data-target="#customerModal"><img
                                        src="{{ asset('assets/backend/app-assets/icon/add-icon.png') }}"
                                        alt="" srcset="" class="img-fluid"
                                        style="height:29px"></a>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-2 pl-0">
                        <label for="">Project Name</label>
                        <input type="text" name="project_name" id="project_name" class="form-control inputFieldHeight" value="" required>
                    </div>

                    <div class="col-md-2 pl-0">
                        <label for="">Project No</label>
                        <input type="text" name="project_no" id="project_no" class="form-control inputFieldHeight" value="" required>
                    </div>
                    <div class="col-md-2 pl-0">
                        <label for="">Address</label>
                        <input type="text" name="address" id="address" class="form-control inputFieldHeight" value="">
                    </div>
                    <div class="col-md-1 pl-0">
                        <label for="">Start Date</label>
                        <input type="text" name="start_date" id="start_date" class="form-control inputFieldHeight datepicker" value="">
                    </div>
                    <div class="col-md-1 pl-0 pr-0">
                        <label for="">End Date</label>
                        <input type="text" name="end_date" id="end_date" class="form-control inputFieldHeight datepicker" value="">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-right-padding col-left-padding" style="margin-top:25px !important">
            <div class="row mx-1">
                <div class="cardStyleChange" style="width: 100%">
                    <div class="card-body bg-white">
                        <table class="table  table-sm ">
                            <thead>
                                <tr>
                                    <th >Description</th>
                                    <th style="width: 10%">QTY</th>
                                    <th style="width: 10%">SQM</th>
                                    <th style="width: 10%">Rate</th>
                                    <th style="width: 15%">Total Amount</th>
                                    <th class="NoPrint" style="width: 20px;padding: 2px;">
                                        <button type="button" class="btn btn-sm btn-success addBtn"style="border: 1px solid green; color: #fff; border-radius: 10px;padding: 5px;" onclick="BtnAdd('#TRow', '#TBody','group-a')">
                                            <i class="bx bx-plus" style="color: white;margin-top: -5px;"></i>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="TBody">
                                <tr id="TRow" class="text-center invoice_row d-none">
                                    <td>
                                        <div class="d-flex justy-content-between align-items-center" >
                                            <input type="text" name="group-a[0][description]" disabled  placeholder="Item Description" class="form-control inputFieldHeight description" required>
                                        </div>
                                    </td>

                                    <td>
                                        <input type="number" step="any" name="group-a[0][qty]" step="any" placeholder="QTY" class="text-center form-control inputFieldHeight qty"style="width: 100%;" disabled required>
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="group-a[0][sqm]" step="any" placeholder="SQM" class="text-center form-control inputFieldHeight sqm"style="width: 100%;" disabled required>
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="group-a[0][amount]" step="any" required placeholder="Rate" disabled class="text-center form-control inputFieldHeight amount"style="width: 100%;">
                                    </td>

                                    <td>
                                        <input type="number" step="any" name="group-a[0][sub_gross_amount]" required disabled class="text-center form-control sub_gross_amount inputFieldHeight" placeholder="Total Amount" style="width: 100%;" readonly>
                                    </td>
                                    <td class="NoPrint add_button text-center d-flex" style="margin-top: 5px;">
                                        <button type="button" class="bg-danger custom-btn" onclick="BtnDelItem(this)">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td class="text-right pr-1" colspan="4" style="color: black">AMOUNT</td>
                                    <td><input type="number" step="any" readonly
                                            id="taxable_amount"
                                            class="text-center form-control inputFieldHeight2 @error('taxable_amount') error @enderror inputFieldHeight taxable_amount"
                                            name="taxable_amount" value=""
                                            placeholder="AMOUNT" readonly required>
                                        @error('taxable_amount')
                                            <span class="error">{{ $message }}</span>
                                        @enderror
                                    </td>
                                </tr>

                                <tr class="text-center">
                                    <td class="text-right pr-1" colspan="4" style="color: black">VAT 5%</td>
                                    <td><input type="number" step="any" readonly
                                            id="total_vat"
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
                                    <td class="text-right pr-1" colspan="4" style="color: black">TOTAL AMOUNT</td>
                                    <td><input type="number" step="any" readonly
                                            id="total_amount"
                                            class="text-center inputFieldHeight2 form-control @error('total_amount') error @enderror inputFieldHeight total_amount"
                                            name="total_amount" value=""
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
                <div class="d-flex justify-content-center align-items-center mt-2 mb-1">
                    <button type="submit" class="btn btn-primary formButton" id="submitButton">
                        <div class="d-flex">
                            <div class="formSaveIcon">
                                <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}" alt="" srcset="" width="25">
                            </div>
                            <div><span>Save</span></div>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

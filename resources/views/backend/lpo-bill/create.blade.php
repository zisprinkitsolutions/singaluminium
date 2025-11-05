<div class="modal-content">
    <div class="modal-header" style="padding: 5px 18px;background:#364a60;">
        <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">New LPO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form action="{{ route('lpo-bill-store') }}" method="POST" id="formSubmit" enctype="multipart/form-data">
        @csrf
        <div class="cardStyleChange bg-white text-left">
            <div class="card-body">
                <div class="row mx-1">
                    <div class="col-md-2 col-left-padding">
                        <label for=""> Date </label>
                        <input type="text" value="{{ Carbon\Carbon::now()->format('d/m/Y') }}" class="form-control inputFieldHeight datepicker" name="date" placeholder="dd-mm-yyyy">
                        @error('date')
                            <div class="btn btn-sm btn-danger">{{ $message }} </div>
                        @enderror
                    </div>
                    <div class="col-md-4 col-left-padding">
                        <label for="">Payee</label>
                        <div class="row align-items-center">
                            <div class="col-10 customer-select">
                                <select name="party_info" id="party_info" class="common-select2 party-info customer" style="width: 100% !important" data-target="" required>
                                    <option value="">Select...</option>
                                    @foreach ($pInfos as $item)
                                        <option value="{{ $item->id }}"> {{ $item->pi_name }} </option>
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
                    <div class="col-md-1 d-none">
                        <label for="">Plot No</label>
                        <input type="text" id="plot_no" class="form-control inputFieldHeight" placeholder="Enter plot no">
                    </div>
                    <div class="col-md-3 col-left-padding">
                        <div class="form-group">
                            <label for=""> Project </label>
                            <select name="project_id" id="project_id"
                                class="common-select2 project-select" style="width: 100% !important"
                                data-target="" >
                                <option value="">Select...</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        data-no="{{ optional($project->new_project)->plot }}">
                                        {{ $project->project_name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-left-padding">
                        <label for=""> Attention </label>
                        <input type="text" class="form-control inputFieldHeight" name="attention" placeholder="Attention">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-right-padding col-left-padding" style="margin-top:25px !important">
            <div class="row mx-1">
                <div class="cardStyleChange" style="width: 100%">
                    <div class="card-body bg-white">
                        <table class="table table-sm ">
                            <thead>
                                <tr>
                                    <th style="width: 20%;color:#fff !important; text-align:left !important; padding-left:9px;"> Description</th>
                                    <th style="color:#fff !important;width: 15%;">Project Task</th>
                                    {{-- <th style="color:#fff !important;width: 15%;">Sub Task</th> --}}
                                    <th style="color:#fff !important;">QTY</th>
                                    <th style="color:#fff !important; width:8%;"> Unit </th>
                                    <th style="color:#fff !important;">Rate</th>
                                    <th style="color:#fff !important;">Amount</th>
                                    <th class="vat-exist" style="width: 8%;color:#fff !important;">Vat Rate </th>
                                    <th class="vat-exist" style="width: 5%;color:#fff !important;">Vat</th>
                                    <th style="color:#fff !important;">Total Amount</th>
                                    <th class="NoPrint" style="width: 5%;padding: 2px;color:#fff;">
                                        <button type="button" class="btn btn-sm btn-success addBtn"style="border: 1px solid green; color: #fff; border-radius: 10px;padding: 5px;" onclick="BtnAdd()">
                                            <i class="bx bx-plus" style="color: white;margin-top: -5px;"></i>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="TBody">
                                <tr id="TRow" class="text-center invoice_row">
                                    <td style="padding: 0">
                                        <input name="group-a[0][multi_acc_head]" placeholder="Item Description" cols="30" rows="1" class="  form-control col-left-padding col-right-padding" list="acc_head_list" required>
                                        <datalist id="acc_head_list">
                                            @foreach ($heads as $item)
                                                <option value="{{$item->name}}">
                                            @endforeach
                                        </datalist>
                                    </td>
                                    <td style="padding: 0">
                                        <select name="group-a[0][task_id]" class="empty_field project_task form-control col-left-padding col-right-padding">
                                            <option value="">Select....</option>
                                        </select>
                                    </td>
                                    <td style="padding: 0" class="d-none">
                                        <select name="group-a[0][sub_task_id]" class="empty_field sub_task_id form-control col-left-padding col-right-padding">
                                            <option value="">Select....</option>
                                        </select>
                                    </td>
                                    <td style="padding: 0">
                                        <input type="number" step="any" name="group-a[0][quantity]" required placeholder="QTY" class="  text-center form-control quantity col-left-padding col-right-padding"style="width: 100%;height:36px;">
                                    </td>

                                    <td style="padding: 0">
                                        <select type="number" step="any" name="group-a[0][unit]" class="  text-center form-control col-left-padding col-right-padding"style="width: 100%;height:36px;">
                                            <option value="">Select...</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }} </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td style="padding: 0">
                                        <input type="number" step="any" name="group-a[0][rate]" step="any" required placeholder="Rate" class="  text-center form-control rate col-left-padding col-right-padding"style="width: 100%;height:36px;">
                                    </td>

                                    <td style="padding: 0">
                                        <input type="number" step="any" name="group-a[0][amount]" step="any" required placeholder="Amount" class="  text-center form-control amount col-left-padding col-right-padding"style="width: 100%;height:36px;">
                                    </td>

                                    <td class="vat-exist" style="padding: 0">
                                        <select name="group-a[0][vat_rate]" required
                                            class="vat_rate form-control  "
                                            style="width: 100%;HEIGHT: 36PX;text-align:center;">
                                            <option value=""> Select..
                                            </option>
                                            @foreach ($vats as $vat)
                                                <option value="{{ $vat->value }}">
                                                    {{ $vat->name . ' (' . $vat->value . ')' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="vat-exist" style="padding: 0"><input type="number" step="any"
                                            class="text-center form-control vat_amount  " required
                                            placeholder="Vat Amount" name="group-a[0][vat_amount]"
                                            readonly>
                                    </td>
                                    <td style="padding: 0">
                                        <input type="number" step="any"
                                            name="group-a[0][sub_gross_amount]" required
                                            class="text-center form-control sub_gross_amount  "
                                            placeholder="Amount" style="width: 100%;height:36px;"
                                            readonly>
                                    </td>
                                    </td>
                                    <td class="NoPrint text-center d-flex" style="padding: 0">
                                        <button type="button" class="addItemBtn bg-info text-white" style="border: 1px solid #ddd; padding: 7px;" title="Add Item">+</button>
                                        <button type="button" class="removeItemBtn bg-danger text-white" style="border: 1px solid #ddd; padding: 7px;" title="Remove Item" onclick="BtnDel(this)">X</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td colspan="5"></td>
                                    <td colspan="2" class="text-right pr-1" style="color: black">TOTAL</td>
                                    <td colspan="2"><input type="number" step="any" readonly
                                            id="taxable_amount"
                                            class="text-center form-control @error('taxable_amount') error @enderror taxable_amount"
                                            name="taxable_amount" value=""
                                            placeholder="Amount" readonly required>
                                        @error('taxable_amount')
                                            <span class="error">{{ $message }}</span>
                                        @enderror
                                    </td>
                                </tr>
                                <tr class="text-center">
                                    <td colspan="5"></td>
                                    <td colspan="2" class="text-right pr-1" style="color: black">VAT</td>
                                    <td colspan="2"><input type="number" step="any" readonly
                                            id="total_vat"
                                            class="text-center form-control @error('total_vat') error @enderror  total_vat"
                                            name="total_vat" value=""
                                            placeholder="@if (!empty($currency->vat_name)) {{ $currency->vat_name }} @endif SUBTOTAL"
                                            readonly required>
                                        @error('total_vat')
                                            <span class="error">{{ $message }}</span>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td colspan="2" class="text-right pr-1" style="color: black">TOTAL AMOUNT</td>
                                    <td colspan="2"><input type="number" step="any" readonly
                                            id="total_amount"
                                            class="text-center form-control @error('total_amount') error @enderror total_amount"
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
                <div class="row px-1">

                    <div class="col-sm-11 col-right-padding ">
                        <div class="row">
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

                    <div class="col-sm-1 text-right d-flex justify-content-end mb-1"
                        style="margin-top:18px;">
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
</div>
<div class="modal-content">
    <div class="modal-header" style="padding: 5px 18px;background:#364a60;">
        <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">Expense</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form action="{{ route('expensepost') }}" method="POST" id="formSubmit" enctype="multipart/form-data" class="text-left">
        @csrf
        <div class="cardStyleChange bg-white">
            <div class="card-body ">

                <div class="row mx-1 pt-1">
                    <div class="col-md-3 changeColStyle  col-right-padding d-none">
                        <div class="row d-flex align-items-center">
                            <div class="col-3">
                                <label for="project">Branch</label>
                            </div>
                            <div class="col-9">
                                <select name="project" class="common-select2 w-100" id="project"
                                    required>
                                    @foreach ($projects as $item)
                                        <option value="{{ $item->id }}" >
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
                            <div class="col-3">
                                <label for="">Party Code</label>
                            </div>
                            <div class="col-9">
                                <input type="text" name="pi_code" id="pi_code"
                                    class="form-control inputFieldHeight"
                                    placeholder="Party Code">
                                @error('party_info')
                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div style="width:12%;margin-right:5px;">
                        <label for="">Date</label>

                        <input type="text"
                            value="{{ Carbon\Carbon::now()->format('d/m/Y') }}"
                            class="form-control inputFieldHeight datepicker"
                            name="date" placeholder="dd-mm-yyyy">
                        @error('date')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                        @enderror

                    </div>
                    <div class="search-item-pi" style="width:40%">
                        <div class="row align-items-center">
                            <div class="col-10">
                                <label for="">Payee</label>

                                <select name="party_info" id="party_info" class="common-select2 party-info customer" style="width: 100% !important" required>
                                    <option value="">Select...</option>
                                    @foreach ($pInfos as $item)
                                        <option value="{{ $item->id }}" > {{ $item->pi_name }}</option>
                                    @endforeach
                                </select>
                                @error('party_info')
                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-2 col-left-padding d-flex align-items-center" style="margin-top:15px;">
                                <a href="#" data-toggle="modal"
                                    data-target="#customerModal"><img
                                        src="{{ asset('assets/backend/app-assets/icon/add-icon.png') }}"
                                        alt="" srcset="" class="img-fluid"
                                        style="height:29px"></a>
                            </div>

                        </div>
                    </div>

                    <div style="width:15%;margin-left:5px;" class="d-none">

                            <label for="">Attention</label>

                            <input type="text" name="attention" id="attention"
                                class="form-control inputFieldHeight"
                                placeholder="Attention">
                            @error('attention')
                                <div class="btn btn-sm btn-danger">{{ $message }}
                                </div>
                            @enderror

                    </div>



                    <div style="width:13%;margin-left:5px;" class="d-none">
                        <label for="">Payment Mode </label>
                        <select name="pay_mode" id="pay_mode"
                            class="form-control inputFieldHeight" required>
                            <option value="">Select...</option>
                            @foreach ($modes as $item)
                                <option value="{{ $item->title }}" {{$item->title=='Credit'?'selected':''}}> {{ $item->title }} </option>
                            @endforeach
                        </select>
                        <small id="pay_available_balance" class="text-danger"></small>
                    </div>


                    <div style="width:12%;margin-left:5px;">
                        <label for="">Bill No</label>

                        <input type="text" name="invoice_no" id="invoice_no"
                            class="form-control inputFieldHeight" value="">
                        @error('pay_mode')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                        @enderror
                    </div>



                    <div class="col-md-3 changeColStyle d-none">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label for="">Bill</label>
                            </div>
                            <div class="col-9">
                                <input type="text" name="" id=""
                                    class="form-control inputFieldHeight"
                                    value="{{ $purchase_expense_no }}" disabled>
                                @error('pay_mode')
                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div style="width:12%;margin-left:5px;">

                        <label for="">Bill Type</label>

                        <select name="invoice_type" id="invoice_type"
                            class="form-control inputFieldHeight" required>
                            <option value="Tax Invoice">With VAT</option>
                            <option value="Proforma Invoice">Without VAT</option>
                        </select>
                        @error('invoice_type')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                        @enderror

                    </div>
                        {{-- <div style="width:12%;margin-left:5px;">
                        <label for="">Paid By</label>
                        <input type="text" name="paid_by" id="paid_by" class="form-control inputFieldHeight" value="">
                        @error('paid_by')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                        @enderror
                    </div> --}}

                    <div class="col-md-12 cheque-content" style="display: none">
                        <div class="row">
                            <div class="col-md-5 changeColStyle">
                                <div class="row align-items-center">
                                    <div class="col-3">
                                        <label for="">Issuing Bank</label>
                                    </div>
                                    <div class="col-9 col-left-padding">
                                        <input type="text" autocomplete="off" name="issuing_bank"
                                            id="issuing_bank" class="form-control inputFieldHeight"
                                            placeholder="Issuing Bank">
                                        @error('issuing_bank')
                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 changeColStyle">
                                <div class="row align-items-center">
                                    <div class="col-2">
                                        <label for="">Branch</label>
                                    </div>
                                    <div class="col-10">
                                        <input type="text" autocomplete="off" name="bank_branch"
                                            id="bank_branch" class="form-control inputFieldHeight"
                                            placeholder="Branch">
                                        @error('bank_branch')
                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 changeColStyle">
                                <div class="row align-items-center">
                                    <div class="col-5 col-right-padding">
                                        <label for="">Cheque No</label>
                                    </div>
                                    <div class="col-7 col-left-padding">
                                        <input type="text" value="" autocomplete="off"
                                            class="form-control inputFieldHeight" name="cheque_no"
                                            placeholder="Cheque Number" id="cheque_no">
                                        @error('cheque_no')
                                            <div class="btn btn-sm btn-danger">{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 changeColStyle">
                                <div class="row align-items-center">
                                    <div class="col-6 col-right-padding">
                                        <label for="">Deposit Date</label>
                                    </div>
                                    <div class="col-6 col-left-padding">
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
            </div>
        </div>

        <div class="col-md-12 col-right-padding col-left-padding" style="margin-top:25px !important">
            <div class="row mx-1">
                <div class="cardStyleChange" style="width: 100%">
                    <div class="card-body bg-white">
                        <table class="table  table-sm ">
                            <thead>
                                <tr>
                                    <th style="width: 25%">
                                        Account Head
                                        <a href="" title="Add New Expense Head" class="expanse_add" style="margin-left: 5px;">
                                            <img src="{{ asset('assets/backend/app-assets/icon/add-icon.png') }}" alt="" class="img-fluid" style="height: 20px;">
                                        </a>
                                    </th>
                                    <th >Description</th>
                                    <th style="width: 8%">QTY</th>
                                    <th style="width: 8%">Unit</th>
                                    {{-- <th style="width: 8%">Type</th> --}}
                                    <th style="width: 8%">Amount</th>
                                    <th class="vat-exist" style="width: 10%">Vat Rate</th>
                                    <th class="vat-exist" style="width: 8%">VAT</th>
                                    <th style="width: 10%">Total Amount</th>
                                    <th class="NoPrint" style="width: 20px;padding: 2px;"> <button type="button"
                                            class="btn btn-sm btn-success addBtn"style="border: 1px solid green;
                                                color: #fff; border-radius: 10px;padding: 5px;"
                                            onclick="BtnAdd('#TRow', '#TBody','group-a')"><i class="bx bx-plus" style="color: white;margin-top: -5px;"></i></button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="TBody">
                                <tr id="TRow" class="text-center invoice_row d-none">
                                    <td>
                                        <select name="group-a[0][head_id]" required class="form-control expense_head account-head" disabled >
                                            <option value="">Select....</option>
                                            @foreach ($special_heads as $item)
                                                <option value="{{$item->id}}" class="head" data-subsidiary="No" data-value="Yes" data-unit="{{$item->is_unit}}">{{$item->fld_ac_head}}</option>
                                                @foreach ($item->sub_heads as $sub_head)
                                                    <option value="Sub{{$sub_head->id}}" class="sub-head" data-subsidiary="No" data-value="Yes" data-unit="{{$sub_head->unit_id}}"> &nbsp;&nbsp;&nbsp; |---{{$sub_head->name}}</option>
                                                @endforeach
                                            @endforeach
                                            @foreach ($account_heads as $item)
                                                <option value="{{$item->id}}" class="head" data-subsidiary="{{$item->id==1758?'No':'Yes'}}" data-value="No" data-unit="{{$item->is_unit}}">{{$item->fld_ac_head}}</option>
                                                @foreach ($item->sub_heads as $sub_head)
                                                    <option value="Sub{{$sub_head->id}}" class="sub-head" data-subsidiary="{{$sub_head->account_head_id==1758?'No':'Yes'}}" data-value="No" data-unit="{{$sub_head->unit_id}}"> &nbsp;&nbsp;&nbsp; |---{{$sub_head->name}}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="d-flex justy-content-between align-items-center" >
                                            <input type="text" name="group-a[0][multi_acc_head]" disabled  placeholder="Item Description" class="form-control inputFieldHeight description">
                                        </div>
                                    </td>

                                    <td>
                                        <input type="number" step="any" name="group-a[0][qty]" step="any" placeholder="QTY" class="text-center form-control inputFieldHeight qty"style="width: 100%;" value="1" disabled>
                                    </td>

                                    <td class="unit-exist">
                                        <select name="group-a[0][unit_id]" disabled
                                            class="inputFieldHeight unit form-control empty_field"
                                            style="width: 100%;text-align:center;">
                                            <option value="">Select....</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="type-exist d-none">
                                        <select name="group-a[0][type]" disabled class="inputFieldHeight2 type form-control empty_field" style="width: 100%;HEIGHT: 36PX;text-align:center;">
                                            <option value="">Select....</option>
                                            <option value="Raw Material">Raw Material</option>
                                            <option value="Expense">Expense</option>
                                        </select>
                                    </td>

                                    <td>
                                        <input type="number" step="any" name="group-a[0][amount]" step="any" required placeholder="Amount" disabled class="text-center form-control inputFieldHeight amount"style="width: 100%;">
                                    </td>

                                    <td class="vat-exist">
                                        <select name="group-a[0][vat_rate]" required disabled
                                            class="inputFieldHeight vat_rate form-control empty_field"
                                            style="width: 100%;text-align:center;">
                                            <option value="">Select.... </option>
                                            @foreach ($vats as $vat)
                                                <option value="{{ $vat->value }}" {{$vat->value == 5?'selected':''}}>
                                                    {{ $vat->name . ' (' . $vat->value . ')' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="vat-exist">
                                        <input type="number" step="any" class="text-center form-control vat_amount inputFieldHeight" required placeholder="VAT Amount" disabled name="group-a[0][vat_amount]" readonly>
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="group-a[0][sub_gross_amount]" required disabled class="text-center form-control sub_gross_amount inputFieldHeight" placeholder="Total Amount" style="width: 100%;" readonly>
                                    </td>
                                    </td>
                                    <td class="NoPrint add_button text-center d-flex" style="margin-top: 5px;">
                                        <button style="padding:5px 5px !important;" type="button" class="bg-success custom-btn d-none project_add" title="Project Expense Add" onclick="CogsBtnProjectItem(this)">
                                            <i class="bx bx-plus" style="color: white;margin-top: -5px;"></i>
                                        </button>
                                        {{-- <button style="padding:5px 5px !important;" type="button" class="bg-success custom-btn d-none subsidiary_add" title="Subsidiary Add" onclick="subsidiaryAdd(this)">
                                            <i class="bx bx-plus" style="color: white;margin-top: -5px;"></i>
                                        </button> --}}
                                        <button type="button" class="bg-danger custom-btn" onclick="BtnDelItem(this)">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="colspan-update"></td>
                                    <td class="text-right pr-1" colspan="2" style="color: black">AMOUNT</td>
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
                                    <td colspan="5" class="colspan-update"></td>
                                    <td class="text-right pr-1" colspan="2" style="color: black">VAT</td>
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
                                    <td colspan="5" class="colspan-update"></td>
                                    <td class="text-right pr-1" colspan="2" style="color: black">TOTAL AMOUNT</td>
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

        <div class="col-md-12 col-right-padding col-left-padding d-none" style="margin-top:25px !important">
            <div class="row mx-1">
                <div class="cardStyleChange" style="width: 100%">
                    <div class="card-body bg-white">

                    </div>
                </div>

            </div>
        </div>
        <div class="cardStyleChange">
            <div class="card-body bg-white">
                <div class="row px-1">
                    <div class="col-sm-4 form-group">
                        <label for="">Voucher Scan/File</label>
                        <input type="file" class="form-control inputFieldHeight"
                            name="voucher_scan[]" accept="image/*,application/pdf" multiple id="fileInput">
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="">Narration</label>
                        <input type="text" class="form-control inputFieldHeight"
                            name="narration" id="narration" placeholder="Narration"
                            value="{{ isset($journalF) ? $journalF->narration : '' }}">
                    </div>
                    <div class="col-md-12" id="fileList">
                        <div class="col-md-12"></div>
                    </div>
                </div>
                <div class="d-flex justify-content-center align-items-center mt-2 mb-1">
                    <button type="submit" class="btn btn-primary formButton" id="submitButton">
                        <div class="d-flex">
                            <div class="formSaveIcon">
                                <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}"
                                    alt="" srcset="" width="25">
                            </div>
                            <div><span>Save</span></div>
                        </div>
                    </button>
                    <a href="{{route("purchase-expense")}}" class="btn btn-warning  d-none" id="newButton">New</a>
                </div>
            </div>
        </div>
    </form>
</div>

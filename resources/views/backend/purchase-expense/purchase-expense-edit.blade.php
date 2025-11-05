<div class="modal-content">
    <div class="modal-header" style="padding: 5px 18px;background:#364a60;">
        <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">Expense</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form action="{{ route('expense-edit-post', $purchase->id) }}" method="POST" id="editFormSubmit" enctype="multipart/form-data">
        @csrf
        <div class="cardStyleChange bg-white">
            <div class="card-body ">
                <input type="hidden" name="purchase_id" value="{{$purchase->id}}" id="purchase_id">
                <div class="row mx-1 pt-1">
                    <div class="col-md-2 changeColStyle  col-right-padding d-none">
                        <div class="row d-flex align-items-center">
                            <div class="col-3">
                                <label for="project">Branch</label>
                            </div>
                            <div class="col-9">
                                <select name="project" class="common-select2 w-100" id="project"
                                    required>
                                    @foreach ($projects as $item)
                                        <option value="{{ $item->id }}"
                                            {{ isset($journalF) ? ($journalF->project_id == $item->id ? 'selected' : '') : '' }}>
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
                    <div class="col-md-1 changeColStyle d-none">
                        <div class="row aling-items-center">
                            <div class="col-4">
                                <label for="">Party Code</label>
                            </div>
                            <div class="col-8">
                                <input type="text" name="pi_code" id="pi_code"
                                    class="form-control inputFieldHeight" required
                                    placeholder="Party Code"
                                    value="{{ $purchase->party->pi_code }}">
                                @error('party_info')
                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div style="width:20%;">
                        <div class="row align-items-center">
                            <div class="col-10">
                                <label for="">Payee</label>

                                <select name="party_info" id="party_info"
                                    class="common-select2 party-info customer"
                                    style="width: 100% !important" data-target="" required>
                                    <option value="">Select...</option>
                                    @foreach ($pInfos as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $purchase->party_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->pi_name }}</option>
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


                    <div style="width:13%;margin-left:5px;" class="d-none">
                        <label for="">Payment Mode</label>

                        <select name="pay_mode" id="pay_mode"
                            class="form-control inputFieldHeight" required>
                            <option value="">Select...</option>

                            @foreach ($modes as $item)
                                <option value="{{ $item->title }}"
                                    {{ $purchase->pay_mode == $item->title ? 'selected' : '' }}>
                                    {{ $item->title }} </option>
                            @endforeach

                        </select>
                        <small id="pay_available_balance" class="text-danger"></small>

                    </div>

                    <div style="width:12%;margin-left:5px;">

                        <label for="">Date</label>


                        <input type="text"
                            value="{{ date('d/m/Y', strtotime($purchase->date)) }}"
                            class="form-control inputFieldHeight datepicker"
                            name="date" placeholder="dd-mm-yyyy">
                        @error('date')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div style="width:12%;margin-left:5px;">

                        <label for="">Invoice No</label>

                        <input type="text" name="invoice_no" id="invoice_no"
                            class="form-control inputFieldHeight"
                            value="{{ $purchase->invoice_no }}">
                        @error('pay_mode')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="col-md-2 changeColStyle d-none">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <label for="">Bill</label>

                            </div>
                            <div class="col-9">
                                <input type="text" name="" id=""
                                    class="form-control inputFieldHeight"
                                    value="{{ $purchase->purchase_no }}" disabled>
                                @error('pay_mode')
                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div style="width:12%;margin-left:5px;">

                        <label for="">Invoice Type</label>


                        <select name="invoice_type" id="invoice_type"
                            class="form-control inputFieldHeight" required>
                            <option value="Tax Invoice"
                                {{ $purchase->invoice_type == 'Tax Invoice' ? 'selected' : '' }}>
                                With Tax</option>
                            <option value="Proforma Invoice"
                                {{ $purchase->invoice_type == 'Proforma Invoice' ? 'selected' : '' }}>
                                Without Tax</option>

                        </select>
                        @error('invoice_type')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                        @enderror

                    </div>
                    @if ($purchase->pay_mode=="Cheque")
                        <div class="col-md-12 cheque-content">
                    @else
                        <div class="col-md-12 cheque-content" style="display: none;">
                    @endif
                        <div class="row">
                            <div class="col-md-5 changeColStyle">
                                <div class="row align-items-center">
                                    <div class="col-3">
                                        <label for="">Issuing Bank</label>
                                    </div>
                                    <div class="col-9 col-left-padding">
                                        <input type="text" autocomplete="off" name="issuing_bank"
                                            id="issuing_bank" class="form-control inputFieldHeight"
                                            placeholder="Issuing Bank" value="{{$purchase->issuing_bank}}">
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
                                            placeholder="Branch" value="{{$purchase->bank_branch}}">
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
                                        <input type="text" autocomplete="off"
                                            class="form-control inputFieldHeight" name="cheque_no"
                                            placeholder="Cheque Number" id="cheque_no" value="{{$purchase->cheque_no}}">
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
                                        <input type="text" autocomplete="off"
                                            class="form-control inputFieldHeight datepicker deposit_date"
                                            name="deposit_date" placeholder="dd/mm/yyyy" value="{{date('d/m/Y', strtotime($purchase->deposit_date))}}">
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

        <div class="col-md-12 col-right-padding col-left-padding"
            style="margin-top:25px !important">
            <div class="row mx-1">
                <div class="cardStyleChange" style="width: 100%">
                    <div class="card-body bg-white">
                        <table class="table  table-sm ">
                            <thead>
                                <th style="width: 25%">Account Head
                                    {{-- <a href="#" title="Add New Expense Head" class="expanse_add" style="margin-left: 5px;">
                                        <img src="{{ asset('assets/backend/app-assets/icon/add-icon.png') }}" alt="" class="img-fluid" style="height: 20px;">
                                    </a> --}}
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
                            </thead>
                            <tbody id="TBody">
                                @foreach ($purchase->items as $key => $purchase_item)
                                <tr class="invoice_row">
                                    <td>
                                        <select name="group-a[{{$key}}][head_id]" required class="form-control expense_head account-head common-select2" >
                                            <option value="">Select....</option>
                                            @foreach ($special_heads as $item)
                                                <option value="{{$item->id}}" class="head" data-subsidiary="No" {{$purchase_item->head_id==$item->id?'selected':''}}  data-value="Yes">{{$item->fld_ac_head}}</option>
                                                @foreach ($item->sub_heads as $sub_head)
                                                    <option value="Sub{{$sub_head->id}}" class="sub-head" data-subsidiary="No" {{$purchase_item->sub_head_id==$sub_head->id?'selected':''}}  data-value="Yes"> &nbsp;&nbsp;&nbsp; |---{{$sub_head->name}}</option>
                                                @endforeach
                                            @endforeach
                                            @foreach ($account_heads as $item)
                                                <option value="{{$item->id}}" class="head" data-subsidiary="{{$item->id==1758?'No':'Yes'}}" {{$purchase_item->head_id==$item->id?'selected':''}}  data-value="No">{{$item->fld_ac_head}}</option>
                                                @foreach ($item->sub_heads as $sub_head)
                                                    <option value="Sub{{$sub_head->id}}" class="sub-head" data-subsidiary="{{$sub_head->account_head_id==1758?'No':'Yes'}}" {{$purchase_item->sub_head_id==$sub_head->id?'selected':''}}  data-value="No"> &nbsp;&nbsp;&nbsp; |---{{$sub_head->name}}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="d-flex justy-content-between">
                                            <input type="text" name="group-a[{{$key}}][multi_acc_head]" value="{{$purchase_item->item_description}}" placeholder="Item Description" class="form-control inputFieldHeight">
                                        </div>
                                    </td>
                                    <td>
                                        <div
                                            class="d-flex justy-content-between align-items-center">
                                            <input type="number" step="any" name="group-a[{{$key}}][qty]" value="{{$purchase_item->qty}}" step="any" placeholder="QTY" class=" form-control inputFieldHeight qty"style="width: 100%;">
                                        </div>
                                    </td>
                                    <td class="unit-exist">
                                        <select name="group-a[{{$key}}][unit_id]" class="inputFieldHeight unit form-control empty_field" style="width: 100%;">
                                            <option value="">Select....</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}" {{$purchase_item->unit_id==$unit->id?'selected':''}}>{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="type-exist d-none">
                                        <select name="group-a[{{$key}}][type]" disabled class="inputFieldHeight2 type form-control empty_field" style="width: 100%;HEIGHT: 36PX;text-align:center;">
                                            <option value="">Select....</option>
                                            <option value="Raw Material">Raw Material</option>
                                            <option value="Expense">Expense</option>
                                        </select>
                                    </td>

                                    <td>
                                        <div class="d-flex justy-content-between">
                                            <input type="number" step="any" name="group-a[{{$key}}][amount]" step="any" value="{{$purchase_item->amount}}" required placeholder="Amount" class="form-control inputFieldHeight2 amount"style="width: 100%; height:35px;">
                                        </div>
                                    </td>


                                    <td class="vat-exist">
                                        <select name="group-a[{{$key}}][vat_rate]" required class="inputFieldHeight2 vat_rate form-control " style="width: 100%;    HEIGHT: 36PX;">
                                            <option value="">Select....</option>
                                            @foreach ($vats as $vat)
                                                <option value="{{ $vat->value }}" {{$purchase_item->vat>0?($vat->value==5?'selected':''):($vat->value!=5?'selected':'') }}>
                                                    {{ $vat->name . ' (' . $vat->value . ')' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="vat-exist">
                                        <input type="number" step="any" class="form-control vat_amount inputFieldHeight2" required placeholder="Vat Amount"  value="{{$purchase_item->vat}}" name="group-a[{{$key}}][vat_amount]" readonly>
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="group-a[{{$key}}][sub_gross_amount]" required class="form-control sub_gross_amount inputFieldHeight2"  placeholder="Amount" style="width: 100%;height:36px;" value="{{$purchase_item->total_amount}}" readonly>
                                    </td>
                                    </td>
                                    <td class="NoPrint d-flex add_button">
                                        <button style="padding:5px 5px !important;" type="button" class="bg-success custom-btn {{$purchase_item->cogs_head($purchase->id,$purchase_item->head_id, $purchase_item->sub_head_id)?'':'d-none'}} project_add" title="Project Expense Add" onclick="BtnProjectItem(this)">
                                            <i class="bx bx-plus" style="color: white;margin-top: -5px;"></i>
                                        </button>
                                        <button style="padding: 5px;" type="button" class="bg-danger custom-btn" onclick="BtnDel(this)">
                                            <i class="bx bx-trash" style="color: white;margin-top: -5px;"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                <tr id="TRow" class="invoice_row d-none">
                                    <td class="custom-select" style="margin-top:-12px !important;">
                                        <select name="group-a[{{count($purchase->items)+1}}][head_id]" required class="form-control expense_head account-head" disabled >
                                            <option value="">Select....</option>
                                            @foreach ($special_heads as $item)
                                                <option value="{{$item->id}}" class="head" data-subsidiary="No" data-value="Yes">{{$item->fld_ac_head}}</option>
                                                @foreach ($item->sub_heads as $sub_head)
                                                    <option value="Sub{{$sub_head->id}}" class="sub-head" data-subsidiary="No" data-value="Yes"> &nbsp;&nbsp;&nbsp; |---{{$sub_head->name}}</option>
                                                @endforeach
                                            @endforeach
                                            @foreach ($account_heads as $item)
                                                <option value="{{$item->id}}" class="head" data-subsidiary="{{$item->id==1758?'No':'Yes'}}" data-value="No">{{$item->fld_ac_head}}</option>
                                                @foreach ($item->sub_heads as $sub_head)
                                                    <option value="Sub{{$sub_head->id}}" class="sub-head" data-subsidiary="{{$sub_head->account_head_id==1758?'No':'Yes'}}" data-value="No"> &nbsp;&nbsp;&nbsp; |---{{$sub_head->name}}</option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="d-flex justy-content-between align-items-center" >
                                            <input type="text" name="group-a[{{count($purchase->items)+1}}][multi_acc_head]" disabled  placeholder="Item Description" class="form-control inputFieldHeight">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justy-content-between align-items-center">
                                            <input type="number" step="any" name="group-a[{{count($purchase->items)+1}}][qty]" step="any" placeholder="QTY" disabled class=" form-control inputFieldHeight qty"style="width: 100%;">
                                        </div>
                                    </td>

                                    <td class="unit-exist">
                                        <select name="group-a[{{count($purchase->items)+1}}][unit_id]" disabled class="inputFieldHeight unit form-control empty_field" style="width: 100%;text-align:center;">
                                            <option value="">Select....</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="type-exist d-none">
                                        <select name="group-a[{{count($purchase->items)+1}}][type]" disabled class="inputFieldHeight2 type form-control empty_field" style="width: 100%; height:36px; text-align:center;">
                                            <option value="">Select....</option>
                                            <option value="Raw Material">Raw Material</option>
                                            <option value="Expense">Expense</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="d-flex justy-content-between align-items-center">
                                            <input type="number" step="any" name="group-a[{{count($purchase->items)+1}}][amount]" step="any" required placeholder="Amount" disabled class="form-control inputFieldHeight amount"style="width: 100%;">
                                        </div>
                                    </td>

                                    <td class="vat-exist">
                                        <select name="group-a[{{count($purchase->items)+1}}][vat_rate]" required disabled class="inputFieldHeight vat_rate form-control empty_field" style="width: 100%;text-align:center;">
                                            <option value="">Select.... </option>
                                            @foreach ($vats as $vat)
                                                <option value="{{ $vat->value }}" {{$vat->value == 5?'selected':''}}>
                                                    {{ $vat->name . ' (' . $vat->value . ')' }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </td>

                                    <td class="vat-exist">
                                        <input type="number" step="any" class="form-control vat_amount inputFieldHeight" required placeholder="Vat Amount" disabled  name="group-a[{{count($purchase->items)+1}}][vat_amount]" readonly>
                                    </td>
                                    <td>
                                        <input type="number" step="any" name="group-a[{{count($purchase->items)+1}}][sub_gross_amount]" required disabled class="form-control sub_gross_amount inputFieldHeight" placeholder="Total Amount" style="width: 100%;" readonly>
                                    </td>
                                    </td>
                                    <td class="NoPrint add_button text-center d-flex" style="margin-top: 5px;">
                                        <button style="padding: 5px;" type="button" class="btn btn-sm btn-success d-none project_add" title="Project Expense Add" onclick="BtnProjectItem(this)">
                                            <i class="bx bx-plus" style="color: white;margin-top: -5px;"></i>
                                        </button>
                                        <button style="padding: 5px;" type="button" class="btn btn-sm btn-danger" onclick="BtnDelItem(this)">
                                            <i class="bx bx-trash" style="color: white;margin-top: -5px;"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="colspan-update"></td>
                                    <td class="text-right pr-1" style="color: black" colspan="2">AMOUNT</td>
                                    <td><input type="number" step="any" readonly
                                            id="taxable_amount"
                                            class="form-control inputFieldHeight2 @error('taxable_amount') error @enderror inputFieldHeight taxable_amount"
                                            name="taxable_amount" value="{{$purchase->amount}}"
                                            placeholder="Amount" readonly required>
                                        @error('taxable_amount')
                                            <span class="error">{{ $message }}</span>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="colspan-update"></td>
                                    <td class="text-right pr-1" style="color: black" colspan="2">VAT</td>
                                    <td><input type="number" step="any" readonly
                                            id="total_vat"
                                            class="inputFieldHeight2 form-control @error('total_vat') error @enderror inputFieldHeight total_vat"
                                            name="total_vat" value="{{$purchase->vat}}"
                                            placeholder="@if (!empty($currency->vat_name)) {{ $currency->vat_name }} @endif SUBTOTAL"
                                            readonly required>
                                        @error('total_vat')
                                            <span class="error">{{ $message }}</span>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="colspan-update"></td>
                                    <td class="text-right pr-1" style="color: black" colspan="2">TOTAL AMOUNT</td>
                                    <td><input type="number" step="any" readonly
                                            id="total_amount"
                                            class="inputFieldHeight2 form-control @error('total_amount') error @enderror inputFieldHeight total_amount"
                                            name="total_amount" value="{{$purchase->total_amount}}"
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
                    <div class="col-sm-2 form-group">
                        <label for="">Voucher Scan/File</label>
                        <input type="file" class="form-control inputFieldHeight"
                            name="voucher_scan[]" accept="image/*,application/pdf" multiple id="fileInput">
                    </div>
                    <div class="col-sm-8 form-group">
                        <label for="">Narration</label>
                        <input type="text" class="form-control inputFieldHeight"
                            name="narration" id="narration" placeholder="Narration"
                            value="{{ $purchase->narration }}">
                    </div>
                    <div class="col-sm-2 text-right d-flex justify-content-end mt-2 mb-1">
                        <button type="submit" class="btn btn-primary formButton" id="submitButton">
                            <div class="d-flex">
                                <div class="formSaveIcon">
                                    <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}"
                                        alt="" srcset="" width="25">
                                </div>
                                <div><span>Save</span></div>
                            </div>
                        </button>
                    </div>
                    <div class="col-md-6" id="fileList">
                    <div class="col-md-6"></div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

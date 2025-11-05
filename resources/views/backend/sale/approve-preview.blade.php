
<section class="print-hideen border-bottom">
    <div class="d-flex flex-row-reverse">
        <div class="py-1 pr-1"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
            <div class="py-1 pr-1"><a href="#" onclick="window.print();" class="btn btn-icon btn-secondary"><i class="bx bx-printer"></i></a></div>
            <div class="py-1 pr-1 show-edit-form" onclick="toggleEditForm();"><button class="btn btn-icon btn-info"><i class='bx bx-edit-alt'></i></button></div>
            <div class="py-1 pr-1"><a href="{{route('sale.delete',$sale)}}" class="btn btn-sm btn-icon btn-danger">Delete</a></div>

            <div class="py-1 pr-1 w-100 pl-2">
                <h4> {{ $sale->invoice_type=="Tax Invoice"? 'Tax Invoice':'Proforma Invoice'}}</h4>
            </div>
        </div>
</section>

@include('layouts.backend.partial.modal-header-info')

<section id="widgets-Statistics">
    <div class="row sale-view">
        <div class="col-md-12">
            <div class=" print-content text-center">
                <h2> {{ $sale->invoice_type=="Tax Invoice"? 'Tax Invoice':'Proforma Invoice'}}</h2>
            </div>
            <div class="">
                <div class="mx-2 mb-2">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-12">
                                    <strong> Party Name: </strong> {{ $sale->party->pi_name}}
                                </div>
                                <div class="col-3">
                                    <strong> Address: </strong> {{ $sale->party->address}}
                                </div>
                                <div class="col-3">
                                    <strong> Attention: </strong> {{ $sale->attention}}
                                </div>
                                <div class="col-3">
                                    <strong> Contact No:</strong> {{ $sale->party->con_no}}
                                </div>
                                <div class="col-3">
                                    <strong> Date:</strong> {{ date('d/m/Y',strtotime($sale->date))}}
                                </div>
                                <div class="col-3">
                                    <strong> Payment Mode:</strong> {{ $sale->pay_mode}}
                                </div>
                                <div class="col-3">
                                    <strong>Invoice No:</strong>{{ $sale->invoice_no}}
                                </div>
                                <div class="col-3">
                                    <strong> Amount:</strong> @if(!empty($currency->symbole)){{$currency->symbole}}@endif {{ $sale->total_amount}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="border-botton">
                <div class="mx-2">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered border-botton">
                            <thead class="thead">
                                <tr >
                                    {{-- <th>Date</th> --}}
                                    <th>Item Description</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                    <th> Vat </th>
                                    <th> Total <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                </tr>
                            </thead>

                            <tbody class="user-table-body">
                                  @foreach ($sale->items as $item)
                                 <tr>
                                    <td>{{$item->item_description}}</td>
                                    <td>{{$item->qty}}</td>
                                    <td>{{$item->unit->name}}</td>
                                    <td>{{$item->rate}}</td>
                                    <td>{{$item->amount}}</td>
                                 </tr>

                                  @endforeach
                                  <tr>
                                    <td colspan="6">Total Amount</td>
                                    <td>{{$sale->amount}}</td>
                                  </tr>
                                  @if ($sale->invoice_type=="Tax Invoice")
                                  <tr>
                                    <td colspan="3"></td>
                                    <td>Vat <small>({{$standard_vat_rate}}%)</small></td>
                                    <td>{{$sale->vat}}</td>
                                  </tr>
                                  <tr>
                                    <td colspan="3"></td>
                                    <td>Total Amount</small></td>
                                    <td>{{$sale->total_amount}}</td>
                                  </tr>
                                  @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if (isset($new))
        @else
        <div class="col-md-12 text-center">
            <a href="{{route('sale-approve',$sale)}}" class="btn btn-info btn-sm" onclick="return confirm('about to authorize purchase. Please, Confirm?')"> Approve </a>
        </div>
        @endif
    </div>

    <div class="edit-form d-none">
        <form action="{{ route('saleIssuepost.edit',$sale->id) }}" method="post" id="formSubmit"
            enctype="multipart/form-data">
            @csrf
            @method('put')
            <div class="cardStyleChange bg-white">
                <div class="card-body ">
                    <div class="row mx-1 pt-1">
                        <div class="col-md-2 changeColStyle  col-right-padding">
                            <div class="row d-flex align-items-center">
                                <div class="col-3">
                                    <label for="project">Branch</label>
                                </div>
                                <div class="col-9">
                                    <select name="project" class="common-select2 w-100" id="project"
                                        required>
                                        @foreach ($projects as $item)
                                            <option value="{{ $item->id }}"
                                                {{$sale->project_id ==  $item->id ? 'selected' : '' }}>
                                                {{ $item->proj_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('project')
                                        <div class="btn btn-sm btn-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 changeColStyle">
                            <div class="row aling-items-center">
                                <div class="col-4">
                                    <label for=""> Party Code </label>
                                </div>
                                <div class="col-8">
                                    <input type="text" name="pi_code" id="pi_code"
                                        class="form-control inputFieldHeight" required
                                        placeholder="Party Code" value="{{ $sale->party->pi_code}}">
                                    @error('pi_code')
                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 changeColStyle search-item-pi">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <label for="">Party Name</label>

                                </div>
                                <div class="col-8 customer-select">
                                    <select name="party_info" id="party_info"
                                        class="common-select2 party-info customer"
                                        style="width: 100% !important" data-target="" required>
                                        <option value="">Select...</option>
                                        @foreach ($pInfos as $item)
                                            <option value="{{ $item->id }}"
                                                {{ $sale->party_id == $item->id ? 'selected' : '' }}>
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
                        <div class="col-md-2 changeColStyle">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <label for="">
                                        @if (!empty($currency->licence_name))
                                            {{ $currency->licence_name }}
                                        @endif
                                    </label>

                                </div>
                                <div class="col-10">
                                    <input type="text" class="form-control inputFieldHeight"
                                        name="trn_no" id="trn_no" class="form-control" readonly value="{{ $sale->party->trn_no }}">
                                    @error('trn_no')
                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 changeColStyle">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <label for="">
                                        Contact
                                    </label>

                                </div>
                                <div class="col-9">
                                    <input type="text" class="form-control inputFieldHeight"
                                        value="{{ $sale->party->con_person }}"
                                        name="party_contact" id="party_contact" class="form-control" readonly>
                                    @error('party_contact')
                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 changeColStyle">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <label for="">
                                        Address
                                    </label>
                                </div>
                                <div class="col-9">
                                    <input type="text" class="form-control inputFieldHeight"
                                        value="{{ $sale->party->address }}"
                                        name="party_address" id="party_address" class="form-control" readonly>
                                    @error('party_address')
                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <div class="col-md-2 changeColStyle">
                            <div class="row align-items-center">
                                <div class="col-4">
                                    <label for="">Payment Mode</label>

                                </div>
                                <div class="col-8">
                                    <select name="pay_mode" id="pay_mode"
                                        class="form-control inputFieldHeight" required>
                                        <option value="">Select...</option>

                                        @foreach ($modes as $item)
                                            <option value="{{ $item->title }}"
                                                {{$sale->pay_mode == $item->title ? 'selected' : ''}}>
                                                {{ $item->title }} </option>
                                        @endforeach

                                    </select>
                                    @error('pay_mode')
                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 changeColStyle" id="printarea">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <label for="">Date</label>

                                </div>
                                <div class="col-10">
                                    <input type="text"
                                        value="{{ date('d/m/Y',strtotime($sale->date))}}"
                                        class="form-control inputFieldHeight datepicker" name="date"
                                        placeholder="dd-mm-yyyy">
                                    @error('date')
                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 changeColStyle">
                            {{-- <div class="row align-items-center">
                                <div class="col-3">
                                    <label for="">Invoice No</label>

                                </div>
                                <div class="col-9">
                                    <input type="text" name="invoice_no" id="invoice_no" class="form-control inputFieldHeight" value="" required>
                                    @error('pay_mode')
                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div> --}}
                        </div>

                        <div class="col-md-2 changeColStyle">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <label for="">Invoice</label>

                                </div>
                                <div class="col-9">
                                    <input type="text" name="" id="" class="form-control inputFieldHeight" value="{{$sale_no}}" disabled>
                                    @error('pay_mode')
                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 changeColStyle">
                            <div class="row align-items-center d-flex justify-content-end">
                                <div class="col-4">
                                    <label for="">Invoice Type</label>

                                </div>
                                <div class="col-8">
                                    <select name="invoice_type" id="invoice_type" class="form-control inputFieldHeight" required>
                                        <option value="Tax Invoice">Tax Invoice</option>
                                        <option value="Proforma Invoice">Proforma Invoice</option>

                                    </select>
                                    @error('invoice_type')
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
                                <thead>
                                    <tr>
                                        <th style="width: 36%">Description</th>
                                        <th style="width: 15%">QTY</th>

                                        <th style="width: 15%">Unit</th>
                                        <th style="width: 15%">Rate</th>
                                        <th style="width: 15%">Amount</th>
                                        <th class="NoPrint"> <button type="button"
                                                class="btn btn-sm btn-success addBtn"style="border: 1px solid green;
                                            color: #fff; border-radius: 10px;padding: 5px;"
                                                onclick="BtnAdd()">ADD</button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="TBody">
                                    @php
                                        $index=0;
                                    @endphp
                                    @foreach ($sale->items as $item)
                                    <tr id="TRow" class="invoice_row">
                                        <td>
                                            <div
                                                class="d-flex justy-content-between align-items-center">
                                                <input type="text" value="{{ $item->item_description}}"
                                                    name="group-a[{{ $index }}][multi_acc_head]" step="any"
                                                    required placeholder="Item Description"
                                                    class="form-control inputFieldHeight2"style="width: 100%;height:36px;">
                                            </div>
                                        </td>

                                        <td>
                                            <div
                                                class="d-flex justy-content-between align-items-center">
                                                <input type="text" name="group-a[{{ $index }}][qty]" value="{{ $item->qty }}"
                                                    step="any" required
                                                    class="form-control inputFieldHeight2 qty"style="width: 100%;height:36px;">
                                            </div>

                                        </td>

                                        <td>
                                            <select name="group-a[{{ $index }}][unit]"
                                                required class="inputFieldHeight2 unit form-control "
                                                style="width: 100%;    HEIGHT: 36PX;">
                                                <option value=""> ----- Choice Option ----
                                                </option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}" {{ $item->unit_id == $unit->id ? 'selected' : '' }}>
                                                        {{ $unit->name }}</option>
                                                @endforeach
                                            </select>

                                        </td>
                                        <td><input type="number" step="any" value="{{ $item->rate }}"
                                                class="form-control rate inputFieldHeight2" required
                                                name="group-a[{{ $index }}][rate]">
                                        <td>
                                            <input type="number" step="any" value={{ $item->amount }}
                                                name="group-a[{{ $index++ }}][amount]" required
                                                class="form-control amount inputFieldHeight2"
                                                style="width: 100%;height:36px;" readonly>
                                        </td>
                                        </td>
                                        <td class="NoPrint"><button style="padding: 2px; margin: 4px;"
                                                type="button"
                                                class="btn btn-sm btn-danger"onclick="BtnDel(this)">DELETE</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tbody>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="text-center" style="color: black">TOTAL</td>
                                        <td><input type="number" step="any" readonly id="taxable_amount" value="{{ $sale->amount }}"
                                                class="form-control inputFieldHeight2 @error('taxable_amount') error @enderror inputFieldHeight taxable_amount"
                                                name="taxable_amount"
                                                placeholder="Amount" readonly required>
                                            @error('taxable_amount')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="text-center" style="color: black">VAT</td>
                                        <td><input type="number" step="any" readonly id="total_vat"
                                                class="inputFieldHeight2 form-control @error('total_vat') error @enderror inputFieldHeight total_vat"
                                                name="total_vat" value="{{ $sale->vat }}"
                                                placeholder="@if (!empty($currency->vat_name)) {{ $currency->vat_name }} @endif SUBTOTAL"
                                                readonly required>
                                            @error('total_vat')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="text-center" style="color: black">TOTAL AMOUNT</td>
                                        <td><input type="number" step="any" readonly id="total_amount"
                                                class="inputFieldHeight2 form-control @error('total_amount') error @enderror inputFieldHeight total_amount"
                                                name="total_amount" value="{{ $sale->total_amount }}"
                                                placeholder="TOTAL "
                                                readonly required>
                                            @error('total_amount')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                            <input type="hidden" name="standard_vat_rate" value="{{$standard_vat_rate}}"  id="standard_vat_rate">
                        </div>
                    </div>

                </div>
            </div>
            <div class="cardStyleChange">
                <div class="card-body bg-white">
                    <div class="row px-1">
                        <div class="col-sm-6 form-group">
                            <label for="">Narration</label>
                            <input type="text" class="form-control inputFieldHeight"
                                name="narration" id="narration" placeholder="Narration"
                                value="{{ $sale->narration }}" required>
                        </div>

                        {{-- <div class="col-sm-3 form-group">
                            <label for="">Voucher Scan/File</label>
                            <input type="file" class="form-control inputFieldHeight"
                                name="voucher_scan" accept="image/*">
                        </div> --}}

                        {{-- <div class="col-sm-3 form-group">
                            <label for="">Voucher Scan/File 2</label>
                            <input type="file" class="form-control inputFieldHeight"
                                name="voucher_scan2" accept="image/*">
                        </div> --}}
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
                            <button class="btn btn-warning  d-none" onClick="refreshPage()"
                                id="newButton">New</button>

                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="divFooter mb-1 ml-1">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png')}}" alt="" width="150"></span>
    </div>
</section>


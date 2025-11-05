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
</style>
<div class="modal-header" style="padding:5px 10px;background:#364a60;">
    <h5 class="modal-title" id="exampleModalLabel"
        style="font-family:Cambria;font-size: 2rem;color:white;margin-left: 10px;">Receipt Edit</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>


<form id="editReceiptForm" action="{{ route('temp-receipt-voucher-update') }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="receipt_voucher_id" value="{{ $receipt->id }}">
    <div class="cardStyleChange bg-white">
        <div class="card-body pb-1">
            <div class="row mx-1 mt-1 d-flex justify-content-center">
                <div class="col-md-2 changeColStyle" id="printarea">

                    <label for="">Date</label>

                    <input type="text" value="{{ date('d/m/Y', strtotime($receipt->date)) }}"
                        class="form-control inputFieldHeight datepicker" name="date" placeholder="dd/mm/yyyy">
                    @error('date')
                        <div class="btn btn-sm btn-danger">{{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="col-sm-2 changeColStyle">
                    <label for="">Type</label>
                    <select name="voucher_type" id="voucher_type" class="form-control inputFieldHeight"
                        style="width: 100% !important" required>
                        @if ($receipt->type == 'due')
                            <option value="due" {{ $receipt->type == 'due' ? 'selected' : '' }}>Due Payment</option>
                        @else
                            <option value="advance" {{ $receipt->type == 'advance' ? 'selected' : '' }}>Advance Payment
                            </option>
                        @endif
                    </select>
                </div>

                <div class="col-md-2 changeColStyle search-item-pi">
                    <label for="">Party Name</label>
                    <select name="party_info" id="party_info" class="form-control inputFieldHeight"
                        style="width: 100% !important" data-target="" required>
                        @foreach ($parties as $item)
                            <option value="{{ $item->id }}"
                                {{ $receipt->party->id == $item->id ? 'selected' : '' }}>
                                {{ $item->pi_name }}</option>
                        @endforeach
                    </select>
                    <small class="text-danger">Available Balance {{ $receipt->party->balance }}</small>
                    @error('party_info')
                        <div class="btn btn-sm btn-danger">{{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="col-md-2 changeColStyle  mb-0 pb-0 project_div {{$receipt->job_project? '':'d-none'}} ">
                    <label for="">Project</label>
                    <select name="project" id="project" class="common-select2 project" style="width: 100% !important">
                        <option value="{{$receipt->job_project?$receipt->job_project->id:''}}">{{$receipt->job_project?$receipt->job_project->project_name:''}}</option>

                    </select>
                    <small id="project_available_balance" class="text-danger project_available_balance"></small>
                </div>
                <div class="col-md-2 changeColStyle">
                    <label for="">Party Code</label>

                    <input type="text" name="pi_code" id="pi_code" class="form-control inputFieldHeight"
                        value="{{ $receipt->party->pi_code }}" required placeholder="Party Code">
                    @error('party_info')
                        <div class="btn btn-sm btn-danger">{{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-2 changeColStyle">

                    <label for="">Payment Mode</label>

                    <select name="pay_mode" id="pay_mode" class="form-control inputFieldHeight" required>
                        @foreach ($modes as $item)
                            <option value="{{ $item->title }}"
                                {{ $receipt->pay_mode == $item->title ? 'selected' : '' }}> {{ $item->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('pay_mode')
                        <div class="btn btn-sm btn-danger">{{ $message }}
                        </div>
                    @enderror

                </div>
                <div class="col-md-2" id="bank_name">
                    <label for="">Bank Name</label>
                    <select name="bank_id" id="bank_id" class="form-control inputFieldHeight bank_id"
                        {{ $receipt->pay_mode == 'Bank' ? '' : 'disabled' }}>
                        <option value="">Select...</option>
                        @foreach ($bank_name as $item)
                            <option value="{{ $item->id }}"
                                {{ $receipt->bank_id == $item->id ? 'selected' : '' }}> {{ $item->name }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 cheque-content" style="display: {{ $receipt->pay_mode == 'Cheque' ? '' : 'none' }}">
                    <div class="row">
                        <div class="col-md-5 changeColStyle">

                            <label for="">Issuing Bank</label>

                            <input type="text" autocomplete="off" name="issuing_bank" id="issuing_bank"
                                class="form-control inputFieldHeight" placeholder="Issuing Bank"
                                value="{{ $receipt->issuing_bank }}" {{ $receipt->pay_mode == 'Cheque' ? 'required' : '' }}>
                            @error('issuing_bank')
                                <div class="btn btn-sm btn-danger">{{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-3 changeColStyle">

                            <label for="">Branch</label>

                            <input type="text" autocomplete="off" name="bank_branch" id="bank_branch"
                                class="form-control inputFieldHeight" placeholder="Branch"
                                value="{{ $receipt->branch }}" {{ $receipt->pay_mode == 'Cheque' ? 'required' : '' }}>
                            @error('bank_branch')
                                <div class="btn btn-sm btn-danger">{{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-2 changeColStyle">

                            <label for="">Cheque No</label>

                            <input type="text" autocomplete="off" class="form-control inputFieldHeight"
                                name="cheque_no" placeholder="Cheque Number" id="cheque_no"
                                value="{{ $receipt->cheque_no }}" {{ $receipt->pay_mode == 'Cheque' ? 'required' : '' }}>
                            @error('cheque_no')
                                <div class="btn btn-sm btn-danger">{{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="col-md-2 changeColStyle">

                            <label for="">Deposit Date</label>

                            <input type="text" autocomplete="off"
                                class="form-control inputFieldHeight datepicker deposit_date" name="deposit_date"
                                placeholder="dd/mm/yyyy"
                                value="{{ $receipt->pay_mode == 'Cheque' ? date('d/m/Y', strtotime($receipt->deposit_date)) : '' }}"
                                {{ $receipt->pay_mode == 'Cheque' ? 'required' : '' }}>
                            @error('deposit_date')
                                <div class="btn btn-sm btn-danger">{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                @php
                    $c = 0;
                @endphp
                <table class="table table-bordered table-sm {{$receipt->type == 'due'?'':'d-none'}}">
                    <thead>
                        <tr>
                            <th style="width: 10%">
                                <input type="checkbox" id="vehicle1" class="btn-select-all">
                                <label for="vehicle1" style="color: white;">S All</label>
                            </th>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th style="width: 15%">Total Amount</th>
                            <th style="width:15%" class="d-none"> Discount</th>
                            <th style="width: 15%">Due Amount <small>( @if (!empty($currency->symbole))
                                        {{ $currency->symbole }}
                                    @endif)</small></th>
                        </tr>
                    </thead>
                    @php
                        $ind = 0;
                        $pre_due = 0;
                    @endphp
                    @foreach ($invoices as $inv)
                        <tr id="TRow">
                            @php
                                $pre_due = $inv->due_amount + $inv->tempReceipt->Total_amount;
                            @endphp
                            <td>
                                <input type="checkbox" class="checkbox-record" checked
                                    name="records[{{ $ind }}]" value="{{ $inv->id }}">
                            </td>
                            <td>
                                {{ $inv->invoice_no }}
                            </td>
                            <td>
                                {{ date('d/m/Y', strtotime($inv->date)) }}
                            </td>
                            <td>
                                {{ $inv->total_budget }}
                            </td>

                            <td class="d-none">
                                <input type="number" step="any" value=""
                                    name="inv_discount[{{ $ind++ }}]"
                                    class="inv_discount form-control inputFieldHeight">
                            </td>
                            <td class="inv_due" data-due="{{ $inv->due_amount }}">
                                {{ $inv->due_amount + $inv->tempReceipt->Total_amount }}
                            </td>
                        </tr>
                    @endforeach
                    @foreach ($invoices2 as $inv)
                        <tr id="TRow">
                            <td>
                                <input type="checkbox" class="checkbox-record" name="records[{{ $ind }}]"
                                    value="{{ $inv->id }}">
                            </td>
                            <td>
                                {{ $inv->invoice_no }}
                            </td>
                            <td>
                                {{ date('d/m/Y', strtotime($inv->date)) }}
                            </td>
                            <td>
                                {{ $inv->total_budget }}
                            </td>

                            <td class="d-none">
                                <input type="number" step="any" value=""
                                    name="inv_discount[{{ $ind++ }}]"
                                    class="inv_discount form-control inputFieldHeight">
                            </td>
                            <td class="inv_due" data-due="{{ $inv->due_amount }}">
                                {{ $inv->due_amount }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3"></td>
                        <td class="text-center" style="color: black">Total Due</td>
                        <td class="total_due">{{ $invoices2->sum('due_amount') + $pre_due }}</td>
                    </tr>
                </table>



                <div class="col-md-2 changeColStyle" id="printarea">
                    <label for="">Voucher File</label>
                    <input class="form-control inputFieldHeight  @error('voucher_file') is-invalid @enderror"
                        type="file" name="voucher_file" style="height: 32px !important"
                        accept="application/pdf,image/png,image/jpeg,application/msword">
                </div>
                <div class="col-md-6 changeColStyle" id="printarea">
                    <label for="">Narration</label>
                    <input type="text" class="form-control inputFieldHeight" name="narration"
                        placeholder="Narration" value="{{ $receipt->narration }}" required>
                </div>

                <div class="col-md-2 changeColStyle {{$receipt->type == 'due'?'':'d-none'}}" id="printarea">
                    <label for="">Due Amount</label>
                    <input type="number" step="any" class="form-control inputFieldHeight" name="due_amount"
                        id="due_amount" placeholder="Due Amount"
                        value="{{ $invoices2->sum('due_amount') + $pre_due }}" readonly>
                </div>

                <div class="col-md-2 changeColStyle" id="printarea">
                    <label for="">Pay Amount</label>
                    <input type="number" step="any" class="form-control inputFieldHeight" name="pay_amount"
                        id="pay_amount" placeholder="Pay Amount"
                        value="{{ $receipt->total_amount }}"
                        @if($receipt->type == 'due')
                        max="{{ $invoices2->sum('due_amount') + $pre_due }}"
                        @endif
                         required>

                </div>



                <div class="col-md-12">
                    <div class="row">
                        <div class="cardStyleChange" style="width: 100%">
                            <div class="card-body bg-white" id="table-part">


                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 d-flex justify-content-center mb-1"
                    style="padding-right: 5px;margin-top:18px;">
                    <button type="submit" class="btn btn-primary formButton " id="submitButton">
                        <div class="d-flex">
                            <div class="formSaveIcon">
                                <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}" alt=""
                                    srcset="" width="25">
                            </div>
                            <div><span>Save</span></div>
                        </div>
                    </button>
                    <a href="{{ route('receipt-voucher3') }}" class="btn btn-warning  d-none" id="newButton">New</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {


        $(document).on("keypress", "#amount", function(e) {
            var key = e.which;
            var value = $(this).val();
            if (e.which == 13) {
                $("#tax_rate").focus();
                e.preventDefault();
                return false;
            }
        });

    });
</script>

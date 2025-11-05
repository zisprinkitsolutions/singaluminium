<div class="modal-content">
    <div class="modal-header" style="padding: 5px 18px;background:#364a60;">
        <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">Payment Voucher</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form action="{{ route('temp-payment-voucher-store') }}" method="POST" id="formSubmit" enctype="multipart/form-data">
        @csrf
        <div class="cardStyleChange bg-white m-1">
            <div class="card-body pb-1">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="">Payee</label>
                        <select name="party_info" id="party_info"
                            class="common-select2 party-info" style="width: 100% !important"
                            data-target="" required>
                            <option value="">Select...</option>
                            @foreach ($parties as $item)
                                <option value="{{ $item->id }}"
                                    {{ isset($journalF) ? ($journalF->party_info_id == $item->id ? 'selected' : '') : '' }}>
                                    {{ $item->pi_name }}</option>
                            @endforeach
                        </select>
                        @error('party_info')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-2 form-group">
                        <label for="">Party Code</label>
                        <input type="text" name="pi_code" id="pi_code"
                            class="form-control inputFieldHeight" required
                            placeholder="Party Code">
                        @error('party_info')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-2 form-group d-none">
                        <label for="">TRN</label>
                        <input type="text" class="form-control inputFieldHeight"
                            value="{{ isset($journalF) ? $journalF->partyInfo->trn_no : '' }}"
                            name="trn_no" id="trn_no" class="form-control" readonly>
                        @error('trn_no')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-2 form-group">
                        <label for="">Payment Mode</label>
                        <select name="pay_mode" id="pay_mode"  class="form-control inputFieldHeight" required>
                            <option value="">Select...</option>
                            @foreach ($modes as $item)
                                <option value="{{ $item->title }}">{{ $item->title }} </option>
                            @endforeach
                        </select>
                        @error('pay_mode')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-2 bank_name" id="bank_name" style="display: none;">
                        <label for="">Bank Name</label>
                        <select name="bank_id" id="bank_id" class="form-control inputFieldHeight">
                            <option value="">Select...</option>
                            @foreach ($bank_name as $item)
                                <option value="{{ $item->id }}" > {{ $item->name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Paid By</label>
                        <select name="paid_by" id="paid_by" class="form-control inputFieldHeight common-select2 paid_by" disabled>
                            <option value="">Select...</option>
                            @foreach ($employee as $item)
                                <option value="{{ $item->id }}" >{{ $item->full_name }} </option>
                            @endforeach
                        </select>
                        @error('paid_by')
                            <div class="btn btn-sm btn-danger">{{ $message }} </div>
                        @enderror
                    </div>

                    <div class="col-md-12 cheque-content" style="display: none">
                        <div class="row">
                            <div class="col-md-3 form-group">

                                <label for="">Issuing Bank</label>

                                <input type="text" autocomplete="off" name="issuing_bank"
                                    id="issuing_bank" class="form-control inputFieldHeight"
                                    placeholder="Issuing Bank">
                                @error('issuing_bank')
                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-3 form-group">

                                <label for="">Branch</label>

                                <input type="text" autocomplete="off" name="bank_branch"
                                    id="bank_branch" class="form-control inputFieldHeight"
                                    placeholder="Branch">
                                @error('bank_branch')
                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-3 form-group">

                                <label for="">Cheque No</label>

                                <input type="text" value="" autocomplete="off"
                                    class="form-control inputFieldHeight" name="cheque_no"
                                    placeholder="Cheque Number" id="cheque_no">
                                @error('cheque_no')
                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-3 form-group">

                                <label for="">Deposit Date</label>

                                <input type="text" value="" autocomplete="off"
                                    class="form-control inputFieldHeight datepicker deposit_date"
                                    name="deposit_date" placeholder="dd-mm-yyyy">
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
        <div class="card-body bg-white m-1" id="table-part"></div>
        <div class="cardStyleChange m-1">
            <div class="card-body bg-white payment-exist">
                <div class="row">
                    <div class="col-sm-3 form-group">
                        <label for="">Date</label>
                        <input type="text" value="{{ date('d/m/Y') }}" class="form-control inputFieldHeight datepicker"
                            name="date" id="narration" placeholder="dd-mm-yyyy"
                                required>
                                @error('date')
                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                    </div>
                                @enderror
                    </div>
                    <div class="col-sm-4 form-group">
                        <label for="">Narration</label>
                        <input type="text" class="form-control inputFieldHeight"
                            name="narration" id="narration" placeholder="Narration"
                            value="{{ isset($journalF) ? $journalF->narration : '' }}" required>
                    </div>

                    <div class="col-sm-2 form-group">
                        <label for="">Pay Amount</label>
                        <input type="number" step="any" class="form-control inputFieldHeight"
                            name="pay_amount" id="pay_amount" placeholder="Pay Amount" min="0.01"
                            value="" required>
                    </div>

                    <div class="col-sm-3 form-group">
                        <label for="">Voucher File</label>
                        <input class="form-control  @error('voucher_file') is-invalid @enderror" type="file" name="voucher_file"
                            style="padding: 0px !important;border:none" accept="application/pdf,image/png,image/jpeg,application/msword" >
                    </div>
                </div>
                <div class="d-flex justify-content-center align-items-center mb-1" >
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
                    <a href="{{route("payment-voucher2")}}" class="btn btn-warning  d-none" id="newButton">New</a>
                </div>
            </div>
            <div class="card-body bg-white payment-not-exist text-center text-danger">
                Doesn't Have Any Dues!
            </div>
        </div>
    </form>
</div>

<div class="modal-content">
    <div class="modal-header" style="padding: 5px 18px;background:#364a60;">
        <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">Payment Voucher</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form action="{{ route('temp-payment-voucher-update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" value="{{$temp_payment_voucher->id}}" name="temp_payment_voucher_id">
        <div class="cardStyleChange bg-white">
            <div class="card-body pb-1">
                <div class="row mx-1 mt-1">
                    <div class="col-md-3 changeColStyle search-item-pi">
                        <label for="">Payee</label>
                        <select name="party_info" id="party_info" class="common-select2 party-info form-control inputFieldHeight selected2" style="width: 100% !important" disabled data-target="" required>
                            <option value="">Select...</option>
                            @foreach ($parties as $item)
                                <option value="{{ $item->id }}" {{ $temp_payment_voucher->party_id == $item->id ? 'selected' : '' }}>{{ $item->pi_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 changeColStyle">
                        <label for="">Payment Mode</label>
                        <select name="pay_mode" id="pay_mode" class="form-control inputFieldHeight" required>
                            @foreach ($modes as $item)
                                <option value="{{ $item->title }}" {{ $temp_payment_voucher->pay_mode == $item->title ? 'selected' : '' }}> {{ $item->title }} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 bank_name" id="bank_name" style="{{$temp_payment_voucher->pay_mode=='Bank'?'':'display: none'}}">
                        <label for="">Bank Name</label>
                        <select name="bank_id" id="bank_id" class="form-control inputFieldHeight">
                            <option value="">Select...</option>
                            @foreach ($bank_name as $item)
                                <option value="{{ $item->id }}" {{$temp_payment_voucher->bank_id==$item->id?'selected':''}}> {{ $item->name }} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 changeColStyle">
                        <label for="">Date</label>
                        <input type="text" value="{{ date('d/m/Y', strtotime($temp_payment_voucher->date)) }}" class="form-control inputFieldHeight datepicker" name="date" placeholder="dd-mm-yyyy">
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="">Paid By</label>
                        <select name="paid_by" id="paid_by" class="form-control inputFieldHeight common-select2 paid_by" {{$temp_payment_voucher->pay_mode == 'Petty Cash'?'':'disabled'}}>
                            <option value="">Select...</option>
                            @foreach ($employee as $item)
                                <option value="{{ $item->id }}" {{$temp_payment_voucher->paid_by==$item->id?'selected':''}}>{{ $item->full_name }} </option>
                            @endforeach
                        </select>
                        @error('paid_by')
                            <div class="btn btn-sm btn-danger">{{ $message }} </div>
                        @enderror
                    </div>
                    <div class="col-md-12 cheque-content" style="display:{{$temp_payment_voucher->pay_mode=='Cheque'?'':'none'}}">
                        <div class="row">
                            <div class="col-md-5 changeColStyle">
                                <label for="">Issuing Bank</label>
                                <input type="text" autocomplete="off" name="issuing_bank" id="issuing_bank" class="form-control inputFieldHeight" placeholder="Issuing Bank" value="{{$temp_payment_voucher->issuing_bank}}" {{$temp_payment_voucher->pay_mode=='Cheque'?'required':''}}>                                                    </div>

                            <div class="col-md-3 changeColStyle">
                                <label for="">Branch</label>
                                <input type="text" autocomplete="off" name="bank_branch" id="bank_branch" class="form-control inputFieldHeight" placeholder="Branch" value="{{$temp_payment_voucher->branch}}" {{$temp_payment_voucher->pay_mode=='Cheque'?'required':''}}>
                            </div>

                            <div class="col-md-2 changeColStyle">
                                <label for="">Cheque No</label>
                                <input type="text"  autocomplete="off" class="form-control inputFieldHeight" name="cheque_no" placeholder="Cheque Number" id="cheque_no" value="{{$temp_payment_voucher->cheque_no}}" {{$temp_payment_voucher->pay_mode=='Cheque'?'required':''}}>
                            </div>

                            <div class="col-md-2 changeColStyle">
                                <label for="">Deposit Date</label>
                                <input type="text"  autocomplete="off" class="form-control inputFieldHeight datepicker deposit_date" name="deposit_date" placeholder="dd-mm-yyyy" value="{{$temp_payment_voucher->deposit_date?date('d/m/Y',strtotime($temp_payment_voucher->deposit_date)):''}}" {{$temp_payment_voucher->pay_mode=='Cheque'?'required':''}}>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="cardStyleChange" style="width: 100%">
                    <div class="card-body bg-white" id="table-part">
                        @php
                            $c=0;
                        @endphp
                        <table class="table table-bordered table-sm" >
                            <thead>
                                <tr >
                                    <th  style="width: 5%">#</th>
                                    <th  style="width: 25%">Purchase</th>
                                    <th  style="width: 20%">Date</th>
                                    <th  style="width: 25%">Total Amount <small>( @if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                    <th  style="width: 25%">Due Amount <small>( @if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                </tr>
                            </thead>
                            @foreach ($new_expenses as $key => $item)
                            @php
                                $exit_invoice_amount = App\TempPaymentVoucherDetail::where('sale_id', $item->id)->where('payment_id', $temp_payment_voucher->id)->first();
                                $due_amount = $item->due_amount;
                                // dd($exit_invoice_amount);
                                if($exit_invoice_amount){
                                    $due_amount += $exit_invoice_amount->total_amount;
                                }
                            @endphp
                            <tr id="TRow" class="text-center">
                                <td>{{$key+1}}</td>
                                <td>
                                    {{$item->purchase_no}}
                                </td>
                                <td>
                                    {{date('d/m/Y', strtotime($item->date))}}
                                </td>
                                <td>
                                    {{$item->total_amount}}
                                </td>
                                <td>
                                    {{$due_amount}}
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3"></td>
                                <td class="text-center" style="color: black">Total Due</td>
                                <td class="text-center">{{$due}}</td>
                            </tr>
                        </table>


                    </div>
                </div>

            </div>
        </div>
        <div class="cardStyleChange ">
            <div class="card-body bg-white payment-exist">
                <div class="row px-1">
                    <div class="col-sm-3 form-group">
                        <label for="">Narration</label>
                        <input type="text" class="form-control inputFieldHeight"
                            name="narration" id="narration" placeholder="Narration"
                            value="{{$temp_payment_voucher->narration}}" required>
                    </div>

                    <div class="col-sm-3 form-group">
                        <label for="">Due Amount</label>
                        <input type="number" step="any" class="form-control inputFieldHeight"
                            name="due_amount" id="due_amount" placeholder="Pay Amount"
                            value="{{$due}}" readonly>
                    </div>
                    <div class="col-sm-3 form-group">
                        <label for="">Pay Amount</label>
                        <input type="number" step="any" class="form-control inputFieldHeight"
                            name="pay_amount" id="pay_amount" placeholder="Pay Amount"
                            value="{{$temp_payment_voucher->total_amount}}" required>
                    </div>

                    <div class="col-sm-3 form-group">
                        <label for="">Voucher File</label>
                        <input
                        class="form-control  @error('voucher_file') is-invalid @enderror" type="file" name="voucher_file"
                            style="padding: 0px !important;border:none" accept="application/pdf,image/png,image/jpeg,application/msword" >
                    </div>
                    {{-- <div class="col-sm-1 text-right d-flex justify-content-end mt-2 mb-1"> --}}
                    <div class="col-sm-12 d-flex justify-content-center align-item-center mt-1 mb-1">
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
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

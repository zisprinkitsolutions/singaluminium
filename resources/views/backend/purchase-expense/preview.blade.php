

<style>
    .customer-static-content {
        background: #ada8a81c;
    }

    .customer-dynamic-content {
        background: #706f6f33;
    }

    .proview-table tr td,
    .proview-table tr th {
        /* border: 1px solid black !important; */
        padding: 3px 6px;
    }

    .customer-dynamic-content2 {
        background: #fff !important;
    }

    .customer-content {
        border: 1px solid black !important;
    }

    @media print and (color) {
        .proview-table {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    @media print {
        .row {
            display: flex;
        }

        .col-md-1 {
            max-width: 8.33% !important;
        }

        .col-md-2 {
            max-width: 16.66% !important;
        }

        .col-md-8 {
            max-width: 66.66% !important;
        }

        .col-md-10 {
            max-width: 83.33% !important;
        }

        .col-md-11 {
            max-width: 91.66% !important;
        }

        .customer-static-content {
            background: #ada8a81c;
        }

        .customer-dynamic-content {
            background: #706f6f33;
        }

        .proview-table tr td,
        table tr th {
            border: 1px solid black !important;
        }

        #widgets-Statistics {
            padding: 2px !important;
        }

        .customer-dynamic-content2 {
            background: #fff !important;
        }

        .customer-content {
            border: 1px solid black !important;
        }
    }
</style>

@php
    $whole = floor($purchase_exp->total_amount);
    $fraction = number_format($purchase_exp->total_amount - $whole, 2);
    $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
    $amount_in_word = $f->format($whole);
    $amount_in_word2 = $f->format((int) ($fraction * 100));
@endphp

<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse">
        <div class="pr-1" style="padding-top: 5px;padding-right: 24px !important;">
            <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a>
        </div>
        <div class="pr-1 w-100 pl-2 text-left">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">Expense</h4>
        </div>
    </div>
</section>

<div class="receipt-voucher-hearder invoice-view-wrapper" style="margin: 50px 20px; border-radius: 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>

<section id="widgets-Statistics" style="padding: 15px 22px;">
    <div class="row">
        <div class="col-md-12 text-center invoice-view-wrapper student_profle-print">
            <h2>Expense</h2>
        </div>

        <div class="col-sm-12">
            <div class="customer-info">
                <table class="table table-sm table-bordered" style="color: #1d1d1d !important;">
                    <tr>
                        <td class="text-left w-75" >
                            <strong style="display: inline-block; width: 100px;">Payee</strong>
                            <span style="display: inline-block; width: 10px;">:</span>
                            <strong>{{ $purchase_exp->party->pi_name??'' }}</strong>
                        </td>
                        <td class="text-left">
                            <strong style="display: inline-block; width: 100px;">Bill No</strong>
                            <span style="display: inline-block; width: 10px;">:</span>
                            {{ $purchase_exp->purchase_no }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            <strong style="display: inline-block; width: 100px;">Address</strong>
                            <span style="display: inline-block; width: 10px;">:</span>
                            {{ optional($purchase_exp->party)->address }}
                        </td>
                        <td class="text-left">
                            <strong style="display: inline-block; width: 100px;">Invoice No</strong>
                            <span style="display: inline-block; width: 10px;">:</span>
                            {{ $purchase_exp->invoice_no }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            <strong style="display: inline-block; width: 100px;">Contact No</strong>
                            <span style="display: inline-block; width: 10px;">:</span>
                            {{ $purchase_exp->party->con_no }}
                        </td>
                        <td class="text-left">
                            <strong style="display: inline-block; width: 100px;">Expense Date</strong>
                            <span style="display: inline-block; width: 10px;">:</span>
                            {{ date('d/m/Y', strtotime($purchase_exp->date)) }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-sm table-bordered border-botton proview-table" style="color: black; ">
                    <thead style="background: #706f6f33 !important;color: black;">
                        <tr >
                            <th class="text-left"  style="text-transform: uppercase; color: black !important;">Account Head</th>
                            <th class="text-left"  style="text-transform: uppercase; color: black !important;">Item Description</th>
                            <th  style="text-transform: uppercase; color: black !important;">QTY</th>
                            <th  style="text-transform: uppercase; color: black !important;">Unit</th>
                            <th class="text-right"  style="text-transform: uppercase; color: black !important;">Amount <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                            <th class="text-right"  style="text-transform: uppercase; color: black !important;">VAT <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                            <th class="text-right"  style="width:150px; text-transform: uppercase; color: black !important;">Total Amount <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                        </tr>
                    </thead>

                    <tbody class="user-table-body">
                        @foreach ($purchase_exp->items as $item)
                        <tr>
                            @if ($item->head_sub)
                            <td class="text-left">{{$item->head_sub->name??''}}</td>
                            @else
                            <td class="text-left">{{$item->head->fld_ac_head??''}}</td>
                            @endif
                            <td class="text-left">{{$item->item_description}}</td>
                            <td class="text-center"> {{$item->qty}} </td>
                            <td class="text-center"> {{$item->unit->name??''}} </td>
                            <td class="text-right">{{number_format($item->amount,2)}}</td>
                            <td class="text-right">{{number_format($item->vat,2)}}</td>
                            <td class="text-right">{{number_format($item->total_amount,2)}}</td>
                        </tr>

                        @endforeach
                        <tr>
                            <td colspan="5" rowspan="3"><strong>Narration: {{$purchase_exp->narration}}</strong></td>
                            <td class="text-right">Total Amount</td>
                            <td class="text-right">{{number_format($purchase_exp->amount,2)}}</td>
                        </tr>
                        <tr>
                            <td class="text-right">VAT</td>
                            <td class="text-right">{{number_format($purchase_exp->vat,2)}}</td>
                        </tr>
                        <tr>
                            <td class="text-right">Total Amount</small></td>
                            <td class="text-right">{{number_format($purchase_exp->total_amount,2)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>





    @if($purchase_exp->documents->count() > 0)
    <section>
        <div class="row pt-2">
            @foreach ($purchase_exp->documents as $document)
            <div class="col-md-2 text-center py-1 px-4 print-hideen document-file" id="document-{{$document->id}}">
                <button class="remove-document py-1 d-none" id={{$document->id}}>
                    <i class="bx bx-trash text-danger"></i>
                </button>
                @if ($document->ext=='pdf')
                <a href="{{ asset('storage/upload/purchase-expense/' . $document->file_name) }}" target="blank">
                    <img src="{{asset('icon/pdf-download-icon-2.png')}}" class="img-fluid" style="width:100%;" alt="{{$document->ext}}">
                </a>
                @else
                <a href="{{ asset('storage/upload/purchase-expense/' . $document->file_name) }}" target="blank">
                    <img src="{{ asset('storage/upload/purchase-expense/' . $document->file_name) }}" class="img-fluid" style="width:100%;" alt="{{$document->ext}}">
                </a>
                @endif
            </div>
            @endforeach
        </div>
    </section>
    @endif
</section>

<section class="d-flex justify-content-center align-items-center print-hideen mb-1">
    @if ($purchase_exp->due_amount>0)
        <div class="payment-button1 print-hidden" style="padding: 3px;margin-top: 2px;">
            <button type="button" class="btn btn-icon btn-primary custom-action-btn" data-toggle="modal" data-target="#paymentModal" title="Payment Now">Payment</button>
        </div>
    @endif

    <div class="d-flex flex-row-reverse justify-content-center align-items-center ">
        <div class="print-hideen" style="">
            <a href="#" onclick="window.print();" class="btn btn-icon btn-secondary custom-action-btn" title="Print Now">
                <i class="bx bx-printer"></i> Print
            </a>
        </div>
    </div>
</section>

<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="padding: 6px !important;">
        <h5 class="modal-title" id="paymentModalLabel">Payment Form</h5>
        <button type="button" class="paymentModal close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{route('payment-voucher-store')}}" method="POST" id="PaymentformSubmit"  enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="voucher_type" value="due">
            <input type="hidden" name="purchase_id" value="{{$purchase_exp->id}}">
            <input type="hidden" name="party_info" value="{{$purchase_exp->party_id}}">
            <div class="row">
                <div class="col-md-6">
                    <label for="">Due Amount</label>
                    <input type="number" step="any" class="form-control inputFieldHeight" name="due_amount" value="{{$purchase_exp->due_amount}}" readonly>
                </div>
                <div class="col-md-6">
                    <label for="">Pay Amount</label>
                    <input type="number" step="any" class="form-control inputFieldHeight" name="pay_amount" max="{{$purchase_exp->due_amount}}">
                </div>
                <div class="col-md-6">
                    <label for="">Date</label>
                    <input type="text"  class="form-control inputFieldHeight datepicker" name="date" value="{{date('d/m/Y')}}" >
                </div>
                <div class="col-md-6">
                    <label for="">Payment Mode</label>
                    <select name="pay_mode" id="pay_mode" class="form-control inputFieldHeight" required>
                        <option value="">Select...</option>
                        @foreach ($modes as $item)
                            <option value="{{ $item->title }}" > {{ $item->title }} </option>
                        @endforeach

                    </select>
                </div>
                <div class="col-md-6" id="bank_name" style="display: none;">
                    <label for="">Bank Name</label>
                    <select name="bank_id" id="bank_id" class="form-control inputFieldHeight">
                        <option value="">Select...</option>
                        @foreach ($bank_name as $item)
                            <option value="{{ $item->id }}" > {{ $item->name }} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 cheque-content" style="display: none">
                    <div class="row">
                        <div class="col-md-6 mb-0 pb-0">
                            <div class="form-group">
                                <label for="">Issuing Bank</label>

                                <input type="text" autocomplete="off" name="issuing_bank"
                                    id="issuing_bank" class="form-control inputFieldHeight"
                                    placeholder="Issuing Bank">
                                @error('issuing_bank')
                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                    </div>
                                @enderror

                            </div>
                        </div>

                        <div class="col-md-6 mb-0 pb-0">
                            <div class="form-group">
                                <label for="">Branch</label>

                                <input type="text" autocomplete="off" name="bank_branch"
                                    id="bank_branch" class="form-control inputFieldHeight"
                                    placeholder="Branch">
                                @error('bank_branch')
                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                    </div>
                                @enderror

                            </div>
                        </div>

                        <div class="col-md-6 mb-0 pb-0">
                            <div class="form-group">
                                <label for="">Cheque No</label>

                                <input type="text" value="" autocomplete="off"
                                    class="form-control inputFieldHeight" name="cheque_no"
                                    placeholder="Cheque Number" id="cheque_no">
                                @error('cheque_no')
                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                    </div>
                                @enderror

                            </div>
                        </div>

                        <div class="col-md-6 mb-0 pb-0">
                            <div class="form-group">
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
                <div class="col-md-6 form-group">
                    <label for="">Paid By</label>
                    <select name="paid_by" id="paid_by" class="form-control inputFieldHeight common-select2" disabled>
                        <option value="">Select...</option>
                        @foreach ($employee as $item)
                            <option value="{{ $item->id }}" >{{ $item->full_name }} </option>
                        @endforeach
                    </select>
                    @error('paid_by')
                        <div class="btn btn-sm btn-danger">{{ $message }} </div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="">Narration</label>
                    <input type="text" required class="form-control inputFieldHeight" name="narration">
                </div>
                <div class="col-sm-12 form-group">
                    <label for="">Voucher File</label>
                    <input class="form-control  @error('voucher_file') is-invalid @enderror" type="file" name="voucher_file"  accept="application/pdf,image/png,image/jpeg,application/msword" >
                </div>
                <div class="col-md-12 d-flex justify-content-center align-items-center">
                    <button type="submit" class="btn btn-primary formButton">
                        <div class="d-flex">
                            <div class="formSaveIcon">
                                <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}" alt="" srcset="" width="25">
                            </div>
                            <div><span>Save</span></div>
                        </div>
                    </button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

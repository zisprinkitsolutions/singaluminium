
<style>
    html,
    body {
        height: 100%;
    }

    thead {
        background: #34465b;
        color: #fff !important;
        height: 30px;
    }
    .receipt-bg{
        display: none;
    }

    .remove-document{
        border: none;
        border-radius: 50%:
    }

    .document-file:hover .remove-document{
        display: block !important;
    }
    @media print{
        .receipt-bg{
            display: block;
        }
        .col-md-4{
            flex: 0 0 33.33333%;
            width: 33.33333% !important;
        }

        .flex-lg-row{
            flex-direction: row !important;
        }

        .row{
            display: flex;
        }
        .col-md-1, .col-1{
            width: 8.33% !important;
        }
        .col-md-2, .col-2{
            width: 16.66% !important;
        }
        .col-md-3, .col-3{
            width: 25% !important;
        }
        .col-md-4, .col-4{
            width: 33.33% !important;
        }
        .col-md-5, .col-5{
            width: 41.65% !important;
        }
        .col-md-6, .col-6{
            width: 50% !important;
        }
        .col-md-7, .col-7{
            width: 58.33% !important;
        }
        .col-md-8, .col-8{
            width: 66.66% !important;
        }
        .col-md-9, .col-9{
            width: 75% !important;
        }
        .col-md-10, .col-10{
            width: 83.33% !important;
        }
        .col-md-11, .col-11{
            width: 91.63% !important;
        }
        .col-md-12, .col-12{
            width: 100% !important;
        }
    }
</style>
@php
    $company_name= \App\Setting::where('config_name', 'company_name')->first();
    $company_address= \App\Setting::where('config_name', 'company_address')->first();
    $company_tele= \App\Setting::where('config_name', 'company_tele')->first();
    $company_email= \App\Setting::where('config_name', 'company_email')->first();
    $trn_no= \App\Setting::where('config_name', 'trn_no')->first();

@endphp
<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse" style="padding-top: 5px;padding-right: 8px;">
        <div class="pr-1" style="margin-top: 5px;">
            <a href="#" class="close btn-icon btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Close"><span
                    aria-hidden="true" data-dismiss="modal" aria-label="Close" ><i class='bx bx-x'></i></span></a>
        </div>

        @if ($fund->approved==false)
            <div class="" style="padding-right: 3px;margin-top: 6px;">
                <a href="{{ route('fund-allocation-approval', $fund->id) }}" class="btn btn-icon btn-warning" onclick="return confirm('Approve! Confirm?')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Approve"><i class="bx bx-check"></i></a>
            </div>
        @endif

        <div class="" style="padding-right: 3px;margin-top: 6px;">
            <a href="{{route('allocation-print',$fund)}}" class="btn btn-icon btn-success univarsal-print"  title="Allocation Print"><i class="bx bx-printer"></i></a>
        </div>
        <div class="pr-1 w-100 pl-2">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">Allocation</h4>
        </div>
    </div>
</section>
<section class="print-hideen">
    <div class="receipt-voucher-hearder invoice-view-wrapper mb-2" style=" border: 1px solid; margin: 50px 20px; border-radius: 20px;">
        @include('layouts.backend.partial.modal-header-info')
    </div>
    <form action="{{ route('fund-allocation.update',$fund->id) }}" class="mt-2" method="POST" id="formSubmit" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="cardStyleChange bg-white">
            <div class="card-body">
                <div class="row pr-2 pl-2">
                    <div class="col-md-2 changeColStyle search-item-pi">
                        <label for="">From Account</label>
                        <select name="from_account" id="from_account" class="common-select2 form-control" style="width: 100% !important" required>
                            <option value="">Select...</option>
                            @foreach ($modes as $mode)
                            <option value="{{$mode->id}}" {{$fund->account_id_from == $mode->id ?'selected':''}}>{{$mode->title}}</option>

                            @endforeach

                        </select>
                        <small id="pay_available_balance" class="text-danger"></small>
                        @error('from_account')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-2 changeColStyle search-item-pi">
                        <label for="">To Account</label>
                        <select name="to_account" id="to_account" class="common-select2 form-control" style="width: 100% !important" required>
                            <option value="">Select...</option>
                            @foreach ($modes as $mode)
                            <option value="{{$mode->id}}"{{$fund->account_id_to == $mode->id ?'selected':''}}>{{$mode->title}}</option>

                            @endforeach
                        </select>
                        @error('to_account')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-2 changeColStyle">
                        <label for="">Date</label>
                        <input type="text" value="{{ date('d/m/Y',strtotime($fund->date)) }}" class="form-control inputFieldHeight datepicker" name="date" placeholder="dd/mm/yyyy" required>
                        @error('date')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label for="">Amount</label>
                        <input type="number" step="any" name="amount" id="amount" value="{{$fund->amount}}" class="form-control inputFieldHeight" placeholder="Amount" required>
                    </div>
                    <div class="col-md-2">
                        <label for="">Transection Cost</label>
                        <input type="number" step="any" name="transaction_cost" value="{{$fund->transaction_cost}}"  id="transaction_cost" class="form-control inputFieldHeight" placeholder="Transaction Cost" required>
                    </div>

                    <div class="col-md-2">
                        <label for="">Transection Number</label>
                        <input type="text" step="any" name="transaction_number" value="{{$fund->transaction_number}}" id="transaction_number" class="form-control inputFieldHeight" placeholder="Transaction Number" required>
                    </div>

                </div>
            </div>
        </div>
        <div class="cardStyleChange">
            <div class="card-body bg-white">
                <div class="row px-1">
                    <div class="col-md-7">
                        <label for="">Notes</label>
                        <input type="text" name="note" id="note" value=" {{$fund->note}}" class="form-control inputFieldHeight" placeholder="note">
                    </div>
                    <div class="col-sm-3 form-group">
                        <label for="">Doucuments/Files</label>
                        <input type="file" class="form-control inputFieldHeight" name="voucher_scan[]" multiple accept="image/*">
                    </div>
                    <div class="col-sm-2 text-right d-flex justify-content-end mt-2 mb-1">
                        <button type="submit" class="btn btn-primary formButton " id="submitButton">
                            <div class="d-flex">
                                <div class="formSaveIcon">
                                    <img  src="{{asset('assets/backend/app-assets/icon/save-icon.png')}}" alt="" srcset=""  width="25">
                                </div>
                                <div><span>Save</span></div>
                            </div>
                        </button>
                        <a href="{{route("fund-collection.index")}}" class="btn btn-warning  d-none" id="newButton">New</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>




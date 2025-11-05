<style>
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
    .proview-table tr th {
        border: 1px solid black !important;
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
    $whole = floor($sale->total_budget);
    $fraction = number_format($sale->total_budget - $whole, 2);
    $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
    $amount_in_word = $f->format($whole);
    $amount_in_word2 = $f->format((int) ($fraction * 100));
@endphp
<section class=" border-bottom" style="padding: 5px 30px;background:#364a60;">
    <div class="d-flex flex-row-reverse">
        <div class="" style="margin-top: 6px;"><a href="#" class="close btn-sm btn btn-danger"
                data-dismiss="modal" aria-label="Close" style="padding-bottom: 8px;" title="Close"><span
                    aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
        {{-- <div class="" style="padding-right: 3px;margin-top: 6px;"><a href="#" onclick="window.print();" class="btn btn-sm btn-success" title="Print"><i class="bx bx-printer"></i></a></div> --}}
        <div class="" style="padding-right: 3px;margin-top: 6px;"><a href="{{ route('sale-print', $sale->id) }}"
                target="_blank" class="btn btn-sm btn-success" title="Print"><i class="bx bx-printer"></i></a></div>
        @if ($sale->invoice_type == 'Proforma Invoice')
            <div class="" style="padding-right: 3px;margin-top: 6px;"><a
                    href="{{ route('convert-to-tax-invoice', $sale->id) }}" class="btn btn-sm btn-info"
                    title="Convert into tax invoice"><i class='bx bx-transfer-alt'></i></a></div>

        @endif
        <div class="" style="padding-right: 3px;margin-top: 6px;">
            <a href="{{route('invoice-delete',$sale)}}" class="btn btn-icon btn-danger" title="Delete"
                onclick="event.preventDefault(); deleteAlert(this, 'About to delete invoice. Please, confirm?');">
                <i class="bx bx-trash"></i>
            </a>
        </div>

        <div class="w-100">
            <h4 style="font-family:Cambria;font-size: 1.4rem;color:white;">
                {{ 'Tax Invoice' . ' ('.$sale->invoice_no .')' }}
                Date: {{date('d/m/Y', strtotime($sale->date))}}
            </h4>
        </div>
    </div>
</section>
@php
    $trn_no = \App\Setting::where('config_name', 'trn_no')->first();
    $company_name = \App\Setting::where('config_name', 'company_name')->first();
@endphp
@include('layouts.backend.partial.modal-header-info')

<section id="widgets-Statistics" class="pt-2 px-1">
    <div class="row">
        <div class="col-sm-12">
            {{-- <h4 class=" text-center" style="margin:0;padding:0;line-height:40px;color: #1d1d1d !important;"> <strong>{{$sale->invoice_type}}</strong> </h4>
            <p class="text-center mb-2" style="color: #1d1d1d !important;">
                Invoice No: @if($sale->invoice_type == 'Tax Invoice')
                {{$sale->invoice_no}}
                @else
                {{$sale->proforma_invoice_no}}
                @endif,
                Date: {{date('d/m/Y', strtotime($sale->date))}},
                @if ($sale->invoice_type!='Proforma Invoice')
                VAT TRN: {{'('.$trn_no->config_value.')'}}, @endif
                Running No:{{$running_no}}
            </p> --}}
            <div class="customer-info m-1">
                <table class="table table-sm table-bordered" style="color: #1d1d1d !important;">
                    <tr>
                        <td class="text-left">
                            <strong style="padding-right: 89px;">TO</strong> <strong>: {{$sale->party->pi_name}}</strong>
                        </td>
                        <td class="text-left">
                            <strong>INVOICE NO <span style="padding-left: 26px">: {{$sale->invoice_type == 'Tax Invoice'?$sale->invoice_no:$sale->proforma_invoice_no}}</span></strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            <strong style="padding-right: 45px">ADDRESS</strong> <span><b>:</b></span> {{$sale->party->address}}
                        </td>
                        <td class="text-left">
                            <strong>INVOICE DATE <span style="padding-left: 12px">: {{date('d.m.Y', strtotime($sale->date))}}</span></strong>
                        </td>
                    </tr>
                    @php
                        $project = $sale->project;
                        $new_project = $project?$project->new_project:null;
                    @endphp
                    <tr>
                        <td class="text-left">
                            <strong style="padding-right: 46px;">PROJECT</strong>  <span><b>:</b></span> {{optional($new_project)->name}}, {{optional($new_project)->project_type}}
                        </td>
                        <td style="width: 300px;" class="text-left">
                            <strong>PLOT NUMBER &nbsp;&nbsp; :</strong> <span> {{optional($new_project)->plot}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-left">
                            <strong>CONSULTANT &nbsp;&nbsp;&nbsp;&nbsp; :</strong> {{optional($new_project)->consultant}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </div>


    <div class="row" style="padding: 15px;">
        <div class="col-md-12">
            <table class="table table-sm table-bordered border-botton proview-table" style="color: black; ">
                <thead style="background: #706f6f33 !important;color: black;">
                    <tr >
                        <th class="text-left pl-1" style="text-transform: uppercase; color: black !important;"> DESCRIPTION / DETAILS OF THE SUPPLY / QUANTITY</th>
                        <th class="text-center" style="text-transform: uppercase; color: black !important;width:70px"> UNIT PRICE <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                        <th class="text-center" style="text-transform: uppercase; color: black !important;width:130px"> NET AMOUNT <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                        <th class="text-center" style="text-transform: uppercase; color: black !important;width:80px"> TAX RATE </th>
                        <th class="text-center" style="text-transform: uppercase; color: black !important;width:80px"> TAX DUE AMOUNT <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                        <th class="text-center" style="text-transform: uppercase; color: black !important;width:140px"> PAYABLE AMOUNT </th>
                    </tr>
                </thead>
                    @php
                        $cc = 0;
                    @endphp
                <tbody class="user-table-body">
                    @foreach ($sale->tasks as $item)
                        <tr class="text-center">
                            <td class="text-left pl-1">
                                <pre>{{ $item->item_description }}</pre>
                            </td>
                            <td class="text-center">{{ number_format($item->rate,2) }}</td>
                            <td class="text-center">{{ number_format($item->budget,2) }}</td>
                            <td class="text-center">{{$item->vat->value??0}}%</td>
                            <td class="text-center">{{ number_format($item->total_budget - $item->budget,2) }}</td>
                            <td class="text-center">{{ number_format($item->total_budget,2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-right pr-1" colspan="5">TOTAL NET PAYABLE AMOUNT (EXCLUDING VAT)</td>
                        <td class="text-center">
                            {{ number_format($sale->budget,2) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right pr-1" colspan="5">VAT
                        </td>
                        <td class="text-center">
                            {{ number_format($sale->vat,2)}}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right pr-1" colspan="5"> RETENTION AMOUNT</td>
                        <td class="text-center">
                            {{ number_format($sale->retention_amount,2)}}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right pr-1" colspan="5"    style="background: #706f6f33">TOTAL GROSS AMOUNT (INCLUDING VAT)</small></td>
                        <td class="text-center"   style="background: #706f6f33">
                            {{ number_format($sale->total_budget,2) }}
                        </td>
                    </tr>
                    <tr>

                        <td colspan="6" class="text-right pr-1 text-capitalize" style="background: #706f6f33; color:#000; text-transform: uppercase !important;">
                            IN WORDS AED: {{ $amount_in_word }}
                            @if ($fraction > 0)
                                {{ '& ' . $amount_in_word2 }}
                            @endif ONLY
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    <div class="py-1" style="margin-left:10px;">
        @if ($sale->due_amount>0)
        <div class="" style="padding: 3px;margin-top: 4px; min-width: 150px !important;">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#receiptModal" style="padding: 3px 10px; ">Receive Payment </button>
        </div>
        @endif
    </div>

    <section>
        <div class="row pt-1">
            <div class="ml-2">
                <ul class="fileList">
                    @foreach ($sale->documents as $item)


                        <li class="voucher-img-wrapper">
                            <a href="{{ asset('storage/' . $item->file_path) }}" target="blank">
                                {{ str_replace('upload/sale/images', '', $item->file_path) }}
                            </a>

                            <img src="{{ asset('storage/' . $item->file_path) }}" width="40" height="40"
                                style="object-fit:cover; border:1px solid #ddd; margin:0 15px;" />



                            <form class="voucher-img-form" action="{{ route('voucher.delete', $item->id) }}" method="POST" style="">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:transparent; border:none; color:red; font-size:30px; cursor:pointer;"><i class="bx bx-trash"> </i></button>

                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    <div class="divFooter mb-1 ml-1 invoice-view-wrapper  footer-margin">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid"
                src="{{ asset('img/zikash-logo.png')}}" alt="" width="70"></span>
    </div>
</section>
<div class="img receipt-bg invoice-view-wrapper">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid"
        style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;"
        alt="">
</div>

<div class="modal fade" id="receiptModal" tabindex="-1" role="dialog"   aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="padding:5px 10px;background:#364a60;">
        <h5 class="modal-title" id="exampleModalLabel"
            style="font-family:Cambria;font-size: 2rem;color:white;margin-left: 10px;"> Receipt Payment </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" title="Close"  onclick="$(this).closest('.modal').hide()">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
      <div class="modal-body">
        <form action="{{route('temp-receipt-voucher-post')}}" method="POST" id="temp_invoice_receive"  enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="voucher_type" value="due">
            <input type="hidden" name="invoice_no" value="{{$sale->id}}">
            <input type="hidden" name="party_info" value="{{$sale->customer_id}}">
            <small class="text-danger">Advance Balance Available: {{$sale->party->balance}}</small>
            <div class="row">
                <div class="col-md-6">
                    <label for="">Date</label>
                    <input type="text"  class="form-control inputFieldHeight datepicker" name="date" value="{{date('d/m/Y')}}" >
                </div>

                <div class="col-md-6">
                    <label for="">Due Amount</label>
                    <input type="number" step="any" class="form-control inputFieldHeight" name="due_amount" value="{{$sale->due_amount}}" readonly>
                </div>
                <div class="col-md-6">
                    <label for="">Recevie Amount</label>
                    <input type="number" step="any" class="form-control inputFieldHeight" name="pay_amount" max="{{$sale->due_amount}}" required>
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
                <div class="col-md-6">
                    <label for="">Narration</label>
                    <input type="text" required class="form-control inputFieldHeight" name="narration">
                </div>
                <div class="col-md-12 text-right mt-2">
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

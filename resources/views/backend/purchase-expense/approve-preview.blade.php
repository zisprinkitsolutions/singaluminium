<style>
    html,
    body {
        height: 100%;
        overflow: hidden;
    }

    thead {
        background: #34465b;
        color: #fff !important;
        height: 30px;
    }

    @media print {

        .table tr th,
        .table tr td {
            color: #000000 !important;
            font-weight: 500 !important;
        }
    }
</style>

<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse">
        <div class="pr-1" style="padding-top: 8px;padding-right: 22px !important;"><a href="#"
                class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i
                        class='bx bx-x'></i></span></a></div>
        <div class="pr-1 w-100 pl-2">
            <h4 class="text-left" style="font-family:Cambria;font-size: 2rem;color:white;">Expense</h4>
        </div>
        {{-- <div class="py-1 pr-1"><a href="#" onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
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
                        <td class="text-left w-75">
                            <strong style="display: inline-block; width: 100px;">Payee</strong>
                            <span style="display: inline-block; width: 10px;">:</span>
                            <strong>{{ $purchase_exp->party->pi_name }}</strong>
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
                        <tr>
                            <th class="text-left" style="text-transform: uppercase; color: black !important;">Account
                                Head</th>
                            <th class="text-left" style="text-transform: uppercase; color: black !important;">Item
                                Description</th>
                            <th style="text-transform: uppercase; color: black !important;">QTY</th>
                            <th style="text-transform: uppercase; color: black !important;">Unit</th>
                            <th class="text-right" style="text-transform: uppercase; color: black !important;">Amount
                                <small>(@if (!empty($currency->symbole))
                                        {{ $currency->symbole }}
                                    @endif)</small>
                            </th>
                            <th class="text-right" style="text-transform: uppercase; color: black !important;">VAT
                                <small>(@if (!empty($currency->symbole))
                                        {{ $currency->symbole }}
                                    @endif)</small>
                            </th>
                            <th class="text-right"
                                style="width:150px; text-transform: uppercase; color: black !important;">Total Amount
                                <small>(@if (!empty($currency->symbole))
                                        {{ $currency->symbole }}
                                    @endif)</small>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="user-table-body">
                        @foreach ($purchase_exp->items as $item)
                            <tr>
                                @if ($item->head_sub)
                                    <td class="text-left">{{ $item->head_sub->name ?? '' }}</td>
                                @else
                                    <td class="text-left">{{ $item->head->fld_ac_head ?? '' }}</td>
                                @endif
                                <td class="text-left">{{ $item->item_description }}</td>
                                <td class="text-center"> {{ $item->qty }} </td>
                                <td class="text-center"> {{ $item->unit->name ?? '' }} </td>
                                <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                                <td class="text-right">{{ number_format($item->vat, 2) }}</td>
                                <td class="text-right">{{ number_format($item->total_amount, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" rowspan="3"><strong>Narration:
                                    {{ $purchase_exp->narration }}</strong></td>
                            <td class="text-right">Total Amount</td>
                            <td class="text-right">{{ number_format($purchase_exp->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-right">VAT</td>
                            <td class="text-right">{{ number_format($purchase_exp->vat, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-right">Total Amount</small></td>
                            <td class="text-right">{{ number_format($purchase_exp->total_amount, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>
<section id="widgets-Statistics">
    @if ($purchase_exp->documents->count() > 0)
    <div class="row p-2" id="documents">
        <h3 class="text-center">Supporting Document</h3>
        @foreach ($purchase_exp->documents as $document)
            <div class="col-md-2 text-center py-1 px-4 print-hideen document-file"
                id="document-{{ $document->id }}">
                <button class="remove-document py-1 d-none" id={{ $document->id }}>
                    <i class="bx bx-trash text-danger"></i>
                </button>
                @if ($document->ext == 'pdf')
                    <a href="{{ asset('storage/upload/purchase-expense/' . $document->file_name) }}"
                        target="blank">
                        <img src="{{ asset('icon/pdf-download-icon-2.png') }}" class="img-fluid"
                            style="width:100%;" alt="{{ $document->ext }}">
                    </a>
                @else
                    <a href="{{ asset('storage/upload/purchase-expense/' . $document->file_name) }}"
                        target="blank">
                        <img src="{{ asset('storage/upload/purchase-expense/' . $document->file_name) }}"
                            class="img-fluid" style="width:100%;" alt="{{ $document->ext }}">
                    </a>
                @endif
            </div>
        @endforeach
    </div>
    @endif

    <div class="row print-hideen pb-2">
        <div class="col-md-12 d-flex justify-content-center align-items-center ">
            @if (Auth::user()->hasPermission('Expense_Edit'))
                <div class="" style="">
                    <a href="#" id="{{ $purchase_exp->id }}"
                        class="btn btn-primary custom-action-btn expense-edit" title="Edit Now">
                        <i class="bx bx-edit"></i> Edit
                    </a>
                </div>
            @endif
            @if (Auth::user()->hasPermission('Expense_Approve'))
                <div class="" style="">
                    <a href="{{ route('purchase-approve', $purchase_exp) }}"
                        class="btn btn-success custom-action-btn approve-btn" title="Approve Now">
                        <i class='bx bx-check'></i> Approve
                    </a>
                </div>
            @endif
            @if (Auth::user()->hasPermission('Expense_Delete'))
                <div class="" style="">
                    <a href="{{ route('purchase-expense.delete', $purchase_exp) }}"
                        class="btn btn-danger custom-action-btn"
                        onclick="event.preventDefault(); deleteAlert(this, 'About to delete invoice. Please, confirm?');" title="Delete Now">
                        <i class="bx bx-trash"></i> Delete
                    </a>
                </div>
            @endif
            <div class="" style="">
                <a href="#" onclick="window.print();" class="btn btn-icon btn-secondary custom-action-btn" title="Print Now">
                    <i class="bx bx-printer"></i> Print
                </a>
            </div>
        </div>
    </div>

    <div class="divFooter mb-1 ml-1 footer-margin invoice-view-wrapper">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid"
                src="{{ asset('img/zikash-logo.png') }}" alt="" width="150"></span>
    </div>
</section>

<div class="img receipt-bg invoice-view-wrapper">
    {{-- <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid"
        style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;"
        alt=""> --}}

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>

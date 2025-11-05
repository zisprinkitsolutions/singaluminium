@extends('layouts.backend.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@section('content')
@include('layouts.backend.partial.style')
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

    .fileList {
        list-style: none;
        padding: 0 !important;
        margin-top: 15px;
    }

    .fileList li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f6f9fc;
        border: 1px solid #d1dbe5;
        padding: 8px 12px;
        border-radius: 5px;
        margin-bottom: 6px;
        font-size: 14px;
        color: #333;
        transition: background-color 0.2s;
    }

    .fileList li:hover {
        background-color: #eef4fa;
    }

    .fileList li button.remove-btn {
        background-color: #ff5b5b;
        color: white;
        border: none;
        padding: 3px 10px;
        font-size: 12px;
        border-radius: 3px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .fileList li button.remove-btn:hover {
        background-color: #e04040;
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
        text-align: center;
    }

    .table-sm th,
    .table-sm td {
        padding: 4px 6px;
    }

    #invoice td {
        padding: 13px 6px !important;
    }

    tr:nth-child(even) {
        background-color: #c8d6e357;
    }

    tr {
        cursor: pointer;
    }

    @media print {

        /* Global resets */
        body {
            color: #34465b !important;
            font-size: 12pt !important;
            line-height: 1.4 !important;
        }

        /* Hide navigation and form controls */
        .navbar,
        .btn,
        input,
        select,
        .form-control,
        .common-select2,
        .nav-tabs {
            display: none !important;
        }


        /* Page breaks */
        .page-break {
            page-break-after: always;
        }

        /* Table styling */
        table {
            border-collapse: collapse !important;
        }

        th,
        td {
            border: 1px solid #eee !important;
            padding: 4px !important;
            color: #444 !important;
        }


        span {
            color: #444 !important;
        }

        .nav.nav-tabs {
            border-bottom: 0px !important;
        }

        .tab-content {
            border-left: none !important;
            border-right: none !important;
            border-bottom: none !important;
        }
    }

    .select2-container--open .select2-dropdown--below {
        width: 30% !important;
    }
</style>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.sales._header', ['activeMenu' => 'list'])
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active">
                    <section id="widgets-Statistics" style="padding-left: 8px;">
                        @include('layouts.backend.partial.modal-header-info')

                        <div class="col-md-12 pt-2">
                            <div class="cardStyleChange">
                                <div class="card-body bg-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center" style="width: 80%;">
                                            @if (Auth::user()->hasPermission('Revenue_Create'))
                                            <button class="btn btn-primary inputFieldHeight create-form"
                                                style="padding:3px 8px !important; white-space: nowrap;"> Issue Tax
                                                Invoice
                                            </button>
                                            @endif
                                            <div style="padding-left:10px; width: 30%;" class="d-none">
                                                <select name="compnay_id" id="compnay_id_search"
                                                    class="common-select2 inputFieldHeight w-100">
                                                    <option value="">Select Company...</option>
                                                    <option value="0" selected>SINGH ALUMINIUM AND STEEL </option>
                                                </select>
                                            </div>
                                            <div style="padding-left:10px;">
                                                <input type="text" name="search" id="search"
                                                    class="form-control inputFieldHeight"
                                                    placeholder="Search by Invoice No">
                                            </div>

                                            <div style="padding-left:10px; width: 35%;">
                                                <select name="party_search" id="party_search"
                                                    class="common-select2 inputFieldHeight w-100">
                                                    <option value="">Select Party / Owner...</option>
                                                    @foreach ($parties as $party)
                                                    <option value="{{ $party->id }}">{{ $party->pi_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div style="padding-left:10px;">
                                                <input type="text" name="date_search" id="date_search"
                                                    class="form-control inputFieldHeight datepicker"
                                                    placeholder="Search by Date">
                                            </div>
                                        </div>

                                        {{-- <div class="d-flex">
                                            <button class="btn btn-info inputFieldHeight"
                                                style="padding:3px 8px !important; width:80px; margin-right:5%;"
                                                data-toggle="modal" data-target="#excel_import"> Import </button>
                                            <button class="btn btn-info inputFieldHeight"
                                                style="padding:3px 8px !important; width:80px; margin-right:5%;"
                                                onclick="window.print()"> Print </button>

                                            <button onclick="exportToExcel();" class="btn btn-success inputFieldHeight"
                                                style="padding:3px 8px !important; white-space: nowrap;"> Excel Export
                                            </button>
                                        </div> --}}
                                        <!-- Right Side (Export/Import) -->
                                        <div class="d-flex justify-content-end" style="width: 20%;">
                                            <div class="dropdown ">
                                                <button class="btn btn-info inputFieldHeight formButton dropdown-toggle"
                                                    type="button" id="exportDropdown" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false"
                                                    style="padding:4px 15px !important;">
                                                    Export / Import
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="exportDropdown">
                                                    <a class="dropdown-item" href="javascript:void(0);"
                                                        onclick="exportToExcel('invoice')">Excel Export</a>
                                                    <a class="dropdown-item" href="javascript:void(0);"
                                                        onclick="window.print()">Print</a>
                                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#excel_import">Excel Import</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div><br>

                                    <h5 class="invoice-view-wrapper"> Revenue List </h5>

                                    <table class="table table-bordered table-sm" id="invoice">
                                        <thead class="thead">
                                            <tr>
                                                <th style="text-align: center !important;"> SL</th>
                                                <th style="text-align: center !important; min-width:fit-content">Date
                                                </th>
                                                <th style="text-align: center !important; min-width: 90px">Invoice No
                                                </th>
                                                {{-- <th style="text-align: center !important; min-width: fit-conten;"> Company</th> --}}
                                                <th style="text-align: left !important; min-width:fit-content">Party /
                                                    Owner Name </th>
                                                <th style="text-align: left !important; min-width:fit-content"> Project
                                                    Name </th>
                                                {{-- <th style="text-align: center !important; min-width:90px"> Plot No </th> --}}
                                                <th style="text-align: left !important; min-width:fit-content"> Location
                                                </th>
                                                <th style="text-align: center !important; min-width:fit-content">Amount
                                                    <small>(@if (!empty($currency->symbole))
                                                        {{ $currency->symbole }}
                                                        @endif) <br>
                                                        {{ number_format($data['total_amount'], 2) }}</small>
                                                </th>
                                                <th style="text-align: left !important;min-width: fit-content"> Remarks
                                                </th>
                                                <th
                                                    style="text-align: left !important; min-width: fit-content ;">
                                                    status
                                                </th>
                                                <th style="text-align: right !important; min-width:fit-content">Received
                                                    <br> {{ number_format($data['paid_amount'], 2) }}
                                                </th>
                                                <th style="text-align: right !important; min-width:fit-content">
                                                    Receivable <br> {{ number_format($data['due_amount'], 2) }} </th>
                                            </tr>
                                        </thead>
                                        <tbody id="sale-body">
                                            @foreach ($pending_sales as $key => $item)
                                            @php
                                            $project = $item->project;
                                            @endphp

                                            <tr class="approve_view p-0" id="{{ $item->id }}">
                                                <td>{{ ($pending_sales->currentPage() - 1) * $pending_sales->perPage() +
                                                    $key + 1 }}
                                                </td>
                                                <td style="text-align: center !important;">
                                                    {{ date('d/m/Y', strtotime($item->date)) }}</td>
                                                @if ($item->invoice_type == 'Proforma Invoice')
                                                <td style="text-align: center !important;">
                                                    {{ $item->proforma_invoice_no }}</td>
                                                @elseif($item->invoice_type == 'Direct Invoice')
                                                <td style="text-align: center !important;">
                                                    {{ $item->invoice_no_s_d }}
                                                </td>
                                                @else
                                                <td style="text-align: center !important;">
                                                    {{ $item->invoice_no }}</td>
                                                @endif
                                                {{-- <td style="text-align: center !important;"
                                                    title="{{ $item->company->company_name ?? 'SINGH ALUMINIUM AND STEEL' }}">
                                                    {{ \Illuminate\Support\Str::limit(
                                                    $item->company->company_name ??
                                                    'SINGH ALUMINIUM AND STEEL',
                                                    10,
                                                    ) }}
                                                </td> --}}
                                                <td style="text-align: left !important;"
                                                    title="{{ optional($item->party)->pi_name }}">
                                                    {{ \Illuminate\Support\Str::words(optional($item->party)->pi_name,
                                                    2, '...') }}
                                                </td>

                                                <td style="text-align: left !important;"
                                                    title="{{ optional($project)->project_name }}">
                                                    {{ \Illuminate\Support\Str::words(optional($project)->project_name, 1,
                                                    '...') }}
                                                </td>
                                                <td class="text-left"> {{ optional($project)->address }}</td>

                                                <td style="text-align: right !important;">
                                                    {{ number_format($item->total_amount, 2) }}</td>
                                                <td style="text-align: left !important;"> <span
                                                        class="bg-warning text-white" style="padding: 2px 3px;">
                                                        Awaiting Approval </span> </td>
                                                @if ($item->due_amount > 0 and $item->paid_amount > 0)
                                                <td style="text-align: left !important;"> <span
                                                        class="bg-warning text-white" style="padding: 2px 3px;"> Partial
                                                        Paid </span> </td>
                                                @elseif($item->due_amount <= 0) <td
                                                    style="text-align: left !important;"> <span
                                                        class="bg-success text-white" style="padding: 2px 3px;"> Full
                                                        Paid </span> </td>
                                                    @else
                                                    <td style="text-align: left !important;"> <span
                                                            class="bg-danger text-white" style="padding: 2px 3px;">
                                                            Receivable </span> </td>
                                                    </td>
                                                    @endif

                                                    <td style="text-align: right">
                                                        {{ number_format($item->paid_amount, 2) }} </td>
                                                    <td style="text-align: right">
                                                        {{ number_format($item->due_amount, 2) }} </td>
                                            </tr>
                                            @endforeach
                                        </tbody>

                                        <tbody id="sale-body-hide">
                                            @foreach ($sales as $key => $item)
                                            @php
                                            $project = $item->project;
                                            @endphp
                                            <tr class="sale_view" id="{{ $item->id }}">
                                                <td>{{ ($pending_sales->currentPage() - 1) * $pending_sales->perPage() +
                                                    $key + 1 }}
                                                </td>
                                                <td style="text-align: center !important;">
                                                    {{ date('d/m/Y', strtotime($item->date)) }}</td>

                                                <td style="text-align: center !important;">{{ $item->invoice_no }}
                                                </td>
                                                {{-- <td style="text-align: center !important;"
                                                    title="{{ $item->company->company_name ?? 'SINGH ALUMINIUM AND STEEL' }}">
                                                    {{ \Illuminate\Support\Str::limit($item->company->company_name ??
                                                    'SINGH ALUMINIUM AND STEEL', 15) }}
                                                </td> --}}

                                                <td style="text-align: left !important;"
                                                    title="{{ optional($item->party)->pi_name }}">
                                                    {{ \Illuminate\Support\Str::limit(optional($item->party)->pi_name,
                                                    15) }}
                                                </td>

                                                <td style="text-align: left !important;"
                                                    title="{{ optional($project)->project_name }}">
                                                    {{ \Illuminate\Support\Str::limit(optional($project)->project_name, 15)
                                                    }}
                                                </td>
                                                <td class="text-left"> {{ optional($project)->address }}</td>

                                                <td style="text-align: right !important;">
                                                    {{ number_format($item->total_budget, 2) }}</td>
                                                <td style="text-align: left !important;"> <span
                                                        class="bg-success text-white" style="padding: 2px 3px;">
                                                        Submitted </span> </td>
                                                @if ($item->due_amount > 0 && $item->paid_amount > 0)
                                                <td style="text-align: left !important;"> <span
                                                        class="bg-warning text-white" style="padding: 2px 3px;"> Partial
                                                        Received </span> </td>
                                                @elseif($item->due_amount <= 0) <td
                                                    style="text-align: left !important;"> <span
                                                        class="bg-success text-white" style="padding: 2px 3px;"> Full
                                                        Received </span> </td>
                                                    @else
                                                    <td style="text-align: left !important;"> <span
                                                            class="bg-danger text-white" style="padding: 2px 3px;">
                                                            Receivable </span> </td>
                                                    </td>
                                                    @endif
                                                    <td style="text-align: right">
                                                        {{ number_format($item->paid_amount, 2) }} </td>
                                                    <td style="text-align: right">
                                                        {{ number_format($item->due_amount, 2) }} </td>
                                            </tr>
                                            @endforeach
                                        </tbody>

                                    </table>

                                    {!! $sales->links() !!}


                                </div>
                            </div>
                        </div>
                        @include('layouts.backend.partial.modal-footer-info')
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- modal --}}
<!-- END: Content-->
{{-- @include('backend.sale.create_modal'); --}}

<div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="voucherPreviewShow">

            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="voucherDetailsPrintModal" tabindex="-1" rrole="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="voucherDetailsPrint">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"
    data-keyboard="false" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding:5px 10px;background:#364a60;">
                <h5 class="modal-title" id="exampleModalLabel"
                    style="font-family:Cambria;font-size: 2rem;color:white;margin-left: 10px;"> Receipt Payment </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" title="Close"
                    onclick="$(this).closest('.modal').hide()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('temp-receipt-voucher-post') }}" method="POST" id="temp_invoice_receive"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="voucher_type" value="due">
                    <input type="hidden" name="invoice_no" id="receipt_invoice_no" value="">
                    <input type="hidden" name="party_info" id="receipt_party_info" value="">
                    <small class="text-danger" id="receipt_advance_balance">Advance Balance Available: </small>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Date</label>
                            <input type="text" class="form-control inputFieldHeight datepicker" name="date"
                                value="{{ date('d/m/Y') }}">
                        </div>

                        <div class="col-md-6">
                            <label for="">Due Amount</label>
                            <input type="number" step="any" class="form-control inputFieldHeight"
                                id="receipt_due_amount" name="due_amount" value="" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="">Recevie Amount</label>
                            <input type="number" step="any" class="form-control inputFieldHeight"
                                id="receipt_pay_amount" name="pay_amount" min="0.01" required>
                        </div>

                        <div class="col-md-6">
                            <label for="">Payment Mode</label>
                            <select name="pay_mode" id="pay_mode" class="form-control inputFieldHeight" required>
                                <option value="">Select...</option>
                                @foreach ($modes as $item)
                                <option value="{{ $item->title }}"> {{ $item->title }} </option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-6" id="bank_name" style="display: none;">
                            <label for="">Bank Name</label>
                            <select name="bank_id" id="bank_id" class="form-control inputFieldHeight">
                                <option value="">Select...</option>
                                @foreach ($bank_name as $item)
                                <option value="{{ $item->id }}"> {{ $item->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 cheque-content" style="display: none">
                            <div class="row">
                                <div class="col-md-6 mb-0 pb-0">
                                    <div class="form-group">
                                        <label for="">Issuing Bank</label>

                                        <input type="text" autocomplete="off" name="issuing_bank" id="issuing_bank"
                                            class="form-control inputFieldHeight" placeholder="Issuing Bank">
                                        @error('issuing_bank')
                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                        </div>
                                        @enderror

                                    </div>
                                </div>

                                <div class="col-md-6 mb-0 pb-0">
                                    <div class="form-group">
                                        <label for="">Branch</label>

                                        <input type="text" autocomplete="off" name="bank_branch" id="bank_branch"
                                            class="form-control inputFieldHeight" placeholder="Branch">
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
                                        <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}" alt=""
                                            srcset="" width="25">
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


<div class="modal fade" id="excel_import" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Import MS Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('invoice-excel-import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="file" required class="form-controll" name="excel_file" accept=".xlsx, .xls, .csv">
                    @php
                    $token = time() + rand(10000, 99999);
                    @endphp
                    <input type="hidden" name="token" value="{{ $token }}">
                    <button type="submit" class="btn btn-primary text-right">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
<script src="{{ asset('assets/backend') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{ asset('assets/backend') }}/app-assets/js/scripts/forms/select/form-select2.js"></script>
<script src="{{ asset('assets/backend') }}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<script src="{{ asset('assets/backend') }}/app-assets/js/scripts/forms/form-repeater.js"></script>
{{-- js work by mominul start --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
@include('backend.sale.modal-js');

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
@if (session('message_import'))
<script>
    const rawHtml = `{!! session('message_import') !!}`;

            Swal.fire({
                icon: '{{ session('alert-type') ?? 'success' }}',
                title: 'Invoice Import Result',
                html: rawHtml +
                    `<br><button id="exportExcelBtn" class="swal2-confirm swal2-styled" style="background-color: #3085d6; margin-top: 10px;">Export to Excel</button>`,
                showConfirmButton: true
            });

            // Wait for DOM to load inside Swal
            setTimeout(() => {
                $('#exportExcelBtn').on('click', function() {
                    // Extract messages from <li> tags
                    const container = document.createElement('div');
                    container.innerHTML = rawHtml;

                    const items = Array.from(container.querySelectorAll('li')).map(li => [li.textContent
                    .trim()]);

                    if (items.length === 0) {
                        items.push(['No skipped messages found.']);
                    }

                    // Create worksheet
                    const ws = XLSX.utils.aoa_to_sheet([
                        ['Skipped Messages'], ...items
                    ]);
                    const wb = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(wb, ws, "Skipped Rows");

                    // Trigger download
                    XLSX.writeFile(wb, 'invoice_skipped_rows.xlsx');
                });
            }, 100);
</script>
@endif
<script>
    function reloadPage() {
            window.location.reload();
        }

        function exportToExcel() {
            var table = document.getElementById("invoice");
            var wb = XLSX.utils.table_to_book(table, {
                sheet: "Invoice"
            });
            XLSX.writeFile(wb, "invoice-list.xlsx");
        }

        $(document).on('click', '.edit-btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                    $('.common-select2').select2();
                }
            })
        })


         $(document).on('click', '.create-form', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: "{{ route('invoice-create-form') }}",
                type: 'get',
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                    $('.common-select2').select2();
                }
            })
        })
        $(document).on("click", ".approve_view", function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ URL('approve-sale-modal') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                }
            });
        });
        $(document).on("click", ".sale_view", function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ URL('sale-modal') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                }
            });
        });


        $('#search').keyup(function() {
            var value = $(this).val();
            var compnay_id = $('compnay_id_search').val();
            var party = $('#party_search').val();
            var date = $('#date_search').val();

            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('search-all-invoice-list') }}",
                method: "POST",
                data: {
                    value: value,
                    party: party,
                    date: date,
                    compnay_id: compnay_id,
                    _token: _token,
                },
                success: function(response) {
                    $("#sale-body").html(response);
                    $('#sale-body-hide').hide();
                }
            })
        });

        $('#party_search').change(function() {
            var party = $(this).val();
            var compnay_id = $('compnay_id_search').val();
            var value = $('#search').val();
            var date = $('#date_search').val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('search-all-invoice-list') }}",
                method: "POST",
                data: {
                    value: value,
                    party: party,
                    date: date,
                    company_id: compnay_id,
                    _token: _token,
                },
                success: function(response) {
                    $("#sale-body").html(response);
                    $('#sale-body-hide').hide();
                }
            })
        });
        $('#compnay_id_search').change(function() {
            var compnay_id = $(this).val();
            var party = $('#party_search').val();
            var value = $('#search').val();
            var date = $('#date_search').val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('search-all-invoice-list') }}",
                method: "POST",
                data: {
                    value: value,
                    party: party,
                    date: date,
                    compnay_id: compnay_id,
                    _token: _token,
                },
                success: function(response) {
                    $("#sale-body").html(response);
                    $('#sale-body-hide').hide();
                }
            })
        });

        $('#date_search').change(function() {
            var date = $(this).val();
            var compnay_id = $('compnay_id_search').val();
            var value = $('#search').val();
            var party = $('#party_search').val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('search-all-invoice-list') }}",
                method: "POST",
                data: {
                    value: value,
                    party: party,
                    date: date,
                    compnay_id: compnay_id,
                    _token: _token,
                },
                success: function(response) {
                    $("#sale-body").html(response);
                    $('#sale-body-hide').hide();
                }
            })

        });


        $(document).on('click', '.receiptModal', function(e) {
            e.preventDefault();
            $('#receiptModal').modal('show');
        })

        $(document).on('submit', '#temp_invoice_receive', function(e) {
            e.preventDefault(); // avoid executing the actual submit of the form.

            var form = $(this);
            var url = form.attr('action');
            var data = new FormData(this);

            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    if (response.warning) {
                        toastr.warning("{{ Session::get('message') }}", response.warning);
                    } else if (response.status) {
                        // Handle validation errors
                        for (var i = 0; i < Object.keys(response.status).length; i++) {
                            var key = i + ".invoice";
                            if (response.status.hasOwnProperty(key)) {
                                var errorMessages = response.status[key];
                                for (var j = 0; j < errorMessages.length; j++) {
                                    toastr.warning(errorMessages[j]);
                                }
                            }
                        }
                    } else if (response == 1) {
                        toastr.warning('Please Check Your Advance Balance');
                    } else {
                        $('#receiptModal').modal('hide');
                        document.getElementById("voucherPreviewShow").innerHTML = response;
                        $('#voucherPreviewModal').modal('show');
                    }
                },
                error: function(err) {
                    let error = err.responseJSON;
                    $.each(error.errors, function(index, value) {
                        toastr.error(value, "Error");
                    });
                }
            });
        });

        $(document).on('change', '#pay_mode', function(e) {
            if ($(this).val() == 'Cheque') {
                $(".deposit_date").attr('required', true);
                $("#bank_branch").attr('required', true);;
                $("#issuing_bank").attr('required', true);
                $("#cheque_no").attr('required', true);
                $('.cheque-content').show();
            } else {
                $(".deposit_date").removeAttr('required');
                $("#bank_branch").removeAttr('required');;
                $("#issuing_bank").removeAttr('required');
                $("#cheque_no").removeAttr('required');
                $('.cheque-content').hide();
            }
            var pay_mode = $(this).val();
            if (pay_mode == 'Bank') {
                $('#bank_name').show();
                $("#bank_id").attr('required', true);
            } else {
                $('#bank_name').val(null).trigger('change');
                $('#bank_name').hide();
                $("#bank_id").removeAttr('required');
            }
        })

        $(document).on('mouseenter', '.datepicker', function() {
            $(this).datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-1000:+1000",
                dateFormat: "dd/mm/yy",
            });
        });

        $(document).on('click', '.approve-btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var amount = parseFloat($(this).data('total'));
            var message = 'Are you sure';
            if (amount > 0) {
                var rentention = amount / 9;
                message = `Are you sure to approve this invoice ? `;
            }

            Swal.fire(alertDesign(message, 'approve'))
                .then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            'type': 'get',
                            success: function(res) {
                                console.log(res)
                                Swal.fire(alertDesign('Invoice approved successfully',
                                        'Create receipt voucher!'))
                                    .then((result) => {
                                        if (result.isConfirmed) {

                                            $('#receipt_invoice_no').val(res.id);
                                            $("#receipt_party_info").val(res.party_id);
                                            if (res.advance > 0) {
                                                $("#receipt_advance_balance").val(res.advance);
                                            }
                                            $('#receipt_due_amount').val(res.due_amount);
                                            $('#receipt_pay_amount').prop('max', res
                                            .due_amount);
                                            $('#receiptModal').modal('show');
                                        } else {
                                            window.location.reload();
                                        }
                                    });

                            }
                        })
                    }
                });
        })
</script>
@endpush

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
            text-align: center;
        }

        td {
            font-size: 12px !important;
            height: 25px !important;
            text-align: center;
        }

        .table-sm th,
        .table-sm td {
            padding: 3 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
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

        .payment-part {
            display: none !important;
        }

        /* .select2-container--open .select2-dropdown--below {
            width: 30% !important;
        } */

    </style>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.sales._header', ['activeMenu' => 'receipts'])
                <div class="tab-content journaCreation">
                    <div id="journaCreation" class="tab-pane bg-white active">
                        <section id="widgets-Statistics" class="p-2">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="cardStyleChange" style="width: 100%">
                                        <div class="card-body bg-white">
                                            @include('layouts.backend.partial.modal-header-info')
                                            @include('layouts.backend.partial.modal-footer-info')

                                            <h5 class="invoice-view-wrapper"> Receipt List </h5>

                                            <div class="d-flex justify-content-between align-items-center mb-1 print-hideen">
                                                @if (Auth::user()->hasPermission('Revenue_Create'))
                                                    <button class="btn btn-primary inputFieldHeight"
                                                        style="padding:3px 6px !important; margin-right:5px;" data-toggle="modal"
                                                        data-target="#receiptModal"> Issue Receipt Voucher </button>
                                                @endif
                                                {{-- <button class="btn btn-info inputFieldHeight print-hideen"
                                                    style="padding:3px 8px !important; width:130px;"
                                                    onclick="window.print()"> Print </button>
                                                <button onclick="exportToExcel();"
                                                    class="btn btn-success inputFieldHeight print-hideen"
                                                    style="padding:3px 8px !important; width:130px"> Excel Export </button>
                                                <button class="btn btn-success inputFieldHeight print-hideen"
                                                    data-toggle="modal" data-target="#excel_import"
                                                    style="padding:3px 8px !important; width:130px"> Excel Import </button> --}}

                                                <div class="dropdown">
                                                    <button class="btn btn-info inputFieldHeight formButton dropdown-toggle"
                                                        type="button" id="exportDropdown" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false"
                                                        style="padding:4px 15px !important;">
                                                        Export / Import
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="exportDropdown">
                                                        <a class="dropdown-item print-hideen" href="javascript:void(0);"
                                                            onclick="exportToExcel()">Excel Export</a>
                                                        <a class="dropdown-item print-hideen" href="javascript:void(0);"
                                                            onclick="window.print()">Print</a>
                                                        <a class="dropdown-item print-hideen" href="javascript:void(0);"
                                                            data-toggle="modal" data-target="#excel_import">Excel Import</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-1 print-hideen" style="padding: 15px">
                                                <div class="col-2 p-0" style="padding-right: 5px !important;">
                                                    <input type="text" name="search" id="search"
                                                        class="form-control inputFieldHeight"
                                                        placeholder="Search by Receipt No">
                                                </div>
                                                <div class="col-3 p-0 d-none" style="padding-right: 5px !important;" class="">
                                                    <select name="compnay_id" id="compnay_id_search"
                                                        class="common-select2 inputFieldHeight w-100">
                                                        <option value="">Select Company...</option>
                                                        <option value="0" selected>SINGH ALUMINIUM AND STEEL
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-4 p-0" style="padding-right: 5px !important;">
                                                    <select name="party_search" id="party_search"
                                                        class="common-select2 inputFieldHeight w-100">
                                                        <option value="">Select Party...</option>
                                                        @foreach ($parties as $party)
                                                            <option value="{{ $party->id }}">{{ $party->pi_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-2 p-0" style="padding-right: 5px !important;">
                                                    <input type="text" name="date_search" id="date_search"
                                                        class="form-control inputFieldHeight datepicker"
                                                        placeholder="Search by Date">
                                                </div>
                                                <div class="col-1 p-0" style="">
                                                    <select name="mode_search" id="mode_search"
                                                        class="common-select2 inputFieldHeight w-100">
                                                        <option value="">Pay Mode...</option>
                                                        @foreach ($modes as $mode)
                                                            <option value="{{ $mode->title }}">{{ $mode->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <table class="table table-bordered table-sm" id="receipt-list">
                                                <thead class="thead">
                                                    <tr>
                                                        <th style="min-width: fit-content;">SL No</th>
                                                        <th style="min-width: fit-content;">Date</th>
                                                        <th style="min-width: fit-content;">Receipt No</th>
                                                        <th style="min-width: fit-content; text-align:left;">Party / Owner
                                                            Name</th>
                                                        <th style="min-widtn:fit-content;text-align:left"> Project Name
                                                        </th>
                                                        <th style="min-widtn:fit-content;text-align:left"> Plot No </th>
                                                        <th style="min-widtn:fit-content;text-align:left"> Location </th>
                                                        {{-- <th style="width: 24%; text-align:left;">Narration</th> --}}
                                                        <th style="min-width: fit-content;">Amount <br> {{ number_format($data['total_amount'], 2) }}</th>
                                                        <th style="min-width: fit-content;"> Remarks </th>
                                                        <th style="min-width: fit-content;">Mode</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="receipt-body">
                                                    @foreach ($temp_receipt_list as $key => $item)
                                                        @php
                                                            $project_name = '';
                                                            $plot_no = '';
                                                            $location = '';
                                                            foreach ($item->items as $receipt_sale) {
                                                                $invoice = $receipt_sale->invoice;
                                                                $project = $invoice->project ? $invoice->project : null;
                                                                $new_project = $project ? $project->new_project : null;
                                                                if ($new_project) {
                                                                    $project_name .= $new_project->name . ' ,';
                                                                    $plot_no .= $new_project->plot . ' ,';
                                                                    $location = $new_project->location . ', ';
                                                                }
                                                            }
                                                        @endphp
                                                        <tr class="receipt_view" id="{{ $item->id }}">

                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ date('d/m/Y', strtotime($item->date)) }}</td>
                                                            <td>{{ $item->receipt_no }}</td>
                                                            <td style="text-align: left !important;"
                                                                title="{{ optional($item->party)->pi_name }}">
                                                                {{ \Illuminate\Support\Str::limit(optional($item->party)->pi_name, 30) }}
                                                            </td>
                                                            <td style="text-align:left" title="{{ $project_name }}">

                                                                 @if ($item->job_project)
                                                                 {{ \Illuminate\Support\Str::limit($item->job_project->project_name, 20) }}
                                                                @else
                                                                 {{ \Illuminate\Support\Str::limit($project_name, 20) }}
                                                                @endif


                                                            </td>
                                                            <td style="text-align:left" title="{{ $plot_no }}">

                                                                 @if ($item->job_project)
                                                                 {{ \Illuminate\Support\Str::limit($item->job_project->new_project->plot, 20) }}
                                                                @else
                                                                 {{ \Illuminate\Support\Str::limit($plot_no, 20) }}
                                                                @endif


                                                            </td>
                                                            <td style="text-align:left" title="{{ $location }}">

                                                                @if ($item->job_project)
                                                                 {{ \Illuminate\Support\Str::limit($item->job_project->new_project->location, 20) }}
                                                                @else
                                                                 {{ \Illuminate\Support\Str::limit($location, 20) }}
                                                                @endif


                                                            </td>
                                                            {{-- <td>{{$item->narration}}</td> --}}
                                                            <td>{{ number_format($item->total_amount, 2) }} </td>
                                                            <td style="min-width: fit-content"> <span
                                                                    class="bg-warning text-white" style="padding: 2px 3px;">
                                                                    Awaiting Approve </span> </td>
                                                            <td>{{ $item->pay_mode }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tbody id="receipt-body-hide">
                                                    @foreach ($receipt_list as $item)
                                                        @php
                                                            $project_name = '';
                                                            $plot_no = '';
                                                            $location = '';
                                                            foreach ($item->items as $receipt_sale) {
                                                                $invoice = $receipt_sale->invoice;
                                                                $project = $invoice->project;
                                                                $new_project = $project ? $project->new_project : null;
                                                                if ($new_project) {
                                                                    $project_name .= $new_project->name . ' ,';
                                                                    $plot_no .= $new_project->plot . ' ,';
                                                                    $location = $new_project->location . ', ';
                                                                }
                                                            }
                                                        @endphp
                                                        <tr class="receipt_exp_view" id="{{ $item->id }}">
                                                            <td>{{ ++$i }}</td>
                                                            <td>{{ date('d/m/Y', strtotime($item->date)) }}</td>

                                                            <td>{{ $item->receipt_no }}</td>
                                                            <td style="text-align: left !important;"
                                                                title="{{ optional($item->party)->pi_name }}">
                                                                {{ \Illuminate\Support\Str::limit(optional($item->party)->pi_name, 30) }}
                                                            </td>
                                                            <td style="text-align:left" title="{{ $project_name }}">
                                                                @if ($item->job_project)
                                                                 {{ \Illuminate\Support\Str::limit($item->job_project->project_name, 20) }}
                                                                @else
                                                                 {{ \Illuminate\Support\Str::limit($project_name, 20) }}
                                                                @endif

                                                            </td>
                                                            <td style="text-align:left" title="{{ $plot_no }}">
                                                                 @if ($item->job_project)
                                                                 {{ \Illuminate\Support\Str::limit($item->job_project->new_project->plot, 20) }}
                                                                @else
                                                                 {{ \Illuminate\Support\Str::limit($plot_no, 20) }}
                                                                @endif
                                                            </td>
                                                            <td style="text-align:left" title="{{ $location }}">
                                                                 @if ($item->job_project)
                                                                 {{ \Illuminate\Support\Str::limit($item->job_project->new_project->location, 20) }}
                                                                @else
                                                                 {{ \Illuminate\Support\Str::limit($location, 20) }}
                                                                @endif
                                                            </td>

                                                            {{-- <td>{{$item->narration}}</td> --}}
                                                            <td>{{ number_format($item->total_amount, 2) }}</td>
                                                            <td style="min-width: fit-content"> <span
                                                                    class="bg-success text-white"
                                                                    style="padding: 2px 3px;">
                                                                    Approved </span> </td>
                                                            <td>{{ $item->pay_mode }}</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr style="background: #394c62 !important; color: #fff !important;">
                                                        <td colspan="7" class="text-right " style=" color: #fff !important">Total</td>
                                                        <td colspan="" style=" color: #fff !important">{{ number_format($temp_receipt_list->sum('total_amount')+$receipt_list->sum('total_amount'), 2) }}</td>
                                                        <td colspan="2"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="mt-1">
                                            {{ $receipt_list->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal --}}

    <div class="modal fade" id="receiptModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="padding:5px 10px;background:#364a60;">
                    <h5 class="modal-title" id="exampleModalLabel"
                        style="font-family:Cambria;font-size: 2rem;color:white;margin-left: 10px;">Receipt Voucher</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" style="padding: 0;">
                    <form action="{{ route('temp-receipt-voucher-post-inv') }}" method="POST" id="formSubmit"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="cardStyleChange bg-white">
                            <div class="card-body pb-1">
                                <div class="row mx-1 mt-1 d-flex justify-content-center">
                                    <div class="col-md-2 date-div  mb-0 pb-0" id="printarea">
                                        <div class="form-group">
                                            <label for="">Date</label>
                                            <input type="text" value="{{ date('d/m/Y') }}"
                                                class="form-control inputFieldHeight datepicker date" name="date"
                                                placeholder="dd-mm-yyyy" id="date">
                                            @error('date')
                                                <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col-md-2 changeColStyle  mb-0 pb-0">
                                        <label for="">Type</label>
                                        <select name="voucher_type" id="voucher_type" class="common-select2 voucher_type"
                                            style="width: 100% !important" required>
                                            <option value="due" selected>Due Payment</option>
                                            <option value="advance">Advance Payment</option>
                                        </select>
                                    </div>


                                    <div class="col-md-2 changeColStyle  mb-0 pb-0 search-item-pi">
                                        <div class="form-group">
                                            <label for="">Party Name </label>
                                            <select name="party_info" id="party_info" class="common-select2 party_info"
                                                style="width: 100% !important" data-target="" required>
                                                <option value="">Select...</option>
                                                @foreach ($parties as $item)
                                                    <option value="{{ $item->id }}">{{ $item->pi_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('party_info')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                            <small id="available_balance" class="text-danger available_balance"></small>
                                        </div>
                                    </div>


                                    <div class="col-md-2 changeColStyle  mb-0 pb-0 project_div d-none">
                                        <label for="">Project</label>
                                        <select name="project" id="project" class="common-select2 project"
                                            style="width: 100% !important">
                                            <option value="">Select...</option>

                                        </select>
                                        <small id="project_available_balance"
                                            class="text-danger project_available_balance"></small>
                                    </div>



                                    <div class="col-md-2 changeColStyle code-div  mb-0 pb-0">
                                        <div class="form-group">
                                            <label for=""> Party Code </label>
                                            <input type="text" name="pi_code" id="pi_code"
                                                class="form-control inputFieldHeight pi_code" required
                                                placeholder="Party Code">
                                            @error('party_info')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2 changeColStyle  mb-0 pb-0 d-none">
                                        <div class="form-group">
                                            <label for="">
                                                @if (!empty($currency->licence_name))
                                                    {{ $currency->licence_name }}
                                                @endif
                                            </label>
                                            <input type="text" class="form-control inputFieldHeight"
                                                value="{{ isset($journalF) ? $journalF->partyInfo->trn_no : '' }}"
                                                name="trn_no" id="trn_no" class="form-control trn_no" readonly>
                                            @error('trn_no')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2 changeColStyle  mb-0 pb-0">
                                        <div class="form-group">
                                            <label for=""> Payment Mode </label>
                                            <select name="pay_mode" id="pay_mode" class="form-control inputFieldHeight"
                                                required>
                                                <option value="">Select...</option>

                                                @foreach ($modes as $item)
                                                    <option value="{{ $item->title }}"
                                                        {{ isset($journalF) ? ($journalF->txn_mode == $item->title ? 'selected' : '') : '' }}>
                                                        {{ $item->title }} </option>
                                                @endforeach

                                            </select>
                                            @error('pay_mode')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2" id="bank_name">
                                        <label for="">Bank Name</label>
                                        <select name="bank_id" id="bank_id"
                                            class="form-control inputFieldHeight bank_id" disabled>
                                            <option value="">Select...</option>
                                            @foreach ($bank_name as $item)
                                                <option value="{{ $item->id }}"> {{ $item->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 cheque-content" style="display: none">
                                        <div class="row">
                                            <div class="col-md-3 changeColStyle  mb-0 pb-0">
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

                                            <div class="col-md-3 changeColStyle  mb-0 pb-0">
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

                                            <div class="col-md-3 changeColStyle  mb-0 pb-0">
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

                                            <div class="col-md-3 changeColStyle  mb-0 pb-0">
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

                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="cardStyleChange" style="width: 100%">
                                                <div class="card-body bg-white table_part">


                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-md-2 changeColStyle" id="printarea">
                                        <label for=""> Upload File</label>
                                        <input
                                            class="form-control inputFieldHeight  @error('voucher_file') is-invalid @enderror"
                                            type="file" name="voucher_file" style="height: 32px !important"
                                            accept="application/pdf,image/png,image/jpeg,application/msword">
                                    </div>

                                    <div class="col-md-6 changeColStyle narration_div" id="">
                                        <div class="form-group">
                                            <label for="">Narration</label>

                                            <input type="text" class="form-control inputFieldHeight" name="narration"
                                                id="narration" placeholder="Narration"
                                                value="{{ isset($journalF) ? $journalF->narration : '' }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-2 changeColStyle due_amount_div" id="">
                                        <div class="form-group">
                                            <label for="">Due Amount</label>
                                            <input type="number" step="any"
                                                class="form-control inputFieldHeight due_amount" name="due_amount"
                                                id="due_amount" placeholder="Due Amount" value="" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-2 changeColStyle d-none" id="printarea">
                                        <div class="form-group">
                                            <label for=""> Discount Amount</label>
                                            <input type="nmber" step="any"
                                                class="form-control inputFieldHeight discount_amount"
                                                name="discount_amount" readonly id="discount_amount"
                                                placeholder="Pay Amount">
                                        </div>
                                    </div>

                                    <div class="col-md-2 changeColStyle" id="printarea">
                                        <div class="form-group">
                                            <label for="">Pay Amount</label>

                                            <input type="number" step="any"
                                                class="form-control inputFieldHeight pay_amount" name="pay_amount"
                                                id="pay_amount" placeholder="Pay Amount" value="" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 d-flex justify-content-center " style="">
                                        <button type="submit" class="btn btn-primary formButton " id="submitButton">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}"
                                                        alt="" srcset="" width="25">
                                                </div>
                                                <div><span>Save</span></div>
                                            </div>
                                        </button>
                                        <a onclick="window.location.reload()" class="btn btn-warning d-none "
                                            id="newButton">New</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- END: Content-->
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
                    <form action="{{ route('receipt-excel-import') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="file" required class="form-controll" name="excel_file"
                            accept=".xlsx, .xls, .csv">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
      @if (session('message_import'))
        <script>
            const rawHtml = `{!! session('message_import') !!}`;

            Swal.fire({
                icon: '{{ session('alert-type') ?? 'success' }}',
                title: 'BOQ Import Result',
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
                        .trim()
                    ]);

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
                    XLSX.writeFile(wb, 'boq_skipped_rows.xlsx');
                });
            }, 100);
        </script>
    @endif
    <script>
        function exportToExcel() {
            var table = document.getElementById("receipt-list");
            var wb = XLSX.utils.table_to_book(table, {
                sheet: "receipt-list"
            });
            XLSX.writeFile(wb, "receipt-list.xlsx");
        }

        $(document).on("click", ".receipt_exp_view", function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ URL('receipt-list-modal') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                    $(".datepicker").datepicker({
                        dateFormat: "dd/mm/yy"
                    });
                }
            });
        });


        $('#search').keyup(function() {
            if ($(this).val() != '') {
                var value = $(this).val();
                var party = $('#party_search').val();
                var company_id = $('#compnay_id_search').val();
                var date = $('#date_search').val();
                var mode = $('#mode_search').val();

                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-receipt') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date: date,
                        mode: mode,
                        company_id: company_id,
                        _token: _token,
                    },
                    success: function(response) {
                        $("#receipt-body").empty().append(response);
                        $('#receipt-body-hide').hide();
                    }
                })
            }
        });

        $('#party_search').change(function() {
            if ($(this).val() != '') {
                var party = $(this).val();
                var value = $('#search').val();
                var company_id = $('#compnay_id_search').val();
                var date = $('#date_search').val();
                var mode = $('#mode_search').val();

                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-receipt') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date: date,
                        mode: mode,
                        company_id: company_id,
                        _token: _token,
                    },
                    success: function(response) {
                        $("#receipt-body").empty().append(response);
                        $('#receipt-body-hide').hide();
                    }
                })
            }
        });

        $('#compnay_id_search').change(function() {
            if ($(this).val() != '') {
                var company_id = $(this).val();
                var value = $('#search').val();
                var party = $('#party_search').val();
                var company_id = $('#compnay_id_search').val();
                var date = $('#date_search').val();
                var mode = $('#mode_search').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-receipt') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date: date,
                        mode: mode,
                        company_id: company_id,
                        _token: _token,
                    },
                    success: function(response) {
                        $("#receipt-body").empty().append(response);
                        $('#receipt-body-hide').hide();
                    }
                })
            }
        });

        $('#date_search').change(function() {
            if ($(this).val() != '') {
                var date = $(this).val();
                var value = $('#search').val();
                var party = $('#party_search').val();
                var company_id = $('#compnay_id_search').val();
                var mode = $('#mode_search').val();

                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-receipt') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date: date,
                        mode: mode,
                        company_id: company_id,
                        _token: _token,
                    },
                    success: function(response) {
                        $("#receipt-body").empty().append(response);
                        $('#receipt-body-hide').hide();
                    }
                })
            }
        });

        $('#mode_search').change(function() {
            if ($(this).val() != '') {
                var party = $('#party_search').val();
                var value = $('#search').val();
                var date = $('#date_search').val();
                var company_id = $('#compnay_id_search').val();
                var mode = $(this).val();

                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-receipt') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date: date,
                        mode: mode,
                        company_id: company_id,
                        _token: _token,
                    },
                    success: function(response) {
                        $("#receipt-body").empty().append(response);
                        $('#receipt-body-hide').hide();
                    }
                })
            }
        });

        $(document).on('change', '.voucher_type', function(e) {
            var form = $(this).closest('form');
            form.find(".table-part").empty();
            form.find(".due_amount").val('');
            form.find('.discount_amount').val('');
            form.find('.pay_amount').val('');
            var type = $(this).val();
            if (type == 'advance') {
                form.find(".due_amount_div").addClass('d-none');
                form.find(".table_part").addClass('d-none');
                form.find(".narration_div").removeClass('col-md-6');
                form.find(".narration_div").addClass('col-md-8');

                     form.find(".project_div").removeClass('d-none');
                    form.find(".date-div").removeClass('col-md-2');
                    form.find(".date-div").addClass('col-md-1');
                    form.find(".code-div").removeClass('col-md-2');
                    form.find(".code-div").addClass('col-md-1');


            } else {
                form.find(".table_part").removeClass('d-none');
                form.find(".due_amount_div").removeClass('d-none');
                form.find(".narration_div").removeClass('col-md-8');
                form.find(".narration_div").addClass('col-md-6');
                if ($('#pay_mode').val() != 'Advance')
                {
                     form.find(".project_div").addClass('d-none');
                    form.find(".date-div").addClass('col-md-2');
                    form.find(".date-div").removeClass('col-md-1');
                    form.find(".code-div").addClass('col-md-2');
                    form.find(".code-div").removeClass('col-md-1');
                }

            }
        })

        $(document).on('change', '#pay_mode', function() {
            var form = $(this).closest('form');
            if ($(this).val() == 'Cheque') {
                form.find(".deposit_date").attr('required', true);
                form.find(".bank_branch").attr('required', true);;
                form.find(".issuing_bank").attr('required', true);
                form.find(".cheque_no").attr('required', true);
                form.find('.cheque-content').show();
                if ($('.voucher_type').val() != 'advance') {
                    form.find(".project_div").addClass('d-none');
                    form.find(".date-div").removeClass('col-md-1');
                    form.find(".date-div").addClass('col-md-2');
                    form.find(".code-div").removeClass('col-md-1');
                    form.find(".code-div").addClass('col-md-2');
                }
            } else if ($(this).val() == 'Advance') {
                form.find(".date-div").removeClass('col-md-2');
                form.find(".date-div").addClass('col-md-1');
                form.find(".code-div").removeClass('col-md-2');
                form.find(".code-div").addClass('col-md-1');
                form.find(".project_div").removeClass('d-none');
            } else {
                form.find(".deposit_date").removeAttr('required');
                form.find(".bank_branch").removeAttr('required');;
                form.find(".issuing_bank").removeAttr('required');
                form.find(".cheque_no").removeAttr('required');
                form.find('.cheque-content').hide();
                if ($('.voucher_type').val() != 'advance') {
                    form.find(".project_div").addClass('d-none');
                    form.find(".date-div").removeClass('col-md-1');
                    form.find(".date-div").addClass('col-md-2');
                    form.find(".code-div").removeClass('col-md-1');
                    form.find(".code-div").addClass('col-md-2');
                }

            }

        });
        $(document).on('change', '#pay_mode', function(e) {
            var to_account = $(this).val();
            if (to_account == 'Bank') {
                $('.bank_id').attr('required', true);
                $('.bank_id').attr('disabled', false);
            } else {
                $('.bank_id').val(null).trigger('change');
                $('.bank_id').attr('required', false);
                $('.bank_id').attr('disabled', true);
            }
        })
        $(document).on('change', '#project', function(e) {
            var form = $(this).closest('form');
            var project = form.find('#project').val();
            var date = form.find('.date').val();
            if (project != '') {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('projectReceipt') }}",
                    method: "POST",
                    data: {
                        date: date,
                        project: project,
                        _token: _token,
                    },
                    success: function(response) {

                        var due = response.due.toFixed(2);

                        form.find(".project_available_balance").html('Available Balance ' + response
                            .project
                            .advance_amount);
                        form.find(".available_balance").empty();

                        form.find(".due_amount").val(due);
                        form.find('.due_amount').data('due', due);
                        form.find(".pay_amount").attr({
                            "max": due, // substitute your own
                            "min": 1 // values (or variables) here
                        });
                        form.find(".table_part").empty().append(response.page);

                    }
                })
            }
        });
        $(document).on('change', '.party_info, .date, .pi_code', function(e) {
            var form = $(this).closest('form');
            var voucher_type = form.find('.voucher_type').val();
            var date = form.find('.date').val();
            var party_id = form.find('.party_info').val();

            if (party_id != '') {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('partyInfosale2R') }}",
                    method: "POST",
                    data: {
                        date: date,
                        party_id: party_id,
                        _token: _token,
                    },
                    success: function(response) {

                        var due = response.due.toFixed(2);
                        form.find(".trn_no").val(response.info.trn_no);
                        form.find(".pi_code").val(response.info.pi_code);
                        form.find(".available_balance").html('Available Balance ' + response.info
                            .balance);
                        form.find(".project_available_balance").empty();

                        let $projectSelect = $("#project");
                        $projectSelect.empty(); // clear old options

                        $projectSelect.append(
                            '<option value="">-- Select Project --</option>'); // default option

                        $.each(response.projects, function(index, project) {
                            $projectSelect.append('<option value="' + project.id + '">' +
                                project.project_name + '</option>');
                        });

                        form.find(".due_amount").val(due);
                        form.find('.due_amount').data('due', due);
                        form.find(".pay_amount").attr({
                            "max": due, // substitute your own
                            "min": 1 // values (or variables) here
                        });
                        form.find(".table_part").empty().append(response.page);

                    }
                })
            }
        });

        $(document).on('submit', '#formSubmit , #editReceiptForm', function(e) {
            e.preventDefault();

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
                        $("#submitButton").prop("disabled", true)
                        $(".deleteBtn").prop("disabled", true)
                        $(".addBtn").prop("disabled", true)
                        document.getElementById("voucherPreviewShow").innerHTML = response.preview;
                        $('#voucherPreviewModal').modal('show');
                        $("#newButton").removeClass("d-none")
                        $("#submitButton").addClass("d-none")
                        $('#receipt-body').html(response.list);
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


        $(document).on("click", ".receipt_view", function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ URL('temp-receipt-voucher-preview') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                    $(".datepicker").datepicker({
                        dateFormat: "dd/mm/yy"
                    });
                }
            });
        });

        $(document).on('click', '.edit-btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: "get",
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                    $(".datepicker").datepicker({
                        dateFormat: "dd/mm/yy"
                    });

                    $('.common-select2').select2();
                }
            });
        })

        $(document).on('click', '.btn-select-all', function(event) {
            if (this.checked) {
                // Iterate each checkbox
                $(':checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $(':checkbox').each(function() {
                    this.checked = false;
                });
            }
        });
    </script>
    <script>
        function togglePayment() {
            const paymentPart = document.querySelector('#payment-part');
            if (paymentPart.classList.contains('d-none')) {
                paymentPart.classList.remove('d-none');
            } else {
                paymentPart.classList.add('d-none');
            }
        }
    </script>
@endpush

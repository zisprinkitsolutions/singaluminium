<style>
    .customer-static-content {
        background: #ada8a81c;
    }

    .customer-dynamic-content {
        background: #706f6f33;
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
       .table td {
            vertical-align: middle;
            border-bottom: 1px solid #DFE3E7;
            border-top: none;
            border-left: none;
            border-right: none;
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

    /* Hover effect */
    .proview-table tbody tr:hover {
        background-color: #f2f2f2;
        cursor: pointer;
    }

    /* Status colors based on progress */
    .progress-low {
        background-color: rgba(249, 0, 0, 0.822);
        color: #fff;
        /* light red */
    }

    .progress-mid {
        background-color: rgba(255, 166, 0, 0.979);
        color: #fff;
        /* light orange */
    }

    .progress-high {
        background-color: rgba(28, 255, 28, 0.89);
        color: #fff;
        /* light green */
    }

    /* Cut text but show full on hover */
    .truncate-text {
        max-width: 120px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    th {
        background: #364a60 !important;
        color: #fff !important;
    }

    .proview-table tbody tr {
        height: 25px;
    }
    .table td
    {
       color: #4a4a4a;
        border-left: none;
        border-right: none;
    }
    .data-table .table tr th {
        padding: 0px 0px;
    }
    /* @media print{
        .print-hideen{
            display: none !important;
        }
    } */
</style>

{{-- @php
$whole = floor($purchase_exp->total_amount);
$fraction = number_format($purchase_exp->total_amount - $whole, 2);
$f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
$amount_in_word = $f->format($whole);
$amount_in_word2 = $f->format((int) ($fraction * 100));
@endphp --}}

<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse">

        <div class="pr-1" style="padding-top: 5px;padding-right: 24px !important;">
            <a href="#" class="btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true"><i class='bx bx-x'></i></span></a>
        </div>

        {{-- <div class="pr-1" style="padding-top: 5px;padding-right: 10px !important;">
            <a href="#" onclick="window.print();" class="btn btn-icon btn-success"><i class="bx bx-printer"></i></a>
        </div> --}}

        <div class="pr-1 w-100 pl-2">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white; text-align:left;"> Accrued Receivable </h4>
        </div>
    </div>
</section>

<div class="receipt-voucher-hearder invoice-view-wrapper" style="margin: 50px 20px; border-radius: 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>

<section id="widgets-Statistics" style="padding: 15px 22px;">
    <div class="row">

        <div class="col-md-12 text-center invoice-view-wrapper student_profle-print">

            <h2 class="text-left"> Accrued Receivable </h2>
        </div>

        <div class="col-12">
            <div class="table-responsive">
                {{-- <form class="search_home_reports" data="sales/sale-list-ajax">
                    <div class="form-row align-items-center">
                        <div style="padding-left:10px;">
                            <input type="text" name="date_from" id="date_from"
                                class="form-control mb-2  datepicker date_from_data-details" autocomplete="off"
                                placeholder="01/01/2025">
                        </div>
                        <div style="padding-left:10px;">
                            <input type="text" name="date_to" id="date_to"
                                class="form-control mb-2  datepicker date_to_data-details" autocomplete="off"
                                placeholder="01/12/2025">
                        </div>
                        <div class="col-auto">
                            {{-- <button type="button"
                                class="btn btn-primary mb-2 search_home_reports_btn">Submit</button> --
                            <button type="button" class="btn custom-search mb-2 formButton data-details" title="Search"
                                data-target="accrued_receivable" style="margin-left:8px;">
                                <div class="d-flex">
                                    <div class="formSaveIcon">
                                        <img src="{{ asset('/') }}assets/backend/app-assets/icon/searching-icon.png"
                                            width="25">
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </form> --}}

                <div class="data-table table-responsive ">
                {{-- <table class="table table-sm table-bordered border-botton proview-table" data-table="accrued_receivable" --}}
                <table class="table table-sm " data-table="accrued_receivable"
                    style="color: black;">
                    <thead style="background: #706f6f33 !important; color: black;">
                        <tr style="background: #364a60 !important;">
                            <th style="text-align: left;">Assigned To</th>
                            <th style="text-align: left;">Owner / Party</th>
                            <th style="text-align: left;" >Project Name</th>
                            <th style="text-align: left;">Project No</th>
                            <th style="text-align: left;">Plot</th>
                            <th style="text-align: left;">Location</th>
                            {{-- <th>Status</th> --}}
                            {{-- <th>Received</th>
                            <th>Receivable</th> --}}
                            <th>Contract Value  <br> {{ number_format($totals->grand_total_budget, 2) }}</th>
                            <th>Invoice Amount <br> {{ number_format($totals->grand_total_received,2)}}</th>
                            <th>Accrued Receivable <br> {{ number_format($totals->grand_accrued_receivable,2)}}</th>
                            {{-- <th>Retention</th> --}}
                        </tr>
                    </thead>
                    <tbody class="user-table-body">
                        @php
                            $t_accrued=0;
                        @endphp
                        @forelse ($ongoing_projects as $project)
                        @php
                        $invices = $project->invoices;
                        $total_paid_amount = $invices->sum('paid_amount');
                        $invoice_amount = $invices->sum('budget');
                        // Decide row class based on avarage_complete %
                        if ($project['avarage_complete'] <= 25) { $rowClass='progress-low' ; } elseif
                            ($project['avarage_complete'] <=50) { $rowClass='progress-mid' ; } else {
                            $rowClass='progress-high' ; } @endphp
                            <tr>
                            <td class="truncate-text" style="text-align: left;"
                                title="{{ $project->company->company_name ?? 'SEA BRIDGE BUILDING CONT. LLC' }}">
                                {{ Str::words($project->company->company_name ?? 'SEA BRIDGE BUILDING CONT. LLC', 2,
                                '...') }}
                            </td>
                            <td style="text-align: left;" class="truncate-text" title="{{ $project->prospect->name }}">
                                {{ Str::words($project->prospect->name, 2, '...') }}
                            </td>
                            <td class="truncate-text" style="text-align: left;"
                                title="{{ $project->prospect->party ? $project->prospect->party->pi_name : '' }}">
                                {{ Str::words($project->prospect->party ? $project->prospect->party->pi_name : '', 2,
                                '...') }}
                            </td>
                            <td style="text-align: left;">{{ $project->prospect->project_no }}</td>
                            <td style="text-align: left;">{{ $project->prospect->plot }}</td>
                            <td style="text-align: left;" >{{ $project->prospect->location }}</td>
                            {{-- <td class="{{ $rowClass }}">{{ $project['avarage_complete'] }}%</td>
                            <td>{{ number_format($total_paid_amount, 2) }}</td>--}}
                            <td>{{ number_format($project->budget, 2) }}</td>
                            <td>{{ number_format($invoice_amount, 2) }}</td>
                            <td>{{ number_format(max($project->budget-$invoice_amount, 0), 2) }}</td>
                            {{-- <td>{{ number_format($project->retention_amount, 2) }}</td> --}}
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center">No ongoing projects found</td>
                            </tr>
                            @endforelse
                          {{-- <tr style=" background-color: #3d4a94 !important;">
                                <td colspan="7" style="text-align: right ; margin-right:5px;">Total</td>
                                <td>{{ number_format($totals->grand_total_budget, 2) }}</td>
                                <td>{{ number_format($totals->grand_accrued_receivable, 2) }}</td>
                            </tr> --}}
                    </tbody>
                </table>
                {{$ongoing_projects->links()}}
                </div>
            </div>
        </div>
    </div>

</section>

<div class="d-flex flex-row-reverse justify-content-center mb-2">
    <div class="print-hideen">
        <a href="#" onclick="window.print();" class="btn btn-icon btn-secondary custom-action-btn" title="Print Now">
            <i class="bx bx-printer"></i> Print
        </a>
    </div>
</div>

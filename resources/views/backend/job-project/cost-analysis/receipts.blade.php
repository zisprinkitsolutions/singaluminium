

<style>
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

    .proview-table th{
        background:#706f6f33 !important;
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
            <a href="#" class="btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a>
        </div>

        {{-- <div class="pr-1" style="padding-top: 5px;padding-right: 10px !important;">
            <a href="#" onclick="window.print();" class="btn btn-icon btn-success"><i class="bx bx-printer"></i></a>
        </div> --}}

        <div class="pr-1 w-100 pl-2">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">  {{ucwords(str_replace('_', ' ', $type))}}  </h4>
        </div>
    </div>
</section>

<div class="receipt-voucher-hearder invoice-view-wrapper" style="margin: 50px 20px; border-radius: 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>

<section id="widgets-Statistics" style="padding: 15px 22px;">
    <div class="row">

        <div class="col-md-12 text-center invoice-view-wrapper student_profle-print">
            @if($to_date && !$from_date)
            <h2> Project Invoices  Date:{{date('d/m/Y',strtotime($to_date))}} </h2>
            @elseif($from_date && !$to_date)
            <h2> Project Invoices Date:{{date('d/m/Y',strtotime($from_date))}} </h2>
            @elseif ($from_date && $to_date)
            <h2> Project Invoices From: {{date('d/m/Y',strtotime($from_date))}} To: {{date('d/m/Y',strtotime($to_date))}} </h2>
            @else
            <h2> {{ucwords(str_replace('_', ' ', $type))}} </h2>
            @endif
        </div>

        <div class="col-sm-12">
            <div class="customer-info">
                <table class="table table-sm table-bordered" style="color: #1d1d1d !important;">
                    <tr>
                        <td class="text-left w-75" >
                            <strong style="display: inline-block; width: 100px;"> Project </strong>
                            <span style="display: inline-block; width: 10px;">:</span>
                            <strong>{{optional($project->new_project)->name }}</strong>
                        </td>
                        <td class="text-left">
                            <strong style="display: inline-block; width: 100px;"> Plot No </strong>
                            <span style="display: inline-block; width: 10px;">:</span>
                            {{ optional($project->new_project)->plot}}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            <strong style="display: inline-block; width: 100px;"> Owner </strong>
                            <span style="display: inline-block; width: 10px;">:</span>
                            {{ optional($project->new_project)->party ? optional($project->new_project)->party->pi_name : '' }}
                        </td>
                        <td class="text-left">
                            <strong style="display: inline-block; width: 100px;"> Location </strong>
                            <span style="display: inline-block; width: 10px;">:</span>
                            {{ optional($project->new_project)->location}}
                        </td>
                    </tr>
                    <tr>
                        @php
                            $contract_amount = optional($project->new_project)->total_contract ?? 0;
                            $paid_amount = $invoices->sum('paid_amount') ?? 0;
                            $due_amount = $invoices->sum('due_amount') ?? 0;
                            $accrued_receivable = $contract_amount - $paid_amount;
                            $total_receivable = $due_amount + $accrued_receivable;
                        @endphp
                        <td class="text-left">
                            <strong style="display: inline-block; width: 100px;"> Contrat:</strong>
                            <span style="display: inline-block; width: 10px;">:</span>
                            {{  number_format($contract_amount,2)}} AED
                        </td>
                        @if($type == 'accrued_receivable:')
                        <td class="text-left">
                            <strong style="display: inline-block; width: 100px;"> {{ucwords(str_replace('_', ' ', $type))}}:  </strong>
                            <span style="display: inline-block; width: 10px;">:</span>
                            {{ number_format( $paid_amount,2) }} AED
                        </td>
                        @else
                        <td class="text-left">
                            <strong style="display: inline-block; width: 100px;"> {{ucwords(str_replace('_', ' ', $type))}}: </strong>
                            <span style="display: inline-block; width: 10px;">:</span>
                            {{ number_format($accrued_receivable,2) }} AED
                        </td>
                        @endif
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-sm table-bordered border-botton proview-table" style="color: black; ">
                    <thead style="background: #706f6f33 !important;color: black;">
                        <tr>
                            <th class="text-center"  style="text-transform: uppercase; color: black !important;"> Date </th>
                            <th class="text-center"  style="text-transform: uppercase; color: black !important;"> Invoice No </th>
                            <th class="text-center"  style="text-transform: uppercase; color: black !important;"> Receipt No </th>
                            <th class="text-center"  style="text-transform: uppercase; color: black !important;"> Paymode  </th>
                            <th class="text-right" style="text-transform: uppercase; color: black !important;"> Invoice Issued </th>
                            <th class="text-right" style="text-transform: uppercase; color: black !important;"> Receipt </th>
                            <th class="text-right"  style="text-transform: uppercase; color: black !important;"> Receivable </th>
                        </tr>
                    </thead>
                    <tbody class="user-table-body">
                        @foreach ($invoices as $invoice)
                        @foreach($invoice->receipt_lists as $item)
                        @php
                            $invoice_amount = $invoice->total_budget - $invoice->retention_amount
                        @endphp
                        <tr class="receipt_exp_view"  id="{{$item->id}}">
                            <td class="text-center"> {{$item->date ? date('d/m/Y', strtotime($item->date)) : ''}} </td>
                            <td class="text-center"> {{ $invoice->invoice_no }} </td>
                            <td class="text-center"> {{ $item->receipt_no }} </td>
                            <td class="text-center"> {{ $item->pay_mode }} </td>
                            <td class="text-right"> {{number_format($invoice->total_budget,2)}} </td>
                            <td class="text-right"> {{number_format($item->total_amount,2)}} </td>
                            <td class="text-right"> {{number_format($invoice_amount - $item->total_amount,2)}} </td>
                        </tr>
                        @endforeach
                        @endforeach
                        <tr>
                            <td colspan="6" class="text-right"> Total Receipt </td>
                            <td class="text-right">{{number_format($invoices->sum('paid_amount'),2)}}</td>
                        </tr>
                         <tr>
                            <td colspan="6" class="text-right"> Recivable </td>
                            <td class="text-right">{{number_format($invoices->sum('due_amount'),2)}}</td>
                        </tr>
                         <tr>
                            <td colspan="6" class="text-right"> Accrued Receivable </td>
                            <td class="text-right">{{number_format($accrued_receivable,2)}}</td>
                        </tr>
                         <tr>
                            <td colspan="6" class="text-right"> Total Receivable </td>
                            <td class="text-right">{{number_format($total_receivable,2)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="d-flex flex-row-reverse justify-content-center align-item-center">
        <div class="print-hideen" >
            <a href="#" onclick="window.print();" class="btn btn-icon btn-secondary custom-action-btn" title="Print Now">
                <i class="bx bx-printer"></i> Print
            </a>
        </div>
    </div>
</section>





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
            <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a>
        </div>

        {{-- <div class="pr-1" style="padding-top: 5px;padding-right: 10px !important;">
            <a href="#" onclick="window.print();" class="btn btn-icon btn-success"><i class="bx bx-printer"></i></a>
        </div> --}}

        <div class="pr-1 w-100 pl-2">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;"> Project Invoices  </h4>
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
            <h2> Project Invoices </h2>
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
                            {{ optional($project->customer)->name}}
                        </td>
                        <td class="text-left">
                            <strong style="display: inline-block; width: 100px;"> Location </strong>
                            <span style="display: inline-block; width: 10px;">:</span>
                            {{ optional($project->new_project)->location}}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            <strong style="display: inline-block; width: 100px;"> Total Contruct </strong>
                            <span style="display: inline-block; width: 10px;">:</span>
                            {{ number_format(optional($project->new_project)->total_contract,2)}} AED
                        </td>
                        <td class="text-left">
                            <strong style="display: inline-block; width: 100px;"> Invoices: ({{$type}})  </strong>
                            <span style="display: inline-block; width: 10px;">:</span>
                            {{ number_format($invoices->sum('total_budget'),2) }} AED
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
                            <th class="text-left"  style="text-transform: uppercase; color: black !important;"> Date </th>
                            <th class="text-left"  style="text-transform: uppercase; color: black !important;"> Invoice No </th>
                            <th  style="text-transform: uppercase; color: black !important;"> Customer </th>
                            <th class="text-right"  style="text-transform: uppercase; color: black !important;"> Total include (VAT) </th>
                            <th class="text-right"  style="text-transform: uppercase; color: black !important;"> Retention Amount </th>
                            <th class="text-right"  style="text-transform: uppercase; color: black !important;"> Received </th>
                            <th class="text-right"  style="width:150px; text-transform: uppercase; color: black !important;"> Receivable </th>
                        </tr>
                    </thead>
                    <tbody class="user-table-body">
                        @foreach ($invoices as $item)
                        <tr class="sale_view"  id="{{$item->id}}">
                            <td class="text-center"> {{$item->date ? date('d/m/Y', strtotime($item->date)) : ''}} </td>
                            <td class="text-center"> {{ $item->invoice_no }} </td>
                            <td> {{optional($item->party)->pi_name}} </td>
                            <td class="text-right">{{number_format($item->total_budget - $item->retention_amount,2)}}</td>
                            <td class="text-right">{{number_format($item->retention_amount,2)}}</td>
                            <td class="text-right">{{number_format($item->paid_amount,2)}}</td>
                            <td class="text-right">{{number_format($item->due_amount,2)}}</td>
                        </tr>

                        @endforeach
                        <tr>
                            <td colspan="3" class="text-right"> Total </td>
                            <td class="text-right"> {{number_format($invoices->sum('total_budget') - $invoices->sum('retention_amount'),2)}} </td>
                            <td class="text-right"> {{number_format($invoices->sum('retention_amount',2))}} </td>
                            <td class="text-right">{{number_format($invoices->sum('paid_amount'),2)}}</td>
                            <td class="text-right">{{number_format($invoices->sum('due_amount'),2)}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="d-flex flex-row-reverse justify-content-center align-items-center">
        <div class="print-hideen" >
            <a href="#" onclick="window.print();" class="btn btn-icon btn-secondary custom-action-btn" title="Print Now">
                <i class="bx bx-printer"></i> Print
            </a>
        </div>
    </div>
</section>



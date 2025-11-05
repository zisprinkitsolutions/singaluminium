@extends('layouts.print_app')

@section('content')

<style>
    .report-header{
        background: #F5F7F8;
        padding:20px;
        border: 1px solid #ddd;
    }
    .right-side .report-title, .left-side .report-title{
        font-weight:400;
        font-size:15px;
        color: #333;
    }

    .report-title span.bold{
        font-weight: 500;
    }

    .report-title-lg{
        font-size: 18px;
        font-weight:500;
        color: #333;
    }
    p.report-title{
        font-weight:500;
        font-size:15px;
        color: #333;
        line-height: 25px;
        margin: 0;
    }

    .report-title small{
        color: #333;
        font-size: 10px;
    }

    .report-details .text-left{
        font-weight:500;
        font-size:17px;
        color: #333;
    }
    .report-details .text-left small{
        color: #333;
        font-size: 10px;
    }

    .report-table .table
    {
        border:1px solid rgb(40, 133, 196);
        width: 100%
    }
    .report-table .table th,
    .report-table .table td{
        border-bottom: 1px solid rgb(40, 133, 196);
    }
    .report-table  .table thead tr{
        background:rgb(40, 133, 196) !important;
    }
    .report-table .table thead th{
        color: #fff;
        text-transform: uppercase !important;
        font-weight: 500 !important;
        font-size: 12px;
        padding: 5px 10px;
    }
    .report-table  .table tbody td{
        color: #333;
        padding: 5px 10px;
        font-size: 12px !important;
        text-transform: capitalize !important;
        font-weight: 400 !important;
    }
    .report-table .table .not-receipt{
        color:rgb(226, 114, 40);
    }
    .report-table .table td.tax_invoice{
        background: rgba(29, 170, 29, 0.377);
        color: rgb(11, 107, 24);
    }

    .table thead th{
        color: #fff;
        text-transform: capitalize;
    }

    .model-report-titles h5{
        font-weight:500;
        font-size:15px;
        color: #333;
    }

    @media print{
         body {
            margin: 0;
            padding: 0;
        }

        h5{
            color: #000 !important;
        }
        .nav.nav-tabs ~ .tab-content{
            border:none !important;
        }
        .report-table  .table thead th{
            color: #000 !important;
            text-transform: capitalize !important;
            font-weight: 500 !important;
        }
        .report-table  .table tbody td,.report-title{
            color: #000;
            font-weight: 500 !important;
        }
        .report-table .table
        {
            border: 1px solid #000 !important;
        }
        .report-table .table th,
        .report-table .table td{
            border: 1px solid #000 !important;
        }
        .page-break {
            page-break-before: always;
            break-before: page;
        }
    }
    .progress-bar {
        width: 100%;
        position: relative;
        height: 25px;
        text-align: center;
        padding: 5px 0;

    }
    .progress {
        position: absolute;
        width: 0%;
        top: 0;
        left: 0;
        z-index: 20;
        height: 100%;
        background-color: #4caf50;
        border-radius: 5px;
        text-align: center;
    }
    .progress-value{
        margin-top: 13px;
        position: absolute;
        z-index: 100;
        left: 10px;
    }
    .roi-box{
        position: relative;
        z-index: 1;
    }

    .roi-box .roi.text-green{
        color: rgb(22, 150, 44);
    }
    .roi-formula{
        width: 400px;
        border: 1px solid #ddd;
        z-index: 100;
        background: #fff;
        text-align: left;
        font-size: 16px;
    }

    tr.progress-danger td{
       background-color: #f28b82;
       color: white !important;
    }

    .toggler{
        cursor: pointer;
    }

    .modal-btn{
        width: 35px;
        height: 35px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .view-details{
        cursor: pointer;
    }

    .income-statement td {
        font-size: 12px !important;
        color: #000 !important;
    }

    .income-statement th {
        font-size: 14px !important;
        color: #000 !important;
    }

    .income-statement th.negetive {
        color: red !important
    }
    .income-statement th.final_profit{
        color: rgb(22, 150, 44) !important;
    }

</style>
<div class="tab-content bg-white">
    <div id="journaCreation" class="tab-pane active p-2" style="overflow:auto !important;">
        @if ($project)

        <input type="hidden" value="{{$project->id}}" id="project_id">

        <div>

            @php
                $estimated_expense = number_format($project->tasks->sum('estimated_expense'),2,'.','');
                $total_investment =  number_format($project->tasks->sum('expense'),2,'.',''); //* 1.05;

                $total_receipt = number_format($receiveds['total'] ?? 0,2,'.','');
                $total_receivable = number_format($receivables->sum('due_amount'),2,'.','');
                $sale = number_format($total_receipt + $total_receivable,2,'.','');
                $total_payment = number_format($project->tasks->sum('payment'),2,'.','');
                $total_payable = number_format($project->tasks->sum('payable'),2,'.','');
                // $total_receipt = number_format($project->tasks->sum('revenue'),2);
                $roi = 0;
                $profit = number_format($sale - $total_investment,2,'.','');
                $roi = $total_investment > 0 ? (($profit) / $total_investment ) * 100 : 0.00;

                $estimated_expense_formatted = number_format($estimated_expense, 2);
                $total_investment_formatted = number_format($total_investment, 2);
                $total_receipt_formatted = number_format($total_receipt, 2);
                $total_receivable_formatted = number_format($total_receivable, 2);
                $sale_formatted = number_format($sale, 2);
                $total_payment_formatted = number_format($total_payment, 2);
                $total_payable_formatted = number_format($total_payable, 2);
                $profit_formatted = number_format($profit);
            @endphp


            <div class="report-header">
                <div class="left-side">
                    <h4 class="report-title-lg"> 1. Project Overview  </h4>

                    <div class="report-table table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th style="width:30%;"> Project Name </th>
                                    {{-- <th style="width:10%;" class="text-center"> Plot No </th> --}}
                                    <th style="width:20%;" > Location </th>
                                    <th class="text-left" style="width:30%;"> Client/Owner </th>
                                    <th style="width:10%;" class="text-center"> Start Date </th>
                                    <th style="width:10%;" class="text-center"> End Date </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td> {{$project->project_name}} </td>
                                    {{-- <td class="text-center"> {{optional($project->new_project)->plot}} </td> --}}
                                    <td> {{$project->address}} </td>
                                    <td> {{optional($project->party)->pi_name}} </td>
                                    <td class="text-center"> {{$project_start_date?date('d/m/Y', strtotime($project_start_date)):null}} </td>
                                    <td class="text-center"> {{$project_end_date?date('d/m/Y', strtotime($project_end_date)):null}} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4 class="report-title-lg"> 2. Financial Summary </h4>
                    <div class="report-table table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    @if($estimated_expense > 0)
                                    <th class="text-right"> Estimated Expense </th>
                                    @endif

                                    <th class="text-right"> Total Investment </th>
                                    <th class="text-right"> Total Payment </th>
                                    <th class="text-right"> Total Payable </th>
                                    <th class="text-right"> Estimated Revenue </th>
                                    <th class="text-right"> Total Revenue  </th>
                                    <th class="text-right"> Total Receipt </th>
                                    <th class="text-right"> Total Receivable  </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @if($estimated_expense > 0)
                                    <td class="text-right"> {{number_format($project->tasks->sum('estimated_expense'),2)}}  </td>
                                    @endif

                                    <td class="text-right view-details" data-url="{{route('project.metarial.cost',['project' => $project->id, 'cost' => 'all'])}}"> {{number_format($total_investment,2)}} </td>
                                    <td class="text-right view-details" data-url="{{route('project.metarial.cost',['project' => $project->id, 'cost' => 'payment'])}}"> {{number_format($total_payment,2)}}  </td>
                                    <td class="text-right view-details" data-url="{{route('project.metarial.cost',['project' => $project->id, 'cost' => 'payable'])}}"> {{number_format($total_payable,2)}} </td>
                                    <td class="text-right"> {{number_format(optional($project->new_project)->contract_value,2)}} </td>
                                    <td class="text-right view-details" data-url="{{route('project.invoice',['project' => $project->id, 'type' => 'all'])}}"> {{number_format($sale,2)}} </td>
                                    <td class="text-right view-details" data-url="{{route('project.invoice',['project' => $project->id, 'type' => 'paid'])}}"> {{number_format($total_receipt,2)}} </td>
                                    <td class="text-right view-details" data-url="{{route('project.invoice',['project' => $project->id, 'type' => 'due'])}}"> {{number_format($total_receivable,2)}} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4 class="report-title-lg text-left" style="margin-top: 15px;"> 3. Expense Details <small> (Payable and Payment without Labour cost) </small>  </h4>

                    <div class="report-table table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th style="min-width:120px;text-align:right !important;">  Total Cost  </th>
                                    <th style="min-width:120px;text-align:right !important;"> Labour Cost  </th>
                                    <th style="min-width: 110px; text-align:right !important;"> Metarial Cost  </th>
                                    <th style="width:100px; text-align:right !important;"> Administrative Cost  </th>
                                    <th style="width:100px; text-align:right !important;"> Payment </th>
                                    <th style="width:100px; text-align:right !important;"> Payable </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $matarial_cost = (float) str_replace(',', '', $matarial_cost ?? 0);
                                    $labour_cost = (float) str_replace(',', '', $labour_cost ?? 0);
                                    $administrative_cost = (float) str_replace(',', '', $administrative_cost ?? 0);

                                    $total_cost = $matarial_cost + $labour_cost + $administrative_cost;
                                @endphp
                                <tr>
                                    <td style="text-align: right !important;"> {{number_format( $total_cost,2)}} </td>
                                    <td style="text-align: right !important;" class="view-details" data-url="{{route('project.labour.cost',['project' => $project->id])}}"> {{number_format($labour_cost),2}} </td>
                                    <td style="text-align: right !important;" class="view-details" data-url="{{route('project.metarial.cost',['project' => $project->id])}}"> {{number_format($matarial_cost),2}} </td>

                                    <td style="text-align: right !important;" class="view-details" data-url="{{route('project.metarial.cost',['project' => $project->id, 'cost' => 'administrative'])}}">{{number_format($administrative_cost,2)}} </td>
                                    <td style="text-align: right !important;" class="view-details" data-url="{{route('project.metarial.cost',['project' => $project->id, 'cost' => 'payment'])}}">{{$total_payment_formatted}} </td>
                                    <td style="text-align: right !important;" class="view-details" data-url="{{route('project.metarial.cost',['project' => $project->id, 'cost' => 'payable'])}}">{{$total_payable_formatted}} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4 class="report-title-lg text-left" style="margin-top: 15px;"> 4. Revenue Details  </h4>

                    <div class="report-table table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th style="min-width:120px;text-align:right !important;"> Total Contract </th>
                                    <th style="min-width: 110px; text-align:right !important;"> Invoice Amount   </th>
                                    <th style="width:100px; text-align:right !important;"> Uninvoice Amount </th>
                                    <th style="min-width:120px;text-align:right !important;"> Invoiced Payment  </th>
                                    <th style="min-width: 110px; text-align:right !important;"> Invoiced Payable   </th>
                                    <th style="width:100px; text-align:right !important;"> Total Received  </th>
                                    <th style="width:100px; text-align:right !important;"> Total Receivable  </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $contract_amount = optional($project->new_project)->contract_value;
                                    $receipt_amount= $receiveds['total'];
                                    $receivable_amount  = $receivables->sum('due_amount');
                                    $sale_amount = $total_receipt + $total_receivable;
                                    $uninvoice_amount = $contract_amount - $sale_amount;
                                    $total_receivable_amount = $contract_amount -  $receipt_amount;
                                @endphp
                                <tr>
                                    <td style="text-align: right !important"> {{number_format($contract_amount,2)}} </td>
                                    <td style="text-align: right !important;" class="view-details" data-url="{{route('project.invoice',['project' => $project->id, 'type' => 'all'])}}"> {{number_format($sale_amount, 2)}} </td>
                                    <td style="text-align: right !important">{{number_format($uninvoice_amount,2)}} </td>
                                    <td style="text-align: right !important;" class="view-details" data-url="{{route('project.invoice',['project' => $project->id, 'type' => 'paid'])}}">{{number_format($receipt_amount,2)}} </td>
                                    <td style="text-align: right !important;" class="view-details" data-url="{{route('project.invoice',['project' => $project->id, 'type' => 'due'])}}">{{number_format($receivable_amount,2)}} </td>
                                    <td style="text-align: right !important">{{number_format($receipt_amount,2)}} </td>
                                    <td style="text-align: right !important">{{number_format( $total_receivable_amount,2)}} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>



                    <h4 class="report-title-lg" style="margin-top: 15px;"> 5. ROI Calculation  </h4>

                    <div class="report-table table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th> Net Profit (Revenue - Investment) </th>
                                    <th class="roi"> ROI ((Net Profit / Total Investment) × 100) </th>
                                    <th> Estimated Progress </th>
                                    <th> Working Progress </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> {{$sale_formatted}} - {{$total_investment_formatted}} =  {{number_format($sale - $total_investment,2)}} <small> ({{$currency->symbole}}) </td>
                                    <td class="report-title roi"> ({{$profit_formatted .' / '. $total_investment_formatted . ') * '. 100 . ' = '}} {{number_format($roi,2)}} % <span> <i class='bx bx-purchase-tag' style="font-size: 13px"></i> </span> </td>
                                    <td class="text-center">
                                        <div class="d-flex">
                                            <div class="bg-danger progress-bar text-center" style="width:100%; max-width:200px;">
                                                <p id="estimate-progress-value" class="progress-value" style="padding:5px 0px;color:#fff;font-size:16px;" data-value="{{ $estimate_progress }}"></class=>
                                                <div id="estimate-progress" class="progress"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex">
                                            <div class="bg-danger progress-bar text-center" style="width:100%; max-width:200px;">
                                                <p id="working-progress-value" class="progress-value" style="padding:5px 0px;color:#fff;font-size:16px;" data-value="{{ $working_progress }}"></class=>
                                                <div id="working-progress" class="progress"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>



                    <div class="roi-formula p-2 border-1 d-none">
                        <p class="report-title"> <span style="color: #4caf50"> SA = {{number_format($sale,2)}} </span>  </p>
                        <p class="report-title"> <span style="color:rgb(211, 58, 58)"> IE = {{$total_investment}} </span>  </p>
                        <p class="report-title" style="margin:0; line-height:20px; font-size:16px;"> ROI = (<span style="color: #4caf50"> SA </span> -  <span style="color: rgb(211, 58, 58)"> IE </span>) / <span style="color: rgb(211, 58, 58)"> IE </span> * 100 </p>

                        @if ($total_investment > 0)
                            <p class="report-title"> = {{($sale - $total_investment) / $total_investment}} * 100   = {{number_format($roi,2)}}</p>
                        @else
                            <p class="report-title"> = {{($sale - $total_investment) . '/' .  $total_investment}} * 100   = {{number_format($roi,2)}}</p>
                        @endif


                        <p class="report-title"> Where, </p>

                        <p class="report-title" style="color: #4caf50"> SA = Sale Amount </p>
                        <p class="report-title" style="color:rgb(211, 58, 58)"> IE = Investment & Expense </p>
                    </div>
                </div>
            </div>

            <div class="row mt-2">

                <div class="col-12">
                    <h4 class="report-title-lg text-left" style="margin-top: 15px;"> 6. Project Task Details </h4>
                </div>

                <div class="col-12 report-details">
                    <div class="report-table table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th style="min-width:120px;text-align:left;"> Task </th>
                                    <th style="min-width: 110px; text-align:center;"> Start Date </th>
                                    <th style="width:100px; text-align:center;"> End Date </th>
                                    <th style="text-align: center"> Estimate </th>
                                    <th style="text-align: center"> Progress </th>
                                    <th style="text-align: right"> Contract </th>
                                    <th style="text-align: right"> Total expense </th>
                                    <th style="text-align: right"> Paid </th>
                                    <th style="text-align: right"> Payable </th>
                                    <th style="text-align: right"> Total Revenue  </th>
                                    <th style="text-align: right"> Received </th>
                                    <th style="text-align: right"> Receivable </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($project->tasks as $task)
                                <tr>
                                    <td>{{$task->task_name}} </td>
                                    <td style="text-align: center">{{$task->start_date ? date('d/m/Y', strtotime($task->start_date)) : null}}</td>
                                    <td style="text-align: center">{{$task->end_date ? date('d/m/Y', strtotime($task->end_date)) : null}}</td>
                                    <td style="text-align: center">{{$task->estimated_progress}} % </td>
                                    <td style="text-align: center">{{$task->completed}} % </td>
                                    <td class="text-right"> {{number_format($task->contact_amount,2)}} </td>
                                    <td style="text-align: right">{{number_format($task->expense,2)}}</td>
                                    <td style="text-align: right">{{number_format($task->payment,2)}}</td>
                                    <td style="text-align: right">{{number_format($task->payable,2)}}</td>
                                    <td style="text-align: right">{{number_format($task->revenue,2)}} </td>
                                    <td style="text-align: right">{{number_format($task->receipt,2)}}</td>
                                    <td style="text-align: right">{{number_format($task->receivable,2)}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-12 report-details" style="margin-top: 15px;">
                    <h5 class="text-left"> Expense Vs Revenue </h5>
                    <canvas id="roi_chart" class="w-100" style="min-height:280px; max-height:450px;"> </canvas>
                </div>


                <div class="col-12">
                    <h4 class="report-title-lg text-left" style="margin-top: 15px;"> 7. Expense & Revenue </h4>
                </div>

                <div class="col-6 report-details" data-title="expense">
                    <h3 class="text-left"> Expense {{number_format($project->purchase_expense->sum('total_amount'),2)}} <small> ({{$currency->symbole}}) </small>  </h3>
                    <div class="report-table table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center"> Invoice No </th>
                                    <th class="text-center"> Purchase No </th>
                                    <th class="text-center"> Date  </th>
                                    <th class="text-center"> Amount </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($payments as $key => $payment)
                                    <tr>
                                        <td class="text-center">{{$payment['invoice_no']}} </td>
                                        <td class="text-center"> {{$key}} </td>
                                        <td class="text-center"> {{$payment['date']}} </td>
                                        <td class="text-center"> {{number_format($payment['amount'],2)}} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-6 report-details" data-title="payble">
                    <h5 class="text-left"> Revenue {{$sale_formatted}} <small> ({{$currency->symbole}}) </small>  </h5>
                    <div class="report-table table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center"> Invoice </th>
                                    <th class="text-center"> Date  </th>
                                    <th class="text-center"> Invoice No  </th>
                                    <th class="text-center"> Amount </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($project->invoices as $invoice)
                                <tr>
                                    <td class="text-center"> {{$invoice->invoice_type}}</td>
                                    <td class="text-center"> {{date('d/m/Y',strtotime($invoice->date))}} </td>
                                    <td class="text-center"> {{$invoice->invoice_no ?? 'N/A' }} </td>
                                    <td class="text-center"> {{number_format($invoice->total_budget - $invoice->retention_amount,2)}} </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-12">
                    <h4 class="report-title-lg text-left" style="margin-top: 15px;"> 8. Received & Payment </h4>
                </div>

                <div class="col-6 report-details" data-title="received">
                    <h5 class="text-left"> Received {{$total_receipt_formatted}} <small> ({{$currency->symbole}}) </small>  </h5>
                    <div class="report-table table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center"> Receipt No </th>
                                    <th class="text-center"> Invoice No </th>
                                    <th class="text-center"> date </th>
                                    <th class="text-center"> Amount <small class="text-white">({{$currency->symbole}}) </small> </th>
                                </tr>
                            </thead>
                            <tbody>

                                  @if ($total_receipt > 0)
                                    @foreach($receiveds['received'] as $item)
                                    <tr>
                                        <td class="text-center"> {{$item['receipt_no']}} </td>
                                        <td class="text-center"> {{$item['invoice_no']}} </td>
                                        <td class="text-center"> {{$item['date']}}  </td>
                                        <td class="text-center"> {{number_format($item['amount'],2)}} </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="3"> Received Not Fouond </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-6 report-details" data-title="payable">
                    <h5 class="text-left"> Payment {{$total_payment_formatted}} <small> ({{$currency->symbole}}) </small> </h5>
                    <div class="report-table table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center"> Purchase No </th>
                                    <th class="text-center"> Invoice No </th>
                                    <th class="text-center"> date </th>
                                    <th class="text-center"> Amount </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($total_payable > 0)
                                    @foreach($payments as $key => $payment)
                                    @if($payment['paid_amount'] > 0)
                                        <tr>
                                            <td class="text-center"> {{$key}} </td>
                                            <td class="text-center">{{$payment['invoice_no']}} </td>
                                            <td class="text-center"> {{$payment['date']}} </td>
                                            <td class="text-center"> {{number_format($payment['paid_amount'],2)}} </td>
                                        </tr>
                                    @endif
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="4"> Payment Not Fouond </td>
                                </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-12">
                    <h4 class="report-title-lg text-left" style="margin-top: 15px;"> 9. Receivable & Payable </h4>
                </div>

                <div class="col-6 report-details" data-title="receivable">
                    <h5 class="text-left"> Receivable {{$total_receivable_formatted}} <small> ({{$currency->symbole}}) </small> </h5>
                    <div class="report-table table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center"> Invoice No </th>
                                    <th class="text-center"> date </th>
                                    <th class="text-center"> Amount <small class="text-white">({{$currency->symbole}}) </small> </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($total_receivable > 0)
                                    @foreach($receivables as $item)
                                    <tr>
                                        <td class="text-center"> {{$item->invoice_no}} </td>
                                        <td class="text-center"> {{date('d/m/Y',strtotime($item->date))}}  </td>
                                        <td class="text-center"> {{number_format($item->due_amount,2)}} </td>

                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="3"> Recivable Not Fouond </td>
                                </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-6 report-details" data-title="payable">
                    <h5 class="text-left"> Payable {{$total_payable_formatted}} <small> ({{$currency->symbole}}) </small> </h5>
                    <div class="report-table table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center"> Purchase No </th>
                                    <th class="text-center"> Invoice No </th>
                                    <th class="text-center"> date </th>
                                    <th class="text-center"> Amount </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($total_payable > 0)
                                    @foreach($payments as $key => $payment)
                                    @if($payment['due_amount'] > 0)
                                        <tr>
                                            <td class="text-center">{{$payment['invoice_no']}} </td>
                                            <td class="text-center"> {{$key}} </td>
                                            <td class="text-center"> {{$payment['date']}} </td>
                                            <td class="text-center"> {{number_format($payment['due_amount'],2)}} </td>
                                        </tr>
                                    @endif
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="4"> Payable Not Fouond </td>
                                </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-12 income-statement">

                    <table class="table table-sm table-hover">
                        <tr>
                            <th colspan="4" class="text-center">
                                <h4 class="report-title-lg"> 10 Project Profit & Loss Statement </h4>
                                <h6> {{ date('d/m/Y', strtotime($income_statements['from'])) }} -
                                    {{ date('d/m/Y', strtotime($income_statements['to'])) }}</h4>
                            </th>
                        </tr>

                        <tr>
                            <th colspan='3'>Operating Revenue</th>
                        </tr>
                        @foreach ($income_statements['revenues'] as $rev)
                            <tr>
                                <td style="width: 80px !important"></td>
                                <td>{{ $rev->fld_ac_head }}</td>
                                <td
                                    class="text-right pr-2 {{ $rev->balance < 0 ? 'negetive' : '' }}">
                                    {{ $rev->balance < 0 ? '(' . number_format(abs($rev->balance), 2) . ')' : number_format($rev->balance, 2) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="2" class="text-center">TOTAL OPERATING REVENUE</th>
                            <th
                                class="text-right pr-2 {{ $income_statements['revenue_balance'] < 0 ? 'negetive' : '' }}">
                                {{ $income_statements['revenue_balance'] < 0
                                    ? '(' . number_format(abs($income_statements['revenue_balance']), 2) . ')'
                                    : number_format($income_statements['revenue_balance'], 2) }}
                            </th>
                        </tr>

                        <tr>
                            <th colspan='3'>OPERATING EXPENSES </th>
                        </tr>

                        <tr>
                            <th style="width: 80px !important"></th>
                            <th>Cost Of Good Sold</th>
                            <th class="text-right pr-2">
                            </th>
                        </tr>
                        <tr>
                            <td style="width: 80px !important"></td>
                            <td>BEGINNING INVENTORY</td>
                            <td
                                class="text-right pr-2 {{ $income_statements['inventory']->beginningBalance < 0 ? 'negetive' : '' }}">
                                {{ $income_statements['inventory']->beginningBalance < 0
                                    ? '(' . number_format(abs($income_statements['inventory']->beginningBalance), 2) . ')'
                                    : number_format($income_statements['inventory']->beginningBalance, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td style="width: 80px !important"></td>
                            <td>PURCHASE INVENTORY</td>
                            <td
                                class="text-right pr-2 {{ $income_statements['inventory']->purchaseAmount < 0 ? 'negetive' : '' }}">
                                {{ $income_statements['inventory']->purchaseAmount < 0
                                    ? '(' . number_format(abs($income_statements['inventory']->purchaseAmount), 2) . ')'
                                    : number_format($income_statements['inventory']->purchaseAmount, 2) }}
                            </td>
                        </tr>

                        @foreach ($income_statements['cog_s'] as $cog)
                            <tr>
                                <td style="width: 80px !important"></td>
                                <td>{{ $cog->fld_ac_head }}</td>
                                <td
                                    class="text-right pr-2 {{ $cog->balance < 0 ? 'negetive' : '' }}">
                                    {{ $cog->balance < 0 ? '(' . number_format(abs($cog->balance), 2) . ')' : number_format($cog->balance, 2) }}
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <td style="width: 80px !important"></td>
                            <td>END INVENTORY</td>
                            <td
                                class="text-right pr-2 {{ $income_statements['inventory']->endBalance < 0 ? 'negetive' : '' }}">
                                {{ $income_statements['inventory']->endBalance > 0
                                    ? '(' . number_format($income_statements['inventory']->endBalance, 2) . ')'
                                    : number_format(abs($income_statements['inventory']->endBalance), 2) }}
                            </td>
                        </tr>

                        <tr>
                            <th colspan="2" class="text-center">TOTAL COST OF GOODS SOLD</th>
                            <th class="text-right pr-2 {{ $income_statements['total_cogs'] < 0 ? 'negetive' : '' }}">
                                {{ $income_statements['total_cogs'] < 0
                                    ? '(' . number_format(abs($income_statements['total_cogs']), 2) . ')'
                                    : number_format($income_statements['total_cogs'], 2) }}
                            </th>
                        </tr>



                        <tr>
                            <th colspan="2" class="text-center">GROSS PROFIT</th>
                            <th class="text-right pr-2 {{ $income_statements['gross_profit'] < 0 ? 'negetive' : '' }}">
                                {{ $income_statements['gross_profit'] < 0
                                    ? '(' . number_format(abs($income_statements['gross_profit']), 2) . ')'
                                    : number_format($income_statements['gross_profit'], 2) }}
                            </th>
                        </tr>

                        <tr>
                            <th colspan='3'>OVERHEAD</th>
                        </tr>

                        @foreach ($income_statements['overHeads'] as $overHead)
                            <tr>
                                <td style="width: 80px !important"></td>
                                <td>{{ $overHead->fld_ac_head }}</td>
                                <td
                                    class="text-right pr-2 {{ $overHead->balance < 0 ? 'negetive' : '' }}">
                                    {{ $overHead->balance < 0 ? '(' . number_format(abs($overHead->balance), 2) . ')' : number_format($overHead->balance, 2) }}
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <th colspan='3'>ADMINISTRATIVE EXPENSES</th>
                        </tr>

                        @foreach ($income_statements['administrative_exp'] as $exp)
                            <tr>
                                <td style="width: 80px !important"></td>
                                <td>{{ $exp->fld_ac_head }}</td>
                                <td
                                    class="text-right pr-2 {{ $exp->balance < 0 ? 'negetive' : '' }}">
                                    {{ $exp->balance < 0 ? '(' . number_format(abs($exp->balance), 2) . ')' : number_format($exp->balance, 2) }}
                                </td>
                            </tr>
                        @endforeach



                        <tr>
                            <th colspan="2" class="text-center">TOTAL EXPENSES</th>
                            <th
                                class="text-right pr-2 {{ $income_statements['total_op_expense'] < 0 ? 'negetive' : '' }}">
                                {{ $income_statements['total_op_expense'] < 0
                                    ? '(' . number_format(abs($income_statements['total_op_expense']), 2) . ')'
                                    : number_format($income_statements['total_op_expense'], 2) }}
                            </th>
                        </tr>



                        <tr>
                            <th colspan="2" class="text-center  {{ $income_statements['net_profit_loss'] < 0 ? 'negetive' : 'final_profit' }}"> {{$income_statements['net_profit_loss'] < 0 ? 'NET LOSS' : 'NET PROFIT'}}</th>
                            <th
                                class="text-right pr-2 {{ $income_statements['net_profit_loss'] < 0 ? 'negetive' : 'final_profit' }}">
                                {{ $income_statements['net_profit_loss'] < 0
                                    ? '(' . number_format(abs($income_statements['net_profit_loss']), 2) . ')'
                                    : number_format($income_statements['net_profit_loss'], 2) }}
                            </th>
                        </tr>

                        <tr>
                            <th colspan="2" class="text-center">DEPRECIATION</th>
                            <th
                                class="text-right pr-2 {{ $income_statements['depreciation']->amount < 0 ? 'negetive' : '' }}">
                                {{ $income_statements['depreciation']->amount < 0
                                    ? '(' . number_format(abs($income_statements['depreciation']->amount), 2) . ')'
                                    : number_format($income_statements['depreciation']->amount, 2) }}
                            </th>
                        </tr>

                        <tr>
                            <th colspan="2" class="text-center {{ $income_statements['final_profit'] < 0 ? 'negetive' : 'final_profit' }}"> {{$income_statements['final_profit'] < 0 ? 'NET LOSS' : 'NET PROFIT' }} </th>
                            <th class="text-right pr-2 {{ $income_statements['final_profit'] < 0 ? 'negetive' : 'final_profit' }}">
                                {{ $income_statements['final_profit'] < 0
                                    ? '(' . number_format(abs($income_statements['final_profit']), 2) . ')'
                                    : number_format($income_statements['final_profit'], 2) }}
                            </th>
                        </tr>
                    </table>

                </div>
            </div>
        </div>

        @endif
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/plugin/chart.js') }}"></script>

<script>
    function animateProgress(valueId, barId) {
        let $valueEl = $('#' + valueId);
        let $barEl = $('#' + barId);

        let value = parseFloat($valueEl.data('value')) || 0;
        value = Math.min(100, value); // Cap at 100%

        let counter = 0;

        let interval = setInterval(() => {
            $valueEl.html(counter + '%');
            $barEl.css('width', counter + '%');

            if (counter >= value) {
                clearInterval(interval);
            }
            counter++;
        }, 10);
    }

    $(document).ready(function(){
        var project_id = $('#project_id').val();
        var url = "{{route('roi.report.chart',':id')}}",
        url = url.replace(':id',project_id);
        $.ajax({
            type:'get',
            url:url,

            success:function(data){
                barChart(data);
            }
        })

        //working-progress bar

        animateProgress('working-progress-value', 'working-progress');
        animateProgress('estimate-progress-value', 'estimate-progress');
    });

    function barChart(data) {
        const fullLabels = data.labels || [];
        const shortLabels = fullLabels.map(label => label.short);
        const fullTaskNames = fullLabels.map(label => label.full);

        const datasets = [];

        $.each(data.value, function(key, val) {
            if (key.startsWith("data_")) {
                const index = key.split("_")[1];
                const labelKey = "label_" + index;

                if (data.value[labelKey] && Array.isArray(val)) {
                    const cleanedData = val.map(v => v === null ? 0 : v);

                    datasets.push({
                        label: data.value[labelKey],
                        data: cleanedData,
                        backgroundColor: getRandomColor(index)
                    });
                }
            }
        });

        const ctx = document.getElementById('roi_chart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: shortLabels,
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            title: function(tooltipItems) {
                                const index = tooltipItems[0].dataIndex;
                                return fullTaskNames[index]; // Show full name in tooltip
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Optional: Generate color based on index
    function getRandomColor(index) {
        const colors = [
            '#FF7043', '#FFA447', '#38E54D', '#FFC300', '#00CED1',
            '#B6CBBD', '#A9C46C', '#CAE0BC', '#CAE0BC', '#00DFA2'
        ];

        return colors[(index - 1) % colors.length];
    }

    $(document).on('click', '.toggler', function () {
    var target = $(this).data('target');

    // Show modal
    $('#' + target).modal('show');

    // Add multi-open class if another modal is already open
    if ($('.modal.show').length > 1) {
        $('#' + target).addClass('multi-open');
        $('.modal-backdrop').last().addClass('multi-open-backdrop');
    }

    $('body').addClass('modal-open');
});

$(document).on('click', '.close-btn', function () {
    var $modal = $(this).closest('.modal');

    $modal.modal('hide');

    // Clean up classes
    $modal.removeClass('multi-open');
    $('.modal-backdrop').removeClass('multi-open-backdrop');

    // If there's still another modal open, keep body scroll locked
    if ($('.modal.show').length > 1) {
        $('body').addClass('modal-open');
    }
});
</script>
@endpush

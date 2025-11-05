@extends('layouts.backend.app')
@push('css')
@include('layouts.backend.partial.style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
<style>
    .title{
        background: rgb(235, 225, 219);
        font-weight:500;
        padding:3px 0;
        font-size:17px;
    }
    .report-customer span{
        color: #000;
        font-weight: 500;
        font-size: 16px;
    }
    .date .date-title{
        width:130px;
        display: block;
        text-align: right;
        background: rgb(218, 165, 132);
        color: #000;
        font-weight: 500;
        font-size: 16px;
    }
    .date .date-value{
        width:200px;
        display: block;
        text-align: center;
        background:rgb(235, 225, 219);
        color: #000;
        font-weight: 500;
        font-size: 16px;
    }
    .custom-tbl{
        width: 100%;
    max-width: 100%;
    margin-bottom: 20px;
    border: 1px solid rgb(40, 133, 196);
    }
    .report-table  .custom-tbl
    {
        border:1px solid rgb(40, 133, 196);
    }
    .report-table .custom-tbl th,
    .report-table .custom-tbl td{
        border-bottom: 1px solid rgb(40, 133, 196);
    }
    .report-table  .custom-tbl thead{
        background:rgb(40, 133, 196);
    }
    .report-table  .custom-tbl thead th{
        color: #fff;
        text-transform: capitalize !important;
        font-weight: 500 !important;
    }
    .report-table  .custom-tbl tbody td{
        color: #000;
        font-size: 12px !important;
        text-transform: capitalize !important;
        font-weight: 400 !important;
    }
    .report-table .custom-tbl .not-receipt{
        color:rgb(226, 114, 40);
    }

    .report-table .custom-tbl td.tax_invoice{
        background: rgba(29, 170, 29, 0.377);
        color: rgb(11, 107, 24);
    }
    @media print{
        .nav.nav-tabs ~ .tab-content{
            border:none !important;
        }
        .date .date-title{
            padding-right: 5px !important;
            width: fit-content;
        }
        .date .date-value{
            text-align: left;
            width: fit-content;
        }
        .report-table  .custom-tbl thead th{
            color: #000 !important;
            text-transform: capitalize !important;
            font-weight: 500 !important;
        }
        .report-table  .custom-tbl tbody td{
            color: #000;
        }

    }
</style>
@endpush

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.project._header')
            <div class="tab-content bg-white">
                <div id="journaCreation" class="tab-pane active p-2">
                    <div class="mb-2 d-flex justify-content-between align-item-center print-hideen">
                        @include('clientReport.project._report_submenu',['activeMenu' => 'report'])
                        <form class="form print-hideen mt-1">
                            <div class="form-group d-flex">

                                <select name="customer_id" id="customer_id" class="form-control common-select2">
                                    <option value=""> Select Customer </option>
                                    @foreach ($customers as  $item)
                                    <option value="{{$item->id}}" {{isset($customer) ? $customer->id == $item->id ? 'selected' : ' ' : ' '}}> {{$item->pi_name}} </option>
                                    @endforeach
                                </select>
                                <select name="project_id" id="project_id" class="common-select2">
                                    <option value=""> Select Porject</option>
                                    @foreach ($projects as  $item)
                                    <option value="{{$item->lpo_projects_id}}" {{isset($project_id) ? $project_id == $item->lpo_projects_id ? 'selected' : ' ' : ' '}}> {{$item->project_name}} </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn-primary btn-sm btn"> Search </button>
                                <button type="button" class="btn-secondary btn-sm btn ml-1" onclick="window.print()"> Print </button>
                            </div>
                        </form>
                    </div>
                    @if ($customer)
                    <div>
                        @include('layouts.backend.partial.modal-header-info')
                        <div class="title text-center">
                            <h4 style="color:#000;font-weight:400; font-size:18px; margin:0;"> Account Statement </h4>
                        </div>

                        <div class="d-flex justify-content-between align-items-center border" style="padding:5px 8px;margin:15px 0;">
                            <div class="report-customer">
                                <span> To, </span> <br>
                                <span> {{$customer->pi_name}}</span> <br>
                                @if ($customer->address)
                                <span> {{$customer->address}} </span> <br>
                                @endif
                                <span>TRN: {{$customer->trn_no}} </span> <br>
                            </div>

                            @php
                                $total_balance = 0;
                                foreach ($customer->projects as $key => $project){
                                    $total_balance += $project->invoicess->sum('due_amount');
                                }
                            @endphp
                            <div>
                                <div class="date d-flex justify-content-end">
                                    <span class="date-title d-block"> Date : </span> <span class="date-value d-block"> {{date('d/m/Y')}} </span>
                                </div>
                                <div class="date d-flex justify-content-end" style="margin-top:7px;">
                                    <span class="date-title d-block"> Total Balance  : </span> <span class="date-value d-block"> {{number_format($total_balance,2,'.','')}} <small> ({{$currency->symbole}}) </small> </span>
                                </div>
                            </div>
                        </div>

                        @php
                            $total_balance = 0;
                        @endphp

                        @foreach($project_map as $item)
                        @php
                            $project = $item['details'];
                        @endphp

                        <div class="project-details mb-1">
                            {{-- <h2 style="color:#000;font-weight:400; font-size:18px;"> Project Overview </h2> --}}
                            <div class="row">
                                <div class="col-6 pr-0">
                                     <p style="color:#000;font-weight:400; font-size:15px; margin:0;">
                                        <span style="color:#000;font-weight:500;"> Project Name: </span> {{ Illuminate\Support\Str::limit($project->project_id?$project->new_project->name:$project->project_name,35)}}
                                    </p>
                                </div>

                                <div class="col-2 pr-0">
                                    <p style="color:#000;font-weight:400; font-size:15px; margin:0;">
                                        <span style="color:#000;font-weight:500;"> LPO No: </span>
                                         {{$project->lpo_no}}
                                    </p>
                               </div>
                               <div class="col-2 pr-0">
                                    <p style="color:#000;font-weight:400; font-size:15px; margin:0;">
                                        <span style="color:#000;font-weight:500;"> QTN. No: </span>
                                        {{$project->quotation->project_code}}
                                    </p>
                                </div>
                                <div class="col-2">
                                    <p style="color:#000;font-weight:400; font-size:15px; margin:0;">
                                        <span style="color:#000;font-weight:500;"> Total <small> ({{$currency->symbole}}) </small> : </span>
                                        {{number_format($project->total_budget+(($standard_vat_rate/100)*$project->total_budget),2,'.','')}}
                                    </p>
                                </div>
                                <div class="col-12">
                                    <p style="color:#000;font-weight:400; font-size:15px; margin:0;">
                                        <span style="color:#000;font-weight:500;"> Work Descriptions :{{ Illuminate\Support\Str::limit($project->project_description,100)}} </span>
                                    </p>
                                </div>
                            </div>
                            {{-- {{ Illuminate\Support\Str::limit($project->project_description,200)}} --}}
                        </div>

                        <div class="report-table table-responsive">
                            <table class="tabl-sm custom-tbl  border-botton  w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="color: #fff !important; border:none !important"> Date </th>
                                        <th class="text-center" style="color: #fff !important; border:none !important"> Payment Terms </th>
                                        <th class="text-center" style="color: #fff !important; border:none !important">D.O No.</th>
                                        <th class="text-center" style="color: #fff !important; border:none !important"> Invoice no </th>
                                        <th class="text-center" style="color: #fff !important; border:none !important"> Invoice Type </th>
                                        <th class="text-center" style="color: #fff !important; border:none !important"> Amount  </th>
                                        <th class="text-center" style="color: #fff !important; border:none !important"> Received Payment </th>
                                        <th class="text-center" style="color: #fff !important; border:none !important"> Balance </th>
                                        <th class="text-center" style="color: #fff !important; border:none !important;width: 200px !important"> Payment Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_budget  = 0;
                                        $total_paid_amount = 0;
                                        $total_due_amount = 0;
                                    @endphp
                                    @foreach ($item['invoices'] as $project)
                                        @foreach($project->invoicess as $invoice)
                                            <tr style="border-top: 1px solid rgb(40, 133, 196);">
                                                <td class="text-center"> {{date('d/m/Y',strtotime($invoice->date))}}  </td>
                                                <td class="text-center"> {{$invoice->total_due_amount_percentage}}%  </td>
                                                <td class="text-center"> {{ $invoice->project ? $invoice->project->do_no : '' }}</td>
                                                <td class="text-center"> {{$invoice->invoice_no?$invoice->invoice_no:$invoice->proforma_invoice_no}} </td>
                                                <td class="text-center {{$invoice->invoice_type == 'Tax Invoice' ? 'tax_invoice' : ' '}}"> {{$invoice->invoice_type}}  </td>
                                                <td class="text-center"> {{$invoice->total_budget}}  </td>
                                                <td class="text-center">
                                                    {{-- {{$invoice->paid_amount}} --}}
                                                </td>
                                                <td class="text-center"> {{$invoice->total_budget}}  </td>
                                                <td></td>
                                                {{-- <td class="text-center"> @foreach ($invoice->receipts as $receipt )
                                                    {{$receipt->payment->pay_mode=="Cheque" ? $receipt->payment->cheque_no  : $receipt->payment->pay_mode }}
                                                @endforeach </td> --}}
                                            </tr>
                                            @php
                                                $receipt_sales = \App\ReceiptSale::whereIn('sale_id',[$invoice->id])->get();
                                                $payment = 0;
                                                $balance=$invoice->total_budget;

                                                $total_budget += $invoice->total_budget;
                                                $total_paid_amount += $invoice->paid_amount;
                                                $total_due_amount += $invoice->due_amount;
                                            @endphp
                                            @if ($receipt_sales->count() > 0)
                                                @foreach ($receipt_sales as $sale)
                                                    @php
                                                    $payment += $sale->Total_amount;
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center" > {{date('d/m/Y',strtotime($sale->payment->date))}}</td>
                                                        <td colspan="2"></td>
                                                        <td class="text-center" >{{$sale->payment->receipt_no}}</td>
                                                        <td class="text-center">Receipt</td>
                                                        <td></td>
                                                    <td class="text-center" > {{$sale->Total_amount}}</td>
                                                        <td class="text-center">{{$balance=$balance-$sale->Total_amount}}</td>
                                                        <td  class="text-center" >
                                                            {{$sale->payment->pay_mode=='Cheque'?$sale->payment->pay_mode.' No. '.$sale->payment->cheque_no.', Bank- '.$sale->payment->issuing_bank:$sale->payment->pay_mode }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endforeach
                                    <tr>
                                        <td colspan="5" style="font-weight:bold !important;color:#000;">  </td>
                                        <td colspan="1" class="text-center" style="font-weight:bold !important;color:#000;"> {{number_format($total_budget,2,'.','')}}   </td>
                                        <td colspan="1" class="text-center" style="font-weight:bold !important;color:#000;"> {{number_format( $total_paid_amount,2,'.','')}}   </td>
                                        <td colspan="1" class="text-center" style="font-weight:bold !important;color:#000;"> {{number_format($total_due_amount,2,'.','')}}   </td>
                                        <td></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        @endforeach
                        {{-- <div class="text-center">
                            <span style="color:#000;font-weight:500;font-size:16px;"> Total Balance {{($currency->symbole)}} : {{$total_balance}} </span>
                        </div> --}}
                    </div>
                    @endif
                </div>

                <div class="divFooter mb-1 ml-1 invoice-view-wrapper student_profle-print">
                    Business Software Solutions by
                    <span style="color: #0005" class="spanStyle"><img class="img-fluid"
                            src="{{ asset('img/zikash-logo.png')}}" alt="" width="70"></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

<script>
    $(document).on('change','#customer_id',function(){
        let id = $(this).val();
        let url = "{{route('customer.projects',':id')}}";
        url = url.replace(':id',id);

        $.ajax({
            type:'get',
            url:url,
            success:function(data){
                $('#project_id').empty();
                $('#project_id').append("<option value=''> Select... </option>");
                $.each(data,function(key,val){
                    $('#project_id').append("<option value='" + val.id +"'> " + val.project_name + " </option>");
                })
            }
        })
    })
    </script>
@endpush

@extends('layouts.backend.app')
@section('content')
@include('layouts.backend.partial.style')
<style>
    @media print{
        .text-success{
            color: black !important;
        }
    }
</style>
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
               
                <!-- Bordered table start -->
                <div class="row" id="table-bordered">
                    <div class="col-12">
                        <div class="card cardStyleChange">
                            <div class="conpany-header">
                                @include('layouts.backend.partial.modal-header-info')
                            </div>
                            <div class="card-body print-hidden">
                                <div class="d-flex mt-2">
                                    <h4 class="card-title flex-grow-1">Invoice Report: 
                                        @if ($customer)
                                            {{$customer->pi_name}}
                                        @endif
                                        @if ($from && $to)
                                            {{" From ".date('d-m-Y')." To ". date('d-m-Y') }}
                                        @endif
                                        @if($date)
                                            {{ date('d/m/Y') }}
                                        @endif
                                    </h4>
                                    {{-- <div>
                                        <button type="button" class="btn mExcelButton formButton mr-1" title="Export" onclick="exportTableToCSV('general-ledger-29 Jan 2023.csv')">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{asset('assets/backend/app-assets/icon/excel-icon.png')}}" width="25">
                                                </div>
                                                <div><span>Export To CSV</span></div>
                                            </div>
                                        </button>
                                        <a href="#" class="btn btn_create mPrint formButton" title="Print" onclick="window.print()">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{asset('assets/backend/app-assets/icon/print-icon.png')}}" width="25">
                                                </div>
                                                <div><span>Print</span></div>
                                            </div>
                                        </a>
                                    </div> --}}
                                </div>
                                <div class="mt-2">
                                    <form action="" method="GET" class="row">
                                        <div class="form-group col-md-2">
                                            <label for="">Date</label>
                                            <input type="text" class="inputFieldHeight form-control" name="date" placeholder="Select Date" id="date">
                                        </div>
                                        <div class="col-2">
                                            <label for="">From</label>
                                            <input type="text" class="inputFieldHeight form-control" name="from" placeholder="From" id="from">
                                        </div>
                                        <div class="col-2">
                                            <label for="">To</label>
                                            <input type="text" class="inputFieldHeight form-control" name="to" placeholder="To" id="to">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="">Customer Name</label>
                                            <select name="customer_id" class="form-control inputFieldHeight common-select2">
                                                <option value="">Select...</option>
                                                @foreach ($customers as $item)
                                                    <option value="{{$item->id}}">{{$item->pi_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-2 text-right mt-2">
                                            <button type="submit" class="btn mSearchingBotton mb-2 formButton" title="Search">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img src="{{asset('assets/backend/app-assets/icon/searching-icon.png')}}" width="25">
                                                    </div>
                                                    <div><span>Search</span></div>
                                                </div>
                                            </button>
                                        </div>
                                        <div class="col-md-2 text-right mt-2">
                                            <button type="button" class="btn mPrint formButton" title="Print" onclick="window.print()">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img src="{{asset('assets/backend/app-assets/icon/print-icon.png')}}" width="25">
                                                    </div>
                                                    <div><span>Print</span></div>
                                                </div>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0 table-sm table-bordered">
                                        <thead  class="thead-light">
                                            <tr style="height: 50px;">
                                                <th>Sl No</th>
                                                <th>Date</th>
                                                <th>Bill No</th>
                                                <th>Status</th>
                                                <th class="text-right">Tax 5%</th>
                                                <th class="text-right pr-1">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total = 0;
                                                $invoice_key = 1;
                                            @endphp
                                            @foreach ($tax_invoices as $invoice)
                                                <tr class="trFontSize">
                                                    <td>{{$invoice_key}}</td>
                                                    <td>{{ date('d/m/Y', strtotime($invoice->date)) }}</td>
                                                    <td>{{$invoice->invoice_no}}</td>
                                                    <td><span class="text-success">Approved</span></td>
                                                    <td class="text-right">{{$invoice->amount}}</td>
                                                    <td class="text-right pr-1">{{$invoice->amount+$invoice->vat_amount}}</td>
                                                </tr>
                                                @php
                                                    $total += $invoice->amount+$invoice->vat_amount;
                                                    $invoice_key += 1;
                                                @endphp
                                            @endforeach
                                            @foreach ($temp_tax_invoices as $invoice)
                                                <tr class="trFontSize">
                                                    <td>{{$invoice_key+1}}</td>
                                                    <td>{{ date('d/m/Y', strtotime($invoice->date)) }}</td>
                                                    <td>{{$invoice->invoice_no}}</td>
                                                    <td><span class="text-dark">Pending</span></td>
                                                    <td class="text-right">{{$invoice->amount}}</td>
                                                    <td class="text-right pr-1">{{$invoice->amount+$invoice->vat_amount}}</td>
                                                </tr>
                                                @php
                                                    $total += $invoice->amount+$invoice->vat_amount;
                                                    $invoice_key += 1;
                                                @endphp
                                            @endforeach
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="text-right">Total Amount</td>
                                                <td class="text-right pr-1">{{$total}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="conpany-header">
                                @include('layouts.backend.partial.modal-footer-info')
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Bordered table end -->



            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection

@push('js')

@endpush
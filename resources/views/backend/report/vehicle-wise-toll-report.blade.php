@extends('layouts.backend.app')
@section('content')
@include('layouts.backend.partial.style')
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
                                    <h4 class="card-title flex-grow-1">Vehicle Wise Toll Report: 
                                        @if ($toll_info)
                                            {{$toll_info->vehicle_number}}
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
                                        <div class="form-group col-md-4">
                                            
                                        </div>
                                        
                                        <div class="form-group col-md-4">
                                            <label for="">Truck Number</label>
                                            <select name="truck_id" id="" class="form-control inputFieldHeight common-select2">
                                                <option value="">Select Truck Number</option>
                                                @foreach ($vehicles as $item)
                                                    <option value="{{$item->id}}">{{$item->vehicle_number}}</option>
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
                                    <table class="table mb-0 table-sm">
                                        @foreach ($truck_list as $item)
                                            <thead  class="thead-light">
                                                <tr style="height: 50px;">
                                                    <th colspan="3">Vehicle Number: {{$item->vehicle_number}}</th>
                                                </tr>
                                            </thead>
                                            <tr>
                                                <td>Date</td>
                                                <td>Description</td>
                                                <td class="text-right pr-1">Amount</td>
                                            </tr>
                                            @php
                                                $net_payment_amount = 0;
                                            @endphp
                                                    @php
                                                        $toll_payment = App\TollFeeInvoiceItem::where('truck_id', $item->id)->get();
                                                        $net_payment_amount += $toll_payment->sum('amount');
                                                    @endphp
                                                    @foreach ($toll_payment as $key => $payment)
                                                        <tr>
                                                            <td>{{date('d/m/Y', strtotime($payment->date))}}</td>
                                                            <td>{{$payment->toll_name->name}}</td>
                                                            <td class="text-right pr-1">{{$payment->amount}}</td>
                                                        </tr>
                                                    @endforeach
                                            <tr class="mb-3">
                                                <td colspan="3" class="text-right pr-1">Total: {{number_format($net_payment_amount,2)}}</td>
                                            </tr>
                                        @endforeach
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
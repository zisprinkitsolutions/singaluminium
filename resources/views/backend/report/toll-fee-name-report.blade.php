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
                                    <h4 class="card-title flex-grow-1">Toll Fee Name Wise Report: 
                                        @if ($toll_info)
                                            {{$toll_info->name}}
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
                                            <label for="">Toll Name</label>
                                            <select name="toll_id" id="" class="form-control inputFieldHeight common-select2">
                                                <option value="">Select Name</option>
                                                @foreach ($toll_name as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
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
                                        @foreach ($toll_list as $item)
                                            <thead  class="thead-light">
                                                <tr style="height: 50px;">
                                                    <th>Toll Name</th>
                                                    <th colspan="4">{{$item->name}}</th>
                                                </tr>
                                            </thead>
                                            <tr>
                                                <td>Date</td>
                                                <td>Description</td>
                                                <td class="text-right">Debit</td>
                                                <td class="text-right pr-1">Credit</td>
                                            </tr>
                                            @php
                                                $net_payment_amount = 0;
                                                $net_recharge_amount = 0;
                                            @endphp
                                                    @php
                                                        $toll_payment = App\TollFeesPayment::where('toll_fees_id', $item->id)->orderBy('date', 'asc')->get();
                                                        $toll_recharge = App\TollFeesRecharge::where('toll_fees_id', $item->id)->orderBy('date', 'asc')->get();
                                                        $net_payment_amount += $toll_payment->sum('amount');
                                                        $net_recharge_amount += $toll_recharge->sum('amount');
                                                    @endphp
                                                    @foreach ($toll_payment as $key => $payment)
                                                        <tr>
                                                            <td>{{date('d/m/Y', strtotime($payment->date))}}</td>
                                                            <td>{{$payment->description}}</td>
                                                            <td class="text-right">0</td>
                                                            <td class="text-right pr-1">{{$payment->amount}}</td>
                                                        </tr>
                                                    @endforeach
                                                    @foreach ($toll_recharge as $key => $recharge)
                                                        <tr>
                                                            <td>{{date('d/m/Y', strtotime($recharge->date))}}</td>
                                                            <td>Toll Recharge</td>
                                                            <td class="text-right">{{$recharge->amount}}</td>
                                                            <td class="text-right pr-1">0</td>
                                                        </tr>
                                                    @endforeach
                                            <tr class="mb-3">
                                                <td colspan="2" class="text-right">Total: </td>
                                                <td class="text-right">{{number_format($net_recharge_amount,2)}}</td>
                                                <td class="text-right pr-1">{{number_format($net_payment_amount,2)}}</td>
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
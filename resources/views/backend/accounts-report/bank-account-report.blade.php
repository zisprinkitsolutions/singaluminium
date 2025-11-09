

@extends('layouts.backend.app')
@section('content')
@include('layouts.backend.partial.style')
<style>
    .tabPadding{
        padding: 5px;
    }
    .padding-right{
        padding-right: 10px;
    }
    td{
        font-size: 12px !important;
    }

    th{
        font-size: 14px !important;
    }
    @media(min-width:1300px){
        .padding-right{
            padding-right: 0px !important;
        }
    }
    @media print{

        .row{
            display: flex;
        }
        .col-md-6{
            max-width: 50% !important;
        }
    }
    .card-body {
        flex: 1 1 auto;
        min-height: 1px;
        padding: 0rem !important;
    }
</style>

<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.report._header',['activeMenu' => 'bank-account'])
            <div class="tab-content bg-white">
                <div class="tab-pane active p-2">
                    <div class="content-body">

                        <section id="widgets-Statistics">
                            <div class="row mt-1" style="margin-left: 5px !important">
                                <div class="col-md-9">
                                    <form action="" method="GET" class="d-flex row">

                                        <div class="row form-group col-md-3">
                                            <input type="text" class="form-control inputFieldHeight datepicker" placeholder="From Date" name="date" autocomplete="off">
                                        </div>
                                        <div class="row form-group col-md-3">
                                            <input type="text" class="form-control inputFieldHeight datepicker" placeholder="To Date" name="date2" autocomplete="off">
                                        </div>
                                        <div class="col-md-3">
                                            <select name="sub_account_head" id="sub_account_head" class="common-select2" style="width: 100% !important">
                                                <option value="">Bank Name....</option>
                                                @foreach ($banks as $item)
                                                    <option value="{{ $item->id }}" > {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <button type="submit" class="btn mSearchingBotton mb-2 formButton inputFieldHeight" title="Search" >
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img src="{{asset('assets/backend/app-assets/icon/searching-icon.png')}}" width="25">
                                                    </div>
                                                    <div><span>Search</span></div>
                                                </div>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-3 text-right">
                                    <a href="#" class="btn btn_create mPrint formButton" title="Print" onclick="media_print('bank_report')">
                                        <div class="d-flex">
                                            <div class="formSaveIcon">
                                                <img src="{{ asset('assets/backend/app-assets/icon/print-icon.png') }}"
                                                    width="25">
                                            </div>
                                            <div><span>Print</span></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body pt-0 pb-0" id="bank_report">
                                @include('layouts.backend.partial.modal-header-info')
                                <table class="table table-bordered table-sm ">
                                    <thead class="thead">
                                        <tr>
                                            <th style="width: 8%">Date</th>
                                            <th style="width: 15%">Bill/Transaction</th>
                                            <th >Owner/Party Name</th>
                                            <th style="width: 8%">Pay Mode</th>
                                            <th style="width: 13%">Bank Name</th>
                                            <th style="width: 10%" class="text-right pr-1">Bank In</th>
                                            <th style="width: 10%" class="text-right pr-1">Bank Out</th>
                                            <th style="width: 13%" class="text-right pr-1">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody id="purch-body">
                                        @php
                                            $t_balance=0;
                                            $cash_in = 0;
                                            $cash_out = 0;
                                            $total_cash_in = 0;
                                            $total_cash_out = 0;
                                        @endphp
                                        @foreach ($bank_account as $item)
                                            @php
                                                $cash_in = null;
                                                $cash_out = null;
                                                if($item->transaction_type==='DR'){
                                                    $cash_in = $item->amount;
                                                    $total_cash_in += $item->amount;
                                                    $t_balance += $item->amount;
                                                }else {
                                                    $cash_out = $item->amount;
                                                    $total_cash_out += $item->amount;
                                                    $t_balance -= $item->amount;
                                                }
                                                $journal = \App\Journal::find($item->journal_id);
                                                $detail_record = $journal->journal_description($journal->id);
                                            @endphp
                                            <tr>
                                                <td>{{date('d/m/Y', strtotime($item->journal_date))}}</td>
                                                @if(isset($detail_record))
                                                    <td style="font-size: 13px; padding-left: 25px !important;" class="show-details" data-type=" {{ isset($detail_record['type']) ? $detail_record['type'] : 'type' }}" id="{{ isset($detail_record['id']) ? $detail_record['id'] : 'id' }}">
                                                        {{ isset($detail_record['name']) ? $detail_record['name'] : 'N/A' }}
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif
                                                <td>{{$item->party->pi_name ?? ''}}</td>
                                                <td>Bank</td>
                                                <td>{{$item->ac_sub_head->name ?? '' }}</td>
                                                <td class="text-right pr-1">
                                                    {{ number_format($cash_in, 2) }}
                                                </td>
                                                <td class="text-right pr-1">
                                                    {{ number_format($cash_out, 2) }}
                                                </td>
                                                <td class="text-right pr-1">
                                                    {{ number_format($t_balance,2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr style="font-weight: bolder">
                                            <td colspan="5" class="text-right pr-1">Total</td>
                                            <td class="text-right pr-1">{{number_format($total_cash_in,2)}}</td>
                                            <td class="text-right pr-1">{{number_format($total_cash_out,2)}}</td>
                                            <td class="text-right pr-1">{{$t_balance<0?'CR: ':'DR: '.number_format($t_balance<0?$t_balance*(-1):$t_balance,2)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


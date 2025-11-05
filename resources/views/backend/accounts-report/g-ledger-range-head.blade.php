@extends('layouts.backend.app')
@section('content')
    @include('layouts.backend.partial.style')
    <style>
        .tabPadding {
            padding: 5px;
        }

        .padding-right {
            padding-right: 10px;
        }

        @media(min-width:1300px) {
            .padding-right {
                padding-right: 0px !important;
            }
        }
        th{
            font-size: 11px !important;
        }
        td{
            font-size: 10px !important;
        }
    </style>
    @php
        $grand_total_value = 0;
        $grand_total_pcs = 0;
    @endphp
    <div class="app-content content print-hideen">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.report._header', ['activeMenu' => 'account_report'])
                <div class="tab-content bg-white">
                    <div class="tab-pane active p-2">
                        <div class="content-body">
                            <section id="widgets-Statistics">
                                <div class="d-flex justify-content-between align-items-center">
                                    @include('clientReport.report._accounting_report_subheader', [
                                        'activeMenu' => 'general_ledger',
                                    ])                                    <div class="d-flex gap-4 align-items-center">
                                        <button type="button" class="btn mExcelButton formButton mr-1"
                                            title="Export"
                                            onclick="exportTableToCSV('general-ledger-{{ date('d M Y') }}.csv')">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{ asset('assets/backend/app-assets/icon/excel-icon.png') }}"
                                                        width="25">
                                                </div>
                                                <div><span>Export To CSV</span></div>
                                            </div>
                                        </button>
                                        <a href="#" class="btn btn_create mPrint formButton" title="Print"
                                            onclick="window.print()">
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

                                <div class="cardStyleChange">
                                    <div class="card-body px-1 py-0 mt-1">
                                            <div class="row">
                                                <div class="col-md-12 padding-right">
                                                    <form action="" method="GET" >
                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <input type="text" class="inputFieldHeight form-control datepicker" name="from" placeholder="From" value="{{date('d/m/Y', strtotime($from))}}"   id="from"  autocomplete="off">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <input type="text" class="inputFieldHeight form-control datepicker" name="to"
                                                                placeholder="To"  id="to" value="{{$from!=$to? (date('d/m/Y', strtotime($to))):''}}" autocomplete="off">
                                                            </div>
                                                            <div class="col-md-3">
                                                                {{-- {{dd($unique_acc_head)}} --}}
                                                                <select name="search" id="search" class="common-select2 form-control">
                                                                    <option value="">Select Head</option>
                                                                    @foreach ($journal_heads as $item)
                                                                    <option value="{{$item->account_head_id.'/head'}}" {{isset($unique_acc_head)? ($unique_acc_head->account_head_id==$item->account_head_id?'selected':''):''}}>{{$item->ac_head->fld_ac_code}}-{{$item->ac_head->fld_ac_head}}</option>

                                                                    @endforeach
                                                                    @foreach ($master_groups as $item)
                                                                    <option value="{{$item->master_account_id.'/master'}}" {{isset($unique_mst)? ( $unique_mst->master_account_id==$item->master_account_id?'selected':''):''}}>{{$item->master_ac->mst_ac_code}}-{{$item->master_ac->mst_ac_head}}</option>

                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2 " >
                                                                <button type="submit" class="btn mSearchingBotton mb-2 formButton" title="Search" >
                                                                    <div class="d-flex">
                                                                        <div class="formSaveIcon">
                                                                            <img src="{{asset('assets/backend/app-assets/icon/searching-icon.png')}}" width="25">
                                                                        </div>
                                                                        <div><span>Search</span></div>
                                                                    </div>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <input type="hidden" name="hidden_date_from" value="{{ isset($from)? $from:"" }}" id="hidden_date_from">
                                                <input type="hidden" name="hidden_date_to" value="{{ isset($to)? $to:"" }}" id="hidden_date_to">
                                            </div>
                                        <div class="d-flex">

                                            <input type="hidden" name="hidden_date_from"
                                                value="{{ isset($from) ? $from : '' }}" id="hidden_date_from">
                                            <input type="hidden" name="hidden_date_to" value="{{ isset($to) ? $to : '' }}"
                                                id="hidden_date_to">
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-0">
                                        <h4 class="text-center">General Ledger</h4>
                                        <h6 class="text-center">{{$from!=null?date('d F Y', strtotime($from)):''}} {{$to!=null? '-'.date('d F Y', strtotime($to)):''}}</h6>
                                        <table class="table table-sm">
                                               @if(isset($unique_acc_head))
                                                    <tr>
                                                            <th>{{ $unique_acc_head->ac_head->fld_ac_code }}</th>
                                                            <th><a
                                                                    href="#">{{ $unique_acc_head->ac_head->fld_ac_head }}</a>
                                                            </th>
                                                            <th>Opening Balance</th>
                                                            <th class="text-right">@if(!empty($currency->symbole)){{$currency->symbole}}@endif {{$each_ledger_dr =$unique_acc_head->headOpeningBalance($unique_acc_head->ac_head->id,$from)>0?$unique_acc_head->headOpeningBalance($unique_acc_head->ac_head->id,$from):'0.00'}}</th>
                                                            <th class="text-right">@if(!empty($currency->symbole)){{$currency->symbole}}@endif{{ $each_ledger_cr  = $unique_acc_head->headOpeningBalance($unique_acc_head->ac_head->id,$from)<0?($unique_acc_head->headOpeningBalance($unique_acc_head->ac_head->id,$from)*(-1)):'0.00'}}</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Narration</th>
                                                            <th>Ref. No.</th>
                                                            <th class="text-right">Debit</th>
                                                            <th class="text-right">Credit</th>
                                                        </tr>

                                                        @foreach (App\JournalRecord::where('account_head_id', $unique_acc_head->account_head_id)->where('journal_id', '!=', 0)->where('opening_balance_entry', false)->whereBetween('journal_date',[$from,$to])->orderBy('journal_date', 'ASC')->get() as $record)
                                                        @php
                                                            $reverse = $record->transaction_type == 'DR' ? 'CR' : 'DR';
                                                        @endphp
                                                        @foreach ($r_count = App\JournalRecord::where('journal_id', $record->journal_id)->where('opening_balance_entry', false)->where('transaction_type', $reverse)->get() as $ledger_record)
                                                            @if ($r_count->count() > 1)
                                                                <tr class="trFontSize journalDetails" v-type="main"
                                                                    style="cursor: pointer;" id="{{ $record->journal_id }}">
                                                                    <td>{{ \Carbon\Carbon::parse($ledger_record->journal_date)->format('d/m/Y') }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $ledger_record->account_head }}
                                                                    </td>
                                                                    <td></td>
                                                                    <td class="text-right">
                                                                        @if(!empty($currency->symbole)){{$currency->symbole}}@endif {{ $dr_amount = $record->transaction_type == 'DR' ? $ledger_record->amount : 0 }}
                                                                    </td>
                                                                    <td class="text-right">
                                                                        @if(!empty($currency->symbole)){{$currency->symbole}}@endif {{ $cr_amount = $record->transaction_type == 'CR' ? $ledger_record->amount : 0 }}
                                                                    </td>
                                                                </tr>
                                                            @else
                                                                <tr class="trFontSize journalDetails" v-type="main"
                                                                    style="cursor: pointer;" id="{{ $record->journal_id }}">
                                                                    <td>{{ \Carbon\Carbon::parse($ledger_record->journal_date)->format('d/m/Y') }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $ledger_record->account_head }}
                                                                    </td>
                                                                    <td></td>
                                                                    <td class="text-right">
                                                                        @if(!empty($currency->symbole)){{$currency->symbole}}@endif {{ $dr_amount = $record->transaction_type == 'DR' ? $record->amount : 0 }}
                                                                    </td>
                                                                    <td class="text-right">
                                                                        @if(!empty($currency->symbole)){{$currency->symbole}}@endif {{ $cr_amount = $record->transaction_type == 'CR' ? $record->amount : 0 }}
                                                                    </td>
                                                                </tr>
                                                            @endif

                                                            @php
                                                                $each_ledger_dr = $each_ledger_dr + $dr_amount;
                                                                $each_ledger_cr = $each_ledger_cr + $cr_amount;
                                                            @endphp
                                                        @endforeach
                                                        @endforeach
                                                        <tr>
                                                            <th colspan="2"></th>
                                                            <th colspan="">Balance C/D</th>
                                                            <th class="text-right">
                                                                @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $each_ledger_dr > $each_ledger_cr ? '0' : number_format($each_ledger_cr - $each_ledger_dr, 2,'.','') }}
                                                            </th>

                                                            <th class="text-right">
                                                                @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $each_ledger_dr > $each_ledger_cr ? number_format($each_ledger_dr - $each_ledger_cr, 2,'.','') : '0' }}
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th></th>
                                                            <th>Total</th>
                                                            <th class="text-right">
                                                                @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $each_ledger_dr > $each_ledger_cr ? number_format($each_ledger_dr, 2,'.','') : number_format($each_ledger_cr, 2,'.','') }}
                                                            </th>
                                                            <th class="text-right">
                                                                @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $each_ledger_dr > $each_ledger_cr ? number_format($each_ledger_dr, 2,'.','') : number_format($each_ledger_cr, 2,'.','') }}
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <p></p>
                                                            </td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                               @endif
                                               @if(isset($unique_mst))
                                               <tr>
                                                <th>{{ $unique_mst->master_ac->mst_ac_code }} </th>
                                                <th><a
                                                        href="{{ route('sub-ledger', $unique_mst->master_account_id) }}">{{ $unique_mst->master_ac->mst_ac_head }}</a>
                                                </th>
                                                <th>Opening Balance</th>
                                                <th class="text-right">@if(!empty($currency->symbole)){{$currency->symbole}}@endif {{$each_ledger_dr = $unique_mst->inventoryOpeningBalance($from)>0?$unique_mst->inventoryOpeningBalance($from):'0.00' }}</th>
                                                <th class="text-right">@if(!empty($currency->symbole)){{$currency->symbole}}@endif {{$each_ledger_cr = $unique_mst->inventoryOpeningBalance($from)<0?$unique_mst->inventoryOpeningBalance($from)*(-1):'0.00' }}</th>
                                            </tr>
                                            <tr>
                                                <th>Date</th>
                                                <th>Narration</th>
                                                <th>Ref. No.</th>
                                                <th class="text-right">Debit</th>
                                                <th class="text-right">Credit</th>
                                            </tr>

                                            @foreach (App\JournalRecord::where('journal_id', '!=', 0)->where('master_account_id', $unique_mst->master_account_id)->where('opening_balance_entry', false)->orderBy('journal_date', 'ASC')->whereBetween('journal_date',[$from,$to])->get() as $record)
                                                @php
                                                    $reverse = $record->transaction_type == 'DR' ? 'CR' : 'DR';
                                                @endphp
                                                @foreach ($r_count = App\JournalRecord::where('journal_id', $record->journal_id)->where('opening_balance_entry', false)->where('transaction_type', $reverse)->get() as $ledger_record)
                                                    @if ($r_count->count() > 1)
                                                        <tr class="trFontSize journalDetails" v-type="main"
                                                            style="cursor: pointer;" id="{{ $record->journal_id }}">
                                                            <td>{{ \Carbon\Carbon::parse($ledger_record->journal_date)->format('d/m/Y') }}
                                                            </td>
                                                            <td>
                                                                {{ $ledger_record->account_head }}
                                                            </td>
                                                            <td></td>
                                                            <td class="text-right">
                                                                @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $dr_amount = $record->transaction_type == 'DR' ? $ledger_record->amount : 0 }}
                                                            </td>
                                                            <td class="text-right">
                                                                @if(!empty($currency->symbole)){{$currency->symbole}}@endif {{ $cr_amount = $record->transaction_type == 'CR' ? $ledger_record->amount : 0 }}
                                                            </td>
                                                        </tr>
                                                    @else
                                                        <tr class="trFontSize journalDetails" v-type="main"
                                                            style="cursor: pointer;" id="{{ $record->journal_id }}">
                                                            <td>{{ \Carbon\Carbon::parse($ledger_record->journal_date)->format('d/m/Y') }}
                                                            </td>
                                                            <td>
                                                                {{ $ledger_record->account_head }}
                                                            </td>
                                                            <td></td>
                                                            <td class="text-right">
                                                                @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $dr_amount = $record->transaction_type == 'DR' ? $record->amount : 0 }}
                                                            </td>
                                                            <td class="text-right">
                                                                @if(!empty($currency->symbole)){{$currency->symbole}}@endif {{ $cr_amount = $record->transaction_type == 'CR' ? $record->amount : 0 }}
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    @php
                                                        $each_ledger_dr = $each_ledger_dr + $dr_amount;
                                                        $each_ledger_cr = $each_ledger_cr + $cr_amount;
                                                    @endphp
                                                @endforeach
                                            @endforeach
                                            <tr>
                                                <th colspan="2"></th>
                                                <th colspan="">Balance C/D</th>
                                                <th class="text-right">
                                                    @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $each_ledger_dr > $each_ledger_cr ? '0' : number_format($each_ledger_cr - $each_ledger_dr, 2,'.','') }}
                                                </th>

                                                <th class="text-right">
                                                    @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $each_ledger_dr > $each_ledger_cr ? number_format($each_ledger_dr - $each_ledger_cr, 2,'.','') : '0' }}
                                                </th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th>Total</th>
                                                <th class="text-right">
                                                    @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $each_ledger_dr > $each_ledger_cr ? number_format($each_ledger_dr, 2,'.','') : number_format($each_ledger_cr, 2,'.','') }}
                                                </th>
                                                <th class="text-right">
                                                    @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $each_ledger_dr > $each_ledger_cr ? number_format($each_ledger_dr, 2,'.','') : number_format($each_ledger_cr, 2,'.','') }}
                                                </th>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p></p>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>

                                               @endif



                                        </table>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherPreviewShow">

                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
@endpush
<style>
    #customers {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #customers td,
    #customers th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #customers tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #customers tr:hover {
        background-color: #ddd;
    }

    #customers th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #04AA6D;
        color: white;
        text-transform: uppercase;

    }

    .graph-7 {
        background: url(../img/graphs/graph-7.jpg) no-repeat;
    }

    .graph-image img {
        display: none;
    }

    @media screen {
        div.divFooter {
            display: none;
        }
    }

    @media print {
        div.divFooter {
            position: fixed;
            bottom: 0;
        }
    }

    th {
        text-transform: uppercase;
    }
</style>
<style>
    .print-layout {
        display: none;
    }

    @media print {
        .print-layout {
            display: block;
            overflow: hidden;
        }
    }
</style>
<section class="print-layout">
    @include('layouts.backend.partial.modal-header-info')
    <div class="card-body pt-0 pb-0">
        <h4 class="text-center">General Ledger</h4>
        <h6 class="text-center">{{$from!=null?date('d F Y', strtotime($from)):''}} {{$to!=null? '-'.date('d F Y', strtotime($to)):''}}</h6>
        <table class="table table-sm">
            @if(isset($unique_acc_head))
                 <tr>
                         <th>{{ $unique_acc_head->ac_head->fld_ac_code }}</th>
                         <th><a
                                 href="">{{ $unique_acc_head->ac_head->fld_ac_head }}</a>
                         </th>
                         <th>Opening Balance</th>
                         <th class="text-right">@if(!empty($currency->symbole)){{$currency->symbole}}@endif {{$each_ledger_dr =$unique_acc_head->headOpeningBalance($unique_acc_head->ac_head->id,$from)>0?$unique_acc_head->headOpeningBalance($unique_acc_head->ac_head->id,$from):'0.00'}}</th>
                         <th class="text-right">@if(!empty($currency->symbole)){{$currency->symbole}}@endif{{ $each_ledger_cr  = $unique_acc_head->headOpeningBalance($unique_acc_head->ac_head->id,$from)<0?($unique_acc_head->headOpeningBalance($unique_acc_head->ac_head->id,$from)*(-1)):'0.00'}}</th>
                     </tr>
                     <tr>
                         <th>Date</th>
                         <th>Narration</th>
                         <th>Ref. No.</th>
                         <th class="text-right">Debit</th>
                         <th class="text-right">Credit</th>
                     </tr>

                     @foreach (App\JournalRecord::where('account_head_id', $unique_acc_head->account_head_id)->where('journal_id', '!=', 0)->where('opening_balance_entry', false)->whereBetween('journal_date',[$from,$to])->orderBy('journal_date', 'ASC')->get() as $record)
                     @php
                         $reverse = $record->transaction_type == 'DR' ? 'CR' : 'DR';
                     @endphp
                     @foreach ($r_count = App\JournalRecord::where('journal_id', $record->journal_id)->where('opening_balance_entry', false)->where('transaction_type', $reverse)->get() as $ledger_record)
                         @if ($r_count->count() > 1)
                             <tr class="trFontSize journalDetails" v-type="main"
                                 style="cursor: pointer;" id="{{ $record->journal_id }}">
                                 <td>{{ \Carbon\Carbon::parse($ledger_record->journal_date)->format('d/m/Y') }}
                                 </td>
                                 <td>
                                     {{ $ledger_record->account_head }}
                                 </td>
                                 <td></td>
                                 <td class="text-right">
                                     @if(!empty($currency->symbole)){{$currency->symbole}}@endif {{ $dr_amount = $record->transaction_type == 'DR' ? $ledger_record->amount : 0 }}
                                 </td>
                                 <td class="text-right">
                                     @if(!empty($currency->symbole)){{$currency->symbole}}@endif {{ $cr_amount = $record->transaction_type == 'CR' ? $ledger_record->amount : 0 }}
                                 </td>
                             </tr>
                         @else
                             <tr class="trFontSize journalDetails" v-type="main"
                                 style="cursor: pointer;" id="{{ $record->journal_id }}">
                                 <td>{{ \Carbon\Carbon::parse($ledger_record->journal_date)->format('d/m/Y') }}
                                 </td>
                                 <td>
                                     {{ $ledger_record->account_head }}
                                 </td>
                                 <td></td>
                                 <td class="text-right">
                                     @if(!empty($currency->symbole)){{$currency->symbole}}@endif {{ $dr_amount = $record->transaction_type == 'DR' ? $record->amount : 0 }}
                                 </td>
                                 <td class="text-right">
                                     @if(!empty($currency->symbole)){{$currency->symbole}}@endif {{ $cr_amount = $record->transaction_type == 'CR' ? $record->amount : 0 }}
                                 </td>
                             </tr>
                         @endif

                         @php
                             $each_ledger_dr = $each_ledger_dr + $dr_amount;
                             $each_ledger_cr = $each_ledger_cr + $cr_amount;
                         @endphp
                     @endforeach
                     @endforeach
                     <tr>
                         <th colspan="2"></th>
                         <th colspan="">Balance C/D</th>
                         <th class="text-right">
                             @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $each_ledger_dr > $each_ledger_cr ? '0' : number_format($each_ledger_cr - $each_ledger_dr, 2,'.','') }}
                         </th>

                         <th class="text-right">
                             @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $each_ledger_dr > $each_ledger_cr ? number_format($each_ledger_dr - $each_ledger_cr, 2,'.','') : '0' }}
                         </th>
                     </tr>
                     <tr>
                         <th></th>
                         <th></th>
                         <th>Total</th>
                         <th class="text-right">
                             @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $each_ledger_dr > $each_ledger_cr ? number_format($each_ledger_dr, 2,'.','') : number_format($each_ledger_cr, 2,'.','') }}
                         </th>
                         <th class="text-right">
                             @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $each_ledger_dr > $each_ledger_cr ? number_format($each_ledger_dr, 2,'.','') : number_format($each_ledger_cr, 2,'.','') }}
                         </th>
                     </tr>
                     <tr>
                         <td>
                             <p></p>
                         </td>
                         <td></td>
                         <td></td>
                         <td></td>
                         <td></td>
                     </tr>
            @endif
            @if(isset($unique_mst))
            <tr>
             <th>{{ $unique_mst->master_ac->mst_ac_code }} </th>
             <th><a
                     href="">{{ $unique_mst->master_ac->mst_ac_head }}</a>
             </th>
             <th>Opening Balance</th>
             <th class="text-right">@if(!empty($currency->symbole)){{$currency->symbole}}@endif {{$each_ledger_dr = $unique_mst->inventoryOpeningBalance($from)>0?$unique_mst->inventoryOpeningBalance($from):'0.00' }}</th>
             <th class="text-right">@if(!empty($currency->symbole)){{$currency->symbole}}@endif {{$each_ledger_cr = $unique_mst->inventoryOpeningBalance($from)<0?$unique_mst->inventoryOpeningBalance($from)*(-1):'0.00' }}</th>
         </tr>
         <tr>
             <th>Date</th>
             <th>Narration</th>
             <th>Ref. No.</th>
             <th class="text-right">Debit</th>
             <th class="text-right">Credit</th>
         </tr>

         @foreach (App\JournalRecord::where('journal_id', '!=', 0)->where('master_account_id', $unique_mst->master_account_id)->where('opening_balance_entry', false)->orderBy('journal_date', 'ASC')->whereBetween('journal_date',[$from,$to])->get() as $record)
             @php
                 $reverse = $record->transaction_type == 'DR' ? 'CR' : 'DR';
             @endphp
             @foreach ($r_count = App\JournalRecord::where('journal_id', $record->journal_id)->where('opening_balance_entry', false)->where('transaction_type', $reverse)->get() as $ledger_record)
                 @if ($r_count->count() > 1)
                     <tr class="trFontSize journalDetails" v-type="main"
                         style="cursor: pointer;" id="{{ $record->journal_id }}">
                         <td>{{ \Carbon\Carbon::parse($ledger_record->journal_date)->format('d/m/Y') }}
                         </td>
                         <td>
                             {{ $ledger_record->account_head }}
                         </td>
                         <td></td>
                         <td class="text-right">
                             @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $dr_amount = $record->transaction_type == 'DR' ? $ledger_record->amount : 0 }}
                         </td>
                         <td class="text-right">
                             @if(!empty($currency->symbole)){{$currency->symbole}}@endif {{ $cr_amount = $record->transaction_type == 'CR' ? $ledger_record->amount : 0 }}
                         </td>
                     </tr>
                 @else
                     <tr class="trFontSize journalDetails" v-type="main"
                         style="cursor: pointer;" id="{{ $record->journal_id }}">
                         <td>{{ \Carbon\Carbon::parse($ledger_record->journal_date)->format('d/m/Y') }}
                         </td>
                         <td>
                             {{ $ledger_record->account_head }}
                         </td>
                         <td></td>
                         <td class="text-right">
                             @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $dr_amount = $record->transaction_type == 'DR' ? $record->amount : 0 }}
                         </td>
                         <td class="text-right">
                             @if(!empty($currency->symbole)){{$currency->symbole}}@endif {{ $cr_amount = $record->transaction_type == 'CR' ? $record->amount : 0 }}
                         </td>
                     </tr>
                 @endif

                 @php
                     $each_ledger_dr = $each_ledger_dr + $dr_amount;
                     $each_ledger_cr = $each_ledger_cr + $cr_amount;
                 @endphp
             @endforeach
         @endforeach
         <tr>
             <th colspan="2"></th>
             <th colspan="">Balance C/D</th>
             <th class="text-right">
                 @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $each_ledger_dr > $each_ledger_cr ? '0' : number_format($each_ledger_cr - $each_ledger_dr, 2,'.','') }}
             </th>

             <th class="text-right">
                 @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $each_ledger_dr > $each_ledger_cr ? number_format($each_ledger_dr - $each_ledger_cr, 2,'.','') : '0' }}
             </th>
         </tr>
         <tr>
             <th></th>
             <th></th>
             <th>Total</th>
             <th class="text-right">
                 @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $each_ledger_dr > $each_ledger_cr ? number_format($each_ledger_dr, 2,'.','') : number_format($each_ledger_cr, 2,'.','') }}
             </th>
             <th class="text-right">
                 @if(!empty($currency->symbole)){{$currency->symbole}}@endif  {{ $each_ledger_dr > $each_ledger_cr ? number_format($each_ledger_dr, 2,'.','') : number_format($each_ledger_cr, 2,'.','') }}
             </th>
         </tr>
         <tr>
             <td>
                 <p></p>
             </td>
             <td></td>
             <td></td>
             <td></td>
             <td></td>
         </tr>

            @endif



     </table>
    </div>
    @include('layouts.backend.partial.modal-footer-info')
</section>

@push('js')
    <script>
        $(document).on("click", ".journalDetails", function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            var v_type = $(this).attr('v-type');
            $.ajax({
                url: "{{ URL('voucher-preview-modal') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    v_type: v_type,
                },
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                }
            });
        });
    </script>
@endpush

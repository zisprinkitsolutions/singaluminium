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

        th {
            font-size: 15px !important;
        }

        a {
            color: #ffffff;
        }
        th{
            font-size: 11px !important;
        }
        td{
            font-size: 10px !important;
        }
        .latter-head{
            display: none;
        }
        @media print{
            .latter-head{
                display:inline;
            }
            .nav.nav-tabs ~ .tab-content{
                border: #ffffff !important;
            }
        }
    </style>
    @php
        $grand_total_value = 0;
        $grand_total_pcs = 0;
    @endphp
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.accounting._header',['activeMenu' => 'financial_reports'])
                <div class="tab-content bg-white">
                    <div class="tab-pane active p-2">
                        <div class="content-body">
                            <section id="widgets-Statistics">
                                <div class="d-flex justify-content-between align-items-center print-hideen">
                                    @include('clientReport.report._financial_report_subheader', [ 'activeMenu' => 'payable_reports'])
                                </div>

                                <div class="cardStyleChange">
                                    <div class="latter-head">
                                        @include('layouts.backend.partial.modal-header-info')
                                    </div>
                                    <div class="card-body px-1 py-0 mt-1 print-hideen">
                                        <div class="row">

                                            <div class="col-md-9 padding-right">
                                                <form action="" method="GET">
                                                    <div class="row">
                                                        <div class="row form-group col-md-4 mr-0">
                                                            <select name="party_id" id="party_id" class="form-control common-select2">
                                                                <option value="">Select...</option>
                                                                @foreach ($partys as $item)
                                                                    <option value="{{$item->id}}" {{$party_info!=null? ($item->id==$party_info?'selected':''):''}}>{{$item->pi_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" class="inputFieldHeight form-control datepicker" name="date" placeholder="Single Date" id="date" autocomplete="off">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" class="inputFieldHeight form-control datepicker" name="from" placeholder="From" id="from" autocomplete="off">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" class="inputFieldHeight form-control datepicker" name="to" placeholder="To" id="to" autocomplete="off">
                                                        </div>
                                                        <div class="col-md-2 text-right">
                                                            <button type="submit"
                                                                class="btn mSearchingBotton mb-2 formButton" title="Search">
                                                                <div class="d-flex">
                                                                    <div class="formSaveIcon">
                                                                        <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                            width="25">
                                                                    </div>
                                                                    <div><span>Search</span></div>
                                                                </div>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                            <div class="col-md-3 text-right col-right-padding">
                                                <button type="button" class="btn mExcelButton formButton mr-1"
                                                    title="Export"
                                                    onclick="exportTableToCSV('general-ledger-{{ date('d M Y') }}.csv')">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{ asset('assets/backend/app-assets/icon/excel-icon.png') }}"
                                                                width="25">
                                                        </div>
                                                        <div><span>Excel</span></div>
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
                                    </div>
                                    <div class="card-body p-0" style="padding: 0 !important;">
                                        @php
                                            if ($party_info) {
                                                $partys = $partys->where('id', $party_info);
                                                $party_change = App\PartyInfo::find($party_info);
                                            } else {
                                                $partys = $partys;
                                            }
                                        @endphp
                                        <table  class="table table-sm table-bordered table-hover">
                                            @if ($party_info)
                                                <tr>
                                                    <th colspan="7" class="text-center">
                                                        <h6>{{$party_change->pi_name}} @if(!empty($currency->licence_name)){{$currency->licence_name}} @endif-{{$party_change->trn_no}}</h6>
                                                        <h6>{{$to!=null?date('d F Y', strtotime($to)):''}} {{$to!=null? '-'.date('d F Y', strtotime($to)):''}}</h6>
                                                    </th>
                                                </tr>
                                            @endif
    
                                            <tr class="trFontSize">
                                                <th>Date</th>
                                                <th style="width: 150px">Account Head</th>
                                                <th>Narration</th>
                                                <th>Debit <small>({{$currency->symbole}})</small></th>
                                                <th>Credit <small>({{$currency->symbole}})</small></th>
                                                <th>Balance <small>({{$currency->symbole}})</small></th>
                                            </tr>
                                            @foreach ($partys as $party)
                                                @php
                                                    $balance=0;
                                                @endphp
                                                {{-- @if ($to != null)
                                                    @php
                                                    $balance=App\PartyInfo::opening($party,$to);
                                                    @endphp
                                                    <tr class="trFontSize">
                                                        <td colspan="3">Opening Balance</td>
                                                        <td>{{$balance>=0? $balance:0}}</td>
                                                        <td>{{$balance>=0? 0:(-1)*$balance}}</td>
                                                        <td>{{$balance>=0? 'DR '.$balance:'CR '.((-1)*$balance)}} </td>
                                                    </tr>
                                                @endif --}}
                                                @foreach ($party->journal_record_payable($party->id, $date, $from, $to) as $record)
                                                    @php
                                                        $journal=App\Journal::find($record->journal_id);
                                                    @endphp
                                                    <tr class="trFontSize journalDetails" v-type="main" style="cursor: pointer;" id="{{ $record->journal_id }}">
                                                        <td>{{date('d-M-Y', strtotime($journal->date))}}</td>
                                                        @if($journal->records()->whereIn('account_head_id',[1,27])->where('transaction_type','DR')->first())
                                                            <td>{{$journal->records()->where('transaction_type','DR')->first()? $journal->records()->where('transaction_type','DR')->first()->ac_head->fld_ac_head:'Not Found'}} </td>
                                                        @else
                                                            <td>{{$journal->records()->where('transaction_type','CR')->first()? $journal->records()->where('transaction_type','CR')->first()->ac_head->fld_ac_head:'Not Found'}} </td>
                                                        @endif
                                                        <td>
                                                            @if($journal->purchaseExp!=null)
                                                            By Puchase Invoice {{$journal->purchaseExp->invoice_no}} dated {{date('d-M-Y', strtotime($journal->date))}}
                                                            @elseif($journal->jobProject !=null)
                                                            By Project {{$journal->jobProject->project_name}}
    
                                                            @elseif($journal->receipt !=null)
                                                            By Receipt {{$journal->receipt->receipt_no}} for project {{$journal->receipt->job_project->project_name}}
                                                            @elseif($journal->payment !=null)
                                                            By Payment {{$journal->payment->payment_no}}
                                                            @else
                                                            By Journal: {{'0'.$journal->journal_no}}; dated {{date('d-M-Y', strtotime($journal->date))}}
    
                                                            @endif
    
                                                        </td>
                                                        @php
                                                            $cr=$journal->records()->where('account_head_id','5')->where('transaction_type','CR')->sum('amount');
                                                            $dr=$journal->records()->where('account_head_id','5')->where('transaction_type','DR')->sum('amount');
                                                            $cr2=$journal->records()->where('account_head_id','3')->where('transaction_type','CR')->sum('amount');
                                                            $dr2=$journal->records()->where('account_head_id','3')->where('transaction_type','DR')->sum('amount');
                                                            $cr3=$journal->records()->where('account_head_id','853')->where('transaction_type','CR')->sum('amount');
    
                                                            $dr3=$journal->records()->where('account_head_id','853')->where('transaction_type','DR')->sum('amount');
                                                            $amount = $dr-$cr2+$dr2-$cr3+$dr3 - $cr;
                                                            $tdr=$dr+$dr2+$dr3;
                                                            $tcr=$cr2+$cr3+$cr;
                                                            $balance=$balance-$cr+$dr-$cr2+$dr2-$cr3+$dr3;
                                                        @endphp
                                                        @if($tdr==0 && $tcr==0)
                                                        @php
                                                            $t_dr=$journal->records()->whereIn('account_head_id',[1,2])->where('transaction_type','DR')->sum('amount');
                                                            $t_cr=$journal->records()->whereIn('account_head_id',[1,2])->where('transaction_type','CR')->sum('amount');
                                                        @endphp
                                                        <td>{{$t_dr}}</td>
                                                        <td>{{$t_cr}}</td>
    
                                                        @elseif ($amount<0)
                                                            <td>0</td>
                                                            <td>{{$amount*(-1)}}</td>
                                                        @else
                                                            <td>{{$amount}}</td>
                                                            <td>0</td>
                                                        @endif
                                                        <td>{{$balance>=0? 'DR '.$balance:'CR '.((-1)*$balance)}} </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
    
                                        </table>
                                    </div>
                                    <div class="latter-head">
                                        @include('layouts.backend.partial.modal-footer-info')
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        $(document).on('change', '#date', function(e){
            $("#from").val('');
            $("#to").val('');
        });
        $(document).on('change', '#from', function(e){
            $("#date").val('');
        });
        $(document).on('change', '#to', function(e){
            $("#date").val('');
        });
    </script>
@endpush
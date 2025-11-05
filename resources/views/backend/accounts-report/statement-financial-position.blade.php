

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
        font-size: 11px !important;
        padding-top: 2px !important;
        padding-bottom: 2px !important;
        background: #fff !important;
    }

    th{
        font-size: 14px !important;
    }
    #widgets-Statistics{
        padding-right: 300px !important;
    }
    @media print{
        body {-webkit-print-color-adjust: exact;}
        .row{
            display: flex;
        }
        .col-md-6{
            max-width: 50% !important;
        }
        .print-hideen{
            display: none !important;
        }
        .nav.nav-tabs ~ .tab-content{
            border: #fff;
        }
        .bx{
            display: none !important;
        }
        .table-bg{
            background: #e3e3e3 !important;
            print-color-adjust: exact; 
        }
        .td-border{
            border-left: 1px solid #fff !important
        }
        #widgets-Statistics{
            padding-right: 150px !important;
            padding-left: 150px !important;
        }
        .subhead{
            display: none !important;
        }
    }
    .card-body {
        min-height: 1px;
        padding: 0rem !important;
    }
</style>

<div class="app-content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.report._header',['activeMenu' => 'tax-report'])
            <div class="tab-content bg-white">
                <div class="tab-pane active p-2">
                    <div class="content-body">
                        <div class="pl-2 print-hideen">
                            @include('clientReport.report._subhead_tax_report', ['activeMenu' => 'financial_position',])
                        </div>
                        <section id="widgets-Statistics" class="col-md-12">
                            <div class="row mt-1 print-hideen" style="margin-left: 5px !important">
                                <div class="col-md-8">
                                    <form action="" method="GET" class="d-flex row">

                                        <div class="row form-group col-md-5 pr-2">
                                            <input type="text" class="form-control inputFieldHeight datepicker" value="{{$from_date?date('d/m/Y', strtotime($from_date)):null}}" placeholder="From Date" required name="from_date" id="from_date" autocomplete="off">
                                        </div>
                                        <div class="row form-group col-md-5">
                                            <input type="text" value="{{$from_date?date('d/m/Y', strtotime($to_date)):null}}" class="form-control inputFieldHeight datepicker" placeholder="To Date" required name="to_date" id="to_date" autocomplete="off">
                                        </div>

                                        <div class="col-md-2">
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
                                <div class="col-md-4 text-right d-flex">
                                    <a href="#" class="btn btn_create mPrint formButton mb-2 mr-1 text-right" title="Print" onclick="window.print()">
                                        <div class="d-flex">
                                            <div class="formSaveIcon">
                                                <img src="{{ asset('assets/backend/app-assets/icon/print-icon.png') }}"
                                                    width="25">
                                            </div>
                                            <div><span>Print</span></div>
                                        </div>
                                    </a>
                                    <a href="{{route('conporate-tax-details', ['from_date'=>$from_date, 'to_date'=> $to_date])}}" class="text-right btn mb-2 btn_create mPrint formButton" title="Details Print" target="_blank">
                                        <div class="d-flex">
                                            <div class="formSaveIcon">
                                                <img src="{{ asset('assets/backend/app-assets/icon/print-icon.png') }}" width="25">
                                            </div>
                                            <div><span>Details Print</span></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body pt-0 pb-0">
                                <table class="table table-bordered table-sm">
                                    <thead class="thead">
                                        <tr class="text-center">
                                            <th colspan="2">
                                                <h5>Corporate Tax Report</h5>
                                                <p class="mb-0">Period: {{date('d/m/Y', strtotime($from_date)) .'-'. date('d/m/Y', strtotime($to_date))}}</p>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="purch-body">
                                        
                                        @php
                                            $opening_revenue = $profitLosse_results[0]['net_amount']<0?$profitLosse_results[0]['net_amount']*-1:$profitLosse_results[0]['net_amount'];
                                            $expendiure =$profitLosse_results[1]['net_amount']<0?$profitLosse_results[1]['net_amount']*-1:$profitLosse_results[1]['net_amount'];
                                            $gross_loss_profit = $opening_revenue-$expendiure;
                                            $non_0perating = $profitLosse_results[2]['net_amount']<0?$profitLosse_results[2]['net_amount']*-1:$profitLosse_results[2]['net_amount'];
                                            $salaris =  $profitLosse_results[3]['net_amount']<0?$profitLosse_results[3]['net_amount']*-1:$profitLosse_results[3]['net_amount'];
                                            $depreciation = $profitLosse_results[4]['net_amount']<0?$profitLosse_results[4]['net_amount']*-1:$profitLosse_results[4]['net_amount'];
                                            $fine_penalties = $profitLosse_results[5]['net_amount']<0?$profitLosse_results[5]['net_amount']*-1:$profitLosse_results[5]['net_amount'];
                                            $donation = $profitLosse_results[6]['net_amount']<0?$profitLosse_results[6]['net_amount']*-1:$profitLosse_results[6]['net_amount'];
                                            $entertainment = $profitLosse_results[7]['net_amount']<0?$profitLosse_results[7]['net_amount']*-1:$profitLosse_results[7]['net_amount'];
                                            $other_expense = $profitLosse_results[8]['net_amount']<0?$profitLosse_results[8]['net_amount']*-1:$profitLosse_results[8]['net_amount'];
                                            $non_operating_expenses = $non_0perating+$salaris+$depreciation+$fine_penalties+$donation+$entertainment+$other_expense;
                                            $non_operating_revenue = $profitLosse_results[9]['net_amount']<0?$profitLosse_results[9]['net_amount']*-1:$profitLosse_results[9]['net_amount'];
                                            $divident_received = $profitLosse_results[10]['net_amount']<0?$profitLosse_results[10]['net_amount']*-1:$profitLosse_results[10]['net_amount'];
                                            $other_non_operating_revenue = $profitLosse_results[11]['net_amount']<0?$profitLosse_results[11]['net_amount']*-1:$profitLosse_results[11]['net_amount'];
                                            $interest_income = $profitLosse_results[12]['net_amount']<0?$profitLosse_results[12]['net_amount']*-1:$profitLosse_results[12]['net_amount'];
                                            $interest_expenditure = $profitLosse_results[13]['net_amount']<0?$profitLosse_results[13]['net_amount']*-1:$profitLosse_results[13]['net_amount'];
                                            $net_interest_income = $interest_income-$interest_expenditure;
                                            $gain_disposal_asset = $profitLosse_results[14]['net_amount']<0?$profitLosse_results[14]['net_amount']*-1:$profitLosse_results[14]['net_amount'];
                                            $loss_disposal_asset = $profitLosse_results[15]['net_amount']<0?$profitLosse_results[15]['net_amount']*-1:$profitLosse_results[15]['net_amount'];
                                            $net_gain_disposal_assets = $gain_disposal_asset - $loss_disposal_asset;
                                            $gain_foreing_exchange = $profitLosse_results[16]['net_amount']<0?$profitLosse_results[16]['net_amount']*-1:$profitLosse_results[16]['net_amount'];
                                            $loss_foreing_exchange = $profitLosse_results[17]['net_amount']<0?$profitLosse_results[17]['net_amount']*-1:$profitLosse_results[17]['net_amount'];
                                            $net_gains_foreign_exchange = $gain_foreing_exchange - $loss_foreing_exchange;
                                            $net_profit = ($gross_loss_profit+$non_operating_revenue+$divident_received+$other_non_operating_revenue+$net_interest_income) - ($non_operating_expenses);
                                        @endphp
                                        
                                        <tr>
                                            <th class="pl-1" style="background: #0000000f !important; font-size: 11px !important; font-weight: bold">Statement of Financial Position</th>
                                            <th class="text-right pr-1"  style="background: #0000000f !important; font-size: 11px !important; font-weight: bold">Current Period (AED)</th>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="pl-1"><strong>Assets</strong></td>
                                        </tr>
                                        <tr class="head-details" data-target="{{$financial_results[0]['account_type']}}" data-type="{{$financial_results[0]['type']}}">
                                            <td style="font-size: 11px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    {{$financial_results[0]['title']}}
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($total_current_asset = $financial_results[0]['net_amount']<0?$financial_results[0]['net_amount']*-1:$financial_results[0]['net_amount'],1, '.', '')}}</td>
                                        </tr>
                                        <tr class="subhead d-none"></tr>
                                        <tr>
                                            <td colspan="2" class="pl-1"><strong>Non-Current Assets</strong></td>
                                        </tr>
                                        <tr class="head-details" data-target="{{$financial_results[1]['account_type']}}" data-type="{{$financial_results[1]['type']}}">
                                            <td style="font-size: 11px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    {{$financial_results[1]['title']}}
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($property_plant = $financial_results[1]['net_amount']<0?$financial_results[1]['net_amount']*-1:$financial_results[1]['net_amount'],1, '.', '')}}</td>
                                        </tr>
                                        <tr class="subhead d-none"></tr>

                                        <tr class="head-details" data-target="{{$financial_results[2]['account_type']}}" data-type="{{$financial_results[2]['type']}}">
                                            <td style="font-size: 11px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    {{$financial_results[2]['title']}}
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($intangible_assets = $financial_results[2]['net_amount']<0?$financial_results[2]['net_amount']*-1:$financial_results[2]['net_amount'],1, '.', '')}}</td>
                                        </tr>
                                        <tr class="subhead d-none"></tr>
                                        <tr class="head-details" data-target="{{$financial_results[3]['account_type']}}" data-type="{{$financial_results[3]['type']}}">
                                            <td style="font-size: 11px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    {{$financial_results[3]['title']}}
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($financial_asset = $financial_results[3]['net_amount']<0?$financial_results[3]['net_amount']*-1:$financial_results[3]['net_amount'],1, '.', '')}}</td>
                                        </tr>
                                        <tr class="subhead d-none"></tr>
                                        <tr class="head-details" data-target="{{$financial_results[4]['account_type']}}" data-type="{{$financial_results[4]['type']}}">
                                            <td style="font-size: 11px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    {{$financial_results[4]['title']}}
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($other_non_current_asset = $financial_results[4]['net_amount']<0?$financial_results[4]['net_amount']*-1:$financial_results[4]['net_amount'],1, '.', '')}}</td>
                                        </tr>
                                        <tr class="subhead d-none"></tr>
                                        
                                        <tr>
                                            <td class="pl-2" style="font-size: 11px !important;"><strong>Total Non-Current Asset (AED)</strong></td>
                                            <td class="text-right pr-1" style="background: #b3b1b1b4 !important">{{number_format($total_non_current_asset = $property_plant+$intangible_assets+$financial_asset+$other_non_current_asset,1, '.', '')}}</td>
                                        </tr>
                                        <tr>
                                            <td class="pl-2" style="font-size: 11px !important;"><strong>Total Asset (AED)</strong></td>
                                            <td class="text-right pr-1" style="background: #b3b1b1b4 !important">{{number_format($total_non_current_asset+$total_current_asset,1, '.', '')}}</td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="2" style="background: #0000000f !important; font-size: 11px !important; font-weight: bold" class="pl-1">Liability</td>
                                        </tr>

                                        <tr class="head-details" data-target="{{$financial_results[5]['account_type']}}" data-type="{{$financial_results[5]['type']}}">
                                            <td style="font-size: 11px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    {{$financial_results[5]['title']}}
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($total_current_liabilty = $financial_results[5]['net_amount']<0?$financial_results[5]['net_amount']*-1:$financial_results[5]['net_amount'],1, '.', '')}}</td>
                                        </tr>
                                        <tr class="subhead d-none"></tr>
                                        <tr class="head-details" data-target="{{$financial_results[6]['account_type']}}" data-type="{{$financial_results[6]['type']}}">
                                            <td style="font-size: 11px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    {{$financial_results[6]['title']}}
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($total_non_current_liability = $financial_results[6]['net_amount']<0?$financial_results[6]['net_amount']*-1:$financial_results[6]['net_amount'],1, '.', '')}}</td>
                                        </tr>
                                        <tr class="subhead d-none"></tr>
                                        <tr>
                                            <td class="pl-2" style="font-size: 11px !important;"><strong>Total Liability (AED)</strong></td>
                                            <td class="text-right pr-1" style="background: #b3b1b1b4 !important">{{number_format($total_liabilty = $total_current_liabilty+$total_non_current_liability,1, '.', '')}}</td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="2" style="background: #0000000f !important; font-size: 11px !important; font-weight: bold" class="pl-1">Equity</td>
                                        </tr>
                                        <tr class="head-details" data-target="{{$financial_results[7]['account_type']}}" data-type="{{$financial_results[7]['type']}}">
                                            <td style="font-size: 11px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    {{$financial_results[7]['title']}}
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($total_share_capital = $financial_results[7]['net_amount']<0?$financial_results[7]['net_amount']*-1:$financial_results[7]['net_amount'],1, '.', '')}}</td>
                                        </tr>
                                        <tr class="subhead d-none"></tr>
                                        <tr class="head-details" data-target="{{$financial_results[8]['account_type']}}" data-type="{{$financial_results[8]['type']}}">
                                            <td style="font-size: 11px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    {{$financial_results[8]['title']}}
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($retained_earning = $net_profit,1, '.', '')}}</td>
                                        </tr>
                                        <tr class="subhead d-none"></tr>
                                        <tr class="head-details" data-target="{{$financial_results[9]['account_type']}}" data-type="{{$financial_results[9]['type']}}">
                                            <td style="font-size: 11px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    {{$financial_results[9]['title']}}
                                                </div>
                                            </td>
                                            <td class="text-right pr-1">{{number_format($other_equity = $financial_results[9]['net_amount']<0?$financial_results[9]['net_amount']*-1:$financial_results[9]['net_amount'],1, '.', '')}}</td>
                                        </tr>
                                        <tr class="subhead d-none"></tr>
                                        <tr>
                                            <td class="pl-2" style="font-size: 11px !important;"><strong>Total Equity (AED)</strong></td>
                                            <td class="text-right pr-1" style="background: #b3b1b1b4 !important">{{number_format($total_equity = $total_share_capital+$retained_earning+$other_equity,1, '.', '')}}</td>
                                        </tr>
                                        
                                        <tr>
                                            <td style="background: #0000000f !important; font-size: 15px !important; font-weight: bold">Total Equity and Liability (AED)</td>
                                            <td class="text-right pr-1;" style="background: #0000000f !important; font-size: 11px !important; font-weight: bold">{{number_format($total_liabilty+$total_equity,1, '.', '')}}</td>
                                        </tr>
                                        <tr class="head-details" data-target="" data-type="">
                                            <td style="font-size: 11px;">
                                                <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                    <i class='bx bx-plus'></i>
                                                    <i class='bx bx-minus d-none'> </i>
                                                    Average number of employees during the TaxPeriod
                                                </div>
                                            </td>
                                            <td class="text-right" style="padding: 0 !important;"><input type="number" step="any" style=" width: 100% !important; text-align: right; padding-right: 10px;"padding-right: 15px;"></td>
                                        </tr>
                                        <tr class="subhead d-none"></tr>
                                    </tbody>
                                </table>
                                <p class="mb-0">
                                    <em>*** We confirm the above figures towards UAE  FTA Corporate Tax submission for the period of Jun 01, 2023 - May 31, 2024</em>
                                </p>
                            </div>
                            @include('layouts.backend.partial.modal-footer-info')
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="voucherPreviewShow">

            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        $(document).on('click', '.head-details', function () {
            var $this = $(this);
            var $subheadRow = $this.next('tr.subhead');

            // Toggle visibility and icons
            $this.find('.bx').toggleClass('d-none');
            $this.find('td').toggleClass('active-bg');
            $subheadRow.toggleClass('d-none');

            // Check if content is already loaded
            if ($subheadRow.data('loaded')) {
                return; // Exit if content is already loaded
            }

            // Show loading overlay
            $('#loading-overlay').show();
            let percentage = 0;
            const interval = setInterval(() => {
                percentage += 20;
                if (percentage > 95) percentage = 95;
                $('#loading-percentage').text(percentage + '%');
            }, 100);

            // Fetch data via AJAX
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var account_type = $this.data('target');
            var type = $this.data('type');
            var _token = $('input[name="_token"]').val();

            $.ajax({
                url: "{{ route('sub-head-details') }}",
                method: 'post',
                data: {
                    from_date: from_date,
                    to_date: to_date,
                    account_type: account_type,
                    type: type,
                    _token: _token,
                },
                success: function (response) {
                    // Update loading percentage
                    $('#loading-percentage').text('100%');

                    // Insert the response into the subhead row
                    $subheadRow.html(response);

                    // Mark the row as loaded
                    $subheadRow.data('loaded', true);
                },
                error: function () {
                    // Handle error if needed
                },
                complete: function () {
                    // Hide loading overlay and reset percentage
                    clearInterval(interval);
                    $('#loading-overlay').hide();
                    $('#loading-percentage').text('0%');
                }
            });
        });
        $(document).on('click', '.tax-sub-head-details', function () {
            var $this = $(this);
            $('#loading-overlay').show();
            let percentage = 0;
            const interval = setInterval(() => {
            percentage += 20;
            if (percentage > 95) percentage = 95;
                $('#loading-percentage').text(percentage + '%');
            }, 100);

            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var account_id = $(this).attr('id');
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{route('tax-sub-head-details')}}",
                method: 'post',
                data: {
                    from_date: from_date,
                    to_date: to_date,
                    account_id: account_id,
                    _token: _token,
                },
                success: function(response) {
                    $('#loading-percentage').text('100%');
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show');
                },
                error: function() {
                },
                complete: function () {
                    clearInterval(interval);
                    $('#loading-overlay').hide();
                    $('#loading-percentage').text('0%');
                }
            });
        });
    </script>
@endpush
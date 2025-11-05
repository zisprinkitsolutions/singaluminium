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
        thead {
            background: #34465b;
            color: #fff !important;
        }
        a {
            color: #ffffff;
        }
        th{
            font-size: 14px !important;
            color: #fff !important;
        }
        td{
            font-size: 13px !important;
        }
        .latter-head{
            display: none;
        }
        .header-info{
            top: 0 !important;
        }

        .loading-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }

        td .sort-indicator desc {
            font-size: 12px;
            margin-left: 8px;
            color: #888;
            opacity: 0.5;
        }

        td .sort-indicator.asc::after {
            content: "▲";
        }

        td .sort-indicator.desc::after {
            content: "▼";
        }

        td .sort-indicator desc {
            opacity: 1;
            color: #007bff;
        }

        .circle {
            width: 30px;
            height: 30px;
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @media print{
            .latter-head{
                display:inline;
            }
            .header-info{
                top: 0 !important;
                position: fixed;
            }
            .print-content{
                top: 100 !important;
                position: fixed;
            }
            .nav.nav-tabs ~ .tab-content{
                border: #ffffff !important;
            }
            .padding-top{
                padding-top: 80px !important;
            }
            .print-hideen{
                display: none !important;
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
                @include('clientReport.report._header',['activeMenu' => 'financial_reports'])
                <div class="tab-content bg-white">
                    <div class="tab-pane active p-2">
                        <div class="content-body">
                            <section id="widgets-Statistics">
                                <div class="d-flex justify-content-between align-items-center print-hideen">
                                    @include('clientReport.report._financial_report_subheader', [ 'activeMenu' => 'missing'])
                                </div>

                                <div class="cardStyleChange">

                                    <div class="card-body mt-1 print-hideen">
                                        <form action="" method="GET">
                                            <div class="d-flex">
                                                <div class="form-group" style="width:15%;">
                                                    <label for="search"> Search </label>
                                                    <input type="text" name="search_query" value="{{$search_query}}" class="form-control inputFieldHeight" placeholder="Search by invoice no">
                                                </div>


                                                <div class="from-group" style="width:10%; margin-left:8px;">
                                                    <label for=""> Month </label>
                                                    <select name="month" class="form-control inputFieldHeight" id="month">
                                                        <option value=""> Select </option>
                                                            @foreach (range(1, 12) as $m)
                                                            <option value="{{ $m}}" {{$month == $m ? 'selected' : ' '}}>
                                                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="from-group" style="width:10%; margin-left:8px;">
                                                    <label for=""> Year </label>
                                                    <select name="year" class="form-control inputFieldHeight" id="year">
                                                        <option value="">Select</option>
                                                        @foreach (range(date('Y'), date('Y') - 10) as $y)
                                                            <option value="{{ $y }}" {{$year == $y ? 'selected' : ' '}}>{{ $y }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                                <div class="from-group" style="width:10%; margin-left:8px;">
                                                    <div class="form-group">
                                                        <label for=""> From Date </label>
                                                        <input type="text" class="datepicker form-control inputFieldHeight" name="from" placeholder="from" value="{{$from ? date('d/m/Y', strtotime($from)) : null}}" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="from-group" style="width:10%; margin-left:8px;">
                                                    <div class="form-group">
                                                        <label for=""> To Date </label>
                                                        <input type="text" class="datepicker form-control inputFieldHeight" name="to" placeholder="to" value="{{$to ? date('d/m/Y', strtotime($to)) : null}}" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-end" style="margin-left:8px;">
                                                    <button type="submit"
                                                        class="btn mSearchingBotton mb-2 mt-2 formButton" title="Search">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                    width="25">
                                                            </div>
                                                        </div>
                                                    </button>

                                                    <a href="{{route('sale-reports')}}" type="submit" style="margin-left: 4px;"
                                                        class="btn mt-2 btn-primary mb-2 formButton" title="Search">
                                                        <div class="d-flex">
                                                            Default
                                                        </div>
                                                    </a>

                                                    <a href="#" class="btn btn_create mPrint formButton mt-2 mb-2" title="Print"
                                                        onclick="media_print('print_section')" style="margin-left:6px;">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/print-icon.png') }}"
                                                                    width="25">
                                                            </div>
                                                        </div>
                                                    </a>

                                                    <button data-url="{{route('sale-report-pdf')}}" type="button" style="margin-left: 4px;"
                                                        class="btn mt-2 mPrint mb-2 formButton download-pdf" title="PDF / Print">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <i class='bx bxs-file-pdf'></i>
                                                            </div>
                                                        </div>
                                                    </button>

                                                    <button data-url="{{route('sale-report-extend-pdf')}}" data-type="extend" type="button" style="margin-left: 4px;"
                                                        class="btn mt-2 mPrint mb-2 formButton download-pdf" title="Extended Pdf / Print">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <i class='bx bxs-file-pdf'></i>
                                                            </div>
                                                        </div>
                                                    </button>

                                                    <button data-url="{{route('sale-report-excel')}}" type="button" style="margin-left: 4px;"
                                                        class="btn mt-2 mPrint mb-2 formButton download-pdf" title="Excel">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/excel-icon.png') }}"
                                                                    width="25">
                                                            </div>
                                                        </div>
                                                    </button>

                                                    <button data-url="{{route('sale-report-extend-excel')}}" data-type="extend-excel" type="button" style="margin-left: 4px;"
                                                        class="btn mt-2 mPrint mb-2 formButton download-pdf" title="Extended Excel">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/excel-icon.png') }}"
                                                                    width="25">
                                                            </div>
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="col-md-12 text-right col-right-padding">
                                            <button type="button" class="btn mExcelButton formButton mr-1"
                                                title="Export"
                                                onclick="exportTableToCSV('sale-report-{{ date('d M Y') }}.csv')">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img src="{{ asset('assets/backend/app-assets/icon/excel-icon.png') }}"
                                                            width="25">
                                                    </div>
                                                    <div><span>Excel</span></div>
                                                </div>
                                            </button>
                                            <a href="#" class="btn btn_create mPrint formButton" title="Print"
                                                onclick="media_print('print_section')">
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
                                    <div class="card-body" style="padding: 0 !important;" id="print_section">
                                        <div class="latter-head header-info">
                                            @include('layouts.backend.partial.modal-header-info')
                                        </div>

                                        <h4 class="text-center padding-top">Sale Reports</h4>

                                        @if($from && $to)
                                        <h6 class="text-center">{{date('d M Y', strtotime($from)).' To '.date('d M Y', strtotime($to))}}</h6>
                                        @elseif ($from)
                                            <h6 class="text-center">{{date('d M Y', strtotime($from))}}</h6>
                                        @endif
                                        <table class="table mb-0 table-sm">
                                            <thead  class="thead">
                                                <tr>
                                                    <th style="width:7%; text-align:center;"> SL No </th>
                                                    <th style="width: 12%">Date</th>
                                                    <th style="width: 15%">Invoice No</th>
                                                    <th style="width: 33%">Party Name</th>
                                                    <th style="width: 20%">Amount <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                                    <th style="width: 13%">Type</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @php
                                                    foreach ($invoice_group as $prefix => &$invoices) {
                                                        usort($invoices, function ($a, $b) {
                                                            return $a['number_part'] <=> $b['number_part']; // Numeric comparison
                                                        });
                                                    }
                                                    unset($invoices);
                                                    $index = 1;
                                                    $total = 0;
                                                @endphp

                                                @foreach ($invoice_group as $key => $prefix)
                                                    @foreach ($prefix as $subKey => $entry)
                                                    @php
                                                    $total_length = count($prefix) - 1;
                                                    $difference = 0;

                                                    if ($subKey < $total_length) {

                                                        $currentInvoice = $entry['invoice_data']->invoice_no;
                                                        $nextInvoice = $prefix[$subKey + 1]['invoice_data']->invoice_no;


                                                        preg_match_all('/\d+/', $currentInvoice, $matches1);
                                                        $currentNumber = isset($matches1[0]) ? (int) end($matches1[0]) : 0;
                                                        $currentPrefix = preg_replace('/[^A-Z]/', '', $currentInvoice);

                                                        preg_match_all('/\d+/', $nextInvoice, $matches2);
                                                        $nextNumber = isset($matches2[0]) ? (int) end($matches2[0]) : 0;
                                                        $nextPrefix = preg_replace('/[^A-Z]/', '', $nextInvoice);

                                                        if ($currentPrefix === $nextPrefix) {
                                                            $difference = abs($nextNumber - $currentNumber);
                                                        }
                                                    }

                                                    $total += $entry['invoice_data']->total_budget;
                                                @endphp

                                                <tr class="sale_view" id="{{ $entry['invoice_data']->id }}">
                                                    <td class="text-center">{{ $index++ }}</td>
                                                    <td>{{ date('d/m/Y', strtotime($entry['invoice_data']->date)) }}</td>
                                                    <td>{{ $entry['invoice_data']->invoice_no }}</td>
                                                    <td>{{ $entry['invoice_data']->pi_name }}</td>
                                                    <td>{{ $entry['invoice_data']->total_budget }}</td>
                                                    <td>{{ $entry['invoice_data']->invoice_type }}</td>
                                                </tr>

                                                {{-- Insert Missing Row if Difference is Greater Than 1 --}}
                                                @if ($difference > 1 && $subKey < $total_length)
                                                    <tr>
                                                        <td class="bg-danger text-white text-center">{{ $index++ }}</td>
                                                        <td class="bg-danger text-white text-center" colspan="5">Missing</td>
                                                    </tr>
                                                @endif
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                            {{-- <tbody>
                                                @php
                                                    $total_length = count($taxInvoces) - 1;
                                                    $index = 1;
                                                    $total = 0;
                                                @endphp
                                                @foreach ($taxInvoces as $key => $item)
                                                    @php
                                                    $difference = 0;
                                                    if($total_length > $key ){
                                                        $matches1 = [];
                                                        $matches2 = [];
                                                        preg_match_all('/\d+/', $item->invoice_no, $matches1);
                                                        $first_prefix = preg_replace('/[^A-Z]/', '', $item->invoice_no);
                                                        $first_number = end($matches1[0]);
                                                        preg_match_all('/\d+/', $taxInvoces[$key + 1]->invoice_no, $matches2);
                                                        $second_prefix = preg_replace('/[^A-Z]/','', $taxInvoces[$key + 1]->invoice_no);
                                                        $second_number = end($matches2[0]);
                                                        $difference = abs($second_number-$first_number);
                                                    }
                                                    $total += $item->total_budget;
                                                    @endphp
                                                    <tr class="sale_view"  id="{{$item->id}}">
                                                        <td class="text-center"> {{$index++}}</td>
                                                        <td>{{date('d/m/Y',strtotime($item->date))}}</td>
                                                        <td>{{$item->invoice_no}}</td>
                                                        <td>{{$item->pi_name}}</td>
                                                        <td>{{$item->total_budget}}</td>
                                                        <td>{{$item->invoice_type}}</td>
                                                    </tr>
                                                      @if ($difference > 1 && $subKey < $total_length)
                                                    @for ($i = 1; $i < $difference; $i++)
                                                        @php
                                                            $missing_number = $currentNumber + $i; // Increment missing number
                                                            if ($key == 'Others') {
                                                                $missing_invoice = $missing_number;
                                                            } else {
                                                                $missing_invoice = $key . $missing_number;
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td class="bg-danger text-white text-center">{{ $index++ }}</td>
                                                            <td class="bg-danger text-white text-center" colspan="5">{{ $missing_invoice }}</td>
                                                        </tr>
                                                    @endfor
                                                    @endif
                                                    @endforeach
                                                @endforeach
                                                @endforeach
                                                <tr class="text-left">
                                                    <td></td>
                                                    <td>Total Amount</td>

                                                    <td></td>
                                                    <td></td>
                                                    <td>{{number_format($total,2,'.','')}}</td>
                                                    <td></td>
                                                </tr>
                                            </tbody> --}}
                                        </table>

                                        {{-- <div class="my-1">
                                            {{$paginatedResults->links()}}
                                        </div> --}}

                                        <div class="latter-head">
                                            @include('layouts.backend.partial.modal-footer-info')
                                        </div>
                                    </div>

                                </div>
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

        $(document).on("click", ".sale_view", function(e) {
            e.preventDefault();
            var id= $(this).attr('id');
            $.ajax({
                url: "{{URL('sale-modal')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response){
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                }
            });
        });
    </script>
@endpush

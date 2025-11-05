<style>
    .custom-search {
        background: #9b9fa3 !important;
        padding: 6px 10px;
    }
    .data-table .table tr th {
        padding: 0px 0px;
    }
    @media print{
        .print-hideen{
            display:none !important;
        }
    }
</style>

<section class="print-hideen border-bottom" style="padding: 5px 15px;background:#364a60;">
    <div class="row pl-2">
        <div class="col-md-6 pl-1">
            <h3 style="font-family:Cambria;font-size: 2rem;color:white;">Receivable</h3>
        </div>
        <div class="col-md-6 ">
            <div class="d-flex flex-row-reverse pr-2" style="padding-top: 6px;">
                <div class=""><a href="#" class="btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
                {{-- <div class="" style="padding-right: 3px;"><a href="#" onclick="window.print();"
                        class="btn btn-icon btn-success"><i class="bx bx-printer"></i></a></div> --}}
            </div>
        </div>
    </div>
</section>

<div style="margin: 10px 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>

<section id="widgets-Statistics">
    <div class="row">
        <div class="col-12 text-center">
            <div class="col-md-12">
                <div class="border-botton">
                    <div class="mx-2">
                      <form class="search_home_reports" data="receipt/home-receivable">
                            <div class="form-row align-items-center">

                                <!-- Date From -->
                                <div style="padding-left:10px;">
                                    <input type="text" name="date_from" value="@if($date_from){{date('d/m/Y', strtotime($date_from))}}@endif"
                                        id="date_from" autocomplete="off" class="form-control mb-2 inputFieldHeight datepicker"
                                        placeholder="dd/mm/yyyy">
                                </div>

                                <!-- Date To -->
                                <div style="padding-left:10px;">
                                    <input type="text" name="date_to" value="@if($date_to){{date('d/m/Y', strtotime($date_to))}}@endif"
                                        id="date_to" autocomplete="off" class="form-control mb-2 inputFieldHeight datepicker"
                                        placeholder="dd/mm/yyyy">
                                </div>

                                <!-- Month Input -->
                                <div style="padding-left:10px;">
                                    <input type="month" name="month" id="month" value="{{ old('month', $month ?? '') }}"
                                        class="form-control mb-2 inputFieldHeight">
                                </div>

                                <!-- Year Input -->
                                <div style="padding-left:10px;">
                                    <input type="number" name="year" id="year" value="{{$year}}" class="form-control mb-2 inputFieldHeight"
                                        placeholder="Year" list="yearOptions">

                                    <!-- Suggested Year List -->
                                    <datalist id="yearOptions">
                                        <option value="2020">
                                        <option value="2021">
                                        <option value="2022">
                                        <option value="2023">
                                        <option value="2024">
                                        <option value="2025">
                                        <option value="2026">
                                        <option value="2027">
                                        <option value="2028">
                                        <option value="2029">
                                        <option value="2030">
                                    </datalist>
                                </div>

                                <!-- Search Button -->
                                <div class="col-auto">
                                    <button type="button" class="btn custom-search mb-2 formButton search_home_reports_btn" title="Search"
                                        style="margin-left:8px;">
                                        <div class="d-flex">
                                            <div class="formSaveIcon">
                                                <img src="{{ asset('/') }}assets/backend/app-assets/icon/searching-icon.png" width="25">
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="data-table table-responsive">
                            <table class="table table-sm" data-table="receivable">
                                <thead class="bg-light" style="background-color: #34465b !important">
                                    <tr class="text-center">
                                        {{-- <th style="color:white;">Date</th> --}}
                                        <th style="color:white; text-align:left;">Assigned To</th>
                                        <th style="color:white; text-align:left;">Owner / Party</th>
                                        <th style="color:white; text-align:left;">Project Name</th>
                                        <th style="color:white; text-align:left;">Project No</th>
                                        <th style="color:white; text-align:left;">Plot</th>
                                        <th style="color:white; text-align:left;">Location</th>
                                        <th style="color:white;">Taxable Amount <br> {{ number_format($grandTotals->grand_total_budget, 2) }} </th>
                                        <th style="color:white;">VAT <br> {{ number_format($grandTotals->grand_total_vat, 2) }} </th>
                                        <th style="color:white;">Total <br> {{ number_format($grandTotals->grand_total_budget, 2) }} </th>
                                        <th style="color:white;">Received Amount <br> {{ number_format($grandTotals->grand_total_paid, 2) }} </th>
                                        <th style="color:white;">Receivable {{ number_format($grandTotals->grand_total_due, 2) }} </th>
                                        {{-- <th style="color:white;">Accrued Receivable <br> {{number_format($grandTotals->grand_total_acc_rec, 2)}}</th>
                                        <th style="color:white;">Total Receivable <br> {{number_format($grandTotals->grand_total_acc_rec + $grandTotals->grand_total_due, 2)}}</th> --}}
                                    </tr>
                                </thead>
                                <tbody style="font-size: 12px !important;">
                                    @foreach ($receivables as $item)

                                    <tr class="text-cente toggle-invoices" data-id="{{ $item->customer_id }}">
                                        <td class="truncate-text text-left text-uppercase"
                                            title="{{ $item->company->company_name ?? 'SINGH ALUMINIUM AND STEEL' }}">
                                            {{ Str::words($item->company->company_name ?? 'SEA BRIDGE BUILDING CONT.LLC', 2, '...') }}
                                        </td>
                                        <td class="truncate-text text-left text-uppercase"
                                            title="{{ optional($item->party)->pi_name }}">
                                            {{ Str::words(optional($item->party)->pi_name, 2, '...') }}
                                        </td>
                                        <td class="truncate-text text-left text-uppercase"
                                            title="{{ $item->prospect->name ?? '-'}}">
                                            {{ Str::words($item->prospect->name ?? '-', 2, '...') }}
                                        </td>
                                        <td class="text-left ">{{ $item->prospect->project_no ?? '-' }}</td>
                                        <td class="text-left">{{ $item->prospect->plot ?? '-' }}</td>
                                        <td class="text-left">{{ $item->prospect->location ?? '-'}}</td>
                                        <td>{{ number_format($item->total_budget, 2) }}</td>
                                        <td>{{ number_format($item->total_vat, 2) }}</td>
                                        <td>{{ number_format($item->total_total_budget, 2) }}</td>
                                        <td>{{ number_format($item->total_paid, 2) }}</td>
                                        <td>{{ number_format($item->total_due, 2) }}</td>
                                        {{-- <td>{{ number_format($acc_rec=$item->prospect->total_contract-$item->total_vat-$item->total_budget, 2) }}</td>
                                        <td>{{ number_format($item->total_due+$acc_rec, 2) }}</td> --}}
                                    </tr>
                                    <tr id="invoice-row-{{ $item->customer_id }}" style="display: none;">
                                        <td colspan="3">
                                            <div class="invoice-container-{{ $item->customer_id }}">
                                                <!-- invoices will load here -->
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    {{-- <tr class="font-weight-bold bg-light" style=" background-color: #3d4a94 !important;">
                                        <td colspan="6" class="text-right pr-1"><strong>Grand Total</strong></td>
                                        <td style="color:#fff;">{{ number_format($grandTotals->grand_total_budget, 2) }}</td>
                                        <td style="color:#fff;" >{{ number_format($grandTotals->grand_total_vat, 2) }}</td>
                                        <td style="color:#fff;" >{{ number_format($grandTotals->grand_total_total_budget, 2) }}</td>
                                        <td style="color:#fff;" >{{ number_format($grandTotals->grand_total_paid, 2) }}</td>
                                        <td style="color:#fff;">{{ number_format($grandTotals->grand_total_due, 2) }}</td>
                                    </tr> --}}
                                </tbody>
                            </table>

                            {{ $receivables->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>



        <div class="divFooter  ml-1  invoice-view-wrapper">
            Business Software Solutions by
            <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png')}}"
                    alt="" width="150"></span>
        </div>
</section>


<div class="d-flex flex-row-reverse justify-content-center mb-2" >
    <div class="print-hideen">
        <a href="#" onclick="window.print();" class="btn btn-icon btn-secondary custom-action-btn" title="Print Now">
            <i class="bx bx-printer"></i> Print
        </a>
    </div>
</div>

<div class="img receipt-bg invoice-view-wrapper">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid"
        style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;" alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid"
        style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>

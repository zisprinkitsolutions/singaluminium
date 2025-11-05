<style>
    .custom-search {
        background: #9b9fa3 !important;
        padding: 6px 10px;
    }
    table th {
        background: #2B569A  !important;
        color: #fff !important;
    }
</style>

<section class="print-hideen border-bottom" style="padding: 5px 15px;background:#364a60;">
    <div class="row pl-2">
        <div class="col-md-6 pl-1">
            <h3 style="font-family:Cambria;font-size: 2rem;color:white; float: left;">Retention</h3>
        </div>
        <div class="col-md-6">
            <div class="d-flex flex-row-reverse" style="padding-right: 8px;padding-top: 6px;">
                <div class=""><a href="#" class="btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
                <div class="" style="padding-right: 3px;"><a href="#" onclick="window.print();"
                        class="btn btn-icon btn-success"><i class="bx bx-printer"></i></a></div>
            </div>
        </div>
    </div>
</section>

<section id="widgets-Statistics">
    <div class="row">
        <div class="col-12 text-center">
            <div class="col-md-12">
                <div class="border-botton">
                    <div class="mx-2 mt-2">
                       <form class="search_home_reports" data="sales/retention-list-ajax">
                        <div class="form-row align-items-center">

                            <!-- Date From -->
                            <div style="padding-left:10px;">
                                <input type="text" name="date_from" value="@if($date_from){{date('d/m/Y', strtotime($date_from))}}@endif" id="date_from" autocomplete="off"
                                    class="form-control mb-2 inputFieldHeight datepicker" placeholder="dd/mm/yyyy">
                            </div>

                            <!-- Date To -->
                            <div style="padding-left:10px;">
                                <input type="text" name="date_to" value="@if($date_to){{date('d/m/Y', strtotime($date_to))}}@endif" id="date_to" autocomplete="off"
                                    class="form-control mb-2 inputFieldHeight datepicker" placeholder="dd/mm/yyyy">
                            </div>

                            <!-- Month Input -->
                            <div style="padding-left:10px;">
                                <input type="month" name="month" id="month" value="{{ old('month', $month ?? '') }}"
                                    class="form-control mb-2 inputFieldHeight">
                            </div>

                            <!-- Year Input -->
                            <div style="padding-left:10px;">
                                <input type="number" name="year" id="year" value="{{$year}}"
                                    class="form-control mb-2 inputFieldHeight"
                                    placeholder="Year" list="yearOptions" >

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
                                <button type="button" class="btn btn-warning mb-2 formButton search_home_reports_btn" data-retention="true"
                                    title="Search" style="margin-left: 8px;">
                                    <div class="d-flex align-items-center">
                                        <div class="formSaveIcon me-2">
                                            <i class="bi bi-cash-stack"></i> <!-- Bootstrap Icon for money -->
                                        </div>
                                        <div>
                                            Retention 
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>
                       </form>

                        <table class="table table-bordered table-sm" data-table="sales_revinew" id="invoice">
                            <thead class="thead">
                                <tr>
                                    <th style="text-align: center !important;"> SL</th>
                                    <th style="text-align: center !important; min-width:fit-content">Date
                                    </th>
                                    <th style="text-align: center !important; min-width: 90px">Invoice No
                                    </th>
                                    <th style="text-align: center !important; min-width: fit-conten;">
                                        Company</th>
                                    <th style="text-align: left !important; min-width:fit-content">Party /
                                        Owner Name </th>
                                    <th style="text-align: left !important; min-width:fit-content"> Project
                                        Name </th>
                                    <th style="text-align: center !important; min-width:90px"> Plot No </th>
                                    <th style="text-align: left !important; min-width:fit-content"> Location
                                    </th>
                                    <th style="text-align: center !important; min-width:fit-content; text-transform:capitalize;">TAXABLE AMOUNT <br>
                                        {{ number_format($grandTotal->sum('budget'), 2) }}
                                        {{-- <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small> --}}
                                    </th>
                                    <th style="text-align: center !important; min-width:fit-content; text-transform:capitalize;">VAT AMOUNT <br>
                                        {{ number_format($grandTotal->sum('vat'), 2) }}
                                        {{-- <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small> --}}
                                    </th>

                                    <th style="text-align: center !important; min-width:fit-content; text-transform:capitalize;">GROSS AMOUNT (INCLUDING VAT) <br>
                                        {{ number_format($grandTotal->sum('total_budget'), 2) }}
                                        {{-- <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small> --}}
                                    </th>
                                    <th style="text-align: center !important; min-width:fit-content; text-transform:capitalize;">
                                        RETENTION AMOUNT <br>

                                        <span @if($grandTotal->sum('retention_amount') < 0) class="badge bg-danger"@endif>
                                            {{ number_format(abs($grandTotal->sum('retention_amount')), 2) }}
                                        </span>
                                        {{-- <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small> --}}
                                    </th>


                                </tr>
                            </thead>

                            <tbody id="sale-body-hide">
                                @foreach ($paginatedSales as $key => $item)
                                @php
                                    ;
                                    $project = $item->project;
                                    $new_project = $project?$project->new_project:n
                                @endphp
                                <tr class="sale_view text-uppercase home-sale-view" id="{{$item->id}}">
                                    <td>{{ ($paginatedSales->currentPage() - 1) * $paginatedSales->perPage() + $key + 1 }}</td>
                                    <td style="text-align: center !important;">
                                        {{date('d/m/Y',strtotime($item->date))}}</td>

                                    <td style="text-align: center !important;">{{$item->invoice_no}}</td>
                                    <td style="text-align: center !important;"
                                        title="{{$item->company->company_name??'SINGH ALUMINIUM AND STEEL'}}">
                                        {{\Illuminate\Support\Str::limit($item->company->company_name??'SINGH ALUMINIUM AND STEEL',15)}}
                                    </td>

                                    <td style="text-align: left !important;"
                                        title="{{optional($item->party)->pi_name}}">
                                        {{\Illuminate\Support\Str::limit(optional($item->party)->pi_name,15)}}
                                    </td>

                                    <td style="text-align: left !important;" title="{{optional($new_project)->name}}">
                                        {{\Illuminate\Support\Str::limit(optional($new_project)->name,15)}}
                                    </td>
                                    <td title="{{optional($new_project)->plot}}">
                                        {{\Illuminate\Support\Str::limit(optional($new_project)->plot,10)}}
                                    </td>
                                    <td class="text-left"> {{optional($new_project)->location}}</td>
                                    <td >{{ number_format($item->budget, 2) }}</td>
                                    <td >{{ number_format($item->vat, 2) }}</td>
                                    <td >{{ number_format($item->total_budget, 2) }}</td>
                                    <td>
                                        @if($item->retention_amount < 0) <span class="badge bg-danger">({{ number_format(abs($item->retention_amount), 2)
                                            }})</span>
                                            @else
                                            <span class="badge bg-success">{{ number_format($item->retention_amount, 2) }}</span>
                                            @endif
                                    </td>
                                 </tr>

                                <tr id="home-sale-view-row-{{ $item->id }}" style="display: none;">
                                    <td colspan="9">
                                        <div class="home-sale-view-container-{{ $item->id }}">
                                            <!-- invoices will load here -->
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                {{-- <tr style=" background-color: #3d4a94 !important;">
                                    <td colspan="3" style="text-align: right ; margin-right:5px; color:#fff;">Total</td>
                                    <td style="color:#fff;">{{ number_format($grandTotal->sum('budget'), 2) }}</td>
                                    <td style="color:#fff;">{{ number_format($grandTotal->sum('vat'), 2) }}</td>
                                    <td style="color:#fff;">{{ number_format($grandTotal->sum('total_budget'), 2) }}</td>
                                    <td style="color:#fff;">{{ number_format($grandTotal->sum('retention_amount'), 2) }}</td>
                                </tr> --}}

                            </tbody>
                        </table>

                       {{ $paginatedSales->appends(request()->all())->links() }}

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


<div class="img receipt-bg invoice-view-wrapper">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid"
        style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;" alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid"
        style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>

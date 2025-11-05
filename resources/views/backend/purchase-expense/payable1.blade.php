<style>
    .custom-search {
        background: #9b9fa3 !important;
        padding: 6px 10px;
    }

    .payable-view:hover {
        background: #d1e3f6 !important;
    }
</style>

<section class="print-hideen border-bottom" style="padding: 5px 15px;background:#364a60;">
    <div class="row pl-2">
        <div class="col-md-6 pl-1">
            <h3 style="font-family:Cambria;font-size: 2rem;color:white;">Payable</h3>
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

<div style="margin: 10px 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>

<section id="widgets-Statistics">
    <div class="row">
        <div class="col-12 text-center">
            <div class="col-md-12">
                <div class="border-botton">
                    <div class="mx-2">
                        <form class="search_home_reports" data="home-payable">

                            <div class="form-row align-items-center">

                                <!-- Date From -->
                                <div style="padding-left:10px;">
                                    <input type="text" name="date_from" value="@if($date_from){{date('d/m/Y', strtotime($date_from))}}@endif"
                                        id="date_from" autocomplete="off" class="form-control mb-2 inputFieldHeight datepicker"
                                        placeholder="dd/mm/yyyy">
                                </div>

                                <!-- Date To -->
                                <div style="padding-left:10px;">
                                    <input type="text" name="date_to" value="@if($date_to){{date('d/m/Y', strtotime($date_to))}}@endif" id="date_to"
                                        autocomplete="off" class="form-control mb-2 inputFieldHeight datepicker" placeholder="dd/mm/yyyy">
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
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-striped" style="width: 805px"
                                data-table="payable">
                                <thead class="bg-light" style="background-color: #2B569A !important; color:#fff;">
                                    <tr>
                                        <th style="color:rgb(255, 255, 255)">Payee Code</th>
                                        <th style="color:rgb(255, 255, 255); text-align:left">Payee</th>
                                        <th style="color:rgb(255, 255, 255)">Balance Payable Amount <br> {{number_format($suppliers->sum('due_amount'),2)}}</th>
                                    </tr>
                                </thead>
                                <tbody id="purch-body">
                                    @foreach ($suppliers as $item)
                                    @if ($item->due_amount>0)
                                    <tr class="payable-view(1ifmodalviewremove) toggle-expense"
                                        data-id="{{ $item->party_id }}" style="cursor: pointer;"
                                        id="{{ $item->party ? $item->party->id :'' }}" style="text-align:center;">
                                        <td style="text-transform: uppercase">{{$item->party ? $item->party->pi_code
                                            :''}}</td>
                                        <td style="text-transform: uppercase ; text-align:left">{{$item->party ?
                                            $item->party->pi_name :''}}</td>
                                        <td>{{number_format($item->due_amount,2)}}</td>
                                    </tr>
                                    <tr id="expense-row-{{ $item->party_id }}" style="display: none;">
                                        <td colspan="3">
                                            <div class="expense-container-{{ $item->party_id }}">
                                                <!-- expense will load here -->
                                            </div>
                                        </td>
                                    </tr>
                                    @endif

                                    @endforeach
                                    {{-- <tr>
                                        <td class="text-right pr-1" colspan="2">Total Amount</td>
                                        <td>{{number_format($suppliers->sum('due_amount'),2)}}</td>
                                    </tr> --}}
                                </tbody>
                            </table>
                            {{$suppliers->appends(request()->all())->links()}}
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


<div class="img receipt-bg invoice-view-wrapper">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid"
        style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;" alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid"
        style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>

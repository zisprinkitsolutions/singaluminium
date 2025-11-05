
<style>
    .custom-search{
        background: #9b9fa3 !important;
        padding: 6px 10px;
    }
</style>

<section class="print-hideen border-bottom" style="padding: 5px 15px;background:#364a60;">
    <div class="row pl-2">
        <div class="col-md-6 pl-1"> <h3 style="font-family:Cambria;font-size: 2rem;color:white;">Received </h3></div>
        <div class="col-md-6">
            <div class="d-flex flex-row-reverse" style="padding-right: 8px;padding-top: 6px;">
                <div class=""><a href="#" class="btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
                <div class="" style="padding-right: 3px;"><a href="#" onclick="window.print();" class="btn btn-icon btn-success"><i class="bx bx-printer"></i></a></div>
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
                    <form class="search_home_reports" data="recieved-data" >
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
                        <table class="table table-sm" data-table="receivabale">
                            <thead class="bg-light" style="background-color: #2B569A !important">
                                <tr class="text-center">
                                    <th style="color:white; text-align:left;">Owner / Party</th>
                                    <th style="color:white; text-align:left;">Project Name</th>
                                    <th style="color:white; text-align:left;">Project No</th>
                                    <th style="color:white; text-align:left;">Plot</th>
                                    <th style="color:white; text-align:left;">Location</th>
                                    <th style="color:white;">Date</th>
                                    <th style="color:white;">Receipt No</th>
                                    <th style="color:white;">Pay Mode</th>
                                    <th style="color:white;">Amount <br> {{number_format($receipt_list->sum('total_amount'),2)}} </th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 12px !important;">
                                @foreach ($receipt_list as $item)
                                    <tr class="text-center text-uppercase">

                                        <td class="text-left" title="{{optional($item->party)->pi_name}}">
                                                {{ \Illuminate\Support\Str::limit( optional($item->party)->pi_name,20)}}
                                         </td>
                                         <td class="text-left" title="{{$item->prospect->name}}">
                                                {{ \Illuminate\Support\Str::limit($item->prospect->name,20)}}
                                            </td>
                                            <td>{{$item->prospect->project_no}}</td>
                                            <td>{{$item->prospect->plot}}</td>
                                            <td>{{$item->prospect->location}}</td>
                                        <td>{{date('d/m/Y',strtotime($item->date))}}</td>
                                        <td>{{$item->receipt_no}}</td>
                                        <td>{{$item->pay_mode}}</td>

                                        <td> {{number_format($item->total_amount,2)}}</td>
                                    </tr>
                                @endforeach
                                {{-- <tr>
                                    <td colspan="4" class="text-right pr-1">Total Amount</td>
                                    <td>{{number_format($receipt_list->sum('total_amount'),2)}}</td>
                                </tr> --}}
                            </tbody>
                        </table>
                        {{$receipt_list->appends(request()->all())->links()}}
                    </div>
                </div>
            </div>
        </div>

    </div>



    <div class="divFooter  ml-1  invoice-view-wrapper">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png')}}" alt="" width="150"></span>
    </div>
</section>


<div class="img receipt-bg invoice-view-wrapper">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid" style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;" alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>


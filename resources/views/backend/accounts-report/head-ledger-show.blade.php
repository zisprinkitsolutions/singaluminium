

<style>
<style>
thead {
    background: #364a60 !important;
    color: #fff;
    height: 30px;
}

@media print {
    #print_section2 {
        color: black;

        padding: 20px !important;
    }

    table {
        border-collapse: collapse;
    }

    table, tr, th, td {
        color: black !important;
        background: transparent !important;
        border: 1px solid #999;
    }

    thead th {
        color: black !important;
        background: transparent !important;
    }

    .table th {
        color: black !important;
    }

    .table .table-sm {
        color: #000000 !important;
    }
}

#progress-bar2{
      width: 100%;
      background-color: #f3f3f3;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin: 20px 0;
      position: relative;
      display: none;
    }

    #progress-bar-inner2{
      height: 25px;
      width: 0%;
      background-color: #4caf50 !important;
      border-radius: 5px;
      line-height: 25px;
      color: white;
      text-align: center;
    }
</style>

<section class="print-hideen border-bottom" style="padding: 5px 15px;background:#364a60;">
    <div class="d-flex flex-row-reverse">

        <div class="" style="padding-top:5px;"><a href="#" class="btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>

        <div class="" style="padding-top:5px; margin:0 5px;" onclick="media_print('print_section2')">
            <a href="#" class="btn-icon btn btn-info"><span>
                <i class='bx bx-printer'></i></span>
            </a>
        </div>


        <div class="" style="padding-top:5px; margin:0 5px;">
            <button class="btn-icon btn btn-info extend-download" title="Pdf / Print"
            data-url="{{route('extended.general.ledger.pdf')}}">
                <i class='bx bxs-file-export'></i>
            </button>
        </div>

        <div class="" style="padding-top:5px; margin:0 5px;">
            <a href="#" class="btn btn-icon btn-success pdf-download" title="Excel import"
                data-url="{{route('extended.general.ledger.excel')}}" data-type="extended">
                <i class='bx bxs-file-pdf'></i>
            </a>
        </div>

        <div class="w-100">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">{{$acc_head->fld_ac_head}} - Ledger </h4>
        </div>
    </div>
</section>

<section id="widgets-Statistics">
    <div class="row p-1">
        <div class="col-2">
            <label for="search"> Search </label>
            <input type="text" name="search_query" id="head-ledger-search-query" class="form-control" placeholder="Search by purchase sale or reveive no">
        </div>

        <div class="col-2">
            <div class="from-group">
                <label for=""> Month </label>
                <select name="" id="head-ledger-month" class="form-control">
                    <option value=""> Select </option>
                        @foreach (range(1, 12) as $month)
                        <option value="{{ $month }}">
                            {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-2">
            <div class="from-group">
                <label for=""> Year </label>
                <select name="year" id="head-ledger-year" class="form-control">
                    <option value="">Select</option>
                    @foreach (range(date('Y'), date('Y') - 10) as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-2">
            <div class="form-group">
                <label for=""> From Date </label>
                <input type="text" class="datepicker form-control" id="head-ledger-from-date" name="from_date" placeholder="from_date" value="{{date('d/m/Y', strtotime($from_date))}}" autocomplete="off">
            </div>
        </div>

        <div class="col-2">
            <div class="form-group">
                <label for=""> To Date </label>
                <input type="text" class="datepicker form-control" id="head-ledger-to-date" name="to_date" placeholder="to_date" value="{{date('d/m/Y', strtotime($to_date))}}" autocomplete="off">
            </div>
        </div>

        <input type="hidden" id="head-ledger-column" value="{{$column}}">
        <input type="hidden" id="head-ledger-head-id" value="{{$acc_head->id}}" autocomplete="off">

        <button type="submit" class="btn mSearchingBotton formButton mb-1 mt-2" title="Search" id="head-ledger-search">
            <div class="d-flex">
                <div class="formSaveIcon">
                    <img src="{{asset('assets/backend/app-assets/icon/searching-icon.png')}}" width="25">
                </div>
                <div><span>Search</span></div>
            </div>
        </button>
    </div>

    <div id="print_section2" class="p-2">
        @include('layouts.backend.partial.modal-header-info')
        <div class="row">
            <div class="col-md-12">
                <div>
                    <div class="text-center">
                        @if ($column == 'sub_account_head_id')
                        <h5>  </strong> Head : <strong> {{ $acc_head->name }} </strong> </h5>
                        @else
                        <h5> Code: <strong>{{ $acc_head->fld_ac_code }} </strong> Head : <strong> {{ $acc_head->fld_ac_head }} </strong> </h5>
                        @endif

                        @if($to_date && $from_date)
                        <p id="title"> From {{date('d/m/Y',strtotime($from_date))}} To   {{date('d/m/Y',strtotime($to_date))}}</p>
                        @elseif ($from_date)
                        <p id="title"> Date {{date('d/m/Y',strtotime($from_date))}}</p>
                        @elseif($to_date)
                        <p id="title"> Date {{date('d/m/Y',strtotime($to_date))}}</p>
                        @endif
                    </div>

                    <div id="ledger-head-results">
                        <table class="table table-sm">
                            <thead style="background-color: #364a60">
                                <tr>
                                    <th style="width: 65%;color:#fff;font-size:14px !important;"> Month / Year </th>
                                    <th style="width: 10%;text-align:right;color:#fff; font-size:14px !important;"> Debit (<small>{{$currency->symbole}}</small>) </th>
                                    <th style="width: 10%;text-align:right;color:#fff; font-size:14px !important;"> Credit(<small>{{$currency->symbole}}</small>)</th>
                                    <th style="width: 15%;text-align:right;color:#fff; font-size:14px !important;"> Balance </th>
                                </tr>
                            </thead>
                            @php
                                $total_dr_amount = 0;
                                $totabl_cr_amount = 0;
                            @endphp
                            @foreach ($records as $record)
                                <tr class="month-detials-toggler" id="{{$record['head_id']}}" data-target=".month-detials-{{$record['fld_ac_code'] . $record['head_id']}}">
                                    <td style="padding-left:20px; font-size:16px !important; width:65%;border-bottom: 1px solid #dddddd ;">
                                        Year:{{ $record['year']}}
                                    </td>
                                    <td style="font-size:16px; width:10%; text-align: right;border-bottom: 1px solid #dddddd;"> {{$record['total_dr_amount']}}</td>
                                    <td style="font-size:16px; width:10%; text-align: right !important;border-bottom: 1px solid #dddddd;"> {{$record['total_cr_amount']}}</td>
                                    <td style="font-size:16px; width:15%; text-align: right;border-bottom: 1px solid #dddddd;"> {{$record['balance']}}</td>
                                </tr>
                                @foreach ($record['months'] as $month)
                                @php
                                    $total_dr_amount = $month->total_dr_amount;
                                    $total_cr_amount = $month->total_cr_amount;
                                    $balance = abs($month->total_dr_amount - $month->total_cr_amount);
                                @endphp
                                <tr class="month-detials-toggler" data-target=".month-detials-{{$record['year'].$month['month'] . $record['head_id']}}"
                                    data-head="{{$record['head_id']}}" data-month="{{$month['month_number']}}" data-year="{{$record['year']}}">
                                    <td style="padding: 4px 20px; text-align:left;border-bottom: 1px solid #dddddd;">
                                        <div class="d-flex align-items-center" style="font-size:16px;">
                                            <i class='bx bx-plus'></i>
                                            <i class='bx bx-minus d-none'> </i>
                                            {{$month['month']}}
                                        </div>
                                    </td>
                                    <td class="text-right" style="border-bottom: 1px solid #dddddd;">  {{$month->total_dr_amount}} </td>
                                    <td class="text-right" style="text-align: right !important;border-bottom: 1px solid #dddddd;">  {{$month->total_cr_amount}} </td>
                                    <td class="text-right" style="border-bottom: 1px solid #dddddd;"> {{number_format($balance, 2, '.','')}} </td>
                                </tr>

                                <tr class="month-detials-{{$record['year'] .$month['month'] . $record['head_id']}}" style="display: none">
                                    <td style="padding-left:10px;width: 100%" colspan="4">
                                        <table class="table table-sm" id="ledger-detials-{{$record['year'].$month['month_number'] . $record['head_id']}}">

                                        </table>
                                    </td>
                                </tr>
                                @endforeach
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- @include('layouts.backend.partial.modal-footer-info') --}}
    </div>
</section>



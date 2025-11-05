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
        font-size: 13px !important;
    }

    th{
        font-size: 12px !important;
        text-transform: uppercase;
        font-weight: 500 !important;
    }
    @media(min-width:1300px){
        .padding-right{
            padding-right: 0px !important;
        }
    }

    .card-body {
        flex: 1 1 auto;
        min-height: 1px;
        padding: 0rem !important;
    }

    #toggler-balance{
        background-color: #34465b;
        color: #fff;
        padding: 5px 10px;
    }

    .child-table:nth-child(even) {background-color: #f2f2f2;}

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

    .parrent-active{
        color:#34465b;
        font-weight: bold;
        background: #eeeeee;
    }

</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.report._header',[
                'activeMenu' => 'account_report',
            ])
            <div class="tab-content bg-white">
                <div class="tab-pane active p-2">
                    <div class="content-body">
                        <div class="d-flex justify-content-between align-items-center">
                            @include('clientReport.report._accounting_report_subheader', [
                                'activeMenu' => 'trial_balance',
                            ])
                        </div>
                        <section id="widgets-Statistics">
                            <div class="cardStyleChange">
                                <div class="card-body">
                                    <div class="mt-1">
                                        <form action="" method="GET" >
                                            <div class="d-flex">
                                                <div class="form-group d-none" style="width:25%;">
                                                    <select name="company_id" id="company_id_search" class="common-select2 inputFieldHeight w-100">
                                                        <option value="">Select Company...</option>
                                                        <option value="0" selected>SINGH ALUMINIUM AND STEEL</option>
                                                        @foreach ($companies as $company)
                                                        <option value="{{ $company->id }}" {{$company->id == $selected_company->id ? 'selected' : ''}}>{{ $company->company_name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group" style="width:15%;padding-left:8px;">
                                                    <input type="text" class="inputFieldHeight form-control datepicker" name="from" placeholder="From"   id="from" autocomplete="off">
                                                </div>

                                                <div class="form-group" style="width:15%;padding-left:8px;">
                                                    <input type="text" class="inputFieldHeight form-control datepicker" name="to"
                                                    placeholder="To"  id="to" autocomplete="off">
                                                </div>
                                                <div class="form-group" style="width:15%;padding-left:8px;">
                                                    <button type="submit" class="btn mSearchingBotton inputFieldHeight formButton" title="Search" >
                                                        <div class="d-flex" style="padding: 0 15px;">
                                                            <div class="formSaveIcon">
                                                                <img src="{{asset('assets/backend/app-assets/icon/searching-icon.png')}}" width="20">
                                                            </div>
                                                            <div><span>Search</span></div>
                                                        </div>
                                                    </button>
                                                </div>

                                                <div class="d-flex justify-content-end" style="width:70%">
                                                    <a href="{{route('trial-balance-excel', ['from' => $date, 'to' => $date1, 'company_id' => $selected_company->id])}}" class="btn mExcelButton inputFieldHeight formButton mr-1" title="Export">
                                                        <div class="d-flex" style="padding: 0 15px;">
                                                            <div class="formSaveIcon">
                                                                <img src="{{asset('assets/backend/app-assets/icon/excel-icon.png')}}" width="25">
                                                            </div>
                                                            <div><span>Excel</span></div>
                                                        </div>
                                                    </a>

                                                    <a href="{{route('trial-balance-pdf', ['from' => $date, 'to' => $date1,'company_id' => $selected_company->id])}}" target="_blank" class="btn inputFieldHeight btn_create mPrint formButton" title="Print">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon" style="padding: 0 5px;">
                                                                <img src="{{asset('assets/backend/app-assets/icon/print-icon.png')}}" width="25">
                                                            </div>
                                                            <div><span>Print/Pdf</span></div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body pt-0 pb-0" id="print_section">
                                @include('layouts.backend.partial.modal-header-info')

                                <table class="table table-sm table-hover">
                                    <tr>
                                        <th colspan="7" class="text-center">
                                            <div class="">
                                                <h5 style="margin-bottom: 0;"> {{$selected_company->company_name ? $selected_company->company_name : 'SINGH ALUMINIUM AND STEEL'}} Trial Balance </h5>
                                                @if($date === $date1)
                                                    <span style="font-size:14px !important;"> {{ date('d/m/Y', strtotime($date)) }} </span>
                                                @else
                                                <span style="font-size:14px !important;"> {{ date('d/m/Y', strtotime($date)) }} -{{ date('d/m/Y', strtotime($date1)) }} </span>
                                                @endif
                                            </div>

                                            {{-- <h6>{{ date('d F Y', strtotime($date)) }} -
                                                {{ date('d F Y', strtotime($date1)) }}</h6> --}}
                                        </th>
                                    </tr>

                                    @php
                                        $total_dr_amount = 0;
                                        $total_cr_amount = 0;
                                    @endphp

                                    <tr style="" class="heading-style2">
                                        <th class="pl-1" style="font-size:12px !important; width:40%;background-color:#34465b;color:#fff;"> Master Head / Account Head  </th>

                                        <th class="text-right" colspan="2" style="background-color:#34465b;color:#fff; width:20%;">
                                            Opening Balance
                                        </th>
                                        <th class="text-right" colspan="2" style="font-size: 13px !important;background-color:#34465b;color:#fff; width:20%;">
                                            Transactions
                                        </th>
                                        <th class="text-right" colspan="2"  style="font-size: 12px !important; width:20%; background-color:#34465b;color:#fff;">
                                            Balance (<small>{{$currency->symbole}}</small>)
                                        </th>
                                    </tr>

                                    <tr>
                                        <td> </td>
                                        <td class="text-right" style="width:10%;  font-size: 12px !important;color:#4B0082;">Debit</td>
                                        <td class="text-right" style="width:10%;font-size: 12px !important;color:#4B0082;">Credit</td>

                                        <td class="text-right" style="width:10%;  font-size: 12px !important;color:#2E8B57;">Debit</td>
                                        <td class="text-right" style="width:10%;font-size: 12px !important;color:#2E8B57;">Credit</td>

                                        <td class="text-right" style="width:10%;  font-size: 12px !important;color:#B8860B;">Debit</td>
                                        <td class="text-right" style="width:10%;font-size: 12px !important;color:#B8860B;">Credit</td>
                                    </tr>

                                    @php
                                        $total_opening_dr_amount = 0;
                                        $total_opening_cr_amount = 0;
                                        $total_closing_dr_amount = 0;
                                        $total_closing_cr_amount = 0;
                                    @endphp

                                    @foreach ($master_accounts as $key => $master_account)
                                    @php
                                        $count_heads = count($master_account['account_heads']);
                                    @endphp
                                    @if($count_heads > 0)
                                    <tr class="master-account parrent-active" data-target=".head-show-{{$master_account['master_account_id']}}">
                                        <td class="text-left text-uppercase" colspan="7">
                                            <div class="d-flex align-items-center" style="font-size: 13px;">
                                                <i class='bx bx-plus d-none'></i>
                                                <i class='bx bx-minus'> </i>
                                                {{$master_account['name']}}
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @foreach ($master_account['account_heads'] as $account_head)
                                    @php
                                      $sub_head_count =  count($account_head['sub_head']);
                                    @endphp

                                    <tr class="{{$sub_head_count > 0 ? 'master-account parrent-active' : 'head-id'}} head-show-{{$master_account['master_account_id']}}"
                                        id="{{$account_head['head_id']}}" data-column="account_head_id"
                                        data-target=".sub-head-show-{{$account_head['head_id']}}">
                                        <td style="width:40%; padding-left: 30px;">
                                            @if($sub_head_count > 0)
                                            <div class="d-flex align-items-center" style="font-size: 12px;">
                                                <i class='bx bx-plus d-none'></i>
                                                <i class='bx bx-minus'> </i>
                                                {{ $account_head['fld_ac_head'] }}
                                            </div>
                                            @else
                                            {{ $account_head['fld_ac_head'] }}
                                            @endif
                                        </td>
                                        @php
                                            $head_opening_dr = $account_head['opening_dr_amount'];
                                            $head_opening_cr = $account_head['opening_cr_amount'];
                                            $head_closing_dr = $account_head['closing_dr_amount'];
                                            $head_closing_cr = $account_head['closing_cr_amount'];
                                        @endphp
                                        <td class="text-right" style="color:#4B0082">
                                            {{ number_format($head_opening_dr > 0 ? $head_opening_dr : 0 ,2) }}
                                        </td>
                                        <td class="text-right" style="color:#4B0082">
                                            {{ number_format($head_opening_cr > 0 ? $head_opening_cr : 0 ,2) }}
                                        </td>
                                        <td class="text-right" style="color:#2E8B57">
                                            {{ number_format($transection_dr = $account_head['total_dr_amount'],2) }}
                                        </td>
                                        <td class="text-right" style="color:#2E8B57">
                                            {{ number_format($transection_cr = $account_head['total_cr_amount'],2) }}
                                        </td>
                                        <td class="text-right" style="color:#B8860B">
                                            {{ number_format($head_closing_dr > 0 ? $head_closing_dr : 0 ,2) }}
                                        </td>
                                        <td class="text-right" style="color:#B8860B">
                                            {{ number_format($head_closing_cr > 0 ? $head_closing_cr : 0 ,2) }}
                                        </td>

                                        @php
                                            $total_opening_dr_amount += $head_opening_dr > $head_opening_cr ? $head_opening_dr : 0;
                                            $total_opening_cr_amount += $head_opening_cr > $head_opening_dr ? $head_opening_cr : 0;
                                            $total_closing_dr_amount += $head_closing_dr > $head_closing_cr ? $head_closing_dr : 0;
                                            $total_closing_cr_amount += $head_closing_cr > $head_closing_dr ? $head_closing_cr : 0;

                                            $total_dr_amount += $transection_dr;
                                            $total_cr_amount += $transection_cr;
                                        @endphp
                                    </tr>
                                    @if($sub_head_count > 0)
                                    <tr class="sub-head-show-{{$account_head['head_id']}} head-show-{{$master_account['master_account_id']}}">
                                        <td colspan="7" style="width:100%">
                                            <table class="table table-sm table-hover" style="margin-bottom: 0 !important;">
                                                @foreach ($account_head['sub_head'] as $sub_head)
                                                @php
                                                    $sub_head_opening_dr = $sub_head->opening_dr_amount;
                                                    $sub_head_opening_cr = $sub_head->opening_cr_amount;
                                                    $sub_head_closing_dr = $sub_head->closing_dr_amount;
                                                    $sub_head_closing_cr = $sub_head->closing_cr_amount;
                                                @endphp

                                                <tr class="head-id" data-column="sub_account_head_id" id="{{$sub_head->id}}">
                                                    <td style="padding-left:60px; width:40%;">
                                                        <div class="d-flex align-items-center" style="font-size: 12px;">
                                                            {{ $sub_head->name }}
                                                        </div>
                                                    </td>

                                                    <td class="text-right" style="width: 10%;color:#4B0082">
                                                        {{ number_format($sub_head_opening_dr > 0 ? $sub_head_opening_dr : 0 ,2) }}
                                                    </td>
                                                    <td class="text-right" style="width: 10%;color:#4B0082">
                                                        {{ number_format($sub_head_opening_cr > 0 ? $sub_head_opening_cr : 0 ,2) }}
                                                    </td>
                                                    <td class="text-right" style="width: 10%;color:#2E8B57">
                                                        {{ number_format($transection_dr = $sub_head->total_dr_amount,2) }}
                                                    </td>
                                                    <td class="text-right" style="width: 10%;color:#2E8B57">
                                                        {{ number_format($transection_cr = $sub_head->total_cr_amount,2) }}
                                                    </td>
                                                    <td class="text-right" style="width: 10%;color:#B8860B">
                                                        {{ number_format($sub_head_closing_dr > 0 ? $sub_head_closing_dr : 0 ,2) }}
                                                    </td>
                                                    <td class="text-right" style="width: 10%;color:#B8860B">
                                                        {{ number_format($sub_head_closing_cr > 0 ? $sub_head_closing_cr : 0 ,2) }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </table>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                @endforeach

                                    <tr>
                                        <th class="pl-2" colspan="1" style=" color:#34465b; font-weight:bold;font-size:13px !important;"> Grand Total</th>
                                        <th class="text-right balance-column" style="color:#4B0082">
                                            {{ number_format($total_opening_dr_amount,2) }}
                                        </th>
                                        <th class="text-right balance-column" style="color:#4B0082">
                                            {{ number_format($total_opening_cr_amount,2) }}
                                        </th>
                                        <th class="text-right" style="color:#2E8B57">{{ number_format($total_dr_amount,2) }}</th>
                                        <th class="text-right" style="color:#2E8B57">{{ number_format($total_cr_amount,2) }}</th>

                                        <th class="text-right balance-column" style="color:#B8860B">
                                            {{ number_format($total_closing_dr_amount,2) }}
                                        </th>
                                        <th class="text-right balance-column" style="color:#B8860B">
                                            {{ number_format($total_closing_cr_amount,2) }}
                                        </th>
                                    </tr>
                              </table>
                              @include('layouts.backend.partial.modal-footer-info')
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="details-show" style="z-index: 1100" tabindex="-1" rrole="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="details-show-content">

            </div>
        </div>
    </div>
</div>

@endsection

@push('js')

<script>
$(document).on('click', '.extend-download', function(){
    var search_query = $('#head-ledger-search-query').val();
    var year = $('#head-ledger-year');
    var month = $('#head-ledger-month').val();
    var from = $('#head-ledger-from-date').val();
    var to = $('#head-ledger-to-date').val();
    var head_id = $('#head-ledger-head-id').val();
    var order_by = $('#head-ledger-order-by').val();
    var type = $(this).data('type');
    var column_name = $('#head-ledger-column').val();
    year = year.val();
    var company_id = "{{$selected_company->id}}";
    const queryData  = new URLSearchParams({
        search_query,
        year,
        month,
        from,
        to,
        head_id,
        column_name,
    });

    var url = $(this).data('url');
    var confirmation = confirm("The file is too large to render. We will notify you once the process is complete?");
    if (!confirmation) {
        return;
    }
    $.ajax({
        url: url,
        type: 'GET',
        data: queryData.toString(),
        success: function (response) {
            checkNotification();
        },
        error: function (xhr, status, error) {
            alert('An error occurred while processing your request.');
        }
    });
});

$(document).on('click', '.pdf-download', function(e) {
    e.preventDefault();

    var url = $(this).data('url');
    var search_query = $('#head-ledger-search-query').val();
    var year = $('#head-ledger-year');
    var month = $('#head-ledger-month').val();
    var from = $('#head-ledger-from-date').val();
    var to = $('#head-ledger-to-date').val();
    var head_id = $('#head-ledger-head-id').val();
    var order_by = $('#head-ledger-order-by').val();
    var type = $(this).data('type');
    var company_id = "{{$selected_company->id}}";
    if(type == 'extended' && !year.val()){
        year.focus();
        toastr.warning('Account year is required', 'Input Validation');
        return
    }
    year = year.val();
    var params = new URLSearchParams({
        search_query,
        year,
        month,
        from,
        to,
        head_id,
        order_by,
        office_id,
    });

    var newUrl = url + (url.includes('?') ? '&' : '?') + params.toString();

    window.open(newUrl, '_blank');
});

$(document).on("click", ".show-details", function(e) {
    e.preventDefault();
    var id = $(this).attr('id');
    var v_type = $(this).data('type');
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
            document.getElementById("details-show-content").innerHTML = response;
            $('#details-show').modal('show')
        }
    });
});

$(document).on("click", ".head-id", function(e) {
    e.preventDefault();
    var column = $(this).data('column');
    var id = $(this).attr('id');
    $('#loading-overlay').show();

    let percentage = 0;
    const interval = setInterval(() => {
        percentage += 5;
        if (percentage > 95) percentage = 95;
        $('#loading-percentage').text(percentage + '%');
    }, 100);

    $.ajax({
        url: "{{ route('head-ledger-show') }}",
        type: "post",
        cache: false,
        data: {
            _token: '{{ csrf_token() }}',
            id: id,
            from_date:"{{$date}}",
            to_date:"{{$date1}}",
            column:column,
            company_id:"{{$selected_company->id}}",
        },

        success: function(response) {
            document.getElementById("ledger-show-content").innerHTML = response;
            $('#ledger-show').modal('show')
            $(".datepicker").datepicker({
                dateFormat: "dd/mm/yy"
            });
            $('#loading-percentage').text('100%');
        },
        error: function (xhr) {
            $('#data-container').html('<p>Error fetching data.</p>');
        },

        complete: function () {
            clearInterval(interval);
            $('#loading-overlay').hide();
            $('#loading-percentage').text('0%');
        }
    });
});

$(document).on("click", ".master-account", function(e) {
    var target = $(this).data('target');
    $(this).find('.bx').toggleClass('d-none');
    $(this).toggleClass('parrent-active');

    $(target).toggle();

});

$(document).on('click', '#toggler-balance', function(){
    $('.balance-column').toggle();
});

function toggleMonthYearData(searchType){
    if(searchType=== 'date'){
        $('#head-ledger-month').val('');
        $('#head-ledger-year').val('');
    }else{
        $('#head-ledger-to-date').val('');
        $('#head-ledger-from-date').val('');
    }
}

$(document).on('change', '#head-ledger-month', function(){
    toggleMonthYearData('month');
});

$(document).on('change', '#head-ledger-year', function(){
    toggleMonthYearData('month');
});

$(document).on('change', '#head-ledger-to-date', function(){
    toggleMonthYearData('date');
});

$(document).on('change', '#head-ledger-from-date', function(){
    toggleMonthYearData('date');
});

$(document).on('click', '.sort-toggler td', function () {
    const thead = $(this).closest('thead');
    const tbody = thead.next('tbody');
    const columnIndex = $(this).data('column');
    let sortOrder = $(this).data('sort');

    sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
    $(this).data('sort', sortOrder);
    $(this).find('.sort-indicator').removeClass('asc desc');
    $(this).find('.sort-indicator').addClass(sortOrder);

    const rows = tbody.find('tr').toArray();

    rows.sort((a, b) => {
        const aText = $(a).find('td').eq(columnIndex).text().trim();
        const bText = $(b).find('td').eq(columnIndex).text().trim();

        const aNum = parseFloat(aText.replace(/[^0-9.-]+/g, ""));
        const bNum = parseFloat(bText.replace(/[^0-9.-]+/g, ""));

        if (!isNaN(aNum) && !isNaN(bNum)) {
            return sortOrder === 'asc' ? aNum - bNum : bNum - aNum;
        }

        return sortOrder === 'asc'
            ? aText.localeCompare(bText)
            : bText.localeCompare(aText);
    });

    tbody.append(rows);
});
function fetchDetail(head_id, month_number, year){
    $('#loading-overlay').show();
    var url = "{{route('general_ledger_details', ':head_id')}}";
    url = url.replace(':head_id', head_id);
    var search_query = $('#head-ledger-search-query').val();
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var order_by = $('#order_by').val();
    var column = $('#head-ledger-column').val();
    var company_id = $('#company_id_search').val();
    let percentage = 0;

    const interval = setInterval(() => {
        percentage += 20;
        if (percentage > 95) percentage = 95;
        $('#loading-percentage').text(percentage + '%');
    }, 100);

    $.ajax({
        type:'GET',
        url:url,
        data:{
            year:year,
            search_query:search_query,
            month_number:month_number,
            year:year,
            from_date:from_date,
            to_date:to_date,
            order_by:order_by,
            column :column,
            company_id:company_id,
        },

        success:function(res){
            $(`#ledger-detials-${year}${month_number}${head_id}`).html(res);
            $('#loading-percentage').text('100%');
        },

        error:function(error){
            toastr.error('Unexcepted Error, Account Head Missing',404);
        },

        complete: function () {
            clearInterval(interval);
            $('#loading-overlay').hide();
            $('#loading-percentage').text('0%');
        }
    })
};

$(document).on('click', '.month-detials-toggler', function() {
    $(this).find('.bx').toggleClass('d-none');
    $(this).find('td').toggleClass('active-bg');
    var target = $(this).data('target');
    var head_id = $(this).data('head');
    var month_number = $(this).data('month');
    var year = $(this).data('year');
    if(month_number){
        fetchDetail(head_id, month_number, year);
    }

    $(this).data('month', false);

    $(target).toggle();
});
</script>

@endpush

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

        #progress-notification{
            position: fixed;
            top: 0;
            right: 0;
            padding: 10px;
            z-index: 100;
            width: 40%;
        }

        #progress-bar {
            width: 100%;
            background-color: #f3f3f3;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 20px 0;
            position: relative;
            display: none;
        }

        #progress-bar-inner {
            height: 25px;
            width: 0%;
            background-color: #4caf50;
            border-radius: 5px;
            line-height: 25px;
            color: white;
            text-align: center;
        }

        .active-bg, .head-details:hover,
        .month-detials-toggler:hover{
            background-color: #e2e2e2;
        }

        @media print{
            #print-section{
                padding:10px;
            }
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
                @include('clientReport.report._header', [
                    'activeMenu' => 'account_report',
                ])
                <div class="tab-content bg-white">
                    <div class="tab-pane active p-2">
                        <div class="content-body">
                            <section id="widgets-Statistics">
                                <div class="d-flex justify-content-between align-items-center">
                                    @include('clientReport.report._accounting_report_subheader', ['activeMenu' => 'general_ledger'])
                                </div>

                                <div class="cardStyleChange">
                                    <div class="card-body mt-1">
                                        <form action="" method="GET">
                                            <div class="d-flex">

                                                <div class="form-group d-none" style="width:25%;">
                                                    <label for="">
                                                        Company
                                                    </label>
                                                    <select name="company_id" id="company_id_search" class="common-select2 inputFieldHeight w-100">
                                                        <option value="">Select Company...</option>
                                                        <option value="" selected>SINGH ALUMINIUM AND STEEL</option>
                                                        @foreach ($companies as $company)
                                                        <option value="{{ $company->id }}" {{$company->id == $selected_company->id ? 'selected' : ''}}>{{ $company->company_name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <input type="hidden" name="office_id" id="office_id" value="{{$selected_office->id}}">
                                                {{-- @endif --}}

                                                <div class="form-group" style="width:25%; margin-left:8px;">
                                                    <label for="">
                                                        Account Head
                                                    </label>
                                                    <select name="search" id="head_id"
                                                    class="form-control common-select2">
                                                        <option value="">Select </option>
                                                        @foreach ($account_heads as $head)
                                                            <option value="{{ $head->id }}" {{$head->id == $search ? 'selected' : ' '}}>
                                                                {{ $head->fld_ac_code }}-{{ $head->fld_ac_head }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                                <div class="from-group" style="width:10%; margin-left:8px;">
                                                    <label for=""> Month </label>
                                                    <select name="month" class="form-control inputFieldHeight" id="month">
                                                        <option value=""> Select </option>
                                                            @foreach (range(1, 12) as $m)
                                                            <option value="{{ $m}}" {{$search_month == $m ? 'selected' : ' '}}>
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
                                                            <option value="{{ $y }}" {{$search_year == $y ? 'selected' : ' '}}>{{ $y }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                                <div class="from-group" style="width:10%; margin-left:8px;">
                                                    <div class="form-group">
                                                        <label for=""> From Date </label>
                                                        <input type="text" id="from" class="datepicker form-control inputFieldHeight" name="from" placeholder="from" value="{{$from ? date('d/m/Y', strtotime($from)) : null}}" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="from-group" style="width:10%; margin-left:8px;">
                                                    <div class="form-group">
                                                        <label for=""> To Date </label>
                                                        <input type="text" class="datepicker form-control inputFieldHeight" id="to" name="to" placeholder="to" value="{{$to ? date('d/m/Y', strtotime($to)) : null}}" autocomplete="off">
                                                    </div>
                                                </div>

                                                <button type="submit" style="marging-left:8px; margin-top:20px;"
                                                    class="btn mSearchingBotton mb-2 ml-1 formButton inputFieldHeight" title="Search">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                width="25">
                                                        </div>
                                                    </div>
                                                </button>

                                                <div class="d-flex justify-content-end" style="margin-left:8px; margin-top:20px; width:35%">
                                                    <a href="{{route('new-general-ledger',['all' => 'all'])}}" type="submit" style="margin-left: 4px;"
                                                        class="btn btn-primary mb-2 formButton inputFieldHeight" title="All">
                                                        <div class="d-flex">
                                                            ALL
                                                        </div>
                                                    </a>

                                                    <a href="#" class="btn btn_create mPrint formButton inputFieldHeight mb-2" title="Print"
                                                        onclick="media_print('print_section')" style="margin-left:6px;">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/print-icon.png') }}"
                                                                    width="25">
                                                            </div>
                                                        </div>
                                                    </a>
                                                    @if($search)
                                                    <button type="button" data-url="{{route('extended.general.ledger.pdf')}}"
                                                        class="btn inputFieldHeight mb-2 btn-secondary formButton extend-download" style="margin-left:6px;"
                                                        title="Extended Pdf/Print"
                                                        data-query="{{ json_encode([
                                                            'month' => $search_month,
                                                            'year' => $search_year,
                                                            'from' => $from,
                                                            'to' => $to,
                                                            'search' => $search,
                                                            'company_id' => $selected_company->id,
                                                        ]) }}">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <i class='bx bxs-file-pdf text-white'></i>
                                                            </div>
                                                        </div>
                                                    </button>

                                                    <button  data-url="{{route('extended.general.ledger.excel')}}" data-type="extend" type="button" style="margin-left: 4px;"
                                                        class="btn inputFieldHeight mPrint mb-2 formButton downloadPdf" title="Extended Excel">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/excel-icon.png') }}"
                                                                    width="25">
                                                            </div>
                                                        </div>
                                                    </button>
                                                    @else

                                                    <button data-url="{{route('general.ledger.pdf',['company_id', $selected_company->id])}}" type="button" style="margin-left: 4px;"
                                                        class="btn inputFieldHeight mPrint mb-2 formButton downloadPdf" title="PDF / Print">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <i class='bx bxs-file-pdf'></i>
                                                            </div>
                                                        </div>
                                                    </button>

                                                    <button data-url="{{route('general.ledger.excel')}}" type="button" style="margin-left: 4px;"
                                                        class="btn inputFieldHeight mPrint mb-2 formButton downloadPdf" title="Excel">
                                                        <div class="d-flex">
                                                            <div class="formSaveIcon">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/excel-icon.png') }}"
                                                                    width="25">
                                                            </div>
                                                        </div>
                                                    </button>
                                                    @endif

                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    @if(count($records) > 0)
                                    <div class="card-body" id="print_section">
                                        @include('layouts.backend.partial.modal-header-info')

                                        <div class="text-center">
                                            <h5 class="text-center" style="color: #313131; font-size:18px; font-weight:400">
                                               {{$selected_company->company_name ? $selected_company->company_name : ' SINGH ALUMINIUM AND STEEL'}} General Ledger
                                            </h5>
                                            @if($selected_account_head)
                                            {{$selected_account_head->fld_ac_code.'-'. $selected_account_head->fld_ac_head}}
                                            @endif
                                            @if ($to && $from)
                                            <p style="color:#313131; font-weight:400; font-size:13px;"> From {{date('d/m/Y', strtotime($from))}} To {{date('d/m/Y', strtotime($to))}} </p>
                                            @elseif ($from)
                                            <p style="color:#313131;font-weight:400; font-size:13px;">  Date {{date('d/m/Y', strtotime($from))}} </>
                                            @elseif ($from)
                                            <p style="color:#313131;font-weight:400; font-size:13px;"> Date {{date('d/m/Y', strtotime($from))}} </>
                                            @endif
                                        </div>

                                        <table class="table table-sm">
                                            <thead style="background-color:#f4f4f4">
                                                <tr>
                                                    <th style="width: 65%; font-size:13px;"> Account Head </th>
                                                    <th class="text-right" style="width:10%; font-size:13px !important;"> Debit(<small style="font-size: 12px;">{{$currency->symbole}}</small>) </th>
                                                    <th class="text-right" style="width:10%; font-size:13px !important;"> Credit(<small style="font-size: 12px;">{{$currency->symbole}}</small>) </th>
                                                    <th class="text-right" style="width:15%; font-size:13px !important;"> Balance C/D </th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php
                                                    $total_dr = 0;
                                                    $total_cr = 0;
                                                @endphp
                                                @foreach ($records as $record)
                                                @php
                                                    $total_dr += $record['dr_amount'];
                                                    $total_cr += $record['cr_amount'];

                                                    $item_counts = count($record['items']);
                                                @endphp

                                                <tr class="head-details" data-url="{{route('general_ledger_yearly_details', $record['id'])}}"
                                                    data-target=".head-details-yearly-{{$record['id']}}" data-column="account_head_id"
                                                        data-items="{{$item_counts}}">
                                                    <td style="font-size: 13px;">
                                                        <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                            <i class='bx bx-plus'></i>
                                                            <i class='bx bx-minus d-none'> </i>
                                                            {{$record['fld_ac_head']}}
                                                        </div>
                                                    </td>
                                                    <td class="text-right" style="font-size: 14px;"> {{number_format($record['dr_amount'],2)}}</td>
                                                    <td class="text-right" style="font-size:14px;">{{number_format($record['cr_amount'],2)}}</td>
                                                    <td class="text-right" style="font-size:14px;"> {{number_format(abs($record['dr_amount'] - $record['cr_amount']),2)}} </td>
                                                </tr>

                                                @if($item_counts > 0)
                                                @foreach ($record['items'] as $item)
                                                <tr class="head-details subhead d-none" data-url="{{route('general_ledger_yearly_details', $item->id)}}"
                                                    data-target=".sub-head-details-yearly-{{$item->id}}" data-column="sub_account_head_id">
                                                    <td style="font-size: 13px; padding-left:15px;padding-left: 25px;">
                                                        <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                            <i class='bx bx-plus'></i>
                                                            <i class='bx bx-minus d-none'> </i>
                                                            {{$item->name}}
                                                        </div>
                                                    </td>

                                                    <td class="text-right" style="font-size: 14px;"> {{number_format($item->dr_amount,2)}}</td>
                                                    <td class="text-right" style="font-size:14px;">{{number_format($item->cr_amount,2)}}</td>
                                                    <td class="text-right" style="font-size:14px;"> {{number_format(abs($item->dr_amount - $item->cr_amount),2)}} </td>
                                                </tr>
                                                <tr class="sub-head-details-yearly-{{$item->id}}" style="display: none;">
                                                    <td colspan="4" class="data-show-yearly" style="width: 100%; padding-left: 25px !important;">

                                                    </td>
                                                </tr>
                                                @endforeach

                                                @else

                                                <tr class="head-details-yearly-{{$record['id']}}" style="display: none">
                                                    <td colspan="4" class="data-show-yearly" style="width: 100%">

                                                    </td>
                                                </tr>
                                                @endif

                                                @endforeach

                                                <tr>
                                                    <td style="width:65%;font-size: 12px; text-align:right;  border-bottom: 1px solid #dddddd;">
                                                        TOTAL
                                                    </td>
                                                    <td class="text-right" style="font-size: 13px;  border-bottom: 1px solid #dddddd;"> {{number_format($total_dr,2)}}</td>
                                                    <td class="text-right" style="font-size:13px;  border-bottom: 1px solid #dddddd;">{{number_format($total_cr,2)}}</td>
                                                    <td class="text-right" style="font-size:13px;  border-bottom: 1px solid #dddddd;"> </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @include('backend.print.footer-with-address')
                                    </div>
                                    @endif
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
    $(document).on('click', '.downloadPdf', function(e){
        e.preventDefault();
        var type = $(this).data('type');
        var head_id = $('#head_id');
        var year = $('#year');

        if(type == 'extend' && !head_id.val()){
            head_id[0].focus();
            head_id.addClass('is-invalid')
            toastr.warning('Account head is required', 'Input Validation' );
            return
        }

        // if(type == 'extend' && !year.val()){
        //     year[0].focus();
        //     toastr.warning('Account year is required', 'Input Validation' );
        //     return
        // }

        var url = $(this).data('url');
        const form = $('form');
        form.attr('method','GET');
        form.attr('target', '_blank');

        form.attr('target', ' ');
        var params = new URLSearchParams(new FormData(form[0]));

        var newUrl = url + (url.includes('?') ? '&' : '?') + params.toString();
        form.prop('action', url);
        form.submit();

        form.removeAttr('target', ' ');
        form.prop('action', ' ');
    });

    $(document).on('click', '.head-details', function () {
        var is_sub_head = $(this).data('items');

        $(this).find('.bx').toggleClass('d-none');
        $(this).find('td').toggleClass('active-bg');

        if(is_sub_head > 0){
            var sublings = $(this).nextAll('.subhead');

            if (sublings.first().hasClass('d-none')) {
                $('.subhead').addClass('d-none'); // hide all others
                sublings.removeClass('d-none'); // show only this one's
            } else {
                sublings.addClass('d-none'); // toggle off current
            }
            return
        }

        $('#loading-overlay').show();
        var year = $('#year').val();
        var from = $('#from').val();
        var to = $('#to').val();
        var url = $(this).data('url');
        var target = $(this).data('target');
        var column = $(this).data('column');
        var month = "{{$search_month}}";
        if ($(target).data('loaded')) {
            $(target).toggle();
            $('#loading-overlay').hide();
            return;
        }

        $(target).data('loaded', true);

        $('#progress-bar').show();

        let percentage = 0;
        const interval = setInterval(() => {
            percentage += 20;
            if (percentage > 95) percentage = 95;
            $('#loading-percentage').text(percentage + '%');
        }, 100);

        $.ajax({
            url: url,
            type: 'get',
            data: {
                from: from,
                to: to,
                month:month,
                year: year,
                column:column,
                company_id: "{{$selected_company->id}}",
            },
            success: function (res) {
                $('#loading-percentage').text('100%');
                $(target).find('.data-show-yearly').html(res);
                $(target).show();
            },
            error: function (error) {
                alert('Error occurred while fetching data.');
                $(target).data('loaded', false);
            },
            complete: function () {
                clearInterval(interval);
                $('#loading-overlay').hide();
                $('#loading-percentage').text('0%');
                $('#progress-bar').hide();
            }
        });
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

    function fetchDetail(head_id, month_number, year,column){

        $('#loading-overlay').show();
        var url = "{{route('general_ledger_details', ':head_id')}}";
        url = url.replace(':head_id', head_id);
        var search_query = $('#search_query').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var order_by = $('#order_by').val();
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
                column:column,
                office_id:$('#office_id').val(),
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
        var column = $(this).data('column');

        if(month_number){
            fetchDetail(head_id, month_number, year,column);
        }

        $(this).data('month', false);

        $(target).toggle();
    });

    $(document).on("click", ".head-id", function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        $('#progress-bar').show();
        updateProgress(10);
        $.ajax({
            url: "{{ route('head-ledger-show') }}",
            type: "post",
            cache: false,
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                from_date:"{{$from}}",
                to_date:"{{$to}}",
            },
            xhr: function () {
                const xhr = new window.XMLHttpRequest();
                xhr.onprogress = function (event) {
                if (event.lengthComputable) {
                    const percentComplete = (event.loaded / event.total) * 100;
                    updateProgress(percentComplete);
                }else{
                    updateProgress(30);
                }
                };
                return xhr;
            },

            beforeSend: function () {
                updateProgress(50);
            },

            success: function(response) {
                document.getElementById("ledger-show-content").innerHTML = response;
                $('#ledger-show').modal('show')
                $(".datepicker").datepicker({
                    dateFormat: "dd/mm/yy"
                });
                updateProgress(100);
            },
            error: function (xhr) {
                $('#data-container').html('<p>Error fetching data.</p>');
            },

            complete: function () {
                setTimeout(() => {
                $('#progress-bar').fadeOut();
                }, 500);
            }
        });
    });

    $(document).on('click', '.extend-download', function(){
        const queryData = JSON.parse($(this).attr('data-query'));
        var url = $(this).data('url');
        var confirmation = confirm("The file is too large to render. We will notify you once the process is complete?");
        if (!confirmation) {
            return;
        }
        $.ajax({
            url: url,
            type: 'GET',
            data: queryData,
            success: function (response) {
                checkNotification();
            },
            error: function (xhr, status, error) {
                alert('An error occurred while processing your request.');
            }
        });
    });

    $(document).on('click', '.sort-toggler td', function () {
        const thead = $(this).closest('thead');
        const tbody = thead.next('tbody');
        const columnIndex = $(this).data('column');
        let sortOrder = $(this).data('sort');

        sortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
        $(this).data('sort', sortOrder);
        $(thead).find('.sort-indicator').removeClass('asc desc');
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

    function toggleMonthYearData(searchType){
        if(searchType=== 'date'){
            $('#month').val('');
            $('#year').val('');
        }else{
            $('#to').val('');
            $('#from').val('');
        }
    }

    $(document).on('change', '#month', function(){
        toggleMonthYearData('month');
    });

    $(document).on('change', '#year', function(){
        toggleMonthYearData('month');
    });

    $(document).on('change', '#to', function(){
        toggleMonthYearData('date');
    });

    $(document).on('change', '#from', function(){
        toggleMonthYearData('date');
    });
</script>
@endpush



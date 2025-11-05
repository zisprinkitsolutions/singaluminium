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

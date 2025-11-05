<table class="table table-sm">
        @php
            $total_dr_amount = 0;
            $totabl_cr_amount = 0;
        @endphp
        @foreach ($records as $record)
            <tr class="month-detials-toggler" id="{{$record['head_id']}}" data-target=".month-detials-{{$record['fld_ac_code'] . $record['head_id']}}"
                data-column="{{$column}}">
                <td style="padding-left:20px; font-size:13px; width:65%;border-bottom: 1px solid #dddddd;">
                    Year:{{ $record['year']}}
                </td>
                <td style="font-size:13px; width:10%; text-align: right;border-bottom: 1px solid #dddddd;"> {{number_format($record['total_dr_amount'],2)}}</td>
                <td style="font-size:13px; width:10%; text-align: right !important;border-bottom: 1px solid #dddddd;"> {{number_format($record['total_cr_amount'],2)}}</td>
                <td style="font-size:13px; width:15%; text-align: right;border-bottom: 1px solid #dddddd;"> {{number_format($record['balance'],2)}}</td>
            </tr>
            @foreach ($record['months'] as $month)
            @php
                $total_dr_amount = $month->total_dr_amount;
                $total_cr_amount = $month->total_cr_amount;
                $balance = abs($month->total_dr_amount - $month->total_cr_amount);
            @endphp
            <tr class="month-detials-toggler" data-target=".month-detials-{{$record['year'].$month['month'] . $record['head_id']}}"
                data-head="{{$record['head_id']}}" data-month="{{$month['month_number']}}" data-year="{{$record['year']}}" data-column="{{$column}}">
                <td style="padding: 4px 0px 4px 20px; text-align:left;border-bottom: 1px solid #dddddd; padding-left: 35px !important;">
                    <div class="d-flex align-items-center" style="font-size:13px;">
                        <i class='bx bx-plus'></i>
                        <i class='bx bx-minus d-none'> </i>
                        {{$month['month']}}
                    </div>
                </td>
                <td class="text-right" style="border-bottom: 1px solid #dddddd; font-size:13px;">  {{number_format($month->total_dr_amount,2)}} </td>
                <td class="text-right" style="text-align: right !important;border-bottom: 1px solid #dddddd;font-size:13px;">  {{number_format($month->total_cr_amount,2)}} </td>
                <td class="text-right" style="border-bottom: 1px solid #dddddd;font-size:13px;"> {{number_format($balance, 2)}} </td>
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

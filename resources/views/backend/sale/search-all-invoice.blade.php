
   @foreach ($pending_sales as $key => $item)
    @php
        $project = $item->project;
    @endphp

    <tr class="approve_view"  id="{{$item->id}}">
        <td>{{$key+1}}</td>
        <td style="text-align: center !important;">{{date('d/m/Y',strtotime($item->date))}}</td>
            @if($item->invoice_type == 'Proforma Invoice')
        <td style="text-align: center !important;">{{$item->proforma_invoice_no}}</td>
        @elseif($item->invoice_type == 'Direct Invoice')
        <td style="text-align: center !important;">{{$item->invoice_no_s_d}}</td>
        @else
        <td style="text-align: center !important;">{{$item->invoice_no}}</td>
        @endif

        <td style="text-align: left !important;" title="{{optional($item->party)->pi_name}}">
            {{\Illuminate\Support\Str::limit(optional($item->party)->pi_name,30)}}
        </td>

        <td style="text-align: left !important;" title="{{optional($project)->project_name}}">
            {{\Illuminate\Support\Str::limit(optional($project)->project_name,30)}}
        </td>
        <td class="text-left"> {{optional($project)->location}}</td>

        <td style="text-align: center !important;">{{number_format($item->total_amount,2)}}</td>
        <td style="text-align: left !important;"> <span class="bg-warning text-white" style="padding: 2px 3px;"> Awaiting Approval </span> </td>
        @if($item->due_amount > 0 and $item->paid_amount > 0)
        <td style="text-align: left !important;"> <span class="bg-warning text-white" style="padding: 2px 3px;"> Partial Paid </span> </td>
        @elseif($item->due_amount <= 0)
        <td style="text-align: left !important;"> <span class="bg-success text-white" style="padding: 2px 3px;"> Full Paid </span> </td>
        @else
        <td style="text-align: left !important;"> <span class="bg-danger text-white" style="padding: 2px 3px;"> Receivable </span>  </td>  </td>
        @endif

        <td> {{$item->paid_amount}} </td>
        <td> {{$item->due_amount}}  </td>
    </tr>
    @endforeach

@foreach ($sales as $key => $item)
@php
    $payment = 0;
    $project = $item->project;
    $payment = $item->tem_receipt_amount();


@endphp
<tr class="sale_view"  id="{{$item->id}}">
    <td>{{$key+1}}</td>
    <td style="text-align: center !important;">{{date('d/m/Y',strtotime($item->date))}}</td>

    <td style="text-align: center !important;">{{$item->invoice_no}}</td>

    <td style="text-align: left !important;" title="{{optional($item->party)->pi_name}}">
            {{\Illuminate\Support\Str::limit(optional($item->party)->pi_name,30)}}
    </td>

    <td style="text-align: left !important;" title="{{optional($project)->project_name}}">
        {{\Illuminate\Support\Str::limit(optional($project)->project_name,30)}}
    </td>
    <td class="text-left"> {{optional($project)->address}}</td>

    <td style="text-align: center !important;">{{number_format($item->total_budget,2)}}</td>
    <td style="text-align: left !important;">  <span class="bg-success text-white" style="padding: 2px 3px;"> Submitted </span>  </td>
    @if($item->due_amount > 0 and $item->paid_amount > 0)
    <td style="text-align: left !important;"> <span class="bg-warning text-white" style="padding: 2px 3px;"> Partial Paid </span> </td>
    @elseif($item->due_amount <= 0)
    <td style="text-align: left !important;"> <span class="bg-success text-white" style="padding: 2px 3px;"> Full Paid </span> </td>
    @else
    <td style="text-align: left !important;"> <span class="bg-danger text-white" style="padding: 2px 3px;"> Receivable </span>  </td>  </td>
    @endif
    <td> {{$item->paid_amount}} </td>
    <td> {{$item->due_amount}} </td>
</tr>
@endforeach


@foreach ($sales as $item)
    @php
        $project = $item->project;
        $new_project = $project?$project->new_project:null;
    @endphp

    <tr class="approve_view"  id="{{$item->id}}">
        <td style="text-align: center !important;">{{date('d/m/Y',strtotime($item->date))}}</td>
            @if($item->invoice_type == 'Proforma Invoice')
        <td style="text-align: center !important;">{{$item->proforma_invoice_no}}</td>
        @elseif($item->invoice_type == 'Direct Invoice')
        <td style="text-align: center !important;">{{$item->invoice_no_s_d}}</td>
        @else
        <td style="text-align: center !important;">{{$item->invoice_no}}</td>
        <td></td>
        @endif


        <td style="text-align: left !important;">{{optional($item->party)->pi_name}}</td>
        <td style="text-align: left !important;" title="{{optional($new_project)->name}}">
            {{\Illuminate\Support\Str::limit(optional($new_project)->name,30)}}
        </td>
        <td> {{optional($new_project)->plot}}</td>
         <td class="text-left">  {{optional($new_project)->location}}</td>

        <td style="text-align: center !important;">{{number_format($item->total_amount,2)}}</td>
        <td style="text-align: center !important;"> <span class="bg-warning text-white" style="padding: 2px 3px;"> Awaiting Approval </span> </td>
        @if($item->due_amount > 0 and $item->paid_amount > 0)
        <td style="text-align: center !important;"> Partial Payment Receipt </td>
        @elseif($item->due_amount <= 0)
        <td style="text-align: center !important;"> Full Payment Receipt </td>
        @else
        <td style="text-align: center !important;"> Receivable </td>
        @endif

        <td> {{$item->paid_amount}} </td>
        <td> {{$item->due_amount}} </td>
    </tr>
@endforeach

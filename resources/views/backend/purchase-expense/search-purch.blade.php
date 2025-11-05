@foreach ($expenses_temp as $key => $item)
    <tr class="approve_view"  id="{{$item->id}}">
        <td>{{$key+1}}</td>
        <td style="text-align: center !important;">{{date('d/m/Y',strtotime($item->date))}}</td>
        <td style="text-align: center !important;">{{$item->purchase_no}}</td>
        <td style="text-align: left !important;" title="{{optional($item->party)->pi_name}}">
            {{\Illuminate\Support\Str::limit(optional($item->party)->pi_name,30)}}
        </td>
        <td style="text-align: center !important;">{{number_format($item->total_amount,2)}}</td>
        <td style="text-align: left !important;"> <span class="bg-warning text-white"> Awaiting Approval </span> </td>
        @if($item->due_amount > 0 and $item->paid_amount > 0)
        <td style="text-align: left !important;"> <span class="bg-warning text-white"> Partial Paid </span> </td>
        @elseif($item->due_amount <= 0)
        <td style="text-align: left !important;"> <span class="bg-success text-white"> Full Paid </span> </td>
        @else
        <td style="text-align: left !important;"> <span class="bg-danger text-white"> Payable </span>  </td>  </td>
        @endif
        <td> {{$item->paid_amount}} </td>
        <td> {{$item->due_amount}} </td>
    </tr>
@endforeach
@foreach ($expenses as $key => $item)
    <tr class="expense_view"  id="{{$item->id}}">
        <td>{{$key+1}}</td>
        <td style="text-align: center !important;">{{date('d/m/Y',strtotime($item->date))}}</td>

        <td style="text-align: center !important;">{{$item->purchase_no}}</td>
        <td style="text-align: left !important;" title="{{optional($item->party)->pi_name}}">
            {{\Illuminate\Support\Str::limit(optional($item->party)->pi_name,30)}}
        </td>
        <td style="text-align: center !important;">{{number_format($item->total_amount,2)}}</td>
        <td style="text-align: left !important;">  <span class="bg-success text-white"> Submitted </span>  </td>
        @if($item->due_amount > 0 and $item->paid_amount > 0)
        <td style="text-align: left !important;"> <span class="bg-warning text-white"> Partial Paid </span> </td>
        @elseif($item->due_amount <= 0)
        <td style="text-align: left !important;"> <span class="bg-success text-white"> Full Paid </span> </td>
        @else
        <td style="text-align: left !important;"> <span class="bg-danger text-white"> Payable </span>  </td>  </td>
        @endif
        <td> {{$item->paid_amount}} </td>
        <td> {{$item->due_amount}} </td>
    </tr>
    @endforeach
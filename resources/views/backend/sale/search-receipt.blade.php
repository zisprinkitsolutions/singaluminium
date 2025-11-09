@foreach ($temp_receipt_list as $key => $item)
@php
    $project_name = '';
    $plot_no = '';
    $location = '';
    foreach($item->items as $receipt_sale){
        $invoice = $receipt_sale->invoice;
        $project = $invoice->project;
        $new_project = $project ? $project->new_project:null;
        if($new_project){
            $project_name .= $new_project->name .' ,';
            $plot_no .= $new_project->plot . ' ,';
            $location = $new_project->location . ', ';
        }
    }
@endphp
<tr class="receipt_view" id="{{$item->id}}">
    <td>{{$key+1}}</td>
    <td>{{date('d/m/Y',strtotime($item->date))}}</td>
    <td>{{$item->receipt_no}}</td>
    <td style="text-align: left !important;" title="{{optional($item->party)->pi_name}}">
        {{\Illuminate\Support\Str::limit(optional($item->party)->pi_name,30)}}
    </td>
    <td style="text-align:left" title="{{$project_name}}">
            {{\Illuminate\Support\Str::limit($project_name,30)}}
    </td>
    <td style="text-align:left" title="{{$plot_no}}">
            {{\Illuminate\Support\Str::limit($plot_no,30)}}
    </td>
    <td style="text-align:left" title="{{$location}}">
            {{\Illuminate\Support\Str::limit($location,30)}}
    </td>
    {{-- <td>{{$item->narration}}</td> --}}
    <td >{{number_format($item->total_amount,2)}} </td>
    <td style="min-width: fit-content">  <span class="bg-warning text-white" style="padding: 2px 3px;"> Awaiting Approve </span>  </td>
    <td >{{$item->pay_mode}}</td>
</tr>
@endforeach



@foreach ($receipt_list as $item)
@php
    $project_name = '';
    $plot_no = '';
    $location = '';

    foreach($item->items as $receipt_sale){
        $invoice = $receipt_sale->invoice;
        $project = $invoice->project;
        $new_project = $project ? $project->new_project:null;
        if($new_project){
            $project_name .= $new_project->name .' ,';
            $plot_no .= $new_project->plot . ' ,';
            $location = $new_project->location . ', ';
        }
    }
@endphp
<tr class="receipt_exp_view"  id="{{$item->id}}">
    <td>{{++$i}}</td>
    <td>{{date('d/m/Y',strtotime($item->date))}}</td>

    <td>{{$item->receipt_no}}</td>
        <td style="text-align: left !important;" title="{{optional($item->party)->pi_name}}">
            {{\Illuminate\Support\Str::limit(optional($item->party)->pi_name,30)}}
    </td>
        <td style="text-align:left" title="{{$project_name}}">
            {{\Illuminate\Support\Str::limit($project_name,30)}}
    </td>
    <td style="text-align:left" title="{{$plot_no}}">
            {{\Illuminate\Support\Str::limit($plot_no,30)}}
    </td>
    <td style="text-align:left" title="{{$location}}">
            {{\Illuminate\Support\Str::limit($location,30)}}
    </td>

    {{-- <td>{{$item->narration}}</td> --}}
    <td >{{number_format($item->total_amount,2)}}</td>
    <td style="min-width: fit-content"> <span class="bg-success text-white" style="padding: 2px 3px;"> Approved </span> </td>
    <td >{{$item->pay_mode}}</td>
</tr>

@endforeach
<tr style="background: #394c62 !important; color: #fff !important;">
    <td colspan="7" class="text-right " style=" color: #fff !important">Total</td>
    <td colspan="" style=" color: #fff !important">{{ number_format($temp_receipt_list->sum('total_amount')+$receipt_list->sum('total_amount'), 2) }}</td>
    <td colspan="2"></td>
</tr>

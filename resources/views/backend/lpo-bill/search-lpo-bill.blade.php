@foreach ($expenses as $key => $item)
<tr class="lpo_bill_view" id="{{ $item->id }}" style="text-align:center;">
    <td>{{ ($expenses->currentPage() - 1) * $expenses->perPage() + $key + 1 }}</td>
    <td style="text-align: left !important;" title="{{ optional($item->party)->pi_name }}">
        {{ \Illuminate\Support\Str::limit(optional($item->party)->pi_name, 30) }}
    </td>
    <td>{{ date('d/m/Y', strtotime($item->date)) }}</td>
    <td>{{ $item->lpo_bill_no }}</td>
    <td style="text-align: left !important;" title="{{ $item->project->project_name??'' }}">
        {{ \Illuminate\Support\Str::limit($item->project->project_name??'', 30) }}
    </td>
    <td>{{number_format($item->total_amount,2) }}</td>
    <td>{{$item->status}}</td>
</tr>

@endforeach

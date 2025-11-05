@foreach ($expenses as $key => $item)
<tr style="cursor: pointer;" class="lpo_bill_view" data-url="{{ route('requisitions.show', $item->id) }}">
    <td>{{ ($expenses->currentPage() - 1) * $expenses->perPage() + $key + 1 }}</td>
    <td>{{ date('d/m/Y', strtotime($item->date)) }}</td>
    <td>{{ $item->requisition_no }}</td>
    <td class="text-left ml-1">{{ $item->project->project_name ?? '-' }}</td>
    <td title="@if($item->status == 'Rejected') {{ $item->note }} @endif">
        <span class="badge
        @if($item->status == 'Approved') bg-success
        @elseif($item->status == 'Rejected') bg-danger
        @elseif($item->status == 'Created') bg-warning text-dark
        @else bg-secondary @endif">
            {{ $item->status }}
        </span>
    </td>
    <td>
        <button class="btn btn-sm view-details" data-url="{{ route('requisitions.show', $item->id) }}">
            <i class="fas fa-eye"></i>
        </button>
    </td>
</tr>
@endforeach

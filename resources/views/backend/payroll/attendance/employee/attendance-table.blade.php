@if(isset($attendances) && $attendances->count() > 0)
@foreach ($attendances as $key => $data)
<tr class="text-center">
    <td>{{ $key + 1 }}</td>
    <td>{{ \Carbon\Carbon::parse($data->date)->format('d/m/Y') }}</td>
    <td>
        @if($data->status == 1)
        YES
        @elseif($data->status == 0)
        Absen
        @elseif($data->status == 2)
        Leave
        @elseif($data->status == 3)
        Weekend
        @else
        NO
        @endif
    </td>
    {{-- morning attendance --}}
    <td>{{ $data->in_time }}</td>
    <td>{{ $data->out_time }}</td>
    <td>{{ $data->reference_in_time }}</td>
    <td>{{ $data->reference_out_time }}</td>

    {{-- evening attendance --}}
    <td>{{ $data->evening_in }}</td>
    <td>{{ $data->evening_out }}</td>
    <td>{{ $data->e_reference_in_time }}</td>
    <td>{{ $data->e_reference_out_time }}</td>


    <td>{{ $data->total_late_time ?? 'N/A' }}</td>
    <td>{{ $data->total_overtime ?? 'N/A' }}</td>
    <td>{{ $data->total_working_hours ?? 'N/A' }}</td>
</tr>
@endforeach
@else
<tr>
    <td colspan="9" class="text-center">No Attendance Found !!</td>
</tr>
@endif

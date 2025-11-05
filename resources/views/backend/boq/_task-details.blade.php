@foreach ($boq->tasks as $task)
    <tr>
        <td style="font-size: 13px !important" class="task_name"> {{$task->name}} </td>
        @if ($task->progress < 25)
            <td class="progress text-warning" data-progress="{{$task->progress}}"> Progress {{$task->progress}} % </td>
        @elseif($task->progress > 25 && $task->progress < 60)
            <td class="progress text-info" data-progress="{{$task->progress}}"> Progress {{$task->progress}} % </td>
        @else
            <td class="progress text-success" data-progress="{{$task->progress}}"> Progress {{$task->progress}} % </td>
        @endif

        <td style="font-size: 13px !important" class="start_date text-center">  {{$task->start_date ? date('d/m/Y', strtotime($task->start_date)) : null}} </td>
        <td style="font-size: 13px !important" class="end_date text-center">  {{$task->start_date ? date('d/m/Y', strtotime($task->start_date)) : null}} </td>
        <td colspan="3"> </td>
        <td style="font-size: 13px !important" class="text-right"> {{$task->contact_amount}} </td>
        <td style="font-size: 13px !important" class="text-right"> {{$task->estimated_expense}} </td>
        <td class="d-flex justify-content-center">

            <button type="button" data-url="{{ route('boq.items.custom-destroy', [$task->id, 'task']) }}" class="text-danger border-0 bg-transparent delete-btn">
                <i class="bx bx-trash"></i>
            </button>

            <button type="button" style="border: none;" class="text-info edit" data-id="{{$task->id}}" data-type="task"><i class="bx bx-edit"></i> </button>
        </td>
    </tr>

    @foreach ($task->items as $item)
    <tr>
        <td></td>
        <td style="font-size: 12px !important" class="item_name" colspan="3">{{$item->item_description}} </td>
        <td style="font-size: 12px !important" class="text-center unit">{{$item->unit}} </td>
        <td style="font-size: 12px !important" class="text-right qty">{{$item->qty}} </td>
        <td style="font-size: 12px !important" class="text-right rate">{{$item->rate}} </td>
        <td style="font-size: 12px !important" class="text-right total">{{$item->total}} </td>
            <td style="font-size: 13px !important" class="text-right estimated_expense"> {{$item->estimated_expense}} </td>
            <td class="d-flex justify-content-center">
            <button type="button" data-url="{{ route('boq.items.custom-destroy', [$item->id, 'item']) }}" class="text-danger border-0 bg-transparent delete-btn">
                <i class="bx bx-trash"></i>
            </button>

            <button type="button" style="border: none;" class="text-info edit" data-id="{{$item->id}}" data-task="{{$task->id}}" data-type="item"> <i class="bx bx-edit"></i></button>
        </td>
    </tr>
    @endforeach
@endforeach

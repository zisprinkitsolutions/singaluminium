@php
    $key = 0;
@endphp
@foreach ($results as $task => $task_items)
    <tr class="task-row" data-task-index="{{$key}}">
        <td style="width:15%;">
            <div class="d-flex">
                <input type="text" name="task_name[{{$key}}]" class="form-control inputFieldHeight task-name" value="{{$task}}" placeholder="Task Name">
            </div>
        </td>

        <td colspan="8" style="width:85%;">
            <table class="table table-sm mb-0" style="width:100%;">
                <tbody class="item-body">
                @foreach($task_items as $index => $item)
                    <tr>
                        <td style="width:25%;border:none;">
                            <input type="text" name="item_description[{{$key}}][{{$index}}]" class="form-control inputFieldHeight" placeholder="Item Details" autocomplete="off" value="{{$item['item_description']}}">
                        </td>
                        <td style="width:10%; border:none;">
                            <input type="text" name="unit[{{$key}}][{{$index}}]" class="form-control inputFieldHeight text-center" placeholder="Unit" required autocomplete="off" value="{{$item['unit']}}">
                        </td>
                        <td style="width:10%; border:none;">
                            <input type="number" name="qty[{{$key}}][{{$index}}]" class="form-control inputFieldHeight qty text-right" placeholder="Qty" autocomplete="off" value="{{number_format($item['qty'],2)}}">
                        </td>
                        <td style="width:10%; border:none;">
                            <input type="text" name="rate[{{$key}}][{{$index}}]" class="form-control inputFieldHeight rate text-right" placeholder="Rate" autocomplete="off" value="{{number_format($item['rate'],2)}}">
                        </td>
                        <td style="width:10%; border:none;">
                            <input type="text" name="amount[{{$key}}][{{$index}}]" class="form-control inputFieldHeight total text-right" placeholder="Amount" required value="{{number_format($item['amount'],2)}}">
                        </td>
                        <td style="width:5%; border:none;">
                            <div class="d-flex">
                                <button type="button" class="addItemBtn bg-info text-white" style="border: 1px solid #ddd;" title="Add Item">+</button>
                                <button type="button" class="removeItemBtn bg-danger text-white" style="border: 1px solid #ddd;" title="Remove Item">X</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </td>
        <td style="width:5%;">
            <button type="button" class="removeTaskBtn bg-danger text-white" style="border: 1px solid #ddd;" title="Remove Task">X</button>
        </td>

        @php
            $key++;
        @endphp
    </tr>
    @endforeach

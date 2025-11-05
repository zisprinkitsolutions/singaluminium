@extends('layouts.backend.app')
@section('content')
<style>
    .table thead th {
    color: #ffffff !important;
    text-align: center;

  }
  .mini-width {
    max-width: 90px !important;
  }
  .mini-width-input {
    max-width: 80px !important;
  }
  .semi-mini-width{
    max-width: 120px !important;
  }
  .semi-mini-width-input{
    max-width: 100px !important;
  }
  .table-sm th, .table-sm td {
    padding: 0.01rem !important;
  }
  input, select {
    height: 25px !important;
  }
  .div_error{
    border: 1px solid red;
  }
  .div_right{
    border: 1px solid black;
  }
</style>
@include('layouts.backend.partial.style')
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <form action="{{route('boq.store')}}" class="mt-1" method="POST">
                @csrf
                <input type="hidden" name="status" id="formStatus" value="final">
                <div class="row">
                    <div class="col-md-3 from-group">
                        <label for=""> Company Name </label>
                        <select name="party_id" id="party_id" required class="form-control common-select2 inputFieldHeight" required>
                            <option value="{{$parties->id}}" selected> {{$parties->pi_name}} </option>
                        </select>
                    </div>

                    <div class="col-md-3 from-group">
                        <label for=""> Project Name </label>
                        <select name="project_name" id="project_id" class="form-control inputFieldHeight inputFieldHeight" required>
                            <option value="{{$project_info->id}}" selected> {{$project_info->name}} </option>
                        </select>
                    </div>

                    <div class="col-md-3 from-group">
                        <label for=""> Date </label>
                        <input type="text" name="date" class="form-control inputFieldHeight datepicker inputFieldHeight" required autocomplete="off" value="{{date('d/m/Y')}}">
                    </div>

                    <div class="col-12">
                        <div class="table-responsive boq-table mt-2">
                            <table class="table table-sm">
                                <thead style="background-color:#34465b !important;">
                                    <tr>
                                        <th class="text-left text-white" style="width:20%; padding:5px 7px;">Task name</th>
                                        <th class="text-center text-white" style="width:75%; padding:5px 7px;" colspan="8"> Item Details </th>
                                        <th class="text-right text-white" style="width:5%; padding:5px 7px;">
                                            <button type="button" id="addTask" class="addItemBtn bg-info text-white" title="Add Task" style="border: 1px solid #ddd;">+</button>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th colspan="8" style="padding:0">
                                            <table class="table table-sm mb-0" style="width:100%;">
                                                <thead>
                                                    <tr style="background-color:#34465b !important;">
                                                        <th class="text-left text-white" style="width:25%; padding:5px 7px;"> Item Description </th>
                                                        <th class="text-center text-white" style="width:10%; padding:5px 7px;">Unit</th>
                                                        <th class="text-right text-white" style="width:10%; padding:5px 7px;">Qty</th>
                                                        <th class="text-right text-white" style="width:10%; padding:5px 7px;">Rate</th>
                                                        <th class="text-right text-white" style="width:10%; padding:5px 7px;">Total ({{$currency->symbole}})</th>
                                                        <th class="text-center text-white" style="width:5%; padding:5px 7px;">Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                @php
                                    $key = 0;
                                @endphp
                                <tbody id="boq-body">
                                    @foreach ($records as $key => $task)
                                    <tr class="task-row" data-task-index="{{$key}}">
                                        <td style="width:15%;">
                                            <div class="d-flex">
                                                <input type="text" name="task_name[{{$key}}]" class="form-control inputFieldHeight task-name" value="{{$task->item_task}}" placeholder="Task Name">
                                            </div>
                                        </td>
                                        <td colspan="8" style="width:85%;">
                                            <table class="table table-sm mb-0" style="width:100%;">
                                                <tbody class="item-body">
                                                @foreach($task->items as $index => $item)
                                                    <tr>
                                                        <td style="width:25%;border:none;">
                                                            <input type="text" name="item_description[{{$key}}][{{$index}}]" class="form-control inputFieldHeight" placeholder="Item Details" autocomplete="off" value="{{$item->description}}">
                                                        </td>
                                                        <td style="width:10%; border:none;">
                                                            <input type="text" name="unit[{{$key}}][{{$index}}]" class="form-control inputFieldHeight text-center" placeholder="Unit" required autocomplete="off" value="{{$item->unit}}">
                                                        </td>
                                                        <td style="width:10%; border:none;">
                                                            <input type="number" name="qty[{{$key}}][{{$index}}]" class="form-control inputFieldHeight qty text-right" placeholder="Qty" autocomplete="off" value="{{$item->qty}}">
                                                        </td>
                                                        <td style="width:10%; border:none;">
                                                            <input type="text" name="rate[{{$key}}][{{$index}}]" class="form-control inputFieldHeight rate text-right" placeholder="Rate" autocomplete="off" value="{{$item->rate}}">
                                                        </td>
                                                        <td style="width:10%; border:none;">
                                                            <input type="text" name="amount[{{$key}}][{{$index}}]" class="form-control inputFieldHeight total text-right" placeholder="Amount" required value="{{$item->amount}}">
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
                                    </tr>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary ml-1"> Save </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('js')
    <script>

        $(document).on('change', '#party_id', function(){
            var party_id = $(this).val();
            var url = "{{route('get.party.project',':party_id')}}";
            url = url.replace(':party_id', party_id);

            $.ajax({
                url:url,
                type:'GET',
                success:function(res){
                    $('#project_id').html('<option value=""> Select </option>');
                    res.projects.forEach(function(project){
                        $('#project_id').append(`<option value="${project.id}"> ${project.name} </option>`);
                    });
                }
            });
        });
        let taskIndex = {{$key + 1}};
        var tasks = @json($records);
        $('#addTask').on('click', function () {
            const itemIndex = 0;

            const taskRow = `
                <tr class="task-row" data-task-index="${taskIndex}">
                    <td style="width:15%;">
                        <div class="d-flex">
                            <input type="text" name="task_name[${taskIndex}]" class="form-control inputFieldHeight task-name" placeholder="Task Name">
                        </div>
                    </td>
                    <td colspan="8" style="width:85%;">
                        <table class="table table-sm mb-0" style="width:100%;">
                            <tbody class="item-body">
                                <tr>
                                    <td style="width:25%; border:none;">
                                        <input type="text" name="item_description[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight" placeholder="Item Details" autocomplete="off">
                                    </td>
                                    <td style="width:10%">
                                        <input type="text" name="unit[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight text-center" placeholder="Unit" required autocomplete="off">
                                    </td>
                                    <td style="width:10%; border:none;">
                                        <input type="number" name="qty[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight qty text-right" placeholder="Qty" autocomplete="off">
                                    </td>
                                    <td style="width:10%; border:none;">
                                        <input type="text" name="rate[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight rate text-right" placeholder="Rate" autocomplete="off">
                                    </td>
                                    <td style="width:10%; border:none;">
                                        <input type="text" name="amount[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight total text-right" placeholder="Amount" required>
                                    </td>
                                    <td style="width:5%">
                                        <div class="d-flex">
                                            <button type="button" class="addItemBtn bg-info text-white" style="border: 1px solid #ddd;" title="Add Item">+</button>
                                            <button type="button" class="removeItemBtn bg-danger text-white" style="border: 1px solid #ddd;" title="Remove Item">X</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="width:5%;">
                        <button type="button" class="removeTaskBtn bg-danger text-white" style="border: 1px solid #ddd;" title="Remove Task">X</button>
                    </td>
                </tr>
            `;

            $('#boq-body').append(taskRow);
            taskIndex++;
        });
        $(document).on('change', '#project_id', function(){
            var project_id = $(this).val();
            var url = "{{route('get.project.task',':project_id')}}";
            url = url.replace(':project_id', project_id);
            $.ajax({
                url:url,
                type:'GET',
                success:function(res){
                    tasks = res.tasks;
                }
            });
        });




        $(document).on('change', '.task_id', function () {
            const $taskRow = $(this).closest('.task-row');
            const $subTaskBody = $taskRow.find('.subtask-row');
            $subTaskBody.empty();

            const selectedOption = $(this).find('option:selected');
            const subtasks = selectedOption.data('subtasks');

            if (Array.isArray(subtasks)) {
                subtasks.forEach(function (subtask, index) {
                    addSubtask(subtask, $taskRow, subtasks);
                });
            }
        });


        // Add item
        $(document).on('click', '.addItemBtn', function () {
            const $itemBody = $(this).closest('tbody');
            const $taskRow = $(this).closest('tr.task-row');
            const taskIndex = $taskRow.data('task-index');
            const itemIndex = $itemBody.find('tr').length; // New item index

            const itemRow = `
                <tr>
                    <td style="width:25%; border:none;">
                        <input type="text" name="item_description[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight" placeholder="Item Details" autocomplete="off">
                    </td>
                    <td style="width:10%; border:none;">
                        <input type="text" name="unit[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight text-center" placeholder="Unit" autocomplete="off">
                    </td>
                    <td style="width:10%; border:none;">
                        <input type="number" name="qty[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight qty text-right" placeholder="Qty" autocomplete="off">
                    </td>
                    <td style="width:10%; border:none;">
                        <input type="text" name="rate[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight rate text-right" placeholder="Rate" autocomplete="off">
                    </td>
                    <td style="width:10%; border:none;">
                        <input type="text" name="amount[${taskIndex}][${itemIndex}]" class="form-control inputFieldHeight total text-right" placeholder="Amount">
                    </td>
                    <td style="width:5%; border:none;">
                        <div class="d-flex">
                            <button type="button" class="addItemBtn bg-info text-white" style="border: 1px solid #ddd;" title="Add Item">+</button>
                            <button type="button" class="removeItemBtn bg-danger text-white" style="border: 1px solid #ddd;" title="Remove Item">X</button>
                        </div>
                    </td>
                </tr>
            `;

            $itemBody.append(itemRow);
        });

        $(document).on('click', '.removeItemBtn', function () {
            $(this).closest('tr').remove();
        });
        // Remove task
        $(document).on('click', '.removeTaskBtn', function () {
            $(this).closest('.task-row').remove();
        });

        $(document).on('click', '.removeSubTaskBtn', function () {
            $(this).closest('.subtask-row').remove();
        })


        $(document).on('click', '.task-toggler', function () {
            var td = $(this).closest('td');
            var task_id = td.find('select');
            var task_name = td.find('.task-name');

            task_id.each(function () {
                if ($(this).hasClass('d-none')) {
                    $(this).removeClass('d-none').prop('disabled', false);
                } else {
                    $(this).addClass('d-none').val('').prop('disabled', true);
                }
            });

            task_name.each(function (){
                if ($(this).hasClass('d-none')) {
                    $(this).removeClass('d-none').prop('disabled', false);
                } else {
                    $(this).addClass('d-none');
                }
            });
        });

        function calculateTotal(){
            $('.qty').each(function(){
                var qty = parseFloat($(this).val());
                var rate = parseFloat($(this).closest('tr').find('.rate').val());
                var total = qty * rate;

                $(this).closest('tr').find('.total').val(total.toFixed(2));
            })
        }

        $(document).on('keyup', '.qty, .rate', function () {
            calculateTotal();
        });

        $('.darft').on('click', function (e) {
            e.preventDefault();
            $('#formStatus').val('draft');
            $('form').submit();
        });

    </script>
@endpush

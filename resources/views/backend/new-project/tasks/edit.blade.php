@extends('layouts.backend.app')
@push('css')
    @include('layouts.backend.partial.style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
    <style>
        body {
            counter-reset: Serial;
        }

        .project-btn {
            border: none;
            color: #fff;
            font-size: 15px;
            font-weight: 500px;
            padding: 3px 10px;
            border-radius: 5px;
        }

        .add_items {
            background: #4CB648;
        }

        .delete_items {
            background: #EA5455;
            padding: 3px 3px 2px 3px;
            font-size: 13px;
        }


        #input-container .form-control {
            border: none;
            height: 30px !important;
        }

        #input-container .form-control:focus {
            border: 1px solid #4CB648;
        }

        .tasks-title,
        .budget-title {
            font-size: 16px;
            color: #313131;
            font-weight: 500;
            text-transform: capitalize;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-size: 16px !important;
        }

        .select2-container--default .select2-selection--single {
            height: 35px !important;
        }

        .add-customer {
            background: #4A47A3;
            padding: 2px 4px !important;
            margin: 0 !important;
        }
        .save-btn {
            background: #406343;
        }

        input.form-control {
            height: 35px !important;
        }
        .sub-btn {
            border: 1px solid #475F7B !important;
            background-color: #fff !important;
            border-radius: 15px !important;
            color: #475F7B !important;
            padding: 3px 6px 3px 6px !important;
        },
        .action-btn {
            background-color: #5F6F94;
            height: 35px;
        }
        .sub-btn:hover,
        .sub-btn.active {
            background-color: #34465b !important;
            color: white !important;
        }
        .sub-btn.active:hover {
            background-color: #c8d6e357 !important;
            color: black !important;
        }
        .form-control,
        .project-btn {
            height: 30px;
        }
        .date_type:focus,
        .date_type:active {
            border: border 1px solid #313131;
        }
        .note-editor p{
            line-height: 23px !important;
            margin: 0;
        }
    </style>
@endpush

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.project._header')
                <div class="tab-content bg-white">
                    <div id="journaCreation" class="tab-pane active">

                        <section class="p-1" id="widgets-Statistics">
                            <form class="repeater mt-1 project-form" action="{{ route('project.tasks.update', $task->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-2">
                                            <label for="task">Task Name</label>

                                            <!-- Flex row: input + button -->
                                            <div class="d-flex align-items-center">
                                                <input type="text" class="form-control inputFieldHeight me-2" id="task" name="task_name" required
                                                    autocomplete="off" value="{{ $task->name }}" style="flex: 1;">

                                                <a href="{{ route('project.tasks.index') }}" class="btn btn-info project-btn ml-1" style="height: 35px">
                                                    Tasks List
                                                </a>
                                            </div>

                                            @error('project_name')
                                            <p class="text-danger mt-1"> {{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <table class="auto-index repeater1 table table-sm">
                                    <thead style="background-color:#34465b !important;">

                                        <tr>
                                            <th class="text-left text-white" style="width:15%; padding:5px 7px;"> Sub Task</th>
                                            <th class="text-center text-white" style="width:85%; padding:5px 7px;" colspan="8"> Item Details </th>
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
                                                            <th class="text-left text-white" style="width:50%; padding:5px 7px;"> Item </th>
                                                            <th class="text-center text-white" style="width:10%; padding:5px 7px;">Unit</th>
                                                            <th class="text-right text-white" style="width:10%; padding:5px 7px;">Qty</th>
                                                            <th class="text-right text-white" style="width:10%; padding:5px 7px;">Rate</th>
                                                            <th class="text-right text-white" style="width:15%; padding:5px 7px;">Total ({{$currency->symbole}})</th>
                                                            <th style="width:5%;"></th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="boq-body">
                                        @foreach ($task->sub_tasks as $key => $sub_task)
                                        <tr class="task-row" data-task-index="{{$key}}">
                                            <td style="width:15%;">
                                                <div class="d-flex">
                                                    <input type="text" name="subtask_name[{{$key}}]" class="form-control task-name" placeholder="SubTask" value="{{$sub_task->name}}">
                                                </div>
                                            </td>
                                            <td colspan="8" style="width:85%;">
                                                <table class="table table-sm mb-0" style="width:100%;">
                                                    <tbody class="item-body">
                                                        @foreach ($sub_task->items as $sub_key => $item)
                                                            <tr>
                                                                <td style="width:width:50%"><input type="text" name="item_description[{{$key}}][{{$sub_key}}]" class="form-control" placeholder="Item Description" autocomplete="off" value="{{$item->item_description}}"></td>
                                                                <td style="width:10%"><input type="text" name="unit[{{$key}}][{{$sub_key}}]" class="form-control text-center" placeholder="Unit" required autocomplete="off" value="{{$item->unit}}"></td>
                                                                <td style="width:10%"><input type="number" name="qty[{{$key}}][{{$sub_key}}]" class="form-control qty text-right" placeholder="Qty" autocomplete="off" value="{{floatval($item->qty)}}"></td>
                                                                <td style="width:10%"><input type="text" name="rate[{{$key}}][{{$sub_key}}]" class="form-control rate text-right" placeholder="Rate" autocomplete="off" value="{{$item->rate}}"></td>
                                                                <td style="width:15%"><input type="text" name="amount[{{$key}}][{{$sub_key}}]" class="form-control total text-right" placeholder="Amount" required value="{{$item->total}}" readonly></td>
                                                                <td style="width:5%">
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
                                                <button type="button" class="removeTaskBtn bg-danger text-white" style="border: 1px solid #ddd;" title="Remove Task" >X</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="7" class="text-right" style="width:80%;"><strong>Total</strong></td>
                                            <td>
                                                <input type="number" name="subtotal" readonly
                                                                step="0.01" class="form-control text-right subtotal" value="{{$task->total_amount}}">
                                            </td>
                                        </tr>

                                    </tfoot>
                                </table>

                                <div class="d-flex justify-content-center mt-1">
                                    <button type="submit" class="project-btn save-btn"> Save </button>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote();
            $('.date').datepicker({
                dateFormat: 'dd/mm/yy'
            })
            $('.customer_id').select2();
            $(".add_items").click(function() {
                addInput();
            });
            var i = 0;
        });

        let taskIndex = '{{$key + 1}}';

        $('#addTask').on('click', function () {
            const itemIndex = 0;
            const taskRow = `
                <tr class="task-row" data-task-index="${taskIndex}">
                <td style="width:15%;">
                    <div class="d-flex">
                        <input type="text" name="subtask_name[${taskIndex}]" class="form-control task-name" placeholder="SubTask">
                    </div>
                </td>
                <td colspan="8" style="width:85%;">
                    <table class="table table-sm mb-0" style="width:100%;">
                        <tbody class="item-body">
                            <tr>
                                <td style="width:50%"><input type="text" name="item_description[${taskIndex}][${itemIndex}]" class="form-control" placeholder="Item Description" autocomplete="off"></td>
                                <td style="width:10%"><input type="text" name="unit[${taskIndex}][${itemIndex}]" class="form-control text-center" placeholder="Unit" required autocomplete="off"></td>
                                <td style="width:10%"><input type="number" name="qty[${taskIndex}][${itemIndex}]" class="form-control qty text-right" placeholder="Qty" autocomplete="off"></td>
                                <td style="width:10%"><input type="text" name="rate[${taskIndex}][${itemIndex}]" class="form-control rate text-right" placeholder="Rate" autocomplete="off"></td>
                                <td style="width:15%"><input type="text" name="amount[${taskIndex}][${itemIndex}]" class="form-control total text-right" placeholder="Amount" required></td>
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
                    <button type="button" class="removeTaskBtn bg-danger text-white" style="border: 1px solid #ddd;" title="Remove Task" >X</button>
                </td>
                </tr>
            `;

            $('#boq-body').append(taskRow);
            taskIndex++;
        });

        // Remove task
        $(document).on('click', '.removeTaskBtn', function () {
            $(this).closest('.task-row').remove();
            calculateTotal();
        });

        $(document).on('click', '.removeItemBtn', function () {
            $(this).closest('tr').remove();
            calculateTotal();
        });

        // Add item
        $(document).on('click', '.addItemBtn', function () {
            const $taskRow = $(this).closest('.task-row');
            const taskIndex = $taskRow.data('task-index');
            const $itemBody = $taskRow.find('.item-body');
            const itemIndex = $itemBody.children('tr').length;

            const itemRow = `
            <tr>
                <td style="width:50%"><input type="text" name="item_description[${taskIndex}][${itemIndex}]" class="form-control" placeholder="Item Description" required autocomplete="off"></td>
                <td style="width:10%"><input type="text" name="unit[${taskIndex}][${itemIndex}]" class="form-control  text-center" placeholder="Unit" required autocomplete="off"></td>
                <td style="width:10%"><input type="number" name="qty[${taskIndex}][${itemIndex}]" class="form-control qty text-right" placeholder="Qty" required autocomplete="off"></td>
                <td style="width:10%"><input type="text" name="rate[${taskIndex}][${itemIndex}]" class="form-control rate text-right" placeholder="Rate" required autocomplete="off"></td>
                <td style="width:10%"><input type="text" name="amount[${taskIndex}][${itemIndex}]" class="form-control total text-right" placeholder="Amount" required readonly autocomplete="off"></td>
                <td style="width:5%">
                    <div class="d-flex">
                        <button type="button" class="addItemBtn bg-info text-white" style="border: 1px solid #ddd;" title="Add Item">+</button>
                        <button type="button" class="removeItemBtn bg-danger text-white" style="border: 1px solid #ddd;" title="Remove Item">X</button>
                    </div>

                </td>
            </tr>
            `;

            $itemBody.append(itemRow);
        });


        function calculateTotal(){
            var subtotal = 0;
            var total_expense = 0;
            $('.qty').each(function(){
                var qty = parseFloat($(this).val());
                var rate = parseFloat($(this).closest('tr').find('.rate').val());
                var expense = parseFloat($(this).closest('tr').find('.expense').val());
                var total = qty * rate;
                subtotal += total;
                total_expense += expense;
                $(this).closest('tr').find('.total').val(total.toFixed(2));
            })
            $('.subtotal').val(parseFloat(subtotal).toFixed(2))
            let discount = parseFloat($('.discount').val()) || 0;
            var total_amount = subtotal - discount;

            total_amount = total_amount;
            $('.total_amount').val(parseFloat(total_amount).toFixed(2))
            $('.total_amount').attr('min', 1)
            $('.total_amount').attr('max' , parseFloat(total_amount).toFixed(2));
            $('.total_expense').val(parseFloat(total_expense).toFixed(2));
            $('.advance_amount').attr('max' , parseFloat(total_amount).toFixed(2));
            $('.due_amount').val(total_amount.toFixed(2));
        }

        $(document).on('keyup', '.qty, .rate, .expense', function () {
            calculateTotal();
        });
    </script>
@endpush

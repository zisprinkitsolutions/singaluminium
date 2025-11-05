@extends('layouts.backend.app')
@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@endpush
@section('content')
    @include('backend.tab-file.style')
    <style>
        tr:nth-child(even) {
            background-color: #c8d6e357;
        }

        a.text-dark:hover,
        a.text-dark:focus {
            color: #ffffff !important;
        }

        .btn-outline-secondary {
            border-radius: 40px;
            padding: 0.2px 9px 0.2px 9px !important;
        }

        .table .thead-light th {
            color: #F2F4F4;
            background-color: #34465b;
            border-color: #DFE3E7;
        }
    </style>

    <div class="app-content content print-hideen">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.project._header', ['activeMenu' => 'new-project'])
                <div class="tab-content bg-white p-2 active">
                    <div class="tab-pane active">
                        <div>
                            <section>
                                <div class="row mb-1">
                                    <div class="col-md-8 text-left">

                                        @if (Auth::user()->hasPermission('ProjectManagement_Create'))
                                            <a class="btn btn-xs btn-primary  formButton" title="Create BOQ Sample"
                                                data-toggle="modal" data-target="#boq-factor">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img src="{{ asset('/icon/trial-balence-icon.png') }}"
                                                            width="25">
                                                    </div>
                                                    <div><span> Create BOQ Sample </span></div>
                                                </div>
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="cardStyleChange">
                                    <table class="table mb-0 table-sm table-hover">
                                        <thead class="thead-light">
                                            <tr style="height: 40px;">
                                                <th style="width:15%;" class="text-left"> Task </th>
                                                <th style="width:20%;" class="text-left"> item description </th>
                                                <th style="width:10%" class="text-center"> House Type </th>
                                                <th style="width:10%" class="text-center"> Work Type </th>
                                                <th style="width:10%" class="text-center"> Cost Factor </th>
                                                <th style="width:10%" class="text-center"> Length </th>
                                                <th style="width:10%" class="text-center"> Unit </th>
                                                <th style="width:10%" class="text-center"> Qty </th>
                                                <th style="width:10%" class="text-center"> Priority </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($boq_samples as $key => $sample)
                                                <tr class="sample-edit" data-sample='@json($sample)'
                                                    style="height: 40px; border-bottom: 1px solid #e0e0e0;">
                                                    <td style="text-align:left;"> {{ optional($sample->task)->name }} </td>
                                                    <td style="text-align:left;">
                                                        {{ optional($sample->item)->item_description }} </td>
                                                    <td style="text-align:center; text-transform:capitalize;">
                                                        {{ $sample->house_type }} </td>
                                                    <td style="text-align:center; text-transform:capitalize">
                                                        {{ $sample->work_type }} </td>
                                                    <td style="text-align:center; text-transform:capitalize">
                                                        {{ $sample->cost_factor }} </td>
                                                    <td style="text-align:center; text-transform:capitalize">
                                                        {{ $sample->area }} </td>
                                                    <td style="text-align:center; text-transform:capitalize">
                                                        {{ optional($sample->boq_unit)->name }} </td>
                                                    <td style="text-align:center; text-transform:capitalize">
                                                        {{ $sample->qty }} </td>
                                                    <td style="text-align:center; text-transform:capitalize">
                                                        {{ $sample->priority }} </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-1">
                                    {{ $boq_samples->links() }}
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal" id="boq-factor">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header" style="background: #364a60; padding:10px !important;">
                    <h4 class="modal-title text-white"> BOQ Sample </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <form action="{{ route('storeBoqFactor') }}" method="POST">
                    <div class="modal-body" style="padding:10px !important;">
                        @csrf
                        <table class="table mb-0 table-sm table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-left" style="width:15%;">Project Task</th>
                                    <th class="text-left" style="width:15%;">Item</th>
                                    <th class="text-left" style="width:10%;">House Type</th>
                                    <th class="text-left" style="width:10%;">Work Type</th>
                                    <th class="text-center" style="width:10%;">Cost Factor</th>
                                    <th class="text-center" style="width:7%;"> Length </th>
                                    <th class="text-center" style="width:7%;">Unit</th>
                                    <th class="text-center" style="width:6%;">Qty</th>
                                    <th class="text-left" style="width:10%;">Priority</th>
                                    <th class="text-left" style="width:10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-left">
                                        <select name="project_task[0]" id="project_task"
                                            class="form-control inputFieldHeight common-select2 project_task text-left"
                                            required>
                                            <option value=""> Select </option>
                                            @foreach ($tasks as $task)
                                                <option value="{{ $task->id }}"
                                                    data-items='@json($task->items)'>{{ $task->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <select name="project_item[0]" id="project_item"
                                            class="form-control inputFieldHeight project_item text-left" required>
                                            <option value=""> Select </option>
                                        </select>
                                    </td>

                                    <td>
                                        <select name="house_type[0]" id="house_type"
                                            class="form-control inputFieldHeight house_type text-left" required>
                                            <option value=""> Select </option>
                                            <option value="residential"> Residential </option>
                                            <option value="villa"> Villa </option>
                                            <option value="apartment"> Apartment</option>
                                        </select>
                                    </td>

                                    <td>
                                        <select name="work_type[0]" id="work_type"
                                            class="form-control inputFieldHeight work_type text-left" required>
                                            <option value=""> Select </option>
                                            <option value="standard"> Standard</option>
                                            <option value="deluxe"> Deluxe </option>
                                            <option value="premium"> Premium </option>
                                        </select>
                                    </td>

                                    <td>
                                        <input type="number" step="any" name="cost_factor[]"
                                            class="form-control inputFieldHeight text-center cost_factor" required
                                            value="1">
                                    </td>

                                    <td>
                                        <input type="number" step="any" name="area[]"
                                            class="form-control inputFieldHeight text-center area">
                                    </td>

                                    <td>
                                        <select name="unit[0]" id="priority"
                                            class="form-control inputFieldHeight unit text-left">
                                            <option value=""> Select </option>

                                            @foreach ($sample_units as $unit)
                                                <option value="{{ $unit->id }}"> {{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <input type="number" step="any" name="qty[]"
                                            class="form-control inputFieldHeight text-center qty" required>
                                    </td>


                                    <td>
                                        <select name="priority[0]" id="priority"
                                            class="form-control inputFieldHeight priority text-left" required>
                                            <option value=""> Select </option>
                                            <option value="high"> High </option>
                                            <option value="medium"> Medium </option>
                                            <option value="low"> Low </option>
                                            <option value="optional"> Optional </option>
                                        </select>
                                    </td>

                                    <td class="text-left">
                                        <button type="button" class="add-row"
                                            style="border: none; background:#3d4a94; color:#fff;"> <i
                                                class="bx bx-plus"></i> </button>
                                        <button type="button" class="remove-row"
                                            style="border: none;background:rgb(197, 66, 5); color:#fff;"> <i
                                                class="bx bx-trash"></i> </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>


                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer d-flex justify-content-center align-items-center">
                        <button type="submit" class="btn btn-primary"> Save </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="profitCenterPrintModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false"
        style="z-index: 1080;">

        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="profitCenterPrintShow">

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script>
        $(document).ready(function() {

            // Function to update name attributes with proper index
            function resetRowIndexes() {
                $("#boq-factor table tbody tr").each(function(rowIndex) {
                    $(this).find("select, input").each(function() {
                        let name = $(this).attr("name");
                        if (name) {
                            let newName = name.replace(/\[\d+\]/, "[" + rowIndex + "]");
                            $(this).attr("name", newName);
                        }
                    });
                });
            }

            // Add row
            $(document).on("click", ".add-row", function() {
                let currentRow = $(this).closest("tr");
                let newRow = currentRow.clone();
                newRow.find('span.select2').remove();
                // Reset values
                newRow.find("select").val("");
                newRow.find("input").val("");

                // Append row
                $("#boq-factor table tbody").append(newRow);
                $('.common-select2').select2();

                newRow.find(".cost_factor").val("1");

                // Reset indexes
                resetRowIndexes();
            });

            // Remove row
            $(document).on("click", ".remove-row", function() {
                let rowCount = $("#boq-factor table tbody tr").length;
                if (rowCount > 1) {
                    $(this).closest("tr").remove();
                    resetRowIndexes();
                } else {
                    toastr.warning("You must keep at least one row.");
                }
            });

            function checkOldBoqFactor(tr) {

                var project_task = tr.find('.project_task').val();
                var project_item = tr.find('.project_item').val();
                var house_type = tr.find('.house_type').val();
                var work_type = tr.find('.work_type').val();

                if (project_item && project_task && house_type && work_type) {
                    $.ajax({
                        type: 'get',
                        url: '{{ route('get.old.boq.factor') }}',
                        data: {
                            project_task: project_task,
                            project_item: project_item,
                            house_type: house_type,
                            work_type: work_type,
                        },
                        success: function(res) {
                            tr.find('.cost_factor').val(res.cost_factor ?? 1);
                            tr.find('.qty').val(res.qty);
                            tr.find('.priority').val(res.priority);
                            tr.find('.unit').val(res.unit);
                            tr.find('.area').val(res.area);
                        }
                    })
                }
            }

            $(document).on('change', '.project_task', function() {
                var tr = $(this).closest('tr');
                checkOldBoqFactor(tr);

                var itemDropdown = $(this).closest("tr").find(".project_item");

                var items = $(this).find("option:selected").data("items");
                // clear old options
                itemDropdown.empty().append('<option value=""> Select </option>');

                if (items && items.length > 0) {
                    $.each(items, function(index, item) {
                        itemDropdown.append('<option value="' + item.id + '">' + item
                            .item_description + '</option>');
                    });
                }
            })

            $(document).on('change', '.project_item', function() {
                var tr = $(this).closest('tr');
                checkOldBoqFactor(tr);
            });

            $(document).on('change', '.house_type', function() {
                var tr = $(this).closest('tr');
                checkOldBoqFactor(tr);
            });

            $(document).on('change', '.work_type', function() {
                var tr = $(this).closest('tr');
                checkOldBoqFactor(tr);
            });
        });

        $(document).on('click', '.sample-edit', function() {
            var sample = $(this).data('sample');

            $('#boq-factor table tbody tr').each(function(index) {
                if (index > 0) {
                    $(this).remove();
                }
            });

            $('#boq-factor').modal('show');
            $('.project_task').val(sample.task_id).trigger('change');
            $('.project_item').val(sample.item_id);
            $('.house_type').val(sample.house_type);
            $('.work_type').val(sample.work_type);
            $('.cost_factor').val(sample.cost_factor);
            $('.area').val(sample.area);
            $('.unit').val(sample.unit);
            $('.qty').val(sample.qty);
            $('.priority').val(sample.priority);
        })
    </script>
@endpush

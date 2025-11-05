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

        .tasks-title,
        .budget-title {
            font-size: 16px;
            color: #313131;
            font-weight: 500;
            text-transform: capitalize;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered,
        .form-control {
            font-size: 13px !important;
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
                            <div class="row">
                                <div class="col-7 d-flex justify-content-between">
                                    <div class="d-flex">
                                        <a href="{{ route('gnatt.chart.index') }}" class="project-btn sub-btn active"> Gantt Chart </a>
                                    </div>
                                </div>
                                <div class="col-5 d-flex justify-content-end">
                                </div>
                            </div>
                            <form class="repeater mt-1 project-form" action="{{ route('gnatt.chart.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">

                                            <label for=""> Party / Owner Name </label>

                                            <select name="customer_id" required
                                                class="form-control @error('customer_id') is-invalid @enderror common-select2" id="party_info">
                                                <option value=""> Select Company </option>
                                                @foreach ($parties as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        {{ old('customer_id') == $customer->id ? 'selected' : ' ' }}>
                                                        {{ $customer->pi_name }} </option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                                <p class="text-danger"> {{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for=""> Quotation </label>

                                            <select name="quotation_id" id="quotation_id" class="form-control quotation_id common-select2">
                                                <option value=""> Select Quotation </option>
                                            </select>
                                            @error('quotation_id')
                                                <p class="text-danger"> {{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for=""> Onboard </label>

                                            <select name="project_id" id="project_id" class="form-control project_id common-select2">
                                                <option value=""> Select project </option>
                                            </select>

                                            @error('project_id')
                                                <p class="text-danger"> {{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for=""> Gantt Chart Name  </label>
                                            <input type="text" class="form-control" name="name" autocomplete="off" id="name" required>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for=""> Estimated Start Date </label>
                                            <input type="text" name="start_date1" id="start_date"
                                                class="date form-control @error('start_date1') is-invalid @enderror"
                                                value="" autocomplete="off">
                                            @error('start_date1')
                                                <p class="text-danger"> {{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for=""> Estimated End Date </label>
                                            <input type="text" name="end_date1" id="end_date"
                                                class="date form-control @error('end_date1') is-invalid @enderror"
                                                autocomplete="off">
                                            @error('end_date1')
                                                <p class="text-danger"> {{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="auto-index repeater1 table table-sm">
                                        <thead style="background-color:#34465b !important;">
                                            <tr>
                                                <th class="text-left text-white" style="min-width:200px; padding:5px 7px;">Task name</th>
                                                <th class="text-left text-white" style="width:200px; padding:5px 7px;">Assign To</th>
                                                <th class="text-center text-white" style="min-width:100px; max-width:120px;">Start Date</th>
                                                <th class="text-center text-white" style="min-width:100px; max-width:120px">End Date</th>
                                                <th class="text-center text-white" style="min-width:80px;  max-width:120px"> Color </th>
                                                <th class="text-center text-white" style="min-width:100px; max-width:120px">Priority</th>
                                                <th class="text-right text-white" style="min-width:60px;">
                                                    <button type="button" id="addTask" class="addItemBtn bg-info text-white" title="Add Task" style="border: 1px solid #ddd;">+</button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="boq-body">

                                        </tbody>
                                    </table>
                                </div>

                                <div class="row d-flex">
                                    <div class="col-7">
                                        <div class="form-group" style="width: 300px;margin-top: 10px;">
                                            <label for=""> Upload Documents </label>
                                            <input
                                                class="form-control file_upload  @error('voucher_file') is-invalid @enderror" type="file" name="voucher_file[]" style="padding: 0px !important; border:none" accept="application/pdf,image/png,image/jpeg,application/msword" multiple>
                                            @error('voucher_file')
                                                <p class="text-danger"> {{ $message }}</p>
                                            @enderror

                                            <ul id="fileList" class="list-group mt-1"></ul>
                                        </div>
                                    </div>

                                    <div class="col-5">
                                        @if(Auth::user()->hasPermission('ProjectManagement_Approve'))
                                        <div class="d-flex justify-content-end mt-1">
                                            <button type="submit" class="project-btn save-btn"> Save </button>
                                        </div>
                                        @endif
                                    </div>
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
            $('.common-select2').select2();
            $(".add_items").click(function() {
                addInput();
            });
            var i = 0;
        });

        $(document).on('mouseenter', '.date',function(){
            $('.date').datepicker({
                dateFormat:'dd/mm/yy'
            })
        })

        let tasks = [];
        let taskIndex = 0;
        let employees = @json($employees);

        $('#addTask').on('click', function () {
            const itemIndex = 0;
            let taskOptions = '<option value=""> Select </option>';
                tasks.forEach(function (task) {
                taskOptions += `<option value="${task.id}" data-items='${JSON.stringify(task.items)}'>${task.task_name}</option>`;
            });

            let employeeOptions = '<option value=""> Select </option>';
            employees.forEach(function (employee) {
                employeeOptions += `<option value="${employee.full_name}">${employee.code} ${employee.full_name} ${employee.contact_number} </option>`;
            });

            const taskRow = `
                <tr class="task-row" data-task-index="${taskIndex}">
                    <td>
                        <div class="d-flex">
                            <select name="task_id[${taskIndex}]" class="form-control task_id inputFieldHeight" required>
                                ${taskOptions}
                            </select>
                            <input type="text" name="task_name[${taskIndex}]" class="form-control d-none task-name" placeholder="Description">
                            <button type="button" class="task-toggler bg-info text-white" style="border: 1px solid #ddd;" title="Add Item"> <i class='bx bx-refresh'></i> </button>
                        </div>
                    </td>

                    <td>
                        <select name="assign_to[${taskIndex}]" class="form-control common-select2 assign_to text-center">
                            ${employeeOptions}
                        </select>
                    </td>

                    <td>
                        <input type="text" name="start_date[${taskIndex}]" class="form-control date start_date" placeholder="Start Date" autocomplete="off">
                    </td>
                    <td>
                        <input type="text" name="end_date[${taskIndex}]" class="form-control date end_date" placeholder="End Date" autocomplete="off">
                    </td>

                    <td>
                        <input type="color" name="color[${taskIndex}]" class="form-control color" placeholder="Color">
                    </td>

                    <td>
                        <select class="form-control inputFieldHeight" name="priority[${taskIndex}]">
                            <option value="Low"> Low </option>
                            <option value="Medium"> Medium </option>
                            <option value="High"> High </option>
                        </select>
                    </td>

                    <td style="width:5%;" class="text-right">
                        <button type="button" class="removeTaskBtn bg-danger text-white" style="border: 1px solid #ddd;" title="Remove Task" >X</button>
                    </td>
                </tr>
            `;

            $('#boq-body').append(taskRow);
            taskIndex++;
            $('.common-select2').select2();
        });


        // Remove task
        $(document).on('click', '.removeTaskBtn', function () {
            $(this).closest('.task-row').remove();
            calculateTotal();
        });

        $(document).on('click', '.task-toggler', function () {
            var td = $(this).closest('td');
            var task_id = td.find('.task_id');
            var task_name = td.find('.task-name');

            task_id.each(function () {
                if ($(this).hasClass('d-none')) {
                    $(this).removeClass('d-none').prop('disabled', false).porp('required', true);
                } else {
                    $(this).addClass('d-none').val('').prop('disabled', true).prop('required', false);
                }
            });
            task_name.each(function (){
                if ($(this).hasClass('d-none')) {
                    $(this).removeClass('d-none').prop('disabled', false).porp('required', true);
                } else {
                    $(this).addClass('d-none').prop('required', false);
                }
            });
        });

        function addTaskTable(boq, type='project') {
            const tbody = $('#boq-body');
            tbody.empty();
            boq.tasks.forEach((task, key) => {
                const items = task.items;
                let employeeOptions = '<option value=""> Select </option>';
                employees.forEach(function (employee) {
                    employeeOptions += `<option value="${employee.full_name}">${employee.code} ${employee.full_name} ${employee.contact_number} </option>`;
                });

                const taskRow = $(`
                    <tr class="task-row" data-task-index="${key}">
                        <td style="width:30%;">
                            <div class="d-flex">
                                <input type="hidden" name="project_task_id[${key}]" value="${task.id}">
                                <input type="text" name="task_name[${key}]" class="form-control task-name" value="${task.task_name}" placeholder="Description">
                            </div>
                        </td>
                        <td style="width:20%;">
                            <select name="assign_to[${key}]" class="form-control common-select2 assign_to text-center">
                                ${employeeOptions}
                            </select>
                        </td>
                        </td>
                        <td style="width:10%;">
                            <input type="text" name="start_date[${key}]" class="form-control date start_date" placeholder="Start Date" value="${formatDate(task.start_date)}" autocomplete="off">
                        </td>
                        <td style="width:10%;">
                            <input type="text" name="end_date[${key}]" class="form-control date end_date" placeholder="End Date" value="${formatDate(task.end_date)}" autocomplete="off">
                        </td>
                        <td style="width:5%;">
                           <input type="color" name="color[${key}]" class="form-control color" placeholder="Color">
                        </td>

                        <td>
                            <select class="form-control inputFieldHeight" name="priority[${key}]">
                                <option value="low"> Low </option>
                                <option value="medium"> Medium </option>
                                <option value="high"> High </option>
                            </select>
                        </td>
                        <td style="width:5%;" class="text-right">
                            <button type="button" class="removeTaskBtn bg-danger text-white" style="border: 1px solid #ddd;" title="Remove Task">X</button>
                        </td>
                    </tr>
                `);

                tbody.append(taskRow);
                taskIndex++;
                $('.common-select2').select2();
            });
        }

        $(document).on('change', '#party_info', function(){
            var party_id = $(this).val();
            var url = '{{route("party.quotations", ":party_id")}}';
            url = url.replace(':party_id', party_id);
            // var url = $(this).data('url').replace('party', party_id);

            $.ajax({
                type:'get',
                url:url,
                success:function(data){
                    $('#quotation_id').html('<option value=""> Select </option>');
                    data.quotations.forEach(function(quotation){
                        $('#quotation_id').append(`
                            <option value="${quotation.id}"> ${quotation.project_name} (${quotation.project_code})  </option>
                        `)
                    })
                }
            });

        });

        function formatDate(dateStr) {
            if (!dateStr) return '';
            const parts = dateStr.split('-'); // ['2025', '05', '07']
            return `${parts[2]}/${parts[1]}/${parts[0]}`; // '07/05/2025'
        }

        $(document).on('change', '#quotation_id', function(){
            var quotation_id = $(this).val();
            var url = "{{route('quotation.projects',':quotation_id')}}";
            url = url.replace(':quotation_id', quotation_id);

            $('#project_id').html('<option value=""> Select </option>');

            $.ajax({
                url:url,
                type:'get',
                success:function(res){
                    var projects = res.projects;
                    var quotation = res.quotation;

                    if (projects && projects.length > 0) {
                        projects.forEach(function (project) {
                            const boqJson = JSON.stringify(project).replace(/"/g, '&quot;');
                            // console.log(boqJson);
                            $('#project_id').append(
                                `<option value="${project.id}" data-boq="${boqJson}">
                                    ${project.project_name} (${project.project_code})
                                </option>`
                            );
                        });
                    }

                    addTaskTable(quotation, type="quotation");
                    tasks = quotation.tasks;
                    taskIndex = tasks.length;
                    $('#start_date').val(formatDate(quotation.start_date));
                    $('#end_date').val(formatDate(quotation.end_date));
                    $('#name').val(quotation.project_name)
                }
            })
        });

        $(document).on('change', '#project_id', function(){
            var selectedOption = $('#project_id').find('option:selected');
            let boq = selectedOption.data('boq');
            $('#start_date').val(formatDate(boq.start_date));
            $('#end_date').val(formatDate(boq.end_date));
            $('#name').val(boq.project_name)
            addTaskTable(boq);
            tasks = boq.tasks;
            taskIndex = tasks.length;
        });

        $(document).on('change', '.task_id', function () {
            const selectedText = $(this).find('option:selected').text();
            const taskNameInput = $(this).closest('td').find('.task-name');

            taskNameInput.val(selectedText);
        });

        let selectedFiles = [];

        function updateFileListDisplay() {
            $('#fileList').empty();
            selectedFiles.forEach((file, index) => {
                const html = `
                    <li class="list-group-item d-flex justify-content-between align-items-center" style="padding: 5px 10px !important;">
                        ${file.name}
                        <button type="button" class="btn btn-sm btn-danger remove-file" data-index="${index}">
                            Remove
                        </button>
                    </li>
                `;
                $('#fileList').append(html);
            });
        }

        function updateFileInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            $('.file_upload')[0].files = dt.files;
        }

        $(document).on('change', '.file_upload', function (e) {
            const newFiles = Array.from(e.target.files);

            // Optional: Prevent duplicates (based on name and size)
            newFiles.forEach(newFile => {
                if (!selectedFiles.some(f => f.name === newFile.name && f.size === newFile.size)) {
                    selectedFiles.push(newFile);
                }
            });

            updateFileListDisplay();
            updateFileInput();
        });

        $(document).on('click', '.remove-file', function () {
            const index = $(this).data('index');
            selectedFiles.splice(index, 1);
            updateFileListDisplay();
            updateFileInput();
        });
    </script>
@endpush

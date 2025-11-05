@extends('layouts.backend.app')
@push('css')
@include('layouts.backend.partial.style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css">
<!-- jQuery Gantt CSS -->
<link rel="stylesheet" href="{{ asset('css/jquery-gantt.css') }}" />
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
        width: 100%;
    }

    .add-customer {
        background: #4A47A3;
        padding: 2px 4px !important;
        margin: 0 !important;
    }

    input.form-control {
        height: 35px !important;
    }

    .project-btn {
        border: none;
        color: #fff;
        font-size: 15px;
        font-weight: 500px;
        padding: 3px 10px;
        border-radius: 5px;
    }



    .btn-sky {
        background-color: #7DE5ED;
    }

    .btn-dark-blue {
        background-color: #5F6F94;
    }

    .btn-light-green,
    .btn-light-green:hover {
        background-color: #1F8A70;
        text-decoration: none;
        color: #fff;
    }

    .sub-btn {
        border: 1px solid #475F7B;
        background-color: #fff;
        border-radius: 15px;
        color: #475F7B;
        padding: 3px 6px 3px 6px !important;
    }

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

    .table .thead-light th {
        color: #F2F4F4;
        background-color: #34465b;
        border-color: #DFE3E7;
    }

    tr:nth-child(even) {
        background-color: #c8d6e357;
    }

    #filenameList {
        list-style: none;
        padding: 0 !important;
        margin-top: 15px;
    }

    #filenameList li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f6f9fc;
        border: 1px solid #d1dbe5;
        padding: 8px 12px;
        border-radius: 5px;
        margin-bottom: 6px;
        font-size: 14px;
        color: #333;
        transition: background-color 0.2s;
    }

    #filenameList li:hover {
        background-color: #eef4fa;
    }

    #filenameList li button.remove-btn {
        background-color: #ff5b5b;
        color: white;
        border: none;
        padding: 3px 10px;
        font-size: 12px;
        border-radius: 3px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    #filenameList li button.remove-btn:hover {
        background-color: #e04040;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        font-size: 14px !important;
        color: #7c7882 !important;
    }
</style>

<style>
    /* Custom style fallback (not effective alone due to inline styles) */
    #gantt .nav-slider-left,
    #gantt .nav-slider-right {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #gantt .navigate .nav-link {
        color: #fff !important;
        background-color: #313131 !important;
        border: none !important;
        box-shadow: none !important;
        font-family: Arial, sans-serif !important;
        font-size: 16px;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 6px 14px;
        border-radius: 5px;
    }

    #gantt .fn-gantt .leftPanel {
        width: 320px !important;
        /* Adjust as needed */
    }

    /* Also increase the width of the description cell */
    #gantt .fn-gantt .leftPanel .fn-label {
        white-space: normal !important;
        word-break: break-word;
        width: 100% !important;
        padding-right: 10px;
    }

    #gantt .fn-gantt .bar .fn-label {
        color: #fff !important;
    }

    #gantt .fn-gantt .bottom {
        margin: 20px;
    }
</style>
@endpush
@section('content')
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.project._header')
            <div class="tab-content bg-white">
                <div id="journaCreation" class="tab-pane active">
                    <section class="p-1" id="widgets-Statistics">
                        <div class="row">

                            <div class="col-md-6 justify-content-start">
                                <form action="{{ route('projects.index') }}" method="get" class="">
                                    <div class="form-group d-flex ">
                                        <input type="text" name="search" class="form-control"
                                            style="margin-right: 10px;width:250px" placeholder="Search Project">

                                        <select name="company_id"
                                            class="form-control inputFieldHeight common-select2 ml-1">
                                            <option value="">Select...</option>
                                            <option value="seabridge" {{$company_id=='seabridge' ? 'selected' :($company_id==null?'selected':'') }}>
                                                SINGH ALUMINIUM AND STEEL</option>
                                            @foreach ($subsidiarys as $subsidiary)
                                            <option value="{{ $subsidiary->id }}" {{$company_id==$subsidiary->id ?
                                                'selected' : '' }}>{{
                                                $subsidiary->company_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="project-btn action-btn bg-info ml-1" title="Search"
                                            style="background: #9ba19c;color: white;">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                        width="25">
                                                </div>
                                                <div><span>Search</span></div>
                                            </div>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 d-flex float-right justify-content-end">
                                <div class="">
                                    <table class="table table-bordered table-sm"
                                        style="background: #f9f9f9; text-color: #000; font-weight: 500; font-size: 14px;">
                                        <thead>
                                            <tr
                                                style="background: #f9f9f9; text-color: #000 !important; font-weight: 600; font-size: 14px;">
                                                <th
                                                    style="background: #f9f9f9; text-color: #000 !important; font-weight: 600; font-size: 14px;">
                                                    Total Ongoing Projects</th>


                                                <th
                                                    style="background: #f9f9f9; text-color: #000 !important; font-weight: 600; font-size: 14px;">
                                                    Total Contract Amount</th>

                                                {{-- <th
                                                    style="background: #f9f9f9; text-color: #000 !important; font-weight: 600; font-size: 14px;">
                                                    Total Taxable Amount</th>
                                                <th
                                                    style="background: #f9f9f9; text-color: #000 !important; font-weight: 600; font-size: 14px;">
                                                    Total VAT</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                style="color: #000; font-weight: 500; font-size: 15px; text-align: right;">
                                                <td>{{ $total_ongoing_project }}</td>
                                                <td>{{ number_format($total_contact_amount, 2) }}</td>
                                                {{-- <td>{{ number_format($total_budget, 2) }}</td>
                                                <td>{{ number_format($total_vat, 2) }}</td> --}}
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="data-table table-responsive ">
                            <table class="table table-sm">
                                <thead style="background-color:#34465b !important;">
                                    <tr class="text-center">
                                        <th class="text-left" style="color:#fff;">SI NO</th>
                                        <th class="text-left" style="color:#fff;">Assigned To</th>
                                        <th class="text-left" style="color:#fff;">Owner / Party</th>
                                        <th style="color:#fff;">Project No</th>
                                        <th class="text-left" style="color:#fff;">Project</th>
                                        <th style="color:#fff;">PLOT</th>
                                        <th style="color:#fff; text-align:left;">Location</th>
                                        <th style="width:10%;color:#fff;" class="text-center">Amount ({{
                                            $currency->symbole }})</th>
                                        <th style="min-width: 110px;color:#fff;" class="text-center">Start Date</th>
                                        <th style="min-width: 100px;color:#fff;" class="text-center">End Date</th>
                                        <th style="width:5%;color:#fff;" title="Progress">Progress</th>
                                        <th style="width:10%;color:#fff;" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $key => $project)
                                    @php
                                    // Related project
                                    $relatedProject = optional(optional(optional($project->quotation)->boq)->project);
                                    @endphp

                                    <tr class="text-center text-uppercase">
                                        <td>{{ ($projects->currentPage() - 1) * $projects->perPage() + $key + 1 }}</td>

                                        {{-- Company --}}
                                        <td style="width: 15%" class="view-prospect text-left" data-id="{{ $project->id }}"
                                            data-url="@if($project->prospect->id){{ route('new-project.show', $project->prospect->id) }}@endif"
                                            title="{{$companyShort= $project->company->company_name ?? 'SEA BRIDGE BUILDING CONT.
                                    LLC' }}">
                                            {{ \Illuminate\Support\Str::limit($companyShort, 20) }}
                                        </td>

                                        {{-- Party --}}
                                        <td style="width: 15%" class="view-prospect text-left" data-id="{{ $project->id }}"
                                            data-url="@if($project->prospect->id){{ route('new-project.show', $project->prospect->id) }}@endif"
                                            title="{{$partyShort= $project->party->pi_name ?? '' }}">
                                            {{ \Illuminate\Support\Str::limit( $partyShort, 20) }}
                                        </td>

                                        {{-- Project No --}}
                                        <td class="view-prospect" data-id="{{ $project->id }}"
                                            data-url="@if($project->prospect->id){{ route('new-project.show', $project->prospect->id) }}@endif">
                                            {{ $relatedProject->project_no ?? '' }}
                                        </td>

                                        {{-- Project Name --}}
                                        <td style="width: 15%" class="view-prospect text-left" data-id="{{ $project->id }}"
                                            data-url="@if($project->prospect->id){{ route('new-project.show', $project->prospect->id) }}@endif"
                                            title="{{  $project->project_name }}">
                                            {{ \Illuminate\Support\Str::limit( $project->project_name, 20) }}
                                        </td>

                                        {{-- Plot --}}
                                        <td class="view-prospect" style="width: 10%" data-id="{{ $project->id }}"
                                            data-url="@if($project->prospect->id){{ route('new-project.show', $project->prospect->id) }}@endif">
                                            {{ $relatedProject->plot ?? '' }}
                                        </td>

                                        {{-- Location --}}
                                        <td class="view-prospect text-left" data-id="{{ $project->id }}" title="{{ $relatedProject->location ?? '' }}"
                                            data-url="@if($project->prospect->id){{ route('new-project.show', $project->prospect->id) }}@endif">
                                            {{ \Illuminate\Support\Str::limit($relatedProject->location ?? '', 10) }}
                                        </td>

                                        {{-- Amount --}}
                                        <td style="width:10%;" class="text-center">
                                            {{ number_format($project->total_budget, 2) }}
                                        </td>

                                        {{-- Start Date --}}
                                        <td class="text-center">
                                            {{ $project->start_date ? date('d/m/Y', strtotime($project->start_date)) :
                                            '...' }}
                                        </td>

                                        {{-- End Date --}}
                                        <td class="text-center">
                                            {{ $project->end_date ? date('d/m/Y', strtotime($project->end_date)) : '...'
                                            }}
                                        </td>

                                        {{-- Progress --}}
                                        <td>
                                            {{ $project->avarage_complete ? number_format($project->avarage_complete, 2,
                                            '.', '') : 0 }} %
                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <button class="project-btn btn-primary view-project"
                                                    data-id="{{ $project->id }}"
                                                    data-url="{{ route('projects.show', $project->id) }}" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </button>

                                                <a href="#" class="project-btn roi-report"
                                                    data-url="{{ route('new.project.roy.report',['project_id' => $project->id, 'print' => false]) }}"
                                                    style="margin-left: 0.2rem !important;background-color:#0f648b;"
                                                    title="ROI Report">
                                                    <i class="fa fa-file-image" style="font-size:16px"></i>
                                                </a>
{{--
                                                <a href="#" class="project-btn document_upload"
                                                    data-id="{{ $project->id }}" title="Document"
                                                    style="margin-left: 0.2rem !important;background-color:#0ead5e;">
                                                    <i class="fa fa-file-image" style="font-size:16px"></i>
                                                </a> --}}

                                                <a href="#" class="project-btn gantt_chart" data-id="{{ $project->id }}"
                                                    data-url="{{ route('gantt.chart.ajax') }}"
                                                    style="margin-left: 0.2rem !important;background-color:#0e33ad;"
                                                    title="Gantt Chart">
                                                    <i class="fas fa-chart-pie" style="font-size:16px"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr style=" background-color: #3d4a94 !important; color:white;">
                                        <td colspan="7" style="text-align: right ; margin-right:5px;">Total Contract Amount</td>
                                        <td>{{ number_format($total_contact_amount, 2) }}</td>
                                        <td colspan="6"></td>
                                    </tr>
                                </tbody>
                            </table>

                            {!! $projects->links() !!}
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="project-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header print-hideen" style="padding: 5px 15px;background:#364a60;">
                <h5 class="modal-title" id="exampleModalLabel"
                    style="font-family:Cambria;font-size: 2rem;color:#fff;padding-left: 12px;"> </h5>
                <div class="d-flex align-items-center">
                    {{-- <a href="" class="project-btn bg-success print-job-project" target="_blank" title="Print"
                        style="margin-right: 0.2rem !important;">
                        <i class="bx bx-printer text-white" style="padding-top:4px;"></i>
                    </a> --}}
                    {{-- <a href="" class="project-btn bg-info invoice-create" title="Genarate Invoice"
                        style="margin-right: 0.2rem !important;">
                        <img src="{{asset('icon/generate.png')}}" class="img-fluid" style="height: 25px" alt="">
                    </a> --}}
                    <button type="button" class="project-btn bg-danger text-white" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body" style="padding: 5px 15px;">

            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example" id="createModel" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px;background:#364a60;">
                <h5 class="modal-title" style="font-family:Cambria;font-size: 2rem;color:#fff;padding-left: 5px;">
                    Document Upload </h5>
                <div class="d-flex align-items-center">
                    <button type="button" class="project-btn bg-danger text-white" data-dismiss="modal"
                        aria-label="Close" style="margin:0 5px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            </div>
            <div id="modal_content">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="project-create-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px;">
                <h5 class="modal-title" id="exampleModalLabel"> Create WorkOrder </h5>
                <div class="d-flex align-items-center">
                    <button type="button" class="project-btn bg-dark text-white" data-dismiss="modal" aria-label="Close"
                        style="margin:0 5px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            </div>
            <div class="project-modal-body" style="padding: 5px 15px;">

            </div>
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

<div class="modal fade bd-example-modal-lg" id="ganttChartModal" tabindex="-1" rrole="dialog"
    aria-labelledby="ganttChartModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false"
    style="z-index: 1080;">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="ganttChartShow">

                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home"
                            type="button" role="tab" aria-controls="nav-home" aria-selected="true">Home</button>
                        <button class="nav-link" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile"
                            type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Profile</button>
                        <button class="nav-link" id="nav-contact-tab" data-toggle="tab" data-target="#nav-contact"
                            type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Contact</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        Placeholder content for the tab panel. This one relates to the home tab. Takes you miles high,
                        so high, 'cause she’s got that one international smile. There's a stranger in my bed, there's a
                        pounding in my head. Oh, no. In another life I would make you stay. ‘Cause I, I’m capable of
                        anything. Suiting up for my crowning battle. Used to steal your parents' liquor and climb to the
                        roof. Tone, tan fit and ready, turn it up cause its gettin' heavy. Her love is like a drug. I
                        guess that I forgot I had a choice.</div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">...
                    </div>
                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                        Placeholder content for the tab panel. This one relates to the home tab. Takes you miles high,
                        so high, 'cause she’s got that one international smile. There's a stranger in my bed, there's a
                        pounding in my head.</div>
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
<script src="{{ asset('js/plugin/jquery-gantt.js') }}"></script>
<script>
    $(document).on('mouseenter', '.date', function() {
            $('.date').datepicker({
                dateFormat: 'dd/mm/yy'
            });
        });

        $(document).on('click', '.roi-report', function(){
            var url = $(this).data('url');
            $.ajax({
                url:url,
                type:'get',
                success:function(res){
                    $('#profitCenterPrintModal').modal('show');
                    $('#profitCenterPrintShow').html(res);
                },
                error: function (xhr) {
                    let message = xhr.responseJSON?.message || 'An unexpected error occurred.';
                    toastr.error(message, 'Error');
                }
            })
        });

        $(document).on('click', '.view-project', function(e) {
            e.preventDefault();
            let project_id = $(this).attr('data-id');
            let url = $(this).attr('data-url');
            let lpo_print_url = "{{ route('job-project-print',":id") }}";
            lpo_print_url = lpo_print_url.replace(':id',project_id);
            let invoice = $(this).attr('data-invoice');
            let quotation = $(this).attr('data-quotation');
            if (invoice == 0) {
                let invoice_create_url = "{{ route('project.invoice.create', ':id') }}"
                invoice_create_url = invoice_create_url.replace(':id', project_id);
                $('.invoice-create').attr('href', invoice_create_url)
            } else {
                $('.invoice-create').hide();
            }
            $.get(url, function(res) {
                $('.modal-body').empty().html(res);
                $('.modal-title').html('Received Work Order - Quotation Ref. '+ quotation);
                $('#project-modal').modal('show');
                $('.print-job-project').attr('href',lpo_print_url);
            })
        });
        $(document).on('click','.view-prospect', function(){
            var url = $(this).data('url');
            // alert(url);

            if(url != ''){
                $.ajax({
                    url:url,
                    type:'get',
                    success:function(res){
                        $('#profitCenterPrintModal').modal('show');
                        $('#profitCenterPrintShow').html(res);
                    },
                    error: function (xhr) {
                        let message = xhr.responseJSON?.message || 'An unexpected error occurred.';
                        toastr.error(message, 'Error');
                    }
                })
            }else{
                toastr.error(message, 'Error');
            }
        });

        // ----------- gantt chart ajax -------------
        let tasks = [];
        let emps = [];
        let taskIndex = 0;

        function addTaskTable(boq, type='project', employees) {
            const tbody = $('#boq-body');
            tbody.empty();
            // console.log(boq.tasks);
            // alert(employees);
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
                            <select name="assign_to[${key}]" class="form-control common-select24 assign_to text-center" required>
                                ${employeeOptions}
                            </select>
                        </td>
                        <td style="width:10%;">
                            <input type="text" name="start_date[${key}]" class="form-control date start_date" placeholder="Start Date" value="${formatDate(task.start_date)}" autocomplete="off" required>
                        </td>
                        <td style="width:10%;">
                            <input type="text" name="end_date[${key}]" class="form-control date end_date" placeholder="End Date" value="${formatDate(task.end_date)}" autocomplete="off" required>
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
                // $('.common-select2').select2();      // make problem
            });
        }
        $(document).on('click','.gantt_chart', function(){
            var url = $(this).data('url');
            var project_id = $(this).data('id');

            if(url != ''){
                $.ajax({
                    url:url,
                    type:'get',
                    data:{project_id:project_id},
                    success:function(res){
                        $('#ganttChartModal').modal('show');
                        $('#ganttChartShow').html(res.view);
                        var projectId = res.project.id;
                        $('[data-toggle="tab"]').attr('data-project', projectId);
                        if(res.chart_have == true){
                            $('#nav-view-tab').trigger('click');
                        }else{
                            var quotation = res.quotation;
                            addTaskTable(quotation, type="quotation", res.employees);
                            emps = res.employees;
                            tasks = quotation.tasks;
                            taskIndex = tasks.length;
                            $('#start_date').val(formatDate(quotation.start_date));
                            $('#end_date').val(formatDate(quotation.end_date));
                            $('#name').val(quotation.project_name);
                            $('.common-select24').select2({
                                dropdownParent: $('#ganttChartModal .modal-body'),
                                width: '100%'
                            });
                        }
                    },
                    error: function (xhr) {

                        let message = xhr.responseJSON?.message || 'An unexpected error occurred.';
                        toastr.error(message, 'Error');
                    }
                });
            }else{
                toastr.error(message, 'Error');
            }
        });
        function formatDate(dateString) {
            if (!dateString) return '';
            let date = new Date(dateString);
            if (isNaN(date.getTime())) return '';
            let day = ("0" + date.getDate()).slice(-2);
            let month = ("0" + (date.getMonth() + 1)).slice(-2);
            let year = date.getFullYear();
            return `${day}-${month}-${year}`;
        }
        // Add task
        $(document).on('click', '#addTask', function () {
            // alert(99);
            const itemIndex = 0;
            let taskOptions = '<option value=""> Select </option>';
            tasks.forEach(function (task) {
                taskOptions += `<option value="${task.id}" data-items='${JSON.stringify(task.items)}'>${task.task_name}</option>`;
            });
            // console.log(tasks);

            // console.log(emps);
            let employeeOptions = '<option value=""> Select </option>';
            emps.forEach(function (employee) {
                employeeOptions += `<option value="${employee.full_name}">${employee.code} ${employee.full_name} ${employee.contact_number} </option>`;
            });

            const taskRow = `
                <tr class="task-row" data-task-index="${taskIndex}">
                    <td>
                        <div class="d-flex">
                            <select name="task_id[${taskIndex}]" class="form-control common-select23 task_id inputFieldHeight" required>
                                ${taskOptions}
                            </select>
                            <input type="text" name="task_name[${taskIndex}]" class="form-control d-none task-name" placeholder="Description">
                            <button type="button" class="task-toggler bg-info text-white" style="border: 1px solid #ddd;" title="Add Item"> <i class='bx bx-refresh'></i> </button>
                        </div>
                    </td>

                    <td>
                        <select name="assign_to[${taskIndex}]" class="form-control common-select23 assign_to text-center">
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
                $('.common-select23').select2({
                    dropdownParent: $('#ganttChartModal .modal-body'),
                    width: '100%'
                });
        });

        $(document).on('change', '.task_id', function() {
            let selectedOption = $(this).find('option:selected');
            let taskRow = $(this).closest('tr');
            let taskNameInput = taskRow.find('.task-name');

            let taskData = selectedOption.data('items');
            if (taskData && taskData.name) {
                 taskNameInput.val(taskData.name);
            } else {
                taskNameInput.val(selectedOption.text());
            }
        });
        // edit Add task
      // Remove task
        $(document).on('click', '.removeTaskBtn', function () {
            $(this).closest('.task-row').remove();
            calculateTotal();
        });
        // -------------------------------------

        // ----------- Gantt View ------------
        $(document).on('click', '#nav-view-tab', function() {
            var projectId = $(this).data('project');

            if (!projectId) {
            toastr.error('Project ID not found.');
            return;
            }

            $.ajax({
            url: "{{ route('gantt.chart.view.ajax') }}",
            method: "POST",
            data: {
            project_id: projectId,
            _token: "{{ csrf_token() }}",
            },
            success: function(response) {
            try {
            var tasks = response.chart?.items || [];

            if (tasks.length === 0) {
            $("#gantt").html('<p class="text-center mt-3">No tasks available for this project.</p>');
            return;
            }

            // Format date safely
            function formatDateToGantt(dateStr) {
            var d = new Date(dateStr);
            return isNaN(d.getTime()) ? "/Date(" + new Date().getTime() + ")/" : "/Date(" + d.getTime() + ")/";
            }

            // Map tasks safely
            var ganttSource = tasks.map(task => {
            if (!task.name || !task.start_date || !task.end_date) return null;

            return {
            name: task.name,
            desc: `
            <strong>Priority:</strong> ${task.priority || 'N/A'}<br>
            <strong>Start:</strong> ${task.start_date || 'N/A'}<br>
            <strong>End:</strong> ${task.end_date || 'N/A'}
            `,
            values: [{
            from: formatDateToGantt(task.start_date),
            to: formatDateToGantt(task.end_date),
            label: `${task.name} (${task.progress || 0}%)`,
            customClass: task.color || 'ganttDefault',
            progress: parseInt(task.progress) || 0
            }]
            };
            }).filter(item => item !== null);

            // Initialize Gantt chart
            $("#gantt").gantt({
            source: ganttSource,
            navigate: "scroll",
            scale: "days",
            maxScale: "months",
            minScale: "hours",
            itemsPerPage: 10,
            onItemClick: function(data) {
            alert("Item clicked: " + (data.name || 'N/A'));
            },
            onAddClick: function(dt, rowId) {
            alert("Empty space clicked - add an item!");
            },
            onRender: function() {
            $('#gantt .bar').each(function(index) {
            const task = tasks[index];
            if (!task) return;

            const baseColor = task.color || '#007bff';
            const progress = parseInt(task.progress) || 0;
            const progressColor = '#28a745';

            $(this).css({
            'background': `linear-gradient(to right, ${progressColor} ${progress}%, ${baseColor} ${progress}%)`,
            'border-color': baseColor,
            'color': '#fff'
            });
            });
            }
            });

            // 👉 Add Print button under Gantt chart
            $("#gantt").after(`
                <div class="d-flex flex-row-reverse justify-content-center align-items-center mt-1 mb-1">
                    <div class="print-hideen">
                        <a onclick="window.print()" class="btn btn-icon btn-secondary custom-action-btn" title="Print Now">
                            <i class="bx bx-printer"></i> Print
                        </a>
                    </div>
                </div>
            `);

            } catch (err) {
            console.error('Gantt render error:', err);
            toastr.error('Error rendering Gantt chart.');
            }
            },
            error: function(xhr) {
            let message = xhr.responseJSON?.message || 'An unexpected error occurred.';
            toastr.error(message, 'Error');
            }
            });
        });
        // -----------------------------------

        // Gantt edit -*-----------------
        $(document).on('click', '#nav-edit-tab', function(){
            var projectId = $(this).data('project');
            var url = "{{ route('gnatt.chart.edit', ':id') }}";
            url = url.replace(':id', projectId);
            $.ajax({
                url:url,
                method: "get",
                data: {
                project_id: projectId,
                _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    $('#nav-edit').html(response);

                    setTimeout(function() {
                        $('.common-select230').select2({
                            dropdownParent: $('#ganttChartModal #boq-body-edit'),
                            width: '100%'
                        });
                        $('.date1').datepicker({
                            dateFormat: 'dd/mm/yy'
                        });
                    }, 200);
                },
                error: function (xhr) {
                    let message = xhr.responseJSON?.message || 'An unexpected error occurred.';
                    toastr.error(message, 'Error');
                }
            });
        });

        $(document).on('click', '#nav-tracking-tab', function(){
        var projectId = $(this).data('project');
        var url = "{{ route('tracking', ':id') }}";
        url = url.replace(':id', projectId);
        $.ajax({
        url:url,
        method: "get",
        data: {
        project_id: projectId,
        _token: "{{ csrf_token() }}",
        },
        success: function(response) {
        $('#nav-tracking').html(response);

        setTimeout(function() {
        $('.common-select2300').select2({
        dropdownParent: $('#ganttChartModal #input-container'),
        width: '100%'
        });
        $('.date1').datepicker({
        dateFormat: 'dd/mm/yy'
        });
        }, 200);
        },
        error: function (xhr) {
        let message = xhr.responseJSON?.message || 'An unexpected error occurred.';
        toastr.error(message, 'Error');
        }
        });
        });
        $(document).on('click', '.gantt-chart-button', function () {
        let url = $(this).data('url');
        let action = $(this).data('action');
        let confirmMsg = action === 'approve'
        ? "Are you sure you want to approve this Gantt chart?"
        : "Are you sure you want to delete this Gantt chart?";

        if (!confirm(confirmMsg)) {
        return;
        }

        $.ajax({
        url: url,
        type: action === 'approve' ? 'get' : 'DELETE',
        data: {
        _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
        toastr.success(res.message || "Action completed successfully!");
        $('.gantt-chart-button-delete').addClass('d-none');
        // Optional: reload page or update UI dynamically
        if(action !== 'approve'){
           setTimeout(() => location.reload(), 1500);
        }
        },
        error: function (xhr) {
        toastr.error("Something went wrong! Please try again.");
        }
        });
        });
        $(document).on('click', '.nav-link1', function () {
        let projectId = $(this).data('project');

        if (!projectId) return;
        var url = "{{ route('gantt.chart.status', ':id') }}";
        url = url.replace(':id', projectId)
        $.ajax({
        url: url,
        type: 'GET',
        success: function (res) {
        if (!res.exists) {
        // No chart → show only Build button
        $('#nav-create-tab').removeClass('d-none').addClass('active');
        $('#nav-tracking-tab, #nav-edit-tab, #nav-view-tab').addClass('d-none');
        $('.gantt-chart-button').addClass('d-none');
        } else {
        // Chart exists → show Tracking/Edit/View
        $('#nav-create-tab').addClass('d-none');
        $('#nav-tracking-tab, #nav-edit-tab, #nav-view-tab').removeClass('d-none');

        if (res.approved) {
        // Chart approved → hide approve/delete
        $('.gantt-chart-button[data-action="approve"], .gantt-chart-button[data-action="delete"]').addClass('d-none');
        } else {
        // Not approved → show approve/delete
        $('.gantt-chart-button').removeClass('d-none');
        }
        }
        },
        error: function () {
        toastr.error('Failed to fetch Gantt chart status.');
        }
        });
        });
        $(document).on('submit', '.chart-form', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');
           let data = new FormData(this)
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
              success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    $('#nav-create-tab').remove();
                    $('#nav-create').remove();
                    $('#nav-tracking-tab, #nav-edit-tab, #nav-view-tab').prop('disabled', false);

                   setTimeout(function() {
                        $('#nav-view-tab').trigger('click');
                   }, 300);
                } else {
                    toastr.error(response.message);
                }

                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        toastr.success(xhr.responseJSON.message);
                    } else {
                        toastr.success('Something went wrong!');
                    }
                }
            });
        });

        $(document).on('click', '.document_upload', function(e) {
            e.preventDefault();
            let project_id = $(this).attr('data-id');
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('document-upload-view') }}",
                method: "POST",
                data: {
                    project_id: project_id,
                    _token: _token,
                },
                success: function(response) {
                    $('#modal_content').html(response);
                    $("#createModel").modal('show');
                }
            });
        });

        let selectedFiles = [];

        $(document).on('change', '#job_document', function(e) {
            selectedFiles = Array.from(e.target.files);
            renderFileList(this);
        });

        function renderFileList(inputElement) {
            const list = $('#filenameList');
            list.empty();

            selectedFiles.forEach((file, index) => {
                list.append(`
                    <li>
                        ${file.name}
                        <button type="button" class="remove-btn" data-index="${index}">Remove</button>
                    </li>
                `);
            });
        }

        $(document).on('click', '.remove-btn', function() {
            const index = $(this).data('index');
            selectedFiles.splice(index, 1);

            // Rebuild new FileList and re-assign to input
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            const fileInput = document.getElementById('job_document');
            fileInput.files = dt.files;

            renderFileList(fileInput);
        });

        $(document).on('click', '.delete_document', function(e) {
            var id = $(this).attr('id');
            var _token = $('input[name="_token"]').val();
            $.ajax({
                method: "post",
                url: "{{ route('delete-job-document') }}",
                data: {
                    id: id,
                    _token: _token,
                },
                success: function(response) {
                    if (response == 1) {
                        $('#tr' + id).remove();
                        toastr.success("Document deleted", "Success");
                    }
                }
            });
        })

        $(document).on('click', '.print-page', function() {
            window.print();
        });

        $(document).on('click', '.create-btn', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('project.ajax.create') }}",
                type: 'get',
                success: function(res) {
                    $('.project-modal-body').empty().html(res);
                    $('#project-create-modal').modal('show');
                    $('.job-project-tasks').hide();
                    $('.select2').select2();
                },
                error: function(error) {
                    toastr.error("Connection error, Can't Create Project", "Warning")
                }
            })
        })

        $(document).on('change', '.project_name', function(e) {
            e.preventDefault();
            let id = $(this).find(':selected').attr('data-id');
            let url = "{{ route('get.lop.project', ':id') }}";
            url = url.replace(':id', id);

            $.ajax({
                url: url,
                type: 'get',
                success: function(lpo_project) {
                    $('.customer_id').find('.customer_' + lpo_project.customer_id).prop('selected',
                        true);
                    $('.project_description').val(lpo_project.project_description);

                    let date = new Date(lpo_project.start_date);
                    let start_date = date.getDate() + '/' + (date.getMonth() + 1) + '/' + date
                        .getFullYear()
                    $('.start_date').val(start_date);
                    let enddate = new Date(lpo_project.end_date);
                    let end_date = enddate.getDate() + '/' + (enddate.getMonth() + 1) + '/' + enddate
                        .getFullYear()
                    $('.end_date').val(end_date);

                    $("#input-container").empty()

                    $.each(lpo_project.tasks, function(key, index) {
                        var inputGroup = "<tr>" +
                            "<td class='text-center'></td>" +
                            "<td style='width: 20%'>" +
                            "<input name='task_name[" + key + "]' value=" + index.task_name +
                            " type='text'  class='form-control' required>" +
                            "</td>" +
                            "<td style='width: 30%'>" +
                            "<textarea name='description[" + key +
                            "]'  cols='30' rows='1' class='form-control' required>" + index
                            .description + "</textarea>" +
                            "</td>" +
                            "<td>" +
                            "<input type='text' value=" + index.unit + "  name='unit[" + key +
                            "]' class='form-control unit text-center'>" +
                            "</td>" +
                            "<td>" +
                            "<input type='number' value=" + index.qty + "  name='qty[" + key +
                            "]' class='form-control qty text-center' step='any'>" +
                            "</td>" +
                            "<td>" +
                            "<input type='number' value=" + index.rate + " name='rate[" + key +
                            "]' class='form-control rate text-center' step='any' readonly>" +
                            "</td>" +
                            "<td>" +
                            "<input type='number' value=" + index.discount +
                            " name='discount[" + key +
                            "]' class='form-control discount text-center' step='any' readonly>" +
                            "</td>" +
                            "<td>" +
                            "<input type='number' value=" + index.amount + " name='amount[" +
                            key +
                            "]' class='form-control amount text-center' step='any' readonly>" +
                            "</td>" +
                            "</tr>";
                        $("#input-container").append(inputGroup);
                        $('.value-shoe-total').val(lpo_project.budget);
                        $('.show-discount-value').val(lpo_project.discount);
                        $('.tatal_amount_show').val(lpo_project.total_budget);
                        $('.job-project-tasks').show();
                        $('.select2').select2();
                    })

                    calculateBudget();

                },
                error: function(error) {
                    toastr.error("Connection error, Can't Create Project", "Warning")
                }
            })
        })

        function calculateBudget() {
            let total_budget = 0;
            let budget = 0
            $('.budget').each(function(index, el) {
                budget += parseFloat($(this).val())
            })
            $('.total_budget').each(function(index, el) {
                total_budget += parseFloat($(this).val())
            })
            $('.total-budget').val(parseFloat(total_budget).toFixed(2))
            $('.budget_sum').val(parseFloat(budget).toFixed(2))
            $('.total-vat').val(parseFloat(total_budget - budget).toFixed(2))
        }

        $(document).on("click", ".delete_items", function() {
            $(this).closest("tr").remove();
            calculateBudget();
        });

        $(document).on('click', '.add_items', function() {
            $.ajax({
                url: "{{ route('get.porjects.vat') }}",
                type: 'get',
                success: function(vats) {
                    var inputGroup = "<tr>" +
                        "<td class='text-center'></td>" +
                        "<td style='width: 20%'>" +
                        "<input type='text' name='task_name[]' class='form-control' required>" +
                        "</td>" +
                        "<td style='width: 30%'>" +
                        "<textarea name='description[]'  cols='30' rows='1' class='form-control' required></textarea>" +
                        "</td>" +
                        "<td>" +
                        "<input type='number' name='budget[]' class='form-control budget text-center' step='any'>" +
                        "</td>" +
                        "<td>"
                    inputGroup += "<select name='vat[]' class='vat form-control'>"
                    $.each(vats, function(key, index) {
                        inputGroup += "<option value=" + index.id + " data-value =" + index
                            .value + "> " + index.name + '  ( ' + index.value + ' )' +
                            " </option>"
                    })
                    inputGroup += "</select>" +
                        "</td>" +
                        "<td>" +
                        "<input type='number' name='total_budget[]' class='form-control total_budget text-center' step='any'>" +
                        "</td>" +
                        "<td class='text-center'>" +
                        "<button  type='button' class='delete_items project-btn'>" +
                        "<i class='bx bx-trash'> </i>" +
                        "</button>" +
                        "</td>" +
                        "</tr>";
                    $("#input-container").append(inputGroup);
                },
                error: function(error) {
                    toastr.error("Something rong Can't add column");
                }
            })
        });

        $(document).on('change', '.vat', function(event) {
            let tr = event.target
            calculateVat(tr);
        })

        $(document).on('keyup', '.budget', function(event) {
            let tr = event.target
            calculateVat(tr);
        })

        function calculateVat(node) {
            node = $(node).closest('tr');
            let vat = parseFloat(node.find('.vat').find(':selected').attr('data-value')) / 100;
            let budget = parseFloat(node.find('.budget').val())
            total_budget = budget + budget * vat;
            node.find('.total_budget').val(parseFloat(total_budget).toFixed(2));
            calculateBudget();
        }
</script>
@endpush

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
    /* -------------- mini table ------------- */
    .custom-table {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        font-size: 14px;
        font-weight: 500;
    }

    .custom-table thead {
        background: linear-gradient(90deg, #007bff, #00c6ff);
        color: #fff;
        font-weight: 600;
        font-size: 15px;
        text-align: center;
    }

    .custom-table th,
    .custom-table td {
        padding: 12px 15px;
        vertical-align: middle;
        color: black;
    }

    .custom-table tbody tr {
        text-align: right;
        transition: background 0.2s ease-in-out;
    }

    .custom-table tbody tr:hover {
        background: #f1f7ff;
    }

    .table thead th {
        color: black;
    }
    /* --------------------------- */
    thead {
            background: #34465b;
        color: #fff !important;
    }

    th {
        color: #fff !important;
        font-size: 11px !important;
        height: 25px !important;
        text-align: center !important;
    }

    td {
        font-size: 12px !important;
        height: 25px !important;
        text-align: center;
    }

    .table-sm th,
    .table-sm td {
        padding: 4px 6px;
    }
    .custom-btn {
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        border: none;
        border-radius: 5px;
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
                            <div class="col-md-2">
                                <button class="btn btn-custom-nis btn-expense inputFieldHeight expense_create_model">
                                    New Project
                                </button>
                            </div>
                            <div class="col-md-5 justify-content-start">
                                <form action="{{ route('projects.index') }}" method="get" class="">
                                    <div class="form-group d-flex ">
                                        <input type="text" name="search" class="form-control"
                                            style="margin-right: 10px;" placeholder="Search Project">

                                        {{-- <select name="company_id" class="form-control inputFieldHeight common-select2 ml-1 d-none">
                                            <option value="">Select...</option>
                                            <option value="seabridge" {{$company_id=='seabridge' ? 'selected' :($company_id==null?'selected':'') }}>
                                                SINGH ALUMINIUM AND STEEL</option>
                                            @foreach ($subsidiarys as $subsidiary)
                                            <option value="{{ $subsidiary->id }}" {{$company_id==$subsidiary->id ?
                                                'selected' : '' }}>{{
                                                $subsidiary->company_name }}
                                            </option>
                                            @endforeach
                                        </select> --}}
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
                            <div class="col-md-5 d-flex float-right justify-content-end">
                                <div class="">
                                    {{-- <table class="table table-bordered table-sm"
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


                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                style="color: #000; font-weight: 500; font-size: 15px; text-align: right;">
                                                <td>{{ $total_ongoing_project }}</td>
                                                <td>{{ number_format($total_contact_amount, 2) }}</td>

                                            </tr>
                                        </tbody>
                                    </table> --}}
                                    <table class="table table-bordered table-sm custom-table">
                                        <thead>
                                            <tr>
                                                <th>Total Ongoing Projects</th>
                                                <th>Total Contract Amount</th>
                                                {{-- <th>Total Taxable Amount</th>
                                                <th>Total VAT</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
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
                                        <th class="text-left" style="color:#fff;">SL</th>
                                        <th class="text-left" style="color:#fff; padding-left:0;">Owner / Party</th>
                                        <th class="text-left" style="color:#fff;">Project No</th>
                                        <th class="text-left" style="color:#fff; padding-left:0;">Project</th>
                                        <th class="text-left" style="color:#fff;">Location</th>
                                        <th style="width:10%;color:#fff;" class="text-center">Amount </th>
                                        <th style="min-width: 110px;color:#fff;" class="text-center">Start Date</th>
                                        <th style="min-width: 100px;color:#fff;" class="text-center">End Date</th>
                                        <th style="width:5%;color:#fff;" title="Progress">Progress</th>
                                        <th style="width:5%;color:#fff;" title="Progress">Status</th>
                                        <th style="width:10%;color:#fff;" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($projects as $key => $project)
                                    <tr class="text-center text-uppercase view-prospect" data-id="{{ $project->id }}" data-url="@if($project->prospect->id){{ route('new-project.show', $project->prospect->id) }}@endif">
                                        <td>{{ ($projects->currentPage() - 1) * $projects->perPage() + $key + 1 }}</td>
                                        <td style="width: 15%" class="text-left" title="{{$party_short= $project->party->pi_name??'' }}">
                                            {{ \Illuminate\Support\Str::limit($party_short, 20) }}
                                        </td>
                                        <td>{{ $project->project_no ?? '' }}</td>
                                        <td style="width: 15%" class="text-left" title="{{  $project->project_name }}">
                                            {{ \Illuminate\Support\Str::limit( $project->project_name, 20) }}
                                        </td>
                                        <td class="text-left">
                                            {{ \Illuminate\Support\Str::limit($project->address ?? '', 10) }}
                                        </td>
                                        <td style="width:10%;" class="text-center">
                                            {{ number_format($project->total_budget, 2) }}
                                        </td>
                                        <td class="text-center">
                                            {{ $project->start_date ? date('d/m/Y', strtotime($project->start_date)) : '...' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $project->end_date ? date('d/m/Y', strtotime($project->end_date)) : '...' }}
                                        </td>
                                        <td>
                                            {{ $project->avarage_complete ? number_format($project->avarage_complete, 2,'.', '') : 0 }} %
                                        </td>
                                        <td>
                                            @php
                                            $badgeClass = 'badge bg-dark';
                                            if ($project->status === 'Planned') $badgeClass = 'badge bg-secondary';
                                            elseif ($project->status === 'In Progress')$badgeClass = 'badge bg-primary';
                                            elseif ($project->status === 'Completed') $badgeClass = 'badge bg-info';
                                            elseif ($project->status === 'Hold On') $badgeClass = 'badge bg-warning';
                                            elseif ($project->status === 'Handover') $badgeClass = 'badge bg-success';
                                            @endphp
                                            <span class="{{ $badgeClass }}" style="padding:7px 3px;">{{ $project->status }}</span>
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

                                               <a href="javascript:void(0);" class="project-btn project_status_update btn btn-sm text-white" data-id="{{ $project->id }}"
                                                    title="Update Project Status" style="margin-left: 0.2rem !important; background-color:#0ead5e;">
                                                    <i class="fa fa-edit" style="font-size:16px"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    {{-- <tr style=" background-color: #3d4a94 !important; color:white;">
                                        <td colspan="7" style="text-align: right ; margin-right:5px;">Total Contract Amount</td>
                                        <td>{{ number_format($total_contact_amount, 2) }}</td>
                                        <td colspan="6"></td>
                                    </tr> --}}
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
<div class="modal fade" id="projectStatusModal" tabindex="-1" aria-labelledby="projectStatusModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="projectStatusModalLabel">Update Project Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">x2</button>
            </div>
            <form action="{{ route('projects.update-status') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Hidden Project ID -->
                    <input type="hidden" name="project_id" id="modal_project_id">

                    <!-- Status Dropdown -->
                    <div class="mb-3">
                        <label for="project_status" class="form-label">Project Status</label>
                        <select name="status" id="project_status" class="form-control common-select2 w-100">
                            <option value="">Select...</option>
                            <option value="Planned">Planned</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="Hold On">Hold On</option>
                            <option value="Handover">Handover</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
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
<div class="modal fade bd-example-modal-lg" id="expense_create_model" tabindex="-1" role="dialog" aria-labelledby="expense_create_modelLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" id="expense_create_model_content">
            {{-- @include('backend.purchase-expense.create') --}}
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="voucherPreviewShow">

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
    BtnAdd('#TRow', '#TBody', 'group-a');

    function BtnAdd(trow, tbody, inputs) {
        var $trow = $(trow);
        var $tbody = $(tbody);
        var newRow = $trow.clone().removeClass("d-none").removeAttr('id');
        newRow.find("input, select, textarea").prop('disabled', false);
        newRow.find("select").addClass('commom-select3');
        newRow.find(".date-").addClass('datepicker');

        var $rows = $tbody.children('tr').not($trow);
        var lastIndex = 0;
        var lastRow = $rows.last();

        // Dynamic input handling
        var lastInputName = lastRow.find("input[name^='" + inputs + "']").attr('name');
        var lastSelectName = lastRow.find("select[name^='" + inputs + "']").attr('name');
        var lastTextName = lastRow.find("textarea[name^='" + inputs + "']").attr('name');
        var lastName = lastInputName || lastSelectName || lastTextName;

        var match = lastName && lastName.match(/\[(\d+)\]/);
        if (match) {
            lastIndex = parseInt(match[1], 10);
        }

        var newIndex = lastIndex + 1;

        newRow.find("input, select, textarea").attr('name', function(index, name) {
            return name.replace(/\[\d+\]/, '[' + newIndex + ']');
        });

        newRow.appendTo(tbody);

        // Initialize select2 on the newly added row
        newRow.find(".commom-select3").each(function() {
            if ($(this).data('select2')) {
                $(this).select2('destroy');
            }
            $(this).select2();
        });
    }
    $(document).on('click', '.expense_create_model', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('new-project-create') }}",
            type: "post",
            cache: false,
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                document.getElementById("expense_create_model_content").innerHTML = response;
                $('.party-info').select2();
                $('#expense_create_model').modal('show');
                BtnAdd('#TRow', '#TBody', 'group-a');
            }
        });
    });
    $(document).on('click', '.project_status_update', function(e) {
        e.preventDefault();
        let projectId = $(this).data('id');
        $.ajax({
            url: "{{ route('new-project-edit') }}",
            type: "post",
            cache: false,
            data: {
                _token: '{{ csrf_token() }}',
                id: projectId,
            },
            success: function(response) {
                document.getElementById("expense_create_model_content").innerHTML = response;
                $('.party-info').select2();
                $('#expense_create_model').modal('show');
                BtnAdd('#TRow', '#TBody', 'group-a');
            }
        });
    });
    $(document).on('mouseenter', '.datepicker', function() {
        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "-1000:+1000",
            dateFormat: "dd/mm/yy",
        });
    });
    function BtnDel(v) {
        $(v).parent().parent().remove();
        $("#TBody").find("tr").each(function(index) {
            $(this).find("th").first().html(index);
        });
        total();
    }
    $(document).on('keyup', '.sqm, .amount', function(e){
        tr = $(this).closest('tr');
        rate = tr.find('.amount').val();
        sqm = tr.find('.sqm').val();
        amount = Number(sqm)*Number(rate);
        sub_gross_amount = tr.find('.sub_gross_amount').val(amount.toFixed(2));
        total();
    });
    function total() {
        var sum = 0;
        $('.sub_gross_amount').each(function() {
            var this_amount = $(this).val();
            this_amount = (this_amount === '') ? 0 : this_amount;
            var this_amount = parseFloat(this_amount);
            sum = sum + this_amount;
        });
        var taxable = sum.toFixed(2);
        var vat = taxable*0.05;
        var total = (vat * 1) + (taxable * 1)
        $(".taxable_amount").val(taxable);
        $(".total_vat").val(vat.toFixed(2));
        $(".total_amount").val((total.toFixed(2)));
    };
    $(document).on('submit', '#formSubmit', function(e) {
        e.preventDefault()
        var form = $(this);
        var url = form.attr('action');
        var data = new FormData(this);
        $.ajax({
            url: url,
            method: 'POST',
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                if (response.warning) {
                    toastr.warning("{{ Session::get('message') }}", response.warning);
                } else if (response.status) {
                    for (var i = 0; i < Object.keys(response.status).length; i++) {
                        var key = i + ".invoice";
                        if (response.status.hasOwnProperty(key)) {
                            var errorMessages = response.status[key];
                            for (var j = 0; j < errorMessages.length; j++) {
                                toastr.warning(errorMessages[j]);
                            }
                        }
                    }
                } else {
                    $('#expense_create_model').modal('hide');
                    $("#submitButton").prop("disabled", true);
                    $(".deleteBtn").prop("disabled", true);
                    $(".addBtn").prop("disabled", true);
                    document.getElementById("voucherPreviewShow").innerHTML = '';
                    document.getElementById("voucherPreviewShow").innerHTML = response.preview;
                    // $('#purch-body').html(response.expense_list);
                }
            },
            error: function(err) {
                let error = err.responseJSON;
                if (error && error.errors) {
                    $.each(error.errors, function(index, value) {
                        toastr.error(value, "Error");
                    });
                } else {
                    toastr.error("An unknown error occurred.");
                }
            }
        });
    });

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
            $('.modal-title').html('Project');
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

</script>
@endpush

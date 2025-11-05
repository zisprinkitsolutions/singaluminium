@extends('layouts.backend.app')
@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />


@include('backend.tab-file.style')
<link rel="stylesheet" href="{{ asset('css/jquery-gantt.css') }}" />
<style>
    tr:nth-child(even) {
        background-color: #c8d6e357;
        /* light bluish with 34% opacity */
    }

    tr:nth-child(odd) {
        background-color: #ffffff;
        /* white or any color you prefer */
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

    /* excel like table */
    /* Table cell spacing and size */
    .profit-center-form table td,
    .profit-center-form table th {
        padding: 0px !important;
        font-size: 12px !important;
        min-width: 120px;
        vertical-align: middle;
    }

    /* Prevent table headers from wrapping */
    .profit-center-form table th {
        white-space: nowrap;
    }

    /* Compact inputs/selects */
    .profit-center-form input,
    .profit-center-form select {
        padding: 0px 0px !important;
        height: 26px !important;
        font-size: 12px;
    }

    /* Make button smaller */
    .profit-center-form button.btn-sm {
        padding: 1px 4px;
        font-size: 12px;
    }

    /* Optional: Prevent table from expanding outside container */
    .profit-center-form .table-responsive {
        overflow-x: auto;
    }


    .table-wrapper {
        max-height: 500px;
        /* Adjust height as needed */
        overflow-y: auto;
    }

    .sticky-header th {
        position: sticky;
        top: -1px;
        z-index: 10;
        white-space: nowrap;
    }

    .profit-center-form input,
    .profit-center-form select {
        padding: 10px 10px !important;
        height: 36px !important;
        font-size: 12px;
    }

    .table .thead-light th {
        color: #F2F4F4;
        background-color: #34465b;
        border-color: #DFE3E7;
        padding: 8px 10px !important;
        font-size: .9rem !important;
        text-align: left !important;
    }

    .table td {
        vertical-align: middle;
        border-bottom: 1px solid #DFE3E7;
        border-top: none;
        padding: 3px 10px !important;
    }
</style>
@endpush
@section('content')
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.project._header',['activeMenu' => 'new-project'])
            <div class="tab-content bg-white p-2 active">
                <div class="tab-pane active">
                    <div>
                        <section>
                            <div class="row mb-1">

                                <div class="col-md-6 text-left">
                                    @if(Auth::user()->hasPermission('ProjectManagement_Create'))
                                    <button type="button" class="btn btn-xs btn-primary btn_create formButton"
                                        title="Add" data-toggle="modal" data-target="#profitCenter"
                                        style="width:110px; ">
                                        <div class="d-flex">
                                            <div class="formSaveIcon" style="margin-right:5px;">
                                                <img src="{{asset('/icon/add-icon.png')}}" height="21">
                                            </div>
                                            <div><span>Add New</span></div>
                                        </div>

                                    </button>
                                    @endif
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-info inputFieldHeight formButton dropdown-toggle" type="button" id="exportDropdown"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding:4px 15px !important;">
                                            Export/Import Options
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="exportDropdown" style="z-index: 999">
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="exportToExcel('projectTable-data')">Excel Export</a>
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="exportFullTableToPDF()">PDF</a>
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#excel_import">Excel Import</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <form action="" class="d-flex items-center justify-content-end">
                                        {{-- <select name="company_id"
                                            class="form-control inputFieldHeight mr-1 common-select2">
                                            <option value="">Select...</option>
                                            <option value="seabridge" {{$company_id=='seabridge' ? 'selected' : '' }}>
                                                SINGH ALUMINIUM AND STEEL</option>
                                            @foreach ($subsidiarys as $subsidiary)
                                            <option value="{{ $subsidiary->id }}" {{$company_id==$subsidiary->id ?
                                                'selected' : '' }}>{{ $subsidiary->company_name }}
                                            </option>
                                            @endforeach
                                        </select> --}}
                                        <input type="text" class="form-control inputFieldHeight" name="search"
                                            value="{{$search}}" style="width: 100%; max-width:350px; margin-right:5px;"
                                            placeholder="Search by project name, plot no, project no">

                                        <button type="submit" class="btn btn-xs btn-primary inputFieldHeight"
                                            title="Search" style="width:110px;padding-top: 6px;padding-bottom: 6px;">
                                            <div class="d-flex justify-content-center">
                                                <div class="formSaveIcon" style="margin-right:5px;">
                                                    <img src="{{asset('/icon/search-icon.png')}}" height="21">
                                                </div>
                                                <div class="" style=""><span>Search</span></div>
                                            </div>

                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="cardStyleChange table-responsive table-wrapper">
                                <table class="table table-bordered table-sm table-hover" id="projectTable-data">

                                    <thead class="thead-light text-center sticky-header" style="white-space: nowrap">
                                        <tr style="background:#34465B">
                                            <th style="position: sticky;left: -2px; z-index:99;">SR# </th>
                                            <th style="position: sticky;left: 48px; z-index:99;">Project </th>
                                            {{-- <th>Company</th> --}}
                                            <th>Onboard</th>
                                            <th>Owner / Party </th>
                                            <th>Plot#</th>
                                            <th>Location</th>
                                            <th>Project No</th>
                                            <th>Project Type</th>
                                            <th>Engineer</th>
                                            <th>Short Name</th>
                                            <th>Consultant</th>
                                            <th>Contract Value</th>
                                            <th>VAT</th>
                                            <th>Variation</th>
                                            <th>Total Contract</th>
                                            <th>Estimation</th>
                                            <th>PS Budget</th>
                                            <th>Status</th>
                                            <th>Insurance</th>
                                            <th>Contract</th>
                                            <th>Contract Period</th>
                                            <th>Area</th>
                                            <th>File No</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Date</th>
                                            <th>Mobile No</th>
                                            <th>Details</th>
                                            <th>Handover On</th>
                                            <th style="position: sticky;right: -2px; z-index:99;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @forelse ($projects as $key => $project)
                                        @php
                                        $bgColor = ($key + 1) % 2 == 0 ? '#f1f1f1' : '#ffffff';
                                        @endphp
                                        <tr style="white-space: nowrap; padding:3px; text-transform:uppercase; text-align:left;">
                                            <td
                                                style="position: sticky;left: -2px; z-index:9;background-color: {{ $bgColor }}">
                                                {{($projects->currentPage() - 1) * $projects->perPage() + $key + 1}}
                                            </td>
                                            <td
                                                style="position: sticky;left: 48px; z-index:9;background-color: {{ $bgColor }}">
                                                {{ $project->name }}</td>
                                            {{-- <td>{{ $project->company->company_name??'SBBC' }}</td> --}}
                                            <td class="text-center"
                                                style="color: {{$project->work_order?'':'#ff0000bf'}}">
                                                {{$project->work_order?"Yes":'No'}}</td>
                                            <td>{{ $project->party->pi_name ?? '' }}</td>
                                            <td>{{ $project->plot }}</td>
                                            <td>{{ $project->location }}</td>
                                            <td>{{ $project->project_no }}</td>
                                            <td>{{ $project->project_type }}</td>
                                            {{-- <td>{{ $project->project_code }}</td> --}}
                                            <td>{{ $project->engineer }}</td>
                                            <td>{{ $project->short_name }}</td>
                                            <td>{{ $project->consultant }}</td>
                                            <td class="text-right">{{ number_format($project->contract_value, 2) }}</td>
                                            <td class="text-right">{{ number_format($project->vat, 2) }}</td>
                                            <td class="text-right">{{ number_format($project->variation, 2) }}</td>
                                            <td class="text-right">{{ number_format($project->total_contract, 2) }}</td>
                                            <td class="text-right">{{ $project->estimation }}</td>
                                            <td class="text-right">{{ $project->ps_budget }}</td>
                                            <td>{{ $project->status }}</td>
                                            <td>{{ $project->insurance }}</td>
                                            <td>{{ $project->contract }}</td>
                                            <td>{{ $project->contract_period }}</td>
                                            <td>{{ $project->area }}</td>
                                            <td>{{ $project->file_no }}</td>
                                            <td>{{ $project->start_date ? date('d/m/Y',
                                                strtotime($project->start_date)): '' }}</td>
                                            <td>{{ $project->end_date ? date('d/m/Y', strtotime($project->deadline)) :''
                                                }}</td>
                                            <td>{{ $project->date ? date('d/m/Y', strtotime($project->date)) : '' }}
                                            </td>
                                            <td>{{ $project->mobile_no }}</td>
                                            <td>{{ $project->details }}</td>
                                            <td>{{ $project->handover_on ? date('d/m/Y',
                                                strtotime($project->handover_on)) : '' }}</td>
                                            <td class="text-right"
                                                style="min-width: 140px;position: sticky;right: -2px; z-index:9;background-color: {{ $bgColor }}">
                                                <div style="text-align:left; gap: 6px;">
                                                    @if(Auth::user()->hasPermission('ProjectManagement_Edit'))
                                                    <a data-href="{{ route('new-project.edit', $project->id) }}"
                                                        class="btn p-0 edit" title="Edit">
                                                        <img src="{{ asset('/icon/edit-icon.png') }}"
                                                            style="height: 20px; width: 20px;" alt="Edit">
                                                    </a>
                                                    @endif

                                                    @if($project->gantt_charts->count() > 0)

                                                    <a href="{{ route('new-project.gantt-chart', [$project->id]) }}"
                                                        class="btn p-0" title="Gantt Chart">
                                                        <img src="{{ asset('icon/gantt-chart.png') }}"
                                                            style="height: 20px; width: 20px;" alt="Gantt Chart">
                                                    </a>
                                                    @endif

                                                    <a data-url="{{ route('new-project.show', $project->id) }}"
                                                        class="btn p-0 view-project" title="View">
                                                        <img src="{{ asset('/icon/view-icon.png') }}"
                                                            style="height: 20px; width: 20px;" alt="View">
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="29" class="text-center">No projects found.</td>
                                        </tr>
                                        @endforelse
                                        {{-- @dd($data['total_ps_budget']) --}}
                                        <tr style=" background-color: #3d4a94 !important; color:white;">
                                            <td colspan="11" style="text-align: right ; margin-right:5px;">Total</td>
                                            <td>{{ number_format($data['total_contract_value'], 2) }}</td>
                                            <td>{{ number_format($data['total_vat'], 2) }}</td>
                                            <td>{{ number_format($data['total_variation'], 2) }}</td>
                                            <td>{{ number_format($data['total_contract'], 2) }}</td>
                                            <td>{{ number_format($data['total_estimation'], 2) }}</td>
                                            <td>{{ number_format($data['total_ps_budget'], 2) }}</td>
                                            <td colspan="13"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>
                <div class="mt-1">
                    {{$projects->links()}}
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="profitCenter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px;background:#364a60;">
                <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">
                    New Project </h5>
                <div class="d-flex align-items-center">
                    <button type="button" class="project-btn bg-danger text-white" data-dismiss="modal"
                        aria-label="Close" style="padding: 3px 12px; border:none;border-radius: 5px;"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{-- @include('alerts.alerts') --}}

                </div>
            </div>

            <div class="modal-body " style="padding: 5px 5px;">
                <section id="widgets-Statistics" class="mr-1 ml-1 mb-1">
                    <p>🛠️ This module allows you to add multiple projects at once for faster data entry.</p>
                    <div class="row">
                        <div class="col-12 profit-center-form">
                            <form action="{{ route('new-project.store') }}" method="POST">
                                @csrf
                                <div class="table-scroll-wrapper"
                                    style="max-height: 80vh; overflow: auto; border: 1px solid #ccc;">
                                    <div class="table-responsive " id="scrollableTableContainer">
                                        <table class="table table-bordered" id="projectTable">
                                            <thead class="thead-light text-center">
                                                <tr>
                                                    <th style="min-width:400px ">Project Name</th>
                                                    <th style="min-width:400px ">Owner Name</th>
                                                    {{-- <th style="min-width:220px "> Company Name</th> --}}
                                                    <th>Plot#</th>
                                                    <th>Location</th>
                                                    <th>Project No</th>
                                                    <th>Project Type</th>
                                                    {{-- <th>Project Code</th> --}}

                                                    <th>Engineer</th>
                                                    <th>Short Name</th>
                                                    <th>Consultant</th>
                                                    <th>Contract Value</th>
                                                    <th>VAT</th>
                                                    <th>Variation</th>
                                                    <th>Total Contract</th>
                                                    <th>Estimation</th>
                                                    <th>PS Budget</th>
                                                    <th>Status</th>
                                                    <th>Insurance</th>
                                                    <th>Contract</th>
                                                    <th>Contract Period</th>
                                                    <th>Area</th>
                                                    <th>File No</th>
                                                    <th>Start Date</th>
                                                    <th>Deadline</th>
                                                    <th>Date</th>
                                                    <th>Mobile No</th>
                                                    <th>Details</th>
                                                    <th>Handover On</th>
                                                    <th style="position: sticky;right: -2px; z-index:9;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="projectRows">
                                                @php
                                                $index = 0;
                                                @endphp
                                                <tr style="display: none">
                                                    <td><input disabled type="text" name="projects[{{ $index }}][name]"
                                                            class="form-control" required></td>
                                                    <td>
                                                        <select disabled name="projects[{{ $index }}][party_id]"
                                                            class="form-control ">
                                                            <option value="">Select...</option>
                                                            @foreach ($pInfos as $item)
                                                            <option value="{{ $item->id }}">{{ $item->pi_name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    {{-- <td>
                                                        <select disabled name="projects[{{ $index }}][company_id]"
                                                            class="form-control ">
                                                            <option value="">Select...</option>
                                                            @foreach ($subsidiarys as $subsidiary)
                                                            <option value="{{ $subsidiary->id }}">{{
                                                                $subsidiary->company_name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </td> --}}
                                                    <td><input disabled type="text" name="projects[{{ $index }}][plot]"
                                                            class="form-control"></td>
                                                    <td><input disabled type="text"
                                                            name="projects[{{ $index }}][location]" class="form-control"
                                                            required></td>
                                                    <td><input disabled type="text"
                                                            name="projects[{{ $index }}][project_no]"
                                                            class="form-control" required></td>
                                                    <td><input disabled type="text"
                                                            name="projects[{{ $index }}][project_type]"
                                                            class="form-control" required></td>
                                                    {{-- <td><input disabled type="text"
                                                            name="projects[{{ $index }}][project_code]"
                                                            class="form-control" required></td> --}}

                                                    <td><input disabled type="text"
                                                            name="projects[{{ $index }}][engineer]"
                                                            class="form-control"></td>
                                                    <td><input disabled type="text"
                                                            name="projects[{{ $index }}][short_name]"
                                                            class="form-control"></td>
                                                    <td><input disabled type="text"
                                                            name="projects[{{ $index }}][consultant]"
                                                            class="form-control"></td>
                                                    <td><input disabled type="number" step="any"
                                                            name="projects[{{ $index }}][contract_value]"
                                                            class="form-control contract_value"></td>
                                                    <td><input disabled type="number" step="any"
                                                            name="projects[{{ $index }}][vat]" class="form-control vat">
                                                    </td>
                                                    <td><input disabled type="number" step="any"
                                                            name="projects[{{ $index }}][variation]"
                                                            class="form-control variation"></td>
                                                    <td><input disabled type="number" step="any"
                                                            name="projects[{{ $index }}][total_contract]"
                                                            class="form-control total_contract" readonly></td>
                                                    <td><input disabled type="text"
                                                            name="projects[{{ $index }}][estimation]"
                                                            class="form-control"></td>
                                                    <td><input disabled type="text"
                                                            name="projects[{{ $index }}][ps_budget]"
                                                            class="form-control"></td>
                                                    <td>
                                                        <select disabled name="projects[{{ $index }}][status]"
                                                            class="form-control">
                                                            <option value="">Select...</option>
                                                            <option>Planned</option>
                                                            <option>In Progress</option>
                                                            <option>Completed</option>
                                                            <option>Hold On</option>
                                                        </select>
                                                    </td>
                                                    <td><input disabled type="text"
                                                            name="projects[{{ $index }}][insurance]"
                                                            class="form-control"></td>
                                                    <td><input disabled type="text"
                                                            name="projects[{{ $index }}][contract]"
                                                            class="form-control"></td>
                                                    <td><input disabled type="text"
                                                            name="projects[{{ $index }}][contract_period]"
                                                            class="form-control"></td>
                                                    <td><input disabled type="text" name="projects[{{ $index }}][area]"
                                                            class="form-control"></td>
                                                    <td><input disabled type="text"
                                                            name="projects[{{ $index }}][file_no]" class="form-control">
                                                    </td>
                                                    <td><input disabled type="date"
                                                            name="projects[{{ $index }}][start_date]"
                                                            placeholder="dd/mm/yyyy" class="form-control"></td>
                                                    <td><input disabled type="date"
                                                            name="projects[{{ $index }}][deadline]"
                                                            placeholder="dd/mm/yyyy" class="form-control"></td>
                                                    <td><input disabled type="date" name="projects[{{ $index }}][date]"
                                                            placeholder="dd/mm/yyyy" class="form-control"></td>
                                                    <td><input disabled type="text"
                                                            name="projects[{{ $index }}][mobile_no]"
                                                            class="form-control"></td>
                                                    <td><input disabled type="text"
                                                            name="projects[{{ $index }}][details]" class="form-control">
                                                    </td>
                                                    <td><input disabled type="date"
                                                            name="projects[{{ $index }}][handover_on]"
                                                            placeholder="dd/mm/yyyy" class="form-control"></td>
                                                    <td class="text-center"
                                                        style="position: sticky;right: -2px; z-index:999; background: #fff !important;">
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="removeRow(this)">X</button>
                                                        <button type="button" class="btn btn-info btn-sm"
                                                            onclick="addProjectRow()">+</button>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-success mt-1" style="margin-right: 5px;" onclick="addProjectRow()">+ Add More
                                    </button>
                                    @if(Auth::user()->hasPermission('ProjectManagement_Create'))
                                    <button type="submit" class="btn btn-primary mt-1">Save</button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
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

<div class="modal fade bd-example-modal-lg" id="edit-modal" tabindex="-1" rrole="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false"
    style="z-index: 1080;">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px;background:#364a60;">
                <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">
                    Project Update</h5>
                <div class="d-flex align-items-center">
                    <button type="button" class="project-btn bg-danger text-white" data-dismiss="modal"
                        aria-label="Close" style="padding: 5px 10px; border:none; border-radius:5px;"
                        data-bs-toggle="tooltip" data-bs-placement="right" title="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{-- @include('alerts.alerts') --}}
                </div>
            </div>
            <div id="edit-show">

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="excel_import" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Import MS Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="boqUploadForm" action="{{ route('prospect-excel-import') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @php
                    $token = time() + rand(10000, 99999);
                    @endphp
                    <div class="d-flex align-items-center gap-2">
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="file" required class="form-control" name="excel_file" accept=".xlsx, .xls, .csv">

                        <button type="submit" id="uploadBtn" class="btn btn-primary d-flex align-items-center ml-1">
                            <span id="btnText">Upload</span>
                            <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"
                                aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('js/plugin/jquery-gantt.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>


<!-- jsPDF and autoTable for PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

@if(session('message_import'))
<script>
    const rawHtml = `{!! session('message_import') !!}`;

    Swal.fire({
        icon: '{{ session('alert-type') ?? 'success' }}',
        title: 'Prospect Import Result',
        html: rawHtml + `<br><button id="exportExcelBtn" class="swal2-confirm swal2-styled" style="background-color: #3085d6; margin-top: 10px;">Export to Excel</button>`,
        showConfirmButton: true
    });

    // Wait for DOM to load inside Swal
    setTimeout(() => {
        $('#exportExcelBtn').on('click', function () {
            // Extract messages from <li> tags
            const container = document.createElement('div');
            container.innerHTML = rawHtml;

            const items = Array.from(container.querySelectorAll('li')).map(li => [li.textContent.trim()]);

            if (items.length === 0) {
                items.push(['No skipped messages found.']);
            }

            // Create worksheet
            const ws = XLSX.utils.aoa_to_sheet([['Skipped Messages'], ...items]);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Skipped Rows");

            // Trigger download
            XLSX.writeFile(wb, 'boq_skipped_rows.xlsx');
        });
    }, 100);
</script>
@endif
<script>
    // if( $('#name').val()!='')
    //     {
    //         $('#profitCenter').modal('show')

    //     }
        function printFunction(){
            window.print();
        }

        $(document).on("click", "#listPrint", function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{URL('profit-center-list-print')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                },
                success: function(response){
                    document.getElementById("profitCenterPrintShow").innerHTML = response;
                    $('#profitCenterPrintModal').modal('show');
                    setTimeout(printFunction, 500);
                }
            });
        });
        $(document).on('click','.edit', function(){
            var url = $(this).data('href');
            $.ajax({
                url:url,
                type:'get',
                success:function(res){
                    $('#edit-show').html(res);
                    $('#edit-modal').modal('show');
                    setTimeout(function() {
                     $('.common-select3').select2({
                            width: '100%',
                            dropdownParent: $('#edit-modal') // modal id বসান
                        });
                    }, 50)


                    $('.datepicker').datepicker({
                        dateFormat: 'dd/mm/yy',
                        changeMonth: true,
                        changeYear: true,
                    });
                },
                error: function (xhr) {
                    let message = xhr.responseJSON?.message || 'An unexpected error occurred.';
                    toastr.error(message, 'Error');
                }
            })
        });
        $(document).on('click','.view-project', function(){
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

        $(document).ready(function() {
            var delay = (function() {
                var timer = 0;
                return function(callback, ms) {
                    clearTimeout(timer);
                    timer = setTimeout(callback, ms);
                };
            })();
            $(document).on("click", ".profit-center-form-btn", function(e) {
                e.preventDefault();
                var that = $(this);
                var urls = that.attr("data_target");
                // alert(urls);
                delay(function() {
                    $.ajax({
                        url: urls,
                        type: 'GET',
                        cache: false,
                        dataType: 'json',
                        success: function(response) {
                            //   alert('ok');
                            console.log(response);
                            $(".profit-center-form").empty().append(response.page);
                        },
                        error: function() {
                            //   alert('no');
                        }
                    });
                }, 999);
            });
        });
        // $(document).on("click", "#profitCenterButton", function(e){
        //     document.getElementById("").removeAttribute('disabled');
        // });

        $(document).on('click', '.delete-document', function(e) {
            if (!confirm('Are you sure you want to delete this document?')) return;
            var id = $(this).attr('id');
            var _token = $('input[name="_token"]').val();
            $(this).closest('.document-wrapper').remove();
            $.ajax({
                method: "post",
                url: "{{ route('delete-job-document') }}",
                data: {
                    id: id,
                    _token: _token,
                },
                success: function(response) {
                    toastr.success("Document deleted", "Success");
                }
            });
        })


        // excel like project row
        // first row add
        addProjectRow()
        function addProjectRow() {
            let firstRow = $('#projectRows tr:first');
            let newIndex = $('#projectRows tr').length;
            let newRow = firstRow.clone();

            newRow.css('display', '');
            newRow.find('input, select').prop('disabled', false);
            newRow.find('input, select').each(function () {
                let name = $(this).attr('name');
                if (name) {
                    let updatedName = name.replace(/\[\d+\]/, '[' + newIndex + ']');
                    $(this).attr('name', updatedName);
                }
                if ($(this).is('select')) {
                    $(this).val('');
                } else {
                    $(this).val('');
                }
                $(this).attr('autocomplete', 'off');
            });
            newRow.find('input[type="date"]').each(function () {
                $(this).attr('type', 'text');
                $(this).addClass('datepicker');
            });
            newRow.find('select').addClass('common-select2 w-100');
            $('#projectRows').append(newRow);
            newRow.find('.common-select2').select2({ width: '100%' });
            newRow.find('.datepicker').datepicker({
                dateFormat: 'dd/mm/yy',
                autoclose: true
            });
        }
        function removeRow(button) {
            if ($('#projectRows tr').length > 1) {
                $(button).closest('tr').remove();
                // Reorder Sr# and input names
                $('#projectRows tr').each(function (index) {
                    $(this).find('.sr-no').text(index + 1);
                    $(this).find('input, select').each(function () {
                        let name = $(this).attr('name');
                        if (name) {
                            let updatedName = name.replace(/\[\d+\]/, '[' + index + ']');
                            $(this).attr('name', updatedName);
                        }
                    });
                });
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            const table = document.getElementById("projectTable");
            table.addEventListener("keydown", function (e) {
                const key = e.key;
                const current = e.target;

                if (!["ArrowUp", "ArrowDown", "ArrowLeft", "ArrowRight"].includes(key)) return;
                // Find current cell and its index
                const currentCell = current.closest("td");
                const currentRow = current.closest("tr");
                if (!currentCell || !currentRow) return;
                const cellIndex = [...currentRow.children].indexOf(currentCell);
                const rowIndex = [...table.tBodies[0].rows].indexOf(currentRow);

                let targetInput = null;

                if (key === "ArrowRight") {
                    const nextCell = currentCell.nextElementSibling;
                    if (nextCell) targetInput = nextCell.querySelector("input, select");
                } else if (key === "ArrowLeft") {
                    const prevCell = currentCell.previousElementSibling;
                if (prevCell) targetInput = prevCell.querySelector("input, select");
                } else if (key === "ArrowDown") {
                    const nextRow = table.tBodies[0].rows[rowIndex + 1];
                if (nextRow) {
                    const nextCell = nextRow.children[cellIndex];
                    if (nextCell) targetInput = nextCell.querySelector("input, select");
                }
                } else if (key === "ArrowUp") {
                    const prevRow = table.tBodies[0].rows[rowIndex - 1];
                if (prevRow) {
                    const prevCell = prevRow.children[cellIndex];
                    if (prevCell) targetInput = prevCell.querySelector("input, select");
                }
                }
                if (targetInput) {
                    e.preventDefault(); // Prevent default scroll behavior
                    targetInput.focus();
                }
            });
        });

        // Function to calculate total contract
        function calculateTotalContract($row) {
            const contract = parseFloat($row.find('.contract_value').val()) || 0;
            const vat = parseFloat($row.find('.vat').val()) || 0;
            const variation = parseFloat($row.find('.variation').val()) || 0;

            const total = contract + vat + variation;
            $row.find('.total_contract').val(total.toFixed(2));
        }

        // Attach event handler to existing rows
        $(document).on('input', '.contract_value, .vat, .variation', function () {
            const $row = $(this).closest('tr');
            calculateTotalContract($row);
        });



        // excel like project row edit part



        // Function to calculate total contract
        function calculateTotalContractEdit() {
            const contract = parseFloat($('.contract_value-edit').val()) || 0;
            const vat = parseFloat($('.vat-edit').val()) || 0;
            const variation = parseFloat($('.variation-edit').val()) || 0;

            const total = contract + vat + variation;
            $('.total_contract-edit').val(total.toFixed(2));
        }

        // Attach event handler to existing rows
        $(document).on('input', '.contract_value-edit, .vat-edit, .variation-edit', function () {
            calculateTotalContractEdit();
        });

        // excel and pdf import
        function exportToExcel() {
            var table = document.getElementById("projectTable-data");
            var wb = XLSX.utils.table_to_book(table, { sheet: "Projects" });
            XLSX.writeFile(wb, "ProjectData.xlsx");
        }

       async function exportFullTableToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'pt', 'a2'); // Landscape, A2 for wide tables

        doc.setFontSize(14);
        doc.text("Full Project Table", 40, 30);

        // Dynamically get headers from <thead>
            const headerCells = document.querySelectorAll("#projectTable-data thead tr th");
            const headers = [
                Array.from(headerCells).map(th => th.innerText.trim())
            ];

            const data = [];
            const rows = document.querySelectorAll("#projectTable-data tbody tr");

            rows.forEach(row => {
                const cells = row.querySelectorAll("td");
                const rowData = Array.from(cells).map(cell => cell.innerText.trim());
                data.push(rowData);
            });

            doc.autoTable({
                head: headers,
                body: data,
                startY: 50,
                theme: 'grid',
                styles: {
                    fontSize: 7,
                    cellPadding: 3,
                },
                headStyles: {
                    fillColor: [52, 70, 91],
                    textColor: 255,
                    halign: 'center',
                },
                bodyStyles: {
                    halign: 'left',
                },
                didDrawPage: function (data) {
                    doc.setFontSize(10);
                    doc.text("Page " + doc.internal.getNumberOfPages(), data.settings.margin.left, doc.internal.pageSize.height - 10);
                }
            });

                doc.save("FullProjectTable.pdf");
            }
</script>
@endpush

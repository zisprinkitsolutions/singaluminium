@extends('layouts.backend.app')
@push('css')
@include('layouts.backend.partial.style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />

@endpush
<style>
    @media print {
        body {
            color-adjust: exact !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        #print_section{
            padding: 20px !important;
            margin: 10px !important;
        }
        th,td{
            color: black !important;
            font-weight: 13px;
        }
        th{
            font-size: 13px;
        }
        .text-white{
            color: black !important;
        }
        .document{
            display: none !important;
        }
        .nav.nav-tabs ~ .tab-content{
            border: white !important;
        }
        .print-hideen{
            display: none !important;
        }
        th {
            border-top: #000 !important;
        }
    }

    .file-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px;
        border: 1px solid #ddd;
        margin-bottom: 5px;
    }

    .delete-btn {
        background: #ff4444;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        cursor: pointer;
    }

    .delete-btn:hover {
        background: #cc0000;
    }
    th {
        cursor: pointer;
        padding: 10px !important;
        position: relative;
    }

    .row.small-gap {
        margin-left: -2.5px;
        margin-right: -2.5px;
    }
    .row.small-gap > [class*="col-"] {
        padding-left: 2.5px;
        padding-right: 2.5px;
    }
</style>
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.project._header')
            <div class="tab-content bg-white">
                <div id="journaCreation" class="tab-pane active p-2">
                    <div class="row print-hideen">
                        <div class="col-md-8">
                            <form action="" method="get">
                                <div class="row small-gap">
                                    <div class="col-md-6 ">
                                        <select name="party_search" id="party_search" class="common-select2 inputFieldHeight" style="width: 100%;">
                                            <option value="">Select Company...</option>
                                            @foreach ($parties as $party)
                                                <option value="{{ $party->id }}">{{ $party->pi_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 ">
                                        <input type="text" name="start_date" id="start_date" class="form-control inputFieldHeight datepicker" placeholder="Start Date">
                                    </div>
                                    <div class="col-md-2 ">
                                        <input type="text" name="end_date" id="end_date" class="form-control inputFieldHeight datepicker" placeholder="End Date">
                                    </div>
                                    {{-- <div class="col-md-3">
                                        <select name="status" id="status" class="common-select2 inputFieldHeight" style="width: 100%;">
                                            <option value="">Select...</option>
                                            <option value="Lagging Behind">Lagging Behind</option>
                                            <option value="Way Ahead">Way Ahead</option>
                                            <option value="Upto The Mark">Upto The Mark</option>
                                        </select>
                                    </div> --}}
                                    <div class="col-md-2  text-right d-flex justify-content-start">
                                        <button type="submit" class="btn btn-secondary formButton" id="submitButton">
                                            <div class="d-flex">

                                                <div><span>Search</span></div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{-- <div class="col-md-4 text-right">
                            <a href="#" class="btn btn-xs mPrint formButton" id="listPrint" title="Print" onclick="window.print()">
                                <img src="{{asset('/icon/print-icon.png')}}" alt="" srcset="" class="img-fluid" width="20"> Print
                            </a>
                            <a href="#" class="btn btn-xs mExcelButton  formButton" id="exportButton" title="Export to Excel">
                                <img src="{{asset('assets/backend/app-assets/icon/excel-icon.png')}}" class="img-fluid" width="20">
                                Excel
                            </a>
                        </div> --}}
                        <!-- Right Side (Export/Import) -->
                        <div class="col-md-4 d-flex justify-content-end">
                            <div class="dropdown print-hideen">
                                <button class="btn btn-info inputFieldHeight formButton dropdown-toggle"
                                    type="button" id="exportDropdown" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"
                                    style="padding:4px 15px !important;">
                                    Export / Import
                                </button>
                                <div class="dropdown-menu" aria-labelledby="exportDropdown">
                                    <a class="dropdown-item " id="exportButton" href="javascript:void(0);">Excel Export</a>
                                    <a class="dropdown-item " id="listPrint" href="javascript:void(0);"
                                        onclick="window.print()">Print</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        @include('layouts.backend.partial.modal-header-info')
                        @foreach ($parties as $party)
                            <h6 class="party-name my-1">{{ $party->pi_name }}</h6>
                            <table class="table table-sm sortable-table" style="border: 1px solid black !important; margin-bottom: 5px !important;">
                                <thead>
                                    <tr>
                                        <td class="text-center" style="background: #ddd !important; border-top: 1px solid black !important; font-size:13px;width:7%;">SL No </td>
                                        <td class="text-left" style="background: #ddd !important; border-top: 1px solid black !important; font-size:13px;width:20%;">Project Name </td>
                                        <td class="text-center" style="background: #ddd !important; border-top: 1px solid black !important;font-size:13px;">Start Date </td>
                                        <td class="text-center" style="background: #ddd !important; border-top: 1px solid black !important;font-size:13px;">Estimated End Date </td>
                                        <td class="text-center" style="background: #ddd !important; border-top: 1px solid black !important;font-size:13px;"> Progress % </td>
                                        {{-- <td class="text-center" style="background: #ddd !important; border-top: 1px solid black !important;font-size:12px;">Status </td> --}}
                                        <td class="text-right document" style="background: #ddd !important; border-top: 1px solid black !important;font-size:12px;">Document </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $projects = $party->jobProjects;
                                        if($start_date){
                                            $projects = $projects->where('start_date', $start_date);
                                        }
                                        if($end_date){
                                            $projects = $projects->where('end_date', $end_date);
                                        }
                                    @endphp
                                    @foreach ($projects as $key => $project)
                                        <tr style="font-size: 12px;">
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td class="text-left" style="font-size:12px;">{{ $project->project_name . '( ' .$project->project_code . ')' }}</td>
                                            <td class="text-center">{{ date('d/m/Y', strtotime($project->start_date)) }}</td>
                                            <td class="text-center">{{ date('d/m/Y', strtotime($project->end_date)) }}</td>
                                            {{-- <td class="text-center">{{$project->estimated_progress}}</td> --}}
                                            <td class="text-center">{{$project->working_progress}}</td>

                                            @php
                                                $diff = $project->estimated_progress - $project->working_progress;
                                            @endphp
                                            {{-- @if($project->estimated_progress >  $project->working_progress)
                                            <td class="text-center">{{ $diff}} % Lagging Behind</td>
                                            @else
                                             <td class="text-center">{{ $diff}} % Way Ahead</td>
                                            @endif --}}
                                            <td class="text-right pr-2 document" style="padding: 0">
                                                <a class="btn document-view" id="{{ $project->id }}" title="Project Document" style="padding: 5px !important;">
                                                    <img src="{{ asset('/icon/authorize.png') }}" style="height: 25px; width: 25px;">
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    </div>
                    {{$parties->links()}}
                </div>
            </div>
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
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script>
    $(document).on('click', '.document-view', function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('project-document-view') }}",
            method: "POST",
            data: {
                id: id,
                _token: _token,
            },
            success: function(response) {
                document.getElementById("voucherPreviewShow").innerHTML = response;
                $('#voucherPreviewModal').modal('show');
                initFileUploadHandler()
            }
        })
    });
    let selectedFiles = [];

    function initFileUploadHandler() {
        const fileInput = document.getElementById('fileInput');
        const fileList = document.getElementById('fileList');

        if (!fileInput || !fileList) return; // Safety check

        fileInput.addEventListener('change', function(e) {
            selectedFiles = selectedFiles.concat(Array.from(e.target.files));
            updateFileListDisplay();
            updateFileInput();
        });

        function updateFileListDisplay() {
            fileList.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                fileItem.innerHTML = `
                    <span>${file.name}</span>
                    <button class="delete-btn" data-index="${index}">×</button>
                `;
                fileList.appendChild(fileItem);
            });

            fileList.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    selectedFiles.splice(index, 1);
                    updateFileListDisplay();
                    updateFileInput();
                });
            });
        }

        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            fileInput.files = dataTransfer.files;
        }
    }

</script>
<script>
    document.getElementById('exportButton').addEventListener('click', function () {
        const tables = document.querySelectorAll('.table');
        const wb = XLSX.utils.book_new();

        tables.forEach((table, index) => {
            // Clone the table to safely modify it without touching the real DOM
            const clonedTable = table.cloneNode(true);

            // Find all <th> elements
            const ths = clonedTable.querySelectorAll('thead th');
            const columnIndexesToRemove = [];

            // Determine which column indexes have class "document"
            ths.forEach((th, idx) => {
                if (th.classList.contains('document')) {
                    columnIndexesToRemove.push(idx);
                }
            });

            // Remove the .document <th> and <td> columns
            clonedTable.querySelectorAll('tr').forEach(row => {
                columnIndexesToRemove
                    .slice() // clone the array to prevent mutation
                    .reverse() // remove from right to left to avoid index shifting
                    .forEach(index => {
                        if (row.children[index]) {
                            row.removeChild(row.children[index]);
                        }
                    });
            });

            // Sheet name from previous heading
            let heading = table.previousElementSibling;
            while (heading && heading.tagName !== 'H6') {
                heading = heading.previousElementSibling;
            }

            let sheetName = heading ? heading.textContent.trim() : `Sheet${index + 1}`;
            sheetName = sheetName.substring(0, 31); // Max sheet name length

            const ws = XLSX.utils.table_to_sheet(clonedTable);
            XLSX.utils.book_append_sheet(wb, ws, sheetName);
        });

        XLSX.writeFile(wb, "ExportedData.xlsx");
    });
</script>


@endpush

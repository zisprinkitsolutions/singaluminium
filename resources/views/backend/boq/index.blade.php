@extends('layouts.backend.app')
@push('css')

@include('layouts.backend.partial.style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
<style>
    tr:nth-child(even) {
        background-color: #c8d6e357;
    }

    #searchForm .select2-container {
        min-width: 250px !important;
        max-width: 250px !important;
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
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <form class="d-flex" id="searchForm" action="{{ route('boq.index') }}" method="GET">
                                    <!-- Search input takes more space -->
                                    <input type="text" name="search"
                                        class="form-control inputFieldHeight mr-1 flex-grow-1" placeholder="search"
                                        autocomplete="off" value="{{$search}}" style="min-width:250px;">

                                    <!-- Party select slightly smaller -->
                                    <select name="party_id" class="form-control common-select2 mr-1" style="min-width:250px;">
                                        <option value=""> Select ... </option>
                                        @foreach ($parties as $party)
                                        <option value="{{$party->id}}" {{$party_id==$party->id ? 'selected' : ''}}>
                                            {{$party->pi_name}} </option>
                                        @endforeach
                                    </select>

                                    <!-- Submit button -->
                                    <div class="col-md-1 p-0">
                                        <button type="submit" class="btn btn-info inputFieldHeight formButton w-100 text-center">
                                            Search
                                        </button>
                                    </div>
                                </form>
                                @if(Auth::user()->hasPermission('ProjectManagement_Create'))
                                <a
                                    class="btn btn-primary ml-1 inputFieldHeight formButton boq-create-btn"
                                    style="padding:4px 25px !important;margin-left: 5px !important; margin-right: 0px;">
                                    Add New
                                </a>
                                @endif

                                <div class="dropdown d-inline-block ml-1">
                                    <button class="btn btn-info inputFieldHeight formButton dropdown-toggle"
                                        type="button" id="exportDropdown" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false" style="padding:4px 15px !important;">
                                        Export/Import Options
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="exportDropdown">
                                        <a class="dropdown-item" href="javascript:void(0);" onclick="exportToExcel()">📤
                                            Excel</a>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            onclick="exportFullTableToPDF()">📙 PDF</a>
                                        {{-- <a class="dropdown-item" href="#" data-toggle="modal"
                                            data-target="#excel_import">Excel Import</a> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div id="gantt-chart">
                                <div class="data-table table-responsive mt-2">
                                    <table class="table table-sm" id="Table-data">

                                        <thead style="background-color:#34465b !important;">
                                            <tr>
                                                <th style="color: #fff; min-width: 50px; max-width: 50px;"> SL </th>
                                                <th style="color: #fff; padding-left:0;"> Party Name </th>
                                                <th style="color: #fff;" class="text-center"> BOQ NO </th>
                                                <th style="color: #fff;" class="text-center"> Date </th>
                                                <th style="color: #fff;" class="text-right"> Total Amount <br>
                                                    {{ number_format($boqs->sum('total_amount'), 2) }} </th>
                                                <th style="color: #fff;" class="text-center"> Status </th>
                                                <th style="color: #fff;" class="text-right"> Action </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($boqs as $key => $boq)
                                                <tr style="text-transform:uppercase">
                                                    <td>{{ ($boqs->currentPage() - 1) * $boqs->perPage() + $key + 1 }}</td>

                                                    <td title="{{ optional($boq->party)->pi_name }}">
                                                        {{ \Illuminate\Support\Str::limit(optional($boq->party)->pi_name, 30) }}
                                                    </td>
                                                    <td class="text-center edit"
                                                        data-url="{{ route('boq.show', $boq->id) }}">
                                                        {{ $boq->boq_no }}</td>
                                                    <td class="text-center edit"
                                                        data-url="{{ route('boq.show', $boq->id) }}">
                                                        {{ date('d/m/Y', strtotime($boq->date)) }}</td>
                                                    <td class="text-right edit"
                                                        data-url="{{ route('boq.show', $boq->id) }}">
                                                        {{ number_format($boq->total_amount, 2) }}</td>
                                                    @if ($boq->status == 0)
                                                        <td class="text-center edit"
                                                            data-url="{{ route('boq.show', $boq->id) }}">
                                                            <span class="badge badge-danger"
                                                                style="text-transform: capitalize; padding:4px 8px;font-size:11px;">
                                                                Draft </span>
                                                        </td>
                                                    @elseif($boq->status == 1)
                                                        <td class="text-center edit"
                                                            data-url="{{ route('boq.show', $boq->id) }}">
                                                            <span class="badge badge-info"
                                                                style="text-transform: capitalize; padding:4px 8px;font-size:11px;">
                                                                New </span>
                                                        </td>
                                                    @else
                                                        <td class="text-center edit"
                                                            data-url="{{ route('boq.show', $boq->id) }}">
                                                            <span class="badge "
                                                                style="text-transform: capitalize; padding:4px 8px;font-size:11px; background:#408F03;">
                                                                Approved </span>
                                                        </td>
                                                    @endif

                                                    <td
                                                        style="display: flex; justify-content: right; align-items: center; gap: 6px;">
                                                        @if ($boq->status < 2)
                                                            @if (Auth::user()->hasPermission('ProjectManagement_Delete'))
                                                                <!-- Delete Button -->
                                                                <form action="{{ route('boq.destroy', $boq->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <button type="submit"
                                                                        onclick="event.preventDefault(); deleteAlert(this, 'Are you sure, want to delete this BOQ?');"
                                                                        style="width: 20px; height: 20px; background-color: white; border: none; color: #dc3545; cursor: pointer;
                                                                display: flex; align-items: center; justify-content: center; padding: 0; border-radius: 4px;"
                                                                        title="Delete">
                                                                        <i class="bx bx-trash"
                                                                            style="font-size: 14px;"></i>
                                                                    </button>
                                                                </form>
                                                            @endif

                                                            <!-- Edit Button -->
                                                            @if (Auth::user()->hasPermission('ProjectManagement_Edit'))
                                                                <a href="{{ route('boq.edit', $boq->id) }}"
                                                                    style="width: 20px; height: 20px; background-color: white; border: none; color: #17a2b8; text-decoration: none;
                                                            display: flex; align-items: center; justify-content: center; padding: 0; border-radius: 4px; cursor: pointer;"
                                                                    title="Edit">
                                                                    <i class="bx bx-edit" style="font-size: 14px;"></i>
                                                                </a>
                                                            @endif
                                                        @endif

                                                        <!-- View Button -->
                                                        <button type="button"
                                                            data-url="{{ route('boq.show', $boq->id) }}" class="edit"
                                                            style="width: 20px; height: 20px; background-color: white; border: none; color: #007bff;
                                                            display: flex; align-items: center; justify-content: center; padding: 0; border-radius: 4px; cursor: pointer;"

                                                        title="View Details">
                                                        <i class="bx bx-spreadsheet" style="font-size: 14px;"></i>
                                                    </button>
                                            </td>


                                                </tr>
                                            @endforeach
                                            {{-- <tr style=" background-color: #3d4a94 !important; color:white;">
                                            <td colspan="8" style="text-align: right ; margin-right:5px;">Total</td>
                                            <td>{{ number_format($cal_total, 2) }}</td>
                                            <td colspan="2"></td>
                                        </tr> --}}
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            <div class="mt-1">
                                {!! $boqs->links() !!}
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Boq Details Modal -->
<div class="modal fade" id="boq_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px; background:#364a60;" >
                <h5 class="modal-title text-white" id="exampleModalCenterTitle"> Boq details </h5>
                <button type="button" data-dismiss="modal"
                    style="border: none; background:rgb(216, 43, 43);color: white; width: 30px; height: 30px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px;">
                    X
                </button>
            </div>
            <div class="modal-body" style="padding: 15px 15px;">
                <form id="boqDetailForm" action="{{route('boq.create')}}" method="GET"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 col-lg-3 form-group">
                            <label for="party_id"> Project <span class="text-danger">*</span> </label>
                            <select name="project_id" id="project_id" class="form-control common-select2" required>
                                <option value=""> Select...</option>
                                @foreach ($project_lists as $project)
                                <option value="{{$project->id}}">  {{$project->name}} </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 col-lg-3 form-group">
                            <div class="d-flex align-items-center" style="gap: 5px;">
                                <div style="width:90%">
                                    <label for="total_length"> Total Area <span class="text-danger">*</span></label>
                                    <input type="number" step="any" class="form-control inputFieldHeight"  name="total_area" placeholder="Enter total area" required>
                                </div>

                                <div style="wdith:10%">
                                    <label for="unit"> unit <span class="text-danger">*</span></label>
                                    <select name="total_area_unit" class="form-control common-select2">
                                        <option value="m2"> Square meter (m²) </option>
                                        <option value="ft2"> Square foot (ft²) </option>
                                        <option value="yd"> Square yard (yd²) </option>
                                        <option value="m2"> Are (a) </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 form-group">
                            <div class="d-flex align-items-center" style="gap: 5px;">
                                <div style="width:90%">
                                    <label for="total_length"> Constration Area <span class="text-danger">*</span></label>
                                    <input type="number" step="any" class="form-control inputFieldHeight" name="constraction_area" placeholder="Enter total area" required>
                                </div>

                                <div style="wdith:10%">
                                    <label for="unit"> Unit <span class="text-danger">*</span></label>
                                    <select name="constraction_area_unit" class="form-control common-select2">
                                        <option value="m2"> Square meter (m²) </option>
                                        <option value="ft2"> Square foot (ft²) </option>
                                        <option value="yd"> Square yard (yd²) </option>
                                        <option value="m2"> Are (a) </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 form-group">
                            <label for="unit"> Finishing Type <span class="text-danger">*</span></label>
                            <select name="work_type" class="form-control common-select2">
                                <option value="standard"> Standard </option>
                                <option value="in"> Deluxe </option>
                                <option value="ft"> Primium </option>
                            </select>
                        </div>
                    </div>

                    <button class="btn btn-primary"> Generate Boq </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="voucherPreviewShow">

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Excel Import Modal -->
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
                <form id="boqUploadForm" action="{{ route('boq-excel-import') }}" method="POST"
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
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <!-- jsPDF and autoTable for PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    @if (session('message_import'))
        <script>
            const rawHtml = `{!! session('message_import') !!}`;

            Swal.fire({
                icon: '{{ session('alert-type') ?? 'success' }}',
                title: 'BOQ Import Result',
                html: rawHtml +
                    `<br><button id="exportExcelBtn" class="swal2-confirm swal2-styled" style="background-color: #3085d6; margin-top: 10px;">Export to Excel</button>`,
                showConfirmButton: true
            });

            // Wait for DOM to load inside Swal
            setTimeout(() => {
                $('#exportExcelBtn').on('click', function() {
                    // Extract messages from <li> tags
                    const container = document.createElement('div');
                    container.innerHTML = rawHtml;

                    const items = Array.from(container.querySelectorAll('li')).map(li => [li.textContent
                    .trim()]);

                    if (items.length === 0) {
                        items.push(['No skipped messages found.']);
                    }

                    // Create worksheet
                    const ws = XLSX.utils.aoa_to_sheet([
                        ['Skipped Messages'], ...items
                    ]);
                    const wb = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(wb, ws, "Skipped Rows");

                    // Trigger download
                    XLSX.writeFile(wb, 'boq_skipped_rows.xlsx');
                });
            }, 100);
        </script>
    @endif
    <script>
        $(document).ready(function() {
            document.getElementById('boqUploadForm').addEventListener('submit', function(e) {
                const uploadBtn = document.getElementById('uploadBtn');
                const btnText = document.getElementById('btnText');
                const btnSpinner = document.getElementById('btnSpinner');

                // Disable button and show spinner
                uploadBtn.disabled = true;
                btnText.textContent = "Uploading";
                btnSpinner.classList.remove('d-none');
            });
            $(document).on('click', '.edit', function() {
                var url = $(this).data('url');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(res) {
                        $("#voucherPreviewShow").html(res);
                        $('#voucherPreviewModal').modal('show');
                    }
                })
            })

            function deleteItem(id) {
                $.ajax({
                    url: '/task-items/' + id,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        alert('Deleted');
                        fetchItems();
                    },
                    error: function() {
                        alert('Delete failed');
                    }
                });
            }
        });
    }, 100);
</script>

<script>
    $(document).ready(function (){

        document.getElementById('boqUploadForm').addEventListener('submit', function(e) {
            const uploadBtn = document.getElementById('uploadBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            // Disable button and show spinner
            uploadBtn.disabled = true;
            btnText.textContent = "Uploading";
            btnSpinner.classList.remove('d-none');
        });

        $(document).on('click', '.boq-create-btn', function(){
            Swal.fire({
            title: 'Do you need AI service ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!',
            cancelButtonText: 'No!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#boq_details').modal('show');
                }else{
                    window.location.href = "{{ route('boq.create') }}";
                }
            })
        })


        // View BOQ Details in Modal
        $(document).on('click', '.edit', function(){
            var url = $(this).data('url');
            $.ajax({
                url:url,
                type:'get',
                success:function(res){
                    $("#voucherPreviewShow").html(res);
                    $('#voucherPreviewModal').modal('show');
                }
            })
        })

        function deleteItem(id) {
            $.ajax({
                url: '/task-items/' + id,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function () {
                    alert('Deleted');
                    fetchItems();
                },
                error: function () {
                    alert('Delete failed');
                }
            });
        }
    });
// excel and pdf import
function exportToExcel() {
    var table = document.getElementById("Table-data");
    var wb = XLSX.utils.table_to_book(table, { sheet: "Projects" });
    XLSX.writeFile(wb, "boq-Data.xlsx");
}
async function exportFullTableToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'pt', 'a2'); // Landscape, A2 for wide tables
    doc.setFontSize(14);
    doc.text("BOQ Table Data", 40, 30);

    // Dynamically get headers from <thead>
        const headerCells = document.querySelectorAll("#Table-data thead tr th");
        const headers = [
            Array.from(headerCells).map(th => th.innerText.trim())
        ];

        const data = [];
        const rows = document.querySelectorAll("#Table-data tbody tr");

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

        doc.save("boq-Table.pdf");
    }
</script>
@endpush

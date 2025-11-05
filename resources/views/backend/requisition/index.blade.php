@extends('layouts.backend.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@section('content')
@include('layouts.backend.partial.style')
<style>
    .changeColStyle span {
        min-width: 16%;
    }

    .modal-body {
        max-height: 70vh;
        /* modal body height */
        overflow-y: auto;
        /* enable vertical scroll */
    }

    .changeColStyle .select2-container--default .select2-selection--single .select2-selection__arrow b {
        display: none;
    }

    .journaCreation {
        background: #1214161c;
    }

    .transaction_type {
        padding-right: 5px;
        padding-left: 5px;
        padding-bottom: 5px;
    }

    @media only screen and (max-width: 1500px) {
        .custome-project span {
            max-width: 140px;
        }
    }

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
        text-align: center !important;

    }

    .table-sm th,
    .table-sm td {
        padding: 0rem;
    }

    .table .thead-light th {
        background-color: #394c62;
    }

    tr {
        cursor: pointer;
    }

    .ui-datepicker {
        z-index: 9999 !important;
    }

    /* Left align the dropdown options */
    .select2-container--default .select2-results__option {
        text-align: left;
    }

    /* Left align the selected item */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        text-align: left;
    }

    /* Optional: make placeholder text left aligned */
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        text-align: left;
    }

    input,
    select {
        text-align: left !important;
    }

    label {
        text-align: left !important;
    }
</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.purchase._header', ['activeMenu' => 'requisition'])
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active">
                    <section id="widgets-Statistics">
                        <div class="row">
                            <div class="col-md-12 px-2">
                                <div class="card shadow-sm border-0">

                                    <div class="card-body p-2">
                                        <!-- Search Filters -->
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-2 w-100">
                                            <!-- Left side filters -->
                                            <div class="d-flex flex-wrap gap-2">
                                                <input type="text" name="search" id="search"
                                                    class="form-control form-control-sm mr-1"
                                                    style="width: 200px; height: 32px;" placeholder="🔎 Requisition No">

                                                <input type="text" name="date_search" id="date_search"
                                                    class="form-control form-control-sm datepicker mr-1"
                                                    style="width: 150px; height: 32px;" placeholder="📅 Date"
                                                    autocomplete="off">
                                            </div>

                                            <!-- Right side button -->
                                            <div class="ms-auto">
                                                <button type="button"
                                                    class="btn btn-primary btn-sm fw-bold createRequisitionModal"
                                                    data-bs-toggle="modal" data-bs-target="#createRequisitionModal">
                                                    ➕ Create Requisition
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Table -->
                                        <div class="table-responsive" style="max-height:500px; overflow-y:auto;">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover table-striped align-middle">
                                                    <thead class="thead-light sticky-top" style=" z-index: 999;">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Date</th>
                                                            <th>Req No</th>
                                                            <th class="text-left ml-1">Project</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="purch-body">
                                                        @foreach ($expenses as $key => $item)
                                                        <tr style="cursor: pointer;" class="lpo_bill_view"
                                                            data-url="{{ route('requisitions.show', $item->id) }}">
                                                            <td>{{ ($expenses->currentPage() - 1) * $expenses->perPage()
                                                                + $key + 1 }}</td>
                                                            <td>{{ date('d/m/Y', strtotime($item->date)) }}</td>
                                                            <td>{{ $item->requisition_no }}</td>
                                                            <td class="text-left ml-1">{{ $item->project->project_name
                                                                ?? '-' }}</td>
                                                            <td
                                                                title="@if($item->status == 'Rejected') {{ $item->note }} @endif">
                                                                <span class="badge
                                                            @if($item->status == 'Approved') bg-success
                                                            @elseif($item->status == 'Rejected') bg-danger
                                                            @elseif($item->status == 'Created') bg-warning text-dark
                                                            @else bg-secondary @endif">
                                                                    {{ $item->status }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-sm view-details"
                                                                    data-url="{{ route('requisitions.show', $item->id) }}">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- Pagination -->
                                        <div class="mt-2">
                                            {!! $expenses->links() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <!-- Create Requisition Modal -->

            </div>
        </div>
    </div>
    {{-- modal --}}
    <!-- Add Item Button -->
    <!-- Create Requisition Modal -->
    <!-- Create Requisition Modal (Desktop-friendly) -->
    <div class="modal fade" id="createRequisitionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white" style="padding: 5px 20px; background: #1c26">
                    <h5 class="modal-title text-white" id="createRequisitionModalLabel">➕  Requisition</h5>
                    <button type="button" class="btn btn-sm btn-light text-dark"data-dismiss="modal" aria-label="Close">❌</button>
                </div>

                <form action="{{ route('requisitions.mobile.store') }}" method="POST" id="formSubmit"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="requisition_id" id="requisition_id" value="">
                    <div class="modal-body">
                        <div class="row g-3 text-left">
                            <!-- Plot No -->
                            <div class="col-md-2 d-none">
                                <label class="form-label">Plot No</label>
                                <input type="text" class="form-control" id="plot_no" style="height: 35px;"
                                    placeholder="Enter plot no">
                            </div>

                            <!-- Project/Plot -->
                            <div class="col-md-4 text-left">
                                <label class="form-label">Project Name</label>
                                <select class="form-control common-select2" name="project_id" id="project_id" required>
                                    <option value="">Select Project</option>
                                    @foreach ($projects as $item)
                                    <option value="{{ $item->id }}" ata-plot="{{ optional($item->new_project)->plot }}">
                                        {{ $item->project_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Attention -->
                            <div class="col-md-3 text-left">
                                <label class="form-label">Attention</label>
                                <select class="form-control common-select2" name="attention" id="attention">
                                    <option value="">Select Attention</option>
                                    @foreach ($attentions as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date -->
                            <div class="col-md-3 text-left">
                                <label class="form-label">Date</label>
                                <input type="text" class="form-control datepicker"
                                    value="{{ Carbon\Carbon::now()->format('d/m/Y') }}" style="height: 35px;"
                                    name="date" placeholder="dd-mm-yyyy" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="card shadow-sm border-0 mt-1">
                            <div class="card-header bg-primary text-white" style="padding: 5px;">
                                <h6 class="mb-0 text-white"><i class="fas fa-tasks"></i> Add Item</h6>
                            </div>
                            <div class="card-body">

                                <!-- Task & Sub Task Selection -->
                                <div class="row mb-1">
                                    <div class="col-md-4 text-left">
                                        <label for="task_id" class="form-label fw-bold text-left">Task</label>
                                        <select class="form-control common-select2" name="task_id" id="task_id">
                                            <option value="">Select Task</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 p-0 text-left d-none">
                                        <label for="sub_task_id" class="form-label fw-bold text-left">Sub Task</label>
                                        <select class="form-control common-select2" name="sub_task_id" id="sub_task_id">
                                            <option value="">Select Sub Task</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Items Container -->
                                <div class="temp_items_container ">

                                </div>

                                <!-- Buttons -->
                                <div class="d-flex justify-content-start mt-1">
                                    <button type="button" class="btn btn-sm btn-success mr-1" id="addMoreItemBtn">
                                        <i class="fas fa-plus"></i> Add More
                                    </button>
                                    <button type="button" id="saveItemsBtn" class="btn btn-primary btn-sm">
                                        <i class="fas fa-save"></i> Save Items
                                    </button>
                                </div>

                            </div>
                        </div>
                        <hr class="my-1">
                        <!-- Items -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6>Items</h6>

                        </div>
                        <div class="main_items_container"></div>
                        <div class="main_items_container1"></div>
                    </div>

                    <div class="modal-footer mb-0" style="padding: 5px;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitRequisitionBtn">
                            <span id="submitText">Save</span>
                            <span id="loadingSpinner" class="loading-spinner d-none"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- <div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true"
        style="width: 380px; left: 15px; top: 10px;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: #f8f8f8">

                <div class="modal-header">
                    <h5 class="modal-title">Add Item</h5>
                    <button type="button" class="btn btn-sm btn-danger" style="padding: 8px; " data-dismiss="modal"
                        aria-label="Close">❌</button>
                </div>

                <div class="modal-body">
                    <h5 class="modal-title">Add Item</h5>

                    <!-- Task -->
                    <div class="mb-3">
                        <label class="form-label-mobile">Task</label>
                        <select class="form-control form-control-mobile common-select21" name="task_id" id="task_id">
                            <option value="">Select Task</option>

                        </select>
                    </div>

                    <!-- Sub Task -->
                    <div class="mb-3">
                        <label class="form-label-mobile">Sub Task</label>
                        <select class="form-control form-control-mobile common-select22" name="sub_task_id"
                            id="sub_task_id">
                            <option value="">Select Sub Task</option>
                        </select>
                    </div>

                    <!-- Items Container -->

                    <div class="temp_items_container">

                    </div>
                    <button type="button" class="btn btn-sm btn-success float-right p-1m mt-1" id="addMoreItemBtn">
                        <i class="fas fa-plus"></i> Add More
                    </button>
                    <!-- Datalist -->


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="saveItemsBtn" class="btn btn-primary">Save Items</button>
                </div>

            </div>
        </div>
    </div> --}}
    <!-- END: Content-->
    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherPreviewShow">

                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('requisition-rejected') }}" method="POST" id="modalForm">
                    @csrf
                    <!-- Body -->
                    <div class="modal-body">
                        <!-- Visible Input -->
                        <div class="form-group">
                            <label for="note" class="float-left pb-1">Note :</label>
                            {{-- <input type="text" class="form-control" id="note" name="note"
                                placeholder="Type something..."> --}}
                            <textarea class="form-control" id="note" name="note" rows="4"
                                placeholder="Type something..."></textarea>
                        </div>

                        <!-- Hidden Input -->
                        <input type="hidden" id="hidden_id" name="hidden_id">
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> --}}
                        <button type="submit" form="modalForm" class="btn btn-primary">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <datalist id="acc_head_list">
        @foreach ($heads as $item)
        <option value="{{ $item->name }}">
            @endforeach
    </datalist>
    @endsection
    @push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/js/scripts/forms/select/form-select2.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/js/scripts/forms/form-repeater.js"></script>
    {{-- js work by mominul start --}}

    <script>
        $(document).on('click', '.createRequisitionModal', function(e) {
            // Clear previous selections and inputs
            e.preventDefault();
            $('#createRequisitionModal').modal('show');

            $('#project_id, #attention').val('').trigger('change');

            // Reset datepicker to today
            let today = new Date();
            let formattedDate =
            `${String(today.getDate()).padStart(2,'0')}/${String(today.getMonth()+1).padStart(2,'0')}/${today.getFullYear()}`;
            $('.datepicker[name="date"]').val(formattedDate);

            // Clear hidden requisition ID
            $('#requisition_id').val('');

            // Clear items container
            $('.main_items_container').html('');
        });

        $(document).on("click", ".lpo_bill_view", function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            $.ajax({
                url: url,
                type: "get",
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                }
            });
        });

        $(document).on("click", ".req-reject-btn", function(e) {
            e.preventDefault();
            var requisitionId = $(this).data('id');
            $('#rejectModal').modal('show');
            $('#hidden_id').val(requisitionId);
            // alert(requisitionId);
            // $.ajax({
            //     url: url,
            //     type: "get",
            //     success: function(response) {
            //         document.getElementById("voucherPreviewShow").innerHTML = response;
            //         $('#voucherPreviewModal').modal('show')
            //     }
            // });
        });

        function search() {
            var value = $('#search').val();
            var party = $('#party_search').val();
            var date = $('#date_search').val();

            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('search-requisition') }}",
                method: "POST",
                data: {
                    value: value,
                    party: party,
                    date: date,
                    _token: _token,
                },
                success: function(response) {
                    $("#purch-body").empty().append(response);
                }
            })
        }

        $('#party_search').change(function() {
            search();
        });

        $('#date_search').change(function() {
            search();
        });

        $('#search').keyup(function() {
            search();
        })
    </script>

    <script>
        $(document).ready(function() {

            // ---------- Select2 -----------
            // Initialize Select2 after the modal is shown
            $('#createRequisitionModal').on('shown.bs.modal', function() {
                $('.common-select2').select2({
                    dropdownParent: $('#createRequisitionModal'),
                    width: '100%'
                });
            });

            // Destroy Select2 when modal is hidden to prevent issues
            $('#createRequisitionModal').on('hidden.bs.modal', function() {
                $('.common-select2').select2('destroy');
            });
            // -------------------------------

            // ------------ Datepicker -------------
            $(function() {
                $(".datepicker").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd/mm/yy",
                    startDate: new Date(),
                    minDate: 0
                });
            });


            $(document).on("change", ".datepicker", function(e) {
                var dobInput = $(this).val()

                // Split the input date into day, month, and year components
                var dateComponents = dobInput.split('/');

                if (dateComponents.length !== 3 && dateComponents.length > 0) {
                    // Handle invalid input by adding a red border
                    $(this).css("border", "1px solid red");
                    alert('Invalid date format. Please use dd/mm/yyyy format.');
                    return;
                } else {
                    $(this).css("border", "1px solid #DFE3E7");
                }

                // If the input is valid, remove any red border
                $(this).css("border", ""); // This will remove the border
            });

            // Add new item row
            let index = 1;
            $('#addItemBtn').click(function(e) {
                e.preventDefault();
                if(project_id = $('#project_id').val()){
                    $('#addItemModal').modal('show');

                    setTimeout(function () {
                    if ($('.common-select21').hasClass("select2-hidden-accessible")) {
                        $('.common-select21').select2('destroy');
                    }
                    $('.common-select21').select2({
                        dropdownParent: $('#addItemModal'),
                        width: '100%'
                    });

                }, 200);
                } else {
                    alert('Please select a project first.');
                }

            });

            // Remove row
            $(document).on('click', '.remove-row', function() {
                $(this).closest('.item-row').remove();
            });

            // Search functionality
            $('#searchButton').click(function() {
                const searchText = $('#searchInput').val().toLowerCase();
                $('.table-mobile tbody tr').each(function() {
                    const rowText = $(this).text().toLowerCase();
                    if (rowText.indexOf(searchText) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });


        $(document).on("change", "#project_id", function(e) {
            var project = $(this).val();
            $.ajax({
                url: "{{ URL('find-project-task') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    project: project,
                },
                success: function(response) {
                    $('#task_id').empty().append(response);
                    $("#sub_task_id").empty();
                    $('.main_items_container').empty();
                }
            });
        });

        // $(document).on("change", "#task_id", function(e) {
        //     var task_id = $(this).val();
        //     if (task_id) {
        //         $.ajax({
        //             url: "{{ route('find-project-task-item') }}",
        //             type: "post",
        //             cache: false,
        //             data: {
        //                 _token: '{{ csrf_token() }}',
        //                 task_id: task_id,
        //             },
        //             success: function(response) {
        //                 $("#sub_task_id").empty().append(response);

        //                 setTimeout(function () {
        //                     if ($('.common-select22').hasClass("select2-hidden-accessible")) {
        //                         $('.common-select22').select2('destroy');
        //                     }
        //                     $('.common-select22').select2({
        //                         dropdownParent: $('#addItemModal'),
        //                         width: '100%'
        //                     });
        //                 }, 200);
        //             }
        //         });
        //     }
        // });

        $("#formSubmit").submit(function(e) {
            e.preventDefault(); // avoid executing the actual submit of the form.
            var form = $(this);
            var url = form.attr('action');
            var data = new FormData(this);

          if ($(".main_items_container, .main_items_container1").children().length === 0) {
            alert("Please add at least one item before submitting.");
            e.preventDefault(); // stop form submission
            return false;
            }
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);

                        if (response.warning) {
                            toastr.warning("{{ Session::get('message') }}", response.warning);
                        } else if (response.status) {
                            // Handle validation errors
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
                            // console.log(response);

                            // $("#submitRequisitionBtn").prop("disabled", true)
                            // $(".deleteItemBtn").prop("disabled", true)
                            // $("#addItemBtn").prop("disabled", true)
                            // document.getElementById("voucherPreviewShow").innerHTML = response;
                            // $('#voucherPreviewModal').modal('show');
                            // $("#newButton").removeClass("d-none")
                            // $("#submitButton").addClass("d-none")

                            // alert(location.href);
                            // ✅ tbody reload
                            $("#purch-body").load(location.href + " #purch-body > *");
                            // ✅ modal close
                            $("#createRequisitionModal").modal('hide');
                            // ✅ form reset
                            $("#createRequisitionModal form")[0].reset();
                            // ✅ success message দেখানো
                            toastr.success("Requisition submitted successfully!");
                            $('.main_items_container').empty();
                        }
                    },
                    error: function(err) {
                        let error = err.responseJSON;
                        $.each(error.errors, function(index, value) {
                            toastr.error(value, "Error");
                        });
                    }
                });
            });


            $(document).ready(function() {
            let $projectSelect = $("#project_id");
            let $plotInput = $("#plot_no");

            // Project select করলে -> plot_no auto fill
            $projectSelect.on("change", function() {
                let plot = $(this).find("option:selected").data("plot");
                $plotInput.val(plot ? plot : "");
            });

            $plotInput.on("change", function() {
                let plotVal = $(this).val().trim().toLowerCase();
                let found = false;

                $projectSelect.find("option").each(function() {
                    let optPlot = $(this).data("plot");

                    if (optPlot && optPlot.toString().toLowerCase() === plotVal) {
                        $projectSelect.val($(this).val()).trigger(
                            "change"); // এখানে trigger যোগ করো
                        found = true;
                        return false; // loop break
                    }
                });

                if (!found) {
                    $projectSelect.val("").trigger("change"); // reset করলে ও trigger করতে হবে
                }
            });
        });

        $(document).ready(function () {
            let itemIndex = 1;
            let lastTask = $('#task_id').val();
            let lastSubTask = $('#sub_task_id').val();

            // --- Task/SubTask Change Warning ---
            $('#task_id, #sub_task_id').on('change', function () {
                if ($('.temp_items_container .item-row').length >= 1 ) {
                    if (confirm("Changing Task or Sub Task will remove all added items. Do you want to continue?")) {
                        $('.temp_items_container').empty();
                    }
                }
            });

            // --- Add More Items ---
            $('#addMoreItemBtn').click(function () {
                let taskId = $('#task_id').val();
                let subTaskId = $('#sub_task_id').val();
                let currentTaskText = $('#task_id option:selected').text(); // selected text
                let currentSubTaskText = $('#sub_task_id option:selected').text();

                // Store in data attributes (on the select elements or anywhere else)
                $('#task_id').attr('data-task-text', currentTaskText);
                $('#sub_task_id').attr('data-sub-task-text', currentSubTaskText);
                // if (!taskId) {
                //     alert("Please select a Task before adding items.");
                //     return;
                // }

                let newRow = `
                <div class="item-row " style="margin-top:5px;">
                    <div class="row  text-left">
                        <input type="hidden" name="group-a[task_id][]" value="${taskId}">
                        <input type="hidden" name="group-a[sub_task_id][]" value="${subTaskId ?? ''}">

                        <div class="col-4 pr-0 pl-1">
                            <input type="text" name="group-a[multi_acc_head][]" class="form-control" placeholder="Description"
                                list="acc_head_list" required>
                        </div>
                        <div class="col-4 pl-1 pr-0">
                            <select name="group-a[unit][]" class="form-control" required>
                                <option value="">Select Unit...</option>
                                @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3 pl-1 pr-0">
                            <input type="number" step="any" name="group-a[quantity][]" class="form-control" placeholder="Qty" required>
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-sm btn-danger deleteItemBtn" style="height: 38px;">🗑</button>
                        </div>
                    </div>
                </div>
                `;
                $('.temp_items_container').append(newRow);
                itemIndex++;
            });

            $(document).on('click', '#saveItemsBtn', function (e) {
                e.preventDefault();

                let taskId = $('#task_id').val();
                let subTaskId = $('#sub_task_id').val();
                let taskText = $('#task_id option:selected').text();
                let subTaskText = $('#sub_task_id option:selected').text();

                // if (!taskId) {
                //     alert("Please select a Task before saving items.");
                //     return;
                // }


                // Unique key for section
                let sectionKey = `task-${taskId}-sub-${subTaskId || 0}`;
                let $existingSection = $(`#${sectionKey}`);

                // Now copy all temp items into this section
                if ($('.temp_items_container').length && $('.temp_items_container .item-row').length) {
                    // If section not exists, create one
                    if ($existingSection.length === 0) {
                    let newSection = `
                    <div class="main-item-row card" id="${sectionKey}">
                        <div class="card-header d-flex justify-content-between align-items-center p-o pl-1">
                            <div class="text-align: left;">
                                <strong>Task:</strong> ${taskText} <br>
                                ${subTaskId ? `<strong>Sub Task:</strong> ${subTaskText}` : ""}
                            </div>
                            <button type="button" class="btn btn-sm btn-danger main-deleteItemBtn">🗑</button>
                        </div>
                        <div class="card-body">
                            <div class="section-items"></div>
                        </div>
                    </div>
                    `;
                    $('.main_items_container').append(newSection);
                    $existingSection = $(`#${sectionKey}`);
                    }

                $('.temp_items_container .item-row').each(function () {
                    let selectedUnit = $(this).find('select').val();
                    let rowClone = $(this).clone();

                    // Replace class names
                    rowClone.removeClass('item-row').addClass('main-item-row-single');
                    rowClone.find('.deleteItemBtn')
                    .removeClass('deleteItemBtn')
                    .addClass('main-deleteItemBtnSingle');
                        rowClone.find('select').val(selectedUnit);
                        rowClone.find('input[list]').attr('list', 'acc_head_list');

                    // Append to section
                    $existingSection.find('.section-items').append(rowClone);
                });
                } else {
                    alert("No items to add. Please add items first.");
                    return;
                }

                // Clear temp area
                $('.temp_items_container').empty();
            });

            // Delete whole section
            $(document).on('click', '.main-deleteItemBtn', function () {
                $(this).closest('.main-item-row').remove();
            });

            // Delete single item inside section
            $(document).on('click', '.main-deleteItemBtnSingle', function () {
                $(this).closest('.main-item-row-single').remove();
            });
            // --- Delete Item ---
            $(document).on('click', '.deleteItemBtn', function () {
                $(this).closest('.item-row').remove();
            });

        });


        // edit requcition

        $(document).on('click', '.editRequisitionBtn', function () {
        let id = $(this).data('id');

        let url = "{{ route('mobile.requisition.edit', ':id') }}".replace(':id', id);

        $.ajax({
        url: url,
        type: "GET",
        success: function (response) {
        // Render form
        let rawDate = response.requisition.date; // "YYYY-MM-DD"
        if (rawDate) {
        let dateParts = rawDate.split('-');
        let formattedDate = `${dateParts[2]}/${dateParts[1]}/${dateParts[0]}`;
        $('.datepicker[name="date"]').val(formattedDate);
        }
        $('#project_id').val(response.requisition.project_id).trigger('change');
        $('#attention').val(response.requisition.attention).trigger('change');
        $('#requisition_id').val(response.requisition.id);
            $('#voucherPreviewModal').modal('hide')
        $("#createRequisitionModal").modal('show');

        // Initialize select2 and datepicker
        setTimeout(function(){
            generateItemsContainer(response.requisition, @json($units));
        $('.common-select2').select2({ dropdownParent: $('#editRequisitionModal'), width: '100%' });
        $('.datepicker').datepicker();
        }, 200);
        },
        error: function () {
        toastr.error("Failed to load requisition details!");
        }
        });
        });

        function generateItemsContainer(requisition, units) {
        $(".main_items_container").empty(); // Reset first

        // Group items by task
        let groupedByTask = {};
        requisition.items.forEach(item => {
        let taskId = item.job_project_task_id || "no_task";
        let subTaskId = item.job_project_task_item_id || "no_subtask";

        if (!groupedByTask[taskId]) groupedByTask[taskId] = {};
        if (!groupedByTask[taskId][subTaskId]) groupedByTask[taskId][subTaskId] = [];

        groupedByTask[taskId][subTaskId].push(item);
        });

        // Loop through tasks
        Object.keys(groupedByTask).forEach(taskId => {
        let subTasks = groupedByTask[taskId];
        let taskName = requisition.items.find(i => i.job_project_task_id == taskId)?.task?.task_name || "-";

        let taskHtml = `
        <div class="main-item-row card ">
            <div class="card-header d-flex  align-items-center pl-1 p-0">
                <div><strong>Task:</strong> ${taskName}</div>
                <button type="button" class="btn btn-sm btn-danger main-deleteItemBtn">🗑</button>
            </div>
            <div class="card-body section-items">`;

                // Loop through subtasks
                Object.keys(subTasks).forEach(subTaskId => {
                let subTaskItems = subTasks[subTaskId];
                let subTaskName = subTaskItems[0]?.sub_task?.item_description || "-";

                taskHtml += `
                <div class="sub-task-group " style="margin-top:5px;">
                    ${subTaskId !== "no_subtask" ? `<div class="text-align:left pl-1 p-0"><strong>Sub Task:</strong> ${subTaskName}</div>` : ''}`;

                    // Loop through items in subtask
                    subTaskItems.forEach(item => {
                    taskHtml += `
                    <div class="item-row " style="margin-top:5px;">
                        <div class="row ">
                            <input type="hidden" name="group-a[task_id][]" value="${item.job_project_task_id}">
                            <input type="hidden" name="group-a[sub_task_id][]" value="${item.job_project_task_item_id || ''}">
                            <input type="hidden" name="group-a[item_id][]" value="${item.id}">
                            <input type="hidden" name="group-a[requisition_id][]" value="${item.requisition_id}">

                            <div class="col-4">
                                <input type="text" name="group-a[multi_acc_head][]" class="form-control"
                                    placeholder="Description" value="${item.item_description}" required>
                            </div>
                            <div class="col-3">
                                <select name="group-a[unit][]" class="form-control text-center" required>
                                    <option value="">Select...</option>
                                    ${units.map(u => `<option value="${u.id}" ${u.id==item.unit_id ? 'selected' : '' }>${u.name}
                                    </option>`).join('')}
                                </select>
                            </div>
                            <div class="col-3">
                                <input type="number" step="any" name="group-a[quantity][]" class="form-control"
                                    placeholder="Qty" value="${item.qty}" required>
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-sm btn-danger deleteItemBtn">🗑</button>
                            </div>
                        </div>
                    </div>`;
                    });

                    taskHtml += `
                </div>`; // Close sub-task-group
                });

                taskHtml += `
            </div>
        </div>`; // Close task card

        $(".main_items_container1").append(taskHtml);
        });
        }
    </script>
    @endpush

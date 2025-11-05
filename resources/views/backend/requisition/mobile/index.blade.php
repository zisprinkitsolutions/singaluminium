<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisition Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        :root {
            --primary-color: #34465b;
            --secondary-color: #2c3e50;
            --accent-color: #3498db;
            --light-bg: #f8f9fa;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
        }

        html,
        body {
            height: 100%;
            margin: 0;
        }

        /* Body */
        /* body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            padding-bottom: 60px;
        } */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            /* full screen height */
        }

        /* Navbar */
        .navbar-brand {
            font-weight: 600;
            font-size: 1.2rem;
        }

        /* Container */
        /* .app-container {
            max-width: 100%;
            padding: 0 10px;
        } */
        .app-container {
            flex: 1;
            /* main content will expand */
        }

        .footer {
            position: fixed;
            bottom: 10px;
            /* নিচ থেকে 10px gap */
            left: 10px;
            width: 100%;
            /* text-align: center; লেখাটা center এ থাকবে */
        }


        /* Cards */
        .card-mobile {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            border: none;
            background-color: #fff;
        }

        .card-header-mobile {
            background-color: #0076ff;
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 8px 0px;
            font-weight: 600;
        }

        /* Buttons */
        .btn-mobile-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            font-weight: 500;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-mobile-primary:hover {
            background-color: var(--secondary-color);
            color: white;
        }

        /* Search input */
        .search-input {
            border-radius: 20px;
            padding: 10px 15px;
            margin-bottom: 10px;
        }

        /* Table Responsive */
        .table-responsive-mobile {
            overflow-x: auto;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            max-height: 400px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        /* Table */
        .table-mobile {
            width: 100%;
            font-size: 14px;
            border-collapse: collapse;
            /* single borders */
            white-space: nowrap;
        }

        .table-mobile thead {
            position: sticky;
            top: 0;
            z-index: 999;
            background-color: #f8f9fa;
            box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.3);
        }

        .table-mobile th,
        .table-mobile td {
            border: 1px solid #ddd;
            padding: 10px 5px;
            text-align: center;
        }

        .table-mobile td {
            vertical-align: middle;
        }

        .table-mobile tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table-mobile tbody tr:hover {
            background-color: #e9ecef;
            cursor: pointer;
        }

        /* Status badges */
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Modals */
        .modal-mobile .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-mobile .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 15px 15px 0 0;
        }

        /* Form Controls */
        .form-label-mobile {
            font-weight: 500;
            margin-bottom: 5px;
            color: var(--secondary-color);
        }

        .form-control-mobile {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
        }

        .form-control-mobile:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 70, 91, 0.25);
        }

        /* Floating Action Button */
        .floating-action-btn {
            position: fixed;
            bottom: 50px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            /* background-color: var(--primary-color); */
            background-color: #0076ff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .floating-action-btn:hover {
            background-color: var(--secondary-color);
        }

        .dropdown-toggle::after {
            color: white;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 10px 0;
            display: flex;
            justify-content: space-around;
        }

        .nav-item-mobile {
            text-align: center;
            padding: 5px 0;
            color: #666;
            font-size: 12px;
        }

        .nav-item-mobile.active {
            color: var(--accent-color);
        }

        .nav-item-mobile i {
            font-size: 20px;
            margin-bottom: 3px;
        }

        .nav-item-mobile span {
            display: block;
            font-size: 12px;
        }

        /* Pagination */
        .pagination-mobile {
            justify-content: center;
            margin: 20px 0;
        }

        .page-link {
            border-radius: 8px;
            margin: 0 3px;
            border: none;
            color: var(--primary-color);
        }

        .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #fff;
        }

        /* Loading spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Custom Scrollbar for table */
        .table-responsive-mobile::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-responsive-mobile::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 4px;
        }

        .table-responsive-mobile::-webkit-scrollbar-track {
            background-color: rgba(0, 0, 0, 0.05);
        }

        /* Select2 styling for mobile */
        .select2-container--default .select2-selection--single {
            height: 46px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px 15px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 46px;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary-color);
        }

        .select2-container--default .select2-dropdown {
            border: 1px solid #ced4da;
            border-radius: 8px;
        }

        /* --------------- */

        /* ----------- datepicker ---------- */
        .ui-datepicker-header {
            background-color: #ffffff;
            color: white;
            padding: 10px;
            border-radius: 4px 4px 0 0;
            text-align: center;
        }

        .ui-datepicker select.ui-datepicker-month,
        .ui-datepicker select.ui-datepicker-year {
            background-color: #ffffff;
            color: #454545;
            font-weight: bold;
            border: 1px solid #c5c5c5;
            border-radius: 4px;
            padding: 5px;
            margin-left: 6px;
        }

        .ui-datepicker-calendar td a {
            text-align: center;
            display: block;
            padding: 5px;
            margin: 2px;
            background-color: #f4f4f4;
            color: #333;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .ui-datepicker-calendar td a:hover {
            background-color: #a7a7a7;
            color: white;
        }

        .ui-datepicker-calendar .ui-state-active {
            background-color: #fffa90;
            color: #4f4f4f !important;
        }

        .ui-datepicker-header {
            background-color: #ffffff;
            color: #000 !important;
        }

        /* ---------------- */

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            /* line-height: 25px !important; */
            line-height: 25px;
        }

        .table-responsive-mobile::-webkit-scrollbar {
            width: 2px;
            height: 2px;
        }
    </style>
</head>

<body>
    @php
    $settings = \App\Setting::where('config_name', 'title_name')->first();
    $company_name = \App\Setting::where('config_name', 'company_name')->first();
    @endphp
    <!-- Top Navigation -->
    <header class="navbar navbar-expand navbar-light d-block d-sm-none mt-4" style="margin-bottom:0;">
        <div class="container-fluid">
            <div class="navbar-header card-header-mobile d-flex justify-content-between align-items-center w-100">
                <span class="navbar-brand text-white" style="padding:10px 15px;">
                    {{ implode(' ', array_slice(explode(' ', $company_name->config_value), 0, 2)) }}
                </span>
                <div class="dropdown" style="padding-right:10px;">
                    <button class="btn btn-sm dropdown-toggle" style="background-color: var(--primary-color);"
                        type="button" id="profileMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-user" style="color:white"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileMenu">
                        <li>
                            <h6 class="dropdown-header" style=" font-size: 18px;">{{ Auth::user()->name ?? 'User' }}
                            </h6>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); confirmLogout();">
                                <i class="fa fa-sign-out"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    {{-- Hidden Logout Form --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    @include('backend.requisition.mobile.watermark') {{-- Show watermark only here --}}

    <!-- Main Content -->
    <div class="container app-container mt-3">
        <div class="card card-mobile">
            <div class="card-body">

                <!-- Requisitions Table -->
                <div class="table-responsive-mobile"
                    style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; border-radius: 5px;">
                    <table class="table table-mobile" style="border-collapse: collapse; width: 100%;">
                        <thead class="thead"
                            style="position: sticky; top: 0; background-color: #f8f9fa; z-index: 999; box-shadow: 0 2px 2px -1px rgba(0,0,0,0.4);">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Req No</th>
                                <th>Project</th>

                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="purch-body">
                            @foreach ($expenses as $key => $item)
                            <tr style="cursor: pointer;">
                                <td>{{ ($expenses->currentPage() - 1) * $expenses->perPage() + $key + 1 }}</td>
                                <td>{{ date('d/m/Y', strtotime($item->date)) }}</td>
                                <td>{{ $item->requisition_no }}</td>
                                <td>{{ $item->project->project_name ?? '' }}</td>

                                <td title="@if($item->status == 'Rejected') {{ $item->note }} @endif"><span class="status-badge status-pending">{{ $item->status }}</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary view-details"
                                        data-url="{{ route('requisitions.mobile.show', $item->id) }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $expenses->links() !!}
                </div>

            </div>
        </div>
    </div>

    <div class="container">
        <footer class="footer footer-static footer-light print-area">
            <p class="clearfix mb-0"><b>Business Software Solutions by</b>
                <span>
                    <img src="{{ asset('/') }}img/zikash-logo.png" style="max-height: 20px" class="img-fluid" alt="">
                </span>
            </p>
        </footer>
    </div>

    <!-- Floating Action Button -->
    <div class="floating-action-btn" >
        <i class="fas fa-plus"></i>
    </div>

    <!-- Create Requisition Modal -->
    <div class="modal fade modal-mobile" id="createRequisitionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title " id="createRequisitionModalLabel"> Requisition</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('requisitions.mobile.store') }}" method="POST" id="formSubmit"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="requisition_id" id="requisition_id" value="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label-mobile">Plot No</label>
                            <input type="text" class="form-control form-control-mobile" id="plot_no"
                                placeholder="Enter plot no">
                        </div>

                        {{-- <div class="mb-3">
                            <label class="form-label-mobile">Project/Plot</label>
                            <select class="form-control form-control-mobile common-select2" name="project_id"
                                id="project_id">
                                <option value="">Select Project</option>
                                @foreach ($projects as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->project_name }} / Plot-
                                    {{ optional($item->new_project)->plot }}
                                </option>
                                @endforeach
                            </select>
                            @error('project_id')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                            @enderror
                        </div> --}}

                        <div class="mb-3">
                            <label class="form-label-mobile">Project/Plot</label>
                            <select class="form-control form-control-mobile common-select2" name="project_id"
                                id="project_id" required>
                                <option value="">Select Project</option>
                                @foreach ($projects as $item)
                                <option value="{{ $item->id }}" data-plot="{{ optional($item->new_project)->plot }}">
                                    {{ $item->project_name }} / Plot-{{ optional($item->new_project)->plot }}
                                </option>
                                @endforeach
                            </select>
                            @error('project_id')
                            <div class="btn btn-sm btn-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- <div class="mb-3">
                            <label class="form-label-mobile">Task</label>
                            <select class="form-control form-control-mobile common-select2" name="task_id" id="task_id">
                                <option value="">Select Task</option>
                            </select>
                            @error('task_id')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label-mobile">Sub Task</label>
                            <select class="form-control form-control-mobile common-select2" name="sub_task_id"
                                id="sub_task_id">
                                <option value="">Select Sub Task</option>
                            </select>
                            @error('sub_task_id')
                            <div class="btn btn-sm btn-danger">{{ $message }}
                            </div>
                            @enderror
                        </div> --}}

                        <div class="mb-3">
                            <label class="form-label-mobile">Attention</label>
                            <select class="form-control form-control-mobile common-select2" name="attention"
                                id="attention" required>
                                <option value="">Select Project</option>
                                @foreach ($attentions as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @error('date')
                        <div class="btn btn-sm btn-danger">{{ $message }}
                        </div>
                        @enderror

                        <div class="mb-3">
                            <label class="form-label-mobile">Date</label>
                            <input type="text" class="form-control form-control-mobile datepicker"
                                value="{{ Carbon\Carbon::now()->format('d/m/Y') }}" name="date" autocomplete="off"
                                placeholder="dd-mm-yyyy" required>
                        </div>
                        @error('date')
                        <div class="btn btn-sm btn-danger">{{ $message }}
                        </div>
                        @enderror

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6>Items</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn">
                                <i class="fas fa-plus"></i> Add Item
                            </button>
                        </div>

                        <div class="main_items_container">

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-mobile-primary" id="submitRequisitionBtn">
                            <span id="submitText">Submit</span>
                            <span id="loadingSpinner" class="loading-spinner d-none"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Requisition Details Modal -->
    <div class="modal fade modal-mobile" id="requisitionDetailsModal" tabindex="-1"
        aria-labelledby="requisitionDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="requisitionModalShow">

            </div>
        </div>
    </div>

    <!-- Add Item Button -->

    <div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true"
        style="width: 380px; left: 15px; top: 10px;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: #f8f8f8">

                <div class="modal-header">
                    <h5 class="modal-title">Add Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

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
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>


    <script>
        $(document).on("click", ".req-reject-btn", function(e) {
            e.preventDefault();
            var requisitionId = $(this).data('id');
            $('#rejectModal').modal('show');
            $('#hidden_id').val(requisitionId);

        });
        $(document).on("click", ".floating-action-btn", function(e) {
            e.preventDefault();
            // Reset select2 fields
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
            $('#createRequisitionModal').modal("show");

        });

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

            $(document).on("change", ".datepicker-dob", function(e) {
                var dobInput = $(this).val()
                // Split the input date into day, month, and year components
                var dateComponents = dobInput.split('/');
                if (dateComponents.length !== 3 && dateComponents.length > 0) {
                    // Handle invalid input gracefully
                    $(this).css("border", "1px solid red");
                    alert('Invalid date format. Please use dd/mm/yyyy format.');
                    return;
                } else {
                    $(this).css("border", "1px solid #DFE3E7");
                }

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
            // -------------------------------------

            // Show details modal when view button is clicked
            $(document).on("click", ".view-details", function(e) {
                e.preventDefault();
                var url = $(this).data('url');
                $.ajax({
                    url: url,
                    type: "get",
                    success: function(response) {
                        document.getElementById("requisitionModalShow").innerHTML = response;
                        $('#requisitionDetailsModal').modal('show')
                    }
                });
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

        function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                document.getElementById('logout-form').submit();
            }
        }

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
                    if(is_edit != ture){
                       $('.main_items_container').empty();
                    }

                }
            });
        });

        $(document).on("change", "#task_id", function(e) {
            var task_id = $(this).val();
            if (task_id) {
                $.ajax({
                    url: "{{ route('find-project-task-item') }}",
                    type: "post",
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        task_id: task_id,
                    },
                    success: function(response) {
                        $("#sub_task_id").empty().append(response);

                        setTimeout(function () {
                            if ($('.common-select22').hasClass("select2-hidden-accessible")) {
                                $('.common-select22').select2('destroy');
                            }
                            $('.common-select22').select2({
                                dropdownParent: $('#addItemModal'),
                                width: '100%'
                            });
                        }, 200);
                    }
                });
            }
        });

        $("#formSubmit").submit(function(e) {
            e.preventDefault(); // avoid executing the actual submit of the form.
            var form = $(this);
            var url = form.attr('action');
            var data = new FormData(this);

            if ($(".main_items_container").children().length === 0) {
                alert("Please add at least one item before submitting.");
                return; // stop submit
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
                <div class="item-row mb-2">
                    <div class="row g-2">
                        <input type="hidden" name="group-a[task_id][]" value="${taskId}">
                        <input type="hidden" name="group-a[sub_task_id][]" value="${subTaskId ?? ''}">

                        <div class="col-4">
                            <input type="text" name="group-a[multi_acc_head][]" class="form-control" placeholder="Description"
                                list="acc_head_list" required>
                        </div>
                        <div class="col-3">
                            <select name="group-a[unit][]" class="form-control text-center" required>
                                <option value="">Select...</option>
                                @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <input type="number" step="any" name="group-a[quantity][]" class="form-control" placeholder="Qty" required>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-sm btn-danger deleteItemBtn">🗑</button>
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
                    <div class="main-item-row card mb-3" id="${sectionKey}">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
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
                    $('#requisitionDetailsModal').modal('hide')
                    generateItemsContainer(response.requisition, @json($units));
                    $("#createRequisitionModal").modal('show');

                    // Initialize select2 and datepicker
                    setTimeout(function(){
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

// Group items by task + subtask
let groupedByTask = {};
requisition.items.forEach(item => {
let taskId = String(item.job_project_task_id || "no_task");
let subTaskId = String(item.job_project_task_item_id || "no_subtask");

if (!groupedByTask[taskId]) groupedByTask[taskId] = {};
if (!groupedByTask[taskId][subTaskId]) groupedByTask[taskId][subTaskId] = [];

groupedByTask[taskId][subTaskId].push(item);
});

// Loop through tasks
Object.keys(groupedByTask).forEach(taskId => {
let subTasks = groupedByTask[taskId];
let firstItem = Object.values(subTasks)[0][0]; // first item in first subtask
let taskName = firstItem?.task?.task_name || "-";

let taskHtml = `
<div class="main-item-row card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div><strong>Task:</strong> ${taskName}</div>
        <button type="button" class="btn btn-sm btn-danger main-deleteItemBtn">🗑</button>
    </div>
    <div class="card-body section-items">`;

        // Loop through subtasks
        Object.keys(subTasks).forEach(subTaskId => {
        let subTaskItems = subTasks[subTaskId];
        let subTaskName = subTaskItems[0]?.sub_task?.item_description || "-";

        taskHtml += `
        <div class="sub-task-group mb-2">
            ${subTaskId !== "no_subtask" ? `<div class="mb-2"><strong>Sub Task:</strong> ${subTaskName}</div>` : ''}`;

            // Loop through items in subtask
            subTaskItems.forEach(item => {
            taskHtml += `
            <div class="item-row mb-2">
                <div class="row g-2">
                    <input type="hidden" name="group-a[task_id][]" value="${item.job_project_task_id || ''}">
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

$(".main_items_container").append(taskHtml);
});
}
    </script>
</body>

</html>

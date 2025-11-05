<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisition Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet">
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

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
            padding-bottom: 60px;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.2rem;
        }

        .app-container {
            max-width: 100%;
            padding: 0 10px;
        }

        .card-mobile {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            border: none;
        }

        .card-header-mobile {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 12px 15px;
            font-weight: 600;
        }

        .btn-mobile-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            font-weight: 500;
            color: white;
        }

        .btn-mobile-primary:hover {
            background-color: var(--secondary-color);
            color: white;
        }

        .search-input {
            border-radius: 20px;
            padding: 10px 15px;
            margin-bottom: 10px;
        }

        .table-responsive-mobile {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-mobile {
            width: 100%;
            font-size: 14px;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-mobile thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-mobile th {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 5px;
            font-weight: 500;
            white-space: nowrap;
        }

        .table-mobile td {
            padding: 12px 5px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        .table-mobile tbody tr {
            background-color: white;
        }

        .table-mobile tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table-mobile tbody tr:hover {
            background-color: #e9ecef;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
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

        .modal-mobile .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-mobile .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .form-label-mobile {
            font-weight: 500;
            margin-bottom: 5px;
            color: var(--secondary-color);
        }

        .form-control-mobile {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ced4da;
        }

        .form-control-mobile:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 70, 91, 0.25);
        }

        .floating-action-btn {
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 10px 0;
        }

        .nav-item-mobile {
            text-align: center;
            padding: 5px 0;
        }

        .nav-item-mobile.active {
            color: var(--accent-color);
        }

        .nav-item-mobile i {
            font-size: 20px;
            margin-bottom: 3px;
        }

        .nav-item-mobile span {
            font-size: 12px;
        }

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
        }

        /* Loading animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <header class="navbar navbar-default visible-xs-block" style="margin-bottom:0;">
        <div class="container-fluid">
            <div class="navbar-header" style="width:100%; display:flex; justify-content:space-between; align-items:center;">
                <span class="navbar-brand" style="padding:10px 15px;">
                    Company Name
                </span>
                <div class="dropdown" style="padding-right:10px;">
                    <button class="btn btn-default dropdown-toggle" type="button" id="profileMenu" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-user"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="profileMenu">
                        <li class="dropdown-header">User Name</li>
                        <li>
                            <a href="#" onclick="event.preventDefault(); confirmLogout();">
                                <i class="fa fa-sign-out"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container app-container mt-3">
        <div class="card card-mobile">
            <div class="card-header card-header-mobile d-flex justify-content-between align-items-center">
                <span>Requisitions</span>
                <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
            <div class="card-body">
                <!-- Search Box -->
                <div class="input-group mb-3">
                    <input type="text" class="form-control search-input" placeholder="Search requisitions..." id="searchInput">
                    <button class="btn btn-outline-secondary" type="button" id="searchButton">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <!-- Requisitions Table -->
                <div class="table-responsive-mobile" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-mobile">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Req No</th>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>12/08/2023</td>
                                <td>REQ-001</td>
                                <td>Project Alpha</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary view-details" data-id="1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>10/08/2023</td>
                                <td>REQ-002</td>
                                <td>Project Beta</td>
                                <td><span class="status-badge status-approved">Approved</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary view-details" data-id="2">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>05/08/2023</td>
                                <td>REQ-003</td>
                                <td>Project Gamma</td>
                                <td><span class="status-badge status-rejected">Rejected</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary view-details" data-id="3">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>01/08/2023</td>
                                <td>REQ-004</td>
                                <td>Project Delta</td>
                                <td><span class="status-badge status-approved">Approved</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary view-details" data-id="4">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>28/07/2023</td>
                                <td>REQ-005</td>
                                <td>Project Epsilon</td>
                                <td><span class="status-badge status-pending">Pending</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary view-details" data-id="5">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-mobile">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="floating-action-btn" data-bs-toggle="modal" data-bs-target="#createRequisitionModal">
        <i class="fas fa-plus"></i>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="row text-center">
            <div class="col-3 nav-item-mobile active">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </div>
            <div class="col-3 nav-item-mobile">
                <i class="fas fa-file-invoice"></i>
                <span>Requisitions</span>
            </div>
            <div class="col-3 nav-item-mobile">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </div>
            <div class="col-3 nav-item-mobile">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </div>
        </div>
    </div>

    <!-- Create Requisition Modal -->
    <div class="modal fade modal-mobile" id="createRequisitionModal" tabindex="-1" aria-labelledby="createRequisitionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRequisitionModalLabel">Create Requisition</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="requisitionForm">
                        <div class="mb-3">
                            <label class="form-label-mobile">Project/Plot</label>
                            <select class="form-control form-control-mobile">
                                <option value="">Select Project</option>
                                <option value="1">Project Alpha / Plot-12</option>
                                <option value="2">Project Beta / Plot-15</option>
                                <option value="3">Project Gamma / Plot-18</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-mobile">Task</label>
                            <select class="form-control form-control-mobile">
                                <option value="">Select Task</option>
                                <option value="1">Construction</option>
                                <option value="2">Electrical</option>
                                <option value="3">Plumbing</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-mobile">Sub Task</label>
                            <select class="form-control form-control-mobile">
                                <option value="">Select Sub Task</option>
                                <option value="1">Wiring Installation</option>
                                <option value="2">Fixture Setup</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-mobile">Attention</label>
                            <input type="text" class="form-control form-control-mobile" placeholder="Enter attention">
                        </div>

                        <div class="mb-3">
                            <label class="form-label-mobile">Date</label>
                            <input type="date" class="form-control form-control-mobile" value="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6>Items</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn">
                                <i class="fas fa-plus"></i> Add Item
                            </button>
                        </div>

                        <div class="items-container">
                            <div class="item-row mb-2">
                                <div class="row">
                                    <div class="col-7">
                                        <input type="text" class="form-control form-control-mobile" placeholder="Description" list="itemList">
                                    </div>
                                    <div class="col-3">
                                        <input type="number" class="form-control form-control-mobile" placeholder="Qty">
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <datalist id="itemList">
                            <option value="Cement">
                            <option value="Steel Rods">
                            <option value="Bricks">
                            <option value="Sand">
                            <option value="Paint">
                        </datalist>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-mobile-primary" id="submitRequisitionBtn">
                        <span id="submitText">Submit</span>
                        <span id="loadingSpinner" class="loading-spinner d-none"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Requisition Details Modal -->
    <div class="modal fade modal-mobile" id="requisitionDetailsModal" tabindex="-1" aria-labelledby="requisitionDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requisitionDetailsModalLabel">Requisition Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="detail-item">
                        <strong>Requisition No:</strong> REQ-001
                    </div>
                    <div class="detail-item">
                        <strong>Date:</strong> 12/08/2023
                    </div>
                    <div class="detail-item">
                        <strong>Project:</strong> Project Alpha
                    </div>
                    <div class="detail-item">
                        <strong>Task:</strong> Construction
                    </div>
                    <div class="detail-item">
                        <strong>Sub Task:</strong> Foundation Work
                    </div>
                    <div class="detail-item">
                        <strong>Attention:</strong> John Doe
                    </div>
                    <div class="detail-item">
                        <strong>Status:</strong> <span class="status-badge status-pending">Pending</span>
                    </div>

                    <hr>

                    <h6>Items</h6>
                    <div class="table-responsive-mobile">
                        <table class="table table-sm table-mobile">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Qty</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Cement</td>
                                    <td>50</td>
                                    <td>Bags</td>
                                </tr>
                                <tr>
                                    <td>Steel Rods</td>
                                    <td>100</td>
                                    <td>Pieces</td>
                                </tr>
                                <tr>
                                    <td>Bricks</td>
                                    <td>500</td>
                                    <td>Pieces</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-mobile-primary">Print</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade modal-mobile" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Requisitions</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label-mobile">Status</label>
                        <select class="form-control form-control-mobile">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-mobile">Date From</label>
                        <input type="date" class="form-control form-control-mobile">
                    </div>

                    <div class="mb-3">
                        <label class="form-label-mobile">Date To</label>
                        <input type="date" class="form-control form-control-mobile">
                    </div>

                    <div class="mb-3">
                        <label class="form-label-mobile">Project</label>
                        <select class="form-control form-control-mobile">
                            <option value="">All Projects</option>
                            <option value="1">Project Alpha</option>
                            <option value="2">Project Beta</option>
                            <option value="3">Project Gamma</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-mobile-primary">Apply Filters</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>

    <script>
        $(document).ready(function() {
            // Show details modal when view button is clicked
            $('.view-details').click(function() {
                $('#requisitionDetailsModal').modal('show');
            });

            // Add new item row
            $('#addItemBtn').click(function() {
                $('.items-container').append(`
                    <div class="item-row mb-2">
                        <div class="row">
                            <div class="col-7">
                                <input type="text" class="form-control form-control-mobile" placeholder="Description" list="itemList">
                            </div>
                            <div class="col-3">
                                <input type="number" class="form-control form-control-mobile" placeholder="Qty">
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-sm btn-danger">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `);
            });

            // Remove item row
            $(document).on('click', '.btn-danger', function() {
                $(this).closest('.item-row').remove();
            });

            // Simulate form submission
            $('#submitRequisitionBtn').click(function() {
                $('#submitText').addClass('d-none');
                $('#loadingSpinner').removeClass('d-none');

                setTimeout(function() {
                    $('#createRequisitionModal').modal('hide');
                    toastr.success('Requisition created successfully!');

                    // Reset form
                    $('#submitText').removeClass('d-none');
                    $('#loadingSpinner').addClass('d-none');
                }, 1500);
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
                // Simulate logout action
                toastr.info('Logged out successfully');
            }
        }
    </script>
</body>
</html>

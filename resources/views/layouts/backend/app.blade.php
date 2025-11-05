<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description"
        content="Frest admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Frest admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $settings = \App\Setting::where('config_name', 'title_name')->first();
        $company_name = \App\Setting::where('config_name', 'company_name')->first();
    @endphp
    <title>{{ $settings->config_value }} - @yield('title') </title>
    <link rel="apple-touch-icon" href="{{ asset('assets/backend') }}/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/alhareb-logo.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700"
        rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend') }}/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend') }}/custom-font.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/backend') }}/app-assets/vendors/css/forms/select/select2.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend') }}/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend') }}/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend') }}/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend') }}/app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend') }}/app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/backend') }}/app-assets/css/themes/semi-dark-layout.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/backend') }}/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/backend') }}/app-assets/css/plugins/forms/validation/form-validation.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/backend/') }}/app-assets/vendors/css/extensions/toastr.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/backend/') }}/app-assets/css/plugins/extensions/toastr.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/backend/') }}/app-assets/datatables/css/dataTables.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend') }}/style.css">
    <!-- END: Custom CSS-->

    <!-- BEGIN: Page CSS-->
    @stack('css')
    <!-- END: Page CSS-->
    <style>
        body {
            text-align: left !important;
        }

        .table-hover-custom tbody tr {
            transition: background-color 0.2s;
        }

        .table-hover-custom tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer !important;
        }

        table tbody tr:nth-child(odd) {
            background-color: #f9f9f9 !important;
        }

        /* table tbody tr:hover {
        background-color: #bbd8f9 ;
        cursor: pointer !important;
        } */
        .table-bordered {
            border: 1px solid #f4f4f4;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }

        table {
            background-color: transparent;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
        }
        .table td {
            color: black;
        }

        .tarek-container {
            width: 85%;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 88% 12%;
            background-color: #ffff;
        }

        .invoice-label {
            font-size: 10px !important
        }

        .content-padding {
            padding: 5px 10px 12px;
        }

        .content-title {
            padding: 10px 0 0 10px;
        }

        .bx-filter {
            font-size: 30px;
            line-height: 0px;
        }

        /* Tareq custom css */
        .active-button-sale {
            color: red;
        }

        .card {
            margin-bottom: 2.2rem !important;
            box-shadow: 0 4px 8px rgba(25, 42, 70, 0.1) !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            /* subtle border */
            transition: all 0.3s ease-in-out, background 0s, color 0s, border-color 0s;
            border-radius: 0.5rem !important;
        }

        .card-body {
            flex: 1 1 auto;
            min-height: 1px;
            padding: 0.5rem !important;
        }

        .col-right-padding {
            padding-right: 0px !important;
            /* padding-left: 0px !important; */
        }

        .col-left-padding {
            padding-left: 0px !important;
            /* padding-left: 0px !important; */
        }

        /* Tareq custom css */
        div.dt-buttons {
            float: right;
            margin-bottom: 10px;
        }

        .print-content {
            display: none !important;
        }

        th .sort-indicator desc {
            font-size: 12px;
            margin-left: 8px;
            color: #888;
            opacity: 0.5;
        }

        th .sort-indicator.asc::after {
            content: "▲";
        }

        th .sort-indicator.desc::after {
            content: "▼";
        }

        th .sort-indicator desc {
            opacity: 1;
            color: #007bff;
        }

        @media print {
            .menu-accordion {
                visibility: hidden;
            }

            .search_home_reports,
            .pagination {
                display: none !important;
            }

            .dt-buttons {
                visibility: hidden;
            }

            .footer {
                visibility: hidden;
            }

            a {
                text-decoration: none !important;
                color: black;
            }

            .print-menu {
                visibility: hidden;
            }

            .modal-content {
                min-width: 99%;
                min-height: 100vh;
            }

            .print-content {
                display: block !important;
            }

            .row {
                display: flex;
            }

            .col-md-1,
            .col-1 {
                width: 8.33% !important;
            }

            .col-md-2,
            .col-2 {
                width: 16.66% !important;
            }

            .col-md-3,
            .col-3 {
                width: 25% !important;
            }

            .col-md-4,
            .col-4 {
                width: 33.33% !important;
            }

            .col-md-5,
            .col-5 {
                width: 41.65% !important;
            }

            .col-md-6,
            .col-6 {
                width: 50% !important;
            }

            .col-md-7,
            .col-7 {
                width: 58.33% !important;
            }

            .col-md-8,
            .col-8 {
                width: 66.66% !important;
            }

            .col-md-9,
            .col-9 {
                width: 75% !important;
            }

            .col-md-10,
            .col-10 {
                width: 83.33% !important;
            }

            .col-md-11,
            .col-11 {
                width: 91.63% !important;
            }

            .col-md-12,
            .col-12 {
                width: 100% !important;
            }
        }

        .main-menu .navbar-header {
            height: 100%;
            width: 260px;
            height: 3.6rem;
            position: relative;
            padding: 0.35rem 1.45rem 0.3rem 1.3rem;
            transition: 300ms ease all, background 0s;
            cursor: pointer;
            z-index: 3;
        }

        .main-menu .navbar-header2 {
            height: 100%;
            width: 260px;
            height: 4.6rem;
            position: relative;
            padding: 0.35rem 1.45rem 0.3rem 1.3rem;
            transition: 300ms ease all, background 0s;
            cursor: pointer;
            z-index: 3;
        }

        .app-content {
            margin: 30px 5px 5px 5px !important;
            padding-top: 30px
        }

        #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #077ac7;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /* Spinner styles */
        .spinner {
            border: 6px solid #f3f3f3;
            /* Light grey */
            border-top: 6px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        /* Spinner animation */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading-container {
            text-align: center;
            color: white;
        }

        .loading-spinner {
            position: relative;
            width: 100px;
            height: 100px;
        }

        .spinner-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 10px solid transparent;
            border-top: 10px solid #3498db;
            position: absolute;
            top: 0;
            left: 0;
            animation: spin 1s linear infinite;
        }

        #loading-percentage {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.5em;
            font-weight: bold;
            color: #fff;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

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

        .notification-wrapper {
            position: relative;
            display: inline-block;
        }

        /* Notification Button */
        .notification-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #007bff;
            border: none;
            padding: 10px;
            border-radius: 50%;
            color: white;
            font-size: 20px;
            cursor: pointer;
            position: relative;
            transition: background-color 0.3s ease;
            width: 30px;
            height: 30px;
            margin: 5px 10px 0 10px;
        }

        .notification-btn:hover {
            background-color: #0056b3;
        }

        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #f03d0b;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
        }

        /* Notification Box (Dropdown) */
        .notification-box {
            display: none;
            position: absolute;
            top: 0px;
            right: 0;
            background-color: white;
            border: 1px solid #ddd;
            width: 250px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            padding: 10px;
            z-index: 999;
            max-height: 400px;
            overflow-y: auto;
        }

        /* Show notification box when hovering */
        .notification-wrapper:hover .notification-box {
            display: block;
        }

        /* Notification Item */
        .notification-item {
            border-bottom: 1px solid #f1f1f1;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        /* Notification Message */
        .notification-message {
            font-size: 14px;
            color: #333;
            margin: 0;
        }

        .notification-message a {
            text-decoration: none;
            color: #333;
        }

        .notification-message a:hover {
            color: #007bff;
        }

        /* Notification Time */
        .notification-time {
            font-size: 12px;
            color: #999;
            display: block;
            margin-top: 5px;
        }

        /* // NAV BAR CSS STYLE */
        .form-product-search {
            text-transform: uppercase;
            width: 180px !important;
            height: 25px;
            background: #394c62;
            border: 1px solid #ffffff;
            color: #ffffff !important;
            font-family: sans-serif;
            font-size: 11px;
        }

        .2filter-table {
            min-height: 200px;
        }

        .nav-top-menu {
            background-color: #394c62;

        }

        .menu-top-header {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .menu-item-top-header {
            position: relative;
            padding: 10px 10px;
            color: white;
            cursor: pointer;
            text-transform: uppercase;
        }

        .menu-item-top-header:hover {
            background-color: #202e3e;
        }

        .dropdown-menu-top-header {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            width: 200px;
            background-color: #202e3e;
            list-style-type: none;
            margin: 0;
            padding: 0;
            min-width: 150px;
            z-index: 1000;
            text-transform: uppercase;
        }

        .dropdown-menu-top-header li {
            padding: 5px;
            color: white;
            text-transform: uppercase;
            cursor: pointer;
        }

        .dropdown-menu-top-header li:hover {
            background-color: #002249;
            color: #fff;
        }

        .dropdown-menu-top-header li a {
            color: #fff;
            text-transform: uppercase;

        }

        .menu-top-active {
            background-color: #202e3e;
        }

        .item-active {
            background: #394c62;
            color: #fff;
        }

        .menu-item-top-header.dropdown:hover .dropdown-menu-top-header {
            display: block;
        }

        .dropdown-item-top-header {
            color: #fff;
            text-transform: uppercase;
        }

        .app-content {
            margin-top: 35px;
        }

        .navbar-sticky .app-content .content-wrapper {
            padding: 0 !important;
            margin-top: 30px !important;
        }

        .select2-container {
            box-sizing: border-box;
            display: inline-block;
            margin: 0;
            position: relative;
            vertical-align: middle;
            width: 100% !important;
        }

        .swal2-confirm,
        .swal2-cancel {
            width: 170px;
        }

        div.dataTables_wrapper div.dataTables_filter,
        div.dataTables_wrapper div.dataTables_length {
            margin: 0 !important;
            float: left;
        }

        .ui-datepicker-header {
            background-color: #ffffff;
            color: #000 !important;
        }

        .data-table .table tbody tr td {
            padding: 0px 0px !important;

        }

        .modal h3,
        .h3 {
            text-align: left;
        }

        /* ------ Button (new expense, inventory, summary) -------- */
        .btn-custom-nis {
            padding: 3px 10px !important;
            margin-right: 5px;
            width: 110px;
            color: #fff;
            border: none;
        }

        /* আলাদা রঙ */
        .btn-expense {
            background-color: #007bff;
            /* নীল */
        }

        .btn-inventory {
            background-color: #28a745;
            /* সবুজ */
        }

        .btn-summary {
            background-color: #6f42c1;
            /* বেগুনি */
        }

        /* hover effect */
        .btn-expense:hover {
            background-color: #0056b3;
        }

        .btn-inventory:hover {
            background-color: #1e7e34;
        }

        .btn-summary:hover {
            background-color: #593196;
        }
        /* ------ Button : End -------- */

        /* ----- Button (action - edit, delete, print, approve) ----- */
        .custom-action-btn {
            padding: 6px 12px;
            /* height same */
            font-size: 14px;
            min-width: 110px;
            /* সব বাটন সমান প্রস্থ */
            text-align: center;
            display: inline-flex;
            /* bootstrap 4 এ কাজ করবে */
            align-items: center;
            justify-content: center;
            margin-right: 5px;
        }

        .custom-action-btn i {
            margin-right: 5px;
            /* আইকন ও টেক্সটের মাঝে gap */
        }
    </style>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body>
    @include('layouts.backend.partial.sidebar')
    @yield('content')
    @include('layouts.backend.partial.footer')
    <!-- END: Footer-->

    <div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="padding: 5px 33px;background:#364a60;">
                    <h5 class="modal-title" id="exampleModalLabel"
                        style="font-family:Cambria;font-size: 2rem;color:white;">New Party Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('customerPost') }}" method="POST" id="customerAddNew">
                        @csrf
                        <div class="row match-height">
                            <div class="col-md-6">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Party Name</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="pi_name" class="form-control" name="pi_name"
                                                value="{{ isset($costCenter) ? $costCenter->pi_name : '' }}"
                                                placeholder="Party Name" required>
                                            @error('pi_name')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Party Type</label>
                                        </div>

                                        <div class="col-md-8 form-group customer-select">
                                            <select name="pi_type" class="common-select2"
                                                style="width: 100% !important" id="pi_type" required>
                                                @if (request()->is('*sales*') || request()->is('*project*'))
                                                    <option value="Customer">Customer</option>
                                                @elseif(request()->is('*purchase*') || request()->is('*lpo-bill*'))
                                                    <option value="Supplier">Supplier</option>
                                                @elseif(request()->is('*new-journal-creation*'))
                                                    <option value="Supplier">Supplier</option>
                                                    <option value="Customer">Customer</option>
                                                @endif

                                            </select>

                                            @error('pi_type')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>


                                        <div class="col-md-4">
                                            <label>TRN No</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="trn_no2" class="form-control" name="trn_no"
                                                value="{{ isset($costCenter) ? $costCenter->trn_no : '' }}"
                                                placeholder="TRN Number">


                                            @error('trn_no')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Address</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="address2" class="form-control" name="address"
                                                value="{{ isset($costCenter) ? $costCenter->address : '' }}"
                                                placeholder="Address">


                                            @error('address')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Contact Person</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="con_person" class="form-control"
                                                name="con_person"
                                                value="{{ isset($costCenter) ? $costCenter->con_person : '' }}"
                                                placeholder="Contact Person">


                                            @error('con_person')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Mobile Phone No</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="number" id="con_no" class="form-control" name="con_no"
                                                value="{{ isset($costCenter) ? $costCenter->con_no : '' }}"
                                                placeholder="Mobile No">


                                            @error('con_no')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Phone No</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="number" id="phone_no" class="form-control"
                                                name="phone_no"
                                                value="{{ isset($costCenter) ? $costCenter->phone_no : '' }}"
                                                placeholder="Phone No">
                                            @error('phone_no')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Email</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="email" class="form-control" name="email"
                                                value="{{ isset($costCenter) ? $costCenter->email : '' }}"
                                                placeholder="Email">


                                            @error('email')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary mr-1">Save</button>
                                            <button type="reset" class="btn btn-light-secondary">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- loading-effect --}}
    <div id="page-loader">
        <div class="spinner"></div>
    </div>

    <div id="loading-overlay" style="display: none;">
        <div class="loading-container">
            <div class="loading-spinner">
                <div class="spinner-circle"></div>
                <span id="loading-percentage">0%</span>
            </div>
            <p> Loading, please wait... </p>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="ledger-show" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="ledger-show-content">

                </div>
            </div>
        </div>
    </div>


    <!-- Requisition Modal -->
    <div class="modal fade" id="requisitionModal" tabindex="-1" role="dialog"
        aria-labelledby="requisitionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requisitionModalLabel">Requisition Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="requisitionModalBody">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('assets/backend') }}/app-assets/vendors/js/vendors.min.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('assets/backend/') }}/app-assets/vendors/js/extensions/toastr.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('assets/backend') }}/app-assets/js/core/app-menu.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/js/core/app.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/js/scripts/components.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/js/scripts/footer.js"></script>
    <!-- END: Theme JS-->

    <script src="{{ asset('assets/backend') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/js/scripts/forms/select/form-select2.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <!-- BEGIN: Page JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    {{-- datatable  --}}
    <script src="{{ asset('assets/backend') }}/app-assets/js/jquery.dataTables.min.js"></script>
    {{-- sweet alert 2  --}}
    <script src="{{ asset('js/plugin/sweetalert2.js') }}"></script>
    <script src="{{ asset('js/table-filter.js') }}"></script>
    @stack('js')
    <script>
        // ------------ latest (old) ------------
        //  function exportToExcel(id) {
        //     var table = document.getElementById(id);
        //     var wb = XLSX.utils.table_to_book(table, {
        //         sheet: id
        //     });
        //     var fileName = "data-list" + (id ? "-" + id : "") + ".xlsx";
        //     XLSX.writeFile(wb, fileName);
        // }
        // --------------------------------------
        function exportToExcel(id) {
            var table = document.getElementById(id);

            if (!table) {
                console.error("Table with id '" + id + "' not found.");
                return;
            }

            // Find hidden rows (may or may not exist)
            var hiddenRows = table.querySelectorAll("tr[style*='display:none']");

            if (hiddenRows.length > 0) {
                // Temporarily show them
                hiddenRows.forEach(row => {
                    row.setAttribute("data-was-hidden", "true");
                    row.style.display = "";
                });
            }

            // Export
            var wb = XLSX.utils.table_to_book(table, { sheet: id });
            var fileName = "data-list" + (id ? "-" + id : "") + ".xlsx";
            XLSX.writeFile(wb, fileName);

            if (hiddenRows.length > 0) {
                // Hide them again
                hiddenRows.forEach(row => {
                    if (row.getAttribute("data-was-hidden") === "true") {
                        row.style.display = "none";
                        row.removeAttribute("data-was-hidden");
                    }
                });
            }
        }


        $(document).on('click', '.req-approve-btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var message = 'Are you sure';
            Swal.fire(alertDesign(message, 'approve'))
                .then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            'type': 'get',
                            success: function(res) {
                                document.getElementById("voucherPreviewShow").innerHTML = res
                                    .preview;
                                $('#voucherPreviewShow').html(res.expense_list);
                                toastr.success('Approved Successfully!');

                            }
                        })
                    }
                });
        })

        $(document).on("click", ".notice-view", function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: "get",
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                }
            });
        });



        $(window).on('load', function() {
            $('#page-loader').fadeOut(500);
        });






        setInterval(function() {
            fetch('/notifications')
                .then(res => res.json())
                .then(data => {
                    let notifList = document.getElementById('notifList');
                    notifList.innerHTML = '';

                    data.notifications.forEach(n => {
                        let li = document.createElement('li');
                        li.innerHTML =
                            `<a class="notice-view" href="/requisition/requisitions/${n.data.requisition_id}">${n.data.message}</a>`;
                        notifList.appendChild(li);
                    });

                    // Update count
                    let notifCount = document.getElementById('notifCount');
                    if (data.unreadCount > notifCount.innerText) {
                        document.getElementById('notifSound').play();
                    }
                    notifCount.innerText = data.unreadCount;
                });
        }, 5000); // check every 5 sec

        function alertDesign(message = null, type) {
            if (!message) {
                message = 'Do you want to delete it';
            }
            var color;
            var icon = 'warning';
            if (type == 'delete') {
                color = '#d33'
            } else {
                color = '#2ecc71'
            }

            let confirm_button = `Yes`;
            let title = 'Confirmation';
            if (type != "delete" && type != "approve") {
                confirm_button = type;
                title = 'Invoice';
                icon = "success";
            }

            if (type == 'Create payment voucher!') {
                title = 'Expense';
            }

            return {
                title: title,
                text: message,
                icon: icon,
                showCancelButton: true,
                confirmButtonText: confirm_button,
                cancelButtonText: 'No',
                confirmButtonColor: color,
                cancelButtonColor: '#3085d6',
            }
        }

        function deleteAlert(el, message = null, type = 'delete') {
            Swal.fire(alertDesign(message, type))
                .then((result) => {
                    if (result.isConfirmed) {
                        const $el = $(el);
                        if ($el.attr('type') == 'submit') {
                            const $form = $el.closest('form');
                            if ($form.length) {
                                $form.submit();
                                return;
                            }
                        }

                        // ✅ Case 2: If it's an anchor <a> with href
                        const href = $el.attr('href');
                        if (href) {
                            window.location.href = href;
                            return;
                        }

                    }
                });
        }

        function queueCall() {
            const interval = setInterval(function() {
                $.ajax({
                    url: '/run-queue',
                    method: 'GET',
                    success: function(res) {
                        //
                    },
                    error: function() {
                        console.log('Error checking notification.');
                    }
                });
            }, 10000);
        }

        // $(document).ready(function() {
        //     queueCall()
        //     checkNotification();
        // });


        function select2_change() {
            $('.common-select2').select2();
        }

        let interval; // To store the interval ID
        let percentage = 0; // Declare percentage globally to reset properly

        $(document).ajaxStart(function() {

            $('button').prop('disabled', true);

            if ($('body').hasClass('no-loader')) return;
            // Clear any previous intervals (safety)
            if (interval) clearInterval(interval);

            // Reset and show loading elements
            percentage = 0;
            $('#loading-percentage').text('0%');
            $('#loading-overlay').fadeIn(200);
            $('#progress-bar').fadeIn(200);

            // Simulate loading percentage
            interval = setInterval(() => {
                percentage += 5;
                if (percentage > 95) percentage = 95;
                $('#loading-percentage').text(percentage + '%');
            }, 100);
        });

        $(document).ajaxStop(function() {

            $('button').prop('disabled', false);

            // Stop loading simulation and reset
            clearInterval(interval);
            $('#loading-percentage').text('100%');

            // Wait a moment before hiding (optional delay)
            setTimeout(() => {
                $('#loading-overlay').fadeOut(300);
                $('#progress-bar').fadeOut(300);
                $('#loading-percentage').text('0%');
            }, 200); // slight delay to show 100%
        });


        $(document).ready(function() {
            $('.modal').each(function() {
                $(this).attr('data-backdrop', 'static');
                $(this).attr('data-keyboard', 'false');
            });

            $('.common-select2').select2();

            $(body).mCustomScrollbar({
                theme: "minimal"
            });

        })

        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}";
            toastr.options = {
                "closeButton": true,
                "tapToDismiss": false,
            };
            switch (type) {
                case 'info':
                    toastr.info("{{ Session::get('message') }}", "Info");
                    break;

                case 'warning':
                    toastr.warning("{{ Session::get('message') }}", "Warning");
                    break;

                case 'success':
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ Session::get('message') }}',
                        confirmButtonColor: '#3085d6'
                    });
                    // toastr.success("{{ Session::get('message') }}", "Success");
                    break;

                case 'error':
                    toastr.error("{{ Session::get('message') }}", "Error");
                    break;
            }
        @endif

        function checkNotification() {
            const interval = setInterval(function() {
                $('body').addClass('no-loader');
                $.ajax({
                    url: '/check/download/notifications',
                    method: 'GET',
                    success: function(res) {
                        if (res) {
                            toastr.success('Your requested file has been ready to downlaod');
                            $('.notification-wrapper').html(res.page);
                            var other_notification = parseInt($('.notification-count').data('other'));
                            $('.notification-count').text(other_notification + res.count);
                            clearInterval(interval);
                        }
                    },
                    error: function() {
                        console.log('Error checking notification.');
                    },
                    complete: function() {
                        $('body').removeClass('no-loader');
                    }
                });
            }, 10000);
        }
    </script>

    <script type="text/javascript">
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
    <!-- END: Page JS-->

    <script>
        function downloadCSV(csv, filename) {
            var csvFile;
            var downloadLink;

            csvFile = new Blob([csv], {
                type: "text/csv"
            });

            downloadLink = document.createElement("a");

            downloadLink.download = filename;

            downloadLink.href = window.URL.createObjectURL(csvFile);

            downloadLink.style.display = "none";
            document.body.appendChild(downloadLink);

            downloadLink.click();
        }

        function exportTableToCSV(filename) {
            var csv = [];
            var rows = document.querySelectorAll("table");

            for (var i = 0; i < rows.length; i++) {
                var row = [],
                    cols = rows[i].querySelectorAll("td, th");

                for (var j = 0; j < cols.length; j++)
                    row.push("\"" + cols[j].innerText + "\"");

                csv.push(row.join(","));
            }

            downloadCSV(csv.join("\n"), filename);
        }

        // *********joman code in here********

        // ********emirates id fromate validation****

        $(document).on("keyup", ".emirates_id_num", function(e) {
            // alert('hello');
            var inputValue = $(this).val();
            var error = $(this).data('error');
            var yyyy = $(this).data('yyyy');
            var button = $(this).data('s-button');
            var dod = $("." + yyyy).val();
            var errorSpan = $("." + error);

            var dateParts = dod.split('/')
            if (dateParts.length === 3) {
                var year = parseInt(dateParts[2], 10);
            }

            var input = inputValue.substring(0, 8);
            var check_value = "784-" + year.toString();
            var check_input = input.toString();

            console.log(check_input + check_value)
            if (check_value === check_input && inputValue.length >= 18 && inputValue.charAt(8) == '-' && inputValue
                .charAt(16) == '-') {
                errorSpan.text("Valid format. EX " + inputValue).css("color", "green").show();
                $("." + button).prop("disabled", false)
            } else {
                errorSpan.text("Invalid format.EX 784-" + year + "-0000000-0").css("color", "red").show();
                $("." + button).prop("disabled", true)
            }
        });
        // text transfare

        $(document).on("change", ".local_address", function(e) {
            e.preventDefault();
            //alert('hello')
            var className = $(this).data('local-to-parmanent');
            var className2 = $(this).data('local-form-parmanent');

            if ($(this).prop("checked")) {
                var text = $("." + className2).val();
                // alert(text);
                var show = $("." + className).val(text);
            } else {
                var show = $("." + className).val('');
            }
        });


        // *********telephone************


        $(document).ready(function() {
            // Define a regular expression pattern for UAE phone numbers
            var uaePhonePattern = /^(\+971|0)(50|52|54|55|56|58|2|3|4|6|7|9)\d{7}$/;

            $(document).on("keyup", ".phoneInput", function(e) {
                // alert('hello')
                var phoneNumber = $(this).val().trim();
                var errorClass = $(this).data('phone-error');
                var button = $(this).data('s-button');
                // Test the input value against the pattern
                if (uaePhonePattern.test(phoneNumber)) {
                    $("." + errorClass).text("Valid format. Ex:" + phoneNumber).css("color", "green")
                        .show();
                    $("." + button).prop("disabled", false)
                } else {
                    $("." + errorClass).text("Invalid format. Ex:+97150000000").css("color", "red").show();
                    $("." + button).prop("disabled", true)

                }
            });

            //     //  *************** passport number validation  ********************
            //     $(document).on("keyup", ".passportNumber", function(e) {
            //         // Get the selected country code
            //         var Country = $(this).data('country');
            //         var selectedCountry = $("." + Country).val().replace(/\s/g, '');
            //         var error = $(this).data('error');
            //         var button = $(this).data('s-button');
            //         // Get the input passport number
            //         var passportNumber = $(this).val().trim();
            //         // alert(selectedCountry)

            //         // Define regular expressions for each supported country
            //         var passportPatterns = {
            //             Bangladesh: /^[A-Z]\d{7}$/, // Bangladesh passport format: One uppercase letter followed by seven digits.
            //             // Sri Lanka: /^[A-Z]{2}\d{6}$/, // Sri Lanka passport format: Two uppercase letters followed by six digits.
            //             Pakistan: /^[A-Z]\d{8}$/, // Pakistan passport format: One uppercase letter followed by eight digits.
            //             Oman: /^[A-Z]{2}\d{7}$/, // Oman passport format: Two uppercase letters followed by seven digits.
            //             sy: /^\d{9}$/, // Syria passport format: Nine digits (simplified for demonstration).
            //             Lebanon: /^[A-Z]{2}\d{6}$/, // Lebanon passport format: Two uppercase letters followed by six digits.
            //             India: /^[A-Z]{1}[0-9]{7}$/, // India passport format: One uppercase letter followed by seven digits.
            //             Nepal: /^[A-Z]{1}[0-9]{7}$/, // Nepal passport format: One uppercase letter followed by seven digits.
            //             ae: /^[A-Z]{1}[0-9]{7}$/, // UAE passport format: One uppercase letter followed by seven digits.
            //             Egypt: /^[A-Z]{2}\d{6}$/, // Egypt passport format: Two uppercase letters followed by six digits.
            //             Macao: /^\d{8}$/, // Morocco passport format: Eight digits (simplified for demonstration).
            //             sd: /^\d{9}$/, // Sudan passport format: Nine digits (simplified for demonstration).
            //             Jordan: /^[A-Z]{1}\d{7}$/, // Jordan passport format: One uppercase letter followed by seven digits.
            //             Afghanistan: /^[A-Z]{1}\d{8}$/, // Afghanistan passport format: One uppercase letter followed by eight digits.
            //             // Add more patterns for other countries as needed
            //          };


            //         // Check if the passport number matches the pattern for the selected country
            //         if (passportPatterns[selectedCountry] && passportPatterns[selectedCountry].test(passportNumber)) {
            //           $("."+ error).text("Valid format  for " + $("." + Country + " option:selected").text()).css("color", "green").show();
            //           $("."+ button).prop("disabled", false)
            //         } else {
            //             $("." + error).text("Invalid format for " + $("." + Country + " option:selected").text()).css("color", "red").show();
            //           $("."+ button).prop("disabled", true)
            //         }
            //       });
        });
        // auto date calculator
        // Input date
    </script>
    <script type="text/javascript">
        $(function() {
            $(".datepicker").datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-1000:+1000",
                dateFormat: "dd/mm/yy",
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
    </script>

    {{-- //******************************************************* --}}
    <script>
        $(document).on("click", ".head-ledger", function(e) {
            // alert(1);
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ route('head-ledger-show') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("ledger-show-content").innerHTML = response;
                    $('#ledger-show').modal('show')
                }
            });
        });


        $(document).on("click", ".master-head-ledger", function(e) {
            // alert(1);
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ route('master-head-ledger') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("ledger-show-content").innerHTML = response;
                    $('#ledger-show').modal('show')
                }
            });
        });

        $("#customerAddNew").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var form = $(this);
            var url = form.attr('action');
            var pi_name = $("#pi_name").val();
            var pi_type = $("#pi_type").val();
            var trn_no = $("#trn_no2").val();
            var address = $("#address2").val();
            var con_person = $("#con_person").val();
            var con_no = $("#con_no").val();
            var phone_no = $("#phone_no").val();
            var email = $("#email").val();
            // alert(mobile);
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    pi_name: pi_name,
                    pi_type: pi_type,
                    trn_no: trn_no,
                    address: address,
                    con_person: con_person,
                    con_no: con_no,
                    phone_no: phone_no,
                    phone_no: phone_no,
                    email: email,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(response) {
                    $(".customer").empty().append(response.page);
                    $("div.customer-select select").val(response.newCustomer.id);
                    $("#trn_no").val(response.newCustomer.trn_no);
                    $("#pi_code").val(response.newCustomer.pi_code);
                    $("#attention").val(response.newCustomer.con_person);
                    $("#customerModal").modal('hide');
                }
            })
        });

        $(document).on("keyup", ".ajax-search", function(e) {
            e.preventDefault();
            // alert('ok');
            var that = $(this);
            var q = e.target.value;
            var url = that.attr("data-url");
            var urls = url + '?q=' + q;
            // var datalist = $("#products");
            // datalist.empty();
            // alert(urls);

            $.ajax({
                url: urls,
                type: 'GET',
                cache: false,
                dataType: 'json',
                success: function(response) {
                    //   alert('ok');
                    // console.log(response);
                    // $(".pagination").remove();
                    $(".user-table-body").empty().append(response.page);
                },
                error: function() {
                    //   alert('no');
                }
            });

        });
    </script>

    <script>
        // ********************************************************** Print  table*****************************************
        async function handlePrintClick(tableId) {
            const tableToPrint = document.getElementById(tableId);
            const currentDate = new Date().toLocaleDateString();
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            document.body.appendChild(iframe);

            if (!tableToPrint) {
                console.error(`Table element with id '${tableId}' not found.`);
                return;
            }

            try {
                // Load stylesheets and other content directly
                const [headerResponse, footerResponse, stylesheetResponse] = await Promise.all([
                    fetch('/get-header').then(response => response.text()),
                    fetch('/get-footer').then(response => response.text()),
                    fetch('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css').then(
                        response => response.text())
                ]);

                iframe.contentDocument.open();
                iframe.contentDocument.write(
                    `<html><head><meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"><title>lavish_perfume_print_report_${currentDate}</title></head><body>`
                );

                // Include styles directly
                iframe.contentDocument.write(
                    `<style>
                @media print {
                    .print-none { display: none !important; }

                    @page {
                        margin: 1cm; /* Set margins as needed */
                    }
                    .table thead th {
                        // background: #000 !important;
                        // color: #000 !important;
                    }

                    .table td {
                        color: #000000b8 !important;
                    }
                    .header {
                        position: fixed;
                        top: 0;
                        left: 0;
                        right: 0;
                    }

                    .footer {
                        position: fixed;
                        bottom: 0;
                        left: 0;
                        right: 0;
                        page-break-before: always;
                        page-break-after: always;
                    }
                    .row-style {
                        border: 1px solid;
                        border-radius: 7px;
                        text-transform: uppercase;
                        padding: 12px;
                    }

                    /* Ensure footer appears on every page */
                    .footer {
                        display: block;
                        position: fixed;
                        bottom: 0;
                        left: 0;
                        right: 0;
                    }

                    body {
                        margin-bottom: 2cm; /* Ensure space for the footer */
                    }
                }
                ${stylesheetResponse}
            </style>`
                );

                iframe.contentDocument.write(headerResponse);
                iframe.contentDocument.write('<div>');
                iframe.contentDocument.write(tableToPrint.outerHTML);
                iframe.contentDocument.write('</div>');

                // Insert footer into every page
                const footer = document.createElement('div');
                footer.innerHTML = footerResponse;
                footer.classList.add('footer');
                iframe.contentDocument.body.appendChild(footer);

                iframe.contentDocument.write('</body></html>');
                iframe.contentDocument.close();

                // Wait for images to load
                const images = iframe.contentDocument.images;
                let imagesLoaded = 0;
                const imagesTotal = images.length;

                const checkImagesLoaded = () => {
                    if (imagesLoaded >= imagesTotal) {
                        iframe.contentWindow.print();
                        document.body.removeChild(iframe);
                    }
                };

                if (imagesTotal === 0) {
                    checkImagesLoaded();
                } else {
                    for (let i = 0; i < imagesTotal; i++) {
                        images[i].onload = () => {
                            imagesLoaded++;
                            checkImagesLoaded();
                        };
                        images[i].onerror = () => {
                            imagesLoaded++;
                            checkImagesLoaded();
                        };
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                document.body.removeChild(iframe);
            }
        }



        // Example usage:
        // handlePrintClick('table1', '/get-header', '/get-footer');

        // ********************************************************** Print  table*****************************************
        $(document).on("click", ".univarsal-print", function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var iframe = $('<iframe>');
            iframe.css({
                position: 'absolute',
                width: '0',
                height: '0',
            });
            $('body').append(iframe);

            fetch(url)
                .then(response => response.text())
                .then(invoiceContent => {
                    var doc = iframe[0].contentWindow.document;
                    doc.open();
                    doc.write(invoiceContent);
                    doc.close();

                    // Wait for all images to load
                    var images = doc.images;
                    var totalImages = images.length;
                    var imagesLoaded = 0;

                    if (totalImages === 0) {
                        printIframe();
                    } else {
                        for (var i = 0; i < totalImages; i++) {
                            images[i].onload = imageLoadHandler;
                            images[i].onerror = imageLoadHandler; // In case an image fails to load
                        }
                    }

                    function imageLoadHandler() {
                        imagesLoaded++;
                        if (imagesLoaded === totalImages) {
                            printIframe();
                        }
                    }

                    function printIframe() {
                        iframe[0].contentWindow.focus();
                        iframe[0].contentWindow.print();
                    }
                })
                .catch(error => {
                    console.error('Error fetching invoice content:', error);
                });
        });

        function journalHeadDetailSearch() {
            var search_query = $('#head-ledger-search-query').val();
            var year = $('#head-ledger-year').val();
            var month = $('#head-ledger-month').val();
            var from_date = $('#head-ledger-from-date').val();
            var to_date = $('#head-ledger-to-date').val();
            var head_id = $('#head-ledger-head-id').val();
            var order_by = $('#head-ledger-order-by').val();
            var column = $('#head-ledger-column').val();
            const currentYear = new Date().getFullYear();

            var title = `Search By ${search_query} ${year}`;
            if (from_date && to_date) {
                title = `Search By ${search_query}  From ${from_date} To ${to_date}`;
            } else if (to_date) {
                title = `Search By ${search_query}  Date ${from_date}  ${to_date}`;
            } else if (from_date) {
                title = `Search By ${search_query}  Date ${from_date}`;
            }

            $('#loading-overlay').show();

            let percentage = 0;
            const interval = setInterval(() => {
                percentage += 5;
                if (percentage > 95) percentage = 95;
                $('#loading-percentage').text(percentage + '%');
            }, 200);

            $.ajax({
                url: "{{ route('journal.head.detail.search') }}",
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    year: year,
                    month: month,
                    from: from_date,
                    to: to_date,
                    search_query: search_query,
                    head_id: head_id,
                    column: column,
                    order_by: order_by,
                },

                success: function(response) {
                    $('#loading-percentage').text('100%');
                    $('#ledger-head-results').html(response);
                    $('#title').text(title);
                },

                error: function(xhr) {
                    $('#data-container').html('<p>Error fetching data.</p>');
                },

                complete: function() {
                    clearInterval(interval);
                    $('#loading-overlay').hide();
                    $('#loading-percentage').text('0%');
                }
            })
        }

        $(document).on('click', '#head-ledger-search', function() {
            journalHeadDetailSearch();
        });
    </script>
    <script>
        async function media_print(tableId) {
            const tableToPrint = document.getElementById(tableId);
            const printWidth = document.getElementById(tableId).offsetWidth;
            const currentDate = new Date().toLocaleDateString();
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            document.body.appendChild(iframe);

            if (!tableToPrint) {
                console.error(`Table element with id '${tableId}' not found.`);
                return;
            }

            try {
                // Load header, footer, and stylesheets
                const [stylesheetResponse] = await Promise.all([

                    fetch('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css').then(
                        response => response.text())
                ]);

                iframe.contentDocument.open();
                iframe.contentDocument.write(
                    `<html><head><meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"><title>print_report_${currentDate}</title></head><body>`
                );

                iframe.contentDocument.write(
                    `<style>
          #${tableId}{
                width: ${printWidth >= 793.7 ? '100%' : '80mm'} !important;
            }
            @media print {

                .print-none { display: none !important; }
                .pagination { display:none !important; }
                .print-show { display: block !important; }
                .print-header-title-arabic{
                     font-size: ${printWidth >= 793.7 ? '60px' : '30px'} !important;
                      font-family:  system-ui;
                      font-weight:900;

                }
                .daily-summery{
                    text-align:center;
                }
                .print-header-title-eng{
                      font-size: ${printWidth >= 793.7 ? '30px' : '12px'} !important;
                      font-family:  system-ui;
                      font-weight:900;
                }
                .print-header-title-loc{
                      font-size: ${printWidth >= 793.7 ? '22px' : '10px'} !important;
                      font-family:  system-ui;
                      font-weight:900;
                }
                .daily-summery-print-style {
                    font-size: ${printWidth >= 793.7 ? '26px' : '13px'} !important;
                    text-align:center;
                }
                @page {
                    margin: .5cm;
                }
                .table thead th {
                    background: #1a233a !important;
                    color: #000000b8 !important;
                    text-transform:uppercase !important;
                }
                .table td {
                    color: #000000b8 !important;
                    font-weight:bold;
                }
                .header {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                }
                .footer {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                }
                .row-style {
                    border: 1px solid;
                    border-radius: 7px;
                    text-transform: uppercase;
                    padding: 12px;
                }
            }
            td span {
                padding: 3px !important;
                background-color: #39da8a00 !important;
            }
            ${stylesheetResponse}

        </style>`
                );

                // Insert the header, table content, and footer
                iframe.contentDocument.write('<div>');
                iframe.contentDocument.write(tableToPrint.outerHTML);
                iframe.contentDocument.write('</div>');
                iframe.contentDocument.write('<div class="footer">');
                iframe.contentDocument.write('</div>');
                iframe.contentDocument.write('</body></html>');
                iframe.contentDocument.close();

                const images = iframe.contentDocument.images;
                let loadedImagesCount = 0;

                function checkAllImagesLoaded() {
                    loadedImagesCount++;
                    if (loadedImagesCount === images.length) {
                        setTimeout(() => {
                            iframe.contentWindow.focus();
                            iframe.contentWindow.print();
                            document.body.removeChild(iframe);
                        }, 500);
                    }
                }

                if (images.length > 0) {
                    for (let i = 0; i < images.length; i++) {
                        if (images[i].complete) {
                            checkAllImagesLoaded();
                        } else {
                            images[i].onload = checkAllImagesLoaded;
                            images[i].onerror = checkAllImagesLoaded; // In case of broken images
                        }
                    }
                } else {
                    setTimeout(() => {
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                        document.body.removeChild(iframe);
                    }, 500);
                }

            } catch (error) {
                console.error('Error:', error);
                document.body.removeChild(iframe);
            }
        }
        // ********************************************************** Print  table*****************************************
    </script>
    <script>
        function function_print() {
            $('.print-layout').addClass('print-hideen');
            window.print();
        }
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click', '.print-invoice', function(e) {
            e.preventDefault();
            var sale_id = $(this).attr('id');
            var iframe = $('<iframe>');
            iframe.css({
                position: 'absolute',
                width: '0',
                height: '0',
            });
            $('body').append(iframe);

            fetch('/get-invoice-print/' + sale_id)
                .then(response => response.text())
                .then(invoiceContent => {
                    var doc = iframe[0].contentWindow.document;
                    doc.open();
                    doc.write(invoiceContent);
                    doc.close();

                    // Wait for all images to load
                    var images = doc.images;
                    var totalImages = images.length;
                    var imagesLoaded = 0;

                    if (totalImages === 0) {
                        printIframe();
                    } else {
                        for (var i = 0; i < totalImages; i++) {
                            images[i].onload = imageLoadHandler;
                            images[i].onerror = imageLoadHandler; // In case an image fails to load
                        }
                    }

                    function imageLoadHandler() {
                        imagesLoaded++;
                        if (imagesLoaded === totalImages) {
                            printIframe();
                        }
                    }

                    function printIframe() {
                        iframe[0].contentWindow.focus();
                        iframe[0].contentWindow.print();
                    }
                })
                .catch(error => {
                    console.error('Error fetching invoice content:', error);
                });

        });

        function printFunction() {
            var sale_id = $('.sale_id').val();
            var iframe = $('<iframe>');
            iframe.css({
                position: 'absolute',
                width: '0',
                height: '0',
            });
            $('body').append(iframe);

            fetch('/get-invoice-print/' + sale_id)
                .then(response => response.text())
                .then(invoiceContent => {
                    var doc = iframe[0].contentWindow.document;
                    doc.open();
                    doc.write(invoiceContent);
                    doc.close();

                    // Wait for all images to load
                    var images = doc.images;
                    var totalImages = images.length;
                    var imagesLoaded = 0;

                    if (totalImages === 0) {
                        printIframe();
                    } else {
                        for (var i = 0; i < totalImages; i++) {
                            images[i].onload = imageLoadHandler;
                            images[i].onerror = imageLoadHandler; // In case an image fails to load
                        }
                    }

                    function imageLoadHandler() {
                        imagesLoaded++;
                        if (imagesLoaded === totalImages) {
                            printIframe();
                        }
                    }

                    function printIframe() {
                        iframe[0].contentWindow.focus();
                        iframe[0].contentWindow.print();
                    }
                })
                .catch(error => {
                    console.error('Error fetching invoice content:', error);
                });
        }
        $(document).on('click', '.auth-print-invoice', function(e) {
            e.preventDefault();
            var sale_id = $(this).attr('id');
            var iframe = $('<iframe>');
            iframe.css({
                position: 'absolute',
                width: '0',
                height: '0',
            });
            $('body').append(iframe);

            fetch('/temp-get-invoice-print/' + sale_id)
                .then(response => response.text())
                .then(invoiceContent => {
                    var doc = iframe[0].contentWindow.document;
                    doc.open();
                    doc.write(invoiceContent);
                    doc.close();

                    // Wait for all images to load
                    var images = doc.images;
                    var totalImages = images.length;
                    var imagesLoaded = 0;

                    if (totalImages === 0) {
                        printIframe();
                    } else {
                        for (var i = 0; i < totalImages; i++) {
                            images[i].onload = imageLoadHandler;
                            images[i].onerror = imageLoadHandler; // In case an image fails to load
                        }
                    }

                    function imageLoadHandler() {
                        imagesLoaded++;
                        if (imagesLoaded === totalImages) {
                            printIframe();
                        }
                    }

                    function printIframe() {
                        iframe[0].contentWindow.focus();
                        iframe[0].contentWindow.print();
                    }
                })
                .catch(error => {
                    console.error('Error fetching invoice content:', error);
                });

        });

        function printFunction() {
            var sale_id = $('.sale_id').val();
            var iframe = $('<iframe>');
            iframe.css({
                position: 'absolute',
                width: '0',
                height: '0',
            });
            $('body').append(iframe);

            fetch('/temp-get-invoice-print/' + sale_id)
                .then(response => response.text())
                .then(invoiceContent => {
                    var doc = iframe[0].contentWindow.document;
                    doc.open();
                    doc.write(invoiceContent);
                    doc.close();

                    // Wait for all images to load
                    var images = doc.images;
                    var totalImages = images.length;
                    var imagesLoaded = 0;

                    if (totalImages === 0) {
                        printIframe();
                    } else {
                        for (var i = 0; i < totalImages; i++) {
                            images[i].onload = imageLoadHandler;
                            images[i].onerror = imageLoadHandler; // In case an image fails to load
                        }
                    }

                    function imageLoadHandler() {
                        imagesLoaded++;
                        if (imagesLoaded === totalImages) {
                            printIframe();
                        }
                    }

                    function printIframe() {
                        iframe[0].contentWindow.focus();
                        iframe[0].contentWindow.print();
                    }
                })
                .catch(error => {
                    console.error('Error fetching invoice content:', error);
                });
        }

        $(document).on('click', '.cancle-modal', function() {
            var url = $(this).data('url');
            $(this).prop('disabled', true);
            Swal.fire(alertDesign("Approval required to post this receipt voucher.", 'approve'))
                .then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    } else {
                        window.location.reload();
                    }
                });
        })

        // $(document).on('show.bs.modal', '.modal', function () {
        //     var zIndex = 1040 + (10 * $('.modal:visible').length);
        //     $(this).css('z-index', zIndex);
        //     setTimeout(function () {
        //         $('.modal-backdrop').not('.modal-stack')
        //             .css('z-index', zIndex - 1)
        //             .addClass('modal-stack');
        //     }, 0);
        // });
    </script>

</body>

</html>

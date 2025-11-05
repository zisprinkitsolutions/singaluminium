@extends('layouts.backend.app')
@php
use Illuminate\Support\Facades\Auth;
$user = Auth::user();
@endphp
@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<style>
    .modal-lg {
        max-width: 90% !important;
    }

    div.dataTables_length {
        margin: 0rem 0 !important;
        float: left !important;
    }

    .modal .data-table.table-responsive {
        max-height: 500px;
        /* adjust height as needed */
        overflow-y: auto;
    }

    /* Common glossy + shadow style */
    .box {
        position: relative;
        transform: translateY(-1px);
        overflow: hidden;
        color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    /* Add glossy effect with gradient overlay */
    .box::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
    }

    /* Slight zoom + shadow on hover */
    .box:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    /* Linner box match style */
    .linner-box {
        color: white;
        font-weight: bold;
        transition: background 0.3s ease;
    }

    .linner-box a {
        color: white !important;
        font-weight: bold;
    }

    .linner-box:hover {
        filter: brightness(1.15);
    }

    /* Unified glossy style for both main box and linner box */


    /* Glossy overlay */
    .box::before,
    .linner-box::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    /* Hover lift effect */
    .box:hover,
    .linner-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    /* Make links inside white & bold */
    .linner-box a {
        color: white !important;
        font-weight: bold;
    }

    /* Slight brightness increase on hover for links */
    .linner-box:hover {
        filter: brightness(1.15);
    }

    .box h3,
    .box p.h5,
    .linner-box a {
        margin: 0;
        font-weight: 900;
    }

    /* Main title */
    .box h3 {
        font-size: 1.5rem;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
    }

    /* Numbers / values */
    .box p.h5,
    .box h1 {
        font-size: 1.5rem;
        font-weight: 700;
    }

    /* Large metric for main numbers */
    .box h1 {
        font-size: 2.2rem;
        line-height: 1.2;
    }

    /* Link text inside linner box */
    .linner-box a {
        font-size: 0.95rem;
        text-transform: capitalize;
        letter-spacing: 0.5px;
        font-style: normal;
        font-size: 12px !important;
        color: white !important;
    }

    /* Slight brightness increase on hover */
    .linner-box:hover {
        filter: brightness(1.15);
    }

    .sub-card {
        color: #fff;
        text-transform: uppercase;

    }

    .h5-number {
        font-weight: 400;
        font-size: 22px !important;
    }

    .number-scroll {
        font-size: 14px;
        font-weight: 500;
        white-space: nowrap;
        /* Prevent line break */
        overflow-x: auto;
        /* Enable horizontal scroll */
        overflow-y: hidden;
        /* Hide vertical overflow */
        -ms-overflow-style: none;
        /* Hide scrollbar in IE & Edge */
        scrollbar-width: none;
        /* Hide scrollbar in Firefox */
    }

    .number-scroll::-webkit-scrollbar {
        display: none;
        /* Hide scrollbar in Chrome, Safari, Opera */
    }

    .dashboard-row {
        row-gap: 12px;
        /* vertical gap for older BS versions; g-* is also used */
    }

    /* A card wrapper that fills column height */
    .home-card {
        display: flex;
        flex-direction: column;
        width: 100%;
        height: 100%;
    }

    /* Make the top colored box expand to fill available height */
    .home-card .box {
        display: flex;
        flex-direction: column;
        justify-content: center;
        /* centers inner content nicely */
        flex: 1 1 auto;
        /* TAKE remaining height */
    }

    /* Keep the bottom strip consistent across cards */
    .home-card .linner-box {
        min-height: 44px;

    }

    /* Optional: tighten sub-card spacing */
    .sub-card .number-scroll {
        font-weight: 600;
        line-height: 1.2;
    }

    /* Avoid squished headings on very small screens */
    .h5-number {
        font-size: 1.15rem;
        margin: 0;
    }
</style>
@endpush
@section('content')
<!-- BEGIN: Content-->
@if(Auth::user()->hasPermission('dashboard'))
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body" id="dashboard">
            <!-- Widgets Statistics start -->
            <section id="widgets-Statistics">
                <div class="row">
                    <div class="col-md-6">
                        {{-- <form action="" method="GET">
                            <div class="row">
                                <div class="col-md-4 pr-0">
                                    <input type="text" class="inputFieldHeight form-control datepicker" name="date_from"
                                        placeholder="From" id="date_from" autocomplete="off"
                                        value="{{date('d/m/Y', strtotime($date_from))}}">
                                </div>
                                <div class="col-md-4 pr-0">
                                    <input type="text" class="inputFieldHeight form-control datepicker" name="date_to"
                                        placeholder="To" id="date_to" autocomplete="off"
                                        value="{{date('d/m/Y', strtotime($date_to))}}">
                                </div>
                                <div class="col-md-4 text-left">
                                    <button type="submit" class="btn mSearchingBotton mb-1 btn-sm formButton"
                                        style="background:#9b9fa3 !important" title="Search">
                                        <div class="d-flex">
                                            <div class="formSaveIcon">
                                                <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                    width="25">
                                            </div>
                                            <div><span>Search</span></div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </form> --}}

                    </div>
                </div>


                <div class="row print-hide g-3 align-items-stretch dashboard-row">
                    <!-- 4 per row on lg, 2 per row on sm, 1 per row on xs -->
                    <!-- Add d-flex on columns so children can stretch equally -->
                    <div class="col-12 col-sm-6 col-lg-3 d-flex">
                        <div class="home-card">
                            <div class="box text-center home-reports" data="project/home-project"
                                style="background: rgb(0, 77, 129); padding:3px;">
                                <h3 class="text-white mb-1 mt-1" style="margin-bottom: 5px;"> Projects</h3>
                                <div class="row justify-content-center mb-1">
                                    <div class="col-6 p-0">
                                        <div class="sub-card">
                                            Total {{$total_projects}}
                                            <div class="number-scroll">
                                                {{ number_format($total_project_value,2)}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 p-0">
                                        <div class="sub-card">
                                            Ongoing {{$total_running_projects}}
                                            <div class="number-scroll">
                                                {{ number_format($total_running_project_value,2)}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="linner-box d-flex align-items-center justify-content-center"
                                style="background: rgb(10, 67, 106);">
                                <a href="#" class="text-white fw-bold me-1">More info</a>
                                <i class="text-primary bx bx-right-arrow-alt"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 d-flex data-details" data-target="accrued_receivable">
                        <div class="home-card">
                            <div class="box p-1 text-center" style="background-color: #ff0000 !important">
                                <div class="d-flex flex-column">
                                    <h3 class="text-white"> Accrued Receivable </h3>
                                    <p class="h5-number" style="color: #fff;">{{ number_format($accrued_receivable,2)}}
                                    </p>
                                </div>
                            </div>
                            <div class="linner-box d-flex align-items-center justify-content-center"
                                style="background-color: #c90000;">
                                <a href="#"> More info </a>
                                <i class="text-primary bx bx-right-arrow-alt"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 d-flex">
                        <div class="home-card">
                            <div class="box p-1 text-center home-reports" data="receipt/home-receivable"
                                style="background: rgb(0, 77, 129); padding:3px;">
                                <h3 class="text-white mb-1 mt-1" style="margin-bottom: 5px;"> Receivable</h3>
                                <div class="row justify-content-center mb-1">
                                    <div class="col-6 p-0">
                                        <div class="sub-card">
                                            Taxable
                                            <div class="number-scroll">
                                                {{ number_format($receivable->net_receivable,2)}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 p-0">
                                        <div class="sub-card">
                                            VAT
                                            <div class="number-scroll">
                                                {{ number_format($receivable->vat_receivable,2)}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="linner-box d-flex align-items-center justify-content-center"
                                style="background: rgb(10, 67, 106);">
                                <a href="#" class="text-white fw-bold me-1">More info</a>
                                <i class="text-primary bx bx-right-arrow-alt"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 d-flex">
                        <div class="home-card">
                            <div class="box box-info p-1 text-center home-reports" data="home-payable"
                                style="background-color: rgb(64, 143, 3) !important">
                                <div class="d-flex flex-column">
                                    <h3 class="text-white">Payable </h3>
                                    <p class="h5-number" style="color: #fff;"> {{ number_format($payble,2) }} </p>
                                </div>
                            </div>
                            <div class="linner-box d-flex align-items-center justify-content-center home-reports"
                                data="home-payable" style="background-color: rgb(54, 117, 7);">
                                <a href="#"> More info </a>
                                <i class="text-info bx bx-right-arrow-alt"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 d-flex">
                        <div class="home-card">
                            <div class="box box-success p-1 text-center home-reports" data="home-expense"
                                style="background-color:rgb(255, 119, 0) !important">
                                <div class="d-flex flex-column">
                                    <h3 class="text-white"> Expenses </h3>
                                    <p class="h5-number" style="color: #fff;">{{
                                        number_format($expenses->sum('total_amount'),2) }}</p>
                                </div>
                            </div>
                            <div class="linner-box d-flex align-items-center justify-content-center"
                                style="background-color: #ff8800;">
                                <a href="#" data="daily-summary" class="home-reports"> More info </a>
                                <i class="text-success bx bx-right-arrow-alt"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 d-flex">
                        <div class="home-card">
                            <div class="box p-1 text-center home-reports" data="sales/sale-list-ajax"
                                style="background-color: #a70090 !important">
                                <div class="d-flex flex-column">
                                    <h3 class="text-white">Sales Revenue </h3>
                                    <p class="h5-number" style="color: #fff;"> {{ number_format($m_sales,2) }} </p>
                                </div>
                            </div>
                            <div class="linner-box d-flex align-items-center justify-content-center"
                                style="background-color: #be05a5 !important;">
                                <a href="#" data="sales/sale-list-ajax" class="home-reports"> More info </a>
                                <i class="bx bx-right-arrow-alt"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 d-flex">
                        <div class="home-card">
                            <div class="box p-1 text-center home-reports" data="recieved-data"
                                style="background-color:#791c00 !important">
                                <div class="d-flex flex-column">
                                    <h3 class="text-white"> Received </h3>
                                    <p class="h5-number" style="color: #fff;">{{
                                        number_format($receipt_list->sum('total_amount'),2) }}
                                    </p>
                                </div>
                            </div>
                            <div class="linner-box d-flex align-items-center justify-content-center home-reports"
                                data="recieved-data" style="background-color: rgb(145, 34, 0);">
                                <a href="#"> More info </a>
                                <i class="bx bx-right-arrow-alt"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 d-flex">
                        <div class="home-card">
                            <div class="box p-1 text-center home-reports" data="sales/retention-list-ajax"
                                style="background-color: #ffb300 !important">
                                <div class="d-flex flex-column">
                                    <h3 class="text-white"> Retention </h3>
                                    <p class="h5-number" style="color: #fff;"> {{
                                        number_format($sales->sum('retention_amount'),2) }}
                                    </p>
                                </div>
                            </div>
                            <div class="linner-box d-flex align-items-center justify-content-center"
                                style="background-color: #ff9e03 !important;">
                                <a href="#" data="sales/retention-list-ajax" class="home-reports"> More info </a>
                                <i class="bx bx-right-arrow-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row print-hide">

                    {{-- executive view --}}
                    @if ($user->role && strtolower($user->role->name) === 'executive')

                    <div class="col-12 col-md-6 px-1 mt-2">
                        <div class="due-fee bg-white px-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="mt-1"> Executive </h2>
                                <div class="d-flex algin-items-center">
                                    {{-- <button class="due-btn bg-info modalopen" data-collection="DUE-STUDENT-WISE">
                                        <i class='bx bxs-analyse'></i> Quick View </button> --}}
                                    <a href="#" class="ml-2 due-btn text-dark"> All <i
                                            class='bx bx-right-arrow-alt'></i> </a>
                                </div>
                            </div>

                            <div class="">
                                <table class="table table-sm">
                                    <thead class="bg-light" style="background-color: #2B569A !important">
                                        <tr class="text-center">
                                            <th style="color:white;">Request ID</th>
                                            <th style="color:white;">Request Type</th>
                                            <th style="color:white;">Status</th>
                                            <th style="color:white;">Remarks</th>
                                            <th style="color:white;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 12px !important;">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 px-1 mt-2">
                        <div class="due-fee bg-white px-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="mt-1"> APPROVER STATUS </h2>
                                <div class="d-flex algin-items-center">
                                    {{-- <button class="due-btn bg-info modalopen" data-collection="DUE-STUDENT-WISE">
                                        <i class='bx bxs-analyse'></i> Quick View </button> --}}
                                    <a href="#" class="ml-2 due-btn text-dark"> All <i
                                            class='bx bx-right-arrow-alt'></i> </a>
                                </div>
                            </div>

                            <div class="">
                                <table class="table table-sm">
                                    <thead class="bg-light" style="background-color: #2B569A !important">
                                        <tr class="text-center">
                                            <th style="color:white;">Request ID</th>
                                            <th style="color:white;">Request Type</th>
                                            <th style="color:white; text-align:left;">Status</th>
                                            <th style="color:white; text-align:left;">Remarks</th>
                                            <th style="color:white;">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody style="font-size: 12px !important;">

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    {{-- executive view end --}}

                    {{-- Authorizer and Approver view --}}
                    @if ($user->role && in_array(strtolower($user->role->name), ['authorizer', 'approver']))

                    <div class="col-12 col-md-12 px-1 mt-2">
                        <div class="due-fee bg-white px-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="mt-1"> PENDINGS</h2>
                                <div class="d-flex algin-items-center">
                                    {{-- <button class="due-btn bg-info modalopen" data-collection="DUE-STUDENT-WISE">
                                        <i class='bx bxs-analyse'></i> Quick View </button> --}}
                                    <a href="#" class="ml-2 due-btn text-dark"> All <i
                                            class='bx bx-right-arrow-alt'></i> </a>
                                </div>
                            </div>

                            <div class="">
                                <table class="table table-sm">
                                    <thead class="bg-light" style="background-color: #2B569A !important">
                                        <tr class="text-center">
                                            <th style="color:white;">Request ID</th>
                                            <th style="color:white;">Created By</th>
                                            <th style="color:white;">Full Name</th>
                                            <th style="color:white;">Employee ID</th>
                                            <th style="color:white;">Department</th>
                                            <th style="color:white;">Request Type</th>
                                            <th style="color:white;">Status</th>
                                            <th style="color:white;">Remarks</th>
                                            <th style="color:white;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 12px !important;">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-12 px-1 mt-2">
                        <div class="due-fee bg-white px-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="mt-1"> AWAITING FOR APPROVAL </h2>
                                <div class="d-flex algin-items-center">
                                    {{-- <button class="due-btn bg-info modalopen" data-collection="DUE-STUDENT-WISE">
                                        <i class='bx bxs-analyse'></i> Quick View </button> --}}
                                    <a href="#" class="ml-2 due-btn text-dark"> All <i
                                            class='bx bx-right-arrow-alt'></i> </a>
                                </div>
                            </div>

                            <div class="">
                                <table class="table table-sm">
                                    <thead class="bg-light" style="background-color: #2B569A !important">
                                        <tr class="text-center">
                                            <th style="color:white;">Request ID</th>
                                            <th style="color:white;">Created By</th>
                                            <th style="color:white;">Full Name</th>
                                            <th style="color:white;">Employee ID</th>
                                            <th style="color:white;">Department</th>
                                            <th style="color:white;">Request Type</th>
                                            <th style="color:white; text-align:left;">Status</th>
                                            <th style="color:white; text-align:left;">Remarks</th>
                                            <th style="color:white;">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody style="font-size: 12px !important;">

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    {{-- Authorizer and Approver view end --}}
                    {{-- my activity view --}}
                    <div class="col-12 col-md-12 px-1 mt-2">
                        <div class="due-fee bg-white px-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="mt-1"> MY ACTIVITY </h2>
                                <div class="d-flex algin-items-center">
                                    {{-- <button class="due-btn bg-info modalopen" data-collection="DUE-STUDENT-WISE">
                                        <i class='bx bxs-analyse'></i> Quick View </button> --}}
                                    <a href="#" class="ml-2 due-btn text-dark"> All <i
                                            class='bx bx-right-arrow-alt'></i> </a>
                                </div>
                            </div>

                            <div class="">
                                <table class="table table-sm">
                                    <thead class="bg-light" style="background-color: #2B569A !important">
                                        <tr class="text-center">
                                            <th style="color:white;">Request ID</th>
                                            <th style="color:white;">Created By</th>
                                            <th style="color:white;">Full Name</th>
                                            <th style="color:white;">Employee ID</th>
                                            <th style="color:white;">Department</th>
                                            <th style="color:white;">Request Type</th>
                                            <th style="color:white; text-align:left;">Status</th>
                                            <th style="color:white; text-align:left;">Remarks</th>
                                            <th style="color:white;">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody style="font-size: 12px !important;">

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- my activity view end --}}
                </div>
            </section>
            <!-- Widgets Statistics End -->
        </div>
    </div>
</div>
@endif

{{-- Modal --}}
<div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="voucherPreviewShow">
                <section class="print-hideen border-bottom" style="background: #364a60;">
                    <div class="d-flex flex-row-reverse">

                        <div class="pr-1" style="padding-top: 5px;padding-right: 24px !important;">
                            <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal"
                                aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a>
                        </div>

                        <div class="pr-1 w-100 pl-2">
                            <h4 style="font-family:Cambria;font-size: 2rem;color:white;"> Daily Report </h4>
                        </div>
                    </div>
                </section>

                <section id="widgets-Statistics" style="padding: 15px 22px;">
                    <form action="{{route('engineer.reports.store')}}" class="daily-report-form" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for=""> Date </label>
                                    <input type="text" name="date" value="{{date('d/m/Y')}}"
                                        class="form-control datepicker date" data-pre=".null" data-next=".project_id">
                                </div>
                            </div>

                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for=""> Project </label>
                                    <select name="project_id" id="project_id"
                                        class="project_id form-control common-select2" data-pre=".date"
                                        data-next=".task_id">
                                        <option value=""> Select... </option>
                                        @foreach ($projects as $project)
                                        <option value="{{$project->id}}"> {{$project->project_name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for=""> Task </label>
                                    <select name="task_id" class="form-control common-select2 task_id"
                                        data-pre=".project_id" data-next=".item_id">

                                    </select>
                                </div>
                            </div>

                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for=""> Item </label>
                                    <select name="item_id" class="form-control common-select2 item_id"
                                        data-pre=".task_id" data-next=".work_details">

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="table-responseive">
                            <table class="table table-bordered table-sm daily-report-input-table">
                                <thead>
                                    <th> Work Details </th>
                                    <th> Progress </th>
                                    <th> image </th>
                                    <th> Action </th>
                                </thead>
                                <tbody class="daily-report-input-body">
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control inputFieldHeight work_details"
                                                placeholder="Work Details" name="work_details[0]" data-pre=".item_id"
                                                data-next=".work_progress" required>
                                        </td>

                                        <td>
                                            <input type="text" class="form-control inputFieldHeight"
                                                placeholder="Work Progress" name="progress[0]" required
                                                data-pre=".work_details" data-next=".image">
                                        </td>

                                        <td>
                                            <input type="file" class="form-control inputFieldHeight image"
                                                placeholder="image" name="image[0]" required data-pre=".image"
                                                data-next="save" multiple>
                                        </td>

                                        <td>
                                            <button type="button" class="add-new-row btn btn-primary inputFieldHeight"
                                                style="padding:4px 7px !important;" data-index="0"> <i
                                                    class='bx bx-message-alt-add'></i> </button>
                                            <button type="button" class="start-camera btn btn-primary inputFieldHeight"
                                                style="padding:4px 7px !important;" data-index="0"> <i
                                                    class='bx bxs-camera-plus'></i> </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="my-1">
                            <button class="btn btn-primary inputFieldHeight"> Save </button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="1voucherPreviewModal" tabindex="-1" rrole="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="1voucherPreviewShow">

            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="receivablePreviewModal" tabindex="-1" rrole="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="receivable_modal_content">

            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="paymnetvoucherPreviewModal" tabindex="-1" rrole="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="payable_modal_content">

            </div>
        </div>
    </div>
</div>
{{-- ongoing project Modal --}}
<div class="modal fade bd-example-modal-lg" id="onging_project" tabindex="-1" rrole="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="voucherPreviewShow">
                <section class="print-hideen border-bottom" style="background: #364a60;">
                    <div class="d-flex flex-row-reverse">

                        <div class="pr-1" style="padding-top: 5px;padding-right: 24px !important;">
                            <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal"
                                aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a>
                        </div>

                        <div class="pr-1 w-100 pl-2">
                            <h4 style="font-family:Cambria;font-size: 2rem;color:white;"> Runing Projects </h4>
                        </div>
                    </div>
                </section>
                <section id="widgets-Statistics" style="padding: 15px 22px;">
                    <div class="data-table table-responsive ">
                        <table id="projectsTable" class="table table-sm table-bordered table-striped"
                            style="margin:0px; width:100%; color:white;">
                            <thead style="background-color:#2B569A !important; margin:0px; width:100%; color:white;">
                                <tr class="text-center">
                                    <th style="color:#fff;">SI NO</th>
                                    <th style="color:#fff;">Company</th>
                                    <th style="color:#fff;">Project</th>
                                    <th style="color:#fff;">Code</th>
                                    <th style="color:#fff;">Customer</th>
                                    <th style="color:#fff;">Amount ({{ $currency->symbole }})</th>
                                    <th style="color:#fff;">Start Date</th>
                                    <th style="color:#fff;">End Date</th>
                                    <th style="color:#fff;">Progress</th>
                                    <th style="color:#fff;">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
<!-- ongoing project show Modal -->
<div class="modal fade" id="project-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header print-hideen" style="padding: 5px 15px;background:#364a60;">
                <h5 class="modal-title" id="exampleModalLabel"
                    style="font-family:Cambria;font-size: 2rem;color:#fff;padding-left: 12px;"> </h5>
                <div class="d-flex align-items-center">
                    <a href="" class="project-btn bg-success print-job-project" target="_blank" title="Print"
                        style="margin-right: 0.2rem !important;">
                        <i class="bx bx-printer text-white" style="padding-top:4px;"></i>
                    </a>
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
<!-- ongoing project show Modal -->

<div id="camera-box"
    style="position: fixed; z-index: 9999; background: white; padding: 10px; top: 20px; right: 20px; border: 1px solid #ccc; display:none">
    <video id="video" width="320" height="240" autoplay></video><br>
    <button class="start-camera btn-primary inputFieldHeight">Start Camera</button>
    <button class="take-photo btn-primary inputFieldHeight">Take Photo</button>
    <button class="cancel-camera btn-primary inputFieldHeight">Cancel</button>

    <canvas id="canvas" width="320" height="240" style="display:none;"></canvas><br>
    <div class="image-body" style="display: none">
        <img id="photo" alt="Captured Image" width="320" height="240" /> <br>
        <button class="save-photo btn-primary inputFieldHeight" style="margin-top:8px; display-none">Save Image</button>
    </div>

</div>


{{-- modal --}}

<div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="voucherPreviewShow">

            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
{{-- <script src="{{ asset('assets/backend/app-assets/vendors/js/jquery/jquery.min.js') }}"></script> --}}
<script>
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '.data-details', function(){
            var target = $(this).data('target');
            var date_from = $('.date_from_data-details').val();
            var date_to = $('.date_to_data-details').val();

            $.ajax({
                url: "{{ route('dashboard-ajax') }}",
                type: "get",
                data: {
                    target: target,
                    date_from : date_from,
                    date_to : date_to,
                },
                success: function(res) {
                    if (res) {
                        document.getElementById("voucherPreviewShow").innerHTML = res;
                        $('#voucherPreviewModal').modal('show')
                    }
                    $(".datepicker").datepicker({
                        dateFormat: "dd/mm/yy"
                    });
                },
                error: function(err) {
                    let error = err.responseJSON;
                    $.each(error.errors, function(index, value) {
                        toastr.error(value);
                    })
                }
            });
        })
        // *********************DASDHBOARD DATA SHOW FUNCTION ***************************
        let currentIndex = 0;

        function addNewRow(row) {
            // Get index from button inside the row
            let currentBtn = row.find('.add-new-row');
            currentIndex = parseInt(currentBtn.data('index')) + 1;

            let newRow = `
                <tr>
                    <td>
                        <input type="text" class="form-control inputFieldHeight work_details" placeholder="Work Details" name="work_details[${currentIndex}]" required>
                    </td>
                    <td>
                        <input type="text" class="form-control inputFieldHeight" placeholder="Work Progress" name="progress[${currentIndex}]" required>
                    </td>
                    <td>
                        <input type="file" class="form-control inputFieldHeight image" placeholder="image" name="image[${currentIndex}]" required>
                    </td>
                    <td>
                        <button type="button" class="add-new-row btn btn-primary inputFieldHeight" style="padding:4px 7px !important;" data-index="${currentIndex}">
                            <i class='bx bx-message-alt-add'></i>
                        </button>
                        <button type="button" class="start-camera btn btn-primary inputFieldHeight" style="padding:4px 7px !important;" data-index="${currentIndex}">
                            <i class='bx bxs-camera-plus'></i>
                        </button>
                    </td>
                </tr>
            `;

            $('.daily-report-input-body').append(newRow);
        }

        // ********************* runing project code here ******************
        $(document).on('click', '.runing_project', function (e) {
            e.preventDefault();
            $('#onging_project').modal('show');

        });

        $(document).on('click', '.view-project', function(e) {
            e.preventDefault();
            let project_id = $(this).attr('data-id');
            let url = $(this).attr('data-url');
            let lpo_print_url = "{{ route('job-project-print',":id") }}";
            lpo_print_url = lpo_print_url.replace(':id',project_id);
            $('.print-job-project').attr('href',lpo_print_url);
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
            })
        });

       // ********************* runing project code end ******************

        $(document).on('click', '.add-new-row', function () {
            var row = $(this).closest('tr');
            addNewRow(row);
        });

        let videoStream;
        $(document).on('click', '.start-camera', function () {
            currentIndex = $(this).data('index'); // store row index

            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(function (stream) {
                        videoStream = stream;
                        $('#video')[0].srcObject = stream;
                        $('#camera-box').show();
                    })
                    .catch(function (error) {
                        toastr.error("Camera access denied or not available: " + error);
                    });
            } else {
                toastr.error("Camera not supported by your browser.");
            }
        });

        $(document).on('click', '.take-photo', function () {
            const video = $('#video')[0];
            const canvas = $('#canvas')[0];
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = canvas.toDataURL('image/png');
            $('#photo').attr('src', imageData);
            $('.save-photo').data('image', imageData).show();
            $('.image-body').show();
        });

        $(document).on('click', '.cancel-camera', function () {
            stopCamera();
            resetCameraBox();
        });

        $(document).on('click', '.save-photo', function () {
            const imageData = $(this).data('image');
            if (!imageData || currentIndex === null) {
                alert('No image to save');
                return;
            }

            // Find the row by currentIndex
            const $row = $(`.daily-report-input-body tr`).eq(currentIndex);

            // Remove existing hidden input if present
            $row.find('.captured-image-input').remove();

            // Create new hidden input
            const hiddenInput = $('<input>', {
                type: 'hidden',
                name: `captured_image[${currentIndex}]`,
                class: 'captured-image-input',
                value: imageData
            });

            $row.append(hiddenInput);

            // Optional feedback
            toastr.success('Image saved to row ' + currentIndex);

            // Cleanup
            $('#photo').attr('src', '');
            $('.save-photo').hide().removeData('image');
            $('.image-body').hide();
        });

        // Helpers
        function stopCamera() {
            if (videoStream) {
                let tracks = videoStream.getTracks();
                tracks.forEach(track => track.stop());
                $('#video')[0].srcObject = null;
                videoStream = null;
            }
        }

        function resetCameraBox() {
            $('#camera-box').hide();
            $('#photo').attr('src', '');
            $('.save-photo').hide().removeData('image');
        }

        $(document).on('click', '.header-btn', function() {
            $('.subhead').toggleClass('d-none');
        });

        $(document).on('click', '.sub-header-btn', function() {
            let id = $(this).attr('data-id');
            $('.child-header-' + id).toggleClass('d-none');
        });

        $(document).on('click', '.child-btn', function() {
            let id = $(this).attr('data-id');
            $('.child-subheader-' + id).toggleClass('d-none');
        })

        $(document).on('click', '.sale_date_search', function() {
            let date = $('#date').val();
            let info = $("#infodata").text();
            var csrf_token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ route('dashboard-ajax') }}",
                type: "post",
                cache: false,
                data: {
                    id: info,
                    date: date,
                    _token: csrf_token
                },
                success: function(res) {
                    if (res) {
                        $('#studentProfileAdd').modal('show');
                        $("#data-ajax-containt").empty().append(res.page);
                        $("#infodata").text(res.info);
                        $(".datepicker").datepicker({
                            dateFormat: "dd/mm/yy"
                        });

                    }
                },
                error: function(err) {
                    let error = err.responseJSON;
                    $.each(error.errors, function(index, value) {
                        toastr.error(value);
                    })
                }
            });
        })

        $(document).on("change", ".project_id", function(e) {
            var project = $(this).val();
            var next = $(this).data('next');

            $('body').addClass('no-loader');

            if(project){
                $.ajax({
                    url: "{{ route('find-project-task') }}",
                    type: "post",
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        project: project,
                    },
                    success: function(response) {
                        $(".task_id").html(response);
                         if (next) {
                            setTimeout(() => {
                                $(next).focus();
                            }, 200);
                        }
                    }
                });
            }
        });

        $(document).on("change", ".task_id", function(e) {
            $('body').addClass('no-loader');
            var task_id = $(this).val();
            if(task_id){
                $.ajax({
                    url: "{{ route('find-project-task-item') }}",
                    type: "post",
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        task_id: task_id,
                    },
                    success: function(response) {
                        $(".item_id").empty().append(response);
                    }
                });
            }
        });

        $(document).on('keydown', 'input, select, textarea', function (e) {
            const form = $('.daily-report-form');

            // ✅ Shift + Enter → go to previous field
            if (e.key === 'Enter' && e.shiftKey) {
                const prevSelector = $(this).data('pre');
                if (prevSelector) {
                    e.preventDefault();
                    $(prevSelector).first().focus();
                }
            }

            // ✅ Enter → go to next field
            else if (e.key === 'Enter' && !e.ctrlKey && !e.altKey) {
                const nextSelector = $(this).data('next');
                if (nextSelector) {
                    e.preventDefault();

                    if (nextSelector === 'save') {
                        form.find('button[type="submit"]').focus(); // or form.submit()
                    } else {
                        const next = $(nextSelector).first();
                        if (next.length) {
                            next.focus();

                            // If next is select2, open it
                            if (next.hasClass('common-select2')) {
                                next.select2('open');
                            }
                        }
                    }
                }
            }

            // ✅ Ctrl + S → Save
            else if (e.ctrlKey && e.key.toLowerCase() === 's') {
                e.preventDefault();
                if (form) {
                    form.requestSubmit();
                }
            }

            // ✅ Alt + → Go to next tab
            else if (e.altKey && e.key === 'ArrowRight') {
                e.preventDefault();
                const nextTabId = activeTab.data('next');
                const focusSelector = activeTab.data('focus1');

                if (nextTabId) {
                    $(nextTabId).click();
                    setTimeout(() => {
                        $(focusSelector).focus();
                    }, 200);
                }
            }

            // ✅ Alt + ← Go to previous tab
            else if (e.altKey && e.key === 'ArrowLeft') {
                e.preventDefault();
                const prevTabId = activeTab.data('pre');
                const focusSelector = activeTab.data('focus');

                if (prevTabId) {
                    $(prevTabId).click();
                    setTimeout(() => {
                        $(focusSelector).focus();
                    }, 200);
                }
            }
        });

        $(document).on('click', '.detail-button', function() {
            alert(1);
            const url = $(this).data('url');
            if (url) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#voucherPreviewShow').html(response);
                        $('#voucherPreviewModal').modal('show');
                    },
                    error: function(xhr) {
                        toastr.error('Failed to load report details.');
                    }
                });
            }
        });
        $(document).on("click", ".home-reports", function(e) {
            e.preventDefault();
            var url= $(this).attr('data');
            var date_to = $('#date_to').val();
            var date_from = $('#date_from').val();
            $.ajax({
                url: url,
                type: "get",
                cache: false,
                data:{
                    date_to: date_to,
                    date_from: date_from,
                    _token:'{{ csrf_token() }}',
                },
                success: function(response){
                    document.getElementById("1voucherPreviewShow").innerHTML = response;
                    $('#1voucherPreviewModal').modal('show');
                    $(".datepicker").datepicker({
                        dateFormat: "dd/mm/yy"
                    });
                }
            });
        });
        // Listen for click on pagination links


        // When month is selected
        $(document).on('change','#month', function () {
            if ($(this).val()) {
                 $('#date_from, #date_to, #year').val('');
            }
        });

        // When year is entered
        $(document).on('change','#year', function () {
            if ($(this).val()) {
                $('#date_from, #date_to, #month').val('');
            }
        });

        // When date_from or date_to is entered
        $(document).on('change','#date_from, #date_to', function () {
            if ($('#date_from').val() || $('#date_to').val()) {
                $('#month, #year').val('');
            }
        });

        $(document).on("click", ".pagination a", function(e){
            e.preventDefault();
            var url = $(this).attr('href'); // the URL like ?page=2

            var dataTable = $(this).closest("div").find("table[data-table]").attr("data-table");
            $.ajax({
                url: url,
                type: "GET",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response){

                    if(dataTable === 'accrued_receivable'){
                        document.getElementById("voucherPreviewShow").innerHTML = response;
                        $('#voucherPreviewModal').modal('show')
                    }else{
                        $('#1voucherPreviewShow').empty().html(response);
                        $('#1voucherPreviewModal').modal('show');
                    }

                    $(".datepicker").datepicker({ dateFormat: "dd/mm/yy" });

                }
            });
        });

        $(document).on("click", ".search_home_reports_btn", function(e) {
            e.preventDefault();
            var $form = $(this).closest('.search_home_reports');
            var retention = $(this).data('retention') ?? null;
            var url= $form.attr('data');
            var date_to = $form.find('input[name="date_to"]').val();
            var year = $form.find('input[name="year"]').val();
            var date_from = $form.find('input[name="date_from"]').val();
            var month = $form.find('input[name="month"]').val();
            var search = $form.find('input[name="search"]').length ? $form.find('input[name="search"]').val() : '';
            var company_id = $form.find('select[name="company_id"]').length ? $form.find('select[name="company_id"]').val() : '';
            $.ajax({
                url: url,
                type: "get",
                cache: false,
                data:{
                    date_to: date_to,
                    date_from: date_from,
                    search: search,
                    month:month,
                    year:year,
                    retention:retention,
                    company_id: company_id,
                    _token:'{{ csrf_token() }}',
                },
                success: function(response){
                    document.getElementById("1voucherPreviewShow").innerHTML = response;
                    $('#1voucherPreviewModal').modal('show');
                    $(".datepicker").datepicker({
                        dateFormat: "dd/mm/yy"
                    });
                }
            });
        });
        $(document).on("click", ".receivable-view", function(e) {
            e.preventDefault();
            var id= $(this).attr('id');
            $.ajax({
                url: "{{route('receivable-view')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response){
                    document.getElementById("receivable_modal_content").innerHTML = response;
                    $('#receivablePreviewModal').modal('show')
                    $(".datepicker").datepicker({
                    dateFormat: "dd/mm/yy"
                });
                }
            });
        });
        // HOME PAYABLE
        $(document).on("click", ".payable-view", function(e) {
            e.preventDefault();
            var id= $(this).attr('id');
            $.ajax({
                url: "{{route('payable-view')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response){
                    document.getElementById("payable_modal_content").innerHTML = response;
                    $('#paymnetvoucherPreviewModal').modal('show')
                    $(".datepicker").datepicker({
                    dateFormat: "dd/mm/yy"
                });
                }
            });
        });

        // HOME RECEIVALE
        $(document).on('click', '.toggle-invoices', function () {
            let customer_id = $(this).data('id');
            let row = $("#invoice-row-" + customer_id);
            // Toggle visibility
             row.toggle();
            if (row.is(':visible')) {
                $.ajax({
                url: "{{ route('invoices.by.project') }}",
                type: "GET",
                data: { customer_id: customer_id },
                success: function (res) {
                let totalAmount = 0;
                let totalDue = 0;
                let totalPaid = 0;
                let totalRetention = 0;

                let html = `<table class="table table-sm table-striped">
                    <tr>
                        <td colspan="6" style="text-align:left; font-weight:bold; font-size:14px; background-color:#e9ecef;">
                            &#128179; All Invoices
                        </td>
                    </tr>
                    <tr>
                        <th>Invoice No</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Received Amount</th>
                        <th>Receivable</th>
                        <th>Retention</th>
                    </tr>`;

                    $.each(res, function (i, invoice) {
                    html += `<tr>
                        <td>${invoice.invoice_no}</td>
                        <td>${invoice.date}</td>
                        <td>${invoice.total_budget}</td>
                        <td>${invoice.paid_amount}</td>
                        <td>${invoice.due_amount}</td>
                        <td>${invoice.retention_amount}</td>
                    </tr>`;

                    // Add to totals
                    totalAmount += parseFloat(invoice.total_budget) || 0;
                    totalDue += parseFloat(invoice.due_amount) || 0;
                    totalPaid += parseFloat(invoice.paid_amount) || 0;
                    totalRetention += parseFloat(invoice.retention_amount) || 0;
                    });

                    // Add total row
                    html += `<tr style="font-weight:bold; background-color: #6599eb !important; color: #fff;">
                        <td colspan="2">Total</td>
                        <td>${totalAmount.toFixed(2)}</td>
                        <td>${totalDue.toFixed(2)}</td>
                        <td>${totalPaid.toFixed(2)}</td>
                        <td>${totalRetention.toFixed(2)}</td>
                    </tr>`;

                    html += `
                </table>`; // ✅ use backticks

                row.find(".invoice-container-" + customer_id).html(html);
                }
                });
            }

        });
        // HOME PAYVABLE
        $(document).on('click', '.toggle-expense', function () {
            let party_id = $(this).data('id');
            let row = $("#expense-row-" + party_id);
            // Toggle visibility
            row.toggle();
            if (row.is(':visible')) {
                $.ajax({
                    url: "{{ route('expense-by-party') }}",
                    type: "GET",
                    data: { party_id: party_id },
                    success: function (res) {
                    let totalAmount = 0;
                    let totalDue = 0;
                    let totalPaid = 0;

                    let html = `<table class="table table-sm table-striped">
                        <tr>
                            <td colspan="6" style="text-align:left; font-weight:bold; font-size:14px; background-color:#e9ecef;">
                                &#128179; All Expenses
                            </td>
                        </tr>
                        <tr>
                            <th>Expense No</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Due</th>
                            <th>Paid</th>
                        </tr>`;

                        $.each(res, function (i, expense) {
                        html += `<tr>
                            <td>${expense.purchase_no}</td>
                            <td>${expense.date}</td>
                            <td>${expense.total_amount}</td>
                            <td>${expense.due_amount}</td>
                            <td>${expense.paid_amount}</td>
                        </tr>`;

                        // Add to totals
                        totalAmount += parseFloat(expense.total_amount) || 0;
                        totalDue += parseFloat(expense.due_amount) || 0;
                        totalPaid += parseFloat(expense.paid_amount) || 0;
                        });

                        // Add total row
                        html += `<tr style="font-weight:bold; background-color: #6599eb !important; color: #fff;">
                            <td colspan="2">Total</td>
                            <td>${totalAmount.toFixed(2)}</td>
                            <td>${totalDue.toFixed(2)}</td>
                            <td>${totalPaid.toFixed(2)}</td>
                        </tr>`;

                        html += `
                    </table>`; // ✅ use backticks

                    row.find(".expense-container-" + party_id).html(html);
                    }
                });
            }

        });

        // HOME EXPENSE VIEW
        $(document).on('click', '.toggle-expense-view', function () {
            let id = $(this).attr('id');
            let row = $("#expense-view-row-" + id);
            row.toggle();

            if (row.is(':visible') ) {
                $.ajax({
                    url: "{{ route('expense-home-view') }}",
                    type: "GET",
                    data: { id: id },
                    success: function (res) {
                        let exp = res; // your main expense
                        let items = exp.items; // expense items

                        let html = `
                        <div class="p-2">
                            <h6 class="text-left">💰 Expense Details</h6>
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <td><strong>Payee:</strong> ${exp.party?.pi_name ?? '-'}</td>
                                    <td><strong>Bill No:</strong> ${exp.purchase_no}</td>
                                </tr>
                                <tr>
                                    <td><strong>Address:</strong> ${exp.party?.address ?? '-'}</td>
                                    <td><strong>Invoice No:</strong> ${exp.invoice_no}</td>
                                </tr>
                                <tr>
                                    <td><strong>Contact:</strong> ${exp.party?.con_no ?? '-'}</td>
                                    <td><strong>Date:</strong> ${exp.date}</td>
                                </tr>
                            </table>
                            <h6 class="text-left">💰 Expense Items</h6>
                            <table class="table table-sm table-striped table-hover table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Account Head</th>
                                        <th>Item</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-end">VAT</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                                    let totalAmount = 0, totalVat = 0, totalFinal = 0;
                                    $.each(items, function (i, item) {
                                    html += `
                                    <tr>
                                        <td>${item.head_sub?.name ?? item.head?.fld_ac_head ?? ''}</td>
                                        <td>${item.item_description ?? '-'}</td>
                                        <td class="text-center">${item.qty}</td>
                                        <td class="text-center">${item.unit?.name ?? ''}</td>
                                        <td class="text-end">${parseFloat(item.amount).toFixed(2)}</td>
                                        <td class="text-end">${parseFloat(item.vat).toFixed(2)}</td>
                                        <td class="text-end">${parseFloat(item.total_amount).toFixed(2)}</td>
                                    </tr>`;

                                    totalAmount += parseFloat(item.amount) || 0;
                                    totalVat += parseFloat(item.vat) || 0;
                                    totalFinal += parseFloat(item.total_amount) || 0;
                                    });

                                    html += `
                                    <tr class="fw-bold bg-light" style="background-color: #6599eb !important; color: #fff;">
                                        <td colspan="4" class="text-end">Sub Total</td>
                                        <td class="text-end">${totalAmount.toFixed(2)}</td>
                                        <td class="text-end">${totalVat.toFixed(2)}</td>
                                        <td class="text-end">${totalFinal.toFixed(2)}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="7"><strong>Narration:</strong> ${exp.narration ?? 'N/A'}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>`;

                        row.find(".expense-view-container-" + id).html(html);
                    },
                    error: function () {
                        row.find(".expense-view-container-" + id).html(
                        `<div class="alert alert-danger">⚠️ Failed to load expense details.</div>`
                        );
                    }
                });
            }
        });

        // HOME SALE VIEW
        $(document).on('click', '.home-sale-view', function () {
            let id = $(this).attr('id');
            let row = $("#home-sale-view-row-" + id);
            row.toggle();

            if (row.is(':visible') ) {
                $.ajax({
                    url: "{{ route('home-sale-view') }}",
                    type: "GET",
                    data: { id: id },
                    success: function (res) {
                        let sale = res; // your main saleense
                        let items = sale.tasks; // saleense items

                        let html = `
                        <div class="p-2">
                            <h6 class="text-left">💰 Sale Details</h6>
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <td><strong>Payee:</strong> ${sale.party?.pi_name ?? '-'}</td>
                                    <td><strong>Invoice No:</strong> ${sale.invoice_no}</td>
                                </tr>



                                <tr>
                                    <td><strong>Address:</strong> ${sale.party?.address ?? '-'}</td>
                                    <td><strong>Date:</strong> ${sale.date}</td>

                                </tr>
                            </table>
                            <h6 class="text-left">💰 Sale Items</h6>

                            <table class="table table-sm table-striped table-hover table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-left">Description / Details of the Supply / Quantity</th>
                                        <th class="text-center">Unit Price</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Taxable Amount</th>
                                        <th class="text-center">VAT</th>
                                        <th class="text-center">Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    `;

                                    let totalNet = 0, totalVat = 0, totalGross = 0;

                                    $.each(sale.tasks, function (i, item) {
                                    let taxRate = item.vat?.value ?? 0;
                                    let taxAmount = (parseFloat(item.total_budget) - parseFloat(item.budget)) || 0;

                                    html += `
                                    <tr class="text-center">
                                        <td class="text-left">${item.item_description ?? '-'}</td>
                                        <td>${parseFloat(item.rate).toFixed(2)}</td>
                                        <td>${parseFloat(item.qty)}</td>
                                        <td>${parseFloat(item.budget).toFixed(2)}</td>
                                        <td>${taxAmount.toFixed(2)}</td>
                                        <td>${parseFloat(item.total_budget).toFixed(2)}</td>
                                    </tr>`;


                                    });

                                    html += `
                                    <tr>
                                    <td colspan="5" class="text-end">TOTAL NET PAYABLE AMOUNT (EXCLUDING VAT)</td>
                                    <td class="text-center">${parseFloat(sale.budget ?? 0).toLocaleString('en-US', {minimumFractionDigits: 2,
                                            maximumFractionDigits: 2})}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end">VAT</td>
                                        <td class="text-center">${parseFloat(sale.vat ?? 0).toLocaleString('en-US', {minimumFractionDigits: 2,
                                            maximumFractionDigits: 2})}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end">RETENTION AMOUNT</td>
                                        <td class="text-center">
                                            <span class="badge ${sale.retention_amount < 0 ? 'bg-danger' : 'bg-success'}">
                                                ${sale.retention_amount < 0 ? '(' + Math.abs(parseFloat(sale.retention_amount ?? 0)).toLocaleString('en-US', {
                                                    minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ')' : parseFloat(sale.retention_amount ??
                                                    0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) } </span>
                                        </td>
                                    </tr>
                                    <tr style="background:#706f6f33;">
                                        <td colspan="5" class="text-end">TOTAL GROSS AMOUNT (INCLUDING VAT)</td>
                                        <td class="text-center">${parseFloat(sale.total_budget ?? 0).toLocaleString('en-US', {minimumFractionDigits: 2,
                                            maximumFractionDigits: 2})}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>`;

                        row.find(".home-sale-view-container-" + id).html(html);
                    },
                        error: function () {
                        row.find(".home-sale-view-container-" + id).html(
                        `<div class="alert alert-danger">⚠️ Failed to load Sale details.</div>`
                        );
                    }
                });
            }
        });



</script>
@endpush

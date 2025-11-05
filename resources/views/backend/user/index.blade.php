@extends('layouts.backend.app')
@push('css')
@endpush

@section('content')
    @include('backend.tab-file.style')
    <style>
        .table .thead-light th {
            color:#F2F4F4 ;
            background-color: #34465b;
            border-color: #DFE3E7;
        }
        tr:nth-child(even) {
            background-color: #c8d6e357;
        }
    </style>
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.administration._header', ['activeManu' => 'user'])

                <div class="tab-content bg-white p-2 active">
                    <div class="tab-pane active">

                        <!-- Bordered table start -->
                        <div class="row" id="table-bordered">
                            <div class="col-12">
                                <div class="card">

                                    <div class="col-md-6" style="padding-left: 25px">
                                        <form method="get">
                                            <div class="row">
                                                <div class="col-9">
                                                    <div class="form-group">User's List

                                                        <input type="search" style="margin-top: 10px"
                                                            class="form-control inputFieldHeight" name="search"
                                                            value="{{ old('search') }}" placeholder="Search by User Email"
                                                            title="Search by  User Email">
                                                    </div>
                                                </div>

                                               <div class="col-md-3" style="padding-left: 0px; margin-top: 31px;">
                                                    <div class="d-flex gap-2">
                                                        <!-- Search Button -->
                                                        <button type="submit" class="btn formButton mSearchingBotton d-flex align-items-center" title="Searching"
                                                            style="padding-left: 8px; padding-right: 8px; background: #1A233A !important;">
                                                            <div class="formSaveIcon me-1">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}" alt="" width="20">
                                                            </div>
                                                            <span>Search</span>
                                                        </button>

                                                        <!-- Add Button -->
                                                        <button type="button" class="ml-1 btn btn-primary btn_create formButton d-flex align-items-center" title="Add"
                                                            data-toggle="modal" data-target="#newUserAdd">
                                                            <div class="formSaveIcon me-1">
                                                                <img src="{{ asset('assets/backend/app-assets/icon/add-icon.png') }}" width="25">
                                                            </div>
                                                            <span>Add</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-body">
                                        <!-- table bordered -->
                                        <div class="table-responsive" style="padding-left: 18px;padding-right: 18px;">
                                            <table class="table table-hover mb-0 table-sm">
                                                <thead class="thead-light">
                                                    <tr class="text-center" style="height: 40px;">
                                                        <th>User Name</th>
                                                        <th>Email</th>

                                                        <th>Role</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($users as $user)
                                                        <tr class="text-center trFontSize">
                                                            <td>{{ $user->name }}</td>
                                                            <td>{{ $user->email }}</td>


                                                            <td>{{ $user->role->name??'' }}</td>
                                                            <td class="pb-1">
                                                                <a class="btn userShowId" style="height: 25px; width: 25px;"
                                                                    title="Change Permission" id="{{ $user->id }}"><img
                                                                        src="{{ asset('assets/backend/app-assets/icon/authorization-icon.png') }}"
                                                                        style=" height: 25px; width: 25px;"></a>
                                                                <a href="#" class="btn userEdit" title="Edit"
                                                                    id="{{ $user->id }}"
                                                                    style="height: 25px; width: 25px;"><img
                                                                        src="{{ asset('assets/backend/app-assets/icon/edit-icon.png') }}"
                                                                        alt="" srcset=""
                                                                        style=" height: 25px; width: 25px;margin-left: -20px;"></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Bordered table end -->
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END: Content-->
    <div class="modal fade bd-example-modal-lg" id="newUserAdd" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="width: 60%;">
            <div class="modal-content">
                @include('backend.user.user-create-modal')
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="userEditModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="width: 60%;">
            <div class="modal-content">
                <div id="userEditDetails">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="userShowModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="width: 60%;">
            <div class="modal-content">
                <section class="print-hideen border-bottom" style="background-color: #34465b;">
                    <div class="d-flex flex-row-reverse">
                        <div class="mIconStyleChange"><a href="#" class="close btn-icon btn btn-danger"
                                data-dismiss="modal" aria-label="Close" title="Close"><span aria-hidden="true"><i
                                        class='bx bx-x'></i></span></a></div>
                        <div class="mIconStyleChange"><a href="#" class="btn btn-icon btn-success" title="Edit"><i
                                    class="bx bx-edit"></i></a></div>
                        <div class="mIconStyleChange"><a href="#" onclick="window.print();"
                                class="btn btn-icon btn-secondary" title="Print"><i class='bx bx-printer'></i></a></div>
                        <div class="mIconStyleChange"><a href="#" onclick="window.print();"
                                class="btn btn-icon btn-primary" title="PDF"><i class='bx bxs-file-pdf'></i></a></div>
                        <div class="mIconStyleChange"><a href="#" onclick="window.print();"
                                class="btn btn-icon btn-light" title="Setting"><i class='bx bxs-virus'></i></a></div>
                    </div>
                </section>
                @include('backend.tab-file.modal-header-info')
                <div id="userShow">

                </div>
                @include('backend.tab-file.modal-footer-info')
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ***********************************select option show*******************************

            var officeType = document.querySelector('.office_type');

            if (officeType) {
                console.log('#office_type element found');

                officeType.addEventListener('change', function() {
                    console.log('Change event triggered');
                    var selectedValue = this.value;

                    var countryD = document.querySelector('.country_d');
                    var outletD = document.querySelector('.outlet_d');
                    var countryD1 = document.querySelector('.country_head_office_id');
                    var outletD1 = document.querySelector('.outlet_id');
                    if (selectedValue === '2') {
                        countryD.style.display = 'block';
                        countryD1.removeAttribute('required');
                        outletD.style.display = 'none';
                        outletD1.value ='';
                        outletD1.setAttribute('required', 'true');
                    } else if (selectedValue === '3') {
                        countryD.style.display = 'block';
                        countryD1.setAttribute('required', 'true');
                        outletD.style.display = 'block';
                        outletD1.setAttribute('required', 'true');
                    } else {
                        countryD.style.display = 'none';
                        countryD1.value ='';

                        countryD1.removeAttribute('required');
                        outletD.style.display = 'none';
                        outletD1.value ='';
                        outletD1.removeAttribute('required');
                    }
                });
            } else {
                console.log('#office_type element not found');
            }
            // ***********************************outlet select append option*******************************
            var officeType = document.querySelector('.country_head_office_id');

            if (officeType) {
                console.log('#office_type element found');

                officeType.addEventListener('change', function() {
                    console.log('Change event triggered');
                    var selectedValue = this.value;

                    var outletDSelector = this.getAttribute('data-class');
                    var outletD = document.querySelector(outletDSelector);
                    $.ajax({
                        url: "{{ URL('find-outlet-option') }}",
                        method: "POST",
                        cache: false,
                        data: {
                            _token: '{{ csrf_token() }}',
                            selectedValue: selectedValue,
                        },
                        success: function(response) {
                            var options =
                                response; // Assuming the response contains an array of options

                            // Clear existing options
                            outletD.innerHTML = '';

                            // Append default option or additional logic if needed

                            // Append new options based on the response
                            options.forEach(function(option) {
                                var newOption = document.createElement('option');
                                newOption.value = option.id;
                                newOption.textContent = option.proj_name;
                                outletD.appendChild(newOption);
                            });
                        }
                    });
                });
            } else {
                console.log('#office_type element not found');
            }

        });
        //    ********************************* find outlet info********************************

        $(document).on("change", ".country_head_office_id", function(e) {
            e.preventDefault();
            var selectedValue = this.value;
            var outletDSelector = this.getAttribute('data-class');
            var outletD = document.querySelector(outletDSelector);
            $.ajax({
                url: "{{ URL('find-outlet-option') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    selectedValue: selectedValue,
                },
                success: function(response) {
                    var options =
                        response; // Assuming the response contains an array of options

                    // Clear existing options
                    outletD.innerHTML = '';

                    // Append default option or additional logic if needed

                    // Append new options based on the response
                    options.forEach(function(option) {
                        var newOption = document.createElement('option');
                        newOption.value = option.id;
                        newOption.textContent = option.proj_name;
                        outletD.appendChild(newOption);
                    });
                }
            });
        });
        //    ********************************* find outlet info********************************
        // ***********************************outlet select append option*******************************
    </script>
    <script>
        $(document).ready(function() {
            // Page Script
            $(document).on('click', '.select-all-permission', function(event) {

                if (this.checked) {
                    // Iterate each checkbox
                    $(':checkbox').each(function() {
                        this.checked = true;
                    });
                } else {
                    $(':checkbox').each(function() {
                        this.checked = false;
                    });
                }
            });


        });
    </script>
    <script>
        $(document).on("click", ".userEdit", function(e) {
            e.preventDefault();

            var id = $(this).attr('id');

            //alert(id);
            $.ajax({
                url: "{{ URL('user-edit-modal') }}",
                method: "POST",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("userEditDetails").innerHTML = response;
                    $('#userEditModal').modal('show');
                }
            });
        });
    </script>
    <script>
        $(document).on("click", ".userShowId", function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            console.log(id);
            $.ajax({
                url: "{{ URL('addpermission.edite') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("userShow").innerHTML = response;
                    $('#userShowModal').modal('show')
                }
            });
        });
    </script>
@endpush

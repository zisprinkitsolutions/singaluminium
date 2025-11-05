@extends('layouts.backend.app')
@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    <style>
        .modal-dialog {
            width: 600px;
        }
    </style>
@endpush
@section('content')
    @include('backend.tab-file.style')
    <style>
        .accordion .pluseMinuseIcon.collapsed::before {
            content: "\f067";
            ;
            cursor: pointer;
            border: 1px solid rgb(123, 123, 123);
        }

        .accordion .pluseMinuseIcon::before {
            font-family: 'FontAwesome';
            content: "\f068";
            cursor: pointer;
            border: 1px solid rgb(123, 123, 123);
        }

        .rowStyle {
            cursor: pointer;
            border-left: dotted;
            padding: 3px;
            margin-bottom: 2px;
        }

        .findMasterAcc {
            cursor: pointer;
        }

        /* ==========My Code========== */
        .bg-secondary {
            background-color: #34465b !important;
            border-radius: 40px;
            color: white !important;
            padding: 2px 5px 2px 5px !important;
        }

        a.bg-secondary:hover,
        a.bg-secondary:focus,
        button.bg-secondary:hover,
        button.bg-secondary:focus {
            background-color: #475f7b30 !important;
            color: black !important;
        }

        tr:nth-child(even) {
            background-color: #c8d6e357;
        }

        a.text-dark:hover,
        a.text-dark:focus {
            color: #ffffff !important;
        }

        .btn-outline-secondary {
            border-radius: 40px;
            padding: 0.2px 9px 0.2px 9px !important;
        }
    </style>
    <div class="app-content content print-hideen">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.setup._header', ['activeMenu' => 'chart-of-account'])

                <div class="tab-content bg-white">
                    <div class="tab-pane active">
                        @include('clientReport.setup.chart-of-account-sub', [
                            'activeMenu' => 'account-head',
                        ])
                        <section class="accountHeadDetails" style="max-width: 800px">
                            <div class="row pl-1">
                                <div class="col-md-12">
                                    <div class="d-flex align-items-center flex-wrap justify-content-between">
                                        <!-- Search Form -->
                                        <form class="form-inline d-flex align-items-center flex-nowrap" method="GET"
                                            action="">
                                            <input type="text" name="account_head_search" value="{{ $value }}"
                                                class="form-control form-control-sm inputFieldHeight ajax-search"
                                                placeholder="Search By Account Head Name" id="account_head_search"
                                                style="{{--width: auto; max-width: 200px;--}} width: 350px; margin-right: 5px;">

                                            <button type="submit"
                                                class="btn btn-sm btn-info d-flex align-items-center" title="Search">
                                                <img src="{{ asset('icon/search-icon.png') }}" width="20"
                                                    class="" style="margin-right: 5px;">
                                                <span>Search</span>
                                            </button>
                                        </form>

                                        <!-- Excel Export Button -->
                                        <a href="{{ route('account-head-export') }}"
                                            class="btn btn-sm btn-success ml-1 d-flex align-items-center"
                                            title="Export to Excel">
                                            <img src="{{ asset('/icon/excel-icon.png') }}" class="img-fluid"
                                                width="20" style="margin-left: 5px;">
                                            <span>Excel Export</span>
                                        </a>
                                    </div>
                                </div>
                                {{-- <div class="col-md-4 text-right pr-2"> --}}
                                    {{-- <button type="button" class="btn btn-xs btn-info btn_create formButton" title="Add"
                                        data-toggle="modal" data-target="#excel_import"
                                        style="padding-top: 6px;padding-bottom: 6px;">
                                        <div class="d-flex">
                                            <div class="formSaveIcon">
                                                <img src="{{ asset('/icon/excel-icon.png') }}" width="25">
                                            </div>
                                            <div><span>Data Import</span></div>
                                        </div>
                                    </button> --}}
                                {{-- </div> --}}
                            </div>
                            <hr>
                            <div class="container">
                                <div id="accordion" class="accordion">
                                    <div class="card mb-0">
                                        @foreach ($master_details as $masterAcc)
                                            <div class="d-flex">
                                                <div class="pluseMinuseIcon {{ $value ? '' : 'collapsed' }}"
                                                    data-toggle="collapse" href="#collapse{{ $masterAcc->id }}"
                                                    id="#collapse{{ $masterAcc->id }}"
                                                    aria-controls="collapse{{ $masterAcc->id }}"
                                                    aria-expanded="{{ $value ? '' : 'false' }}">
                                                    <li class="btn" style="padding-right: 5px !important;">
                                                        {{ $masterAcc->mst_ac_code }} - {{ $masterAcc->mst_ac_head }}</li>
                                                </div>
                                                @if (Auth::user()->hasPermission('Setup_Create'))
                                                    <a href="#" class="findMasterAcc"
                                                        data-target="{{ route('findMasterAcc', $masterAcc) }}"
                                                        title="New Head" style=" line-height: 2.5">
                                                        <img src="{{ asset('/icon/add-icon.png') }}"
                                                            style=" height: 25px; width: 25px;">
                                                    </a>
                                                @endif
                                            </div>
                                            <div id="collapse{{ $masterAcc->id }}"
                                                class="collapse multi-collapse {{ $value ? 'show' : '' }}">
                                                @if ($value)
                                                    @foreach (App\Models\AccountHead::where('master_account_id', $masterAcc->id)->where('fld_ac_head', 'LIKE', '%' . $value . '%')->get() as $item)
                                                        <div class="rowStyle d-flex ml-2" id="{{ 'tr' . $item->id }}">
                                                            <div style="line-height: 20px;">.....</div>
                                                            <div>
                                                                <li class="btn p-0 m-0" id="{{ 'update' . $item->id }}">
                                                                    {{ $item->fld_ac_code }}-{{ $item->fld_ac_head }}</li>
                                                                @if ($item->office_id != 0)
                                                                    @if (Auth::user()->hasPermission('Setup_Edit'))
                                                                        <a href="#" class="editAccHead"
                                                                            data-target="{{ route('editAccHead', $item) }}"
                                                                            id="{{ $item->id }}" title="Edit">
                                                                            <img src="{{ asset('/icon/edit-icon.png') }}"
                                                                                style=" height: 25px; width: 25px;">
                                                                        </a>
                                                                    @endif

                                                                    @if (Auth::user()->hasPermission('Setup_Delete'))
                                                                        <a href="#"
                                                                            class="text-danger account-head-delete"
                                                                            id="{{ $item->id }}" title="Delete">
                                                                            <img src="{{ asset('/icon/delete-icon.png') }}"
                                                                                style=" height: 25px; width: 25px;">
                                                                        </a>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    @foreach (App\Models\AccountHead::where('master_account_id', $masterAcc->id)->get() as $item)
                                                        <div class="rowStyle d-flex ml-2" id="{{ 'tr' . $item->id }}">
                                                            <div style="line-height: 20px;">.....</div>
                                                            <div>
                                                                <li class="btn p-0 m-0" id="{{ 'update' . $item->id }}">
                                                                    {{ $item->fld_ac_code }}-{{ $item->fld_ac_head }}</li>
                                                                @if (Auth::user()->hasPermission('Setup_Edit'))
                                                                    <a href="#" class="editAccHead"
                                                                        data-target="{{ route('editAccHead', $item) }}"
                                                                        id="{{ $item->id }}" title="Edit">
                                                                        <img src="{{ asset('/icon/edit-icon.png') }}"
                                                                            style=" height: 25px; width: 25px;">
                                                                    </a>
                                                                @endif
                                                                @if (Auth::user()->hasPermission('Setup_Delete'))
                                                                    <a href="#"
                                                                        class="text-danger account-head-delete"
                                                                        id="{{ $item->id }}" title="Delete">
                                                                        <img src="{{ asset('/icon/delete-icon.png') }}"
                                                                            style=" height: 25px; width: 25px;">
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    {{ $master_details->links() }}
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherPreviewShow">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="excel_import" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="width: 700px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Company List</h5>
                    {{-- <a href="{{asset('asmaa-transport truck service add excel sample.xlsx')}}">Sample Download</a> --}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('account-head-excel-import') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-1">
                            <label for="">Select Company</label>
                            <select name="office_id" class="form-control common-select2" id=""
                                style="width: 100%" required>
                                <option value="">Select Company</option>
                                @foreach ($offices as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="mb-1">
                        <input type="file" required class="form-control" name="excel_file" accept=".xlsx">
                    </div> --}}
                        @php
                            $token = time() + rand(10000, 99999);
                        @endphp
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    {{-- <script src="{{ asset('storage/upload/vendors/js/jquery/jquery.min.js') }}"></script> --}}

    <script>
        var index = 1;

        document.addEventListener('DOMContentLoaded', function() {
            // Use delegation
            document.body.addEventListener('click', function(e) {
                // Add new A/C Head
                if (e.target.classList.contains('add-head')) {
                    index++;
                    const group = e.target.closest('.ac-head-group');
                    const newGroup = group.cloneNode(true);

                    var newId = group.querySelector

                    newGroup.querySelector('.form-check-label').setAttribute('for', `add-unit_${index}`);
                    const checkbox = newGroup.querySelector('.form-check-input');
                    checkbox.value = null;
                    checkbox.setAttribute('id', `add-unit_${index}`);

                    newGroup.querySelector('input').value = '';
                    const button = newGroup.querySelector('button');
                    button.classList.remove('btn-success', 'add-head');
                    button.classList.add('btn-danger', 'remove-head');
                    button.textContent = '-';

                    group.parentNode.appendChild(newGroup);
                }

                // Remove A/C Head
                if (e.target.classList.contains('remove-head')) {
                    const group = e.target.closest('.ac-head-group');
                    group.remove();
                }
            });
        });
    </script>


    <script>
        $(document).on('click', '.account-head-delete', function(e) {
            e.preventDefault();
            Swal.fire(alertDesign('About to delete sub-head', 'delete'))
                .then((result) => {
                    if (result.isConfirmed) {
                        var id = $(this).attr('id');
                        var _token = $('input[name="_token"]').val();
                        $.ajax({
                            method: "post",
                            url: "{{ route('account-head-delete') }}",
                            data: {
                                id: id,
                                _token: _token,
                            },
                            success: function(response) {
                                if (response == 1) {
                                    $('#tr' + id).remove();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'Account Head Deleted',
                                        confirmButtonColor: '#3085d6'
                                    });
                                } else {
                                    $('#tr' + id).remove();
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Warning!',
                                        text: 'This account head already used',
                                        confirmButtonColor: '#3085d6'
                                    });
                                }
                            }
                        });


                        const href = $el.attr('href');
                        if (href) {
                            window.location.href = href;
                            return;
                        }

                    }
                });
        })
        $(document).ready(function() {
            $('#category').change(function() {
                // alert(1);
                if ($(this).val() != '') {
                    var value = $(this).val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('findMastedCode') }}",
                        method: "POST",
                        data: {
                            value: value,
                            _token: _token,
                        },
                        success: function(response) {
                            $("#mst_ac_code").val(response);
                        }

                    })
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var delay = (function() {
                var timer = 0;
                return function(callback, ms) {
                    clearTimeout(timer);
                    timer = setTimeout(callback, ms);
                };
            })();

            $(document).on("click", ".findMasterAcc", function(e) {
                e.preventDefault();
                var that = $(this);
                var urls = that.attr("data-target");
                $.ajax({
                    url: urls,
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function(response) {
                        //   alert('ok');
                        document.getElementById("voucherPreviewShow").innerHTML = response.page;
                        $('#voucherPreviewModal').modal('show')

                        $('#voucherPreviewModal').on('shown.bs.modal', function() {
                            // Focus on the input field after the modal is fully shown
                            $('#fld_ac_head').focus();
                        });
                    },
                    error: function() {
                        //   alert('no');
                    }
                });
            });
            $(document).on("click", ".editAccHead", function(e) {
                e.preventDefault();
                var that = $(this);
                var urls = that.attr("data-target");
                $.ajax({
                    url: urls,
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function(response) {
                        document.getElementById("voucherPreviewShow").innerHTML = response.page;
                        $('#voucherPreviewModal').modal('show')
                        $('#voucherPreviewModal').on('shown.bs.modal', function() {
                            var inputField = $('#fld_ac_head')[
                                0]; // Get the DOM element
                            inputField.focus(); // Focus on the input field
                            // Move the cursor to the end of the text
                            if (inputField.value.length > 0) {
                                inputField.setSelectionRange(inputField.value.length,
                                    inputField.value.length);
                            }
                        });
                    },
                    error: function() {
                        //   alert('no');
                    }
                });
            });
            $(document).on('submit', '#formSubmit', function(e) {
                e.preventDefault(); // avoid executing the actual submit of the form.
                var form = $(this);
                var url = form.attr('action');
                var data = new FormData(this);
                $("#submitButton").prop("disabled", true)
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
                            $('#voucherPreviewModal').modal('hide');
                            $('#update' + response.id).html(response.fld_ac_code + '-' +
                                response.fld_ac_head);
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Account Head Updated',
                                confirmButtonColor: '#3085d6'
                            });
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


            $(document).on('submit', '#formSubmit_new_head', function(e) {
                e.preventDefault();

                var form = $(this);
                var url = form.attr('action');
                var data = new FormData(this);

                $("#submitButton").prop("disabled", true);

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Have you reviewed your account setting properly? This setting cannot be changed once the account is under use.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Save it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: data,
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function(response) {
                                $("#submitButton").prop("disabled", false);

                                if (response.warning) {
                                    toastr.warning(response.warning);
                                } else if (response.status) {
                                    // Handle validation errors
                                    Object.keys(response.status).forEach(function(key) {
                                        var messages = response.status[key];
                                        messages.forEach(msg => toastr.warning(
                                            msg));
                                    });
                                } else {
                                    $('#voucherPreviewModal').modal('hide');
                                    $('#collapse' + response.m_id).append(response
                                        .page);

                                    // Success alert using SweetAlert (still using for success)
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'Account Head Added',
                                        confirmButtonColor: '#3085d6'
                                    });
                                }
                            },
                            error: function(err) {
                                $("#submitButton").prop("disabled", false);

                                let error = err.responseJSON;
                                if (error && error.errors) {
                                    $.each(error.errors, function(index, value) {
                                        toastr.error(value, "Error");
                                    });
                                } else {
                                    toastr.error("Something went wrong.", "Error");
                                }
                            }
                        });
                    }
                })
            });
        });
    </script>
@endpush

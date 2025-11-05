@extends('layouts.backend.app')
@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
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
                            'activeMenu' => 'account-sub-head',
                        ])
                        <section id="widgets-Statistics" class="mr-1 ml-1 mb-2 accountHeadStyle HeadStyle">
                            <div class="row">

                                <div class="col-md-6 text-right">
                                    {{-- <button type="button" class="btn btn-xs btn-info btn_create formButton" title="Add" data-toggle="modal" data-target="#excel_import" style="padding-top: 6px;padding-bottom: 6px;">
                                    <div class="d-flex">
                                        <div class="formSaveIcon">
                                            <img src="{{asset('/icon/excel-icon.png')}}" width="25">
                                        </div>
                                        <div><span>Data Import</span></div>
                                    </div>
                                </button> --}}
                                    <a href="{{ route('sub-account-export') }}" class="btn btn-xs mExcelButton formButton"
                                        title="Export to Excel"><img src="{{ asset('/icon/excel-icon.png') }}"
                                            class="img-fluid" width="30">Excel Export</a>
                                </div>
                            </div>
                        </section>
                        <hr>
                        <section class="accountHeadDetails" style="max-width: 800px">
                            <div class="container">
                                <div id="accordion" class="accordion">
                                    <div class="card mb-0">
                                        @foreach ($accountHeadhassubs as $accountHead)
                                            <div class="pluseMinuseIcon collapsed" data-toggle="collapse"
                                                href="#collapse{{ $accountHead->id }}"
                                                aria-controls="collapse{{ $accountHead->id }}" aria-expanded="false">
                                                <li class="btn " data-target="#">{{ $accountHead->fld_ac_code }} -
                                                    {{ $accountHead->fld_ac_head }}</li>
                                                <a href="#" class="sub-head-add"
                                                    data-target="{{ route('subhead-add', $accountHead) }}" title="New Head"
                                                    style=" line-height: 2.5">
                                                    <img src="{{ asset('/icon/add-icon.png') }}"
                                                        style=" height: 25px; width: 25px;">
                                                </a>
                                            </div>
                                            <div id="collapse{{ $accountHead->id }}" class="collapse multi-collapse">
                                                @foreach ($accountHead->sub_heads as $item)
                                                    <div class="rowStyle d-flex ml-2" id="tr{{ $item->id }}">
                                                        <div style="line-height: 20px;">.....</div>
                                                        <div>
                                                            <a href="#"
                                                                id="update{{ $item->id }}">{{ $item->name }}</a>
                                                            <a href="#" class="editAccHead"
                                                                data-target="{{ route('edit-acc-sub-head', $item) }}"
                                                                id="{{ $item->id }}" title="Edit">
                                                                <img src="{{ asset('/icon/edit-icon.png') }}"
                                                                    style=" height: 25px; width: 25px;">
                                                            </a>
                                                            <a href="#" class="text-danger sub-account-head-delete"
                                                                onclick="return confirm('Delete Account Sub Head. Confirm?')"
                                                                id="{{ $item->id }}" title="Delete">
                                                                <img src="{{ asset('/icon/delete-icon.png') }}"
                                                                    style=" height: 25px; width: 25px;">
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="excel_import" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Company List</h5>
                    {{-- <a href="{{asset('asmaa-transport truck service add excel sample.xlsx')}}">Sample Download</a> --}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('sub-account-head-excel-import') }}" method="POST"
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
                            <button type="submit" class="btn btn-primary"
                                onclick="return confirm('Please Confirm ?')">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal" role="document">
            <div class="modal-content">
                <div id="voucherPreviewShow">

                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    {{-- <script src="{{ asset('storage/upload/vendors/js/jquery/jquery.min.js') }}"></script> --}}
    <script>
    let subheadCount = 1;

    $(document).on('click', '#add-subhead', function () {
        subheadCount++;

        let newInput = `
        <div class="col-md-6 subhead-group d-flex align-items-end gap-1">
            <div class="w-100">
                <label>Subhead ${subheadCount}</label>
                <input type="text" name="name[]" class="form-control inputFieldHeight">
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-subhead  ">×</button>
        </div>`;

        $('#subhead-wrapper').append(newInput);
    });

    $(document).on('click', '.remove-subhead', function () {
        $(this).closest('.subhead-group').remove();
        // Optional: re-number labels
        $('#subhead-wrapper .subhead-group').each(function (index) {
            $(this).find('label').text('Subhead ' + (index + 1));
        });
        subheadCount = $('#subhead-wrapper .subhead-group').length;
    });
</script>
    <script>
        $(document).on("click", ".sub-head-add", function(e) {
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


        $(document).on('click', '.sub-account-head-delete', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            var _token = $('input[name="_token"]').val();
            $.ajax({
                method: "post",
                url: "{{ route('sub-account-head-delete') }}",
                data: {
                    id: id,
                    _token: _token,
                },
                success: function(response) {
                    if (response == 1) {
                        $('#tr' + id).remove();
                        toastr.success("Account Sub Head Deleted");
                    } else {
                        toastr.warning('This account sub head already used')
                    }
                }
            });
        })
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
                            $('#fld_ac_head').focus();
                        });
                        $(".common-select2").select2();
                    },
                    error: function() {}
                });
            });

            $(document).on('submit', '#updateFormSubmit', function(e) {
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
                            $('#update' + response.id).html(response.name);
                            toastr.success("Account Head Updated", "Success");
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
        });
    </script>
@endpush

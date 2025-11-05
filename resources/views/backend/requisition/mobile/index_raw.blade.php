@extends('layouts.backend.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@section('content')
    @include('layouts.backend.partial.style')
    <style>
        .changeColStyle span {
            min-width: 16%;
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

        tr {
            cursor: pointer;
        }
    </style>

    @php
        $settings = \App\Setting::where('config_name', 'title_name')->first();
        $company_name = \App\Setting::where('config_name', 'company_name')->first();
    @endphp
    <header class="navbar navbar-default visible-xs-block" style="margin-bottom:0;">
        <div class="container-fluid">
            <div class="navbar-header" style="width:100%; display:flex; justify-content:space-between; align-items:center;">

                {{-- Company Name (Max 2 words) --}}
                <span class="navbar-brand" style="padding:10px 15px;">
                    {{ implode(' ', array_slice(explode(' ', $company_name->config_value), 0, 2)) }}
                </span>

                {{-- Profile Dropdown --}}
                <div class="dropdown" style="padding-right:10px;">
                    <button class="btn btn-default dropdown-toggle" type="button" id="profileMenu" data-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fa fa-user"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="profileMenu">
                        <li class="dropdown-header">{{ Auth::user()->name ?? 'User' }}</li>
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

    {{-- Hidden Logout Form --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <div class="app-content content print-hideen">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.purchase._header', ['activeMenu' => 'requisition'])
                <div class="tab-content journaCreation">
                    <div id="journaCreation" class="tab-pane bg-white active">
                        <div class="py-1 px-1">
                            <div class="py-1 px-1">
                                <style>
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
                                        border-radius: 5px;
                                        padding: 0.2px 9px 0.2px 9px !important;
                                        width: 70px;
                                    }
                                </style>

                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{-- {{route("requisitions.create")}} --}}"
                                        class="btn btn-outline-secondary nav-item nav-link tabPadding bg-secondary text-white"
                                        role="tab" aria-controls="nav-contact" aria-selected="false"
                                        style="margin-right:15px;" data-toggle="modal"
                                        data-target="#requisitionCreateModal">
                                        <div>Create</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <section id="widgets-Statistics">
                            <div class="row">
                                <div class="col-md-12 px-2">
                                    <div class="cardStyleChange" style="width: 100%">
                                        <div class="card-body bg-white">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="text" name="search" id="search"
                                                        class="form-control inputFieldHeight"
                                                        placeholder="Search by Requisition No">
                                                </div>
                                                {{-- <div class="col-3">
                                                    <select name="party_search" id="party_search"
                                                        class="common-select2 inputFieldHeight" style="width: 100%;">
                                                        <option value="">Select Payee ...</option>
                                                        @foreach ($parties as $party)
                                                            <option value="{{ $party->id }}">{{ $party->pi_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div> --}}

                                                <div class="col-md-3">
                                                    <input type="text" name="date_search" id="date_search"
                                                        class="form-control inputFieldHeight datepicker"
                                                        placeholder="Search by Date" autocomplete="off">
                                                </div>
                                            </div><br>

                                            <div style="height:500px; overflow-y:auto;">
                                                <table class="table table-bordered table-sm ">
                                                    <thead class="thead" style="position: sticky ; top:-2px; z-index:999;">
                                                        <tr>
                                                            <th style=" color:#fff !important;;">SL</th>
                                                            <th style="color:#fff !important;;">Date</th>
                                                            <th style="width: 15%; color:#fff !important;">Requisition No
                                                            </th>
                                                            <th class="text-left"
                                                                style="width: 25%; color:#fff !important;">Project Name</th>
                                                            <th class="text-left"
                                                                style="width: 25%; color:#fff !important;">Task</th>
                                                            <th class="text-left"
                                                                style="width: 25%; color:#fff !important;">Sub Task</th>
                                                            <th class="text-left" style=" color:#fff !important;">Status
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="purch-body">
                                                        @foreach ($expenses as $key => $item)
                                                            <tr class="lpo_bill_view"
                                                                data-url="{{ route('requisitions.show', $item->id) }}"
                                                                style="text-align:center;">
                                                                <td>{{ ($expenses->currentPage() - 1) * $expenses->perPage() + $key + 1 }}
                                                                </td>
                                                                <td>{{ date('d/m/Y', strtotime($item->date)) }}</td>
                                                                <td>{{ $item->requisition_no }}</td>
                                                                <td class="text-left">{{ $item->project->project_name ?? '' }}
                                                                </td>
                                                                <td class="text-left">
                                                                    {{ $item->task ? $item->task->task_name : '' }}</td>
                                                                <td class="text-left">
                                                                    {{ \Illuminate\Support\Str::limit($item->subTask ? $item->subTask->item_description : '', 30) }}
                                                                </td>
                                                                <td class="text-left">{{ $item->status }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            {!! $expenses->links() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    {{-- modal --}}
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
    <div class="modal fade bd-example-modal-lg" id="voucherDetailsPrintModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherDetailsPrint">

                </div>
            </div>
        </div>
    </div>
    {{-- _________ Create Modal _________ --}}
    <div class="modal fade bd-example-modal-lg" id="requisitionCreateModal" tabindex="-1" rrole="dialog"
        aria-labelledby="requisitionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="requisitionCreateShow">

                    <style>
                        .custom-search {
                            background: #9b9fa3 !important;
                            padding: 6px 10px;
                        }
                    </style>

                    <section class="print-hideen border-bottom" style="padding: 5px 15px;background:#364a60;">
                        <div class="row pl-2">
                            <div class="col-md-6 pl-1">
                                <h3 style="font-family:Cambria;font-size: 2rem;color:white;">Requisition Create</h3>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex flex-row-reverse" style="padding-right: 8px;padding-top: 6px;">
                                    <div class=""><a href="#" class="btn-icon btn btn-danger"
                                            data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i
                                                    class='bx bx-x'></i></span></a></div>
                                    <div class="" style="padding-right: 3px;"><a href="#"
                                            onclick="window.print();" class="btn btn-icon btn-success"><i
                                                class="bx bx-printer"></i></a></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div style="margin: 10px 20px;">
                        @include('layouts.backend.partial.modal-header-info')
                    </div>

                    <section id="widgets-Statistics">
                        <form action="{{ route('requisitions.store') }}" method="POST" id="formSubmit"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="cardStyleChange bg-white">
                                <div class="card-body ">
                                    <div class="row px-1">
                                        <div class="col-md-3 ">
                                            <label for="">Project/Plot</label>
                                            <div class="row align-items-center">
                                                <div class="col-12 customer-select">
                                                    <select name="project_id" id="project_id" class="common-select2"
                                                        style="width: 100% !important" data-target="">
                                                        <option value="">Select...</option>
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
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-left-padding">
                                            <label for="">Task</label>
                                            <div class="row align-items-center">
                                                <div class="col-12 customer-select">
                                                    <select name="task_id" id="task_id" class="common-select2"
                                                        style="width: 100% !important" data-target="">
                                                        <option value="">Select Project/Plot...</option>
                                                    </select>
                                                    @error('task_id')
                                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-left-padding">
                                            <label for="">Sub Task</label>
                                            <div class="row align-items-center">
                                                <div class="col-12 customer-select">
                                                    <select name="sub_task_id" id="sub_task_id" class="common-select2"
                                                        style="width: 100% !important" data-target="">
                                                        <option value="">Select Task...</option>
                                                    </select>
                                                    @error('sub_task_id')
                                                        <div class="btn btn-sm btn-danger">{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-left-padding">
                                            <label for="">Attention</label>
                                            <input type="text" name="attention" id="attention"
                                                class="form-control inputFieldHeight">
                                        </div>
                                        <div class="col-md-1 col-left-padding">
                                            <label for="">Date</label>
                                            <input type="text" value="{{ Carbon\Carbon::now()->format('d/m/Y') }}"
                                                class="form-control inputFieldHeight datepicker" name="date"
                                                autocomplete="off" placeholder="dd-mm-yyyy">
                                            @error('date')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-right-padding"
                                style="margin-top:25px !important;padding-left:0px !important">
                                <div class="row mx-1">
                                    <div class="cardStyleChange" style="width: 100%">
                                        <div class="card-body bg-white">
                                            <table class="table  table-sm ">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30%">Description</th>
                                                        <th>Unit</th>
                                                        <th>Quantity</th>
                                                        <th class="NoPrint" style="width: 5%;padding: 2px;">
                                                            <button type="button"
                                                                class="btn btn-sm btn-success addBtn"style="border: 1px solid green;
                                                                                        color: #fff; border-radius: 10px;padding: 5px;"
                                                                onclick="BtnAdd()"><i class="bx bx-plus"
                                                                    style="color: white;margin-top: -5px;"></i></button>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="TBody">
                                                    <tr id="TRow" class="text-center invoice_row">
                                                        <td>
                                                            <div class="d-flex justy-content-between align-items-center">
                                                                <input name="group-a[0][multi_acc_head]"
                                                                    placeholder="Item Description" id=""
                                                                    cols="30" rows="1"
                                                                    class="form-control inputFieldHeight"
                                                                    list="acc_head_list" required>

                                                                <datalist id="acc_head_list">
                                                                    @foreach ($heads as $item)
                                                                        <option value="{{ $item->name }}">
                                                                    @endforeach
                                                                </datalist>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <select type="number" step="any" name="group-a[0][unit]"
                                                                class="text-center form-control quantity"style="width: 100%;height:36px;">

                                                                <option value="">Select...</option>

                                                                @foreach ($units as $unit)
                                                                    <option value="{{ $unit->id }}">
                                                                        {{ $unit->name }} </option>
                                                                @endforeach
                                                            </select>
                                                        </td>

                                                        <td>
                                                            <div class="d-flex justy-content-between align-items-center">
                                                                <input type="number" step="any"
                                                                    name="group-a[0][quantity]" required
                                                                    placeholder="Quantity"
                                                                    class="text-center form-control inputFieldHeight quantity"style="width: 100%">
                                                            </div>
                                                        </td>

                                                        <td class="NoPrint text-center"><button
                                                                style="padding: 5px; margin: 4px;" type="button"
                                                                class="btn btn-sm btn-danger"onclick="BtnDel(this)"><i
                                                                    class="bx bx-trash"
                                                                    style="color: white;margin-top: -5px;"></i></button>
                                                        </td>
                                                    </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="cardStyleChange">
                                <div class="card-body bg-white">
                                    <div class="row px-1">

                                        {{-- <div class="col-sm-11 col-right-padding ">
                                            <div class="row">
                                                <div class="col-sm-3 col-right-padding  form-group">
                                                    <label for="">Voucher File</label>
                                                    <input type="file" class="form-control inputFieldHeight"
                                                        name="voucher_file" id="voucher_file" >
                                                </div>
                                                <div class="col-sm-3 col-right-padding  form-group">
                                                    <label for="">Checked By</label>
                                                    <input type="text" class="form-control inputFieldHeight"
                                                        name="checked_by" id="checked_by" placeholder="Checked By"
                                                        value="" >
                                                </div>


                                                <div class="col-sm-3 col-right-padding  form-group">
                                                    <label for="">Prepared By</label>
                                                    <input type="text" class="form-control inputFieldHeight"
                                                        name="prepared_by" id="prepared_by" placeholder="Prepared By"
                                                        value="" >
                                                </div>

                                                <div class="col-sm-3  form-group">
                                                    <label for="">Approved By</label>
                                                    <input type="text" class="form-control inputFieldHeight"
                                                        name="approved_by" id="approved_by" placeholder="Approved By"
                                                        value="" >
                                                </div>

                                                {{-- <div class="col-sm-3  form-group">
                                                    <label for="">Pay Terms</label>
                                                    <input type="text" class="form-control inputFieldHeight"
                                                        name="pay_terms" id="pay_terms" placeholder="Pay Terms"
                                                        value="" >
                                                </div>
                                            </div>
                                        </div> --}}

                                        <div class="col-12 text-right d-flex justify-content-start mb-1">
                                            <button type="submit" class="btn btn-primary formButton " id="submitButton">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}"
                                                            alt="" srcset="" width="25">
                                                    </div>
                                                    <div><span>Save</span></div>
                                                </div>
                                            </button>
                                            <a href="{{ route('lpo-bill-create') }}" class="btn btn-warning  d-none"
                                                id="newButton">New</a>


                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </section>

                    <div class="img receipt-bg invoice-view-wrapper">
                        <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid"
                            style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;"
                            alt="">
                        {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/js/scripts/forms/select/form-select2.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/js/scripts/forms/form-repeater.js"></script>
    {{-- js work by mominul start --}}

    {{-- Confirm Logout Script --}}
    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to logout?")) {
                document.getElementById('logout-form').submit();
            }
        }
    </script>

    <script>
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


        // -------- For (create) -----------
        // js work by mominul start
        function refreshPage() {
            window.location.reload();
        }
        // js work by mominul end

        $(document).ready(function() {

            // $('.btn_create').click(function(){
            $(document).on("click", ".btn_create", function(e) {
                e.preventDefault();
                // alert('Alhamdulillah');
                setTimeout(function() {
                    $('.multi-acc-head').select2();
                    $('.multi-tax-rate').select2();
                }, 1000);
            });


            $("#formSubmit").submit(function(e) {
                e.preventDefault(); // avoid executing the actual submit of the form.
                var form = $(this);
                var url = form.attr('action');
                var data = new FormData(this);
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
                            $("#submitButton").prop("disabled", true)
                            $(".deleteBtn").prop("disabled", true)
                            $(".addBtn").prop("disabled", true)
                            document.getElementById("voucherPreviewShow").innerHTML = response;
                            $('#voucherPreviewModal').modal('show');
                            $("#newButton").removeClass("d-none")
                            $("#submitButton").addClass("d-none")
                            $('#requisitionCreateModal').modal('hide');
                            // 🔄 Table tbody reload
                            $("#purch-body").load(location.href + " #purch-body>*", "");
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

            $("#date").focus();
            $('#project').change(function() {
                console.log($(this).val());
                if ($(this).val() != '') {
                    var value = $(this).val();
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('findProject') }}",
                        method: "POST",
                        data: {
                            value: value,
                            _token: _token,
                        },
                        success: function(response) {
                            console.log(response);
                            $("#owner").val(response.owner_name);
                            $("#location").val(response.address);
                            $("#address").val(response.address);
                            $("#mobile").val(response.cont_no);
                        }
                    })
                }
            });

            $('#party_info').change(function() {
                if ($(this).val() != '') {
                    var value = $(this).val();
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('partyInfoInvoice2') }}",
                        method: "POST",
                        data: {
                            value: value,
                            _token: _token,
                        },
                        success: function(response) {
                            console.log(response);
                            $("#trn_no").val(response.trn_no);
                            $("#pi_code").val(response.pi_code);
                            $("#party_contact").val(response.con_no);
                            $("#party_address").val(response.address);
                            $("#attention").val(response.con_person);
                            $("#invoice_no").focus();
                        }
                    })
                }
            });

            $(document).on("keyup", "#pi_code", function(e) {
                // alert(1);
                var value = $(this).val();
                var _token = $('input[name="_token"]').val();
                if ($(this).val() != '') {
                    $.ajax({
                        url: "{{ route('partyInfoInvoice3') }}",
                        method: "POST",
                        data: {
                            value: value,
                            _token: _token,
                        },
                        success: function(response) {
                            console.log(response);
                            var qty = 1;
                            if (response != '') {
                                $("div.search-item-pi select").val(response.id);
                                $('.common-select2').select2();
                                $("#trn_no").val(response.trn_no);
                                $("#party_contact").val(response.con_no);
                                $("#party_address").val(response.address);

                                $("#invoice_no").focus();
                            }
                        }
                    })
                }
            });


            $(document).on("keyup", ".amount", function(e) {
                var amount = $(this).val();
                var invoice_type = $('#invoice_type').val();
                var quantity = $(this).closest("tr").find(".quantity").val();
                var rate = amount / quantity;

                var vat_amount = 0;
                var vat_rate = $(this).closest("tr").find(".vat_rate").val();
                vat_amount = (vat_rate / 100) * amount;
                gross_amount = (amount * 1) + vat_amount;
                gross_amount = gross_amount * 1;

                $(this).closest("tr").find(".rate").val(rate.toFixed(2));
                $(this).closest("tr").find(".vat_amount").val(vat_amount.toFixed(2));
                $(this).closest("tr").find(".sub_gross_amount").val(gross_amount.toFixed(2));
                total();

            });


            $(document).on("keyup", ".quantity", function(e) {
                var quantity = $(this).val();
                var invoice_type = $('#invoice_type').val();
                var rate = $(this).closest("tr").find(".rate").val();
                var amount = rate * quantity;

                var vat_amount = 0;
                var vat_rate = $(this).closest("tr").find(".vat_rate").val();
                vat_amount = (vat_rate / 100) * amount;
                gross_amount = (amount * 1) + vat_amount;
                gross_amount = gross_amount * 1;

                $(this).closest("tr").find(".amount").val(amount.toFixed(2));
                $(this).closest("tr").find(".vat_amount").val(vat_amount.toFixed(2));
                $(this).closest("tr").find(".sub_gross_amount").val(gross_amount.toFixed(2));
                total();

            });



            $(document).on("keyup", ".rate", function(e) {
                var rate = $(this).val();
                var invoice_type = $('#invoice_type').val();
                var quantity = $(this).closest("tr").find(".quantity").val();
                var amount = rate * quantity;

                var vat_amount = 0;
                var vat_rate = $(this).closest("tr").find(".vat_rate").val();
                vat_amount = (vat_rate / 100) * amount;
                gross_amount = (amount * 1) + vat_amount;
                gross_amount = gross_amount * 1;

                $(this).closest("tr").find(".amount").val(amount.toFixed(2));
                $(this).closest("tr").find(".vat_amount").val(vat_amount.toFixed(2));
                $(this).closest("tr").find(".sub_gross_amount").val(gross_amount.toFixed(2));
                total();

            });




            $(document).on("change", ".vat_rate", function(e) {
                var amount = $(this).closest("tr").find(".amount").val();
                var invoice_type = $('#invoice_type').val();
                var vat_amount = 0;
                var vat_rate = $(this).val();
                vat_amount = (vat_rate / 100) * amount;
                amount = (amount * 1) + vat_amount;
                $(this).closest("tr").find(".vat_amount").val(vat_amount.toFixed(2));
                $(this).closest("tr").find(".sub_gross_amount").val(amount.toFixed(2));

                total();
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
                        }
                    });
                }
            });




            function total() {
                var sum = 0;
                var total_vat = 0;
                $('.amount').each(function() {
                    var this_amount = $(this).val();
                    this_amount = (this_amount === '') ? 0 : this_amount;
                    var this_amount = parseFloat(this_amount);
                    sum = sum + this_amount;
                });
                $('.vat_amount').each(function() {
                    var this_amount = $(this).val();
                    this_amount = (this_amount === '') ? 0 : this_amount;
                    var this_amount = parseFloat(this_amount);
                    //
                    total_vat = total_vat + this_amount;
                });
                var taxable = sum.toFixed(2)
                var vat = total_vat.toFixed(2)
                var total = (vat * 1) + (taxable * 1)
                $(".taxable_amount").val(taxable);
                $(".total_vat").val(vat);
                $(".total_amount").val((total.toFixed(2)));
            };

        });

        function BtnAdd() {
            /* Add Button */
            var newRow = $("#TRow").clone();
            newRow.removeClass("d-none");
            newRow.find("input, select,textarea").val('').attr('name', function(index, name) {
                return name.replace(/\[\d+\]/, '[' + ($('#TBody tr').length) + ']');
            });
            newRow.find("th").first().html($('#TBody tr').length + 1);
            newRow.appendTo("#TBody");
            newRow.find(".common-select2").select2();
        }

        function BtnDel(v) {
            /* Delete Button */
            $(v).parent().parent().remove();

            $("#TBody").find("tr").each(function(index) {
                $(this).find("th").first().html(index);
            });
        }
        // ---------------------------------
    </script>
@endpush

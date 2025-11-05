@extends('layouts.backend.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@section('content')
@include('layouts.backend.partial.style')
<style>
    .changeColStyle span {
        min-width: 16%;
    }

    .fileList {
        list-style: none;
        padding: 0 !important;
        margin-top: 15px;
    }

    .fileList li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f6f9fc;
        border: 1px solid #d1dbe5;
        padding: 8px 12px;
        border-radius: 5px;
        margin-bottom: 6px;
        font-size: 14px;
        color: #333;
        transition: background-color 0.2s;
    }

    .fileList li:hover {
        background-color: #eef4fa;
    }

    .fileList li button.remove-btn {
        background-color: #ff5b5b;
        color: white;
        border: none;
        padding: 3px 10px;
        font-size: 12px;
        border-radius: 3px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .fileList li button.remove-btn:hover {
        background-color: #e04040;
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
        padding: 4px 8px;
    }

    tr:nth-child(even) {
        background-color: #c8d6e357;
    }

    tr {
        cursor: pointer;
    }

    @media print {

        /* Global resets */
        body {
            color: #34465b !important;
            font-size: 12pt !important;
            line-height: 1.4 !important;
        }

        /* Hide navigation and form controls */
        .navbar,
        .btn,
        input,
        select,
        .form-control,
        .common-select2,
        .nav-tabs {
            display: none !important;
        }


        /* Page breaks */
        .page-break {
            page-break-after: always;
        }

        /* Table styling */
        table {
            border-collapse: collapse !important;
        }

        th,
        td {
            border: 1px solid #eee !important;
            padding: 4px !important;
            color: #444 !important;
        }

        span {
            color: #444 !important;
        }

        .nav.nav-tabs {
            border-bottom: 0px !important;
        }

        .tab-content {
            border-left: none !important;
            border-right: none !important;
            border-bottom: none !important;
        }
    }
</style>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.sales._header', ['activeMenu' => 'receivable'])
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active">
                    <section id="widgets-Statistics">
                        <div class="row ">
                            <div class="col-md-12 px-2">
                                <div class="cardStyleChange" style="width: 100%">
                                    <div class="card-body bg-white">
                                        <div class="d-flex justify-content-between align-items-center print-hideen">
                                            <form class="d-flex align-items-center" style="width: 35%;">
                                                <div class="w-100 print-hideen" style="margin-right: 5px;">
                                                    <select name="party_search"
                                                        class="common-select2 inputFieldHeight w-100"
                                                        style="width:260px;">
                                                        <option value="">Select Party / Owner Name ...</option>
                                                        @foreach ($suppliers as $party)
                                                        <option value="{{ $party->id }}">{{ $party->pi_name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <button type="submit"
                                                    class="btn btn-primary inputFieldHeight print-hideen"
                                                    style="padding: 4px 10px !important;"> Search </button>
                                            </form>

                                            {{-- <div>
                                                <button class="btn btn-info inputFieldHeight print-hideen"
                                                    style="padding:3px 8px !important; width:80px;"
                                                    onclick="window.print()"> Print </button>
                                                <button onclick="exportToExcel();"
                                                    class="btn btn-success inputFieldHeight print-hideen"
                                                    style="padding:3px 8px !important; width:80px"> Excel </button>
                                            </div> --}}
                                            <!-- Right Side (Export/Import) -->
                                            <div class="dropdown print-hideen">
                                                <button class="btn btn-info inputFieldHeight formButton dropdown-toggle"
                                                    type="button" id="exportDropdown" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false"
                                                    style="padding:4px 15px !important;">
                                                    Export / Import
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="exportDropdown">
                                                    <a class="dropdown-item" href="javascript:void(0);"
                                                        onclick="exportToExcel('receivable')">Excel Export</a>
                                                    <a class="dropdown-item" href="javascript:void(0);"
                                                        onclick="window.print()">Print</a>
                                                </div>
                                            </div>
                                        </div>

                                        @include('layouts.backend.partial.modal-header-info')
                                        @include('layouts.backend.partial.modal-footer-info')

                                        <h5 class="invoice-view-wrapper"> Receivable </h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm" id="receivable">
                                                <thead class="thead">
                                                    <tr>
                                                        <th class="text-left" style="min-width: 180px !important;">
                                                            Party / Owner Name </th>
                                                        <th class="text-left" style="min-width: 180px !important;">
                                                            Project Name </th>
                                                        <th style="min-width: 90px;"> Plot No </th>
                                                        <th> Location </th>
                                                        <th> Contract Amount </th>
                                                        <th> Invoice Submitted </th>
                                                        <th> Invoice Amount </th>
                                                        <th> Received </th>
                                                        <th>Advance</th>
                                                        <th> Receivable </th>
                                                        <th> Expected Receivable </th>
                                                        <th> Retention</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($data['project'] as $key => $project)

                                                    <tr>
                                                        <td class="text-left view-receivable"
                                                            data-url="{{route('receivable-view',['id' => $key,'type' => 'project'])}}"
                                                            title="{{$project['party_name']}}">
                                                            {{ \Illuminate\Support\Str::limit($project['party_name'],
                                                            18) }}
                                                        </td>
                                                        <td class="text-left view-receivable"
                                                            data-url="{{route('receivable-view',['id' => $key,'type' => 'project'])}}"
                                                            title="{{ $project['project_name'] }}">
                                                            {{
                                                            \Illuminate\Support\Str::limit($project['project_name'],18)
                                                            }}

                                                        </td>

                                                        <td class="view-receivable"
                                                            data-url="{{route('receivable-view',['id' => $key,'type' => 'project'])}}">
                                                            {{ $project['plot_no'] }}</td>
                                                        <td class="view-receivable"
                                                            data-url="{{route('receivable-view',['id' => $key,'type' => 'project'])}}">
                                                            {{ $project['location'] }}</td>
                                                        <td class="view-receivable"
                                                            data-url="{{route('receivable-view',['id' => $key,'type' => 'project'])}}">
                                                            {{ number_format($project['contract_amount'], 2) }}</td>
                                                        <td class="view-receivable"
                                                            data-url="{{route('receivable-view',['id' => $key,'type' => 'project'])}}">
                                                            {{ $project['total_invoice'] }}</td>
                                                        <td class="view-receivable"
                                                            data-url="{{route('receivable-view',['id' => $key,'type' => 'project'])}}">
                                                            {{ number_format($project['total_amount'], 2) }}</td>
                                                        <td class="view-receivable"
                                                            data-url="{{route('receivable-view',['id' => $key,'type' => 'project'])}}">
                                                            {{ number_format($project['paid_amount'], 2) }}</td>
                                                        <td class="view-receivable"
                                                            data-url="{{route('receivable-view',['id' => $key,'type' => 'project'])}}">
                                                            {{ number_format($project['advance_amount'], 2) }}</td>
                                                        <td class="view-receivable"
                                                            data-url="{{route('receivable-view',['id' => $key,'type' => 'project'])}}">
                                                            {{ number_format($project['due_amount'], 2) }}</td>
                                                        <td class="view-receivable"
                                                            data-url="{{route('receivable-view',['id' => $key,'type' => 'project'])}}">
                                                            {{number_format($project['contract_amount'] -
                                                            $project['paid_amount'],2)}}</td>
                                                        @if($project['retention_amount'] > 0)
                                                        <td class="view-receivable"
                                                            data-url="{{route('retention-form',['id' => $key])}}">
                                                            <button class="btn btn-success"
                                                                style="padding:4px 7px !important;">
                                                                {{number_format($project['retention_amount'],2)}}
                                                            </button></td>
                                                        @else
                                                        <td class="view-receivable"
                                                            data-url="{{route('receivable-view',['id' => $key,'type' => 'project'])}}">
                                                            0.00</td>
                                                        @endif
                                                    </tr>
                                                    @endforeach


                                                    @if (!empty($data['unlinked']))
                                                    <tr>
                                                        <td colspan="11"><strong> Out of contruct invoices </strong>
                                                        </td>
                                                    </tr>
                                                    @foreach ($data['unlinked'] as $key => $unlinked)
                                                    <tr class="view-receivable"
                                                        data-url="{{route('receivable-view',['id' => $key,'type' => 'invoice'])}}">
                                                        <td class="text-left">{{ $unlinked['party_name'] }}</td>
                                                        <td class="text-left">{{ $unlinked['project_name'] }}</td>
                                                        <td>--</td>
                                                        <td>-- </td>
                                                        <td>{{ number_format($unlinked['total_amount'], 2) }}</td>
                                                        <td>{{ $unlinked['total_invoice'] }}</td>
                                                        <td>{{ number_format($unlinked['total_amount'], 2) }}</td>
                                                        <td>{{ number_format($unlinked['paid_amount'], 2) }}</td>
                                                        <td>{{ number_format($unlinked['due_amount'], 2) }}</td>
                                                        <td>---</td>
                                                        <td>---</td>
                                                    </tr>
                                                    @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="mt-1">
                                        {{$invoices->links()}}
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
<div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="receivable_modal_content">

            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="voucherPreviewModal2" tabindex="-1" rrole="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="receivable_modal_content2">

            </div>
        </div>
    </div>
</div>
<!-- END: Content-->


@endsection
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
<script src="{{ asset('assets/backend') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{ asset('assets/backend') }}/app-assets/js/scripts/forms/select/form-select2.js"></script>
<script src="{{ asset('assets/backend') }}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<script src="{{ asset('assets/backend') }}/app-assets/js/scripts/forms/form-repeater.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script>
    $(document).on('keyup', '.amount', function() {
            var amount = parseFloat($(this).val()) || 0;
            var vat = amount * (5 / 100);

            $('.vat_amount').val(vat.toFixed(2));
            $('.sub_total').val((amount + vat).toFixed(2));
            $('.taxable_amount').val(amount.toFixed(2));
            $('.total_vat').val(vat.toFixed(2));
            $('.total_amount').val((amount + vat).toFixed(2));
        });
    function exportToExcel() {
        var table = document.getElementById("receivable");
        var wb = XLSX.utils.table_to_book(table, { sheet: "receivable" });
        XLSX.writeFile(wb, "receivable-list.xlsx");
    }

    $(document).on("click", ".view-receivable", function(e) {
        e.preventDefault();
        var url = $(this).data('url');
		$.ajax({
			url: url,
			type: "get",

			success: function(response){
                document.getElementById("receivable_modal_content").innerHTML = response;
                $('#voucherPreviewModal').modal('show')
                $(".datepicker").datepicker({
                dateFormat: "dd/mm/yy"
            });
			}
		});
	});

        $('#party_search').change(function() {
            if ($(this).val() != '') {
                var party = $(this).val();
                var value = $('#search').val();
                var date = $('#date_search').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-customer-due') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date:date,
                        _token: _token,
                    },
                    success: function(response) {

                        $("#purch-body").empty().append(response);
                    }
                })
            }
        });

    let selectedFiles = [];

    $(document).on('change','.file_upload', function (e) {
        selectedFiles = Array.from(e.target.files);
        renderFileList(this);
    });

    function renderFileList(inputElement) {
        const list = $(inputElement).closest('.form-group').find('.fileList');
        list.empty();

        selectedFiles.forEach((file, index) => {
            list.append(`
                <li>
                    ${file.name}
                    <button type="button" class="remove-btn" data-index="${index}">Remove</button>
                </li>
            `);
        });
    }

    $(document).on('click', '.remove-btn', function () {
        const index = $(this).data('index');
        selectedFiles.splice(index, 1);

        // Rebuild new FileList and re-assign to input
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        const fileInput = document.getElementById('voucher_scan2');
        fileInput.files = dt.files;

        renderFileList(fileInput);
    });

    $(document).on('submit', "#formSubmit",function(e) {
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
                        document.getElementById("receivable_modal_content").innerHTML = response.preview;
                        $('#voucherPreviewModal').modal('show')

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

        $(document).on("click", ".sale_view", function(e) {
            e.preventDefault();
            var id= $(this).attr('id');
            $.ajax({
                url: "{{URL('sale-modal')}}",
                type: "post",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response){
                    document.getElementById("receivable_modal_content2").innerHTML = response;
                    $('#voucherPreviewModal2').modal('show')
                }
            });
        });

         $(document).on('click', '.approve-btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var amount = parseFloat($(this).data('total'));
            var message = 'Are you sure';
            if (amount > 0) {
                var rentention = amount / 9;
                message = `Are you sure to approve this invoice ? `;
            }
            Swal.fire(alertDesign(message, 'approve'))
                .then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            'type': 'get',
                            success: function(res) {
                                Swal.fire({
                                    title: 'Invoice approved successfully',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        })
                    }
                });
        })
</script>
@endpush

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
        text-align: center;
        padding: 0 5px;
    }

    td {
        font-size: 12px !important;
        height: 25px !important;
        text-align: center;
        padding: 0 5px;

    }

    tr {
        cursor: pointer;
    }

    @media print {
        .print-hidden {
            display: none !important;
        }

        .nav.nav-tabs~.tab-content {
            border-left: 1px solid #fff;
            border-right: 1px solid #fff;
            border-bottom: 1px solid #fff;
            padding-left: 0;
        }

        thead tr {
            border: 1px solid !important;
        }

        thead tr th {
            color: black !important;
        }
    }
</style>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.purchase._header', ['activeMenu' => 'lpo_bill'])
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active">
                    {{-- <div class="py-1 px-1">
                        @include('clientReport.lpo-bill._subhead_lpo_bill', [
                        'activeMenu' => 'list',
                        ])
                    </div> --}}
                    <section id="widgets-Statistics">
                        @include('layouts.backend.partial.modal-header-info')
                        <div class="row ">
                            <div class="col-md-12 px-2">
                                <div class="cardStyleChange" style="width: 100%">
                                    <div class="card-body bg-white">
                                        <div class="row  print-hideen">
                                            <div class="col-1">
                                                <button class="btn btn-primary inputFieldHeight lpo_create_model"
                                                    style="padding:3px 8px !important;"> New LPO </button>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" name="search" id="search"
                                                    class="form-control inputFieldHeight"
                                                    placeholder="Search by LPO No">
                                            </div>
                                            <div class="col-3">
                                                <select name="party_search" id="party_search"
                                                    class="common-select2 inputFieldHeight" style="width: 100%;">
                                                    <option value="">Select Payee ...</option>
                                                    @foreach ($parties as $party)
                                                    <option value="{{ $party->id }}">{{ $party->pi_name }} </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-3">
                                                <input type="text" name="date_search" id="date_search"
                                                    class="form-control inputFieldHeight datepicker"
                                                    placeholder="Search by Date">
                                            </div>
                                            <div class="col-2">
                                                <div class="dropdown mb-2" style="z-index: 1000;">
                                                    <button
                                                        class="btn btn-info inputFieldHeight formButton dropdown-toggle"
                                                        type="button" id="exportDropdown" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false"
                                                        style="padding:4px 15px !important;">
                                                        Export/Import
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="exportDropdown">
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            onclick="exportToExcel()">Excel Export</a>
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            onclick="window.print()">Print</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><br>
                                        <div style="height:500px; overflow-y:auto;">
                                            <table class="table table-bordered table-sm" id="expense">
                                                <thead class="thead" style="position: sticky ; top:-2px; z-index:999;">
                                                    <tr>
                                                        <th style="width: 5%;color:#fff !important;">SL</th>
                                                        <th style="width: 20%;color:#fff !important; text-align:left;">
                                                            Payee Name</th>
                                                        <th style="color:#fff !important;">Date</th>
                                                        <th style="width: 15%;color:#fff !important;">LPO No</th>
                                                        <th style="width: 20%;color:#fff !important;">Project</th>
                                                        <th style="width: 12%;color:#fff !important;">Amount
                                                            <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small>
                                                            <br> <span id="lpo_total">{{
                                                                number_format($data['total_amount'], 2) }}</span>
                                                        </th>
                                                        <th style="color:#fff !important;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="purch-body">
                                                    @foreach ($expenses as $key => $item)
                                                    <tr class="lpo_bill_view" id="{{ $item->id }}"
                                                        style="text-align:center;">
                                                        <td>{{ ($expenses->currentPage() - 1) * $expenses->perPage() +
                                                            $key + 1 }}</td>
                                                        <td style="text-align: left !important;"
                                                            title="{{ optional($item->party)->pi_name }}">
                                                            {{
                                                            \Illuminate\Support\Str::limit(optional($item->party)->pi_name,
                                                            30) }}
                                                        </td>
                                                        <td>{{ date('d/m/Y', strtotime($item->date)) }}</td>
                                                        <td>{{ $item->lpo_bill_no }}</td>
                                                        <td style="text-align: left !important;"
                                                            title="{{ $item->project->project_name??'' }}">
                                                            {{
                                                            \Illuminate\Support\Str::limit($item->project->project_name??'',
                                                            30) }}
                                                        </td>
                                                        <td>{{number_format($item->total_amount,2) }}</td>
                                                        <td>{{$item->status}}</td>
                                                    </tr>
                                                    @endforeach
                                                    {{-- <tr
                                                        style=" background-color: #3d4a94 !important; color:white;">
                                                        <td colspan="7" style="text-align: right ; margin-right:5px;">
                                                            Total</td>
                                                        <td>{{ number_format($data['total_amount'], 2) }}</td>
                                                        <td colspan="1"></td>
                                                    </tr> --}}
                                                </tbody>
                                            </table>
                                        </div>

                                        {!! $expenses->links()!!}
                                    </div>
                                </div>
                            </div>

                        </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
{{-- modal --}}
<!-- END: Content-->
<div class="modal fade bd-example-modal-lg" id="lpo_create_model" tabindex="-1" role="dialog"
    aria-labelledby="lpo_create_modelLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" id="lpo_create_model_content">

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
<div class="modal fade bd-example-modal-lg" id="voucherDetailsPrintModal" tabindex="-1" rrole="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="voucherDetailsPrint">

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportToExcel() {
            var table = document.getElementById("expense");
            var wb = XLSX.utils.table_to_book(table, {
                sheet: "list"
            });
            XLSX.writeFile(wb, "lpo-list.xlsx");
        }
        $(document).on('focus', '.datepicker', function(){
            $(this).datepicker({
                dateFormat: 'dd/mm/yy',
                minDate: 0 // today only and future
            });
        });

       $(document).on('click', '.approve-btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var userLimit = $(this).data('user-limit'); // pass from blade
            var lpoAmount = $(this).data('lpo-amount'); // pass from blade

            // 🔹 Check user approval limit
            if (parseFloat(userLimit) < parseFloat(lpoAmount)) { Swal.fire({ icon: 'error' , title: 'Approval Denied' ,
                html: "You are not authorized to approve this LPO.<br><br>" + "<b>Your max approve amount:</b> " + userLimit
                + "<br>" + "<b>LPO amount:</b> " + lpoAmount + "<br><br>" + "👉 Please contact your higher authority." ,
                confirmButtonText: 'OK' }); return false; } // 🔹 If allowed → show confirmation var
                message='Are you sure you want to approve this LPO?' ; Swal.fire(alertDesign(message, 'approve' )) .then((result)=>
                {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'get',
                        success: function(res) {
                            document.getElementById("voucherPreviewShow").innerHTML = res.preview;
                            $('#voucherPreviewModal').modal('show');
                            toastr.success('Approved Successfully!');
                            $("#purch-body").empty().append(res.expense_list);
                        }
                    });
                }
            });
        });
        $(document).on("click", ".lpo_bill_view", function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ URL('lpo-bill-view') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show')
                }
            });
        });


        $('#search').keyup(function() {
            if ($(this).val() != '') {
                var value = $(this).val();
                var party = $('#party_search').val();
                var date = $('#date_search').val();

                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-lpo-bill') }}",
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

        $('#party_search').change(function() {
            if ($(this).val() != '') {
                var party = $(this).val();
                var value = $('#search').val();
                var date = $('#date_search').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-lpo-bill') }}",
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

        $('#date_search').change(function() {
            if ($(this).val() != '') {
                var date = $(this).val();
                var value = $('#search').val();
                var party = $('#party_search').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-lpo-bill') }}",
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
        $(document).on('click', '.lpo_create_model', function(e){
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ route('lpo-bill-create') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("lpo_create_model_content").innerHTML = response;
                    $('.common-select2').select2();
                    $('#lpo_create_model').modal('show');
                    $('.datepicker').datepicker();
                }
            });
        });
        $(document).ready(function() {
            $(document).on("click", ".btn_create", function(e) {
                e.preventDefault();
                setTimeout(function() {
                    $('.multi-acc-head').select2();
                    $('.multi-tax-rate').select2();
                }, 1000);
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
                        $('.project_task').empty().append(response);
                        $(".sub_task_id").empty();
                    }
                });
            });
            $(document).on("click", ".lpo-bill-edit", function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: "get",
                    cache: false,
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        document.getElementById("voucherPreviewShow").innerHTML = '';
                        $('#voucherPreviewModal').modal('hide')
                        document.getElementById("lpo_create_model_content").innerHTML = response;
                        $('.common-select2').select2();
                        $('#lpo_create_model').modal('show');
                    }
                });
            });

            $(document).on("change", ".project_task", function(e) {
                var task_id = $(this).val();
                current_tr = $(this).closest("tr");
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
                            current_tr.find(".sub_task_id").empty().append(response);
                        }
                    });
                }
            });
            $(document).on('submit', '#formSubmit', function(e) {
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
                            document.getElementById("voucherPreviewShow").innerHTML = response.preview;
                            $("#purch-body").empty().append(response.expense_list);
                            $('#voucherPreviewModal').modal('show');
                            $("#newButton").removeClass("d-none")
                            $("#submitButton").addClass("d-none")
                            $("#lpo_create_model").modal('hide');
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
        $(document).on("change", "#project_id", function () {
            let $plotInput = $("#plot_no");
            let plot = $(this).find("option:selected").data("no"); // data-no ব্যবহার করো
            $plotInput.val(plot ? plot : "");
        });
        $(document).on("change", "#plot_no", function () {
            let $projectSelect = $("#project_id");
            let plotVal = $(this).val().trim().toLowerCase();
            let found = false;

            $projectSelect.find("option").each(function () {
                let optPlot = $(this).data("no"); // data-no check
                if (optPlot && optPlot.toString().toLowerCase() === plotVal) {
                    $projectSelect.val($(this).val()).trigger("change");
                    found = true;
                    return false; // break loop
                }
            });

            if (!found) {
                $projectSelect.val("").trigger("change");
            }
        });
        $(document).on("click", ".addItemBtn", function () {
            let $row   = $(this).closest("tr");
            let task_id = $row.find('.project_task').val();
            let sub_task_id = $row.find('.sub_task_id').val();
            let $clone = $row.clone();

            // get values from clone
            console.log("Task:", task_id, "SubTask:", sub_task_id);

            // find the highest index
            let lastIndex = 0;
            $("#tBody tr").each(function () {
                $(this).find("input, select, textarea").each(function () {
                    let name = $(this).attr("name");
                    if (name) {
                        let match = name.match(/group-a\[(\d+)\]/);
                        if (match) {
                            lastIndex = Math.max(lastIndex, parseInt(match[1]));
                        }
                    }
                });
            });
            let newIndex = lastIndex + 1;

            // update name attributes for cloned row
            $clone.find("input, select, textarea").each(function () {
                let name = $(this).attr("name");
                if (name) {
                    $(this).attr("name", name.replace(/group-a\[\d+\]/, "group-a[" + newIndex + "]"));
                }
            });
            $clone.find("input, select, textarea").val("");
            $clone.find('.project_task').val(task_id);
            $clone.find('.sub_task_id').val(sub_task_id);
            // insert new row
            $row.after($clone);
        });


        function checkApproveLimit(userLimit, lpoAmount, redirectUrl) {
            if (parseFloat(userLimit) < parseFloat(lpoAmount)) {
                alert(
                    "You are not authorized to approve this LPO.\n\n" +
                    "Your max approve amount: " + userLimit + "\n" +
                    "LPO amount: " + lpoAmount + "\n\n" +
                    "Please contact your higher authority."
                );
                return false;
            }
            window.location.href = redirectUrl;
        }
</script>
@endpush
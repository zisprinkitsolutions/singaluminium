@extends('layouts.backend.app')
@push('css')
    @include('layouts.backend.partial.style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            counter-reset: Serial;
        }

        .project-btn {
            border: none;
            color: #fff;
            font-size: 15px;
            font-weight: 500px;
            padding: 3px 10px;
            border-radius: 5px;
        }

        .add_items {
            background: #4CB648;
        }

        .delete_items {
            background: #EA5455;
            padding: 3px 3px 2px 3px;
            font-size: 13px;
        }

        .auto-index td:first-child:before {
            counter-increment: Serial;
            /* Increment the Serial counter */
            content: counter(Serial);
            /* Display the counter */
        }

        .auto-index,
        .auto-index th,
        .auto-index td {
            border: 1px solid #ddd;
        }

        .auto-index,
        .auto-index td {
            border: 1px solid #ddd;
            padding: 0 !important;
            margin: 0 !important;
        }

        #input-container .form-control {
            border: none;
        }

        #input-container .form-control:focus {
            border: 1px solid #4CB648;
        }

        .tasks-title,
        .budget-title {
            font-size: 12px;
            color: #313131;
            font-weight: 500;
            text-transform: capitalize;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-size: 12px !important;
        }

        .select2-container--default .select2-selection--single {
            height: 35px !important;
        }

        .add-customer {
            background: #4A47A3;
            padding: 2px 4px !important;
            margin: 0 !important;
        }

        .save-btn {
            background: #406343;
        }

        .form-control {
            height: 35px !important;
        }
    </style>
@endpush

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.project._header')
                <div class="tab-content bg-white">
                    <div id="journaCreation" class="tab-pane active">
                        <section class="p-1" id="widgets-Statistics">
                            <form class="repeater" action="{{ route('project.invoice.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="d-flex">
                                    @if ($job_project->invoice_type == 'amount_base')
                                        <div class="form-group w-100 d-none">
                                            <label for=""> Invoice type </label>
                                            <select name="invoice_type" class="invoice_type form-control"
                                                data-amount="{{ $job_project->due_amount }}">
                                                <option value="amount_base" selected> Amount Base Invoice </option>
                                            </select>
                                        </div>
                                    @elseif($job_project->invoice_type == 'task_base')
                                        <div class="form-group w-100 d-none">
                                            <label for=""> Invoice type </label>
                                            <select name="invoice_type" class="invoice_type form-control"
                                                data-amount="{{ $job_project->due_amount }}">
                                                <option value="task_base" selected> Tasks Base Invoice </option>
                                            </select>
                                        </div>
                                    @else
                                        <div class="form-group w-100 d-none">
                                            <label for=""> Invoice type</label>
                                            <select name="invoice_type" class="invoice_type form-control"
                                                data-amount="{{ $job_project->due_amount }}" style="font-size: 12px;">
                                                <option value="task_base" style="font-size: 12px;"> Tasks Base Invoice </option>
                                                <option value="amount_base" selected> Amount Base Invoice </option>
                                            </select>
                                        </div>
                                    @endif

                                    {{-- <div class="form-group w-100 ml-1">
                                        <label for=""> Invoice NO </label>
                                        <input type="text" name="invoice_no"
                                            class="form-control invoice_no  @error('invoice_no') is_invalid @enderror"
                                            placeholder="Invoice No" style="margin-top: 5px;font-size: 12px;" autocomplete="off" readonly>

                                        @error('invoice_no')
                                            <p class="text-danger"> {{ $message }}</p>
                                        @enderror
                                    </div> --}}
                                    <input type="hidden" name="job_project_id" value="{{$job_project->id}}">
                                    <div class="form-group w-100 ">
                                        <label for=""> Project Name </label>
                                        <select name="project_id" class="form-control  @error('project_id') is_invalid  @enderror" style="font-size: 12px;">
                                            <option value="{{ $job_project->project_id }}"> {{ $job_project->project_id?$job_project->new_project->name:$job_project->project_name }}</option>
                                        </select>
                                        @error('job_project_name')
                                            <p class="text-danger"> {{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-group w-100 ml-1 d-none">

                                        <label for="">Site/Delivery </label>
                                        <input type="text" name="site_delivery"
                                            value="{{$job_project->site_delivery }}" autocomplete="off"
                                            class="form-control @error('site_delivery') is_invalid @enderror"
                                            placeholder="site delivery ..." style="margin-top: 5px;" required readonly>

                                        @error('site_delivery')
                                            <p class="text-danger"> {{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group w-100 ml-1">

                                        <label for=""> Customer Name </label>

                                        <select name="customer_id"
                                            class="form-control select2Input @error('customer_id') is-invalid @enderror">
                                            <option selected readonly style="font-size: 12px;"> Select Customer </option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ old('customer_id', $job_project->customer_id) == $customer->id ? 'selected' : ' ' }}>
                                                    {{ $customer->pi_name }} </option>
                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                            <p class="text-danger"> {{ $message }}</p>
                                        @enderror

                                    </div>

                                    <div class="form-group w-100 ml-1">
                                        <label for="">Attention </label>
                                        <input type="text" name="attention" id="attention"
                                            autocomplete="off"
                                            class="form-control @error('attention') is_invalid @enderror"
                                            placeholder="attention ..." value="{{$job_project->attention}}" required>

                                        @error('attention')
                                            <p class="text-danger"> {{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group w-100 ml-1">
                                        <label for=""> Mobile No  </label>
                                        <input type="text" name="mobile_no" id="mobile_no"
                                            autocomplete="off"
                                            class="form-control @error('mobile_no') is_invalid @enderror"
                                            placeholder="mobile no ..." value="{{$job_project->mobile_no}}" required>

                                        @error('mobile_no')
                                            <p class="text-danger"> {{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row d-flex align-items-center mb-1">
                                    <div class="col-md-3">
                                        <label for=""> Desctiption </label>
                                        <textarea name="project_description" readonly cols="30" rows="2" placeholder="Description max 200 characters"
                                            class="form-control @error('project_description') is-invalid @enderror" style="font-size: 12px;">{{ old('project_description', $job_project->project_description) }}</textarea>
                                        @error('project_description')
                                            <p class="text-danger"> {{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for=""> Date </label>
                                        <input type="text" name="date"
                                            class="date form-control @error('date') is-invalid @enderror"
                                            value="{{ date('d/m/Y') }}" autocomplete="off" style="font-size: 12px;padding:0 !important">
                                        @error('date')
                                            <p class="text-danger"> {{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="">Top Note</label>
                                        <input type="text" class="form-control" name="top_note" required id="top_note" placeholder="5% Progressive Payment Invoice">

                                    </div>

                                    <div class="col-md-2">
                                        <label><input type="checkbox" name="with_note" checked> With Note</label>
                                    </div>


                                    <div class="col-md-2">
                                        <label for=""> Invoice Type </label>
                                            <select name="tax_invoice" class="form-control" style="font-size: 12px;">
                                                <option value="Tax Invoice"> Tax Invoice </option>

                                                <option value="Proforma Invoice"> Proforma Invoice </option>
                                            </select>
                                    </div>
                                </div>


                                <table class="auto-index repeater1 table table-sm">
                                    <thead style="background-color:#34465b !important;">
                                        <tr class="text-center">
                                            <th class="text-center" style="color:#fff;"> S.NO </th>
                                            <th style="color:#fff;"> Task Name </th>
                                            <th style="color:#fff;"> Description </th>
                                            <th class="text-center" style="color:#fff;"> Unit </th>
                                            <th class="text-center" style="color:#fff;"> Qty </th>
                                            <th class="text-center" style="color:#fff;"> Rate </th>
                                            @if($job_project->discount)
                                            <th class="text-center" style="color:#fff;"> Discount </th>
                                            @endif
                                            <th class="text-center" style="color:#fff;"> Amount </th>
                                        </tr>
                                    </thead>
                                    <tbody id="input-container">
                                        @foreach ($job_project->tasks as $key => $task)
                                            <tr class="text-center">
                                                <td class="text-center" style="font-size: 12px;">
                                                    <input type="checkbox" class="task_checkbox"
                                                        name="invoice_tasks[{{ $key }}]"
                                                        value="{{ $task->id }}" checked>
                                                    <input type="hidden" value="{{ $task->id }}"
                                                        name="task_id[{{ $key }}]">
                                                </td>
                                                <td style="width: 20%">
                                                    <input type="text" name="task_name[{{ $key }}]"
                                                        class="text-center form-control @error('task_name') is-invalid @enderror"
                                                        required autocomplete="off" value="{{ $task->task_name }}"
                                                        readonly style="font-size: 12px;">
                                                </td>

                                                <td>
                                                    <textarea name="description[{{ $key }}]" cols="30" rows="1" class="form-control" readonly
                                                        required style="font-size: 12px;">{{ $task->description }}</textarea>
                                                </td>

                                                <td>
                                                    <input type="text" name="unit[{{ $key }}]"
                                                        class="form-control text-center unit" required readonly
                                                        value="{{ $task->unit }}" style="font-size: 12px;">
                                                </td>
                                                <td>
                                                    <input type="number" name="qty[{{ $key }}]"
                                                        class="form-control text-center qty" required readonly
                                                        value='{{ $task->qty }}' style="font-size: 12px;">
                                                </td>
                                                <td>
                                                    <input type="number" name="rate[{{ $key }}]"
                                                        class="form-control text-center rate" required readonly
                                                        value='{{ $task->rate }}' style="font-size: 12px;">
                                                </td>

                                                @if($job_project->discount)
                                                <td>
                                                    <input type="number" name="discount[{{ $key }}]"
                                                        class="form-control text-center discount" required readonly
                                                        value='{{ $task->discount }}' style="font-size: 12px;">
                                                </td>
                                                @endif

                                                <td>
                                                    <input type="number" step="any"
                                                        name="amount[{{ $key }}]"
                                                        class="form-control amount text-center" required readonly
                                                        value="{{ $task->amount }}" style="font-size: 12px;">
                                                </td>
                                                {{-- <td class="text-center">
                                            <button  type="button" class="delete_items project-btn"> <i class="bx bx-trash"></i> </button>
                                        </td> --}}
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tbody>
                                        @php
                                            $total = $job_project->budget - $job_project->discount;
                                            $percentage = (100 * $job_project->paid_amount) / $total;
                                            $max = 100 - $percentage;
                                            $max=number_format($max,2);

                                        @endphp
                                        <tr>
                                            <td class="text-center d-none"> </td>
                                            <td colspan="{{$job_project->discount ? 7 : 6}}" class="text-right"> <span class="mr-1"> Total </span>
                                            </td>
                                            <td colspan="1"> <input type="number" name="total" readonly
                                                    step="any" class="form-control text-center total"
                                                    value="{{ $job_project->total_budget }}" style="font-size: 12px;"> </td>
                                        </tr>
                                        <tr class="previous_amount">
                                            <td class="text-center d-none"> </td>
                                            <td colspan="{{$job_project->discount ? 7 : 6}}" class="text-right"> <span class="mr-1"> Previous invoice</span> </td>
                                            <td colspan="1"> <input type="number" name="previous_amount" readonly
                                                    step="any" class="form-control text-center"
                                                    value="{{ $job_project->paid_amount }}"
                                                    readonly style="font-size: 12px;"> </td>
                                        </tr>
                                        <tr class="previous_amount">
                                            <td class="text-center d-none"> </td>
                                            <td colspan="{{$job_project->discount ? 7 : 6}}" class="text-right"> <span class="mr-1"> Amount </span>
                                            </td>
                                            <td colspan="1"> <input type="number" name="total_due_amount"
                                                    step="any" class="form-control text-center total_due_amount"
                                                    max="{{ $job_project->due_amount }}" min="1"
                                                    value="{{ $job_project->due_amount }}" placeholder="invoice Amount"
                                                    readonly style="font-size: 12px;"> </td>
                                            <input type="hidden" class="sub_total"
                                                value="{{ $job_project->budget - $job_project->discount }}">
                                        </tr>
                                        <tr class="previous_amount">
                                            <td class="text-center d-none"> </td>
                                            <td colspan="{{$job_project->discount ? 7 : 6}}" class="text-right"> <span class="mr-1"> Amount % </span>
                                            </td>
                                            <td colspan="1"> <input type="number" name="total_due_amount_percentage"
                                                    step="any"
                                                    class="form-control text-center total_due_amount_percentage"
                                                    max="{{ $max }}"
                                                    value="{{ $max }}"
                                                    placeholder="invoice Amount in percentage" style="font-size: 12px;"> </td>
                                        </tr>

                                        <tr>
                                            <td class="text-center d-none"> </td>
                                            <td colspan="{{$job_project->discount ? 7 : 6}}" class="text-right"> VAT <span class="mr-1">
                                                    {{ $standard_vat_rate }}% </span> </td>
                                            <td colspan="1"> <input type="number" name="vat" step="any"
                                                    data-vat="{{ $standard_vat_rate }}"
                                                    class="form-control text-center vat" value="0"
                                                    placeholder="invoice Amount" readonly style="font-size: 12px;"> </td>
                                        </tr>

                                        <tr>
                                            <td class="text-center d-none"> </td>
                                            <td colspan="{{$job_project->discount ? 7 : 6}}" class="text-right"> <span class="mr-1"> Total Amount (@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small>
                                                </span> </td>
                                            <td colspan="1"> <input type="number" name="invoice_amount"
                                                    step="any" class="form-control text-center invoice_amount"
                                                    max="{{ $job_project->due_amount }}" min="1"
                                                    value="{{ $job_project->due_amount }}" readonly
                                                    placeholder="invoice Amount" style="font-size: 12px;"> </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="row d-flex align-items-center">
                                    <div class="col-7">
                                        <div class="form-group" style="    width: 300px;margin-top: 27px;">
                                            <label for=""> Voucher File Upload  </label>
                                            <input
                                                class="form-control  @error('voucher_file') is-invalid @enderror" type="file" name="voucher_file" style="padding:0px; border:none" accept="application/pdf,image/png,image/jpeg,application/msword" >
                                            @error('voucher_file')
                                                <p class="text-danger"> {{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                   <div class="col-5">
                                    <div class="d-flex justify-content-end mt-1">
                                        <button type="submit" class="project-btn save-btn"> Save </button>
                                    </div>
                                   </div>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="add-customer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="padding: 5px 15px;">
                    <h5 class="modal-title" id="exampleModalLabel"> Create Party </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 5px 15px;">
                    <div class="d-flex">
                        <div class="form-group w-50">
                            <label for=""> Party Name </label>
                            <input type="text" name="pi_name" class="form-control" required>
                            <p class="error-pi_name text-danger"> </p>
                        </div>
                        <div class="form-group w-50 ml-1">
                            <label for=""> Contact Person </label>
                            <input type="text" name="con_person" class="form-control" required>
                            <p class="error-con_person text-danger"></p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="form-group w-50">
                            <label for=""> Party Type </label>
                            <select name="pi_type" class="form-control">
                                <option selected> Customer </option>
                                <option> Supplier </option>
                                <option> Employee </option>
                                <option> Government Body </option>
                            </select>
                            <p class="error-pi_type text-danger"></p>
                        </div>
                        <div class="form-group w-50 ml-1">
                            <label for=""> Mobile Phone Number </label>
                            <input type="text" name="phone_no" class="form-control" required>
                            <p class="error-phone_no text-danger"></p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="form-group w-50">
                            <label for=""> TRN No </label>
                            <input type="text" name="trn_no" class="form-control" required>
                            <p class="error-trn_no text-danger"></p>
                        </div>
                        <div class="form-group w-50 ml-1">
                            <label for=""> Phone Number </label>
                            <input type="text" name="con_no" class="form-control" required>
                            <p class="error-con_no text-danger"></p>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="form-group w-50">
                            <label for=""> Address </label>
                            <input type="text" name="address" class="form-control" required>
                            <p class="error-address text-danger"></p>
                        </div>
                        <div class="form-group w-50 ml-1">
                            <label for=""> Email </label>
                            <input type="text" name="email" class="form-control" required>
                            <p class="error-email text-danger"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 5px 15px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary create-party"> Create </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script>
        $(document).ready(function() {
            getInvoiceNo();

            $('.select2Input').select2();

            $(".add_items").click(function() {
                addInput();
            });

            $('.date').datepicker({
                dateFormat: 'dd/mm/yy'
            })

            function addInput() {
                $.ajax({
                    url: "{{ route('get.porjects.vat') }}",
                    type: 'get',
                    success: function(vats) {
                        var inputGroup = "<tr>" +
                            "<td class='text-center'></td>" +
                            "<td style='width: 20%'>" +
                            "<input type='text' name='task_name[]' class='form-control' required>" +
                            "</td>" +
                            "<td style='width: 30%'>" +
                            "<textarea name='description[]'  cols='30' rows='1' class='form-control' required></textarea>" +
                            "</td>" +
                            "<td>" +
                            "<input type='number' name='budget[]' class='form-control budget text-center' step='any'>" +
                            "</td>" +
                            "<td>"
                        inputGroup += "<select name='vat[]' class='vat form-control'>"
                        $.each(vats, function(key, index) {
                            inputGroup += "<option value=" + index.id + " data-value =" + index
                                .value + "> " + index.name + '  ( ' + index.value + ' )' +
                                " </option>"
                        })
                        inputGroup += "</select>" +
                            "</td>" +
                            "<td>" +
                            "<input type='number' name='total_budget[]' class='form-control total_budget text-center' step='any'>" +
                            "</td>" +
                            "<td class='text-center'>" +
                            "<button  type='button' class='delete_items project-btn'>" +
                            "<i class='bx bx-trash'> </i>" +
                            "</button>" +
                            "</td>" +
                            "</tr>";
                        $("#input-container").append(inputGroup);
                    },
                    error: function(error) {
                        toastr.error("Something rong Can't add column");
                    }
                })

            }
            checkInvoiceType();
        });

        function getInvoiceNo() {
            let invoice_no = $('.invoice_no').val();
            $.ajax({
                url: "{{ route('get.unique.invoice.no') }}",
                type: 'get',
                success: function(invoice_no) {
                    $('.invoice_no').val(invoice_no);
                }
            })
        }
        $(document).on('keyup', '.total_due_amount_percentage', function() {
            if ($(this).val() > 0) {
                var sub_total = $('.sub_total').val();
                var percentage = (sub_total / 100) * $(this).val();
                $('.total_due_amount').val(percentage.toFixed(2))
                checkInvoiceType();
                $('.save-btn').prop("disabled", false);
            } else {
                toastr.error("Amount can't be zero", 'error');
                $('.save-btn').prop("disabled", true);
                $('.vat').val('0.00')
                $('.invoice_amount').val('0.00')
                $('.total_due_amount').val('0.00')

            }

        })
        $(document).on('keyup', '.total_due_amount', function() {

            if ($(this).val() > 0) {
                var sub_total = $('.sub_total').val();
                var percentage = ($(this).val() * 100) / sub_total;
                $('.total_due_amount_percentage').val(percentage.toFixed(2))
                checkInvoiceType();
                $('.save-btn').prop("disabled", false);
            } else {
                toastr.error("Amount can't be zero", 'error');
                $('.save-btn').prop("disabled", true);
                $('.vat').val('0.00')
                $('.invoice_amount').val('0.00')
                $('.total_due_amount_percentage').val('0.00')
            }

        })

        $(document).on('change', '.invoice_type', function() {
            checkInvoiceType();
        })

        function checkInvoiceType() {
            let invoice_type = $('.invoice_type').val();
            if (invoice_type == 'amount_base') {
                calculateBudget2();
                $('.total_due_amount').removeAttr('readonly');
                $('.previous_amount').show();
                $('.task_checkbox').each(function(index, el) {
                    $(this).hide()
                })
                $('#invoice_amount').removeClass('invoice_amount');
            } else {
                $('.previous_amount').hide();
                $('.total_due_amount').attr('readonly', 'readonly');
                $('.task_checkbox').each(function(index, el) {
                    $(this).show()
                })
                $('#invoice_amount').addClass('invoice_amount');
                calculateBudget();
            }
        }

        function calculateBudget2() {
            let due_amount = parseFloat($('.total_due_amount').val());
            let vat = parseFloat($('.vat').attr('data-vat'));
            $('.vat').val(parseFloat((due_amount * vat) / 100).toFixed(2))
            $('.invoice_amount').val(parseFloat(due_amount + (due_amount * vat) / 100).toFixed(2))
            $('.invoice_amount').attr('max', parseFloat(due_amount + (due_amount * vat) / 100).toFixed(2))
        }

        function calculateBudget() {
            let due_amount = 0;
            $('.task_checkbox').each(function(index, el) {
                if ($(this).is(':checked')) {
                    let tr = el.closest('tr');
                    due_amount += parseFloat($(tr).find('.due_amount').val())
                }
            })
            if (due_amount > 0) {
                $('.total').val(due_amount);
                let vat = parseFloat($('.vat').attr('data-vat'));
                $('.vat').val(parseFloat((due_amount * vat) / 100).toFixed(2))
                $('.invoice_amount').val(parseFloat(due_amount + (due_amount * vat) / 100).toFixed(2))
                $('.invoice_amount').attr('max', parseFloat(due_amount + (due_amount * vat) / 100).toFixed(2))
                $('.save-btn').prop("disabled", false);
            } else {
                toastr.error('At last one item should be selected', 'error');
                $('.save-btn').prop("disabled", true);
            }

        }

        $(document).on('click', '.create-party', function() {
            $.ajax({
                url: "{{ route('jobproject.customer.store') }}",
                method: "POST",
                data: {
                    _token: $('input[name="_token"]').val(),
                    pi_name: $('input[name="pi_name"]').val(),
                    pi_type: $('select[name="pi_type"]').val(),
                    trn_no: $('input[name="trn_no"]').val(),
                    address: $('input[name="address"]').val(),
                    con_person: $('input[name="con_person"]').val(),
                    con_no: $('input[name="con_no"]').val(),
                    phone_no: $('input[name="phone_no"]').val(),
                    email: $('input[name="email"]').val(),
                },
                success: function(data) {
                    $('.customer_id').append("<option value='" + data.id + "' selected>" + data
                        .pi_name + "</option>");
                    $('.customer_id').select2();

                    $("#add-customer").modal('hide');
                },
                error: function(error) {
                    $.each(error.responseJSON.errors, function(key, val) {
                        $('p.error' + '-' + key).text(val[0]);
                        $('p.error' + '-' + key).siblings().addClass('is-invalid');
                    })
                }
            })
        })
        $(document).on('change', '.task_checkbox', function() {
            calculateBudget();
            disableInput();
        })

        function disableInput() {
            $('.task_checkbox').each(function(index, el) {
                let tr = el.closest('tr');
                if ($(this).is(':checked')) {
                    $(tr).find(':input[type="number"]').each(function() {
                        $(this).prop('disabled', false);
                    })
                } else {
                    $(tr).find(':input[type="number"]').each(function(el, index) {
                        console.log(index);
                        $(this).prop('disabled', true);
                    })
                }
            })
        }

        $(document).on('change', '#invoice_type', function() {
            if ($(this).val() == 'with_tax') {
                $('.vat').css('background-color', '#fff');
                calculateBudget()
            } else {
                $('.vat').css('background-color', '#ddd');
                $('.total-vat').val(0);
                calculateBudget()
            }
        })
    </script>
@endpush

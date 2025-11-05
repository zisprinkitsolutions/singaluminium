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
            font-size: 12px !important;
            height: 25px !important;
            text-align: center !important;
        }

        td {
            font-size: 14px !important;
            height: 25px !important;
            padding:0 6px !important;
        }

        .card-body {
            flex: 1 1 auto;
            min-height: 1px;
            padding: 0rem !important;
        }

        .card {
            margin-bottom: 0rem;
            box-shadow: none;
        }
        .btn i{
            top: 0px !important;
        }
    </style>
    <div class="app-content content print-hideen">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <input type="hidden" name="standard_vat_rate" value="{{$standard_vat_rate}}"  id="standard_vat_rate">

                @include('clientReport.accounting._header', ['activeMenu' =>'fund-allocation'])
                <div class="tab-content journaCreation active">
                    <div id="journaCreation" class="tab-pane bg-white active">

                        <div class="py-1 px-1">
                            @include('backend.fund-allocation.submenu', [
                                'activeMenu' => 'index',
                            ])
                        </div>

                        <section id="widgets-Statistics">
                            <div class="cardStyleChange p-1" >
                                <div class="card-body bg-white">
                                    <form action="">
                                        <div class="row">

                                            <div class="col-md-10">
                                                <div class="row">
                                                    <div class="col-2">
                                                        <select name="from_account_search" id="from_account_search" class="form-control inputFieldHeight" >
                                                            <option value="">From Account</option>
                                                            @foreach ($modes as $mode)
                                                            <option value="{{$mode->id}}">{{$mode->title}}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                    <div class="col-2">
                                                        <select name="to_account_search" id="to_account_search" class="form-control inputFieldHeight" >
                                                            <option value="">To Account</option>
                                                            @foreach ($modes as $mode)
                                                            <option value="{{$mode->id}}">{{$mode->title}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-2">
                                                        <input type="text" name="date_search" id="date_search" class="form-control inputFieldHeight datepicker" placeholder="Date">
                                                    </div>

                                                    <div class="col-2">
                                                        <input type="text" name="date_from_search" id="date_from_search" class="form-control inputFieldHeight datepicker" placeholder="Date From">
                                                    </div>
                                                    <div class="col-2">
                                                        <input type="text" name="date_to_search" id="date_to_search" class="form-control inputFieldHeight datepicker" placeholder="Date To">
                                                    </div>
                                                    <div class="col-md-1 text-right ">
                                                        <button type="subit" class="btn btn-light btn_create formButton" title="Search" >
                                                            <div class="d-flex">
                                                                <div class="formSaveIcon">
                                                                    <img src="{{asset('assets/backend/app-assets/icon/search-icon.png')}}" width="25">
                                                                </div>
                                                                <div><span>Search</span></div>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-right">
                                                @if(Auth::user()->hasPermission('Accounting_Create'))
                                                <button type="button" class="btn btn-primary btn_create formButton" title="Add" data-toggle="modal" data-target="#new_invoice" style="padding-top: 6px;padding-bottom: 6px;">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" width="25">
                                                        </div>
                                                        <div><span>Add</span></div>
                                                    </div>
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                    <div class="mt-2">
                                        <table class="table table-bordered table-responsive-sm table-sm">
                                            <thead class="thead">
                                                <tr >
                                                    <th style="width: 100px;">Date</th>
                                                    <th>From Account</th>
                                                    <th>To Account</th>
                                                    <th>Payment Account</th>
                                                    <th style="width: 150px; text-align:right;">Amount</th>
                                                    <th style="width: 150px; text-align:right;">Transaction Cost</th>
                                                    <th style="min-width: 150px; text-align:right">Transaction Number</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="purch-body">
                                                @foreach ($allocations as $item)
                                                    <tr>
                                                        <td href="{{route('fund-allocation.show',$item)}}" class="allocation-show text-center">{{date('d/m/Y', strtotime($item->date))}}</td>
                                                        <td href="{{route('fund-allocation.show',$item)}}" class="allocation-show text-center">{{optional($item->fromAccount)->title}}</td>
                                                        <td href="{{route('fund-allocation.show',$item)}}" class="allocation-show text-center">{{optional($item->toAccount)->title}}</td>
                                                        <td href="{{route('fund-allocation.show',$item)}}" class="allocation-show text-center">{{optional($item->payment_account)->full_name??''}}</td>
                                                        <td class="text-right allocation-show" href="{{route('fund-allocation.show',$item)}}" class="text-right">{{number_format($item->amount,2)}}</td>
                                                        <td class="text-right allocation-show" href="{{route('fund-allocation.show',$item)}}" class="text-right">{{number_format($item->transaction_cost,2)}}</td>
                                                        <td class="text-center allocation-show" href="{{route('fund-allocation.show',$item)}}" class="text-right">{{$item->transaction_number}}</td>
                                                        <td style="padding-bottom: 11px; padding-top: 0px">
                                                            <div class="d-flex justify-content-center">
                                                                <a href="{{ route('fund-allocation-approval', $item->id) }}" class="btn btn-icon {{--btn-warning--}} btn-success"
                                                                     onclick="event.preventDefault(); deleteAlert(this, 'Approve!, confirm?', 'approve');"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Approve"><i class="bx bx-check"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>
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
    <div class="modal fade" id="new_invoice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="padding: 5px 15px;background:#364a60;">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">Add New</h5>
                    <div class="d-flex align-items-center">
                        <button type="button" class="project-btn bg-danger text-white" data-dismiss="modal" aria-label="Close" style="padding: 3px 12px;" data-bs-toggle="tooltip" data-bs-placement="right" title="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body" style="padding: 5px 5px;">
                    <section id="widgets-Statistics" class="mb-1">
                        <div class="pt-2">
                            <form action="{{ route('fund-allocation.store') }}" method="POST" id="formSubmit" enctype="multipart/form-data">
                                @csrf
                                <div class="cardStyleChange bg-white">
                                    <div class="card-body">
                                        <div class="row pr-2 pl-2">
                                            <div class="col-md-2 changeColStyle search-item-pi">
                                                <label for="">From Account</label>
                                                <select name="from_account" id="from_account" class="common-select2" style="width: 100% !important" required>
                                                    <option value="">Select...</option>
                                                    @foreach ($modes as $mode)
                                                    <option value="{{$mode->id}}">{{$mode->title}}</option>

                                                    @endforeach

                                                </select>
                                                <small id="pay_available_balance" class="text-danger"></small>
                                                @error('from_account')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-2 changeColStyle search-item-pi">
                                                <label for="">To Account</label>
                                                <select name="to_account" id="to_account" class="common-select2" style="width: 100% !important" required>
                                                    <option value="">Select...</option>
                                                    @foreach ($modes as $mode)
                                                    <option value="{{$mode->id}}">{{$mode->title}}</option>

                                                    @endforeach
                                                </select>
                                                @error('to_account')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-2 changeColStyle">
                                                <label for="">Date</label>
                                                <input type="text" value="{{ Carbon\Carbon::now()->format('d/m/Y') }}" class="form-control inputFieldHeight datepicker" name="date" placeholder="dd/mm/yyyy" required>
                                                @error('date')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-2">
                                                <label for="">Amount</label>
                                                <input type="number" step="any" name="amount" id="amount" class="form-control inputFieldHeight" placeholder="Amount" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="">Transection Cost</label>
                                                <input type="number" step="any" name="transaction_cost" id="transaction_cost" class="form-control inputFieldHeight" placeholder="Transaction Cost">
                                            </div>

                                            <div class="col-md-2">
                                                <label for="">Transection Number</label>
                                                <input type="text" step="any" name="transaction_number" id="transaction_number" class="form-control inputFieldHeight" placeholder="Transaction Number">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="cardStyleChange">
                                    <div class="card-body bg-white">
                                        <div class="row px-1">
                                            <div class="col-md-3">
                                                <label for="">Payment Account</label>
                                                <select name="paid_by" id="paid_by" disabled class="common-select2" style="width: 100% !important" data-target="" required>
                                                    <option value="">Select...</option>
                                                    @foreach ($employee as $item)
                                                        <option value="{{ $item->id }}" > {{ $item->full_name .'('. $item->code .')' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('paid_by')
                                                    <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label for="">Notes</label>
                                                <input type="text" name="note" id="note" class="form-control inputFieldHeight" placeholder="note">
                                            </div>
                                            <div class="col-sm-3 form-group">
                                                <label for="">Doucuments/Files</label>
                                                <input type="file" class="form-control inputFieldHeight" name="voucher_scan[]" multiple accept="image/*">
                                            </div>
                                            <div class="col-sm-12 text-right d-flex justify-content-center mt-1">
                                                <button type="submit" class="btn btn-primary formButton " id="submitButton">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img  src="{{asset('assets/backend/app-assets/icon/save-icon.png')}}" alt="" srcset=""  width="25">
                                                        </div>
                                                        <div><span>Save</span></div>
                                                    </div>
                                                </button>
                                                <a href="{{route("fund-allocation.index")}}" class="btn btn-warning  d-none" id="newButton">New</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
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
<script>
    $(document).on("click", ".allocation-show", function(e) {
        e.preventDefault();
        var url= $(this).attr('href');
        $.ajax({
            url:url,
            type: "get",
            cache: false,

            success: function(response){
                document.getElementById("voucherPreviewShow").innerHTML = response;
                $('#voucherPreviewModal').modal('show');
            }
        });
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
                if(response !=2){
                    $('#new_invoice').modal('hide');
                    document.getElementById("voucherPreviewShow").innerHTML = response;
                    $('#voucherPreviewModal').modal('show');
                }else{
                    toastr.error('Sum of Amount and Transection Cost should not Exceed Balance!', "Warning");
                }
                // $("#submitButton").prop("disabled", true)
                // $("#newButton").removeClass("d-none")
                // $("#submitButton").addClass("d-none")
            },
            error: function(err) {
                let error = err.responseJSON;
                $.each(error.errors, function(index, value) {
                toastr.error(value, "Error");
                });
            }
        });
    });
    $(document).on("change", "#date_search", function(e) {
        e.preventDefault();
        $('#date_to_search').val('');
        $('#date_from_search').val('');

    });
    $(document).on("change", "#date_to_search,#date_from_search", function(e) {
        e.preventDefault();
        $('#date_search').val('');

    });
    var pay_available_balance = 0;
    $(document).on('change', '#from_account', function(e){
        e.preventDefault();
        var pay_mode = $("#from_account option:selected").text();
        var _token = $('input[name="_token"]').val();
        if(pay_mode != 'Credit'){
            $.ajax({
                url: "{{ route('available-pay-amount') }}",
                method: "POST",
                data: {
                    pay_mode: pay_mode,
                    _token: _token,
                },
                success: function(response) {
                    $("#pay_available_balance").html(pay_mode+': ' + response);
                    pay_available_balance = response;
                    // $("#amount").attr({ "max" : response, })
                }
            })
        }else{
            $("#pay_available_balance").html('');
        }
    });
    $(document).on('change', '#to_account', function(e){
        var to_account = $(this).val();
        if (to_account == 6) {
            $('#paid_by').attr('required', true);
            $('#paid_by').attr('disabled', false);
        } else {
            $('#paid_by').attr('required', false);
            $('#paid_by').attr('disabled', true);
        }
    })
</script>
@endpush

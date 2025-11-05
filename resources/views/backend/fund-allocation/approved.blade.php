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
        @media print{
            .nav.nav-tabs ~ .tab-content {
                border-left: 1px solid #ffffff;
                border-right: 1px solid #ffffff;
                border-bottom: 1px solid #ffffff;
                padding-left: 0;
            }
            tr th{
                color: black !important;
            }
        }
    </style>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <input type="hidden" name="standard_vat_rate" value="{{$standard_vat_rate}}"  id="standard_vat_rate">

                @include('clientReport.accounting._header', ['activeMenu' =>'fund-allocation'])
                <div class="tab-content journaCreation active">
                    <div id="journaCreation" class="tab-pane bg-white active">

                        <div class="py-1 px-1 print-hideen">
                            @include('backend.fund-allocation.submenu', [
                                'activeMenu' => 'list',
                            ])
                        </div>

                        <section id="widgets-Statistics">
                            <div class="cardStyleChange p-1" >
                                <div class="card-body bg-white">
                                    <form action="" class=" print-hideen">
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
                                                                    <img src="{{asset('/icon/search-icon.png')}}" width="25">
                                                                </div>
                                                                <div><span>Search</span></div>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-right">
                                                <button type="button" class="btn btn-primary btn_create formButton" title="Print" style="padding-top: 6px;padding-bottom: 6px;" onclick="window.print()">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{asset('/icon/print-icon.png')}}" width="25">
                                                        </div>
                                                        <div><span>Print</span></div>
                                                    </div>
                                                </button>
                                                {{-- <!-- Right Side (Export/Import) -->
                                                <div class="dropdown print-hideen mb-2">
                                                    <button class="btn btn-info inputFieldHeight formButton dropdown-toggle"
                                                        type="button" id="exportDropdown" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false"
                                                        style="padding:4px 15px !important;">
                                                        Export / Import
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="exportDropdown">
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            onclick="exportToExcel('expense')">Excel Export</a>
                                                        <a class="dropdown-item" href="javascript:void(0);"
                                                            onclick="window.print()">Print</a>
                                                        <a class="dropdown-item" href="#" data-toggle="modal"
                                                            data-target="#excel_import">Excel Import</a>
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </form>
                                    <div class="print-content text-center">
                                        <div class="conpany-header">
                                            @include('layouts.backend.partial.modal-header-info')
                                        </div>
                                        <h4>Fund Allocation List</h4>
                                    </div>
                                    <div class="mt-2" style="height: 500px; overflow-y:auto;">
                                        <table class="table table-bordered table-responsive-sm table-sm ">
                                            <thead class="thead" style="position: sticky; top:-2px; z-index:99;">
                                                <tr >
                                                    <th style="width: 100px;">Date</th>
                                                    <th> From Account </th>
                                                    <th> To Account </th>
                                                    <th> Payment Account </th>
                                                    <th style="width: 150px;text-align:right !important;"> Amount </th>
                                                    <th style="width: 150px;text-align:right;"> Transaction Cost </th>
                                                    <th style="min-width: 150px;text-align:right;"> Transaction Number </th>
                                                </tr>
                                            </thead>
                                            <tbody id="purch-body">
                                                @foreach ($allocations as $item)
                                                    <tr class="allocation-show" href="{{route('fund-allocation.show',$item)}}">
                                                        <td class="text-center">{{date('d/m/Y', strtotime($item->date))}}</td>
                                                        <td class="text-center">{{$item->fromAccount->title}}</td>
                                                        <td class="text-center">{{$item->toAccount->title}}</td>
                                                        <td class="text-center">{{$item->payment_account->full_name??''}}</td>
                                                        <td class="text-right">{{number_format($item->amount,2)}}</td>
                                                        <td class="text-right">{{number_format($item->transaction_cost,2)}}</td>
                                                        <td class="text-center">{{$item->transaction_number}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    {{$allocations->links()}}
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
            if(response){
                $('#new_invoice').modal('hide');
                document.getElementById("voucherPreviewShow").innerHTML = response;
                $('#voucherPreviewModal').modal('show');
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

</script>
@endpush

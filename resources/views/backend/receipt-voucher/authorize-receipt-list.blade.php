@extends('layouts.backend.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@section('content')
@include('layouts.backend.partial.style')
<style>
    .changeColStyle span{
        min-width: 16%;
    }
    .changeColStyle .select2-container--default .select2-selection--single .select2-selection__arrow b{
        display: none;
    }
    .journaCreation{
        background: #1214161c;
    }
    .transaction_type{
        padding-right:5px;
        padding-left:5px;
        padding-bottom:5px;
    }
    @media only screen and (max-width: 1500px) {
        .custome-project span{
            max-width: 140px;
        }
    }

    thead {
        background: #34465b;
        color: #fff !important;
    }
    th{
        color: #fff !important;
        font-size: 11px !important;
        height: 25px !important;
        text-align: center !important;
    }
    td
    {
        font-size: 12px !important;
        height: 25px !important;
        text-align: center;
    }

    .table-sm th, .table-sm td {
        padding: 0rem;
    }
    tr:nth-child(even) {background-color: #f2f2f2;}

</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.receipt._header',['activeMenu' => 'receipt_voucher'])
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active">
                    <div class="py-1 px-1">
                        @include('backend.receipt-voucher._subhead_receipt_voucher', [
                            'activeMenu' => 'authorize',
                        ])
                    </div>
                    <section id="widgets-Statistics">

                            <div class="col-md-12" style="padding-left: 23px;">
                                <div class="row">
                                    <div class="cardStyleChange" style="width: 100%">
                                        <div class="card-body bg-white">
                                            <div class="row">
                                                <div class="col-3">
                                                    <input type="text" name="search" id="search"
                                                        class="form-control inputFieldHeight"
                                                        placeholder="Search by Receipt No">
                                                </div>
                                                <div class="col-3">
                                                    <select name="party_search" id="party_search"
                                                        class="common-select2 inputFieldHeight w-100">
                                                        <option value="" style="width: 200px !important;">Select Party...</option>
                                                        @foreach ($parties as $party)
                                                            <option value="{{ $party->id }}">{{ $party->pi_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                                <div class="col-3">
                                                    <input type="text" name="date_search" id="date_search"
                                                        class="form-control inputFieldHeight datepicker"
                                                        placeholder="Search by Date">
                                                </div>


                                                <div class="col-3">
                                                    <select name="mode_search" id="mode_search"
                                                    class="common-select2 inputFieldHeight w-100">
                                                    <option value="">Pay Mode...</option>
                                                    @foreach ($modes as $mode)
                                                        <option value="{{ $mode->title }}">{{ $mode->title }}</option>
                                                    @endforeach
                                                </select>
                                                </div>
                                            </div><br>
                                            <table class="table table-bordered table-sm ">
                                                <thead class="thead">
                                                    <tr >
                                                        <th  style="width: 7%">SL No</th>
                                                        <th  style="width: 12%">Date</th>
                                                        <th  style="width: 12%">Receipt No</th>
                                                        <th style="width: 25%">Party Name</th>
                                                        <th  style="width: 24%">Narration</th>
                                                        <th  style="width: 10%"  >Amount</th>
                                                        <th style="width: 10%">Pay Mode</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="receipt-body">
                                                    @foreach ($receipt_list as $key => $item)
                                                    <tr class="receipt_exp_view"  id="{{$item->id}}">
                                                        <td>{{$key+1}}</td>
                                                        <td>{{date('d/m/Y',strtotime($item->date))}}</td>

                                                        <td>{{$item->receipt_no}}</td>
                                                        <td>   {{ $item->name==null?  $item->party->pi_name : $item->name}}</td>
                                                        <td>{{$item->narration}}</td>
                                                        <td >{{$item->total_amount}}</td>
                                                        <td >{{$item->pay_mode}}</td>

                                                    </tr>

                                                    @endforeach
                                                </tbody>

                                            </table>
                                        </div>
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
{{-- modal --}}
    <!-- END: Content-->
    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div id="voucherPreviewShow">

            </div>
          </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="voucherDetailsPrintModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
<script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/select/form-select2.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/form-repeater.js"></script>
{{-- js work by mominul start --}}

<script>
       $(document).on("click", ".receipt_exp_view", function(e) {
        e.preventDefault();
        var id= $(this).attr('id');
		$.ajax({
			url: "{{URL('temp-receipt-voucher-preview')}}",
			type: "post",
			cache: false,
			data:{
				_token:'{{ csrf_token() }}',
                id:id,
			},
			success: function(response){
                document.getElementById("voucherPreviewShow").innerHTML = response;
                $('#voucherPreviewModal').modal('show')
                $(".datepicker").datepicker({
                dateFormat: "dd/mm/yy"
            });
			}
		});
	});


    $('#search').keyup(function() {
            if ($(this).val() != '') {
                var value = $(this).val();
                var party = $('#party_search').val();
                var date = $('#date_search').val();
                var mode = $('#mode_search').val();

                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-receipt-voucher-temp') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date:date,
                        mode:mode,
                        _token: _token,
                        status: 0,
                    },
                    success: function(response) {
                        $("#receipt-body").empty().append(response);
                    }
                })
            }
        });

        $('#party_search').change(function() {
            if ($(this).val() != '') {
                var party = $(this).val();
                var value = $('#search').val();
                var date = $('#date_search').val();
                var mode = $('#mode_search').val();

                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-receipt-voucher-temp') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date:date,
                        mode:mode,
                        _token: _token,
                        status: 0,
                    },
                    success: function(response) {
                        $("#receipt-body").empty().append(response);
                    }
                })
            }
        });

        $('#date_search').change(function() {
            if ($(this).val() != '') {
                var date = $(this).val();
                var value = $('#search').val();
                var party = $('#party_search').val();
                var mode = $('#mode_search').val();

                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-receipt-voucher-temp') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date:date,
                        mode:mode,
                        _token: _token,
                        status: 0,
                    },
                    success: function(response) {
                        $("#receipt-body").empty().append(response);
                    }
                })
            }
        });

        $('#mode_search').change(function() {
            if ($(this).val() != '') {
                var party = $('#party_search').val();
                var value = $('#search').val();
                var date = $('#date_search').val();
                var mode = $(this).val();

                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-receipt-voucher-temp') }}",
                    method: "POST",
                    data: {
                        value: value,
                        party: party,
                        date:date,
                        mode:mode,
                        _token: _token,
                        status: 0,
                    },
                    success: function(response) {
                        $("#receipt-body").empty().append(response);
                    }
                })
            }
        });
</script>
@endpush

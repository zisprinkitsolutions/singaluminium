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
        text-align: center !important;
    }

    .table-sm th, .table-sm td {
        padding: 0rem;
    }

    tr:nth-child(even) {
        background-color: #c8d6e357;
    }
    tr{
    cursor: pointer;
}
@media print{
    .tab-content {
    border-left: none !important;
    border-right: none !important;
    border-bottom: none !important;
    padding-left: 0;
}
th{
    color: black !important;
}
}
</style>
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.sales._header',['activeMenu' => 'list'])
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active">
                    <div class="py-1 px-1 print-hideen">
                        @include('clientReport.sales._subhead_sale_list', [
                            'activeMenu' => 'transections',
                        ])
                    </div>
                    <section id="widgets-Statistics" style="padding-left: 8px;">
                        @include('layouts.backend.partial.modal-header-info')

                            <div class="col-md-12">
                                <div class="row ">
                                    <div class="cardStyleChange" >
                                        <div class="card-body bg-white">
                                            <div class="row print-hideen">
                                                <div class="col-md-4">
                                                    <input type="hidden" name="search" id="search"
                                                        class="form-control inputFieldHeight"
                                                        placeholder="Search by Bill No, Invoice No">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="hidden" name="date_search" id="date_search"
                                                        class="form-control inputFieldHeight datepicker"
                                                        placeholder="Search by Date">
                                                </div>

                                                <div class="col-md-4">

                                                        <select name="party_search" id="party_search"
                                                        class="common-select2 inputFieldHeight w-100">
                                                        <option value="">Select Party...</option>
                                                        @foreach ($parties as $party)
                                                            <option value="{{ $party->id }}">{{ $party->pi_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn-secondary btn-sm btn" onclick="window.print()"> Print </button>                                                </div>
                                            </div><br>

                                            <table class="table table-bordered table-sm " style="width: 950px">
                                                <thead class="thead">
                                                    <tr >
                                                        <th >Date</th>
                                                        <th >Invoice/Receipt No</th>
                                                        <th >Party Name</th>
                                                        <th >Amount <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                                        <th >Type</th>
                                                        <th>Balance</th>
                                                    </tr>
                                                </thead>

                                                    <tbody id="purch-body">
                                                        @php
                                                        $balance=0;
                                                    @endphp
                                                    @foreach ($transections as $item)
                                                    <tr class="{{$item->invoice_type=='Receipt'? 'receipt_exp_view':'sale_view'}}"  id="{{$item->id}}">
                                                        <td>{{date('d/m/Y',strtotime($item->date))}}</td>
                                                        <td>{{$item->transection_no}}</td>
                                                        <td>{{App\PartyInfo::find($item->party_id)->pi_name}}</td>
                                                        <td>{{number_format($item->amount,2)}}</td>
                                                        <td>{{$item->invoice_type}}</td>
                                                        @php
                                                            if($item->invoice_type=='Receipt')
                                                            {
                                                                $balance -=$item->amount;
                                                            }
                                                            else {
                                                                $balance +=$item->amount;
                                                            }
                                                        @endphp
                                                        <td>{{number_format($balance,2)}}</td>
                                                    </tr>

                                                    @endforeach
                                                    <tr>
                                                        <td colspan="5" class="text-right"><strong>Total Balance</strong></td>
                                                        <td><strong>{{number_format($balance,2)}}</strong></td>
                                                    </tr>
                                                </tbody>

                                            </table>
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
    @include('layouts.backend.partial.modal-footer-info')

@endsection
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/select/form-select2.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js"></script>
<script src="{{ asset('assets/backend')}}/app-assets/js/scripts/forms/form-repeater.js"></script>
{{-- js work by mominul start --}}

<script>
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
                document.getElementById("voucherPreviewShow").innerHTML = response;
                $('#voucherPreviewModal').modal('show')
			}
		});
	});

    $(document).on("click", ".receipt_exp_view", function(e) {
        e.preventDefault();
        var id= $(this).attr('id');
		$.ajax({
			url: "{{URL('receipt-list-modal')}}",
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

                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-all-transection-list') }}",
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

                var party = $(this).val();
                var value = $('#search').val();
                var date = $('#date_search').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-all-transection-list') }}",
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

        });

        $('#date_search').change(function() {
            if ($(this).val() != '') {
                var date = $(this).val();
                var value = $('#search').val();
                var party = $('#party_search').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('search-all-transection-list') }}",
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

</script>
@endpush

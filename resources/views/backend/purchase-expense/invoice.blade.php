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
    }

    .table-sm th, .table-sm td {
        padding: 0rem;
    }

    tr:nth-child(even) {
        background-color: #c8d6e357;
    }
</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.purchase._header',['activeMenu' => 'invoice'])
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active">

                    <section id="widgets-Statistics ">
                        <div class="cardStyleChange" style="max-width::200px !important">
                            <div class="row mx-1" >
                                <div class="col-md-9 mt-1">
                                    <form action="">
                                        <div class="row col-left-padding">
                                            <div class="col-7 col-left-padding">
                                                <select name="party" id="party" class="common-select2 w-100" >
                                                    <option value="">Select Party</option>
                                                    @foreach ($parties as $party)
                                                    <option value="{{$party->id}}">{{$party->pi_name}}-(@if(!empty($currency->licence_name)){{$currency->licence_name}} @endif {{$party->trn_no}})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-5">
                                                <input type="text" class="form-control inputFieldHeight" placeholder="Invoice Number" id="invoice_no" name="invoice_no">

                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-3 d-flex align-items-center">
                                    <input type="text" class="form-control datepicker date inputFieldHeight" placeholder="Date" autocomplete="off" name="date" id="">

                                </div>

                                <div class="col-md-12 col-left-padding mt-1">
                                    <table class="table table-bordered table-sm ">
                                        <thead class="thead">
                                            <tr>
                                                <th style="width: 12%">Date</th>
                                                <th  style="width: 22%">Purchase No</th>
                                                <th style="width: 22%">Party Name</th>
                                                <th  style="width: 22%">Invoice No</th>
                                                <th  style="width: 22%"  >Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-body">
                                            @foreach ($invoicess as $item)
                                            <tr class="purch_exp_view"  id="{{$item->purchase->id}}" style="text-align:center;">
                                                <td>{{date('d/m/Y',strtotime($item->purchase->date))}}</td>

                                                <td>{{$item->purchase->purchase_no}}</td>
                                                <td>{{$item->purchase->party->pi_name}}</td>
                                                <td>{{$item->invoice_no}}</td>
                                                <td>{{$item->Total_amount}}</td>
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
       $(document).on("click", ".purch_exp_view", function(e) {
        e.preventDefault();
        var id= $(this).attr('id');
		$.ajax({
			url: "{{URL('purch-exp-modal')}}",
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


    $(document).on("change", "#party", function(e) {
        e.preventDefault();
        $('.date').val('')
        var id= $(this).val();
        var invoice_no= $('#invoice_no').val();
		$.ajax({
			url: "{{URL('find-invoice')}}",
			type: "post",
			cache: false,
			data:{
				_token:'{{ csrf_token() }}',
                id:id,
                invoice_no:invoice_no,
			},
			success: function(response){

                $('#table-body').empty().append(response);
			}
		});
	});

    $(document).on("keyup", "#invoice_no", function(e) {
        e.preventDefault();
        $('.date').val('')

        var invoice_no= $(this).val();
        var id= $('#party').val();
		$.ajax({
			url: "{{URL('find-invoice')}}",
			type: "post",
			cache: false,
			data:{
				_token:'{{ csrf_token() }}',
                id:id,
                invoice_no:invoice_no,
			},
			success: function(response){

                $('#table-body').empty().append(response);
			}
		});
	});

    $(document).on("change", ".date", function(e) {
        e.preventDefault();
        $('#invoice_no').val('');
        $('#party').val('');
        var date= $(this).val();
		$.ajax({
			url: "{{URL('find-invoice-date')}}",
			type: "post",
			cache: false,
			data:{
				_token:'{{ csrf_token() }}',
                date:date,
			},
			success: function(response){

                $('#table-body').empty().append(response);
                $('.common-select2').select2();
			}
		});
	});
</script>
@endpush

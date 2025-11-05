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
tr{
    cursor: pointer;
}
</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.purchase._header',['activeMenu' => 'payments'])
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active">

                    <section id="widgets-Statistics">

                         <div class="row mx-1">
                            <div class="col-md-12 col-left-padding">
                                <table class="table table-bordered table-sm mt-2" style="width:805px">
                                    <thead class="thead">
                                        <tr >
                                            <!-- <th  style="width: 6%">#</th> -->
                                            <th  style="width: 13%">Payment No</th>
                                            <th style="width: 35%">Payee</th>
                                            <th  style="width: 30%">Narration</th>
                                            <th  style="width: 22%"  >Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payments as $item)
                                        <tr class="purch_exp_view"  id="{{$item->id}}">
                                            <!-- <td>{{++$i}}</td> -->
                                            <td>{{$item->payment_no}}</td>
                                            <td>{{$item->party->pi_name}}</td>
                                            <td>{{$item->narration}}</td>
                                            <td>{{number_format($item->total_amount,2)}}</td>
                                        </tr>

                                        @endforeach
                                    </tbody>

                                </table>
                                {!! $payments->links()!!}
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
			url: "{{URL('payment-modal')}}",
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
</script>
@endpush

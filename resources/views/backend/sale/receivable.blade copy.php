
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

        tr:nth-child(even) {
            background-color: #c8d6e357;
        }

        tr {
            cursor: pointer;
        }
    </style>
    <div class="app-content content print-hideen">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.sales._header', ['activeMenu' => 'receivable'])
                <div class="tab-content journaCreation">
                    <div id="journaCreation" class="tab-pane bg-white active">

                        <section id="widgets-Statistics">

                            <div class="row " style="width: 805px">

                                <div class="col-md-12 px-2">

                                    <div class="cardStyleChange" style="width: 100%">
                                        <div class="card-body bg-white">
                                            <div class="row">

                                                <div class="col-6">
                                                    {{-- <select name="party_search" id="party_search"
                                                        class="common-select2 inputFieldHeight w-100">
                                                        <option value="">Select Party...</option>
                                                        @foreach ($suppliers as $party)
                                                            <option value="{{ $party->id }}">{{ $party->pi_name }}
                                                            </option>
                                                        @endforeach
                                                    </select> --}}
                                                </div>


                                            </div><br>

                                            <table class="table table-bordered table-sm " style="width: 805px">
                                                <thead class="thead">
                                                    <tr>
                                                        <th>Party / Owner Name</th>
                                                        <th>Project Name</th>
                                                        <th >Project Code</th>
                                                        <th>Contract Amount</th>
                                                        <th>Total Invoice Submited</th>
                                                        <th>Total Received</th>
                                                        <th>Recevivable</th>
                                                        <th>Expected Receivable</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="purch-body">
                                                    @foreach ($projects as $project)
                                                        <tr class="receivable-view" id="{{ $project->id }}"
                                                            style="text-align:center;">
                                                            <td>{{ $project->party->pi_name}}</td>
                                                            <td>{{$project->project_name}}</td>
                                                            <td>{{number_format($project->due_amount,2)}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                        </div>
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
    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div id="receivable_modal_content">

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
    {{-- js work by mominul start --}}

    <script>
   $(document).on("click", ".receivable-view", function(e) {
        e.preventDefault();
        var id= $(this).attr('id');
		$.ajax({
			url: "{{URL('receivable-view')}}",
			type: "post",
			cache: false,
			data:{
				_token:'{{ csrf_token() }}',
                id:id,
			},
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


    </script>
@endpush


@extends('layouts.backend.app')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@section('content')
@include('layouts.backend.partial.style')
<style>
    .changeColStyle span{
        width: 213px !important;
    }
    .changeColStyle .select2-container--default .select2-selection--single .select2-selection__arrow b{
        display: none;
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

    tr {
        /* cursor: pointer; */
        cursor: initial;
    }
</style>
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.accounting._header',['activeMenu' => 'jouranal-approve'])
            <div class="tab-content bg-white">
                <style>
                    .pl-3, .px-3 {
                        padding-left: 2.4rem !important;
                    }
                </style>
                <div id="journalAuthorization" class="tab-pane active pt-1">
                    <div class="row">
                        <div class="col-6 pl-3">

                        </div>
                        <div class="col-md-6 text-right">
                            {{-- <a href="#" class="btn btn-xs formButton mExcelButton mr-2" onclick="exportTableToCSV('journal.csv')">
                                <img  src="{{asset('assets/backend/app-assets/icon/excel-icon.png')}}" alt="" srcset="" class="img-fluid" width="30">
                                Excel
                            </a> --}}
                            <!-- Right Side (Export/Import) -->
                            <div class="dropdown print-hideen mb-2 mr-2">
                                <button class="btn btn-info inputFieldHeight formButton dropdown-toggle"
                                    type="button" id="exportDropdown" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"
                                    style="padding:4px 15px !important;">
                                    Export / Import
                                </button>
                                <div class="dropdown-menu" aria-labelledby="exportDropdown">
                                    <a class="dropdown-item" href="javascript:void(0);"
                                        onclick="exportTableToCSV('journal.csv')">Excel Export</a>
                                    {{-- <a class="dropdown-item" href="javascript:void(0);"
                                        onclick="exportToExcel('expense')">Excel Export</a>
                                    <a class="dropdown-item" href="javascript:void(0);"
                                        onclick="window.print()">Print</a>
                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                        data-target="#excel_import">Excel Import</a> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    </section>

                    <section>
                        <div class="mx-2">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="thead">
                                    <tr class="mTheadTr">
                                        <th style="width:15%">Date</th>
                                        <th style="width:20%">Journal No</th>
                                        {{-- <th>Voucher Type</th> --}}
                                        <th style="width:40%" class="text-left pl-1">Narration</th>
                                        <th style="width:20%" class="text-right pr-1">Amount</th>
                                        <th style="width:5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="user-table-body">
                                    @foreach ($journals as $journal)
                                        <tr class="text-center trFontSize">
                                            <td>{{ \Carbon\Carbon::parse($journal->date)->format('d/m/Y')}} </td>
                                            <td>
                                                <a href="#" class="btn journalApprovalShowId trFontSize" style="font-size: 12px !important"  title="Preview" data-id="{{$journal->id}}">
                                                    {{ $journal->journal_no }}

                                                </a>
                                            </td>                                            {{-- <td class="pl-2">{{ $journal->voucher_type->type == 'DR' ? 'DEBIT' : ($journal->voucher_type->type=='CR' ? 'CREDIT' : 'JOURNAL') }}</td> --}}
                                            <td class="text-left pl-1">{{ $journal->narration }}</td>
                                            <td class="text-right pr-1">@if(!empty($currency->symbole)){{$currency->symbole}}@endif {{ number_format($journal->amount,2) }}</td>
                                            <td style="padding-bottom: 11px; padding-top: 0px">
                                                <div class="d-flex justify-content-center">
                                                    <a href="#" class="btn journalApprovalShowId" style="height: 25px; width: 25px;" title="Preview" data-id="{{$journal->id}}">
                                                        <img src="{{ asset('assets/backend/app-assets/icon/view-icon.png')}}" style=" height: 25px; width: 25px;">
                                                    </a>
                                                    <a href="{{ route('journalDelete', $journal) }}"  onclick="return confirm('about to delete journal. Please, Confirm?')" class="btn" style="height: 25px; width: 25px;" title="Delete">
                                                        <img src="{{ asset('assets/backend/app-assets/icon/delete-icon.png')}}" style=" height: 25px; width: 25px; margin-left: -20px;">
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- modal --}}
    <div class="modal fade bd-example-modal-lg" id="journalApprovalModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div id="journalApprovalShow">

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
    $(document).on("click", ".journalApprovalShowId", function(e) {
        e.preventDefault();
        var id= $(this).data('id');
        console.log(id);
		$.ajax({
			url: "{{URL('journal-approval-show-modal')}}",
			type: "post",
			cache: false,
			data:{
				_token:'{{ csrf_token() }}',
                id:id,
			},
			success: function(response){
                document.getElementById("journalApprovalShow").innerHTML = response;
                $('#journalApprovalModal').modal('show')
			}
		});
	});
</script>
{{-- js work by mominul end --}}

@endpush



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
</style>
<style>
    thead {
        background: #34465b;
        color: #fff !important;
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
            @include('clientReport.accounting._header', ['activeMenu' =>'jouranal-creation'])


            <div class="tab-content bg-white">
                <div id="journalAuthorization" class="tab-pane active">


                    <div class="row pt-2">
                        <div class="col-md-12">
                            <p class="text-center">
                                <a href="{{ route('journal_edit', $journal->id)}}" class="btn btn-info">Edit</a>
                                <a href="{{ route('new-journal-creation')}}" class="btn btn-success">Continue Entry</a>
                            </p>
                        </div>
                    </div>
                    <section id="widgets-Statistics">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="">
                                        <div class="ml-2 mb-1 mr-2">
                                            <div class="row">
                                                <div class="col-3">
                                                    <strong>Journal No:</strong>  {{ $journal->journal_no}}
                                                </div>
                                                <div class="col-2">
                                                    <strong>Date:</strong> {{ date('d/m/Y',strtotime($journal->date))}}
                                                </div>
                                                <div class="col-5">
                                                    <strong>Party Name:</strong> {{ $journal->PartyInfo->pi_name??''}}
                                                </div>

                                                <div class="col-2">
                                                    <strong>Amount:</strong> {{$currency->symbole}} {{ number_format($journal->amount,2)}}
                                                </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="border-botton">
                                        <div class="">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead class="thead" style="background:#364a60;">
                                                        <tr class="text-center mTheadTr trFontSize">
                                                            <th style="color: #fff" class="text-left pl-1">A/C Head</th>
                                                            <th style="color: #fff" class="text-left pl-1">Description</th>
                                                            <th style="color: #fff">Invoice No</th>
                                                            <th style="color: #fff">Debit</th>
                                                            <th style="color: #fff">Credit</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody class="user-table-body">
                                                            @php
                                                                $rowcount=$journal->records->count();
                                                            @endphp
                                                            @foreach ($journal->records as $record)
                                                            <tr class="text-center trFontSize">
                                                                <td style="border-bottom: none;" class="text-left pl-1">{{ $record->ac_sub_head?$record->ac_sub_head->name:$record->account_head }}</td>
                                                                <td style="border-bottom: none;" class="text-left pl-1">{{ $record->description }}</td>
                                                                <td style="border-bottom: none;">{{ $record->invoice_no }}</td>

                                                                <td style="border-bottom: none;">{{ ($record->transaction_type=='DR') ? $currency->symbole .' '.number_format($record->amount,2) : ''  }}</td>
                                                                <td style="border-bottom: none;">{{ ($record->transaction_type=='CR') ? $currency->symbole .' '.number_format($record->amount,2) : ''  }}</td>

                                                            </tr>
                                                            @endforeach
                                                            <tr class="border-bottom">
                                                                <td colspan="4" class="text-center"> ( {{$journal->narration}} ) </td>

                                                            </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <div class="row pt-4">
                            <div class="col-12 text-center">
                                <h3>Supporting Document</h3>
                            </div>
                            @if($journal->documents->count() > 0)
                                @foreach ($journal->documents as $document)
                                <div class="col-md-2 text-center py-1 px-4 print-hideen document-file" id="document-{{$document->id}}">
                                    <button class="remove-document py-1 d-none" id={{$document->id}}>
                                        <i class="bx bx-trash text-danger"></i>
                                    </button>
                                    @if ($document->ext=='pdf')
                                    <a href="{{ asset('storage/upload/journal-entry-expense/' . $document->file_name) }}" target="blank">
                                        <img src="{{asset('icon/pdf-download-icon-2.png')}}" class="img-fluid" style="width:100%;" alt="{{$document->ext}}">
                                    </a>
                                    @else
                                    <a href="{{ asset('storage/upload/journal-entry-expense/' . $document->file_name) }}" target="blank">
                                        <img src="{{ asset('storage/upload/journal-entry-expense/' . $document->file_name) }}" class="img-fluid" style="width:100%;" alt="{{$document->ext}}">
                                    </a>
                                    @endif
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- modal --}}
    <div class="modal fade bd-example-modal-lg" id="journalAuthorizeModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div id="journalAuthorizeShow">

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
    $(document).on("click", ".journalAuthorizeShowId", function(e) {
        e.preventDefault();
        var id= $(this).attr('id');
        console.log(id);
		$.ajax({
			url: "{{URL('journal-authorize-show-modal')}}",
			type: "post",
			cache: false,
			data:{
				_token:'{{ csrf_token() }}',
                id:id,
			},
			success: function(response){
                document.getElementById("journalAuthorizeShow").innerHTML = response;
                $('#journalAuthorizeModal').modal('show')
			}
		});
	});
</script>
{{-- js work by mominul end --}}

@endpush


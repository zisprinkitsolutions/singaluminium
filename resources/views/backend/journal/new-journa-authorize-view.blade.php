<style>
    thead {
        background: #34465b;
        color: #fff !important;
        height: 30px;
    }

    .print-view {
        display: none;
    }

    @media print {
        .print-view {
            display: block;
        }
    }
</style>

<section class="print-hideen border-bottom" style="padding: 5px 15px;background: #34465b;">
    <div class="d-flex flex-row-reverse" style="padding: 0 11px;">
        <div class="mIconStyleChange" style="padding: 10px 2px !important;"><a href="#"
                class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i
                        class='bx bx-x'></i></span></a></div>
        {{-- <div class="mIconStyleChange"><a href="#" class="btn btn-icon btn-success"><i class="bx bx-edit"></i></a></div> --}}
        {{-- <div class="mIconStyleChange" style="padding: 10px 2px !important;"><a href="#" onclick="window.print();"
                class="btn btn-icon btn-success" title="Print"><i class='bx bx-printer'></i></a></div>
        <div class="mIconStyleChange" style="padding: 10px 2px !important;"><a
                href="{{ route('tem-journal-view-pdf', $journal->id) }}" class="btn btn-icon btn-primary"
                title="PDF"><i class='bx bxs-file-pdf'></i></a></div> --}}
        {{-- <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
        <div class="w-100">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">Journal</h4>
        </div>
    </div>
</section>
<div class="print-view">
    @include('layouts.backend.partial.modal-header-info')
</div>

<section id="widgets-Statistics" class="px-2">
    <div class="cardStyleChange">

        <div class="card-body p-0 pt-1">
            <div class="row">
                <div class="col-md-12 text-center invoice-view-wrapper student_profle-print">
                    <h2>Journal</h2>
                </div>
                <div class="col-3">
                    <strong>Journal No:</strong> {{ $journal->journal_no }}
                </div>

                <div class="col-2">
                    <strong>Date:</strong> {{ date('d/m/Y', strtotime($journal->date)) }}
                </div>

                <div class="col-2">
                    <strong>Payment Mode:</strong> {{ $journal->pay_mode }}
                </div>
                <div class="col-3">
                    <strong>Party Name:</strong> {{ $journal->PartyInfo->pi_name ?? '' }}
                </div>

                <div class="col-2">
                    <strong>Amount:</strong>
                    @if (!empty($currency->symbole))
                        {{ $currency->symbole }}
                    @endif {{ number_format($journal->amount, 2) }}
                </div>

            </div>
        </div>
    </div>
    <div class="cardStyleChange">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-bordered border-botton">
                    <thead class="thead">
                        <tr>
                            {{-- <th>Date</th> --}}
                            <th class="text-left pl-1">HEAD</th>
                            <th>Invoice No</th>
                            <th>Debit</th>
                            <th>Credit</th>
                        </tr>
                    </thead>

                    <tbody class="user-table-body">
                        @php
                            $rowcount = $journal->records->count();
                        @endphp
                        @foreach ($journal->records()->orderBy('transaction_type', 'DESC')->get() as $record)
                            <tr class="text-center trFontSize">
                                <td class="text-left pl-1">
                                    {{ $record->ac_sub_head ? $record->ac_sub_head->name : $record->account_head }}</td>
                                <td>{{ $record->invoice_no }}</td>

                                <td>{{ $retVal = $record->transaction_type == 'DR' ? $currency->symbole . ' ' . number_format($record->amount, 2) : '' }}
                                </td>
                                <td>{{ $retVal = $record->transaction_type == 'CR' ? $currency->symbole . ' ' . number_format($record->amount, 2) : '' }}
                                </td>
                            </tr>
                        @endforeach
                        <tr class="border-bottom">
                            <td colspan="4" class="text-center"> ( {{ $journal->narration }} ) </td>

                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row d-flex align-items-center justify-content-center mb-1">
                @if ($journal->documents->count() > 0)
                    <div class="col-12 text-center print-hideen">
                        <h3>Supporting Document</h3>
                    </div>
                    @foreach ($journal->documents as $document)
                    <div class="col-md-2 text-center py-1 px-4 print-hideen document-file"
                        id="document-{{ $document->id }}">
                        <button class="remove-document py-1 d-none" id={{ $document->id }}>
                            <i class="bx bx-trash text-danger"></i>
                        </button>
                        @if ($document->ext == 'pdf')
                            <a href="{{ asset('storage/upload/journal-entry-expense/' . $document->file_name) }}"
                                target="blank">
                                <img src="{{ asset('icon/pdf-download-icon-2.png') }}" class="img-fluid"
                                    style="width:100%;" alt="{{ $document->ext }}">
                            </a>
                        @else
                            <a href="{{ asset('storage/upload/journal-entry-expense/' . $document->file_name) }}"
                                target="blank">
                                <img src="{{ asset('storage/upload/journal-entry-expense/' . $document->file_name) }}"
                                    class="img-fluid" style="width:100%;" alt="{{ $document->ext }}">
                            </a>
                        @endif
                    </div>
                    @endforeach
                @endif
                <div class="col-12 d-flex justify-content-center align-items-center print-hideen mt-1">
                    {{-- <button type="submit" class="btn mr-1 btn-info formButton" title="Authorize">
                        <a href="{{ route('journalMakeAuthorize', $journal) }}"
                            onclick="return confirm('about to authorize journal. Please, Confirm?')"
                            class="btn btn-info formButton btn-block">
                            <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}" alt=""
                                srcset="" width="25">
                            Authorize
                        </a>
                    </button> --}}
                    <div class="print-hideen">
                        <a href="{{ route('journalMakeAuthorize', $journal) }}"
                            onclick="return confirm('about to authorize journal. Please, Confirm?')"
                            class="btn btn-info custom-action-btn">
                            <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}" alt=""
                                srcset="" width="20" style="margin-right: 5px;">
                            Authorize
                        </a>
                    </div>
                    <div class="d-flex flex-row-reverse">
                        <div class="print-hideen">
                            <a href="#" onclick="window.print();" class="btn btn-icon btn-secondary custom-action-btn" title="Print Now">
                                <i class='bx bx-printer'></i> Print
                            </a>
                        </div>
                        <div class="print-hideen" >
                            <a href="{{ route('tem-journal-view-pdf', $journal->id) }}" class="btn btn-icon btn-primary custom-action-btn"title="PDF Download">
                                <i class='bx bxs-file-pdf'></i> PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('layouts.backend.partial.modal-footer-info')

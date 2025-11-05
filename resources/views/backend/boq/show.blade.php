<div>
    <div class="modal-content">
        <div class="modal-header" style="padding: 5px 20px; background:#364a60;">
            <h5 class="modal-title text-white" title="Billing OF Quantity"> BOQ </h5>
            <div class="d-flex align-items-center" style="gap: 5px;">
                @if ($boq->status < 2)
                    @if (Auth::user()->hasPermission('ProjectManagement_Delete'))
                        <form action="{{ route('boq.destroy', $boq->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('delete')
                            <button type="submit"
                                style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border: none;"
                                class="btn btn-danger btn-sm"
                                onclick="event.preventDefault(); deleteAlert(this, 'About to delete BOQ info. Please, confirm?');">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
                    @endif
                    @if (Auth::user()->hasPermission('ProjectManagement_Approve'))
                        <a href="{{ route('boq.approve', $boq->id) }}"
                            onclick="event.preventDefault(); deleteAlert(this, 'Are you sure about this process, confirm?','approve');"
                            style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;"
                            class="btn btn-success btn-sm">
                            <i class='bx bx-check'></i>
                        </a>
                    @endif
                    @if (Auth::user()->hasPermission('ProjectManagement_Edit'))
                        <a href="{{ route('boq.edit', $boq->id) }}"
                            style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;"
                            class="btn btn-info btn-sm">
                            <i class="bx bx-edit"></i>
                        </a>
                    @endif
                @endif


                {{-- <a href="{{ route('boq.print', $boq->id) }}" target="_blank"
                    style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;"
                    class="btn btn-success btn-sm">
                    <i class="bx bx-printer"></i>
                </a> --}}

                <button type="button"
                    style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;"
                    class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">
                    <i class='bx bx-x'></i>
                </button>
            </div>
        </div>

        <div class="modal-body" style="padding: 5px 20px;">
            <div class="row">
                <div class="col-md-12">
                    <p title="BOQ = Billing of Quantity"><strong> Party Name :</strong>
                        {{ optional($boq->party)->pi_name }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong> BOQ No :</strong> {{ $boq->boq_no }}</p>
                </div>

                <div class="col-md-4">
                    <p><strong> Date :</strong> {{ date('d/m/Y', strtotime($boq->date)) }}</p>
                </div>


                <div class="col-12">
                    <div class="table-responsive mt-2">
                        <table class="table table-sm">
                            <thead style="background: #ddd !important">
                                <tr>
                                    <th class="" style="color:#444;font-weight:600; width:3%">SL </th>
                                    <th class="text-left" style="color:#444;font-weight:600 ;width: 40%"> Description  </th>
                                    <th class="text-right" style="color:#444;font-weight:600;"> QTY </th>
                                    <th class="text-right" style="color:#444;font-weight:600;"> SQM </th>
                                    <th class="text-right" style="color:#444;font-weight:600;"> Rate </th>
                                    <th class="text-right" style="color:#444;font-weight:600;"> Total </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($boq->items as $key => $task)
                                    <tr>
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td>{{ $task->item_description }}</td>
                                        <td class="text-right">{{$task->qty}}</td>
                                        <td class="text-right" style="padding: 5px 15px !important;"> {{number_format($task->sqm,2)}} </td>
                                        <td class="text-right" style="padding: 5px 15px !important;"> {{number_format($task->rate,2)}} </td>
                                        <td class="text-right" style="padding: 5px 15px !important;"> {{number_format($task->total,2)}} </td>
                                    <tr>
                                @endforeach


                                <tr>
                                    <td colspan="5" class="text-dark text-right" style="border-right:1px solid #ddd;font-size:14px;font-weight:500;">
                                        Amount ({{ $currency->symbole }})
                                    </td>
                                    <td class="text-right text-dark" style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                                        {{ number_format($boq->amount,2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-dark text-right" style="border-right:1px solid #ddd;font-size:14px;font-weight:500;">
                                        VAT ({{ $currency->symbole }})
                                    </td>
                                    <td class="text-right text-dark" style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                                        {{ number_format($boq->vat,2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-dark text-right" style="border-right:1px solid #ddd;font-size:14px;font-weight:500;">
                                        Total Amount ({{ $currency->symbole }})
                                    </td>
                                    <td class="text-right text-dark" style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                                        {{ number_format($boq->total_amount,2) }}
                                    </td>
                                </tr>
                                <tr>
                                    @php
                                        $whole = floor($boq->total_amount);
                                        $fraction = number_format($boq->total_amount  - $whole, 2);
                                        $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                        $amount_in_word = $f->format($whole);
                                        $amount_in_word2 = $f->format($fraction);
                                    @endphp
                                    <td colspan="6" class="text-center text-dark text-capitalize"
                                        style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                                        In Words: {{ $amount_in_word }} Dirhams
                                        @if ($fraction > 0)
                                            {{ '& ' . substr($amount_in_word2, 10) }}
                                        @else
                                        Zero
                                        @endif Fils
                                    </td>
                                </tr>

                                {{-- <tr>
                                    <td colspan="8" class="text-center text-dark" style="border-right:1px solid #f2dede;font-size:14px;font-weight:500;">
                                        Note: 5% VAT will be added to the total amount.(TRN) {{$trn_no}}
                                    </td>
                                </tr> --}}

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


        <div class="d-flex justify-content-center align-items-center mb-2" >
            <a href="{{ route('boq.print', $boq->id) }}" target="_blank"class="btn btn-secondary custom-action-btn" title="Print Now">
                <i class="bx bx-printer"></i> Print
            </a>
        </div>


    </div>
</div>
</div>

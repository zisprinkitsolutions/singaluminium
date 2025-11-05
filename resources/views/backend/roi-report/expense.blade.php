
<section class="print-hideen border-bottom">
    <div class="d-flex flex-row-reverse">
        <div class="py-1 pr-1"><a href="#" class="btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
        <div class="py-1 pr-1"><a href="#" onclick="window.print();" class="btn btn-icon btn-secondary"><i class="bx bx-printer"></i></a></div>

        <div class="py-1 pr-1 w-100 pl-2">
            <h4> {{ $project->project_name}} </h4>
        </div>
    </div>
</section>

<div class="receipt-voucher-hearder invoice-view-wrapper" style="margin:70px 20px 50px 20px; border-radius: 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>


<section id="widgets-Statistics">
    <div class="p-1">
        <div class="invoice-view-wrapper model-report-titles">
            <div class="d-flex justify-content-between invoice-view-wrapper">
                <h5> {{ $project->project_name}} </h5>
                <h5> Investment & Expense {{number_format($expenses->sum('total_amount'),2)}} <small> ({{$currency->symbole}}) </small>  </h5>
            </div>
        </div>


        <div class="report-table table-responsive mt-1">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th class="text-center"> Bill Date </th>
                        <th class="text-center"> Bill Number </th>
                        <th class="text-center"> Supplier </th>
                        <th class="text-center"> Total Amount </th>
                        {{-- <th class="text-center"> Payment Status </th> --}}
                        <th class="text-center"> Bill Description </th>

                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_amount = 0;
                        $paid_amount = 0;
                        $due_amount = 0;
                    @endphp
                    @foreach($expenses as $item)
                    <tr class="item-details" data-id="{{$item->id}}" data-type="expense">
                        <td class="text-center"> {{date('d/m/Y',strtotime($item->bill_info->date))}} </td>
                        <td class="text-center"> {{$item->bill_info->invoice_no}} </td>
                        <td class="text-center"> {{$item->bill_info->party->pi_name}} </td>
                        <td class="text-center"> {{number_format($item->amount,2)}} </td>
                        {{-- @if ($item->bill_info->paid_amount > 0 && $item->bill_info->due_amount > 0)
                        <td class="text-center text-warning"> Payment {{$item->bill_info->paid_amount}} Due {{$item->bill_info->due_amount}}</td>
                        @else
                        <td class="text-center {{$item->bill_info->paid_amount > 0 ? 'text-success' : 'text-danger'}}"> Payment {{$item->bill_info->paid_amount}} Due {{$item->bill_info->due_amount}}</td>
                        @endif --}}
                        <td class="text-center"> {{\Illuminate\Support\Str::limit($item->bill_info->narration, 30, $end='...') }} </td>
                        @php
                            $total_amount += $item->amount;
                            $paid_amount += $item->bill_info->paid_amount;
                            $due_amount += $item->bill_info->due_amount;
                        @endphp
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-right"> Total <small> ({{$currency->symbole}}) </small> </td>
                        <td class="text-center"> {{number_format($total_amount,2)}} </td>

                        {{-- @if ($paid_amount > 0 && $due_amount > 0)
                        <td class="text-center text-warning"> Payment {{number_format($paid_amount,2)}} Due {{number_format($due_amount,2)}} </td>
                        @else
                        <td class="text-center {{$paid_amount > 0 ? 'text-success' : 'text-danger'}}"> Payment {{number_format($paid_amount,2)}} Due {{number_format($due_amount,2)}}</td>
                        @endif --}}

                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="divFooter mb-1 ml-1">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png')}}" alt="" width="150"></span>
    </div>
</section>

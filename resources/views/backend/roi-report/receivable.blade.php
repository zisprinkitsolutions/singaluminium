
<section class="print-hideen border-bottom">
    <div class="d-flex flex-row-reverse">
        <div class="py-1 pr-1"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
        <div class="py-1 pr-1"><a href="#" onclick="window.print();" class="btn btn-icon btn-secondary"><i class="bx bx-printer"></i></a></div>

        <div class="py-1 pr-1 w-100 pl-2">
            <h4> {{ $project->project_name}} </h4>
        </div>
    </div>
</section>

<div class="receipt-voucher-hearder invoice-view-wrapper" style="margin: 50px 20px; border-radius: 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>

<section id="widgets-Statistics">
    <div class="p-1">
        <div class="invoice-view-wrapper model-report-titles">
            <div class="d-flex justify-content-between invoice-view-wrapper">
                <h5> {{ $project->project_name}} </h5>
                <h5 class="text-center"> Receivable {{$receivables->sum('due_amount')+$project->temp_receipt()}} <small> ({{$currency->symbole}}) </small> </h5>
            </div>
        </div>

        <div class="report-table table-responsive mt-1">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th class="text-center"> Invoice Date </th>
                        <th class="text-center"> Invoice Number </th>
                        <th class="text-center"> Customer </th>
                        <th class="text-center"> Total Amount <small>({{$currency->symbole}}) </small> </th>
                        <th class="text-center"> Invoice Description </th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($receivables as $item)
                    <tr class="item-details" data-id="{{$item->id}}" data-type="sale">
                        <td class="text-center"> {{date('d/m/Y',strtotime($item->date))}} </td>
                        <td class="text-center"> {{$item->invoice_no}} </td>
                        <td class="text-center"> {{$item->party->pi_name}} </td>
                        <td class="text-center"> {{$item->due_amount+$item->tem_receipt_amount()}} </td>
                        <td class="text-center"> Invoice from project </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-right"> Total <small> ({{$currency->symbole}}) </small> </td>
                        <td class="text-center"> {{$receivables->sum('due_amount')+$project->temp_receipt()}} </td>
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

<style>
    .row {
        display: flex;
    }

    .col-md-1 {
        max-width: 8.33% !important;
    }

    .col-md-2 {
        max-width: 16.66% !important;
    }

    .col-md-8 {
        max-width: 66.66% !important;
    }

    .col-md-10 {
        max-width: 83.33% !important;
    }

    .col-md-11 {
        max-width: 91.66% !important;
    }

    .customer-static-content {
        background: #ada8a81c;
    }

    .customer-dynamic-content {
        background: #706f6f33;
    }

    .proview-table tr td,
    .proview-table tr th {
        border: 1px solid black !important;
    }

    .customer-dynamic-content2 {
        background: #fff !important;
    }

    .customer-content {
        border: 1px solid black !important;
    }

    .font-bold{
        font-weight: bold;
    }
    p,span{
        color: #333333;
        font-weight: 500;
        font-size:14px;
    }
    .text-lg{
        font-size:16px;
    }
    .text-md{
        font-size: 14px;
    }
    .text-sm{
        font-size:12px;
    }

    table{
        border-collapse:collapse;
    }
    table.date, table.date th, table.date td{
        border:  1px solid #313131 !important;
        font-size: 14px;
        color: #333333;
    }

    .item-table tr th{
        text-transform:capitalize;
        font-size: 14px;
        font-weight: bold;
        color: #fff;
        padding: 5px 0;
        border: none !important;
    }

    .item-table tr td{
        border: 1px solid #333333;
        color: #333333;
        font-size: 14px;
    }
</style>

<section class=" border-bottom" style="padding: 5px 15px;background:#364a60;">
    <div class="d-flex flex-row-reverse">
        <div class="" style="margin-top: 6px;"><a href="#" class="close btn-icon btn btn-danger"
                data-dismiss="modal" aria-label="Close" style="padding-bottom: 6px;" title="Close"><span
                    aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
        <div style="padding-right: 3px;margin-top: 6px;"><a href="#" class="btn btn-icon btn-success" title="Print" onclick="window.print()"><i class="bx bx-printer"></i></a></div>
    </div>
</section>
<div class="receipt-voucher-hearder invoice-view-wrapper">
    @include('layouts.backend.partial.modal-header-info')
</div>
<section id="widgets-Statistics">
    <div class="pt-2">
        <table class="table table-bordered table-sm">
            <thead class="thead">
                <tr >
                    <th style="width: 5%">SL No</th>
                    <th style="width: 5%">Date</th>
                    <th style="width: 10%">Receipt No</th>
                    <th style="width: 25%">Party Name </th>
                    <th style="width: 25%">Narration</th>
                    <th style="width: 10%">Amount</th>
                    <th style="width: 20%">Pay Mode</th>
                </tr>
            </thead>
            <tbody id="receipt-body">
                @foreach ($receipt_list as $key => $item)
                <tr class="receipt_exp_view"  id="{{$item->id}}">
                    <td>{{$key+1}}</td>
                    <td>{{date('d/m/Y',strtotime($item->payment?$item->payment->date:date('Y-m-d')))}}</td>
                    <td>{{$item->payment?$item->payment->receipt_no:''}}</td>
                    <td>{{$item->party?$item->party->pi_name:'' }}</td>
                    <td>{{$item->payment?$item->payment->narration:''}}</td>
                    @if ($cash)
                    <td>{{$item->amount}}</td>
                    @else
                    <td>{{$item->amount}}</td>
                    @endif
                    <td>{{$item->payment?$item->payment->pay_mode:''}}</td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>


    <div class="divFooter mb-1 ml-1 invoice-view-wrapper  footer-margin">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid"
                src="{{ asset('img/zikash-logo.png')}}" alt="" width="70"></span>
    </div>
</section>
<div class="img receipt-bg invoice-view-wrapper">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid"
        style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;"
        alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>

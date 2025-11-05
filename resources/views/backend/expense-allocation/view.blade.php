

<style>
    html, body {
        height:100%;
    }
    thead {
    background: #34465b;
    color: #fff !important;
    height: 30px;
}
@media print{
        .table tr th,
        .table tr td{
            color: #000000 !important;
            font-weight:500 !important;
        }
    }
</style>
<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse">

        <div class="pr-1" style="padding-top: 8px;padding-right: 22px !important;"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
        
        <div class="pr-1" style="padding: 8px;padding-right: 0.2rem !important;"><a href="#" onclick="window.print();" class="btn btn-icon btn-primary"><i class="bx bx-printer"></i></a></div>
        @if ($allocation->status == 0)
        <div class="pr-1" style="padding-top: 8px;padding-right: 0.2rem !important;">
            <a href="#" id="{{$allocation->id}}" class="btn btn-sm btn-icon btn-success allocation-edit" style="padding: 6px 8px;margin-right: -8px;"><i class="bx bx-edit"></i></a>
        </div>
        @endif
        <div class="pr-1" style="padding-top: 9px;padding-right: 0.2rem !important;"><a href="{{route('expense-allocation-delete',$allocation)}}" class="btn btn-sm btn-icon btn-danger" style="padding: 6px 8px;" onclick="return confirm('Confirm Delete ?')"><i class="bx bx-trash"></i></a></div>


        <div class="pr-1 w-100 pl-2" style="margin-top: 2px;">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">Expense Allocation</h4>
        </div>
        {{-- <div class="py-1 pr-1"><a href="#" onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
    </div>
</section>
<div class="receipt-voucher-hearder invoice-view-wrapper" style="margin: 50px 20px; border-radius: 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>
<section id="widgets-Statistics">

    <div class="row">
        <div class="col-md-6 pl-4">
            <strong>Account Head:</strong> {{ $allocation->account_head->fld_ac_head}}
        </div>
        <div class="col-md-6">
            <strong>Date:</strong> {{date('d/m/Y', strtotime($allocation->date))}}
        </div>
        <div class="col-md-12">
            <div class="border-botton">
                <div class="mx-2">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered border-botton">
                            <thead class="thead">
                                <tr>
                                    <th>Project Name</th>
                                    <th>QTY</th>
                                    <th class="text-right pr-1">Amount <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                </tr>
                            </thead>

                            <tbody class="user-table-body">
                                @foreach ($allocation->items as $item)
                                    <tr>
                                        <td class="text-center">{{$item->project->name??''}}</td>
                                        <td class="text-center">{{$item->qty}}</td>
                                        <td class="text-right pr-1">{{number_format($item->amount),2}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2" class="text-right pr-1">Total Amount</td>
                                    <td  class="text-right pr-1">{{number_format($allocation->total_amount,2)}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if ($allocation->status == 0)
            <div class="col-md-12 text-center mb-1">
                <a href="{{route('expense-allocation-approve',$allocation)}}" class="btn btn-info btn-sm" onclick="return confirm('about to approve purchase. Please, Confirm?')"> Approve </a>
            </div>
        @endif
    </div>




    <div class="divFooter mb-1 ml-1 footer-margin invoice-view-wrapper">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png')}}" alt="" width="150"></span>
    </div>
</section>

<div class="img receipt-bg invoice-view-wrapper footer-margin">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid" style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;" alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>

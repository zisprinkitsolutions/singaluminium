


<section class="print-hideen border-bottom" style="padding: 5px 15px;background:#364a60;">
    <div class="row pl-2">
        <div class="col-md-6 pl-1"> <h3 style="font-family:Cambria;font-size: 2rem;color:white;">Receivable History</h3></div>
        <div class="col-md-6">
            <div class="d-flex flex-row-reverse" style="padding-right: 8px;padding-top: 6px;">
                <div class=""><a href="#" class="btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
                <div class="" style="padding-right: 3px;"><a href="#" onclick="window.print();" class="btn btn-icon btn-success"><i class="bx bx-printer"></i></a></div>
            </div>
        </div>
    </div>
</section>

<div style="margin: 10px 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>

<section id="widgets-Statistics">
    <div class="row">
        <div class="col-12 text-center">
        <div class="col-md-12">
            <div class="border-botton">
                <div class="mx-2">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm " style="width: 805px">
                            <thead class="thead">
                                <tr>
                                    <th>Party Code</th>
                                    <th>Name</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody id="purch-body">
                                @foreach ($suppliers as $party)
                                    @if ($party->due_amount>0)
                                        <tr class="receivable-view" id="{{ $party->id }}" style="text-align:center;">
                                            <td>{{ $party->pi_code}}</td>
                                            <td>{{$party->pi_name}}</td>
                                            <td>{{number_format($party->due_amount,2)}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr class="border-bottom">
                                    <td class="text-right pr-1" colspan="2">Total Amount</td>
                                    <td>{{number_format($suppliers->sum('due_amount', 2))}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>



    <div class="divFooter  ml-1  invoice-view-wrapper">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png')}}" alt="" width="150"></span>
    </div>
</section>


<div class="img receipt-bg invoice-view-wrapper">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid" style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;" alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>


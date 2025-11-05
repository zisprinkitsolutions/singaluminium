<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="row pl-2 align-items-center d-flex justify-content-start">
        <div class="col-md-6 pl-1">
            <h3 style="font-family:Cambria;font-size: 2rem;color:white;">Payable Details</h3>
        </div>
        <div class="col-md-6">
            <div class="d-flex flex-row-reverse">
                <div class="pr-1"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal"
                        aria-label="Close" style=""><span aria-hidden="true"><i class="bx bx-x"></i></span></a>
                </div>
            </div>
        </div>
    </div>
</section>
<div>
    @include('layouts.backend.partial.modal-header-info')

</div>
<section id="widgets-Statistics">
    <div class="row ">
        {{-- <div class="col-12 text-center my-2">
            <h3>Payable History</h3>
        </div> --}}
        <div class="col-md-12">
            <div class="">
                <div class="mx-2 mb-2 mt-1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-4">
                                    <strong>Payee:</strong> {{ $info->pi_name }}
                                </div>
                                <div class="col-3">
                                    <strong>Address:</strong> {{ $info->address }}
                                </div>
                                <div class="col-3">
                                    <strong>Attention:</strong> {{ $info->con_person }}
                                </div>
                                <div class="col-2">
                                    <strong>Contact No:</strong> {{ $info->con_no }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="border-botton">
                <div class="mx-2">
                    @php
                        $c = 0;
                    @endphp
                    <div class="table-responsive">

                        @php
                            $c = 0;
                        @endphp
                        <table class="table table-bordered table-sm ">
                            <thead>
                                <tr>
                                    <th style="width: 5%">SL No</th>
                                    <th>Date</th>
                                    <th style="width: 28%">Purchase</th>
                                    <th style="width: 28%">Total Amount <small>( @if (!empty($currency->symbole))
                                                {{ $currency->symbole }}
                                            @endif)</small></th>
                                    <th style="width: 28%">Due Amount <small>( @if (!empty($currency->symbole))
                                                {{ $currency->symbole }}
                                            @endif)</small></th>
                                </tr>
                            </thead>
                            @foreach ($expenses as $item)
                                <tr id="TRow">
                                    <td>{{ ++$c }}</td>
                                    <td>{{ date('d/m/Y', strtotime($item->date)) }}</td>
                                    <td>
                                        {{ $item->purchase_no }}
                                    </td>
                                    <td>
                                        {{ number_format($item->total_amount, 2) }}
                                    </td>
                                    <td>
                                        {{ number_format($item->due_amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="border-bottom">
                                <td colspan="3"></td>
                                <td class="text-center" style="color: black">Total Due</td>
                                <td>{{ number_format($expenses->sum('due_amount'), 2) }}</td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="d-flex flex-row-reverse justify-content-center align-items-center mb-2">
        <div class="print-hideen" style="">
            <a href="#" onclick="window.print();" class="btn btn-icon btn-secondary custom-action-btn" title="Print Now">
                <i class="bx bx-printer"></i> Print
            </a>
        </div>
    </div>


    <div class="divFooter  ml-1  invoice-view-wrapper">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png') }}"
                alt="" width="150"></span>
    </div>
</section>


<div class="img receipt-bg invoice-view-wrapper">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid"
        style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;"
        alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>




<section class="print-hideen border-bottom" style="padding: 5px 15px;background:#364a60;">
    <div class="row pl-2">
        <div class="col-md-6 pl-1"> <h3 style="font-family:Cambria;font-size: 2rem;color:white;">Invoice History</h3></div>
        <div class="col-md-6">
            <div class="d-flex flex-row-reverse" style="padding-right: 8px;padding-top: 6px;">
                <div class=""><a href="#" class="btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
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
                    @php
                        $c=0;
                        $t_balance=0
                    @endphp
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm " >
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th> Date</th>
                                    <th >Invoice</th>
                                    <th >Amount <small>( @if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                    <th> Invoice_balance</th>
                                    <th >Total Balance <small>( @if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                </tr>
                            </thead>


                            @foreach ($invoices as $inv)
                            <tr style="border-top:2px solid black" class="sale_view"  id="{{$inv->id}}">
                                <td>{{++$c}}</td>
                                <td>{{date('d/m/Y',strtotime($inv->date))}}</td>
                                <td>
                                    {{$inv->invoice_no}} <small>({{$inv->invoice_type}})</small>
                                </td>
                                <td>
                                    {{number_format($inv->total_budget,2)}}
                                </td>
                                <td>
                                    {{number_format($balance=$inv->total_budget,2)}}
                                </td>
                                <td>{{number_format($t_balance=$t_balance+$balance,2)}}</td>
                            </tr>
                            @if ($inv->receipts->count()>0)
                            {{-- <tr>
                                <td colspan="2"></td>
                                <td>
                                    <table class="table table-sm m-0">
                                        <tr>
                                            <th style="color: black !important">Date</th>
                                            <th style="color: black !important">Receipt</th>
                                        </tr>

                                    </table>
                                </td>
                                <td colspan="2"></td>
                            </tr> --}}
                                @foreach ($inv->receipts as $rcpt)

                                <tr style="background-color: #d4edda; ">
                                    <td colspan="1" style="border:none;"></td>

                                    <td style="width: 30% !important;">{{date('d/m/Y',strtotime($rcpt->payment->date))}}</td>
                                    <td style="">{{$rcpt->payment->receipt_no}}</td>

                                    <td style="">{{number_format($rcpt->Total_amount , 2)}}</td>
                                    <td style="">{{number_format($balance=$balance-$rcpt->Total_amount,2)}}</td>
                                    <td style="">{{number_format($t_balance=$t_balance-$rcpt->Total_amount,2)}}  </td>
                                </tr>
                                @endforeach
                            @endif

                            @endforeach
                            <tr>
                                <td colspan="4"></td>
                                <td class="text-center" style="color: black">Total Due</td>
                                <td style="color: black">{{number_format($invoices->sum('due_amount'),2)}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-12">
        <div class="d-flex flex-row-reverse justify-content-center align-items-center print-hideen mb-2" style="">
                <a href="#" onclick="window.print();" class="btn btn-icon btn-secondary custom-action-btn" title="Print Now">
                    <i class="bx bx-printer"></i> Print
                </a>
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


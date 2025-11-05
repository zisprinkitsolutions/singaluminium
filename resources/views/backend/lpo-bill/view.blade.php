

<style>
    html, body {
        height:100%;
        color: #0867d2 !important;
    }
    .lpo-table{
        border: 1px solid #0867d2 !important;
        color: #0867d2 !important;
    }
    .authority-info th{
        color: black !important;
        font-weight: bolder !important;
        border: 1px solid #0867d2 !important;
    }
    .authority-info td{
        color: black !important;
        border: 1px solid #0867d2 !important;
    }

    body {
            margin: 0;
            padding: 0;
            background: #fff !important;
        }

        .customer-static-content {
            background: #ada8a81c;
            text-align: left;
        }

        .customer-dynamic-content {
            background: #706f6f33;
        }

        .customer-dynamic-content2{
            background: #fff !important;
        }
        .customer-content{
            border: 1px solid black !important;
        }
        th, td{
            color: black !important;
        }

        .summernote{
            line-height: 5px !important;
        }
        pre{
            margin: 0px !important;
        }

        .summernote p {
            line-height: 5px;
        }

        .table-sm th, .table-sm td {
            padding: 10px;
        }

        @media print {
            /* Your custom print styles here */
            body {
                margin: 0; /* Adjust as needed */
            }
        }

</style>
@php
    $trn_no= \App\Setting::where('config_name', 'trn_no')->first();
@endphp
<section class="print-hideen border-bottom text-left" style="background: #364a60;">
    <div class="d-flex flex-row-reverse">

        <div class="pr-1" style="padding-top: 8px;padding-right: 22px !important;"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
        <div class="pr-1" style="padding: 8px;padding-right: 0.2rem !important;"><a href="{{route('lpo-bill-print',$purchase_exp)}}" target="_blank" title="LPO Print" class="btn btn-icon btn-primary"><i class="bx bx-printer"></i></a></div>


        <div class="pr-1 w-100 pl-2" style="margin-top: 2px;">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">LPO</h4>
        </div>
        {{-- <div class="py-1 pr-1"><a href="#" onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
    </div>
</section>
<div class="receipt-voucher-hearder invoice-view-wrapper" style="margin: 50px 20px 0; border-radius: 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>
<section id="widgets-Statistics">
    <div class="row mt-1">
        <div class="col-md-4 text-center"></div>
        <div class="col-md-4 text-center" style="padding-left: 0px !important;padding-right:0px !important">
            {{-- <span style="font-size: 30px; border-bottom: 1px solid #0867d2">أمر الشراء المحلي</span><br> --}}
            <span style="font-size: 18px;"> LOCAL PURCHASE ORDER </span>
        </div>
        <div class="col-md-4 text-right pr-3 mt-1">
            <span style="background: #85a6cc; color: #0867d2; " >VAT TRN: {{$trn_no->config_value}}</span>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-12">

            <div class="customer-info">
                <div class="row ml-1 mr-1 " style="border:1px solid #0867d2">
                    <div class="col-2 customer-static-content">
                        <strong>SUPPLIER/CONTRACTOR</strong> <br>
                        M/S: <br>
                        Address: <br>
                        Contact Person: <br>
                        Contact No: <br>
                        Email: <br>
                        TRN:

                    </div>
                    <div class="col-6 customer-dynamic-content text-left">
                        <br>
                        {{ optional($purchase_exp->party)->pi_name ?? '...' }} <br>
                        {{ optional($purchase_exp->party)->address ?: '...' }} <br>
                        {{ $purchase_exp->attention ?: '...' }} <br>

                        @php
                            $party = optional($purchase_exp->party);
                            $phone = $party->phone_no;
                            $con   = $party->con_no;
                        @endphp

                        @if ($phone === $con && $con && $con !== '.')
                            {{ $phone }} hh
                        @elseif ($con && $phone && $con !== '.' && $phone !== '.')
                            {{ $con . ', ' . $phone }}
                        @else
                            {{ $con && $con !== '.' ? $con : ($phone && $phone !== '.' ? $phone : '...') }}
                        @endif
                        <br>

                        {{ $party->con_no ? $party->email : '...' }} <br>
                        {{ $party->con_no ? $party->trn_no : '...' }} <br>
                    </div>
                    <div class="col-4 customer-dynamic-content text-right pt-1">
                        <span>
                            NO.: {{$purchase_exp->lpo_bill_no}}<br>
                        </span>
                        <span>
                            Date: {{date('d/m/Y')}} <br>
                        </span>
                        <span>
                            Contact Person : {{$purchase_exp->contact_person}} <br>
                        </span>
                        @php
                            $date = $purchase_exp->delivary_date;
                            $timestamp = strtotime($date);
                        @endphp

                        {{-- <span>
                            Delivery: {{ date('l', $timestamp) . ', ' . date('F', $timestamp) . ' ' . date('d', $timestamp) . ', ' . date('Y', $timestamp) }} <br>
                        </span> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pl-2 mt-1 text-left">

        <p> <strong> Project: </strong>{{ $purchase_exp->project? $purchase_exp->project->project_name:'' }} </p>
        <p> <strong> Project No: </strong> {{$purchase_exp->project? $purchase_exp->project->project_name:''}} </p>
        {{-- <p> <strong> Plot No : </strong> {{optional($purchase_exp->project)->new_project ? optional($purchase_exp->project)->new_project->plot : ''}}  </p> --}}
        <p> <strong> Owner: </strong> {{optional($purchase_exp->project)->party ? optional($purchase_exp->project)->party->pi_name : ''}} </p>
        <p> <strong> Location:  </strong> {{$purchase_exp->project? $purchase_exp->project->address:''}} </p>
    </div>

    <p class="pl-2 pt-1">{{$purchase_exp->lpo_for}} </p>
    <div class="row">
        <div class="col-md-12">
            <div class="border-botton">
                <div class="mx-2">
                    <div class="">
                        <table class="table table-sm table-bordered lpo-table authority-info">
                            <tbody class="user-table-body">
                                <tr class="border-top">
                                    <th rowspan="2" style="border: 1px solid #0867d2 !important;"> No </th>
                                    <th rowspan="2" style="border: 1px solid #0867d2 !important; text-align:left;"> Description </th>
                                    <th rowspan="2" style="border: 1px solid #0867d2 !important;"> Task </th>
                                    <th rowspan="2" style="border: 1px solid #0867d2 !important;"> Sub Task </th>
                                    <th rowspan="2" style="border: 1px solid #0867d2 !important;"> Qty </th>
                                    <th rowspan="2" style="border: 1px solid #0867d2 !important;"> Unit </th>
                                    <th colspan="2" style="border: 1px solid #0867d2 !important;"> Rate </th>
                                    <th colspan="2" style="border: 1px solid #0867d2 !important;width:150px"> Amount </th>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #0867d2 !important;"> Dhs </td>
                                    <td style="border: 1px solid #0867d2 !important;"> Fils </td>
                                    <td style="border: 1px solid #0867d2 !important;"> Dhs </td>
                                <td style="border: 1px solid #0867d2 !important;"> Fils </td>
                                </tr>
                                  @foreach ($items as $key => $item)
                                    @php
                                        $rate_whole = floor($item->rate);
                                        $rate_fraction = number_format($item->rate - $rate_whole, 2);
                                        $total_whole = floor($item->amount);
                                        $total_fraction = number_format($item->amount - $total_whole, 2);
                                    @endphp
                                <tr>
                                    <td style="border: 1px solid #0867d2 !important;">{{$key+1}}</td>
                                    <td><pre class="text-left border-0">{{$item->item_description}}</pre></td>
                                    <td><pre class="text-left border-0">{{$item->task->task_name??''}}</pre></td>
                                    <td><pre class="text-left border-0">{{$item->subTask->item_description??''}}</pre></td>
                                    <td style="border: 1px solid #0867d2 !important;">{{floatval($item->qty)}}</td>
                                    <td style="border: 1px solid #0867d2 !important;">{{optional($item->unit)->name}}</td>
                                    <td style="border: 1px solid #0867d2 !important;">{{$rate_whole}}</td>
                                    <td style="border: 1px solid #0867d2 !important;">{{$rate_fraction*100}}</td>
                                    <td style="border: 1px solid #0867d2 !important;">{{$total_whole}}</td>
                                    <td style="border: 1px solid #0867d2 !important;">{{$total_fraction*100}}</td>
                                 </tr>

                                  @endforeach
                                    @php
                                      $amount_whole = floor($purchase_exp->amount);
                                      $amount_fraction = number_format($purchase_exp->amount - $amount_whole, 2);
                                      $vat_whole = floor($purchase_exp->vat);
                                      $vat_fraction = number_format($purchase_exp->vat - $vat_whole, 2);
                                    @endphp
                                  <tr>
                                    <td style="border: 1px solid #0867d2 !important;" colspan="6" class="text-right pr-1">Total </td>
                                    <td style="border: 1px solid #0867d2 !important;" colspan="2">{{$amount_whole}}</td>
                                    <td style="border: 1px solid #0867d2 !important;" colspan="2">{{$amount_fraction*100}}</td>
                                  </tr>

                                  <tr>
                                    <td style="border: 1px solid #0867d2 !important;" colspan="6" class="text-right pr-1">VAT 5% </td>
                                    <td style="border: 1px solid #0867d2 !important;" colspan="2">{{$vat_whole}}</td>
                                    <td style="border: 1px solid #0867d2 !important;" colspan="2">{{$vat_fraction*100}}</td>
                                  </tr>

                                  <tr>
                                    <td style="border: 1px solid #0867d2 !important;" colspan="6" class="text-center pr-1">
                                        @php
                                            $total_whole = floor($purchase_exp->total_amount);
                                            $total_fraction = number_format($purchase_exp->total_amount - $total_whole, 2);
                                            $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                            $amount_in_word = $f->format($total_whole);
                                            $amount_in_word2 = $f->format($total_fraction);
                                        @endphp
                                        <div class="d-flex w-100">
                                            <div class="d-flex justify-content-between aligin-items-center mb-1 w-100">
                                                <span style="width:150px !important; color:#0867d2;font-size:15px;font-weight:bold; line-height:23px !important;">
                                                    Total Dhs
                                                </span>
                                                <div class="w-100" style="border-bottom:1px dashed #0867d2;">
                                                    <p style="margin:0 !important;padding:0!important;color:#0867d2;font-size:15px;font-weight:500 !important; padding-left:30px;text-transform: uppercase">
                                                        {{ $amount_in_word }}
                                                        @if ($total_fraction > 0)
                                                            {{ '& ' . substr($amount_in_word2, 10) }}
                                                        @endif {{ $currency->symbole }}
                                                    </p>
                                                </div>

                                            </div>
                                        </div>
                                    </td>
                                    {{-- <td style="border: 1px solid #0867d2 !important;" colspan="5" class="text-right pr-1">Total Dhs <span class="pl-2">المجموع درهم</span></td> --}}
                                    <td style="border: 1px solid #0867d2 !important;" colspan="2">{{$total_whole}}</td>
                                    <td style="border: 1px solid #0867d2 !important;" colspan="2">{{$total_fraction*100}}</td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="container mt-1 ">
                            <h5 class="mb-2 fw-bold text-center"> Support Documents </h5>
                            @if($purchase_exp->voucher_file)
                            @php
                                $imageExtensions = ['png', 'jpeg', 'gif', 'jpg'];
                                $pdfExtensions = ['pdf'];
                            @endphp


                            @if(in_array($purchase_exp->extension, $pdfExtensions))

                            {{-- <div class="row justify-content-center">
                                <a href="{{ Storage::url('upload/documents/' . $recept->voucher_file) }}">
                                <div class="col-auto">
                                    <img src="{{asset('icon/pdf-download-icon-2.png')}}" alt="Image" class="img-fluid">
                                </div>
                                </a>
                            </div> --}}

                            <div class="row justify-content-center">
                            <a href="{{ Storage::url('upload/documents/' . $purchase_exp->voucher_file) }}" target="_blank">View
                                <img src="{{asset('icon/pdf-download-icon-2.png')}}" alt="Image" class="img-fluid" style="height: 50px"></a>
                            {{-- <iframe src="{{ Storage::url('upload/documents/' . $purchase_exp->voucher_file) }}" width="100%" height="600px" frameborder="0"></iframe> --}}
                            </div>
                            @else
                            <div class="row justify-content-center">
                                <div class="col-auto">
                                   <a href="{{ asset('storage/upload/documents/' . $purchase_exp->voucher_file) }}" target="_blank"> <img src="{{ asset('storage/upload/documents/' . $purchase_exp->voucher_file) }}" alt="Image" class="img-fluid" ></a>
                                </div>
                            </div>
                            @endif
                            @endif
                        </div>


                        <div class="row d-flex justify-content-center">
                            <div class="col-md-12">
                                <table class="table table-sm table-bordered authority-info">
                                    <tr>
                                        <th></th>
                                        <th>Checked By</th>
                                        <th>Prepared By</th>
                                        <th>Approved By</th>
                                    </tr>
                                    <tr>
                                        <th class="text-left">Name</th>
                                        <td>{{$purchase_exp->checked_by}}</td>
                                        <td>{{$purchase_exp->prepared_by}}</td>
                                        <td>{{$purchase_exp->approved_by}}</td>

                                    </tr>
                                    <tr>
                                        <th class="text-left">Signature</th>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                    </tr>
                                    <tr>
                                        <th class="text-left">Date</th>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                    </tr>
                                </table>

                            </div>

                            <div class="col-md-12">
                                <h5 style="color:#0867d2;">Pay Terms:</h5>
                                <p>{{$purchase_exp->narration}}</p>
                            </div>
                        </div>
                        <div class="col-md-12  d-flex justify-content-center align-items-center print-hideen mb-1">
                            @if(Auth::user()->hasPermission('Expense_Edit') && $purchase_exp->status == "Created" && Auth::user()->is_authorizer == 1  )
                            <div class="pr-1" style="padding-top: 8px;padding-right: 12px !important;"><a href="{{route('lpo-bill-edit',$purchase_exp)}}" title="LPO Edit" class="btn btn-sm btn-icon btn-success lpo-bill-edit" style="padding: 6px 8px;margin-right: -8px;"><i class="bx bx-edit"></i></a></div>
                            @endif

                            @if(Auth::user()->hasPermission('Expense_Delete') && $purchase_exp->status == "Created")
                            <div class="pr-1" style="padding-top: 8px;padding-right: 0.2rem !important;">
                                <a href="{{route('lpo-bill-delete',$purchase_exp)}}" class="btn btn-sm btn-icon btn-danger" title="LPO Delete" onclick="event.preventDefault(); deleteAlert(this, 'About to delete LPO. Please, confirm?');" style="padding: 6px 8px;"><i class="bx bx-trash"></i></a></div>
                            @endif
                          @if ($purchase_exp->status == "Approved" && Auth::user()->is_approver == 1)
                        <div class="pr-1" style="padding-top: 8px;padding-right:0.2rem !important;">
                            <a href="javascript:void(0);"
                                onclick="checkApproveLimit({{ Auth::user()->max_approve_amount ?? 0 }}, {{ $purchase_exp->total_amount }}, '{{ route('lpo-to-purchase-expense',['lpo' => $purchase_exp->id]) }}')"
                                title="Create Expense" class="btn btn-icon btn-success">
                                <i class='bx bx-right-arrow-circle'></i>
                            </a>
                        </div>
                        @endif

                       @if ($purchase_exp->status != "Approved" && Auth::user()->is_approver == 1)
                        <div class="pr-1" style="padding-top: 8px;padding-right:0.2rem !important;">
                            <a href="{{ route('lpo-approve', $purchase_exp) }}" class="btn btn-icon btn-success approve-btn"
                                data-user-limit="{{ Auth::user()->max_approve_amount ?? 0 }}"
                                data-lpo-amount="{{ $purchase_exp->total_amount }}" title="Approve">
                                <i class='bx bx-check'></i>
                            </a>
                        </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

<style>
    .row{
        display: flex;
    }
    .col-md-1{
        max-width: 8.33% !important;
    }
    .col-md-2{
        max-width: 16.66% !important;
    }
    .col-md-8{
        max-width: 66.66% !important;
    }
    .col-md-10{
        max-width: 83.33% !important;
    }
    .col-md-11{
        max-width: 91.66% !important;
    }
    .customer-static-content{
        background: #ada8a81c;
    }
    .customer-dynamic-content{
        background: #706f6f33;
    }
    .proview-table tr td, .proview-table tr th{
        border: 1px solid black !important;
        padding:3px 6px;
    }
    .customer-dynamic-content2{
        background: #fff !important;
    }
    .customer-content{
        border: 1px solid black !important;
    }



    @media print and (color) {
        .proview-table {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }

    @media print{
        .row{
            display: flex;
        }
        .col-md-1{
            max-width: 8.33% !important;
        }
        .col-md-2{
            max-width: 16.66% !important;
        }
        .col-md-8{
            max-width: 66.66% !important;
        }
        .col-md-10{
            max-width: 83.33% !important;
        }
        .col-md-11{
            max-width: 91.66% !important;
        }
        .customer-static-content{
            background: #ada8a81c;
        }
        .customer-dynamic-content{
            background: #706f6f33;
        }
        .proview-table tr td, table tr th{
            border: 1px solid black !important;
        }
        #widgets-Statistics{
            padding: 2px !important;
        }
        .customer-dynamic-content2{
            background: #fff !important;
        }
        .customer-content{
            border: 1px solid black !important;
        }
    }
</style>
@php
    $whole = floor($sale->total_amount);
    $fraction = number_format($sale->total_amount - $whole, 2);

    $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
    $amount_in_word = $f->format($whole);
    $amount_in_word2 = $f->format((int)($fraction*100));
@endphp
<section class="print-hideen border-bottom" style="padding: 5px 30px;background:#364a60;">
    <div class="d-flex flex-row-reverse">
        <div class="" style="margin-top: 6px;"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close" title="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
        <div class="" style="padding-right: 3px;margin-top: 6px;"><a href="{{route('auth-sale-print',$sale->id)}}" target="_blank" class="btn btn-icon btn-secondary" title="Print"><i class="bx bx-printer"></i></a></div>
        @if(Auth::user()->hasPermission('Revenue_Edit'))
        <div class=" show-edit-form" style="padding-right: 3px;margin-top: 6px;"><a class="btn btn-icon btn-primary edit-btn" href="{{ route('proforma-invoice-edit', ['id' => $sale->id]) }}" title="Edit"> <i class='bx bx-edit-alt'></i></a></div>
        @endif
        @if(Auth::user()->hasPermission('Revenue_Delete'))
        <div class="" style="padding-right: 3px;margin-top: 6px;">
            <a href="{{route('sale.delete',$sale)}}" class="btn btn-icon btn-danger" title="Delete"
                onclick="event.preventDefault(); deleteAlert(this, 'About to delete invoice. Please, confirm?');">
                <i class="bx bx-trash"></i>
            </a>
        </div>
        @endif
       @if(Auth::user()->hasPermission('Revenue_Approve'))
        <div class="" style="padding-right: 3px;margin-top: 6px;">
            <a href="{{route('sale-approve',$sale)}}" class="btn btn-icon btn-success approve-btn" data-total="{{$sale->site_project ? $sale->amount : 0}}" title="Approve">
                <i class='bx bx-check'></i>
            </a>
        </div>
        @endif

        <div class="w-100">
            <h4 style="font-family:Cambria;font-size: 1.4rem;color:white;">
                {{"Proforma Invoice" .' (' . $sale->proforma_invoice_no.')'}}
                Date: {{date('d/m/Y', strtotime($sale->date))}}
            </h4>
        </div>
    </div>
</section>
@php
    $trn_no= \App\Setting::where('config_name', 'trn_no')->first();
    $company_name= \App\Setting::where('config_name', 'company_name')->first();
@endphp
@include('layouts.backend.partial.modal-header-info')

<section id="widgets-Statistics" class="pt-2 px-1">
    <div class="row">
        <div class="col-sm-12">
            {{-- <h4 class=" text-center" style="margin:0;padding:0;line-height:40px;color: #1d1d1d !important;"> <strong>{{$sale->invoice_type=="Tax Invoice"? 'Tax Invoice':($sale->invoice_type=="Proforma Invoice"?'Proforma Invoice':'Invoice')}}</strong> </h4>
            <p class="text-center mb-2" style="color: #1d1d1d !important;">
                Invoice No: @if($sale->invoice_type == 'Tax Invoice')
                {{$sale->invoice_no}}
                @else
                {{$sale->proforma_invoice_no}}
                @endif,
                ,@if ($sale->invoice_type == 'Tax Invoice') VAT TRN No: {{'('.$trn_no->config_value.')'}},@endif Running No:{{$running_no}}
            </p> --}}
            <div class="customer-info m-1">
                <table class="table table-sm table-bordered" style="color: #1d1d1d !important;">
                    <tr>
                        <td class="text-left">
                            <strong style="padding-right: 89px;">TO</strong> <strong>: {{$sale->party->pi_name}}</strong>
                        </td>
                        <td class="text-left">
                            <strong>INVOICE NO <span style="padding-left: 26px">: {{$sale->invoice_type == 'Tax Invoice'?$sale->invoice_no:$sale->proforma_invoice_no}}</span></strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            <strong style="padding-right: 45px">ADDRESS</strong> <span><b>:</b></span> {{$sale->party->address}}
                        </td>
                        <td class="text-left">
                            <strong>INVOICE DATE <span style="padding-left: 12px">: {{date('d.m.Y', strtotime($sale->date))}}</span></strong>
                        </td>
                    </tr>
                    @php
                        $project = $sale->project;
                        $new_project = $project?$project->new_project:null;
                    @endphp
                    <tr>
                        <td class="text-left">
                            <strong style="padding-right: 46px;">PROJECT</strong>  <span><b>:</b></span> {{$sale->project->project_name??''}}
                        </td>
                        <td style="width: 300px;" class="text-left">
                            <strong>PLOT NUMBER &nbsp;&nbsp; :</strong> <span> {{optional($new_project)->plot}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-left">
                            <strong>CONSULTANT &nbsp;&nbsp;&nbsp;&nbsp; :</strong> {{optional($new_project)->consultant}}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="row" style="padding: 15px;">
        <div class="col-md-12">
            <table class="table-sm table-bordered border-botton  w-100" style="color: black; ">
                <thead style="background-color: #706f6f33 !important;color: black;">
                    <tr >
                        <th class="text-left pl-1" style="text-transform: uppercase; color: black !important;"> DESCRIPTION / DETAILS OF THE SUPPLY / QUANTITY</th>
                        <th class="text-center" style="text-transform: uppercase; color: black !important;width:70px"> UNIT PRICE <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                        <th class="text-center" style="text-transform: uppercase; color: black !important;width:130px"> NET AMOUNT <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                        <th class="text-center" style="text-transform: uppercase; color: black !important;width:80px"> TAX RATE </th>
                        <th class="text-center" style="text-transform: uppercase; color: black !important;width:80px"> TAX DUE AMOUNT <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                        <th class="text-center" style="text-transform: uppercase; color: black !important;width:140px"> PAYABLE AMOUNT </th>
                    </tr>
                </thead>
                @php
                    $cc=0;
                @endphp
                <tbody class="user-table-body">
                    @foreach (\App\SaleItem::where('sale_id',$sale->id)->get() as  $item)
                        <tr class="text-center">
                            <td class="text-left pl-1">
                                <pre>{{ $item->item_description }}</pre>
                            </td>
                            <td class="text-center">{{ number_format($item->rate,2) }}</td>
                            <td class="text-center">{{ number_format($item->amount,2) }}</td>
                            <td class="text-center">{{$item->vat>0?5:0}}%</td>
                            <td class="text-center">{{ number_format($item->vat,2) }}</td>
                            <td class="text-center">{{ number_format($item->total_amount,2) }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="text-right pr-1" colspan="5">TOTAL NET PAYABLE AMOUNT (EXCLUDING VAT)</td>
                            <td class="text-center">
                                {{ number_format($sale->amount,2) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right pr-1" colspan="5">VAT
                            </td>
                            <td class="text-center">
                                {{ number_format($sale->vat,2)}}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right pr-1" colspan="5"> RETENTION AMOUNT</td>
                            <td class="text-center">
                                {{ number_format($sale->retention_amount,2)}}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right pr-1" colspan="5"    style="background: #706f6f33">TOTAL GROSS AMOUNT (INCLUDING VAT)</small></td>
                            <td class="text-center"   style="background: #706f6f33">
                                {{ number_format($sale->total_amount,2) }}
                            </td>
                        </tr>
                        <tr>

                            <td colspan="6" class="text-right pr-1 text-capitalize" style="background: #706f6f33; color:#000; text-transform: uppercase !important;">
                                IN WORDS AED: {{ $amount_in_word }}
                                @if ($fraction > 0)
                                    {{ '& ' . $amount_in_word2 }}
                                @endif ONLY
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>

    <section>
        <div class="row pt-1">
            <div class="ml-2">
                <ul class="fileList">
                    @foreach ($sale->documents as $item)


                        <li class="voucher-img-wrapper">
                            <a href="{{ asset('storage/' . $item->file_path) }}" target="blank">
                                {{ str_replace('upload/sale/', '', $item->file_path) }}
                            </a>

                            <img src="{{ asset('storage/' . $item->file_path) }}" width="40" height="40"
                                style="object-fit:cover; border:1px solid #ddd; margin:0 15px;" />



                            <form class="voucher-img-form" action="{{ route('voucher.delete', $item->id) }}" method="POST" style="">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:transparent; border:none; color:red; font-size:30px; cursor:pointer;"><i class="bx bx-trash"> </i></button>

                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
</section>

<div class="img receipt-bg invoice-view-wrapper">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid" style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;" alt="">

    {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
</div>


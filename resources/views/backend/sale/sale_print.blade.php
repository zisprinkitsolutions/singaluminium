
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend') }}/app-assets/css/bootstrap.css">
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #fff !important;
        }

        .customer-static-content {
            border-right: 1px solid #999;
            padding: 10px;
        }

        .customer-dynamic-content {
            /* background: #706f6f33; */
            padding: 10px;
        }

        .customer-dynamic-content2 {
            background: #fff !important;
        }

        .customer-content {
            border: 1px solid black !important;
        }

        pre {
            margin: 0px !important;
        }

        p {
            margin: 0px !important;
        }

        @media print {
            pre {
                border: none !important;
            }

            .row {
                display: flex;
            }

            .col-md-1 {
                max-width: 8.33% !important;
            }

            .col-md-2 {
                max-width: 16.66% !important;
            }

            .col-md-3 {
                max-width: 25% !important;
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
        }
    </style>
    @stack('css')
    <title>{{$company_name}}</title>
</head>

<body onload="setTimeout(function() { window.print();},1000);">
    {{--

    <body> --}}
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <table width="100%">
                <thead>
                    <tr>
                        <td class="headerGroup">
                            <div class="header-block">
                                {{-- print header --}}
                            </div>
                        </td>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td class="footerGroup">
                            <div class="footer-block">
                                {{-- print footer --}}
                            </div>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <tr>
                        <td>
                         @php

                        $whole = floor($sale->total_budget);
                        $fraction = number_format($sale->total_budget - $whole, 2);
                        $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                        $amount_in_word = $f->format($whole);
                        $amount_in_word2 = $f->format((int)($fraction*100));
                        @endphp
                        <div class="row" style="padding: 0px 9px;">
                            <div class="col-sm-12">
                                <h4 class=" text-center" style="margin:0;padding:0;line-height:40px;color: #1d1d1d !important;"> <strong> {{$sale->trn=='YES'?'TAX INVOICE':'INVOICE'}}</strong> </h4>
                                <p class="text-center mb-2" style="color: #1d1d1d !important;">
                                    Invoice No: @if($sale->invoice_type == 'Tax Invoice')
                                    {{$sale->invoice_no}}
                                    @else
                                    {{$sale->proforma_invoice_no}}
                                    @endif,
                                    Date: {{date('d/m/Y', strtotime($sale->date))}}
                                    {{-- @if ($sale->invoice_type!='Proforma Invoice')
                                    VAT TRN: {{'('.$trn_no.')'}}, @endif
                                    Running No:{{$running_no}} --}}
                                </p>
                                <div class="customer-info m-1">
                                    <table class="table table-sm table-bordered" style="color: #1d1d1d !important;">
                                        <tr>
                                            <td>
                                                <strong style="padding-right: 110px;">TO</strong> <strong>: {{$sale->party->pi_name}}</strong>
                                            </td>
                                            <td>
                                                <strong>INVOICE NO <span style="padding-left: 35px">: {{$sale->invoice_type == 'Tax
                                                        Invoice'?$sale->invoice_no:$sale->proforma_invoice_no}}</span></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong style="padding-right: 53px">ADDRESS</strong> <span><b>:</b></span>
                                              {{ optional($sale->subsidiary)->company_address ?? optional($sale->party)->address }}
                                            </td>
                                            <td>
                                                <strong>INVOICE DATE <span style="padding-left: 18px">: {{date('d.m.Y',
                                                        strtotime($sale->date))}}</span></strong>
                                            </td>
                                        </tr>
                                        @php
                                        $project = $sale->project;
                                        $new_project = $project?$project->new_project:null;
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong style="padding-right: 54px;">PROJECT</strong> <span><b>:</b></span>
                                                {{$sale->project->project_name??''}}
                                            </td>
                                            <td style="width: 300px;">
                                                <strong>PLOT NUMBER &nbsp;&nbsp; :</strong> <span> {{optional($new_project)->plot}}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <strong>CONSULTANT &nbsp;&nbsp;&nbsp;&nbsp; :</strong> {{optional($new_project)->consultant}}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="padding: 15px 25px;">
                            <div class="col-sm-12">
                                <table class="table-sm table-bordered border-botton  w-100" style="color: black; ">
                                    <thead style="background-color: #706f6f33 !important;color: black;">
                                        <tr>
                                            <th class="text-left pl-1" style="text-transform: uppercase; color: black !important;"> DESCRIPTION
                                                / DETAILS OF THE SUPPLY / QUANTITY</th>
                                            <th class="text-center" style="text-transform: uppercase; color: black !important;width:100px"> UNIT
                                                PRICE <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                            <th class="text-center" style="text-transform: uppercase; color: black !important;width:100px"> NET
                                                AMOUNT <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                            <th class="text-center" style="text-transform: uppercase; color: black !important;width:100px"> TAX
                                                RATE </th>
                                            <th class="text-center" style="text-transform: uppercase; color: black !important;width:130px"> TAX
                                                DUE AMOUNT <small>(@if(!empty($currency->symbole)){{$currency->symbole}}@endif)</small></th>
                                            <th class="text-center" style="text-transform: uppercase; color: black !important;width:140px">
                                                PAYABLE AMOUNT </th>
                                        </tr>
                                    </thead>
                                    @php
                                    $cc = 0;
                                    @endphp
                                    <tbody class="user-table-body">
                                        @foreach ($sale->tasks as $item)
                                        <tr class="text-center">
                                            <td class="text-left pl-1">
                                                <pre>{{ $item->item_description }}</pre>
                                            </td>
                                            <td class="text-center">{{ number_format($item->rate,2) }}</td>
                                            <td class="text-center">{{ number_format($item->budget,2) }}</td>
                                            <td class="text-center">{{$item->vat->value??0}}%</td>
                                            <td class="text-center">{{ number_format($item->total_budget -$item->budget,2) }}</td>
                                            <td class="text-center">{{ number_format($item->total_budget,2) }}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td class="text-right pr-1" colspan="5">TOTAL NET PAYABLE AMOUNT (EXCLUDING VAT)</td>
                                            <td class="text-center">
                                                {{ number_format($sale->budget,2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right pr-1" colspan="5">VAT
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($sale->vat,2)}}
                                            </td>
                                        </tr>
                                         @if (!$sale->retention_invoice)
                                        <tr>
                                            <td class="text-right pr-1" colspan="5"> RETENTION</td>
                                            <td class="text-center">
                                                {{ number_format($sale->retention_amount,2)}}
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td class="text-right pr-1" colspan="5" style="background: #706f6f33">TOTAL GROSS AMOUNT (INCLUDING
                                                VAT)</small></td>
                                            <td class="text-center" style="background: #706f6f33">
                                                {{ number_format($sale->total_budget,2) }}
                                            </td>
                                        </tr>
                                        <tr>

                                            <td colspan="6" class="text-right pr-1 text-capitalize"
                                                style="background: #706f6f33; color:#000; text-transform: uppercase !important;">
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
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ============ srtart absolute header/footer image========== -->
        <div class="header">
            @include('layouts.backend.partial.modal-header-info')
        </div>
        <div class="footer">
            @include('backend.print.footer-with-address')
        </div>
        <div class="img">
            <img src="{{ asset('img/singh-bg.png')}}" class="img-fluid" style="position: fixed; top:500px; left:100px; opacity:0.09; height:500px;" alt="">
        </div>
        <!-- ============ end absolute header/footer image========== -->

        <!-- Optional JavaScript; choose one of the two! -->
        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
        </script>
        @stack('js')

    </body>

</html>

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
            background: #ada8a81c;
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
        @media print{
            #widgets-Statistics{
                padding-right: 150px !important;
                padding-left: 150px !important;
            }
            .table-bg{
                background: #e3e3e3 !important;
                print-color-adjust: exact; 
            }
            .td-border{
                border-left: 1px solid #fff !important
            }
        }
    </style>

    <title>Invoice</title>
</head>

<body onload="window.print();">

    @php
        $trn_no = \App\Setting::where('config_name', 'trn_no')->first();
        $company_name = \App\Setting::where('config_name', 'company_name')->first();
    @endphp

    @php
        $trn_no = \App\Setting::where('config_name', 'trn_no')->first();
        $company_name = \App\Setting::where('config_name', 'company_name')->first();
    @endphp
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
                    <td id="widgets-Statistics">
                        <table class="table table-bordered table-sm ">
                            <thead class="thead">
                                <tr class="text-center">
                                    <th colspan="2">
                                        <h4>Corporate Tax Report</h4>
                                        <p>Period: {{date('d/m/Y', strtotime($from_date)) .'-'. date('d/m/Y', strtotime($to_date))}}</p>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="pl-1">Statement of Profit/Loss</th>
                                    <th class="text-right pr-1" style="width: 200px !important;">Amount</th>
                                </tr>
                            </thead>
                            <tbody id="purch-body">
                                @foreach ($profitLosse_results as $item)
                                
                                    <tr class="head-details" data-target="{{$item['account_type']}}" data-type="{{$item['type']}}">
                                        <td style="font-size: 13px;">
                                            <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                {{$item['title']}}
                                            </div>
                                        </td>
                                        <td class="text-right pr-1">{{number_format($item['net_amount']<0?$item['net_amount']*-1:$item['net_amount'],2)}}</td>
                                    </tr>
                                    <tr class="subhead">
                                        <td colspan="2" style="padding: 0;">
                                            <div class=" ml-2">
                                                <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                    @foreach (App\JournalRecord::corporate_tax_details($from_date, $to_date, $item['account_type'], $item['type']) as $detail)
                                                        <tr class="tax-sub-head-details" id="{{$detail->account_head_id}}">
                                                            <td class="td-border">{{$detail->ac_head->fld_ac_head??''}}</td>
                                                            <td class="text-right pr-1" style="width: 200px !important;">{{number_format($detail->net_amount<0?$detail->net_amount*-1:$detail->net_amount,2)}}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                
                                <tr>
                                    <th class="pl-1">Statement of Financial Position</th>
                                    <th class="text-right pr-1">Amount</th>
                                </tr>
                                @foreach ($financial_results as $item)
                                    <tr class="head-details" data-target="{{$item['account_type']}}" data-type="{{$item['type']}}">
                                        <td style="font-size: 13px;">
                                            <div class="d-flex align-items-center" style="text-transform: uppercase">
                                                <i class='bx bx-plus'></i>
                                                <i class='bx bx-minus d-none'> </i>
                                                {{$item['title']}}
                                            </div>
                                        </td>
                                        <td class="text-right pr-1">{{number_format($item['net_amount']<0?$item['net_amount']*-1:$item['net_amount'],2)}}</td>
                                    </tr>
                                    <tr class="subhead">
                                        <td colspan="2" style="padding: 0;">
                                            <div class=" ml-2">
                                                <table class="table table-sm table-bg" style="background: #e3e3e3 !important; margin-bottom:0 !important;">
                                                    @foreach (App\JournalRecord::corporate_tax_details($from_date, $to_date, $item['account_type'], $item['type']) as $detail)
                                                        <tr class="tax-sub-head-details" id="{{$detail->account_head_id}}">
                                                            <td class="td-border">{{$detail->ac_head->fld_ac_head??''}}</td>
                                                            <td class="text-right pr-1" style="width: 200px !important;">{{number_format($detail->net_amount<0?$detail->net_amount*-1:$detail->net_amount,2)}}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <!-- ============ srtart absolute header/footer image========== -->
    <div class="header">
        
    </div>
    <div class="footer">
        
        <div class="divFooter text-left pl-4">
            Business Software Solutions by
            <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png')}}" alt="" width="150"></span>
        </div>
    </div>
    <!-- ============ end absolute header/footer image========== -->

    <!-- Optional JavaScript; choose one of the two! -->
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>

</html>

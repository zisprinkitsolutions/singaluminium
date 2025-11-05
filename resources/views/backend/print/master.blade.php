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

        .border-gray{
            border: 1px solid #999999;
            padding: 10px;
        }

        .customer-static-content {
            background: #ada8a81c;
            padding: 10px;
        }

        .customer-dynamic-content {
            /* background: #706f6f33; */
            padding: 10px;
            border-left: 1px solid #999999;
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
        .divFooter{
            position: fixed;
            bottom: -30px;
        }
    </style>

    <title>{{request()->is('lpo-bill/print/*')?'LPO':'Invoice'}}</title>
</head>

<body onload="window.print();">

    @php
    $trn_no = \App\Setting::where('config_name', 'trn_no')->first()->config_value;
    $company_name= \App\Setting::where('config_name', 'company_name')->first();
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
                    <td>
                        @yield('body')
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <!-- ============ srtart absolute header/footer image========== -->
    <div class="header">
       @yield('header')
    </div>
    <div class="footer">
        @yield('footer')
    </div>
    <div class="divFooter mb-1 ml-1 footer-margin invoice-view-wrapper">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png')}}" alt="" width="150"></span>
    </div>
    <!-- ============ end absolute header/footer image========== -->

    <!-- Optional JavaScript; choose one of the two! -->
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>

</html>

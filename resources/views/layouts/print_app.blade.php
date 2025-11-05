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

        .customer-dynamic-content2{
            background: #fff !important;
        }
        .customer-content{
            border: 1px solid black !important;
        }
        pre{
            margin: 0px !important;
        }

        p{
            margin:0px !important;
        }
        @media print{
            pre{
                border: none !important;
            }
            .row{
                display: flex;
            }
            .col-md-1{
                max-width: 8.33% !important;
            }
            .col-md-2{
                max-width: 16.66% !important;
            }
            .col-md-3{
                max-width: 25% !important;
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
        }
        .text-dark{
            color: black !important;
        }
    </style>
    @stack('css')
    <title>{{$company_name}}</title>
</head>

<body onload="setTimeout(function() { window.print();},1000);">
{{-- <body> --}}
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
                        @yield('content')
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- ============ start absolute header/footer image========== -->
    <div class="header">
        @include('layouts.backend.partial.modal-header-info')
    </div>
    <div class="footer">
        @include('backend.print.footer-with-address')
    </div>
    <!-- ============ end absolute header/footer image========== -->
    <div class="img">
        <img src="{{ asset('img/singh-bg.png')}}" class="img-fluid" style="position: fixed; top:500px; left:100px; opacity:0.09; height:500px;" alt="">
    </div>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    @stack('js')

</body>

</html>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description"
        content="Frest admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Frest admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $settings = \App\Setting::where('config_name', 'title_name')->first();
        $company_name = \App\Setting::where('config_name', 'company_name')->first();
    @endphp
    <title>{{ $settings->config_value }} - @yield('title') </title>
    <link rel="apple-touch-icon" href="{{ asset('assets/backend') }}/app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon"
        href="{{ asset('assets/backend') }}/app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700"
        rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend') }}/app-assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/backend') }}/app-assets/vendors/css/forms/select/select2.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend') }}/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend') }}/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend') }}/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend') }}/app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend') }}/app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/backend') }}/app-assets/css/themes/semi-dark-layout.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/backend') }}/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/backend') }}/app-assets/css/plugins/forms/validation/form-validation.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/backend/') }}/app-assets/vendors/css/extensions/toastr.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/backend/') }}/app-assets/css/plugins/extensions/toastr.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/backend/') }}/app-assets/datatables/css/dataTables.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/backend') }}/style.css">
    <!-- END: Custom CSS-->

    <!-- BEGIN: Page CSS-->
    @stack('css')
    <!-- END: Page CSS-->
    <style>
        .table-bordered {
            border: 1px solid #f4f4f4;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }

        table {
            background-color: transparent;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
        }

        .tarek-container {
            width: 85%;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 88% 12%;
            background-color: #ffff;
        }

        .invoice-label {
            font-size: 10px !important
        }

        .content-padding {
            padding: 5px 10px 12px;
        }

        .content-title {
            padding: 10px 0 0 10px;
        }

        .bx-filter {
            font-size: 30px;
            line-height: 0px;
        }

        /* Tareq custom css */
        .active-button-sale {
            color: red;
        }

        .card-body {
            flex: 1 1 auto;
            min-height: 1px;
            padding: 0.5rem !important;
        }

        .col-right-padding {
            padding-right: 0px !important;
            /* padding-left: 0px !important; */
        }

        .col-left-padding {
            padding-left: 0px !important;
            /* padding-left: 0px !important; */
        }

        /* Tareq custom css */

        div.dt-buttons {
            float: right;
            margin-bottom: 10px;
        }

        .print-content {
            display: none !important;
        }

        @media print {
            .menu-accordion {
                visibility: hidden;
            }

            .dt-buttons {
                visibility: hidden;
            }

            .footer {
                visibility: hidden;
            }

            a {
                text-decoration: none !important;
                color: black;
            }

            .print-menu {
                visibility: hidden;
            }

            .modal-content {
                min-width: 99%;
                min-height: 100vh;
            }

            .print-content {
                display: block !important;
            }

            .row{
                display: flex;
            }
            .col-md-1, .col-1{
                width: 8.33% !important;
            }
            .col-md-2, .col-2{
                width: 16.66% !important;
            }
            .col-md-3, .col-3{
                width: 25% !important;
            }
            .col-md-4, .col-4{
                width: 33.33% !important;
            }
            .col-md-5, .col-5{
                width: 41.65% !important;
            }
            .col-md-6, .col-6{
                width: 50% !important;
            }
            .col-md-7, .col-7{
                width: 58.33% !important;
            }
            .col-md-8, .col-8{
                width: 66.66% !important;
            }
            .col-md-9, .col-9{
                width: 75% !important;
            }
            .col-md-10, .col-10{
                width: 83.33% !important;
            }
            .col-md-11, .col-11{
                width: 91.63% !important;
            }
            .col-md-12, .col-12{
                width: 100% !important;
            }
        }

        .main-menu .navbar-header {
            height: 100%;
            width: 260px;
            height: 3.6rem;
            position: relative;
            padding: 0.35rem 1.45rem 0.3rem 1.3rem;
            transition: 300ms ease all, background 0s;
            cursor: pointer;
            z-index: 3;
        }

        .main-menu .navbar-header2 {
            height: 100%;
            width: 260px;
            height: 4.6rem;
            position: relative;
            padding: 0.35rem 1.45rem 0.3rem 1.3rem;
            transition: 300ms ease all, background 0s;
            cursor: pointer;
            z-index: 3;
        }


        element.style {}

        html .navbar-sticky .app-content .content-wrapper {
            padding: 1.8rem 2.2rem 0;
            margin-top: 0rem !important;
        }
    </style>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern semi-dark-layout 2-columns  navbar-sticky footer-static  "
    data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- BEGIN: Header-->
    <div class="header-navbar-shadow"></div>
    {{-- @include('layouts.backend.partial.nav') --}}
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    @include('layouts.backend.partial.sidebar')
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    @yield('content')
    <!-- END: Content-->

    <!-- demo chat-->
    <div class="widget-chat-demo">

    </div>
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    @include('layouts.backend.partial.footer')
    <!-- END: Footer-->

    <div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="padding: 5px 33px;background:#364a60;">
                    <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">New Party Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('customerPost') }}" method="POST" id="customerAddNew">

                        @csrf
                        <div class="row match-height">



                            <div class="col-md-6">

                                <div class="form-body">
                                    <div class="row">


                                        <div class="col-md-4">
                                            <label>Party Name</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="pi_name" class="form-control" name="pi_name"
                                                value="{{ isset($costCenter) ? $costCenter->pi_name : '' }}"
                                                placeholder="Party Name" required>
                                            @error('pi_name')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Party Type</label>
                                        </div>
                                        <div class="col-md-8 form-group customer-select">
                                            <select name="pi_type" class="common-select2"
                                                style="width: 100% !important" id="pi_type" required>
                                                <option value="">Select...</option>
                                                @foreach ($partyTypes as $item)
                                                    <option value="{{ $item->title }}"
                                                        {{ isset($costCenter) ? ($costCenter->pi_type == $item->title ? 'selected' : '') : '' }}>
                                                        {{ $item->title }}</option>
                                                @endforeach
                                            </select>

                                            @error('pi_type')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>


                                        <div class="col-md-4">
                                            <label>TRN No</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="trn_no2" class="form-control" name="trn_no"
                                                value="{{ isset($costCenter) ? $costCenter->trn_no : '' }}"
                                                placeholder="TRN Number">


                                            @error('trn_no')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Address</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="address2" class="form-control" name="address"
                                                value="{{ isset($costCenter) ? $costCenter->address : '' }}"
                                                placeholder="Address">


                                            @error('address')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Contact Person</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="con_person" class="form-control"
                                                name="con_person"
                                                value="{{ isset($costCenter) ? $costCenter->con_person : '' }}"
                                                placeholder="Contact Person">


                                            @error('con_person')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Mobile Phone No</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="number" id="con_no" class="form-control" name="con_no"
                                                value="{{ isset($costCenter) ? $costCenter->con_no : '' }}"
                                                placeholder="Mobile No">


                                            @error('con_no')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Phone No</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="number" id="phone_no" class="form-control"
                                                name="phone_no"
                                                value="{{ isset($costCenter) ? $costCenter->phone_no : '' }}"
                                                placeholder="Phone No">
                                            @error('phone_no')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Email</label>
                                        </div>
                                        <div class="col-md-8 form-group">
                                            <input type="text" id="email" class="form-control" name="email"
                                                value="{{ isset($costCenter) ? $costCenter->email : '' }}"
                                                placeholder="Email">


                                            @error('email')
                                                <div class="btn btn-sm btn-danger">{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-12 d-flex justify-content-end ">

                                            <button type="submit" class="btn btn-primary mr-1">Submit</button>
                                            <button type="reset" class="btn btn-light-secondary">Reset</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="ledger-show" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="ledger-show-content">

                </div>
            </div>
        </div>
    </div>
    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('assets/backend') }}/app-assets/vendors/js/vendors.min.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('assets/backend/') }}/app-assets/vendors/js/extensions/toastr.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('assets/backend') }}/app-assets/js/core/app-menu.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/js/core/app.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/js/scripts/components.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/js/scripts/footer.js"></script>
    <!-- END: Theme JS-->

    <script src="{{ asset('assets/backend') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
    <script src="{{ asset('assets/backend') }}/app-assets/js/scripts/forms/select/form-select2.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <!-- BEGIN: Page JS-->
    @stack('js')
    <script>
         function select2_change()
        {
            $('.common-select2').select2();

        }

        $(document).ready(function() {
            $('.common-select2').select2();

            $(body).mCustomScrollbar({
                theme: "minimal"
            });
        })

        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}";
            console.log(type);
            toastr.options = {
                "closeButton": true,
                "tapToDismiss": false,
            };
            switch (type) {
                case 'info':
                    toastr.info("{{ Session::get('message') }}", "Info");
                    break;

                case 'warning':
                    toastr.warning("{{ Session::get('message') }}", "Warning");
                    break;

                case 'success':
                    toastr.success("{{ Session::get('message') }}", "Success");
                    break;

                case 'error':
                    toastr.error("{{ Session::get('message') }}", "Error");
                    break;
            }
        @endif
    </script>
    <script type="text/javascript">
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
    <!-- END: Page JS-->

    <script>
        function downloadCSV(csv, filename) {
            var csvFile;
            var downloadLink;

            // CSV file
            csvFile = new Blob([csv], {
                type: "text/csv"
            });

            // Download link
            downloadLink = document.createElement("a");

            // File name
            downloadLink.download = filename;

            // Create a link to the file
            downloadLink.href = window.URL.createObjectURL(csvFile);

            // Hide download link
            downloadLink.style.display = "none";

            // Add the link to DOM
            document.body.appendChild(downloadLink);

            // Click download link
            downloadLink.click();
        }

        function exportTableToCSV(filename) {
            var csv = [];
            var rows = document.querySelectorAll("table tr");

            for (var i = 0; i < rows.length; i++) {
                var row = [],
                    cols = rows[i].querySelectorAll("td, th");

                for (var j = 0; j < cols.length; j++)
                    row.push("\"" + cols[j].innerText + "\"");

                csv.push(row.join(","));
            }

            // Download CSV file
            downloadCSV(csv.join("\n"), filename);

        }

        // *********joman code in here********

        // ********emirates id fromate validation****


        $(document).on("keyup", ".emirates_id_num", function(e) {
            // alert('hello');
            var inputValue = $(this).val();
            var error = $(this).data('error');
            var yyyy = $(this).data('yyyy');
            var button = $(this).data('s-button');
            var dod = $("." + yyyy).val();
            var errorSpan = $("." + error);



            var dateParts = dod.split('/')
            if (dateParts.length === 3) {
                var year = parseInt(dateParts[2], 10);
            }
            var input = inputValue.substring(0, 8);
            var check_value = "784-" + year.toString();
            var check_input = input.toString();

            console.log(check_input + check_value)
            if (check_value === check_input && inputValue.length >= 18 && inputValue.charAt(8) == '-' && inputValue
                .charAt(16) == '-') {
                errorSpan.text("Valid format. EX " + inputValue).css("color", "green").show();
                $("." + button).prop("disabled", false)
            } else {
                errorSpan.text("Invalid format.EX 784-" + year + "-0000000-0").css("color", "red").show();
                $("." + button).prop("disabled", true)
            }
        });
        // text transfare

        $(document).on("change", ".local_address", function(e) {
            e.preventDefault();
            //alert('hello')
            var className = $(this).data('local-to-parmanent');
            var className2 = $(this).data('local-form-parmanent');

            if ($(this).prop("checked")) {
                var text = $("." + className2).val();
                // alert(text);
                var show = $("." + className).val(text);
            } else {
                var show = $("." + className).val('');
            }
        });


        // *********telephone************


        $(document).ready(function() {
            // Define a regular expression pattern for UAE phone numbers
            var uaePhonePattern = /^(\+971|0)(50|52|54|55|56|58|2|3|4|6|7|9)\d{7}$/;

            $(document).on("keyup", ".phoneInput", function(e) {
                // alert('hello')
                var phoneNumber = $(this).val().trim();
                var errorClass = $(this).data('phone-error');
                var button = $(this).data('s-button');
                // Test the input value against the pattern
                if (uaePhonePattern.test(phoneNumber)) {
                    $("." + errorClass).text("Valid format. Ex:" + phoneNumber).css("color", "green")
                        .show();
                    $("." + button).prop("disabled", false)
                } else {
                    $("." + errorClass).text("Invalid format. Ex:+97150000000").css("color", "red").show();
                    $("." + button).prop("disabled", true)

                }
            });

            //     //  *************** passport number validation  ********************
            //     $(document).on("keyup", ".passportNumber", function(e) {
            //         // Get the selected country code
            //         var Country = $(this).data('country');
            //         var selectedCountry = $("." + Country).val().replace(/\s/g, '');
            //         var error = $(this).data('error');
            //         var button = $(this).data('s-button');
            //         // Get the input passport number
            //         var passportNumber = $(this).val().trim();
            //         // alert(selectedCountry)

            //         // Define regular expressions for each supported country
            //         var passportPatterns = {
            //             Bangladesh: /^[A-Z]\d{7}$/, // Bangladesh passport format: One uppercase letter followed by seven digits.
            //             // Sri Lanka: /^[A-Z]{2}\d{6}$/, // Sri Lanka passport format: Two uppercase letters followed by six digits.
            //             Pakistan: /^[A-Z]\d{8}$/, // Pakistan passport format: One uppercase letter followed by eight digits.
            //             Oman: /^[A-Z]{2}\d{7}$/, // Oman passport format: Two uppercase letters followed by seven digits.
            //             sy: /^\d{9}$/, // Syria passport format: Nine digits (simplified for demonstration).
            //             Lebanon: /^[A-Z]{2}\d{6}$/, // Lebanon passport format: Two uppercase letters followed by six digits.
            //             India: /^[A-Z]{1}[0-9]{7}$/, // India passport format: One uppercase letter followed by seven digits.
            //             Nepal: /^[A-Z]{1}[0-9]{7}$/, // Nepal passport format: One uppercase letter followed by seven digits.
            //             ae: /^[A-Z]{1}[0-9]{7}$/, // UAE passport format: One uppercase letter followed by seven digits.
            //             Egypt: /^[A-Z]{2}\d{6}$/, // Egypt passport format: Two uppercase letters followed by six digits.
            //             Macao: /^\d{8}$/, // Morocco passport format: Eight digits (simplified for demonstration).
            //             sd: /^\d{9}$/, // Sudan passport format: Nine digits (simplified for demonstration).
            //             Jordan: /^[A-Z]{1}\d{7}$/, // Jordan passport format: One uppercase letter followed by seven digits.
            //             Afghanistan: /^[A-Z]{1}\d{8}$/, // Afghanistan passport format: One uppercase letter followed by eight digits.
            //             // Add more patterns for other countries as needed
            //          };


            //         // Check if the passport number matches the pattern for the selected country
            //         if (passportPatterns[selectedCountry] && passportPatterns[selectedCountry].test(passportNumber)) {
            //           $("."+ error).text("Valid format  for " + $("." + Country + " option:selected").text()).css("color", "green").show();
            //           $("."+ button).prop("disabled", false)
            //         } else {
            //             $("." + error).text("Invalid format for " + $("." + Country + " option:selected").text()).css("color", "red").show();
            //           $("."+ button).prop("disabled", true)
            //         }
            //       });
        });
        // auto date calculator
        // Input date
    </script>
    <script type="text/javascript">
        $(function() {
            var currentDate = new Date();
            currentDate.setFullYear(currentDate.getFullYear() - 1); // Go back to the previous year
            currentDate.setMonth(11); // Set the month to December (0-based index)
            currentDate.setDate(31);
            $(".datepicker").datepicker({
                dateFormat: "dd/mm/yy"
            }); // Initialize datepicker for elements with class .datepicker
            $(".datepicker-dob").datepicker({
                maxDate: currentDate,
                dateFormat: "dd/mm/yy"
            }); // Initialize datepicker for elements with class .datepicker-dob
        });
        $(document).on("change", ".datepicker-dob", function(e) {
            var dobInput = $(this).val()
            // Split the input date into day, month, and year components
            var dateComponents = dobInput.split('/');
            if (dateComponents.length !== 3 && dateComponents.length > 0) {
                // Handle invalid input gracefully
                $(this).css("border", "1px solid red");
                alert('Invalid date format. Please use dd/mm/yyyy format.');
                return;
            } else {
                $(this).css("border", "1px solid #DFE3E7");
            }

        });
        $(document).on("change", ".datepicker", function(e) {
            var dobInput = $(this).val()

            // Split the input date into day, month, and year components
            var dateComponents = dobInput.split('/');

            if (dateComponents.length !== 3 && dateComponents.length > 0) {
                // Handle invalid input by adding a red border
                $(this).css("border", "1px solid red");
                alert('Invalid date format. Please use dd/mm/yyyy format.');
                return;
            } else {
                $(this).css("border", "1px solid #DFE3E7");
            }

            // If the input is valid, remove any red border
            $(this).css("border", ""); // This will remove the border
        });
    </script>

    {{-- //******************************************************* --}}
    <script>
        $(document).on("click", ".head-ledger", function(e) {
            // alert(1);
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ route('head-ledger-show') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("ledger-show-content").innerHTML = response;
                    $('#ledger-show').modal('show')
                }
            });
        });


        $(document).on("click", ".master-head-ledger", function(e) {
            // alert(1);
            e.preventDefault();
            var id = $(this).attr('id');
            $.ajax({
                url: "{{ route('master-head-ledger') }}",
                type: "post",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("ledger-show-content").innerHTML = response;
                    $('#ledger-show').modal('show')
                }
            });
        });



        $("#customerAddNew").submit(function(e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var form = $(this);
            var url = form.attr('action');
            var pi_name = $("#pi_name").val();
            var pi_type = $("#pi_type").val();
            var trn_no = $("#trn_no2").val();
            var address = $("#address2").val();
            var con_person = $("#con_person").val();
            var con_no = $("#con_no").val();
            var phone_no = $("#phone_no").val();
            var email = $("#email").val();
            // alert(mobile);
            $.ajax({
                url: url,
                method: "POST",
                data: {
                    pi_name: pi_name,
                    pi_type: pi_type,
                    trn_no: trn_no,
                    address: address,
                    con_person: con_person,
                    con_no: con_no,
                    phone_no: phone_no,
                    phone_no: phone_no,
                    email: email,
                    '_token': '{{ csrf_token() }}'
                },
                success: function(response) {
                    $(".customer").empty().append(response.page);
                    $("div.customer-select select").val(response.newCustomer.id);
                    $("#trn_no").val(response.newCustomer.trn_no);
                    $("#pi_code").val(response.newCustomer.pi_code);



                    $("#customerModal").modal('hide');
                }
            })
        });

        $(document).on("keyup", ".ajax-search", function(e) {
            e.preventDefault();
            // alert('ok');
            var that = $(this);
            var q = e.target.value;
            var url = that.attr("data-url");
            var urls = url + '?q=' + q;
            // var datalist = $("#products");
            // datalist.empty();
            // alert(urls);


            delay(function() {
                $.ajax({
                    url: urls,
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function(response) {
                        //   alert('ok');
                        // console.log(response);
                        // $(".pagination").remove();
                        $(".user-table-body").empty().append(response.page);
                    },
                    error: function() {
                        //   alert('no');
                    }
                });
            }, 999);
        });
    </script>

<script>
    // ********************************************************** Print  table*****************************************
    async function handlePrintClick(tableId) {
        const tableToPrint = document.getElementById(tableId);
        const currentDate = new Date().toLocaleDateString();
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
        if (!tableToPrint) {
            console.error(`Table element with id '${tableId}' not found.`);
            return;
        }
        try {
            // Load stylesheets directly
            const [headerResponse, footerResponse, stylesheetResponse] = await Promise.all([
                fetch('/get-header').then(response => response.text()),
                fetch('/get-footer').then(response => response.text()),
                fetch('https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css').then(
                    response => response.text())
            ]);

            iframe.contentDocument.open();
            iframe.contentDocument.write(
                `<html><head><meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"><title>lavish_perfume_print_report_${currentDate}</title></head><body>`
            );

            // Include styles directly
            iframe.contentDocument.write(
                `<style>
                @media print {
                    .print-none { display: none !important; }

                    @page {
                        margin: 1cm; /* Set margins as needed */
                    }
                    .table thead th {
                        background: #E6BC99  !important;
                        color: #fff !important;
                    }

                    .table td {
                        color: #000000b8 !important;
                    }
                    .header {
                        position: fixed;
                        top: 0;
                        left: 0;
                        right: 0;
                    }

                    .footer {
                        position: fixed;
                        bottom: 0;
                        left: 0;
                        right: 0;
                    }
                     .row-style {
                        border: 1px solid;
                        border-radius: 7px;
                        text-transform: uppercase;
                        padding: 12px;
                    }



                }
                ${stylesheetResponse}
            </style>`
            );
            iframe.contentDocument.write(headerResponse);
            iframe.contentDocument.write('<div>');
            iframe.contentDocument.write(tableToPrint.outerHTML);
            iframe.contentDocument.write('</div>');
            iframe.contentDocument.write(footerResponse);
            iframe.contentDocument.write('</body></html>');
            iframe.contentDocument.close();
            iframe.contentWindow.print();
        } catch (error) {
            console.error('Error:', error);
        } finally {
            document.body.removeChild(iframe);
        }
    }

    // Example usage:
    // handlePrintClick('table1', '/get-header', '/get-footer');

    // ********************************************************** Print  table*****************************************
</script>


</body>

</html>

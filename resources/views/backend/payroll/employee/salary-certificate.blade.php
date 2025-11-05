<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Certificate</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            color: black;
            margin: 50px;
        }
        .title {
            font-weight: bold;
            font-size: 38px;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 20px;
            font-family: sans-serif;
        }
        .date {
            text-align: right;
            font-size: 14px;
        }
        .heading {
            text-align: left;
            text-transform: uppercase;
            font-size: 16px;
            margin-top: 30px;
        }
        .content {
            font-size: 25px;
            line-height: 1.6;
            margin-top: 20px;
        }
        .content span {
            font-weight: bold;
        }
        .salary-table {
            width: 100%;
            margin-top: 30px;
        }
        .salary-table th, .salary-table td {
            text-align: center;
            padding: 10px;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            text-align: justify;
        }
        .signature {
            margin-top: 40px;
            font-size: 14px;
            font-weight: bold;
        }

        .spaced-text {
            margin-top: 10px;
            text-indent: 80px;
            word-spacing: 15px;
        }
    </style>
</head>
<body>

    <div class="container">
       <div class="row">
        <div class="col-8">

        </div>
        <div class="col-4 ">
            <div class="card-body mx-25 d-flex justify-content-center" style="text-align: center">
                <img src="{{ asset('img/imsc-logo.PNG') }}"  style="height: 250px; width: 300px;" alt=""><br>
            </div>
        </div>
        <div class="col-12">
            <div class="title">SALARY CERTIFICATE</div>
        </div>
       </div>





        <div class="content">
            <p>To: Whom It May Concern</p>
            <p class=" spaced-text">This is to certify that <span>{{$employee_info->full_name}}</span>, holder of Philippines passport no. <span>{{$employee_info->passport_number}}</span>, is currently working with our organization in the position of <span>{{$employee_info->job_title}}</span>. He is employed with us since <span>{{date('Y/m/d' ,strtotime($employee_info->joining_date))}}</span> with the monthly salary {{$basic}} AED.</p>

            <p class=" spaced-text mt-3">This letter is issued on behalf of the employee’s request and bears no financial responsibility on behalf of any of the authorized signatories.</p>
            <p class="mt-3">
                Thanking you, <br>
                For and on behalf of RAS AL KHAIMAH INTERNATIONAL MARINE SPORTS CLUB
            </p>

            <p class="mt-5 pt-5">
                Regards, <br> <br> <br> <br>
                <u>Aaref Al Haranki</u> <br>
                Chairman
            </p>
        </div>


    </div>
    <div class="img receipt-bg">
        <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid" style="position: fixed; top: 450px; left: 220px;  width: 500px !important; height: 500px; opacity: .6;" alt="">
        <img src="{{ asset('img/imsc-cert-footer.PNG') }}" class="img-fluid" style="position: fixed; bottom: 0px;  width: 100%px !important;  " alt="">
        {{-- <img src="{{ asset('img/finallogo.jpeg') }}" class="img-fluid" style="position: fixed; top:100px; left:0px; opacity:0.1;width:100%; " alt=""> --}}
    </div>
    <!-- Bootstrap JS, Popper.js, and jQuery (Optional) -->

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>

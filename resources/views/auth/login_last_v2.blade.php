<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: auto;
            margin: 0;
            padding: 0;
        }

        .form-control {
            border: none;
            border-bottom: 2px solid black;
            border-radius: 0;
            box-shadow: none;
            padding-left: 0;
        }

        .form-control:focus {
            border-bottom-color: #ccc;
            box-shadow: none;
        }

        .form-control::placeholder {
            font-weight: 600;
            color: black;
        }

        .logo img {
            width: 40%;
            max-width: 50%;
        }

        .header-section h3 {
            font-weight: 500;
        }

        .header-section h1 {
            font-weight: 500;
        }

        hr {
            border: 1px solid lightgray;
        }

        .custom-btn {
            border-radius: 0;
            width: 30%;
            color: white;
            background-color: #044B8C;
        }

        .custom-btn:hover {
            color: #ddd;
        }

        .forget-link a {
            font-weight: 600;
            color: black;
        }

        .no-gutters {
            margin-right: 0;
            margin-left: 0;
        }

        .no-gutters>.col,
        .no-gutters>[class*="col-"] {
            padding-right: 0;
            padding-left: 0;
        }

        /* Mobile Responsive */
        @media (max-width: 767.98px) {
            .header-section h3 {
                font-size: 1.2rem;
            }

            .header-section h1 {
                font-size: 1.5rem;
            }

            .logo img {
                width: 60%;
            }

            .custom-btn {
                width: 100%;
            }

            .pr-5 {
                padding-right: 1rem !important;
            }

            .left-section img {
                /*height: auto;
                max-height: 250px;
                object-fit: cover;*/
                display: none;
            }
        }
    </style>
</head>

<body style="background-color: #f6fdff;">

    <!-- Header Section -->
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-12 text-center text-md-left">
                <div class="header-section">
                    <h3 class="pt-5">Powering Construction with Smart ERP</h3>
                    <hr>
                    <h1>Innovating for the world, <br> Built in the UAE</h1>
                </div>
            </div>
            <div class="col-md-6 d-none d-md-block"></div>
        </div>
    </div>

    <!-- Logo Section (Separate) -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-7"></div>
            <div class="col-sm-5 text-center text-md-left">
                <div class="logo ">
                    <img src="{{ asset('img/zikash-logo.png')}}" alt="">
                </div>
            </div>
        </div>
    </div>

    <!-- Image + Form Section -->
    <div class="container-fluid">
        <!-- <div class="row align-items-center"> -->
        <div class="row ">
            <!-- Left Side Image -->
            <div class="col-md-7 col-12 order-2 order-md-1">
                <div class="left-section">
                    <img src="{{ asset('img/login-img.png')}}" class="w-100 img-fluid" alt="">
                </div>
            </div>

            <!-- Right Side Form -->
            <div class="col-md-5 col-12 order-1 order-md-2 text-center text-md-left">
                <div class="right-section no-gutters mt-5 pr-5">
                    <div class="login-form">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-4">
                                {{-- <input type="text" class="form-control" id="username" placeholder="USER NAME"> --}}
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="EMAIL">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                {{-- <input type="password" class="form-control" id="password" placeholder="PASSWORD"> --}}
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="PASSWORD">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="text-left mb-5 forget-link">
                                <a href="javascript:void(0)"><small>Forgot Password?</small></a>
                            </div>
                            <div class="mb-3 text-center">
                                <button type="submit" class="btn custom-btn">Log in</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

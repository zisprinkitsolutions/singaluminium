@extends('layouts.backend.app-print')
@section('content')
   
<style>
    @media print{
        html, body {
            height:100%; 
            overflow: hidden;
        }
        .printPage{
            margin-top:-700px;
        }
    }
</style>
<section id="widgets-Statistics">
    <div>
        <h4 class="text-center">Service Provider</h4>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-5">
                                <strong>Service Provider Code</strong>
                            </div>
                            <div class="col-7">
                                <strong>:</strong> {{ $pInfo->pi_code }}
                            </div>

                            <div class="col-5">
                                <strong>Service Provider Name</strong>
                            </div>
                            <div class="col-7">
                                <strong>:</strong> {{ $pInfo->pi_name }}
                            </div>

                            <div class="col-5">
                                <strong>Type</strong>
                            </div>
                            <div class="col-7">
                                <strong>:</strong> {{ $pInfo->pi_type }}
                            </div>

                            <div class="col-5">
                                <strong>TRN Number</strong>
                            </div>
                            <div class="col-7">
                                <strong>:</strong> {{ $pInfo->trn_no }}
                            </div>

                            <div class="col-5">
                                <strong>Contact Person</strong>
                            </div>
                            <div class="col-7">
                                <strong>:</strong> {{ $pInfo->con_person }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-5">
                                <strong>Contact Number</strong>
                            </div>
                            <div class="col-7">
                                <strong>:</strong> {{ $pInfo->con_no }}
                            </div>
                            <div class="col-5">
                                <strong>Phone Number</strong>
                            </div>
                            <div class="col-7">
                                <strong>:</strong> {{ $pInfo->phone_no }}
                            </div>

                            <div class="col-5">
                                <strong>Address</strong>
                            </div>
                            <div class="col-7">
                                <strong>:</strong> {{ $pInfo->address }}
                            </div>

                            <div class="col-5">
                                <strong>Email</strong>
                            </div>
                            <div class="col-7">
                                <strong>:</strong> {{ $pInfo->email }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div>
</section>
@endsection
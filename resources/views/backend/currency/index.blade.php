@extends('layouts.backend.app')
@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@endpush
@section('content')
    @include('layouts.backend.partial.style')
    <!-- BEGIN: Content-->

    <div class="app-content content print-hidden">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.accountFinance._accounting_finance_header', [
                    'activeMenu' => 'currency',
                ])
                <div class="tab-content bg-white">

                    <div class="card cardStyleChange">
                        <section id="widgets-Statistics" class="mr-1 ml-1 pt-2">

                            <div class="row" style="padding-left: 10px; padding-right: 10px">
                                <div class="col-12">
                                    <form action="{{ route('account-info.store') }}" method="POST">
                                        @csrf
                                        <div class="row match-height">
                                            <div class="col-md-3 changeColStyle">
                                                <label>Country Name</label>
                                                <input type="text" id="" class="form-control inputFieldHeight"
                                                    value="@if (!empty($currency->country_name)) {{ $currency->country_name }} @endif"
                                                    name="country_name" placeholder="Country Name">
                                                @error('country_name')
                                                    <div class="btn btn-sm btn-danger">{{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-3 changeColStyle">
                                                <label>Currency Name</label>
                                                <input type="text" id=""
                                                    class="form-control inputFieldHeight"value="@if (!empty($currency->currency_nane)) {{ $currency->currency_nane }} @endif"
                                                    name="currency_nane" placeholder="Currency Name">
                                                @error('currency_name')
                                                    <div class="btn btn-sm btn-danger">{{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-3 changeColStyle">
                                                <label>Symbol</label>
                                                <input type="text" id=""
                                                    class="form-control inputFieldHeight"value="@if (!empty($currency->symbole)) {{ $currency->symbole }} @endif"
                                                    name="symbol" placeholder="Symbol" required>
                                                @error('proj_name')
                                                    <div class="btn btn-sm btn-danger">{{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-3 changeColStyle">
                                                <label>LICENCE NAME</label>
                                                <input type="text" id=""
                                                    class="form-control inputFieldHeight"value="@if (!empty($currency->licence_name)) {{ $currency->licence_name }} @endif"
                                                    name="licence_name" placeholder="Licence Name" required>
                                                @error('proj_name')
                                                    <div class="btn btn-sm btn-danger">{{ $message }} </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-3 changeColStyle">
                                                <label>VAT NAME</label>
                                                <input type="text" id=""
                                                    class="form-control inputFieldHeight"value="@if (!empty($currency->vat_name)) {{ $currency->vat_name }} @endif"
                                                    name="vat_name" placeholder="Vat Name" required>
                                                @error('proj_name')
                                                    <div class="btn btn-sm btn-danger">{{ $message }} </div>
                                                @enderror
                                            </div>

                                            <div class="col-3 col-md-3 d-flex   mb-1 mt-2">
                                                <button type="submit" class="btn mr-1 btn-primary formButton"
                                                    title="Form Save">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}"
                                                                alt="" srcset="" class="img-fluid"
                                                                width="25">
                                                        </div>
                                                        <div><span> Save</span></div>
                                                    </div>
                                                </button>
                                                <button type="reset" class="btn btn-light-secondary formButton"
                                                    title="Form Reset">
                                                    <div class="d-flex">
                                                        <div class="formRefreshIcon">
                                                            <img src="{{ asset('assets/backend/app-assets/icon/refresh-icon.png') }}"
                                                                alt="" srcset="" class="img-fluid"
                                                                width="25">
                                                        </div>
                                                        <div><span> Reset</span></div>
                                                    </div>
                                                </button>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

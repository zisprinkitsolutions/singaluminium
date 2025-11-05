<style>
    @media{
        .border1{
            color: red !important;
        }
        .border2{
            color: green !important;
        }
    }
</style>
@php
    $company_name = App\Setting::where('config_name', 'company_name')->first();
    $company_name_arabic = App\Setting::where('config_name', 'company_name_arabic')->first();
    $company_email = App\Setting::where('config_name', 'company_email')->first();
    $company_tele = App\Setting::where('config_name', 'company_tele')->first();
    $company_fax = App\Setting::where('config_name', 'company_fax')->first();
    $company_mobile = App\Setting::where('config_name', 'company_mobile')->first();
@endphp
<section class="invoice-view-wrapper">
    <div class="row">
        <div class="col-md-5 col-xl-5 col-5">
            <h4 class="text-left pl-1" style="font-size: 23px !important; color: #ff0000c0 !important;"><strong>{{$company_name->config_value}}</strong></h4>
            <h5 class="text-left pl-1">
                Tel: {{$company_tele->config_value}} <br>
                Fax: {{$company_fax->config_value}} <br>
                Mob: {{$company_mobile->config_value}} <br>
            </h5>
        </div>
        <div class="col-xl-2 col-md-2 col-2 text-center p-0 m-0">
            <div>
                <img src="{{ asset('img/alhareb-logo.PNG') }}"alt=""style="height: 100px !important">
            </div>
            <p class=" text-center p-0 m-0" style="color: #ff0000c0 !important;">Email:{{$company_email->config_value}}</p>
        </div>
        <div class="col-md-5 col-xl-5 col-5">
            <h2 class="text-right pr-1" style="color: #ff0000c0 !important;">{{$company_name_arabic->config_value}}</h2>
            <h5 class="text-right pr-1">
                هاتف: {{$company_tele->config_value}} <br>
                فاكس: {{$company_fax->config_value}} <br>
                الغوغاء: {{$company_mobile->config_value}} <br>
            </h5>
        </div>
    </div>
    <div style="border-top: 3px solid #ff0000c0 !important" style=" margin-bootm: 5px !important; "></div>
    <div style="border-top: 3px solid #008000b6 !important" ></div>
</section>

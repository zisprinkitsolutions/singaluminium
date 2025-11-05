@php
    $company_address_arabic= \App\Setting::where('config_name', 'company_address_arabic')->first();
    $company_address= \App\Setting::where('config_name', 'company_address')->first();
    $company_tele= \App\Setting::where('config_name', 'company_tele')->first();
    $company_email= \App\Setting::where('config_name', 'company_email')->first();
    $trn_no= \App\Setting::where('config_name', 'trn_no')->first();
    $arabic_context= \App\Setting::where('config_name', 'arabic_context')->first();
    $po_box= \App\Setting::where('config_name', 'p.o_box')->first();

@endphp
{{-- <div class="divFoote invoice-view-wrapper" style="background: #f6f5f5 ;">

    <p style="border-top: 3px solid #ff0000c0 !important" style="margin-bootm: 5px !important;"></p>
    <p style="border-top: 3px solid #008000b6 !important" ></p>
    <p class="text-center" style="text-align: center !important; color: black">
        P.O. Box: {{$po_box->config_value}}, {{$company_address->config_value}} صندوق بريد: {{$po_box->config_value}}, {{$company_address_arabic->config_value}}</p>
</div> --}}
<div class="divFooter text-left pl-2">
    Business Software Solutions by
    <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png') }}" alt="" width="50"></span>
</div>

@php
    $school_name = App\Setting::where('config_name', 'school_name')->first();
    $school_address = App\Setting::where('config_name', 'school_address')->first();
    $school_tele = App\Setting::where('config_name', 'school_tele')->first();
    $school_email = App\Setting::where('config_name', 'school_email')->first();
@endphp
<section>
    <div class="divFlex" style="margin-bottom:30px;">
        <div style="margin-left:15px;padding-left:15px;">
            <img src="{{ asset('assets/backend/app-assets')}}/beps-logo.png"  style="height: 100px;">
        </div>
        <div style="text-align: center; margin-top: -100px;">
            {{-- <h2 style="line-height: .1;"> <img src="{{ asset('assets/backend/app-assets')}}/arabic-fornt.png"  style="height: 40px; width: 300px; padding-bottom:20px;"><br>{{$school_name->config_value}}</h2> --}}
            <h3 style="margin-left: 100px;">BANGLADESH ENGLISH PRIVATE SCHOOL</h3>
            <h4 style="margin-left: 100px;">RAS AL KHAIMAH, U.A.E.</h4>
            {{-- <p style="margin-bottom:0px; font-size: 10px;">
                RECOGNISED BY MINISTRY OF EDUCTION -U.A.E - (AFFILIATED OT BISE, DHAKA SCHOOL CODE:9601, COLLEGE CODE 9526, EIIN:133909)
            </p>
            <p style="margin-top:0px; font-size: 11px;">
                <span>Tel: {{$school_tele->config_value}}.</span>
                <span>Email: {{$school_email->config_value}} </span>
                <span>P.O.Box: 12275 - </span>
                <span>{{$school_address->config_value}}</span>
            </p> --}}
        </div>
    </div>
</section>

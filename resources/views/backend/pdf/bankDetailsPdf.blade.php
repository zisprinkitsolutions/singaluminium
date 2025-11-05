

@php
$company_name= \App\Setting::where('config_name', 'company_name')->first();
$company_address= \App\Setting::where('config_name', 'company_address')->first();
$company_tele= \App\Setting::where('config_name', 'company_tele')->first();
$company_email= \App\Setting::where('config_name', 'company_email')->first();
$trn_no= \App\Setting::where('config_name', 'trn_no')->first();

@endphp
<section class="print-hideen border-bottom">
    <div class="d-flex flex-row-reverse">
        <div class="mIconStyleChange"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
    </div>
</section>
<section id="widgets-Statistics">
    <div class="container pt-2">
        <div class="row">
            <div class="col-md-1">
                <img src="{{ asset('img/finallogo.jpeg') }}"  style="height: 100px" alt="">
            </div>
            <div class="col-md-10 text-center">
                <h2>{{ $company_name->config_value }}</h2>
                <h6>{{ $company_address->config_value }}</h6>
                <div class="row">
                    <div class="col-6 text-right">
                        <h6>Mobile {{ $company_tele->config_value }}</h6>
                    </div>
                    <div class="col-6 text-left">
                        <h6>TRN {{ $trn_no->config_value }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
         <section id="widgets-Statistics">
             <div class="row">
                 <div class="col-12 mt-1 mb-2">
                     <h4>Bank Details</h4>
                 </div>
             </div>
            <div class="row">
            <table id="customers" class="table table-sm table-hover">
                <thead class="thead-light">
                    <tr style="height: 50px;">
                        <th>Bank Code</th>
                        <th>Bank Name</th>
                        <th>Bank Branch</th>
                        <th>Account Title</th>
                        <th>Account Number</th>
                        </tr>
                </thead>
                @foreach ($bankDetails as $bank)
                    <tr>
                        <td>{{ $bank->bank_code }}</td>
                        <td>{{ $bank->bank_name }}</td>
                        <td>{{ $bank->branch }}</td>
                        <td>{{ $bank->signatory }}</td>
                        <td>{{ $bank->ac_no }}</td>
                    </tr>
                @endforeach
            </table>
            </div>
         </section>
        </div>
    </div>
</div>

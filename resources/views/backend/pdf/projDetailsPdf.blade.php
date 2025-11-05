
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
<div class="container">
    <div class="row">
        <div class="col-md-12">
         <section id="widgets-Statistics">
             <div class="row">
                 <div class="col-12 mt-1 mb-2">
                     <h4>Project Details</h4>
                 </div>
             </div>

                 <div class="row">
                        <table id="customers" class="table table-sm table-hover">
                            <thead class="thead-light">
                                <tr style="height: 50px;">
                                    <th>Branch No</th>
                                    <th>Branch Name</th>
                                    <th>Branch Type</th>
                                    <th>Manager</th>
                                    <th>Site Address</th>
                                    <th>Office Phone No</th>
                                    <th>Mobile Number</th>
                                    <th>Trade License Issue Date</th>
                                    <th>License Expiery</th>
                                    <th>Profit Center</th>
                                </tr>
                            </thead>

                            @foreach ($projDetails as $proj)
                            <tr>
                                <td>{{ $proj->proj_no }}</td>
                                            <td>{{ $proj->proj_name }}</td>
                                            <td>{{ $proj->proj_type }}</td>
                                            <td>{{ $proj->owner_name }}</td>

                                            <td>{{ $proj->address }}</td>
                                            <td>{{ $proj->cons_agent }}</td>
                                            <td>{{ $proj->cont_no }}</td>
                                            <td>{{ $proj->ord_date }}</td>
                                            <td>{{ $proj->hnd_over_date }}</td>
                                            <td>{{ $proj->profitCenter($proj->pc_code)->pc_name }}</td>

                            </tr>

                            @endforeach


                        </table>
                 </div>
         </section>
        </div>
    </div>
</div>

<style>
    html,
    body {
        height: 100%;
    }

    thead {
        background: #34465b;
        color: #fff !important;
        height: 30px;
    }
    .receipt-bg{
        display: none;
    }

    .remove-document{
        border: none;
        border-radius: 50%:
    }

    .document-file:hover .remove-document{
        display: block !important;
    }
    @media print{
        .receipt-bg{
            display: block;
        }
        .col-md-4{
            flex: 0 0 33.33333%;
            width: 33.33333% !important;
        }

        .flex-lg-row{
            flex-direction: row !important;
        }
    }
</style>
@php
    $company_name= \App\Setting::where('config_name', 'company_name')->first();
    $company_address= \App\Setting::where('config_name', 'company_address')->first();
    $company_tele= \App\Setting::where('config_name', 'company_tele')->first();
    $company_email= \App\Setting::where('config_name', 'company_email')->first();
    $trn_no= \App\Setting::where('config_name', 'trn_no')->first();

@endphp
<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse" style="padding-top: 5px;padding-right: 8px;">
        <div class="pr-1" style="margin-top: 5px;">
            <a href="#" class="close btn-icon btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Close"><span
                    aria-hidden="true" data-dismiss="modal" aria-label="Close" onclick="window.location.reload();"><i class='bx bx-x'></i></span></a>
        </div>
        @if ($fund->approved==false)
        <div class="" style="padding-right: 3px;margin-top: 6px;">
            <a href="{{ route('fund-allocation-approval', $fund->id) }}" class="btn btn-icon btn-warning" onclick="return confirm('Approve! Confirm?')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Approve"><i class="bx bx-check"></i></a>
        </div>

        <div class="" style="padding-right: 3px;margin-top: 6px;">
            <a href="{{route('allocation-print',$fund)}}" class="btn btn-icon btn-success univarsal-print"  title="Allocation Print"><i class="bx bx-printer"></i></a>
        </div>

        @endif

        <div class="pr-1 w-100 pl-2">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">Allocation</h4>
        </div>
    </div>
</section>
<section class="print-hideen">
    <div class="cardStyleChange bg-white p-2">
        <div class="row">
            <div class="col-sm-12">
                <div class="customer-info">
                    <div class="row ml-1 mr-1 "style="border: 2px solid #bdbdbd;">
                        <div class="col-sm-2 customer-static-content">
                            From Account: <br>
                            Amount: <br>
                            Transaction Cost: <br>
                        </div>
                        <div class="col-sm-4 customer-dynamic-content">
                            {{ $fund->fromAccount->title }} <br>
                            {{ $fund->amount }} <br>
                            {{ $fund->transaction_cost }} <br>
                        </div>

                        <div class="col-sm-2 customer-static-content">
                            To Account: <br>
                            Date: <br>
                            Transaction Number: <br>
                        </div>
                        <div class="col-sm-4 customer-dynamic-content">
                            {{ $fund->toAccount->title }} <br>
                            {{ $fund->date }} <br>
                            {{ $fund->transaction_number }} <br>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="customer-info">
                    <div class="row ml-1 mr-1 "style="border: 2px solid #bdbdbd;">
                        <div class="col-sm-12 customer-static-content" style="text-align: center !important">
                            Note: {{$fund->note}}
                        </div>


                    </div>
                </div>
            </div>


        <div>
            <div class="row p-2" id="documents">
                @if($fund->documents->count() > 0)
                    @foreach ($fund->documents as $document)
                    <div class="col-md-2 text-center py-1 px-4 print-hideen document-file" id="document-{{$document->id}}">
                        <button class="remove-document py-1 d-none" id={{$document->id}}>
                            <i class="bx bx-trash text-danger"></i>
                        </button>
                        <a href="{{ asset('storage/upload/fund-allocation/' . $document->file_name) }}" target="blank">
                            <img src="{{ asset('storage/upload/fund-allocation/' . $document->file_name) }}" class="img-fluid" style="width:100%;" alt="{{$document->ext}}">
                        </a>
                    </div>
                    @endforeach
                @endif
            </div>
    </div>
</section>

<div class="img receipt-bg">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid" style="position: fixed; top: 220px; left: 170px; opacity: 0.3; width: 600px !important; height: 400px;" alt="">
</div>
<div class="img receipt-bg">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid" style="position: fixed; top: 920px; left: 170px; opacity: 0.3; width: 600px !important; height: 400px;" alt="">
</div>


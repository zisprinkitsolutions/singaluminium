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
    /* .print-hidden{
        display: none !important;
    } */
    @media print{
        .receipt-bg{
            display: block;
        }
        .col-md-4{
            width: 33.33% !important;
        }

        .flex-lg-row{
            flex-direction: row !important;
        }

        .row{
            display: flex !important;
        }
        .col-md-1{
            width: 8.33% !important;
        }
        .col-md-2{
            width: 16.66% !important;
        }
        .col-md-3{
            width: 25% !important;
        }
        .col-md-4{
            width: 33.33% !important;
        }
        .col-md-5{
            width: 41.65% !important;
        }
        .col-md-6{
            width: 50% !important;
        }
        .col-md-7{
            width: 58.33% !important;
        }
        .col-md-8{
            width: 66.66% !important;
        }
        .col-md-9{
            width: 75% !important;
        }
        .col-md-10{
            width: 83.33% !important;
        }
        .col-md-11{
            width: 91.63% !important;
        }
        .col-md-12{
            width: 100% !important;
        }
        .print-hidden{
            /* display: block !important; */
            display: none !important;
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
<section class="print-hidden border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse" style="padding-top: 5px;padding-right: 8px;">
        <div class="pr-1" style="margin-top: 5px;">
            <a href="#" class="close btn-icon btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Close"><span
                    aria-hidden="true" data-dismiss="modal" aria-label="Close" onclick="window.location.reload();"><i class='bx bx-x'></i></span></a>
        </div>
        {{-- @if ($fund->approved==false)
            @if(Auth::user()->hasPermission('Accounting_Approve'))
            <div class="" style="padding-right: 3px;margin-top: 6px;">
                <a href="{{ route('fund-allocation-approval', $fund->id) }}" class="btn btn-icon btn-warning" onclick="return confirm('Approve! Confirm?')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Approve"><i class="bx bx-check"></i></a>
            </div>
            @endif
        @endif

        <div class="" style="padding-right: 3px;margin-top: 6px;">
            <a href="{{route('allocation-print', $fund->id)}}" class="btn btn-icon btn-success univarsal-print"  title="Allocation Print"><i class="bx bx-printer"></i></a>
        </div> --}}
        <div class="pr-1 w-100 pl-2">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">Allocation</h4>
        </div>
    </div>
</section>
<section class="">
    <div class="receipt-voucher-hearder invoice-view-wrapper" style=" border: 1px solid; margin: 50px 20px; border-radius: 20px;">
        @include('layouts.backend.partial.modal-header-info')
    </div>
    <div class="cardStyleChange bg-white p-2">
        <h2 class="text-center invoice-view-wrapper"></h2>
        <div class="row">
            <div class="col-md-12">
                <div class="customer-info">
                    <div class="row ml-1 mr-1 "style="border: 2px solid #bdbdbd;">
                        <div class="col-md-2 customer-static-content" style="width: 16.66% !important;">
                            From Account: <br>
                            Amount: <br>
                            Transaction Cost: <br>
                        </div>
                        <div class="col-md-4 customer-dynamic-content" style="width: 33% !important;">
                            {{ $fund->fromAccount->title }} <br>
                            {{ number_format($fund->amount,2) }} <br>
                            {{ number_format($fund->transaction_cost,2) }} <br>
                        </div>

                        <div class="col-md-2 customer-static-content" style="width: 16.66% !important;">
                            To Account: <br>
                            Date: <br>
                            Transaction Number: <br>
                        </div>
                        <div class="col-md-4 customer-dynamic-content" style="width: 33% !important;">
                            {{ $fund->toAccount->title }} <br>
                            {{ date('d/m/Y', strtotime($fund->date)) }} <br>
                            {{ $fund->transaction_number }} <br>
                        </div>
                    </div>
                    <p class="row m-1" style="border: 2px solid #bdbdbd;">Note: {{$fund->note}}</p>
                </div>
            </div>


        @if($fund->documents->count() > 0)
        <div>
            <div class="row p-2" id="documents">
                @foreach ($fund->documents as $document)
                <div class="col-md-2 text-center py-1 px-4 print-hidden document-file" id="document-{{$document->id}}">
                    {{-- <button class="remove-document py-1 " id={{$document->id}}>
                        <i class="bx bx-trash text-danger"></i>
                    </button> --}}
                    <a href="{{ asset('storage/upload/fund-allocation/' . $document->file_name) }}" target="blank">
                        <img src="{{ asset('storage/upload/fund-allocation/' . $document->file_name) }}" class="img-fluid" style="width:100%;" alt="{{$document->ext}}">
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="d-flex flex-row-reverse justify-content-center print-hidden">
        @if ($fund->approved==false)
            @if(Auth::user()->hasPermission('Accounting_Approve'))
            <div class="" >
                <a href="{{ route('fund-allocation-approval', $fund->id) }}" class="btn btn-icon btn-success custom-action-btn "
                    onclick="return confirm('Approve! Confirm?')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Approve Now">
                    <i class="bx bx-check"></i> Approve
                </a>
            </div>
            @endif
        @endif
         <div class="">
                <a href="{{ route('fund-allocation-delete', $fund->id) }}"
                    class="btn btn-icon btn-danger custom-action-btn " onclick="return confirm('Delete! Confirm?')"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Approve Now">
                    <i class="bx bx-check"></i> Delete
                </a>
            </div>

        <div class="" >
            <a href="{{route('allocation-print', $fund->id)}}" class="btn btn-icon btn-secondary univarsal-print custom-action-btn "  title="Print Now">
                <i class="bx bx-printer"></i> Print
            </a>
        </div>
    </div>
</section>



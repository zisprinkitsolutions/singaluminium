
    <style>
        .table-bordered {
            border: 1px solid #f4f4f4;
        }
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }
        table {
            background-color: transparent;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
        }
        .tarek-container {
            width: 85%;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 88% 12%;
            background-color: #ffff;
        }
        .invoice-label {
            font-size: 10px !important
        }
        @media print{
            html, body {
                height:100%; 
                overflow: hidden;
            }
        }
    </style>
<section class="print-hideen border-bottom">
    <div class="d-flex flex-row-reverse">
        <div class="mIconStyleChange"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
        <div class="mIconStyleChange"><a href="{{route('service-provider.edit', $pInfo->id)}}" class="btn btn-icon btn-success"><i class="bx bx-edit"></i></a></div>
        <div class="mIconStyleChange"><a href="#" class="btn btn-icon btn-secondary partyCenterPrint" id="{{$pInfo->id}}" data-dismiss="modal"><i class='bx bx-printer'></i></a></div>
        {{-- <div class="mIconStyleChange"><a href="#" onclick="window.print();" class="btn btn-icon btn-primary"><i class='bx bxs-file-pdf'></i></a></div> --}}
        {{-- <div class="mIconStyleChange"><a href="#" onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
    </div>
</section>
@include('backend.tab-file.modal-header-info')
<section id="widgets-Statistics">
    <div class="row">
        <div class="col-md-12">
            <div class="row d-flex align-items-center">
                <div class="col-6">
                    <h4 class="ml-2">Service Provider</h4>
                </div>
            </div>
        </div>
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
                <div class="row">
                    <div class="col-md-2 mt-2">
                        <label for=""><strong>Contact Document</strong></label>
                        <div class="" style="height: 120px; width: 120px;">
                            @if ($pInfo->extension1 == 'pdf')
                                <a href="{{ asset('storage/upload/service-provider/'.$pInfo->document1)}}" target="_blank">
                                    <img src="{{ asset('/icon/pdf-download-icon-2.png')}}" alt="" style="height: 70px; width: 70px;">
                                </a>
                            @else
                                @if ($pInfo->document1)
                                    <a href="{{ asset('storage/upload/service-provider/'.$pInfo->document1)}}" target="_blank">
                                        <img src="{{ asset('storage/upload/service-provider/'.$pInfo->document1)}}" alt="" style="height: 70px; width: 70px;">
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="col-md-10 mt-2">
                        <label for=""><strong>Others Document</strong></label>
                        <div class="row" style="margin-left: 10px !important">
                                @if(count($others) != 0)
                                    @foreach($others as $others)
                                        <div class="col-md-1" style="padding: 0px !important">
                                            @if ($others->extension == 'pdf')
                                                <a href="{{ asset('storage/upload/service-provider/'.$others->filename)}}" target="_blank">
                                                    <img src="{{ asset('/icon/pdf-download-icon-2.png')}}" alt="" style="height:70px; width: 70px;"  alt="" title="{{$others->name}}">
                                                </a>
                                            @else
                                                <a href="{{ asset('storage/upload/service-provider/'.$others->filename)}}" target="_blank">
                                                    <img src="{{ asset('storage/upload/service-provider/'.$others->filename)}}" alt="" style="height:70px; width: 70px;"   alt="" title="{{$others->name}}">
                                                </a>
                                            @endif    
                                        </div>
                                    @endforeach
                                @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('backend.tab-file.modal-footer-info')
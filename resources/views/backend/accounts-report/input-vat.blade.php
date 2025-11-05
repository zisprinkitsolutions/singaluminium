@extends('layouts.backend.app')
@php
    $company_name= \App\Setting::where('config_name', 'company_name')->first();
    $company_address= \App\Setting::where('config_name', 'company_address')->first();
    $address2= \App\Setting::where('config_name', 'address2')->first();
    $company_tele= \App\Setting::where('config_name', 'company_tele')->first();
    $company_email= \App\Setting::where('config_name', 'company_email')->first();
    $trn_no= \App\Setting::where('config_name', 'trn_no')->first();
    $i=1;
@endphp
@push('css')
@include('layouts.backend.partial.style')
<style>
    .changeColStyle span{
        min-width: 16%;
    }
    .changeColStyle .select2-container--default .select2-selection--single .select2-selection__arrow b{
        display: none;
    }
    .journaCreation{
        background: #1214161c;
    }
    .transaction_type{
        padding-right:5px;
        padding-left:5px;
        padding-bottom:5px;
    }
    @media only screen and (max-width: 1500px) {
        .custome-project span{
            max-width: 140px;
        }
    }

    thead {
        background: #34465b;
        color: #fff !important;
    }
    th{
        color: #fff !important;
        font-size: 11px !important;
        height: 25px !important;
        text-align: center !important;
    }
    td
    {
        font-size: 12px !important;
        height: 25px !important;
        text-align: center !important;
    }

    .table-sm th, .table-sm td {
        padding: 0rem;
    }

    tr:nth-child(even) {
        background-color: #c8d6e357;
    }
    tr{
    cursor: pointer;
}
</style>
@endpush
@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.report._header', [ 'activeMenu' => 'vat_reports'])
            <div class="tab-content journaCreation">
                <div id="journaCreation" class="tab-pane bg-white active">
                    <section id="widgets-Statistics ">
                        <div class="d-flex justify-content-between align-items-center p-1">
                            @include('clientReport.report.vat_report_subheader', ['activeMenu' => 'input_vat'])
                        </div>

                        <div class="cardStyleChange print-menu">
                            <div class="card-body">
                                <div class="row ml-2">
                                    <div class="col-md-3 pl-0">
                                        <form action="" method="GET" class="d-flex row">
                                            <div class="row form-group col-md-8" style="padding-left:7px;">
                                                <input type="text"
                                                    class="inputFieldHeight form-control datepicker" name="date"
                                                    placeholder="Select Date" required autocomplete="off">
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn mSearchingBotton mb-2 formButton"
                                                    title="Search">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                width="25">
                                                        </div>
                                                        <div><span>Search</span></div>
                                                    </div>
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-5 ">
                                        <form action="" method="GET" class="d-flex row">
                                            <div class="row form-group col-md-5">
                                                <input type="text"
                                                    class="inputFieldHeight form-control datepicker" name="from"
                                                    placeholder="From Date" required autocomplete="off">
                                            </div>
                                            <div class="row form-group col-md-5 ml-1">
                                                <input type="text"
                                                    class="inputFieldHeight form-control datepicker" name="to"
                                                    placeholder="To Date" required autocomplete="off">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn mSearchingBotton mb-2 formButton"
                                                    title="Search">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                width="25">
                                                        </div>
                                                        <div><span>Search</span></div>
                                                    </div>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-3 col-padding-right pl-0">
                                        <a href="#" onclick="media_print('input_vat')" class="btn btn-icon btn-secondary"><i class="bx bx-printer"></i>Print</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="card-body pt-0 pb-0 daily-summery" id="input_vat">
                                @include('layouts.backend.partial.modal-header-info')
                                <h2 class="text-center">Input VAT Report</h2>
                                <h4 class="text-center">
                                    @if ($from && $to)
                                        Date:From {{date('d/m/Y', strtotime($from))}} To {{date('d/m/Y', strtotime($to))}}
                                    @else
                                        Date: {{date('d/m/Y', strtotime($date))}}
                                    @endif
                                </h4>
                                <table class="table table-bordered table-sm text-center">
                                    <thead class="thead">
                                        <tr >
                                            <th style="text-align: center !important; width: 20%">SUPPLIER NAME</th>
                                            <th style="text-align: center !important; width: 10%">TRN NUMBER</th>
                                            <th style="text-align: center !important; width: 8%">DATE</th>
                                            <th style="text-align: center !important; width: 12%">INVOICE NUMBER</th>
                                            <th style="text-align: center !important; width: 20%">DESCRIPTION</th>
                                            <th style="text-align: center !important; width: 12%">TAXABLE AMOUNT</th>
                                            <th style="text-align: center !important; width: 8%">VAT AMOUNT</th>
                                            <th style="text-align: center !important; width: 10%">TOTAL AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody id="purch-body">
                                        @foreach ($input_vats as $item)
                                        <tr class="purch_exp_view" id="{{$item->id}}">
                                            <td style="text-align: center !important;">{{$item->party->pi_name??''}}</td>
                                            <td style="text-align: center !important;">{{$item->party->trn_no??''}}</td>
                                            <td style="text-align: center !important;">{{date('d/m/Y',strtotime($item->date))}}</td>
                                            <td style="text-align: center !important;">{{$item->purchase_no}}</td>
                                            <td style="text-align: center !important;">{{$item->narration}}</td>
                                            <td style="text-align: center !important;">{{number_format($item->amount,2)}}</td>
                                            <td style="text-align: center !important;">{{number_format($item->vat,2)}}</td>
                                            <td style="text-align: center !important;">{{number_format($item->total_amount,2)}}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="6" class="text-right pr-1"><Strong>Total VAT</Strong></td>
                                            <td>{{number_format($input_vats->sum('vat'),2)}}</td>
                                            <td></td>
                                        </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>


    <div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="voucherPreviewShow">

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="project-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px;">
              <h5 class="modal-title" id="exampleModalLabel"> View Project </h5>
              <div class="d-flex align-items-center">
                <button type="button" class="print-page project-btn bg-dark" style="margin:0 5px;">
                    <span aria-hidden="true">  <i class="bx bx-printer text-white"></i> </span>
                </button>
                <button type="button" class="project-btn bg-dark text-white" data-dismiss="modal" aria-label="Close" style="margin:0 5px;">
                    <span aria-hidden="true">&times;</span>
                </button>
              </div>

            </div>
            <div class="modal-body" style="padding: 5px 15px;">

            </div>
          </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    window.onafterprint = function() {
        location.reload();
        $(".datepicker").datepicker({
            dateFormat: "dd/mm/yy"
        });
    };
    $(document).on("click", ".purch_exp_view", function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        $.ajax({
            url: "{{ URL('purch-exp-modal') }}",
            type: "post",
            cache: false,
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
            },
            success: function(response) {
                document.getElementById("voucherPreviewShow").innerHTML = response;
                $('#voucherPreviewModal').modal('show')
            }
        });
    });
</script>
@endpush

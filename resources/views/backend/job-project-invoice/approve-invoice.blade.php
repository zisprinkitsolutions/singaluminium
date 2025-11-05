@extends('layouts.backend.app')
@push('css')
@include('layouts.backend.partial.style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
<style>
    .project-btn{
        border: none;
        color: #fff;
        font-size: 15px;
        font-weight: 500px;
        padding:3px 10px;
        border-radius: 5px;
        height: 30px;
    }
    .btn-sky{
        background-color: #7DE5ED;
    }
    .btn-dark-blue{
        background-color: #5F6F94;
    }
    .btn-light-green,.btn-light-green:hover{
        background-color: #1F8A70;
        text-decoration: none;
        color: #fff;
    }
    .btn-dark-blue{
        background-color: #5F6F94;
    }
    .btn-light-green,.btn-light-green:hover{
        background-color: #1F8A70;
        text-decoration: none;
        color: #fff;
    }

    .sub-btn{
        border:1px solid #475F7B;
        background-color: #fff;
        border-radius: 15px;
        color: #475F7B;
        padding: 3px 6px 3px 6px !important;
    }

    .action-btn{
        background-color: #5F6F94;
        height: 35px;
    }
    .sub-btn:hover,
    .sub-btn.active{
        background-color: #34465b  !important;
        color:white  !important;
    }
    .sub-btn.active:hover{
        background-color: #c8d6e357  !important;
        color:black  !important;
    }
    .form-control{
        height: 35px;
    }
</style>
@endpush

@section('content')
<div class="app-content content print-hideen">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            @include('clientReport.project._header')
            <div class="tab-content bg-white">
                <div id="journaCreation" class="tab-pane active">
                    <section class="p-1" id="widgets-Statistics">
                        <div class="row" >
                            <div class="col-md-7 d-flex">
                                {{-- <a href="{{ route('project.invoice.index') }}" class="project-btn create-btn sub-btn d-inline-block"> Authorization  </a> --}}
                                <a href="{{ route('project.authorize.invoice') }}" class="project-btn create-btn sub-btn ml-1 d-inline-block"> Waiting for Approval </a>
                                <a href="{{ route('project.approve.invoice') }}" class="project-btn create-btn sub-btn ml-1 active d-block"> Invoice List </a>
                            </div>
                            <div class="col-md-5 justify-content-end" >
                                <form action="{{ route('project.approve.invoice') }}" method="get" class="ml-1">
                                    <div class="form-group d-flex ">
                                        <input type="text" name="filter_date" placeholder="Date" class="form-control w-25 date" autocomplete="off">
                                        <input type="text" name="search" class="form-control search w-75" placeholder="Search Invoice" autocomplete="off">
                                        <!-- <button type="submit" class="project-btn action-btn"> Search   </button> -->
                                        <button type="submit" class="project-btn action-btn bg-info" title="Search" style="background: #9ba19c;color: white;">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}" width="25">
                                                </div>
                                                <div><span>Search</span></div>
                                            </div>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="data-table table-responsive mt-2">
                            <table class="table table-sm">
                                <thead style="background-color:#34465b !important;">
                                    <tr class="text-center">
                                        <th style="color:#fff;"> Invoice No </th>
                                        <th style="color:#fff;"> Project </th>
                                        <th style="color:#fff;"> Customer </th>
                                        <th style="color:#fff;"> Date </th>
                                        <th style="color:#fff;"> Amount </th>
                                        <th style="color:#fff;"> Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $invoice)
                                        <tr class="text-center">
                                            @if( $invoice->invoice_type == 'Tax Invoice')
                                            <td> {{ $invoice->invoice_no }}</td>
                                            @else
                                            <td> {{ $invoice->proforma_invoice_no }}</td>
                                            @endif
                                            <td> {{ $invoice->project_id?$invoice->new_project->name:$invoice->project->project_name }} </td>
                                            <td> {{ $invoice->party->pi_name }} </td>
                                            <td class="text-center"> {{ date('d/m/Y',strtotime($invoice->date)) }}</td>
                                            <td  class="text-center"> {{number_format($invoice->total_budget,2)}}</td>

                                            <td  class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <button class="project-btn btn-primary view-approve-invoice" data-url="{{ route('project.approve.invoice.show',$invoice->id) }}" title="View"><i class="fa fa-eye text-white"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {!! $invoices->links() !!}
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="project-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        {{-- <div class="modal-header  print-hideen" style="padding: 5px 15px;background:#364a60;">
            <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:#fff;padding-left: 12px;"> Invoice </h5>
            <div class="d-flex align-items-center">
              <button type="button" class="print-page project-btn bg-success " style="margin-right: 0.2rem !important;">
                  <span aria-hidden="true">  <i class="bx bx-printer text-white"></i> </span>
              </button>
              <button type="button" class="project-btn bg-danger text-white" data-dismiss="modal" aria-label="Close" style="margin-right: 1.1rem !important;">
                  <span aria-hidden="true">&times;</span>
              </button>
            </div>

          </div> --}}
        <div class="modal-body" style="padding: 5px 15px;">

        </div>
      </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).on('mouseenter','.date',function(){
    $('.date').datepicker({dateFormat:'dd/mm/yy'});
})
$(document).on('click','.view-approve-invoice',function(e){
    e.preventDefault();
    let url = $(this).attr('data-url');
    $.get(url,function(res){
        $('.modal-body').html(res);
        $('.modal-title').html('Invoice');
        $('#project-modal').modal('show');
    })
});

$(document).on('click','.print-page',function(){
    window.print();
})
</script>

@endpush

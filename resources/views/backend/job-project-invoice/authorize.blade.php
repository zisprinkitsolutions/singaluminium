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
    tr:nth-child(even) {
        background-color: #c8d6e357;
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
                        <div class="row">
                            <div class="col-md-8 d-flex">
                                {{-- <a href="{{ route('project.invoice.index') }}" class="project-btn create-btn sub-btn"> Authorization  </a> --}}

                                <a href="{{ route('project.authorize.invoice') }}" class="project-btn create-btn sub-btn ml-1 active"> Waiting for Approval </a>

                                <a href="{{ route('project.approve.invoice') }}" class="project-btn create-btn sub-btn ml-1"> Invoice List </a>
                            </div>
                            <div class="col-md-4 justify-content-end" >

                            </div>
                        </div>

                        <div class="data-table table-responsive mt-2">
                            <table class="table table-sm">
                                <thead style="background-color:#34465b !important;">
                                    <tr  class="text-center">
                                        <th style="color:#fff;"> Invoice No </th>
                                        <th style="color:#fff;"> Project </th>
                                        <th style="color:#fff;"> Customer </th>
                                        <th style="color:#fff;"> Date </th>
                                        <th style="color:#fff;"> Amount ({{$currency->symbole}})</th>
                                        <th style="color:#fff;"> Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tem_invoices as $invoice)
                                        <tr  class="text-center">
                                            @if( $invoice->tax_invoice == 'Tax Invoice')
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
                                                    <button class="project-btn btn-primary view-invoice" data-url="{{ route('project.invoice.show',$invoice->id) }}" title="View"><i class="fa fa-eye text-white"></i></button>
                                                    <a href="{{ route('project.invoice.edit',$invoice->id) }}" class="project-btn btn-dark-blue edit-project" style="margin-left: 0.2rem !important;" title="Edit"><i class="fa fa-edit text-white"></i></a>
                                                    <a href="{{ route('project.invoice.delete',$invoice->id) }}" onclick="return confirm('Please Confirm ?')" class="project-btn bg-danger" style="margin-left: 0.2rem !important;" title="Delete"><i class="fa fa-trash text-white"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $tem_invoices->links() !!}
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

        <div class="modal-body" style="padding: 5px 15px;">

        </div>
      </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).on('click','.view-invoice',function(e){
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

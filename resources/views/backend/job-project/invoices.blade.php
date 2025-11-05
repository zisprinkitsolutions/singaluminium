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
    .table .thead-light th {
        color:#F2F4F4 ;
        background-color: #34465b;
        border-color: #DFE3E7;
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
                        <div class="data-table table-responsive mt-2">
                            <table class="table table-sm">
                                <thead style="background-color:#34465b !important;">
                                    <tr  class="text-center">
                                        <th> Project </th>
                                        <th> Customer </th>
                                        <th  class="text-center"> Budget </th>
                                        <th  class="text-center"> Due </th>
                                        <th class="text-center"> Start Date </th>
                                        <th class="text-center"> End Date </th>
                                        <th  class="text-center"> Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $project)
                                        <tr  class="text-center">
                                            <td> {{ $project->project_name }} </td>
                                            <td> {{ $project->party->pi_name }} </td>
                                            <td  class="text-center"> {{ $project->total_budget }}</td>
                                            <td  class="text-center"> {{ $project->due_amount}}</td>
                                            <td class="text-center"> {{ date('d/m/Y',strtotime($project->start_date)) }}</td>
                                            <td class="text-center"> {{ date('d/m/Y',strtotime($project->end_date)) }}</td>
                                            <td  class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <button class="project-btn btn-sky view-project" data-id="{{ $project->id }}" data-url="{{ route('projects.show',$project->id) }}" data-invoice="{{ $project->is_invoice }}"> View </button>
                                                    @if ($project->is_invoice == 0)
                                                    <a href="{{ route('projects.edit',$project->id) }}" class="ml-1 project-btn btn-dark-blue edit-project"> Edit </a>
                                                    @endif
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
        <div class="modal-header" style="padding: 5px 15px;">
          <h5 class="modal-title" id="exampleModalLabel"> View Project </h5>
          <div class="d-flex align-items-center">
            <button type="button" class="print-page project-btn bg-dark" style="margin:0 5px;">
                <span aria-hidden="true">  <i class="bx bx-printer text-white"></i> </span>
            </button>
            <a href="" class="project-btn bg-info invoice-create" style="margin:0 5px;">
                Genarate Invoice
            </a>
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
$(document).on('click','.view-project',function(e){
    e.preventDefault();
    let project_id = $(this).attr('data-id');
    let url = $(this).attr('data-url');

    let invoice = $(this).attr('data-invoice');

    if(invoice == 0){
        let invoice_create_url = "{{ route('project.invoice.create',":id") }}"
        invoice_create_url = invoice_create_url.replace(':id', project_id);
        $('.invoice-create').attr('href',invoice_create_url)
    }else{
        $('.invoice-create').hide();
    }


    $.get(url,function(res){
        $('.modal-body').html(res);
        $('.modal-title').html('View Project');
        $('#project-modal').modal('show');
    })
});

$(document).on('click','.print-page',function(){
    window.print();
})
</script>

@endpush

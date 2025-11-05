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
    .sub-btn{
        border:1px solid #475F7B !important;
        background-color: #fff !important;
        /* border-radius: 15px !important; */
        border-radius: 8px !important;
        color: #475F7B !important;
        padding: 3px 6px 3px 6px !important;
    },
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
    .form-control,
    .project-btn{
        height: 30px;
    }
    .date_type:focus,
    .date_type:active{
        border: border 1px solid #313131;
    }
    .table .thead-light th {
        color:#F2F4F4 ;
        background-color: #34465b;
        border-color: #DFE3E7;
    }
    tr:nth-child(even) {
        background-color: #c8d6e357;
    }
    tr td{
        padding: 5px 0 !important;
    }
    tr th{
        padding: 10px 0 !important;
    }
    .data-table .table tr th {
        color: white;
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
                            <div class="col-7 d-flex justify-content-between">
                                <div class="d-flex">
                                    @if(Auth::user()->hasPermission('ProjectManagement_Create'))
                                    <a href="{{ route('lpo-projects.create') }}" class="project-btn sub-btn">&nbsp;&nbsp;&nbsp; Create &nbsp;&nbsp;&nbsp;</a>
                                    @endif
                                    <form action="{{ route('lpo-projects.index') }}" method="get" class="ml-1">
                                        <input type="hidden" value="new" name="quotation">

                                        <button type="submit" class="project-btn sub-btn bg-dark {{ $active_btn == 'new' ? 'active' : '' }}">&nbsp;&nbsp; View All &nbsp;&nbsp;</button>
                                    </form>

                                    {{-- <form action="{{ route('lpo-projects.index') }}" method="get" class="ml-1">
                                        <input type="hidden" value="old" name="quotation">
                                        <button type="submit" class="project-btn sub-btn bg-dark  {{ $active_btn == 'old' ? 'active' : '' }}"> Quatation <small>(WO)</small>   </button>
                                    </form> --}}
                                </div>

                            </div>
                            <div class="col-5 d-flex justify-content-end" >
                                <form action="{{ route('lpo-projects.index') }}" method="get">
                                    <div class="form-group d-flex">
                                        <!-- <button type="submit" class="project-btn action-btn bg-info"> <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}" width="25"> Search   </button> -->
                                            {{-- <select name="company_id" class="form-control inputFieldHeight common-select2">
                                                <option value="">Select...</option>
                                                <option value="seabridge" {{$company_id=='seabridge' ? 'selected' : '' }}>SINGH ALUMINIUM AND STEEL</option>
                                                @foreach ($subsidiarys as $subsidiary)
                                                <option value="{{ $subsidiary->id }}" {{$company_id==$subsidiary->id ? 'selected' : '' }}>{{
                                                    $subsidiary->company_name }}
                                                </option>
                                                @endforeach
                                            </select> --}}
                                            <input type="text" value="{{ $search}}" name="search" class="form-control search w-100 " style="margin-left: 10px;" placeholder="Search Project">
                                            <button type="submit" class="project-btn action-btn bg-info ml-1" title="Search" style="background: #9ba19c;color: white;">
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
                        <table class="table table-sm table-hover table-bordered align-middle">
                            <thead class="text-center" style="background-color:#34465b !important; ">
                                <tr>
                                    <th class="text-left">SI NO</th>
                                    <th class="text-left">Owner / Party</th>
                                    <th>Quote No.</th>
                                    <th class="text-left">Total <br> {{ number_format($data['total_budget'], 2) }}</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th style="width: 100px;">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($projects as $key => $project)
                                <tr class="text-center text-uppercase">
                                    <!-- Serial Number -->
                                    <td class="text-left">
                                        {{ ($projects->currentPage() - 1) * $projects->perPage() + $key + 1 }}
                                    </td>
                                    <!-- Owner / Party -->
                                    <td class="text-left view-project" data-id="{{ $project->id }}"
                                        data-url="{{ route('lpo-projects.show',$project->id) }}"
                                        title="{{ optional(optional($project->boq)->party)->pi_name }}">
                                        {{ Str::limit(optional(optional($project->boq)->party)->pi_name, 30) }}
                                    </td>


                                    <!-- Quote No -->
                                    <td class="view-project" style="min-width:110px;" data-id="{{ $project->id }}"
                                        data-url="{{ route('lpo-projects.show',$project->id) }}">
                                        {{ $project->project_code }}
                                    </td>

                                    <!-- Total -->
                                    <td class="text-left view-project" data-id="{{ $project->id }}"
                                        data-url="{{ route('lpo-projects.show',$project->id) }}"
                                        title="{{ optional($project->party)->pi_name }}">
                                        {{ number_format($project->total_budget, 2) }}
                                    </td>

                                    <!-- Start Date -->
                                    <td class="view-project">
                                        {{ $project->start_date ? date('d/m/Y', strtotime($project->start_date)) : '...' }}
                                    </td>

                                    <!-- End Date -->
                                    <td class="view-project">
                                        {{ $project->end_date ? date('d/m/Y', strtotime($project->end_date)) : '...' }}
                                    </td>

                                    <!-- Status -->
                                    <td class="{{ $project->has_work_order ? 'text-success' : 'text-danger' }}">
                                        {{ $project->has_work_order ? 'Successful' : 'In-Review' }}
                                    </td>

                                    <!-- Action -->
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-sm btn-primary view-project" data-id="{{ $project->id }}"
                                                data-url="{{ route('lpo-projects.show',$project->id) }}" title="View">
                                                <i class="fa fa-eye"></i>
                                            </button>

                                            @if(Auth::user()->hasPermission('ProjectManagement_Edit'))
                                            <a href="{{ route('lpo-projects.edit',$project->id) }}" class="btn btn-sm btn-info" style="margin-left: 5px;"
                                                title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                {{-- <tr style=" background-color: #3d4a94 !important; color:white;">
                                    <td colspan="8" style="text-align: right ; margin-right:5px;">Total &nbsp;</td>
                                    <td>{{ number_format($data['total_budget'], 2) }}</td>
                                    <td colspan="4"></td>
                                </tr> --}}
                            </tbody>
                        </table>
                            {!! $projects->links() !!}
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
        <div class="modal-header print-hideen" style="padding: 5px 15px;background:#364a60;">
          <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:#fff;padding-left: 12px;"> Quotation </h5>
          <div class="d-flex align-items-center">
                {{-- <a href="" class="project-btn bg-success print-lpo" target="_blank"  title="Print" style="margin-right: 0.2rem !important;">
                    <i class="bx bx-printer text-white" style="padding-top:4px;"></i>
                </a> --}}

                {{-- <button type="button" class="print-page project-btn bg-success" title="Print" style="margin-right: 0.2rem !important;">
                    <span aria-hidden="true">  <i class="bx bx-printer text-white" style="padding-top:4px;"></i> </span>
                </button> --}}
                {{-- @if(Auth::user()->hasPermission('ProjectManagement_Authorize'))
                <a href="" class="project-btn bg-info work-station-create" title="Genarate Work Order" style="margin-right: 0.2rem !important;">
                   <img src="{{asset('icon/generate.png')}}" class="img-fluid" style="height: 25px" alt="">
                </a>
                @endif --}}
                <button type="button" class="project-btn bg-danger text-white" data-dismiss="modal" aria-label="Close">
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
    let work_station_url = "{{ route('work.station.create',":id") }}";
    let lpo_print_url = "{{ route('lpo-print',":id") }}";
    let onboard = $(this).closest('tr').find('.onboard').text();
    if(onboard == 'Successful'){
        $('.work-station-create').hide()
    }else{
        $('.work-station-create').show();
    }
    work_station_url = work_station_url.replace(':id',project_id);
    lpo_print_url = lpo_print_url.replace(':id',project_id);

    $.get(url,function(res){
        $('.modal-body').html(res);
        $('.modal-title').html('Quotation');
        $('#project-modal').modal('show');
        $('.work-station-create').attr('href',work_station_url);
        $('.print-lpo').attr('href',lpo_print_url);
    })
});

$(document).on('click','.print-page',function(){
    window.print();
})
</script>

@endpush

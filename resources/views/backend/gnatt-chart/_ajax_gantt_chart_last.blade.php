<style>
    .nav.nav-tabs1 {
        border-bottom: none !important;
    }

    .nav-tabs1 .nav-link1.active {
        background: #364a60 !important;
    }

    .nav-tabs1 .nav-link1,
    .nav-pills1 .nav-link1 {
        background-color: #23eded00 !important;
        color: #4f4f50;
        font-weight: 900;
        font-size: 16px;
        border: 1px solid #dfe3e7;
        border-radius: 5px;
        margin: 0px 6px;
    }

    .nav-tabs1 .nav-link1:hover {
        background-color: #364a60 !important;
        color: #ffffff;
        font-weight: 900;
        font-size: 16px;
        border: 1px solid #dfe3e7;
        border-radius: 5px;
        margin: 0px 6px;
    }

    .project-btn {

        padding: 11px 22px !important;

    }
</style>

<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse">

        <div class="pr-1" style="padding-top: 8px; !important;">
            <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"
                style="height:35px; width:35px; display:flex; align-items:center; justify-content:center; padding:0; font-size:18px;">
                <span aria-hidden="true"><i class='bx bx-x'></i></span>
            </a>
        </div>

        <div class="pr-1" style="padding: 8px;padding-right: 0.2rem !important;">
            <a onclick="window.print()" class="btn btn-icon btn-primary"
                style="height:35px; width:35px; display:flex; align-items:center; justify-content:center; padding:0; font-size:18px;">
                <i class="bx bx-printer"></i>
            </a>
        </div>

        {{-- <div class="pr-1" style="padding-top: 8px;padding-right: 0.2rem !important;">
            <a href="{{route('new-project.gantt-chart.pdf',$project_info->id)}}" class="btn btn-sm btn-icon btn-success"
                style="height:25px; width:25px; display:flex; align-items:center; justify-content:center; padding:0; font-size:18px;">
                <i class='bx bxs-file-pdf'></i>
            </a>
        </div> --}}

        {{-- @if ($project_info->boqs->count() < 0) <div class="pr-1"
            style="padding-top: 9px;padding-right: 0.2rem !important;">
            <form action="{{route('requisitions.destroy', $project_info->id)}}" method="POST">
                @csrf
                @method('delete')
                <button type="submit" onclick="return(confirm('Are you want to delete this?'))"
                    class="btn btn-sm btn-icon btn-danger"
                    style="height:25px; width:25px; display:flex; align-items:center; justify-content:center; padding:0; font-size:18px;">
                    <i class="bx bx-trash"></i>
                </button>
            </form>
    </div>
    @endif --}}

    <div class="pr-1 w-100 pl-2" style="margin-top: 2px;">
        <h4 style="font-family:Cambria;font-size: 2rem;color:white;"> Gantt Chart (Project: {{$project->project_name}} , Project No: {{$project->prospect->project_no}} , Plot: {{$project->prospect->plot}} , Location: {{$project->prospect->location}})  </h4>
    </div>
    </div>
</section>

    <div class="modal-body" style="padding: 5px 5px;">
        @include('layouts.backend.partial.modal-header-info')

        <nav>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="nav nav-tabs nav-tabs1" id="nav-tab" role="tablist">
                @if(!$chart_have)
                <button class="nav-link nav-link1 active" id="nav-create-tab"data-project="{{$project->id}}" data-toggle="tab"
                    data-target="#nav-create" type="button" role="tab" aria-controls="nav-create" aria-selected="true">
                    🛠️ Build Gantt Chart
                </button>
                @endif

                <button class="nav-link nav-link1 " id="nav-tracking-tab" data-project="{{$project->id}}" data-toggle="tab"
                    data-target="#nav-tracking" type="button" role="tab" aria-controls="nav-tracking" aria-selected="false"
                    @if(!$chart_have) disabled @endif>
                    📊 Tracking
                </button>

                <button class="nav-link nav-link1 " id="nav-edit-tab" data-project="{{$project->id}}" data-toggle="tab" data-target="#nav-edit"
                    type="button" role="tab" aria-controls="nav-edit" aria-selected="false" @if(!$chart_have) disabled @endif>
                    ✏️ Edit
                </button>

                <button class="nav-link nav-link1 " id="nav-view-tab" data-project="{{$project->id}}" data-toggle="tab" data-target="#nav-view"
                    type="button" role="tab" aria-controls="nav-view" aria-selected="false" @if(!$chart_have) disabled @endif>
                    👁️ View
                </button>


                {{-- Approve --}}
                    <button type="button" class="nav-link1   gantt-chart-button" data-action="approve"
                        data-url="{{ route('gnatt.chart.approve', $project->id ?? 0) }}">
                        ✅ Approve
                    </button>

                    <button type="button" class="nav-link1 gantt-chart-button-delete gantt-chart-button" data-action="delete"
                        data-url="{{ route('gnatt.chart.destroy', $project->id ?? 0) }}">
                        🗑️ Delete
                    </button>
            </div>

        </div>

    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-create" role="tabpanel" aria-labelledby="nav-create-tab">
            {{-- Gantt Chart (create) --}}
            <section class="p-1" id="widgets-Statistics">
                {{-- <div class="row">
                    <div class="col-7 d-flex justify-content-between">
                        <div class="d-flex">
                            <a href="{{ route('gnatt.chart.index') }}" class="project-btn sub-btn active"> Gantt Chart
                            </a>
                        </div>
                    </div>
                    <div class="col-5 d-flex justify-content-end">
                    </div>
                </div> --}}
                <form class="repeater mt-1 project-form chart-form" action="{{ route('gnatt.chart.store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <input type="hidden" value="{{$project->lpo_projects_id}}" name="quotation_id">
                        <input type="hidden" value="{{$project->id}}" name="project_id">
                        <input type="hidden" value="{{$project->customer_id}}" name="customer_id">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for=""> Gantt Chart Name </label>
                                <input type="text" class="form-control" name="name" autocomplete="off" id="name"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for=""> Estimated Start Date </label>
                                <input type="text" name="start_date1" id="start_date"
                                    class="date form-control @error('start_date1') is-invalid @enderror" value=""
                                    autocomplete="off" required>
                                @error('start_date1')
                                <p class="text-danger"> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for=""> Estimated End Date </label>
                                <input type="text" name="end_date1" id="end_date"
                                    class="date form-control @error('end_date1') is-invalid @enderror"
                                    autocomplete="off" required>
                                @error('end_date1')
                                <p class="text-danger"> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        {{-- <table class="auto-index repeater1 table table-sm"> --}}
                            <table class=" repeater1 table table-sm">
                                <thead style="background-color:#34465b !important;">
                                    <tr>
                                        <th class="text-left text-white" style="min-width:200px; padding:5px 7px;">Task
                                            name</th>
                                        <th class="text-left text-white" style="width:200px; padding:5px 7px;">Assign To
                                        </th>
                                        <th class="text-center text-white" style="min-width:100px; max-width:120px;">
                                            Start Date
                                        </th>
                                        <th class="text-center text-white" style="min-width:100px; max-width:120px">End
                                            Date</th>
                                        <th class="text-center text-white" style="min-width:80px;  max-width:120px">
                                            Color </th>
                                        <th class="text-center text-white" style="min-width:100px; max-width:120px">
                                            Priority</th>
                                        <th class="text-right text-white" style="min-width:60px;">
                                            <button type="button" id="addTask" class="addItemBtn bg-info text-white"
                                                title="Add Task" style="border: 1px solid #ddd;">+</button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="boq-body">

                                </tbody>
                            </table>
                    </div>

                    <div class="row d-flex">
                        <div class="col-7">
                            <div class="form-group" style="width: 300px;margin-top: 10px;">
                                <label for=""> Upload Documents </label>
                                <input class="form-control file_upload  @error('voucher_file') is-invalid @enderror"
                                    type="file" name="voucher_file[]" style="padding: 0px !important; border:none"
                                    accept="application/pdf,image/png,image/jpeg,application/msword" multiple>
                                @error('voucher_file')
                                <p class="text-danger"> {{ $message }}</p>
                                @enderror

                                <ul id="fileList" class="list-group mt-1"></ul>
                            </div>
                        </div>

                        <div class="col-5">
                            @if (Auth::user()->hasPermission('ProjectManagement_Approve'))
                                <div class="d-flex justify-content-end mt-1">
                                    <button type="submit" class="btn btn-primary btn-lg">Save</button>
                                </div>
                            @endif
                        </div>
                    </div>
                </form>
            </section>
            {{-- --}}
        </div>
        <div class="tab-pane fade" id="nav-tracking" role="tabpanel" aria-labelledby="nav-tracking-tab">...</div>
        <div class="tab-pane fade p-2" id="nav-edit" role="tabpanel" aria-labelledby="nav-edit-tab"></div>
        <div class="tab-pane fade" id="nav-view" role="tabpanel" aria-labelledby="nav-view-tab">
            <div id="gantt" class="mt-1"></div>
        </div>
    </div>


    @include('layouts.backend.partial.modal-footer-info')
</div>

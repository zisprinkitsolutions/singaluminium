<style>
    .document-wrapper {
        position: relative;
        width: 140px;
        margin: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .document-wrapper img {
        object-fit: cover;
        height: 100px;
        width: 100%;
    }

    .delete-document {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(255, 0, 0, 0.8);
        border: none;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        cursor: pointer;
        font-weight: bold;
        line-height: 20px;
        text-align: center;
    }

    .documents-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
</style>

<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse align-items-center">

        <div class="pr-1" >
            <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"
               style="height:30px; width:30px; display:flex; align-items:center; justify-content:center; padding:0; font-size:18px;">
                <span aria-hidden="true"><i class='bx bx-x'></i></span>
            </a>
        </div>

        {{-- <div class="pr-1" style="padding: 8px;padding-right: 0.2rem !important;">
            <a onclick="window.print()" class="btn btn-icon btn-primary"
               style="height:25px; width:25px; display:flex; align-items:center; justify-content:center; padding:0; font-size:18px;">
                <i class="bx bx-printer"></i>
            </a>
        </div> --}}

        {{-- <div class="pr-1" style="padding-top: 8px;padding-right: 0.2rem !important;">
            <a href="{{route('new-project.gantt-chart.pdf',$project_info->id)}}" class="btn btn-sm btn-icon btn-success"
               style="height:25px; width:25px; display:flex; align-items:center; justify-content:center; padding:0; font-size:18px;">
                <i class='bx bxs-file-pdf'></i>
            </a>
        </div> --}}

        {{-- @if($project_info->boqs->count() < 0)
        <div class="pr-1" style="padding-top: 9px;padding-right: 0.2rem !important;">
            <form action="{{route('requisitions.destroy', $project_info->id)}}" method="POST">
                @csrf
                @method('delete')
                <button type="submit" onclick="return(confirm('Are you want to delete this?'))" class="btn btn-sm btn-icon btn-danger"
                        style="height:25px; width:25px; display:flex; align-items:center; justify-content:center; padding:0; font-size:18px;">
                    <i class="bx bx-trash"></i>
                </button>
            </form>
        </div>
        @endif --}}

        <div class="pr-1 w-100 pl-2" >
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;"> Project : {{$project_info->name }} , Project No : {{$project_info->project_no}}</h4>
        </div>
    </div>
</section>

<div class="modal-body" style="padding: 5px 5px;">
    @include('layouts.backend.partial.modal-header-info')
    <section id="widgets-Statistics" class="mr-1 ml-1 mb-1">

        <div class="row">
            <div class="col-12 profit-center-form">
                <div class="row">
                    <div class="col-md-6">
                        <h6><strong>🏗️ Project Name:</strong> {{$project_info->name }}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong>📘 Project No:</strong> {{$project_info->project_no}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong>🔄 Status:</strong> {{$project_info->status}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong>🏗️ Type:</strong> {{$project_info->project_type}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong>🔢 Code:</strong> {{$project_info->project_code}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong>👤 Owner:</strong> {{$project_info->party->pi_name??''}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                            <h6><strong>👤 Compnay Name :</strong> {{$project_info->company->company_name??'SINGH ALUMINIUM AND STEEL'}}</h6>
                    </div>

                    <div class="col-md-3 search-item-pi">
                        <h6><strong>📞 Mobile:</strong> {{$project_info->mobile_no}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong>📍 Location:</strong> {{$project_info->location}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong>📋 Consultant:</strong> {{$project_info->consultant}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong>👷 Engineer:</strong> {{$project_info->engineer}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong>🔤 Short Name:</strong> {{$project_info->short_name}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong>💰 Contract Value:</strong> {{number_format($contract_value=$project_info->work_order? $project_info->work_order->budget:0,2)}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong>💸 VAT:</strong> {{number_format($vat=($contract_value*(5/100)),2)}}</h6>
                    </div>

                    <div class="col-md-3 search-item-pi">
                        <h6><strong>📦 Total Contract:</strong> {{number_format($contract_value+$vat,2)}}</h6>
                    </div>


                    <div class="col-md-3 search-item-pi">
                        <h6><strong>🛡️ Insurance:</strong> {{$project_info->insurance}}</h6>
                    </div>

                    <div class="col-md-3 search-item-pi">
                        <h6><strong>🕓 Period:</strong> {{$project_info->contract_period}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong>🌐 Area:</strong> {{$project_info->area}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong>📁 File No:</strong> {{$project_info->file_no}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong>🕐 Start:</strong> {{$project_info->start_date?date('d/m/Y',
                            strtotime($project_info->start_date)):''}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong>📅 Deadline:</strong> {{$project_info->end_date?date('d/m/Y',
                            strtotime($project_info->end_date)):''}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong>🚚 Handover:</strong> {{$project_info->handover_on?date('d/m/Y',
                            strtotime($project_info->handover_on)):''}}</h6>
                    </div>
                    <div class="col-md-3">
                        <h6><strong>📝 Details:</strong> {{$project_info->details}}</h6>
                    </div>
                </div>
            </div>

            @if ($gantt_chart)
            <div class="col-md-12 my-1">
                <div class="tab-content bg-white">
                    <h6> Project Gantt Chart </h6>
                    <div id="gantt"></div>
                </div>
            </div>
            @endif

            @if ($documents->count() > 0)
            <div class="col-md-12 my-1">
                <div class="tab-content bg-white">
                    <h6> Project Documents</h6>
                    <div class="documents-container">
                        @foreach ($documents as $item)
                            <div class="document-wrapper" id="doc-{{$item->id}}">
                                <button class="delete-document print-hideen" id="{{$item->id}}">&times;</button>

                                <a href="{{ asset('storage/upload/project-document/' . $item->file_name) }}" target="_blank">
                                    @if ($item->ext == 'pdf')
                                        <img src="{{ asset('icon/pdf-download-icon-2.png') }}" alt="{{$item->ext}}">
                                    @else
                                        <img src="{{ asset('storage/upload/project-document/' . $item->file_name) }}" alt="{{$item->ext}}">
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    <section class="print-hideen border-bottom">
        <div class="d-flex flex-row-reverse justify-content-center">
            <div class="pr-1" style="padding: 8px;padding-right: 0.2rem !important;">
                <a onclick="window.print()" class="btn btn-icon btn-secondary custom-action-btn" title="Print Now">
                    <i class="bx bx-printer"></i> Print
                </a>
            </div>
        </div>
    </section>
    @include('layouts.backend.partial.modal-footer-info')
</div>

<script>
        // Convert PHP tasks array to JS
        var tasks = [];

        @if(isset($gantt_chart) && $gantt_chart->items)
            tasks = @json($gantt_chart->items)
        @endif

        // Format date to Gantt format
        function formatDateToGantt(dateStr) {
            return "/Date(" + new Date(dateStr).getTime() + ")/";
        }

        // Map Laravel tasks into Gantt chart format
        var ganttSource = tasks.map(task => {
            return {
                name: task.name,
                desc: `
                    <strong>Priority:</strong> ${task.priority || 'N/A'}<br>
                    <strong>Start:</strong> ${task.start_date}<br>
                    <strong>End:</strong> ${task.end_date}
                `,
                values: [{
                    from: formatDateToGantt(task.start_date),
                    to: formatDateToGantt(task.end_date),
                    label: `${task.name} (${task.progress || 0}%)`,
                    customClass: task.color || 'ganttDefault',
                    progress: task.progress || 0
                }]
            };
        });

        $(function () {
            $("#gantt").gantt({
                source: ganttSource,
                navigate: "scroll",
                scale: "days",
                maxScale: "months",
                minScale: "hours",
                itemsPerPage: 10,
                onItemClick: function (data) {
                    alert("Item clicked: " + data.name);
                },
                onAddClick: function (dt, rowId) {
                    alert("Empty space clicked - add an item!");
                },
                onRender: function () {
                    $('#gantt .bar').each(function (index) {
                    const task = tasks[index];
                    const baseColor = task.color || '#007bff';
                    const progress = parseInt(task.progress) || 0;

                    // Choose a strong color for progress portion
                    let progressColor = '#28a745';

                    // Apply background gradient without removing the label
                    $(this).css({
                        'background': `linear-gradient(to right, ${progressColor} ${progress}%, ${baseColor} ${progress}%)`,
                        'border-color': baseColor,
                        'color': '#fff'
                    });
                });

                }
            });
        });

    </script>



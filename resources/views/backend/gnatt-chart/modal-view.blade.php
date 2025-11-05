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
    <div class="d-flex flex-row-reverse">

        <div class="pr-1" style="padding-top: 8px; !important;">
            <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"
               style="height:25px; width:25px; display:flex; align-items:center; justify-content:center; padding:0; font-size:18px;">
                <span aria-hidden="true"><i class='bx bx-x'></i></span>
            </a>
        </div>

        <div class="pr-1" style="padding: 8px;padding-right: 0.2rem !important;">
            <a onclick="window.print()" class="btn btn-icon btn-primary"
               style="height:25px; width:25px; display:flex; align-items:center; justify-content:center; padding:0; font-size:18px;">
                <i class="bx bx-printer"></i>
            </a>
        </div>

        {{-- <div class="pr-1" style="padding-top: 8px;padding-right: 0.2rem !important;">
            <a href="{{route('new-project.gantt-chart.pdf',optional($chart->project)->id)}}" class="btn btn-sm btn-icon btn-success"
               style="height:25px; width:25px; display:flex; align-items:center; justify-content:center; padding:0; font-size:18px;">
                <i class='bx bxs-file-pdf'></i>
            </a>
        </div> --}}

        {{-- @if(optional($chart->project)->boqs->count() < 0)
        <div class="pr-1" style="padding-top: 9px;padding-right: 0.2rem !important;">
            <form action="{{route('requisitions.destroy', optional($chart->project)->id)}}" method="POST">
                @csrf
                @method('delete')
                <button type="submit" onclick="return(confirm('Are you want to delete this?'))" class="btn btn-sm btn-icon btn-danger"
                        style="height:25px; width:25px; display:flex; align-items:center; justify-content:center; padding:0; font-size:18px;">
                    <i class="bx bx-trash"></i>
                </button>
            </form>
        </div>
        @endif --}}

        <div class="pr-1 w-100 pl-2" style="margin-top: 2px;">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;"> Gantt Chart </h4>
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
                      <h6><strong> Name: </strong> {{$chart->name}}</h6>
                    </div>

                    @if (optional($chart->project)->project_no)
                    <div class="col-md-3 search-item-pi">
                        <h6><strong> Project No: </strong> {{optional($chart->project)->project_no}}</h6>
                    </div>
                    @endif

                    @if (optional($chart->project)->status)
                    <div class="col-md-3 search-item-pi">
                        <h6><strong> Project Status: </strong> {{optional($chart->project)->status}}</h6>
                    </div>
                    @endif


                    <div class="col-md-3 search-item-pi">
                        <h6><strong> Project Type: </strong> {{optional($chart->project)->project_type}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong> Project Code: </strong> {{optional($chart->project)->project_code}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong> Owner Name: </strong> {{optional($chart->project)->party->pi_name??''}}</h6>
                    </div>
                    <div class="col-md-3 search-item-pi">
                        <h6><strong> Mobile No: </strong> {{optional($chart->project)->mobile_no}}</h6>
                    </div>
                        <div class="col-md-3 search-item-pi">
                        <h6><strong> Site Location: </strong> {{optional($chart->project)->location}}</h6>
                    </div>
                        <div class="col-md-3 search-item-pi">
                        <h6><strong> Consulting Agent: </strong> {{optional($chart->project)->consulting_agent }}</h6>
                    </div>

                    <div class="col-md-3 search-item-pi">
                        <h6><strong> Project Amount: </strong> {{optional($chart->project)->total_amount}}</h6>
                    </div>

                    <div class="col-md-3 search-item-pi">
                        <h6><strong> Work Order Date: </strong> {{optional($chart->project)->start_date?date('d/m/Y', strtotime(optional($chart->project)->start_date)):''}}</h6>
                    </div>

                    <div class="col-md-3 search-item-pi">
                        <h6><strong> Handover Date: </strong> {{optional($chart->project)->end_date?date('d/m/Y', strtotime(optional($chart->project)->end_date)):''}}</h6>
                    </div>

                    <div class="col-md-3 search-item-pi">
                        <h6><strong> Handover ON: </strong> {{optional($chart->project)->handover_on?date('d/m/Y', strtotime(optional($chart->project)->handover_on)):''}}</h6>
                    </div>

                    <div class="col-md-3">
                          <h6><strong> Project Details: </strong> {{optional($chart->project)->details}}</h6>
                    </div>
                    @endif
                </div>
            </div>

            @if ($chart)
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
                    <strong>Assigned:</strong> ${task.assign_by || 'N/A'}<br>
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



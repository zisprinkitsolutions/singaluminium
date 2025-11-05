

<style>
    html, body {
        height:100%;
    }
    thead {
    background: #34465b;
    color: #fff !important;
    height: 30px;
}

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
@media print{
        .table tr th,
        .table tr td{
            color: #000000 !important;
            font-weight:500 !important;
        }
    }
</style>

@php
    $company_name = App\Setting::where('config_name', 'company_name')->first();
    $company_name_arabic = App\Setting::where('config_name', 'company_name_arabic')->first();
    $company_email = App\Setting::where('config_name', 'company_email')->first();
    $company_tele = App\Setting::where('config_name', 'company_tele')->first();
    $company_fax = App\Setting::where('config_name', 'company_fax')->first();
    $company_mobile = App\Setting::where('config_name', 'company_mobile')->first();
@endphp
<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse">

        <div class="pr-1" style="padding-top: 8px;padding-right: 22px !important;"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>

        <div class="pr-1" style="padding: 8px;padding-right: 0.2rem !important;"><a href="#" onclick="window.print();" class="btn btn-icon btn-primary"><i class="bx bx-printer"></i></a></div>

        <div class="pr-1 w-100 pl-2" style="margin-top: 2px;">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;"> Project Report </h4>
        </div>
    </div>
</section>
<div class="receipt-voucher-hearder invoice-view-wrapper" style="margin: 50px 20px; border-radius: 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>

<div class="p-2">
    <div class="d-flex justify-content-between">
        <div>
            <h6> TO, </h6>
            <p><strong style="font-size: 14px;">Company Name:</strong> {{optional($chart->party)->pi_name}}</p>

            @if (optional($chart->party)->pi_name_arabic)
                <p><strong style="font-size: 14px;">Company Name Arabic:</strong> {{optional($chart->party)->pi_name_arabic}}</p>
            @endif

            @if(optional($chart->party)->address)
            <p><strong style="font-size: 14px;">Address:</strong> {{optional($chart->party)->address}}</p>
            @endif

            @if(optional($chart->party)->phone_no)
            <p><strong style="font-size: 14px;">Phone No:</strong> {{optional($chart->party)->phone_no}}</p>
            @endif
            @if(optional($chart->party)->trn_no)
            <p><strong style="font-size: 14px;">TRN:</strong> {{optional($chart->party)->trn_no}}</p>
            @endif
        </div>

        <div class="text-right">
            <h6> From, </h6>
            <p><strong style="font-size: 14px;">Company Name:</strong> {{$company_name->config_value}}</p>
            <p><strong style="font-size: 14px;">Email:</strong> {{$company_email->config_value}}</p>
            <p><strong style="font-size: 14px;">Phone:</strong> {{$company_mobile->config_value}}</p>
        </div>
    </div>

    <div class="">
        @php
            use Carbon\Carbon;

            $end = Carbon::parse($chart->end_date);
            $now = Carbon::today();
            $remain = $now->diffInDays($end, false);
        @endphp

        <h6><strong style="font-size: 14px;"> Project:</strong> {{optional($chart->quotation)->project_name}}</h6>
        <h6><strong style="font-size: 14px;"> Start Date:</strong>{{date('d/m/Y',strtotime($chart->start_date))}}</h6>
        <h6><strong style="font-size: 14px;"> End Date:</strong>{{date('d/m/Y',strtotime($chart->end_date))}}</h6>
        <h6><strong style="font-size: 14px;"> Progress:</strong> {{number_format($chart->items->sum('progress') / $chart->items->count(),2)}} %</h6>

        @if($remain > 0)
        <p><strong style="font-size: 14px;">Remaing Days:</strong> {{$remain > 0 ? $remain : 0}}</p>
        @endif

    </div>

    <div class="my-1">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead style="background-color:#34465b !important;">
                    <tr>
                        <th style="color: #fff; min-width:5%;"> SN </th>
                        <th style="color: #fff; min-width: 180px;"> Task </th>

                        <th class="text-left" style="color: #fff; min-width: 130px;"> Assign To </th>
                        <th class="text-center" style="color: #fff; min-width: 100px; max-width: 120px;"> Start Date </th>
                        <th class="text-center" style="color: #fff; min-width: 100px; max-width: 120px;"> End Date </th>
                        <th class="text-center" style="color: #fff; min-width: 100px; max-width: 100px;"> Progress % </th>
                        <th class="text-center" style="color: #fff; min-width: 120px; max-width: 100px;"> Remaining Days </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($chart->items as $key =>  $item)
                    <tr>
                        @php
                            $end = Carbon::parse($item->end_date);
                            $now = Carbon::today();
                            $remaining = $now->diffInDays($end, false);
                        @endphp

                        <td> {{ $key + 1}} </td>
                        <td> {{ $item->name}} </td>
                        <td class="text-left"> {{ $item->assign_by}} </td>
                        <td class="text-center"> {{ $item->start_date ? date('d/m/Y', strtotime($item->start_date)) : ''}} </td>
                        <td class="text-center">{{$item->end_date ? date('d/m/Y',strtotime($item->end_date)) : ''}}</td>
                        <td class="text-center"> {{$item->progress}} </td>
                        <td class="text-center"> {{ $remaining > 0 ?  $remaining : 0}} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($chart)
        <div class="col-md-12 my-1">
            <div class="tab-content bg-white">
                <h6>Gantt Chart </h6>
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
</div>

<section id="widgets-Statistics">
    <div class="divFooter mb-1 ml-1 footer-margin invoice-view-wrapper">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png')}}" alt="" width="150"></span>
    </div>
</section>

<div class="img receipt-bg invoice-view-wrapper footer-margin">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid" style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;" alt="">

</div>

<script>
        // Convert PHP tasks array to JS
        var tasks = [];

        @if(isset($chart) && $chart->items)
            tasks = @json($chart->items)
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
                    <strong style="font-size: 14px;">Priority:</strong> ${task.priority || 'N/A'}<br>
                    <strong style="font-size: 14px;">Start:</strong> ${task.start_date}<br>
                    <strong style="font-size: 14px;">End:</strong> ${task.end_date}
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


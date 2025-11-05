@extends('layouts.backend.app')

@push('css')
    @include('layouts.backend.partial.style')

    <!-- jQuery Gantt CSS -->
    <link rel="stylesheet" href="{{ asset('css/jquery-gantt.css') }}" />

    <style>
        /* Custom style fallback (not effective alone due to inline styles) */
        #gantt .nav-slider-left,
        #gantt .nav-slider-right{
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #gantt .navigate .nav-link {
            color: #fff !important;
            background-color: #313131 !important;
            border: none !important;
            box-shadow: none !important;
            font-family: Arial, sans-serif !important;
            font-size: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 6px 14px;
            border-radius: 5px;
        }

        #gantt .fn-gantt .leftPanel {
            width: 320px !important; /* Adjust as needed */
        }

        /* Also increase the width of the description cell */
        #gantt .fn-gantt .leftPanel .fn-label {
            white-space: normal !important;
            word-break: break-word;
            width: 100% !important;
            padding-right: 10px;
        }

        #gantt .fn-gantt .bar .fn-label{
            color: #fff !important;
        }

        #gantt .fn-gantt .bottom{
            margin: 20px;
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
                    <div id="gantt"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- jQuery & Gantt plugin -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/plugin/jquery-gantt.js') }}"></script>

    <script>
        // Convert PHP tasks array to JS
        var tasks = @json($chart->items);

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
@endpush

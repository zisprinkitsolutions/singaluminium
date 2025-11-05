@extends('layouts.backend.app')
@push('css')
<link rel="stylesheet" href="{{ asset('css/jquery-gantt.css') }}" />
@include('layouts.backend.partial.style')
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.css" />

<style>
    #gantt .nav-slider-left,
    #gantt .nav-slider-right {
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
        width: 320px !important;
        /* Adjust as needed */
    }

    /* Also increase the width of the description cell */
    #gantt .fn-gantt .leftPanel .fn-label {
        white-space: normal !important;
        word-break: break-word;
        width: 100% !important;
        padding-right: 10px;
    }

    #gantt .fn-gantt .bar .fn-label {
        color: #fff !important;
    }

    #gantt .fn-gantt .bottom {
        margin: 20px;
    }

    .form-control {

        border: 1px solid #ffffff !important;

    }

    .select2-container--default .select2-selection--single {
        border: 1px solid #ffffff !important;
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
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                @if(Auth::user()->hasPermission('ProjectManagement_Create'))
                                <a href="{{route('gnatt.chart.create')}}" class="btn btn-primary">
                                    Build Gantt Chart
                                </a>
                                @endif
                            </div>
                        </div>

                        <div id="gantt-chart">
                            <div class="data-table table-responsive mt-2">
                                <table class="table table-sm">
                                    <thead style="background-color:#34465b !important;">
                                        <tr>
                                            <th style="color: #fff; min-width: 50px; max-width: 50px;"> SL </th>
                                            <th style="color: #fff;"> Comapny </th>
                                            <th style="color: #fff;"> Gantt Chart </th>
                                            <th style="color: #fff;"> Party </th>

                                            <th class="text-center"
                                                style="color: #fff; min-width: 100px; max-width: 120px;"> Start Date
                                            </th>
                                            <th class="text-center"
                                                style="color: #fff; min-width: 100px; max-width: 120px;"> End Date </th>

                                            <th class="text-center"
                                                style="color: #fff; min-width: 120px; max-width: 150px;"> Remaining Days
                                            </th>
                                            <th class="text-center"
                                                style="color: #fff; min-width: 100px; max-width: 110px;"> Progress %
                                            </th>

                                            <th class="text-right" style="color: #fff; min-width: 100px;"> Action </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($gnatts as $key => $gnatt)
                                        @php
                                        $end = Carbon\Carbon::parse($gnatt->end_date);
                                        $now = Carbon\Carbon::today();
                                        $remain = $now->diffInDays($end, false);
                                        @endphp
                                        <tr>
                                            <td>{{ ($gnatts->currentPage() - 1) * $gnatts->perPage() + $key + 1 }}</td>
                                            <td class="chart-show"
                                                data-url="{{route('gnatt.chart.report', $gnatt->id)}}"
                                                title="{{$gnatt->job_project->company??$gnatt->job_project->company->company_name??'SBBC'}}">
                                                {{\Illuminate\Support\Str::limit($gnatt->job_project->company??$gnatt->job_project->company->company_name??'SBBC',30)
                                                }} </td>
                                            <td class="chart-show"
                                                data-url="{{route('gnatt.chart.report', $gnatt->id)}}"
                                                title="{{$gnatt->name}}">
                                                {{\Illuminate\Support\Str::limit($gnatt->name,30) }} </td>
                                            <td class="chart-show"
                                                data-url="{{route('gnatt.chart.report', $gnatt->id)}}"
                                                title="{{optional($gnatt->party)->pi_name}}">
                                                {{\Illuminate\Support\Str::limit(optional($gnatt->party)->pi_name,30) }}
                                            </td>
                                            <td class="text-center chart-show"
                                                data-url="{{route('gnatt.chart.report', $gnatt->id)}}">
                                                {{$gnatt->start_date ? date('d/m/Y',strtotime($gnatt->start_date)) :
                                                ''}}</td>
                                            <td class="text-center chart-show"
                                                data-url="{{route('gnatt.chart.report', $gnatt->id)}}">
                                                {{$gnatt->end_date ? date('d/m/Y',strtotime($gnatt->end_date)) : ''}}
                                            </td>
                                            <td class="text-center chart-show"
                                                data-url="{{route('gnatt.chart.report', $gnatt->id)}}">{{$remain > 0 ?
                                                $remain : '0.00'}}</td>
                                            @if($gnatt->items->count() > 0)
                                            <td class="text-center chart-show"
                                                data-url="{{route('gnatt.chart.report', $gnatt->id)}}">
                                                {{number_format($gnatt->items->sum('progress') /
                                                $gnatt->items->count(),2)}}</td>
                                            @else
                                            <td class="text-center chart-show"
                                                data-url="{{route('gnatt.chart.report', $gnatt->id)}}">0%</td>
                                            @endif

                                            <td class="">
                                                <div class="d-flex justify-content-end">
                                                    @if($gnatt->status == 0)
                                                    @if(Auth::user()->hasPermission('ProjectManagement_Delete'))
                                                    <form action="{{route('gnatt.chart.destroy',$gnatt->id)}}"
                                                        method="POST">
                                                        @csrf
                                                        @method('delete')

                                                        <button type="submit" style="border: none;" class="text-danger"
                                                            onclick="event.preventDefault(); deleteAlert(this, 'Are you want to delete this gantt chart ?');">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                    @endif

                                                    <a href="{{ route('tracking', [$gnatt->id]) }}"
                                                        style="border: none; margin:0 5px;" title="Project Progress">
                                                        <img src="{{asset('icon/progress.gif')}}" style="height: 20px"
                                                            alt="">
                                                    </a>

                                                    @if(Auth::user()->hasPermission('ProjectManagement_Approve'))
                                                    <a href="{{ route('gnatt.chart.approve', [$gnatt->id]) }}"
                                                        style="border: none; margin:0 5px;" title="Approve"
                                                        onclick="event.preventDefault(); deleteAlert(this, 'Are you want to approve this gantt chart ?' , 'approve');">
                                                        <i class='bx bx-message-square-check'></i>
                                                    </a>

                                                    @endif
                                                    @if(Auth::user()->hasPermission('ProjectManagement_Approve'))
                                                    <a href="{{route('gnatt.chart.edit', $gnatt->id)}}"
                                                        style="border: none; margin:0 5px;" class="text-info edit"> <i
                                                            class="bx bx-edit"></i> </a>
                                                    @endif
                                                    @endif

                                                    <a href="{{route('gnatt.chart.show',$gnatt->id)}}"
                                                        style="border: none;" class="text-info"> <i
                                                            class='bx bx-show'></i> </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center mt-1">
                            {{ $gnatts->links() }}
                        </div>

                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal fade bd-example-modal-lg" id="voucherPreviewModal" tabindex="-1" role="dialog"
    aria-labelledby="createTaskItemModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div id="voucherPreviewShow">

            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ asset('js/plugin/jquery-gantt.js') }}"></script>
<script>
    $(document).ready(function(){

        $(document).on('click', '.chart-show', function(){
            var url = $(this).data('url');
            $.ajax({
                url:url,
                type:'get',
                success:function(res){
                    $('#voucherPreviewModal').modal('show');
                    $('#voucherPreviewShow').html(res);
                }
            })
        })

        $(document).on('click', '.edit', function(){
            var url = $(this).data('url');
            $.ajax({
                url:url,
                type:'get',
                success:function(res){
                    $("#voucherPreviewShow").html(res);
                    $('#voucherPreviewModal').modal('show');
                }
            })
        })


        function deleteItem(id) {
            $.ajax({
                url: '/task-items/' + id,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function () {
                    alert('Deleted');
                    fetchItems();
                },
                error: function () {
                    alert('Delete failed');
                }
            });
        }

        function formatDate(date) {
            const d = new Date(date);
            return d.toISOString().split('T')[0];
        }
        let ganttInstance = null;
        $(document).on('click','.view', function(){
            $('#gnatt-modal').modal('show');
            $('#gnatt-modal').one('shown.bs.modal', function () {
                // Delay to allow full DOM layout
                setTimeout(function () {
                    // Optional: clear previous content
                    document.getElementById("ganttChartContainer").innerHTML = '';

                    var items = [
                        { content: "Task 1", start: new Date(2025, 4, 18), finish: new Date(2025, 4, 20) },
                        { content: "Task 2", start: new Date(2025, 4, 19), finish: new Date(2025, 4, 22), completedFinish: new Date(2025, 4, 20) }
                    ];

                    DlhSoft.Controls.GanttChartView.initialize(
                        document.getElementById("ganttChartContainer"),
                        items,
                        {
                            currentTime: new Date()
                        }
                    );
                }, 100); // Delay by 100ms
            });
        });
    });


    $(document).on('click', '.delete-document', function(e) {
        if (!confirm('Are you sure you want to delete this document?')) return;
        var id = $(this).attr('id');
        var _token = $('input[name="_token"]').val();
        $(this).closest('.document-wrapper').remove();
        $.ajax({
            method: "post",
            url: "{{ route('delete-job-document') }}",
            data: {
                id: id,
                _token: _token,
            },
            success: function(response) {
                toastr.success("Document deleted", "Success");
            }
        });
    })
</script>
@endpush
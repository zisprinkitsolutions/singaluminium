@extends('layouts.print_app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <h4 class=" text-center" style="margin:0;padding:0;line-height:29px;"> Requisition </h4>

        <div class="customer-info">
            <div class="row ml-1 mr-1 ">
                <div class="col-12 bg-white text-right" style="padding:0px 0 10px 0 !important">
                    <span >VAT TRN: {{$trn_no}}</span>
                </div>
                <div class="col-2 customer-static-content" style='border: 1px solid; padding: 5px;'>
                    <strong>For</strong> <br>
                        Project: <br>
                        Task: <br>
                        Sub Task: <br>
                        Contact No: <br>
                        Request Raise By:
                </div>
                <div class="col-8 customer-dynamic-content" style='border-top: 1px solid; border-bottom: 1px solid;'>
                    <br>
                        {{ $lpo->project? $lpo->project->project_name:'' }} <br>
                        {{ $lpo->task? $lpo->task->task_name:'' }} <br>
                        {{ $lpo->subTask? $lpo->subTask->item_description:'' }} <br>
                        {{ $lpo->attention!=null ? $lpo->attention : '...'}}<br>
                        {{$lpo->creator?$lpo->creator->name:''}}

                </div>
                <div class="col-2 customer-dynamic-content text-right pt-1" style='border: 1px solid;'>

                    <span>
                        NO.: {{$lpo->requisition_no}}<br>
                    </span>
                    <span>
                        Date: {{date('d/m/Y')}} <br>
                    </span>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="row" style="padding: 15px;">
    <div class="col-sm-12">

        @php
        $groupedByTask = $lpo->items->groupBy('job_project_task_id');
        @endphp

        @foreach($groupedByTask as $taskId => $taskItems)
        @php
        $taskName = optional($taskItems->first()->task)->task_name;
        $groupedBySubTask = $taskItems->groupBy('job_project_task_item_id');
        @endphp
        <div class="card  shadow-sm">
            <div class="card-header" style="padding: 5px 17px;" style="background-color: #f8f9fa;">
                <strong>Task: {{ $taskName ?? '-' }}</strong>
            </div>
            <div class="card-body" style="padding-top: 5px; padding-left: 20px; ">
                @foreach($groupedBySubTask as $subTaskId => $subTaskItems)
                @php
                $subTaskName = optional($subTaskItems->first()->subTask)->item_description;
                @endphp

                <div class=" border rounded">
                    {{-- <h6 class="text-secondary text-left" style="padding:3px 5px;">Sub-Task: {{ $subTaskName ?? '-' }}</h6> --}}
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th class="text-left ml-1">Description</th>
                                <th>Unit</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subTaskItems as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-left ml-1">{{ $item->item_description }}</td>
                                <td>{{ $item->unit? $item->unit->name : '' }}</td>
                                <td>{{ floatval($item->qty) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

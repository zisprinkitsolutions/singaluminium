<style>
    .btn-action {
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        border-radius: 6px;
        font-weight: 400;
        color: #fff !important;
    }
</style>
<div class="modal-header text-white" style="background-color:#0076ff;">
    <h5 class="modal-title" id="requisitionDetailsModalLabel">📋 Requisition Details</h5>

    <div class="d-flex align-items-center gap-2">
        {{-- Print Button --}}
        <a href="{{ route('requisitions.print', $requisition->id) }}" target="_blank" class="btn btn-sm btn-success"
            title="Print Requisition">
            🖨️
        </a>

        {{-- Close Button --}}
        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal" aria-label="Close" title="Close">
            ❌
        </button>
    </div>
</div>
<div class="modal-body">
    <div class="detail-item">
        <strong>Requisition No:</strong> {{$requisition->requisition_no}}
    </div>
    <div class="detail-item">
        <strong>Date:</strong> {{ date('d/m/Y') }}
    </div>
    <div class="detail-item">
        <strong>Project:</strong> {{ $requisition->project? $requisition->project->project_name:'' }}
    </div>
    {{-- <div class="detail-item">
        <strong>Task:</strong> {{ $requisition->task? $requisition->task->task_name:'' }}
    </div>
    <div class="detail-item">
        <strong>Sub Task:</strong> {{ $requisition->subTask? $requisition->subTask->item_description:'' }}
    </div> --}}
    <div class="detail-item">
        <strong>Contact No:</strong> {{ $requisition->attention!=null ? $requisition->attention : '...'}}
    </div>
    <div class="detail-item">
        <strong>Request Raised By:</strong> {{$requisition->creator?$requisition->creator->name:''}}
    </div>
    <div class="detail-item">
        <strong>Status:</strong> <span class="status-badge status-pending">{{ $requisition->status }}</span>
    </div>
    @if($requisition->status == 'Rejected')
    <div class="detail-item">
            <strong>Note:</strong> <span class="status-badge status-pending">{{$requisition->note }}</span>
        </div>
    @endif
    <hr>

    <h6>Items</h6>
    <div class="table-responsive-mobile">
        @php
        // Group items by Task
        $groupedByTask = $requisition->items->groupBy('job_project_task_id');

        @endphp

        @foreach($groupedByTask as $taskId => $taskItems)
        @php
        $taskName = optional($taskItems->first()->task)->task_name;
        $groupedBySubTask = $taskItems->groupBy('job_project_task_item_id');
        @endphp

        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Task: {{ $taskName ?? '-' }}</strong>
            </div>
            <div class="card-body">

                @foreach($groupedBySubTask as $subTaskId => $subTaskItems)
                @php
                $subTaskName = optional($subTaskItems->first()->subTask)->item_description;
                @endphp

                <div class="mb-3 p-2 border rounded">
                    <h6 class="text-secondary">Sub-Task: {{ $subTaskName ?? '-' }}</h6>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Description</th>
                                <th>Unit</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subTaskItems as $key => $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->item_description }}</td>
                                <td>{{ $item->unit ? $item->unit->name : '' }}</td>
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
<div class="modal-footer d-flex flex-wrap gap-2">

    {{-- Convert to LPO --}}
    {{-- @if ($requisition->status == 'Approved' && Auth::user()->hasPermission('Expense_Approve'))
    <a href="{{ route('requisitions.make.lpo', $requisition->id) }}" class="btn btn-action btn-primary">
        🔀 Convert LPO
    </a>
    @endif --}}

    {{-- Edit --}}
    @if ( $requisition->status != 'Approved' && $requisition->status != 'Rejected')
    <a data-id="{{  $requisition->id }}" class="btn btn-action btn-info editRequisitionBtn ">
        ✏️ Edit
    </a>
    @endif

    {{-- Approve --}}
    @if ($requisition->status == 'In Review' && Auth::user()->hasPermission('Expense_Approve') && Auth::user()->is_approver == 1)
    <a href="{{ route('requisition-approve', $requisition) }}" class="btn btn-action  btn-success req-approve-btn">
        ✅ Approve
    </a>
    @endif

    {{-- Delete button --}}
    @if ( $requisition->status != 'Approved')
    <div class="pr-1">
        <form action="{{ route('requisitions.destroy', $requisition->id) }}" method="POST"
            onsubmit="return confirm('Are you sure you want to delete this?')">
            @csrf
            @method('delete')
            <button type="submit" class="btn btn-action btn-danger">
                🗑️ Delete
            </button>
        </form>
    </div>
    @endif

    {{-- Reject button --}}
    @if ($requisition->status != 'Rejected' && $requisition->status != 'Approved')
    <div class="pr-1">
        <button type="button" class="btn btn-action  req-reject-btn" style="background:rgb(255, 89, 0)"
            data-id="{{ $requisition->id }}">
            🚫 Reject
        </button>
    </div>
    @endif


</div>


<style>
    .btn-action {
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    border-radius: 6px;
    font-weight: 500;
    color: #fff !important;
    }
</style>

<div class="modal-header  text-white" style="padding: 10px;">
    <h5 class="modal-title">📋 Requisition Details</h5>
    <div class="d-flex align-items-center">
        <a href="{{ route('requisitions.print', $requisition->id) }}" style="padding: 8px; margin-right: 5px;" target="_blank" class="btn btn-sm btn-success"
            title="Print Requisition">🖨️</a>
        <button type="button" class="btn btn-sm btn-danger" style="padding: 8px; " data-dismiss="modal" aria-label="Close">❌</button>
    </div>
</div>

<div class="modal-body">
    <div class="" style="padding : 0 5px;">
        <div style="text-align: left; line-height: 1.6;">
            <div class="row">
                 <div class="col-4"><strong>Requisition No:</strong> {{ $requisition->requisition_no }} <br>
                 <strong>Date:</strong> {{ date('d/m/Y') }} <br></div>
                <div class="col-4"><strong>Project:</strong> {{ $requisition->project? $requisition->project->project_name : '-' }} <br>
                <strong>Contact No:</strong> {{ $requisition->attention ?? '...' }} <br></div>
                <div class="col-4"><strong>Request Raised By:</strong> {{ $requisition->creator? $requisition->creator->name : '-' }} <br>
                <strong>Status:</strong>
                <span class="badge bg-warning text-dark">{{ $requisition->status }}</span></div>
                @if($requisition->status == 'Rejected')
                <div class="col-4"><strong>Rejecte Resone :</strong> {{ $requisition->reject_reason }}

                    </div>
                @endif
            </div>
        </div>
    </div>
    <h6 class="mt-1 text-left">Items</h6>
    @php
    $groupedByTask = $requisition->items->groupBy('job_project_task_id');
    @endphp

    @foreach($groupedByTask as $taskId => $taskItems)
    @php
    $taskName = optional($taskItems->first()->task)->task_name;
    $groupedBySubTask = $taskItems->groupBy('job_project_task_item_id');
    @endphp
    <div class="card  shadow-sm">
        <div class="card-header" style="padding: 5px 10px;" style="background-color: #f8f9fa;">
            <strong>Task: {{ $taskName ?? '-' }}</strong>
        </div>
        <div class="card-body">
            @foreach($groupedBySubTask as $subTaskId => $subTaskItems)
            @php
            $subTaskName = optional($subTaskItems->first()->subTask)->item_description;
            @endphp

            <div class=" border rounded">
                {{-- <h6 class="text-secondary text-left">Sub-Task: {{ $subTaskName ?? '-' }}</h6> --}}
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

<div class="modal-footer d-flex flex-wrap gap-2">
    @if ($requisition->status == 'Approved' && Auth::user()->hasPermission('Expense_Approve')&& Auth::user()->is_authorizer == 1 && Auth::user()->is_approver == 1)
    <a href="{{ route('requisitions.make.lpo', $requisition->id) }}" class="btn btn-action btn-primary">🔀 Convert
        LPO</a>
    @endif


    @if ($requisition->status == 'In Review' && $requisition->status != 'Rejected' && Auth::user()->hasPermission('Expense_Approve') && Auth::user()->is_approver == 1)
    <a href="{{ route('requisition-approve', $requisition) }}" class="btn btn-action btn-success req-approve-btn ">✅
        Approve</a>
    @endif
   @if($requisition->status != 'Rejected' || $requisition->status == 'Created')

   <a data-id="{{  $requisition->id }}" class="btn btn-action btn-info editRequisitionBtn">✏️ Edit</a>

    <form action="{{ route('requisitions.destroy', $requisition->id) }}" method="POST"
        onsubmit="return confirm('Are you sure you want to delete this?')">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-action btn-danger">🗑️ Delete</button>
    </form>
    @endif

    @if ($requisition->status != 'Rejected' && $requisition->status != 'Approved' && $requisition->status == 'In Review' && Auth::user()->is_authorizer == 1 && Auth::user()->is_approver == 1)
    <button type="button" class="btn btn-action text-white req-reject-btn" style="background:rgb(255, 89, 0)"
        data-id="{{ $requisition->id }}">
        🚫 Reject
    </button>
    @endif

    @if ($requisition->status != 'Rejected' && $requisition->status != 'Approved' && $requisition->status == 'In Review' &&
    Auth::user()->is_authorizer == 1 && Auth::user()->is_approver == 1)
    <button type="button" class="btn btn-action text-white req-reject-btn" style="background:rgb(255, 89, 0)"
        data-id="{{ $requisition->id }}">
        ↩️ Re Apply
    </button>
    @endif
</div>

    <div class="divFooter mb-1 ml-1 footer-margin invoice-view-wrapper">
        Business Software Solutions by
        <span style="color: #0005" class="spanStyle"><img class="img-fluid" src="{{ asset('img/zikash-logo.png') }}"
                alt="" width="150"></span>
    </div>
</section>

<div class="img receipt-bg invoice-view-wrapper footer-margin">
    <img src="{{ asset('img/singh-bg.png') }}" class="img-fluid"
        style="position: fixed; top: 420px; left: 200px; opacity: 0.2; width: 650px !important; height: 250px;"
        alt="">
</div>

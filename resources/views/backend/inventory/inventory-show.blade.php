
<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex flex-row-reverse">

        <div class="pr-1" style="padding-top: 8px;padding-right: 22px !important;"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
        <div class="pr-1" style="padding: 8px;padding-right: 0.2rem !important;" title="Print"><a href="#" onclick="window.print();" class="btn btn-icon btn-secondary"><i class="bx bx-printer"></i></a></div>
        @if ($temp)
        <div class="pr-1" style="padding-top: 8px;padding-right: 0.2rem !important;" title="Edit"><a href="#" class="btn inventory-edit btn-sm btn-icon btn-success" id="{{$inventory->id}}" style="padding: 6px 8px;margin-right: -8px;"><i class="bx bx-edit"></i></a></div>
        <div class="pr-1" style="padding-top: 8px;padding-right: 10px !important;" title="Approve"><a href="{{route('inventory-expense-approve',$inventory)}}" class="btn btn-sm btn-icon btn-warning" onclick="event.preventDefault(); deleteAlert(this, 'About to approve . Please, confirm?', 'approve');" style="padding: 6px 8px;margin-right: -8px;"><i class="bx bx-check"></i></a></div>
        <div class="pr-1" style="padding-top: 9px;padding-right: 0.2rem !important;" title="Delete"><a href="{{route('inventory-expense-delete',$inventory)}}" class="btn btn-sm btn-icon btn-danger"  onclick="event.preventDefault(); deleteAlert(this, 'About to delete . Please, confirm?');" style="padding: 6px 8px;"><i class="bx bx-trash"></i></a></div>
        @endif

        <div class="pr-1 w-100 pl-2">
            <h4 style="font-family:Cambria;font-size: 2rem;color:white;">Project Expense Details</h4>
        </div>
        {{-- <div class="py-1 pr-1"><a href="#" onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
    </div>
</section>
<div class="receipt-voucher-hearder invoice-view-wrapper" style="margin: 50px 20px; border-radius: 20px;">
    @include('layouts.backend.partial.modal-header-info')
</div>
<div class="row pr-1 w-100 pl-2">
    <div class="col-2">
        <label for=""><strong>Date:</strong></label>
        {{date('d/m/Y', strtotime($inventory->date))}}
    </div>
    <div class="col-6">
        <label for=""><strong>Account Head:</strong></label>
        {{$inventory->sub_account_head?$inventory->sub_account_head->name:$inventory->account_head->fld_ac_head}}
    </div>
</div>
<table class="table table-sm pr-1 w-100 pl-2" style="">
    <thead>
        <tr>
            <th class="text-left pl-1">Project</th>
            <th class="text-left pl-1">Task</th>
            <th class="text-left pl-1">Task Item</th>
            <th style="width: 50px;" class="text-right pr-1">QTY</th>
        </tr>
    </thead>
    <tbody id="task_TBody">
        @foreach ($inventory->items as $temp)
            <tr class="text-center invoice_row">
                <td  class="text-left pl-1">{{$temp->project->project_name??''}}</td>
                <td  class="text-left pl-1">{{$temp->project_task->task_name??''}}</td>
                <td  class="text-left pl-1">{{$temp->project_task_item->item_description??''}}</td>
                <td class="text-right pr-1">{{$temp->qty}}</td>
            </tr>
        @endforeach
    </tbody>
    <tbody>
        <tr>
            <td colspan="3" class="text-right pr-1">Total QTY: </td>
            <td class="text-right pr-1">{{$inventory->items->sum('qty')}}</td>
        </tr>
    </tbody>
</table>
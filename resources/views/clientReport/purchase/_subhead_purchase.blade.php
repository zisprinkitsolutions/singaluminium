

<style>
    .bg-secondary {
        background-color: #34465b !important;
        border-radius: 40px;
        color:white  !important;
        padding: 2px 5px 2px 5px !important;
    }
    a.bg-secondary:hover, a.bg-secondary:focus,
    button.bg-secondary:hover,
    button.bg-secondary:focus {
        background-color: #475f7b30 !important;
        color:black!important;
    }
    tr:nth-child(even) {
        background-color: #c8d6e357;
    }
    a.text-dark:hover, a.text-dark:focus {
        color: #ffffff !important;
    }
    .btn-outline-secondary {
        border-radius: 40px;
        padding: 0.2px 9px 0.2px 9px !important;
    }
</style>

<div class="d-flex gap-2">
    <a href="{{route("purchase-expense")}}" class="btn btn-outline-secondary nav-item nav-link tabPadding {{  $activeMenu == 'create' ? 'bg-secondary text-white' : ' text-dark'}}" style="margin-right:15px;">
        <div>&nbsp;&nbsp; Create New &nbsp;&nbsp;</div>
    </a>
    <a href="{{route('purchase_approve')}}" class="btn btn-outline-secondary nav-item nav-link tabPadding {{  $activeMenu == 'approve' ? 'bg-secondary text-white' : ' text-dark'}}" style="margin-right:15px;">
        <div>Awaiting Approval</div>
    </a>
    <a href="#" class="btn btn-outline-secondary nav-item nav-link tabPadding stock_inventory_model {{  $activeMenu == 'inventory' ? 'bg-secondary text-white' : ' text-dark'}}" style="margin-right:15px;" data-toggle="modal" data-target="#stock_inventory_model">
        <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Inventory &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </div>
    </a>
</div>

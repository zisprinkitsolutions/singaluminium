<div class="d-flex" style="width: 850px !important; margin:15px;">

    <a href="#" class="btn btn-outline-secondary nav-item nav-link inputFieldHeight tabPadding stock_inventory_model {{  $activeMenu == 'inventory' ? 'bg-secondary text-white' : ' text-dark'}}" style="margin-right:15px; padding:3px 6px !important;">
        <div>Inventory</div>
    </a>
    <a href="#" id="pending-inventory" class="btn btn-outline-secondary inputFieldHeight nav-item nav-link tabPadding inventory-list  {{  $activeMenu == 'pendding-inventory' ? 'bg-secondary text-white' : ' text-dark'}}" style="margin-right:15px; padding:3px 6px !important;">
        <div>Pending</div>
    </a>
    <a href="#" id="approval-inventory" class="btn btn-outline-secondary inputFieldHeight inventory-list nav-item nav-link tabPadding  {{  $activeMenu == 'approval-inventory' ? 'bg-secondary text-white' : ' text-dark'}}" style="margin-right:15px; padding:3px 6px !important;">
        <div>Approve </div>
    </a>
</div>

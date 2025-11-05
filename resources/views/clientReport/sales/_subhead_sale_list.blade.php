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

<div class="d-flex align-items-center gap-2">
    <a href="{{route("sale-list")}}" class="btn btn-outline-secondary nav-item nav-link tabPadding {{  $activeMenu == 'tax-invoice' ? 'bg-secondary text-white' : ' text-dark'}}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div>Tax Invoice</div>
    </a>
    <a href="{{route('sale-proforma-invoice-list')}}" class="btn btn-outline-secondary nav-item nav-link tabPadding {{  $activeMenu == 'proforma-invoice' ? 'bg-secondary text-white d-block' : ' text-dark '}}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div>Proforma Invoice</div>
    </a>
    <a href="{{route('sale-direct-invoice-list')}}" class="btn btn-outline-secondary nav-item nav-link tabPadding {{  $activeMenu == 'direct-invoice' ? 'bg-secondary text-white' : ' text-dark'}}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">

        <div>Direct Invoice </div>
    </a>
    <a href="{{route('all-invoice-list')}}" class="btn btn-outline-secondary nav-item nav-link tabPadding {{  $activeMenu == 'all-invoice' ? 'bg-secondary text-white' : ' text-dark'}}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">

        <div>All Invoice </div>
    </a>

    <a href="{{route('transection')}}" class="btn btn-outline-secondary nav-item nav-link tabPadding {{  $activeMenu == 'transections' ? 'bg-secondary text-white' : ' text-dark'}}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">

        <div>Transections</div>
    </a>

</div>

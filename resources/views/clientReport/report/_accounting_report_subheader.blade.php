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
    <a href="{{route("new-general-ledger")}}" class="btn btn-outline-secondary nav-item nav-link tabPadding {{  $activeMenu == 'general_ledger' ? 'bg-secondary text-white' : ' text-dark'}}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div>General Ledger</div>
    </a>

    <a href="{{route('party-report')}}" class="btn btn-outline-secondary nav-item nav-link tabPadding {{  $activeMenu == 'party_report' ? 'bg-secondary text-white' : ' text-dark'}}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">

        <div>&nbsp;Party Ledger &nbsp;</div>
    </a>


    <a href="{{route('new-trial-balance')}}" class="btn btn-outline-secondary nav-item nav-link tabPadding {{  $activeMenu == 'trial_balance' ? 'bg-secondary text-white' : ' text-dark'}}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">

        <div>&nbsp;Trial Balance &nbsp;</div>
    </a>
    <a href="{{route('income-statement')}}" class="btn btn-outline-secondary nav-item nav-link tabPadding {{  $activeMenu == 'income_statement' ? 'bg-secondary text-white' : ' text-dark'}}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">

        <div>Income Statement</div>
    </a>
    <a href="{{route('balance-sheet')}}" class="btn btn-outline-secondary nav-item nav-link tabPadding {{  $activeMenu == 'balance_sheet' ? 'bg-secondary text-white' : ' text-dark'}}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">

        <div>Balance Sheet</div>
    </a>

    <a href="{{route('purchase-reports')}}" class="btn btn-outline-secondary nav-item nav-link tabPadding {{  $activeMenu == 'purchase_reports' ? 'bg-secondary text-white' : ' text-dark'}}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div>Purchase Reports</div>
    </a>
    <a href="{{route('sale-reports')}}" class="btn btn-outline-secondary nav-item nav-link tabPadding {{  $activeMenu == 'sale_reports' ? 'bg-secondary text-white' : ' text-dark'}}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div>Sale Reports</div>
    </a>
</div>

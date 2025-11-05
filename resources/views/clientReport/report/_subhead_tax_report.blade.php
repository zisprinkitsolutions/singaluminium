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
    <a href="{{route("tax-reports")}}" class="btn btn-outline-secondary nav-item nav-link tabPadding {{  $activeMenu == 'profit_loss' ? 'bg-secondary text-white' : ' text-dark'}}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div>Statement of Profit/Loss</div>
    </a>
    <a href="{{route('statement-other-comprehensive-income')}}" class="btn btn-outline-secondary nav-item nav-link tabPadding {{  $activeMenu == 'comprehensive_income' ? 'bg-secondary text-white d-block' : ' text-dark'}}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div>Statement of Other Comprehensive Income</div>
    </a>
    <a href="{{route('statement-financial-position')}}" class="btn btn-outline-secondary nav-item nav-link tabPadding {{  $activeMenu == 'financial_position' ? 'bg-secondary text-white' : ' text-dark'}}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div>Statement of Financial Position</div>
    </a>
</div>

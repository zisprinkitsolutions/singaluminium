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
        border-radius: 5px;
        padding: 0.2px 9px 0.2px 9px !important;
    }
</style>

<div class="d-flex align-items-center gap-2 mb-1" style="margin-left: -5px">
    <a href="{{route("employee-salary-show")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'salary' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div>  Sheet </div>
    </a>
    <a href="{{route("salary-process.index")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'salary-process' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div> Process </div>
    </a>

    <a href="{{route("salary-process.authorize-list")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'authorize' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div> Awaiting Approve  </div>
    </a>

    <a href="{{route("salary-process.approve-list")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'list' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div> Approve List  </div>
    </a>
</div>

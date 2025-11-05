<style>
    .bg-secondary {
        background-color: #34465b !important;
        /* border-radius: 40px; */
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

<div class="d-flex align-items-center gap-2 ml-2">

    <a href="{{route("new-employee-attendance")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'attendance' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div> Employee Attendance </div>
    </a>

    <a href="{{route("employee-monthly-attendance")}}" class="btn btn-outline-secondary ml-1 nav-item nav-link {{ $activeMenu == 'monthly' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div>Monthly Attendance</div>
    </a>
    <a href="{{route("weekend-holiday-policies.index")}}" class="btn btn-outline-secondary ml-1 nav-item nav-link {{ $activeMenu == 'holiday-weekend' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div>Weekend </div>
    </a>

    <a href="{{route("project.working.report")}}" class="btn btn-outline-secondary ml-1 nav-item nav-link {{ $activeMenu == 'project' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div> Report </div>
    </a>
</div>

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
    {{-- <a href="{{route("grade-wise-salary-components.index")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'grade-wise-salary-components' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div> Grade Wise Components </div>
    </a>

    <a href="{{route("employee-salary.index")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'employee-wise-component' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div>
            Employee Wise Components
        </div>
    </a> --}}
{{--
    <a href="{{route("salary-structures.index")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'salary-component' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div>  Salary Components      </div>
    </a>
    <a href="{{route("grades.index")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'grades' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false"style="margin-right:15px;">
        <div> Grades </div>
    </a> --}}
    {{-- <a href="{{route("job-type-info.index")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'job_type_info' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false"style="margin-right:15px;">
        <div> Job Type </div>
    </a> --}}

    <a href="{{route("division.index")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'division' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div>Department </div>
    </a>

    {{-- <a href="{{route("department.index")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'department' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div> Designation </div>
    </a> --}}

    {{-- <a href="{{route("salary-types.index")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'salary-type' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div> Salary Type </div>
    </a> --}}
    {{-- <a href="{{route("policies.index")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'polices' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div> Polices </div>
    </a> --}}

</div>

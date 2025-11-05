@if ($sub_menu == 'employee_profile')
<div class="d-flex align-items-center gap-2" style="border-bottom: 1px solid #ddd">
    <a href="{{route("new-employee-section")}}" class=" nav-item nav-link active {{ request()->is('*new-employee-section') ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div>Employee Profile</div>
    </a>
    <a href="{{route("new-employee-document")}}" class="text-dark nav-item nav-link {{ request()->is('*new-employee-document') ? 'bg-secondary text-white' :'text-dark' }}"" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div>Employee Document</div>
    </a>
</div>

@elseif($sub_menu == 'employee_attendance')
<div class="d-flex align-items-center gap-2" style="border-bottom: 1px solid #ddd">
    <a href="{{route('new-employee-attendance')}}" class="nav-item nav-link {{ request()->is('*new-employee-attendance') ? 'bg-secondary text-white' :'text-dark' }}">
        <div>Employees Attendance</div>
    </a>
    <a href="{{route("new-employee-leave")}}" class="nav-item nav-link {{ request()->is('*new-employee-leave') ? 'bg-secondary text-white' :'text-dark' }}">
        <div>Employees Leave</div>
    </a>
</div>
@endif






<div class="d-flex align-items-center gap-2 d-none {{ request()->is('new') ? 'show' : 'd-none' }}" style="border-bottom: 1px solid #ddd">
    <a href="{{route("new-fees-collections")}}" class="nav-item nav-link {{ request()->is('new-employee-section') ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div>Collection Heads</div>
    </a>
</div>

<div class="d-flex align-items-center gap-2 d-none {{ request()->is('new') ? 'show' : 'd-none' }}" style="border-bottom: 1px solid #ddd">
    <a href="{{route("new-fees-collections")}}" class="nav-item nav-link {{ request()->is('new-employee-section') ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div>Collection Heads</div>
    </a>
</div>

<div class="d-flex align-items-center gap-2 {{ request()->is('*new') ? 'show' : 'd-none' }}" style="border-bottom: 1px solid #ddd">
    <a href="{{route("new-fees-collections")}}" class="nav-item nav-link {{ request()->is('new-employee-section') ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div>Collection Heads</div>
    </a>
</div>

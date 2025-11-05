<div class="d-flex align-items-center gap-2" style="border-bottom: 1px solid #ddd">
    <a href="{{route("new-exam")}}" class="nav-item nav-link {{ $activeMenu == 'employee_profile' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">

        <div> Employee Profile </div>
    </a>

    <a href="{{route('new-exam-schedule')}}" class="nav-item nav-link {{ $activeMenu == 'salary_procedure' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">

        <div> Salary Procedure </div>
    </a>
    <a href="{{route('new-exam-attendance')}}" class="nav-item nav-link {{ $activeMenu == 'company_bank_info' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">

        <div> Company Bank Info </div>
    </a>
    <a href="{{route("new-student-mark")}}" class="nav-item nav-link {{ $activeMenu == 'bases_table' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">

        <div> Base Table </div>
    </a>
    <a href="{{route("new-teacher-exam-list")}}" class="nav-item nav-link {{ $activeMenu == 'teacher_exam_list' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">

        <div> Base Table </div>
    </a>
</div>

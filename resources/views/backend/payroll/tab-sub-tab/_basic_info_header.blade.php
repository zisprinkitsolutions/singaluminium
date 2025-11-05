<style>
    .card{
        margin-bottom: 0.5rem;
    }
</style>
<div class="nav nav-tabs master-tab-section" id="nav-tab" role="tablist">
    @if (
        Auth::user()->hasPermission('Employee') ||
        Auth::user()->hasPermission('Attendance') ||
        Auth::user()->hasPermission('Employee_Leave') ||
        Auth::user()->hasPermission('Salary') ||
        Auth::user()->hasPermission('salary_procedure') ||
        Auth::user()->hasPermission('HR_Setup')
      )


    @if (Auth::user()->hasPermission('Employee'))

    <a href="{{ route('employees.index') }}" class="nav-item nav-link {{$activeMenu == 'employee-profile' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-center">
            <img src="{{asset('assets/backend/app-assets/icon/employee-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Employee  </div>
    </a>
    @endif
    {{-- @if (Auth::user()->hasPermission('player'))

    <a href="{{ route('players.index') }}" class="nav-item nav-link {{$activeMenu == 'player' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-center">
            <img src="{{asset('icon/sport.png')}}" alt="" srcset="" class="img-fluid" >
        </div>
        <div> Player Profile  </div>
    </a>
    @endif --}}
@if (Auth::user()->hasPermission('Attendance'))
    <a href="{{ route('new-employee-attendance') }}" class="nav-item nav-link {{$activeMenu == 'attendance' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-center">
            <img src="{{asset('assets/backend/app-assets/icon/exam-attendance-icon.png')}}" alt="" srcset="" class="img-fluid" >
        </div>
        <div> Attendance </div>
    </a>


    {{-- @if (Auth::user()->hasPermission('policy')) --}}

    {{-- <a href="{{route("policies.index")}}" class="nav-item nav-link {{$activeMenu == 'policies' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-center">
            <img src="{{asset('assets/backend/app-assets/icon/subject-details-icon.png')}}" alt="" srcset="" class="img-fluid" >
        </div>
        <div> Policies </div>
    </a> --}}
    {{-- @endif --}}
    {{-- @if (Auth::user()->hasPermission('employee_leave_approval')) --}}

    <a href="{{ route('employee-leave-application.index') }}" class="nav-item nav-link {{$activeMenu == 'leave_application' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-center">
            <img src="{{asset('assets/backend/app-assets/icon/exam-icon.png')}}" alt="" srcset="" class="img-fluid" >
        </div>
        <div> Leave Application </div>
    </a>

    @endif

    @if (Auth::user()->hasPermission('notice'))

    <a href="{{route("notice-board.index")}}" class="nav-item nav-link {{$activeMenu == 'notice_borad' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-center">
            <img src="{{asset('assets/backend/app-assets/icon/collection-icon.png')}}" alt="" srcset="" class="img-fluid" >
        </div>
        <div>Notice </div>
    </a>
    @endif
    @if (Auth::user()->hasPermission('Salary'))
    <a href="{{ route('employee-salary-show') }}" class="nav-item nav-link {{$activeMenu == 'salary-info' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-center">
            <img src="{{asset('assets/backend/app-assets/icon/list-icon.png')}}" alt="" srcset="" class="img-fluid" >
        </div>
        <div> Salary </div>
    </a>
    @endif
    @else

    <a href="{{ route('employees.index') }}" class="nav-item nav-link {{$activeMenu == 'employee-profile' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-center">
            <img src="{{asset('assets/backend/app-assets/icon/employee-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> My Profile </div>
    </a>

    <a href="{{ route('new-employee-attendance') }}" class="nav-item nav-link {{$activeMenu == 'attendance' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-center">
            <img src="{{asset('assets/backend/app-assets/icon/exam-attendance-icon.png')}}" alt="" srcset="" class="img-fluid" >
        </div>
        <div>  Attendance </div>
    </a>

    <a href="{{ route('employee-leave-application.index') }}" class="nav-item nav-link {{$activeMenu == 'leave_application' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-center">
            <img src="{{asset('assets/backend/app-assets/icon/list-icon.png')}}" alt="" srcset="" class="img-fluid" >
        </div>
        <div>  Leave Application </div>
    </a>

    <a href="{{ route('employee-salary-show') }}" class="nav-item nav-link {{$activeMenu == 'salary-info' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-center">
            <img src="{{asset('assets/backend/app-assets/icon/list-icon.png')}}" alt="" srcset="" class="img-fluid" >
        </div>
        <div> Salary </div>
    </a>

    @endif

    <a href="{{ route('reporting.authority.index') }}" class="nav-item nav-link {{$activeMenu == 'reporting-authority' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-center">
            <img src="{{asset('assets/backend/app-assets/icon/exam-schedule-icon.png')}}" alt="" srcset="" class="img-fluid" >
        </div>
        <div> Reporting Authority </div>
    </a>

    @if (Auth::user()->hasPermission('HR_Setup'))

    <a href="{{ route('division.index') }}" class="nav-item nav-link {{$activeMenu == 'grade-wise-salary-components' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-center">
            <img src="{{asset('assets/backend/app-assets/icon/collection-head-icon.png')}}" alt="" srcset="" class="img-fluid" >
        </div>
        <div> HR / PAYROLL </div>
    </a>
    @endif
</div>

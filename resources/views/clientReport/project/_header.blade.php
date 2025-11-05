<div class="nav nav-tabs master-tab-section print-hideen " id="nav-tab" role="tablist">

    @if(Auth::user()->hasPermission('Onboard'))
    <a href="{{route('projects.index')}}"
        class="nav-item nav-link {{ request()->is('*/projects*') || request()->is('*work/statuion/create*') ? 'active' : '' }}"
        role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/work-order.png')}}" alt="" srcset="" class="img-fluid" width="50" height="20">
        </div>
        <div>&nbsp;&nbsp;&nbsp;&nbsp; Projects &nbsp;&nbsp;&nbsp;&nbsp;</div>
    </a>
    @endif
    {{-- @if(Auth::user()->hasPermission('Gantt_Chart'))
    <a href="{{route('gnatt.chart.index')}}" class="nav-item nav-link {{ request()->is('*gnatt*') ? 'active' : '' }}"
        role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/gantt-chart-menu.png')}}" alt="" srcset="" class="img-fluid" width="50" height="20">
        </div>
        <div class="text-center">&nbsp;&nbsp; Gantt Chart &nbsp;&nbsp;</div>
    </a>
    @endif --}}
    {{-- <a href="{{route('cost.analysis')}}"
        class="nav-item nav-link {{ request()->is('*project/cost/analysis') || request()->is('*search-project-report') ? 'active' : '' }}" role="tab"
        aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/document-icon.png')}}" alt="" srcset="" class="img-fluid"
                width="50" height="20">
        </div>
        <div> Financial Analysis </div>
    </a>
    @if(Auth::user()->hasPermission('Project'))
    <a href="{{route('new-project.index')}}"
        class="nav-item nav-link {{ request()->is('*new-project*') ? 'active' : '' }}" role="tab"
        aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/project.png')}}" alt="" srcset="" class="img-fluid" width="50" height="20">
        </div>
        <div class="text-center">&nbsp;&nbsp;&nbsp;&nbsp; Prospects &nbsp;&nbsp;&nbsp;&nbsp;</div>
    </a>
    @endif --}}
    @if(Auth::user()->hasPermission('Bill_OF_Quantity'))

    <a href="{{route('boq.index')}}" class="nav-item nav-link {{ request()->is('*bill-of-quantity*') ? 'active' : '' }}" role="tab"
        aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/list.png')}}" alt="" srcset="" class="img-fluid" width="50" height="20">
        </div>
        <div class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; BOQ
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
    </a>
    @endif
    @if(Auth::user()->hasPermission('Quotation'))
    <a href="{{route('lpo-projects.index')}}"
        class="nav-item nav-link {{ request()->is('*lpo-projects*') ? 'active' : '' }}" role="tab"
        aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/invoice.png')}}" alt="" srcset="" class="img-fluid" width="50" height="20">
        </div>
        <div class="text-center">&nbsp;&nbsp;&nbsp;&nbsp; Quotation &nbsp;&nbsp;&nbsp;&nbsp;</div>
    </a>
    @endif

    @if(Auth::user()->hasPermission('Project_Task'))
    <a href="{{route('project.tasks.index')}}"
        class="nav-item nav-link {{ request()->is('*project/tasks*') ? 'active' : '' }}" role="tab"
        aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/fee-structure-icon.png')}}" alt="" srcset=""
                class="img-fluid" width="50" height="20">
        </div>
        <div class="text-center"> Project Task </div>
    </a>
    @endif

    {{-- @if(Auth::user()->hasPermission('Project_Task'))
    <a href="{{route('boq.sample.list')}}"
        class="nav-item nav-link {{ request()->is('project/boq/sample/list') ? 'active' : '' }}" role="tab"
        aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/list-icon.png')}}" alt="" srcset=""
                class="img-fluid" width="50" height="20">
        </div>
        <div class="text-center"> BOQ Sample </div>
    </a>
    @endif --}}



    {{-- @if (Auth::user()->hasPermission('p_Invoice'))
    <a href="{{route('project.authorize.invoice')}}"
        class="nav-item nav-link {{ request()->is('*job/project*/invoice*') ? 'active' : '' }}" role="tab"
        aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/document-icon.png')}}" alt="" srcset="" class="img-fluid"
                width="50" height="20">
        </div>
        <div> Invoice </div>
    </a>
    @endif
    <a href="{{route('projects.report')}}"
        class="nav-item nav-link {{ request()->is('*jobprojects/*reports') ? 'active' : '' }}" role="tab"
        aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/report.png')}}" alt="" srcset="" class="img-fluid"
                width="50" height="20">
        </div>
        <div> Report </div>
    </a> --}}
</div>

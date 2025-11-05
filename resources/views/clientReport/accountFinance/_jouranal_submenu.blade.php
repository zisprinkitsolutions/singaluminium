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
    <a href="{{route("new-journal")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'jouranal_view' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">

        <div>View</div>
    </a>
    <a href="{{route('new-journal-creation')}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'journal_entry' ? 'bg-secondary text-white' :'text-dark' }}"  role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">

        <div>Entry</div>
    </a>
    <a  href="{{route("journal-authorization-section")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'authorization' ? 'bg-secondary text-white' :'text-dark' }}"" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection" style="margin-right:15px;">

        <div>Waiting for Authorize</div>
    </a>
    <a href="{{route("journal-approval-section")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'approval' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">

        <div>  Waiting for Approval  </div>
    </a>
</div>

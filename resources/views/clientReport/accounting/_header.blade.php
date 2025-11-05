<div class="nav nav-tabs master-tab-section" id="nav-tab" role="tablist">

    @if(Auth::user()->hasPermission('View'))
    <a href="{{route("new-journal")}}" class="nav-item nav-link {{ $activeMenu == 'jouranal' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/list-view.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div>&nbsp &nbsp &nbsp &nbsp &nbsp  View &nbsp &nbsp &nbsp &nbsp &nbsp  </div>
    </a>
    @endif
    @if(Auth::user()->hasPermission('Accounting_Create'))
    <a href="{{route('new-journal-creation')}}" class="nav-item nav-link {{ $activeMenu=='jouranal-creation' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/account-entry.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div>&nbsp &nbsp &nbsp &nbsp &nbsp Entry &nbsp &nbsp &nbsp &nbsp &nbsp</div>
    </a>
    @endif
    @if(Auth::user()->hasPermission('Accounting_Authorize'))
    <a href="{{route("journal-authorization-section")}}" class="nav-item nav-link {{ $activeMenu == 'journal_authorize' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/authorize.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div>Waiting for Authorize</div>
    </a>
    @endif
    @if(Auth::user()->hasPermission('Accounting_Approve'))
    <a href="{{route("journal-approval-section")}}" class="nav-item nav-link {{ $activeMenu == 'jouranal-approve' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/approve.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Waiting for Approval </div>
    </a>
    @endif
    @if(Auth::user()->hasPermission('Fund_Allocation'))
    <a href="{{route("fund-allocation.index")}}" class="nav-item nav-link {{ $activeMenu == 'fund-allocation' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/payment.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Fund Allocation </div>
    </a>
    @endif


</div>





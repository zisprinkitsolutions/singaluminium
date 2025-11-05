<div class="nav nav-tabs master-tab-section" id="nav-tab" role="tablist">
    @if (Auth::user()->hasPermission('Chart_of_Accounts'))
    <a href="{{route('new-chart-of-account')}}" class="nav-item nav-link {{ $activeMenu=='chart-of-account' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/chart-of-account.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> chart of account</div>
    </a>
    @endif
    @if (Auth::user()->hasPermission('Stake_Holder'))
    <a href="{{route("partyInfoDetails")}}" class="nav-item nav-link {{ $activeMenu == 'cost_center' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/stake-holder.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Stake Holder </div>
    </a>
    @endif
    {{-- @if (Auth::user()->hasPermission('Stake_Holder'))
    <a href="{{route("subsidiary.index")}}" class="nav-item nav-link {{ $activeMenu == 'subsidiary' ? 'active' : ' ' }}"
        role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
          <img src="{{asset('icon/chart-of-account.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Subsidiary </div>
    </a>
    @endif --}}
</div>

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
    <a href="{{route("costCenterDetails")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'cost_center' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">

        <div> Cost Center </div>
    </a>
    <a href="{{route('profitCenterDetails')}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'profit_center' ? 'bg-secondary text-white' :'text-dark' }}"  role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">

        <div> Profit Center </div>
    </a>

    <a  href="{{route("partyInfoDetails")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'party_info' ? 'bg-secondary text-white' :'text-dark' }}"" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection" style="margin-right:15px;">
        <div> Party Information </div>
    </a>
    <a href="{{route("service-provider.index")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'service_provider' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">

        <div>  Service Provider </div>
    </a>

    {{-- [<a href="{{route("new-donar")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'donar' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div>  Donor </div>
    </a>

    <a href="{{route("new-charity")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'charity' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div>  Charity </div>
    </a>] --}}
</div>

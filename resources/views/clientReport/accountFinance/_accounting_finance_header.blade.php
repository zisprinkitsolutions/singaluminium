<div class="nav nav-tabs master-tab-section" id="nav-tab" role="tablist">
    <!--<a href="{{ route('account-info.index') }}" class="nav-item nav-link {{ $activeMenu == 'currency' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false">-->
    <!--    <div class="master-icon text-cente">-->
    <!--        <img src="{{asset('assets/backend/app-assets/icon/master-account.png')}}" alt="" srcset="" class="img-fluid" width="50">-->
    <!--    </div>-->
    <!--    <div>Accounting Info</div>-->
    <!--</a>-->
    <a href="{{route('new-chart-of-account')}}" class="nav-item nav-link {{ $activeMenu == 'chart_of' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/master-account.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> chart of account</div>
    </a>
    <a href="{{route("costCenterDetails")}}" class="nav-item nav-link {{ $activeMenu == 'cost_center' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/document-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Stake Holder </div>
    </a>

    <a href="{{ route('purchase-expense') }}" class="nav-item nav-link {{ $activeMenu == 'purchase_expense' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/payment-icon.png')}}" alt="" srcset="" class="img-fluid" width="55">
        </div>
        <div> Purchase Expense  </div>
    </a>

    <a href="{{ route('saleIssue') }}" class="nav-item nav-link {{ $activeMenu == 'sales' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/payment-icon.png')}}" alt="" srcset="" class="img-fluid" width="55">
        </div>
        <div> Sales  </div>
    </a>

    <a href="{{ route('new-journal') }}" class="nav-item nav-link {{ $activeMenu == 'jouranal' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/account-recieved-icon.png')}}" alt="" srcset="" class="img-fluid" width="55">
        </div>
        <div> Journal  </div>
    </a>



    {{-- <a href="{{ route('opening-asset') }}" class="nav-item nav-link {{ $activeMenu == 'opening_balance' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/voucher-list-icon.png')}}" alt="" srcset="" class="img-fluid" width="55">
        </div>
        <div> Opening Balance  </div>
    </a> --}}

    <a href="{{ route('new-general-ledger') }}" class="nav-item nav-link {{ $activeMenu == 'account_report' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/due-report-icon.png')}}" alt="" srcset="" class="img-fluid" width="55">
        </div>
        <div> Accounts Report </div>
    </a>

    {{-- <a href="#" class="nav-item nav-link {{ $activeMenu == 'report' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/leave-icon.png')}}" alt="" srcset="" class="img-fluid" width="55">
        </div>
        <div> Report  </div>
    </a> --}}
</div>

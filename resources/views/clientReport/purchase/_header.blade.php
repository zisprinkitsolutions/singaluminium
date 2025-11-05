@if(!isset($isMobile) || !$isMobile)
<div class="nav nav-tabs master-tab-section print-hidden" id="nav-tab" role="tablist">
    @if(Auth::user()->hasPermission('Requisition'))
    <a href="{{route("requisitions.index")}}"
        class="nav-item nav-link {{ $activeMenu=='requisition' ? 'active' : ' ' }}" role="tab"
        aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/exam-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Requisition </div>
    </a>
    @endif
    @if(Auth::user()->hasPermission('LPO'))

    <a href="{{route("lpo-bill-list")}}" class="nav-item nav-link {{ $activeMenu == 'lpo_bill' ? 'active' : ' ' }}"
        role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/approve.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp LPO &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp </div>
    </a>
    @endif
    @if(Auth::user()->hasPermission('Expense'))
    <a href="{{route("purchase-expense")}}"
        class="nav-item nav-link {{ $activeMenu=='purchase_expense' ? 'active' : ' ' }}" role="tab"
        aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/purchaseexpense-entry.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div>&nbsp &nbsp &nbsp &nbsp Expense &nbsp &nbsp &nbsp &nbsp</div>
    </a>
    @endif
    @if(Auth::user()->hasPermission('Payment'))

    {{-- <a href="{{route(" expense-allocation.index")}}"
        class="nav-item nav-link {{ $activeMenu=='expense-allocation' ? 'active' : ' ' }}" role="tab"
        aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/account-entry.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Expense Allocation</div>
    </a> --}}

    <a href="{{route("payment-voucher2")}}"
        class="nav-item nav-link {{ $activeMenu == 'payment_voucher' ? 'active' : ' ' }}" role="tab"
        aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/payment-voucher.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div>&nbsp &nbsp &nbsp Payment &nbsp &nbsp &nbsp </div>
    </a>
    @endif
    @if(Auth::user()->hasPermission('Payable'))

    <a href="{{route("payable")}}" class="nav-item nav-link {{ $activeMenu == 'payable' ? 'active' : ' ' }}" role="tab"
        aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/payment.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> &nbsp &nbsp &nbsp Payable &nbsp &nbsp &nbsp </div>
    </a>
    @endif

</div>
@endif

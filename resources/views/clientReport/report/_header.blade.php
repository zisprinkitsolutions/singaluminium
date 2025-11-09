<div class="nav nav-tabs master-tab-section print-hideen" id="nav-tab" role="tablist">
    @if(Auth::user()->hasPermission('Accounting_Reports'))
    <a href="{{route("new-general-ledger")}}" class="nav-item nav-link {{ $activeMenu=='account_report' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/accounting-report.webp')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Accounting Reports</div>
    </a>
    @endif
    @if(Auth::user()->hasPermission('Account_Receivable'))
    {{-- <a href="{{route('balance-sheet')}}" class="nav-item nav-link {{ $activeMenu == 'financial_reports' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/payment-voucher-list.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div>Financial Reports</div>
    </a> --}}
    <a href="{{route('accounts.receivable',['type' => 'receivable'])}}" class="nav-item nav-link {{ $activeMenu == 'receivable' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/invoice.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Accounts Receivable </div>
    </a>
    @endif
    @if(Auth::user()->hasPermission('Account_Payable'))
    <a href="{{route('accounts.receivable',['type' => 'payable'])}}" class="nav-item nav-link {{ $activeMenu == 'payable' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/payable.jpg')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Accounts Payable </div>
    </a>
    @endif
    @if(Auth::user()->hasPermission('Petty_Cash_Report'))

    <a href="{{route("petty-cash-report")}}" class="nav-item nav-link {{ $activeMenu=='petty-cash' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/list.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Petty Cash</div>
    </a>
    @endif
    <a href="{{route("bank-account-report")}}" class="nav-item nav-link {{ $activeMenu=='bank-account' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/list.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Bank Report</div>
    </a>
    @if(Auth::user()->hasPermission('Daily_Summary'))
    <a href="{{route("daily-summary.report")}}" class="nav-item nav-link {{ $activeMenu=='daily-summary' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/stake-holder.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div>Daily Summary</div>
    </a>
    @endif
    @if(Auth::user()->hasPermission('Party_Transactions'))
    <a href="{{ route('party.index')}}" class="nav-item nav-link {{ $activeMenu=='party_transaction' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/accounting-report.webp')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Party Transaction</div>
    </a>
    @endif
    {{-- @if(Auth::user()->hasPermission('Accounting_Reports')) --}}
    <a href="{{ route('input-vat-report')}}" class="nav-item nav-link {{ $activeMenu=='vat_reports' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/accounting-report.webp')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> VAT Reports</div>
    </a>
    {{-- @endif --}}
    @if(Auth::user()->hasPermission('Stock_Report'))
    <a href="{{ route('stock-report')}}" class="nav-item nav-link {{ $activeMenu=='stock_report' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/accounting-report.webp')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Stock Report</div>
    </a>
    @endif

</div>





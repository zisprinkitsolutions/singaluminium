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
<div class="d-flex align-items-center pl-1 gap-2" style="margin-left: -5px">
    {{-- <a href="{{route("student-transaction-summary")}}" class="nav-item nav-link {{ $activeMenu == 'student-transaction-summery' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div>Student Transaction Summary</div>
    </a> --}}
    <a href="{{route("employees.index")}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'profile' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div>Employees Profile</div>
    </a>
    {{-- <a href="{{route('new-fee-structure')}}" class="nav-item nav-link {{ $activeMenu == 'fee_structure' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/fee-structure-icon.png')}}" class="img-fluid" width="45">
        </div>
        <div>Fee Structure</div>
    </a> --}}

    <a href="{{route('employee-history.index')}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'HISTORY' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">

        <div>Employee History </div>
    </a>
    <a href="{{ route('employee-document.index')}}" class="btn btn-outline-secondary nav-item nav-link {{ $activeMenu == 'document' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div>Employee Document</div>
    </a>
    {{-- <a href="{{ route('receipt-voucher.index') }}" class="nav-item nav-link {{ $activeMenu == 'recive_voucture' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div> Receipt Voucher  </div>
    </a>
    <a href="{{route("other-income")}}" class="nav-item nav-link {{ $activeMenu == 'other_collection' ? 'bg-secondary text-white' :'text-dark' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div>Other Collections</div>
    </a> --}}
</div>

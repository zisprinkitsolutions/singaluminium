<div class="nav nav-tabs master-tab-section" id="nav-tab" role="tablist">

    @if (Auth::user()->hasPermission('Receipt_Voucher'))
    <a href="{{route("receipt-voucher3")}}" class="nav-item nav-link {{ $activeMenu == 'receipt_voucher' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/payment-voucher.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div>Receipt Voucher</div>
    </a>
    @endif
    @if (Auth::user()->hasPermission('Receipt_List'))
    <a href="{{route("receipt-voucher-list-show")}}" class="nav-item nav-link {{ $activeMenu == 'receipts' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/payment-voucher-list.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> &nbsp  &nbsp &nbsp Receipt List &nbsp  &nbsp &nbsp </div>
    </a>
    @endif
    @if (Auth::user()->hasPermission('Receivable'))
    <a href="{{route("receivable")}}" class="nav-item nav-link {{ $activeMenu == 'receivable' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/list.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Receivable &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</div>
    </a>
  @endif
</div>





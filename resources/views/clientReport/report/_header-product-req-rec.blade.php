<div class="nav nav-tabs master-tab-section print-hideen" id="nav-tab" role="tablist">

    <a href="{{route("product-req-and-rec.index")}}" class="nav-item nav-link {{ $activeMenu=='index' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/purchaseexpense-entry.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Product Requisition</div>
    </a>
    {{-- <a href="{{route('product-req-and-rec.list')}}" class="nav-item nav-link {{ $activeMenu == 'list' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/list-view.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div>Product Receive</div>
    </a> --}}
</div>





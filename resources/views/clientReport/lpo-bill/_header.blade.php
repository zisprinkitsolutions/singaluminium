<div class="nav nav-tabs master-tab-section" id="nav-tab" role="tablist">
    <a href="{{route("lpo-bill-create")}}" class="nav-item nav-link {{ $activeMenu=='lpo_bill' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/purchaseexpense-entry.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp LPO  &nbsp &nbsp  &nbsp &nbsp &nbsp</div>
    </a>
    <a href="{{route("lpo-bill-list")}}" class="nav-item nav-link {{ $activeMenu=='lpo_bill_list' ? 'active' : ' ' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('icon/list.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp List  &nbsp &nbsp  &nbsp &nbsp &nbsp</div>
    </a>

</div>





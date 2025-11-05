<div class="nav nav-tabs master-tab-section" id="nav-tab" role="tablist">
    <a href="{{ route('division.index') }}" class="nav-item nav-link {{$activeMenu == 'base-table' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/supplier-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Base Table  </div>
    </a>
    <a href="{{route('grade-wise-salary-components.index')}}" class="nav-item nav-link {{ $activeMenu == 'grade-wise-salary-components' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false" id="parentProfileTab">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/supplier-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div class="text-dark"> Grade Wise Components </div>
    </a>
</div>

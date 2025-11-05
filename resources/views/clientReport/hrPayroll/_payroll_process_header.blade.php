
<div class="nav nav-tabs master-tab-section" id="nav-tab" role="tablist">
    {{-- <a href="{{ route('deduction-entry.index') }}" class="nav-item nav-link {{ $activeMenu == 'decuction-entry' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/balence-sheet-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Deduction Entry </div>
    </a> --}}
    <a href="{{ route('advance-salary.index') }}" class="nav-item nav-link {{$activeMenu == 'advance-salary' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/balence-sheet-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Advance Salary </div>
    </a>
    <a href="{{ route('salary-process.index') }}" class="nav-item nav-link {{$activeMenu == 'salary-process' ? 'active' : '' }}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/supplier-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div> Salary Process  </div>
    </a>
    <a href="{{route('pay-salary.index')}}" class="nav-item nav-link {{$activeMenu == 'pay-salary' ? 'active' : ' ' }} " role="tab" aria-controls="nav-contact" aria-selected="false" id="parentProfileTab">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/payment-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div class="text-dark"> Pay Salary </div>
    </a>
</div>

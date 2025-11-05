<div class="nav nav-tabs master-tab-section" id="nav-tab" role="tablist">
    @if ( Auth::user()->hasPermission('manage_profile'))
    <a href="{{route('role.index')}}" class="nav-item nav-link {{$activeManu=='role'?'active':''}}" role="tab" aria-controls="nav-contact" aria-selected="false" id="parentProfileTab">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/mapping-icon.png')}}" alt="" srcset="" class="img-fluid" width="55">
        </div>
        <div class="text-center"> Role </div>
    </a>
    @endif
     @if ( Auth::user()->hasPermission('user'))
    <a href="{{ route('user.index')}}" class="nav-item nav-link {{$activeManu=='user'?'active':''}}" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/account-heads.png')}}" alt="" srcset="" class="img-fluid" width="55">
        </div>
        <div class="text-center"> User</div>
    </a>
    @endif
    @if ( Auth::user()->hasPermission('settings'))
    <a href="{{ route('settings.index')}}" class="nav-item nav-link {{$activeManu=='settings'?'active':''}}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">
        <div class="master-icon text-cente">
            <img src="{{asset('assets/backend/app-assets/icon/document-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">
        </div>
        <div class="text-center">  Setting </div>
    </a>
    @endif
    <!--@if ( Auth::user()->id == 6)-->
    <!--<a href="{{ route('app-configs.index')}}" class="nav-item nav-link {{$activeManu=='app-configs'?'active':''}}" role="tab" aria-controls="nav-contact" aria-selected="false" id="mJournalAuthorizationSection">-->
    <!--    <div class="master-icon text-cente">-->
    <!--        <img src="{{asset('assets/backend/app-assets/icon/document-icon.png')}}" alt="" srcset="" class="img-fluid" width="50">-->
    <!--    </div>-->
    <!--    <div class="text-center">  App Config </div>-->
    <!--</a>-->
    <!--@endif-->
</div>

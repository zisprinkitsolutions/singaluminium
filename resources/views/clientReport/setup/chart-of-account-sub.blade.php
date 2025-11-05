<div class="d-flex align-items-center gap-2 p-2 ">
    @if (Auth::user()->hasPermission('Chart_of_Accounts'))
    <a href="{{route('new-chart-of-account')}}"
        class="btn btn-outline-secondary {{$activeMenu =='master-ac' ? 'text-white bg-secondary':''}} nav-item nav-link"
        style="margin-right:15px; border-radius: 5px;"
        role="tab" aria-controls="nav-contact" aria-selected="false" >
        <div> Master Account </div>
    </a>
    @endif
    @if (Auth::user()->hasPermission('Chart_of_Accounts'))
    <a href="{{route('new-account-head')}}"
        class="btn btn-outline-secondary {{$activeMenu =='account-head' ? 'text-white bg-secondary':''}}  nav-item nav-link"
        style="border-radius: 5px;"
        role="tab" aria-controls="nav-contact" aria-selected="false">
        <div> Account Head </div>
    </a>
    @endif
    @if (Auth::user()->hasPermission('Chart_of_Accounts'))
    <a href="{{route('new-account-sub-head')}}"
        class="btn btn-outline-secondary {{$activeMenu =='account-sub-head' ? 'text-white bg-secondary':''}}  nav-item nav-link ml-1"
        style="border-radius: 5px;"
        role="tab" aria-controls="nav-contact" aria-selected="false">
        <div> Account Sub-Head </div>
    </a>
    @endif
</div>

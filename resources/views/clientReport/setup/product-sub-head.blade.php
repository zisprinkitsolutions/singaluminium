<div class="d-flex align-items-center gap-2 p-2 ">
    
    <a href="{{ route('style.index')}}" class="btn btn-outline-secondary {{$activeMenu =='style' ? 'text-white bg-secondary':''}}  nav-item nav-link" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div> Style </div>
    </a>
    <a href="{{route('brand.index')}}" class="btn btn-outline-secondary {{$activeMenu =='color' ? 'text-white bg-secondary':''}}  nav-item nav-link ml-1" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div> Color </div>
    </a>
    <a href="{{route('group.index')}}" class="btn btn-outline-secondary {{$activeMenu =='size' ? 'text-white bg-secondary':''}}  nav-item nav-link ml-1" role="tab" aria-controls="nav-contact" aria-selected="false">
        <div> Size </div>
    </a>
    <a href="{{route('item-list.index')}}" class="btn btn-outline-secondary {{$activeMenu =='product' ? 'text-white bg-secondary':''}} nav-item nav-link ml-1" role="tab" aria-controls="nav-contact" aria-selected="false" style="margin-right:15px;">
        <div> Item List </div>
    </a>
</div>

@if(count($others) != 0)
    @foreach($others as $others)
    <div class="col-md-1 img" style="height: 60px; width: 60px;">
        {{-- <a href=""   class="close delete-img"></a> --}}
    {{-- <span data_target="{{ route('othersDelete', $others->id) }}" class="close delete-img" >&times;</span> --}}
        <span class="btn btn-warning invoice-item-delete" id="" data_target="{{ route('othersDelete',$others) }}"><i class="bx bx-trash"></i></span>

            @if ($others->extension == 'pdf')
                <a href="{{ asset('storage/upload/service-provider/'.$others->filename)}}" target="_blank">
                    
                    <img src="{{ asset('/icon/pdf-download-icon-2.png')}}" alt="jugyjugyt" style="height: 100%; width: 100%;">
                </a>
            @else
                <a href="{{ asset('storage/upload/service-provider/'.$others->filename)}}" target="_blank">
                    <img src="{{ asset('storage/upload/service-provider/'.$others->filename)}}" alt="" style="height: 100%; width: 100%;">
                </a>
            @endif     
    </div>
    @endforeach
@endif
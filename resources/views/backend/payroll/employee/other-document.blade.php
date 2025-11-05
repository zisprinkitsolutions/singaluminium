<table class="table table-sm w-100">
    <thead>
        <tr>
            <th>File Name</th>
            <th>File </th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($document_lists as $item)
            <tr id="{{'tr'.$item->id}}">
                <td>{{$item->name}}</td>
                <td>
                    <img src="{{ asset('storage/upload/other-documents/'.$item->filename)}}" id="_image_previewu" class="_image image-upload" alt="">
                </td>
                {{-- onclick="return confirm('Are you sure to delete this?')" --}}
                <td>
                    <a href="#" class="btn other-document-delete" title="Delete" style="padding-top: 1px; padding-bottom: 1px; height: 30px; width: 30px;" id="{{$item->id}}">
                        <img src="{{asset('assets/backend/app-assets/icon/delete-icon.png')}}" style=" height: 30px; width: 30px;">
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
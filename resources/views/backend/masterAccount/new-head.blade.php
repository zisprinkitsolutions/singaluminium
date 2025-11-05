
@foreach ($new_entries as $accHead)
<div class="rowStyle d-flex ml-2" id="{{'tr'.$accHead->id}}">
    <div style="line-height: 20px;">.....</div>
    <div>
        <li class="btn p-0 m-0" id="{{'update'.$accHead->id}}">{{ $accHead->fld_ac_code }}-{{ $accHead->fld_ac_head }}</li>
        <a href="#" class="editAccHead" data-target="{{ route('editAccHead', $accHead) }}" id="{{$accHead->id}}" title="Edit">
            <img src="{{ asset('/icon/edit-icon.png')}}" style=" height: 25px; width: 25px;">
        </a>
        <a href="#" class="text-danger account-head-delete" onclick="return confirm('Delete Account Head. Confirm?')" id="{{$accHead->id}}" title="Delete">
            <img src="{{ asset('/icon/delete-icon.png')}}" style=" height: 25px; width: 25px;">
        </a>
    </div>
</div>
@endforeach


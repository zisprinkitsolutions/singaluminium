@foreach ($masterDetails as $masterAcc1)
<tr class="trFontSize"  style="height: 40px;text-align:center;">
    <td>{{ $masterAcc1->mst_ac_code }}</td>
    <td>{{ $masterAcc1->mst_ac_head }}</td>
    <td>{{ $masterAcc1->mst_definition }}</td>
    <td>{{ $masterAcc1->mst_ac_type }}</td>
    <td>{{ $masterAcc1->vat_type }}</td>

    <td>
        <div style="margin-top: -12px;">
        <a href="{{ route('masterEdit',$masterAcc1) }}" class="btn" style="height: 30px; width: 30px;" title="Edit"><img src="{{ asset('/icon/edit-icon.png')}}" style=" height: 25px; width: 25px;"></a>
        <a href="{{ route('masterDelete',$masterAcc1) }}" onclick="return confirm('about to delete master account. Please, Confirm?')"  class="btn" style="height: 25px; width: 25px;padding: 0.467rem 0.8rem;" title="Delete"><img src="{{ asset('/icon/delete-icon.png')}}" style=" height: 25px; width: 25px; margin-left: -12px;"></a>
        </div>
    </td>
</tr>
@endforeach

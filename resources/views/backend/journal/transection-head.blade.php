<option value="">Select...</option>
@foreach ($acHeads as $item)

<option value="{{ $item->id }}"
    {{ isset($journalF) ? ($journalF->ac_head_id == $item->id ? 'selected' : '') : '' }}>
    {{ $item->fld_ac_head }}</option>
@endforeach

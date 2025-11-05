<option value="">Select...</option>
@foreach ($customers as $item)
<option value="{{ $item->id }}">{{ $item->pi_name }}</option>

@endforeach


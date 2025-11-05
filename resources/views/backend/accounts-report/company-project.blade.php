<option value="">Select...</option>
@foreach ($projects as $proj)
<option value="{{$proj->id}}"> Plot - {{$proj->new_project->plot }} / {{ $proj->project_name }} </option>

@endforeach

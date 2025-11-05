
<option value=""> Select </option>
@foreach ($tasks as $task)
    <option value="{{$task->id}}"> {{$task->name}} </option>
@endforeach

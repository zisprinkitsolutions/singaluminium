<select style="max-width: 200px !important;" name="project_task_id[]" class="form-control">
    <option value="">Select Task</option>
    @foreach ($task_lists as $key => $item)
        <option value="{{$item->id}}">{{$item->task_name}}</option>
    @endforeach
</select>
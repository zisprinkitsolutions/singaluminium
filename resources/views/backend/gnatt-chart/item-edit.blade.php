<form action="{{route('gnatt.chart.item.update', $item->id)}}" method="post">
    @csrf
    @method('put')

    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Create New Item </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Gantt Chart</label>
                        <select name="gnatt_chart_id" id="gnatt_chart_id" class="form-control" required>
                            <option value="">Select Gnatt Chart </option>
                            @foreach(App\GnattChart::all() as $task)
                                <option value="{{ $task->id }}" {{$task->id == $item->gnatt_chart_id ? 'selected' : ' '}}>{{ $task->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Item Name</label>
                        <input type="text" name="name" class="form-control" required value="{{$item->name}}">
                    </div>

                     <div class="form-group">
                        <label> Assign by</label>
                        <input type="text" name="assign_by" class="form-control" value="{{$item->assign_by}}" required>
                    </div>

                        <div class="form-group">
                        <label>	Priority </label>
                        <select name="priority" class="form-control"  required>
                            <option {{$item->priority == "Low" ? 'selected' : ' '}}> Low </option>
                            <option {{$item->priority == "Medium" ? 'selected' : ' '}}> Medium </option>
                            <option {{$item->priority == "High" ? 'selected' : ' '}}> High </option>
                        </select>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="text" name="start_date" class="form-control datepicker" required autocomplete="off" value="{{date('d/m/Y', strtotime($item->start_date))}}">
                    </div>

                    <div class="form-group">
                        <label>End Date</label>
                        <input type="text" name="end_date" class="form-control datepicker" required autocomplete="off" value="{{date('d/m/Y', strtotime($item->end_date))}}">
                    </div>

                    <div class="form-group">
                        <label>Progress (%)</label>
                        <input type="number" name="progress" class="form-control" min="0" max="100" value="0" required value="{{$item->progress}}">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Item </button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
    </div>
</form>

<form class="mt-1 chart-form" action="{{ route('gnatt.chart.update', $chart->id) }}" method="post"
    enctype="multipart/form-data">
    @csrf
    @method('put')
    <div class="row">

        <input type="hidden" value="{{$chart->quotation_id }}" name="quotation_id">
        <input type="hidden" value="{{$chart->job_project_id}}" name="project_id">
        <input type="hidden" value="{{$chart->party_id}}" name="customer_id">

        <div class="col-md-2">
            <div class="form-group">
                <label for=""> Gantt Chart Name </label>
                <input type="text" class="form-control" name="name" autocomplete="off" id="name" required
                    value="{{$chart->name}}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="">Estimated Start Date </label>
                <input type="text" name="start_date1"
                    class="date1 form-control @error('start_date1') is-invalid @enderror"
                    value="{{$chart->start_date ? date('d/m/Y', strtotime($chart->start_date)) : ''}}"
                    autocomplete="off" required>
                @error('start_date1')
                <p class="text-danger"> {{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="">Estimated End Date </label>
                <input type="text" name="end_date1"
                    class="date1 form-control @error('end_date1') is-invalid @enderror" autocomplete="off"
                    value="{{$chart->end_date ? date('d/m/Y', strtotime($chart->end_date)) : ''}}" required>
                @error('end_date1')
                <p class="text-danger"> {{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="auto-index repeater1 table table-sm" style="min-width: 800px;">
            <thead style="background-color:#34465b !important;">
                <tr>
                    <th class="text-left text-white" style="width:35%;">Task name</th>
                    <th class="text-center text-white" style="width:20%;">Assign To</th>
                    <th class="text-center text-white" style="width:13%;">Start Date</th>
                    <th class="text-center text-white" style="width:13%;">End Date</th>

                    <th class="text-center text-white" style="width:7%;"> Color </th>
                    <th class="text-center text-white" style="width:7%;">Priority</th>
                    <th class="text-right text-white" style="width:5%;">

                    </th>
                </tr>
            </thead>
            <tbody id="boq-body-edit">
                @foreach ($chart->items as $key => $item)
                <tr class="task-row" data-task-index="{{$key}}">
                    <td>
                        <input type="text" name="task_name[{{$key}}]" class="form-control task-name"
                            placeholder="Description" value="{{$item->name}}">
                    </td>
                    <td>
                        <select name="assign_to[{{$key}}]" class="form-control common-select230 assign_to text-center">
                            <option value=""> Select </option>
                            @foreach ($employees as $employee)
                            <option value="{{$employee->full_name}}" {{$employee->full_name == $item->assign_by ?
                                'selected' : ' '}}> {{$employee->code . ' ' . $employee->full_name . ' ' . $employee->contact_number}} </option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <input type="text" name="start_date[{{$key}}]" class="form-control text-center date start_date"
                            placeholder="Start Date"
                            value="{{$item->start_date ? date('d/m/Y', strtotime($item->start_date)) : ''}}"
                            autocomplete="off" required>
                    </td>

                    <td>
                        <input type="text" name="end_date[{{$key}}]" class="form-control text-center date end_date"
                            placeholder="End Date"
                            value="{{$item->end_date ? date('d/m/Y', strtotime($item->end_date)) : ''}}"
                            autocomplete="off" required>
                    </td>

                    <td>
                        <input type="color" name="color[{{$key}}]" class="form-control text-center" placeholder="color"
                            value="{{$item->color}}">
                    </td>

                    <td>
                        <select class="form-control text-center inputFieldHeight" name="priority[{{$key}}]">
                            <option value="low" {{$item->priority == 'low' ? 'selected' : ''}}> Low </option>
                            <option value="medium" {{$item->priority == 'medium' ? 'selected' : ''}}> Medium </option>
                            <option value="high" {{$item->priority == 'high' ? 'selected' : ''}}> High </option>
                        </select>
                    </td>


                    <td class="text-right">
                        <button type="button" class="removeTaskBtn bg-danger text-white" style="border: 1px solid #ddd;"
                            title="Remove Task">X</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="row d-flex align-items-center">
        <div class="col-12">
            <div class="form-group" style="width: 100%">
                <label for=""> Upload Documents </label>
                <input multiple class="form-control file_upload  @error('voucher_file') is-invalid @enderror"
                    type="file" name="voucher_file[]" style="padding: 0px !important; border:none"
                    accept="application/pdf,image/png,image/jpeg,application/msword">
                @error('voucher_file')
                <p class="text-danger"> {{ $message }}</p>
                @enderror

                <ul id="fileList" class="list-group mt-1"></ul>
            </div>
        </div>

        <div class="col-12">
            <div class="d-flex flex-row-reverse justify-content-center align-items-center ">
                <div class="print-hideen">
                    <a onclick="window.print()" class="btn btn-icon btn-secondary custom-action-btn" title="Print Now">
                        <i class="bx bx-printer"></i> Print
                    </a>
                </div>
                @if(Auth::user()->hasPermission('ProjectManagement_Approve'))
                    <button type="submit" class="save-btn btn btn-info custom-action-btn"> Save </button>
                @endif
            </div>
        </div>
    </div>
</form>

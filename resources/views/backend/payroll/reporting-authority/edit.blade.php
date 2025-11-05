
<div class="modal-header" style="padding:5px 10px;background:#364a60;">
    <h5 class="modal-title" id="exampleModalLabel"
        style="font-family:Cambria;font-size: 2rem;color:white;margin-left: 10px;"> Reporting Authority </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body" style="width:100%;max-width:700px;padding:15px;">
    <form action="{{ route('reporting.authority.update', $top_employee->id) }}" method="POST">
        @csrf
        @method('put')

        <div class="row">
            <div class="col-5 form-group">
                <label for=""> Parent Employee </label>
                <select name="parent_id" class="form-control common-select2" id="edit-top-employee">
                    <option value="{{$top_employee->id}}">  {{$top_employee->full_name . ' (' .$top_employee->code.')' }} </option>
                </select>
            </div>

            <div class="col-5 form-group">
                <label for=""> Date </label>
                <input type="text" class="datepicker form-control inputFieldHeight" id="edit-date" data-date="{{$date}}" name="work_date" value="{{date('d/m/Y', strtotime($date))}}" required>
            </div>
        </div>

        <table class="table table-bordered table-sm">
            <thead style="background:#34465b;color:#fff;">
                <tr>
                    <th style="width: 70%;color:#fff; text-align:left !important; padding:4px;"> Employee </th>
                    <th style="width: 15%;color:#fff; text-align:center !important; padding:4px;"> Code </th>
                    <th class="NoPrint" style="padding: 2px; text-align:center !important;"> <button type="button"
                            class="btn btn-sm btn-success addBtn"style="border: 1px solid green;
                        color: #fff; border-radius: 10px;padding: 5px;"
                            onclick="BtnAdd(this)"><i class="bx bx-plus" style="color: white;"></i></button>
                    </th>
                </tr>
            </thead>

            <tbody class="sale-item">
                @foreach ($top_employee->recursiveSubordinates as $key => $employee1)
                    <tr>
                        <td>
                            <select name="group-a[{{$key}}][child_id]" class="child_id form-control text-left common-select2" required>
                                <option value="{{$employee1->id}}" data-code="{{$employee1->code}}" selected> {{$employee1->full_name . ' (' .$employee1->code.')' }} </option>
                                @foreach ($employees as $employee)
                                    <option value="{{$employee->id}}" data-code="{{$employee->code}}"> {{$employee->full_name . ' (' .$employee->code.')' }} </option>
                                @endforeach
                            </select>
                        </td>


                        <td class="text-center"> {{$employee1->code}}  </td>

                        <td class="NoPrint text-center">
                            <button style="padding: 5px; margin: 4px;"
                                type="button"
                                data-url="{{route('reporting.authority.destroy', $employee1->id)}}"
                                class="btn btn-sm btn-danger"onclick="BtnDel(this)"><i class="bx bx-trash" style="color: white;"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button class="btn btn-sm btn-primary"> Save </button>
    </form>
</div>

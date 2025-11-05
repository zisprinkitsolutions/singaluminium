<div class="content-body">
    <form class="form form-vertical"  action="{{route('employee-leave.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <section id="basic-vertical-layouts">
            <div class="row match-height">
                <div class="col-md-12 col-12">
                    <div class="cardStyleChange">
                        <div class="card-body">
                            <div class="form-body">
                                <div class="row" style="margin-right: -5px;margin-left: -5px;">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label>Employee Name</label>
                                        <select name="employee_name" class="inputFieldHeight form-control" required onchange="employee()" id="select_id">
                                            <option value="">Select Name</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{$employee->id}}">{{$employee->first_name}} </option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label>Employee ID Number</label>
                                        <input type="text" name="employee_id" class="inputFieldHeight form-control" value="" id="employee_id" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label>From Date</label>
                                        <input type="text" name="from_date" placeholder="dd/mm/yyyy" required class="inputFieldHeight form-control datepicker form_date1 todate_calculation1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label>To Date</label>
                                        <input type="text" name="to_date" placeholder="dd/mm/yyyy" required class="inputFieldHeight form-control datepicker form_date2 todate_calculation1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label>Days leave</label>
                                        <input type="text" name="days_leave" required class="inputFieldHeight form-control days_leave1" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Leave Reason</label>
                                            <input type="text" name="leave_reason" placeholder="Leave Reason" required class="inputFieldHeight form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Document</label>
                                        <input type="file" class="form-control inputFieldHeight" name="files[]" multiple >
                                        @error('file')
                                        <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 d-flex justify-content-end">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn_create formButton mt-2 mb-2" title="Add" data-toggle="modal" data-target="#mNewEmployeeLeaveAdd">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img src="{{asset('assets/backend/app-assets/icon/save-icon.png')}}" width="25">
                                                    </div>
                                                    <div><span>Save</span></div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
</div>

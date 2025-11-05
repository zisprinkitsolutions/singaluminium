<div>
    <div class="modal fade"  id="employee-modal" tabindex="-1"
        role="dialog" aria-labelledby="employee-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="p-0" style="font-family:Cambria;font-size: 2rem;"><b>Add Leave</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <form action="{{ route('leave-policies.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="leave_type" class="col-form-label">Employee</label>
                                    <select name="emp_id" id="emp_id" class="form-control emp_id" required>
                                        <option value="">Select..</option>
                                        @foreach ($employees as $employee)
                                          <option value="{{$employee->id}}" data-emp_id="{{$employee->emp_id}} ">{{$employee->full_name}}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="leave_year_to" class="col-form-label">Start Date</label>
                                    <input
                                        type="text"
                                        name="start_date"
                                        id="start_date"
                                        autocomplete="off"
                                        placeholder="DD/MM/YYYY"
                                        class="start_date form-control datepicker_cus"
                                        required >
                                </div>
                                <div class="col-md-3">
                                    <label for="leave_year_to" class="col-form-label">End Date</label>
                                    <input
                                        type="text"
                                        name="end_date"
                                        id="end_date"
                                        autocomplete="off"
                                        placeholder="DD/MM/YYYY"
                                        class="end_date form-control datepicker_cus">

                                </div>

                                <div class="col-md-3 " >
                                    <label for="leave_day" class="col-form-label">Leave Day</label>
                                    <input type="number" readonly name="leave_day" autocomplete="off" id="leave_year_numbers" class="leave_day form-control">
                                </div>
                                <!-- File Upload Field -->
                                <div class="col-md-3">
                                    <label for="file" class="col-form-label">Upload File</label>
                                    <input type="file" name="file" id="file" class="form-control">
                                </div>

                                <!-- Description Field -->
                                <div class="col-md-12">
                                    <label for="description" class="col-form-label">Description</label>
                                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary submint-leave-policy" style="margin-top: 20px;">Save Leave Policy</button>
                                </div>
                            </div>
                        </form>



                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- **************** Employees edit modal ************************ --}}

    <div class="modal fade" id="employee-modal-edit" tabindex="-1"
        role="dialog" aria-labelledby="employee-modal-edit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg mt-5" role="document">
            <div class="modal-content" id="edit-modal">


            </div>
        </div>
    </div>
    {{-- **************** Employees  edit  modal end ************************ --}}
</div>

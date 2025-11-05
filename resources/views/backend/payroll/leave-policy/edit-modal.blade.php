<div class="modal-header">
    <h5 class="p-0" style="font-family:Cambria;font-size: 2rem;"><b>Leave info</b></h5>
    <div class="d-flex flex-row-reverse">
        <div class="mIconStyleChange">
            <a href="#" class="close btn-icon btn btn-danger mIconStyleChange212" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class='bx bx-x'></i></span>
            </a>
        </div>
        @if ($policy->status != 1)

        <div class="mIconStyleChange">
            <a href="{{route('leave-policies-delete',$policy->id)}}" onclick="return  confirm('Are Youe Sure To delete It ?')"   title="delete" class="btn btn-icon btn-danger "><i class='bx bx-trash'></i></a>
        </div>

        <div class="mIconStyleChange">
            <a href="{{ route('leave-policies-approve',$policy->id) }}" title="Approve" onclick="return confirm('Are Youe Sure To Approve It ?')" class="btn btn-icon btn-warning"><i class='bx bx-check'></i></a>
        </div>
        @endif
    </div>
</div>
<div class="modal-body">
    <div class="">
        <form action="{{ route('leave-policies.update', $policy->id ?? '') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($policy)) @method('PUT') @endif

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="emp_id" class="col-form-label">Employee</label>
                    <select name="emp_id" id="emp_id" class="form-control" required>
                        <option value="">Select..</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" @if(isset($policy) && $policy->emp_id == $employee->id) selected @endif>
                                {{ $employee->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Leave Type Field -->
                <div class="col-md-3">
                    <label for="leave_type" class="col-form-label">Leave Type</label>
                    <select name="leave_type" class="form-control leave_type_u" required>
                        <option value="">Select..</option>
                        <option value="Annual" @if(isset($policy) && $policy->leave_type == 'Annual') selected @endif>Annual Vacation</option>
                        <option value="Other" @if(isset($policy) && $policy->leave_type == 'Other') selected @endif>Other Leave</option>
                    </select>
                </div>

                <!-- Conditional Fields for Annual Leave -->
                <div class="col-md-3 annualLeaveFields_u" style="@if($policy->leave_type != 'Annual')display: ;" @endif>

                    <label for="leave_type" class="col-form-label">Origin</label>

                    <select name="origin" id="origin" class="form-control">
                        <option value="Local" @if(isset($policy) && $policy->origin == 'Local') selected @endif>Local</option>
                        <option value="Forenar" @if(isset($policy) && $policy->origin == 'Forenar') selected @endif>Forenar</option>
                    </select>
                </div>

                <div class="col-md-3 annualLeaveFields_u" style="@if($policy->leave_type != 'Annual')display: none; @endif">
                    <label for="leave_year_form" class="col-form-label">Yearly Vacation From</label>
                    <input type="text" name="leave_year_form" id="leave_year_form" class="form-control datepicker"
                        value="{{ date('d/m/Y', strtotime($policy->leave_year_form)) ??'' }}" placeholder="DD/MM/YYYY">
                </div>

                <div class="col-md-3 annualLeaveFields_u" style="@if($policy->leave_type != 'Annual')display: none; @endif">
                    <label for="leave_year_to" class="col-form-label">Yearly Vacation To</label>
                    <input type="text" name="leave_year_to" id="leave_year_to" class="form-control datepicker"
                        value="{{ date('d/m/Y', strtotime($policy->leave_year_to)) ?? '' }}" placeholder="DD/MM/YYYY">
                </div>

                <div class="col-md-3 annualLeaveFields_u" style="@if($policy->leave_type != 'Annual')display: none; @endif">
                    <label for="leave_year_numbers" class="col-form-label">Yearly Vacation Numbers</label>
                    <input type="number" step="0.01" name="leave_year_numbers" id="leave_year_numbers" class="form-control"
                        value="{{ $policy->leave_year_numbers ?? '' }}">
                </div>

                <div class="col-md-3 annualLeaveFields_u" style="@if($policy->leave_type != 'Annual')display: none; @endif">
                    <label for="yearly_paid_leave_number" class="col-form-label">Paid Leave Numbers</label>
                    <input type="number" step="0.01" name="yearly_paid_leave_number" id="yearly_paid_leave_number" class="form-control"
                        value="{{ $policy->yearly_paid_leave_number ?? '' }}">
                </div>

                <!-- Conditional Fields for Other Leave Types -->
                <div class="col-md-3 otherLeaveFields_u" style="@if($policy->leave_type != 'Other')display: none;@endif">
                    <label for="leave_date_form" class="col-form-label">Leave Date From</label>
                    <input type="text" name="leave_date_form" id="leave_date_form" class="form-control datepicker"
                        value="{{ date('d/m/Y', strtotime($policy->leave_date_form))  ?? '' }}" placeholder="DD/MM/YYYY">
                </div>

                <div class="col-md-3 otherLeaveFields_u" style="@if($policy->leave_type != 'Other')display: none;@endif">
                    <label for="leave_date_to" class="col-form-label">Leave Date To</label>
                    <input type="text" name="leave_date_to" id="leave_date_to" class="form-control datepicker"
                        value="{{ date('d/m/Y', strtotime($policy->leave_date_to))  ?? '' }}" placeholder="DD/MM/YYYY">
                </div>

                <div class="col-md-3 otherLeaveFields_u" style="@if($policy->leave_type != 'Other')display: none;@endif">
                    <label for="leave_day_numbers" class="col-form-label">Leave Day Numbers</label>
                    <input type="number" step="0.01" name="leave_day_numbers" id="leave_day_numbers" class="form-control"
                        value="{{ $policy->leave_day_numbers ?? '' }}">
                </div>

                <div class="col-md-3 otherLeaveFields_u" style="@if($policy->leave_type != 'Other')display: none;@endif">
                    <label for="paid_leave_day_numbers" class="col-form-label">Paid Leave Day Numbers</label>
                    <input type="number" step="0.01" name="paid_leave_day_numbers" id="paid_leave_day_numbers" class="form-control"
                        value="{{ $policy->paid_leave_day_numbers ?? '' }}">
                </div>

                <!-- File Upload Field -->
                <div class="col-md-3">
                    <label for="file" class="col-form-label">Upload File</label>
                    <input type="file" name="file" id="file" class="form-control">
                </div>

                <!-- Description Field -->
                <div class="col-md-12">
                    <label for="description" class="col-form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ $policy->description ?? '' }}</textarea>
                </div>

                <!-- Submit Button -->
                @if ($policy->status != 1)

                {{-- <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Save Leave Policy</button>
                </div> --}}
                @endif
            </div>
        </form>

    </div>
</div>

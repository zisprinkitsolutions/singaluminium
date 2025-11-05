<div class="modal-header" style="background: #475f7b; padding:2px 25px !important;">
    <h5 class="p-0" style="font-family:Cambria;font-size: 2rem; margin-bottom:0; color:#fff;"> Leave Application Info</h5>
    <div class="d-flex flex-row-reverse">
        <div class="mIconStyleChange">
            <a href="#" class="close btn-icon btn btn-danger mIconStyleChange212" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class='bx bx-x'></i></span>
            </a>
        </div>
        @if ($leave->status == 0 )

        <div class="mIconStyleChange">
            <a href="{{route('employee-leave-application-delete',$leave->id)}}" onclick="return  confirm('Are Youe Sure To delete It ?')"   title="delete" class="btn btn-icon btn-danger "><i class='bx bx-trash'></i></a>
        </div>
        @endif

        @if ($leave->status == 0)

        <div class="mIconStyleChange">
            <a href="{{route('employee-leave-application-reject',$leave->id)}}" onclick="return  confirm('Are Youe Sure To reject It ?')"   title="Reject" class="btn btn-icon btn-warning "><i class='bx bx-message-square-x'></i></a>
        </div>
        <div class="mIconStyleChange">
            <a href="{{ route('employee-leave-application-approve',$leave->id) }}" title="Approve" onclick="return confirm('Are Youe Sure To Approve It ?')" class="btn btn-icon btn-info"><i class='bx bx-check'></i></a>
        </div>
        @endif
    </div>
</div>
<div class="modal-body">
    <div class="">
        <form action="{{ route('employee-leave-application.update', $leave->id ?? '') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($leave)) @method('PUT') @endif

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="emp_id" class="col-form-label">Employee</label>
                    <input type="hidden" name="emp_id" value="{{$leave->employee_id}}" required>
                    <input type="text" class="form-control " readonly required value="{{$leave->emp ? $leave->emp->full_name : ''}}" required>
                </div>

                <div class="col-md-3 ">
                    <label for="leave_year_to" class="col-form-label">Start Date</label>
                    <input type="text" name="start_date" disabled  value="{{ date('d/m/Y', strtotime($leave->start_date ))}}" autocomplete="off" placeholder="DD/MM/YYY" class="start_dateu form-control datepicker">
                </div>
                <div class="col-md-3 ">
                    <label for="leave_year_to" class="col-form-label">End Date</label>
                    <input type="text" name="end_date" disabled  value="{{ date('d/m/Y', strtotime($leave->end_date ))}} " autocomplete="off" placeholder="DD/MM/YYY" class="end_dateu form-control datepicker">
                </div>
                <div class="col-md-3 " >
                    <label for="leave_day" class="col-form-label">Leave Day</label>
                    <input type="number" name="leave_day" value="{{$leave->leave_day}}" autocomplete="off"  class="form-control leave_dayu">
                </div>

                <!-- File Upload Field -->
                <div class="col-md-3">
                    <label for="file" class="col-form-label"> Upload File </label>
                    <input type="file" name="file" id="file" class="form-control">
                </div>

                <!-- Description Field -->
                <div class="col-md-12">
                    <label for="description" class="col-form-label"> Description </label>
                    <textarea name="description" id="description" class="form-control summernote_edit" rows="3">{!! $leave->description  !!}</textarea>
                </div>

                <!-- Submit Button -->
                @if ($leave->employee_id == Auth::user()->employee_id &&  $leave->status == 0  )
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Update </button>
                </div>
                @endif
            </div>
        </form>

    </div>
</div>

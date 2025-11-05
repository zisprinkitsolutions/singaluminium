<style>
    @media print{

        html, body {
            height:100%;
            overflow: hidden;
        }
    }
</style>
<section class="print-hideen border-bottom">
    <div class="row">
        <div class="col-6 pl-2">
            <h5 style="font-family:Cambria;font-size: 2.3rem;"><b>Edit Employee Leave</b> </h5>
        </div>
        <div class="col-6">
            <div class="d-flex flex-row-reverse">
                <div class="mIconStyleChange"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
            </div>
        </div>
    </div>
</section>
<div class="content-body">
    <form class="form form-vertical"  action="{{route('employee-leave-update', $leave->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <section id="basic-vertical-layouts">
            <div class="row match-height">
                <div class="col-md-12 col-12">
                    <div class="cardStyleChange">
                        <div class="card-body">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label>Employee Name</label>
                                       <input type="text"  value="{{$leave->employee_name}}" name="employee_name" class="inputFieldHeight form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label>Employee ID Number</label>
                                        <input type="text" name="employee_id" value="{{$leave->employee_id}}" class="inputFieldHeight form-control" value="" id="employee_id" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label>From Date</label>
                                        <input type="text" name="from_date" value="{{date('d/m/Y', strtotime($leave->from_date))}}" placeholder="dd/mm/yyyy" required class="inputFieldHeight form-control datepicker form_date11 todate_calculation11" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                        <label>To Date</label>
                                        <input type="text" name="to_date" value="{{date('d/m/Y', strtotime($leave->to_date))}}"  placeholder="dd/mm/yyyy" required class="inputFieldHeight form-control datepicker form_date22 todate_calculation11" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Days leave</label>
                                            <input type="number" name="days_leave"  class="inputFieldHeight form-control days_leave11" value="{{$leave->days_leave}}" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Leave Reason</label>
                                            <input type="text" name="leave_reason"  value="{{$leave->leave_reason}}" placeholder="Leave Reason"  class="inputFieldHeight form-control " required>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Documents</label>
                                        <input type="file" class="form-control inputFieldHeight" name="files[]" multiple >
                                        @error('file')
                                        <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row data">
                                                @foreach($others as $others)
                                                    <div class="col-md-1 img" >
                                                        {{-- <a href=""   class="close delete-img"></a> --}}
                                                {{-- <span data_target="{{ route('othersDelete', $others->id) }}" class="close delete-img" >&times;</span> --}}
                                                        <span class="btn btn-warning invoice-item-delete" id="" data_target="{{ route('employeeLeaveDocumentDelete',$others) }}"><i class="bx bx-trash"></i></span>

                                                            @if ($others->extension == 'pdf')
                                                                <a href="{{ asset('storage/upload/employee-leave/'.$others->filename)}}"  target="_blank">

                                                                    <img src="{{ asset('assets/backend/app-assets/icon/pdf-download-icon-2.png')}}" style="height:60px" class="img-fluid" alt="" >
                                                                </a>
                                                            @else
                                                                <a href="{{ asset('storage/upload/employee-leave/'.$others->filename)}}" target="_blank">
                                                                    <img src="{{ asset('storage/upload/employee-leave/'.$others->filename)}}" style="height:60px" class="img-fluid" alt="" >
                                                                </a>
                                                            @endif
                                                    </div>
                                                @endforeach
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex justify-content-end">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn_create formButton mt-2 mb-2" title="Update" >
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img src="{{asset('assets/backend/app-assets/icon/save-icon.png')}}" width="25">
                                                    </div>
                                                    <div><span>Update</span></div>
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
@include('backend.tab-file.modal-footer-info')

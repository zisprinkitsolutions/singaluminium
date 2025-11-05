
@php
    $emirates=array('Abu Dhabi','Ajman','Dubai','Fujairah','Ras Al Khaimah','Sharjah','Umm Al Quwain');
@endphp
<section class="print-hideen border-bottom">
    <div class="row">
        <div class="col-6 pl-2">
            <h5 style="font-family:Cambria;font-size: 2rem;"><b>View Employee Leave</b> </h5>
        </div>
        <div class="col-6">
            <div class="d-flex flex-row-reverse">
                <div class="mIconStyleChange"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>                            
            </div>
        </div>
    </div>
</section>
<form class="form form-vertical"  action="{{route('employee-leave.update', $leave->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <section id="basic-vertical-layouts">
        <div class="row match-height">
            <div class="col-md-12 col-12">
                <div class="cardStyleChange">
                    <div class="card-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                    <label>Employee Name</label>
                                   <input type="text" readonly value="{{$leave->employee_name}}" name="employee_name" class="inputFieldHeight form-control">
                                    </div>
                                </div>
            
                                <div class="col-sm-3">
                                    <div class="form-group">
                                    <label>Employee ID Number</label>
                                    <input type="text" name="employee_id" value="{{$leave->employee_id}}" class="inputFieldHeight form-control" value="" id="employee_id" readonly>   
                                    </div>
                                </div>
            
                                <div class="col-sm-3">
                                    <div class="form-group">
                                    <label>From Date</label>
                                    <input type="date" name="from_date" readonly value="{{$leave->from_date}}" placeholder="dd/mm/yyyy" required class="inputFieldHeight form-control">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                    <label>To Date</label>
                                    <input type="date" name="to_date" value="{{$leave->to_date}}"  placeholder="dd/mm/yyyy" required class="inputFieldHeight form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                    <label>Days leave</label>
                                    <input type="number" name="days_leave" required class="inputFieldHeight form-control" value="{{$leave->days_leave}}" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                    <label>Leave Reason</label>
                                    <input type="text" name="leave_reason" readonly value="{{$leave->leave_reason}}" placeholder="Leave Reason" required class="inputFieldHeight form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Documents</label>
                                    <div class="row data" style="margin-left: 10px !important">
                                            @foreach($others as $others)
                                                <div class="col-md-2" style="padding: 0px !important">
                                                        @if ($others->extension == 'pdf')
                                                            <a href="{{ asset('storage/upload/employee-leave/'.$others->filename)}}"  target="_blank">
                                                                
                                                                <img src="{{ asset('assets/backend/app-assets/icon/pdf-download-icon-2.png')}}" style="height:60px" class="w-100"  alt="" title="{{$others->name}}">
                                                            </a>
                                                        @else
                                                            <a href="{{ asset('storage/upload/employee-leave/'.$others->filename)}}" target="_blank">
                                                                <img src="{{ asset('storage/upload/employee-leave/'.$others->filename)}}" style="height:60px" class="w-100"  title="{{$others->name}}" alt="" >
                                                            </a>
                                                        @endif     
                                                </div>
                                            @endforeach
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
@include('backend.tab-file.modal-footer-info')
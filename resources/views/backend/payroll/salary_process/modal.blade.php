<div>
    @php
        use Carbon\CarbonPeriod;
    @endphp
    {{-- **************** Employees create modal start************************ --}}
    <div class="modal fade" style="width: 60%;left: 30%; top: -40px" id="employee-modal" tabindex="-1"
        role="dialog" aria-labelledby="employee-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="p-0" style="font-family:Cambria;font-size: 2rem;"><b>SALARY PROCESS</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
              
                </div>
                <div class="modal-body">
                    <div>
                        <form action="{{route('salary-process-start')}}" method="get" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <label style="margin-right: 10px" class="col-sm-1 col-form-label">MONTH</label>
                                <div class="col-sm-3">
                                    <select name="month" id="" class="inputFieldHeight form-control" required>
                                        <option value="">Select month</option>
                                        @foreach(CarbonPeriod::create(now()->startOfMonth(), '1 month', now()->addMonths(11)->startOfMonth()) as $date)
                                        <option value="{{ $date->format('F') }}" {{$date->format('F') == Date('F')?'selected':''}}>
                                                {{ $date->format('F') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="text" class="form-control" name="name" value="" placeholder="Type new Head"> --}}
                                    {{-- <input type="file"accept=".xlsx" name="file" class="form-control"> --}}
                                </div>
                                <label class="col-sm-1 col-form-label">YEAR</label>
                                <div class="col-sm-3">
                                    <select name="year" class="inputFieldHeight form-control" id="" required>
                                        @for($i=date('Y')-2;date('Y')+2>$i;$i++)
                                            <option value="{{$i}}" {{ $i == date('Y')?'selected':'' }}>{{$i}}</option>
                                        @endfor
                                    </select>
                                    {{-- <input type="text" class="form-control" name="name" value="" placeholder="Type new Head"> --}}
                                    {{-- <input type="file"accept=".xlsx" name="file" class="form-control"> --}}
                                </div>
                                <br>
                                <div class="col-sm-3">
                                    <button style="padding:4px 10px;" type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- **************** Employees create modal end ************************ --}}

    {{-- **************** Employees create modal start************************ --}}
    <div class="modal fade" style="width: 60%;left: 30%; top: -40px" id="confirm-modal" tabindex="-1"
        role="dialog" aria-labelledby="confirm-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                        <h5 class="modal-title">UPLOAD APPROVED DOCUMENT</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div>
                        <form action="{{route('salary-process-confirm')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-2">
                                <label style="margin-right: 10px;" class="col-sm-1 col-form-label">DOCUMENT: </label>
                                <div class="col-sm-6" style="margin-left: 41px">
                                    <input type="file" class="form-control inputFieldHeight" name="file" id="file" value="{{old('file')}}" class="inputFieldHeight form-control @error('m_emirates_id_upload') error @enderror" required>
                                </div>
                                <div class="col-sm-3">
                                    <button style="padding:4px 10px;" type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- **************** Employees create modal end ************************ --}}


    {{-- **************** Employees edit modal ************************ --}}

    <div class="modal fade" style="width: 100%;" id="employee-modal-edit" tabindex="-1"
        role="dialog" aria-labelledby="employee-modal-edit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mt-5 modal-lg" role="document">
            <div class="modal-content" id="edit-modal">


            </div>
        </div>
    </div>
    {{-- **************** Employees  edit  modal end ************************ --}}
</div>

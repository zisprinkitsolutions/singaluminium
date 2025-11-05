
    <div class="modal-header">
        <h5 class="p-0" style="font-family:Cambria;font-size: 2rem;"><b>Designation Edit</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="">
            <form action="{{route('department.update', $info->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-sm-4">
                        <label class="col-form-label">Department</label>
                        <select name="division_id" id="division_id" class="form-control common-select2" style="width: 100% !important" required>
                            <option value="">Select division</option>
                            @foreach ($divisions as $item)
                                <option value="{{$item->id}}" {{$info->division_id == $item->id?'selected':''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                        {{-- <input type="text" class="form-control" name="name" value="" placeholder="Type new Head"> --}}
                        {{-- <input type="file"accept=".xlsx" name="file" class="form-control"> --}}
                    </div>
                    <div class="col-sm-4">
                        <label class="col-form-label">Designation NAME</label>
                        <input type="text" class="form-control" name="name" value="{{$info->name}}" style="height: 35px" placeholder="Type new Head">
                        {{-- <input type="file"accept=".xlsx" name="file" class="form-control"> --}}
                    </div>
                    <div class="col-sm-3" style="padding-top: 30px;">
                        <button type="submit" class="btn btn-primary">Submit</button>

                    </div>
                </div>

            </form>
        </div>
    </div>

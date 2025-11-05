<div>

    {{-- **************** Employees create modal start************************ --}}

    <div class="modal fade" style="width: 60%;left: 30%; top: -40px" id="employee-modal" tabindex="-1"
        role="dialog" aria-labelledby="employee-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="p-0" style="font-family:Cambria;font-size: 2rem;"><b>Designation</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <form action="{{route('department.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <label class="col-form-label">Department</label>
                                    <select name="division_id" id="division_id" class="form-control common-select2" style="width: 100% !important" required>
                                        <option value="">Select Department</option>
                                        @foreach ($divisions as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="text" class="form-control" name="name" value="" placeholder="Type new Head"> --}}
                                    {{-- <input type="file"accept=".xlsx" name="file" class="form-control"> --}}
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-form-label">Designation NAME</label>
                                    <input type="text" class="form-control" name="name" value="" style="height: 35px" placeholder="Type new Head">
                                    {{-- <input type="file"accept=".xlsx" name="file" class="form-control"> --}}
                                </div>
                                <div class="col-sm-3" style="margin-top: -23px;">
                                    <label class="col-form-label"> </label>
                                    <button style="margin-top:30px; height: 40px" type="submit" class="btn btn-primary">Submit</button>
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

    <div class="modal fade" style="width: 60%;left: 20%; top: -40px; overflow-y: hidden;" id="employee-modal-edit" tabindex="-1"
        role="dialog" aria-labelledby="employee-modal-edit" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mt-5" role="document">
            <div class="modal-content" id="edit-modal">


            </div>
        </div>
    </div>
    {{-- **************** Employees  edit  modal end ************************ --}}
</div>

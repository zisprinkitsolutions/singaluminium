<div class="modal-header master-account-head" style="padding: 5px 20px; background:#364a60;" >
    <h5 class="modal-title text-white"> Account Head Details </h5>
    <div class="d-flex align-items-center" style="gap: 5px;">
        <button type="button"
            style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;"
            class="btn btn-danger btn-sm"
            data-dismiss="modal" aria-label="Close">
            <i class='bx bx-x'></i>
        </button>
    </div>
</div>

<div class="col-12 mt-1 master-account-body">
    <div class="card  p-1">
        <div>
            @isset($masterAcc)
                <form action="{{ route('accHeahDetailsPost', $masterAcc) }}" id="formSubmit_new_head" method="POST">
            @else
                <form action="{{ route('accHeahDetailsPost') }}" method="POST" id="formSubmit_new_head">
            @endisset
                @csrf
                <div class="row match-height ">
                    <div class="col-md-4">
                        <label>A/C Code</label>
                        <input type="text" id="MA_Code" class="form-control" name="MA_Code"
                               value="{{ isset($masterAcc) ? $masterAcc->mst_ac_code . '-' . $subCode : '' }}"
                               placeholder="Master A/C Code" readonly disabled>
                    </div>

                    <div class="col-md-4">
                        <label>Master A/C Head</label>
                        <input type="text" name="fld_Master_ACHead" class="form-control"
                               value="{{ isset($masterAcc) ? $masterAcc->mst_ac_head : '' }}" readonly disabled>
                    </div>

                    <div class="col-md-4">
                        <label>Definition</label>
                        <input type="text" name="fld_Defination" class="form-control"
                               value="{{ isset($masterAcc) ? $masterAcc->mst_definition : '' }}" readonly disabled>
                    </div>

                    {{-- Dynamic A/C Head Fields --}}
                    <div class="col-md-12 mt-2" id="ac-head-container">
                        <div class="row ac-head-group">

                            <div class="col-md-11">
                                <label> A/C Head </label>
                                <div class="d-flex align-items-center">
                                    <input type="text" class="form-control inputFieldHeight" name="fld_ac_head[]" placeholder="A/C Head" required>

                                    <div class="form-check" style="margin-left: 10px">
                                        <input class="form-check-input" type="checkbox" value="1" id="add-unit_1" name="is_unit[]">
                                        <label class="form-check-label" for="add-unit_1">
                                            Add Unit
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-1 d-flex align-items-end p-0 master-account-button">
                                <button type="button" class="btn btn-success btn-sm add-head"> + </button>
                            </div>

                        </div>
                    </div>

                    @if(Auth::user()->hasPermission('Setup_Create'))
                    <div class="col-md-12 mt-1 text-left">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript to handle dynamic fields --}}


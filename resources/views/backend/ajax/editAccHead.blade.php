<div class="modal-header" style="padding: 5px 20px; background:#364a60;" >
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

<div class="col-12 mt-1">
    <div class="card  p-1">
        <div class="card-body" style="padding: 10px 15px !important;">
                <form action="{{ route('accHeahEditPost', $account_head) }}" method="POST" id="formSubmit">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>A/C Code</label>

                                <input type="text" id="MA_Code" class="form-control" name="MA_Code"
                                    value="{{ isset($account_head) ? $account_head->fld_ac_code : '' }}"
                                    placeholder="Master A/C Code" readonly disabled>

                                @error('MA_Code')
                                    <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>A/C Head</label>

                                    <input type="text" id="fld_ac_head" class="form-control" name="fld_ac_head" value="{{ isset($account_head) ? $account_head->fld_ac_head : '' }}"

                                        placeholder="A/C Head" required>

                                @error('fld_ac_head')
                                    <div class="btn btn-sm btn-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Master A/C Head</label>

                                <input type="text" name="fld_Master_ACHead" class="form-control"
                                value="{{ isset($account_head) ? $account_head->fld_ms_ac_head : '' }}" id=""
                                    readonly disabled>
                            </div>
                            <div class="form-group">
                                <label>Definition</label>

                                <input type="text" name="fld_Defination" class="form-control"
                                value="{{ isset($account_head) ? $account_head->fld_definition : '' }}" id=""
                                    readonly disabled>
                            </div>

                        </div>
                        @if(Auth::user()->hasPermission('Setup_Edit'))
                        <div class="col-12 d-flex justify-content-start align-items-center">
                            <div class="form-check" style="margin-right: 10px">
                                <input class="form-check-input" type="checkbox" value="1" id="add-unit_1" name="is_unit" {{ isset($account_head) && $account_head->is_unit ? 'checked' : '' }}>
                                <label class="form-check-label" for="add-unit_1">
                                    Add Unit
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary" style="margin-left: 0.2rem !important;">Update</button>
                                    {{-- <button type="reset" class="btn btn-light-secondary">Reset</button> --}}
                        </div>
                        @endif
                    </div>
                </form>
            </div>
    </div>
</div>

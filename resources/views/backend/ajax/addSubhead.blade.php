
<style>
    .remove-subhead {
        margin-left: 5px !important;
    }
</style>
<div class="modal-header" style="padding: 5px 20px; background:#364a60;" >
    <h5 class="modal-title text-white"> Account Sub Head Details </h5>
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
        <div class="">
           <form action="{{ route('accountSubheadPost', $acc) }}" id="formSubmit_new_head" method="POST">
                @csrf
                <div class="row match-height" id="subhead-wrapper">
                    <div class="col-md-6 subhead-group d-flex align-items-end gap-1">
                        <div class="w-100">
                            <label>Subhead 1</label>
                            <input type="text" name="name[]" class="form-control inputFieldHeight" required>
                        </div>
                        <!-- Empty space to keep alignment -->
                    </div>

                    @if ($acc->is_unit)
                    <div class="col-md-6 subhead-group d-flex align-items-end gap-1">
                        <div class="w-100">
                            <label>Unit</label>
                            <select name="unit_id[]" id="unit_id" class="form-control inputFieldHeight" required>
                                <option value="">Select...</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="mt-2">
                    <button type="button" id="add-subhead" class="btn btn-secondary ml-1" style="width:120px;" data-unit="{{$acc->is_unit}}">Add More</button>
                    <button type="submit" class="btn btn-primary float-left" style="width:120px;">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

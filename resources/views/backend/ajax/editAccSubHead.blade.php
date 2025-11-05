<section class="print-hideen border-bottom" style="background: #364a60;">
    <div class="d-flex justify-content-between align-items-center px-2 " style="padding: 5px;">

        <!-- Title -->
        <h4 class="mb-0" style="font-family: Cambria; font-size: 1.3rem; color: white;">
            Sub Head Edit
        </h4>

        <!-- Close Button -->
        <a href="#" class="btn btn-danger btn-icon close" data-dismiss="modal" aria-label="Close">
            <i class='bx bx-x' aria-hidden="true"></i>
        </a>
    </div>
</section>

<div class="col-12">
    <form action="{{ route('sub-head-post-update', $account_head->id) }}" method="POST" id="updateFormSubmit">
        @csrf
        <div class="row" style="margin-right: 0px;margin-left:0px;">
            <div class="col-md-12 mt-2">
                <select name="head_id" id="head" class="form-control common-select2 inputFieldHeight" required style="width: 100% !important">
                    <option value="">Select Head...</option>
                    @foreach ($accountHeads as $head)
                    <option value="{{$head->id}}" {{$head->id==$account_head->account_head_id?'selected':''}}>{{ $head->fld_ac_code }} - {{ $head->fld_ac_head }}</option>
                    @endforeach
                </select>
            </div>

            <div class="{{$account_head->unit_id ? 'col-md-6' : 'col-md-12'}} mt-2">
                <input type="text" name="name" class="form-control inputFieldHeight" required id="" value="{{$account_head->name}}">
            </div>

            @if($account_head->unit_id)
            <div class="col-md-6 mt-2">
                <select name="unit_id" id="unit_id" class="form-control common-select2 inputFieldHeight" required style="width: 100% !important">
                    <option value="">Unit...</option>
                    @foreach ($units as $unit)
                    <option value="{{$unit->id}}" {{$unit->id==$account_head->unit_id?'selected':''}}>{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            @if(Auth::user()->hasPermission('Setup_Edit'))
            <div class="col-md-12 mt-2 mb-1 text-left">
                <button type="submit" class="btn btn-sm btn-info">Update</button>
            </div>
            @endif
        </div>
    </form>
</div>

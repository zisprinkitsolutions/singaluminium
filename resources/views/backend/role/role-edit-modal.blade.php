<section class="print-hideen border-bottom">
    <div class="d-flex flex-row-reverse">
        <div class="mIconStyleChange"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal"
                aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
        {{-- <div class="mIconStyleChange"><a href="#" class="btn btn-icon btn-success"><i class="bx bx-edit"></i></a></div>
        <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-secondary"><i class='bx bx-printer'></i></a></div>
        <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-primary"><i class='bx bxs-file-pdf'></i></a></div>
        <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
    </div>
</section>

@include('backend.tab-file.modal-header-info')
<section id="basic-vertical-layouts">
    <div class="cardStyleChange">
        <form class="form form-vertical" action="{{ route('role.update', $role->id) }}" method="POST"
            enctype="multipart/form-data" style="padding: 25px;">
            @csrf
            @method('PUT')
            <!-- Basic Vertical form layout section start -->
            <section id="basic-vertical-layouts">
                <div class="row match-height">
                    <div class="col-md-12 col-12">
                        <div class="card-body">
                            <div class="form-body">
                                <h4>UPDATE ROLES</h4>
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label>Role Name</label>
                                            <input type="text" id="contact-info-vertical"
                                                class="inputFieldHeight form-control @error('role_name') error @enderror"
                                                name="role_name" value="{{ $role->name ?? old('role_name') }}"
                                                placeholder="Family Name">
                                            @error('role_name')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label></label>
                                            <ul class="list-unstyled">
                                                <li class="d-inline-block mr-2">
                                                    <fieldset>
                                                        <div class="checkbox">
                                                            <input type="checkbox" class="checkbox-input"
                                                                id="edit_all">
                                                            <label for="edit_all"> Select All </label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    @error('permissions')
                                        <div class="col-md-12 col-12">
                                            <span class="error">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Basic Vertical form layout section end -->

            <section id="basic-checkbox">
                <div class="row">
                    @forelse ($modules as $module)
                        <div class="col-12">
                            <div class="card-body">
                                <h4 class="border-bottom">{{ $module->name }}</h4>
                                <ul class="list-unstyled">
                                    @foreach ($module->permissions as $key => $permission)
                                        <li class="d-inline-block mr-2">
                                            <fieldset>
                                                <div class="checkbox">
                                                    <input type="checkbox" class="checkbox-input"
                                                        id="permission-id-{{ $permission->id }}"
                                                        value="{{ $permission->id }}" name="permissions[]"
                                                        @if (isset($role)) @foreach ($role->permissions as $rPermission)
                                                {{ $permission->id == $rPermission->id ? 'checked' : '' }}
                                                @endforeach @endif>
                                                    <label
                                                        for="permission-id-{{ $permission->id }}">{{ $permission->name }}</label>
                                                </div>
                                            </fieldset>
                                        </li>
                                    @endforeach
                                </ul>
                                @if ($loop->last)
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn mr-1 btn-primary formButton"
                                            data-repeater-delete="" title="Add" data-repeater-create="">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}"
                                                        alt="" srcset="" width="25">
                                                </div>
                                                <div><span>Save</span></div>
                                            </div>
                                        </button>
                                        <button type="reset" class="btn btn-light-secondary formButton"
                                            title="Form Reset">
                                            <div class="d-flex">
                                                <div class="formRefreshIcon">
                                                    <img src="{{ asset('assets/backend/app-assets/icon/refresh-icon.png') }}"
                                                        alt="" srcset="" class="img-fluid" width="25">
                                                </div>
                                                <div><span> Reset</span></div>
                                            </div>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty

                    @endforelse

                </div>
            </section>
        </form>
    </div>
</section>

@push('js')
@endpush


@include('backend.tab-file.modal-footer-info')

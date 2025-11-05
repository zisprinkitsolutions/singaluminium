<style>
    .commonSelect2Style span {
        width: 100% !important;
    }

    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        display: none;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        display: none;
    }
</style>
<section class="print-hideen border-bottom" style="background-color: #34465b;">
    <div class="d-flex flex-row-reverse">
        <div class="mIconStyleChange"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal"
                aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
        {{-- <div class="mIconStyleChange"><a href="#" class="btn btn-icon btn-success"><i class="bx bx-edit"></i></a></div>
        <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-secondary"><i class='bx bx-printer'></i></a></div>
        <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-primary"><i class='bx bxs-file-pdf'></i></a></div>
        <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
    </div>
</section>
@php
    $emirates = ['Abu Dhabi', 'Ajman', 'Dubai', 'Fujairah', 'Ras Al Khaimah', 'Sharjah', 'Umm Al Quwain'];
@endphp
@include('backend.tab-file.modal-header-info')
@include('backend.tab-file.style')
<section id="basic-vertical-layouts">
    <div class="cardStyleChange">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            <form class="form form-vertical" action="{{ route('user.update', $user->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <!-- Basic Vertical form layout section start -->
                <section id="basic-vertical-layouts">
                    <div class="row match-height">
                        <div class="col-md-12 col-12">
                            <div class="card-body">
                                <div class="form-body">
                                    <h4>User Information</h4>
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>User Name</label>
                                                <input  type="text" id="first-name-vertical"
                                                    class="inputFieldHeight form-control @error('name') error @enderror"
                                                    name="name" value="{{ $user->name }}" placeholder="User Name" required>
                                                @error('name')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>Email Address</label>
                                                <input readonly type="email" id="contact-info-vertical"
                                                    class="inputFieldHeight form-control @error('email') error @enderror"
                                                    name="email" value="{{ $user->email }}" placeholder="Email ID" required>
                                                @error('email')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>New Password</label>
                                                <input type="text" id="contact-info-vertical"
                                                    class="inputFieldHeight form-control @error('email') error @enderror"
                                                    name="password" value="" placeholder="New Password">
                                                @error('email')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="role_id">Role</label>
                                                <select id="role_id"
                                                    class="inputFieldHeight form-control @error('role_id') error @enderror"
                                                    name="role_id" required>
                                                    <option value=""> Select Section</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}"
                                                            {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                            {{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('role_id')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                  <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="is_creator">Creator</label>
                                        <select id="is_creator" class="inputFieldHeight form-control @error('is_creator') error @enderror"
                                            name="is_creator" required>
                                            <option value="1" {{ old('is_creator', $user->is_creator) == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ old('is_creator', $user->is_creator) == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                        @error('is_creator')
                                        <span class="error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="is_authorizer">Authorizer</label>
                                        <select id="is_authorizer" class="inputFieldHeight form-control @error('is_authorizer') error @enderror"
                                            name="is_authorizer" required>
                                            <option value="1" {{ old('is_authorizer', $user->is_authorizer) == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ old('is_authorizer', $user->is_authorizer) == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                        @error('is_authorizer')
                                        <span class="error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label for="is_approver">Approver</label>
                                        <select id="is_approver" class="inputFieldHeight form-control @error('is_approver') error @enderror"
                                            name="is_approver" required>
                                            <option value="1" {{ old('is_approver', $user->is_approver) == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ old('is_approver', $user->is_approver) == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                        @error('is_approver')
                                        <span class="error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label>Max approve amount</label>
                                        <input type="number" step="any" id="max_approve_amount"
                                            class="inputFieldHeight form-control @error('max_approve_amount') error @enderror" name="max_approve_amount"
                                            value="{{$user->max_approve_amount}}">
                                        @error('max_approve_amount')
                                        <span class="error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                        <div class="col-md-1 d-flex justify-content-end mt-2 mb-2">
                                            <button type="tutton" class="btn btn-primary formButton" title="Save"
                                                id="SearchButton">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}"
                                                            width="20">
                                                    </div>
                                                    <div><span> Save</span></div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Basic Vertical form layout section end -->
            </form>
        </div>
    </div>
</section>
@include('backend.tab-file.modal-footer-info')

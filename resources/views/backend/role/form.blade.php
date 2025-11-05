@extends('layouts.backend.app')

@section('content')
    @include('backend.tab-file.style')
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.administration._header', ['activeManu' => 'role'])

                <div class="tab-content p-2 active" style="background: azure;">
                    <div class="tab-pane active">

                        <!-- Bordered table start -->
                        <div class="row" id="table-bordered">
                            <div class="col-12">
                                <form class="form form-vertical"
                                    action="{{ isset($role) ? route('role.update', $role->id) : route('role.store') }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @if (isset($role))
                                        @method('PUT')
                                    @endif

                                    <!-- Basic Vertical form layout section start -->
                                    <section id="basic-vertical-layouts">
                                        <div class="row match-height">
                                            <div class="col-md-12 col-12">
                                                <div class="card card-primary">
                                                    <div class="card-header">
                                                        <h4 class="card-title">MANAGE ROLES</h4>
                                                    </div>

                                                    <div class="card-body">
                                                        {{-- <form class="form form-vertical"> --}}
                                                        <div class="form-body">
                                                            <div class="row">
                                                                <div class="col-md-4 col-12">
                                                                    <div class="form-group">
                                                                        <label>Role Name</label>
                                                                        <input type="text" id="contact-info-vertical"
                                                                            class="form-control @error('role_name') error @enderror"
                                                                            name="role_name"
                                                                            value="{{ $role->name ?? old('role_name') }}"
                                                                            placeholder="Family Name">
                                                                        @error('role_name')
                                                                            <span class="error">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4 col-12">
                                                                    <div class="form-group">
                                                                        <label></label>
                                                                        <ul class="list-unstyled mb-0">
                                                                            <li class="d-inline-block mr-2 mb-1">
                                                                                <fieldset>
                                                                                    <div class="checkbox">
                                                                                        <input type="checkbox"
                                                                                            class="checkbox-input"
                                                                                            id="select-all">
                                                                                        <label for="select-all"> Select All
                                                                                        </label>
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
                                                        {{-- </form> --}}
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
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h4 class="card-title">{{ $module->name }}</h4>
                                                        </div>
                                                        <div class="card-body">
                                                            <ul class="list-unstyled mb-0">
                                                                @foreach ($module->permissions as $key => $permission)
                                                                    <li class="d-inline-block mr-2 mb-1">
                                                                        <fieldset>
                                                                            <div class="checkbox">
                                                                                <input type="checkbox"
                                                                                    class="checkbox-input"
                                                                                    id="permission-id-{{ $permission->id }}"
                                                                                    value="{{ $permission->id }}"
                                                                                    name="permissions[]"
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
                                                                    <button type="submit" class="btn btn-primary mr-1">
                                                                        @isset($role)
                                                                            Update
                                                                        @else
                                                                            Create
                                                                        @endisset
                                                                    </button>
                                                                    <button type="reset"
                                                                        class="btn btn-light-secondary">Reset</button>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- col-12 end -->
                                            @empty
                                            @endforelse

                                        </div>
                                    </section>

                                </form>
                            </div>
                        </div>
                        <!-- Bordered table end -->
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END: Content-->
    <div class="modal fade bd-example-modal-lg" id="newRoleAdd" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                @include('backend.role.role-create-modal')
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="roleEditModal" tabindex="-1" rrole="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="roleEditDetails">

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).on("click", ".roleEdit", function(e) {
            e.preventDefault();

            var id = $(this).attr('id');
            //alert(id);
            $.ajax({
                url: "{{ URL('role-edit-modal') }}",
                method: "POST",
                cache: false,
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                success: function(response) {
                    document.getElementById("roleEditDetails").innerHTML = response;
                    $('#roleEditModal').modal('show');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Page Script
            // $('#edit_all').click(function (event) {
            $(document).on("click", "#edit_all", function(e) {

                if (this.checked) {
                    // Iterate each checkbox
                    $(':checkbox').each(function() {
                        this.checked = true;
                    });
                } else {
                    $(':checkbox').each(function() {
                        this.checked = false;
                    });
                }
            });


        });
    </script>
@endpush

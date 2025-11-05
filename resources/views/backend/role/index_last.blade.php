@extends('layouts.backend.app')

@section('content')
@include('backend.tab-file.style')
<style>
    .table .thead-light th {
        color:#F2F4F4 ;
        background-color: #34465b;
        border-color: #DFE3E7;
    }
    tr:nth-child(even) {
        background-color: #c8d6e357;
    }
</style>
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.administration._header',['activeManu'=>'role'])

                <div class="tab-content bg-white p-2 active">
                    <div class="tab-pane active">

                        <!-- Bordered table start -->
                        <div class="row" id="table-bordered">
                            <div class="col-12">
                                <div class="card">

                                    <form method="get">
                                        <div class="row pl-1">
                                            <div class="col-6">
                                                <div class="form-group">Role's List

                                                    <input type="search" style="margin-top: 10px"
                                                        class="form-control inputFieldHeight" name="search"
                                                        value="{{ old('search') }}"
                                                        placeholder="Search by Role Name Or User"
                                                        title="Search by Role Name Or User ">
                                                </div>
                                            </div>

                                            <div class="col-md-3" style="padding-left: 0px;margin-top:31px">
                                                {{-- <button class="btn btn-secondary" style="padding:8px 30px 11px 30px; float: right;"><i class='bx bx-search'></i></button> --}}
                                                <button type="submit" class="btn  formButton mSearchingBotton"
                                                    title="Searching"
                                                    style="padding-left: 8px;
                                                    padding-right: 8px; background:#1A233A !important">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{ asset('assets/backend/app-assets/icon/searching-icon.png') }}"
                                                                alt="" srcset="" width="20">
                                                        </div>
                                                        <div><span> Search</span></div>
                                                    </div>
                                                </button>
                                                <button type="button" class="btn btn-primary btn_create formButton" title="Add" data-toggle="modal"
                                                    data-target="#newRoleAdd">
                                                    <div class="d-flex">
                                                        <div class="formSaveIcon">
                                                            <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" width="25">
                                                        </div>
                                                        <div><span>Add New</span></div>
                                                    </div>
                                                    </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                    <div class="card-body">
                                        <!-- table bordered -->
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0 table-sm">
                                                <thead  class="thead-light">
                                                    <tr class="text-center" style="height: 40px;">
                                                        <th>Sl No</th>
                                                        <th>Name</th>
                                                        <th>Permissions</th>
                                                        <th>Updated at</th>
                                                        <th style="width:80px;">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $sl=0;
                                                    @endphp
                                                    @foreach ($roles as $role)

                                                    <tr class="text-center" style="font-size: 12px;">
                                                        <td>{{ ++$sl }}</td>
                                                        <td>{{ $role->name }}</td>


                                                        <td>
                                                            @if ($role->permissions->count() > 0)
                                                                <span class="badge badge-info">{{ $role->permissions->count() }}</span>
                                                            @else
                                                                <span class="badge badge-danger">No permission found :(</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $role->updated_at->diffForHumans() }}</td>
                                                        <td>
                                                            <a href="{{route('role.edit', $role->id)}}" class="btn btn-icon btn-success" style="padding: 2px 5px;"><i class="bx bx-edit"></i></a>
                                                            <!-- <a href="#" class="btn roleEdit" title="Edit" id="{{$role->id}}" style="padding-top: 1px; padding-bottom: 1px; height: 30px; width: 30px;"><img src="{{asset('assets/backend/app-assets/icon/edit-icon.png')}}" alt="" srcset="" style=" height: 30px; width: 30px;"></a> -->
                                                        </td>
                                                    </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Bordered table end -->
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- END: Content-->
    <div class="modal fade bd-example-modal-lg" id="newRoleAdd" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            @include('backend.role.role-create-modal')
          </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="roleEditModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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

            var id= $(this).attr('id');
            //alert(id);
            $.ajax({
                url: "{{URL('role-edit-modal')}}",
                method: "POST",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response){
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
                    $(':checkbox').each(function () {
                        this.checked = true;
                    });
                } else {
                    $(':checkbox').each(function () {
                        this.checked = false;
                    });
                }
            });


        });
    </script>
@endpush

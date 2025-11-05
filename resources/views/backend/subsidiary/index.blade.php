@extends('layouts.backend.app')

@section('content')
<style>
    .table .thead-light th {
        color: #F2F4F4;
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
           @include('clientReport.setup._header',['activeMenu' => 'subsidiary'])
            <div class="tab-content bg-white p-2 active">
                <div class="tab-pane active">

                    <!-- Bordered table start -->
                    <div class="row" id="table-bordered">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <button type="button" class="btn btn-primary btn_create formButton" title="Add"
                                        data-toggle="modal" data-target="#newSettingAdd">
                                        <div class="d-flex">
                                            <div class="formSaveIcon">
                                                <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}"
                                                    width="25">
                                            </div>
                                            <div><span>Add New</span></div>
                                        </div>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <!-- table bordered -->
                                    <div class="table-responsive" style="padding-left: 18px;padding-right: 18px;">
                                        <table class="table table-hover mb-0 table-sm table-bordered table-striped" style="max-width: 1000px;">
                                            <thead class="thead-light">
                                                <tr class="text-center" style="height: 40px;">
                                                    <th>Sl No</th>
                                                    <th>Comapny Name</th>
                                                    <th style="text-align:right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($subsidiarys as $index => $subsidiary)
                                                <tr class="text-center" style="font-size: 12px;">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $subsidiary->company_name }}</td>
                                                    <td class="text-right action-buttons">
                                                        <!-- View Button -->
                                                        <a href="javascript:void(0);" class="btn btn-sm btn-info actionModal" data-action="view"
                                                            data-id="{{ $subsidiary->id }}" data-url="{{ route('subsidiary.show', $subsidiary->id) }}" title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        <!-- Edit Button -->
                                                        <a href="javascript:void(0);" class="btn btn-sm btn-warning actionModal" data-action="edit"
                                                            data-id="{{ $subsidiary->id }}" data-url="{{ route('subsidiary.edit', $subsidiary->id) }}" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        <!-- Delete Button -->
                                                        {{-- <a href="javascript:void(0);" class="btn btn-sm btn-danger actionModal" data-action="delete"
                                                            data-id="{{ $subsidiary->id }}" data-url="{{ route('subsidiary.destroy', $subsidiary->id) }}" title="Delete">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a> --}}
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

<div class="modal fade bd-example-modal-lg" id="newSettingAdd" tabindex="-1" rrole="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            @include('backend.subsidiary.create-modal')
        </div>
    </div>
</div>
<div class="modal fade" id="subsidiaryModal" tabindex="-1" aria-labelledby="subsidiaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-body" id="subsidiaryModalBody" style="padding: 0px !important;">
                <!-- AJAX content will load here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')

<script>
    function disableSubmitButton(form) {
        const button = form.querySelector('#submitBtn');
        button.disabled = true;
        button.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...`;
    }
   $(document).on('click', '.actionModal', function () {
        const action = $(this).data('action');
        const url = $(this).data('url');
        const id = $(this).data('id');

        // Optional: Confirm before delete
        if (action === 'delete') {
            if (!confirm('Are you sure you want to delete this item?')) return;

            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function (response) {
                    alert('Deleted successfully!');
                    location.reload(); // Or use DataTables redraw
                },
                error: function (xhr) {
                    alert('Something went wrong!');
                }
            });
        } else {
            // For View/Edit
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    $('#subsidiaryModalBody').html(data);
                    $('#subsidiaryModal').modal('show');
                },
                error: function () {
                    alert('Failed to load data.');
                }
            });
        }
    });
</script>
@endpush

@extends('layouts.backend.app')
@push('css')
    @include('layouts.backend.partial.style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
@endpush
@php
    $key=0;
@endphp
@section('content')
    <div class="app-content content print-hideen">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                 @include('clientReport.setup._header',['activeMenu' => 'house-type'])
                <div class="tab-content bg-white">
                    <div id="journaCreation" class="tab-pane active">
                        <section class="p-1" id="widgets-Statistics">
                            <div class="row mb-1">
                                <div class="col-md-8 text-left">

                                    @if(Auth::user()->hasPermission('ProjectManagement_Create'))
                                    <a class="btn btn-xs btn-primary  formButton" title="Create BOQ Sample" data-toggle="modal" data-target="#house-type">
                                        <div class="d-flex">
                                            <div class="formSaveIcon">
                                                <img src="{{asset('/icon/trial-balence-icon.png')}}" width="25">
                                            </div>
                                            <div><span> Create House Type </span></div>
                                        </div>
                                    </a>
                                    @endif
                                </div>
                            </div>

                            <div class="cardStyleChange" style="width:100%; max-width:670px;">
                                <table class="table mb-0 table-sm table-hover">
                                    <thead  class="thead-light">
                                        <tr style="height: 40px;">
                                            <th style="width:15%;" class="text-left"> House Type </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($house_types as $key => $type)
                                            <tr class="edit" data-id="{{$type->id}}" data-name="{{$type->name}}" style="height: 40px; border-bottom: 1px solid #e0e0e0;">
                                                <td style="text-align:left;"> {{ $type->name}} </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="house-type">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header" style="background: #364a60; padding:10px !important;">
                <h4 class="modal-title text-white"> House Type </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" style="padding:10px !important;">
                <form action="{{route('house.type.store')}}" method="POST">
                    @csrf
                    <input type="hidden" name="house_type_id" id="house_type_id">
                    <div class="form group">
                        <label for=""> House Type </label>
                        <input type="text" class="form-control" id="house_type_name" name="house_type" value="{{old('house_type')}}" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-1"> Save </button>
                </form>
            </div>
            </div>
        </div>
        </div>

<div class="modal fade bd-example-modal-lg" id="profitCenterPrintModal" tabindex="-1" rrole="dialog"
   aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false"  style="z-index: 1080;">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div id="profitCenterPrintShow">

        </div>
      </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).on('click', '.edit', function(){
        var id = $(this).data('id');
        var name = $(this).data('name');
        $('#house_type_name').val(name);
        $('#house_type_id').val(id);

        $('#house-type').modal('show');
    });
</script>
@endpush

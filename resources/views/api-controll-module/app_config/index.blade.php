@extends('layouts.backend.app')

@section('content')
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
                @include('clientReport.administration._header',['activeManu'=>'app-configs'])

                <div class="tab-content bg-white p-2 active">
                    <div class="tab-pane active">

                        <!-- Bordered table start -->
                        <div class="row" id="table-bordered">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">App Config</h4>
                                        <button type="button" class="btn btn-primary btn_create formButton" title="Add" data-toggle="modal" data-target="#newSettingAdd">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{asset('assets/backend/app-assets/icon/add-icon.png')}}" width="25">
                                                </div>
                                                <div><span>Add New</span></div>
                                            </div>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <!-- table bordered -->
                                        <div class="table-responsive" style="padding-left: 18px;padding-right: 18px;">
                                            <table class="table table-hover mb-0 table-sm">
                                                <thead  class="thead-light">
                                                    <tr class="text-center" style="height: 40px;">
                                                        <th>Sl No</th>
                                                        <th>Config Name</th>
                                                        <th>Config Value</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($settings as $setting)

                                                    <tr class="text-center" style="font-size: 12px;">
                                                        <td>{{ $setting->id }} </td>
                                                        <td>{{ $setting->config_name }} </td>
                                                        <td>{{ $setting->config_value }} </td>

                                                        <td>
                                                            <a href="#" class="settingEdit" title="Edit" id="{{$setting->id}}" style="padding-top: 1px; padding-bottom: 1px; height: 25px; width: 25px;"><img src="{{asset('assets/backend/app-assets/icon/edit-icon.png')}}" alt="" srcset="" style=" height: 25px; width: 25px;"></a>
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

    <div class="modal fade bd-example-modal-lg" id="newSettingAdd" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="width: 60%;">
          <div class="modal-content">
            @include('api-controll-module.app_config.setting-create-modal')
          </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="settingEditModal" tabindex="-1" rrole="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="width: 60%;">
          <div class="modal-content">
            <div id="settingEditDetails">
            </div>
          </div>
        </div>
    </div>
@endsection

@push('js')

    <script>
        $(document).on("click", ".settingEdit", function(e) {
            e.preventDefault();

            var id= $(this).attr('id');
            //alert(id);
            $.ajax({
                url: "{{route('app-config-edit-modal')}}",
                method: "POST",
                cache: false,
                data:{
                    _token:'{{ csrf_token() }}',
                    id:id,
                },
                success: function(response){
                    document.getElementById("settingEditDetails").innerHTML = response;
                    $('#settingEditModal').modal('show');
                }
            });
        });
    </script>
@endpush

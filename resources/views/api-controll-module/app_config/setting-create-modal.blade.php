<style>
    .commonSelect2Style span{
        width: 100% !important;
    }
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b{
        display: none;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b{
        display: none;
    }
</style>
<section class="print-hideen border-bottom" style="background-color: #34465b;">
    <div class="row pl-1 pr-1">
        <div class="col-md-6"><h4 class="card-title" style="font-family:Cambria;font-size: 2rem;color:#fff;padding-top: 2px;">Create Config Settings</h4></div>
        <div class="col-md-6">
            <div class="d-flex flex-row-reverse">
                <div class="mIconStyleChange"><a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a></div>
                {{-- <div class="mIconStyleChange"><a href="#" class="btn btn-icon btn-success"><i class="bx bx-edit"></i></a></div>
                <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-secondary"><i class='bx bx-printer'></i></a></div>
                <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-primary"><i class='bx bxs-file-pdf'></i></a></div>
                <div class="mIconStyleChange"><a href="#"  onclick="window.print();" class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
            </div>
        </div>
    </div>

</section>
@include('backend.tab-file.modal-header-info')
@include('backend.tab-file.style')
<section id="basic-vertical-layouts">
    <div class="cardStyleChange">
        <section id="basic-vertical-layouts">
            <form class="form form-vertical"  method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row match-height">
                    <div class="col-md-12 col-12">
                        <!-- <div class="card-header">

                        </div> -->
                        <div class="card-body">
                            <div class="form-body">
                                <div class="row pl-1 pr-1">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="config-name">Config Name</label>
                                            <input type="text" id="config-name" class="inputFieldHeight form-control @error('config_name') error @enderror" name="config_name" value="{{ isset($edit_setting) ? $edit_setting->config_name : old('config_name')}}" placeholder="Config Name" required>
                                            @error('config_name')
                                            <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-12">
                                        <div class="form-group">
                                            <label for="config-value">Config Value</label>
                                            <input type="text" id="config-value" class="inputFieldHeight form-control @error('config_value') error @enderror" name="config_value" value="{{ isset($edit_setting) ? $edit_setting->config_value : old('config_value')}}" placeholder="Config Value" required>
                                            @error('config_value')
                                            <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-1 d-flex justify-content-end mt-2 mb-2">
                                        <button type="submit" class="btn btn-primary formButton" title="Save" id="SearchButton">
                                            <div class="d-flex">
                                                <div class="formSaveIcon">
                                                    <img src="{{asset('assets/backend/app-assets/icon/save-icon.png')}}" width="20">
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
            </form>
        </section>
    </div>
</section>

@include('backend.tab-file.modal-footer-info')

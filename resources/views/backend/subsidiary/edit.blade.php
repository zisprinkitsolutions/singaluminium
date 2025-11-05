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
    .btn-icon-text img {
        margin-right: 2px; /* gap কমিয়ে দিলাম */
    }
</style>
<section class="print-hideen border-bottom" style="background-color: #34465b;">
    <div class="row pl-1 pr-1 align-items-center"> <!-- vertical center এর জন্য -->
        <!-- Left side -->
        <div class="col-md-6 d-flex align-items-center">
            <h4 class="card-title mb-0"
                style="font-family: Cambria; font-size: 2rem; color:#fff;">
                Subsidiary Edit
            </h4>
        </div>
        <!-- Right side -->
        <div class="col-md-6">
            <div class="d-flex justify-content-end align-items-center">
                <div class="mIconStyleChange">
                    <a href="#" class="close btn-icon btn btn-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class='bx bx-x'></i></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@include('backend.tab-file.modal-header-info')
@include('backend.tab-file.style')
<section id="basic-vertical-layouts">
    <div class="cardStyleChange">
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

                @if(session('msg'))
                        <div class="alert alert-warning ">
                            {!! session('msg') !!}
                        </div>
                @endif
            </div>
        </div>
        <div class="content-body">
            <form class="form form-vertical"
                action="{{ isset($subsidiary) ? route('subsidiary.update', $subsidiary->id) : route('subsidiary.store') }}"
                method="POST" enctype="multipart/form-data" onsubmit="disableSubmitButton(this)">

                @csrf
                @if(isset($subsidiary))
                    @method('PUT')
                @endif

                <div class="row match-height">
                    <div class="col-12">
                        <div class="card-body">
                            <div class="form-body">
                                <div class="row text-left p-1">

                                    @php
                                    $fields = [
                                        'company_name' => 'Company Name',
                                        'company_address' => 'Company Address',
                                        'company_email' => 'Company Email',
                                        'company_mobile' => 'Company Mobile',
                                        'trn_no' => 'TRN No',
                                    ];
                                    @endphp

                                    @foreach($fields as $name => $label)
                                        <div class="col-md-4 col-12 mb-1">
                                            <div class="form-group">
                                                <label style="padding-bottom: 5px;" for="{{ $name }}">
                                                    <strong>{{ $label }} :</strong>
                                                </label>
                                                <input type="text" id="{{ $name }}"
                                                    class="form-control {{--inputFieldHeight--}} @error($name) error @enderror"
                                                    name="{{ $name }}"
                                                    value="{{ old($name, isset($subsidiary) ? $subsidiary->$name : '') }}"
                                                    placeholder="{{ $label }}" required>
                                                @error($name)
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Letterhead Image -->
                                    <div class="col-md-4 col-12 mb-1">
                                        <div class="form-group">
                                            <label style="padding-bottom: 5px;" for="letterhead_image">
                                                <strong>Letterhead Image :</strong>
                                            </label>
                                            <input type="file" accept="image/*" id="letterhead_image"
                                                class="form-control {{--inputFieldHeight--}} @error('image') error @enderror"
                                                name="image">
                                            @error('image')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12 d-flex justify-content-center ">
                                        <button type="submit" id="submitBtn" class="btn btn-primary formButton"
                                                style="padding: 4px 12px;" title="{{ isset($subsidiary) ? 'Update' : 'Save' }}">
                                            <div class="d-flex align-items-center btn-icon-text">
                                                <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}" width="20" >
                                                <span>{{ isset($subsidiary) ? 'Update' : 'Save' }}</span>
                                            </div>
                                        </button>
                                    </div>

                                </div> <!-- row -->
                            </div> <!-- form-body -->
                        </div> <!-- card-body -->
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@include('backend.tab-file.modal-footer-info')

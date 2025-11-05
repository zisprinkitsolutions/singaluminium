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
    <div class="row pl-1 pr-1 align-items-center">
        <div class="col-md-6 d-flex align-items-center text-left">
            <h4 class="card-title mb-0" style="font-family:Cambria;font-size: 2rem;color:#fff;">Add New Subsidiary</h4>
        </div>
        <div class="col-md-6">
            {{-- <div class="d-flex flex-row-reverse"> --}}
            <div class="d-flex justify-content-end align-items-center">
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
            {{-- <form class="form form-vertical" action="{{ route('subsidiary.store') }}" method="POST" enctype="multipart/form-data"
                onsubmit="disableSubmitButton(this)">
                @csrf
                <div class="row match-height">
                    <div class="col-md-12 col-12">
                        <div class="card-body">
                            <div class="form-body">
                                <div class="row pl-1 pr-1">

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
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="{{ $name }}">{{ $label }}</label>
                                            <input type="text" id="{{ $name }}"
                                                class="inputFieldHeight form-control @error($name) error @enderror"
                                                name="{{ $name }}"
                                                value="{{ old($name) }}"
                                                placeholder="{{ $label }}" required>
                                            @error($name)
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="col-md-3 col-12">
                                        <div class="form-group">
                                            <label for="Letterhead Image">Letterhead Image</label>
                                            <input type="file" accept="image/*" id="letterhead_image" class="inputFieldHeight form-control @error($name) error @enderror"
                                                name="image" value="{{ old('image') }}" required>
                                            @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12 d-flex justify-content-end mt-2 mb-2">
                                        <button type="submit" id="submitBtn" class="btn btn-primary formButton"
                                            style="padding: 4px 12px;" title="Save">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}" width="20"
                                                    class="mr-1">
                                                <span>Save</span>
                                            </div>
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form> --}}

            <form class="form form-vertical" action="{{ route('subsidiary.store') }}" method="POST" enctype="multipart/form-data" onsubmit="disableSubmitButton(this)">
    @csrf
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
                                <label for="{{ $name }}" style="padding-bottom: 5px;">
                                    <strong>{{ $label }} :</strong>
                                </label>
                                <input type="text" id="{{ $name }}"
                                    class="form-control @error($name) is-invalid @enderror"
                                    name="{{ $name }}"
                                    value="{{ old($name) }}"
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
                                <label for="letterhead_image" style="padding-bottom: 5px;">
                                    <strong>Letterhead Image :</strong>
                                </label>
                                <input type="file" accept="image/*" id="letterhead_image"
                                    class="form-control @error('image') is-invalid @enderror"
                                    name="image" value="{{ old('image') }}" required>
                                @error('image')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 d-flex justify-content-center ">
                            <button type="submit" id="submitBtn" class="btn btn-primary formButton" style="padding: 6px 14px;" title="Save">
                                <div class="d-flex align-items-center btn-icon-text">
                                    <img src="{{ asset('assets/backend/app-assets/icon/save-icon.png') }}" width="20" class="">
                                    <span>Save</span>
                                </div>
                            </button>
                        </div>

                    </div> <!-- row -->
                </div> <!-- form-body -->
            </div> <!-- card-body -->
        </div>
    </div>
</form>

        </section>
    </div>
</section>

@include('backend.tab-file.modal-footer-info')

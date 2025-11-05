<div class="modal-header" style="padding: 5px 15px;background:#364a60;">
                <h5 class="modal-title" id="exampleModalLabel" style="font-family:Cambria;font-size: 2rem;color:white;">Party Details</h5>
                <div class="d-flex align-items-center">
                    <button type="button" class="project-btn bg-danger text-white" data-dismiss="modal" aria-label="Close" style="padding: 5px 10px; border:none; border-radius:5px" data-bs-toggle="tooltip" data-bs-placement="right" title="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{-- @include('alerts.alerts') --}}
                </div>
            </div>
            <div class="modal-body" style="padding: 15px 15px;">
                <section id="widgets-Statistics" class="mr-1 ml-1 mb-1">
                    <div class="row">

                        <div class="col-12 party-info-form">
                            @isset($partyInfo)
                                <form action="{{ route('partyInfoUpdate', $partyInfo) }}" method="POST" onreset="select2_change()">
                            @else
                                <form action="{{ route('partyInfoPost') }}" method="POST" onreset="select2_change()">
                            @endisset
                                @csrf
                                <div class="cardStyleChange pt-1">
                                    <div class="form-row text-left">
                                        <!-- Row 1 -->
                                        <div class="col-md-4 mb-2">
                                            <label style="padding-bottom: 5px"><strong>Party Code :</strong></label>
                                            <input type="text" class="form-control form-control" value="{{ isset($cc)? $cc: (isset($partyInfo)?$partyInfo->pi_code:'') }}" disabled readonly>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label style="padding-bottom: 5px"><strong>Party Info Name :</strong></label>
                                            <input type="text" name="pi_name" class="form-control form-control" value="{{ isset($partyInfo) ? $partyInfo->pi_name : '' }}" placeholder="Party Info Name" required>
                                            @error('pi_name')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label style="padding-bottom: 5px"><strong>Party Type :</strong></label>
                                            <select name="pi_type" class="form-control form-control" required>
                                                <option value="">Select...</option>
                                                @foreach ($costTypes as $item)
                                                    <option value="{{ $item->title }}" {{ isset($partyInfo) ? ($partyInfo->pi_type == $item->title ? 'selected' : '') : '' }}>
                                                        {{ $item->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('pi_type')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Row 2 -->
                                        <div class="col-md-4 mb-2">
                                            <label style="padding-bottom: 5px"><strong>@if(!empty($currency->licence_name)) {{$currency->licence_name}} @endif No :</strong></label>
                                            <input type="number" name="trn_no" class="form-control form-control" value="{{ isset($partyInfo) ? $partyInfo->trn_no : '' }}" placeholder="TRN Number">
                                            @error('trn_no')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label style="padding-bottom: 5px"><strong>Address :</strong></label>
                                            <input type="text" name="address" class="form-control form-control" value="{{ isset($partyInfo) ? $partyInfo->address : '' }}" placeholder="Address">
                                            @error('address')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label style="padding-bottom: 5px"><strong>Contact Person :</strong></label>
                                            <input type="text" name="con_person" class="form-control form-control" value="{{ isset($partyInfo) ? $partyInfo->con_person : '' }}" placeholder="Contact Person">
                                            @error('con_person')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Row 3 -->
                                        <div class="col-md-4 mb-2">
                                            <label style="padding-bottom: 5px"><strong>Mobile Phone No :</strong></label>
                                            <input type="number" name="con_no" class="form-control form-control" value="{{ isset($partyInfo) ? $partyInfo->con_no : '' }}" placeholder="Mobile No">
                                            @error('con_no')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label style="padding-bottom: 5px"><strong>Phone No :</strong></label>
                                            <input type="number" name="phone_no" class="form-control form-control" value="{{ isset($partyInfo) ? $partyInfo->phone_no : '' }}" placeholder="Phone No">
                                            @error('phone_no')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label style="padding-bottom: 5px"><strong>Email :</strong></label>
                                            <input type="text" name="email" class="form-control form-control" value="{{ isset($partyInfo) ? $partyInfo->email : '' }}" placeholder="Email">
                                            @error('email')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- Buttons -->
                                        <div class="col-12 d-flex justify-content-center ">
                                            {{-- <button type="submit" class="btn btn-primary btn-sm mr-2">
                                                <i class="bx bx-save"></i> Save
                                            </button>
                                            <button type="reset" class="btn btn-secondary btn-sm" onclick="select2_change()">
                                                <i class="bx bx-refresh"></i> Reset
                                            </button> --}}
                                            @isset($partyInfo)
                                            {{-- <button class="btn btn-info party-info-form-btn formButton" data_target="{{ route('partyInfoForm') }}" id="profitCenterButton"><img src="{{ asset('icon/add-icon.png')}}" alt="" srcset="" class="image-fluid" width="25">New</button> --}}
                                            @endisset
                                            <button type="submit" class="btn btn-primary formButton" style="margin-right: 5px;" title="Form Save">
                                                <div class="d-flex">
                                                    <div class="formSaveIcon">
                                                        <img  src="{{asset('icon/save-icon.png')}}" alt="" srcset="" class="img-fluid" width="25">
                                                    </div>
                                                    <div><span> Save</span></div>
                                                </div>
                                            </button>
                                            <button type="reset" class="btn btn-light-secondary formButton" title="Form Reset" onclick="select2_change()">
                                                <div class="d-flex">
                                                    <div class="formRefreshIcon">
                                                        <img  src="{{asset('icon/refresh-icon.png')}}" alt="" srcset="" class="img-fluid" width="25">
                                                    </div>
                                                    <div><span> Reset</span></div>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </section>
            </div>

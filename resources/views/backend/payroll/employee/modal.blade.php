@php
$emirates=array('Abu Dhabi','Ajman','Dubai','Fujairah','Ras Al Khaimah','Sharjah','Umm Al Quwain');
$languages= array('Bangla','English','Urdu','Arabic','Hindi');
$employee_roles= array('Principle','Teacher', 'Admin', 'Accounts Executive',
'Librarian','Driver','Clerk','Cleaner','Secretary','Accountant','Trainer');
@endphp
<div>
    <style>
        .h5,
        h5 {
            margin-top: 25px;
            margin-top: -0.5rem;
            font-size: 14px;
            font-size: 1.6rem;
            padding-bottom: 5px;
        }

        input[type=text],
        select,
        textarea {
            height: 2.5rem;
            font-size: 16px;
        }

        .form-control {
            height: calc(1.1em + 0.94rem + 3.7px);
        }

        input[type=file] {
            height: 2.5rem;
            font-size: 16px;
        }

        input[type=date] {
            height: 2.5rem;
            font-size: 16px;
        }

        input {
            height: 2.5rem;
            font-size: 16px;
        }

        .habib {
            padding-right: 0px !important;
            padding-left: 2px !important;
        }
        .image-upload {
            height: 100px;
            width: 100%;
            display: inherit;
        }
        .col-fixed{
            max-width:160px;
            flex: 0 0 auto;
        }
        .responsive-box {
            width: 100%;
        }

        .keyboard-control-btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 122, 204, 0.6);
            border: 2px solid #007ACC !important;
            background-color: #fff !important;
            color: #007ACC !important;
        }

        @media (min-width: 768px) {
            .responsive-box {
                min-width: 400px;
                max-width: calc(100% - 160px - 20px); /* প্রোফাইল ছবি বাদে বাকি স্পেস */
                flex: 1;

            }
        }

    </style>

    {{-- **************** Employees create modal start************************ --}}


    <div class="modal fade bd-example-modal-lg" id="employee-modal" style="width: 100%;" tabindex="-1" rrole="dialog"
        aria-labelledby="employee-modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="padding-right: 0px !important">
            <div class="modal-content ">
                <section class="print-hideen border-bottom" style="padding: 0px 32px; background-color:#475f7b;">
                    <div class="d-flex justify-content-between align-item-center">

                            <h5 style="font-family:Cambria;font-size: 1.6rem; margin-top:8px;margin-left:13px;color:#ececec !important;"><b>Employee Profile</b> </h5>

                            <div class="d-flex flex-row-reverse align-item-center">

                                <div class="mIconStyleChange" style="padding:0;"><a href="#"
                                        class="close btn-icon btn btn-danger mIconStyleChange212" data-dismiss="modal"
                                        aria-label="Close"><span aria-hidden="true"><i class='bx bx-x'></i></span></a>
                                </div>
                                {{-- <div class="mIconStyleChange"><a href="#" onclick="window.print();"
                                        class="btn btn-icon btn-secondary"><i class='bx bx-printer'></i></a></div>
                                <div class="mIconStyleChange"><a href="#" onclick="window.print();"
                                        class="btn btn-icon btn-primary"><i class='bx bxs-file-pdf'></i></a></div>
                                <div class="mIconStyleChange"><a href="#" onclick="window.print();"
                                        class="btn btn-icon btn-light"><i class='bx bxs-virus'></i></a></div> --}}
                            </div>
                    </div>
                </section>
                <div class="modal-body" id="modal-body">
                    <div class="card-body" style="padding: 0px;">
                        <ul class="nav nav-tabs nav-tabs1" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="personal-info-tab" data-toggle="tab" href="#personal-info" role="tab" aria-controls="personal-info" aria-selected="false">Personal Information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="contact-info-tab" data-toggle="tab" href="#contact-info" role="tab" aria-controls="contact-info" aria-selected="false"> Emergency Contact</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="job-info-tab" data-toggle="tab" href="#job-info" role="tab" aria-controls="job-info" aria-selected="false">Job Information</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" id="bank-account-tab" data-toggle="tab" href="#bank-account" role="tab" aria-controls="bank-account" aria-selected="false">Bank Account</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link " id="policy-info-tab" data-toggle="tab" href="#policy" role="tab" aria-controls="policy" aria-selected="true">Policy</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link " id="files-info-tab" data-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="true">Document</a>
                            </li>

                        </ul>

                        <div class="tab-content tab-content1" id="myTabContent" data-next="contact-info-tab">

                            {{-- PERSONAL INFORMATION --}}
                            <div class="tab-pane fade active show" id="personal-info" role="tabpanel"
                                data-next="#contact-info-tab" data-focus1=".em_name_local" aria-labelledby="personal-info-tab">
                                {{-- Personal Information --}}
                                <form action="{{route('employees.store')}}" class="employee_send_form" method="post" data-id="#contact-info-tab"
                                    data-focus=".em_name_local" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" class="employee_id" name="employee_id" id="">



                                    <div class="d-flex flex-wrap mb-1">
                                        <div class="col-fixed" style="margin-right:15px;">
                                            <div class="card pt-1">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="profile-picture-box" style="height:110px; width:140px;padding-left:15px; position: relative;">
                                                            <img class="preview" src="" style="height:110px; width:110px;display:none;">

                                                            <div class="bigPreview" style="display:none; position:absolute; top:0; left:120px; z-index:1000; border:1px solid #ccc; background:#fff;">
                                                                <img src="" id="bigPreviewImg" style="height:400px; width:400px;">
                                                            </div>
                                                        </div>

                                                        <input type="file" name="employee_image" id="profile_picture" class="form-control" style="margin: 5px 0;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="responsive-box" style="margin-right:15px;">
                                            <div class="card pb-1 pt-1">
                                                <div class="row mx-0">
                                                    <div class="col-md-4 form-group">
                                                        <label for="mode">First Name <sup class="text-danger">*</sup></label>
                                                        <input type="text" class="form-control inputFieldHeight first_name" name="first_name"  data-tab="1" data-index="0"
                                                            id="first_name" value=""
                                                            onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32) || (event.charCode == 46))  '
                                                            required>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label for="mode">Middle Name </label>
                                                        <input type="text" class="form-control inputFieldHeight" name="middle_name" data-tab="1" data-index="1"
                                                            id="middle_name" value=""
                                                            onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32) || (event.charCode == 46))'>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label for="mode">Last Name<sup class="text-danger">*</sup></label>
                                                        <input type="text" class="form-control inputFieldHeight" name="last_name"  data-tab="1" data-index="2"
                                                            id="last_name" value=""
                                                            onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32) || (event.charCode == 46))  '
                                                            required>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label for="mode">Date of Birth</label>
                                                        <input type="text" id="dob" autocomplete="off" class="form-control inputFieldHeight  datepicker"  value="" data-tab="1" data-index="3"
                                                        placeholder="DD/MM/YY" name="dob" >
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label for="gender">Gender<sup class="text-danger">*</sup></label>
                                                        <select name="gender" data-tab="1" data-index="4"
                                                            class="inputFieldHeight form-control  @error('salutation') error @enderror"
                                                            required id="gender">
                                                            <option value="Male"> Male</option>
                                                            <option value="Female" > Female</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label for="mode"> Nationality<sup class="text-danger">*</sup></label>
                                                        <input type="text" value="" class="form-control inputFieldHeight " name="nationality"  id="nationality"
                                                            required data-tab="1" data-index="5">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="responsive-box">
                                            <div class="card pb-1 pt-1">
                                                <div class="d-flex align-items-center" style="padding: 0 10px;">
                                                    <div class="form-group" style="width:70%;">
                                                        <label for="mode">Address (IN UAE) </label>
                                                        <input type="text" value="" class="form-control inputFieldHeight" name="parmanent_address"
                                                            id="parmanent_address" data-tab="1" data-index="6">
                                                    </div>
                                                    <div class="form-group" style="width:30%;margin-left:5px;">
                                                        <label for="mode"> Tel Number</label>
                                                        <input type="text" value="" class="form-control inputFieldHeight" name="contact_number"  id="contact_number"
                                                            data-tab="1" data-index="7">
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center" style="padding: 0 10px;">
                                                    <div class="form-group" style="width:50%;">
                                                        <label for="mode">Email <sup class="text-danger">*</sup> </label>
                                                        <input type="email" value="" class="form-control inputFieldHeight" name="email"  id="email"
                                                            data-tab="1" data-index="8" required>
                                                    </div>

                                                    <div class="form-group" style="margin-left:5px;width:30%;">
                                                        <label for="mode"> Employee Code </label>

                                                        <input type="text" name="employee_code" id="employee_code" class="form-control inputFieldHeight employee_code" required>
                                                        <small id="code_message" class="text-danger code_message"></small>
                                                    </div>

                                                    <div class="form-group" style="margin-left:5px;width:20%;">
                                                        <label for="mode"> Blood Group </label>
                                                        <input type="text"  value="" class="form-control inputFieldHeight blood_group" name="blood_group" id="blood_group"
                                                            data-tab="1" data-index="9">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <button type="submit" class="btn save-btn" style="width: 85px; background:#007ACC; color:#fff; margin-top: 10px; font-size:20px;">
                                            Save
                                        </button>

                                        <button type="submit" class="btn keyboard-control-btn" data-tab="#contact-info-tab" data-focus=".blood_group"
                                            style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                            Next
                                        </button>
                                    </div>
                                </form>
                            </div>
                            {{-- CONTACT INFORMATION --}}
                            <div class="tab-pane fade" id="contact-info" role="tabpanel" aria-labelledby="contact-info-tab"
                                data-prev="#personal-info-tab" data-focus=".first_name" data-next="#job-info-tab" data-focus1=".job_title">
                                 {{-- contact Information --}}
                                <form action="{{route('employees.store')}}" class="employee_send_form" method="post" data-id="#job-info-tab" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" class="employee_id" name="employee_id" id="">

                                    <div class="row">
                                        <div class=" col-12 col-lg-6">
                                            <div class="card pb-1 pt-1">
                                                 <h6  style="padding: 0 10px;"> LOCAL  </h6>
                                                <div class="row mx-0" style="padding: 0 5px;">
                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode"> Name  <sup class="text-danger">*</sup></label>
                                                        <input type="text"value="" class="form-control inputFieldHeight em_name_local" name="em_name_local" id="em_name_local" required>
                                                    </div>
                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode">Email Address</label>
                                                        <input type="email"  value=""class="form-control inputFieldHeight" name="em_email_local" id="em_email_local">
                                                    </div>
                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode"> Phone Number <sup class="text-danger">*</sup></label>
                                                        <input type="text" value="" class="form-control inputFieldHeight" name="em_contact_number_local"required>
                                                    </div>


                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode">City <sup class="text-danger">*</sup></label>
                                                        <input type="text" value="" class="form-control inputFieldHeight " name="em_city_local"  id="em_city_local"required>

                                                    </div>
                                                    {{-- <div class="form-group col-6 col-lg-4 d-none" style="padding: 0 5px;">
                                                        <label for="mode"> Country <sup class="text-danger">*</sup></label>
                                                        <select name="em_country_local" id="em_country_local"
                                                            class="form-control errorr-abcd"
                                                            style="width: 100% !important">
                                                            <option value="">Select ...</option>
                                                            @foreach ($countries as $country)
                                                            <option value="{{$country->id}}">{{$country->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div> --}}
                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode">Address <sup class="text-danger">*</sup></label>
                                                        <input type="text" value="" class="form-control inputFieldHeight " name="em_parmanent_address_local"  id="em_parmanent_address_local"required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6 ">
                                            <div class="card pb-1 pt-1">
                                                <h6  style="padding: 0 10px;"> HOME COUNTRY </h6>
                                                <div class="row mx-0" style="padding: 0 5px;">
                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode"> Name  <sup class="text-danger">*</sup></label>
                                                        <input type="text"value="" class="form-control inputFieldHeight" name="em_name_origin" id="em_name_origin"required>
                                                    </div>
                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">

                                                        <label for="mode">Email Address</label>
                                                        <input type="email"  value=""class="form-control inputFieldHeight" name="em_email_origin" id="em_email_origin">
                                                    </div>
                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode"> Phone Number <sup class="text-danger">*</sup></label>
                                                        <input type="text" value="" class="form-control inputFieldHeight" name="em_contact_number_origin"required>
                                                    </div>


                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode">City <sup class="text-danger">*</sup></label>
                                                        <input type="text" value="" class="form-control inputFieldHeight " name="em_city_origin"  id="em_city_origin"required>

                                                    </div>
                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode"> Country <sup class="text-danger">*</sup></label>
                                                        <select name="em_country_origin" id="em_country_origin"
                                                            class="form-control common-select2 errorr-abcd"
                                                            style="width: 100% !important" required>
                                                            <option value="">Select ...</option>
                                                            @foreach ($countries as $country)
                                                            <option value="{{$country->id}}">{{$country->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode">Address <sup class="text-danger">*</sup></label>
                                                        <input type="text" value="" class="form-control inputFieldHeight em_parmanent_address_origin" name="em_parmanent_address_origin"  id="em_parmanent_address_local"required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button type="submit" class="btn" style="width: 85px; background:#007ACC; color:#fff; margin-top: 10px; font-size:20px;">
                                            Save
                                        </button>

                                        <div>
                                            <button type="button" class="btn keyboard-control-btn" data-tab="#personal-info-tab" data-focus1=".first_name"
                                                style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                                Prev
                                            </button>

                                            <button type="button" class="btn keyboard-control-btn" data-tab="#job-info-tab" data-focus1=".job_title" data-focus=".em_parmanent_address_origin"
                                                style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                                Next
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            {{-- JOB INFORMATION --}}
                            <div class="tab-pane fade" id="job-info" role="tabpanel" aria-labelledby="job-info-tab"
                                data-prev="#contact-info-tab" data-focus=".em_name_local" data-next="#bank-account-tab" data-focus1=".bank_name">
                                <form action="{{route('employees.store')}}" class="employee_send_form" method="post" data-id="#bank-account-tab" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" class="employee_id" name="employee_id" id="">

                                    <div class="row">
                                        <div class="col-12 col-sm-6 col-md-6 ">
                                            <div class="card pb-1 pt-1">
                                                <div class="row mx-0" style="padding: 0 5px;">
                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode">JOB TITLE<sup class="text-danger">*</sup></label>
                                                        <input type="text" value="" class="form-control inputFieldHeight job_title" name="job_title" id="job_title"required>
                                                    </div>
                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode"> JOB DESCRIPTION</label>
                                                        <input type="text" value="" class="form-control inputFieldHeight" name="job_description">
                                                    </div>
                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode">Department <sup class="text-danger">*</sup></label>

                                                        <select name="division" id="division"
                                                            class="form-control common-select2 errorr-abcd"
                                                            style="width: 100% !important" required>
                                                            <option value="">Select ...</option>
                                                            @foreach ($divisions as $division)
                                                            <option value="{{$division->id}}" >{{$division->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode">Joining Date <sup class="text-danger">*</sup></label>
                                                        <input type="text" value="" class="form-control inputFieldHeight datepicker"
                                                            autocomplete="off" name="joining_date"
                                                            placeholder="DD/MM/YY"  required>
                                                    </div>

                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode" style="white-space: nowrap;">Job Status </label>

                                                            <select name="job_type" id="job_type" class="form-control common-select2"
                                                                style="width: 100% !important">
                                                                <option value="">Select...</option>
                                                                @foreach ($job_types  as  $job_type)
                                                                <option value="{{$job_type->id}}">{{$job_type->type}}</option>
                                                                @endforeach
                                                            </select>
                                                    </div>

                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode" style="white-space: nowrap;">Job Active Status <sup
                                                            class="text-danger">*</sup></label>

                                                        <select name="job_status" id="job_type" class="form-control common-select2"

                                                            style="width: 100% !important" required>
                                                            <option value="1" >Active</option>
                                                            <option value="0">Inactive</option>

                                                        </select>
                                                    </div>

                                                    {{-- <div class="col-md-6">
                                                        <label for="last_visite" class="col-form-label">Last Visite Date</label>
                                                        <input  type="text" autocomplete="off" placeholder="DD/MM/YYY" name="last_visite" id="last_visite" class="form-control datepicker">
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>

                                        <div class=" col-12 col-sm-6 col-md-6">
                                            <div class="card pb-1 pt-1">
                                                <div class="row mx-0" style="padding: 0 5px;">
                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode">Passport Number</label>
                                                        <input type="text"  value=""class="form-control inputFieldHeight eid_validaiton" id="" data-type="employee_passport" name="passport_number"  id="passport_number">
                                                    </div>
                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode">Visa Issue</label>
                                                        <input type="text" value="" autocomplete="off" class="form-control inputFieldHeight datepicker" placeholder="DD/MM/YY" name="vissa_issue">
                                                    </div>

                                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                        <label for="mode">Visa Expiry</label>
                                                        <input type="text"  value=""autocomplete="off" class="form-control inputFieldHeight datepicker visa_expiry_date" placeholder="DD/MM/YY" name="visa_expiry_date">

                                                    </div>

                                                    {{-- <div class="col-md-6">
                                                        <label for="mode">DESIGNATION <sup class="text-danger">*</sup></label>

                                                        <select name="department" id="department"
                                                            class="form-control common-select2" style="width: 100% !important"
                                                            required>
                                                            <option value="">Select ...</option>
                                                            @foreach ($departments as $department)
                                                            <option value="{{$department->id}}">{{$department->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div> --}}



                                                    {{-- <div class="col-md-6">
                                                        <label for="mode">Employee Basic Salary <sup class="text-danger">*</sup></label>
                                                        <input type="text" value="" class="form-control inputFieldHeight " name="basic_salary" style="font-size:12px"  required>
                                                    </div> --}}


                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <button type="submit" class="btn" style="width: 85px; background:#007ACC; color:#fff; margin-top: 10px; font-size:20px;">
                                            Save
                                        </button>

                                        <div>
                                            <button type="button" class="btn keyboard-control-btn" data-tab="#contact-info-tab" data-focus1=".em_name_local"
                                                style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                                Prev
                                            </button>

                                            <button type="button" class="btn keyboard-control-btn" data-tab="#bank-account-tab" data-focus1=".bank_name" data-focus=".visa_expiry_date"
                                                style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                                Next
                                            </button>
                                        </div>
                                    </div>

                                </form>
                            </div>

                                {{-- bank-account --}}
                                <div class="tab-pane fade" id="bank-account" role="tabpanel" aria-labelledby="bank-account-tab"
                                    data-prev="#job-info-tab" data-focus=".job-title" data-next="#policy-info-tab" data-focus1=".e_ref_out_time">

                                    <form action="{{route('employees.store')}}" class="employee_send_form" method="post" data-id="#policy-tab" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" class="employee_id" name="employee_id" id="">

                                        <div class="row">

                                            <div class=" col-12 col-sm-6 col-md-6 ">
                                                <div class="card pb-1 pt-1">

                                                    <div class="row mx-0" style="padding: 0 5px;">
                                                        <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                            <label for="mode">BANK NAME<sup class="text-danger">*</sup></label>
                                                            <input type="text" value="" class="form-control inputFieldHeight bank_name" name="bank_name" id="bank_name"required>
                                                        </div>
                                                        <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                            <label for="mode"> BRANCH NAME<sup class="text-danger">*</sup></label>
                                                            <input type="text" value="" class="form-control inputFieldHeight" name="branch_name"required>
                                                        </div>
                                                        <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                            <label for="mode">ACCOUNT NUMBER<sup class="text-danger">*</sup></label>
                                                            <input type="text" value=""  class="form-control inputFieldHeight "  name="account_number" required>
                                                        </div>


                                                        {{-- <div class="col-md-6">
                                                            <label for="last_visite" class="col-form-label">Last Visite Date</label>
                                                            <input  type="text" autocomplete="off" placeholder="DD/MM/YYY" name="last_visite" id="last_visite" class="form-control datepicker">
                                                        </div> --}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class=" col-12 col-sm-6 col-md-6 ">
                                                <div class="card pb-1 pt-1" >
                                                    <div class="row mx-0" style="padding: 0 5px;">
                                                        <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                            <label for="mode">ACCOUNT NAME<sup class="text-danger">*</sup></label>
                                                            <input type="text"  value=""class="form-control inputFieldHeight " name="account_name"  id="account_name"required>
                                                        </div>
                                                        <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                            <label for="mode">IBAN NUMBER<sup class="text-danger">*</sup></label>
                                                            <input type="text"  value=""autocomplete="off" class="form-control inputFieldHeight "  name="ibal_number" required>
                                                        </div>
                                                        <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                                            <label for="mode">ACCOUNT TYPE<sup class="text-danger">*</sup></label>
                                                            <input type="text"  value=""autocomplete="off" class="form-control inputFieldHeight "  name="account_type" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <button type="submit" class="btn" style="width: 85px; background:#007ACC; color:#fff; margin-top: 10px; font-size:20px;">
                                                Save
                                            </button>

                                            <div>
                                                <button type="button" class="btn keyboard-control-btn" data-tab="#job-info-tab" data-focus1=".job_title"
                                                    style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                                    Prev
                                                </button>

                                                <button type="button" class="btn keyboard-control-btn" data-tab="#policy-info-tab" data-focus1=".policy_type" data-focus=".e_ref_out_time"
                                                    style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                                    Next
                                                </button>
                                            </div>
                                        </div>

                                    </form>
                                </div>

                                {{-- policy INFORMATION --}}
                                <div class="tab-pane fade" id="policy" role="tabpanel" aria-labelledby="policy-tab"
                                    data-prev="#bank-account-tab" data-focus=".bank_name" data-next="#files-info-tab" data-focus1=".employee_image">
                                    <form action="{{route('employees.store')}}" class="employee_send_form" method="post" data-id="#files-tab" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" class="employee_id" name="employee_id" id="">
                                    <div class="row">

                                        <div class=" col-12 col-sm-6 col-md-6 ">
                                            <div class="card pb-1 pt-1">
                                                <div class="row mx-0" style="padding: 0 5px;">
                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="policy_type" class="col-form-label">Policy Type</label>
                                                        <select required name="policy_type" id="policy_type" class="form-control policy_type inputFieldHeight">
                                                            <option value="">Select..</option>
                                                            <option value="Default">Default</option>
                                                            <option value="Custom">Custom</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="effect_date" class="col-form-label">Effective Date</label>
                                                        <input required type="text" autocomplete="off" placeholder="DD/MM/YYY" name="effect_date" id="effect_date" class="form-control datepicker inputFieldHeight">
                                                    </div>

                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="air_ticket_eligibility" class="col-form-label">Air Ticket (Yes/No)</label>
                                                        <select required name="air_ticket_eligibility" id="air_ticket_eligibility" class="form-control">
                                                            <option value="">Select..</option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="apply_over_time" class="col-form-label">Cash Redeem</label>
                                                        <select required name="apply_over_time" id="apply_over_time" class="form-control">
                                                            <option value="">Select..</option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="ticket_price_percentage" class="col-form-label">Ticket allowance (Cash)</label>
                                                        <input required type="number"  name="ticket_price_percentage" id="ticket_price_percentage" class="form-control">
                                                    </div>

                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="vacation_paid_or_unpaid" class="col-form-label">Vacation Payment Type</label>
                                                        <select  required name="vacation_paid_or_unpaid" id="vacation_paid_or_unpaid" class="form-control">
                                                            <option value="">Select..</option>
                                                            <option value="Paid">Paid</option>
                                                            <option value="Unpaid">Unpaid</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="vacation_type" class="col-form-label">Leave Type</label>
                                                        <select  required name="vacation_type" id="vacation_type" class="form-control">
                                                            <option value="">Select..</option>
                                                            <option value="Fixed Period">Fixed Period</option>
                                                            <option value="Flexible Period">Flexible Period</option>
                                                        </select>
                                                    </div>

                                                    {{-- <div class="col-md-6">
                                                        <label for="minimum_day_for_ticket_price" class="col-form-label">Minimum Days for applicable vacation</label>
                                                        <input required type="number"  name="minimum_day_for_ticket_price" id="minimum_day_for_ticket_price" class="form-control">
                                                    </div> --}}
                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="minimun_vacation_priod" class="col-form-label">Minimum Leave Period (Year)</label>
                                                        <select  required name="minimun_vacation_priod" id="minimun_vacation_priod" class="form-control">
                                                            <option value="">Select..</option>
                                                            <option value="1" >One Year</option>
                                                            <option value="2" >Two Year</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="number_of_yearly_vacation" class="col-form-label">Total Leave (Days) per Year</label>
                                                        <input required type="number" name="number_of_yearly_vacation" id="number_of_yearly_vacation" class="form-control inputFieldHeight">
                                                    </div>
                                                    {{--
                                                        <div class="col-md-6">
                                                            <label for="time_zone" class="col-form-label">Time Zone</label>
                                                            <input required type="text" name="time_zone" value="{{isset($emp_policy->time_zone) ? $emp_policy->time_zone : '' }}" id="time_zone" class="form-control">
                                                        </div> --}}
                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="basic_salary" class="col-form-label">Basic Salary</label>
                                                        <input required type="text" name="basic_salary" value="" id="basic_salary" class="form-control inputFieldHeight">
                                                    </div>
                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="description" class="col-form-label">Description</label>
                                                        <textarea name="description" id="description" class="form-control" rows="2"></textarea>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class=" col-12 col-sm-6 col-md-6 ">
                                            <div class="card pb-1 pt-1">
                                                <div class="row mx-0" style="padding: 0 5px;">
                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="maximum_time_for_attendace" class="col-form-label">Attendance Record Time (Min)</label>
                                                        <input required type="number" name="maximum_time_for_attendace" id="maximum_time_for_attendace" class="form-control">
                                                    </div>

                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="late_type" class="col-form-label">Late Type</label>
                                                        <select required name="late_type" id="late_type" class="form-control">
                                                            <option value="day">Day</option>
                                                            <option value="hours">Hours</option>
                                                        </select>
                                                    </div>

                                                    {{-- <div class="col-md-6">
                                                        <label for="minimum_day_for_late" class="col-form-label">Minimum Days for Late</label>
                                                        <input required type="number"  name="minimum_day_for_late" id="minimum_day_for_late" class="form-control">
                                                    </div> --}}

                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="minimum_hours_for_late" class="col-form-label">Minimum Allowable Late Hrs </label>
                                                        <input required type="number"  name="minimum_hours_for_late" id="minimum_hours_for_late" class="form-control">
                                                    </div>
                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="salary_loss" class="col-form-label">Salary Deduction Rate (%)</label>
                                                        <input required type="number" step="0.01" name="salary_loss" id="salary_loss" class="form-control">
                                                    </div>
                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="cash_redeem" class="col-form-label"> Over Time Eligible </label>
                                                        <select required name="cash_redeem" id="cash_redeem" class="form-control">
                                                            {{-- <option value="">Select..</option> --}}
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="overtime_rate" class="col-form-label">Overtime Rate (%)</label>
                                                        <input required type="number"  name="overtime_rate" id="overtime_rate" class="form-control">
                                                    </div>

                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="min_hours_for_overtime" class="col-form-label">Minimum Hrs for OverTime </label>
                                                        <input required type="number"  name="min_hours_for_overtime" id="min_hours_for_overtime" class="form-control">
                                                    </div>

                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="late_grace_time" class="col-form-label">Late Grace Time (Min)</label>
                                                        <input required type="number" name="late_grace_time" id="late_grace_time" class="form-control">
                                                    </div>



                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="m_ref_in_time" class="col-form-label">1st shift (start)</label>
                                                        <input required type="time" name="m_ref_in_time" id="m_ref_in_time" class="form-control morning_in_time">
                                                    </div>

                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="m_ref_out_time" class="col-form-label">1st shift (END)</label>
                                                        <input required type="time" name="m_ref_out_time" id="m_ref_out_time" class="form-control morning_out_time">
                                                    </div>

                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="e_ref_in_time" class="col-form-label">2nd shift (START)</label>
                                                        <input required type="time" name="e_ref_in_time" id="e_ref_in_time" class="form-control evening_in_time">
                                                    </div>

                                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                                        <label for="e_ref_out_time" class="col-form-label">2nd shift (end)</label>
                                                        <input required type="time"  name="e_ref_out_time" id="e_ref_out_time" class="form-control e_ref_out_time evening_out_time">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <button type="submit" class="btn" style="width: 85px; background:#007ACC; color:#fff; margin-top: 10px; font-size:20px;">
                                            Save
                                        </button>

                                        <div>
                                            <button type="button" class="btn keyboard-control-btn" data-tab="#bank-account-tab" data-focus1=".bank_name"
                                                style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                                Prev
                                            </button>

                                            <button type="button" class="btn keyboard-control-btn" data-tab="#files-info-tab" data-focus1=".employee_image" data-focus=".e_ref_out_time"
                                                style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                                Next
                                            </button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                              {{-- FILES --}}
                            <div class="tab-pane fade  " id="files" role="tabpanel" aria-labelledby="files-tab"
                                data-prev="#policy-info-tab" data-focus=".policy_type">
                                    {{-- file Information --}}
                                <form action="{{route('employees.store')}}" class="employee_send_form" method="post" data-id="#files-tab" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" class="employee_id" name="employee_id" id="">
                                    <div class="row">
                                        <table class="table  table-sm table-hover" style="max-width: 100%">
                                            <thead  class="thead-light">
                                                <tr>
                                                    <th> Document Input </th>
                                                    <th> Document Type </th>
                                                    <th> Preview </th>
                                                    <th class="text-right">
                                                        Action
                                                        <button type="button" class="add-row text-white"
                                                                title="Add Row"
                                                                style="padding: 2px 6px; font-size: 14px; background-color: #28a745; color: white; border: none; border-radius: 3px; margin-left: 5px;">
                                                            <i class="bx bx-plus" style="color: #fff;"></i>
                                                        </button>
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody class="documentTableBody">

                                                <td>
                                                    <input type="file" class="form-control inputFieldHeight file-input" name="document[]" required>
                                                </td>

                                                <td>
                                                    <select name="document_type[]" id="document_type" required class="form-control inputFieldHeight">
                                                        <option value=""> Select </option>
                                                        <option value="id_card"> ID Card </option>
                                                        <option value="visa"> Visa Image </option>
                                                        <option value="other"> Other </option>
                                                    </select>
                                                </td>


                                                <td>
                                                    <img src="" class="preview" alt="">
                                                </td>
                                                <td class="text-right">
                                                    <button type="button" class="delete-button"
                                                            title="Delete"
                                                            style="padding: 2px 6px; font-size: 14px; background-color: #dc3545; color: white; border: none; border-radius: 3px;">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </td>

                                            </tbody>

                                        </table>

                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button type="submit" class="btn" style="width: 85px; background:#007ACC; color:#fff; margin-top: 10px; font-size:20px;">
                                            Save
                                        </button>

                                        <div>
                                            <button type="button" class="btn keyboard-control-btn" data-tab="#policy-info-tab" data-focus1=".policy_type"
                                                style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                                Prev
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    {{-- **************** Employees create modal end ************************ --}}

    <div class="modal fade" style="width: 100%;" id="employee-modal-edit" tabindex="-1" role="dialog"
        aria-labelledby="employee-modal-edit" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document" style="padding-right: 0px !important;">
            <div class="modal-content ">


                <div class="" id="edit-modal">


                </div>
            </div>
        </div>
        {{-- **************** Employees edit modal end ************************ --}}
    </div>

    @push('js')
    <script>


    $(document).ready(function() {
        function readURL(input, previewId) {
            if (input.files && input.files[0]) {
                var file = input.files[0];
                var fileType = file['type'];
                var validImageTypes = ["image/jpeg", "image/png", "image/gif"];

                if ($.inArray(fileType, validImageTypes) > -1) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $(previewId).attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                } else {
                    $(previewId).attr('src', '{{asset('/img/file.png')}}');
                }
            }
        }


        // Event listeners for each file input
        $('#employee_image').change(function() {
            readURL(this, '#employee_image_preview');
        });

        $('#emirates_image').change(function() {
            readURL(this, '#emirates_image_preview');
        });

        $('#vissa_image').change(function() {
            readURL(this, '#vissa_image_preview');
        });

        $('#_image').change(function() {
            readURL(this, '#_image_preview');
        });
    });

        // 8888888888888888888888888888888888 AJAX email-valadition off   888888888888888888888888888888888888888888
      $(document).on("change", ".errorr-abcd", function(e) {
        e.preventDefault();

        var value = $(this).val();
            var emailInput = $("#email");

            if (value == 3 || value == 4 || value == 5) {
                emailInput.prop("required", false);
                emailInput.removeClass("ajax-error");
                $(".employee-save-button").prop("disabled", false)
                $(".email_error").text("").css("color", "green").show();


            } else {
                if(emailInput.val()!='')

            {

                emailInput.prop("required", true);
                emailInput.addClass("ajax-error");
                reverse('email');
            }

        }



  });
// 8888888888888888888888888888888888 AJAX VALIDATION 888888888888888888888888888888888888888888
    </script>

    @endpush


<!-- summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

@php
    $emirates=array('Abu Dhabi','Ajman','Dubai','Fujairah','Ras Al Khaimah','Sharjah','Umm Al Quwain');
    $languages= array('Bangla','English','Urdu','Arabic','Hindi');
    $employee_roles= array('Principle','Teacher', 'Admin', 'Accounts Executive', 'Librarian','Driver','Clerk','Cleaner','Secretary','Accountant','Trainer');
@endphp
<style>
    input[type=text], select, textarea {
        height: 2.5rem;
        font-size: 16px;
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
 <section class="print-hideen border-bottom" style="padding: 0px 32px; background-color:#475f7b;">
    <div class="d-flex justify-content-between align-item-center">
        <h5 style="font-family:Cambria;font-size: 1.6rem; margin-top:8px;margin-left:13px;color:#ececec !important;"><b>Edit Employee Profile</b> </h5>

        <div class="d-flex flex-row-reverse">
            <div class="mIconStyleChange">
                <a href="#" class="btn-icon btn btn-danger mIconStyleChange212" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class='bx bx-x'></i></span>
                </a>
            </div>
            <div class="mIconStyleChange">
                <a href="#" title="Print" onclick="handlePrintClick('modal-body-print', 0)" class="btn btn-icon btn-info"><i class='bx bx-printer'></i> </a>
            </div>
            <div class="mIconStyleChange">
                <a href="{{route('employees-delete',$employee_info->id)}}" onclick="return  confirm('Are Youe Sure To delete It ?')"   title="delete" class="btn btn-icon btn-danger "><i class='bx bx-trash'></i></a>
            </div>

            @if ($employee_info->status != 1)
            <div class="mIconStyleChange">
                <a href="{{ route('employees-approve',$employee_info->id) }}" title="Approve" onclick="return confirm('Are Youe Sure To Approve It ?')" class="btn btn-icon btn-warning"><i class='bx bx-check'></i></a>
            </div>
            @endif
        </div>
    </div>
</section>
<div class="modal-body" id="modal-body">
    <div class="card-body" style="padding: 0px;">
        <ul class="nav nav-tabs nav-tabs1" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="personal-info-tab-edit" data-toggle="tab" href="#personal-infou" role="tab" aria-controls="personal-infou" aria-selected="false">Personal Information</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="contact-info-tab-edit" data-toggle="tab" href="#contact-infou" role="tab" aria-controls="contact-infou" aria-selected="false"> Emergency Contact</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="job-info-tab-edit" data-toggle="tab" href="#job-infou" role="tab" aria-controls="job-infou" aria-selected="false">Job Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="bank-accountu-tab-edit" data-toggle="tab" href="#bank-accountu" role="tab" aria-controls="bank-accountu" aria-selected="false">Bank Account</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " id="policy-tab-edit" data-toggle="tab" href="#policyu" role="tab" aria-controls="policyu" aria-selected="true">Policy</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " id="files-tab-edit" data-toggle="tab" href="#filesu" role="tab" aria-controls="filesu" aria-selected="true">Documents</a>
            </li>

        </ul>

        <div class="tab-content tab-content1" id="myTabContent">

            {{-- PERSONAL INFORMATION --}}
            <div class="tab-pane fade" id="personal-infou" role="tabpanel"
                data-next="#contact-info-tab-edit" data-focus1=".em_name_local" aria-labelledby="personal-info-tab">

                {{-- Personal Information --}}
                <form action="{{route('employees.update', $employee_info->id)}}"  class="employee_send_form" method="post"
                    data-id="#contact-info-tab-edit" data-focus=".em_name_local" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    {{-- <h5 style="color: #475f7b;margin-top: 0.5rem;;font-size:19px;"> PERSONAL INFORMATION </h5> --}}
                    <div class="d-flex flex-wrap mb-1">
                        <div class="col-fixed" style="margin-right:15px;">
                            <div class="card pt-1">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="profile-picture-box" style="height:110px; width:140px;padding-left:15px; position: relative;">
                                            <img class="preview" src="{{ asset('storage/upload/employee/'.$employee_info->employee_image)}}" style="height:110px; width:110px;{{$employee_info->employee_image? ' ' : 'display:none'}};">

                                            <div class="bigPreview" style="display:none; position:absolute; top:0; left:120px; z-index:1000; border:1px solid #ccc; background:#fff;">
                                                <img src="{{ asset('storage/upload/employee/'.$employee_info->employee_image)}}" id="bigPreviewImg" style="height:400px; width:400px;">
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
                                        <input type="text" class="form-control inputFieldHeight first_name" name="first_name"
                                            id="first_name" value="{{$employee_info->first_name}}"
                                            onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32) || (event.charCode == 46))  '
                                            required>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label for="mode">Middle Name </label>
                                        <input type="text" class="form-control inputFieldHeight" name="middle_name"
                                            id="middle_name" value="{{$employee_info->middle_name}}"
                                            onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32) || (event.charCode == 46))'>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="mode">Last Name<sup class="text-danger">*</sup></label>
                                        <input type="text" class="form-control inputFieldHeight" name="last_name"
                                            id="last_name" value="{{$employee_info->last_name}}"
                                            onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32) || (event.charCode == 46))  '
                                            required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="mode">Date of Birth<sup class="text-danger">*</sup></label>
                                        <input type="text" autocomplete="off" class="form-control inputFieldHeight  datepicker"  value="{{date('d/m/Y', strtotime($employee_info->dob))}}"
                                        placeholder="DD/MM/YY" name="dob"
                                            required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="mode">Gender<sup class="text-danger">*</sup></label>
                                        <select name="gender"
                                            class="inputFieldHeight form-control  @error('salutation') error @enderror"
                                            id="" required>
                                            <option value="Male" {{$employee_info->gender =='Male' ? 'selected' : '' }}> Male</option>
                                            <option value="Female" {{$employee_info->gender =='Female' ? 'selected' : '' }}> Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label for="mode"> Nationality<sup class="text-danger">*</sup></label>
                                        <input type="text" value="{{$employee_info->nationality}}" class="form-control inputFieldHeight " name="nationality"  id="nationality"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="responsive-box">
                            <div class="card pb-1 pt-1">
                                <div class="d-flex align-items-center" style="padding: 0 10px;">
                                <div class="form-group" style="width:70%;">
                                        <label for="mode">Address (IN UAE) </label>
                                        <input type="text" value="{{$employee_info->parmanent_address}}" class="form-control inputFieldHeight" name="parmanent_address"
                                            id="present_address">
                                    </div>

                                    <div class="form-group" style="width:30%;margin-left:5px;">
                                        <label for="mode">Tel Number</label>
                                        <input type="text" value="{{$employee_info->contact_number}}"  class="form-control inputFieldHeight" name="contact_number"  id="contact_number">
                                    </div>
                                </div>

                                <div class="d-flex align-items-center" style="padding: 0 10px;">
                                    <div class="form-group" style="width:50%;">
                                        <label for="mode">Email<sup class="text-danger">*</sup></label>
                                        <input type="email" value="{{$employee_info->email}}" class="form-control inputFieldHeight" name="email"  id="email" required>
                                    </div>

                                    <div class="form-group" style="margin-left:5px;width:30%;">
                                        <label for="mode"> Employee Code </label>

                                        <input type="text" name="employee_code" data-employee="{{$employee_info->id}}" id="employee_code" value="{{$employee_info->code}}" class="form-control inputFieldHeight employee_code" required>
                                        <small class="text-danger code_message"></small>
                                    </div>

                                    <div class="form-group" style="width:20%;margin-left:5px;">
                                        <label for="mode">Blood Group </label>
                                        <input type="text"  value="{{$employee_info->blood_group}}"class="form-control inputFieldHeight blood_group" name="blood_group" id="blood_group">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn" style="width: 85px; background:#007ACC; color:#fff; margin-top: 10px; font-size:20px;">
                            Save
                        </button>

                        <button type="button" class="btn keyboard-control-btn" data-tab="#contact-info-tab-edit" data-focus1=".em_name_local" data-focus=".blood_group"
                            style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                            Next
                        </button>
                    </div>
                </form>
            </div>

            {{-- CONTACT INFORMATION --}}
            <div class="tab-pane fade" id="contact-infou" role="tabpanel" aria-labelledby="contact-info-tab-edit"
                data-prev="#personal-info-tab-edit" data-focus=".first_name" data-next="#job-info-tab-edit" data-focus1=".job_title">

                <form action="{{route('employees.update', $employee_info->id)}}"  class="employee_send_form" method="post"
                    data-id="#job-info-tab-edit" data-focus=".job_title" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    {{-- contact Information --}}
                    <div class="row">

                        <div class=" col-12 col-sm-6 col-md-6 ">
                            <div class="card pb-1 pt-1">
                                <h6  style="padding: 0 10px;"> LOCAL  </h6>
                                <div class="row mx-0" style="padding: 0 5px;">
                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                        <label for="mode"> Name <sup class="text-danger">*</sup></label>
                                        <input type="text" value="{{$employee_info->em_name_local}}" class="form-control inputFieldHeight em_name_local" name="em_name_local" id="em_name_local" required>
                                    </div>

                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                        <label for="mode">Email Address</label>
                                        <input type="email"  value="{{$employee_info->em_email_local}}"class="form-control inputFieldHeight" name="em_email_local" id="em_email_local">
                                    </div>

                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                        <label for="mode"> Phone Number<sup class="text-danger">*</sup></label>
                                        <input type="text" value="{{$employee_info->em_contact_number_local}}" class="form-control inputFieldHeight" name="em_contact_number_local"required>
                                    </div>

                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                        <label for="mode">City<sup class="text-danger">*</sup></label>
                                        <input type="text" value="{{$employee_info->em_city_local}}" class="form-control inputFieldHeight " name="em_city_local"  id="em_city_local"required>
                                    </div>

                                    {{-- <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                        <label for="mode"> Country<sup class="text-danger">*</sup></label>
                                        <select name="em_country_local" id="em_country_local"
                                            class="form-control common-select2 errorr-abcd"
                                            style="width: 100% !important" required>
                                            <option value="">Select ...</option>
                                            @foreach ($countries as $country)
                                            <option value="{{$country->id}}" {{$country->id  == $employee_info->em_country_local ? 'selected' : ''}}>{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                        <label for="mode">Address<sup class="text-danger">*</sup></label>
                                        <input type="text" value="{{$employee_info->em_parmanent_address_local}}" class="form-control inputFieldHeight " name="em_parmanent_address_local"  id="em_parmanent_address_local"required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" col-12 col-sm-6 col-md-6 ">
                            <div class="card pb-1 pt-1">
                                <h6  style="padding: 0 10px;"> HOME COUNTRY </h6>
                                <div class="row mx-0" style="padding: 0 5px;">
                                <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                        <label for="mode"> Name <sup class="text-danger">*</sup></label>
                                        <input type="text"value="{{$employee_info->em_name_origin}}" class="form-control inputFieldHeight" name="em_name_origin" id="em_name_origin"required>
                                    </div>
                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">

                                        <label for="mode">Email Address</label>
                                        <input type="email"  value="{{$employee_info->em_email_origin}}"class="form-control inputFieldHeight" name="em_email_origin" id="em_email_origin">
                                    </div>
                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                        <label for="mode"> Phone Number <sup class="text-danger">*</sup></label>
                                        <input type="text" value="{{$employee_info->em_contact_number_origin}}" class="form-control inputFieldHeight" name="em_contact_number_origin"required>
                                    </div>


                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                        <label for="mode">City <sup class="text-danger">*</sup></label>
                                        <input type="text" value="{{$employee_info->em_city_origin}}" class="form-control inputFieldHeight " name="em_city_origin"  id="em_city_origin"required>

                                    </div>
                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                        <label for="mode"> Country <sup class="text-danger">*</sup></label>
                                        <select name="em_country_origin" id="em_country_origin"
                                            class="form-control common-select2 errorr-abcd"
                                            style="width: 100% !important" required>
                                            <option value="">Select ...</option>
                                            @foreach ($countries as $country)
                                            <option value="{{$country->id}}" {{$country->id ==  $employee_info->em_country_origin ? 'selected' : ''}}>{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                        <label for="mode">Address <sup class="text-danger">*</sup></label>
                                        <input type="text" value="{{$employee_info->em_parmanent_address_origin}}" class="form-control inputFieldHeight em_parmanent_address_origin" name="em_parmanent_address_origin"  id="em_parmanent_address_local"required>
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
                            <button type="button" class="btn keyboard-control-btn" data-tab="#contact-info-tab-edit" data-focus1=".first_name"
                                style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                Prev
                            </button>

                            <button type="button" class="btn keyboard-control-btn" data-tab="#job-info-tab-edit" data-focus1=".job_title" data-focus=".em_parmanent_address_origin"
                                style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                Next
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- JOB INFORMATION --}}
            <div class="tab-pane fade" id="job-infou" role="tabpanel" aria-labelledby="job-info-tab-edit"
                data-prev="#contact-info-tab-edit" data-focus=".em_name_origin" data-next="#bank-accountu-tab-edit" data-focus1=".bank_name">
                <form action="{{route('employees.update', $employee_info->id)}}" class="employee_send_form" method="post" data-id="#bank-accountu-tab-edit" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                 <div class="row">

                    <div class=" col-12 col-sm-6 col-md-6 ">
                        <div class="card pb-1 pt-1">
                            <div class="row mx-0" style="padding: 0 5px;">
                                 <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                    <label for="mode"> JOB TITLE <sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{$employee_info->job_title}}" class="form-control inputFieldHeight job_title" name="job_title" id="job_title"required>
                                </div>
                                 <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                    <label for="mode"> JOB DESCRIPTION<sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{$employee_info->job_description}}" class="form-control inputFieldHeight" name="job_description"required>
                                </div>

                                <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                    <label for="mode"> Department <sup class="text-danger">*</sup></label>
                                    <select name="division" id="division"
                                        class="form-control common-select2 errorr-abcd"
                                        style="width: 100% !important" required>
                                        <option value="">Select ...</option>
                                        @foreach ($divisions as $division)
                                        <option value="{{$division->id}}" {{$division->id == $employee_info->division ? 'selected' : '' }}>{{$division->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                    <label for="mode">Joining Date <sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{$employee_info->joining_date ? date('d/m/Y', strtotime($employee_info->joining_date)) : ''}}" class="form-control inputFieldHeight datepicker"
                                        autocomplete="off" name="joining_date"
                                        placeholder="DD/MM/YY"  required>
                                </div>
                                <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                    <label for="mode" style="white-space: nowrap;">Job Status <sup
                                        class="text-danger">*</sup></label>

                                    <select name="job_type" id="job_type" class="form-control common-select2"
                                        style="width: 100% !important" required>
                                        <option value="">Select...</option>
                                        @foreach ($job_types  as  $job_type)
                                        <option value="{{$job_type->id}}" {{$job_type->id == $employee_info->job_type ? 'selected' : '' }}>{{$job_type->type}}</option>
                                        @endforeach


                                    </select>
                                </div>

                                {{-- <div class="col-md-6">
                                    <label for="mode">Employee Basic Salary <sup class="text-danger">*</sup></label>
                                    <input type="text" value="{{$employee_info->basic_salary}}" class="form-control inputFieldHeight " name="basic_salary" style="font-size:12px"  required>
                                </div> --}}
                                <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                    <label for="mode" style="white-space: nowrap;">Job Active Status <sup
                                        class="text-danger">*</sup></label>

                                    <select name="job_status" id="job_type" class="form-control common-select2 job_status"
                                        style="width: 100% !important" required>
                                        <option value="1" {{"1" == $employee_info->job_status ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{"0" == $employee_info->job_status ? 'selected' : '' }}>Inactive</option>
                                        <option value="2" {{"2" == $employee_info->job_status ? 'selected' : '' }}> Leave </option>
                                    </select>
                                </div>

                                <div class="form-group col-6 col-lg-4 job_status_div" style="padding: 0 5px; {{$employee_info->job_status == '2' ?'':'display:none;'}}">
                                    <label style="white-space: nowrap;"> Leave Date <sup
                                        class="text-danger leave_date-span">*</sup></label>

                                    <input type="text" class="form-control datepicker leave_date" name="leave_date"
                                        value="{{$employee_info->leave_date ? date('d/m/Y', strtotime($employee_info->leave_date)) : date('d/m/Y')}}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class=" col-12 col-sm-6 col-md-6 ">
                        <div class="card pb-1 pt-1" >
                            <div class="row mx-0" style="padding: 0 5px;">

                                {{-- <div class="col-md-6">
                                    <label for="mode">DESIGNATION <sup class="text-danger">*</sup></label>

                                    <select name="department" id="department"
                                        class="form-control common-select2" style="width: 100% !important"
                                        required>
                                        <option value="">Select ...</option>
                                        @foreach ($departments as $department)
                                        <option value="{{$department->id}}"{{$department->id == $employee_info->department ? 'selected' : '' }}>{{$department->name}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                    <label for="mode">Passport Number</label>
                                    <input type="text"  value="{{$employee_info->passport_number}}"class="form-control inputFieldHeight eid_validaiton" id="{{$employee_info->id}}" data-type="employee_passport" name="passport_number"  id="passport_number">
                                </div>

                                <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                    <label for="mode">Visa Issue</label>
                                    <input type="text" value="{{$employee_info->vissa_issue ? date('d/m/Y', strtotime($employee_info->vissa_issue)): ''}}" autocomplete="off" class="form-control inputFieldHeight datepicker" placeholder="DD/MM/YY" name="vissa_issue">
                                </div>

                                <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                    <label for="mode">Visa Expiry</label>
                                    <input type="text"  value="{{$employee_info->visa_expiry_date ? date('d/m/Y', strtotime($employee_info->visa_expiry_date)): ''}}"autocomplete="off" class="form-control inputFieldHeight datepicker" placeholder="DD/MM/YY" name="visa_expiry_date">

                                </div>

                                <div class="form-group col-6 col-lg-4 d-none" style="padding: 0 5px;">
                                    <label for="last_visite">Last Visit Date</label>
                                    <input  type="text" autocomplete="off" readonly placeholder="DD/MM/YYY" name="last_visite" value="{{ $employee_info->last_visite ? date('d/m/Y', strtotime($employee_info->last_visite)) : ''}}" id="last_visite" class="form-control datepicker last_visite">
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
                            <button type="button" class="btn keyboard-control-btn" data-tab="#contact-info-tab-edit" data-focus1=".em_name_origin"
                                style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                Prev
                            </button>

                            <button type="button" class="btn keyboard-control-btn" data-tab="#bank-accountu-tab-edit" data-focus1=".bank_name" data-focus=".account_type"
                                style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                Next
                            </button>
                        </div>
                    </div>
             </form>
            </div>
            {{-- bank-account --}}
            <div class="tab-pane fade" id="bank-accountu" role="tabpanel" aria-labelledby="bank-account-tab"
                data-prev="#job-info-tab-edit" data-focus=".job-title" data-next="#policy-tab-edit" data-focus1=".e_ref_out_time">

                <form action="{{route('employees.update', $employee_info->id)}}" class="employee_send_form" method="post" data-id="#policy-tab" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <input type="hidden" class="employee_id" name="employee_id" id="">

                    <div class="row">

                        <div class=" col-12 col-sm-6 col-md-6 ">
                            <div class="card pb-1 pt-1">
                                <div class="row mx-0" style="padding: 0 5px;">
                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                        <label for="mode">BANK NAME<sup class="text-danger">*</sup></label>
                                        <input type="text" value="{{$employee_info->bank_name}}" class="form-control inputFieldHeight bank_name" name="bank_name" id="bank_name"required>
                                    </div>
                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                        <label for="mode"> BRANCH NAME<sup class="text-danger">*</sup></label>
                                        <input type="text" value="{{$employee_info->branch_name}}" class="form-control inputFieldHeight" name="branch_name"required>
                                    </div>
                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                        <label for="mode">ACCOUNT NUMBER<sup class="text-danger">*</sup></label>
                                        <input type="text" value="{{$employee_info->account_number}}"  class="form-control inputFieldHeight "  name="account_number" required>
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
                                <div class="row mx-0">
                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                        <label for="mode">ACCOUNT NAME<sup class="text-danger">*</sup></label>
                                        <input type="text"  value="{{$employee_info->account_name}}" class="form-control inputFieldHeight " name="account_name"  id="account_name"required>
                                    </div>
                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                        <label for="mode">IBAN NUMBER<sup class="text-danger">*</sup></label>
                                        <input type="text"  value="{{$employee_info->ibal_number}}" class="form-control inputFieldHeight "  name="ibal_number" required>

                                    </div>
                                    <div class="form-group col-6 col-lg-4" style="padding: 0 5px;">
                                        <label for="mode">ACCOUNT TYPE<sup class="text-danger">*</sup></label>
                                        <input type="text"  value="{{$employee_info->account_type}}" class="form-control inputFieldHeight account_type"  name="account_type" required>
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
                            <button type="button" class="btn keyboard-control-btn" data-tab="#job-info-tab-edit" data-focus1=".job_title"
                                style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                Prev
                            </button>

                            <button type="button" class="btn keyboard-control-btn" data-tab="#policy-tab-edit" data-focus1=".policy_type" data-focus=".e_ref_out_time"
                                style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                Next
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            {{-- policy INFORMATION --}}
            <div class="tab-pane fade" id="policyu" role="tabpanel" aria-labelledby="policy-tab"
                data-prev="#bank-accountu-tab-edit" data-focus=".bank_name" data-next="#files-tab-edit" data-focus1=".employee_image">

                <form action="{{route('employees.update', $employee_info->id)}}"  class="employee_send_form" method="post" data-id="#files-tab" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">

                        <div class="col-12 col-lg-6">
                            <div class="card pb-1 pt-1">
                                <div class="row mx-0" style="padding: 0 5px;">
                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="policy_type" class="col-form-label">Policy Type</label>
                                        <select required name="policy_type" id="policy_typeu" class="form-control policy_type inputFieldHeight">
                                            <option value="">Select..</option>
                                            <option value="Custom" {{ (isset($emp_policy->policy_type) && $emp_policy->policy_type == 'Custom') ? 'selected' : '' }}>Custom</option>
                                            <option value="Default" {{ (isset($emp_policy->policy_type) && $emp_policy->policy_type == 'Default') ? 'selected' : '' }}>Default</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="effect_date" class="col-form-label">Effective Date</label>
                                        <input required type="text" autocomplete="off" placeholder="DD/MM/YYYY" value="{{ isset($emp_policy->effect_date) ? date('d/m/Y', strtotime($emp_policy->effect_date)) : '' }}" name="effect_date" class="form-control datepicker inputFieldHeight">
                                    </div>

                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="air_ticket_eligibility" class="col-form-label">Air Ticket (Yes/No)</label>
                                        <select required name="air_ticket_eligibility" id="air_ticket_eligibility" class="form-control">
                                            <option value="">Select..</option>
                                            <option value="Yes" {{ (isset($emp_policy->air_ticket_eligibility) && $emp_policy->air_ticket_eligibility == 'Yes') ? 'selected' : '' }}>Yes</option>
                                            <option value="No" {{ (isset($emp_policy->air_ticket_eligibility) && $emp_policy->air_ticket_eligibility == 'No') ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="apply_over_time" class="col-form-label">Cash Redeem</label>
                                        <select  required name="apply_over_time" id="apply_over_time" class="form-control">
                                            <option value="">Select..</option>
                                            <option value="Yes" {{ (isset($emp_policy->apply_over_time) && $emp_policy->apply_over_time == 'Yes') ? 'selected' : '' }}>Yes</option>
                                            <option value="No" {{ (isset($emp_policy->apply_over_time) && $emp_policy->apply_over_time == 'No') ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="ticket_price_percentage" class="col-form-label">Ticket allowance (Cash)</label>
                                        <input required type="number" name="ticket_price_percentage"
                                            value="{{ isset($emp_policy->ticket_price_percentage) ? $emp_policy->ticket_price_percentage : '' }}"
                                            id="ticket_price_percentage" class="form-control"  >
                                    </div>
                                <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="vacation_paid_or_unpaid" class="col-form-label">Leave Payment Type</label>
                                        <select  required name="vacation_paid_or_unpaid" id="vacation_paid_or_unpaid" class="form-control">
                                            <option value="">Select..</option>
                                            <option value="Paid" {{ (isset($emp_policy->vacation_paid_or_unpaid) && $emp_policy->vacation_paid_or_unpaid == 'Paid') ? 'selected' : '' }}>Paid</option>
                                            <option value="Unpaid" {{ (isset($emp_policy->vacation_paid_or_unpaid) && $emp_policy->vacation_paid_or_unpaid == 'Unpaid') ? 'selected' : '' }}>Unpaid</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="vacation_type" class="col-form-label">Vacation Type</label>
                                        <select readonly required name="vacation_type" id="vacation_type" class="form-control">
                                            <option value="">Select..</option>
                                            <option value="Fixed Period" {{ (isset($emp_policy->vacation_type) && $emp_policy->vacation_type == 'Fixed Period') ? 'selected' : '' }}>Fixed Period</option>
                                            <option value="Flexible Period" {{ (isset($emp_policy->vacation_type) && $emp_policy->vacation_type == 'Flexible Period') ? 'selected' : '' }}>Flexible Period</option>
                                        </select>
                                    </div>

                                    {{-- <div class="col-md-6">
                                        <label for="minimum_day_for_ticket_price" class="col-form-label">Minimum Days for applicable vacation</label>
                                        <input required type="number" name="minimum_day_for_ticket_price" value="{{ isset($emp_policy->minimum_day_for_ticket_price) ? $emp_policy->minimum_day_for_ticket_price : '' }}" id="minimum_day_for_ticket_price" class="form-control">
                                    </div> --}}



                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="minimun_vacation_priod" class="col-form-label"> Minimum Leave
                                                Period (Year) </label>
                                        <select  required name="minimun_vacation_priod" id="minimun_vacation_priod" class="form-control">
                                            <option value="">Select..</option>
                                            <option value="1" {{isset($emp_policy->minimun_vacation_priod)  && $emp_policy->minimun_vacation_priod == 1  ? 'selected' : '' }}>One Year</option>
                                            <option value="2" {{isset($emp_policy->minimun_vacation_priod)  && $emp_policy->minimun_vacation_priod == 2  ? 'selected' : '' }}>Two Year</option>
                                        </select>
                                    </div>
                                    <!-- Number of Yearly Vacations -->
                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="number_of_yearly_vacation" class="col-form-label">Total Leave (Days) per Year</label>
                                        <input required type="number" name="number_of_yearly_vacation"
                                            value="{{ isset($emp_policy->number_of_yearly_vacation) ? $emp_policy->number_of_yearly_vacation : '' }}"
                                            id="number_of_yearly_vacation" class="form-control inputFieldHeight" min="0">
                                    </div>
                                    {{--
                                    <div class="col-md-6">
                                        <label for="time_zone" class="col-form-label">Time Zone</label>
                                        <input required type="text" name="time_zone" value="{{isset($emp_policy->time_zone) ? $emp_policy->time_zone : '' }}" id="time_zone" class="form-control">
                                    </div> --}}
                                <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="basic_salary" class="col-form-label">Basic Salary</label>
                                        <input required type="text" name="basic_salary" value="{{ isset($emp_policy->basic_salary) ? $emp_policy->basic_salary : '' }}" id="basic_salary" class="form-control inputFieldHeight">
                                    </div>

                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="description" class="col-form-label">Description</label>
                                        <textarea name="description" id="description" class="form-control" rows="2">{{ isset($emp_policy->description) ? $emp_policy->description : '' }}</textarea>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="card pb-1 pt-1">
                                <div class="row mx-0">

                                    <!-- Ticket Price Percentage -->

                                    <!-- Maximum Time for Attendance ( Hours) -->
                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="maximum_time_for_attendace" class="col-form-label">Attendance Record Time (Min)</label>
                                        <input required type="number" name="maximum_time_for_attendace"
                                            value="{{ isset($emp_policy->maximum_time_for_attendace) ? $emp_policy->maximum_time_for_attendace : '' }}"
                                            id="maximum_time_for_attendace" class="form-control" >
                                    </div>

                                    <!-- Late Type -->
                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="late_type" class="col-form-label">Late Type</label>
                                        <select  required name="late_type" id="late_type" class="form-control">
                                            <option value="day" {{ isset($emp_policy->late_type) && $emp_policy->late_type == 'day' ? 'selected' : '' }}>Day</option>
                                            <option value="hours" {{ isset($emp_policy->late_type) && $emp_policy->late_type == 'hours' ? 'selected' : '' }}>Hours</option>
                                        </select>
                                    </div>

                                    <!-- Minimum Days for Late -->
                                    {{-- <div class="col-md-6">
                                        <label for="minimum_day_for_late" class="col-form-label">Minimum Days for Late</label>
                                        <input required type="number" name="minimum_day_for_late"
                                            value="{{ isset($emp_policy->minimum_day_for_late) ? $emp_policy->minimum_day_for_late : '' }}"
                                            id="minimum_day_for_late" class="form-control" min="0">
                                    </div> --}}

                                    <!-- Minimum Hours for Late -->
                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="minimum_hours_for_late" class="col-form-label">Minimum Allowable  Late Hrs</label>
                                        <input required type="number" name="minimum_hours_for_late"
                                            value="{{ isset($emp_policy->minimum_hours_for_late) ? $emp_policy->minimum_hours_for_late : '' }}"
                                            id="minimum_hours_for_late" class="form-control" min="0">
                                    </div>

                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="salary_loss" class="col-form-label">Salary Deduction Rate (%)</label>
                                        <input required type="number" step="0.01" name="salary_loss"
                                        value="{{ isset($emp_policy->salary_loss) ? $emp_policy->salary_loss : '' }}" id="salary_loss" class="form-control">
                                    </div>

                                    <!-- Apply Over Time -->
                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="cash_redeem" class="col-form-label">  Over Time Eligible </label>
                                        <select required name="cash_redeem" id="cash_redeem" class="form-control">
                                            <option value="">Select..</option>
                                            <option value="Yes" {{ isset($emp_policy->cash_redeem) && $emp_policy->cash_redeem == 'Yes' ? 'selected' : '' }}>Yes</option>
                                            <option value="No" {{ isset($emp_policy->cash_redeem) && $emp_policy->cash_redeem == 'No' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    <!-- Overtime Rate -->
                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                            <label for="overtime_rate" class="col-form-label">Overtime Rate (%)</label>
                                            <input required type="number" name="overtime_rate"
                                                value="{{ isset($emp_policy->overtime_rate) ? $emp_policy->overtime_rate : '' }}"
                                                id="overtime_rate" class="form-control" min="0" step="0.01">
                                    </div>

                                    <!-- Minimum Hours for Overtime -->
                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="min_hours_for_overtime" class="col-form-label">Minimum Hrs for OverTime</label>
                                        <input required type="number" name="min_hours_for_overtime"
                                            value="{{ isset($emp_policy->min_hours_for_overtime) ? $emp_policy->min_hours_for_overtime : '' }}"
                                            id="min_hours_for_overtime" class="form-control" min="0">
                                    </div>

                                    <!-- Late Grace Time -->
                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="late_grace_time" class="col-form-label">Late Grace Time (Min)</label>
                                        <input required type="number" name="late_grace_time"
                                            value="{{ isset($emp_policy->late_grace_time) ? $emp_policy->late_grace_time : '' }}"
                                            id="late_grace_time" class="form-control" min="0">
                                    </div>


                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="m_ref_in_time" class="col-form-label"> 1st  shift (Start) </label>
                                        <input required type="time" value="{{ isset($emp_policy->m_ref_in_time) ? $emp_policy->m_ref_in_time : '' }}" name="m_ref_in_time" id="m_ref_in_time" class="form-control morning_in_time" required>
                                    </div>

                                        <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="m_ref_out_time" class="col-form-label"> 1st shift (END) </label>
                                        <input required type="time" value="{{ isset($emp_policy->m_ref_out_time) ? $emp_policy->m_ref_out_time : '' }}" name="m_ref_out_time" id="m_ref_out_time" class="form-control morning_out_time" required>
                                    </div>

                                    <!-- Evening Reference Office In Time -->
                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="e_ref_in_time" class="col-form-label">2nd shift (Start)</label>
                                        <input required type="time" name="e_ref_in_time"
                                            value="{{ isset($emp_policy->e_ref_in_time) ? $emp_policy->e_ref_in_time : '' }}"
                                            id="e_ref_in_time" class="form-control evening_in_time" required>
                                    </div>

                                    <!-- Evening Reference Office Out Time -->
                                    <div class="form-group col-12 col-lg-6" style="padding: 0 5px;">
                                        <label for="e_ref_out_time" class="col-form-label">2nd shift (End)</label>
                                        <input required type="time" name="e_ref_out_time"
                                            value="{{ isset($emp_policy->e_ref_out_time) ? $emp_policy->e_ref_out_time : '' }}"
                                            id="e_ref_out_time" class="form-control e_ref_out_time evening_out_time" required>
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
                            <button type="button" class="btn keyboard-control-btn" data-tab="#bank-accountu-tab-edit" data-focus1=".bank_name"
                                style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                Prev
                            </button>

                            <button type="button" class="btn keyboard-control-btn" data-tab="#files-tab-edit" data-focus1=".employee_image" data-focus=".e_ref_out_time"
                                style="width: 85px; background:#475f7b; color:#fff; margin-top: 10px; font-size:20px;">
                                Next
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- FILES --}}
            <div class="tab-pane fade  " id="filesu" role="tabpanel" aria-labelledby="files-tab"
                data-prev="#policy-tab-edit" data-focus=".policy_type">
                {{-- file Information --}}
                <form action="{{route('employees.update', $employee_info->id)}}"  class="employee_send_form" method="post" data-id="#files-tab" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
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
                                @foreach ($document_lists as $item)
                                <tr>
                                    <td>
                                        {{$item->ext}}
                                    </td>

                                    <td>
                                        {{ucwords(str_replace('_', ' ', $item->type))}}
                                    </td>

                                    <td>
                                        <a href="{{ asset('storage/upload/other-documents/'.$item->filename) }}" target="_blank">
                                            <div class="profile-picture-box" style="padding-left:15px; position: relative;">
                                                <img class="preview" src="{{ asset('storage/upload/other-documents/'.$item->filename) }}" style="max-height: 50px; max-width: 80px; object-fit: contain; border: 1px solid #ccc; padding: 2px;">

                                                <div class="bigPreview" style="display:none; position:absolute; top:0; left:120px; z-index:1000; border:1px solid #ccc; background:#fff;">
                                                    <img src="{{ asset('storage/upload/other-documents/'.$item->filename) }}" id="bigPreviewImg" style="height:400px; width:400px;">
                                                </div>
                                            </div>
                                        </a>
                                    </td>

                                    <td class="text-right">
                                        <button type="button" class="delete-button other-document-delete"
                                                title="Delete" id="{{$item->id}}"
                                                style="padding: 2px 6px; font-size: 14px; background-color: #dc3545; color: white; border: none; border-radius: 3px;">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn" style="width: 85px; background:#007ACC; color:#fff; margin-top: 10px; font-size:20px;">
                            Save
                        </button>

                        <div>
                            <button type="button" class="btn keyboard-control-btn" data-tab="#policy-tab-edit" data-focus1=".policy_type"
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

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script type="text/javascript">
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 100
            });

            $('#summernote2').summernote({
                height: 100
            });
        });
            // $('#summernote').summernote({
            //     height: 200
            // });

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
            $('#employee_imageu2').change(function() {
                readURL(this, '#employee_image_previewu2');
            });
            $('#employee_imageu1').change(function() {
                readURL(this, '#employee_image_previewu1');
            });

            $('#emirates_imageu').change(function() {
                readURL(this, '#emirates_image_previewu');
            });

            $('#vissa_imageu').change(function() {
                readURL(this, '#vissa_image_previewu');
            });

            $('#_imageu').change(function() {
                readURL(this, '#_image_previewu');
            });
        });
</script>

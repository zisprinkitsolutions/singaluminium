@php

use App\Models\Payroll\Employee;
use App\Models\Payroll\EmployeeAttendance;
use App\Models\Payroll\EmployeePolicy;

$emirates=array('Abu Dhabi','Ajman','Dubai','Fujairah','Ras Al Khaimah','Sharjah','Umm Al Quwain');
$languages= array('Bangla','English','Urdu','Arabic','Hindi');
$employee_roles= array('Principle','Teacher', 'Admin', 'Accounts Executive',
'Librarian','Driver','Clerk','Cleaner','Secretary','Accountant','Trainer');
@endphp
@php
$company_tele = \App\Setting::where('config_name', 'company_tele')->first();
$company_email = \App\Setting::where('config_name', 'company_email')->first();
$facebook = \App\Setting::where('config_name', 'facebook')->first();
$instragram = \App\Setting::where('config_name', 'instragram')->first();
$youtube = \App\Setting::where('config_name', 'youtube')->first();
$web_link = \App\Setting::where('config_name', 'web_link')->first();
@endphp

<style>

    input[type=text],
    select,
    textarea {
        height: 2.5rem;
        font-size: 12px !important;

    }

    select.form-control:not([multiple]) {

        background-image: none !important;
    }
</style>
<section class="print-hideen border-bottom" style="padding: 10px;">
    <div class="row">
        <div class="col-6 pt-2 pl-2">
            <h5 style="font-family:Cambria;font-size: 2.3rem;"><b>View Employee Profile</b> </h5>
        </div>
        <div class="col-6">
            <div class="d-flex flex-row-reverse">
                <div class="mIconStyleChange">
                    <a href="#" class="close btn-icon btn btn-danger mIconStyleChange212" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true"><i class='bx bx-x'></i></span>
                    </a>
                </div>
                <div class="mIconStyleChange">
                    <a href="#" title="Print" onclick="handlePrintClick('modal-body-print', 0)"
                        class="btn btn-icon btn-info"><i class='bx bx-printer'></i> </a>
                </div>
                <div class="mIconStyleChange">
                    <a href="{{route('employees-delete',$employee_info->id)}}"
                        onclick="return  confirm('Are Youe Sure To delete It ?')" title="delete"
                        class="btn btn-icon btn-danger "><i class='bx bx-trash'></i></a>
                </div>
                <div class="mIconStyleChange">
                    <a data-id="{{ route('employees.edit',$employee_info->id) }}" title="Edit"
                        class="btn btn-icon btn-success employee-edit"><i class='bx bx-edit'></i></a>
                </div>
                @if ($employee_info->status != 1)
                <div class="mIconStyleChange">
                    <a href="{{ route('employees-approve',$employee_info->id) }}" title="Approve"
                        onclick="return confirm('Are Youe Sure To Approve It ?')" class="btn btn-icon btn-warning"><i
                            class='bx bx-check'></i></a>
                </div>
                @endif
            </div>

        </div>
</section>
<div class="modal-body" id="modal-body">
    <div class="row">
        <div class="col-2">
            <div class="row">
                <!-- Left Section: Employee Image, Name, and ID -->
                <div class="col-12 text-center ">
                    <a href="{{ asset('storage/upload/employee/'.$employee_info->employee_image) }}" target="_blank"
                        style="color:#000;">
                        <img src="{{ asset('storage/upload/employee/'.$employee_info->employee_image) }}"
                            class="img-fluid rounded-circle" style="width:150px; height:150px; object-fit: cover;"
                            alt="Employee Image">
                    </a>
                    <h6 style="font-size: 15px;color: #475f7b;font-weight:900" class="mt-1">{{ $employee_info->full_name }}</h6>
                    <h6 style="font-size: 14px;color: #475f7b;font-weight:900">  {{ $employee_info->job_title }}</h6>

                    <p style="font-size: 14px;color: #475f7b;font-weight:900"> ID: {{ $employee_info->emp_id }}</p>
                </div>

                <!-- Right Section: Employee Details in Two Columns -->
                <div class="col-12">
                    <div class="row">
                        <!-- Column Titles (Left) -->
                        <div class="col-12">
                            <label for="mode" style="font-weight:900">First Name : <span class="text-right"> {{
                                    $employee_info->first_name }}</span></label>
                            <label for="mode" style="font-weight:900">DOB :{{ date('d/m/Y',
                                strtotime($employee_info->dob)) }}</label>
                            <label for="mode" style="font-weight:900">Gender : {{ $employee_info->gender == 'Male' ?
                                'Male' : 'Female' }}</label>
                            <label for="mode" style="font-weight:900">Blood Group : {{ $employee_info->blood_group}}</label>
                            {{-- <label for="mode" class="font-weight-bold">Marital Status : {{
                                $employee_info->marital_status == 'Married' ? 'Married' : 'Single' }}</label> --}}
                        </div>
                        <a href="#" title="Print" onclick="handlePrintClick('id-card-print', 0 , 0)"
                            class="btn btn-icon btn-info"><i class='bx bx-printer'></i> Print ID Card</a>

                    </div>
                </div>
            </div>



        </div>
        <div class="col-10">
            <div class="card-body" style="padding: 0px;">
                <ul class="nav nav-tabs nav-tabs1" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" style="padding: 7px !important;" id="personal-info-tabv"
                            data-toggle="tab" href="#personal-infov" role="tab" aria-controls="personal-infov"
                            aria-selected="false">Personal Info</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link " style="padding: 7px !important;" id="contact-info-tabv" data-toggle="tab"
                            href="#contact-infov" role="tab" aria-controls="contact-infov"
                            aria-selected="false">Emergency Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " style="padding: 7px !important;" id="job-info-tabv" data-toggle="tab"
                            href="#job-infov" role="tab" aria-controls="job-infov" aria-selected="false">Job Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="bank-account-tabv" data-toggle="tab" href="#bank-accountv" role="tab" aria-controls="bank-accountv" aria-selected="false">Bank Account</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  " style="padding: 7px !important;" id="policy-tabv" data-toggle="tab"
                            href="#policyv" role="tab" aria-controls="policyv" aria-selected="true">Policy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  " style="padding: 7px !important;" id="vacation-tab" data-toggle="tab"
                            href="#vacation" role="tab" aria-controls="vacation" aria-selected="true">Vacation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  " style="padding: 7px !important;" id="attendance-tab" data-toggle="tab"
                            href="#attendance" role="tab" aria-controls="attendance" aria-selected="true">Attendance</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  " style="padding: 7px !important;" id="files-tabv" data-toggle="tab"
                            href="#filesv" role="tab" aria-controls="filesv" aria-selected="true">Document</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  " style="padding: 7px !important;" id="notice-tab" data-toggle="tab"
                            href="#notice" role="tab" aria-controls="notice" aria-selected="true">Notice</a>
                    </li>
                </ul>

                <div class="tab-content tab-content1" id="myTabContent">
                    {{-- PERSONAL INFORMATION --}}

                    <div class="tab-pane fade show active" id="personal-infov" role="tabpanel"
                        aria-labelledby="personal-info-tabv">
                        {{-- Personal Information --}}
                        <div class="row">
                            <div class="col-md-12 col-12 changeColStyle pl-1">
                                <h5 style="color: #475f7b;margin-top: 0.5rem;;font-size:19px; margin-left:4px;">PERSONAL
                                    INFORMATION</h5>
                            </div>
                            <div class=" col-12 col-sm-6 col-md-6 ">
                                <div class="card pb-1 pt-1">
                                    <div class="row mx-0">
                                        <div class="col-md-6">
                                            <label for="mode">First Name <sup class="text-danger">*</sup></label>
                                            <input readonly type="text" class="form-control inputFieldHeight"
                                                name="first_name" id="first_name" value="{{$employee_info->first_name}}"
                                                onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32) || (event.charCode == 46))  '
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode">Middle Name <sup class="text-danger">*</sup></label>
                                            <input readonly type="text" class="form-control inputFieldHeight"
                                                name="middle_name" id="middle_name"
                                                value="{{$employee_info->middle_name}}"
                                                onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32) || (event.charCode == 46))  '
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode">Last Name<sup class="text-danger">*</sup></label>
                                            <input readonly type="text" class="form-control inputFieldHeight"
                                                name="last_name" id="last_name" value="{{$employee_info->last_name}}"
                                                onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32) || (event.charCode == 46))  '
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode">Date of Birth<sup class="text-danger">*</sup></label>
                                            <input readonly type="text" autocomplete="off"
                                                class="form-control inputFieldHeight  datepicker"
                                                value="{{date('d/m/Y', strtotime($employee_info->dob))}}"
                                                placeholder="DD/MM/YY" name="dob" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode">Gender<sup class="text-danger">*</sup></label>
                                            <select name="gender"
                                                class="inputFieldHeight form-control  @error('salutation') error @enderror"
                                                id="" required>
                                                <option value="Male" {{$employee_info->gender =='Male' ? 'selected' : ''
                                                    }}> Male</option>
                                                <option value="Female" {{$employee_info->gender =='Female' ? 'selected'
                                                    : '' }}> Female</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode"> Nationality<sup class="text-danger">*</sup></label>
                                            <input readonly type="text" value="{{$employee_info->nationality}}"
                                                class="form-control inputFieldHeight " name="nationality"
                                                id="nationality" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode">Address (IN UAE) <sup class="text-danger">*</sup></label>
                                            <input readonly type="text" value="{{$employee_info->parmanent_address}}"
                                                class="form-control inputFieldHeight" name="parmanent_address"
                                                id="present_address" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode">Blood Group <sup class="text-danger">*</sup></label>
                                            <input readonly type="text" value="{{$employee_info->blood_group}}"
                                                class="form-control inputFieldHeight" name="blood_group"
                                                id="blood_group" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" col-12 col-sm-6 col-md-6 ">
                                <div class="card pb-1 pt-1">
                                    <div class="row mx-0">
                                        <div class="col-md-6">
                                            <label for="mode">Email<sup class="text-danger">*</sup></label>
                                            <input readonly type="email" value="{{$employee_info->email}}"
                                                class="form-control inputFieldHeight" name="email" id="email" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode">Tel Number<sup class="text-danger">*</sup></label>
                                            <input readonly type="text" value="{{$employee_info->contact_number}}"
                                                class="form-control inputFieldHeight" name="contact_number"
                                                id="contact_number" required>
                                        </div>

                                        <div class="col-6">
                                            <label for="mode">No. of kids </label>
                                            <input style="background-color: #F2F4F4; !important" readonly type="text"
                                                value="{{$employee_info->kids_no}}"
                                                class="form-control inputFieldHeight" style="border:none" readonly
                                                name="kids_no" id="kids_no" readonly>
                                        </div>
                                        <div class="col-6">
                                            <label for="mode">Joining Date </label>
                                            <input style="background-color: #F2F4F4; !important" type="text" readonly
                                                value="{{date('d/m/Y', strtotime($employee_info->joining_date))}}"
                                                class="form-control inputFieldHeight " autocomplete="off"
                                                name="joining_date" style="font-size:12px; border:none"
                                                placeholder="DD/MM/YY" readonly>
                                        </div>
                                        <div class="col-6">
                                            <label for="mode">Current Date</label>
                                            <input style="background-color: #F2F4F4; !important" readonly type="text"
                                                value="{{date('d/m/Y')}}" class="form-control inputFieldHeight"
                                                style="border:none" readonly name="health_status" id="health_status"
                                                readonly>
                                        </div>
                                        <div class="col-6">
                                            @php
                                            $joiningDate = new DateTime($employee_info->joining_date);
                                            $currentDate = new DateTime();

                                            $interval = $joiningDate->diff($currentDate);

                                            $years = $interval->y;
                                            $months = $interval->m;
                                            $days = $interval->d;
                                            @endphp
                                            <label for="mode">NO Of Year</label>
                                            <input style="background-color: #F2F4F4; !important" readonly type="text"
                                                value="{{ $years }} years, {{ $months }} months, and {{ $days }} days."
                                                class="form-control inputFieldHeight" style="border:none" readonly
                                                name="health_status" id="health_status">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- CONTACT INFORMATION --}}
                    <div class="tab-pane fade" id="contact-infov" role="tabpanel" aria-labelledby="contact-info-tabv">
                        {{-- contact Information --}}
                        <div class="row">
                            <div class="col-md-12 col-12 changeColStyle pl-1">
                                <h5 style="color: #475f7b;margin-top: 0.5rem;;font-size:19px; margin-left:4px">EMERGENCY
                                    CONTACT</h5>
                            </div>
                            <div class=" col-12 col-sm-6 col-6 ">
                                <div class="card pb-1 pt-1">
                                    <div class="row mx-0">

                                        <div class="col-md-6">
                                            <label for="mode"> Name (Local) <sup class="text-danger">*</sup></label>
                                            <input style="background-color: #F2F4F4; !important" type="text"
                                                value="{{$employee_info->em_name_local}}"
                                                class="form-control inputFieldHeight" name="em_name_local"
                                                id="em_name_local" readonly>
                                        </div>
                                        <div class="col-md-6">

                                            <label for="mode">Email Address(Local)<sup
                                                    class="text-danger">*</sup></label>
                                            <input style="background-color: #F2F4F4; !important" type="email"
                                                value="{{$employee_info->em_email_local}}"
                                                class="form-control inputFieldHeight" name="em_email_local"
                                                id="em_email_local" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode"> Phone Number ( Local )<sup
                                                    class="text-danger">*</sup></label>
                                            <input style="background-color: #F2F4F4; !important" type="text"
                                                value="{{$employee_info->em_contact_number_local}}"
                                                class="form-control inputFieldHeight" name="em_contact_number_local"
                                                readonly>
                                        </div>


                                        <div class="col-md-6">
                                            <label for="mode">City (Local)<sup class="text-danger">*</sup></label>
                                            <input style="background-color: #F2F4F4; !important" type="text"
                                                value="{{$employee_info->em_city_local}}"
                                                class="form-control inputFieldHeight " name="em_city_local"
                                                id="em_city_local" readonly>

                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode"> Country (Local)<sup class="text-danger">*</sup></label>
                                            <select style="background-color: #F2F4F4; !important"
                                                name="em_country_local" id="em_country_local"
                                                class="form-control common-select2 errorr-abcd"
                                                style="width: 100% !important" readonly>
                                                <option value="">Select ...</option>
                                                @foreach ($countries as $country)
                                                <option value="{{$country->id}}" {{$country->id ==
                                                    $employee_info->em_country_local ?
                                                    'selected':''}}>{{$country->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode">Address (Local)<sup class="text-danger">*</sup></label>
                                            <input style="background-color: #F2F4F4; !important" type="text"
                                                value="{{$employee_info->em_parmanent_address_local}}"
                                                class="form-control inputFieldHeight " name="em_parmanent_address_local"
                                                id="em_parmanent_address_local" readonly>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class=" col-12 col-sm-6 col-6 ">
                                <div class="card pb-1 pt-1" style="height: 197px;">
                                    <div class="row mx-0">
                                        <div class="col-md-6">
                                            <label for="mode"> Name (Origin) <sup class="text-danger">*</sup></label>
                                            <input style="background-color: #F2F4F4; !important" type="text"
                                                value="{{$employee_info->em_name_origin}}"
                                                class="form-control inputFieldHeight" name="em_name_origin"
                                                id="em_name_origin" readonly>
                                        </div>
                                        <div class="col-md-6">

                                            <label for="mode">Email Address(Origin)<sup
                                                    class="text-danger">*</sup></label>
                                            <input style="background-color: #F2F4F4; !important" type="email"
                                                value="{{$employee_info->em_email_origin}}"
                                                class="form-control inputFieldHeight" name="em_email_origin"
                                                id="em_email_origin" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode"> Phone Number ( Origin )<sup
                                                    class="text-danger">*</sup></label>
                                            <input style="background-color: #F2F4F4; !important" type="text"
                                                value="{{$employee_info->em_contact_number_origin}}"
                                                class="form-control inputFieldHeight" name="em_contact_number_origin"
                                                readonly>
                                        </div>


                                        <div class="col-md-6">
                                            <label for="mode">City (Origin)<sup class="text-danger">*</sup></label>
                                            <input style="background-color: #F2F4F4; !important" type="text"
                                                value="{{$employee_info->em_city_origin}}"
                                                class="form-control inputFieldHeight " name="em_city_origin"
                                                id="em_city_origin" readonly>

                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode"> Country (Origin)<sup class="text-danger">*</sup></label>
                                            <select style="background-color: #F2F4F4; !important"
                                                name="em_country_origin" id="em_country_origin"
                                                class="form-control common-select2 errorr-abcd"
                                                style="width: 100% !important" readonly>
                                                <option value="">Select ...</option>
                                                @foreach ($countries as $country)
                                                <option value="{{$country->id}}" {{$country->id ==
                                                    $employee_info->em_country_origin ?
                                                    'selected':''}}>{{$country->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode">Address (Origin)<sup class="text-danger">*</sup></label>
                                            <input style="background-color: #F2F4F4; !important" type="text"
                                                value="{{$employee_info->em_parmanent_address_origin}}"
                                                class="form-control inputFieldHeight "
                                                name="em_parmanent_address_origin" id="em_parmanent_address_local"
                                                readonly>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- JOB INFORMATION --}}
                    <div class="tab-pane fade" id="job-infov" role="tabpanel" aria-labelledby="job-info-tabv">
                        <div class="row">
                            <div class="col-md-12 col-12 changeColStyle pl-1">
                                <h5 style="color: #475f7b;margin-top: 0.5rem;;font-size:19px; margin-left:4px">JOB
                                    INFORMATION </h5>
                            </div>
                            <div class=" col-12 col-sm-6 col-md-6 ">
                                <div class="card pb-1 pt-1">
                                    <div class="row mx-0">
                                        <div class="col-md-6">
                                            <label for="mode">JOB TITLE</label>
                                            <input readonly type="text" value="{{$employee_info->job_title}}"
                                                class="form-control inputFieldHeight" style="border:none" readonly
                                                name="job_title" id="job_title" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode"> JOB DESCRIPTION</label>
                                            <input readonly type="text" value="{{$employee_info->job_description}}"
                                                class="form-control inputFieldHeight" style="border:none" readonly
                                                name="job_description" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode">Visa Issue</label>
                                            <input readonly type="text"
                                                value="{{date('d/m/Y', strtotime($employee_info->vissa_issue))}}"
                                                autocomplete="off" class="form-control inputFieldHeight "
                                                style="border:none" readonly placeholder="DD/MM/YY" name="vissa_issue"
                                                readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode">Passport Number</label>
                                            <input readonly type="text" value="{{$employee_info->passport_number}}"
                                                class="form-control inputFieldHeight" style="border:none" readonly
                                                name="passport_number" id="passport_number" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode">Visa Expiriy</label>
                                            <input readonly type="text"
                                                value="{{date('d/m/Y', strtotime($employee_info->visa_expiry_date))}}"
                                                autocomplete="off" class="form-control inputFieldHeight "
                                                style="border:none" readonly placeholder="DD/MM/YY"
                                                name="visa_expiry_date" readonly>

                                        </div>
                                        <div class="col-md-6">
                                            <label for="last_visite" class="col-form-label">Last Visite Date</label>
                                            <input type="text" autocomplete="off" placeholder="DD/MM/YYY"
                                                name="last_visite"
                                                value="{{date('d/m/Y', strtotime($employee_info->last_visite))}}"
                                                readonly id="last_visite" class="form-control datepicker">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class=" col-12 col-sm-6 col-md-6 ">
                                <div class="card pb-1 pt-1" style="height: 197px;">
                                    <div class="row mx-0">
                                        <div class="col-md-6">
                                            <label for="mode">Department </label>

                                            <select readonly name="division" id="division"
                                                class="form-control common-select2 errorr-abcd"
                                                style="width: 100% !important; border:none" readonly>
                                                <option value="">Select ...</option>
                                                @foreach ($divisions as $division)
                                                <option value="{{$division->id}}" {{$division->id ==
                                                    $employee_info->division ? 'selected':'' }}>{{$division->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- <div class="col-md-6">
                                            <label for="mode">DESIGNATION </label>

                                            <select readonly name="department" id="department"
                                                class="form-control common-select2"
                                                style="width: 100% !important; border:none" readonly>
                                                <option value="">Select ...</option>
                                                @foreach ($departments as $department)
                                                <option value="{{$department->id}}" {{$department->id ==
                                                    $employee_info->department ? 'selected':'' }}>{{$department->name}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                        <div class="col-md-6">
                                            <label for="mode">Joining Date </label>
                                            <input readonly type="text"
                                                value="{{date('d/m/Y', strtotime($employee_info->joining_date))}}"
                                                class="form-control inputFieldHeight " autocomplete="off"
                                                name="joining_date" style="font-size:12px; border:none"
                                                placeholder="DD/MM/YY" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode" style="white-space: nowrap;">Job Status <sup
                                                    class="text-danger">*</sup></label>

                                            <select readonly name="job_type" id="job_type"
                                                class="form-control common-select2"
                                                style="width: 100% !important; border:none" readonly>
                                                <option value="">Select...</option>
                                                @foreach ($job_types as $job_type)
                                                <option value="{{$job_type->id}}">{{$job_type->type}}</option>
                                                @endforeach

                                            </select>
                                        </div>

                                        {{-- <div class="col-md-6">
                                            <label for="mode">Employee Basic Salary <sup class="text-danger">*</sup></label>
                                            <input type="text" value="{{$employee_info->basic_salary}}" class="form-control inputFieldHeight " readonly name="basic_salary" style="font-size:12px"  required>
                                        </div> --}}

                                        <div class="col-md-6">
                                            <label for="mode" style="white-space: nowrap;">Job Active Status <sup
                                                    class="text-danger">*</sup></label>

                                            <select readonly name="job_status" id="job_type"
                                                class="form-control common-select2"
                                                style="width: 100% !important; border:none" readonly>
                                                <option value="1" {{"1"==$employee_info->job_status ? 'selected':''
                                                    }}>Active</option>
                                                <option value="0" {{"0"==$employee_info->job_status ? 'selected':''
                                                    }}>Inactive</option>

                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                      {{-- bank-account --}}
                    <div class="tab-pane fade" id="bank-accountv" role="tabpanel" aria-labelledby="bank-account-tabv">
                        <form action="{{route('employees.store')}}" class="employee_send_form" method="post" data-id="#policy-tab" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" class="employee_id" name="employee_id" id="">

                        <div class="row">
                            <div class="col-md-12 col-12 changeColStyle pl-1">
                                <h5 style="color: #475f7b;margin-top: 0.5rem;;font-size:19px; margin-left:4px">Bank Account </h5>
                            </div>
                            <div class=" col-12 col-sm-6 col-md-6 ">
                                <div class="card pb-1 pt-1">
                                    <div class="row mx-0">
                                        <div class="col-md-6">
                                            <label for="mode">BANK NAME<sup class="text-danger">*</sup></label>
                                            <input type="text" value="{{$employee_info->bank_name}}" class="form-control inputFieldHeight" name="bank_name" readonly id="bank_name"required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode"> BRANCH NAME<sup class="text-danger">*</sup></label>
                                            <input type="text" value="{{$employee_info->branch_name}}" class="form-control inputFieldHeight" readonly name="branch_name"required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode">ACCOUNT NUMBER<sup class="text-danger">*</sup></label>
                                            <input type="text" value="{{$employee_info->account_number}}"  class="form-control inputFieldHeight " readonly  name="account_number" required>
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
                                        <div class="col-md-6">
                                            <label for="mode">ACCOUNT NAME<sup class="text-danger">*</sup></label>
                                            <input type="text"  value="{{$employee_info->account_name}}"class="form-control inputFieldHeight " name="account_name" readonly id="account_name"required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode">IBAN NUMBER<sup class="text-danger">*</sup></label>
                                            <input type="text"  value="{{$employee_info->ibal_number}}"class="form-control inputFieldHeight " readonly  name="ibal_number" required>

                                        </div>
                                        <div class="col-md-6">
                                            <label for="mode">ACCOUNT TYPE<sup class="text-danger">*</sup></label>
                                            <input type="text"  value="{{$employee_info->account_type}}"class="form-control inputFieldHeight " readonly  name="account_type" required>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="submit"  value=" Employee Save" class="form-control btn btn-success" style=" width: 160px; float: right; margin-top: 10px;">
                    </form>
                    </div>
                    {{-- policy INFORMATION --}}
                    <div class="tab-pane fade" id="policyv" role="tabpanel" aria-labelledby="policy-tabv">
                        <div class="row">
                            <div class="col-md-12 col-12 changeColStyle pl-1">
                                <h5 style="color: #475f7b;margin-top: 0.5rem;;font-size:19px; margin-left:4px">POLICY
                                    INFORMATION </h5>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6">
                                <div class="card pb-1 pt-1">
                                    <div class="row mx-0">

                                        <div class="col-md-6">
                                            <label for="policy_type" class="col-form-label">Policy Type</label>
                                            <select readonly name="policy_type" id="policy_typeu"
                                                class="form-control policy_typeu">
                                                <option value="">Select..</option>
                                                <option value="Custom" {{ (isset($emp_policy->policy_type) &&
                                                    $emp_policy->policy_type == 'Custom') ? 'selected' : '' }}>Custom
                                                </option>
                                                <option value="Default" {{ (isset($emp_policy->policy_type) &&
                                                    $emp_policy->policy_type == 'Default') ? 'selected' : '' }}>Default
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="effect_date" class="col-form-label">Effective Date</label>
                                            <input readonly type="text" placeholder="DD/MM/YYYY"
                                                value="{{ isset($emp_policy->effect_date) ? date('d/m/Y', strtotime($emp_policy->effect_date)) : '' }}"
                                                name="effect_date" class="form-control datepicker">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="air_ticket_eligibility" class="col-form-label">Air Ticket
                                                (Yes/No)</label>
                                            <select readonly name="air_ticket_eligibility" id="air_ticket_eligibility"
                                                class="form-control">
                                                {{-- <option value="">Select..</option> --}}
                                                <option value="Yes" {{ (isset($emp_policy->air_ticket_eligibility) &&
                                                    $emp_policy->air_ticket_eligibility == 'Yes') ? 'selected' : ''
                                                    }}>Yes</option>
                                                {{-- <option value="No" {{ (isset($emp_policy->air_ticket_eligibility)
                                                    && $emp_policy->air_ticket_eligibility == 'No') ? 'selected' : ''
                                                    }}>No</option> --}}
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="apply_over_time" class="col-form-label">Cash Redeem</label>
                                            <select readonly name="apply_over_time" id="apply_over_time"
                                                class="form-control">
                                                {{-- <option value="">Select..</option> --}}
                                                <option value="Yes" {{ (isset($emp_policy->apply_over_time) &&
                                                    $emp_policy->apply_over_time == 'Yes') ? 'selected' : '' }}>Yes
                                                </option>
                                                {{-- <option value="No" {{ (isset($emp_policy->apply_over_time) &&
                                                    $emp_policy->apply_over_time == 'No') ? 'selected' : '' }}>No
                                                </option> --}}
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="vacation_paid_or_unpaid" class="col-form-label">Vacation Paid
                                                Type</label>
                                            <select readonly name="vacation_paid_or_unpaid" id="vacation_paid_or_unpaid"
                                                class="form-control">
                                                {{-- <option value="">Select..</option> --}}
                                                <option value="Paid" {{ (isset($emp_policy->vacation_paid_or_unpaid) &&
                                                    $emp_policy->vacation_paid_or_unpaid == 'Paid') ? 'selected' : ''
                                                    }}>Paid</option>
                                                {{-- <option value="Unpaid" {{ (isset($emp_policy->
                                                    vacation_paid_or_unpaid) && $emp_policy->vacation_paid_or_unpaid ==
                                                    'Unpaid') ? 'selected' : '' }}>Unpaid</option> --}}
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="vacation_type" class="col-form-label">Leave Type</label>
                                            <select readonly name="vacation_type" id="vacation_type"
                                                class="form-control">
                                                {{-- <option value="">Select..</option> --}}
                                                <option value="Fixed Period" {{ (isset($emp_policy->vacation_type) &&
                                                    $emp_policy->vacation_type == 'Fixed Period') ? 'selected' : ''
                                                    }}>Fixed Period</option>
                                                {{-- <option value="Flexible Period" {{ (isset($emp_policy->
                                                    vacation_type) && $emp_policy->vacation_type == 'Flexible Period') ?
                                                    'selected' : '' }}>Flexible Period</option> --}}
                                            </select>
                                        </div>
                                        {{--
                                        <div class="col-md-6">
                                            <label for="minimum_day_for_ticket_price" class="col-form-label">Minimum
                                                Days for Ticket Price</label>
                                            <input readonly type="number" name="minimum_day_for_ticket_price"
                                                value="{{ isset($emp_policy->minimum_day_for_ticket_price) ? $emp_policy->minimum_day_for_ticket_price : '' }}"
                                                id="minimum_day_for_ticket_price" class="form-control">
                                        </div> --}}
                                        <!-- Ticket Price Percentage -->
                                        <div class="col-md-6">
                                            <label for="ticket_price_percentage" class="col-form-label">Ticket allowance
                                                (Cash)</label>
                                            <input readonly type="number" name="ticket_price_percentage"
                                                value="{{ isset($emp_policy->ticket_price_percentage) ? $emp_policy->ticket_price_percentage : '' }}"
                                                id="ticket_price_percentage" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="minimun_vacation_priod" class="col-form-label">Minimum Leave
                                                Period (Year)</label>
                                            <input required type="number" name="minimun_vacation_priod"
                                                value="{{isset($emp_policy->minimun_vacation_priod) ? $emp_policy->minimun_vacation_priod : '' }}"
                                                id="minimun_vacation_priod" class="form-control">
                                        </div>
                                        <!-- Number of Yearly Vacations -->
                                        <div class="col-md-6">
                                            <label for="number_of_yearly_vacation" class="col-form-label">Number of
                                                Yearly Leave</label>
                                            <input readonly type="number" name="number_of_yearly_vacation"
                                                value="{{ isset($emp_policy->number_of_yearly_vacation) ? $emp_policy->number_of_yearly_vacation : '' }}"
                                                id="number_of_yearly_vacation" class="form-control" min="0">
                                        </div>
                                        {{--
                                        <div class="col-md-6">
                                            <label for="time_zone" class="col-form-label">Time Zone</label>
                                            <input readonly type="text" name="time_zone"
                                                value="{{ isset($emp_policy->time_zone) ? $emp_policy->time_zone : '' }}"
                                                id="time_zone" class="form-control">
                                        </div> --}}
                                        <div class="col-md-6">
                                            <label for="basic_salary" class="col-form-label">Basic Salary</label>
                                            <input required readonly type="text" name="basic_salary" value="{{ isset($emp_policy->basic_salary) ? $emp_policy->basic_salary : '' }}" id="basic_salary" class="form-control">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="description" class="col-form-label">Description</label>
                                            <textarea name="description" id="description" class="form-control"
                                                rows="3">{{ isset($emp_policy->description) ? $emp_policy->description : '' }}</textarea>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-6">
                                <div class="card pb-1 pt-1">
                                    <div class="row mx-0">



                                        <!-- Maximum Time for Attendance (Hours) -->
                                        <div class="col-md-6">
                                            <label for="maximum_time_for_attendace" class="col-form-label">Maximum Time
                                                for Attendance (Minutes)</label>
                                            <input readonly type="number" name="maximum_time_for_attendace"
                                                value="{{ isset($emp_policy->maximum_time_for_attendace) ? $emp_policy->maximum_time_for_attendace : '' }}"
                                                id="maximum_time_for_attendace" class="form-control">
                                        </div>

                                        <!-- Late Type -->
                                        <div class="col-md-6">
                                            <label for="late_type" class="col-form-label">Late Type</label>
                                            <select readonly name="late_type" id="late_type" class="form-control">
                                                {{-- <option value="day" {{ isset($emp_policy->late_type) &&
                                                    $emp_policy->late_type == 'Day' ? 'selected' : '' }}>Day</option>
                                                --}}
                                                <option value="hours" {{ isset($emp_policy->late_type) &&
                                                    $emp_policy->late_type == 'Hours' ? 'selected' : '' }}>Hours
                                                </option>
                                            </select>
                                        </div>

                                        <!-- Minimum Days for Late -->
                                        {{-- <div class="col-md-6">
                                            <label for="minimum_day_for_late" class="col-form-label">Minimum Days for
                                                Late</label>
                                            <input readonly type="number" name="minimum_day_for_late"
                                                value="{{ isset($emp_policy->minimum_day_for_late) ? $emp_policy->minimum_day_for_late : '' }}"
                                                id="minimum_day_for_late" class="form-control" min="0">
                                        </div> --}}

                                        <!-- Minimum Hours for Late -->
                                        <div class="col-md-6">
                                            <label for="minimum_hours_for_late" class="col-form-label">Minimum Hours for
                                                Late</label>
                                            <input readonly type="number" name="minimum_hours_for_late"
                                                value="{{ isset($emp_policy->minimum_hours_for_late) ? $emp_policy->minimum_hours_for_late : '' }}"
                                                id="minimum_hours_for_late" class="form-control" min="0">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="salary_loss" class="col-form-label">Salary Loss Rate ( Basic
                                                salary percentanbe of day)</label>
                                            <input readonly type="number" step="0.01" name="salary_loss"
                                                value="{{ isset($emp_policy->salary_loss) ? $emp_policy->salary_loss : '' }}"
                                                id="salary_loss" class="form-control">
                                        </div>
                                        <!-- Apply Over Time -->
                                        <div class="col-md-6">
                                            <label for="cash_redeem" class="col-form-label">Apply Over Time</label>
                                            <select readonly name="cash_redeem" id="cash_redeem" class="form-control">
                                                <option value="">Select..</option>
                                                <option value="Yes" {{ isset($emp_policy->cash_redeem) &&
                                                    $emp_policy->cash_redeem == 'Yes' ? 'selected' : '' }}>Yes</option>
                                                <option value="No" {{ isset($emp_policy->cash_redeem) &&
                                                    $emp_policy->cash_redeem == 'No' ? 'selected' : '' }}>No</option>
                                            </select>
                                        </div>

                                        <!-- Overtime Rate (Percentage) -->
                                        <div class="col-md-6">
                                            <label for="overtime_rate" class="col-form-label">Overtime Rate
                                                (Percentage)</label>
                                            <input readonly type="number" name="overtime_rate"
                                                value="{{ isset($emp_policy->overtime_rate) ? $emp_policy->overtime_rate : '' }}"
                                                id="overtime_rate" class="form-control" min="0" step="0.01">
                                        </div>

                                        <!-- Minimum Hours for Overtime -->
                                        <div class="col-md-6">
                                            <label for="min_hours_for_overtime" class="col-form-label">Minimum Hours for
                                                Overtime</label>
                                            <input readonly type="number" name="min_hours_for_overtime"
                                                value="{{ isset($emp_policy->min_hours_for_overtime) ? $emp_policy->min_hours_for_overtime : '' }}"
                                                id="min_hours_for_overtime" class="form-control" min="0">
                                        </div>

                                        <!-- Late Grace Time (Minutes) -->
                                        <div class="col-md-6">
                                            <label for="late_grace_time" class="col-form-label">Late Grace Time
                                                (Minutes)</label>
                                            <input readonly type="number" name="late_grace_time"
                                                value="{{ isset($emp_policy->late_grace_time) ? $emp_policy->late_grace_time : '' }}"
                                                id="late_grace_time" class="form-control" min="0">
                                        </div>


                                        <div class="col-md-6">
                                            <label for="m_ref_in_time" class="col-form-label">Morning Reference Office
                                                In Time</label>
                                            <input readonly type="time" name="m_ref_in_time"
                                                value="{{ isset($emp_policy->m_ref_in_time) ? $emp_policy->m_ref_in_time : '' }}"
                                                id="m_ref_in_time" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="m_ref_out_time" class="col-form-label">Morning Reference Office
                                                Out Time</label>
                                            <input readonly type="time" name="m_ref_out_time"
                                                value="{{ isset($emp_policy->m_ref_out_time) ? $emp_policy->m_ref_out_time : '' }}"
                                                id="m_ref_out_time" class="form-control">
                                        </div>
                                        <!-- Evening Reference Office In Time -->
                                        <div class="col-md-6">
                                            <label for="e_ref_in_time" class="col-form-label">Evening Reference Office
                                                In Time</label>
                                            <input readonly type="time" name="e_ref_in_time"
                                                value="{{ isset($emp_policy->e_ref_in_time) ? $emp_policy->e_ref_in_time : '' }}"
                                                id="e_ref_in_time" class="form-control">
                                        </div>

                                        <!-- Evening Reference Office Out Time -->
                                        <div class="col-md-6">
                                            <label for="e_ref_out_time" class="col-form-label">Evening Reference Office
                                                Out Time</label>
                                            <input readonly type="time" name="e_ref_out_time"
                                                value="{{ isset($emp_policy->e_ref_out_time) ? $emp_policy->e_ref_out_time : '' }}"
                                                id="e_ref_out_time" class="form-control">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    {{-- vacation information--}}
                    <div class="tab-pane fade  " id="vacation" role="tabpanel" aria-labelledby="vacation-tab">
                        {{-- attendance Information --}}
                        <div class="row">

                            <div class="col-12 col-sm-12 col-md-12">
                                <h5 style="color: #000;margin: 0.5rem;;font-size:19px">Vacation Information </h5>

                                <div class="card p-1">
                                    @php
                                    use Carbon\Carbon;
                                    $employee = Employee::where('emp_id', $employee_info->emp_id)->first();
                                    if ($employee) {
                                    // Get the most recent EmployeePolicy based on the effective date
                                        $date_ = date('Y-m-d');
                                        $emp_policy = policy_helper($employee_info->emp_id,$date_);
                                        $total_leave = \App\Models\Payroll\EmployeeLeaveApplication::where('employee_id', $employee->id)->whereYear('start_date', date('Y'))->sum('leave_day');

                                        $last_visit = $employee->last_visite ? $employee->last_visite : $employee->joining_date;
                                        $remaining_vacation = $employee->remaining_vacation;

                                        // Calculate the next visit based on policy type
                                        if ($emp_policy && $emp_policy->vacation_type == 'Fixed Period') {
                                        $last =  Carbon::parse($last_visit);
                                        $period = $emp_policy->minimun_vacation_priod;
                                        $next_visit = $last->copy()->addYears($period);
                                        }
                                        }

                                        @endphp
                                        {{-- @dd($total_leave) --}}

                                        <div class="table-responsive" id="employee_table_data"
                                            style="min-height: 300px;">
                                            <div class="list-group">
                                                @if(isset($employee))

                                                <div class="list-group-item">
                                                    <strong>Last Visit Date:</strong> {{ isset($last) ? date('d/m/Y',
                                                    strtotime($last)) : 'N/A' }}
                                                </div>
                                                <div class="list-group-item">

                                                    <strong>Available Leave:   @if($emp_policy)</strong>{{ $emp_policy->number_of_yearly_vacation && $total_leave
                                                        ? $emp_policy->number_of_yearly_vacation - $total_leave
                                                        : $emp_policy->number_of_yearly_vacation  }}
                                                        @else
                                                        'N/A'
                                                        @endif

                                                </div>
                                                <div class="list-group-item">
                                                    <strong>Next Vacation Date:</strong> {{ isset($next_visit) ?
                                                    date('d/m/Y', strtotime($next_visit)) : 'N/A' }}
                                                </div>

                                                <div class="list-group-item">
                                                    <strong>Time Until Next Vacation:</strong>
                                                    @if(isset($next_visit))
                                                    @php
                                                    $nextVisitDate = Carbon::parse($next_visit);
                                                    $currentDate = Carbon::now();
                                                    $diffYears = $currentDate->diffInYears($nextVisitDate);
                                                    $diffMonths =
                                                    $currentDate->copy()->addYears($diffYears)->diffInMonths($nextVisitDate);
                                                    $diffDays =
                                                    $currentDate->copy()->addYears($diffYears)->addMonths($diffMonths)->diffInDays($nextVisitDate);
                                                    @endphp
                                                    {{ $diffYears }} years, {{ $diffMonths }} months, {{ $diffDays }}
                                                    days
                                                    @else
                                                    N/A
                                                    @endif
                                                </div>
                                                @else
                                                <div class="list-group-item text-center">
                                                    Not Found !!
                                                </div>
                                                @endif
                                            </div>

                                        </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- attendance information --}}
                    <div class="tab-pane fade  " id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
                        {{-- attendance Information --}}
                        <div class="row">

                            <div class="col-12 col-sm-12 col-md-12">
                                <h5 style="color: #000;margin: 0.5rem;;font-size:19px">Attendance of {{ date('M Y')}}
                                </h5>

                                <div class="card p-1">
                                    @php
                                    $employee = Employee::where('emp_id',$employee_info->emp_id)->first() ;
                                    if( $employee){
                                    $attendanceData = EmployeeAttendance::where('employee_id', $employee->id)
                                    ->whereYear('date', date('Y'))
                                    ->whereMonth('date', date('m'))
                                    ->orderBy('date','desc')
                                    ->get();
                                    }

                                    @endphp

                                    <div class="table-responsive" id="employee_table_data" style="min-height: 300px;">
                                        <table class="table table-sm table-hover table-bordered"
                                            style="max-width: 100%;">
                                            <thead class="thead-light">
                                                <tr class="text-center">
                                                    <th rowspan="2">NO</th>
                                                    <th rowspan="2">DATE</th>
                                                    <th rowspan="2">ATTENDANCE STATUS</th>

                                                    {{-- morning attendance info --}}
                                                    <th colspan="4">MORNING</th>

                                                    {{-- evening attendance info --}}
                                                    <th colspan="4">EVENING</th>

                                                    <th rowspan="2"> LATE TIME</th>

                                                    <th rowspan="2"> OVERTIME</th>
                                                    <th rowspan="2"> WORKING HOURS</th>
                                                </tr>
                                                <tr>
                                                    <th style="width: 10%; padding: 4px;">IN TIME</th>
                                                    <th style="width: 10%; padding: 4px;">OUT TIME</th>
                                                    <th style="width: 10%; padding: 4px;">REF IN </th>
                                                    <th style="width: 10%; padding: 4px;">REF OUT </th>
                                                    {{-- evening attendance info --}}
                                                    <th style="width: 10%; padding: 4px;">IN TIME</th>
                                                    <th style="width: 10%; padding: 4px;">OUT TIME</th>
                                                    <th style="width: 10%; padding: 4px;">REF IN </th>
                                                    <th style="width: 10%; padding: 4px;">REF OUT </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($attendanceData) && $attendanceData->count() > 0)
                                                @foreach ($attendanceData as $key => $data)
                                                <tr class="text-center">
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($data->date)->format('d/m/Y') }}</td>
                                                    <td>
                                                        @if($data->status == 1)
                                                        YES
                                                        @elseif($data->status == 0)
                                                        Absen
                                                        @elseif($data->status == 2)
                                                        Leave
                                                        @elseif($data->status == 3)
                                                        Weekend
                                                        @else
                                                        NO
                                                        @endif
                                                    </td>
                                                    {{-- morning attendance --}}
                                                    <td>{{ $data->in_time }}</td>
                                                    <td>{{ $data->out_time }}</td>
                                                    <td>{{ $data->reference_in_time }}</td>
                                                    <td>{{ $data->reference_out_time }}</td>

                                                    {{-- evening attendance --}}
                                                    <td>{{ $data->evening_in }}</td>
                                                    <td>{{ $data->evening_out }}</td>
                                                    <td>{{ $data->e_reference_in_time }}</td>
                                                    <td>{{ $data->e_reference_out_time }}</td>


                                                    <td>{{ $data->total_late_time ?? 'N/A' }}</td>
                                                    <td>{{ $data->total_overtime ?? 'N/A' }}</td>
                                                    <td>{{ $data->total_working_hours ?? 'N/A' }}</td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="9" class="text-center">No Attendance Found !!</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FILES --}}
                    <div class="tab-pane fade  " id="filesv" role="tabpanel" aria-labelledby="files-tabv">
                        {{-- file Information --}}
                        <div class="row">
                            <div class="col-md-12 col-12 changeColStyle pl-1">
                                <h5 style="color: #000;margin-top: 0.5rem;;font-size:19px">Documents</h5>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6">
                                <div class="card p-1">
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <a href="{{ asset('storage/upload/employee/'.$employee_info->employee_image)}}"
                                                target="blunk_" style=" color:#000">
                                                <img src="{{ asset('storage/upload/employee/'.$employee_info->employee_image)}}"
                                                    id="employee_image_previewu"
                                                    class="employee_image image-upload img-fluid rounded"
                                                    alt="Image View"
                                                    style="width:100%; height:300px; object-fit: cover;" alt="">
                                                <label style="" for="employee_image">PROFILE IMAGE</label>
                                            </a>
                                        </div>
                                        <div class="col-md-12">
                                            <a href="{{ asset('storage/upload/employee/'.$employee_info->emirates_image)}}"
                                                target="blunk_" style=" color:#000">
                                                <img src="{{ asset('storage/upload/employee/'.$employee_info->emirates_image)}}"
                                                    id="emirates_image_previewu"
                                                    class="emirates_image image-upload img-fluid rounded"
                                                    alt="Image View"
                                                    style="width:100%; height:300px; object-fit: cover;" alt="">
                                                <label style="" for="emirates_image">ID IMAGE</label>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-6">
                                <div class="card p-1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="{{ asset('storage/upload/employee/'.$employee_info->vissa_image)}}"
                                                target="blunk_" style=" color:#000">
                                                <img src="{{ asset('storage/upload/employee/'.$employee_info->vissa_image)}}"
                                                    id="vissa_image_previewu"
                                                    class="vissa_image image-upload img-fluid rounded"
                                                    style="width:100%; height:300px; object-fit: cover;" alt="">
                                                <label style="" for="vissa_image">VISA IMAGE</label>
                                            </a>
                                        </div>
                                        <div class="col-md-12">
                                            <a href="{{ asset('storage/upload/employee/'.$employee_info->image)}}"
                                                target="blunk_" style=" color:#000">
                                                <img src="{{ asset('storage/upload/employee/'.$employee_info->image)}}"
                                                    id="_image_previewu" class="_image image-upload img-fluid rounded"
                                                    alt="Image View"
                                                    style="width:100%; height:300px; object-fit: cover;" alt="">
                                                <label style="" for="_image">IMAGE </label>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-12 col-sm-6 col-md-6">
                                <div class="card pt-1 pb-1" id="other_document_replace">
                                    <table class="table table-sm w-100">
                                        <thead>
                                            <tr>
                                                <th>File Name</th>
                                                <th>File </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($document_lists as $item)
                                            <tr id="{{'tr'.$item->id}}">
                                                <td>{{$item->name}}</td>
                                                <td>
                                                    <a href="{{ asset('storage/upload/other-documents/'.$item->filename) }}"
                                                        target="_blank" style="color:#000;">
                                                        <img src="{{ asset('storage/upload/other-documents/'.$item->filename) }}"
                                                            class="img-fluid" style=" height:100px; object-fit: cover;">
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- notification --}}
                    <div class="tab-pane fade  w-100" id="notice" role="tabpanel" aria-labelledby="notice-tab">
                        {{-- file Information --}}
                        <div class="row">
                            <div class="col-md-12 col-12 changeColStyle pl-1">
                                <h5 style="color: #000;margin-top: 0.5rem;;font-size:19px">Notice Board</h5>
                            </div>

                            <div class="card pt-1 pb-1 w-100" id="other_document_replace">
                                <table class="table table-bordered table-sm employee_change  " id="2filter-table">
                                    <thead class="thead-light">
                                        <tr class="text-center" style="height: 40px;">
                                            <th>Notice</th>
                                            <th>Document</th>
                                        </tr>
                                    </thead>
                                    <tbody class="t-body">
                                        @foreach ($notice_lits as $key => $data)
                                        <tr class="pl-1" style="border-bottom: 1px solid #dfe3e7">
                                            <td>{!! $data->notice !!}</td>
                                            <td>
                                                <a href="{{ asset('storage/upload/notice-board/'.$data->document) }}"
                                                    target="_blank" style="color:#000;">
                                                    <img src="{{ asset('storage/upload/notice-board/'.$data->document) }}"
                                                        class="img-fluid rounded-circle"
                                                        style="width:150px; height:150px; object-fit: cover;"
                                                        alt="Notice Docs">
                                                </a>
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
        </div>
    </div>

</div>


<div class="d-none">
    <div class="modal-body" id="modal-body-print">
        <div class="card-body" style="padding: 0px;">
            <h5 style="color: #475f7b;margin-top: 0.5rem;;font-size:19px; margin-left:4px; text-align:center">EMPLOYEE
                INFORMATION</h5>
            <div class="row">
                <div class="col-2">
                    <div class="row">
                        <!-- Left Section: Employee Image, Name, and ID -->
                        <div class="col-12 text-left ">
                            <a href="{{ asset('storage/upload/employee/'.$employee_info->employee_image) }}"
                                target="_blank" style="color:#000;">
                                <img src="{{ asset('storage/upload/employee/'.$employee_info->employee_image) }}"
                                    class="img-fluid rounded-circle"
                                    style="width:150px; height:150px; object-fit: cover;" alt="Employee Image">
                            </a>
                            <h6 style="font-size: 15px;color: #475f7b; font-weight:900" class="mt-1">{{ $employee_info->full_name }}
                            </h6>
                            <h6 style="font-size: 14px;color: #475f7b;font-weight:900">  {{ $employee_info->job_title }}</h6>

                            <p style="font-size: 14px;color: #475f7b;font-weight:900"> ID: {{ $employee_info->emp_id }}</p>
                        </div>
                        <!-- Right Section: Employee Details in Two Columns -->
                        <div class="col-12">
                            <div class="row">
                                <!-- Column Titles (Left) -->
                                <div class="col-12">
                                    <label for="mode" style="font-size: 15px;color: #475f7b;  font-weight:900">First Name : <span class="text-right"> {{
                                            $employee_info->first_name }}</span></label>
                                    <label for="mode" style="font-size: 15px;color: #475f7b;  font-weight:900">DOB :{{ date('d/m/Y', strtotime($employee_info->dob))
                                        }}</label>
                                    <label for="mode" style="font-size: 15px;color: #475f7b;  font-weight:900">Gender : {{ $employee_info->gender == 'Male' ? 'Male' :
                                        'Female' }}</label>
                                    <label for="mode" style="font-size: 15px;color: #475f7b;  font-weight:900">Blood Group : {{ $employee_info->blood_group }}</label>
                                    <label for="mode" style="font-size: 15px;color: #475f7b;  font-weight:900">Marital Status : {{ $employee_info->marital_status ==
                                        'Married' ? 'Married' : 'Single' }}</label>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-10">
                    <div class="card-body" style="padding: 0px;">
                        {{-- PERSONAL INFORMATION --}}
                        <div class="row">
                            <div class="col-md-12 col-12 changeColStyle pl-1">
                                <h5 style="color: #475f7b; margin-top: 0.5rem; font-size:19px; margin-left:4px;">
                                    PERSONAL INFORMATION</h5>
                                <hr>
                            </div>

                            <div class="col-12 col-sm-6 col-md-6">
                                <div class="row mx-0">
                                    <div class="col-md-6">
                                        <div><strong>First Name:</strong> <span>{{$employee_info->first_name}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Middle Name:</strong> <span>{{$employee_info->middle_name}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Last Name:</strong> <span>{{$employee_info->last_name}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Date of Birth:</strong> <span>{{ date('d/m/Y',
                                                strtotime($employee_info->dob)) }}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Gender:</strong> <span>{{ $employee_info->gender }}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Nationality:</strong> <span>{{$employee_info->nationality}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Address (IN UAE):</strong>
                                            <span>{{$employee_info->parmanent_address}}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Blood Group:</strong> <span>{{$employee_info->blood_group}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-6">
                                <div class="row mx-0">
                                    <div class="col-md-6">
                                        <div><strong>Email:</strong> <span>{{$employee_info->email}}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Tel Number:</strong>
                                            <span>{{$employee_info->contact_number}}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>No. of Kids:</strong> <span>{{$employee_info->kids_no}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Joining Date:</strong> <span>{{ date('d/m/Y',
                                                strtotime($employee_info->joining_date)) }}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Current Date:</strong> <span>{{ date('d/m/Y') }}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Years of Service:</strong> <span>{{ $years }} years, {{ $months }}
                                                months, and {{ $days }} days.</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CONTACT INFORMATION --}}
                        <div class="row">
                            <div class="col-md-12 col-12 changeColStyle pl-1">
                                <h5 style="color: #475f7b; margin-top: 0.5rem; font-size:19px; margin-left:4px">
                                    EMERGENCY CONTACT</h5>
                                <hr>
                            </div>

                            <!-- Local Emergency Contact Information -->
                            <div class="col-12 col-sm-6">
                                <div class="row mx-0">
                                    <div class="col-md-6">
                                        <div><strong>Name (Local):</strong>
                                            <span>{{$employee_info->em_name_local}}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Email Address (Local):</strong>
                                            <span>{{$employee_info->em_email_local}}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Phone Number (Local):</strong>
                                            <span>{{$employee_info->em_contact_number_local}}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>City (Local):</strong>
                                            <span>{{$employee_info->em_city_local}}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Country (Local):</strong>
                                            <span>
                                                @foreach ($countries as $country)
                                                @if($country->id == $employee_info->em_country_local) {{$country->name}}
                                                @endif
                                                @endforeach
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Address (Local):</strong>
                                            <span>{{$employee_info->em_parmanent_address_local}}</span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Origin Emergency Contact Information -->
                            <div class="col-12 col-sm-6">
                                <div class="row mx-0">
                                    <div class="col-md-6">
                                        <div><strong>Name (Origin):</strong>
                                            <span>{{$employee_info->em_name_origin}}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Email Address (Origin):</strong>
                                            <span>{{$employee_info->em_email_origin}}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Phone Number (Origin):</strong>
                                            <span>{{$employee_info->em_contact_number_origin}}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>City (Origin):</strong>
                                            <span>{{$employee_info->em_city_origin}}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Country (Origin):</strong>
                                            <span>
                                                @foreach ($countries as $country)
                                                @if($country->id == $employee_info->em_country_origin)
                                                {{$country->name}} @endif
                                                @endforeach
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Address (Origin):</strong>
                                            <span>{{$employee_info->em_parmanent_address_origin}}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- JOB INFORMATION --}}
                        <div class="row">
                            <div class="col-md-12 col-12 changeColStyle pl-1">
                                <h5 style="color: #475f7b; margin-top: 0.5rem; font-size:19px; margin-left:4px">JOB
                                    INFORMATION</h5>
                                <hr>
                            </div>

                            <!-- Left Column Job Information -->
                            <div class="col-12 col-sm-6">
                                <div class="row mx-0">
                                    <div class="col-md-6">
                                        <div><strong>JOB TITLE:</strong> <span>{{$employee_info->job_title}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>JOB DESCRIPTION:</strong>
                                            <span>{{$employee_info->job_description}}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Visa Issue:</strong> <span>{{date('d/m/Y',
                                                strtotime($employee_info->vissa_issue))}}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Passport Number:</strong>
                                            <span>{{$employee_info->passport_number}}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Visa Expiry:</strong> <span>{{date('d/m/Y',
                                                strtotime($employee_info->visa_expiry_date))}}</span></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column Job Information -->
                            <div class="col-12 col-sm-6">
                                <div class="row mx-0">
                                    <div class="col-md-6">
                                        <div><strong>Department:</strong>
                                            <span>
                                                @foreach ($divisions as $division)
                                                @if($division->id == $employee_info->division) {{$division->name}}
                                                @endif
                                                @endforeach
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Joining Date:</strong> <span>{{date('d/m/Y',
                                                strtotime($employee_info->joining_date))}}</span></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Job Status:</strong>
                                            <span>
                                                @foreach ($job_types as $job_type)
                                                @if($job_type->id == $employee_info->job_type) {{$job_type->type}}
                                                @endif
                                                @endforeach
                                            </span>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-6">
                                        <div><strong>Employee Basic Salary:</strong>
                                            <span>
                                                {{$employee_info->basic_salary}}
                                            </span>
                                        </div>
                                    </div> --}}
                                    <div class="col-md-6">
                                        <div><strong>Job Active Status:</strong>
                                            <span>{{$employee_info->job_status == 1 ? 'Active' : 'Inactive'}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                               {{-- bank account --}}
                               <div class="row">
                                <div class="col-md-12 col-12 changeColStyle pl-1">
                                    <h5 style="color: #475f7b; margin-top: 0.5rem; font-size:19px; margin-left:4px">Bank Account</h5>
                                    <hr>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <div class="row mx-0">
                                        <div class="col-md-6">
                                            <div><strong>Bank Name:</strong> <span>{{$employee_info->bank_name}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div><strong>Branch Name:</strong>
                                                <span>{{$employee_info->branch_name}}</span></div>
                                        </div>

                                        <div class="col-md-6">
                                            <div><strong>Account Number:</strong>
                                                <span>{{$employee_info->account_number}}</span></div>
                                        </div>

                                    </div>
                                </div>

                                <!-- Right Column Job Information -->
                                <div class="col-12 col-sm-6">
                                    <div class="row mx-0">
                                        <div class="col-md-6">
                                            <div><strong>Account Name:</strong> <span>{{$employee_info->account_name}}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div><strong>Iban:</strong>
                                                <span>{{$employee_info->iban_number}}</span></div>
                                        </div>

                                        <div class="col-md-6">
                                            <div><strong>Account Type:</strong>
                                                <span>{{$employee_info->account_type}}</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        {{-- policy INFORMATION --}}
                        <div class="row">
                            <div class="col-md-12 col-12 changeColStyle pl-1">
                                <h5 style="color: #475f7b;margin-top: 0.5rem;font-size:19px; margin-left:4px">POLICY
                                    INFORMATION</h5>
                                <hr>
                            </div>

                            <div class="col-12 col-sm-6 col-md-6">
                                <div class="row mx-0">

                                    <!-- Policy Type -->
                                    <div class="col-md-6">
                                        <div><strong>Policy Type:</strong> <span>{{ isset($emp_policy->policy_type) ?
                                                $emp_policy->policy_type : 'Not Available' }}</span></div>
                                    </div>

                                    <!-- Effective Date -->
                                    <div class="col-md-6">
                                        <div><strong>Effective Date:</strong> <span>{{ isset($emp_policy->effect_date) ?
                                                date('d/m/Y', strtotime($emp_policy->effect_date)) : 'Not Available'
                                                }}</span></div>
                                    </div>

                                    <!-- Air Ticket Eligibility -->
                                    <div class="col-md-6">
                                        <div><strong>Air Ticket (Yes/No):</strong> <span>{{
                                                isset($emp_policy->air_ticket_eligibility) ?
                                                $emp_policy->air_ticket_eligibility : 'Not Available' }}</span></div>
                                    </div>

                                    <!-- Cash Redeem -->
                                    <div class="col-md-6">
                                        <div><strong>Cash Redeem:</strong> <span>{{ isset($emp_policy->apply_over_time)
                                                ? $emp_policy->apply_over_time : 'Not Available' }}</span></div>
                                    </div>
                                    <!-- basic-salary-->
                                    <div class="col-md-6">
                                        <div><strong>Basic Salary:</strong> <span>{{ isset($emp_policy->basic_salary) ? $emp_policy->basic_salary : 'Not Available' }}</span></div>
                                    </div>
                                    <!-- Vacation Paid Type -->
                                    <div class="col-md-6">
                                        <div><strong>Vacation Paid Type:</strong> <span>{{
                                                isset($emp_policy->vacation_paid_or_unpaid) ?
                                                $emp_policy->vacation_paid_or_unpaid : 'Not Available' }}</span></div>
                                    </div>

                                    <!-- Leave Type -->
                                    <div class="col-md-6">
                                        <div><strong>Leave Type:</strong> <span>{{ isset($emp_policy->vacation_type) ?
                                                $emp_policy->vacation_type : 'Not Available' }}</span></div>
                                    </div>

                                    <!-- Ticket Allowance (Cash) -->
                                    <div class="col-md-6">
                                        <div><strong>Ticket Allowance (Cash):</strong> <span>{{
                                                isset($emp_policy->ticket_price_percentage) ?
                                                $emp_policy->ticket_price_percentage : 'Not Available' }}</span></div>
                                    </div>

                                    <!-- Minimum Leave Period -->
                                    <div class="col-md-6">
                                        <div><strong>Minimum Leave Period (Years):</strong> <span>{{
                                                isset($emp_policy->minimun_vacation_priod) ?
                                                $emp_policy->minimun_vacation_priod : 'Not Available' }}</span></div>
                                    </div>

                                    <!-- Number of Yearly Vacations -->
                                    <div class="col-md-6">
                                        <div><strong>Number of Yearly Leave:</strong> <span>{{
                                                isset($emp_policy->number_of_yearly_vacation) ?
                                                $emp_policy->number_of_yearly_vacation : 'Not Available' }}</span></div>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-md-12">
                                        <div><strong>Description:</strong> <span>{{ isset($emp_policy->description) ?
                                                $emp_policy->description : 'Not Available' }}</span></div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-6">
                                <div class="row mx-0">

                                    <!-- Maximum Time for Attendance (Minutes) -->
                                    <div class="col-md-6">
                                        <div><strong>Maximum Time for Attendance (Minutes):</strong> <span>{{
                                                isset($emp_policy->maximum_time_for_attendace) ?
                                                $emp_policy->maximum_time_for_attendace : 'Not Available' }}</span>
                                        </div>
                                    </div>

                                    <!-- Late Type -->
                                    <div class="col-md-6">
                                        <div><strong>Late Type:</strong> <span>{{ isset($emp_policy->late_type) ?
                                                $emp_policy->late_type : 'Not Available' }}</span></div>
                                    </div>

                                    <!-- Minimum Hours for Late -->
                                    <div class="col-md-6">
                                        <div><strong>Minimum Hours for Late:</strong> <span>{{
                                                isset($emp_policy->minimum_hours_for_late) ?
                                                $emp_policy->minimum_hours_for_late : 'Not Available' }}</span></div>
                                    </div>

                                    <!-- Salary Loss Rate -->
                                    <div class="col-md-6">
                                        <div><strong>Salary Loss Rate:</strong> <span>{{ isset($emp_policy->salary_loss)
                                                ? $emp_policy->salary_loss : 'Not Available' }}</span></div>
                                    </div>

                                    <!-- Apply Over Time -->
                                    <div class="col-md-6">
                                        <div><strong>Apply Over Time:</strong> <span>{{ isset($emp_policy->cash_redeem)
                                                ? $emp_policy->cash_redeem : 'Not Available' }}</span></div>
                                    </div>

                                    <!-- Overtime Rate (Percentage) -->
                                    <div class="col-md-6">
                                        <div><strong>Overtime Rate (Percentage):</strong> <span>{{
                                                isset($emp_policy->overtime_rate) ? $emp_policy->overtime_rate : 'Not
                                                Available' }}</span></div>
                                    </div>

                                    <!-- Minimum Hours for Overtime -->
                                    <div class="col-md-6">
                                        <div><strong>Minimum Hours for Overtime:</strong> <span>{{
                                                isset($emp_policy->min_hours_for_overtime) ?
                                                $emp_policy->min_hours_for_overtime : 'Not Available' }}</span></div>
                                    </div>

                                    <!-- Late Grace Time -->
                                    <div class="col-md-6">
                                        <div><strong>Late Grace Time (Minutes):</strong> <span>{{
                                                isset($emp_policy->late_grace_time) ? $emp_policy->late_grace_time :
                                                'Not Available' }}</span></div>
                                    </div>

                                    <!-- Morning Reference In Time -->
                                    <div class="col-md-6">
                                        <div><strong>Morning Reference In Time:</strong> <span>{{
                                                isset($emp_policy->m_ref_in_time) ? $emp_policy->m_ref_in_time : 'Not
                                                Available' }}</span></div>
                                    </div>

                                    <!-- Morning Reference Out Time -->
                                    <div class="col-md-6">
                                        <div><strong>Morning Reference Out Time:</strong> <span>{{
                                                isset($emp_policy->m_ref_out_time) ? $emp_policy->m_ref_out_time : 'Not
                                                Available' }}</span></div>
                                    </div>

                                    <!-- Evening Reference In Time -->
                                    <div class="col-md-6">
                                        <div><strong>Evening Reference In Time:</strong> <span>{{
                                                isset($emp_policy->e_ref_in_time) ? $emp_policy->e_ref_in_time : 'Not
                                                Available' }}</span></div>
                                    </div>

                                    <!-- Evening Reference Out Time -->
                                    <div class="col-md-6">
                                        <div><strong>Evening Reference Out Time:</strong> <span>{{
                                                isset($emp_policy->e_ref_out_time) ? $emp_policy->e_ref_out_time : 'Not
                                                Available' }}</span></div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- FILES --}}
                        {{-- <div class="row">
                            <div class="col-md-12 col-12 changeColStyle pl-1">
                                <h5 style="color: #000;margin-top: 0.5rem;;font-size:19px">Documents</h5>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6">
                                <div class="card p-1">
                                    <div class="row ">
                                        <div class="col-md-12">
                                            <a href="{{ asset('storage/upload/employee/'.$employee_info->employee_image)}}"
                                                target="blunk_" style=" color:#000">
                                                <img src="{{ asset('storage/upload/employee/'.$employee_info->employee_image)}}"
                                                    id="employee_image_previewu"
                                                    class="employee_image image-upload img-fluid rounded"
                                                    alt="Image View"
                                                    style="width:100%; height:300px; object-fit: cover;" alt="">
                                                <label style="" for="employee_image">PROFILE IMAGE</label>
                                            </a>
                                        </div>
                                        <div class="col-md-12">
                                            <a href="{{ asset('storage/upload/employee/'.$employee_info->emirates_image)}}"
                                                target="blunk_" style=" color:#000">
                                                <img src="{{ asset('storage/upload/employee/'.$employee_info->emirates_image)}}"
                                                    id="emirates_image_previewu"
                                                    class="emirates_image image-upload img-fluid rounded"
                                                    alt="Image View"
                                                    style="width:100%; height:300px; object-fit: cover;" alt="">
                                                <label style="" for="emirates_image">ID IMAGE</label>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-6">
                                <div class="card p-1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="{{ asset('storage/upload/employee/'.$employee_info->vissa_image)}}"
                                                target="blunk_" style=" color:#000">
                                                <img src="{{ asset('storage/upload/employee/'.$employee_info->vissa_image)}}"
                                                    id="vissa_image_previewu"
                                                    class="vissa_image image-upload img-fluid rounded" alt="Image View"
                                                    style="width:100%; height:300px; object-fit: cover;" alt="">
                                                <label style="" for="vissa_image">VISA IMAGE</label>
                                            </a>
                                        </div>
                                        <div class="col-md-12">
                                            <a href="{{ asset('storage/upload/employee/'.$employee_info->image)}}"
                                                target="blunk_" style=" color:#000">
                                                <img src="{{ asset('storage/upload/employee/'.$employee_info->image)}}"
                                                    id="_image_previewu" class="_image image-upload img-fluid rounded"
                                                    alt="Image View"
                                                    style="width:100%; height:300px; object-fit: cover;" alt="">
                                                <label style="" for="_image">IMAGE </label>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="d-none">
    <div id="id-card-print">
    <style>

    /* * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    } */

    /* body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f5f5f5;
        font-family: Arial, sans-serif;
    } */

    .id-card {
        width: 320px;
        height: 530px;
        background-color: #fff;

        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        position: relative;
    }

    /* Header Background with Diagonal Split */
    .id-card-header {
        height: 140px;
        position: relative;
        display: flex;
        justify-content: flex-end;
        align-items: flex-start;

    }
    .header-green{
        position: absolute;
        top: 0;
        left: 0;
        width: 70%;
        height: 220px;
        background-color: #77cdff;
        clip-path: polygon(22% 0, 100% 35%, 54% 98%, 0 72%, 0 0);
    }
    .header-red{

        top: 0;
        right: 0;
        width: 85%;
        height: 255px;
        background-color: #00004e;
        clip-path: polygon(0 0, 100% 0, 100% 66%, 94% 73%, 50% 69%);
    }
    .photo-section {
    position: absolute;

    }

    /* .photo {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        background: white;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #00004e;
    } */

    .photo img {
        width: 80px;
        height: 80px;
        border-radius: 50%
    }


    .border-wrapper {
        transform: translateX(-50%);
        width: 50%; /* Set hexagon width to 50% of card width */
        height: calc(50% * 0.577);
        top: -72px;
        left: 50%;
        position: relative;
        clip-path: polygon(0 25%, 50% 0, 100% 25%, 100% 75%, 50% 100%, 0 75%);
        background-color: black; /* Border color */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Inner element for the clipped image */
    .clipped-image {
        width: calc(100% - 10px); /* Adjust to add space for the border */
        height: calc(100% - 10px); /* Adjust to add space for the border */
        clip-path: polygon(0 25%, 50% 0, 100% 25%, 100% 75%, 50% 100%, 0 75%);
        background-image: url({{ asset('storage/upload/employee/'.$employee_info->employee_image) }}); /* Replace with your image URL */
        background-size: cover;
        background-position: center;
    }

    .id-card-content {
        padding: 10px 10px 30px 10px;
         /* background: #fff; */
        overflow: hidden;
        /* display: inline-block; */
        top: -72px;
        position: relative;
    }
    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0.1;
        background-image: url('{{asset('/img/id_logo.png')}}');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        width: 200px;
        height: 200px;
        z-index: 0;
    }

    .name {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 5px;
        text-transform: uppercase;
        vertical-align: middle;
        color: #072f64;
        text-align: center;
        font-family: "Poppins", sans-serif;
        font-weight: 900;

        }

    .role {
        background-color: #77cdff;
        color: #162f5a;
        padding: 3px 5px;
        border-radius: 5px;
        font-size:12px;
        font-weight: bold;
        margin-bottom: 5px;
        display: inline-block;
        text-align: center;
    }

    .details {
        font-size: 16px;
        text-align: left;
        color: #072f64;
        font-family: "Poppins", sans-serif;
        /* margin-left: 60px; */
    }

    .details div {
        margin: 5px 0;
    }

    .details span {
    font-weight: bold;
    color: #072f64;
    }
    .details .ID {
    margin-left: 60px;
    margin-right: 5px;
    }
    .details .DOB {
    margin-left: 66px;
    margin-right: 5px;
    }
    .details .GENDER {
    margin-left: 46px;
    margin-right: 5px;
    }
    .details .BLOOD {
    margin-left: 5px;
    margin-right: 5px;
    }

    .barcode {
        display: flex;
        justify-content: center;
        padding: 10px;
        margin-top: -110px;
    }

    .barcode img {
    width: 60%;

    }

    .coner{
        position: relative;
    }
    /* Bottom Corner Gradient */
    .green {
        position: absolute;
    content: "";
    bottom: 0;
    left: 0;
    width: 50%;
    height: 70px;
    background-color: #77cdff;
    clip-path: polygon(0 0, 0 100%, 70% 100%);

    }
    .blue{

    content: "";
    bottom: 20px;
    margin-top: -14px;
    left: 0;
    width: 40%;
    height: 50px;
    background-color: #00004e;
    clip-path: polygon(30% 52%, 34% 63%, 36% 72%, 36% 85%, 34% 94%, 26% 100%, 0 100%, 0 3%, 25% 44%);

    }
    </style>
    <style>



    .id-card-back {
        width: 320px;
        height: 535px;
        background-color: #fff;

        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        position: relative;

    }

    /* Header Background with Diagonal Split */
    .id-card-back-header {
        height: 140px;
        position: relative;
        display: flex;
        justify-content: flex-end;
        align-items: flex-start;

    }
    .id-card-back-green{
        position: absolute;
        top: 0;
        left: 0;
        width: 50%;
        height: 150px;
        background-color: #77cdff;
        clip-path: polygon(30% 0, 100% 53%, 68% 96%, 0 41%, 0 0);

    }
    .id-card-back-red{
        top: 0;
        right: 0;
        width: 85%;
        height: 255px;
        background-color: #00004e;
        clip-path: polygon(30% 0%, 70% 0%, 100% 0, 100% 70%, 96% 75%, 91% 76%, 0 29%, 0 0);

    }

    .id-card-back-corner{
        position: relative;
        }
    /* Bottom Corner Gradient */
    .id-card-back-b-green {
        position: absolute;
        content: "";
        bottom: 0;
        left: 0;
        width: 50%;
        height: 60px;
        background-color: #77cdff;
        clip-path: polygon(0 0, 0 100%, 70% 100%);

    }
    .id-card-back-blue{

        content: "";
        bottom: 20px;
        margin-top: 354px;
        left: 0;
        width: 40%;
        height: 40px;
        background-color: #00004e;
        clip-path: polygon(30% 52%, 34% 63%, 36% 72%, 36% 85%, 34% 94%, 26% 100%, 0 100%, 0 3%, 25% 44%);
    }

    .id-card-back-content {
        padding-left: 36px;
        color: #333;
        padding-right: 20px;
        padding-top: 46px;
        position: absolute;
        top:100px;
    }

    .id-card-back-content h2 {
        font-size: 1.2rem;
        font-weight: bolder;
        color: #122b66;
        margin-bottom: 0px;

    }

    .id-card-back-content p {
        font-size: 0.9rem;
        font-weight: 500;
        line-height: 1.2;
        margin-bottom: 1px;
        color: #122b66;
        margin-top: 2px;
    }

    /* ID Number button styling */
    .id-card-back-id-number {
        width: 200px;
        display: block;
        text-align: center;
        padding: 5px;
        background-color: #77cdff;
        color: #122b66;
        font-weight: bold;
        font-size: 1rem;
        border-radius: 25px;
        margin-left: 25px;
        margin-top: 5px;
        margin-bottom: 2px;
    }
    .id-card-back-conten-section h2 {
        font-size: 1em;
        color: #00264d; /* Dark navy */
        margin-bottom: 5px;
        margin-top: 2px;
        display: inline-block;
    }

        .id-card-back-conten-section p {
        font-size: 0.9em;
        color: #122b66; /* Dark gray */
        line-height: 1;
        margin: 5px 0;
    }

    </style>

    <div class="id-card">
        <!-- Header Section with Background Colors and Logo -->
        <div class="id-card-header">
            <div class="header-red"></div>
            <div class="header-green"></div>
            <div class="photo-section">
            <div class="photo">
                <!-- Use an actual image here if available -->
                <img src="{{asset('/img/id_logo.png')}}" alt="Logo" class="logo1 photo" >
            </div>
            </div>
        </div>
        <!-- Hexagonal Profile Picture Section with 50% Width -->
        <div class="border-wrapper">
            <div class="clipped-image"></div>
        </div>

        <!-- Content Section for Name, Role, and Details -->
        <div class="id-card-content">
            <span class="watermark"></span>
            <div class="name">{{ $employee_info->full_name }}</div>
            <div style="display: flex; justify-content: center;">
                <div class="role">{{ $employee_info->job_title ? $employee_info->job_title : 'N/A' }}</div>
            </div>

            <div style="display: flex; justify-content: center;">
                <div class="details">
                    <div><span>ID No</span><span class="ID">:</span> {{ $employee_info->emp_id }}</div>
                    <div><span>DBO</span><span class="DOB">:</span>{{ date('d/m/Y', strtotime($employee_info->dob)) }}</div>
                    <div><span>Gender</span><span class="GENDER">:</span> {{ $employee_info->gender == 'Male' ? 'Male' : 'Female' }}</div>
                    <div><span>Blood Group</span><span class="BLOOD">:</span>{{ $employee_info->blood_group}}</div>
                </div>
            </div>
        </div>

        <!-- Barcode Section -->
        <div class="barcode">
            <img id="barcodeImage" src="" alt="Barcode"> <!-- Placeholder image -->
        </div>

            <div class="corner">
                <div class="green"></div>
                <div class="blue"></div>
            </div>
        </div>
        {{-- here start id card back part  --}}

    <div class="id-card-back mt-3" style="page-break-before: always;">
        <!-- Header Section with Background Colors and Logo -->
        <div class="id-card-back-header">
        <div class="id-card-back-red"></div>
        <div class="id-card-back-green">  </div>
        </div>


        <div class="id-card-back-content">
        <h2>TERMS & <br>CONDITIONS</h2>
        <hr style="background-color: #122b66; width: 50px; height: 5px; margin-bottom: 1px;margin-top: 2px; border-radius: 10px; margin-left:0px;">
        <p>Identification: Carry the ID card at all times during working hours for identification purposes.</p>
        <p>Authorized Use: The ID card is strictly for official use and should not be shared or used for unauthorized purposes.</p>
        <div class="id-card-back-id-number">ID Number: {{ $employee_info->emp_id }}</div>
        <div class="id-card-back-conten-section">
            <h2>Club Social Media Communication</h2>
            <hr style="background-color: #122b66; width: 50px; height: 5px; margin-bottom: 1px; margin-top: 2px; border-radius: 10px; margin-left:0px;">
            <p><strong>Tel No:</strong> {{$company_tele->config_value}}</p>
            <p><strong>Email:</strong> {{$company_email->config_value}}</p>
            <p><strong>Facebook:</strong>  {{$facebook->config_value}}</p>
            <p><strong>Instagram:</strong>  {{$instragram->config_value}}</p>
            <p><strong>YouTube:</strong>  {{$youtube->config_value}}</p>
            <p><strong>Web Page:</strong> {{$web_link->config_value}}</p>
        </div>
    </div>
    <div class="id-card-back-corner">
        <div class="id-card-back-b-green"></div>
        <div class="id-card-back-blue"></div>
    </div>
    <div class="id-card-b-corner">
        <div class="id-card-b-green"> </div>
        <div class="id-card-b-blue"> </div>
    </div>
    <canvas id="barcodeCanvas" style="display: none;"></canvas> <!-- Invisible canvas for barcode -->
</div>

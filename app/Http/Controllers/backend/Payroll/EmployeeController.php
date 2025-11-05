<?php

namespace App\Http\Controllers\backend\Payroll;


// use App\Country;
use App\Http\Controllers\Controller;
use App\Models\Payroll\Country;
use App\Models\Payroll\CountryCode;
use App\Models\Payroll\Department;
use App\Models\Payroll\Division;
use App\Models\Payroll\Employee;
use App\Models\Payroll\EmployeeAttendance;
use App\Models\Payroll\EmployeeDocument;
use App\Models\Payroll\EmployeePolicy;
use App\Models\Payroll\SalaryType;
use App\Models\Payroll\EmployeeTemp;
use App\Models\Payroll\GradeWiseSalaryComponentHistory;
use App\Models\Payroll\JobType;
use App\Models\Payroll\JobTypeInfo;
use App\Models\Payroll\Nationality;
use App\Models\Payroll\NoticeBoard;
use App\Models\Payroll\Policy;
use App\Models\Payroll\PorfessionalDocument;
use App\Models\Payroll\SalaryProcess;
use App\PartyInfo;
use App\Role;
use App\User;
use App\AccountSubHead;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Ui\Presets\React;
use Illuminate\Support\Str;
class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (Auth::user()->hasPermission('Employee')) {
            $from = $request->input('from');
            $to = $request->input('to');
            $search_query = $request->filled('search') ? trim($request->search) : null;
            $search_array = explode(' ', $search_query);
            $search1 = null;
            $search2 = null;

            if (count($search_array) == 2) {
                $search2 = $search_array[0];
                $search1 = $search_array[0] . '  ' . $search_array[1];
            }

            if (count($search_array) > 2) {
                $search2 = $search_array[0] . ' ' . $search_array[1];
                $search1 = $search_array[0] . ' ' . $search_array[1] . '  ' . $search_array[2];
            }

            if (count($search_array) > 3) {
                $search2 = $search1 = $search_array[0] . ' ' . $search_array[1] . ' ' . $search_array[2];
                $search1 = $search_array[0] . ' ' . $search_array[1] . '  ' . $search_array[2];
            }

            $division_search = $request->division ? $request->division : null;
            $fromDate = $from ? Carbon::createFromFormat('d/m/Y', $from)->format('Y-m-d') : null;
            $toDate = $to ? Carbon::createFromFormat('d/m/Y', $to)->format('Y-m-d') : null;

            $employees = EmployeeTemp::orderBy('code', 'asc')
                ->when($search_query, function ($query) use ($search_query, $search1, $search2) {
                    $query->where(function ($q) use ($search_query, $search1, $search2) {
                        $q->where('full_name', 'like', "%{$search_query}%")
                            ->orWhere('full_name', 'like', "%{$search1}%")
                            ->orWhere('full_name', 'like', "%{$search2}%")
                            ->orWhere('contact_number', 'like', "%{$search_query}%")
                            ->orWhere('parmanent_address', 'like', "%{$search_query}%"); // Fixed typo
                    });
                })->when($fromDate, function ($query, $fromDate) use ($toDate) {
                    if ($toDate) {
                        return $query->whereBetween('joining_date', [$fromDate, $toDate]);
                    }
                    return $query->whereDate('joining_date', $fromDate);
                })->when($division_search, function ($query) use ($division_search) {

                    $query->where('division', $division_search);
                });
                // ->paginate(15);
            $cal_total_salary = (clone $employees)->get()->sum('basic_salary');
            $employees = $employees->paginate(15);


            $date = Carbon::now()->subYears(10);
            $id = EmployeeTemp::latest()->first();
            $countrytCode = Country::get();
            $countrytCode2 = Country::get();
            $countrytCode3 = Country::get();
            $departments = Department::get();
            $countries = Country::get();
            $job_types = JobTypeInfo::get();


            $divisions = Division::get();
            $roles = Role::get();

            // dd($EmployeeTemps);
            // $accoutHeads = AccountHead::all();
            $salaryTypes = SalaryType::all();
            $search = $search_query;
            return view('backend.payroll.employee.index', compact(
                'employees',
                'salaryTypes',
                'date',
                'countrytCode',
                'departments',
                'countries',
                'job_types',
                'countrytCode2',
                'countrytCode3',
                'divisions',
                'division_search',
                'roles',
                'fromDate',
                'toDate',
                'search',
                'cal_total_salary'
            ));
        } else {

            $employee_info = Employee::find(Auth::user()->employee_id);
            $salaryTypes = SalaryType::all();
            $countrytCode = Country::get();
            $countrytCode2 = Country::get();
            $countrytCode3 = Country::get();
            $department = Department::get();
            $job_types = JobTypeInfo::get();

            $countries = Country::get();

            $divisions = Division::get();
            $roles = Role::get();

            $pro_quali = PorfessionalDocument::where('employee_id', $employee_info->emp_id)->get();
            $date = date('Y-m-d');
            $emp_policy = policy_helper($employee_info->emp_id, $date);
            $document_lists = EmployeeDocument::where('employee_id', $employee_info->id)->get();
            $mail_emp_info = Employee::where('emp_id', $employee_info->emp_id)->first();
            $notice_lits = [];
            if ($mail_emp_info) {
                $notice_lits = NoticeBoard::where('employee_id', $mail_emp_info->id)->orWhere('employee_id', null)->get();
            }

            return view('backend.payroll.employee.profile-view', [
                'employee_info' => $employee_info,
                'salaryTypes' => $salaryTypes,
                'countrytCode' => $countrytCode,
                'departments' => $department,
                'countries' => $countries,
                'countrytCode2' => $countrytCode2,
                'countrytCode3' => $countrytCode3,
                'pro_quali' => $pro_quali,
                'divisions' => $divisions,
                'roles' => $roles,
                'emp_policy' => $emp_policy,
                'job_types' => $job_types,
                'document_lists' => $document_lists,
                'notice_lits' => $notice_lits,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkCode(Request $request)
    {
        $code = $request->code;
        $id = $request->id; // This is for update, to exclude current row

        $employee = EmployeeTemp::where('code', $code)
            ->when($id, function ($q) use ($id) {
                $q->where('id', '!=', $id);
            })->first();


        if ($employee) {
            return response()->json(['exists' => true]);
        }

        return response()->json(['exists' => false]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->employee_id == null) {
            $eid_latest = EmployeeTemp::orderBy('id', 'desc')->first();
            if ($eid_latest) {
                $last_emp_id = substr($eid_latest->emp_id, 2);
                if ($last_emp_id <= 101) {
                    $last_emp_id = '00200';
                }
                $new_sequence = intval($last_emp_id) + 1;
                $eid = 'SB' . str_pad($new_sequence, 5, '0', STR_PAD_LEFT);
            } else {
                $eid = 'SB00101';
            }

            $employee_image = '';

            if ($request->file('profile_picture')) {
                $name = $request->file('profile_picture')->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext = $request->file('profile_picture')->getClientOriginalExtension();
                $employee_image = 'employee_image' . time() . '.' . $ext;

                $request->file('profile_picture')->storeAs('public/upload/employee', $employee_image);
            }

            $emirates_image = '';

            if ($request->file('emirates_image')) {
                $name = $request->file('emirates_image')->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext = $request->file('emirates_image')->getClientOriginalExtension();
                $emirates_image = 'emirates_image' . time() . '.' . $ext;

                $request->file('emirates_image')->storeAs('public/upload/employee', $emirates_image);
            }
            $vissa_image = '';

            if ($request->file('vissa_image')) {
                $name = $request->file('vissa_image')->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext = $request->file('vissa_image')->getClientOriginalExtension();
                $vissa_image = 'vissa_image' . time() . '.' . $ext;

                $request->file('vissa_image')->storeAs('public/upload/employee', $vissa_image);
            }

            $image = '';
            if ($request->file('image')) {
                $name = $request->file('image')->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext = $request->file('image')->getClientOriginalExtension();
                $image = 'image' . time() . '.' . $ext;

                $request->file('image')->storeAs('public/upload/employee', $image);
            }

            //dob
            $dob = '';
            if (isset($request->dob)) {
                $old_date = explode('/', $request->dob);

                $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
                $new_date = date('Y-m-d', strtotime($new_data));
                $dob = \DateTime::createFromFormat("Y-m-d", $new_date);
            }
            $joining_date = "";
            if (isset($request->joining_date)) {

                //joining_date
                $old_date = explode('/', $request->joining_date);
                $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
                $new_date = date('Y-m-d', strtotime($new_data));
                $joining_date = \DateTime::createFromFormat("Y-m-d", $new_date);
            }

            //$request->visa_expiry_date
            $visa_expiry_date = '';
            if (isset($request->visa_expiry_date)) {
                $old_date = explode('/', $request->visa_expiry_date);

                $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
                $new_date = date('Y-m-d', strtotime($new_data));
                $visa_expiry_date = \DateTime::createFromFormat("Y-m-d", $new_date);
            }
            $vissa_issue = '';
            if (isset($request->vissa_issue)) {

                $vissa_issue1 = explode('/', $request->vissa_issue);

                $new_data = $vissa_issue1[0] . '-' . $vissa_issue1[1] . '-' . $vissa_issue1[2];
                $new_date = date('Y-m-d', strtotime($new_data));
                $vissa_issue = \DateTime::createFromFormat("Y-m-d", $new_date);
            }
            $effect_date = '';

            if (isset($request->effect_date)) {

                $vissa_issue1 = explode('/', $request->effect_date);

                $new_data = $vissa_issue1[0] . '-' . $vissa_issue1[1] . '-' . $vissa_issue1[2];
                $new_date = date('Y-m-d', strtotime($new_data));
                $effect_date = \DateTime::createFromFormat("Y-m-d", $new_date);
            }
            $last_visite = '';

            if (isset($request->last_visite)) {

                $vissa_issue1 = explode('/', $request->last_visite);

                $new_data = $vissa_issue1[0] . '-' . $vissa_issue1[1] . '-' . $vissa_issue1[2];
                $new_date = date('Y-m-d', strtotime($new_data));
                $last_visite = \DateTime::createFromFormat("Y-m-d", $new_date);
            }

            $first_name = $request->first_name;
            $middle_name = trim($request->middle_name);
            $last_name = $request->last_name;
            $nationality = $request->nationality;

            $exist_temp_employee = EmployeeTemp::where('first_name', $first_name)
                ->where('middle_name', $middle_name)
                ->where('last_name', $last_name)
                ->where('dob', $dob)
                ->where('nationality', $nationality)
                ->exists();

            $exist_employee = Employee::where('first_name', $first_name)
                ->where('middle_name', $middle_name)
                ->where('last_name', $last_name)
                ->where('dob', $dob)
                ->where('nationality', $nationality)
                ->exists();

            if ($exist_temp_employee || $exist_employee) {
                return response()->json([
                    'error' => "Duplicate Entry" . $first_name . ' ' . $middle_name . ' ' . $last_name . "Employee Already Exist"
                ], 409);
            }
            $full_name = $request->first_name . ' ' .  $request->middle_name . ' ' .  $request->last_name;
            $employee =  EmployeeTemp::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'full_name' => str_replace('  ', ' ', $full_name),
                'code' => $request->employee_code,
                'salutation' => $request->salutation,
                'emp_id' => $eid,
                // 'father_name' => $request->father_name,
                // 'mother_name' => $request->mother_name,
                'dob' => $dob,
                'nationality' => $request->nationality,
                'employee_image' => $employee_image,
                'job_type' => $request->job_type,
                'job_status' => $request->job_status,

                'blood_group' => $request->blood_group,

                'parmanent_address' => $request->parmanent_address,
                'contact_number' => $request->contact_number,
                'local_contact_number' => $request->local_contact_number,

                'email' => $request->email,

                'em_name_local' => $request->em_name_local,
                'em_parmanent_address_local' => $request->em_parmanent_address_local,
                'em_city_local' => $request->em_countrytCode,
                'em_contact_number_local' => $request->em_contact_number_local,
                'em_email_local' => $request->em_email,
                'em_country_local' => $request->em_email,

                'em_name_origin' => $request->em_name_origin,
                'em_parmanent_address_origin' => $request->em_parmanent_address_origin,
                'em_city_origin' => $request->em_countrytCode,
                'em_contact_number_origin' => $request->em_contact_number_origin,
                'em_email_origin' => $request->em_email_origin,
                'em_country_origin' => $request->em_country_origin,

                'emirates_id' => $request->emirates_id,
                'emirates_image' => $emirates_image,
                'passport_number' => $request->passport_number,
                'vissa_image' => $vissa_image,
                'image' => $image,

                'visa_number' => $request->visa_number,
                'visa_type' => $request->visa_type,
                'pass_issue_country' => $request->pass_issue_country,
                'visa_issue_country' => $request->visa_issue_country,
                // 'last_visite' => $last_visite,
                'company' => $request->company,
                'division' => $request->division,
                'department' => $request->department,
                'designation' => $request->designation,
                'joining_date' => $joining_date,
                'role' => $request->division == 6 ? 5 : null,

                'qualification' => $request->qualification,


                'bank_name' => $request->bank_name,
                'branch_name' => $request->branch_name,
                'account_number' => $request->account_number,
                'ibal_number' => $request->ibal_number,
                'routing_number' => $request->routing_number,
                'swift_code' => $request->swift_code,

                'status' => 0,

                'visa_expiry_date' => $visa_expiry_date,
                'employee_wage_type' => $request->employee_wage_type,
                'employment_location' => $request->employment_location,
                'basic_salary' => $request->basic_salary,
                'payment_method' => $request->payment_method,
                'grade' => $request->grade,

                'description' => $request->description,
                'sub_description' => $request->sub_description,

                'gender' => $request->gender,
                'post_box' => $request->post_box,
                // 'marital_status' => $request->marital_status,
                'kids_no' => $request->kids_no,
                'health_status' => $request->health_status,
                'city' => $request->city,

                'em_relation' => $request->em_relation,
                'job_title' => $request->job_title,
                'job_description' => $request->job_description,
                'vissa_issue' => $vissa_issue,

                'bank_name' => $request->bank_name,
                'branch_name' => $request->branch_name,
                'account_number' => $request->account_number,
                'account_name' => $request->account_name,
                'ibal_number' => $request->ibal_number,
                'account_type' => $request->account_type
            ]);


            if (isset($request->policy_type)) {
                $emp_up = EmployeeTemp::find($employee->id);
                $emp_up->remaining_vacation =  $request->number_of_yearly_vacation;
                $emp_up->save();

                $emp_policy = EmployeePolicy::where('employee_id', $employee->emp_id)
                    ->where('effect_date', $effect_date)
                    ->first();

                $policy_data = [
                    'employee_id'                => $employee->emp_id,
                    'policy_type'                => $request->policy_type,
                    'effect_date'                => $effect_date,
                    'air_ticket_eligibility'     => $request->air_ticket_eligibility,
                    'apply_over_time'            => $request->apply_over_time,
                    'vacation_paid_or_unpaid'    => $request->vacation_paid_or_unpaid,
                    'vacation_type'              => $request->vacation_type,
                    'minimum_day_for_ticket_price' => $request->minimum_day_for_ticket_price,
                    'm_ref_in_time'              => $request->m_ref_in_time,
                    'm_ref_out_time'             => $request->m_ref_out_time,
                    'time_zone'                  => $request->time_zone,
                    'description'                => $request->description,
                    'ticket_price_percentage'    => $request->ticket_price_percentage,
                    'maximum_time_for_attendace' => $request->maximum_time_for_attendace,
                    'late_type'                  => $request->late_type,
                    'minimum_day_for_late'       => $request->minimum_day_for_late,
                    'minimum_hours_for_late'     => $request->minimum_hours_for_late,
                    'salary_loss'                => $request->salary_loss,
                    'cash_redeem'                => $request->cash_redeem,
                    'overtime_rate'              => $request->overtime_rate,
                    'min_hours_for_overtime'     => $request->min_hours_for_overtime,
                    'late_grace_time'            => $request->late_grace_time,
                    'number_of_yearly_vacation'  => $request->number_of_yearly_vacation,
                    'e_ref_in_time'              => $request->e_ref_in_time,
                    'e_ref_out_time'             => $request->e_ref_out_time,
                    'minimun_vacation_priod'     => $request->minimun_vacation_priod,
                    'basic_salary'             => $request->basic_salary,
                ];

                if ($emp_policy) {
                    $emp_policy->update($policy_data);
                } else {
                    EmployeePolicy::create($policy_data);
                }
            }

            $message = 'Employee Added successfully!';
        } else {
            $employee = EmployeeTemp::where('id', $request->employee_id)->first();

            $employee_image = $employee->employee_image;
            $vissa_image = $employee->vissa_image;

            if ($request->hasFile('document')) {
                foreach ($request->file('document') as $index => $file) {
                    $type = $request->document_type[$index];
                    $name = $file->getClientOriginalName();
                    $name = pathinfo($name, PATHINFO_FILENAME);
                    $ext = $file->getClientOriginalExtension();
                    $other_document = $employee->id . uniqid() . '.' . $ext;
                    $file->storeAs('public/upload/other-documents', $other_document);
                    $other_doc = new EmployeeDocument;
                    $other_doc->name = $request->document_name ? $request->document_name : 'N/A';
                    $other_doc->filename = $other_document;
                    $other_doc->employee_id = $employee->id;
                    $other_doc->type = $type;
                    $other_doc->ext = $ext;
                    $other_doc->save();

                    if ($type == 'profile_image') {
                        $oldFilePath = 'public/upload/employee/' . $employee->employee_image;

                        if (\Storage::exists($oldFilePath)) {
                            \Storage::delete($oldFilePath);
                        }

                        $name = $file->getClientOriginalName();
                        $name = pathinfo($name, PATHINFO_FILENAME);
                        $ext = $file->getClientOriginalExtension();
                        $employee_image = 'employee_image' . time() . '.' . $ext;

                        $file->storeAs('public/upload/employee', $employee_image);
                    }

                    if ($type == 'profile_image') {
                        $name = $file->getClientOriginalName();
                        $name = pathinfo($name, PATHINFO_FILENAME);
                        $ext = $file->getClientOriginalExtension();
                        $vissa_image = 'vissa_image' . time() . '.' . $ext;

                        $file->storeAs('public/upload/employee', $vissa_image);
                    }
                }
            }

            if ($request->file('employee_image')) {
                $name = $request->file('employee_image')->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext = $request->file('employee_image')->getClientOriginalExtension();
                $employee_image = 'employee_image' . time() . '.' . $ext;

                $request->file('employee_image')->storeAs('public/upload/employee', $employee_image);
            }
            $emirates_image = $employee->emirates_image;

            if ($request->file('emirates_image')) {
                $name = $request->file('emirates_image')->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext = $request->file('emirates_image')->getClientOriginalExtension();
                $emirates_image = 'emirates_image' . time() . '.' . $ext;

                $request->file('emirates_image')->storeAs('public/upload/employee', $emirates_image);
            }

            if ($request->file('vissa_image')) {
                $name = $request->file('vissa_image')->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext = $request->file('vissa_image')->getClientOriginalExtension();
                $vissa_image = 'vissa_image' . time() . '.' . $ext;

                $request->file('vissa_image')->storeAs('public/upload/employee', $vissa_image);
            }

            $image = $employee->image;
            if ($request->file('image')) {
                $name = $request->file('image')->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext = $request->file('image')->getClientOriginalExtension();
                $image = 'image' . time() . '.' . $ext;

                $request->file('image')->storeAs('public/upload/employee', $image);
            }

            //dob
            $dob = $employee->dob;
            if ($request->dob) {
                $old_date = explode('/', $request->dob);

                $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
                $new_date = date('Y-m-d', strtotime($new_data));
                $dob = \DateTime::createFromFormat("Y-m-d", $new_date);
            }
            $joining_date = $employee->joining_date;
            if ($request->joining_date) {

                //joining_date
                $old_date = explode('/', $request->joining_date);
                $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
                $new_date = date('Y-m-d', strtotime($new_data));
                $joining_date = \DateTime::createFromFormat("Y-m-d", $new_date);
            }

            //$request->visa_expiry_date
            $visa_expiry_date = $employee->visa_expiry_date;
            if ($request->visa_expiry_date) {
                $old_date = explode('/', $request->visa_expiry_date);

                $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
                $new_date = date('Y-m-d', strtotime($new_data));
                $visa_expiry_date = \DateTime::createFromFormat("Y-m-d", $new_date);
            }
            $vissa_issue = $employee->vissa_issue;
            if ($request->vissa_issue) {

                $vissa_issue1 = explode('/', $request->vissa_issue);

                $new_data = $vissa_issue1[0] . '-' . $vissa_issue1[1] . '-' . $vissa_issue1[2];
                $new_date = date('Y-m-d', strtotime($new_data));
                $vissa_issue = \DateTime::createFromFormat("Y-m-d", $new_date);
            }
            $effect_date = '';

            if (isset($request->effect_date)) {
                $vissa_issue1 = explode('/', $request->effect_date);
                $new_data = $vissa_issue1[0] . '-' . $vissa_issue1[1] . '-' . $vissa_issue1[2];
                $new_date = date('Y-m-d', strtotime($new_data));
                $effect_date = \DateTime::createFromFormat("Y-m-d", $new_date);
                $effect_date = $effect_date ? $effect_date->format('Y-m-d') : null; // Convert to string format
            }
            $last_visite = $employee->last_visite;;

            if (isset($request->last_visite)) {

                $vissa_issue1 = explode('/', $request->last_visite);

                $new_data = $vissa_issue1[0] . '-' . $vissa_issue1[1] . '-' . $vissa_issue1[2];
                $new_date = date('Y-m-d', strtotime($new_data));
                $last_visite = \DateTime::createFromFormat("Y-m-d", $new_date);
            }
            // return $last_visite;
            $full_name = ($request->first_name ?? $employee->first_name) . ' ' . ($request->middle_name ?? $employee->middle_name) . ' ' . ($request->last_name ?? $employee->last_name);
            $employee->update([
                'first_name' => $request->first_name ?? $employee->first_name,
                'middle_name' => $request->middle_name ?? $employee->middle_name,
                'last_name' => $request->last_name ?? $employee->last_name,
                'full_name' => str_replace('  ', ' ', $full_name),
                'code' => $request->employee_code  ?? $employee->code,

                'salutation' => $request->salutation ?? $employee->salutation,
                'emp_id' => $employee->emp_id,
                'dob' => $dob ?? $employee->dob,
                'nationality' => $request->nationality ?? $employee->nationality,
                'employee_image' => $employee_image ?? $employee->employee_image,
                'job_type' => $request->job_type ?? $employee->job_type,
                'job_status' => $request->job_status ?? $employee->job_status,

                'blood_group' => $request->blood_group ?? $employee->blood_group,

                'parmanent_address' => $request->parmanent_address ?? $employee->parmanent_address,
                'contact_number' => $request->contact_number ?? $employee->contact_number,
                'local_contact_number' => $request->local_contact_number ?? $employee->local_contact_number,

                'email' => $request->email ?? $employee->email,

                'em_name_local' => $request->em_name_local ?? $employee->em_name_local,
                'em_parmanent_address_local' => $request->em_parmanent_address_local ?? $employee->em_parmanent_address_local,
                'em_city_local' => $request->em_city_local ?? $employee->em_city_local,
                'em_contact_number_local' => $request->em_contact_number_local ?? $employee->em_contact_number_local,
                'em_email_local' => $request->em_email_local ?? $employee->em_email_local,
                'em_country_local' => $request->em_country_local ?? $employee->em_country_local,

                'em_name_origin' => $request->em_name_origin ?? $employee->em_name_origin,
                'em_parmanent_address_origin' => $request->em_parmanent_address_origin ?? $employee->em_parmanent_address_origin,
                'em_city_origin' => $request->em_city_origin ?? $employee->em_city_origin,
                'em_contact_number_origin' => $request->em_contact_number_origin ?? $employee->em_contact_number_origin,
                'em_email_origin' => $request->em_email_origin ?? $employee->em_email_origin,
                'em_country_origin' => $request->em_country_origin ?? $employee->em_country_origin,

                'emirates_id' => $request->emirates_id ?? $employee->emirates_id,
                'emirates_image' => $emirates_image ?? $employee->emirates_image,
                'image' => $image ?? $employee->image,

                'passport_number' => $request->passport_number ?? $employee->passport_number,
                'vissa_image' => $vissa_image ?? $employee->vissa_image,
                'visa_number' => $request->visa_number ?? $employee->visa_number,
                'visa_type' => $request->visa_type ?? $employee->visa_type,
                'pass_issue_country' => $request->pass_issue_country ?? $employee->pass_issue_country,
                'visa_issue_country' => $request->visa_issue_country ?? $employee->visa_issue_country,
                // 'last_visite' => $last_visite ?? $employee->last_visite,

                'company' => $request->company ?? $employee->company,
                'division' => $request->division ?? $employee->division,
                'department' => $request->department ?? $employee->department,
                'designation' => $request->designation ?? $employee->designation,
                'joining_date' => $joining_date ?? $employee->joining_date,
                'role' => $request->division == 6 ? 5 : $employee->role,

                'qualification' => $request->qualification ?? $employee->qualification,

                'bank_name' => $request->bank_name ?? $employee->bank_name,
                'branch_name' => $request->branch_name ?? $employee->branch_name,
                'account_number' => $request->account_number ?? $employee->account_number,
                'ibal_number' => $request->ibal_number ?? $employee->ibal_number,
                'routing_number' => $request->routing_number ?? $employee->routing_number,
                'swift_code' => $request->swift_code ?? $employee->swift_code,

                'status' => $request->status ?? $employee->status,

                'visa_expiry_date' => $visa_expiry_date ?? $employee->visa_expiry_date,
                'employee_wage_type' => $request->employee_wage_type ?? $employee->employee_wage_type,
                'employment_location' => $request->employment_location ?? $employee->employment_location,
                'basic_salary' => $request->basic_salary ?? $employee->basic_salary,
                'payment_method' => $request->payment_method ?? $employee->payment_method,
                'grade' => $request->grade ?? $employee->grade,

                'description' => $request->description ?? $employee->description,
                'sub_description' => $request->sub_description ?? $employee->sub_description,

                'gender' => $request->gender ?? $employee->gender,
                'post_box' => $request->post_box ?? $employee->post_box,
                // 'marital_status' => $request->marital_status ?? $employee->marital_status,
                'kids_no' => $request->kids_no ?? $employee->kids_no,
                'health_status' => $request->health_status ?? $employee->health_status,
                'city' => $request->city ?? $employee->city,

                'em_relation' => $request->em_relation ?? $employee->em_relation,
                'job_title' => $request->job_title ?? $employee->job_title,
                'job_description' => $request->job_description ?? $employee->job_description,
                'vissa_issue' => $vissa_issue ?? $employee->vissa_issue,

                'bank_name' => $request->bank_name ?? $employee->bank_name,
                'branch_name' => $request->branch_name ?? $employee->branch_name,
                'account_number' => $request->account_number ?? $employee->account_number,
                'account_name' => $request->account_name ?? $employee->account_name,
                'ibal_number' => $request->ibal_number ?? $employee->ibal_number,
                'account_type' => $request->account_type ?? $employee->account_type,
                'status' => 0,
            ]);

            if (isset($request->policy_type)) {

                $emp_up = EmployeeTemp::find($employee->id);
                $emp_up->remaining_vacation =  $request->number_of_yearly_vacation;
                $emp_up->save();
                $emp_policy = EmployeePolicy::where('employee_id', $employee->emp_id)
                    ->where('effect_date', $effect_date)
                    ->first();

                $policy_data = [
                    'employee_id'                => $employee->emp_id,
                    'policy_type'                => $request->policy_type,
                    'effect_date'                => $effect_date,
                    'air_ticket_eligibility'     => $request->air_ticket_eligibility,
                    'apply_over_time'            => $request->apply_over_time,
                    'vacation_paid_or_unpaid'    => $request->vacation_paid_or_unpaid,
                    'vacation_type'              => $request->vacation_type,
                    'minimum_day_for_ticket_price' => $request->minimum_day_for_ticket_price,
                    'm_ref_in_time'              => $request->m_ref_in_time,
                    'm_ref_out_time'             => $request->m_ref_out_time,
                    'time_zone'                  => $request->time_zone,
                    'description'                => $request->description,
                    'ticket_price_percentage'    => $request->ticket_price_percentage,
                    'maximum_time_for_attendace' => $request->maximum_time_for_attendace,
                    'late_type'                  => $request->late_type,
                    'minimum_day_for_late'       => $request->minimum_day_for_late,
                    'minimum_hours_for_late'     => $request->minimum_hours_for_late,
                    'salary_loss'                => $request->salary_loss,
                    'cash_redeem'                => $request->cash_redeem,
                    'overtime_rate'              => $request->overtime_rate,
                    'min_hours_for_overtime'     => $request->min_hours_for_overtime,
                    'late_grace_time'            => $request->late_grace_time,
                    'number_of_yearly_vacation'  => $request->number_of_yearly_vacation,
                    'e_ref_in_time'              => $request->e_ref_in_time,
                    'e_ref_out_time'             => $request->e_ref_out_time,
                    'minimun_vacation_priod'     => $request->minimun_vacation_priod,
                    'basic_salary'             => $request->basic_salary,
                ];

                if ($emp_policy) {
                    $emp_policy->update($policy_data);
                } else {
                    EmployeePolicy::create($policy_data);
                }
            }
            $message = 'Employee Update successfully!';
        }


        return response()->json(['id' => $employee->id, 'message' => $message]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $employee_info = EmployeeTemp::find($id);
        $salaryTypes = SalaryType::all();
        $countrytCode = Country::get();
        $countrytCode2 = Country::get();
        $countrytCode3 = Country::get();
        $department = Department::get();
        $job_types = JobTypeInfo::get();

        $countries = Country::get();
        $divisions = Division::get();
        $roles = Role::get();

        $pro_quali = PorfessionalDocument::where('employee_id', $employee_info->emp_id)->get();
        $date = date('Y-m-d');
        $emp_policy = policy_helper($employee_info->emp_id, $date);
        $document_lists = EmployeeDocument::where('employee_id', $employee_info->id)->get();
        $mail_emp_info = Employee::where('emp_id', $employee_info->emp_id)->first();
        $notice_lits = [];
        if ($mail_emp_info) {
            $notice_lits = NoticeBoard::where('employee_id', $mail_emp_info->id)->orWhere('employee_id', null)->get();
        }

        $employee = Employee::where('emp_id', $employee_info->emp_id)->first();

        $date = Carbon::now();
        $monthYear = $request->month ?? $date->format('Y-m');
        $salary_month = date('m');
        $salary_year = date('Y');

        $totalDaysInMonth = Carbon::parse($monthYear)->daysInMonth;
        $currentDayOfMonth = $date->format('Y-m') == $monthYear ? $date->day : $totalDaysInMonth;

        $salary_process = [];
        $already_payment = [];

        if ($employee) {
            $salary_process = SalaryProcess::where('employee_id', $employee->id)->where('advance_amount', '>', 0)->get();
            $already_payment = SalaryProcess::where('employee_id', $employee->id)->where('status', 1)->where('month', $salary_month)->where('year', $salary_year)->get();
        } else {
            $salary_process = [];
            $already_payment = [];
        }


        if ($salary_month && $salary_year) {
            $salary_date = new DateTime("$salary_year-$salary_month-01");

            if ($employee) {
                $last_visite = !empty($employee->last_visite) ? new DateTime($employee->last_visite) : (!empty($employee->joining_date) ? new DateTime($employee->joining_date) : null);
                $v_type = !empty($employee->last_visite) ? 'l' : 'j';

                if ($last_visite) {
                    $interval = $salary_date->diff($last_visite);
                    $months_difference = ($interval->y * 12) + $interval->m;
                    $text = $months_difference;
                }
            }
        }

        $salary = [];

        $basic_salary = 0;

        if ($employee) {
            $check_attendance = EmployeeAttendance::check_attendance($employee->id, $salary_month, $salary_year, $basic_salary);
            $overtime_amount = isset($check_attendance['overtime_amount']) ? str_replace(',', '', $check_attendance['overtime_amount']) : 0;
            $late_amount = isset($check_attendance['late_amount']) ? str_replace(',', '', $check_attendance['late_amount']) : 0;
            $total_absen_penalty = isset($check_attendance['total_absen_penalty']) ? $check_attendance['total_absen_penalty'] : 0;
            $basic_salary = isset($check_attendance['basic_salary']) ? $check_attendance['basic_salary'] : 0;

            // Calculate the prorated amount if the month is not complete
            $basic_salary_current_day = $basic_salary;
            if ($currentDayOfMonth < $totalDaysInMonth) {
                $basic_salary_current_day = ($basic_salary / $totalDaysInMonth) * $currentDayOfMonth;
            }
            //    return([ $basic_salary,$currentDayOfMonth , $totalDaysInMonth]);
            $total_amount = ($overtime_amount - $late_amount - $total_absen_penalty) + $basic_salary_current_day;
            $salary = [
                'overtime_amount' => number_format(isset($overtime_amount) ? $overtime_amount : 0, 2, '.', ''),
                'late_amount' => number_format(isset($late_amount) ? $late_amount : 0, 2, '.', ''),
                'total_absen_penalty' => number_format(isset($total_absen_penalty) ? $total_absen_penalty : 0, 2, '.', ''),
                'basic_salary' => number_format($basic_salary, 2, '.', ''),
                'basic_salary_current_day' => number_format($basic_salary_current_day, 2, '.', ''),

                'amount' => number_format($total_amount, 2, '.', ''),
                'advance' => number_format($salary_process->sum('advance_amount'), 2, '.', ''),
                'paid_salary' => number_format($already_payment->sum('amount'), 2, '.', ''),
            ];
        }



        return Response()->json([
            'page' => view('backend.payroll.employee.view-modal', [
                'employee_info' => $employee_info,
                'salaryTypes' => $salaryTypes,
                'countrytCode' => $countrytCode,
                'departments' => $department,
                'countries' => $countries,
                'countrytCode2' => $countrytCode2,
                'countrytCode3' => $countrytCode3,
                'pro_quali' => $pro_quali,
                'divisions' => $divisions,
                'roles' => $roles,
                'emp_policy' => $emp_policy,
                'job_types' => $job_types,
                'document_lists' => $document_lists,
                'notice_lits' => $notice_lits,
                'salary' => $salary,
            ])->render(),
            'emp_id' =>  $employee_info->emp_id,
        ]);
    }

    public function getPayroll(Request $request)
    {
        $employee = Employee::find($request->employee_id)->first();

        $date = Carbon::now();

        $salary_month = $request->month;
        $salary_year = $request->year;
        $monthYear = $salary_year - $salary_month;

        $totalDaysInMonth = Carbon::parse($monthYear)->daysInMonth;
        $currentDayOfMonth = $date->format('Y-m') == $monthYear ? $date->day : $totalDaysInMonth;

        $salary_process = SalaryProcess::where('employee_id', $employee->id)->where('advance_amount', '>', 0)->get();
        $already_payment = SalaryProcess::where('employee_id', $employee->id)->where('status', 1)->where('month', $salary_month)->where('year', $salary_year)->get();

        if ($salary_month && $salary_year) {
            $salary_date = new DateTime("$salary_year-$salary_month-01");

            if ($employee) {
                $last_visite = !empty($employee->last_visite) ? new DateTime($employee->last_visite) : (!empty($employee->joining_date) ? new DateTime($employee->joining_date) : null);
                $v_type = !empty($employee->last_visite) ? 'l' : 'j';

                if ($last_visite) {
                    $interval = $salary_date->diff($last_visite);
                    $months_difference = ($interval->y * 12) + $interval->m;
                    $text = $months_difference;
                }
            }
        }

        $basic_salary = 0;
        $check_attendance = EmployeeAttendance::check_attendance($employee->id, $salary_month, $salary_year, $basic_salary);
        $overtime_amount = isset($check_attendance['overtime_amount']) ? str_replace(',', '', $check_attendance['overtime_amount']) : 0;
        $late_amount = isset($check_attendance['late_amount']) ? str_replace(',', '', $check_attendance['late_amount']) : 0;
        $total_absen_penalty = isset($check_attendance['total_absen_penalty']) ? $check_attendance['total_absen_penalty'] : 0;
        $basic_salary = isset($check_attendance['basic_salary']) ? $check_attendance['basic_salary'] : 0;

        // Calculate the prorated amount if the month is not complete
        $basic_salary_current_day = $basic_salary;
        if ($currentDayOfMonth < $totalDaysInMonth) {
            $basic_salary_current_day = ($basic_salary / $totalDaysInMonth) * $currentDayOfMonth;
        }
        //    return([ $basic_salary,$currentDayOfMonth , $totalDaysInMonth]);
        $total_amount = ($overtime_amount - $late_amount - $total_absen_penalty) + $basic_salary_current_day;
        $salary = [
            'overtime_amount' => number_format(isset($overtime_amount) ? $overtime_amount : 0, 2, '.', ''),
            'late_amount' => number_format(isset($late_amount) ? $late_amount : 0, 2, '.', ''),
            'total_absen_penalty' => number_format(isset($total_absen_penalty) ? $total_absen_penalty : 0, 2, '.', ''),
            'basic_salary' => number_format($basic_salary, 2, '.', ''),
            'basic_salary_current_day' => number_format($basic_salary_current_day, 2, '.', ''),

            'amount' => number_format($total_amount, 2, '.', ''),
            'advance' => number_format($salary_process->sum('advance_amount'), 2, '.', ''),
            'paid_salary' => number_format($already_payment->sum('amount'), 2, '.', ''),
        ];


        return Response()->json([
            'page' => view('backend.payroll.employee.payroll_details', [
                'salary' => $salary,
            ])->render(),
        ]);
    }

    public function print_salary_certificate($id)
    {
        $employee_info = EmployeeTemp::find($id);
        $emp_policy = policy_helper($employee_info->emp_id, date('Y-m-d'));
        $basic_slary = 0;
        if ($emp_policy) {
            $basic_slary =  $emp_policy->basic_sslary ?? 0;
        }



        return Response()->json([
            'page' => view('backend.payroll.employee.salary-certificate', [
                'employee_info' => $employee_info,
                'basic' => $basic_slary,

            ])->render(),

        ]);
    }

    public function find_policy(Request $request)
    {
        $employee_info = EmployeeTemp::find($request->emp_id);
        $joining_date = $employee_info->joining_date ?? date('Y-m-d');

        $policy = Policy::where('effect_date', '<=', $joining_date)->orderBy('effect_date', 'desc')->first();
        if (!$policy) {
            $policy = Policy::where('effect_date', '>=', $joining_date)->orderBy('effect_date', 'desc')->first();
        }
        return $policy;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee_info = EmployeeTemp::find($id);
        $salaryTypes = SalaryType::all();
        $countrytCode = Country::get();
        $countrytCode2 = Country::get();
        $countrytCode3 = Country::get();
        $departments = Department::get();
        $job_types = JobTypeInfo::get();

        $countries = Country::get();

        $divisions = Division::get();
        $roles = Role::get();
        $date = date('Y-m-d');
        $emp_policy = policy_helper($employee_info->emp_id, $date);
        $pro_quali = PorfessionalDocument::where('employee_id', $employee_info->emp_id)->get();
        // $accoutHeads = AccountHead::all();
        // return view('backend.payroll.employee.edit-modal', compact( 'employee_info','salaryTypes',
        //     'countrytCode','department','nationality','grades', 'countries','branchs'));

        // dd($employee_info->emp_id);
        $document_lists = EmployeeDocument::where('employee_id', $employee_info->id)->get();
        return Response()->json([
            'page' => view('backend.payroll.employee.edit-modal', [
                'employee_info' => $employee_info,
                'salaryTypes' => $salaryTypes,
                'countrytCode' => $countrytCode,
                'departments' => $departments,
                'countries' => $countries,
                'job_types' => $job_types,
                'countrytCode2' => $countrytCode2,
                'countrytCode3' => $countrytCode3,
                'pro_quali' => $pro_quali,
                'divisions' => $divisions,
                'emp_policy' => $emp_policy,
                'roles' => $roles,
                'document_lists' => $document_lists,

            ])->render(),

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Gate::authorize('HR_Edit');

        $employee = EmployeeTemp::where('id', $id)->first();
        $employee_image = $employee->employee_image;
        $vissa_image = $employee->vissa_image;

        if ($request->hasFile('document')) {
            foreach ($request->file('document') as $index => $file) {
                $type = $request->document_type[$index];
                $name = $file->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext = $file->getClientOriginalExtension();
                $other_document = $employee->id . uniqid() . '.' . $ext;
                $file->storeAs('public/upload/other-documents', $other_document);
                $other_doc = new EmployeeDocument;
                $other_doc->name = $request->document_name ? $request->document_name : 'N/A';
                $other_doc->filename = $other_document;
                $other_doc->employee_id = $id;
                $other_doc->type = $type;
                $other_doc->ext = $ext;
                $other_doc->save();

                if ($type == 'profile_image') {
                    $oldFilePath = 'public/upload/employee/' . $employee->employee_image;

                    if (\Storage::exists($oldFilePath)) {
                        \Storage::delete($oldFilePath);
                    }

                    $name = $file->getClientOriginalName();
                    $name = pathinfo($name, PATHINFO_FILENAME);
                    $ext = $file->getClientOriginalExtension();
                    $employee_image = 'employee_image' . time() . '.' . $ext;

                    $file->storeAs('public/upload/employee', $employee_image);
                }

                if ($type == 'profile_image') {
                    $name = $file->getClientOriginalName();
                    $name = pathinfo($name, PATHINFO_FILENAME);
                    $ext = $file->getClientOriginalExtension();
                    $vissa_image = 'vissa_image' . time() . '.' . $ext;

                    $file->storeAs('public/upload/employee', $vissa_image);
                }
            }
        }



        if ($request->file('employee_image')) {
            $name = $request->file('employee_image')->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $request->file('employee_image')->getClientOriginalExtension();
            $employee_image = 'employee_image' . time() . '.' . $ext;

            $request->file('employee_image')->storeAs('public/upload/employee', $employee_image);
        }
        $emirates_image = $employee->emirates_image;

        if ($request->file('emirates_image')) {
            $name = $request->file('emirates_image')->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $request->file('emirates_image')->getClientOriginalExtension();
            $emirates_image = 'emirates_image' . time() . '.' . $ext;

            $request->file('emirates_image')->storeAs('public/upload/employee', $emirates_image);
        }


        if ($request->file('vissa_image')) {
            $name = $request->file('vissa_image')->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $request->file('vissa_image')->getClientOriginalExtension();
            $vissa_image = 'vissa_image' . time() . '.' . $ext;

            $request->file('vissa_image')->storeAs('public/upload/employee', $vissa_image);
        }

        $image = $employee->image;
        if ($request->file('image')) {
            $name = $request->file('image')->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $request->file('image')->getClientOriginalExtension();
            $image = 'image' . time() . '.' . $ext;

            $request->file('image')->storeAs('public/upload/employee', $image);
        }

        //dob
        $dob = $employee->dob;
        if ($request->dob) {
            $old_date = explode('/', $request->dob);

            $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
            $new_date = date('Y-m-d', strtotime($new_data));
            $dob = \DateTime::createFromFormat("Y-m-d", $new_date);
        }
        $joining_date = $employee->joining_date;
        if ($request->joining_date) {

            //joining_date
            $old_date = explode('/', $request->joining_date);
            $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
            $new_date = date('Y-m-d', strtotime($new_data));
            $joining_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        }

        //$request->visa_expiry_date
        $visa_expiry_date = $employee->visa_expiry_date;
        if ($request->visa_expiry_date) {
            $old_date = explode('/', $request->visa_expiry_date);

            $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
            $new_date = date('Y-m-d', strtotime($new_data));
            $visa_expiry_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        }
        $vissa_issue = $employee->vissa_issue;
        if ($request->vissa_issue) {

            $vissa_issue1 = explode('/', $request->vissa_issue);

            $new_data = $vissa_issue1[0] . '-' . $vissa_issue1[1] . '-' . $vissa_issue1[2];
            $new_date = date('Y-m-d', strtotime($new_data));
            $vissa_issue = \DateTime::createFromFormat("Y-m-d", $new_date);
        }
        $effect_date = '';

        if (isset($request->effect_date)) {
            $vissa_issue1 = explode('/', $request->effect_date);
            $new_data = $vissa_issue1[0] . '-' . $vissa_issue1[1] . '-' . $vissa_issue1[2];
            $new_date = date('Y-m-d', strtotime($new_data));
            $effect_date = \DateTime::createFromFormat("Y-m-d", $new_date);
            $effect_date = $effect_date ? $effect_date->format('Y-m-d') : null; // Convert to string format
        }
        $last_visite =  $employee->last_visite;;

        if (isset($request->last_visite)) {

            $vissa_issue1 = explode('/', $request->last_visite);

            $new_data = $vissa_issue1[0] . '-' . $vissa_issue1[1] . '-' . $vissa_issue1[2];
            $new_date = date('Y-m-d', strtotime($new_data));
            $last_visite = \DateTime::createFromFormat("Y-m-d", $new_date);
        }

        if ($request->leave_date) {
            $vissa_issue1 = explode('/', $request->leave_date);
            $new_data = $vissa_issue1[0] . '-' . $vissa_issue1[1] . '-' . $vissa_issue1[2];
            $new_date = date('Y-m-d', strtotime($new_data));
            $leave_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        }

        if ($request->job_status == 0) {
            $leave_date = null;
        }

        $full_name = ($request->first_name ?? $employee->first_name) . ' ' . ($request->middle_name ?? $employee->middle_name) . ' ' . ($request->last_name ?? $employee->last_name);

        $employee->update([
            'first_name' => $request->first_name ?? $employee->first_name,
            'middle_name' => $request->middle_name ?? $employee->middle_name,
            'last_name' => $request->last_name ?? $employee->last_name,
            'full_name' => str_replace('  ', ' ', $full_name),
            'salutation' => $request->salutation ?? $employee->salutation,
            'emp_id' => $employee->emp_id,
            'dob' => $dob ?? $employee->dob,
            'nationality' => $request->nationality ?? $employee->nationality,
            'employee_image' => $employee_image ?? $employee->employee_image,
            'image' => $image ?? $employee->image,

            'job_type' => $request->job_type ?? $employee->job_type,
            'job_status' => $request->job_status ?? $employee->job_status,
            'leave_date' => $leave_date ?? $employee->leave_date,
            'blood_group' => $request->blood_group ?? $employee->blood_group,

            'parmanent_address' => $request->parmanent_address ?? $employee->parmanent_address,
            'contact_number' => $request->contact_number ?? $employee->contact_number,
            'local_contact_number' => $request->local_contact_number ?? $employee->local_contact_number,

            'email' => $request->email ?? $employee->email,

            'em_name_local' => $request->em_name_local ?? $employee->em_name_local,
            'em_parmanent_address_local' => $request->em_parmanent_address_local ?? $employee->em_parmanent_address_local,
            'em_city_local' => $request->em_city_local ?? $employee->em_city_local,
            'em_contact_number_local' => $request->em_contact_number_local ?? $employee->em_contact_number_local,
            'em_email_local' => $request->em_email_local ?? $employee->em_email_local,
            'em_country_local' => $request->em_country_local ?? $employee->em_country_local,

            'em_name_origin' => $request->em_name_origin ?? $employee->em_name_origin,
            'em_parmanent_address_origin' => $request->em_parmanent_address_origin ?? $employee->em_parmanent_address_origin,
            'em_city_origin' => $request->em_city_origin ?? $employee->em_city_origin,
            'em_contact_number_origin' => $request->em_contact_number_origin ?? $employee->em_contact_number_origin,
            'em_email_origin' => $request->em_email_origin ?? $employee->em_email_origin,
            'em_country_origin' => $request->em_country_origin ?? $employee->em_country_origin,

            'emirates_id' => $request->emirates_id ?? $employee->emirates_id,
            'emirates_image' => $emirates_image ?? $employee->emirates_image,
            'passport_number' => $request->passport_number ?? $employee->passport_number,
            'vissa_image' => $vissa_image ?? $employee->vissa_image,
            'visa_number' => $request->visa_number ?? $employee->visa_number,
            'visa_type' => $request->visa_type ?? $employee->visa_type,
            'pass_issue_country' => $request->pass_issue_country ?? $employee->pass_issue_country,
            'visa_issue_country' => $request->visa_issue_country ?? $employee->visa_issue_country,

            'company' => $request->company ?? $employee->company,
            'division' => $request->division ?? $employee->division,
            'department' => $request->department ?? $employee->department,
            'designation' => $request->designation ?? $employee->designation,
            'joining_date' => $joining_date ?? $employee->joining_date,
            'role' => $request->division == 6 ? 5 : $employee->role,

            'qualification' => $request->qualification ?? $employee->qualification,

            'bank_name' => $request->bank_name ?? $employee->bank_name,
            'branch_name' => $request->branch_name ?? $employee->branch_name,
            'account_number' => $request->account_number ?? $employee->account_number,
            'ibal_number' => $request->ibal_number ?? $employee->ibal_number,
            'routing_number' => $request->routing_number ?? $employee->routing_number,
            'swift_code' => $request->swift_code ?? $employee->swift_code,

            'status' => 2,

            'visa_expiry_date' => $visa_expiry_date ?? $employee->visa_expiry_date,
            'employee_wage_type' => $request->employee_wage_type ?? $employee->employee_wage_type,
            'employment_location' => $request->employment_location ?? $employee->employment_location,
            'basic_salary' => $request->basic_salary ?? $employee->basic_salary,
            'payment_method' => $request->payment_method ?? $employee->payment_method,
            'grade' => $request->grade ?? $employee->grade,

            'description' => $request->description ?? $employee->description,
            'sub_description' => $request->sub_description ?? $employee->sub_description,

            'gender' => $request->gender ?? $employee->gender,
            'post_box' => $request->post_box ?? $employee->post_box,
            'code' => $request->employee_code ?? $employee->code,
            // 'marital_status' => $request->marital_status ?? $employee->marital_status,
            'kids_no' => $request->kids_no ?? $employee->kids_no,
            'health_status' => $request->health_status ?? $employee->health_status,
            'city' => $request->city ?? $employee->city,

            'em_relation' => $request->em_relation ?? $employee->em_relation,
            'job_title' => $request->job_title ?? $employee->job_title,
            'job_description' => $request->job_description ?? $employee->job_description,
            'vissa_issue' => $vissa_issue ?? $employee->vissa_issue,
            // 'last_visite' => $last_visite ?? $employee->last_visite,

            'bank_name' => $request->bank_name ?? $employee->bank_name,
            'branch_name' => $request->branch_name ?? $employee->branch_name,
            'account_number' => $request->account_number ?? $employee->account_number,
            'account_name' => $request->account_name ?? $employee->account_name,
            'ibal_number' => $request->ibal_number ?? $employee->ibal_number,
            'account_type' => $request->account_type ?? $employee->account_type,
        ]);

        if (isset($request->policy_type)) {
            $emp_policy = EmployeePolicy::where('employee_id', $employee->emp_id)
                ->where('effect_date', $effect_date)
                ->first();

            $policy_data = [
                'employee_id'                => $employee->emp_id,
                'policy_type'                => $request->policy_type,
                'effect_date'                => $effect_date,
                'air_ticket_eligibility'     => $request->air_ticket_eligibility,
                'apply_over_time'            => $request->apply_over_time,
                'vacation_paid_or_unpaid'    => $request->vacation_paid_or_unpaid,
                'vacation_type'              => $request->vacation_type,
                'minimum_day_for_ticket_price' => $request->minimum_day_for_ticket_price,
                'm_ref_in_time'              => $request->m_ref_in_time,
                'm_ref_out_time'             => $request->m_ref_out_time,
                'time_zone'                  => $request->time_zone,
                'description'                => $request->description,
                'ticket_price_percentage'    => $request->ticket_price_percentage,
                'maximum_time_for_attendace' => $request->maximum_time_for_attendace,
                'late_type'                  => $request->late_type,
                'minimum_day_for_late'       => $request->minimum_day_for_late,
                'minimum_hours_for_late'     => $request->minimum_hours_for_late,
                'salary_loss'                => $request->salary_loss,
                'cash_redeem'                => $request->cash_redeem,
                'overtime_rate'              => $request->overtime_rate,
                'min_hours_for_overtime'     => $request->min_hours_for_overtime,
                'late_grace_time'            => $request->late_grace_time,
                'number_of_yearly_vacation'  => $request->number_of_yearly_vacation,
                'e_ref_in_time'              => $request->e_ref_in_time,
                'e_ref_out_time'             => $request->e_ref_out_time,
                'minimun_vacation_priod'     => $request->minimun_vacation_priod,
                'basic_salary'             => $request->basic_salary,



            ];

            if ($emp_policy) {
                $emp_policy->update($policy_data);
            } else {
                EmployeePolicy::create($policy_data);
            }
        }

        $message = 'Employee Update successfully!';

        $document_lists = EmployeeDocument::where('employee_id', $employee->id)->get();

        if ($employee->status == 1) {
            $this->approve(request(), $employee->id); // pass current request
        }


        // return response()->json('id' => $employee->id , 'message' => $message,]);
        $page = view('backend.payroll.employee.other-document', ['id' => $employee->id, 'message' => $message, 'document_lists' => $document_lists])->render();

        return response()->json(['id' => $employee->id, 'message' => $message, 'page' => $page]);
    }


    public function other_document_delete(Request $request)
    {
        $doc = EmployeeDocument::find($request->id);
        $doc->delete();
        return 1;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('HRPAYROLl_Delete');
        $employee = EmployeeTemp::find($id);

        $emp_policy = EmployeePolicy::where('employee_id', $employee->emp_id)->get();

        // Find the corresponding employee record
        $employee1 = Employee::where('emp_id',  $employee->emp_id)->where('full_name', $employee->full_name)->first();

        if (!$employee1) {
            // Define paths to the images
            $employeeImagePath = public_path('storage/upload/employee/') . $employee->employee_image;
            $emiratesImagePath = public_path('storage/upload/employee/') . $employee->emirates_image;
            $vissaImagePath = public_path('storage/upload/employee/') . $employee->vissa_image;
            $otherImagePath = public_path('storage/upload/employee/') . $employee->_image;

            // Delete the files if they exist
            if (file_exists($employeeImagePath) && !is_dir($employeeImagePath)) {
                unlink($employeeImagePath);
            }

            if (file_exists($emiratesImagePath) && !is_dir($emiratesImagePath)) {
                unlink($emiratesImagePath);
            }

            if (file_exists($vissaImagePath) && !is_dir($vissaImagePath)) {
                unlink($vissaImagePath);
            }

            if (file_exists($otherImagePath) && !is_dir($otherImagePath)) {
                unlink($otherImagePath);
            }

            // Finally, delete the employee record
            $employee->delete();
            $emp_policy->each->delete();
            // Success notification
            $notification = array(
                'message'       => 'Employee Deleted successfully!',
                'alert-type'    => 'success'
            );
        } else {
            // Warning notification if the employee is already approved
            $notification = array(
                'message'       => 'Employee Already Approved !!',
                'alert-type'    => 'warning'
            );
        }

        // Redirect back with the appropriate notification
        return redirect()->back()->with($notification);
    }



    public function employeeProDocumentDelete(Request $request)
    {
        $employeeDocument = PorfessionalDocument::find($request->id);
        $path = public_path('storage/upload/employee/post_quali/') . $employeeDocument->image;
        if (file_exists($path)) {
            unlink($path);
        }
        $employee_id = $employeeDocument->employee_id;
        $employeeDocument->delete();
        $others = PorfessionalDocument::where('employee_id', $employee_id)->get();
        // $notification = array(
        //     'message'       => 'Employee Salary Deleted successfully!',
        //     'alert-type'    => 'success'
        // );
        return Response()->json([
            'page' => view('backend.payroll.employee.ajaxImage', ['others' => $others, 'i' => 1])->render(),

        ]);
    }

    public function employeePriview(Request $request)
    {
        $employee = Employee::find($request->id);
        $salaryTypes = SalaryType::all();
        $countrytCode = Country::get();
        $countrytCode2 = Country::get();
        $countrytCode3 = Country::get();
        $department = Department::get();
        $nationality = Nationality::get();
        $countries = Country::get();

        $pro_quali = PorfessionalDocument::where('employee_id', $employee->emp_id)->get();
        // $notification = array(
        //     'message'       => 'Employee Salary Deleted successfully!',
        //     'alert-type'    => 'success'
        // );
        return Response()->json([
            'page' => view('backend.payroll.employee.view-modal', [
                'employee_info' => $employee,
                'salaryTypes' => $salaryTypes,
                'countrytCode' => $countrytCode,
                'department' => $department,
                'nationality' => $nationality,
                'countries' => $countries,
                'countrytCode2' => $countrytCode2,
                'countrytCode3' => $countrytCode3,
                'pro_quali' => $pro_quali
            ])->render()
        ]);
    }

    public function employeeInfo(Request $request)
    {
        $emp_info = Employee::where('emp_id', 'like', '%' . $request->id . '%')->orWhere('name', 'like', '%' . $request->id . '%')->get();
        // dd($emp_name);
        // return $emp_name;
        if ($emp_info->count() > 0) {
            if ($request->ajax()) {
                return Response()->json([
                    'page' => view('backend.payroll.employee.ajaxEmpList', ['empList' => $emp_info, 'i' => 1])->render(),

                ]);
            }
        }
    }

    public function findCurrency(Request $request)
    {
        $currency = Country::find($request->id);
        return Response()->json([
            'currency' => $currency->currency,

        ]);
    }

    public function approve(Request $request, $id)
    {
        Gate::authorize('HR_Approve');

        //  dd($id);
        // $request->validate([
        //     'name' => 'required'
        // ]);
        Gate::authorize('Employee');

        $temp = EmployeeTemp::find($id);

        if ($temp->full_name == null || $temp->email==null || $temp->joining_date == null) {
            return back()->with(['alert-type' => 'warning', 'message' => 'Employee approval requires both email and name with joining date']);
        }

        $temp = EmployeeTemp::find($id);

        $temp->update([
            'status' => 1,
            'approved_by' => Auth::id()
        ]);


        $check = Employee::where('emp_id',  $temp->emp_id)->where('full_name', $temp->full_name)->get();

        if (count($check) == 0) {
            $employee_value = Employee::create([
                'first_name' => $temp->first_name,
                'middle_name' => $temp->middle_name,
                'last_name' => $temp->last_name,
                'full_name' => $temp->full_name,


                'salutation' => $temp->salutation,
                'emp_id' => $temp->emp_id,
                'job_status' => $temp->job_status,

                'dob' => $temp->dob,
                'nationality' => $temp->nationality,
                'employee_image' => $temp->employee_image,
                'remaining_vacation' => $temp->remaining_vacation,


                'job_type' => $temp->job_type,

                'blood_group' => $temp->blood_group,
                'pr_city' => $temp->pr_city,
                'pr_country' => $temp->pr_country,
                'parmanent_address' => $temp->parmanent_address,
                'contact_number' => $temp->contact_number,
                'local_country_code' => $temp->local_country_code,
                'local_contact_number' => $temp->local_contact_number,
                'email' => $temp->email,

                'em_name' => $temp->em_name,
                'em_parmanent_address' => $temp->em_parmanent_address,
                'em_country_code' => $temp->em_country_code,
                'em_contact_number' => $temp->em_contact_number,
                'em_email' => $temp->em_email,

                'emirates_id' => $temp->emirates_id,
                'emirates_image' => $temp->emirates_image,
                'passport_number' => $temp->passport_number,
                'passport_image' => $temp->passport_image,
                'vissa_image' => $temp->vissa_image,
                'image' => $temp->image,


                'visa_number' => $temp->visa_number,
                'visa_type' => $temp->visa_type,
                'pass_issue_country' => $temp->pass_issue_country,
                'visa_issue_country' => $temp->visa_issue_country,
                // 'last_visite' =>  $temp->last_visite,

                'company' => $temp->company,
                'division' => $temp->division,
                'department' => $temp->department,
                'designation' => $temp->designation,
                'joining_date' => $temp->joining_date,
                'role' => $temp->role,

                'qualification' => $temp->qualification,

                'bank_name' => $temp->bank_name,
                'branch_name' =>  $temp->branch_name,
                'account_number' =>  $temp->account_number,
                'account_name' =>  $temp->account_name,
                'ibal_number' =>  $temp->ibal_number,
                'account_type' =>  $temp->account_type,
                'swift_code' => $temp->swift_code,

                'visa_expiry_date' => $temp->visa_expiry_date,
                'employee_wage_type' => $temp->employee_wage_type,
                'employment_location' => $temp->employment_location,
                'basic_salary' => $temp->basic_salary,
                'payment_method' => $temp->payment_method,
                'grade' => $temp->grade,

                'status' => 1,

                'description' => $temp->description,
                'sub_description' => $temp->sub_description,

                'gender' => $temp->gender,
                'post_box' => $temp->post_box,
                'code' => $temp->code,
                // 'marital_status' => $temp->marital_status,
                'kids_no' => $temp->kids_no,
                'health_status' => $temp->health_status,
                'city' => $temp->city,

                'em_relation' => $request->em_relation,
                'job_title' => $request->job_title,
                'job_description' => $request->job_description,
                'vissa_issue' => $temp->vissa_issue,
                'leave_date' => $temp->leave_date,
            ]);

            //party create
            $latest = PartyInfo::withTrashed()->orderBy('id', 'DESC')->first();

            if ($latest) {
                $pi_code = preg_replace('/^PI-/', '', $latest->pi_code);
                ++$pi_code;
            } else {
                $pi_code = 1;
            }
            if ($pi_code < 10) {
                $cc = "PI-000" . $pi_code;
            } elseif ($pi_code < 100) {
                $cc = "PI-00" . $pi_code;
            } elseif ($pi_code < 1000) {
                $cc = "PI-0" . $pi_code;
            } else {
                $cc = "PI-" . $pi_code;
            }

            $draftCost = new PartyInfo();
            $draftCost->pi_code = $cc;
            $draftCost->emp_id = $employee_value->id;
            $draftCost->pi_name = $employee_value->salutation . ' ' . $employee_value->first_name . ' ' . $employee_value->middle_name . ' ' . $employee_value->last_name;
            $draftCost->pi_type = 'Employee';
            $draftCost->address = $employee_value->parmanent_address;
            $draftCost->con_person = $employee_value->em_name;
            $draftCost->con_no = $employee_value->em_country_code . $employee_value->em_contact_number;
            $draftCost->phone_no =  $employee_value->ontact_number;
            $draftCost->email = $employee_value->email;
            $draftCost->save();
        } else {
            $employee_value = Employee::where('emp_id', $temp->emp_id)->where('full_name', $temp->full_name)->first();
            $employee_value->update([
                'first_name' => $temp->first_name,
                'middle_name' => $temp->middle_name,
                'last_name' => $temp->last_name,
                'full_name' => $temp->full_name,

                'salutation' => $temp->salutation,
                'emp_id' => $temp->emp_id,

                'dob' => $temp->dob,
                'nationality' => $temp->nationality,
                'employee_image' => $temp->employee_image,
                'remaining_vacation' => $temp->remaining_vacation,

                'job_type' => $temp->job_type,

                'blood_group' => $temp->blood_group,
                'pr_city' => $temp->pr_city,
                'pr_country' => $temp->pr_country,
                'parmanent_address' => $temp->parmanent_address,
                'contact_number' => $temp->contact_number,
                'local_country_code' => $temp->local_country_code,
                'local_contact_number' => $temp->local_contact_number,
                'email' => $temp->email,

                'em_name' => $temp->em_name,
                'em_parmanent_address' => $temp->em_parmanent_address,
                'em_country_code' => $temp->em_country_code,
                'em_contact_number' => $temp->em_contact_number,
                'em_email' => $temp->em_email,

                'emirates_id' => $temp->emirates_id,
                'emirates_image' => $temp->emirates_image,
                'passport_number' => $temp->passport_number,
                'passport_image' => $temp->passport_image,
                'vissa_image' => $temp->vissa_image,
                'image' => $temp->image,


                'visa_number' => $temp->visa_number,
                'visa_type' => $temp->visa_type,
                'pass_issue_country' => $temp->pass_issue_country,
                'visa_issue_country' => $temp->visa_issue_country,
                // 'last_visite' =>  $temp->last_visite,

                'company' => $temp->company,
                'division' => $temp->division,
                'department' => $temp->department,
                'designation' => $temp->designation,
                'joining_date' => $temp->joining_date,
                'role' => $temp->role,

                'qualification' => $temp->qualification,


                'bank_name' =>  $temp->bank_name,
                'branch_name' =>  $temp->branch_name,
                'account_number' =>  $temp->account_number,
                'account_name' =>  $temp->account_name,
                'ibal_number' =>  $temp->ibal_number,
                'account_type' =>  $temp->account_type,
                'swift_code' => $temp->swift_code,

                'visa_expiry_date' => $temp->visa_expiry_date,
                'employee_wage_type' => $temp->employee_wage_type,
                'employment_location' => $temp->employment_location,
                'basic_salary' => $temp->basic_salary,
                'payment_method' => $temp->payment_method,
                'grade' => $temp->grade,

                'status' => 1,

                'description' => $temp->description,
                'sub_description' => $temp->sub_description,

                'gender' => $temp->gender,
                'post_box' => $temp->post_box,
                'code' => $temp->code,
                // 'marital_status' => $temp->marital_status,
                'kids_no' => $temp->kids_no,
                'health_status' => $temp->health_status,
                'city' => $temp->city,

                'em_relation' => $request->em_relation,
                'job_title' => $request->job_title,
                'job_description' => $request->job_description,
                'vissa_issue' => $temp->vissa_issue,
                'leave_date' => $temp->leave_date,
            ]);

            //party info update
            $party_id = PartyInfo::where('emp_id', $employee_value->id)->first();
            if ($party_id) {
                $draftCost = PartyInfo::find($party_id->id);
            } else {
                $latest = PartyInfo::withTrashed()->orderBy('id', 'DESC')->first();

                if ($latest) {
                    $pi_code = preg_replace('/^PI-/', '', $latest->pi_code);
                    ++$pi_code;
                } else {
                    $pi_code = 1;
                }
                if ($pi_code < 10) {
                    $cc = "PI-000" . $pi_code;
                } elseif ($pi_code < 100) {
                    $cc = "PI-00" . $pi_code;
                } elseif ($pi_code < 1000) {
                    $cc = "PI-0" . $pi_code;
                } else {
                    $cc = "PI-" . $pi_code;
                }
                $draftCost = new PartyInfo();
                $draftCost->pi_code = $cc;
                $draftCost->emp_id = $employee_value->id;
            }
            $draftCost->pi_name = $employee_value->salutation . ' ' . $employee_value->first_name . ' ' . $employee_value->middle_name . ' ' . $employee_value->last_name;
            $draftCost->pi_type = 'Employee';
            $draftCost->address = $employee_value->parmanent_address;
            $draftCost->con_person = $employee_value->em_name;
            $draftCost->con_no = $employee_value->em_country_code . $employee_value->em_contact_number;
            $draftCost->phone_no =  $employee_value->ontact_number;
            $draftCost->email = $employee_value->email;
            $draftCost->save();
        }

        if ($temp->division != 4) {
            $user = User::where('employee_id',  $employee_value->id)->first();

            $role = Role::where('name', $temp->div->name)->first();

            if(!$role){
                $role = new Role();
                $role->name = $temp->div->name;
                $role->slug = Str::slug($temp->div->name);
                $role->save();
            }

            if ($user) {
                $user->name = $temp->full_name;
                $user->email = $temp->email;
                // $user->password = Hash::make(123456789);
                $user->role_id = $role->id;
                $user->employee_id = $employee_value->id;
                $user->save();
            } else {
                $user_email = User::where('email',  $employee_value->email)->first();
                if (!$user_email) {
                    User::create([
                        'name' => $temp->full_name,
                        'email' => $temp->email,
                        'password' => Hash::make(123456789),
                        'role_id' => $role->id,
                        'employee_id' => $employee_value->id,
                    ]);
                } else {
                    $notification = array(
                        'message'       => 'Approved successfully, But For this employee email user already exist!',
                        'alert-type'    => 'Warning'
                    );
                    return redirect('hr/payroll/employees')->with($notification);
                }
            }
            $sub_head = AccountSubHead::where('employee_id', $employee_value->id)->first();
            if($sub_head){
                $sub_head->name = $employee_value->full_name;
                $sub_head->save();
            }else{
                $sub_head = new AccountSubHead;
                $sub_head->office_id = 1;
                $sub_head->account_head_id = 93;
                $sub_head->name = $employee_value->full_name;
                $sub_head->employee_id = $employee_value->id;
                $sub_head->save();
            }
        }else{
            $sub_head = AccountSubHead::where('employee_id', $employee_value->id)->first();
            if($sub_head){
                $sub_head->forceDelete();
            }
        }



        $notification = array(
            'message'       => 'Approved successfully!',
            'alert-type'    => 'success'
        );
        return redirect('hr/payroll/employees')->with($notification);
    }

    //employee password change

    public function changePassword(Request $request)
    {
        // return $request;

        $request->validate([
            'email' => 'required|email',
            'old_password' => 'required',
            'new_password' => 'required|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->old_password, $user->password)) {
            return response()->json(['error' => 'Old password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers\backend\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll\Employee;
use App\Models\Payroll\LeavePolicy;
use App\Models\Payroll\WeekendHoliday;
use App\Models\Payroll\WeekendPolicyEmployee;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Calculation\Web;

class WeekendHolidayController extends Controller
{
    private function change_date_format($date)
    {
        $date_array = explode('/', $date);
        $date_string = implode('-', $date_array);
        $date = date('Y-m-d', strtotime($date_string));
        return $date;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currentDate = new DateTime();
        $firstDay =0;
        $lastDay = 0;

        $form =  $request->form ? $this->change_date_format($request->form) :$firstDay;
        $search = $request->filled('search') ? trim($request->search) : null;
        $search_array = explode(' ', $search);
        $search1 = null;
        $search2 = null;

        if(count($search_array) == 2){
            $search2 = $search_array[0];
            $search1 = $search_array[0] . '  ' . $search_array[1];
        }

        if(count($search_array) > 2){
            $search2 = $search_array[0] . ' ' . $search_array[1];
            $search1 = $search_array[0] . ' ' . $search_array[1] . '  ' . $search_array[2];
        }

        if(count($search_array) > 3){
            $search2 = $search1 = $search_array[0] . ' ' . $search_array[1] . ' ' . $search_array[2];
            $search1 = $search_array[0] . ' ' . $search_array[1] . '  ' . $search_array[2];
        }

        $date = $form ? Carbon::parse($form) : Carbon::now();
        $request_date = date('Y-m' , strtotime( $date));
        $employees = Employee::where('status', 1)->orderBy('code')
        ->when($search, function ($query) use ($search,$search1,$search2) {
                    $query->where(function ($q) use ($search,$search1,$search2) {
                        $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search1}%")
                        ->orWhere('full_name', 'like', "%{$search2}%")
                        ->orWhere('contact_number', 'like', "%{$search}%")
                        ->orWhere('parmanent_address', 'like', "%{$search}%"); // Fixed typo
                    });
                })->get();


        // Fetch leave policies with filtering

        $numDays = $date->daysInMonth;

        $daysArray = [];

        for ($day = 1; $day <= $numDays; $day++) {
            $currentDay = Carbon::createFromDate($date->year, $date->month, $day);

            $dayData = [
                'day_number' => $currentDay->day,
                'date' => $currentDay->format('Y-m-d')

            ];

            $daysArray[] = $dayData;
        }

        return view('backend.payroll.holiday-weekend.index', compact( 'request_date','form','employees','search' ,'daysArray'));
    }
    public function weekend_default_check(Request $request){
      //   return $request;

        $employee = Employee::where('id', $request->emp_id)
        ->first();
        $defaultDays = json_decode($employee->default_weekend, true);
        $days = 'Default Weekend: ' . implode(', ', $defaultDays);
        return response()->json( $days, 200);
    }

    public function weekend_default(Request $request){
       // return $request;
        $emp_ids = $request->input('emp_id');
        foreach ($emp_ids as $key => $emp_id) {
            $days = $request->days[$emp_id] ?? '';
            // return $days;

            $employee = Employee::where('id', $emp_id)
            ->first();
            $employee->default_weekend = $days;
            $employee->save();
        }
        $notification = array(
            'message' => 'Default Weekend Set Successfully !',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'emp_id' => 'required',
            'date' => 'required',
        ]);


        $is_save = 0;
        $notifications_mult = [];
        $emp_ids = $request->input('emp_id');
        foreach ($emp_ids as $key => $emp_id) {
            $datesString = $request->date[$key];
            $datesArray = explode(',', $datesString);
            foreach ($datesArray as $date) {
                $formatted = \Carbon\Carbon::createFromFormat('d/m/Y', trim($date))->format('Y-m-d');
                $date = $this->change_date_format($formatted);
                $weekendHoliday = WeekendHoliday::where('emp_id', $emp_id)
                ->where('date', $date)
                ->first();
                if (!$weekendHoliday) {
                    $weekendHoliday = new WeekendHoliday();
                    $weekendHoliday->emp_id = $emp_id;
                    $weekendHoliday->date = $date;
                    $weekendHoliday->month = date('F', strtotime($date));
                    $weekendHoliday->weekend = date('l', strtotime($date));
                    $weekendHoliday->status = 1;
                    $weekendHoliday->save();
                    $is_save = 1;

                } else {
                    $notifications_mult[] = [
                        'message' => 'The weekend for ' . $weekendHoliday->emp->full_name . ' has already been scheduled on ' . $formatted . '.',
                        'alert-type' => 'warning'
                    ];
                }
            }

        }

        if($is_save){
            $notifications_mult[] = [
                'message' => 'Weekend Added successfully',
                'alert-type' => 'success'
            ];
        }

        return redirect()->back()->with('notifications_mult', $notifications_mult);
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payroll\WeekendHoliday  $weekendHoliday
     * @return \Illuminate\Http\Response
     */
    public function show(WeekendHoliday $weekendHoliday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payroll\WeekendHoliday  $weekendHoliday
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $policy = LeavePolicy::find($id);
        $employees = Employee::get();
        return view('backend.payroll.holiday-weekend.edit-modal', compact('policy','employees'));
    }


    public function update(Request $request, $id)
    {
        // Validate the input
        $request->validate([
            'emp_id' => 'required|integer',
            'leave_type' => 'required|string',
        ]);

        // Convert dates to the correct format, if provided
        $leaveYearForm = $request->input('leave_year_form') ? $this->change_date_format($request->input('leave_year_form')) : '';
        $leaveYearTo = $request->input('leave_year_to') ? $this->change_date_format($request->input('leave_year_to')) : '';
        $leave_date_form = $request->input('leave_date_form') ? $this->change_date_format($request->input('leave_date_form')) : '';
        $leave_date_to = $request->input('leave_date_to') ? $this->change_date_format($request->input('leave_date_to')) : '';

        // Find the existing leave policy by ID
        $leavePolicy = LeavePolicy::findOrFail($id);

        // Handle file upload if a new file is provided
        if ($request->file('file')) {
            $name = $request->file('file')->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $request->file('file')->getClientOriginalExtension();
            $file = 'leave-document' . time() . '.' . $ext;
            $request->file('file')->storeAs('public/upload/leave-documents', $file);

            if ($leavePolicy->file) {
                Storage::delete('public/upload/leave-documents/' . $leavePolicy->file);
            }
            $leavePolicy->file = $file;
        }

        $leavePolicy->emp_id = $request->input('emp_id');
        $leavePolicy->leave_type = $request->input('leave_type');
        $leavePolicy->origin = $request->input('origin');
        $leavePolicy->leave_year_form = $leaveYearForm;
        $leavePolicy->leave_year_to = $leaveYearTo;
        $leavePolicy->leave_date_form = $leave_date_form;
        $leavePolicy->leave_date_to = $leave_date_to;
        $leavePolicy->leave_year_numbers = $request->input('leave_year_numbers');
        $leavePolicy->yearly_paid_leave_number = $request->input('yearly_paid_leave_number');
        $leavePolicy->leave_day_numbers = $request->input('leave_day_numbers');
        $leavePolicy->paid_leave_day_numbers = $request->input('paid_leave_day_numbers');
        $leavePolicy->description = $request->input('description');

        $leavePolicy->save();

        $notification = array(
            'message' => 'Updated successfully!',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
    public function approve($id)
    {
        $leavePolicy = LeavePolicy::findOrFail($id);

       if($leavePolicy && $leavePolicy->status == 0){
          $leavePolicy->status = 1;
          $leavePolicy->save();

            $notification = array(
                'message' => 'Approve successfully!',
                'alert-type' => 'success'
            );
       }else{
        $notification = array(
            'message' => 'Already approved',
            'alert-type' => 'warning'
        );
       }
       return redirect()->back()->with($notification);
    }

    public function destroy($id)
    {
        $leavePolicy = LeavePolicy::findOrFail($id);

       if($leavePolicy && $leavePolicy->status == 0){
          $leavePolicy->delete();
            $notification = array(
                'message' => 'Delete successfully!',
                'alert-type' => 'success'
            );
       }else{
        $notification = array(
            'message' => 'This can not be delete!',
            'alert-type' => 'warning'
        );
       }


        return redirect()->back()->with($notification);
    }
}

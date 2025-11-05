<?php

namespace App\Http\Controllers\backend\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll\Employee;
use App\Models\Payroll\EmployeeHistory;
use App\Models\Payroll\LeaveDocument;
use App\Models\Payroll\LeaveInformation;
use App\Models\Payroll\Nationality;
use App\Models\Payroll\PorfessionalDocument;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class EmployeeDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        // dd('hi');
        // $startDate = date('Y')."-01-01";
        // $now = Carbon::now()->format('y-m-d');

        // Gate::authorize('app.mapping.index');
        $leave_info = LeaveInformation::orderBy('id', 'desc');
        $employees = Employee::orderBy('id', 'desc');
        if($request->search){
            $employees = $employees->where(function ($query) use ($request) {
                if ($request->search) {
                    $searchTerms = explode(' ', $request->search);
                    foreach ($searchTerms as $term) {
                        $term = trim($term); // Remove leading/trailing spaces from each term
                        if (!empty($term)) { // Skip empty terms
                            $query->orWhere(function ($subquery) use ($term) {
                                $subquery->where('first_name', 'like', '%' . $term . '%');
                                        //  ->orWhere('first_name', 'like', '%' . $term . '%')
                                        //  ->orWhere('middle_name', 'like', '%' . $term . '%')
                                        //  ->orWhere('last_name', 'like', '%' . $term . '%');
                            });
                            $query->orWhere('emp_id', 'like', '%' . $term . '%')
                                ->orWhere('contact_number', 'like', '%' . $term . '%')
                                ->orWhere('local_contact_number', 'like', '%' . $term . '%');
                        }
                    }
                }
            });
        }
        $employees = $employees->paginate(15)->withQueryString();
        // dd($salaryStructure['1']['id']);
        return view('backend.payroll.employee_document.index', compact('leave_info', 'employees'));
    }
    public function teacher_index(Request $request) {
        Gate::authorize('teacher_documents');
        // dd('hi');
        // $startDate = date('Y')."-01-01";
        // $now = Carbon::now()->format('y-m-d');

        //Gate::authorize('app.mapping.index');

        $leave_info = LeaveInformation::orderBy('id', 'desc')->get();
        $employees = Employee::all();
        if($request->search){
            $employees = Employee::orWhere('first_name', 'like', '%' . $request->search. '%')
            ->orWhere('last_name', 'like', '%' . $request->search . '%')
            ->orWhere('contact_number', 'like', '%' . $request->search . '%')->orWhere('designation', 'like', '%' . $request->search . '%')
            ->orWhere('emp_id', 'like', '%' . $request->search . '%')->get();
        }
        // dd($salaryStructure['1']['id']);
        return view('backend.teacher_document.index', compact('leave_info', 'employees'));
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
        // dd($request);
        if ($request->file('file')) {

                $name= $request->file('file')->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext= $request->file('file')->getClientOriginalExtension();
                $file_name= $request->history.time().'.'.$ext;

                $request->file('file')->storeAs( 'public/upload/employee_history', $file_name);
            }

        $employee_history = new EmployeeHistory();
        $employee_history->emp_id = $request->employee_id;
        $employee_history->history = $request->history;
        $employee_history->date = date('Y-m-d');
        $employee_history->remark = $request->remark;
        $employee_history->approved_by = $request->approved_by;
        $employee_history->extension = $ext;
        $employee_history->document = $file_name;
        $employee_history->save();


        $notification= array(
            'message'       => 'Created successfully!',
            'alert-type'    => 'success'
        );
        return back()->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $histories= EmployeeHistory::where('emp_id', $id)->get();
        $employee = Employee::find($id);
        $professional_document = PorfessionalDocument::where('employee_id',$employee->emp_id)->get();
        return Response()->json([
            'page' => view('backend.payroll.employee_document.view-modal', ['histories' => $histories,
                                                                     'employee' => $employee,
                                                                     'professional_document' => $professional_document,
                                                                     ])->render()


        ]);
        // return view('backend.payroll.employee_document.view-modal', compact('histories','employee','professional_document'));
    }
    public function teacher_show($id)
    {
        $histories= EmployeeHistory::where('emp_id', $id)->get();
        $employee = Employee::find($id);
        $professional_document = PorfessionalDocument::where('employee_id',$employee->emp_id)->get();
        return Response()->json([
            'page' => view('backend.teacher_document.view-modal', ['histories' => $histories,
                                                                     'employee' => $employee,
                                                                     'professional_document' => $professional_document,
                                                                     ])->render()


        ]);
        // return view('backend.payroll.employee_document.view-modal', compact('histories','employee','professional_document'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $nationality_info = Nationality::find($id);
        $nationalities = Nationality::orderBy('id', 'desc')->get();
        return view('backend.payroll.nationality.edit', compact('nationality_info', 'nationalities'));
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
        // $request->validate([
        //     'name' => 'required',
        // ]);
        $employee_leave = LeaveInformation::find($id);
        $employee_leave->employee_id = $request->employee_id;
        $employee_leave->leave_type = $request->leave_type;
        $employee_leave->from_date = $request->from_date;
        $employee_leave->to_date = $request->to_date;
        $employee_leave->days_leave = $request->days;
        $employee_leave->leave_reason = $request->leave_reason;
        $employee_leave->save();
        if($request->file('files')){
            foreach($request->file('files') as $file){
                $name= $file->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext= $file->getClientOriginalExtension();
                $studentImageName= $name.time().'.'.$ext;

                $file->storeAs( 'public/upload/employee-leave', $studentImageName);

                LeaveDocument::create([
                    'name'              => $name,
                    'filename'          => $studentImageName,
                    'leave_info_id'     => $employee_leave->id,
                    'extension'         => $ext
                ]);
            // $ext= $request->document2->getClientOriginalExtension();
            // $document_two= 'b'.time().$request->pi_name.'.'.$ext;
            // $emirates_id_upload = $request->file('document2')->storeAs( 'public/upload/service-provider', $document_two);
            // $draftCost->document2= $document_two;
            // $draftCost->extension2= $ext;
            }
        }

        $notification= array(
            'message'       => 'Update successfully!',
            'alert-type'    => 'success'
        );
        return redirect('leave-management')->with($notification);
    }

    public function employee_history_view(Request $request){
        $histories= EmployeeHistory::where('emp_id', $request->id)->get();
        $employee = Employee::find($request->id);
        $professional_document = PorfessionalDocument::where('employee_id',$employee->emp_id)->get();
        return view('backend.payroll.employee_document.view-modal', compact('histories','employee','professional_document'));
    }
//Professional Document update start

    public function professionalDocumentEdit($id)
    {
        $employee = Employee::find($id);
        $documents = PorfessionalDocument:: where('employee_id', $employee->emp_id)->orderBy('id', 'desc')->get();
        return Response()->json([
            'page' => view('backend.payroll.employee_document.professional-document-edit-modal', ['documents' => $documents ])->render()


        ]);
    }

    public function professionalDocumentUpdate(Request $request) {

        // dd($request->all());
            foreach($request->records as $key => $value){
                if ($request->records[$key]['head']) {
                    if($request->records[$key]['file']) {
                        $name= $request->records[$key]['file']->getClientOriginalName();
                        $name = pathinfo($name, PATHINFO_FILENAME);
                        $ext= $request->records[$key]['file']->getClientOriginalExtension();
                        $employee_image= 'hredit'.$key.time().'.'.$ext;

                        $request->records[$key]['file']->storeAs( 'public/upload/employee/post_quali', $employee_image);

                        PorfessionalDocument::find($request->records[$key]['head'])->update([
                            'image' => $employee_image,

                        ]);
                    }
                }
            }
            $notification= array(
                'message'       => 'Update successfully!',
                'alert-type'    => 'success'
            );
            return redirect('employee-document')->with($notification);
    }


    //Professional Document update End

//History Document update Start
    public function historyDocumentEdit($id) {

        // $employee = Employee::find($id);
        $documents = EmployeeHistory:: where('emp_id', $id)->orderBy('id', 'desc')->get();
        return Response()->json([
            'page' => view('backend.payroll.employee_document.history_document_edit', ['documents' => $documents])->render()

        ]);

        // return view('backend.payroll.employee_document.history_document_edit', compact('documents'));
    }

    public function historyDocumentUpdate(Request $request)
    {
        // dd($request->records);
            foreach($request->records as $key => $value){
                // dd(isset($request->records[$key]['head']));
                if (isset($request->records[$key]['head'])) {
                    if($request->records[$key]['file']) {
                        $name= $request->records[$key]['file']->getClientOriginalName();
                        $name = pathinfo($name, PATHINFO_FILENAME);
                        $ext= $request->records[$key]['file']->getClientOriginalExtension();
                        $employee_image= 'hredit'.$key.time().'.'.$ext;

                        $request->records[$key]['file']->storeAs( 'public/upload/employee_history', $employee_image);

                        EmployeeHistory::find($request->records[$key]['head'])->update([
                            'document' => $employee_image,
                            'extension' => $ext,

                        ]);
                    }
                }
            }
            $notification= array(
                'message'       => 'Update successfully!',
                'alert-type'    => 'success'
            );
            return redirect('employee-document')->with($notification);
    }

    //History Document update End

    public function employeeDocumentEdit($id)
    {
        // $employee = Employee::find($id);
        $documents = Employee::find($id);
        // dd($documents);
        return Response()->json([
            'page' => view('backend.payroll.employee_document.employee-document-edit-modal', ['documents' => $documents])->render()


        ]);

        return view('backend.payroll.employee_document.employee_document_edit', compact('documents'));
    }

    public function teacher_employeeDocumentEdit($id)
    {
        // $employee = Employee::find($id);
        $documents = Employee::find($id);
        // dd($documents);
        return Response()->json([
            'page' => view('backend.teacher_document.employee-document-edit-modal', ['documents' => $documents])->render()


        ]);

        return view('backend.payroll.employee_document.employee_document_edit', compact('documents'));
    }

    public function employeeDocumentUpdate(Request $request, $id)
    {
        // dd($request->all());
            if ($request->file('emirates_image')) {

                $name= $request->file('emirates_image')->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext= $request->file('emirates_image')->getClientOriginalExtension();
                $emirates_image= 'emirates_image'.time().'.'.$ext;

                $request->file('emirates_image')->storeAs( 'public/upload/employee', $emirates_image);
                Employee::find($id)->update([
                    'emirates_image' => $emirates_image,
                ]);
            }

            if ($request->file('passport_image')) {

                $name= $request->file('passport_image')->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext= $request->file('passport_image')->getClientOriginalExtension();
                $passport_image= 'passport_image'.time().'.'.$ext;

                $request->file('passport_image')->storeAs( 'public/upload/employee', $passport_image);
                Employee::find($id)->update([
                    'passport_image' => $passport_image,
                ]);
            }

            if ($request->file('quali_image')) {

                $name= $request->file('quali_image')->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext= $request->file('quali_image')->getClientOriginalExtension();
                $quali_image= 'quali_image'.time().'.'.$ext;

                $request->file('quali_image')->storeAs( 'public/upload/employee', $quali_image);
                Employee::find($id)->update([
                    'quali_image' => $quali_image,
                ]);
            }
            $notification= array(
                'message'       => 'Update successfully!',
                'alert-type'    => 'success'
            );
            return redirect('employee-document')->with($notification);
    }
    public function teacher_employeeDocumentUpdate(Request $request, $id)
    {
        // dd($request->all());
            if ($request->file('emirates_image')) {

                $name= $request->file('emirates_image')->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext= $request->file('emirates_image')->getClientOriginalExtension();
                $emirates_image= 'emirates_image'.time().'.'.$ext;

                $request->file('emirates_image')->storeAs( 'public/upload/employee', $emirates_image);
                Employee::find($id)->update([
                    'emirates_image' => $emirates_image,
                ]);
            }

            if ($request->file('passport_image')) {

                $name= $request->file('passport_image')->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext= $request->file('passport_image')->getClientOriginalExtension();
                $passport_image= 'passport_image'.time().'.'.$ext;

                $request->file('passport_image')->storeAs( 'public/upload/employee', $passport_image);
                Employee::find($id)->update([
                    'passport_image' => $passport_image,
                ]);
            }

            if ($request->file('quali_image')) {

                $name= $request->file('quali_image')->getClientOriginalName();
                $name = pathinfo($name, PATHINFO_FILENAME);
                $ext= $request->file('quali_image')->getClientOriginalExtension();
                $quali_image= 'quali_image'.time().'.'.$ext;

                $request->file('quali_image')->storeAs( 'public/upload/employee', $quali_image);
                Employee::find($id)->update([
                    'quali_image' => $quali_image,
                ]);
            }
            $notification= array(
                'message'       => 'Update successfully!',
                'alert-type'    => 'success'
            );
            return redirect()->back()->with($notification);
    }
    public function documentInfo(Request $request)
    {
        $emp_info = Employee::where('emp_id', 'like', '%' . $request->id . '%')->orWhere('name', 'like', '%' . $request->id . '%')->get();

        if($emp_info->count()>0)
        {
            if ($request->ajax()) {
                return Response()->json([
                    'page' => view('backend.payroll.employee_document.ajaxEmpList', ['empList' => $emp_info, 'i' => 1])->render(),

                ]);
            }
        }
    }

}

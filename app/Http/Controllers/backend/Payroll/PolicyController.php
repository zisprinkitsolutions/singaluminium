<?php

namespace App\Http\Controllers\backend\Payroll;
use App\Http\Controllers\Controller;
use App\Models\Payroll\Employee;
use App\Models\Payroll\Policy;
use App\Models\Payroll\PolicyHistory;
use App\Models\Payroll\PolicyType;
use Illuminate\Http\Request;

class PolicyController extends Controller
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
    public function index()
    {
          // Gate::authorize('salary_procedure');
          $policies = Policy::orderBy('id', 'desc')->get();
          $polices_type = PolicyType::get();

          return view('backend.payroll.policy.index', compact('policies','polices_type'));

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
        $validatedData = $request->validate([
            'effect_date' => 'required|unique:policies,effect_date',
        ]);

      //  return $request;


        $effective_date = $this->change_date_format($request->effect_date);
        $check =   Policy::where('effect_date',$effective_date)->first();

        if($check){
            $notification= array(
                'message'       => 'This effective date already have a policy ,Please Try other effective date!',
                'alert-type'    => 'warning'
            );
            return redirect()->back()->with($notification);
        }
        $m_ref_in_time = date('h:i:s A', strtotime($request->m_ref_in_time));
        $m_ref_out_time = date('h:i:s A', strtotime($request->m_ref_out_time));
        $e_ref_in_time = date('h:i:s A', strtotime($request->e_ref_in_time));
        $e_ref_out_time = date('h:i:s A', strtotime($request->e_ref_out_time));

        $policy = new Policy();
        $policy->effect_date = $effective_date;
        $policy->vacation_paid_or_unpaid = $request->vacation_paid_or_unpaid;
        $policy->air_ticket_eligibility = $request->air_ticket_eligibility;
        $policy->apply_over_time = $request->apply_over_time;
        $policy->cash_redeem = $request->cash_redeem;
        $policy->vacation_type = $request->vacation_type;
        $policy->minimum_day_for_ticket_price = $request->minimum_day_for_ticket_price;
        $policy->ticket_price_percentage = $request->ticket_price_percentage;
        $policy->late_type = $request->late_type;
        $policy->minimum_day_for_late = $request->minimum_day_for_late;
        $policy->minimum_hours_for_late = $request->minimum_hours_for_late;
        $policy->salary_loss = $request->salary_loss;
        $policy->minimun_vacation_priod = $request->minimun_vacation_priod;

        $policy->overtime_rate = $request->overtime_rate;
        $policy->min_hours_for_overtime = $request->min_hours_for_overtime;
        $policy->late_grace_time = $request->late_grace_time;
        $policy->maximum_time_for_attendace = $request->maximum_time_for_attendace;
        $policy->number_of_yearly_vacation = $request->number_of_yearly_vacation;
        $policy->m_ref_in_time = $m_ref_in_time;
        $policy->m_ref_out_time = $m_ref_out_time;
        $policy->e_ref_in_time = $e_ref_in_time;
        $policy->e_ref_out_time = $e_ref_out_time;
        $policy->description = $request->description;

        $policy->save();

        $notification= array(
            'message'       => 'Added successfully!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payroll\Policy  $policy
     * @return \Illuminate\Http\Response
     */
    public function show(Policy $policy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payroll\Policy  $policy
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $policy = Policy::find($id);
        $polices_type = PolicyType::get();
       // return $policy;
        return view('backend.payroll.policy.edit-modal', compact('policy','polices_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payroll\Policy  $policy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'effect_date' => 'required|unique:policies,effect_date,' . $id,
        ]);



        $policy =   Policy::find($id);
        $check = Employee::where('policy_id', $id)->first();
        if(!$check){
            $effective_date = $this->change_date_format($request->effect_date);
            $m_ref_in_time = date('h:i:s A', strtotime($request->m_ref_in_time));
            $m_ref_out_time = date('h:i:s A', strtotime($request->m_ref_out_time));
            $e_ref_in_time = date('h:i:s A', strtotime($request->e_ref_in_time));
            $e_ref_out_time = date('h:i:s A', strtotime($request->e_ref_out_time));
            $policy->vacation_paid_or_unpaid = $request->vacation_paid_or_unpaid;
            $policy->effect_date = $effective_date;
            $policy->air_ticket_eligibility = $request->air_ticket_eligibility;
            $policy->apply_over_time = $request->apply_over_time;
            $policy->cash_redeem = $request->cash_redeem;
            $policy->vacation_type = $request->vacation_type;
            $policy->minimum_day_for_ticket_price = $request->minimum_day_for_ticket_price;
            $policy->ticket_price_percentage = $request->ticket_price_percentage;
            $policy->late_type = $request->late_type;
            $policy->minimum_day_for_late = $request->minimum_day_for_late;
            $policy->minimum_hours_for_late = $request->minimum_hours_for_late;
            $policy->salary_loss = $request->salary_loss;
            $policy->minimun_vacation_priod = $request->minimun_vacation_priod;

            $policy->overtime_rate = $request->overtime_rate;
            $policy->min_hours_for_overtime = $request->min_hours_for_overtime;
            $policy->late_grace_time = $request->late_grace_time;
            $policy->maximum_time_for_attendace = $request->maximum_time_for_attendace;
            $policy->number_of_yearly_vacation = $request->number_of_yearly_vacation;
            $policy->m_ref_in_time = $m_ref_in_time;
            $policy->m_ref_out_time = $m_ref_out_time;
            $policy->e_ref_in_time = $e_ref_in_time;
            $policy->e_ref_out_time = $e_ref_out_time;
            $policy->description = $request->description;

            $policy->save();
            $notification= array(
                'message'       => 'Update successfully!',
                'alert-type'    => 'success'
            );
        }else{
            $notification= array(
                'message'       => 'This already use other record!',
                'alert-type'    => 'warning'
            );

        }
        return redirect()->back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payroll\Policy  $policy
     * @return \Illuminate\Http\Response
     */
    public function destroy(Policy $policy)
    {
        //
    }
}

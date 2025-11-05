<?php

namespace App\Http\Controllers\backend;

use App\CostCenterType;
use App\Http\Controllers\Controller;
use App\Models\CostCenter;
use App\ProjectDetail;
use Illuminate\Http\Request;

class CostCenterController extends Controller
{
    public function costCenterDetails(Request $request)
    {
        // Gate::authorize('app.account.index');

        $latest = CostCenter::withTrashed()->orderBy('id','DESC')->first();

        if ($latest) {
            $cc_code=preg_replace('/^CC-/', '', $latest->cc_code );
            ++$cc_code;
        } else {
            $cc_code = 1;
        }
        if($cc_code<10)
        {
            $cc="CC-000".$cc_code;
        }
        elseif($cc_code<100)
        {
            $cc="CC-00".$cc_code;
        }
        elseif($cc_code<1000)
        {
            $cc="CC-0".$cc_code;
        }
        else
        {
            $cc="CC-".$cc_code;
        }
        $costCenterDetails = CostCenter::where('activity', '!=', 'Draft')->orderBy('id','DESC');
        if($request->search_value){
            $costCenterDetails = $costCenterDetails->where('cc_code', 'like', "%{$request->search_value}%")
                ->orWhere('cc_name', 'like', "%{$request->search_value}%");
        }
        $costCenterDetails = $costCenterDetails->paginate(25);
        $projects=ProjectDetail::all();
        return view('backend.costCenter.costCenterDetails', compact('costCenterDetails','projects','cc'));
    }

    public function costCenterPost(Request $request)
    {
        // Gate::authorize('app.account.index');

        $request->validate([
            'cc_name' => 'required',
            'activity'  => 'required',
            'prsn_responsible' => 'required',
            'project_id' => 'required',
        ],
        [
            'cc_name.required' => 'Profit Center is required',
            'activity.required' => 'Activity is required',
            'prsn_responsible.required' => 'Person responsible is required',
            'project_id' => 'Select Project'
        ]
    );
        $latest = CostCenter::withTrashed()->orderBy('id','DESC')->first();
        if ($latest) {
            $cc_code=preg_replace('/^CC-/', '', $latest->cc_code );
            ++$cc_code;
        } else {
            $cc_code = 1;
        }
        if($cc_code<10)
        {
            $c_code="CC-000".$cc_code;
        }
        elseif($cc_code<100)
        {
            $c_code="CC-00".$cc_code;
        }
        elseif($cc_code<1000)
        {
            $c_code="CC-0".$cc_code;
        }
        else
        {
            $c_code="CC-".$cc_code;
        }
        $draftProfit = new CostCenter();
        $draftProfit->cc_code = $c_code;
        $draftProfit->cc_name = $request->cc_name;
        $draftProfit->activity = $request->activity;
        $draftProfit->prsn_responsible = $request->prsn_responsible;
        $draftProfit->project_id = $request->project_id;
        $sv=$draftProfit->save();
        $notification= array(
            'message'       => 'Added Successfully!',
            'alert-type'    => 'success'
        );

        return redirect()->route('costCenterDetails')->with($notification);
    }

    public function costCenEdit($costCenter)
    {
        // Gate::authorize('app.account.index');

        $costCenter=CostCenter::find($costCenter);
        if(!$costCenter)
        {
            return back()->with('error', "Not Found");
        }
        $costTypes=CostCenterType::get();
        $costCenterDetails = CostCenter::orderBy('id','DESC')->paginate(25);
        $projects=ProjectDetail::all();
        return view('backend.costCenter.costCenterDetails', compact('costCenter', 'costCenterDetails','costTypes','projects'));
    }

    public function costCentersUpdate(Request $request, $costCenter)
    {
        // Gate::authorize('app.account.index');

        $request->validate([
            'cc_name' => 'required',
            'activity'  => 'required',
            'prsn_responsible' => 'required',
            'project_id' => 'required',
        ],
        [
            'cc_name.required' => 'Profit Center is required',
            'activity.required' => 'Activity is required',
            'prsn_responsible.required' => 'Person responsible is required',
            'project_id' => 'Select Project'
        ]
    );
        $costCenter=CostCenter::find($costCenter);
        if(!$costCenter)
        {
            $notification= array(
                'message'       => 'Alread Exit!',
                'alert-type'    => 'warning'
            );
            return back()->with($notification);
        }
        $costCenter->cc_name = $request->cc_name;
        $costCenter->activity = $request->activity;
        $costCenter->prsn_responsible = $request->prsn_responsible;
        $costCenter->project_id = $request->project_id;
        $sv=$costCenter->save();

        $notification= array(
            'message'       => 'Updated Successfully!',
            'alert-type'    => 'success'
        );
        return back()->with($notification);
    }

}

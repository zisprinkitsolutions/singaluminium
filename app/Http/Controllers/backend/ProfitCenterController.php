<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\ProfitCenter;
use Illuminate\Http\Request;

class ProfitCenterController extends Controller
{
    public function profitCenterDetails(Request $request)
    {
        // Gate::authorize('app.account.index');
        $latest = ProfitCenter::withTrashed()->latest()->first();

        if ($latest) {
            $pc_code=preg_replace('/^PC-/', '', $latest->pc_code );
            ++$pc_code;
        } else {
            $pc_code = 1;
        }
        if($pc_code<10)
        {
            $p_code="PC-000".$pc_code;
        }
        elseif($pc_code<100)
        {
            $p_code="PC-00".$pc_code;
        }
        elseif($pc_code<1000)
        {
            $p_code="PC-0".$pc_code;
        }
        else
        {
            $p_code="PC-".$pc_code;
        }

        $profitDetails = ProfitCenter::where('activity', '!=', 'Draft')->latest();
        if($request->search_value){
            $profitDetails = $profitDetails->where('pc_code', 'like', "%{$request->search_value}%")
            ->orWhere('pc_name', 'like', "%{$request->search_value}%");
        }
        $profitDetails = $profitDetails->paginate(25);
        return view('backend.profitCenter.profitCenterDetails', compact('profitDetails','p_code'));
    }

    public function profitCenterPost(Request $request)
    {
        // Gate::authorize('app.account.index');

        $request->validate([
            'pc_name' => 'required',
            'activity'  => 'required',
            'prsn_responsible' => 'required',

        ],
        [
            'pc_name.required' => 'Profit Center is required',
            'activity.required' => 'Activity is required',
            'prsn_responsible.required' => 'Person responsible is required',
        ]

    );

            $latest = ProfitCenter::withTrashed()->latest()->first();

            if ($latest) {
                $pc_code=preg_replace('/^PC-/', '', $latest->pc_code );
                ++$pc_code;
            } else {
                $pc_code = 1;
            }
            if($pc_code<10)
            {
                $p_code="PC-000".$pc_code;
            }
            elseif($pc_code<100)
            {
                $p_code="PC-00".$pc_code;
            }
            elseif($pc_code<1000)
            {
                $p_code="PC-0".$pc_code;
            }
            else
            {
                $p_code="PC-".$pc_code;
            }


            $draftProfit = new ProfitCenter();
            $draftProfit->pc_code = $p_code;
            $draftProfit->pc_name = $request->pc_name;
            $draftProfit->activity = $request->activity;
            $draftProfit->prsn_responsible = $request->prsn_responsible;
            $sv=$draftProfit->save();
            $notification= array(
                'message'       => 'Added Successfully!',
                'alert-type'    => 'success'
            );
            return redirect()->route('profitCenterDetails')->with($notification);
    }
    public function profitCenEdit(Request $request, $profitCenter)
    {
        // Gate::authorize('app.account.index');

        $profitCenter=ProfitCenter::find($profitCenter);
        if(!$profitCenter)
        {
            return back()->with('error', "Not Found");

        }
        $profitDetails = ProfitCenter::where('activity', '!=', 'Draft')->latest();
        if($request->search_value){
            $profitDetails = $profitDetails->where('pc_code', 'like', "%{$request->search_value}%")
            ->orWhere('pc_name', 'like', "%{$request->search_value}%");
        }
        $profitDetails = $profitDetails->paginate(25);
        return view('backend.profitCenter.profitCenterDetails', compact('profitDetails','profitCenter'));
    }

    public function profitCentersUpdate(Request $request,$profitCenter)
    {
        // Gate::authorize('app.account.index');

        $request->validate([
            'pc_name' => 'required',
            'activity'  => 'required',
            'prsn_responsible' => 'required',

        ],
        [
            'pc_name.required' => 'Profit Center is required',
            'activity.required' => 'Activity is required',
            'prsn_responsible.required' => 'Person responsible is required',
        ]);

        $profitCenter=ProfitCenter::find($profitCenter);
            if(!$profitCenter)
            {
                $notification= array(
                    'message'       => 'Not Found!',
                    'alert-type'    => 'warning'
                );
                return redirect()->route('profitCenterDetails')->with($notification);
            }
            $profitCenter->pc_name = $request->pc_name;
            $profitCenter->activity = $request->activity;
            $profitCenter->prsn_responsible = $request->prsn_responsible;
            $profitCenter->save();

            $notification= array(
                'message'       => 'Update Successfully!',
                'alert-type'    => 'success'
            );
            return redirect()->route('profitCenterDetails')->with($notification);

        }

        public function profitCenDelete($profitCenter)
        {
            // Gate::authorize('app.account.index');

            $profitCenter=ProfitCenter::find($profitCenter);
            if(!$profitCenter)
            {
                return back()->with('error', "Not Found");

            }
            $count=$profitCenter->projects($profitCenter->pc_code)->count();
            // dd($count);
            if($count>0)
            {
                $notification= array(
                    'message'       => 'It has Branch!',
                    'alert-type'    => 'warning'
                );
                return back()->with($notification);
            }
            $profitCenter->forceDelete();
            $notification= array(
                'message'       => 'Delete Success!',
                'alert-type'    => 'success'
            );
            return back()->with($notification);
        }


}

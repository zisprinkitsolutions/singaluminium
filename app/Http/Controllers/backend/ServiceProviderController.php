<?php

namespace App\Http\Controllers\backend;

use App\CostCenterType;
use App\Http\Controllers\Controller;
use App\PartyDocument;
use App\PartyInfo;
use Illuminate\Http\Request;
use Svg\Tag\Rect;

class ServiceProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $latest = PartyInfo::withTrashed()->orderBy('id','DESC')->first();

        if ($latest) {
            $pi_code=preg_replace('/^PI-/', '', $latest->pi_code );
            ++$pi_code;
        } else {
            $pi_code = 1;
        }
        if($pi_code<10)
        {
            $cc="PI-000".$pi_code;
        }
        elseif($pi_code<100)
        {
            $cc="PI-00".$pi_code;
        }
        elseif($pi_code<1000)
        {
            $cc="PI-0".$pi_code;
        }
        else
        {
            $cc="PI-".$pi_code;
        }
        $costTypes=CostCenterType::get();
        $partyInfos = PartyInfo::where('pi_type','!=', "Draft")->where('pi_type', 'Supplier')->orderBy('id','DESC');
        if($request->search_value){
            $partyInfos = $partyInfos->where('pi_code', 'like', "%{$request->search_value}%")
            ->orWhere('pi_name', 'like', "%{$request->search_value}%")
            ->orWhere('trn_no', 'like', "%{$request->search_value}%");
        }
        $partyInfos = $partyInfos->paginate(25);
        return view('backend.service-provider.partyCenterDetails', compact('partyInfos','costTypes','cc'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $partyInfo=PartyInfo::find($id);
        $others = PartyDocument::where('party_id', $partyInfo->id)->get();
        if(!$partyInfo)
        {
            return back()->with('error', "Not Found");

        }
        $costTypes=CostCenterType::get();
        $costTypes=CostCenterType::get();
        $partyInfos = PartyInfo::where('pi_type','!=', "Draft")->where('pi_type', 'Supplier')->orderBy('id','DESC');
        if($request->search_value){
            $partyInfos = $partyInfos->where('pi_code', 'like', "%{$request->search_value}%")
            ->orWhere('pi_name', 'like', "%{$request->search_value}%")
            ->orWhere('trn_no', 'like', "%{$request->search_value}%");
        }
        $partyInfos = $partyInfos->paginate(25);
        return view('backend.service-provider.partyCenterDetailsEdit', compact('partyInfos', 'partyInfo','costTypes', 'others'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function service_provider_list_print(Request $request){
        $partyInfos = PartyInfo::where('pi_type', 'Supplier')->get();
        return view('backend.service-provider.party-center-print-list', compact('partyInfos'));
    }
    public function service_provider_preview(Request $request){
        $pInfo=PartyInfo::find($request->id);
        $others=PartyDocument::where('party_id',$request->id)->get();
        if(!$pInfo)
        {
            return back()->with('error', "Not Found");
        }
        return view('backend.service-provider.party-center-view', compact('pInfo','others'));
    }
    public function service_provider_print(Request $request){
        $pInfo=PartyInfo::find($request->id);
        return view('backend.service-provider.party-center-print', compact('pInfo'));
    }

    public function othersDelete(Request $request)
    {
        $employeeSalary = PartyDocument::find($request->id);
        $party_id = $employeeSalary->party_id;
        $employeeSalary->delete();
        $others = PartyDocument::where('party_id', $party_id)->get();
        $notification = array(
            'message'       => 'Employee Salary Deleted successfully!',
            'alert-type'    => 'success'
        );
        return Response()->json([
            'page' => view('backend.service-provider.ajaxImage', ['others' => $others, 'i' => 1])->render(),

        ]);

    }

    function downloadFile($file_name){
        $path = "storage/upload/student-parent/".$file_name;
        return response()->download($path);

    }
}

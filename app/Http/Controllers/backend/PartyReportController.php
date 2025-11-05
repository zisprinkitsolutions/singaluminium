<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\JournalRecord;
use App\PartyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PartyReportController extends Controller
{
    private function dateFormat($date)
    {
        $old_date = explode('/', $date);

        $new_data = $old_date[0].'-'.$old_date[1].'-'.$old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }
    public function index(Request $request)
    {
        Gate::authorize('Party_Transactions');
        $date=null;
        $date2=null;
        $parties=PartyInfo::get();
        $party=null;
        $records=null;
        $opening=0;
        if($request->party_name)
        {
            $records=JournalRecord::whereIn('account_type_id',[1,2])->whereNotIn('account_head_id',[19])->where('party_info_id',$request->party_name)->select('journal_id')->distinct()->get();
            $party=PartyInfo::find($request->party_name);
            // dd($records);
        }

        if($request->date!==null && $request->date2!=null )
        {
            $date=$this->dateFormat($request->date);

            $date2=$this->dateFormat($request->date2);
            $records=JournalRecord::whereIn('account_type_id',[1,2])->whereNotIn('account_head_id',[19])->where('party_info_id',$request->party_name)->whereBetween('journal_date',[$date,$date2])->select('journal_id')->distinct()->get();

        }
        elseif($request->date!=null)
        {
            $date=$this->dateFormat($request->date);

            $records=JournalRecord::whereIn('account_type_id',[1,2])->whereNotIn('account_head_id',[19])->where('party_info_id',$request->party_name)->where('journal_date',$date)->select('journal_id')->distinct()->get();
        }
        elseif($request->date2!=null)
        {
            $date=$this->dateFormat($request->date2);

            $records=JournalRecord::whereIn('account_type_id',[1,2])->whereNotIn('account_head_id',[19])->where('party_info_id',$request->party_name)->where('journal_date', $date)->select('journal_id')->distinct()->get();
        }

        return view('backend.party-report.index',compact('records','date','parties','party','date2'));
    }

}

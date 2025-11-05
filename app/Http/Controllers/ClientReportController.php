<?php

namespace App\Http\Controllers;

use App\PartyInfo;
use Illuminate\Http\Request;

class ClientReportController extends Controller
{
    private function dateFormat($date){
        $old_date = explode('/', $date);
        $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }

    public function setupReport(){
        return view('clientReport.setup.setup');
    }

    public function purchaseReport (){
        return view('clientReport.purchase.purchase');
    }

    public function salesReport()
    {
        return view('clientReport.sales.sale');
    }

    public function accountingReport()
    {
        return view('clientReport.accounting.accounting');
    }

    public function report()
    {
        return view('clientReport.report.report');
    }
    public function projectReport(Request $request){
        $start_date = $request->start_date?$this->dateFormat($request->start_date):null;
        $end_date = $request->end_date?$this->dateFormat($request->end_date):null;
        $party = PartyInfo::find($request->party_search);
        $parties = PartyInfo::whereHas('jobProjects')->orWhereHas('quotations')->paginate(25);
        $status = $request->status;
        return view('clientReport.project.report', compact('parties', 'party', 'start_date', 'status', 'end_date'));
    }

    public function receiptReport()
    {
        return view('clientReport.receipt.report');

    }
    public function hrReport(){
        return view('clientReport.hrPayroll.hr_payroll');
    }

    public function partyReport()
    {
        return view('clientReport.party.report');

    }
    public function administrationReport(){
        return view('clientReport.administration');
    }

    public function lpo_bill_report(){
        return view('clientReport.lpo-bill.lpo-bill');
    }
    public function business(){
        return view('clientReport.business.business');
    }
}

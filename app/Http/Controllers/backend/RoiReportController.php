<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\JobProject;
use App\PurchaseExpense;
use App\Receipt;
use App\BillDistribute;
use Illuminate\Http\Request;

class RoiReportController extends Controller
{
    public function reportDetails(Request $request){
        $project = JobProject::find($request->id);
        if($request->title == "payble"){
            $paybles = $project->purchase_expense()
                ->where(function ($query) {
                    $query->where('due_amount', '>', 0)
                        ->orWhereHas('tempPayment');
                })->get();

            return view('backend.roi-report.payble',compact('project','paybles'));
        }elseif($request->title == 'receivable'){
            $receivables = $project->invoicess()
            ->where(function ($query) {
                $query->where('due_amount', '>', 0)
                    ->orWhereHas('tempReceipt');
            })
            ->get();
            return view('backend.roi-report.receivable',compact('receivables','project'));

        }elseif($request->title == 'received'){

            $invoice_id = $project->invoicess->pluck('id')->all();
            $receiveds = Receipt::whereHas('items', function ($q) use ($invoice_id) {
                $q->whereIn('sale_id', $invoice_id);
            })->get();


            return view('backend.roi-report.received',compact('receiveds','project'));

        }elseif($request->title == 'expense'){
            $expenses = BillDistribute::where('project_id',$project->id)->get();
            return view('backend.roi-report.expense',compact('expenses','project'));
        }
    }

}

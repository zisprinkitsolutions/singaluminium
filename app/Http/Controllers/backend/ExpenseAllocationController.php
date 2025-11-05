<?php

namespace App\Http\Controllers\backend;
use App\Models\AccountHead;
use App\ExpenseAllocation;
use App\ExpenseAllocationItem;
use App\ProjectExpense;
use App\PurchaseExpenseItem;
use Carbon\Carbon;
use App\JournalRecord;
use App\Journal;
use App\Models\InvoiceNumber;
use App\Stock;
use App\PurchaseExpense;
use App\NewProject;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpenseAllocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function dateFormat($date)
    {
        $old_date = explode('/', $date);

        $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }
    private function journal_no()
    {
        $sub_invoice = Carbon::now()->format('Ymd');
        // return $sub_invoice;
        $latest_journal_no = Journal::withTrashed()->whereDate('created_at', Carbon::today())->where('journal_no', 'LIKE', "%{$sub_invoice}%")->orderBy('id','DESC')->first();
        // return $latest_journal_no;
        if ($latest_journal_no) {
            $journal_no = substr($latest_journal_no->journal_no, 0, -1);
            $journal_code = $journal_no + 1;
            $journal_no = $journal_code . "J";
        } else {
            $journal_no = Carbon::now()->format('Ymd') . '001' . "J";
        }

        return $journal_no;
    }
    public function index(Request $request)
    {
        if($request->date){
            $date = $this->dateFormat($request->date);
        }else{
            $date=date('Y-m-d');
        }
        $from = null;
        $to = null;
        $special_heads = AccountHead::where('master_account_id', 3)->get();
        return view('backend.expense-allocation.index', compact('date', 'from', 'to', 'special_heads'));
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
        $allocation = New ExpenseAllocation;
        $allocation->date = $this->dateFormat($request->date);
        $allocation->account_head_id = $request->accout_head_id;
        $allocation->account_sub_head_id = $request->sub_head_id;
        $allocation->total_amount = $request->task_total_amount;
        $allocation->purchase_expense_id = $request->purchase_expense_id;
        $allocation->save();
        if($request->project_id){
            $ids = $request->project_id;
            foreach($ids as $key => $id){
                $item = new ExpenseAllocationItem;
                $item->expense_allocation_id = $allocation->id;
                $item->project_id = $id;
                $item->amount = $request->task_amount[$key];
                $item->qty = $request->task_qty[$key];
                $item->save();
            }
        }
        return $allocation;
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
    public function edit(Request $request)
    {
        $allocation = ExpenseAllocation::find($request->id);
        $expense = PurchaseExpenseItem::find($allocation->purchase_expense_id);
        $project_lists = NewProject::all();
        return view('backend.expense-allocation.edit', compact('expense', 'project_lists', 'allocation'));
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
        // dd($request->all());
        $allocation = ExpenseAllocation::find($id);
        $allocation->date = $this->dateFormat($request->date);
        $allocation->total_amount = $request->task_total_amount;
        $allocation->save();
        $allocation->items->each->delete();
        if($request->project_id){
            $ids = $request->project_id;
            foreach($ids as $key => $id){
                $item = new ExpenseAllocationItem;
                $item->expense_allocation_id = $allocation->id;
                $item->project_id = $id;
                $item->amount = $request->task_amount[$key];
                $item->qty = $request->task_qty[$key];
                $item->save();
            }
        }
        $notification = array(
            'message'       => 'Update Success!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $allocation = ExpenseAllocation::find($id);
        if($allocation->status ==0){
            $allocation->items->each->delete();
            $allocation->delete();
            $notification = array(
                'message'       => 'Delete Success!',
                'alert-type'    => 'success'
            );
            return redirect()->back()->with($notification);
        }
        $notification = array(
            'message'       => 'Somthing Wroing!',
            'alert-type'    => 'warning'
        );
        return redirect()->back()->with($notification);
    }
    public function expense_allocation_list(Request $request){
        if($request->date){
            $date = $this->dateFormat($request->date);
        }else{
            $date=date('Y-m-d');
        }
        $from = null;
        $to = null;
        $lists = ExpenseAllocation::orderBy('id', 'desc')->get();
        return view('backend.expense-allocation.list', compact('date', 'from', 'to', 'lists'));
    }
    public function expense_allocation_details(Request $request){
        $allocation = ExpenseAllocation::find($request->id);
        return view('backend.expense-allocation.view', compact('allocation'));
    }
    public function expense_allocation_approve($id){

        $allocation = ExpenseAllocation::find($id);
        $purch_exItem = PurchaseExpenseItem::find($allocation->purchase_expense_id);
        $purch_ex = PurchaseExpense::find($purch_exItem->purchase_expense_id);
        // dd($purch_ex);
        $project_expense = ExpenseAllocationItem::where('expense_allocation_id', $allocation->id)->get();
        
        $journal_no = $this->journal_no();
        $journal = new Journal();
        $journal->project_id        = $purch_ex->project_id;
        $journal->purchase_expense_id = $purch_ex->id;
        $journal->transection_type  = 'Project Expense Entry';
        $journal->transaction_type  = 'Increase';
        $journal->journal_no        = $journal_no;
        $journal->date              = $allocation->date;
        $journal->pay_mode          = $purch_ex->pay_mode;
        $journal->cost_center_id    = 0;
        $journal->party_info_id     = $purch_ex->party_id;
        $journal->account_head_id   = 123;
        $journal->voucher_type      = 'CREDIT';
        $journal->amount            = $allocation->total_amount;
        $journal->tax_rate          = 0;
        $journal->vat_amount        = 0;
        $journal->total_amount      = $allocation->total_amount;
        $journal->gst_subtotal      = 0;
        $journal->narration         = $purch_ex->narration;
        $journal->approved_by       = $purch_ex->approved_by;
        $journal->authorized_by     = $purch_ex->authorized_by;
        $journal->created_by        = $purch_ex->created_by;
        $journal->save();
        

        foreach($project_expense as $project_e){
            $proj_exp = new ProjectExpense;
            $proj_exp->sub_head_id = $allocation->account_sub_head_id;
            $proj_exp->account_head_id = $allocation->account_head_id;
            $proj_exp->project_id = $project_e->project_id;
            $proj_exp->amount = $project_e->amount;
            $proj_exp->qty = $project_e->qty;
            $proj_exp->save();

            $item_stock = Stock::where('account_head_id', $proj_exp->account_head_id)->where('sub_account_head_id', $proj_exp->sub_head_id)->first();
            if(!$item_stock){
                $item_stock = Stock::where('account_head_id', $proj_exp->account_head_id)->first();
            }
            if($item_stock){
                $item_stock->amount_out += $proj_exp->amount;
                $item_stock->stock_out += $proj_exp->qty;
                $item_stock->save();
            }
            $exit_purchasse_item = PurchaseExpenseItem::where('purchase_expense_id', $purch_ex->id)->where('head_id', $proj_exp->account_head_id)->first();
            // dd($allocation->purchase_expense_id);
            $exit_purchasse_item->out_qty += $proj_exp->qty;
            $exit_purchasse_item->out_amount += $proj_exp->amount;
            $exit_purchasse_item->save();
            
            $project_expense_head = AccountHead::find($allocation->account_head_id);
            $jl_record = new JournalRecord();
            $jl_record->journal_id          = $journal->id;
            $jl_record->project_details_id  = $journal->project_id;
            $jl_record->cost_center_id      = $journal->cost_center_id;
            $jl_record->party_info_id       = $journal->party_info_id;
            $jl_record->journal_no          = $journal->journal_no;
            $jl_record->sub_account_head_id = $allocation->account_sub_head_id;
            $jl_record->account_head_id     = $project_expense_head->id;
            $jl_record->master_account_id   = $project_expense_head->master_account_id;
            $jl_record->account_head        = $project_expense_head->fld_ac_head;
            $jl_record->amount              = $project_e->amount;
            $jl_record->total_amount        = $project_e->amount;
            $jl_record->vat_rate_id         = 0;
            $jl_record->invoice_no          = 0;
            $jl_record->transaction_type    = 'CR';
            $jl_record->journal_date        = $journal->date;
            $jl_record->is_main_head        = 1;
            $jl_record->account_type_id     = $project_expense_head->account_type_id;
            $jl_record->project_id          = $project_e->project_id;
            $jl_record->save();
            
            $ac_head = AccountHead::find(34);
            $jl_record = new JournalRecord();
            $jl_record->journal_id          = $journal->id;
            $jl_record->project_details_id  = $journal->project_id;
            $jl_record->cost_center_id      = $journal->cost_center_id;
            $jl_record->party_info_id       = $journal->party_info_id;
            $jl_record->journal_no          = $journal->journal_no;
            $jl_record->account_head_id     = $ac_head->id;
            $jl_record->master_account_id   = $ac_head->master_account_id;
            $jl_record->account_head        = $ac_head->fld_ac_head;
            $jl_record->amount              = $project_e->amount;
            $jl_record->total_amount        = $project_e->amount;
            $jl_record->vat_rate_id         = 0;
            $jl_record->invoice_no          = 0;
            $jl_record->transaction_type    = 'DR';
            $jl_record->journal_date        = $journal->date;
            $jl_record->is_main_head        = 1;
            $jl_record->account_type_id     = $ac_head->account_type_id;
            $jl_record->project_id          = $project_e->project_id;
            $jl_record->save();
        }
        $allocation->status = 1;
        $allocation->save();
        $notification = array(
            'message'       => 'Approve Successfully!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);

    }
}

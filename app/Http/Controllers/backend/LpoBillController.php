<?php

namespace App\Http\Controllers\backend;

use App\AccountSubHead;
use App\Http\Controllers\Controller;
use App\JobProject;
use App\LpoBill;
use App\LpoBillDetail;
use App\Models\InvoiceNumber;
use App\PartyInfo;
use App\PurchaseExpenseItemTemp;
use App\PurchaseExpenseTemp;
use App\Requisition;
use App\TempCogsAssign;
use App\Unit;
use App\VatRate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Svg\Tag\Rect;
use Illuminate\Support\Facades\Storage;


class LpoBillController extends Controller
{
    private function dateFormat($date)
    {
        $old_date = explode('/', $date);

        $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];
        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }
    private function lpo_bill_no()
    {
        $sub_invoice = 'LPO' . Carbon::now()->format('y');
        // return $sub_invoice;
        $let_purch_exp = LpoBill::where('lpo_bill_no', 'LIKE', "%{$sub_invoice}%")->orderBy('id', 'DESC')->first();
        if ($let_purch_exp) {
            $purch_no = preg_replace('/^' . $sub_invoice . '/', '', $let_purch_exp->lpo_bill_no);
            $purch_code = $purch_no + 1;
            if ($purch_code < 10) {
                $purch_no = $sub_invoice . '000' . $purch_code;
            } elseif ($purch_code < 100) {
                $purch_no = $sub_invoice . '00' . $purch_code;
            } elseif ($purch_code < 1000) {
                $purch_no = $sub_invoice . '0' . $purch_code;
            } else {
                $purch_no = $sub_invoice . $purch_code;
            }
        } else {
            $purch_no = $sub_invoice . '0001';
        }
        return $purch_no;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize('LPO');
        $expenses = LpoBill::orderBy('lpo_bill_no', 'desc');
                        // ->paginate(25);
        $cal_total_amount = (clone $expenses)->get()->sum('total_amount');
        $expenses = $expenses->paginate(25);
        $data = [];
        $data['total_amount'] = $cal_total_amount;

        $i = 0;
        $parties = PartyInfo::where('pi_type', 'Supplier')->get();
        return view('backend.lpo-bill.index', compact('expenses', 'i', 'parties', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('Expense_Create');

        $pInfos = PartyInfo::where('pi_type', 'Supplier')->get();
        $parties = PartyInfo::where('pi_type', 'Supplier')->get();
        $vats = VatRate::all();
        $projects = JobProject::orderBy('id', 'desc')->get();
        $units = Unit::orderBy('name')->get();

        $heads = AccountSubHead::join('account_heads', 'account_heads.id', '=', 'account_sub_heads.account_head_id')
            ->join('master_accounts', 'master_accounts.id', '=', 'account_heads.master_account_id')
            ->whereIn('master_accounts.mst_definition', ['Cost of Sales / Goods Sold'])
            ->orWhere('mst_ac_head', 'INVENTORY')
            ->select('account_sub_heads.*')
            ->get();

        return view('backend.lpo-bill.create', compact('parties', 'pInfos', 'vats', 'projects', 'heads'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Gate::authorize('Expense_Create');
        // if (Auth::user()->is_authorizer != 1 && Auth::user()->is_approver != 1) {
        //     return redirect()->back()->with([
        //         'message'    => 'You do not have enough permission!',
        //         'alert-type' => 'error'
        //     ]);
        // }

        // dd($request->all());
        $request->validate(
            [
                'date'              =>  'required',
                'party_info'        => 'required',
            ],
            [
                'date.required'         => 'Date is required',
                'party_info.required'   => 'Party Info is required',
            ]
        );
        //Update date formate
        $update_date_format = $this->dateFormat($request->date);
        //purchase expense entry

        $voucher_file_name = '';
        $ext = '';

        if ($request->hasFile('voucher_file')) {
            $voucher_scan = $request->file('voucher_file');
            $name = $voucher_scan->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $voucher_scan->getClientOriginalExtension();
            $voucher_file_name = $name . time() . '.' . $ext;
            $voucher_scan->storeAs('public/upload/documents', $voucher_file_name);
        }
        $lpo_bill_no = $this->lpo_bill_no();
        $lpo_bill = new LpoBill();
        $lpo_bill->date = $update_date_format;
        $lpo_bill->lpo_bill_no = $lpo_bill_no;
        $lpo_bill->total_amount = $request->total_amount;
        $lpo_bill->vat = $request->total_vat;
        $lpo_bill->amount = $request->taxable_amount;
        $lpo_bill->party_id =  $request->party_info;
        $lpo_bill->narration = $request->narration;
        $lpo_bill->checked_by = $request->checked_by;
        $lpo_bill->prepared_by = $request->prepared_by;
        $lpo_bill->approved_by = $request->approved_by;
        $lpo_bill->attention = $request->attention;
        $lpo_bill->job_project_id = $request->project_id;
        $lpo_bill->delivary_date = $request->delivary_date ? $this->dateFormat($request->delivary_date) : null;
        $lpo_bill->contact_person = $request->contact_person;
        $lpo_bill->lpo_for = $request->for;
        $lpo_bill->gst_subtotal = 0;
        $lpo_bill->voucher_file = $voucher_file_name;
        $lpo_bill->extension = $ext;
        $lpo_bill->project_id = $request->project_id;
        $lpo_bill->attention = $request->attention;
        $lpo_bill->requisition_id = $request->requisition_id ? $request->requisition_id : null;

        $lpo_bill->created_by = auth()->user()->id;
        $lpo_bill->save();
        //end purchase expense entry

        //records entry
        $multi_head = $request->input('group-a');

        foreach ($multi_head as $each_head) {
            //purchase record
            $plo_bill_detail = new LpoBillDetail;
            $plo_bill_detail->item_description = $each_head['multi_acc_head'];
            $plo_bill_detail->amount = $each_head['amount'];
            $plo_bill_detail->task_id = isset($each_head['task_id'])?$each_head['task_id']:null;
            // $plo_bill_detail->sub_task_id = isset($each_head['sub_task_id'])?$each_head['sub_task_id']:null;
            $plo_bill_detail->vat = $each_head['vat_amount'];
            $plo_bill_detail->qty = $each_head['quantity'];
            $plo_bill_detail->rate = $each_head['rate'];
            $plo_bill_detail->unit_id = $each_head['unit'];
            $plo_bill_detail->total_amount = $each_head['sub_gross_amount'];
            $plo_bill_detail->party_id = $request->party_info;
            $plo_bill_detail->lpo_bill_id = $lpo_bill->id;
            $plo_bill_detail->gst_subtotal = 0;
            $plo_bill_detail->save();
            //end purchase record
        }

        $purchase_exp = $lpo_bill;

        $items = LpoBillDetail::where('lpo_bill_id', $lpo_bill->id)->get();

        $expenses = LpoBill::orderBy('status', 'desc');
        $cal_total_amount = (clone $expenses)->get()->sum('total_amount');
        $expenses = $expenses->paginate(25);
        $data = [];
        $data['total_amount'] = $cal_total_amount;
        if($lpo_bill->requisition_id){
            $notification = array(
                'message'       => 'LPO Created Successfully!',
                'alert-type'    => 'success'
            );
            return redirect()->route('lpo-bill-list')->with($notification);
        }
        return response()->json([
            'preview' =>  view('backend.lpo-bill.view', compact('purchase_exp', 'items'))->render(),
            'expense_list' => view('backend.lpo-bill.search-lpo-bill', compact('expenses'))->render(),
            'data'=>$data,
        ]);
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
    public function edit($id)
    {
        Gate::authorize('Expense_Edit');

        $lpo_bill = LpoBill::find($id);
        $items = LpoBillDetail::where('lpo_bill_id', $lpo_bill->id)->get();
        $pInfos = PartyInfo::where('pi_type', 'Supplier')->get();
        $vats = VatRate::all();
        $projects = JobProject::orderBy('id', 'desc')->get();
        $units = Unit::orderBy('name')->get();
        $heads = AccountSubHead::join('account_heads', 'account_heads.id', '=', 'account_sub_heads.account_head_id')
            ->join('master_accounts', 'master_accounts.id', '=', 'account_heads.master_account_id')
            ->whereIn('master_accounts.mst_definition', ['Cost of Sales / Goods Sold'])
            ->orWhere('mst_ac_head', 'INVENTORY')
            ->select('account_sub_heads.*')
            ->get();
        return view('backend.lpo-bill.edit', compact('lpo_bill', 'items', 'pInfos', 'vats', 'projects', 'units', 'heads'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Gate::authorize('Expense_Edit');

        // dd($request->all());
        // return $request->all();
        $request->validate(
            [
                'date'              =>  'required',
                'party_info'        => 'required',
            ],
            [
                'date.required'         => 'Date is required',
                'party_info.required'   => 'Party Info is required',
            ]
        );
        //Update date formate
        $update_date_format = $this->dateFormat($request->date);
        //purchase expense entry

        $lpo_bill_no = $this->lpo_bill_no();
        $lpo_bill =  LpoBill::find($request->lpo_bill_id);
        $lpo_bill->date = $update_date_format;
        $lpo_bill->total_amount = $request->total_amount;
        $lpo_bill->vat = $request->total_vat;
        $lpo_bill->amount = $request->taxable_amount;
        $lpo_bill->party_id =  $request->party_info;
        $lpo_bill->narration = $request->narration;
        $lpo_bill->checked_by = $request->checked_by;
        $lpo_bill->prepared_by = $request->prepared_by;
        $lpo_bill->approved_by = $request->approved_by;
        $lpo_bill->gst_subtotal = 0;
        $lpo_bill->job_project_id = $request->project_id;
        $lpo_bill->delivary_date = $request->delivary_date ? $this->dateFormat($request->delivary_date) : null;
        $lpo_bill->contact_person = $request->contact_person;
        $lpo_bill->lpo_for = $request->for;

        $voucher_file_name = $lpo_bill->voucher_file;
        $ext = $lpo_bill->extension;
        if ($request->hasFile('voucher_file')) {
            if (Storage::exists('public/upload/documents/' . $lpo_bill->voucher_file)) {
                Storage::delete('public/upload/documents/' . $lpo_bill->voucher_file);
            }
            $voucher_scan = $request->file('voucher_file');
            $name = $voucher_scan->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $ext = $voucher_scan->getClientOriginalExtension();
            $voucher_file_name = $name . time() . '.' . $ext;
            $voucher_scan->storeAs('public/upload/documents', $voucher_file_name);
        }
        $lpo_bill->voucher_file = $voucher_file_name;
        $lpo_bill->extension = $ext;

        $lpo_bill->project_id = $request->project_id;
        $lpo_bill->attention = $request->attention;
        $lpo_bill->edited_by = auth()->user()->id;
        $lpo_bill->save();
        //end purchase expense entry
        LpoBillDetail::where('lpo_bill_id', $lpo_bill->id)->delete();
        //records entry
        $multi_head = $request->input('group-a');
        foreach ($multi_head as $each_head) {
            //purchase record
            $plo_bill_detail = new LpoBillDetail;
            $plo_bill_detail->item_description = $each_head['multi_acc_head'];
            $plo_bill_detail->task_id = isset($each_head['task_id'])?$each_head['task_id']:null;
            $plo_bill_detail->sub_task_id = isset($each_head['sub_task_id'])?$each_head['sub_task_id']:null;
            $plo_bill_detail->amount = $each_head['amount'];
            $plo_bill_detail->qty = $each_head['quantity'];
            $plo_bill_detail->rate = $each_head['rate'];
            $plo_bill_detail->vat = $each_head['vat_amount'];
            $plo_bill_detail->total_amount = $each_head['sub_gross_amount'];
            $plo_bill_detail->party_id = $request->party_info;
            $plo_bill_detail->lpo_bill_id = $lpo_bill->id;
            $plo_bill_detail->gst_subtotal = 0;
            $plo_bill_detail->unit_id = $each_head['unit'];
            $plo_bill_detail->save();
            //end purchase record
        }
        $notification = array(
            'message'       => 'Update Successfully!',
            'alert-type'    => 'success'
        );
        return redirect()->route('lpo-bill-list')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Gate::authorize('Expense_Delete');
        $purch = LpoBill::find($id);
        LpoBillDetail::where('lpo_bill_id', $purch->id)->delete();
        $purch->delete();
        $notification = array(
            'message'       => 'Deleted Successfully!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function view(Request $request)
    {
        $purchase_exp = LpoBill::find($request->id);
        $items = LpoBillDetail::where('lpo_bill_id', $purchase_exp->id)->get();
        $new = 0;
        return view('backend.lpo-bill.view', compact('purchase_exp', 'new', 'items'));
    }

    public function search_lpo_bill(Request $request)
    {
        // dd($request);
        $expenses = LpoBill::orderBy('id', 'asc');
        if ($request->value) {
            $expenses = $expenses->where('lpo_bill_no', 'like', '%' . $request->value . '%');
        }
        if ($request->party != '') {
            $expenses = $expenses->where('party_id', $request->party);
        }
        if ($request->date != '') {
            $date = $this->dateFormat($request->date);
            $expenses = $expenses->where('date', $date);
        }
        $expenses = $expenses->get();
        return view('backend.lpo-bill.search-lpo-bill', compact('expenses'));
    }

    public function print($id)
    {
        $lpo = LpoBill::find($id);
        $items = LpoBillDetail::where('lpo_bill_id', $lpo->id)->get();

        return view('backend.lpo-bill.print', compact('lpo', 'items'));
    }

    public function lpo_to_purchase_expense($id)
    {
        Gate::authorize('Expense_Edit');

        $lpo_bill = LpoBill::find($id);
        $items = LpoBillDetail::where('lpo_bill_id', $lpo_bill->id)->get();
        $pInfos = PartyInfo::where('pi_type', 'Supplier')->get();
        $vats = VatRate::all();
        $projects = JobProject::orderBy('id', 'desc')->get();
        $units = Unit::orderBy('name')->get();
        $heads = AccountSubHead::join('account_heads', 'account_heads.id', '=', 'account_sub_heads.account_head_id')
            ->join('master_accounts', 'master_accounts.id', '=', 'account_heads.master_account_id')
            ->whereIn('master_accounts.mst_definition', ['Cost of Sales / Goods Sold'])
            ->orWhere('mst_ac_head', 'INVENTORY')
            ->select('account_sub_heads.*')
            ->get();
        return view('backend.lpo-bill.make-expense', compact('lpo_bill', 'items', 'pInfos', 'vats', 'projects', 'units', 'heads'));
    }


    private function temp_purchase_expense_no()
    {
        $sub_invoice = 'P' . Carbon::now()->format('y');
        // return $sub_invoice;
        $let_purch_exp = InvoiceNumber::where('purchase_no', 'LIKE', "%{$sub_invoice}%")->first();
        if ($let_purch_exp) {
            $purch_no = preg_replace('/^' . $sub_invoice . '/', '', $let_purch_exp->purchase_no);
            $purch_code = $purch_no + 1;
            if ($purch_code < 10) {
                $purch_no = $sub_invoice . '000' . $purch_code;
            } elseif ($purch_code < 100) {
                $purch_no = $sub_invoice . '00' . $purch_code;
            } elseif ($purch_code < 1000) {
                $purch_no = $sub_invoice . '0' . $purch_code;
            } else {
                $purch_no = $sub_invoice . $purch_code;
            }
        } else {
            $purch_no = $sub_invoice . '0001';
        }
        return $purch_no;
    }


    public function expense(Request $request)
    {
        $update_date_format = $this->dateFormat($request->date);
        //purchase expense entry
        $purch_no = $this->temp_purchase_expense_no();
        $purch_ex = new PurchaseExpenseTemp();
        $purch_ex->date = $update_date_format;
        // $purch_ex->job_project_id = $request->project_id;
        $purch_ex->pay_mode =  'Credit';
        $purch_ex->purchase_no = $purch_no;
        $purch_ex->invoice_no = $request->invoice_no;
        $purch_ex->lpo_bill_id = $request->lpo_bill_id;
        $purch_ex->requisition_id = $request->requisition_id;
        $purch_ex->project_id = $request->project_id;
        $purch_ex->task_id = $request->task_id;
        $purch_ex->sub_task_id = $request->sub_task_id;
        $purch_ex->invoice_type =  'Tax Invoice';
        $purch_ex->head_id = 0;
        $purch_ex->total_amount = $request->total_amount;
        $purch_ex->vat = $request->total_vat;
        $purch_ex->amount = $request->taxable_amount;
        $purch_ex->party_id =  $request->party_info;
        $purch_ex->narration = $request->narration ? $request->narration : 'N/A';
        $purch_ex->gst_subtotal = 0;
        $purch_ex->created_by = Auth::id();
        $purch_ex->paid_amount = 0;
        $purch_ex->due_amount = $purch_ex->total_amount;
        $purch_ex->authorized = true;
        $purch_ex->authorized_by = Auth::id();
        $purch_ex->save();

        $purchase_number = InvoiceNumber::first();
        $purchase_number->purchase_no = $purch_ex->purchase_no;
        $purchase_number->save();


        $multi_head = $request->input('group-a');
        foreach ($multi_head as $each_head) {
              if($each_head['head_id'] || $each_head['multi_acc_head']){
                $purc_exp_itm = new PurchaseExpenseItemTemp();
                $purc_exp_itm->sub_head_id = $each_head['head_id'];
                $purc_exp_itm->item_description = $each_head['multi_acc_head'];
                $purc_exp_itm->amount = $each_head['amount'];
                $purc_exp_itm->vat = $each_head['vat_amount'];
                $purc_exp_itm->total_amount = $each_head['sub_gross_amount'];
                $purc_exp_itm->qty = isset($each_head['quantity']) ? $each_head['quantity'] : 1;
                $purc_exp_itm->unit_id = isset($each_head['unit']) ? $each_head['unit'] : null;
                $purc_exp_itm->type = 'Tax Invoice';
                $purc_exp_itm->party_id = $request->party_info;
                $purc_exp_itm->purchase_expense_id = $purch_ex->id;
                $purc_exp_itm->gst_subtotal = 0;
                $purc_exp_itm->save();

                $proj_exp = new TempCogsAssign();
                $sub_acc = AccountSubHead::find($each_head['head_id']);
                $proj_exp->sub_head_id = $each_head['head_id'];
                $proj_exp->task_id = $each_head['task_id'];
                $proj_exp->task_item_id = $each_head['sub_task_id'];
                $proj_exp->account_head_id = $sub_acc->account_head_id;
                $proj_exp->purchase_expense_id = $purch_ex->id;
                $proj_exp->project_id =  $purch_ex->project_id;
                $proj_exp->task_id = $purch_ex->task_id;
                $proj_exp->task_item_id = $purch_ex->sub_task_id;
                $proj_exp->amount = $purc_exp_itm->amount;
                $proj_exp->qty = $purc_exp_itm->qty;
                $proj_exp->save();
            }
        }
        return redirect()
        ->route('purchase-expense')
        ->with([
            'message'    => 'Purchase Expense Created Successfully',
            'alert-type' => 'success',
        ]);

    }

      public function lpo_approve ($id)
    {
        $purchase_exp = LpoBill::find($id);
        $purchase_exp->status = 'Approved';
        $purchase_exp->approved_by = Auth::id();
        $purchase_exp->save();
        $requisition = Requisition::where('id', $purchase_exp->requisition_id)->first();
        if ($requisition) {
            $requisition->status = 'LPO Created';
            $requisition->save();
        }
        $items = LpoBillDetail::where('lpo_bill_id', $purchase_exp->id)->get();
        $new = 0;

        $expenses = LpoBill::orderBy('status', 'desc');
        $cal_total_amount = (clone $expenses)->get()->sum('total_amount');
        $expenses = $expenses->paginate(25);
        $data = [];
        $data['total_amount'] = $cal_total_amount;

        return response()->json([
            'preview' =>  view('backend.lpo-bill.view', compact('purchase_exp', 'new', 'items'))->render(),
            'expense_list' => view('backend.lpo-bill.search-lpo-bill', compact('expenses'))->render(),
            'data'=>$data,
        ]);
    }
}

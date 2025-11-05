<?php

namespace App\Http\Controllers\backend;

use App\AccountSubHead;
use App\Http\Controllers\Controller;
use App\JobProject;
use App\Notifications\RequisitionCreated;
use App\PartyInfo;
use App\Requisition;
use App\RequisitionItem;
use App\User;
use App\VatRate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class RequisitionControler extends Controller
{
    private function dateFormat($date)
    {
        $old_date = explode('/', $date);
        $new_data = $old_date[0] . '-' . $old_date[1] . '-' . $old_date[2];

        $new_date = date('Y-m-d', strtotime($new_data));
        $new_date = \DateTime::createFromFormat("Y-m-d", $new_date);
        return $new_date->format('Y-m-d');
    }
    private function requisition_no()
    {
        $sub_invoice = 'REQ' . Carbon::now()->format('y');
        // return $sub_invoice;
        $requisition = Requisition::where('requisition_no', 'LIKE', "%{$sub_invoice}%")->orderBy('id', 'DESC')->first();
        if ($requisition) {
            $requisition_no = preg_replace('/^' . $sub_invoice . '/', '', $requisition->requisition_no);
            $purch_code = $requisition_no + 1;
            if ($purch_code < 10) {
                $requisition_no = $sub_invoice . '000' . $purch_code;
            } elseif ($purch_code < 100) {
                $requisition_no = $sub_invoice . '00' . $purch_code;
            } elseif ($purch_code < 1000) {
                $requisition_no = $sub_invoice . '0' . $purch_code;
            } else {
                $requisition_no = $sub_invoice . $purch_code;
            }
        } else {
            $requisition_no = $sub_invoice . '0001';
        }
        return $requisition_no;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize('Requisition');
        $user = auth()->user();

        $expenses = Requisition::query()
            ->when(
                $user->role->slug !== 'administration'
                && !$user->is_authorizer
                && !$user->is_approver,
                fn($query) => $query->where('created_by', $user->id)
            )
        ->latest('id')
        ->paginate(25);


        $i = 0;
       $pInfos = PartyInfo::where('pi_type', 'Supplier')->get();
        $parties = PartyInfo::where('pi_type', 'Supplier')->get();
        $projects = JobProject::get();
        $attentions = User::where('is_authorizer' , true)->orWhere('is_approver' , true)->get();
        $vats = VatRate::all();
        $heads = AccountSubHead::join('account_heads', 'account_heads.id', '=', 'account_sub_heads.account_head_id')
            ->join('master_accounts', 'master_accounts.id', '=', 'account_heads.master_account_id')
            ->whereIn('master_accounts.mst_definition', ['Cost of Sales / Goods Sold'])
            ->orWhere('mst_ac_head', 'INVENTORY')
            ->select('account_sub_heads.*')
            ->get();
        return view('backend.requisition.index', compact('expenses', 'i', 'parties', 'pInfos', 'projects', 'vats', 'heads','attentions'));
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
        $projects = JobProject::get();
        $vats = VatRate::all();
        $heads = AccountSubHead::join('account_heads', 'account_heads.id', '=', 'account_sub_heads.account_head_id')
            ->join('master_accounts', 'master_accounts.id', '=', 'account_heads.master_account_id')
            ->whereIn('master_accounts.mst_definition', ['Cost of Sales / Goods Sold'])
            ->orWhere('mst_ac_head', 'INVENTORY')
            ->select('account_sub_heads.*')
            ->get();
        $attentions = User::where('is_authorizer' , true)->orWhere('is_approver' , true)->get();

        return view('backend.requisition.create', compact('parties', 'pInfos', 'vats', 'projects', 'heads','attentions'));
    }

    public function mobileIndex(){
        Gate::authorize('Requisition');
        $user = auth()->user();

        $expenses = Requisition::query()
            ->when(
                $user->role->slug !== 'administration'
                && !$user->is_authorizer
                && !$user->is_approver,
                fn($query) => $query->where('created_by', $user->id)
            )
        ->latest('id')
        ->paginate(25);
        $i = 0;
        // $parties = PartyInfo::where('pi_type', 'Supplier')->get();

        $pInfos = PartyInfo::where('pi_type', 'Supplier')->get();
        $parties = PartyInfo::where('pi_type', 'Supplier')->get();
        $projects = JobProject::get();
        $attentions = User::where('is_authorizer' , true)->orWhere('is_approver' , true)->get();
        $vats = VatRate::all();
        $heads = AccountSubHead::join('account_heads', 'account_heads.id', '=', 'account_sub_heads.account_head_id')
            ->join('master_accounts', 'master_accounts.id', '=', 'account_heads.master_account_id')
            ->whereIn('master_accounts.mst_definition', ['Cost of Sales / Goods Sold'])
            ->orWhere('mst_ac_head', 'INVENTORY')
            ->select('account_sub_heads.*')
            ->get();

        return view('backend.requisition.mobile.index', compact('expenses', 'i', 'parties', 'pInfos', 'projects', 'vats', 'heads','attentions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //  return $request->all();
        Gate::authorize('Expense_Create');
        $request->validate(
            [
                'date'              =>  'required',
            ],
            [
                'date.required'         => 'Date is required',
            ]
        );

        $update_date_format = $this->dateFormat($request->date);
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
        $requisition_no = $this->requisition_no();
        $requisition = new Requisition();
        $requisition->date = $update_date_format;
        $requisition->requisition_no = $requisition_no;
        $requisition->total_amount = 0;
        $requisition->vat_amount = 0;
        $requisition->amount = 0;
        $requisition->party_id =  $request->party_info;
        $requisition->narration = $request->narration;
        $requisition->attention = $request->attention;

        $requisition->project_id = $request->project_id;
        $requisition->task_id = $request->task_id;
        $requisition->task_item_id = $request->sub_task_id;
        $requisition->created_by = auth()->user()->id;

        $requisition->lpo_date = $request->lpo_date ? $this->dateFormat($request->lpo_date) : null;

        // $requisition->voucher_file = $voucher_file_name;
        // $requisition->extension = $ext;
        $requisition->save();
        //end purchase expense entry
        //records entry
        $multi_head = $request->input('group-a');
        foreach ($multi_head as $each_head) {
            //purchase record
            $item = new RequisitionItem;
            $item->item_description = $each_head['multi_acc_head'];
            $item->amount = 0;
            $item->vat = 0;
            $item->qty = $each_head['quantity'];
            $item->rate = 0;
            $item->unit_id = $each_head['unit'];

            $item->total_amount = 0;
            $item->requisition_id = $requisition->id;
            $item->save();
        }

        $items = RequisitionItem::where('requisition_id', $requisition->id)->get();

        $users = User::whereHas('role.permissions', function ($q) {
            $q->whereIn('slug', ['Expense_Authorize', 'Expense_Approve']);
        })
        ->orWhereHas('addPermissions', function ($q) {
            $q->whereIn('slug', ['Expense_Authorize', 'Expense_Approve']);
        })
        ->get();


        Notification::send($users, new RequisitionCreated($requisition));

        $new = 0;
        return view('backend.requisition.view', compact('requisition', 'new', 'items'));
    }

    public function mobileStore(Request $request)
    {
        // Check permissions based on create/update
        if ($request->has('requisition_id') && $request->requisition_id) {
            Gate::authorize('Expense_Edit');
        } else {
            Gate::authorize('Expense_Create');
        }

        // Validation
        $request->validate(
            [
                'date' => 'required',
            ],
            [
                'date.required' => 'Date is required',
            ]
        );

        $update_date_format = $this->dateFormat($request->date);

        // Handle file upload (optional)
        $voucher_file_name = '';
        $ext = '';
        if ($request->hasFile('voucher_file')) {
            $voucher_scan = $request->file('voucher_file');
            if ($voucher_scan && $voucher_scan->isValid()) {
                $name = pathinfo($voucher_scan->getClientOriginalName(), PATHINFO_FILENAME);
                $ext = $voucher_scan->getClientOriginalExtension();
                $voucher_file_name = $name . time() . '.' . $ext;
                $voucher_scan->storeAs('public/upload/documents', $voucher_file_name);
            }
        }

        // ✅ If requisition_id exists → Update, otherwise → Create
        if ($request->requisition_id) {
            // -------- UPDATE --------
            $requisition = Requisition::findOrFail($request->requisition_id);

            $requisition->date = $update_date_format;
            $requisition->party_id = $request->party_info;
            $requisition->narration = $request->narration;
            $requisition->attention = $request->attention;
            $requisition->project_id = $request->project_id;
            $requisition->status = 'In Review'; // Reset status to 'Created' on update
            $requisition->edited_by = auth()->user()->id;
            $requisition->lpo_date = $request->lpo_date ? $this->dateFormat($request->lpo_date) : null;

            if ($voucher_file_name) {
                $requisition->voucher_file = $voucher_file_name;
                $requisition->extension = $ext;
            }

            $requisition->save();

            // Delete old items and re-insert (OR update individually if needed)
            RequisitionItem::where('requisition_id', $requisition->id)->delete();

        } else {
            // -------- CREATE --------
            $requisition_no = $this->requisition_no();
            $requisition = new Requisition();
            $requisition->date = $update_date_format;
            $requisition->requisition_no = $requisition_no;
            $requisition->total_amount = 0;
            $requisition->vat_amount = 0;
            $requisition->amount = 0;
            $requisition->party_id = $request->party_info;
            $requisition->narration = $request->narration;
            $requisition->attention = $request->attention;
            $requisition->project_id = $request->project_id;
             $requisition->status = 'In Review'; 
            $requisition->created_by = auth()->user()->id;
            $requisition->lpo_date = $request->lpo_date ? $this->dateFormat($request->lpo_date) : null;

            if ($voucher_file_name) {
                $requisition->voucher_file = $voucher_file_name;
                $requisition->extension = $ext;
            }

            $requisition->save();

            // Notify only on create
            $users = User::get();
            Notification::send($users, new RequisitionCreated($requisition));
        }

        // ✅ Insert Items
        $multi_head = $request->input('group-a');
        $count = isset($multi_head['task_id']) ? count($multi_head['task_id']) : 0;

        for ($i = 0; $i < $count; $i++) {
            if (
                empty($multi_head['multi_acc_head'][$i]) ||
                empty($multi_head['quantity'][$i]) ||
                empty($multi_head['unit'][$i])
            ) {
                continue;
            }

            $item = new RequisitionItem;
            $item->item_description         = $multi_head['multi_acc_head'][$i];
            $item->amount                   = 0;
            $item->job_project_task_id      = $multi_head['task_id'][$i] ?? null;
            $item->job_project_task_item_id = $multi_head['sub_task_id'][$i] ?? null;
            $item->vat                      = 0;
            $item->qty                      = $multi_head['quantity'][$i];
            $item->rate                     = 0;
            $item->unit_id                  = $multi_head['unit'][$i];
            $item->total_amount             = 0;
            $item->requisition_id           = $requisition->id;
            $item->save();
        }

        $items = RequisitionItem::where('requisition_id', $requisition->id)->get();
        $expenses = Requisition::where('created_by', auth()->user()->id)->orderBy('id', 'desc')->paginate(25);
        $i = 0;
        $parties = PartyInfo::where('pi_type', 'Supplier')->get();

        return response()->json([
            'success' => true,
            'message' => $request->requisition_id ? 'Requisition updated successfully!' : 'Requisition created successfully!',
            'data' => [
                'requisition' => $requisition,
                'items' => $items,
                'expenses' => $expenses,
                'i' => $i,
                'parties' => $parties,
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Requisition $requisition)
    {
        $notification = auth()->user()
            ->unreadNotifications()
            ->where('data->requisition_id', $requisition->id)
            ->first();

        if ($notification) {
            $notification->markAsRead();
        }
        return view('backend.requisition.view', compact('requisition'));
    }

    public function mobileShow($id)
    {
        $requisition = Requisition::with('items')->where('id', $id)->first();
        // dd($requisition);

        $notification = auth()->user()
            ->unreadNotifications()
            ->where('data->requisition_id', $requisition->id)
            ->first();

        if ($notification) {
            $notification->markAsRead();
        }
        return view('backend.requisition.mobile._ajax_requisition_details', compact('requisition'));
    }

    public function makeLpo(Requisition $requisition)
    {
        $pInfos = PartyInfo::where('pi_type', 'Supplier')->get();
        $parties = PartyInfo::where('pi_type', 'Supplier')->get();
        $vats = VatRate::all();
        $projects = JobProject::get();
        $heads = AccountSubHead::join('account_heads', 'account_heads.id', '=', 'account_sub_heads.account_head_id')
            ->join('master_accounts', 'master_accounts.id', '=', 'account_heads.master_account_id')
            ->whereIn('master_accounts.mst_definition', ['Cost of Sales / Goods Sold'])
            ->orWhere('mst_ac_head', 'INVENTORY')
            ->select('account_sub_heads.*')
            ->get();
        return view('backend.requisition.make_lpo', compact('requisition', 'pInfos', 'parties', 'vats', 'projects', 'heads'));
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
        $requisition = Requisition::find($id);
        $items = RequisitionItem::where('requisition_id', $requisition->id)->get();
        $pInfos = PartyInfo::where('pi_type', 'Supplier')->get();
        $vats = VatRate::all();
        $projects = JobProject::get();
        $heads = AccountSubHead::join('account_heads', 'account_heads.id', '=', 'account_sub_heads.account_head_id')
            ->join('master_accounts', 'master_accounts.id', '=', 'account_heads.master_account_id')
            ->whereIn('master_accounts.mst_definition', ['Cost of Sales / Goods Sold'])
            ->orWhere('mst_ac_head', 'INVENTORY')
            ->select('account_sub_heads.*')
            ->get();
        return view('backend.requisition.edit', compact('requisition', 'items', 'pInfos', 'vats', 'projects', 'heads'));
    }

    public function Mobileedit($id)
    {
        Gate::authorize('Expense_Edit');
        $requisition = Requisition::with(['items.unit','items.task','items.subTask','project'])->findOrFail($id);

        return response()->json([
                'requisition'=> $requisition ,

        ]);

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Requisition $requisition)
    {
        // dd('ok');
        Gate::authorize('Expense_Edit');
        // return $request->all();
        $request->validate(
            [
                'date'              =>  'required',
                // 'party_info'        => 'required',
            ],
            [
                'date.required'         => 'Date is required',
                // 'party_info.required'   => 'Party Info is required',
            ]
        );
        //Update date formate
        $update_date_format = $this->dateFormat($request->date);
        //purchase expense entry

        $requisition->date = $update_date_format;
        $requisition->total_amount = 0;
        $requisition->vat_amount = 0;
        $requisition->amount = 0;
        $requisition->party_id =  $request->party_info;
        $requisition->attention = $request->attention;

        $requisition->project_id = $request->project_id;
        $requisition->task_id = $request->task_id;
        $requisition->task_item_id = $request->sub_task_id;
        $requisition->edited_by = auth()->user()->id;
        $requisition->save();

        //end purchase expense entry
        RequisitionItem::where('requisition_id', $requisition->id)->delete();
        //records entry
        $multi_head = $request->input('group-a');
        foreach ($multi_head as $each_head) {
            //purchase record
            $item = new RequisitionItem;
            $item->item_description = $each_head['multi_acc_head'];
            $item->amount = 0;
            $item->vat = 0;
            $item->qty = $each_head['quantity'];
            $item->rate = 0;
            $item->unit_id = $each_head['unit'];

            $item->total_amount = 0;
            $item->requisition_id = $requisition->id;
            $item->save();
            //end purchase record
        }
        $notification = array(
            'message'       => 'Update Successfully!',
            'alert-type'    => 'success'
        );
        return redirect()->route('requisitions.create')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requisition $requisition)
    {
        Gate::authorize('Expense_Delete');

        RequisitionItem::where('requisition_id', $requisition->id)->delete();
        $requisition->delete();

        $notification = array(
            'message'       => 'Deleted Successfully!',
            'alert-type'    => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function view(Request $request)
    {
        $purchase_exp = Requisition::find($request->id);
        $items = Requisition::where('lpo_bill_id', $purchase_exp->id)->get();
        $new = 0;
        return view('backend.lpo-bill.view', compact('purchase_exp', 'new', 'items'));
    }

    public function search(Request $request)
    {
        $expenses = Requisition::orderBy('id', 'asc');
        if ($request->value) {
            $expenses = $expenses->where('requisition_no', 'like', '%' . $request->value . '%');
        }
        if ($request->party != '') {
            $expenses = $expenses->where('party_id', $request->party);
        }
        if ($request->date != '') {
            $date = $this->dateFormat($request->date);
            $expenses = $expenses->where('date', $date);
        }
        $expenses = $expenses->get();
        return view('backend.requisition.search_requisition', compact('expenses'));
    }

    public function print($id)
    {
        $lpo = Requisition::find($id);
        $items = RequisitionItem::where('requisition_id', $lpo->id)->get();
        return view('backend.requisition.print', compact('lpo', 'items'));
    }

    public function requisition_approve(Request $request , $id)
    {

        $req = Requisition::find($id);
        $req->status = 'Approved';
        $req->approved_by = auth()->user()->id;
        $req->save();


        $requisition = Requisition::find($id);


        $notification = [
        'message' => 'Requisition Approve successfully!',
        'alert-type' => 'success'
        ];

        if ($request->ajax()) {
            return response()->json([
                'preview' => view('backend.requisition.view', compact('requisition'))->render(),
            ]);
        }

       return redirect()->back()->with($notification);
    }

     public function requisition_rejected(Request $request)
    {
        $req = Requisition::find($request->hidden_id);
        $req->note = $request->note;
        $req->reject_reason = $request->note;
        $req->status = 'Rejected';
        $req->approved_by = auth()->user()->id;
        $req->save();
        $requisition = Requisition::find($request->hidden_id);

        $notification = [
        'message' => 'Requisition Approve successfully!',
        'alert-type' => 'success'
        ];

        if ($request->ajax()) {
            return response()->json([
                'preview' => view('backend.requisition.view', compact('requisition'))->render(),
            ]);
        }

       return redirect()->back()->with($notification);
    }
}

<?php

namespace App\Http\Controllers\backend;

use App\AccountSubHead;
use App\Http\Controllers\Controller;
use App\Models\AccHead;
use App\Models\AccountHead;
use App\Models\MasterAccount;
use App\Models\MstACType;
use App\Models\MstDefinition;
use App\MstCatType;
use App\VatType;
use App\Office;
use Illuminate\Http\Request;
use App\TempMasterAccount;
use App\TempAccountHead;
use App\TempSubAccountHead;
use App\SubAccountHead;
use Illuminate\Support\Facades\Gate;
use App\Exports\SubAccountExport;
use App\Exports\MasterAccountExport;
use App\Exports\AccountHeadExport;
use App\Imports\MasterAccountImport;
use App\Imports\AccountHeadImport;
use App\Imports\SubAccountHeadImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Sale;
use App\SaleItem;
use App\JobProjectInvoice;
use App\JobProjectInvoiceTask;
use App\TempReceiptVoucher;
use App\TempReceiptVoucherDetail;
use App\Receipt;
use App\ReceiptSale;
use App\PurchaseExpenseTemp;
use App\PurchaseExpenseItemTemp;
use App\PurchaseExpense;
use App\PurchaseExpenseItem;
use App\TempPaymentVoucher;
use App\TempPaymentVoucherDetail;
use App\Payment;
use App\PaymentInvoice;
use App\JournalTemp;
use App\JournalRecordsTemp;
use App\Journal;
use App\JournalRecord;
use App\Unit;
use PDF;
use Auth;
use DB;
use Session;

class MasterAccountController extends Controller
{
    public function chart_of_account()
    {
        Gate::authorize('Chart_of_Accounts');

        $offices = Office::whereNotIn('id', [Auth::user()->office_id])->get();

        $vat_types = VatType::get();
        $mstAccType = MstACType::get();
        $categories = MstCatType::get();
        $mst_definitions = MstDefinition::get();
        $masterDetails = MasterAccount::whereIn('office_id', [0, Auth::user()->office_id])->where('mst_ac_type', '!=', 'Draft')->orderBy('mst_ac_code', 'asc')->paginate(25);
        $masterDetailsPDF = MasterAccount::whereIn('office_id', [0, Auth::user()->office_id])->where('mst_ac_code', '!=', 'Draft')->latest()->get();
        $master_details = MasterAccount::whereIn('office_id', [0, Auth::user()->office_id])->get();
        return view('backend.masterAccount.chart-of-account', compact('vat_types', 'mstAccType', 'categories', 'mst_definitions', 'masterDetails', 'master_details', 'masterDetailsPDF', 'offices'));
    }

    public function new_account_head(Request $request)
    {
        Gate::authorize('Chart_of_Accounts');
        $offices = Office::whereNotIn('id', [Auth::user()->office_id])->get();
        $value = $request->account_head_search;
        if ($value) {
            $master_details = MasterAccount::whereIn('master_accounts.office_id', [0, Auth::user()->office_id])
                ->select('master_accounts.*')
                ->join('account_heads', 'account_heads.master_account_id', '=', 'master_accounts.id')
                ->where('account_heads.fld_ac_head', 'LIKE', '%' . $value . '%')
                ->distinct()
                ->paginate(25);
        } else {
            $master_details = MasterAccount::whereIn('office_id', [0, Auth::user()->office_id])->orderBy('mst_ac_code', 'asc')->paginate(25);
        }

        // $query = MasterAccount::whereIn('office_id', [0, Auth::user()->office_id]);
        // if ($value) {
        //     $query->whereHas('accountHeads', function($q) use ($value) {
        //         $q->where('fld_ac_head', 'LIKE', '%'.$value.'%');
        //     });
        // }
        // $master_details = $query->orderBy('mst_ac_code', 'asc')->get();
        // dd($master_details);
        return view('backend.masterAccount.new-accHeadDetails', compact('master_details', 'offices', 'value'));
    }

    public function new_account_sub_head(Request $request)
    {
        Gate::authorize('Chart_of_Accounts');
        $units = Unit::orderBy('name')->get();
        $offices = Office::whereNotIn('id', [Auth::user()->office_id])->get();
        $accountHeads = AccountHead::whereIn('office_id', [0, Auth::user()->office_id])->orderBy('fld_ac_code', 'asc')->get();
        $accountHeadhassubs = AccountHead::whereIn('office_id', [0, Auth::user()->office_id])->orderBy('fld_ac_code', 'asc')->paginate(25);
        return view('backend.masterAccount.new-ac-subhead', compact('accountHeads', 'accountHeadhassubs', 'offices'));
    }

    public function sub_head_post(Request $request)
    {
        $sub_head = new AccountSubHead();
        $sub_head->office_id = Auth::user()->office_id;
        $sub_head->account_head_id = $request->head_id;
        $sub_head->name = $request->name;
        $sub_head->save();

        $notification = [
            'message' => 'Sub Head Add Successfull !',
            'alert-type' => 'success'
        ];
        return back()->with($notification);
    }

    public function MasterDetailsPost(Request $request)
    {
        Gate::authorize('Setup_Create');
        // dd($request->all());
        $request->validate(
            [
                'mst_ac_head'               => 'required',
                'category'                  => 'required',
                'mst_definition'            => 'required',
                'mst_ac_type'               => 'required',
                'vat_type'                  =>  'required',
            ],
            [
                'category.required'         => 'Category is required',
                'mst_ac_head.required'      => 'Master Account Head is required',
                'mst_definition.required'   => 'Definition is required',
                'mst_ac_type.required'      => 'Account Type is required',
                'vat_type.required'         => 'Vat Type is required',
            ]
        );

        $typeCat = MstACType::where('id', $request->mst_ac_type)->first();
        $cat = MstCatType::where('id', $request->category)->orderBy('id', 'desc')->first();
        $latest_master = MasterAccount::withTrashed()->whereBetween('mst_ac_code', [$cat->value, $cat->value + 99])->whereIn('office_id', [0, Auth::user()->office_id])->orderBy('id', 'desc')->first();
        if ($latest_master) {
            if ($latest_master->mst_ac_code >= $cat->value + 99) {
                return back()->with('error', 'No Place to Store');
            }
        }

        $masterAcc = new MasterAccount;
        if ($latest_master) {
            $masterAcc->mst_ac_code = $latest_master->mst_ac_code + 1;
        } else {
            $masterAcc->mst_ac_code = $cat->value;
        }

        $reserved = ($request->category) == 5 ? 1 : 0;
        $masterAcc->office_id       = Auth::user()->office_id;
        $masterAcc->mst_ac_head     = $request->mst_ac_head;
        $masterAcc->account_type_id = $request->category;
        $masterAcc->mst_definition  = $request->mst_definition;
        $masterAcc->mst_ac_type     = $request->mst_ac_type;
        $masterAcc->vat_type        = $request->vat_type;
        $masterAcc->reserved        = $reserved;
        $masterAcc->category_id     = $request->category;
        $masterAS = $masterAcc->save();

        $notification = array(
            'message'       => 'Add Successfully!',
            'alert-type'    => 'success'
        );
        return back()->with($notification);
    }

    public function accountDetails($id){
        $masterAcc = MasterAccount::find($id);
        return view('backend.masterAccount.show', compact('masterAcc'));
    }

    public function findMastedCode(Request $request)
    {

        // $typeCat = MstACType::where('id', $request->value)->first();
        $cat = MstCatType::where('id', $request->value)->orderBy('id', 'desc')->first();
        $latest_master = MasterAccount::whereIn('office_id', [0, Auth::user()->office_id])->withTrashed()->whereBetween('mst_ac_code', [$cat->value, $cat->value + 99])->orderBy('mst_ac_code', 'desc')->first();
        // dd($latest_master->mst_ac_code);
        if ($latest_master) {
            if ($latest_master->mst_ac_code >= $cat->value + 99) {
                $msCode = 'No Place to Store';
            } else {
                $msCode = $latest_master->mst_ac_code + 1;
            }
        }else{
            $msCode = $cat->value;
        }
        return $msCode;
    }

    public function masterEdit($masterAcc)
    {
        Gate::authorize('Setup_Edit');


        $offices = Office::whereNotIn('id',[ Auth::user()->office_id])->get();

        $masterAcc = MasterAccount::find($masterAcc);

        if (!$masterAcc) {
            return back()->with('error', "Not Found");
        }
        $vat_types = VatType::get();
        $categories = MstCatType::get();
        $mstAccType = MstACType::get();
        $mst_definitions = MstDefinition::get();
        $masterDetails = MasterAccount::whereIn('office_id', [0, Auth::user()->office_id])->where('mst_ac_type', '!=', 'Draft')->orderBy('mst_ac_code', 'asc')->paginate(25);
        $master_details = MasterAccount::whereIn('office_id', [0, Auth::user()->office_id])->latest()->get();
        $masterDetailsPDF = MasterAccount::whereIn('office_id', [0, Auth::user()->office_id])->where('mst_ac_code', '!=', 'Draft')->latest()->get();
        return view('backend.masterAccount.chart-of-account', compact('vat_types', 'mstAccType', 'categories', 'mst_definitions', 'masterDetails', 'master_details', 'masterAcc', 'masterDetailsPDF', 'offices'));
        // return view('backend.masterAccount.masteAccDetails', compact('masterDetails','categories', 'masterAcc', 'mst_definitions', 'mstAccType', 'vat_types'));
    }

    public function masterDetailsUpdate($masterAcc, Request $request)
    {
        Gate::authorize('Setup_Edit');
        $request->validate(
            [
                'mst_ac_head' => 'required',
                'mst_definition'        => 'required',
                'vat_type'         =>  'required',
            ],
            [
                'mst_ac_head.required' => 'Master Account Head is required',
                'mst_definition.required' => 'Definition is required',
                'vat_type.required' => 'Vat Type is required',

            ]
        );
        $typeCat = MstACType::where('id', $request->mst_ac_type)->first();
        $cat = MstCatType::where('id', $request->category)->orderBy('id', 'desc')->first();
        $latest_master = MasterAccount::withTrashed()->whereBetween('mst_ac_code', [$cat->value, $cat->value + 99])->whereIn('office_id', [0, Auth::user()->office_id])->orderBy('id', 'desc')->first();
        if ($latest_master) {
            if ($latest_master->mst_ac_code >= $cat->value + 99) {
                return back()->with('error', 'No Place to Store');
            }
        }
        $masterAcc = MasterAccount::find($masterAcc);
        if (!$masterAcc) {
            return back()->with('error', "Not Found");
        }

        if ($latest_master) {
            $masterAcc->mst_ac_code = $latest_master->mst_ac_code + 1;
        } else {
            $masterAcc->mst_ac_code = $cat->value;
        }

        $reserved = ($request->category) == 5 ? 1 : 0;
        $masterAcc->office_id       = Auth::user()->office_id;
        $masterAcc->mst_ac_head     = $request->mst_ac_head;
        $masterAcc->account_type_id = $request->category;
        $masterAcc->mst_definition  = $request->mst_definition;
        $masterAcc->mst_ac_type     = $request->mst_ac_type;
        $masterAcc->vat_type        = $request->vat_type;
        $masterAcc->reserved        = $reserved;
        $masterAcc->category_id     = $request->category;
        $masterAS = $masterAcc->save();

        // $masterAcc->mst_ac_head = $request->mst_ac_head;
        // $masterAcc->mst_definition = $request->mst_definition;
        // $masterAcc->mst_ac_type = $request->mst_ac_type;
        // $masterAcc->vat_type = $request->vat_type;
        // $masterAcc->save();

        $notification = array(
            'message'       => 'Update Successfully!',
            'alert-type'    => 'success'
        );
        return redirect('setup/new-chart-of-account')->with($notification);
    }

    public function masterDelete($masterAcc)
    {
        Gate::authorize('Setup_Delete');

        $masterAcc = MasterAccount::find($masterAcc);
        if (!$masterAcc) {
            return back()->with('error', "Not Found");
        }
        $count = AccountHead::where('ma_code', $masterAcc->mst_ac_code)->count();
        if ($count > 0) {
            $notification = array(
                'message'       => 'This Related with Account Head!',
                'alert-type'    => 'warning'
            );
            return back()->with($notification);
        }

        $masterAcc->forceDelete();
        $notification = array(
            'message'       => 'Deleted Successfully!',
            'alert-type'    => 'success'
        );
        return redirect('setup/new-chart-of-account')->with($notification);
    }
    public function chart_of_account_pdf()
    {
        $masterDetails = MasterAccount::whereIn('office_id', [0, Auth::user()->office_id])->where('mst_ac_code', '!=', 'Draft')->latest()->get();
        $pdf = PDF::loadView('backend.masterAccount.chart-of-account-pdf', compact('masterDetails'));
        return $pdf->download('chart-of-account-list.pdf');
    }

    public function findMasterAcc(Request $request, MasterAccount $masterAcc)
    {
        Gate::authorize('Setup_Create');

        $last = AccountHead::whereIn('office_id', [0, Auth::user()->office_id])->where('master_account_id', $masterAcc->id)->orderBy('id', 'desc')->first();

        if ($last) {
            $subCode = $last->ac_code + 1;
        } else {
            $subCode = 100;
        }

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.findMasterAcc', ['masterAcc' => $masterAcc, 'subCode' => $subCode])->render()]);
        }
    }

    public function editAccHead(AccountHead $item, Request $request)
    {

        Gate::authorize('Setup_Edit');
        $account_head = AccountHead::where('id', $item->id)->latest()->first();

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.editAccHead', ['account_head' => $account_head,])->render()]);
        }
    }


    public function edit_acc_sub_head(Request $request, $id)
    {
        Gate::authorize('Setup_Edit');

        $accountHeads = AccountHead::whereIn('office_id', [0, Auth::user()->office_id])->orderBy('fld_ac_code', 'asc')->get();
        $account_head = AccountSubHead::find($id);
        $units = Unit::orderBy('name')->get();

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.editAccSubHead', [
                'account_head' => $account_head, 'accountHeads' => $accountHeads,
                'units' => $units,
            ])->render()]);
        }
    }

    public function sub_head_post_update(Request $request, $id)
    {
        Gate::authorize('Setup_Edit');
        $sub_head = AccountSubHead::find($id);
        $sub_head->account_head_id = $request->head_id;
        $sub_head->name = $request->name;
        $sub_head->unit_id = isset($request->unit_id) ? $request->unit_id : null;
        $sub_head->save();
        return $sub_head;
    }

    public function accHeahDetailsPost(Request $request, MasterAccount $masterAcc)
    {

        Gate::authorize('Setup_Create');

        // return $masterAcc;
        $request->validate(
            [
                'fld_ac_head'        => 'required',
            ],

            [
                'fld_ac_head.required' => 'A/C Head is required',
            ]
        );

        $accHeadL = AccountHead::whereIn('office_id', [0, Auth::user()->office_id])->where('master_account_id', $masterAcc->id)->orderBy('id', 'desc')->first();

        if ($accHeadL) {
            $new_code = $accHeadL->ac_code + 1;
        } else {
            $new_code = 100;
        }
        $new_start=$new_code;

        foreach ($request->fld_ac_head as $index => $acHeadName)
        {
            $accHead = new AccountHead();
            $accHead->ac_code = $new_code;
            $accHead->office_id = Auth::user()->office_id;
            $accHead->ma_code = $masterAcc->mst_ac_code;
            $accHead->fld_ac_head = $acHeadName;
            $accHead->is_unit = isset($request->is_unit[$index]) ? $request->is_unit[$index] : 0;
            $accHead->fld_ac_code = $masterAcc->mst_ac_code . "-" . $accHead->ac_code;
            $accHead->fld_ms_ac_head = $masterAcc->mst_ac_head;
            $accHead->fld_definition = $masterAcc->mst_definition;
            $accHead->account_type_id = $masterAcc->account_type_id;
            $accHead->master_account_id = $masterAcc->id;
            $accHead->save();
            $new_code += 1;
        }


        $accHead->save();
        // dd($accHead->master_account_id);
        $acchead = $accHead;
        $new_entries = AccountHead::where('master_account_id',$masterAcc->id)->where('ac_code','>=',$new_start)->get();

        if ($request->has('type') && $request->type == 'ajax') {
            return $acchead;
        } else {
            if ($request->ajax()) {
                return Response()->json(['page' => view('backend.masterAccount.new-head', ['new_entries' => $new_entries])->render(), 'm_id' => $accHead->master_account_id]);
            }
        }
    }

    public function accHeahEditPost(Request $request, $account_head)
    {
        Gate::authorize('Setup_Edit');

        $request->validate(
            [
                'fld_ac_head'        => 'required',
            ],

            [
                'fld_ac_head.required' => 'A/C Head is required',
            ]
        );

        $account_head = AccountHead::find($account_head);
        $account_head->fld_ac_head = $request->fld_ac_head;
        $account_head->is_unit = $request->is_unit ? 1 : 0;
        $account_head->save();

        return $account_head;
    }

    public function deleteAcHead($acHead)
    {
        Gate::authorize('Setup_Delete');
        $head = AccountHead::find($acHead);
        if (!$head) {
            return back()->with('error', 'Not Found');
        }
        $head->forceDelete();
        return back()->with('success', 'Deleted Successfully');
    }
    public function account_head_delete(Request $request)
    {
        Gate::authorize('Setup_Delete');

        $record = AccountHead::find($request->id);
        $check = DB::select("
            SELECT id AS id FROM purchase_expense_items WHERE head_id = $record->id
            UNION
            SELECT id AS id FROM journal_records WHERE account_head_id =  $record->id
            UNION
            SELECT id AS id FROM purchase_expense_item_temps WHERE head_id =  $record->id
            UNION
            SELECT id AS id FROM journal_records_temps WHERE account_head_id =  $record->id
            UNION
            SELECT id AS id FROM account_sub_heads WHERE account_head_id =  $record->id;
        ");
        if (count($check) > 0) {
            return false;
        } else {
            $record->forceDelete();
            return true;
        }
    }
    public function sub_account_head_delete(Request $request)
    {
        Gate::authorize('Setup_Delete');
        $record = AccountSubHead::find($request->id);
        $check = DB::select("
            SELECT id AS id FROM journal_records WHERE account_head_id =  $record->id;
        ");
        if (count($check) > 0) {
            return false;
        } else {
            $record->forceDelete();
            return true;
        }
    }
    public function master_account_export()
    {
        return Excel::download(new MasterAccountExport, 'master-account.xlsx');
    }
    public function account_head_export()
    {
        return Excel::download(new AccountHeadExport, 'account-head.xlsx');
    }
    public function sub_account_export()
    {
        return Excel::download(new SubAccountExport, 'sub-head.xlsx');
    }

    public function master_account_excel_import(Request $request)
    {
        $request->validate([
            'office_id'        => 'required',
        ]);
        // master account
        try {
            MasterAccount::where('office_id', Auth::user()->office_id)->forceDelete();
            AccountHead::where('office_id', Auth::user()->office_id)->forceDelete();
            AccountSubHead::where('office_id', Auth::user()->office_id)->forceDelete();

            $master_accounts = MasterAccount::where('office_id', $request->office_id)->get();
            // dd($master_accounts);
            foreach ($master_accounts as $master_account) {
                $masterAcc = new MasterAccount;
                $masterAcc->office_id       = Auth::user()->office_id;
                $masterAcc->mst_ac_code     = $master_account->mst_ac_code;
                $masterAcc->mst_ac_head     = $master_account->mst_ac_head;
                $masterAcc->account_type_id = $master_account->account_type_id;
                $masterAcc->mst_definition  = $master_account->mst_definition;
                $masterAcc->mst_ac_type     = $master_account->mst_ac_type;
                $masterAcc->vat_type        = $master_account->vat_type;
                $masterAcc->reserved        = $master_account->reserved;
                $masterAcc->category_id     = $master_account->category_id;
                $masterAcc->save();
                // account head
                // dd($master_account);
                $account_heads = AccountHead::where('office_id', $request->office_id)->where('master_account_id', $master_account->id)->get();
                foreach ($account_heads as $account) {
                    $accHead = new AccountHead();
                    $accHead->office_id = Auth::user()->office_id;
                    $accHead->ac_code = $account->ac_code;
                    $accHead->ma_code = $account->ma_code;
                    $accHead->fld_ac_head = $account->fld_ac_head;
                    $accHead->fld_ac_code = $account->fld_ac_code;
                    $accHead->fld_ms_ac_head = $account->fld_ms_ac_head;
                    $accHead->fld_definition = $account->fld_definition;
                    $accHead->account_type_id = $account->account_type_id;
                    $accHead->master_account_id = $masterAcc->id;
                    $accHead->save();
                    // sub head
                    $account_sub_heads = AccountSubHead::where('office_id', $request->office_id)->where('account_head_id', $account->id)->get();
                    foreach ($account_sub_heads as $sub_head) {
                        $accSubHead = new AccountSubHead();
                        $accSubHead->office_id = Auth::user()->office_id;
                        $accSubHead->account_head_id = $accHead->id;
                        $accSubHead->name = $sub_head->name;
                        $accSubHead->save();
                    }
                }
            }
            $master_accounts = MasterAccount::where('office_id', 0)->get();
            foreach ($master_accounts as $master_account) {
                $account_heads = AccountHead::where('office_id', $request->office_id)->where('master_account_id', $master_account->id)->get();
                foreach ($account_heads as $account) {
                    $accHead = new AccountHead();
                    $accHead->office_id = Auth::user()->office_id;
                    $accHead->ac_code = $account->ac_code;
                    $accHead->ma_code = $account->ma_code;
                    $accHead->fld_ac_head = $account->fld_ac_head;
                    $accHead->fld_ac_code = $account->fld_ac_code;
                    $accHead->fld_ms_ac_head = $account->fld_ms_ac_head;
                    $accHead->fld_definition = $account->fld_definition;
                    $accHead->account_type_id = $account->account_type_id;
                    $accHead->master_account_id = $master_account->id;
                    $accHead->save();
                    // sub head
                    $account_sub_heads = AccountSubHead::where('office_id', $request->office_id)->where('account_head_id', $account->id)->get();
                    foreach ($account_sub_heads as $sub_head) {
                        $accSubHead = new AccountSubHead();
                        $accSubHead->office_id = Auth::user()->office_id;
                        $accSubHead->account_head_id = $accHead->id;
                        $accSubHead->name = $sub_head->name;
                        $accSubHead->save();
                    }
                }
            }
            Sale::where('office_id', Auth::user()->office_id)->forceDelete();
            SaleItem::where('office_id', Auth::user()->office_id)->forceDelete();
            JobProjectInvoice::where('office_id', Auth::user()->office_id)->forceDelete();
            JobProjectInvoiceTask::where('office_id', Auth::user()->office_id)->forceDelete();
            TempReceiptVoucher::where('office_id', Auth::user()->office_id)->forceDelete();
            TempReceiptVoucherDetail::where('office_id', Auth::user()->office_id)->forceDelete();
            Receipt::where('office_id', Auth::user()->office_id)->forceDelete();
            ReceiptSale::where('office_id', Auth::user()->office_id)->forceDelete();
            PurchaseExpenseTemp::where('office_id', Auth::user()->office_id)->forceDelete();
            PurchaseExpenseItemTemp::where('office_id', Auth::user()->office_id)->forceDelete();
            PurchaseExpense::where('office_id', Auth::user()->office_id)->forceDelete();
            PurchaseExpenseItem::where('office_id', Auth::user()->office_id)->forceDelete();
            TempPaymentVoucher::where('office_id', Auth::user()->office_id)->forceDelete();
            TempPaymentVoucherDetail::where('office_id', Auth::user()->office_id)->forceDelete();
            Payment::where('office_id', Auth::user()->office_id)->forceDelete();
            PaymentInvoice::where('office_id', Auth::user()->office_id)->forceDelete();
            JournalTemp::where('office_id', Auth::user()->office_id)->forceDelete();
            JournalRecordsTemp::where('office_id', Auth::user()->office_id)->forceDelete();
            Journal::where('office_id', Auth::user()->office_id)->forceDelete();
            JournalRecord::where('office_id', Auth::user()->office_id)->forceDelete();
        } catch (\Exception $e) {
            // Convert $temp_entry to an array before logging
            Log::error('Error processing row: ', ['temp_entry' => $master_accounts->toArray()]);
            Log::error($e->getMessage());
            $notification = array(
                'message'       => 'Somthing wrong!',
                'alert-type'    => 'warning'
            );
            return back()->with($notification);
        }

        $notification = array(
            'message'       => 'Data Copy Successfully!',
            'alert-type'    => 'success'
        );
        return back()->with($notification);
        // $request->session()->put('token', $request->token);
        // $request->session()->put('office_id', $request->office_id);
        // Excel::import(new MasterAccountImport, $request->excel_file);
        // return redirect()->route('master-account-check-excel-import');
    }
    public function master_account_check_excel_import()
    {
        $vat_types = VatType::get();
        $mstAccType = MstACType::get();
        $categories = MstCatType::get();
        $mst_definitions = MstDefinition::get();
        $records = TempMasterAccount::where('user_id', Auth::user()->id)->where('token', $token = Session::get('token'))->get();
        return view('backend.masterAccount.master-account-check-excel-import', compact('records', 'vat_types', 'mstAccType', 'categories', 'mst_definitions'));
    }
    public function master_account_delete_excel(Request $request)
    {
        $record = TempMasterAccount::find($request->id);
        $record->delete();
        return true;
    }


    public function update_master_account(Request $request)
    {
        $temp_entry = TempMasterAccount::find($request->id);
        $field = $request->field_name;
        $value = $request->field_value;
        $temp_entry->$field = $value;
        $temp_entry->save();
        return true;
    }
    public function master_account_final_excel_import(Request $request)
    {
        $records = $request->temp_invoice_id;
        foreach ($records as $key => $record) {
            $temp_entry = TempMasterAccount::find($record);
            try {
                if ($temp_entry) {
                    $typeCat = MstACType::where('title', $temp_entry->ac_type)->first();
                    $cat = MstCatType::where('title', $temp_entry->account_type)->orderBy('id', 'desc')->first();
                    // dd($cat);
                    $latest_master = MasterAccount::withTrashed()->whereBetween('mst_ac_code', [$cat->value, $cat->value + 99])->whereIn('office_id', [0, Auth::user()->office_id])->orderBy('mst_ac_code', 'desc')->first();
                    // dd($latest_master->mst_ac_code);
                    if ($latest_master) {
                        if ($latest_master->mst_ac_code >= $cat->value + 99) {
                            return back()->with('error', 'No Place to Store');
                        }
                    }
                    $masterAcc = new MasterAccount;
                    if ($latest_master) {
                        $masterAcc->mst_ac_code = $latest_master->mst_ac_code + 1;
                    } else {
                        $masterAcc->mst_ac_code = $cat->value;
                    }
                    $reserved = ($cat->id) == 5 ? 1 : 0;
                    $masterAcc->office_id       = Auth::user()->office_id;
                    $masterAcc->mst_ac_head     = $temp_entry->fld_ac_head;
                    $masterAcc->account_type_id = $typeCat->id;
                    $masterAcc->mst_definition  = $temp_entry->definition;
                    $masterAcc->mst_ac_type     = $temp_entry->ac_type;
                    $masterAcc->vat_type        = $temp_entry->vat_type;
                    $masterAcc->reserved        = $reserved;
                    $masterAcc->category_id     = $typeCat->id;
                    $masterAcc->save();
                    $temp_entry->delete();
                }
            } catch (\Exception $e) {
                // Convert $temp_entry to an array before logging
                Log::error('Error processing row: ', ['temp_entry' => $temp_entry->toArray()]);
                Log::error($e->getMessage());
                return null; // Skip invalid row
            }
        }
        $notification = array(
            'message'       => 'Upload Successfully!',
            'alert-type'    => 'success'
        );
        return redirect('setup/new-chart-of-account')->with($notification);
    }



    public function account_head_excel_import(Request $request)
    {

        $request->validate([
            'office_id'        => 'required',
        ]);
        AccountHead::where('office_id', Auth::user()->office_id)->delete();
        $account_heads = AccountHead::where('office_id', $request->office_id)->get();
        // dd(Auth::user()->office_id);
        foreach ($account_heads as $account) {
            $accHead = new AccountHead();
            $accHead->office_id = Auth::user()->office_id;
            $accHead->ac_code = $account->ac_code;
            $accHead->ma_code = $account->ma_code;
            $accHead->fld_ac_head = $account->fld_ac_head;
            $accHead->fld_ac_code = $account->fld_ac_code;
            $accHead->fld_ms_ac_head = $account->fld_ms_ac_head;
            $accHead->fld_definition = $account->fld_definition;
            $accHead->account_type_id = $account->account_type_id;
            $accHead->master_account_id = $account->master_account_id;
            $accHead->save();
        }
        $notification = array(
            'message'       => 'Data Copy Successfully!',
            'alert-type'    => 'success'
        );
        return back()->with($notification);
        // $request->session()->put('token', $request->token);
        // $request->session()->put('office_id', $request->office_id);
        // Excel::import(new AccountHeadImport, $request->excel_file);
        // return redirect()->route('account-head-check-excel-import');
    }
    public function account_head_check_excel_import()
    {
        $master_account = MasterAccount::whereIn('office_id', [0, Auth::user()->office_id])->get();
        $records = TempAccountHead::where('user_id', Auth::user()->id)->where('token', $token = Session::get('token'))->get();
        return view('backend.masterAccount.account-head-check-excel-import', compact('records', 'master_account'));
    }
    public function account_head_delete_excel(Request $request)
    {
        $record = TempAccountHead::find($request->id);
        $record->delete();
        return true;
    }


    public function update_account_head(Request $request)
    {
        $temp_entry = TempAccountHead::find($request->id);
        $field = $request->field_name;
        $value = $request->field_value;
        $temp_entry->$field = $value;
        $temp_entry->save();
        return true;
    }
    public function account_head_final_excel_import(Request $request)
    {
        // dd($request->all());
        $records = $request->temp_invoice_id;
        foreach ($records as $key => $record) {
            $temp_entry = TempAccountHead::find($record);
            try {
                if ($temp_entry) {
                    $masterAcc = MasterAccount::where('mst_ac_head', $temp_entry->master_account)->first();
                    $accHeadL = AccountHead::where('ma_code', $masterAcc->mst_ac_code)->orderBy('id', 'desc')->first();
                    $accHead = new AccountHead();
                    if ($accHeadL) {
                        $accHead->ac_code = $accHeadL->ac_code + 1;
                    } else {
                        $accHead->ac_code = 100;
                    }
                    $accHead->office_id = Auth::user()->office_id;
                    $accHead->ma_code = $masterAcc->mst_ac_code;
                    $accHead->fld_ac_head = $temp_entry->account_head;
                    $accHead->fld_ac_code = $masterAcc->mst_ac_code . "-" . $accHead->ac_code;
                    $accHead->fld_ms_ac_head = $masterAcc->mst_ac_head;
                    $accHead->fld_definition = $masterAcc->mst_definition;
                    $accHead->account_type_id = $masterAcc->account_type_id;
                    $accHead->master_account_id = $masterAcc->id;
                    $accHead->save();

                    $temp_entry->delete();
                }
            } catch (\Exception $e) {
                Log::error('Error processing row: ', ['temp_entry' => $temp_entry->toArray()]);
                Log::error($e->getMessage());
                return null;
            }
        }
        $notification = array(
            'message'       => 'Account Head Upload Successfully!',
            'alert-type'    => 'success'
        );
        return redirect('setup/new-account-head')->with($notification);
    }


    public function sub_account_head_excel_import(Request $request)
    {
        $request->validate([
            'office_id'        => 'required',
        ]);
        AccountSubHead::where('office_id', Auth::user()->office_id)->delete();
        $account_sub_heads = AccountSubHead::where('office_id', $request->office_id)->get();
        foreach ($account_sub_heads as $account) {
            $accHead = new AccountSubHead();
            $accHead->office_id = Auth::user()->office_id;
            $accHead->account_head_id = $account->account_head_id;
            $accHead->name = $account->name;
            $accHead->save();
        }
        $notification = array(
            'message'       => 'Data Copy Successfully!',
            'alert-type'    => 'success'
        );
        return back()->with($notification);
        // $request->session()->put('token', $request->token);
        // $request->session()->put('office_id', $request->office_id);
        // Excel::import(new SubAccountHeadImport, $request->excel_file);
        // return redirect()->route('sub-account-head-check-excel-import');
    }
    public function sub_account_head_check_excel_import()
    {
        $account_head = AccountHead::whereIn('office_id', [0, Auth::user()->office_id])->get();
        $records = TempSubAccountHead::where('user_id', Auth::user()->id)->where('token', $token = Session::get('token'))->get();
        // dd(213);
        return view('backend.masterAccount.sub-account-head-check-excel-import', compact('records', 'account_head'));
    }
    public function sub_account_head_delete_excel(Request $request)
    {
        $record = TempSubAccountHead::find($request->id);
        $record->delete();
        return true;
    }

    public function update_sub_account_head(Request $request)
    {
        $temp_entry = TempSubAccountHead::find($request->id);
        $field = $request->field_name;
        $value = $request->field_value;
        $temp_entry->$field = $value;
        $temp_entry->save();
        return true;
    }
    public function sub_account_head_final_excel_import(Request $request)
    {
        // dd($request->all());
        $records = $request->temp_invoice_id;
        foreach ($records as $key => $record) {
            $temp_entry = TempSubAccountHead::find($record);
            // try {
            if ($temp_entry) {
                $accHeadL = AccountHead::where('fld_ac_head', $temp_entry->account_head)->first();
                $accHead = new AccountSubHead();
                $accHead->office_id = Auth::user()->office_id;
                $accHead->account_head_id = $accHeadL->id;
                $accHead->name = $temp_entry->sub_head;
                $accHead->save();

                $temp_entry->delete();
            }
            // } catch (\Exception $e) {
            //     Log::error('Error processing row: ', ['temp_entry' => $temp_entry->toArray()]);
            //     Log::error($e->getMessage());
            //     return null;
            // }
        }
        $notification = array(
            'message'       => 'Sub Head Upload Successfully!',
            'alert-type'    => 'success'
        );
        return redirect('setup/new-account-sub-head')->with($notification);
    }

    public function subhead_add(Request $request, $id)
    {
        $acc = AccountHead::find($id);
        $units = Unit::orderBy('name')->get();

        if ($request->ajax()) {
            return Response()->json(['page' => view('backend.ajax.addSubhead', ['acc' => $acc, 'units' => $units])->render()]);
        }
    }

    public function accountSubheadPost(Request $request, $id)
    {
        foreach ($request->name as $index => $subheadName) {
            if($subheadName)
            {
                $sub_head = new AccountSubHead();
                $sub_head->office_id = Auth::user()->office_id;
                $sub_head->account_head_id = $id;
                $sub_head->unit_id = isset($request->unit_id[$index]) ? $request->unit_id[$index] : null;
                $sub_head->name = $subheadName;
                $sub_head->save();
            }
        }

        $notification = [
            'message' => 'Sub Head Add Successfull !',
            'alert-type' => 'success'
        ];
        return back()->with($notification);
    }
}

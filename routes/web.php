<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\AppConfig;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('auth.login');
// });
Route::get('/storage-link', function () {
    Artisan::call('storage:link');
});

Route::get('check-print', function(){
    return view('check-print');
});


Route::get('/notifications', function () {
    $user = auth()->user();
    return response()->json([
        'notifications' => $user->unreadNotifications,
        'unreadCount'   => $user->unreadNotifications->count()
    ]);
});

Route::get('delete-demo', 'HomeController@delete_demo');

Route::get('/get-header', function () {
    return view('layouts.backend.partial.modal-header-info')->render();
});

Route::get('/get-footer', function () {
    return view('layouts.backend.partial.modal-footer-info')->render();
});

Route::get('purchase_number_correction', 'HomeController@p_number_correction');
Route::get('payment_number_correction', 'HomeController@payment_number_correction');
Route::get('party-name-correction', 'HomeController@party_correction');

Route::get('employee-party', 'HomeController@emp_party');
Route::get('opening-cash-asset', 'backend\OpeningBalanceController@opening_cash_asset')->name('opening-cash-asset');
Route::post('opening-cash-asset-store', 'backend\OpeningBalanceController@opening_cash_asset_store')->name('opening-cash-asset.store');
Route::get('opening-others', 'backend\OpeningBalanceController@opening_others')->name('opening-others');
Route::post('opening-others-store', 'backend\OpeningBalanceController@opening_others_store')->name('opening-others.store');

Route::get('/', 'Auth\LoginController@loginf')->name('loginf');
Auth::routes();
Route::get('/search/ajax/{id}', 'HomeController@SearchAjax')->name('admin.masterAccSearchAjax');
Route::get('user/dashboard/data', 'HomeController@user_dashboard_data')->name('user.dashboard.data');



// ************************************************** API function form zms systme start code  ***************************************************

Route::get('/get-expiry-date', function () {
    $configurations = AppConfig::whereIn('config_name', ['client_id', 'next_time_interval'])->get()->pluck('config_value', 'config_name');
    $client_id = $configurations['client_id'];
    $expiryCheck_interval = $configurations['next_time_interval'];
    return response()->json([
        'client_id' => $client_id,
        'expiryCheck_interval' => $expiryCheck_interval,
    ]);
});

Route::post('/update-time-interval-date-update', function (Request $request) {
    $expiryCheck = AppConfig::where('config_name', 'next_time_interval')->first();
    if ($expiryCheck) {
        $expiryCheck->config_value = $request->next_time_interval;
        $expiryCheck->save();
        return response()->json('Update successful');
    }
    return response()->json('Config not found', 404);
});

Route::post('/get-expiry-date-update', function (Request $request) {

    $configs = ['next_time_interval', 'end_point', 'company_name'];
    $updated = false;

    foreach ($configs as $config) {
        $appConfig = AppConfig::where('config_name', $config)->first();
        if ($appConfig && $request->has($config)) {
            $appConfig->config_value = $request->input($config);
            $appConfig->save();
            $updated = true;
        }
    }

    if($updated) {
        return response()->json('Update successful');
    }

    return response()->json('Config not found', 404);
});

Route::get('sub-head-create', 'HomeController@sub_head_create')->name('sub-head-create');
Route::get('/requirement-list', 'ApiControllModuleController@requirement')->name('requirement-list');
Route::get('/moduls-list', 'ApiControllModuleController@moduls_list')->name('moduls-list');
Route::resource('app-configs','AppConfigController');
Route::post("app-config-edit-modal", "AppConfigController@setting_edit_modal")->name("app-config-edit-modal");


//************************************************ API function form zms systme end code  ***************************************************

//

Route::group(['middleware' => ['auth', 'mobile.redirect']], function () {
    Route::resource('app-configs','AppConfigController');
    Route::post("app-config-edit-modal", "AppConfigController@setting_edit_modal")->name("app-config-edit-modal");
    // ************  api route to connect zms **************
    Route::get('/requirement-list', 'HomeController@requirement')->name('requirement-list');
    Route::get('/moduls-list', 'HomeController@moduls_list')->name('moduls-list');
    Route::post('head-leder-details-search', 'backend\AccountsReportController@headDetailsSearch')->name('journal.head.detail.search');

    // ************  api route to connect zms **************

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/projects/data', 'HomeController@getProjectsData')->name('projects.data');


    Route::post('/customer/Post/', 'backend\SaleOrderController@customerPost')->name('customerPost');
    Route::post('head-ledger-show', 'backend\AccountsReportController@head_ledger_show')->name('head-ledger-show');
    Route::post('master-ledger-show', 'backend\AccountsReportController@master_head_ledger')->name('master-head-ledger');
    Route::resource('account-info','CurrencyController');
    Route::get('/home/dashboard/ajax','HomeController@dashboardAjax')->name('dashboard-ajax');

    //setup start
    Route::prefix('/setup')->group(function(){
        // product section
        Route::resource('style', 'backend\StyleController');
        Route::resource('subsidiary', 'SubsidiaryController');

        Route::resource('brand', 'backend\BrandController');
        Route::resource('group', 'backend\GroupController');
        Route::resource('item-list', 'backend\ItemListController');
        Route::post('vat-type-value', 'backend\ItemListController@vat_type_value')->name('vat-type-value');
        Route::post('item-type-no', 'backend\ItemListController@item_type_no')->name('item-type-no');
        Route::post('group-id', 'backend\ItemListController@group_id')->name('group-id');
        Route::post('brand-country', 'backend\ItemListController@brand_country')->name('brand-country');
        Route::get('item-delete/{id}', 'backend\ItemListController@item_delete')->name('item-list.item-delete');
        Route::post('excel-file-import', 'backend\ItemListController@import')->name('excel-file-import');
        Route::post('partyInfo_import-excel-file-import', 'backend\PartyInfoController@partyInfo_import')->name('partyInfo_import-excel-file-import');
        Route::post('style-id', 'backend\StyleController@style_id')->name('style-id');
        Route::post("item-barcode", "backend\ItemListController@item_barcode")->name("item-barcode");
        Route::post("item-barcode-check", "backend\ItemListController@item_barcode_check")->name("item-barcode-check");
        Route::post("item-name-auto-select", "backend\ItemListController@item_name_auto_select")->name("item-name-auto-select");
        Route::get("items-download", "backend\ItemListController@items_download")->name("items-download");

        Route::get('/report', 'ClientReportController@setupReport')->name('setup.report');
        Route::get('/project-details', 'backend\ProjectController@projectDetails')->name('projectDetails');
        Route::get('new-chart-of-account', 'backend\MasterAccountController@chart_of_account')->name('new-chart-of-account');
        Route::get('new-chart-of-account/details/{id}','backend\MasterAccountController@accountDetails')->name('chart-acctount.details');
        Route::get('/cost-center-details', 'backend\CostCenterController@costCenterDetails')->name('costCenterDetails');
        Route::post('/project-details/Post', 'backend\ProjectController@projectDetailsPost')->name('projectDetailsPost');
        Route::get('/project-details/edit/{proj}', 'backend\ProjectController@projectEdit')->name('projectEdit');
        Route::get('/project-details/delete/{proj}', 'backend\ProjectController@projenew-projectctDelete')->name('projectDelete');
        Route::get('new-account-head', 'backend\MasterAccountController@new_account_head')->name('new-account-head');
        Route::get('new-account-sub-head', 'backend\MasterAccountController@new_account_sub_head')->name('new-account-sub-head');
        Route::post('sub-head-post', 'backend\MasterAccountController@sub_head_post')->name('sub-head-post');
        Route::post('sub-head-post-update/{id}', 'backend\MasterAccountController@sub_head_post_update')->name('sub-head-post-update');
        // *****
        Route::get('subhead-add/{id}','backend\MasterAccountController@subhead_add')->name('subhead-add');
        Route::post('subhead-post/{id}', 'backend\MasterAccountController@accountSubheadPost')->name('accountSubheadPost');
        // *****
        Route::get("chart-ofaccount-pdf", "backend\MasterAccountController@chart_of_account_pdf")->name("chart-ofaccount-pdf");
        Route::get('/master-details/edit/{masterAcc}', 'backend\MasterAccountController@masterEdit')->name('masterEdit');
        Route::get('/master-details/delete/{masterAcc}', 'backend\MasterAccountController@masterDelete')->name('masterDelete');
        Route::post('/master-details/Post', 'backend\MasterAccountController@MasterDetailsPost')->name('masterDetailsPost');
        Route::post('/master-details/findMastedCode', 'backend\MasterAccountController@findMastedCode')->name('findMastedCode');
        Route::get('/findMasterAcc/{masterAcc}', 'backend\MasterAccountController@findMasterAcc')->name('findMasterAcc');
        Route::get('/editAccHead/{item}', 'backend\MasterAccountController@editAccHead')->name('editAccHead');
        Route::get('/edit-acc-sub-head/{item}', 'backend\MasterAccountController@edit_acc_sub_head')->name('edit-acc-sub-head');
        Route::get('/acount-head/delete/{account_head}', 'backend\MasterAccountController@deleteAcHead')->name('deleteAcHead');
        Route::post('/project-details/Post/edit/{proj}', 'backend\ProjectController@projectDetailsUpdate')->name('projectDetailsUpdate');
        Route::post('/master-details/Post/edit/{masterAcc}', 'backend\MasterAccountController@masterDetailsUpdate')->name('masterDetailsUpdate');
        Route::get('/master-accounts-details', 'backend\MasterAccountController@masteAccDetails')->name('masteAccDetails');
        Route::post('/project-details/Post/{masterAcc}', 'backend\MasterAccountController@accHeahDetailsPost')->name('accHeahDetailsPost');
        Route::post('/project-details/edit/post/{account_head}', 'backend\MasterAccountController@accHeahEditPost')->name('accHeahEditPost');
        Route::get('/profit-details', 'backend\ProfitCenterController@ProfitCenterDetails')->name('profitCenterDetails');
        Route::get('/party-info', 'backend\PartyInfoController@partyInfoDetails')->name('partyInfoDetails');
        Route::resource('service-provider', 'backend\ServiceProviderController');
        Route::post('/costCenter/Post/', 'backend\CostCenterController@costCenterPost')->name('costCenterPost');
        Route::get('/profit-center/form', 'backend\ProfitCenterController@profitCenterForm')->name('profitCenterForm');
        Route::get('/cost-center/edit/{costCenter}', 'backend\CostCenterController@costCenEdit')->name('costCenEdit');
        Route::get('/cost-center/delete/{costCenter}', 'backend\CostCenterController@costCenDelete')->name('costCenDelete');
        Route::post('/cost-center/Post/edit/{costCenter}', 'backend\CostCenterController@costCentersUpdate')->name('costCentersUpdate');
        Route::get('/profit-center/edit/{profitCenter}', 'backend\ProfitCenterController@profitCenEdit')->name('profitCenEdit');
        Route::get('/profit-center/delete/{profitCenter}', 'backend\ProfitCenterController@profitCenDelete')->name('profitCenDelete');
        Route::post('/profit-center/Post/', 'backend\ProfitCenterController@profitCenterPost')->name('profitCenterPost');
        Route::post('/profit-center/Post/edit/{profitCenter}', 'backend\ProfitCenterController@profitCentersUpdate')->name('profitCentersUpdate');
        Route::get('/party-info/edit/{pInfo}', 'backend\PartyInfoController@partyInfoEdit')->name('partyInfoEdit');
        Route::get('/party-info/delete/{pinfo}', 'backend\PartyInfoController@partyInfoDelete')->name('partyInfoDelete');
        Route::post('/party-info/Post/', 'backend\PartyInfoController@partyInfoPost')->name('partyInfoPost');
        Route::post('party-ledger-modal', 'backend\AccountsReportController@party_report_modal')->name('party-ledger-modal');
        Route::post('/party-info/Post/edit/{profitCenter}', 'backend\PartyInfoController@partyInfoUpdate')->name('partyInfoUpdate');
        Route::get('/party-info/form/', 'backend\PartyInfoController@partyInfoForm')->name('partyInfoForm');
        Route::get('master-account-export', 'backend\MasterAccountController@master_account_export')->name('master-account-export');
        Route::get('sub-account-export', 'backend\MasterAccountController@sub_account_export')->name('sub-account-export');
        Route::get('account-head-export', 'backend\MasterAccountController@account_head_export')->name('account-head-export');

        Route::post('master-account-excel-import', 'backend\MasterAccountController@master_account_excel_import')->name('master-account-excel-import');
        Route::get('master-account-check-excel-import', 'backend\MasterAccountController@master_account_check_excel_import')->name('master-account-check-excel-import');
        Route::post('master-account-delete-excel', 'backend\MasterAccountController@master_account_delete_excel')->name('master-account-delete-excel');
        Route::post('update-master-account', 'backend\MasterAccountController@update_master_account')->name('update-master-account');
        Route::post('master-account-final-excel-import', 'backend\MasterAccountController@master_account_final_excel_import')->name('master-account-final-excel-import');

        Route::post('account-head-excel-import', 'backend\MasterAccountController@account_head_excel_import')->name('account-head-excel-import');
        Route::get('account-head-check-excel-import', 'backend\MasterAccountController@account_head_check_excel_import')->name('account-head-check-excel-import');
        Route::post('account-head-delete-excel', 'backend\MasterAccountController@account_head_delete_excel')->name('account-head-delete-excel');
        Route::post('update-account-head', 'backend\MasterAccountController@update_account_head')->name('update-account-head');
        Route::post('account-head-final-excel-import', 'backend\MasterAccountController@account_head_final_excel_import')->name('account-head-final-excel-import');

        Route::post('sub-account-head-excel-import', 'backend\MasterAccountController@sub_account_head_excel_import')->name('sub-account-head-excel-import');
        Route::get('sub-account-head-check-excel-import', 'backend\MasterAccountController@sub_account_head_check_excel_import')->name('sub-account-head-check-excel-import');
        Route::post('sub-account-head-delete-excel', 'backend\MasterAccountController@sub_account_head_delete_excel')->name('sub-account-head-delete-excel');
        Route::post('update-sub-account-head', 'backend\MasterAccountController@update_sub_account_head')->name('update-sub-account-head');
        Route::post('sub-account-head-final-excel-import', 'backend\MasterAccountController@sub_account_head_final_excel_import')->name('sub-account-head-final-excel-import');
        // asset
        Route::resource('office-asset', 'backend\OfficeAssetController');
        Route::post('category-store', 'backend\OfficeAssetController@category_store')->name('category-store');
        Route::post('item-store', 'backend\OfficeAssetController@item_store')->name('item-store');
        Route::get('office-asset-list', 'backend\OfficeAssetController@office_asset_list')->name('office-asset-list');
        Route::get('office-asset-approve', 'backend\OfficeAssetController@office_asset_approve')->name('office-asset-approve');
        Route::get('office-asset-submit/{id}', 'backend\OfficeAssetController@office_asset_submit')->name('office-asset-submit');
        Route::post('asset-view-modal', 'backend\OfficeAssetController@asset_view_modal')->name('asset-view-modal');
        Route::post('search-sub-head', 'backend\OfficeAssetController@search_sub_head')->name('search-sub-head');
        Route::get('depreciation-create', 'backend\OfficeAssetController@depreciation_create')->name('depreciation-create');
        Route::post('depreciation-create-store', 'backend\OfficeAssetController@depreciation_create_store')->name('depreciation-create-store');
        Route::post('search-asset-item', 'backend\OfficeAssetController@search_asset_item')->name('search-asset-item');
        Route::get('depreciation-list', 'backend\OfficeAssetController@depreciation_list')->name('depreciation-list');
        Route::get('depreciation-approve', 'backend\OfficeAssetController@depreciation_approve')->name('depreciation-approve');
        Route::post('depreciation-view-modal', 'backend\OfficeAssetController@depreciation_view_modal')->name('depreciation-view-modal');
        Route::get('depreciation-edit/{id}', 'backend\OfficeAssetController@depreciation_edit')->name('depreciation-edit');
        Route::get('depreciation-submit/{id}', 'backend\OfficeAssetController@depreciation_submit')->name('depreciation-submit');
        Route::post('depreciation-update/{id}', 'backend\OfficeAssetController@depreciation_update')->name('depreciation-update');
        Route::resource('document','backend\DocumentController');

        Route::resource('fund-allocation', 'backend\FundAllocationController');
        Route::get('fund-allocation-approval/{id}', 'backend\FundAllocationController@fund_allocation_approval')->name('fund-allocation-approval');
        Route::get('fund-allocation-approve', 'backend\FundAllocationController@fund_allocation_approve')->name('fund-allocation-approve');
        Route::get('allocation-print/{id}', 'backend\FundAllocationController@allocation_print')->name('allocation-print');
        Route::post('fund-allocation-modal', 'backend\FundAllocationController@fund_allocation_modal')->name('fund-allocation-modal');
        Route::post('approve-fund-collection-show', 'backend\FundCollectionController@approve_fund_collection_show')->name('approve-fund-collection-show');

        Route::resource('house/type','backend\HouseTypeController')->names('house.type');
        Route::resource('work/type','backend\WorkTypeController')->names('work.type');
        Route::resource('boq/unit','backend\BoqUnitController')->names('boq.unit');
    });

    Route::post('document-search', 'backend\DocumentController@search')->name('document-search');
    Route::post('sub-account-head-delete', 'backend\MasterAccountController@sub_account_head_delete')->name('sub-account-head-delete');
    Route::post('account-head-delete', 'backend\MasterAccountController@account_head_delete')->name('account-head-delete');
    Route::post('projectView', 'backend\ProjectController@projectView')->name('projectView');
    Route::get('project-list-print', 'backend\ProjectController@project_list_print')->name('project-list-print');
    Route::post("party-center-preview", "backend\PartyInfoController@party_center_preview")->name("party-center-preview");
    //setup end

    //purchase start
        Route::prefix('/purchase')->group(function(){
            Route::get('/report', 'ClientReportController@purchaseReport')->name('purchase.report');
            Route::get('purchase-expense-invoice', 'backend\purchaseExpenseController@purchase_expense_invoice')->name('purchase-expense-invoice');
            Route::get('purchase-expense', 'backend\purchaseExpenseController@purchase_expense')->name('purchase-expense');

            Route::post('purchase-expense-edit', 'backend\purchaseExpenseController@purchase_expense_edit')->name('purchase-expense-edit');
            Route::post('purchase-expense/edit/post/{id}', 'backend\purchaseExpenseController@expense_edit_post')->name('expense-edit-post');

            Route::get('purchase-expense-list', 'backend\purchaseExpenseController@purchase_expense_list')->name('purchase-expense-list');
            Route::get('payment-voucher2', 'backend\purchaseExpenseController@payment_voucher2')->name('payment-voucher2');
            Route::get('payment-voucher2-list', 'backend\purchaseExpenseController@payment_voucher2_list')->name('payment-voucher2-list');
            Route::post('expensepost/post', 'backend\purchaseExpenseController@expensepost')->name('expensepost');
            Route::post('check-account-head', 'backend\JournalEntryController@check_account_head')->name('check-account-head');
            Route::post('invoice_no_validation', 'backend\purchaseExpenseController@invoice_no_validation')->name('invoice_no_validation');
            Route::post('find-tax_rate', 'backend\JournalEntryController@findTaxRate')->name('findTaxRate');
            Route::post('find-project', 'backend\JournalEntryController@findProject')->name('findProject');
            Route::post('find-cost-center', 'backend\JournalEntryController@findCostCenter')->name('findCostCenter');
            Route::post('partyInfoInvoice2R', 'backend\purchaseExpenseController@partyInfoInvoice2')->name('partyInfoInvoice2R');
            Route::post('party/info/by/term2/', 'backend\JournalEntryController@partyInfoInvoice2')->name('partyInfoInvoice2');
            Route::post('party/info/by/term3/', 'backend\JournalEntryController@partyInfoInvoice3')->name('partyInfoInvoice3');
            Route::post('find-account-head', 'backend\JournalEntryController@findAccHead')->name('findAccHead');
            Route::post('find-account-head/id', 'backend\JournalEntryController@findAccHeadId')->name('findAccHeadId');
            Route::post('receipt-post', 'backend\purchaseExpenseController@receipt_post')->name('receipt-post');
            Route::post('findsaleRec', 'backend\purchaseExpenseController@findsaleRec')->name('findsaleRec');
            Route::post('search-purchase-expense', 'backend\purchaseExpenseController@search_purch')->name('search-purchase-expense');
            Route::get('purchase-authorize', 'backend\purchaseExpenseController@purchase_authorize')->name('purchase_authorize');
            Route::get('purchase-authorize/{id}', 'backend\purchaseExpenseController@purchase_authorization')->name('purchase-authorize');
            Route::get('purchase-approve', 'backend\purchaseExpenseController@purchase_approve')->name('purchase_approve');
            Route::get('purchase-approve/{id}', 'backend\purchaseExpenseController@purchase_approval')->name('purchase-approve');
            Route::get('payment-realised/{id}', 'backend\purchaseExpenseController@payment_realised')->name('payment-realised');
            Route::get('payment-declined/{id}', 'backend\purchaseExpenseController@payment_declined')->name('payment-declined');
            Route::post('payment-nex-deposit/{id}', 'backend\purchaseExpenseController@payment_deposit')->name('nex-deposit');
            Route::get('purchase-expense-delete/{id}', 'backend\purchaseExpenseController@purchase_delete')->name('purchase-expense.delete');
            //
            Route::post('payment-voucher-store', 'backend\TempPaymentVoucherController@store')->name('payment-voucher-store');
            Route::post('temp-payment-voucher-store', 'backend\TempPaymentVoucherController@temp_payment_voucher_store')->name('temp-payment-voucher-store');
            Route::get('payment-voucher-authorize/{id}', 'backend\TempPaymentVoucherController@payment_voucher_authorize')->name('payment-voucher-authorize');
            Route::get('payment-voucher-approve/{id}', 'backend\TempPaymentVoucherController@payment_voucher_approve')->name('payment-voucher-approve');
            Route::get('temp-payment-voucher-authorize', 'backend\TempPaymentVoucherController@temp_payment_voucher_authorize')->name('temp-payment-voucher-authorize');
            Route::get('temp-payment-voucher-approve', 'backend\TempPaymentVoucherController@temp_payment_voucher_approve')->name('temp-payment-voucher-approve');
            Route::post('temp-payment-voucher-edit', 'backend\TempPaymentVoucherController@temp_payment_voucher_edit')->name('temp-payment-voucher-edit');
            Route::post('temp-payment-voucher-update', 'backend\TempPaymentVoucherController@temp_payment_voucher_update')->name('temp-payment-voucher-update');
            Route::get('temp-payment-voucher-delete/{id}', 'backend\TempPaymentVoucherController@temp_payment_voucher_delete')->name('temp-payment-voucher-delete');
            Route::post('search-payment-voucher', 'backend\TempPaymentVoucherController@search_payment_voucher')->name('search-payment-voucher');
            Route::get('payment-voucher-delete/{id}', 'backend\TempPaymentVoucherController@payment_voucher_delete')->name('payment-voucher-delete');

            Route::post('search-supplier-due', 'backend\purchaseExpenseController@search_supplier_due')->name('search-supplier-due');
            Route::post('available-pay-amount', 'backend\purchaseExpenseController@available_pay_amount')->name('available-pay-amount');
            Route::post('available-balance-add', 'backend\purchaseExpenseController@available_balance_add')->name('available-balance-add');
            Route::post('project-expense', 'backend\purchaseExpenseController@project_expense')->name('project-expense');
            Route::post('project-expense-store', 'backend\purchaseExpenseController@project_expense_store')->name('project-expense-store');
            Route::post('check-project-expense', 'backend\purchaseExpenseController@check_project_expense')->name('check-project-expense');
            Route::post('check-project-expense-edit', 'backend\purchaseExpenseController@check_project_expense_edit')->name('check-project-expense-edit');

            Route::resource('expense-allocation', 'backend\ExpenseAllocationController');
            Route::get('expense-allocation-list', 'backend\ExpenseAllocationController@expense_allocation_list')->name('expense-allocation-list');
            Route::post('expense-allocation-details', 'backend\ExpenseAllocationController@expense_allocation_details')->name('expense-allocation-details');
            Route::get('expense-allocation-approve/{id}', 'backend\ExpenseAllocationController@expense_allocation_approve')->name('expense-allocation-approve');
            Route::get('expense-allocation-delete/{id}', 'backend\ExpenseAllocationController@destroy')->name('expense-allocation-delete');
            Route::post('expense-allocation-edit', 'backend\ExpenseAllocationController@edit')->name('expense-allocation-edit');
            Route::get('account-inventory', 'backend\purchaseExpenseController@account_inventory')->name('account-inventory');
            Route::post('pendding-inventory', 'backend\purchaseExpenseController@pendding_inventory')->name('pendding-inventory');
            Route::post('approval-inventory', 'backend\purchaseExpenseController@approval_inventory')->name('approval-inventory');
            Route::post('temp-inventory-show', 'backend\purchaseExpenseController@temp_inventory_show')->name('temp-inventory-show');
            Route::post('inventory-show', 'backend\purchaseExpenseController@inventory_show')->name('inventory-show');
            Route::post('inventory-edit', 'backend\purchaseExpenseController@inventory_expense_edit')->name('inventory-edit');
            Route::post('inventory-update', 'backend\purchaseExpenseController@inventory_expense_update')->name('inventory-update');
            Route::get('inventory-expense-delete/{id}', 'backend\purchaseExpenseController@inventory_expense_delete')->name('inventory-expense-delete');
            Route::get('inventory-expense-approve/{id}', 'backend\purchaseExpenseController@inventory_expense_approve')->name('inventory-expense-approve');
            Route::post('cogs-project-expense', 'backend\purchaseExpenseController@cogs_project_expense')->name('cogs-project-expense');
            Route::post('cogs-project-expense-edit', 'backend\purchaseExpenseController@cogs_project_expense_edit')->name('cogs-project-expense-edit');
            Route::post('cogs-project-expense-store', 'backend\purchaseExpenseController@cogs_project_expense_store')->name('cogs-project-expense-store');
            Route::post('cogs-project-expense-update', 'backend\purchaseExpenseController@cogs_project_expense_update')->name('cogs-project-expense-update');
            Route::post('temp-cogs-clear', 'backend\purchaseExpenseController@temp_cogs_clear')->name('temp-cogs-clear');
            Route::post('inventory-project-expense', 'backend\purchaseExpenseController@inventory_project_expense')->name('inventory-project-expense');
            Route::post('inventory-project-expense-store', 'backend\purchaseExpenseController@inventory_project_expense_store')->name('inventory-project-expense-store');
            Route::post('expense-create-model-content', 'backend\purchaseExpenseController@expense_create_model_content')->name('expense-create-model-content');
            Route::post('inventory-create-model-content', 'backend\purchaseExpenseController@inventory_create_model_content')->name('inventory-create-model-content');

            Route::get('/project/expense/report', 'backend\ProjectExpenseReportController@expenseReport')->name('project.expense.report');
            Route::get('/project/expense/report/{project}', 'backend\ProjectExpenseReportController@expenseReportDetails')->name('project.expense.report.details');
            Route::get('/boq/exepse/compare/{project}', 'backend\ProjectExpenseReportController@boqCompare')->name('project.boq.compare');
            Route::post('subsidiary-create', 'backend\purchaseExpenseController@subsidiary_create')->name('subsidiary-create');
            Route::post('subsidiary-store', 'backend\purchaseExpenseController@subsidiary_store')->name('subsidiary-store');
            Route::post('subsidiary-edit', 'backend\purchaseExpenseController@subsidiary_edit')->name('subsidiary-edit');
            Route::post('subsidiary-update', 'backend\purchaseExpenseController@subsidiary_update')->name('subsidiary-update');
        });
        Route::post('payable-view', 'backend\purchaseExpenseController@payable_view')->name('payable-view');
        Route::get('payable', 'backend\purchaseExpenseController@payable')->name('payable');
        // expesnse home payable home
        Route::get('home-payable', 'backend\purchaseExpenseController@home_payable')->name('home-payable');
        Route::get('home-expense', 'backend\purchaseExpenseController@home_expense')->name('home-expense');
        Route::get('/expense-home-view', "backend\purchaseExpenseController@geteExpenseView")->name('expense-home-view');
        Route::get('/expense-by-party', "backend\purchaseExpenseController@geteExpense")->name('expense-by-party');

        Route::post('find-invoice', 'backend\purchaseExpenseController@find_invoice')->name('find-invoice');
        Route::post('find-invoice-date', 'backend\purchaseExpenseController@find_invoice_date')->name('find-invoice-date');
        Route::post('purch-exp-modal', 'backend\purchaseExpenseController@purch_exp_modal')->name('purch-exp-modal');
        Route::post('auth-purch-exp-modal', 'backend\purchaseExpenseController@auth_purch_exp_modal')->name('auth_purch-exp-modal');
        Route::post('approve-purch-exp-modal', 'backend\purchaseExpenseController@approve_purch_exp_modal')->name('approve_purch-exp-modal');
        Route::post('payment-modal', 'backend\purchaseExpenseController@payment_modal')->name('payment-modal');
        //
        Route::get('approveexpensedelete/{id}', 'backend\purchaseExpenseController@approveexpensedelete')->name('approveexpensedelete');
        Route::post('temp-payment-voucher-preview', 'backend\TempPaymentVoucherController@temp_payment_voucher_preview')->name('temp-payment-voucher-preview');
    //purchase end
    Route::post('expense-excel-import', 'backend\purchaseExpenseController@expense_excel_import')->name('expense-excel-import');
    Route::get('check-excel-import', 'backend\purchaseExpenseController@check_excel_import')->name('check-excel-import');
    Route::post('delete-excel-truck-entry', 'backend\purchaseExpenseController@delete_excel_truck_entry')->name('delete-excel-truck-entry');
    Route::post('final-excel-import', 'backend\purchaseExpenseController@final_excel_import')->name('final-excel-import');


    //Sale Import
    Route::post('invoice-excel-import', 'backend\SaleController@invoice_excel_import')->name('invoice-excel-import');
    Route::get('invoice-excel-export', 'backend\SaleController@invoice_excel_export')->name('invoice-excel-export');
    //Receipt Voucher import
    Route::post('receipt-excel-import', 'backend\SaleController@receipt_excel_import')->name('receipt-excel-import');
    Route::get('receipt-excel-export', 'backend\SaleController@receipt_excel_export')->name('receipt-excel-export');


    //sales start
        Route::prefix('/sales')->group(function(){
            Route::get('/report', 'ClientReportController@salesReport')->name('sales.report');
            Route::get('saleIssue', 'backend\SaleController@saleIssue')->name('saleIssue');
            Route::get('sale-list', 'backend\SaleController@sale_list')->name('sale-list');
            Route::get('sale-list-ajax', 'backend\SaleController@sale_list_ajax')->name('sale-list-ajax');
            Route::get('retention-list-ajax', 'backend\SaleController@retention_list_ajax')->name('retention-list-ajax');
            Route::post('search-all-invoice-list', 'backend\SaleController@search_all_invoice_list')->name('search-all-invoice-list');
            Route::get('all-invoice-list', 'backend\SaleController@all_invoice_list')->name('all-invoice-list');
            Route::get('sale-direct-invoice-list', 'backend\SaleController@sale_list_direct')->name('sale-direct-invoice-list');
            Route::get('sale-proforma-invoice-list', 'backend\SaleController@sale_list_proforma')->name('sale-proforma-invoice-list');

            Route::get('receipt-voucher2', 'backend\SaleController@receipt_voucher2')->name('receipt-voucher2');
            Route::get('receipt-voucher3', 'backend\SaleController@receipt_voucher3')->name('receipt-voucher3');

            Route::get('receipt-voucher-list-show', 'backend\SaleController@receipt_voucher_list_show')->name('receipt-voucher-list-show');
            Route::post('saleIssue/post', 'backend\SaleController@saleIssuepost')->name('saleIssuepost');
            Route::post('search-sale', 'backend\SaleController@search_sale')->name('search-sale');
            Route::post('project-limit', 'backend\SaleController@project_limit')->name('project-limit');
            Route::post('payment-post', 'backend\SaleController@payment_post')->name('payment-post');
            Route::post('partyInfosale2R', 'backend\SaleController@partyInfosale2')->name('partyInfosale2R');
            Route::post('partyInfodueInvoices', 'backend\SaleController@partyInfodueInvoices')->name('partyInfodueInvoices');
            Route::post('findInvoiceforReceipt', 'backend\SaleController@findInvoiceforReceipt')->name('findInvoiceforReceipt');
            Route::post('payment-change/{id}', 'backend\SaleController@payment_change')->name('payment-change');

            Route::post('search-receipt', 'backend\SaleController@search_receipt')->name('search-receipt');
            Route::post('find-job-project', 'backend\SaleController@find_job_project')->name('find-job-project');
            Route::get('receipt-realised/{id}', 'backend\SaleController@receipt_realised')->name('receipt-realised');
            Route::get('receipt-declined/{id}', 'backend\SaleController@receipt_declined')->name('receipt-declined');
            Route::post('receipt-nex-deposit/{id}', 'backend\SaleController@receipt_deposit')->name('nex-deposit-receipt');
            Route::get('sale-authorize', 'backend\SaleController@sale_authorize')->name('sale_authorize');
            Route::get('sale-approve', 'backend\SaleController@sale_approve')->name('sale_approve');
            Route::get('sale-authorize/{id}', 'backend\SaleController@sale_authorization')->name('sale-authorize');
            Route::get('sale-approve/{id}', 'backend\SaleController@sale_approval')->name('sale-approve');
            Route::get('invoice-delete/{id}', 'backend\SaleController@invoice_delete')->name('invoice-delete');
            Route::get('receipt-delete/{id}', 'backend\SaleController@receipt_delete')->name('receipt-delete');
            Route::get('sale-delete/{id}', 'backend\SaleController@sale_delete')->name('sale.delete');
            ///////
            Route::post('temp-receipt-voucher-post', 'backend\TempReceiptVoucherController@store')->name('temp-receipt-voucher-post');
            Route::post('temp-receipt-voucher-post-inv', 'backend\TempReceiptVoucherController@store_invoice_receipt')->name('temp-receipt-voucher-post-inv');

            Route::post('search-receipt-voucher-temp', 'backend\TempReceiptVoucherController@search_receipt')->name('search-receipt-voucher-temp');
            Route::get('receipt-voucher-authorize/{id}', 'backend\TempReceiptVoucherController@receipt_voucher_authorize')->name('receipt-voucher-authorize');
            Route::get('receipt-voucher-approve/{id}', 'backend\TempReceiptVoucherController@receipt_voucher_approve')->name('receipt-voucher-approve');
            Route::post('temp-receipt-voucher-update', 'backend\TempReceiptVoucherController@temp_receipt_voucher_update')->name('temp-receipt-voucher-update');
            Route::get('receipt-voucher-delete/{id}', 'backend\TempReceiptVoucherController@temp_receipt_delete')->name('receipt-voucher-delete');
            Route::get('direct-receipt', 'backend\TempReceiptVoucherController@direct_receipt')->name('direct-receipt');
            Route::post('direct-receipt-post', 'backend\TempReceiptVoucherController@direct_receipt_post')->name('direct-receipt-post');
            Route::post('/saleIssu/update','backend\SaleController@saleIssueEdit')->name('saleIssuepost.edit');
            Route::get('proforma-invoice-edit/{id}', 'backend\SaleController@proforma_edit')->name('proforma-invoice-edit');
            Route::get('invoice-create-form', 'backend\SaleController@create_form')->name('invoice-create-form');
            Route::get('transection', 'backend\SaleController@transection')->name('transection');
            Route::post('search-all-transection-list', 'backend\SaleController@search_all_transection_list')->name('search-all-transection-list');
            Route::delete('voucher-delete/{id}', 'backend\SaleController@voucherDelete')->name('voucher.delete');

        });
        Route::post('temp-receipt-voucher-preview', 'backend\TempReceiptVoucherController@temp_receipt_voucher_preview')->name('temp-receipt-voucher-preview');
        Route::prefix('/receipt')->group(function(){
            Route::get('receipt-voucher-edit/{id}', 'backend\TempReceiptVoucherController@receipt_voucher_edit')->name('receipt-voucher-edit');

            Route::get('temp-receipt-voucher-authorize', 'backend\TempReceiptVoucherController@temp_receipt_voucher_authorize')->name('temp-receipt-voucher-authorize');
            Route::get('temp-receipt-voucher-approve', 'backend\TempReceiptVoucherController@temp_receipt_voucher_approve')->name('temp-receipt-voucher-approve');
            Route::get('/report', 'ClientReportController@receiptReport')->name('receipt.report');
            Route::get('receipt-voucher2', 'backend\SaleController@receipt_voucher2')->name('receipt-voucher2');
            Route::get('receipt-voucher-list-show', 'backend\SaleController@receipt_voucher_list_show')->name('receipt-voucher-list-show');
            Route::post('search-sale', 'backend\SaleController@search_sale')->name('search-sale');
            Route::post('payment-post', 'backend\SaleController@payment_post')->name('payment-post');
            Route::post('partyInfosale2R', 'backend\SaleController@partyInfosale2')->name('partyInfosale2R');
            Route::post('projectReceipt', 'backend\SaleController@projectReceipt')->name('projectReceipt');
            Route::post('search-receipt', 'backend\SaleController@search_receipt')->name('search-receipt');
            Route::post('find-job-project', 'backend\SaleController@find_job_project')->name('find-job-project');
            Route::get('receipt-realised/{id}', 'backend\SaleController@receipt_realised')->name('receipt-realised');
            Route::get('receipt-declined/{id}', 'backend\SaleController@receipt_declined')->name('receipt-declined');
            Route::post('receipt-nex-deposit/{id}', 'backend\SaleController@receipt_deposit')->name('nex-deposit-receipt');
            Route::post('search-sale-inv', 'backend\SaleController@search_sale_inv')->name('search-sale-inv');
            Route::post('search-sale', 'backend\SaleController@search_sale')->name('search-sale');
            Route::get('sale-revenue', 'backend\SaleController@receivable')->name('receivable');
            Route::get('home-receivable' , "backend\SaleController@home_receivable")->name('home-receivable');
            // web.php
            Route::get('/invoices-by-project', "backend\SaleController@getInvoices")->name('invoices.by.project');

            Route::post('search-customer-due', 'backend\SaleController@search_customer_due')->name('search-customer-due');
        });
        Route::get('sale-revenue', 'backend\SaleController@receivable')->name('receivable');
        Route::get('recieved-data', 'backend\SaleController@recieved_data')->name('recieved-data');
        Route::get('receivable-view', 'backend\SaleController@receivable_view')->name('receivable-view');
        Route::get('retention-form', 'backend\SaleController@retentionForm')->name('retention-form');
        Route::post('authorize-sale-modal', 'backend\SaleController@authorize_sale_modal')->name('authorize-sale-modal');
        Route::post('receipt-list-modal', 'backend\SaleController@receipt_list_modal')->name('receipt-list-modal');
        Route::post('approve-sale-modal', 'backend\SaleController@approve_sale_modal')->name('approve-sale-modal');
        Route::post('sale-modal', 'backend\SaleController@sale_modal')->name('sale-modal');
        Route::get('home-sale-view', 'backend\SaleController@home_sale_view')->name('home-sale-view');
        Route::get('sale-print/{id}', 'backend\SaleController@sale_print')->name('sale-print');
        Route::get('auth-sale-print/{id}', 'backend\SaleController@auth_sale_print')->name('auth-sale-print');
        Route::get('temp-invoice-print/{id}', 'backend\JobProjectInvoiceController@temp_invoice_print')->name('temp-invoice-print');
        Route::get('invoice-print/{id}', 'backend\JobProjectInvoiceController@invoice_print')->name('invoice-print');

    //sales end

    //Accounting start
    Route::prefix('/accounting')->group(function(){
        Route::get('/report', 'ClientReportController@accountingReport')->name('accounting.report');
        Route::get('new-journal', 'backend\JournalEntryController@new_journal')->name('new-journal');
        Route::get("new-journal-creation", "backend\JournalEntryController@new_journal_creation")->name("new-journal-creation");
        Route::get('journal-authorization-section', 'backend\JournalEntryController@journal_authorization_section')->name('journal-authorization-section');
        Route::get("journal-approval-section", "backend\JournalEntryController@journal_approval_section")->name("journal-approval-section");
        Route::post('find-findamount', 'backend\JournalEntryController@findamount')->name('findamount');
        Route::post('find-cost-center/id', 'backend\JournalEntryController@findCostCenterId')->name('findCostCenterId');
        Route::get('journal-view-pdf/{id}', 'backend\JournalEntryController@journal_view_pdf')->name('journal-view-pdf');
        Route::post('journal-entry/post', 'backend\JournalEntryController@journalEntryPost')->name('journalEntryPost');
        Route::post('transection-heads', 'backend\JournalEntryController@transection_heads')->name('transection-heads');
        Route::get('journal-success/{id}', 'backend\JournalEntryController@journal_success')->name('journal-success');
        Route::get("journal-edit/{id}", "backend\JournalEntryController@journal_edit")->name("journal_edit");
        Route::post('journal-entry/edit-post/{journal}', 'backend\JournalEntryController@journalEntryEditPost')->name('journalEntryEditPost');
        Route::get('/journal/delete/{journal}', 'backend\JournalEntryController@journalDelete')->name('journalDelete');
        Route::get('/journal-delete/{journal}', 'backend\JournalEntryController@journal_delete')->name('journal-delete');
        Route::get('tem-journal-view-pdf/{id}', 'backend\JournalEntryController@tem_journal_view_pdf')->name('tem-journal-view-pdf');
        Route::get('/journal-authorize/{journal}', 'backend\JournalEntryController@journalMakeAuthorize')->name('journalMakeAuthorize');
        Route::get('/journal/delete/{journal}', 'backend\JournalEntryController@journalDelete')->name('journalDelete');
        Route::get('/journal-approve/{journal}', 'backend\JournalEntryController@journalMakeApprove')->name('journalMakeApprove');

        Route::resource('fund-allocation', 'backend\FundAllocationController');
        Route::get('fund-allocation-approval/{id}', 'backend\FundAllocationController@fund_allocation_approval')->name('fund-allocation-approval');
        Route::get('fund-allocation-delete/{id}', 'backend\FundAllocationController@fund_allocation_delete')->name('fund-allocation-delete');
        Route::get('fund-allocation-approve', 'backend\FundAllocationController@fund_allocation_approve')->name('fund-allocation-approve');
        Route::get('allocation-print/{id}', 'backend\FundAllocationController@allocation_print')->name('allocation-print');
        Route::post('approve-fund-collection-show', 'backend\FundCollectionController@approve_fund_collection_show')->name('approve-fund-collection-show');
        Route::post('account-head-type-check', 'backend\JournalEntryController@account_head_type_check')->name('account-head-type-check');
        Route::post('party-amount-store', 'backend\JournalEntryController@party_amount_store')->name('party-amount-store');
    });
    Route::post('voucher-preview-modal', 'backend\JournalEntryController@voucher_preview_modal')->name('voucher-preview-modal');
    Route::post("journal-authorize-show-modal", "backend\JournalEntryController@journal_authorize_show_modal")->name("journal-authorize-show-modal");
    Route::post("journal-approval-show-modal", "backend\JournalEntryController@journal_approval_show_modal")->name("journal-approval-show-modal");
    //Accounting End
    Route::post('cash-today-sale-received', 'backend\AccountsReportController@cash_today_sale_received')->name('cash-today-sale-received');
    Route::post('cash-today-payment-expense', 'backend\AccountsReportController@cash_today_payment_expense')->name('cash-today-payment-expense');
    Route::post('cash-previous-receivable-receive', 'backend\AccountsReportController@cash_previous_receivable_receive')->name('cash-previous-receivable-receive');
    Route::post('cash-previous-payable-payment', 'backend\AccountsReportController@cash_previous_payable_payment')->name('cash-previous-payable-payment');
    Route::post('cash-advance-receive', 'backend\AccountsReportController@cash_advance_receive')->name('cash-advance-receive');
    Route::post('cash-advance-payment', 'backend\AccountsReportController@cash_advance_payment')->name('cash-advance-payment');

    Route::post('bank-today-sale-received', 'backend\AccountsReportController@bank_today_sale_received')->name('bank-today-sale-received');
    Route::post('bank-today-payment-expense', 'backend\AccountsReportController@bank_today_payment_expense')->name('bank-today-payment-expense');
    Route::post('bank-previous-receivable-receive', 'backend\AccountsReportController@bank_previous_receivable_receive')->name('bank-previous-receivable-receive');
    Route::post('bank-previous-payable-payment', 'backend\AccountsReportController@bank_previous_payable_payment')->name('bank-previous-payable-payment');
    Route::post('bank-advance-receive', 'backend\AccountsReportController@bank_advance_receive')->name('bank-advance-receive');
    Route::post('bank-advance-payment', 'backend\AccountsReportController@bank_advance_payment')->name('bank-advance-payment');

    Route::post('previous-account-receivable', 'backend\AccountsReportController@previous_account_receivable')->name('previous-account-receivable');
    Route::post('today-account-receivable', 'backend\AccountsReportController@today_account_receivable')->name('today-account-receivable');

    Route::post('previous-account-payable', 'backend\AccountsReportController@previous_account_payable')->name('previous-account-payable');
    Route::post('today-account-payable', 'backend\AccountsReportController@today_account_payable')->name('today-account-payable');
    Route::post('fund-transfer/{from}/{to}', 'backend\AccountsReportController@fund_transfer')->name('fund-transfer');

    Route::post('today-payment-expense/{pay_mode}', 'backend\AccountsReportController@today_payment_expense')->name('today-payment-expense');
    Route::post('previous-payment-expense/{pay_mode}', 'backend\AccountsReportController@previous_payment_expense')->name('previous-payment-expense');

     // lpo bill start
     Route::prefix('/lpo-bill')->group(function(){
        Route::get('/report', 'ClientReportController@lpo_bill_report')->name('lpo-bill-report');
        Route::post('lpo-bill-create', 'backend\LpoBillController@create')->name('lpo-bill-create');
        Route::post('lpo-bill-store', 'backend\LpoBillController@store')->name('lpo-bill-store');
        Route::post('lpo-bill-update', 'backend\LpoBillController@update')->name('lpo-bill-update');
        Route::get('lpo-bill-list', 'backend\LpoBillController@index')->name('lpo-bill-list');
        Route::get('lpo-bill-delete/{id}', 'backend\LpoBillController@destroy')->name('lpo-bill-delete');
        Route::get('lpo-bill-edit/{id}', 'backend\LpoBillController@edit')->name('lpo-bill-edit');
        Route::get('print/{id}', 'backend\LpoBillController@print')->name('lpo-bill-print');
        Route::get('lpo-to-purchase-expense/{lpo}', 'backend\LpoBillController@lpo_to_purchase_expense')->name('lpo-to-purchase-expense');
        Route::post('lpo-to-expense', 'backend\LpoBillController@expense')->name('lpo-to-expense');
        Route::get('lpo-approve/{id}', 'backend\LpoBillController@lpo_approve')->name('lpo-approve');

    });
    Route::post('lpo-bill-view', 'backend\LpoBillController@view')->name('lpo-bill-view');
    Route::post('search-lpo-bill', 'backend\LpoBillController@search_lpo_bill')->name('search-lpo-bill');
    //lpo bill end

    Route::prefix('requisition')->group(function(){

        Route::resource('requisitions', 'backend\RequisitionControler');
        Route::get('requisition/print/{requisition}', 'backend\RequisitionControler@print')->name('requisitions.print');
        Route::get('requisition/make/lpo/{requisition}', 'backend\RequisitionControler@makeLpo')->name('requisitions.make.lpo');
        Route::post('requistion/search', 'backend\RequisitionControler@search')->name('search-requisition');
        Route::get('requisition-approve/{id}', 'backend\RequisitionControler@requisition_approve')->name('requisition-approve');
        Route::post('requisition-rejected', 'backend\RequisitionControler@requisition_rejected')->name('requisition-rejected');

        Route::get('requisitions/mobile/index', 'backend\RequisitionControler@mobileIndex')->name('requisitions.mobile.index');
        Route::get('requisitions/mobile/show/{id}', 'backend\RequisitionControler@mobileShow')->name('requisitions.mobile.show');
        Route::post('requisitions/mobile/store', 'backend\RequisitionControler@mobileStore')->name('requisitions.mobile.store');
        Route::get('/mobile/requisition/{id}/edit', 'backend\RequisitionControler@Mobileedit')->name('mobile.requisition.edit');

    });

    //report start
    Route::post('fetch-company-oth', 'backend\AccountsReportController@company_oth')->name('fetch-company-oth');

        Route::prefix('/reports')->group(function(){
            Route::get('/accounts/{type}', 'backend\AccountsReportController@accountsReceivable')->name('accounts.receivable');
            Route::get('/accounts/{report_type}/details/{party}', 'backend\AccountsReportController@accountsReceivableDetails')->name('accounts.receivable.details');
            Route::get('account/{type}/print','backend\AccountsReportController@accountsReceivablePrint')->name('accounts.receivable.print');
            Route::get('/account/{type}/pdf','backend\AccountsReportController@accountsReceivablePdf')->name('accounts.receivable.pdf');
            Route::get('account/{type}/extended/pdf','backend\AccountsReportController@accountsReceivableExtendedPdf')->name('accounts.receivable.extended.pdf');
            Route::get('/account/{type}/excel','backend\AccountsReportController@accountsReceivableExcel')->name('accounts.receivable.excel');
            Route::get('account/{type}/extended/excel','backend\AccountsReportController@accountsReceivableExtendedExcel')->name('accounts.receivable.extended.excel');
            Route::get('/', 'ClientReportController@report')->name('report');
            Route::prefix('/accounting-report')->group(function(){
                Route::get('new-general-ledger', 'backend\AccountsReportController@new_general_ledger')->name('new-general-ledger');
                Route::get('general-ledger-yearly-details/{account_head_id}', 'backend\AccountsReportController@general_ledger_yearly_details')->name('general_ledger_yearly_details');
                Route::get('general-ledger-details/{account_head}','backend\AccountsReportController@general_ledger_details')->name('general_ledger_details');
                Route::get('party-report', 'backend\AccountsReportController@party_report')->name('party-report');
                Route::get('party-report-details/{party}/{searched_project?}', 'backend\AccountsReportController@party_report_details')->name('party-report-detail');
                Route::get('new-trial-balance', 'backend\AccountsReportController@new_trial_balance')->name('new-trial-balance');
                Route::get('income-statement', 'backend\AccountsReportController@income_statement')->name('income-statement');
                Route::get('daily-report', 'backend\AccountsReportController@daily_report')->name('daily-report');
                Route::get('head-details/{account_head_id}', 'backend\AccountsReportController@head_details')->name('head-details');
                Route::get('party-head-details/{party}-{account_head_id}', 'backend\AccountsReportController@party_head_details')->name('party-head-details');
                Route::get('sub-ledger/{master_account}', 'backend\AccountsReportController@sub_ledger')->name('sub-ledger');

                Route::get('balance-sheet', 'backend\AccountsReportController@balance_sheet')->name('balance-sheet');
                Route::get('sale-reports', 'backend\AccountsReportController@sale_reports')->name('sale-reports');
                Route::get('purchase-reports', 'backend\AccountsReportController@purchase_reports')->name('purchase-reports');
                Route::get('receivable-reports', 'backend\AccountsReportController@receivable_reports')->name('receivable-reports');
                Route::get('payable-reports', 'backend\AccountsReportController@payable_reports')->name('payable-reports');
                Route::get('missing/invoice/number', 'backend\AccountsReportController@missing_invoice_number')->name('missing-invoice-number');
            });
            Route::get('bank-account-report', 'backend\AccountsReportController@bank_account_report')->name('bank-account-report');
            Route::get('petty-cash-report', 'backend\AccountsReportController@petty_cash_report')->name('petty-cash-report');
            Route::get('tax-reports', 'backend\AccountsReportController@tax_reports')->name('tax-reports');
            Route::get('statement-other-comprehensive-income', 'backend\AccountsReportController@statement_other_comprehensive_income')->name('statement-other-comprehensive-income');
            Route::get('statement-financial-position', 'backend\AccountsReportController@statement_financial_position')->name('statement-financial-position');
            Route::post('sub-head-details', 'backend\AccountsReportController@sub_head_details')->name('sub-head-details');
            Route::post('tax-sub-head-details', 'backend\AccountsReportController@tax_sub_head_details')->name('tax-sub-head-details');
            Route::get('conporate-tax-details', 'backend\AccountsReportController@conporate_tax_details')->name('conporate-tax-details');

            Route::resource('/party','backend\PartyReportController');
            Route::get('input-vat-report', 'backend\AccountsReportController@input_vat_report')->name('input-vat-report');
            Route::get('output-vat-report', 'backend\AccountsReportController@output_vat_report')->name('output-vat-report');
            Route::get('stock-report', 'backend\AccountsReportController@stock_report')->name('stock-report');
            Route::post('head-expense-detail', 'backend\AccountsReportController@head_expense_detail')->name('head-expense-detail');
            Route::post('head-project-expense', 'backend\AccountsReportController@head_project_expense')->name('head-project-expense');
            Route::post('project-expense-adjust', 'backend\AccountsReportController@project_expense_adjust')->name('project-expense-adjust');
            Route::post('project-expense-adjust-store', 'backend\AccountsReportController@project_expense_adjust_store')->name('project-expense-adjust-store');
            //Stock Position
        });

        Route::prefix('/pdf')->group(function(){
            Route::get('general/ledger', 'backend\AccountReportPdfController@generalLedgerPdf')->name('general.ledger.pdf');
            Route::get('extended/general/ledger', 'backend\AccountReportPdfController@extendedGeneralLedgerPdf')->name('extended.general.ledger.pdf');

            Route::get('general/ledger/excel', 'backend\AccountReportPdfController@generalLedgerExcel')->name('general.ledger.excel');
            Route::get('extended/general/ledger/excel', 'backend\AccountReportPdfController@extendedGeneralLedgerExcel')->name('extended.general.ledger.excel');

            Route::get('/trail-balance-excel', 'backend\AccountReportPdfController@trialBalanceExcel')->name('trial-balance-excel');
            Route::get('/trial-balance/pdf/', 'backend\AccountReportPdfController@trialBalancePdf')->name('trial-balance-pdf');

            Route::get('/party-report-pdf', 'backend\AccountReportPdfController@party_report_pdf')->name('party_report_pdf');
            Route::get('/party-report/excel/', 'backend\AccountReportPdfController@party_report_excel')->name('party_report_excel');
            Route::get('/party-report/extended/excel', 'backend\AccountReportPdfController@party_report_extend_excel')->name('party_report_extend_excel');
            Route::get('/party-report-extended', 'backend\AccountReportPdfController@party_report_extend_pdf')->name('party_report_extend_pdf');

            Route::get('/sale-reports-pdf', 'backend\AccountReportPdfController@sale_report_pdf')->name('sale-report-pdf');
            Route::get('missing/invoice/number', 'backend\AccountReportPdfController@missing_invoice_number_pdf')->name('missing-invoice-number-pdf');
            Route::get('/sale-reports-extend-pdf', 'backend\AccountReportPdfController@sale_report_extend_pdf')->name('sale-report-extend-pdf');
            Route::get('/sale-reports-excel', 'backend\AccountReportPdfController@sale_report_excel')->name('sale-report-excel');
            Route::get('/sale-reports-extend-excel', 'backend\AccountReportPdfController@sale_report_extend_excel')->name('sale-report-extend-excel');
        });

        Route::get('/check/download/notifications', 'backend\AccountReportPdfController@checkDownloadNotification');
        Route::get('/download/large/file', 'backend\AccountReportPdfController@downloadFile')->name('download-large-file');
        Route::get('daily-summary', 'backend\AccountsReportController@daily_summary')->name('daily-summary.report');

        //report en
        Route::prefix('/project')->group(function(){
            Route::get('/reports','ClientReportController@projectReport')->name('jobporjects.report');
            Route::resource('/gantt/chart', 'backend\GnattChartController')->names('gnatt.chart');
            Route::get('gantt-chart-ajax', 'backend\GnattChartController@ajaxGanttChart')->name('gantt.chart.ajax');
            Route::post('gantt-chart-view-ajax', 'backend\GnattChartController@ajaxGanttChartView')->name('gantt.chart.view.ajax');
            Route::get('/gnatt/chart/report/{chart}', 'backend\GnattChartController@report')->name('gnatt.chart.report');
            Route::post('gnatt/item/store','backend\GnattChartController@itemStore')->name('gnatt.chart.item.store');
            Route::get('gnatt/chart/item/edit/{item}', 'backend\GnattChartController@itemEdit')->name('chart.item.edit');
            Route::delete('gnatt/item/delete/{item}', 'backend\GnattChartController@itemDestroy')->name('chart.item.destroy');
            Route::put('gnatt/item/update/{item}','backend\GnattChartController@itemUpdate')->name('gnatt.chart.item.update');
            Route::get('/gantt/chart/status/{project}', 'backend\GnattChartController@getChartStatus') ->name('gantt.chart.status');
            Route::get('gnatt/chart/approve/{chart}', 'backend\GnattChartController@approve')->name('gnatt.chart.approve');

            Route::post('/gnatt/task/traking-store','backend\JobProjectController@traking_store')->name('traking-store');
            Route::get('/gnatt/tracking/{id}','backend\JobProjectController@tracking')->name('tracking');

            Route::resource('/bill-of-quantity/boq','backend\BillOfQuantityController')->names('boq');
            Route::get('/boq/sample/list', 'backend\BillOfQuantityController@boqSample')->name('boq.sample.list');
            Route::get('/get/bill-of-quantity','backend\BillOfQuantityController@getBoqItems')->name('get.boq.items');
            Route::post('/boq/factor/store', 'backend\BillOfQuantityController@storeBoqSample')->name('storeBoqFactor');
            Route::get('/get/old/boq/sample', 'backend\BillOfQuantityController@getOldBoqSample')->name('get.old.boq.factor');

            Route::get('/bill-of-quantity/approved/{boq}','backend\BillOfQuantityController@boqApprove')->name('boq.approve');
            Route::get('/bill-of-quantity/print/{boq}','backend\BillOfQuantityController@boqPrint')->name('boq.print');
            Route::post('/boq/item/update/{boq}', 'backend\BillOfQuantityController@itemUpdate')->name('boq.item.update');
            Route::get('boq/get/{project}', 'backend\BillOfQuantityController@getBoq')->name('get.boq');
            Route::get('/get/party/boq/{party}', 'backend\BillOfQuantityController@partyBoq')->name('get.party.boq');
            Route::post('project-item-get', 'backend\BillOfQuantityController@project_item_get')->name('project-item-get');
            Route::get('get/party/project/{party}', 'backend\BillOfQuantityController@partyProject')->name('get.party.project');
            Route::get('get/project/task/{project}', 'backend\BillOfQuantityController@projectTask')->name('get.project.task');

            Route::delete('/boq/items/destroy/{item_id}/{type}', 'backend\BillOfQuantityController@itemDestroy')->name('boq.items.custom-destroy');
            Route::get('/boq/items/edit/{item_id}/{type}', 'backend\BillOfQuantityController@itemEdit')->name('boq.items.edit');

            Route::post('project-document-view', 'backend\NewProjectController@project_document_view')->name('project-document-view');
            Route::post('project-document-store', 'backend\NewProjectController@project_document_store')->name('project-document-store');

            Route::resource('/lpo-projects','backend\LpoProjectController');

            Route::resource('/new-project','backend\NewProjectController');
            Route::post('prospect-excel-import', 'backend\NewProjectController@prospect_excel_import')->name('prospect-excel-import');
            Route::get('new-project/gantt-chart/{project}','backend\NewProjectController@ganttChart')->name('new-project.gantt-chart');
            Route::Get('new-project/gantt-chart/{project}/pdf', 'backend\NewProjectController@ganttChartPdf')->name('new-project.gantt-chart.pdf');
            Route::resource('/project/tasks', 'backend\NewProjectTaskController')->names('project.tasks');

            Route::get('/new-project/roi/reports/{project_id}','backend\JobProjectController@roiReport')->name('new.project.roy.report');
            Route::get('/lpo-print/{lpo_project}','backend\LpoProjectController@lpo_print')->name('lpo-print');

            Route::get('/lpo-projects/get/{lpo_project}','backend\LpoProjectController@getLpoProject')->name('get.lop.project');
            Route::get('/party/quotations/{party}', 'backend\LpoProjectController@partyQuotations')->name('party.quotations');
            Route::get('/quotation/projects/{quotation}','backend\LpoProjectController@quotationProjects')->name('quotation.projects');

            Route::resource('/projects','backend\JobProjectController');
            Route::post('new-project-create', 'backend\JobProjectController@new_project_create')->name('new-project-create');
            Route::post('new-project-edit', 'backend\JobProjectController@new_project_edit')->name('new-project-edit');
            Route::post('projects-update-status','backend\JobProjectController@project_update')->name('projects.update-status');
            Route::get('home-project','backend\JobProjectController@home_project')->name('home-project');


            Route::get('/projects/ajax/create','backend\JobProjectController@ajaxCreate')->name('project.ajax.create');
            Route::get('/work/order/create/{lpo_project}','backend\JobProjectController@workStationCreate')->name('work.station.create');
            Route::get('/job-projects/detials/{job_project}','backend\JobProjectController@projectDetails')->name('jobproject.details');
            Route::resource('/project/payments','backend\JobProjectPaymentController');
            Route::post('/porject/payment/store','backend\JobProjectController@projectPaymentStore')->name('projects.payment.store');
            Route::post('/jobproject/customer','backend\JobProjectController@addCustomer')->name('jobproject.customer.store');
            Route::get('/porjects/expense','backend\ProjectExpenseController@index')->name('porject.expense.index');
            Route::get('/porjects/expense/create','backend\ProjectExpenseController@create')->name('porject.expense.create');
            Route::post('/porjects/expense/store','backend\ProjectExpenseController@store')->name('project.expense.store');
            Route::get('/projects/expense/{job_project}','backend\ProjectExpenseController@show')->name('project.expense.show');
            Route::get('/projects/expense/edit/{job_project}','backend\ProjectExpenseController@edit')->name('project.expense.edit');
            Route::get('/projects/expense/{job_project}','backend\ProjectExpenseController@show')->name('project.expense.show');
            Route::put('/projects/expense/update/{job_project}','backend\ProjectExpenseController@update')->name('project.expense.update');
            Route::get('/project/expense/get/units','backend\ProjectExpenseController@getUnits')->name('get.units');
            Route::get('/work/station/vat','backend\JobProjectController@getVat')->name('get.porjects.vat');
            Route::get('/job/project/invoice/{job_project}','backend\JobProjectInvoiceController@projectInvoiceCreate')->name('project.invoice.create');
            Route::post('/job/project/invoice/store','backend\JobProjectInvoiceController@store')->name('project.invoice.store');
            Route::get('/job/project/invoice','backend\JobProjectInvoiceController@index')->name('project.invoice.index');
            Route::get('/job/project/invoice/show/{tem_invoice}','backend\JobProjectInvoiceController@show')->name('project.invoice.show');
            Route::get('/job/project/invoice/edit/{tem_invoice}','backend\JobProjectInvoiceController@edit')->name('project.invoice.edit');
            Route::put('/job/project/invoice/update/{tem_invoice}','backend\JobProjectInvoiceController@update')->name('project.invoice.update');
            Route::get('/job/project/authorize/change/{tem_invoice}','backend\JobProjectInvoiceController@makeAutorizeInvoice')->name('project.invoice.autorize.change');
            Route::get('/job/project/author/invoice','backend\JobProjectInvoiceController@authorizeInvoice')->name('project.authorize.invoice');
            Route::get('/job/project/approved/change/{tem_invoice}','backend\JobProjectInvoiceController@makeApprovedInvoice')->name('project.invoice.approve.change');
            Route::get('/job/project/approved/invoice','backend\JobProjectInvoiceController@approvedInvoice')->name('project.approve.invoice');
            Route::get('/job/project/approved-invoice/view/{invoice}','backend\JobProjectInvoiceController@approvedInvoiceView')->name('project.approve.invoice.show');
            Route::get('/check/invoice/no','backend\JobProjectInvoiceController@checkInvoiceNo')->name('get.unique.invoice.no');

            Route::get('/job/project/invoice/delete/{tem_invoice}','backend\JobProjectInvoiceController@destroy')->name('project.invoice.delete');
            Route::get('/jobprojects/reports2','backend\JobProjectController@projectReport2')->name('projects.report2');
            Route::get('/jobprojects/reports','backend\JobProjectController@projectReport')->name('projects.report');
            Route::get('/convert/task/invoice/{invoice}','backend\JobProjectInvoiceController@convert_tax_invoice')->name('convert-to-tax-invoice');
            Route::get('/jobprojects/roi/reports/{project_id}','backend\JobProjectController@roiReport')->name('projects.roi.report');
            Route::post('/report/roi/details','backend\RoiReportController@reportDetails')->name('roi.report.details');
            Route::get('/job-project-print/{job_project}','backend\JobProjectController@job_project_print')->name('job-project-print');

            Route::get('transaction-history', 'backend\purchaseExpenseController@transaction_history')->name('transaction-history');
            Route::get('party/project/{party}', 'backend\JobProjectController@partyProjects')->name('party.projects');
            Route::get('project/party/{project}', 'backend\JobProjectController@projectParty')->name('project.party');

            Route::get('/cost/analysis','backend\ProjectCostAnalysisController@costAnalysis')->name('cost.analysis');
            Route::get('/cost/details/{project}', 'backend\ProjectCostAnalysisController@metarilaCost')->name('project.metarial.cost');
            Route::get('/invoices/{project}', 'backend\ProjectCostAnalysisController@invoices')->name('project.invoice');
            Route::get('receipts/details/{project}', 'backend\ProjectCostAnalysisController@receipt')->name('project.receipt');
            Route::get('/labour/cost/{project}', 'backend\ProjectCostAnalysisController@labourCost')->name('project.labour.cost');
            Route::get('/labor/cost/details/{project}/{employee}', 'backend\ProjectCostAnalysisController@labourCostDetails')->name('labour.cost.report.details');
            Route::get('project/cost/analysis','backend\ProjectCostAnalysisController@costAnalysis')->name('cost.analysis');

            Route::post('boq-excel-import', 'backend\BillOfQuantityController@boq_excel_import')->name('boq-excel-import');
            Route::get('boq-check-excel-import', 'backend\BillOfQuantityController@boq_check_excel_import')->name('boq-check-excel-import');

            Route::resource('engineer/reports', 'backend\EngineerReportController')->names('engineer.reports');

            Route::get('engineer/reports/approve/{report}', 'backend\EngineerReportController@approve')->name('engineer.reports.approve');
            Route::get('search-project-report', 'backend\ProjectCostAnalysisController@search_project_report')->name('search-project-report');
        });

        Route::get('/customer/project/{customer}','backend\JobProjectController@customerProject')->name('customer.projects');
        //project end
        Route::get('/report/roi/chart/{id}','backend\JobProjectController@roiReportChart')->name('roi.report.chart');
        Route::post('job-document-update', 'backend\JobProjectController@job_document_update')->name('job-document-update');
        Route::post('document-upload-view', 'backend\JobProjectController@job_document_view')->name('document-upload-view');
        Route::post('delete-job-document', 'backend\JobProjectController@delete_job_document')->name('delete-job-document');
        Route::post('find-project-task', 'backend\JobProjectController@find_project_task')->name('find-project-task');
        Route::post('find-project-task-item', 'backend\JobProjectController@find_project_task_item')->name('find-project-task-item');
        //party report

        Route::get('/payroll-dashboard', 'backend\Dashboard2Controller@index');
            Route::resource("employees", "backend\Payroll\EmployeeController");
            Route::resource("employee-attendence", "backend\Payroll\EmployeeAttendenceController");
            Route::resource("employee-banks", "backend\Payroll\EmployeeBankController");
            Route::resource("employee-salary", "backend\Payroll\EmployeeSalaryController");
            Route::get('employee/payroll', 'backend\Payroll\EmployeeController@getPayroll')->name('get.payroll');
            Route::get('/check-employee-code', 'backend\Payroll\EmployeeController@checkCode')->name('employee.check-code');

            Route::resource("salary-structures", "backend\Payroll\SalaryComponentController");
            Route::resource("grade-wise-salary-components", "backend\Payroll\GradeWiseSalaryComponentController");
            Route::resource("salary-types", "backend\Payroll\SalaryTypesController");
            Route::resource("grades", "backend\Payroll\GradeController");
            Route::resource("pay-salary", "backend\Payroll\PaySalaryController");
            Route::resource('nationality', 'backend\Payroll\NationalityController');
            Route::resource('department', 'backend\Payroll\DepartmentController');
            Route::resource('division', 'backend\Payroll\DivisionController');
            Route::resource('country-code', 'backend\Payroll\CountryCodeController');
            Route::resource('branch', 'backend\Payroll\BankBranchController');
            Route::resource('salary-process', 'backend\Payroll\SalaryprocessController');

            Route::resource('time-tracking', 'backend\Payroll\TimeTrackController');
            Route::resource('grade-wise-leave-list', 'backend\Payroll\GradeWiseLeaveListController');
            Route::resource('leave-management', 'backend\Payroll\LeaveManagementController');
            Route::resource('performance-management', 'backend\Payroll\PerformanceController');
            Route::resource('employee-history', 'backend\Payroll\EmployeeHistoryController');
            Route::resource('employee-document', 'backend\Payroll\EmployeeDocumentController');
            Route::resource('deduction-entry', 'backend\Payroll\DeductionEntryController');

            Route::get('salary-process/authorize/list', 'backend\Payroll\SalaryprocessController@authorizeList')->name('salary-process.authorize-list');
            Route::get('salary-process/approve/{id}','backend\Payroll\SalaryprocessController@approve')->name('salary.procres.approve');
            Route::get('employee/salary/process/destroy/{id}','backend\Payroll\SalaryprocessController@destroy')->name('employee.salary.procres.destroy');
            Route::post('salary-process/action','backend\Payroll\SalaryprocessController@procesAction')->name('salary-process.action.all');
            Route::get('salary/process/approve/list', 'backend\Payroll\SalaryprocessController@approveList')->name('salary-process.approve-list');

            Route::post('deduction-edit/{id}', 'backend\Payroll\DeductionEntryController@update');
            Route::get("salary-process-start", "backend\Payroll\SalaryprocessController@crearteSalary")->name("salary-process-start");
            Route::post("salary-process-confirm", "backend\Payroll\SalaryprocessController@confirm")->name("salary-process-confirm");
            Route::get('employee/name', 'backend\Payroll\EmployeeBankController@employeeInfo')->name('employee-name');
            Route::get('employee/info', 'backend\Payroll\EmployeeController@employeeInfo')->name('employee-info');
            Route::get('routing/number', 'backend\Payroll\EmployeeBankController@bankInfo')->name('routing-number');
            Route::get("salary-crearte", "backend\Payroll\SalaryController@crearteSalary")->name("salary-crearte");
            Route::get("base-table", "backend\Payroll\NationalityController@base")->name("base-table");
            Route::post('percent/', 'backend\Payroll\SalaryController@percentCount')->name('percentCount');
            Route::get('generate-pdf', 'backend\Payroll\PDFController@generatePDF');
            Route::get('generate-payslip', 'backend\Payroll\PDFController@generatePayslip')->name('generate-payslip');
            Route::get('/download/payslip', 'backend\Payroll\SalaryprocessController@downloadPayslip')->name('download.payslip');
            Route::get('generate-management-report', 'backend\Payroll\PDFController@generateManagementReport')->name('generate-management-report');
            Route::get('print-document', 'backend\Payroll\PaySalaryController@printDocument')->name('print-document');
            Route::get('employee-time-tracking', 'backend\Payroll\TimeTrackController@employeeInfo')->name('employee-time-tracking');
            Route::get('leave-info', 'backend\Payroll\LeaveManagementController@employeeInfo')->name('leave-info');
            Route::post("employee-leave-edit-modal", "backend\Payroll\LeaveManagementController@employee_leave_edit_modal")->name("employee-leave-edit-modal");
            Route::get('employeeLeaveDocumentDelete/{id}', 'backend\Payroll\LeaveManagementController@employeeLeaveDocumentDelete')->name('employeeLeaveDocumentDelete');
            Route::post("employee-view-leave-modal", "backend\Payroll\LeaveManagementController@employee_view_leave_modal")->name("employee-view-leave-modal");
            Route::get('time-entry/{id}/{status}', 'backend\Payroll\TimeTrackController@timeEntry')->name('time-entry');
            Route::get('employeeProDocumentDelete/{id}', 'backend\Payroll\EmployeeController@employeeProDocumentDelete')->name('employeeProDocumentDelete');
            Route::get('employee-view-profile-modal', 'backend\Payroll\EmployeeController@employeePriview')->name('employee-view-profile-modal');
            Route::get("employee-history-view", "backend\Payroll\EmployeeHistoryController@employee_history_view")->name("employee-history-view");
            Route::get("employee-document-view", "backend\Payroll\EmployeeDocumentController@employee_history_view")->name("employee-document-view");
            Route::get("employee-document-edit/{id}", "backend\Payroll\EmployeeDocumentController@employeeDocumentEdit")->name("employee-document-edit");
            Route::post("employee-document-update/{id}", "backend\Payroll\EmployeeDocumentController@employeeDocumentUpdate")->name("employee-document-update");
            Route::get("professional-document-update/{id}", "backend\Payroll\EmployeeDocumentController@professionalDocumentEdit")->name("professional-document-edit");
            Route::post("professional-document-update", "backend\Payroll\EmployeeDocumentController@professionalDocumentUpdate")->name("professional-document-update");
            Route::get("history-document-edit/{id}", "backend\Payroll\EmployeeDocumentController@historyDocumentEdit")->name("history-document-edit");
            Route::post("history-document-update", "backend\Payroll\EmployeeDocumentController@historyDocumentUpdate")->name("history-document-update");
            Route::get('employee-search', 'backend\Payroll\EmployeeController@employeeInfo')->name('employee-search');
            Route::get('history-search', 'backend\Payroll\EmployeeHistoryController@historyInfo')->name('history-search');
            Route::get('document-search', 'backend\Payroll\EmployeeDocumentController@documentInfo')->name('document-search');
            Route::get('find-currency', 'backend\Payroll\EmployeeController@findCurrency')->name('find-currency');
            Route::get('employees-approve/{id}', 'backend\Payroll\EmployeeController@approve')->name('employees-approve');
            Route::get('employees-edit-approve/{id}', 'backend\Payroll\EmployeeController@editApprove')->name('employees-edit-approve');
            Route::resource('employee-attendance', 'backend\EmployeeAttendence');
            Route::post('employee-attendance/index', 'backend\EmployeeAttendence@index')->name('search_em_attend');
            Route::resource('employee-leave', 'backend\EmployeeLeaveController');
            Route::get('employee-leave-print/{id}', 'backend\EmployeeLeaveController@employee_leave_print')->name('employee-leave-print');
            Route::get('employee-attendance-print', 'backend\EmployeeAttendence@employee_attendance_print')->name('employee-attendance-print');
            Route::get("new-employee-attendance-edit", "backend\EmployeeAttendence@new_employee_attendance_edit")->name("new-employee-attendance-edit");
            Route::post("new-employee-attendance-update", "backend\EmployeeAttendence@new_employee_attendance_update")->name("new-employee-attendance-update");
            Route::prefix('new-employee-attendance')->group(function () {
            Route::get('/report', 'ClientReportController@hrReport')->name('hr.payroll.report');
            Route::get("new-employee-section", "backend\EmployeeController@new_employee_section")->name("new-employee-section");
            Route::get("new-employee-document", "backend\EmployeeDocumentController@new_employee_document")->name("new-employee-document");
            Route::get("new-salary-structure", "backend\SalaryStructureController@new_salary_structure")->name("new-salary-structure");
            Route::get("new-management-team", "backend\ManagementTeamController@new_management_team")->name("new-management-team");
            Route::get("new-eChartOf-account", "backend\EChartOfAccountController@new_eChartOf_account")->name("new-eChartOf-account");
            Route::get("new-supllier", "backend\SupplierController@new_supllier")->name("new-supllier");
            Route::get("new-mapping", "backend\MappingController@new_mapping")->name("new-mapping");
            Route::get('/employee/attendance/search/{id}','backend\EmployeeAttendence@attendanceSearch')->name('employee.attendance.search');
        });

        Route::prefix('/hr/payroll')->group(function () {
            Route::get("new-employee-leave", "backend\EmployeeLeaveController@new_employee_leave")->name("new-employee-leave");

            Route::get('/report', 'ClientReportController@hrReport')->name('hr.payroll.report');
            Route::resource("employees", "backend\Payroll\EmployeeController");
            Route::resource('division', 'backend\Payroll\DivisionController');
            Route::resource('department', 'backend\Payroll\DepartmentController');
            Route::resource("salary-types", "backend\Payroll\SalaryTypesController");
            Route::resource('nationality', 'backend\Payroll\NationalityController');
            Route::resource('branch', 'backend\Payroll\BankBranchController');
            Route::resource('grade-wise-leave-list', 'backend\Payroll\GradeWiseLeaveListController');
            Route::resource("grade-wise-salary-components", "backend\Payroll\GradeWiseSalaryComponentController");
            Route::resource("employee-salary", "backend\Payroll\EmployeeSalaryController");
            Route::resource("salary-structures", "backend\Payroll\SalaryComponentController");
            Route::resource('deduction-entry', 'backend\Payroll\DeductionEntryController');
            Route::resource('salary-process', 'backend\Payroll\SalaryprocessController');
            Route::resource("pay-salary", "backend\Payroll\PaySalaryController");
            Route::resource("grades", "backend\Payroll\GradeController");
            Route::resource('employee-history', 'backend\Payroll\EmployeeHistoryController');
            Route::resource('employee-document', 'backend\Payroll\EmployeeDocumentController');
            Route::get("pay-request", "backend\Payroll\PaySalaryController@payRequest")->name('pay-request');
            Route::resource('employee-overtime', 'backend\EmployeeOvertimeController');
            Route::get('employee-overtime-edit', 'backend\EmployeeOvertimeController@employee_overtime_edit')->name('employee-overtime-edit');
            Route::post('employee-overtime-update', 'backend\EmployeeOvertimeController@employee_overtime_update')->name('employee-overtime-update');
            Route::resource('advance-salary', 'backend\AdvanceSalaryController');

            Route::get('/project/working/report', 'backend\Payroll\ProjectReportController@report')->name('project.working.report');
            Route::get('/project/working/report/detaile/{id}', 'backend\Payroll\ProjectReportController@reportDetails')->name('project.working.report.details');
        });

        Route::post('search-holiday-recode', 'backend\EmployeeAttendence@search_holiday_recode')->name('search-holiday-recode');
        Route::get('employee-overtime-print', 'backend\EmployeeOvertimeController@employee_overtime_print')->name('employee-overtime-print');
        Route::post('pay-salary-view', 'backend\Payroll\PaySalaryController@pay_salary_view')->name('pay-salary-view');
        Route::post('pay-salary-document', 'backend\Payroll\PaySalaryController@pay_salary_document')->name('pay-salary-document');
        Route::post('pay-salary-document-upload', 'backend\Payroll\PaySalaryController@pay_salary_document_upload')->name('pay-salary-document-upload');
        Route::post('delete-salary-pay-document', 'backend\Payroll\PaySalaryController@delete_salary_pay_document')->name('delete-salary-pay-document');

    //****************************************************payroll start **********************************************
    //****************************************************payroll start **********************************************
    Route::post('pay-salary-sheet', 'backend\Payroll\SalaryprocessController@pay_salary_sheet')->name('pay-salary-sheet');
    Route::post('other-document-delete', 'backend\Payroll\EmployeeController@other_document_delete')->name('other-document-delete');
    Route::get("employees-salray-cetificate/{id}", "backend\Payroll\EmployeeController@print_salary_certificate")->name("employees.salray-cetificate");
    Route::get("employees-delete/{id}", "backend\Payroll\EmployeeController@destroy")->name("employees-delete");
    Route::get("custom-policy", "backend\Payroll\EmployeeController@find_policy")->name("custom-policy");
    Route::resource("policies", "backend\Payroll\PolicyController");
    Route::resource("leave-policies", "backend\Payroll\LeavePolicyController");
    Route::post("check-vacation", "backend\Payroll\LeavePolicyController@check_vacation")->name('check-vacation');
    Route::resource("weekend-holiday-policies", "backend\Payroll\WeekendHolidayController");
    Route::post('/weekend-default-store', 'backend\Payroll\WeekendHolidayController@weekend_default')->name('weekend-default.store');
    Route::get('/weekend-default-find', 'backend\Payroll\WeekendHolidayController@weekend_default_check')->name('weekend-default.check');
    Route::resource('salary-process', 'backend\Payroll\SalaryprocessController');
    Route::resource('employee-history', 'backend\Payroll\EmployeeHistoryController');
    Route::resource('employee-document', 'backend\Payroll\EmployeeDocumentController');
    Route::get("employee-salary-show", "backend\Payroll\SalaryprocessController@employee_salary_show")->name("employee-salary-show");
    Route::get('find-currency', 'backend\Payroll\EmployeeController@findCurrency')->name('find-currency');
    Route::get('employees-approve/{id}', 'backend\Payroll\EmployeeController@approve')->name('employees-approve');
    Route::resource('employee-attendance','backend\Payroll\EmployeeAttendence');
    Route::resource("employee-leave-application", "backend\Payroll\EmployeeLeaveApplicationController");
    Route::get("employee-leave-application-approve/{id}", "backend\Payroll\EmployeeLeaveApplicationController@approve")->name('employee-leave-application-approve');
    Route::get("employee-leave-application-delete/{id}", "backend\Payroll\EmployeeLeaveApplicationController@destroy")->name('employee-leave-application-delete');
    Route::get("employee-leave-application-reject/{id}", "backend\Payroll\EmployeeLeaveApplicationController@reject")->name('employee-leave-application-reject');

    Route::prefix('/hr/payroll')->group(function(){

        Route::get("new-employee-attendance", "backend\Payroll\EmployeeAttendence@new_employee_attendance")->name("new-employee-attendance");
        Route::get("employee-monthly-attendance", "backend\Payroll\EmployeeAttendence@employee_monthly_attendance")->name("employee-monthly-attendance");
        Route::get('/report', 'ClientReportController@hrReport')->name('hr.payroll.report');
        Route::resource("employees", "backend\Payroll\EmployeeController");
        Route::resource('division', 'backend\Payroll\DivisionController');
        Route::resource("notice-board", "backend\Payroll\NoticeBoardController");

        Route::resource('/reporting/authority','backend\Payroll\ReportingAuthorityController')->names('reporting.authority');
    });

    Route::post("employee-wise-attendance-show", "backend\Payroll\EmployeeAttendence@employee_attendance_show")->name("employee-wise-attendance-show");

    //**************************************************** payroll end **********************************************
    //**************************************************** payroll cnd **********************************************
    //****************************payroll*******************///
    //*********administration******///

    Route::prefix('/administration')->group(function(){
        Route::get('/report', 'ClientReportController@administrationReport')->name('administration.report');
        Route::resource('role', 'backend\RoleController');
        Route::resource('user','backend\UserController');
        Route::resource('settings','backend\SettingController');
    });

    Route::post('/addpermission.edite', 'AddPrmissionController@edit')->name('addpermission.edite');
    Route::post('/aditional-permission/update', 'AddPrmissionController@update')->name('aditional-permission.update');
    Route::post("role-edit-modal", "backend\RoleController@role_edit_modal")->name("role-edit-modal");
    Route::post("user-edit-modal", "backend\UserController@user_edit_modal")->name("user-edit-modal");
    Route::post("user-show-modal", "backend\UserController@user_show_modal")->name("user-show-modal");
    Route::post("setting-edit-modal", "backend\SettingController@setting_edit_modal")->name("setting-edit-modal");
    Route::get('pay-salary-preview', 'backend\Payroll\SalaryprocessController@pay_salary_preview')->name('pay-salary-preview');
    Route::post('search-project-task-list', 'backend\Payroll\SalaryprocessController@search_project_task_list')->name('search-project-task-list');
    //*********administration******///
    Route::prefix('/business')->group(function(){
        Route::get('/business-report', 'ClientReportController@business')->name('business-report');

    });
});

Route::get('/get-header', function () {
    return view('layouts.backend.partial.modal-header-info')->render();
});

Route::get('/get-footer', function () {
    return view('layouts.backend.partial.modal-footer-info')->render();
});

Route::get('project/adjust', 'backend\JobProjectController@adjustProject');

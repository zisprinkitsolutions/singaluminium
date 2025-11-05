<?php

use App\Module;
use App\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Dashboard
        $moduleAppDashboard = Module::updateOrCreate(['name' => 'Admin Dashboard']);
        Permission::updateOrCreate([
            'module_id' => $moduleAppDashboard->id,
            'name' => 'Access Dashboard',
            'slug' => 'dashboard',
        ]);

        // setup
        $setup = Module::updateOrCreate(['name' => 'Setup']);
        Permission::updateOrCreate([
            'module_id' => $setup->id,
            'name' => 'Chart of Accounts',
            'slug' => 'Chart_of_Accounts',
        ]);
        Permission::updateOrCreate([
            'module_id' => $setup->id,
            'name' => 'Stake Holder',
            'slug' => 'Stake_Holder',
        ]);
        Permission::updateOrCreate([
            'module_id' => $setup->id,
            'name' => 'Create',
            'slug' => 'Setup_Create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $setup->id,
            'name' => 'Edit',
            'slug' => 'Setup_Edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $setup->id,
            'name' => 'Delete',
            'slug' => 'Setup_Delete',
        ]);
        Permission::updateOrCreate([
            'module_id' => $setup->id,
            'name' => 'Authorize',
            'slug' => 'Setup_Authorize',
        ]);
        Permission::updateOrCreate([
            'module_id' => $setup->id,
            'name' => 'Approve',
            'slug' => 'Setup_Approve',
        ]);

        // Project Management
        $ProjectManagement = Module::updateOrCreate(['name' => 'Project Management']);
        Permission::updateOrCreate([
            'module_id' => $ProjectManagement->id,
            'name' => 'Project',
            'slug' => 'Project',
        ]);
        Permission::updateOrCreate([
            'module_id' => $ProjectManagement->id,
            'name' => 'Bill OF Quantity',
            'slug' => 'Bill_OF_Quantity',
        ]);
        Permission::updateOrCreate([
            'module_id' => $ProjectManagement->id,
            'name' => 'Quotation',
            'slug' => 'Quotation',
        ]);
        Permission::updateOrCreate([
            'module_id' => $ProjectManagement->id,
            'name' => 'Onboard',
            'slug' => 'Onboard',
        ]);
        Permission::updateOrCreate([
            'module_id' => $ProjectManagement->id,
            'name' => 'Gantt Chart',
            'slug' => 'Gantt_Chart',
        ]);
        Permission::updateOrCreate([
            'module_id' => $ProjectManagement->id,
            'name' => 'Project Task',
            'slug' => 'Project_Task',
        ]);
        Permission::updateOrCreate([
            'module_id' => $ProjectManagement->id,
            'name' => 'Create',
            'slug' => 'ProjectManagement_Create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $ProjectManagement->id,
            'name' => 'Edit',
            'slug' => 'ProjectManagement_Edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $ProjectManagement->id,
            'name' => 'Delete',
            'slug' => 'ProjectManagement_Delete',
        ]);
        Permission::updateOrCreate([
            'module_id' => $ProjectManagement->id,
            'name' => 'Authorize',
            'slug' => 'ProjectManagement_Authorize',
        ]);
        Permission::updateOrCreate([
            'module_id' => $ProjectManagement->id,
            'name' => 'Approve',
            'slug' => 'ProjectManagement_Approve',
        ]);


        // Expense
        $Expense = Module::updateOrCreate(['name' => 'Expense']);
        Permission::updateOrCreate([
            'module_id' => $Expense->id,
            'name' => 'Requisition',
            'slug' => 'Requisition',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Expense->id,
            'name' => 'LPO',
            'slug' => 'LPO',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Expense->id,
            'name' => 'Expense',
            'slug' => 'Expense',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Expense->id,
            'name' => 'Payment',
            'slug' => 'Payment',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Expense->id,
            'name' => 'Payable',
            'slug' => 'Payable',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Expense->id,
            'name' => 'Create',
            'slug' => 'Expense_Create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Expense->id,
            'name' => 'Edit',
            'slug' => 'Expense_Edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Expense->id,
            'name' => 'Delete',
            'slug' => 'Expense_Delete',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Expense->id,
            'name' => 'Authorize',
            'slug' => 'Expense_Authorize',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Expense->id,
            'name' => 'Approve',
            'slug' => 'Expense_Approve',
        ]);


        // Revenue
        $Revenue = Module::updateOrCreate(['name' => 'Revenue']);
        Permission::updateOrCreate([
            'module_id' => $Revenue->id,
            'name' => 'Invoice',
            'slug' => 'Invoice',
        ]);

        Permission::updateOrCreate([
            'module_id' => $Revenue->id,
            'name' => 'Receipt Voucher',
            'slug' => 'Receipt_Voucher',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Revenue->id,
            'name' => 'Revenue Analysis',
            'slug' => 'Revenue_Analysis',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Revenue->id,
            'name' => 'Create',
            'slug' => 'Revenue_Create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Revenue->id,
            'name' => 'Edit',
            'slug' => 'Revenue_Edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Revenue->id,
            'name' => 'Delete',
            'slug' => 'Revenue_Delete',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Revenue->id,
            'name' => 'Authorize',
            'slug' => 'Revenue_Authorize',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Revenue->id,
            'name' => 'Approve',
            'slug' => 'Revenue_Approve',
        ]);

        // Accounting
        $Accounting = Module::updateOrCreate(['name' => 'Accounting']);
        Permission::updateOrCreate([
            'module_id' => $Accounting->id,
            'name' => 'View',
            'slug' => 'View',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Accounting->id,
            'name' => 'Fund Allocation',
            'slug' => 'Fund_Allocation',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Accounting->id,
            'name' => 'Create',
            'slug' => 'Accounting_Create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Accounting->id,
            'name' => 'Edit',
            'slug' => 'Accounting_Edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Accounting->id,
            'name' => 'Delete',
            'slug' => 'Accounting_Delete',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Accounting->id,
            'name' => 'Authorize',
            'slug' => 'Accounting_Authorize',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Accounting->id,
            'name' => 'Approve',
            'slug' => 'Accounting_Approve',
        ]);

        // Reports
        $Reports = Module::updateOrCreate(['name' => 'Reports']);
        Permission::updateOrCreate([
            'module_id' => $Reports->id,
            'name' => 'Accounting Reports',
            'slug' => 'Accounting_Reports',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Reports->id,
            'name' => 'Account Receivable',
            'slug' => 'Account_Receivable',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Reports->id,
            'name' => 'Account Payable',
            'slug' => 'Account_Payable',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Reports->id,
            'name' => 'Petty Cash Report',
            'slug' => 'Petty_Cash_Report',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Reports->id,
            'name' => 'Daily Summary',
            'slug' => 'Daily_Summary',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Reports->id,
            'name' => 'Party Transactions',
            'slug' => 'Party_Transactions',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Reports->id,
            'name' => 'Stock Report',
            'slug' => 'Stock_Report',
        ]);

        // HR & Payroll
        $HRPAYROLl = Module::updateOrCreate(['name' => 'HR & Payroll']);
        Permission::updateOrCreate([
            'module_id' => $HRPAYROLl->id,
            'name' => 'Employee',
            'slug' => 'Employee',
        ]);
        Permission::updateOrCreate([
            'module_id' => $HRPAYROLl->id,
            'name' => 'Attendance',
            'slug' => 'Attendance',
        ]);
        Permission::updateOrCreate([
            'module_id' => $HRPAYROLl->id,
            'name' => 'Employee Leave',
            'slug' => 'Employee_Leave',
        ]);
        Permission::updateOrCreate([
            'module_id' => $HRPAYROLl->id,
            'name' => 'Salary',
            'slug' => 'Salary',
        ]);
        Permission::updateOrCreate([
            'module_id' => $HRPAYROLl->id,
            'name' => 'HR Setup',
            'slug' => 'HR_Setup',
        ]);
        Permission::updateOrCreate([
            'module_id' => $HRPAYROLl->id,
            'name' => 'Create',
            'slug' => 'HR_Create',
        ]);
        Permission::updateOrCreate([
            'module_id' => $HRPAYROLl->id,
            'name' => 'Edit',
            'slug' => 'HR_Edit',
        ]);
        Permission::updateOrCreate([
            'module_id' => $HRPAYROLl->id,
            'name' => 'Delete',
            'slug' => 'HRPAYROLl_Delete',
        ]);
        Permission::updateOrCreate([
            'module_id' => $HRPAYROLl->id,
            'name' => 'Authorize',
            'slug' => 'HR_Authorize',
        ]);
        Permission::updateOrCreate([
            'module_id' => $HRPAYROLl->id,
            'name' => 'Approve',
            'slug' => 'HR_Approve',
        ]);


        //  Administration
        $Administration = Module::updateOrCreate(['name' => 'Administration']);
        Permission::updateOrCreate([
            'module_id' => $Administration->id,
            'name' => 'Roles',
            'slug' => 'manage_profile',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Administration->id,
            'name' => 'User',
            'slug' => 'user',
        ]);
        Permission::updateOrCreate([
            'module_id' => $Administration->id,
            'name' => 'Settings',
            'slug' => 'settings',
        ]);
    }
}

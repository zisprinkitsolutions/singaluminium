@if (!isset($isMobile) || !$isMobile)
    <!-- Full Top Navigation Bar with Dropdowns and Search Form -->
    <style>
        .menu-item-top-header {
            padding: 8px 8px !important;
        }

        .menu-title {
            color: #fff !important;
        }

        .bx-check-shield {
            color: #fff !important;
        }

        @keyframes pulse {
                0% {
                transform: scale(1);
                }

                50% {
                transform: scale(1.05);
                }

                100% {
                transform: scale(1);
                }
                }

                @keyframes progress {
                0% {
                width: 0;
                }

                100% {
                width: 100%;
                }
                }

                body {
                text-align: center;
                font-family: Arial, sans-serif;
                }

                .download-btn {
                    position: relative;
                    display: inline-block;
                    padding: 6px 6px;
                    margin-top: 4px;
                    background:
                    linear-gradient(to right, #24d600, #09dc02);
                    color: rgb(255, 255, 255);
                    border: none;
                    border-radius: 5px;
                    font-size: 16px;
                    text-decoration: none;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    transition: background 0.3s ease;
                    overflow: hidden;
                    animation:
                    pulse 2s infinite alternate;
                }

                .download-btn:hover {
                background:
                linear-gradient(to right, #3ba800, #00a405);
                }

                .download-icon {
                margin-right: 5px;
                }

                .progress-bar {

                animation: progress 3s linear forwards;
                }

                .bg-menu{
                    background-color: #021631;
                }
    </style>

    <div class=" {{-- bg-dark --}} text-white print-hideen bg-menu" style="position: fixed; top: 0; width:100%; z-index: 99;">
        <div class="row">
            <div class="col-sm-2 col-md-2 col-lg-2 col-12 d-flex align-items-center">
                <!-- User Info -->
                <a href="{{ route('home') }}" title="{{$company_name->config_value}}" class="navbar-brand text-white d-flex align-items-center mr-4 pr-4"
                    style="font-size: 14px; margin-top: 4px; margin-left: 10px; width:250px">
                    <img src="{{ asset('assets/backend/app-assets/user.png') }}" alt="avatar" class="rounded-circle"
                        style="height: 30px;width: 30px;margin-right: 7px;">
                  {{ \Illuminate\Support\Str::words($company_name->config_value, 2, '...') }}
                </a>
            </div>

            <ul class="menu-top-header">
                <a href=""></a>
                <a href="" class="text-right"></a>
                <a href="" class="text-right"></a>
            </ul>

            <div class="pl-1 col-sm-10 col-md-10 col-lg-10 col-12">
                <nav class="nav-top-menu bg-menu">
                    <ul class="menu-top-header d-flex">
                        {{-- setup link --}}

                        @if (Auth::user()->hasPermission('Chart_of_Accounts') || Auth::user()->hasPermission('Stake_Holder'))
                            @if (Auth::user()->role_id != 68)
                                <a href="{{ route('setup.report') }}">
                                    <li
                                        class="menu-item-top-header dropdown
                                {{ request()->is('setup*') ||
                                request()->is('products*') ||
                                request()->is('product') ||
                                request()->is('sub-category') ||
                                request()->is('child-category') ||
                                request()->is('*office')
                                    ? 'menu-top-active'
                                    : ' ' }}">

                                        <span style="color: #fff !important">Setup</span>

                                        <ul class="dropdown-menu-top-header text-left">

                                            @if (Auth::user()->hasPermission('Chart_of_Accounts'))
                                                <a href="{{ route('new-chart-of-account') }}">
                                                    <li
                                                        class="{{ request()->is('setup/new-chart-of-account') || request()->is('*master-details/edit/*') || request()->is('setup/new-account-head') ? 'item-active' : '' }}">
                                                        <span class="">
                                                            <i class="bx bx-check-shield"></i>
                                                            <span class="menu-title text-truncate"
                                                                data-i18n="Cost Center"> Chart of Accounts </span>
                                                        </span>
                                                    </li>
                                                </a>
                                            @endif

                                            @if (Auth::user()->hasPermission('Stake_Holder'))
                                                <a href="{{ route('partyInfoDetails') }}">
                                                    <li
                                                        class=" {{ request()->is('*profit-details') ||
                                                        request()->is('*-center/edit*') ||
                                                        request()->is('*cost-center-details') ||
                                                        request()->is('*party-info*')
                                                            ? 'item-active'
                                                            : '' }}
                                                {{ request()->is('party-info') ||
                                                request()->is('*service-provider') ||
                                                request()->is('new-donar') ||
                                                request()->is('new-charity')
                                                    ? 'item-active'
                                                    : '' }}">
                                                        <span class="dropdown-item-top-header">
                                                            <i class="bx bx-check-shield"></i>
                                                            <span class="menu-title text-truncate"
                                                                data-i18n="Cost Center"> Stake Holder </span>
                                                        </span>
                                                    </li>
                                                </a>
                                            @endif
                                            @if (Auth::user()->hasPermission('Stake_Holder'))
                                                <a href="{{ route('subsidiary.index') }}">
                                                    <li
                                                        class=" {{ request()->is('*subsidiary') ? 'item-active' : '' }}">
                                                        <span class="dropdown-item-top-header">
                                                            <i class="bx bx-check-shield"></i>
                                                            <span class="menu-title text-truncate"
                                                                data-i18n="Cost Center"> Subsidiary </span>
                                                        </span>
                                                    </li>
                                                </a>
                                            @endif

                                            {{-- @if (Auth::user()->hasPermission('Stake_Holder'))
                                                <a href="{{ route('house.type.index') }}">
                                                    <li class=" {{ request()->is('*house/type*') ? 'item-active' : '' }}">
                                                        <span class="dropdown-item-top-header">
                                                            <i class="bx bx-check-shield"></i>
                                                            <span class="menu-title text-truncate"
                                                                data-i18n="Cost Center"> House Type </span>
                                                        </span>
                                                    </li>
                                                </a>
                                            @endif --}}
                                        </ul>
                                    </li>
                                </a>
                            @endif
                        @endif

                        @if (Auth::user()->hasPermission('Project') ||
                                Auth::user()->hasPermission('Bill_OF_Quantity') ||
                                Auth::user()->hasPermission('Onboard') ||
                                Auth::user()->hasPermission('Gantt_Chart') ||
                                Auth::user()->hasPermission('Project_Task'))
                            <a href="{{ route('jobporjects.report') }}">
                                <li
                                    class="menu-item-top-header dropdown {{ request()->is('*new-project*') ? 'menu-top-active' : '' }}">
                                    <span style="color: #fff !important"> Project Management </span>
                                    <ul class="dropdown-menu-top-header text-left">
                                        @if (Auth::user()->hasPermission('Onboard'))
                                            <a href="{{ route('projects.index') }}">
                                                <li
                                                    class=" {{ request()->is('project/projects') ? 'item-active' : '' }}">
                                                    <span class="dropdown-item-top-header">
                                                        <i class="bx bx-check-shield"></i>
                                                        <span class="menu-title text-truncate" data-i18n="Cost Center">
                                                            Projects </span>
                                                    </span>
                                                </li>
                                            </a>
                                        @endif



                                        {{-- @if (Auth::user()->hasPermission('Gantt_Chart'))
                                            <a href="{{ route('gnatt.chart.index') }}">
                                                <li class="{{ request()->is('gnatt/chart') ? 'item-active' : '' }}">
                                                    <span class="">
                                                        <i class="bx bx-check-shield"></i>
                                                        <span class="menu-title text-truncate" data-i18n="Cost Center">
                                                            Gantt Chart </span>
                                                    </span>
                                                </li>
                                            </a>
                                        @endif --}}

                                        {{-- @if (Auth::user()->hasPermission('Onboard')) --}}
                                        <a href="{{ route('cost.analysis') }}">
                                            <li
                                                class=" {{ request()->is('*project/cost/analysis') ? 'item-active' : '' }}">
                                                <span class="dropdown-item-top-header">
                                                    <i class="bx bx-check-shield"></i>
                                                    <span class="menu-title text-truncate" data-i18n="Cost Center">
                                                        Financial Analysis </span>
                                                </span>
                                            </li>
                                        </a>
                                        {{-- @endif --}}

                                        @if (Auth::user()->hasPermission('Project'))
                                            <a href="{{ route('new-project.index') }}">
                                                <li class="{{ request()->is('*new-project*') ? 'item-active' : '' }}">
                                                    <span class="">
                                                        <i class="bx bx-check-shield"></i>
                                                        <span class="menu-title text-truncate" data-i18n="Cost Center">
                                                            Prospects </span>
                                                    </span>
                                                </li>
                                            </a>
                                        @endif


                                        {{-- @if (Auth::user()->hasPermission('Gantt_Chart'))
                                        <a href="{{ route('gnatt.chart.index') }}">
                                            <li class="{{ request()->is('gnatt/chart') ? 'item-active' : '' }}">
                                                <span class="">
                                                    <i class="bx bx-check-shield"></i>
                                                    <span class="menu-title text-truncate" data-i18n="Cost Center"> Gantt Chart </span>
                                                </span>
                                            </li>
                                        </a>
                                        @endif --}}

                                        @if (Auth::user()->hasPermission('Bill_OF_Quantity'))
                                            <a href="{{ route('boq.index') }}">
                                                <li class="{{ request()->is('*boq*') ? 'item-active' : '' }}">
                                                    <span class="">
                                                        <i class="bx bx-check-shield"></i>
                                                        <span class="menu-title text-truncate" data-i18n="Cost Center">
                                                            Bill OF Quantity </span>
                                                    </span>
                                                </li>
                                            </a>
                                        @endif

                                        @if (Auth::user()->hasPermission('Quotation'))
                                            <a href="{{ route('lpo-projects.index') }}">
                                                <li
                                                    class=" {{ request()->is('*lpo-projects*') ? 'item-active' : '' }}">
                                                    <span class="dropdown-item-top-header">
                                                        <i class="bx bx-check-shield"></i>
                                                        <span class="menu-title text-truncate" data-i18n="Cost Center">
                                                            Quotation </span>
                                                    </span>
                                                </li>
                                            </a>
                                        @endif

                                        @if (Auth::user()->hasPermission('Project_Task'))
                                            <a href="{{ route('project.tasks.index') }}">
                                                <li class="{{ request()->is('gnatt/chart') ? 'item-active' : '' }}">
                                                    <span class="">
                                                        <i class="bx bx-check-shield"></i>
                                                        <span class="menu-title text-truncate" data-i18n="Cost Center">
                                                            Project Task </span>
                                                    </span>
                                                </li>
                                            </a>
                                        @endif

                                        {{-- @if (Auth::user()->hasPermission('Project_Task'))
                                            <a href="{{ route('boq.sample.list') }}">
                                                <li class="{{ request()->is('*boq/sample/list*') ? 'item-active' : '' }}">
                                                    <span class="">
                                                        <i class="bx bx-check-shield"></i>
                                                        <span class="menu-title text-truncate" data-i18n="Cost Center">
                                                            Boq Sample </span>
                                                    </span>
                                                </li>
                                            </a>
                                        @endif --}}

                                        {{--
                                    @if (Auth::user()->hasPermission('p_Invoice'))
                                        <a href="{{ route('project.invoice.index') }}">
                                            <li class=" {{ request()->is('*job/project*/invoice*') ? 'item-active' : '' }}">
                                                <span class="dropdown-item-top-header">
                                                    <i class="bx bx-check-shield"></i>
                                                    <span class="menu-title text-truncate" data-i18n="Cost Center">Invoice</span>
                                                </span>
                                            </li>
                                        </a>
                                    @endif --}}
                                        {{-- <a href="{{ route('projects.report') }}">
                                        <li class=" {{ request()->is('*jobprojects/*reports') ? 'item-active' : '' }}">
                                            <span class="dropdown-item-top-header">
                                                <i class="bx bx-check-shield"></i>
                                                <span class="menu-title text-truncate" data-i18n="Cost Center">Reports</span>
                                            </span>
                                        </li>
                                    </a> --}}
                                    </ul>
                                </li>
                            </a>
                        @endif

                        {{-- PRODUCT PACKING  --}}

                        @if (Auth::user()->hasPermission('Requisition') ||
                                Auth::user()->hasPermission('LPO') ||
                                Auth::user()->hasPermission('Expense') ||
                                Auth::user()->hasPermission('Payment') ||
                                Auth::user()->hasPermission('Payable'))
                            <a href="{{ route('purchase.report') }}">
                                <li
                                    class="menu-item-top-header dropdown {{ request()->is('purchase*') || request()->is('lpo-bill*') ? 'menu-top-active' : ' ' }}">
                                    <span style="color: #fff !important">Expenses</span>
                                    <ul class="dropdown-menu-top-header text-left" style="width: 210px !important;">
                                        <li>
                                            @if (Auth::user()->hasPermission('Requisition'))
                                                <a href="{{ route('requisitions.index') }}">
                                        <li class="{{ request()->is('*lpo-bill') ? 'item-active' : '' }}">
                                            <span class="dropdown-item-top-header">
                                                <i class="bx bx-check-shield"></i>
                                                <span class="menu-title text-truncate" data-i18n="Cost Center">
                                                    Requisition </span>
                                            </span>
                                        </li>

                            </a>
                        @endif
                        @if (Auth::user()->hasPermission('LPO'))
                            <a href="{{ route('lpo-bill-list') }}">
                                <li class="{{ request()->is('*lpo-bill') ? 'item-active' : '' }}">
                                    <span class="dropdown-item-top-header">
                                        <i class="bx bx-check-shield"></i>
                                        <span class="menu-title text-truncate" data-i18n="Cost Center">LPO</span>
                                    </span>
                                </li>
                            </a>
                        @endif

                        @if (Auth::user()->hasPermission('Expense'))
                            <a href="{{ route('purchase-expense') }}">
                                <li
                                    class="{{ request()->is('*purchase-expense') || request()->is('*purchase-approve') || request()->is('*purchase-authorize') ? 'item-active' : '' }}">
                                    <span class="dropdown-item-top-header">
                                        <i class="bx bx-check-shield"></i>
                                        <span class="menu-title text-truncate" data-i18n="Cost Center">Expense</span>
                                    </span>
                                </li>
                            </a>
                        @endif

                        {{-- <a href="{{route("expense-allocation.index")}}">
                                            <li class="{{ request()->is('*expense-allocation') || request()->is('*approve-allocation') || request()->is('*authorize-allocation') ? 'item-active' : '' }}">
                                                <span class="dropdown-item-top-header">
                                                    <i class="bx bx-check-shield"></i>
                                                    <span class="menu-title text-truncate" data-i18n="Cost Center">Expense Allocation</span>
                                                </span>
                                            </li>
                                        </a> --}}

                        @if (Auth::user()->hasPermission('Payment'))
                            <a href="{{ route('payment-voucher2') }}">
                                <li
                                    class="{{ request()->is('*payment-voucher2') || request()->is('*temp-payment-voucher-edit/*') || request()->is('*temp-payment-voucher-*') ? 'item-active' : '' }}">
                                    <span class="dropdown-item-top-header">
                                        <i class="bx bx-check-shield"></i>
                                        <span class="menu-title text-truncate" data-i18n="Cost Center">Payment</span>
                                    </span>
                                </li>
                            </a>
                        @endif

                        @if (Auth::user()->hasPermission('Payable'))
                            <a href="{{ route('payable') }}">
                                <li class="{{ request()->is('*payable') ? 'item-active' : '' }}">
                                    <span class="dropdown-item-top-header">
                                        <i class="bx bx-check-shield"></i>
                                        <span class="menu-title text-truncate" data-i18n="Cost Center">Payable</span>
                                    </span>
                                </li>
                            </a>
                        @endif
                        </li>
                    </ul>
                    </li>
                    </a>
@endif
{{-- Sales link --}}

@if (Auth::user()->hasPermission('Invoice') ||
        Auth::user()->hasPermission('Receipt_Voucher') ||
        Auth::user()->hasPermission('Revenue_Analysis'))
    <a href="{{ route('sales.report') }}">
        <li
            class="menu-item-top-header dropdown {{ request()->is('sales/s*') || request()->is('sales/s*') || request()->is('sales/report*') || request()->is('sales/proforma*') || request()->is('sales/all*') || request()->is('sales/transection*') || request()->is('receipt*') || request()->is('sales/receipt-voucher3') ? 'menu-top-active' : ' ' }}">

            <span style="color: #fff !important">Revenue</span>
            <ul class="dropdown-menu-top-header text-left">
                @if (Auth::user()->hasPermission('Invoice'))
                    <a href="{{ route('sale-list') }}">
                        <li
                            class="{{ request()->is('sales/sale-list') || request()->is('sales/sale-approve') || request()->is('sales/sale-authorize') ? 'item-active' : '' }}">
                            <span class="dropdown-item-top-header">
                                <i class="bx bx-check-shield"></i>
                                <span class="menu-title text-truncate" data-i18n="Cost Center"> Invoice </span>
                            </span>
                        </li>
                    </a>
                @endif

                {{-- @if (Auth::user()->hasPermission('invoice'))
                                        <a href="{{route("sale-list")}}">
                                            <li class="{{ request()->is('sales/transection') || request()->is('sales/sale-list') || request()->is('sales/sale-proforma-invoice-list') || request()->is('sales/sale-direct-invoice-list')
                                            || request()->is('sales/all-invoice-list') ? 'item-active' : '' }}">
                                                <span class="dropdown-item-top-header">
                                                    <i class="bx bx-check-shield"></i>
                                                    <span class="menu-title text-truncate" data-i18n="Cost Center"> List </span>
                                                </span>
                                            </li>
                                        </a>
                                    @endif --}}

                @if (Auth::user()->hasPermission('Receipt_Voucher'))
                    <a href="{{ route('receipt-voucher-list-show') }}">
                        <li
                            class="{{ request()->is('receipt/receipt-voucher-list-show') || request()->is('receipt/temp-receipt-voucher*') || request()->is('receipt/receipt-voucher-edit*') ? 'item-active' : '' }}">
                            <span class="dropdown-item-top-header">
                                <i class="bx bx-check-shield"></i>
                                <span class="menu-title text-truncate" data-i18n="Cost Center"> Receipt Voucher
                                </span>
                            </span>
                        </li>
                    </a>
                @endif

                {{-- @if (Auth::user()->hasPermission('Receipt_Voucher'))
                                        <a href="{{ route('receipt-voucher-list-show') }}">
                                            <li class="{{request()->is('receipt/receipt-voucher-list-show') ? 'item-active' : '' }}">
                                                <span class="dropdown-item-top-header">
                                                    <i class="bx bx-check-shield"></i>
                                                    <span class="menu-title text-truncate" data-i18n="Cost Center"> Receipt List</span>
                                                </span>
                                            </li>
                                        </a>
                                    @endif --}}

                @if (Auth::user()->hasPermission('Revenue_Analysis'))
                    <a href="{{ route('receivable') }}">
                        <li class="{{ request()->is('receipt/receivable') ? 'item-active' : '' }}">
                            <span class="dropdown-item-top-header">
                                <i class="bx bx-check-shield"></i>
                                <span class="menu-title text-truncate" data-i18n="Cost Center"> Revenue Analysis
                                </span>
                            </span>
                        </li>
                    </a>
                @endif
        </li>
        </ul>
        </li>
    </a>
@endif
{{-- accounting link --}}
@if (Auth::user()->hasPermission('View') ||
        Auth::user()->hasPermission('Fund_Allocation') ||
        Auth::user()->hasPermission('Accounting_Authorize') ||
        Auth::user()->hasPermission('Accounting_Approve') ||
        Auth::user()->hasPermission('Accounting_Create'))
    <a href="{{ route('accounting.report') }}">
        <li
            class="menu-item-top-header dropdown {{ request()->is('accounting*') || request()->is('fund-allocation*') ? 'menu-top-active' : ' ' }}">
            <span style="color: #fff !important">Accounting</span>
            <ul class="dropdown-menu-top-header text-left">
                <li>
                    @if (Auth::user()->hasPermission('view'))
                        <a href="{{ route('new-journal') }}">
                <li class="{{ request()->is('accounting/new-journal') ? 'item-active' : '' }}">
                    <span class="dropdown-item-top-header">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> View </span>
                    </span>
                </li>
    </a>
@endif
@if (Auth::user()->hasPermission('Accounting_Create'))
    <a href="{{ route('new-journal-creation') }}">
        <li
            class="{{ request()->is('accounting/new-journal-creation') || request()->is('accounting/journal-success/*') || request()->is('accounting/journal-edit/*') ? 'item-active' : '' }}">
            <span class="dropdown-item-top-header">
                <i class="bx bx-check-shield"></i>
                <span class="menu-title text-truncate" data-i18n="Cost Center"> Entry </span>
            </span>
        </li>
    </a>
@endif
@if (Auth::user()->hasPermission('Accounting_Authorize'))
    <a href="{{ route('journal-authorization-section') }}">
        <li class="{{ request()->is('accounting/journal-authorization-section') ? 'item-active' : '' }}">
            <span class="dropdown-item-top-header">
                <i class="bx bx-check-shield"></i>
                <span class="menu-title text-truncate" data-i18n="Cost Center"> Authorize</span>
            </span>
        </li>
    </a>
@endif
@if (Auth::user()->hasPermission('Accounting_Approve'))
    <a href="{{ route('journal-approval-section') }}">
        <li class="{{ request()->is('accounting/journal-approval-section') ? 'item-active' : '' }}">
            <span class="dropdown-item-top-header">
                <i class="bx bx-check-shield"></i>
                <span class="menu-title text-truncate" data-i18n="Cost Center">Approve</span>
            </span>
        </li>
    </a>
@endif
@if (Auth::user()->hasPermission('Fund_Allocation'))
    <a href="{{ route('fund-allocation.index') }}">
        <li class="{{ request()->is('fund-allocation*') ? 'item-active' : '' }}">
            <span class="">
                <i class="bx bx-check-shield"></i>
                <span class="menu-title text-truncate" data-i18n="Cost Center" style="font-size: 13px"> Fund
                    Allocation </span>
            </span>
        </li>
    </a>
@endif
</li>
</ul>
</li>
</a>
@endif
{{-- report menu  --}}
@if (Auth::user()->hasPermission('Account_Receivable') ||
        Auth::user()->hasPermission('Account_Payable') ||
        Auth::user()->hasPermission('Petty_Cash_Report') ||
        Auth::user()->hasPermission('Daily_Summary') ||
        Auth::user()->hasPermission('Party_Transactions') ||
        Auth::user()->hasPermission('Stock_Report'))
    <a href="{{ route('report') }}">
        <li
            class="menu-item-top-header dropdown {{ request()->is('reports*') || request()->is('daily-summary*') || request()->is('project/engineer/reports') ? 'menu-top-active' : ' ' }}">
            <span style="color: #fff !important">Reports</span>
            <ul class="dropdown-menu-top-header text-left">
                @if (Auth::user()->hasPermission('Accounting_Reports'))
                    <a href="{{ route('new-general-ledger') }}">
                        <li class="{{ request()->is('reports/accounting-report/*') ? 'item-active' : '' }}">
                            <span class="dropdown-item-top-header">
                                <i class="bx bx-check-shield"></i>
                                <span class="menu-title text-truncate" data-i18n="Cost Center"> Accounting Reports
                                </span>
                            </span>
                        </li>
                    </a>
                @endif

                {{-- @if (Auth::user()->hasPermission('Financial_Reports'))
                                        <a href="{{ route('balance-sheet') }}">
                                            <li class="{{ request()->is('reports/financial-report*') ? 'item-active' : '' }}">
                                                <span class="dropdown-item-top-header">
                                                    <i class="bx bx-check-shield"></i>
                                                    <span class="menu-title text-truncate" data-i18n="Cost Center"> Financial Reports </span>
                                                </span>
                                            </li>
                                        </a>
                                    @endif --}}
                @if (Auth::user()->hasPermission('Account_Receivable'))
                    <a href="{{ route('accounts.receivable', ['type' => 'receivable']) }}">
                        <li class="{{ request()->is('receivable*') ? 'item-active' : '' }}">
                            <span class="dropdown-item-top-header">
                                <i class="bx bx-check-shield"></i>
                                <span class="menu-title text-truncate" data-i18n="Cost Center"> Account Receivable
                                </span>
                            </span>
                        </li>
                    </a>
                @endif
                @if (Auth::user()->hasPermission('Account_Payable'))
                    <a href="{{ route('accounts.receivable', ['type' => 'payable']) }}">
                        <li class="{{ request()->is('payable*') ? 'item-active' : '' }}">
                            <span class="dropdown-item-top-header">
                                <i class="bx bx-check-shield"></i>
                                <span class="menu-title text-truncate" data-i18n="Cost Center"> Account Payable
                                </span>
                            </span>
                        </li>
                    </a>
                @endif
                @if (Auth::user()->hasPermission('Petty_Cash_Report'))
                    <a href="{{ route('petty-cash-report') }}">
                        <li class="{{ request()->is('petty-cash-report*') ? 'item-active' : '' }}">
                            <span class="dropdown-item-top-header">
                                <i class="bx bx-check-shield"></i>
                                <span class="menu-title text-truncate" data-i18n="Cost Center">Petty Cash
                                    Report</span>
                            </span>
                        </li>
                    </a>
                @endif
                @if (Auth::user()->hasPermission('Daily_Summary'))
                    <a href="{{ route('daily-summary.report') }}" style="font-size: 14px;">
                        <li class="{{ request()->is('daily-summary') ? 'item-active' : '' }}">
                            <span class="dropdown-item-top-header">
                                <i class="bx bx-check-shield"></i>
                                <span class="menu-title text-truncate" data-i18n="Cost Center">Daily Summary</span>
                            </span>
                        </li>
                    </a>
                @endif
                @if (Auth::user()->hasPermission('Party_Transactions'))
                    <a href="{{ route('party.index') }}" style="font-size: 14px;">
                        <li class="{{ request()->is('reports/party') ? 'item-active' : ' ' }}">
                            <span class="dropdown-item-top-header">
                                <i class="bx bx-check-shield"></i>
                                <span class="menu-title text-truncate" data-i18n="Cost Center">Party
                                    Transactions</span>
                            </span>
                        </li>
                    </a>
                @endif
                @if (Auth::user()->hasPermission('Stock_Report'))
                    <a href="{{ route('stock-report') }}" style="font-size: 14px;">
                        <li class="{{ request()->is('reports/stock-report') ? 'item-active' : ' ' }}">
                            <span class="dropdown-item-top-header">
                                <i class="bx bx-check-shield"></i>
                                <span class="menu-title text-truncate" data-i18n="Cost Center">Stock Report</span>
                            </span>
                        </li>
                    </a>
                @endif

                {{-- @if (Auth::user()->hasPermission('Daily_Summary')) --}}
                <a href="{{ route('engineer.reports.index') }}" style="font-size: 14px;">
                    <li class="{{ request()->is('project/engineer/reports*') ? 'item-active' : ' ' }}">
                        <span class="dropdown-item-top-header">
                            <i class="bx bx-check-shield"></i>
                            <span class="menu-title text-truncate" data-i18n="Cost Center">Work Rport</span>
                        </span>
                    </li>
                </a>
                {{-- @endif --}}

            </ul>
        </li>
    </a>
@endif
{{-- Administration --}}
@if (Auth::user()->hasPermission('Employee') ||
        Auth::user()->hasPermission('Attendance') ||
        Auth::user()->hasPermission('Employee_Leave') ||
        Auth::user()->hasPermission('Salary') ||
        Auth::user()->hasPermission('HR_Setup'))
    <a href="{{ route('hr.payroll.report') }}">
        <li
            class="menu-item-top-header dropdown {{ request()->is('hr/payroll/*') || request()->is('salary-process') || request()->is('players*') || request()->is('pay-salary*') || request()->is('employee-salary-show') ? 'menu-top-active' : ' ' }}">
            <span style="color: #fff !important">HR & PAYROLL</span>
            <ul class="dropdown-menu-top-header text-left">

                @if (Auth::user()->hasPermission('Employee'))
                    <a href="{{ route('employees.index') }}">
                        <li
                            class="{{ request()->is('hr//employees') || request()->is('*employees') ? 'item-active' : '' }}">
                            <span class="dropdown-item-top-header">
                                <i class="bx bx-check-shield"></i>
                                <span class="menu-title text-truncate" data-i18n="Cost Center"> Employee </span>
                            </span>
                        </li>
                    </a>
                @endif
                @if (Auth::user()->hasPermission('Attendance'))
                    <a href="{{ route('new-employee-attendance') }}">
                        <li
                            class="{{ request()->is('hr/*/new-employee-attendance') || request()->is('*new-employee-leave') ? 'item-active' : ' ' }}">
                            <span class="dropdown-item-top-header">
                                <i class="bx bx-check-shield"></i>
                                <span class="menu-title text-truncate" data-i18n="Cost Center"> Attendance </span>
                            </span>
                        </li>
                    </a>
                @endif
                @if (Auth::user()->hasPermission('Employee_Leave'))
                    <a href="{{ route('employee-leave-application.index') }}">
                        <li class="{{ request()->is('*new-employee-leave') ? 'item-active' : ' ' }}">
                            <span class="dropdown-item-top-header">
                                <i class="bx bx-check-shield"></i>
                                <span class="menu-title text-truncate" data-i18n="Cost Center"> Employee Leave </span>
                            </span>
                        </li>
                    </a>
                @endif

                @if (Auth::user()->hasPermission('Salary'))
                    <a href="{{ route('employee-salary-show') }}">
                        <li class="{{ request()->is('employee-salary-show') ? 'item-active' : '' }}">
                            <span class="dropdown-item-top-header">
                                <i class="bx bx-check-shield"></i>
                                <span class="menu-title text-truncate" data-i18n="Cost Center"> Salary </span>
                            </span>
                        </li>
                    </a>
                @endif

                <a href="{{ route('reporting.authority.index') }}">
                    <li
                        class="{{ request()->is('*authority*') || request()->is('*department') || request()->is('*salary-types') || request()->is('*nationality') ? 'item-active' : '' }}">
                        <span class="dropdown-item-top-header">
                            <i class="bx bx-check-shield"></i>
                            <span class="menu-title text-truncate" data-i18n="Cost Center"> Reporting Authority
                            </span>
                        </span>
                    </li>
                </a>

                @if (Auth::user()->hasPermission('HR_Setup'))
                    <a href="{{ route('division.index') }}">
                        <li
                            class="{{ request()->is('*division') || request()->is('*department') || request()->is('*salary-types') || request()->is('*nationality') || request()->is('*branch') || request()->is('grade') || request()->is('employee-salary') || request()->is('salary-structures') || request()->is('employee-history') || request()->is('employee-document') ? 'item-active' : '' }}">
                            <span class="dropdown-item-top-header">
                                <i class="bx bx-check-shield"></i>
                                <span class="menu-title text-truncate" data-i18n="Cost Center"> HR & PAYROLL </span>
                            </span>
                        </li>
                    </a>
                @endif
            </ul>
        </li>
    </a>
@endif
@if (Auth::user()->hasPermission('user') ||
        Auth::user()->hasPermission('settings') ||
        Auth::user()->hasPermission('manage_profile'))
    <a href="{{ route('administration.report') }}">
        <li class="menu-item-top-header dropdown {{ request()->is('administration/*') ? 'menu-top-active' : ' ' }}">
            <span style="color: #fff !important">ADMINISTRATION</span>
            <ul class="dropdown-menu-top-header text-left">
                <a href="{{ route('role.index') }}">
                    <li class="{{ request()->is('*/role') ? 'item-active' : '' }}">
                        <span class="dropdown-item-top-header">
                            <i class="bx bx-check-shield"></i>
                            <span class="menu-title text-truncate" data-i18n="Cost Center"> Roles </span>
                        </span>
                    </li>
                </a>
                @if (Auth::user()->hasPermission('user'))
                    <a href="{{ route('user.index') }}">
                        <li class="{{ request()->is('*/user') ? 'item-active' : '' }}">
                            <span class="dropdown-item-top-header">
                                <i class="bx bx-check-shield"></i>
                                <span class="menu-title text-truncate" data-i18n="Cost Center"> Users </span>
                            </span>
                        </li>
                    </a>
                @endif

                @if (Auth::user()->hasPermission('settings'))
                    <a href="{{ route('settings.index') }}">
                        <li class="{{ request()->is('*/settings') ? 'item-active' : '' }}">
                            <span class="dropdown-item-top-header">
                                <i class="bx bx-check-shield"></i>
                                <span class="menu-title text-truncate" data-i18n="Cost Center"> Settings </span>
                            </span>
                        </li>
                    </a>
                @endif
            </ul>
        </li>
    </a>
@endif





<div class="dropdown" style="margin-top: 6px">
    <a href="#" id="notificationBell" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        🔔 <span id="notifCount" class="badge badge-danger">{{auth()->user()->unreadNotifications->count()}}</span>
    </a>

    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationBell" id="notifList" style="width:300px; max-height:400px; overflow-y:auto;">
        <h6 class="dropdown-header">Notifications</h6>
        <div class="dropdown-divider"></div>
        <div id="notifItems">
            @foreach (auth()->user()->unreadNotifications as $item)
                <li><a class="notice-view" href="{{route('requisitions.show',$item)}}">{{$item->message}}</a></li>
            @endforeach
        </div>
    </div>
</div>

<audio id="notifSound" src="{{ asset('sounds/notify.mp3') }}" preload="auto"></audio>



<a href="{{asset('/')}}/app-seabridge.apk" class="download-btn" download="" title="Download The App Form Here.">
    <i class="fas fa-download download-icon"></i>
    Download App
    <div class="progress-bar"></div>
</a>

<a onclick="if(confirm('Are you sure you want to log out?')) { event.preventDefault(); document.getElementById('logout-form').submit(); }"
    style="margin-left: auto !important;">
    <li class="menu-item-top-header">
        <span>
            <span class="logout-button">

                <i class="bx bx-log-out-circle"></i>
            </span>
        </span>
    </li>
</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
</ul>
</nav>
</div>
</div>
</div>
@endif

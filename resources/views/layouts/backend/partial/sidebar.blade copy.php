<style>
    .menu-item-top-header{
        padding: 8px 8px !important;
    }
    .menu-title{
        color: #fff !important;
    }
    .bx-check-shield{
        color: #fff !important;
    }
</style>
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow print-hidden" data-scroll-to-active="true">
    <!--<div class="navbar-header">-->
    <!--    <ul class="nav navbar-nav flex-row">-->
    <!--        <li class="nav-item mr-auto text-center"><a class="navbar-brand" href="{{url('/')}}">-->

    <!--              <img src="{{ asset('img/singh-bg.png') }}"  style="width: 120px; height:30px" alt="">-->
    <!--            </a></li>-->
    <!--    </ul>-->


    <!--</div>-->
    <div class="navbar-header2">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item">
                <a class="navbar-brand" href="{{ route('home')}}">
                       <div class="row">
                        <div class="col-8">
                            <p style="font-size: 15px !important">{{ Auth::user()->name}} <br></span><span class="user-status text-muted">Available</span></p>
                        </div>
                        <div class="col-4">
                            <span><img class="round" src="{{ asset('assets/backend')}}/app-assets/user.png" alt="avatar" height="30" width="30"></span>
                        </div>
                       </div>

                </a>
            </li>
        </ul>


    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="">
            @if (Auth::user()->hasPermission('Chart_of_Accounts') ||
                Auth::user()->hasPermission('Stake_Holder'))
                <li class="dropdown" id="sidebar-dropdown">
                    <a href="{{ route('setup.report') }}" class="btn w-100 dropdown-toggle text-left
                        {{ (request()->is('setup*')) ? 'active' : ' ' }}">
                        Setup
                    </a>
                    <div id="dropdown-menu" class="{{ (request()->is('setup*')) ?  'show' : 'd-none'  }}">

                        @if (Auth::user()->hasPermission('Chart_of_Accounts'))
                        <a class="dropdown-item {{ request()->is('setup/new-chart-of-account') || request()->is('*master-details/edit/*') || request()->is('setup/new-account-head') ? 'active' : '' }}" href="{{ route('new-chart-of-account') }}">
                            <i class="bx bx-check-shield"></i>
                            <span class="menu-title text-truncate" data-i18n="Cost Center"> Chart of Accounts </span>
                        </a>
                        @endif
                        @if (Auth::user()->hasPermission('Stake_Holder'))
                        <a class="dropdown-item
                        {{(request()->is('*profit-details')) || request()->is('*-center/edit*') || request()->is('*cost-center-details') || request()->is('*party-info*') ? 'active' : '' }}
                        {{(request()->is('party-info')) || request()->is('*service-provider') || request()->is('new-donar') || request()->is('new-charity') ? 'active' : '' }}"
                        href="{{ route('partyInfoDetails') }}">
                            <i class="bx bx-check-shield"></i>
                            <span class="menu-title text-truncate" data-i18n="Cost Center"> Stake Holder </span>
                        </a>
                        @endif
                    </div>
                </li>
            @endif
            @if (Auth::user()->hasPermission('Quotation') ||
            Auth::user()->hasPermission('p_Invoice') ||
            Auth::user()->hasPermission('Work_Order'))
            <li class="dropdown" id="sidebar-dropdown">
                <a href="{{ route('jobporjects.report') }}" class="btn w-100 dropdown-toggle text-left
                    {{ (request()->is('project*')) ? 'active' : ' ' }}">
                    Project
                </a>

                <div id="dropdown-menu" class="{{ request()->is('project/*') ? 'show' : 'd-none' }}">
                    <a class="dropdown-item {{ request()->is('*new-project*') ? 'active' : '' }}" href="{{ route('lpo-projects.index') }}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Project </span>
                    </a>
                    @if (Auth::user()->hasPermission('Quotation'))
                     <a class="dropdown-item {{ request()->is('*lpo-projects*') ? 'active' : '' }}" href="{{ route('lpo-projects.index') }}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Quotation </span>
                    </a>
                    @endif
                    @if (Auth::user()->hasPermission('Work_Order'))

                    <a class="dropdown-item {{ request()->is('project/projects')  ? 'active' : '' }}" href="{{ route('projects.index') }}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Work Order  </span>
                    </a>
                    @endif
                    @if (Auth::user()->hasPermission('p_Invoice'))
                    <a class="dropdown-item {{ request()->is('*job/project*/invoice*') ? 'active' : '' }}"
                    href="{{ route('project.invoice.index') }}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Invoice </span>
                    </a>
                    @endif

                    <a class="dropdown-item {{ request()->is('*jobprojects/*reports') ? 'active' : '' }}"
                        href="{{ route('projects.report') }}">
                            <i class="bx bx-check-shield"></i>
                            <span class="menu-title text-truncate" data-i18n="Cost Center"> Reports </span>
                        </a>
                </div>
            </li>
            @endif

            <li class="dropdown" id="sidebar-dropdown">
                <a href="{{ route('lpo-bill-report') }}" class="btn w-100 dropdown-toggle text-left
                    {{ (request()->is('lpo-bill*')) ? 'active' : ' ' }}">
                    Lpo
                </a>
                <div id="dropdown-menu" class="{{ (request()->is('lpo-bill*')) ?  'show' : 'd-none'  }}">
                    <a class="dropdown-item {{ request()->is('*lpo-bill-create') ? 'active' : '' }}" href="{{route("lpo-bill-create")}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> LPO </span>
                    </a>
                </div>
                <div id="dropdown-menu" class="{{ (request()->is('lpo-bill*')) ?  'show' : 'd-none'  }}">
                    <a class="dropdown-item {{ request()->is('*lpo-bill-list') ? 'active' : '' }}" href="{{route("lpo-bill-list")}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> List </span>
                    </a>
                </div>
            </li>


            @if (Auth::user()->hasPermission('Bill') ||
            Auth::user()->hasPermission('Payment_Voucher') ||
            Auth::user()->hasPermission('Payment_List')||
            Auth::user()->hasPermission('p_List'))
            <li class="dropdown" id="sidebar-dropdown">
                <a href="{{ route('purchase.report') }}" class="btn w-100 dropdown-toggle text-left
                    {{ (request()->is('purchase*')) ? 'active' : ' ' }}">
                    Purchase
                </a>
                <div id="dropdown-menu" class="{{ (request()->is('purchase*')) ?  'show' : 'd-none'  }}">
                    @if (Auth::user()->hasPermission('Bill'))
                    <a class="dropdown-item {{ request()->is('*purchase-expense') || request()->is('*purchase-approve') || request()->is('*purchase-authorize') ? 'active' : '' }}" href="{{route("purchase-expense")}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Bill </span>
                    </a>
                    @endif
                    @if (Auth::user()->hasPermission('p_List'))
                    <a class="dropdown-item {{ request()->is('*purchase-expense-list') ? 'active' : '' }}" href="{{route("purchase-expense-list")}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> List </span>
                    </a>
                    @endif
                    @if (Auth::user()->hasPermission('Payment_Voucher'))
                    <a class="dropdown-item {{ request()->is('*payment-voucher2') || request()->is('*temp-payment-voucher-edit/*')|| request()->is('*temp-payment-voucher-*') ? 'active' : '' }}" href="{{route("payment-voucher2")}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Payment Voucher </span>
                    </a>
                    @endif
                    @if (Auth::user()->hasPermission('Payment_List'))
                    <a class="dropdown-item {{ request()->is('*payment-voucher2-list') ? 'active' : '' }}" href="{{route("payment-voucher2-list")}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Payment List</span>
                    </a>
                    @endif
                    @if (Auth::user()->hasPermission('p_Payable'))
                    <a class="dropdown-item {{ request()->is('*payable') ? 'active' : '' }}" href="{{route("payable")}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center">Payable</span>
                    </a>
                    @endif
                </div>
            </li>
            @endif
            @if (Auth::user()->hasPermission('Invoice') || Auth::user()->hasPermission('List'))
            <li class="dropdown" id="sidebar-dropdown">
                <a href="{{ route('sales.report') }}" class="btn w-100 dropdown-toggle text-left
                    {{ (request()->is('sales/s*')) || (request()->is('sales/s*'))|| (request()->is('sales/report*')) || (request()->is('sales/proforma*')) || (request()->is('sales/all*')) || (request()->is('sales/transection*')) ? 'active' : ' ' }}">
                    Sales
                </a>
                <div id="dropdown-menu" class="{{ (request()->is('sales/s*')) || (request()->is('sales/s*'))|| (request()->is('sales/report*')) || (request()->is('sales/proforma*')) || (request()->is('sales/all*')) || (request()->is('sales/transection*')) ?  'show' : 'd-none'  }}">

                    @if (Auth::user()->hasPermission('Invoice'))
                    <a class="dropdown-item {{ request()->is('sales/saleIssue') ||  request()->is('sales/sale-approve') ||  request()->is('sales/sale-authorize') ? 'active' : '' }}" href="{{route("saleIssue")}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Invoice </span>
                    </a>
                    @endif
                    @if (Auth::user()->hasPermission('List'))
                    <a class="dropdown-item {{request()->is('sales/transection') || request()->is('sales/sale-list') || request()->is('sales/sale-proforma-invoice-list') || request()->is('sales/sale-direct-invoice-list') || request()->is('sales/all-invoice-list') ? 'active' : '' }}" href="{{route("sale-list")}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> List </span>
                    </a>
                    @endif
                </div>
            </li>
            @endif
            @if (Auth::user()->hasPermission('Receipt_Voucher')||
            Auth::user()->hasPermission('Receivable')||
            Auth::user()->hasPermission('Receipt_List'))
            <li class="dropdown" id="sidebar-dropdown">
                <a href="{{ route('receipt.report') }}" class="btn w-100 dropdown-toggle text-left
                    {{ (request()->is('receipt*')) || (request()->is('sales/receipt-voucher3')) ? 'active' : ' ' }}">
                    Receipt
                </a>
                <div id="dropdown-menu" class="{{ (request()->is('receipt*'))|| (request()->is('sales/receipt-voucher3'))  ?  'show' : 'd-none'  }}">

                    @if (Auth::user()->hasPermission('Receipt_Voucher'))
                    <a class="dropdown-item {{ request()->is('sales/receipt-voucher3') || request()->is('receipt/temp-receipt-voucher*') || request()->is('receipt/receipt-voucher-edit*')  ? 'active' : '' }}" href="{{route("receipt-voucher3")}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Receipt Voucher </span>
                    </a>
                    @endif
                    @if (Auth::user()->hasPermission('Receipt_List'))
                    <a class="dropdown-item {{ request()->is('receipt/receipt-voucher-list-show') ? 'active' : '' }}" href="{{route("receipt-voucher-list-show")}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Receipt List</span>
                    </a>
                    @endif
                    @if (Auth::user()->hasPermission('Receivable'))
                    <a class="dropdown-item {{ request()->is('receipt/receivable') ? 'active' : '' }}" href="{{route("receivable")}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Receivable</span>
                    </a>
                    @endif
                </div>
            </li>
            @endif

            @if (Auth::user()->hasPermission('View') ||
            Auth::user()->hasPermission('Entry')||Auth::user()->hasPermission('Authorize') ||Auth::user()->hasPermission('Approve'))
             <li class="dropdown" id="sidebar-dropdown">
                <a href="{{ route('accounting.report') }}" class="btn w-100 dropdown-toggle text-left
                    {{ (request()->is('accounting*')) ? 'active' : ' ' }}">
                    Accounting
                </a>
                <div id="dropdown-menu" class="{{ (request()->is('accounting*')) ?  'show' : 'd-none'  }}">
                    @if (Auth::user()->hasPermission('View'))
                    <a class="dropdown-item {{ request()->is('accounting/new-journal')   ? 'active' : '' }}" href="{{route("new-journal")}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> View </span>
                    </a>
                    @endif
                    @if (Auth::user()->hasPermission('Entry'))
                    <a class="dropdown-item {{ request()->is('accounting/new-journal-creation') || request()->is('accounting/journal-success/*') || request()->is('accounting/journal-edit/*') ? 'active' : '' }}" href="{{route('new-journal-creation')}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Entry </span>
                    </a>
                    @endif
                    @if (Auth::user()->hasPermission('Authorize'))
                    <a class="dropdown-item {{ request()->is('accounting/journal-authorization-section') ? 'active' : '' }}" href="{{route("journal-authorization-section")}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Authorize </span>
                    </a>
                    @endif
                    @if (Auth::user()->hasPermission('Approve'))
                    <a class="dropdown-item {{ request()->is('accounting/journal-approval-section') ? 'active' : '' }}" href="{{route("journal-approval-section")}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Approve </span>
                    </a>
                   @endif
                   <a class="dropdown-item {{ request()->is('accounting/fund-allocation*') ? 'active' : '' }}" href="{{route('fund-allocation.index')}}">
                        <i class="bx bx-check-shield"></i><span class="menu-title text-truncate" data-i18n="Roles">Fund Allocation</span>
                    </a>
                </div>
            </li>
            @endif
            @if (Auth::user()->hasPermission('Accounting_Reports') ||
            Auth::user()->hasPermission('Financial_Reports') )
            <li class="dropdown" id="sidebar-dropdown">
                <a href="{{ route('report') }}" class="btn w-100 dropdown-toggle text-left
                    {{ (request()->is('reports*')) ? 'active' : ' ' }}">
                    Reports
                </a>
                <div id="dropdown-menu" class="{{ (request()->is('reports*') || request()->is('daily-summary*'))  ?  'show' : 'd-none'  }}">
                    @if (Auth::user()->hasPermission('Accounting_Reports'))
                    <a class="dropdown-item {{ request()->is('reports/accounting-report/*')   ? 'active' : '' }}" href="{{route("new-general-ledger")}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Accounting Reports </span>
                    </a>
                    @endif
                    @if (Auth::user()->hasPermission('Financial_Reports'))

                    <a class="dropdown-item {{ request()->is('reports/financial-report*') ? 'active' : '' }}" href="{{route('balance-sheet')}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Financial Reports </span>
                    </a>
                    @endif
                    <a class="dropdown-item {{ request()->is('receivable*') ? 'active' : '' }}" href="{{route('accounts.receivable',['type' => 'receivable'])}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Account Receivable </span>
                    </a>
                    <a class="dropdown-item {{ request()->is('daily-summary*') ? 'active' : '' }}" href="{{route('accounts.receivable',['type' => 'payable'])}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Account Payable </span>
                    </a>
                    <a class="dropdown-item {{ request()->is('daily-summary*') ? 'active' : '' }}" href="{{route('daily-summary.report')}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Daily Summary </span>
                    </a>
                    <a class="dropdown-item {{ request()->is('reports/petty-cash-report*') ? 'active' : '' }}" href="{{route('petty-cash-report')}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Cost Center"> Petty Cash Report </span>
                    </a>
                    @if (Auth::user()->hasPermission('Party_Transections'))
                        <a class="dropdown-item  {{ request()->is('reports/party') ? 'active' : ' ' }}"
                            href="{{ route('party.index')}} ">
                            <i class="bx bx-check-shield"></i>
                            <span class="menu-title text-truncate" data-i18n="Employee Profile"> Party Transactions </span>
                        </a>
                    @endif
                    
                </div>
            </li>
            @endif
            @if (Auth::user()->hasPermission('basic_info') ||
            Auth::user()->hasPermission('employee_attendance')||Auth::user()->hasPermission('salary_procedure'))

            <li class="dropdown" id="sidebar-dropdown">
                <a href="{{ route('hr.payroll.report') }}" class="btn w-100  text-left dropdown-toggle
                    {{ (request()->is('hr/payroll/*')) || request()->is('salary-process') || request()->is('players*') || request()->is('pay-salary*') || request()->is('employee-salary-show')? 'active' : ' ' }}">
                    HR & PAYROLL
                </a>

                <div id="dropdown-menu" class="{{ (request()->is('hr/payroll/*')) || (request()->is('players*')) || request()->is('salary-process') || request()->is('pay-salary*') || request()->is('employee-salary-show') ? 'show' : 'd-none' }}">
                    {{-- @if (Auth::user()->hasPermission('salary_procedure'))


                <a class=" dropdown-item {{ request()->is('*division')
                        || request()->is('*department') || request()->is('*salary-types')
                        || request()->is('*nationality') || request()->is('*branch')
                        || request()->is('grade') || request()->is('employee-salary')
                        || request()->is('salary-structures') || request()->is('employee-history')
                        || request()->is('employee-document')? 'active':'' }}"
                        href="{{ route('grade-wise-salary-components.index')}} ">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Employee Profile"> HR Setup </span>
                    </a>
                    @endif --}}
                    @if ( Auth::user()->hasPermission('basic_info'))
                    <a class="dropdown-item {{(request()->is('hr//employees')) || (request()->is('*employees'))  ? 'active':'' }}"
                        href="{{ route('employees.index')}} ">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Employee Profile"> Employee Profile </span>
                    </a>
                    @endif
                    {{-- @if (Auth::user()->hasPermission('player'))

                    <a class="dropdown-item {{ (request()->is('players*'))  ? 'active':'' }}"
                        href="{{ route('players.index')}} ">
                    <i class="bx bx-check-shield"></i>
                    <span class="menu-title text-truncate" data-i18n="Employee Profile"> Player Profile </span>
                </a>
                @endif --}}

                    @if(Auth::user()->hasPermission('employee_attendance'))
                    <a class=" dropdown-item
                        {{ (request()->is('hr/*/new-employee-attendance')) || (request()->is('*new-employee-leave')) ? 'active' : ' ' }}"
                        href="{{ route('new-employee-attendance')}}">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Employee Profile"> Employees Attendance </span>
                    </a>
                    @endif
                    {{-- @if (Auth::user()->hasPermission('payroll_process'))

                    <a class=" dropdown-item
                        {{(request()->is('hr/*/deduction-entry')) || request()->is('*salary-process')
                        || request()->is('*pay-salary') || request()->is('salary-structures') ? 'active':'' }}"
                        href="{{ route('loan-advance-emi.index')}} ">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Employee Profile"> Payroll  </span>
                    </a>
                    @endif --}}
                    <a class=" dropdown-item
                        {{ request()->is('employee-salary-show') ? 'active':'' }}"
                        href="{{ route('employee-salary-show')}} ">
                        <i class="bx bx-check-shield"></i>
                        <span class="menu-title text-truncate" data-i18n="Employee Profile">Employee Salary info  </span>
                    </a>

                </div>
            </li>
            @endif

            @if (Auth::user()->hasPermission(' manage_profile') ||
                Auth::user()->hasPermission('user') ||
                Auth::user()->hasPermission('settings'))
                <li class="dropdown" id="sidebar-dropdown">
                    <a href="{{ route('administration.report') }}"class="btn w-100 dropdown-toggle text-left{{ request()->is('administration/*') ? 'active' : ' ' }}">
                        ADMINISTRATION
                    </a>

                    <div id="dropdown-menu" class="{{ request()->is('administration/*') ? 'show' : 'd-none' }}">
                        @if (Auth::user()->hasPermission(manage_profile'))
                            <a class="dropdown-item {{ request()->is('*/role') ? 'active' : '' }}"
                                href="{{ route('role.index') }}">
                                <i class="bx bx-user-plus"></i><span class="menu-title text-truncate"
                                    data-i18n="Roles">Roles</span>
                            </a>
                        @endif
                        @if (Auth::user()->hasPermission('user'))
                            <a class="dropdown-item {{ request()->is('*/user') ? 'active' : '' }}"
                                href="{{ route('user.index') }}">
                                <i class="bx bx-user-plus"></i><span class="menu-title text-truncate"
                                    data-i18n="Users">Users</span>
                            </a>
                        @endif
                        @if (Auth::user()->hasPermission('settings'))
                            <a class="dropdown-item {{ request()->is('*/settings') ? 'active' : '' }}"
                                href="{{ route('settings.index') }}">
                                <i class="bx bx-user-plus"></i><span class="menu-title text-truncate"
                                    data-i18n="Users">Settings</span>
                            </a>
                        @endif
                    </div>
                </li>
            @endif
      <li class="dropdown" id="sidebar-dropdown">
        <a href="{{ route('requirement-list') }}"class="btn w-100 dropdown-toggle text-left {{ request()->is('requirement-list') || request()->is('moduls-list')  ? 'active' : ' ' }}">
            PROGRAM UPDATE         </a>

        <div id="dropdown-menu" class="{{ request()->is('requirement-list') ||  request()->is('moduls-list')  ? 'show' : 'd-none' }}">
                <a class="dropdown-item {{ request()->is('requirement-list') ? 'active' : '' }}"
                    href="{{ route('requirement-list') }}">
                    <i class="bx bx-user-plus"></i><span class="menu-title text-truncate"
                        data-i18n="Roles">UPDATE LIST </span>
                </a>
                <a class="dropdown-item {{ request()->is('moduls-list') ? 'active' : '' }}"
                    href="{{ route('moduls-list') }}">
                    <i class="bx bx-user-plus"></i><span class="menu-title text-truncate"
                        data-i18n="Users">Modules</span>
                </a>

        </div>
    </li>
            <li class="dropdown mt-1" id="sidebar-dropdown ">
                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"><i class="bx bx-log-out-circle"></i><span class="menu-title text-truncate" data-i18n="Logout">Logout</span></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>


        </ul>
    </div>
</div>

<script>

</script>

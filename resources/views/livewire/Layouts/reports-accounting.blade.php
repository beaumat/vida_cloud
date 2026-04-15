<?php
use App\Services\UserServices;
?>
<li id="accounting" class="nav-item {{ request()->is('reports/accounting*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('reports/accounting*') ? 'active font-weight-bold' : '' }}">
        <i class="fa fa-file-text-o nav-icon"></i>
        <p> Accounting <i class="right fas fa-angle-left"></i> </p>
    </a>
    <ul class="nav nav-treeview">

        @if (UserServices::GetUserRightAccess('report.accounting.general-ledger'))
            <li class="nav-item">
                <a href="{{ route('reportsaccountinggeneral_ledeger_report') }}"
                    class="nav-link {{ request()->is('reports/accounting/general-ledger*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print  nav-icon"></i>
                    <p>General Ledger</p>
                </a>
            </li>
        @endif

        @if (UserServices::GetUserRightAccess('report.accounting.trial-balance'))
            <li class="nav-item">
                <a href="{{ route('reportsaccountingtrial_balance_report') }}"
                    class="nav-link {{ request()->is('reports/accounting/trial-balance*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print  nav-icon"></i>
                    <p>Trial Balance</p>
                </a>
            </li>
        @endif

        @if (UserServices::GetUserRightAccess('report.accounting.transaction-journal'))
            <li class="nav-item">
                <a href="{{ route('reportsaccountingtransaction_journal_report') }}"
                    class="nav-link {{ request()->is('reports/accounting/transaction-journal*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print  nav-icon"></i>
                    <p>Transaction Journal</p>
                </a>
            </li>
        @endif

        @if (UserServices::GetUserRightAccess('report.accounting.transaction-details'))
            <li class="nav-item">
                <a href="{{ route('reportsaccountingtransaction_details_report') }}"
                    class="nav-link {{ request()->is('reports/accounting/account-transaction*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print  nav-icon"></i>
                    <p>Account Transaction</p>
                </a>
            </li>
        @endif

    </ul>
</li>

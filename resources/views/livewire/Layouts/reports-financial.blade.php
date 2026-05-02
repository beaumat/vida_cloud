<?php
use App\Services\UserServices;
?>
<li id="financial" class="nav-item {{ request()->is('reports/financial*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('reports/financial*') ? 'active' : '' }}">
        <i class="fa fa-file-text-o  nav-icon"></i>
        <p>
            Financial
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        @if (UserServices::GetUserRightAccess('report.financial.income-statement'))
            <li class="nav-item ">
                <a href="{{ route('reportsfinancialincome_statement_report') }}"
                    class="nav-link {{ request()->is('reports/financial/income-statement*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print nav-icon"></i>
                    <p>Profit and Loss</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('report.financial.balance-sheet'))
            <li class="nav-item">
                <a href="{{ route('reportsfinancialbalance_sheet_report') }}"
                    class="nav-link {{ request()->is('reports/financial/balance-sheet*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print nav-icon"></i>
                    <p>Balance Sheet</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('report.financial.equity'))
            <li class="nav-item">
                <a href="{{ route('reportsfinancialequity_report') }}"
                    class="nav-link {{ request()->is('reports/financial/equity*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print nav-icon"></i>
                    <p>Movements in Equity</p>
                </a>
            </li>
        @endif

        @if (UserServices::GetUserRightAccess('report.financial.cash-flow'))
            <li class="nav-item">
                <a href="{{ route('reportsfinancialcash_flow_report') }}"
                    class="nav-link {{ request()->is('reports/financial/cash-flow*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print nav-icon"></i>
                    <p>Cash Flow</p>
                </a>
            </li>
        @endif

         {{-- @if (UserServices::GetUserRightAccess('report.pettycash.petty-cash'))
            <li class="nav-item">
                <a href="{{ route('reportsfinancialpetty_cash_report') }}"
                    class="nav-link {{ request()->is('reports/pettycash/petty-cash-report*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-print nav-icon"></i>
                    <p>Petty Cash</p>
                </a>
            </li>
        @endif --}}
    </ul>
</li>

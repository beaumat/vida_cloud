<?php
use App\Services\UserServices;
?>
<li class="nav-item {{ request()->is('banking*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('banking*') ? 'active ' : '' }}">
        <i class="nav-icon fa fa-university"></i>
        <p> Banking <i class="fas fa-angle-left right"></i> </p>
    </a>
    <ul class="nav nav-treeview bg-blue-dark">
        @if (UserServices::GetUserRightAccess('banking.deposit.view'))
            <li class="nav-item">
                <a href="{{ route('bankingdeposit') }}"
                    class="nav-link  {{ request()->is('banking/deposit*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-balance-scale nav-icon"></i>
                    <p>Deposit</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('banking.fund-transfer.view'))
            <li class="nav-item">
                <a href="{{ route('bankingfund_transfer') }}"
                    class="nav-link  {{ request()->is('banking/fund-transfer*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-exchange nav-icon"></i>
                    <p>Fund Transfer</p>
                </a>
            </li>
        @endif

        {{-- @if (UserServices::GetUserRightAccess('banking.bank-transfer.view'))
            <li class="nav-item">
                <a href="{{ route('bankingbank_transfer') }}"
                    class="nav-link  {{ request()->is('banking/bank-transfer*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-stack-exchange nav-icon"></i>
                    <p>Bank Transfer</p>
                </a>
            </li>
        @endif --}}


        @if (UserServices::GetUserRightAccess('banking.make-cheque.view'))
            <li class="nav-item">
                <a href="{{ route('bankingmake_cheque') }}"
                    class="nav-link  {{ request()->is('banking/make-cheque*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="far fa-credit-card nav-icon"></i>
                    <p>Pay by Check</p>
                </a>
            </li>
        @endif

        @if (UserServices::GetUserRightAccess('banking.bank-recon.view'))
            <li class="nav-item">
                <a href="{{ route('bankingbank_recon') }}"
                    class="nav-link  {{ request()->is('banking/bank-recon*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-pencil-square-o nav-icon"></i>
                    <p>Reconciliation</p>
                </a>
            </li>
        @endif

        @if (UserServices::GetUserRightAccess('banking.bank-statement.view'))
            <li class="nav-item">
                <a href="{{ route('bankingbank_statement') }}"
                    class="nav-link  {{ request()->is('banking/bank-statement*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-pencil-square nav-icon"></i>
                    <p>Bank Statement</p>
                </a>
            </li>
        @endif
        {{--
        @if (UserServices::GetUserRightAccess('banking.spend-money.view'))
            <li class="nav-item">
                <a href="{{ route('bankingspend_money') }}"
                    class="nav-link  {{ request()->is('banking/spend-money*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-money nav-icon"></i>
                    <p>Spend Money</p>
                </a>
            </li>
        @endif


        @if (UserServices::GetUserRightAccess('banking.receive-money.view'))
            <li class="nav-item">
                <a href="{{ route('bankingreceive_money') }}"
                    class="nav-link  {{ request()->is('banking/receive-money*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-money nav-icon"></i>
                    <p>Receive Money</p>
                </a>
            </li>
        @endif --}}
    </ul>
</li>

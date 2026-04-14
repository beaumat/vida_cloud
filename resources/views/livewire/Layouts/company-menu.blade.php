<?php
use App\Services\UserServices;
use App\Services\ModeServices;
?>
<li class="nav-item {{ request()->is('company*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('company*') ? 'active ' : '' }}">
        <i class="nav-icon fa fa-building"></i>
        <p> Company <i class="fas fa-angle-left right"></i> </p>
    </a>
    <ul class="nav nav-treeview bg-blue-dark">
        @if (UserServices::GetUserRightAccess('company.stock-transfer.view'))
            <li class="nav-item">
                <a href="{{ route('companystock_transfer') }}"
                    class="nav-link {{ request()->is('company/stock-transfer*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-truck nav-icon"></i>
                    <p>Stock Transfer</p>
                </a>
            </li>
        @endif



        @if (UserServices::GetUserRightAccess('company.inventory-adjustment.view'))
            <li class="nav-item">
                <a href="{{ route('companyinventory_adjustment') }}"
                    class="nav-link {{ request()->is('company/inventory-adjustment*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-adjust nav-icon"></i>
                    <p>Inventory Adjustment</p>
                </a>
            </li>
        @endif

        @if (UserServices::GetUserRightAccess('company.build-assembly.view'))
            <li class="nav-item">
                <a href="{{ route('companybuild_assembly') }}"
                    class="nav-link {{ request()->is('company/build-assembly*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-cube nav-icon"></i>
                    <p>Build Assembly</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('company.general-journal.view'))
            <li class="nav-item">
                <a href="{{ route('companygeneral_journal') }}"
                    class="nav-link  {{ request()->is('company/general-journal*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-table nav-icon"></i>
                    <p>General Journal <i>(Manual)</i></p>
                </a>
            </li>
        @endif

        @if (ModeServices::GET() == 'H')
            {{-- Hospital --}}
            @if (UserServices::GetUserRightAccess('company.pull-out.view'))
                <li class="nav-item">
                    <a href="{{ route('companypull_out') }}"
                        class="nav-link {{ request()->is('company/pull-out*') ? 'text-warning font-weight-bold' : '' }}">
                        <i class="fa fa-undo nav-icon"></i>
                        <p>Pull Out</p>
                    </a>
                </li>
            @endif
            @if (UserServices::GetUserRightAccess('company.depreciation.view'))
                <li class="nav-item">
                    <a href="{{ route('companydepreciation') }}"
                        class="nav-link  {{ request()->is('company/depreciation*') ? 'text-warning font-weight-bold' : '' }}">
                        <i class="fas fa-archive nav-icon" aria-hidden="true"></i>
                        <p>Depreciation</p>
                    </a>
                </li>
            @endif
            @if (UserServices::GetUserRightAccess('company.cost-adjustment.view'))
                <li class="nav-item">
                    <a href="{{ route('companycost_adjustment') }}"
                        class="nav-link  {{ request()->is('company/cost-adjustment*') ? 'text-warning font-weight-bold' : '' }}">
                        <i class="fas fa-archive nav-icon" aria-hidden="true"></i>
                        <p>Cost Adjustment</p>
                    </a>
                </li>
            @endif
            @if (UserServices::GetUserRightAccess('company.stock-received'))
                <li class="nav-item">
                    <a href="{{ route('companystock_received') }}"
                        class="nav-link {{ request()->is('company/stock-received*') ? 'text-warning font-weight-bold' : '' }}">
                        <i class="fa fa-archive nav-icon"></i>
                        <p>Stock Received</p>
                    </a>
                </li>
            @endif
        @endif
    </ul>
</li>

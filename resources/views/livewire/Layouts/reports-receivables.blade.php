        <?php
        use App\Services\UserServices;
        ?>
        <li id="receivables" class="nav-item {{ request()->is('reports/receivables*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('reports/receivables*') ? 'active' : '' }}">
                <i class="fa fa-file-text-o nav-icon"></i>
                <p>
                    Receivables
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                @if (UserServices::GetUserRightAccess('report.receivables.ar-aging'))
                    <li class="nav-item">
                        <a href="{{ route('reportsar_aging') }}"
                            class="nav-link {{ request()->is('reports/receivables/ar-aging') ? 'text-warning font-weight-bold' : '' }}">
                            <i class="fa fa-print nav-icon"></i>
                            <p>AR Aging</p>
                        </a>
                    </li>
                @endif
                @if (UserServices::GetUserRightAccess('report.receivables.customer-balance'))
                    <li class="nav-item">
                        <a href="{{ route('reportscustomer_balance') }}"
                            class="nav-link {{ request()->is('reports/receivables/customer-balance') ? 'text-warning font-weight-bold' : '' }}">
                            <i class="fa fa-print nav-icon"></i>
                            <p>Invoice Balance</p>
                        </a>
                    </li>
                @endif
            </ul>
        </li>

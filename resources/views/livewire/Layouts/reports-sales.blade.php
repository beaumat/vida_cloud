        <?php
        use App\Services\UserServices;
        ?>
        <li id="Customer" class="nav-item {{ request()->is('reports/customer*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is('reports/customer*') ? 'active' : '' }}">
                <i class="fa fa-file-text-o nav-icon"></i>
                <p>
                    Customers
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                @if (UserServices::GetUserRightAccess('report.customer.sales'))
                    <li class="nav-item">
                        <a href="{{ route('reportscustomer_sales_report') }}"
                            class="nav-link {{ request()->is('reports/customer/sales') ? 'text-warning font-weight-bold' : '' }}">
                            <i class="fa fa-print nav-icon"></i>
                            <p>Sales</p>
                        </a>
                    </li>
                @endif
            </ul>
        </li>

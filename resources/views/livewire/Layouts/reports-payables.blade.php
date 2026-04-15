         <?php
         use App\Services\UserServices;
         ?>
         <li id="payables" class="nav-item  {{ request()->is('reports/payables*') ? 'menu-open' : '' }}">
             <a href="#" class="nav-link {{ request()->is('reports/payables*') ? 'active' : '' }}">
                 <i class="fa fa-file-text-o nav-icon"></i>
                 <p> Payables <i class="right fas fa-angle-left"></i> </p>
             </a>
             <ul class="nav nav-treeview">
                 @if (UserServices::GetUserRightAccess('report.payables.ap-aging'))
                     <li class="nav-item">
                         <a href="{{ route('reportsap_aging') }}"
                             class="nav-link {{ request()->is('reports/payables/ap-aging') ? 'text-warning font-weight-bold' : '' }}">
                             <i class="fa fa-print  nav-icon"></i>
                             <p>AP Aging</p>
                         </a>
                     </li>
                 @endif
                 @if (UserServices::GetUserRightAccess('report.payables.vendor-balance'))
                     <li class="nav-item">
                         <a href="{{ route('reportsvendor_balance') }}"
                             class="nav-link {{ request()->is('reports/payables/vendor-balance') ? 'text-warning font-weight-bold' : '' }}">
                             <i class="fa fa-print nav-icon"></i>
                             <p>Billing Balance</p>
                         </a>
                     </li>
                 @endif
             </ul>
         </li>

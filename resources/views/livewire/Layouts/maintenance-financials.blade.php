     <?php
     use App\Services\UserServices;
     ?>
     <li id="financial" class="nav-item {{ request()->is('maintenance/financial*') ? 'menu-open' : '' }}">
         <a href="#" class="nav-link {{ request()->is('maintenance/financial*') ? 'active' : '' }}">
             <i class="fa fa-pie-chart nav-icon"></i>
             <p>
                 Financial
                 <i class="right fas fa-angle-left"></i>
             </p>
         </a>
         <ul class="nav nav-treeview">
             @if (UserServices::GetUserRightAccess('chart-of-account.view'))
                 <li class="nav-item">
                     <a href="{{ route('maintenancefinancialcoa') }}"
                         class="nav-link {{ request()->is('maintenance/financial/chart-of-account*') ? 'text-warning font-weight-bold' : '' }}">
                         <i class="fa fa-file nav-icon"></i>
                         <p>Chart Of Account</p>
                     </a>
                 </li>
             @endif
             @if (UserServices::GetUserRightAccess('payment-method.view'))
                 <li class="nav-item">
                     <a href="{{ route('maintenancefinancialpayment_method') }}"
                         class="nav-link {{ request()->is('maintenance/financial/payment-method*') ? 'text-warning font-weight-bold' : '' }}">
                         <i class="fa fa-file nav-icon"></i>
                         <p>Payment Methods</p>
                     </a>
                 </li>
             @endif
             @if (UserServices::GetUserRightAccess('payment-term.view'))
                 <li class="nav-item">
                     <a href="{{ route('maintenancefinancialpayment_term') }}"
                         class="nav-link {{ request()->is('maintenance/financial/payment-term*') ? 'text-warning font-weight-bold' : '' }}">
                         <i class="fa fa-file nav-icon"></i>
                         <p>Payment Terms</p>
                     </a>
                 </li>
             @endif
             @if (UserServices::GetUserRightAccess('tax-list.view'))
                 <li class="nav-item">
                     <a href="{{ route('maintenancefinancialtax_list') }}"
                         class="nav-link {{ request()->is('maintenance/financial/tax-list*') ? 'text-warning font-weight-bold' : '' }}">
                         <i class="fa fa-file nav-icon"></i>
                         <p>Tax List</p>
                     </a>
                 </li>
             @endif


         </ul>
     </li>

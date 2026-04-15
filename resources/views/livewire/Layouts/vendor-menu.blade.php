     <?php
     use App\Services\UserServices;
     ?>
     <li class="nav-item {{ request()->is('vendors*') ? 'menu-open' : '' }}">
         <a href="#" class="nav-link {{ request()->is('vendors*') ? 'active ' : '' }}">
             <i class="nav-icon fas fa-user-tie"></i>
             <p> Vendors <i class="right fas fa-angle-left"></i> </p>
         </a>
         <ul class="nav nav-treeview bg-blue-dark">
             @if (UserServices::GetUserRightAccess('vendor.purchase-order.view'))
                 <li class="nav-item">
                     <a href="{{ route('vendorspurchase_order') }}"
                         class="nav-link {{ request()->is('vendors/purchase-order*') ? 'text-warning font-weight-bold' : '' }}">
                         <i class="fas fa-shopping-cart nav-icon"></i>
                         <p>Purchase Order</p>
                     </a>
                 </li>
             @endif
             @if (UserServices::GetUserRightAccess('vendor.bill.view'))
                 <li class="nav-item">
                     <a href="{{ route('vendorsbills') }}"
                         class="nav-link {{ request()->is('vendors/bills*') ? 'text-warning font-weight-bold' : '' }}">
                         <i class="fas fa-file-invoice nav-icon"></i>
                         <p>Bills</p>
                     </a>
                 </li>
             @endif
             @if (UserServices::GetUserRightAccess('vendor.bill-payment.view'))
                 <li class="nav-item">
                     <a href="{{ route('vendorsbill_payment') }}"
                         class="nav-link {{ request()->is('vendors/bill-payments*') ? 'text-warning font-weight-bold' : '' }}">
                         <i class="fas fa-money-bill nav-icon"></i>
                         <p>Pay Bills</p>
                     </a>
                 </li>
             @endif
             @if (UserServices::GetUserRightAccess('vendor.bill-credit.view'))
                 <li class="nav-item">
                     <a href="{{ route('vendorsbill_credit') }}"
                         class="nav-link {{ request()->is('vendors/bill-credits*') ? 'text-warning font-weight-bold' : '' }}">
                         <i class="fas fa-credit-card nav-icon"></i>
                         <p>Bill Credits</p>
                     </a>
                 </li>
             @endif

             @if (UserServices::GetUserRightAccess('vendor.withholding-tax.view'))
                 <li class="nav-item">
                     <a href="{{ route('vendorswithholding_tax') }}"
                         class="nav-link {{ request()->is('vendors/withholding-tax*') ? 'text-warning font-weight-bold' : '' }}">
                         <i class="fas fa-balance-scale nav-icon"></i>
                         <p>Withholding Tax</p>
                     </a>
             @endif
         </ul>
     </li>

         <li id="inventory" class="nav-item  {{ request()->is('reports/inventory*') ? 'menu-open' : '' }}">
             <a href="#" class="nav-link {{ request()->is('reports/inventory*') ? 'active' : '' }}">
                 <i class="fa fa-file-text-o nav-icon"></i>
                 <p> Inventory <i class="right fas fa-angle-left"></i> </p>
             </a>
             <ul class="nav nav-treeview">
                 @can('report.inventory.validation-summary')
                     <li class="nav-item">
                         <a href="{{ route('reportsvalidation_summry') }}"
                             class="nav-link {{ request()->is('reports/inventory/validation-summary') ? 'text-warning font-weight-bold' : '' }}">
                             <i class="fa fa-print  nav-icon"></i>
                             <p>Validation</p>
                         </a>
                     </li>
                 @endcan

                 <li class="nav-item">
                     <a href="{{ route('reportsinventory_usage_report') }}"
                         class="nav-link {{ request()->is('reports/inventory/usage') ? 'text-warning font-weight-bold' : '' }}">
                         <i class="fa fa-print  nav-icon"></i>
                         <p>Usage Report</p>
                     </a>
                 </li>
             </ul>
         </li>

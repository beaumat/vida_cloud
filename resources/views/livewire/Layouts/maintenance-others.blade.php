       <?php
       use App\Services\UserServices;
       ?>
       <li id="others" class="nav-item {{ request()->is('maintenance/others*') ? 'menu-open' : '' }}">
           <a href="#" class="nav-link {{ request()->is('maintenance/others*') ? 'active' : '' }}">
               <i class="fa fa-list-alt nav-icon" aria-hidden="true"></i>
               <p> Others <i class="right fas fa-angle-left"></i> </p>
           </a>
           <ul class="nav nav-treeview">
               @if (UserServices::GetUserRightAccess('others.shift.view'))
                   <li class="nav-item">
                       <a href="{{ route('maintenanceothersshift') }}"
                           class="nav-link {{ request()->is('maintenance/others/shift*') ? 'text-warning font-weight-bold' : '' }}">
                           <i class="fa fa-file nav-icon"></i>
                           <p>Shift</p>
                       </a>
                   </li>
               @endif
               @if (UserServices::GetUserRightAccess('others.hemodialysis-machine.view'))
                   <li class="nav-item">
                       <a href="{{ route('maintenanceothershemo_machine') }}"
                           class="nav-link {{ request()->is('maintenance/others/hemodialysis-machine*') ? 'text-warning font-weight-bold' : '' }}">
                           <i class="fa fa-sort-numeric-asc nav-icon"></i>
                           <p>Hemodialysis Machine</p>
                       </a>
                   </li>
               @endif
               @if (UserServices::GetUserRightAccess('others.requirement.view'))
                   <li class="nav-item">
                       <a href="{{ route('maintenanceothersrequirement') }}"
                           class="nav-link {{ request()->is('maintenance/others/requirement*') ? 'text-warning font-weight-bold' : '' }}">
                           <i class="fa fa-sort-numeric-asc nav-icon"></i>
                           <p>Rquirements</p>
                       </a>
                   </li>
               @endif
               @if (UserServices::GetUserRightAccess('others.item-active-list.view'))
                   <li class="nav-item">
                       <a href="{{ route('maintenanceothersitem-active-list') }}"
                           class="nav-link {{ request()->is('maintenance/others/item-active-list*') ? 'text-warning font-weight-bold' : '' }}">
                           <i class="fa fa-sort-numeric-asc nav-icon"></i>
                           <p>Item Inventory</p>
                       </a>
                   </li>
               @endif
               @if (UserServices::GetUserRightAccess('others.item-treatment.view'))
                   <li class="nav-item">
                       <a href="{{ route('maintenanceothersitem_treatment') }}"
                           class="nav-link {{ request()->is('maintenance/others/item-treatment*') ? 'text-warning font-weight-bold' : '' }}">
                           <i class="fa fa-sort-numeric-asc nav-icon"></i>
                           <p>Item Treatment</p>
                       </a>
                   </li>
               @endif
           </ul>
       </li>

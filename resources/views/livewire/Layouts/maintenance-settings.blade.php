<?php
use App\Services\UserServices;
?>

<li id="settings" class="nav-item {{ request()->is('maintenance/settings*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('maintenance/settings*') ? 'active' : '' }}">
        <i class="fa fa-wrench nav-icon"></i>
        <p> Settings <i class="right fas fa-angle-left"></i> </p>
    </a>
    <ul class="nav nav-treeview">
        @if (UserServices::GetUserRightAccess('users'))
            <li class="nav-item">
                <a href="{{ route('maintenancesettingsusers') }}"
                    class="nav-link {{ request()->is('maintenance/settings/user*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-user nav-icon"></i>
                    <p>Users</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('roles-and-permission'))
            <li class="nav-item">
                <a href="{{ route('maintenancesettingsroles') }}"
                    class="nav-link {{ request()->is('maintenance/settings/rolespermission*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-hand-o-right nav-icon"></i>
                    <p>Roles & Permission</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('location.view'))
            <li class="nav-item">
                <a href="{{ route('maintenancesettingslocation') }}"
                    class="nav-link {{ request()->is('maintenance/settings/location*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-map-marker nav-icon"></i>
                    <p>Location</p>
                </a>
            </li>
        @endif
        @if(UserServices::GetUserRightAccess('location-group.view'))
            <li class="nav-item">
                <a href="{{ route('maintenancesettingslocation_group') }}"
                    class="nav-link {{ request()->is('maintenance/settings/location-group*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-location-arrow nav-icon"></i>
                    <p>Location Group</p>
                </a>
            </li>
        @endif
        @if(UserServices::GetUserRightAccess('option'))
            <li class="nav-item">
                <a href="{{ route('maintenancesettingsoption') }}"
                    class="nav-link {{ request()->is('maintenance/settings/option*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-wrench nav-icon"></i>
                    <p>Options</p>
                </a>
            </li>
        @endif
{{-- 
        @if(UserServices::GetUserRightAccess('option'))
            <li class="nav-item">
                <a href="{{ route('maintenancesettingsimport') }}"
                    class="nav-link {{ request()->is('maintenance/settings/import*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-upload nav-icon"></i>

                    <p>Import</p>
                </a>
            </li>
        @endif --}}
    </ul>
</li>

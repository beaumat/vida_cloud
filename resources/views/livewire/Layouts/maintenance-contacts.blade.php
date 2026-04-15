<?php
use App\Services\UserServices;
use App\Services\ModeServices;
?>
<li id="contacts" class="nav-item {{ request()->is('maintenance/contact*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('maintenance/contact*') ? 'active' : '' }}">
        <i class="fa fa-address-book nav-icon"></i>
        <p> Contacts <i class="right fas fa-angle-left"></i> </p>
    </a>
    <ul class="nav nav-treeview">
        @if (UserServices::GetUserRightAccess('contact.customer.view'))
            <li class="nav-item">
                <a href="{{ route('maintenancecontactcustomer') }}"
                    class="nav-link {{ request()->is('maintenance/contact/customer*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-users nav-icon"></i>
                    <p>Customer</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('contact.vendor.view'))
            <li class="nav-item">
                <a href="{{ route('maintenancecontactvendor') }}"
                    class="nav-link {{ request()->is('maintenance/contact/vendor*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-users nav-icon"></i>
                    <p>Vendor</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('contact.employee.view'))
            <li class="nav-item">
                <a href="{{ route('maintenancecontactemployees') }}"
                    class="nav-link {{ request()->is('maintenance/contact/employees*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-users nav-icon"></i>
                    <p>Employees</p>
                </a>
            </li>
        @endif
        @if (ModeServices::GET() == 'H')
            {{-- Hospital --}}
            @if (UserServices::GetUserRightAccess('contact.patient.view'))
                <li class="nav-item">
                    <a href="{{ route('maintenancecontactpatients') }}"
                        class="nav-link {{ request()->is('maintenance/contact/patients*') ? 'text-warning font-weight-bold' : '' }}">
                        <i class="fa fa-users nav-icon"></i>
                        <p>Patients</p>
                    </a>
                </li>
            @endif
            @if (UserServices::GetUserRightAccess('contact.doctor.view'))
                <li class="nav-item">
                    <a href="{{ route('maintenancecontactdoctors') }}"
                        class="nav-link {{ request()->is('maintenance/contact/doctors*') ? 'text-warning font-weight-bold' : '' }}">
                        <i class="fa fa-users nav-icon"></i>
                        <p>Doctors</p>
                    </a>
                </li>
            @endif
        @endif
    </ul>
</li>

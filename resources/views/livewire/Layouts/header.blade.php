<?php
use App\Services\UserServices;
use App\Services\ModeServices;
?>
<nav class="main-header navbar navbar-expand navbar-dark text-sm">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
        </li>
        @if (UserServices::GetUserRightAccess('contact.customer.view') ||
                UserServices::GetUserRightAccess('contact.vendor.view') ||
                UserServices::GetUserRightAccess('contact.employee.view') ||
                UserServices::GetUserRightAccess('contact.patient.view') ||
                UserServices::GetUserRightAccess('contact.doctor.view'))
            <li class="nav-item dropdown">
                <a id="dropdownSubMenu2" href="#" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false" class="nav-link dropdown-toggle">Contacts</a>
                <ul aria-labelledby="dropdownSubMenu2" class="border-0 shadow dropdown-menu">
                    @if (ModeServices::GET() == 'H')
                        @if (UserServices::GetUserRightAccess('contact.patient.view'))
                            <li><a href="{{ route('maintenancecontactpatients') }}" class="dropdown-item">Patients </a>
                            </li>
                        @endif
                        @if (UserServices::GetUserRightAccess('contact.doctor.view'))
                            <li><a href="{{ route('maintenancecontactdoctors') }}" class="dropdown-item"> Doctors</a>
                            </li>
                        @endif
                    @endif
                    @if (UserServices::GetUserRightAccess('contact.customer.view'))
                        <li><a href="{{ route('maintenancecontactcustomer') }}" class="dropdown-item">Customers</a></li>
                    @endif
                    @if (UserServices::GetUserRightAccess('contact.vendor.view'))
                        <li><a href="{{ route('maintenancecontactvendor') }}" class="dropdown-item">Vendors</a></li>
                    @endif
                    @if (UserServices::GetUserRightAccess('contact.employee.view'))
                        <li><a href="{{ route('maintenancecontactemployees') }}" class="dropdown-item"> Employees</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('items.view') ||
                UserServices::GetUserRightAccess('others.item-active-list.view') ||
                UserServices::GetUserRightAccess('price-location'))
            <li class="nav-item dropdown">
                <a id="dropdownSubMenu3" href="#" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false" class="nav-link dropdown-toggle">Files</a>
                <ul aria-labelledby="dropdownSubMenu3" class="border-0 shadow dropdown-menu ">
                    @if (UserServices::GetUserRightAccess('items.view'))
                        <li>
                            <a href="{{ route('maintenanceinventoryitem') }}" class="dropdown-item">
                                Item
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('maintenanceinventoryfixed_asset_item') }}" class="dropdown-item">
                                Fixed Asset Item
                            </a>
                        </li>
                    @endif

                    @if (UserServices::GetUserRightAccess('others.item-active-list.view'))
                        <li>
                            <a href="{{ route('maintenanceothersitem-active-list') }}" class="dropdown-item">
                                Item Inventory
                            </a>
                        </li>
                    @endif

                    @if (UserServices::GetUserRightAccess('price-location'))
                        <li>
                            <a href="{{ route('maintenanceinventoryprice_location') }}" class="dropdown-item">
                                Price Adjust by Location
                            </a>
                        </li>
                    @endif
                    
                </ul>
            </li>
        @endif




    </ul>

    <!-- Right navbar links -->
    <ul class="ml-auto navbar-nav">
        <!-- Navbar Search -->
        {{-- <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search"
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li> --}}

        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            {{-- <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">3</span>
            </a> --}}
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                {{-- <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="{{ asset('dist/img/user1-128x128.jpg') }}" alt="User Avatar"
                            class="mr-3 img-size-50 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Brad Diesel
                                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">Call me whenever you can...</p>
                            <p class="text-sm text-muted"><i class="mr-1 far fa-clock"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="{{ asset('dist/img/user8-128x128.jpg') }}" alt="User Avatar"
                            class="mr-3 img-size-50 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                John Pierce
                                <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">I got your message bro</p>
                            <p class="text-sm text-muted"><i class="mr-1 far fa-clock"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="{{ asset('dist/img/user3-128x128.jpg') }}" alt="User Avatar"
                            class="mr-3 img-size-50 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Nora Silvester
                                <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">The subject goes here</p>
                            <p class="text-sm text-muted"><i class="mr-1 far fa-clock"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a> --}}
            </div>
        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            {{-- <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a> --}}
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                {{-- <a href="#" class="dropdown-item">
                    <i class="mr-2 fas fa-envelope"></i> 4 new messages
                    <span class="float-right text-sm text-muted">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="mr-2 fas fa-users"></i> 8 friend requests
                    <span class="float-right text-sm text-muted">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="mr-2 fas fa-file"></i> 3 new reports
                    <span class="float-right text-sm text-muted">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a> --}}
            </div>
        </li>
        <li class="nav-item dropdown ">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                class="nav-link dropdown-toggle text-sm"><i class="fa fa-user-circle" aria-hidden="true"></i>
                {{ auth()->user()->name }}</a>
            <ul aria-labelledby="dropdownSubMenu1" class="border-0 shadow dropdown-menu bg-dark">
                <li>
                    <a class="nav-link text-xs" wire:click="openChangePassword" title="Change Password"
                        data-slide="true" href="#" role="button">
                        <i class="fa fa-key"></i> Change-Password
                    </a>
                </li>

                <li>
                    <a class="nav-link text-xs" wire:click="logout" title="Log-out" data-slide="true" href="#"
                        role="button" wire:confirm="Are you sure you want to logout?">
                        <i class="fa fa-sign-out"></i> Logout
                    </a>
                </li>
            </ul>

        </li>

    </ul>

</nav>

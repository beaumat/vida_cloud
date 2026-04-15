<?php
use App\Services\UserServices;
?>
<li id="inventory" class="nav-item {{ request()->is('maintenance/inventory*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('maintenance/inventory*') ? 'active' : '' }}">
        <i class="fa fa-cubes nav-icon"></i>
        <p> Inventory <i class="right fas fa-angle-left"></i> </p>
    </a>
    <ul class="nav nav-treeview">
        @if (UserServices::GetUserRightAccess('items.view'))
            <li class="nav-item">
                <a href="{{ route('maintenanceinventoryitem') }}"
                    class="nav-link {{ request()->is('maintenance/inventory/items*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-cube nav-icon"></i>
                    <p>Item Master List</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('item-group.view'))
            <li class="nav-item">
                <a href="{{ route('maintenanceinventoryitem_group') }}"
                    class="nav-link {{ request()->is('maintenance/inventory/item-group*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-cube nav-icon"></i>
                    <p>Item Group</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('item-class.view'))
            <li class="nav-item">
                <a href="{{ route('maintenanceinventoryitem_class') }}"
                    class="nav-link {{ request()->is('maintenance/inventory/item-class*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-cube nav-icon"></i>
                    <p>Item Class</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('item-sub-class.view'))
            <li class="nav-item">
                <a href="{{ route('maintenanceinventoryitem_sub_class') }}"
                    class="nav-link {{ request()->is('maintenance/inventory/item-sub-class*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-cube nav-icon"></i>
                    <p>Item Sub Class</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('manufacturer.view'))
            <li class="nav-item">
                <a href="{{ route('maintenanceinventorymanufacturers') }}"
                    class="nav-link {{ request()->is('maintenance/inventory/manufacturers*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-cube nav-icon"></i>
                    <p>Manufacturers</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('ship-via.view'))
            <li class="nav-item">
                <a href="{{ route('maintenanceinventoryship_via') }}"
                    class="nav-link {{ request()->is('maintenance/inventory/ship-via*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-cube nav-icon"></i>
                    <p>Ship Via</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('price-level.view'))
            <li class="nav-item">
                <a href="{{ route('maintenanceinventoryprice_level') }}"
                    class="nav-link {{ request()->is('maintenance/inventory/price-level*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-cube nav-icon"></i>
                    <p>Price Level</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('unit-of-measure.view'))
            <li class="nav-item">
                <a href="{{ route('maintenanceinventoryunit_of_measure') }}"
                    class="nav-link {{ request()->is('maintenance/inventory/unit-of-measure*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-cube nav-icon"></i>
                    <p>Unit of Measure</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('stock-bin.view'))
            <li class="nav-item">
                <a href="{{ route('maintenanceinventorystock_bin') }}"
                    class="nav-link {{ request()->is('maintenance/inventory/stock-bin*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-cube nav-icon"></i>
                    <p>Stock Bin</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('inventory-adjustment-type.view'))
            <li class="nav-item">
                <a href="{{ route('maintenanceinventoryinventory_adjustment_type') }}"
                    class="nav-link {{ request()->is('maintenance/inventory/inventory-adjustment-type*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-cube nav-icon"></i>
                    <p>Inventory Adjustment Type</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('price-location'))
            <li class="nav-item">
                <a href="{{ route('maintenanceinventoryprice_location') }}"
                    class="nav-link {{ request()->is('maintenance/inventory/price-location*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-cube nav-icon"></i>
                    <p>Price List Location</p>
                </a>
            </li>
        @endif

        @if (UserServices::GetUserRightAccess('fixed-asset-item'))
            <li class="nav-item">
                <a href="{{ route('maintenanceinventoryfixed_asset_item') }}"
                    class="nav-link {{ request()->is('maintenance/inventory/fixed-asset-items*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-cube nav-icon"></i>
                    <p>Fixed Asset Items</p>
                </a>
            </li>
        @endif
    </ul>
</li>

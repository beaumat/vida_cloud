@php
    use App\Services\UserServices;
    use App\Services\ModeServices;
@endphp

<aside class="main-sidebar  sidebar-dark-info elevation-1 text-xs">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('dist/img/cloud_128.png') }}" alt="" class="brand-image elevation-0" style="opacity: .8">
        <span class="brand-text font-weight-light text-xs text-info"><b>Cloud</b> System.</span>
    </a>
    <div class="sidebar">
        <nav class="mt-1">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Dashboard </p>
                    </a>
                </li>
                @if (ModeServices::GET() == 'H')
                    @if (UserServices::GetUserRightAccess('patient.schedule.view') ||
                            UserServices::GetUserRightAccess('patient.service-charges.view') ||
                            UserServices::GetUserRightAccess('patient.payment.view') ||
                            UserServices::GetUserRightAccess('patient.treatment.view') ||
                            UserServices::GetUserRightAccess('patient.philhealth.view') ||
                            UserServices::GetUserRightAccess('patient.philhealth.manual.view') ||
                            UserServices::GetUserRightAccess('doctor.view'))
                        @livewire('Layouts.PatientMenu')
                    @endif
                @endif
                @if (UserServices::GetUserRightAccess('customer.invoice.view') ||
                        UserServices::GetUserRightAccess('customer.sales-order.view') ||
                        UserServices::GetUserRightAccess('customer.credit-memo.view') ||
                        UserServices::GetUserRightAccess('customer.received-payment.view') ||
                        UserServices::GetUserRightAccess('customer.tax-credit.view'))
                    @livewire('Layouts.CustomerMenu')
                @endif
                @if (UserServices::GetUserRightAccess('vendor.purchase-order.view') ||
                        UserServices::GetUserRightAccess('vendor.bill.view') ||
                        UserServices::GetUserRightAccess('vendor.bill-credit.view') ||
                        UserServices::GetUserRightAccess('vendor.bill-payment.view') ||
                        UserServices::GetUserRightAccess('vendor.withholding-tax.view'))
                    @livewire('Layouts.VendorMenu')
                @endif
                @if (UserServices::GetUserRightAccess('company.stock-transfer.view') ||
                        UserServices::GetUserRightAccess('company.build-assembly.view') ||
                        UserServices::GetUserRightAccess('company.inventory-adjustment.view') ||
                        UserServices::GetUserRightAccess('company.general-journal.view') ||
                        UserServices::GetUserRightAccess('company.pull-out.view'))
                    @livewire('Layouts.CompanyMenu')
                @endif
                @if (UserServices::GetUserRightAccess('banking.deposit.view') ||
                        UserServices::GetUserRightAccess('banking.fund-transfer.view') ||
                        UserServices::GetUserRightAccess('banking.make-cheque.view') ||
                        UserServices::GetUserRightAccess('banking.bank-recon.view'))
                    @livewire('Layouts.BankingMenu')
                @endif
                @if (UserServices::GetUserRightAccess('report.patient.sales') ||
                        UserServices::GetUserRightAccess('report.patient.treatment') ||
                        UserServices::GetUserRightAccess('report.patient.balance') ||
                        UserServices::GetUserRightAccess('report.patient.doctor-pf') ||
                        UserServices::GetUserRightAccess('report.financial.income-statement') ||
                        UserServices::GetUserRightAccess('report.financial.balance-sheet') ||
                        UserServices::GetUserRightAccess('report.financial.cash-flow') ||
                        UserServices::GetUserRightAccess('report.accounting.general-ledger') ||
                        UserServices::GetUserRightAccess('report.accounting.trial-balance') ||
                        UserServices::GetUserRightAccess('report.accounting.transaction-details') ||
                        UserServices::GetUserRightAccess('report.accounting.transaction-journal') ||
                        UserServices::GetUserRightAccess('report.customer.sales'))

                    <li class="nav-item {{ request()->is('reports*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-line-chart"></i>
                            <p> Reports <i class="right fas fa-angle-left"></i> </p>
                        </a>
                        <ul class="nav nav-treeview bg-blue-dark">
                            @if (ModeServices::GET() == 'H')
                                @if (UserServices::GetUserRightAccess('report.patient.sales') ||
                                        UserServices::GetUserRightAccess('report.patient.treatment') ||
                                        UserServices::GetUserRightAccess('report.patient.balance') ||
                                        UserServices::GetUserRightAccess('report.patient.doctor-pf'))
                                    @livewire('Layouts.ReportsPatients')
                                @endif
                            @endif

                            @if (UserServices::GetUserRightAccess('report.customer.sales'))
                                @livewire('Layouts.ReportsSales')
                            @endif

                            @if (UserServices::GetUserRightAccess('report.accounting.general-ledger') ||
                                    UserServices::GetUserRightAccess('report.accounting.trial-balance') ||
                                    UserServices::GetUserRightAccess('report.accounting.transaction-journal') ||
                                    UserServices::GetUserRightAccess('report.accounting.transaction-details'))
                                @livewire('Layouts.ReportsAccounting')
                            @endif

                            @if (UserServices::GetUserRightAccess('report.financial.income-statement') ||
                                    UserServices::GetUserRightAccess('report.financial.balance-sheet') ||
                                    UserServices::GetUserRightAccess('report.financial.cash-flow'))
                                @livewire('Layouts.ReportsFinancial')
                            @endif

                            @if (UserServices::GetUserRightAccess('report.receivables.ar-aging') ||
                                    UserServices::GetUserRightAccess('report.receivables.customer-balance'))
                                @livewire('Layouts.ReportsReceivables')
                            @endif

                            @if (UserServices::GetUserRightAccess('report.payables.ap-aging') ||
                                    UserServices::GetUserRightAccess('report.payables.vendor-balance'))
                                @livewire('Layouts.ReportsPayables')
                            @endif

                             @if (UserServices::GetUserRightAccess('report.inventory.validation-summary'))
                                @livewire('Layouts.ReportsInventory')
                            @endif

                        </ul>
                    </li>
                @endif

                @if (UserServices::GetUserRightAccess('contact.customer.view') ||
                        UserServices::GetUserRightAccess('contact.vendor.view') ||
                        UserServices::GetUserRightAccess('contact.employee.view') ||
                        UserServices::GetUserRightAccess('contact.patient.view') ||
                        UserServices::GetUserRightAccess('contact.doctor.view') ||
                        UserServices::GetUserRightAccess('chart-of-account.view') ||
                        UserServices::GetUserRightAccess('payment-method.view') ||
                        UserServices::GetUserRightAccess('payment-term.view') ||
                        UserServices::GetUserRightAccess('tax-list.view') ||
                        UserServices::GetUserRightAccess('items.view') ||
                        UserServices::GetUserRightAccess('item-class.view') ||
                        UserServices::GetUserRightAccess('item-sub-class.view') ||
                        UserServices::GetUserRightAccess('item-group.view') ||
                        UserServices::GetUserRightAccess('manufacturer.view') ||
                        UserServices::GetUserRightAccess('ship-via.view') ||
                        UserServices::GetUserRightAccess('unit-of-measure.view') ||
                        UserServices::GetUserRightAccess('inventory-adjustment-type.view') ||
                        UserServices::GetUserRightAccess('stock-bin.view') ||
                        UserServices::GetUserRightAccess('price-level.view') ||
                        UserServices::GetUserRightAccess('price-location') ||
                        UserServices::GetUserRightAccess('others.shift.view') ||
                        UserServices::GetUserRightAccess('others.hemodialysis-machine.view') ||
                        UserServices::GetUserRightAccess('others.requirement.view') ||
                        UserServices::GetUserRightAccess('others.item-active-list.view') ||
                        UserServices::GetUserRightAccess('others.item-treatment.view') ||
                        UserServices::GetUserRightAccess('users') ||
                        UserServices::GetUserRightAccess('roles-and-permission') ||
                        UserServices::GetUserRightAccess('location.view') ||
                        UserServices::GetUserRightAccess('location-group.view') ||
                        UserServices::GetUserRightAccess('option'))
                    <li class="nav-item {{ request()->is('maintenance*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->is('maintenance*') ? 'active ' : '' }}">
                            <i class="nav-icon fa fa-cog"></i>
                            <p> Maintenance <i class="right fas fa-angle-left"></i> </p>
                        </a>
                        <ul class="nav nav-treeview bg-blue-dark">
                            @if (UserServices::GetUserRightAccess('contact.customer.view') ||
                                    UserServices::GetUserRightAccess('contact.vendor.view') ||
                                    UserServices::GetUserRightAccess('contact.employee.view') ||
                                    UserServices::GetUserRightAccess('contact.patient.view') ||
                                    UserServices::GetUserRightAccess('contact.doctor.view'))
                                @livewire('Layouts.MaintenanceContacts')
                            @endif
                            @if (UserServices::GetUserRightAccess('chart-of-account.view') ||
                                    UserServices::GetUserRightAccess('payment-method.view') ||
                                    UserServices::GetUserRightAccess('payment-term.view') ||
                                    UserServices::GetUserRightAccess('tax-list.view'))
                                @livewire('Layouts.MaintenanceFinancials')
                            @endif
                            @if (UserServices::GetUserRightAccess('items.view') ||
                                    UserServices::GetUserRightAccess('item-class.view') ||
                                    UserServices::GetUserRightAccess('item-sub-class.view') ||
                                    UserServices::GetUserRightAccess('item-group.view') ||
                                    UserServices::GetUserRightAccess('manufacturer.view') ||
                                    UserServices::GetUserRightAccess('ship-via.view') ||
                                    UserServices::GetUserRightAccess('unit-of-measure.view') ||
                                    UserServices::GetUserRightAccess('inventory-adjustment-type.view') ||
                                    UserServices::GetUserRightAccess('stock-bin.view') ||
                                    UserServices::GetUserRightAccess('price-level.view') ||
                                    UserServices::GetUserRightAccess('price-location'))
                                @livewire('Layouts.MaintenanceInventory')
                            @endif
                            @if (ModeServices::GET() == 'H')
                                @if (UserServices::GetUserRightAccess('others.shift.view') ||
                                        UserServices::GetUserRightAccess('others.hemodialysis-machine.view') ||
                                        UserServices::GetUserRightAccess('others.requirement.view') ||
                                        UserServices::GetUserRightAccess('others.item-active-list.view') ||
                                        UserServices::GetUserRightAccess('others.item-treatment.view'))
                                    @livewire('Layouts.MaintenanceOthers')
                                @endif
                            @endif
                            @if (UserServices::GetUserRightAccess('users') ||
                                    UserServices::GetUserRightAccess('roles-and-permission') ||
                                    UserServices::GetUserRightAccess('location.view') ||
                                    UserServices::GetUserRightAccess('location-group.view') ||
                                    UserServices::GetUserRightAccess('option'))

                                @livewire('Layouts.MaintenanceSettings')
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>

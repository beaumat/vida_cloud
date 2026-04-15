<?php
use App\Services\UserServices;
?>

<li id="patients" class="nav-item {{ request()->is('patients*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('patients*') ? 'active ' : '' }}"> <i
            class="nav-icon fas fa-wheelchair "></i>
        <p> Patients <i class="fas fa-angle-left right"></i> </p>
    </a>
    <ul class="nav nav-treeview bg-blue-dark">
        @if (UserServices::GetUserRightAccess('patient.schedule.view'))
            <li class="nav-item">
                <a href="{{ route('patientsschedules') }}"
                    class="nav-link {{ request()->is('patients/schedules*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fas fa-calendar nav-icon"></i>
                    <p>Schedules</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('patient.treatment.view'))
            <li class="nav-item">
                <a href="{{ route('patientshemo') }}"
                    class="nav-link {{ request()->is('patients/hemodialysis-treatment*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fas fa-medkit nav-icon"></i>
                    <p>Treatment</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('patient.service-charges.view'))
            <li class="nav-item">
                <a href="{{ route('patientsservice_charges') }}"
                    class="nav-link {{ request()->is('patients/service-charges*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fas fa-file-invoice nav-icon"></i>
                    <p>Service Charges</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('patient.payment.view'))
            <li class="nav-item">
                <a href="{{ route('patientspayment') }}"
                    class="nav-link {{ request()->is('patients/payments*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fas fa-money-bill-wave nav-icon"></i>
                    <p>Cash/GL Payments</p>
                </a>
            </li>
        @endif

        @if (UserServices::GetUserRightAccess('patient.philhealth.view'))
            <li class="nav-item {{ request()->is('patients/phil-health*') ? 'menu-open' : '' }}">
                <a href="{{ route('patientsphic') }}"
                    class="nav-link {{ request()->is('patients/phil-health*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fas fa-wheelchair nav-icon"></i>
                    <p>PhilHealth</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('patient.philhealth.manual.view'))
            <li class="nav-item {{ request()->is('patients/phil-health-manual*') ? 'menu-open' : '' }}">
                <a href="{{ route('patientsphic') }}"
                    class="nav-link {{ request()->is('patients/phil-health-manual*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fas fa-wheelchair nav-icon"></i>
                    <p>PhilHealth Manual</p>
                </a>
            </li>
        @endif

        @if (UserServices::GetUserRightAccess('report.patient.doctor-pf'))
            <li class="nav-item {{ request()->is('patients/doctor-pf*') ? 'menu-open' : '' }}">
                <a href="{{ route('patientsdoctor_fee') }}"
                    class="nav-link {{ request()->is('patients/doctor-pf*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-user-md nav-icon" aria-hidden="true"></i>
                    <p>Doctor PF</p>
                </a>
            </li>
        @endif

        @if (UserServices::GetUserRightAccess('patient.doctor.batch.view'))
            <li class="nav-item {{ request()->is('patients/doctor-batch-payment*') ? 'menu-open' : '' }}">
                <a href="{{ route('patientsdoctor_batch') }}"
                    class="nav-link {{ request()->is('patients/doctor-batch-payment*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-user-md nav-icon" aria-hidden="true"></i>
                    <p>Doctor Batch Payment</p>
                </a>
            </li>
        @endif
        @if (UserServices::GetUserRightAccess('patient.payment-period.view'))
            <li class="nav-item {{ request()->is('patients/payment-period*') ? 'menu-open' : '' }}">
                <a href="{{ route('patientspayment_period') }}"
                    class="nav-link {{ request()->is('patients/payment-period*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-cc-mastercard nav-icon" aria-hidden="true"></i>
                    <p>Payment Period (ACPN)</p>
                </a>
            </li>

            <li class="nav-item {{ request()->is('patients/phic-paid*') ? 'menu-open' : '' }}">
                <a href="{{ route('patientsphic_paid') }}"
                    class="nav-link {{ request()->is('patients/phic-paid*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-paypal nav-icon" aria-hidden="true"></i>
                    <p>Philhealth Paid (ACPN)</p>
                </a>
            </li>

            <li class="nav-item {{ request()->is('patients/phic-payment-2026*') ? 'menu-open' : '' }}">
                <a href="{{ route('patientsphic_payment2026') }}"
                    class="nav-link {{ request()->is('patients/phic-payment-2026*') ? 'text-warning font-weight-bold' : '' }}">
                    <i class="fa fa-paypal nav-icon" aria-hidden="true"></i>
                    <p>Philhealth Paid (2026)</p>
                </a>
            </li>
        @endif

    </ul>
</li>

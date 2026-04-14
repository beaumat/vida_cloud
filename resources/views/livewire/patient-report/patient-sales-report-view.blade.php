<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="#"> Patient Collection Report
                        </a></h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            <a target="_blank"
                                href="{{ route('reportspatient_sales_report_print', [
                                    'date_from' => $DATE_TRANSACTION_FROM,
                                    'date_to' => $DATE_TRANSACTION_TO,
                                    'location_id' => $LOCATION_ID,
                                    'patient' => !empty($selectedPatient) ? implode(',', $selectedPatient) : 'none',
                                    'item' => !empty($selectedItem) ? implode(',', $selectedItem) : 'none',
                                    'method' => !empty($selectedMethod) ? implode(',', $selectedMethod) : 'none',
                                ]) }}"
                                class="btn btn-sm btn-primary">
                                <i class="fa fa-print" aria-hidden="true"></i>
                                Print</a>
                            <button class="btn btn-sm btn-success" wire:click='export()'> <i class="fa fa-file-excel-o"
                                    aria-hidden="true"></i> Export To Excel</button>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid bg-light">
            <div class="row" wire:loading.attr='hidden'>
                <div class="col-md-12" style="max-height: 80vh; overflow-y: auto;">
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="text-xs bg-sky sticky-header">
                            <tr>
                                <th class="col-2">Patient</th>
                                <th class="col-1"> Category</th>
                                <th class="col-3">Description</th>
                                <th class="text-center">Qty</th>
                                <th class="bg-info">Reference</th>
                                <th class="bg-info">Date</th>
                                <th class="bg-info">Charges </th>
                                <th class="bg-warning">Credit</th>
                                <th class="bg-danger">Running Bal.</th>
                                <th>Doctor</th>
                                <th>Location </th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($dataList as $list)
                                {{-- LOGIC START --}}
                                @php
                                    if ($list->LINE_NO != 999) {
                                        if ($sc_code == $list->CODE) {
                                            $is_sc = false;
                                        } else {
                                            $is_sc = true;
                                            $NO_OF_TREATMENT = $NO_OF_TREATMENT + 1;
                                        }
                                    }

                                    if ($PREV_SC_ITEM_REF_ID == $list->ITEM_REF_ID) {
                                        $not_to_charge = true;
                                    } else {
                                        $not_to_charge = false;
                                    }

                                    if ($tempName == $list->PATIENT_NAME) {
                                        $is_add = false;
                                        if ($not_to_charge == false) {
                                            // if ($list->LINE_NO == 999) {
                                            //     $running_balance = $running_balance - $list->AMOUNT ?? 0;
                                            // } else {
                                            //     $running_balance = $running_balance + $list->AMOUNT ?? 0;
                                            // }
                                        }

                                        if ($list->LINE_NO == 999) {
                                            $running_balance = $running_balance - $list->AMOUNT ?? 0;
                                        } else {
                                            $running_balance = $running_balance + $list->AMOUNT ?? 0;
                                        }
                                    } else {
                                        $is_add = true;
                                        $is_sc = true;
                                        $running_balance = $list->AMOUNT ?? 0;
                                        $NO_OF_PATIENT = $NO_OF_PATIENT + 1;
                                    }

                                    if ($list->LINE_NO != 999) {
                                        $tempName = $list->PATIENT_NAME;
                                        $sc_code = $list->CODE;
                                        $PREV_SC_ITEM_REF_ID = $list->ITEM_REF_ID ?? 0;
                                    }
                                @endphp

                                @if ($is_add == true)
                                    <tr class="bg-dark">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                @endif
                                {{-- LOGIC END --}}
                                <tr class=" @if ($is_add == true) font-weight-bold @endif">
                                    <td>
                                        @if ($is_add == true)
                                            {{ $list->PATIENT_NAME }}
                                        @endif
                                    </td>
                                    <td class="@if ($list->LINE_NO == 999) text-success font-weight-bold @endif">
                                        {{ $list->CLASS_NAME }}
                                    </td>
                                    <td class="@if ($list->LINE_NO == 999) text-success font-weight-bold @endif">
                                        {{ $list->ITEM_NAME }}
                                    </td>
                                    <td class="text-center @if ($list->LINE_NO == 999) text-success font-weight-bold @endif">
                                        {{  $list->QUANTITY > 0 ? number_format($list->QUANTITY, 0) : '' }}
                                    </td>
                                    <td class="@if ($list->LINE_NO == 999) text-success font-weight-bold @endif">
                                        @if ($list->LINE_NO != 999)
                                            @if ($is_sc == true)
                                                <a target="_BLANK"
                                                    href="{{ route('patientsservice_charges_edit', ['id' => $list->ID]) }}">{{ $list->CODE }}</a>
                                            @endif
                                        @else
                                            <a target="_BLANK" class="text-success"
                                                href="{{ route('patientspayment_edit', ['id' => $list->ID]) }}">{{ $list->CODE }}</a>
                                        @endif
                                    </td>
                                    <td class="@if ($list->LINE_NO == 999) text-success font-weight-bold @endif">
                                        @if ($list->LINE_NO != 999)
                                            @if ($is_sc == true)
                                                {{ date('m/d/Y', strtotime($list->DATE)) }}
                                            @endif
                                        @else
                                            {{ date('m/d/Y', strtotime($list->DATE)) }}
                                        @endif
                                    </td>

                                    <td class="text-right ">
                                        @if ($list->LINE_NO != 999)
                                            {{ number_format($list->AMOUNT, 2) }}
                                            @php
                                                $TOTAL_CHARGE = $TOTAL_CHARGE + $list->AMOUNT ?? 0;
                                            @endphp
                                        @endif
                                    </td>
                                    <td
                                        class="text-right font-weight-bold @if ($list->LINE_NO == 999) text-success @endif">

                                        @if ($list->LINE_NO == 999)
                                            {{ number_format($list->AMOUNT * -1, 2) }}
                                            @php
                                                //   $TOTAL_PAID = $TOTAL_PAID + $list->AMOUNT ?? 0;
                                            @endphp
                                        @else
                                            {{ $list->PREVIOUS_CREDIT > 0 ? number_format($list->PREVIOUS_CREDIT * -1, 2) : '' }}
                                            @if ($list->PREVIOUS_CREDIT ?? 0 > 0)
                                                @php
                                                    $running_balance = $running_balance - $list->PREVIOUS_CREDIT ?? 0;
                                                    $TOTAL_CHARGE = $TOTAL_CHARGE - $list->PREVIOUS_CREDIT ?? 0;
                                                @endphp
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        {{ number_format($running_balance ?? 0, 2) }}
                                    </td>
                                    <td>
                                        @if ($is_add == true)
                                            {{ $list->DOCTOR_NAME }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($is_add == true)
                                            {{ $list->LOCATION_NAME }}
                                        @endif
                                    </td>
                                </tr>





                                @php

                                    if (substr($list->ITEM_NAME, 0, 6) == 'Cash :') {
                                        $CASH_AMOUNT = $CASH_AMOUNT + $list->AMOUNT ?? 0;
                                        $TOTAL_PAID = $TOTAL_PAID + $list->AMOUNT ?? 0;
                                    }

                                    if (substr($list->ITEM_NAME, 0, 12) == 'Philhealth :') {
                                        $PHILHEALTH_AMOUNT = $PHILHEALTH_AMOUNT + $list->AMOUNT ?? 0;
                                    }

                                    if (substr($list->ITEM_NAME, 0, 6) == 'DSWD :') {
                                        $DSWD_AMOUNT = $DSWD_AMOUNT + $list->AMOUNT ?? 0;
                                    }

                                    if (substr($list->ITEM_NAME, 0, 8) == 'LINGAP :') {
                                        $LINGAP_AMOUNT = $LINGAP_AMOUNT + $list->AMOUNT ?? 0;
                                    }

                                    if (substr($list->ITEM_NAME, 0, 6) == 'PCSO :') {
                                        $PCSO_AMOUNT = $PCSO_AMOUNT + $list->AMOUNT ?? 0;
                                    }

                                    if (substr($list->ITEM_NAME, 0, 4) == 'OP :') {
                                        $OP_AMOUNT = $OP_AMOUNT + $list->AMOUNT ?? 0;
                                    }

                                    if (substr($list->ITEM_NAME, 0, 5) == 'OVP :') {
                                        $OVP_AMOUNT = $OVP_AMOUNT + $list->AMOUNT ?? 0;
                                    }

                                    if (substr($list->ITEM_NAME, 0, 10) == 'Other GL :') {
                                        $OTHER_GL_AMOUNT = $OTHER_GL_AMOUNT + $list->AMOUNT ?? 0;
                                    }

                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-xs"><label>No. of Patient : </label> <span class="text-primary">
                                    {{ $NO_OF_PATIENT }}</span> </h6>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-xs"><label>No. of Treatment : </label> <span class="text-primary">
                                    {{ $NO_OF_TREATMENT }}</span> </h6>
                        </div>
                        <div class="col-md-12 text-xs">
                            <label class="text-xs bg-warning">Previous Credit Summary </label>
                            <ol>
                                @foreach ($preDataList as $list)
                                    <li><i class="text-orange">{{ $list->PAYMENT_METHOD }} : </i> <strong
                                            class="text-purple"> {{ date('m/d/Y', strtotime($list->PP_DATE)) }}
                                        </strong>/ pt:<b>{{ $list->PATIENT_NAME }}</b>
                                        / Credit : <span
                                            class="text-success">{{ number_format($list->PP_PAID, 2) }}</span> on
                                        <span class="text-primary">{{ $list->ITEM_NAME }}</span>
                                    </li>
                                @endforeach

                            </ol>
                            <span class="h6">Total: <b
                                    class="text-success">{{ number_format($PRE_COLLECTION, 2) }}</b></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-3 text-right">
                            <div class="row">
                                <div class="col-12  text-xs"> <label class="text-xs">Philhealth Paid : </label>
                                    <span
                                        class="text-success font-weight-bold text-xs">{{ number_format($PHILHEALTH_AMOUNT, 2) }}</span>
                                </div>
                                <div class="col-12 text-xs"> <label class="text-xs">DSWD Paid : </label>
                                    <span
                                        class="text-success font-weight-bold text-xs">{{ number_format($DSWD_AMOUNT, 2) }}</span>
                                </div>
                                <div class="col-12 text-xs"> <label class="text-xs">LINGAP Paid : </label>
                                    <span
                                        class="text-success font-weight-bold text-xs">{{ number_format($LINGAP_AMOUNT, 2) }}</span>
                                </div>
                                <div class="col-12  text-xs"> <label class="text-xs">PCSO Paid : </label>
                                    <span
                                        class="text-success active font-weight-bold text-xs">{{ number_format($PCSO_AMOUNT, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <div class="row">

                                <div class="col-12 text-xs">
                                    <label class="text-xs">OP Paid : </label>
                                    <span
                                        class="text-success font-weight-bold text-xs">{{ number_format($OP_AMOUNT, 2) }}
                                    </span>
                                </div>
                                <div class="col-12 text-xs">
                                    <label class="text-xs">OVP Paid : </label>
                                    <span
                                        class="text-success font-weight-bold text-xs">{{ number_format($OVP_AMOUNT, 2) }}
                                    </span>
                                </div>
                                <div class="col-12 text-xs">
                                    <label class="text-xs">OTHER GL Paid : </label>
                                    <span
                                        class="text-success font-weight-bold text-xs">{{ number_format($OTHER_GL_AMOUNT, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <div class="row">
                                <div class=" col-12 text-xs"> <label class="text-xs">Cash Paid : </label>
                                    <span
                                        class="text-success font-weight-bold text-xs">{{ number_format($CASH_AMOUNT, 2) }}</span>
                                </div>
                                <div class="col-12  text-xs"> <label class="text-xs">Previous Cash :
                                    </label>
                                    <span
                                        class="text-success font-weight-bold text-xs">{{ number_format($PRE_CASH_AMOUNT, 2) }}</span>
                                </div>
                                <div class="col-12  text-xs"> <label class="text-xs ">Net Cash Sales : </label>
                                    <span
                                        class="text-info font-weight-bold text-xs">{{ number_format($CASH_AMOUNT + $PRE_CASH_AMOUNT, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-right">
                            <h6 class="text-xs"> <label class="text-xs">TOTAL CHARGES : </label>
                                <span
                                    class="text-primary font-weight-bold h6">{{ number_format($TOTAL_CHARGE, 2) }}</span>
                            </h6>
                            <h6 class="text-xs"> <label class="text-xs">CURRENT CREDIT : </label>
                                <span
                                    class="text-success font-weight-bold h6">{{ number_format($TOTAL_PAID, 2) }}</span>
                            </h6>
                            <h6 class="text-xs"> <label class="text-xs">TOTAL BALANCE : </label>
                                <span
                                    class="text-danger font-weight-bold h6">{{ number_format($TOTAL_CHARGE - $TOTAL_PAID, 2) }}</span>
                            </h6 class="text-xs">
                        </div>
                    </div>
                </div>
            </div>
            <div wire:loading.delay>
                <span class="spinner spinner-border-xs animate-spin text-info" role="status"
                    aria-hidden="true"></span>
                <span class="text-info"> Processing data, please wait...</span>
            </div>
        </div>
    </section>
</div>

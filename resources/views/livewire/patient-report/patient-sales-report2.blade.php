<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('reportspatient_sales_report') }}"> Patient Collection Report
                        </a></h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid bg-light">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group bg-light p-2 border border-secondary">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_TRANSACTION_FROM" titleName="(SC) From"
                                            wire:model.live='DATE_TRANSACTION_FROM' :isDisabled="false" />
                                    </div>
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_TRANSACTION_TO" titleName="(SC) To"
                                            wire:model.live='DATE_TRANSACTION_TO' :isDisabled="false" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                {{-- <div class="row">
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_COLLECTION_FROM" titleName="(P) From"
                                            wire:model='DATE_COLLECTION_FROM' :isDisabled="false" />
                                    </div>
                                    <div class="col-md-5">
                                        <livewire:date-input name="DATE_COLLECTION_TO" titleName="(P) To"
                                            wire:model='DATE_COLLECTION_TO' :isDisabled="false" />
                                    </div>
                                </div> --}}
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-8">
                                        {{-- @if ($refreshComponent)
                                            <livewire:select-option name="PATIENT_ID1" titleName="Selected patient"
                                                :options="$patientList" :zero="true" :isDisabled=false
                                                wire:model.live='PATIENT_ID' />
                                        @else
                                            <livewire:select-option name="PATIENT_ID2" titleName="Selected patient"
                                                :options="$patientList" :zero="true" :isDisabled=false
                                                wire:model.live='PATIENT_ID' />
                                        @endif --}}
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mt-0">
                                            <label class="text-xs ">Location:</label>
                                            <select
                                                @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
                                                name="location" wire:model.live='LOCATION_ID'
                                                class="form-control form-control-sm text-xs ">
                                                <option value="0"> All Location</option>
                                                @foreach ($locationList as $item)
                                                    <option value="{{ $item->ID }}"> {{ $item->NAME }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <div wire:loading.delay>
                                    <span class="spinner"></span>
                                </div>
                                <button class="btn btn-xs btn-danger w-25" wire:click='showfilter()'
                                    wire:loading.attr='disabled'>Generate</button>
                                <button class="btn btn-xs btn-success w-25" wire:click='export()'
                                    wire:loading.attr='disabled'>Export</button>


                                <a type="button" class="btn btn-xs btn-warning w-25"
                                    href="{{ route('reportspatient_sales_report_print', ['date_from' => $DATE_TRANSACTION_FROM, 'date_to' => $DATE_TRANSACTION_TO, 'location_id' => $LOCATION_ID]) }}"
                                    target="_BLANK">
                                    Print
                                </a>
                            </div>
                            <div class="col-6">

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-4">
                                {{-- filterPatient --}}

                                @if ($refreshComponent)
                                    <livewire:select-checkbox name="PATIENT_ID1" titleName="Filter patient"
                                        :options="$filterPatient" :zero="true" :isDisabled=false
                                        wire:model='selectedPatient' />
                                @else
                                    <livewire:select-checkbox name="PATIENT_ID2" titleName="Filter patient"
                                        :options="$filterPatient" :zero="true" :isDisabled=false
                                        wire:model='selectedPatient' />
                                @endif
                            </div>
                            <div class="col-4">
                                {{-- filterPatient --}}

                                @if ($refreshComponent)
                                    <livewire:select-checkbox name="ITEM1" titleName="Filter item" :options="$filterItem"
                                        :zero="true" :isDisabled=false wire:model='selectedItem' />
                                @else
                                    <livewire:select-checkbox name="ITEM2" titleName="Filter item" :options="$filterItem"
                                        :zero="true" :isDisabled=false wire:model='selectedItem' />
                                @endif
                            </div>
                            <div class="col-4">
                                @if ($refreshComponent)
                                    <livewire:select-checkbox name="METHOD1" titleName="Filter method"
                                        :options="$filterMethod" :zero="true" :isDisabled=false
                                        wire:model='selectedMethod' />
                                @else
                                    <livewire:select-checkbox name="METHOD2" titleName="Filter method"
                                        :options="$filterMethod" :zero="true" :isDisabled=false
                                        wire:model='selectedMethod' />
                                @endif
                            </div>
                            <div class="col-6 p-1">
                                <button class="btn btn-xs btn-info w-25" wire:click='shortFilter()'
                                    wire:loading.attr='disabled'>Filter</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12" wire:loading.attr='hidden'>
                    <div class="row">
                        <div class="col-md-12" style="max-height: 80vh; overflow-y: auto;">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky sticky-header">
                                    <tr>
                                        <th>Patient Name</th>
                                        <th>Item Name</th>
                                        <th class="bg-info">(SC) Date</th>
                                        <th class="bg-info">(SC) Code</th>
                                        <th class="bg-info">(SC) Amount</th>
                                        <th class="bg-success">(P) Date</th>
                                        <th class="bg-success">(P) Code</th>
                                        <th class="bg-success">(P) Method</th>
                                        <th class="bg-success">(P) Deposit</th>
                                        <th class="bg-success">(P) Paid </th>
                                        <th class="bg-danger">Running Bal.</th>
                                        <th>Doctor</th>
                                        <th>Location </th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        {{-- LOGIC START --}}
                                        @php
                                            if ($sc_code == $list->SC_CODE) {
                                                $is_sc = false;
                                            } else {
                                                $is_sc = true;
                                                $NO_OF_TREATMENT = $NO_OF_TREATMENT + 1;
                                            }

                                            if ($PREV_SC_ITEM_REF_ID == $list->SC_ITEM_REF_ID) {
                                                $not_to_charge = true;
                                            } else {
                                                $not_to_charge = false;
                                            }

                                            if ($tempName == $list->PATIENT_NAME) {
                                                $is_add = false;
                                                if ($not_to_charge == false) {
                                                    $running_balance = $running_balance + $list->SC_AMOUNT ?? 0;
                                                }
                                            } else {
                                                $is_add = true;
                                                $is_sc = true;
                                                $running_balance = $list->SC_AMOUNT ?? 0;
                                                $NO_OF_PATIENT = $NO_OF_PATIENT + 1;
                                            }

                                            $running_balance = $running_balance - $list->PP_PAID;
                                            $tempName = $list->PATIENT_NAME;
                                            $sc_code = $list->SC_CODE;
                                            $PREV_SC_ITEM_REF_ID = $list->SC_ITEM_REF_ID ?? 0;
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
                                            <td>{{ $list->ITEM_NAME }}</td>
                                            <td>
                                                @if ($is_sc == true)
                                                    <a target="_BLANK"
                                                        href="{{ route('patientsservice_charges_edit', ['id' => $list->SC_ID]) }}">{{ $list->SC_CODE }}</a>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($is_sc == true)
                                                    {{ date('m/d/Y', strtotime($list->SC_DATE)) }}
                                                @endif
                                            </td>


                                            <td class="text-right">
                                                @if ($not_to_charge == false)
                                                    {{ number_format($list->SC_AMOUNT, 2) }}
                                                    @php
                                                        $TOTAL_CHARGE = $TOTAL_CHARGE + $list->SC_AMOUNT ?? 0;
                                                    @endphp
                                                @endif
                                            </td>
                                            <td class="@if ($list->PP_DATE) bg-warning @endif">
                                                @if ($list->PP_DATE)
                                                    {{ date('m/d/Y', strtotime($list->PP_DATE)) }}
                                                @endif
                                            </td>
                                            <td class="@if ($list->PP_ID) bg-warning @endif">
                                                @if ($list->PP_ID)
                                                    <a target="_BLANK"
                                                        href="{{ route('patientspayment_edit', ['id' => $list->PP_ID]) }}">{{ $list->PP_CODE }}</a>
                                                @endif
                                            </td>
                                            <td class="@if ($list->PP_ID) bg-warning @endif">
                                                {{ $list->PAYMENT_METHOD }}</td>
                                            <td
                                                class="text-right @if ($list->PP_ID) bg-warning @endif">
                                                @if ($list->PP_DEPOSIT > 0)
                                                    {{ number_format($list->PP_DEPOSIT, 2) }}
                                                @endif
                                            </td>
                                            <td
                                                class="text-right @if ($list->PP_ID) bg-warning @endif">
                                                @if ($list->PP_PAID > 0)
                                                    {{ number_format($list->PP_PAID, 2) }}

                                                    @php
                                                        $TOTAL_PAID = $TOTAL_PAID + $list->PP_PAID ?? 0;

                                                        if ($list->PAYMENT_METHOD_ID == 1) {
                                                            //Cash
                                                            $CASH_AMOUNT = $CASH_AMOUNT + $list->PP_PAID ?? 0;
                                                        }

                                                        if ($list->PAYMENT_METHOD_ID == 91) {
                                                            //Philhealth
                                                            $PHILHEALTH_AMOUNT =
                                                                $PHILHEALTH_AMOUNT + $list->PP_PAID ?? 0;
                                                        }

                                                        if ($list->PAYMENT_METHOD_ID == 92) {
                                                            //DSWD
                                                            $DSWD_AMOUNT = $DSWD_AMOUNT + $list->PP_PAID ?? 0;
                                                        }

                                                        if ($list->PAYMENT_METHOD_ID == 93) {
                                                            //LINGAP
                                                            $LINGAP_AMOUNT = $LINGAP_AMOUNT + $list->PP_PAID ?? 0;
                                                        }

                                                        if ($list->PAYMENT_METHOD_ID == 94) {
                                                            //PCSO
                                                            $PCSO_AMOUNT = $PCSO_AMOUNT + $list->PP_PAID ?? 0;
                                                        }
                                                        if ($list->PAYMENT_METHOD_ID == 96) {
                                                            //Other GL
                                                            $OTHER_GL_AMOUNT = $OTHER_GL_AMOUNT + $list->PP_PAID ?? 0;
                                                        }
                                                        if ($list->PAYMENT_METHOD_ID == 97) {
                                                            //OVP
                                                            $OVP_AMOUNT = $OVP_AMOUNT + $list->PP_PAID ?? 0;
                                                        }

                                                        if ($list->PAYMENT_METHOD_ID == 98) {
                                                            //OP
                                                            $OP_AMOUNT = $OP_AMOUNT + $list->PP_PAID ?? 0;
                                                        }
                                                    @endphp
                                                @endif
                                            </td>
                                            <td class="text-right">

                                                {{ number_format($running_balance, 2) }}
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
                                    <label class="h6">Previous Cash Collection </label>
                                    <ol>
                                        @foreach ($preDataList as $list)
                                            <li> <b>{{ $list->PATIENT_NAME }}</b>/ <i>{{ $list->PAYMENT_METHOD }}</i>
                                                / Paid:
                                                <span
                                                    class="text-success">{{ number_format($list->PP_PAID, 2) }}</span>
                                                on
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
                                        <div class="col-12  text-xs"> <label class="text-xs">Philhealth Paid :
                                            </label>
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
                                        <div class="col-12  text-xs"> <label class="text-xs">Previous Cash Collection
                                                :
                                            </label>
                                            <span
                                                class="text-success font-weight-bold text-xs">{{ number_format($PRE_COLLECTION, 2) }}</span>
                                        </div>
                                        <div class="col-12  text-xs"> <label class="text-xs ">Net Cash Sales :
                                            </label>
                                            <span
                                                class="text-info font-weight-bold text-xs">{{ number_format($CASH_AMOUNT + $PRE_COLLECTION, 2) }}</span>
                                        </div>


                                    </div>
                                </div>
                                <div class="col-md-3 text-right">
                                    <h6 class="text-xs"> <label class="text-xs">TOTAL (SC) : </label>
                                        <span
                                            class="text-primary font-weight-bold h6">{{ number_format($TOTAL_CHARGE, 2) }}</span>
                                    </h6>
                                    <h6 class="text-xs"> <label class="text-xs">TOTAL (Payment) : </label>
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

                </div>


            </div>
        </div>
    </section>
</div>

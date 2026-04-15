<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-md-12">
                    <div class="card">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <div class="row">
                                <div class="col-sm-6">
                                    {{ $ID == 0 ? 'Create' : '' }}
                                    <a class="text-white" href="{{ route('patientsphic') }}">
                                        Philhealth
                                    </a>
                                </div>
                                <div class="col-sm-6 text-right">
                                    @if ($ID > 0)
                                        <i> {{ $STATUS_DESCRIPTION }}</i>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body bg-light">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-12">
                                                    @if ($Modify)
                                                        <livewire:select-option name="CONTACT_ID" titleName="Patient"
                                                            :options="$patientList" :zero="true" :isDisabled=false
                                                            wire:model.live='CONTACT_ID' />
                                                    @else
                                                        <livewire:select-option name="CONTACT_ID" titleName="Patient"
                                                            :options="$patientList" :zero="true" :isDisabled=true
                                                            wire:model.live='CONTACT_ID' />
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    @if ($Modify)
                                                        <livewire:date-input name="DATE_ADMITTED"
                                                            titleName="Date Admitted" wire:model='DATE_ADMITTED'
                                                            :isDisabled="false" />
                                                    @else
                                                        <livewire:date-input name="DATE_ADMITTED"
                                                            titleName="Date Admitted" wire:model='DATE_ADMITTED'
                                                            :isDisabled="true" />
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    @if ($Modify)
                                                        <livewire:time-input name="TIME_ADMITTED"
                                                            titleName="Time Admitted" wire:model='TIME_ADMITTED'
                                                            :isDisabled="false" />
                                                    @else
                                                        <livewire:time-input name="TIME_ADMITTED"
                                                            titleName="Time Admitted" wire:model='TIME_ADMITTED'
                                                            :isDisabled="true" />
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <livewire:date-input name="DATE" titleName="Date"
                                                        wire:model='DATE' :isDisabled="true" />
                                                </div>
                                                <div class="col-md-3">
                                                    @if ($Modify)
                                                        <livewire:text-input name="Code" titleName="SOA No."
                                                            :isDisabled=false wire:model='CODE' />
                                                    @else
                                                        <livewire:text-input name="Code" titleName="SOA No."
                                                            :isDisabled=true wire:model='CODE' />
                                                    @endif
                                                </div>
                                                <div class="col-md-3"
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif>
                                                    @if ($Modify)
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled=false
                                                            wire:model='LOCATION_ID' />
                                                    @else
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled=true
                                                            wire:model='LOCATION_ID' />
                                                    @endif
                                                </div>
                                                <div class="col-md-3">

                                                </div>
                                                <div class="col-md-3">
                                                    @if ($Modify)
                                                        <livewire:date-input name="DATE_DISCHARGED"
                                                            titleName="Date Discharged" wire:model='DATE_DISCHARGED'
                                                            :isDisabled="false" />
                                                    @else
                                                        <livewire:date-input name="DATE_DISCHARGED"
                                                            titleName="Date Discharged" wire:model='DATE_DISCHARGED'
                                                            :isDisabled="true" />
                                                    @endif
                                                </div>
                                                <div class="col-md-3">
                                                    @if ($IS_HIDE)
                                                        <livewire:time-input name="TIME_HIDE"
                                                            titleName="Time Discharged*" wire:model='TIME_HIDE'
                                                            :isDisabled="true" />
                                                    @else
                                                        @if ($Modify)
                                                            <livewire:time-input name="TIME_DISCHARGED"
                                                                titleName="Time Discharged" wire:model='TIME_DISCHARGED'
                                                                :isDisabled="false" />
                                                        @else
                                                            <livewire:time-input name="TIME_DISCHARGED"
                                                                titleName="Time Discharged" wire:model='TIME_DISCHARGED'
                                                                :isDisabled="true" />
                                                        @endif
                                                    @endif

                                                </div>
                                                <div class="col-md-3">
                                                    <livewire:text-input name="AR_NO" titleName="AR No."
                                                        :isDisabled=true wire:model='AR_NO' />
                                                </div>
                                                <div class="col-md-3">
                                                    <livewire:date-input name="AR_DATE" titleName="AR Date"
                                                        wire:model='AR_DATE' :isDisabled="true" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        @if ($STATUS == 0)
                                            @if ($Modify)
                                                <button type="submit" class="btn btn-sm btn-success text-xs">
                                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                                    {{ $ID === 0 ? 'Pre-save' : 'Update' }}</button>

                                                @if ($ID > 0)
                                                    <button type="button" wire:click='updateCancel'
                                                        class="btn btn-sm btn-danger text-xs"><i class="fa fa-ban"
                                                            aria-hidden="true"></i> Cancel</button>
                                                @endif
                                            @else
                                                @if (!$isPaid)
                                                    <button type="button" wire:click='getModify()'
                                                        class="btn btn-sm btn-info text-xs"
                                                        @if ($STATUS > 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                        <i class="fa fa-wrench" aria-hidden="true"></i> Modify
                                                    </button>
                                                    <button type="button" wire:click="getARForm()"
                                                        class="btn btn-success active btn-sm text-xs"> <i
                                                            class="fa fa-registered" aria-hidden="true"></i> LHIO Form
                                                    </button>
                                                @endif
                                                @if ($ID > 0)
                                                    @can('patient.philhealth.update')
                                                        <button type="button" wire:click="getChangeDoctor()"
                                                            class="btn btn-primary active btn-sm text-xs">
                                                            <i class="fa fa-user-md" aria-hidden="true"></i> Change PF
                                                        </button>
                                                    @endcan

                                                    @if ($AR_NO == null)
                                                        <button type="button" wire:click="getComputation()"
                                                            class="btn btn-danger active btn-sm text-xs"
                                                            wire:confirm='Are you sure to re-calculate the computation?'>
                                                            <i class="fa fa-user-md" aria-hidden="true"></i>
                                                            Re-compute

                                                        </button>
                                                    @endif
                                                @endif

                                            @endif
                                        @endif
                                    </div>
                                    <div class="text-right col-6 col-md-6">
                                        @can('patient.philhealth.print')

                                            @if ($ID > 0)
                                                <button type="button" class="btn btn-sm btn-primary text-xs"
                                                    wire:click='print()'>
                                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                                </button>
                                            @endif
                                        @endcan
                                        @can('patient.philhealth.create')
                                            @if ($ID > 0)
                                                <a id="new" title="Create"
                                                    href="{{ route('patientsphic_create') }}"
                                                    class="btn btn-success btn-sm text-xs"> <i class="fas fa-plus"></i>
                                                    New </a>
                                            @endif
                                        @endcan
                                        @if ($ID > 0)
                                            <button type="button" class="btn btn-sm btn-info text-xs"
                                                wire:click='finder()'>
                                                <i class="fa fa-search" aria-hidden="true"></i> Find
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
    @if ($ID > 0)
        <section class="content">
            <div class="container-fluid bg-light">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0" wire:loading.class='loading-form'>
                                <ul class="nav text-xs nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'soa') active @endif"
                                            id="custom-tabs-four-soa-tab" wire:click="SelectTab('soa')"
                                            data-toggle="pill" href="#custom-tabs-four-soa" role="tab"
                                            aria-controls="custom-tabs-four-soa" aria-selected="true">
                                            Summary of Fees
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'treatment') active @endif"
                                            id="custom-tabs-four-treatment-tab" wire:click="SelectTab('treatment')"
                                            data-toggle="pill" href="#custom-tabs-four-treatment" role="tab"
                                            aria-controls="custom-tabs-four-treatment" aria-selected="true">Treatment
                                            Summary</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'drugNmed') active @endif"
                                            id="custom-tabs-four-drugNmed-tab" wire:click="SelectTab('drugNmed')"
                                            data-toggle="pill" href="#custom-tabs-four-drugNmed" role="tab"
                                            aria-controls="custom-tabs-four-drugNmed" aria-selected="true">CF Form</a>
                                    </li>

                                    <li wire:loading.delay>
                                        <span class='spinner'></span>
                                    </li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <div class="tab-pane fade @if ($tab == 'soa') show active @endif"
                                        id="custom-tabs-four-soa" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-soa-tab">
                                        <div class="row"
                                            @if ($ID == 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <div class="col-md-12">
                                                @if ($tab == 'soa')
                                                    @livewire('PhilHealth.StatementOfAccount', ['ID' => $ID])
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade @if ($tab == 'treatment') show active @endif"
                                        id="custom-tabs-four-treatment" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-treatment-tab">
                                        <div class="row"
                                            @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <div class="col-md-12"
                                                @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                                @if ($tab == 'treatment')
                                                    @livewire('PhilHealth.TreatmentSummary', ['CONTACT_ID' => $CONTACT_ID, 'LOCATION_ID' => $LOCATION_ID, 'DATE_ADMITTED' => $DATE_ADMITTED, 'DATE_DISCHARGED' => $DATE_DISCHARGED])
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade @if ($tab == 'drugNmed') show active @endif"
                                        id="custom-tabs-four-drugNmed" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-drugNmed-tab">
                                        <div class="row"
                                            @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <div class="col-md-12">
                                                @if ($tab == 'drugNmed')
                                                    @livewire('PhilHealth.DrugMedicines', ['PHILHEALTH_ID' => $ID])
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="card-footer h6 bg-light text-right">
                                PAYMENT COLLECTION : <strong class="text-danger">{{ number_format($PAYMENT_AMOUNT, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    @livewire('PhilHealth.ArForm')
    @livewire('PhilHealth.PrintModal')
    @livewire('PhilHealth.ChangeProFeeModal', ['PHILHEALTH_ID' => $ID, 'LOCATION_ID' => $LOCATION_ID])
    @livewire('PhilHealth.Finder')
</div>

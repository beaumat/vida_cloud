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
                                    <a class="text-white" href="{{ route('patientsservice_charges') }}"> Service
                                        Charges </a>
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
                                        <div class="col-md-6">
                                            @if ($Modify)
                                                @if ($WALK_IN)
                                                    <livewire:select-option name="PATIENT_ID2" titleName="Patient"
                                                        :options="$patientList" :zero="true" :isDisabled=false
                                                        wire:model='PATIENT_ID' />
                                                @else
                                                    <livewire:select-option name="PATIENT_ID1" titleName="Patient"
                                                        :options="$patientList" :zero="true" :isDisabled=false
                                                        wire:model='PATIENT_ID' />
                                                @endif
                                            @else
                                                <livewire:select-option name="PATIENT_ID3" titleName="Patient"
                                                    :options="$patientList" :zero="true" :isDisabled=true
                                                    wire:model='PATIENT_ID' />

                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <livewire:date-input name="DATE" titleName="Date"
                                                        wire:model.live='DATE' :isDisabled="true" />
                                                </div>
                                                <div class="col-md-4">
                                                    @if ($Modify)
                                                        <livewire:text-input name="Code" titleName="Reference No."
                                                            :isDisabled=false wire:model='CODE' />
                                                    @else
                                                        <livewire:text-input name="Code" titleName="Reference No."
                                                            :isDisabled=true wire:model='CODE' />
                                                    @endif
                                                </div>
                                                <div class="col-md-4"
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif>
                                                    @if ($Modify && $AMOUNT == 0)
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled=false
                                                            wire:model.live='LOCATION_ID' />
                                                    @else
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled=true
                                                            wire:model.live='LOCATION_ID' />
                                                    @endif
                                                </div>
                                                <div class="col-md-4">
                                                    @if ($Modify)
                                                        <livewire:select-option name="OUTPUT_TAX_ID" titleName="Tax"
                                                            :options="$taxList" :zero="false" :isDisabled=false
                                                            wire:model='OUTPUT_TAX_ID' />
                                                    @else
                                                        <livewire:select-option name="OUTPUT_TAX_ID" titleName="Tax"
                                                            :options="$taxList" :zero="false" :isDisabled=true
                                                            wire:model='OUTPUT_TAX_ID' />
                                                    @endif
                                                </div>
                                                <div class="col-md-8">
                                                    @if ($Modify)
                                                        <livewire:text-input name="NOTES" titleName="Notes"
                                                            :isDisabled=false wire:model='NOTES' :vertical="false" />
                                                    @else
                                                        <livewire:text-input name="NOTES" titleName="Notes"
                                                            :isDisabled=true wire:model='NOTES' :vertical="false" />
                                                    @endif
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
                                                <button type="submit" class="btn btn-sm btn-primary"> <i
                                                        class="fa fa-floppy-o" aria-hidden="true"></i>
                                                    {{ $ID === 0 ? 'Pre-save' : 'Update' }}</button>

                                                @if ($ID > 0)
                                                    <button type="button" wire:click='updateCancel'
                                                        class="btn btn-sm btn-danger"><i class="fa fa-ban"
                                                            aria-hidden="true"></i> Cancel</button>
                                                @endif
                                            @else
                                                <button type="button" wire:click='getModify()'
                                                    class="btn btn-sm btn-info"
                                                    @if ($STATUS > 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                    <i class="fa fa-wrench" aria-hidden="true"></i> Modify
                                                </button>
                                                
                                                    <button type="button" wire:click='delete()'
                                                        class="btn btn-sm btn-danger"
                                                        wire:confirm="Are you sure you want to delete this?"
                                                        @if ($STATUS > 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                        <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                                    </button>
                                             
                                            @endif
                                        @endif
                                    </div>
                                    <div class="text-right col-6 col-md-6">
                                        @can('patient.service-charges.create')
                                            @if ($ID > 0 && $STATUS > 0)
                                                <a id="new" title="Create"
                                                    href="{{ route('patientsservice_charges_create') }}"
                                                    class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> New </a>
                                            @endif
                                        @endcan

                                        @if ($ID == 0)
                                            <input type="checkbox" wire:model.live = 'WALK_IN' />
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
    @if ($ID > 0 && !$Modify)
        <section class="content">
            <div class="container-fluid bg-light">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0 text-xs" wire:loading.class='loading-form'>
                                <ul class="nav text-xs nav-tabs" id="custom-tabs-four-tab" role="tablist">

                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'item') active @endif"
                                            id="custom-tabs-four-item-tab" wire:click="SelectTab('item')"
                                            data-toggle="pill" href="#custom-tabs-four-item" role="tab"
                                            aria-controls="custom-tabs-four-item" aria-selected="true">Items</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'payment') active @endif"
                                            id="custom-tabs-four-payment-tab" wire:click="SelectTab('payment')"
                                            data-toggle="pill" href="#custom-tabs-four-payment" role="tab"
                                            aria-controls="custom-tabs-four-payment" aria-selected="true">Payments</a>
                                    </li>
                                    <li wire:loading.delay>
                                        <span class="spinner"></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <div class="tab-pane fade @if ($tab == 'item') show active @endif"
                                        id="custom-tabs-four-item" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-item-tab">
                                        <div class="row">
                                            <div class="col-md-12"
                                                @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                                @if ($tab == 'item')
                                                    @livewire('ServiceCharge.ServiceChargeFormItems', ['SERVICE_CHARGES_ID' => $ID, 'STATUS' => $STATUS, 'TAX_ID' => $OUTPUT_TAX_ID, 'PATIENT_ID' => $PATIENT_ID, 'LOCATION_ID' => $LOCATION_ID, 'WALK_IN' => $WALK_IN, 'HEMO_ID' => $HEMO_ID])
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade @if ($tab == 'payment') show active @endif"
                                        id="custom-tabs-four-payment" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-payment-tab">
                                        <div class="row"
                                            @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <div class="col-md-12">
                                                @if ($tab == 'payment')
                                                    @livewire('ServiceCharge.Payments', ['SERVICE_CHARGES_ID' => $ID, 'PATIENT_ID' => $PATIENT_ID, 'LOCATION_ID' => $LOCATION_ID])
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-4 text-left">
                                        @if ($HEMO_ID > 0)
                                            <a target="_BLANK"
                                                href="{{ route('patientshemo_edit', ['id' => $HEMO_ID]) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                Treatment Details </a>

                                            <button type="button" class="btn btn-sm btn-info"
                                                wire:click='openForm()'>Agreement Form</button>
                                        @endif

                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-3 text-right">
                                                <label class="text-sm">Tax:</label>
                                                <label
                                                    class="text-info text-lg">{{ number_format($OUTPUT_TAX_AMOUNT, 2) }}</label>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <label class="text-sm">Total:</label>
                                                <label
                                                    class="text-primary text-lg">{{ number_format($AMOUNT, 2) }}</label>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <label class="text-sm">Payment:</label>
                                                <label
                                                    class="text-success text-lg">{{ number_format($AMOUNT - $BALANCE_DUE, 2) }}</label>

                                            </div>
                                            <div class="col-md-3 text-right">
                                                <label class="text-sm">Balance:</label>
                                                <label
                                                    class="text-danger text-lg">{{ number_format($BALANCE_DUE, 2) }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @livewire('ServiceCharge.AgreementFormModal');
</div>

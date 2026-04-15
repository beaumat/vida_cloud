<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <div class="row">
                                <div class="col-sm-6">
                                    {{ $ID == 0 ? 'Create' : '' }}
                                    <a class="text-white" href="{{ route('patientsphic_pay') }}"> Patient: Philhealth
                                        Payments </a>
                                </div>
                                <div class="col-sm-6 text-right">
                                    @if ($ID > 0)
                                        {{-- <i> {{ $STATUS_DESCRIPTION }}</i> --}}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body bg-light">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <livewire:select-option name="PATIENT_ID" titleName="Patient"
                                                :options="$contactList" :zero="true" isDisabled="{{ !$Modify }}"
                                                wire:model.live='PATIENT_ID' />



                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class='col-md-6'>
                                                            <livewire:number-input name="AMOUNT" titleName="GROSS INCOME"
                                                                isDisabled="{{ $Modify && $AMOUNT_APPLIED == 0 ? false : true }}"
                                                                wire:model.live.lazy.150ms='AMOUNT' />
                                                        </div>
                                                        <div class='col-md-6'>
                                                            @if ($showTax)
                                                                <livewire:number-input name="WTAX_AMOUNT"
                                                                    titleName="WTax (2%) Less: {{ number_format($WTAX_AMOUNT, 2) }}"
                                                                    isDisabled="{{ true }}"
                                                                    wire:model='LESS_AMOUNT' />
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-6">
                                                    <livewire:dropdown-option name="PAYMENT_METHOD_ID"
                                                        isDisabled="{{ !$Modify }}" titleName="Payment Method"
                                                        :options="$paymentMethodList" :zero="false"
                                                        wire:model.live='PAYMENT_METHOD_ID' />

                                                </div>
                                            </div>

                                            <div class="row">
                                                @if ($reloadType)
                                                    @if ($showReceiptNo)
                                                        <div class="col-md-6">
                                                            <livewire:text-input name="RECEIPT_REF_NO"
                                                                titleName=$TITLE_REF isDisabled="{{ !$Modify }}"
                                                                wire:model='RECEIPT_REF_NO' />
                                                        </div>
                                                    @endif
                                                    @if ($showReceiptDate)
                                                        <div class="col-md-6">
                                                            <livewire:date-input name="RECEIPT_DATE"
                                                                :titleName=$TITLE_DATE wire:model='RECEIPT_DATE'
                                                                isDisabled="{{ !$Modify }}" />
                                                        </div>
                                                    @endif
                                                @else
                                                    @if ($showReceiptNo)
                                                        <div class="col-md-6">
                                                            <livewire:text-input name="RECEIPT_REF_NO"
                                                                :titleName=$TITLE_REF isDisabled="{{ !$Modify }}"
                                                                wire:model='RECEIPT_REF_NO' />
                                                        </div>
                                                    @endif

                                                    @if ($showReceiptDate)
                                                        <div class="col-md-6">
                                                            <livewire:date-input name="RECEIPT_DATE"
                                                                :titleName=$TITLE_DATE wire:model='RECEIPT_DATE'
                                                                isDisabled="{{ !$Modify }}" />
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <livewire:date-input name="DATE" titleName="Date"
                                                        wire:model='DATE' :isDisabled="true" />
                                                </div>
                                                <div class="col-md-4">
                                                    <livewire:text-input name="Code" titleName="Reference No."
                                                        :isDisabled=true wire:model='CODE' />
                                                </div>
                                                <div class="col-md-4"
                                                
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif>
                                                    <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                        :options="$locationList" :zero="false"
                                                        isDisabled="{{ !$Modify && $AMOUNT == 0 ? false : true }}"
                                                        wire:model='LOCATION_ID' />

                                                </div>
                                                @if ($showCardNo)
                                                    <div class="col-md-6">
                                                        <livewire:text-input name="CARD_NO" titleName="Card No."
                                                            isDisabled="{{ !$Modify }}" wire:model='CARD_NO' />
                                                    </div>
                                                @endif

                                                @if ($showCardDateExpire)
                                                    <div class="col-md-6">
                                                        <livewire:date-input name="CARD_EXPIRY_DATE"
                                                            titleName="Card Expired" wire:model='CARD_EXPIRY_DATE'
                                                            isDisabled="{{ !$Modify }}" />

                                                    </div>
                                                @endif

                                                <div class="col-md-12">
                                                    <livewire:text-input name="NOTES" titleName="Notes"
                                                        isDisabled="{{ !$Modify }}" wire:model='NOTES'
                                                        :vertical="false" />
                                                </div>
                                                <div class="col-md-12">
                                                    @if ($reloadphcomboBoxList)
                                                        <livewire:select-option name="PHILHEALTH_ID1"
                                                            titleName="Philhealth" :options="$dataPhList" :zero="true"
                                                            isDisabled="{{ !$Modify }}"
                                                            wire:model.live='PHILHEALTH_ID' />
                                                    @else
                                                        <livewire:select-option name="PHILHEALTH_ID2"
                                                            titleName="Philhealth" :options="$dataPhList" :zero="true"
                                                            isDisabled="{{ !$Modify }}"
                                                            wire:model.live='PHILHEALTH_ID' />
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
                                            @if ($AMOUNT_APPLIED == 0)
                                                <button type="button" wire:click='getModify()'
                                                    class="btn btn-sm btn-info">
                                                    <i class="fa fa-wrench" aria-hidden="true"></i> Modify
                                                </button>
                                            @endif

                                            @if ($showFileName)
                                                @can('patient.payment.print')
                                                    <a target="_blank" href="{{ asset('storage/' . $FILE_PATH) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Preview
                                                    </a>
                                                @endcan
                                            @endif

                                            @if ($showFileName)
                                                @can('patient.payment.update')
                                                    @if (!$IS_CONFIRM)
                                                        <button type="button" wire:click='getConfirm()'
                                                            wire:confirm="Are you sure this guaranteed letter is confirm?"
                                                            class="btn btn-sm btn-info">
                                                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                                                            Confirm
                                                        </button>
                                                    @else
                                                        <label class="text-xs text-primary px-3">
                                                            <i>
                                                                Guarantee Letter Confirm on
                                                                <b class="text-info">{{ \Carbon\Carbon::parse($DATE_CONFIRM)->format('m/d/Y') }}
                                                                </b>
                                                            </i>
                                                        </label>
                                                    @endif
                                                @endif
                                            @endcan
                                        @endif
                                    </div>
                                    <div class="text-right col-6 col-md-6">
                            
                                        @can('patient.payment.create')
                                            @if ($ID > 0 && $STATUS > 0)
                                                <a id="new" title="Create"
                                                    href="{{ route('patientsphic_pay_create') }}"
                                                    class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> New </a>
                                            @endif
                                        @endcan

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid bg-light">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav text-sm nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-tabs-four-item-tab" data-toggle="pill"
                                        href="#custom-tabs-four-item" role="tab"
                                        aria-controls="custom-tabs-four-item" aria-selected="true">Service Charges</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane fade show active " id="custom-tabs-four-item" role="tabpanel"
                                    aria-labelledby="custom-tabs-four-item-tab">
                                    <div class="row"
                                        @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                        <div class="col-md-12"
                                            @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                            @livewire('PatientPayment.PatientPaymentCharges', ['PATIENT_PAYMENT_ID' => $ID, 'PATIENT_ID' => $PATIENT_ID, 'LOCATION_ID' => $LOCATION_ID, 'STATUS' => $STATUS, 'AMOUNT' => $AMOUNT, 'AMOUNT_APPLIED' => $AMOUNT_APPLIED, 'PHILHEALTH_ID' => $PHILHEALTH_ID])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6 text-left">
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            <label class="text-sm">Payment Applied:</label>
                                            <label
                                                class="text-primary text-lg">{{ number_format($AMOUNT_APPLIED, 2) }}</label>

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
    @livewire('PatientPayment.PaymentRecordModal')
</div>

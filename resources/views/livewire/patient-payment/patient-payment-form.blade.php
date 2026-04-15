<?php
use App\Services\UserServices;
?>
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
                                    <a class="text-white" href="{{ route('patientspayment') }}"> Patient: Cash/GL
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
                                                wire:model='PATIENT_ID' />

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <livewire:number-input name="AMOUNT" titleName="Amount"
                                                        isDisabled="{{ $Modify && $AMOUNT_APPLIED == 0 ? false : true }}"
                                                        wire:model='AMOUNT' />

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
                                                                titleName="{{ $TITLE_REF }}"
                                                                isDisabled="{{ !$Modify }}"
                                                                wire:model='RECEIPT_REF_NO' />
                                                        </div>
                                                    @endif
                                                    @if ($showReceiptDate)
                                                        <div class="col-md-6">
                                                            <livewire:date-input name="RECEIPT_DATE"
                                                                titleName="{{ $TITLE_DATE }}"
                                                                wire:model='RECEIPT_DATE'
                                                                isDisabled="{{ !$Modify }}" />
                                                        </div>
                                                    @endif
                                                @else
                                                    @if ($showReceiptNo)
                                                        <div class="col-md-6">
                                                            <livewire:text-input name="RECEIPT_REF_NO"
                                                                titleName="{{ $TITLE_REF }}"
                                                                isDisabled="{{ !$Modify }}"
                                                                wire:model='RECEIPT_REF_NO' />
                                                        </div>
                                                    @endif

                                                    @if ($showReceiptDate)
                                                        <div class="col-md-6">
                                                            <livewire:date-input name="RECEIPT_DATE"
                                                                titleName="{{ $TITLE_DATE }}"
                                                                wire:model='RECEIPT_DATE'
                                                                isDisabled="{{ !$Modify }}" />
                                                        </div>
                                                    @endif
                                                @endif

                                                <div class="col-md-6">
                                                    @if ($PAYMENT_METHOD_ID != 1)
                                                        @if ($Modify)
                                                            <livewire:select-option name="UNDEPOSITED_FUNDS_ACCOUNT_ID"
                                                                titleName="GL Accounts" :options="$accountList"
                                                                :zero="true" :isDisabled=false
                                                                wire:model='UNDEPOSITED_FUNDS_ACCOUNT_ID' />
                                                        @else
                                                            <livewire:select-option name="UNDEPOSITED_FUNDS_ACCOUNT_ID"
                                                                titleName="GL Accounts" :options="$accountList"
                                                                :zero="true" :isDisabled=true
                                                                wire:model='UNDEPOSITED_FUNDS_ACCOUNT_ID' />
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <livewire:date-input name="DATE" titleName="Date"
                                                        wire:model.live='DATE' isDisabled="{{ !$Modify }}" />
                                                </div>
                                                <div class="col-md-4">
                                                    <livewire:text-input name="Code" titleName="Reference No."
                                                        :isDisabled=true wire:model='CODE' />
                                                </div>
                                                <div class="col-md-4"
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif>

                                                    @if ($Modify)
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled="false"
                                                            wire:model='LOCATION_ID' />
                                                    @else
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled="true"
                                                            wire:model='LOCATION_ID' />
                                                    @endif


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

                                                @if ($showFileName && $FILE_PATH == '')
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="fileUpload" class="text-xs">PDF document file
                                                                @if ($PDF)
                                                                    <i class="fa fa-check-circle text-success"
                                                                        aria-hidden="true"></i>
                                                                @endif
                                                            </label>
                                                            <div class="input-group input-group-sm">
                                                                <div class="custom-file text-xs">
                                                                    <input type="file"
                                                                        class="custom-file-input text-xs"
                                                                        id="fileUpload" wire:model='PDF'>
                                                                    <label class="custom-file-label text-xs"
                                                                        for="fileUpload">
                                                                        @if ($PDF)
                                                                            {{ $PDF->getClientOriginalName() }}
                                                                        @else
                                                                            Choose file
                                                                        @endif
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                @if (!$Modify)
                                                                    <button class="btn btn-xs btn-primary mt-2">Upload
                                                                        File</button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
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
                                            @if ($REF_ID == 0)
                                                @if ($AMOUNT_APPLIED == 0 || UserServices::GetUserRightAccess('patient.payment.update'))
                                                    <button type="button" wire:click='getModify()'
                                                        class="btn btn-sm btn-info">
                                                        <i class="fa fa-wrench" aria-hidden="true"></i> Modify
                                                    </button>
                                                @endif
                                            @endif
                                            @if ($ID > 0)
                                                @if (UserServices::GetUserRightAccess('customer.invoice.view') &&
                                                        UserServices::GetUserRightAccess('customer.invoice.create'))
                                                    @if ($PAYMENT_METHOD_ID == 1)
                                                        @if ($AMOUNT == $AMOUNT_APPLIED)
                                                            @if ($REF_ID > 0)
                                                                <a href="{{ route('customerssales_receipt_edit', ['id' => $REF_ID]) }}"
                                                                    target="_BLANK" class="btn btn-success btn-sm ">
                                                                    <i class="fa fa-sticky-note-o"
                                                                        aria-hidden="true"></i>
                                                                    View Sales Receipt
                                                                </a>
                                                            @else
                                                                <button type="button" class="btn btn-success btn-sm"
                                                                    wire:click='makeSalesReceipt()'
                                                                    wire:confirm='Are you sure to make Sales Receipt?'>
                                                                    <i class="fa fa-sticky-note-o"
                                                                        aria-hidden="true"></i>
                                                                    Make Sales Receipt
                                                                </button>
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if ($REF_ID > 0)
                                                            <a href="{{ route('customersinvoice_edit', ['id' => $REF_ID]) }}"
                                                                target="_BLANK" class="btn btn-success btn-sm ">
                                                                <i class="fa fa-sticky-note-o" aria-hidden="true"></i>
                                                                View Invoice
                                                            </a>
                                                        @else
                                                            <button type="button" class="btn btn-success btn-sm"
                                                                wire:click='makeInvoice()'
                                                                wire:confirm='Are you sure to make invoice?'>
                                                                <i class="fa fa-sticky-note-o" aria-hidden="true"></i>
                                                                Make Invoice
                                                            </button>
                                                        @endif

                                                    @endif
                                                @endif
                                            @endif

                                            @if ($FILE_PATH)
                                                @can('patient.payment.print')
                                                    <a target="_blank" href="{{ asset('storage/' . $FILE_PATH) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Preview
                                                    </a>
                                                @endcan
                                            @endif

                                            @if ($FILE_PATH)
                                                @can('patient.audit')
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
                                        @if ($ID > 0)
                                            <button type="button" class="btn btn-dark btn-sm"
                                                wire:click="openPayment()">
                                                <i class="fa fa-money" aria-hidden="true"></i>
                                                Assistance Record
                                            </button>
                                        @endif
                                        @can('patient.payment.create')
                                            @if ($ID > 0 && $STATUS > 0)
                                                <a id="new" title="Create"
                                                    href="{{ route('patientspayment_create') }}"
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
                                            @livewire('PatientPayment.PatientPaymentCharges', ['PATIENT_PAYMENT_ID' => $ID, 'PATIENT_ID' => $PATIENT_ID, 'LOCATION_ID' => $LOCATION_ID, 'STATUS' => $STATUS, 'AMOUNT' => $AMOUNT, 'AMOUNT_APPLIED' => $AMOUNT_APPLIED, 'REF_ID' => $REF_ID])
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
    @livewire('Invoice.MakeInvoice')
    @livewire('SalesReceipt.MakeSalesReceipt')
    @livewire('PatientPayment.PaymentRecordModal')

</div>

@php
    use App\Services\UserServices;
@endphp
<div class="@if (!$IS_MODAL) content-wrapper @endif ">
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
                                    <a class="text-white" href="{{ route('customersinvoice') }}">
                                        Invoice
                                    </a>
                                </div>
                                <div class="col-sm-6 text-right">
                                    @if ($ID > 0)
                                        <i> {{ $STATUS_DESCRIPTION }}</i>
                                    @endif
                                </div>
                            </div>
                        </div>

                            <div class="card-body bg-light">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row"
                                                @if ($PATIENT_PAYMENT_ID > 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                <div class='col-12'>
                                                    @if ($Modify && $STATUS == 0)
                                                        <livewire:select-option-type name="CUSTOMER_ID1"
                                                            titleName="Customer" :options="$contactList" :zero="true"
                                                            :isDisabled="false" wire:model='CUSTOMER_ID' />
                                                    @else
                                                        <livewire:select-option-type name="CUSTOMER_ID2"
                                                            titleName="Customer" :options="$contactList" :zero="true"
                                                            :isDisabled="true" wire:model='CUSTOMER_ID' />
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    @if ($Modify)
                                                        <livewire:select-option name="PAYMENT_TERMS_ID"
                                                            :isDisabled="false" titleName="Payment Terms"
                                                            :options="$paymentTermList" :zero="false"
                                                            wire:model.live='PAYMENT_TERMS_ID' />
                                                    @else
                                                        <livewire:select-option name="PAYMENT_TERMS_ID"
                                                            :isDisabled="true" titleName="Payment Terms"
                                                            :options="$paymentTermList" :zero="false"
                                                            wire:model.live='PAYMENT_TERMS_ID' />
                                                    @endif
                                                </div>
                                                <div class="col-md-3">
                                                    <livewire:date-input name="DUE_DATE"
                                                        isDisabled="{{ !$Modify }}" titleName="Due Date"
                                                        wire:model='DUE_DATE' />

                                                </div>
                                                <div class="col-md-3">
                                                    <livewire:date-input name="DISCOUNT_DATE"
                                                        isDisabled="{{ !$Modify }}" titleName="Discount Date"
                                                        wire:model='DISCOUNT_DATE' />
                                                </div>
                                                <div class="col-md-3">
                                                    <livewire:text-input name="PO_NUMBER" titleName="PO Number"
                                                        isDisabled="{{ !$Modify }}" wire:model='PO_NUMBER'
                                                        :vertical="false" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    @if ($ID == 0 && auth()->user()->date_enabled)
                                                        <livewire:date-input name="DATE" titleName="Date"
                                                            wire:model.live='DATE' :isDisabled="false" />
                                                    @else
                                                        <livewire:date-input name="DATE" titleName="Date"
                                                            wire:model.live='DATE' :isDisabled="true" />
                                                    @endif
                                                </div>
                                                <div class="col-md-4">
                                                    <livewire:text-input name="Code" titleName="Reference No."
                                                        isDisabled="{{ !$Modify }}" wire:model='CODE' />
                                                </div>
                                                <div class="col-md-4"
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif>
                                                    @if ($Modify && $STATUS == 0)
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled="false"
                                                            wire:model.live='LOCATION_ID' />
                                                    @else
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled="true"
                                                            wire:model.live='LOCATION_ID' />
                                                    @endif
                                                </div>
                                                <div class="col-md-4">
                                                    @if ($Modify)
                                                        <livewire:select-option name="ACCOUNT_ID1" titleName="Account"
                                                            :options="$accountList" :zero="true" :isDisabled="false"
                                                            wire:model='ACCOUNTS_RECEIVABLE_ID' />
                                                    @else
                                                        <livewire:select-option name="ACCOUNT_ID2" titleName="Account"
                                                            :options="$accountList" :zero="true" :isDisabled="true"
                                                            wire:model='ACCOUNTS_RECEIVABLE_ID' />
                                                    @endif
                                                </div>
                                                <div class="col-md-3">
                                                    @if ($Modify)
                                                        <livewire:select-option name="OUTPUT_TAX_ID" titleName="Tax"
                                                            :options="$taxList" :zero="false" :isDisabled="false"
                                                            wire:model='OUTPUT_TAX_ID' />
                                                    @else
                                                        <livewire:select-option name="OUTPUT_TAX_ID" titleName="Tax"
                                                            :options="$taxList" :zero="false" :isDisabled="true"
                                                            wire:model='OUTPUT_TAX_ID' />
                                                    @endif
                                                </div>
                                                <div class="col-md-5">
                                                    <livewire:text-input name="NOTES" titleName="Notes"
                                                        isDisabled="{{ !$Modify }}" wire:model='NOTES'
                                                        :vertical="false" />

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        @if ($STATUS == 0 || $STATUS == 16)
                                            @if ($Modify)
                                                <button wire:click='save' class="btn btn-sm btn-primary"> <i
                                                        class="fa fa-floppy-o" aria-hidden="true"></i>
                                                    {{ $ID === 0 ? 'Pre-save' : 'Update' }}</button>
                                                @if ($ID > 0)
                                                    <button type="button" wire:click='updateCancel'
                                                        wire:confirm='Want to cancel?'
                                                        class="btn btn-sm btn-danger"><i class="fa fa-ban"
                                                            aria-hidden="true"></i> Cancel</button>
                                                @endif
                                            @else
                                                <button type="button" wire:click='getModify()'
                                                    class="btn btn-sm btn-info">
                                                    <i class="fa fa-wrench" aria-hidden="true"></i> Modify
                                                </button>
                                                <button type="button" wire:click='getPosted()'
                                                    class="btn btn-sm btn-warning"
                                                    wire:confirm="Are you sure you want to post?">
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Posted
                                                </button>
                                            @endif
                                        @endif

                                        @if (UserServices::GetUserRightAccess('customer.invoice.delete') && $STATUS == 16)
                                            <button wire:click='delete()'
                                                wire:confirm="Are you sure you want to delete this?"
                                                class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash" aria-hidden="true"></i> Delete
                                            </button>
                                        @endif

                                        @if ($STATUS == 15)
                                            @can('customer.invoice.update')
                                                <button type="button" wire:click='getUnposted()'
                                                    class="btn btn-sm btn-secondary"
                                                    wire:confirm="Are you sure you want to unpost?">
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Unpost
                                                </button>
                                            @endcan
                                        @endif
                                    </div>
                                    <div class="text-right col-6 col-md-6">
                                        @if ($STATUS != 16)
                                            @if ($ID > 0 && $STATUS > 0)
                                                @can('customer.invoice.print')
                                                    <button type="button" wire:click='OpenJournal()'
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fa fa-file-text-o" aria-hidden="true"></i> Journal
                                                    </button>
                                                    <a type="button" target="_BLANK"
                                                        href="{{ route('customersinvoice_print', ['id' => $ID]) }}"
                                                        class="btn btn-sm btn-dark">
                                                        <i class="fa fa-print" aria-hidden="true"></i> Print
                                                    </a>
                                                @endcan
                                                @can('customer.invoice.create')
                                                    <a id="new" title="Create"
                                                        href="{{ route('customersinvoice_create') }}"
                                                        class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> New
                                                    </a>
                                                @endcan
                                            @endif

                                        @endif
                                    </div>
                                </div>
                            </div>


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
                            <div class="card-header p-0 border-bottom-0 text-xs" wire:loading.class='loading-form'>
                                <ul class="nav text-xs nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'item') active @endif"
                                            id="custom-tabs-four-item-tab" wire:click="SelectTab('item')"
                                            data-toggle="pill" href="#custom-tabs-four-item" role="tab"
                                            aria-controls="custom-tabs-four-item" aria-selected="true">Items</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'tax') active @endif"
                                            id="custom-tabs-four-tax-tab" wire:click="SelectTab('tax')"
                                            data-toggle="pill" href="#custom-tabs-four-tax" role="tab"
                                            aria-controls="custom-tabs-four-tax" aria-selected="true">Tax Credits
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'payment') active @endif"
                                            id="custom-tabs-four-payment-tab" wire:click="SelectTab('payment')"
                                            data-toggle="pill" href="#custom-tabs-four-payment" role="tab"
                                            aria-controls="custom-tabs-four-payment" aria-selected="true">
                                            Payments</a>
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
                                        <div class="row"
                                            @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <div class="col-md-12"
                                                @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                                @if ($tab == 'item')
                                                    @livewire('Invoice.InvoiceFormItems', ['INVOICE_ID' => $ID, 'STATUS' => $STATUS, 'TAX_ID' => $OUTPUT_TAX_ID, 'LOCATION_ID' => $LOCATION_ID])
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade @if ($tab == 'tax') show active @endif"
                                        id="custom-tabs-four-tax" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-tax-tab">
                                        <div class="row"
                                            @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <div class="col-md-12"
                                                @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                                @if ($tab == 'tax')
                                                    @livewire('Invoice.TaxCredit', ['INVOICE_ID' => $ID, 'CUSTOMER_ID' => $CUSTOMER_ID, 'LOCATION_ID' => $LOCATION_ID, 'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID, 'INVOICE_STATUS_ID' => $STATUS, 'AMOUNT' => $AMOUNT])
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade @if ($tab == 'payment') show active @endif"
                                        id="custom-tabs-four-payment" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-payment-tab">
                                        <div class="row"
                                            @if ($ID === 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                            <div class="col-md-12"
                                                @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                                @if ($tab == 'payment')
                                                    @livewire('Invoice.ReceivedPayment', ['INVOICE_ID' => $ID, 'CUSTOMER_ID' => $CUSTOMER_ID, 'LOCATION_ID' => $LOCATION_ID, 'ACCOUNTS_RECEIVABLE_ID' => $ACCOUNTS_RECEIVABLE_ID, 'INVOICE_STATUS_ID' => $STATUS])
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-4 text-left">
                                        <div class="row">
                                            <div class="col-md-4">
                                                @if ($BILL_ID > 0)
                                                    <a class="btn btn-sm btn-info"
                                                        href="{{ route('vendorsbills_edit', ['id' => $BILL_ID]) }}"
                                                        target="_blank">
                                                        <i class="fa fa-file"></i> Doctor Bills
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                            </div>
                                        </div>
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
    @livewire('AccountJournal.AccountJournalModal')
</div>

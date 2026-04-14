<div class="@if (!$IS_MODAL) content-wrapper @endif">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>
    <section class="@if (!$IS_MODAL) content @endif">
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-md-12">
                    <div class="card">
                        <div class="pt-1 pb-1 card-header bg-sky">
                            <div class="row">
                                <div class="col-sm-6">
                                    <a class="text-white" href="{{ route('customerssales_receipt') }}">
                                        Sales Receipt
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
                                        <div class="col-md-6">
                                            <div class='form-group'
                                                @if ($PATIENT_PAYMENT_ID > 0) style="opacity: 0.5;pointer-events: none;" @endif>
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


                                            <div class="row">
                                                <div class="col-md-4">
                                                    @if ($Modify)
                                                        <livewire:select-option name="PAYMENT_METHOD_ID"
                                                            :isDisabled="false" titleName="Payment Method"
                                                            :options="$paymentMethodList" :zero="false"
                                                            wire:model.live='PAYMENT_METHOD_ID' />
                                                    @else
                                                        <livewire:select-option name="PAYMENT_METHOD_ID"
                                                            :isDisabled="true" titleName="Payment Method"
                                                            :options="$paymentMethodList" :zero="false"
                                                            wire:model.live='PAYMENT_METHOD_ID' />
                                                    @endif
                                                </div>

                                                <div class="col-md-3">
                                                    <livewire:text-input name="PAYMENT_REF_NO"
                                                        titleName="{{ $TITLE_REF }}" isDisabled="{{ !$Modify }}"
                                                        wire:model='PAYMENT_REF_NO' :vertical="false" />
                                                </div>
                                                <div class="col-md-4">
                                                    @if ($showCardNo)
                                                        <livewire:text-input name="CARD_NO" titleName="Card No."
                                                            isDisabled="{{ !$Modify }}" wire:model='CARD_NO'
                                                            :vertical="false" />
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <livewire:date-input name="DATE" titleName="Date"
                                                        wire:model.live='DATE' :isDisabled="true" />
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
                                                @if ($BANK_MODE)
                                                    <div class="col-md-4">
                                                        @if ($Modify)
                                                            <livewire:select-option name="ACCOUNT_ID1"
                                                                titleName="Deposit to Bank Account" :options="$accountList"
                                                                :zero="true" :isDisabled="false"
                                                                wire:model='UNDEPOSITED_FUNDS_ACCOUNT_ID' />
                                                        @else
                                                            <livewire:select-option name="ACCOUNT_ID2"
                                                                titleName="Deposit to Bank Account" :options="$accountList"
                                                                :zero="true" :isDisabled="true"
                                                                wire:model='UNDEPOSITED_FUNDS_ACCOUNT_ID' />
                                                        @endif
                                                    </div>
                                                @endif
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
                                                <button type="submit" class="btn btn-sm btn-primary"> <i
                                                        class="fa fa-floppy-o" aria-hidden="true"></i>
                                                    {{ $ID === 0 ? ($PATIENT_PAYMENT_ID > 0 ? 'Save & Post' : 'Pre-save') : 'Update' }}</button>
                                                @if ($ID > 0)
                                                    <button type="button" wire:click='updateCancel'
                                                        wire:confirm='Want to cancel?' class="btn btn-sm btn-danger"><i
                                                            class="fa fa-ban" aria-hidden="true"></i> Cancel</button>
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

                                        @if ($STATUS == 15)
                                            @can('customer.invoice.update')
                                                <button type="button" wire:click='getUnposted()'
                                                    class="btn btn-sm btn-secondary"
                                                    wire:confirm="Are you sure you want to unpost?">
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Unpost
                                                </button>
                                            @endcan
                                        @endif
                                        @if ($STATUS == 16)
                                            @can('customer.invoice.delete')
                                                <button type="button" wire:click='delete()'
                                                    class="btn btn-sm btn-danger"
                                                    wire:confirm="Are you sure you want to delete?">
                                                    <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                                </button>
                                            @endcan
                                        @endif


                                    </div>
                                    <div class="text-right col-6 col-md-6">

                                        @if ($DEPOST_ID > 0)
                                            <a id="bankdeposit" target="_blank" title="Deposit"
                                                href="{{ route('bankingdeposit_edit', ['id' => $DEPOST_ID]) }}"
                                                class="btn btn-info btn-sm"> <i class="fas fa-university"></i> Deposit
                                            </a>
                                        @endif

                                        @if ($STATUS != 16)
                                            @if ($ID > 0 && $STATUS > 0)
                                                <button type="button" wire:click='OpenJournal()'
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fa fa-file-text-o" aria-hidden="true"></i> Journal
                                                </button>
                                                </button>
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
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav text-xs nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'item') active @endif"
                                            id="custom-tabs-four-item-tab" wire:click="SelectTab('item')"
                                            data-toggle="pill" href="#custom-tabs-four-item" role="tab"
                                            aria-controls="custom-tabs-four-item" aria-selected="true">Items</a>
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
                                                @livewire('SalesReceipt.SalesReceiptFormItems', ['SALES_RECEIPT_ID' => $ID, 'STATUS' => $STATUS, 'TAX_ID' => $OUTPUT_TAX_ID, 'LOCATION_ID' => $LOCATION_ID])
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
                                            </div>
                                            <div class="col-md-4">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-9 text-right">
                                                <label class="text-sm">Tax:</label>
                                                <label
                                                    class="text-info text-lg">{{ number_format($OUTPUT_TAX_AMOUNT, 2) }}</label>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <label class="text-sm">Total:</label>
                                                <label
                                                    class="text-primary text-lg">{{ number_format($AMOUNT, 2) }}</label>
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

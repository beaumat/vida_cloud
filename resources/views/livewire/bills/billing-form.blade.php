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
                                    <a class="text-white" href="{{ route('vendorsbills') }}"> Bills </a>
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
                                            <livewire:select-option-type name="VENDOR_ID" titleName="Vendor"
                                                :options="$vendorList" :zero="true" isDisabled="{{ !$Modify }}"
                                                wire:model='VENDOR_ID' />
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
                                                        isDisabled="{{ !$Modify }}" titleName="Dua Date"
                                                        wire:model='DUE_DATE' />
                                                </div>
                                                <div class="col-md-6">
                                                    @if ($Modify)
                                                        <livewire:select-option name="ACCOUNT_ID1" titleName="Account"
                                                            :options="$accountList" :zero="true" :isDisabled="false"
                                                            wire:model='ACCOUNTS_PAYABLE_ID' />
                                                    @else
                                                        <livewire:select-option name="ACCOUNT_ID2" titleName="Account"
                                                            :options="$accountList" :zero="true" :isDisabled="true"
                                                            wire:model='ACCOUNTS_PAYABLE_ID' />
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    @if (($ID == 0 && auth()->user()->date_enabled) || ($Modify && auth()->user()->date_enabled))
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
                                                    <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                        :options="$locationList" :zero="false"
                                                        isDisabled="{{ !$Modify }}" wire:model='LOCATION_ID' />
                                                </div>
                                                <div class="col-md-4">
                                                    @if ($Modify)
                                                        <livewire:select-option name="INPUT_TAX_ID" titleName="Tax"
                                                            :options="$taxList" :zero="false" :isDisabled="false"
                                                            wire:model='INPUT_TAX_ID' />
                                                    @else
                                                        <livewire:select-option name="INPUT_TAX_ID" titleName="Tax"
                                                            :options="$taxList" :zero="false" :isDisabled="true"
                                                            wire:model='INPUT_TAX_ID' />
                                                    @endif
                                                </div>
                                                <div class="col-md-8">
                                                    <livewire:text-input name="NOTES" titleName="Notes"
                                                        isDisabled="{{ !$Modify }}" wire:model='NOTES'
                                                        :vertical="false" />
                                                </div>
                                                @if ($Modify)
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="fileUpload" class="text-xs">PDF/Image document
                                                                file
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
                                        @if ($STATUS == $openStatus || $STATUS == 16)
                                            @if ($Modify)
                                                <button type="button" wire:click='save()' class="btn btn-sm btn-primary"> <i
                                                        class="fa fa-floppy-o" aria-hidden="true"></i>
                                                    {{ $ID === 0 ? 'Pre-save' : 'Update' }}</button>

                                                @if ($ID > 0)
                                                    <button type="button" wire:click='updateCancel()' name="cancelbtn"
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
                                                    wire:confirm='Are you sure you want to post?'>
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Posted
                                                </button>
                                                @can('vendor.bill.delete')
                                                    <button type="button" wire:click='delete()'
                                                        class="btn btn-sm btn-danger"
                                                        wire:confirm='Are you sure you want to delete?'>
                                                        <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                                    </button>
                                                @endcan
                                            @endif
                                        @endif
                                        @if ($STATUS == 15)
                                            @can('vendor.bill.update')
                                                <button type="button" wire:click='getUnposted()'
                                                    class="btn btn-sm btn-secondary"
                                                    wire:confirm="Are you sure you want to unpost?">
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Unpost
                                                </button>
                                            @endcan
                                        @endif
                                    </div>
                                    <div class="text-right col-md-6 col-6">
                                        @if ($showFileName)
                                            @if ($ID > 0)
                                                @can('vendor.bill.print')
                                                    <a target="_blank" href="{{ asset('storage/' . $FILE_PATH) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Preview
                                                    </a>
                                                @endcan
                                                @can('vendor.bill.update')
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
                                                                File Confirm on
                                                                <b class="text-info">{{ \Carbon\Carbon::parse($DATE_CONFIRM)->format('m/d/Y') }}
                                                                </b>
                                                            </i>
                                                        </label>
                                                    @endif
                                                @endcan
                                            @endif
                                        @endif
                                        @if ($STATUS > 0)
                                            @if ($STATUS != 16 && $ID > 0)
                                                @can('vendor.bill.print')
                                                    <a type="button" target="_BLANK"
                                                        href="{{ route('vendorsbills_print', ['id' => $ID]) }}"
                                                        class="btn btn-sm btn-dark">
                                                        <i class="fa fa-print" aria-hidden="true"></i> Print
                                                    </a>
                                                    <button type="button" wire:click='OpenJournal()'
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fa fa-file-text-o" aria-hidden="true"></i> Journal
                                                    </button>
                                                @endcan
                                            @endif
                                            @can('vendor.bill.create')
                                                <a id="new" title="Create"
                                                    href="{{ route('vendorsbills_create') }}"
                                                    class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> New </a>
                                            @endcan
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
                                    @if ($useAccount)
                                        <li class="nav-item">
                                            <a class="nav-link @if ($tab == 'account') active @endif"
                                                id="custom-tabs-four-account-tab" wire:click="SelectTab('account')"
                                                data-toggle="pill" href="#custom-tabs-four-account" role="tab"
                                                aria-controls="custom-tabs-four-account"
                                                aria-selected="true">Expenses</a>
                                        </li>
                                    @endif

                                    <li wire:loading.delay>
                                        <span class="spinner"></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body"
                                @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <div class="tab-pane fade @if ($tab == 'item') show active @endif "
                                        id="custom-tabs-four-item" role="tabpanel">
                                        @if ($tab == 'item')
                                            @livewire('Bills.BillingFormItems', ['BILL_ID' => $ID, 'STATUS' => $STATUS, 'TAX_ID' => $INPUT_TAX_ID, 'LOCATION_ID' => $LOCATION_ID, 'DATE' => $DATE])
                                        @endif
                                    </div>
                                    @if ($useAccount)
                                        <div class="tab-pane fade @if ($tab == 'account') show active @endif "
                                            id="custom-tabs-four-account" role="tabpanel">
                                            @if ($tab == 'account')
                                                @livewire('Bills.BillingFormAccounts', ['BILL_ID' => $ID, 'STATUS' => $STATUS, 'TAX_ID' => $INPUT_TAX_ID, 'LOCATION_ID' => $LOCATION_ID, 'DATE' => $DATE])
                                            @endif
                                        </div>
                                    @endif

                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-4 text-left">
                                        <div class="row">
                                            <div class="col-md-4">
                                                @livewire('Bills.PurchaseOrderListPromp', ['VENDOR_ID' => $VENDOR_ID, 'BILL_ID' => $ID, 'LOCATION_ID' => $LOCATION_ID, 'STATUS' => $STATUS])
                                            </div>
                                            <div class="col-md-4">
                                                @livewire('Bills.BillPaymentModal', ['BILL_ID' => $ID])
                                            </div>
                                            <div class="col-md-4">
                                                @if ($PHILHEALTH_ID > 0)
                                                    <a class="btn btn-success btn-xs w-100" target="_blank"
                                                        href="{{ route('patientsphic_edit', ['id' => $PHILHEALTH_ID]) }}">
                                                        Philhealth/SOA
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-3 text-right">
                                                <label class="text-sm">Input Tax:</label>
                                                <label
                                                    class="text-info text-lg">{{ number_format($INPUT_TAX_AMOUNT, 2) }}</label>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <label class="text-sm">Total:</label>
                                                <label
                                                    class="text-primary text-lg">{{ number_format($AMOUNT, 2) }}</label>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <label class="text-sm">Payment:</label>
                                                <label
                                                    class="text-purple text-lg">{{ number_format($PAYMENT, 2) }}</label>
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

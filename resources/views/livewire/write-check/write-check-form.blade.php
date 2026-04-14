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
                                    <a class="text-white" href="{{ route('bankingmake_cheque') }}"> Pay by Check </a>
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
                                            <div class="row">
                                                <div class="col-md-12">
                                                    @if ($Modify)
                                                        <livewire:select-option name="BANK_ACCOUNT_ID"
                                                            titleName="Bank Account" :options="$accountList" :zero="true"
                                                            :isDisabled=false wire:model='BANK_ACCOUNT_ID' />
                                                    @else
                                                        <livewire:select-option name="BANK_ACCOUNT_ID"
                                                            titleName="Bank Account" :options="$accountList" :zero="true"
                                                            :isDisabled=true wire:model='BANK_ACCOUNT_ID' />
                                                    @endif
                                                </div>
                                                <div class="col-md-12">
                                                    @if ($Modify && $AMOUNT_APPLIED == 0)
                                                        <livewire:select-option-type name="PAY_TO_ID" titleName="Pay To"
                                                            :options="$contactList" :zero="true" :isDisabled=false
                                                            wire:model='PAY_TO_ID' />
                                                    @else
                                                        <livewire:select-option-type name="PAY_TO_ID" titleName="Pay To"
                                                            :options="$contactList" :zero="true" :isDisabled=true
                                                            wire:model='PAY_TO_ID' />
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <livewire:date-input name="DATE" titleName="Date"
                                                        wire:model='DATE' :isDisabled="true" />
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
                                                            wire:model='LOCATION_ID' />
                                                    @else
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled=true
                                                            wire:model='LOCATION_ID' />
                                                    @endif
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
                                        @if ($STATUS == 0 || $STATUS == 16)
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
                                            @can('banking.make-cheque.update')
                                                <button type="button" wire:click='getUnposted()'
                                                    class="btn btn-sm btn-secondary"
                                                    wire:confirm="Are you sure you want to unpost?">
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Unpost
                                                </button>
                                            @endcan
                                        @endif
                                    </div>
                                    <div class="text-right col-6 col-md-6">
                                        @if ($STATUS == 15)
                                            @if ($ID > 0)
                                                <button type="button" wire:click='OpenJournal()'
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fa fa-file-text-o" aria-hidden="true"></i> Journal
                                                </button>
                                                @can('banking.make-cheque.create')
                                                    <a id="new" title="Create"
                                                        href="{{ route('bankingmake_cheque_create') }}"
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
                            <div class="card-header p-0 border-bottom-0 text-xs" wire:loading.class='loading-form'>
                                <ul class="nav text-xs nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'item') active @endif"
                                            id="custom-tabs-four-item-tab" wire:click="SelectTab('item')"
                                            data-toggle="pill" href="#custom-tabs-four-item" role="tab"
                                            aria-controls="custom-tabs-four-item" aria-selected="true">Items</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link @if ($tab == 'account') active @endif"
                                            id="custom-tabs-four-account-tab" wire:click="SelectTab('account')"
                                            data-toggle="pill" href="#custom-tabs-four-account" role="tab"
                                            aria-controls="custom-tabs-four-account" aria-selected="true">Expenses</a>
                                    </li>
                                    <li wire:loading.delay>
                                        <span class="spinner"></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <div class="tab-pane fade @if ($tab == 'item') show active @endif "
                                        id="custom-tabs-four-item" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12"
                                                @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                                @if ($tab == 'item')
                                                    @livewire('WriteCheck.WriteCheckFormItems', ['CHECK_ID' => $ID, 'STATUS' => $STATUS, 'TAX_ID' => $INPUT_TAX_ID, 'LOCATION_ID' => $LOCATION_ID, 'DATE' => $DATE])
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade @if ($tab == 'account') show active @endif "
                                        id="custom-tabs-four-account" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12"
                                                @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                                @if ($tab == 'account')
                                                    @livewire('WriteCheck.WriteCheckFormAccounts', ['CHECK_ID' => $ID, 'STATUS' => $STATUS, 'TAX_ID' => $INPUT_TAX_ID, 'LOCATION_ID' => $LOCATION_ID, 'DATE' => $DATE])
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-4 text-left">

                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-3 text-right">
                                                <label class="text-sm">Input Tax:</label>
                                                <label class="text-info text-lg">
                                                    {{ number_format($INPUT_TAX_AMOUNT, 2) }}
                                                </label>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <label class="text-sm">Items:</label>
                                                <label class="text-orange text-lg">
                                                    {{ number_format($TOTAL_ITEMS, 2) }}
                                                </label>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <label class="text-sm">Expenses:</label>
                                                <label class="text-orange text-lg">
                                                    {{ number_format($TOTAL_EXPENSES, 2) }}
                                                </label>
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

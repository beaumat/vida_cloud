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
                                    <a class="text-white" href="{{ route('bankingfund_transfer') }}"> Fund Transfer
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
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-header card-primary text-primary text-sm">
                                                Received
                                            </div>
                                            <div class="card-body">
                                                <div class="container">
                                                    <div class='row'>
                                                        <div class="col-md-6">
                                                            @if ($Modify)
                                                                <livewire:number-input name="AMOUNT"
                                                                    titleName="Amount Fund" :isDisabled=false
                                                                    wire:model='AMOUNT' />
                                                            @else
                                                                <livewire:number-input name="AMOUNT" titleName="Amount"
                                                                    :isDisabled=true wire:model='AMOUNT' />
                                                            @endif
                                                        </div>
                                                        <div class="col-md-12">
                                                            @if ($Modify)
                                                                <livewire:select-option name="TO_ACCOUNT_ID"
                                                                    titleName="To Account" :options="$toAccountList"
                                                                    :zero="true" :isDisabled=false
                                                                    wire:model='TO_ACCOUNT_ID' />
                                                            @else
                                                                <livewire:select-option name="TO_ACCOUNT_ID"
                                                                    titleName="To Account" :options="$toAccountList"
                                                                    :zero="true" :isDisabled=true
                                                                    wire:model='TO_ACCOUNT_ID' />
                                                            @endif
                                                        </div>
                                                        <div class="col-md-12">
                                                            @if ($Modify)
                                                                <livewire:select-option name="TO_LOCATION_ID"
                                                                    titleName="To Location" :options="$toLocationList"
                                                                    :zero="true" :isDisabled=false
                                                                    wire:model='TO_LOCATION_ID' />
                                                            @else
                                                                <livewire:select-option name="TO_LOCATION_ID"
                                                                    titleName="To Location" :options="$toLocationList"
                                                                    :zero="true" :isDisabled=true
                                                                    wire:model='TO_LOCATION_ID' />
                                                            @endif
                                                        </div>

                                                        <div class="col-md-12">
                                                            @if ($Modify)
                                                                <livewire:select-option name="TO_NAME_ID"
                                                                    titleName="To Name" :options="$toContactList"
                                                                    :zero="true" :isDisabled=false
                                                                    wire:model='TO_NAME_ID' />
                                                            @else
                                                                <livewire:select-option name="TO_NAME_ID"
                                                                    titleName="To Name" :options="$toContactList"
                                                                    :zero="true" :isDisabled=true
                                                                    wire:model='TO_NAME_ID' />
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-header card-primary text-primary text-sm">
                                                Spend
                                            </div>
                                            <div class="card-body">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col-md-12">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col-md-12">
                                                            @if ($Modify)
                                                                <livewire:select-option name="FROM_ACCOUNT_ID"
                                                                    titleName="From Account" :options="$fromAccountList"
                                                                    :zero="true" :isDisabled=false
                                                                    wire:model='FROM_ACCOUNT_ID' />
                                                            @else
                                                                <livewire:select-option name="FROM_ACCOUNT_ID"
                                                                    titleName="From Account" :options="$fromAccountList"
                                                                    :zero="true" :isDisabled=true
                                                                    wire:model='FROM_ACCOUNT_ID' />
                                                            @endif
                                                        </div>
                                                        <div class="col-md-12"
                                                            @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif>
                                                            @if ($Modify)
                                                                <livewire:select-option name="FROM_LOCATION_ID"
                                                                    titleName="From Location" :options="$fromLocationList"
                                                                    :zero="true" :isDisabled=false
                                                                    wire:model='FROM_LOCATION_ID' />
                                                            @else
                                                                <livewire:select-option name="FROM_LOCATION_ID"
                                                                    titleName="From Location" :options="$fromLocationList"
                                                                    :zero="true" :isDisabled=true
                                                                    wire:model='FROM_LOCATION_ID' />
                                                            @endif
                                                        </div>

                                                        <div class="col-md-12">
                                                            @if ($Modify)
                                                                <livewire:select-option name="FROM_NAME_ID"
                                                                    titleName="From Name" :options="$fromContactList"
                                                                    :zero="true" :isDisabled=false
                                                                    wire:model='FROM_NAME_ID' />
                                                            @else
                                                                <livewire:select-option name="FROM_NAME_ID"
                                                                    titleName="From Name" :options="$fromContactList"
                                                                    :zero="true" :isDisabled=true
                                                                    wire:model='FROM_NAME_ID' />
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-header card-primary text-primary text-sm">
                                                Transaction
                                            </div>
                                            <div class="card-body">
                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            @if ($ID == 0 && auth()->user()->date_enabled)
                                                                <livewire:date-input name="DATE" titleName="Date"
                                                                    wire:model.live='DATE' :isDisabled="false" />
                                                            @else
                                                                <livewire:date-input name="DATE" titleName="Date"
                                                                    wire:model.live='DATE' :isDisabled="true" />
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6">
                                                            @if ($Modify)
                                                                <livewire:text-input name="Code"
                                                                    titleName="Reference No." :isDisabled=false
                                                                    wire:model='CODE' />
                                                            @else
                                                                <livewire:text-input name="Code"
                                                                    titleName="Reference No." :isDisabled=true
                                                                    wire:model='CODE' />
                                                            @endif
                                                        </div>
                                                        <div class="col-md-12">
                                                            @if ($Modify)
                                                                <livewire:select-option
                                                                    name="INTER_LOCATION_ACCOUNT_ID"
                                                                    titleName="Inter Location Account"
                                                                    :options="$interLocationAccountList" :zero="true"
                                                                    :isDisabled=false
                                                                    wire:model='INTER_LOCATION_ACCOUNT_ID' />
                                                            @else
                                                                <livewire:select-option
                                                                    name="INTER_LOCATION_ACCOUNT_ID"
                                                                    titleName="Inter Location Account"
                                                                    :options="$interLocationAccountList" :zero="true"
                                                                    :isDisabled=true
                                                                    wire:model='INTER_LOCATION_ACCOUNT_ID' />
                                                            @endif
                                                        </div>
                                                        <div class="col-md-12">
                                                            @if ($Modify)
                                                                <livewire:text-input name="NOTES" titleName="Notes"
                                                                    :isDisabled=false wire:model='NOTES'
                                                                    :vertical="false" />
                                                            @else
                                                                <livewire:text-input name="NOTES" titleName="Notes"
                                                                    :isDisabled=true wire:model='NOTES'
                                                                    :vertical="false" />
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer" wire:loading.class='loading-form'>
                            <div class="row">

                                <div class="col-md-6 col-6">
                                    @if ($STATUS != 15 || $STATUS == 16)
                                        @if ($Modify)
                                            <button type="button" wire:click='save()'
                                                class="btn btn-sm btn-primary"> <i class="fa fa-floppy-o"
                                                    aria-hidden="true"></i>
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
                                            <button type="button" wire:click='posted()'
                                                class="btn btn-sm btn-warning">
                                                <i class="fa fa-cloud-upload" aria-hidden="true"></i> Posted
                                            </button>
                                        @endif
                                    @endif

                                    @if ($STATUS == 15)
                                        @if ($IS_REVERSE == false)
                                            @can('banking.fund-transfer.update')
                                                <button type="button" wire:click='getUnposted()'
                                                    class="btn btn-sm btn-secondary"
                                                    wire:confirm="Are you sure you want to unpost?">
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Unpost
                                                </button>
                                            @endcan
                                        @endif
                                        @can('banking.fund-transfer.reverse')
                                            <button type="button" wire:click='getReverse()'
                                                class="btn btn-sm btn-success">
                                                <i class="fa fa-cloud-upload" aria-hidden="true"></i> Reverse
                                            </button>
                                        @endcan
                                        @if ($IS_REVERSE)
                                            <button type="button" wire:click='OpenJournalReverse()'
                                                class="btn btn-sm btn-warning">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i> Journal Reverse
                                            </button>
                                        @endif
                                    @endif
                                </div>
                                <div class="text-right col-6 col-md-6">
                                    @if ($STATUS == 15)
                                        @can('banking.fund-transfer.print')
                                            <a type="button" target="_BLANK"
                                                href="{{ route('bankingfund_transfer_print', ['id' => $ID]) }}"
                                                class="btn btn-sm btn-dark">
                                                <i class="fa fa-print" aria-hidden="true"></i> Print
                                            </a>

                                            <button type="button" wire:click='OpenJournal()'
                                                class="btn btn-sm btn-warning">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i> Journal
                                            </button>
                                        @endcan
                                        @can('banking.fund-transfer.create')
                                            <a id="new" title="Create"
                                                href="{{ route('bankingfund_transfer_credit') }}"
                                                class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> New </a>
                                        @endcan
                                    @endif
                                </div>
                                <div class="col-12" wire:loading.delay>
                                    <span class="spinner"></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    @if ($ID > 0)
        @livewire('AccountJournal.AccountJournalModal')
        @livewire('FundTransfer.ReverseForm', ['id' => $ID])
    @endif
</div>

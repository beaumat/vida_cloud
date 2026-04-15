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
                                    <a class="text-white" href="{{ route('bankingbank_recon') }}"> Bank Reconciliation
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card card-body">
                                            <label class="font-weight-bold text-sm text-primary">
                                                Select an account to reconcile, and then enter the ending balance from
                                                your account statement
                                            </label>
                                            <div class="row">
                                                <div class="col-md-6 col-6">
                                                    @if ($Modify && $ID == 0)
                                                        <livewire:select-option name="ACCOUNT_ID1"
                                                            titleName="Bank Account" :options="$accountList" :zero="true"
                                                            :isDisabled="false" wire:model.live='ACCOUNT_ID' />
                                                    @else
                                                        <livewire:select-option name="ACCOUNT_ID2"
                                                            titleName="Bank Account" :options="$accountList" :zero="true"
                                                            :isDisabled="true" wire:model.live='ACCOUNT_ID' />
                                                    @endif
                                                </div>
                                                <div class="col-md-3 col-3">
                                                    <livewire:number-input name="BEGINNING_BALANCE"
                                                        titleName="Beginning Balance" isDisabled="{{ !$Modify }}"
                                                        wire:model='BEGINNING_BALANCE' />
                                                </div>
                                                <div class="col-md-3 col-3">
                                                    <livewire:number-input name="ENDING_BALANCE"
                                                        titleName="Ending Balance" isDisabled="{{ !$Modify }}"
                                                        wire:model='ENDING_BALANCE' />
                                                </div>
                                                <div class="col-md-6 col-6">
                                                    @if ($Modify && $ID == 0)
                                                        @if ($bankStateRefresh)
                                                            <livewire:select-option name="BANK_STATEMENT_ID1"
                                                                titleName="Bank Statement Upload" :options="$bankStatementList"
                                                                :zero="true" :isDisabled="false"
                                                                wire:model.live='BANK_STATEMENT_ID' />
                                                        @else
                                                            <livewire:select-option name="BANK_STATEMENT_ID0"
                                                                titleName="Bank Statement Upload" :options="$bankStatementList"
                                                                :zero="true" :isDisabled="false"
                                                                wire:model.live='BANK_STATEMENT_ID' />
                                                        @endif
                                                    @else
                                                        <livewire:select-option name="BANK_STATEMENT_ID2"
                                                            titleName="Bank Statement Upload" :options="$bankStatementList"
                                                            :zero="true" :isDisabled="true"
                                                            wire:model.live='BANK_STATEMENT_ID' />
                                                    @endif
                                                </div>
                                                <div class="col-md-3">
                                                    @if ($ID == 0 && auth()->user()->date_enabled)
                                                        <livewire:date-input name="DATE"
                                                            titleName="Statement as of date" wire:model.live='DATE'
                                                            :isDisabled="false" />
                                                    @else
                                                        <livewire:date-input name="DATE"
                                                            titleName="Statement as of date" wire:model.live='DATE'
                                                            :isDisabled="true" />
                                                    @endif
                                                </div>
                                                <div class="col-md-3">
                                                    <livewire:text-input name="Code" titleName="Reference No."
                                                        isDisabled="{{ !$Modify }}" wire:model='CODE' />
                                                </div>
                                                <div class="col-md-4"
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif>
                                                    <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                        :options="$locationList" :zero="false"
                                                        isDisabled="{{ !$Modify }}" wire:model='LOCATION_ID' />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12 text-xs">
                                                <div class="card card-body">

                                                    <div class="row">
                                                        {{--  SERVICE CHARGES RATE --}}
                                                        <div class="col-md-3 col-3">
                                                            <livewire:number-input name="SC_RATE"
                                                                titleName="Service Charges Rate"
                                                                isDisabled="{{ !$Modify }}" wire:model='SC_RATE' />
                                                        </div>
                                                        <div class="col-md-3 col-3">
                                                            <livewire:date-input name="SC_DATE" titleName="Date"
                                                                wire:model.live='SC_DATE'
                                                                isDisabled="{{ !$Modify }}" />
                                                        </div>
                                                        <div class="col-md-6 col-6">
                                                            @if ($Modify)
                                                                <livewire:select-option name="SC_ACCOUNT_ID1"
                                                                    titleName="Account" :options="$sc_accountList"
                                                                    :zero="true" :isDisabled="false"
                                                                    wire:model='SC_ACCOUNT_ID' />
                                                            @else
                                                                <livewire:select-option name="SC_ACCOUNT_ID2"
                                                                    titleName="Account" :options="$sc_accountList"
                                                                    :zero="true" :isDisabled="true"
                                                                    wire:model='SC_ACCOUNT_ID' />
                                                            @endif
                                                        </div>

                                                    </div>
                                                    <div class="row">

                                                        {{-- INTEREST EARN --}}
                                                        <div class="col-md-3 col-3">
                                                            <livewire:number-input name="IE_RATE"
                                                                titleName="Interest Earn "
                                                                isDisabled="{{ !$Modify }}" wire:model='IE_RATE' />
                                                        </div>
                                                        <div class="col-md-3 col-3">
                                                            <livewire:date-input name="IE_DATE" titleName="Date"
                                                                wire:model.live='IE_DATE'
                                                                isDisabled="{{ !$Modify }}" />
                                                        </div>
                                                        <div class="col-md-6 col-6">
                                                            @if ($Modify)
                                                                <livewire:select-option name="IE_ACCOUNT_ID1"
                                                                    titleName="Account" :options="$ie_accountList"
                                                                    :zero="true" :isDisabled="false"
                                                                    wire:model='IE_ACCOUNT_ID' />
                                                            @else
                                                                <livewire:select-option name="EI_ACCOUNT_ID2"
                                                                    titleName="Account" :options="$ie_accountList"
                                                                    :zero="true" :isDisabled="true"
                                                                    wire:model='IE_ACCOUNT_ID' />
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="col-md-12">
                                                <div class="card card-xs text-xs">
                                                    <div class=" card-body">
                                                        <div class="row">
                                                            <div class="col-2">Bank Debit :</div>
                                                            <div class="col-2 font-weight-bold ">
                                                                {{ number_format($BANK_DEBIT, 2) }}</div>
                                                            <div class="col-2">Bank Credit :</div>
                                                            <div class="col-2 font-weight-bold">
                                                                {{ number_format($BANK_CREDIT, 2) }}</div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-2 ">Cleared Debit :</div>
                                                            <div class="col-2 font-weight-bold">
                                                                {{ number_format($CLEARED_DEBIT, 2) }}</div>
                                                            <div class="col-2">Cleared Credit :</div>
                                                            <div class="col-2 font-weight-bold">
                                                                {{ number_format($CLEARED_CREDIT, 2) }}</div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-2 ">Difference Debit :</div>
                                                            <div class="col-2 font-weight-bold text-danger">
                                                                {{ number_format($BANK_DEBIT - $CLEARED_DEBIT, 2) }}
                                                            </div>
                                                            <div class="col-2">Difference Credit :</div>
                                                            <div class="col-2 font-weight-bold text-danger">
                                                                {{ number_format($BANK_CREDIT - $CLEARED_CREDIT, 2) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
                                                <button type="submit" class="btn btn-sm btn-primary"> <i
                                                        class="fa fa-floppy-o" aria-hidden="true"></i>
                                                    {{ $ID === 0 ? 'Pre-save' : 'Update' }}</button>
                                                @if ($ID > 0)
                                                    <button type="button" wire:click='updateCancel()'
                                                        name="cancelbtn" wire:confirm='Want to cancel?'
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
                                                    wire:confirm='Are you sure you want to post?'>
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Posted
                                                </button>
                                            @endif
                                        @endif
                                        @if ($STATUS == 15)
                                            @can('banking.bank-recon.update')
                                                <button type="button" wire:click='getUnposted()'
                                                    class="btn btn-sm btn-secondary"
                                                    wire:confirm="Are you sure you want to unpost?">
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Unpost
                                                </button>
                                            @endcan
                                        @endif
                                    </div>
                                    <div class="text-right col-md-6 col-6">
                                        @if ($STATUS > 0)
                                            @if ($STATUS != 16 && $ID > 0)
                                                @can('banking.bank-recon.print')
                                                    <a type="button" target="_BLANK"
                                                        href="{{ route('bankingbank_recon_print', ['id' => $ID]) }}"
                                                        class="btn btn-sm btn-dark">
                                                        <i class="fa fa-print" aria-hidden="true"></i> Print
                                                    </a>
                                                    @if ($IE_RATE > 0 || $SC_RATE > 0)
                                                        <button type="button" wire:click='OpenJournal()'
                                                            class="btn btn-sm btn-warning">
                                                            <i class="fa fa-file-text-o" aria-hidden="true"></i> Journal
                                                        </button>
                                                    @endif
                                                @endcan
                                            @endif
                                            @can('banking.bank-recon.create')
                                                <a id="new" title="Create"
                                                    href="{{ route('bankingbank_recon_create') }}"
                                                    class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> New </a>
                                            @endcan
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
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-12 ">
                                <div class="card card-primary card-outline card-outline-tabs">
                                    <div class="card-header p-0 border-bottom-0 text-xs"
                                        wire:loading.class='loading-form'>
                                        <ul class="nav text-xs nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link @if ($tab == 'bank') active @endif"
                                                    id="custom-tabs-four-bank-tab" wire:click="SelectTab('bank')"
                                                    data-toggle="pill" href="#custom-tabs-four-bank" role="tab"
                                                    aria-controls="custom-tabs-four-bank" aria-selected="true">Bank
                                                    Statement </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link @if ($tab == 'cleared') active @endif"
                                                    id="custom-tabs-four-cleared-tab"
                                                    wire:click="SelectTab('cleared')" data-toggle="pill"
                                                    href="#custom-tabs-four-cleared" role="tab"
                                                    aria-controls="custom-tabs-four-cleared"
                                                    aria-selected="true">Cleared Entry</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link @if ($tab == 'uncleared') active @endif"
                                                    id="custom-tabs-four-uncleared-tab"
                                                    wire:click="SelectTab('uncleared')" data-toggle="pill"
                                                    href="#custom-tabs-four-uncleared" role="tab"
                                                    aria-controls="custom-tabs-four-uncleared"
                                                    aria-selected="true">Uncleared Bank Statement</a>
                                            </li>
                                            <li wire:loading.delay>
                                                <span class="spinner"></span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body">
                                        <div class="tab-content" id="custom-tabs-four-tabContent">
                                            <div class="tab-pane fade @if ($tab == 'bank') show active @endif "
                                                id="custom-tabs-four-bank" role="tabpanel">
                                                @if ($tab == 'bank')
                                                    @livewire('BankRecon.BankStatement', ['BANK_STATEMENT_ID' => $BANK_STATEMENT_ID, 'ACCOUNT_RECONCILIATION_ID' => $ID, 'ACCOUNT_ID' => $ACCOUNT_ID, 'STATUS' => $STATUS])
                                                @endif
                                            </div>
                                            <div class="tab-pane fade @if ($tab == 'cleared') show active @endif "
                                                id="custom-tabs-four-cleared" role="tabpanel">
                                                @if ($tab == 'cleared')
                                                    @livewire('BankRecon.BankReconFormItems', ['ACCOUNT_RECONCILIATION_ID' => $ID, 'STATUS' => $STATUS])
                                                @endif
                                            </div>
                                            <div class="tab-pane fade @if ($tab == 'uncleared') show active @endif "
                                                id="custom-tabs-four-cleared" role="tabpanel">
                                                @if ($tab == 'uncleared')
                                                    @livewire('BankRecon.BankStatementUncleared', ['BANK_STATEMENT_ID' => $BANK_STATEMENT_ID, 'ACCOUNT_ID' => $ACCOUNT_ID, 'STATUS' => $STATUS])
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
        </section>
        @livewire('BankRecon.EntryList', ['ACCOUNT_RECONCILIATION_ID' => $ID, 'ACCOUNT_ID' => $ACCOUNT_ID, 'BANK_STATEMENT_ID' => $BANK_STATEMENT_ID])
    @endif

</div>

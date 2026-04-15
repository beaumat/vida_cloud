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
                                    <a class="text-white" href="{{ route('vendorswithholding_tax') }}">
                                        Withholding Tax
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
                                        <div class='col-6'>
                                            <div class="row">
                                                <div class="col-12">
                                                    @if ($Modify && $STATUS == 0)
                                                        <livewire:select-option-type name="WITHHELD_FROM_ID1"
                                                            titleName="Vendor" :options="$contactList" :zero="true"
                                                            :isDisabled="false" wire:model='WITHHELD_FROM_ID' />
                                                    @else
                                                        <livewire:select-option-type name="WITHHELD_FROM_ID2"
                                                            titleName="Vendor" :options="$contactList" :zero="true"
                                                            :isDisabled="true" wire:model='WITHHELD_FROM_ID' />
                                                    @endif
                                                </div>
                                                <div class="col-6">
                                                    @if (($Modify && $STATUS == 0) || ($Modify && $STATUS == 16))
                                                        <livewire:select-option name="EWT_ID"
                                                            titleName="Withholding Tax Type" :options="$taxList"
                                                            :zero="true" :isDisabled="false"
                                                            wire:model.live='EWT_ID' />
                                                    @else
                                                        <livewire:select-option name="EWT_ID"
                                                            titleName="Withholding Tax Type" :options="$taxList"
                                                            :zero="true" :isDisabled="true"
                                                            wire:model.live='EWT_ID' />
                                                    @endif
                                                </div>
                                                <div class="col-3">
                                                    <div class="row">
                                                        <div class="col-12"><label class="text-xs mt-1">Rate:</label>
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="text-xs">
                                                                {{ number_format($EWT_RATE ?? 0, 2) }}</label>
                                                        </div>
                                                    </div>

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
                                                    @if ($Modify)
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled=false
                                                            wire:model.live='LOCATION_ID' />
                                                    @else
                                                        <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                            :options="$locationList" :zero="false" :isDisabled=true
                                                            wire:model.live='LOCATION_ID' />
                                                    @endif
                                                </div>
                                                <div class="col-md-12">
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
                                            @can('vendor.withholding-tax.edit')
                                                <button type="button" wire:click='getUnposted()'
                                                    class="btn btn-sm btn-secondary"
                                                    wire:confirm="Are you sure you want to unpost?">
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Unpost
                                                </button>
                                            @endcan
                                        @endif

                                        @if ($STATUS == 16 || $STATUS == 0)
                                            @can('vendor.withholding-tax.delete')
                                                <button type="button" wire:click='DeleteEntry()'
                                                    class="btn btn-sm btn-danger"
                                                    wire:confirm="Are you sure you want to Delete?">
                                                    <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                                </button>
                                            @endcan
                                        @endif
                                    </div>
                                    
                                    <div class="text-right col-6 col-md-6">
                                        @if ($STATUS != 16)
                                            @if ($ID > 0 && $STATUS > 0)
                                                <button type="button" wire:click='OpenJournal()'
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fa fa-file-text-o" aria-hidden="true"></i> Journal
                                                </button>

                                                @can('company.stock-transfer.new')
                                                    <a id="new" title="Create"
                                                        href="{{ route('companystock_transfer_create') }}"
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
                                        <a class="nav-link active" id="custom-tabs-four-item-tab" data-toggle="pill"
                                            href="#custom-tabs-four-item" role="tab"
                                            aria-controls="custom-tabs-four-item" aria-selected="true">
                                            Bill List</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <div class="tab-pane fade show active " id="custom-tabs-four-item"
                                        role="tabpanel" aria-labelledby="custom-tabs-four-item-tab">
                                        <div class="row">
                                            <div class="col-md-12"
                                                @if ($Modify == true) style="opacity: 0.5;pointer-events: none;" @endif>
                                                @livewire('WithHoldingTax.BillList', ['WITHHOLDING_TAX_ID' => $ID, 'VENDOR_ID' => $WITHHELD_FROM_ID, 'LOCATION_ID' => $LOCATION_ID, 'EWT_RATE' => $EWT_RATE, 'STATUS' => $STATUS])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">

                                    </div>
                                    <div class="col-md-4 text-right">
                                        <label>{{ number_format($AMOUNT, 2) }}</label>
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

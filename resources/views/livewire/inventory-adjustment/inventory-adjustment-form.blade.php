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
                                <div class="col-8 col-sm-6">
                                    <a class="text-white" href="{{ route('companyinventory_adjustment') }}">
                                        Inventory Adjustment
                                    </a>
                                </div>
                                <div class="col-4 col-sm-6 text-right">
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
                                        <div class="col-12 col-md-6">
                                            <div class='row'>
                                                <div class="col-md-12">
                                                    <livewire:select-option name="ADJUSTMENT_TYPE_ID0"
                                                        isDisabled="{{ !$Modify }}" titleName="Adjustment Type"
                                                        :options="$adjustmentTypeList" :zero="true"
                                                        wire:model='ADJUSTMENT_TYPE_ID' />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="row">
                                                <div class="col-6 col-md-4">
                                                    @if ($ID == 0 && auth()->user()->date_enabled)
                                                        <livewire:date-input name="DATE" titleName="Date"
                                                            wire:model.live='DATE' :isDisabled="false" />
                                                    @else
                                                        <livewire:date-input name="DATE" titleName="Date"
                                                            wire:model.live='DATE' :isDisabled="true" />
                                                    @endif
                                                </div>
                                                <div class="col-6 col-md-4">
                                                    <livewire:text-input name="Code" titleName="Reference No."
                                                        isDisabled="{{ !$Modify }}" wire:model='CODE' />

                                                </div>
                                                <div class="col-6 col-md-4"
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif>
                                                    <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                        :options="$locationList" :zero="false"
                                                        isDisabled="{{ !$Modify }}" wire:model='LOCATION_ID' />

                                                </div>
                                                <div class="col-md-12">
                                                    <livewire:text-input name="NOTES" titleName="Notes"
                                                        isDisabled="{{ !$Modify }}" wire:model='NOTES'
                                                        :vertical="false" :maxlength="80" />
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        @if ($STATUS != 15 || $STATUS == 16)
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
                                                <button type="button" wire:click='posted()'
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Posted
                                                </button>
                                                @if ($STATUS == 16 || $STATUS == 0)
                                                    @can('company.inventory-adjustment.delete')
                                                        <button type="button" wire:click='delete()'
                                                            class="btn btn-sm btn-danger"
                                                            wire:confirm="Are you sure you want to delete?">
                                                            <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                                        </button>
                                                    @endcan
                                                @endif
                                            @endif
                                        @endif

                                        @if ($STATUS == 15)
                                            @can('company.inventory-adjustment.update')
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
                                            <button type="button" wire:click='OpenJournal()'
                                                class="btn btn-sm btn-warning">
                                                <i class="fa fa-file-text-o" aria-hidden="true"></i> Journal
                                            </button>

                                            <a id="new" title="Create"
                                                href="{{ route('companyinventory_adjustment_create') }}"
                                                class="btn btn-primary btn-sm"> <i class="fas fa-plus"></i> New </a>
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
                                        aria-controls="custom-tabs-four-item" aria-selected="true">Adjustment
                                        Items</a>
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
                                            @livewire('InventoryAdjustment.InventoryAdjustmentFormItems', ['INVENTORY_ADJUSTMENT_ID' => $ID, 'STATUS' => $STATUS, 'openStatus' => $openStatus, 'DATE' => $DATE, 'LOCATION_ID' => $LOCATION_ID])
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
    @livewire('AccountJournal.AccountJournalModal')
</div>

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
                                    <a class="text-white" href="{{ route('companybuild_assembly') }}"> Build Assembly
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
                                            <livewire:select-option name="ASSEMBLY_ITEM_ID" titleName="Assembly Item"
                                                :options="$itemList" :zero="true" isDisabled="{{ !$Modify }}"
                                                wire:model.live='ASSEMBLY_ITEM_ID' />
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <livewire:number-input name="QUANTITY" titleName="Quantity to Build"
                                                        isDisabled="{{ !$Modify }}" wire:model='QUANTITY' />
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row mt-1">
                                                        <div class="col-md-12">
                                                            <label for="UNIT_ID" class="text-xs ">
                                                                Unit of Measure
                                                            </label>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <select @if (!$Modify) disabled @endif
                                                                wire:model='UNIT_ID' name="UNIT_ID" id='UNIT_ID'
                                                                class="form-control form-control-sm  text-xs">
                                                                @foreach ($unitList as $list)
                                                                    @if ($list->ID != null)
                                                                        <option value="{{ $list->ID }}">
                                                                            {{ $list->SYMBOL }}
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
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
                                                    <livewire:text-input name="Code" titleName="Reference No."
                                                        isDisabled="{{ !$Modify }}" wire:model='CODE' />
                                                </div>
                                                <div class="col-md-4"
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif>
                                                    <livewire:select-option name="LOCATION_ID" titleName="Location"
                                                        :options="$locationList" :zero="false"
                                                        isDisabled="{{ !$Modify }}" wire:model='LOCATION_ID' />
                                                </div>
                                                <div class="col-md-12">
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
                                        @if ($STATUS == 0)
                                            @if ($Modify)
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                                    {{ $ID === 0 ? 'Pre-save' : 'Update' }}</button>

                                                @if ($ID > 0)
                                                    <button type="button" wire:click='updateCancel'
                                                        class="btn btn-sm btn-danger"><i class="fa fa-ban"
                                                            aria-hidden="true"></i> Cancel</button>
                                                @endif
                                            @else
                                                <button type="button" wire:click='getModify()'
                                                    class="btn btn-sm btn-info"
                                                    @if ($STATUS > 0) style="opacity: 0.5;pointer-events: none;" @endif>
                                                    <i class="fa fa-wrench" aria-hidden="true"></i> Modify
                                                </button>
                                                <button type="button" wire:click='posted()'
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Posted
                                                </button>
                                            @endif
                                        @endif

                                        @if ($STATUS == 15)
                                            @can('company.build-assembly.update')
                                                <button type="button" wire:click='getUnposted()'
                                                    class="btn btn-sm btn-secondary"
                                                    wire:confirm="Are you sure you want to unpost?">
                                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i> Unpost
                                                </button>
                                            @endcan
                                        @endif

                                        @if ($STATUS == 16)
                                            @if ($Modify)
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                                    {{ 'Update' }}</button>
                                                    
                                                <button type="button" wire:click='updateCancel'
                                                    class="btn btn-sm btn-danger"><i class="fa fa-ban"
                                                        aria-hidden="true"></i> Cancel</button>
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

                                    </div>
                                    <div class="text-right col-6 col-md-6">
                                        @if ($STATUS != 16)
                                            @if ($ID > 0 && $STATUS > 0)
                                                <button type="button" wire:click='OpenJournal()'
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fa fa-file-text-o" aria-hidden="true"></i> Journal
                                                </button>
                                                @can('company.build-assembly.create')
                                                    <a id="new" title="Create"
                                                        href="{{ route('companybuild_assembly_create') }}"
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
                                        aria-controls="custom-tabs-four-item" aria-selected="true">Components</a>
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
                                            @livewire('BuildAssembly.BuildAssemblyFormItems', ['BUILD_ASSEMBLY_ID' => $ID, 'ASSEMBLY_ITEM_ID' => $ASSEMBLY_ITEM_ID, 'LOCATION_ID' => $LOCATION_ID, 'IS_POSTED' => $STATUS === 0 ? false : true])
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
                                        <div class="col-md-8 text-right">

                                        </div>
                                        <div class="col-md-4 text-right">
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
    @livewire('AccountJournal.AccountJournalModal')
</div>

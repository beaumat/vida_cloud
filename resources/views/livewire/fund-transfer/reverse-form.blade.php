<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"> Fund Transfer Reverse </h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

                        <div class="container">

                            <div class="row">
                                <div class="col-md-6">
                                    @if ($ID == 0 && auth()->user()->date_enabled)
                                        <livewire:date-input name="DATE" titleName="Date" wire:model.live='DATE'
                                            :isDisabled="false" />
                                    @else
                                        <livewire:date-input name="DATE" titleName="Date" wire:model.live='DATE'
                                            :isDisabled="true" />
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    @if ($DISPLAY_MODE)
                                        @if ($Modify)
                                            <livewire:select-option name="LOCATION_ID1" titleName="Location"
                                                :options="$locationList" :zero="true" :isDisabled=false
                                                wire:model='LOCATION_ID' />
                                        @else
                                            <livewire:select-option name="LOCATION_ID2" titleName="Location"
                                                :options="$locationList" :zero="true" :isDisabled=true
                                                wire:model='LOCATION_ID' />
                                        @endif
                                    @else
                                        @if ($Modify)
                                            <livewire:select-option name="LOCATION_ID3" titleName="Location"
                                                :options="$locationList" :zero="true" :isDisabled=false
                                                wire:model='LOCATION_ID' />
                                        @else
                                            <livewire:select-option name="LOCATION_ID4" titleName="Location"
                                                :options="$locationList" :zero="true" :isDisabled=true
                                                wire:model='LOCATION_ID' />
                                        @endif
                                    @endif

                                </div>
                                <div class="col-md-12">
                                    @if ($Modify)
                                        <livewire:text-input name="NOTES" titleName="Notes" :isDisabled=false
                                            wire:model='NOTES' :vertical="false" />
                                    @else
                                        <livewire:text-input name="NOTES" titleName="Notes" :isDisabled=true
                                            wire:model='NOTES' :vertical="false" />
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" wire:loading.class='loading-form'>
                        <div wire:loading.delay>
                            <span class="spinner"></span>
                        </div>
                        @if ($Modify)
                            <button type="button" class="btn btn-success btn-sm" wire:click="saveData"
                                wire:confirm='Are you sure?'>Save</button>
                        @endif
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

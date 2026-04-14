<div>
    <button wire:click="openModal()" class="btn btn-warning btn-sm text-xs">
        <i class="fa fa-plus" aria-hidden="true"></i> 
    </button>
    @if ($showModal)
    <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content text-left">
                <div class="modal-header">Quick Add Items</div>
                <div class="modal-body">
                    @livewire('Hemodialysis.ItemTreatment', ['HEMO_ID' => $HEMO_ID, 'LOCATION_ID' => $LOCATION_ID])
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" wire:click='create()' class="btn btn-success btn-sm">Create</button> --}}
                    <button type="button" wire:click='closeModal()' class="btn btn-danger btn-sm">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable" role="document"
                style="margin: auto;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="text-primary">
                            Transfer
                            @if ($IS_TREATMENT)
                                Treatment :
                            @else
                                Service Charges :
                            @endif
                            Patient Name
                        </h6>
                        <button type="button" class="close" wire:click="closeModal()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-3 text-right">
                                <label>Change to :</label>
                            </div>
                            <div class="col-9">
                                <livewire:select-option name="NEW_CONTACT_ID" :options="$contactList" :isDisabled='false'
                                    vertical='true' zero='true' titleName='' withLabel='false'
                                    wire:model='NEW_CONTACT_ID' />

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success btn-sm" wire:click="SaveChange()">Save
                            Change</button>
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

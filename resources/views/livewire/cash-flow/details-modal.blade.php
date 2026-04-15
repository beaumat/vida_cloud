<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-lx" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"> Details </h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form wire:submit.prevent='save' wire:loading.attr='disabled'>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label class="text-xs">Name:</label>
                                    <input type="text" wire:model='NAME' class="form-control form-control-sm" />
                                </div>
                                <div class="col-12">
                                    <label class="text-xs">Line No.:</label>
                                    <input type="number" wire:model='LINE_NO' class="form-control form-control-sm" />
                                </div>
                                <div class="col-12">
                                    <label class="text-xs">Inactive:</label>
                                    <input type="checkbox" wire:model='INACTIVE' class="form-check form-check-sm" />
                                </div>
                                <div class="col-12">
                                    <label class="text-xs">Is Total:</label>
                                    <input type="checkbox" wire:model='IS_TOTAL' class="form-check form-check-sm" />
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success btn-sm">
                                @if ($ID == 0)
                                    Save
                                @else
                                    Update
                                @endif
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm"
                                wire:click="closeModal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<div>
    @if ($showModal)
    <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog model-sm modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Pin password</h6>
                    <button type="button" class="close" wire:click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent='logpin'>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' =>
                        session('message'), 'error' => session('error')])
                        <input type="password" class="form-control form-control-sm" wire:model='PIN'
                            title="Pin password" placeholder="Pin password" />
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning btn-md"> <i class="fa fa-lock"
                                aria-hidden="true"></i> OK </button>
                        <button type="button" class="btn btn-secondary btn-md" wire:click="closeModal">Close</button>
                        <i class="text-md text-danger">Note: Only <b>nurses</b> are authorized to enter the PIN. <br/>Please use the first letter of the first
                            name, middle name, and last name (e.g., Mario Santos Garcia becomes "msg").</i>
                    </div>
                </form>
            </div>
        </div>

    </div>
    @endif
</div>
<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Make Bill</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        <form id="quickForm" wire:submit.prevent='save'>
                            <div class="card-body bg-light">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <livewire:date-input name="DATE" titleName="Billing Date"
                                                :isDisabled=false wire:model='DATE' />
                                        </div>
                                        <div class="col-md-4">
                                            <livewire:text-input name="CODE"
                                                titleName="Billing No. (leave empty to auto generate)" :isDisabled=false
                                                :maxlength="20" wire:model='CODE' />
                                        </div>
                                        <div class="col-md-4">

                                        </div>
                                        <div class="col-md-12">
                                            <livewire:text-input name="NOTES" titleName="Notes" :isDisabled=false
                                                :maxlength="1000" wire:model='NOTES' />
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-success btn-sm m-1"
                                    wire:confirm='Are you sure to make bill?'>Make</button>
                                <button type="button" class="btn btn-secondary btn-sm m-1"
                                    wire:click="closeModal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

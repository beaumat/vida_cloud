<div>
    @if ($showModal)
        <div class="modal show" id="modal-xl" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
            <div class="modal-dialog modal-md" role="document"
                style="height: auto; margin: auto; top: 50%; transform: translateY(-50%);">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Change Password</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form wire:submit.prevent='save' wire:loading.attr='disabled'>
                        <div class="modal-body">
                            @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                            <div class="row">
                                <div class="col-md-4 text-right">
                                    <label class="text-primary text-sm">Current Password</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="password" wire:model='CURRENT' class="form-control form-control-sm" maxlength='12' />
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4 text-right">
                                    <label class="text-primary text-sm">New Password</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="password" wire:model='NEW' class="form-control form-control-sm" maxlength='12' />
                                </div>
                                <div class="col-md-12 text-xs text-danger pt-2">
                                    <i>New password(format) must meet the following criteria:</i>
                                    <ul>
                                        <li><span>At least 4 characters</span></li>
                                        <li><span>No more than 12 characters</span></li>
                                        <li><span>At least 1 number</span></li>
                                        <li><span>At least 1 letter</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                              <div wire:loading.delay>
                                    <span class="spinner"></span>
                                </div>
                            <button type="submit" class="btn btn-success btn-sm"  wire:loading.attr='disabled'>Change</button>
                            <button type="button" class="btn btn-secondary btn-sm"
                                wire:click="closeModal"  wire:loading.attr='disabled'>Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

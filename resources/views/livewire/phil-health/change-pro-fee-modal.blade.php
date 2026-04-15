    <div>
        @if ($showModal)
            <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
                <div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
                    <div class="modal-content text-left">
                        <div class="modal-header">Change Professional Fee</div>
                        <div class="modal-body">
                            @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                            <div class="form-group">
                                <label class="text-sm">Nephro/Doctors :</label>
                                <select name="doctorid" wire:model.live='doctorid' class="form-control form-control-sm">

                                    @foreach ($doctorList as $item)
                                        <option value="{{ $item->ID }}"> {{ $item->NAME }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class='modal-footer'>
                            <button class="btn btn-success btn-sm" wire:click='update()' wire:confirm='Are you sure?'
                                wire:loading.attr='disabled'>Update</button>
                            <button type="button" wire:click='closeModal()' class="btn btn-danger btn-sm"
                                wire:loading.attr='disabled'>
                                Close
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        @endif
    </div>

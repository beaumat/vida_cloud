<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable" role="document"
                style="margin: auto;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="text-primary">Add</h6>
                        <button type="button" class="close" wire:click="closeModal()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form wire:submit.prevent="AddCharge" wire:loading.attr='disabled'>
                        <div class="modal-body">
                            @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                            <div class="row">
                                <div class="col-4 text-right text-sm text-primary">
                                    Item :
                                </div>
                                <div class="col-8">
                                    <label class="text-xs">{{ $ITEM_NAME }}</label>
                                </div>
                                @if (count($unitList) > 0)
                                    <div class="col-4 text-right text-sm text-primary">
                                        Unit of Measure :
                                    </div>
                                    <div class="col-8">
                                        <select wire:model='UNIT_ID' name="UNIT_ID"
                                            class="text-sm form-control form-control-sm">
                                            <option value="0"></option>
                                            @foreach ($unitList as $list)
                                                <option value="{{ $list->ID }}">{{ $list->SYMBOL }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-4 text-right text-sm text-primary">
                                    Quantity
                                </div>
                                <div class="col-8">
                                    <input type="number" class="form-control form-control-sm" wire:model='QUANTITY' />
                                </div>
                                @if ($J_QTY > 0)
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-md-8">
                                        <span class="text-info text-xs"> Free
                                            <label>{{ $J_QTY ?? 0 }}</label>
                                            <label>{{ $J_ITEM_NAME }}</label></span>
                                    </div>
                                @endif
                            </div>
                            @if ($haveTrigger)
                                {{-- <div class="row mt-2">
                                    <div class="col-md-12">
                                        <input type='checkbox' wire:model.live='IS_JUSTIFY' name="IS_JUSTIFY" />
                                        <label class="text-xs font-weight-bold text-success"> +1
                                            {{ $J_ITEM_NAME }}</label> <br />
                                        @if ($IS_JUSTIFY)
                                            <span class="text-xs font-weight-bold">
                                                <i>Please provide a justification or a reasonable
                                                    explanation for selecting this option:</i>
                                            </span>
                                            <textarea type='text' wire:model='JUSTIFY_NOTES' class="form-control form-control-sm" rows="3"></textarea>
                                        @endif
                                    </div>
                                </div> --}}
                            @endif

                        </div>
                        <div class="modal-footer">
                            <div wire:loading.delay>
                                <span class="spinner"></span>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm"
                                wire:loading.attr='hidden'>Add</button>
                            <button type="button" wire:click='closeModal()' class="btn btn-danger btn-sm"
                                wire:loading.attr='hidden'>Close</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

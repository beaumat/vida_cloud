<div>
    @if ($showModal)
        <div class="modal show" id="modal-xl" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
            <div class="modal-dialog modal-xl" role="document"
                style="width: 90%; max-width: none; height: auto; margin: auto; top: 50%; transform: translateY(-50%);">
                <div class="modal-content text-left">
                    <div class="modal-header">
                        <h6 class="modal-title text-dark">Fixed Asset Item Details : {{ $ITEM_NAME }}</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                    <div wire:loading.class='loading-form' >
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-2">
                                    <livewire:text-input name="PO_NUMBER" titleName="PO Number" wire:model='PO_NUMBER'
                                        :vertical="false" :isDisabled="false" />
                                </div>
                                <div class="col-2">
                                    <livewire:text-input name="SERIAL_NO" titleName="Serial No." wire:model='SERIAL_NO'
                                        :vertical="false" :isDisabled="false" />
                                </div>
                              
                                <div class="col-3">
                                    <livewire:select-option name="ACCOUNT_ID1" titleName="Accumulated Account"
                                        :options="$accountList" :zero="true" :isDisabled="false"
                                        wire:model='ACCUMULATED_ACCOUNT_ID' />
                                </div>
                                <div class="col-2">
                                    <livewire:date-input name="WARRANTIY_EXPIRED" titleName="Warranty Expired"
                                        wire:model='WARRANTIY_EXPIRED' :isDisabled="false" />
                                </div>
                                <div class="col-4">
                                    <livewire:text-input name="OTHER_DESCRIPTION" titleName="Other Description"
                                        wire:model='OTHER_DESCRIPTION' :vertical="false" :isDisabled="false" />
                                </div>
                                <div class="col-2">
                                    <livewire:checkbox-input name="PERSONAL_PROPERTY_RETURN"
                                        titleName="Personal Property Return" wire:model='PERSONAL_PROPERTY_RETURN'
                                        :isDisabled="false" />
                                </div>
                                <div class="col-2">
                                    <livewire:checkbox-input name="IS_NEW" titleName="Is New" wire:model='IS_NEW'
                                        :isDisabled="false" />
                                </div>
                                <div class="col-4"></div>
                                <div class="col-2">
                                    <livewire:date-input name="PO_DATE" titleName="Purchase Order Date"
                                        wire:model='PO_DATE' :isDisabled="false" />
                                </div>
                                <div class="col-1">
                                    <livewire:number-input name="YEAR_MODEL" titleName="Year Modal"
                                        wire:model='YEAR_MODEL' :vertical="false" :isDisabled="false" />
                                </div>
                                <div class="col-1">
                                    <livewire:number-input name="QUANTITY" titleName="Quantity" wire:model='QUANTITY'
                                        :vertical="false" :isDisabled="false" />
                                </div>
                                <div class="col-1">

                                    <label for="AQ_COST" class="text-xs">Aquisition Cost </label>

                                    <input type="number" step="0.01" class="form-control form-control-sm text-right"
                                        name="AQ_COST" wire:model.live.debounce.1000ms='AQ_COST' />
                                </div>
                                <div class="col-1">
                                    <label for="Usefulllife" class="text-xs">Useful life</label>

                                    <input type="number" step="0.01" class="form-control form-control-sm text-right"
                                        name="USEFUL_LIFE" wire:model.live.debounce.1000ms='USEFUL_LIFE' />
                                </div>

                                <div class="col-2">
                                    <livewire:checkbox-input name="INACTIVE" titleName="Inactive" wire:model='INACTIVE'
                                        :isDisabled="false" />
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row ">
                                            <div class="col-6">
                                                <label>Per Year</label> : {{ number_format($PER_YEAR, 2) }}
                                            </div>
                                            <div class="col-6">
                                                <label>Per Month</label> : {{ number_format($PER_MONTH, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 text-right">
                                        <button type="button" wire:click="save()" class="btn btn-success btn-sm">
                                            @if ($ID > 0)
                                                Update
                                            @else
                                                Save
                                            @endif
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">
                                            Close
                                        </button>
                                    </div>
                                <div class="col-12" wire:loading.delay>
                                    <span class="spinner"></span>
                                </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

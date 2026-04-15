<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Make Cash Payment</h6>
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
                                        <div class="col-6">

                                        </div>
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <livewire:date-input name="DATE" titleName="SL Date"
                                                        :isDisabled=true wire:model='DATE' />
                                                </div>
                                                <div class="col-md-4">
                                                    <livewire:text-input name="RECEIPT_REF_NO" titleName="SL NO."
                                                        :isDisabled=false wire:model='RECEIPT_REF_NO' />
                                                </div>
                                                <div class="col-md-4">
                                                    <livewire:number-input name="AMOUNT" titleName="Amount"
                                                        :isDisabled=true wire:model='AMOUNT' />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <table class="table table-sm table-bordered table-hover">
                                        <thead class="text-xs bg-sky">
                                            <tr>
                                                <th class="text-center"><input type="checkbox" wire:model.live='SelectAll' /> </th>
                                                <th>Item Description</th>
                                                <th>Category</th>
                                                <th class="text-right">Amount</th>
                                                <th class="text-right">Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-xs">
                                            @foreach ($itemList as $list)
                                                <tr>
                                                    <td class="text-center"> <input type="checkbox" name="itemList{{ $list->ID }}"
                                                            wire:model.live='itemSelected.{{ $list->ID }}' />
                                                    </td>
                                                    <td>{{ $list->DESCRIPTION }}</td>
                                                    <td>{{ $list->CLASS_DESCRIPTION }}</td>
                                                    <td class="text-right">{{ number_format($list->AMOUNT, 2) }}</td>
                                                    <td class="text-right">{{ number_format($list->BALANCE, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-success btn-sm m-1">Save</button>
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

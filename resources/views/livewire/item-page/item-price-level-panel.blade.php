<div class="row" @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <div class="col-md-12">
        <div class="card bg-light">
            <div class="card-body">
                <table class="table table-sm table-bordered table-hover">
                    <thead class="text-xs bg-sky">
                        <tr>
                            <th>Price Levels</th>
                            <th class="col-3 text-right">Custom Price</th>
                            <th class="col-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($priceLevelList as $list)
                            <tr>
                                <td> {{ $list->DESCRIPTION }}</td>
                                <td class="text-right">
                                    @if ($editItemId === $list->ID)
                                        <input type="number" wire:model="newCustomPrice"
                                            class="form-control form-control-sm text-xs text-right">
                                    @else
                                        {{ number_format($list->CUSTOM_PRICE, 2) }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($editItemId === $list->ID)
                                        <button title="Update" id="updatebtn"
                                            wire:click="updateItem({{ $list->ID }})"
                                            class="text-success btn btn-sm btn-link">
                                            <i class="fas fa-check" aria-hidden="true"></i>
                                        </button>
                                        <button title="Cancel" id="cancelbtn" href="#" wire:click="cancelItem()"
                                            class="text-warning btn btn-sm btn-link">
                                            <i class="fas fa-ban" aria-hidden="true"></i>
                                        </button>
                                    @else
                                        <button title="Edit" id="editbtn"
                                            wire:click='editItem({{ $list->ID . ',' . $list->CUSTOM_PRICE }})'
                                            class="text-info btn btn-sm btn-link">
                                            <i class="fas fa-edit" aria-hidden="true"></i>
                                        </button>
                                        <button title="Delete" id="deletebtn"
                                            wire:click='deleteItem({{ $list->ID }})'
                                            wire:confirm="Are you sure you want to delete this?"
                                            class="text-danger btn btn-sm btn-link">
                                            <i class="fas fa-times" aria-hidden="true"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <form wire:submit.prevent='saveItem' wire:loading.attr='disabled'>
                            <tr>
                                <td>
                                    @if ($saveSuccess)
                                        <livewire:select-option name="PRICE_LEVEL_ID" titleName="Price Level"
                                            isDisabled="{{ false }}" :options="$priceLevels" :zero="true"
                                            wire:model='PRICE_LEVEL_ID' :vertical="false" :withLabel="false"
                                             />
                                    @else
                                        <livewire:select-option name="PRICE_LEVEL_ID1" titleName="Price Level"
                                            isDisabled="{{ false }}" :options="$priceLevels" :zero="true"
                                            wire:model='PRICE_LEVEL_ID' :vertical="false" :withLabel="false"
                                     />
                                    @endif
                                </td>
                                <td>
                                    <livewire:number-input name="CUSTOM_PRICE" titleName="Custom Price"
                                        isDisabled="{{ false }}" wire:model='CUSTOM_PRICE' :vertical="false"
                                        :withLabel="false" />
                                </td>
                                <td>
                                    <div class="mt-2">
                                        <button type="submit" wire:loading.attr='hidden'
                                            class="text-white btn btn-success btn-sm w-100">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <div wire:loading.delay>
                                            <span class="spinner"></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

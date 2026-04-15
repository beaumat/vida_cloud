<div>
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-1">Fixed Asset No.</th>
                <th class="col-6">Fixed Asset Item</th>
                <th class="col-1 text-right">Amount</th>
                @if ($STATUS == 0 || $STATUS == 16)
                    <th class="text-center col-1">Action</th>
                @endif
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($dataList as $list)
                <tr>

                    <td>{{ sprintf('%05d', $list->ASSET_ITEM_ID) }}</td>
                    <td>{{ $list->ITEM_NAME }}</td>
                    <td class="text-right">
                        @if ($editID === $list->ID)
                            <input step="0.01" type="number" class="form-control form-control-sm text-right"
                                name="editAmount" wire:model='editAmount' />
                        @else
                            {{ number_format($list->AMOUNT, 2) }}
                        @endif
                    </td>

                    @if ($STATUS == 0 || $STATUS == 16)
                        <td class="text-center">
                            @if ($editID === $list->ID)
                                <button title="Update" id="updatebtn" wire:click="update()"
                                    class="btn btn-xs btn-success">
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                </button>
                                <button title="Cancel" id="cancelbtn" href="#" wire:click="cancel()"
                                    class="btn btn-xs btn-warning">
                                    <i class="fas fa-ban" aria-hidden="true"></i>
                                </button>
                            @else
                                <button title="Edit" id="editbtn" wire:click='edit( {{ $list->ID }})'
                                    class="btn btn-xs btn-info">
                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                </button>
                                <button title="Delete" id="deletebtn" wire:click='delete({{ $list->ID }})'
                                    wire:confirm="Are you sure you want to delete this?" class="btn btn-xs btn-danger">
                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                </button>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
            @if ($STATUS == 0 || $STATUS == 16)
                <tr>
                    <form wire:submit.prevent='add()' wire:loading.attr='disabled'>
                        <td></td>
                        <td>
                            @if ($saveSuccess)
                                <livewire:select-option name="FIXED_ASSET_ITEM_ID2" titleName="" :options="$fixedAssetItemList"
                                    :zero="true" wire:model.live='FIXED_ASSET_ITEM_ID' :isDisabled=false
                                    :vertical="false" :withLabel="false" />
                            @else
                                <livewire:select-option name="FIXED_ASSET_ITEM_ID1" titleName="" :options="$fixedAssetItemList"
                                    :zero="true" wire:model.live='FIXED_ASSET_ITEM_ID' :isDisabled=false
                                    :vertical="false" :withLabel="false" />
                            @endif
                        </td>
                        <td>
                            <input step="0.01" type="number" class="form-control form-control-sm text-right"
                                name="AMOUNT" wire:model='AMOUNT' />
                        </td>
                        <td>
                            <div>
                                <button type="submit" wire:loading.attr='hidden'
                                    @if ($FIXED_ASSET_ITEM_ID == 0) disabled @endif
                                    class="btn btn-primary btn-xs w-100">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <div wire:loading.delay>
                                    <span class="spinner"></span>
                                </div>
                            </div>
                        </td>
                    </form>
                </tr>
            @endif
        </tbody>
    </table>

</div>

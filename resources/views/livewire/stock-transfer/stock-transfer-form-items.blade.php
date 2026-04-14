<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-1">Code</th>
                <th class="col-4">Description</th>
                <th class="col-1">Qty</th>
                <th class="col-1">U/M</th>
                <th class="col-1 text-right">Unit Price</th>
                <th class="col-1 text-right">Retail Amount</th>
                <th class="col-1 text-right">Unit Cost</th>
                <th class="col-1 text-right">Amount</th>
                @if ($STATUS == $openStatus || $STATUS == 16)
                    <th class="text-center col-1">Action</th>
                @endif
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($itemList as $list)
                <tr>
                    <td>{{ $list->CODE }}</td>
                    <td>{{ $list->DESCRIPTION }}</td>
                    <td class="text-right">
                        @if ($editItemId === $list->ID)
                            <input type="number" step="0.01" class="form-control form-control-sm text-right"
                                name="lineQty" wire:model.live.debounce.1000ms='lineQty' wire:blur="getEditAmount" />
                        @else
                            {{ number_format($list->QUANTITY, 0) }}
                        @endif
                    </td>
                    <td class="text-sm">
                        @if ($editItemId === $list->ID)
                            <select wire:model.live='lineUnitId' name="lineUnitId"
                                class="text-sm form-control form-control-sm">
                                @foreach ($editUnitList as $listitem)
                                    <option value="{{ $listitem->ID }}">{{ $listitem->SYMBOL }}</option>
                                @endforeach
                            </select>
                        @else
                            {{ $list->SYMBOL }}
                        @endif
                    </td>
                    {{-- Unit Price --}}
                    <td class="text-right">
                        @if ($editItemId === $list->ID)
                            <input type="number" step="0.01" class="form-control form-control-sm  text-right"
                                name="lineUnitPrice" wire:model.live.debounce.1000ms='lineUnitPrice'
                                wire:blur="getEditAmount" />
                        @else
                            {{ number_format($list->UNIT_PRICE, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($editItemId === $list->ID)
                            <label class="">{{ number_format($lineRetailValue, 2) }}</label>
                        @else
                            {{ number_format($list->RETAIL_VALUE, 2) }}
                        @endif
                    </td>
                    {{-- Unit Cost --}}
                    <td class="text-right">
                        @if ($editItemId === $list->ID)
                            <input type="number" step="0.01" class="form-control form-control-sm  text-right"
                                name="lineUnitCost" wire:model.live.debounce.1000ms='lineUnitCost'
                                wire:blur="getEditAmount" />
                        @else
                            {{ number_format($list->UNIT_COST, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($editItemId === $list->ID)
                            <label class="">{{ number_format($lineAmount, 2) }}</label>
                        @else
                            {{ number_format($list->AMOUNT, 2) }}
                        @endif
                    </td>


                    @if ($STATUS == $openStatus || $STATUS == 16)
                        <td class="text-center">
                            @if ($editItemId === $list->ID)
                                <button title="Update" id="updatebtn" wire:click="updateItem()"
                                    class="btn btn-success btn-xs">
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                </button>
                                <button title="Cancel" id="cancelbtn" wire:click="cancelItem()"
                                    class="btn btn-warning btn-xs">
                                    <i class="fas fa-ban" aria-hidden="true"></i>
                                </button>
                            @else
                                <button title="Edit" id="editbtn" wire:click='editItem({{ $list->ID }})'
                                    class="btn btn-info btn-xs">
                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                </button>

                                <button title="Delete" id="deletebtn" wire:click='deleteItem({{ $list->ID }})'
                                    wire:confirm="Are you sure you want to delete this?" class="btn btn-danger btn-xs">
                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                </button>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach

            {{-- INSERT FORM --}}
            @if ($STATUS == $openStatus || $STATUS == 16)
                <form wire:submit.prevent='saveItem' wire:loading.attr='disabled'>
                    <tr>
                        <td class="text-md">
                            @if ($saveSuccess)
                                @if ($codeBase)
                                    <livewire:select-option name="ITEM_ID1" titleName="Item Code" :options="$itemCodeList"
                                        :zero="true" wire:model.live='ITEM_ID' :vertical="false" :isDisabled=false
                                        :withLabel="false" />
                                @else
                                    <label class="text-xs"> {{ $ITEM_CODE }}</label>
                                @endif
                            @else
                                @if ($codeBase)
                                    <livewire:select-option name="ITEM_ID2" titleName="Item Code" :options="$itemCodeList"
                                        :zero="true" wire:model.live='ITEM_ID' :vertical="false" :isDisabled=false
                                        :withLabel="false" />
                                @else
                                    <label class=" text-xs"> {{ $ITEM_CODE }}</label>
                                @endif
                            @endif
                        </td>
                        <td class="text-md">
                            @if ($saveSuccess)
                                @if (!$codeBase)
                                    <livewire:select-option name="ITEM_ID3" titleName="Item Description"
                                        :options="$itemDescList" :zero="true" wire:model.live='ITEM_ID' :vertical="false"
                                        :isDisabled=false :withLabel="false" />
                                @else
                                    <label class=" text-xs"> {{ $ITEM_DESCRIPTION }}</label>
                                @endif
                            @else
                                @if (!$codeBase)
                                    <livewire:select-option name="ITEM_ID4" titleName="Item Description"
                                        :options="$itemDescList" :zero="true" wire:model.live='ITEM_ID'
                                        :isDisabled=false :vertical="false" :withLabel="false" />
                                @else
                                    <label class=" text-xs"> {{ $ITEM_DESCRIPTION }}</label>
                                @endif
                            @endif
                        </td>

                        <td>
                            <input type="number" step="0.01" class="form-control form-control-sm text-right"
                                name="Qty" wire:model.live.debounce.1000ms='QUANTITY' wire:blur="getAmount"
                                @if ($ITEM_ID == 0) readonly @endif />
                        </td>
                        <td>
                            <select wire:model='UNIT_ID' name="UNIT_ID" class="text-sm form-control form-control-sm"
                                @if ($ITEM_ID == 0) readonly @endif>
                                @foreach ($unitList as $list)
                                    <option value="{{ $list->ID }}">{{ $list->SYMBOL }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" step="0.01" class="form-control form-control-sm text-right"
                                name="UNIT_PRICE" wire:model.live.debounce.1000ms='UNIT_PRICE'
                                wire:blur="getAmount" />
                        </td>
                        <td class="text-right">
                            <label class=" text-sm">{{ number_format($RETAIL_VALUE, 2) }}</label>
                        </td>
                        <td>
                            <input type="number" step="0.01" class="form-control form-control-sm text-right"
                                name="UNIT_PRICE" wire:model.live.debounce.1000ms='UNIT_COST'
                                wire:blur="getAmount" />
                        </td>
                        <td class="text-right">
                            <label class=" text-sm">{{ number_format($AMOUNT, 2) }}</label>
                        </td>


                        <td>
                            <div class="">
                                <button type="submit" wire:loading.attr='hidden'
                                    @if ($ITEM_ID == 0) disabled @endif
                                    class="text-white btn bg-sky btn-sm w-100">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <div wire:loading.delay>
                                    <span class="spinner"></span>
                                </div>
                            </div>
                        </td>

                    </tr>

                </form>
            @endif

        </tbody>

    </table>
    @if ($STATUS == $openStatus || $STATUS == 16)
        <livewire:custom-check-box name="codeBase" titleName="Use item code" wire:model.live='codeBase'
            :isDisabled=false />
    @endif
</div>

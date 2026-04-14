<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

    <div style="max-height: 73vh; overflow-y: auto;" class="border">
        <div style="width:1500px;max-width:1900px;">
            <table class="table table-sm table-bordered table-hover">
                <thead class="text-xs bg-sky sticky-header">
                    <tr>
                        <th class="col-1">Code</th>
                        <th class="col-5">Description</th>
                        <th class="col-1">U/M</th>
                        <th class="col-1">New Qty</th>
                        <th class="col-1">New Cost</th>
                        <th class="col-1">Diff Qty</th>
                        <th class="col-1">Diff Cost</th>
                        @if ($STATUS == $openStatus || $STATUS == 16)
                            <th class="text-center col-1 hide-on-small">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="text-xs">
                    @foreach ($itemList as $list)
                        <tr>
                            <td>{{ $list->CODE }}</td>
                            <td>{{ $list->DESCRIPTION }}</td>
                            <td class="text-xs">
                                @if ($editItemId === $list->ID)
                                    <select wire:model.live='lineUnitId' name="lineUnitId"
                                        class="text-xs form-control form-control-sm">
                                        @foreach ($editUnitList as $listitem)
                                            <option value="{{ $listitem->ID }}">{{ $listitem->SYMBOL }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    {{ $list->SYMBOL }}
                                @endif
                            </td>
                            <td class="text-right">
                                @if ($editItemId == $list->ID)
                                    <input type="number" step="0.01" class="form-control form-control-sm text-right"
                                        name="lineQty" wire:model='lineQty' />
                                @else
                                    {{ number_format($list->QUANTITY, 0) }}
                                @endif
                            </td>
                            <td class="text-right">
                                @if ($editItemId == $list->ID)
                                    <input type="number" step="0.01" class="form-control form-control-sm text-right"
                                        name="lineUnitCost" wire:model='lineUnitCost' />
                                @else
                                    {{ number_format($list->UNIT_COST, 2) }}
                                @endif
                            </td>
                            <td class="text-right">
                                {{ number_format($list->QTY_DIFFERENCE, 0) }}
                            </td>
                            <td class="text-right">
                                {{ number_format($list->VALUE_DIFFERENCE, 0) }}
                            </td>
                            @if ($STATUS == $openStatus || $STATUS == 16)
                                <td class="text-center hide-on-small">
                                    @if ($editItemId == $list->ID)
                                        <button title="Update" id="updatebtn" wire:click="updateItem()"
                                            class="btn btn-xs btn-success">
                                            <i class="fas fa-check" aria-hidden="true"></i>
                                        </button>

                                        <button title="Cancel" id="cancelbtn" href="#" wire:click="cancelItem()"
                                            class="btn btn-xs btn-warning">
                                            <i class="fas fa-ban" aria-hidden="true"></i>
                                        </button>
                                    @else
                                        <button title="Edit" id="editbtn"
                                            wire:click='editItem( {{ $list->ID }})' class="btn btn-xs btn-info">
                                            <i class="fas fa-edit" aria-hidden="true"></i>
                                        </button>

                                        <button title="Delete" id="deletebtn"
                                            wire:click='deleteItem({{ $list->ID }})'
                                            wire:confirm="Are you sure you want to delete this?"
                                            class="btn btn-xs btn-danger">
                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                        </button>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    <tr class="hide-on-small">
                        {{-- INSERT FORM --}}
                        @if ($STATUS == $openStatus || $STATUS == 16)
                            <form wire:submit.prevent='saveItem' wire:loading.attr='disabled'>
                                <td class="text-md">
                                    @if ($saveSuccess)
                                        @if ($codeBase)
                                            <livewire:select-option name="ITEM_ID1" titleName="Item Code"
                                                :options="$itemCodeList" :zero="true" wire:model.live='ITEM_ID'
                                                :vertical="false" isDisabled="{{ false }}" :withLabel="false" />
                                        @else
                                            <label class="mt-1 text-xs"> {{ $ITEM_CODE }}</label>
                                        @endif
                                    @else
                                        @if ($codeBase)
                                            <livewire:select-option name="ITEM_ID2" titleName="Item Code"
                                                :options="$itemCodeList" :zero="true" wire:model.live='ITEM_ID'
                                                :vertical="false" isDisabled="{{ false }}" :withLabel="false" />
                                        @else
                                            <label class="mt-1 text-xs"> {{ $ITEM_CODE }}</label>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-md">
                                    @if ($saveSuccess)
                                        @if (!$codeBase)
                                            <livewire:select-option name="ITEM_ID3" titleName="Item Description"
                                                :options="$itemDescList" :zero="true" wire:model.live='ITEM_ID'
                                                :vertical="false" isDisabled="{{ false }}"
                                                :withLabel="false" />
                                        @else
                                            <label class="mt-1 text-xs"> {{ $ITEM_DESCRIPTION }}</label>
                                        @endif
                                    @else
                                        @if (!$codeBase)
                                            <livewire:select-option name="ITEM_ID4" titleName="Item Description"
                                                :options="$itemDescList" :zero="true" wire:model.live='ITEM_ID'
                                                isDisabled="{{ false }}" :vertical="false"
                                                :withLabel="false" />
                                        @else
                                            <label class="mt-1 text-xs"> {{ $ITEM_DESCRIPTION }}</label>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <select wire:model='UNIT_ID' name="UNIT_ID"
                                        class="text-sm form-control form-control-sm"
                                        @if ($ITEM_ID == 0) readonly @endif>
                                        @foreach ($unitList as $list)
                                            <option value="{{ $list->ID }}">{{ $list->SYMBOL }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="form-control form-control-sm text-right"
                                        name="Qty" wire:model='QUANTITY'
                                        @if ($ITEM_ID == 0) readonly @endif />
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="form-control form-control-sm text-right"
                                        name="UNIT_COST" wire:model='UNIT_COST'
                                        @if ($ITEM_ID == 0) readonly @endif />
                                </td>
                                <td class="text-right">

                                </td>
                                <td class="text-right">

                                </td>
                                <td>
                                    <div class="mt-1">
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
                            </form>
                        @endif

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="hide-on-small">
        @if ($STATUS == $openStatus || $STATUS == 16)
            <livewire:custom-check-box name="codeBase" titleName="Use item code" wire:model.live='codeBase'
                isDisabled="{{ false }}" />
        @endif
    </div>
    <div class='d-sm-none'>
        @if ($STATUS == $openStatus || $STATUS == 16)
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent='saveItem' wire:loading.attr='disabled'>
                        <div class="row">
                            <div class="col-12">
                                <label class="mt-1 text-xs"><b class="text-primary">Item Code:</b>
                                    {{ $ITEM_CODE }}</label>
                            </div>
                            <div class='col-12'>
                                @if ($saveSuccess)
                                    <livewire:select-option name="ITEM_ID5" titleName="Description" :options="$itemDescList"
                                        :zero="true" wire:model.live='ITEM_ID' :vertical="true"
                                        isDisabled="{{ false }}" :withLabel="true" />
                                @else
                                    <livewire:select-option name="ITEM_ID6" titleName="Description" :options="$itemDescList"
                                        :zero="true" wire:model.live='ITEM_ID' :vertical="true"
                                        isDisabled="{{ false }}" :withLabel="true" />
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-3">
                                        <label class="text-xs">Unit</label>
                                    </div>
                                    <div class="col-9">
                                        <select wire:model='UNIT_ID' name="UNIT_ID"
                                            class="text-sm form-control form-control-sm mt-1"
                                            @if ($ITEM_ID == 0) readonly @endif>
                                            @foreach ($unitList as $list)
                                                <option value="{{ $list->ID }}">{{ $list->SYMBOL }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class='col-12'>
                                <div class="row">
                                    <div class="col-3">
                                        <label class="text-xs">Quantity</label>
                                    </div>
                                    <div class="col-9">
                                        <input type="number" step="0.01"
                                            class="form-control form-control-sm mt-1 text-right" name="Qty"
                                            wire:model='QUANTITY' @if ($ITEM_ID == 0) readonly @endif />
                                    </div>
                                </div>
                            </div>
                            <div class='col-12'>
                                <div class="row">
                                    <div class="col-3">
                                        <label class="text-xs">Rate</label>
                                    </div>
                                    <div class="col-9">
                                        <input type="number" step="0.01"
                                            class="form-control form-control-sm mt-1 text-right" name="UNIT_COST"
                                            wire:model='UNIT_COST'
                                            @if ($ITEM_ID == 0) readonly @endif />
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-3">

                                    </div>
                                    <div class="col-9">
                                        <div class="mt-1">
                                            <button type="submit" wire:loading.attr='hidden'
                                                @if ($ITEM_ID == 0) disabled @endif
                                                class="text-white btn bg-sky btn-sm w-100">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <div wire:loading.delay>
                                                <span class="spinner"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        @endif

    </div>
</div>

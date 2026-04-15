<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-1">Code</th>
                <th class="col-5">Description</th>
                <th class="col-1">Qty</th>
                <th class="col-1">Unit</th>
                <th class="col-1">Rate</th>
                <th class="col-1">Amount</th>
                <th class="text-center">Tax</th>
                <th>Inv.Qty</th>
                <th>Closed</th>
                @if ($STATUS == $openStatus)
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
                            <input type="number" step="0.01" class="form-control form-control-sm mt-2 text-right"
                                name="lineQty" wire:model.live.debounce.1000ms='lineQty' wire:blur="getEditAmount" />
                        @else
                            {{ number_format($list->QUANTITY, 0) }}
                        @endif

                    </td>
                    <td class="text-sm">
                        @if ($editItemId === $list->ID)
                            <select wire:model='lineUnitId' name="lineUnitId"
                                class="text-sm form-control form-control-sm mt-2">
                                @foreach ($editUnitList as $listitem)
                                    <option value="{{ $listitem->ID }}">{{ $listitem->SYMBOL }}</option>
                                @endforeach
                            </select>
                        @else
                            {{ $list->SYMBOL }}
                        @endif

                    </td>
                    <td class="text-right">
                        @if ($editItemId === $list->ID)
                            <input type="number" step="0.01" class="form-control form-control-sm mt-2 text-right"
                                name="lineRate" wire:model.live.debounce.1000ms='lineRate' wire:blur="getEditAmount"
                                readonly />
                        @else
                            {{ number_format($list->RATE, 2) }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($editItemId === $list->ID)
                            <label class="mt-2">{{ number_format($lineAmount, 2) }}</label>
                        @else
                            {{ number_format($list->AMOUNT, 2) }}
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($editItemId === $list->ID)
                            <input type="checkbox" class="text-lg mt-2" wire:model='lineTax' name="lineTax" />
                        @else
                            @if ($list->TAXABLE)
                                <i class="fa fa-check-square-o" aria-hidden="true"></i>
                            @endif
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($list->INVOICED_QTY, 2) }}</td>
                    <td class="text-center">
                        @if ($list->CLOSED == true)
                            Yes
                        @else
                            No
                        @endif
                    </td>
                    @if ($STATUS == $openStatus)
                        <td class="text-center">
                            @if ($editItemId === $list->ID)
                                <button title="Update" id="updatebtn" wire:click="updateItem({{ $list->ID }})"
                                    class="text-success btn btn-sm btn-link">
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                </button>
                                <button title="Cancel" id="cancelbtn" href="#" wire:click="cancelItem()"
                                    class="text-warning btn btn-sm btn-link">
                                    <i class="fas fa-ban" aria-hidden="true"></i>
                                </button>
                            @else
                                <button title="Edit" id="editbtn"
                                    wire:click='editItem( {{ $list->ID }}, {{ $list->QUANTITY }} ,{{ $list->UNIT_ID ? $list->UNIT_ID : 0 }},{{ $list->RATE }},{{ $list->AMOUNT }},{{ $list->TAXABLE }},{{ $list->ITEM_ID }})'
                                    class="text-info btn btn-sm btn-link">
                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                </button>
                                <button title="Delete" id="deletebtn" wire:click='deleteItem({{ $list->ID }})'
                                    wire:confirm="Are you sure you want to delete this?"
                                    class="text-danger btn btn-sm btn-link">
                                    <i class="fas fa-times" aria-hidden="true"></i>
                                </button>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach

            {{-- INSERT FORM --}}
            @if ($STATUS == $openStatus)
                <form wire:submit.prevent='saveItem' wire:loading.attr='disabled'>
                    <tr>
                        <td class="text-md">
                            @if ($saveSuccess)
                                @if ($codeBase)
                                    <livewire:select-option name="ITEM_ID1" titleName="Item Code" :options="$itemCodeList"
                                        :zero="true" wire:model.live='ITEM_ID' :vertical="false"
                                        :withLabel="false" />
                                @else
                                    <label class="mt-2 text-xs"> {{ $ITEM_CODE }}</label>
                                @endif
                            @else
                                @if ($codeBase)
                                    <livewire:select-option name="ITEM_ID2" titleName="Item Code" :options="$itemCodeList"
                                        :zero="true" wire:model.live='ITEM_ID' :vertical="false"
                                        :withLabel="false" />
                                @else
                                    <label class="mt-2 text-xs"> {{ $ITEM_CODE }}</label>
                                @endif
                            @endif
                        </td>
                        <td class="text-md">
                            @if ($saveSuccess)
                                @if (!$codeBase)
                                    <livewire:select-option name="ITEM_ID3" titleName="Item Description"
                                        :options="$itemDescList" :zero="true" wire:model.live='ITEM_ID' :vertical="false"
                                        :withLabel="false" />
                                @else
                                    <label class="mt-2 text-xs"> {{ $ITEM_DESCRIPTION }}</label>
                                @endif
                            @else
                                @if (!$codeBase)
                                    <livewire:select-option name="ITEM_ID4" titleName="Item Description"
                                        :options="$itemDescList" :zero="true" wire:model.live='ITEM_ID'
                                        :vertical="false" :withLabel="false" />
                                @else
                                    <label class="mt-2 text-xs"> {{ $ITEM_DESCRIPTION }}</label>
                                @endif
                            @endif
                        </td>

                        <td>

                            <input type="number" step="0.01" class="form-control form-control-sm mt-2 text-right"
                                name="Qty" wire:model.live.debounce.1000ms='QUANTITY' wire:blur="getAmount"
                                @if ($ITEM_ID == 0) readonly @endif />
                        </td>
                        <td>
                            <select wire:model='UNIT_ID' name="UNIT_ID"
                                class="text-sm form-control form-control-sm mt-2"
                                @if ($ITEM_ID == 0) readonly @endif>
                                @foreach ($unitList as $list)
                                    <option value="{{ $list->ID }}">{{ $list->SYMBOL }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>

                            <input type="number" step="0.01" class="form-control form-control-sm mt-2 text-right"
                                name="rate" wire:model.live.debounce.1000ms='RATE' wire:blur="getAmount" />
                        </td>
                        <td class="text-right">
                            <label class="mt-2 text-sm">{{ number_format($AMOUNT, 2) }}</label>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" class="text-lg mt-2" wire:model='TAXABLE' name="taxable"
                                @if ($ITEM_ID == 0) disabled @endif />
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                            <div class="mt-2">
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
    @if ($STATUS == $openStatus)
        <livewire:custom-check-box name="codeBase" titleName="Use item code" wire:model.live='codeBase' />
    @endif
</div>

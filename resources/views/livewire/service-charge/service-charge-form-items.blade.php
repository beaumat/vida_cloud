<div>

    {{-- @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')]) --}}

    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th>Code</th>
                <th class="col-2">Description</th>
                <th class="col-2">Category</th>
                <th class="col-2"> Account</th>
                <th class="col-1 text-right">Qty</th>
                <th class="col-1 text-center">Unit</th>
                <th class="text-right col-1">Rate</th>
                <th class="text-right col-1">Amount</th>
                <th class="text-center">Tax</th>
                <th class="text-center "> Invoice </th>
                <th class="text-center col-2">Action</th>
            </tr>
        </thead>
        <tbody class="text-xs">
            @foreach ($itemList as $list)
                <tr class="@if ($list->other_charge) text-success font-weight-bold active @endif">
                    <td>{{ $list->CODE }}</td>
                    <td>{{ $list->DESCRIPTION }}</td>
                    <td> {{ $list->CLASS_DESCRIPTION }}</td>
                    <td>
                        @if ($editItemId == $list->ID)
                            <livewire:select-option name="lineINCOME_ACCOUNT_ID" titleName="" :options="$editAccountList"
                                :zero="true" wire:model.live='lineINCOME_ACCOUNT_ID' :vertical="false"
                                :withLabel="false" isDisabled="{{ false }}" />
                        @else
                            {{ $list->ACCOUNT_NAME }}
                        @endif

                    </td>
                    <td class="text-right">
                        @if ($editItemId == $list->ID)
                            <input type="number" step="0.01" class="form-control form-control-sm text-right"
                                @if (!$canBeQtyEdit) disabled @endif name="lineQty"
                                wire:model.live.debounce.1000ms='lineQty' wire:blur="getEditAmount" />
                        @else
                            {{ number_format($list->QUANTITY, 0) }}
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($editItemId === $list->ID)
                            <select wire:model='lineUnitId' name="lineUnitId" class="form-control form-control-sm">
                                <option value="0"></option>
                                @foreach ($editUnitList as $listitem)
                                    <option value="{{ $listitem->ID }}">{{ $listitem->SYMBOL }}</option>
                                @endforeach
                            </select>
                        @else
                            {{ $list->SYMBOL }}
                        @endif
                    </td>
                    <td class="text-right @if ($list->RATE == 0) text-primary font-weight-bold @endif">
                        @if ($editItemId === $list->ID)
                            <input type="number" step="0.01" class="form-control form-control-sm text-right"
                                name="lineRate" wire:model.live.debounce.1000ms='lineRate' wire:blur="getEditAmount"
                                @if ($editPrice == false) readonly @endif />
                        @else
                            {{ $list->RATE > 0 ? number_format($list->RATE, 2) : 'FREE' }}
                        @endif
                    </td>
                    <td class="text-right">
                        @if ($editItemId === $list->ID)
                            <label>{{ number_format($lineAmount, 2) }}</label>
                        @else
                            {{ number_format($list->AMOUNT, 2) }}
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($editItemId == $list->ID)
                            <input type="checkbox" class="text-lg" wire:model='lineTax' name="lineTax" />
                        @else
                            @if ($list->TAXABLE)
                                <i class="fa fa-check-square-o" aria-hidden="true"></i>
                            @endif
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($list->INVOICE_ID > 0)
                            <i class="fa fa-check-square-o text-success" aria-hidden="true"></i>
                        @else
                            {{ ' ' }}
                        @endif
                    <td class="text-center">
                        @if ($list->INVOICE_ID == 0)
                            @if ($editItemId === $list->ID)
                                <button title="Update" id="updatebtn" wire:click="updateItem({{ $list->ID }})"
                                    class="btn btn-xs btn-success">
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                </button>
                                <button title="Cancel" id="cancelbtn" href="#" wire:click="cancelItem()"
                                    class="btn btn-xs btn-warning">
                                    <i class="fas fa-ban" aria-hidden="true"></i>
                                </button>
                            @else
                                @if ($list->count_pay == 0 || $isAdmin == true || $alowedEdit == true)
                                    <button title="Edit Active" id="editbtn"
                                        wire:click='editItem( {{ $list->ID }}, {{ $list->QUANTITY }} ,{{ $list->UNIT_ID ? $list->UNIT_ID : 0 }},{{ $list->RATE }},{{ $list->AMOUNT }},{{ $list->TAXABLE }},{{ $list->ITEM_ID }})'
                                        class="btn btn-xs btn-info">
                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                    </button>

                                    <button title="Delete Active" id="deletebtn"
                                        wire:click='deleteItem({{ $list->ID }})'
                                        wire:confirm="Are you sure you want to delete this?"
                                        class="btn btn-xs btn-danger">
                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                    </button>
                                @else
                                    {{-- Disabled buttons --}}
                                    <button type="button" title="Edit Disabled" id="editbtn"
                                        class="btn btn-xs btn-secondary">
                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                    </button>
                                    <button type="button" title="Delete Disabled" id="deletebtn"
                                        class="btn btn-xs btn-secondary">
                                        <i class="fas fa-trash" aria-hidden="true"></i>
                                    </button>
                                @endif
                                <button class="btn btn-xs btn-primary" title="Cash Payment"
                                    wire:click="cashPayment({{ $list->ID }}, {{ $list->AMOUNT }})">
                                    <i class="fa fa-money" aria-hidden="true"></i>
                                </button>
                                <button class="btn btn-xs btn-success" title="Open Payment"
                                    wire:click="openPayment({{ $list->ID }}, {{ $list->AMOUNT }})"> <i
                                        class="fa fa-paypal" aria-hidden="true"></i> </button>
                            @endif
                        @else
                            <a target="_blank" class="btn btn-xs btn-success w-100"
                                href="{{ route('customersinvoice_edit', ['id' => $list->INVOICE_ID]) }}">View
                                Invoice</a>
                        @endif
                    </td>
                    {{-- @endif --}}
                </tr>
            @endforeach

            {{-- INSERT FORM --}}
            {{-- @if ($STATUS == $openStatus) --}}
            <form wire:submit.prevent='saveItem' wire:loading.attr='disabled'>
                <tr class="text-xs">
                    <td>
                        @if ($saveSuccess)
                            @if ($codeBase)
                                <livewire:select-option name="ITEM_ID1" titleName="Item Code" :options="$itemCodeList"
                                    :zero="true" wire:model.live='ITEM_ID' :vertical="false" :withLabel="false"
                                    isDisabled="{{ false }}" />
                            @else
                                <label class=" text-xs"> {{ $ITEM_CODE }}</label>
                            @endif
                        @else
                            @if ($codeBase)
                                <livewire:select-option name="ITEM_ID2" titleName="Item Code" :options="$itemCodeList"
                                    :zero="true" wire:model.live='ITEM_ID' :vertical="false" :withLabel="false"
                                    isDisabled="{{ false }}" />
                            @else
                                <label class=" text-xs"> {{ $ITEM_CODE }}</label>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if ($saveSuccess)
                            @if (!$codeBase)
                                <livewire:select-option name="ITEM_ID3" titleName="Item Description"
                                    :options="$itemDescList" :zero="true" wire:model.live='ITEM_ID' :vertical="false"
                                    :withLabel="false" isDisabled="{{ false }}" />
                            @else
                                <label class="text-xs"> {{ $ITEM_DESCRIPTION }}</label>
                            @endif
                        @else
                            @if (!$codeBase)
                                <livewire:select-option name="ITEM_ID4" titleName="Item Description"
                                    :options="$itemDescList" :zero="true" wire:model.live='ITEM_ID' :vertical="false"
                                    :withLabel="false" isDisabled="{{ false }}" />
                            @else
                                <label class=" text-xs"> {{ $ITEM_DESCRIPTION }}</label>
                            @endif
                        @endif
                    </td>
                    <td> <label class="text-xs"> {{ $CLASS_DESCRIPTION }}</label></td>

                    <td>
                        @if ($reloadAccount)
                            <livewire:select-option name="INCOME_ACCOUNT_ID" titleName="" :options="$accountList"
                                :zero="true" wire:model.live='INCOME_ACCOUNT_ID' :vertical="false"
                                :withLabel="false" isDisabled="{{ false }}" />
                        @else
                            <livewire:select-option name="INCOME_ACCOUNT_ID1" titleName="" :options="$accountList"
                                :zero="true" wire:model.live='INCOME_ACCOUNT_ID' :vertical="false"
                                :withLabel="false" isDisabled="{{ false }}" />
                        @endif
                    </td>
                    <td>
                        <input type="number" step="0.01" class="form-control form-control-sm text-xs text-right"
                            name="Qty" wire:model.live.debounce.1000ms='QUANTITY' wire:blur="getAmount"
                            @if ($ITEM_ID == 0) readonly @endif />
                    </td>
                    <td>
                        <select wire:model='UNIT_ID' name="UNIT_ID" @if ($ITEM_ID == 0) readonly @endif
                            class="text-xs text-center form-control form-control-sm ">
                            <option value="0"></option>
                            @foreach ($unitList as $list)
                                <option value="{{ $list->ID }}">{{ $list->SYMBOL }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" step="0.01" class="form-control form-control-sm text-xs text-right"
                            @if ($editPrice == false) readonly @endif name="rate"
                            wire:model.live.debounce.1000ms='RATE' wire:blur="getAmount" />
                    </td>
                    <td class="text-right">
                        <label class=" text-xs">{{ number_format($AMOUNT, 2) }}</label>
                    </td>
                    <td class="text-center">
                        <input @if ($ITEM_ID == 0) disabled @endif type="checkbox" class="text-lg"
                            wire:model='TAXABLE' name="taxable" />
                    </td>
                    <td> {{ ' ' }}</td>
                    <td>
                        <div class="">
                            <button type="submit" wire:loading.attr='hidden'
                                @if ($ITEM_ID == 0) disabled @endif
                                class="btn btn-success btn-xs w-100">
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
    <div class="form-group">
        <button class="btn btn-xs btn-primary" wire:click='OpenMultiPayment'>
            <i class="fa fa-money" aria-hidden="true"></i> Make Cash Payment
        </button>
    </div>
    {{-- @if ($STATUS == $openStatus) --}}
    {{-- <livewire:custom-check-box name="codeBase" titleName="Use item code" wire:model.live='codeBase'
        isDisabled="{{ false }}" /> --}}
    {{-- @endif --}}

    @livewire('ServiceCharge.PaymentAvailable', ['SERVICE_CHARGES_ID' => $SERVICE_CHARGES_ID])
    @livewire('ServiceCharge.CashPayment', ['SERVICE_CHARGES_ID' => $SERVICE_CHARGES_ID])
    @livewire('ServiceCharge.CashPaymentMulti', ['SERVICE_CHARGES_ID' => $SERVICE_CHARGES_ID])
</div>

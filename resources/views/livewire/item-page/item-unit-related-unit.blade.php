<div class="card bg-light">
    <div class="card-body ">
        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

        <table class="table table-sm table-bordered table-hover">
            <thead class="text-xs bg-sky">
                <tr>
                    <th>Units</th>
                    <th class="col-1">Symbol</th>
                    <th class="text-right col-1">Qty</th>
                    <th class="text-right col-2">Rate</th>
                    <th>Barcode</th>
                    <th class="text-center col-2">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody class="text-xs">
                @foreach ($unitRelatedList as $list)
                    <tr>
                        <td> {{ $list->NAME }}</td>
                        <td> {{ $list->SYMBOL }}</td>
                        <td class="text-right">
                            @if ($editItemId === $list->ID)
                                <input type="number" wire:model="newQUANTITY"
                                    class="form-control form-control-sm text-right">
                            @else
                                {{ $list->QUANTITY }}
                            @endif
                        </td>
                        <td class="text-right">
                            @if ($editItemId === $list->ID)
                                <input type="number" wire:model="newRATE"
                                    class="form-control form-control-sm text-right">
                            @else
                                {{ number_format($list->RATE, 2) }}
                            @endif
                        </td>
                        <td>
                            @if ($editItemId === $list->ID)
                                <input type="text" wire:model="newBARCODE" class="form-control form-control-sm">
                            @else
                                {{ $list->BARCODE }}
                            @endif
                        </td>
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
                                    wire:click="editItem({{ $list->ID }},{{ $list->QUANTITY }},{{ $list->RATE }},'{{ $list->BARCODE }}')"
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
                    </tr>
                @endforeach
                <form wire:submit.prevent='saveItem' wire:loading.attr='disabled'>
                    <tr class="text-xs">
                        <td>
                            @if ($saveSuccess)
                                <livewire:select-option name="UNIT_ID1" titleName="Units" :options="$units"
                                    isDisabled="{{ false }}" :zero="true" wire:model.live='UNIT_ID'
                                    :vertical="false" :withLabel="false" />
                            @else
                                <livewire:select-option name="UNIT_ID2" titleName="Units" :options="$units"
                                    isDisabled="{{ false }}" :zero="true" wire:model.live='UNIT_ID'
                                    :vertical="false" :withLabel="false" />
                            @endif
                        </td>
                        <td>
                            <label class="mt-3"> {{ $UNIT_SYMBOL }}</label>
                        </td>
                        <td>
                            <livewire:number-input name="QUANTITY" titleName="Quantity" wire:model='QUANTITY'
                                isDisabled="{{ false }}" :vertical="false" :withLabel="false" />
                        </td>
                        <td>
                            <livewire:number-input name="RATE" titleName="Rate" wire:model='RATE' :vertical="false"
                                isDisabled="{{ false }}" :withLabel="false" />
                        </td>
                        <td>
                            <livewire:text-input name="BARCODE" titleName="Barcode" wire:model='BARCODE'
                                isDisabled="{{ false }}" :vertical="false" :withLabel="false" />
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

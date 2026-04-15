<div class="container-fluid">


    <div class="row" @if ($itemId === 0) style="opacity: 0.5;pointer-events: none;" @endif>
        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
        <div class="col-md-12">
            <div class="card bg-light">
                <div class="card-body ">
                    <div class="col-md-12 mb-2">
                        <livewire:custom-check-box name="codeBase" titleName="Use item code" wire:model.live='codeBase'
                            isDisabled="{{ false }}" />
                    </div>
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="text-sm bg-sky">
                            <tr>
                                <th>Code</th>
                                <th> Description</th>
                                <th class="text-right col-1">Qty</th>
                                <th class="text-right col-2">Rate</th>
                                <th class="col-2 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach ($componentList as $list)
                                <tr>
                                    <td> {{ $list->CODE }}</td>
                                    <td> {{ $list->DESCRIPTION }}</td>
                                    <td class="text-right">
                                        @if ($editItemId === $list->ID)
                                            <input type="number" wire:model="newQty"
                                                class="form-control form-control-sm text-right">
                                        @else
                                            {{ number_format($list->QUANTITY, 2) }}
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if ($editItemId === $list->ID)
                                            <input type="number" wire:model="newRate"
                                                class="form-control form-control-sm text-right">
                                        @else
                                            {{ number_format($list->RATE, 2) }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($editItemId === $list->ID)
                                            <button type="button" title="Update" id="updatebtn"
                                                wire:click="updateItem({{ $list->ID }})"
                                                class="text-success btn btn-sm btn-link">
                                                <i class="fas fa-check" aria-hidden="true"></i>
                                            </button>
                                            <button type="button" title="Cancel" id="cancelbtn" href="#"
                                                wire:click="cancelItem()" class="text-warning btn btn-sm btn-link">
                                                <i class="fas fa-ban" aria-hidden="true"></i>
                                            </button>
                                        @else
                                            <button type="button" title="Edit" id="editbtn"
                                                wire:click='editItem({{ $list->ID . ',' . $list->QUANTITY . ',' . $list->RATE }})'
                                                class="text-info btn btn-sm btn-link">
                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                            </button>
                                            <button type="button" title="Delete" id="deletebtn"
                                                wire:click='deleteItem({{ $list->ID }})'
                                                wire:confirm="Are you sure you want to delete this?"
                                                class="text-danger btn btn-sm btn-link">
                                                <i class="fas fa-times" aria-hidden="true"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <form wire:submit.prevent='saveItem' wire:loading.attr='disabled'>
                                    <td>

                                        @if ($saveSuccess)
                                            @if ($codeBase)
                                                <livewire:select-option name="COMPONENT_ID1" titleName="Item Code"
                                                    isDisabled="{{ false }}" :options="$itemCodeList" :zero="true"
                                                    wire:model='COMPONENT_ID' :vertical="false" :withLabel="false" />
                                            @endif
                                        @else
                                            @if ($codeBase)
                                                <livewire:select-option name="COMPONENT_ID2" titleName="Item Code"
                                                    isDisabled="{{ false }}" :options="$itemCodeList" :zero="true"
                                                    wire:model='COMPONENT_ID2' :vertical="false" :withLabel="false" />
                                            @endif
                                        @endif

                                    </td>
                                    <td>

                                        @if ($saveSuccess)
                                            @if (!$codeBase)
                                                <livewire:select-option name="COMPONENT_ID3"
                                                    isDisabled="{{ false }}" titleName="Item Description"
                                                    :options="$itemDescList" :zero="true" wire:model='COMPONENT_ID'
                                                    :vertical="false" :withLabel="false" />
                                            @endif
                                        @else
                                            @if (!$codeBase)
                                                <livewire:select-option name="COMPONENT_ID4"
                                                    isDisabled="{{ false }}" titleName="Item Description"
                                                    :options="$itemDescList" :zero="true" wire:model='COMPONENT_ID'
                                                    :vertical="false" :withLabel="false" />
                                            @endif
                                        @endif

                                    </td>
                                    <td>

                                        <livewire:number-input name="QUANTITY" titleName="Quantity"
                                            isDisabled="{{ false }}" wire:model='QUANTITY' :vertical="false"
                                            :withLabel="false" />
                                    </td>
                                    <td>

                                        <livewire:number-input name="RATE" titleName="Rate" wire:model='RATE'
                                            isDisabled="{{ false }}" :vertical="false" :withLabel="false" />

                                    </td>
                                    <td>
                                        <div class="mt-2">
                                            <button type="submit" wire:loading.attr='hidden'
                                                class="text-white btn bg-sky btn-sm w-100">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <div wire:loading.delay>
                                                <span class="spinner"></span>
                                            </div>
                                        </div>
                                    </td>
                                </form>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>


    </div>

</div>

<div class="card bg-light">
    <div class="card-body">
        <div class="row">
            @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
        </div>
        <table class="table table-sm table-bordered table-hover">
            <thead class="text-xs bg-sky">
                <tr>
                    <th>Location</th>
                    <th class="text-right col-2">Purchases Unit</th>
                    <th class="text-right col-2">Sales Unit</th>
                    <th class="text-right col-2">Shipping Unit</th>
                    <th class="text-center col-2">Action</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach ($unitLocationList as $list)
                    <tr>
                        <td>{{ $list->LOCATION_NAME }}</td>
                        <td>
                            @if ($editItemId === $list->ID)
                                <select wire:model='newPURCHASES_UNIT_ID' id="newPO_ID"
                                    class="text-sm form-control form-control-sm">
                                    <option value="0"> Choose purchases unit </option>
                                    @foreach ($unitList as $option)
                                        <option value="{{ $option->ID }}">
                                            {{ $option->NAME }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                {{ $list->PURCHASES_UNIT }}
                            @endif
                        </td>
                        <td>
                            @if ($editItemId === $list->ID)
                                <select wire:model='newSALES_UNIT_ID' id="newSALE_ID"
                                    class="text-sm form-control form-control-sm">
                                    <option value="0"> Choose sales unit </option>
                                    @foreach ($unitList as $option)
                                        <option value="{{ $option->ID }}">
                                            {{ $option->NAME }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                {{ $list->SALES_UNIT }}
                            @endif
                        </td>
                        <td>
                            @if ($editItemId === $list->ID)
                                <select wire:model='newSHIPPING_UNIT_ID' id="newSHIP_ID"
                                    class="text-sm form-control form-control-sm">
                                    <option value="0"> Choose shipping unit </option>
                                    @foreach ($unitList as $option)
                                        <option value="{{ $option->ID }}">
                                            {{ $option->NAME }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                {{ $list->SHIPPING_UNIT }}
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
                                    wire:click='editItem({{ $list->ID . ',' . $list->PURCHASES_UNIT_ID . ',' . $list->SALES_UNIT_ID . ',' . $list->SHIPPING_UNIT_ID }})'
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
                    <td>
                        @if ($saveSuccess)
                            <livewire:select-option name="Unit_LOCATION_ID1" titleName="Location" :options="$locationList"
                                isDisabled="{{ false }}" :zero="true" wire:model='LOCATION_ID'
                                :vertical="false" :withLabel="false" />
                        @else
                            <livewire:select-option name="Unit_LOCATION_ID2" titleName="Location" :options="$locationList"
                                isDisabled="{{ false }}" :zero="true" wire:model='LOCATION_ID'
                                :vertical="false" :withLabel="false" />
                        @endif
                    </td>
                    <td>
                        @if ($saveSuccess)
                            <livewire:select-option name="PURCHASES_UNIT_ID1" titleName="Purchases Unit"
                                isDisabled="{{ false }}" :options="$unitList" :zero="true"
                                wire:model='PURCHASES_UNIT_ID' :vertical="false" :withLabel="false" />
                        @else
                            <livewire:select-option name="PURCHASES_UNIT_ID2" titleName="Purchases Unit"
                                isDisabled="{{ false }}" :options="$unitList" :zero="true"
                                wire:model='PURCHASES_UNIT_ID' :vertical="false" :withLabel="false" />
                        @endif
                    </td>
                    <td>
                        @if ($saveSuccess)
                            <livewire:select-option name="SALES_UNIT_ID1" titleName="Sales Unit" :options="$unitList"
                                isDisabled="{{ false }}" :zero="true" wire:model='SALES_UNIT_ID'
                                :vertical="false" :withLabel="false" />
                        @else
                            <livewire:select-option name="SALES_UNIT_ID2" titleName="Sales Unit" :options="$unitList"
                                isDisabled="{{ false }}" :zero="true" wire:model='SALES_UNIT_ID'
                                :vertical="false" :withLabel="false" />
                        @endif
                    </td>
                    <td>
                        @if ($saveSuccess)
                            <livewire:select-option name="SHIPPING_UNIT_ID1" titleName="Shipping Unit"
                                isDisabled="{{ false }}" :options="$unitList" :zero="true"
                                wire:model='SHIPPING_UNIT_ID' :vertical="false" :withLabel="false" />
                        @else
                            <livewire:select-option name="SHIPPING_UNIT_ID2" titleName="Shipping Unit"
                                isDisabled="{{ false }}" :options="$unitList" :zero="true"
                                wire:model='SHIPPING_UNIT_ID' :vertical="false" :withLabel="false" />
                        @endif
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
                </form>
            </tbody>
        </table>
    </div>
</div>

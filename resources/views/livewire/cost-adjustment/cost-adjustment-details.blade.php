<div>
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
    <table class="table table-sm table-bordered table-hover">
        <thead class="text-xs bg-sky">
            <tr>
                <th class="col-1">Code</th>
                <th class="col-5">Description</th>
                <th class="col-1">Cost</th>
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
                            <input type="number" step="0.01" class="form-control form-control-sm text-right"
                                name="lineRate" wire:model='EDIT_COST'
                                 />
                        @else
                            {{ number_format($list->COST, 2) }}
                        @endif
                    </td>
              
                 
                    @if ($STATUS == $openStatus)
                        <td class="text-center">
                            @if ($editItemId == $list->ID)
                                <button title="Update" id="updatebtn" wire:click="updateItem({{ $list->ID }})"
                                    class="btn btn-xs btn-success ">
                                    <i class="fas fa-check" aria-hidden="true"></i>
                                </button>
                                <button title="Cancel" id="cancelbtn" href="#" wire:click="cancelItem()"
                                    class="btn btn-warning btn btn-xs">
                                    <i class="fas fa-ban" aria-hidden="true"></i>
                                </button>
                            @else
                                <button title="Edit" id="editbtn"
                                    wire:click='editItem({{ $list->ID }})'
                                    class=" btn btn-xs btn-info">
                                    <i class="fas fa-edit" aria-hidden="true"></i>
                                </button>
                                <button title="Delete" id="deletebtn" wire:click='deleteItem({{ $list->ID }})'
                                    wire:confirm="Are you sure you want to delete this?"
                                    class=" btn btn-xs btn-danger">
                                    <i class="fas fa-trash" aria-hidden="true"></i>
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
                        <td  class="text-md">
                            @if ($saveSuccess)
                                @if ($codeBase)
                                    <livewire:select-option name="ITEM_ID1" titleName="Item Code" :options="$itemCodeList"
                                        :zero="true" wire:model.live='ITEM_ID' :vertical="false" isDisabled='{{ false }}'
                                        :withLabel="false" />
                                @else
                                    <label class="mt-2 text-xs"> {{ $ITEM_CODE }}</label>
                                @endif
                            @else
                                @if ($codeBase)
                                    <livewire:select-option name="ITEM_ID2" titleName="Item Code" :options="$itemCodeList"
                                        :zero="true" wire:model.live='ITEM_ID' :vertical="false" isDisabled='{{ false }}'
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
                                        :options="$itemDescList" :zero="true" wire:model.live='ITEM_ID' :vertical="false" isDisabled='{{ false }}'
                                        :withLabel="false" />
                                @else
                                    <label class="mt-2 text-xs"> {{ $ITEM_DESCRIPTION }}</label>
                                @endif
                            @else
                                @if (!$codeBase)
                                    <livewire:select-option name="ITEM_ID4" titleName="Item Description"
                                        :options="$itemDescList" :zero="true" wire:model.live='ITEM_ID'
                                        :vertical="false" :withLabel="false"  isDisabled='{{ false }}'/>
                                @else
                                    <label class="mt-2 text-xs"> {{ $ITEM_DESCRIPTION }}</label>
                                @endif
                            @endif
                        </td>                     
                        <td>
                            <input type="number" step="0.01" class="form-control form-control-sm  text-right"
                                name="rate" wire:model='COST' />
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
    @if ($STATUS == $openStatus)
        <livewire:custom-check-box name="codeBase" titleName="Use item code" wire:model.live='codeBase' isDisabled='{{ false }}' />
    @endif
</div>

<div>
    <button wire:click="openModal" class="btn btn-info btn-sm text-xs ">
        <i class="fa fa-cog" aria-hidden="true"></i> Trigger Setup
    </button>

    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Item Trigger</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

                        <table class="table table-sm table-boredered table-hover">
                            <thead class="text-xs bg-sky">
                                <tr>
                                    <th class="col-4"> Item </th>
                                    <th class="col-2"> Qty </th>
                                    <th class="col-3"> UM </th>
                                    <th class="col-3"> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataList as $list)
                                    <tr>
                                        <td>{{ $list->ITEM_NAME }}</td>
                                        <td>{{ $list->QUANTITY }}</td>
                                        <td>{{ $list->SYMBOL }}</td>
                                        <td><button class="btn btn-danger btn-xs w-100"
                                                wire:click='delete({{ $list->ID }})'
                                                wire:confirm="Are you sure you want to delete this?">
                                                <i class="fas fa-times" aria-hidden="true"></i>
                                            </button></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>
                                        <select wire:model.live='ITEM_ID' class="w-100">
                                            <option value="0"></option>
                                            @foreach ($itemList as $list)
                                                <option value="{{ $list->ID }}">
                                                    {{ $list->DESCRIPTION }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="w-100 text-xs" wire:model='QUANTITY' />
                                    </td>
                                    <td>
                                        <select wire:model='UNIT_ID' class="w-100">
                                            <option value="0"> </option>
                                            @foreach ($unitList as $list)
                                                <option value="{{ $list->ID }}">
                                                    {{ $list->SYMBOL }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="mt-0">
                                            <button type="button" wire:click='saveTrigger()' wire:loading.attr='hidden'
                                                class="text-white btn bg-success btn-xs w-100">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <div wire:loading.delay>
                                                <span class="spinner"></span>
                                            </div>
                                        </div>
                                    </td>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

<div>


    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content text-left">
                    <div class="modal-header">
                        <h6 class="modal-title">Actual base quantity on :
                            <span class="text-primary">{{ $ITEM_SOA_NAME }}</span>
                        </h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        <div class="container-flud">
                            <div class="form-group">
                                <table class="table table-bordered table-hover">
                                    <thead class="bg-sky text-xs ">
                                        <tr>
                                            <th class="col-2">CODE</th>
                                            <th class="col-7">DESCRIPTION</th>
                                            <th class="col-2 text-center">ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-xs">
                                        @foreach ($dataList as $list)
                                            <tr>
                                                <td>{{ $list->CODE }}</td>
                                                <td>{{ $list->DESCRIPTION }}</td>

                                                <td>
                                                    <button class="btn btn-xs btn-danger w-100"
                                                        wire:confirm="Are you delete : {{ $list->DESCRIPTION }}?"
                                                        wire:click='Delete({{ $list->ID }})'>
                                                        <i class="fa fa-times" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td>
                                                @if ($refreshItem)
                                                    <livewire:select-option name="ITEM_ID3" titleName="Item Description"
                                                        :options="$itemDescList" :zero="true" wire:model.live='ITEM_ID'
                                                        :vertical="false" isDisabled="{{ false }}"
                                                        :withLabel="false" />
                                                @else
                                                    <livewire:select-option name="ITEM_ID1" titleName="Item Description"
                                                        :options="$itemDescList" :zero="true" wire:model.live='ITEM_ID'
                                                        :vertical="false" isDisabled="{{ false }}"
                                                        :withLabel="false" />
                                                @endif
                                            </td>


                                            <td>
                                                <button class="btn btn-xs btn-success w-100" wire:click='Add()'>
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    @endif

</div>

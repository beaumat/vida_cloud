<div>
    <div class="row">

        <div class='col-md-6'>
            <div class='form-group'>
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <table class="table table-sm table-bordered table-hover">
                    <thead class='bg-sky text-xs'>
                        <tr>
                            <th>Item Requred</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        @foreach ($dataList as $list)
                            <tr>
                                <td>{{ $list->DESCRIPTION }}</td>
                                <td>
                                    <button title="Delete" class='btn btn-danger btn-xs w-100'
                                        wire:click='delete({{ $list->ID }})'>
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        {{-- insert --}}
                        <tr>
                            <td>
                                <livewire:select-option name="ITEM_ID" titleName="Item" :options="$itemList"
                                    :zero="true" wire:model.live='ITEM_ID' :vertical="false" :withLabel="false" :isDisabled='false' />
                            </td>
                            <td>
                                <button class='btn btn-primary btn-xs w-100' wire:click='save()'>
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

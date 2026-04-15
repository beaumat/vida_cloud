<div>
    <button wire:click="openModal" class="btn btn-success btn-xs text-xs w-100 ">
        <i class="fa fa-plus" aria-hidden="true"></i> NEW ASSET
    </button>

    @if ($showModal)
        <div class="modal show" id="modal-xl" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
            <div class="modal-dialog modal-xl" role="document"
                style="width: 90%; max-width: none; height: auto; margin: auto; top: 50%; transform: translateY(-50%);">
                <div class="modal-content text-left">
                    <div class="modal-header">
                        <h6 class="modal-title text-dark">Fixed Asset Item Registered</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                    <div class="modal-body">
                        <div class='row'>
                            <div class="col-4">
                                <input type="text" wire:model.live='search' placeholder="Search here..."
                                    class="form-control form-control-sm" />
                            </div>
                            <div class="col-4">
                            </div>
                            <div class="col-4">
                            </div>
                            <div class="col-12">
                                <div style="max-height: 73vh; overflow-y: auto;" class="border">
                                    <table class="table table-bordered table-hover mt-1">
                                        <thead class='bg-sky'>
                                            <th>CODE</th>
                                            <th>DESCRIPTION</th>
                                            <th>Cost</th>
                                            <th>Price</th>
                                            <th>Unit Base</th>
                                            <th>ASSET ACCOUNT</th>
                                            <th>ACTION</th>
                                        </thead>
                                        <tbody class="text-xs">
                                            @foreach ($dataList as $list)
                                                <tr class="text-dark">
                                                    <td>{{ $list->CODE }}</td>
                                                    <td>{{ $list->DESCRIPTION }}</td>
                                                    <td>{{ $list->COST }}</td>
                                                    <td>{{ $list->RATE }}</td>
                                                    <td>{{ $list->UNIT_NAME }}</td>
                                                    <td>{{ $list->ASSET_NAME }}</td>
                                                    <td>
                                                        <button class="btn btn-success btn-xs w-100"
                                                            wire:click='Add({{ $list->ID }})'>
                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

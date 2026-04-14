<div>
    @if ($showModal)
        <div class="modal show" id="modal-xl" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
            <div class="modal-dialog modal-xl" role="document"
                style="width: 90%; max-width: none; height: auto; margin: auto; top: 50%; transform: translateY(-50%);">
                <div class="modal-content text-left">
                    <div class="modal-header">
                        <div class="header-title">Not Include List</div>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-sm" wire:model.live.lazy='search'
                                placeholder="Search for Description then Press Enter" />
                        </div>
                        <div class="form-group">
                            <div id="tableContainer" style="max-height: 80vh; overflow-y: auto;">
                                <table class="table table-sm table-bordered table-hover">
                                    <thead class="text-xs bg-sky sticky-header">
                                        <tr>
                                            <th class="col-1">Code</th>
                                            <th class="col-4">Item Description</th>

                                            <th>Category</th>
                                            <th>Sub Category</th>


                                            <th class="col-1 text-center">Action</th>

                                        </tr>
                                    </thead>
                                    <tbody class="text-xs">
                                        @foreach ($itemList as $list)
                                            <tr>
                                                <td> {{ $list->CODE }}</td>
                                                <td> {{ $list->DESCRIPTION }}</td>
                                                <td> {{ $list->CLASS }} </td>
                                                <td> {{ $list->SUB_CLASS }}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-xs btn-success w-100"
                                                        wire:click='addOn({{ $list->ID }})'>
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
            </div>
        </div>
    @endif
</div>

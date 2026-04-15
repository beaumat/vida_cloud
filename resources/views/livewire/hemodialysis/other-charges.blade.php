<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6>{{ $ITEM_SUB_NAME }}</h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" wire:model.live.debounce.150ms='search'
                                    class="w-100 form-control form-control-sm" placeholder="Search" />

                            </div>
                            <div class="col-md-12">
                                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                                <table class="table table-sm table-bordered table-hover">
                                    <thead class="text-xs bg-sky">
                                        <tr>
                                            <th>Item</th>
                                            <th class="col-2 text-center">Add</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-xs">
                                        @foreach ($itemList as $list)
                                            <tr>
                                                <td> {{ $list->DESCRIPTION }}</td>
                                                <td>
                                                    <button class="btn btn-success active btn-xs w-100"
                                                        wire:click="Adding({{ $list->ID }})">
                                                        <i class="fas fa-plus" aria-hidden="true"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
        @livewire('Hemodialysis.OtherChargesModal')
    @endif
</div>

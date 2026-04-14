<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-lx" role="document">
                <div class="modal-content text-left">
                    <div class="modal-header">
                        <span wire:confirm='Are u sure?' wire:click='AutoSetDefault()'>
                            Doctor Order/Action
                        </span>
                    </div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        <table class="table table-sm table-bordered table-hover w-100">
                            <thead class="text-xs bg-sky">
                                <tr>
                                    <th>Doctor Order</th>
                                    <th class="col-3 text-center">Control</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($dataList as $item)
                                    <tr>
                                        <td>
                                            @if ($editID == $item->ID)
                                                <input class="form-control form-control-sm w-100"
                                                    wire:model='DOCTOR_ORDER' />
                                            @else
                                                <span class="text-sm"> {{ $item->DESCRIPTION }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($editID == $item->ID)
                                                <button title="save" wire:click='save()'
                                                    class="btn btn-sm btn-success"><i class="fa fa-floppy-o"
                                                        aria-hidden="true"></i></button>
                                                <button title="cancel" wire:click='cancel()'
                                                    class="btn btn-sm btn-warning"><i class="fa fa-ban"
                                                        aria-hidden="true"></i></button>
                                            @else
                                                <button title="edit" wire:click='edit({{ $item->ID }})'
                                                    class="btn btn-sm btn-info"><i class="fa fa-pencil"
                                                        aria-hidden="true"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class='modal-footer'>
                        <div class="container">
                            <div class="row">
                                <div class="col-6 text-left">
                                </div>
                                <div class="col-6 text-right">
                                    <button type="button" wire:click='closeModal()' class="btn btn-danger btn-sm">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>

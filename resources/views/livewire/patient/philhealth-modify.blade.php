<div>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document"
                style="margin: auto;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="text-primary">
                            PHIC 156 Adjustment
                        </h6>
                        <button type="button" class="close" wire:click="closeModal()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        <table class="table table-sm table-bordered table-hover">
                            <thead class="text-xs bg-sky">
                                <tr>
                                    <th class="col-1">Year</th>
                                    <th class="col-1">No of Used</th>
                                    <th class="col-1">No of Dialyzer</th>
                                    <th>Notes</th>
                                    <th class="col-2 text-center">Attachment</th>
                                    <th class="text-center col-1">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-xs">
                                @foreach ($dataList as $list)
                                    <tr>
                                        <td>

                                            @if ($list->ID == $E_ID)
                                                <input type="number" class="form-control form-control-sm"
                                                    wire:model='E_YEAR' />
                                            @else
                                                {{ $list->YEAR }}
                                            @endif


                                        </td>
                                        <td>
                                            @if ($list->ID == $E_ID)
                                                <input type="number" class="form-control form-control-sm"
                                                    wire:model='E_NO_OF_USED' />
                                            @else
                                                {{ $list->NO_OF_USED }}
                                            @endif

                                        </td>
                                        <td>
                                            @if ($list->ID == $E_ID)
                                                <input type="number" class="form-control form-control-sm"
                                                    wire:model='E_NO_OF_ITEM' />
                                            @else
                                                {{ $list->NO_OF_ITEM }}
                                            @endif

                                        </td>
                                        <td>

                                            @if ($list->ID == $E_ID)
                                                <input type="text" class="form-control form-control-sm"
                                                    wire:model='E_NOTES' />
                                            @else
                                                {{ $list->NOTES }}
                                            @endif


                                        </td>
                                        <td>
                                            @if ($list->ID == $E_ID)
                                                <div class="input-group input-group-xs">
                                                    <div class="custom-file text-xs">
                                                        <input type="file"
                                                            class="custom-file-input custom-file-input-xs text-xs"
                                                            id="fileUpload" wire:model='PDF'>
                                                        <label class="custom-file-label custom-file-label-xs text-xs"
                                                            for="fileUpload">
                                                            @if ($PDF)
                                                                {{ $PDF->getClientOriginalName() }}
                                                            @else
                                                                Choose file
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                            @else
                                                @if ($list->FILE_PATH)
                                                    <a target="_blank" class="btn btn-primary btn-xs w-100"
                                                        href="{{ asset('storage/' . $list->FILE_PATH) }}">
                                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i> ViewFile

                                                    </a>
                                                @endif
                                            @endif

                                        </td>
                                        <td>
                                            @if ($list->ID == $E_ID)
                                                <div class="row">
                                                    <div class="col-6">
                                                        <button type="button" class="btn btn-xs btn-primary w-100"
                                                            wire:click='Update()'>
                                                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <button type="button" class="btn btn-xs btn-warning w-100"
                                                            wire:click='Canceled()'>
                                                            <i class="fa fa-ban" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col-6">
                                                        <button type="button" class="btn btn-xs btn-info w-100"
                                                            wire:click='Edit({{ $list->ID }})'>
                                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                    <div class="col-6">
                                                        <button type="button" class="btn btn-xs btn-danger w-100"
                                                            wire:click='Delete({{ $list->ID }})'>
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" wire:model='YEAR' />
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm"
                                            wire:model='NO_OF_USED' />
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm"
                                            wire:model='NO_OF_ITEM' />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" wire:model='NOTES' />
                                    </td>
                                    <td>
                                        <div class="input-group input-group-xs">
                                            <div class="custom-file text-xs">
                                                <input type="file"
                                                    class="custom-file-input custom-file-input-xs text-xs"
                                                    id="fileUpload" wire:model='NEW_PDF'>
                                                <label class="custom-file-label custom-file-label-xs text-xs"
                                                    for="fileUpload">
                                                    @if ($NEW_PDF)
                                                        {{ $NEW_PDF->getClientOriginalName() }}
                                                    @else
                                                        Choose file
                                                    @endif
                                                </label>
                                            </div>
                                        </div>

                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-xs btn-success w-100"
                                            wire:click='Add()'><i class="fa fa-plus" aria-hidden="true"></i></button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <div class="container">
                            <div class="row">
                                <div class="col-6 text-left">
                                    <div class="row">
                                        <div class="col-6">
                                            Current : <b class="text-danger">{{ $TOTAL }}</b>
                                        </div>
                                        <div class="col-6">
                                            Balance : <b class="text-danger">{{ 156 - $TOTAL }}</b>
                                        </div>
                                    </div>


                                </div>
                                <div class="col-6 text-right">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        wire:click="closeModal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<div>
    @if ($showModal)
        <div class="modal show" id="modal-md" tabindex="-1" role="dialog"
            style="display: block; background-color: rgba(0, 0, 0, 0.5); position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden;">
            <div class="modal-dialog modal-xl" role="document"
                style="width: 90%; max-width: none; height: auto; margin: auto; top: 50%; transform: translateY(-50%);">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title"> Patient : <strong class="text-primary">{{ $PATIENT_NAME }}</strong>
                        </h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="max-height: 73vh; overflow-y: auto;">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

                        <div style="width:1550px;max-width:1600px;">
                            <div class='row'>
                                <div class="col-md-12">
                                    <table class="table table-sm table-bordered table-hover">
                                        <thead class="text-xs bg-sky sticky-header">
                                            <tr>
                                                <th class="col-1">TIME</th>
                                                <th class="text-center col-1">BP</th>
                                                <th class="text-center col-1">HR</th>
                                                <th class="text-center col-1">BFR</th>
                                                <th class="text-center col-1">AP | VP</th>
                                                <th class="text-center col-1">TFR</th>
                                                <th class="text-center col-1">TMP</th>
                                                <th class="text-center col-1">HEPARIN</th>
                                                <th class="text-center col-1">FLUSHING</th>
                                                <th class="text-center">NOTES</th>
                                                @if ($STATUS_ID == 1)
                                                    <th class="col-2 text-center">ACTION</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody class='text-xs'>
                                            @foreach ($dataList as $list)
                                                <tr>
                                                    <td class='text-center'>
                                                        @if ($list->ID == $EDIT_ID)
                                                            <input type="time" name="TIME" class="text-xs w-100"
                                                                wire:model='EDIT_TIME' />
                                                        @else
                                                            {{ date('h:i:s A', strtotime($list->TIME)) }}
                                                        @endif
                                                    </td>
                                                    <td class='text-center'>

                                                        @if ($list->ID == $EDIT_ID)
                                                            <input type="text" name="EDIT_BP_1" class="text-xs w-50"
                                                                wire:model='EDIT_BP_1' /><input type="text"
                                                                name="EDIT_BP_2" class="text-xs w-50"
                                                                wire:model='EDIT_BP_2' />
                                                        @else
                                                            {{ $list->BP_1 }}/{{ $list->BP_2 }}
                                                        @endif

                                                    </td>
                                                    <td class='text-center'>

                                                        @if ($list->ID == $EDIT_ID)
                                                            <input type="text" name="EDIT_HR" class="text-xs"
                                                                wire:model='EDIT_HR' />
                                                        @else
                                                            {{ $list->HR }}
                                                        @endif

                                                    </td>
                                                    <td class='text-center'>

                                                        @if ($list->ID == $EDIT_ID)
                                                            <input type="text" name="EDIT_BFR" class="text-xs"
                                                                wire:model='EDIT_BFR' />
                                                        @else
                                                            {{ $list->BFR }}
                                                        @endif

                                                    </td>
                                                    <td class='text-center'>

                                                        @if ($list->ID == $EDIT_ID)
                                                            <input type="text" name="EDIT_AP" class="text-xs w-50"
                                                                wire:model='EDIT_AP' /><input type="text"
                                                                name="EDIT_VP" class="text-xs w-50"
                                                                wire:model='EDIT_VP' />
                                                        @else
                                                            {{ $list->AP }}|{{ $list->VP }}
                                                        @endif

                                                    </td>
                                                    <td class='text-center'>
                                                        @if ($list->ID == $EDIT_ID)
                                                            <input type="text" name="EDIT_TFP" class="text-xs"
                                                                wire:model='EDIT_TFP' />
                                                        @else
                                                            {{ $list->TFP }}
                                                        @endif

                                                    </td>
                                                    <td class='text-center'>

                                                        @if ($list->ID == $EDIT_ID)
                                                            <input type="text" name="EDIT_TMP" class="text-xs"
                                                                wire:model='EDIT_TMP' />
                                                        @else
                                                            {{ $list->TMP }}
                                                        @endif

                                                    </td>
                                                    <td class='text-center'>

                                                        @if ($list->ID == $EDIT_ID)
                                                            <input type="text" name="EDIT_HEPARIN"
                                                                class="text-xs w-100" wire:model='EDIT_HEPARIN' />
                                                        @else
                                                            {{ $list->HEPARIN }}
                                                        @endif

                                                    </td>
                                                    <td class='text-center'>

                                                        @if ($list->ID == $EDIT_ID)
                                                            <input type="text" name="EDIT_FLUSHING"
                                                                class="text-xs w-100" wire:model='EDIT_FLUSHING' />
                                                        @else
                                                            {{ $list->FLUSHING }}
                                                        @endif

                                                    </td>
                                                    <td class='text-left'>

                                                        @if ($list->ID == $EDIT_ID)
                                                            <input type="text" name="EDIT_NOTES"
                                                                class="text-xs w-100" wire:model='EDIT_NOTES' />
                                                        @else
                                                            {{ $list->NOTES }}
                                                        @endif

                                                    </td>
                                                    @if ($STATUS_ID == 1)
                                                        <td class='text-center'>
                                                            @if ($list->ID === $EDIT_ID)
                                                                <button type="button" name="update"
                                                                    class="btn btn-xs btn-success"
                                                                    wire:click="update()">
                                                                    <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                                                </button>
                                                                <button type="button" name="cancel"
                                                                    class="btn btn-xs btn-warning"
                                                                    wire:confirm='are you sure to cancel?'
                                                                    wire:click="cancel()">
                                                                    <i class="fa fa-ban" aria-hidden="true"></i>
                                                                </button>
                                                            @else
                                                                <button type="button" name="edit"
                                                                    class="btn btn-xs btn-info"
                                                                    wire:click="edit({{ $list->ID }})">
                                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                </button>

                                                                <button type="button" name="delete"
                                                                    class="btn btn-xs btn-danger"
                                                                    wire:confirm='are you sure to delete?'
                                                                    wire:click="delete({{ $list->ID }})">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </button>
                                                            @endif

                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                    {{-- INSERT --}}
                                    @if ($STATUS_ID == 1)
                                        <div class='col-md-3 col-3'>
                                            <div class="card card-sm m-1 ">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-12 col-md-12">
                                                            <div class="row">
                                                                <div class="col-12  mb-2">
                                                                    <input type="time" name="TIME"
                                                                        class="text-xs form-control form-control-sm"
                                                                        wire:model='TIME' placeholder="Time" />
                                                                </div>
                                                                <div class="col-12 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-6">
                                                                            <input type="number" name="BP_1"
                                                                                class="text-xs form-control form-control-sm"
                                                                                wire:model='BP_1'
                                                                                placeholder="BP[1]" />
                                                                        </div>
                                                                        <div class="col-md-6 col-6">
                                                                            <input type="number" name="BP_2"
                                                                                class="text-xs form-control form-control-sm"
                                                                                wire:model='BP_2'
                                                                                placeholder="BP[2]" />
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                                <div class="col-6 mb-2"><input type="number"
                                                                        name="HR"
                                                                        class="text-xs form-control form-control-sm"
                                                                        wire:model='HR' placeholder="HR" /></div>
                                                                <div class="col-6 mb-2">
                                                                    <input type="number" name="BFR"
                                                                        class="text-xs form-control form-control-sm"
                                                                        wire:model='BFR' placeholder="BFR" />
                                                                </div>
                                                                <div class="col-12 mb-2">
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-6">
                                                                        <input type="number" name="AP"
                                                                                class="text-xs form-control form-control-sm"
                                                                                placeholder="AP" wire:model='AP' />
                                                                        </div>
                                                                        <div class="col-md-6 col-6">
                                                                            <input type="number" name="VP"
                                                                                class="text-xs form-control form-control-sm"
                                                                                wire:model='VP' placeholder="VP" />
                                                                        </div>
                                                                    </div>



                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <input type="text" name="TFP"
                                                                        class="text-xs form-control form-control-sm"
                                                                        wire:model='TFP' placeholder="TFR" />
                                                                </div>
                                                                <div class="col-6 mb-2">
                                                                    <input type="number" name="TMP"
                                                                        class="text-xs form-control form-control-sm"
                                                                        wire:model='TMP' placeholder="TMP" />
                                                                </div>

                                                                <div class="col-6 mb-2"><input type="text"
                                                                        name="HEPARIN"
                                                                        class="text-xs form-control form-control-sm"
                                                                        wire:model='HEPARIN' placeholder="Heparin" />
                                                                </div>
                                                                <div class="col-6 mb-2"><input type="text"
                                                                        name="FLUSHING"
                                                                        class="text-xs form-control form-control-sm"
                                                                        wire:model='FLUSHING'
                                                                        placeholder="Flushing" />
                                                                </div>
                                                                <div class="col-12 mb-2"><input type="text"
                                                                        name="NOTES"
                                                                        class="text-xs form-control form-control-sm"
                                                                        wire:model='NOTES' placeholder="Notes" />
                                                                </div>
                                                                <div class="col-12 mb-2">
                                                                    <button wire:loading.attr='disabled'
                                                                        type="button"
                                                                        class="btn btn-sm btn-success w-100"
                                                                        wire:click='save()'><i class="fa fa-plus"
                                                                            aria-hidden="true"></i> Add</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm"
                            wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

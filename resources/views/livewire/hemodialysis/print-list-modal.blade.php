<div>
    <button wire:click="openModal" class="btn btn-warning btn-sm text-xs" title="Multiple Select Print Form">
        <i class="fa fa-print" aria-hidden="true"></i>
    </button>

    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Multiple Select </h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                            <div class="form-group row">
                                <div class="col-3 text-left">
                                    <label class="text-xs">Date :</label>
                                </div>
                                <div class="col-md-9 text-left">
                                    <livewire:date-input name="DATE" :withLabel="false" titleName="Date" wi
                                        wire:model.live='DATE' :isDisabled="false" />
                                </div>
                                <div class="col-3 text-left">
                                    <label class="text-xs">Select :</label>
                                </div>
                                <div class="col-9">
                                    <select class="form-control form-control-sm text-xs text-left"
                                        wire:model.live='SHIFT_ID'>
                                        <option value="0"> All Shift </option>
                                        @foreach ($shiftList as $list)
                                            <option value="{{ $list->ID }}"> {{ $list->NAME }} Shift</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th> <input class="text-lg" type="checkbox" wire:model.live="SelectAll" /></th>
                                        <th class="col-2 text-left">No.</th>
                                        <th class="col-9 text-left">Patient Name</th>
                                        <th class="col-1">Shift</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($hemoList as $list)
                                        <tr>
                                            <td class="text-center">
                                                <input class="text-lg" type="checkbox"
                                                    wire:model.live="hemoSelected.{{ $list->ID }}" />
                                            </td>
                                            <td class="text-left">
                                                <label for=""> {{ $list->CODE }}</label>
                                            </td>

                                            <td class="text-left">
                                                <label for=""> {{ $list->PATIENT_NAME }}</label>
                                            </td>
                                            <td class="text-left">
                                                <label for=""> {{ $list->SHIFT }}</label>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="modal-footer">


                        <button class="btn btn-warning btn-sm" wire:click='print'>
                            <i class="fa fa-print" aria-hidden="true"></i> Front View
                        </button>
                        <button class="btn btn-dark btn-sm" wire:click='printback'>
                            <i class="fa fa-print" aria-hidden="true"></i> Back View
                        </button>
                        <button class="btn btn-info btn-sm" wire:click='printfrontback'>
                            <i class="fa fa-print" aria-hidden="true"></i> Both View
                        </button>

                        <button type="button" class="btn btn-danger btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

@script
    <script>
        $wire.on('openNewTab', (eventData) => {
            window.open(eventData.data, '_blank');
        });
    </script>
@endscript

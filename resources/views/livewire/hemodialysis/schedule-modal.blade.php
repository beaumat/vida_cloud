<div>
    <button wire:click="openModal" class="btn btn-info btn-sm text-xs " title="Schedule Create Form">
        <i class="fa fa-calendar" aria-hidden="true"></i>
    </button>
    @if ($showModal)
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">Schedule on <input type='date' class="text-xs w-50"
                                wire:model.live='DATE' /></h6>
                        <button type="button" class="close" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                        <div class="form-group row">
                            <div class="col-3 text-left">
                                <label class="text-xs">Select :</label>
                            </div>
                            <div class="col-6">
                                <select class="form-control form-control-sm text-xs text-left"
                                    wire:model.live='SHIFT_ID'>
                                    <option value="0"> All Shift </option>
                                    @foreach ($shiftList as $list)
                                        <option value="{{ $list->ID }}"> {{ $list->NAME }} Shift</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <label class="text-xs">With Back </label>
                                <input type="checkbox" wire:model='withBack' />
                            </div>
                        </div>
                        <div class="form-group">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th>
                                            <input class="text-lg" type="checkbox" wire:model.live="SelectAll" />
                                        </th>
                                        <th class="col-8 text-left">Patient Name</th>
                                        <th class="col-2 text-left">Shift </th>
                                        <th class="col-2 text-left">Type</th>

                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td class="text-center">
                                                <input class="text-lg" type="checkbox"
                                                    wire:model.live="scheduleSelected.{{ $list->ID }}" />
                                            </td>
                                            <td class="text-left">
                                                <label for=""> {{ $list->PATIENT_NAME }}</label>
                                            </td>
                                            <td class="text-left">
                                                <label for=""> {{ $list->SHIFT }}</label>
                                            </td>
                                            <td class="text-left">
                                                <label for=""> {{ $list->PATIENT_TYPE }}</label>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-success btn-sm" wire:click="create">Create &
                            Print</button>

                        <button type="button" class="btn btn-secondary btn-sm" wire:click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@script
    <script>
        $wire.on('schedOpenNewTab', (eventData) => {
            window.open(eventData.data, '_blank');
        });
    </script>
@endscript

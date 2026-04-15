<div>
    @if ($showModal)
        <div class="modal-wrapper">
            <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
                <div class="modal-dialog modal-sm modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <label class="model-title">Print Schedule</label>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-12">

                                    <label for="WEEK_LEVEL" class="text-xs">Week Levels</label>
                                    <select wire:model.live='WEEKLY_ID' id="WEEK_LEVEL"
                                        class="form-control form-control-sm">
                                        @foreach ($weekLevels as $option)
                                            <option value="{{ $option['ID'] }}">
                                                {{ $option['DESCRIPTION'] }} ({{ $option['TOTAL'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="SHIFT_ID" class="text-xs">Selected Shift</label>
                                    <select wire:model.live='SHIFT_ID' id="SHIFT_ID"
                                        class="form-control form-control-sm">
                                        <option value="0">
                                            All
                                        </option>
                                        @foreach ($shiftList as $option)
                                            <option value="{{ $option->ID }}">
                                                @if ($option->DESCRIPTION)
                                                    {{ $option->DESCRIPTION }}
                                                @else
                                                    @if ($option->NAME)
                                                        {{ $option->NAME }}
                                                    @else
                                                        {{ $option->CODE }}
                                                    @endif
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">

                            <a target="_BLANK"
                                href="{{ route('patientsschedules_print', [
                                    'week' => $WEEKLY_ID,
                                    'location' => $LOCATION_ID,
                                    'year' => $YEAR,
                                    'month' => $MONTH,
                                    'shift' => $SHIFT_ID,
                                ]) }}"
                                class="btn btn-sm btn-primary"> Preview </a>
                            <button class="btn btn-sm btn-danger" wire:click='closeModal'>Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

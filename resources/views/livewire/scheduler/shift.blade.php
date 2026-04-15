<div>
    <div class="row" id='{{ $ID }}' wire:loading.class="loading-form" wire:target="save"
        @if ($CONTACT_ID === 0 || $STATUS_ID > 0 || $EXIST_HEMO > 0) style="opacity: 0.5;pointer-events: none;" @endif>
        <div class="col-md-6">
            <div class="form-group">
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-checkbox" wire:click="save({{ 0 }})" type="radio"
                            wire:model="SHIFT_ID" value="0" />
                        N/A
                    </label>
                </div>
                @foreach ($shiftList as $list)
                    <div class="form-check">
                        <label @if ($SHIFT_ID == $list->ID) style="font-weight:bolder;color:blue" @endif
                            class="form-check-label">
                            <input class="form-checkbox" wire:click="save({{ $list->ID }})" type="radio"
                                wire:model="SHIFT_ID" value="{{ $list->ID }}" />
                            {{ $list->NAME }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-6">
            @if ($this->SHIFT_ID > 0)
                <select class="w-100" wire:model.live='HEMO_MACHINE_ID'>
                    @if ($hemoMachineList)
                        @foreach ($hemoMachineList as $list)
                            <option value="{{ $list->ID }}"> {{ $list->DESCRIPTION }}</option>
                        @endforeach
                    @endif
                </select>
                <br />
                <div class="mt-4">
                    <button class="btn btn-xs btn-primary" wire:click='openList'>
                        <i class="fa fa-history" aria-hidden="true"></i>
                    </button>
                </div>
            @endif
        </div>

    </div>
    <div wire:loading wire:target="save">
        <span class="spinner text-xs"></span>
    </div>

</div>

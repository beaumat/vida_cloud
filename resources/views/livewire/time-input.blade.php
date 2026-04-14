<div class="mt-0">
    @if ($vertical)
        <div class="row">
            <div class="col-3">
                @if ($withLabel)
                    <label for="{{ $name }}" class="text-xs">{{ $titleName }}</label>
                @endif
            </div>

            <div class="col-9">
                <input type="time" autocomplete="off" wire:model='value' class="text-xs form-control form-control-sm"
                    id="{{ $name }}"  @if ($withLabel) placeholder="Enter {{ Str::lower($titleName) }}" @endif
                    @if ($isDisabled) readonly @endif />
            </div>
        </div>
    @else
        @if ($withLabel)
            <label for="{{ $name }}" class="text-xs">{{ $titleName }}</label>
        @endif
        
        <input type="time" autocomplete="off" wire:model='value' class="text-xs form-control form-control-sm"
            id="{{ $name }}"
            @if ($withLabel) placeholder="Enter {{ Str::lower($titleName) }}" @endif
            @if ($isDisabled) readonly @endif />
    @endif
</div>

<div class="mt-0">
    @if ($vertical)
        <div class="row">
            <div class="col-3">
                @if ($withLabel)
                    <label for="{{ $name }}" class="text-xs">{{ $titleName }}</label>
                @endif
            </div>

            <div class="col-9">
                <input type="text" maxlength="{{ $maxlength }}" autocomplete="off" wire:model='value' class="text-xs form-control form-control-sm2"
                    id="{{ $name }}" @if ($isDisabled) disabled @endif />
            </div>
        </div>
    @else
        @if ($withLabel)
            <label for="{{ $name }}" class="text-xs" style="width: 300px;">{{ $titleName }}</label>
        @endif
        <input type="text" maxlength="{{ $maxlength }}" autocomplete="off" wire:model='value' class="text-xs form-control form-control-sm2" 
            id="{{ $name }}" @if ($isDisabled) disabled @endif />
    @endif
</div>
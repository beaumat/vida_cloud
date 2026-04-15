<div style="margin-top:10px" class="custom-control custom-checkbox">
    <input class="text-xs custom-control-input" wire:model.live='value' type="checkbox" id="{{ $name }}"
        @if ($isDisabled) disabled @endif>
    @if ($withLabel)
        <label for="{{ $name }}" class="text-xs custom-control-label">{{ $titleName }}</label>
    @endif
</div>

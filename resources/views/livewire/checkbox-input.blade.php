<div class="form-group">
    <input class="form-check-input ml-2" type="checkbox" wire:model='value'
        @if ($isDisabled) disabled @endif name="{{ $name }}" id="{{ $name }}" />
    <label class="form-check-label text-xs ml-4" for="{{ $name }}">{{ $titleName }}</label>
</div>

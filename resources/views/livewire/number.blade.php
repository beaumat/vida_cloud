<input type="number" step="any" wire:model='value'
    class="text-xs w-100 @if ($isFocused) text-left @else text-right @endif" name="{{ $name }}"
    wire:focus="$set('isFocused', true)" wire:blur="$set('isFocused', false)" id="decimalInput" />

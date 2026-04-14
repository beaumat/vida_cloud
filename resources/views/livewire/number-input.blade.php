<div class="mt-0">

    @if ($vertical)
        <div class="row">
            <div class="col-3">
                @if ($withLabel)
                    <label for="{{ $name }}" class="text-xs">{{ $titleName }}</label>
                @endif
            </div>
            <div class="col-9">
                <input type="number" step="any" type="text" id="decimalInput" wire:model='value'
                    {{-- wire:dblclick='OpenCalculator()' --}}
                    class="text-xs form-control form-control-sm2 @if ($isFocused) text-left @else text-right @endif"
                    id="{{ $name }}" @if ($isDisabled) disabled @endif
                    wire:focus="$set('isFocused', true)" wire:blur="$set('isFocused', false)" />
            </div>
        </div>
    @else
        @if ($withLabel)
            <label for="{{ $name }}" class="text-xs">{{ $titleName }}</label>
        @endif
        <input type="number" step="any" type="text" id="decimalInput" wire:model='value'   wire:dblclick='OpenCalculator()'
            class="text-xs form-control form-control-sm2 @if ($isFocused) text-left @else text-right @endif"
            id="{{ $name }}" @if ($isDisabled) disabled @endif
            wire:focus="$set('isFocused', true)" wire:blur="$set('isFocused', false)" />
    @endif


</div>

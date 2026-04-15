<div class="mt-0">
    @if ($vertical)
        <div class="row">
            <div class="col-3">
                @if ($withLabel)
                    <label for="{{ $name }}" class="text-xs">{{ $titleName }}</label>
                @endif
            </div>

            <div class="col-9">
                <textarea class="text-xs form-control form-control-sm2" rows='6' maxlength="{{ $maxlength }}"
                    wire:model='value' id="{{ $name }}" @if ($isDisabled) disabled @endif></textarea>
            </div>
        </div>
    @else
        @if ($withLabel)
            <label for="{{ $name }}" class="text-xs" style="width: 300px;">{{ $titleName }}</label>
        @endif


        <textarea class="text-xs form-control form-control-sm2" rows='6' maxlength="{{ $maxlength }}"
             wire:model='value' id="{{ $name }}" @if ($isDisabled) disabled @endif></textarea>
    @endif
</div>

<div class="mt-0">
    @if ($vertical)
        <div class="row">
            <div class="col-3">
                @if ($withLabel)
                    <label for="{{ $name }}" class="text-xs"> {{ $titleName }}</label>
                @endif
            </div>
            <div class="col-9">
                <select wire:model='value' id="{{ $name }}" class="form-control form-control-sm text-xs"
                    @if ($isDisabled) disabled @endif>
                    @if ($zero)
                        <option value="0">
                            &nbsp;
                        </option>
                    @endif
                    @foreach ($options as $option)
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
    @else
        @if ($withLabel)
            <label for="{{ $name }}" class="text-xs"> {{ $titleName }}</label>
        @endif
        <select wire:model='value' id="{{ $name }}" class="form-control form-control-sm text-xs"
            @if ($isDisabled) disabled @endif>
            @if ($zero)
                <option value="0"> &nbsp; </option>
            @endif
            @foreach ($options as $option)
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
    @endif

</div>



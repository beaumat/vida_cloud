<div wire:ignore class="text-md">
    @if ($vertical)
        <div class="row">
            <div class="col-3">
                @if ($withLabel)
                    <label for="{{ $name }}" class="text-xs"> {{ $titleName }}</label>
                @endif
            </div>
            <div class="col-9">
                <select wire:model='value' id="{{ $name }}" class="form-control form-control-sm "
                    @if ($isDisabled) disabled @endif>
                    @if ($zero)
                        <option value="0">
                            &nbsp;
                        </option>
                    @endif
                    @if ($options)
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
                                - {{ $option->TYPE }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    @else
        @if ($withLabel)
            <label for="{{ $name }}" class="text-xs"> {{ $titleName }}</label>
        @endif
        <select wire:model='value' id="{{ $name }}" class="form-control form-control-sm"
            @if ($isDisabled) disabled @endif>
            @if ($zero)
                <option value="0">
                    &nbsp;
                </option>
            @endif
            @if ($options)
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
                        - {{ $option->TYPE }}

                    </option>
                @endforeach
            @endif
        </select>
    @endif

</div>

@script
    <script>
        $(document).ready(function() {
            $('#{{ $name }}').select2();
            $('#{{ $name }}').siblings('.select2-container').css('width', '100%');
            $('#{{ $name }}').on('change', function(event) {
                $wire.$set('value', event.target.value);
                $('#{{ $name }}').val(event.target.value);
                $('#{{ $name }}').trigger('change.select2');
            });
        });
    </script>
@endscript

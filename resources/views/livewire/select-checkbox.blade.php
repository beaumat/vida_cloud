<div wire:ignore class="mt-0 text-sm">
    @if ($vertical)
        <div class="row">
            <div class="col-3 text-right">
                @if ($withLabel)
                    <label for="{{ $name }}" class="text-xs"> {{ $titleName }}</label>
                @endif
            </div>
            <div class="col-9">
                <select wire:model="selectedOptions" id="{{ $name }}" class="form-control form-control-sm" multiple>
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
        <select wire:model="selectedOptions" id="{{ $name }}" class="form-control form-control-sm" multiple>
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
                    </option>
                @endforeach
            @endif
        </select>
    @endif
</div>

@script
<script>
    $(document).ready(function() {
        $('#{{ $name }}').select2({
            closeOnSelect: false,  // Allow multiple selections with checkboxes
            placeholder: "Select options"
        });

        $('#{{ $name }}').on('change', function(event) {
            var data = $(this).val();
            @this.set('selectedOptions', data); // Sync with Livewire
        });

        // Initialize the select2 with existing selected options
        $('#{{ $name }}').val(@this.selectedOptions).trigger('change');
    });

    // Listen to Livewire update event
    Livewire.on('refreshSelect2', function() {
        $('#{{ $name }}').select2({
            closeOnSelect: false,
            placeholder: "Select options"
        }).trigger('change');
    });
</script>
@endscript

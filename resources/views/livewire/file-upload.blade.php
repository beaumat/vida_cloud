<div class="form-group">
    <label for="fileUpload" class="text-xs">PDF document file @if ($value)
            <i class="fa fa-check-circle text-success" aria-hidden="true"></i>
        @endif
    </label>
    <div class="input-group input-group-sm">
        <div class="custom-file text-xs">
            <input type="file" class="custom-file-input text-xs" id="fileUpload" wire:model='value'>
            <label class="custom-file-label text-xs" for="fileUpload">
                @if ($value)
                    {{ $value->getClientOriginalName() }}
                @else
                    Choose file
                @endif
            </label>
        </div>

    </div>
</div>

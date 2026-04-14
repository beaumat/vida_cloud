    <div class="row">
        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
        <div class="col-10">
            <div class="row">

                <div class="col-11">
                    @if ($FILE_PATH)
                        <a href='{{ asset('storage/' . $FILE_PATH) }}' target="_blank" class="btn btn-primary btn-xs w-25">
                            Show Docs</a>
                        @if (!$FILE_CONFIRM_DATE)
                            <label class="text-danger text-sm"> : This file has not been confirmed by the main office or
                                admin.


                            </label>
                            @can('patient.audit')
                                <button type="button" class="btn btn-info btn-xs "
                                    wire:confirm='Are you sure to confirm this document?' wire:click='confirm()'> <i
                                        class="fa fa-check" aria-hidden="true"></i> Confirm</button>
                            @endcan
                        @else
                            <label class="text-success text-sm"> : This file has been confirmed. </label>
                        @endif
                    @else
                        <div class="input-group" style="font-size: 12px; max-width: 100%;">
                            <div class="custom-file" style="font-size: 12px;">
                                <input type="file" class="custom-file-input" id="fileUpload" wire:model='PDF'
                                    style="padding: 1px; height: 30px;">
                                <label class="custom-file-label" for="fileUpload"
                                    style="padding: 1px; height: 25px; font-size: 10px;">
                                    @if ($PDF)
                                        {{ $PDF->getClientOriginalName() }}
                                    @else
                                        Choose file
                                    @endif
                                </label>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-1">
                    @if ($PDF)
                        <i class="fa fa-check-circle text-success fa-2x" aria-hidden="true"></i>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-2">

            @if (!$FILE_CONFIRM_DATE)
                @if (!$FILE_PATH)
                    <button type="button" class="btn btn-xs btn-success w-100"
                        wire:confirm='Are you sure to upload this document?' wire:click='uploading()'>Upload</button>
                @else
                    <button type="button" class="btn btn-xs btn-danger w-100"
                        wire:confirm='Are you sure to remove this document?' wire:click='removeFile()'>Remove</button>
                @endif
            @endif
        </div>
    </div>

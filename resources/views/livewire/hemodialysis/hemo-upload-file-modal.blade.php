<div>
    <button title="Upload Image Docs" wire:click="openModal()" class="btn btn-dark btn-sm text-xs">
        <i class="fa fa-upload" aria-hidden="true"></i>
    </button>
    @if ($showModal)
        @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
        <div class="modal" tabindex="-1" role="dialog" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content text-left">
                    <div class="modal-header">Upload Files</div>
                    <form wire:submit.prevent="uploadImages">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="images">Choose Images</label>
                                <input type="file" id="images" wire:model="images" multiple class="form-control">
                            </div>
                            @if ($images)
                                <div class="mt-4">
                                    <h5>Image Preview:</h5>
                                    <div class="row" style="max-height: 300px; overflow-y: auto;">
                                        @foreach ($images as $image)
                                            <div class="col-md-3 mb-4">
                                                <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail"
                                                    alt="Preview">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="form-group row">
                                @foreach ($qrCodeNotReadData as $list)
                                    @if ($list['status'] == true)
                                        <div class="text-success col-12 col-md-12 text-xs font-weight-bold">
                                            {{ $list['code'] }}</div>
                                    @else
                                        <div class="text-danger col-12 col-md-12 text-xs font-weight-bold">
                                            {{ $list['code'] }}</div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success btn-sm"
                                @if (!$images) disabled @endif>Upload</button>
                            <button type="button" wire:click='closeModal()'
                                class="btn btn-danger btn-sm">Close</button>
                        </div>
                    </form>

                    <!-- Progress Bar -->
                    <div wire:loading wire:target="uploadImages">
                        <div class="progress mt-2">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Spinner -->
    <div wire:loading wire:target="uploadImages" class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

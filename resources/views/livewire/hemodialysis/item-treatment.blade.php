<div class="row">
    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

    <div class="col-md-12">
        <input wire:model.live='search' name="search" class="form-control form-control-xs" />
    </div>
    <div class="col-md-12 mt-4">
        @foreach ($dataList as $list)
            <button wire:click='addItem({{ $list->ID }})' class="btn btn-primary btn-sm m-1">
                {{ $list->ITEM_NAME }}
            </button>
        @endforeach
    </div>


</div>

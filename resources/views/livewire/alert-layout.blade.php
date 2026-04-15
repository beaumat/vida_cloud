<div class="col-md-12">
    @if ($errors)
        <div class=" text-sm alert alert-sm alert-danger alert-dismissible" id="error-alert">
            <button type="button" class="close text-white" wire:click="dismissAlert">
                <i class="fa fa-times text-white" aria-hidden="true"></i>
            </button>
            <ul>
                @foreach ($errors as $err)
                    <li><i class="fas fa-ban"></i> {{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @elseif ($message)
        <div class="alert alert-success alert-sm  alert-dismissible" id="success-alert">
            <button type="button" class="close text-white" aria-label="Close" wire:click="dismissAlert">
                <i class="fa fa-times text-white" aria-hidden="true"></i>
            </button>
            <small><i class="icon fas fa-check"></i> {!! $message !!}</small>

        </div>
    @elseif ($error)
        <div class="pt-1 pb-1 text-sm alert alert-danger" id="session-error-alert">
            <button type="button" class="close text-white"wire:click="dismissAlert">
                <i class="fa fa-times text-white" aria-hidden="true"></i>
            </button>
            <i class="fas fa-times-circle"></i> <!-- Error icon -->
            {!! $error !!}
        </div>
    @endif
</div>

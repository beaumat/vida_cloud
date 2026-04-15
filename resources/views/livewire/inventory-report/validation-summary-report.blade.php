<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0">
                        <a href="{{ route('reportsvalidation_summry') }}">
                            Inventory Validation
                        </a>
                    </h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid bg-light">
            <div class="row">
                <div class="col-md-12">
                    @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])

                    <div class="form-group bg-light p-2 border border-secondary">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <label class="text-xs ">Date As Of :</label>
                                        <input type='date' class="form-control form-control-sm " wire:model='DATE' />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-group mt-4">
                                            <div class="row">
                                                <div class="col-6 text-right">
                                                    <div wire:loading.delay>
                                                        <span class="spinner"></span>
                                                    </div>
                                                    <button class="btn btn-sm btn-primary" wire:click='generate()'
                                                        wire:loading.attr='disabled'>Generate</button>

                                                </div>
                                                <div class="col-6">
                                                    <div wire:loading.delay>
                                                        <span class="spinner"></span>
                                                    </div>
                                                    <button class="btn btn-sm btn-success" wire:click='export()'
                                                        wire:loading.attr='disabled'>Export To Excel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">

                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-md-4">
                                        {{-- <label class="text-xs ">Payment Method:</label>
                                        <select name="paymentMethod" wire:model='PAYMENT_METHOD_ID'
                                            class="form-control form-control-sm text-xs ">
                                            <option value="0"> All Payment Method</option>
                                            @foreach ($paymentMethodList as $item)
                                                <option value="{{ $item->ID }}">{{ $item->DESCRIPTION }}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-xs ">Location:</label>
                                        <select
                                            @if (Auth::user()->locked_location) style="opacity:
                                                0.5;pointer-events: none;" @endif
                                            name="location" wire:model.live='LOCATION_ID'
                                            class="form-control form-control-sm text-xs ">
                                            <option value="0"> All Location</option>
                                            @foreach ($locationList as $item)
                                                <option value="{{ $item->ID }}">{{ $item->NAME }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" style="max-height: 80vh; overflow-y: auto;">
                    <table class="table table-sm table-bordered table-hover">
                        <thead class="text-xs bg-sky sticky-header">
                            <tr>
                                <th class="col-1 text-center">Quantity</th>
                                <th class="col-5">Item</th>
                                <th class="col-2">Category</th>
                                <th class="col-2">Sub-Category</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs"> 
    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>


@script
    <script>
        $wire.on('OpenNewTab', (eventData) => {
            window.open(eventData.data, '_blank');
        });
    </script>
@endscript

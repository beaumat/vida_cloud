<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('companystock_received') }}"> Stock Received </a></h5>
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
        <div class="container-fluid">
            <div class="row">
                @livewire('alert-layout', ['errors' => $errors->any() ? $errors->all() : '', 'message' => session('message'), 'error' => session('error')])
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="mt-0">
                                                <label class="text-sm">Search:</label>
                                                <input type="text" wire:model.live.debounce.150ms='search'
                                                    class="w-100 form-control form-control-sm" placeholder="Search" />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mt-0">
                                                <label class="text-sm">Location:</label>
                                                <select
                                                    @if (Auth::user()->locked_location) style="opacity: 0.5;pointer-events: none;" @endif
                                                    name="location" wire:model.live='locationid'
                                                    class="form-control form-control-sm">
                                                    <option value="0"> All Location</option>
                                                    @foreach ($locationList as $item)
                                                        <option value="{{ $item->ID }}"> {{ $item->NAME }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="text-xs bg-sky">
                                    <tr>
                                        <th class="col-1">Ref No.</th>
                                        <th class="col-1">Date Transfer</th>
                                        <th class="col-1">Transfer From</th>
                                        <th class="col-1">Transfer To</th>
                                        <th class="col-1">Prepared By</th>
                                        <th class="col-2">Notes</th>
                                        <th class="col-1">Created On</th>
                                        <th class="col-1">Details</th>

                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td> {{ $list->CODE }} </td>
                                            <td> {{ date('m/d/Y', strtotime($list->DATE)) }}</td>
                                            <td> {{ $list->TRANSFER_FROM }}</td>
                                            <td> {{ $list->LOCATION_NAME }}</td>
                                            <td> {{ $list->PREPARED_BY }}</td>
                                            <td> {{ $list->NOTES }}</td>
                                            <td> {{ date('m-d-Y H:i:s', strtotime($list->RECORDED_ON)) }}</td>
                                            <td> <button class="btn btn-primary btn-xs w-100"
                                                    wire:click='viewDetails({{ $list->ID }})'>View</button> </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    {{ $dataList->links() }}
                </div>
            </div>
        </div>
    </section>

    @livewire('StockTransfer.StockReceivedForm')
</div>

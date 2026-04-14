<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="m-0"><a href="{{ route('maintenanceinventoryprice_location') }}"> Price Adjustment By
                            Location </a>
                    </h5>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            <input class="form-check-input" type="checkbox" wire:model.live="isControl" />
                            @if ($isControl)
                                <label class="text-sm "><br /></label>
                                <button type="button" wire:click='showNotInclude()' class="btn btn-danger btn-xs">
                                    <i class="fa fa-download" aria-hidden="true"></i> Not Include
                                    List
                                </button>
                            @endif
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- Main content -->
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
                                        <div class="col-md-7">
                                            <div class="mt-0">
                                                <label class="text-sm">Search:</label>
                                                <input type="text" wire:model.live.debounce.120ms='search'
                                                    class="w-100 form-control form-control-sm" placeholder="Search" />
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="text-right mt-4">
                                                <input class="text-xs" type="checkbox" wire:model.live='showCost'
                                                    name="showcheckbox" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-0">
                                                <label class="text-sm">Sub Category:</label>
                                                <select name="location" wire:model.live='SUB_CLASS_ID'
                                                    class="form-control form-control-sm">
                                                    <option value="0"></option>
                                                    @foreach ($subList as $item)
                                                        <option value="{{ $item->ID }}">
                                                            {{ $item->DESCRIPTION }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mt-0">
                                                <label class="text-sm" wire:click='autoUpdate()'
                                                    wire:confirm="Are you sure you want to update price?">Location:
                                                </label>

                                                <select
                                                    @if (Auth::user()->locked_location) style="opacity:
                                                    0.5;pointer-events: none;" @endif
                                                    name="location" wire:model.live='LOCATION_ID'
                                                    class="form-control form-control-sm">
                                                    @foreach ($locationList as $item)
                                                        <option value="{{ $item->ID }}">
                                                            {{ $item->NAME }}
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
                                        <th class="col-1">Code</th>
                                        <th>Description</th>
                                        <th>Category</th>
                                        <th>Sub Category</th>
                                        <th class="col-1 text-right">Price</th>
                                        @if ($showCost)
                                            <th class="col-1 text-right">Cost</th>
                                        @endif
                                        <th class="col-2 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs">
                                    @foreach ($dataList as $list)
                                        <tr>
                                            <td>{{ $list->CODE }}</td>
                                            <td>{{ $list->DESCRIPTION }}</td>
                                            <td>{{ $list->CLASS }}</td>
                                            <td>{{ $list->SUB_CLASS }}</td>
                                            <td class="text-right">
                                                @if ($list->ID == $editId)
                                                    <input class="text-xs border border-secondary"
                                                        wire:model='editPrice' />
                                                @else
                                                    {{ number_format($list->CUSTOM_PRICE, 2) }}
                                                @endif
                                            </td>
                                            @if ($showCost)
                                                <td class="text-right">
                                                    @if ($list->ID == $editId)
                                                        <input class="text-xs border border-secondary"
                                                            wire:model='editCost' />
                                                    @else
                                                        {{ number_format($list->CUSTOM_COST, 2) }}
                                                    @endif
                                                </td>
                                            @endif
                                            <td class="text-center">
                                                @if ($list->ID == $editId)
                                                    <button name="btnsave_{{ $list->ID }}"
                                                        class="btn btn-xs btn-success" wire:click='save()'>
                                                        <i class="fa fa-floppy-o" aria-hidden="true"></i> Save
                                                    </button>
                                                    <button name="btncancel_{{ $list->ID }}"
                                                        class="btn btn-xs btn-warning" wire:click='cancel()'>
                                                        <i class="fa fa-ban" aria-hidden="true"></i> Cancel
                                                    </button>
                                                @else
                                                    <button name="btnedit_{{ $list->ID }}"
                                                        class="btn btn-xs btn-info"
                                                        wire:click='edit({{ $list->ID }})'>
                                                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                                    </button>
                                                    @if ($isControl)
                                                        <button type="button" class="btn btn-xs btn-danger"
                                                            title="Not Include in your list"
                                                            wire:click='itemNotInclude({{ $list->ID }})'
                                                            title="Not Include">
                                                            <i class="fas fa-times" aria-hidden="true"></i> Not
                                                            Include
                                                        </button>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    @livewire('PriceLocation.NotIncludeList')

</div>
